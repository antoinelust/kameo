<?php
require_once dirname(__FILE__).'/../vendor/autoload.php';

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';

require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';

require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();
$mailAcessory = new PHPMailer();


$brandUtilisation=array();
$brandUtilisation[0]['brand'] = 'Conway';
$brandUtilisation[0]['utilisation'] = 'Ville et chemin';
$brandUtilisation[0]['limite'] = 10;

$brandUtilisation[1]['brand'] = 'Conway';
$brandUtilisation[1]['utilisation'] = 'Ville';
$brandUtilisation[1]['limite'] = 10;

$brandUtilisation[2]['brand'] = 'Conway';
$brandUtilisation[2]['utilisation'] = 'Tout chemin';
$brandUtilisation[2]['limite'] = 10;

$brandUtilisation[3]['brand'] = 'Conway';
$brandUtilisation[3]['utilisation'] = 'Gravel';
$brandUtilisation[3]['limite'] = 10;

$brandUtilisation[4]['brand'] = 'Conway';
$brandUtilisation[4]['utilisation'] = 'VTT';
$brandUtilisation[4]['limite'] = 10;

$brandUtilisation[5]['brand'] = 'Ahooga';
$brandUtilisation[5]['utilisation'] = 'Pliant';
$brandUtilisation[5]['limite'] = 10;

$brandUtilisation[6]['brand'] = 'Ahooga';
$brandUtilisation[6]['utilisation'] = 'Ville';
$brandUtilisation[6]['limite'] = 10;


$brandUtilisation[6]['brand'] = 'Benno';
$brandUtilisation[6]['utilisation'] = 'Ville';
$brandUtilisation[6]['limite'] = 10;


$brandUtilisation[7]['brand'] = 'Bzen';
$brandUtilisation[7]['utilisation'] = 'Ville';
$brandUtilisation[7]['limite'] = 10;


$brandUtilisation[8]['brand'] = 'Douze cycle';
$brandUtilisation[8]['utilisation'] = 'Cargo';
$brandUtilisation[8]['limite'] = 10;


$brandUtilisation[9]['brand'] = 'HNF Nicolai';
$brandUtilisation[9]['utilisation'] = 'Ville';
$brandUtilisation[9]['limite'] = 10;

$brandUtilisation[10]['brand'] = 'HNF Nicolai';
$brandUtilisation[10]['utilisation'] = 'Ville et chemin';
$brandUtilisation[10]['limite'] = 10;

$brandUtilisation[11]['brand'] = 'HNF Nicolai';
$brandUtilisation[11]['utilisation'] = 'Speedpedelec';
$brandUtilisation[11]['limite'] = 10;


$brandUtilisation[12]['brand'] = 'Kayza';
$brandUtilisation[12]['utilisation'] = 'Ville et chemin';
$brandUtilisation[12]['limite'] = 10;

$brandUtilisation[13]['brand'] = 'Kayza';
$brandUtilisation[13]['utilisation'] = 'Tout chemin';
$brandUtilisation[13]['limite'] = 10;

$brandUtilisation[14]['brand'] = 'Kayza';
$brandUtilisation[14]['utilisation'] = 'VTT';
$brandUtilisation[14]['limite'] = 10;


$brandUtilisation[15]['brand'] = 'Moustache Bikes';
$brandUtilisation[15]['utilisation'] = 'Speedpedelec';
$brandUtilisation[15]['limite'] = 10;

$brandUtilisation[16]['brand'] = 'Moustache Bikes';
$brandUtilisation[16]['utilisation'] = 'Ville';
$brandUtilisation[16]['limite'] = 10;

$brandUtilisation[17]['brand'] = 'Moustache Bikes';
$brandUtilisation[17]['utilisation'] = 'Tout chemin';
$brandUtilisation[17]['limite'] = 10;

$brandUtilisation[18]['brand'] = 'Moustache Bikes';
$brandUtilisation[18]['utilisation'] = 'VTT';
$brandUtilisation[18]['limite'] = 10;


$brandUtilisation[19]['brand'] = 'Victoria';
$brandUtilisation[19]['utilisation'] = 'Ville et chemin';
$brandUtilisation[19]['limite'] = 10;

$brandUtilisation[20]['brand'] = 'Victoria';
$brandUtilisation[20]['utilisation'] = 'Ville';
$brandUtilisation[20]['limite'] = 10;

$brandUtilisation[21]['brand'] = 'Victoria';
$brandUtilisation[21]['utilisation'] = 'Tout chemin';
$brandUtilisation[21]['limite'] = 10;

