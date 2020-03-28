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
    $revenu=isset($_GET['revenu']) ? $_GET['revenu'] : false;
    $domicile=isset($_GET['domicile']) ? $_GET['domicile'] : NULL;
    $travail=isset($_GET['travail']) ? $_GET['travail'] : NULL;
    $transport=isset($_GET['transport']) ? $_GET['transport'] : NULL;
    $transportationEssence=isset($_GET['transportationEssence']) ? $_GET['transportationEssence'] : NULL;
    $bike=isset($_GET['model']) ? $_GET['model'] : NULL;

    $domicile = str_replace(', ', ',', $domicile);
    $domicile= str_replace(str_split(' \,'),"+",$domicile);

    $travail = str_replace(', ', ',', $travail);
    $travail= str_replace(str_split(' \,'),"+",$travail);


    $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$domicile."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";

    $feedback=getAPIData($url);	
    $json_a = json_decode($feedback, true);
    
    if($json_a['status']!="OK")
    {
        $response['response']="error";
        $response['message']=$feedback;
        $response['address']=$domicile;
        
        $response['status']=$json_a['status'];
        echo json_encode($response);
        die;
        
    }
    
    
    $latitude_start=$json_a['results']['0']['geometry']['location']['lat'];
    $longitude_start=$json_a['results']['0']['geometry']['location']['lng'];

    $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$travail."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";

    $feedback=getAPIData($url);	
    $json_a = json_decode($feedback, true);
    
    if($json_a['status']!="OK")
    {
        $response['response']="error";
        $response['message']=$feedback;
        $response['address']=$travail;
        $response['status']=$json_a['status'];
        echo json_encode($response);
        die;
        
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
    $response['revenu']=$revenu;
    $response['transport']=$transport;
    $response['transportationEssence']=$transportationEssence;
    $response['bike']=$bike;
    
    
    
    $response['duration_car']=$durationCar;
    $response['duration_bike']=$durationBike;
    $response['distance_car']=$distanceCar;
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