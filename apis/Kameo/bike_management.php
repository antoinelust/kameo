<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';


function plan_maintenances($start, $end)
{
    $maintenances = array();
    $date = date('Y-m-d', strtotime("+3 months", strtotime($start)));
    while ($date <= $end) {
        $maintenances[] = $date;
        $date = date('Y-m-d', strtotime("+9 months", strtotime($date)));
    }
    return $maintenances;
}


if(isset($_POST['action'])){
    $action=isset($_POST['action']) ? $_POST['action'] : NULL;



    if($action=='add'){
        $model=$_POST['model'];
        $frameNumber=$_POST['frameNumber'];
        $size=$_POST['size'];
        $color=isset($_POST['color']) ? $_POST['color'] : NULL;

        $portfolioID=$_POST['portfolioID'];
        $buyingPrice=$_POST['price'];
        $frameReference=$_POST['frameReference'];
        $lockerReference=$_POST['lockerReference'];
        $gpsID=$_POST['gpsID'];
        $user=$_POST['user'];
        $company=$_POST['company'];
        $buyingDate=isset($_POST['orderingDate']) ? $_POST['orderingDate'] : NULL;
        $deliveryDate=isset($_POST['deliveryDate']) ? $_POST['deliveryDate'] : NULL;
        $orderNumber=isset($_POST['orderNumber']) ? $_POST['orderNumber'] : NULL;
        $offerReference=isset($_POST['offerReference']) ? $_POST['offerReference'] : NULL;
        $clientReference=isset($_POST['clientReference']) ? $_POST['clientReference'] : NULL;

        $contractType=isset($_POST['contractType']) ? $_POST['contractType'] : NULL;

        if($contractType=="stock" && $company != 'KAMEO'){
            errorMessage("ES0060");
        }


        if($model != NULL && $size != NULL && $frameReference != NULL && $user != NULL && $company != NULL){

            if($frameNumber != NULL){
                include 'connexion.php';
                $sql="select * from customer_bikes where FRAME_NUMBER='$frameNumber'";

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                if($result->num_rows!='0'){
                    $conn->close();
                    errorMessage("ES0036");
                }
                $conn->close();
            }




            if($contractType=="order"){
                $billingPrice=0;
                $billingType=NULL;
                $billingGroup=1;
                $contractStart=NULL;
                $contractEnd=NULL;
                $buildingInitialization=NULL;
                $billingType=NULL;
                $_POST['userAccess']=NULL;
                $_POST['buildingAccess']=NULL;
                $sellPrice=0;


            }else{
                if($contractType=="selling"){
                    $sellPrice = isset($_POST['bikeSoldPrice']) ? $_POST['bikeSoldPrice'] : 0;
                    $buildingInitialization=NULL;

                }else{
                    $sellPrice = 0;
                    $buildingInitialization=isset($_POST['firstBuilding']) ? $_POST['firstBuilding'] : NULL;
                }
                $billingPrice=isset($_POST['billingPrice']) ? $_POST['billingPrice'] : NULL;
                $billingType=isset($_POST['billingType']) ? $_POST['billingType'] : NULL;
                $billingGroup=isset($_POST['billingGroup']) ? $_POST['billingGroup'] : 1;
                $contractStart=isset($_POST['contractStart']) ? $_POST['contractStart'] : NULL;
                $contractEnd=isset($_POST['contractEnd']) ? $_POST['contractEnd'] : NULL;
                if($billingType=='paid'){
                    $contractEnd=NULL;
                    $billingPrice=NULL;
                    $_POST['userAccess']=NULL;
                    $_POST['buildingAccess']=NULL;
                    $billingGroup='0';
                }

            }



            if($contractStart!=NULL){
                $contractStart="'".$contractStart."'";
            }else{
                $contractStart='NULL';
            }

            if($contractEnd!=NULL){
                $contractEnd="'".$contractEnd."'";
            }else{
                $contractEnd='NULL';
            }

            if($color !=NULL){
                $color="'".$color."'";
            }else{
                $color='NULL';
            }
            if($billingPrice!=NULL){
                $billingPrice="'".$billingPrice."'";
            }else{
                $billingPrice='NULL';
            }
            if($lockerReference!=NULL){
                $lockerReference="'".$lockerReference."'";
            }else{
                $lockerReference='NULL';
            }
            if($gpsID!=NULL){
                $gpsID="'".$gpsID."'";
            }else{
                $gpsID='NULL';
            }
            if($offerReference!=NULL){
                $offerReference="'".$offerReference."'";
            }else{
                $offerReference='NULL';
            }
            if($clientReference!=NULL){
                $clientReference="'".$clientReference."'";
            }else{
                $clientReference='NULL';
            }
            if(isset($_POST['billing'])){
                $automaticBilling="Y";
            }else{
                $automaticBilling="N";
            }

            if(isset($_POST['insurance'])){
                $insurance="Y";
            }else{
                $insurance="N";
            }

            include 'connexion.php';
            $sql= "INSERT INTO  customer_bikes (USR_MAJ, HEU_MAJ, FRAME_NUMBER, TYPE, SIZE, COLOR, CONTRACT_TYPE, CONTRACT_START, CONTRACT_END, COMPANY, MODEL, FRAME_REFERENCE, LOCKER_REFERENCE, GPS_ID, AUTOMATIC_BILLING, BILLING_TYPE, LEASING_PRICE, STATUS, INSURANCE, BILLING_GROUP, BIKE_PRICE, BIKE_BUYING_DATE, STAANN, SOLD_PRICE, DELIVERY_DATE, ORDER_NUMBER, OFFER_ID, EMAIL) VALUES ('$user', CURRENT_TIMESTAMP, '$frameNumber', '$portfolioID', '$size', $color, '$contractType', $contractStart, $contractEnd, '$company', '$model', '$frameReference', $lockerReference, $gpsID, '$automaticBilling', '$billingType', $billingPrice, 'OK', '$insurance', '$billingGroup', '$buyingPrice', '$buyingDate', '','$sellPrice', '$deliveryDate', '$orderNumber', $offerReference, $clientReference)";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $bikeID = $conn->insert_id;
            $conn->close();

            $dossier = '../images_bikes/';
            $fichier = $bikeID.".jpg";
            $fichierMini = $bikeID."_mini.jpg";

            if($buildingInitialization){
                $sql= "INSERT INTO  reservations (USR_MAJ, HEU_MAJ, BIKE_ID, DATE_START, BUILDING_START, DATE_END, BUILDING_END, EMAIL, STATUS, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$bikeID', '0', '$buildingInitialization', '0', '$buildingInitialization', '$user', 'Closed','')";

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
            }

            if(isset($_POST['buildingAccess'])){
                foreach($_POST['buildingAccess'] as $valueInArray){
                    $sql= "INSERT INTO  bike_building_access (USR_MAJ, TIMESTAMP, BUILDING_CODE, BIKE_ID, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$bikeID', '')";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                }
            }

            if(isset($_POST['userAccess'])){
                $type_bike = $_POST['bikeType'];
                foreach($_POST['userAccess'] as $valueInArray){
                    $sql= "INSERT INTO  customer_bike_access (USR_MAJ, TIMESTAMP, EMAIL, BIKE_ID, TYPE, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$bikeID', '$type_bike', '')";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                }
            }
            successMessage("SM0015");
        }else{
            errorMessage("ES0025");
        }


    }else if($action=='update'){

        $user=$_POST['user'];
        $company=$_POST['company'];
        $bikeID=isset($_POST['bikeID']) ? $_POST['bikeID'] : NULL;

        $model=$_POST['model'];
        $frameNumberOriginel=$_POST['frameNumberOriginel'];
        $frameNumber=$_POST['frameNumber'];
        $size=$_POST['size'];
        $color=isset($_POST['color']) ? $_POST['color'] : NULL;
        $portfolioID=$_POST['portfolioID'];
        $frameReference=$_POST['frameReference'];
        $lockerReference=$_POST['lockerReference'];
        $gpsID=$_POST['gpsID'];
        $type_bike = $_POST['bikeType'];

        $buyingPrice=isset($_POST['price']) ? $_POST['price'] : NULL;
        $buyingDate=isset($_POST['buyingDate']) ? $_POST['buyingDate'] : NULL;
        $contractType=isset($_POST['contractType']) ? $_POST['contractType'] : NULL;
        $contractStart=isset($_POST['contractStart']) ? $_POST['contractStart'] : NULL;
        $contractEnd=isset($_POST['contractEnd']) ? $_POST['contractEnd'] : NULL;
        $sellPrice = isset($_POST['bikeSoldPrice']) ? $_POST['bikeSoldPrice'] : 0;
        $orderingDate=isset($_POST['orderingDate']) ? $_POST['orderingDate'] : NULL;
        $estimatedDeliveryDate=isset($_POST['estimatedDeliveryDate']) ? $_POST['estimatedDeliveryDate'] : NULL;
        $deliveryDate=isset($_POST['deliveryDate']) ? $_POST['deliveryDate'] : NULL;
        $orderNumber=isset($_POST['orderNumber']) ? $_POST['orderNumber'] : NULL;
        $offerReference=isset($_POST['offerReference']) ? $_POST['offerReference'] : NULL;
        $clientReference=isset($_POST['clientReference']) ? $_POST['clientReference'] : NULL;
        $billingPrice=isset($_POST['billingPrice']) ? $_POST['billingPrice'] : NULL;
        $billingType=isset($_POST['billingType']) ? $_POST['billingType'] : NULL;
        $billingGroup=isset($_POST['billingGroup']) ? $_POST['billingGroup'] : NULL;

        if($contractType=="stock" && $company != 'KAMEO'){
            errorMessage("ES0060");
        }


        if(isset($_POST['billing'])){
            $automaticBilling="Y";
        }else{
            $automaticBilling="N";
        }
        if(isset($_POST['insurance'])){
            $insurance="Y";
        }else{
            $insurance="N";
        }


        if($color!=NULL && $color != 'null' && $color != '' && $color != 'NULL'){
            $color="'".$color."'";
        }else{
            $color='NULL';
        }

        if($contractStart!=NULL){
            $contractStart="'".$contractStart."'";
        }else{
            $contractStart='NULL';
        }
        if($contractEnd!=NULL){
            $contractEnd="'".$contractEnd."'";
        }else{
            $contractEnd='NULL';
        }

        if($billingPrice!=NULL){
            $billingPrice="'".$billingPrice."'";
        }else{
            $billingPrice='NULL';
        }
        if($lockerReference!=NULL){
            $lockerReference="'".$lockerReference."'";
        }else{
            $lockerReference='NULL';
        }
        if($gpsID!=NULL){
            $gpsID="'".$gpsID."'";
        }else{
            $gpsID='NULL';
        }
        if($offerReference!=NULL){
            $offerReference="'".$offerReference."'";
        }else{
            $offerReference='NULL';
        }
        if($clientReference!=NULL){
            $clientReference="'".$clientReference."'";
        }else{
            $clientReference='NULL';
        }
        if($estimatedDeliveryDate!=NULL){
            $estimatedDeliveryDate="'".$estimatedDeliveryDate."'";
        }else{
            $estimatedDeliveryDate='NULL';
        }
        if($deliveryDate!=NULL){
            $deliveryDate="'".$deliveryDate."'";
        }else{
            $deliveryDate='NULL';
        }

        $response=array();
        if($bikeID != NULL && $user != NULL)
        {
            if($frameNumberOriginel != $frameNumber){

                include 'connexion.php';
                $sql="update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', FRAME_NUMBER='$frameNumber' where ID = '$bikeID'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }

                $conn->close();
            }

            include 'connexion.php';

            if($contractType=="order"){
                $sql="update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', MODEL='$model', TYPE='$portfolioID', SIZE='$size', COLOR=$color,  CONTRACT_TYPE='$contractType', COMPANY='$company', FRAME_REFERENCE='$frameReference', LOCKER_REFERENCE=$lockerReference, GPS_ID=$gpsID, BIKE_BUYING_DATE='$orderingDate', ESTIMATED_DELIVERY_DATE=$estimatedDeliveryDate, DELIVERY_DATE=$deliveryDate, ORDER_NUMBER='$orderNumber', OFFER_ID=$offerReference, EMAIL=$clientReference where ID = '$bikeID'";
            }else{
                $sql="update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', MODEL='$model', TYPE='$portfolioID', SIZE='$size', COLOR=$color, CONTRACT_TYPE='$contractType', CONTRACT_START=$contractStart, CONTRACT_END=$contractEnd, COMPANY='$company', FRAME_REFERENCE='$frameReference', LOCKER_REFERENCE=$lockerReference, GPS_ID=$gpsID, AUTOMATIC_BILLING='$automaticBilling', INSURANCE='$insurance', BILLING_TYPE='$billingType', LEASING_PRICE=$billingPrice, BILLING_GROUP='$billingGroup', BIKE_PRICE='$buyingPrice', SOLD_PRICE = $sellPrice, EMAIL=$clientReference where ID = '$bikeID'";
            }

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $conn->close();

        }
        else
        {
            errorMessage("ES0012");
        }

        include 'connexion.php';
        $sql= "SELECT * FROM customer_bikes WHERE ID='$bikeID' and COMPANY='$company'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $length = $result->num_rows;
        $conn->close();

        if($length==0){
            include 'connexion.php';
            $sql= "UPDATE customer_bikes SET COMPANY='$company' WHERE ID='$bikeID'";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();
        }


        if(isset($_POST['buildingAccess'])){

            include 'connexion.php';
            $sql= "SELECT * FROM bike_building_access WHERE BIKE_ID='$bikeID' AND STAANN != 'D'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $length = $result->num_rows;
            $conn->close();

            while($row = mysqli_fetch_array($result)){
                $presence=false;
                foreach($_POST['buildingAccess'] as $valueInArray){
                    if($row['BUILDING_CODE']==$valueInArray){
                        $presence=true;
                    }
                }
                $buildingCode=$row['BUILDING_CODE'];

                if($presence==false){
                    include 'connexion.php';
                    $sql="update bike_building_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BUILDING_CODE = '$buildingCode' and BIKE_ID='$bikeID'";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();
                }
            }



            foreach($_POST['buildingAccess'] as $valueInArray){
                include 'connexion.php';
                $sql="select * FROM bike_building_access WHERE BUILDING_CODE='$valueInArray' and BIKE_ID='$bikeID'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $length=$result->num_rows;

                if($length==0){
                    $sql= "INSERT INTO  bike_building_access (USR_MAJ, TIMESTAMP, BUILDING_CODE, BIKE_ID, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$bikeID', '')";
                }else{
                    $sql="select * FROM bike_building_access WHERE BUILDING_CODE='$valueInArray' and BIKE_ID='$bikeID' and STAANN = 'D'";
                }

                include 'connexion.php';
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $conn->close();

                if($length==1){
                    include 'connexion.php';
                    $sql="update bike_building_access SET STAANN='' WHERE BUILDING_CODE='$valueInArray' and BIKE_ID='$bikeID'";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();

                }
            }

        }else{
            include 'connexion.php';
            $sql="update bike_building_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BIKE_ID='$bikeID' and STAANN != 'D'";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();
        }

        if(isset($_POST['userAccess'])){

            include 'connexion.php';
            $sql= "SELECT * FROM customer_bike_access WHERE ID='$bikeID' AND STAANN != 'D'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $length = $result->num_rows;
            $conn->close();

            while($row = mysqli_fetch_array($result)){
                $presence=false;
                foreach($_POST['userAccess'] as $valueInArray){
                    if($row['EMAIL']==$valueInArray){
                        $presence=true;
                    }
                }
                $emailUser=$row['EMAIL'];


                if($presence==false){
                    include 'connexion.php';
                    $sql="update customer_bike_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = '$emailUser' and BIKE_ID='$bikeID'";

                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();
                }
            }



            if( $type_bike == 'partage'){
                foreach($_POST['userAccess'] as $valueInArray){
                    include 'connexion.php';
                    $sql="select * FROM customer_bike_access WHERE EMAIL='$valueInArray' and BIKE_ID='$bikeID'";

                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $result = mysqli_query($conn, $sql);
                    $length=$result->num_rows;
                    $conn->close();

                    include 'connexion.php';

                    if($length==0){
                        $sql= "INSERT INTO  customer_bike_access (USR_MAJ, TIMESTAMP, EMAIL, BIKE_ID, TYPE, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$bikeID', '$type_bike', '')";
                    }else{
                        $sql="select * FROM customer_bike_access WHERE EMAIL='$valueInArray' and BIKE_ID='$bikeID' and STAANN = 'D'";
                    }

                    include 'connexion.php';
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();
                    if($length==1){
                        include 'connexion.php';
                        $sql="update customer_bike_access SET USR_MAJ='$user', TYPE='$type_bike', TIMESTAMP=CURRENT_TIMESTAMP, STAANN='' WHERE EMAIL='$valueInArray' and BIKE_ID='$bikeID'";
                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $conn->close();
                    }

                }
            }
        }else{
            include 'connexion.php';
            $sql="update customer_bike_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BIKE_ID='$bikeID' and STAANN != 'D'";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();

        }

        if(isset($_POST['bikeType']) && $_POST['bikeType'] == "personnel"){
            include 'connexion.php';
            $sql="DELETE FROM customer_bike_access WHERE BIKE_ID = '$bikeID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $email = $_POST['email'];

            $sql="INSERT INTO customer_bike_access (TIMESTAMP, USR_MAJ, EMAIL, BIKE_ID, TYPE, STAANN) VALUES (CURRENT_TIMESTAMP, '$user', '$email', '$bikeID', '$type_bike', '')";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
        }

        if(isset($_POST['contractStart']) && isset($_POST['contractEnd'])){

            include 'connexion.php';

            if(isset($_POST['contractType'])){
                if ($_POST['contractType'] == 'leasing'){
                    $dates = plan_maintenances($_POST['contractStart'], $_POST['contractEnd']);

                    for ($i=0; $i < sizeof($dates); $i++) {
                        $next_date = $dates[$i];
                        $num_m = array_search($next_date, $dates) + 1;

                        $sql="INSERT INTO entretiens (HEU_MAJ, USR_MAJ, BIKE_ID, DATE, STATUS, NR_ENTR)
                        SELECT CURRENT_TIMESTAMP, '$user', '$bikeID', '$next_date', 'AUTOMATICALY_PLANNED', '$num_m'
                        FROM DUAL WHERE NOT EXISTS (SELECT * FROM entretiens WHERE BIKE_ID = '$bikeID' AND DATE(DATE + INTERVAL 3 MONTH) >= '$dates[$i]')";

                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                    }
                }
                if ($_POST['contractType'] == 'selling') {
                    $date = date('Y-m-d', strtotime("+3 months", strtotime($_POST['contractStart'])));
                    $sql="INSERT INTO entretiens (HEU_MAJ, USR_MAJ, BIKE_ID, DATE, STATUS, NR_ENTR)
                    VALUES (CURRENT_TIMESTAMP, '$user', '$bikeID', '$date', 'AUTOMATICALY_PLANNED', 1)";

                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                }

            }

            $conn->close();
        }

        successMessage("SM0003");

    }


    if($action=="delete"){
        $bikeID=$_POST['bikeID'];

        if($bikeID != NULL){
            include 'connexion.php';
            $sql="UPDATE customer_bikes SET HEU_MAJ=CURRENT_TIMESTAMP, STAANN = 'D' WHERE ID = '$bikeID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $sql="DELETE FROM customer_bike_access WHERE BIKE_ID = '$bikeID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $sql="DELETE FROM bike_building_access WHERE BIKE_ID = '$bikeID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();
            successMessage("SM0018");
        }else{
            errorMessage("ES0012");
        }
    }



}else{
    errorMessage("ES0012");
}


?>
