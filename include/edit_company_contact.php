<?php

if(!isset($_SESSION))
{
    session_start();
}
include 'globalfunctions.php';

$id = isset($_POST["id"]) ? $_POST["id"] : NULL;
$companyId = isset($_POST["companyId"]) ? $_POST["companyId"] : NULL;
$email = isset($_POST["contactEmail"]) ? $_POST["contactEmail"] : NULL;
$firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : NULL;
$lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : NULL;
$phone = isset($_POST["phone"]) ? $_POST["phone"] : NULL;
$function = isset($_POST["function"]) ? $_POST["function"] : NULL;
$bikesStats = isset($_POST["bikesStats"]) ? (($_POST["bikesStats"] == 'true') ? 'Y' : 'N')  : 'N';

include 'connexion.php';
$sql = "UPDATE companies_contact
        SET EMAIL = '$email',
            NOM = '$lastName',
            PRENOM = '$firstName',
            PHONE = '$phone',
            FUNCTION = '$function',
            BIKES_STATS = '$bikesStats'
        WHERE ID = $id;";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}



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
