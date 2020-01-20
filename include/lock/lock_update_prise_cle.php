<?php
include '../connexion.php';

$building=$_GET['building'];
$emplacement=$_GET['emplacement'];
$code=$_GET['code'];


$sql="SELECT ID_reservation from locking_code WHERE BUILDING_START = '$building' AND CODE = '$code'";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);  
$resultat = mysqli_fetch_assoc($result);  
$conn->close();

$reservationID=$resultat['ID_reservation'];


include '../connexion.php';
$sql="UPDATE locking_bikes SET MOVING='Y', PLACE_IN_BUILDING='-1', RESERVATION_ID='$reservationID' WHERE BUILDING = '$building' AND PLACE_IN_BUILDING = '$emplacement'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);  
$conn->close();

include '../connexion.php';
$sql="UPDATE locking_code SET VALID='N' WHERE BUILDING_START = '$building' AND CODE = '$code'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);  
$conn->close();

?>