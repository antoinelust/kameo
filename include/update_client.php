<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$companyName = $_POST["widget_companyDetails_companyName"];
$companyStreet = $_POST["widget_companyDetails_companyStreet"];
$ZIPCode = $_POST["widget_companyDetails_companyZIPCode"];
$companyTown = $_POST["widget_companyDetails_companyTown"];
$companyVAT = $_POST["widget_companyDetails_companyVAT"];
$emailContact = $_POST["widget_companyDetails_emailContact"];
$lastNameContact = $_POST["widget_companyDetails_lastNameContact"];
$firstNameContact = $_POST["widget_companyDetails_firstNameContact"];
$type = $_POST["type"];
$phone = $_POST["phone"];
$user = $_POST["widget_companyDetails_requestor"];
$internalReference = $_POST["widget_companyDetails_internalReference"];
$locking=isset($_POST['locking']) ? "Y" : "N";
$assistance=isset($_POST['assistance']) ? "Y" : "N";

if( $_SERVER['REQUEST_METHOD'] == 'POST') {

 if($user != '') {
 
    include 'connexion.php';
	$sql = "select ID from companies where INTERNAL_REFERENCE='$internalReference'";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
     
    $result = mysqli_query($conn, $sql);     
    if($result->num_rows=='0'){
        errorMessage("ES0037");
    }
	
	$sql = "update companies set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$user', COMPANY_NAME='$companyName', STREET='$companyStreet', ZIP_CODE='$ZIPCode', TOWN='$companyTown',  VAT_NUMBER='$companyVAT', EMAIL_CONTACT='$emailContact', NOM_CONTACT='$lastNameContact', PRENOM_CONTACT='$firstNameContact', TYPE='$type', CONTACT_PHONE='$phone' where INTERNAL_REFERENCE='$internalReference'";
     
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$conn->close();
     
    include 'connexion.php';
    $sql = "select * from conditions WHERE COMPANY='$internalReference'";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $length = $result->num_rows;

    $conn->close();   
     
     if($length==0){
            include 'connexion.php';
            $sql="INSERT INTO conditions (USR_MAJ, HEU_MAJ, BOOKING_DAYS, BOOKING_LENGTH, HOUR_START_INTAKE_BOOKING, HOUR_END_INTAKE_BOOKING, HOUR_START_DEPOSIT_BOOKING, HOUR_END_DEPOSIT_BOOKING, MONDAY_INTAKE, TUESDAY_INTAKE, WEDNESDAY_INTAKE, THURSDAY_INTAKE, FRIDAY_INTAKE, SATURDAY_INTAKE, SUNDAY_INTAKE, MONDAY_DEPOSIT, TUESDAY_DEPOSIT, WEDNESDAY_DEPOSIT, THURSDAY_DEPOSIT, FRIDAY_DEPOSIT, SATURDAY_DEPOSIT, SUNDAY_DEPOSIT, COMPANY, ASSISTANCE, LOCKING, MAX_BOOKINGS_YEAR, MAX_BOOKINGS_MONTH) VALUE('$user', CURRENT_TIMESTAMP, '2', '24', '7', '19', '7', '19', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '0', '0', '$internalReference', '$assistance', '$locking', '999', '999')";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
     }else{
         if($locking!=$resultat['LOCKING'] || $assistance!=$resultat['ASSISTANCE']){
            include 'connexion.php';
            $sql = "UPDATE conditions SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$user', ASSISTANCE='$assistance', LOCKING='$locking' WHERE COMPANY='$internalReference'";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
         }
     }
     
     
	 successMessage("SM0003");

} else {
	$response = array ('response'=>'error');     
	echo json_encode($response);
	die;
}
    
}
else
{
	errorMessage(ES0012);
}
?>
