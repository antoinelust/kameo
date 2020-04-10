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

if(isset($_GET['month'])){
    $month=$_GET['month'];
}else{
    $month=null;
}

if(isset($_GET['year'])){
    $year=$_GET['year'];
}else{
    $year=null;
}
if(isset($_GET['simulation'])){
    $simulation=$_GET['simulation'];
}else{
    $simulation=null;
}



$monthFR=array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');


function requireToVar($file){
    ob_start();
    require($file);
    return ob_get_clean();
}



include 'include/globalfunctions.php';

include 'include/connexion.php';
$sql= "SELECT * FROM ((select COMPANY, BILLING_GROUP from customer_bikes WHERE AUTOMATIC_BILLING='Y') UNION (SELECT COMPANY, BILLING_GROUP FROM boxes WHERE AUTOMATIC_BILLING='Y')) as T1";


if(isset($company)){
    $sql=$sql." WHERE COMPANY='$company'";
}

error_log("SQL1:".$sql."\n", 3, "generate_invoices.log");    


if ($conn->query($sql) === FALSE) {
    echo $conn->error;
    die;
}


$result = mysqli_query($conn, $sql);     
$i=0;

while($row = mysqli_fetch_array($result))
{
    $internalReference=$row['COMPANY'];
    $currentDate=date('Y-m-d');
    $billingGroup=$row['BILLING_GROUP'];
    $sql_dateStart="SELECT * from ((select min(CONTRACT_START) as 'start', COMPANY, BILLING_GROUP from customer_bikes aa where aa.CONTRACT_START<='$currentDate' and (aa.CONTRACT_END>'$currentDate' or aa.CONTRACT_END is NULL) and aa.COMPANY='$internalReference' and aa.AUTOMATIC_BILLING='Y' and aa.BILLING_GROUP='$billingGroup') UNION (select min(START ) as 'start', COMPANY, BILLING_GROUP from boxes aa where aa.START<='$currentDate' and (aa.END>'$currentDate' or aa.END is NULL) and aa.COMPANY='$internalReference' and aa.AUTOMATIC_BILLING='Y' and aa.BILLING_GROUP='$billingGroup')) AS T1 WHERE start is NOT NULL";
    error_log("SQL2 :".$sql_dateStart."\n", 3, "generate_invoices.log");    
    
    
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
                
        if($resultat_dateStart['start'])
        {
            $firstDay=substr($resultat_dateStart['start'], 8, 2);
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

                if($date){
                    $file = __DIR__.'/temp/date.txt';
                    $myfile = fopen($file, "w")  or die("Unable to open file!");
                    fwrite($myfile, $date);
                    fclose($myfile);            
                }
                if($month){
                    $file = __DIR__.'/temp/month.txt';
                    $myfile = fopen($file, "w")  or die("Unable to open file!");
                    fwrite($myfile, $month);
                    fclose($myfile);            
                }
                if($year){
                    $file = __DIR__.'/temp/year.txt';
                    $myfile = fopen($file, "w")  or die("Unable to open file!");
                    fwrite($myfile, $year);
                    fclose($myfile);            
                }
                if($simulation){
                    $file = __DIR__.'/temp/simulation.txt';
                    $myfile = fopen($file, "w")  or die("Unable to open file!");
                    fwrite($myfile, $simulation);
                    fclose($myfile);            
                }
                
                
                
                $test=requireToVar(__DIR__.'/facture.php');
                $file = 'facture'.$i.'.php';
                $myfile = fopen(__DIR__.'/'.$file, "w")  or die("Unable to open file!");
                fwrite($myfile, $test);
                fclose($myfile);
                
                include 'include/connexion.php';
                $sql_reference="select max(ID) as MAX_TOTAL, max(ID_OUT_BILL) as MAX_OUT from factures";
                if ($conn->query($sql_reference) === FALSE) {
                    echo $conn->error;
                    die;
                }
                $result_reference = mysqli_query($conn, $sql_reference);   
                $resultat_reference = mysqli_fetch_assoc($result_reference);
                $newID=$resultat_reference['MAX_TOTAL'];
                $newIDOUT=$resultat_reference['MAX_OUT'];
                
                
                
                try {
                    include dirname(__FILE__).'/'.$file;
                    $content = ob_get_clean();
                    $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $path='/factures/'.date('Y').'.'.date('m').'.'.date('d').'_'.$internalReference.'_'.$newID.'_facture_'.$newIDOUT.'.pdf';
                    $html2pdf->Output(__DIR__ . $path, 'F');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
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
                if(substr($_SERVER['REQUEST_URI'], 1, 4) != "test" && substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
                    if(!$simulation || $simulation=='N'){
                        if($resultat3['BILLS_SENDING'] == "Y" && $emailContactBilling != "" && $lastNameContactBilling != ""){
                            $mail->AddAddress($emailContactBilling, $lastNameContactBilling." ".$firstNameContactBilling);
                            $mail->AddBCC("antoine@kameobikes.com", "Antoine Lust");
                            $mail->AddBCC("julien@kameobikes.com", "Julien Jamar");                        
                        }else{
                            $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
                        }
                    }else{
                        $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
                    }
                    
                    if(!$mail->Send()) {
                       echo error_get_last()['message'];  

                    }else {
                       echo 'mail envoyé';
                    }    
                }else if(substr($_SERVER['REQUEST_URI'], 1, 4) == "test"){
                    $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
                    
                    if(!$mail->Send()) {
                       echo error_get_last()['message'];  

                    }else {
                       echo 'mail envoyé';
                    }    
                }else{
                    echo '<br>Société '.$companyName.'<br><strong>environnement localhost, mail non envoyé</strong><br>';
                }

                /*$file = __DIR__.'/temp/company.txt';
                if ((file_exists($file))){
                    unlink($file);
                }
                $file = __DIR__.'/temp/billingGroup.txt';
                if ((file_exists($file))){
                    unlink($file);
                }
                
                $file = __DIR__.'/temp/date.txt';
                if ((file_exists($file))){
                    unlink($file);
                }
                $file = __DIR__.'/temp/month.txt';
                if ((file_exists($file))){
                    unlink($file);
                }
                $file = __DIR__.'/temp/year.txt';
                if ((file_exists($file))){
                    unlink($file);
                }
                $file = __DIR__.'/temp/simulation.txt';
                if ((file_exists($file))){
                    unlink($file);
                }

                $file = __DIR__.'/facture'.$i.'.php';;
                if ((file_exists($file))){
                    unlink($file);
                }*/

            }            
        }
        if (ob_get_contents()) ob_end_clean();
        
    }
}

?>

