<?php 

    include '../connexion.php';
	$stmt = $conn->prepare("SELECT aa.ID, aa.COMPANY_NAME from companies aa, customer_bikes bb WHERE aa.INTERNAL_REFERENCE=bb.COMPANY group by aa.INTERNAL_REFERENCE ORDER BY aa.ID");
	if($stmt)
	{
		$stmt->execute();
		echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
	}
	else
		error_message('500', 'Unable to retrieve chats users');
?>
                
