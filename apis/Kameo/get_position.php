<?php

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

//include '../../vendor/firebase/php-jwt/src/JWT.php';
include 'globalfunctions.php';

/*use \Firebase\JWT\JWT;

$key = "example_key";
$payload = array(
    "iss" => "http://example.org",
    "aud" => "http://example.com",
    "iat" => 1356999524,
    "nbf" => 1357000000
);*/

/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
//$jwt = JWT::encode($payload, $key);
//$decoded = JWT::decode($jwt, $key, array('HS256'));


/*if( !isset($_SERVER['PHP_AUTH_USER']) )
{
    if (isset($_SERVER['HTTP_AUTHORIZATION']) && (strlen($_SERVER['HTTP_AUTHORIZATION']) > 0)){
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
        if( strlen($_SERVER['PHP_AUTH_USER']) == 0 || strlen($_SERVER['PHP_AUTH_PW']) == 0 )
        {
            unset($_SERVER['PHP_AUTH_USER']);
            unset($_SERVER['PHP_AUTH_PW']);
        }
    }
}*/


//if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
    include 'connexion.php';
    /*$user=$_SERVER['PHP_AUTH_USER'];
    $password=$_SERVER['PHP_AUTH_PW'];
    $sql="SELECT * FROM api_access WHERE EMAIL='$user' and PASSWORD = '$password'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    $conn->close();

    if($length == 1){*/
        // set response code
        http_response_code(200);
        $response['authorization']="Login succesfull";


        if(isset($_GET['bikeName'])){
            $bikeName=$_GET['bikeName'];
            include 'connexion.php';
            $sql="SELECT * FROM customer_bikes aa, bike_catalog bb WHERE aa.FRAME_NUMBER='$bikeName' and aa.TYPE=bb.ID";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $length = $result->num_rows;
            $conn->close();
        }else if(isset($_GET['bikeId'])){
            $bikeId=$_GET['bikeId'];
            include 'connexion.php';
            $sql="SELECT * FROM customer_bikes aa, bike_catalog bb WHERE aa.ID='$bikeId' and aa.TYPE=bb.ID";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $result = mysqli_query($conn, $sql);
            $length = $result->num_rows;
            $conn->close();
        }else{
            // set response code
            http_response_code(404);

            // tell the user login failed
            echo json_encode(array("message" => "Bike not found."));
            die;
        }


        if($length==1){
            $resultat=mysqli_fetch_array($result);
            $GPS_ID=$resultat['GPS_ID'];
            $response['GPS_ID']=$GPS_ID;
            $response['brand']=$resultat['BRAND'];
            $response['model']=$resultat['MODEL'];

            if($GPS_ID == ''){
                // set response code
                http_response_code(404);

                // tell the user login failed
                echo json_encode(array("message" => "GPS not defined for this bike."));
                die;
            }else if(strlen ($GPS_ID)==5){
                /*$data['deviceId']=$GPS_ID;
                $data['from']='1963-11-22T18%3A30%3A00Z';
                $data['to']='2020-05-25T18%3A30%3A00Z';
                $callBack=CallAPI('POST', 'traccar.powunity.com/api/reports/summary', $data, 'antoine.lust@kameobikes.com', 'atoinelust');
                echo $callBack;*/

                $now=new DateTime('now');

                $MonthBefore=new DateTime('now');
                $interval = new DateInterval('P7D');
                $MonthBefore->sub($interval);

                $param='deviceId='.$GPS_ID.'&from='.$MonthBefore->format('Y-m-d').'T'.$MonthBefore->format('H:m:s').'Z&to='.$now->format('Y-m-d').'T'.$now->format('H:m:s')."Z";

                $ch = curl_init();
                //curl_setopt($ch, CURLOPT_URL, 'https://traccar.powunity.com/api/reports/route?'.$param);
                curl_setopt($ch, CURLOPT_URL, 'https://traccar.powunity.com/api/positions?'.$param);
                curl_setopt($ch, CURLOPT_USERPWD, 'antoine.lust@kameobikes.com' . ":" . 'antoinelust');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                $content = curl_exec($ch);
                curl_close($ch);
                $json_a = json_decode($content, true);
                $length=sizeof($json_a);
                $response['id']=$json_a[$length-1]['id'];
                $response['bikeNumber']=$resultat['FRAME_NUMBER'];
                $update= new DateTime($json_a[0]['deviceTime']);
                $response['timestamp']= $update->format('Y-m-d H:m:s');

                $response['batteryLevel']= $json_a[$length-1]['attributes']['batteryLevel'];
                $response['url']='https://traccar.powunity.com/api/positions?'.$param;

                $response['latitude']= $json_a[$length-1]['latitude'];
                $response['longitude']= $json_a[$length-1]['longitude'];


                http_response_code(200);
                echo json_encode($response);
                die;



            }else if(strlen ($GPS_ID)==16){

                $now=new DateTime('now');

                $MonthBefore=new DateTime('now');
                $interval = new DateInterval('P7D');
                $MonthBefore->sub($interval);

                $param='group=kameobikes&serial='.$GPS_ID.'&limit=1';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.invoxia.com/api/v1/gettrackerdata/?'.$param);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                curl_setopt($ch, CURLOPT_HTTPHEADER , array("X-Api-Key: R2JPahhqb32cPbJE92xuKRjwyLbycn"));
                $content = curl_exec($ch);
                curl_close($ch);
                $json_a = json_decode($content, true);

                if(!isset($json_a[$length-1]['attributes']['batteryLevel'])){
                    $batteryLevel=0;
                }else{
                    $batteryLevel= $json_a[$length-1]['attributes']['batteryLevel']*20;
                }

                $response['id']=$GPS_ID;
                $response['bikeNumber']=$resultat['FRAME_NUMBER'];
                $response['batteryLevel']=$batteryLevel;
                $update= new DateTime($json_a[0]['datetime']);
                $response['timestamp']= $update->format('Y-m-d H:m:s');
                $response['latitude']= $json_a[0]['lat'];
                $response['longitude']= $json_a[0]['lng'];
                $response['url']='https://api.invoxia.com/api/v1/gettrackerdata/?'.$param;

                http_response_code(200);
                echo json_encode($response);
                die;

            }else{

                // set response code
                http_response_code(404);

                // tell the user login failed
                echo json_encode(array("message" => "GPS not defined for this bike."));
                die;
            }
        }


    /*}else{
        // set response code
        http_response_code(401);

        // tell the user login failed
        echo json_encode(array("message" => "Wrong credentials."));
        die;
    }
}else{
    // set response code
    http_response_code(401);

    // tell the user login failed
    echo json_encode(array("message" => "Login failed."));
    die;
}*/


?>
