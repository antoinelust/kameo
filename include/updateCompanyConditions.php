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
$intakeMonday=isset($_POST['companyConditionsIntakeBookingMonday']) ? $_POST['companyConditionsIntakeBookingMonday'] : "0";
$intakeTuesday=isset($_POST['companyConditionsIntakeBookingTuesday']) ? $_POST['companyConditionsIntakeBookingTuesday'] : "0";
$intakeWednesday=isset($_POST['companyConditionsIntakeBookingWednesday']) ? $_POST['companyConditionsIntakeBookingWednesday'] : "0";
$intakeThursday=isset($_POST['companyConditionsIntakeBookingThursday']) ? $_POST['companyConditionsIntakeBookingThursday'] : "0";
$intakeFriday=isset($_POST['companyConditionsIntakeBookingFriday']) ? $_POST['companyConditionsIntakeBookingFriday'] : "0";
$intakeSaturday=isset($_POST['companyConditionsIntakeBookingSaturday']) ? $_POST['companyConditionsIntakeBookingSaturday'] : "0";
$intakeSunday=isset($_POST['companyConditionsIntakeBookingSunday']) ? $_POST['companyConditionsIntakeBookingSunday'] : "0";
$depositMonday=isset($_POST['companyConditionsDepositBookingMonday']) ? $_POST['companyConditionsDepositBookingMonday'] : "0";
$depositTuesday=isset($_POST['companyConditionsDepositBookingTuesday']) ? $_POST['companyConditionsDepositBookingTuesday'] : "0";
$depositWednesday=isset($_POST['companyConditionsDepositBookingWednesday']) ? $_POST['companyConditionsDepositBookingWednesday'] : "0";
$depositThursday=isset($_POST['companyConditionsDepositBookingThursday']) ? $_POST['companyConditionsDepositBookingThursday'] : "0";
$depositFriday=isset($_POST['companyConditionsDepositBookingFriday']) ? $_POST['companyConditionsDepositBookingFriday'] : "0";
$depositSaturday=isset($_POST['companyConditionsDepositBookingSaturday']) ? $_POST['companyConditionsDepositBookingSaturday'] : "0";
$depositSunday=isset($_POST['companyConditionsDepositBookingSunday']) ? $_POST['companyConditionsDepositBookingSunday'] : "0";
$email=isset($_POST['companyConditionsEmail']) ? $_POST['companyConditionsEmail'] : null;
    
$response=array();

if($bookingDays != NULL && $bookingLength != NULL && $startHourIntakeBooking != NULL && $endHourIntakeBooking != NULL && $startHourDepositBooking != NULL && $endHourDepositBooking != NULL && $intakeMonday != NULL && $intakeTuesday != NULL && $intakeWednesday != NULL && $intakeThursday != NULL && $intakeFriday != NULL && $intakeSaturday != NULL && $intakeSunday != NULL && $depositMonday != NULL && $depositTuesday != NULL && $depositWednesday != NULL && $depositThursday != NULL && $depositFriday != NULL && $depositSaturday != NULL && $depositSunday != NULL && $email != NULL)
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
    $sql="update conditions set USR_MAJ = 'mykameo', HEU_MAJ = CURRENT_TIMESTAMP, BOOKING_DAYS='$bookingDays', BOOKING_LENGTH='$bookingLength', HOUR_START_INTAKE_BOOKING = '$startHourIntakeBooking', HOUR_END_INTAKE_BOOKING = '$endHourIntakeBooking', HOUR_START_DEPOSIT_BOOKING = '$startHourDepositBooking', HOUR_END_DEPOSIT_BOOKING = '$endHourDepositBooking', MONDAY_INTAKE = '$intakeMonday', TUESDAY_INTAKE = '$intakeTuesday', WEDNESDAY_INTAKE = '$intakeWednesday', THURSDAY_INTAKE = '$intakeThursday', FRIDAY_INTAKE = '$intakeFriday', SATURDAY_INTAKE = '$intakeSaturday', SUNDAY_INTAKE = '$intakeSunday', MONDAY_DEPOSIT = '$depositMonday', TUESDAY_DEPOSIT = '$depositTuesday', WEDNESDAY_DEPOSIT = '$depositWednesday', THURSDAY_DEPOSIT = '$depositThursday', FRIDAY_DEPOSIT = '$depositFriday', SATURDAY_DEPOSIT = '$depositSaturday', SUNDAY_DEPOSIT = '$depositSaturday' WHERE COMPANY = '$company'";
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