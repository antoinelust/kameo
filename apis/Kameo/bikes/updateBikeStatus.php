<?php
$user=$_POST['user'];
$bikeID=$_POST['bikeID'];
$model=$_POST['bikeModel'];
$status=$_POST['bikeStatus'];
$email=$_POST['email'];

$response=array();


if($bikeID != NULL && $status != NULL)
{

    $resultat=execSQL("select * from customer_bikes WHERE ID = ?", array('i', $bikeID), false)[0];
    $company=$resultat['COMPANY'];

    if($status!=$resultat['STATUS']){
        execSQL("update customer_bikes set STATUS = ?, USR_MAJ = ?, HEU_MAJ = CURRENT_TIMESTAMP WHERE ID = ?", array('ssi', $status, $token, $bikeID), true);
    }

    if($model!=$resultat['MODEL']){
        execSQL("update customer_bikes set MODEL = ?, USR_MAJ = ?, HEU_MAJ = CURRENT_TIMESTAMP WHERE ID = ?", array('ssi', $model, $token, $bikeID), true);
    }


    if(!isset(($_POST['buildingAccess'])) && $_POST['bikeType']=='partage'){
        execSQL("update bike_building_access set STAANN='D', USR_MAJ=?, TIMESTAMP=CURRENT_TIMESTAMP where BIKE_ID = ?", array('si', $token, $bikeID), true);
    }else if(isset(($_POST['buildingAccess'])) && $_POST['bikeType']=='partage'){
      foreach($_POST as $name => $value){
        if($name=="buildingAccess"){
          foreach($_POST['buildingAccess'] as $valueInArray){
            $result= execSQL("SELECT * FROM bike_building_access WHERE BIKE_ID=? and BUILDING_CODE=?", array('is', $bikeID, $valueInArray), false);
            if(is_null($result)){
              execSQL("INSERT INTO  bike_building_access (USR_MAJ, BIKE_ID, BUILDING_CODE, STAANN) VALUES (?, ?, ?, '')", array('sis', $user, $bikeID, $valueInArray), true);
            }else{
              execSQL("UPDATE bike_building_access set STAANN='' WHERE BIKE_ID=? and BUILDING_CODE=?", array('is', $bikeID, $valueInArray), true);
            }
          }
        }
      }
    }
    successMessage("SM0003");
}
else
{
    errorMessage("ES0012");
}
?>
