<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();

include 'globalfunctions.php';

$user = $_POST['widget-new-booking-mail-customer'];
$bikeID=$_POST['bikeID'];
$buildingStart=$_POST['widget-new-booking-building-start'];
$buildingEnd=$_POST['widget-new-booking-building-end'];
$lockingcode=$_POST['widget-new-booking-locking-code'];

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
    $sql= "select * from reservations aa where aa.STAANN!='D' and aa.BIKE_ID = '$bikeID' and not exists (select 1 from reservations bb where bb.STAANN!='D' and aa.ID=bb.BIKE_ID and ((bb.DATE_END_2 > '$dateStart_2String' and bb.DATE_END_2 < '$dateEnd_2String') OR (bb.DATE_START_2>'$dateStart_2String' and bb.DATE_START_2<'$dateEnd_2String')))";

   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
    $length = $result->num_rows;



	 if($length == 0){
        errorMessage("ES0019");
    }

	include 'connexion.php';

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

        $conn->close();

    }


    successMessage("SM0006");
}else{
	errorMessage("ES0012");
}
?>
