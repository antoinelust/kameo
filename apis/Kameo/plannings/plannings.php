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
		if($action === 'retrieve'){
		}else if ($action == "listPlannings"){
			$response=array();
	    $response['plannings'] = execSQL("SELECT tt.DATE, SUM(CASE WHEN TYPE='commande' THEN 1 ELSE 0 END) as nombreCommande, SUM(CASE WHEN TYPE='entretien' THEN 1 ELSE 0 END) as nombreEntretiens, (CASE WHEN EXISTS (SELECT 1 FROM plannings WHERE plannings.DATE=tt.DATE) THEN 'confirmed' ELSE 'TO DO' END) as status FROM (SELECT substr(entretiens.DATE, 1, 10) as DATE, 'entretien' as TYPE FROM entretiens WHERE entretiens.ADDRESS != '8 Rue de la brasserie, 4000 Liège'	UNION ALL SELECT substr(client_orders.ESTIMATED_DELIVERY_DATE, 1, 10) as DATE, 'commande' as TYPE FROM client_orders WHERE client_orders.STATUS != 'done' AND client_orders.DELIVERY_ADDRESS != '8 Rue de la brasserie, 4000 Liège') as tt WHERE DATE IS NOT NULL AND DATE != '0000-00-00' AND DATE >= ? AND DATE <= ? GROUP BY DATE ORDER BY DATE ", array('ss', $_GET['dateStart'], $_GET['dateEnd']), false);
	    echo json_encode($response);
	    die;
	  }else if ($action == "getPlanning"){
			$entretien=execSQL("SELECT * FROM plannings WHERE DATE=?", array('s', $_GET['date']), false);
			if(count($entretien)==0){
				$response['status']="new";
				$response['internalEntretiens'] = execSQL("SELECT tt.*,
					(CASE
					 WHEN tt.type='personnel' THEN (SELECT PHONE FROM customer_referential, customer_bike_access WHERE customer_referential.EMAIL=customer_bike_access.EMAIL AND customer_bike_access.BIKE_ID=tt.BIKE_ID LIMIT 1)
					 WHEN tt.type='partage' OR tt.type='vendu' THEN (SELECT companies_contact.PHONE FROM companies_contact, customer_bikes, companies WHERE customer_bikes.ID=tt.BIKE_ID AND companies.INTERNAL_REFERENCE=customer_bikes.COMPANY AND companies_contact.ID_COMPANY=companies.ID LIMIT 1)
					 ELSE 'undefined'
					 END
					) as PHONE,

					(CASE
						WHEN EXISTS (SELECT 1 FROM customer_bike_access WHERE customer_bike_access.BIKE_ID=tt.BIKE_ID and customer_bike_access.TYPE = 'personnel' AND customer_bike_access.STAANN != 'D') THEN (SELECT customer_bike_access.EMAIL FROM customer_bike_access WHERE customer_bike_access.BIKE_ID=tt.BIKE_ID and customer_bike_access.TYPE = 'personnel' AND customer_bike_access.STAANN != 'D' LIMIT 1)
						ELSE 'partage'
						END
					) as EMAIL,

					(CASE
					 WHEN tt.type='personnel' OR tt.type='partage' OR tt.type='vendu' THEN (SELECT companies.COMPANY_NAME FROM companies, customer_bikes WHERE customer_bikes.COMPANY=companies.INTERNAL_REFERENCE AND customer_bikes.ID=tt.BIKE_ID LIMIT 1)
					 ELSE 'undefined'
					 END
					) as COMPANY_NAME,

					(CASE
					 WHEN tt.type='personnel' OR tt.type='partage' OR tt.type='vendu' THEN (SELECT CONCAT(bike_catalog.BRAND, ' - ', bike_catalog.MODEL) FROM bike_catalog, customer_bikes WHERE customer_bikes.TYPE=bike_catalog.ID AND customer_bikes.ID=tt.BIKE_ID)
					 ELSE 'undefined'
					 END
				 ) as MODEL

					FROM
					(SELECT entretiens.ID, entretiens.BIKE_ID, entretiens.EXTERNAL_BIKE, entretiens.DATE, entretiens.STATUS, entretiens.OUT_DATE_PLANNED, entretiens.AVOID_BILLING, entretiens.INTERNAL_COMMENT, entretiens.COMMENT, entretiens.ADDRESS,

					(CASE
					 WHEN entretiens.EXTERNAL_BIKE=0 THEN
					 	CASE
					 	WHEN EXISTS (SELECT 1 FROM customer_bikes, customer_bike_access WHERE entretiens.BIKE_ID=customer_bikes.ID and customer_bikes.CONTRACT_TYPE in ('leasing', 'renting') AND customer_bike_access.TYPE='personnel' AND customer_bike_access.STAANN != 'D' AND customer_bike_access.BIKE_ID=customer_bikes.ID) THEN 'personnel'
					 	WHEN EXISTS (SELECT 1 FROM customer_bikes WHERE entretiens.BIKE_ID=customer_bikes.ID and customer_bikes.CONTRACT_TYPE in ('leasing', 'renting')) AND NOT EXISTS (SELECT 1 FROM customer_bike_access WHERE customer_bike_access.TYPE='personnel' AND customer_bike_access.BIKE_ID=entretiens.BIKE_ID) THEN 'partage'
					 	WHEN EXISTS (SELECT 1 FROM customer_bikes WHERE entretiens.BIKE_ID=customer_bikes.ID and customer_bikes.CONTRACT_TYPE in ('selling')) THEN 'vendu'
					 	ELSE 'undefined'
					 	END
					 WHEN entretiens.EXTERNAL_BIKE=1 THEN 'externe'
					 ELSE 'undefined'
					 END) as type, aa.FRAME_REFERENCE
				FROM entretiens, customer_bikes as aa WHERE entretiens.DATE = ? and entretiens.BIKE_ID = aa.ID and entretiens.EXTERNAL_BIKE='0' and entretiens.ADDRESS != '8 Rue de la brasserie, 4000 Liège') as tt", array('s', $_GET['date']), false);


				$response['externalEntretiens'] = execSQL("SELECT tt.*,

					(SELECT companies_contact.PHONE FROM companies_contact, external_bikes WHERE external_bikes.ID=tt.BIKE_ID AND external_bikes.COMPANY_ID=companies_contact.ID_COMPANY LIMIT 1) as PHONE,
					(SELECT companies.COMPANY_NAME FROM companies, external_bikes WHERE external_bikes.COMPANY_ID=companies.ID AND external_bikes.ID=tt.BIKE_ID LIMIT 1) as COMPANY_NAME,
					(SELECT CONCAT(external_bikes.BRAND, ' - ', external_bikes.MODEL) FROM external_bikes WHERE external_bikes.ID=tt.BIKE_ID) as MODEL

					FROM
					(SELECT entretiens.ID, entretiens.BIKE_ID, entretiens.EXTERNAL_BIKE, entretiens.DATE, entretiens.STATUS, entretiens.OUT_DATE_PLANNED, entretiens.AVOID_BILLING, entretiens.INTERNAL_COMMENT, entretiens.COMMENT, entretiens.ADDRESS,

					(SELECT 'externe' FROM DUAL) as type, aa.FRAME_REFERENCE
				FROM entretiens, external_bikes as aa WHERE entretiens.DATE = ? and entretiens.BIKE_ID = aa.ID and entretiens.EXTERNAL_BIKE='1' and entretiens.ADDRESS != '8 Rue de la brasserie, 4000 Liège') as tt", array('s', $_GET['date']), false);


				$response['orders']=execSQL("SELECT client_orders.ID, client_orders.STATUS, companies.COMPANY_NAME,
					(CASE
						WHEN (client_orders.DELIVERY_ADDRESS IS NOT NULL) THEN (SELECT client_orders.DELIVERY_ADDRESS)
					 	WHEN grouped_orders.EMAIL = '' THEN (SELECT CONCAT(companies.STREET, ', ', companies.ZIP_CODE, ' ', companies.TOWN) FROM companies WHERE grouped_orders.COMPANY_ID=companies.ID LIMIT 1)
					 	ELSE (SELECT CONCAT(customer_referential.ADRESS, ', ', customer_referential.POSTAL_CODE, ' ', customer_referential.CITY) FROM customer_referential WHERE customer_referential.EMAIL=grouped_orders.EMAIL)
					END) as ADDRESS,
					(CASE
					 WHEN grouped_orders.EMAIL = '' THEN (SELECT companies_contact.PHONE FROM companies, companies_contact WHERE grouped_orders.COMPANY_ID=companies_contact.ID_COMPANY AND companies_contact.TYPE='contact' LIMIT 1)
					 ELSE (SELECT PHONE FROM customer_referential WHERE customer_referential.EMAIL=grouped_orders.EMAIL)
					END) as PHONE,
					(CASE
						WHEN EXISTS (SELECT 1 FROM customer_bikes WHERE customer_bikes.ID=client_orders.BIKE_ID) THEN (SELECT customer_bikes.FRAME_REFERENCE FROM customer_bikes WHERE customer_bikes.ID=client_orders.BIKE_ID)
						ELSE 'undefined'
					END) as FRAME_REFERENCE,
					(SELECT EMAIL FROM grouped_orders WHERE grouped_orders.ID=client_orders.GROUP_ID) as EMAIL

					FROM client_orders, grouped_orders, companies WHERE grouped_orders.ID=client_orders.GROUP_ID AND grouped_orders.COMPANY_ID=companies.ID AND client_orders.DELIVERY_ADDRESS != '8 Rue de la brasserie, 4000 Liège' AND client_orders.ESTIMATED_DELIVERY_DATE=?", array('s', $_GET['date']), false);
			}else{
				$response['status']='confirmed';
				$response['steps']=execSQL("SELECT * FROM plannings WHERE plannings.DATE=? ORDER BY STEP", array('s', $_GET['date']), false);
				$i=0;
				foreach($response['steps'] as $step){
					if($step['ITEM_TYPE']=='internalMaintenance'){
						$resultat=execSQL("SELECT COMPANY_NAME, customer_bikes.FRAME_REFERENCE, entretiens.INTERNAL_COMMENT, entretiens.COMMENT,
							(CASE WHEN EXISTS (SELECT 1 FROM customer_bike_access WHERE customer_bike_access.BIKE_ID=customer_bikes.ID AND customer_bike_access.TYPE='personnel' AND customer_bike_access.STAANN != 'D') THEN (SELECT customer_referential.PHONE FROM customer_referential, customer_bike_access WHERE customer_bike_access.BIKE_ID=customer_bikes.ID AND customer_referential.EMAIL=customer_bike_access.EMAIL AND customer_bike_access.TYPE='personnel' AND customer_bike_access.STAANN != 'D' LIMIT 1) ELSE (SELECT 'Velo partage' FROM DUAL) END) as PHONE,
							(CASE WHEN EXISTS (SELECT 1 FROM customer_bike_access WHERE customer_bike_access.BIKE_ID=customer_bikes.ID AND customer_bike_access.TYPE='personnel' AND customer_bike_access.STAANN != 'D') THEN (SELECT CONCAT(customer_referential.PRENOM, ' ', customer_referential.NOM) FROM customer_referential, customer_bike_access WHERE customer_bike_access.BIKE_ID=customer_bikes.ID AND customer_referential.EMAIL=customer_bike_access.EMAIL AND customer_bike_access.TYPE='personnel' AND customer_bike_access.STAANN != 'D' LIMIT 1) ELSE (SELECT 'Velo partage' FROM DUAL) END) as NAME,
							(CASE WHEN EXISTS (SELECT 1 FROM customer_bike_access WHERE customer_bike_access.BIKE_ID=customer_bikes.ID AND customer_bike_access.TYPE='personnel' AND customer_bike_access.STAANN != 'D') THEN (SELECT customer_bike_access.EMAIL FROM customer_bike_access WHERE customer_bike_access.BIKE_ID=customer_bikes.ID AND customer_bike_access.TYPE='personnel' AND customer_bike_access.STAANN != 'D' LIMIT 1) ELSE (SELECT 'Velo partage' FROM DUAL) END) as EMAIL
							 FROM customer_bikes, companies, entretiens WHERE customer_bikes.COMPANY=companies.INTERNAL_REFERENCE AND customer_bikes.ID=entretiens.BIKE_ID AND entretiens.ID=?", array('i', $step['ITEM_ID']), false)[0];
						$response['steps'][$i]["COMPANY_NAME"]=$resultat['COMPANY_NAME'];
						$response['steps'][$i]["EMAIL"]=$resultat['EMAIL'];
						$response['steps'][$i]["FRAME_REFERENCE"]=$resultat['FRAME_REFERENCE'];
						$response['steps'][$i]["COMMENT"]=$resultat['COMMENT'];
						$response['steps'][$i]["INTERNAL_COMMENT"]=$resultat['INTERNAL_COMMENT'];
						$response['steps'][$i]["PHONE"]=$resultat['PHONE'];
						$response['steps'][$i]["NAME"]=$resultat['NAME'];
					}
					if($step['ITEM_TYPE']=='order'){
						$resultat=execSQL("SELECT COMPANY_NAME, companies.INTERNAL_REFERENCE,
							(CASE WHEN grouped_orders.EMAIL != '' AND grouped_orders.EMAIL IS NOT NULL THEN (SELECT customer_referential.PHONE FROM customer_referential WHERE customer_referential.EMAIL=grouped_orders.EMAIL LIMIT 1) END) as PHONE,
							(CASE WHEN grouped_orders.EMAIL != '' AND grouped_orders.EMAIL IS NOT NULL THEN (SELECT customer_referential.NOM FROM customer_referential WHERE customer_referential.EMAIL=grouped_orders.EMAIL LIMIT 1) END) as NAME,
							(CASE WHEN grouped_orders.EMAIL != '' AND grouped_orders.EMAIL IS NOT NULL THEN grouped_orders.EMAIL END) as EMAIL
							 FROM companies, client_orders, grouped_orders WHERE companies.ID=grouped_orders.COMPANY_ID AND client_orders.ID=? AND grouped_orders.ID=client_orders.GROUP_ID", array('i', $step['ITEM_ID']), false)[0];
						$response['steps'][$i]["COMPANY_NAME"]=$resultat['COMPANY_NAME'];
						$response['steps'][$i]["EMAIL"]=$resultat['EMAIL'];
						$response['steps'][$i]["FRAME_REFERENCE"]=$resultat['FRAME_REFERENCE'];
						$response['steps'][$i]["PHONE"]=$resultat['PHONE'];
						$response['steps'][$i]["NAME"]=$resultat['NAME'];
					}


					$i++;

				}
				echo json_encode($response);
				die;
			}

			echo json_encode($response);
			die;
		}
		else
		error_message('405');
	break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
		if($action=='add'){
			$response['response']='success';
			if(isset($_POST['startHour'])){
				$step=0;
				$type='startAddress';
				$movingTime=0;
				$executionTime=0;
				$status='confirmed';
				$itemID=0;
				$j=0;
				execSQL("INSERT INTO plannings (USR_MAJ, DATE, ADDRESS, ITEM_TYPE, ITEM_ID, STEP, MOVING_TIME, EXECUTION_TIME, PLANNED_START_HOUR, PLANNED_END_HOUR, STATUS) VALUES (?,?,?,?,?,?,?,?,?,?,?)", array('ssssiiiisss', $token, $_POST['date'], $_POST['startAddress'], $type, $itemID, $step, $movingTime, $executionTime, $_POST['startHourTour'], $_POST['startHourTour'], $status), true);
				if(isset($_POST['address'])){
					for ($i = 0; $i < count($_POST['address']); $i++) {
						$step=$i+1;
						$type=$_POST['type'][$i];
						$movingTime=$_POST['deplacement'][$i];
						$executionTime=$_POST['execution'][$i];
						if($type=='additionalStep'){
							$itemID=0;
							$description=$_POST['description'][$j];
							$j++;
						}else{
							$itemID=$_POST['id'][$i-$j];
							$description=NULL;
						}
						$status='confirmed';
						$startHour=$_POST['startHour'][$i];
						$endHour=$_POST['endHour'][$i];
						$address=$_POST['address'][$i];
						execSQL("INSERT INTO plannings (USR_MAJ, DATE, ADDRESS, ITEM_TYPE, ITEM_ID, STEP, MOVING_TIME, EXECUTION_TIME, PLANNED_START_HOUR, PLANNED_END_HOUR, STATUS, DESCRIPTION) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)", array('ssssiiiissss', $token, $_POST['date'], $address, $type, $itemID, $step, $movingTime, $executionTime, $startHour, $endHour, $status, $description), true);
					}
				}
				$step=$i+2;
				$type='endAddress';
				$movingTime=$_POST['endPointDeplacement'];
				$executionTime=0;
				$status='confirmed';
				$itemID=0;
				execSQL("INSERT INTO plannings (USR_MAJ, DATE, ADDRESS, ITEM_TYPE, ITEM_ID, STEP, MOVING_TIME, EXECUTION_TIME, PLANNED_START_HOUR, PLANNED_END_HOUR, STATUS) VALUES (?,?,?,?,?,?,?,?,?,?,?)", array('ssssiiiisss', $token, $_POST['date'], $_POST['endAddress'], $type, $itemID, $step, $movingTime, $executionTime, $_POST['endHourTour'], $_POST['endHourTour'], $status), true);

			}
			echo json_encode($response);
			die;
		}else if($action=="delete"){
			if(get_user_permissions("admin", $token)){
				execSQL("DELETE FROM plannings WHERE DATE=?", array('s', $_POST['date']), true);
				echo json_encode("success");
				die;
			}else {
				error_message('403');
			}
		}else if($action=="confirmTask"){

			execSQL("UPDATE plannings SET status='done', REAL_START_HOUR=?, REAL_END_HOUR=? WHERE ID=?", array('ssi', ($_POST['arrival'] != '') ? $_POST['arrival'] : NULL, ($_POST['departure'] != '') ? $_POST['departure'] : NULL, $_POST['id']), true);
			$response['message']="Etape validée";
			echo json_encode($response);
			die;
		}else
			error_message('405');
	break;
	default:
	error_message('405');
	break;
	}
	?>
