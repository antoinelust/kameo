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

	execSQL("UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, DATE_END_2=? WHERE ID=?", array('ssi', $token, $newEndDate, $ID), true);

	$nextBooking = execSQL("SELECT aa.ID, aa.DATE_START_2, aa.EMAIL from reservations aa WHERE BIKE_ID=? AND DATE_START_2 > ? AND STAANN != 'D'", array('ss', $bikeID, $dateEnd), false);
	if($nextBooking != null){

		$nextBookingStart = $nextBooking[0]['DATE_START_2'];
		$nextBookingID = $nextBooking[0]['ID'];
		$nextBookingEMAIL = $nextBooking[0]['EMAIL'];
		$now = new DateTime("now");
		$nextBookingDate = new DateTime($nextBookingStart);
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
		$subject = "Your booking number ".$reservationID." is over but key is not returned";
		$mail->Subject = $subject;

		if($company=='Actiris'){
		  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_updateBookingHour_actiris.php';
		}else{
		  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';
		  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_lateBooking.php';
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
	
	$response['response']="success";
	echo json_encode($response);
  die;
}
?>
