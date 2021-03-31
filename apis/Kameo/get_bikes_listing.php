<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
require_once 'globalfunctions.php';
require_once 'authentication.php';
$token = getBearerToken();

log_inputs($token);

$email = isset($_POST['email']) ? $_POST['email'] : NULL;
$company = isset($_POST['company']) ? $_POST['company'] : NULL;
$admin = isset($_POST['admin']) ? $_POST['admin'] : NULL;
$stockAndCommand = isset($_POST['stockAndCommand']) ? $_POST['stockAndCommand'] : FALSE;
$customersCompaniesToIncludeInLoan = isset($_POST['customersCompaniesToIncludeInLoan']) ? $_POST['customersCompaniesToIncludeInLoan'] : NULL;

$response = array();

if ($admin != "Y") {
    include 'connexion.php';
    $sql = "SELECT COMPANY  FROM customer_referential WHERE TOKEN = '$token'";
    if ($conn->query($sql) === FALSE) {
        $response = array('response' => 'error', 'message' => $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows == '0') {
        errorMessage("ES0039");
    }
    $resultat = mysqli_fetch_assoc($result);
    $company = $resultat['COMPANY'];

    if($company == "KAMEO"){
      $company = isset($_POST['company']) ? $_POST['company'] : $company;
    }
    $sql = "SELECT customer_bikes.ID as id, customer_bikes.HEU_MAJ, FRAME_NUMBER as frameNumber, customer_bikes.MODEL as model, COMPANY as company, AUTOMATIC_BILLING as automatic_billing, BILLING_TYPE as billingType, CONTRACT_TYPE as contractType, CONTRACT_START as contractStart, SELLING_DATE as sellingDate, LEASING_PRICE as leasingPrice, SOLD_PRICE as soldPrice, CONTRACT_END as contractEnd, STATUS as status, INSURANCE as insurance, BIKE_PRICE as bikePrice, GPS_ID as GPS_ID, ESTIMATED_DELIVERY_DATE as estimatedDeliveryDate, DELIVERY_DATE as deliveryDate, BIKE_BUYING_DATE as bikeBuyingDate, ORDER_NUMBER as orderNumber, SIZE as size, COLOR as color, BRAND as brand, bike_catalog.MODEL as modelBike, FRAME_TYPE as frameType, BUYING_PRICE as buyingPrice, PRICE_HTVA as priceHTVA, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission FROM customer_bikes, bike_catalog WHERE customer_bikes.STAANN != 'D' AND TYPE=bike_catalog.ID AND customer_bikes.COMPANY=? and customer_bikes.CONTRACT_TYPE NOT IN ('order', 'stock', 'waiting_delivery') ORDER BY customer_bikes.MODEL";
    $response['bike']=execSQL("$sql", array('s', $company), false);
    $response['response']="success";

} else {
    if ($customersCompaniesToIncludeInLoan == "Y") {
        include 'connexion.php';
        $sql = "SELECT * FROM customer_bikes WHERE COMPANY != 'KAMEO' AND ((CONTRACT_TYPE = 'leasing' AND LEASING_PRICE != '0') OR CONTRACT_TYPE = 'location' OR CONTRACT_TYPE = 'order') AND STAANN != 'D' AND NOT EXISTS (SELECT 1 FROM loan_belfius WHERE ID_BIKE=customer_bikes.ID)";

        if ($stockAndCommand) {
            $sql = $sql . " AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE='order' OR CONTRACT_TYPE='pending_delivery')";
        }

        if ($conn->query($sql) === FALSE) {
            $response = array('response' => 'error', 'message' => $conn->error);
            echo json_encode($response);
            die;
        }
    }else{
        include 'connexion.php';
        $sql = "SELECT customer_bikes.ID as id, customer_bikes.HEU_MAJ, FRAME_NUMBER as frameNumber, customer_bikes.MODEL as model, COMPANY as company, AUTOMATIC_BILLING as automatic_billing, BILLING_TYPE as billingType, CONTRACT_TYPE as contractType, CONTRACT_START as contractStart, SELLING_DATE as sellingDate, LEASING_PRICE as leasingPrice, SOLD_PRICE as soldPrice, CONTRACT_END as contractEnd, STATUS as status, INSURANCE as insurance, BIKE_PRICE as bikePrice, GPS_ID as GPS_ID, ESTIMATED_DELIVERY_DATE as estimatedDeliveryDate, DELIVERY_DATE as deliveryDate, BIKE_BUYING_DATE as bikeBuyingDate, ORDER_NUMBER as orderNumber, SIZE as size, COLOR as color, BRAND as brand, bike_catalog.MODEL as modelBike, FRAME_TYPE as frameType, BUYING_PRICE as buyingPrice, PRICE_HTVA as priceHTVA, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission, (select SUM(AMOUNT_HTVA)/bike_catalog.BUYING_PRICE*100 from factures_details WHERE ITEM_ID=customer_bikes.ID AND ITEM_TYPE='bike') as rentability FROM customer_bikes, bike_catalog WHERE customer_bikes.STAANN != 'D' AND TYPE=bike_catalog.ID";
        if ($stockAndCommand) {
            $sql = $sql . " AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE='order')";
        }
        $response['bike']=execSQL("$sql", array(), false);
        $response['response']="success";
    }
}

log_output($response);
echo json_encode($response);
die;
