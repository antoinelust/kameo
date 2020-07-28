<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

require_once('../../include/php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();



// Form Fields
$id = $_POST["id"];
$firstNameContactBilling = $_POST["firstName"];
$lastNameContactBilling = $_POST["lastName"];
$emailContactBilling = $_POST["email"];
$fileName = $_POST["fileName"];
$date=new DateTime($_POST['date']);

$monthFR=array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');

include 'connexion.php';
$sql="select * from factures where ID='$id'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);   
$resultat = mysqli_fetch_assoc($result);
$conn->close();

$internalReference=$resultat['COMPANY'];
$billingGroup=$resultat['BILLING_GROUP'];




include 'connexion.php';
$sql="select * from companies where INTERNAL_REFERENCE='$internalReference' and BILLING_GROUP='$billingGroup'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);   
$resultat = mysqli_fetch_assoc($result);
$conn->close();


$companyName=$resultat['COMPANY_NAME'];

require_once('../../include/php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();
$mail->IsHTML(true);                                    // Set email format to HTML
$mail->CharSet = 'UTF-8';


$mail->From = 'info@kameobikes.com';
$mail->FromName = 'Information Kameo Bikes';
$mail->AddReplyTo('info@kameobikes.com', 'Information Kameo Bikes');
$mail->Subject = 'Kameo Bikes - '. $companyName .' - Facture de '.$monthFR[($date->format('m')-1)].' '.$date->format('Y');

$temp=$monthFR[($date->format('m')-1)].' '.$date->format('Y');
$message="Bonjour,<br><br>

Veuillez trouver en pièce jointe la facture Kameo Bikes pour le mois de $temp.<br>
Pour toute question, n'hésitez pas à nous contacter.<br><br>

Bien à vous,<br><br>

L'équipe Kameo Bikes";

$file_to_attach = "../factures/".$fileName;


if(substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
    $mail->AddAttachment( $file_to_attach , $fileName );
}
$mail->Body = $message;
if(substr($_SERVER['REQUEST_URI'], 1, 4) != "test" && substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
    $mail->AddAddress($emailContactBilling, $lastNameContactBilling." ".$firstNameContactBilling);
    $mail->AddBCC("antoine@kameobikes.com", "Antoine Lust");
    $mail->AddBCC("julien@kameobikes.com", "Julien Jamar");                        
}else{
    $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
}


if(substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
    if(!$mail->Send()) {
       $response=array();
       $response['response']="error";  
       $response['message']=error_get_last()['message'];  
    }else{
        $now=new DateTime('now');
        $nowString=$now->format('Y-m-d H:i');
        include 'connexion.php';
        $sql="update factures set FACTURE_SENT = '1', FACTURE_SENT_DATE='$nowString' WHERE ID='$id'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);   
        $conn->close();
        successMessage("SM0026");
    }
}else{
		$response = array ('response'=>'success', 'message'=> "Société ".$companyName."<br><strong>environnement localhost, mail non envoyé</strong>");
		echo json_encode($response);
		die;    
}





?>
