<!-- HEADER -->
<header id="header" class="header-light">
  <div id="header-wrap">
	<div class="container">
	  <!--LOGO-->
	  <div id="logo">
		<a href="/"><img src="/images/logo.png" alt="KAMEO Bikes Logo"></a>
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
			  <li class="fr"><a href="/"><i class="fa fa-home"></i> Accueil</a></li>
			  <li class="en"><a href="/"><i class="fa fa-home"></i> Home</a></li>
			  <li class="nl"><a href="/"><i class="fa fa-home"></i> Home</a></li>
			  <li class="dropdown">
				<a class="fr" href="#">Nos Solutions <i class="fa fa-angle-down"></i> </a>
				<a class="en" href="#">Our Solutions <i class="fa fa-angle-down"></i> </a>
				<a class="nl" href="#">Onze Oplossingen <i class="fa fa-angle-down"></i> </a>
				<ul class="dropdown-menu">
				  <li>
					<a class="fr" href="velo-partage">Vélos partagés</a>
					<a class="en" href="velo-partage">Shared Bikes</a>
					<a class="nl" href="velo-partage">Deelfietsen</a>
				  </li>
				  <li>
					<a class="fr" href="velo-personnel">Vélos personnels</a>
					<a class="en" href="velo-personnel">Personnal Bikes</a>
					<a class="nl" href="velo-personnel">Persoonlijke fietsen</a>
				  </li>
				  <li>
					<a class="fr" href="gestion-flotte">Gestion de flotte</a>
					<a class="en" href="gestion-flotte">Fleet management</a>
					<a class="nl" href="gestion-flotte">Vlootbeheer</a>
				  </li>
				  <li>
					<a class="fr" href="location-tout-inclus">Location tout inclus & Achat</a>
					<a class="en" href="location-tout-inclus">All inclusive rental & Purchase</a>
					<a class="nl" href="location-tout-inclus">All-inclusive verhuur & Aankoop</a>
				  </li>
				</ul>
			  </li>
			  <li class="dropdown">
				<a class="fr" href="#">Catalogue <i class="fa fa-angle-down"></i> </a>
				<a class="en" href="#">Catalogue <i class="fa fa-angle-down"></i> </a>
				<a class="nl" href="#">Catalogus <i class="fa fa-angle-down"></i> </a>
				<ul class="dropdown-menu">
				  <li>
					<a class="fr" href="achat">Nos Vélos</a>
					<a class="en" href="achat">Our Bikes</a>
					<a class="nl" href="achat">Onze Fietsen</a>
				  </li>
				  <li>
					<a class="fr" href="accessoires">Nos Accessoires</a>
					<a class="en" href="accessoires">Our Accessories</a>
					<a class="nl" href="accessoires">Onze Accessoires</a>
				  </li>
				  <li>
					<a class="fr" href="bons-plans">Nos Bons Plans</a>
					<a class="en" href="bons-plans">Our Deals</a>
					<a class="nl" href="bons-plans">Onze Deals</a>
				  </li>
				</ul>
			  </li>
			  <li class="dropdown">
				<a class="fr" href="#">Avantages <i class="fa fa-angle-down"></i> </a>
				<a class="en" href="#">Benefits <i class="fa fa-angle-down"></i> </a>
				<a class="nl" href="#">Voordelen <i class="fa fa-angle-down"></i> </a>
				<ul class="dropdown-menu">
				  <li>
					<a class="fr" href="avantages">Avantages liés au vélo</a>
					<a class="en" href="avantages">Cycling benefits</a>
					<a class="nl" href="avantages">Fietsvoordelen</a>
				  </li>
				  <li>
					<a class="fr" href="cash4bike">Calculateur Cash For Bike</a>
					<a class="en" href="cash4bike">Cash For Bike Calculator</a>
					<a class="nl" href="cash4bike">Cash For Bike Rekening</a>
				  </li>
				</ul>
			  </li>
			  <li class="fr"><a href="contact">Contact</a></li>
			  <li class="en"><a href="contact">Contact</a></li>
			  <li class="nl"><a href="contact">Contact</a></li>
			  <li class="fr"><a href="blog">Media/Blog</a></li>
			  <li class="en"><a href="blog">Media/Blog</a></li>
			  <li class="nl"><a href="blog">Media/Blog</a></li>
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
						<form id="user_management" class="form-transparent-grey" action="apis/Kameo/access_management.php" role="form" method="post">
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

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
						<form id="widget-lostPassword" class="form-transparent-grey" action="apis/Kameo/lost_password.php" role="form" method="post">
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

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