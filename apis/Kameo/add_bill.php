<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;




$email=$_POST['widget-addBill-form-email'];
$company=$_POST['widget-addBill-form-company'];
$beneficiaryCompany=$_POST['beneficiaryCompany'];
$companyOther=$_POST['widget-addBill-form-companyOther'];
$date=$_POST['widget-addBill-form-date'];
$type=$_POST['type'];
$billType=isset($_POST['billType']) ? $_POST['billType'] : NULL;
$typeOther=isset($_POST['typeOther']) ? $_POST['typeOther'] : NULL;
$amountHTVA=$_POST['widget-addBill-form-amountHTVA'];
$amountTVAC=$_POST['widget-addBill-form-amountTVAC'];
$billingSent=isset($_POST['widget-addBill-form-sent']) ? "1" : "0";
$ID=isset($_POST['ID']) ?$_POST['ID'] :  NULL;
$ID_OUT=isset($_POST['ID_OUT']) ?$_POST['ID_OUT'] :  NULL;
$billingSentDate=isset($_POST['widget-addBill-form-sendingDate']) ? date($_POST['widget-addBill-form-sendingDate']) : "0";
$billingPaid=isset($_POST['widget-addBill-form-paid']) ? "1" : "0";
$billingPaidDate=isset($_POST['widget-addBill-form-paymentDate']) ? date($_POST['widget-addBill-form-paymentDate']) : "0";
$billingLimitPaidDate=isset($_POST['widget-addBill-form-datelimite']) ? date($_POST['widget-addBill-form-datelimite']) : "0";
$communication=$_POST['communication'];


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

if($billingSent =="1" && $billingSentDate == "0")
{
    errorMessage("ES0031");
}

if($billingPaid =="1" && $billingPaidDate == "0")
{
    errorMessage("ES0032");
}


if($type=="autre" && !isset($typeOther)){
    errorMessage("ES0050");
}else if($type=="autre"){
    $type=$typeOther;
}



