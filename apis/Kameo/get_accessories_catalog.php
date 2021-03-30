<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$response=array();

include 'connexion.php';
$sql="SELECT accessories_catalog.id AS ACCESSORIES_ID,
             accessories_catalog.BRAND,
             accessories_catalog.MODEL,
             accessories_catalog.BUYING_PRICE,
             accessories_catalog.PRICE_HTVA,
             accessories_catalog.PRICE_HTVA*1.25/36 as leasingPrice,
             accessories_catalog.STOCK,
             accessories_catalog.DISPLAY,
             accessories_catalog.DESCRIPTION,
             accessories_catalog.ACCESSORIES_CATEGORIES,
             accessories_categories.ID AS CATEGORIES_ID,
             accessories_categories.CATEGORY
      FROM accessories_catalog
      INNER JOIN accessories_categories ON accessories_catalog.ACCESSORIES_CATEGORIES = accessories_categories.ID
      ORDER BY ACCESSORIES_CATEGORIES ASC, BRAND ASC";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
if($result->num_rows=='0'){
    errorMessage("ES0039");
}



$response['response']="success";
$conn->close();

$i=0;
while($row = mysqli_fetch_array($result))

{
    $response['accessories'][$i]['id']=$row['ACCESSORIES_ID'];
    $response['accessories'][$i]['brand']=$row['BRAND'];
    $response['accessories'][$i]['model']=$row['MODEL'];
    $response['accessories'][$i]['buyingPrice']=$row['BUYING_PRICE'];
    $response['accessories'][$i]['priceHTVA']=$row['PRICE_HTVA'];
    $response['accessories'][$i]['leasingPrice']=$row['leasingPrice'];
    $response['accessories'][$i]['stock']=$row['STOCK'];
    $response['accessories'][$i]['showAccessories']=$row['DISPLAY'];
    $response['accessories'][$i]['description']=$row['DESCRIPTION'];
    $response['accessories'][$i]['accessoriesCategories']=$row['ACCESSORIES_CATEGORIES'];
    $response['accessories'][$i]['categoryId']=$row['CATEGORIES_ID'];
    $response['accessories'][$i]['category']=$row['CATEGORY'];
    $i++;
}


echo json_encode($response);
die;
?>
