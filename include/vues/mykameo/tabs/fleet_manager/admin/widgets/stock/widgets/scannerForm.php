<?php
session_cache_limiter('nocache');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: application/json');
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
require_once 'authentication.php';

log_inputs();

$action=isset($_POST['action']) ? addslashes($_POST['action']) : NULL;
$barcode=isset($_POST['barcode']) ? addslashes($_POST['barcode']) : NULL;

if($action == 'check'){

	$sql="SELECT ARTICLE_ID FROM article_referential WHERE BARCODE='$barcode'";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	$nbr=$result->rowCount();µ
	echo 'console.log('$nbr')';
	if($nbr==0){
	$response['response']='success';
		error_message('403', 'Insufficient privilegies to consult that bike');
	}
	else {
	$response['response']='test';
	}
}



