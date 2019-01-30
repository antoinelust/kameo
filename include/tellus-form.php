<?php
session_start();
include 'globalfunctions.php';

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


// Form Fields
$user = $_SESSION['userID'];

if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    
 if($user != '') {
            
    include 'connexion.php';
	$sql = "select * from customer_referential where EMAIL='$user'";
	$result = mysqli_query($conn, $sql);
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$row = mysqli_fetch_assoc($result);
	$conn->close();
	



	require_once('php-mailer/PHPMailerAutoload.php');
	$mail = new PHPMailer();

	$mail->IsHTML(true);                                    // Set email format to HTML
	$mail->CharSet = 'UTF-8';
	
/* 	$mail->AddAddress('thibaut.mativa@kameobikes.com', 'Thibaut Mativa');
	$mail->AddAddress('julien.jamar@kameobikes.com', 'Julien Jamar');
	$mail->AddAddress('julien.jamardebolse@gmail.com', 'Julien Jamar De Bolsee'); */
	$mail->AddAddress('antoine.lust@kameobikes.com', 'Antoine Lust');


	$firstName=$row["PRENOM"];
	$name=$row["NOM"];
	$phone=$row["PHONE"];
	$frameNumber=$row["FRAME_NUMBER"];	
	$sujet = $_POST["widget-tellus-form-subject"];	
	$message = $_POST["widget-tellus-form-message"];

    $mail->From = $user;
	$mail->FromName = $firstName.' '.$name;
	$mail->AddAddress($sendmail);								  
	$mail->AddReplyTo($email, $name);
	
	
	$subject = 'Tell us what you fell - bike '.$frameNumber;
	$mail->Subject = $subject;
	
    $sujet = isset($sujet) ? "<strong>Sujet</strong> : $sujet<br><br>" : '';
    $message = isset($message) ? "<strong>Message</strong> : $message <br><br>" : '';
	
	$name = isset($name) ? "<hr> <br>Nom: $name<br><br>" : '';
	$firstName = isset($firstName) ? "Prenom: $firstName<br><br>" : '';
	$email = isset($user) ? "Email: $user<br><br>" : '';
	$phone = isset($phone) ? "Numero de telephone: $phone<br><br>" : '';
	$frameNumber = isset($frameNumber) ? "Numéro de cadre: $frameNumber<br><br>" : '';

	
	$mail->Body = $sujet . $message . $name . $firstName . $email . $phone . $frameNumber;

	if(!$mail->Send()) {
		$response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);  
		echo json_encode($response);
		die;
	}
	if ($_SESSION['langue'] == "fr"){
		$response = array ('response'=>'success', 'message'=> "Merci d'avoir partagé vos impressions ! Si nécessaire, nous vous recontacterons dans les plus brefs délais.");  
	} elseif ($_SESSION['langue'] == "nl"){
		$response = array ('response'=>'success', 'message'=> "Bedankt voor het delen van je gevoelens! Indien nodig nemen we zo snel mogelijk contact met u op.");
	} else{
		$response = array ('response'=>'success', 'message'=> "Thank you for sharing your feelings! If necessary, we will contact you as soon as possible.");  
	}	
	echo json_encode($response);
	
} else {
	 errorMessage(ES0008);
}
    
}
?>
