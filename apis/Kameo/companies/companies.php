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
include __DIR__ .'/../connexion.php';

$token = getBearerToken();

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
	$action=isset($_GET['action']) ? $_GET['action'] : NULL;

	if($action === 'retrieve'){
		if(get_user_permissions("admin", $token)){
			include 'get_company_details.php';
		}else{
			error_message('403');
		}
	}

	else if($action === 'retrieveCustommerBike'){
		if(get_user_permissions("admin", $token)){
			$idComp=isset($_GET['ID']) ? $_GET['ID'] : NULL;
			$sqlComp="SELECT * FROM companies where ID='$idComp'";
			$resultComp = mysqli_query($conn, $sqlComp);
			$rowComp = mysqli_fetch_assoc($resultComp);
			$company = $rowComp['INTERNAL_REFERENCE'];

			$sql="SELECT * FROM customer_referential where COMPANY='$company' AND STAANN != 'D'";

			if ($conn->query($sql) === FALSE) {
				$response = array ('response'=>'error', 'message'=> $conn->error);
				echo json_encode($response);
				die;
			}
			$result = mysqli_query($conn, $sql);
			$i=0;
			while($row = mysqli_fetch_array($result)){
				$tempmail = $row['EMAIL'];
				$sqlBikeAccess="SELECT * FROM customer_bike_access where EMAIL='$tempmail' AND TYPE='personnel'";
				$resultBikeAccess = mysqli_query($conn, $sqlBikeAccess);
				$length = $resultBikeAccess->num_rows;
				$rowBikeAccess = mysqli_fetch_assoc($resultBikeAccess);
				if($length>0){
					$response['user'][$i]['name']=$row['NOM'];
					$response['user'][$i]['firstName']=$row['PRENOM'];
					$response['user'][$i]['email']=$row['EMAIL'];
					$response['user'][$i]['phone']=$row['PHONE'];
					$response['user'][$i]['bikeId']=$rowBikeAccess['BIKE_ID'];
					$i++;
				}

			}
			$response['userNumber']=$i;

			echo json_encode($response);
		}else{
			error_message('403');
		}
	}else if($action === 'list'){
		if(get_user_permissions("admin", $token)){
			include 'list_companies.php';
		}else{
			error_message('403');
		}
	}else if($action=="listMinimal"){
		if(get_user_permissions("admin", $token)){
			$response['company']=execSQL("SELECT c.ID, COMPANY_NAME AS companyName, INTERNAL_REFERENCE AS internalReference FROM companies c WHERE STAANN != 'D' ORDER BY COMPANY_NAME", array(), false);
			$response['response']="success";
			echo json_encode($response);
			die;
		}else{
			error_message('403');
		}
	}else if($action === 'listCafetariaCompanies'){
		if(get_user_permissions("admin", $token)){
			if ($result = $conn->query("SELECT c.COMPANY_NAME, (SELECT COUNT(*) FROM companies_orderable co WHERE co.INTERNAL_REFERENCE = c.INTERNAL_REFERENCE) AS NUM_OF_ORDERABLE, co.CAFETARIA, co.DISCOUNT, co.TVA_INCLUDED, co.CAFETERIA_TYPE, co.CAFETERIA_TYPES FROM companies c, conditions co WHERE c.INTERNAL_REFERENCE=co.COMPANY AND co.NAME = 'generic' AND c.STAANN != 'D'")) {
				echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));
				$result->close();
			}
		}else
		error_message('403');
	}else if($action === 'getCompanyContacts'){
		if(get_user_permissions("admin", $token)){
			include 'get_company_contact.php';
		}else
		error_message('403');
	}
	else if($action === 'graphic'){
		if(get_user_permissions("admin", $token)){
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
		if(get_user_permissions("admin", $token)){
			include 'add_company_contact.php';
		}else{
			error_message('403');
		}
	}else if($action === 'editCompanyContact'){
		if(get_user_permissions("admin", $token)){
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
