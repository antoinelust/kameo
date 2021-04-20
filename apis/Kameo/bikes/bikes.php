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
		if($action=="retrieve"){
			if(get_user_permissions(["admin", "fleetManager"], $token)){
				include "getBikeDetails.php";
			}else
				error_message('403');
		}else if($action=="getPersonnalBike"){
			include 'getPersonnalBike.php';
		}else if($action=="getListofBills"){
			if(get_user_permissions(["admin", "bikesStock"], $token)){
				include 'getListofBills.php';
			}else
				error_message('403');
		}else if($action=="getPersonnalBikeActions"){
			include 'getPersonnalBikeActions.php';
		}else if($action=="list"){
			$admin = isset($_GET['admin']) ? $_GET['admin'] : NULL;
			if ($admin != "Y") {
				if(get_user_permissions(["admin", "fleetManager"], $token)){
					include "listBikesFleetManager.php";
				}else
					error_message('403');
			}else{
				if(get_user_permissions("admin", $token)){
					include "listBikesAdmin.php";
				}else
					error_message('403');
			}
		}else if($action === 'listBikeBills'){
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
		}else if($action === 'getAddress'){
				if(get_user_permissions("admin", $token)){
					$response=execSQL("SELECT ADDRESS FROM customer_bikes WHERE ID = ?", array('i', $_GET['ID']), false)[0]['ADDRESS'];
					if($response=="" || $response == null){
						$response=execSQL("SELECT CONCAT(STREET, ', ', ZIP_CODE, ' ', TOWN) as ADDRESS FROM companies, customer_bikes WHERE customer_bikes.ID=? AND customer_bikes.COMPANY=companies.INTERNAL_REFERENCE", array('i', $_GET['ID']), false)[0]['ADDRESS'];
					}
					echo json_encode($response);
					die;
				}else
					error_message('403');
		}else if($action === 'listPendingDeliveryBikes'){
				if(get_user_permissions("admin", $token)){
					$response=execSQL("SELECT customer_bikes.ID as id, BRAND as brand, bike_catalog.MODEL as model, bike_catalog.BUYING_PRICE as buyingPrice, bike_catalog.PRICE_HTVA as priceHTVA FROM customer_bikes, bike_catalog WHERE customer_bikes.TYPE=bike_catalog.ID AND COMPANY=? AND customer_bikes.CONTRACT_TYPE='pending_delivery'", array('s', $_GET['company']), false);
					echo json_encode($response);
					die;
				}else
					error_message('403');
		}else
			error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
		if($action === 'add'){
			if(get_user_permissions("admin", $token)){
				require_once 'add_bike.php';
			}else
				error_message('403');
		}else if($action === 'update'){
			if(get_user_permissions("admin", $token)){
				require_once 'updateBike.php';
			}else
				error_message('403');
		}else if($action === 'updateBikeStatus'){
			if(get_user_permissions("fleetManager", $token)){
				require_once 'updateBikeStatus.php';
			}else
				error_message('403');
		}else{
			error_message('405');
		}
	break;
	default:
		error_message('405');
	break;
}
?>
