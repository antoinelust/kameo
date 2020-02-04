<?php

  //récupération des données du $_POST (pré boucle)
  $companyId = isset($_POST["companyId"]) ? $_POST["companyId"] : NULL;
  $email = isset($_POST["email"]) ? $_POST["email"] : NULL;
  $firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : NULL;
  $lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : NULL;
  $function = isset($_POST["function"]) ? $_POST["function"] : NULL;
  $bikesStats = isset($_POST["bikesStats"]) ? $_POST["bikesStats"] : false;


  //creation de la response
  $response['companyId'] = $companyId;
  $response['email'] = $email;
  $response['firstName'] = $firstName;
  $response['lastName'] = $lastName;
  $response['function'] = $function;
  $response['bikesStats'] = $bikesStats;

  echo json_encode($response);


 ?>
