<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$type=isset($_GET['type']) ? $_GET['type'] : NULL;

if($type=="ownerField"){
    $response=array();
    $response['response']="success";
    include 'connexion.php';
    $sql="SELECT OWNER, NOM, PRENOM  FROM company_actions aa, customer_referential bb WHERE aa.OWNER=bb.EMAIL and bb.STAANN != 'D' GROUP BY OWNER, NOM, PRENOM";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $length=$result->num_rows;
    $response['ownerNumber']=$length;
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['owner'][$i]['email']=$row['OWNER'];
        $response['owner'][$i]['name']=$row['NOM'];
        $response['owner'][$i]['firstName']=$row['PRENOM'];
        $i++;
    }
    $conn->close();
    $response['response']="success";
    echo json_encode($response);
    die;
    
}else{
    errorMessage("ES0012");
}
?>
