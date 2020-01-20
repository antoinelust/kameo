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
    $response['bill']['type']=$row['TYPE'];
    $response['bill']['communicationSentAccounting']=$row['FACTURE_SENT_ACCOUNTING'];    
    $response['bill']['file']=$row['FILE_NAME'];
    
    include 'connexion.php';
	$sql="SELECT *  FROM factures_details WHERE FACTURE_ID = '$ID'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);  
    $length = $result->num_rows;
    
	$response['billDetailsNumber']=$length;
    
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['bill']['billDetails'][i]['frameNumber']=$row['FRAME_NUMBER'];
        $response['bill']['billDetails'][i]['comments']=$row['COMMENTS'];
        $response['bill']['billDetails'][i]['amountHTVA']=$row['AMOUNT_HTVA'];
        $response['bill']['billDetails'][i]['amountTVAC']=$row['AMOUNT_TVAC'];
        $i++;
    }
    
	echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0006");
}

?>