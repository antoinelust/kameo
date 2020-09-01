<?php 

    include '../connexion.php';
	$stmt = $conn->prepare("SELECT T1.COMPANY, T1.BILLING_GROUP, cb.FRAME_NUMBER, cb.CONTRACT_START, CB.CONTRACT_END FROM ((select COMPANY, BILLING_GROUP from customer_bikes WHERE AUTOMATIC_BILLING='Y' and CONTRACT_TYPE='leasing') UNION (SELECT COMPANY, BILLING_GROUP FROM boxes WHERE AUTOMATIC_BILLING='Y')) as T1, customer_bikes cb WHERE T1.company=cb.COMPANY and T1.BILLING_GROUP=cb.BILLING_GROUP and cb.STAANN != 'D' ORDER BY T1.COMPANY, T1.BILLING_GROUP, cb.FRAME_NUMBER");
	if($stmt)
	{
		$stmt->execute();
		echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
	}
	else
		error_message('500', 'Unable to retrieve list of companies');
?>
                
