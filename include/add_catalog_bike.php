<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$user = $_POST["widget-addCatalog-form-user"];
$brand = $_POST["widget-addCatalog-form-brand"];
$model = $_POST["widget-addCatalog-form-model"];
$frameType = $_POST["widget-addCatalog-form-frame"];
$utilisation = $_POST["widget-addCatalog-form-utilisation"];
$electric = $_POST["widget-addCatalog-form-electric"];
$buyingPrice = $_POST["buyPrice"];
$price = $_POST["widget-addCatalog-form-price"];
$stock = $_POST["widget-addCatalog-form-stock"];
$link = $_POST["widget-addCatalog-form-link"];

if(isset($_FILES['widget-addCatalog-form-file'])){
    
    $extensions = array('.jpg');
    $extension = strrchr($_FILES['widget-addCatalog-form-file']['name'], '.');
    if(!in_array($extension, $extensions))
    {
          errorMessage("ES0041");
    }


    $taille_maxi = 6291456;
    $taille = filesize($_FILES['widget-addCatalog-form-file']['tmp_name']);
    if($taille>$taille_maxi)
    {
          errorMessage("ES0023");
    }

    //upload of Bike picture

    $dossier = '../images_bikes/';

    $fichier = strtolower(str_replace(" ", "-", $brand))."_".strtolower(str_replace(" ", "-", $model))."_".strtolower($frameType).$extension;

     if(move_uploaded_file($_FILES['widget-addCatalog-form-file']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
        $upload=true;
        $path= $dossier . $fichier;
     }
     else
     {
          errorMessage("ES0024");
     }

    $img = resize_image($dossier . $fichier, 200, 200);
    $fichierMini = strtolower(str_replace(" ", "-", $brand))."_".strtolower(str_replace(" ", "-", $model))."_".strtolower($frameType)."_mini".$extension;
    imagejpeg($img, $dossier . $fichierMini);
}else{
	errorMessage("ES0025");
}


if( $_SERVER['REQUEST_METHOD'] == 'POST') {

 if($brand != '' && $model != '' && $frameType != '' && $utilisation != '' && $electric != '' && $price != '' && $stock != '') {
 
    include 'connexion.php';
     
	$sql = "INSERT INTO bike_catalog (USR_MAJ, BRAND, MODEL, FRAME_TYPE, UTILISATION,  ELECTRIC, BUYING_PRICE, PRICE_HTVA, STOCK, LINK) VALUES ('$user', '$brand', '$model', '$frameType', '$utilisation', '$electric', '$buyingPrice', '$price', '$stock', '$link')";
     
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
