<?php
include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';


error_log("--------------------------------------------------------------------------------------- \n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_verifier_code.php - building :".$_GET['building']."\n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_verifier_code.php - code :".$_GET['code']."\n", 3, "logs/logs_boxes.log");


$is_null = true;

try
{
	$bdd = new PDO('mysql:host='.$servername.';dbname='.$dbname.';charset=utf8', $username, $password);
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}



$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE BUILDING="'.$_GET['building'].'" AND BIKE_ID LIKE (SELECT BIKE_ID FROM reservations WHERE ID = (SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = '.$_GET['code'].' AND VALID = \'Y\' AND DATE_BEGIN <= UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) AND DATE_END >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP())));');

while ($donnees = $reponse->fetch())
{
	$is_null = false;
	echo $donnees['PLACE_IN_BUILDING'];
	error_log(date("Y-m-d H:i:s")." - lock_verifier_code.php - OUTPUT :".$donnees['PLACE_IN_BUILDING']."\n", 3, "logs/logs_boxes.log");

}

if ($is_null)
{
	$reponse = $bdd->query('SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = '.$_GET['code'].' AND VALID = \'Y\';');
	if($reponse->fetch())
	{
		echo "-4";	// Hors delai
		error_log(date("Y-m-d H:i:s")." OUTPUT - Hors délai \n", 3, "logs/logs_boxes.log");
		$is_null = false;
	}
}
if ($is_null)
{
	$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE BIKE_ID LIKE (SELECT BIKE_ID FROM reservations WHERE ID = (SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = '.$_GET['code'].' AND VALID = \'N\' AND DATE_BEGIN <= UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) AND DATE_END >= CURRENT_TIMESTAMP()));');
	if($reponse->fetch())
	{
		echo "-2";	// Code deja utilise ou annule
		error_log(date("Y-m-d H:i:s")." OUTPUT - Code déjà utilisé ou annulé \n", 3, "logs/logs_boxes.log");

		$is_null = false;
	}
}
if ($is_null)
{
	echo "-3"; 	// Mauvais code
	error_log(date("Y-m-d H:i:s")." OUTPUT - -Mauvais code \n", 3, "logs/logs_boxes.log");

}

$reponse->closeCursor();
?>
