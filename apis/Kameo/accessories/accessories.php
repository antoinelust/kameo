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
require_once __DIR__ .'/../connexion.php';

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
		}elseif($action === 'listStock'){
			if(get_user_permissions("admin", $token)){
				include '../connexion.php';
				require_once 'listStock.php';
				$conn->close();
			}else
				error_message('403');
		}elseif($action=="getAccessoryStock"){
			if(get_user_permissions("admin", $token)){
				include '../connexion.php';
				$accessory = execSQL("SELECT aa.*, bb.CATEGORY, cc.ACCESSORIES_CATEGORIES FROM accessories_stock aa, accessories_categories bb, accessories_catalog cc WHERE aa.CATALOG_ID=cc.ID AND cc.ACCESSORIES_CATEGORIES=bb.ID AND aa.ID=?", array("s", $_GET['ID']), false)[0];
				$accessory['response']="success";
				echo json_encode($accessory);
				die;
				$conn->close();
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
				$response['accessory']=execSQL("SELECT accessories_catalog.ID, accessories_catalog.BRAND, accessories_catalog.MODEL, accessories_catalog.DESCRIPTION, accessories_catalog.ACCESSORIES_CATEGORIES, accessories_catalog.BUYING_PRICE, accessories_catalog.PRICE_HTVA, accessories_catalog.STOCK, accessories_catalog.DISPLAY, accessories_catalog.PROVIDER, accessories_categories.ID, accessories_categories.CATEGORY, accessories_catalog.REFERENCE, accessories_catalog.MINIMAL_STOCK
			  FROM accessories_catalog, accessories_categories
			  WHERE accessories_catalog.ACCESSORIES_CATEGORIES = accessories_categories.ID AND accessories_catalog.ID=?", array('i', $ID), false)[0];
				$response['response']='success';
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'getCategories'){
			if(get_user_permissions("admin", $token)){
				include '../connexion.php';
				$categories['categories'] = execSQL("SELECT ID, CATEGORY FROM accessories_categories GROUP BY ID, CATEGORY ORDER BY CATEGORY", array(), false);
				$categories['response']="success";
				echo json_encode($categories);
				die;
			}else
				error_message('403');
		}else if($action === 'getModelsCategory'){
			if(get_user_permissions("admin", $token)){
				include '../connexion.php';
				$models['models'] = execSQL("SELECT ID, BRAND, MODEL FROM accessories_catalog WHERE ACCESSORIES_CATEGORIES = ? ORDER BY  BRAND, MODEL", array("i", $_GET['category']), false);
				$models['response']="success";
				echo json_encode($models);
				die;
			}else
				error_message('403');
		}else
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
			if(get_user_permissions("admin", $token))
				require 'add_stock_accessory.php';
			else
				error_message('403');
		}else if($action === 'updateStockAccessory'){
			if(get_user_permissions("admin", $token))
				require 'add_stock_accessory.php';
			else
				error_message('403');
		}else
        error_message('405');

	break;
	default:
			error_message('405');
        break;
}

$conn->close();
?>
