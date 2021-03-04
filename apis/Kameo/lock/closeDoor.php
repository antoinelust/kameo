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
execSQL("INSERT INTO reservations_details (ACTION, RESERVATION_ID, BUILDING, OUTCOME) VALUES (?, ?, ?, ?)", array('siss', 'close_door', $lastBookingID, $_GET['building'], 'OK'), true);
return true;
?>
