<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=isset($_POST['email']) ? $_POST['email']: null;
$sent=isset($_POST['sent']) ? $_POST['sent']: null;
$paid=isset($_POST['paid']) ? $_POST['paid']: null;
$direction=isset($_POST['direction']) ? $_POST['direction']: null;
$company2=isset($_POST['company']) ? $_POST['company']: null;
$response=array();

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
    
    
    include 'connexion.php';    
    if($company!='KAMEO'){
        $response['update']=false;
    	$sql="select * from factures where COMPANY = '$company'";
    }else{
        $response['update']=true;
        if($company2!="*" && $company2 != NULL){
    	   $sql="select * from factures WHERE COMPANY ='$company2'";
        }else{
    	   $sql="select * from factures WHERE 1 ";
        } 
    }
        
    
    
    if($paid!='*' && $paid != NULL){
        $sql=$sql." AND FACTURE_PAID='$paid'";
    }
    if($sent!='*' && $sent != NULL){
        $sql=$sql." AND FACTURE_SENT='$sent'";
    }
    if($direction!='*' && $direction != NULL){
        if($direction=="IN"){
            $sql=$sql." AND AMOUNT_HTVA>0";
        }else if($direction=="OUT"){
            $sql=$sql." AND AMOUNT_HTVA<0";
        }
    }
    
    
    $result = mysqli_query($conn, $sql);   
    $conn->close();
    
    $length = $result->num_rows;
    $response['response']="success";
	$response['billNumber']=$length;

    
    $i=0;
    while($row = mysqli_fetch_array($result))

    {

		$response['bill'][$i]['company']=$row['COMPANY'];
		$response['bill'][$i]['beneficiaryCompany']=$row['BENEFICIARY_COMPANY'];
		$response['bill'][$i]['ID']=$row['ID'];
		$response['bill'][$i]['date']=$row['DATE'];            
		$response['bill'][$i]['amountHTVA']=$row['AMOUNT_HTVA'];
        $response['bill'][$i]['amountTVAC']=$row['AMOUNT_TVAINC'];
        $response['bill'][$i]['sent']=$row['FACTURE_SENT'];
        $response['bill'][$i]['sentDate']=$row['FACTURE_SENT_DATE'];
        $response['bill'][$i]['paid']=$row['FACTURE_PAID'];
        $response['bill'][$i]['paidDate']=$row['FACTURE_PAID_DATE'];
        $response['bill'][$i]['limitPaidDate']=$row['FACTURE_LIMIT_PAID_DATE'];
        $response['bill'][$i]['fileName']=$row['FILE_NAME'];
        $response['bill'][$i]['communication']=$row['COMMUNICATION_STRUCTUREE'];
        $response['bill'][$i]['communicationSentAccounting']=$row['FACTURE_SENT_ACCOUNTING'];
        $i++;

	}
    
    include 'connexion.php';     
    if($company=='KAMEO'){
        $sql="select * from factures";
    }else{
        $sql="select * from factures WHERE COMPANY='$company'";
    }
    
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['billNumberTotal']=$length;
    $conn->close();

    
    include 'connexion.php';         
    if($company=='KAMEO'){
        $sql="select * from factures where AMOUNT_HTVA>0";
    }else{
        $sql="select * from factures WHERE AMOUNT_HTVA>0 AND COMPANY='$company'";
    }
    
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['billINNumber']=$length;
    $conn->close();

    
    include 'connexion.php';             
    if($company=='KAMEO'){
        $sql="select * from factures where AMOUNT_HTVA<0";
    }else{
        $sql="select * from factures WHERE AMOUNT_HTVA<0 AND COMPANY='$company'";
    }
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['billOUTNumber']=$length;
    $conn->close();

    
    include 'connexion.php'; 
    if($company=='KAMEO'){
        $sql="select * from factures where AMOUNT_HTVA>0 AND FACTURE_SENT='0'";
    }else{
        $sql="select * from factures WHERE AMOUNT_HTVA>0 AND FACTURE_SENT='0' AND COMPANY='$company'";
    }
    
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['billINNumberNotSent']=$length;
    $conn->close();

    
    include 'connexion.php'; 
    if($company=='KAMEO'){
        $sql="select * from factures where AMOUNT_HTVA<0 AND FACTURE_PAID='0'";
    }else{
        $sql="select * from factures WHERE AMOUNT_HTVA<0 AND FACTURE_PAID='0' AND COMPANY='$company'";
    }
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['billOUTNumberNotPaid']=$length;
    $conn->close();

    include 'connexion.php';
    if($company=='KAMEO'){
        $sql="select * from factures where AMOUNT_HTVA>0 AND FACTURE_PAID='0' AND FACTURE_SENT='1'";
    }else{
        $sql="select * from factures WHERE AMOUNT_HTVA>0 AND FACTURE_PAID='0' AND FACTURE_SENT='1' AND COMPANY='$company'";
    }

    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['billINNumberNotPaid']=$length;
    $conn->close();

    
    include 'connexion.php';
    $sql="select MAX(ID_OUT_BILL) as MAX_OUT, MAX(ID) as MAX_TOTAL from factures";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
	$result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();

    $response['IDMaxBillingOut']=$resultat['MAX_OUT'];
    
    $newID=$resultat['MAX_TOTAL'];
    $newID=strval($newID+1);
    $length=strlen($newID);
    $i=(3-$length);
    $reference=$newID;
    while($i>0){
        $i-=1;
        $reference="0".$reference;
    }
    $currentDate = new DateTime('now');
    $month=$currentDate->format('m');
    $year=$currentDate->format('Y');
    $base_modulo=$month.substr($year,2,2).$reference;
    $modulo_check=($base_modulo % 97);
    $reference='000/'.$month.substr($year,2,2).'/'.$reference.$modulo_check;
    
    $response['communication']=$reference;
    $response['IDMaxBilling']=$resultat['MAX_TOTAL'];
    
    
    
    
    
    
    
    echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0006");
}

?>