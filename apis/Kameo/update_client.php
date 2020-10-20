<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
$fichier =fopen('update_client_logs.txt', 'a');
fwrite($fichier, json_encode($_POST) . "\r\n");
fclose($fichier);

$companyName = addslashes($_POST["widget_companyDetails_companyName"]);
$companyStreet = addslashes($_POST["widget_companyDetails_companyStreet"]);
$ZIPCode = $_POST["widget_companyDetails_companyZIPCode"];
$companyTown = addslashes($_POST["widget_companyDetails_companyTown"]);
$companyVAT = $_POST["widget_companyDetails_companyVAT"];
$type = isset($_POST["type"]) ? $_POST["type"] : $_POST["typeHidden"];
$audience = isset($_POST["audience"]) ? $_POST["audience"] : $_POST["typeHidden"];
$user = $_POST["widget_companyDetails_requestor"];
$internalReference = $_POST["widget_companyDetails_internalReference"];
$billing=isset($_POST['billing']) ? "Y" : "N";
$locking=isset($_POST['locking']) ? "Y" : "N";
$assistance=isset($_POST['assistance']) ? "Y" : "N";
$emailContactBilling=isset($_POST['email_billing']) ? $_POST['email_billing'] : NULL;
$firstNameContactBilling=isset($_POST['firstNameContactBilling']) ? $_POST['firstNameContactBilling'] : NULL;
$ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;
$lastNameContactBilling=isset($_POST['lastNameContactBilling']) ? $_POST['lastNameContactBilling'] : NULL;
$phoneBilling=isset($_POST['phoneBilling']) ? $_POST['phoneBilling'] : NULL;

if( $_SERVER['REQUEST_METHOD'] == 'POST') {

 if($user != '' && $ID!='') {

    include 'connexion.php';
  	$sql = "select ID from companies where INTERNAL_REFERENCE='$internalReference'";
  	if ($conn->query($sql) === FALSE) {
  		$response = array ('response'=>'error', 'message'=> $conn->error);
  		echo json_encode($response);
  		die;
  	}

      $result = mysqli_query($conn, $sql);
      if($result->num_rows=='0'){
          errorMessage("ES0037");
      }

  	$sql = "update companies set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$user', COMPANY_NAME='$companyName', STREET='$companyStreet', ZIP_CODE='$ZIPCode', TOWN='$companyTown',  VAT_NUMBER='$companyVAT', TYPE='$type', AUDIENCE='$audience', EMAIL_CONTACT_BILLING='$emailContactBilling', FIRSTNAME_CONTACT_BILLING='$firstNameContactBilling', LASTNAME_CONTACT_BILLING='$lastNameContactBilling', PHONE_CONTACT_BILLING='$phoneBilling', BILLS_SENDING='$billing' where ID='$ID'";

  	if ($conn->query($sql) === FALSE) {
  		$response = array ('response'=>'error', 'message'=> $conn->error);
  		echo json_encode($response);
  		die;
  	}
  	$result = mysqli_query($conn, $sql);

    $sql = "select * from conditions WHERE COMPANY='$internalReference'";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $length = $result->num_rows;

   if($length!=0){
       if($locking!=$resultat['LOCKING'] || $assistance!=$resultat['ASSISTANCE']){
          include 'connexion.php';
          $sql = "UPDATE conditions SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$user', ASSISTANCE='$assistance', LOCKING='$locking' WHERE COMPANY='$internalReference'";

          if ($conn->query($sql) === FALSE) {
              $response = array ('response'=>'error', 'message'=> $conn->error);
              echo json_encode($response);
              die;
          }
          $result = mysqli_query($conn, $sql);
       }
   }
   successMessage("SM0003");
  } else {
  	$response = array ('response'=>'error');
  	echo json_encode($response);
  	die;
  }
}
else
{
	errorMessage("ES0012");
}
?>
