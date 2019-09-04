<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';

$model=$_POST['widget-addBike-form-model'];
$frameNumber=$_POST['widget-addBike-form-frameNumber'];
$size=$_POST['widget-addBike-form-size'];
$contractStart=$_POST['widget-addBike-form-contractStart'];
$contractEnd=$_POST['widget-addBike-form-contractEnd'];
$contractReference=$_POST['widget-addBike-form-contractReference'];
$frameReference=$_POST['widget-addBike-form-frameReference'];
$billingPrice=$_POST['widget-addBike-form-billingPrice'];
$billingGroup=$_POST['widget-addBike-form-billingGroup'];
$user=$_POST['widget-addBike-form-user'];
$company=$_POST['widget-addBike-form-company'];
$buildingInitialization=$_POST['widget-addBike-form-firstBuilding'];

$extensions = array('.jpg');
$extension = strrchr($_FILES['widget-addBike-form-picture']['name'], '.');
if(!in_array($extension, $extensions))
{
      errorMessage("ES0041");
}


$taille_maxi = 6291456;
$taille = filesize($_FILES['widget-addBike-form-picture']['tmp_name']);
if($taille>$taille_maxi)
{
      errorMessage("ES0023");
}

//upload of Bike picture

$dossier = '../images_bikes/';



$fichier = $frameNumber.$extension;

 if(move_uploaded_file($_FILES['widget-addBike-form-picture']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
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


if($model != NULL && $frameNumber != NULL && $size != NULL && $contractStart != NULL && $contractEnd != NULL && $contractReference != NULL && $frameReference != NULL && $billingPrice != NULL && $billingGroup != NULL && $user != NULL && $company != NULL && $buildingInitialization != NULL){
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
    
    if(isset($_POST['widget-addBike-form-billing'])){
        $automaticBilling="Y";
    }else{
        $automaticBilling="N";
    }

    $sql= "INSERT INTO  customer_bikes (USR_MAJ, HEU_MAJ, FRAME_NUMBER, TYPE, SIZE, CONTRACT_START, CONTRACT_END, CONTRACT_REFERENCE, COMPANY, MODEL, FRAME_REFERENCE, LEASING, LEASING_PRICE, STATUS, BILLING_GROUP) VALUES ('$user', CURRENT_TIMESTAMP, '$frameNumber', '0', '$size', '$contractStart', '$contractEnd', '$contractReference', '$company', '$model', '$frameReference', '$automaticBilling', '$billingPrice', 'OK', '$billingGroup')";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;   
    }

    $sql= "INSERT INTO  reservations (USR_MAJ, HEU_MAJ, FRAME_NUMBER, DATE_START, BUILDING_START, DATE_END, BUILDING_END, EMAIL, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$frameNumber', '0', '$buildingInitialization', '0', '$buildingInitialization', '$user', '')";

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

?>