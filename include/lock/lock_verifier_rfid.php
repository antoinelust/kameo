<?php
include '../connexion.php';

$frameNumber=$_GET['frame_number'];

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

$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE MOVING = \'Y\' AND BIKE_ID = \''.$bikeID.'\';');

//print_r($bdd->errorInfo());

while ($donnees = $reponse->fetch())
{
	echo $donnees['PLACE_IN_BUILDING'];
}

$reponse->closeCursor();
?>