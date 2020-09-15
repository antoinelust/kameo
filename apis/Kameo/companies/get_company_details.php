<?php

if (isset($_GET['ID']) || isset($_GET['email'])){

  $company=isset($_GET['company']) ? $conn->real_escape_string($_GET['company']) : NULL;
  $ID=isset($_GET['ID']) ? $conn->real_escape_string($_GET['ID']) : NULL;
  $email=isset($_GET['email']) ? $conn->real_escape_string($_GET['email']) : NULL;

  $response=array();

  if($ID==NULL && $email==NULL){
      errorMessage("ES0012");
  }

  if($ID==NULL && $email != NULL){
      $stmt = $conn->prepare("SELECT ID FROM customer_referential, companies dd where aa.EMAIL=? and aa.COMPANY=bb.INTERNAL_REFERENCE");
      if ($stmt)
      {
          $stmt->bind_param("s", $email);
          $stmt->execute();
          $ID = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['ID'];
          $stmt->close();
      }else{
          error_message('500', 'Unable to retrieve company details');
          $stmt->close();
      }
  }


  if($ID != NULL){
      $stmt = $conn->prepare("SELECT ID, COMPANY_NAME as companyName, STREET as companyStreet, ZIP_CODE as companyZIPCode, TOWN as companyTown, VAT_NUMBER as companyVAT, TYPE as type, EMAIL_CONTACT_BILLING as emailContactBilling, FIRSTNAME_CONTACT_BILLING as firstNameContactBilling, LASTNAME_CONTACT_BILLING as lastNameContactBilling, PHONE_CONTACT_BILLING as phoneContactBilling, BILLS_SENDING as automaticBilling, INTERNAL_REFERENCE as internalReference FROM companies dd where ID=?");
      if ($stmt)
      {
          $stmt->bind_param("i", $ID);
          $stmt->execute();
      }else{
          error_message('500', 'Unable to retrieve company details');
          $stmt->close();
      }

  }else{
      $stmt = $conn->prepare("SELECT ID, COMPANY_NAME as companyName, STREET as companyStreet, ZIP_CODE as companyZIPCode, TOWN as companyTown, VAT_NUMBER as companyVAT, TYPE as type, EMAIL_CONTACT_BILLING as emailContactBilling, FIRSTNAME_CONTACT_BILLING as firstNameContactBilling, LASTNAME_CONTACT_BILLING as lastNameContactBilling, PHONE_CONTACT_BILLING as phoneContactBilling, BILLS_SENDING as automaticBilling, INTERNAL_REFERENCE as internalReference FROM companies dd where INTERNAL_REFERENCE='?'");
      if ($stmt)
      {
          $stmt->bind_param("s", $company);
          $stmt->execute();
      }else{
          error_message('500', 'Unable to retrieve company details');
          $stmt->close();
      }
  }
  $response['response']="success";
  $resultat = $stmt->get_result()->fetch_assoc();
  $response = array_merge($response, $resultat);
  $stmt->close();


  if($company==NULL){
      $company = $resultat['internalReference'];
  }


  $sql="SELECT * FROM conditions dd where COMPANY='$company'";

  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }

  $result = mysqli_query($conn, $sql);
  $resultat = mysqli_fetch_assoc($result);

  $response['assistance']=$resultat['ASSISTANCE'];
  $response['locking']=$resultat['LOCKING'];

  $sql="SELECT * FROM customer_bikes dd where COMPANY='$company' AND STAANN != 'D'";

  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }
  $result = mysqli_query($conn, $sql);

  $response['bikeNumber']=$result->num_rows;

  $i=0;
  while($row = mysqli_fetch_array($result)){
      $response['bike'][$i]['id']=$row['ID'];
      $response['bike'][$i]['heuMaj']=$row['HEU_MAJ'];
      $response['bike'][$i]['frameNumber']=$row['FRAME_NUMBER'];
      $bikeID=$row['ID'];
      $response['bike'][$i]['model']=$row['MODEL'];
      $response['bike'][$i]['facturation']=$row['AUTOMATIC_BILLING'];
      $response['bike'][$i]['leasingPrice']=$row['LEASING_PRICE'];
      $response['bike'][$i]['contractType']=$row['CONTRACT_TYPE'];
      $response['bike'][$i]['contractStart']=$row['CONTRACT_START'];
      $response['bike'][$i]['contractEnd']=$row['CONTRACT_END'];
      $response['bike'][$i]['deliveryDate']=$row['DELIVERY_DATE'];
      $response['bike'][$i]['bikeBuyingDate']=$row['BIKE_BUYING_DATE'];
      $response['bike'][$i]['orderNumber']=$row['ORDER_NUMBER'];

      $sql2="SELECT * FROM bike_building_access dd where BIKE_ID='$bikeID'";

      if ($conn->query($sql2) === FALSE) {
          $response = array ('response'=>'error', 'message'=> $conn->error);
          echo json_encode($response);
          die;
      }

      $result2 = mysqli_query($conn, $sql2);
      $j=0;
      while($row2 = mysqli_fetch_array($result2)){
          $response['bike'][$i]['building'][$j]['buildingCode']=$row2['BUILDING_CODE'];
          $j++;
      }
      $response['bike'][$i]['buildingNumber']=$j;

      $i++;
  }
  $response['bikeNumber']=$i;

  $sql="SELECT * FROM building_access dd where COMPANY='$company'";

  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }

  $result = mysqli_query($conn, $sql);
  $response['buildingNumber']=$result->num_rows;
  $i=0;
  while($row = mysqli_fetch_array($result)){
      $response['building'][$i]['buildingReference']=$row['BUILDING_REFERENCE'];
      $response['building'][$i]['buildingFR']=$row['BUILDING_FR'];
      $response['building'][$i]['buildingNL']=$row['BUILDING_NL'];
      $response['building'][$i]['buildingEN']=$row['BUILDING_EN'];
      $response['building'][$i]['address']=$row['ADDRESS'];
      $i++;

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
}

function get_company(){
  require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';
  require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/authentication.php';
  include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';

  $token = getBearerToken();
  $stmt = $conn->prepare("SELECT COMPANY as company FROM customer_referential where TOKEN=?");
  if ($stmt)
  {
      $stmt->bind_param("s", $token);
      $stmt->execute();
      $response['contact']=$stmt->get_result()->fetch_array(MYSQLI_ASSOC);
      $stmt->close();
      return $response;
  }else{
      error_message('500', 'Unable to retrieve company');
      $stmt->close();
  }
}
?>
