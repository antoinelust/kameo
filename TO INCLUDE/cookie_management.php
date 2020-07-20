<?php
//session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


$response=array();
//récupération des données du $_POST (pré boucle)

if(isset($_POST['action'])){
    $action = isset($_POST["action"]) ? $_POST["action"] : NULL;

    if($action == 'set'){
      $type = isset($_POST["type"]) ? $_POST["type"] : NULL;
      if($type=='GDPR'){
          setcookie('GDPR', 'accepted', time() + 365*24*3600, null, null, false, true);
          $response['response']="success";
          
      }

    }
}

if(isset($_GET['action'])){
    $action = isset($_GET["action"]) ? $_GET["action"] : NULL;
    $type = isset($_GET["type"]) ? $_GET["type"] : NULL;
        
    
    if($action=="retrieve" && $type="GDPR"){
        
        
        if(isset($_COOKIE['GDPR'])){
            $response['response']="success";
            $response['GDPR']=$_COOKIE['GDPR'];
        }else{
            $response['response']="success";
            $response['GDPR']="false";
        }
        
        
    }
}


echo json_encode($response);
die;

