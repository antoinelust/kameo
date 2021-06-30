<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

include '../globalfunctions.php';

$token = getBearerToken();
switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;
		if($action === 'listErrors'){
			include '../connexion.php';
			$stmt = $conn->prepare("SELECT CODE, BUILDING_START, VALID, COUNT(*) AS cnt
				FROM locking_code WHERE VALID='Y'
				GROUP BY CODE, BUILDING_START, VALID
				HAVING cnt > 1");
				$stmt->execute();
			$result = $stmt->get_result();
			if($result->num_rows > 0){
				error_message('400', "ERROR - Plusieurs memes codes valides en meme temps");
				die;
			}

			$stmt = $conn->prepare("SELECT * FROM reservations aa WHERE aa.STATUS='Open' AND aa.STAANN != 'D' AND EXISTS (SELECT 1 FROM reservations bb WHERE bb.DATE_START_2 < aa.DATE_END_2 and aa.DATE_START_2 <bb.DATE_START_2 AND bb.STATUS='Open' and bb.STAANN != 'D' and bb.BIKE_ID=aa.BIKE_ID)");
			$stmt->execute();
			$result = $stmt->get_result();
			if($result->num_rows > 0){
				error_message('400', 'ERROR - Une réservation se finit après le début d une autre');
				die;
			}
			die;
		}else
			error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
		error_message('405');
	break;
	default:
			error_message('405');
		break;
}

$conn->close();
?>
