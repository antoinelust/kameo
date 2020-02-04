<?php

  //récupération des données du $_POST (pré boucle)
  $companyId = isset($_POST["companyIdTemplate"]) ? $_POST["companyIdTemplate"] : NULL;
  $buyOrLeasing = isset($_POST["buyOrLeasing"]) ? $_POST["buyOrLeasing"] : NULL;
  $leasingDuration = isset($_POST["leasingDuration"]) ? $_POST["leasingDuration"] : NULL;
  $numberMaintenance = isset($_POST["numberMaintenance"]) ? $_POST["numberMaintenance"] : NULL;
  $assurance = isset($_POST["assurance"]) ? true : false;
  $bikesNumber = isset($_POST["bikesNumber"]) ? $_POST["bikesNumber"] : NULL;
  $boxesNumber = isset($_POST["boxesNumber"]) ? $_POST["boxesNumber"] : NULL;
  $accessoriesNumber = isset($_POST["accessoriesNumber"]) ? $_POST["accessoriesNumber"] : NULL;
  $othersNumber = isset($_POST["othersNumber"]) ? $_POST["othersNumber"] : NULL;

  //création des tableaux destinés a recevoir les id des différents item
  $bikes = $bikesNumber > 0 ? getIds('bikeBrandModel',$bikesNumber) : NULL;
  $boxes = $boxesNumber > 0 ? getIds('boxModel',$boxesNumber) : NULL;
  $accessories = $accessoriesNumber > 0 ? getIds('accessoryAccessory',$accessoriesNumber) : NULL;
  $others = $othersNumber > 0 ? getOthers($othersNumber) : NULL;

  //creation de la response
  $response['companyId'] = $companyId;
  $response['buyOrLeasing'] = $buyOrLeasing;
  $response['leasingDuration'] = $leasingDuration;
  $response['numberMaintenance'] = $numberMaintenance;
  $response['assurance'] = $assurance;
  $response['bikes'] = $bikes;
  $response['boxes'] = $boxes;
  $response['accessories'] = $accessories;
  $response['others'] = $others;

  echo json_encode($response);




  function getIds($key, $counter){
    $arr = array();
    $composedKey = '';
    for ($i=1; $i <= $counter ; $i++) {
      $composedKey = $key . $i;
      array_push($arr, $_POST[$composedKey]);
    }
    return $arr;
  }

  function getOthers($counter){
    $arr = array();
    for ($i=1; $i <= $counter ; $i++) {
      $composedDescription = 'othersDescription'.$i;
      $composedCost = 'othersCost'.$i;
      $arr[$i-1]['othersDescription'] = $_POST[$composedDescription];
      $arr[$i-1]['othersCost'] = $_POST[$composedCost];
    }
    return $arr;
  }


 ?>
