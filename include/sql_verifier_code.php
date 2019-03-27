<?php
include 'connexion2.php';


$building = "venturelab";

try
{
	$bdd = new PDO('mysql:host='.$servername.';dbname='.$dbname.';charset=utf8', $username, $password);
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE FRAME_NUMBER LIKE (SELECT FRAME_NUMBER FROM reservations WHERE ID = (SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$building.'\' AND CODE = '.$_GET['code'].' AND VALID = \'Y\' AND DATE_BEGIN <= UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) AND DATE_END >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP())));');

//print_r($bdd->errorInfo());

while ($donnees = $reponse->fetch())
{
	echo $donnees['PLACE_IN_BUILDING'];
}

$reponse->closeCursor();
?>