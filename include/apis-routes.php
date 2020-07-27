<?php
	$router = new AltoRouter();
	
	header('Content-type: application/json');
	header('WWW-Authenticate: Bearer');
	
	$token = getBearerToken(); //Defined in authentication.php
	if (authenticate($token))	//If token exist in databases
	{
		$router->map('GET','/api/companies', 'apis/Kameo/companies/companies.php');
	}
	else
		error_message('401');

$match = $router->match();
if($match&&!is_a($match['target'], "Closure"))
  require $match['target'];
else if (is_a($match['target'], "Closure"))
	$match['target']();
else
{
	header("Content-Type: text/html");
	require 'pages/404.php';
}
?>