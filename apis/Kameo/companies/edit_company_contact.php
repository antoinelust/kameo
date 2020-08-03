<?php

if(!isset($_SESSION))
{
    session_start();
}
include '../connexion.php';

$id = isset($_POST["id"]) ? $conn->real_escape_string($_POST["id"]) : NULL;
$companyId = isset($_POST["companyId"]) ? $conn->real_escape_string($_POST["companyId"]) : NULL;
$email = isset($_POST["contactEmail"]) ? $conn->real_escape_string($_POST["contactEmail"]) : NULL;
$firstName = isset($_POST["firstName"]) ? $conn->real_escape_string($_POST["firstName"]) : NULL;
$lastName = isset($_POST["lastName"]) ? $conn->real_escape_string($_POST["lastName"]) : NULL;
$phone = isset($_POST["phone"]) ? $conn->real_escape_string($_POST["phone"]) : NULL;
$function = isset($_POST["function"]) ? $conn->real_escape_string($_POST["function"]) : NULL;
$bikesStats = isset($_POST["bikesStats"]) ? (($_POST["bikesStats"] == 'true') ? 'Y' : 'N')  : 'N';
$USRemail = isset($_POST["email"]) ? $conn->real_escape_string($_POST["email"]) : NULL;


include 'connexion.php';
$stmt = $conn->prepare("UPDATE companies_contact
        SET EMAIL = ?,
            NOM = ?,
            PRENOM = ?,
            PHONE = ?,
            FUNCTION = ?,
            BIKES_STATS = ?,
            USR_MAJ = ?,
            HEU_MAJ = CURRENT_TIMESTAMP
        WHERE ID = ?");

$stmt->bind_param("ssssssss", $email, $lastName, $firstName, $phone, $function, $bikesStats, $USRemail, $id);
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
$response['companyId'] = $companyId;

echo(json_encode($response));
die;