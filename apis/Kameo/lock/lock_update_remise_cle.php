<?php

$building=isset($_GET['building']) ? htmlspecialchars($_GET['building']) : NULL;
$frame_number=isset($_GET['frame_number']) ? htmlspecialchars($_GET['frame_number']) : NULL;
$emplacement=isset($_GET['emplacement']) ? htmlspecialchars($_GET['emplacement']) : NULL;

error_log("--------------------------------------------------------------------------------------- \n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_update_remise_cle.php - INPUT building :".$_GET['building']."\n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_update_remise_cle.php - INPUT frame_number :".$_GET['frame_number']."\n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_update_remise_cle.php - INPUT emplacement :".$_GET['emplacement']."\n", 3, "logs/logs_boxes.log");


if($building == NULL || $frame_number == NULL || $emplacement == NULL){
    echo "-1";
    die;
}


include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$sql="SELECT ID from customer_bikes WHERE FRAME_NUMBER = '$frame_number'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$resultat = mysqli_fetch_assoc($result);

$bike_ID=$resultat['ID'];


$sql="SELECT RESERVATION_ID from locking_bikes WHERE BUILDING = '$building' AND BIKE_ID = '$bike_ID'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$resultat = mysqli_fetch_assoc($result);

$reservationID=$resultat['RESERVATION_ID'];
error_log(date("Y-m-d H:i:s")." - lock_update_remise_cle.php - reservations :".$reservationID."\n", 3, "logs/logs_boxes.log");


$sql="UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, DATE_END_2=CURRENT_TIMESTAMP, USR_MAJ='mykameo', STATUS='Closed' WHERE ID='$reservationID'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$sql="SELECT BUILDING_END FROM reservations WHERE ID='$reservationID'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$resultat = mysqli_fetch_assoc($result);
$initiallyPlannedBuilding = $resultat['BUILDING_END'];

$sql="SELECT BUILDING_REFERENCE FROM building_access WHERE BUILDING_CODE='$building'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$resultat = mysqli_fetch_assoc($result);
$realBuildingEnd = $resultat['BUILDING_REFERENCE'];

if($initiallyPlannedBuilding != $realBuildingEnd){
  $sql="UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='mykameo', BUILDING_END='$realBuildingEnd' WHERE ID='$reservationID'";
  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }
}

$sql="UPDATE locking_bikes SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='mykameo', MOVING='N', PLACE_IN_BUILDING='$emplacement', BUILDING='$building' WHERE BIKE_ID='$bike_ID' AND BUILDING='$building'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$conn->close();
?>
