<?php
$seconds_to_cache = 60*60*24;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Pragma: cache");
header("Cache-Control: max-age=$seconds_to_cache");
header('Content-type: application/json');


include 'globalfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/authentication.php';
$token = getBearerToken();

//if(get_user_permissions("admin", $token)){
if(isset($_GET['action'])){
  $action=isset($_GET['action']) ? $_GET['action'] : NULL;
  $item=isset($_GET['item']) ? $_GET['item'] : NULL;

  if($action=="list"){
    $errorArrayLeasingDuration=array();
    $errorIndex=0;
      if($item=="bikesAndBoxes"){
        $response=array();
        $response['bike']['selling']=execSQL("SELECT ID as bikeID, FRAME_NUMBER as frameNumber from customer_bikes WHERE CONTRACT_TYPE='selling' AND SELLING_DATE is NULL", array(), false);
        $response['bike']['sellingCompany']=execSQL("SELECT ID as bikeID, FRAME_NUMBER as frameNumber from customer_bikes WHERE CONTRACT_TYPE='selling' AND COMPANY = 'KAMEO'", array(), false);
        $response['bike']['order']=execSQL("SELECT customer_bikes.ID as 'bikeID', client_orders.ESTIMATED_DELIVERY_DATE as 'clientDeliveryDate', customer_bikes.ESTIMATED_DELIVERY_DATE as 'supplierDeliveryDate' FROM client_orders, customer_bikes WHERE customer_bikes.CONTRACT_TYPE = 'order' AND client_orders.BIKE_ID=customer_bikes.ID AND client_orders.STATUS='confirmed' AND (client_orders.ESTIMATED_DELIVERY_DATE < customer_bikes.ESTIMATED_DELIVERY_DATE OR client_orders.ESTIMATED_DELIVERY_DATE > DATE_ADD(customer_bikes.ESTIMATED_DELIVERY_DATE, INTERVAL 20 DAY))", array(), false);
        $response['bike']['stock']=execSQL("SELECT ID as id, FRAME_NUMBER as frameNumber FROM customer_bikes WHERE CONTRACT_TYPE='stock' AND COMPANY != 'KAMEO'", array(), false);


        $response['bike']['bill']=array();

        include 'connexion.php';
        $sql="SELECT * FROM customer_bikes aa WHERE COMPANY != 'KAMEO' AND CONTRACT_START is NOT NULL and STAANN != 'D' and (CONTRACT_TYPE = 'leasing' OR CONTRACT_TYPE = 'renting') and BILLING_TYPE != 'paid'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $conn->close();

        $i=0;
        $j=0;

        while($row = mysqli_fetch_array($result)){

            $bikeID=$row['ID'];
            $bikeNumber=$row['FRAME_NUMBER'];
            $companyBike=$row['COMPANY'];
            $contractStart=new DateTime($row['CONTRACT_START'], new DateTimeZone('Europe/Brussels'));

            if($row['CONTRACT_TYPE']=='leasing'){
              if($row['CONTRACT_END'] != NULL){
                  $contractEnd=new DateTime($row['CONTRACT_END']);
                  $interval = $contractEnd->diff($contractStart);
                  $ts1 = strtotime($row['CONTRACT_START']);
                  $ts2 = strtotime($row['CONTRACT_END']);

                  $year1 = date('Y', $ts1);
                  $year2 = date('Y', $ts2);
                  $month1 = date('m', $ts1);
                  $month2 = date('m', $ts2);
                  $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

                  if(!in_array($diff, array('24', '36', '48'))){
                    $errorArrayLeasingDuration[$errorIndex]['bikeID']=$bikeID;
                    $errorArrayLeasingDuration[$errorIndex]['frameNumber']=$row['FRAME_NUMBER'];
                    $errorArrayLeasingDuration[$errorIndex]['contractStart']=$row['CONTRACT_START'];
                    $errorArrayLeasingDuration[$errorIndex]['contractEnd']=$row['CONTRACT_END'];
                    $errorArrayLeasingDuration[$errorIndex]['contractDuration']=$diff;
                    $errorIndex++;
                  }
                  $now=new DateTime('now');
              }else{
                  $contractEnd=new DateTime('now');
              }
            }else if($row['CONTRACT_TYPE']=='renting'){
                if($row['CONTRACT_END'] != NULL){
                    $contractEnd=new DateTime($row['CONTRACT_END'], new DateTimeZone('Europe/Brussels'));
                    $now=new DateTime('now', new DateTimeZone('Europe/Brussels'));
                }else{
                    $contractEnd=new DateTime('now');
                }


                if($contractEnd->format('m')==1){
                    $monthBefore=12;
                    $yearBefore=(($contractEnd->format('Y'))-1);
                }else{
                    $monthBefore=(($contractEnd->format('m'))-1);
                    $yearBefore=$contractEnd->format('Y');
                }
                $dayBefore=$contractEnd->format('d');

                if(strlen($monthBefore)==1){
                    $monthBefore='0'.$monthBefore;
                }
                if(strlen($dayBefore)==1){
                    $dayBefore='0'.$dayBefore;
                }
                $contractEnd=new DateTime($yearBefore.'-'.$monthBefore.'-'.$dayBefore);
            }

            if($now<$contractEnd){
                $contractEnd=$now;
            }

            $billingType=$row['BILLING_TYPE'];

            $day=$contractStart->format('d');
            $month=$contractStart->format('m');
            $year=$contractStart->format('Y');

            $dateTemp=new DateTime($row['CONTRACT_START']);

            while($dateTemp<=$contractEnd){
              $dateTempString=$dateTemp->format('d-m-Y');
              $dateTempString2=$dateTemp->format('Y-m-d');

              include 'connexion.php';
              $sql="SELECT * FROM factures_details WHERE ITEM_TYPE='bike' AND ITEM_ID='$bikeID' and DATE_START = '$dateTempString2'";

              $j++;

              if ($conn->query($sql) === FALSE) {
                  $response = array ('response'=>'error', 'message'=> $conn->error);
                  echo json_encode($response);
                  die;
              }
              $result2 = mysqli_query($conn, $sql);
              $length = $result2->num_rows;
              $conn->close();

              if($length == 0){
                  $response['bike']['bill'][$i]['bikeID']=$bikeID;
                  $response['bike']['bill'][$i]['bikeNumber']=$bikeNumber;
                  $response['bike']['bill'][$i]['company']=$companyBike;
                  $response['bike']['bill'][$i]['date']=$dateTempString;
                  $response['bike']['bill'][$i]['description']="Facture manquante pour le vélo à la date du $dateTempString";
                  $i++;
              }


              if($billingType=='annual'){
                $year++;
              }else{
                if($month=='12'){
                    $month='01';
                    $year++;
                }else{
                    $month++;
                }
              }

              $dateTemp=new DateTime($row['CONTRACT_START']);

              if($day>last_day_month($month)){
                  $dayTemp=last_day_month($month);
              }else{
                  $dayTemp=$day;
              }
              $dateTemp->setDate($year, $month, $dayTemp);
            }
        }
        $response['contract']=$errorArrayLeasingDuration;

        include 'connexion.php';
        $sql="SELECT * FROM boxes aa WHERE COMPANY != 'KAMEO' AND START is NOT NULL and STAANN != 'D'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $conn->close();

        $i=0;
        $j=0;

        $response['box']=array();
        $response['box']['bill']=array();
        while($row = mysqli_fetch_array($result)){
            $boxID=$row['ID'];
            $company=$row['COMPANY'];
            $contractStart=new DateTime($row['START'], new DateTimeZone('Europe/Brussels'));
            $dateTemp=$contractStart;

            if($row['END'] != NULL){
                $contractEnd=new DateTime($row['END'], new DateTimeZone('Europe/Brussels'));
                $now=new DateTime('now');
                if($now<$contractEnd){
                    $contractEnd=$now;
                }
            }else{
                $contractEnd=new DateTime('now', new DateTimeZone('Europe/Brussels'));
            }

            $day=$contractStart->format('d');
            $month=$contractStart->format('m');
            $year=$contractStart->format('Y');
            while($dateTemp<=$contractEnd){
                $dateTempString=$dateTemp->format('d-m-Y');
                $dateTempString2=$dateTemp->format('Y-m-d');

                include 'connexion.php';
                $sql="SELECT * FROM factures_details WHERE ITEM_TYPE='box' AND ITEM_ID='$boxID' and DATE_START = '$dateTempString2'";
                $j++;


                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result2 = mysqli_query($conn, $sql);
                $length = $result2->num_rows;
                $conn->close();

                if($length == 0){
                  $response['box']['bill'][$i]['boxID']=$boxID;
                  $response['box']['bill'][$i]['company']=$company;
                  $response['box']['bill'][$i]['description']="Facture manquante pour la borne à la date du $dateTempString";
                  $i++;
                }

                if($month=='12'){
                    $month='01';
                    $year++;
                }else{
                    $month++;
                }

                if($day>last_day_month($month)){
                    $dayTemp=last_day_month($month);
                }else{
                    $dayTemp=$day;
                }
                $dateTemp->setDate($year, $month, $dayTemp);
            }
        }

        $response['orders']=execSQL("SELECT client_orders.ID, client_orders.BIKE_ID FROM client_orders, customer_bikes WHERE client_orders.BIKE_ID=customer_bikes.ID AND customer_bikes.CONTRACT_TYPE in ('leasing', 'renting', 'selling') AND client_orders.STATUS != 'done'", array(), false);

        $response['maintenance']['KAMEOBikes']=execSQL("SELECT entretiens.ID, customer_bikes.ID as bikeID FROM entretiens, customer_bikes WHERE entretiens.STATUS in ('DONE', 'DELIVERED_TO_CLIENT') AND AVOID_BILLING=0 AND entretiens.EXTERNAL_BIKE=0 AND entretiens.BIKE_ID=customer_bikes.ID AND (customer_bikes.CONTRACT_TYPE in ('selling') OR (customer_bikes.CONTRACT_TYPE='leasing') AND entretiens.LEASING_TO_BILL=1) AND NOT EXISTS (SELECT 1 FROM factures_details WHERE factures_details.ITEM_TYPE='maintenance' AND factures_details.ITEM_ID=entretiens.ID)", array(), false);
        $response['maintenance']['externalBikes']=execSQL("SELECT entretiens.ID, external_bikes.ID as bikeID FROM entretiens, external_bikes WHERE entretiens.STATUS in ('DONE', 'DELIVERED_TO_CLIENT') AND AVOID_BILLING=0 AND entretiens.EXTERNAL_BIKE=1 AND entretiens.BIKE_ID=external_bikes.ID AND NOT EXISTS (SELECT 1 FROM factures_details WHERE factures_details.ITEM_TYPE='maintenance' AND factures_details.ITEM_ID=entretiens.ID)", array(), false);

        $response['response']="success";
        echo json_encode($response);
        die;


      }

  }
}else{

}


?>
