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
echo "<tr><th>ID</th><th>Reference du velo</th><th>Reference du cadre</th><th>Debut de contrat</th><th>Fin de contrat</th></tr>";

$now=new DateTime();
$nowString=$now->format("Y-m-d");

$sql="SELECT * FROM customer_bikes WHERE INSURANCE='Y' AND COMPANY != 'KAMEO' AND CONTRACT_END >= '$nowString'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result))
{
  $dateStart=new DateTime($row['CONTRACT_START']);
  if($dateStart>$OneMonthAgo){
    echo "<tr><td>".$row['ID']."</td><td>".$row['FRAME_NUMBER']."</td><td>".$row['FRAME_REFERENCE']."</td><td>".$row['CONTRACT_START']."</td><td>".$row['CONTRACT_END']."</td></tr>";
  }else{
    echo "<tr><td>".$row['ID']."</td><td>".$row['FRAME_NUMBER']."</td><td>".$row['FRAME_REFERENCE']."</td><td>".$row['CONTRACT_START']."</td><td>".$row['CONTRACT_END']."</td></tr>";
  }
}

echo "</table>";


$conn->close();



?>
