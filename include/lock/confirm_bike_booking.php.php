<?php
include '../globalfunctions.php';

$rfid=$_GET['uid'];
$minutes=$_GET['minutes'];
$building=$_GET['building'];
$frameNumber=$_GET['frameNumber'];


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
        
    $test=CallAPI('POST', 'http://localhost:81/kameo/include/new_booking.php', $data);
    
    var_dump(json_decode($test)); 

    
    

    
}else{
    //pas d'utilisateur trouvé
    echo "-3";
}
?>