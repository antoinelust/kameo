<?php
$response['response']="success";
$response['categories']=execSQL("SELECT * from accessories_categories ORDER BY CATEGORY", array(), false);
echo json_encode($response);
die;
?>
