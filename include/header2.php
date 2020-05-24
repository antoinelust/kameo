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
	<title>KAMEO Bikes | Mobilité urbaine pour entreprises</title>

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

  <!-- Template notifications -->
	<link href="css/notifications.css" rel="stylesheet">
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

	<!-- Facebook Pixel Code -->
	<script>
	  !function(f,b,e,v,n,t,s)
	  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	  n.queue=[];t=b.createElement(e);t.async=!0;
	  t.src=v;s=b.getElementsByTagName(e)[0];
	  s.parentNode.insertBefore(t,s)}(window, document,'script',
	  'https://connect.facebook.net/en_US/fbevents.js');
	  fbq('init', '625514371193036');
	  fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	  src="https://www.facebook.com/tr?id=625514371193036&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Facebook Pixel Code -->

</head>

<body class="wide">


	<!-- WRAPPER -->
	<div class="wrapper">

	<!-- TOPBAR -->
	<!--
		<div id="topbar" class="topbar-dark">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<ul class="top-menu">
							<li><a href="#" onClick="setFr()">Fr</a></li>
							<li><a href="#" onClick="setNl()">Nl</a></li>
							<li><a href="#" onClick="setEn()">En</a></li>
						</ul>
					</div>

				</div>
			</div>
		</div>

-->

		<!-- END: TOPBAR -->

		<!-- HEADER -->
		<header id="header" class="header-light">
			<div id="header-wrap">
				<div class="container">

					<!--LOGO-->
					<h1>
					<div id="logo">
						<a href="index.php"><img src="images/logo.png" alt="KAMEO Bikes Logo">
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
									<li class="fr"><a href="index.php"><i class="fa fa-home"></i>Accueil</a></li>
									<li class="en"><a href="index.php"><i class="fa fa-home"></i>Home</a></li>
									<li class="nl"><a href="index.php"><i class="fa fa-home"></i>Home</a></li>

									<li class="dropdown fr"> <a href="#">Notre solution <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu">
											<li><a href="vente-leasing-location.php">Vente, Leasing et Location</a> </li>
											<li><a href="maintenance-assurance.php">Maintenance et Assurance</a> </li>
											<li><a href="gestion-de-flotte.php">Gestion de flotte</a> </li>
										</ul>
									</li>
                                    <li class="dropdown en"> <a href="#">Our solution <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu">
											<li><a href="vente-leasing-location.php">Sell, lease and renting</a> </li>
											<li><a href="maintenance-assurance.php">Assistance and insurance</a> </li>
											<li><a href="gestion-de-flotte.php">Fleet management</a> </li>
										</ul>
									</li>
                                    <li class="dropdown nl"> <a href="#">Onze oplossing <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu">
											<li><a href="vente-leasing-location.php">Verkoopn lease en verhuur</a> </li>
											<li><a href="maintenance-assurance.php">Bijstand en onderhoud</a> </li>
											<li><a href="gestion-de-flotte.php">Vloot beheer</a> </li>
										</ul>
									</li>
									<li class="dropdown fr"> <a href="#">Nos catalogues <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu">
											<li><a href="catalogue.php">Nos vélos</a> </li>
											<li><a href="accessoires.php">Nos accessoires</a> </li>
										</ul>
									</li>
									<li class="dropdown en"> <a href="#">Our catalogs <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu">
											<li><a href="catalogue.php">Our bikes</a> </li>
											<li><a href="accessoires.php">Our accessories</a> </li>
										</ul>
									</li>
									<li class="dropdown nl"> <a href="#">Onze catalogi <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu">
											<li><a href="catalogue.php">Onze fietsen</a> </li>
											<li><a href="accessoires.php">Onze accessoires</a> </li>
										</ul>
									</li>
									<li class="fr"><a href="contact.php">Contact</a></li>
									<li class="en"><a href="contact.php">Contact</a></li>
									<li class="nl"><a href="contact.php">Contact</a></li>

									<?php
                                    $userID = isset($_POST['userID']) ? $_POST['userID'] : NULL;


									if ($userID != NULL)
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
								</ul>
							</nav>
						</div>
					</div>
					<!--END: NAVIGATION-->
				</div>
			</div>
		</header>
		<!-- END: HEADER -->

<div class="modal fade" id="mykameo" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3 class="fr">Accéder à My Kameo</h3>
						<h3 class="en">Access to My Kameo</h3>
						<h3 class="nl">Ga naar My Kameo</h3>

							<form id="user_management" class="form-transparent-grey" action="include/access_management.php" role="form" method="post">
								<div class="form-group">
									<label class="sr-only fr">Adresse mail</label>
									<label class="sr-only en">E-mail</label>
									<label class="sr-only nl">Mail</label>
									<input type="email" name="userID" class="form-control" placeholder="Adresse mail" autocomplete="username">
								</div>
								<div class="form-group m-b-5">
									<label class="sr-only fr">Mot de passe</label>
									<label class="sr-only en">Password</label>
									<label class="sr-only nl">Wachtwoord</label>
									<input type="password" name="password" class="form-control" placeholder="Mot de passe" autocomplete="current-password">
								</div>
								<div class="form-group form-inline text-left ">


									<a data-target="#lostPassword" data-toggle="modal" data-dismiss="modal" href="#" class="right fr"><small>Mot de passe oublié?</small></a>
									<a data-target="#lostPassword" data-toggle="modal" data-dismiss="modal" href="#" class="right nl"><small>Wachtwoord kwijt?</small></a>
									<a data-target="#lostPassword" data-toggle="modal" data-dismiss="modal" href="#" class="right en"><small>Password lost?</small></a>
								</div>
								<div class="text-left form-group">
									<button class="button effect fill fr" type="submit">Accéder</button>
									<button class="button effect fill en" type="submit">Confirm</button>
									<button class="button effect fill nl" type="submit">Bevestingen</button>
								</div>
							</form>
							<script type="text/javascript">
                                jQuery("#user_management").validate({

                                    submitHandler: function(form) {
                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
												window.location.href = "mykameo.php";
                                                } else {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'danger'
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });

                            </script>


					</div>
				</div>
			</div>

		</div>
	</div>
</div>



<div class="modal fade" id="lostPassword" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">

						<h3 class="fr">Mot de passe oublié</h3>
						<h3 class="nl">Wachtwoord kwijt</h3>
						<h3 class="en">Password lost</h3>
							<form id="widget-lostPassword" class="form-transparent-grey" action="include/lostPassword.php" role="form" method="post">
								<div class="form-group">
									<label class="sr-only fr">Adresse mail</label>
									<label class="sr-only en">E-mail</label>
									<label class="sr-only nl">Mail</label>

									<input type="text" name="widget-update-form-email" class="form-control required" autocomplete="username">
								</div>
								<div class="text-left form-group">
									<button  class="button effect fill fr" type="submit"><i class="fa fa-paper-plane"></i>&nbsp;Envoyer</button>
									<button  class="button effect fill en" type="submit" ><i class="fa fa-paper-plane"></i>&nbsp;Confirm</button>
									<button  class="button effect fill nl" type="submit" ><i class="fa fa-paper-plane"></i>&nbsp;Verzenden</button>

								</div>
							</form>
							<script type="text/javascript">
                                jQuery("#widget-lostPassword").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });
                                                } else {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'danger'
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });

                            </script>


					</div>
				</div>
			</div>

		</div>
	</div>
</div>

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
