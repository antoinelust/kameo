<?php

$bikeID=isset($_GET['bikeID']) ? $_GET['bikeID']: NULL;
$response=array();


$response['bills'] = array();
$bills=array();

$bills[]=execSQL("select factures_details.FACTURE_ID, factures_details.AMOUNT_HTVA, 'OUT' as direction, factures.FACTURE_PAID, factures.FACTURE_SENT, factures.FILE_NAME, factures.DATE from factures_details, factures where ITEM_ID = ? AND ITEM_TYPE='bike' and factures_details.FACTURE_ID=factures.ID", array ('i', $bikeID), false);
$bills[]=execSQL("select FACTURE_ID, 'IN' as direction, bills_catalog_bikes_link.BUYING_PRICE as AMOUNT_HTVA, factures.FACTURE_PAID, factures.FACTURE_SENT, factures.FILE_NAME, factures.DATE from bills_catalog_bikes_link, factures WHERE factures.ID=bills_catalog_bikes_link.FACTURE_ID AND BIKE_ID=?", array('i', $bikeID), false);

foreach($bills as $arr) {
    if(is_array($arr)) {
        $response['bills'] = array_merge($response['bills'], $arr);
    }
}

echo json_encode($response);
die;
?>
