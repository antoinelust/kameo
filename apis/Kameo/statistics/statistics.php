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

$token = getBearerToken();

log_inputs($token);

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;
		if($action === 'getStatistics'){

			if(get_user_permissions("admin", $token)){
				$resultat=execSQL("SELECT substr(client_orders.CREATION_TIME, 1 ,7) as commandsMonth, SUM(CASE WHEN TYPE='leasing' THEN 1 ELSE 0 END) as leasingOrders, SUM(CASE WHEN TYPE='achat' THEN 1 ELSE 0 END) as sellingOrders FROM client_orders GROUP BY substr(client_orders.CREATION_TIME, 1 ,7)", array(), false);
				$response['leasingOrders']=array_column($resultat, 'leasingOrders');
				$response['sellingOrders']=array_column($resultat, 'sellingOrders');
				$response['commandsMonth']=array_column($resultat, 'commandsMonth');
				$resultat=execSQL("SELECT substr(customer_bikes.CONTRACT_START, 1 ,7) as contractStartMonth, COUNT(1) as contractStartSum FROM `customer_bikes` WHERE customer_bikes.STAANN != 'D' AND customer_bikes.CONTRACT_TYPE='leasing' GROUP BY substr(customer_bikes.CONTRACT_START, 1 ,7)", array(), false);
				$response['contractStartMonth']=array_column($resultat, 'contractStartMonth');
				$response['contractStartSum']=array_column($resultat, 'contractStartSum');
				$resultat=execSQL("SELECT substr(customer_bikes.SELLING_DATE, 1 ,7) as soldBikesMonth, COUNT(1) as soldBikesSum FROM `customer_bikes` WHERE customer_bikes.STAANN != 'D' AND customer_bikes.CONTRACT_TYPE='selling' GROUP BY substr(customer_bikes.SELLING_DATE, 1 ,7)", array(), false);
				$response['soldBikesMonth']=array_column($resultat, 'soldBikesMonth');
				$response['soldBikesSum']=array_column($resultat, 'soldBikesSum');
				$resultat=execSQL("SELECT substr(ESTIMATED_DELIVERY_DATE, 1, 7) as month, COUNT(1) as numberOfBike, round(SUM(BIKE_PRICE)) as cost, round(SUM(bike_catalog.PRICE_HTVA)) as retailPrice  FROM `customer_bikes`, bike_catalog WHERE CONTRACT_TYPE='order' AND customer_bikes.STAANN != 'D' AND customer_bikes.TYPE=bike_catalog.ID GROUP BY substr(ESTIMATED_DELIVERY_DATE, 1, 7)", array(), false);
				$response['deliveryMonth']=array_column($resultat, 'month');
				$response['deliveryNumberOfBike']=array_column($resultat, 'numberOfBike');
				$response['deliveryCost']=array_column($resultat, 'cost');
				$response['deliveryRetailPrice']=array_column($resultat, 'retailPrice');
				echo json_encode($response);
				die;
				log_output($result);
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
?>
