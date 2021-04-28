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
		
		<!-- PAGE TITLE -->
		<section id="page-title" class="page-title-parallax text-light background-overlay-dark" style="background: url('images/slider_1_leasing.jpg')" data-stellar-background-ratio="0.5">
			<div class="container">
				<div class="page-title col-md-8">
					<h1>Parallax page title</h1>
					<span>Subtext for page title. Lorem ipsum viverra a!</span>
				</div>
				<div class="breadcrumb col-md-4">
					<ul>
						<li><a href="#"><i class="fa fa-home"></i></a>
						</li>
						<li><a href="#">Home</a>
						</li>
						<li><a href="#">Page title</a>
						</li>
						<li class="active"><a href="#">Page title version</a>
						</li>
					</ul>
				</div>
			</div>
		</section>
		<!-- END: PAGE TITLE -->
		
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
