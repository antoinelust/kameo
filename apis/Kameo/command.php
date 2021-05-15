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

        $resultat=execSQL("SELECT PRENOM, NOM, bb.ID FROM customer_referential aa, companies bb WHERE TOKEN=? and aa.COMPANY=bb.INTERNAL_REFERENCE", array('s', $token), false)[0];
        $firstName=$resultat['PRENOM'];
        $name=$resultat['NOM'];
        $companyID=$resultat['ID'];


        /*$sql="SELECT * FROM client_orders where EMAIL='$email'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $length = $result->num_rows;
        if($length>0){
            errorMessage("ES0061");
        }*/

        $groupID=execSQL("INSERT INTO grouped_orders (USR_MAJ, COMPANY_ID, EMAIL) VALUES (?,?,?)", array('sis', $token, $companyID, $email), true);

        execSQL("INSERT INTO client_orders (USR_MAJ, GROUP_ID, PORTFOLIO_ID, SIZE, REMARK, STATUS, LEASING_PRICE, TYPE, COMMENTS_ADMIN) VALUES(?, ?, ?, ?, ?, 'new', ?, ?, '')", array("siissds", $token, $groupID, $portfolioID, $size, $remark, $order_amount, $leasing_type), true);

        if(isset($_POST['accessory'])){
          foreach ($_POST['accessory'] as $index => $accessory){
            $accessoryID=$accessory;
            $accessoryBillingType=$_POST['accessoryBillingType'][$index];
            $accessoryAmount=$_POST['accessoryAmount'][$index];
            $remark='';
            $status='new';
            execSQL("INSERT INTO order_accessories (USR_MAJ, ORDER_ID, BRAND, PRICE_HTVA, TYPE, DESCRIPTION, STATUS) VALUES(?,?,?,?,?,?,?)",
            array('siidsss', $token, $groupID, $accessoryID, $accessoryAmount, $accessoryBillingType, $remark, $status), true);
          }
        }

        require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
        $mail = new PHPMailer();

        if(constant('ENVIRONMENT')=="production"){
            $resultat=execSQL("SELECT aa.EMAIL, aa.NOM, aa.PRENOM FROM customer_referential aa, customer_referential bb WHERE bb.TOKEN=? and aa.COMPANY=bb.COMPANY and aa.ACCESS_RIGHTS like '%fleetManager%' and aa.STAANN != 'D' GROUP BY aa.EMAIL, aa.NOM, aa.PRENOM", array('s', $token), false);
            foreach($resultat as $contact){
              if($contact['COMPANY'] != 'KAMEO'){
                $mail->AddAddress($contact['EMAIL'], $contact['PRENOM'].' '.$contact['NOM']);
              }
            }
            $mail->AddCC('julien@kameobikes.com', 'Julien Jamar');
        }else{
            $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
        }
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';


        $mail->From = "info@kameobikes.com";
        $mail->FromName = 'Info KAMEO Bikes';
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
        successMessage("SM0027");
    }
}else if(isset($_GET['action'])){
  $action=isset($_GET['action']) ? $_GET['action'] : NULL;
  if($action=="list"){
    $email=isset($_GET['email']) ? $_GET['email'] : NULL;
    $response=array();
    $result=execSQL("SELECT client_orders.* FROM client_orders, grouped_orders where grouped_orders.EMAIL=? and status != 'cancelled' AND grouped_orders.ID=client_orders.GROUP_ID", array('s', $email), false);

    if(is_null($result)){
      $response['commandNumber']=0;
    }else{
      $response['commandNumber']=count($result);
    }

    $response['accessories']=execSQL("SELECT order_accessories.BRAND as catalogID, order_accessories.PRICE_HTVA, accessories_categories.CATEGORY, accessories_catalog.BRAND, accessories_catalog.MODEL, order_accessories.TYPE, order_accessories.ORDER_ID as orderID
      												FROM order_accessories, accessories_categories, accessories_catalog, customer_referential, grouped_orders
      												WHERE order_accessories.BRAND=accessories_catalog.ID
      												AND accessories_categories.ID=accessories_catalog.ACCESSORIES_CATEGORIES
                              AND order_accessories.ORDER_ID=grouped_orders.ID
                              AND grouped_orders.EMAIL=customer_referential.EMAIL AND customer_referential.TOKEN=?",
                            array('s',$token), false);

    $i=0;

    if(!is_null($result)){
      foreach($result as $row){
        $catalogID=$row['PORTFOLIO_ID'];
        include 'connexion.php';
        $sql="SELECT * FROM bike_catalog where ID='$catalogID'";
        if ($conn->query($sql) === FALSE){
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result2 = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result2);
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
    }
    $response['response']="success";
    echo json_encode($response);
    die;
  }
}
?>
