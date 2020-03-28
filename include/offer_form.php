<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

require_once('php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();



// Form Fields
$name = $_POST["widget-offer-name"];
$firstName = $_POST["widget-offer-firstName"];
$email = $_POST["widget-offer-email"];
$brand = $_POST["widget-offer-brand"];
$model = $_POST["widget-offer-model"];
$frameType = $_POST['widget-offer-frame-type'];
$phone = isset($_POST["widget-offer-phone"]) ? $_POST["widget-offer-phone"] : null;
$subject = "Demande de commande pour le vélo ".$brand." ".$model." par ".$firstName." ".$name;
$velo = $_POST["widget-offer-brand"].' '.$_POST["widget-offer-model"].' '.$_POST["widget-offer-frame-type"];
$leasing = $_POST["widget-offer-leasing"];
$antispam = $_POST['widget-offer-antispam'];


$length = strlen($phone);
if ($length<8 or $length>12) {
	errorMessage(ES0004);
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
          
                $name = isset($name) ? "Nom: $name<br><br>" : '';
				$firstName = isset($firstName) ? "Prenom: $firstName<br><br>" : '';
                $email = isset($email) ? "Email: $email<br><br>" : '';
                $phone = isset($phone) ? "Phone: $phone<br><br>" : '';
                $velo = isset($velo) ? "Vélo: $velo <br><br>" : '';
                $leasing = isset($leasing) ? "Solution de financement demandée: $leasing <br><br>" : '';

                $mail->Body = $name . $firstName . $email . $phone . $velo . $leasing;
     
                         
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
