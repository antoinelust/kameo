<?php
include '../globalfunctions.php';

$rfid=$_GET['uid'];
$minutes=$_GET['minutes'];
$building=$_GET['building'];
$frameNumber=$_GET['frameNumber'];


include '../connexion.php';
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


include '../connexion.php';
$sql="SELECT * from customer_referential WHERE RFID='$rfid'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);  
$resultat = mysqli_fetch_assoc($result);
$length = $result->num_rows;
$conn->close();


if($length=="1"){
    $client=$resultat['EMAIL'];
    $company=$resultat['COMPANY'];
    
    include '../connexion.php';
    $sql="SELECT * from building_access WHERE BUILDING_CODE='$building'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);  
    $resultat = mysqli_fetch_assoc($result);
    $length = $result->num_rows;
    $conn->close();

    $buildingReference=$resultat['BUILDING_REFERENCE'];
    
        
    $dateStart=new DateTime();
    $dateStartString=$dateStart->format("Y-m-d H:i");
        
    $dateEnd=$dateStart;
    $interval="PT".$minutes."M";
    $dateEnd->add(new DateInterval($interval));
    $dateEndString=$dateEnd->format("Y-m-d H:i");
            
    $data=array("widget-new-booking-mail-customer" => $client, "widget-new-booking-frame-number" => $frameNumber, "widget-new-booking-building-start" => $buildingReference, "widget-new-booking-building-end" => $buildingReference, "widget-new-booking-locking-code" => "0000", "widget-new-booking-date-start" => $dateStartString , "widget-new-booking-date-end"=> $dateEndString);
        
    $test=CallAPI('POST', 'https://www.kameobikes.com/test/include/new_booking.php', $data);
        
    include '../connexion.php';
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