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


$internalReference=$_POST['widget-addClient-form-internalReference'];
$description=$_POST['widget-addClient-form-description'];
$VAT=$_POST['widget-addClient-form-VAT'];
$street=$_POST['widget-addClient-form-street'];
$zipCode=$_POST['widget-addClient-form-zipCode'];
$city=$_POST['widget-addClient-form-city'];
$contactMail=$_POST['widget-addClient-form-contactMail'];
$contactFirstMail=$_POST['widget-addClient-form-contactFirstMail'];
$contactLastName=$_POST['widget-addClient-form-contactLastName'];
$originator=$_POST['widget-addClient-form-email'];


if(isset($_FILES['widget-addClient-form-picture'])){
    $extensions = array('.jpg');
    $extension = strrchr($_FILES['widget-addClient-form-picture']['name'], '.');
    if(!in_array($extension, $extensions))
    {
          errorMessage("ES0041");
    }


    $taille_maxi = 6291456;
    $taille = filesize($_FILES['widget-addClient-form-picture']['tmp_name']);
    if($taille>$taille_maxi)
    {
          errorMessage("ES0023");
    }

    //upload of Bike picture

    $dossier = '../images/';



    $fichier = $internalReference.$extension;

     if(move_uploaded_file($_FILES['widget-addClient-form-picture']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
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


if($internalReference != NULL && $description != NULL && $VAT != NULL && $street != NULL && $zipCode != NULL && $city != NULL && $contactMail != NULL && $contactFirstMail != NULL && $contactLastName != NULL){
    include 'connexion.php';
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

    $sql= "INSERT INTO  companies (USR_MAJ, HEU_MAJ, COMPANY_NAME, STREET, ZIP_CODE, TOWN, VAT_NUMBER, INTERNAL_REFERENCE, EMAIL_CONTACT, NOM_CONTACT, PRENOM_CONTACT, STAANN) VALUES ('$originator', CURRENT_TIMESTAMP, '$description', '$street', '$zipCode', '$city', '$VAT', '$internalReference', '$contactMail', '$contactLastName', '$contactFirstMail', '')";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->errno);
        echo json_encode($response);
        die;   
    }
    $conn->close();   

    successMessage("SM0008");
}else{
    errorMessage("ES0025");
}
?>