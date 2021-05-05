<?php


function plan_maintenances($start, $end)
{
    $maintenances = array();
    $date = date('Y-m-d', strtotime("+3 months", strtotime($start)));
    while ($date <= $end) {
        $maintenances[] = $date;
        $date = date('Y-m-d', strtotime("+9 months", strtotime($date)));
    }
    return $maintenances;
}

$bikeOwner=isset($_POST['name']) ? $_POST['name'] : NULL;
$company=$_POST['company'];
$bikeID=isset($_POST['bikeID']) ? $_POST['bikeID'] : NULL;
$model=$_POST['model'];
$frameNumberOriginel=$_POST['frameNumberOriginel'];
$frameNumber=$_POST['frameNumber'];
$size=$_POST['size'];
$color=isset($_POST['color']) ? $_POST['color'] : NULL;
$portfolioID=$_POST['portfolioID'];
$frameReference=$_POST['frameReference'];
$lockerReference=$_POST['lockerReference'];
$gpsID=$_POST['gpsID'];
$localisation=isset($_POST['localisation']) ? $_POST['localisation'] : NULL;
$type_bike = $_POST['bikeType'];
$buyingPrice=isset($_POST['price']) ? $_POST['price'] : NULL;
$contractType=isset($_POST['contractType']) ? $_POST['contractType'] : NULL;
$contractStart=isset($_POST['contractStart']) ? $_POST['contractStart'] : NULL;
$contractEnd=isset($_POST['contractEnd']) ? $_POST['contractEnd'] : NULL;
$sellPrice = isset($_POST['bikeSoldPrice']) ? $_POST['bikeSoldPrice'] : 0;
$orderingDate=isset($_POST['orderingDate']) ? $_POST['orderingDate'] : NULL;
$estimatedDeliveryDate=isset($_POST['estimatedDeliveryDate']) ? $_POST['estimatedDeliveryDate'] : NULL;
$deliveryDate=isset($_POST['deliveryDate']) ? $_POST['deliveryDate'] : NULL;

$orderNumber=isset($_POST['orderNumber']) ? $_POST['orderNumber'] : NULL;
$offerReference=isset($_POST['offerReference']) ? $_POST['offerReference'] : NULL;
$clientReference=isset($_POST['clientReference']) ? $_POST['clientReference'] : NULL;
$billingPrice=isset($_POST['billingPrice']) ? $_POST['billingPrice'] : NULL;
$billingType=isset($_POST['billingType']) ? $_POST['billingType'] : NULL;
$billingGroup=isset($_POST['billingGroup']) ? $_POST['billingGroup'] : NULL;
$address=isset($_POST['address']) ? ($_POST['address'] != '' ? $_POST['address'] : NULL) : NULL;

if($contractType=="stock" && $company != 'KAMEO'){
    errorMessage("ES0060");
}
if($contractType=="pending_delivery" && $company == 'KAMEO'){
    errorMessage("ES0069");
}

if($contractStart=='')
  $contractStart = NULL;

if($contractEnd == '')
  $contractEnd = NULL;

if($contractType=='stock' || $contractType=='test'){
  $billingPrice=0;
  $contractStart=NULL;
  $contractEnd=NULL;
  $automaticBilling="N";
  $sellingDate=NULL;
  $sellingPrice=0;
}

if($contractType=='test'){
  $deliverDate=NULL;
  $estimatedDeliveryDate=NULL;
}
if($contractType=='stock'){
  $localisation=isset($_POST['localisation']) ? $_POST['localisation'] : NULL;
}else{
  $localisation=NULL;
}

if($contractType=="leasing" || $contractType=="renting" || $contractType=="pending_delivery"){
  $sellingDate=NULL;
  $sellingPrice=0;
}

if(isset($_POST['billing'])){
    $automaticBilling="Y";
}else{
    $automaticBilling="N";
}
if(isset($_POST['insurance'])){
    $insurance="Y";
}else{
    $insurance="N";
}

