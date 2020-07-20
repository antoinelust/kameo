<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

include 'globalfunctions.php';

if(!isset($_SESSION))
{
    session_start();
}


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
	
	if(isset($_FILES['widget-entretien-form-message-attachment']))
	{ 
		$dossier = '../upload/entretien/';
		
		$extensions = array('.png', '.jpg', '.jpeg');
		$extension = strrchr($_FILES['widget-entretien-form-message-attachment']['name'], '.');
		if(!in_array($extension, $extensions))
		{
			  errorMessage("ES0022");
		}

		 
		$taille_maxi = 6291456;
		$taille = filesize($_FILES['widget-entretien-form-message-attachment']['tmp_name']);
		if($taille>$taille_maxi)
		{
			  errorMessage("ES0023");
		}
		
		$today = getdate();

		
		$fichier = $user.'-'.$today['mday'].$today['mon'].$today['year'].$today['hours'].$today['minutes'].$extension;
		 
		 if(move_uploaded_file($_FILES['widget-entretien-form-message-attachment']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
		 {
			$upload=true;
			$path= $dossier . $fichier;
		 }
		 else
		 {
			  errorMessage("ES0024");
		 }
	}



	require_once('../../include/php-mailer/PHPMailerAutoload.php');
	$mail = new PHPMailer();

	$mail->IsHTML(true);                                    // Set email format to HTML
	$mail->CharSet = 'UTF-8';
	
 	$mail->AddAddress('thibaut@kameobikes.com', 'Thibaut Mativa');
	$mail->AddAddress('julien@kameobikes.com', 'Julien Jamar');
	$mail->AddAddress('pierre-yves@kameobikes.com', 'Pierre-Yves Adant');
	$mail->AddAddress('julien@kameobikes.com', 'Julien Jamar De Bolsee');
	$mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');

	$firstName=$row["PRENOM"];
	$name=$row["NOM"];
	$phone=$row["PHONE"];
     
    $frameNumber=$_POST['widget-entretien-form-frame-number'];
	$bikePart=$_POST["widget-entretien-form-bikePart"];	
	$message=$_POST["widget-entretien-form-message"];
	
	if($upload){
		$mail->AddAttachment( $path , $fichier );
	}

    $mail->From = $user;
	$mail->FromName = $firstName.' '.$name;
	$mail->AddAddress($sendmail);								  
	$mail->AddReplyTo($email, $name);
	


	
	$subject = 'Demand of maintenance from '.$firstName.' '.$name;
	$mail->Subject = $subject;
	
    $bikePart = isset($bikePart) ? "<strong>Partie du vélo</strong> : $bikePart<br><br>" : '';
    $message = isset($message) ? "<strong>Message</strong> : $message <br><br>" : '';
	
	$name = isset($name) ? "<hr> <br>Nom: $name<br><br>" : '';
	$firstName = isset($firstName) ? "Prenom: $firstName<br><br>" : '';
	$email = isset($user) ? "Email: $user<br><br>" : '';
	$phone = isset($phone) ? "Numero de telephone: $phone<br><br>" : '';
	$frameNumber = isset($frameNumber) ? "Numéro de cadre: $frameNumber<br><br>" : '';

	
	$mail->Body = $bikePart . $message . $name . $firstName . $email . $phone . $frameNumber;

	if(!$mail->Send()) {
		$response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);  
		echo json_encode($response);
		die;
	}
	if ($_SESSION['langue'] == "en"){
		$response = array ('response'=>'success', 'message'=> "Mail sent. We will contact you as soon as possible.");  
	} elseif ($_SESSION['langue'] == "nl"){
		$response = array ('response'=>'success', 'message'=> "Post verzonden. We nemen zo snel mogelijk contact met u op.");
	} else{
		$response = array ('response'=>'success', 'message'=> "Demande de support envoyée. Nous vous contacterons dans les plus brefs délais.");  
	}	
	echo json_encode($response);
	
} else {
	 errorMessage("ES0008");
}
    
}
?>
