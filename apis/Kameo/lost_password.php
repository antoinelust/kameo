<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');
require_once('../../include/php-mailer/PHPMailerAutoload.php');


include_once 'globalfunctions.php';
require_once 'authentication.php';
$token = getBearerToken();

log_inputs($token);

//corresponds to the request for the mail, to have the link for reseting the mail
if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['widget-update-form-email'])){


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
				errorMessage("ES0014");
			}

			logLostPassword();
			writeMail();
			successMessage('SM0001');
		}
		else{
			errorMessage('ES0012');
		}
}

//corresponds actually to the request for modifying the password
elseif($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['widget-lostPassword-form-new-password']) && isset($_POST['widget-lostPassword-form-hash'])){
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

	$now = new DateTime();
  $now->sub(new DateInterval('PT1H'));

	$demand=new DateTime($row["DATE"]);



	if($row["DATE"]==NULL || $row["EMAIL"]==NULL || $demand < $now)
	{
		errorMessage("ES0013");
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

	successMessage('SM0002');
}
else
{
	errorMessage('ES0012');
}


function writeMail(){

    global $e_mail;
    global $hash;

	require_once('../../include/php-mailer/PHPMailerAutoload.php');
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

	require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';

  if(constant('ENVIRONMENT') == "production"){
     $link = 'https://www.kameobikes.com/index?hash='. $hash;
  }else if(constant("ENVIRONMENT") == "test"){
     $link = 'https://www.test.kameobikes.com/index?hash='. $hash;
  }else{
      $link='';
  }

	$mail->Body = $body . $link;

  if(constant('ENVIRONMENT') == "production" || constant('ENVIRONMENT') == "test"){
    if(!$mail->Send()) {
        $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
        echo json_encode($response);
        die;
    }
  }
}


function logLostPassword(){
  global $hash;
  global $e_mail;
	include 'connexion.php';
	$sql = "INSERT INTO lost_Password (HASH, EMAIL, DATE) VALUES ('$hash', '$e_mail', CURRENT_TIMESTAMP)";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}

	$conn->close();

}
?>
