<!DOCTYPE html>
<html lang="en">
<?php
  if(!isset($_SESSION))
  {
      session_start();
  }
?>
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<?php
    if(substr($_SERVER['REQUEST_URI'], 1, 4) != "test" && substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
        include 'googleAnalytics.php';
    }
?>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="description" content="KAMEO Bikes, Mobilité urbaine pour entreprises. Vente, Leasing et Location de vélos pour entreprises. Entretien sur votre lieu de travail ou à domicile.">
 	<meta name="keywords" content="mobilité, vélos, vélos électriques, VAE, entretiens à domicile, entretiens sur le lieu de travail, Orbea, Ahooga, Conway, Victoria, Tern, i:SY, vélo urbain, vélo cargo, accessoires vélo, casques vélo, cadenas vélo">
 	<meta name="author" content="Thibaut Mativa">
 	<meta property="og:image" content="http://www.kameobikes/images/vignette.jpg" />

	<link rel="shortcut icon" href="images/favicon.png">
	<title class="fr">KAMEO Bikes | La solution complète pour vos vélos de société</title>
	<title class="fr">KAMEO Bikes | Bike solutions for businesses</title>
	<title class="fr">KAMEO Bikes | Fiets oplossingen voor bedrijven</title>

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

</head>

<body class="wide">

	<!-- WRAPPER -->
	<div class="wrapper">

	<!-- TOPBAR -->
	<div id="topbar" class="topbar-colored">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<ul class="top-menu right">
						<li class="social-facebook text-light"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
						<li class="social-linkedin text-light"><a href="https://www.linkedin.com/company/kameobikes/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
						<li><a href="#" onClick="setFr()">Fr</a></li>
						<li><a href="#" onClick="setNl()">Nl</a></li>
						<li><a href="#" onClick="setEn()">En</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- END: TOPBAR -->
	<!-- TOPBAR -->
	<!--
	<div id="topbar" class="topbar-colored">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<ul class="top-menu right">
          <?php
            $userID = isset($_POST['userID']) ? $_POST['userID'] : NULL;

            if ($userID != NULL){?>
                <a class="text-red" href="mykameo.php"><span>My Kameo</span></a>
                <?php
            }
            else
            {
                ?>
                <a class="button small red-dark button-3d full-rounded" data-target="#mykameo" data-toggle="modal" href="#"><span>My Kameo</span></a>
                <?php
            }
            ?>
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
          <div id="logo">
            <a href="index.php"><img src="images/logo.png" alt="KAMEO Bikes Logo"></a>
          </div>
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
                  <li class="fr"><a href="index.php"><i class="fa fa-home"></i> Accueil</a></li>
                  <li class="en"><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                  <li class="nl"><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                  <li class="dropdown">
                    <a class="fr" href="#">Nos Solutions <i class="fa fa-angle-down"></i> </a>
                    <a class="en" href="#">Our Solutions <i class="fa fa-angle-down"></i> </a>
                    <a class="nl" href="#">Onze Oplossingen <i class="fa fa-angle-down"></i> </a>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="fr" href="velo-partage.php">Vélos partagés</a>
                        <a class="en" href="velo-partage.php">Shared Bikes</a>
                        <a class="nl" href="velo-partage.php">Deelfietsen</a>
                      </li>
                      <li>
                        <a class="fr" href="velo-personnel.php">Vélos personnels</a>
                        <a class="en" href="velo-personnel.php">Personnal Bikes</a>
                        <a class="nl" href="velo-personnel.php">Persoonlijke fietsen</a>
                      </li>
                      <li>
                        <a class="fr" href="gestion-flotte.php">Gestion de flotte</a>
                        <a class="en" href="gestion-flotte.php">Fleet management</a>
                        <a class="nl" href="gestion-flotte.php">Vlootbeheer</a>
                      </li>
                      <li>
                        <a class="fr" href="location-tout-inclus.php">Location tout inclus & Achat</a>
                        <a class="en" href="location-tout-inclus.php">All inclusive rental & Purchase</a>
                        <a class="nl" href="location-tout-inclus.php">All-inclusive verhuur & Aankoop</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a class="fr" href="#">Catalogue <i class="fa fa-angle-down"></i> </a>
                    <a class="en" href="#">Catalogue <i class="fa fa-angle-down"></i> </a>
                    <a class="nl" href="#">Catalogus <i class="fa fa-angle-down"></i> </a>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="fr" href="achat.php">Nos Vélos</a>
                        <a class="en" href="achat.php">Our Bikes</a>
                        <a class="nl" href="achat.php">Onze Fietsen</a>
                      </li>
                      <li>
                        <a class="fr" href="accessoires.php">Nos Accessoires</a>
                        <a class="en" href="accessoires.php">Our Accessories</a>
                        <a class="nl" href="accessoires.php">Onze Accessoires</a>
                      </li>
                      <li>
                        <a class="fr" href="bonsplans.php">Nos Bons Plans</a>
                        <a class="en" href="bonsplans.php">Our Deals</a>
                        <a class="nl" href="bonsplans.php">Onze Deals</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a class="fr" href="#">Avantages <i class="fa fa-angle-down"></i> </a>
                    <a class="en" href="#">Benefits <i class="fa fa-angle-down"></i> </a>
                    <a class="nl" href="#">Voordelen <i class="fa fa-angle-down"></i> </a>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="fr" href="avantages.php">Avantages liés au vélo</a>
                        <a class="en" href="avantages.php">Cycling benefits</a>
                        <a class="nl" href="avantages.php">Fietsvoordelen</a>
                      </li>
                      <li>
                        <a class="fr" href="cash4bike.php">Calculateur Cash For Bike</a>
                        <a class="en" href="cash4bike.php">Cash For Bike Calculator</a>
                        <a class="nl" href="cash4bike.php">Cash For Bike Rekening</a>
                      </li>
                    </ul>
                  </li>
                  <li class="fr"><a href="contact2.php">Contact</a></li>
                  <li class="en"><a href="contact2.php">Contact</a></li>
                  <li class="nl"><a href="contact2.php">Contact</a></li>
                  <?php
                    $login = isset($_POST['login']) ? $_POST['login'] : isset($_SESSION['login']) ? $_SESSION['login'] : "false";
                    $userID = isset($_POST['userID']) ? $_POST['userID'] : isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL;
                    if ($login!="false" || $userID!=NULL)
                          echo '<li><a class="text-red" href="mykameo.php"><span>My Kameo</span></a></li>';
                    else
                          echo '<li><a class="text-red" data-target="#mykameo" data-toggle="modal" href="#"><span>My Kameo</span></a></li>' . "\n";
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
									<label for="widget-update-form-email" class="fr">Adresse mail</label>
									<label for="widget-update-form-email" class="en">E-mail</label>
									<label for="widget-update-form-email" class="nl">Mail</label>
									<input type="text" name="widget-update-form-email" class="form-control required" autocomplete="username">
								</div>
								<div class="text-left form-group">
									<button  class="button effect fill fr" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
									<button  class="button effect fill en" type="submit" ><i class="fa fa-paper-plane"></i>Confirm</button>
									<button  class="button effect fill nl" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>

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
