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
$comment = isset($_POST["comment"]) ? $_POST["comment"] : NULL;
error_log($date);

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
