<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

require_once 'globalfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/authentication.php';
$token = getBearerToken();

if(isset($_POST['action'])){
    $action=isset($_POST['action']) ? $_POST['action'] : NULL;

    if($action == "command"){

        $portfolioID=isset($_POST['ID']) ? $_POST['ID'] : NULL;
        $email=isset($_POST['email']) ? $_POST['email'] : NULL;
        $size=isset($_POST['size']) ? $_POST['size'] : NULL;
        $remark=isset($_POST['remark']) ? nl2br($_POST['remark']) : NULL;
        $order_amount = isset($_POST['order_amount']) ? $_POST['order_amount'] : NULL;
        $leasing_type = isset($_POST['leasing_type']) ? $_POST['leasing_type'] : "leasing";

        include 'connexion.php';
        $stmt = $conn->prepare("SELECT PRENOM, NOM, bb.ID FROM customer_referential aa, companies bb WHERE EMAIL=? and aa.COMPANY=bb.INTERNAL_REFERENCE");
        if (!$stmt->bind_param("s", $email)) {
            $response = array ('response'=>'error', 'message'=> "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error);
            echo json_encode($response);
            die;
        }

        if (!$stmt->execute()) {
            $response = array ('response'=>'error', 'message'=> "Echec lors de l'exécution : (" . $stmt->errno . ") " . $stmt->error);
            echo json_encode($response);
            die;
        }

        $resultat=$stmt->get_result()->fetch_assoc();
        $firstName=$resultat['PRENOM'];
        $name=$resultat['NOM'];
        $companyID=$resultat['ID'];
        $stmt->close();


        $sql="SELECT * FROM client_orders where EMAIL='$email'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $length = $result->num_rows;
        if($length>0){
            errorMessage("ES0061");
        }

        $stmt = $conn->prepare("INSERT INTO client_orders (USR_MAJ, EMAIL, PORTFOLIO_ID, SIZE, REMARK, STATUS, LEASING_PRICE, TYPE, COMPANY) VALUES(?, ?, ?, ?, ?, 'new', ?, ?, ?)") or die($mysqli->error);
				$stmt->bind_param("ssissdss", $email, $email, $portfolioID, $size, $remark, $order_amount, $leasing_type, $companyID);
				$stmt->execute();
        $stmt->close();

        require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
        $mail = new PHPMailer();

        if(constant('ENVIRONMENT')=="production"){
            $stmt = $conn->prepare("SELECT aa.* FROM customer_referential aa, customer_referential bb WHERE bb.EMAIL=? and aa.COMPANY=bb.COMPANY and aa.ADMINISTRATOR='Y' and aa.STAANN != 'D'");
            if (!$stmt->bind_param("s", $email)) {
                $response = array ('response'=>'error', 'message'=> "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error);
                echo json_encode($response);
                die;
            }
            if (!$stmt->execute()) {
                $response = array ('response'=>'error', 'message'=> "Echec lors de l'exécution : (" . $stmt->errno . ") " . $stmt->error);
                echo json_encode($response);
                die;
            }
            foreach($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $contact){
              if($contact['COMPANY'] != 'KAMEO'){
                $mail->AddAddress($contact['EMAIL'], $contact['PRENOM'].' '.$contact['NOM']);
              }
            }
            $stmt->close();

            $mail->AddCC('julien@kameobikes.com', 'Julien Jamar');
        }else{
            $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
        }
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';


        $mail->From = $email;
        $mail->FromName = $firstName.' '.$name;
        $mail->AddReplyTo($email, $name);
        $subject="Nouvelle commande de vélo de la part de ".$firstName.' '.$name;
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

                                    <h3>Nouvelle commande !&nbsp;</h3>

        <p>Nouvelle commande de la part de $firstName $name.<br>
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

        if(constant('ENVIRONMENT')=="test" || constant('ENVIRONMENT')=='production'){
            if(!$mail->Send()) {
               $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
                echo json_encode($response);
                die;
            }
        }

        $conn->close();
        $response['sql']=$sql;
        successMessage("SM0027");
    }
}else if(isset($_GET['action'])){

    $action=isset($_GET['action']) ? $_GET['action'] : NULL;

    if($action=="list"){


        $email=isset($_GET['email']) ? $_GET['email'] : NULL;
        $response=array();
        include 'connexion.php';
        $sql="SELECT * FROM client_orders where EMAIL='$email' and status != 'cancelled'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $conn->close();
        $length = $result->num_rows;

        $response['commandNumber']=$length;

        $i=0;

        while($row = mysqli_fetch_array($result)){


            $catalogID=$row['PORTFOLIO_ID'];
            include 'connexion.php';
            $sql="SELECT * FROM bike_catalog where ID='$catalogID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $resultat = mysqli_fetch_assoc($result);
            $conn->close();
            $response[$i]['id']=$row['ID'];
            $response[$i]['catalogID']=$catalogID;
            $response[$i]['size']=$row['SIZE'];
            $response[$i]['color']=$row['COLOR'];
            $response[$i]['remark']=$row['REMARK'];
            $response[$i]['status']=$row['STATUS'];
            $response[$i]['brand']=$resultat['BRAND'];
            $response[$i]['model']=$resultat['MODEL'];
            $response[$i]['frameType']=$resultat['FRAME_TYPE'];
            $response[$i]['deliveryDate']=$row['ESTIMATED_DELIVERY_DATE'];
            $response[$i]['deliveryAddress']=$row['DELIVERY_ADDRESS'];
            $response[$i]['testDATE']=$row['TEST_DATE'];
            $response[$i]['testAddress']=$row['TEST_ADDRESS'];
            $i++;
        }
        $response['response']="success";
        echo json_encode($response);
        die;

    }
}
?>
