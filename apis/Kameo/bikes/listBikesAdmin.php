<?php
$stockAndCommand = isset($_GET['stockAndCommand']) ? $_GET['stockAndCommand'] : FALSE;
$customersCompaniesToIncludeInLoan = isset($_GET['customersCompaniesToIncludeInLoan']) ? $_GET['customersCompaniesToIncludeInLoan'] : NULL;

if ($customersCompaniesToIncludeInLoan == "Y") {
    $sql = "SELECT * FROM customer_bikes WHERE COMPANY != 'KAMEO' AND ((CONTRACT_TYPE = 'leasing' AND LEASING_PRICE != '0') OR CONTRACT_TYPE = 'location' OR CONTRACT_TYPE = 'order') AND STAANN != 'D' AND NOT EXISTS (SELECT 1 FROM loan_belfius WHERE ID_BIKE=customer_bikes.ID)";
    if ($conn->query($sql) === FALSE) {
        $response = array('response' => 'error', 'message' => $conn->error);
        echo json_encode($response);
        die;
    }
    $response['bike']=execSQL("$sql", array('s', $company), false);
    $response['response']="success";
    echo json_encode($response);
    die;
}else{
    $sql = "SELECT customer_bikes.ID as id, customer_bikes.HEU_MAJ, customer_bikes.FRAME_NUMBER as frameNumber, customer_bikes.MODEL as model, customer_bikes.COMPANY as company, AUTOMATIC_BILLING as automatic_billing, BILLING_TYPE as billingType, CONTRACT_TYPE as contractType, CONTRACT_START as contractStart, SELLING_DATE as sellingDate, LEASING_PRICE as leasingPrice, SOLD_PRICE as soldPrice, CONTRACT_END as contractEnd, STATUS as status, INSURANCE as insurance, BIKE_PRICE as bikePrice, GPS_ID as GPS_ID, ESTIMATED_DELIVERY_DATE as estimatedDeliveryDate, DELIVERY_DATE as deliveryDate, BIKE_BUYING_DATE as bikeBuyingDate, ORDER_NUMBER as orderNumber, SIZE as size, COLOR as color, BRAND as brand, bike_catalog.MODEL as modelBike, FRAME_TYPE as frameType, BUYING_PRICE as buyingPrice, PRICE_HTVA as priceHTVA, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission, (select SUM(AMOUNT_HTVA)/bike_catalog.BUYING_PRICE*100 from factures_details WHERE ITEM_ID=customer_bikes.ID AND ITEM_TYPE='bike') as rentability, 'personnel' as type, customer_referential.NOM as ownerName, customer_referential.PRENOM as ownerFirstName  FROM customer_bikes, customer_referential, bike_catalog,customer_bike_access WHERE customer_bikes.STAANN != 'D' AND customer_bikes.TYPE=bike_catalog.ID AND customer_bike_access.BIKE_ID=customer_bikes.ID and customer_bike_access.TYPE='personnel' and customer_bike_access.EMAIL=customer_referential.EMAIL AND customer_bike_access.STAANN != 'D' ";
    if ($stockAndCommand){
        $sql = $sql . " AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE='order')";
    }
    $sql = $sql . "UNION ALL
    SELECT customer_bikes.ID as id, customer_bikes.HEU_MAJ, customer_bikes.FRAME_NUMBER as frameNumber, customer_bikes.MODEL as model, customer_bikes.COMPANY as company, AUTOMATIC_BILLING as automatic_billing, BILLING_TYPE as billingType, CONTRACT_TYPE as contractType, CONTRACT_START as contractStart, SELLING_DATE as sellingDate, LEASING_PRICE as leasingPrice, SOLD_PRICE as soldPrice, CONTRACT_END as contractEnd, STATUS as status, INSURANCE as insurance, BIKE_PRICE as bikePrice, GPS_ID as GPS_ID, ESTIMATED_DELIVERY_DATE as estimatedDeliveryDate, DELIVERY_DATE as deliveryDate, BIKE_BUYING_DATE as bikeBuyingDate, ORDER_NUMBER as orderNumber, SIZE as size, COLOR as color, BRAND as brand, bike_catalog.MODEL as modelBike, FRAME_TYPE as frameType, BUYING_PRICE as buyingPrice, PRICE_HTVA as priceHTVA, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission, (select SUM(AMOUNT_HTVA)/bike_catalog.BUYING_PRICE*100 from factures_details WHERE ITEM_ID=customer_bikes.ID AND ITEM_TYPE='bike') as rentability, 'partage' as type, '' as ownerName, ''as ownerFirtsName  FROM customer_bikes, bike_catalog WHERE customer_bikes.STAANN != 'D' AND customer_bikes.TYPE=bike_catalog.ID AND NOT EXISTS (SELECT 1 FROM customer_bike_access WHERE customer_bike_access.BIKE_ID=customer_bikes.ID and customer_bike_access.TYPE='personnel' and customer_bike_access.STAANN != 'D') ";
    if($stockAndCommand){
        $sql = $sql . " AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE='order')";
    }

    $response['bike']=execSQL($sql, array(), false);
    $response['response']="success";
    echo json_encode($response);
    die;
}

?>
