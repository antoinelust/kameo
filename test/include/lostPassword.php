<?php
include 'globalfunctions.php';

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


//corresponds to the request for the mail, to have the link for reseting the mail
if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['widget-update-form-email'])) {

			
	//Function to generate random number here
	$number=rand();
	$hash=password_hash($number, PASSWORD_DEFAULT);

	$e_mail=$_POST['widget-update-form-email'];
    
	include 'connexion.php';

		if(isset($e_mail)){
			$sql = "SELECT PASSWORD FROM customer_referential where EMAIL='$e_mail'";
			if ($conn->query($sql) === FALSE) {
				$response = array ('response'=>'error', 'message'=> $conn->error);
				echo json_encode($response);
				die;
			}
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($result);
			$conn->close();

			if($row["PASSWORD"]==NULL)
			{
				//refaire un test avec celui là, ne marche pas. essayer avec un compte qui n'existe pas dans notre db.
				errorMessage(ES0014);
			}

			writeMail();
			logLostPassword();
			successMessage(SM0001);
		}
		else{
			errorMessage(ES0012);
		}
}

//corresponds actually to the request for modifying the password
elseif( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['widget-lostPassword-form-new-password']) && isset($_POST['widget-lostPassword-form-hash']) && isset($_POST['widget-lostPassword-form-antispam'])){

	$hash=$_POST['widget-lostPassword-form-hash'];
	$sql = "SELECT * FROM lost_Password where HASH='$hash'";
    
	include 'connexion.php';

	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	$conn->close();
	
	if($row["TIMESTAMP"]==NULL)
	{
		errorMessage(ES0012);
	}
	if($row["EMAIL"]==NULL)
	{
		errorMessage(ES0012);
	}
	
    
	$now = new DateTime();
    $now->sub(new DateInterval('PT1H'));
    $timestamp_onehourbefore=$now->getTimestamp();
    
	$demand=$row["TIMESTAMP"];


	if ($demand < $timestamp_onehourbefore) {
        //cette erreur ne marche pas, voir pourquoi ! 
		errorMessage(ES0013);
	}

	$EMAIL=$row["EMAIL"];
	
    include 'connexion.php';
    
    $password = password_hash($_POST["widget-lostPassword-form-new-password"], PASSWORD_BCRYPT);
    $sql = "update customer_referential set PASSWORD='$password' where EMAIL='$EMAIL'";
    
	$result = mysqli_query($conn, $sql);
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$conn->close();
	
	successMessage(SM0002);
}
else
{
	errorMessage(ES0012);
}


function writeMail(){
    
    global $e_mail;
    global $hash;
	
	require_once('php-mailer/PHPMailerAutoload.php');
	$mail = new PHPMailer();

	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	$mail->From = "security@kameobikes.com";
	$mail->FromName = "Security Kameo";
	$mail->AddAddress($e_mail);
	
	$mail->AddReplyTo("security@kameobikes.com");
	$subject = "Mot de passe oublié";
	$mail->Subject = $subject;
	
	$body = "Veuillez trouvez ci-dessous le lien afin de pouvoir réinitialiser votre mot de passe. <br /> Veuillez copier-coller le lien ci-dessous dans votre barre d'adresse : ";
	//vérifier comme définir un paramètre GET
	$link = 'http://www.kameobikes.com/test/index.php?hash='. $hash;
	$mail->Body = $body . $link;
    

    
	if(!$mail->Send()) {
		$response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);  
		echo json_encode($response);
		die;
	}

}


function logLostPassword(){
    global $hash;
    global $e_mail;
	include 'connexion.php';
    $datetime = new DateTime();
    $timestamp=$datetime->getTimestamp();
	$sql = "INSERT INTO lost_Password (HASH, EMAIL, TIMESTAMP) VALUES ('$hash', '$e_mail', '$timestamp')";

	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    
	$conn->close();
	
}
?>