<?php

global $conn;
$ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;
$newEndDate=isset($_POST['newEndDate']) ? $_POST['newEndDate']." ".$_POST['newEndHour'] : NULL;

if($ID==NULL){
	errorMessage("ES0012");
}else{
	$booking = execSQL("SELECT aa.BIKE_ID, aa.DATE_END_2, aa.BUILDING_START, bb.COMPANY, aa.EMAIL, bb.MODEL, aa.EXTENSIONS from reservations aa, customer_bikes bb WHERE aa.ID=? AND aa.BIKE_ID=bb.ID", array('i', $ID), false);
	$bikeID = $booking[0]['BIKE_ID'];
	$dateEnd = $booking[0]['DATE_END_2'];
	$company = $booking[0]['COMPANY'];
	$email = $booking[0]['EMAIL'];
	$customName = $booking[0]['MODEL'];
	$building = $booking[0]['BUILDING_START'];
	$dateEnd = new DateTime($booking[0]['DATE_END_2'], new DateTimeZone('Europe/Brussels'));
	$newEndDate = new DateTime($_POST['newEndDate']." ".$_POST['newEndHour'], new DateTimeZone('Europe/Brussels'));
	$dateEndString = $dateEnd->format("Y-m-d H:i");
	$newEndDateString = $newEndDate->format("Y-m-d H:i");
	$limitDate = $dateEnd->add(new DateInterval('P1M'));

	if($limitDate < $newEndDate){
		errorMessage("ES0068");
	}


	if($booking[0]['EXTENSIONS'] == '0'){
		execSQL("UPDATE reservations SET INITIAL_END_DATE=? WHERE ID=?", array('si', $dateEndString, $ID), true);
	}else{
		errorMessage("ES0067");
	}
	execSQL("UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, DATE_END_2=?, EXTENSIONS=EXTENSIONS+1 WHERE ID=?", array('ssi', $token, $newEndDate->format('Y-m-d H:i'), $ID), true);
	execSQL("INSERT INTO reservations_details (ACTION, RESERVATION_ID, BUILDING, OUTCOME) VALUES (?, ?, ?, ?)", array('siss', 'prolongation', $ID, $building, $dateEndString.'/'.$newEndDateString), true);

	require_once $_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php';
	$mail = new PHPMailer();
	require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';

	if(constant('ENVIRONMENT')!="local"){

		if($company=='Actiris'){
			$mail->From = "bookabike@actiris.be";
			$mail->FromName = "Book a Bike - Actiris";
		}else{
			$mail->From = "info@kameobikes.com";
			$mail->FromName = "Info Kameo Bikes";
		}

		if(constant('ENVIRONMENT') == "production"){
			$mail->AddAddress($email);
			if($email=="julien@actiris.be"){
				$mail->AddAddress("antoine@kameobikes.com");
			}
			$mail->AddBCC("antoine@kameobikes.com");
		}else if(constant('ENVIRONMENT') == "test"){
			$mail->AddAddress("antoine@kameobikes.com");
		}

		$mail->IsHTML(true);
		$mail->CharSet = 'UTF-8';


		$subject="Extension of your booking - Kameo Bikes";
		$mail->Subject = $subject;

		if($company=="Actiris"){
			include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_extend_booking_actiris.php';
		}else{
			include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';
			include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_body_extend_booking.php';
			include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_footer.php';
		}
		$mail->Body = $body;

		error_log(date("Y-m-d H:i:s")." - BODY  - ".$mail->Body."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/logs/daily_logs.log');

		if(constant('ENVIRONMENT') == "production" || constant('ENVIRONMENT') == "test"){
			if(!$mail->Send()) {
				 $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
					echo json_encode($response);
					die;
			}
		}
	}


	successMessage("SM0006");
}
?>
