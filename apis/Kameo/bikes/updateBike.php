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



$user=$_POST['user'];
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
$type_bike = $_POST['bikeType'];

$buyingPrice=isset($_POST['price']) ? $_POST['price'] : NULL;
$buyingDate=isset($_POST['buyingDate']) ? $_POST['buyingDate'] : NULL;
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
if($bikeID != NULL && $user != NULL)
{
  if($frameNumberOriginel != $frameNumber){
    execSQL("update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', FRAME_NUMBER='$frameNumber' where ID = '$bikeID'", array(), true);
  }
  if($contractType=="order"){
    execSQL("update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ=?, MODEL=, TYPE=?, SIZE=?, COLOR=?,  CONTRACT_TYPE=?, COMPANY=?, FRAME_REFERENCE=?, LOCKER_REFERENCE=?, GPS_ID=?, BIKE_BUYING_DATE=?, ESTIMATED_DELIVERY_DATE=?, DELIVERY_DATE=?, ORDER_NUMBER=?, OFFER_ID=?, EMAIL=? where ID =?", array('ssissssssssssssi', $user, $model, $portfolioID, $size, $color, $contractType, $company, $frameReference, $lockerReference, $gpsID, $orderingDate, $estimatedDeliveryDate, $deliveryDate, $orderNumber, $offerReference, $clientReference, $bikeID), true);
  }else{
    execSQL("update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ=?, MODEL=?, TYPE=?, SIZE='$size', COLOR=?, CONTRACT_TYPE=?, CONTRACT_START=?, CONTRACT_END=?, COMPANY=?, FRAME_REFERENCE=?, LOCKER_REFERENCE=?, GPS_ID=?, BIKE_BUYING_DATE=?, AUTOMATIC_BILLING=?, INSURANCE=?, BILLING_TYPE=?, LEASING_PRICE=?, BILLING_GROUP=?, BIKE_PRICE=?, SOLD_PRICE = ?, EMAIL=?, ADDRESS=? where ID = ?", array('ssissssssssssssssddssi', $user, $model, $portfolioID, $color, $contractType, $contractStart, $contractEnd, $company, $frameReference, $lockerReference, $gpsID, $buyingDate, $automaticBilling, $insurance, $billingType, $billingPrice, $billingGroup, $buyingPrice, $sellPrice, $clientReference, $address, $bikeID), true);
  }
}
else
{
    errorMessage("ES0012");
}

$customerBikes=array();
$customerBikes=execSQL("SELECT * FROM customer_bikes WHERE ID='$bikeID' and COMPANY='$company'", array(), false);

if(count($customerBikes)==0){
  execSQL("UPDATE customer_bikes SET COMPANY='$company' WHERE ID='$bikeID'", array(), true);
}


if(isset($_POST['buildingAccess'])){

  $buildings=array();
  $buildings=execSQL("SELECT * FROM bike_building_access WHERE BIKE_ID='$bikeID' AND STAANN != 'D'", array(), false);

  foreach((array) $buildings as $row){
    $presence=false;
    foreach($_POST['buildingAccess'] as $valueInArray){
      if($row['BUILDING_CODE']==$valueInArray){
          $presence=true;
      }
    }
    $buildingCode=$row['BUILDING_CODE'];
    if($presence==false){
      execSQL("update bike_building_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BUILDING_CODE = '$buildingCode' and BIKE_ID='$bikeID'", array(), true);
    }
  }


  foreach($_POST['buildingAccess'] as $valueInArray){
    $bikeBuildingAccess=execSQL("select * FROM bike_building_access WHERE BUILDING_CODE='$valueInArray' and BIKE_ID='$bikeID'", array(), false);
    if(count($bikeBuildingAccess)==0){
      execSQL("INSERT INTO  bike_building_access (USR_MAJ, TIMESTAMP, BUILDING_CODE, BIKE_ID, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$bikeID', '')", array(), true);
    }else{
      $exsitingBikeBuildingAccess=execSQL("select * FROM bike_building_access WHERE BUILDING_CODE='$valueInArray' and BIKE_ID='$bikeID' and STAANN = 'D'", array(), false);
      if(count($exsitingBikeBuildingAccess)==1){
        execSQL("update bike_building_access SET STAANN='' WHERE BUILDING_CODE='$valueInArray' and BIKE_ID='$bikeID'", array(), true);
      }
    }
  }
}else{
    execSQL("update bike_building_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BIKE_ID='$bikeID' and STAANN != 'D'", array(), true);
}

