<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
$ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;

include 'connexion.php';
$sql="SELECT * FROM companies_contact dd where ID_COMPANY='$ID'";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result2 = mysqli_query($conn, $sql);
$length = $result2->num_rows;
$conn->close();

$response['length']=$length;

$i=0;
while($row = mysqli_fetch_array($result2))
{
  $response[$i]['contactId']=$row['ID'];
  $response[$i]['emailContact']=$row['EMAIL'];
  $response[$i]['firstNameContact']=$row['PRENOM'];
  $response[$i]['lastNameContact']=$row['NOM'];
  $response[$i]['phone']=$row['PHONE'];
  $response[$i]['bikesStats']=$row['BIKES_STATS'];
  $response[$i]['fonction']=$row['FUNCTION'];
    $i++;
}

echo json_encode($response);
die;
