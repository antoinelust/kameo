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


$internalReference=$_POST['internalReference'];
$description=$_POST['description'];
$VAT=$_POST['VAT'];
$street=$_POST['street'];
$zipCode=$_POST['zipCode'];
$city=$_POST['city'];
$contactMail=$_POST['contactMail'];
$contactFirstMail=$_POST['contactFirstName'];
$contactLastName=$_POST['contactLastName'];
$originator=$_POST['email'];
$type=$_POST['type'];
$phone=$_POST['phone'];
$mailInitialisation=$_POST['mailInitialisation'];
$nameInitialisation=$_POST['nameInitialisation'];
$firstNameInitialisation=$_POST['firstNameInitialisation'];



if(isset($_POST['passwordInitialisation'])){
    $passwordTechnicalUser=password_hash($_POST['passwordInitialisation'], PASSWORD_DEFAULT);
}else{
    $passwordTechnicalUser='';
}
$dossier = '../images/';


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


if($internalReference != NULL && $description != NULL && $VAT != NULL && $street != NULL && $zipCode != NULL && $city != NULL && $contactMail != NULL && $contactFirstMail != NULL && $contactLastName != NULL){
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

    $sql= "INSERT INTO  companies (USR_MAJ, HEU_MAJ, COMPANY_NAME, STREET, ZIP_CODE, TOWN, VAT_NUMBER, INTERNAL_REFERENCE, EMAIL_CONTACT, NOM_CONTACT, PRENOM_CONTACT, TYPE, CONTACT_PHONE, STAANN) VALUES ('$originator', CURRENT_TIMESTAMP, '$description', '$street', '$zipCode', '$city', '$VAT', '$internalReference', '$contactMail', '$contactLastName', '$contactFirstMail', '$type', '$phone', '')";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;   
    }

    if($type=="CLIENT" && $mailInitialisation != '' && $passwordTechnicalUser != ''){
        $sql= "INSERT INTO  customer_referential (USR_MAJ, NOM_INDEX, PRENOM_INDEX, NOM, PRENOM, PHONE, POSTAL_CODE, CITY, ADRESS, WORK_ADRESS, WORK_POSTAL_CODE, WORK_CITY, COMPANY, EMAIL, PASSWORD, ADMINISTRATOR, STAANN) VALUES ('mykameo', UPPER('$nameInitialisation'), UPPER('$firstNameInitialisation'), '$nameInitialisation', '$firstNameInitialisation', '', '0', '', '', '', '0', '', '$internalReference', '$mailInitialisation', '$passwordTechnicalUser', 'N', '')";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;   
        }

    $conn->close();
    }
    

    successMessage("SM0008");
}else{
    errorMessage("ES0025");
}
?>