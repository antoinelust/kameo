<?php
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
  session_start();
}
include 'globalfunctions.php';

$id = isset($_POST["id"]) ? $_POST["id"] : NULL;
$file = isset($_POST["file"]) ? $_POST["file"] : NULL;
$unlink = false;
$response = array();

//construction du lien vers le fichier sur le serveur
if ($file != NULL) {
  //adresse de la racine du site
  $file = __DIR__ . '/../' . $file;
}



if($id != NULL){
  $unlink = unlink($file);
  if ($unlink == true){
    include 'connexion.php';
    $sql = "DELETE FROM companies_offers
            WHERE ID = $id;";

    if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }
    $response = array('response' => true);
    echo json_encode($response);
  } else{
    $response = array ('response'=>'error', 'message'=> 'Une erreur est survenue lors de la suppression du fichier');
    echo json_encode($response);
  }
}else{
  $response = 'ID invalide';
  echo json_encode($response);
  die;
}
