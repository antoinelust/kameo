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

$reponse = $bdd->query('SELECT * FROM locking_bikes;');

//print_r($bdd->errorInfo());

while ($donnees = $reponse->fetch())
{
	echo $donnees['ID'] . ' | ' . $donnees['FRAME_NUMBER'] . ' | ' . $donnees['PLACE_IN_BUILDING'] . ' | ' . $donnees['MOVING'] . '<br>';
}
echo '<br>';

$reponse = $bdd->query('SELECT * FROM locking_code;');

//print_r($bdd->errorInfo());

while ($donnees = $reponse->fetch())
{
	echo $donnees['ID_reservation'] . ' | ' . $donnees['DATE_BEGIN'] . ' | ' . $donnees['DATE_END'] . ' | ' . $donnees['CODE'] . ' | ' . $donnees['VALID'] . '<br>';
}

$reponse->closeCursor();
?>