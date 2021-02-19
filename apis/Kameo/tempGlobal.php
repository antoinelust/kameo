<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

include 'connexion.php';

$sql="SELECT * from bike_catalog WHERE STAANN != 'D' AND DISPLAY='Y'";
if ($conn->query($sql) === FALSE){
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
}
$dossier2= '../../images_bikes/';
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){

  $fichier =  $dossier2.$row['ID'].".jpg";
  $fichier_mini =  $dossier2.$row['ID']."_mini.jpg";


  if((getimagesize($fichier)[0]/getimagesize($fichier)[1])>1.79 || (getimagesize($fichier)[0]/getimagesize($fichier)[1])<1.77){
    echo $row['BRAND']." ".$row['MODEL']." ".$row['FRAME_TYPE']." - Season : ".$row['SEASON']."\n";

  }
}
?>
