<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


include 'globalfunctions.php';

$userID = $_POST["userID"];
$UserPassword = $_POST["password"];

include 'connexion.php';


$sql = "SELECT * FROM customer_referential where EMAIL='$userID'";

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

if (password_verify($UserPassword, $row["PASSWORD"])) { 
	$_SESSION['userID']=$userID;
	$_SESSION['UserPassword']=$UserPassword;
	$_SESSION['login']=true;
}
else{
	$_SESSION['login']=false;
	errorMessage("ES0007");
}

$response = array ('response'=>'success');
echo json_encode($response);
die;

?>