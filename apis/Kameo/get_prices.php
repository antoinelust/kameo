<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

$marginBike=0.7;
$marginOther=0.3;
$leasingDuration=36;
$otherCost=3*84+4*100;


function get_prices($retailPrice){
    // Form Fields
    global $marginBike, $marginOther, $leasingDuration, $otherCost;
    $priceRetailer=$retailPrice*(1-0.27);
    $debtCost=$priceRetailer*0.09;


    $totalCost=($priceRetailer+$debtCost+$otherCost);

    $bikeEndSell=0.15*$retailPrice;

    $leasingPrice=($priceRetailer*(1+$marginBike)+$otherCost*(1+$marginOther))/$leasingDuration;

    $rentabilite=($leasingPrice*$leasingDuration+$bikeEndSell-$totalCost)/$totalCost;

    $response['response']="success";
    $response['retailPrice']=$retailPrice;
    $response['leasingPrice']=round($leasingPrice);

    $response['HTVARetailPrice']=round($retailPrice);
    $response['TVARetailPrice']=round($retailPrice-$retailPrice);
    $response['avantageFiscalRetailPrice']=round(0.34*$retailPrice);
    $response['finalPriceRetailPrice']=round($retailPrice-0.34*$retailPrice);

    $response['HTVALeasingPrice']=round($leasingPrice);
    $response['avantageFiscalLeasingPrice']=round(0.34*$leasingPrice);
    $response['finalPriceLeasingPrice']=round($leasingPrice-0.34*$leasingPrice);

    return $response;

}



if(isset($_POST["retailPrice"])){
    $retailPrice = $_POST["retailPrice"];
    $response=get_prices($_POST["retailPrice"]);
    echo json_encode($response);
    die;
}


?>
