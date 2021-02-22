<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$email=isset($_POST['email']) ? $_POST['email'] : NULL;
$company=isset($_POST['company']) ? $_POST['company'] : (isset($_GET['company']) ? $_GET['company'] : NULL);
$companyID=isset($_POST['companyID']) ? $_POST['companyID'] : (isset($_GET['companyID']) ? $_GET['companyID'] : NULL);
$response=array();

require_once 'authentication.php';
$token = getBearerToken();
log_inputs($token);


if($email != NULL || $company != NULL || $token != NULL || $company != NULL){

  include 'connexion.php';

  if($company == NULL && $companyID == NULL){
    if($token != NULL){
      $sql = "SELECT COMPANY from customer_referential WHERE TOKEN = '$token'";
    }else if ($email != NULL){
      $sql = "SELECT COMPANY from customer_referential WHERE EMAIL = '$email'";
    }else{
      errorMessage("ES0038");
    }

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $company = $resultat['COMPANY'];
  }

  if($company){
    $sql = "SELECT NOM AS name, PRENOM AS firstName, PHONE as phone, EMAIL AS email, STAANN AS staann FROM customer_referential WHERE COMPANY = '$company'";
  }else{
    $sql = "SELECT NOM AS name, PRENOM AS firstName, PHONE as phone, EMAIL AS email, customer_referential.STAANN AS staann FROM customer_referential, companies WHERE companies.ID='$companyID' and companies.INTERNAL_REFERENCE=customer_referential.COMPANY";
  }

  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }
  $result = mysqli_query($conn, $sql);
  $response['users'] = $result->fetch_all(MYSQLI_ASSOC);
  $response['usersNumber']=$result->num_rows;
  $response['response']="success";

  log_output($response);
  echo json_encode($response);

  $result->close();
}else{
    errorMessage("ES0038");
}

die;

?>
