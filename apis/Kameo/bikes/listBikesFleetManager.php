<?php

$company=execSQL("SELECT COMPANY FROM customer_referential WHERE TOKEN = ?", array('s', $token), false)[0]['COMPANY'];

$sql = "SELECT customer_bikes.ID as id, customer_bikes.HEU_MAJ, FRAME_NUMBER as frameNumber, customer_bikes.MODEL as model, COMPANY as company, AUTOMATIC_BILLING as automatic_billing, BILLING_TYPE as billingType, CONTRACT_TYPE as contractType, CONTRACT_START as contractStart, SELLING_DATE as sellingDate, LEASING_PRICE as leasingPrice, SOLD_PRICE as soldPrice, CONTRACT_END as contractEnd, STATUS as status, INSURANCE as insurance, BIKE_PRICE as bikePrice, GPS_ID as GPS_ID, ESTIMATED_DELIVERY_DATE as estimatedDeliveryDate, DELIVERY_DATE as deliveryDate, BIKE_BUYING_DATE as bikeBuyingDate, ORDER_NUMBER as orderNumber, SIZE as size, COLOR as color, BRAND as brand, bike_catalog.MODEL as modelBike, FRAME_TYPE as frameType, BUYING_PRICE as buyingPrice, PRICE_HTVA as priceHTVA, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission, (CASE (SELECT COUNT(1) FROM customer_bike_access WHERE customer_bike_access.TYPE='personnel' AND customer_bike_access.STAANN != 'D' AND customer_bike_access.BIKE_ID=customer_bikes.ID) WHEN 0 THEN 'partage' ELSE 'personnel' END) AS biketype FROM customer_bikes, bike_catalog WHERE customer_bikes.STAANN != 'D' AND TYPE=bike_catalog.ID AND customer_bikes.COMPANY=? and customer_bikes.CONTRACT_TYPE NOT IN ('order', 'stock', 'waiting_delivery') ORDER BY customer_bikes.FRAME_NUMBER";
if(isset($_GET['company']) && $company='KAMEO'){
  $response['bike']=execSQL($sql, array('s', $_GET['company']), false);
}else{
  $response['bike']=execSQL($sql, array('s', $company), false);
}
$response['response']="success";
echo json_encode($response);
die;

?>
