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
		if(get_user_permissions("admin", $token)){
			include 'retrieveMaintenance.php';
		}
	}else if ($action == "listAllMaintenances") {
    $response = array ();
    $date_start = new DateTime($_GET['dateStart']);
    $date_start_string=$date_start->format('Y-m-d');

    $date_end = new DateTime($_GET['dateEnd']);
    $date_end_string=$date_end->format('Y-m-d');

    $response['maintenance'] = execSQL("SELECT * FROM
      (SELECT entretiens.ID AS id, entretiens.DATE AS date, entretiens.OUT_DATE_PLANNED AS OUT_DATE_PLANNED, entretiens.STATUS AS status,
         COMMENT AS comment, customer_bikes.FRAME_NUMBER AS frame_number, customer_bikes.COMPANY AS company, MODEL AS model, (CASE WHEN customer_bikes.ADDRESS != '' THEN customer_bikes.ADDRESS ELSE companies.STREET END) as bikeAddress,
         FRAME_REFERENCE AS frame_reference, customer_bikes.ID AS bike_id,customer_referential.PHONE AS phone, customer_referential.ADRESS AS street, customer_referential.POSTAL_CODE AS zip_code,
         customer_referential.CITY AS town, customer_bike_access.TYPE AS type, customer_bike_access.EMAIL AS email
         FROM entretiens
         INNER JOIN customer_bikes ON customer_bikes.ID = entretiens.BIKE_ID
         INNER JOIN companies ON companies.INTERNAL_REFERENCE = customer_bikes.COMPANY
         INNER JOIN customer_bike_access ON customer_bike_access.BIKE_ID = customer_bikes.ID AND customer_bike_access.TYPE='personnel'
         INNER JOIN customer_referential ON customer_referential.EMAIL=customer_bike_access.EMAIL
         WHERE entretiens.DATE >= ? AND entretiens.DATE <= ? AND entretiens.EXTERNAL_BIKE=0
       UNION
       SELECT entretiens.ID AS id, entretiens.DATE AS date,entretiens.OUT_DATE_PLANNED AS OUT_DATE_PLANNED, entretiens.STATUS AS status,
          COMMENT AS comment, customer_bikes.FRAME_NUMBER AS frame_number, customer_bikes.COMPANY AS company, MODEL AS model, (CASE WHEN customer_bikes.ADDRESS != '' THEN customer_bikes.ADDRESS ELSE companies.STREET END) as bikeAddress,
          FRAME_REFERENCE AS frame_reference, customer_bikes.ID AS bike_id, (SELECT PHONE from companies_contact WHERE ID_COMPANY=companies.ID  AND TYPE='contact' LIMIT 1) AS phone, companies.STREET AS street, companies.ZIP_CODE AS zip_code,
          companies.TOWN AS town, 'partage' AS type, 'N/A' AS email
          FROM entretiens
          INNER JOIN customer_bikes ON customer_bikes.ID = entretiens.BIKE_ID
          INNER JOIN companies ON companies.INTERNAL_REFERENCE = customer_bikes.COMPANY
          WHERE entretiens.DATE >= ? AND entretiens.DATE <= ? AND NOT EXISTS (SELECT 1 from customer_bike_access WHERE customer_bike_access.BIKE_ID = customer_bikes.ID AND customer_bike_access.TYPE='personnel') and EXTERNAL_BIKE=0
        UNION
        SELECT entretiens.ID AS id, entretiens.DATE AS date,entretiens.OUT_DATE_PLANNED AS OUT_DATE_PLANNED, entretiens.STATUS AS status,
           COMMENT AS comment, 'External Bike' AS frame_number, companies.INTERNAL_REFERENCE AS company, external_bikes.MODEL AS model, CONCAT(companies.STREET, ', ', companies.ZIP_CODE, ' ', companies.TOWN) as bikeAddress,
           external_bikes.FRAME_REFERENCE AS frame_reference, external_bikes.ID AS bike_id, (SELECT PHONE from companies_contact WHERE ID_COMPANY=companies.ID AND TYPE='contact' LIMIT 1) AS phone, companies.STREET AS street, companies.ZIP_CODE AS zip_code,
           companies.TOWN AS town, 'external' AS type, 'N/A' AS email
           FROM entretiens
           INNER JOIN external_bikes ON external_bikes.ID = entretiens.BIKE_ID
           INNER JOIN companies ON companies.ID = external_bikes.COMPANY_ID
           WHERE entretiens.DATE >= ? AND entretiens.DATE <= ? AND EXTERNAL_BIKE=1
      ) as tt
      GROUP BY id
      ORDER BY date", array('ssssss', $date_start_string, $date_end_string, $date_start_string, $date_end_string, $date_start_string, $date_end_string), false);
    $response['response'] = 'success';


    echo json_encode($response);
    die;
  }else if($action === 'list'){
		if(get_user_permissions("admin", $token)){
			if(isset($_GET['company'])){
				$response['internalMaintenances']=execSQL("SELECT entretiens.ID, entretiens.DATE, bike_catalog.BRAND, bike_catalog.MODEL FROM entretiens, customer_bikes, bike_catalog WHERE entretiens.STATUS='DONE' AND BIKE_ID=customer_bikes.ID  AND customer_bikes.TYPE=bike_catalog.ID AND customer_bikes.COMPANY=? ORDER BY entretiens.ID DESC", array('s', $_GET['company']), false);

				$response['externalMaintenances']=execSQL("SELECT entretiens.ID, entretiens.DATE, external_bikes.BRAND, external_bikes.MODEL FROM entretiens, external_bikes WHERE entretiens.STATUS='DONE' AND BIKE_ID=external_bikes.ID  AND external_bikes.COMPANY_ID=(SELECT ID FROM companies WHERE INTERNAL_REFERENCE=?) ORDER BY entretiens.ID DESC", array('s', $_GET['company']), false);
				if(is_null($response['internalMaintenances'])){
					$response['internalMaintenances']=array();
				}
				if(is_null($response['externalMaintenances'])){
					$response['externalMaintenances']=array();
				}
				echo json_encode($response);
				die;
			}else{
				echo json_encode(execSQL("SELECT * FROM entretiens WHERE BIKE_ID IN (SELECT ID FROM customer_bikes WHERE COMPANY = (SELECT COMPANY FROM customer_referential WHERE TOKEN = ?)) ORDER BY ID DESC", array('s', $token), false));
				die;
			}
		}
	}else if($action === 'listServices'){
		if(get_user_permissions("admin", $token)){
			echo json_encode(execSQL("SELECT * FROM services_entretiens WHERE CATEGORY = ? ORDER BY DESCRIPTION", array('s', $_GET['category']), false));
			die;
		}
	}else if($action === 'listCategories'){
		if(get_user_permissions("admin", $token)){
			echo json_encode(execSQL("SELECT CATEGORY FROM services_entretiens GROUP BY CATEGORY ORDER BY CATEGORY", array(), false));
			die;
		}
	}else
	error_message('405');
	break;
	case 'POST':
	$action=isset($_POST['action']) ? $_POST['action'] : NULL;
	if($action === 'add'){
		if(get_user_permissions("admin", $token)){
			$id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
			$user = isset($_POST["user"]) ? $_POST["user"] : NULL;
			$date = isset($_POST["dateMaintenance"]) ? date('Y-m-d',strtotime($_POST["dateMaintenance"])): NULL;
			$status = isset($_POST["status"]) ? $_POST["status"] : NULL;
			$comment = isset($_POST["comment"]) ? addslashes($_POST["comment"]) : NULL;
			$internalComment = isset($_POST["internalComment"]) ? addslashes($_POST["internalComment"]) : NULL;
			$bike_id = isset($_POST["velo"]) ? $_POST["velo"] : NULL;
			$external = isset($_POST["external"]) ? $_POST["external"] : NULL;
			$outDatePlanned = isset($_POST["dateOutPlanned"]) ? $_POST["dateOutPlanned"] : NULL;
			if($status=='DONE'){
				execSQL("INSERT INTO entretiens (HEU_MAJ,END_DATE_MAINTENANCE, USR_MAJ, BIKE_ID, EXTERNAL_BIKE, DATE, STATUS, COMMENT, INTERNAL_COMMENT, NR_ENTR,OUT_DATE_PLANNED ) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, 1, ?)", array('siisssss', $user, $bike_id, $external, $date, $status, $comment, $internalComment,$outDatePlanned), true);
			}
			else if($status=='DELIVERED_TO_CLIENT'){
				execSQL("INSERT INTO entretiens (HEU_MAJ,OUT_DATE, USR_MAJ, BIKE_ID, EXTERNAL_BIKE, DATE, STATUS, COMMENT, INTERNAL_COMMENT, NR_ENTR, OUT_DATE_PLANNED ) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, 1,? )", array('siisssss', $user, $bike_id, $external, $date, $status, $comment, $internalComment,$outDatePlanned), true);
			}
			else{
				execSQL("INSERT INTO entretiens (HEU_MAJ, USR_MAJ, BIKE_ID, EXTERNAL_BIKE, DATE, STATUS, COMMENT, INTERNAL_COMMENT, NR_ENTR, OUT_DATE_PLANNED ) VALUES (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, 1, ?)", array('siisssss', $user, $bike_id, $external, $date, $status, $comment, $internalComment, $outDatePlanned), true);
			}
			$response=array('response'=>"success", "message"=>"Entretien ajouté avec succès");
			echo json_encode($response);
			die;
		}else
		error_message('403');
	}if($action === 'update'){
		if(get_user_permissions("admin", $token)){
			$id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
			$user = isset($_POST["user"]) ? $_POST["user"] : NULL;
			$date = isset($_POST["dateMaintenance"]) ? date('Y-m-d',strtotime($_POST["dateMaintenance"])): NULL;
			$status = isset($_POST["status"]) ? $_POST["status"] : NULL;
			$comment = isset($_POST["comment"]) ? $_POST["comment"] : NULL;
			$internalComment = isset($_POST["internalComment"]) ? $_POST["internalComment"] : NULL;
			$bike_id = isset($_POST["velo"]) ? $_POST["velo"] : NULL;
			$outDatePlanned = isset($_POST["dateOutPlanned"]) ? $_POST["dateOutPlanned"] : NULL;

			if($status=='DONE'){
				$sql =execSQL("UPDATE entretiens SET USR_MAJ = ?, HEU_MAJ = CURRENT_TIMESTAMP, END_DATE_MAINTENANCE =CURRENT_TIMESTAMP , DATE = ?, STATUS = ?, COMMENT = ?, INTERNAL_COMMENT=?,OUT_DATE_PLANNED=? WHERE ID = ?;", array('ssssssi', $token, $date, $status, $comment, $internalComment,$outDatePlanned, $id), true);
			}
			else if($status=='DELIVERED_TO_CLIENT'){
				$sql =execSQL("UPDATE entretiens SET USR_MAJ = ?, HEU_MAJ = CURRENT_TIMESTAMP,OUT_DATE = CURRENT_TIMESTAMP, DATE = ?, STATUS = ?, COMMENT = ?, INTERNAL_COMMENT=?,OUT_DATE_PLANNED=? WHERE ID = ?;", array('ssssssi', $token, $date, $status, $comment, $internalComment,$outDatePlanned, $id), true);
			}
			else{
				$sql =execSQL("UPDATE entretiens SET USR_MAJ = ?, HEU_MAJ = CURRENT_TIMESTAMP, DATE = ?, STATUS = ?, COMMENT = ?, INTERNAL_COMMENT=?,OUT_DATE_PLANNED=? WHERE ID = ?;", array('ssssssi', $token, $date, $status, $comment, $internalComment, $outDatePlanned, $id), true);
			}
			$response = array ('response'=>'success', 'message' => 'la modification a bien été effectuée');
			echo json_encode($response);
			die;
		}else
		error_message('403');
	}else if($action === 'addImage'){
		if(get_user_permissions("admin", $token)){
			$id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
			$name = isset($_POST["name"]) ? $_POST["name"] : NULL;
			$dossier =  $_SERVER['DOCUMENT_ROOT'].'/images_entretiens/'.$id.'/'.$name;
			if(!file_exists($dossier)){
				mkdir($dossier, 0777, true);
			}
			$dossier =  $_SERVER['DOCUMENT_ROOT'].'/images_entretiens/'.$id.'/'.$name;
			if(!file_exists($dossier)){
				mkdir($dossier, 0777, true);
			}
			$fichier = basename( $_FILES['media']['name']);
				if(move_uploaded_file($_FILES['media']['tmp_name'], $dossier . '/' . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
				{
					$upload=true;
					$path= $dossier . '/' . $fichier;
				}else{
					errorMessage('ES0012');
				}
				$response= array( 'response' => 'sucess', 'message' => 'Fichier ajouté');
				echo json_encode($response);
				die;
			}else
			error_message('403');
		}else if($action == "deleteImage"){
			$url=$_POST['url'];
			$path = explode("/", $url);
			$id = explode("_", $path[1]);
			if(file_exists( $_SERVER['DOCUMENT_ROOT']."/".$url )){
				unlink($_SERVER['DOCUMENT_ROOT']."/".$url);
				$response = array('response' => "success", 'id' => $id[0], "message" => "Image supprimée");
				echo json_encode($response);
				die;
			}else{
				$response = array('response' => "error", "message" => "Fichier non trouvé");
				echo json_encode($response);
				die;
			}
		}else if($action == "deleteEntretien"){
			$ID=isset($_POST['id']) ? $_POST['id'] : NULL;
			execSQL("DELETE FROM entretiens WHERE ID = ?", array('i', $ID), true);
			successMessage("SM0031");
			die;
		}else{
			error_message('405');
		}
		break;
		default:
		error_message('405');
		break;
	}
	?>
