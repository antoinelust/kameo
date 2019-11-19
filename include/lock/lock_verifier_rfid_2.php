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

$reponse = $bdd->query('SELECT FRAME_NUMBER FROM locking_bikes WHERE RFID_UID LIKE \''.$_GET['uid'].'\';');

if($donnees = $reponse->fetch())	// uid de velo
{
	$reponse = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE MOVING = \'Y\' AND FRAME_NUMBER LIKE \''.$donnees['FRAME_NUMBER'].'\';');
	if ($donnees = $reponse->fetch())
	{
		echo $donnees['PLACE_IN_BUILDING'];
	}

}
else // pas un uid de velo, verifier si il est dans la liste des customer
{
	$reponse = $bdd->query('SELECT EMAIL FROM customer_referential WHERE RFID_UID LIKE \''.$_GET['uid'].'\';');
	if ($donnees = $reponse->fetch())
	{
		$libre = false;
		//echo $donnees['EMAIL']. "<br>";
		$reponse = $bdd->query('SELECT FRAME_NUMBER FROM locking_bikes WHERE MOVING = \'N\';');
		while (($donnees_1 = $reponse->fetch()) && !$libre)
		{
			//echo $donnees_1['FRAME_NUMBER'] . " || ";
			$reponse_2 = $bdd->query('SELECT ID_reservation FROM locking_code WHERE VALID = \'Y\' AND BUILDING_START LIKE \''.$_GET['building'].'\' AND DATE_BEGIN >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) AND DATE_BEGIN <= UNIX_TIMESTAMP(ADDDATE(CURRENT_DATE(), INTERVAL 1 DAY));');
			$libre = true;
			while (($donnees_2 = $reponse_2->fetch()) && $libre)
			{
				//echo $donnees_2['ID_reservation'];
				$reponse_3 = $bdd->query('SELECT EMAIL FROM reservations WHERE FRAME_NUMBER LIKE \''.$donnees_1['FRAME_NUMBER'].'\' AND ID = '.$donnees_2['ID_reservation'].';');
				if($donnees_3 = $reponse_3->fetch())
				{
					$libre = false;
					//echo $donnees_3['EMAIL']." ";
				}
				else
				{
					$libre = true;
				}
			}
			if($libre)
			{
				//echo "libre <br>";
				echo $donnees_1['FRAME_NUMBER'] . ':';
				$reponse_4 = $bdd->query('SELECT PLACE_IN_BUILDING FROM locking_bikes WHERE MOVING = \'N\' AND FRAME_NUMBER LIKE \''.$donnees_1['FRAME_NUMBER'].'\';');
				if ($donnees_4 = $reponse_4->fetch())
				{
					echo $donnees_4['PLACE_IN_BUILDING'].':'.$donnees['EMAIL'];
				}
			}
			else
			{
				//echo "pas libre <br>";
			}
		}
	}
}
//print_r($bdd->errorInfo());
$reponse->closeCursor();
?>