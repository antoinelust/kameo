<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');

require_once '../globalfunctions.php';
require_once '../authentication.php';
require_once '../connexion.php'; 

$token = getBearerToken();

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;
		
		if($action === 'listChatUsers'){
			if(get_user_permissions("admin", $token)){
				$stmt = $conn->prepare("SELECT EMAIL FROM customer_referential WHERE TOKEN = ?");
				$stmt->bind_param("s", $token);
				if ($stmt->execute())
				{
					$email = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['EMAIL'];
					$stmt->close();
					$stmt = $conn->prepare("SELECT DISTINCT ca.EMAIL_USER as EMAIL, (SELECT count(*) from chat where (EMAIL_USER = ca.EMAIL_USER AND EMAIL_DESTINARY = 'support@kameobikes.com') or EMAIL_DESTINARY = ca.EMAIL_USER) as NB_MESSAGES, (SELECT STATUS FROM client_orders co WHERE co.EMAIL=ca.EMAIL_USER) as ORDER_STATUS FROM chat ca WHERE ca.EMAIL_USER != ?");
					$stmt->bind_param("s", $email);
					if($stmt->execute())
						echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
					else
						echo json_encode(array ('response'=>'error'));
				}else
					echo json_encode(array ('response'=>'error'));
				$stmt->close();
			}else
				error_message('403');
		}else if($action === 'retreiveMessages'){
			if(get_user_permissions(["order","admin"], $token)){
				$type=isset($_GET['type']) ? $_GET['type'] : NULL;
				$stmt = $conn->prepare("SELECT EMAIL FROM customer_referential WHERE TOKEN = ?");
				$stmt->bind_param("s", $token);
				if ($stmt->execute())
					$user = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['EMAIL'];
				$stmt->close();
				if (get_user_permissions("admin", $token))
					$user=isset($_GET['email']) ? $_GET['email'] : $user;
				$stmt = $conn->prepare("SELECT EMAIL_USER as emailUser, NOM as name, PRENOM as firstName, EMAIL_DESTINARY as emailDestinary, MESSAGE as message, DATE_FORMAT(MESSAGE_TIMESTAMP,'%d/%m') as messageDate, DATE_FORMAT(MESSAGE_TIMESTAMP,'%H:%i') as messageHour FROM chat, customer_referential WHERE EMAIL_USER = EMAIL AND TYPE=? AND (EMAIL_DESTINARY=? OR (EMAIL_USER = ? AND EMAIL_DESTINARY='support@kameobikes.com'))");
				$stmt->bind_param("sss", $type, $user, $user);
				if ($stmt->execute())
				{
					$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
					$stmt->close();
					$response=array();
					$response['response']="success";
					if ($messages != null)
					{
						$response['chatNumber']=count($messages);
						$response['chat']=$messages;
						for ($i = 0; $i < $response['chatNumber']; $i++)
							if (file_exists('../../images/images_users/'.strtolower($response['chat'][$i]['firstName']." ".$response['chat'][$i]['name'].".jpg")))
								$response['chat'][$i]['img']=strtolower('/images/images_users/'.$response['chat'][$i]['firstName']." ".$response['chat'][$i]['name'].".jpg");
					}
					else
						$response['chatNumber']=0;
					echo json_encode($response);
				}else
					echo json_encode(array ('response'=>'error'));
			}else
				error_message('403');
		}else
				error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
		if ($action === 'sendMessage')
		{
			if(get_user_permissions(["order", "admin"], $token)){
				$stmt = $conn->prepare("SELECT EMAIL FROM customer_referential WHERE TOKEN = ?");
				$stmt->bind_param("s", $token);
				if ($stmt->execute())
				{
					$email = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['EMAIL'];
					$stmt->close();
					if (get_user_permissions("admin", $token))
						$recipient=isset($_POST['recipient']) ? $_POST['recipient'] : "support@kameobikes.com";
					else
						$recipient= "support@kameobikes.com";
					$type=isset($_POST['type']) ? $_POST['type'] : NULL;
					$message=isset($_POST['message']) ? $_POST['message'] : NULL;
					$stmt = $conn->prepare("INSERT INTO chat (USR_MAJ, EMAIL_USER, EMAIL_DESTINARY, TYPE, MESSAGE) VALUES(?, ?, ?, ?, ?)");
					$stmt->bind_param("sssss", $email, $email, $recipient, $type, $message);
					if ($stmt->execute())
					{
						if (notify_message($conn, $email, $recipient, $stmt->insert_id))
						{
							$response['response']="success";
							echo json_encode($response);
						}else
							echo json_encode(array ('response'=>'error1'));
					}else
						echo json_encode(array ('response'=>'error2'));
				}else
					echo json_encode(array ('response'=>'error3'));
				$stmt->close();
			}else
				error_message('403');
		}else
			error_message('405');
	break;
	default:
			error_message('405');
		break;
}

function notify_message($conn, $sender, $destinary, $insertedID)
{
	$stmt = $conn->prepare("SELECT ID FROM customer_referential WHERE EMAIL = ?");
	$stmt->bind_param("s", $destinary);
	if ($stmt->execute())
	{
		$ownerID = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['ID'];
		$stmt->close();
		if ($ownerID == NULL) //If user does not exist, send to administration
		{
			$ownerID = 0;
			$notifContent = '<a class="text-green" href="#">'.$sender.' vous a envoyé un message.</a>';
			//data-toggle="modal" data-target="#taskManagement"
		}
		else
			$notifContent = '<a class="text-green" href="#">Vous avez reçu un message.</a>'; 
		$stmt = $conn->prepare("INSERT INTO notifications (USR_MAJ, TEXT, `READ`, TYPE, USER_ID, TYPE_ITEM, HEU_MAJ, DATE) VALUES (?, ?, 'N', 'newChatMessage', ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
		$stmt->bind_param("ssii", $sender, $notifContent, $ownerID, $insertedID);
		if ($stmt->execute())
		{
			$stmt->close();
			return true;
		}
	}
	return false;
}
$conn->close();
?>