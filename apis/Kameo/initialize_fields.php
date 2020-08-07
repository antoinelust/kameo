<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$type=isset($_GET['type']) ? $_GET['type'] : NULL;

if($type=="ownerField"){    
    global $conn;
    include 'connexion.php';
    $stmt = $conn->prepare("SELECT OWNER as email, bb.NOM as name, bb.PRENOM as firstName from company_actions aa, customer_referential bb where aa.OWNER=bb.EMAIL and bb.STAANN != 'D' GROUP BY OWNER");
    if($stmt)
    {
        $stmt->execute();
        echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
        echo json_encode(Array("reponse" => "success"));
        $stmt->close();
        $conn->close();
    }
    else
        error_message('500', 'Unable to retrieve Actions owners');
}else{
    errorMessage("ES0012");
}
?>
