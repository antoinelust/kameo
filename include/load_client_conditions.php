<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';


$userID=$_POST['userID'];

if($userID != NULL)
{
	
    include 'connexion.php';
	$sql="select * from customer_referential where EMAIL = '$userID'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
	$result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);
    
    $frameNumber=$resultat['FRAME_NUMBER'];
    $company=substr($frameNumber,0,3);
    $response['clientConditions']['administrator']=$resultat['ADMINISTRATOR'];   

    
    $sql="select * from conditions where FRAME_NUMBER like '$frameNumber%'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
	$result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);    

    //vérifier nom du champ SQL
	$response['text']="success";
	$response['message']="";
    $response['clientConditions']['bookingDays']=$resultat['BOOKING_DAYS']; 
    $response['clientConditions']['bookingLength']=$resultat['BOOKING_LENGTH']; 
    $response['clientConditions']['assistance']=$resultat['ASSISTANCE']; 


	
    
	echo json_encode($response);
    die;

}
else
{
	errorMessage(ES0012);
}

?>