<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=$_POST['email'];
$dateStart=isset( $_POST['dateStart'] ) ? new DateTime($_POST['dateStart']) : NULL;
$dateEnd=isset( $_POST['dateEnd'] ) ? new DateTime($_POST['dateEnd']) : NULL;
$bikeValue=isset( $_POST['bikeValue'] ) ? $_POST['bikeValue'] : NULL;


$response=array();

if($email != NULL && $dateStart != NULL && $dateEnd != NULL && $bikeValue != NULL)
{

    $dateStartString=$dateStart->format('Y-m-d H:i');
    $dateEndString=$dateEnd->format('Y-m-d H:i');
    
    
	$timestamp_now=time();
	
    include 'connexion.php';
    
    if($bikeValue=="all")
    {
        $sql="SELECT dd.ID, dd.BIKE_ID, cc.FRAME_NUMBER, ee.BUILDING_FR as building_start_fr, gg.BUILDING_FR as building_end_fr, ee.BUILDING_EN, ee.BUILDING_NL, dd.EMAIL, dd.DATE_START_2, dd.DATE_END_2 FROM customer_bikes cc, reservations dd, building_access ee, building_access gg where cc.COMPANY=(select ff.COMPANY from customer_referential ff where EMAIL='$email') AND cc.ID=dd.BIKE_ID and dd.STAANN!='D' and dd.DATE_START_2>'$dateStartString' and dd.DATE_END_2<='$dateEndString' and dd.BUILDING_START=ee.BUILDING_REFERENCE and dd.BUILDING_END=gg.BUILDING_REFERENCE ORDER BY DATE_START_2";
    } else {
        $sql="SELECT dd.ID, dd.BIKE_ID, cc.FRAME_NUMBER, ee.BUILDING_FR, gg.BUILDING_EN, ee.BUILDING_NL, dd.EMAIL, dd.DATE_START_2, dd.DATE_END_2 FROM customer_bikes cc, reservations dd, building_access ee, building_access gg where cc.COMPANY=(select ff.COMPANY from customer_referential ff where EMAIL='$email') AND cc.ID=dd.BIKE_ID and dd.BIKE_ID='$bikeValue' and dd.STAANN!='D' and dd.DATE_START_2>'$dateStartString' and dd.DATE_END_2<='$dateEndString' and dd.BUILDING_START=ee.BUILDING_REFERENCE and dd.BUILDING_END=gg.BUILDING_REFERENCE ORDER BY DATE_START_2";
    }
    
    if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;        
	}
	
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
	$response['bookingNumber']=$length;
    $response['response']="success";
	$response['sql']=$sql;


    
    $i=0;
    while($row = mysqli_fetch_array($result))

    {

        $response['booking'][$i]['reservationID']=$row['ID'];
		$response['booking'][$i]['frameNumber']=$row['FRAME_NUMBER'];
		$response['booking'][$i]['bikeID']=$row['BIKE_ID'];
		$response['booking'][$i]['dateStart']=$row['DATE_START_2'];      
		$response['booking'][$i]['dateEnd']=$row['DATE_END_2'];
		$response['booking'][$i]['buildingStart']=$row['building_start_fr'];      
		$response['booking'][$i]['buildingEnd']=$row['building_end_fr'];      
        
		$response['booking'][$i]['user']=$row['EMAIL'];
        $i++;

	}

	echo json_encode($response);
    die;

}
else
{
    if($email==NULL){
        errorMessage("ES0006");    
    }else{
        errorMessage("ES0012");
    }
	
}

?>