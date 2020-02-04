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

$reponse = $bdd->query('SELECT * FROM locking_bikes;');

//print_r($bdd->errorInfo());

while ($donnees = $reponse->fetch())
{
	echo $donnees['ID'] . ' | ' . $donnees['FRAME_NUMBER'] . ' | ' . $donnees['BUILDING'] . ' | ' . $donnees['PLACE_IN_BUILDING'] . ' | ' . $donnees['MOVING'] . '<br>';
}
echo '<br>';

$reponse = $bdd->query('SELECT * FROM locking_code;');

//print_r($bdd->errorInfo());

while ($donnees = $reponse->fetch())
{
	echo $donnees['ID_reservation'] . ' | ' . $donnees['DATE_BEGIN'] . ' | ' . $donnees['DATE_END'] . ' | ' . $donnees['BUILDING_START'] . ' | ' . $donnees['CODE'] . ' | ' . $donnees['VALID'] . '<br>';
}

$reponse->closeCursor();
?>