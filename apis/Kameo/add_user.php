<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

require_once 'globalfunctions.php';
require_once 'authentication.php';
$token = getBearerToken();




if(get_user_permissions(["fleetManager", "admin"], $token)){


  global $requestor;
  global $email;
  global $password_unencrypted;
  global $company;

  $generatePassword=isset($_POST['generatePassword']) ? $_POST['generatePassword'] : NULL;
  $fleetManager=isset($_POST['fleetManager']) ? "Y" : "N";
  $send_mail=isset($_POST['sendMail']) ? "Y" : "N";


  if(!isset($_POST['firstName']) || !isset($_POST['name']) || !isset($_POST['mail']) || ((!isset($_POST['requestor'])) && !isset($_POST['company']))){

      errorMessage("ES0012");
  }





  $email=$_POST['mail'];
  $name=$_POST['name'];
  $phone = $_POST['phone'];
  $firstName=$_POST['firstName'];
  $requestor=$_POST['requestor'];

  include 'connexion.php';
  $sql="SELECT * from customer_referential where EMAIL='$requestor'";

  if ($conn->query($sql) === FALSE){
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }

  $result = mysqli_query($conn, $sql);
  $resultat = mysqli_fetch_assoc($result);

  if(isset($_POST['company']) && !empty($_POST['company'])) {
      $company = $_POST['company'];
  }else{
  	$company = $resultat['COMPANY'];
  }

  include 'get_company_conditions.php';
  $conditions=get_company_conditions(NULL, NULL, $company);

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
      $sql="SELECT COUNT(1) as SOMME FROM customer_referential WHERE TOKEN='$token'";
      if ($conn->query($sql) === FALSE) {
          $response = array ('response'=>'error', 'message'=> $conn->error);
          echo json_encode($response);
          die;
      }
      $result = mysqli_query($conn, $sql);
      $resultat = mysqli_fetch_assoc($result);
      if($resultat['SOMME']>0){
          $duplicate=true;
      }else{
          $duplicate=false;
      }
  }


  $accessRights='';


  if($conditions['companyConditions']['cafeteria']=='Y'){
      $accessRights=$accessRights.',order';
  }
  if($conditions['companyConditions']['booking']=='Y'){
      $accessRights=$accessRights.',search';
  }
  if($fleetManager=='Y'){
      $accessRights=$accessRights.',fleetManager';
  }
  if(substr($accessRights, 0, 1)==','){
      $accessRights=substr($accessRights, 1, strlen($accessRights)-1);
  }


  include 'connexion.php';
  $sql= "INSERT INTO  customer_referential (USR_MAJ, NOM_INDEX, PRENOM_INDEX, NOM, PRENOM, PHONE, POSTAL_CODE, CITY, ADRESS, WORK_ADRESS, WORK_POSTAL_CODE, WORK_CITY, COMPANY, EMAIL, PASSWORD, ADMINISTRATOR, STAANN, TOKEN, ACCESS_RIGHTS) VALUES ('$requestor', UPPER('$name'), UPPER('$firstName'), '$name', '$firstName', '$phone', '0', '', '', '', '0', '', '$company', '$email', '$pass', '$fleetManager', '', '$token', '$accessRights')";

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
    require_once('../../include/php-mailer/PHPMailerAutoload.php');
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

    $subject = "Accès MyKameo - Toegang tot MyKameo";
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
    successMessage("SM0008");
    }

}else{
  error_message('403');
}

?>
