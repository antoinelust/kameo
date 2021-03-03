<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/authentication.php';
$token = getBearerToken();


//if(get_user_permissions("admin", $token)){
  if(isset($_GET['action'])){
      $action=isset($_GET['action']) ? $_GET['action'] : NULL;
      $item=isset($_GET['item']) ? $_GET['item'] : NULL;

      if($action=="list"){
          if($item=="bikesAndBoxes"){

            $response=array();
            include 'connexion.php';
            $sql="SELECT * FROM company_actions aa WHERE not exists (select 1 from companies bb where aa.COMPANY=bb.INTERNAL_REFERENCE)";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $conn->close();

            $i=0;
            $response['company']=array();
            while($row = mysqli_fetch_array($result)){
                $response['company']['action'][$i]['id']=$row['ID'];
                $response['company']['action'][$i]['description']="Pas de société définie pour l'action suivante.<br/><strong>Titre : </strong>".$row['TITLE']."<br /> Actuellement identifié sur la société : <strong>".$row['COMPANY']."</strong>";
                $i++;
            }
            include 'connexion.php';
            $sql="SELECT ID as bikeID, FRAME_NUMBER as frameNumber from customer_bikes WHERE CONTRACT_TYPE='selling' AND SELLING_DATE is NULL";
            $result = $conn->query($sql);

            $response['bike']['selling']=array();
            if ($result && $result = $conn->query($sql)){
              $response['bike']['selling'] = $result->fetch_all(MYSQLI_ASSOC);
            }else{
              if ($conn->query($sql) === FALSE){
                  $response = array ('response'=>'error', 'message'=> $conn->error);
                  echo json_encode($response);
                  die;
              }
            }

            include 'connexion.php';
            $sql="SELECT ID as bikeID, FRAME_NUMBER as frameNumber from customer_bikes WHERE CONTRACT_TYPE='selling' AND COMPANY = 'KAMEO'";
            $result = $conn->query($sql);

            if ($result && $result = $conn->query($sql)){
              $response['bike']['sellingCompany'] = $result->fetch_all(MYSQLI_ASSOC);
            }else{
              if ($conn->query($sql) === FALSE){
                  $response = array ('response'=>'error', 'message'=> $conn->error);
                  echo json_encode($response);
                  die;
              }
            }

            $sql="SELECT customer_bikes.ID as 'bikeID', client_orders.ESTIMATED_DELIVERY_DATE as 'clientDeliveryDate', customer_bikes.ESTIMATED_DELIVERY_DATE as 'supplierDeliveryDate' FROM `client_orders`, customer_bike_access, customer_bikes WHERE client_orders.EMAIL=customer_bike_access.EMAIL AND client_orders.STATUS='confirmed' AND customer_bike_access.BIKE_ID=customer_bikes.ID AND (client_orders.ESTIMATED_DELIVERY_DATE < customer_bikes.ESTIMATED_DELIVERY_DATE OR client_orders.ESTIMATED_DELIVERY_DATE > DATE_ADD(customer_bikes.ESTIMATED_DELIVERY_DATE, INTERVAL 20 DAY))";
            if ($conn->query($sql) === FALSE){
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
              $response['bike']['order'] = $result->fetch_all(MYSQLI_ASSOC);
            }

            include 'connexion.php';
            $sql="SELECT * FROM customer_bikes WHERE CONTRACT_TYPE='stock' AND COMPANY != 'KAMEO'";
            if ($conn->query($sql) === FALSE){
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $conn->close();

            $i=0;

            while($row = mysqli_fetch_array($result)){
                $response['bike']['stock'][$i]['id']=$row['ID'];
                $response['bike']['stock'][$i]['frameNumber']=$row['FRAME_NUMBER'];
                $i++;
            }
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
                $contractStart=new DateTime($row['CONTRACT_START']);
                $dateTemp=$contractStart;
                if($row['CONTRACT_TYPE']=='leasing'){
                    if($row['CONTRACT_END'] != NULL){
                        $contractEnd=new DateTime($row['CONTRACT_END']);
                        $now=new DateTime('now');
                        if($now<$contractEnd){
                            $contractEnd=$now;
                        }
                    }else{
                        $contractEnd=new DateTime('now');
                    }
                }else if($row['CONTRACT_TYPE']=='renting'){
                    if($row['CONTRACT_END'] != NULL){
                        $contractEnd=new DateTime($row['CONTRACT_END']);
                        $now=new DateTime('now');
                        if($now<$contractEnd){
                            $contractEnd=$now;
                        }
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

                $day=$contractStart->format('d');
                $month=$contractStart->format('m');
                $year=$contractStart->format('Y');

                while($dateTemp<=$contractEnd){



                    $dateTempString=$dateTemp->format('d-m-Y');
                    $dateTempString2=$dateTemp->format('Y-m-d');

                    include 'connexion.php';
                    $sql="SELECT * FROM factures_details WHERE ITEM_TYPE='bike' AND ITEM_ID='$bikeID' and DATE_START = '$dateTempString2'";

                    //$response['bike']['log'][$j]['bikeID']=$bikeID;
                    //$response['bike']['log'][$j]['bikeNumber']=$bikeNumber;
                    //$response['bike']['log'][$j]['sql']=$sql;
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
                        //$response['bike']['bill'][$i]['sql']=$sql;
                        $response['bike']['bill'][$i]['bikeNumber']=$bikeNumber;
                        $response['bike']['bill'][$i]['description']="Facture manquante pour le vélo à la date du $dateTempString";
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


            $j=0;
            include 'connexion.php';
            $sql="SELECT * FROM customer_bikes aa WHERE COMPANY != 'KAMEO' AND CONTRACT_START is NOT NULL and STAANN != 'D' and (CONTRACT_TYPE = 'selling') and SOLD_PRICE != '0'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_array($result)){
                $bikeID=$row['ID'];
                $bikeNumber=$row['FRAME_NUMBER'];
                $dateTempString=$row['CONTRACT_START'];

                $sql="SELECT * FROM factures_details WHERE ITEM_TYPE='bike' AND ITEM_ID='$bikeID'";

                //$response['bike']['log'][$j]['bikeID']=$bikeID;
                //$response['bike']['log'][$j]['bikeNumber']=$bikeNumber;
                $j++;
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result2 = mysqli_query($conn, $sql);
                $length = $result2->num_rows;

                if($length == 0){
                    $response['bike']['bill'][$i]['bikeID']=$bikeID;
                    //$response['bike']['bill'][$i]['sql']=$sql;
                    $response['bike']['bill'][$i]['bikeNumber']=$bikeNumber;
                    $response['bike']['bill'][$i]['description']="Facture manquante pour le vélo vendu à la date du $dateTempString";
                    $i++;
                }
            }
            $conn->close();

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
            $response['response']="success";
            echo json_encode($response);
            die;


          }

      }
    }
//}else{
//    errorMessage("ES0012");
//}


?>
