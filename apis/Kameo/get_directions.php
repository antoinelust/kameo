<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
require_once '../../include/api_keys.php';

$connected=@fsockopen("www.google.com", 80);
if($connected){

    $response=array();

    $address_start=isset($_POST['address_start']) ? $_POST['address_start'] : NULL;
    $address_end=isset($_POST['address_end']) ? $_POST['address_end'] : NULL;
    $timestamp=isset($_POST['timestamp']) ? $_POST['timestamp'] : "now";
    
    $address_start = str_replace(', ', ',', $address_start);
    $address_start= str_replace(str_split(' \,'),"+",$address_start);

    $address_end = str_replace(', ', ',', $address_end);
    $address_end= str_replace(str_split(' \,'),"+",$address_end);


    $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$address_start."&key=".$google_token;
    

    $responseAPI=getAPIData($url);	
    $json_a = json_decode($responseAPI, true);
    $latitude_start=$json_a['results']['0']['geometry']['location']['lat'];
    $longitude_start=$json_a['results']['0']['geometry']['location']['lng'];

    $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$address_end."&key=".$google_token;

    $responseAPI=getAPIData($url);	
    $json_a = json_decode($responseAPI, true);
    $latitude_end=$json_a['results']['0']['geometry']['location']['lat'];
    $longitude_end=$json_a['results']['0']['geometry']['location']['lng'];

    //Then, calculate duration, first by foot

    $url="https://maps.googleapis.com/maps/api/directions/json?origin=".$latitude_start.",".$longitude_start."&destination=".$latitude_end.",".$longitude_end."&mode=walking&key=".$google_token;
    $responseAPI=getAPIData($url);	
    $json_a = json_decode($responseAPI, true);

    if($json_a['status']=="OK")
    {
        $durationWalking=$json_a['routes']['0']['legs']['0']['duration']['value'];
    } else{
        errorMessage("ES0009");
    }

    // Then in Bike
    $url="https://maps.googleapis.com/maps/api/directions/json?origin=".$latitude_start.",".$longitude_start."&destination=".$latitude_end.",".$longitude_end."&departure_time=".$timestamp."&mode=bicycling&key=".$google_token;
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

    $url="https://maps.googleapis.com/maps/api/directions/json?origin=".$latitude_start.",".$longitude_start."&destination=".$latitude_end.",".$longitude_end."&departure_time=".$timestamp."&key=".$google_token;
    $responseAPI=getAPIData($url);	
    $json_a = json_decode($responseAPI, true);

    if($json_a['status']=="OK")
    {
        $durationCar=$json_a['routes']['0']['legs']['0']['duration']['value'];
    } else{
        errorMessage("ES0009");
    }

        $durationWalking=round($durationWalking/60);
        $durationBike=round($durationBike/60);
        $durationCar=round($durationCar/60);


        $response['duration_walking']=$durationWalking;
        $response['duration_bike']=$durationBike;
        $response['duration_car']=$durationCar;
        $response['distance_bike']=$distanceBike;

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