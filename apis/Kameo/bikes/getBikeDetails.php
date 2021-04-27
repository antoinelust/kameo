<?php

$id=isset($_GET['bikeID']) ? addslashes($_GET['bikeID']) : NULL;
$response=array();

$response=execSQL("SELECT CASE WHEN aa.COMPANY=bb.COMPANY THEN 'true' WHEN bb.COMPANY='KAMEO' THEN 'true' ELSE 'false' END as 'bikeAccess'  FROM customer_bikes aa, customer_referential bb WHERE bb.TOKEN='$token' AND aa.ID=?", array('i', $id), false)[0];
if($response['bikeAccess'] == 'false'){
  error_message('403', 'Insufficient privilegies to consult that bike');
}

$row=execSQL("SELECT * FROM customer_bikes  WHERE ID = ?", array('i', $id), false)[0];

$company=$row['COMPANY'];

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
$response['localisation']=$row['LOCALISATION'];
$catalogID=$row['TYPE'];

$resultat=execSQL("SELECT * FROM bike_catalog WHERE ID=?", array('i', $catalogID), false)[0];

$response['img']=$resultat['ID'];
$response['brand']=$resultat['BRAND'];
$response['modelCatalog']=$resultat['MODEL'];
$response['catalogPrice']=$resultat['PRICE_HTVA'];
$response['motor']=$resultat['MOTOR'];
$response['battery']=$resultat['BATTERY'];
$response['transmission']=$resultat['TRANSMISSION'];
$response['possibleSizes']=$resultat['SIZES'];
$response['status']=$row['STATUS'];




$buildings=array();
$buildings[]=execSQL("SELECT bb.BUILDING_REFERENCE as buildingCode, bb.BUILDING_FR as descriptionFR, 'true' as access FROM bike_building_access aa, building_access bb WHERE aa.BIKE_ID=? and BUILDING_REFERENCE=aa.BUILDING_CODE and aa.STAANN!='D' and COMPANY=?", array('is', $id, $company), false);
$buildings[]=execSQL("SELECT BUILDING_REFERENCE as buildingCode, BUILDING_FR as descriptionFR, 'false' as access FROM building_access WHERE COMPANY = ? AND not exists (select 1 from bike_building_access bb where bb.BUILDING_CODE=BUILDING_REFERENCE and bb.BIKE_ID=? and bb.STAANN!='D')", array('si', $company, $id), false);
$response['building'] = array();
foreach($buildings as $arr) {
  if(is_array($arr)) {
    $response['building'] = array_merge($response['building'], $arr);
  }
}

$users=array();

$users[]=execSQL("SELECT aa.NOM as name,aa.PHONE as phone, aa.PRENOM as firstName, aa.EMAIL as email, 'true' as access FROM customer_referential aa, customer_bike_access bb WHERE aa.COMPANY=? AND aa.EMAIL=bb.EMAIL and aa.STAANN != 'D' and bb.BIKE_ID=? and bb.STAANN!='D' ORDER BY NOM", array('si', $company, $id), false);
$users[]=execSQL("SELECT aa.NOM as name,aa.PHONE as phone, aa.PRENOM as firstName, aa.EMAIL as email, 'false' as access FROM customer_referential aa WHERE aa.COMPANY=? AND NOT EXISTS (select 1 from customer_bike_access bb WHERE aa.EMAIL=bb.EMAIL and bb.BIKE_ID=? and bb.STAANN!='D') ORDER BY NOM", array('si', $company, $id), false);

$response['user'] = array();
foreach($users as $arr) {
  if(is_array($arr)) {
    $response['user'] = array_merge($response['user'], $arr);
  }
}



$emailOwner=execSQL("SELECT EMAIL FROM customer_bike_access WHERE TYPE='personnel' AND TYPE!='D' AND BIKE_ID=?", array('i', $id), false)[0]['EMAIL'];
if(!is_null($emailOwner)){
  $response['biketype']='personnel';
  $response['bikeOwner']=$emailOwner;
  $response['deliveryDateOrder']=execSQL("SELECT ESTIMATED_DELIVERY_DATE FROM client_orders WHERE EMAIL=?", array('s', $emailOwner), false)[0]['ESTIMATED_DELIVERY_DATE'];
  if(is_null($response['deliveryDate'])){
    $response['deliveryDateOrder']='';
  }
}else{
  $response['biketype']="partage";
  $response['deliveryDateOrder']='';
}

echo json_encode($response);
die;

?>
