<?php
	$router = new AltoRouter();
	
	header('Content-type: application/json');
	header('WWW-Authenticate: Bearer');
	
	$router->addMatchTypes(array('ext' => '((\.).+)?$'));
	
	$token = getBearerToken(); //Defined in authentication.php
	if (authenticate($token))	//If token exist in databases
	{ 
		$router->map('GET','/api/companies', 'apis/Kameo/companies/companies.php');
		$router->map('GET|POST','/api/chats[ext]', 'apis/Kameo/chats/chats.php');
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
	header("Content-Type: application/problem");
	header("HTTP/1.0 404 Not Found");
}
?>