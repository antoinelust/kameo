<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';

$openDoors = execSQL("SELECT boxes.ID, boxes.OPEN_UPDATE_TIME, boxes.COMPANY, boxes.BUILDING FROM `boxes` WHERE COMPANY='Actiris' AND DOOR='Open' AND boxes.OPEN_UPDATE_TIME < (NOW() + INTERVAL 2 MINUTE)
AND NOT EXISTS (SELECT 1 FROM notifications WHERE notifications.TYPE_ITEM=boxes.ID AND notifications.TYPE='openDoor' AND notifications.DATE=boxes.OPEN_UPDATE_TIME)", array(), false);
foreach ((array) $openDoors as $openDoor){
  echo "----------------------------------"."\n";
  echo "Company : ".$openDoor['COMPANY']."\n";
  echo "ID de la borne : ".$openDoor['ID']."\n";
  echo "Building : ".$openDoor['BUILDING']."\n";
  echo "Date d'ouverture : ".$openDoor['OPEN_UPDATE_TIME']."\n";
  execSQL("INSERT INTO `notifications` ( `HEU_MAJ`, `USR_MAJ`, `DATE`, `TEXT`, `READ`, `TYPE`, `USER_ID`, `TYPE_ITEM`, `STAAN`) VALUES (CURRENT_TIMESTAMP, 'sendMailOpenDoor.php', ?, 'envoi mail pour porte ouverte', 'N', 'openDoor', '0', ?, NULL)", array('si', $openDoor['OPEN_UPDATE_TIME'], $openDoor['ID']), true);
  echo "environnement : ".constant('ENVIRONMENT')."\n";
  require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
  $mail = new PHPMailer();
  $mail->IsHTML(true);                                    // Set email format to HTML
  $mail->CharSet = 'UTF-8';
  $mail->From = "info@kameobikes.com";
  $mail->FromName = "Info Kameo Bikes";
  $mail->AddReplyTo('info@kameobikes.com', 'Information Kameo Bikes');
  $subject = "The door of the box is open since more than 2 minutes";
  $mail->Subject = $subject;

  echo "sujet : ".$subject."\n";

  if(constant('ENVIRONMENT') == "production"){
    if($openDoor['COMPANY']=="Actiris"){
      $mail->AddAddress("bookabike@actiris.be");
    }else{
      $mail->AddAddress("antoine@kameobikes.com");
    }
    $mail->addBcc("antoine@kameobikes.com");
  }else if(constant('ENVIRONMENT') == "test"){
    $mail->AddAddress("antoine@kameobikes.com");
  }
  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';
  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_openDoor.php';
  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_footer.php';
  $mail->Body = $body;
  if(constant('ENVIRONMENT')=='production' || constant('ENVIRONMENT') == "test"){
    if(!$mail->Send()) {
       echo "ERROR : ".$mail->ErrorInfo;
       die;
    }
  }
}
echo "success";
die;
