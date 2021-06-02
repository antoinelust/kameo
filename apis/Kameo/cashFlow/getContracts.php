<?php

$response['contracts']['leasingBikes']=execSQL("SELECT aa.COMPANY as company, (SELECT bb.ID FROM companies bb WHERE bb.INTERNAL_REFERENCE=aa.COMPANY LIMIT 1)  as companyID, ROUND(SUM(CASE WHEN BILLING_TYPE = 'annual' THEN LEASING_PRICE/12 ELSE LEASING_PRICE END)) as leasingAmount, COUNT(1) AS 'bikeNumber' FROM customer_bikes aa WHERE  aa.STAANN != 'D' and aa.COMPANY != 'KAMEO' AND aa.CONTRACT_TYPE IN ('leasing', 'location') GROUP BY aa.COMPANY ORDER BY aa.COMPANY", array(), false);
$response['contracts']['sellingBikes']=execSQL("SELECT factures_details.ITEM_ID as bikeID, factures_details.AMOUNT_HTVA as sellingPrice, companies.COMPANY_NAME as companyName, companies.ID as companyID FROM factures_details, customer_bikes, companies WHERE factures_details.ITEM_TYPE='bike' AND factures_details.DATE_START=factures_details.DATE_END AND factures_details.DATE_START>='2021-01-01' AND ITEM_ID=customer_bikes.ID AND customer_bikes.COMPANY=companies.INTERNAL_REFERENCE", array(), false);
$response['contracts']['bikes']=execSQL("SELECT aa.COMPANY as company, (SELECT bb.ID FROM companies bb WHERE bb.INTERNAL_REFERENCE=aa.COMPANY LIMIT 1)  as companyID, ROUND(SUM(CASE WHEN BILLING_TYPE = 'annual' THEN LEASING_PRICE/12 ELSE LEASING_PRICE END)) as leasingAmount, COUNT(1) AS 'bikeNumber' FROM customer_bikes aa WHERE  aa.STAANN != 'D' and aa.COMPANY != 'KAMEO' AND aa.CONTRACT_TYPE IN ('leasing', 'location') GROUP BY aa.COMPANY ORDER BY aa.COMPANY", array(), false);
$response['contracts']['boxes']=execSQL("SELECT COMPANY as company, (SELECT bb.ID FROM companies bb WHERE bb.INTERNAL_REFERENCE=boxes.COMPANY LIMIT 1)  as companyID, SUM(AMOUNT) as 'amount', COUNT(1) AS 'boxesNumber' FROM boxes WHERE STAANN != 'D' AND COMPANY != 'KAMEO' GROUP BY COMPANY", array(), false);
$response['offers']=execSQL("SELECT * FROM offers WHERE STATUS='ongoing' AND STAANN != 'D'", array(), false);
echo json_encode($response);
die;
/*
$response['response']="success";
$response['offer'][$i]['id']=$row['ID'];
$response['offer'][$i]['company']=$row['COMPANY'];
$response['offer'][$i]['type']=$row['TYPE'];
$response['offer'][$i]['title']=$row['TITRE'];
$response['offer'][$i]['amount']=$row['AMOUNT'];
$response['offer'][$i]['probability']=$row['PROBABILITY'];
$response['offer'][$i]['start']=$row['START'];
$response['offer'][$i]['end']=$row['END'];
$response['offer'][$i]['margin']=$row['MARGIN'];
$response['offer'][$i]['status']=$row['STATUS'];
$response['offer'][$i]['file']=$row['FILE_NAME'];
*/


?>
