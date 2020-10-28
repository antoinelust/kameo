<?php

$building=isset($_GET['building']) ? htmlspecialchars($_GET['building']) : NULL;
$frame_number=isset($_GET['frame_number']) ? htmlspecialchars($_GET['frame_number']) : NULL;
$emplacement=isset($_GET['emplacement']) ? htmlspecialchars($_GET['emplacement']) : NULL;

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
$conn->close();

$bike_ID=$resultat['ID'];


include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$sql="SELECT RESERVATION_ID from locking_bikes WHERE BUILDING = '$building' AND BIKE_ID = '$bike_ID'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$resultat = mysqli_fetch_assoc($result);
$conn->close();

$reservationID=$resultat['RESERVATION_ID'];

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$sql="UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='mykameo', STATUS='Closed' WHERE ID='$reservationID'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$conn->close();

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$sql="UPDATE locking_bikes SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='mykameo', MOVING='N', PLACE_IN_BUILDING='$emplacement', BUILDING='$building' WHERE BIKE_ID='$bike_ID' AND BUILDING='$building'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$conn->close();
?>
