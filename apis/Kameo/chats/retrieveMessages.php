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
$stmt = $conn->prepare("SELECT EMAIL_USER as emailUser, NOM as name, PRENOM as firstName, EMAIL_DESTINARY as emailDestinary, MESSAGE as message, DATE_FORMAT(MESSAGE_TIMESTAMP,'%d/%m') as messageDate, DATE_FORMAT(MESSAGE_TIMESTAMP,'%H:%i') as messageHour FROM chat, customer_referential WHERE EMAIL_USER = EMAIL AND TYPE=? AND (EMAIL_DESTINARY=? OR (EMAIL_USER = ? AND EMAIL_DESTINARY='support@kameobikes.com'))");

if ($stmt)
{
	$stmt->bind_param("sss", $type, $user, $user);
	$stmt->execute();
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
	error_message('500', 'Unable to retrieve messages');
?>