<?php

	session_cache_limiter('nocache');
	if(!isset($_SESSION))
		session_start();
	header("Content-Type: text/html");
	require_once 'include/alto-router/alto-router.php';
	require_once 'apis/Kameo/globalfunctions.php';
	require_once 'apis/Kameo/authentication.php';
	require_once 'apis/Kameo/connexion.php';
	$router = new AltoRouter();

	/** MATCH ANY EXTENSION ADDED TO ROUTE USING [ext] **/
	$router->addMatchTypes(array('ext' => '((\.).+)?$'));

	require_once 'include/pages-routes.php';


	$router->map('GET|POST','@/api/.*', function(){
		require_once 'include/apis-routes.php';
	});

	$match = $router->match();
	if($match&&!is_a($match['target'], "Closure"))
	  require $match['target'];
	else if (is_a($match['target'], "Closure"))
		$match['target']();
	else
	  require 'pages/404.php';
?>
