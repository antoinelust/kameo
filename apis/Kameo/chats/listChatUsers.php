<?php
global $conn;
$stmt = $conn->prepare("SELECT EMAIL FROM customer_referential WHERE TOKEN = ?");
if ($stmt)
{
	$stmt->bind_param("s", $token);
	$stmt->execute();
	$email = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['EMAIL'];
	$stmt->close();
	$stmt = $conn->prepare("SELECT DISTINCT ca.EMAIL_USER as EMAIL, (SELECT count(*) from chat where (EMAIL_USER = ca.EMAIL_USER AND EMAIL_DESTINARY = 'support@kameobikes.com') or EMAIL_DESTINARY = ca.EMAIL_USER) as NB_MESSAGES, (SELECT STATUS FROM client_orders co WHERE co.EMAIL=ca.EMAIL_USER) as ORDER_STATUS, (SELECT IF(EMAIL_DESTINARY='support@kameobikes.com', 'N', 'Y') FROM chat WHERE EMAIL_DESTINARY=ca.EMAIL_USER OR EMAIL_USER=ca.EMAIL_USER ORDER BY ID DESC LIMIT 1) as 'MESSAGE_READ' FROM chat ca WHERE ca.EMAIL_USER not like '%kameobikes.com'");
	if($stmt)
	{
		$stmt->execute();
		echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
	}
	else
		error_message('500', 'Unable to retrieve chats users');
}else
	error_message('500', 'Unable to retrieve your email address');
$stmt->close();
?>
