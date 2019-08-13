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
$user = $_POST["widget_companyDetails_requestor"];
$internalReference = $_POST["widget_companyDetails_internalReference"];


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
	
	$sql = "update companies set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$user', COMPANY_NAME='$companyName', STREET='$companyStreet', ZIP_CODE='$ZIPCode', TOWN='$companyTown',  VAT_NUMBER='$companyVAT', EMAIL_CONTACT='$emailContact', NOM_CONTACT='$lastNameContact', PRENOM_CONTACT='$firstNameContact' where INTERNAL_REFERENCE='$internalReference'";
     
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);
	$conn->close();
		
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
