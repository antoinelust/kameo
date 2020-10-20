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


function get_prices($retailPrice, $company){


  if($company != NULL){
    include 'connexion.php';
    $sql="SELECT * FROM conditions WHERE COMPANY='$company' AND NAME='generic'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    if($result->num_rows == 1){
      $discount=$resultat['DISCOUNT'];
      $remainingPriceIncludedInLeasing=$resultat['REMAINING_PRICE_INCLUDED_IN_LEASING'];
    }else{
      $discount=0;
      $remainingPriceIncludedInLeasing="N";
    }
  }


    // Form Fields
    global $marginBike, $marginOther, $leasingDuration, $otherCost;
    $priceRetailer=$retailPrice*(1-0.27);
    $leasingPrice=(($priceRetailer*(1+$marginBike)+$otherCost*(1+$marginOther))/$leasingDuration)*(100-$discount)/100;

    if($remainingPriceIncludedInLeasing=="Y"){
      $leasingPrice=$leasingPrice + ($retailPrice*0.16/$leasingDuration);
    }

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
    $company = isset($_POST['company']) ? addslashes($_POST['company']) : NULL;
    $response=get_prices($_POST["retailPrice"], $company);
    echo json_encode($response);
    die;
}


?>
