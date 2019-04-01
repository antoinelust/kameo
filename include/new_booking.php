<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();

include 'globalfunctions.php';


//timestampStart and timestampEnd can be decomissioned from mykameo
//$timestampStart=$_POST['widget-new-booking-timestamp-start'];
//$timestampEnd=$_POST['widget-new-booking-timestamp-end'];

$user = $_POST['widget-new-booking-mail-customer'];
$frameNumber=$_POST['widget-new-booking-frame-number'];
$buildingStart=$_POST['widget-new-booking-building-start'];
$buildingEnd=$_POST['widget-new-booking-building-end'];
$lockingcode=$_POST['widget-new-booking-locking-code'];

$temp=new DateTime($_POST['widget-new-booking-date-start']);
$temp->sub(new DateInterval('PT15M'));
$dateStart=strtotime($temp->format('Y-m-d H:i'));

$temp=new DateTime($_POST['widget-new-booking-date-end']);
$dateEnd=strtotime($temp->format('Y-m-d H:i'));



if( $_SERVER['REQUEST_METHOD'] == 'POST' && $frameNumber != NULL & $buildingStart != NULL && $buildingEnd != NULL && $dateStart != NULL && $dateEnd != NULL && $user!= NULL ) {

	include 'connexion.php';
    $sql= "select * from reservations aa where aa.STAANN!='D' and aa.FRAME_NUMBER = '$frameNumber' and not exists (select 1 from reservations bb where bb.STAANN!='D' and aa.FRAME_NUMBER=bb.FRAME_NUMBER and ((bb.DATE_END > '$dateStart' and bb.DATE_END < '$dateEnd') OR (bb.DATE_START>'$dateStart' and bb.DATE_START<'$dateEnd')))";
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
    $sql= "INSERT INTO reservations (USR_MAJ, FRAME_NUMBER, DATE_START, BUILDING_START, DATE_END, BUILDING_END, EMAIl, STAANN) VALUES ('new_booking', '$frameNumber', '$dateStart', '$buildingStart', '$dateEnd', '$buildingEnd', '$user', '')";


   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	} 
    $conn->close();
    
    if($lockingcode!=""){
        include 'connexion.php';

        $sql= "select ID from reservations where FRAME_NUMBER = '$frameNumber' and EMAIL = '$user' and DATE_START = '$dateStart' and DATE_END = '$dateEnd' and STAANN != 'D' ";
        
        error_log($sql, 3, "mes-erreurs.log");

        
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);  
        $resultat = mysqli_fetch_assoc($result);    
        $ID = $resultat['ID'];
        $sql= "INSERT INTO locking_code (ID_reservation, DATE_BEGIN, DATE_END, BUILDING_START, CODE, VALID) VALUES ('$ID','$dateStart', '$dateEnd', '$buildingStart', '$lockingcode', 'Y')";
        
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        } 
        $conn->close();

    }
    
    
    successMessage("SM0006");
} else{
	errorMessage("ES0012");
}
?>