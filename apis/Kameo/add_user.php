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


  if(!isset($_POST['firstName']) || !isset($_POST['name']) || !isset($_POST['mail']) || ((!isset($_POST['requestor'])) && !isset($_POST['company']))){

      errorMessage("ES0012");
  }





  $email=$_POST['mail'];
  $name=$_POST['name'];
  $firstName=$_POST['firstName'];
  $requestor=$_POST['requestor'];

  include 'connexion.php';
  $sql="select * from customer_referential where EMAIL='$requestor'";

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
  $sql= "INSERT INTO  customer_referential (USR_MAJ, NOM_INDEX, PRENOM_INDEX, NOM, PRENOM, PHONE, POSTAL_CODE, CITY, ADRESS, WORK_ADRESS, WORK_POSTAL_CODE, WORK_CITY, COMPANY, EMAIL, PASSWORD, ADMINISTRATOR, STAANN, TOKEN, ACCESS_RIGHTS) VALUES ('$requestor', UPPER('$name'), UPPER('$firstName'), '$name', '$firstName', '', '0', '', '', '', '0', '', '$company', '$email', '$pass', '$fleetManager', '', '$token', '$accessRights')";

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

  require_once('../../include/php-mailer/PHPMailerAutoload.php');
  $mail = new PHPMailer();

  $mail->IsHTML(true);
  $mail->CharSet = 'UTF-8';



  if(substr($_SERVER['REQUEST_URI'], 1, 4) != "test" && substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
      $mail->AddAddress($email);
  }else{
      $mail->AddAddress($requestor);
  }

  $mail->From = "info@kameobikes.com";
  $mail->FromName = "Kameo Bikes";

  $mail->AddReplyTo("info@kameobikes.com");
  $mail->AddAddress($email);

  $subject = "Compte créé pour la plateforme MyKameo ! ";
  $mail->Subject = $subject;


  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';


  $body = $body."
      <body>
          <!--[if !gte mso 9]><!----><span class=\"mcnPreviewText\" style=\"display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;\">Mail reçu via la page de contact</span><!--<![endif]-->
          <!--*|END:IF|*-->
          <center>
              <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" width=\"100%\" id=\"bodyTable\">
                  <tr>
                      <td align=\"center\" valign=\"top\" id=\"bodyCell\">
                          <!-- BEGIN TEMPLATE // -->
                          <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                              <tr>
                                  <td align=\"center\" valign=\"top\" id=\"templateHeader\" data-template-container>
                                      <!--[if (gte mso 9)|(IE)]>
                                      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"600\" style=\"width:600px;\">
                                      <tr>
                                      <td align=\"center\" valign=\"top\" width=\"600\" style=\"width:600px;\">
                                      <![endif]-->
                                      <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"templateContainer\">
                                          <tr>
                                              <td valign=\"top\" class=\"headerContainer\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"mcnImageBlock\" style=\"min-width:100%;\">
      <tbody class=\"mcnImageBlockOuter\">
              <tr>
                  <td valign=\"top\" style=\"padding:9px\" class=\"mcnImageBlockInner\">
                      <table align=\"left\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"mcnImageContentContainer\" style=\"min-width:100%;\">
                          <tbody><tr>
                              <td class=\"mcnImageContent\" valign=\"top\" style=\"padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;\">


                                          <img align=\"center\" alt=\"\" src=\"https://gallery.mailchimp.com/c4664c7c8ed5e2d53dc63720c/images/8b95e5d1-2ce7-4244-a9b0-c5c046bf7e66.png\" width=\"300\" style=\"max-width:300px; padding-bottom: 0; display: inline !important; vertical-align: bottom;\" class=\"mcnImage\">


                              </td>
                          </tr>
                      </tbody></table>
                  </td>
              </tr>
      </tbody>
  </table></td>
                                          </tr>
                                      </table>
                                      <!--[if (gte mso 9)|(IE)]>
                                      </td>
                                      </tr>
                                      </table>
                                      <![endif]-->
                                  </td>
                              </tr>
                              <tr>
                                  <td align=\"center\" valign=\"top\" id=\"templateBody\" data-template-container>
                                      <!--[if (gte mso 9)|(IE)]>
                                      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"600\" style=\"width:600px;\">
                                      <tr>
                                      <td align=\"center\" valign=\"top\" width=\"600\" style=\"width:600px;\">
                                      <![endif]-->
                                      <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"templateContainer\">
                                          <tr>
                                              <td valign=\"top\" class=\"bodyContainer\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"mcnTextBlock\" style=\"min-width:100%;\">
      <tbody class=\"mcnTextBlockOuter\">
          <tr>
              <td valign=\"top\" class=\"mcnTextBlockInner\" style=\"padding-top:9px;\">
                  <!--[if mso]>
                  <table align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;\">
                  <tr>
                  <![endif]-->

                  <!--[if mso]>
                  <td valign=\"top\" width=\"600\" style=\"width:600px;\">
                  <![endif]-->
                  <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:100%; min-width:100%;\" width=\"100%\" class=\"mcnTextContentContainer\">
                    <tbody><tr>
                      <td valign=\"top\" class=\"mcnTextContent\" style=\"padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;\">

                        <h3>Félicitations!&nbsp;</h3>

                        <p>Vous recevez cet email car vous venez d'être ajouté aux utilisateurs des vélos KAMEO.<br>
                        Vous pouvez dès à présent vous connecter à votre espace en ligne <a href=\"www.kameobikes.com/mykameo.php\">MyKAMEO</a>.<br>
                        <br>
                        <strong>Votre identifiant:</strong> $email <br>
                        <strong>Votre mot de passe:</strong> $password_unencrypted</p>
                      </td>
                      <td valign=\"top\" class=\"mcnTextContent\" style=\"padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;\">

                        <h3>Gefeliciteerd!!&nbsp;</h3>

                        <p>U ontvangt deze e-mail omdat u vanaf nu toegang hebt aan de KAMEO-fietsen.<br>
                        U kunt zich aanmelden op uw account <a href=\"www.kameobikes.com/mykameo.php\">MyKAMEO</a>.<br>
                        <br>
                        <strong>Gebruikersnaam:</strong> $email <br>
                        <strong>Wachtwoord:</strong> $password_unencrypted</p>
                      </td>
                    </tr>
                  </tbody></table>
                  <!--[if mso]>
                  </td>
                  <![endif]-->

                  <!--[if mso]>
                  </tr>
                  </table>
                  <![endif]-->
              </td>
          </tr>
      </tbody>
  </table>";



  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_footer.php';

  $mail->Body = $body;


  if(constant('ENVIRONMENT')=="test" || constant('ENVIRONMENT')=='production'){
      if(!$mail->Send()) {
         $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
          echo json_encode($response);
          die;
      }
  }

  successMessage("SM0008");

}else{
  error_message('403');
}

?>
