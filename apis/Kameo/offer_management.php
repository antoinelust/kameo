<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
include_once 'authentication.php';

$token = getBearerToken();
log_inputs($token);
if(!get_user_permissions("admin", $token)){
  error_message('403');
}



if(isset($_POST['action']))
{
    $id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
    $action = isset($_POST["action"]) ? $_POST["action"] : NULL;
    $requestor = isset($_POST["requestor"]) ? $_POST["requestor"] : NULL;
    $company = isset($_POST["company"]) ? $_POST["company"] : NULL;
    $title = isset($_POST["title"]) ? addslashes($_POST["title"]) : NULL;
    $description = isset($_POST["description"]) ? addslashes($_POST["description"]) : NULL;
    $status = isset($_POST["status"]) ? addslashes($_POST["status"]) : NULL;
    $type = isset($_POST["type"]) ? $_POST["type"] : NULL;
    $probability = isset($_POST["probability"]) ? $_POST["probability"] : NULL;
    $amount = isset($_POST["amount"]) ? $_POST["amount"] : NULL;
    $date = isset($_POST["date"]) ? date($_POST["date"]) : NULL;
    $start = isset($_POST["start"]) ? date($_POST["start"]) : NULL;
    $end = isset($_POST["end"]) ? date($_POST["end"]) : NULL;
    $margin = isset($_POST["margin"]) ? date($_POST["margin"]) : NULL;

    if($date!=NULL){
        $date="'".$date."'";
    }else{
        $date='NULL';
    }

    if($start!=NULL){
        $start="'".$start."'";
    }else{
        $start='NULL';
    }

    if($end!=NULL){
        $end="'".$end."'";
    }else{
        $end='NULL';
    }

    if(isset($_POST["action"])){
        if($_POST["action"]=="add"){
            include 'connexion.php';
            $sql="INSERT INTO offers (HEU_MAJ, USR_MAJ, TITRE, DESCRIPTION, STATUS, PROBABILITY, TYPE, AMOUNT, MARGIN, DATE, START, END, COMPANY, STAANN)
             VALUES (CURRENT_TIMESTAMP, '$requestor', '$title', '$description', '$status', '$probability', '$type', '$amount', '$margin', $date, $start, $end, '$company', '')";


            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $conn->close();
            $response['sql']=$sql;
            successMessage("SM0019");

        }else if($_POST["action"]=="update"){

            include 'connexion.php';
            $sql="UPDATE offers SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$requestor', TITRE='$title', TYPE='$type', DESCRIPTION='$description', STATUS='$status', PROBABILITY='$probability', MARGIN='$margin', AMOUNT='$amount', DATE=$date, START=$start, END=$end WHERE ID='$id'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $conn->close();
            $response['sql']=$sql;
            successMessage("SM0020");

        }
    }
    else
    {
        errorMessage("ES0012");
    }
}else if(isset($_GET['action'])){
    $action = isset($_GET["action"]) ? $_GET["action"] : NULL;
    $id = isset($_GET["ID"]) ? $_GET["ID"] : NULL;
    $company = isset($_GET["company"]) ? $_GET["company"] : NULL;
    $graphics = isset($_GET["graphics"]) ? "1" : NULL;


    if($action=="retrieve"){
        if($graphics){

            $response['response']="success";
            include "connexion.php";
            $sql="select MAX(CONTRACT_END) as 'CONTRACT_END' from customer_bikes WHERE AUTOMATIC_BILLING='Y'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $resultat = mysqli_fetch_assoc($result);
            $conn->close();

            $date_end=$resultat['CONTRACT_END'];

            $date_start = new DateTime("NOW");
            $arrayContracts=array();
            $arrayOffers=array();
            $arrayDates=array();
            $arrayCosts=array();
            $arrayFreeCashFlow=array();
            $arrayIN=array();
            $i=0;
            while(($date_start->format('Y-m-d'))<=$date_end){

                $date_start_string=$date_start->format('Y-m-d');

                include 'connexion.php';
                $sql="SELECT SUM(CASE WHEN BILLING_TYPE = 'annual' THEN LEASING_PRICE/12 ELSE LEASING_PRICE END) as 'PRICE' FROM customer_bikes aa WHERE aa.STAANN != 'D' and aa.COMPANY != 'KAMEO' AND aa.CONTRACT_TYPE IN ('leasing', 'location') AND CONTRACT_START <= '$date_start_string' AND CONTRACT_END >= '$date_start_string'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $resultat = mysqli_fetch_assoc($result);
                $conn->close();

                $contractAmountTemp=$resultat['PRICE'];


                include 'connexion.php';
                $sql="SELECT SUM(AMOUNT) AS 'PRICE' FROM boxes WHERE START <= '$date_start_string' AND END >= '$date_start_string' AND STAANN != 'D'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $resultat = mysqli_fetch_assoc($result);
                $conn->close();

                $contractAmountTemp=$contractAmountTemp+$resultat['PRICE'];



                array_push($arrayContracts, round($contractAmountTemp));

                include 'connexion.php';
                $sql="SELECT SUM(AMOUNT) AS 'PRICE' FROM costs WHERE START <= '$date_start_string' AND END >= '$date_start_string'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $resultat = mysqli_fetch_assoc($result);
                array_push($arrayCosts, round(-$resultat['PRICE']));
                $costs=$resultat['PRICE'];

                $conn->close();


                include 'connexion.php';
                $sql="SELECT AMOUNT, PROBABILITY FROM offers WHERE START != '' AND END != '' AND TYPE = 'leasing' AND STATUS='ongoing' AND START <= '$date_start_string' AND END >= '$date_start_string'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $amount=0;
                while($row = mysqli_fetch_array($result)){
                    $amount=$amount+round(($row['AMOUNT']*$row['PROBABILITY']/100));
                }

                array_push($arrayOffers, $amount);
                array_push($arrayDates, $date_start->format('Y-m-d'));
                array_push($arrayIN, round($amount + $contractAmountTemp));
                array_push($arrayFreeCashFlow, round($amount + $contractAmountTemp - $costs ));
                $date_start->add(new DateInterval('P10D'));

                $conn->close();
                $i++;



            }

            $response['response']="success";
            $response['arrayContracts']=$arrayContracts;
            $response['arrayOffers']=$arrayOffers;
            $response['arrayCosts']=$arrayCosts;
            $response['arrayFreeCashFlow']=$arrayFreeCashFlow;
            $response['totalIN']=$arrayIN;
            $response['arrayDates']=$arrayDates;





            echo json_encode($response);
            die;




        }else{
            if($id){
                include 'connexion.php';
                $sql="SELECT * FROM offers WHERE ID='$id'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }

                $result = mysqli_query($conn, $sql);
                $resultat = mysqli_fetch_assoc($result);
                $conn->close();

                $response['response']="success";
                $response['title']=$resultat['TITRE'];
                $response['description']=$resultat['DESCRIPTION'];
                $response['type']=$resultat['TYPE'];
                $response['probability']=$resultat['PROBABILITY'];
                $response['margin']=$resultat['MARGIN'];
                $response['amount']=$resultat['AMOUNT'];
                $response['date']=$resultat['DATE'];
                $response['start']=$resultat['START'];
                $response['end']=$resultat['END'];
                $response['status']=$resultat['STATUS'];
                $response['file']=$resultat['FILE_NAME'];

                include 'connexion.php';
                $sql="SELECT * FROM offers_details WHERE OFFER_ID='$id' AND STAANN != 'D'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }

                $result = mysqli_query($conn, $sql);
                $length = $result->num_rows;
                $conn->close();
                $response['itemsNumber']=$length;
                $i=0;
                while($row = mysqli_fetch_array($result)){
                    $response['item'][$i]['type']=$row['ITEM_TYPE'];
                    $response['item'][$i]['ID']=$row['ITEM_ID'];
                    $itemID=$row['ITEM_ID'];;
                    $response['item'][$i]['locationPrice']=$row['ITEM_LOCATION_PRICE'];
                    $response['item'][$i]['installationPrice']=$row['ITEM_INSTALLATION_PRICE'];


                    if($response['item'][$i]['type'] == 'box'){
                        include 'connexion.php';
                        $sql2="SELECT * FROM boxes_catalog WHERE ID='$itemID'";
                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $result2 = mysqli_query($conn, $sql2);
                        $resultat2=mysqli_fetch_assoc($result2);
                        $conn->close();
                        $response['item'][$i]['model']=$resultat2['MODEL'];
                    }else if($response['item'][$i]['type'] == 'bike'){
                        include 'connexion.php';
                        $sql2="SELECT * FROM bike_catalog WHERE ID='$itemID'";
                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $result2 = mysqli_query($conn, $sql2);
                        $resultat2=mysqli_fetch_assoc($result2);
                        $conn->close();
                        $response['item'][$i]['brand']=$resultat2['BRAND'];
                        $response['item'][$i]['model']=$resultat2['MODEL'];
                    }else if($response['item'][$i]['type'] == 'accessory'){
                        include 'connexion.php';
                        $sql2="SELECT * FROM accessories_catalog WHERE ID='$itemID'";
                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $result2 = mysqli_query($conn, $sql2);
                        $resultat2=mysqli_fetch_assoc($result2);
                        $conn->close();
                        $response['item'][$i]['brand']=$resultat2['BRAND'];
                        $response['item'][$i]['model']=$resultat2['MODEL'];
                    }

                    $i++;
                }



                echo json_encode($response);
                die;

            }
            else if($company){

                include 'connexion.php';
                $sql="SELECT bb.ID, aa.COMPANY, aa.BILLING_GROUP, aa.CONTRACT_START, aa.CONTRACT_END, ROUND(SUM(CASE WHEN BILLING_TYPE = 'annual' THEN LEASING_PRICE/12 ELSE LEASING_PRICE END)) as 'PRICE', COUNT(1) AS 'BIKE_NUMBER' FROM customer_bikes aa, companies bb WHERE aa.STAANN != 'D' and aa.COMPANY != 'KAMEO' AND aa.CONTRACT_TYPE IN ('leasing', 'location') and aa.COMPANY=bb.INTERNAL_REFERENCE and aa.BILLING_GROUP=bb.BILLING_GROUP";
                if($company!="*"){
                    $sql=$sql." AND COMPANY='$company'";
                }
                $sql=$sql." GROUP BY bb.ID, aa.COMPANY, aa.BILLING_GROUP, aa.CONTRACT_START, aa.CONTRACT_END";

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }


                $result = mysqli_query($conn, $sql);
                $conn->close();

                $response['contractsNumber'] = $result->num_rows;
                $i=0;
                while($row = mysqli_fetch_array($result))
                {

                    $response['response']="success";
                    $response['contract'][$i]['company']=$row['COMPANY'];
                    $response['contract'][$i]['companyID']=$row['ID'];
                    if($row['BIKE_NUMBER']>1){
                        $response['contract'][$i]['description']=$row['BIKE_NUMBER']." vélos en location";
                    }else{
                        $response['contract'][$i]['description']=$row['BIKE_NUMBER']." vélo en location";
                    }
                    $response['contract'][$i]['amount']=$row['PRICE'];
                    $response['contract'][$i]['start']=$row['CONTRACT_START'];
                    $response['contract'][$i]['end']=$row['CONTRACT_END'];
                    $i++;
                }

                include 'connexion.php';
                $sql="SELECT COMPANY, START, END, SUM(AMOUNT) as 'PRICE', COUNT(1) AS 'BOXES_NUMBER' FROM boxes WHERE STAANN != 'D' and COMPANY != 'KAMEO' and COMPANY!='KAMEO VELOS TEST'";
                if($company!="*"){
                    $sql=$sql." AND COMPANY='$company'";
                }
                $sql=$sql." GROUP BY COMPANY, START, END";

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }


                $result = mysqli_query($conn, $sql);
                $conn->close();

                $response['contractsNumber'] = $response['contractsNumber'] + $result->num_rows;

                while($row = mysqli_fetch_array($result))
                {

                    $response['response']="success";
                    $response['contract'][$i]['company']=$row['COMPANY'];
                    if($row['BOXES_NUMBER']>1){
                        $response['contract'][$i]['description']=$row['BOXES_NUMBER']." bornes en leasing";
                    }else{
                        $response['contract'][$i]['description']=$row['BOXES_NUMBER']." borne en leasing";
                    }
                    $response['contract'][$i]['amount']=$row['PRICE'];
                    $response['contract'][$i]['start']=$row['START'];
                    $response['contract'][$i]['end']=$row['END'];
                    $i++;
                }


                include 'connexion.php';
                $sql="SELECT SUM(CASE WHEN BILLING_TYPE = 'annual' THEN LEASING_PRICE/12 ELSE LEASING_PRICE END) as 'PRICE' FROM customer_bikes aa WHERE aa.STAANN != 'D' and aa.COMPANY != 'KAMEO' AND aa.CONTRACT_TYPE IN ('leasing', 'location') AND CONTRACT_START <= CURRENT_TIMESTAMP AND (CONTRACT_END > CURRENT_TIMESTAMP OR CONTRACT_END is NULL)";

                if($company!="*"){
                    $sql=$sql." AND COMPANY='$company'";
                }

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $resultat = mysqli_fetch_assoc($result);
                $conn->close();

                $response['sumContractsCurrent']=$resultat['PRICE'];

                include 'connexion.php';
                $sql="SELECT SUM(AMOUNT) as 'PRICE' FROM boxes WHERE START<CURRENT_TIMESTAMP AND (END > CURRENT_TIMESTAMP OR END is NULL) AND STAANN != 'D' and COMPANY != 'KAMEO' and COMPANY!='KAMEO VELOS TEST'";
                if($company!="*"){
                    $sql=$sql." AND COMPANY='$company'";
                }

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $resultat = mysqli_fetch_assoc($result);
                $conn->close();

                $response['sumContractsCurrent']+=$resultat['PRICE'];

                include 'connexion.php';
                $sql="SELECT * FROM offers WHERE STATUS='ongoing' AND STAANN != 'D'";
                if($company!="*"){
                    $sql=$sql." AND COMPANY='$company'";
                }

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $conn->close();

                $response['offersNumber'] = $result->num_rows;
                $i=0;
                while($row = mysqli_fetch_array($result))
                {

                    $response['response']="success";
                    $response['offer'][$i]['id']=$row['ID'];
                    $response['offer'][$i]['company']=$row['COMPANY'];
                    $response['offer'][$i]['type']=$row['TYPE'];
                    $response['offer'][$i]['title']=$row['TITRE'];
                    $response['offer'][$i]['amount']=$row['AMOUNT'];
                    $response['offer'][$i]['probability']=$row['PROBABILITY'];
                    $response['offer'][$i]['start']=$row['START'];
                    $response['offer'][$i]['end']=$row['END'];
                    $response['offer'][$i]['margin']=$row['MARGIN'];
                    $response['offer'][$i]['status']=$row['STATUS'];
                    $response['offer'][$i]['file']=$row['FILE_NAME'];
                    $i++;
                }


                /////////////////////


                include 'connexion.php';
                $sql="SELECT * FROM costs WHERE STAANN != 'D' AND (END>CURRENT_TIMESTAMP OR END IS NULL)";


                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $conn->close();

                $response['costsNumber'] = $result->num_rows;
                $i=0;
                while($row = mysqli_fetch_array($result))
                {

                    $response['response']="success";
                    $response['cost'][$i]['id']=$row['ID'];
                    $response['cost'][$i]['type']=$row['TYPE'];
                    $response['cost'][$i]['title']=$row['TITLE'];
                    $response['cost'][$i]['description']=$row['DESCRIPTION'];
                    $response['cost'][$i]['amount']=$row['AMOUNT'];
                    $response['cost'][$i]['start']=$row['START'];
                    $response['cost'][$i]['end']=$row['END'];
                    $i++;
                }
                echo json_encode($response);
                die;

            }else{
            errorMessage("ES0012");
            }
        }

    }else if($action=="delete"){
      $offerID=$_GET['offerID'];
      execSQL("DELETE FROM offers_details WHERE OFFER_ID=?", array('i', $offerID), true);
      execSQL("DELETE FROM offers WHERE ID=?", array('i', $offerID), true);
      successMessage("SM0003");
      die;
    }else{
        errorMessage("ES0012");
    }
}
else
{
    errorMessage("ES0012");
}
