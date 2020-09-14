<!DOCTYPE html>
<html lang="fr">
<?php
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
		<?php include 'include/header.php'; ?>
		<!--Square icons-->
  <section>

	<div class="container">
		<div class="row">
				<h1 class="text-green"><?=L::gestion_flotte_title?></h1>
				<br>
				<div class="col-md-12" style="background: url('images/fond_degrade4.jpg');" data-stellar-background-ratio="0.6">
					<h4 class="col-md-6 text-center"><?=L::gestion_flotte_list1_1?></h4><br>
					<h4 class="col-md-6 text-center"><?=L::gestion_flotte_list1_2?></h4><br>
					<h4 class="col-md-6 text-center"><?=L::gestion_flotte_list1_3?></h4><br>
					<h4 class="col-md-6 text-center"><?=L::gestion_flotte_list1_4?></h4><br>
					<h4 class="col-md-6 text-center"><?=L::gestion_flotte_list1_5?></h4><br>
					<h4 class="col-md-6 text-center"><?=L::gestion_flotte_list1_6?></h4><br>
					<h4 class="col-md-6 text-center"><?=L::gestion_flotte_list1_7?></h4><br>
					<h4 class="col-md-6 text-center"><?=L::gestion_flotte_list1_8?></h4><br>
					<h4 class="col-md-6 text-center"><?=L::gestion_flotte_list1_9?></h4>
				</div>
				<div class="space"></div>
				<h4 class="text-center"><?=L::gestion_flotte_fleet_text1?></h4>
				<h4 class="text-center"><?=L::gestion_flotte_fleet_text2?></h4>

				<div class="separator"></div>

				<div class="col-md-6">
					<h3><?=L::gestion_flotte_mykameo?></h3>
					<p><?=L::gestion_flotte_mykameo_description?></p>
					<img src="images/MyKameo_face.jpg" class="img-responsive img-rounded img-thumbnail" alt="Ecran MyKAMEO">
				</div>
				<div class="col-md-6">
					<h3><?=L::gestion_flotte_myborne?></h3>
					<p><?=L::gestion_flotte_myborne_description?></p>
					<img src="images/Borne_Web_Out.jpg" class="img-responsive img-rounded img-thumbnail" alt="MyBorne">
				</div>

				<div class="space"></div>
				<!--

				<p class="fr">Gérer sa mobilité demande de l’énergie et du temps! En partant de ce constat, KAMEO Bikes a développé sa plateforme <strong class="text-red">MyKAMEO</strong> et son système de rangement de clés <strong class="text-green">MyBORNE</strong>.</p>
				<p>L’objectif de cette plateforme est de digitaliser l’ensemble des actions répétitives liées à vos déplacements tant au niveau administratif qu’au niveau pratique. De base, la demande d’un entretien, la gestion des factures ou encore le remplissage d’une déclaration de vol y sont entièrement automatisés.</p>
				<p class="fr">Vous avez des difficultés à gérer votre flotte de matériel roulant? Vous souhaitez le faire selon des critères complexes à optimiser? Nous pouvons implémenter ensemble votre système de gestion sur mesure! Simplifiez vous la vie en automatisant les tâches répétitives et chronophages.</p>
				<p class="fr">Par exemple, notre système de gestion de flotte partagées peut intégrer tous vos véhicules et être paramétré en fonction de vos besoins!</p>
				-->
				<div class="col-md-6">
					<p class="text-center"><?=L::gestion_flotte_mykameo_text?></p>
				</div>
				<div class="col-md-6">
					<p class="text-center"><strong class="text-red"><?=L::gestion_flotte_myborne_text?></p>
				</div>
				<div class="col-md-6">
					<h4><?=L::gestion_flotte_usercan?></h4>
					<ul>
					<?=L::gestion_flotte_usercan_list?>
					</ul>
				</div>
				<div class="col-md-6">
					<h4><?=L::gestion_flotte_admin?></h4>
					<ul>
					<?=L::gestion_flotte_admin_list?>
					</ul>
				</div>
				<div class="space"></div>
				<p><?=L::gestion_flotte_admin_addfunc?></p>
				<div class="separator"></div>

				<h3><?=L::gestion_flotte_reserve_bike?></h3>

				<div class="col-md-3">
					<img src="images/Mykameo1.png" class="img-responsive img-rounded" alt="Réserver un véhicule - étape 1">
					<p class="text-center"><?=L::gestion_flotte_reserve_connect?></p>
				</div>

				<div class="col-md-3">
					<img src="images/Mykameo2.png" class="img-responsive img-rounded" alt="Réserver un véhicule - étape 2">
					<p class="text-center"><?=L::gestion_flotte_reserve_code?></p>
				</div>

				<div class="col-md-3">
					<img src="images/Mykameo3.png" class="img-responsive img-rounded" alt="Réserver un véhicule - étape 3">
					<p class="text-center"><?=L::gestion_flotte_reserve_key?></p>
				</div>

				<div class="col-md-3">
					<img src="images/Mykameo4.png" class="img-responsive img-rounded" alt="Réserver un véhicule - étape 4">
					<p class="text-center"><?=L::gestion_flotte_reserve_unlock?></p>
				</div>

		</div>
	</div>
</section>

<!-- CALL TO ACTION -->
<div class="jumbotron jumbotron-center jumbotron-fullwidth background-green text-light">
  <div class="container">
    <h3><?=L::gestion_flotte_know_more?></h3>
    <p><?=L::gestion_flotte_know_more_sub?></p>
    <div> <a class="button large black-light button-3d effect icon-left" href="contact.php"><span><i class="fa fa-cloud"></i><?=L::gestion_flotte_know_more_btn?></span></a> </div>
	</div>
</div>

<!--END: CALL TO ACTION -->

<?php include 'include/footer.php'; ?>

</div>
<!-- END: WRAPPER -->

<!-- Theme Base, Components and Settings -->
<script src="js/theme-functions.js"></script>

<!-- Language management -->
<script type="text/javascript" src="js/language.js"></script>

</body>
</html>
