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
$response=array();
include 'connexion.php';

$action=isset($_GET['action']) ? addslashes($_GET['action']) : NULL;
$barcode=isset($_GET['barcode']) ? addslashes($_GET['barcode']) : NULL;
$idCategory= isset($_GET['idCategory']) ? addslashes($_GET['idCategory']) : NULL;


$idAccessory=isset($_GET['accessory']) ? addslashes($_GET['accessory']) : NULL;
$result = isset($_GET['result']) ? addslashes($_GET['result']) : NULL;
$brand=isset($_GET['brand']) ? addslashes($_GET['brand']) : NULL;
$id=isset($_GET['model']) ? addslashes($_GET['model']) : NULL;
$size=isset($_GET['size']) ? addslashes($_GET['size']) : NULL;
$color=isset($_GET['color']) ? addslashes($_GET['color']) : NULL;


	// Verifie si le code barre est présent dans la table

if($action == 'check'){
	$sql="SELECT ARTICLE_ID FROM article_referential WHERE BARCODE='$barcode'";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$length = $result->num_rows;
	$row = mysqli_fetch_assoc($result);

	if($length==0){
		$response['response']='success';
	}
	else{
		$response['response']='present';
	}
	echo json_encode($response);
}
// Recupere toutes les marques de vélo

else if($action == 'loadDataBike'){
	include 'connexion.php';
	$sql="SELECT DISTINCT BRAND FROM bike_catalog";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$i=0;
	$response['response']='success';
	while($row = mysqli_fetch_array($result)){
		$response['bike'][$i]['brand']= $row['BRAND'];
		$i++;
	}
	$response['numberBrand'] = $i;
	echo json_encode($response);
}

//Recupere les model de vélo en fonction de la marque de vélo

else if($action == 'loadModelBrand'){
	include 'connexion.php';
	$sqlModel="SELECT ID,MODEL, FRAME_TYPE FROM bike_catalog WHERE BRAND='$brand'";
	$resultModel = mysqli_query($conn, $sqlModel);
	$iModel = 0;
	while($rowModel = mysqli_fetch_array($resultModel)){
		$response['bike'][$iModel]['model']= $rowModel['MODEL'];
		$response['bike'][$iModel]['frame_type']= $rowModel['FRAME_TYPE'];
		$response['bike'][$iModel]['id']= $rowModel['ID'];
		$iModel++;
	}
	$response['response']='success';
	$response['numberModel'] = $iModel;
	echo json_encode($response);
}

//Recupere les categories d'accessoire

else if($action == 'loadCategory'){
	include 'connexion.php';
	$sql="SELECT ID,CATEGORY FROM accessories_categories";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$i=0;
	$response['response']='success';
	while($row = mysqli_fetch_array($result)){
		$response['bike'][$i]['category']= $row['CATEGORY'];
		$response['bike'][$i]['id']= $row['ID'];
		$i++;
	}
	$response['numberCategory'] = $i;
	echo json_encode($response);
}

	//Recupere les marque d'accessoire 
else if($action == 'loadModelBrandCategory'){
	include 'connexion.php';
	$sql="SELECT ID,BRAND,MODEL FROM accessories_catalog WHERE ACCESSORIES_CATEGORIES = '$idCategory'";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$i=0;
	$response['response']='success';
	while($row = mysqli_fetch_array($result)){
		$response['bike'][$i]['brand']= $row['BRAND'];
		$response['bike'][$i]['model']= $row['MODEL'];
		$response['bike'][$i]['id']= $row['ID'];
		$i++;
	}
	$response['numberModelBrand'] = $i;
	echo json_encode($response);
}

////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////// Action correspondant à l'envoie du formulaire 

	// ajout du code barre du velo 
else if($action == 'addBike'){
	include 'connexion.php';
	$sqlTest = "INSERT INTO article_referential(TYPE, BARCODE, SIZE, COLOR, ID_CATALOGUE)
	VALUES ('bike', '$result', '$size', '$color', '$id')";
	//mysqli_query($conn, $sqlTest);

	if ($conn->query($sqlTest) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);

		die;
	}
	$response = array ('response'=>'success', 'message'=> 'Code barre repertorié');
	echo json_encode($response);
}

	// ajout du code barre de l'accessoire 

else if($action == 'addAccessory'){
	include 'connexion.php';
	$sqlTest = "INSERT INTO article_referential(TYPE, BARCODE, SIZE, COLOR, ID_CATALOGUE)
	VALUES ('accessory', '$result', NULL , NULL, '$idAccessory')";

	if ($conn->query($sqlTest) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$response = array ('response'=>'success', 'message'=> 'Code barre repertorié');
	echo json_encode($response);
}



