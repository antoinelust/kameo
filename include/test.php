<?php
include 'globalfunctions.php';

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


//corresponds to the request for the mail, to have the link for reseting the mail
if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    successMessage(SM0005);
}
?>