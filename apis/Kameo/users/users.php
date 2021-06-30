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

log_inputs($token);

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;
		if($action=="retrieve"){
			if(get_user_permissions("fleetManager", $token)){
				$email=$_GET['email'];
				$response['user']=execSQL("SELECT * FROM customer_referential where EMAIL=?", array('s',$email), false)[0];
				if(strpos($response['user']['ACCESS_RIGHTS'],'fleetManager') !== false){
		        $response['user']['fleetManager']='Y';
		    }else{
		        $response['user']['fleetManager']='N';
		    }
				$company=$response['user']['COMPANY'];
				$buildings = array();
				$buildings[]=execSQL("SELECT bb.BUILDING_REFERENCE as buildingCode, bb.BUILDING_FR as descriptionFR, 'true' as 'access' FROM customer_building_access aa, building_access bb WHERE aa.EMAIL=? and BUILDING_REFERENCE=aa.BUILDING_CODE and aa.STAANN!='D'", array('s', $email), false);
				$buildings[]=execSQL("SELECT bb.BUILDING_REFERENCE as buildingCode, bb.BUILDING_FR as descriptionFR, 'false' as 'access' FROM customer_building_access aa, building_access bb WHERE aa.EMAIL=? and BUILDING_REFERENCE=aa.BUILDING_CODE and aa.STAANN='D'", array('s', $email), false);
				$buildings[]=execSQL("SELECT BUILDING_REFERENCE as buildingCode, BUILDING_FR as descriptionFR, 'false' as 'access' FROM building_access WHERE COMPANY = ? AND not exists (select 1 from customer_building_access bb where bb.BUILDING_CODE=BUILDING_REFERENCE and bb.EMAIL=?)", array('ss', $company, $email), false);

				$response['building'] = array();

				foreach($buildings as $building) {
				    if(is_array($building)) {
				        $response['building'] = array_merge($response['building'], $building);
				    }
				}

				$bikes = array();
				$bikes[]=execSQL("SELECT bb.ID as bikeID, bb.FRAME_NUMBER, bb.MODEL as model, 'true' as access FROM customer_bike_access aa, customer_bikes bb WHERE aa.EMAIL=? and bb.ID=aa.BIKE_ID and aa.STAANN!='D' and aa.TYPE='partage' ORDER BY bb.ID", array('s', $email), false);
				$bikes[]=execSQL("SELECT bb.ID as bikeID, bb.FRAME_NUMBER, bb.MODEL as model, 'false' as access FROM customer_bike_access aa, customer_bikes bb WHERE aa.EMAIL=? and aa.BIKE_ID=bb.ID and (aa.STAANN='D' OR aa.STAANN is null) and aa.TYPE='partage'", array('s', $email), false);
				$bikes[]=execSQL("SELECT aa.ID as bikeID, FRAME_NUMBER, MODEL as model, 'false' as access FROM customer_bikes aa WHERE COMPANY = ? AND not exists (select 1 from customer_bike_access bb where bb.BIKE_ID=aa.ID and bb.EMAIL=? and (bb.TYPE='partage' OR bb.TYPE='personnel'))", array('ss', $company, $email), false);
				$response['bike'] = array();
				foreach($bikes as $bike) {
				    if(is_array($bike)) {
				        $response['bike'] = array_merge($response['bike'], $bike);
				    }
				}

				echo json_encode($response);
			  die;
			}
		}else if($action=='list'){
			$email=isset($_POST['email']) ? $_POST['email'] : NULL;
			$company=isset($_POST['company']) ? $_POST['company'] : (isset($_GET['company']) ? $_GET['company'] : NULL);
			$companyID=isset($_POST['companyID']) ? $_POST['companyID'] : (isset($_GET['companyID']) ? $_GET['companyID'] : NULL);

			$companyTOKEN=execSQL("SELECT COMPANY from customer_referential WHERE TOKEN = ?", array('s', $token), false)[0]['COMPANY'];
			if($companyTOKEN != 'KAMEO'){
				$company=NULL;
				$companyID=NULL;
				$company = execSQL("SELECT COMPANY from customer_referential WHERE TOKEN = ?", array('s', $token), false)[0]['COMPANY'];
			}

			if($company){
				$response['users'] = execSQL("SELECT NOM AS name, PRENOM AS firstName, PHONE as phone, EMAIL AS email, STAANN AS staann FROM customer_referential WHERE COMPANY = ?", array('s', $company), false);
			}else{
				$response['users'] = execSQL("SELECT NOM AS name, PRENOM AS firstName, PHONE as phone, EMAIL AS email, customer_referential.STAANN AS staann FROM customer_referential, companies WHERE companies.ID=? and companies.INTERNAL_REFERENCE=customer_referential.COMPANY", array('i', $companyID), false);
			}
			$response['usersNumber']=count($response['users']);
			$response['response']="success";
			log_output($response);
			echo json_encode($response);
			die;
		}else
			error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;

		if($action === 'deleteUserAdmin'){
			if(get_user_permissions("admin", $token)){
					execSQL("UPDATE customer_referential SET HEU_MAJ=CURRENT_TIME, USR_MAJ=?, STAANN = 'D' WHERE EMAIL = ?", array('ss', $token, $_POST['email']), true);
					execSQL("UPDATE customer_bike_access SET TIMESTAMP=CURRENT_TIME, USR_MAJ=?, STAANN = 'D' WHERE EMAIL = ?", array('ss', $token, $_POST['email']), true);
					execSQL("UPDATE customer_building_access SET TIMESTAMP=CURRENT_TIME, USR_MAJ=?, STAANN = 'D' WHERE EMAIL = ?", array('ss', $token, $_POST['email']), true);
					successMessage("SM0030");
			}else
				error_message('403');
		}else if($action === 'update'){
			if(get_user_permissions(["fleetManager", "admin"], $token)){
				include 'updateUser.php';
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
