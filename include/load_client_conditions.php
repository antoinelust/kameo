<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';


$email=$_POST['email'];

if($email != NULL)
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
    $response['clientConditions']['administrator']=$resultat['ADMINISTRATOR'];       
    
    include 'connexion.php';
	$sql="select * from conditions where COMPANY = '$company'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
	$result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();   
    
 

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
    $response['clientConditions']['su,dayIntake']=$resultat['SUNDAY_INTAKE'];
    $response['clientConditions']['mondayDeposit']=$resultat['MONDAY_DEPOSIT'];
    $response['clientConditions']['tuesdayDeposit']=$resultat['TUESDAY_DEPOSIT'];
    $response['clientConditions']['wednesdayDeposit']=$resultat['WEDNESDAY_DEPOSIT'];
    $response['clientConditions']['thursdayDeposit']=$resultat['THURSDAY_DEPOSIT'];
    $response['clientConditions']['fridayDeposit']=$resultat['FRIDAY_DEPOSIT'];
    $response['clientConditions']['saturdayDeposit']=$resultat['SATURDAY_DEPOSIT'];
    $response['clientConditions']['sundayDeposit']=$resultat['SUNDAY_DEPOSIT'];

	
    
	echo json_encode($response);
    die;

}
else
{
	errorMessage(ES0012);
}

?>