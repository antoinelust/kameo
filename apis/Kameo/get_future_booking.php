<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
require_once __DIR__ .'/authentication.php';
$token = getBearerToken();

if(!get_user_permissions("search", $token)){
  error_message('403');
}
log_inputs($token);

$bookingID=$_POST['bookingID'];



if($bookingID != NULL)
{

    include 'connexion.php';
	$sql="SELECT aa.BIKE_ID, aa.DATE_START_2, aa.DATE_END_2, bb.BUILDING_FR AS 'building_start', cc.BUILDING_FR
		AS 'building_end', dd.FRAME_NUMBER, dd.TYPE, ee.BRAND, ee.MODEL, ee.FRAME_TYPE
		FROM reservations aa, building_access bb, building_access cc, customer_bikes dd, bike_catalog ee
		WHERE aa.ID = '$bookingID' AND aa.BUILDING_START=bb.BUILDING_REFERENCE AND aa.BUILDING_END=cc.BUILDING_REFERENCE AND aa.BIKE_ID=dd.ID AND ee.ID = dd.TYPE";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}


	$result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();

    $dateStart2String=date($resultat['DATE_START_2']);


	$frameNumber = $resultat['FRAME_NUMBER'];
	$bikeID = $resultat['BIKE_ID'];
    $response['booking']['ID']=$bookingID;
    $response['booking']['bikeID']=$bikeID;
    $response['booking']['buildingStart']= $resultat['building_start'];
    $response['booking']['buildingEnd']= $resultat['building_end'];
    $response['booking']['start']=$resultat['DATE_START_2'];
	$response['booking']['end']=$resultat['DATE_END_2'];
	$response['booking']['frameNumber']=$resultat['FRAME_NUMBER'];
	$response['booking']['model']=$resultat['MODEL'];
	$response['booking']['frameType']=$resultat['FRAME_TYPE'];
	$response['booking']['brand']=$resultat['BRAND'];


    include 'connexion.php';
    $sql="select * from locking_code where ID_reservation='$bookingID'";


    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();


    if ($length == 0){
        $response['booking']['code']=false;
    }
    else{
        $response['booking']['code']=$resultat['CODE'];
    }

    $response['response']="success";
		log_output($response);
		echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0012");
}

?>
