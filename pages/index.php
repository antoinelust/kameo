<?php
include 'include/head.php';
header_remove("Set-Cookie");
header_remove("X-Powered-By");
?>
<!DOCTYPE html>
<html lang="fr">

<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include 'include/topbar.php'; ?>
		<?php include 'include/header.php'; ?>
		<br>
		<h1 class="text-center text-uppercase text-medium" data-animation="fadeInUp"><?= L::header_title; ?></h1>
		<img src="/images/background_new.webp" class="img-responsive img-rounded" alt="KAMEO Bikes, votre one stop shop pour vos vélos de société">
		<!-- MISSION & VISSION -->
		<section class="box-fancy section-fullwidth text-light no-padding">
			<div class="row">
				<div class="col-md-6 text-center" style="background-color: #3cb395">
					<h2><?= L::employer_title; ?></h2>
					<span><?= L::employer_description; ?></span>
					<a class="button green button-3d effect fill-vertical" style="display: block;" data-target="#employeur" data-toggle="modal" href="#"><span><i class="fa fa-key"></i><?= L::employer_btn_solutions; ?></span></a>
				</div>

				<div class="col-md-6 text-center" style="background-color: #1D9377">
					<h2><?= L::employee_title; ?></h2>
					<span><?= L::employee_description; ?></span>
					<a class="button green button-3d effect fill-vertical" style="display: block;" data-target="#employe" data-toggle="modal" href="#"><span><i class="fa fa-plus"></i><?= L::employee_btn_knowmore; ?></span></a>
				</div>

				<div class="jumbotron jumbotron-center jumbotron-redirection-tb">
					<h3>JE SUIS UN PARTICULIER</h3>
					<h4>Vous cherchez un vélo pour votre compte personnel ?</h4>
					<a class="button black button-3d effect icon-left" href="https://www.tb-velo-electrique.be/"><span><i class="fa fa-bicycle"></i>KAMEO Bikes pour particuliers</span></a>
				</div>

				<!-- CALL TO ACTION -->
				<div class="jumbotron jumbotron-center jumbotron-fullwidthtext-light" style="background: url('images/Fond_Site_Black.jpg');" data-stellar-background-ratio="0.3">
					<div class="container">
						<h3><?= L::calculate_title; ?></h3>
						<p><?= L::calculate_description; ?></p>
						<a class="button large green button-3d effect icon-left" href="cash4bike.php">
							<span><i class="fa fa-calculator"></i><?= L::calculate_btn_calculate; ?></span>
						</a>
					</div>
				</div>
				<!--END: CALL TO ACTION -->
			</div>
		</section>
		<!-- END: MISSION & VISSION -->
		<div class="modal fade" id="employeur" tabindex="-1" role="modal" aria-labelledby="modal-label-employer" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h2 class="modal-title" id="modal-label-employer"><?= L::employer_modal_title; ?></h2>
					</div>
					<div class="modal-body">
						<div class="row text-center">
							<div class="col-md-12">
								<h4 class="text-green"><?= L::employer_modal_sharedbike_title; ?></h4>
								<p><?= L::employer_modal_sharedbike_description; ?></p>
								<a class="button green button-3d effect fill-vertical" style="display: block;" href="velo-partage.php"><span><i class="fa fa-users"></i><?= L::employer_modal_btn_discover; ?></span></a>
							</div>
							<div class="separator"></div>
							<div class="col-md-12">
								<h4 class="text-green"><?= L::employer_modal_personalbike_title; ?></h4>
								<p><?= L::employer_modal_personalbike_description; ?></p>
								<a class="button green button-3d effect fill-vertical" style="display: block;" href="velo-personnel.php"><span><i class="fa fa-user"></i><?= L::employer_modal_btn_knowmore; ?></span></a>
							</div>
							<div class="separator"></div>
							<div class="col-md-12">
								<h4 class="text-red"><?= L::employer_modal_managefleet_title; ?></h4>
								<p><?= L::employer_modal_managefleet_description; ?></p>
								<a class="button red button-3d effect fill-vertical" style="display: block;" href="gestion-flotte.php"><span><i class="fa fa-laptop"></i><?= L::employer_modal_btn_offer; ?></span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="employe" tabindex="-1" role="modal" aria-labelledby="modal-label-employee" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h2 class="modal-title" id="modal-label-employee"><?= L::employee_modal_title; ?></h2>
					</div>
					<div class="modal-body">
						<div class="row text-center">
							<div class="col-md-12">
								<h4 class="text-green"><?= L::employee_modal_bike_title; ?></h4>
								<p><?= L::employee_modal_bike_description; ?></p>
								<a class="button green button-3d effect fill-vertical" style="display: block;" href="achat.php"><span><i class="fa fa-bicycle"></i><?= L::employee_modal_btn_catalogue; ?></span></a>
							</div>
							<div class="separator"></div>
							<div class="col-md-12">
								<h4 class="text-green"><?= L::employee_modal_accessory_title; ?></h4>
								<p><?= L::employee_modal_accessory_description; ?></p>
								<a class="button green button-3d effect fill-vertical" style="display: block;" href="accessoires.php"><span><i class="fa fa-diamond"></i><?= L::employee_modal_btn_accessories; ?></span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<section>
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
						<img src="images/RoueKameo.png" class="img-responsive img-rounded" alt="Roue des services KAMEO Bikes">
					</div>
					<div class="col-md-12 text-center" style="background: url('images/fond_degrade2.webp');" data-stellar-background-ratio="0.6"><br>
						<?= L::choose_kameo_list_avantages; ?>
					</div>
				</div>
			</div>
		</section>

		<!-- SECTION CLIENTS -->
		<section class="p-b-0">
			<div class="container">
				<h1 class="text-green"><?= L::societes_title; ?></h1>
				<ul class="grid grid-4-columns">
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
		<div class="modal fade" id="newPassword" tabindex="-1" role="modal" aria-labelledby="modal-label-newpass" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<h3><?= L::password_forgotten; ?></h3>
								<form id="widget-lostPassword-form" action="../apis/Kameo/lostPassword.php" role="form" method="post">
									<div class="row">
										<div class="form-group col-sm-12">
											<label for="subject"><?= L::password_new; ?></label>
											<input type="password" name="widget-lostPassword-form-new-password" class="form-control required" autocomplete="new-password">
										</div>
									</div>
									<input type="text" class="hidden" id="widget-lostPassword-form-antispam" name="widget-lostPassword-form-antispam" value="" />
									<?php
									if (isset($_GET['hash']))
										echo '<input type="text" class="hidden" id="widget-lostPassword-form-hash" name="widget-lostPassword-form-hash" value="' . $_GET['hash'] . '"/>';
									else
										echo '<input type="text" class="hidden" id="widget-lostPassword-form-hash" name="widget-lostPassword-form-hash"/>';
									?>
									<button class="button effect fill" type="submit"><i class="fa fa-paper-plane"></i><?= L::password_send; ?></button>
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
	<script src="/js/theme-functions.js"></script>

	<!-- Language management -->
	<script type="text/javascript" src="/js/language.js"></script>

	<?php
	if (isset($_GET['hash'])) {
		echo `<script type="text/javascript">
      $('#newPassword').modal('toggle');
    </script>`;
		checkHash($GET['HASH']);
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