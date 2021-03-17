<?php
include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';


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

$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE BUILDING="'.$_GET['building'].'" AND BIKE_ID LIKE (SELECT BIKE_ID FROM reservations WHERE ID = (SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE "'.$_GET['building'].'" AND CODE = "'.$_GET['code'].'" AND VALID = "Y" AND DATE_BEGIN <= UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) AND DATE_END >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP())));');

while ($donnees = $reponse->fetch())
{
	$is_null = false;
	$reponse2 = $bdd->query('SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = \''.$_GET['code'].'\' AND VALID = \'Y\';');
	$resultat = $reponse2->fetch();
	echo $donnees['PLACE_IN_BUILDING']."/".$resultat['ID_reservation'];
	execSQL("INSERT INTO reservations_details (ACTION, RESERVATION_ID, BUILDING, OUTCOME) VALUES (?, ?, ?, ?)", array('siss', 'verifier_code', $resultat['ID_reservation'], $_GET['building'], $donnees['PLACE_IN_BUILDING']."/".$resultat['ID_reservation']), true);
	error_log(date("Y-m-d H:i:s")." - lock_verifier_code.php - OUTPUT :".$donnees['PLACE_IN_BUILDING']."/".$resultat['ID_reservation']."\n", 3, "logs/logs_boxes.log");
}

if ($is_null)
{
	$reponse = $bdd->query('SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = \''.$_GET['code'].'\' AND VALID = \'Y\';');
	if($reponse->fetch())
	{
		echo "-4/9999";	// Hors delai
		$reponse2 = $bdd->query('SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = \''.$_GET['code'].'\' AND VALID = \'Y\';');
		$resultat = $reponse2->fetch();
		execSQL("INSERT INTO reservations_details (ACTION, RESERVATION_ID, BUILDING, OUTCOME) VALUES (?, ?, ?, ?)", array('siss', 'verifier_code', $_GET['building'], $resultat['ID_reservation'], '-4'), true);
		error_log(date("Y-m-d H:i:s")." OUTPUT - Hors délai \n", 3, "logs/logs_boxes.log");
		$is_null = false;
	}
}
if ($is_null)
{
	$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE BIKE_ID LIKE (SELECT BIKE_ID FROM reservations WHERE ID = (SELECT ID_reservation FROM locking_code WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = \''.$_GET['code'].'\' AND VALID = \'N\' AND DATE_BEGIN <= UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) AND DATE_END >= CURRENT_TIMESTAMP()));');
	if($reponse->fetch())
	{
		echo "-2/9999";	// Code deja utilise ou annule
		execSQL("INSERT INTO reservations_details (ACTION, RESERVATION_ID, BUILDING, OUTCOME) VALUES (?, ?, ?, ?)", array('siss', 'verifier_code', '9999', $_GET['building'], '-2'), true);
		error_log(date("Y-m-d H:i:s")." OUTPUT - Code déjà utilisé ou annulé \n", 3, "logs/logs_boxes.log");
		$is_null = false;
	}
}
if ($is_null)
{
	echo "-3/9999"; 	// Mauvais code
	execSQL("INSERT INTO reservations_details (ACTION, RESERVATION_ID, BUILDING, OUTCOME) VALUES (?, ?, ?, ?)", array('siss', 'verifier_code', '9999', $_GET['building'], '-3'), true);
	error_log(date("Y-m-d H:i:s")." OUTPUT - -Mauvais code \n", 3, "logs/logs_boxes.log");
}
$reponse->closeCursor();
?>
