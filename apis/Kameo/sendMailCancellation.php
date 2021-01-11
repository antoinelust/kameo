<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$sql="SELECT aa.EMAIL, bb.COMPANY, aa.DATE_START_2, aa.DATE_END_2 from reservations aa, customer_referential bb WHERE aa.ID='$ID' AND aa.EMAIL=bb.EMAIL";
if ($conn->query($sql) === FALSE) {
  $response = array ('response'=>'error', 'message'=> $conn->error);
  echo json_encode($response);
  die;
}
$result=mysqli_query($conn, $sql);
$resultat=mysqli_fetch_assoc($result);
$email=$resultat['EMAIL'];
$company=$resultat['COMPANY'];

$customName = $row['MODEL'];
$temp=new DateTime($resultat['DATE_START_2'], new DateTimeZone('Europe/Brussels'));
$dateStart=$temp->format('d/m/Y H:i');
$temp=new DateTime($resultat['DATE_END_2'], new DateTimeZone('Europe/Brussels'));
$dateEnd=$temp->format('d/m/Y H:i');

require_once('../../include/php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();
$mail->IsHTML(true);                                    // Set email format to HTML
$mail->CharSet = 'UTF-8';

if(constant('ENVIRONMENT') == "production"){
  $mail->AddAddress($email);
  $mail->addBcc("antoine@kameobikes.com");
}else if(constant('ENVIRONMENT') == "test"){
  $mail->AddAddress("antoine@kameobikes.com");
  $mail->addBcc("antoine@kameobikes.com");
}

if($company=='Actiris'){
  $mail->From = "bookabike@actiris.be";
  $mail->FromName = "Book a Bike - Actiris";
}else{
  $mail->From = "info@kameobikes.com";
  $mail->FromName = "Info Kameo Bikes";
}
$mail->AddReplyTo('info@kameobikes.com', 'Information Kameo Bikes');
$subject = "Your booking number ".$ID." has been cancelled";
$mail->Subject = $subject;

if($company=='Actiris'){
  include 'mails/mail_annulation_actiris.php';
}else{
  include 'mails/mail_header.php';
  include 'mails/mail_annulation.php';
  include 'mails/mail_footer.php';
}

$mail->Body = $body;
if(constant('ENVIRONMENT')=='production' || constant('ENVIRONMENT') == "test"){
  if(!$mail->Send()) {
     $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
     echo json_encode($response);
     die;
  }
}