$response=array();
if($frameNumberOriginel != $frameNumber){
  execSQL("update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$token', FRAME_NUMBER='$frameNumber' where ID = '$bikeID'", array(), true);
}
if($contractType=="order"){
  execSQL("update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ=?, MODEL=?, TYPE=?, SIZE=?, COLOR=?, CONTRACT_TYPE=?, COMPANY=?, BIKE_BUYING_DATE=?, ESTIMATED_DELIVERY_DATE=?, ORDER_NUMBER=?, OFFER_ID=? where ID =?", array('ssissssssssi', $token, $model, $portfolioID, $size, $color, $contractType, $company, $orderingDate, $estimatedDeliveryDate, $orderNumber, $offerReference, $bikeID), true);
}else{
  
  execSQL("update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ=?, MODEL=?, TYPE=?, SIZE='$size', COLOR=?, CONTRACT_TYPE=?, CONTRACT_START=?, CONTRACT_END=?, COMPANY=?, FRAME_REFERENCE=?, LOCKER_REFERENCE=?, GPS_ID=?, BIKE_BUYING_DATE=?, AUTOMATIC_BILLING=?, INSURANCE=?, BILLING_TYPE=?, LEASING_PRICE=?, BILLING_GROUP=?, BIKE_PRICE=?, SOLD_PRICE = ?, EMAIL=?, ADDRESS=?, DELIVERY_DATE=?, LOCALISATION=? where ID = ?", array('ssissssssssssssssddssssi', $token, $model, $portfolioID, $color, $contractType, $contractStart, $contractEnd, $company, $frameReference, $lockerReference, $gpsID, $orderingDate, $automaticBilling, $insurance, $billingType, $billingPrice, $billingGroup, $buyingPrice, $sellPrice, $clientReference, $address, $deliveryDate, $localisation, $bikeID), true);
}

$customerBikes=array();
$customerBikes=execSQL("SELECT * FROM customer_bikes WHERE ID='$bikeID' and COMPANY='$company'", array(), false);

if(count($customerBikes)==0){
  execSQL("UPDATE customer_bikes SET COMPANY='$company' WHERE ID='$bikeID'", array(), true);
}


if(isset($_POST['bikeType']) && $_POST['bikeType'] == "personnel"){
    execSQL("DELETE FROM customer_bike_access WHERE BIKE_ID = '$bikeID'", array(), true);
    execSQL("INSERT INTO customer_bike_access (TIMESTAMP, USR_MAJ, EMAIL, BIKE_ID, TYPE, STAANN) VALUES (CURRENT_TIMESTAMP, '$token', '$bikeOwner', '$bikeID', '$type_bike', '')", array(), true);
}

if(isset($_POST['contractStart']) && isset($_POST['contractEnd'])){
    if(isset($_POST['contractType'])){
        if ($_POST['contractType'] == 'leasing'){
            $dates = plan_maintenances($_POST['contractStart'], $_POST['contractEnd']);

            for ($i=0; $i < sizeof($dates); $i++) {
                $next_date = $dates[$i];
                $num_m = array_search($next_date, $dates) + 1;

                execSQL("INSERT INTO entretiens (HEU_MAJ, USR_MAJ, BIKE_ID, DATE, STATUS, NR_ENTR)
                SELECT CURRENT_TIMESTAMP, '$token', '$bikeID', '$next_date', 'AUTOMATICALY_PLANNED', '$num_m'
                FROM DUAL WHERE NOT EXISTS (SELECT * FROM entretiens WHERE BIKE_ID = '$bikeID' AND DATE(DATE + INTERVAL 3 MONTH) >= '$dates[$i]')", array(), true);
            }
        }
        if ($_POST['contractType'] == 'selling') {
            $date = date('Y-m-d', strtotime("+3 months", strtotime($_POST['contractStart'])));
            execSQL("INSERT INTO entretiens (HEU_MAJ, USR_MAJ, BIKE_ID, DATE, STATUS, NR_ENTR) VALUES (CURRENT_TIMESTAMP, '$user', '$bikeID', '$date', 'AUTOMATICALY_PLANNED', 1)", array(), true);
        }
    }
}

successMessage("SM0003");
?>