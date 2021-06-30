<?php
error_log("--------------------------------------------------------------------------------------- \n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - updateBoxStatus.php - building :".$_GET['building']."\n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - updateBoxStatus.php - door :".$_GET['door']."\n", 3, "logs/logs_boxes.log");

include '../globalfunctions.php';


$boxID=execSQL("SELECT ID FROM boxes WHERE BUILDING=?", array('s', $_GET['building']), false)[0]['ID'];

if($_GET['door']=='1'){
  execSQL("UPDATE boxes SET HEU_MAJ=CURRENT_TIMESTAMP, DOOR='Open', RESERVATION_ID=NULL, OPEN_UPDATE_TIME=CURRENT_TIMESTAMP WHERE building=?", array("s", $_GET['building']), true);
}else{
  execSQL("UPDATE boxes SET HEU_MAJ=CURRENT_TIMESTAMP, DOOR='Closed', RESERVATION_ID=NULL, OPEN_UPDATE_TIME=CURRENT_TIMESTAMP WHERE building=?", array("s", $_GET['building']), true);
  $result=execSQL("SELECT TYPE FROM notifications WHERE TYPE_ITEM=? AND TYPE in ('openDoor', 'closedDoor') ORDER BY DATE DESC LIMIT 1", array('s', $boxID), false);
  if(count($result) != 0){
    if($result[0]['TYPE']=="openDoor"){
      execSQL("INSERT INTO `notifications` ( `HEU_MAJ`, `USR_MAJ`, `DATE`, `TEXT`, `READ`, `TYPE`, `USER_ID`, `TYPE_ITEM`, `STAAN`) VALUES (CURRENT_TIMESTAMP, 'closeDoor.php', CURRENT_TIMESTAMP, 'envoi mail pour porte refermée', 'N', 'closedDoor', '0', ?, NULL)", array('i', $boxID), true);
      require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
      $mail = new PHPMailer();
      $mail->IsHTML(true);                                    // Set email format to HTML
      $mail->CharSet = 'UTF-8';
      $mail->From = "info@kameobikes.com";
      $mail->FromName = "Info Kameo Bikes";
      $mail->AddReplyTo('info@kameobikes.com', 'Information Kameo Bikes');
      $subject = "The door of the box has been closed";
      $mail->Subject = $subject;

      echo "sujet : ".$subject."\n";

      include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';
      include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_closeDoor.php';
      include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_footer.php';

      echo $body;

      $mail->Body = $body;
      if(constant('ENVIRONMENT')=='production' || constant('ENVIRONMENT') == "test"){
        if(!$mail->Send()) {
           echo "ERROR : ".$mail->ErrorInfo;
           die;
        }
      }else{
        echo "Environnement localhost, mail non envoyé \n";
      }

    }
  }
}

echo "success";

return true;
?>
