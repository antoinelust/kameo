<?php
include 'connexion2.php';

$is_null = true;

try
{
	$bdd = new PDO('mysql:host='.$servername.';dbname='.$dbname.';charset=utf8', $username, $password);
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}



$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE FRAME_NUMBER LIKE (SELECT FRAME_NUMBER FROM reservations WHERE ID = (SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = '.$_GET['code'].' AND VALID = \'Y\' AND DATE_BEGIN <= UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) AND DATE_END >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP())));');


//print_r($bdd->errorInfo());

while ($donnees = $reponse->fetch())
{
	$is_null = false;
	echo $donnees['PLACE_IN_BUILDING'];
}

if ($is_null)
{
	$reponse = $bdd->query('SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = '.$_GET['code'].' AND VALID = \'Y\';');
	if($reponse->fetch())
	{
		echo "-1";	// Hors delai
		$is_null = false;
	}
}
if ($is_null)
{
	$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE FRAME_NUMBER LIKE (SELECT FRAME_NUMBER FROM reservations WHERE ID = (SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = '.$_GET['code'].' AND VALID = \'N\' AND DATE_BEGIN <= UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) AND DATE_END >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP())));');
	if($reponse->fetch())
	{
		echo "-2";	// Code deja utilise ou annule
		$is_null = false;
	}
}
if ($is_null)
{
	echo "-3"; 	// Mauvais code
}

$reponse->closeCursor();
?>