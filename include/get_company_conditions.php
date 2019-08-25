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
    if($company!='KAMEO'){
        $response['update']=false;
    } else{
        $response['update']=true;
    }
    
    
    $response['companyConditions']['administrator']=$resultat['ADMINISTRATOR'];       
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
    $response['companyConditions']['bookingDays']=$resultat['BOOKING_DAYS']; 
    $response['companyConditions']['bookingLength']=$resultat['BOOKING_LENGTH']; 
    $response['companyConditions']['assistance']=$resultat['ASSISTANCE']; 
    $response['companyConditions']['hourStartIntakeBooking']=$resultat['HOUR_START_INTAKE_BOOKING'];
    $response['companyConditions']['hourEndIntakeBooking']=$resultat['HOUR_END_INTAKE_BOOKING'];
    $response['companyConditions']['hourStartDepositBooking']=$resultat['HOUR_START_DEPOSIT_BOOKING'];
    $response['companyConditions']['hourEndDepositBooking']=$resultat['HOUR_END_DEPOSIT_BOOKING'];
    $response['companyConditions']['locking']=$resultat['LOCKING'];
    $response['companyConditions']['mondayIntake']=$resultat['MONDAY_INTAKE'];
    $response['companyConditions']['tuesdayIntake']=$resultat['TUESDAY_INTAKE'];
    $response['companyConditions']['wednesdayIntake']=$resultat['WEDNESDAY_INTAKE'];
    $response['companyConditions']['thursdayIntake']=$resultat['THURSDAY_INTAKE'];
    $response['companyConditions']['fridayIntake']=$resultat['FRIDAY_INTAKE'];
    $response['companyConditions']['saturdayIntake']=$resultat['SATURDAY_INTAKE'];
    $response['companyConditions']['sundayIntake']=$resultat['SUNDAY_INTAKE'];
    $response['companyConditions']['mondayDeposit']=$resultat['MONDAY_DEPOSIT'];
    $response['companyConditions']['tuesdayDeposit']=$resultat['TUESDAY_DEPOSIT'];
    $response['companyConditions']['wednesdayDeposit']=$resultat['WEDNESDAY_DEPOSIT'];
    $response['companyConditions']['thursdayDeposit']=$resultat['THURSDAY_DEPOSIT'];
    $response['companyConditions']['fridayDeposit']=$resultat['FRIDAY_DEPOSIT'];
    $response['companyConditions']['saturdayDeposit']=$resultat['SATURDAY_DEPOSIT'];
    $response['companyConditions']['sundayDeposit']=$resultat['SUNDAY_DEPOSIT'];

	
    
	echo json_encode($response);
    die;

}
else
{
	errorMessage(ES0012);
}

?>