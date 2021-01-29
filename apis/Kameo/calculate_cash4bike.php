<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


session_start();
include 'globalfunctions.php';

$connected=@fsockopen("www.google.com", 80);

if($connected){

    $response=[];

    $type=isset($_GET['type']) ? $_GET['type'] : false;
    $revenuEmployee=isset($_GET['revenu']) ? $_GET['revenu'] : false;
    $domicile=isset($_GET['domicile']) ? addslashes($_GET['domicile']) : NULL;
    $travail=isset($_GET['travail']) ? addslashes($_GET['travail']) : NULL;
    $prime=isset($_GET['prime']) ? $_GET['prime'] : NULL;
    $transport=isset($_GET['transport']) ? $_GET['transport'] : NULL;
    $transportationEssence=isset($_GET['transportationEssence']) ? $_GET['transportationEssence'] : NULL;
    $bike=isset($_GET['model']) ? $_GET['model'] : NULL;
    $leasingAmount=isset($_GET['leasingAmount']) ? $_GET['leasingAmount'] : NULL;
    $frequenceBikePerWeek=isset($_GET['frequence']) ? $_GET['frequence'] : NULL;

    if($bike=="selection"){
        errorMessage("ES0057");
    }

    $domicile = str_replace(', ', ',', $domicile);
    $domicile= str_replace(str_split(' \,'),"+",$domicile);
    $travail = str_replace('\'', '', $travail);


    $travail = str_replace(', ', ',', $travail);
    $travail= str_replace(str_split(' \,'),"+",$travail);
    $travail = str_replace('\'', '', $travail);

    $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$domicile."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";

    $feedback=getAPIData($url);
    $json_a = json_decode($feedback, true);

    if($json_a['status']!="OK")
    {
        errorMessage("ES0009");
    }


    $latitude_start=$json_a['results']['0']['geometry']['location']['lat'];
    $longitude_start=$json_a['results']['0']['geometry']['location']['lng'];

    $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$travail."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";

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
    $response=getAPIData($url);
    $json_a = json_decode($response, true);

    if($json_a['status']=="OK")
    {
        $durationBike=$json_a['routes']['0']['legs']['0']['duration']['value'];
        $distanceBike=$json_a['routes']['0']['legs']['0']['distance']['value'];
    } else{
        errorMessage("ES0009");
    }


    //Then in car

    $url="https://maps.googleapis.com/maps/api/directions/json?origin=".$latitude_start.",".$longitude_start."&destination=".$latitude_end.",".$longitude_end."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";
    $response=getAPIData($url);
    $json_a = json_decode($response, true);

    if($json_a['status']=="OK")
    {
        $durationCar=$json_a['routes']['0']['legs']['0']['duration']['value'];
        $distanceCar=$json_a['routes']['0']['legs']['0']['distance']['value'];

    } else{
        errorMessage("ES0009");
    }

    $durationBike=round($durationBike/60);
    $durationCar=round($durationCar/60);


    $response=array();
    $response['type']=$type;
    $response['revenu']=$revenuEmployee;
    $response['transport']=$transport;
    $response['transportationEssence']=$transportationEssence;
    $response['bike']=$bike;


    $response['duration_car']=$durationCar;
    $response['duration_bike']=$durationBike;
    $response['distance_car']=$distanceCar;
    $response['distance_bike']=$distanceBike;



    if($type=='ouvrier'){
        $impactOnGrossSalary=($leasingAmount*12/17.58);
    }else if($type=="employe"){
        $impactOnGrossSalary=($leasingAmount*12/18.08);
    }else{
        errorMessage("ES0012");
    }

    $socialCotisation=$impactOnGrossSalary*0.1307;
    $basisForTaxes=$impactOnGrossSalary-$socialCotisation;

    if($revenuEmployee<636.49){
        $taxRate=0;
    }
    else if($revenuEmployee>=636.49 && $revenuEmployee < 951.87){
        $taxeRate=(($revenuEmployee-636.49)*0.25)/$revenuEmployee;
    }else if($revenuEmployee >= 951.87 && $revenuEmployee < 1680.32){
        $taxRate=(($revenuEmployee-951.87)*0.4)/$revenuEmployee;
    }else if($revenuEmployee >= 1680.32 && $revenuEmployee < 2908.05){
        $taxRate=(($revenuEmployee-1949.17)*0.45 + (1680.32-951.87)*0.4)/$revenuEmployee;
    }else if($revenuEmployee >= 2908.05 ){
        $taxRate=(($revenuEmployee-2908.05)*0.5 + (2908.05-1680.32)*0.44 + (1680.32-951.87)*0.4+ (951.87-636.49)*0.4)/$revenuEmployee;
    }else{
        errorMessage("ES0012");
    }

    $taxes=$basisForTaxes*$taxRate;

    $impactOnNetSalary=($basisForTaxes-$taxes);

    if($prime=='0'){
        $impactBikeAllowance=0;
    }else if($prime=="1"){
        $primeForBike=0.24;
        $impactBikeAllowance=($primeForBike*$frequenceBikePerWeek*2*$distanceBike/1000*4);
    }else{
        errorMessage("ES0012");
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
    }else if($transport=="covoiturage" || $transport == "public transport" || $transport == "personalBike" || $transport == "walk"){
        $impactCarSavingMoney=0;
        $impactCarSavingCO2=0;
    }else{
        errorMessage("ES0012");
    }

    $response['leasingAmount']=round($leasingAmount);
    $response['impactOnGrossSalary']=round($impactOnGrossSalary);
    $response['impactBikeAllowance']=round($impactBikeAllowance);
    $response['impactOnNetSalary']=round($impactOnNetSalary);
    $response['impactCarSaving']=round($impactCarSavingMoney);
    $response['impactCarSavingCO2']=round($impactCarSavingCO2/1000);
    $response['totalImpact']=round($impactOnNetSalary-$impactBikeAllowance-$impactCarSavingMoney);


    include 'connexion.php';
  	$sql = "INSERT INTO cash4bike (USR_MAJ, DOMICILE, TRAVAIL, REVENU, TRANSPORT,  ESSENCE, PRIME, FREQUENCE, MODEL) VALUES ('cash4Bike.php', '$domicile', '$travail', '$revenuEmployee', '$transport', '$transportationEssence', '$prime', '$frequenceBikePerWeek', '$bike')";
  	if ($conn->query($sql) === FALSE) {
  		$response = array ('response'=>'error', 'message'=> $conn->error);
  		echo json_encode($response);
  		die;
  	}
  	$conn->close();


    $response['message']="Veuillez trouver le résultat de l'analyse ci-dessous";
    echo json_encode($response);
    die;


}else{
        $response['duration_walking']=0;
        $response['duration_bike']=0;
        $response['duration_car']=0;
        $response['distance_bike']=0;

        echo json_encode($response);
        die;

}
?>
