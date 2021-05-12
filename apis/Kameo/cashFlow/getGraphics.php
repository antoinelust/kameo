<?php

$response['response']="success";
$resultat=execSQL("select MAX(CONTRACT_END) as 'CONTRACT_END' from customer_bikes WHERE AUTOMATIC_BILLING='Y'", array(), false)[0];
$date_end=$resultat['CONTRACT_END'];
$date_start = new DateTime("NOW");
$arrayContracts=array();
$arrayOffers=array();
$arrayDates=array();
$arrayCosts=array();
$arrayFreeCashFlow=array();
$arrayIN=array();
$i=0;
while(($date_start->format('Y-m-d'))<=$date_end){

    $date_start_string=$date_start->format('Y-m-d');
    $contractAmountTemp=execSQL("SELECT SUM(CASE WHEN BILLING_TYPE = 'annual' THEN LEASING_PRICE/12 ELSE LEASING_PRICE END) as 'PRICE' FROM customer_bikes aa WHERE aa.STAANN != 'D' and aa.COMPANY != 'KAMEO' AND aa.CONTRACT_TYPE IN ('leasing', 'location') AND CONTRACT_START <= '$date_start_string' AND CONTRACT_END >= '$date_start_string'", array(), false)[0]['PRICE'];
    $contractAmountTemp+=execSQL("SELECT SUM(AMOUNT) AS 'PRICE' FROM boxes WHERE START <= '$date_start_string' AND END >= '$date_start_string' AND STAANN != 'D'", array(), false)[0]['PRICE'];
    array_push($arrayContracts, round($contractAmountTemp));

    $resultat=execSQL("SELECT SUM(AMOUNT) AS 'PRICE' FROM costs WHERE START <= '$date_start_string' AND END >= '$date_start_string'", array(), false)[0];
    array_push($arrayCosts, round(-$resultat['PRICE']));
    $costs=$resultat['PRICE'];
    $result=execSQL("SELECT AMOUNT, PROBABILITY FROM offers WHERE START != '' AND END != '' AND TYPE = 'leasing' AND STATUS='ongoing' AND START <= '$date_start_string' AND END >= '$date_start_string'", array(), false);
    $amount=0;
    foreach($result as $row){
        $amount=$amount+round(($row['AMOUNT']*$row['PROBABILITY']/100));
    }

    array_push($arrayOffers, $amount);
    array_push($arrayDates, $date_start->format('Y-m-d'));
    array_push($arrayIN, round($amount + $contractAmountTemp));
    array_push($arrayFreeCashFlow, round($amount + $contractAmountTemp - $costs ));
    $date_start->add(new DateInterval('P10D'));
}

$response['response']="success";
$response['arrayContracts']=$arrayContracts;
$response['arrayOffers']=$arrayOffers;
$response['arrayCosts']=$arrayCosts;
$response['arrayFreeCashFlow']=$arrayFreeCashFlow;
$response['totalIN']=$arrayIN;
$response['arrayDates']=$arrayDates;

$result=execSQL("SELECT substr(factures_details.DATE_START, 1 ,7) as 'month', round(SUM(CASE WHEN ITEM_TYPE='bike' AND factures_details.DATE_START != factures_details.DATE_END THEN AMOUNT_HTVA ELSE 0 END)) as 'bikeLeasing', round(SUM(CASE WHEN ITEM_TYPE='bike' AND (factures_details.DATE_START = factures_details.DATE_END OR factures_details.DATE_END IS NULL) THEN AMOUNT_HTVA ELSE 0 END)) as 'bikeSelling', round(SUM(CASE WHEN (ITEM_TYPE='accessory' OR ITEM_TYPE='accessoryCatalog') AND factures_details.DATE_START != factures_details.DATE_END THEN AMOUNT_HTVA ELSE 0 END)) as 'accessoryLeasing', round(SUM(CASE WHEN (ITEM_TYPE='accessory' OR ITEM_TYPE='accessoryCatalog') AND factures_details.DATE_START = factures_details.DATE_END THEN AMOUNT_HTVA ELSE 0 END)) as 'accessorySelling', round(SUM(CASE WHEN ITEM_TYPE='box' THEN AMOUNT_HTVA ELSE 0 END)) as 'boxesLeasing', round(SUM(CASE WHEN ITEM_TYPE='maintenance' THEN AMOUNT_HTVA ELSE 0 END)) as 'maintenance' FROM factures_details WHERE DATE_START > '2020-01-01' GROUP BY substr(factures_details.DATE_START, 1 ,7)", array(), false);
$response['arrayDatesCA']=array_column($result, 'month');
$response['bikeLeasing']=array_column($result, 'bikeLeasing');
$response['bikeSelling']=array_column($result, 'bikeSelling');
$response['accessoryLeasing']=array_column($result, 'accessoryLeasing');
$response['accessorySelling']=array_column($result, 'accessorySelling');
$response['boxesLeasing']=array_column($result, 'boxesLeasing');
$response['maintenance']=array_column($result, 'maintenance');

$response['totalCA']=execSQL("SELECT SUM(AMOUNT_HTVA) as totalCA FROM factures WHERE  DATE >= MAKEDATE(year(now()),1) AND AMOUNT_HTVA>0", array(), false)[0]['totalCA'];

echo json_encode($response);
die;
?>
