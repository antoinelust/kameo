<?php
include '../connexion.php';

$rfid=$_GET['uid'];
$minutes=$_GET['minutes'];
$building=$_GET['building'];



$sql="SELECT * from customer_referential WHERE RFID='$rfid'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}


$result = mysqli_query($conn, $sql);  
$resultat = mysqli_fetch_assoc($result);
$length = $result->num_rows;
if($length=="1"){
    $client=$resultat['EMAIL'];
    $company=$resultat['COMPANY'];
    

    function CallAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        
        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
                
        
        $errors = curl_error($curl);
        $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);        

        curl_close($curl);

        return $result;
    }
    
    $dateStart=new DateTime();
    $dateStartString=$dateStart->format("H-m-d H:i");
    $dateEndString=$dateStartString;
        
    $data=array("widget-new-booking-mail-customer" => $client, "widget-new-booking-frame-number" => 'blabla', "widget-new-booking-building-start" => $building, "widget-new-booking-building-end" => $building, "widget-new-booking-locking-code" => "0000", "widget-new-booking-date-start" => $dateStartString , "widget-new-booking-date-end"=> $dateEndString);
        
    $test=CallAPI('POST', 'http://localhost:81/kameo/include/new_booking.php', $data);
    
    var_dump(json_decode($test)); 

    
    

    
}else{
    //pas d'utilisateur trouvé
    echo "-3";
}
?>