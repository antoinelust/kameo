<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

if ($_POST['action'] == "setAsRead") {
  $ID = isset($_POST['ID']) ? $_POST['ID'] : NULL;
  include 'connexion.php';
  $sql= "UPDATE notifications SET `READ` = 'Y' WHERE ID = '$ID'";
  if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
  } else{
    $response = array ('response'=>'success', 'id' => $ID);
    echo json_encode($response);
    die;
  }
}
