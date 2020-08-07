<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
include 'connexion.php';

$type=isset($_GET['type']) ? $_GET['type'] : NULL;

if($type=="ownerField"){    
    $stmt = $conn->prepare("SELECT OWNER as email, bb.NOM as name, bb.PRENOM as firstName from company_actions aa, customer_referential bb where aa.OWNER=bb.EMAIL and bb.STAANN != 'D' GROUP BY OWNER");
    if($stmt)
    {
        $stmt->execute();
		$response['owner'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		$response['ownerNumber'] = count($response['owner']);
		$response['response'] = "success";
        echo json_encode($response);
        $stmt->close();
    }
    else
        error_message('500', 'Unable to retrieve actions owners');
}else
    errorMessage("ES0012");
$conn->close();
?>
