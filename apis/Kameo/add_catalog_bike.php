<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
include_once 'authentication.php';

$token = getBearerToken();
log_inputs($token);


$user = $_POST["user"];
$brand = $_POST["brand"];
$model = $_POST["model"];
$frameType = $_POST["frame"];
$utilisation = $_POST["utilisation"];
$electric = $_POST["electric"];
$buyingPrice = $_POST["buyPrice"];
$price = $_POST["price"];
$stock = $_POST["stock"];
$display=isset($_POST['display']) ? "Y" : "N";
$motor = $_POST["motor"];
$battery = $_POST["battery"];
$transmission = $_POST["transmission"];
//$license = $_POST["license"];
$season = $_POST["season"];
$priority = $_POST["priority"];
$sizes="";

if(isset($_FILES['file']) && isset($_FILES['fileMini'])){

    $extensions = array('.jpg');
    $extension = strrchr($_FILES['file']['name'], '.');
    if(!in_array($extension, $extensions))
    {
          errorMessage("ES0041");
    }
    $extension = strrchr($_FILES['fileMini']['name'], '.');
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


    $taille_maxi = 6291456;
    $taille = filesize($_FILES['fileMini']['tmp_name']);
    if($taille>$taille_maxi)
    {
          errorMessage("ES0023");
    }

}else{
	errorMessage("ES0025");
}


if( $_SERVER['REQUEST_METHOD'] == 'POST') {

 if($brand != '' && $model != '' && $frameType != '' && $utilisation != '' && $electric != '' && $price != '' && $stock != '') {

    include 'connexion.php';

      if($priority<0 || $priority>100)
      {
            errorMessage("ES0063");
      }


      if(isset($_POST['sizes'])){
        foreach($_POST['sizes'] as $size){
          $sizes=$sizes.$size.",";
        }
        $sizes=substr($sizes, 0, -1);
      }

      $sql = "INSERT INTO bike_catalog (USR_MAJ, BRAND, MODEL, FRAME_TYPE, UTILISATION,  ELECTRIC, BUYING_PRICE, PRICE_HTVA, STOCK, DISPLAY, STAANN, MOTOR, BATTERY, TRANSMISSION, SIZES, SEASON, PRIORITY) VALUES ('$user', '$brand', '$model', '$frameType', '$utilisation', '$electric', '$buyingPrice', '$price', '$stock', '$display', '', '$motor', '$battery', '$transmission', '$sizes', '$season', '$priority')";

      if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
      }

      $ID=$conn->insert_id;
      //upload of Bike picture

      $dossier =  $_SERVER['DOCUMENT_ROOT'].'/images_bikes/';
      $fichier = $ID.$extension;

       if(move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
       {
          $upload=true;
          $path= $dossier . $fichier;
       }
       else
       {
            errorMessage("ES0024");
       }
       $fichier = $ID."_mini".$extension;

      if(move_uploaded_file($_FILES['fileMini']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
      {
         $upload=true;
         $path= $dossier . $fichier;
      }
      else
      {
           errorMessage("ES0024");
      }

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
