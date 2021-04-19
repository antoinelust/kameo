<?php

$model=$_POST['model'];
$idOrder = $_POST['bikeID'];
$bikeOwner = isset($_POST['name']) ? $_POST['name'] : NULL;
$frameNumber=$_POST['frameNumber'];
$size=$_POST['size'];
$color=isset($_POST['color']) ? $_POST['color'] : NULL;
$portfolioID=$_POST['portfolioID'];
$buyingPrice=$_POST['price'];
$frameReference=$_POST['frameReference'];
$company=$_POST['company'];
$bikeType=isset($_POST['bikeType']) ? $_POST['bikeType'] : NULL;
$buyingDate=isset($_POST['orderingDate']) ? $_POST['orderingDate'] : NULL;
$estimatedDeliveryDate=isset($_POST['estimatedDeliveryDate']) ? $_POST['estimatedDeliveryDate'] : NULL;
$deliveryDate=isset($_POST['deliveryDate']) ? $_POST['deliveryDate'] : NULL;
$orderNumber=isset($_POST['orderNumber']) ? $_POST['orderNumber'] : NULL;
$offerReference=isset($_POST['offerReference']) ? $_POST['offerReference'] : NULL;
$contractType=isset($_POST['contractType']) ? $_POST['contractType'] : NULL;

if($frameNumber != NULL){
  $result=execSQL("select * from customer_bikes where FRAME_NUMBER=?", array('s', $frameNumber), false);
  if(!is_null($result)){
      errorMessage("ES0036");
  }
}
include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$contractStart=NULL;
$contractEnd=NULL;
$frameNumber='TBC';
$frameReference='TBC';
$stmt = $conn->prepare("INSERT INTO  customer_bikes (USR_MAJ, HEU_MAJ, FRAME_NUMBER, TYPE, SIZE, COLOR, CONTRACT_TYPE, CONTRACT_START, CONTRACT_END, COMPANY, MODEL, FRAME_REFERENCE, LOCKER_REFERENCE, GPS_ID, AUTOMATIC_BILLING, BILLING_TYPE, LEASING_PRICE, STATUS, INSURANCE, BILLING_GROUP, BIKE_PRICE, BIKE_BUYING_DATE, STAANN, SOLD_PRICE, ESTIMATED_DELIVERY_DATE, DELIVERY_DATE, ORDER_NUMBER, OFFER_ID, EMAIL) VALUES (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'TBC', '', 'N', '', '0', 'OK', 'N', '1', ?, ?, '','0', ?, NULL, ?, ?, '')") or die ("Failed to prepare the statement : ".$conn->error);
$stmt->bind_param('ssissssssssdsssi', $token, $frameNumber, $portfolioID, $size, $color, $contractType, $contractStart, $contractEnd, $company, $model, $frameReference, $buyingPrice, $buyingDate, $estimatedDeliveryDate, $orderNumber, $offerReference);
if( !$stmt->execute() ){
  echo json_encode($stmt->error);
  die;
}
$bikeID = $conn->insert_id;
$stmt->close();
$conn->close();

if($bikeOwner && $bikeType=='personnel'){
  execSQL("INSERT INTO customer_bike_access (USR_MAJ, EMAIL, BIKE_ID, TYPE, STAANN) VALUES(?, ?, ?, 'personnel', '')", array('ssi', $token, $bikeOwner, $bikeID), true);
}
successMessage("SM0015");
?>
