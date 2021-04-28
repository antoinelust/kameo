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

log_inputs($token);

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;

		if($action === 'listModelsFromBrand'){
			if(get_user_permissions("admin", $token)){
				$response=execSQL("SELECT ID, MODEL, FRAME_TYPE, SEASON, PRICE_HTVA, BUYING_PRICE FROM bike_catalog WHERE BRAND = ? AND STAANN != 'D' ORDER BY MODEL", array('s', $_GET['brand']), false);
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'listSizesFromModel'){
			if(get_user_permissions("admin", $token)){
				$response=execSQL("SELECT SIZES FROM bike_catalog WHERE ID=?", array('i', $_GET['ID']), false)[0]['SIZES'];
				if(is_null($response)){
					$response=array();
				}else{
					$response=explode(",", $response);
					array_column($response, 'SIZES');
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
		if($action === 'linkBikeToBill'){
			if(get_user_permissions("admin", $token)){
				if(isset($_POST['catalogID'])){
					foreach ($_POST['catalogID'] as $key=>$value) {
						$billingID=$_POST['ID'];
						$catalogID=$value;
						$buyingPrice=$_POST['buyingPrice'][$key];
						$size=$_POST['size'][$key];
						execSQL("INSERT INTO bills_catalog_bikes_link (USR_MAJ, FACTURE_ID, CATALOG_ID, SIZE, BUYING_PRICE) VALUES (?, ?, ?, ?, ?)", array('siisd', $token, $billingID, $catalogID, $size, $buyingPrice), true);
					}
					successMessage("SM0003");
				}
				$response['response']='success';
				echo json_encode($response);
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

$conn->close();
?>
