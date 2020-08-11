<?php
global $conn;
$type=isset($_GET['type']) ? $_GET['type'] : NULL;
$stmt = $conn->prepare("SELECT EMAIL FROM customer_referential WHERE TOKEN = ?");
if ($stmt)
{
	$stmt->bind_param("s", $token);
	$stmt->execute();
	$user = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['EMAIL'];
	$stmt->close();
} else
	error_message('500', 'Unable to retrieve your email address');
if (get_user_permissions("admin", $token))
	$user=isset($_GET['email']) ? $_GET['email'] : $user;
$sql = "SELECT EMAIL_USER as emailUser, NOM as name, PRENOM as firstName, EMAIL_DESTINARY as emailDestinary, MESSAGE as message, DATE_FORMAT(MESSAGE_TIMESTAMP,'%d/%m') as messageDate, DATE_FORMAT(MESSAGE_TIMESTAMP,'%H:%i') as messageHour FROM chat, customer_referential WHERE EMAIL_USER = EMAIL AND (EMAIL_DESTINARY=? OR (EMAIL_USER = ? AND EMAIL_DESTINARY='support@kameobikes.com'))";
if ($type != NULL)
	$sql = $sql." AND TYPE=?";
$stmt = $conn->prepare($sql);
if ($stmt)
{
	if ($type != NULL)
		$stmt->bind_param("sss", $user, $user, $type);
	else
		$stmt->bind_param("ss", $user, $user);
	$stmt->execute();
	$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	$stmt->close();
	$response=array();
	$response['response']="success";
	if ($messages != null)
	{
		$response['messagesNumber']=count($messages);
		$response['messages']=$messages;
		for ($i = 0; $i < $response['messagesNumber']; $i++)
			if (file_exists('../../images/images_users/'.strtolower($response['messages'][$i]['firstName']." ".$response['messages'][$i]['name'].".jpg")))
				$response['messages'][$i]['img']=strtolower('/images/images_users/'.$response['messages'][$i]['firstName']." ".$response['messages'][$i]['name'].".jpg");
	}
	else
		$response['messagesNumber']=0;
	echo json_encode($response);
}else
	error_message('500', 'Unable to retrieve messages');
?>