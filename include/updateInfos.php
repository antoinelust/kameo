<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


// Form Fields
$user = $_SESSION['userID'];
$firstName = $_POST["widget-update-form-firstname"];
$name = $_POST["widget-update-form-name"];
$phone = $_POST["widget-update-form-phone"];
$adress = $_POST["widget-update-form-adress"];
$postCode = $_POST["widget-update-form-post-code"];
$city = $_POST["widget-update-form-city"];
$workAdress = $_POST["widget-update-form-work-adress"];
$workPostCode = $_POST["widget-update-form-work-post-code"];
$workCity = $_POST["widget-update-form-work-city"];

$newPasswordSwitch = $_POST["widget-update-form-password-switch"];

if($newPasswordSwitch=="true"){
    $newPassword = $_POST["widget-update-form-password"];
    $newPasswordConfirmation=$_POST["widget-update-form-password-confirmation"];
    
    if ($newPassword != $newPasswordConfirmation){
        errorMessage(ES0021);
    }
    else
    {
        $new_password_hash = password_hash($newPassword, PASSWORD_BCRYPT);
         $_SESSION['UserPassword']=$new_password_hash;
        
    }
}
     


if( $_SERVER['REQUEST_METHOD'] == 'POST') {

     if($user != '') {

         //Vérifier pour faire passer connexion.php via une fonction, pour éviter de surcharger des variables telles que $password.
        include 'connexion.php';
        $sql = "select ID from customer_referential where EMAIL='$user'";
        $result = mysqli_query($conn, $sql);
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();

        $row = mysqli_fetch_assoc($result);
        $ID = 	$row["ID"];

        include 'connexion.php';

        if($newPasswordSwitch=="true"){
            $sql = "update customer_referential set NOM='$name', NOM_INDEX='$name', PRENOM='$firstName', PRENOM_INDEX='$firstName',  PHONE='$phone', ADRESS='$adress', POSTAL_CODE='$postCode', CITY='$city', WORK_ADRESS='$workAdress', WORK_POSTAL_CODE='$workPostCode', WORK_CITY='$workCity', PASSWORD='$new_password_hash' where ID='$ID'";
        }else{
            $sql = "update customer_referential set NOM='$name', NOM_INDEX='$name', PRENOM='$firstName', PRENOM_INDEX='$firstName',  PHONE='$phone', ADRESS='$adress', POSTAL_CODE='$postCode', CITY='$city', WORK_ADRESS='$workAdress', WORK_POSTAL_CODE='$workPostCode', WORK_CITY='$workCity' where ID='$ID'";
        }

        $result = mysqli_query($conn, $sql);
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();

         successMessage('SM0003');

    } else {
        $response = array ('response'=>'error');     
        echo json_encode($response);
        die;
    }
    
}
else
{
	errorMessage('ES0012');
}
?>
