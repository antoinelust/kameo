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
$type=$_POST['type'];
$typeOther=isset($_POST['typeOther']) ? $_POST['typeOther'] : NULL;
$amountHTVA=$_POST['widget-addBill-form-amountHTVA'];
$amountTVAC=$_POST['widget-addBill-form-amountTVAC'];
$billingSent=isset($_POST['widget-addBill-form-sent']) ? "1" : "0";
$ID=isset($_POST['ID']) ?$_POST['ID'] :  NULL;
$billingSentDate=isset($_POST['widget-addBill-form-sendingDate']) ? date($_POST['widget-addBill-form-sendingDate']) : "0";
$billingPaid=isset($_POST['widget-addBill-form-paid']) ? "1" : "0";
$billingPaidDate=isset($_POST['widget-addBill-form-paymentDate']) ? date($_POST['widget-addBill-form-paymentDate']) : "0";
$billingLimitPaidDate=isset($_POST['widget-addBill-form-datelimite']) ? date($_POST['widget-addBill-form-datelimite']) : "0";
$communication=$_POST['widget-addBill-form-communication'];


if($amountHTVA<0 && $company!="KAMEO"){
    errorMessage("ES0045");
}
if($amountHTVA>0 && $company=="KAMEO"){
    errorMessage("ES0047");
}
if($amountHTVA<0 && $beneficiaryCompany=="KAMEO"){
    errorMessage("ES0046");
}
if($amountHTVA>0 && $beneficiaryCompany!="KAMEO"){
    errorMessage("ES0048");
}


if($billingSent =="1" && $billingSentDate == null)
{
    errorMessage("ES0031");
}

if($billingPaid =="1" && $billingPaidDate == null)
{
    errorMessage("ES0032");
}

if($type=="autre" && !isset($typeOther)){
    errorMessage("ES0050");
}else if($type=="autre"){
    $type=$typeOther;
}

if(isset($_FILES['widget-addBill-form-file']))
{ 
    include 'connexion.php';
    $sql="select max(ID) as 'MAXID' from factures";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);   
    $resultat = mysqli_fetch_assoc($result);
    $maxID=$resultat['MAXID'];
    $newID=$maxID+1;
    
    $dossier = '../factures/';

    $extensions = array('.pdf');
    $extension = strrchr($_FILES['widget-addBill-form-file']['name'], '.');
    
    if($amountHTVA<0){
        $fileName=substr($date, 0, 10)."_".$beneficiaryCompany."_".$newID;
    }else{
        $fileName=substr($date, 0, 10)."_".$company."_".$newID;
    }
    
    if($ID && $beneficiaryCompany=='KAMEO'){
        $fileName=$fileName."_facture_".$ID;
    }
        
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
          errorMessage("ES0024");
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
    

if($company=="other"){
    $company=$companyOther;
}

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


$sql= "INSERT INTO  factures (ID, USR_MAJ, HEU_MAJ, COMPANY, BENEFICIARY_COMPANY, DATE, AMOUNT_HTVA, AMOUNT_TVAINC, COMMUNICATION_STRUCTUREE, FILE_NAME, FACTURE_SENT, FACTURE_SENT_DATE, FACTURE_PAID, FACTURE_PAID_DATE, TYPE, FACTURE_LIMIT_PAID_DATE) VALUES ('$newID', '$email', CURRENT_TIMESTAMP, '$company', '$beneficiaryCompany', '$date', '$amountHTVA', '$amountTVAC', '$communication', '$fichier', '$billingSent', $billingSentDate, '$billingPaid', $billingPaidDate, '$type', $billingLimitPaidDate)";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}


$conn->close();   
$response['sql']=$sql;
successMessage("SM0012");
?>
