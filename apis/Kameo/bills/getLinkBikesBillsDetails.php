<?php
  $id=$_GET['billingID'];
	$response['billingDetails']=execSQL("SELECT * from factures WHERE ID=?", array('i', $id), false)[0];
  $response['catalogDetails']=execSQL("SELECT BRAND, MODEL, bike_catalog.ID as catalogID, bills_catalog_bikes_link.BUYING_PRICE, PRICE_HTVA, bills_catalog_bikes_link.SIZE, bills_catalog_bikes_link.BIKE_ID from bills_catalog_bikes_link, bike_catalog WHERE bills_catalog_bikes_link.CATALOG_ID=bike_catalog.ID AND FACTURE_ID=?", array('i', $id), false);
  $response['modelDetails']=execSQL("SELECT * from factures_details WHERE FACTURE_ID=?", array('i', $id), false);
  $response['notLinkedBikes']=execSQL("SELECT bills_catalog_bikes_link.ID, bills_catalog_bikes_link.CATALOG_ID, BRAND, MODEL, FRAME_TYPE, SEASON FROM bills_catalog_bikes_link, bike_catalog WHERE FACTURE_ID=? AND bills_catalog_bikes_link.CATALOG_ID=bike_catalog.ID AND bills_catalog_bikes_link.BIKE_ID is NULL", array('i', $id), false);
  echo json_encode($response);
  die;
?>
