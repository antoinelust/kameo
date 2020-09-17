<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$user = $_POST["user"];
$brand = $_POST["brand"];
$model = $_POST["model"];
$frameType = $_POST["frame"];
$utilisation = $_POST["utilisation"];
$electric = $_POST["electric"];
$buyingPrice = $_POST["buyPrice"];
$price = $_POST["price"];
$stock = $_POST["stock"];
$link = $_POST["link"];
$display=isset($_POST['display']) ? "Y" : "N";


if(isset($_FILES['file']) && isset($_FILES['fileMini'])){

    $extensions = array('.jpg');
    $extension = strrchr($_FILES['file']['name'], '.');
    if(!in_array($extension, $extensions))
    {
          errorMessage("ES0041");
    }


    $taille_maxi = 6291456;
    $taille = filesize($_FILES['file']['tmp_name']);
    if($taille>$taille_maxi)
    {
          errorMessage("ES0023");
    }

    //upload of Bike picture

    $dossier =  $_SERVER['DOCUMENT_ROOT'].'/images_bikes/';

    $fichier = strtolower(str_replace(" ", "-", $brand))."_".strtolower(str_replace(" ", "-", $model))."_".strtolower($frameType).$extension;

     if(move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
        $upload=true;
        $path= $dossier . $fichier;
     }
     else
     {
          errorMessage("ES0024");
     }
    $extension = strrchr($_FILES['fileMini']['name'], '.');
    if(!in_array($extension, $extensions))
    {
          errorMessage("ES0041");
    }


    $taille_maxi = 6291456;
    $taille = filesize($_FILES['fileMini']['tmp_name']);
    if($taille>$taille_maxi)
    {
          errorMessage("ES0023");
    }

    //upload of Bike picture

    $dossier =  $_SERVER['DOCUMENT_ROOT'].'/images_bikes/';

    $fichier = strtolower(str_replace(" ", "-", $brand))."_".strtolower(str_replace(" ", "-", $model))."_".strtolower($frameType)."_mini".$extension;

     if(move_uploaded_file($_FILES['fileMini']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
        $upload=true;
        $path= $dossier . $fichier;
     }
     else
     {
          errorMessage("ES0024");
     }

}else{
	errorMessage("ES0025");
}


if( $_SERVER['REQUEST_METHOD'] == 'POST') {

 if($brand != '' && $model != '' && $frameType != '' && $utilisation != '' && $electric != '' && $price != '' && $stock != '') {

    include 'connexion.php';

	$sql = "INSERT INTO bike_catalog (USR_MAJ, BRAND, MODEL, FRAME_TYPE, UTILISATION,  ELECTRIC, BUYING_PRICE, PRICE_HTVA, STOCK, DISPLAY, LINK, STAANN) VALUES ('$user', '$brand', '$model', '$frameType', '$utilisation', '$electric', '$buyingPrice', '$price', '$stock', '$display', '$link', '')";

	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$conn->close();

	 successMessage("SM0015");

    } else {
        $response = array ('response'=>'error');
        echo json_encode($response);
        die;
    }

}
else
{
	errorMessage("ES0012");
}
?>
