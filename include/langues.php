<?php

if(!isset($_SESSION)) 
	session_start();  

function setLangue($data){
	$_SESSION['langue'] = $data;
	setcookie("langue", $data, time() + (86400 * 30), "/");
	echo $_SESSION['langue'];
}

function getLangue($data) {
    $langue=isset($_SESSION['langue']) ? $_SESSION['langue'] : NULL;
    $langueCookie=isset($_COOKIE['langue']) ? $_COOKIE['langue'] : NULL;
	if($langue=='fr' || $langue=='nl' || $langue=='en')
        $_SESSION['langue']=$langue;
    else if ($langueCookie=='fr' || $langueCookie=='en' || $langueCookie=='nl')
		$_SESSION['langue']=$langueCookie;
	else
        $_SESSION['langue']="fr";
    echo $_SESSION['langue'];
}

if (isset($_POST['setLangue'])) {
	setLangue($_POST['setLangue']);
    return true;
}

if (isset($_POST['getLangue'])) {
	getLangue($_POST['getLangue']);
}
?>