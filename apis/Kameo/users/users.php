<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

require_once '../globalfunctions.php';
require_once '../authentication.php';
require_once '../connexion.php';

$token = getBearerToken();

log_inputs($token);

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;

		if($action === 'deleteUserAdmin')
		{
			if(get_user_permissions("admin", $token)){
					execSQL("DELETE FROM customer_referential WHERE EMAIL = ?", array('s', $_POST['email']), true);
					execSQL("DELETE FROM customer_bike_access WHERE EMAIL = ?", array('s', $_POST['email']), true);
					execSQL("DELETE FROM customer_building_access WHERE EMAIL = ?", array('s', $_POST['email']), true);
					successMessage("SM0030");
			}
			else
			{
				successMessage("SM0003");
			}
		}else{
			error_message('403');
		}
		break;
	default:
			error_message('405');
		break;
}
$conn->close();
?>
