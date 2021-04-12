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

    if($action === 'retrieve'){
			if(get_user_permissions("bills", $token)){
			}else{
				error_message('403');
			}
    }else if($action === 'list'){
			if(get_user_permissions(["admin", "bills"], $token)){
				include 'get_bills_listing.php';
			}else{
				error_message('403');
			}
		}else if($action === 'getLinkBikesBillsDetails'){
			if(get_user_permissions(["admin", "bikesStock"], $token)){
				include 'getLinkBikesBillsDetails.php';
			}else{
				error_message('403');
			}
		}else if($action === 'listCompaniesWithLeasing'){
			if(get_user_permissions("bills", $token)){
				include 'get_list_of_companies_with_leasing.php';
			}else
				error_message('403');
		}else if($action === 'listBikesNotLinkedToBill'){
			if(get_user_permissions(["admin", "bikesStock"], $token)){
				$response=execSQL("SELECT * FROM customer_bikes WHERE TYPE=? AND NOT EXISTS(SELECT 1 from bills_catalog_bikes_link where bills_catalog_bikes_link.BIKE_ID=customer_bikes.ID) and STAANN != 'D' AND CONTRACT_TYPE in ('selling', 'leasing', 'renting', 'stock', 'pending_delivery')", array('i', $_GET['catalogID']), false);
				if($response == null){
					$response=array();
				}
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'graphic'){
			if(get_user_permissions("bills", $token)){
				include 'graphic_companies.php';
			}else{
				error_message('403');
			}
		}else
			error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;

		if($action === 'addCompanyContact'){
			if(get_user_permissions("bills", $token)){
				include 'add_company_contact.php';
			}else{
				error_message('403');
			}
		}else if($action === 'editCompanyContact'){
			if(get_user_permissions("bills", $token)){
				include 'edit_company_contact.php';
			}else{
				error_message('403');
			}
		}else if($action === 'linkBikeToBill'){
			if(get_user_permissions(["admin", "bikesStock"], $token)){
				execSQL("UPDATE bills_catalog_bikes_link SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, BIKE_ID=? WHERE ID=?", array('sii', $token, $_POST['bikeID'], $_POST['ID']), true);
				successMessage("SM0003");
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
$conn->close();
?>
