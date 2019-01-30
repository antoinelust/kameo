<?php

require_once dirname(__FILE__).'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
ob_start();
$monthFR=array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');


function requireToVar($file){
    ob_start();
    require($file);
    return ob_get_clean();
}



include 'include/globalfunctions.php';

include 'include/connexion.php';
$sql= "select * from companies where STAANN!='D'";

if ($conn->query($sql) === FALSE) {
    echo $conn->error;
    die;
}

$result = mysqli_query($conn, $sql);     
$length = $result->num_rows;
$i=0;

while($row = mysqli_fetch_array($result))
{
    $internalReference=$row['INTERNAL_REFERENCE'];
    $currentDate=date('Y-m-d');
    $sql_dateStart="select min(CONTRACT_START), COMPANY from customer_bikes where CONTRACT_START<'$currentDate' and CONTRACT_END>'$currentDate' group by company";
    if ($conn->query($sql_dateStart) === FALSE) {
        echo $conn->error;
        die;
    }
    $result_dateStart = mysqli_query($conn, $sql_dateStart);   
    $length=$result_dateStart->num_rows;
    if($length > 0)
    {
        $resultat_dateStart = mysqli_fetch_assoc($result_dateStart);
        $firstDay=substr($resultat_dateStart['min(CONTRACT_START)'], 8, 2);
        $today=substr($currentDate, 8 ,2);

        if($today==$firstDay)
        {
            $file = __DIR__.'/temp/company.txt';
            $myfile = fopen($file, "w")  or die("Unable to open file!");
            fwrite($myfile, $internalReference);
            fclose($myfile);

            $test=requireToVar(__DIR__.'/facture.php');
            $file = 'facture'.$i.'.php';
            $myfile = fopen($file, "w")  or die("Unable to open file!");
            fwrite($myfile, $test);
            fclose($myfile);

            try {
                include dirname(__FILE__).'/'.$file;
                $content = ob_get_clean();
                $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
                $html2pdf->pdf->SetDisplayMode('fullpage');
                $html2pdf->writeHTML($content);
                $path='/factures/'.$company.$monthFR[(date('n')-1)].date('Y').'.pdf';
                $html2pdf->Output(__DIR__ . $path, 'F');

                //$html2pdf->output('example01.pdf');

            } catch (Html2PdfException $e) {
                $html2pdf->clean();
                $formatter = new ExceptionFormatter($e);
                echo $formatter->getHtmlMessage();
            }              


            $sql3="select EMAIL_CONTACT, NOM_CONTACT, PRENOM_CONTACT from companies where INTERNAL_REFERENCE='$COMPANY'";
            if ($conn->query($sql3) === FALSE) {
                echo $conn->error;
                die;
            }
            $result3 = mysqli_query($conn, $sql3);   
            $resultat3 = mysqli_fetch_assoc($result3);


            $emailContact=$resultat3['EMAIL_CONTACT'];
            $nameContact=$resultat3['NOM_CONTACT'];
            $firstnameContact=$resultat3['PRENOM_CONTACT'];

            require_once('include/php-mailer/PHPMailerAutoload.php');
            $mail = new PHPMailer();
            $mail->IsHTML(true);                                    // Set email format to HTML
            $mail->CharSet = 'UTF-8';

            $mail->AddAddress('antoine.lust@kameobikes.com', 'Antoine Lust');

            $mail->From = 'julien.jamar@kameobikes.com';
            $mail->FromName = 'Julien Jamar';
            $mail->AddReplyTo('julien.jamar@kameobikes.com', 'Julien Jamar');
            $mail->Subject = 'Kameo Bikes - Facture de '.$monthFR[(date('n')-1)].' '.date('Y');

            $temp=$monthFR[(date('n')-1)].' '.date('Y');
            $message="Bonjour,<br><br>

            Veuillez trouver en pièce jointe la facture Kameo Bikes pour le mois de $temp.<br>
            Pour toute question, n'hésitez pas à nous contacter.<br><br>

            Bien à vous,<br><br>

            L'équipe Kameo Bikes";

            $file_to_attach = __DIR__ .$path;
            $FileName = $company.$monthFR[(date('n')-1)].date('Y').'.pdf';

            $mail->AddAttachment( $file_to_attach , $FileName );

            $mail->Body = $message;

            if(!$mail->Send()) {
               echo error_get_last()['message'];  

            }else {
               echo 'mail envoyé';
            }



        }            
    }

}

?>

