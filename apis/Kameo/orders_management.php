<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

include_once 'globalfunctions.php';
require_once 'authentication.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/include/lang_management.php'; //french by defaut, as many files as wanted can be added to the array

$token = getBearerToken();
log_inputs($token);

if(isset($_POST['action'])){

	$action=isset($_POST['action']) ? $_POST['action'] : NULL;
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

	if($action=='add'){
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
	}


	else if($action=='update'){

		$linkOfferToBike=isset($_POST['linkOfferToBike']) ? $_POST['linkOfferToBike'] : NULL;
		execSQL("UPDATE client_orders  SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, STATUS=?, PORTFOLIO_ID=?, SIZE=?, DELIVERY_ADDRESS=?, LEASING_PRICE=?, TYPE=?, ESTIMATED_DELIVERY_DATE=?, DELIVERY_ADDRESS=?, COMMENTS_ADMIN=?, TEST_STATUS=?, BIKE_ID=? WHERE ID=?", array("ssissdsssssii", $token, $status, $portfolioID, $size, $deliveryAddress, $price, $type, $deliveryDate, $deliveryAddress,$commentsAdmin, $testStatus, $linkOfferToBike, $ID), true);
////////////////////////////////////////////////

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
	}else if($action=="confirmCommand"){
		if(!get_user_permissions("fleetManager", $token)){
			error_message('403');
		}
		include 'connexion.php';
		$ID=isset($_POST['ID']) ? $conn->real_escape_string($_POST['ID']) : NULL;
		$stmt = $conn->prepare("UPDATE client_orders SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ= ?, STATUS='confirmed' WHERE ID=?");

		if (!$stmt->bind_param("si", $token, $ID)) {
			$response = array ('response'=>'error', 'message'=> "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error);
			echo json_encode($response);
			die;
		}

		if (!$stmt->execute()) {
			$response = array ('response'=>'error', 'message'=> "Echec lors de l'exécution : (" . $stmt->errno . ") " . $stmt->error);
			echo json_encode($response);
			die;
		}

		$stmt->close();
		$response = array ('response'=>'success', 'message'=> L::successMessages_orderConfirmation);
		echo json_encode($response);
		die;

	}else if($action=="refuse"){
		if(!get_user_permissions("fleetManager", $token)){
			error_message('403');
		}

		include 'connexion.php';
		$ID=isset($_POST['ID']) ? $conn->real_escape_string($_POST['ID']) : NULL;
		$reasonOfRefusal=isset($_POST['reasonOfRefusal']) ? $conn->real_escape_string($_POST['reasonOfRefusal']) : NULL;

		$stmt = $conn->prepare("update client_orders set STATUS='refused', REMARK  = CONCAT(REMARK, 'Refusé par votre fleet manager pour la raison suivante: $reasonOfRefusal <br>') WHERE ID=?");

		if (!$stmt->bind_param("i", $ID)) {
			$response = array ('response'=>'error', 'message'=> "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error);
			echo json_encode($response);
			die;
		}

		if (!$stmt->execute()) {
			$response = array ('response'=>'error', 'message'=> "Echec lors de l'exécution : (" . $stmt->errno . ") " . $stmt->error);
			echo json_encode($response);
			die;
		}

		$stmt->close();
		$response = array ('response'=>'success', 'message'=> L::successMessages_orderRefusalConfirmation);
		echo json_encode($response);
		die;
	}else if($action=="delete"){
	}


}else if(isset($_GET['action'])){

	$action=isset($_GET['action']) ? $_GET['action'] : NULL;

	if($action=='list'){
		if(get_user_permissions(["admin", "fleetManager"], $token)){
			include 'connexion.php';
			$company=execSQL("SELECT COMPANY FROM customer_referential WHERE TOKEN=?", array('s', $token), false)[0]['COMPANY'];
			if($company=="KAMEO"){
				$sql= "SELECT grouped_orders.EMAIL, co.GROUP_ID, co.STATUS, co.ID, co.SIZE, co.ESTIMATED_DELIVERY_DATE, co.DELIVERY_ADDRESS, co.TEST_STATUS, co.TEST_DATE, co.TEST_BOOLEAN, co.LEASING_PRICE, co.TYPE, companies.ID as companyID, companies.COMPANY_NAME as companyName, (SELECT SUM(PRICE_HTVA) FROM order_accessories WHERE co.GROUP_ID=order_accessories.ORDER_ID AND order_accessories.TYPE=co.TYPE) as sumAccessories, bike_catalog.BRAND, bike_catalog.MODEL, co.BIKE_ID FROM client_orders co, companies, bike_catalog, grouped_orders WHERE grouped_orders.COMPANY_ID=companies.ID AND grouped_orders.ID=co.GROUP_ID AND co.PORTFOLIO_ID=bike_catalog.ID ORDER BY CASE STATUS WHEN 'new' THEN 1 WHEN 'confirmed' THEN 2 WHEN 'closed' THEN 3 ELSE 5 END, id DESC";
			}else{
				$sql="SELECT grouped_orders.EMAIL, co.GROUP_ID, co.STATUS, co.ID, co.SIZE, co.ESTIMATED_DELIVERY_DATE, co.DELIVERY_ADDRESS, co.TEST_STATUS, co.TEST_DATE, co.TEST_BOOLEAN, co.LEASING_PRICE, co.TYPE, companies.ID as companyID, companies.COMPANY_NAME as companyName, (SELECT SUM(PRICE_HTVA) FROM order_accessories WHERE co.GROUP_ID=order_accessories.ORDER_ID AND order_accessories.TYPE=co.TYPE) as sumAccessories, bike_catalog.BRAND, bike_catalog.MODEL, co.BIKE_ID FROM client_orders co, companies, bike_catalog, grouped_orders WHERE grouped_orders.COMPANY_ID=companies.ID AND grouped_orders.ID=co.GROUP_ID AND companies.INTERNAL_REFERENCE='$company' AND bike_catalog.ID=co.PORTFOLIO_ID ORDER BY CASE STATUS WHEN 'new' THEN 1 WHEN 'confirmed' THEN 2 WHEN 'closed' THEN 3 ELSE 5 END, id DESC";
			}
			if ($conn->query($sql) === FALSE) {
				$response = array ('response'=>'error', 'message'=> $conn->error);
				echo json_encode($response);
				die;
			}
			$result2 = mysqli_query($conn, $sql);
			$length = $result2->num_rows;
			$conn->close();
			$response=array();
			$response['response']="success";
			$response['ordersNumber']=$length;
			$i=0;

			while($row = mysqli_fetch_array($result2)){
				$emailCustomer=$row['EMAIL'];
				$response['order'][$i]['ID']=$row['ID'];
				$response['order'][$i]['groupID']=$row['GROUP_ID'];
				$response['order'][$i]['size']=$row['SIZE'];
				$response['order'][$i]['status']=$row['STATUS'];
				$response['order'][$i]['estimatedDeliveryDate']=$row['ESTIMATED_DELIVERY_DATE'];
				$response['order'][$i]['deliveryAddress']=$row['DELIVERY_ADDRESS'];
				$response['order'][$i]['testStatus']=$row['TEST_STATUS'];
				$response['order'][$i]['testDate']=$row['TEST_DATE'];
				$response['order'][$i]['testBoolean']=$row['TEST_BOOLEAN'];
				$response['order'][$i]['price']=$row['LEASING_PRICE'];
				$response['order'][$i]['type']=$row['TYPE'];
				$response['order'][$i]['companyID']=$row['companyID'];
				$response['order'][$i]['companyName']=$row['companyName'];
				$response['order'][$i]['brand']=$row['BRAND'];
				$response['order'][$i]['model']=$row['MODEL'];
				$response['order'][$i]['sumAccessories']=$row['sumAccessories'];

				if(!is_null($row['sumAccessories'])){
					$response['order'][$i]['price'] = $response['order'][$i]['price']+$row['sumAccessories'];
				};

				$response['order'][$i]['email']=$row['EMAIL'];
				if($row['BIKE_ID'] != NULL){
					$response['order'][$i]['contract'] = execSQL("SELECT CONTRACT_TYPE FROM customer_bikes WHERE ID = ?", array('i', $row['BIKE_ID']), false)[0]['CONTRACT_TYPE'];
				}
				$i++;
			}
		}
		log_output($response);
		echo json_encode($response);
		die;

	}else if($action=='retrieve'){

		$ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;

		$response['response']="success";
		$response['order']= execSQL("SELECT client_orders.ID, grouped_orders.EMAIL as email, client_orders.SIZE as size, client_orders.STATUS as status, client_orders.ESTIMATED_DELIVERY_DATE as estimatedDeliveryDate, client_orders.DELIVERY_ADDRESS as deliveryAddress,
		client_orders.TEST_BOOLEAN as testBoolean, client_orders.TEST_DATE as testDate, client_orders.TEST_ADDRESS as testAddress, client_orders.TEST_STATUS as testStatus, client_orders.TEST_RESULT as testResult, client_orders.LEASING_PRICE as price,
		client_orders.TYPE as type, client_orders.REMARK as comment, client_orders.PORTFOLIO_ID as portfolioID, client_orders.COMMENTS_ADMIN as commentsAdmin, client_orders.BIKE_ID as stockBikeID, bike_catalog.BRAND as brand, bike_catalog.MODEL as model,
		bike_catalog.FRAME_TYPE as frameType, bike_catalog.PRICE_HTVA as priceHTVA FROM client_orders, grouped_orders, bike_catalog WHERE client_orders.ID=? AND client_orders.GROUP_ID=grouped_orders.ID AND bike_catalog.ID=client_orders.PORTFOLIO_ID", array('i', $ID), false)[0];

		$resultat= execSQL("SELECT NOM as name, PRENOM as firstname, PHONE as phone FROM customer_referential WHERE TOKEN=?", array('s', $token), false)[0];
		$response['order']=array_merge ($response['order'], $resultat);
		$response['order']['accessories']=execSQL("SELECT order_accessories.BRAND as catalogID, order_accessories.PRICE_HTVA, accessories_categories.CATEGORY, accessories_catalog.BRAND, accessories_catalog.MODEL, order_accessories.TYPE, order_accessories.ORDER_ID as orderID
															FROM order_accessories, accessories_categories, accessories_catalog, client_orders
															WHERE order_accessories.BRAND=accessories_catalog.ID
															AND accessories_categories.ID=accessories_catalog.ACCESSORIES_CATEGORIES
															AND order_accessories.ORDER_ID=client_orders.GROUP_ID AND client_orders.ID=?", array('i', $ID), false);
		echo json_encode($response);
		die;
	}

	if($action=='delete')
	{
		include 'connexion.php';
		$ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;

		$sql = "DELETE FROM order_accessories WHERE ID='$ID'";

		if ($conn->query($sql) === FALSE) {

			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
		$result = mysqli_query($conn, $sql);
		$response = array ('response'=>'success', 'message'=> "Successfully Deleted!");

		echo json_encode($response);
		die;
	}
}
else{
	errorMessage("ES0012");
}


?>
