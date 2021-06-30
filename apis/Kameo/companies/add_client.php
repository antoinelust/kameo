<?php

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
$type=$_POST['type'];
$audience=$_POST['audience'];

if($audience=="B2B"){


  if(isset($_FILES['picture']) && !empty($_FILES['picture'])){
    $dossier=__DIR__ .'/../../../images/';
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
}


if($audience=="B2B"){
  $result=execSQL("select * from companies where INTERNAL_REFERENCE=?", array('s', $internalReference), false);
  if(count($result)!='0'){
      $conn->close();
      errorMessage("ES0036");
  }
  $compID = execSQL("INSERT INTO  companies (USR_MAJ, HEU_MAJ, COMPANY_NAME, STREET, ZIP_CODE, TOWN, VAT_NUMBER, INTERNAL_REFERENCE, TYPE, AUTOMATIC_STATISTICS, BILLING_GROUP, STAANN, BILLS_SENDING, AUDIENCE) VALUES (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, '', '1', '', 'N', ?)", array('sssssssss', $token, $description, $street, $zipCode, $city, $VAT, $internalReference, $type, $audience), true);
  execSQL("INSERT INTO conditions (USR_MAJ, HEU_MAJ, BOOKING_DAYS, BOOKING_LENGTH, HOUR_START_INTAKE_BOOKING, HOUR_END_INTAKE_BOOKING, HOUR_START_DEPOSIT_BOOKING, HOUR_END_DEPOSIT_BOOKING, MONDAY_INTAKE, TUESDAY_INTAKE, WEDNESDAY_INTAKE, THURSDAY_INTAKE, FRIDAY_INTAKE, SATURDAY_INTAKE, SUNDAY_INTAKE, MONDAY_DEPOSIT, TUESDAY_DEPOSIT, WEDNESDAY_DEPOSIT, THURSDAY_DEPOSIT, FRIDAY_DEPOSIT, SATURDAY_DEPOSIT, SUNDAY_DEPOSIT, COMPANY, ASSISTANCE, LOCKING, MAX_BOOKINGS_YEAR, MAX_BOOKINGS_MONTH, NAME) VALUE(?, CURRENT_TIMESTAMP, '2', '24', '7', '19', '7', '19', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '0', '0', ?, 'N', 'N', '9999', '9999', 'generic')", array('ss', $token, $internalReference), true);
}else{
  $name=isset($_POST['name']) ? htmlspecialchars($_POST['name']) : NULL;
  $firstName=isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : NULL;
  $phone=isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : NULL;
  $email=isset($_POST['email']) ? htmlspecialchars($_POST['email']) : NULL;
  $internalReference=$firstName." ".$name;
  $result=execSQL("select * from companies where INTERNAL_REFERENCE=?", array('s', $internalReference), false);
  if(count($result)!='0'){
      $conn->close();
      errorMessage("ES0036");
  }
  $compID = execSQL("INSERT INTO  companies (USR_MAJ, HEU_MAJ, COMPANY_NAME, STREET, ZIP_CODE, TOWN, VAT_NUMBER, INTERNAL_REFERENCE, TYPE, AUTOMATIC_STATISTICS, BILLING_GROUP, STAANN, BILLS_SENDING, AUDIENCE) VALUES (?, CURRENT_TIMESTAMP, ?, ?, ?, ?, NULL, ?, ?, '', '1', '', 'N', ?)", array('ssssssss', $token, $internalReference, $street, $zipCode, $city, $internalReference, $type, $audience), true);
  execSQL("INSERT INTO companies_contact (USR_MAJ, NOM, PRENOM, EMAIL, PHONE, FUNCTION, ID_COMPANY, TYPE, BIKES_STATS) VALUES (?, ?, ?, ?, ?, 'contact', ?, 'billing', 'N')", array('sssssi', $token, $name, $firstName, $email, $phone, $compID), true);
}

$response['response']="success";
$response['message']="Société ajoutée";
$response['companyID']=$compID;
$response['companyName']=$internalReference;
echo json_encode($response);
die;
?>
