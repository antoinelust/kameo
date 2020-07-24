<?php
	header("Content-Type: text/html");
	include 'include/alto-router/alto-router.php';
	$router = new AltoRouter();
	
	/** MATCH ANY EXTENSION ADDED TO ROUTE USING [ext] **/
	$router->addMatchTypes(array('ext' => '((\.).+)?$'));
	
	include 'include/pages-routes.php';
	
	$router->map('GET','/api/v1/bills', 'apis/Kameo/add_bill.php');
	
	$match = $router->match();
	if($match&&!is_a($match['target'], "Closure"))
	  require $match['target'];
	else if (is_a($match['target'], "Closure"))
		$match['target']();
	else
	  require 'pages/404.php';
?>