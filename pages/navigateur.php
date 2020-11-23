<!DOCTYPE html>
<html lang="fr">
<?php
	include 'include/head.php';
?>
<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include 'include/topbar.php'; ?>
		<?php include 'include/header.php'; ?>
		<!-- 404 PAGE -->
		<section class="m-t-80 p-b-150">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="page-error-404"><i class="fa fa-close"></i></div>
					</div>
					<div class="col-md-8">
						<div class="text-left">
							<h1 class="text-medium">Attention</h1>
							<p class="lead">Votre navigateur ne supporte pas vos actions.</p>
							<div class="seperator m-t-20 m-b-20"></div>
							<p class="lead">Téléchargez plutôt avec :</p>
							<ul class="main-menu nav">
								<li><ins><a href="https://www.mozilla.org/fr/firefox/new/" target="_blank">Firefox</a></ins> </li>
								<li><ins><a href="https://www.google.fr/chrome/?brand=XXVF&gclid=CjwKCAiAtej9BRAvEiwA0UAWXoW36NrZJqM7t1Pp7lZDW-y_vkCqEtcy6cwv9FFrqB7-ZKV_VMH6kRoCHCIQAvD_BwE&gclsrc=aw.ds" target="_blank">Google Chrome</a></ins> </li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END:  404 PAGE -->
		<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->
	<!-- Theme Base, Components and Settings -->
	<script src="/js/theme-functions.js"></script>
	<!-- Language management -->
	<script type="text/javascript" src="/js/language.js"></script>
</body>
</html>
