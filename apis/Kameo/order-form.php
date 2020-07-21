<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

require_once('../../include/php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();



// Form Fields
$name = $_POST["widget-contact-form-name"];
$firstName = $_POST["widget-contact-form-firstName"];


$email = $_POST["widget-contact-form-email"];
$company = $_POST["widget-contact-form-entreprise"];
$phone = isset($_POST["widget-contact-form-phone"]) ? $_POST["widget-contact-form-phone"] : null;
$subject = isset($_POST["widget-contact-form-subject"]) ? $_POST["widget-contact-form-subject"] : 'Nouveau message - Commande';
$message = $_POST["widget-contact-form-message"];
$antispam = $_POST['widget-contact-form-antispam'];
$captcha = strlen($_POST['g-recaptcha-response']);


$length = strlen($phone);
/** @TODO VERIFY NO LETTER OR SPECIAL CHAR **/
if ($length<8 or $length>12) {
	errorMessage("ES0004");
}

if($captcha == 0){
    errorMessage("ES0020");
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($antispam) && $antispam == '') {
    
 if($email != '' && $message != '') {
                 
     	        $mail->IsHTML(true);                                    // Set email format to HTML
                $mail->CharSet = 'UTF-8';
     			
				$mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');

                $mail->From = $email;
                $mail->FromName = $firstName.' '.$name;
                $mail->AddReplyTo($email, $name);
                $mail->Subject = $subject;
          
                $name = isset($name) ? "Nom: $name<br><br>" : '';
				$firstName = isset($firstName) ? "Prenom: $firstName<br><br>" : '';
                $email = isset($email) ? "Email: $email<br><br>" : '';
                $phone = isset($phone) ? "Phone: $phone<br><br>" : '';
                $company = isset($company) ? "Société: $company<br><br>" : '';
                $message = isset($message) ? "Message: $message <br><br>" : '';

                $mail->Body = $name . $firstName . $email . $phone . $message;
     
                         
        if(!$mail->Send()) {
		   $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);  
            
		}else {
           $response = array ('response'=>'success');  
        }
     echo json_encode($response);

} else {
	$response = array ('response'=>'error');     
	echo json_encode($response);
}
    
}
?>