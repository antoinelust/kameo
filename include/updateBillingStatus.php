<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$IDBilling=isset($_POST['widget-updateBillingStatus-form-billingReference']) ? $_POST['widget-updateBillingStatus-form-billingReference'] : NULL;
$originator=isset($_POST['widget-updateBillingStatus-form-billingCompany']) ? $_POST['widget-updateBillingStatus-form-billingCompany'] : NULL;
$beneficiary=isset($_POST['widget-updateBillingStatus-form-beneficiaryBillingCompany']) ? $_POST['widget-updateBillingStatus-form-beneficiaryBillingCompany'] : NULL;
$date=isset($_POST['widget-updateBillingStatus-form-date']) ? date($_POST['widget-updateBillingStatus-form-date']) : NULL;
$amountHTVA=isset($_POST['widget-updateBillingStatus-form-amountHTVA']) ? $_POST['widget-updateBillingStatus-form-amountHTVA'] : NULL;
$amountTVA=isset($_POST['widget-updateBillingStatus-form-amountTVAC']) ? $_POST['widget-updateBillingStatus-form-amountTVAC'] : NULL;
$billingSent=isset($_POST['widget-updateBillingStatus-form-sent']) ? "1" : "0";
$billingSentDate=isset($_POST['widget-updateBillingStatus-form-sendingDate']) ? date($_POST['widget-updateBillingStatus-form-sendingDate']) : "";
$billingPaid=isset($_POST['widget-updateBillingStatus-form-paid']) ? "1" : "0";
$billingPaidDate=isset($_POST['widget-updateBillingStatus-form-paymentDate']) ? date($_POST['widget-updateBillingStatus-form-paymentDate']) : "";
$billingLimitPaidDate=isset($_POST['widget-updateBillingStatus-form-datelimite']) ? date($_POST['widget-updateBillingStatus-form-datelimite']) : "";
$user=$_POST['widget-updateBillingStatus-form-user'];
$communication=$_POST['widget-updateBillingStatus-form-communication'];
$response=array();


if($amountHTVA<0 && $originator!="KAMEO"){
    errorMessage("ES0045");
}
if($amountHTVA>0 && $originator=="KAMEO"){
    errorMessage("ES0047");
}
if($amountHTVA<0 && $beneficiary=="KAMEO"){
    errorMessage("ES0046");
}
if($amountHTVA>0 && $beneficiary!="KAMEO"){
    errorMessage("ES0048");
}

if($billingSentDate==""){
    $billingSentDate=null;
}
if($billingPaidDate==""){
    $billingPaidDate=null;
}

if($billingSent =="1" && $billingSentDate == null)
{
    errorMessage("ES0031");
}

if($billingPaid =="1" && $billingPaidDate == null)
{
    errorMessage("ES0032");
}



if( $IDBilling!=""){
    include 'connexion.php';
    
    if($billingSentDate!=NULL){
        $billingSentDate="'".$billingSentDate."'";
    }else{
        $billingSentDate='NULL';
    }       
    
    if($billingPaidDate!=NULL){
        $billingPaidDate="'".$billingPaidDate."'";
    }else{
        $billingPaidDate='NULL';
    }    
    
    if($billingPaidDate!=NULL){
        $billingLimitPaidDate="'".$billingLimitPaidDate."'";
    }else{
        $billingLimitPaidDate='NULL';
    }      

    $sql="update factures set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', COMPANY='$originator', BENEFICIARY_COMPANY='$beneficiary', DATE='$date', AMOUNT_HTVA='$amountHTVA', AMOUNT_TVAINC='$amountTVA', FACTURE_SENT='$billingSent', FACTURE_SENT_DATE=$billingSentDate, FACTURE_PAID='$billingPaid', FACTURE_PAID_DATE=$billingPaidDate, FACTURE_LIMIT_PAID_DATE=$billingLimitPaidDate, COMMUNICATION_STRUCTUREE='$communication'  where ID='$IDBilling'";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }       
    $conn->close();

    successMessage("SM0003");

}else
{
	errorMessage("ES0012");
}

?>