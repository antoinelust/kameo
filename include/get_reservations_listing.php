<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=$_POST['email'];
$dateStart=isset( $_POST['timeStampStart'] ) ? $_POST['timeStampStart'] : NULL;
$dateEnd=isset( $_POST['timeStampEnd'] ) ? $_POST['timeStampEnd'] : NULL;
$bikeValue=isset( $_POST['bikeValue'] ) ? $_POST['bikeValue'] : NULL;

$response=array();

if($email != NULL && $dateStart != NULL && $dateEnd != NULL && $bikeValue != NULL)
{

	$timestamp_now=time();
	
    include 'connexion.php';
    
    if($bikeValue=="all")
    {
        $sql="SELECT dd.ID, dd.FRAME_NUMBER, ee.BUILDING_FR as building_start_fr, gg.BUILDING_FR as building_end_fr, ee.BUILDING_EN, ee.BUILDING_NL, dd.EMAIL, dd.DATE_START, dd.DATE_END FROM customer_bikes cc, reservations dd, building_access ee, building_access gg where cc.COMPANY=(select ff.COMPANY from customer_referential ff where EMAIL='$email') AND cc.FRAME_NUMBER=dd.FRAME_NUMBER and dd.STAANN!='D' and dd.DATE_START>'$dateStart' and dd.DATE_END<'$dateEnd' and dd.BUILDING_START=ee.BUILDING_REFERENCE and dd.BUILDING_END=gg.BUILDING_REFERENCE ORDER BY DATE_START";
    } else {
        $sql="SELECT dd.ID, dd.FRAME_NUMBER, ee.BUILDING_FR, gg.BUILDING_EN, ee.BUILDING_NL, dd.EMAIL, dd.DATE_START, dd.DATE_END FROM customer_bikes cc, reservations dd, building_access ee, building_access gg where cc.COMPANY=(select ff.COMPANY from customer_referential ff where EMAIL='$email') AND cc.FRAME_NUMBER=dd.FRAME_NUMBER and dd.FRAME_NUMBER='$bikeValue' and dd.STAANN!='D' and dd.DATE_START>'$dateStart' and dd.DATE_END<'$dateEnd' and dd.BUILDING_START=ee.BUILDING_REFERENCE and dd.BUILDING_END=gg.BUILDING_REFERENCE ORDER BY DATE_START";
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
		$response['booking'][$i]['dateStartFR']=date('d/m/Y H:i', $row['DATE_START']).' - '.$row['building_start_fr'];            
		$response['booking'][$i]['dateStartEN']=date('d/m/Y H:i', $row['DATE_START']).' - '.$row['building_start_fr'];            
		$response['booking'][$i]['dateStartNL']=date('d/m/Y H:i', $row['DATE_START']).' - '.$row['building_start_fr'];            
		$response['booking'][$i]['dateEndFR']=date('d/m/Y H:i', $row['DATE_END']).' - '.$row['building_end_fr'];            
		$response['booking'][$i]['dateEndEN']=date('d/m/Y H:i', $row['DATE_END']).' - '.$row['building_end_fr'];            
		$response['booking'][$i]['dateEndNL']=date('d/m/Y H:i', $row['DATE_END']).' - '.$row['building_end_fr'];  
        
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