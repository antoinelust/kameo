<?php
error_log("--------------------------------------------------------------------------------------- \n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - updateBoxStatus.php - building :".$_GET['building']."\n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - updateBoxStatus.php - door :".$_GET['door']."\n", 3, "logs/logs_boxes.log");
error_log(date("Y-m-d H:i:s")." - updateBoxStatus.php - key :".$_GET['key']."\n", 3, "logs/logs_boxes.log");

include '../globalfunctions.php';

echo "ok";

return true;
?>
