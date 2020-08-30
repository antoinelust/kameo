<?php if(!isset($_SESSION))
    session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/include/lang_management.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/include/cookie_consent.php';
//require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/activitylog.php';

?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="description" content="KAMEO Bikes, Mobilité urbaine pour entreprises. Vente, Leasing et Location de vélos pour entreprises. Entretien sur votre lieu de travail ou à domicile.">
 	<meta name="keywords" content="kameo, kameo bikes, vélo électrique, vélo de société belgique, vélo électrique liège, kameos, mobilité, vélos, vélos électriques, VAE, entretiens à domicile, entretiens sur le lieu de travail, Orbea, Ahooga, Conway, Victoria, Tern, i:SY, vélo urbain, vélo cargo, accessoires vélo, casques vélo, cadenas vélo">
 	<meta name="author" content="Thibaut Mativa">
 	<meta property="og:image" content="/images/vignette.jpg" />

	<link rel="shortcut icon" href="/images/favicon.png">
	<title class="fr">KAMEO Bikes | La solution complète pour vos vélos de société</title>
	<title class="en">KAMEO Bikes | Bike solutions for businesses</title>
	<title class="nl">KAMEO Bikes | Fiets oplossingen voor bedrijven</title>
	
	<!-- Bootstrap Core CSS -->
	<link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="/vendor/fontawesome/css/font-awesome.min.css" type="text/css" rel="stylesheet">
	<link href="/vendor/animateit/animate.min.css" rel="stylesheet">
	<!-- vendor css -->
	<link href="/vendor/owlcarousel/owl.carousel.css" rel="stylesheet">
	<link href="/vendor/magnific-popup/magnific-popup.css" rel="stylesheet">
	<!-- Template base -->
	<link href="/css/theme-base.css" rel="stylesheet">
	<!-- Template elements -->
	<link href="/css/theme-elements.css" rel="stylesheet">
    <!-- Template notifications -->
	<link href="/css/notifications.css" rel="stylesheet">
    <!-- DateTimePicker css -->
    <link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<!-- Responsive classes -->
	<link href="/css/responsive.css" rel="stylesheet">
	<!-- Template color -->
	<link href="/css/color-variations/blue.css" rel="stylesheet" type="text/css" media="screen" title="blue">
	<!-- LOAD GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,800,700,600%7CRaleway:100,300,600,700,800" rel="stylesheet" type="text/css" />
	<!-- CSS CUSTOM STYLE -->
    <link rel="stylesheet" type="text/css" href="/css/custom.css" media="screen" />
	
    <!--VENDOR SCRIPT-->
    <script src="/vendor/jquery/jquery-1.11.2.min.js"></script>
    <script src="/vendor/plugins-compressed.js"></script>
	<!-- I am not a robot script -->
	<script src='https://www.google.com/recaptcha/api.js'></script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<?php if(substr($_SERVER['REQUEST_URI'], 1, 4) != "test" && substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost")
			include __DIR__.'/googleAnalytics.php';?>

	<!--[if lt IE 9]>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->

    <!-- GDPR cookie consent bar -->
    <script src="/js/cookie_consent.js"></script>
</head>