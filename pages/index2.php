<!DOCTYPE html>
<html lang="fr">
<?php
header_remove("Set-Cookie");
header_remove("X-Powered-By");
include 'include/head.php';
?>
<body class="wide">
<?
	require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
	if(constant('ENVIRONMENT')=="production"){
		include $_SERVER['DOCUMENT_ROOT'].'/include/googleTagManagerBody.php';
	}
?>

	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include 'include/topbar.php'; ?>
		<?php include 'include/header2.php'; ?>
		
		<!-- SLIDER -->
		<section class="no-padding">

			<div id="slider-carousel" class="boxed-slider">

				<div style="background-image:url('images/slider_1_leasing.jpg');" class="owl-bg-img">

					<div class="container-fullscreen">
						<div class="text-middle">
							<div class="container">
								<div class="slider-content">
									<h1 class="text-uppercase text-large"><strong class="text-green">Leasing</strong> <strong class="text-light">de vélos</strong></h1>
									<p class="text-light" style="font-size:24px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sagittis, sem quis lacinia faucibus, orci ipsum gravida tortor, vel interdum mi sapien ut justo.</p>
									<a class="button color full-rounded transparent"  style="margin-right: 2em;" href="leasing-employeur">Je suis un employeur</a>
									<a class="button white full-rounded" style="background-color: white" href="#">Je suis un employé</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="background-image:url('images/slider_2_achat.jpg');" class="owl-bg-img">

					<div class="container-fullscreen">
						<div class="text-middle">
							<div class="container">
								<div class="slider-content">
									<h1 class="text-uppercase text-large"><strong class="text-green">Achat</strong> <strong class="text-light">de vélos</strong></h1>
									<p class="text-light" style="font-size:24px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sagittis, sem quis lacinia faucibus, orci ipsum gravida tortor, vel interdum mi sapien ut justo.</p>
									<a class="button white full-rounded" style="margin-right: 2em; background-color: white" href="#">Je suis un particulier</a>
									<a class="button color full-rounded transparent" style="margin-right: 2em;" href="#">Je suis un employeur</a>
									<a class="button white full-rounded" style="background-color: white" href="#">Je suis un employé</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="background-image:url('images/slider_3_entretien.jpg');" class="owl-bg-img">

					<div class="container-fullscreen">
						<div class="text-middle">
							<div class="container">
								<div class="slider-content">
									<h1 class="text-uppercase text-large"><strong class="text-green">Entretien</strong> <strong class="text-light">de vélos</strong></h1>
									<p class="text-light" style="font-size:24px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sagittis, sem quis lacinia faucibus, orci ipsum gravida tortor, vel interdum mi sapien ut justo.</p>
									<a class="button color full-rounded transparent"  style="margin-right: 2em;" href="#">Je suis un particulier</a>
									<a class="button white full-rounded" style="background-color: white" href="#">Je suis une entreprise</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END: SLIDER -->
		
		<!-- SECTION SLIDER OWL -->
		<!--
		<div id="slider">
			<div id="slider-carousel">
				<div style="background-image:url('images/slider_1_leasing.jpg');" class="owl-bg-img fullscreen">
					<div class="container-fullscreen">
						<div class="text-middle">
							<div class="container">
								<div class="col-md-12 slider-content">
									<h1 class="text-uppercase text-large"><strong class="text-green">Leasing</strong> <strong class="text-light">de vélos</strong></h1>
									<p class="text-light" style="font-size:24px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sagittis, sem quis lacinia faucibus, orci ipsum gravida tortor, vel interdum mi sapien ut justo.</p>
									<a class="button green full-rounded"  style="margin-right: 2em;" "href="#"><span>Je suis un employeur</span></a>
									<a class="button white full-rounded" style="background-color: white" href="#"><span>Je suis un employé</span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="background-image:url('images/slider_2_achat.jpg');" class="owl-bg-img fullscreen">
					<div class="container-fullscreen">
						<div class="text-middle">
							<div class="container">
								<div class="col-md-12 slider-content">
									<h1 class="text-uppercase text-large"><strong class="text-green">Achat</strong> <strong class="text-light">de vélos</strong></h1>
									<p class="text-light" style="font-size:24px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sagittis, sem quis lacinia faucibus, orci ipsum gravida tortor, vel interdum mi sapien ut justo.</p>
									<a class="button white full-rounded" style="margin-right: 2em; background-color: white" href="#"><span>Je suis un particulier</span></a>
									<a class="button green full-rounded" style="margin-right: 2em;" "href="#"><span>Je suis un employeur</span></a>
									<a class="button white full-rounded" style="background-color: white" href="#"><span>Je suis un employé</span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="background-image:url('images/slider_3_entretien.jpg');" class="owl-bg-img fullscreen">
					<div class="container-fullscreen">
						<div class="text-middle">
							<div class="container">
								<div class="col-md-12 slider-content">
									<h1 class="text-uppercase text-large"><strong class="text-green">Entretien</strong> <strong class="text-light">de vélos</strong></h1>
									<p class="text-light" style="font-size:24px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sagittis, sem quis lacinia faucibus, orci ipsum gravida tortor, vel interdum mi sapien ut justo.</p>
									<a class="button green full-rounded"  style="margin-right: 2em;" "href="#"><span>Je suis un particulier</span></a>
									<a class="button white full-rounded" style="background-color: white" href="#"><span>Je suis une entreprise</span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		-->
		<!-- END: SECTION SLIDER OWL -->
		
		<div class="jumbotron jumbotron-center jumbotron-fullwidth" style="background-color: #636363";>
			<div class="container">
				<h1 class="text-uppercase text-large"><strong class="text-green">Services</strong> <strong class="text-light">annexes</strong></h1>
				<p class="text-light" style="font-size:24px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sagittis, sem quis lacinia faucibus, orci ipsum gravida tortor, vel interdum mi sapien ut justo.</p>
				<a class="button color full-rounded transparent" style="margin-right: 2em;" href="#"><span>Gestion de flotte</span></a>
				<a class="button white full-rounded" style="margin-right: 2em; background-color: white" href="#"><span>Infrastructures</span></a>
				<a class="button color full-rounded transparent" href="#"><span>Expertise / Conseil</span></a>
			</div>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h1 class="text-green"><?= L::choose_kameo_title; ?></h1>
					<br>
					<p class="text-justify"><?= L::choose_kameo_subtitle; ?></p>
					<p class="text-justify"><?= L::choose_kameo_text; ?></p>
					<p class="text-justify"><?= L::choose_kameo_text2; ?></p>
					<p><?= L::choose_kameo_text3; ?></p>
				</div>
				<div class="col-md-6">
					<img src="images/Roue_Kameo_Montage_GIF.gif" class="img-responsive img-rounded center" alt="Roue des services KAMEO Bikes" style="width:75%">
				</div>
			</div>
		</div>
		
		<section class="background-green">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<h2 class="text-light text-uppercase text-medium">Conway<br>
						<strong class="text-dark">cairon t 270 2021</strong></h2>
						<p class="text-light text-justify">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.<br>
						Sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
						<a class="button white full-rounded" style="margin-right: 2em; background-color: white" href="#">En savoir plus</a>
					</div>
					<div class="col-md-6">
						<img src="images/t270.png" class="img-responsive img-rounded center" alt="Roue des services KAMEO Bikes">
					</div>
					<a class="button full-rounded text-light" style="margin-right: 2em; background-color: black" href="#">Voir tout le catalogue</a>
				</div>
			</div>
		</section>
		
		<!-- SECTION CLIENTS -->
		<section class="p-b-0">
			<div class="container">
				<h1 class="text-green"><?= L::societes_title; ?></h1>
				<ul class="grid grid-4-columns">
					<!-- <li style="cursor: default;"><img draggable="false" src="images/clients/bpost.png" alt="Nos clients - BPost"></li> -->
					<li style="cursor: default;"><img draggable="false" src="images/clients/actiris.png" alt="Nos clients - Actiris"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/infrabel.png" alt="Nos clients - Infrabel"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/afelio.png" alt="Nos clients - Afelio"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/atradius.png" alt="Nos clients - Atradius"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/galler.png" alt="Nos clients - Galler Chocolatiers"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/siapartners.png" alt="Nos clients - SiaPartners"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/spi.png" alt="Nos clients - SPI"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/agc.png" alt="Nos clients - AGC"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/rayon9.png" alt="Nos clients - Rayon 9"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/chu.png" alt="Nos clients - CHU Liège"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/dedale.png" alt="Nos clients - Dedale Assurances"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/elegis.png" alt="Nos clients - Elegis"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/epsylon.png" alt="Nos clients - Epsylon"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/infine.png" alt="Nos clients - In Fine"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/idea.png" alt="Nos clients - IDEA"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/bxlville.png" alt="Nos clients - Ville de Bruxelles"></li>
					<li style="cursor: default;"><img draggable="false" src="images/clients/prefer.png" alt="Nos clients - Prefer"></li>
				</ul>
			</div>
		</section>
		<!-- END: SECTION CLIENTS -->
		
		
		<!--
		<section class="newbox box-fancy section-fullwidth text-light p-b-0">
			<div class="row" style="margin-top: -100px;">
				<div class="col-md-4" style="background: linear-gradient(-24deg, rgba(52,154,130,1) 0%, rgba(59,178,150,1) 100%); margin-top: -75px ; margin-bottom: -75px ;">
					<h1 class="text-large text-uppercase">Achat</h1>
					<div  style="background-color: rgba(255, 255, 255, .1); padding : 1em ; border: 1px solid white; ">
						<span>Je suis un <strong>particulier</strong> et je souhaite acheter un vélo. Nous avons une section dédiée pour vous.<br><a class="button border icon-left" href=""><span><i class="fa fa-arrow-right"></i>Vers Test & Ride</span></a></span>
					</div>
					<div class="space"></div>
					<div  style="background-color: rgba(255, 255, 255, .1); padding : 1em ; border: 1px solid white; ">
						<span>Je suis un <strong>employeur</strong> et je souhaite acheter un vélo pour mettre à disposition de mes employés.<br><a class="button border icon-left" href=""><span><i class="fa fa-arrow-right"></i>En savoir plus</span></a></span>
					</div>
					<div class="space"></div>
					<div  style="background-color: rgba(255, 255, 255, .1); padding : 1em ; border: 1px solid white; ">
						<span>Je suis un <strong>employé</strong> et je souhaite savoir comment faire pour acheter un vélo d'entreprise.<br><a class="button border icon-left" href=""><span><i class="fa fa-arrow-right"></i>Découvrir comment faire</span></a></span>
					</div>
				</div>
				
				<div class="col-md-4" style="background: linear-gradient(-24deg, rgba(52,154,130,1) 0%, rgba(59,178,150,1) 100%); margin-top: -75px ; margin-bottom: -75px ;">
					<h1 class="text-large text-uppercase">Leasing</h1>
					<div  style="background-color: rgba(255, 255, 255, .1); padding : 1em ; border: 1px solid white; ">
						<span>Je suis un <strong>employeur</strong> et je souhaite prendre des vélos en leasing pour mettre à disposition de mes employés.<br><a class="button border icon-left" href=""><span><i class="fa fa-arrow-right"></i>En savoir plus</span></a></span>
					</div>
					<div class="space"></div>
					<div  style="background-color: rgba(255, 255, 255, .1); padding : 1em ; border: 1px solid white; ">
						<span>Je suis un <strong>employé</strong> et je souhaite savoir comment faire pour avoir des vélos en leasing au sein de mon entreprise.<br><a class="button border icon-left" href=""><span><i class="fa fa-arrow-right"></i>Découvrir comment faire</span></a></span>
					</div>
				</div>

				<div class="col-md-4" style="background: linear-gradient(-24deg, rgba(52,154,130,1) 0%, rgba(59,178,150,1) 100%); margin-top: -75px ; margin-bottom: -75px ;">
					<h1 class="text-large text-uppercase">Entretiens</h1>
					<div  style="background-color: rgba(255, 255, 255, .1); padding : 1em ; border: 1px solid white; ">
						<span>Je suis un <strong>particulier</strong> et je souhaite faire entretenir mon vélo.<br><a class="button border icon-left" href=""><span><i class="fa fa-arrow-right"></i>Vers entretien-vélo.com</span></a></span>
					</div>
					<div class="space"></div>
					<div  style="background-color: rgba(255, 255, 255, .1); padding : 1em ; border: 1px solid white; ">
						<span>Je suis une <strong>entreprise</strong> et je souhaite faire entretenir ma flotte de vélos.<br><a class="button border icon-left" href=""><span><i class="fa fa-arrow-right"></i>En savoir plus</span></a></span>
					</div>
				</div>
			</div>
		</section>
		-->
		<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->

	<!-- Theme Base, Components and Settings -->
	<script src="/js/theme-functions.js"></script>
	<!-- Language management -->
	<script type="text/javascript" src="/js/language.js"></script>

	<?php
	if (isset($_GET['hash'])) {
		echo "coucou";
		echo $_GET['hash'];
		echo '<script type="text/javascript">
      $("#newPassword").modal("toggle");
    </script>';
	}
	?>

	<?php
	if (isset($_GET['deconnexion']) && $_GET['deconnexion'] == true) {
		if ($_SESSION['langue'] == 'en')
			$message = "You have been disconnected due to inactivity";
		elseif ($_SESSION['langue'] == 'nl')
			$message = "U bent afgesloten vanwege inactiviteit";
		else
			$message = "Vous avez été déconnecté pour cause d\'inactivité";
		echo `<script type="text/javascript">
  			test="` . $message . `";
  			console.log("deconnexion:"+test);
          $.notify({
              message: '` . $message . `'
          }, {
              type: 'danger'
          });
        </script>`;
	}
	?>
</body>

</html>
