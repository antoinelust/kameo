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
 <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">


                    <div class="col-md-12">
                        <h1 class=" text-green"><?=L::bons_plans_title;?></h1>
                        </div>
                        <!-- SHOP PRODUCTS -->
								<div class="shop">
									<div class="row">
										
										<!--
										<div class="col-md-4">
											<div class="product">
												<div class="product-image">
													<a href="bp_conway_ets370_f2.php"><img alt="Shop product image!" src="images_bikes/bonsplans/conway_ets370_f.jpg">
													</a>
													<span class="product-sale">Promo</span>
													<span class="product-sale-off">-25%</span>
													<div class="product-overlay">
														<a href="bp_conway_ets370_f2.php">Aperçu</a>
													</div>
												</div>

												<div class="product-description">
													<div class="product-category">Réf. : CW-370f2</div>
													<div class="product-title">
														<h3><a href="bp_conway_ets370_f2.php">Conway</a></h3>
														<p>Ets 370</p>
														<p><strong>Cadre Femme</strong><br>
														Kilomètres parcourus : 590,4 km</p>
													</div>
													<div class="product-price">
														<del>2066€ htva</del><ins class="text-green">1611€ htva</ins>
													</div>
													<p class="right text-right">Location tout inclus:<br> <strong class="text-green">82€</strong> / mois</p>
												</div>

											</div>
										</div>

										<div class="separator visible-xs"></div>
										
										-->

										<!--
										<div class="col-md-4">
											<div class="product">
												<div class="product-image">
													<a href="bp_conway_ets370_f3.php"><img alt="Shop product image!" src="images_bikes/bonsplans/conway_ets370_f.jpg">
													</a>
													<span class="product-sale">Promo</span>
													<span class="product-sale-off">-25%</span>
													<div class="product-overlay">
														<a href="bp_conway_ets370_f3.php">Aperçu</a>
													</div>
												</div>

												<div class="product-description">
													<div class="product-category">Réf. : CW-370f3</div>
													<div class="product-title">
														<h3><a href="bp_conway_ets370_f3.php">Conway</a></h3>
														<p>Ets 370</p>
														<p><strong>Cadre Femme</strong><br>
														Kilomètres parcourus : 584,6 km</p>
													</div>
													<div class="product-price">
														<del>2066€ htva</del><ins class="text-green">1611€ htva</ins>
													</div>
													<p class="right text-right">Location tout inclus:<br> <strong class="text-green">82€</strong> / mois</p>
												</div>

											</div>
										</div>

										<div class="separator visible-xs"></div>
										-->



										<div class="col-md-4">
											<div class="product">
												<div class="product-image">
													<a href="bp_orbea_gain.php"><img alt="Shop product image!" src="images_bikes/bonsplans/orbea_gain_h.jpg">
													</a>
													<span class="product-sale">Promo</span>
													<span class="product-sale-off">-7%</span>
													<div class="product-overlay">
														<a href="bp_orbea_gain.php">Aperçu</a>
													</div>
												</div>

												<div class="product-description">
													<div class="product-category">Réf. : OB-F10</div>
													<div class="product-title">
														<h3><a href="bp_orbea_gain.php">Orbea</a></h3>
														<p>Gain</p>
														<p><strong>Cadre Homme</strong><br>
														Kilomètres parcourus : 0 km</p>
													</div>
													<div class="product-price">
														<del>2133 htva</del><ins class="text-green">1975 htva</ins>
													</div>
													<p class="right text-right">Location tout inclus:<br> <strong class="text-green">92€</strong> / mois</p>
												</div>

											</div>
										</div>

										<div class="separator visible-xs"></div>




										<div class="col-md-4">
											<div class="product">
												<div class="product-image">
													<a href="bp_ahooga_folding.php"><img alt="Shop product image!" src="images_bikes/bonsplans/ahooga_folding.jpg">
													</a>
													<span class="product-sale">Promo</span>
													<span class="product-sale-off">-19%</span>
													<div class="product-overlay">
														<a href="bp_ahooga_folding.php">Aperçu</a>
													</div>
												</div>

												<div class="product-description">
													<div class="product-category">Réf. : AH-165</div>
													<div class="product-title">
														<h3><a href="bp_ahooga_folding.php">Ahooga</a></h3>
														<p>Folding Bike</p>
														<p><strong>Cadre mixte</strong><br>
														Kilomètres parcourus :  inconnu</p>
													</div>
													<div class="product-price">
														<del>1834€ htva</del><ins class="text-green">1487€ htva</ins>
													</div>
													<p class="right text-right">Location tout inclus:<br> <strong class="text-green">77€</strong> / mois</p>
												</div>

											</div>
										</div>

										<!--
										<div class="separator visible-xs"></div>



										<div class="col-md-4">
											<div class="product">
												<div class="product-image">
													<a href="#"><img alt="Shop product image!" src="images_bikes/bonsplans/bzen_amsterdam_f_vendu.jpg">
													</a>
													<span class="product-hot">Promo</span>
													<span class="product-sale-off-red">-46%</span>
												</div>

												<div class="product-description">
													<div class="product-category">Réf. : BZ-174</div>
													<div class="product-title">
														<h3><a href="#">BZEN</a></h3>
														<p>Amsterdam</p>
														<p><strong>Cadre Femme</strong><br>
														Kilomètres parcourus :  -200 km</p>
													</div>
													<div class="product-price">
														<del>2314€ htva</del><ins class="text-green">1239€ htva</ins>
													</div>
													<p class="right text-right">Location tout inclus:<br> <strong class="text-green">74€</strong> / mois</p>
												</div>

											</div>
										</div>
										-->




									</div>
								</div>
				        <!-- END: Portfolio Items -->

                    </div>
                </div>
            </div>
        </section>
        <!-- END: CONTENT -->
	<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->


	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Custom js file -->
	<script src="js/language.js"></script>



</body>

</html>
