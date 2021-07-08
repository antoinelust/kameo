<?php
$response=array();

$company=execSQL("select COMPANY from customer_referential WHERE TOKEN=?", array('s', $token), false)[0]['COMPANY'];

$sql="select aa.ID as ID, (SELECT companies.ID as companyID FROM companies WHERE companies.INTERNAL_REFERENCE=aa.COMPANY LIMIT 1) as companyID, COMPANY as company, BENEFICIARY_COMPANY as beneficiaryCompany, DATE as date, AMOUNT_HTVA as amountHTVA, AMOUNT_TVAINC as amountTVAC, FACTURE_SENT as sent, FACTURE_SENT_DATE as sentDate, FACTURE_PAID as paid, FACTURE_PAID_DATE as paidDate, FACTURE_LIMIT_PAID_DATE as limitPaidDate, FILE_NAME as fileName, COMMUNICATION_STRUCTUREE as communication, FACTURE_SENT_ACCOUNTING as communicationSentAccounting from factures aa";

if($company!=='KAMEO'){
  $response['update']=false;
	$sql=$sql." WHERE aa.COMPANY = '$company'";
}else{
  $response['update']=true;
}

$sql=$sql." GROUP BY aa.ID";

$response['bill']=execSQL($sql, array(), false);
$response['response']="success";

$information=execSQL("select LPAD(MAX(ID_OUT_BILL)+1, 3, '0') as reference, MAX(ID_OUT_BILL) as MAX_OUT, MAX(ID) as MAX_TOTAL from factures", array(), false)[0];
$response['IDMaxBillingOut']=$information['MAX_OUT'];
$newID=$information['MAX_TOTAL'];
$newID=strval($newID+1);
$reference=$newID;
$base_modulo=substr('00'.$reference, -6);
$base_modulo=date('Y').$base_modulo;
$modulo_check=($base_modulo % 97);
$modulo_check=substr('0'+$modulo_check, -2);
$reference=substr($base_modulo.$modulo_check, -12);
$reference=substr($reference, 0,3).'/'.substr($reference, 3,4).'/'.substr($reference, 7,5);

$response['communication']=$reference;
$response['IDMaxBilling']=$information['MAX_TOTAL'];
echo json_encode($response);
die;


?>
