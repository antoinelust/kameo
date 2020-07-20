<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';

require_once('php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();



// Form Fields
$name = $_POST["widget-newsletter-form-name"];
$firstName = $_POST["widget-newsletter-form-firstName"];
$email = $_POST["widget-newsletter-form-email"];
$antispam = $_POST["widget-newsletter-form-antispam"];

if($name==''){
    errorMessage('ES0026');
}

if($firstName==''){
    errorMessage('ES0027');
}

if($email==''){
    errorMessage('ES0028');
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($antispam) && $antispam == '') {
    
     if($email != '' && $name != '' && $firstName != '') {
            $connected=@fsockopen("www.google.com", 80);
            if($connected){

                    // API to mailchimp ########################################################
                    $authToken = '22b81aff4753a48217567772c2e46ff6-us20';                
                    $md5hash=md5($email);
                    $ch = curl_init('https://us20.api.mailchimp.com/3.0/lists/982e13f200/members/'.$md5hash);
                    curl_setopt_array($ch, array(
                        CURLOPT_POST => FALSE,
                        CURLOPT_RETURNTRANSFER => TRUE,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: apikey '.$authToken,
                            'Content-Type: application/json'
                        )                    
                    ));
                    $response = curl_exec($ch);
                    $json_a = json_decode($response, true);                    
                    
                    if($json_a['status']=="404"){
                        // The data to send to the API
                        $postData = array(
                            "email_address" => "$email", 
                            "status" => "subscribed", 
                            "merge_fields" => array(
                                "FNAME"=> "$firstName",
                                "LNAME"=> "$name")
                        );

                        // Setup cURL
                        $ch = curl_init('https://us20.api.mailchimp.com/3.0/lists/982e13f200/members/');
                        curl_setopt_array($ch, array(
                            CURLOPT_POST => TRUE,
                            CURLOPT_RETURNTRANSFER => TRUE,
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: apikey '.$authToken,
                                'Content-Type: application/json'
                            ),
                            CURLOPT_POSTFIELDS => json_encode($postData)
                        ));
                        // Send the request
                        $response = curl_exec($ch);
                        $json_a = json_decode($response, true);
                    }
                
                    elseif($json_a['status']=="unsubscribed"){
                        // The data to send to the API
                        $postData = array(
                            "status" => "subscribed"
                        );

                        // Setup cURL
                        $ch = curl_init('https://us20.api.mailchimp.com/3.0/lists/982e13f200/members/'.$md5hash.'?status=subscribed');
                        curl_setopt_array($ch, array(
                            CURLOPT_CUSTOMREQUEST => 'PATCH',
                            CURLOPT_RETURNTRANSFER => TRUE,
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: apikey '.$authToken,
                                'Content-Type: application/json'
                            ),
                            CURLOPT_POSTFIELDS => json_encode($postData)
                        ));
                        // Send the request
                        $response = curl_exec($ch);
                        $json_a = json_decode($response, true);
                    }                    

            }
        $response = array ('response'=>'success');  
        echo json_encode($response);         
        die;

    } else {
        $response = array ('response'=>'error');     
        echo json_encode($response);
        die;
    }
    
}
?>
