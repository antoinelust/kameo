<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();

include 'connexion.php';
$sql="SELECT * FROM customer_referential WHERE EMAIL like '%kameobikes.com'";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);        
$length = $result->num_rows;
$response['membersNumber']=$length;

$response['response']="success";

$i=0;
while($row = mysqli_fetch_array($result))
{
    $response['member'][$i]['name']=$row['NOM'];
    $response['member'][$i]['firstName']=$row['PRENOM'];
    $response['member'][$i]['email']=$row['EMAIL'];
    $response['member'][$i]['staann']=$row['STAANN'];
    $i++;
}                                                       
$conn->close();

echo json_encode($response);
die;    

    