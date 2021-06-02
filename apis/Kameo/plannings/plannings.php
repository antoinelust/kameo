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
	    $response['plannings'] = execSQL("SELECT DATE, SUM(CASE WHEN TYPE='commande' THEN 1 ELSE 0 END) as nombreCommande, SUM(CASE WHEN TYPE='entretien' THEN 1 ELSE 0 END) as nombreEntretiens FROM (SELECT substr(entretiens.DATE, 1, 10) as DATE, 'entretien' as TYPE FROM entretiens GROUP BY substr(entretiens.DATE, 1, 10)	UNION ALL SELECT substr(client_orders.ESTIMATED_DELIVERY_DATE, 1, 10) as DATE, 'commande' as TYPE FROM client_orders WHERE client_orders.STATUS != 'done') as tt WHERE DATE IS NOT NULL AND DATE != '0000-00-00' GROUP BY DATE ORDER BY DATE", array(), false);
	    echo json_encode($response);
	    die;
	  }else if ($action == "getPlanning"){
			$response['internalEntretiens'] = execSQL("SELECT tt.*,

				(CASE
				 WHEN tt.type='personnel' THEN (SELECT PHONE FROM customer_referential, customer_bike_access WHERE customer_referential.EMAIL=customer_bike_access.EMAIL AND customer_bike_access.BIKE_ID=tt.BIKE_ID LIMIT 1)
				 WHEN tt.type='partage' OR tt.type='vendu' THEN (SELECT companies_contact.PHONE FROM companies_contact, customer_bikes, companies WHERE customer_bikes.ID=tt.BIKE_ID AND companies.INTERNAL_REFERENCE=customer_bikes.COMPANY AND companies_contact.ID_COMPANY=companies.ID LIMIT 1)
				 ELSE 'undefined'
				 END
				) as PHONE,

				(CASE
				 WHEN tt.type='personnel' THEN (SELECT CONCAT(customer_referential.ADRESS, ' ', customer_referential.POSTAL_CODE, ' ', customer_referential.CITY) FROM customer_referential, customer_bike_access WHERE customer_referential.EMAIL=customer_bike_access.EMAIL AND customer_bike_access.BIKE_ID=tt.BIKE_ID LIMIT 1)
				 WHEN tt.type='partage' OR tt.type='vendu' THEN (SELECT CONCAT(companies.STREET, ' ', companies.ZIP_CODE, ' ', companies.TOWN) FROM customer_bikes, companies WHERE customer_bikes.ID=tt.BIKE_ID AND companies.INTERNAL_REFERENCE=customer_bikes.COMPANY LIMIT 1)
				 ELSE 'undefined'
				 END
				) as ADDRESS,

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
				(SELECT entretiens.ID, entretiens.BIKE_ID, entretiens.EXTERNAL_BIKE, entretiens.DATE, entretiens.STATUS, entretiens.OUT_DATE_PLANNED, entretiens.AVOID_BILLING, entretiens.INTERNAL_COMMENT, entretiens.COMMENT,

				(CASE
				 WHEN entretiens.EXTERNAL_BIKE=0 THEN
				 	CASE
				 	WHEN EXISTS (SELECT 1 FROM customer_bikes, customer_bike_access WHERE entretiens.BIKE_ID=customer_bikes.ID and customer_bikes.CONTRACT_TYPE in ('leasing', 'renting') AND customer_bike_access.TYPE='personnel' AND customer_bike_access.BIKE_ID=customer_bikes.ID) THEN 'personnel'
				 	WHEN EXISTS (SELECT 1 FROM customer_bikes WHERE entretiens.BIKE_ID=customer_bikes.ID and customer_bikes.CONTRACT_TYPE in ('leasing', 'renting')) AND NOT EXISTS (SELECT 1 FROM customer_bike_access WHERE customer_bike_access.TYPE='personnel' AND customer_bike_access.BIKE_ID=entretiens.BIKE_ID) THEN 'partage'
				 	WHEN EXISTS (SELECT 1 FROM customer_bikes WHERE entretiens.BIKE_ID=customer_bikes.ID and customer_bikes.CONTRACT_TYPE in ('selling')) THEN 'vendu'
				 	ELSE 'undefined'
				 	END
				 WHEN entretiens.EXTERNAL_BIKE=1 THEN 'externe'
				 ELSE 'undefined'
				 END) as type, aa.FRAME_REFERENCE
			FROM entretiens, customer_bikes as aa WHERE entretiens.DATE = ? and entretiens.BIKE_ID = aa.ID and entretiens.EXTERNAL_BIKE='0') as tt", array('s', $_GET['date']), false);


			$response['externalEntretiens'] = execSQL("SELECT tt.*,

				(SELECT companies_contact.PHONE FROM companies_contact, external_bikes WHERE external_bikes.ID=tt.BIKE_ID AND external_bikes.COMPANY_ID=companies_contact.ID_COMPANY LIMIT 1) as PHONE,
				(SELECT CONCAT(companies.STREET, ' ', companies.ZIP_CODE, ' ', companies.TOWN) FROM external_bikes, companies WHERE external_bikes.ID=tt.BIKE_ID AND companies.ID=external_bikes.COMPANY_ID LIMIT 1) as ADDRESS,
				(SELECT companies.COMPANY_NAME FROM companies, external_bikes WHERE external_bikes.COMPANY_ID=companies.ID AND external_bikes.ID=tt.BIKE_ID LIMIT 1) as COMPANY_NAME,
				(SELECT CONCAT(external_bikes.BRAND, ' - ', external_bikes.MODEL) FROM external_bikes WHERE external_bikes.ID=tt.BIKE_ID) as MODEL

				FROM
				(SELECT entretiens.ID, entretiens.BIKE_ID, entretiens.EXTERNAL_BIKE, entretiens.DATE, entretiens.STATUS, entretiens.OUT_DATE_PLANNED, entretiens.AVOID_BILLING, entretiens.INTERNAL_COMMENT, entretiens.COMMENT,

				(SELECT 'externe' FROM DUAL) as type, aa.FRAME_REFERENCE
			FROM entretiens, external_bikes as aa WHERE entretiens.DATE = ? and entretiens.BIKE_ID = aa.ID and entretiens.EXTERNAL_BIKE='1') as tt", array('s', $_GET['date']), false);


			$response['orders']=execSQL("SELECT client_orders.ID, client_orders.STATUS, companies.COMPANY_NAME,
				(CASE
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

				FROM client_orders, grouped_orders, companies WHERE grouped_orders.ID=client_orders.GROUP_ID AND grouped_orders.COMPANY_ID=companies.ID AND client_orders.ESTIMATED_DELIVERY_DATE=?", array('s', $_GET['date']), false);

			echo json_encode($response);
			die;
		}
		else
		error_message('405');
	break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
		error_message('405');
	break;
	default:
	error_message('405');
	break;
	}
	?>
