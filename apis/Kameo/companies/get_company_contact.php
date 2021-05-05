<?php
$ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;

$response=array();
$response=execSQL("SELECT ID as contactId, EMAIL as emailContact, PRENOM as firstNameContact, NOM as lastNameContact, PHONE as phone, BIKES_STATS as bikesStats, FUNCTION as fonction, TYPE FROM companies_contact dd where ID_COMPANY=?", array('i', $ID), false);
if(is_null($response)){
  $response=array();
}
echo json_encode($response);
die;
