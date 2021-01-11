<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

require_once '../globalfunctions.php';
require_once '../authentication.php';
require_once '../connexion.php';

$token = getBearerToken();
switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;


		if($action === 'retrieveNotifications'){
			if(get_user_permissions(["search", "order","chatsManager","admin", 'fleetManager', 'personnalBike'], $token)){
				$stmt = $conn->prepare("SELECT ID FROM customer_referential WHERE TOKEN = ?");
				$stmt->bind_param("s", $token);
				if ($stmt->execute())
				{
					$id = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['ID'];
					$stmt->close();
					if(get_user_permissions("chatsManager", $token))
						$result = $conn->query("SELECT * FROM notifications WHERE (USER_ID = '".$id."' OR USER_ID = 0) AND `READ` = 'N' AND (STAAN <> 'D' OR STAAN IS NULL) ORDER BY `notifications`.`READ` ASC, `notifications`.`READ` DESC");
					else
						$result = $conn->query("SELECT * FROM notifications WHERE USER_ID = '".$id."' AND (STAAN <> 'D' OR STAAN IS NULL) ORDER BY `notifications`.`READ` ASC, `notifications`.`READ` DESC");
					$response=array();
					if ($result && $result->num_rows>0) {
						$notifications = $result->fetch_all(MYSQLI_ASSOC);
						$response['response']="success";
						$response['notifications']=array();
						foreach($notifications as $index=>$notification){
							$notificationTemp=array();
							$notificationTemp['TYPE']=$notification['TYPE'];
							$notificationTemp['TYPE_ITEM']=$notification['TYPE_ITEM'];
							$notificationTemp['READ']=$notification['READ'];
							$notificationTemp['notificationID']=$notification['ID'];

							if($notification['TYPE']=='lateBooking'){
								$reservationID=$notification['TYPE_ITEM'];
								$informations = execSQL("SELECT aa.DATE_START_2, aa.BIKE_ID, aa.DATE_END_2, bb.MODEL from reservations aa, customer_bikes bb WHERE aa.ID='$reservationID' AND  aa.BIKE_ID=bb.ID", array(), false);
								$notificationTemp['start']=$informations[0]['DATE_START_2'];
								$notificationTemp['end']=$informations[0]['DATE_END_2'];
								$notificationTemp['model']=$informations[0]['MODEL'];
								$notificationTemp['bikeID']=$informations[0]['BIKE_ID'];
								$bikeID=$informations[0]['BIKE_ID'];
								$dateEnd=$informations[0]['DATE_END_2'];

								$nextBooking = execSQL("SELECT aa.DATE_START_2 from reservations aa WHERE aa.BIKE_ID='$bikeID' AND  aa.DATE_START_2>'$dateEnd'", array(), false);
								if($nextBooking != NULL){
									$notificationTemp['nextBookingStart']=$nextBooking[0]['DATE_START_2'];
								}
								array_push($response['notifications'], $notificationTemp);
							}else if($notification['TYPE']=='lateBookingNextUser'){
								$reservationID=$notification['TYPE_ITEM'];
								$informations = execSQL("SELECT aa.DATE_START_2, aa.BIKE_ID, aa.DATE_END_2, bb.MODEL from reservations aa, customer_bikes bb WHERE aa.ID='$reservationID' AND  aa.BIKE_ID=bb.ID", array(), false);
								$notificationTemp['start']=$informations[0]['DATE_START_2'];
								$notificationTemp['end']=$informations[0]['DATE_END_2'];
								$notificationTemp['model']=$informations[0]['MODEL'];
								$notificationTemp['bikeID']=$informations[0]['BIKE_ID'];
								array_push($response['notifications'], $notificationTemp);
							}else if($notification['TYPE']=='lateBookingNextUserNewHour'){
								$reservationID=$notification['TYPE_ITEM'];
								$informations = execSQL("SELECT aa.DATE_START_2, aa.BIKE_ID, aa.DATE_END_2, bb.MODEL from reservations aa, customer_bikes bb WHERE aa.ID='$reservationID' AND  aa.BIKE_ID=bb.ID", array(), false);
								$notificationTemp['start']=$informations[0]['DATE_START_2'];
								$notificationTemp['end']=$informations[0]['DATE_END_2'];
								$notificationTemp['model']=$informations[0]['MODEL'];
								$notificationTemp['bikeID']=$informations[0]['BIKE_ID'];
								$dateStartBooking = $informations[0]['DATE_START_2'];
								$informationsPreviousBooking = execSQL("SELECT aa.DATE_END_2 from reservations aa, reservations bb WHERE aa.BIKE_ID = bb.BIKE_ID AND bb.ID='$reservationID' AND  aa.DATE_START_2 < bb.DATE_START_2 AND aa.STAANN != 'D' ORDER BY aa.DATE_START_2 DESC LIMIT 1", array(), false);
								$notificationTemp['endPreviousBooking']=$informationsPreviousBooking[0]['DATE_END_2'];
								array_push($response['notifications'], $notificationTemp);
							}else if($notification['TYPE']=='feedback'){
								$reservationID=$notification['TYPE_ITEM'];
								$informations = execSQL("SELECT aa.DATE_START_2, aa.BIKE_ID, aa.DATE_END_2, bb.MODEL, aa.STATUS from reservations aa, customer_bikes bb WHERE aa.ID='$reservationID' AND  aa.BIKE_ID=bb.ID", array(), false);
								if($informations[0]['STATUS'] == "Closed"){
									array_push($response['notifications'], $notificationTemp);
								}else if($informations[0]['STATUS'] == "No Box"){
									$now=new DateTime("now", new DateTimeZone('Europe/Brussels'));
									$notificationDate = new DateTime($notification['DATE'], new DateTimeZone('Europe/Brussels'));
									if($notificationDate < $now){
										array_push($response['notifications'], $notificationTemp);
									}
								}else{

								}
							}
						}
					}else {
						$response['response']="success";
						$response['notificationsNumber']=0;
					}
					$response['notificationsNumber']=count($response['notifications']);
					echo json_encode($response);
					die;
				}else
					echo json_encode(array ('response'=>'error'));
			}else
				error_message('403');
		}else
				error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
			error_message('405');
	break;
	default:
			error_message('405');
		break;
}

$conn->close();
?>
