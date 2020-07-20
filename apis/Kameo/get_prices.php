<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';

$marginBike=0.7;
$marginOther=0.3;
$leasingDuration=36;

// Form Fields
$retailPrice = $_POST["retailPrice"];
$priceRetailer=$retailPrice*(1-0.27);
$debtCost=$priceRetailer*0.09;

$otherCost=3*84+4*100;

$totalCost=($priceRetailer+$debtCost+$otherCost);

$bikeEndSell=0.15*$retailPrice;

$leasingPrice=($priceRetailer*(1+$marginBike)+$otherCost*(1+$marginOther))/$leasingDuration;

$rentabilite=($leasingPrice*$leasingDuration+$bikeEndSell-$totalCost)/$totalCost;

$response['response']="success";
$response['retailPrice']=$retailPrice;
//$response['rentabilite']=$rentabilite;
$response['leasingPrice']=round($leasingPrice);

$response['HTVARetailPrice']=round($retailPrice);
$response['TVARetailPrice']=round($retailPrice-$retailPrice);
$response['avantageFiscalRetailPrice']=round(0.34*$retailPrice);
$response['finalPriceRetailPrice']=round($retailPrice-0.34*$retailPrice);

$response['HTVALeasingPrice']=round($leasingPrice);
$response['avantageFiscalLeasingPrice']=round(0.34*$leasingPrice);
$response['finalPriceLeasingPrice']=round($leasingPrice-0.34*$leasingPrice);

echo json_encode($response);
die;

?>
