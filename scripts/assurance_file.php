<?php

header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=velosKameoBikes.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

include '../apis/Kameo/connexion.php';


$OneMonthAgo=new DateTime();
$interval = new DateInterval('P1M');
$OneMonthAgo->sub($interval);

echo "<table>";
echo "<tr><th>ID</th><th>Reference du velo</th><th>Reference du cadre</th><th>Marque</th><th>Modèle</th><th>Debut de contrat</th><th>Fin de contrat</th></tr>";

$now=new DateTime();
$nowString=$now->format("Y-m-d");

$sql="SELECT customer_bikes.ID, customer_bikes.FRAME_NUMBER, customer_bikes.FRAME_REFERENCE, customer_bikes.CONTRACT_START, customer_bikes.CONTRACT_END, bike_catalog.BRAND, bike_catalog.MODEL FROM customer_bikes, bike_catalog WHERE INSURANCE='Y' AND COMPANY != 'KAMEO' AND (CONTRACT_TYPE='leasing' OR CONTRACT_TYPE='renting' AND (CONTRACT_END >= '$nowString' OR CONTRACT_END is NULL)) AND customer_bikes.TYPE=bike_catalog.ID";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result))
{
  $dateStart=new DateTime($row['CONTRACT_START']);
  echo "<tr><td>".$row['ID']."</td><td>".$row['FRAME_NUMBER']."</td><td>".$row['FRAME_REFERENCE']."</td><td>".$row['BRAND']."</td><td>".$row['MODEL']."</td><td>".$row['CONTRACT_START']."</td><td>".$row['CONTRACT_END']."</td></tr>";
}

echo "</table>";


$conn->close();



?>
