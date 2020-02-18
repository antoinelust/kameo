<?php

if(!isset($_SESSION))
{
    session_start();
}
include 'globalfunctions.php';

$id = isset($_POST["id"]) ? $_POST["id"] : NULL;

if($id != NULL){
/*  include 'connexion.php';
  $sql = "DELETE FROM companies_contact
          WHERE ID = $id;";

  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }*/
  echo ('valide');
}else{
  $response = 'ID invalide';
  echo json_encode($response);
  die;
}
