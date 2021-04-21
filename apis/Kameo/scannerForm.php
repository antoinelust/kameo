<?php
session_cache_limiter('nocache');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: application/json');
header('Content-type: application/json');

session_start();
require_once 'authentication.php';


$token = getBearerToken();


$response=array();
include 'connexion.php';

$action=isset($_GET['action']) ? addslashes($_GET['action']) : NULL;
$barcode=isset($_GET['barcode']) ? addslashes($_GET['barcode']) : NULL;
$idCategory= isset($_GET['idCategory']) ? addslashes($_GET['idCategory']) : NULL;
$type =  isset($_GET['typeArticle']) ? addslashes($_GET['typeArticle']) : NULL;
$id = isset($_GET['id']) ? addslashes($_GET['id']) : NULL;
$typeContract = isset($_GET['typeContract']) ? addslashes($_GET['typeContract']) : NULL;
$typeArticle = isset($_GET['typeArticle']) ? addslashes($_GET['typeArticle']) : NULL;

$idAccessory=isset($_GET['accessory']) ? addslashes($_GET['accessory']) : NULL;
$result = isset($_GET['result']) ? addslashes($_GET['result']) : NULL;
$brand=isset($_GET['brand']) ? addslashes($_GET['brand']) : NULL;
$idModel=isset($_GET['model']) ? addslashes($_GET['model']) : NULL;
$size=isset($_GET['size']) ? addslashes($_GET['size']) : NULL;
$color=isset($_GET['color']) ? addslashes($_GET['color']) : NULL;

