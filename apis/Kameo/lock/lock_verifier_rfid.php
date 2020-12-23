<?php
include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';

$frameNumber=$_GET['frame_number'];
$building=$_GET['building'];


error_log("--------------------------------------------------------------------------------------- \n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_verifier_rfid.php - INPUT building :".$_GET['building']."\n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_verifier_rfid.php - INPUT frame_number :".$_GET['frame_number']."\n", 3, "logs/logs_boxes.log");


$sql="SELECT ID FROM customer_bikes where FRAME_NUMBER='$frameNumber'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$resultat = mysqli_fetch_assoc($result);
$conn->close();

$bikeID=$resultat['ID'];

try
{
	$bdd = new PDO('mysql:host='.$servername.';dbname='.$dbname.';charset=utf8', $username, $password);
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE MOVING = \'Y\' AND BIKE_ID = \''.$bikeID.'\' AND BUILDING = \''.$building.'\';');

while ($donnees = $reponse->fetch())
{
  error_log(date("Y-m-d H:i:s")." - lock_verifier_rfid.php - OUTPUT emplacement :".$donnees['PLACE_IN_BUILDING']."\n", 3, "logs/logs_boxes.log");
	echo $donnees['PLACE_IN_BUILDING'];
}

$reponse->closeCursor();
?>
