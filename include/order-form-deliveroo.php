<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


require_once('php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();


// Form Fields
$name = isset($_POST["widget-contact-form-name"]) ? $_POST["widget-contact-form-name"] : null;
$firstName = isset($_POST["widget-contact-form-firstName"]) ? $_POST["widget-contact-form-firstName"] : null;
$email = isset($_POST["widget-contact-form-email"]) ? $_POST["widget-contact-form-email"] : null;
$phone = isset($_POST["widget-contact-form-phone"]) ? $_POST["widget-contact-form-phone"] : null;
$bike = isset($_POST["widget-contact-form-velo"]) ? $_POST["widget-contact-form-velo"] : null;
$type = isset($_POST["widget-contact-form-type"]) ? $_POST["widget-contact-form-type"] : null;
$antispam = $_POST['widget-contact-form-antispam'];
$captcha = strlen($_POST['g-recaptcha-response']);


$length = strlen($phone);
if ($length<8 or $length>12) {
	errorMessage("ES0004");
}

if($captcha == 0){
    //errorMessage("ES0020");
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $name != null && $firstName != null  && $email != null && $bike != null && $type != null && isset($antispam)) {
            
                //If you don't receive the email, enable and configure these parameters below: 
     
                //$mail->isSMTP();                                      // Set mailer to use SMTP
                //$mail->Host = 'mail.yourserver.com';                  // Specify main and backup SMTP servers, example: smtp1.example.com;smtp2.example.com
                //$mail->SMTPAuth = true;                               // Enable SMTP authentication
                //$mail->Username = 'SMTP username';                    // SMTP username
                //$mail->Password = 'SMTP password';                    // SMTP password
                //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                //$mail->Port = 587;                                    // TCP port to connect to 
     
     	        $mail->IsHTML(true);                                    // Set email format to HTML
                $mail->CharSet = 'UTF-8';
     			
				$mail->AddAddress('julien@kameobikes.com', 'Julien Jamar');
				$mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');

                $mail->From = $email;
                $mail->FromName = $firstName.' '.$name;
                $mail->AddReplyTo($email, $name);
                $mail->Subject = 'Commande d\'un nouveau vélo pour Deliveroo';
          
                $name = isset($name) ? "Nom: $name<br><br>" : '';
				$firstName = isset($firstName) ? "Prenom: $firstName<br><br>" : '';
                $email = isset($email) ? "Email: $email<br><br>" : '';
                $phone = isset($phone) ? "Phone: $phone<br><br>" : '';
                $bike = isset($bike) ? "Velo: $bike <br><br>" : '';
                $type = isset($type) ? "Type de financement: $type <br><br>" : '';

                $mail->Body = $name . $firstName . $email . $phone . $bike . $type;
     
                         
        if(!$mail->Send()) {
		   $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);  
		}else {
           $response = array ('response'=>'success');  
        }
     echo json_encode($response);

} else {
    errorMessage("ESOO25");
}
?>
