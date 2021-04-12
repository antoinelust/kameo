<?php

header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");



require_once 'globalfunctions.php';
require_once 'authentication.php';


$token = getBearerToken();
log_inputs($token);
$response=array();

include 'connexion.php';

$action=isset($_GET['action']) ? addslashes($_GET['action']) : NULL;
$csvName=isset($_GET['csvName']) ? addslashes($_GET['csvName']) : NULL;
$testAction=isset($_POST['testAction']) ? addslashes($_POST['testAction']) : NULL;

///////////////////////////////////////////////////////////////////////////////////
///////////////Ne fonctionne que pour les accessoire, si une adapatation est voulue.Modifier le code=. 


if($testAction=='addFileCSVToLoad'){
	$csvName =isset($_POST['fileCSV']) ? addslashes($_POST['fileCSV']) : NULL;
	$sql = $conn->prepare("UPDATE order_file_csv SET USR_MAJ='$token', HEU_MAJ=CURRENT_TIMESTAMP, LOAD_STATUS='LOADED' WHERE CSV_NAME='$csvName'");
	$sql->execute();
	$response['response']='success';
	$response['message']='Le fichier a bien été chargé, consultez le ci-dessous';

	echo json_encode($response);
}

else if ($action=='listOrderCSVFile'){

	$response=execSQL("SELECT * FROM order_file_csv WHERE LOAD_STATUS='LOADED'",array(),false);
	if($response==null){
		$response=array();
	}
	echo json_encode($response);
}
else if ($action=='listOrderCSVFileClosed'){

	$response=execSQL("SELECT * FROM order_file_csv WHERE LOAD_STATUS='ACHIEVED'",array(),false);
	if($response==null){
		$response=array();
	}
	echo json_encode($response);
}

