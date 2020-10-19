<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=$_POST['email'];
$response=array();

if($email != NULL)
{
	
    include 'connexion.php';
	$sql="SELECT * FROM customer_referential dd where EMAIL='$email'";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();   


    $response['response']="success";
    $response['user']['name']=$resultat['NOM'];
    $response['user']['firstName']=$resultat['PRENOM'];            
    $response['user']['email']=$resultat['EMAIL'];
    $response['user']['phone']=$resultat['PHONE'];
    $response['user']['staann']=$resultat['STAANN'];
    if($resultat['ADMINISTRATOR']==''){
        $response['user']['administrator']='N';
    }else{
        $response['user']['administrator']=$resultat['ADMINISTRATOR'];    
    }
    
    $company=$resultat['COMPANY'];

    
    
    //Partie pour les bâtiments
    
    include 'connexion.php';
    $sql="SELECT bb.BUILDING_REFERENCE, bb.BUILDING_FR FROM customer_building_access aa, building_access bb WHERE aa.EMAIL='$email' and BUILDING_REFERENCE=aa.BUILDING_CODE and aa.STAANN!='D'";
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
    $sql="SELECT bb.BUILDING_REFERENCE, bb.BUILDING_FR FROM customer_building_access aa, building_access bb WHERE aa.EMAIL='$email' and BUILDING_REFERENCE=aa.BUILDING_CODE and aa.STAANN='D'";
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
    $sql="SELECT BUILDING_REFERENCE, BUILDING_FR FROM building_access WHERE COMPANY = '$company' AND not exists (select 1 from customer_building_access bb where bb.BUILDING_CODE=BUILDING_REFERENCE and bb.EMAIL='$email')";

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
    

    
    // Partie pour les vélos
    
    include 'connexion.php';
    $sql="SELECT bb.ID, bb.FRAME_NUMBER, bb.MODEL FROM customer_bike_access aa, customer_bikes bb WHERE aa.EMAIL='$email' and bb.ID=aa.BIKE_ID and aa.STAANN!='D' and aa.TYPE='partage'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['bike'][$i]['bikeID']=$row['ID'];
        $response['bike'][$i]['access']=true;
        $response['bike'][$i]['model']=$row['MODEL'];
        $i++;
    }
    
    
    include 'connexion.php';
    $sql="SELECT bb.ID, bb.FRAME_NUMBER, bb.MODEL FROM customer_bike_access aa, customer_bikes bb WHERE aa.EMAIL='$email' and aa.BIKE_ID=bb.ID and aa.STAANN='D' and aa.TYPE='partage'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $length = $length+$result->num_rows;
    while($row = mysqli_fetch_array($result)){
        $response['bike'][$i]['bikeID']=$row['ID'];
        $response['bike'][$i]['access']=false;
        $response['bike'][$i]['model']=$row['MODEL'];
        $i++;
    }
        
    
    include 'connexion.php';
    $sql="SELECT aa.ID, FRAME_NUMBER, MODEL FROM customer_bikes aa WHERE COMPANY = '$company' AND exists (select 1 from customer_bike_access bb where bb.BIKE_ID=aa.ID and bb.TYPE='partage')";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $response['bikeNumber'] = $result->num_rows + $length;
    while($row = mysqli_fetch_array($result)){
        $response['bike'][$i]['bikeID']=$row['ID'];
        $response['bike'][$i]['access']=false;
        $response['bike'][$i]['model']=$row['MODEL'];
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