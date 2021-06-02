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
      if($_GET['category']=="antivol"){$categories="'cadenas'";}
      else if($_GET['category']=="casques"){$categories="'casque', 'casque_enfant'";}
      else if($_GET['category']=="textiles"){$categories="'textiles', 'gants', 'bandana'";}
      else if($_GET['category']=="casques"){$categories="'casques', 'casques_enfants'";}
      else if($_GET['category']=="sacoche"){$categories="'sacoche', 'panier'";}
      else if($_GET['category']=="phare"){$categories="'phare_avant', 'phare_arriere', 'catadiopte'";}
      else if($_GET['category']=="siege_enfant"){$categories="'siege_enfant'";}
      else if($_GET['category']=="gourde"){$categories="'gourde'";}
      else if($_GET['category']=="garde_boue"){$categories="'garde_boue'";}
      else if($_GET['category']=="outils"){$categories="'multitools'";}
      else if($_GET['category']=="GPS"){$categories="'GPS', 'traceur'";}
      else if($_GET['category']=="pompe_a_velo"){$categories="'pompe_a_velo'";}
      else if($_GET['category']=="Produitsentretien"){$categories="'produit_entretien', 'protection_anti_rouille', 'anti_crevaison', 'lubrifiant', 'nettoyant_et_degraissant'";}
      else if($_GET['category']=="selle"){$categories="'selle'";}

      $response['accessories'] = execSQL("SELECT accessories_catalog.ID as catalogID, accessories_catalog.BRAND, accessories_catalog.DESCRIPTION, accessories_catalog.MODEL, accessories_catalog.DISPLAY, accessories_categories.CATEGORY, accessories_catalog.PRICE_HTVA, accessories_catalog.PRICE_HTVA*1.5/36 as leasingPrice FROM accessories_catalog, accessories_categories WHERE  accessories_catalog.ACCESSORIES_CATEGORIES=accessories_categories.ID AND accessories_categories.CATEGORY IN ($categories) ORDER BY accessories_categories.CATEGORY", array(), false);
      $response['sql']="SELECT accessories_catalog.ID as catalogID, accessories_catalog.BRAND, accessories_catalog.DESCRIPTION, accessories_catalog.MODEL, accessories_catalog.DISPLAY, accessories_categories.CATEGORY, accessories_catalog.PRICE_HTVA, accessories_catalog.PRICE_HTVA*1.5/36 as leasingPrice FROM accessories_catalog, accessories_categories WHERE  accessories_catalog.ACCESSORIES_CATEGORIES=accessories_categories.ID AND accessories_categories.CATEGORY IN ($categories) ORDER BY accessories_categories.CATEGORY";
      echo json_encode($response);
      die;
    }else if($action=="retrieve"){
      $response=array();
      $catalogID=isset($_GET['catalogID']) ? $_GET['catalogID'] : NULL;
      $response['response']="success";
      $response['accessory'] = execSQL("SELECT accessories_catalog.ID, accessories_catalog.BRAND, accessories_catalog.DESCRIPTION, accessories_catalog.MODEL, accessories_catalog.DISPLAY, accessories_categories.CATEGORY, accessories_catalog.PRICE_HTVA, accessories_catalog.PRICE_HTVA*1.5/36 as leasingPrice FROM accessories_catalog, accessories_categories WHERE  accessories_catalog.ACCESSORIES_CATEGORIES=accessories_categories.ID AND accessories_catalog.ID=?", array("s", $catalogID), false)[0];
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
