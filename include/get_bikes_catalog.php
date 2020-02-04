<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';



$response=array();

    include 'connexion.php';
    $sql="SELECT *  FROM bike_catalog";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    if($result->num_rows=='0'){
        errorMessage("ES0039");
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
    $response['bike'][$i]['link']=$row['LINK'];

    $i++;

}


echo json_encode($response);
die;
?>
