<?php
include '../connexion.php';

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
	$reponse = $bdd->query('SELECT FRAME_NUMBER FROM locking_bikes WHERE BUILDING LIKE \''.$_GET['building'].'\' AND PLACE_IN_BUILDING = '.$emplacement.';');

	//print_r($bdd->errorInfo());
}
while ($donnees = $reponse->fetch());

if($emplacement > $_GET['max_empl'])
{
	echo -1;
}
else
{
	echo $emplacement;	
}

$reponse->closeCursor();
?>