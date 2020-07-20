<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=$_POST['widget-reactivateUser-form-mail'];


$response=array();

if($email != NULL)
{

    include 'connexion.php';
	$sql="update customer_referential set STAANN='', USR_MAJ='mykameo', HEU_MAJ=CURRENT_TIMESTAMP WHERE EMAIL = '$email'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
    $conn->close();  
    
    successMessage("SM0010");

}
else
{
	errorMessage("ES0012");
}

?>