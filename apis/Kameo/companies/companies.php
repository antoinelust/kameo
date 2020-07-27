<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));

require_once 'apis/Kameo/globalfunctions.php';
require_once 'apis/Kameo/authentication.php';
require_once 'apis/Kameo/connexion.php'; 

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;
		
		if($action == 'list'){         
			if(get_user_permissions("admin", $token)){
				header("HTTP/1.0 200 Ok");                    
				include 'list_companies.php';
			}else{
				error_message('401');
			}
		}else if($action == 'graphic'){
			if(get_user_permissions("admin", $token)){
				header("HTTP/1.0 200 Ok");                    
				include 'graphic_companies.php';
			}else{
				error_message('401');
			}
		}else{
			error_message('405');
		}    
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
		
		if($action == 'addCompanyContact'){
			if(get_user_permissions("admin", $token)){
				header("HTTP/1.0 200 Ok");                    
				include 'add_company_contact.php';
			}else{
				error_message('401');
			}
		}else if($action == 'editCompanyContact'){
			if(get_user_permissions("admin", $token)){
				header("HTTP/1.0 200 Ok");                    
				include 'edit_company_contact.php';
			}else{
				error_message('401');
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