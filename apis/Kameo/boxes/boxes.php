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

log_inputs($token);

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;
		if($action === 'listBoxes'){
			$company = isset($_GET["company"]) ? $_GET["company"] : NULL;
			if(get_user_permissions("fleetManager", $token)){
				$sql="SELECT COMPANY FROM customer_referential WHERE TOKEN='$token'";
				if ($conn->query($sql) === FALSE) {
					$response = array ('response'=>'error', 'message'=> $conn->error);
					echo json_encode($response);
					die;
				}
				$result = mysqli_query($conn, $sql);
				$resultat=mysqli_fetch_assoc($result);
				$company=$resultat['COMPANY'];
			}else{
				error_message('403');
			}
      $sql="SELECT * FROM boxes WHERE STAANN != 'D' AND COMPANY='$company'";
      if ($conn->query($sql) === FALSE) {
          $response = array ('response'=>'error', 'message'=> $conn->error);
          echo json_encode($response);
          die;
      }
      $result = mysqli_query($conn, $sql);
      $conn->close();
      $response['response']="success";
      $response['boxesNumber'] = $result->num_rows;
      $i=0;

      while($row = mysqli_fetch_array($result))
      {

          $response['box'][$i]['id']=$row['ID'];
          $response['box'][$i]['model']=$row['MODEL'];
          $response['box'][$i]['reference']=$row['REFERENCE'];
          $response['box'][$i]['company']=$row['COMPANY'];
          $response['box'][$i]['start']=$row['START'];
          $response['box'][$i]['end']=$row['END'];
          $response['box'][$i]['automatic_billing']=$row['AUTOMATIC_BILLING'];
          $response['box'][$i]['amount']=$row['AMOUNT'];
          $response['box'][$i]['billing_group']=$row['BILLING_GROUP'];
          $i++;
      }
      echo json_encode($response);
      die;
		}else if($action === 'retrieveBox'){
			$id = isset($_GET["id"]) ? $_GET["id"] : NULL;
			if($id){
				if(get_user_permissions("admin", $token)){
					$id=$id;
				}else if(get_user_permissions("fleetManager", $token)){
					$sql="SELECT cr.COMPANY as COMPANY_C1, boxes.COMPANY as COMPANY_C2 FROM customer_referential cr, boxes WHERE TOKEN='$token' and boxes.ID='$id'";
					if ($conn->query($sql) === FALSE) {
							$response = array ('response'=>'error', 'message'=> $conn->error);
							echo json_encode($response);
							die;
					}
					$result = mysqli_query($conn, $sql);
					$resultat = mysqli_fetch_assoc($result);
					if($resultat['COMPANY_C1'] != $resultat['COMPANY_C2']){
						error_message('403');
					}
				}else{
					error_message('403');
				}

				$sql="SELECT * FROM boxes WHERE ID='$id'";
				if ($conn->query($sql) === FALSE) {
						$response = array ('response'=>'error', 'message'=> $conn->error);
						echo json_encode($response);
						die;
				}

				$result = mysqli_query($conn, $sql);
				$resultat = mysqli_fetch_assoc($result);
				$response['response']="success";
				$response['id']=$resultat['ID'];
				$response['model']=$resultat['MODEL'];
				$response['reference']=$resultat['REFERENCE'];
				$response['company']=$resultat['COMPANY'];
				$response['start']=$resultat['START'];
				$response['end']=$resultat['END'];
				$response['automatic_billing']=$resultat['AUTOMATIC_BILLING'];
				$response['amount']=$resultat['AMOUNT'];
				$response['billing_group']=$resultat['BILLING_GROUP'];

				$sql="SELECT bb.ID as id, bb.MODEL as model, cc.PLACE_IN_BUILDING  as place
				FROM boxes aa INNER JOIN customer_bikes bb ON aa.COMPANY=bb.COMPANY
				INNER JOIN locking_bikes cc ON bb.ID=cc.BIKE_ID where aa.ID='$id' and aa.BUILDING=cc.BUILDING and cc.PLACE_IN_BUILDING !='-1' ORDER BY cc.PLACE_IN_BUILDING";
				if ($conn->query($sql) === FALSE) {
						$response = array ('response'=>'error', 'message'=> $conn->error);
						echo json_encode($response);
						die;
				}
				$result = mysqli_query($conn, $sql);
				$response['keys_in'] = $result->fetch_all(MYSQLI_ASSOC);

				$sql="SELECT bb.ID as id, bb.TYPE as type, bb.MODEL as model, cc.PLACE_IN_BUILDING as place, ee.EMAIL, ee.DATE_START_2, ee.DATE_END_2
				FROM boxes aa
				INNER JOIN customer_bikes bb ON aa.COMPANY=bb.COMPANY
				INNER JOIN locking_bikes cc ON bb.ID=cc.BIKE_ID
				INNER JOIN bike_catalog dd ON dd.ID=bb.TYPE
				INNER JOIN reservations ee
				WHERE aa.ID='$id' and aa.BUILDING=cc.BUILDING and cc.PLACE_IN_BUILDING ='-1' and cc.RESERVATION_ID=ee.ID and NOT EXISTS (SELECT 1 FROM locking_bikes ff WHERE cc.BIKE_ID=ff.BIKE_ID and ff.PLACE_IN_BUILDING != '-1') ORDER BY bb.FRAME_NUMBER";

				if ($conn->query($sql) === FALSE) {
						$response = array ('response'=>'error', 'message'=> $conn->error);
						echo json_encode($response);
						die;
				}
				$result = mysqli_query($conn, $sql);
				$i = 0;
				while($row = mysqli_fetch_array($result))
				{
						$response['keys_out'][$i]['id']=$row['id'];
						$response['keys_out'][$i]['model']=$row['model'];
						$response['keys_out'][$i]['place']=$row['place'];
						$response['keys_out'][$i]['img'] = $row['type'];
						$response['keys_out'][$i]['email'] = $row['EMAIL'];
						$response['keys_out'][$i]['dateStart'] = $row['DATE_START_2'];
						$response['keys_out'][$i]['dateEnd'] = $row['DATE_END_2'];
						$i++;
				}

				$sql="SELECT bb.ID as id, bb.TYPE as type, bb.MODEL as model, ff.BUILDING_FR
				FROM boxes aa
				INNER JOIN customer_bikes bb ON aa.COMPANY=bb.COMPANY
				INNER JOIN locking_bikes cc ON bb.ID=cc.BIKE_ID
				INNER JOIN bike_catalog dd ON dd.ID=bb.TYPE
				INNER JOIN locking_bikes ee ON cc.BIKE_ID=ee.BIKE_ID AND ee.PLACE_IN_BUILDING != '-1'
        INNER JOIN building_access ff ON ff.BUILDING_CODE=ee.BUILDING
				WHERE aa.ID='$id' and aa.BUILDING=cc.BUILDING and cc.PLACE_IN_BUILDING ='-1' ORDER BY bb.FRAME_NUMBER";

				if ($conn->query($sql) === FALSE) {
						$response = array ('response'=>'error', 'message'=> $conn->error);
						echo json_encode($response);
						die;
				}
				$result = mysqli_query($conn, $sql);
				$i = 0;
				while($row = mysqli_fetch_array($result))
				{
						$response['keys_other_box'][$i]['id']=$row['id'];
						$response['keys_other_box'][$i]['model']=$row['model'];
						$response['keys_other_box'][$i]['img'] = $row['type'];
						$response['keys_other_box'][$i]['building'] = $row['BUILDING_FR'];
						$i++;
				}
				$conn->close();
				echo json_encode($response);
				die;
			}else{
					errorMessage("ES0012");
			}
		}
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
		if ($action === 'sendMessage')
		{
			if(get_user_permissions(["order", "admin"], $token)){
				require_once 'sendMessage.php';
			}else
				error_message('403');
		}else
			error_message('405');
	break;
	default:
			error_message('405');
		break;
}

$conn->close();
?>
