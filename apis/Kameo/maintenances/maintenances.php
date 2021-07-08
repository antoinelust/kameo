<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60))); // 1 hour
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

require_once __DIR__ .'/../globalfunctions.php';
require_once __DIR__ .'/../authentication.php';
require_once __DIR__ .'/../connexion.php';

$token = getBearerToken();

log_inputs($token);

switch($_SERVER["REQUEST_METHOD"])
{

	case 'GET':
	$action=isset($_GET['action']) ? $_GET['action'] : NULL;
	if($action === 'retrieve'){
		if(get_user_permissions("admin", $token)){
			include 'retrieveMaintenance.php';
		}
	}
	else if ($action==='listNoneDevis'){
		if(get_user_permissions("admin", $token)){
			echo json_encode(execSQL("SELECT * FROM devis_entretien WHERE STATUS='NONE'", array(), false));
			die;
		}
	}
	else if ($action==='listDoneDevis'){
		if(get_user_permissions("admin", $token)){
			echo json_encode(execSQL("SELECT * FROM devis_entretien WHERE STATUS='DONE'", array(), false));
			die;
		}
	}else if ($action == "listAllMaintenances") {
    $response = array ();
    $date_start = new DateTime($_GET['dateStart']);
    $date_start_string=$date_start->format('Y-m-d');

    $date_end = new DateTime($_GET['dateEnd']);
    $date_end_string=$date_end->format('Y-m-d');

    $response['maintenance'] = execSQL("SELECT tt.*,
			(CASE
			 WHEN tt.type='personnel' THEN (SELECT PHONE FROM customer_referential, customer_bike_access WHERE customer_referential.EMAIL=customer_bike_access.EMAIL AND customer_bike_access.BIKE_ID=tt.BIKE_ID LIMIT 1)
			 WHEN tt.type='partage' OR tt.type='vendu' THEN (SELECT companies_contact.PHONE FROM companies_contact, customer_bikes, companies WHERE customer_bikes.ID=tt.BIKE_ID AND companies.INTERNAL_REFERENCE=customer_bikes.COMPANY AND companies_contact.ID_COMPANY=companies.ID LIMIT 1)
			 WHEN tt.type='externe' THEN (SELECT companies_contact.PHONE FROM companies_contact, external_bikes WHERE external_bikes.ID=tt.BIKE_ID AND external_bikes.COMPANY_ID=companies_contact.ID_COMPANY LIMIT 1)
			 ELSE 'undefined'
			 END
			) as PHONE,

			(CASE
			 WHEN tt.type='personnel' OR tt.type='partage' OR tt.type='vendu' THEN (SELECT companies.COMPANY_NAME FROM companies, customer_bikes WHERE customer_bikes.COMPANY=companies.INTERNAL_REFERENCE AND customer_bikes.ID=tt.BIKE_ID LIMIT 1)
			 WHEN tt.type='externe' THEN (SELECT companies.COMPANY_NAME FROM companies, external_bikes WHERE external_bikes.COMPANY_ID=companies.ID AND external_bikes.ID=tt.BIKE_ID LIMIT 1)
			 ELSE 'undefined'
			 END
			) as COMPANY_NAME,

			(CASE
			 WHEN tt.type='personnel' OR tt.type='partage' OR tt.type='vendu' THEN (SELECT CONCAT(bike_catalog.BRAND, ' - ', bike_catalog.MODEL) FROM bike_catalog, customer_bikes WHERE customer_bikes.TYPE=bike_catalog.ID AND customer_bikes.ID=tt.BIKE_ID)
			 WHEN tt.type='externe' THEN (SELECT CONCAT(external_bikes.BRAND, ' - ', external_bikes.MODEL) FROM external_bikes WHERE external_bikes.ID=tt.BIKE_ID)
			 ELSE 'undefined'
			 END
			) as MODEL,


			(CASE
			WHEN EXISTS (SELECT 1 FROM factures_details WHERE factures_details.ITEM_TYPE='maintenance' AND factures_details.ITEM_ID=tt.ID) THEN 'OK'
			WHEN tt.AVOID_BILLING=1 THEN 'OK'
			WHEN tt.type IN ('vendu', 'externe') AND tt.AVOID_BILLING=0 AND NOT EXISTS (SELECT 1 FROM factures_details WHERE factures_details.ITEM_TYPE='maintenance' AND factures_details.ITEM_ID=tt.ID) THEN 'KO'
			WHEN tt.TYPE IN ('partage', 'personnel') THEN 'OK'
			ELSE 'undefined'
			END) as 'paid'

			FROM
			(SELECT entretiens.ID, entretiens.BIKE_ID, entretiens.EXTERNAL_BIKE, entretiens.DATE, entretiens.STATUS, entretiens.OUT_DATE_PLANNED, entretiens.AVOID_BILLING, entretiens.ADDRESS,

			(CASE
			 WHEN entretiens.EXTERNAL_BIKE=0 THEN
			 	CASE
			 	WHEN EXISTS (SELECT 1 FROM customer_bikes, customer_bike_access WHERE entretiens.BIKE_ID=customer_bikes.ID and customer_bikes.CONTRACT_TYPE in ('leasing', 'renting') AND customer_bike_access.TYPE='personnel' AND customer_bike_access.BIKE_ID=customer_bikes.ID) THEN 'personnel'
			 	WHEN EXISTS (SELECT 1 FROM customer_bikes WHERE entretiens.BIKE_ID=customer_bikes.ID and customer_bikes.CONTRACT_TYPE in ('leasing', 'renting')) AND NOT EXISTS (SELECT 1 FROM customer_bike_access WHERE customer_bike_access.TYPE='personnel' AND customer_bike_access.BIKE_ID=entretiens.BIKE_ID) THEN 'partage'
			 	WHEN EXISTS (SELECT 1 FROM customer_bikes WHERE entretiens.BIKE_ID=customer_bikes.ID and customer_bikes.CONTRACT_TYPE in ('selling')) THEN 'vendu'
			 	ELSE 'undefined'
			 	END
			 WHEN entretiens.EXTERNAL_BIKE=1 THEN 'externe'
			 ELSE 'undefined'
			 END) as type


			 FROM entretiens WHERE entretiens.DATE >= ? AND entretiens.DATE <= ? ) as tt", array('ss', $date_start_string, $date_end_string), false);
    $response['response'] = 'success';
		echo json_encode($response);
    die;
  }else if($action === 'list'){
		if(get_user_permissions("admin", $token)){
			if(isset($_GET['company'])){
				$response['internalMaintenances']=execSQL("SELECT entretiens.ID, entretiens.DATE, bike_catalog.BRAND, bike_catalog.MODEL FROM entretiens, customer_bikes, bike_catalog WHERE entretiens.STATUS='DONE' AND BIKE_ID=customer_bikes.ID  AND customer_bikes.TYPE=bike_catalog.ID AND customer_bikes.COMPANY=? ORDER BY entretiens.ID DESC", array('s', $_GET['company']), false);
				$response['externalMaintenances']=execSQL("SELECT entretiens.ID, entretiens.DATE, external_bikes.BRAND, external_bikes.MODEL FROM entretiens, external_bikes WHERE entretiens.STATUS='DONE' AND BIKE_ID=external_bikes.ID  AND external_bikes.COMPANY_ID=(SELECT ID FROM companies WHERE INTERNAL_REFERENCE=?) ORDER BY entretiens.ID DESC", array('s', $_GET['company']), false);
				if(is_null($response['internalMaintenances'])){
					$response['internalMaintenances']=array();
				}
				if(is_null($response['externalMaintenances'])){
					$response['externalMaintenances']=array();
				}
				echo json_encode($response);
				die;
			}else{
				echo json_encode(execSQL("SELECT * FROM entretiens WHERE BIKE_ID IN (SELECT ID FROM customer_bikes WHERE COMPANY = (SELECT COMPANY FROM customer_referential WHERE TOKEN = ?)) ORDER BY ID DESC", array('s', $token), false));
				die;
			}
		}
	}else if($action === 'listServices'){
		if(get_user_permissions("admin", $token)){
			echo json_encode(execSQL("SELECT * FROM services_entretiens WHERE CATEGORY = ?", array('s', $_GET['category']), false));
			die;
		}
	}else if($action === 'listCategories'){
		if(get_user_permissions("admin", $token)){
			echo json_encode(execSQL("SELECT CATEGORY FROM services_entretiens GROUP BY CATEGORY ORDER BY CATEGORY", array(), false));
			die;
		}
	}else if($action === "getContacts"){
		if(get_user_permissions("admin", $token)){
			$result=execSQL("SELECT EXTERNAL_BIKE, BIKE_ID FROM entretiens WHERE ID=?", array('i', $_GET['maintenanceID']), false)[0];
			$bikeID=$result['BIKE_ID'];
			$externalBike=$result['EXTERNAL_BIKE'];
			if($externalBike=='0'){
				$result=execSQL("SELECT * FROM customer_bike_access WHERE BIKE_ID=(SELECT BIKE_ID FROM entretiens WHERE ID=?) AND TYPE='personnel' AND STAANN != 'D'", array('i', $_GET['maintenanceID']), false);
				if(count($result)>0){
					$contact=execSQL("SELECT PHONE, NOM, PRENOM, EMAIL from customer_referential WHERE EMAIL=?", array('i', $result['EMAIL']), false);
				}else{
					$contact=execSQL("SELECT PHONE, NOM, PRENOM, EMAIL FROM companies_contact WHERE TYPE='contact' AND ID_COMPANY=(SELECT ID FROM companies WHERE INTERNAL_REFERENCE = (SELECT COMPANY FROM customer_bikes WHERE ID=?))", array('i', $bikeID), false);
				}
			}else{
				$contact=execSQL("SELECT PHONE, NOM, PRENOM, EMAIL FROM companies_contact WHERE TYPE='contact' AND ID_COMPANY=(SELECT COMPANY_ID FROM external_bikes WHERE ID = ?)", array('i', $bikeID), false);
			}
			echo json_encode($contact);
			die;
		}
	}
	else
	error_message('405');
	break;
	case 'POST':
	$action=isset($_POST['action']) ? $_POST['action'] : NULL;
	if($action === 'add'){
		if(get_user_permissions("admin", $token)){
			$user = isset($_POST["user"]) ? $_POST["user"] : NULL;
			$date = isset($_POST["dateMaintenance"]) ? date('Y-m-d',strtotime($_POST["dateMaintenance"])): NULL;
			$status = isset($_POST["status"]) ? $_POST["status"] : NULL;
			$comment = isset($_POST["comment"]) ? addslashes($_POST["comment"]) : NULL;
			$internalComment = isset($_POST["internalComment"]) ? addslashes($_POST["internalComment"]) : NULL;
			$bike_id = isset($_POST["velo"]) ? $_POST["velo"] : NULL;
			$external = isset($_POST["external"]) ? $_POST["external"] : NULL;
			$outDatePlanned = isset($_POST["dateOutPlanned"]) ? $_POST["dateOutPlanned"] : NULL;
			if(isset($_POST['maintenanceatKAMEO'])){
				$address="8 Rue de la Brasserie, 4000 Liège";
			}else{
				$address=$_POST['address'];
			}

			if($status=='DONE'){
				$id=execSQL("INSERT INTO entretiens (HEU_MAJ,END_DATE_MAINTENANCE, USR_MAJ, BIKE_ID, EXTERNAL_BIKE, DATE, STATUS, COMMENT, INTERNAL_COMMENT, NR_ENTR,OUT_DATE_PLANNED, ADDRESS ) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)", array('siissssss', $user, $bike_id, $external, $date, $status, $comment, $internalComment,$outDatePlanned, $address), true);
			}
			else if($status=='DELIVERED_TO_CLIENT'){
				$id=execSQL("INSERT INTO entretiens (HEU_MAJ,OUT_DATE, USR_MAJ, BIKE_ID, EXTERNAL_BIKE, DATE, STATUS, COMMENT, INTERNAL_COMMENT, NR_ENTR, OUT_DATE_PLANNED ) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)", array('siissssss', $user, $bike_id, $external, $date, $status, $comment, $internalComment,$outDatePlanned, $address), true);
			}
			else{
				$id=execSQL("INSERT INTO entretiens (HEU_MAJ, USR_MAJ, BIKE_ID, EXTERNAL_BIKE, DATE, STATUS, COMMENT, INTERNAL_COMMENT, NR_ENTR, OUT_DATE_PLANNED, ADDRESS ) VALUES (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)", array('siissssss', $user, $bike_id, $external, $date, $status, $comment, $internalComment, $outDatePlanned, $address), true);
			}

			if(isset($_POST['service'])){
				$services['ID']=$_POST['service'];
				$services['length']=$_POST['manualWorkloadLength'];
				$services['amount']=$_POST['manualWorkloadTotal'];
				for ($i = 0; $i < count($services['ID']); $i++) {
					execSQL("INSERT INTO entretiens_details (USR_MAJ, TYPE, MAINTENANCE_ID, SERVICE, DURATION, AMOUNT) VALUES (?, 'service', ?, ?, ?, ?)", array('siiid', $token, $id, $services['ID'][$i], $services['length'][$i], $services['amount'][$i]), true);
				}
			}
			if(isset($_POST['linkIDStock'])){
				$accessory['linkIDStock']=$_POST['linkIDStock'];
				$accessory['amount']=$_POST['accessoryAmount'];
				for ($i = 0; $i < count($accessory['linkIDStock']); $i++) {
					execSQL("INSERT INTO entretiens_details (USR_MAJ, TYPE, MAINTENANCE_ID, SERVICE, AMOUNT) VALUES (?, 'accessory', ?, ?, ?)", array('siid', $token, $id, $accessory['linkIDStock'][$i], $accessory['amount'][$i]), true);
				}
			}
			if(isset($_POST['otherAccessoryDescription'])){
				$accessory['description']=$_POST['otherAccessoryDescription'];
				$accessory['amount']=$_POST['otherAccessoryAmount'];
				for ($i = 0; $i < count($accessory['description']); $i++) {
					execSQL("INSERT INTO entretiens_details (USR_MAJ, TYPE, MAINTENANCE_ID, DESCRIPTION, AMOUNT) VALUES (?, 'otherAccessory', ?, ?, ?)", array('sisd', $token, $id, $accessory['description'][$i], $accessory['amount'][$i]), true);
				}
			}

			$response=array('response'=>"success", "message"=>"Entretien ajouté avec succès");
			echo json_encode($response);
			die;
		}else
		error_message('403');
	}if($action === 'update'){
		if(get_user_permissions("admin", $token)){
			$id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
			$date = isset($_POST["dateMaintenance"]) ? date('Y-m-d',strtotime($_POST["dateMaintenance"])): NULL;
			$status = isset($_POST["status"]) ? $_POST["status"] : NULL;
			$comment = isset($_POST["comment"]) ? $_POST["comment"] : NULL;
			$internalComment = isset($_POST["internalComment"]) ? $_POST["internalComment"] : NULL;
			$bike_id = isset($_POST["velo"]) ? $_POST["velo"] : NULL;
			$outDatePlanned = isset($_POST["dateOutPlanned"]) ? $_POST["dateOutPlanned"] : NULL;
			$clientWarned = isset($_POST['clientWarned']) ? 1 : 0;

			if(isset($_POST['maintenanceatKAMEO'])){
				$address="8 Rue de la Brasserie, 4000 Liège";
			}else{
				$address=$_POST['address'];
			}

			if($status=='DONE'){
				$sql =execSQL("UPDATE entretiens SET USR_MAJ = ?, HEU_MAJ = CURRENT_TIMESTAMP, END_DATE_MAINTENANCE =CURRENT_TIMESTAMP , DATE = ?, STATUS = ?, COMMENT = ?, INTERNAL_COMMENT=?,OUT_DATE_PLANNED=?, CLIENT_WARNED=?, ADDRESS=? WHERE ID = ?;", array('ssssssisi', $token, $date, $status, $comment, $internalComment,$outDatePlanned, $clientWarned, $address, $id), true);
			}
			else if($status=='DELIVERED_TO_CLIENT'){
				$sql =execSQL("UPDATE entretiens SET USR_MAJ = ?, HEU_MAJ = CURRENT_TIMESTAMP,OUT_DATE = CURRENT_TIMESTAMP, DATE = ?, STATUS = ?, COMMENT = ?, INTERNAL_COMMENT=?,OUT_DATE_PLANNED=?, CLIENT_WARNED=?, ADDRESS=? WHERE ID = ?;", array('ssssssisi', $token, $date, $status, $comment, $internalComment,$outDatePlanned, $clientWarned, $address, $id), true);
			}
			else{
				$sql =execSQL("UPDATE entretiens SET USR_MAJ = ?, HEU_MAJ = CURRENT_TIMESTAMP, DATE = ?, STATUS = ?, COMMENT = ?, INTERNAL_COMMENT=?,OUT_DATE_PLANNED=?, CLIENT_WARNED=?, ADDRESS=? WHERE ID = ?;", array('ssssssisi', $token, $date, $status, $comment, $internalComment, $outDatePlanned, $clientWarned, $address, $id), true);
			}
			if(isset($_POST['service'])){
				$services['ID']=$_POST['service'];
				$services['length']=$_POST['manualWorkloadLength'];
				$services['amount']=$_POST['manualWorkloadTotal'];
				for ($i = 0; $i < count($services['ID']); $i++) {
					execSQL("INSERT INTO entretiens_details (USR_MAJ, TYPE, MAINTENANCE_ID, SERVICE, DURATION, AMOUNT) VALUES (?, 'service', ?, ?, ?, ?)", array('siiid', $token, $id, $services['ID'][$i], $services['length'][$i], $services['amount'][$i]), true);
				}
			}
			if(isset($_POST['linkIDStock'])){
				$accessory['linkIDStock']=$_POST['linkIDStock'];
				$accessory['amount']=$_POST['accessoryAmount'];
				for ($i = 0; $i < count($accessory['linkIDStock']); $i++) {
					execSQL("INSERT INTO entretiens_details (USR_MAJ, TYPE, MAINTENANCE_ID, SERVICE, AMOUNT) VALUES (?, 'accessory', ?, ?, ?)", array('siid', $token, $id, $accessory['linkIDStock'][$i], $accessory['amount'][$i]), true);
				}
			}
			if(isset($_POST['otherAccessoryDescription'])){
				$accessory['description']=$_POST['otherAccessoryDescription'];
				$accessory['amount']=$_POST['otherAccessoryAmount'];
				for ($i = 0; $i < count($accessory['description']); $i++) {
					execSQL("INSERT INTO entretiens_details (USR_MAJ, TYPE, MAINTENANCE_ID, DESCRIPTION, AMOUNT) VALUES (?, 'otherAccessory', ?, ?, ?)", array('sisd', $token, $id, $accessory['description'][$i], $accessory['amount'][$i]), true);
				}
			}
			$response = array ('response'=>'success', 'message' => 'la modification a bien été effectuée');
			echo json_encode($response);
			die;
		}else
		error_message('403');
	}else if($action === 'addImage'){
		if(get_user_permissions("admin", $token)){
			$id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
			$name = isset($_POST["name"]) ? $_POST["name"] : NULL;
			$dossier =  $_SERVER['DOCUMENT_ROOT'].'/images_entretiens/'.$id.'/'.$name;
			if(!file_exists($dossier)){
				mkdir($dossier, 0777, true);
			}
			$dossier =  $_SERVER['DOCUMENT_ROOT'].'/images_entretiens/'.$id.'/'.$name;
			if(!file_exists($dossier)){
				mkdir($dossier, 0777, true);
			}
			$fichier = basename( $_FILES['media']['name']);
				if(move_uploaded_file($_FILES['media']['tmp_name'], $dossier . '/' . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
				{
					$upload=true;
					$path= $dossier . '/' . $fichier;
				}else{
					errorMessage('ES0012');
				}
				$response['response']='success';
				$response['message']='Fichier ajouté';
				$response['ID']=$id;
				$response['folderName']=$name;
				$response['fileName']= $fichier;
				echo json_encode($response);
				die;
			}else
			error_message('403');
		}else if($action == "deleteImage"){
			$url=$_POST['url'];
			$path = explode("/", $url);
			$id = explode("_", $path[1]);
			if(file_exists( $_SERVER['DOCUMENT_ROOT']."/".$url )){
				unlink($_SERVER['DOCUMENT_ROOT']."/".$url);
				$response = array('response' => "success", 'id' => $id[0], "message" => "Image supprimée");
				echo json_encode($response);
				die;
			}else{
				$response = array('response' => "error", "message" => "Fichier non trouvé");
				echo json_encode($response);
				die;
			}
		}else if($action == "deleteEntretien"){
			$ID=isset($_POST['id']) ? $_POST['id'] : NULL;
			execSQL("DELETE FROM entretiens WHERE ID = ?", array('i', $ID), true);
			successMessage("SM0031");
			die;
		}else if($action==="sendCommunication"){
			$sendMail=(isset($_POST['warnByEmail']) ? true : false);
			$sendSMS=(isset($_POST['warnByPhone']) ? true : false);
			$message=nl2br($_POST['message']);
			if($sendMail){
				require_once($_SERVER['DOCUMENT_ROOT'].'/include/php-mailer/PHPMailerAutoload.php');
				$mail = new PHPMailer();
				if(constant('ENVIRONMENT')=="production"){
					$mail->AddAddress($_POST['emailAddress']);
				}else{
					$mail->AddAddress('antoine@kameobikes.com', 'Antoine Lust');
				}
				$mail->IsHTML(true);
				$mail->CharSet = 'UTF-8';
				$mail->From = "info@kameobikes.com";
				$mail->FromName = 'Info KAMEO Bikes';
				$mail->AddReplyTo("info@kameobikes.com", "Info KAMEO Bikes");
				$subject="Votre vélo est prêt !";
				$mail->Subject = $subject;
				include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_header.php';

        $body = $body."
            <body>
                <!--[if !gte mso 9]><!----><span class=\"mcnPreviewText\" style=\"display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;\">Mail du service entretien</span><!--<![endif]-->
                <!--*|END:IF|*-->
                <center>
                    <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" width=\"100%\" id=\"bodyTable\">
                        <tr>
                            <td align=\"center\" valign=\"top\" id=\"bodyCell\">
                                <!-- BEGIN TEMPLATE // -->
                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                                    <tr>
                                        <td align=\"center\" valign=\"top\" id=\"templateHeader\" data-template-container>
                                            <!--[if (gte mso 9)|(IE)]>
                                            <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"600\" style=\"width:600px;\">
                                            <tr>
                                            <td align=\"center\" valign=\"top\" width=\"600\" style=\"width:600px;\">
                                            <![endif]-->
                                            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"templateContainer\">
                                                <tr>
                                                    <td valign=\"top\" class=\"headerContainer\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"mcnImageBlock\" style=\"min-width:100%;\">
            <tbody class=\"mcnImageBlockOuter\">
                    <tr>
                        <td valign=\"top\" style=\"padding:9px\" class=\"mcnImageBlockInner\">
                            <table align=\"left\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"mcnImageContentContainer\" style=\"min-width:100%;\">
                                <tbody><tr>
                                    <td class=\"mcnImageContent\" valign=\"top\" style=\"padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;\">


                                                <img align=\"center\" alt=\"\" src=\"https://gallery.mailchimp.com/c4664c7c8ed5e2d53dc63720c/images/8b95e5d1-2ce7-4244-a9b0-c5c046bf7e66.png\" width=\"300\" style=\"max-width:300px; padding-bottom: 0; display: inline !important; vertical-align: bottom;\" class=\"mcnImage\">


                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
            </tbody>
        </table></td>
                                                </tr>
                                            </table>
                                            <!--[if (gte mso 9)|(IE)]>
                                            </td>
                                            </tr>
                                            </table>
                                            <![endif]-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" valign=\"top\" id=\"templateBody\" data-template-container>
                                            <!--[if (gte mso 9)|(IE)]>
                                            <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"600\" style=\"width:600px;\">
                                            <tr>
                                            <td align=\"center\" valign=\"top\" width=\"600\" style=\"width:600px;\">
                                            <![endif]-->
                                            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"templateContainer\">
                                                <tr>
                                                    <td valign=\"top\" class=\"bodyContainer\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"mcnTextBlock\" style=\"min-width:100%;\">
            <tbody class=\"mcnTextBlockOuter\">
                <tr>
                    <td valign=\"top\" class=\"mcnTextBlockInner\" style=\"padding-top:9px;\">
                        <!--[if mso]>
                        <table align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;\">
                        <tr>
                        <![endif]-->

                        <!--[if mso]>
                        <td valign=\"top\" width=\"600\" style=\"width:600px;\">
                        <![endif]-->
                        <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:100%; min-width:100%;\" width=\"100%\" class=\"mcnTextContentContainer\">
                            <tbody><tr>

                                <td valign=\"top\" class=\"mcnTextContent\" style=\"padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;\">
																$message
                                </td>
                            </tr>
                        </tbody></table>
                        <!--[if mso]>
                        </td>
                        <![endif]-->

                        <!--[if mso]>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
            </tbody>
        </table>";



        include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/mails/mail_footer.php';

        $mail->Body = $body;

        if(constant('ENVIRONMENT')=="test" || constant('ENVIRONMENT')=='production'){
            if(!$mail->Send()) {
               $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);
                echo json_encode($response);
                die;
            }
        }
				execSQL("UPDATE entretiens SET USR_MAJ=?, HEU_MAJ=CURRENT_TIMESTAMP, CLIENT_WARNED=1 WHERE ID=?", array('si', $token, $_POST['maintenanceID']), true);
        successMessage("SM0026");
				die;
			}
		}else{
			error_message('405');
		}
		break;
		default:
		error_message('405');
		break;
	}
	?>
