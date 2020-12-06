<?php

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

	/** BILLS GENERATION **/
	$router->map('GET','/generate_invoices[ext]', 'generate_invoices.php');
	$router->map('GET','/initiate_location_bill[ext]', 'initiation_location_bill.php');

	/** Generation valeur du stock de vélo **/
	$router->map('GET','/automatic_mail_bikes[ext]', 'scripts/automatic_mail_bikes.php');

	/** NEWSLETTER **/
	$router->map('GET','/newsletter[ext]', 'pages/newsletter.php');

	/** POLITIQUE DE CONFIDENTIALITE **/
	$router->map('GET','/privacy-policy[ext]', 'pages/privacy-policy.php');

	/** CONDITIONS GENERALES D'UTILISATION **/
	$router->map('GET','/terms-and-conditions[ext]', 'pages/terms-and-conditions.php');

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

	/** PAGES DIVERSES **/
	$router->map('GET','/prime-velo-wallonie[ext]', 'pages/prime-velo-wallonie.php');
	$router->map('GET','/ebook_Leasing_Velo_Entreprise[ext]', 'pages/ebook_Leasing_Velo_Entreprise.php');

	/**MyAedes**/
	$router->map('GET','/myaedes[ext]', 'pages/myaedes.php');

	/** BORNE **/
	/*$router->map('GET','/include/lock/lock_verifier_code[ext]', 'apis/Kameo/lock/lock_verifier_code.php');
	$router->map('GET','/include/lock/lock_verifier_rfid_client[ext]', 'apis/Kameo/lock/lock_verifier_rfid_client.php');
	$router->map('GET','/include/lock/lock_verifier_rfid[ext]', 'apis/Kameo/lock/lock_verifier_rfid.php');
	$router->map('GET','/include/lock/lock_update_remise_cle[ext]', 'apis/Kameo/lock/lock_update_remise_cle.php');
	$router->map('GET','/include/lock/lock_update_prise_cle[ext]', 'apis/Kameo/lock/lock_update_prise_cle.php');
	$router->map('GET','/include/lock/lock_emplacement_libre_2[ext]', 'apis/Kameo/lock/lock_emplacement_libre_2.php');
	$router->map('GET','/include/lock/list_bikes_box_booking[ext]', 'apis/Kameo/lock/list_bikes_box_booking.php');
	$router->map('GET','/include/lock/confirm_bike_booking[ext]', 'apis/Kameo/lock/confirm_bike_booking.php');*/

	/** CONTACT **/
	$router->map('GET','/contact[ext]', 'pages/contact.php');

	/** BLOG **/
	$router->map('GET','/blog[ext]', 'pages/blog.php');
	$router->map('GET','/blog_Infrastructures-cyclables-a-Liege-et-a-Bruxelles-pendant-le-deconfinement-et-apres[ext]', 'pages/blog_Infrastructures-cyclables-a-Liege-et-a-Bruxelles-pendant-le-deconfinement-et-apres.php');
	$router->map('GET','/blog_Choisir-son-velo-electrique[ext]', 'pages/blog_Choisir-son-velo-electrique.php');

	/** 403 **/
	$router->map('GET','/403[ext]', 'pages/403.php');

	/** NAVIGATEUR **/
	$router->map('GET','/navigateur[ext]', 'pages/navigateur.php');
	
	/** SECUREX **/
	$router->map('GET','/login_securex[ext]', 'pages/login_securex.php');
	
?>
