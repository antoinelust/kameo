<?php
session_cache_limiter('nocache');
if(!isset($_SESSION)) 
	session_start();

header('Content-type: application/json');
header('WWW-Authenticate: Bearer realm="DefaultRealm"');
header('Expires: ' . gmdate('r', 0));

include '../globalfunctions.php';
//create and inclure error_messages.php file containing error messages to return as well as headers as for example: header('HTTP/1.1 401 Unauthorized error="invalid_token", error_description="The access token is invalid."');
include '../authentication.php';

//CHECK AUTH AND PERMS HERE:
$token = getBearerToken(); //Defined in authentication.php
if (authenticate($token))	//If token exist in databases
{
	$permissions = get_user_permissions($token); // Retrieve permissions of the user
        
	switch($_SERVER["REQUEST_METHOD"])
	{
		case 'GET':
            $action=isset($_GET['action']) ? $_GET['action'] : NULL;
            
            if($action == 'list'){         
                if(in_array("admin", $permissions, TRUE)){
                    header("HTTP/1.0 200 Ok");                    
                    include 'list_companies.php';
                }else{
                    error_message('401');
                }
            }
            
            if($action == 'graphic'){
                if(in_array("admin", $permissions, TRUE)){
                    header("HTTP/1.0 200 Ok");                    
                    include 'graphic_companies.php';
                }else{
                    error_message('401');
                }
            }
            
			//if(in_array("fleetmanager", $permissions, TRUE))	//If the array $permissions contains the "fleetmanager" permission
				/*if(!empty($_GET["myGETvar"]))
				{
					$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_ONLY);	//READ ONLY
					$mysqli->query("SELECT * FROM mytable");
					$mysqli->commit();
					echo json_encode($_GET["myGETvar"]);
				}*/
			break;
		case 'POST':
			//if(in_array("admin", $permissions, TRUE))	//If the array $permissions contains the "admin" permission
				/*if(!empty($_POST["myPOSTvar"]))
				{
					$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE); //READ/WRITE
					$mysqli->query("UPDATE whatever FROM mytable");
					$mysqli->commit();
					do_my_stuff();
				}*/
			break;
		default:
                    error_message('405');
			break;
	}
}else
    error_message('401');
?>