<?php
global $conn;
$ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;
$newEndDate=isset($_POST['newEndDate']) ? $_POST['newEndDate'] : NULL;
$messageForNextBooking=isset($_POST['messageForNextBooking']) ? nl2br($_POST['messageForNextBooking']) : NULL;

if($ID==NULL){
	errorMessage("ES0012");
}else{
	$booking = execSQL("SELECT aa.BIKE_ID, aa.DATE_END_2, bb.COMPANY from reservations aa, customer_bikes bb WHERE aa.ID=? AND aa.BIKE_ID=bb.ID", array('i', $ID), false);
	$bikeID = $booking[0]['BIKE_ID'];
	$dateEnd = $booking[0]['DATE_END_2'];
	$company = $booking[0]['COMPANY'];
	//execSQL("UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, DATE_END_2=? WHERE ID=?", array('ssi', $token, $newEndDate, $ID), true);
	//execSQL("UPDATE notifications SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, STAAN='D' WHERE TYPE_ITEM=? AND TYPE='lateBooking'", array('si', $token, $ID), true);

	$newEndDateString=str_replace("T", " ", $newEndDate);

	if($company=='Actiris'){
		$nextBooking = execSQL("SELECT aa.ID, aa.DATE_START_2, aa.EMAIL, bb.ID as customerID from reservations aa, customer_referential bb WHERE aa.EMAIL=bb.EMAIL AND aa.BIKE_ID=? AND aa.DATE_START_2 > ? AND aa.DATE_START_2 < ? AND aa.STAANN != 'D' ORDER BY aa.DATE_START_2", array('sss', $bikeID, $dateEnd, $newEndDate), false);

		if($nextBooking != null){

			$nextBookingStart = $nextBooking[0]['DATE_START_2'];
			$nextBookingID = $nextBooking[0]['ID'];
			$nextBookingEMAIL = $nextBooking[0]['EMAIL'];

			execSQL("INSERT INTO `notifications` ( `HEU_MAJ`, `USR_MAJ`, `DATE`, `TEXT`, `READ`, `TYPE`, `USER_ID`, `TYPE_ITEM`, `STAAN`) VALUES (CURRENT_TIMESTAMP, 'identifyLateBooking.php', CURRENT_TIMESTAMP, '', 'N', 'lateBookingNextUserNewHour', ?, ?, NULL)", array('ii', $nextBooking[0]['customerID'], $nextBookingID), true);

			require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
			$mail = new PHPMailer();
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';

			if(constant('ENVIRONMENT') == "production"){
			  //$mail->AddAddress($nextBookingEMAIL);
				$mail->AddAddress("antoine@kameobikes.com");
			  $mail->addBcc("antoine@kameobikes.com");
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
			$subject = "Your booking number ".$ID." is starting soon but key is not available";
			$mail->Subject = $subject;

			if($company=='Actiris'){
			  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_updateBookingHour_actiris.php';
			}else{
			  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';
			  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_lateBooking.php';
			  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mail_footer.php';
			}
			$mail->Body = $body;
			error_log(date("Y-m-d H:i:s")." - BODY : ".$body."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/updateEndBooking.log');

			if(constant('ENVIRONMENT')=='production' || constant('ENVIRONMENT') == "test"){
			  if(!$mail->Send()) {
			     $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
			     echo json_encode($response);
			     die;
			  }
			}
		}
	}
	$nextBooking = execSQL("UPDATE notifications SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='update_end_booking.php', STAAN='D' WHERE TYPE_ITEM=? AND TYPE='lateBooking'", array('i', $ID), true);

	$response['response']="success";
	echo json_encode($response);
  die;
}
?>
