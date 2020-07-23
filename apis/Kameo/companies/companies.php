<?php
session_cache_limiter('nocache');
if(!isset($_SESSION)) 
	session_start();

header('Content-type: application/json');
header('WWW-Authenticate: Bearer realm="DefaultRealm"');
header('Expires: ' . gmdate('r', 0));

include '../globalfunctions.php';
include '../authentication.php';
include '../connexion.php'; 


//CHECK AUTH AND PERMS HERE:
$token = getBearerToken(); //Defined in authentication.php
if (authenticate($token))	//If token exist in databases
{
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
}else
    error_message('401');
?>