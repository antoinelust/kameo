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
require_once __DIR__ .'/../connexion.php'; 

$token = getBearerToken();

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;
		
		if($action === 'listCatalog'){
			if(get_user_permissions("admin", $token)){
				require_once 'listCatalog.php';
			}else
				error_message('403');
		}else if($action === 'listCategories'){
			if(get_user_permissions("admin", $token)){
				require_once 'listCategories.php';
			}else
				error_message('403');
		}else if($action === 'retrieve'){
			if(get_user_permissions("admin", $token)){
				require_once 'retrieveAccessory.php';
			}else
				error_message('403');
		}else
			error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
		if($action === 'addAccessory'){
			if(get_user_permissions("admin", $token)){
				require_once 'add_catalog_accessory.php';
			}else{
				error_message('403');
            }
        }else{
            error_message('405');
        }
			
	break;
	default:
			error_message('405');
        break;
}

$conn->close();
?>