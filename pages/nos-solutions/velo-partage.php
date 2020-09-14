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
			<!-- SECTION SOLUTIONS -->
			<section class="" style="background: url('images/fond_degrade.jpg');" data-stellar-background-ratio="0.3">
				<div class="container">
				<a class="button black-light button-3d effect fill-vertical right"  href="velo-personnel.php"><?=L::btn_personalbikes_btnpbikes;?>&nbsp;<i class="fa fa-arrow-right"></i></a>
					<div class="row">
						<div class="col-md-7">
							<!-- <hr class="space"> -->
                            <h1 class="text-dark"><?=L::description_title;?></h1>
							<p class="lead text-light text-justify"><?=L::description_text1;?></p>
							<p class="lead text-light"><?=L::description_text2;?></p>

							<a class="button black-light button-3d effect fill-vertical scroll-to"  href="#plus"><span><?=L::description_btn_discover_offer;?><i class="fa fa-arrow-down"></i></span></a>
						</div>
						<div class="col-md-5">
							<img src="images/Atradius_Bikes.jpg" class="img-responsive img-rounded" alt="Vélos électriques BZEN brandés aux couleurs d'Atradius">
						</div>
						<hr class="space" id="plus">

					</div>
				</div>
			</section>
			<!-- END: SECTION SOLUTIONS -->

			<!-- SECTION PROCESS -->
			<!--
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<img src="images/infographie_fr.png" class="img-responsive img-rounded" alt="Infographie - Parcous client chez KAMEO Bikes">
						</div>
						<div class="col-md-4"> -->
							<!-- <hr class="space"> -->
							<!--
							<h1 class="text-green">COMMENT ÇA MARCHE</h1>
							<p><strong class="text-green">Vous restez concentré sur votre activité, on se charge de tout!</strong></p>
							<ul>
								<li>Nous définissons ensemble vos besoins réels pour votre future flotte</li>
								<li>Nous vous proposons différent vélos</li>
								<li>Nous organisons des sessions d'essais avec vos employés</li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			-->
			<!-- END: SECTION PROCESS -->


			<!-- SECTION FLOTTE -->
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<!-- <hr class="space"> -->
							<h1 class="text-green"><?=L::fleet_title;?></h1>
							<p class="text-justify"><?=L::fleet_text1;?></p>
							<p class="text-justify"><?=L::fleet_text2;?></p>
						    <p class="text-justify"><?=L::fleet_text3;?></p>


						</div>
						<div class="col-md-6">
							<img src="images/Flotte_BZen.jpg" class="img-responsive img-rounded" alt="BZEN - flotte de vélos">
						</div>

						<!-- SELECTION -->
						<div class="col-md-12">
						<h3 class="text-green"><?=L::fleet_selection;?></h3>
							<div class="carousel" data-lightbox-type="gallery">
								<div class="portfolio-item">
									<div class="portfolio-image effect social-links">
										<img src="images_bikes/bzen_amsterdam_f_mini.jpg" alt="BZEN Amsterdam">
										<div class="image-box-content">
											<p>
												<a href="offre.php?brand=bzen&model=amsterdam&frameType=f"><i class="fa fa-eye"></i></a>
											</p>
										</div>
									</div>
									<div class="">
										<h4 class="title text-center"><?=L::bike_name_bzenamsterdam;?></h4>
									</div>
								</div>


								<div class="portfolio-item">
									<div class="portfolio-image effect social-links">
										<img src="images_bikes/conway_cairon-t-200-se-500_f_mini.jpg" alt="CONWAY Cairon">
										<div class="image-box-content">
											<p>
												<a href="offre.php?brand=conway&model=cairon t 200 se 500&frameType=f"><i class="fa fa-eye"></i></a>
											</p>
										</div>
									</div>
									<div class="">
										<h4 class="title text-center"><?=L::bike_name_conwaycairont200mixed;?></h4>
									</div>
								</div>

								<div class="portfolio-item">
									<div class="portfolio-image effect social-links">
										<img src="images_bikes/ahooga_modular-bike-low-step_f_mini.jpg" alt="AHOOGA Modular">
										<div class="image-box-content">
											<p>
												<a href="offre.php?brand=ahooga&model=modular bike hybrid&frameType=h"><i class="fa fa-eye"></i></a>
											</p>
										</div>
									</div>
									<div class="">
										<h4 class="title text-center"><?=L::bike_name_ahoogamodular;?></h4>
									</div>
								</div>

								<div class="portfolio-item">
									<div class="portfolio-image effect social-links">
										<img src="images_bikes/conway_cairon-t-200-se-500_m_mini.jpg" alt="AHOOGA Modular">
										<div class="image-box-content">
											<p>
												<a href="offre.php?brand=conway&model=cairon%20t%20200%20se%20500&frameType=m"><i class="fa fa-eye"></i></a>
											</p>
										</div>
									</div>
									<div class="">
                                        <h4 class="title text-center"><?=L::bike_name_conwaycairont200mixed;?></h4>
									</div>
								</div>


							</div>
						</div>
						<!-- END : SELECTION -->
					</div>
				</div>
			</section>
			<!-- END: SECTION FLOTTE -->

			<!-- SECTION GESTION FLOTTE -->
			<section class="p-b-0">
				<div class="container">
					<div class="row" style="background-color: white;">
					<!--
						<div class="col-md-4">
							<img src="images/Borne.jpg" class="img-responsive img-rounded" alt="MyBORNE - gestion des clés de votre flotte">
						</div>
						<div class="col-md-8">
					-->
							<!-- <hr class="space"> -->
					<!--
							<h1 class="text-green">GESTION DE LA FLOTTE</h1>
							<p class="text-justify">Gérer une flotte de vélos partagés demande de l’organisation et du temps ! MyKAMEO a été développé pour permettre à nos clients de profiter de leurs vélos sans devoir s’en soucier.</p>

							<p class="text-justify">La plateforme MyKAMEO est une solution IT de gestion qui permet à chaque utilisateur de se connecter sur un espace sécurisé, de réserver un vélo de la flotte partagée de l’entreprise et de donner du feedback quant à l’état et l’utilisation de celui-ci. Elle permet également au Fleet Manager de contrôler la flotte, les réservations et de paramétrer l’ensemble. Des statistiques mensuelles sur l’utilisation des vélos et leur état lui sont envoyées automatiquement afin de pouvoir suivre facilement l’évolution du projet vélo !</p>

							<p class="text-justify"">La demande d’un entretien, la gestion des factures ou encore le remplissage d’une déclaration de vol y sont entièrement automatisés.</p>
				-->
							<!-- <h1 class="text-green text-center fr">GESTION DE LA FLOTTE</h1>
							<h1 class="text-green text-center en">GESTION DE LA FLOTTE</h1>
							<h1 class="text-green text-center nl">GESTION DE LA FLOTTE</h1>	-->
			 				<div class="col-md-7"><br><br>
									<h4 class="col-md-6 text-center"><?=L::manage_fleet_secureacces;?></h4><br>
                                    <h4 class="col-md-6 text-center"><?=L::manage_fleet_keymanage;?></h4><br>
									<h4 class="col-md-6 text-center"><?=L::manage_fleet_maintrequest;?></h4><br>
                                    <h4 class="col-md-6 text-center"><?=L::manage_fleet_batterycharge;?></h4><br>
									<h4 class="col-md-6 text-center"><?=L::manage_fleet_accessoriesacces;?></h4><br>
                                    <h4 class="col-md-6 text-center"><?=L::manage_fleet_bikereserve;?></h4><br>
									<h4 class="col-md-6 text-center"><?=L::manage_fleet_usermanage;?></h4><br>
                                    <h4 class="col-md-6 text-center"><?=L::manage_fleet_termsofuse;?></h4>
									<h4 class="col-md-6 text-center"><?=L::manage_fleet_monitoring;?></h4><br>
							</div>
							<div class="col-md-5">
							<h1 class="text-green text-center"><?=L::manage_fleet_title;?></h1>
								<p class="text-justify background-white"><?=L::manage_fleet_text1;?></p>
								<p class="text-justify background-white"><?=L::manage_fleet_text2;?></p>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- END: SECTION GESTION FLOTTE -->

			<!-- CALL TO ACTION -->
			<div class="jumbotron jumbotron-center jumbotron-fullwidth background-dark text-light">
			  <div class="container">
			    <h3><?=L::action_test1month;?></h3>
			    <p><?=L::action_test_subtitle;?></p>
		   		<div><a class="button large green button-3d effect icon-left" href="contact.php"><?=L::action_btncontact;?><span><i class="fa fa-send"></i></span></a></div>
			</div>
		</div>

			<!--END: CALL TO ACTION -->

			<!-- SECTION MAINTENANCE -->
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<!-- <hr class="space"> -->
							<h1 class="text-green"><?=L::insurance_title;?></h1>
							<p class="text-justify"><?=L::insurance_uppertext;?></p>

							<p class="text-justify"><?=L::insurance_text2;?></p>

						</div>
						<div class="col-md-4">
							<img src="images/pvelo.png" class="img-responsive img-rounded" alt="PVELO - l'assurance vélo">
						</div>
					</div>
				</div>
			</section>
			<!-- END: SECTION MAINTENANCE -->

			<!-- SECTION INFRASTRUCTURES -->
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
						<!-- <h1 class="text-green fr">INSTALLATION D'INFRASTRUCTURES</h1>
						<h1 class="text-green en">INSTALLATION D'INFRASTRUCTURES</h1>
						<h1 class="text-green nl">INSTALLATION D'INFRASTRUCTURES</h1> -->
							<img src="images/infrastructure.png" class="img-responsive img-rounded" alt="Schéma d'une infrastructure réalisable par KAMEO Bikes">
						</div>
						<div class="col-md-6">
							<!-- <hr class="space"> -->
							<h1 class="text-green"><?=L::infra_title;?></h1>
							<p class="text-justify"><?=L::infra_subtitle;?><br><?=L::infra_text;?></p>

							<p class="text-justify"><?=L::infra_contact;?></p>

						</div>
					</div>
				</div>
			</section>
			<!-- END: SECTION INFRASTRUCTURES -->

			<!-- SECTION LOCATION TOUT INCLUS -->
			<!--
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="text-green">LOCATION TOUT INCLUS OU ACHAT?</h1>
							<p>Vous choisirez la formule qui vous convient le mieux.</p>
							<a class="button green button-3d effect fill-vertical" href="location-tout-inclus.php"><span><i class="fa fa-balance-scale"></i>Comparer</span></a>
						</div>
					</div>
				</div>
			</section>
			-->
			<!-- END: SECTION LOCATION TOUT INCLUS -->


				<hr class="space">
			<!-- CALL TO ACTION -->
				<div class="jumbotron jumbotron-center jumbotron-fullwidth text-light" style="background: url('images/fond_degrade3.jpg');" data-stellar-background-ratio="0.3">
				  <div class="container">
				    <h3><?=L::meet_title;?></h3>
				    <p><?=L::meet_pedal;?><br><?=L::meet_contact;?></p>
				    <div> <a class="button large black-light button-3d effect icon-left" href="contact.php"><span><i class="fa fa-send"></i><?=L::meet_btncontact;?></span></a> </div>
				</div>
			</div>

