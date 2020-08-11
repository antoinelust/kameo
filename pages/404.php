<!DOCTYPE html>
<html lang="fr">
<?php 
	include 'include/head.php';
	header("HTTP/1.0 404 Not Found");
	header_remove("Set-Cookie");
	header_remove("X-Powered-By");
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
					<div class="col-md-6">
						<div class="page-error-404">404</div>
					</div>
					<div class="col-md-6">
						<div class="text-left">
							<h1 class="text-medium">Page non trouvée!</h1>
							<p class="lead">La page que vous cherchez a peut-être été supprimée ou est temporairement innaccessible.</p>
							<div class="seperator m-t-20 m-b-20"></div>
							<p class="lead">Vous cherchez peut-être :</p>
							<ul class="main-menu nav">
								<li><a href="/velo-partage.php">Des vélos partagés</a> </li>
								<li><a href="/velo-personnel.php">Des vélos personnels</a> </li>
								<li><a href="/gestion-flotte.php">Un système de gestion de flotte</a> </li>
								<li><a href="/cash4bike.php">Notre calculateur Cash For Bike</a> </li>
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
