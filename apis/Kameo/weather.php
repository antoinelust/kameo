<?php

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


$connected=@fsockopen("www.google.com", 80, $errno, $errstr, 10);
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';

if($connected && constant('ENVIRONMENT')!="local"){

    if(!isset($_SESSION))
    {
        session_start();
    }

    include 'globalfunctions.php';

    $date=$_POST['date'];
    $address=$_POST['address'];

    $timestamp=strtotime($date);

    $address = str_replace(', ', ',', $address);
    $address= str_replace(str_split(' \,'),"+",$address);

    $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=AIzaSyADDgTKivQUzNh2Aatlvdv1W9H1_n7GZro";
    $responseAPI=getAPIData($url);
    $json_a = json_decode($responseAPI, true);


    $latitude=$json_a['results']['0']['geometry']['location']['lat'];
    $longitude=$json_a['results']['0']['geometry']['location']['lng'];

    if($json_a['status']<>"OK")
    {
        errorMessage("ES0009");
    }

    $url="https://api.darksky.net/forecast/15e074760f5f027d4e1857a5668486ae/".$latitude.",".$longitude.",".$timestamp;

    if(strlen($_SESSION['langue'])==2){
        $url1=$url."?lang=".$_SESSION['langue']."&exclude=daily&units=si";
    } else{
        $url1=$url."?lang=en&exclude=daily&units=si";
    }

    $responseAPI=getAPIData($url1);
    $json_a = json_decode($responseAPI, true);


    $response=array();
    $response['response']="success";
    $response['temperature']=$json_a['currently']['temperature'];
    $response['icon']=$json_a['currently']['icon'];
    $response['precipProbability']=($json_a['currently']['precipProbability'] * 100);
    $response['windSpeed']=$json_a['currently']['windSpeed'];
    echo json_encode($response);
    die;


}else{
    $response['response']="success";
    $response['temperature']=20;
    $response['icon']="clearday";
    $response['precipProbability']=0;
    $response['windSpeed']=0;
    echo json_encode($response);
    die;
}


?>
