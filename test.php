<?php
$priceTemp=1000;

$url='http://localhost:81/kameo/include/load_portfolio.php';
$xml = file_get_contents($url);

$obj = json_decode($xml);
echo $xml;
var_dump($xml);
foreach($obj->{'bike'} as $test){
    echo $test->{'brand'};
};

$curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'localhost:81/kameo/include/get_prices.php?retailPrice='.$priceTemp,
                CURLOPT_USERAGENT => 'Codular Sample cURL Request'
            ]);

            // Send the request & save response to $resp
            $resp = curl_exec($curl);
            // Close request to clear up some resources
            curl_close($curl);


            $obj = json_decode($resp);
            $leasingPrice=$obj->{'leasingPrice'};
echo $leasingPrice;

?>