$brandUtilisation[22]['brand'] = 'Victoria';
$brandUtilisation[22]['utilisation'] = 'Pliant';
$brandUtilisation[22]['limite'] = 10;





$orderArticle=array();
$orderBike=array();
$arrayCSV=array();
//////////// Order To Bike

$cpt=0;
$sum = 0;
$size = 0;

while($cpt!=count($brandUtilisation)){

	$brandArray = $brandUtilisation[$cpt]['brand'];
	$utilisationArray = $brandUtilisation[$cpt]['utilisation'];
	$limitArray = $brandUtilisation[$cpt]['limite'];

	
	$sqlBike="SELECT * FROM bike_catalog where BRAND='$brandArray' AND UTILISATION='$utilisationArray'";
	$resultBike = mysqli_query($conn, $sqlBike);
	$i=0;
	while($rowBike = mysqli_fetch_array($resultBike)){
		$tempId = $rowBike['ID'];
		$stockMin = $rowBike['MINIMAL_STOCK'];

		$sql="SELECT * FROM customer_bikes WHERE TYPE='$tempId' AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE='order')";
		$result = mysqli_query($conn, $sql);
		$length = $result->num_rows;

		$sum = $sum + $length;
		$i++;
	}

	if($sum<$limitArray){
		$orderBike[$size]['type'] = 'bike';
		$orderBike[$size]['brand'] = $brandArray;
		$orderBike[$size]['utilisation'] = $utilisationArray;
		$orderBike[$size]['limite'] = $limitArray;
		$orderBike[$size]['numberOfArticle'] = $sum;
		$orderBike[$size]['numberOfArticleToOrder'] = $limitArray-$sum;
		$size++;
	}

	$cpt++;
	$sum=0;
}

$sqlAccessory="SELECT * FROM accessories_catalog";
$resultAccessory = mysqli_query($conn, $sqlAccessory);
$size = 0;

while($rowAccessory = mysqli_fetch_array($resultAccessory)){
	$tempId = $rowAccessory['ID'];
	$stockMin = $rowAccessory['MINIMAL_STOCK'];
	$idCategory = $rowAccessory['ACCESSORIES_CATEGORIES'];

	$sql="SELECT * FROM accessories_stock WHERE CATALOG_ID='$tempId' AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE='order')";
	$result = mysqli_query($conn, $sql);
	$length = $result->num_rows;

	if($length<$stockMin){
		
		$sqlCategory="SELECT * FROM accessories_categories WHERE ID='$idCategory'";
		$resultCategory = mysqli_query($conn, $sqlCategory);
		$rowCategory = mysqli_fetch_assoc($resultCategory);

		$orderArticle[$size]['type'] = 'accessory';
		$orderArticle[$size]['provider'] = $rowAccessory['PROVIDER'];
		$orderArticle[$size]['brand'] = $rowAccessory['BRAND'];
		$orderArticle[$size]['reference'] = $rowAccessory['REFERENCE'];
		$orderArticle[$size]['category'] = $rowCategory['CATEGORY'];
		$orderArticle[$size]['limite'] = $stockMin;
		$orderArticle[$size]['price'] = $rowAccessory['BUYING_PRICE'];
		$orderArticle[$size]['numberOfArticleToOrder'] = $stockMin-$length;
		$size++;
	}
}

