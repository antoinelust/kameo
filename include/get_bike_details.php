<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$frameNumber=$_POST['frameNumber'];

$response=array();

if($frameNumber != NULL)
{

	
    include 'connexion.php';
	$sql="SELECT *  FROM customer_bikes WHERE FRAME_NUMBER = '$frameNumber'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $row = mysqli_fetch_assoc($result);



    $response['response']="success";
    $response['model']=$row['MODEL'];
    $response['contractReference']=$row['CONTRACT_REFERENCE'];            
    $response['frameReference']=$row['FRAME_REFERENCE'];            
    if($row['LEASING']=="Y"){
        $response['contractType']="leasing";
        $response['contractStart']=$row['CONTRACT_START'];
        $response['contractEnd']=$row['CONTRACT_END'];
    }else{
        $response['contractType']="other";
        $response['contractStart']="N/A";
        $response['contractEnd']="N/A";
    }
    $response['status']=$row['STATUS'];
    $company=$row['COMPANY'];
    include 'connexion.php';
    $sql="SELECT bb.BUILDING_REFERENCE, bb.BUILDING_FR FROM bike_building_access aa, building_access bb WHERE aa.BIKE_NUMBER='$frameNumber' and BUILDING_REFERENCE=aa.BUILDING_CODE and aa.STAANN!='D'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['building'][$i]['buildingCode']=$row['BUILDING_REFERENCE'];
        $response['building'][$i]['access']=true;
        $response['building'][$i]['descriptionFR']=$row['BUILDING_FR'];
        $i++;
    }
    
    include 'connexion.php';
    $sql="SELECT bb.BUILDING_REFERENCE, bb.BUILDING_FR FROM bike_building_access aa, building_access bb WHERE aa.BIKE_NUMBER='$frameNumber' and BUILDING_REFERENCE=aa.BUILDING_CODE and aa.STAANN='D'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $length = $length+$result->num_rows;
    while($row = mysqli_fetch_array($result)){
        $response['building'][$i]['buildingCode']=$row['BUILDING_REFERENCE'];
        $response['building'][$i]['access']=false;
        $response['building'][$i]['descriptionFR']=$row['BUILDING_FR'];
        $i++;
    }
    
    
    include 'connexion.php';
    $sql="SELECT BUILDING_REFERENCE, BUILDING_FR FROM building_access WHERE COMPANY = '$company' AND not exists (select 1 from bike_building_access bb where bb.BUILDING_CODE=BUILDING_REFERENCE and bb.BIKE_NUMBER='$frameNumber')";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $response['buildingNumber'] = $result->num_rows + $length;
    while($row = mysqli_fetch_array($result)){
        $response['building'][$i]['buildingCode']=$row['BUILDING_REFERENCE'];
        $response['building'][$i]['access']=false;
        $response['building'][$i]['descriptionFR']=$row['BUILDING_FR'];
        $i++;
    }
    
    
	echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0006");
}

?>