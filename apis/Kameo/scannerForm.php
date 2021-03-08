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

$token = getBearerToken();
log_inputs($token);

$response=array();
include 'connexion.php';

$action=isset($_GET['action']) ? addslashes($_GET['action']) : NULL;
$barcode=isset($_GET['barcode']) ? addslashes($_GET['barcode']) : NULL;
$idCategory= isset($_GET['idCategory']) ? addslashes($_GET['idCategory']) : NULL;
$type =  isset($_GET['typeArticle']) ? addslashes($_GET['typeArticle']) : NULL;
$id = isset($_GET['id']) ? addslashes($_GET['id']) : NULL;

$idAccessory=isset($_GET['accessory']) ? addslashes($_GET['accessory']) : NULL;
$result = isset($_GET['result']) ? addslashes($_GET['result']) : NULL;
$brand=isset($_GET['brand']) ? addslashes($_GET['brand']) : NULL;
$idModel=isset($_GET['model']) ? addslashes($_GET['model']) : NULL;
$size=isset($_GET['size']) ? addslashes($_GET['size']) : NULL;
$color=isset($_GET['color']) ? addslashes($_GET['color']) : NULL;


////// Verifie si le code barre est présent dans la table

if($action == 'check'){
	include 'connexion.php';
	$sql="SELECT * FROM article_referential WHERE BARCODE='$barcode'";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
	$result = mysqli_query($conn, $sql);
	$length = $result->num_rows;
	$row = mysqli_fetch_assoc($result);
	$response['type'] = $row['TYPE'];
	$response['id'] = $row['ID_CATALOGUE'];


	if($length==0){
		$response['response']='success';
	}
	else{
		$response['response']='present';
	}
	echo json_encode($response);
}

