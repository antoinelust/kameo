<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';



$email=$_POST['widget-addBill-form-email'];
$company=$_POST['widget-addBill-form-company'];
$beneficiaryCompany=$_POST['widget-addBill-form-beneficiaryCompany'];
$companyOther=$_POST['widget-addBill-form-companyOther'];
$date=$_POST['widget-addBill-form-date'];
$type=$_POST['widget-addBill-form-type'];
$amountHTVA=$_POST['widget-addBill-form-amountHTVA'];
$amountTVAC=$_POST['widget-addBill-form-amountTVAC'];
$billingSent=isset($_POST['widget-addBill-form-sent']) ? "1" : "0";
$billingSentDate=isset($_POST['widget-addBill-form-sendingDate']) ? date($_POST['widget-addBill-form-sendingDate']) : "0";
$billingPaid=isset($_POST['widget-addBill-form-paid']) ? "1" : "0";
$billingPaidDate=isset($_POST['widget-addBill-form-paymentDate']) ? date($_POST['widget-addBill-form-paymentDate']) : "0";
$billingLimitPaidDate=isset($_POST['widget-addBill-form-datelimite']) ? date($_POST['widget-addBill-form-datelimite']) : "0";
$communication=$_POST['widget-addBill-form-communication'];

if($amountHTVA<0 && $company!="KAMEO"){
    errorMessage("ES0045");
}
if($amountHTVA<0 && $beneficiaryCompany=="KAMEO"){
    errorMessage("ES0046");
}


if(isset($_FILES['widget-addBill-form-file']))
{ 
    $dossier = '../factures/';

    $extensions = array('.pdf');
    $extension = strrchr($_FILES['widget-addBill-form-file']['name'], '.');
    $fileName=$_FILES['widget-addBill-form-file']['name'];    
    
    if(!in_array($extension, $extensions))
    {
          errorMessage("ES0034");
    }


    $taille_maxi = 6291456;
    $taille = filesize($_FILES['widget-addBill-form-file']['tmp_name']);
    if($taille>$taille_maxi)
    {
          errorMessage("ES0023");
    }

    $today = getdate();



    $fichier = $fileName.".pdf";

     if(move_uploaded_file($_FILES['widget-addBill-form-file']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
        $upload=true;
        $path= $dossier . $fichier;
     }
     else
     {
          errorMessage(ES0024);
     }
    
    
}else{
    errorMessage("ES0035");
}
 


if($billingSent =="1" && $billingSentDate == "0")
{
    errorMessage("ES0031");
}

if($billingPaid =="1" && $billingPaidDate == "0")
{
    errorMessage("ES0032");
}
    
if($billingSentDate==""){
    $billingSentDate="NULL";
}else{
    $billingSentDate="'".$billingSentDate."'";
}
if($billingPaidDate==""){
    $billingPaidDate="NULL";
}else{
    $billingPaidDate="'".$billingPaidDate."'";
}
if($company=="other"){
    $company=$companyOther;
}

include 'connexion.php';
$sql= "INSERT INTO  factures (USR_MAJ, HEU_MAJ, COMPANY, BENEFICIARY_COMPANY, DATE, AMOUNT_HTVA, AMOUNT_TVAINC, COMMUNICATION_STRUCTUREE, FILE_NAME, FACTURE_SENT, FACTURE_SENT_DATE, FACTURE_PAID, FACTURE_PAID_DATE, TYPE, FACTURE_LIMIT_PAID_DATE) VALUES ('$email', CURRENT_TIMESTAMP, '$company', '$beneficiaryCompany', '$date', '$amountHTVA', '$amountTVAC', '$communication', '$fileName', '$billingSent', $billingSentDate, '$billingPaid', $billingPaidDate, '$type', '$billingLimitPaidDate')";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}


$conn->close();   
$response['sql']=$sql;
successMessage("SM0012");
?>
