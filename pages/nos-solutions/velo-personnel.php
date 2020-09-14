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
				<a class="button black-light button-3d effect fill-vertical right"  href="velo-partage.php"><span><?=L::personalbike_btn_shared_bikes;?><i class="fa fa-arrow-right"></i></span></a>
					<div class="row">
						<div class="col-md-7">
							<!-- <hr class="space"> -->
							<h1 class="text-dark"><?=L::personalbike_title;?></h1>
							<p class="lead text-light"><i class="fa fa-heart"><?=L::personalbike_text_l1;?>
								<i class="fa fa-clock-o"></i><?=L::personalbike_text_l2;?>
								<i class="fa fa-leaf"></i><?=L::personalbike_text_l3;?>
								<i class="fa fa-user"></i><?=L::personalbike_text_l4;?></p>
							<p class="lead text-dark"><strong><?=L::personalbike_subtitle;?></strong></p>

							<p class="lead text-light"><?=L::personalbike_text2;?></p>

							<a class="button black-light button-3d effect fill-vertical scroll-to"  href="#plus"><span><?=L::personalbike_btn_discover_offer;?><i class="fa fa-arrow-down"></i></span></a>
						</div>
						<div class="col-md-4">
							<img src="images/Solution2.png" class="img-responsive img-rounded" alt="Roue des solutions de KAMEO Bikes">
						</div>
						<hr class="space" id="plus">

					</div>
				</div>
			</section>
			<!-- END: SECTION SOLUTIONS -->

			<!-- SECTION PROCESS PERSONNEL -->
			<!--
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<img src="images/infographie_fr.png" class="img-responsive img-rounded" alt="Infographie - Parcous client chez KAMEO Bikes">
						</div>
						<div class="col-md-4">
						-->
							<!-- <hr class="space"> -->
							<!--
							<h1 class="text-green">COMMENT ON PROCÈDE</h1>
							<p class="text-justify"><strong class="text-green">Vous restez concentré sur votre activité, on se charge de tout!</strong><br>
							Nous réfléchissons à vos besoins réels pour vos collaborateurs. Nous leur proposons des vélos électriques adaptés et nous leur proposons des essais au sein de l'entreprise.<br>
							Après ces essais, ils auront la possibilité de passer commande pour leur propre vélo. Ils pourront également commander des accessoires tels que des sacoches, casques, vêtements, ...<br>
							Nous livrons les vélos sur le site de l'entreprise et donnerons les accès à la plateforme MyKAMEO, depuis laquelle ils retrouveront toutes les informations relatives à leur contrat. Ils auront également la possibilité de nous contacter via cette plateforme en cas de problème.</p>

						</div>
					</div>
				</div>
			</section>
			-->
			<!-- END: SECTION PROCESS PERSONNEL-->

			<!-- SECTION VELO PERSONNE -->
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<!-- <hr class="space"> -->
							<h1 class="text-green"><?=L::personalbike_choice;?></h1>
							<p class="text-justify"><?=L::personalbike_choice_line1;?></p>
							<p class="text-justify"><?=L::personalbike_choice_line2;?></p>
							<p class="text-justify"><?=L::personalbike_choice_line3;?></p>
							<!--
							<div class="col-md-12 text-center">
								<a class="button green button-3d effect fill-vertical" href="achat.php"><span>Le catalogue complet</span></a>
							</div>
							-->

						</div>
						<!--
						<div class="col-md-6">
							<div class="carousel" data-carousel-col="1">
	                        	<img alt="image" src="images/Flotte_BZen.jpg">
	                        	<img alt="image" src="images/Flotte_BZen.jpg">
	                    	</div>
	                    </div>
	                    -->

	                    <div class="col-md-6">
		                    <div id="slider">
								<div id="slider-carousel">
				                	<img alt="image" src="images/bike1.jpg" class="img-responsive img-rounded" >
				                	<img alt="image" src="images/bike3.jpg" class="img-responsive img-rounded" >
				                	<img alt="image" src="images/bike2.jpg" class="img-responsive img-rounded" >
				                	<img alt="image" src="images/bike4.jpg" class="img-responsive img-rounded" >
		                    	</div>
		                    </div>
	                    </div>
					</div>
				</div>
			</section>
			<!-- END: SECTION VELO PERSONNEL -->

			<!-- SECTION MAINTENANCE -->
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-4">
							<div class="col-md-3 visible-sm visible-xs">
								<h1 class="text-green"><?=L::personalbike_maintassur;?></h1>
							</div>
							<img src="images/pvelo.png" class="img-responsive img-rounded" alt="PVELO - l'assurance vélo">
						</div>
						<div class="col-md-8 visible-lg visible-md">
							<!-- <hr class="space"> -->
							<h1 class="text-green"><?=L::personalbike_maintassur;?></h1>
							<p class="text-justify"><?=L::personalbike_maintassur_text1;?></p>
							<p class="text-justify"><?=L::personalbike_maintassur_text2;?></p>
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
							<!-- <hr class="space"> -->
							<h1 class="text-green"><?=L::personalbike_install_infra;?></h1>
							<p class="text-justify"><?=L::personalbike_installinf_text1;?></p>

							<p class="text-justify"><?=L::personalbike_installinf_text2;?></p>

						</div>
						<div class="col-md-6">
							<img src="images/infrastructure.png" class="img-responsive img-rounded" alt="Schéma d'une infrastructure réalisable par KAMEO Bikes">
						</div>
					</div>
				</div>
			</section>
			<!-- END: SECTION INFRASTRUCTURES -->

			<!-- SECTION LOCATION TOUT INCLUS -->
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="text-green"><?=L::personalbike_location_buy;?></h1>
							<p><?=L::personalbike_location_buy_desc;?></p>
							<a class="button green button-3d effect fill-vertical" href="location-tout-inclus.php"><span><i class="fa fa-balance-scale"></i><?=L::personalbike_location_btn_compare;?></span></a>
						</div>
					</div>
				</div>
			</section>
			<!-- END: SECTION LOCATION TOUT INCLUS -->
							<hr class="space">
			<!-- CALL TO ACTION -->
				<div class="jumbotron jumbotron-center jumbotron-fullwidth text-light" style="background: url('images/fond_degrade3.jpg');" data-stellar-background-ratio="0.3">
				  <div class="container">
				    <h3><?=L::personalbike_rdv_title;?></h3>
				    <p><?=L::personalbike_rdv_sub;?><br>
                        <?=L::personalbike_rdv_description;?></p>
				    <div> <a class="button large black-light button-3d effect icon-left" href="contact.php"><span><i class="fa fa-cloud"></i><?=L::personalbike_btn_contact;?></span></a> </div>
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
						<h3><?=L::personalbike_forgot_pass;?></h3>
						<form id="widget-lostPassword-form" action="apis/Kameo/lostPassword.php" role="form" method="post">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="subject"><?=L::personalbike_new_pass;?></label>
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
                            <button class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;<?=L::personalbike_send;?></button>
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
