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
	else if($action=='getGraphic'){
		if(get_user_permissions("admin", $token)){

			$company = isset($_GET["company"]) ? $_GET["company"] : NULL;
			$owner = isset($_GET["owner"]) ? $_GET["owner"] : NULL;
			$numberOfDays = isset($_GET["numberOfDays"]) ? $_GET["numberOfDays"] : NULL;
			$intervalStop="P".$numberOfDays."D";

			$date_start = new DateTime("NOW");
			$date_start->sub(new DateInterval($intervalStop));
			$date_start->setTime(23, 59);
			$date_start_string=$date_start->format('Y-m-d');

			$date_end = new DateTime("NOW");
			$date_end->add(new DateInterval('P1D'));
			$date_end->sub(new DateInterval($intervalStop));

			$arrayTotalTasks=array();
			$arrayContacts=array();
			$arrayReminder=array();
			$arrayRDVPlan=array();
			$arrayRDV=array();
			$arrayOffers=array();
			$arrayOffersSigned=array();
			$arrayDelivery=array();
			$arrayOther=array();
			$arrayDates=array();

			$date_now=new DateTime("NOW");
			$date_nom_string=$date_now->format('Y-m-d');

			include __DIR__ .'/../connexion.php';
			$sql="SELECT TYPE, COUNT(1) AS 'SUM' FROM company_actions WHERE STATUS='TO DO' AND DATE < '$date_nom_string'";

			if($owner!='*' && $owner != ''){
					$sql=$sql. "AND OWNER='$owner'";
			}
			$sql=$sql." GROUP BY TYPE";

			if ($conn->query($sql) === FALSE) {
					$response = array ('response'=>'error', 'message'=> $conn->error);
					echo json_encode($response);
					die;
			}
			$result = mysqli_query($conn, $sql);


			$presenceTotalTasks=0;
			$presenceContacts=0;
			$presenceReminder=0;
			$presenceRDVPlan=0;
			$presenceRDV=0;
			$presenceOffers=0;
			$presenceOffersSigned=0;
			$presenceDelivery=0;
			$presenceOther=0;

			while($row = mysqli_fetch_array($result))
			{
					if($row['TYPE']=="contact"){
							$presenceContacts=1;
					}
					if($row['TYPE']=="rappel"){
							$presenceReminder=1;
					}
					if($row['TYPE']=="plan rdv"){
							$presenceRDVPlan=1;
					}
					if($row['TYPE']=="rdv"){
							$presenceRDV=1;
					}
					if($row['TYPE']=="offre"){
							$presenceOffers=1;
					}
					if($row['TYPE']=="offreSigned"){
							$presenceOffersSigned=1;
					}
					if($row['TYPE']=="delivery"){
							$presenceDelivery=1;
					}
					if($row['TYPE']=="other"){
							$presenceOther=1;
					}
			}

			$response['response']="success";
			$response['presenceContacts']=$presenceContacts;
			$response['presenceReminder']=$presenceReminder;
			$response['presenceRDVPlan']=$presenceRDVPlan;
			$response['presenceRDV']=$presenceRDV;
			$response['presenceOffers']=$presenceOffers;
			$response['presenceOffersSigned']=$presenceOffersSigned;
			$response['presenceDelivery']=$presenceOffersSigned;
			$response['presenceOther']=$presenceOther;


			$conn->close();



			while($date_start<=$date_now){
					$date_start_string=$date_start->format('Y-m-d');

					include __DIR__ .'/../connexion.php';
					$sql="SELECT COUNT(1) AS 'SUM' FROM company_actions WHERE STATUS='TO DO' AND DATE<='$date_start_string'";

					if($owner!='*' && $owner != ''){
							$sql=$sql. "AND OWNER='$owner'";
					}


					if ($conn->query($sql) === FALSE) {
							$response = array ('response'=>'error', 'message'=> $conn->error);
							echo json_encode($response);
							die;
					}
					$result = mysqli_query($conn, $sql);
					$resultat = mysqli_fetch_assoc($result);
					array_push($arrayTotalTasks, $resultat['SUM']);
					$conn->close();





					include __DIR__ .'/../connexion.php';

					$sql="SELECT TYPE, COUNT(1) AS 'SUM' FROM company_actions WHERE STATUS='TO DO' AND DATE<='$date_start_string'";
					if($owner!='*' && $owner != ''){
							$sql=$sql. "AND OWNER='$owner'";
					}

					$sql=$sql." GROUP BY TYPE";


					if ($conn->query($sql) === FALSE) {
							$response = array ('response'=>'error', 'message'=> $conn->error);
							echo json_encode($response);
							die;
					}
					$result = mysqli_query($conn, $sql);
					$conn->close();

					$presenceTotalTasks=0;
					$presenceContacts=0;
					$presenceReminder=0;
					$presenceRDVPlan=0;
					$presenceRDV=0;
					$presenceOffers=0;
					$presenceOffersSigned=0;
					$presenceDelivery=0;
					$presenceOther=0;

					while($row = mysqli_fetch_array($result))
					{

							if($row['TYPE']=="contact"){
									$presenceContacts=1;
									array_push($arrayContacts, $row['SUM']);
							}
							if($row['TYPE']=="rappel"){
									$presenceReminder=1;
									array_push($arrayReminder, $row['SUM']);
							}
							if($row['TYPE']=="plan rdv"){
									$presenceRDVPlan=1;
									array_push($arrayRDVPlan, $row['SUM']);
							}
							if($row['TYPE']=="rdv"){
									$presenceRDV=1;
									array_push($arrayRDV, $row['SUM']);
							}
							if($row['TYPE']=="offre"){
									$presenceOffers=1;
									array_push($arrayOffers, $row['SUM']);
							}
							if($row['TYPE']=="offreSigned"){
									$presenceOffersSigned=1;
									array_push($arrayOffersSigned, $row['SUM']);
							}
							if($row['TYPE']=="delivery"){
									$presenceDelivery=1;
									array_push($arrayDelivery, $row['SUM']);
							}
							if($row['TYPE']=="other"){
									$presenceOther=1;
									array_push($arrayOther, $row['SUM']);
							}

					}

					if($presenceContacts==0){
							array_push($arrayContacts, "0");
					}
					if($presenceReminder==0){
							array_push($arrayReminder, "0");
					}
					if($presenceRDVPlan==0){
							array_push($arrayRDVPlan, "0");
					}
					if($presenceRDV==0){
							array_push($arrayRDV, "0");
					}
					if($presenceOffers==0){
							array_push($arrayOffers, "0");
					}
					if($presenceOffersSigned==0){
							array_push($arrayOffersSigned, "0");
					}
					if($presenceDelivery==0){
							array_push($arrayDelivery, "0");
					}
					if($presenceOther==0){
							array_push($arrayOther, "0");
					}

					array_push($arrayDates, $date_start_string);


					$date_start->add(new DateInterval('P1D'));
					$date_end->add(new DateInterval('P1D'));
					$date_start->setTime(23, 59);
					$date_end->setTime(23, 59);

			}

			$response['response']="success";
			$response['arrayTotalTasks']=$arrayTotalTasks;
			$response['arrayContacts']=$arrayContacts;
			$response['arrayReminder']=$arrayReminder;
			$response['arrayRDVPlan']=$arrayRDVPlan;
			$response['arrayRDV']=$arrayRDV;
			$response['arrayOffers']=$arrayOffers;
			$response['arrayOffersSigned']=$arrayOffersSigned;
			$response['arrayDelivery']=$arrayDelivery;
			$response['arrayOther']=$arrayOther;
			$response['arrayDates']=$arrayDates;
			echo json_encode($response);
			die;
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
