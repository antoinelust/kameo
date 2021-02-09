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

log_inputs($token);

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;

		if($action === 'listOrderable'){

			if(get_user_permissions("admin", $token) && isset($_GET['company'])){
				$stmt = $conn->prepare("SELECT co.BIKE_ID FROM bike_catalog bc, companies_orderable co, companies c WHERE co.INTERNAL_REFERENCE = c.INTERNAL_REFERENCE AND co.BIKE_ID = bc.ID AND c.COMPANY_NAME = ? ");
				$company = urldecode($_GET['company']);
				$stmt->bind_param("s", $company);
				$stmt->execute();
				$orderable = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
				$stmt->close();
				if ($portfolio = $conn->query("SELECT ID, BRAND, MODEL FROM bike_catalog WHERE DISPLAY='Y' AND STAANN!='D'")) {
					$result = array();
					while ($bike = mysqli_fetch_object($portfolio))
					{
						if (array_search($bike->ID, array_column($orderable, 'BIKE_ID')) !== false)
							$bike->ORDERABLE = 'true';
						else
							$bike->ORDERABLE = 'false';
						$result[] = $bike;
					}
					echo json_encode($result);
					log_output($result);
				}else
					errorMessage("ES0012");
			}else if(get_user_permissions(["order", "admin"], $token)){

        $marginBike=0.7;
        $marginOther=0.3;
        $leasingDuration=36;
				$stmt = $conn->prepare("SELECT COMPANY from customer_referential WHERE TOKEN=?");
				$stmt->bind_param("s", $token);
				$stmt->execute();
				$company_reference = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['COMPANY'];
				$stmt->close();
				$stmt = $conn->prepare("SELECT co.INTERNAL_REFERENCE as company, bc.ID, bc.BRAND as brand, bc.MODEL as model, bc.FRAME_TYPE as frameType, bc.UTILISATION as utilisation, bc.ELECTRIC as electric,bc.PRICE_HTVA as price, bc.LINK as url, STOCK as stock FROM bike_catalog bc, companies_orderable co WHERE STAANN != 'D' AND bc.DISPLAY='Y' AND bc.ID = co.BIKE_ID AND co.INTERNAL_REFERENCE = ? ORDER BY STOCK DESC, BRAND, MODEL");
				$stmt->bind_param("s", $company_reference);
				$stmt->execute();
				$orderable = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
				$response = array();
				$response['response'] = "success";

				$response['bike'] = $orderable;

				$i=0;
				require_once $_SERVER['DOCUMENT_ROOT']."/apis/Kameo/get_prices.php";
				foreach ($orderable as $bike){
					$getPrice=get_prices($bike['price'], $company_reference);
					$response['bike'][$i]['leasingPrice']=$getPrice['leasingPrice'];
					$i++;
				}



				$stmt->close();
				$response['bikeNumber'] = count($orderable);

				$stmt = $conn->prepare("SELECT DISCOUNT, REMAINING_PRICE_INCLUDED_IN_LEASING, CAFETERIA_TYPES, TVA_INCLUDED from conditions WHERE COMPANY=? AND NAME='generic'");
				$stmt->bind_param("s", $company_reference);
				$stmt->execute();
				$reponse=$stmt->get_result()->fetch_array(MYSQLI_ASSOC);
				$response['discount']=$reponse['DISCOUNT'];
				$response['cafeteriaTypes']=explode(',', $reponse['CAFETERIA_TYPES']);
				$response['tvaIncluded']=$reponse['TVA_INCLUDED'];
				$response['remainingPriceIncludedInLeasing']=$reponse['REMAINING_PRICE_INCLUDED_IN_LEASING'];
				$stmt->close();
				echo json_encode($response);
				log_output($response);
			}else
				error_message('403');
		}else
			error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;

		if($action === 'updateOrderable')
		{
			if(get_user_permissions("admin", $token) && isset($_POST['company']) && isset($_POST['cafeteria'])){
				$stmt = $conn->prepare("SELECT INTERNAL_REFERENCE FROM companies WHERE COMPANY_NAME=?");
				$stmt->bind_param("s", $_POST['company']);
				$stmt->execute();
				$company_reference = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['INTERNAL_REFERENCE'];
				$conditionID = isset($_POST['conditionID']) ? $_POST['conditionID'] : NULL;
				$cafeteria = ($_POST['cafeteria'] === "true") ? "Y" : "N";
				$remainingPriceIncluded = ($_POST['includedLeasingPrice'] === "true") ? "Y" : "N";
				$tva = ($_POST['tva'] === "true") ? "Y" : "N";
				$types = isset($_POST['types']) ? $_POST['types'] : NULL;
				$discount=isset($_POST['discount']) ? $_POST['discount'] : NULL;
				$sellingPorcentage=isset($_POST['sellingPorcentage']) ? $_POST['sellingPorcentage'] : NULL;
				$stmt->close();
				$stmt = $conn->prepare("UPDATE conditions SET HEU_MAJ=CURRENT_TIMESTAMP, CAFETARIA=?, DISCOUNT=?, CAFETERIA_TYPES=?, TVA_INCLUDED=?, REMAINING_PRICE_INCLUDED_IN_LEASING	=?, SELLING_PRICE=? WHERE ID = ?");
				$stmt->bind_param("sdssddi", $cafeteria, $discount, $types, $tva, $remainingPriceIncluded, $sellingPorcentage, $conditionID);
				$stmt->execute();
				$stmt->close();
				if ($_POST['cafeteria'] === "true")
				{
					$stmt = $conn->prepare("SELECT co.BIKE_ID FROM bike_catalog bc, companies_orderable co WHERE co.INTERNAL_REFERENCE = ? AND co.BIKE_ID = bc.ID");
					$stmt->bind_param("s", $company_reference);
					$stmt->execute();
					$orderable = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
					$stmt->close();
					$orderable = array_column($orderable, 'BIKE_ID');
					$checked = isset($_POST['bikesOrderable']) ? $_POST['bikesOrderable'] : Array();
					$to_insert = array_diff($checked, $orderable);
					$to_delete = array_diff($orderable, $checked);
					$stmt_insert = $conn->prepare("INSERT INTO companies_orderable (ID, INTERNAL_REFERENCE, BIKE_ID) VALUES (null, ?, ?)");
					$stmt_insert->bind_param("ss", $company_reference, $insert_item);
					$stmt_delete = $conn->prepare("DELETE FROM companies_orderable WHERE INTERNAL_REFERENCE = ? AND BIKE_ID = ?");
					$stmt_delete->bind_param("ss", $company_reference, $delete_item);
					$conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
					foreach ($to_insert as $insert_item)
						if (!$stmt_insert->execute())
							errorMessage("ES0012");
					foreach ($to_delete as $delete_item)
						if (!$stmt_delete->execute())
							errorMessage("ES0012");
					$stmt_insert->close();
					$stmt_delete->close();
					if ($conn->commit())
						successMessage("SM0003");
				}
				else
				{
					successMessage("SM0003");
				}
			}else
				error_message('403');
		}
	break;
	default:
			error_message('405');
		break;
}
$conn->close();
?>
