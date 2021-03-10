<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();

require_once 'globalfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
require_once __DIR__ .'/authentication.php';

$token = getBearerToken();
log_inputs($token);

if(!get_user_permissions("search", $token)){
  error_message('403');
}

$resultat = execSQL("SELECT EMAIL FROM customer_referential WHERE TOKEN='$token'", array(), false);
$user = $resultat[0]['EMAIL'];
$bikeID=htmlspecialchars($_POST['bikeID']);
$buildingStart=htmlspecialchars($_POST['widget-new-booking-building-start']);
$buildingEnd=htmlspecialchars($_POST['widget-new-booking-building-end']);

if(isset($_POST['action'])){
  if(isset($_POST['oldBookingID'])){
    $oldBookingID=$_POST['oldBookingID'];
    $resultat = execSQL("SELECT CODE FROM locking_code WHERE ID_reservation='$oldBookingID'", array(), false);
    $lockingcode = $resultat[0]['CODE'];
    execSQL("UPDATE locking_code SET VALID='N', STAANN = 'D', HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='replaceBooking.php' WHERE ID_reservation='$oldBookingID'", array(), true);
    execSQL("UPDATE reservations SET STAANN = 'D', HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='replaceBooking.php' WHERE ID='$oldBookingID'", array(), true);
    execSQL("UPDATE notifications SET STAAN = 'D', HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='replaceBooking.php' WHERE TYPE_ITEM=? AND TYPE='feedback'", array('i', $oldBookingID), true);
    execSQL("DELETE FROM feedbacks WHERE ID_RESERVATION=?", array('i', $oldBookingID), true);
  }else{
    errorMessage("ES0012");
  }
}else{
  $lockingcode=addslashes($_POST['widget-new-booking-locking-code']);
}

$temp=new DateTime($_POST['widget-new-booking-date-start'], new DateTimeZone('Europe/Brussels'));
$dateStart=$temp->format('U');
$dateStart_2=$temp;
$dateStart_2String=$dateStart_2->format('Y-m-d H:i');
$dateStart_3String=$dateStart_2->format('d/m/Y');

$temp->sub(new DateInterval('PT15M'));
$dateStart2=$temp->format('U');


