<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

include 'connexion.php';

$sql="SELECT * from bike_catalog WHERE season='2020-2021' and BRAND='Conway'";
if ($conn->query($sql) === FALSE){
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
}
$result = mysqli_query($conn, $sql);
$dossier = '../../images_bikes/atraiter/';
$dossier2= '../../images_bikes/';
while($row = mysqli_fetch_array($result)){
  echo $row['BRAND']." - ".$row['MODEL']." - ".$row['FRAME_TYPE'];
  if (file_exists($dossier.strtolower(str_replace(" ", "-", $row["BRAND"]))."_".strtolower(str_replace(" ", "-", $row["MODEL"]))."_".strtolower($row["FRAME_TYPE"]).'.jpg')) {
    rename($dossier.strtolower(str_replace(" ", "-", $row["BRAND"]))."_".strtolower(str_replace(" ", "-", $row["MODEL"]))."_".strtolower($row["FRAME_TYPE"]).'.jpg', $dossier2.$row["ID"].'.jpg');
    echo "fichier principal trouvé !\n";
  }else{
    echo "fichier principal non trouvé !\n";
  }
  if(file_exists($dossier.strtolower(str_replace(" ", "-", $row["BRAND"]))."_".strtolower(str_replace(" ", "-", $row["MODEL"]))."_".strtolower($row["FRAME_TYPE"]).'_mini.jpg'))
  {
    echo "fichier mini trouvé !\n";
    rename($dossier.strtolower(str_replace(" ", "-", $row["BRAND"]))."_".strtolower(str_replace(" ", "-", $row["MODEL"]))."_".strtolower($row["FRAME_TYPE"]).'_mini.jpg', $dossier2.$row["ID"].'_mini.jpg');
  }else{
    echo "fichier mini non trouvé !\n";
  }
}
/*

$extensions = array('.jpg');
$dossier =  $_SERVER['DOCUMENT_ROOT'].'/images_bikes/';
$fichier = strtolower(str_replace(" ", "-", $brand))."_".strtolower(str_replace(" ", "-", $model))."_".strtolower($frameType).$extension;
 if(move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
 {
    $upload=true;
    $path= $dossier . $fichier;
 }
*/
?>
