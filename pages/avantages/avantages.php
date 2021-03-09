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
				<h1 class="text-green"><?=L::avantages_title;?></h1>
				<br>
				<h3><?=L::avantages_employer;?></h3>
				<div class="row">
			      <div class="col-md-6">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-leaf"></i></a> </div>
			          <h5><?=L::avantages_entreprise_title;?></h5>
			          <p><?=L::avantages_entreprise_subtitle;?></p>
			          <br>
			        </div>
			      </div>
			      <div class="col-md-6">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-money"></i></a> </div>
			          <h5><?=L::avantages_incitants_title;?></h5>
			          <p>
									<?=L::avantages_incitants_sub1;?><br>
									<?=L::avantages_incitants_sub2;?>
								</p>
			          <br>
			        </div>
			      </div>
			      <div class="col-md-6">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-diamond"></i></a> </div>
			          <h5><?=L::avantages_bienetre_title;?></h5>
			          <p><?=L::avantages_bienetre_subtitle;?></p>
			        </div>
			      </div>
			       <div class="col-md-6">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-user"></i></a> </div>
			          <h5><?=L::avantages_atout_title;?></h5>
			          <p><?=L::avantages_atout_subtitle;?></p>
			          <br>
			        </div>
			      </div>
			    </div>

			    <h3><?=L::avantages_employee;?></h3>
				<div class="row">
			      <div class="col-md-4">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-leaf"></i></a> </div>
			          <h5><?=L::avantages_environment_title;?></h5>
			          <p><?=L::avantages_environment_subtitle;?></p>
			          <br>
			        </div>
			      </div>
			      <div class="col-md-4">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-money"></i></a> </div>
			          <h5><?=L::avantages_incitants2_title;?></h5>
			          <p><?=L::avantages_incitants2_subtitle;?></p>
			          <br>
			        </div>
			      </div>
			      <div class="col-md-4">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-clock-o"></i></a> </div>
			          <h5><?=L::avantages_gaintime_title;?></h5>
			          <p><?=L::avantages_gaintime_subtitle;?></p>
			        </div>
			      </div>
			       <div class="col-md-6">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-plus"></i></a> </div>
			          <h5><?=L::avantages_combinaison_title;?></h5>
			          <p><?=L::avantages_combinaison_subtitle;?></p>
			          <br>
			        </div>
			      </div>
			      <div class="col-md-6">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-heart"></i></a> </div>
			          <h5><?=L::avantages_lifestyle_title;?></h5>
			          <p><?=L::avantages_lifestyle_subtitle;?></p>
			          <br>
			        </div>
			      </div>
			    </div>

			     <h3 class="text-green"><?=L::avantages_whykameo_title;?></h3>
				<div class="row">

			      <div class="col-md-6">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-cogs"></i></a> </div>
			          <h5><?=L::avantages_quality_title;?></h5>
			          <p><?=L::avantages_quality_subtitle;?></p>
			          <br>
			        </div>
			      </div>

			      <div class="col-md-6">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-sliders"></i></a> </div>
			          <h5><?=L::avantages_offer_service_title;?></h5>
			          <p><?=L::avantages_offer_service_sub1;?><br>
                          <?=L::avantages_offer_service_sub2;?></p>
			          <br>
			        </div>
			      </div>

			       <div class="col-md-6">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-home"></i></a> </div>
			          <h5><?=L::avantages_onestop_title;?></h5>
			          <p><?=L::avantages_onestop_subtitle;?></p>
			          <br>
			        </div>
			      </div>

			      <div class="col-md-6">
			        <div class="icon-box box-type effect medium center color">
			          <div class="icon"> <a href="#"><i class="fa fa-cogs"></i></a> </div>
			          <h5><?=L::avantages_easymanage_title;?></h5>
			          <p><?=L::avantages_easymanage_subtitle;?></p>
			          <br>
			        </div>
			      </div>

			    </div>


		</div>
	</div>
</section>
	<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->

	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>

</body>

</html>
