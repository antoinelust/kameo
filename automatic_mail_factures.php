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
$token = getBearerToken();

include 'include/head.php';
echo '<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">';
		include 'include/topbar.php';
		include 'include/header.php';

include 'apis/Kameo/connexion.php';
$sql="SELECT substr(DATE, 1, 7) as 'MOIS', SUM(CASE WHEN AMOUNT_HTVA > 0 THEN AMOUNT_HTVA ELSE 0 END) AS 'SUM1', SUM(CASE WHEN AMOUNT_HTVA < 0 THEN AMOUNT_HTVA ELSE 0 END) AS 'SUM2', SUM(AMOUNT_HTVA) AS 'SUM3' from factures GROUP BY Substr(DATE, 1, 7)";
$sql2 = "SELECT * FROM factures WHERE  FACTURE_SENT = '0' AND AMOUNT_HTVA>0";
$sql3 = "SELECT * FROM factures WHERE  FACTURE_SENT = '1' AND FACTURE_PAID='0' AND AMOUNT_HTVA>0 AND (FACTURE_LIMIT_PAID_DATE < CURDATE() OR ISNULL(FACTURE_LIMIT_PAID_DATE))";
$sql7 = "SELECT * FROM factures WHERE  FACTURE_SENT = '1' AND FACTURE_PAID='0' AND AMOUNT_HTVA>0 AND FACTURE_LIMIT_PAID_DATE	> CURDATE()";
$sql4 = "SELECT SUM(AMOUNT_HTVA) as SOMME FROM factures WHERE  FACTURE_SENT = '0' AND AMOUNT_HTVA>0";
$sql5 = "SELECT SUM(AMOUNT_HTVA) as SOMME FROM factures WHERE  FACTURE_SENT = '1' AND FACTURE_PAID='0' AND AMOUNT_HTVA>0 AND (FACTURE_LIMIT_PAID_DATE < CURDATE() OR ISNULL(FACTURE_LIMIT_PAID_DATE))";
$sql6 = "SELECT SUM(AMOUNT_HTVA) as SOMME FROM factures WHERE  FACTURE_SENT = '1' AND FACTURE_PAID='0' AND AMOUNT_HTVA>0 and FACTURE_LIMIT_PAID_DATE	> CURDATE()";
$sql8 = "SELECT * FROM factures WHERE FACTURE_PAID='0' AND AMOUNT_HTVA<0";
$sql9 = "SELECT SUM(AMOUNT_HTVA) as SOMME FROM factures WHERE FACTURE_PAID='0' AND AMOUNT_HTVA<0";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);

if ($conn->query($sql2) === FALSE) {
    $response2 = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response2);
    die;
}
$result2 = mysqli_query($conn, $sql2);

if ($conn->query($sql3) === FALSE) {
    $response3 = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response3);
    die;
}
$result3 = mysqli_query($conn, $sql3);

if ($conn->query($sql7) === FALSE) {
    $response7 = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response3);
    die;
}
$result7 = mysqli_query($conn, $sql7);

if ($conn->query($sql4) === FALSE) {
    $response4 = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response4);
    die;
}
$result4 = mysqli_query($conn, $sql4);

if ($conn->query($sql5) === FALSE) {
    $response5 = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response5);
    die;
}
$result5 = mysqli_query($conn, $sql5);

if ($conn->query($sql6) === FALSE) {
    $response6 = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response6);
    die;
}
$result6 = mysqli_query($conn, $sql6);

if ($conn->query($sql8) === FALSE) {
    $response8 = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response8);
    die;
}
$result8 = mysqli_query($conn, $sql8);


if ($conn->query($sql9) === FALSE) {
    $response9 = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response9);
    die;
}
$result9 = mysqli_query($conn, $sql9);


