<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';



require_once('../../include/php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();

log_input();

$name = isset($_POST["name"]) ? addslashes($_POST["name"]) : "N/A";
$firstName = isset($_POST["firstName"]) ? addslashes($_POST["firstName"]) : "N/A";
$email = isset($_POST["email"]) ? addslashes($_POST["email"]) : "N/A";
$phone = isset($_POST["phone"]) ? addslashes($_POST["phone"]) : null;
$offer = isset($_POST["offer"]) ? addslashes($_POST["offer"]) : null;

$subject = "Demande de commande pour le bons Plan ".$offer." par ".$firstName." ".$name;




$length = strlen($phone);
if ($length<8 or $length>12) {
	errorMessage("ES0004");
}

if( $_SERVER['REQUEST_METHOD'] == 'POST') {

   if($email != '' && $phone != '') {


        $mail->IsHTML(true);                                    // Set email format to HTML
        $mail->CharSet = 'UTF-8';

        $mail->AddAddress('younes.chillah@kameobikes.com', 'Younes Chillah');
        

        $mail->From = $email;
        $mail->FromName = $firstName.' '.$name;
        $mail->AddReplyTo($email, $name);
        $mail->Subject = $subject;
        include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';

        $message='Nouvelle demande de commande pour un bon plan ('.$offer.') de la part de '.$firstName.' '.$name.'<br>
        Numéro de téléphone: '.$phone;




        $body = $body."
        <body>
        <!--[if !gte mso 9]><!----><span class=\"mcnPreviewText\" style=\"display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;\">Mail reçu via la page de de bons plans</span><!--<![endif]-->
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

        <h3>Nouvelle demande de commande : !&nbsp;</h3>

        <p>$message<br>
        <br>
        Rendez-vous sur votre interface <a href=\"https://www.kameobikes.com/mykameo.php\">MyKameo</a> pour plus d'informations.</p>
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

        include 'connexion.php';



        if(constant('ENVIRONMENT')=="test" || constant('ENVIRONMENT')=="production"){
            if(!$mail->Send()) {
                $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);

            }else {
                $response = array ('response'=>'success', 'message'=> "Nous avons bien reçu votre message et nous reviendrons vers vous dès que possible.");
            }
        }else{
            $response = array ('response'=>'success', 'message'=> "Environnement local, mail non envoyé");
        }
        echo json_encode($response);
        die;

    } else {
       $response = array ('response'=>'error');
       echo json_encode($response);
       die;
   }

}
?>
