<?php

require_once dirname(__FILE__).'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
ob_start();


if(isset($_GET['company'])){
    $company=$_GET['company'];
}

if(isset($_GET['date'])){
    $date=$_GET['date'];
}else{
    $date=null;
}



$monthFR=array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');


function requireToVar($file){
    ob_start();
    require($file);
    return ob_get_clean();
}



include 'include/globalfunctions.php';

include 'include/connexion.php';
$sql= "select COMPANY, BILLING_GROUP from customer_bikes WHERE LEASING='Y'";

if(isset($company)){
    $sql=$sql."AND COMPANY='$company'";
}
if ($conn->query($sql) === FALSE) {
    echo $conn->error;
    die;
}
$sql=$sql." GROUP BY COMPANY, BILLING_GROUP";
$result = mysqli_query($conn, $sql);     
$i=0;

while($row = mysqli_fetch_array($result))
{
    $internalReference=$row['COMPANY'];
    $currentDate=date('Y-m-d');
    $billingGroup=$row['BILLING_GROUP'];
    $sql_dateStart="select min(CONTRACT_START), COMPANY, BILLING_GROUP from customer_bikes where CONTRACT_START<='$currentDate' and CONTRACT_END>'$currentDate' and COMPANY='$internalReference' and LEASING='Y' and BILLING_GROUP='$billingGroup'";
    if ($conn->query($sql_dateStart) === FALSE) {
        echo $conn->error;
        die;
    }
    $result_dateStart = mysqli_query($conn, $sql_dateStart);   
    $length=$result_dateStart->num_rows;
    while($resultat_dateStart = mysqli_fetch_array($result_dateStart)){
        if (ob_get_contents()) ob_end_clean();        
        ob_start();
        $billingGroup=$resultat_dateStart['BILLING_GROUP'];
        
        if($resultat_dateStart['min(CONTRACT_START)'])
        {
            $firstDay=substr($resultat_dateStart['min(CONTRACT_START)'], 8, 2);
            $today=substr($currentDate, 8 ,2);
            

            if($today==$firstDay || $firstDay==$date)
            {
                
                $sql_companyDetails="select COMPANY_NAME from companies where INTERNAL_REFERENCE='$internalReference' and BILLING_GROUP='$billingGroup'";
                if ($conn->query($sql_companyDetails) === FALSE) {
                    echo $conn->error;
                    die;
                }
                $result_companyDetails = mysqli_query($conn, $sql_companyDetails);   
                $resultat_companyDetails = mysqli_fetch_assoc($result_companyDetails);
                $companyName=$resultat_companyDetails['COMPANY_NAME'];
                
                $file = __DIR__.'/temp/company.txt';
                $myfile = fopen($file, "w")  or die("Unable to open file!");
                fwrite($myfile, $internalReference);
                fclose($myfile);
                
                $file = __DIR__.'/temp/billingGroup.txt';
                $myfile = fopen($file, "w")  or die("Unable to open file!");
                fwrite($myfile, $billingGroup);
                fclose($myfile);            

                $test=requireToVar(__DIR__.'/facture.php');
                $file = 'facture'.$i.'.php';
                $myfile = fopen(__DIR__.'/'.$file, "w")  or die("Unable to open file!");
                fwrite($myfile, $test);
                fclose($myfile);

                if(substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
                    try {
                        include dirname(__FILE__).'/'.$file;
                        $content = ob_get_clean();
                        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
                        $html2pdf->pdf->SetDisplayMode('fullpage');
                        $html2pdf->writeHTML($content);
                        $path='/factures/'.date('Y').'.'.date('m').'.'.date('d').'_'.$internalReference.'_'.$billingGroup.'.pdf';;
                        $html2pdf->Output(__DIR__ . $path, 'F');


                    } catch (Html2PdfException $e) {
                        $html2pdf->clean();
                        $formatter = new ExceptionFormatter($e);
                        echo $formatter->getHtmlMessage();
                    }  
                }

                include 'include/connexion.php';
                $sql3="select EMAIL_CONTACT, NOM_CONTACT, PRENOM_CONTACT, EMAIL_CONTACT_BILLING, FIRSTNAME_CONTACT_BILLING, LASTNAME_CONTACT_BILLING, PHONE_CONTACT_BILLING, BILLS_SENDING from companies where INTERNAL_REFERENCE='$internalReference' and BILLING_GROUP='$billingGroup'";
                if ($conn->query($sql3) === FALSE) {
                    echo $conn->error;
                    die;
                }
                $result3 = mysqli_query($conn, $sql3);   
                $resultat3 = mysqli_fetch_assoc($result3);


                $emailContact=$resultat3['EMAIL_CONTACT'];
                $emailContactBilling=$resultat3['EMAIL_CONTACT_BILLING'];
                $nameContact=$resultat3['NOM_CONTACT'];
                $firstnameContact=$resultat3['PRENOM_CONTACT'];
                $firstNameContactBilling=$resultat3['FIRSTNAME_CONTACT_BILLING'];
                $lastNameContactBilling=$resultat3['LASTNAME_CONTACT_BILLING'];

                require_once('include/php-mailer/PHPMailerAutoload.php');
                $mail = new PHPMailer();
                $mail->IsHTML(true);                                    // Set email format to HTML
                $mail->CharSet = 'UTF-8';


                $mail->From = 'info@kameobikes.com';
                $mail->FromName = 'Information Kameo Bikes';
                $mail->AddReplyTo('info@kameobikes.com', 'Information Kameo Bikes');
                $mail->Subject = 'Kameo Bikes - '. $companyName .' - Facture de '.$monthFR[(date('n')-1)].' '.date('Y');

                $temp=$monthFR[(date('n')-1)].' '.date('Y');
                $message="Bonjour,<br><br>

                Veuillez trouver en pièce jointe la facture Kameo Bikes pour le mois de $temp.<br>
                Pour toute question, n'hésitez pas à nous contacter.<br><br>

                Bien à vous,<br><br>

                L'équipe Kameo Bikes";

                $file_to_attach = __DIR__ .$path;
                $FileName = date('Y').'.'.date('m').'.'.date('d').'_'.$internalReference.'_'.$billingGroup.'.pdf';

                if(substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
                    $mail->AddAttachment( $file_to_attach , $FileName );
                }

                $mail->Body = $message;
                if(substr($_SERVER[REQUEST_URI], 1, 4) != "test" && substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
                    
                    if($resultat3['BILLS_SENDING'] == "Y" && $emailContactBilling != "" && $lastNameContactBilling != ""){
                        $mail->AddAddress($emailContactBilling, $lastNameContactBilling." ".$firstNameContactBilling);
                        $mail->AddBCC("antoine.lust@kameobikes.com", "Antoine Lust");
                        $mail->AddBCC("julien.jamar@kameobikes.com", "Julien Jamar");
                        
                    }else{
                        $mail->AddAddress('antoine.lust@kameobikes.com', 'Antoine Lust');
                    }
                    
                    if(!$mail->Send()) {
                       echo error_get_last()['message'];  

                    }else {
                       echo 'mail envoyé';
                    }    
                }else if(substr($_SERVER[REQUEST_URI], 1, 4) == "test"){
                    $mail->AddAddress('antoine.lust@kameobikes.com', 'Antoine Lust');
                    
                    if(!$mail->Send()) {
                       echo error_get_last()['message'];  

                    }else {
                       echo 'mail envoyé';
                    }    
                }else{
                    echo '<br>Société '.$companyName.'<br><strong>environnement localhost, mail non envoyé</strong><br>';
                }

                $file = __DIR__.'/temp/company.txt';
                unlink($file);
                $file = __DIR__.'/temp/billingGroup.txt';
                unlink($file); 

                $file = __DIR__.'/facture'.$i.'.php';;
                unlink($file); 

            


            }            
        }
        if (ob_get_contents()) ob_end_clean();
        
    }
}

?>

