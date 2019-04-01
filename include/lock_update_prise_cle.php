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

$reponse = $bdd->query('UPDATE locking_bikes SET MOVING = \'Y\', PLACE_IN_BUILDING = -1 WHERE BUILDING LIKE \''.$_GET['building'].'\' AND PLACE_IN_BUILDING = '.$_GET['emplacement'].';');

$reponse = $bdd->query('UPDATE locking_code SET VALID = \'N\' WHERE BUILDING_START LIKE \''.$_GET['building'].'\' AND CODE = '.$_GET['code'].';');

//print_r($bdd->errorInfo());

$reponse->closeCursor();
?>