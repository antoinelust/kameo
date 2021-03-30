<?php

include '../connexion.php';
$response=execSQL("SELECT aa.ID, aa.COMPANY_NAME from companies aa, customer_bikes bb WHERE aa.INTERNAL_REFERENCE=bb.COMPANY group by aa.INTERNAL_REFERENCE ORDER BY aa.ID", array(), false);
echo json_encode($response);
die;
?>
