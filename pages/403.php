<!DOCTYPE html>
<html lang="fr">
<?php 
	include 'include/head.php';
	header("HTTP/1.0 403 Forbidden");
?>
<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include 'include/topbar.php'; ?>
		<?php include 'include/header.php'; ?>
		<!-- 403 PAGE -->
		<section class="m-t-80 p-b-150">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="page-error-404">403</div>
					</div>
					<div class="col-md-6">
						<div class="text-left">
							<h1 class="text-medium">Accès interdit!</h1>
							<p class="lead">Vous n'avez pas le droit d'accéder à cette page.</p>
							<div class="seperator m-t-20 m-b-20"></div>
							<p class="lead">Retour à <a href="index.php" class="text-green"><strong>la page d'accueil</strong></a>.</p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END: 403 PAGE -->
		<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->
	<!-- Theme Base, Components and Settings -->
	<script src="/js/theme-functions.js"></script>
	<!-- Language management -->
	<script type="text/javascript" src="/js/language.js"></script>
</body>
</html>
