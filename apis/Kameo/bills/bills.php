<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

require_once __DIR__ .'/../globalfunctions.php';
require_once __DIR__ .'/../authentication.php';
require_once __DIR__ .'/../connexion.php';

$token = getBearerToken();

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;

    if($action === 'retrieve'){
			if(get_user_permissions("bills", $token)){
			}else{
				error_message('403');
			}
    }else if($action === 'list'){
			if(get_user_permissions(["admin", "bills"], $token)){
				include 'get_bills_listing.php';
			}else{
				error_message('403');
			}
		}else if($action === 'getLinkBikesBillsDetails'){
			if(get_user_permissions(["admin", "bikesStock"], $token)){
				include 'getLinkBikesBillsDetails.php';
			}else{
				error_message('403');
			}
		}else if($action === 'listCompaniesWithLeasing'){
			if(get_user_permissions("bills", $token)){
				include 'get_list_of_companies_with_leasing.php';
			}else
				error_message('403');
		}else if($action === 'listBikesNotLinkedToBill'){
			if(get_user_permissions(["admin", "bikesStock"], $token)){
				$response=execSQL("SELECT * FROM customer_bikes WHERE TYPE=? AND NOT EXISTS(SELECT 1 from bills_catalog_bikes_link where bills_catalog_bikes_link.BIKE_ID=customer_bikes.ID) and STAANN != 'D' AND CONTRACT_TYPE in ('selling', 'leasing', 'renting', 'stock', 'pending_delivery')", array('i', $_GET['catalogID']), false);
				if($response == null){
					$response=array();
				}
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'getContactsForBillingSending'){
			if(get_user_permissions(["admin"], $token)){
				$result=execSQL('SELECT * FROM factures WHERE ID=?', array('i', $_GET['ID']), false)[0];
				if($result['EMAIL'] == NULL || $result['EMAIL'] == ''){
					$response['beneficiaries']=execSQL("SELECT companies_contact.NOM, companies_contact.PRENOM, companies_contact.EMAIL FROM companies_contact, companies WHERE companies.INTERNAL_REFERENCE=? AND companies_contact.ID_COMPANY=companies.ID AND companies_contact.TYPE='billing' GROUP BY companies_contact.NOM, companies_contact.PRENOM, companies_contact.EMAIL", array('s', $result['COMPANY']), false);
					$response['cc']=execSQL("SELECT companies_contact.NOM, companies_contact.PRENOM, companies_contact.EMAIL FROM companies_contact, companies WHERE companies.INTERNAL_REFERENCE=? AND companies_contact.ID_COMPANY=companies.ID AND companies_contact.TYPE='ccBilling' GROUP BY companies_contact.NOM, companies_contact.PRENOM, companies_contact.EMAIL", array('s', $result['COMPANY']), false);
					if($response['beneficiaries'] == null){
						$response['beneficiaries']=array();
					}
				}else{
					$response['beneficiaries']=execSQL("SELECT NOM, PRENOM, EMAIL FROM customer_referential WHERE EMAIL=?", array('s', $result['EMAIL']), false);
				}
				if($response['cc'] == null){
					$response['cc']=array();
				}
				echo json_encode($response);
				die;
			}else
				error_message('403');
		}else if($action === 'graphic'){
			if(get_user_permissions("bills", $token)){
				include 'graphic_companies.php';
			}else{
				error_message('403');
			}
		}else
			error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;

		if($action === 'addCompanyContact'){
			if(get_user_permissions("bills", $token)){
				include 'add_company_contact.php';
			}else{
				error_message('403');
			}
		}else if($action === 'editCompanyContact'){
			if(get_user_permissions("bills", $token)){
				include 'edit_company_contact.php';
			}else{
				error_message('403');
			}
		}else if($action === 'linkBikeToBill'){
			if(get_user_permissions(["admin", "bikesStock"], $token)){
				execSQL("UPDATE bills_catalog_bikes_link SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, BIKE_ID=? WHERE ID=?", array('sii', $token, $_POST['bikeID'], $_POST['ID']), true);
				successMessage("SM0003");
			}else
				error_message('403');
		}else if($action === 'sendBill'){
			if(get_user_permissions(["admin"], $token)){
				include 'send_bill.php';
			}else
				error_message('403');
		}else if($action=="delete"){
			if(get_user_permissions("admin", $token)){

				if(isset($_POST['reference'])){
						$reference=$_POST['reference'];
						$resultat=execSQL("SELECT * from factures where ID=?", array('s', $reference), false);
						foreach($resultat as $result){
							if(file_exists($_SERVER['DOCUMENT_ROOT'].'/factures/'.$result['FILE_NAME'])){
									unlink($_SERVER['DOCUMENT_ROOT'].'/factures/'.$result['FILE_NAME']);
							}
						}
						$result=execSQL("SELECT * from factures_details where FACTURE_ID=?", array('s', $reference), false);
						foreach($result as $item){
							if($item['ITEM_TYPE']=="accessory"){
								execSQL("UPDATE order_accessories SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, STATUS='confirmed' WHERE ACCESSORY_ID=?", array('si', $token, $item['ITEM_ID']), true);
								execSQL("UPDATE accessories_stock SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, CONTRACT_TYPE='pending_delivery', CONTRACT_START=NULL, CONTRACT_END=NULL, CONTRACT_AMOUNT=NULL, SELLING_DATE=NULL, SELLING_AMOUNT=NULL WHERE ID=?", array('si', $token, $item['ITEM_ID']), true);
							}else if($item['ITEM_TYPE']=="bike"){
								execSQL("UPDATE client_orders SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, STATUS='confirmed' WHERE BIKE_ID=?", array('si', $token, $item['ITEM_ID']), true);
								execSQL("UPDATE customer_bikes SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, CONTRACT_TYPE='pending_delivery', SELLING_DATE=NULL, SELLING_AMOUNT=NULL WHERE ID=?", array('si', $token, $item['ITEM_ID']), true);
							}
						}
						execSQL("DELETE FROM factures where ID=?", array('s', $reference), true);
						execSQL("DELETE FROM factures_details where FACTURE_ID=?", array('s', $reference), true);

						successMessage("SM0016");
				}else{
						errorMessage("ES0012");
				}
			}else{
				error_message('403');
			}
		}else if($action=="update"){
			if(get_user_permissions("admin", $token)){
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
				$accountingSent=isset($_POST['accounting']) ? "1" : "0";
				if(isset($_FILES['widget-updateBillingStatus-form-file'])){
						$extensions = array('.pdf');
						$extension = strrchr($_FILES['widget-updateBillingStatus-form-file']['name'], '.');
						if(!in_array($extension, $extensions))
						{
									errorMessage("ES0034");
						}

						$taille_maxi = 6291456;
						$taille = filesize($_FILES['widget-updateBillingStatus-form-file']['tmp_name']);
						if($taille>$taille_maxi)
						{
									errorMessage("ES0023");
						}

						//upload of Bike picture

						$dossier = $_SERVER['DOCUMENT_ROOT'].'/factures/';
						if(isset($_POST['widget-updateBillingStatus-form-currentFile']) && $_POST['widget-updateBillingStatus-form-currentFile'] != ''){
								$currentFile=$_POST['widget-updateBillingStatus-form-currentFile'];
								if(file_exists($dossier.$currentFile)){
										unlink($dossier.$currentFile) or die("Couldn't delete file");
								}
						}

						if($amountHTVA<0){
								$fichier=substr($date, 0, 10)."_".$beneficiary."_".$IDBilling.".pdf";
						}else{
								$fichier=substr($date, 0, 10)."_".$company."_".$IDBilling.".pdf";
						}

						if(!move_uploaded_file($_FILES['widget-updateBillingStatus-form-file']['tmp_name'], $dossier.$fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
						{
								errorMessage("ES0024");
						}
						execSQL("UPDATE factures set FILE_NAME=? where ID=?", array('si', $fichier, $IDBilling), true);
				}


				if($amountHTVA<0 && $originator!="KAMEO"){errorMessage("ES0045");}
				if($amountHTVA>0 && $originator=="KAMEO"){errorMessage("ES0047");}
				if($amountHTVA<0 && $beneficiary=="KAMEO"){errorMessage("ES0046");}
				if($amountHTVA>0 && $beneficiary!="KAMEO"){errorMessage("ES0048");}
				if($billingSentDate==""){$billingSentDate=null;}
				if($billingPaidDate==""){$billingPaidDate=null;}
				if($billingSent =="1" && $billingSentDate == null){errorMessage("ES0031");}
				if($billingPaid =="1" && $billingPaidDate == null){errorMessage("ES0032");}

				if( $IDBilling!=""){
						execSQL("UPDATE factures set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ=?, COMPANY=?, BENEFICIARY_COMPANY=?, DATE=?, AMOUNT_HTVA=?, AMOUNT_TVAINC=?, FACTURE_SENT=?, FACTURE_SENT_DATE=?, FACTURE_PAID=?, FACTURE_PAID_DATE=?, FACTURE_LIMIT_PAID_DATE=?, COMMUNICATION_STRUCTUREE=?, FACTURE_SENT_ACCOUNTING=?  where ID=?",
						array('ssssddisisssii', $token, $originator, $beneficiary, $date, $amountHTVA, $amountTVA, $billingSent, $billingSentDate, $billingPaid, $billingPaidDate, $billingLimitPaidDate, $communication, $accountingSent, $IDBilling), true);
						successMessage("SM0003");
				}else{
						errorMessage("ES0012");
				}
			}else {
				error_message('403');
			}

		}else{
			error_message('405');
		}
		break;
	default:
				error_message('405');
		break;
}
$conn->close();
?>
