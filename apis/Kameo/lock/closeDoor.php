<?php
error_log("--------------------------------------------------------------------------------------- \n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - closeDoor.php - building :".$_GET['building']."\n", 3, "logs/logs_boxes.log");

$reservationID=isset($_GET['reservationID']) ? $_GET['reservationID'] : NULL;

if($reservationID){
  error_log(date("Y-m-d H:i:s")." - closeDoor.php - reservationID :".$reservationID."\n", 3, "logs/logs_boxes.log");
}


include '../globalfunctions.php';
execSQL("UPDATE boxes SET HEU_MAJ=CURRENT_TIMESTAMP, DOOR='Closed', OPEN_UPDATE_TIME=CURRENT_TIMESTAMP WHERE building=?", array("s", $_GET['building']), true);
$lastBooking = execSQL("SELECT RESERVATION_ID FROM reservations_details WHERE ACTION IN ('verifier_code', 'vérifier_rfid') AND BUILDING = ? ORDER BY ID DESC", array('s', $_GET['building']), false);
$lastBookingID = $lastBooking[0]['RESERVATION_ID'];
$company=execSQL("SELECT COMPANY FROM boxes WHERE BUILDING=?", array('s', $_GET['building']), false)[0]['COMPANY'];
execSQL("INSERT INTO reservations_details (ACTION, RESERVATION_ID, BUILDING, OUTCOME) VALUES (?, ?, ?, ?)", array('siss', 'close_door', $lastBookingID, $_GET['building'], 'OK'), true);

$boxID=execSQL("SELECT ID FROM boxes WHERE BUILDING=?", array('s', $_GET['building']), false)[0]['ID'];
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

return true;
?>
