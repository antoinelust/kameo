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
		if($action === 'listOrderable'){
			if(get_user_permissions("admin", $token) && isset($_GET['company'])){
				$stmt = $conn->prepare("SELECT co.BIKE_ID FROM bike_catalog bc, companies_orderable co, companies c WHERE co.INTERNAL_REFERENCE = c.INTERNAL_REFERENCE AND co.BIKE_ID = bc.ID AND c.COMPANY_NAME = ? ");
				$company = urldecode($_GET['company']);
				$stmt->bind_param("s", $company);
				$stmt->execute();
				$orderable = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
				$stmt->close();
				if ($portfolio = $conn->query("SELECT ID, BRAND, MODEL FROM bike_catalog WHERE DISPLAY='Y' AND STAANN!='D'")) {
					$result = array();
					while ($bike = mysqli_fetch_object($portfolio))
					{
						if (array_search($bike->ID, array_column($orderable, 'BIKE_ID')) !== false)
							$bike->ORDERABLE = 'true';
						else
							$bike->ORDERABLE = 'false';
						$result[] = $bike;
					}
					echo json_encode($result);
					log_output($result);
				}else
					errorMessage("ES0012");
			}else if(get_user_permissions(["order", "admin"], $token)){

        $marginBike=0.7;
        $marginOther=0.3;
        $leasingDuration=36;
				$company_reference=execSQL("SELECT COMPANY from customer_referential WHERE TOKEN=?", array('s', $token), false)[0]['COMPANY'];
				$response = array();
				$response['bike']=execSQL("SELECT BIKE_ID from companies_orderable WHERE INTERNAL_REFERENCE = ? ORDER BY BIKE_ID", array('s', $company_reference), false);
				if(is_null($response['bike'])){
					$response['bike']=array();
				}
				$response['response'] = "success";
				$response['company'] = $company_reference;
				$reponse=execSQL("SELECT DISCOUNT, REMAINING_PRICE_INCLUDED_IN_LEASING, CAFETERIA_TYPES, TVA_INCLUDED from conditions WHERE COMPANY=? AND NAME='generic'", array('s', $company_reference), false)[0];
				$response['discount']=$reponse['DISCOUNT'];
				$response['cafeteriaTypes']=explode(',', $reponse['CAFETERIA_TYPES']);
				$response['tvaIncluded']=$reponse['TVA_INCLUDED'];
				$response['remainingPriceIncludedInLeasing']=$reponse['REMAINING_PRICE_INCLUDED_IN_LEASING'];
				echo json_encode($response);
				log_output($response);
			}else
				error_message('403');
		}else if($action === 'listOrderableAccessories'){
			if(get_user_permissions("order", $token)){
				$response['response']="success";
				$response['accessories'] = execSQL("SELECT companies_orderable_accessories.* FROM companies_orderable_accessories, companies, customer_referential WHERE TOKEN=? AND customer_referential.COMPANY=companies.INTERNAL_REFERENCE AND companies.ID=companies_orderable_accessories.COMPANY_ID", array("s", $token), false);
				if(is_null($response['accessories'])){
					$response['accessories']=array();
				}
				$conditions = execSQL("SELECT DISCOUNT, REMAINING_PRICE_INCLUDED_IN_LEASING, CAFETERIA_TYPES, TVA_INCLUDED from conditions, customer_referential WHERE conditions.COMPANY=customer_referential.COMPANY AND NAME='generic' and customer_referential.TOKEN=?", array('s', $token), false)[0];
				$response['discount']=$conditions['DISCOUNT'];
				$response['cafeteriaTypes']=explode(',', $conditions['CAFETERIA_TYPES']);
				$response['tvaIncluded']=$conditions['TVA_INCLUDED'];
				$response['remainingPriceIncludedInLeasing']=$conditions['REMAINING_PRICE_INCLUDED_IN_LEASING'];
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'listGroupedOrders'){
			if(get_user_permissions("admin", $token)){
				$response['orders'] = execSQL("SELECT grouped_orders.ID, COMPANY_ID, EMAIL, COMPANY_NAME, (SELECT COUNT(1) FROM order_accessories WHERE order_accessories.ORDER_ID=grouped_orders.ID) as numberAccessories,  (SELECT COUNT(1) FROM order_accessories WHERE order_accessories.ORDER_ID=grouped_orders.ID AND order_accessories.STATUS!='done') as numberAccessoriesNotDelivered, (SELECT COUNT(1) FROM client_orders WHERE client_orders.GROUP_ID=grouped_orders.ID) as numberBikes,  (SELECT COUNT(1) FROM client_orders WHERE client_orders.GROUP_ID=grouped_orders.ID AND client_orders.STATUS!='done') as numberBikesNotDelivered FROM `grouped_orders`, companies WHERE grouped_orders.COMPANY_ID=companies.ID", array(), false);
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'retrieveGroupedOrder'){
			if(get_user_permissions("admin", $token)){
				$response = execSQL("SELECT * FROM grouped_orders WHERE ID=?", array('i', $_GET['ID']), false)[0];
				$response['bikes'] = execSQL("SELECT client_orders.ID, client_orders.STATUS, client_orders.SIZE, client_orders.TYPE, bike_catalog.BRAND, bike_catalog.MODEL, client_orders.LEASING_PRICE, client_orders.ESTIMATED_DELIVERY_DATE FROM client_orders, bike_catalog WHERE GROUP_ID=? AND client_orders.PORTFOLIO_ID=bike_catalog.ID", array('i', $_GET['ID']), false);
				$response['accessories'] = execSQL("SELECT order_accessories.ID, accessories_categories.CATEGORY, accessories_catalog.BRAND, accessories_catalog.MODEL, order_accessories.TYPE, order_accessories.PRICE_HTVA, order_accessories.ESTIMATED_DELIVERY_DATE, order_accessories.STATUS FROM order_accessories, accessories_categories, accessories_catalog WHERE order_accessories.BRAND=accessories_catalog.ID AND accessories_catalog.ACCESSORIES_CATEGORIES=accessories_categories.ID AND order_accessories.ORDER_ID=?", array('i', $_GET['ID']), false);
				if(is_null($response['bikes'])){
					$response['bikes']=array();
				}
				if(is_null($response['accessories'])){
					$response['accessories']=array();
				}
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else
			error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
		if($action === 'updateOrderable')
		{
			if(get_user_permissions("admin", $token) && isset($_POST['company']) && isset($_POST['cafeteria'])){
				$stmt = $conn->prepare("SELECT INTERNAL_REFERENCE FROM companies WHERE COMPANY_NAME=?");
				$stmt->bind_param("s", $_POST['company']);
				$stmt->execute();
				$company_reference = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['INTERNAL_REFERENCE'];
				$conditionID = isset($_POST['conditionID']) ? $_POST['conditionID'] : NULL;
				$cafeteria = ($_POST['cafeteria'] === "true") ? "Y" : "N";
				$remainingPriceIncluded = ($_POST['includedLeasingPrice'] === "true") ? "Y" : "N";
				$tva = ($_POST['tva'] === "true") ? "Y" : "N";
				$types = isset($_POST['types']) ? $_POST['types'] : NULL;
				$discount=isset($_POST['discount']) ? $_POST['discount'] : NULL;
				$sellingPorcentage=isset($_POST['sellingPorcentage']) ? $_POST['sellingPorcentage'] : NULL;
				$stmt->close();
				$stmt = $conn->prepare("UPDATE conditions SET HEU_MAJ=CURRENT_TIMESTAMP, CAFETARIA=?, DISCOUNT=?, CAFETERIA_TYPES=?, TVA_INCLUDED=?, REMAINING_PRICE_INCLUDED_IN_LEASING	=?, SELLING_PRICE=? WHERE ID = ?");
				$stmt->bind_param("sdssddi", $cafeteria, $discount, $types, $tva, $remainingPriceIncluded, $sellingPorcentage, $conditionID);
				$stmt->execute();
				$stmt->close();
				if ($_POST['cafeteria'] === "true")
				{
					$stmt = $conn->prepare("SELECT co.BIKE_ID FROM bike_catalog bc, companies_orderable co WHERE co.INTERNAL_REFERENCE = ? AND co.BIKE_ID = bc.ID");
					$stmt->bind_param("s", $company_reference);
					$stmt->execute();
					$orderable = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
					$stmt->close();
					$orderable = array_column($orderable, 'BIKE_ID');
					$checked = isset($_POST['bikesOrderable']) ? $_POST['bikesOrderable'] : Array();
					$to_insert = array_diff($checked, $orderable);
					$to_delete = array_diff($orderable, $checked);
					$stmt_insert = $conn->prepare("INSERT INTO companies_orderable (ID, INTERNAL_REFERENCE, BIKE_ID) VALUES (null, ?, ?)");
					$stmt_insert->bind_param("ss", $company_reference, $insert_item);
					$stmt_delete = $conn->prepare("DELETE FROM companies_orderable WHERE INTERNAL_REFERENCE = ? AND BIKE_ID = ?");
					$stmt_delete->bind_param("ss", $company_reference, $delete_item);
					$conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
					foreach ($to_insert as $insert_item)
						if (!$stmt_insert->execute())
							errorMessage("ES0012");
					foreach ($to_delete as $delete_item)
						if (!$stmt_delete->execute())
							errorMessage("ES0012");
					$stmt_insert->close();
					$stmt_delete->close();
					if ($conn->commit())
						successMessage("SM0003");
				}
				else
				{
					successMessage("SM0003");
				}
			}else
				error_message('403');
		}else if($action === "addGroupedOrder"){
			if(get_user_permissions("admin", $token)){
				$companyID=$_POST['company'];
				$email=isset($_POST['email']) ? $_POST['email'] : '';
				$groupID=execSQL("INSERT INTO grouped_orders (USR_MAJ, COMPANY_ID, EMAIL) VALUES (?,?,?)", array('sis', $token, $companyID, $email), true);

				$test='N';
				$remark='';
				if(isset($_POST['catalogID'])){
					foreach ($_POST['catalogID'] as $key => $catalogID) {
						$size=$_POST['size'][$key];
						$contractType=$_POST['contractType'][$key];
						$amount=$_POST['bikeAmount'][$key];
						$status=$_POST['status'][$key];
						$estimatedDeliveryDate=$_POST['estimatedDeliveryDate'][$key];
						execSQL("INSERT INTO client_orders (USR_MAJ, GROUP_ID, PORTFOLIO_ID, SIZE, REMARK, STATUS, LEASING_PRICE, TYPE, TEST_BOOLEAN, ESTIMATED_DELIVERY_DATE) VALUES(?,?,?,?,?,?,?,?,?,?)",
						array('siisssdsss', $token, $groupID, $catalogID, $size, $remark, $status, $amount, $contractType, $test, $estimatedDeliveryDate), true);
					}
				}
				if(isset($_POST['accessoryCatalogID'])){
					foreach ($_POST['accessoryCatalogID'] as $key => $catalogID) {
						$contractType=$_POST['accessoryContractType'][$key];
						$amount=$_POST['accessoryAmount'][$key];
						$status=$_POST['accessoryStatus'][$key];
						$estimatedDeliveryDate=$_POST['accessoryEstimatedDeliveryDate'][$key];
						execSQL("INSERT INTO order_accessories (USR_MAJ, ORDER_ID, BRAND, PRICE_HTVA, TYPE, DESCRIPTION, ESTIMATED_DELIVERY_DATE, STATUS) VALUES(?,?,?,?,?,?,?,?)",
						array('siidssss', $token, $groupID, $catalogID, $amount, $contractType, $remark, $estimatedDeliveryDate, $status), true);
					}
				}

				successMessage("SM0032");
			}else
				error_message('403');
		}else if($action === "deleteBikeOrder"){
			execSQL("DELETE FROM client_orders WHERE ID=?", array('i', $_POST['ID']), true);
			$response['response']="success";
			echo json_encode($response);
			die;
		}else if($action === "deleteAccessoryOrder"){
			execSQL("DELETE FROM order_accessories WHERE ID=?", array('i', $_POST['ID']), true);
			$response['response']="success";
			echo json_encode($response);
			die;
		}else if($action === "updateGroupedOrder"){
			if(get_user_permissions("admin", $token)){
				$groupID=$_POST['ID'];
				$email=isset($_POST['email']) ? $_POST['email'] : '';

				execSQL("UPDATE grouped_orders SET COMPANY_ID=?, EMAIL=?, HEU_MAJ=CURRENT_TIMESTAMP WHERE ID=?", array('isi', $_POST['company'], $email, $groupID), true);
				if(isset($_POST['catalogID'])){
					foreach ($_POST['catalogID'] as $key => $catalogID) {
						$size=$_POST['size'][$key];
						$contractType=$_POST['contractType'][$key];
						$amount=$_POST['bikeAmount'][$key];
						$status=$_POST['status'][$key];
						$estimatedDeliveryDate=$_POST['estimatedDeliveryDate'][$key];
						$test='N';
						$remark='';
						execSQL("INSERT INTO client_orders (USR_MAJ, GROUP_ID, PORTFOLIO_ID, SIZE, REMARK, STATUS, LEASING_PRICE, TYPE, TEST_BOOLEAN, ESTIMATED_DELIVERY_DATE, COMMENTS_ADMIN) VALUES(?,?,?,?,?,?,?,?,?,?,?)",
						array('siisssdssss', $token, $groupID, $catalogID, $size, $remark, $status, $amount, $contractType, $test, $estimatedDeliveryDate, $remark), true);
					}
				}
				if(isset($_POST['accessoryCatalogID'])){
					foreach ($_POST['accessoryCatalogID'] as $key => $catalogID) {
						$contractType=$_POST['accessoryContractType'][$key];
						$amount=$_POST['accessoryAmount'][$key];
						$status=$_POST['accessoryStatus'][$key];
						$estimatedDeliveryDate=$_POST['accessoryEstimatedDeliveryDate'][$key];
						$remark='';
						execSQL("INSERT INTO order_accessories (USR_MAJ, ORDER_ID, BRAND, PRICE_HTVA, TYPE, DESCRIPTION, ESTIMATED_DELIVERY_DATE, STATUS) VALUES(?,?,?,?,?,?,?,?)",
						array('siidssss', $token, $groupID, $catalogID, $amount, $contractType, $remark, $estimatedDeliveryDate, $status), true);
					}
				}
				successMessage("SM0032");
			}else
				error_message('403');
		}else
			error_message('405');

	break;
	default:
			error_message('405');
		break;
}
?>