if(isset($_POST['userAccess'])){

    $informationCustomerBikeAccess=array();
    $informationCustomerBikeAccess=execSQL("SELECT * FROM customer_bike_access WHERE ID='$bikeID' AND STAANN != 'D'", array(), false);
    foreach((array) $informationCustomerBikeAccess as $row){
        $presence=false;
        foreach($_POST['userAccess'] as $valueInArray){
            if($row['EMAIL']==$valueInArray){
                $presence=true;
            }
        }
        $emailUser=$row['EMAIL'];

        if($presence==false){
            execSQL("update customer_bike_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = '$emailUser' and BIKE_ID='$bikeID'", array(), true);
        }
    }




    if( $type_bike == 'partage'){
        foreach($_POST['userAccess'] as $valueInArray){
          $informationCustomerBikeAccess=array();
            $informationCustomerBikeAccess=execSQL("select * FROM customer_bike_access WHERE EMAIL='$valueInArray' and BIKE_ID='$bikeID'", array(), false);
            if(count($informationCustomerBikeAccess)==0){
                execSQL("INSERT INTO  customer_bike_access (USR_MAJ, TIMESTAMP, EMAIL, BIKE_ID, TYPE, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$bikeID', '$type_bike', '')", array(), true);
            }else{
              $existingCustomerBikeAccess=array();
              $existingCustomerBikeAccess=execSQL("select * FROM customer_bike_access WHERE EMAIL='$valueInArray' and BIKE_ID='$bikeID' and STAANN = 'D'", array(), false);
              if(count($existingCustomerBikeAccess)==1){
                execSQL("update customer_bike_access SET USR_MAJ='$user', TYPE='$type_bike', TIMESTAMP=CURRENT_TIMESTAMP, STAANN='' WHERE EMAIL='$valueInArray' and BIKE_ID='$bikeID'", array(), true);
              }
            }
        }
    }
}else{
    execSQL("update customer_bike_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BIKE_ID='$bikeID' and STAANN != 'D'", array(), true);
}

if(isset($_POST['bikeType']) && $_POST['bikeType'] == "personnel"){
    execSQL("DELETE FROM customer_bike_access WHERE BIKE_ID = '$bikeID'", array(), true);
    $email = $_POST['email'];
    execSQL("INSERT INTO customer_bike_access (TIMESTAMP, USR_MAJ, EMAIL, BIKE_ID, TYPE, STAANN) VALUES (CURRENT_TIMESTAMP, '$user', '$email', '$bikeID', '$type_bike', '')", array(), true);
}

if(isset($_POST['contractStart']) && isset($_POST['contractEnd'])){

    if(isset($_POST['contractType'])){
        if ($_POST['contractType'] == 'leasing'){
            $dates = plan_maintenances($_POST['contractStart'], $_POST['contractEnd']);

            for ($i=0; $i < sizeof($dates); $i++) {
                $next_date = $dates[$i];
                $num_m = array_search($next_date, $dates) + 1;

                execSQL("INSERT INTO entretiens (HEU_MAJ, USR_MAJ, BIKE_ID, DATE, STATUS, NR_ENTR)
                SELECT CURRENT_TIMESTAMP, '$user', '$bikeID', '$next_date', 'AUTOMATICALY_PLANNED', '$num_m'
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
