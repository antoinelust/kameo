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
	
	// 1st part : get all the records in the past
	
	//vérifier pour les champs
    include 'connexion.php';
    
    $date1stJanuary=date('Y-01-01');
    
    $sql="select * from reservations where DATE_START_2>'$date1stJanuary' and EMAIL='$user' and STAANN != 'D'";
    
   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}    
    $result = mysqli_query($conn, $sql);     
    $response['maxBookingsPerYear']= $result->num_rows;            
        
        
    $date1stOfMonth=date('Y-m-01');
    $sql="select * from reservations where DATE_START_2>'$date1stOfMonth' and EMAIL='$user' and STAANN != 'D'";
   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);     
    $response['maxBookingsPerMonth']= $result->num_rows;            
    
    $sql="SELECT * from conditions where COMPANY=(select COMPANY from customer_referential where EMAIL='$user')";
   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);     
    $resultat = mysqli_fetch_assoc($result);
    $response['maxBookingsPerYearCondition']= $resultat['MAX_BOOKINGS_YEAR'];            
    $response['maxBookingsPerMonthCondition']= $resultat['MAX_BOOKINGS_MONTH'];
    
    
    
	$sql="select aa.*, bb.FRAME_NUMBER from reservations aa, customer_bikes bb where aa.EMAIL = '$user' and aa.DATE_END_2 < now() and aa.STAANN!='D' and aa.BIKE_ID=bb.ID order by DATE_START_2 DESC";
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
		$response['booking'][$i]['ID']=$row['ID'];
        $response['booking'][$i]['bikeID']=$row['BIKE_ID'];
        $response['booking'][$i]['frameNumber']=$row['FRAME_NUMBER'];
		$response['booking'][$i]['start']= $row['DATE_START_2'];            
		$response['booking'][$i]['end']=$row['DATE_END_2'];
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
	$sql="select aa.*, bb.FRAME_NUMBER from reservations aa, customer_bikes bb where aa.EMAIL = '$user' and aa.DATE_END_2 > now() and aa.STAANN!='D' and aa.BIKE_ID=bb.ID order by DATE_START_2";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    
	$response['future_bookings']=$length;
    $response['booking']['codePresence']=false;
    while($row = mysqli_fetch_array($result))
    {
        $buildingStart=$row['BUILDING_START'];
        $buildingEnd=$row['BUILDING_END'];
        $bikeID=$row['BIKE_ID'];
        
        
		$response['booking'][$i]['bikeID']=$bikeID;
		$response['booking'][$i]['frameNumber']=$row['FRAME_NUMBER'];;
		$response['booking'][$i]['start']= $row['DATE_START_2'];
		$response['booking'][$i]['end']=$row['DATE_END_2'];
		$response['booking'][$i]['building_start']=$row['BUILDING_START'];
		$response['booking'][$i]['building_end']=$row['BUILDING_END'];
		$response['booking'][$i]['time']="past";
		$response['booking'][$i]['time']="future";
        $response['booking'][$i]['bookingID']=$row['ID'];
        $ID=$row['ID'];      
        
        
        $buildingReference=$row['BUILDING_START'];        
        include 'connexion.php';
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

        $dateEnd=$row['DATE_END_2'];
        $sql3="select * from reservations where BIKE_ID='$bikeID' and DATE_START_2>'$dateEnd' and STAANN!='D' ORDER BY DATE_START_2 LIMIT 1";
        
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
        
        $sql4="select * from locking_code where ID_reservation='$ID'";
        
        if ($conn->query($sql4) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }


        $result4 = mysqli_query($conn, $sql4); 
        $length4 = $result4->num_rows;

        if ($length4 == 0){
            $response['booking'][$i]['code']=false;
            $response['booking'][$i]['codeValue']="";
            
        }
        else{
            $resultat4 = mysqli_fetch_assoc($result4);
            $response['booking'][$i]['code']=true; 
            $response['booking']['codePresence']=true;
            $response['booking'][$i]['codeValue']=$resultat4['CODE'];
                
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