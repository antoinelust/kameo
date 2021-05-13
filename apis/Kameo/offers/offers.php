<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

require_once __DIR__ .'/../globalfunctions.php';
require_once __DIR__ .'/../authentication.php';

$token = getBearerToken();

log_inputs($token);

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;
		error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
		if($action=="update"){
			execSQL("UPDATE offers SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, TITRE=?, TYPE=?, DESCRIPTION=?, STATUS=?, PROBABILITY=?, MARGIN=?, AMOUNT=?, DATE=?, START=?, END=? WHERE ID=?",
			array('sssssiidsssi', $token, $_POST['title'], $_POST['type'], $_POST['description'], $_POST['status'], $_POST['probability'], $_POST['margin'], $_POST['amount'], $_POST['date'], ($_POST['start'] != '') ? $_POST['start'] : NULL, $_POST['end'], $_POST['ID']), true);
			successMessage("SM0020");
		}
		error_message('405');
	break;
	default:
		error_message('405');
	break;
}
?>
