<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/authentication.php';
date_default_timezone_set('UTC');
$today = date("Y-m-d H:i:s");
$sql= "INSERT INTO activitylog (TIMESTAMP, MAIL) VALUES ('$today','$token')"; 
if ($conn->query($sql) === FALSE) {
	echo $conn->error;
}
$conn->close();
?>
