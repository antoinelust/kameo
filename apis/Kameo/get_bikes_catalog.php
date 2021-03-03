<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


$brand=isset($_GET['brand']) ? $_GET['brand']: null;
$id=isset($_GET['id']) ? $_GET['id']: null;

$response=array();

include 'connexion.php';
$sql="SELECT *  FROM bike_catalog WHERE STAANN != 'D'";

if($brand){
    $sql=$sql." AND BRAND='$brand'";
}
if($id){
    $sql=$sql." AND ID='$id'";
}



if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
if($result->num_rows=='0'){
    errorMessage("ES0056");
}


$length = $result->num_rows;
$response['bikeNumber']=$length;
$response['response']="success";
$conn->close();

$i=0;
while($row = mysqli_fetch_array($result))

{

    $response['bike'][$i]['lastUpdate']=$row['HEU_MAJ'];
    $response['bike'][$i]['userUpdate']=$row['USR_MAJ'];
    $response['bike'][$i]['brand']=$row['BRAND'];
    $response['bike'][$i]['model']=$row['MODEL'];
    $response['bike'][$i]['id']=$row['ID'];
    $response['bike'][$i]['frameType']=$row['FRAME_TYPE'];
    $response['bike'][$i]['usage']=$row['UTILISATION'];
    $response['bike'][$i]['electric']=$row['ELECTRIC'];
    $response['bike'][$i]['buyingPrice']=$row['BUYING_PRICE'];
    $response['bike'][$i]['priceHTVA']=$row['PRICE_HTVA'];
    $response['bike'][$i]['stock']=$row['STOCK'];
    $response['bike'][$i]['display']=$row['DISPLAY'];
    $response['bike'][$i]['motor']=$row['MOTOR'];
    $response['bike'][$i]['battery']=$row['BATTERY'];
    $response['bike'][$i]['transmission']=$row['TRANSMISSION'];
    $i++;

}


echo json_encode($response);
die;
?>
