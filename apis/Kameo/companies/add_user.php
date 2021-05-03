<?php


$generatePassword=isset($_POST['generatePassword']) ? $_POST['generatePassword'] : NULL;
$fleetManager=isset($_POST['fleetManager']) ? "Y" : "N";
$send_mail=isset($_POST['sendMail']) ? "Y" : "N";

if(!isset($_POST['firstName']) || !isset($_POST['name']) || !isset($_POST['mail']) && !isset($_POST['company'])){
    errorMessage("ES0012");
}

$email=$_POST['mail'];
$name=$_POST['name'];
$phone = isset($_POST["phone"]) ? $_POST["phone"] : NULL;
$firstName=$_POST['firstName'];

$company=execSQL("SELECT COMPANY from customer_referential where TOKEN=?", array('s', $token), false)[0]['COMPANY'];
if($company=='KAMEO'){
    $company = $_POST['company'];
}

$conditions=getCondition($company);

if($generatePassword){
    $password_unencrypted=uniqid();
    $pass=password_hash($password_unencrypted, PASSWORD_DEFAULT);
}else if(isset($_POST['password'])){
    $password_unencrypted=$_POST['password'];
    $pass=password_hash($_POST['password'], PASSWORD_DEFAULT);
}else{
    errorMessage("ES0012");
}


$duplicate=true;

while($duplicate){
    $token = random_str();
    $somme=execSQL("SELECT COUNT(1) as SOMME FROM customer_referential WHERE TOKEN=?", array('s', $token), false)[0]['SOMME'];
    if($somme>0){
        $duplicate=true;
    }else{
        $duplicate=false;
    }
}


$accessRights='';


if($conditions['conditions']['CAFETARIA']=='Y'){
    $accessRights=$accessRights.',order';
}
if($conditions['conditions']['BOOKING']=='Y'){
    $accessRights=$accessRights.',search';
}
if($fleetManager=='Y'){
    $accessRights=$accessRights.',fleetManager';
}
if(substr($accessRights, 0, 1)==','){
    $accessRights=substr($accessRights, 1, strlen($accessRights)-1);
}

if($phone!=NULL && $phone != ""){
  $phone="'$phone'";
}else{
  $phone="NULL";
}

include __DIR__ .'/../connexion.php';
$sql= "INSERT INTO  customer_referential (USR_MAJ, NOM_INDEX, PRENOM_INDEX, NOM, PRENOM, PHONE, POSTAL_CODE, CITY, ADRESS, WORK_ADRESS, WORK_POSTAL_CODE, WORK_CITY, COMPANY, EMAIL, PASSWORD, STAANN, TOKEN, ACCESS_RIGHTS) VALUES ('$token', UPPER('$name'), UPPER('$firstName'), '$name', '$firstName', $phone, '0', '', '', '', '0', '', '$company', '$email', '$pass', '', '$token', '$accessRights')";

if ($conn->query($sql) === FALSE) {
    if($conn->errno=="1062"){
        errorMessage("ES0030");
    }else{
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
}



foreach($_POST as $name => $value){
    if($name=="buildingAccess"){
        foreach($_POST['buildingAccess'] as $valueInArray) {
            $sql= "INSERT INTO  customer_building_access (USR_MAJ, EMAIL, BUILDING_CODE, STAANN) VALUES ('mykameo','$email', '$valueInArray', '')";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
        }
    }

    if($name=="bikeAccess"){
        foreach($_POST['bikeAccess'] as $valueInArray) {
			$sql= "SELECT TYPE FROM customer_bike_access WHERE BIKE_ID='$valueInArray'";
			if ($conn->query($sql) === FALSE) {
				$response = array ('response'=>'error', 'message'=> $conn->error);
				echo json_encode($response);
				die;
			}
			$result = mysqli_query($conn, $sql);
			$resultat = mysqli_fetch_assoc($result);
			$type_bike=$resultat['TYPE'];

			if ($type_bike != 'personnel'){
				$sql= "INSERT INTO  customer_bike_access (USR_MAJ, EMAIL, BIKE_ID, TYPE, STAANN) VALUES ('mykameo','$email', '$valueInArray', 'partage', '')";
				if ($conn->query($sql) === FALSE) {
					$response = array ('response'=>'error', 'message'=> $conn->error);
					echo json_encode($response);
					die;
				}
			}
        }
    }
}

if($send_mail == "Y"){
  require_once('../../../include/php-mailer/PHPMailerAutoload.php');
  $mail = new PHPMailer();

  $mail->IsHTML(true);
  $mail->CharSet = 'UTF-8';

  $mail->AddAddress($email);

  if($company=='Actiris'){
      $mail->From = "bookabike@actiris.be";
      $mail->FromName = "Book a Bike - Actiris";
  }else{
      $mail->From = "info@kameobikes.com";
      $mail->FromName = "Info Kameo Bikes";
  }

  $mail->AddReplyTo("info@kameobikes.com");
  $mail->AddAddress($email);

  if($company == "Methanex Corporation"){
    $subject = "Be-Flex - Bike Lease - MyKameo Access";
  }else{
    $subject = "Accès MyKameo - Toegang tot MyKameo";
  }
  $mail->Subject = $subject;

  if($company=='Actiris'){
      include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_new_user_actiris.php';
  }else{
      include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';
      include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_body_new_user.php';
      include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_footer.php';
  }
  $mail->Body = $body;

  require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';

  if(constant('ENVIRONMENT')!="local"){
      if(!$mail->Send()) {
          $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
          echo json_encode($response);
          die;
      }
  }
}
successMessage("SM0008");
?>
