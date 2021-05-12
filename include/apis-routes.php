<?php
	$router = new AltoRouter();

	header('Content-type: application/json');
	header('WWW-Authenticate: Bearer');

	$router->addMatchTypes(array('ext' => '((\.).+)?$'));

	$token = getBearerToken(); //Defined in authentication.php
	if (authenticate($token))	//If token exist in databases
	{
		$router->map('GET|POST','/api/companies', 'apis/Kameo/companies/companies.php');
		$router->map('GET','/api/csvOrder', 'apis/Kameo/csvOrder.php');
		$router->map('GET|POST','/api/orders[ext]', 'apis/Kameo/orders/orders.php');
		$router->map('GET|POST','/api/chats[ext]', 'apis/Kameo/chats/chats.php');
		$router->map('GET|POST','/api/bikes[ext]', 'apis/Kameo/bikes/bikes.php');
		$router->map('GET|POST','/api/users[ext]', 'apis/Kameo/users/users.php');
		$router->map('GET|POST','/api/maintenances', 'apis/Kameo/maintenances/maintenances.php');
		$router->map('GET|POST','/api/cashFlow', 'apis/Kameo/cashFlow/cashFlow.php');
		$router->map('GET','/api/statistics', 'apis/Kameo/statistics/statistics.php');
		$router->map('GET|POST','/api/offers', 'apis/Kameo/offers/offers.php');
		$router->map('GET|POST','/api/accessories', 'apis/Kameo/accessories/accessories.php');
		$router->map('GET|POST','/api/bills[ext]', 'apis/Kameo/bills/bills.php');
		$router->map('GET|POST','/api/portfolioBikes[ext]', 'apis/Kameo/portfolioBikes/portfolioBikes.php');
		$router->map('GET|POST','/api/customerCollab[ext]', 'apis/Kameo/companies_collab/companies.php');
		$router->map('GET|POST','/api/bikesCollab[ext]', 'apis/Kameo/bikes_collab/bikes.php');
		$router->map('GET|POST','/api/tasks[ext]', 'apis/Kameo/tasks/tasks.php');
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
