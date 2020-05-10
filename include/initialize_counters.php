<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$email=$_POST['email'];

include 'connexion.php';
$sql = "select 1 from customer_bikes where STAANN != 'D' AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE = 'test' OR CONTRACT_TYPE='leasing' OR CONTRACT_TYPE='renting')";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$result = mysqli_query($conn, $sql);
$length=$result->num_rows;

$response=array();
$response['bikeNumber']=$length;
$conn->close();

include 'connexion.php';
$sql = "select 1 from bike_catalog where STAANN != 'D'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$result = mysqli_query($conn, $sql);
$length=$result->num_rows;

$response['bikeNumberPortfolio']=$length;
$conn->close();


include 'connexion.php';
$sql="SELECT * from companies WHERE TYPE='PROSPECT' OR TYPE='CLIENT' AND STAANN != 'D'";    
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);        
$response['companiesNumberClientOrProspect'] = $result->num_rows;
$conn->close();

include 'connexion.php';
$sql="SELECT SUM(LEASING_PRICE) as 'SOMME' from customer_bikes WHERE CONTRACT_START < CURRENT_TIMESTAMP AND (CONTRACT_END > CURRENT_TIMESTAMP OR CONTRACT_END is NULL)";    
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);        
$resultat = mysqli_fetch_assoc($result);
$response['sumContractsCurrent'] = $resultat['SOMME'];
$conn->close();

include 'connexion.php';
$sql="SELECT 1 from company_actions WHERE OWNER = '$email' AND STATUS = 'TO DO'";    
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);        
$length=$result->num_rows;
$response['actionNumberNotDone'] = $length;
$conn->close();

include 'connexion.php';
$sql="SELECT COUNT(1) AS 'SOMME' FROM boxes WHERE STAANN != 'D'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql); 
$resultat = mysqli_fetch_assoc($result);
$response['boxesNumberTotal']=$resultat['SOMME'];
$conn->close();

include 'connexion.php';
$sql="SELECT COUNT(1) AS 'SOMME' FROM factures WHERE FACTURE_SENT='0' OR FACTURE_PAID='0'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql); 
$resultat = mysqli_fetch_assoc($result);
$response['billsNumber']=$resultat['SOMME'];
$conn->close();


include 'connexion.php';


$sql = "SELECT 1 FROM feedbacks WHERE STATUS='DONE'";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$response['feedbacksNumber']=$result->num_rows;

$conn->close();




$response['response']="success";
echo json_encode($response);
die;


?>
