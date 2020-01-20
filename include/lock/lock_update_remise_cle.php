<?php


$building=$_GET['building'];
$frame_number=$_GET['frame_number'];
$emplacement=$_GET['emplacement'];

include '../connexion.php';
$sql="SELECT RESERVATION_ID from locking_bikes WHERE BUILDING = '$building' AND FRAME_NUMBER = '$frame_number'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);  
$resultat = mysqli_fetch_assoc($result);  
$conn->close();

$reservationID=$resultat['RESERVATION_ID'];

include '../connexion.php';
$sql="UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='mykameo', STATUS='Closed' WHERE ID='$reservationID'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);  
$conn->close();

include '../connexion.php';
$sql="UPDATE locking_bikes SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='mykameo', MOVING='N', PLACE_IN_BUILDING='$emplacement', BUILDING='$building' WHERE FRAME_NUMBER='$frame_number'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);  
$conn->close();
?>