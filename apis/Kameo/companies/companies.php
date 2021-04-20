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
	if($action === 'retrieveCustommerBike'){
		if(get_user_permissions("admin", $token)){
			$idComp=isset($_GET['ID']) ? $_GET['ID'] : NULL;
			$sqlComp="SELECT * FROM companies where ID='$idComp'";
			$resultComp = mysqli_query($conn, $sqlComp);
			$rowComp = mysqli_fetch_assoc($resultComp);
			$company = $rowComp['INTERNAL_REFERENCE'];
			$response['user']=execSQL("SELECT customer_referential.NOM as name, customer_referential.PRENOM as firstName, customer_referential.EMAIL as email, customer_referential.PHONE as phone, BIKE_ID as bikeId FROM customer_bike_access, customer_referential where customer_referential.COMPANY=? AND customer_bike_access.EMAIL=customer_referential.EMAIL AND customer_bike_access.TYPE='personnel' AND customer_bike_access.STAANN !='D' and customer_referential.STAANN != 'D'", array('s', $company), false);
			if(is_null($response['user'])){
				$response['user']=array();
			}
			$response['bike']=execSQL("SELECT customer_bikes.ID as id, bike_catalog.BRAND as brand, bike_catalog.MODEL as model, customer_bikes.CONTRACT_TYPE as contract FROM customer_bikes, bike_catalog where customer_bikes.COMPANY=? AND customer_bikes.TYPE=bike_catalog.ID AND customer_bikes.STAANN !='D'", array('s', $company), false);
			if(is_null($response['user'])){
				$response['bike']=array();
			}
			echo json_encode($response);
			die;
		}else
			error_message('403');
	}else if($action === 'retrieve'){
		if(get_user_permissions(["fleetManager", "admin"], $token)){
			include 'get_company_details.php';
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
		}else
			error_message('403');
	}else if($action === 'listCafetariaCompanies'){
		if(get_user_permissions("admin", $token)){
				$result = $conn->query("SELECT c.COMPANY_NAME, (SELECT COUNT(*) FROM companies_orderable co WHERE co.INTERNAL_REFERENCE = c.INTERNAL_REFERENCE) AS NUM_OF_ORDERABLE, co.CAFETARIA, co.DISCOUNT, co.TVA_INCLUDED, co.CAFETERIA_TYPE, co.CAFETERIA_TYPES FROM companies c, conditions co WHERE c.INTERNAL_REFERENCE=co.COMPANY AND co.NAME = 'generic' AND c.STAANN != 'D'");
				echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));
				$result->close();
			}else
				error_message('403');
		}else if($action === 'getCompanyContacts'){
			if(get_user_permissions("admin", $token)){
				include 'get_company_contact.php';
			}else
				error_message('403');
		}else if($action === 'listUsers'){
			if(get_user_permissions("admin", $token)){
				$response=execSQL("SELECT NOM, PRENOM, EMAIL, PHONE FROM customer_referential WHERE COMPANY = ?", array('s', $_GET['company']), false);
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'retrieveOffer'){
			if(get_user_permissions("admin", $token)){
				$response=execSQL("SELECT * FROM offers WHERE ID=?", array('s', $_GET['ID']),false)[0];
				$response['item']=execSQL("SELECT * FROM offers_details WHERE OFFER_ID=?", array('s', $_GET['ID']),false);
				$i=0;
				if(!is_null($response['item'])){
					foreach($response['item'] as $item){
						if($item['ITEM_TYPE'] == 'box'){
								$resultat2=execSQL("SELECT * FROM boxes_catalog WHERE ID='?'", array('i', $_GET['ID']), false);
								$response['item'][$i]['model']=$resultat2['MODEL'];
						}else if($item['ITEM_TYPE'] == 'bike'){
								$resultat2=execSQL("SELECT * FROM bike_catalog WHERE ID=?", array('i', $_GET['ID']), false);
								$response['item'][$i]['brand']=$resultat2['BRAND'];
								$response['item'][$i]['model']=$resultat2['MODEL'];
						}else if($item['ITEM_TYPE'] == 'accessory'){
								$resultat2=execSQL("SELECT * FROM accessories_catalog WHERE ID=.", array('i', $_GET['ID']), false);
								$response['item'][$i]['brand']=$resultat2['BRAND'];
								$response['item'][$i]['model']=$resultat2['MODEL'];
						}
						$i++;
					}
				}else{
					$response['item']=array();
				}

				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'retrieveOffers'){
			if(get_user_permissions("admin", $token)){
				$response=execSQL("SELECT * FROM offers WHERE COMPANY=?", array('s', $_GET['company']),false);
				if($response == NULL){
					$response=array();
				}
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'graphic'){
			if(get_user_permissions("admin", $token)){
				include 'graphic_companies.php';
			}else
				error_message('403');
		}else if($action === 'getCompanyContacts'){
			if(get_user_permissions("admin", $token)){
				include 'get_company_contact.php';
			}else
			error_message('403');
		}else if($action === 'graphic'){
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
	}else if($action === 'addClient'){
		if(get_user_permissions("admin", $token)){
			include 'add_client.php';
		}else{
			error_message('403');
		}
	}else if($action === 'editClient'){
		if(get_user_permissions("admin", $token)){
			include 'update_company.php';
		}else{
			error_message('403');
		}
	}else if($action === 'editCompanyContact'){
		if(get_user_permissions("admin", $token)){
			include 'edit_company_contact.php';
		}else{
			error_message('403');
		}
	}else if($action === 'deleteContact'){
		if(get_user_permissions("admin", $token)){
			execSQL('DELETE FROM companies_contact WHERE ID = ?', array('i', $_POST['id']), true);
		}else{
			error_message('403');
		}
	}else if($action === 'addCondition'){
		if(get_user_permissions(["fleetManager", "admin"], $token)){
			include 'addCompanyCondition.php';
		}else{
			error_message('403');
		}
	}else if($action === 'updateCompanyConditions'){
		if(get_user_permissions(["fleetManager", "admin"], $token)){
			include 'updateCompanyConditions.php';
		}else{
			error_message('403');
		}
	}else if($action === 'addManualOffer'){
		if(get_user_permissions("admin", $token)){
			$id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
			$action = isset($_POST["action"]) ? $_POST["action"] : NULL;
			$requestor = isset($_POST["requestor"]) ? $_POST["requestor"] : NULL;
			$company = isset($_POST["company"]) ? $_POST["company"] : NULL;
			$title = isset($_POST["title"]) ? addslashes($_POST["title"]) : NULL;
			$description = isset($_POST["description"]) ? addslashes($_POST["description"]) : NULL;
			$status = isset($_POST["status"]) ? addslashes($_POST["status"]) : NULL;
			$type = isset($_POST["type"]) ? $_POST["type"] : NULL;
			$probability = isset($_POST["probability"]) ? $_POST["probability"] : NULL;
			$amount = isset($_POST["amount"]) ? $_POST["amount"] : NULL;
			$date = isset($_POST["date"]) ? date($_POST["date"]) : NULL;
			$start = isset($_POST["start"]) ? date($_POST["start"]) : NULL;
			$end = isset($_POST["end"]) ? date($_POST["end"]) : NULL;
			$margin = isset($_POST["margin"]) ? date($_POST["margin"]) : NULL;
			execSQL("INSERT INTO offers (HEU_MAJ, USR_MAJ, TITRE, DESCRIPTION, STATUS, PROBABILITY, TYPE, AMOUNT, MARGIN, DATE, START, END, COMPANY, STAANN)
			VALUES (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '')",
			array('ssssisdissss', $token, $title, $description, $status, $probability, $type, $amount, $margin, $date, $start, $end, $company), true);
			successMessage("SM0019");
		}else{
			error_message('403');
		}
	}else if($action === 'deleteOffer'){
		if(get_user_permissions("admin", $token)){
			$offerID=$_POST['offerID'];
      execSQL("DELETE FROM offers_details WHERE OFFER_ID=?", array('i', $offerID), true);
      execSQL("DELETE FROM offers WHERE ID=?", array('i', $offerID), true);
      successMessage("SM0003");
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