else if ($action=='retrieve'){
	$dossier = $_SERVER['DOCUMENT_ROOT'].'/orderCSV/';
	$monfichier = fopen($dossier.''.$csvName, 'r');

	$row=0;
	while (($data = fgetcsv($monfichier, 1000, ";")) !== FALSE) {
		$num = count($data);
		if($row>0){
			for ($c=0; $c < $num; $c++) {
				if($c==1){
					$response[$row-1]['reference'] = $data[$c];
				}
				if($c==2){
					$response[$row-1]['numberToOrder'] = $data[$c];
				}
			}
		}
		$row++;
	}
	$i=0;
	$row--;
	while($i<$row){
		$tempRef = $response[$i]['reference'];
		$sql="SELECT * FROM accessories_catalog WHERE REFERENCE='$tempRef'";
		$result = mysqli_query($conn, $sql);
		$rowSQL = mysqli_fetch_assoc($result);
		$tempid =  $rowSQL['ID'];

		$sqlStock="SELECT * FROM accessories_stock WHERE CATALOG_ID='$tempid'";
		$resultStock = mysqli_query($conn, $sqlStock);
		$length = $resultStock->num_rows;

		$response[$i]['id'] = $rowSQL['ID'];
		$response[$i]['price'] =  $rowSQL['PRICE_HTVA'];
		$response[$i]['provider'] =  $rowSQL['PROVIDER'];
		$response[$i]['stockOpti'] =  $rowSQL['STOCK_OPTIMUM'];
		$response[$i]['brand'] =  $rowSQL['BRAND'];
		$response[$i]['model'] =  $rowSQL['MODEL'];
		$response[$i]['min'] = $rowSQL['MINIMAL_STOCK'];
		$response[$i]['stock'] = $length;
		$i++;
	}
	echo json_encode($response);
	die;
}
else if ($action=='validFormToChangeOrder'){
	//////Recupere les données nécessaire
	$csvName=isset($_GET['testCSVOrderDetail']) ? addslashes($_GET['testCSVOrderDetail']) : NULL;
	$numberOfArticle=isset($_GET['totalArticleNumber']) ? addslashes($_GET['totalArticleNumber']) : NULL;
	$data = array();
	$i=0;
	$sql="SELECT * FROM accessories_stock WHERE INTERNAL_REFERENCE='$csvName'";
	$result = mysqli_query($conn, $sql);
	include 'connexion.php';
	
	while($row = mysqli_fetch_array($result)){
		$id = $row['CATALOG_ID'];
		$numberToArticle=isset($_GET['numberArticleCSV'.$id]) ? addslashes($_GET['numberArticleCSV'.$id]) : NULL;
		$sqlRef="SELECT * FROM accessories_catalog WHERE ID = '$id'";
		$resultRef = mysqli_query($conn, $sqlRef);
		$rowRef = mysqli_fetch_assoc($resultRef);
		$data[$i]['clientNumber'] = '358783';
		$data[$i]['reference'] = $rowRef['REFERENCE'];
		$data[$i]['numberOfArticle'] =$numberToArticle;
		$i++;
	}

	print_r($data);
    //////Remodifie le fichier
	$nameFile=$csvName;
	$dossier = $_SERVER['DOCUMENT_ROOT'].'/orderCSV/';
	$monfichier = fopen($dossier.''.$nameFile, 'w+');
	$header=null;
	foreach($data as $t)
	{
		if(!$header) {
			$arrayCSVTempLine=array('Numero de Client','Numero article','Nombre article');
			fputcsv($monfichier,$arrayCSVTempLine,";");
			$header = true;
		}
		fputcsv($monfichier,$t,";");
	}
	fclose ($monfichier);


	$sql = $conn->prepare("UPDATE order_file_csv SET USR_MAJ='$token', HEU_MAJ=CURRENT_TIMESTAMP, LOAD_STATUS='ACHIEVED' WHERE CSV_NAME='$csvName'");
	$sql->execute();

	////Modifie les lignes dans la base de donnée
	foreach($data as $t)
	{
		if($t['numberOfArticle']>1){
			$ref = $t['reference'];
			$sqlRef="SELECT * FROM accessories_catalog WHERE REFERENCE = '$ref'";
			$resultRef = mysqli_query($conn, $sqlRef);
			$rowRef = mysqli_fetch_assoc($resultRef);
			$id = $rowRef['ID'];

			$sql = $conn->prepare("UPDATE accessories_stock SET CONTRACT_TYPE='order' WHERE CATALOG_ID='$id' AND INTERNAL_REFERENCE='$csvName'");
			$sql->execute();
			$i=0;
			while($i<$t['numberOfArticle']-1){
				$sqlTest = "INSERT INTO accessories_stock (HEU_MAJ, USR_MAJ, COMPANY_ID , USER_EMAIL,CATALOG_ID,CONTRACT_TYPE, CONTRACT_START, CONTRACT_END, CONTRACT_AMOUNT, SELLING_DATE, SELLING_AMOUNT,STAANN,INTERNAL_REFERENCE)
				VALUES (CURRENT_TIMESTAMP, '$token', NULL, NULL,'$id','order',NULL,NULL,NULL,NULL,NULL,'','$nameFile')";
				mysqli_query($conn, $sqlTest);

				$i++;
			}
		}
		else if ($t['numberOfArticle']=1){
			$ref = $t['reference'];
			$sqlRef="SELECT * FROM accessories_catalog WHERE REFERENCE = '$ref'";
			$resultRef = mysqli_query($conn, $sqlRef);
			$rowRef = mysqli_fetch_assoc($resultRef);
			$id = $rowRef['ID'];

			$sql = $conn->prepare("UPDATE accessories_stock SET CONTRACT_TYPE='order' WHERE CATALOG_ID='$id' AND INTERNAL_REFERENCE='$csvName'");
			$sql->execute();
		}
		else {
			$ref = $t['reference'];
			$sqlRef="SELECT * FROM accessories_catalog WHERE REFERENCE = '$ref'";
			$resultRef = mysqli_query($conn, $sqlRef);
			$rowRef = mysqli_fetch_assoc($resultRef);
			$id = $rowRef['ID'];

			$sql = $conn->prepare("DELETE FROM accessories_stock WHERE CATALOG_ID='$id' AND INTERNAL_REFERENCE='$csvName'");
			$sql->execute();
		}
	}

	$response['response']='success';
	$response['message']='La commande a bien été traité';

}
?>