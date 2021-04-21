<?php
$companyName = addslashes($_POST["companyName"]);
$companyStreet = addslashes($_POST["companyStreet"]);
$ZIPCode = $_POST["companyZIPCode"];
$companyTown = addslashes($_POST["companyTown"]);
$companyVAT = $_POST["companyVAT"];
$type = isset($_POST["type"]) ? $_POST["type"] : $_POST["typeHidden"];
$audience = isset($_POST["audience"]) ? $_POST["audience"] : $_POST["typeHidden"];
$internalReference = $_POST["internalReference"];
$billing=isset($_POST['billing']) ? "Y" : "N";
$booking=isset($_POST['booking']) ? "Y" : "N";
$locking=isset($_POST['locking']) ? "Y" : "N";
$assistance=isset($_POST['assistance']) ? "Y" : "N";
$ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;

execSQL("update companies set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY_NAME=?, STREET=?, ZIP_CODE=?, TOWN=?, VAT_NUMBER=?, TYPE=?, AUDIENCE=? where ID=?", array('ssssssssi', $token, $companyName, $companyStreet, $ZIPCode, $companyTown, $companyVAT, $type, $audience, $ID), true);
execSQL("UPDATE conditions SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, ASSISTANCE=?, LOCKING=? WHERE COMPANY=? AND NAME='generic'", array('ssss', $token, $assistance, $locking, $internalReference), true);
successMessage("SM0003");

?>
