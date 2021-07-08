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
			$result = execSQL("SELECT CODE, BUILDING_START, VALID, COUNT(*) AS cnt FROM locking_code WHERE VALID='Y' GROUP BY CODE, BUILDING_START, VALID HAVING cnt > 1", array(), false);
			if(count($result) > 0){
				error_message('400', "ERROR - Plusieurs memes codes valides en meme temps");
				die;
			}

			$result=execSQL("SELECT * FROM reservations aa WHERE aa.STATUS='Open' AND aa.STAANN != 'D' AND EXISTS (SELECT 1 FROM reservations bb WHERE bb.DATE_START_2 < aa.DATE_END_2 and aa.DATE_START_2 <bb.DATE_START_2 AND bb.STATUS='Open' and bb.STAANN != 'D' and bb.BIKE_ID=aa.BIKE_ID)", array(), false);
			if(count($result) > 0){
				error_message('400', 'ERROR - Une réservation se finit après le début d une autre');
				die;
			}
			$result=execSQL("SELECT order_accessories.ORDER_ID, order_accessories.ORDER_ID, grouped_orders.COMPANY_ID, grouped_orders.EMAIL, companies.COMPANY_NAME, order_accessories.STATUS, order_accessories.TYPE, accessories_catalog.BRAND, accessories_catalog.MODEL FROM order_accessories, accessories_catalog, grouped_orders, companies WHERE STATUS != 'done' AND NOT EXISTS (SELECT 1 FROM accessories_stock WHERE accessories_stock.CONTRACT_TYPE in ('stock', 'order', 'pending_delivery') AND accessories_stock.CATALOG_ID=order_accessories.BRAND) AND order_accessories.BRAND=accessories_catalog.ID AND grouped_orders.ID=order_accessories.ORDER_ID AND companies.ID=grouped_orders.COMPANY_ID", array(), false);
			if(count($result) > 0){
				header("HTTP/1.0 400 Bad Request");
				foreach($result as $accessory){
					echo "Accessoire en commande sans être de stock : Group ID : ".$accessory['ORDER_ID']." - COMPANY : ".$accessory['COMPANY_NAME']." - STATUS : ".$accessory['status']." - Type de contrat : ".$accessory['TYPE']." - Marque : ".$accessory['BRAND']." - Modèle : ".$accessory['MODEL']."\n";
				}
				die;
			}
			echo "success";
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
