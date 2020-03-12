<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';


if(isset($_POST['action'])){
    $action=isset($_POST['action']) ? $_POST['action'] : NULL;
    if($action=='add'){
        $model=$_POST['model'];
        $frameNumber=$_POST['frameNumber'];
        $size=$_POST['size'];
        $portfolioID=$_POST['portfolioID'];
        $buyingPrice=$_POST['price'];
        $buyingDate=$_POST['buyingDate'];
        $contractType=isset($_POST['contractType']) ? $_POST['contractType'] : NULL;
        $contractStart=isset($_POST['contractStart']) ? $_POST['contractStart'] : NULL;
        $contractEnd=isset($_POST['contractEnd']) ? $_POST['contractEnd'] : NULL;
        $sellPrice = isset($_POST['bikeSoldPrice']) ? $_POST['bikeSoldPrice'] : 0;

        error_log($sellPrice);

        $frameReference=$_POST['frameReference'];
        $billingPrice=$_POST['billingPrice'];
        $billingType=$_POST['billingType'];
        $billingGroup=$_POST['billingGroup'];
        $user=$_POST['user'];
        $company=$_POST['company'];
        $buildingInitialization=isset($_POST['firstBuilding']) ? $_POST['firstBuilding'] : NULL;

        if($buildingInitialization == NULL){
            errorMessage("ES0042");
        }

        $extensions = array('.jpg');
        $extension = strrchr($_FILES['picture']['name'], '.');
        if(!in_array($extension, $extensions))
        {
              errorMessage("ES0041");
        }


        $taille_maxi = 6291456;
        $taille = filesize($_FILES['picture']['tmp_name']);
        if($taille>$taille_maxi)
        {
              errorMessage("ES0023");
        }

        //upload of Bike picture

        $dossier = '../images_bikes/';



        $fichier = $frameNumber.$extension;

         if(move_uploaded_file($_FILES['picture']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
         {
            $upload=true;
            $path= $dossier . $fichier;
         }
         else
         {
              errorMessage("ES0024");
         }

        copy($dossier . $fichier, $dossier . $frameNumber."_big".$extension);
        copy($dossier . $fichier, $dossier . $frameNumber."_mini".$extension);
        $img = resize_image($dossier . $frameNumber.$extension, 800, 800);
        imagejpeg($img, $dossier. $frameNumber.$extension);
        $img = resize_image($dossier . $frameNumber.$extension, 100, 100);
        imagejpeg($img, $dossier. $frameNumber."_mini".$extension);


        if($model != NULL && $frameNumber != NULL && $size != NULL && $frameReference != NULL && $user != NULL && $company != NULL && $buildingInitialization != NULL){
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

            $sql="select * from building_access where COMPANY='$company'";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            if($result->num_rows=='0'){
                $conn->close();
                errorMessage("ES0036");
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

            $sql= "INSERT INTO  customer_bikes (USR_MAJ, HEU_MAJ, FRAME_NUMBER, TYPE, SIZE, CONTRACT_TYPE, CONTRACT_START, CONTRACT_END, COMPANY, MODEL, FRAME_REFERENCE, AUTOMATIC_BILLING, BILLING_TYPE, LEASING_PRICE, STATUS, INSURANCE, BILLING_GROUP, BIKE_PRICE, BIKE_BUYING_DATE, STAANN, SOLD_PRICE) VALUES ('$user', CURRENT_TIMESTAMP, '$frameNumber', '$portfolioID', '$size', '$contractType', $contractStart, $contractEnd, '$company', '$model', '$frameReference', '$automaticBilling', '$billingType', $billingPrice, 'OK', '$insurance', '$billingGroup', '$buyingPrice', '$buyingDate', '','$sellPrice')";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $sql= "INSERT INTO  reservations (USR_MAJ, HEU_MAJ, FRAME_NUMBER, DATE_START, BUILDING_START, DATE_END, BUILDING_END, EMAIL, STATUS, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$frameNumber', '0', '$buildingInitialization', '0', '$buildingInitialization', '$user', 'Closed','')";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            if(isset($_POST['buildingAccess'])){
                foreach($_POST['buildingAccess'] as $valueInArray){
                    $sql= "INSERT INTO  bike_building_access (USR_MAJ, TIMESTAMP, BUILDING_CODE, BIKE_NUMBER, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$frameNumber', '')";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                }
            }

            if(isset($_POST['userAccess'])){
                foreach($_POST['userAccess'] as $valueInArray){
                    $sql= "INSERT INTO  customer_bike_access (USR_MAJ, TIMESTAMP, EMAIL, BIKE_NUMBER, TYPE, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$frameNumber', 'partage', '')";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                }
            }


            $conn->close();

            successMessage("SM0015");
        }else{
            errorMessage("ES0025");
        }


    }else if($action=='update'){
        $model=$_POST['model'];
        $frameNumberOriginel=$_POST['frameNumberOriginel'];
        $frameNumber=$_POST['frameNumber'];
        $size=$_POST['size'];
        $portfolioID=$_POST['portfolioID'];
        $buyingPrice=$_POST['price'];
        $buyingDate=$_POST['buyingDate'];
        $contractType=isset($_POST['contractType']) ? $_POST['contractType'] : NULL;
        $contractStart=isset($_POST['contractStart']) ? $_POST['contractStart'] : NULL;
        $contractEnd=isset($_POST['contractEnd']) ? $_POST['contractEnd'] : NULL;
        $sellPrice = isset($_POST['bikeSoldPrice']) ? $_POST['bikeSoldPrice'] : 0;
        error_log($sellPrice);

        $frameReference=$_POST['frameReference'];
        $billingPrice=$_POST['billingPrice'];
        $billingType=$_POST['billingType'];
        $billingGroup=$_POST['billingGroup'];
        $user=$_POST['user'];
        $company=$_POST['company'];

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
        if($buyingDate!=NULL){
            $buyingDate="'".$buyingDate."'";
        }else{
            $buyingDate='NULL';
        }

        if($billingPrice!=NULL){
            $billingPrice="'".$billingPrice."'";
        }else{
            $billingPrice='NULL';
        }



        if(substr($frameNumber, 0, 3) != substr($company, 0, 3)){
            errorMessage("ES0051");
        }


        if(isset($_FILES['picture'])){

            $extensions = array('.jpg');
            $extension = strrchr($_FILES['picture']['name'], '.');
            if(!in_array($extension, $extensions))
            {
                  errorMessage("ES0041");
            }


            $taille_maxi = 6291456;
            $taille = filesize($_FILES['picture']['tmp_name']);
            if($taille>$taille_maxi)
            {
                  errorMessage("ES0023");
            }

            //upload of Bike picture

            $dossier = '../images_bikes/';

            $fichier=$frameNumber.$extension;
            unlink($dossier.$fichier) or die("Couldn't delete file");

            $fichierMini=$frameNumber."_mini".$extension;
            unlink($dossier.$fichierMini) or die("Couldn't delete file");


            $fichier=$frameNumber.$extension;

             if(move_uploaded_file($_FILES['picture']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
             {
                $upload=true;
                $path= $dossier . $fichier;
             }
             else
             {
                  errorMessage("ES0024");
             }

            $img = resize_image($dossier . $fichier, 200, 200);
            $fichierMini=$frameNumber."_mini".$extension;
            imagejpeg($img, $dossier . $fichierMini);
        }

        $response=array();
        if($frameNumber != NULL && $user != NULL)
        {
            if($frameNumberOriginel != $frameNumber){

                include'connexion.php';
                $sql="update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', FRAME_NUMBER='$frameNumber' where FRAME_NUMBER = '$frameNumberOriginel'";

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }

                $conn->close();

                $dossier = '../images_bikes/';
                $fichier= $frameNumberOriginel.'.jpg';

                copy($dossier . $fichier, $dossier . $frameNumber.'.jpg');

                $fichier= $frameNumberOriginel.'_mini.jpg';

                copy($dossier . $fichier, $dossier . $frameNumber.'_mini.jpg');




                include'connexion.php';
                $sql="update customer_bike_access set TIMESTAMP = CURRENT_TIMESTAMP, USR_MAJ='$user', BIKE_NUMBER='$frameNumber' where BIKE_NUMBER = '$frameNumberOriginel'";

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }

                $conn->close();


                include'connexion.php';
                $sql="update bike_building_access set TIMESTAMP = CURRENT_TIMESTAMP, USR_MAJ='$user', BIKE_NUMBER='$frameNumber' where BIKE_NUMBER = '$frameNumberOriginel'";

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }

                $conn->close();



            }

            include 'connexion.php';

            $sql="update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', MODEL='$model', TYPE='$portfolioID', SIZE='$size', CONTRACT_TYPE='$contractType', CONTRACT_START=$contractStart, CONTRACT_END=$contractEnd, COMPANY='$company', FRAME_REFERENCE='$frameReference', AUTOMATIC_BILLING='$automaticBilling', INSURANCE='$insurance', BILLING_TYPE='$billingType', LEASING_PRICE=$billingPrice, BILLING_GROUP='$billingGroup', BIKE_PRICE='$buyingPrice', BIKE_BUYING_DATE=$buyingDate, SOLD_PRICE = $sellPrice where FRAME_NUMBER = '$frameNumber'";

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
        $sql= "SELECT * FROM customer_bikes WHERE FRAME_NUMBER='$frameNumber' and COMPANY='$company'";

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
            $sql= "UPDATE customer_bikes SET COMPANY='$company' WHERE FRAME_NUMBER='$frameNumber'";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();
        }


        if(isset($_POST['buildingAccess'])){

            include 'connexion.php';
            $sql= "SELECT * FROM bike_building_access WHERE BIKE_NUMBER='$frameNumber' AND STAANN != 'D'";
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
                    $sql="update bike_building_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BUILDING_CODE = '$buildingCode' and BIKE_NUMBER='$frameNumber'";

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
                $sql="select * FROM bike_building_access WHERE BUILDING_CODE='$valueInArray' and BIKE_NUMBER='$frameNumber'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $length=$result->num_rows;

                if($length==0){
                    $sql= "INSERT INTO  bike_building_access (USR_MAJ, TIMESTAMP, BUILDING_CODE, BIKE_NUMBER, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$frameNumber', '')";
                }else{
                    $sql="select * FROM bike_building_access WHERE BUILDING_CODE='$valueInArray' and BIKE_NUMBER='$frameNumber' and STAANN = 'D'";
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
                    $sql="update bike_building_access SET STAANN='' WHERE BUILDING_CODE='$valueInArray' and BIKE_NUMBER='$frameNumber'";
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
            $sql="update bike_building_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BIKE_NUMBER='$frameNumber' and STAANN != 'D'";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();
        }

        if(isset($_POST['userAccess'])){

            include 'connexion.php';
            $sql= "SELECT * FROM customer_bike_access WHERE BIKE_NUMBER='$frameNumber' AND STAANN != 'D'";
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
                    $sql="update customer_bike_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = '$emailUser' and BIKE_NUMBER='$frameNumber'";

                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();
                }
            }


            foreach($_POST['userAccess'] as $valueInArray){
                include 'connexion.php';
                $sql="select * FROM customer_bike_access WHERE EMAIL='$valueInArray' and BIKE_NUMBER='$frameNumber'";

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
                    $sql= "INSERT INTO  customer_bike_access (USR_MAJ, TIMESTAMP, EMAIL, BIKE_NUMBER, TYPE, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$frameNumber', 'partage', '')";
                }else{
                    $sql="select * FROM customer_bike_access WHERE EMAIL='$valueInArray' and BIKE_NUMBER='$frameNumber' and STAANN = 'D'";
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
                    $sql="update customer_bike_access SET USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP, STAANN='' WHERE EMAIL='$valueInArray' and BIKE_NUMBER='$frameNumber'";
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
            $sql="update customer_bike_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BIKE_NUMBER='$frameNumber' and STAANN != 'D'";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();

        }
        successMessage("SM0003");

    }


    if($action=="delete"){
        $frameNumber=$_POST['frameNumber'];

        if($frameNumber != NULL){
            include 'connexion.php';
            $sql="UPDATE customer_bikes SET STAANN = 'D' WHERE FRAME_NUMBER = '$frameNumber'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $sql="DELETE FROM customer_bike_access WHERE BIKE_NUMBER = '$frameNumber'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $sql="DELETE FROM bike_building_access WHERE BIKE_NUMBER = '$frameNumber'";
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
