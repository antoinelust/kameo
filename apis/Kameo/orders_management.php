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
	$idBike=isset($_POST['assignBike']) ? $_POST['assignBike'] : NULL;
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

	if($action=='add'){
		if(!get_user_permissions("admin", $token)){
			error_message('403');
		}
		include 'connexion.php';
		$stmt = $conn->prepare("INSERT INTO client_orders (HEU_MAJ, USR_MAJ, EMAIL, PORTFOLIO_ID, SIZE, STATUS, TEST_BOOLEAN, TEST_DATE, TEST_ADDRESS, TEST_STATUS, TEST_RESULT, ESTIMATED_DELIVERY_DATE, DELIVERY_ADDRESS, LEASING_PRICE, TYPE, COMPANY) VALUES(CURRENT_TIMESTAMP, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		if ($stmt)
		{
			$stmt->bind_param("ssisssssssssdsi", $token, $mail, $portfolioID, $size, $status, $testBoolean, $testDate, $testAddress, $testStatus, $testResult, $deliveryDate, $deliveryAddress, $price, $type, $company );
			if(!$stmt->execute()){echo json_encode("there was an error....".$conn->error); die;}
			$response['response']="success";
			$stmt->close();
			successMessage("SM0003");
		} else
		error_message('500', 'Error occured while changing data');
		$conn->close();


			
	


	}
	else if($action=='update'){

		include 'connexion.php';
		$stmt = $conn->prepare("UPDATE client_orders  SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, EMAIL=?, STATUS=?, PORTFOLIO_ID=?, SIZE=?, DELIVERY_ADDRESS=?, LEASING_PRICE=?, TYPE=?, ESTIMATED_DELIVERY_DATE=?, DELIVERY_ADDRESS=?, TEST_STATUS=? WHERE ID=?");
		if ($stmt)
		{
			$stmt->bind_param("sssissdssssi", $email, $mail, $status, $portfolioID, $size, $deliveryAddress, $price, $type, $deliveryDate, $deliveryAddress, $testStatus, $ID);
			if(!$stmt->execute()){echo "there was an error....".$conn->error;}
			$response['response']="success";
			$stmt->close();
		} else{
			error_message('500', 'Error occured while changing data');
		}

//////////////////////////////////////////////Code test assignation velo

		if ($idBike!=null){

			include 'connexion.php';

			$stmt = $conn->prepare("UPDATE customer_bikes SET HEU_MAJ=CURRENT_TIMESTAMP,USR_MAJ='$email',CONTRACT_TYPE='pending_delivery' WHERE ID='$idBike'");
			$stmt->execute();

			$sqlTest = "INSERT INTO customer_bike_access (TIMESTAMP, USR_MAJ, EMAIL , BIKE_ID,TYPE)
			VALUES (CURRENT_TIMESTAMP, '$email', '$mail', '$idBike' ,'personnel')";
			mysqli_query($conn, $sqlTest);

		}

////////////////////////////////////////////////

		if($testBoolean=="Y"){
			include 'connexion.php';
			$stmt = $conn->prepare("UPDATE client_orders  SET TEST_BOOLEAN='Y', TEST_DATE=?, TEST_ADDRESS=?, TEST_RESULT=? WHERE ID=?");
			if ($stmt)
			{
				$stmt->bind_param("sssi", $testDate, $testAddress, $testResult, $ID);
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
			$sql="SELECT * FROM customer_referential WHERE TOKEN='$token'";
			if ($conn->query($sql) === FALSE) {
				$response = array ('response'=>'error', 'message'=> $conn->error);
				echo json_encode($response);
				die;
			}
			$result = mysqli_query($conn, $sql);
			$resultat=mysqli_fetch_assoc($result);
			$company=$resultat['COMPANY'];

			if($company=="KAMEO"){
				$sql= "SELECT aa.*, bb.ID as companyID, bb.COMPANY_NAME as companyName FROM client_orders aa, companies bb WHERE aa.COMPANY=bb.ID ORDER BY CASE STATUS WHEN 'new' THEN 1 WHEN 'confirmed' THEN 2 WHEN 'closed' THEN 3 ELSE 5 END, id DESC";
			}else{
				$sql="SELECT co.*, companies.ID as companyID, companies.COMPANY_NAME as companyName FROM client_orders co, companies WHERE co.COMPANY=companies.ID AND companies.INTERNAL_REFERENCE='$company' ORDER BY CASE STATUS WHEN 'new' THEN 1 WHEN 'confirmed' THEN 2 WHEN 'closed' THEN 3 ELSE 5 END, id DESC";
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



				$portfolioID=$row['PORTFOLIO_ID'];
				include 'connexion.php';
				$sql= "SELECT * FROM bike_catalog WHERE ID='$portfolioID'";
				if ($conn->query($sql) === FALSE) {
					$response = array ('response'=>'error', 'message'=> $conn->error);
					echo json_encode($response);
					die;
				}
				$result = mysqli_query($conn, $sql);
				$resultat=mysqli_fetch_assoc($result);
				$response['order'][$i]['brand']=$resultat['BRAND'];
				$response['order'][$i]['model']=$resultat['MODEL'];
				$priceHTVA=$resultat['PRICE_HTVA'];

				$emailUser=$row['EMAIL'];
				if($emailUser != ""){
					include 'connexion.php';
					$sql= "SELECT * FROM customer_referential WHERE EMAIL='$emailUser'";
					if ($conn->query($sql) === FALSE) {
						$response = array ('response'=>'error', 'message'=> $conn->error);
						echo json_encode($response);
						die;
					}
					$result = mysqli_query($conn, $sql);
					$resultat=mysqli_fetch_assoc($result);
					$response['order'][$i]['user']=$resultat['PRENOM']." ".$resultat['NOM'];
				}else{
					$response['order'][$i]['user']="N/A";
				}

				if($row['STATUS']=='confirmed'){
					if($emailUser != ''){
						$sqlStatus = "SELECT BIKE_ID
						FROM customer_bike_access WHERE EMAIL = '$emailUser'";
						$resultStatus = mysqli_query($conn, $sqlStatus);
						$rowStatus = $resultStatus->fetch_assoc();
						$tempBikeID = $rowStatus['BIKE_ID'];
						if($tempBikeID!=null){
							$sqlContrat = "SELECT CONTRACT_TYPE
							FROM customer_bikes WHERE ID = '$tempBikeID'";
							$resultContrat = mysqli_query($conn, $sqlContrat);
							$rowContrat = $resultContrat->fetch_assoc();
							$response['order'][$i]['contract'] = $rowContrat['CONTRACT_TYPE'];
						}
					}
				}
				$i++;
			}
		}
		log_output($response);
		echo json_encode($response);
		die;

	}else if($action=='retrieve'){

		$ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;

		include 'connexion.php';
		$sql= "SELECT * FROM client_orders WHERE ID='$ID'";
		if ($conn->query($sql) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
		$result = mysqli_query($conn, $sql);
		$resultat = mysqli_fetch_assoc($result);
		$conn->close();
		$response=array();
		$email = $resultat['EMAIL'];
		$response['response']="success";
		$response['order']['ID']=$resultat['ID'];
		$response['order']['email']=$email;
		$response['order']['size']=$resultat['SIZE'];
		$response['order']['status']=$resultat['STATUS'];
		$response['order']['estimatedDeliveryDate']=$resultat['ESTIMATED_DELIVERY_DATE'];
		$response['order']['deliveryAddress']=$resultat['DELIVERY_ADDRESS'];
		$response['order']['testBoolean']=$resultat['TEST_BOOLEAN'];
		$response['order']['testDate']=$resultat['TEST_DATE'];
		$response['order']['testAddress']=$resultat['TEST_ADDRESS'];
		$response['order']['testStatus']=$resultat['TEST_STATUS'];
		$response['order']['testResult']=$resultat['TEST_RESULT'];
		$response['order']['price']=$resultat['LEASING_PRICE'];
		$response['order']['type']=$resultat['TYPE'];
		$response['order']['comment']=br2nl($resultat['REMARK']);
		$response['order']['img']=br2nl($resultat['PORTFOLIO_ID']);
		$email=$resultat['EMAIL'];

		$portfolioID=$resultat['PORTFOLIO_ID'];



		include 'connexion.php';
		$sql= "SELECT * FROM bike_catalog WHERE ID='$portfolioID'";
		if ($conn->query($sql) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
		$result = mysqli_query($conn, $sql);
		$resultat = mysqli_fetch_assoc($result);
		$response['order']['portfolioID']=$portfolioID;
		$response['order']['brand']=$resultat['BRAND'];
		$response['order']['model']=$resultat['MODEL'];
		$response['order']['frameType']=$resultat['FRAME_TYPE'];
		$priceHTVA=$resultat['PRICE_HTVA'];

		$sql= "SELECT * FROM customer_referential WHERE EMAIL='$email'";
		if ($conn->query($sql) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
		$result = mysqli_query($conn, $sql);
		$resultat = mysqli_fetch_assoc($result);
		$company=$resultat['COMPANY'];
		$response['order']['name']=$resultat['NOM'];
		$response['order']['firstname']=$resultat['PRENOM'];
		$response['order']['phone']=$resultat['PHONE'];
		$response['order']['priceHTVA']=$priceHTVA;
		$resultat=execSQL("SELECT order_accessories.BUYING_PRICE, order_accessories.PRICE_HTVA, accessories_categories.CATEGORY, MODEL, order_accessories.TYPE, order_accessories.ID as orderID FROM order_accessories INNER JOIN accessories_categories ON order_accessories.CATEGORY = accessories_categories.ID INNER JOIN accessories_catalog ON accessories_catalog.ACCESSORIES_CATEGORIES = accessories_categories.ID WHERE order_accessories.ORDER_ID=? AND accessories_catalog.ID=order_accessories.BRAND", array('i', $ID), false);
		$response['accessoryNumber'] = 0;
		$response['order']['accessories']=$resultat;
		if($resultat){
			$response['accessoryNumber']=count($resultat);
		}else{
			$response['accessoryNumber'] = 0;
		}
		$result = mysqli_query($conn, $sql);
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
