<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';

$lateBookings = execSQL("SELECT aa.ID as reservationID, bb.ID as customerID, bb.COMPANY FROM reservations aa, customer_referential bb WHERE aa.STATUS='Open' AND aa.STAANN != 'D' AND aa.DATE_END_2 < CURRENT_TIMESTAMP AND aa.EMAIL=bb.EMAIL AND NOT EXISTS (SELECT 1 FROM notifications cc WHERE cc.TYPE='lateBooking' and cc.TYPE_ITEM=aa.ID)", array(), false);
foreach ((array) $lateBookings as $lateBooking) {
  execSQL("INSERT INTO `notifications` ( `HEU_MAJ`, `USR_MAJ`, `DATE`, `TEXT`, `READ`, `TYPE`, `USER_ID`, `TYPE_ITEM`, `STAAN`) VALUES (CURRENT_TIMESTAMP, 'identifyLateBooking.php', CURRENT_TIMESTAMP, '', 'N', 'lateBooking', ?, ?, NULL)", array('ii', $lateBooking['customerID'], $lateBooking['reservationID']), true);
  include 'sendMailLateBooking.php';

  $bikeID = $lateBooking['BIKE_ID'];
  $dateEnd = $lateBooking['DATE_END_2'];
  $company = $lateBooking['COMPANY'];

  $nextBooking = execSQL("SELECT aa.ID, aa.DATE_START_2, aa.EMAIL from reservations aa WHERE BIKE=? AND DATE_START_2 > ?", array('ss', $bikeID, $dateEnd), false);

  if($nextBooking != null){
    $nextBookingStart = $nextBooking[0]['DATE_START_2'];
    $nextBookingID = $nextBooking[0]['ID'];
    $nextBookingEMAIL = $nextBooking[0]['EMAIL'];
    $now = new DateTime("now",new DateTimeZone('Europe/Brussels'));
		$nextBookingDate = new DateTime($nextBookingStart,new DateTimeZone('Europe/Brussels'));
		$interval = date_diff($now, $nextBookingDate);
		$numberOfHours = $interval->format('%h');

		if($numberOfHours < 24 ){
			$warnNextBooking = true;
		}else{
			$warnNextBooking = false;
		}
    require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
		$mail = new PHPMailer();
		$mail->IsHTML(true);                                    // Set email format to HTML
		$mail->CharSet = 'UTF-8';

		if(constant('ENVIRONMENT') == "production"){
			if($warnNextBooking){
			  //$mail->AddAddress($nextBookingEMAIL);
				$mail->AddAddress("antoine@kameobikes.com");
			  $mail->addBcc("antoine@kameobikes.com");
			}else{
        $mail->AddAddress("antoine@kameobikes.com");
        $mail->addBcc("antoine@kameobikes.com");
      }
		}else if(constant('ENVIRONMENT') == "test"){
		  $mail->AddAddress("antoine@kameobikes.com");
		}

		if($company=='Actiris'){
		  $mail->From = "bookabike@actiris.be";
		  $mail->FromName = "Book a Bike - Actiris";
		}else{
		  $mail->From = "info@kameobikes.com";
		  $mail->FromName = "Info Kameo Bikes";
		}
		$mail->AddReplyTo('info@kameobikes.com', 'Information Kameo Bikes');
		$subject = "Your booking number ".$nextBookingID." will start soon but the key is not yet returned";
		$mail->Subject = $subject;

		if($company=='Actiris'){
		  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_updateBookingHour_nextBooking_actiris.php';
		}else{
		  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';
		  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_lateBooking_nextBooking.php';
		  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mail_footer.php';
		}

		$mail->Body = $body;
		if(constant('ENVIRONMENT')=='production' || constant('ENVIRONMENT') == "test"){
		  if(!$mail->Send()) {
		     $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
		     echo json_encode($response);
		     die;
		  }
		}
  }
}

echo "success";
die;
