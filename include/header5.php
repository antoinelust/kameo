<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 


?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-108429655-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-108429655-1');

</script>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="description" content="KAMEO Bikes, Mobilité urbaine pour entreprises. Vente, Leasing et Location de vélos pour entreprises. Entretien sur votre lieu de travail ou à domicile.">
 	<meta name="keywords" content="mobilité, vélos, vélos électriques, VAE, entretiens à domicile, entretiens sur le lieu de travail, Orbea, Ahooga, Conway, Victoria, Tern, i:SY, vélo urbain, vélo cargo, accessoires vélo, casques vélo, cadenas vélo">
 	<meta name="author" content="Thibaut Mativa">
 	<meta property="og:image" content="http://www.kameobikes/images/vignette.jpg" />

	
	
	<link rel="shortcut icon" href="images/favicon.png">
	<title>KAMEO Bikes | La solution complète pour vos vélos de société</title>

	<!-- Bootstrap Core CSS -->
	<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="vendor/fontawesome/css/font-awesome.min.css" type="text/css" rel="stylesheet">
	<link href="vendor/animateit/animate.min.css" rel="stylesheet">

	<!-- Vendor css -->
	<link href="vendor/owlcarousel/owl.carousel.css" rel="stylesheet">
	<link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

	<!-- Template base -->
	<link href="css/theme-base.css" rel="stylesheet">

	<!-- Template elements -->
	<link href="css/theme-elements.css" rel="stylesheet">	
	
    <!-- DateTimePicker css -->
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

    
<!-- Responsive classes -->
	<link href="css/responsive.css" rel="stylesheet">

<!--[if lt IE 9]>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->	


	<!-- Template color -->
	<link href="css/color-variations/blue.css" rel="stylesheet" type="text/css" media="screen" title="blue">

	<!-- LOAD GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,800,700,600%7CRaleway:100,300,600,700,800" rel="stylesheet" type="text/css" />
	

	<!-- CSS CUSTOM STYLE -->
    <link rel="stylesheet" type="text/css" href="css/custom.css" media="screen" />

    <!--VENDOR SCRIPT-->
    <script src="vendor/jquery/jquery-1.11.2.min.js"></script>
    <script src="vendor/plugins-compressed.js"></script>

	<!-- I am not a robot script -->
	<script src='https://www.google.com/recaptcha/api.js'></script>

</head>

<body class="wide">
	

	<!-- WRAPPER -->
	<div class="wrapper">
	
	<!-- TOPBAR -->
	<!--
		<div id="topbar" class="topbar-colored dark">
			<div class="container">
				<div class="row">
					<div class="">
						<ul class="top-menu right">
							<a class="button small red-dark button-3d full-rounded" href="#"><span>MyKAMEO</span></a>
							<li><a href="#" onClick="setFr()">Fr</a></li>
							<li><a href="#" onClick="setNl()">Nl</a></li>
							<li><a href="#" onClick="setEn()">En</a></li>
						</ul>
						<ul class="top-menu left">
							<a class="button small blue  full-rounded center" href="#"><span> <i class="fa fa-facebook"></i></span></a>
							<a class="button small pink  full-rounded center" href="#"><span> <i class="fa fa-instagram"></i></span></a>
						</ul>
					</div>
				</div>
			</div>
		</div>
		-->
		
		<!-- END: TOPBAR -->
		
					
		
		
		<!-- TOPBAR -->
		<div id="topbar" class="topbar-dark">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<ul class="top-menu left">
							<li class="social-facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
							<li class="social-instagram"><a href="#"><i class="fa fa-instagram"></i></a></li>
							<li class="social-linkedin"><a href="#"><i class="fa fa-linkedin"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- END: TOPBAR -->
		
		<!-- TOPBAR -->
		<div id="topbar" class="topbar-colored">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<ul class="top-menu right">
							<a class="button small red-dark button-3d full-rounded" href="#"><span>MyKAMEO</span></a>
							<li><a href="#" onClick="setFr()">Fr</a></li>
							<li><a href="#" onClick="setNl()">Nl</a></li>
							<li><a href="#" onClick="setEn()">En</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- END: TOPBAR -->
		

		<!-- HEADER -->
		<header id="header" class="header-light">
			<div id="header-wrap">
				<div class="container">

					<!--LOGO-->
					<h1>
					<div id="logo">
						<a href="index2.php"><img src="images/logo.png" alt="KAMEO Bikes Logo">
						</a>
					</div>
					</h1>
					<!--END: LOGO-->

					<!--MOBILE MENU -->
					<div class="nav-main-menu-responsive">
						<button class="lines-button x">
							<span class="lines"></span>
						</button>
					</div>
					<!--END: MOBILE MENU -->

					<!--NAVIGATION-->
					<div class="navbar-collapse collapse main-menu-collapse navigation-wrap">
						<div class="container">
							<nav id="mainMenu" class="main-menu mega-menu">
								<ul class="main-menu nav nav-pills">
									<li class="fr"><a href="index2.php"><i class="fa fa-home"></i> Accueil</a></li>
									<li class="en"><a href="index2.php"><i class="fa fa-home"></i> Home</a></li>
									<li class="nl"><a href="index2.php"><i class="fa fa-home"></i> Home</a></li>
									
									<li class="dropdown fr"> <a href="#">Nos solutions <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu">
											<li><a href="velo-partage.php">Vélos partagés</a> </li>
											<li><a href="velo-personnel.php">Vélos personnels</a> </li>
											<li><a href="gestion-flotte.php">Système de gestion de flotte</a> </li>
											<li><a href="leasing.php">Leasing & Vente Vélo</a> </li>
										</ul>
									</li>
									
									<li class="dropdown fr"> <a href="#">Catalogue <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu">
											<li><a href="achat.php">Nos vélos</a> </li>
											<li><a href="accessoires.php">Nos accessoires</a> </li>
										</ul>
									</li>
									
									<li class="fr"><a href="avantages.php">Avantages</a></li>
									
									<li class="fr"><a href="contact2.php">Contact</a></li>
									<li class="en"><a href="contact2.php">Contact</a></li>
									<li class="nl"><a href="contact2.php">Contact</a></li>
									
									<!--
									
									<?php 
									//$_SESSION['login']="false";
                                    $login = isset($_POST['login']) ? $_POST['login'] : "false";
                                    $userID = isset($_POST['userID']) ? $_POST['userID'] : NULL;

                                    
									if ($login=="true" && $userID=NULL)
									{?>
									<li><a class="text-red" href="mykameo.php"><span>My Kameo</span></a></li>
									<?php
									}
									else
									{
									?>
									<li><a class="text-red" data-target="#mykameo" data-toggle="modal" href="#"><span>My Kameo</span></a></li>										
									<?php
									}
									?>
									
									-->
									
								</ul>
							</nav>
						</div>
					</div>
					<!--END: NAVIGATION-->

					
				</div>
			</div>
		</header>
		<!-- END: HEADER -->		




<!-- Hotjar Tracking Code for www.kameobikes.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:1142496,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>