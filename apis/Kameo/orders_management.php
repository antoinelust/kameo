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
	else if($action=="confirmCommand"){
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
			$company=execSQL("SELECT COMPANY FROM customer_referential WHERE TOKEN=?", array('s', $token), false)[0]['COMPANY'];
			if($company=="KAMEO"){
				$sql= "SELECT grouped_orders.EMAIL, co.GROUP_ID, co.STATUS, co.ID, co.SIZE, co.ESTIMATED_DELIVERY_DATE, co.DELIVERY_ADDRESS, co.TEST_STATUS, co.TEST_DATE, co.TEST_BOOLEAN, co.LEASING_PRICE, co.TYPE, companies.ID as companyID, companies.COMPANY_NAME as companyName, (SELECT SUM(PRICE_HTVA) FROM order_accessories WHERE co.GROUP_ID=order_accessories.ORDER_ID AND order_accessories.TYPE=co.TYPE) as sumAccessories, bike_catalog.BRAND, bike_catalog.MODEL, co.BIKE_ID FROM client_orders co, companies, bike_catalog, grouped_orders WHERE grouped_orders.COMPANY_ID=companies.ID AND grouped_orders.ID=co.GROUP_ID AND co.PORTFOLIO_ID=bike_catalog.ID ORDER BY CASE STATUS WHEN 'new' THEN 1 WHEN 'confirmed' THEN 2 WHEN 'closed' THEN 3 ELSE 5 END, id DESC";
			}else{
				$sql="SELECT grouped_orders.EMAIL, co.GROUP_ID, co.STATUS, co.ID, co.SIZE, co.ESTIMATED_DELIVERY_DATE, co.DELIVERY_ADDRESS, co.TEST_STATUS, co.TEST_DATE, co.TEST_BOOLEAN, co.LEASING_PRICE, co.TYPE, companies.ID as companyID, companies.COMPANY_NAME as companyName, (SELECT SUM(PRICE_HTVA) FROM order_accessories WHERE co.GROUP_ID=order_accessories.ORDER_ID AND order_accessories.TYPE=co.TYPE) as sumAccessories, bike_catalog.BRAND, bike_catalog.MODEL, co.BIKE_ID FROM client_orders co, companies, bike_catalog, grouped_orders WHERE grouped_orders.COMPANY_ID=companies.ID AND grouped_orders.ID=co.GROUP_ID AND companies.INTERNAL_REFERENCE='$company' AND bike_catalog.ID=co.PORTFOLIO_ID ORDER BY CASE STATUS WHEN 'new' THEN 1 WHEN 'confirmed' THEN 2 WHEN 'closed' THEN 3 ELSE 5 END, id DESC";
			}
			include 'connexion.php';
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
		$tvaIncluded="N";
		$resultat=execSQL("SELECT TVA_INCLUDED FROM conditions WHERE COMPANY=?", array('s', $company), false);
		if(count($resultat)==1){
			if($resultat[0]['TVA_INCLUDED']=='Y'){
				$tvaIncluded='Y';
			}
		}
		$response['tvaIncluded']=$tvaIncluded;
		log_output($response);
		echo json_encode($response);
		die;

	}else if($action=='delete')
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
