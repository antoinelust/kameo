<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';

$lateBookings = execSQL("SELECT aa.ID as reservationID, aa.BIKE_ID, aa.DATE_END_2, bb.ID as customerID, bb.COMPANY FROM reservations aa, customer_referential bb WHERE aa.STATUS='Open' AND (aa.STAANN <> 'D' OR aa.STAANN is NULL) AND aa.DATE_END_2 < CURRENT_TIMESTAMP AND aa.EMAIL=bb.EMAIL", array(), false);
foreach ((array) $lateBookings as $lateBooking) {
  if($lateBooking['COMPANY']=="Actiris"){


    $bikeID = $lateBooking['BIKE_ID'];
    $dateEnd = $lateBooking['DATE_END_2'];

    $nextBooking = execSQL("SELECT aa.ID, aa.DATE_START_2, aa.DATE_END_2, cc.MODEL, aa.EMAIL, bb.ID as customerID, bb.COMPANY from reservations aa, customer_referential bb, customer_bikes cc WHERE aa.BIKE_ID=cc.ID AND aa.EMAIL = bb.EMAIL AND BIKE_ID=? AND DATE_START_2 > ? AND NOT EXISTS (select 1 from notifications WHERE notifications.TYPE='lateBookingNextUser' AND (notifications.STAAN <> 'D' OR notifications.STAAN IS NULL) AND notifications.TYPE_ITEM = aa.ID AND notifications.USER_ID=(SELECT ID from customer_referential WHERE EMAIL = aa.EMAIL))", array('ss', $bikeID, $dateEnd), false);
    if($nextBooking != null){
      echo 'ID : '.$nextBooking[0]['ID']."\n";
      $nextBookingStart = $nextBooking[0]['DATE_START_2'];
      $nextBookingEnd = $nextBooking[0]['DATE_END_2'];
      $nextBookingID = $nextBooking[0]['ID'];
      $customName =  $nextBooking[0]['MODEL'];
      $nextBookingEMAIL = $nextBooking[0]['EMAIL'];
      $company = $nextBooking[0]['COMPANY'];
      $now = new DateTime("now", new DateTimeZone('Europe/Brussels'));
  		$nextBookingDate = new DateTime($nextBookingStart, new DateTimeZone('Europe/Brussels'));
      $bookingEnd = new DateTime($dateEnd, new DateTimeZone('Europe/Brussels'));
  		$interval1 = date_diff($now, $nextBookingDate);
      $numberOfMinutes1 = $interval1->days * 24 * 60;
      $numberOfMinutes1 += $interval1->h * 60;
      $numberOfMinutes1 += $interval1->i;

      $interval2 = date_diff($bookingEnd, $nextBookingDate);
      $numberOfMinutes2 = $interval2->days * 24 * 60;
      $numberOfMinutes2 += $interval2->h * 60;
      $numberOfMinutes2 += $interval2->i;

      if($now > $nextBookingDate){
        $warnNextBooking = false;
      }else if(($numberOfMinutes2 > ($numberOfMinutes1-30 ) && $numberOfMinutes1 < 120) || ($numberOfMinutes2 > ($numberOfMinutes1-5 ) && $numberOfMinutes1 < 15) ){
  			$warnNextBooking = true;
  		}else{
  			$warnNextBooking = false;
  		}

      if($warnNextBooking){
        execSQL("INSERT INTO `notifications` ( `HEU_MAJ`, `USR_MAJ`, `DATE`, `TEXT`, `READ`, `TYPE`, `USER_ID`, `TYPE_ITEM`, `STAAN`) VALUES (CURRENT_TIMESTAMP, 'identifyLateBooking.php', CURRENT_TIMESTAMP, '', 'N', 'lateBookingNextUser', ?, ?, NULL)", array('ii', $nextBooking[0]['customerID'], $nextBookingID), true);
        require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
    		$mail = new PHPMailer();
    		$mail->IsHTML(true);                                    // Set email format to HTML
    		$mail->CharSet = 'UTF-8';

    		if(constant('ENVIRONMENT') == "production"){
    			if($warnNextBooking){
    			  //$mail->AddAddress($nextBookingEMAIL);
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
    		  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_lateBooking_nextBooking_actiris.php';
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
  }
}

echo "success";
die;
