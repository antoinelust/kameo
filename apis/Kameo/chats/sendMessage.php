<?php
global $conn;
$stmt = $conn->prepare("SELECT EMAIL FROM customer_referential WHERE TOKEN = ?");
if ($stmt)
{
	$stmt->bind_param("s", $token);
	$stmt->execute();
	$email = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['EMAIL'];
	$stmt->close();
	if (get_user_permissions("admin", $token))
		$recipient=isset($_POST['recipient']) ? $_POST['recipient'] : "support@kameobikes.com";
	else
		$recipient= "support@kameobikes.com";
	$type=isset($_POST['type']) ? $_POST['type'] : NULL;
	$message=isset($_POST['message']) ? $_POST['message'] : NULL;
	$stmt = $conn->prepare("INSERT INTO chat (USR_MAJ, EMAIL_USER, EMAIL_DESTINARY, TYPE, MESSAGE) VALUES(?, ?, ?, ?, ?)");
	if ($stmt)
	{
		$stmt->bind_param("sssss", $email, $email, $recipient, $type, $message);
		$conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		$stmt->execute();
		if (notify_message($conn, $email, $recipient, $stmt->insert_id))
		{
			$conn->commit();
			$response['response']="success";
			echo json_encode($response);
			$stmt->close();
		}else
			error_message('500', 'Unable to send notification of your message, it has been canceled');
	}else
		error_message('500', 'Unable to send your message');
}else
	error_message('500', 'Unable to retrieve your email address');


function notify_message($conn, $sender, $destinary, $insertedID)
{
	$stmt = $conn->prepare("SELECT ID FROM customer_referential WHERE EMAIL = ?");
	if ($stmt)
	{
		$stmt->bind_param("s", $destinary);
		$stmt->execute();
		$ownerID = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['ID'];
		$stmt->close();
		if ($ownerID == NULL) //If user does not exist, send to administration
		{
			$ownerID = 0;
			$notifContent = '<a class="text-green" href="#" data-toggle="modal" data-target="#adminChat" data-correspondent="'.$sender.'" data-order="">'.$sender.' vous a envoyé un message.</a>';
		}
		else
			$notifContent = '<span class="text-green">Vous avez reçu un message.</span>'; 
		$stmt = $conn->prepare("INSERT INTO notifications (USR_MAJ, TEXT, `READ`, TYPE, USER_ID, TYPE_ITEM, HEU_MAJ, DATE) VALUES (?, ?, 'N', 'newChatMessage', ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
		if ($stmt)
		{
			$stmt->bind_param("ssii", $sender, $notifContent, $ownerID, $insertedID);
			$stmt->execute();
			$stmt->close();
			return true;
		}else
			return false;
	}
	return false;
}
?>