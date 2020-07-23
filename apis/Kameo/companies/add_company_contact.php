<?php
//session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($addClient) || $addClient != true){
  if(!isset($_SESSION))
  {
      session_start();
  }
}

include '../connexion.php';

$response=array();
//récupération des données du $_POST (pré boucle)
$companyId = isset($_POST["companyId"]) ? $conn->real_escape_string($_POST["companyId"]) : NULL;
$email = isset($_POST["contactEmail"]) ? $conn->real_escape_string($_POST["contactEmail"]) : NULL;
$firstName = isset($_POST["firstName"]) ? $conn->real_escape_string($_POST["firstName"]) : NULL;
$lastName = isset($_POST["lastName"]) ? $conn->real_escape_string($_POST["lastName"]) : NULL;
$phone = isset($_POST["phone"]) ? $conn->real_escape_string($_POST["phone"]) : NULL;
$function = isset($_POST["function"]) ? $conn->real_escape_string($_POST["function"]) : NULL;
$bikesStats = isset($_POST["bikesStats"]) ? (($_POST["bikesStats"] == 'true')) ? 'Y' : 'N')  : 'N';
$USRemail = isset($_POST["email"]) ? $conn->real_escape_string($_POST["email"]) : NULL;

if(isset($addClient) && $addClient == true){
$companyId = $compID;
}


include 'connexion.php';

$stmt = $conn->prepare("INSERT INTO companies_contact (NOM, PRENOM, EMAIL, PHONE, FUNCTION, ID_COMPANY, BIKES_STATS,USR_MAJ)
 VALUES (?, ?, ?, ?, ?, ?, ?, ?)");        
$stmt->bind_param("ssssssss", $lastName, $firstName, $email, $phone, $function, $companyId, $bikesStats, $USRemail);
$stmt->execute();
$id = $conn->insert_id;
 $stmt->close();
$conn->close();

if(!isset($addClient) || $addClient == false){
    $response['id'] = $id;
    $response['emailContact'] = $email;
    $response['firstName'] = $firstName;
    $response['lastName'] = $lastName;
    $response['phone'] = $phone;
    $response['fonction'] = $function;
    $response['bikesStats'] = $bikesStats;
    $response['companyId'] = $companyId;
    echo(json_encode($response));
    die;
}