if($billType == "manual"){


    if(isset($_FILES['widget-addBill-form-file']))
    {     
        $dossier = $_SERVER['DOCUMENT_ROOT'].'/factures/';

        $extensions = array('.pdf');
        $extension = strrchr($_FILES['widget-addBill-form-file']['name'], '.');

        if($amountHTVA<0){
            $fileName=substr($date, 0, 10)."_".$beneficiaryCompany."_".$ID;
        }else{
            $fileName=substr($date, 0, 10)."_".$company."_".$ID;
        }

        if($ID && $beneficiaryCompany=='KAMEO'){
            $fileName=$fileName."_facture_".$ID_OUT;
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

         if(!move_uploaded_file($_FILES['widget-addBill-form-file']['tmp_name'], $dossier . $fichier)){
              errorMessage("ES0024");
         }


    }else{
        errorMessage("ES0035");
    }
}else{
    
    include 'connexion.php';
    $sql="select max(ID) as MAX_TOTAL, max(ID_OUT_BILL) as MAX_OUT from factures";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result3 = mysqli_query($conn, $sql);   
    $resultat = mysqli_fetch_assoc($result3);
    $newID=$resultat['MAX_TOTAL'];
    $newID=strval($newID+1);

    $newIDOUT=$resultat['MAX_OUT'];
    $newIDOUT=strval($newIDOUT+1);
    
    
    $bikesNumber=isset($_POST['bikesNumber']) ? $_POST['bikesNumber'] : NULL;
    $accessoriesNumber=isset($_POST['accessoriesNumber']) ? $_POST['accessoriesNumber'] : NULL;
    $otherAccessoriesNumber=isset($_POST['otherAccessoriesNumber']) ? $_POST['otherAccessoriesNumber'] : NULL;
    $i=0;
    while($i<$bikesNumber){
        $data['ID'.$i] = $_POST['bikeID'][$i];
        $data['price'.$i] = $_POST['bikeFinalPrice'][$i];
        $data['type'.$i] = "bikeSell";
        $i++;
    }
    $j=0;
    while($j<$accessoriesNumber){
        $data['ID'.$i] = $_POST['accessoryID'][$j];
        $data['price'.$i] = $_POST['accessoryFinalPrice'][$j];
        $data['type'.$i] = "accessorySell";
        $j++;
        $i++;
    }    
    $j=0;
    while($j<$otherAccessoriesNumber){
        $data['price'.$i] = $_POST['otherAccessoryFinalPrice'][$j];
        $data['description'.$i] = $_POST['otherAccessoryDescription'][$j];
        $data['type'.$i] = "otherAccessorySell";
        $j++;
        $i++;
    }
    $data['itemNumber'] = $i;
    $data['company'] = $company;
    $data['dateStart'] = $date;
    $data['billingGroup'] = "1";
        
    
    if(substr($_SERVER['REQUEST_URI'], 1, 4) != "test" && substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
        $test=CallAPI('POST', 'https://www.kameobikes.com/include/generate_bill.php', $data);
    }else if(substr($_SERVER['REQUEST_URI'], 1, 4) == "test"){
        $test=CallAPI('POST', 'https://www.kameobikes.com/test/include/generate_bill.php', $data);
    }else{
        $test=CallAPI('POST', 'localhost:81/kameo/include/generate_bill.php', $data);
    }
    
    $test=str_replace("./images/", "../images/", $test);
    $test=str_replace("./images_bikes/", "../images_bikes/", $test);
    
    error_log("Final result :".$test."\n", 3, "generate_bill.log");    
    
    try {
        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($test);        
        
        $path='/../factures/'.date('Y').'.'.date('m').'.'.date('d').'_'.$company.'_'.$newID.'_facture_'.$newIDOUT.'.pdf';       
        $html2pdf->Output(__DIR__ . $path, 'F');
        
    } catch (Html2PdfException $e) {
        $html2pdf->clean();
        $formatter = new ExceptionFormatter($e);
        $response = array ('response'=>'error', 'message'=> $formatter->getHtmlMessage());
        echo json_encode($response);
        die;
        
        
    }
    
    $fichier = date('Y').'.'.date('m').'.'.date('d').'_'.$company.'_'.$newID.'_facture_'.$newIDOUT.'.pdf';
    
    
}
    

if($company=="other"){
    $company=$companyOther;
}


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

include 'connexion.php';
if($billType=='manual'){
    if($ID && $beneficiaryCompany=='KAMEO'){
        $sql= "INSERT INTO  factures (ID, ID_OUT_BILL, USR_MAJ, HEU_MAJ, COMPANY, BENEFICIARY_COMPANY, DATE, AMOUNT_HTVA, AMOUNT_TVAINC, COMMUNICATION_STRUCTUREE, FILE_NAME, FACTURE_SENT, FACTURE_SENT_DATE, FACTURE_PAID, FACTURE_PAID_DATE, TYPE, FACTURE_LIMIT_PAID_DATE) VALUES ('$ID', '$ID_OUT', '$email', CURRENT_TIMESTAMP, '$company', '$beneficiaryCompany', '$date', '$amountHTVA', '$amountTVAC', '$communication', '$fichier', '$billingSent', $billingSentDate, '$billingPaid', $billingPaidDate, '$type', $billingLimitPaidDate)";
    }else{
        $sql= "INSERT INTO  factures (ID, ID_OUT_BILL, USR_MAJ, HEU_MAJ, COMPANY, BENEFICIARY_COMPANY, DATE, AMOUNT_HTVA, AMOUNT_TVAINC, COMMUNICATION_STRUCTUREE, FILE_NAME, FACTURE_SENT, FACTURE_SENT_DATE, FACTURE_PAID, FACTURE_PAID_DATE, TYPE, FACTURE_LIMIT_PAID_DATE) VALUES ('$ID', NULL, '$email', CURRENT_TIMESTAMP, '$company', '$beneficiaryCompany', '$date', '$amountHTVA', '$amountTVAC', '$communication', '$fichier', '$billingSent', $billingSentDate, '$billingPaid', $billingPaidDate, '$type', $billingLimitPaidDate)";
    }    

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
}else{
}

$conn->close();   
$response['sql']=$sql;
successMessage("SM0012");
?>
