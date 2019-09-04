<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$IDBilling=isset($_POST['widget-updateBillingStatus-form-billingReference']) ? $_POST['widget-updateBillingStatus-form-billingReference'] : "0";
$date=isset($_POST['widget-updateBillingStatus-form-billingdate']) ? date($_POST['widget-updateBillingStatus-form-billingdate']) : NULL;
$originator=isset($_POST['widget-updateBillingStatus-form-billingCompany']) ? $_POST['widget-updateBillingStatus-form-billingCompany'] : NULL;
$beneficiary=isset($_POST['widget-updateBillingStatus-form-beneficiaryBillingCompany']) ? $_POST['widget-updateBillingStatus-form-beneficiaryBillingCompany'] : NULL;
$amountHTVA=isset($_POST['widget-updateBillingStatus-form-billingAmountHTVA']) ? $_POST['widget-updateBillingStatus-form-billingAmountHTVA'] : NULL;
$amountTVA=isset($_POST['widget-updateBillingStatus-form-billingAmountTVAINC']) ? $_POST['widget-updateBillingStatus-form-billingAmountTVAINC'] : NULL;
$billingSent=isset($_POST['widget-updateBillingStatus-form-billingSent']) ? "1" : "0";
$billingSentDate=isset($_POST['widget-updateBillingStatus-form-billingSentDate']) ? date($_POST['widget-updateBillingStatus-form-billingSentDate']) : "";
$billingPaid=isset($_POST['widget-updateBillingStatus-form-billingPaid']) ? "1" : "0";
$billingPaidDate=isset($_POST['widget-updateBillingStatus-form-billingPaidDate']) ? date($_POST['widget-updateBillingStatus-form-billingPaidDate']) : "";
$billingLimitPaidDate=isset($_POST['widget-updateBillingStatus-form-billingLimitPaidDate']) ? date($_POST['widget-updateBillingStatus-form-billingLimitPaidDate']) : "";
$user=$_POST['widget-updateBillingStatus-form-user'];
$communication=$_POST['widget-updateBillingStatus-form-communication'];
$response=array();


if($amountHTVA<0 && $originator!="KAMEO"){
    errorMessage("ES0045");
}
if($amountHTVA<0 && $beneficiary=="KAMEO"){
    errorMessage("ES0046");
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
    if(($billingSentDate != null || $billingPaidDate != null || $billingLimitPaidDate != null)){
        include 'connexion.php';
        if($billingSentDate!=null && ($billingPaidDate==null || $billingPaidDate=="0"))
        {
                $sql="update factures set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', COMPANY='$originator', BENEFICIARY_COMPANY='$beneficiary', DATE='$date', AMOUNT_HTVA='$amountHTVA', AMOUNT_TVAINC='$amountTVA', FACTURE_SENT='$billingSent', FACTURE_SENT_DATE='$billingSentDate', COMMUNICATION_STRUCTUREE='$communication' where ID='$IDBilling'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
               }
        }
        else if($billingPaidDate!=null && ($billingSentDate==null || $billingSentDate=="0"))
        {
            $sql="update factures set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', COMPANY='$originator', BENEFICIARY_COMPANY='$beneficiary', DATE='$date', AMOUNT_HTVA='$amountHTVA', AMOUNT_TVAINC='$amountTVA', FACTURE_SENT='$billingSent', FACTURE_PAID='$billingPaid', FACTURE_PAID_DATE='$billingPaidDate', COMMUNICATION_STRUCTUREE='$communication' where ID='$IDBilling'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
           }
        }else if($billingPaidDate != null && $billingSentDate!= null){
            $sql="update factures set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', COMPANY='$originator', BENEFICIARY_COMPANY='$beneficiary', DATE='$date', AMOUNT_HTVA='$amountHTVA', AMOUNT_TVAINC='$amountTVA', FACTURE_SENT='$billingSent', FACTURE_SENT_DATE='$billingSentDate', FACTURE_PAID='$billingPaid', FACTURE_PAID_DATE='$billingPaidDate', COMMUNICATION_STRUCTUREE='$communication' where ID='$IDBilling'";
            
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
           }        
        }

        
        if($billingLimitPaidDate != null){
            $sql="update factures set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', COMPANY='$originator', BENEFICIARY_COMPANY='$beneficiary', DATE='$date', AMOUNT_HTVA='$amountHTVA', AMOUNT_TVAINC='$amountTVA', FACTURE_LIMIT_PAID_DATE='$billingLimitPaidDate', COMMUNICATION_STRUCTUREE='$communication' where ID='$IDBilling'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }     
        }
        
        $conn->close();

        successMessage("SM0003");
    }else{
        errorMessage("ES0033");
    }
}else
{
	errorMessage("ES0012");
}

?>