<!--END: CALL TO ACTION -->

<!-- Language management -->
<script type="text/javascript" src="js/language.js"></script>

	<div class="modal fade" id="newPassword" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3><?=L::sharedBike_forgotPass;?></h3>
						<form id="widget-lostPassword-form" action="apis/Kameo/lostPassword.php" role="form" method="post">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="subject"><?=L::sharedBike_newPass;?></label>
                                    <input type="password" name="widget-lostPassword-form-new-password" class="form-control required" autocomplete="new-password">
                                </div>
                            </div>
                            <input type="text" class="hidden" id="widget-lostPassword-form-antispam" name="widget-lostPassword-form-antispam" value="" />
                            <?php
                            if(isset($_GET['hash'])){
                                ?>
                                <input type="text" class="hidden" id="widget-lostPassword-form-hash" name="widget-lostPassword-form-hash" value="<?php echo $_GET['hash'] ?>" />
                                <?php
                            }
                            else{
                                ?>
                                <input type="text" class="hidden" id="widget-lostPassword-form-hash" name="widget-lostPassword-form-hash"/>
                                <?php
                            }
                            ?>
                            <button class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;<?=L::sharedBike_send;?></button>
                        </form>
							<script type="text/javascript">
                                jQuery("#widget-lostPassword-form").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                console.log(text);
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
	<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->

	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>

<?php
    if(isset($_GET['hash'])){
	?>
	<script type="text/javascript">
	$('#newPassword').modal('toggle');
	</script>

<?php
	checkHash($GET['HASH']);
}
?>


<?php
    if(isset($_GET['deconnexion']) && $_GET['deconnexion']==true ){
		if ($_SESSION['langue']=='en')
		{
			$message = "You have been disconnected due to inactivity";
		}
		elseif ($_SESSION['langue']=='nl')
		{
			$message = "U bent afgesloten vanwege inactiviteit";
		}
		else
		{
			$message = "Vous avez été déconnecté pour cause d\'inactivité";
		}
	?>
        <script type="text/javascript">
			test="<?php echo $message;?>";
			console.log("deconnexion:"+test);
            $.notify({
                message: '<?php echo $message; ?>'
            }, {
                type: 'danger'
            });
        </script>

<?php
}
?>

</body>

</html>
