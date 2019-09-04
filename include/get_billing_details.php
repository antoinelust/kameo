<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$ID=$_POST['ID'];

$response=array();

if($ID != NULL)
{

	
    include 'connexion.php';
	$sql="SELECT *  FROM factures WHERE ID = '$ID'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $row = mysqli_fetch_assoc($result);



    $response['response']="success";
    $response['bill']['company']=$row['COMPANY'];
    $response['bill']['beneficiaryCompany']=$row['BENEFICIARY_COMPANY'];
    $response['bill']['communication']=$row['COMMUNICATION_STRUCTUREE'];
    $response['bill']['ID']=$row['ID'];
    $response['bill']['date']=$row['DATE'];            
    $response['bill']['amountHTVA']=$row['AMOUNT_HTVA'];
    $response['bill']['amountTVAC']=$row['AMOUNT_TVAINC'];
    $response['bill']['sent']=$row['FACTURE_SENT'];
    $response['bill']['sentDate']=$row['FACTURE_SENT_DATE'];
    $response['bill']['paid']=$row['FACTURE_PAID'];
    $response['bill']['paidDate']=$row['FACTURE_PAID_DATE'];
    $response['bill']['paidLimitDate']=$row['FACTURE_LIMIT_PAID_DATE'];
    $response['bill']['fileName']=$row['FILE_NAME'];
    
    
	echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0006");
}

?>