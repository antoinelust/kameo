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

$reponse = $bdd->query('UPDATE locking_bikes SET MOVING = \'N\', PLACE_IN_BUILDING = '.$_GET['emplacement'].', BUILDING = \''.$_GET['building'].'\' WHERE FRAME_NUMBER LIKE \''.$_GET['frame_number'].'\';');

//print_r($bdd->errorInfo());

$reponse->closeCursor();
?>