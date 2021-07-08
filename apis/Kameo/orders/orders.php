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
		if($action == "retrieve"){
			if(get_user_permissions(["fleetManager", "admin"], $token)){
				$ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;
				$response['response']="success";

				$arrs = array();
				$arrs[] = execSQL("SELECT client_orders.ID, grouped_orders.EMAIL as email, client_orders.SIZE as size, client_orders.STATUS as status, client_orders.ESTIMATED_DELIVERY_DATE as estimatedDeliveryDate, client_orders.DELIVERY_ADDRESS as deliveryAddress,
				client_orders.TEST_BOOLEAN as testBoolean, client_orders.TEST_DATE as testDate, client_orders.TEST_ADDRESS as testAddress, client_orders.TEST_STATUS as testStatus, client_orders.TEST_RESULT as testResult, client_orders.LEASING_PRICE as price,
				client_orders.TYPE as type, client_orders.REMARK as comment, client_orders.PORTFOLIO_ID as portfolioID, client_orders.COMMENTS_ADMIN as commentsAdmin, client_orders.BIKE_ID as stockBikeID, bike_catalog.BRAND as brand, bike_catalog.MODEL as model,
				bike_catalog.FRAME_TYPE as frameType, bike_catalog.PRICE_HTVA as priceHTVA FROM client_orders, grouped_orders, bike_catalog WHERE client_orders.ID=? AND client_orders.GROUP_ID=grouped_orders.ID AND bike_catalog.ID=client_orders.PORTFOLIO_ID", array('i', $ID), false)[0];
				if($arrs[0]['email'] != ''){
					$arrs[] = execSQL("SELECT NOM as name, PRENOM as firstname, PHONE as phone FROM customer_referential WHERE EMAIL=?", array('s', $arrs[0]['email']), false)[0];
				}
				$response['order']= array();

				foreach($arrs as $arr) {
						if(is_array($arr)) {
								$response['order']=array_merge($response['order'], $arr);
						}
				}

				$response['order']['accessories']=execSQL("SELECT order_accessories.BRAND as catalogID, order_accessories.PRICE_HTVA, accessories_categories.CATEGORY, accessories_catalog.BRAND, accessories_catalog.BUYING_PRICE, accessories_catalog.MODEL, order_accessories.TYPE, order_accessories.ORDER_ID as orderID
																	FROM order_accessories, accessories_categories, accessories_catalog, client_orders
																	WHERE order_accessories.BRAND=accessories_catalog.ID
																	AND accessories_categories.ID=accessories_catalog.ACCESSORIES_CATEGORIES
																	AND order_accessories.ORDER_ID=client_orders.GROUP_ID AND client_orders.ID=?", array('i', $ID), false);
				echo json_encode($response);
				die;
			}else {
				error_message('403');
			}
		}
		else if($action === 'listOrderable'){
			if(get_user_permissions("admin", $token) && isset($_GET['company'])){
				$company = $_GET['company'];

				$orderable=execSQL("SELECT co.BIKE_ID FROM bike_catalog bc, companies_orderable co, companies c WHERE co.INTERNAL_REFERENCE = c.INTERNAL_REFERENCE AND co.BIKE_ID = bc.ID AND c.COMPANY_NAME = ? ", array('s', $company), false);
				include '../connexion.php';
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
				$response['orders'] = execSQL("SELECT grouped_orders.ID, COMPANY_ID, EMAIL, COMPANY_NAME, (SELECT COUNT(1) FROM order_accessories WHERE order_accessories.ORDER_ID=grouped_orders.ID) as numberAccessories,  (SELECT COUNT(1) FROM order_accessories WHERE order_accessories.ORDER_ID=grouped_orders.ID AND order_accessories.STATUS!='done') as numberAccessoriesNotDelivered, (SELECT COUNT(1) FROM client_orders WHERE client_orders.GROUP_ID=grouped_orders.ID) as numberBikes,  (SELECT COUNT(1) FROM client_orders WHERE client_orders.GROUP_ID=grouped_orders.ID AND client_orders.STATUS!='done') as numberBikesNotDelivered, (SELECT COUNT(1) FROM order_boxes WHERE order_boxes.GROUP_ID=grouped_orders.ID) as numberBoxes,  (SELECT COUNT(1) FROM order_boxes WHERE order_boxes.GROUP_ID=grouped_orders.ID AND order_boxes.STATUS!='done') as numberBoxesNotDelivered FROM `grouped_orders`, companies WHERE grouped_orders.COMPANY_ID=companies.ID", array(), false);
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'retrieveGroupedOrder'){
			if(get_user_permissions("admin", $token)){
				$response = execSQL("SELECT * FROM grouped_orders WHERE ID=?", array('i', $_GET['ID']), false)[0];
				$response['bikes'] = execSQL("SELECT client_orders.ID, client_orders.BIKE_ID, client_orders.STATUS, client_orders.SIZE, client_orders.TYPE, bike_catalog.BRAND, bike_catalog.MODEL, client_orders.LEASING_PRICE, client_orders.ESTIMATED_DELIVERY_DATE FROM client_orders, bike_catalog WHERE GROUP_ID=? AND client_orders.PORTFOLIO_ID=bike_catalog.ID", array('i', $_GET['ID']), false);
				$response['accessories'] = execSQL("SELECT order_accessories.ID, order_accessories.ACCESSORY_ID, accessories_categories.CATEGORY, accessories_catalog.BRAND, accessories_catalog.MODEL, order_accessories.TYPE, order_accessories.PRICE_HTVA, order_accessories.ESTIMATED_DELIVERY_DATE, order_accessories.STATUS FROM order_accessories, accessories_categories, accessories_catalog WHERE order_accessories.BRAND=accessories_catalog.ID AND accessories_catalog.ACCESSORIES_CATEGORIES=accessories_categories.ID AND order_accessories.ORDER_ID=?", array('i', $_GET['ID']), false);
				$response['boxes'] = execSQL("SELECT order_boxes.ID, order_boxes.MODEL, order_boxes.INSTALLATION_PRICE, order_boxes.MONTHLY_PRICE, order_boxes.ESTIMATED_DELIVERY_DATE, order_boxes.STATUS FROM order_boxes WHERE order_boxes.GROUP_ID=?", array('i', $_GET['ID']), false);
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
				include '../connexion.php';
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
				if(isset($_POST['boxModel'])){
					foreach ($_POST['boxModel'] as $key => $model) {
						$intallationPrice=$_POST['boxInstallationPrice'][$key];
						$monthlyPrice=$_POST['boxMonthlyPrice'][$key];
						$status=$_POST['boxStatus'][$key];
						$estimatedDeliveryDate=$_POST['boxEstimatedDeliveryDate'][$key];
						execSQL("INSERT INTO order_boxes (USR_MAJ, GROUP_ID, MODEL, INSTALLATION_PRICE, MONTHLY_PRICE, ESTIMATED_DELIVERY_DATE, STATUS) VALUES(?,?,?,?,?,?,?)",
						array('sisddss', $token, $groupID, $model, $intallationPrice, $monthlyPrice, $estimatedDeliveryDate, $status), true);
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
					foreach ($_POST['accessoryCatalogID'] as $key => $catalogID){
						$contractType=$_POST['accessoryContractType'][$key];
						$amount=$_POST['accessoryAmount'][$key];
						$status=$_POST['accessoryStatus'][$key];
						$estimatedDeliveryDate=$_POST['accessoryEstimatedDeliveryDate'][$key];
						$remark='';
						execSQL("INSERT INTO order_accessories (USR_MAJ, ORDER_ID, BRAND, PRICE_HTVA, TYPE, DESCRIPTION, ESTIMATED_DELIVERY_DATE, STATUS) VALUES(?,?,?,?,?,?,?,?)",
						array('siidssss', $token, $groupID, $catalogID, $amount, $contractType, $remark, $estimatedDeliveryDate, $status), true);
					}
				}
				if(isset($_POST['boxModel'])){
					foreach ($_POST['boxModel'] as $key => $model) {
						$intallationPrice=$_POST['boxInstallationPrice'][$key];
						$monthlyPrice=$_POST['boxMonthlyPrice'][$key];
						$status=$_POST['boxStatus'][$key];
						$estimatedDeliveryDate=$_POST['boxEstimatedDeliveryDate'][$key];
						execSQL("INSERT INTO order_boxes (USR_MAJ, GROUP_ID, MODEL, INSTALLATION_PRICE, MONTHLY_PRICE, ESTIMATED_DELIVERY_DATE, STATUS) VALUES(?,?,?,?,?,?,?)",
						array('sisddss', $token, $groupID, $model, $intallationPrice, $monthlyPrice, $estimatedDeliveryDate, $status), true);
					}
				}
				successMessage("SM0032");
			}else
				error_message('403');
		}else if($action=='add'){
			$email=isset($_POST['email']) ? $_POST['email'] : NULL;
			$company=isset($_POST['company']) ? $_POST['company'] : NULL;
			$mail=isset($_POST['mail']) ? $_POST['mail'] : NULL;
			$ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;
			$status=isset($_POST['status']) ? $_POST['status'] : NULL;
			$portfolioID=isset($_POST['portfolioID']) ? $_POST['portfolioID'] : NULL;
			$size=isset($_POST['size']) ? $_POST['size'] : NULL;
			$testBoolean=isset($_POST['testBoolean']) ? "Y" : "N";
			$testDate=isset($_POST['testDate']) ? ($_POST['testDate'] == "" ? NULL : $_POST['testDate']) : NULL;
			$testStatus=isset($_POST['testStatus']) ? $_POST['testStatus'] : "not started";
			$testAddress=isset($_POST['testAddress']) ? addslashes($_POST['testAddress']) : NULL;
			$testResult=isset($_POST['testResult']) ? addslashes($_POST['testResult']) : NULL;
			$deliveryDate=isset($_POST['deliveryDate']) ? ($_POST['deliveryDate'] == "" ? NULL : $_POST['deliveryDate']) : NULL;
			$deliveryAddress=isset($_POST['deliveryAddress']) ? addslashes($_POST['deliveryAddress']) : NULL;
			$price=isset($_POST['price']) ? $_POST['price'] : NULL;
			$type=isset($_POST['type']) ? $_POST['type'] : "leasing";
			$addAccessory=isset($_POST['glyphicon-plus']) ? ($_POST['glyphicon-plus']) : NULL;
			$deleteAccessory=isset($_POST['glyphicon-minus']) ? ($_POST['glyphicon-minus']) : NULL;
			$accessoriesNumber=isset($_POST['accessoriesNumber']) ? $_POST['accessoriesNumber'] : NULL;
			$categoryAccessory=isset($_POST['accessoryCategory']) ? $_POST['accessoryCategory'] : NULL;
			$typeAccessory=isset($_POST['accessoryAccessory']) ? $_POST['accessoryAccessory'] : NULL;
			$financialTypeAccessory=isset($_POST['financialTypeAccessory']) ? $_POST['financialTypeAccessory'] : NULL;
			$buyingPrice=isset($_POST['buyingPriceAcc']) ? $_POST['buyingPriceAcc'] : NULL;
			$sellingPrice=isset($_POST['sellingPriceAcc']) ? $_POST['sellingPriceAcc'] : NULL;
			$commentsAdmin=isset($_POST['commentsAdmin']) ? $_POST['commentsAdmin'] : NULL;

			if(!get_user_permissions("admin", $token)){
				error_message('403');
			}
			include 'connexion.php';
			$response['response']="success";
			$stmt = $conn->prepare("INSERT INTO client_orders (HEU_MAJ, USR_MAJ, EMAIL, PORTFOLIO_ID, SIZE, STATUS, TEST_BOOLEAN, TEST_DATE, TEST_ADDRESS, TEST_STATUS, TEST_RESULT, ESTIMATED_DELIVERY_DATE, DELIVERY_ADDRESS, LEASING_PRICE, TYPE,COMMENTS_ADMIN, COMPANY) VALUES(CURRENT_TIMESTAMP, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)");
			if ($stmt)
			{
				$stmt->bind_param("ssisssssssssdssi", $token, $mail, $portfolioID, $size, $status, $testBoolean, $testDate, $testAddress, $testStatus, $testResult, $deliveryDate, $deliveryAddress, $price, $type, $commentsAdmin,$company );
				if(!$stmt->execute()){echo json_encode("there was an error....".$conn->error); die;}
				$orderID=$stmt->insert_id;
				$stmt->close();
			} else
				error_message('500', 'Error occured while changing data');

			if(isset($_POST['accessoryCategory']) && isset($_POST['accessoryAccessory'])){
				foreach( $categoryAccessory as $index => $categoryAccessory)
				{
					$category = $categoryAccessory;
					$accessory = $typeAccessory[$index];
					$financialT = $financialTypeAccessory[$index];
					$buyingP = $buyingPrice[$index];
					$sellingP = $sellingPrice[$index];
					execSQL("INSERT INTO order_accessories(BRAND, CATEGORY, BUYING_PRICE, PRICE_HTVA, DESCRIPTION, TYPE, ORDER_ID) VALUES ('$accessory', '$category', '$buyingP', '$sellingP', '//', '$financialT', '$orderID')", array(), true);
				}
			}
			successMessage("SM0003");
			die;
		}else if($action=="update"){
			$email=isset($_POST['email']) ? $_POST['email'] : NULL;
			$company=isset($_POST['company']) ? $_POST['company'] : NULL;
			$mail=isset($_POST['mail']) ? $_POST['mail'] : NULL;
			$ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;
			$status=isset($_POST['status']) ? $_POST['status'] : NULL;
			$portfolioID=isset($_POST['portfolioID']) ? $_POST['portfolioID'] : NULL;
			$size=isset($_POST['size']) ? $_POST['size'] : NULL;
			$testBoolean=isset($_POST['testBoolean']) ? "Y" : "N";
			$testDate=isset($_POST['testDate']) ? ($_POST['testDate'] == "" ? NULL : $_POST['testDate']) : NULL;
			$testStatus=isset($_POST['testStatus']) ? $_POST['testStatus'] : "not started";
			$testAddress=isset($_POST['testAddress']) ? addslashes($_POST['testAddress']) : NULL;
			$testResult=isset($_POST['testResult']) ? addslashes($_POST['testResult']) : NULL;
			$deliveryDate=isset($_POST['deliveryDate']) ? ($_POST['deliveryDate'] == "" ? NULL : $_POST['deliveryDate']) : NULL;
			$deliveryAddress=isset($_POST['deliveryAddress']) ? addslashes($_POST['deliveryAddress']) : NULL;
			$price=isset($_POST['price']) ? $_POST['price'] : NULL;
			$type=isset($_POST['type']) ? $_POST['type'] : "leasing";
			$addAccessory=isset($_POST['glyphicon-plus']) ? ($_POST['glyphicon-plus']) : NULL;
			$deleteAccessory=isset($_POST['glyphicon-minus']) ? ($_POST['glyphicon-minus']) : NULL;
			$accessoriesNumber=isset($_POST['accessoriesNumber']) ? $_POST['accessoriesNumber'] : NULL;
			$categoryAccessory=isset($_POST['accessoryCategory']) ? $_POST['accessoryCategory'] : NULL;
			$typeAccessory=isset($_POST['accessoryAccessory']) ? $_POST['accessoryAccessory'] : NULL;
			$financialTypeAccessory=isset($_POST['financialTypeAccessory']) ? $_POST['financialTypeAccessory'] : NULL;
			$buyingPrice=isset($_POST['buyingPriceAcc']) ? $_POST['buyingPriceAcc'] : NULL;
			$sellingPrice=isset($_POST['sellingPriceAcc']) ? $_POST['sellingPriceAcc'] : NULL;
			$commentsAdmin=isset($_POST['commentsAdmin']) ? $_POST['commentsAdmin'] : NULL;

			if(get_user_permissions("admin", $token)){
				execSQL("UPDATE client_orders  SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, STATUS=?, PORTFOLIO_ID=?, SIZE=?, DELIVERY_ADDRESS=?, LEASING_PRICE=?, TYPE=?, ESTIMATED_DELIVERY_DATE=?, COMMENTS_ADMIN=?, TEST_STATUS=? WHERE ID=?", array("ssissdssssi", $token, $status, $portfolioID, $size, $deliveryAddress, $price, $type, $deliveryDate,$commentsAdmin, $testStatus, $ID), true);
				if(isset($_POST['linkOrderToBike'])){
					execSQL("UPDATE client_orders SET BIKE_ID=? WHERE ID=?", array('ii', $_POST['linkOrderToBike'], $ID), true);
				}

				if($testBoolean=="Y"){
					include 'connexion.php';
					$stmt = $conn->prepare("UPDATE client_orders SET TEST_BOOLEAN='Y', HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, TEST_DATE=?, TEST_ADDRESS=?, TEST_RESULT=? WHERE ID=?");
					if ($stmt)
					{
						$stmt->bind_param("ssssi", $token, $testDate, $testAddress, $testResult, $ID);
						if(!$stmt->execute()){echo "there was an error....".$conn->error;}
						$response['response']="success";
						$stmt->close();
					} else
					error_message('500', 'Error occured while changing data');
				}

				if(isset($_POST['accessoryCategory']) && isset($_POST['accessoryAccessory']))
				{
					include 'connexion.php';
					foreach( $categoryAccessory as $index => $categoryAccessory)
					{
						$category = $categoryAccessory;
						$accessory = $typeAccessory[$index];
						$financialT = $financialTypeAccessory[$index];
						$buyingP = $buyingPrice[$index];
						$sellingP = $sellingPrice[$index];
						$sql2 = "INSERT INTO order_accessories(BRAND, CATEGORY, BUYING_PRICE, PRICE_HTVA, DESCRIPTION, TYPE, ORDER_ID) VALUES ('$accessory', '$category', '$buyingP', '$sellingP', '//', '$financialT', '$ID')";
						if ($conn->query($sql2) === FALSE) {
							$response = array ('response'=>'error', 'message'=> $conn->error);
							echo json_encode($response);
							die;
						}
					}
					$conn->close();
				}
				successMessage("SM0003");
			}
		}else if($action=='confirmOrder'){
			if(get_user_permissions("admin", $token)){
				if($_POST['itemType']=="bike"){
					$result=execSQL("SELECT * FROM client_orders WHERE ID=?", array('i', $_POST['itemID']), false)[0];
					$bikeID=$result['BIKE_ID'];
					$leasingAmount=$result['LEASING_PRICE'];
					$groupID=$result['GROUP_ID'];
					$result=execSQL("SELECT * FROM grouped_orders WHERE ID = ?", array('i', $groupID), false)[0];
					$companyID=$result['COMPANY_ID'];
					$email=$result['EMAIL'];
					$internalReference=execSQL('SELECT INTERNAL_REFERENCE FROM companies WHERE ID=?', array('i', $companyID), false)[0]['INTERNAL_REFERENCE'];
					execSQL('UPDATE client_orders SET USR_MAJ=?, status="done", ESTIMATED_DELIVERY_DATE=? WHERE ID=?', array('ssi', $token, $_POST['deliveryDate'], $_POST['itemID']), true);
					execSQL('UPDATE customer_bikes SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY=?, BILLING_TYPE="monthly", INSURANCE="Y", AUTOMATIC_BILLING="Y", CONTRACT_TYPE="leasing", CONTRACT_START=?, CONTRACT_END=?, LEASING_PRICE=? WHERE ID=?', array('ssssdi', $token, $internalReference, $_POST['contractStart'], $_POST['contractEnd'], $leasingAmount, $bikeID), true);
					if($email != ""){
						$result=execSQL("SELECT * FROM customer_bike_access WHERE BIKE_ID=? and EMAIL=?", array('is', $bikeID, $email), false);
						if(count($result)>0){
							execSQL("UPDATE customer_bike_access SET STAANN='' WHERE BIKE_ID=? AND EMAIL=?", array('is', $bikeID, $email), true);
						}else{
							execSQL("INSERT INTO customer_bike_access (TIMESTAMP, USR_MAJ, BIKE_ID, EMAIL, TYPE, STAANN) VALUES (CURRENT_TIMESTAMP, ?, ?, ?, 'personnel', '')", array('sis', $token, $bikeID, $email), true);
						}
					}
					$response['response']="success";
					$response['message']="Commande confirmée et vélo assigné au client";
					echo json_encode($response);
					die;
				}
				else if($_POST['itemType']=="accessory"){
					$result=execSQL("SELECT * FROM order_accessories WHERE ID=?", array('i', $_POST['itemID']), false)[0];
					$accessoryID=$result['ACCESSORY_ID'];
					$leasingAmount=$result['PRICE_HTVA'];
					$groupID=$result['ORDER_ID'];
					$result=execSQL("SELECT * FROM grouped_orders WHERE ID = ?", array('i', $groupID), false)[0];
					$companyID=$result['COMPANY_ID'];
					$email=$result['EMAIL'];
					$internalReference=execSQL('SELECT INTERNAL_REFERENCE FROM companies WHERE ID=?', array('i', $companyID), false)[0]['INTERNAL_REFERENCE'];
					execSQL('UPDATE order_accessories SET USR_MAJ=?, status="done", ESTIMATED_DELIVERY_DATE=? WHERE ID=?', array('ssi', $token, $_POST['deliveryDate'], $_POST['itemID']), true);
					execSQL('UPDATE accessories_stock SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_ID=?, ORDER_ID=?, CONTRACT_TYPE="leasing", CONTRACT_START=?, CONTRACT_END=?, CONTRACT_AMOUNT=?, USER_EMAIL=? WHERE ID=?', array('siissdsi', $token, $companyID, $groupID, $_POST['contractStart'], $_POST['contractEnd'], $leasingAmount, $email, $accessoryID), true);
					$response['response']="success";
					$response['message']="Commande confirmée et accessoire assigné au client";
					echo json_encode($response);
					die;
				}
			}else {
				error_message('403');
			}

		}else
			error_message('405');
	break;
	default:
			error_message('405');
		break;
}
?>
