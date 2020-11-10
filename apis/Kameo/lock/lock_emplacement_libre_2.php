<?php
include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';


error_log("--------------------------------------------------------------------------------------- \n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_emplacement_libre_2.php - INPUT - building :".$_GET['building']."\n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - lock_emplacement_libre_2.php - INPUT - max_empl :".$_GET['max_empl']."\n", 3, "logs/logs_boxes.log");



try
{
	$bdd = new PDO('mysql:host='.$servername.';dbname='.$dbname.';charset=utf8', $username, $password);
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$emplacement = 0;
do
{
	$emplacement ++;
	$reponse = $bdd->query('SELECT BIKE_ID FROM locking_bikes WHERE BUILDING LIKE \''.$_GET['building'].'\' AND PLACE_IN_BUILDING = '.$emplacement.';');

	//print_r($bdd->errorInfo());
}
while ($donnees = $reponse->fetch());

if($emplacement > $_GET['max_empl'])
{
	echo -1;
}
else
{
	error_log(date("Y-m-d H:i:s")." - lock_emplacement_libre_2.php- OUTPUT - Emplacement :".$emplacement."\n", 3, "logs/logs_boxes.log");

	echo $emplacement;
}

$reponse->closeCursor();
?>
