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

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
	$action=isset($_GET['action']) ? $_GET['action'] : NULL;

	if($action === 'listCatalog'){
		if(get_user_permissions("admin", $token)){
			require_once 'listCatalog.php';
		}else
		error_message('403');
	}
	else if ($action === 'getOrderDetailAcessory'){
		$id=isset($_GET['ID']) ? $_GET['ID'] : NULL;

		if(get_user_permissions("admin", $token)){
			$retrieveOrderAcessories= execSQL("SELECT order_accessories.ID, order_accessories.TYPE, order_accessories.PRICE_HTVA, order_accessories.STATUS, order_accessories.DESCRIPTION, grouped_orders.COMPANY_ID, grouped_orders.EMAIL, grouped_orders.ID as GROUP_ID,  accessories_categories.ID as categoryID, accessories_categories.CATEGORY, order_accessories.BRAND as catalogID, accessories_catalog.BRAND, accessories_catalog.MODEL FROM order_accessories, accessories_catalog, accessories_categories, grouped_orders WHERE order_accessories.BRAND=accessories_catalog.ID AND accessories_catalog.ACCESSORIES_CATEGORIES=accessories_categories.ID AND order_accessories.ID=? AND order_accessories.ORDER_ID=grouped_orders.ID", array('i', $id), false)[0];
			echo json_encode($retrieveOrderAcessories);
			die;
		}
		else{
			error_message('403');
		}
	}
	else if ($action === 'listOrderAcessories'){
		if(get_user_permissions("admin", $token)){
			$listOrderAcessories= execSQL("SELECT order_accessories.ID, order_accessories.TYPE, order_accessories.PRICE_HTVA, order_accessories.STATUS, COMPANY_NAME, grouped_orders.EMAIL, accessories_categories.CATEGORY, accessories_catalog.BRAND, accessories_catalog.MODEL FROM order_accessories, accessories_catalog, accessories_categories, companies, grouped_orders WHERE grouped_orders.ID=order_accessories.ORDER_ID AND order_accessories.BRAND=accessories_catalog.ID AND accessories_catalog.ACCESSORIES_CATEGORIES=accessories_categories.ID AND grouped_orders.COMPANY_ID=companies.ID", array(), false);
			echo json_encode($listOrderAcessories);
			die;
		}
		else{
			error_message('403');
		}
	}
	else if ($action === 'getStatOfAccessoryOrder'){
		$id=isset($_GET['ID']) ? $_GET['ID'] : NULL;
		if(get_user_permissions("admin", $token)){
			$response['state']= execSQL("SELECT aa.*, bb.* FROM accessories_stock aa, accessories_catalog bb WHERE aa.ORDER_ID='$id' AND aa.CATALOG_ID=bb.ID", array(), false);
			$response['response']="success";

			if($response['state']==null){
				$status="En attente de traitement";
			}
			if($response['state'][0]['CONTRACT_TYPE']=='order'){
				$status="En attente de livraison de nos fournisseurs";
			}
			if($response['state'][0]['CONTRACT_TYPE']=='pending_delivery'){
				$status="En attente d'expedition chez le client";
			}
			if($response['state'][0]['CONTRACT_TYPE']=='leasing' || $response['state'][0]['CONTRACT_TYPE']=='selling'){
				$status="Commande envoyé chez le client";
			}
			$response['status']=$status;
			echo json_encode($response);
			die;
		}
		else{
			error_message('403');
		}
	}
	else if ($action === 'getOrderAccessory'){
		$id=isset($_GET['ID']) ? $_GET['ID'] : NULL;
		if(get_user_permissions("admin", $token)){
			$retrieveOrderAcessories['orderAccessory']= execSQL("SELECT cc.*,dd.* FROM order_accessories aa, accessories_stock dd, accessories_catalog cc WHERE aa.ID='$id' AND aa.BRAND=dd.CATALOG_ID AND (dd.CONTRACT_TYPE='stock' OR dd.CONTRACT_TYPE='order') AND (dd.COMPANY_ID=12 OR dd.COMPANY_ID IS NULL) AND dd.ORDER_ID IS NULL AND cc.ID=aa.BRAND", array(), false);
			$retrieveOrderAcessories['response']="success";

			echo json_encode($retrieveOrderAcessories);
			die;
		}
		else{
			error_message('403');
		}
	}
	else if ($action === 'getStatOfAccessoryOrder'){
		$id=isset($_GET['ID']) ? $_GET['ID'] : NULL;
		if(get_user_permissions("admin", $token)){
			$response['state']= execSQL("SELECT aa.*, bb.* FROM accessories_stock aa, accessories_catalog bb WHERE aa.ORDER_ID='$id' AND aa.CATALOG_ID=bb.ID", array(), false);
			$response['response']="success";

			if($response['state']==null){
				$status="En attente de traitement";
			}
			if($response['state'][0]['CONTRACT_TYPE']=='order'){
				$status="En attente de livraison de nos fournisseurs";
			}
			if($response['state'][0]['CONTRACT_TYPE']=='pending_delivery'){
				$status="En attente d'expedition chez le client";
			}
			if($response['state'][0]['CONTRACT_TYPE']=='leasing' || $response['state'][0]['CONTRACT_TYPE']=='selling'){
				$status="Commande envoyé chez le client";
			}
			$response['status']=$status;
			echo json_encode($response);
			die;
		}
		else{
			error_message('403');
		}
	}
	else if ($action === 'updateOrderDetailAcessory'){
		$id=isset($_GET['id']) ? $_GET['id'] : NULL;
		$company=isset($_GET['company']) ? $_GET['company'] : NULL;
		$idAccessory = isset($_GET['accessory']) ? $_GET['accessory'] : NULL;

		if(get_user_permissions("admin", $token)){
			$listOrderAcessories= execSQL("SELECT * FROM accessories_stock WHERE ID='$idAccessory'", array(), false);
			if($listOrderAcessories[0]['CONTRACT_TYPE']=='order'){
				$contrat='order';
			}
			else{
				$contrat='pending_delivery';
			}
			$companyDetail= execSQL("SELECT * FROM companies WHERE COMPANY_NAME='$company'", array(), false);
			$idComp = $companyDetail[0]['ID'];

			$sql="UPDATE accessories_stock SET COMPANY_ID='$idComp', ORDER_ID='$id',CONTRACT_TYPE='$contrat' WHERE ID='$idAccessory'";
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			$retrieveOrderAcessories['response']="success";
			echo json_encode($retrieveOrderAcessories);
		}
		else{
			error_message('403');
		}
	}
	elseif($action === 'listStock'){
		if(get_user_permissions("admin", $token)){
			$stockAccessories['accessories'] = execSQL("SELECT aa.*, bb.BRAND, bb.MODEL, cc.ID as categoryId, cc.CATEGORY, dd.COMPANY_NAME, bb.BUYING_PRICE as buyingPriceCatalog, bb.PRICE_HTVA as sellingPriceCatalog FROM accessories_stock aa, accessories_catalog bb, accessories_categories cc, companies dd WHERE aa.CATALOG_ID=bb.ID AND bb.ACCESSORIES_CATEGORIES=cc.ID AND aa.COMPANY_ID=dd.ID ORDER BY cc.CATEGORY", array(), false);
			$stockAccessories['response']="success";
			echo json_encode($stockAccessories);
			die;
		}else
		error_message('403');
	}elseif($action=="getAccessoryStock"){
		if(get_user_permissions("admin", $token)){
			$accessory = execSQL("SELECT aa.*, bb.CATEGORY, cc.ACCESSORIES_CATEGORIES FROM accessories_stock aa, accessories_categories bb, accessories_catalog cc WHERE aa.CATALOG_ID=cc.ID AND cc.ACCESSORIES_CATEGORIES=bb.ID AND aa.ID=?", array("s", $_GET['ID']), false)[0];
			$accessory['response']="success";
			echo json_encode($accessory);
			die;
		}else
		error_message('403');
	}else if($action === 'listCategories'){
		if(get_user_permissions("admin", $token)){
			require_once 'listCategories.php';
		}else
		error_message('403');
	}else if($action === 'retrieveCatalog'){
		if(get_user_permissions("admin", $token)){
			$ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;
			$response['accessory']=execSQL("SELECT accessories_catalog.ID, accessories_catalog.BRAND, accessories_catalog.MODEL, accessories_catalog.DESCRIPTION, accessories_catalog.ACCESSORIES_CATEGORIES, accessories_catalog.BUYING_PRICE, accessories_catalog.PRICE_HTVA, accessories_catalog.DISPLAY, accessories_catalog.PROVIDER, accessories_categories.ID, accessories_categories.CATEGORY, accessories_catalog.REFERENCE, accessories_catalog.MINIMAL_STOCK,accessories_catalog.STOCK_OPTIMUM
				FROM accessories_catalog, accessories_categories
				WHERE accessories_catalog.ACCESSORIES_CATEGORIES = accessories_categories.ID AND accessories_catalog.ID=?", array('i', $ID), false)[0];
			$response['response']='success';
			echo json_encode($response);
			die;
		}else
			error_message('403');
	}else if($action === 'listCatalog'){
		if(get_user_permissions("admin", $token)){
			require_once 'listCatalog.php';
		}else
		error_message('403');
	}else if($action === 'getBikeFromCompany'){
		if(get_user_permissions("admin", $token)){
			$idComp=isset($_GET['ID']) ? $_GET['ID'] : NULL;
			$result=execSQL("SELECT customer_bikes.* FROM customer_bikes, companies where companies.ID=? AND companies.INTERNAL_REFERENCE=customer_bikes.COMPANY", array('i', $idComp), false);
			$i=0;
			if(is_null($result)){
				$response['bikeNumber']=0;
				$response['bike']=array();
			}else{
				foreach($result as $row){
					$response['bike'][$i]['id']=$row['ID'];
					$response['bike'][$i]['model']=$row['MODEL'];
					$response['bike'][$i]['contract']=$row['CONTRACT_TYPE'];
					$i++;
				}
			}
			$response['bikeNumber']=$i;
			echo json_encode($response);
			die;
		}else
		error_message('403');
	}elseif($action === 'listStock'){
		if(get_user_permissions("admin", $token)){
			$stockAccessories['accessories'] = execSQL("SELECT aa.*, bb.BRAND, bb.MODEL, cc.CATEGORY FROM accessories_stock aa, accessories_catalog bb, accessories_categories cc WHERE aa.CATALOG_ID=bb.ID AND bb.ACCESSORIES_CATEGORIES=cc.ID", array(), false);
			$stockAccessories['response']="success";
			echo json_encode($stockAccessories);
			die;
		}else
		error_message('403');
	}elseif($action=="getAccessoryStock"){
		if(get_user_permissions("admin", $token)){
			$accessory = execSQL("SELECT aa.*, bb.CATEGORY, cc.ACCESSORIES_CATEGORIES FROM accessories_stock aa, accessories_categories bb, accessories_catalog cc WHERE aa.CATALOG_ID=cc.ID AND cc.ACCESSORIES_CATEGORIES=bb.ID AND aa.ID=?", array("s", $_GET['ID']), false)[0];
			$accessory['response']="success";
			echo json_encode($accessory);
			die;
		}else
		error_message('403');
	}else if($action === 'listCategories'){
		if(get_user_permissions("admin", $token)){
			require_once 'listCategories.php';
		}else
		error_message('403');
	}else if($action === 'retrieveCatalog'){
		if(get_user_permissions("admin", $token)){
			$ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;
			$response['accessory']=execSQL("SELECT accessories_catalog.ID, accessories_catalog.BRAND, accessories_catalog.MODEL, accessories_catalog.DESCRIPTION, accessories_catalog.ACCESSORIES_CATEGORIES, accessories_catalog.BUYING_PRICE, accessories_catalog.PRICE_HTVA, accessories_catalog.STOCK, accessories_catalog.DISPLAY, accessories_catalog.PROVIDER, accessories_categories.ID, accessories_categories.CATEGORY, accessories_catalog.REFERENCE, accessories_catalog.MINIMAL_STOCK,accessories_catalog.STOCK_OPTIMUM
				FROM accessories_catalog, accessories_categories
				WHERE accessories_catalog.ACCESSORIES_CATEGORIES = accessories_categories.ID AND accessories_catalog.ID=?", array('i', $ID), false)[0];
			$response['response']='success';
			echo json_encode($response);
			die;
		}else
		error_message('403');
	}else if($action === 'getCategories'){
		if(get_user_permissions("admin", $token)){
			$categories['categories'] = execSQL("SELECT ID, CATEGORY FROM accessories_categories GROUP BY ID, CATEGORY ORDER BY CATEGORY", array(), false);
			$categories['response']="success";
			echo json_encode($categories);
			die;
		}else
		error_message('403');
	}else if($action === 'getModelsCategory'){
		if(get_user_permissions("admin", $token)){
			$models['models'] = execSQL("SELECT ID, BRAND, MODEL, PRICE_HTVA FROM accessories_catalog WHERE ACCESSORIES_CATEGORIES = ? ORDER BY  BRAND, MODEL", array("i", $_GET['category']), false);
			if(is_null($models['models'])){
				$models['models']=array();
			}
			$models['response']="success";
			echo json_encode($models);
			die;
		}else
		error_message('403');
	}
	else if($action === 'getFacturesBillsAccessory'){
		if(get_user_permissions("admin", $token)){
			include 'accessoriesBills.php';
			die;
		}else
		error_message('403');
	}
	else if($action === 'getFacturesBillsNotLinkedAccessory'){
		if(get_user_permissions("admin", $token)){
			$response=execSQL("SELECT aa.*, bb.BRAND, bb.MODEL, cc.COMPANY_NAME FROM accessories_stock aa, accessories_catalog bb, companies cc WHERE aa.CATALOG_ID=bb.ID AND cc.ID=aa.COMPANY_ID AND NOT EXISTS(SELECT 1 from bills_catalog_accessories_link where bills_catalog_accessories_link.ACCESSORY_ID=aa.ID)", array(), false);
			if($response == null){
				$response=array();
			}
			echo json_encode($response);
			die;
		}else
		error_message('403');
	}
	else if($action === 'getLinkAccessoriesBillsDetails'){
		if(get_user_permissions("admin", $token)){
			$id=$_GET['billingID'];
			$response['billingDetails']=execSQL("SELECT * from factures WHERE ID=?", array('i', $id), false)[0];

			$response['catalogDetails']=execSQL("SELECT BRAND, MODEL, accessories_catalog.ID as catalogID, bills_catalog_accessories_link.BUYING_PRICE, PRICE_HTVA, bills_catalog_accessories_link.ACCESSORY_ID from bills_catalog_accessories_link, accessories_catalog WHERE bills_catalog_accessories_link.CATALOG_ID=accessories_catalog.ID AND bills_catalog_accessories_link.FACTURE_ID=?", array('i', $id), false);

			$response['modelDetails']=execSQL("SELECT * from factures_details WHERE FACTURE_ID=?", array('i', $id), false);

			$response['notLinkedAccessories']=execSQL("SELECT bills_catalog_accessories_link.ID, bills_catalog_accessories_link.CATALOG_ID, BRAND, MODEL,accessories_categories.CATEGORY FROM bills_catalog_accessories_link, accessories_catalog,accessories_categories WHERE FACTURE_ID=? AND bills_catalog_accessories_link.CATALOG_ID=accessories_catalog.ID AND accessories_catalog.ACCESSORIES_CATEGORIES=accessories_categories.ID AND bills_catalog_accessories_link.ACCESSORY_ID is NULL ", array('i', $id), false);

			echo json_encode($response);
			die;
		}else
		error_message('403');
	}
	else if($action === 'summaryAccessoriesLinked'){
		if(get_user_permissions("admin", $token)){
			$response=execSQL("SELECT accessories_stock.*, bills_catalog_accessories_link.BUYING_PRICE, accessories_catalog.BRAND, accessories_catalog.MODEL, companies.COMPANY_NAME FROM accessories_stock, accessories_catalog, bills_catalog_accessories_link, companies WHERE accessories_catalog.ID=accessories_stock.CATALOG_ID AND companies.ID=accessories_stock.COMPANY_ID AND accessories_stock.ID=bills_catalog_accessories_link.ACCESSORY_ID AND bills_catalog_accessories_link.FACTURE_ID=?
				UNION ALL
				(SELECT accessories_stock.*, bills_catalog_accessories_link.BUYING_PRICE, accessories_catalog.BRAND, accessories_catalog.MODEL,'NONE' FROM accessories_stock, accessories_catalog, bills_catalog_accessories_link WHERE accessories_catalog.ID=accessories_stock.CATALOG_ID AND accessories_stock.ID=bills_catalog_accessories_link.ACCESSORY_ID AND bills_catalog_accessories_link.FACTURE_ID=? AND not EXISTS(SELECT 1 FROM companies WHERE companies.ID=accessories_stock.COMPANY_ID))", array('ii', $_GET['factureID'],$_GET['factureID']), false);
			echo json_encode($response);
			die;
		}else
		error_message('403');
	}
	else if($action === 'listModelsFromBrandAccessories'){
		if(get_user_permissions("admin", $token)){
			$brand = $_GET['brand'];
			$response=execSQL("SELECT accessories_categories.CATEGORY ,accessories_catalog.* FROM accessories_catalog, accessories_categories  WHERE accessories_catalog.BRAND='$brand' AND accessories_categories.ID=accessories_catalog.ACCESSORIES_CATEGORIES ORDER BY MODEL", array(), false);
			echo json_encode($response);
			die;
		}else
		error_message('403');
	}
	else if($action === 'listAccessoriesNotLinkedToBill'){
		if(get_user_permissions("admin", $token)){
			$catalogID = $_GET['catalogID'];
			$response=execSQL("SELECT  aa.*,bb.COMPANY_NAME,cc.CATEGORY,dd.BRAND,dd.MODEL FROM accessories_stock aa, companies bb,accessories_categories cc,accessories_catalog dd WHERE aa.CATALOG_ID='$catalogID' AND dd.ID=aa.CATALOG_ID AND dd.ACCESSORIES_CATEGORIES=cc.ID AND NOT EXISTS(SELECT 1 from bills_catalog_accessories_link where bills_catalog_accessories_link.ACCESSORY_ID=aa.ID) AND bb.ID =aa.COMPANY_ID
				UNION ALL
				(SELECT  aa.*,'NONE',cc.CATEGORY,dd.BRAND,dd.MODEL FROM accessories_stock aa,accessories_categories cc,accessories_catalog dd WHERE aa.CATALOG_ID='$catalogID' AND dd.ID=aa.CATALOG_ID AND dd.ACCESSORIES_CATEGORIES=cc.ID AND NOT EXISTS(SELECT 1 from bills_catalog_accessories_link where bills_catalog_accessories_link.ACCESSORY_ID=aa.ID) AND NOT EXISTS(SELECT 1 from companies where companies.ID=aa.COMPANY_ID))", array(), false);
			echo json_encode($response);
			die;
		}else
		error_message('403');
	}
	else if($action === 'linkAccessoryToBill'){
			if(get_user_permissions("admin", $token)){
				execSQL("UPDATE bills_catalog_accessories_link SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, ACCESSORY_ID=? WHERE ID=?", array('sii', $token, $_GET['accessoryID'], $_GET['ID']), true);
				successMessage("SM0003");
			}else
				error_message('403');
		}


	else
		error_message('405');
	break;
	case 'POST':
	$action=isset($_POST['action']) ? $_POST['action'] : NULL;
	if($action === 'addAccessory'){
		if(get_user_permissions("admin", $token)){
			require_once 'add_catalog_accessory.php';
		}else
		error_message('403');
	}else if($action === 'addStockAccessory'){
		if(get_user_permissions("admin", $token)){
			require 'add_stock_accessory.php';
		}
		else
			error_message('403');
	}else if($action === 'updateStockAccessory'){
		if(get_user_permissions("admin", $token))
			require 'add_stock_accessory.php';
		else
			error_message('403');
	}
	else if($action === 'valideFormTolinkAccessoriesToBills'){
		if(get_user_permissions("admin", $token)){
			if(isset($_POST['catalogID'])){
				foreach ($_POST['catalogID'] as $key=>$value) {
					$billingID=$_POST['ID'];
					$catalogID=$value;
					$buyingPrice=$_POST['buyingPrice'][$key];
					execSQL("INSERT INTO bills_catalog_accessories_link (USR_MAJ, FACTURE_ID, CATALOG_ID, BUYING_PRICE) VALUES (?, ?, ?, ?)", array('siid', $token, $billingID, $catalogID, $buyingPrice), true);
				}
				successMessage("SM0003");
			}
			die;
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
