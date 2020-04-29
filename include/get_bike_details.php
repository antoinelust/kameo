<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$frameNumber=isset($_POST['frameNumber']) ? $_POST['frameNumber'] : NULL;
$id=isset($_POST['id']) ? $_POST['id'] : NULL;
$company=isset($_POST['company']) ? $_POST['company'] : NULL;

$response=array();


if($frameNumber != NULL || $id != NULL)
{


    include 'connexion.php';
    if($frameNumber != NULL){
        $sql="SELECT *  FROM customer_bikes WHERE FRAME_NUMBER = '$frameNumber'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
    }
    else if($id != NULL){
        $sql="SELECT *  FROM customer_bikes WHERE ID = '$id'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
    }

    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    $row = mysqli_fetch_assoc($result);
    $response['response']="success";
    $response['id']=$row['ID'];
    $response['frameNumber']=$frameNumber;
    $response['model']=$row['MODEL'];
    $response['type']=$row['TYPE'];
    $response['frameReference']=$row['FRAME_REFERENCE'];
    $response['lockerReference']=$row['LOCKER_REFERENCE'];
    $response['company']=$row['COMPANY'];
    $response['size']=$row['SIZE'];
    $response['leasing']=$row['AUTOMATIC_BILLING'];
    $response['insurance']=$row['INSURANCE'];
    $response['leasingPrice']=$row['LEASING_PRICE'];
    $response['bikePrice']=$row['BIKE_PRICE'];
    $response['buyingDate']=$row['BIKE_BUYING_DATE'];
    $response['billingGroup']=$row['BILLING_GROUP'];
    $response['billingType']=$row['BILLING_TYPE'];
    $response['contractType']=$row['CONTRACT_TYPE'];
    $response['contractStart']=$row['CONTRACT_START'];
    $response['contractEnd']=$row['CONTRACT_END'];
		$response['soldPrice']=$row['SOLD_PRICE'];

    $response['status']=$row['STATUS'];
    if(!$company){
        $company=$row['COMPANY'];
    }
    include 'connexion.php';
    $sql="SELECT bb.BUILDING_REFERENCE, bb.BUILDING_FR FROM bike_building_access aa, building_access bb WHERE aa.BIKE_NUMBER='$frameNumber' and BUILDING_REFERENCE=aa.BUILDING_CODE and aa.STAANN!='D' and COMPANY='$company'";


    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['building'][$i]['buildingCode']=$row['BUILDING_REFERENCE'];
        $response['building'][$i]['access']=true;
        $response['building'][$i]['descriptionFR']=$row['BUILDING_FR'];
        $i++;
    }


    include 'connexion.php';
    $sql="SELECT BUILDING_REFERENCE, BUILDING_FR FROM building_access WHERE COMPANY = '$company' AND not exists (select 1 from bike_building_access bb where bb.BUILDING_CODE=BUILDING_REFERENCE and bb.BIKE_NUMBER='$frameNumber' and bb.STAANN!='D')";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $response['buildingNumber'] = $result->num_rows + $length;
    while($row = mysqli_fetch_array($result)){
        $response['building'][$i]['buildingCode']=$row['BUILDING_REFERENCE'];
        $response['building'][$i]['access']=false;
        $response['building'][$i]['descriptionFR']=$row['BUILDING_FR'];
        $i++;
    }

    include 'connexion.php';
    $sql="SELECT aa.NOM, aa.PRENOM, aa.EMAIL FROM customer_referential aa, customer_bike_access bb WHERE aa.COMPANY='$company' AND aa.EMAIL=bb.EMAIL and aa.STAANN != 'D' and bb.BIKE_NUMBER='$frameNumber' and bb.STAANN!='D' ORDER BY NOM";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['user'][$i]['name']=$row['NOM'];
        $response['user'][$i]['firstName']=$row['PRENOM'];
        $response['user'][$i]['email']=$row['EMAIL'];
        $response['user'][$i]['access']=true;
        $i++;
    }


    include 'connexion.php';
    $sql="SELECT aa.NOM, aa.PRENOM, aa.EMAIL FROM customer_referential aa WHERE aa.COMPANY='$company' AND NOT EXISTS (select 1 from customer_bike_access bb WHERE aa.EMAIL=bb.EMAIL and bb.BIKE_NUMBER='$frameNumber' and bb.STAANN!='D') ORDER BY NOM";
    if($company){
        $sql=$sql." AND COMPANY='$company'";
    }

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $length = $length + $result->num_rows;
    while($row = mysqli_fetch_array($result)){
        $response['user'][$i]['name']=$row['NOM'];
        $response['user'][$i]['firstName']=$row['PRENOM'];
        $response['user'][$i]['email']=$row['EMAIL'];
        $response['user'][$i]['access']=false;
        $i++;
    }

    $response['userNumber']=$length;

    echo json_encode($response);
    die;
}
else
{
	errorMessage("ES0006");
}

?>
