<?php
global $conn;
$stmt = $conn->prepare("SELECT aa.ID, aa.NAME, aa.BUYING_PRICE, aa.PRICE_HTVA, aa.STOCK, aa.SHOW_ACCESSORIES, aa.DESCRIPTION, bb.CATEGORY FROM accessories_catalog aa, accessories_categories bb WHERE aa.ACCESSORIES_CATEGORIES=bb.ID");
if ($stmt)
{
	$stmt->execute();
    $response['response']="success";
    $response['accessories']=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($response);
	$stmt->close();
}else
	error_message('500', 'Unable to retrieve catalog of accessories');
?>