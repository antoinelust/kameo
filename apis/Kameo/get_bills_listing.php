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
        error_message('500', 'Unable to retrieve your email address');
    }

    $sql="select aa.ID as ID, COMPANY as company, BENEFICIARY_COMPANY as beneficiaryCompany, DATE as date, AMOUNT_HTVA as amountHTVA, AMOUNT_TVAINC as amountTVAC, FACTURE_SENT as sent, FACTURE_SENT_DATE as sentDate, FACTURE_PAID as paid, FACTURE_PAID_DATE as paidDate, FACTURE_LIMIT_PAID_DATE as limitPaidDate, FILE_NAME as fileName, COMMUNICATION_STRUCTUREE as communication, FACTURE_SENT_ACCOUNTING as communicationSentAccounting, bb.EMAIL_CONTACT_BILLING as emailContactBilling, bb.FIRSTNAME_CONTACT_BILLING as firstNameContactBilling, bb.LASTNAME_CONTACT_BILLING as lastNameContactBilling from factures aa, companies bb where aa.COMPANY=bb.INTERNAL_REFERENCE";
    
    if($company!=='KAMEO'){
        $response['update']=false;
    	$sql=$sql." and aa.COMPANY = '$company'";
    }else{
        $response['update']=true;
        if($company2!="*" && $company2 != NULL){
    	   $sql=$sql." and aa.COMPANY = '$company2'";
        }
    }


    if($paid!=='*' && $paid !== NULL){
        $sql=$sql." AND FACTURE_PAID='$paid'";
    }
    if($sent!=='*' && $sent !== NULL){
        $sql=$sql." AND FACTURE_SENT='$sent'";
    }
    if($direction!=='*' && $direction !== NULL){
        if($direction=="IN"){
            $sql=$sql." AND AMOUNT_HTVA>0";
        }else if($direction=="OUT"){
            $sql=$sql." AND AMOUNT_HTVA<0";
        }
    }

    $sql=$sql." GROUP BY aa.ID";

    $result=execute_sql_query($sql, $conn);
    $response['bill']=$result->fetch_all(MYSQLI_ASSOC);
    $length = $result->num_rows;
    $response['response']="success";
	  $response['billNumber']=$length;

    $sql="select LPAD(MAX(ID_OUT_BILL)+1, 3, '0') as reference, MAX(ID_OUT_BILL) as MAX_OUT, MAX(ID) as MAX_TOTAL from factures";
    $result=execute_sql_query($sql, $conn);
    $resultat = mysqli_fetch_assoc($result);
    $response['IDMaxBillingOut']=$resultat['MAX_OUT'];
    $conn->close();


    $newID=$resultat['MAX_TOTAL'];
    $newID=strval($newID+1);
    $length=strlen($newID);
    $i=(3-$length);
    $reference=$resultat['reference'];
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
