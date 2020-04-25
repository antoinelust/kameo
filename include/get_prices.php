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

$priceTemp=($retailPrice+3*75+4*100+4*100);


// Calculation of coefficiant for leasing price

if($priceTemp<2500){
    $coefficient=3.289;
}elseif ($priceTemp<=5000){
    $coefficient=3.056;
}elseif ($priceTemp<=12500){
    $coefficient=2.965;
}elseif ($priceTemp<=25000){
    $coefficient=2.921;
}elseif ($priceTemp<=75000){
    $coefficient=2.898;
}else{
	errorMessage(ES0012);
}

$response['response']="success";
$response['retailPrice']=$retailPrice;
$leasingPrice=round(($priceTemp)*($coefficient)/100); 	
$response['leasingPrice']=round($leasingPrice);

$response['HTVARetailPrice']=round($retailPrice/1.21);
$response['TVARetailPrice']=round($retailPrice-$retailPrice/1.21);
$response['avantageFiscalRetailPrice']=round(0.34*$retailPrice/1.21);
$response['finalPriceRetailPrice']=round($retailPrice/1.21-0.34*$retailPrice/1.21);

$response['HTVALeasingPrice']=round($leasingPrice);
$response['avantageFiscalLeasingPrice']=round(0.34*$leasingPrice);
$response['finalPriceLeasingPrice']=round($leasingPrice-0.34*$leasingPrice);

echo json_encode($response);
die;

?>
