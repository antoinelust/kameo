<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');
require_once('../../include/php-mailer/PHPMailerAutoload.php');


include 'globalfunctions.php';

if(isset($_GET['action'])){
    $action=$_GET['action'];
    if($action=='retrieveBooking'){
        $ID = $_GET["ID"];

        include 'connexion.php';


        $sql = "SELECT aa.*, bb.FRAME_NUMBER, bb.MODEL as modelBike, cc.BRAND, cc.MODEL as modelCatalog, cc.FRAME_TYPE, cc.ID as 'catalogID' FROM reservations aa, customer_bikes bb, bike_catalog cc where aa.ID='$ID' and aa.BIKE_ID=bb.ID and bb.TYPE=cc.ID";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();

        $response = array ('response'=>'success');
	      $response['start']= $resultat['DATE_START_2'];
	      $response['end']= $resultat['DATE_END_2'];
        $response['bikeID']=$resultat['BIKE_ID'];
        $response['email']=$resultat['EMAIL'];
        $response['ID']=$resultat['ID'];
        $response['bike']=$resultat['modelBike'];
        $response['img']=$resultat['catalogID'];

        include 'connexion.php';


        $sql = "SELECT * FROM feedbacks where ID_RESERVATION='$ID'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();

        $response['status']=$resultat['STATUS'];
        if($resultat['STATUS']=='DONE'){
            $response['note']= $resultat['NOTE'];
            $response['comment']=$resultat['COMMENT'];
            $response['entretien']=$resultat['ENTRETIEN'];
        }


        echo json_encode($response);
        die;
    }
    else if($action=='retrieveFeedback'){
        $ID = $_GET["ID"];


        include 'connexion.php';
        $sql = "SELECT aa.*, bb.ID, bb.FRAME_NUMBER, bb.TYPE as 'catalogID' FROM feedbacks aa, customer_bikes bb where ID_RESERVATION='$ID' and aa.BIKE_ID=bb.ID";


        //error_log('in function');


        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();

        $response = array ('response'=>'success');
        $response['ID']=$resultat['ID'];
        $response['bike']=$resultat['FRAME_NUMBER'];
        $response['bikeID']=$resultat['BIKE_ID'];
        $response['catalogID']=$resultat['catalogID'];
        $response['note']=$resultat['NOTE'];
        $response['comment']=$resultat['COMMENT'];
        $response['entretien']=$resultat['ENTRETIEN'];

        include 'connexion.php';
        $sql = "SELECT * FROM reservations where ID='$ID'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();

        $response['start']=$resultat['DATE_START_2'];
        $response['end']=$resultat['DATE_END_2'];
        $response['email']=$resultat['EMAIL'];

        echo json_encode($response);
        die;
    }
    else if($action=='list'){

        include 'connexion.php';


        $sql = "SELECT dd.FRAME_NUMBER, dd.ID, bb.EMAIL, cc.DATE_START_2, cc.DATE_END_2, bb.NOM, bb.PRENOM, bb.COMPANY, aa.STATUS, aa.ENTRETIEN, aa.COMMENT, aa.NOTE, aa.ID_RESERVATION, aa.BIKE_ID FROM feedbacks aa, customer_referential bb, reservations cc, customer_bikes dd WHERE cc.EMAIL=bb.EMAIL AND dd.ID=aa.BIKE_ID AND aa.ID_RESERVATION=cc.ID AND aa.STATUS != 'CANCELLED' ORDER BY aa.HEU_MAJ DESC";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $conn->close();

        $i=0;
        $response = array ('response'=>'success');
        $response['feedbacksNumber']=$result->num_rows;
        while($row = mysqli_fetch_array($result))
        {
            $IDReservation=$row['ID_RESERVATION'];
            $response['feedback'][$i]['bikeID']=$row['BIKE_ID'];
            $response['feedback'][$i]['bike']=$row['FRAME_NUMBER'];
            $response['feedback'][$i]['IDReservation']=$row['ID_RESERVATION'];
            $response['feedback'][$i]['note']=$row['NOTE'];
            $response['feedback'][$i]['comment']=$row['COMMENT'];
            $response['feedback'][$i]['entretien']=$row['ENTRETIEN'];
            $response['feedback'][$i]['status']=$row['STATUS'];
            $response['feedback'][$i]['company']=$row['COMPANY'];
            $response['feedback'][$i]['firstName']=$row['PRENOM'];
            $response['feedback'][$i]['name']=$row['NOM'];
            $response['feedback'][$i]['start']=$row['DATE_START_2'];
            $response['feedback'][$i]['end']=$row['DATE_END_2'];
            $response['feedback'][$i]['email']=$row['EMAIL'];

            include 'connexion.php';

            $sql2="SELECT * FROM notifications where TYPE='feedback' AND TYPE_ITEM='$IDReservation'";

            if ($conn->query($sql2) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result2 = mysqli_query($conn, $sql2);
            $resultat = mysqli_fetch_assoc($result2);
            $conn->close();
            $response['feedback'][$i]['read']=$resultat['READ'];


            $i++;
        }

        echo json_encode($response);
        die;

    }
}else if(isset($_POST['action'])){
    $action=$_POST['action'];
    if($action=='add'){
        $ID=$_POST['ID'];
        $note=$_POST['note'];
        $entretien = isset($_POST["entretien"]) ? "1" : "0";
        $comment = isset($_POST["comment"]) ? nl2br($_POST["comment"]) : NULL;
        $user = isset($_POST["user"]) ? $_POST["user"] : NULL;
        $bike = isset($_POST["bike"]) ? $_POST["bike"] : NULL;
        include 'connexion.php';
        $feedback=execSQL("select * from feedbacks aa WHERE aa.ID_RESERVATION=?", array("i", $ID), false);
        $ID_feedback=$feedback[0]['ID'];

        if($comment!=NULL){
            $comment="'".addslashes($comment)."'";
        }else{
            $comment='NULL';
        }

        $sql="UPDATE feedbacks SET USR_MAJ='$user', HEU_MAJ=CURRENT_TIMESTAMP, ID_RESERVATION='$ID', NOTE='$note', COMMENT=$comment, ENTRETIEN='$entretien', STATUS='DONE' WHERE ID='$ID_feedback'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $sql="SELECT COMPANY FROM customer_referential WHERE EMAIL='$user'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $company=$resultat['COMPANY'];

        $conn->close();
        ($entretien == '1') ? $entretien = "Oui" : $entretien = "Non";

        $mail = new PHPMailer();

        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';

        if($company=='Actiris'){
            $mail->AddAddress('bookabike@actiris.be');
        }else{
            $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
        }

        $mail->From = "info@kameobikes.com";
        $mail->FromName = "Kameo Bikes";

        $mail->AddReplyTo($user);
        $mail->Subject = "Nouveau feedback - ".$user;

        if($company=="Actiris"){
          include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_feedback_actiris.php';
        }else{
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

                                      <h3>Nouveau feedback !&nbsp;</h3>

                                      <strong>$user :</strong><br>
                                      Vélo : $bike <br>
                                      ID de réservation : $ID <br>
                                      Note : $note <br>
                                      Besoin d'entretien ? $entretien <br>
                                      Commentaire: $comment<br>
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
        }



        $mail->Body = $body;
        if(constant('ENVIRONMENT')=="test" || constant('ENVIRONMENT')=='production'){
            if(!$mail->Send()) {
               $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
                echo json_encode($response);
                die;
            }
        }
        successMessage("SM0023");
    }

}


?>
