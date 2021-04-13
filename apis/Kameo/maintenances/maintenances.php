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
				execSQL("INSERT INTO entretiens (HEU_MAJ, USR_MAJ, BIKE_ID, DATE, STATUS, COMMENT, INTERNAL_COMMENT, NR_ENTR ) VALUES (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, 1)", array('sissss', $user, $bike_id, $date, $status, $comment, $internalComment), true);
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
				$sql =execSQL("UPDATE entretiens SET USR_MAJ = ?, HEU_MAJ = CURRENT_TIMESTAMP, DATE = ?, STATUS = ?, COMMENT = ?, INTERNAL_COMMENT=? WHERE ID = ?;", array('sssssi', $token, $date, $status, $comment, $internalComment, $id), true);
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
