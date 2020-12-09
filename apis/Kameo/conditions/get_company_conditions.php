<?php
global $conn;
$company=isset($_GET['company']) ? $_GET['company'] : NULL;
$stmt = $conn->prepare("SELECT * FROM conditions WHERE COMPANY = (SELECT INTERNAL_REFERENCE FROM companies WHERE COMPANY_NAME = ?)");
if ($stmt)
{
	$stmt->bind_param("s", $company);
	$stmt->execute();
	$response['conditions'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	$stmt->close();

  $company=$response['conditions'][0]['COMPANY'];
  $stmt = $conn->prepare("select EMAIL, NOM, PRENOM from customer_referential aa where COMPANY=? and not exists (select 1 from specific_conditions bb where bb.EMAIL=aa.EMAIL and bb.COMPANY=aa.COMPANY and bb.STAANN != 'D')");
  if ($stmt)
  {
    $stmt->bind_param("s", $company);
    $stmt->execute();
    $response['emails'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
  }
} else
	error_message('500', 'Unable to retrieve conditions');

	echo json_encode($response);
  die;
?>
