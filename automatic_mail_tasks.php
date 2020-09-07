<!DOCTYPE html>
<html lang="fr">
<?php
ob_start();
if(!isset($_SESSION))
	session_start();

$token=isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL; //@TODO: replaced by a token to check if connected
$user_ID=isset($_SESSION['ID']) ? $_SESSION['ID'] : NULL; //Used by: notifications.js
$langue=isset($_SESSION['langue']) ? $_SESSION['langue'] : 'fr';

include 'apis/Kameo/connexion.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/authentication.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';

$token = getBearerToken();

include 'include/head.php';
echo '<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">';
		include 'include/topbar.php';
		include 'include/header.php';

    require_once('include/php-mailer/PHPMailerAutoload.php');

    ?>





 <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                    	<div class="heading heading text-left m-b-20">
                        <h2>Mail de rappel</h2>
                        </div>
                        <p>Génération des actions à prendre</p>

                        <div class="m-t-30">
                            <?php
                            echo "------------------<br />";
                            echo "Début du script: ".date("H:m:s")."<br>";
                            echo "------------------<br />";
                            $temp=date("Y-m-d");



                            $mail = new PHPMailer();

                            $mail->IsHTML(true);
                            $mail->CharSet = 'UTF-8';

                            if(constant('ENVIRONMENT')=="production"){
                                $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
                                $mail->AddAddress('julien@kameobikes.com', 'Julien Jamar');
                                $mail->AddAddress("thibaut@kameobikes.com");
                                $mail->AddAddress("pierre-yves@kameobikes.com");
                            }else{
                                $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
                            }
                            $mail->From = "info@kameobikes.com";
                            $mail->FromName = "Kameo Bikes";

                            $mail->AddReplyTo("info@kameobikes.com");
                            $mail->Subject = "Mail automatique - Aperçu des tâches";

                            include 'include/connexion.php';
                            $sql= "select * from company_actions WHERE (DATE_REMINDER>'$temp' or DATE_REMINDER='NULL') AND STATUS='TO DO'";

                            if ($conn->query($sql) === FALSE) {
                                $response = array ('response'=>'error', 'message'=> $conn->error);
                                echo json_encode($response);
                                die;
                            }
                            $result = mysqli_query($conn, $sql);

                            $part2="<h3>Actions à prendre, date de rappel pas dépassée :</h3>";

                            $part2=$part2."<table style=\"width:100%\" class=\"tableResume\"><tr><th class=\"tableResume\">ID</th><th class=\"tableResume\">Société</th><th class=\"tableResume\">Date</th><th class=\"tableResume\">Date Rappel</th><th class=\"tableResume\">Description</th><th class=\"tableResume\">Status</th></tr>";

                            while($row = mysqli_fetch_array($result))
                            {
                                $id=$row['ID'];
                                $company=$row['COMPANY'];
                                $date_reminder=substr($row['DATE_REMINDER'], 0, 10);
                                $date=substr($row['DATE'],0,10);
                                $description=$row['DESCRIPTION'];
                                $status=$row['STATUS'];
                                $part2=$part2."<tr class=\"tableResume\"><td class=\"tableResume\">".$id."</td><td class=\"tableResume\">".$company."</td><td class=\"tableResume\">".$date."</td><td class=\"tableResume\">".$date_reminder."</td><td class=\"tableResume\">".$description."</td><td class=\"tableResume\">".$status."</td></tr>";

                            }
                            $part2=$part2."</table><div class=\"separator\"></div>";
                            $part2=$part2."<h3>Actions à prendre, date de rappel dépassée :</h3>";

                            include 'include/connexion.php';
                            $sql= "select * from company_actions WHERE DATE_REMINDER<'$temp' AND STATUS='TO DO'";

                            if ($conn->query($sql) === FALSE) {
                                $response = array ('response'=>'error', 'message'=> $conn->error);
                                echo json_encode($response);
                                die;
                            }
                            $result = mysqli_query($conn, $sql);


                            $part2=$part2."<table style=\"width:100%\" class=\"tableResume\"><tr><th class=\"tableResume\">ID</th><th class=\"tableResume\">Société</th><th class=\"tableResume\">Date</th><th class=\"tableResume\">Date Rappel</th><th class=\"tableResume\">Description</th><th class=\"tableResume\">Status</th></tr>";

                            while($row = mysqli_fetch_array($result))
                            {
                                $id=$row['ID'];
                                $company=$row['COMPANY'];
                                $date_reminder=substr($row['DATE_REMINDER'], 0, 10);
                                $date=substr($row['DATE'],0,10);
                                $description=$row['DESCRIPTION'];
                                $status=$row['STATUS'];
                                $part2=$part2."<tr class=\"tableResume\"><td class=\"tableResume\">".$id."</td><td class=\"tableResume\">".$company."</td><td class=\"tableResume\">".$date."</td><td class=\"tableResume\">".$date_reminder."</td><td class=\"tableResume\">".$description."</td><td class=\"tableResume\">".$status."</td></tr>";

                            }
                            $part2=$part2."</table>";
                            echo $part2;

                            include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';



                            $body=$body.'<body>
                                    <!--[if !gte mso 9]><!----><span class=\"mcnPreviewText\" style=\"display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;\">Résumé des tâches</span><!--<![endif]-->
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
                                                        <h3>Tâches non-complétées</h3>
                            <p>Veuillez trouver ci-dessous la liste des actions à prendre:<br>
                            <br>'.$part2;

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

                            echo "<br>------------------<br />";
                            echo "Fin du script: ".date("H:m:s");
                            echo "<br>------------------<br />";

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: CONTENT -->


		<!-- FOOTER -->
<footer class="background-dark text-grey" id="footer">
    <div class="footer-content">
        <div class="container">

        <br><br>

            <div class="row text-center">
                <div class="copyright-text text-center"> &copy; 2019 KAMEO Bikes</div>
                <div class="social-icons center">
							<ul>
								<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>

								<li class="social-instagram"><a href="https://www.instagram.com/kameobikes/" target="_blank"><i class="fa fa-instagram"></i></a></li>
							</ul>
				</div>
				<div class="copyright-text text-center"><a href="blog.php" class="text-green text-bold">Le blog</a></div>

				<br>
				<br>

            </div>
        </div>
    </div>
</footer>
		<!-- END: FOOTER -->
	<!-- END: WRAPPER -->


	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Custom js file -->
	<script src="js/language.js"></script>


</div></body>


</html>
