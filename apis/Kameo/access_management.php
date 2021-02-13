<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


include 'globalfunctions.php';
include 'connexion.php';
include_once 'authentication.php';

$token = getBearerToken();
log_inputs($token);


$userID = $_POST["userID"];
$userIDUP=strtoupper($userID);
$UserPassword = $_POST["password"];

$sql = "SELECT ID, PASSWORD, TOKEN, STAANN FROM customer_referential where UPPER(EMAIL)='$userIDUP'";

if ($conn->query($sql) === FALSE) {
	$response = array ('response'=>'error', 'message'=> $conn->error);
	echo json_encode($response);
	die;
}
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$conn->close();


if($row["PASSWORD"]==NULL)
{
	errorMessage("ES0006");
}

if($row["STAANN"]=="D"){
    errorMessage("ES0029");
}

if (password_verify($UserPassword, $row["PASSWORD"])) {
	$_SESSION['ID'] = $row['ID'];
	$_SESSION['userID']=$userID;
	$_SESSION['UserPassword']=$UserPassword;
	$_SESSION['bearerToken']=$row['TOKEN'];
}
else{
	errorMessage("ES0007");
}

$response = array ('response'=>'success');
echo json_encode($response);
die;

?>
