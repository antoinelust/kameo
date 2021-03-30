<?php
session_cache_limiter('nocache');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: application/json');
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

log_inputs();

try{
  if(isset($_GET['action'])){
    $action=isset($_GET['action']) ? $_GET['action'] : NULL;
    if($action=="list"){
      $response=array();
      $response['response']="success";
      $response['accessories'] = execSQL("SELECT accessories_catalog.ID as catalogID, accessories_catalog.BRAND, accessories_catalog.DESCRIPTION, accessories_catalog.MODEL, accessories_catalog.DISPLAY, accessories_categories.CATEGORY, accessories_catalog.PRICE_HTVA FROM accessories_catalog, accessories_categories WHERE  accessories_catalog.ACCESSORIES_CATEGORIES=accessories_categories.ID ORDER BY accessories_categories.CATEGORY", array(), false);
      echo json_encode($response);
      die;
    }else if($action=="retrieve"){
      $response=array();
      $catalogID=isset($_GET['catalogID']) ? $_GET['catalogID'] : NULL;
      $response['response']="success";
      $response['accessory'] = execSQL("SELECT accessories_catalog.ID, accessories_catalog.BRAND, accessories_catalog.DESCRIPTION, accessories_catalog.MODEL, accessories_catalog.DISPLAY, accessories_categories.CATEGORY, accessories_catalog.PRICE_HTVA FROM accessories_catalog, accessories_categories WHERE  accessories_catalog.ACCESSORIES_CATEGORIES=accessories_categories.ID AND accessories_catalog.ID=?", array("s", $catalogID), false)[0];
      echo json_encode($response);
      die;
    }
  }else{
    errorMessage("ES0012");
  }
} catch (Exception $e) {
  $response['response']="error";
  $response['message']=$e->getMessage();
  echo json_encode($response);
  die;
}


?>
