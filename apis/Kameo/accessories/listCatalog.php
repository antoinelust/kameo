<?php
global $conn;
$stmt = $conn->prepare("SELECT aa.ID, aa.BRAND, aa.MODEL, aa.BUYING_PRICE, aa.PRICE_HTVA, aa.STOCK, aa.DISPLAY, aa.DESCRIPTION, aa.PROVIDER, aa.REFERENCE, bb.CATEGORY FROM accessories_catalog aa, accessories_categories bb WHERE aa.ACCESSORIES_CATEGORIES=bb.ID");
if ($stmt)
{
	$stmt->execute();
    $response['response']="success";
    $response['accessories']=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $response['ID'] = count($response['accessories']);
    echo json_encode($response);
	$stmt->close();
}else
	error_message('500', 'Unable to retrieve catalog of accessories');
?>
