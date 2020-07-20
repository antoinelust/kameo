<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$ID = $_POST["widget-updateCatalog-form-ID"];
$user = $_POST["widget-updateCatalog-form-user"];
$brand = $_POST["widget-updateCatalog-form-brand"];
$model = $_POST["widget-updateCatalog-form-model"];
$frameType = $_POST["widget-updateCatalog-form-frame"];
$utilisation = $_POST["widget-updateCatalog-form-utilisation"];
$electric = $_POST["widget-updateCatalog-form-electric"];
$price = $_POST["widget-updateCatalog-form-price"];
$stock = $_POST["widget-updateCatalog-form-stock"];
$link = $_POST["widget-updateCatalog-form-link"];

if(isset($_FILES['widget-updateCatalog-form-picture'])){
    
    $extensions = array('.jpg');
    $extension = strrchr($_FILES['widget-updateCatalog-form-picture']['name'], '.');
    if(!in_array($extension, $extensions))
    {
          errorMessage("ES0041");
    }


    $taille_maxi = 6291456;
    $taille = filesize($_FILES['widget-updateCatalog-form-picture']['tmp_name']);
    if($taille>$taille_maxi)
    {
          errorMessage("ES0023");
    }

    //upload of Bike picture

    $dossier = '../images_bikes/';

	$sql = "select * from bike_catalog where ID='$ID'";
     
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);    

    $fichier=str_replace(" ", "-", $resultat['BRAND'])."_".str_replace(" ", "-", $resultat['MODEL']).$extension;
    unlink($dossier.$fichier) or die("Couldn't delete file");
    
    $fichierMini=str_replace(" ", "-", $resultat['BRAND'])."_".str_replace(" ", "-", $resultat['MODEL'])."_mini".$extension;
    unlink($dossier.$fichier) or die("Couldn't delete file");
    

    $fichier = str_replace(" ", "-", $brand)."_".str_replace(" ", "-", $model).$extension;

     if(move_uploaded_file($_FILES['widget-updateCatalog-form-picture']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
        $upload=true;
        $path= $dossier . $fichier;
     }
     else
     {
          errorMessage("ES0024");
     }

    $img = resize_image($dossier . $fichier, 200, 200);
    $fichierMini = str_replace(" ", "-", $brand)."_".str_replace(" ", "-", $model)."_mini".$extension;
    imagejpeg($img, $dossier . $fichierMini);
}


if( $_SERVER['REQUEST_METHOD'] == 'POST') {

 if($ID != '' && $brand != '' && $model != '' && $frameType != '' && $utilisation != '' && $electric != '' && $price != '' && $stock != '') {
 
    include 'connexion.php';
     
	$sql = "update bike_catalog set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$user', BRAND='$brand', MODEL='$model', FRAME_TYPE='$frameType', UTILISATION='$utilisation',  ELECTRIC='$electric', PRICE_HTVA='$price', STOCK='$stock', LINK='$link' WHERE ID='$ID'";
     
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$conn->close();
		
	 successMessage("SM0003");

    } else {
        $response = array ('response'=>'error');     
        echo json_encode($response);
        die;
    }

}
else
{
	errorMessage(ES0012);
}
?>
