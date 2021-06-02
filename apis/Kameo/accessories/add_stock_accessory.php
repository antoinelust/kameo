<?php

$modelID = isset($_POST["model"]) ? addslashes($_POST["model"]) : NULL;
$companyID = isset($_POST["company"]) ? addslashes($_POST["company"]) : NULL;
$userEMAIL = isset($_POST["user"]) ? addslashes($_POST["user"]) : NULL;
$contractType = isset($_POST["contractType"]) ? addslashes($_POST["contractType"]) : NULL;
$contractStart = isset($_POST["contractStart"]) ? ($_POST["contractStart"] != '' ? $_POST["contractStart"] : NULL) : NULL;
$contractEnd = isset($_POST["contractEnd"]) ? ($_POST["contractEnd"] != '' ? $_POST["contractEnd"] : NULL) : NULL;
$leasingAmount = isset($_POST["leasingAmount"]) ? ($_POST["leasingAmount"] != '' ? $_POST["leasingAmount"] : NULL) : NULL;
$sellingDate = isset($_POST["sellingDate"]) ? ($_POST["sellingDate"] != '' ? $_POST["sellingDate"] : NULL) : NULL;
$sellingAmount=isset($_POST["sellingAmount"]) ? ($_POST["sellingAmount"] != '' ? $_POST["sellingAmount"] : NULL) : NULL;
$action=isset($_POST['action']) ? $_POST['action'] : NULL;
$bikeID=isset($_POST['bike']) ? $_POST['bike'] : NULL;
$numberToOrder = isset($_POST['numberArticle']) ? $_POST['numberArticle'] : NULL;
$estimatedDeliveryDate = ($_POST["estimateDeliveryDate"] != '') ? $_POST["estimateDeliveryDate"] : NULL;
$deliveryDate = isset($_POST["deliveryDate"]) ? (($_POST['deliveryDate'] != '') ? $_POST['deliveryDate'] : NULL) : NULL;


if($modelID != '' && $contractType != '') {

  if($contractType == 'leasing') {
    $sellingDate = null;
    $sellingAmount = null;
  }else{
    $contractStart = null;
    $contractEnd = null;
    $leasingAmount = null;
  }

  include '../connexion.php';
/// voir coment ajouyter les id de velo ou bike
  if($action=="addStockAccessory"){
    $i=0;
    while($i<$numberToOrder){
      execSQL("INSERT INTO accessories_stock (USR_MAJ, COMPANY_ID, USER_EMAIL, CATALOG_ID, CONTRACT_TYPE, CONTRACT_START,  CONTRACT_END, CONTRACT_AMOUNT, SELLING_DATE, SELLING_AMOUNT, STAANN, BIKE_ID,ESTIMATED_DELIVERY_DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '',?,?) ", array("sisisssdsdis", $token, $companyID, $userEMAIL, $modelID, $contractType, $contractStart, $contractEnd, $leasingAmount, $sellingDate, $sellingAmount, $bikeID,$estimatedDeliveryDate), true);
      $i++;
    }
    successMessage("SM0028");
  }else if($action=="updateStockAccessory"){
    $ID = isset($_POST["ID"]) ? addslashes($_POST["ID"]) : NULL;

    if($contractType=="selling"){
      execSQL("UPDATE accessories_stock set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_ID=?, USER_EMAIL=?, CATALOG_ID=?, CONTRACT_TYPE=?, SELLING_DATE=?, SELLING_AMOUNT=?,BIKE_ID=? WHERE ID=? ", array("sisissdii", $token, $companyID, $userEMAIL, $modelID, $contractType, $sellingDate, $sellingAmount,$bikeID, $ID), true);
    }else if($contractType=="leasing"){
      execSQL("UPDATE accessories_stock set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_ID=?, USER_EMAIL=?, CATALOG_ID=?, CONTRACT_TYPE=?, CONTRACT_START=?, CONTRACT_END=?, CONTRACT_AMOUNT=?, BIKE_ID=? WHERE ID=? ", array("sisisssdii", $token, $companyID, $userEMAIL, $modelID, $contractType, $contractStart, $contractEnd, $leasingAmount,$bikeID, $ID), true);
    }
    else if($contractType=="stock" || $contractType=="pending_delivery"){
      if($contractType=="stock"){
        $companyID=12;
      }
      execSQL("UPDATE accessories_stock set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_ID=?, USER_EMAIL=?, CATALOG_ID=?, CONTRACT_TYPE=?,  BIKE_ID=?, DELIVERY_DATE=? WHERE ID=? ", array("sisisisi", $token, $companyID, $userEMAIL, $modelID, $contractType ,$bikeID,$deliveryDate, $ID), true);
    }
    else if($contractType=="order"){
      execSQL("UPDATE accessories_stock set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_ID=?, USER_EMAIL=?, CATALOG_ID=?, CONTRACT_TYPE=?,  BIKE_ID=?, ESTIMATED_DELIVERY_DATE=? WHERE ID=? ", array("sisisisi", $token, $companyID, $userEMAIL, $modelID, $contractType,$bikeID,$estimatedDeliveryDate, $ID), true);
  }
  successMessage("SM0028");
}
}else{
  $response = array ('response'=>'error');
  echo json_encode($response);
  die;
}
?>
