<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


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
    
    
    include 'connexion.php';
	$sql="select * from reservations where DATE_START_2 < '$dateStart2String' and BIKE_ID = '$bikeID' and STAANN != 'D' ORDER BY DATE_START_2 DESC LIMIT 1";
    
	
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);

    $EmailClientBefore=$resultat['EMAIL'];
	$response['clientBefore']['end']= $resultat['DATE_END_2']; 
    
    include 'connexion.php';
	$sql="select * from customer_referential where EMAIL='$EmailClientBefore'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $length = $result->num_rows;
    
    
    if($length!="1"){
			$response['clientBefore']['name']="";
			$response['clientBefore']['surname']="";
			$response['clientBefore']['phone']="";
			$response['clientBefore']['mail']="";
	}else{
        $response['clientBefore']['name']=$resultat['NOM'];
        $response['clientBefore']['surname']=$resultat['PRENOM'];
        $response['clientBefore']['phone']=$resultat['PHONE'];
        $response['clientBefore']['mail']=$resultat['EMAIL'];
    }
    

    include 'connexion.php';
	$sql="select * from reservations where DATE_START_2 > '$dateStart2String' and BIKE_ID = '$bikeID' and STAANN != 'D' ORDER BY DATE_START_2 LIMIT 1";    
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}    
	$result = mysqli_query($conn, $sql);  
    $resultat = mysqli_fetch_assoc($result);
    $length = $result->num_rows;

    if($length!="1"){
			$response['clientAfter']['name']="";
			$response['clientAfter']['surname']="";
			$response['clientAfter']['phone']="";
			$response['clientAfter']['mail']="";
	}
	else{
	    $bookingIDAfter=$resultat['ID'];
		$IDClientAfter=$resultat['EMAIL'];
		$response['clientAfter']['start']= $resultat['DATE_START_2'];           

		
		include 'connexion.php';
		$sql="select * from customer_referential where email='$IDClientAfter'";
		if ($conn->query($sql) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
		$result = mysqli_query($conn, $sql);
		$length = $result->num_rows;
		if($length=="1"){
			$resultat = mysqli_fetch_assoc($result);    
			$response['clientAfter']['name']=$resultat['NOM'];
			$response['clientAfter']['surname']=$resultat['PRENOM'];
			$response['clientAfter']['phone']=$resultat['PHONE'];
			$response['clientAfter']['mail']=$resultat['EMAIL'];
		}
	}
    $response['response']="success";       
	echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0012");
}

?>