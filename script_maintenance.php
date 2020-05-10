<?php

$now=new DateTime('now');
$nowString=$now->format('Y-m-d');



include 'connexion.php';
$sql="SELECT * from customer_bikes where COMPANY!= 'KAMEO' and CONTRACT_START != NULL";
if ($conn->query($sql) === FALSE) {
  $response = array ('response'=>'error', 'message'=> $conn->error);
  echo json_encode($response);
  die;
}
$result = mysqli_query($conn, $sql);
$conn->close();

while($row = mysqli_fetch_array($result))
{
    include 'connexion.php';
    $bikeID=$row['BIKE_ID'];
    $sql="select * from entretiens where BIKE_ID = $bikeID and TYPE='3 mois'";
    if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }
    $result = mysqli_query($conn, $sql);
    
}

$contractStart=new DateTime($resultat['CONTRACT_START']);





?>

