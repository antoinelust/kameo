<?php

$building=isset($_GET['building']) ? htmlspecialchars($_GET['building']) : NULL;
$code=isset($_GET['code']) ? htmlspecialchars($_GET['code']) : NULL;
$emplacement=isset($_GET['emplacement']) ? htmlspecialchars($_GET['emplacement']) : NULL;
$rfid=isset($_GET['UID']) ? htmlspecialchars($_GET['UID']) : NULL;


error_log("--------------------------------------------------------------------------------------- \n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_update_prise_cle.php - building :".$_GET['building']."\n", 3, "logs/logs_boxes.log");

if(isset($_GET['frame_number'])){
  error_log(date("Y-m-d H:i:s")." - lock_update_prise_cle.php - frame_number :".$_GET['frame_number']."\n", 3, "logs/logs_boxes.log");
}

error_log(date("Y-m-d H:i:s")." - lock_update_prise_cle.php - emplacement :".$_GET['emplacement']."\n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_update_prise_cle.php - RFID :".$rfid."\n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_update_prise_cle.php - code :".$_GET['code']."\n", 3, "logs/logs_boxes.log");

if( ($code==NULL && $rfid == NULL) || $emplacement == NULL || $building == NULL){
    echo "-1";
}


if($code==NULL){
    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
    $sql="SELECT * from customer_referential WHERE RFID = '$rfid'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $email=$resultat['EMAIL'];
    $conn->close();

    include '../connexion.php';

    $dateStartBooking=new DateTime();
    $interval = new DateInterval("PT15M");
    $dateStartBooking->add($interval);
    $dateStartBookingString=$dateStartBooking->format("Y-m-d H:i");



    $sql="SELECT * from reservations WHERE EMAIL = '$email' AND DATE_START_2 < '$dateStartBookingString' AND DATE_END_2 > CURRENT_TIMESTAMP() AND STATUS='Open' AND STAANN != 'D'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $reservationID=$resultat['ID'];
}else{
    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
    $sql="SELECT ID_reservation from locking_code WHERE BUILDING_START = '$building' AND CODE = '$code' AND VALID='Y'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $reservationID=$resultat['ID_reservation'];
    $sql="UPDATE reservations SET HEU_MAJ = CURRENT_TIMESTAMP, DATE_START_2 = CURRENT_TIMESTAMP WHERE ID ='$reservationID'";
    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
}

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$sql="SELECT BIKE_ID FROM locking_bikes WHERE BUILDING = '$building' AND PLACE_IN_BUILDING = '$emplacement'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$resultat = mysqli_fetch_assoc($result);
$bike_ID=$resultat['BIKE_ID'];

error_log(date("Y-m-d H:i:s")." - lock_update_prise_cle.php - ".$sql." \n", 3, "logs/logs_boxes.log");

$sql="UPDATE locking_bikes SET HEU_MAJ=CURRENT_TIMESTAMP(), MOVING='Y', PLACE_IN_BUILDING='-1', RESERVATION_ID='$reservationID' WHERE BIKE_ID='$bike_ID'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
error_log(date("Y-m-d H:i:s")." - lock_update_prise_cle.php - ".$sql." \n", 3, "logs/logs_boxes.log");

$result = mysqli_query($conn, $sql);
$conn->close();

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$sql="UPDATE locking_code SET HEU_MAJ=CURRENT_TIMESTAMP(), VALID='N' WHERE ID_reservation='$reservationID'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$conn->close();

echo "1";
error_log(date("Y-m-d H:i:s")." - lock_update_prise_cle.php - SUCCESS \n", 3, "logs/logs_boxes.log");

?>
