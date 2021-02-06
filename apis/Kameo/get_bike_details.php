<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
require_once 'authentication.php';
$token = getBearerToken();

log_inputs($token);


$id=isset($_POST['bikeID']) ? addslashes($_POST['bikeID']) : NULL;
$company=isset($_POST['company']) ? addslashes($_POST['company']) : NULL;

$response=array();
include 'connexion.php';

if($id == null){
  $sql="SELECT BIKE_ID FROM customer_referential, customer_bike_access WHERE TOKEN='$token' AND customer_referential.EMAIL=customer_bike_access.EMAIL AND customer_referential.STAANN != 'D' AND customer_bike_access.STAANN != 'D'";
  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }
  $result = mysqli_query($conn, $sql);
  $resultat = mysqli_fetch_assoc($result);
  $id=$resultat['BIKE_ID'];
}else{
  $sql="SELECT CASE WHEN aa.COMPANY=bb.COMPANY THEN 'true' WHEN bb.COMPANY='KAMEO' THEN 'true' ELSE 'false' END as 'bikeAccess'  FROM customer_bikes aa, customer_referential bb WHERE bb.TOKEN='$token' AND aa.ID='$id'";
  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }
  $result = mysqli_query($conn, $sql);
  $resultat = mysqli_fetch_assoc($result);
  if($resultat['bikeAccess'] == 'false'){
    error_message('403', 'Insufficient privilegies to consult that bike');
  }
}

if($id != NULL)
{


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
    $response['sellingDate']=$row['SELLING_DATE'];
    $response['billingGroup']=$row['BILLING_GROUP'];
    $response['billingType']=$row['BILLING_TYPE'];
    $response['contractType']=$row['CONTRACT_TYPE'];
    $response['contractStart']=$row['CONTRACT_START'];
    $response['contractEnd']=$row['CONTRACT_END'];
    $response['estimatedDeliveryDate']=$row['ESTIMATED_DELIVERY_DATE'];
    $response['deliveryDate']=$row['DELIVERY_DATE'];
    $response['soldPrice']=$row['SOLD_PRICE'];
    $response['bikeBuyingDate']=$row['BIKE_BUYING_DATE'];
    $response['orderNumber']=$row['ORDER_NUMBER'];
    $response['offerID']=$row['OFFER_ID'];
    $response['gpsID']=$row['GPS_ID'];
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

    $response['img']=$resultat['ID'];

    $response['brand']=$resultat['BRAND'];
    $response['modelCatalog']=$resultat['MODEL'];
    $response['catalogPrice']=$resultat['PRICE_HTVA'];
    $response['motor']=$resultat['MOTOR'];
    $response['battery']=$resultat['BATTERY'];
    $response['transmission']=$resultat['TRANSMISSION'];

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
    $sql="SELECT aa.NOM,aa.PHONE,aa.PRENOM, aa.EMAIL, bb.TYPE
    FROM customer_referential aa, customer_bike_access bb
    WHERE aa.COMPANY='$company'
    AND aa.EMAIL=bb.EMAIL and aa.STAANN != 'D'
    and bb.BIKE_ID='$id'
    and bb.STAANN!='D' ORDER BY NOM";

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
        $response['user'][$i]['phone']=$row['PHONE'];
        $response['user'][$i]['access']=true;
        $i++;
    }

    $sql = "SELECT EMAIL FROM customer_bike_access WHERE TYPE='personnel' AND TYPE!='D' AND BIKE_ID='$id'";
    $result = mysqli_query($conn, $sql);
    $personnelBool = $result->num_rows;
    if($personnelBool>0){
      $resultat = mysqli_fetch_assoc($result);
      $response['biketype']='personnel';
      $response['bikeOwner']=$resultat['EMAIL'];
    }else{
      $response['biketype']="partage";
    }

    include 'connexion.php';
    $sql="SELECT aa.NOM, aa.PHONE, aa.PRENOM, aa.EMAIL
    FROM customer_referential aa
    WHERE aa.COMPANY='$company'
    AND NOT EXISTS (select 1 from customer_bike_access bb
    WHERE aa.EMAIL=bb.EMAIL and bb.BIKE_ID='$id' and bb.STAANN!='D') ORDER BY NOM";

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
        $response['user'][$i]['phone']=$row['PHONE'];
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