///////////////Recupere la liste des vélo de type order
else if ($action == 'loadBikeOrder'){

	include 'connexion.php';

	$sql="SELECT * FROM article_referential WHERE BARCODE='$barcode'";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$length = $result->num_rows;
	$row = mysqli_fetch_assoc($result);
	$response['color']=$row['COLOR'];
	$response['size']=$row['SIZE'];
	$tempId = $row['ID_CATALOGUE'];
	$color = $row['COLOR'];
	$size = $row['SIZE'];



	$sqlDetails="SELECT * FROM bike_catalog WHERE ID='$tempId'";
	$resultDetails = mysqli_query($conn, $sqlDetails);
	$rowDetails= mysqli_fetch_assoc($resultDetails);

	$response['model']=$rowDetails['MODEL'];
	$response['brand']=$rowDetails['BRAND'];
	$response['frame_type']=$rowDetails['FRAME_TYPE'];



	$sqlBike="SELECT * FROM customer_bikes WHERE TYPE='$tempId' AND CONTRACT_TYPE='order' AND COLOR='$color' AND SIZE='$size' ORDER BY ESTIMATED_DELIVERY_DATE ASC";
	if ($conn->query($sqlBike) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$resultBike = mysqli_query($conn, $sqlBike);
	$response['response']='success';
	$i=0;
	while($rowBike = mysqli_fetch_array($resultBike)){
		$response['bike'][$i]['bike']= $rowBike['ID'];
		
		if($rowBike['ESTIMATED_DELIVERY_DATE']==null){
			$response['bike'][$i]['estimate_date']='--/--/----';
		}
		else{
			$response['bike'][$i]['estimate_date']= $rowBike['ESTIMATED_DELIVERY_DATE'];
		}
		$i++;
	}
	$response['numberBikeOrder'] = $i;
	echo json_encode($response);
}
///////////////Recupere la list des accessoires de type order
else if ($action == 'loadAccessoryOrder'){

	include 'connexion.php';

	$sql="SELECT * FROM article_referential WHERE BARCODE='$barcode'";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$length = $result->num_rows;
	$row = mysqli_fetch_assoc($result);
	$tempId = $row['ID_CATALOGUE'];



	$sqlDetails="SELECT * FROM accessories_catalog WHERE ID='$tempId'";
	$resultDetails = mysqli_query($conn, $sqlDetails);
	$rowDetails= mysqli_fetch_assoc($resultDetails);

	$response['model']=$rowDetails['MODEL'];
	$response['brand']=$rowDetails['BRAND'];
	$response['idCategory']=$rowDetails['ACCESSORIES_CATEGORIES'];
	$tempIdCategory = $rowDetails['ACCESSORIES_CATEGORIES'];


	$sqlAccessory="SELECT * FROM accessories_stock WHERE CATALOG_ID='$tempId' AND CONTRACT_TYPE='order'";
	if ($conn->query($sqlAccessory) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$resultAccessory = mysqli_query($conn, $sqlAccessory);
	$response['response']='success';
	$i=0;
	while($rowAccessory = mysqli_fetch_array($resultAccessory)){
		$response['bike'][$i]['accessory']= $rowAccessory['ID'];
		$i++;
	}
	$response['numberAccessoryOrder'] = $i;

	$sqlCategory="SELECT * FROM accessories_categories WHERE ID='$tempIdCategory'";
	$resultCategory= mysqli_query($conn, $sqlCategory);
	$rowCategory = mysqli_fetch_assoc($resultCategory);
	$response['category'] = $rowCategory['CATEGORY'];
	echo json_encode($response);
}

else if($action == 'changeContractType'){
	
	if($type='BIKE'){
		include 'connexion.php';
		$sql="SELECT * FROM customer_bike_access WHERE BIKE_ID='$id' AND TYPE='personnel'";
		if ($conn->query($sql) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
		$result= mysqli_query($conn, $sql);
		$length = $result->num_rows;
		$row= mysqli_fetch_assoc($result);

		//voir si lié ou pas pour savoir si on met stock ou pending_delivery

		if($length==0){
			$sql = $conn->prepare("UPDATE customer_bikes SET USR_MAJ='$token', HEU_MAJ=CURRENT_TIMESTAMP,CONTRACT_TYPE='stock' WHERE ID='$id'");
			$sql->execute();
			$response['response']='success';
		}
		else{
			$sql = $conn->prepare("UPDATE customer_bikes SET USR_MAJ='$token', HEU_MAJ=CURRENT_TIMESTAMP, CONTRACT_TYPE='pending_delivery' WHERE ID='$id'");
			$sql->execute();
			$response['response']='success';
		}
		echo json_encode($response);

	}
	else if($type='ACCESSORY'){
		include 'connexion.php';
		$sql = $conn->prepare("UPDATE accessories_stock SET USR_MAJ='$token',HEU_MAJ=CURRENT_TIMESTAMP,CONTRACT_TYPE='stock' WHERE ID='$id'");
		$sql->execute();
		$response['response']='success';
		echo json_encode($response);
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////:
////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////:Action lié a l'ajoute de referencement 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////

////// Recupere les données lié au code barre 
else if ($action == 'getDataFromBarcode'){
	include 'connexion.php';
	$sql="SELECT * FROM article_referential WHERE BARCODE='$barcode'";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	$response['response']='success';
	$response['type']=$row['TYPE'];
	$tempId = $row['ID_CATALOGUE'];

	if($row['TYPE']=='BIKE'){
		$response['color']=$row['COLOR'];
		$response['size']=$row['SIZE'];
		$sqlBike="SELECT * FROM bike_catalog WHERE ID='$tempId'";
		$resultBike = mysqli_query($conn, $sqlBike);
		$rowBike = mysqli_fetch_assoc($resultBike);

		$response['model']=$rowBike['MODEL'];
		$response['brand']=$rowBike['BRAND'];
		$response['frame_type']=$rowBike['FRAME_TYPE'];

	}
	else if($row['TYPE']=='ACCESSORY'){
		$sqlAccessory="SELECT * FROM accessories_catalog WHERE ID='$tempId'";
		$resultAccessory= mysqli_query($conn, $sqlAccessory);
		$rowAccessory = mysqli_fetch_assoc($resultAccessory);

		$response['model']=$rowAccessory['MODEL'];
		$response['brand']=$rowAccessory['BRAND'];
		$response['idCategory'] = $rowAccessory['ACCESSORIES_CATEGORIES'];


		$tempIdCategory= $rowAccessory['ACCESSORIES_CATEGORIES'];

		$sqlCategory="SELECT * FROM accessories_categories WHERE ID='$tempIdCategory'";
		$resultCategory= mysqli_query($conn, $sqlCategory);
		$rowCategory = mysqli_fetch_assoc($resultCategory);
		$response['category'] = $rowCategory['CATEGORY'];

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
	VALUES ('bike', '$result', '$size', '$color', '$idModel')";
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