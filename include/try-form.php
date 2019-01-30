<?php
include 'globalfunctions.php';

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');



/* $response = array ('response'=>'error', 'message'=>$to);
echo json_encode($response);
die; */

// Form Fields
$name = $_POST["widget-contact-form-name"];
$firstName = $_POST["widget-contact-form-firstName"];
$birthDate = $_POST["widget-contact-form-birthDate"];
$email = $_POST["widget-contact-form-email"];
$phone = $_POST["widget-contact-form-phone"];
$postalCode = $_POST["widget-contact-form-postalCode"];
$velo = $_POST["widget-contact-form-velo"];
$dateEssai = $_POST["widget-contact-form-dateEssai"];
$antispam = $_POST['widget-contact-form-antispam'];
$captcha = strlen($_POST['g-recaptcha-response']);


$birthDate=new DateTime(str_replace("/","-",$birthDate));
$dateEssai=new DateTime(str_replace("/","-",$dateEssai));
	
	

					

$datetime1 = new DateTime('now');
$datetime1->sub(new DateInterval('P16Y'));

if ($birthDate > $datetime1) {
	errorMessage(ES0001);
}


$datetime1 = new DateTime('now');
$datetime1->add(new DateInterval('P2D'));


if ($dateEssai < $datetime1) {
	errorMessage(ES0002);
}


$datetime1 = new DateTime('now');
$datetime1->add(new DateInterval('P3M'));

if ($dateEssai > $datetime1) {
	errorMessage(ES0003);
}

$length = strlen($phone);
if ($length<8 or $length>12) {
	errorMessage(ES0004);
}

$length = strlen($postalCode);
if ($length<4) {
	errorMessage(ES0005);
}
if($captcha == 0){
    errorMessage(ES0020);
}
					
					
if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($name) && isset($firstName) && isset($birthDate) && isset($email) && isset($phone) && isset($postalCode) && isset($velo) && isset($dateEssai) && $antispam == '') {
    
 if($email != '') {

				include 'connexion.php';
				$birthDateSQL = $birthDate->format('Y-m-d');
				$dateEssaiSQL = $dateEssai->format('Y-m-d');

				$sql = "INSERT INTO main_table (Name, FIRSTNAME, BIRTHDATE, MAIL, PHONENUMBER, POSTALCODE, VELO, DATEESSAI) VALUES ('$name', '$firstName', '$birthDateSQL', '$email', '$phone', '$postalCode', '$velo', '$dateEssaiSQL')";

				if ($conn->query($sql) === FALSE) {
					$response = array ('response'=>'error', 'message'=> $conn->error);
					echo json_encode($response);
					die;
				}
				$conn->close();
					
                //If you don't receive the email, enable and configure these parameters below: 
     
                //$mail->isSMTP();                                      // Set mailer to use SMTP
                //$mail->Host = 'mail.yourserver.com';                  // Specify main and backup SMTP servers, example: smtp1.example.com;smtp2.example.com
                //$mail->SMTPAuth = true;                               // Enable SMTP authentication
                //$mail->Username = 'SMTP username';                    // SMTP username
                //$mail->Password = 'SMTP password';                    // SMTP password
                //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                //$mail->Port = 587;                                    // TCP port to connect to 
     			require_once('php-mailer/PHPMailerAutoload.php');
				$mail = new PHPMailer();

     	        $mail->IsHTML(true);                                    // Set email format to HTML
                $mail->CharSet = 'UTF-8';
				
				$mail->AddAddress('thibaut.mativa@kameobikes.com', 'Thibaut Mativa');
				$mail->AddAddress('julien.jamar@kameobikes.com', 'Julien Jamar');
				$mail->AddAddress('julien.jamardebolse@gmail.com', 'Julien Jamar De Bolsee');
				$mail->AddAddress('antoine.lust@kameobikes.com', 'Antoine Lust');


                $mail->From = $email;
                $mail->FromName = $firstName.' '.$name;
                $mail->AddAddress($sendmail);								  
                $mail->AddReplyTo($email, $name);
				$subject = "Demande d'essai";
                $mail->Subject = $subject;
				
				$birthDateMSG = $birthDate->format('d-m-Y');
				$dateEssaiMSG = $dateEssai->format('d-m-Y');
                $name = isset($name) ? "Nom: $name<br><br>" : '';
				$firstName = isset($firstName) ? "Prenom: $firstName<br><br>" : '';
				$birthDate = isset($birthDate) ? "Date de naissance: $birthDateMSG <br><br>" : '';
                $email = isset($email) ? "Email: $email<br><br>" : '';
                $phone = isset($phone) ? "Phone: $phone<br><br>" : '';
				$postalCode = isset($postalCode) ? "Code Postal: $postalCode<br><br>" : '';
				$velo = isset($velo) ? "Velo souhaité: $velo<br><br>" : '';
				$dateEssai = isset($dateEssai) ? "Date d'essai souhaité: $dateEssaiMSG <br><br>" : '';

				$mail->Body = $name . $firstName . $birthDate . $email . $phone . $postalCode . $velo . $dateEssai . '<br><br><br>This email was sent from: ' . $_SERVER['HTTP_REFERER'];

				if(!$mail->Send()) {
					$response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);  
					echo json_encode($response);
					die;
				}
				$response = array ('response'=>'success');  
				echo json_encode($response);
				die;


} else {
	$response = array ('response'=>'error', 'message'=>'champ non rempli');     
	echo json_encode($response);
	die;
}
    
}
?>
