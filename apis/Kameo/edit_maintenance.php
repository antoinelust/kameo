<?php

if(!isset($_SESSION))
{
    session_start();
}
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

$id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
$user = isset($_POST["user"]) ? $_POST["user"] : NULL;
$date = isset($_POST["dateMaintenance"]) ? date('Y-m-d',strtotime($_POST["dateMaintenance"])): NULL;
$status = isset($_POST["status"]) ? $_POST["status"] : NULL;
$comment = isset($_POST["comment"]) ? addslashes($_POST["comment"]) : NULL;
$bike_id = isset($_POST["velo"]) ? $_POST["velo"] : NULL;
error_log($date);


if(isset($_POST["action"])){
  if ($_POST["action"]=="edit") {

    if (isset($_FILES['file'])){
      $temp = explode(".", $_FILES["file"]["name"]);
  
      //upload of Maintenance picture
  
      $dossier = $_SERVER['DOCUMENT_ROOT'].'/images_entretiens/';
  
      $i = 1;
      $fichier=strtolower(strval($id)) ."_". strval($i) . '.' . end($temp);
      while(file_exists($dossier.$fichier)){
        $i++;
        $fichier=strtolower(strval($id))."_".strval($i). '.' . end($temp);
      }
  
      move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichier); //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
    }
  
    include 'connexion.php';
    $sql ="UPDATE entretiens SET USR_MAJ = '$user', HEU_MAJ = CURRENT_TIMESTAMP, DATE = '$date', STATUS = '$status', COMMENT = '$comment' WHERE ID = $id;";
  
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    } else {
      $response = array ('response'=>'success', 'message' => 'la modification a bien été effectuée');
      echo json_encode($response);
      die;
    }
  }
  else{
    include 'connexion.php';
    
    $sql ="INSERT INTO entretiens (HEU_MAJ, USR_MAJ, BIKE_ID, DATE, STATUS, COMMENT, NR_ENTR ) 
    VALUES (CURRENT_TIMESTAMP, '$user', '$bike_id', '$date', '$status', '$comment', 1)";
  
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    } else {
      $response = array ('response'=>'success', 'message' => "l'entretien a bien été rajouté");
      echo json_encode($response);
      die;
    }
  }
}
