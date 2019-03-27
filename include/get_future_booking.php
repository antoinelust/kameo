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
	$sql="select * from reservations where ID = '$bookingID'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);
    
    $dateStart = $resultat['DATE_START'];
    $IDClient = $resultat['ID'];
	$frameNumber = $resultat['FRAME_NUMBER'];
    
    
    include 'connexion.php';
	$sql="select * from reservations where DATE_START < '$dateStart' and FRAME_NUMBER = '$frameNumber' and STAANN != 'D' ORDER BY DATE_START DESC LIMIT 1";
	
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);

    $EmailClientBefore=$resultat['EMAIL'];
	$response['clientBefore']['depositHour']= date('H:i',$resultat['DATE_END']); 
    $response['clientBefore']['depositDay']= date('d/m/Y',$resultat['DATE_END']);            
    
    include 'connexion.php';
	$sql="select * from customer_referential where EMAIL='$EmailClientBefore'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    
    $response['clientBefore']['name']=$resultat['NOM'];
    $response['clientBefore']['surname']=$resultat['PRENOM'];
    $response['clientBefore']['phone']=$resultat['PHONE'];
    $response['clientBefore']['mail']=$resultat['EMAIL'];

    include 'connexion.php';
	$sql="select * from reservations where DATE_START > '$dateStart' and FRAME_NUMBER = '$frameNumber' and STAANN != 'D' ORDER BY DATE_START LIMIT 1";
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
		$IDClientAfter=$resultat['ID_CLIENT'];
		$response['clientAfter']['intakeHour']= date('H:i',$resultat['DATE_START']);            
		$response['clientAfter']['intakeDay']= date('d/m/Y',$resultat['DATE_START']);            

		
		include 'connexion.php';
		$sql="select * from customer_referential where national_registry_number='$IDClientAfter'";
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
	
	

	echo json_encode($response);
    die;

}
else
{
	errorMessage(ES0012);
}

?>