<?php
include 'include/connexion.php';
date_default_timezone_set('UTC');
$today = date("Y-m-d H:i:s");
$sql= "INSERT INTO activitylog (TIMESTAMP, MAIL) VALUES ('$today','$user')"; 
if ($conn->query($sql) === FALSE) {

	echo $conn->error;
}

$conn->close();
?>
