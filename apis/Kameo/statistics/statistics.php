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
			if(get_user_permissions(['bikesStock', "admin"], $token)){
				$resultat=execSQL("SELECT substr(client_orders.CREATION_TIME, 1 ,7) as commandsMonth,
				SUM(CASE WHEN TYPE='leasing' THEN 1 ELSE 0 END) as leasingOrders,
				SUM(CASE WHEN TYPE='achat' THEN 1 ELSE 0 END) as sellingOrders,
				(SELECT COUNT(order_boxes.ID) FROM order_boxes WHERE substr(order_boxes.CREATION_TIME, 1 ,7)=substr(client_orders.CREATION_TIME, 1 ,7)) as boxesOrders,
				ROUND(SUM(CASE WHEN TYPE='achat' THEN (client_orders.LEASING_PRICE-bike_catalog.BUYING_PRICE) ELSE 0 END)) as sellingMargin,
				ROUND(SUM(CASE WHEN TYPE='achat' THEN (bike_catalog.BUYING_PRICE) ELSE 0 END)) as sellingCost,
        ROUND(SUM(CASE WHEN TYPE='leasing' THEN ((client_orders.LEASING_PRICE*36)+0.16*bike_catalog.PRICE_HTVA-bike_catalog.BUYING_PRICE-3*84-4*100) ELSE 0 END)) as leasingMargin,
        ROUND(SUM(CASE WHEN TYPE='leasing' THEN (bike_catalog.BUYING_PRICE+3*84+4*100) ELSE 0 END)) as leasingCost,
        (SELECT COALESCE(ROUND(SUM(order_boxes.MONTHLY_PRICE*36+order_boxes.INSTALLATION_PRICE-700)), 0) FROM order_boxes WHERE substr(order_boxes.CREATION_TIME, 1 ,7)=substr(client_orders.CREATION_TIME, 1 ,7)) as boxesMargin,
        (SELECT COUNT(order_boxes.ID)*700 FROM order_boxes WHERE substr(order_boxes.CREATION_TIME, 1 ,7)=substr(client_orders.CREATION_TIME, 1 ,7)) as boxesCost
        FROM client_orders, bike_catalog WHERE client_orders.PORTFOLIO_ID = bike_catalog.ID GROUP BY substr(client_orders.CREATION_TIME, 1 ,7)", array(), false);
				$response['leasingOrders']=array_column($resultat, 'leasingOrders');
				$response['leasingCost']=array_column($resultat, 'leasingCost');
				$response['boxesOrders']=array_column($resultat, 'boxesOrders');
				$response['leasingMargin']=array_column($resultat, 'leasingMargin');
				$response['sellingOrders']=array_column($resultat, 'sellingOrders');
				$response['sellingMargin']=array_column($resultat, 'sellingMargin');
				$response['sellingCost']=array_column($resultat, 'sellingCost');
				$response['boxesCost']=array_column($resultat, 'boxesCost');
				$response['boxesMargin']=array_column($resultat, 'boxesMargin');
				$response['commandsMonth']=array_column($resultat, 'commandsMonth');
				$resultat=execSQL("SELECT substr(customer_bikes.CONTRACT_START, 1 ,7) as contractStartMonth, COUNT(1) as contractStartSum FROM `customer_bikes` WHERE customer_bikes.STAANN != 'D' AND customer_bikes.CONTRACT_TYPE='leasing' GROUP BY substr(customer_bikes.CONTRACT_START, 1 ,7)", array(), false);
				$response['contractStartMonth']=array_column($resultat, 'contractStartMonth');
				$response['contractStartSum']=array_column($resultat, 'contractStartSum');
				$resultat=execSQL("SELECT substr(customer_bikes.SELLING_DATE, 1 ,7) as soldBikesMonth, COUNT(1) as soldBikesSum FROM `customer_bikes` WHERE customer_bikes.STAANN != 'D' AND customer_bikes.CONTRACT_TYPE='selling' AND customer_bikes.SOLD_PRICE != '0' GROUP BY substr(customer_bikes.SELLING_DATE, 1 ,7)", array(), false);
				$response['soldBikesMonth']=array_column($resultat, 'soldBikesMonth');
				$response['soldBikesSum']=array_column($resultat, 'soldBikesSum');
				$resultat=execSQL("SELECT substr(ESTIMATED_DELIVERY_DATE, 1, 7) as month, COUNT(1) as numberOfBike, round(SUM(BIKE_PRICE)) as cost, round(SUM(bike_catalog.PRICE_HTVA - BIKE_PRICE)) as retailMargin  FROM `customer_bikes`, bike_catalog WHERE CONTRACT_TYPE='order' AND customer_bikes.STAANN != 'D' AND customer_bikes.TYPE=bike_catalog.ID GROUP BY substr(ESTIMATED_DELIVERY_DATE, 1, 7)", array(), false);
				$response['deliveryMonth']=array_column($resultat, 'month');
				$response['deliveryNumberOfBike']=array_column($resultat, 'numberOfBike');
				$response['deliveryCost']=array_column($resultat, 'cost');
				$response['retailMargin']=array_column($resultat, 'retailMargin');
				$resultat=execSQL("SELECT bike_catalog.BRAND, COUNT(1) as somme FROM customer_bikes, bike_catalog WHERE customer_bikes.CONTRACT_TYPE='stock' AND customer_bikes.TYPE != 'D' AND customer_bikes.TYPE=bike_catalog.ID GROUP BY bike_catalog.BRAND", array(), false);
				$response['stockByBrandLabel']=array_column($resultat, 'BRAND');
				$response['stockByBrandData']=array_column($resultat, 'somme');
				$resultat=execSQL("SELECT bike_catalog.UTILISATION, COUNT(1) as somme FROM customer_bikes, bike_catalog WHERE customer_bikes.CONTRACT_TYPE='stock' AND customer_bikes.TYPE != 'D' AND customer_bikes.TYPE=bike_catalog.ID GROUP BY bike_catalog.UTILISATION", array(), false);
				$response['stockByUtilisationLabel']=array_column($resultat, 'UTILISATION');
				$response['stockByUtilisationData']=array_column($resultat, 'somme');
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
