<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


session_start();
include 'globalfunctions.php';




$address_start=isset($_POST['address_start']) ? $_POST['address_start'] : NULL;
$address_end=isset($_POST['address_end']) ? $_POST['address_end'] : NULL;
$timestamp=isset($_POST['timestamp']) ? $_POST['timestamp'] : NULL;

$address_start = str_replace(', ', ',', $address_start);
$address_start= str_replace(str_split(' \,'),"+",$address_start);

$address_end = str_replace(', ', ',', $address_end);
$address_end= str_replace(str_split(' \,'),"+",$address_end);


$url="https://maps.googleapis.com/maps/api/geocode/json?address=".$address_start."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";

$response=getAPIData($url);	
$json_a = json_decode($response, true);
$latitude_start=$json_a['results']['0']['geometry']['location']['lat'];
$longitude_start=$json_a['results']['0']['geometry']['location']['lng'];

$url="https://maps.googleapis.com/maps/api/geocode/json?address=".$address_end."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";

$response=getAPIData($url);	
$json_a = json_decode($response, true);
$latitude_end=$json_a['results']['0']['geometry']['location']['lat'];
$longitude_end=$json_a['results']['0']['geometry']['location']['lng'];

//Then, calculate duration, first by foot

$url="https://maps.googleapis.com/maps/api/directions/json?origin=".$latitude_start.",".$longitude_start."&destination=".$latitude_end.",".$longitude_end."&mode=walking&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";
$response=getAPIData($url);	
$json_a = json_decode($response, true);

if($json_a['status']=="OK")
{
	$durationWalking=$json_a['routes']['0']['legs']['0']['duration']['value'];
} else{
    errorMessage("ES0009");
}

// Then in Bike
$url="https://maps.googleapis.com/maps/api/directions/json?origin=".$latitude_start.",".$longitude_start."&destination=".$latitude_end.",".$longitude_end."&departure_time=".$timestamp."&mode=bicycling&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";
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

$url="https://maps.googleapis.com/maps/api/directions/json?origin=".$latitude_start.",".$longitude_start."&destination=".$latitude_end.",".$longitude_end."&departure_time=".$timestamp."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";
$response=getAPIData($url);	
$json_a = json_decode($response, true);

if($json_a['status']=="OK")
{
	$durationCar=$json_a['routes']['0']['legs']['0']['duration']['value'];
} else{
    errorMessage("ES0009");
}

	$durationWalking=round($durationWalking/60);
	$durationBike=round($durationBike/60);
	$durationCar=round($durationCar/60);
	
	
	$response=array();
	$response['duration_walking']=$durationWalking;
	$response['duration_bike']=$durationBike;
	$response['duration_car']=$durationCar;
    $response['distance_bike']=$distanceBike;

	echo json_encode($response);
	die;
?>