<?php

$email=$_POST['mail'];
$name=$_POST['name'];
$firstName=$_POST['firstname'];
$phone=$_POST['phone'];
$fleetManager=isset($_POST['fleetManager']) ? "Y" : "N";


$response=array();

$resultat=execSQL("select * from customer_referential where EMAIL = ?", array('s', $email), false)[0];
$company=$resultat['COMPANY'];

if($fleetManager == "Y" && strpos($resultat['ACCESS_RIGHTS'], 'fleetManager') === false){
  $newAccessRights = $resultat['ACCESS_RIGHTS'].",fleetManager";
}else if($fleetManager == "N" && strpos($resultat['ACCESS_RIGHTS'], 'fleetManager') !== false){
  $newAccessRights = str_replace(",fleetManager", "", $resultat['ACCESS_RIGHTS']);
}else{
  $newAccessRights=$resultat['ACCESS_RIGHTS'];
}

execSQL("UPDATE customer_referential set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, PRENOM=?, NOM=?, PHONE=?, ACCESS_RIGHTS=? where EMAIL = ?", array('ssssss', $token, $firstName, $name, $phone, $newAccessRights, $email), true);


if(!isset(($_POST['buildingAccess']))){
  execSQL("update customer_building_access set STAANN='D', USR_MAJ=?, TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = ?", array('ss', $token, $email), true);
}else if(isset(($_POST['buildingAccess']))){
  foreach($_POST['buildingAccess'] as $valueInArray){
    execSQL("REPLACE INTO customer_building_access (USR_MAJ, EMAIL, BUILDING_CODE, STAANN) VALUES (?, ?, ?, '')", array('sss', $token, $email, $valueInArray), true);
  }
}

if(!isset(($_POST['bikeAccess']))){
  execSQL("update customer_bike_access set STAANN='D', USR_MAJ=?, TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = ? and TYPE = 'partage'", array('ss', $token, $email), true);
}else if(isset(($_POST['bikeAccess']))){
  execSQL("REPLACE INTO customer_bike_access (USR_MAJ, EMAIL, BIKE_ID, TYPE, STAANN) VALUES (?, ?, ?, 'partage', '')", array('sss', $token, $email, $valueInArray), true);
}

successMessage("SM0003");
?>
