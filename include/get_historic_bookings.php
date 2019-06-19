<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$user=$_POST['user'];
$response=array();

if($user != NULL)
{

	$timestamp_now=time();
	
	// 1st part : get all the records in the past
	
	//vérifier pour les champs
    include 'connexion.php';
	$sql="select * from reservations where EMAIL = '$user' and DATE_END < '$timestamp_now' and STAANN!='D' order by DATE_START DESC LIMIT 5";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
	$response['previous_bookings']=$length;


    
    $i=0;
    while($row = mysqli_fetch_array($result))

    {

        $response['booking'][$i]['dayStart']= date('d/m/Y',$row['DATE_START']);            
		$response['booking'][$i]['dayEnd']= date('d/m/Y',$row['DATE_END']);            
        $response['booking'][$i]['frameNumber']=$row['FRAME_NUMBER'];
		$response['booking'][$i]['hour_start']= date('H:i',$row['DATE_START']);            
		$response['booking'][$i]['hour_end']=date('H:i',$row['DATE_END']);
		$response['booking'][$i]['building_start']=$row['BUILDING_START'];
		$response['booking'][$i]['building_end']=$row['BUILDING_END'];
		$response['booking'][$i]['time']="past";       
        
        $buildingReference=$row['BUILDING_START'];        
        $sql2="select * from building_access where BUILDING_REFERENCE='$buildingReference'";
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result2 = mysqli_query($conn, $sql2);        
        $resultat2 = mysqli_fetch_assoc($result2);
        $response['booking'][$i]['building_start_fr']=$resultat2['BUILDING_FR'];
        $response['booking'][$i]['building_start_en']=$resultat2['BUILDING_EN'];
        $response['booking'][$i]['building_start_nl']=$resultat2['BUILDING_NL'];
        
        $buildingReference=$row['BUILDING_END'];        
        $sql2="select * from building_access where BUILDING_REFERENCE='$buildingReference'";
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result2 = mysqli_query($conn, $sql2);        
        $resultat2 = mysqli_fetch_assoc($result2);
        $response['booking'][$i]['building_end_fr']=$resultat2['BUILDING_FR'];
        $response['booking'][$i]['building_end_en']=$resultat2['BUILDING_EN'];
        $response['booking'][$i]['building_end_nl']=$resultat2['BUILDING_NL'];
        
        $i++;

	}
	//2nd part : get all the records in the future

    include 'connexion.php';
	$sql="select * from reservations where EMAIL = '$user' and DATE_START > '$timestamp_now' and STAANN!='D' order by DATE_START LIMIT 5";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    
	$response['future_bookings']=$length;
    
    while($row = mysqli_fetch_array($result))
    {

        
        $frameNumber=$row['FRAME_NUMBER'];
        $buildingStart=$row['BUILDING_START'];
        $buildingEnd=$row['BUILDING_END'];
        
        
		$response['booking'][$i]['frameNumber']=$row['FRAME_NUMBER'];
		$response['booking'][$i]['dayStart']= date('d/m/Y',$row['DATE_START']);            
		$response['booking'][$i]['dayEnd']= date('d/m/Y',$row['DATE_END']);            
		$response['booking'][$i]['hour_start']= date('H:i',$row['DATE_START']);            
		$response['booking'][$i]['hour_end']=date('H:i',$row['DATE_END']);
		$response['booking'][$i]['building_start']=$row['BUILDING_START'];
		$response['booking'][$i]['building_end']=$row['BUILDING_END'];
		$response['booking'][$i]['time']="past";
		$response['booking'][$i]['time']="future";
        $response['booking'][$i]['bookingID']=$row['ID'];
        
        $buildingReference=$row['BUILDING_START'];        
        $sql2="select * from building_access where BUILDING_REFERENCE='$buildingReference'";
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result2 = mysqli_query($conn, $sql2);        
        $resultat2 = mysqli_fetch_assoc($result2);
        $response['booking'][$i]['building_start_fr']=$resultat2['BUILDING_FR'];
        $response['booking'][$i]['building_start_en']=$resultat2['BUILDING_EN'];
        $response['booking'][$i]['building_start_nl']=$resultat2['BUILDING_NL'];
        
        $buildingReference=$row['BUILDING_END'];        
        $sql2="select * from building_access where BUILDING_REFERENCE='$buildingReference'";
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result2 = mysqli_query($conn, $sql2);        
        $resultat2 = mysqli_fetch_assoc($result2);
        $response['booking'][$i]['building_end_fr']=$resultat2['BUILDING_FR'];
        $response['booking'][$i]['building_end_en']=$resultat2['BUILDING_EN'];
        $response['booking'][$i]['building_end_nl']=$resultat2['BUILDING_NL'];

        $dateEnd=$row['DATE_END'];
        $sql3="select * from reservations where FRAME_NUMBER='$frameNumber' and DATE_START>'$dateEnd' and STAANN!='D' ORDER BY DATE_START LIMIT 1";
        
        if ($conn->query($sql3) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }


        $result3 = mysqli_query($conn, $sql3); 
        $length3 = $result3->num_rows;

        if ($length3 == 0){
            $response['booking'][$i]['annulation']=true;
        }
        else{
            $resultat3 = mysqli_fetch_assoc($result3);
            $buildingStartNext=$resultat3['BUILDING_START'];
            $buildingEndNext=$resultat3['BUILDING_END'];
            if ($buildingStartNext==$buildingStart && $buildingEndNext==$buildingEnd){
                $response['booking'][$i]['annulation']=true;
            } else{
                $response['booking'][$i]['annulation']=false;
            }
        }     

        
        $i++;
	}
	
    $response['response']="success";
	echo json_encode($response);
    die;

}
else
{
	errorMessage(ES0012);
}

?>