$bikeArrayId = isset($_GET['bikeArrayId']) ?$_GET['bikeArrayId'] :  NULL;
$lengthArray= isset($_GET['articleNumbers']) ? addslashes($_GET['articleNumbers']) : NULL;
$idBikes = isset($_GET['listOrderType']) ? addslashes($_GET['listOrderType']) : NULL;
$dateNow = isset($_GET['testDateToday']) ? addslashes($_GET['testDateToday']) : NULL;
$dateInThreeYears = isset($_GET['testDateIn3years']) ? addslashes($_GET['testDateIn3years']) : NULL;
$companyId = isset($_GET['companyPending']) ? addslashes($_GET['companyPending']) : NULL;
$companyIdSelling = isset($_GET['orderCompany']) ? addslashes($_GET['orderCompany']) : NULL;
$clientEmail = isset($_GET['orderClient']) ? addslashes($_GET['orderClient']) : NULL;
$companyInternalReference = isset($_GET['companyInternalReference']) ? addslashes($_GET['companyInternalReference']) : NULL;

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
	$response['type'] = $tempId;
	$response['frame_type']=$rowDetails['FRAME_TYPE'];

	if($typeContract=='stock'){
		$sqlBike="SELECT * FROM customer_bikes WHERE TYPE='$tempId' AND (CONTRACT_TYPE='$typeContract' OR CONTRACT_TYPE='pending_delivery') AND COLOR='$color' AND SIZE='$size' ORDER BY ESTIMATED_DELIVERY_DATE ASC";
	}
	else {
		$sqlBike="SELECT * FROM customer_bikes WHERE TYPE='$tempId' AND CONTRACT_TYPE='$typeContract' AND COLOR='$color' AND SIZE='$size' ORDER BY ESTIMATED_DELIVERY_DATE ASC";
	}

	
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
		$response['bike'][$i]['contract']= $rowBike['CONTRACT_TYPE'];
		$response['bike'][$i]['bikePrice']= $rowBike['BIKE_PRICE'];
		
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
	$row = mysqli_fetch_assoc($result);
	$tempId = $row['ID_CATALOGUE'];


	if($typeContract=='stock'){

		$sqlDetails="SELECT * FROM accessories_catalog WHERE ID='$tempId'";
		$resultDetails = mysqli_query($conn, $sqlDetails);
		$rowDetails= mysqli_fetch_assoc($resultDetails);

		$response['model']=$rowDetails['MODEL'];
		$response['brand']=$rowDetails['BRAND'];
		$response['idCategory']=$rowDetails['ACCESSORIES_CATEGORIES'];
		$tempIdCategory = $rowDetails['ACCESSORIES_CATEGORIES'];

		$sqlAccessory="SELECT * FROM accessories_stock WHERE CATALOG_ID='$tempId' AND (CONTRACT_TYPE='$typeContract' OR CONTRACT_TYPE='pending_delivery')";
		$response['response']='success';
		$resultAccessory = mysqli_query($conn, $sqlAccessory);

		if ($conn->query($sqlAccessory) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}

		$i=0;
		while($rowAccessory = mysqli_fetch_array($resultAccessory)){
			$response['bike'][$i]['accessory']= $rowAccessory['ID'];
			$response['bike'][$i]['contract']= $rowAccessory['CONTRACT_TYPE'];
			$i++;
		}
		$response['numberAccessoryOrder'] = $i;

		$sqlCategory="SELECT * FROM accessories_categories WHERE ID='$tempIdCategory'";
		$resultCategory= mysqli_query($conn, $sqlCategory);
		$rowCategory = mysqli_fetch_assoc($resultCategory);
		$response['category'] = $rowCategory['CATEGORY'];

	}
	else {
		$sqlAccessory="SELECT * FROM accessories_stock WHERE CATALOG_ID='$tempId' AND CONTRACT_TYPE='$typeContract'";
		$resultAccessory = mysqli_query($conn, $sqlAccessory);
		
		if ($conn->query($sqlAccessory) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}

		$i=0;
		while($rowAccessory = mysqli_fetch_array($resultAccessory)){
			
			$response[$i]['accessory']= $rowAccessory['ID'];
			$response[$i]['contract']= $rowAccessory['CONTRACT_TYPE'];

			$sqlDetails="SELECT * FROM accessories_catalog WHERE ID='$tempId'";
			$resultDetails = mysqli_query($conn, $sqlDetails);
			$rowDetails= mysqli_fetch_assoc($resultDetails);

			$response[$i]['model']=$rowDetails['MODEL'];
			$response[$i]['brand']=$rowDetails['BRAND'];
			$response[$i]['idCategory']=$rowDetails['ACCESSORIES_CATEGORIES'];
			$tempIdCategory = $rowDetails['ACCESSORIES_CATEGORIES'];

			$sqlCategory="SELECT * FROM accessories_categories WHERE ID='$tempIdCategory'";
			$resultCategory= mysqli_query($conn, $sqlCategory);
			$rowCategory = mysqli_fetch_assoc($resultCategory);
			$response[$i]['category'] = $rowCategory['CATEGORY'];
			$i++;
		}
	}
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
		
	}
	if($type='ACCESSORY'){
		include 'connexion.php';
		$status='';
		
		$sqlCheck="SELECT * FROM accessories_stock WHERE ID='$id' ";
		$resultCheck= mysqli_query($conn, $sqlCheck);
		$rowCheck= mysqli_fetch_assoc($resultCheck);
		$company = $rowCheck['COMPANY_ID'];
		$order = $rowCheck['ORDER_ID'];

		if(($company!=NULL || $company!=12)|| $order != null){
			$status='pending_delivery';
		}

		if(($company==NULL || $company==12)|| $order == null){
			$status = 'stock';
		}
		
		$sql = $conn->prepare("UPDATE accessories_stock SET USR_MAJ='$token',HEU_MAJ=CURRENT_TIMESTAMP,CONTRACT_TYPE='$status' WHERE ID='$id'");
		$sql->execute();
		$response['response']='success';
	}
	echo json_encode($response);
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


/////Remplissage donné pour un vélo pending_delivery déja lié à un client

