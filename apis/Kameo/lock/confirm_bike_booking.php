<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';

$rfid=isset($_GET['UID']) ? htmlspecialchars($_GET['UID']) : NULL;
$minutes=isset($_GET['minutes']) ? htmlspecialchars($_GET['minutes']) : NULL;
$building=isset($_GET['building']) ? htmlspecialchars($_GET['building']) : NULL;
$frameNumber=isset($_GET['frameNumber']) ? htmlspecialchars($_GET['frameNumber']) : NULL;

if($rfid == NULL || $minutes == NULL || $building == NULL || $frameNumber == NULL){
    echo "-3";
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


$sql="SELECT * from customer_referential WHERE RFID='$rfid'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);  
$resultat = mysqli_fetch_assoc($result);
$length = $result->num_rows;


if($length=="1"){
    $client=$resultat['EMAIL'];
    $company=$resultat['COMPANY'];
    
    $sql="SELECT * from building_access WHERE BUILDING_CODE='$building'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);  
    $resultat = mysqli_fetch_assoc($result);
    $length = $result->num_rows;

    $buildingReference=$resultat['BUILDING_REFERENCE'];
    
        
    $dateStart=new DateTime();
    $dateStartString=$dateStart->format("Y-m-d H:i");
        
    $dateEnd=$dateStart;
    $interval="PT".$minutes."M";
    $dateEnd->add(new DateInterval($interval));
    $dateEndString=$dateEnd->format("Y-m-d H:i");
            
    $data=array("widget-new-booking-mail-customer" => $client, "widget-new-booking-frame-number" => $frameNumber, "widget-new-booking-building-start" => $buildingReference, "widget-new-booking-building-end" => $buildingReference, "widget-new-booking-locking-code" => "0000", "widget-new-booking-date-start" => $dateStartString , "widget-new-booking-date-end"=> $dateEndString);
        
    $test=CallAPI('POST', 'https://www.kameobikes.com/test/include/new_booking.php', $data);
        
    $sql="SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE BIKE_ID LIKE (SELECT BIKE_ID FROM reservations WHERE ID = (SELECT ID_reservation FROM locking_code WHERE BUILDING_START ='$building' AND BIKE_ID='$bike_ID' AND CODE = '0' AND VALID = 'Y' AND DATE_BEGIN <= UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) AND DATE_END >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP())))";    
            
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);  
    $resultat = mysqli_fetch_assoc($result);
    $length = $result->num_rows;
    $conn->close();
    
    if($length==1){
        echo $resultat['PLACE_IN_BUILDING'];
    }else{
        echo "-1";
    }

    
}else{
    //pas d'utilisateur trouvé
    echo "-3";
}
?>