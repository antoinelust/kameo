<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


$response=array();


    include 'connexion.php';
    $sql="select MAX(ID) from reservations";
   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}    
    $result = mysqli_query($conn, $sql);     
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();   
    
    $response['response']="success";
    $response['bookings']=$resultat['MAX(ID)'];
	echo json_encode($response);
    die;


?>