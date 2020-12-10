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


    $sql = "SELECT * FROM customer_bikes aa where aa.COMPANY='$company' AND aa.STAANN != 'D'";
    if ($conn->query($sql) === FALSE) {
        $response = array('response' => 'error', 'message' => $conn->error);
        echo json_encode($response);
        die;
    }
} else {
    if ($customersCompaniesToIncludeInLoan == "Y") {
        include 'connexion.php';
        $sql = "SELECT * FROM customer_bikes WHERE COMPANY != 'KAMEO' AND ((CONTRACT_TYPE = 'leasing' AND LEASING_PRICE != '0') OR CONTRACT_TYPE = 'location' OR CONTRACT_TYPE = 'order') AND STAANN != 'D' AND NOT EXISTS (SELECT 1 FROM loan_belfius WHERE ID_BIKE=customer_bikes.ID)";

        if ($stockAndCommand) {
            $sql = $sql . " AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE='order')";
        }

        if ($conn->query($sql) === FALSE) {
            $response = array('response' => 'error', 'message' => $conn->error);
            echo json_encode($response);
            die;
        }
    }else{
        include 'connexion.php';
        $sql = "SELECT * FROM customer_bikes WHERE STAANN != 'D'";

        if ($stockAndCommand) {
            $sql = $sql . " AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE='order')";
        }

        if ($conn->query($sql) === FALSE) {
            $response = array('response' => 'error', 'message' => $conn->error);
            echo json_encode($response);
            die;
        }
    }
}
include 'connexion.php';

$result = mysqli_query($conn, $sql);
$length = $result->num_rows;
$response['bikeNumber'] = $length;
$response['response'] = "success";
$response['sql'] = $sql;
$conn->close();

$i = 0;
while ($row = mysqli_fetch_array($result)) {
    $idBike = $row['ID'];
    $response['bike'][$i]['id'] = $row['ID'];
    $response['bike'][$i]['HEU_MAJ'] = $row['HEU_MAJ'];
    $response['bike'][$i]['frameNumber'] = $row['FRAME_NUMBER'];
    $response['bike'][$i]['model'] = $row['MODEL'];
    $response['bike'][$i]['company'] = $row['COMPANY'];
    $response['bike'][$i]['automatic_billing'] = $row['AUTOMATIC_BILLING'];
    $response['bike'][$i]['billingType'] = $row['BILLING_TYPE'];
    $response['bike'][$i]['contractType'] = $row['CONTRACT_TYPE'];
    $response['bike'][$i]['contractStart'] = $row['CONTRACT_START'];
    $response['bike'][$i]['leasingPrice'] = $row['LEASING_PRICE'];
    $response['bike'][$i]['soldPrice'] = $row['SOLD_PRICE'];
    $response['bike'][$i]['contractEnd'] = $row['CONTRACT_END'];
    $response['bike'][$i]['status'] = $row['STATUS'];
    $response['bike'][$i]['insurance'] = $row['INSURANCE'];
    $response['bike'][$i]['bikePrice'] = $row['BIKE_PRICE'];
    $response['bike'][$i]['GPS_ID'] = $row['GPS_ID'];
    $response['bike'][$i]['deliveryDate'] = $row['DELIVERY_DATE'];
    $response['bike'][$i]['bikeBuyingDate'] = $row['BIKE_BUYING_DATE'];
    $response['bike'][$i]['orderNumber'] = $row['ORDER_NUMBER'];
    $response['bike'][$i]['size'] = $row['SIZE'];
    $response['bike'][$i]['color'] = $row['COLOR'];


    if ($row['TYPE']) {
        $type = $row['TYPE'];
        include 'connexion.php';
        $sql2 = "select * from bike_catalog WHERE ID='$type'";
        if ($conn->query($sql2) === FALSE) {
            $response = array('response' => 'error', 'message' => $conn->error);
            echo json_encode($response);
            die;
        }

        $result2 = mysqli_query($conn, $sql2);
        $resultat2 = mysqli_fetch_assoc($result2);
        $conn->close();
        $response['bike'][$i]['brand'] = $resultat2['BRAND'];
        $response['bike'][$i]['modelBike'] = $resultat2['MODEL'];
        $response['bike'][$i]['frameType'] = $resultat2['FRAME_TYPE'];
        $response['bike'][$i]['buyingPrice'] = $resultat2['BUYING_PRICE'];
        $response['bike'][$i]['priceHTVA'] = $resultat2['PRICE_HTVA'];
        $response['bike'][$i]['motor'] = $resultat2['MOTOR'];
        $response['bike'][$i]['battery'] = $resultat2['BATTERY'];
        $response['bike'][$i]['transmission'] = $resultat2['TRANSMISSION'];
    } else {
        $response['bike'][$i]['brand'] = null;
        $response['bike'][$i]['modelBike'] = null;
    }

    if ($row['BIKE_PRICE']) {
        include 'connexion.php';
        $sql3 = "select SUM(AMOUNT_HTVA) AS 'SOMME' from factures_details WHERE BIKE_ID='$idBike'";
        if ($conn->query($sql3) === FALSE) {
            $response = array('response' => 'error', 'message' => $conn->error);
            echo json_encode($response);
            die;
        }

        $result3 = mysqli_query($conn, $sql3);
        $resultat3 = mysqli_fetch_assoc($result3);
        $conn->close();

        $rentability = round($resultat3['SOMME'] / $row['BIKE_PRICE'] * 100);
        $response['bike'][$i]['rentability'] = $rentability;
        $response['bike'][$i]['sql'] = $sql3;
    } else {
        $response['bike'][$i]['rentability'] = "N/A";
    }

    include 'connexion.php';
    $sql4 = "SELECT TYPE AS 'biketype' from customer_bike_access WHERE BIKE_ID='$idBike'";
    if ($conn->query($sql4) === FALSE) {
        $response = array('response' => 'error', 'message' => $conn->error);
        echo json_encode($response);
        die;
    }

    $result4 = mysqli_query($conn, $sql4);
    $resultat4 = mysqli_fetch_assoc($result4);
    if ($result4->num_rows == '0') {
        $response['bike'][$i]['biketype'] = 'partage';
    }else{
        $response['bike'][$i]['biketype'] = $resultat4['biketype'];
    }

    $conn->close();



    $i++;
}

include 'connexion.php';
$dateStart = new DateTime();
$dateStart->setDate(date("Y"), 1, 1);
$dateStartString = $dateStart->format('Y-m-d');
$sql2 = "SELECT count(1) FROM customer_bikes cc, reservations dd where COMPANY=(select COMPANY from customer_referential where EMAIL='$email') and cc.ID=dd.BIKE_ID and cc.STAANN != 'D' and dd.STAANN!='D' and dd.DATE_START_2>'$dateStartString'";
if ($conn->query($sql2) === FALSE) {
    $response = array('response' => 'error', 'message' => $conn->error);
    echo json_encode($response);
    die;
}
$result2 = mysqli_query($conn, $sql2);
$resultat2 = mysqli_fetch_assoc($result2);

$response['numberOfBookings'] = $resultat2['count(1)'];

log_output($response);
echo json_encode($response);
die;
