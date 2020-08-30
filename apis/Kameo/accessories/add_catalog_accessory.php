<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
require_once __DIR__ .'/../authentication.php';
require_once __DIR__ .'/../globalfunctions.php';
$token = getBearerToken();


$ID = isset($_POST["ID"]) ? htmlspecialchars($_POST["ID"]) : NULL;
$action = isset($_POST["name"]) ? htmlspecialchars($_POST["action"]) : NULL;
$name = isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : NULL;
$description = isset($_POST["description"]) ? htmlspecialchars($_POST["description"]) : NULL;
$category = isset($_POST["category"]) ? htmlspecialchars($_POST["category"]) : NULL;
$buyingPrice = isset($_POST["buyingPrice"]) ? htmlspecialchars($_POST["buyingPrice"]) : NULL;
$sellingPrice = isset($_POST["sellingPrice"]) ? htmlspecialchars($_POST["sellingPrice"]) : NULL;
$stock = isset($_POST["stock"]) ? htmlspecialchars($_POST["stock"]) : NULL;
$display=isset($_POST['display']) ? "Y" : "N";


if($name != '' && $description != '' && $category != '' && $buyingPrice != '' && $sellingPrice != '' && $stock != '' && $display != '') {

    include '../connexion.php';
    
    if($action=="add"){
        $stmt = $conn->prepare("INSERT INTO accessories_catalog (USR_MAJ, NAME, DESCRIPTION, ACCESSORIES_CATEGORIES, BUYING_PRICE,  PRICE_HTVA, STOCK, SHOW_ACCESSORIES) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ");
        if ($stmt)
        {
            $stmt->bind_param("sssiiiis", $token, $name, $description, $category, $buyingPrice, $sellingPrice, $stock, $display);
            $stmt->execute();
            $ID = $conn->insert_id;
            
            $stmt->close();
        }else
            error_message('500', 'Unable to add an accessory');
    }else if($action=="update"){
        
        
        
        $stmt = $conn->prepare("UPDATE accessories_catalog set USR_MAJ=?, NAME=?, DESCRIPTION=?, ACCESSORIES_CATEGORIES=?, BUYING_PRICE=?,  PRICE_HTVA=?, STOCK=?, SHOW_ACCESSORIES=? WHERE ID=? ");
        if ($stmt)
        {
            $stmt->bind_param("sssiiiisi", $token, $name, $description, $category, $buyingPrice, $sellingPrice, $stock, $display,$ID);
            $stmt->execute();
            $stmt->close();
        }else
            error_message('500', 'Unable to add an accessory');
    }
        
    $conn->close();

} else {
    $response = array ('response'=>'error');     
    echo json_encode($response);
    die;
}


if(isset($_FILES['file'])){
    
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
    
    
    
    if($action=="add"){
        
        //upload of Bike picture

        $dossier = $_SERVER['DOCUMENT_ROOT'].'/images_accessories/';

        $fichier = $ID.$extension;

         if(!move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichier))
         {
              errorMessage("ES0024");
         }    


    }else if($action=="update"){
        
        
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/images_accessories/'.$ID.$extension)) {   
            unlink($_SERVER['DOCUMENT_ROOT'].'/images_accessories/'.$ID.$extension) or die("Couldn't delete file");
        }
        
        //upload of Bike picture

        $dossier = $_SERVER['DOCUMENT_ROOT'].'/images_accessories/';

        $fichier = $ID.$extension;

         if(!move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichier))
         {
              errorMessage("ES0024");
         }    

        
    }
    

    
}

successMessage("SM0028");



?>
