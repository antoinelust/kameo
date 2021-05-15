<?php

// Form Fields
$id = $_POST["ID"];


if(!isset($_POST['beneficiaries'])){
  echo json_encode(array('response' => 'error', 'message' => 'Au moins un destinataire doit être défini'));
  die;
}


$monthFR=array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');

$resultat=execSQL("select factures.COMPANY, factures.BILLING_GROUP, companies.COMPANY_NAME, factures.DATE, factures.FILE_NAME from factures, companies where factures.ID=? AND factures.COMPANY=companies.INTERNAL_REFERENCE", array ('i', $id), false)[0];
$internalReference=$resultat['COMPANY'];
$billingGroup=$resultat['BILLING_GROUP'];
$companyName=$resultat['COMPANY_NAME'];
$date=new DateTime($resultat['DATE']);

require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();
$mail->IsHTML(true);                                    // Set email format to HTML
$mail->CharSet = 'UTF-8';


$mail->From = 'invoices@kameobikes.com';
$mail->FromName = 'Invoices Kameo Bikes';
$mail->AddReplyTo('invoices@kameobikes.com', 'Invoices Kameo Bikes');
$mail->Subject = 'Kameo Bikes - '. $companyName .' - Facture de '.$monthFR[($date->format('m')-1)].' '.$date->format('Y');

$temp=$monthFR[($date->format('m')-1)].' '.$date->format('Y');
$message="Bonjour,<br><br>

Veuillez trouver en pièce jointe la facture Kameo Bikes pour le mois de $temp.<br>
Pour toute question, n'hésitez pas à nous contacter.<br><br>

Bien à vous,<br><br>

L'équipe Kameo Bikes";

$file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/factures/'.$resultat['FILE_NAME'];


$mail->AddAttachment( $file_to_attach , $resultat['FILE_NAME'] );

$mail->Body = $message;
if(constant('ENVIRONMENT')=='production' || constant('ENVIRONMENT')=='test'){

  foreach($_POST['beneficiaries'] as $beneficiary){
    $mail->AddAddress($beneficiary['email'], $beneficiary['firstName']." ".$beneficiary['name']);
  }
  if(isset($_POST['ccs'])){
    foreach($_POST['ccs'] as $cc){
      $mail->AddCC($cc['email'], $cc['firstName']." ".$cc['name']);
    }
  }
}else{
  $mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
}
$mail->AddBCC("invoices@kameobikes.com", "Invoices Kameo Bikes");

if(constant('ENVIRONMENT')=='production' || constant('ENVIRONMENT')=='test'){
    if(!$mail->Send()) {
       $response=array();
       $response['response']="error";
       $response['message']=error_get_last()['message'];
    }else{
        $now=new DateTime('now');
        $nowString=$now->format('Y-m-d H:i');
        execSQL("update factures set FACTURE_SENT = '1', FACTURE_SENT_DATE=? WHERE ID=?", array('si', $nowString, $id), true);
        successMessage("SM0026");
    }
}else{
		$response = array ('response'=>'success', 'message'=> "Société ".$companyName."<br><strong>environnement localhost, mail non envoyé</strong>");
		echo json_encode($response);
		die;
}





?>
