<?php
	header("Content-Type: text/html");
	include 'include/alto-router/alto-router.php';
	$router = new AltoRouter();
	
	/** MATCH ANY EXTENSION ADDED ROUTE USING [ext] **/
	$router->addMatchTypes(array('ext' => '((\.).+)?$'));
	
	/** INDEX **/
	$router->map('GET','@(/|index((\.).+)?)$', 'pages/index.php', 'index');
	
	/** NOS SOLUTIONS **/
	$router->map('GET','/velo-partage[ext]', 'pages/nos-solutions/velo-partage.php');
	$router->map('GET','/velo-personnel[ext]', 'pages/nos-solutions/velo-personnel.php');
	$router->map('GET','/gestion-flotte[ext]', 'pages/nos-solutions/gestion-flotte.php');
	$router->map('GET','/location-tout-inclus[ext]', 'pages/nos-solutions/location-tout-inclus.php');
	
	/** CATALOGUE **/
	$router->map('GET','/achat[ext]', 'pages/catalogue/achat.php');
	$router->map('GET','/accessoires[ext]', 'pages/catalogue/accessoires.php');
	$router->map('GET','/bons-plans[ext]', 'pages/catalogue/bons-plans.php');
	
	/** COMMANDER **/
	$router->map('GET','/commander[ext]', 'pages/commander.php');
	
	/** FAQ **/
	$router->map('GET','/faq[ext]', 'pages/faq.php');
	
	/** MYKAMEO **/
	$router->map('GET','/mykameo[ext]', 'pages/mykameo.php');
	
	/** NEWSLETTER **/
	$router->map('GET','/newsletter[ext]', 'pages/newsletter.php');
	
	/** OFFRE **/
	$router->map('GET','/offre[ext]', 'pages/offre.php');
	
	/** BONS PLANS **/
	$router->map('GET','/bp_conway_ets370_f1[ext]', 'pages/catalogue/bons-plans/bp_conway_ets370_f1.php');
	$router->map('GET','/bp_conway_ets370_f2[ext]', 'pages/catalogue/bons-plans/bp_conway_ets370_f2.php');
	$router->map('GET','/bp_conway_ets370_f3[ext]', 'pages/catalogue/bons-plans/bp_conway_ets370_f3.php');
	$router->map('GET','/bp_conway_ets370_f4[ext]', 'pages/catalogue/bons-plans/bp_conway_ets370_f4.php');
	$router->map('GET','/bp_orbea_gain[ext]', 'pages/catalogue/bons-plans/bp_orbea_gain.php');
	$router->map('GET','/bp_hnf[ext]', 'pages/catalogue/bons-plans/bp_hnf.php');
	$router->map('GET','/bp_ahooga_folding[ext]', 'pages/catalogue/bons-plans/bp_ahooga_folding.php');
	$router->map('GET','/bp_douze_cargo[ext]', 'pages/catalogue/bons-plans/bp_douze_cargo.php');
	$router->map('GET','/bp_conway_wme929[ext]', 'pages/catalogue/bons-plans/bp_conway_wme929.php');
	
	/** AVANTAGES **/
	$router->map('GET','/avantages[ext]', 'pages/avantages/avantages.php');
	$router->map('GET','/cash4bike[ext]', 'pages/avantages/cash4bike.php');
	
	/** CONTACT **/
	$router->map('GET','/contact[ext]', 'pages/contact.php');
	
	/** BLOG **/
	$router->map('GET','/blog[ext]', 'pages/blog.php');
	$router->map('GET','/blog_Infrastructures-cyclables-a-Liege-et-a-Bruxelles-pendant-le-deconfinement-et-apres[ext]', 'pages/blog_Infrastructures-cyclables-a-Liege-et-a-Bruxelles-pendant-le-deconfinement-et-apres.php');
	
	/** 403 **/
	$router->map('GET','/403[ext]', 'pages/403.php');
	
	$match = $router->match();
	if($match) {
	  require $match['target'];
	}
	else {
	  require 'pages/404.php';
	}
?>