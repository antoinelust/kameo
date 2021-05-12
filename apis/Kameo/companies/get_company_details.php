<?php
$company=isset($_GET['company']) ? $conn->real_escape_string($_GET['company']) : NULL;
$ID=isset($_GET['ID']) ? $conn->real_escape_string($_GET['ID']) : NULL;
$email=isset($_GET['email']) ? $conn->real_escape_string($_GET['email']) : NULL;

$response=array();

if($ID==NULL && $token != NULL){
  $ID=execSQL("SELECT bb.ID FROM customer_referential aa, companies bb where aa.TOKEN=? and aa.COMPANY=bb.INTERNAL_REFERENCE", array('s', $token), false)[0]['ID'];
}else if($ID==NULL && $company != NULL){
  $ID=execSQL("SELECT companies.ID FROM companies where INTERNAL_REFERENCE=?", array('s', $company), false)[0]['ID'];
}

$resultat=execSQL("SELECT ID, COMPANY_NAME as companyName, STREET as companyStreet, ZIP_CODE as companyZIPCode, TOWN as companyTown, VAT_NUMBER as companyVAT, TYPE as type, AUDIENCE as audience, INTERNAL_REFERENCE as internalReference FROM companies dd where ID=?", array('i', $ID), false)[0];
$response['response']="success";
$response = array_merge($response, $resultat);

if($company==NULL){
    $company = $resultat['internalReference'];
}

$resultat['contacts']=execSQL("SELECT * FROM companies_contact WHERE ID_COMPANY=?", array('i', $ID), false);
if(is_null($resultat['contacts'])){
  $resultat['contacts']=array();
}

$resultat=execSQL("SELECT * FROM conditions dd where COMPANY=? AND NAME='generic'", array('s', $company), false);
if(count($resultat)==0){
  $response['booking']="N";
  $response['assistance']="N";
  $response['locking']="N";
}else{
  $response['booking']=$resultat[0]['BOOKING'];
  $response['assistance']=$resultat[0]['ASSISTANCE'];
  $response['locking']=$resultat[0]['LOCKING'];
}

$response['bike']=execSQL("SELECT * FROM customer_bikes where COMPANY=? AND STAANN != 'D'", array('s', $company), false);
if(is_null($response['bike'])){
  $response['bike']=array();
}

$response['bikeNumber']=count($response['bike']);

$response['externalBikes']=execSQL("SELECT * FROM external_bikes where COMPANY_ID=?", array('i', $ID), false);
if(is_null($response['externalBikes'])){
  $response['externalBikes']=array();
}



///////////////////

$sql="SELECT CONTRACT_START, CONTRACT_END, SUM(LEASING_PRICE) as 'PRICE', COUNT(1) as 'BIKE_NUMBER' FROM `customer_bikes` WHERE COMPANY = '$company' AND AUTOMATIC_BILLING='Y' GROUP BY CONTRACT_START, CONTRACT_END";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$result = mysqli_query($conn, $sql);
$response['bikeContracts']=$result->num_rows;
$i=0;
while($row = mysqli_fetch_array($result)){
    $response['offer'][$i]['id']="N/A";
    $response['offer'][$i]['description']=$row['BIKE_NUMBER']." vélos en leasing";
    $response['offer'][$i]['probability']="signé";
    $response['offer'][$i]['type']="N/A";
    $response['offer'][$i]['amount']=$row['PRICE']." €/mois";
    $response['offer'][$i]['margin']="N/A";
    $response['offer'][$i]['start']=$row['CONTRACT_START'];
    $response['offer'][$i]['end']=$row['CONTRACT_END'];
    $i++;
}


///////////////////

$sql="SELECT * FROM offers dd where COMPANY='$company' AND STAANN != 'D'";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$result = mysqli_query($conn, $sql);
$response['offerNumber']=$result->num_rows;
while($row = mysqli_fetch_array($result)){
    $response['offer'][$i]['id']=$row['ID'];
    $response['offer'][$i]['title']=$row['TITRE'];
    $response['offer'][$i]['description']=$row['DESCRIPTION'];
    $response['offer'][$i]['probability']=$row['PROBABILITY'];
    $response['offer'][$i]['type']=$row['TYPE'];
    $response['offer'][$i]['amount']=$row['AMOUNT'];
    $response['offer'][$i]['margin']=$row['MARGIN'];
    $response['offer'][$i]['date']=$row['DATE'];
    $response['offer'][$i]['start']=$row['START'];
    $response['offer'][$i]['end']=$row['START'];
    $response['offer'][$i]['status']=$row['STATUS'];
    $response['offer'][$i]['file']=$row['FILE_NAME'];
    $i++;
}

$sql="SELECT * FROM customer_referential dd where COMPANY='$company' AND STAANN != 'D'";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$result = mysqli_query($conn, $sql);
$i=0;
while($row = mysqli_fetch_array($result)){
    $response['user'][$i]['name']=$row['NOM'];
    $response['user'][$i]['firstName']=$row['PRENOM'];
    $response['user'][$i]['email']=$row['EMAIL'];
    $response['user'][$i]['phone']=$row['PHONE'];
    $i++;

}
$response['userNumber']=$i;

$sql="SELECT * FROM factures dd where COMPANY='$company'";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$result = mysqli_query($conn, $sql);
$length = $result->num_rows;

$response['billNumber']=$length;
$conn->close();

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


echo json_encode($response);
die;
?>
