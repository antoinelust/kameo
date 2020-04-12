<?php
include '../.php';

try
{
	$bdd = new PDO('mysql:host='.$servername.';dbname='.$dbname.';charset=utf8', $username, $password);
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE MOVING = \'Y\' AND FRAME_NUMBER LIKE \''.$_GET['frame_number'].'\';');

//print_r($bdd->errorInfo());

while ($donnees = $reponse->fetch())
{
	echo $donnees['PLACE_IN_BUILDING'];
}

$reponse->closeCursor();
?>