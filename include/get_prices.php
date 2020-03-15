<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';

// Form Fields
$retailPrice = $_POST["retailPrice"];
//$retailPrice=100;

$HTVAprice=($retailPrice/1.21);
$HTVApriceRetailer=$HTVAprice*(1-0.27);
$HTVApriceAfterCost=$HTVApriceRetailer+200+4*100+3*84;
$finalHTVAPrice=$HTVApriceAfterCost*1.3/36;


$leasingPrice=$finalHTVAPrice;
$response['leasingPrice']=round($leasingPrice);
$rentingPrice=round($leasingPrice*1.5);
$response['rentingPrice']=$rentingPrice;

$response['HTVARetailPrice']=round($retailPrice/1.21);
$response['TVARetailPrice']=round($retailPrice-$retailPrice/1.21);
$response['avantageFiscalRetailPrice']=round(0.34*$retailPrice/1.21);
$response['finalPriceRetailPrice']=round($retailPrice/1.21-0.34*$retailPrice/1.21);

$response['HTVALeasingPrice']=round($leasingPrice);
$response['avantageFiscalLeasingPrice']=round(0.34*$leasingPrice);
$response['finalPriceLeasingPrice']=round($leasingPrice-0.34*$leasingPrice);

$response['HTVARentingPrice']=round($rentingPrice);
$response['avantageFiscalRentingPrice']=round(0.34*$rentingPrice);
$response['finalPriceRentingPrice']=round($rentingPrice-0.34*$rentingPrice);


echo json_encode($response);
die;

?>
