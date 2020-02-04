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

$reponse = $bdd->query('SELECT FRAME_NUMBER FROM locking_bikes WHERE BUILDING LIKE \''.$building.'\' AND PLACE_IN_BUILDING = '.$_GET['emplacement'].';');

//print_r($bdd->errorInfo());

while ($donnees = $reponse->fetch())
{
	echo $donnees['FRAME_NUMBER'];
}

$reponse->closeCursor();
?>