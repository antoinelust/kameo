<?php

if($company=='Actiris'){
  require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
  $reservationID=$lateBooking['reservationID'];
  $informations = execSQL("SELECT aa.EMAIL, aa.BIKE_ID, bb.COMPANY, aa.DATE_START_2, aa.DATE_END_2, cc.MODEL from reservations aa, customer_referential bb, customer_bikes cc WHERE aa.ID='$reservationID' AND aa.EMAIL=bb.EMAIL AND aa.BIKE_ID=cc.ID", array(), false);
  $email=$informations[0]['EMAIL'];
  $customName=$informations[0]['MODEL'];
  $temp=new DateTime($informations[0]['DATE_START_2'], new DateTimeZone('Europe/Brussels'));
  $dateStartString=$temp->format('d/m/Y H:i');
  $temp=new DateTime($informations[0]['DATE_END_2'], new DateTimeZone('Europe/Brussels'));
  $dateEndString=$temp->format('d/m/Y H:i');

  $bikeID=$informations[0]['BIKE_ID'];
  $dateStart=$informations[0]['DATE_START_2'];

  $nextBooking = execSQL("select * from reservations WHERE BIKE_ID='$bikeID' AND DATE_START_2 > '$dateStart' AND STAANN != 'D'", array(), false);
  if($nextBooking != NULL){
    $nextBookingStart=new DateTime($nextBooking[0]['DATE_START_2'], new DateTimeZone('Europe/Brussels'));
    $nextBookingStartString=$nextBookingStart->format('d/m/Y h:i');
    echo $bikeID;
    die;
    $now=new DateTime("now", new DateTimeZone('Europe/Brussels'));
    $interval = $now->diff($nextBookingStart);
    $intervalHours = $interval->format('%h');
  }

  require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
  $mail = new PHPMailer();
  $mail->IsHTML(true);                                    // Set email format to HTML
  $mail->CharSet = 'UTF-8';

  echo "environnement : ".constant('ENVIRONMENT')."\n";

  if(constant('ENVIRONMENT') == "production"){
    $mail->AddAddress($email);
    $mail->AddCC("bookabike@actiris.be");
    $mail->addBcc("antoine@kameobikes.com");
  }else if(constant('ENVIRONMENT') == "test"){
    $mail->AddAddress("antoine@kameobikes.com");
  }

  echo "company : ".$company."\n";

  if($company=='Actiris'){
    $mail->From = "bookabike@actiris.be";
    $mail->FromName = "Book a Bike - Actiris";
  }else{
    $mail->From = "info@kameobikes.com";
    $mail->FromName = "Info Kameo Bikes";
  }
  $mail->AddReplyTo('info@kameobikes.com', 'Information Kameo Bikes');
  $subject = "Your booking number ".$reservationID." is over but key is not returned";
  $mail->Subject = $subject;

  echo "sujet : ".$subject."\n";

  if( $company == 'Actiris'){
    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_lateBooking_actiris.php';
  }else{
    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';
    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_lateBooking.php';
    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mail_footer.php';
  }
  $mail->Body = $body;

  echo "body : ".$body."\n";
  error_log(date("Y-m-d H:i:s")." - BODY : ".$body."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/sendMailLateBooking.log');

  if(constant('ENVIRONMENT')=='production' || constant('ENVIRONMENT') == "test"){
    if(!$mail->Send()) {
       $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
       echo json_encode($response);
       die;
    }
  }
}
