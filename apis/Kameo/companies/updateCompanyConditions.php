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


$response=array();

if($name != NULL && $bookingDays != NULL && $bookingLength != NULL && $startHourIntakeBooking != NULL && $endHourIntakeBooking != NULL && $startHourDepositBooking != NULL && $endHourDepositBooking != NULL && $intakeMonday != NULL && $intakeTuesday != NULL && $intakeWednesday != NULL && $intakeThursday != NULL && $intakeFriday != NULL && $intakeSaturday != NULL && $intakeSunday != NULL && $depositMonday != NULL && $depositTuesday != NULL && $depositWednesday != NULL && $depositThursday != NULL && $depositFriday != NULL && $depositSaturday != NULL && $depositSunday != NULL && $email != NULL && $maximumBookingsYear != NULL && $maximumBookingsMonth != NULL)
{
	$resultat=execSQL("select * from customer_referential where EMAIL = ?", array('s', $email), false)[0];
  $company=$resultat['COMPANY'];
  $result=execSQL("SELECT * FROM specific_conditions WHERE CONDITION_REFERENCE=? AND STAANN!='D'", array('i', $id), false);
  if(!is_null($result)){
    foreach($result as $row){
      $presence=false;
      $emailtemp=$row['EMAIL'];
      if(isset($_POST['userAccess'])){
          foreach($_POST['userAccess'] as $valueInArray){
              if($row['EMAIL']==$valueInArray){
                  $presence=true;
              }
          }
      }
      if($presence==false){
        execSQL("update specific_conditions set STAANN='D', USR_MAJ=?, HEU_MAJ=CURRENT_TIMESTAMP where CONDITION_REFERENCE = ? and EMAIL=?", array('sis', $token, $id, $emailtemp), true);
      }
    }
  }

  $resultCondition=execSQL("select * from conditions where ID=?", array('i', $id), false);
  $name=$resultCondition[0]['NAME'];

  if(is_null($resultCondition)){
      errorMessage("ES0053");
  }else{
      execSQL("update conditions set USR_MAJ = 'mykameo', HEU_MAJ = CURRENT_TIMESTAMP, BOOKING='$booking', BOOKING_DAYS='$bookingDays', BOOKING_LENGTH='$bookingLength', HOUR_START_INTAKE_BOOKING = '$startHourIntakeBooking', HOUR_END_INTAKE_BOOKING = '$endHourIntakeBooking', HOUR_START_DEPOSIT_BOOKING = '$startHourDepositBooking', HOUR_END_DEPOSIT_BOOKING = '$endHourDepositBooking', MONDAY_INTAKE = '$intakeMonday', TUESDAY_INTAKE = '$intakeTuesday', WEDNESDAY_INTAKE = '$intakeWednesday', THURSDAY_INTAKE = '$intakeThursday', FRIDAY_INTAKE = '$intakeFriday', SATURDAY_INTAKE = '$intakeSaturday', SUNDAY_INTAKE = '$intakeSunday', MONDAY_DEPOSIT = '$depositMonday', TUESDAY_DEPOSIT = '$depositTuesday', WEDNESDAY_DEPOSIT = '$depositWednesday', THURSDAY_DEPOSIT = '$depositThursday', FRIDAY_DEPOSIT = '$depositFriday', SATURDAY_DEPOSIT = '$depositSaturday', SUNDAY_DEPOSIT = '$depositSunday', MAX_BOOKINGS_YEAR='$maximumBookingsYear', MAX_BOOKINGS_MONTH='$maximumBookingsMonth', LOCKING='$box', MINUTES_FOR_AUTOMATIC_CANCEL='$minutesBeforeCancellation' WHERE ID = '$id'", array(), true);
  }

  if(isset($_POST['userAccess']) && $name!='generic'){
      foreach($_POST['userAccess'] as $valueInArray){
        execSQL("UPDATE specific_conditions SET STAANN='D' WHERE EMAIL=? AND COMPANY=? AND CONDITION_REFERENCE!=?", array('ssi', $valueInArray, $company, $id), true);

        $resultatSpecificCondition=execSQl("select * from specific_conditions WHERE email=? and CONDITION_REFERENCE=?", array('si', $valueInArray, $id), false);
        if(is_null($resultatSpecificCondition)){
          execSQL("INSERT INTO specific_conditions (USR_MAJ, HEU_MAJ, COMPANY, EMAIL, CONDITION_REFERENCE, STAANN) VALUE('$email', CURRENT_TIMESTAMP, '$company', '$valueInArray', '$id', '')", array(), true);
        }else{
          execSQL("UPDATE specific_conditions SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, STAANN='' WHERE CONDITION_REFERENCE=? AND EMAIL=? AND COMPANY=?", array('siss', $token, $id, $valueInArray, $company), true);
        }
      }
  }else if(isset($_POST['userAccess'])){
    foreach($_POST['userAccess'] as $valueInArray){
      execSQL("UPDATE specific_conditions SET STAANN='D' WHERE EMAIL=?", array('s', $valueInArray), true);
    }
  }
  successMessage("SM0003");
}
else
{
	errorMessage("ES0012");
}

?>
