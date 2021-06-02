<?php
session_cache_limiter('nocache');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: application/json');
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

log_inputs();

try{
  if(isset($_GET['action'])){
    $action=isset($_GET['action']) ? $_GET['action'] : NULL;
    $ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;
    $SIZE=isset($_GET['SIZE']) ? $_GET['SIZE'] : NULL;
    $IDOrder=isset($_GET['IDOrder']) ? $_GET['IDOrder'] : NULL;
    $category=isset($_GET['category']) ? addslashes($_GET['category']) : NULL;

    if($action=="list" || $action=="addBike"){

      $response=array();
      $marginBike=0.7;
      $marginOther=0.3;
      $leasingDuration=36;

      $revenuEmployee=isset($_GET['revenuEmployee']) ? addslashes($_GET['revenuEmployee']) : NULL;
      $frequenceBikePerWeek=isset($_GET['frequenceBikePerWeek']) ? addslashes($_GET['frequenceBikePerWeek']) : NULL;
      $homeAddress=isset($_GET['homeAddress']) ? addslashes($_GET['homeAddress']) : NULL;
      $workAddress=isset($_GET['workAddress']) ? addslashes($_GET['workAddress']) : NULL;
      $type=isset($_GET['type']) ? addslashes($_GET['type']) : "employé";
      $prime=isset($_GET['prime']) ? addslashes($_GET['prime']) : true;
      $transport=isset($_GET['transport']) ? addslashes($_GET['transport']) : "personnalCar";
      $transportationEssence=isset($_GET['transportationEssence']) ? addslashes($_GET['transportationEssence']) : "essence";
      $size=isset($_GET['size']) ? addslashes($_GET['size']) : "*";


      $response['response']="success";


      if($revenuEmployee != NULL && $frequenceBikePerWeek != NULL && $homeAddress != NULL && $workAddress != NULL && $type != NULL && $prime != NULL && $transport != NULL && $transportationEssence != NULL){

        include 'connexion.php';
        $sql = "INSERT INTO cash4bike (USR_MAJ, DOMICILE, TRAVAIL, REVENU, TRANSPORT,  ESSENCE, PRIME, FREQUENCE, MODEL) VALUES ('cash4Bike.php', '$homeAddress', '$workAddress', '$revenuEmployee', '$transport', '$transportationEssence', '$prime', '$frequenceBikePerWeek', '0')";
        if ($conn->query($sql) === FALSE) {
          $response = array ('response'=>'error', 'message'=> $conn->error);
          echo json_encode($response);
          die;
        }
        $conn->close();


        if($revenuEmployee<636.49){
          $taxRate=0;
        }else if($revenuEmployee>=636.49 && $revenuEmployee < 951.87){
          $taxeRate=(($revenuEmployee-636.49)*0.25)/$revenuEmployee;
        }else if($revenuEmployee >= 951.87 && $revenuEmployee < 1680.32){
          $taxRate=(($revenuEmployee-951.87)*0.4)/$revenuEmployee;
        }else if($revenuEmployee >= 1680.32 && $revenuEmployee < 2908.05){
          $taxRate=(($revenuEmployee-1949.17)*0.45 + (1680.32-951.87)*0.4)/$revenuEmployee;
        }else{
          $taxRate=(($revenuEmployee-2908.05)*0.5 + (2908.05-1680.32)*0.44 + (1680.32-951.87)*0.4+ (951.87-636.49)*0.4)/$revenuEmployee;
        }

        $homeAddress = str_replace(', ', ',', $homeAddress);
        $homeAddress= str_replace(str_split(' \,'),"+",$homeAddress);

        $workAddress = str_replace(', ', ',', $workAddress);
        $workAddress= str_replace(str_split(' \,'),"+",$workAddress);


        $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$homeAddress."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";

        $feedback=getAPIData($url);
        $json_a = json_decode($feedback, true);

        if($json_a['status']!="OK")
        {
          errorMessage("ES0009");
        }


        $latitude_start=$json_a['results']['0']['geometry']['location']['lat'];
        $longitude_start=$json_a['results']['0']['geometry']['location']['lng'];

        $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$workAddress."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";

        $feedback=getAPIData($url);
        $json_a = json_decode($feedback, true);

        if($json_a['status']!="OK")
        {
          errorMessage("ES0010");
        }



        $latitude_end=$json_a['results']['0']['geometry']['location']['lat'];
        $longitude_end=$json_a['results']['0']['geometry']['location']['lng'];


              // Then in Bike
        $url="https://maps.googleapis.com/maps/api/directions/json?origin=".$latitude_start.",".$longitude_start."&destination=".$latitude_end.",".$longitude_end."&mode=bicycling&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";
        $responseAPI=getAPIData($url);
        $json_a = json_decode($responseAPI, true);

        if($json_a['status']=="OK")
        {
          $durationBike=$json_a['routes']['0']['legs']['0']['duration']['value'];
          $distanceBike=$json_a['routes']['0']['legs']['0']['distance']['value'];
        } else{
          errorMessage("ES0009");
        }


              //Then in car

        $url="https://maps.googleapis.com/maps/api/directions/json?origin=".$latitude_start.",".$longitude_start."&destination=".$latitude_end.",".$longitude_end."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";
        $responseAPI=getAPIData($url);
        $json_a = json_decode($responseAPI, true);

        if($json_a['status']=="OK")
        {
          $durationCar=$json_a['routes']['0']['legs']['0']['duration']['value'];
          $distanceCar=$json_a['routes']['0']['legs']['0']['distance']['value'];

        } else{
          errorMessage("ES0009");
        }

        $durationBike=round($durationBike/60);
        $durationCar=round($durationCar/60);

        if(!$prime){
          $impactBikeAllowance=0;
        }else{
          $primeForBike=0.24;
          $impactBikeAllowance=($primeForBike*$frequenceBikePerWeek*2*$distanceBike/1000*4);
        }

        if($transportationEssence=="essence"){
          $consomation=7;
          $CO2PerKM=167.44;
          $GazPrice=0.98;
        }else if($transportationEssence=="diesel"){
          $consomation=5.8;
          $CO2PerKM=153.12;
          $GazPrice=1.27;
        }else{
          $consomation=0;
          $CO2PerKM=0;
          $GazPrice=0;
        }

        if($transport=='personnalCar'){
          $impactCarSavingMoney=($consomation*$GazPrice/100*$distanceCar*2*$frequenceBikePerWeek*4/1000);
          $impactCarSavingCO2=($CO2PerKM*$consomation*$frequenceBikePerWeek*2*4);
        }else if($transport=="companyCar"){
          $impactCarSavingMoney=0;
          $impactCarSavingCO2=($CO2PerKM*$consomation/100*$frequenceBikePerWeek*2*4);
        }else{
          $impactCarSavingMoney=0;
          $impactCarSavingCO2=0;
        }
        $response['realImpactCalculated']="Y";
      }


      include 'connexion.php';

      if($size && $size!='*'){

        $indexArray = array("XS" => 1, "S" => 2, "M"=>4, "L"=>8, "XL"=>16, "unique"=>32);
        $indexSize = $indexArray[$size];

        $stockQuery = "AND bb.SIZE = ?";
        $orderQuery = "SELECT 1 from customer_bikes where customer_bikes.STAANN != 'D' AND customer_bikes.SIZE=? AND COMPANY='KAMEO' AND bike_catalog.ID=customer_bikes.TYPE and customer_bikes.CONTRACT_TYPE='order')";
        $sizeInPortfolioQuery = "AND bike_catalog.SIZES & ?";
        if($category){
          $sizeInPortfolioQuery = $sizeInPortfolioQuery." AND UTILISATION='$category'";
        }
      }else{
        $stockQuery = "";
        $orderQuery = "SELECT 1 from customer_bikes where customer_bikes.STAANN != 'D' AND COMPANY='KAMEO' AND bike_catalog.ID=customer_bikes.TYPE and customer_bikes.CONTRACT_TYPE='order')";
        $sizeInPortfolioQuery = "";
        if($category){
          $sizeInPortfolioQuery = $sizeInPortfolioQuery." AND UTILISATION='$category'";
        }
      }

      $sql="(select bike_catalog.ID, BRAND as brand, bike_catalog.MODEL as model, FRAME_TYPE as frameType, UTILISATION as utilisation, ELECTRIC as electric, STOCK as stock, DISPLAY as display, BUYING_PRICE as buyPrice, PRICE_HTVA as price, (round((PRICE_HTVA*(1-0.27)*(1+0.7)+(3*84+4*100)*(1+0.3))/36)) as leasingPrice, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission, SEASON as season, PRIORITY as priority, count(case when bb.SIZE = 'XS' then 1 end) as stockXS, count(case when bb.SIZE = 'S' then 1 end) as stockS, count(case when bb.SIZE = 'M' then 1 end) as stockM, count(case when bb.SIZE = 'L' then 1 end) as stockL, count(case when bb.SIZE = 'XL' then 1 end) as stockXL, count(case when bb.SIZE = 'unique' then 1 end) as stockUni, COUNT(1) as stockTotal, NULL as estimatedDeliveryDate, SIZES as sizes
      from bike_catalog, customer_bikes bb WHERE bike_catalog.ID=bb.TYPE AND bb.STAANN!='D' AND bike_catalog.STAANN != 'D' and bb.COMPANY='KAMEO' and bb.CONTRACT_TYPE='stock' ".$stockQuery."  ".$sizeInPortfolioQuery."  GROUP BY TYPE)

      UNION ALL


      (select bike_catalog.ID, BRAND as brand, bike_catalog.MODEL as model, FRAME_TYPE as frameType, UTILISATION as utilisation, ELECTRIC as electric, STOCK as stock, DISPLAY as display, BUYING_PRICE as buyPrice, PRICE_HTVA as price, (round((PRICE_HTVA*(1-0.27)*(1+0.7)+(3*84+4*100)*(1+0.3))/36)) as leasingPrice, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission, SEASON as season, PRIORITY as priority, '0' as stockXS, '0' as stockS, '0' as stockM, '0' as stockL, '0' as stockXL , '0' as stockUni, 0 as stockTotal, (SELECT min(customer_bikes.ESTIMATED_DELIVERY_DATE) FROM customer_bikes WHERE customer_bikes.TYPE=bike_catalog.ID AND customer_bikes.STAANN !='D' AND customer_bikes.CONTRACT_TYPE='order' and customer_bikes.STAANN != 'D') as estimatedDeliveryDate, SIZES as sizes
      from bike_catalog where bike_catalog.STAANN != 'D'  ".$sizeInPortfolioQuery."  AND not EXISTS (SELECT 1 from customer_bikes where customer_bikes.STAANN != 'D' AND COMPANY='KAMEO' AND bike_catalog.ID=customer_bikes.TYPE and customer_bikes.CONTRACT_TYPE='stock') AND EXISTS (".$orderQuery.")

      UNION ALL

      (select ID, BRAND as brand, MODEL as model, FRAME_TYPE as frameType, UTILISATION as utilisation, ELECTRIC as electric, STOCK as stock, DISPLAY as display, BUYING_PRICE as buyPrice, PRICE_HTVA as price, (round((PRICE_HTVA*(1-0.27)*(1+0.7)+(3*84+4*100)*(1+0.3))/36)) as leasingPrice, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission, SEASON as season, PRIORITY as priority, '0' as stockXS, '0' as stockS, '0' as stockM, '0' as stockL, '0' as stockXL , '0' as stockUni, 0 as stockTotal, NULL as estimatedDeliveryDate, SIZES as sizes
      from bike_catalog where STAANN != 'D' ".$sizeInPortfolioQuery." AND not EXISTS (SELECT 1 from customer_bikes where customer_bikes.STAANN != 'D' AND COMPANY='KAMEO' AND bike_catalog.ID=customer_bikes.TYPE and customer_bikes.CONTRACT_TYPE='stock') AND NOT EXISTS (".$orderQuery.")";

      if($action == "list"){
        $sql = $sql."ORDER BY stockTotal DESC, case when estimatedDeliveryDate is null then 1 else 0 end, estimatedDeliveryDate";
      }else{
        $sql = $sql."ORDER BY BRAND, MODEL";
      }



      $stmt = $conn->prepare($sql);
      if($stmt){
        if($size && $size!='*'){
          $stmt->bind_param('siisis', $size, $indexSize, $indexSize, $size, $indexSize, $size);
        }

        $stmt->execute();
        $bikes = ($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
        $response['bikeNumber']=count($bikes);


        if($revenuEmployee != NULL && $frequenceBikePerWeek != NULL && $homeAddress != NULL && $workAddress != NULL && $type != NULL && $prime != NULL && $transport != NULL && $transportationEssence != NULL){
          foreach($bikes as $index=>$bike){

            if($type=='ouvrier'){
              $impactOnGrossSalary=($bike['leasingPrice']*12/17.58);
            }else{
              $impactOnGrossSalary=($bike['leasingPrice']*12/18.08);
            }

            $socialCotisation=$impactOnGrossSalary*0.1307;
            $basisForTaxes=$impactOnGrossSalary-$socialCotisation;

            $taxes=$basisForTaxes*$taxRate;
            $impactOnNetSalary=($basisForTaxes-$taxes);
            $bikes[$index]["impactOnGrossSalary"]=$impactOnGrossSalary;
            $bikes[$index]["impactOnNetSalary"]=$impactOnNetSalary;
            $bikes[$index]["impactBikeAllowance"]=$impactBikeAllowance;
            $bikes[$index]["impactCarSavingMoney"]=$impactCarSavingMoney;
            $bikes[$index]["realImpact"]=($impactOnNetSalary-$impactBikeAllowance-$impactCarSavingMoney);
          }
        }
        $response['bike']=($bikes);
        echo json_encode($response);
        log_output($response);
      }else{
        error_message('500', 'Unable to list portfolio bikes');
      }
      $stmt->close();
      $conn->close();
      die;
    }
    if($action=="retrieve"){
      $response=execSQL("SELECT ID, BRAND as brand, MODEL as model, FRAME_TYPE as frameType, UTILISATION as utilisation, ELECTRIC as electric, STOCK as stock, DISPLAY as display, BUYING_PRICE as buyingPrice, PRICE_HTVA as portfolioPrice, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission, SEASON as season, PRIORITY as priority, SIZES as sizes, MINIMAL_STOCK as minimalStock FROM bike_catalog WHERE ID=?", array('i', $ID), false)[0];
      include_once 'get_prices.php';
      $response['leasingPrice']=get_prices($response['portfolioPrice']);

      echo json_encode($response);
      die;
    }
  }
  else{
    errorMessage("ES0012");
  }

}  catch (Exception $e) {
  $response['response']="error";
  $response['message']=$e->getMessage();
  echo json_encode($response);
  die;
}


?>