if(constant('ENVIRONMENT') == 'production' || constant('ENVIRONMENT') == 'test'){
	$mail->AddAddress('antoine.lust@kameobikes.com', 'Antoine Lust');
	$mailAcessory->AddAddress('antoine.lust@kameobikes.com', 'Antoine Lust');
                                //$mail->AddAddress('julien.jamar@kameobikes.com', 'Julien Jamar');
                                //$mail->AddAddress("thibaut.mativa@kameobikes.com");
                                //$mail->AddAddress("pierre-yves.adant@kameobikes.com");
}


        $mail->IsHTML(true);                                    // Set email format to HTML
        $mail->CharSet = 'UTF-8';

        $mail->From = "info@kameobikes.com";
        $mail->FromName = "Kameo Bikes";
        $mail->AddAddress('younes.chillah@kameobikes.com', 'Younes Chillah');
        $mail->AddReplyTo("info@kameobikes.com");
        $mail->Subject = "Aperçu des vélos nécessitant une commande";


        $mailAcessory->IsHTML(true);                                    // Set email format to HTML
        $mailAcessory->CharSet = 'UTF-8';

        $mailAcessory->From = "info@kameobikes.com";
        $mailAcessory->FromName = "Kameo Bikes";
        $mailAcessory->AddAddress('younes.chillah@kameobikes.com', 'Younes Chillah');
        $mailAcessory->AddReplyTo("info@kameobikes.com");
        $mailAcessory->Subject = "Aperçu des vélos nécessitant une commande";

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

        <td valign=\"top\" class=\"mcnTextContent\" style=\"padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;\">";

        $dest="<h3>Commande des différents vélo a effectué  : &nbsp;</h3><br>
        <table class=\"table table-condensed\"><tbody><thead><tr><th><span class=\"fr-inline\"> Marque du vélo</span></th><th><span class=\"fr-inline\">Utilisation</span></th><th><span class=\"fr-inline\">Limite minimal de stock </span></th><th><span class=\"fr-inline\"> Nombre de vélo à commander</span></th></tr></thead>";
        $i=0;
        while($i<count($orderBike)){
        	$temp="<tr><td>".$orderBike[$i]['brand']."</td><td>".$orderBike[$i]['utilisation']."</td><td>".$orderBike[$i]['limite']."</td><td>".$orderBike[$i]['numberOfArticleToOrder']."</td></tr>";
        	$dest=$dest.''.$temp;
        	$i++;
        }
        $dest=$dest."</tbody></table>";

        $destAcessory="<h3>Commande des différents accessoires a effectué  : &nbsp;</h3><br>
        <table class=\"table table-condensed\"><tbody><thead><tr><th><span class=\"fr-inline\"> Marque de l'accessoire</span></th><th><span class=\"fr-inline\">Catgorie</span></th><th><span class=\"fr-inline\">Limite minimal de stock </span></th><th><span class=\"fr-inline\"> Nombre d'accessoire à commander</span></th></tr></thead>";
        $i=0;
        while($i<count($orderArticle)){
        	if($orderArticle[$i]['provider']!='Hartje'){
        		$temp="<tr><td>".$orderArticle[$i]['brand']."</td><td>".$orderArticle[$i]['category']."</td><td>".$orderArticle[$i]['limite']."</td><td>".$orderArticle[$i]['numberOfArticleToOrder']."</td></tr>";
        		$destAcessory=$destAcessory.''.$temp;
        	}
        	$i++;
        }
        $destAcessory=$destAcessory."</tbody></table>";            



        $body2 = "
        <br>
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

        $mail->Body = $body.''.$dest.''.$body2;
        $mailAcessory->Body = $body.''.$destAcessory.''.$body2;

        if(constant('ENVIRONMENT')=="test" || constant('ENVIRONMENT')=="production"){
        	if(!$mail->Send()) {
        		$response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);

        	}else {
        		$response = array ('response'=>'success', 'message'=> "Nous avons bien reçu votre message et nous reviendrons vers vous dès que possible.");
        	}

        	if(!$mailAcessory->Send()) {
        		$response = array ('response'=>'error', 'message'=> $mailAcessory->ErrorInfo);
        	}else {
        		$response = array ('response'=>'success', 'message'=> "Nous avons bien reçu votre message et nous reviendrons vers vous dès que possible.");
        	}
        }else{
        	$response = array ('response'=>'success', 'message'=> "Environnement local, mail non envoyé");
        }
        echo json_encode($response);


        $i=0;
        $size=0;
        while($i<count($orderArticle)){
        	if($orderArticle[$i]['provider']=='Hartje'){
        		$arrayCSVTemp = array('358783',$orderArticle[$i]['reference'],$orderArticle[$i]['numberOfArticleToOrder']);
        		array_push($arrayCSV,$arrayCSVTemp);
        	}
        	$i++;
        }

        file_put_contents('AccessoryOrder'.date('Y').'.'.date('m').'.'.date('d').'.csv','');

        $nameFile='AccessoryOrder'.date('Y').'.'.date('m').'.'.date('d').'.csv';
        $monfichier = fopen(''.$nameFile, 'w+');
        $header=null;
        
        foreach($arrayCSV as $t)
        {
        	if(!$header) {
        		$arrayCSVTempLine=array('Numero de Client','Numero article','Nombre article');
        		fputcsv($monfichier,$arrayCSVTempLine,";");
        		$arrayCSVTempLine=array('');
        		fputcsv($monfichier,$arrayCSVTempLine,";");
        		
        		$header = true;
        	}
        	fputcsv($monfichier,$t,";");
        	
        }
        fclose ($monfichier);
        ?>

