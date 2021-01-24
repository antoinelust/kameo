<?php
error_log("--------------------------------------------------------------------------------------- \n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - openDoor.php - building :".$_GET['building']."\n", 3, "logs/logs_boxes.log");

include '../globalfunctions.php';
execSQL("UPDATE boxes SET HEU_MAJ=CURRENT_TIMESTAMP, DOOR='Open', OPEN_UPDATE_TIME=CURRENT_TIMESTAMP WHERE building=?", array("s", $_GET['building']), true);
return true;
?>
