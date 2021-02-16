<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';

$lateBookings = execSQL("SELECT aa.ID as reservationID, aa.EXTENSIONS, aa.BIKE_ID, bb.ID as customerID, bb.COMPANY, aa.DATE_END_2, bb.EMAIL FROM reservations aa, customer_referential bb WHERE aa.STATUS='Open' AND aa.STAANN != 'D' AND aa.DATE_END_2 < CURRENT_TIMESTAMP AND aa.EMAIL=bb.EMAIL AND EXISTS (SELECT 1 FROM locking_bikes WHERE locking_bikes.RESERVATION_ID = aa.ID AND locking_bikes.PLACE_IN_BUILDING='-1') AND NOT EXISTS (SELECT 1 FROM notifications cc WHERE cc.TYPE='lateBooking' and cc.TYPE_ITEM=aa.ID AND cc.TEXT=aa.EXTENSIONS)", array(), false);
foreach ((array) $lateBookings as $lateBooking) {
  $reservationEnd = new DateTime($lateBooking['DATE_END_2'], new DateTimeZone('Europe/Brussels'));
  $minutes=intval(getCondition($lateBooking['COMPANY'])['conditions']['MINUTES_FOR_AUTOMATIC_WARNING_LATE_BOOKING']);
  echo "----------------------------------"."\n";
  echo "Mail : ".$lateBooking['EMAIL']."\n";
  echo "Company : ".$lateBooking['COMPANY']."\n";
  echo "Minutes : ".$minutes."\n";
  echo "ID réservation : ".$lateBooking['reservationID']."\n";
  echo "Fin de réservation : ".$lateBooking['DATE_END_2']."\n";
  echo "Extension : ".$lateBooking['EXTENSIONS']."\n";
  $company = $lateBooking['COMPANY'];

  $time = new DateTime('now', new DateTimeZone('Europe/Brussels'));
  $time->sub(new DateInterval('PT' . $minutes . 'M'));
  if($reservationEnd < $time){
    echo "MAIL - Génération du mail car l'heure actuelle est supérieure de ".$minutes." minutes à l'heure de fin de la réservation \n";
    execSQL("INSERT INTO `notifications` ( `HEU_MAJ`, `USR_MAJ`, `DATE`, `TEXT`, `READ`, `TYPE`, `USER_ID`, `TYPE_ITEM`, `STAAN`) VALUES (CURRENT_TIMESTAMP, 'identifyLateBooking.php', CURRENT_TIMESTAMP, ?, 'N', 'lateBooking', ?, ?, NULL)", array('sii', $lateBooking['EXTENSIONS'], $lateBooking['customerID'], $lateBooking['reservationID']), true);
    $bikeID = $lateBooking['BIKE_ID'];
    include 'sendMailLateBooking.php';
  }else{
    echo "BYPASS - Pas de génération du mail car l'heure actuelle n'est pas supérieure de ".$minutes." minutes à l'heure de fin de la réservation \n";
  }
}
echo "success";
die;
