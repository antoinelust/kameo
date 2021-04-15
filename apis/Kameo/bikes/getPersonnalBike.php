<?php
$bikeID=execSQL("SELECT BIKE_ID FROM customer_referential, customer_bike_access WHERE TOKEN=? AND customer_bike_access.TYPE='personnel' AND customer_referential.EMAIL=customer_bike_access.EMAIL AND customer_referential.STAANN != 'D' AND customer_bike_access.STAANN != 'D'", array('s', $token), false)[0]['BIKE_ID'];
$response=execSQL("SELECT * FROM customer_bikes  WHERE ID = ?", array('i', $bikeID), false)[0];
$catalogID=$response['TYPE'];
$response=array_merge($response, execSQL("SELECT BRAND, MODEL as modelCatalog, TRANSMISSION, PRICE_HTVA FROM bike_catalog WHERE ID=?", array('i', $catalogID), false)[0]);
echo json_encode($response);
die;
?>
