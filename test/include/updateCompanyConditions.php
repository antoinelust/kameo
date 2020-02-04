<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$phone = isset($_POST["widget-contact-form-phone"]) ? $_POST["widget-contact-form-phone"] : null;

$bookingDays=isset($_POST['companyConditionsDaysInAdvance']) ? $_POST['companyConditionsDaysInAdvance'] : null;
$bookingLength=isset($_POST['companyConditionsBookingLength']) ? $_POST['companyConditionsBookingLength'] : null;
$startHourIntakeBooking=isset($_POST['companyConditionsStartIntakeBooking']) ? $_POST['companyConditionsStartIntakeBooking'] : null;
$endHourIntakeBooking=isset($_POST['companyConditionsEndIntakeBooking']) ? $_POST['companyConditionsEndIntakeBooking'] : null;
$startHourDepositBooking=isset($_POST['companyConditionsStartDepositBooking']) ? $_POST['companyConditionsStartDepositBooking'] : null;
$endHourDepositBooking=isset($_POST['companyConditionsEndDepositBooking']) ? $_POST['companyConditionsEndDepositBooking'] : null;
$intakeMonday=isset($_POST['companyConditionsIntakeBookingMonday']) ? "1" : "0";
$intakeTuesday=isset($_POST['companyConditionsIntakeBookingTuesday']) ? "1" : "0";
$intakeWednesday=isset($_POST['companyConditionsIntakeBookingWednesday']) ? "1" : "0";
$intakeThursday=isset($_POST['companyConditionsIntakeBookingThursday']) ? "1" : "0";
$intakeFriday=isset($_POST['companyConditionsIntakeBookingFriday']) ? "1" : "0";
$intakeSaturday=isset($_POST['companyConditionsIntakeBookingSaturday']) ? "1" : "0";
$intakeSunday=isset($_POST['companyConditionsIntakeBookingSunday']) ? "1" : "0";
$depositMonday=isset($_POST['companyConditionsDepositBookingMonday']) ? "1" : "0";
$depositTuesday=isset($_POST['companyConditionsDepositBookingTuesday']) ? "1" : "0";
$depositWednesday=isset($_POST['companyConditionsDepositBookingWednesday']) ? "1" : "0";
$depositThursday=isset($_POST['companyConditionsDepositBookingThursday']) ? "1" : "0";
$depositFriday=isset($_POST['companyConditionsDepositBookingFriday']) ? "1" : "0";
$depositSaturday=isset($_POST['companyConditionsDepositBookingSaturday']) ? "1" : "0";
$depositSunday=isset($_POST['companyConditionsDepositBookingSunday']) ? "1" : "0";
$email=isset($_POST['companyConditionsEmail']) ? $_POST['companyConditionsEmail'] : null;
$maximumBookingsYear=isset($_POST['companyConditionsBookingsPerYear']) ? $_POST['companyConditionsBookingsPerYear'] : null;
$maximumBookingsMonth=isset($_POST['companyConditionsBookingsPerMonth']) ? $_POST['companyConditionsBookingsPerMonth'] : null;

$response=array();

if($bookingDays != NULL && $bookingLength != NULL && $startHourIntakeBooking != NULL && $endHourIntakeBooking != NULL && $startHourDepositBooking != NULL && $endHourDepositBooking != NULL && $intakeMonday != NULL && $intakeTuesday != NULL && $intakeWednesday != NULL && $intakeThursday != NULL && $intakeFriday != NULL && $intakeSaturday != NULL && $intakeSunday != NULL && $depositMonday != NULL && $depositTuesday != NULL && $depositWednesday != NULL && $depositThursday != NULL && $depositFriday != NULL && $depositSaturday != NULL && $depositSunday != NULL && $email != NULL && $maximumBookingsYear != NULL && $maximumBookingsMonth != NULL)
{
    include 'connexion.php';
	$sql="select * from customer_referential where EMAIL = '$email'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
	$result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();   

    $company=$resultat['COMPANY'];
    

    include 'connexion.php';
    $sql="select * from conditions where COMPANY='$company'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
	$result = mysqli_query($conn, $sql); 
    $length = $result->num_rows;
    if($length == 0){
        $sql="INSERT INTO conditions (USR_MAJ, HEU_MAJ, BOOKING_DAYS, BOOKING_LENGTH, HOUR_START_INTAKE_BOOKING, HOUR_END_INTAKE_BOOKING, HOUR_START_DEPOSIT_BOOKING, HOUR_END_DEPOSIT_BOOKING, MONDAY_INTAKE, TUESDAY_INTAKE, WEDNESDAY_INTAKE, THURSDAY_INTAKE, FRIDAY_INTAKE, SATURDAY_INTAKE, SUNDAY_INTAKE, MONDAY_DEPOSIT, TUESDAY_DEPOSIT, WEDNESDAY_DEPOSIT, THURSDAY_DEPOSIT, FRIDAY_DEPOSIT, SATURDAY_DEPOSIT, SUNDAY_DEPOSIT, COMPANY, ASSISTANCE, LOCKING, MAX_BOOKINGS_YEAR, MAX_BOOKINGS_MONTH) VALUE('$email', CURRENT_TIMESTAMP, '$bookingDays', '$bookingLength', '$startHourIntakeBooking', '$endHourIntakeBooking', '$startHourDepositBooking', '$endHourDepositBooking', '$intakeMonday', '$intakeTuesday', '$intakeWednesday', '$intakeThursday', '$intakeFriday', '$intakeSaturday', '$intakeSunday', '$depositMonday', '$depositTuesday', '$depositWednesday', '$depositThursday', '$depositFriday', '$depositSaturday', '$depositSunday', '$company', 'N', 'N', '$maximumBookingsYear', '$maximumBookingsMonth')";
    }else{
        $sql="update conditions set USR_MAJ = 'mykameo', HEU_MAJ = CURRENT_TIMESTAMP, BOOKING_DAYS='$bookingDays', BOOKING_LENGTH='$bookingLength', HOUR_START_INTAKE_BOOKING = '$startHourIntakeBooking', HOUR_END_INTAKE_BOOKING = '$endHourIntakeBooking', HOUR_START_DEPOSIT_BOOKING = '$startHourDepositBooking', HOUR_END_DEPOSIT_BOOKING = '$endHourDepositBooking', MONDAY_INTAKE = '$intakeMonday', TUESDAY_INTAKE = '$intakeTuesday', WEDNESDAY_INTAKE = '$intakeWednesday', THURSDAY_INTAKE = '$intakeThursday', FRIDAY_INTAKE = '$intakeFriday', SATURDAY_INTAKE = '$intakeSaturday', SUNDAY_INTAKE = '$intakeSunday', MONDAY_DEPOSIT = '$depositMonday', TUESDAY_DEPOSIT = '$depositTuesday', WEDNESDAY_DEPOSIT = '$depositWednesday', THURSDAY_DEPOSIT = '$depositThursday', FRIDAY_DEPOSIT = '$depositFriday', SATURDAY_DEPOSIT = '$depositSaturday', SUNDAY_DEPOSIT = '$depositSunday', MAX_BOOKINGS_YEAR='$maximumBookingsYear', MAX_BOOKINGS_MONTH='$maximumBookingsMonth' WHERE COMPANY = '$company'";
    }
    
    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $conn->close();     


    successMessage("SM0003");

}
else
{
	errorMessage("ES0012");
}

?>