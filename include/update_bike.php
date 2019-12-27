<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


$action=$_POST['action'];
$user=$_POST['user'];


if($action=="delete"){
    $frameNumber=$_POST['frameNumber'];

    if($user != NULL && $frameNumber != NULL){
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


if($action=="update"){
    
    
    $frameNumber=$_POST['widget-updateBikeStatusAdmin-form-bikeNumber'];
    $frameNumberOriginel=$_POST['widget-updateBikeStatusAdmin-form-bikeNumberOriginel'];
    $model=$_POST['widget-updateBikeStatusAdmin-form-model'];
    $size=isset($_POST['widget-updateBikeStatusAdmin-form-size']) ? $_POST['widget-updateBikeStatusAdmin-form-size'] : NULL;
    $frameReference=isset($_POST['widget-updateBikeStatusAdmin-form-frameReference']) ? $_POST['widget-updateBikeStatusAdmin-form-frameReference'] : NULL;
    $brand=isset($_POST['brand']) ? $_POST['brand'] : NULL;
    $price=isset($_POST['widget-updateBikeStatusAdmin-form-price']) ? $_POST['widget-updateBikeStatusAdmin-form-price'] : '0';
    $buyingDate=isset($_POST['widget-updateBikeStatusAdmin-form-buyingDate']) ? $_POST['widget-updateBikeStatusAdmin-form-buyingDate'] : NULL;
    $company=isset($_POST['widget-updateBikeStatusAdmin-form-company']) ? $_POST['widget-updateBikeStatusAdmin-form-company'] : NULL;
    $contractStart=isset($_POST['widget-updateBikeStatusAdmin-form-contractStart']) ? date($_POST['widget-updateBikeStatusAdmin-form-contractStart']) : NULL;
    $contractEnd=isset($_POST['widget-updateBikeStatusAdmin-form-contractEnd']) ? date($_POST['widget-updateBikeStatusAdmin-form-contractEnd']) : NULL;
    $assistanceReference=isset($_POST['widget-updateBikeStatusAdmin-form-assistanceReference']) ? $_POST['widget-updateBikeStatusAdmin-form-assistanceReference'] : NULL;
    $billing=isset($_POST['widget-updateBikeStatusAdmin-form-billing']) ? 'Y' : 'N';
    $billingPrice=isset($_POST['widget-updateBikeStatusAdmin-form-billingPrice']) ? $_POST['widget-updateBikeStatusAdmin-form-billingPrice'] : NULL;
    $billingGroup=isset($_POST['widget-updateBikeStatusAdmin-form-billingGroup']) ? $_POST['widget-updateBikeStatusAdmin-form-billingGroup'] : NULL;

    
    if(substr($frameNumber, 0, 3) != substr($company, 0, 3)){
        errorMessage("ES0051");
    }
    

    if(isset($_FILES['widget-updateBikeStatusAdmin-form-picture'])){

        $extensions = array('.jpg');
        $extension = strrchr($_FILES['widget-updateBikeStatusAdmin-form-picture']['name'], '.');
        if(!in_array($extension, $extensions))
        {
              errorMessage("ES0041");
        }


        $taille_maxi = 6291456;
        $taille = filesize($_FILES['widget-updateBikeStatusAdmin-form-picture']['tmp_name']);
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

         if(move_uploaded_file($_FILES['widget-updateBikeStatusAdmin-form-picture']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
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
        
        if($brand!=NULL){
            $brand="'".$brand."'";
        }else{
            $brand='NULL';
        }        
        
        
        $sql="update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', BRAND=$brand, MODEL='$model', SIZE='$size', CONTRACT_START=$contractStart, CONTRACT_END=$contractEnd, CONTRACT_REFERENCE='$assistanceReference', COMPANY='$company', FRAME_REFERENCE='$frameReference', AUTOMATIC_BILLING='$billing', LEASING_PRICE='$billingPrice', BILLING_GROUP='$billingGroup', BIKE_PRICE='$price', BIKE_BUYING_DATE=$buyingDate where FRAME_NUMBER = '$frameNumber'";

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
?>