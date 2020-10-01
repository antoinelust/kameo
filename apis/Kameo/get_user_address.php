<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


include_once 'authentication.php';
include 'globalfunctions.php';

$token = getBearerToken();

if(!isset($_SESSION))
{
    session_start();
}

if(get_user_permissions("search", $token)){


  include 'connexion.php';
  $sql="SELECT ADRESS as address, POSTAL_CODE as postalCode, CITY as city  FROM customer_referential WHERE TOKEN='$token'";
  if ($conn->query($sql) === FALSE) {

  	$response = array ('response'=>'error', 'message'=> $conn->error);
  	echo json_encode($response);
  	die;

  }

  $result = mysqli_query($conn, $sql);
  $resultat = mysqli_fetch_assoc($result);
  $address=$resultat['address'] . ", " . $resultat['postalCode'] . ", " . $resultat['city'];

  $address=str_replace(str_split(' \,'),"+",$address);

  $response['address']=$address;

  echo json_encode($response);
  die;
}else{
  error_message('403');
}

?>
