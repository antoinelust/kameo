<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();

include 'globalfunctions.php';

$user = htmlspecialchars($_POST['widget-new-booking-mail-customer']);
$bikeID=htmlspecialchars($_POST['bikeID']);
$buildingStart=htmlspecialchars($_POST['widget-new-booking-building-start']);
$buildingEnd=htmlspecialchars($_POST['widget-new-booking-building-end']);
$lockingcode=htmlspecialchars($_POST['widget-new-booking-locking-code']);

$temp=new DateTime($_POST['widget-new-booking-date-start']);
$dateStart=strtotime($temp->format('Y-m-d H:i'));
$dateStart_2=$temp;
$dateStart_2String=$dateStart_2->format('Y-m-d H:i');

$temp->sub(new DateInterval('PT15M'));
$dateStart2=strtotime($temp->format('Y-m-d H:i'));


$temp=new DateTime($_POST['widget-new-booking-date-end']);
$dateEnd=strtotime($temp->format('Y-m-d H:i'));
$dateEnd_2=$temp;
$dateEnd_2String=$dateEnd_2->format('Y-m-d H:i');


if( $_SERVER['REQUEST_METHOD'] == 'POST' && $bikeID != NULL & $buildingStart != NULL && $buildingEnd != NULL && $dateStart != NULL && $dateEnd != NULL && $user!= NULL ) {

	include 'connexion.php';
    $sql= "select * from reservations where STAANN!='D' and BIKE_ID = '$bikeID' AND ((DATE_END_2 >= '$dateStart_2String' and DATE_END_2 <= '$dateEnd_2String') OR (DATE_START_2>='$dateStart_2String' and DATE_START_2 <= '$dateEnd_2String'))";

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

    // ====Ajout de la notification de feedback
    include 'connexion.php';
    $ownerID = $conn->query("SELECT ID FROM customer_referential WHERE EMAIL = '$user'");
    $ownerID = mysqli_fetch_assoc($ownerID)['ID'];
    $dateFinReservation = date("Y-m-d H:i:s", $dateEnd);
    $feedbackText = '';

    $sql= "INSERT INTO notifications (USR_MAJ, HEU_MAJ, DATE, TEXT , `READ`, TYPE, USER_ID, TYPE_ITEM) VALUES ('$user', CURRENT_TIMESTAMP, '$dateFinReservation','$feedbackText', 'N', 'feedback',$ownerID,$insertedID)";
        if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $notifID = $conn->insert_id;
    $feedbackText = 'Votre réservation n°'.$insertedID.' est terminée<br/><a data-toggle="modal" href="#" onclick=initiatizeFeedback('.$insertedID.','.$notifID.') class="text-green">Cliquez ici</a> pour donner votre feedback';
    $sql= "UPDATE notifications SET TEXT = '$feedbackText' WHERE ID = $notifID";
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

        include 'connexion.php';

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
		require_once $_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
		$mail = new PHPMailer();


		require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
		if($connected && constant('ENVIRONMENT')!="local"){

			$sql="select aa.FRAME_NUMBER, bb.BRAND, bb.MODEL from customer_bikes aa, bike_catalog bb WHERE aa.ID='$bikeID' and aa.TYPE=bb.ID";
			if ($conn->query($sql) === FALSE) {
					$response = array ('response'=>'error', 'message'=> $conn->error);
					echo json_encode($response);
					die;
			}
			$result = mysqli_query($conn, $sql);
			$resultat = mysqli_fetch_assoc($result);
			$frameNumber=$resultat['FRAME_NUMBER'];
			$brand=$resultat['BRAND'];
			$model=$resultat['MODEL'];

			$mail->From = 'info@kameobikes.com';
	    $mail->FromName = "Information Kameo Bikes";
	    $mail->AddReplyTo('info@kameobikes.com', "Information Kameo Bikes");
			$mail->AddAddress($email);
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';
			

			$subject="New bike booking - Kameo Bikes";
			$mail->Subject = $subject;
			include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';

			$body = $body."
					<body>
							<!--[if !gte mso 9]><!----><span class=\"mcnPreviewText\" style=\"display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;\">Mail reçu via la page de contact</span><!--<![endif]-->
							<!--*|END:IF|*-->
							<center>
									<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" width=\"100%\" id=\"bodyTable\">
											<tr>
													<td align=\"center\" valign=\"top\" id=\"bodyCell\">
															<!-- BEGIN TEMPLATE // -->
															<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
																	<tr>
																			<td align=\"center\" valign=\"top\" id=\"templateHeader\" data-template-container>
																					<!--[if (gte mso 9)|(IE)]>
																					<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"600\" style=\"width:600px;\">
																					<tr>
																					<td align=\"center\" valign=\"top\" width=\"600\" style=\"width:600px;\">
																					<![endif]-->
																					<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"templateContainer\">
																							<tr>
																									<td valign=\"top\" class=\"headerContainer\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"mcnImageBlock\" style=\"min-width:100%;\">
					<tbody class=\"mcnImageBlockOuter\">
									<tr>
											<td valign=\"top\" style=\"padding:9px\" class=\"mcnImageBlockInner\">
													<table align=\"left\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"mcnImageContentContainer\" style=\"min-width:100%;\">
															<tbody><tr>
																	<td class=\"mcnImageContent\" valign=\"top\" style=\"padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;\">


																							<img align=\"center\" alt=\"\" src=\"https://gallery.mailchimp.com/c4664c7c8ed5e2d53dc63720c/images/8b95e5d1-2ce7-4244-a9b0-c5c046bf7e66.png\" width=\"300\" style=\"max-width:300px; padding-bottom: 0; display: inline !important; vertical-align: bottom;\" class=\"mcnImage\">


																	</td>
															</tr>
													</tbody></table>
											</td>
									</tr>
					</tbody>
			</table></td>
																							</tr>
																					</table>
																					<!--[if (gte mso 9)|(IE)]>
																					</td>
																					</tr>
																					</table>
																					<![endif]-->
																			</td>
																	</tr>
																	<tr>
																			<td align=\"center\" valign=\"top\" id=\"templateBody\" data-template-container>
																					<!--[if (gte mso 9)|(IE)]>
																					<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"600\" style=\"width:600px;\">
																					<tr>
																					<td align=\"center\" valign=\"top\" width=\"600\" style=\"width:600px;\">
																					<![endif]-->
																					<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"templateContainer\">
																							<tr>
																									<td valign=\"top\" class=\"bodyContainer\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"mcnTextBlock\" style=\"min-width:100%;\">
					<tbody class=\"mcnTextBlockOuter\">
							<tr>
									<td valign=\"top\" class=\"mcnTextBlockInner\" style=\"padding-top:9px;\">
											<!--[if mso]>
											<table align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;\">
											<tr>
											<![endif]-->

											<!--[if mso]>
											<td valign=\"top\" width=\"600\" style=\"width:600px;\">
											<![endif]-->
											<table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:100%; min-width:100%;\" width=\"100%\" class=\"mcnTextContentContainer\">
													<tbody><tr>

															<td valign=\"top\" class=\"mcnTextContent\" style=\"padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;\">

																	<h3>Nouvelle réservation de vélo !&nbsp;</h3>
																	<ul>
																		<li>Date de début: $dateStartString</li>
																		<li>Date de fin: $dateEndString</li>
																		<li>Identification du vélo : $frameNumber</li>
																		<li>Marque : $brand</li>
																		<li>Modèle : $model</li>
																	</ul>
																		Rendez-vous sur votre interface <a href=\"https://www.kameobikes.com/mykameo.php\">MyKameo</a> pour plus d'informations.</p>
															</td>
													</tr>
											</tbody></table>
											<!--[if mso]>
											</td>
											<![endif]-->

											<!--[if mso]>
											</tr>
											</table>
											<![endif]-->
									</td>
							</tr>
					</tbody>
			</table>";



			include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_footer.php';

			$mail->Body = $body;

			if(!$mail->Send()) {
				 $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
					echo json_encode($response);
					die;
			}


		}

		$conn->close();
    successMessage("SM0006");
}else{
	errorMessage("ES0012");
}
?>
