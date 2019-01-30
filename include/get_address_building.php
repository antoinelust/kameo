<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';

$building_code=$_POST['buildingCode'];

include 'connexion.php';
$sql="SELECT * FROM building_access WHERE BUILDING_CODE='$building_code'";
if ($conn->query($sql) === FALSE) {

	$response = array ('response'=>'error', 'message'=> $conn->error);
	echo json_encode($response);
	die;

}

$result = mysqli_query($conn, $sql);        
$resultat = mysqli_fetch_assoc($result);
$length = $result->num_rows;
$address=$resultat['ADDRESS'];
$description_FR=$resultat['BUILDING_FR'];
$description_EN=$resultat['BUILDING_EN'];
$description_NL=$resultat['BUILDING_NL'];

$address=str_replace(str_split(' \,'),"+",$address);

$response['response']='success';
$response['address']=$address;
$response['building_fr']=$description_FR;
$response['building_en']=$description_EN;
$response['building_nl']=$description_NL;

echo json_encode($response);
die;

?>