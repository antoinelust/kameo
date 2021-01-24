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

if($modelID != '' && $companyID != '' && $contractType != '') {

  if($contractType == 'leasing') {
    $sellingDate = null;
    $sellingAmount = null;
  }else{
    $contractStart = null;
    $contractEnd = null;
    $leasingAmount = null;
  }

  include '../connexion.php';

  if($action=="addStockAccessory"){
    $stmt = $conn->prepare("INSERT INTO accessories_stock (USR_MAJ, COMPANY_ID, USER_EMAIL, CATALOG_ID, CONTRACT_TYPE, CONTRACT_START,  CONTRACT_END, CONTRACT_AMOUNT, SELLING_DATE, SELLING_AMOUNT, STAANN) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '') ");
    if ($stmt)
    {
        $stmt->bind_param("sisisssdsd", $token, $companyID, $userEMAIL, $modelID, $contractType, $contractStart, $contractEnd, $leasingAmount, $sellingDate, $sellingAmount);
        $stmt->execute();
    }else
        error_message('500', $conn->error);

    successMessage("SM0028");
  }else if($action=="updateStockAccessory"){
      $ID = isset($_POST["ID"]) ? addslashes($_POST["ID"]) : NULL;
      if($contractType=="achat"){
        $stmt = $conn->prepare("UPDATE accessories_stock set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_ID=?, USER_EMAIL=?, CATALOG_ID=?, CONTRACT_TYPE='achat', SELLING_DATE=?, SELLING_AMOUNT=? WHERE ID=? ");
        if ($stmt)
        {
          $stmt->bind_param("sisisdi", $token, $companyID, $userEMAIL, $modelID, $sellingDate, $sellingAmount, $ID);
          $stmt->execute();
        }else
            error_message('500', $conn->error);
      }else if($contractType=="leasing"){
        $stmt = $conn->prepare("UPDATE accessories_stock set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_ID=?, USER_EMAIL=?, CATALOG_ID=?, CONTRACT_TYPE=?, CONTRACT_START=?, CONTRACT_END=?, CONTRACT_AMOUNT=? WHERE ID=? ");
        if ($stmt)
        {
          $stmt->bind_param("sisisssdi", $token, $companyID, $userEMAIL, $modelID, $contractType, $contractStart, $contractEnd, $leasingAmount, $ID);
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
