<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';

$companies=array('Actiris');

foreach($companies as $company){
  $minutesToAdd=execSQL("SELECT MINUTES_FOR_AUTOMATIC_CANCEL FROM conditions WHERE COMPANY=?", array('s', $company), false)[0]['MINUTES_FOR_AUTOMATIC_CANCEL'];
  $time = new DateTime('NOW', new DateTimeZone('Europe/Brussels'));
  $time->sub(new DateInterval('PT' . $minutesToAdd . 'M'));
  $stamp = $time->format('Y-m-d H:i');

  $resultSendMail=execSQL("SELECT aa.ID, bb.MODEL from reservations aa, customer_bikes bb WHERE aa.DATE_START_2 < '$stamp' AND aa.BIKE_ID=bb.ID AND aa.STAANN != 'D' AND aa.STATUS='Open' AND bb.COMPANY = ? AND NOT EXISTS (select 1 FROM locking_bikes WHERE RESERVATION_ID=aa.ID AND PLACE_IN_BUILDING='-1')", array('s', $company), false);
  echo 'Société : '.$company.'\n';
  echo "Requête SQL : SELECT aa.ID, bb.MODEL from reservations aa, customer_bikes bb WHERE aa.DATE_START_2 < '$stamp' AND aa.BIKE_ID=bb.ID AND aa.STAANN != 'D' AND aa.STATUS='Open' AND bb.COMPANY = '".$company."' AND NOT EXISTS (select 1 FROM locking_bikes WHERE RESERVATION_ID=aa.ID AND PLACE_IN_BUILDING='-1')  \n";

  foreach($resultSendMail as $row){
    $ID=$row['ID'];
    $customName = $row['MODEL'];
    echo '---------------\n';
    echo 'ID Réservation : '.$ID.'\n';
    execSQL("UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, STAANN = 'D', USR_MAJ='script' WHERE ID=?", array('i', $ID), true);
    $resultat=execSQL("SELECT aa.EMAIL, bb.COMPANY, aa.DATE_START_2, aa.DATE_END_2 from reservations aa, customer_referential bb WHERE aa.ID=? AND aa.EMAIL=bb.EMAIL", array('i', $ID), false)[0];
    $email=$resultat['EMAIL'];
    $company=$resultat['COMPANY'];

    $customName = $row['MODEL'];
    $temp=new DateTime($resultat['DATE_START_2'], new DateTimeZone('Europe/Brussels'));
    $dateStart=$temp->format('d/m/Y H:i');
    $temp=new DateTime($resultat['DATE_END_2'], new DateTimeZone('Europe/Brussels'));
    $dateEnd=$temp->format('d/m/Y H:i');

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php';
    $mail = new PHPMailer();
    $mail->IsHTML(true);                                    // Set email format to HTML
    $mail->CharSet = 'UTF-8';

    if(constant('ENVIRONMENT') == "production"){
      if($email=='julien@actiris.be'){
        $mail->AddAddress("antoine@kameobikes.com");
      }else{
        $mail->AddAddress($email);
        $mail->addBcc("antoine@kameobikes.com");
      }
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
      include '../mails/mail_annulation_actiris.php';
    }else{
      include '../mails/mail_header.php';
      include '../mails/mail_annulation.php';
      include '../mails/mail_footer.php';
    }

    $mail->Body = $body;
    if(constant('ENVIRONMENT')=='production' || constant('ENVIRONMENT') == "test"){
      if(!$mail->Send()){
         $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
         echo json_encode($response);
         die;
      }
    }else{
      echo 'environnement de production - pas de mail envoyé \n';
    }
  }
  echo '--------------- \n';
  echo '--------------- \n';
}


echo '--------------- \n';
echo '--------------- \n';
echo "success";
die;
