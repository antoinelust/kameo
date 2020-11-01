<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';

global $requestor;
global $email;
global $password_unencrypted;
//variable indiquant a add_company_contact.php qu'il s'agit d'un ajout de contact
//au moment de l'ajout d'un client (fonctionnement différent)
$addClient = true;

$internalReference=$_POST['internalReference'];
$description=$_POST['description'];
$VAT=$_POST['VAT'];
$street=addslashes($_POST['street']);
$zipCode=$_POST['zipCode'];
$city=addslashes($_POST['city']);
$originator=$_POST['email'];
$type=$_POST['type'];
$mailInitialisation=$_POST['mailInitialisation'];
$nameInitialisation=$_POST['nameInitialisation'];
$firstNameInitialisation=$_POST['firstNameInitialisation'];
$contactEmail=$_POST['contactEmail'];
$firstName=$_POST['firstName'];
$lastName=$_POST['lastName'];


if(isset($_POST['passwordInitialisation'])){
    $passwordTechnicalUser=password_hash($_POST['passwordInitialisation'], PASSWORD_DEFAULT);
}else{
    $passwordTechnicalUser='';
}
$dossier = '../../images/';


if(isset($_FILES['picture']) && !empty($_FILES['picture'])){
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




    $fichier = $internalReference.$extension;

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
    imagejpeg($img, $dossier . $fichier);
}else{
    copy($dossier . "default.jpg", $dossier . $internalReference . ".jpg");
}


if($internalReference != NULL && $description){
    include 'connexion.php';

    if($type=="CLIENT" && $mailInitialisation != '' && $passwordTechnicalUser != ''){
        $sql= "SELECT * FROM customer_referential WHERE EMAIL='$mailInitialisation'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;

        }
        $result = mysqli_query($conn, $sql);
        $length=$result->num_rows;
        if($length>0){
            errorMessage("ES0049");
        }
    }




    $sql="select * from companies where INTERNAL_REFERENCE='$internalReference'";
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

    $sql= "INSERT INTO  companies (USR_MAJ, HEU_MAJ, COMPANY_NAME, STREET, ZIP_CODE, TOWN, VAT_NUMBER, INTERNAL_REFERENCE, EMAIL_CONTACT, NOM_CONTACT, PRENOM_CONTACT, TYPE, AUTOMATIC_STATISTICS, BILLING_GROUP, STAANN, BILLS_SENDING) VALUES ('$originator', CURRENT_TIMESTAMP, '$description', '$street', '$zipCode', '$city', '$VAT', '$internalReference', '$contactEmail', '$firstName', '$lastName', '$type', '', '1', '', 'N')";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $compID = $conn->insert_id;

    $sql="INSERT INTO conditions (USR_MAJ, HEU_MAJ, BOOKING_DAYS, BOOKING_LENGTH, HOUR_START_INTAKE_BOOKING, HOUR_END_INTAKE_BOOKING, HOUR_START_DEPOSIT_BOOKING, HOUR_END_DEPOSIT_BOOKING, MONDAY_INTAKE, TUESDAY_INTAKE, WEDNESDAY_INTAKE, THURSDAY_INTAKE, FRIDAY_INTAKE, SATURDAY_INTAKE, SUNDAY_INTAKE, MONDAY_DEPOSIT, TUESDAY_DEPOSIT, WEDNESDAY_DEPOSIT, THURSDAY_DEPOSIT, FRIDAY_DEPOSIT, SATURDAY_DEPOSIT, SUNDAY_DEPOSIT, COMPANY, ASSISTANCE, LOCKING, MAX_BOOKINGS_YEAR, MAX_BOOKINGS_MONTH, NAME) VALUE('$originator', CURRENT_TIMESTAMP, '2', '24', '7', '19', '7', '19', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '0', '0', '$internalReference', 'N', 'N', '9999', '9999', 'generic')";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    if($type=="CLIENT" && $mailInitialisation != '' && $passwordTechnicalUser != ''){
        $sql= "INSERT INTO  customer_referential (USR_MAJ, NOM_INDEX, PRENOM_INDEX, NOM, PRENOM, PHONE, POSTAL_CODE, CITY, ADRESS, WORK_ADRESS, WORK_POSTAL_CODE, WORK_CITY, COMPANY, EMAIL, PASSWORD, ADMINISTRATOR, TOKEN, ACCESS_RIGHTS, STAANN) VALUES ('mykameo', UPPER('$nameInitialisation'), UPPER('$firstNameInitialisation'), '$nameInitialisation', '$firstNameInitialisation', '', '0', '', '', '', '0', '', '$internalReference', '$mailInitialisation', '$passwordTechnicalUser', 'N', '', '', '')";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
    $conn->close();
    }

    include 'add_company_contact.php';
    successMessage("SM0008");

}else{
    errorMessage("ES0025");
}
?>
