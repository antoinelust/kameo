<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

$token = getBearerToken();
log_inputs($token);

if(!get_user_permissions("search", $token)){
  error_message('403');
}
$bookingID=isset($_POST['bookingID']) ? $_POST['bookingID'] : NULL;
$newDateStart=isset($_POST['newDateStart']) ? $_POST['newDateStart'] : NULL;
execSQL("UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='updateStartDateBooking', DATE_START_2 = ? WHERE ID=?", array("si", $newDateStart, $bookingID), true);
execSQL("UPDATE notifications SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='updateStartDateBooking', STAAN='D' WHERE TYPE_ITEM=? AND TYPE='lateBookingNextUserNewHour'", array("i", $bookingID), true);

successMessage("SM0029");
?>
