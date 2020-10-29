<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

require_once('../../include/php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();



// Form Fields
$name = isset($_POST["widget-offer-name"]) ? htmlspecialchars($_POST["widget-offer-name"]) : "N/A";
$firstName = isset($_POST["widget-offer-firstName"]) ? htmlspecialchars($_POST["widget-offer-firstName"]) : "N/A";
$email = isset($_POST["widget-offer-email"]) ? htmlspecialchars($_POST["widget-offer-email"]) : "N/A";
$brand = isset($_POST["widget-offer-brand"]) ? htmlspecialchars($_POST["widget-offer-brand"]) : "N/A";
$model = isset($_POST["widget-offer-model"]) ? htmlspecialchars($_POST["widget-offer-model"]) : "N/A";
$frameType = isset($_POST["widget-offer-frame-type"]) ? htmlspecialchars($_POST["widget-offer-frame-type"]) : "N/A";
$phone = isset($_POST["widget-offer-phone"]) ? htmlspecialchars($_POST["widget-offer-phone"]) : null;
$subject = "Demande de commande pour le vélo ".$brand." ".$model." par ".$firstName." ".$name;
$velo = $_POST["widget-offer-brand"].' '.$_POST["widget-offer-model"].' '.$_POST["widget-offer-frame-type"];
$leasing = isset($_POST["widget-offer-leasing"]) ? htmlspecialchars($_POST["widget-offer-leasing"]) : "N/A";
$antispam = htmlspecialchars($_POST['widget-offer-antispam']);


$length = strlen($phone);
if ($length<8 or $length>12) {
	errorMessage("ES0004");
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($antispam) && $antispam == '') {
    
 if($email != '' && $phone != '' && $velo != '' && $leasing != '') {
            

        $mail->IsHTML(true);                                    // Set email format to HTML
        $mail->CharSet = 'UTF-8';
        
        $mail->AddAddress('julien@kameobikes.com', 'Julien Jamar');
        $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');

        $mail->From = $email;
        $mail->FromName = $firstName.' '.$name;
        $mail->AddReplyTo($email, $name);
        $mail->Subject = $subject;
        
        /*$name = isset($name) ? "Nom: $name<br><br>" : '';
        $firstName = isset($firstName) ? "Prenom: $firstName<br><br>" : '';
        $email = isset($email) ? "Email: $email<br><br>" : '';
        $phone = isset($phone) ? "Phone: $phone<br><br>" : '';
        $velo = isset($velo) ? "Vélo: $velo <br><br>" : '';
        $leasing = isset($leasing) ? "Solution de financement demandée: $leasing <br><br>" : '';*/

        $mail->Body = $name . $firstName . $email . $phone . $velo . $leasing;

        include 'connexion.php';
    
    $sql = "INSERT INTO companies(USR_MAJ, COMPANY_NAME, AUDIENCE,BILLING_GROUP,STREET,ZIP_CODE,TOWN,VAT_NUMBER,INTERNAL_REFERENCE,EMAIL_CONTACT,NOM_CONTACT, PRENOM_CONTACT,TYPE,AUTOMATIC_STATISTICS,BILLS_SENDING,STAANN) SELECT '$email','$firstName"." "."$name', 'B2B',1,' ',0,' ','/','$firstName"." "."$name',' ','$name ','$firstName','Prospect','N','N','N' FROM DUAL WHERE NOT EXISTS(SELECT COMPANY_NAME FROM companies WHERE COMPANY_NAME='$firstName"." "."$name')";
        
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
        $conn->close();

        if(constant('ENVIRONMENT')=="test" || constant('ENVIRONMENT')=="production"){
            if(!$mail->Send()) {
            $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);  
                
            }else {
            $response = array ('response'=>'success');  
            }
        }else{
            $response = array ('response'=>'success', 'message'=> "Environnement local, mail non envoyé");
        }
        echo json_encode($response);
        die;

} else {
	$response = array ('response'=>'error');     
    echo json_encode($response);
    die;
}
    
}
?>
