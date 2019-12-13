<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


$response=array();

include 'connexion.php';
$sql="SELECT * from companies";


$type=isset($_POST['type']) ? $_POST['type'] : NULL;


if($type!="*" && $type != NULL){
    $sql=$sql." WHERE TYPE='$type'";
}

$sql=$sql." ORDER BY COMPANY_NAME";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);        
$response['companiesNumber'] = $result->num_rows;
$i=0;
$response['response']="success";
while($row = mysqli_fetch_array($result)){
    $response['company'][$i]['ID']=$row['ID'];
    $response['company'][$i]['companyName']=$row['COMPANY_NAME'];
    $response['company'][$i]['internalReference']=$row['INTERNAL_REFERENCE'];
    $response['company'][$i]['type']=$row['TYPE'];
    $internalReference=$row['INTERNAL_REFERENCE'];
    $sql2="SELECT * FROM customer_bikes WHERE COMPANY='$internalReference'";
    if ($conn->query($sql2) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result2 = mysqli_query($conn, $sql2);        
    $response['company'][$i]['companyBikeNumber'] = $result2->num_rows;
    $bikeAccessStatus="OK";
    $customerBuildingStatus="OK";
    
    if($response['company'][$i]['companyBikeNumber']==0){
        $bikeAccessStatus="KO";
    }
    while($row2 = mysqli_fetch_array($result2)){
        $bikeReference=$row2['FRAME_NUMBER'];
        $sql3="SELECT * from customer_bike_access where BIKE_NUMBER='$bikeReference' and STAANN!='D'";
        if ($conn->query($sql3) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result3 = mysqli_query($conn, $sql3);     
        if($result3->num_rows=='0'){
            $bikeAccessStatus="KO";
        }
    }
    
    $sql3="SELECT * from customer_building_access where EMAIL in (select EMAIL from customer_referential where COMPANY='$internalReference') and BUILDING_CODE in (select BUILDING_REFERENCE FROM building_access where COMPANY='$internalReference')";
    if ($conn->query($sql3) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result3 = mysqli_query($conn, $sql3);     
    if($result3->num_rows=='0'){
        $customerBuildingStatus="KO";
    }else{
        $sql4="SELECT * from building_access where COMPANY='$internalReference'";
        if ($conn->query($sql4) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result4 = mysqli_query($conn, $sql4);     
        while($row4 = mysqli_fetch_array($result4)){
            $buildingReference=$row4['BUILDING_REFERENCE'];
            $sql5="SELECT * from customer_building_access where BUILDING_CODE='$buildingReference' and STAANN!='D'";
            if ($conn->query($sql5) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result5 = mysqli_query($conn, $sql5);     
            if($result5->num_rows=='0'){
                $customerBuildingStatus="KO";
            }
        }
    }
    $response['company'][$i]['bikeAccessStatus'] = $bikeAccessStatus;
    $response['company'][$i]['customerBuildingAccess'] = $customerBuildingStatus;
    $i++;
}
    
    


echo json_encode($response);
die;



?>