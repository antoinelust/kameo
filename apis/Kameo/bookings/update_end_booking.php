<?php

global $conn;
$ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;
$newEndDate=isset($_POST['newEndDate']) ? $_POST['newEndDate']." ".$_POST['newEndHour'] : NULL;

if($ID==NULL){
	errorMessage("ES0012");
}else{
	$booking = execSQL("SELECT aa.BIKE_ID, aa.DATE_END_2, bb.COMPANY from reservations aa, customer_bikes bb WHERE aa.ID=? AND aa.BIKE_ID=bb.ID", array('i', $ID), false);
	$bikeID = $booking[0]['BIKE_ID'];
	$dateEnd = $booking[0]['DATE_END_2'];
	$company = $booking[0]['COMPANY'];
	$nextBooking = execSQL("SELECT aa.ID, aa.DATE_START_2, aa.EMAIL, bb.ID as customerID from reservations aa, customer_referential bb WHERE aa.EMAIL=bb.EMAIL AND aa.BIKE_ID=? AND aa.DATE_START_2 > ? AND aa.DATE_START_2 < ? AND aa.STAANN != 'D' ORDER BY aa.DATE_START_2", array('sss', $bikeID, $dateEnd, $newEndDate), false);

	if($nextBooking != null){

		$nextBookingStart = new DateTime($nextBooking[0]['DATE_START_2']);
		$newEndDate = new DateTime($newEndDate);
		if($nextBookingStart < $newEndDate){
			errorMessage("ES0066");
		}
	}
	execSQL("UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, DATE_END_2=? WHERE ID=?", array('ssi', $token, $newEndDate, $ID), true);
	successMessage("SM0006");
}
?>
