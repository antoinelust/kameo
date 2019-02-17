<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$frameNumber=$_POST['widget-updateBikeStatus-form-frameNumber'];
$status=$_POST['bikeStatus'];

$response=array();

if($frameNumber != NULL && $status != NULL)
{

	
    include 'connexion.php';
	$sql="update customer_bikes set STATUS = '$status' WHERE FRAME_NUMBER = '$frameNumber'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
	$conn->close();

    successMessage("SM0003");


}
else
{
	errorMessage("ES0012");
}

?>