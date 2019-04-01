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

$reponse = $bdd->query('UPDATE locking_bikes SET MOVING = \'Y\' WHERE 1');
$reponse = $bdd->query('UPDATE locking_bikes SET PLACE_IN_BUILDING = -1 WHERE 1');

//print_r($bdd->errorInfo());

$reponse->closeCursor();
?>