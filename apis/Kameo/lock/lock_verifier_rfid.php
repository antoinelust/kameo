<?php
include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';

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
  $reponse2 = $bdd->query('SELECT RESERVATION_ID FROM locking_bikes WHERE BUILDING LIKE \''.$building.'\' AND BIKE_ID = \''.$bikeID.'\';');
	$resultat = $reponse2->fetch();
  echo $donnees['PLACE_IN_BUILDING'].'/'.$resultat['RESERVATION_ID'];
  error_log(date("Y-m-d H:i:s")." - lock_verifier_rfid.php - OUTPUT emplacement :".$donnees['PLACE_IN_BUILDING'].'/'.$resultat['RESERVATION_ID']."\n", 3, "logs/logs_boxes.log");
  execSQL("INSERT INTO reservations_details (ACTION, RESERVATION_ID, BUILDING, OUTCOME) VALUES (?, ?, ?, ?)", array('siss', 'verifier_rfid', $resultat['RESERVATION_ID'], $_GET['building'], $donnees['PLACE_IN_BUILDING'].'/'.$resultat['RESERVATION_ID']), true);
}

$reponse->closeCursor();
?>
