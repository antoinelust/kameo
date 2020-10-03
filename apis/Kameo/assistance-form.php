<?php
session_cache_limiter('nocache');
include 'globalfunctions.php';

header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
    session_start();


// Form Fields
$user = $_SESSION['userID'];

if( $_SERVER['REQUEST_METHOD'] == 'POST') {

 if($user != '') {

  include 'connexion.php';
	$sql = "select * from customer_referential where EMAIL='$user'";
	$result = mysqli_query($conn, $sql);
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$row = mysqli_fetch_assoc($result);
  $company=$row['COMPANY'];
	$conn->close();

	if(isset($_FILES['widget-assistance-form-message-attachment']))
	{

		$dossier = $_SERVER['DOCUMENT_ROOT'].'/upload/assistance/';

		$extensions = array('.png', '.jpg', '.jpeg');
		$extension = strrchr($_FILES['widget-assistance-form-message-attachment']['name'], '.');
		if(!in_array($extension, $extensions))
		{
			  errorMessage("ES0022");
		}


		$taille_maxi = 6291456;
		$taille = filesize($_FILES['widget-assistance-form-message-attachment']['tmp_name']);
		if($taille>$taille_maxi)
		{
			  errorMessage("ES0023");
		}

		$today = getdate();


		$fichier = $user.'-'.$today['mday'].$today['mon'].$today['year'].$today['hours'].$today['minutes'].$extension;

		 if(move_uploaded_file($_FILES['widget-assistance-form-message-attachment']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
		 {
			$upload=true;
			$path= $dossier . $fichier;
		 }
		 else
		 {
			  errorMessage("ES0024");
		 }
	}



	require_once('../../include/php-mailer/PHPMailerAutoload.php');
	$mail = new PHPMailer();

	$mail->IsHTML(true);                                    // Set email format to HTML
	$mail->CharSet = 'UTF-8';
  if($company=='Actiris'){
    $mail->AddAddress('bookabike@actiris.be');
  }else{
    $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
  }
	$firstName=$row["PRENOM"];
	$name=$row["NOM"];
	$phone=$row["PHONE"];
	$frameNumber=$row["FRAME_NUMBER"];
	$contractNumber = $_POST["widget-assistance-form-contract"];
	$message = $_POST["widget-assistance-form-message"];

	if(isset($upload) && $upload){
		$mail->AddAttachment( $path , $fichier );
	}

    $mail->From = $user;
	$mail->FromName = $firstName.' '.$name;
	$subject = 'Assistance demandée par '.$firstName.' '.$name;
  $message = isset($message) ? "<strong>Message</strong> :<br>".nl2br($message)."<br><br>" : '';
	$name = isset($name) ? "<hr> <br>Nom: $name<br><br>" : '';
	$firstName = isset($firstName) ? "Prenom: $firstName<br><br>" : '';
	$email = isset($user) ? "Email: $user<br><br>" : '';


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

                              <h3>Nouvelle demande d'assistance !&nbsp;</h3>

                              $name $firstName $email $message
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

	if(!$mail->Send()) {
		$response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
		echo json_encode($response);
		die;
	}
	if ($_SESSION['langue'] == "en"){
		$response = array ('response'=>'success', 'message'=> "Mail sent. We will contact you as soon as possible.");
	} elseif ($_SESSION['langue'] == "nl"){
		$response = array ('response'=>'success', 'message'=> "Post verzonden. We nemen zo snel mogelijk contact met u op.");
	} else{
		$response = array ('response'=>'success', 'message'=> "Demande de support envoyée. Nous vous contacterons dans les plus brefs délais.");
	}
	echo json_encode($response);

} else {
	 errorMessage("ES0008");
}

}
?>