else if ($action == 'getDataFromOrderPendingDelivery'){
	include 'connexion.php';
	
	if($typeArticle=='BIKE'){
		
		$sql="SELECT * FROM customer_bike_access WHERE BIKE_ID='$id'";
		$result= mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		$tempEmail=$row['EMAIL'];


		$sqlData="SELECT * FROM customer_referential WHERE EMAIL='$tempEmail'";
		$resultData= mysqli_query($conn, $sqlData);
		$rowData = mysqli_fetch_assoc($resultData);

		$response['response']='success';
		$response['name']=$rowData['NOM'];
		$response['firstname']=$rowData['PRENOM'];
		$tempcomp = $rowData['COMPANY'];

		$sqlComp="SELECT * FROM companies WHERE INTERNAL_REFERENCE='$tempcomp'";
		$resultComp= mysqli_query($conn, $sqlComp);
		$rowComp = mysqli_fetch_assoc($resultComp);
		$response['company']=$rowComp['COMPANY_NAME'];
		$response['response']='success';

	}
	if($typeArticle=='ACCESSORY'){
		
		$sql="SELECT * FROM accessories_stock WHERE ID='$id'";
		$result= mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		$tempcomp=$row['COMPANY_ID'];


		$sqlData="SELECT * FROM companies WHERE ID='$tempcomp'";
		$resultData= mysqli_query($conn, $sqlData);
		$rowData = mysqli_fetch_assoc($resultData);
		$response['response']='success';
		$response['company'] = $rowData['COMPANY_NAME'];
	}

	echo json_encode($response);
	die;


}
else if ($action == 'listCompanies'){

	$sql="SELECT * FROM companies";
	$result= mysqli_query($conn, $sql);
	$i=0;
	$response['response']='success';
	while($row = mysqli_fetch_array($result)){
		$response['companies'][$i]['name']= $row['COMPANY_NAME'];
		$response['companies'][$i]['id']= $row['ID'];
		$response['companies'][$i]['internalRef']= $row['INTERNAL_REFERENCE'];

		$i++;
	}
	$response['numberCompanies'] = $i;
	echo json_encode($response);
	die;
}
// article en vente
else if($action=='selling'){

	if($typeArticle=='BIKE'){
		$sql = $conn->prepare("UPDATE customer_bikes SET USR_MAJ='$token', HEU_MAJ=CURRENT_TIMESTAMP,CONTRACT_TYPE='selling',SELLING_DATE='$dateNow', COMPANY = '$companyInternalReference', CONTRACT_START ='$dateNow' WHERE ID='$idBikes'");
		$sql->execute();
		
		if($clientEmail!=NULL){
			$sqlTest = "INSERT INTO customer_bike_access (TIMESTAMP, USR_MAJ, EMAIL , BIKE_ID,TYPE)
			VALUES (CURRENT_TIMESTAMP, '$email', '$clientEmail', '$idBikes' ,'personnel')";
			mysqli_query($conn, $sqlTest);
		}
	}
	else if($typeArticle=='ACCESSORY'){
		$sql = $conn->prepare("UPDATE accessories_stock SET USR_MAJ='$token', HEU_MAJ=CURRENT_TIMESTAMP,CONTRACT_TYPE='selling',COMPANY_ID = '$companyIdSelling', SELLING_DATE='$dateNow',CONTRACT_START ='$dateNow', CONTRACT_END='$dateInThreeYears' WHERE ID='$idBikes'");
		$sql->execute();		
	}
	$response['response']='success';
	$response['message']='article modifié (vente) dans le BDD';
	$response['type']='selling';
	echo json_encode($response);
	die;
}
// livraison d'un vélo en attente de livraison
else if($action=='leasingStockPending'){

	if($companyId!=''){
		
		$sqlData="SELECT * FROM companies WHERE COMPANY_NAME='$companyId'";
		$resultData= mysqli_query($conn, $sqlData);
		$rowData = mysqli_fetch_assoc($resultData);
		$companyInternalReference = $rowData['INTERNAL_REFERENCE'];
		$companyId = $rowData['ID'];
	}
	else{
		$companyId = $companyIdSelling;
	}
	
	if($typeArticle=='BIKE'){
		$sql = $conn->prepare("UPDATE customer_bikes SET USR_MAJ='$token', HEU_MAJ=CURRENT_TIMESTAMP,CONTRACT_TYPE='leasing', COMPANY = '$companyInternalReference', CONTRACT_START ='$dateNow', CONTRACT_END='$dateInThreeYears' WHERE ID='$idBikes'");
		$sql->execute();
		$response['response']='success';

		if($clientEmail!=NULL){
			$sqlTest = "INSERT INTO customer_bike_access (TIMESTAMP, USR_MAJ, EMAIL , BIKE_ID,TYPE)
			VALUES (CURRENT_TIMESTAMP, '$email', '$clientEmail', '$idBikes' ,'personnel')";
			mysqli_query($conn, $sqlTest);
		}
	}
	if($typeArticle=='ACCESSORY'){
		$sql = $conn->prepare("UPDATE accessories_stock SET USR_MAJ='$token', HEU_MAJ=CURRENT_TIMESTAMP,CONTRACT_TYPE='leasing', COMPANY_ID = '$companyId', CONTRACT_START ='$dateNow', CONTRACT_END='$dateInThreeYears' WHERE ID='$idBikes'");
		$sql->execute();
		$response['response']='success';
	}

	$response['message']='article modifié (leasing) dans le BDD';
	$response['type']='leasing';
	echo json_encode($response);
	die;

}
else if($action=='getPrice'){
	include 'connexion.php';

	if($typeArticle=='BIKE'){

		$sql="SELECT * FROM customer_bikes WHERE ID='$id'";
		$result= mysqli_query($conn, $sql);
		$row= mysqli_fetch_assoc($result);
		$tempId = $row['TYPE'];
		

		$sqlBike="SELECT * FROM bike_catalog WHERE ID='$tempId'";
		$resultBike= mysqli_query($conn, $sqlBike);
		$rowBike= mysqli_fetch_assoc($resultBike);
		$response['price']=$rowBike['PRICE_HTVA'];
		$response['response']='success';
		

	}
	else if($typeArticle=='ACCESSORY'){
		
		$sql="SELECT * FROM accessories_stock WHERE ID='$id'";
		$result= mysqli_query($conn, $sql);
		$row= mysqli_fetch_assoc($result);
		$tempId = $row['CATALOG_ID'];
		
		$sqlAccessory="SELECT * FROM accessories_catalog WHERE ID='$tempId'";
		$resultAccessory= mysqli_query($conn, $sqlAccessory);
		$rowAccessory = mysqli_fetch_assoc($resultAccessory);
		$response['price']=$rowAccessory['PRICE_HTVA'];
		$response['response']='success';
		

	}
	echo json_encode($response);
	die;
}
else if($action=='changeMultipleArticles'){
	// faire une boucle qui tourne jusque le tab length
	// verifier le type et changer le stock date et contrat
	// puis appeler add-bill et creer un nouveau if pour ajouter le tableau dans $data
	$i=0;
	while ($i<$lengthArray) {
		$idArticle = $bikeArrayId[$i][0];
		if($bikeArrayId[$i][3]=='BIKE'){
			$sql = $conn->prepare("UPDATE customer_bikes SET USR_MAJ='$token', HEU_MAJ=CURRENT_TIMESTAMP,CONTRACT_TYPE='selling',SELLING_DATE='$dateNow', COMPANY = '$companyInternalReference', CONTRACT_START ='$dateNow' WHERE ID='$idArticle'");
			$sql->execute();
		}
		else{
			$sql = $conn->prepare("UPDATE accessories_stock SET USR_MAJ='$token', HEU_MAJ=CURRENT_TIMESTAMP,CONTRACT_TYPE='selling',COMPANY_ID = '$companyId', CONTRACT_START ='$dateNow', CONTRACT_END='$dateInThreeYears' WHERE ID='$idArticle'");
			$sql->execute();		
		}
		$i++;
	}
	$response['response']='success';
	echo json_encode($response);
	die;
}
else if($action=='bindAccessoriesMultiple'){
	
	foreach ($_GET['accessoryId'] as $row) {
		$status='';
		$id=$row;
		
		$sqlCheck="SELECT * FROM accessories_stock WHERE ID='$id' ";
		$resultCheck= mysqli_query($conn, $sqlCheck);
		$rowCheck= mysqli_fetch_assoc($resultCheck);
		$company = $rowCheck['COMPANY_ID'];
		$order = $rowCheck['ORDER_ID'];

		if(($company!=NULL || $company!=12)|| $order != null){
			$status='pending_delivery';
		}

		if(($company==NULL || $company==12)|| $order == null){
			$status = 'stock';
		}
		
		$sql = $conn->prepare("UPDATE accessories_stock SET USR_MAJ='$token',HEU_MAJ=CURRENT_TIMESTAMP,CONTRACT_TYPE='$status' WHERE ID='$id'");
		$sql->execute();
		
	}	
	$response['response']='success';
	echo json_encode($response);
	die;
}

