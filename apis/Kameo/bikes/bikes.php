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

log_inputs($token);

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;

		if($action === 'listBikeBills'){
			if(get_user_permissions(["admin", "bikesStock"], $token)){
				require_once 'listBikeBills.php';
			}else
				error_message('403');
		}else if($action === 'listOfBikesNotLinked'){
				if(get_user_permissions(["admin", "bikesStock"], $token)){
					$response=execSQL("SELECT customer_bikes.ID, FRAME_NUMBER, CONTRACT_TYPE, CONTRACT_START, CONTRACT_END, COMPANY, bike_catalog.BRAND, bike_catalog.MODEL FROM customer_bikes, bike_catalog WHERE bike_catalog.ID=customer_bikes.TYPE AND CONTRACT_TYPE in ('selling', 'leasing', 'renting', 'pending_delivery', 'stock') and customer_bikes.STAANN != 'D' AND NOT EXISTS (SELECT 1 from bills_catalog_bikes_link WHERE bills_catalog_bikes_link.BIKE_ID = customer_bikes.ID)", array(), false);
					echo json_encode($response);
					die;
				}else
					error_message('403');
		}else if($action === 'summaryBikesLinked'){
				if(get_user_permissions(["admin", "bikesStock"], $token)){
					$response=execSQL("SELECT customer_bikes.ID, customer_bikes.CONTRACT_TYPE, customer_bikes.CONTRACT_START, customer_bikes.CONTRACT_END, customer_bikes.COMPANY, customer_bikes.SIZE, customer_bikes.BIKE_BUYING_DATE, customer_bikes.BIKE_PRICE, bills_catalog_bikes_link.BUYING_PRICE, customer_bikes.DELIVERY_DATE, bike_catalog.BRAND, bike_catalog.MODEL FROM customer_bikes, bike_catalog, bills_catalog_bikes_link WHERE bike_catalog.ID=customer_bikes.TYPE AND customer_bikes.ID=bills_catalog_bikes_link.BIKE_ID AND bills_catalog_bikes_link.FACTURE_ID=?", array('i', $_GET['factureID']), false);
					echo json_encode($response);
					die;
				}else
					error_message('403');
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
