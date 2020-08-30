<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

require_once '../globalfunctions.php';
require_once '../authentication.php';
require_once '../connexion.php'; 

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
			if(get_user_permissions("bills", $token)){
				include 'get_list_of_companies_bills.php';
			}else{
				error_message('403');
			}
		}else if($action === 'listCafetariaCompanies'){
			if(get_user_permissions("bills", $token)){
			}else
				error_message('403');
		}
		else if($action === 'graphic'){
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