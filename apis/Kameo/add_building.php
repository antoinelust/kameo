<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';


$reference=$_POST['widget-addBuilding-form-reference'];
$descriptionFr=$_POST['widget-addBuilding-form-descriptionFr'];
$descriptionEn=$_POST['widget-addBuilding-form-descriptionEn'];
$descriptionNl=$_POST['widget-addBuilding-form-descriptionNl'];
$address=$_POST['widget-addBuilding-form-adress'];
$user=$_POST['widget-addBuilding-form-requestor'];
$company=$_POST['widget-addBuilding-form-company'];

if($reference != NULL && $descriptionFr != NULL && $descriptionEn != NULL && $descriptionNl != NULL && $address != NULL && $user != NULL){
    include 'connexion.php';
    $sql="select * from building_access where BUILDING_REFERENCE='$reference'";

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

    $sql= "INSERT INTO  building_access (USR_MAJ, HEU_MAJ, BUILDING_REFERENCE, BUILDING_CODE, BUILDING_FR, BUILDING_EN, BUILDING_NL, ADDRESS, COMPANY) VALUES ('$user', CURRENT_TIMESTAMP, '$reference', '$reference', '$descriptionFr', '$descriptionEn', '$descriptionNl', '$address', '$company')";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    if(isset($_POST['bikeAccess'])){
        foreach($_POST['bikeAccess'] as $valueInArray){
            $sql= "INSERT INTO  bike_building_access (USR_MAJ, TIMESTAMP, BIKE_ID, BUILDING_CODE, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$reference', '')";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
        }
    }

    if(isset($_POST['userAccess'])){
        foreach($_POST['userAccess'] as $valueInArray){
            $sql= "INSERT INTO  customer_building_access (USR_MAJ, TIMESTAMP, EMAIL, BUILDING_CODE, STAANN) VALUES ('$user', CURRENT_TIMESTAMP, '$valueInArray', '$reference', '')";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
        }
    }


    $conn->close();

    successMessage("SM0014");
}else{
    errorMessage("ES0025");
}
?>
