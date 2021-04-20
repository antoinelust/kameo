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
include __DIR__ .'/../connexion.php';

$token = getBearerToken();

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
	$action=isset($_GET['action']) ? $_GET['action'] : NULL;
	if($action === 'list'){
		if(get_user_permissions("espaceCollaboratif", $token)){
			$response=execSQL("SELECT aa.ID, bb.BRAND, bb.MODEL, aa.CONTRACT_START, aa.CONTRACT_END, aa.LEASING_PRICE, cc.COMPANY_NAME FROM customer_bikes aa, bike_catalog bb, companies cc WHERE aa.TYPE=bb.ID AND aa.COMPANY IN (SELECT INTERNAL_REFERENCE FROM companies WHERE AQUISITION = (SELECT COMPANY FROM customer_referential WHERE TOKEN=?)) AND aa.STAANN != 'D' AND aa.COMPANY=cc.INTERNAL_REFERENCE", array('s', $token), false);
			if(is_null($response)){
				$response=array();
			}
			echo json_encode($response);
			die;
		}else{
			error_message('403');
		}
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
