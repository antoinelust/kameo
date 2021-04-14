<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

include 'connexion.php';

$sql="SELECT * from bike_catalog WHERE SEASON='2020-2021' AND BRAND='Kayza'";
if ($conn->query($sql) === FALSE){
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
}
$result = mysqli_query($conn, $sql);
$dossier = '../../images_bikes/atraiter/';
$dossier2= '../../images_bikes/';
while($row = mysqli_fetch_array($result)){
  echo $row['BRAND']." - ".$row['MODEL']." - ".$row['FRAME_TYPE']."\n";
  $fichier =  $dossier.strtolower(str_replace(" ", "_", $row["BRAND"]))."_".strtolower(str_replace(" ", "_", $row["MODEL"]))."_".strtolower($row["FRAME_TYPE"]).".JPG";
  $fichier_mini =  $dossier.strtolower(str_replace(" ", "_", $row["BRAND"]))."_".strtolower(str_replace(" ", "_", $row["MODEL"]))."_".strtolower($row["FRAME_TYPE"])."_mini.JPG";
  if (file_exists($fichier)) {
    rename($fichier, $dossier2.$row["ID"].'.jpg');
    echo "fichier principal trouvé !\n";
  }else{
    echo "fichier principal non trouvé : ".$fichier."\n";
  }
  if(file_exists($fichier_mini))
  {
    echo "fichier mini trouvé !\n";
    rename($fichier_mini, $dossier2.$row["ID"].'_mini.jpg');
  }else{
    echo "fichier mini non trouvé : ".$fichier_mini."\n";
  }
  echo "----------------------- \n\n";
}


echo "----------------------- \n\n";
echo "----------------------- \n\n";
echo "----------------------- \n\n";

$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_array($result)){
  echo $row['BRAND']." - ".$row['MODEL']." - ".$row['FRAME_TYPE']."\n";
  $fichier =  $dossier2.$row['ID'].".jpg";
  $fichier_mini =  $dossier2.$row['ID']."_mini.jpg";
  if (!file_exists($fichier)){
    echo "fichier manquant ! : ".$row['BRAND']." ".$row['MODEL']." ".$ROW['FRAME_TYPE']." - ".$fichier."\n";
  }else{
    echo "fichier présent !\n";
    echo "taille de l'image : ".(getimagesize($fichier)[0]/getimagesize($fichier)[1])."\n";
  }
  if (!file_exists($fichier_mini)){
    echo "fichier mini manquant ! : ".$row['BRAND']." ".$row['MODEL']." ".$ROW['FRAME_TYPE']." - ".$fichier_mini."\n";
  }else{
    echo "fichier mini présent !\n";
    echo "taille de l'image : ".(getimagesize($fichier_mini)[0]/getimagesize($fichier_mini)[1])."\n";
  }
}

?>
