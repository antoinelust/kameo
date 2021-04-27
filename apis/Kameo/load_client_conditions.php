<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

require_once __DIR__ .'/globalfunctions.php';
require_once __DIR__ .'/authentication.php';
$token = getBearerToken();


$email=$_POST['email'];

if($token != NULL)
{
  $company=execSQL("select COMPANY from customer_referential where TOKEN = ?", array('s', $token), false)[0]['COMPANY'];
  $email=execSQL("select EMAIL from customer_referential where TOKEN = ?", array('s', $token), false)[0]['EMAIL'];
  $resultat=execSQL("select * from specific_conditions where COMPANY = ? AND EMAIL=? AND STAANN != 'D'", array('ss', $company, $email), false)[0];

  if(is_null($resultat)){
    $resultat=execSQL("select * from conditions where COMPANY = ?", array('s', $company), false)[0];
  }else{
    $conditionReference=$resultat['CONDITION_REFERENCE'];
    $resultat=execSQL("select * from conditions where ID = ?", array('i', $conditionReference), false)[0];
  }

    //vérifier nom du champ SQL
	$response['response']="success";
  $response['clientConditions']['bookingDays']=$resultat['BOOKING_DAYS'];
  $response['clientConditions']['bookingLength']=$resultat['BOOKING_LENGTH'];
  $response['clientConditions']['assistance']=$resultat['ASSISTANCE'];
  $response['clientConditions']['hourStartIntakeBooking']=$resultat['HOUR_START_INTAKE_BOOKING'];
  $response['clientConditions']['hourEndIntakeBooking']=$resultat['HOUR_END_INTAKE_BOOKING'];
  $response['clientConditions']['hourStartDepositBooking']=$resultat['HOUR_START_DEPOSIT_BOOKING'];
  $response['clientConditions']['hourEndDepositBooking']=$resultat['HOUR_END_DEPOSIT_BOOKING'];
  $response['clientConditions']['locking']=$resultat['LOCKING'];
  $response['clientConditions']['mondayIntake']=$resultat['MONDAY_INTAKE'];
  $response['clientConditions']['tuesdayIntake']=$resultat['TUESDAY_INTAKE'];
  $response['clientConditions']['wednesdayIntake']=$resultat['WEDNESDAY_INTAKE'];
  $response['clientConditions']['thursdayIntake']=$resultat['THURSDAY_INTAKE'];
  $response['clientConditions']['fridayIntake']=$resultat['FRIDAY_INTAKE'];
  $response['clientConditions']['saturdayIntake']=$resultat['SATURDAY_INTAKE'];
  $response['clientConditions']['sundayIntake']=$resultat['SUNDAY_INTAKE'];
  $response['clientConditions']['mondayDeposit']=$resultat['MONDAY_DEPOSIT'];
  $response['clientConditions']['tuesdayDeposit']=$resultat['TUESDAY_DEPOSIT'];
  $response['clientConditions']['wednesdayDeposit']=$resultat['WEDNESDAY_DEPOSIT'];
  $response['clientConditions']['thursdayDeposit']=$resultat['THURSDAY_DEPOSIT'];
  $response['clientConditions']['fridayDeposit']=$resultat['FRIDAY_DEPOSIT'];
  $response['clientConditions']['saturdayDeposit']=$resultat['SATURDAY_DEPOSIT'];
  $response['clientConditions']['sundayDeposit']=$resultat['SUNDAY_DEPOSIT'];
  $response['clientConditions']['maxBookingsPerYear']=$resultat['MAX_BOOKINGS_YEAR'];
  $response['clientConditions']['maxBookingsPerMonth']=$resultat['MAX_BOOKINGS_MONTH'];
  $response['clientConditions']['cafetaria']=$resultat['CAFETARIA'];

	echo json_encode($response);
  die;

}
else
{
	errorMessage("ES0012");
}

?>
