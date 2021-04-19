<?php

$name=isset($_POST['name']) ? $_POST['name'] : null;
$bookingDays=isset($_POST['daysInAdvance']) ? $_POST['daysInAdvance'] : null;
$bookingLength=isset($_POST['bookingLength']) ? $_POST['bookingLength'] : null;
$startHourIntakeBooking=isset($_POST['startIntakeBooking']) ? $_POST['startIntakeBooking'] : null;
$endHourIntakeBooking=isset($_POST['endIntakeBooking']) ? $_POST['endIntakeBooking'] : null;
$startHourDepositBooking=isset($_POST['startDepositBooking']) ? $_POST['startDepositBooking'] : null;
$endHourDepositBooking=isset($_POST['endDepositBooking']) ? $_POST['endDepositBooking'] : null;
$intakeMonday=isset($_POST['intakeBookingMonday']) ? "1" : "0";
$intakeTuesday=isset($_POST['intakeBookingTuesday']) ? "1" : "0";
$intakeWednesday=isset($_POST['intakeBookingWednesday']) ? "1" : "0";
$intakeThursday=isset($_POST['intakeBookingThursday']) ? "1" : "0";
$intakeFriday=isset($_POST['intakeBookingFriday']) ? "1" : "0";
$intakeSaturday=isset($_POST['intakeBookingSaturday']) ? "1" : "0";
$intakeSunday=isset($_POST['intakeBookingSunday']) ? "1" : "0";
$depositMonday=isset($_POST['depositBookingMonday']) ? "1" : "0";
$depositTuesday=isset($_POST['depositBookingTuesday']) ? "1" : "0";
$depositWednesday=isset($_POST['depositBookingWednesday']) ? "1" : "0";
$depositThursday=isset($_POST['depositBookingThursday']) ? "1" : "0";
$depositFriday=isset($_POST['depositBookingFriday']) ? "1" : "0";
$depositSaturday=isset($_POST['depositBookingSaturday']) ? "1" : "0";
$depositSunday=isset($_POST['depositBookingSunday']) ? "1" : "0";
$email=isset($_POST['email']) ? $_POST['email'] : null;
$maximumBookingsYear=isset($_POST['bookingsPerYear']) ? $_POST['bookingsPerYear'] : null;
$maximumBookingsMonth=isset($_POST['bookingsPerMonth']) ? $_POST['bookingsPerMonth'] : null;
$minutesBeforeCancellation=isset($_POST['minutesBeforeCancellation']) ? $_POST['minutesBeforeCancellation'] : NULL;
$booking=isset($_POST['booking']) ? "Y" : "N";
$box=isset($_POST['box']) ? "Y" : "N";
$action=isset($_POST['action']) ? $_POST['action'] : null;
$id=isset($_POST['id']) ? $_POST['id'] : null;
$email=isset($_POST['email']) ? $_POST['email'] : null;


$resultat=execSQL("select * from customer_referential where EMAIL = ?", array('s', $email), false)[0];
$company=$resultat['COMPANY'];
$result=execSQL("select * from conditions where COMPANY = ? and name=?", array('ss', $company, $name), false);
if(!is_null($result)){
    errorMessage("ES0052");
}
execSQL("INSERT INTO conditions (USR_MAJ, HEU_MAJ, NAME, BOOKING, BOOKING_DAYS, BOOKING_LENGTH, HOUR_START_INTAKE_BOOKING, HOUR_END_INTAKE_BOOKING, HOUR_START_DEPOSIT_BOOKING, HOUR_END_DEPOSIT_BOOKING, MONDAY_INTAKE, TUESDAY_INTAKE, WEDNESDAY_INTAKE, THURSDAY_INTAKE, FRIDAY_INTAKE, SATURDAY_INTAKE, SUNDAY_INTAKE, MONDAY_DEPOSIT, TUESDAY_DEPOSIT, WEDNESDAY_DEPOSIT, THURSDAY_DEPOSIT, FRIDAY_DEPOSIT, SATURDAY_DEPOSIT, SUNDAY_DEPOSIT, COMPANY, ASSISTANCE, LOCKING, MAX_BOOKINGS_YEAR, MAX_BOOKINGS_MONTH) VALUE('$token', CURRENT_TIMESTAMP, '$name', '$booking', '$bookingDays', '$bookingLength', '$startHourIntakeBooking', '$endHourIntakeBooking', '$startHourDepositBooking', '$endHourDepositBooking', '$intakeMonday', '$intakeTuesday', '$intakeWednesday', '$intakeThursday', '$intakeFriday', '$intakeSaturday', '$intakeSunday', '$depositMonday', '$depositTuesday', '$depositWednesday', '$depositThursday', '$depositFriday', '$depositSaturday', '$depositSunday', '$company', 'N', 'N', '$maximumBookingsYear', '$maximumBookingsMonth')", array(), true);

$id=execSQL("select ID from conditions where COMPANY=? and NAME=?", array('ss', $company, $name), false)[0]['ID'];
if(isset($_POST['userAccess'])){
    foreach($_POST['userAccess'] as $valueInArray){
        execSQL("UPDATE specific_conditions SET STAANN='D' WHERE EMAIL=?", array('s', $email), true);
        execSQL("INSERT INTO specific_conditions (USR_MAJ, HEU_MAJ, COMPANY, EMAIL, CONDITION_REFERENCE, STAANN) VALUE(?, CURRENT_TIMESTAMP, ?, ?, ?, '')", array('sssi', $token, $company, $valueInArray, $id), true);
    }
}

successMessage("SM0003");


?>
