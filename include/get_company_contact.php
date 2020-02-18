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
if($result2->num_rows=='0'){
    errorMessage("ES0039");
}
$conn->close();

$i=0;
while($row = mysqli_fetch_array($result2))
{
  $response['contactId'][$i]=$row['ID'];
  $response['emailContact'][$i]=$row['EMAIL'];
  $response['firstNameContact'][$i]=$row['PRENOM'];
  $response['lastNameContact'][$i]=$row['NOM'];
  $response['phone'][$i]=$row['PHONE'];
  $response['bikesStats'][$i]=$row['BIKES_STATS'];
  $response['fonction'][$i]=$row['FUNCTION'];
    $i++;
}

echo json_encode($response);
die;
