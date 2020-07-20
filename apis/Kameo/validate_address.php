<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


session_start();
include 'globalfunctions.php';

$connected=@fsockopen("www.google.com", 80);
if($connected){

    $response=[];

    $address=isset($_GET['address']) ? $_GET['address'] : NULL;
    $address = str_replace(', ', ',', $address);
    $address= str_replace(str_split(' \,'),"+",$address);

    $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";

    $feedback=getAPIData($url);	
    $json_a = json_decode($feedback, true);
    
    if($json_a['status']!="OK")
    {
        $response['response']="error";
        $response['message']=$feedback;
        $response['status']=$json_a['status'];
        echo json_encode($response);
        die;
        
    }
        
    $latitude=$json_a['results']['0']['geometry']['location']['lat'];
    $longitude=$json_a['results']['0']['geometry']['location']['lng'];


    $response['response']="success";
    $response['latitude']=$latitude;
    $response['longitude']=$longitude;

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