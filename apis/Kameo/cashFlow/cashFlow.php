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

		if($action=="getContracts"){
			if(get_user_permissions("admin", $token)){
				include "getContracts.php";
			}else
				error_message('403');
		}else if($action=="getCosts"){
			if(get_user_permissions("admin", $token)){
				echo json_encode(execSQL("SELECT * FROM costs WHERE STAANN != 'D' AND (END>CURRENT_TIMESTAMP OR END IS NULL)", array(), false));
				die;
			}else
				error_message('403');
		}else if($action=="getGraphics"){
			if(get_user_permissions(["bikesStock", "admin"], $token)){
				include "getGraphics.php";
			}else
				error_message('403');
		}else
			error_message('405');
		break;
	case 'POST':
	break;
	default:
		error_message('405');
	break;
}
?>
