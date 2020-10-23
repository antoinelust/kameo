<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


$id=isset($_POST['bikeID']) ? $_POST['bikeID'] : NULL;
$company=isset($_POST['company']) ? $_POST['company'] : NULL;

$response=array();


if($id != NULL)
{


    include 'connexion.php';
    $sql="SELECT * FROM customer_bikes  WHERE ID = '$id'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $response['response']="success";
    $response['id']=$row['ID'];
    $response['frameNumber']=$row['FRAME_NUMBER'];
    $response['model']=$row['MODEL'];
    $response['type']=$row['TYPE'];
    $response['frameReference']=$row['FRAME_REFERENCE'];
    $response['lockerReference']=$row['LOCKER_REFERENCE'];
    $response['company']=$row['COMPANY'];
    $response['size']=$row['SIZE'];
    $response['color']=$row['COLOR'];
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
    $response['deliveryDate']=$row['DELIVERY_DATE'];
    $response['soldPrice']=$row['SOLD_PRICE'];
    $response['bikeBuyingDate']=$row['BIKE_BUYING_DATE'];
    $response['orderNumber']=$row['ORDER_NUMBER'];
    $response['offerID']=$row['OFFER_ID'];
    if($company == NULL){
        $company=$row['COMPANY'];
    }
	$conn->close();

    $catalogID=$row['TYPE'];

    include 'connexion.php';
    $sql="SELECT * FROM bike_catalog WHERE ID='$catalogID'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);


    $file=__DIR__.'/images_bikes/'.$resultat['ID'].'jpg';
    if ((file_exists($file))){
        $response['img']=__DIR__.'/images_bikes/'.$resultat['ID'];
    }else{
        $response['img']=strtolower(str_replace(" ", "-", $resultat['BRAND']))."_".strtolower(str_replace(" ", "-", $resultat['MODEL']))."_".strtolower($resultat['FRAME_TYPE']);
    }

    $response['brand']=$resultat['BRAND'];
    $response['modelCatalog']=$resultat['MODEL'];
    $response['catalogPrice']=$resultat['PRICE_HTVA'];
    $response['motor']=$resultat['MOTOR'];
    $response['battery']=$resultat['BATTERY'];
    $response['transmission']=$resultat['TRANSMISSION'];
    $response['license']=$resultat['LICENSE'];



    $response['status']=$row['STATUS'];
    include 'connexion.php';
    $sql="SELECT bb.BUILDING_REFERENCE, bb.BUILDING_FR FROM bike_building_access aa, building_access bb WHERE aa.BIKE_ID='$id' and BUILDING_REFERENCE=aa.BUILDING_CODE and aa.STAANN!='D' and COMPANY='$company'";


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
    $sql="SELECT BUILDING_REFERENCE, BUILDING_FR FROM building_access WHERE COMPANY = '$company' AND not exists (select 1 from bike_building_access bb where bb.BUILDING_CODE=BUILDING_REFERENCE and bb.BIKE_ID='$id' and bb.STAANN!='D')";
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
    $sql="SELECT aa.NOM, aa.PRENOM, aa.EMAIL, bb.TYPE FROM customer_referential aa, customer_bike_access bb WHERE aa.COMPANY='$company' AND aa.EMAIL=bb.EMAIL and aa.STAANN != 'D' and bb.BIKE_ID='$id' and bb.STAANN!='D' ORDER BY NOM";

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

    $sql = "SELECT TYPE FROM customer_bike_access WHERE BIKE_ID='$id'";
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    if($resultat['TYPE']){
        $response['biketype']=$resultat['TYPE'];
    }else{
        $response['biketype']='partage';
    }


    include 'connexion.php';
    $sql="SELECT aa.NOM, aa.PRENOM, aa.EMAIL FROM customer_referential aa WHERE aa.COMPANY='$company' AND NOT EXISTS (select 1 from customer_bike_access bb WHERE aa.EMAIL=bb.EMAIL and bb.BIKE_ID='$id' and bb.STAANN!='D') ORDER BY NOM";
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
