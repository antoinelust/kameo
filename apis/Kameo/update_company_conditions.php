<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


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

    if($action=="create"){
        include 'connexion.php';
        $sql="select * from conditions where COMPANY = '$company' and name='$name'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $length=$result->num_rows;
        $conn->close();

        if($length>0){
            errorMessage("ES0052");
        }

        include 'connexion.php';
        $sql="INSERT INTO conditions (USR_MAJ, HEU_MAJ, NAME, BOOKING, BOOKING_DAYS, BOOKING_LENGTH, HOUR_START_INTAKE_BOOKING, HOUR_END_INTAKE_BOOKING, HOUR_START_DEPOSIT_BOOKING, HOUR_END_DEPOSIT_BOOKING, MONDAY_INTAKE, TUESDAY_INTAKE, WEDNESDAY_INTAKE, THURSDAY_INTAKE, FRIDAY_INTAKE, SATURDAY_INTAKE, SUNDAY_INTAKE, MONDAY_DEPOSIT, TUESDAY_DEPOSIT, WEDNESDAY_DEPOSIT, THURSDAY_DEPOSIT, FRIDAY_DEPOSIT, SATURDAY_DEPOSIT, SUNDAY_DEPOSIT, COMPANY, ASSISTANCE, LOCKING, MAX_BOOKINGS_YEAR, MAX_BOOKINGS_MONTH) VALUE('$email', CURRENT_TIMESTAMP, '$name', '$booking', '$bookingDays', '$bookingLength', '$startHourIntakeBooking', '$endHourIntakeBooking', '$startHourDepositBooking', '$endHourDepositBooking', '$intakeMonday', '$intakeTuesday', '$intakeWednesday', '$intakeThursday', '$intakeFriday', '$intakeSaturday', '$intakeSunday', '$depositMonday', '$depositTuesday', '$depositWednesday', '$depositThursday', '$depositFriday', '$depositSaturday', '$depositSunday', '$company', 'N', 'N', '$maximumBookingsYear', '$maximumBookingsMonth')";


        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();

        include 'connexion.php';
        $sql="select ID from conditions where COMPANY='$company' and NAME='$name'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();
        $id=$resultat['ID'];


        if(isset($_POST['userAccess'])){
            foreach($_POST['userAccess'] as $valueInArray){
                include 'connexion.php';
                $sql="UPDATE specific_conditions SET STAANN='D' WHERE EMAIL='$email' AND COMPANY='$company'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $conn->close();

                include 'connexion.php';
                $sql="INSERT INTO specific_conditions (USR_MAJ, HEU_MAJ, COMPANY, EMAIL, CONDITION_REFERENCE, STAANN) VALUE('$email', CURRENT_TIMESTAMP, '$company', '$valueInArray', '$id', '')";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $conn->close();

            }
        }






    }else if($action=='update'){


        include 'connexion.php';
        $sql= "SELECT * FROM specific_conditions WHERE CONDITION_REFERENCE='$id' AND STAANN!='D'";


        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $length = $result->num_rows;
        $conn->close();

        while($row = mysqli_fetch_array($result)){
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
                include 'connexion.php';
                $sql="update specific_conditions set STAANN='D', USR_MAJ='$email', HEU_MAJ=CURRENT_TIMESTAMP where CONDITION_REFERENCE = '$id' and EMAIL='$emailtemp'";

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $conn->close();
            }
        }





        include 'connexion.php';
        $sql="select * from conditions where ID='$id'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $length = $result->num_rows;
        $resultat = mysqli_fetch_assoc($result);
        $name=$resultat['NAME'];
        $conn->close();

        if($length == 0){
            errorMessage("ES0053");
        }else{
            include 'connexion.php';
            $sql="update conditions set USR_MAJ = 'mykameo', HEU_MAJ = CURRENT_TIMESTAMP, NAME='$name', BOOKING='$booking', BOOKING_DAYS='$bookingDays', BOOKING_LENGTH='$bookingLength', HOUR_START_INTAKE_BOOKING = '$startHourIntakeBooking', HOUR_END_INTAKE_BOOKING = '$endHourIntakeBooking', HOUR_START_DEPOSIT_BOOKING = '$startHourDepositBooking', HOUR_END_DEPOSIT_BOOKING = '$endHourDepositBooking', MONDAY_INTAKE = '$intakeMonday', TUESDAY_INTAKE = '$intakeTuesday', WEDNESDAY_INTAKE = '$intakeWednesday', THURSDAY_INTAKE = '$intakeThursday', FRIDAY_INTAKE = '$intakeFriday', SATURDAY_INTAKE = '$intakeSaturday', SUNDAY_INTAKE = '$intakeSunday', MONDAY_DEPOSIT = '$depositMonday', TUESDAY_DEPOSIT = '$depositTuesday', WEDNESDAY_DEPOSIT = '$depositWednesday', THURSDAY_DEPOSIT = '$depositThursday', FRIDAY_DEPOSIT = '$depositFriday', SATURDAY_DEPOSIT = '$depositSaturday', SUNDAY_DEPOSIT = '$depositSunday', MAX_BOOKINGS_YEAR='$maximumBookingsYear', MAX_BOOKINGS_MONTH='$maximumBookingsMonth', LOCKING='$box', MINUTES_FOR_AUTOMATIC_CANCEL='$minutesBeforeCancellation' WHERE ID = '$id'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();

        }


        if(isset($_POST['userAccess']) && $name!='generic'){
            foreach($_POST['userAccess'] as $valueInArray){
                include 'connexion.php';
                $sql="UPDATE specific_conditions SET STAANN='D' WHERE EMAIL='$valueInArray' AND COMPANY='$company' AND CONDITION_REFERENCE!='$id'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $conn->close();
                include 'connexion.php';
                $sql="select * from specific_conditions WHERE email='$valueInArray' and CONDITION_REFERENCE='$id'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $length = $result->num_rows;
                $conn->close();
                if($length==0){
                    include 'connexion.php';
                    $sql="INSERT INTO specific_conditions (USR_MAJ, HEU_MAJ, COMPANY, EMAIL, CONDITION_REFERENCE, STAANN) VALUE('$email', CURRENT_TIMESTAMP, '$company', '$valueInArray', '$id', '')";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();
                }else{
                    include 'connexion.php';
                    $sql="UPDATE specific_conditions SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$email', STAANN='' WHERE CONDITION_REFERENCE='$id' AND EMAIL='$valueInArray' AND COMPANY='$company'";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();
                }

            }
        }

    }






    successMessage("SM0003");

}
else
{
	errorMessage("ES0012");
}

?>
