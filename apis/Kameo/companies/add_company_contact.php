<?php
//session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


$response=array();
$companyId = isset($_POST["companyId"]) ? $conn->real_escape_string($_POST["companyId"]) : NULL;
$email = isset($_POST["contactEmail"]) ? $conn->real_escape_string($_POST["contactEmail"]) : NULL;
$firstName = isset($_POST["firstName"]) ? $conn->real_escape_string($_POST["firstName"]) : NULL;
$lastName = isset($_POST["lastName"]) ? $conn->real_escape_string($_POST["lastName"]) : NULL;
$phone = isset($_POST["phone"]) ? $conn->real_escape_string($_POST["phone"]) : NULL;
$function = isset($_POST["function"]) ? $conn->real_escape_string($_POST["function"]) : NULL;
$bikesStats = isset($_POST["bikesStats"]) ? (($_POST["bikesStats"] == 'true')) ? 'Y' : 'N'  : 'N';
$type = isset($_POST["type"]) ? $conn->real_escape_string($_POST["type"]) : NULL;

if(isset($addClient) && $addClient == true){
$companyId = $compID;
}

include __DIR__ .'/../connexion.php';
$stmt = $conn->prepare("INSERT INTO companies_contact (USR_MAJ, NOM, PRENOM, EMAIL, PHONE, FUNCTION, ID_COMPANY, TYPE, BIKES_STATS)
 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssiss", $token, $lastName, $firstName, $email, $phone, $function, $companyId, $type, $bikesStats);
$stmt->execute();
$id = $conn->insert_id;
$stmt->close();
$conn->close();

$response['id'] = $id;
$response['emailContact'] = $email;
$response['firstName'] = $firstName;
$response['lastName'] = $lastName;
$response['phone'] = $phone;
$response['fonction'] = $function;
$response['bikesStats'] = $bikesStats;
$response['type'] = $type;
$response['companyId'] = $companyId;
echo(json_encode($response));
die;
