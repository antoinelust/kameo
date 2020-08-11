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
    $stmt = $conn->prepare("select COMPANY from customer_referential WHERE EMAIL=?");
    if ($stmt)
    {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $company = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['COMPANY'];
        $stmt->close();
    }else{
        $stmt->close();        
        error_message('500', 'Unable to retrieve your email address');
    }

    if($company!='KAMEO'){
        $response['update']=false;
    	$sql="select COMPANY as company, BENEFICIARY_COMPANY as beneficiaryCompany, aa.ID as ID, DATE as date, AMOUNT_HTVA as amountHTVA, AMOUNT_TVAINC as amountTVAC, FACTURE_SENT as sent, FACTURE_SENT_DATE as sentDate, FACTURE_PAID as paid, FACTURE_PAID_DATE as paidDate, FACTURE_LIMIT_PAID_DATE as limitPaidDate, FILE_NAME as fileName, COMMUNICATION_STRUCTUREE as communication, FACTURE_SENT_ACCOUNTING as communicationSentAccounting, bb.EMAIL_CONTACT_BILLING as emailContactBilling, bb.FIRSTNAME_CONTACT_BILLING as firstNameContactBilling, bb.LASTNAME_CONTACT_BILLING as  lastNameContactBilling from factures aa, companies bb where aa.COMPANY=bb.INTERNAL_REFERENCE and aa.COMPANY = '$company'";
    }else{
        $response['update']=true;
        if($company2!="*" && $company2 != NULL){
    	$sql="select COMPANY as company, BENEFICIARY_COMPANY as beneficiaryCompany, aa.ID as ID, DATE as date, AMOUNT_HTVA as amountHTVA, AMOUNT_TVAINC as amountTVAC, FACTURE_SENT as sent, FACTURE_SENT_DATE as sentDate, FACTURE_PAID as paid, FACTURE_PAID_DATE as paidDate, FACTURE_LIMIT_PAID_DATE as limitPaidDate, FILE_NAME as fileName, COMMUNICATION_STRUCTUREE as communication, FACTURE_SENT_ACCOUNTING as communicationSentAccounting, bb.EMAIL_CONTACT_BILLING as emailContactBilling, bb.FIRSTNAME_CONTACT_BILLING as firstNameContactBilling, bb.LASTNAME_CONTACT_BILLING as  lastNameContactBilling from factures aa, companies bb where aa.COMPANY=bb.INTERNAL_REFERENCE and aa.COMPANY = '$company2'";
        }else{
    	$sql="select COMPANY as company, BENEFICIARY_COMPANY as beneficiaryCompany, aa.ID as ID, DATE as date, AMOUNT_HTVA as amountHTVA, AMOUNT_TVAINC as amountTVAC, FACTURE_SENT as sent, FACTURE_SENT_DATE as sentDate, FACTURE_PAID as paid, FACTURE_PAID_DATE as paidDate, FACTURE_LIMIT_PAID_DATE as limitPaidDate, FILE_NAME as fileName, COMMUNICATION_STRUCTUREE as communication, FACTURE_SENT_ACCOUNTING as communicationSentAccounting, bb.EMAIL_CONTACT_BILLING as emailContactBilling, bb.FIRSTNAME_CONTACT_BILLING as firstNameContactBilling, bb.LASTNAME_CONTACT_BILLING as  lastNameContactBilling from factures aa, companies bb where aa.COMPANY=bb.INTERNAL_REFERENCE";
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
    
    $result=execute_sql_query($sql, $conn);
    $response['bill']=$result->fetch_all(MYSQLI_ASSOC);
    $length = $result->num_rows;
    $response['response']="success";
	$response['billNumber']=$length;

    
    $i=0;
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