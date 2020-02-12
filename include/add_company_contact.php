<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';

  $response=array();
  //récupération des données du $_POST (pré boucle)
  $id = 0;
  $companyId = isset($_POST["companyId"]) ? $_POST["companyId"] : NULL;
  $email = isset($_POST["email"]) ? $_POST["email"] : NULL;
  $firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : NULL;
  $lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : NULL;
  $phone = isset($_POST["phone"]) ? $_POST["phone"] : NULL;
  $function = isset($_POST["function"]) ? $_POST["function"] : NULL;
  $bikesStats = isset($_POST["bikesStats"]) ? $_POST["bikesStats"] : false;

  /*include 'connexion.php';
  $sql= "INSERT INTO companies_contact (NOM, PRENOM, EMAIL, PHONE, FUNCTION, ID_COMPANY, BIKES_STATS)
         VALUES ($lastName, $firstName, $email, $phone, $function, $companyId, $bikesStats);";
  $result = mysqli_query($conn, $sql);
  $id = $conn->insert_id;

  //$id = $conn->query($sql);
  $conn->close();*/

  //creation de la response
  $response['id'] = $id;
  $response['companyId'] = $companyId;
  $response['email'] = $email;
  $response['firstName'] = $firstName;
  $response['lastName'] = $lastName;
  $response['fonction'] = $function;
  $response['phone'] = $phone;
  $response['bikesStats'] = $bikesStats;

  echo json_encode($response);
  die;
