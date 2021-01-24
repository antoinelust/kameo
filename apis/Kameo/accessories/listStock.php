<?php
$stockAccessories['accessories'] = execSQL("SELECT aa.*, bb.BRAND, bb.MODEL, cc.CATEGORY, dd.COMPANY_NAME FROM accessories_stock aa, accessories_catalog bb, accessories_categories cc, companies dd WHERE aa.CATALOG_ID=bb.ID AND bb.ACCESSORIES_CATEGORIES=cc.ID AND aa.COMPANY_ID=dd.ID", array(), false);
$stockAccessories['response']="success";
echo json_encode($stockAccessories);
die;
?>
