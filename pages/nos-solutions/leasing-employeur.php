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
				<div class="page-title col-md-6">
					<h1>Leasing de vélos - Employeur</h1>
					<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent at ligula et tortor faucibus pretium. Duis at pulvinar sapien. Suspendisse congue ultrices diam, id lobortis dui elementum et. Fusce vestibulum ipsum at imperdiet laoreet. Proin lacinia tempor enim, sed dapibus ex vehicula sed. Donec et nulla ut diam condimentum elementum.</span>
				</div>
			</div>
		</section>
		<!-- END: PAGE TITLE -->
		
		<div class="container">
			<div class="row">
				<h1 class="text-green">Que cherchez-vous ?</h1>
				<div id="tabs-05c" class="tabs color">
					<ul class="tabs-navigation">
						<li class="active"><a href="#personnels"><i class="fa fa-bicycle"></i>Des vélos société</a> </li>
						<li><a href="#partage"><i class="fa fa-users"></i>Des vélos partagés</a> </li>
						
					</ul>
					<div class="tabs-content">
						<div class="tab-pane" id="personnels">
							<h4>Vélos de société / Cash for bike</h4>
							<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
						</div>
						<div class="tab-pane active" id="partage">
							<h4>Vélos partagés pour vos employés</h4>
							<p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
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
