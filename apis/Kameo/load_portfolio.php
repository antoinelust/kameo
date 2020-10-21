<?php
session_cache_limiter('nocache');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: application/json');
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

try{
    if(isset($_GET['action'])){
        $action=isset($_GET['action']) ? $_GET['action'] : NULL;
        $comingFrom=isset($_GET['comingFrom']) ? $_GET['comingFrom'] : NULL;
        $ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;

        if($action=="list"){

            $response=array();
            $marginBike=0.7;
            $marginOther=0.3;
            $leasingDuration=36;

            include 'connexion.php';
            
            if($comingFrom)
            {
                $sql="SELECT ID as ID, BRAND as brand, MODEL as model, FRAME_TYPE as frameType, UTILISATION as utilisation, ELECTRIC as electric, STOCK as stock, DISPLAY as display, BUYING_PRICE as buyPrice, PRICE_HTVA as price, LINK as url, (round((PRICE_HTVA*(1-0.27)*(1+?)+(3*84+4*100)*(1+?))/?)) as leasingPrice, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission FROM bike_catalog WHERE STAANN != 'D' ORDER BY STOCK DESC, MODEL";
            }
            else
            {
                $sql="SELECT ID as ID, BRAND as brand, MODEL as model, FRAME_TYPE as frameType, UTILISATION as utilisation, ELECTRIC as electric, STOCK as stock, DISPLAY as display, BUYING_PRICE as buyPrice, PRICE_HTVA as price, LINK as url, (round((PRICE_HTVA*(1-0.27)*(1+?)+(3*84+4*100)*(1+?))/?)) as leasingPrice, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission FROM bike_catalog WHERE STAANN != 'D' ORDER BY BRAND, MODEL";
            }
            
            $stmt = $conn->prepare($sql);
            if($stmt){
                $stmt->bind_param('ddi', $marginBike, $marginOther, $leasingDuration);
                $stmt->execute();
                $response['response']="success";
                $bike = ($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
                $response['bikeNumber']=count($bike);
                $response['bike']=($bike);
                echo json_encode($response);
            }else{
                error_message('500', 'Unable to retrieve portfolio bikes');
            }
            $stmt->close();
            $conn->close();
            die;

        }
        if($action=="retrieve"){
            include 'connexion.php';
            $sql="SELECT ID, BRAND as brand, MODEL as model, FRAME_TYPE as frameType, UTILISATION as utilisation, ELECTRIC as electric, STOCK as stock, DISPLAY as display, BUYING_PRICE as buyingPrice, PRICE_HTVA as portfolioPrice, LINK as url, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission FROM bike_catalog WHERE ID='$ID'";
            $stmt = $conn->prepare($sql);
            if($stmt){
                //$stmt->bind_param('ffi', $marginBike, $marginOther, $leasingDuration);
                $stmt->execute();
                $response = array("response" => "success");
                $response = array_merge($response,$stmt->get_result()->fetch_array(MYSQLI_ASSOC));
            }else{
                error_message('500', 'Unable to retrieve portfolio bike');
            }
            $stmt->close();
            $conn->close();

            $response['img']=strtolower(str_replace(" ", "-", $response['brand']))."_".strtolower(str_replace(" ", "-", $response['model']))."_".strtolower($response['frameType']);
			echo json_encode($response);
        }
    }else{
        errorMessage("ES0012");
    }

}  catch (Exception $e) {
    $response['response']="error";
    $response['message']=$e->getMessage();
    echo json_encode($response);
    die;

}



?>
