<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
require_once __DIR__ .'/../authentication.php';
require_once __DIR__ .'/../globalfunctions.php';
$token = getBearerToken();


$ID = isset($_POST["ID"]) ? addslashes($_POST["ID"]) : NULL;
$action = isset($_POST["action"]) ? addslashes($_POST["action"]) : NULL;
$brand = isset($_POST["brand"]) ? addslashes($_POST["brand"]) : NULL;
$model = isset($_POST["model"]) ? addslashes($_POST["model"]) : NULL;
$description = isset($_POST["description"]) ? addslashes(nl2br($_POST["description"])) : NULL;
$category = isset($_POST["category"]) ? addslashes($_POST["category"]) : NULL;
$buyingPrice = isset($_POST["buyingPrice"]) ? addslashes($_POST["buyingPrice"]) : NULL;
$sellingPrice = isset($_POST["sellingPrice"]) ? addslashes($_POST["sellingPrice"]) : NULL;
$stock = isset($_POST["stock"]) ? addslashes($_POST["stock"]) : NULL;
$display=isset($_POST['display']) ? "Y" : "N";
$provider = isset($_POST["provider"]) ? addslashes($_POST["provider"]) : NULL;
$articleNbr = isset($_POST["articleNbr"]) ? addslashes($_POST["articleNbr"]) : NULL;
$minimalStockAccessory = isset($_POST["minimalStockAccessory"]) ? addslashes($_POST["minimalStockAccessory"]) : NULL;
$optimumStockAccessory = isset($_POST["optimumStockAccessory"]) ? addslashes($_POST["optimumStockAccessory"]) : NULL;



if($brand != '' && $model != '' && $description != '' && $category != '' && $buyingPrice != '' && $sellingPrice != '' && $stock != '' && $display != '' && $provider != '' && $articleNbr != '') {

    include '../connexion.php';

    if($action=="add"){

        $stmt = $conn->prepare("INSERT INTO accessories_catalog (USR_MAJ, BRAND, MODEL, DESCRIPTION, ACCESSORIES_CATEGORIES, BUYING_PRICE,  PRICE_HTVA, STOCK, DISPLAY, PROVIDER, REFERENCE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
        if ($stmt)
        {
            $stmt->bind_param("ssssiddisss", $token, $brand, $model, $description, $category, $buyingPrice, $sellingPrice, $stock, $display, $provider, $articleNbr);
            $stmt->execute();
            $ID = $conn->insert_id;

        }else
            error_message('500', 'Unable to add an accessory');
    }else if($action=="update"){

        $stmt = $conn->prepare("UPDATE accessories_catalog set USR_MAJ=?, BRAND=?, MODEL=?, DESCRIPTION=?, ACCESSORIES_CATEGORIES=?, BUYING_PRICE=?,  PRICE_HTVA=?, STOCK=?, DISPLAY=?, PROVIDER=?,REFERENCE=?, MINIMAL_STOCK=?, STOCK_OPTIMUM=? WHERE ID=? ");
        if ($stmt)
        {
            $stmt->bind_param("ssssiddisssiii", $token, $brand, $model, $description, $category, $buyingPrice, $sellingPrice, $stock, $display, $provider, $articleNbr, $minimalStockAccessory, $optimumStockAccessory, $ID);
            $stmt->execute();
        }else
            error_message('500', 'Unable to add an accessory');
    }
} else {
    $response = array ('response'=>'error');
    echo json_encode($response);
    die;
}


if(isset($_FILES['file'])){
  $extensions = array('.jpg', '.JPG');
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

  if($action=="add"){

    //upload of Bike picture

    $dossier = $_SERVER['DOCUMENT_ROOT'].'/images_accessories/';

    $fichier = $ID.'.jpg';

    if(!move_uploaded_file($_FILES['file']['tmp_name'], $dossier .$fichier))
    {
      errorMessage("ES0024");
    }
    successMessage("SM0028");
  }
  if($action=="update"){
    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/images_accessories/'.$ID.'.jpg')) {
        unlink($_SERVER['DOCUMENT_ROOT'].'/images_accessories/'.$ID.'.jpg') or die("Couldn't delete file");
    }

    //upload of Bike picture

    $dossier = $_SERVER['DOCUMENT_ROOT'].'/images_accessories/';
    $fichier = $ID.'.jpg';
    if(!move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichier))
    {
      errorMessage("ES0024");
    }
  }
}
successMessage("SM0003");

?>
