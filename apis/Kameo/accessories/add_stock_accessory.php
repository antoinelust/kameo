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
    $stmt = $conn->prepare("INSERT INTO accessories_stock (USR_MAJ, COMPANY_ID, USER_EMAIL, CATALOG_ID, CONTRACT_TYPE, CONTRACT_START,  CONTRACT_END, CONTRACT_AMOUNT, SELLING_DATE, SELLING_AMOUNT, STAANN, BIKE_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '',?) ");
    if ($stmt)
    {
        $stmt->bind_param("sisisssdsdi", $token, $companyID, $userEMAIL, $modelID, $contractType, $contractStart, $contractEnd, $leasingAmount, $sellingDate, $sellingAmount, $bikeID);
        $stmt->execute();
    }else
        error_message('500', $conn->error);

    successMessage("SM0028");
  }else if($action=="updateStockAccessory"){
      $ID = isset($_POST["ID"]) ? addslashes($_POST["ID"]) : NULL;
      if($contractType=="achat"){
        $stmt = $conn->prepare("UPDATE accessories_stock set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_ID=?, USER_EMAIL=?, CATALOG_ID=?, CONTRACT_TYPE='achat', SELLING_DATE=?, SELLING_AMOUNT=?,BIKE_ID=? WHERE ID=? ");
        if ($stmt)
        {
          $stmt->bind_param("sisisdii", $token, $companyID, $userEMAIL, $modelID, $sellingDate, $sellingAmount,$bikeID, $ID);
          $stmt->execute();
        }else
            error_message('500', $conn->error);
      }else if($contractType=="leasing"){
        $stmt = $conn->prepare("UPDATE accessories_stock set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_ID=?, USER_EMAIL=?, CATALOG_ID=?, CONTRACT_TYPE=?, CONTRACT_START=?, CONTRACT_END=?, CONTRACT_AMOUNT=?, BIKE_ID=? WHERE ID=? ");
        if ($stmt)
        {
          $stmt->bind_param("sisisssdii", $token, $companyID, $userEMAIL, $modelID, $contractType, $contractStart, $contractEnd, $leasingAmount,$bikeID, $ID);
          $stmt->execute();
        }else
            error_message('500', $conn->error);
      }
      else {
         $stmt = $conn->prepare("UPDATE accessories_stock set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_ID=?, USER_EMAIL=?, CATALOG_ID=?, CONTRACT_TYPE=?,  BIKE_ID=? WHERE ID=? ");
        if ($stmt)
        {
          $stmt->bind_param("sisisii", $token, $companyID, $userEMAIL, $modelID, $contractType,$bikeID, $ID);
          $stmt->execute();
        }else
            error_message('500', $conn->error);
      }
      successMessage("SM0028");
  }
}else{
    $response = array ('response'=>'error');
    echo json_encode($response);
    die;
}
?>
