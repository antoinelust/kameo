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

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
	$action=isset($_GET['action']) ? $_GET['action'] : NULL;
	$id = isset($_GET["id"]) ? $_GET["id"] : NULL;

	if($action === 'list'){
		if(get_user_permissions("admin", $token)){
			$company = isset($_GET["company"]) ? $_GET["company"] : NULL;
			$status = isset($_GET["status"]) ? $_GET["status"] : NULL;
			$user = isset($_GET["user"]) ? $_GET["user"] : NULL;
			$owner = isset($_GET["owner"]) ? $_GET["owner"] : NULL;
			$tasksListing_number=isset($_GET["numberOfResults"]) ? $_GET["numberOfResults"] : NULL;

			include __DIR__ .'/../connexion.php';
			if($company=="*"){
					$sql="SELECT company_actions.*, companies.COMPANY_NAME FROM company_actions, companies WHERE company_actions.COMPANY_ID=companies.ID";
			}else{
					$sql="SELECT company_actions.*, companies.COMPANY_NAME FROM company_actions, companies WHERE company_actions.COMPANY_ID=companies.ID AND company_actions.COMPANY_ID='$company'";
			}

			if($status=="TO DO"){
					$sql=$sql." AND STATUS='TO DO'";
			}else if($status=="LATE"){
					$sql=$sql." AND CURRENT_DATE()>DATE_REMINDER AND STATUS = 'TO DO'";
			}

			if($owner!='*' && $owner != NULL){
					$sql=$sql." AND OWNER='$owner'";
			}

			if($tasksListing_number){
					$sql=$sql." ORDER BY ID DESC LIMIT $tasksListing_number";
			}else{
					$sql=$sql." ORDER BY ID DESC";
			}
		if ($conn->query($sql) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
			$result = mysqli_query($conn, $sql);
			$length = $result->num_rows;
			$response['actionNumber']=$length;

			$currentDate= new DateTime();

			$response['user']=$user;
			$i=0;
			$response['response']="success";
			while($row = mysqli_fetch_array($result))
			{
					$response['action'][$i]['id']=$row['ID'];
					$response['action'][$i]['date']=$row['DATE'];
					$response['action'][$i]['title']=$row['TITLE'];
					$response['action'][$i]['description']=$row['DESCRIPTION'];
					$response['action'][$i]['companyID']=$row['COMPANY_ID'];
					$response['action'][$i]['companyName']=$row['COMPANY_NAME'];
					$response['action'][$i]['type']=$row['TYPE'];
					$response['action'][$i]['channel']=$row['CHANNEL'];
					$response['action'][$i]['date_reminder']=$row['DATE_REMINDER'];
					$response['action'][$i]['status']=$row['STATUS'];
					$response['action'][$i]['owner']=$row['OWNER'];
					$ownerTask=$row['OWNER'];

					include __DIR__ .'/../connexion.php';
					$sql2="select * from customer_referential where email='$ownerTask'";
					$result2 = mysqli_query($conn, $sql2);
					$resultat2 = mysqli_fetch_assoc($result2);
					$response['action'][$i]['ownerName']=$resultat2['NOM'];
					$response['action'][$i]['ownerFirstName']=$resultat2['PRENOM'];


					$response['action'][$i]['id']=$row['ID'];
					$actionDate=new DateTime($row['DATE_REMINDER']);
					if($actionDate<$currentDate){
							$response['action'][$i]['late']=true;
					}else{
							$response['action'][$i]['late']=false;
					}

					$i++;
			}
			$conn->close();


			include __DIR__ .'/../connexion.php';
			$sql="SELECT * FROM company_actions";
			if($owner!='*' && $owner != NULL){
					$sql=$sql." WHERE OWNER='$owner'";
			}

		if ($conn->query($sql) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
			$result = mysqli_query($conn, $sql);
			$length = $result->num_rows;
			$response['actionNumberTotal']=$length;
			$response['sql1']=$sql;
			$conn->close();

			include __DIR__ .'/../connexion.php';
			$sql="SELECT * FROM company_actions WHERE STATUS != 'DONE'";
			if($owner!='*' && $owner != NULL){
					$sql=$sql." AND OWNER='$owner'";
			}

		if ($conn->query($sql) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
			$result = mysqli_query($conn, $sql);
			$length = $result->num_rows;
			$response['actionNumberNotDone']=$length;
			$conn->close();

			include __DIR__ .'/../connexion.php';
			$sql="SELECT * FROM company_actions WHERE STATUS != 'DONE' AND CURRENT_DATE()>DATE_REMINDER";
			if($owner!='*' && $owner != NULL){
					$sql=$sql." AND OWNER='$owner'";
			}
		if ($conn->query($sql) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
			$result = mysqli_query($conn, $sql);
			$length = $result->num_rows;
			$response['actionNumberLate']=$length;
			$conn->close();


			include __DIR__ .'/../connexion.php';
			$sql="SELECT * from customer_referential WHERE COMPANY='KAMEO' and STAANN != 'D'";
		if ($conn->query($sql) === FALSE) {
			$response = array ('response'=>'error', 'message'=> $conn->error);
			echo json_encode($response);
			die;
		}
			$result = mysqli_query($conn, $sql);
			$length = $result->num_rows;
			$response['ownerNumber']=$length;
			$i=0;
			while($row = mysqli_fetch_array($result)){
					$response['owner'][$i]['email']=$row['EMAIL'];
					$response['owner'][$i]['name']=$row['NOM'];
					$response['owner'][$i]['firstName']=$row['PRENOM'];
					$i++;


			}

			echo json_encode($response);
			die;
		}else
			error_message('403');
	}
	else if ($action === 'retrieve'){
		if(get_user_permissions("admin", $token)){
			$id = isset($_GET["id"]) ? $_GET["id"] : NULL;
			$resultat=execSQL("SELECT * FROM company_actions WHERE ID=?", array('i', $id), false)[0];
			$response['response']="success";
			$response['action']['id']=$resultat['ID'];
			$response['action']['date']=$resultat['DATE'];
			$response['action']['type']=$resultat['TYPE'];
			$response['action']['channel']=$resultat['CHANNEL'];
			$response['action']['title']=strip_tags($resultat['TITLE']);
			$response['action']['description']=strip_tags($resultat['DESCRIPTION']);
			$response['action']['companyID']=$resultat['COMPANY_ID'];
			$response['action']['date_reminder']=$resultat['DATE_REMINDER'];
			$response['action']['status']=$resultat['STATUS'];
			$response['action']['owner']=$resultat['OWNER'];
			echo json_encode($response);
			die;
		}
		else{
			error_message('403');
		}
	}
	else
		error_message('405');
	break;
	case 'POST':
	$id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
	$action = isset($_POST["action"]) ? $_POST["action"] : NULL;
	$company = isset($_POST["company"]) ? $_POST["company"] : NULL;
	$type = isset($_POST["type"]) ? $_POST["type"] : NULL;
	$user = isset($_POST["requestor"]) ? $_POST["requestor"] : NULL;
	$title=isset($_POST["title"]) ? addslashes($_POST["title"]) : NULL;
	$description=isset($_POST["description"]) ? addslashes($_POST["description"]) : NULL;
	$date=isset($_POST["date"]) ? date($_POST["date"]) : NULL;
	$date_reminder=isset($_POST["date_reminder"]) && $_POST["date_reminder"] != '' ? date($_POST["date_reminder"]) : NULL;
	$status=isset($_POST["status"]) ? $_POST["status"] : NULL;
	$owner=isset($_POST["owner"]) ? $_POST["owner"] : NULL;
	$channel=isset($_POST["channel"]) && $_POST["channel"] != '' ? $_POST["channel"] : NULL;

	if($action === 'add'){
		if(get_user_permissions("admin", $token)){
			$insertedID=execSQL("INSERT INTO  company_actions (USR_MAJ, HEU_MAJ, COMPANY_ID, TYPE, DATE, DATE_REMINDER, TITLE, DESCRIPTION, STATUS, OWNER, CHANNEL) VALUES (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?,?, ?, ?,?)", array('sissssssss', $token, $company, $type, $date, $date_reminder, $title, $description, $status, $owner, $channel), true);
			//ajout de la task dans les notifications, si c'est une task destinée a un autre utilisateur
			if ($owner != $_SESSION['userID']){
				$ownerIDexecSQL=execSQL("SELECT ID FROM customer_referential WHERE EMAIL = ?", array('s', $owner), true)[0]['ID'];
				$notifContent = '<a class="text-green retrieveTask" onclick="retrieve_task('.$insertedID.')"  name="'.$insertedID.'" data-toggle="modal" data-target="#taskManagement" href="#">Une nouvelle tâche vous a été assigné.</a>';
				execSQL("INSERT INTO notifications (USR_MAJ, TEXT, `READ`, TYPE, USER_ID, TYPE_ITEM, HEU_MAJ, DATE) VALUES (?, ?, 'N', 'taskEdited', ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)", array('ssii', $token, $notifContent, $ownerIDexecSQL, $insertedID), true);
			}
			successMessage("SM0017");
		}else
		error_message('403');
	}else if($action === 'update'){
		if(get_user_permissions("admin", $token)){
			execSQL("UPDATE  company_actions SET USR_MAJ=?, HEU_MAJ=CURRENT_TIMESTAMP, TYPE=?, COMPANY_ID=?, DATE=?, DATE_REMINDER=?, TITLE=?, DESCRIPTION=?, STATUS=?, OWNER=? WHERE ID=?", array('ssissssssi', $token, $type, $company, $date, $date_reminder, $title, $description, $status, $owner, $id), true);
			execSQL("UPDATE notifications SET STAAN = 'D' WHERE (TYPE_ITEM =? AND TYPE LIKE 'task%' AND (STAAN <> 'D' OR STAAN IS NULL))", array('i', $id), true);

			//Si utilisateur différent de l'owner, nouvelle notif
			if ($owner != $_SESSION['userID']) {
				//récupération de l'id de l'utilisateur
				include __DIR__ .'/../connexion.php';
				$ownerID = $conn->query("SELECT ID FROM customer_referential WHERE EMAIL = '$owner'");
				$ownerID = mysqli_fetch_assoc($ownerID)['ID'];
				$conn->close();

				//création de la notification
				$notifContent = '<a class="text-green retrieveTask" onclick="retrieve_task('.$id.')"  name="'.$id.'" data-toggle="modal" data-target="#taskManagement" href="#">Une tâche vous a été assigné ou a été modifié.</a>';
				execSQL("INSERT INTO notifications (USR_MAJ, TEXT, `READ`, TYPE, USER_ID, TYPE_ITEM, HEU_MAJ, DATE) VALUES (?, ?, 'N', 'taskEdited', ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)", array('ssii', $token, $notifContent, $ownerID, $id), true);
			}
			successMessage("SM0017");
		}
		else
			error_message('403');
	}else
	error_message('405');

	break;
	default:
	error_message('405');
	break;
}
?>
