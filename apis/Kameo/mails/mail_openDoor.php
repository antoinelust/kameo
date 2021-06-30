<?php

if(constant('ENVIRONMENT') == "production"){
  if($openDoor['COMPANY']=="Actiris"){
    $mail->AddAddress("bookabike@actiris.be");
    $mail->addBcc("antoine@kameobikes.com");
  }else{
    $mail->AddAddress("antoine@kameobikes.com");
  }
}else if(constant('ENVIRONMENT') == "test"){
  $mail->AddAddress("antoine@kameobikes.com");
}


if($openDoor['RESERVATION_ID'] != NULL){
  $result=execSQL("SELECT customer_referential.EMAIL, customer_referential.NOM, customer_referential.PRENOM FROM customer_referential, reservations WHERE reservations.EMAIL=customer_referential.EMAIL AND reservations.ID=?", array('i', $openDoor['RESERVATION_ID']), false)[0];
  $userFR="Porte ouverte dans le cadre de la réservation de ".$result['PRENOM'].' '.$result['NOM']." (".$result['EMAIL'].")";
  $userNL="Deur open als onderdeel van de reservering van ".$result['PRENOM'].' '.$result['NOM']." (".$result['EMAIL'].")";
  $mail->AddAddress($result['EMAIL']);

}else{
  $reservationID=$openDoor['RESERVATION_ID'];
  $userFR="Nous n'avons pas pu identifer l'utilisateur ayant ouvert la porte";
  $userNL="We konden de gebruiker die de deur opendeed niet identificeren";
}


$body = $body."
    <body>
        <!--[if !gte mso 9]><!----><span class=\"mcnPreviewText\" style=\"display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;\">Accès MyKameo</span><!--<![endif]-->
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

                      <h3>Votre borne n'a pas été correctement fermée</h3>

                      <p>Vous recevez cet email car nous avons détecté que la porte de votre borne est toujours ouverte, depuis plus de 2 minutes<br/>
                      <br>
                      <strong>Identifiant de votre borne : </strong>". $openDoor['ID']." <br>
                      <strong>Ouverte depuis : </strong> ".$openDoor['OPEN_UPDATE_TIME']."<br>
                      <strong>Utilisateur : </strong>".$userFR."</p>
                    </td>
                    <td valign=\"top\" class=\"mcnTextContent\" style=\"padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;\">

                      <h3>Uw box werd niet correct gesloten</h3>

                      <p>U ontvangt deze e-mail omdat we hebben vastgesteld dat de deur van uw box nog steeds geopend is, sinds meer dan 2 minuten.<br>
                      <br>
                      <strong>ID van box : </strong> ". $openDoor['ID']." <br>
                      <strong>Open sinds : </strong> ".$openDoor['OPEN_UPDATE_TIME']."<br>
                      <strong>Utilisateur : </strong>".$userNL."</p>
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

?>
