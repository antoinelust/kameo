<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');
session_start();
include 'globalfunctions.php';

$id = isset($_POST['ID']) ? $_POST['ID'] : NULL;
$response = array();
if ($id != NULL) {
  include 'connexion.php';
  $sql="UPDATE notifications SET `READ` = 'Y' WHERE ID = $id;";
  if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
  }
  else{
    $response = array ('response'=>'success');
    echo json_encode($response);
    die;
  }
} else{
  $response = array ('response'=>'error', 'message'=> "Pas d'id");
  echo json_encode($response);
  die;
}
