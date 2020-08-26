<?php

$building=isset($_GET['building']) ? htmlspecialchars($_GET['building']) : NULL;
$code=isset($_GET['code']) ? htmlspecialchars($_GET['code']) : NULL;
$emplacement=isset($_GET['emplacement']) ? htmlspecialchars($_GET['emplacement']) : NULL;
$rfid=isset($_GET['UID']) ? htmlspecialchars($_GET['UID']) : NULL;

    
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
    $conn->close();
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
    $conn->close();
    $reservationID=$resultat['ID_reservation'];
    
    
    
}

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$sql="UPDATE locking_bikes SET HEU_MAJ=CURRENT_TIMESTAMP(), MOVING='Y', PLACE_IN_BUILDING='-1', RESERVATION_ID='$reservationID' WHERE BUILDING = '$building' AND PLACE_IN_BUILDING = '$emplacement'";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
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

?>