<?php
	$router = new AltoRouter();

	header('Content-type: application/json');
	header('WWW-Authenticate: Bearer');

	$router->addMatchTypes(array('ext' => '((\.).+)?$'));

	$token = getBearerToken(); //Defined in authentication.php
	if (authenticate($token))	//If token exist in databases
	{
		$router->map('GET','/api/companies', 'apis/Kameo/companies/companies.php');
		$router->map('GET','/api/csvOrder', 'apis/Kameo/csvOrder.php');
		$router->map('GET|POST','/api/chats[ext]', 'apis/Kameo/chats/chats.php');
		$router->map('GET|POST','/api/bikes[ext]', 'apis/Kameo/bikes/bikes.php');
		$router->map('GET|POST','/api/maintenances', 'apis/Kameo/maintenances/maintenances.php');
		$router->map('GET|POST','/api/accessories', 'apis/Kameo/accessories/accessories.php');
		$router->map('GET|POST','/api/bills[ext]', 'apis/Kameo/bills/bills.php');
		$router->map('GET|POST','/api/portfolioBikes[ext]', 'apis/Kameo/portfolioBikes/portfolioBikes.php');
	}
	else
		error_message('401');

$match = $router->match();
if($match&&!is_a($match['target'], "Closure"))
  require $match['target'];
else if (is_a($match['target'], "Closure"))
	$match['target']();
else
	error_message('404');
?>