$temp=new DateTime($_POST['widget-new-booking-date-end'], new DateTimeZone('Europe/Brussels'));
$dateEnd=$temp->format('U');
$dateEnd_2=$temp;
$dateEnd_2String=$dateEnd_2->format('Y-m-d H:i');
$dateEnd_3String=$dateEnd_2->format('d/m/Y');

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $bikeID != NULL & $buildingStart != NULL && $buildingEnd != NULL && $dateStart != NULL && $dateEnd != NULL && $user!= NULL ) {

	include 'connexion.php';
    $sql= "select * from reservations where STAANN!='D' and BIKE_ID = '$bikeID' AND (STATUS='No box' OR STATUS='Open') AND ((DATE_END_2 >= '$dateStart_2String' and DATE_END_2 <= '$dateEnd_2String') OR (DATE_START_2>='$dateStart_2String' and DATE_START_2 <= '$dateEnd_2String'))";

   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
    $length = $result->num_rows;


	 if($length > 0){
        errorMessage("ES0019");
    }

    $timestamp= time();
    $sql= "INSERT INTO reservations (USR_MAJ, STATUS, BIKE_ID, DATE_START, DATE_START_2, BUILDING_START, DATE_END, DATE_END_2, BUILDING_END, EMAIl, STAANN) VALUES ('new_booking', 'No box', '$bikeID', '$dateStart', '$dateStart_2String', '$buildingStart', '$dateEnd', '$dateEnd_2String', '$buildingEnd', '$user', '')";


   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
		$insertedID = $conn->insert_id;
    $conn->close();


    execSQL("INSERT INTO reservations_details (ACTION, RESERVATION_ID, BUILDING, OUTCOME) VALUES (?, ?, ?, ?)", array('siss', 'new_booking', $insertedID, $buildingStart, $dateStart_2String.'/'.$dateEnd_2String.'/'.$buildingStart.'/'.$buildingEnd), true);


    // ====Ajout de la notification de feedback
    include 'connexion.php';
    $ownerID = $conn->query("SELECT ID FROM customer_referential WHERE EMAIL = '$user'");
    $ownerID = mysqli_fetch_assoc($ownerID)['ID'];
    $dateFinReservation = date("Y-m-d H:i:s", $dateEnd);
    $feedbackText = '';

    $sql= "INSERT INTO notifications (USR_MAJ, HEU_MAJ, DATE, TEXT , `READ`, TYPE, USER_ID, TYPE_ITEM) VALUES ('$user', CURRENT_TIMESTAMP, '$dateFinReservation','', 'N', 'feedback',$ownerID,$insertedID)";
        if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $sql="INSERT INTO feedbacks (HEU_MAJ, USR_MAJ, BIKE_ID, ID_RESERVATION, NOTE, COMMENT, ENTRETIEN, STATUS) VALUES (CURRENT_TIMESTAMP, '$user', '$bikeID', '$insertedID', NULL, NULL, NULL, 'SENT')";


    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $conn->close();
    include 'connexion.php';

    if($lockingcode!=""){
        include 'connexion.php';

        $sql= "select ID from reservations where BIKE_ID = '$bikeID' and EMAIL = '$user' and DATE_START_2 = '$dateStart_2String' and DATE_END_2 = '$dateEnd_2String' and STAANN != 'D' ";



        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $ID = $resultat['ID'];
        $sql= "select * from building_access where BUILDING_REFERENCE = '$buildingStart'";



        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $building=$resultat['BUILDING_CODE'];


        $sql= "INSERT INTO locking_code (ID_reservation, USR_MAJ, DATE_BEGIN, DATE_END, BUILDING_START, CODE, VALID) VALUES ('$ID', 'new_booking.php', '$dateStart2', '$dateEnd', '$building', '$lockingcode', 'Y')";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $sql= "UPDATE reservations SET STATUS='Open' where ID='$ID'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

    }
		require_once $_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php';
		$mail = new PHPMailer();


		require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';

		if(constant('ENVIRONMENT')!="local"){

			$sql="select aa.FRAME_NUMBER, aa.MODEL as customName, aa.COMPANY, bb.BRAND, bb.MODEL from customer_bikes aa, bike_catalog bb WHERE aa.ID='$bikeID' and aa.TYPE=bb.ID";
			if ($conn->query($sql) === FALSE) {
					$response = array ('response'=>'error', 'message'=> $conn->error);
					echo json_encode($response);
					die;
			}
			$result = mysqli_query($conn, $sql);
			$resultat = mysqli_fetch_assoc($result);
			$frameNumber=$resultat['FRAME_NUMBER'];
			$brand=$resultat['BRAND'];
			$customName = $resultat['customName'];
			$model=$resultat['MODEL'];
			$company=$resultat['COMPANY'];



			if($company=='Actiris'){
		    $mail->From = "bookabike@actiris.be";
		    $mail->FromName = "Book a Bike - Actiris";
		  }else{
		    $mail->From = "info@kameobikes.com";
		    $mail->FromName = "Info Kameo Bikes";
		  }

      if(constant('ENVIRONMENT') == "production"){
        $mail->AddAddress($user);
        if($user=="julien@actiris.be"){
          $mail->AddAddress("antoine@kameobikes.com");
        }
      }else if(constant('ENVIRONMENT') == "test"){
        $mail->AddAddress("antoine@kameobikes.com");
      }

			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';


			$subject="New bike booking - Kameo Bikes";
			$mail->Subject = $subject;

			if($company=="Actiris"){
				include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_new_booking_actiris.php';
			}else{
				include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';
				include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_body_new_booking.php';
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
}else{
	errorMessage("ES0012");
}
?>
