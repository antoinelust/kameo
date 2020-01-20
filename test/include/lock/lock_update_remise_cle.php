<?php
include 'connexion2.php';

try
{
	$bdd = new PDO('mysql:host='.$servername.';dbname='.$dbname.';charset=utf8', $username, $password);
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$reponse = $bdd->query('SELECT RESERVATION_ID FROM locking_bikes WHERE BUILDING LIKE \''.$_GET['building'].'\' AND FRAME_NUMBER = \''.$_GET['frame_number'].'\';');

//print_r($bdd->errorInfo());

while ($donnees = $reponse->fetch())
{
	$ID_booking=$donnees['RESERVATION_ID'];
    $reponse = $bdd->query('UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=\'mykameo\', STATUS = \'Closed\' WHERE ID LIKE \''.$ID_booking.'\';');
}



$temp=new DateTime();
$dateNow=strtotime($temp->format('Y-m-d H:i'));


$reponse = $bdd->query('UPDATE locking_bikes SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=\'mykameo\', MOVING = \'N\', PLACE_IN_BUILDING = '.$_GET['emplacement'].', BUILDING = \''.$_GET['building'].'\' WHERE FRAME_NUMBER LIKE \''.$_GET['frame_number'].'\';');

//print_r($bdd->errorInfo());

$reponse->closeCursor();
?>