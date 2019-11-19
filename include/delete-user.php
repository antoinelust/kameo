<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=$_POST['widget-deleteUser-form-mail'];
$confirmation=$_POST['widget-deleteUser-form-confirmation'];

if($confirmation!="DELETE"){
    errorMessage("ES0028");
}

$response=array();

if($email != NULL)
{

    include 'connexion.php';
	$sql="DELETE FROM customer_referential WHERE EMAIL = '$email'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
        
        

	$sql="DELETE FROM customer_bike_access WHERE EMAIL = '$email'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
        
	$sql="DELETE FROM customer_building_access WHERE EMAIL = '$email'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
    $conn->close();  
    
    successMessage("SM0009");

}
else
{
	errorMessage("ES0012");
}

?>