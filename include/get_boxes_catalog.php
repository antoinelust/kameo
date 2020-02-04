<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';



$response=array();

    include 'connexion.php';
    $sql="SELECT *  FROM boxes_catalog";
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
$response['boxesNumber']=$length;
$response['response']="success";
$conn->close();

$i=0;
while($row = mysqli_fetch_array($result))

{

    $response['boxes'][$i]['lastUpdate']=$row['HEU_MAJ'];
    $response['boxes'][$i]['userUpdate']=$row['USR_MAJ'];
    $response['boxes'][$i]['model']=$row['MODEL'];
    $response['boxes'][$i]['id']=$row['ID'];
    $response['boxes'][$i]['productionPrice']=$row['PRODUCTION_PRICE'];
    $response['boxes'][$i]['installationPrice']=$row['INSTALLATION_PRICE'];
    $response['boxes'][$i]['locationPrice']=$row['LOCATION_PRICE'];


    $i++;

}


echo json_encode($response);
die;
?>
