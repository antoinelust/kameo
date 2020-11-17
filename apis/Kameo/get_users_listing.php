<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$email=isset($_POST['email']) ? $_POST['email'] : NULL;
$company=isset($_POST['company']) ? $_POST['company'] : NULL;
$response=array();

require_once 'authentication.php';
$token = getBearerToken();
log_inputs($token);


if($email != NULL || $company != NULL){
  include 'connexion.php';
  if($company == NULL){
    $sql = "SELECT COMPANY from customer_referential WHERE EMAIL = '$email'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $company = $resultat['COMPANY'];
  }

  $sql = "SELECT NOM AS name, PRENOM AS firstName, EMAIL AS email, STAANN AS staann FROM customer_referential WHERE COMPANY = '$company'";
  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }
  $result = mysqli_query($conn, $sql);

  if($result->num_rows=='0'){
      errorMessage("ES0039");
  }

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
