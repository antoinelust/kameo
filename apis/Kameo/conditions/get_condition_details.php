<?php
global $conn;
$ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;
if($ID==NULL){
	return getCondition()['conditions'];
}else{
	$stmt = $conn->prepare("SELECT * FROM conditions WHERE ID =?");
	if ($stmt)
	{
		$stmt->bind_param("i", $ID);
		$stmt->execute();
		$response = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
		$stmt->close();
		$company=$response['COMPANY'];
		$response['response']="success";
	  if($response['NAME']=="generic"){
	    $stmt = $conn->prepare("select EMAIL, NOM, PRENOM from customer_referential aa where COMPANY=? and not exists (select 1 from specific_conditions bb where bb.EMAIL=aa.EMAIL and bb.COMPANY=aa.COMPANY and bb.STAANN != 'D')");
	    if ($stmt)
	    {
	    	$stmt->bind_param("s", $company);
	    	$stmt->execute();
	    	$response['emails'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	    	$stmt->close();
	    }
	  }else{
	    $stmt = $conn->prepare("select aa.EMAIL, bb.NOM, bb.PRENOM from specific_conditions aa, customer_referential bb where CONDITION_REFERENCE=? AND aa.EMAIL=bb.EMAIL AND aa.STAANN != 'D'");
	    if ($stmt)
	    {
	      $stmt->bind_param("i", $ID);
	      $stmt->execute();
	      $response['emails'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	      $stmt->close();
	    }
	  }
	} else
		error_message('500', 'Unable to retrieve condition details');

		echo json_encode($response);
	  die;
}
?>
