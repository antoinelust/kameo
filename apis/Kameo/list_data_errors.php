<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

require_once __DIR__ .'/globalfunctions.php';
require_once __DIR__ .'/authentication.php';
require_once __DIR__ .'/connexion.php';

$token = getBearerToken();

log_inputs($token);

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;
		if($action === 'listErrors'){
			include 'connexion.php';
			$stmt = $conn->prepare("SELECT * from client_orders WHERE STATUS='Closed' AND NOT EXISTS (SELECT 1 FROM customer_bike_access WHERE client_orders.EMAIL=customer_bike_access.EMAIL AND TYPE='personnel')");
			$stmt->execute();
			$result = $stmt->get_result();
			if($result->num_rows > 0){
				error_message('400', 'ERROR - Commandes cloturees non liees a un velo personnel');
			}
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
