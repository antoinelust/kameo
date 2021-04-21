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
		
		<!-- SECTION SLIDER OWL -->
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
		<!-- END: SECTION SLIDER OWL -->
		
		<div class="jumbotron jumbotron-center jumbotron-fullwidth background-dark">
			<div class="container">
				<h1 class="text-uppercase text-large"><strong class="text-green">Services</strong> <strong class="text-light">annexes</strong></h1>
				<p class="text-light" style="font-size:24px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sagittis, sem quis lacinia faucibus, orci ipsum gravida tortor, vel interdum mi sapien ut justo.</p>
				<a class="button green full-rounded text-light" style="margin-right: 2em;" href="#"><span>Gestion de flotte</span></a>
				<a class="button white full-rounded" style="margin-right: 2em; background-color: white" "href="#"><span>Infrastructures</span></a>
				<a class="button green full-rounded text-light" href="#"><span>Expertise / Conseil</span></a>
			</div>
		</div>
		
		
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