require_once('include/php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();

$mail->IsHTML(true);
$mail->CharSet = 'UTF-8';

include 'apis/Kameo/environment.php';
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
$subject = "Mail automatique - factures Kameo Bikes ";
$mail->Subject = $subject;

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';

$body = $body."
    <body>
        <!--[if !gte mso 9]><!----><span class=\"mcnPreviewText\" style=\"display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;\">Résumé des factures</span><!--<![endif]-->
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

                            <h3>Résumé des factures depuis le début de l'année</h3>
<p>Veuillez trouver ci-dessous un résumé des factures émises depuis le début de l'année:<br>
<br>";

$temp="<table style=\"width:100%\" class=\"tableResume\"><tr><th class=\"tableResume\">Mois</th><th class=\"tableResume\">Factures IN</th><th class=\"tableResume\">Factures OUT</th><th class=\"tableResume\">Total</th></tr>";
$dest=$temp;
$table="<table style=\"width:100%\" class=\"tableResume\"><tr><th class=\"tableResume\">Mois</th><th class=\"tableResume\">Factures IN</th><th class=\"tableResume\">Factures OUT</th><th class=\"tableResume\">Total</th></tr>";
while($row = mysqli_fetch_array($result))
{
    $temp="<tr class=\"tableResume\"><td class=\"tableResume\">".$row['MOIS']."</td><td class=\"tableResume\"><font color=\"green\">".round($row['SUM1'])." €</font></td><td class=\"tableResume\"><font color=\"red\">".round($row['SUM2'])." €</td>";
    $dest=$dest.$temp;
    $table=$table.$temp;
    if($row['SUM3']>0){
        $temp="<td class=\"tableResume\"><font color=\"green\">".round($row['SUM3'])." €</td></tr>";
    }else{
        $temp="<td class=\"tableResume\"><font color=\"red\">".round($row['SUM3'])." €</td></tr>";
    }
    $dest=$dest.$temp;
    $table=$table.$temp;
}

$dest=$dest."</table>";
$table=$table."</table>";
$body=$body.$dest."<br /><br /><h3>Factures à envoyer à nos clients</h3>
<p>Veuillez trouver ci-dessous la liste des factures à envoyer à nos clients :<br>
<br>";

$dest="";
$temp="<table style=\"width:100%\" class=\"tableResume\"><tr><th class=\"tableResume\">ID</th><th class=\"tableResume\">Société</th><th class=\"tableResume\">Date</th><th class=\"tableResume\">Montant (HTVA)</th><th class=\"tableResume\">Lien</th></tr>";
$dest=$temp;
while($row = mysqli_fetch_array($result2)){
    $temp="<tr class=\"tableResume\"><td class=\tableResume\">".$row['ID']."</td><td class=\"tableResume\">".$row['COMPANY']."</td><td class=\"tableResume\">".substr($row['DATE'],0,10)."</td><td class=\"tableResume\">".$row['AMOUNT_HTVA']." €</td><td class=\"tableResume\"><a href=\"www.kameobikes.com/factures/".$row['FILE_NAME']."\">Lien</a></td></tr>";
    $dest=$dest.$temp;
}

$dest=$dest."</table>";


$resultat4 = mysqli_fetch_assoc($result4);

$dest=$dest."<p>Valeur totale des factures non envoyées: ".round($resultat4['SOMME'])." € </p>";


$body=$body.$dest."<br /><br /><h3>Factures en retard de paiement</h3>
<p>Veuillez trouver ci-dessous la liste des factures qui n'ont pas encore été payées par nos clients :<br>
<br>";

$dest="";
$temp="<table style=\"width:100%\" class=\"tableResume\"><tr><th class=\"tableResume\">ID</th><th class=\"tableResume\">Société</th><th class=\"tableResume\">Date</th><th class=\"tableResume\">Montant (HTVA)</th><th>Date Limite de paiement</th><th class=\"tableResume\">Lien</th></tr>";
$dest=$temp;
while($row = mysqli_fetch_array($result3))
{
    $temp="<tr class=\"tableResume\"><td class=\tableResume\">".$row['ID']."</td><td class=\"tableResume\">".$row['COMPANY']."</td><td class=\"tableResume\">".substr($row['DATE'],0,10)."</td><td class=\"tableResume\">".$row['AMOUNT_HTVA']." €</td><td class=\"tableResume\">".substr($row['FACTURE_LIMIT_PAID_DATE'],0,10)."</td><td class=\"tableResume\"><a href=\"https://www.kameobikes.com/factures/".$row['FILE_NAME']."\">Lien</a></td></tr>";
    $dest=$dest.$temp;
}

$dest=$dest."</table>";

$resultat5 = mysqli_fetch_assoc($result5);

$dest=$dest."<p>Valeur totale des factures en retard de paiement: ".round($resultat5['SOMME'])." €</p>";

$body=$body.$dest."<br /><br /><h3>Factures non payées mais pas en retard de paiement</h3>
<p>Veuillez trouver ci-dessous la liste des factures qui n'ont pas encore été payées par nos clients :<br>
<br>";

$dest="";
$temp="<table style=\"width:100%\" class=\"tableResume\"><tr><th class=\"tableResume\">ID</th><th class=\"tableResume\">Société</th><th class=\"tableResume\">Date</th><th class=\"tableResume\">Montant (HTVA)</th><th>Date Limite de paiement</th><th class=\"tableResume\">Lien</th></tr>";
$dest=$temp;
while($row = mysqli_fetch_array($result7))
{
    $temp="<tr class=\"tableResume\"><td class=\tableResume\">".$row['ID']."</td><td class=\"tableResume\">".$row['COMPANY']."</td><td class=\"tableResume\">".substr($row['DATE'],0,10)."</td><td class=\"tableResume\">".$row['AMOUNT_HTVA']." €</td><td class=\"tableResume\">".substr($row['FACTURE_LIMIT_PAID_DATE'],0,10)."</td><td class=\"tableResume\"><a href=\"https://www.kameobikes.com/factures/".$row['FILE_NAME']."\">Lien</a></td></tr>";
    $dest=$dest.$temp;
}

$dest=$dest."</table>";

$resultat6 = mysqli_fetch_assoc($result6);

$dest=$dest."<p>Valeur totale des factures non payées mais pas en retard de paiement: ".round($resultat6['SOMME'])." €</p>";


$body=$body.$dest;

$body=$body."<br /><br /><h3>Factures à payer</h3>
<p>Veuillez trouver ci-dessous la liste des factures que nous devons payer :<br>
<br>";

$dest="";
$temp="<table style=\"width:100%\" class=\"tableResume\"><tr><th class=\"tableResume\">ID</th><th class=\"tableResume\">Société</th><th class=\"tableResume\">Date</th><th class=\"tableResume\">Montant (HTVA)</th><th class=\"tableResume\">Lien</th></tr>";
$dest=$temp;
while($row = mysqli_fetch_array($result8)){
    $temp="<tr class=\"tableResume\"><td class=\tableResume\">".$row['ID']."</td><td class=\"tableResume\">".$row['BENEFICIARY_COMPANY']."</td><td class=\"tableResume\">".substr($row['DATE'],0,10)."</td><td class=\"tableResume\">".$row['AMOUNT_HTVA']." €</td><td class=\"tableResume\"><a href=\"www.kameobikes.com/factures/".$row['FILE_NAME']."\">Lien</a></td></tr>";
    $dest=$dest.$temp;
}

$dest=$dest."</table>";

$resultat9 = mysqli_fetch_assoc($result9);

$dest=$dest."<p>Valeur totale des factures à payer: ".round($resultat9['SOMME'])." €</p>";


$body=$body.$dest;


$body=$body."</table><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"mcnButtonBlock\" style=\"min-width:100%;\">
</table><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"mcnTextBlock\" style=\"min-width:100%;\">
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
?>
<!-- CONTENT -->
<section class="content">
    <div class="container">
        <div class="row">
            <!-- post content -->
            <div class="post-content float-right col-md-9">
                <!-- Post item-->
                <div class="post-item">
                    <div class="post-content-details">
                        <div class="heading heading text-left m-b-20">
                            <h2>Mail automatique - Vue sur les factures</h2>
                        </div>
                        <?php echo $table; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="loader"><!-- Place at bottom of page --></div>

<?php include 'include/footer.php'; ?>

</div>
<!-- END: WRAPPER -->

<!-- Theme Base, Components and Settings -->
<script src="js/theme-functions.js"></script>

</body>
<?php
$conn->close();
?>
<?php

if(constant('ENVIRONMENT')=="production" || constant('ENVIRONMENT')=="test"){
  if(!$mail->Send()) {
      $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
      echo json_encode($response);
      die;
  }
}


?>
</html>
