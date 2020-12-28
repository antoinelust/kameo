<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';

$lateBookings = execSQL("SELECT aa.ID as reservationID, bb.ID as customerID, bb.COMPANY FROM reservations aa, customer_referential bb WHERE aa.STATUS='Open' AND aa.STAANN != 'D' AND aa.DATE_END_2 < CURRENT_TIMESTAMP AND aa.EMAIL=bb.EMAIL AND NOT EXISTS (SELECT 1 FROM notifications cc WHERE cc.TYPE='lateBooking' and cc.TYPE_ITEM=aa.ID)", array(), false);
foreach ((array) $lateBookings as $lateBooking) {
  execSQL("INSERT INTO `notifications` ( `HEU_MAJ`, `USR_MAJ`, `DATE`, `TEXT`, `READ`, `TYPE`, `USER_ID`, `TYPE_ITEM`, `STAAN`) VALUES (CURRENT_TIMESTAMP, 'identifyLateBooking.php', CURRENT_TIMESTAMP, '', 'N', 'lateBooking', ?, ?, NULL)", array('ii', $lateBooking['customerID'], $lateBooking['reservationID']), true);
  include 'sendMailLateBooking.php';
}

echo "success";
die;
