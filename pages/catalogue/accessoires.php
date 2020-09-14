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

					<h1 class="text-green"><?= L::accessoires_title; ?></h1>
					<br>
					<p><?= L::accessoires_subtitle1; ?></p>
					<p><?= L::accessoires_subtitle2; ?></p>

					<!--
		</div>
	</div>
	</section>
		-->



					<div class="separator"></div>

					<div class="col-md-4">
						<h4><?= L::accessoires_starterpack_title; ?></h4>
						<br />
						<p><?= L::accessoires_starterpack_subtitle; ?></p>

						<a class="button black-light button-3d effect fill-vertical" data-target="#starterpack" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i><?= L::accessoires_btn_ourSelection; ?></span></a>

					</div>

					<img class="col-md-8" src="images/accessoires/starterpack.jpg" alt="Starter Pack accessoires vélo électrique mobilité">

					<div class="modal fade" id="starterpack" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
									<h4 id="modal-label-3" class="modal-title"><?= L::accessoires_sp_modal_title; ?></h4>
								</div>
								<div class="modal-body">
									<div class="row mb20">
										<div class="col-sm-12">
											<p><?= L::accessoires_sp_modal_subtitle; ?></p>
										</div>
									</div>
									<div class="row">

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_sp_item1_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Abus_Bordo_6000.jpg" alt="Abus Bordo 6000 cadenas vélo">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>
												<ul>
													<?= L::accessoires_sp_item1; ?>
												</ul>
												<br>
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_sp_item1_price; ?></p>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_sp_item2_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Abus_Hyban.jpg" alt="Abus Hyban Casque vélo">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>
												<ul>
													<?= L::accessoires_sp_item2; ?>
												</ul>
												<br>
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_sp_item2_price; ?></p>

											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_sp_item3_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Basil.jpg" alt="Basil B-safe sac à dos vélo">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>

												<ul>
													<?= L::accessoires_sp_item3; ?>
												</ul>

												<br />
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_sp_item3_price; ?></p>

											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_sp_item4_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Gants_FLite.jpg" alt="F-Lite Thermo gants tactile vélo">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>
												<ul>
													<?= L::accessoires_sp_item4; ?>
												</ul>
												<br class="hidden-xs hidden-sm">
												<br>
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_sp_item4_price; ?></p>

											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_sp_item5_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Securite_Wowow.jpg" alt="WOWOW Trouser Clip protège pantalon vélo">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>
												<ul>
													<?= L::accessoires_sp_item5; ?>
												</ul>
												<br class="hidden-xs hidden-sm">
												<br>
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_sp_item5_price; ?></p>

											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_sp_item6_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Securite_Gilet.jpg" alt="A ACT gilet fluo vélo">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>

												<ul>
													<?= L::accessoires_sp_item6; ?>
												</ul>

												<br>
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_sp_item6_price; ?></p>

											</div>
										</div>
									</div>

									<h4 class="text-right"><?= L::accessoires_sp_packprice; ?></h4>

								</div>
								<div class="modal-footer">
									<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?= L::accessoires_modal_order; ?></span></a>
									<a class="button button-3d rounded effect icon-left" data-dismiss="modal"><span><i class="fa fa-close"></i><?= L::accessoires_modal_close; ?></span></a>
								</div>
							</div>
						</div>
					</div>

					<div class="separator"></div>

					<img class="col-md-8  hidden-sm hidden-xs" src="images/accessoires/packoutils.jpg" alt="Pack Outils réparation vélo mobilité">

					<div class="col-md-4">
						<h4><?= L::accessoires_autorepairpack_title; ?></h4>
						<br>
						<p><?= L::accessoires_autorepairpack_subtitle; ?></p>

						<a class="button black-light button-3d effect fill-vertical" data-target="#packoutils" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i><?= L::accessoires_btn_ourSelection; ?></span></a>
					</div>

					<img class="col-md-8 visible-sm visible-xs" src="images/accessoires/packoutils.jpg" alt="">

					<div class="modal fade" id="packoutils" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
									<h4 id="modal-label-3" class="modal-title"><?= L::accessoires_arp_modal_title; ?></h4>
								</div>
								<div class="modal-body">
									<div class="row mb20">
										<div class="col-sm-12">
											<p><?= L::accessoires_arp_modal_subtitle; ?></p>
										</div>
									</div>
									<div class="row">

										<div class="col-sm-4">
											<div class="box-services-a">
												<h4><?= L::accessoires_arp_item1_title; ?></h4>
												<img class="img-responsive" src="images/accessoires/Pompe_Contec.jpg" alt="Pompe à vélo Contec">
												<br class="hidden-xs hidden-sm">
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_arp_item1_price; ?></p>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h4><?= L::accessoires_arp_item2_title; ?></h4>
												<img class="img-responsive" src="images/accessoires/ChambreAAir_Hartje.jpg" alt="Chambre à air HARTJE vélo">
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_arp_item2_price; ?></p>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_arp_item3_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/DemontePneu_Schwalbe.jpg" alt="Démonte pneu vélo SCHWALBE">
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_arp_item3_price; ?></p>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_arp_item4_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Outils_Sigma.jpg" alt="Outils de vélo de poche SIGMA">
												<br class="hidden-xs hidden-sm">
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_arp_item4_price; ?></p>
											</div>
										</div>

									</div>

									<h4 class="text-right"><?= L::accessoires_arp_packprice ?></h4>

								</div>
								<div class="modal-footer">
									<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?= L::accessoires_modal_order; ?></span></a>
									<a class="button button-3d rounded effect icon-left" data-dismiss="modal"><span><i class="fa fa-close"></i><?= L::accessoires_modal_close; ?></span></a>
								</div>
							</div>
						</div>
					</div>


					<div class="separator"></div>


					<div class="col-md-4">
						<h4><?= L::accessoires_cadenas_title; ?></h4>
						<br>
						<p><?= L::accessoires_cadenas_subtitle; ?></p>

						<a class="button black-light button-3d effect fill-vertical" data-target="#cadenas" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i><?= L::accessoires_btn_ourSelection; ?></span></a>

					</div>

					<img class="col-md-8" src="images/accessoires/cadenas.jpg" alt="Cadenas ABUS">

					<div class="modal fade" id="cadenas" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
									<h4 id="modal-label-3" class="modal-title"><?= L::accessoires_cadenas_modal_title; ?></h4>
								</div>
								<div class="modal-body">
									<div class="row mb20">
										<div class="col-sm-12">
											<p><?= L::accessoires_cadenas_modal_subtitle; ?></p>
										</div>
									</div>
									<div class="row">

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_lock_item1_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Abus_Bordo_6000.jpg" alt="Abus Bordo 6000/120 cadenas">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>
												<ul>
													<?= L::accessoires_lock_item1; ?>
												</ul>
												<br>
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_lock_item1_price; ?></p>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_lock_item2_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Abus_Bordo_Granit.jpg" alt="Abus Bordo Granit XPlus 6500 cadenas">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>
												<ul>
													<?= L::accessoires_lock_item2; ?>
												</ul>
												<br>
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_lock_item2_price; ?></p>

											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_lock_item3_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Abus_Bordo_Alarm.jpg" alt="Abus Bordo Alarm 6000A cadenas">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>

												<ul>
													<?= L::accessoires_lock_item3; ?>
												</ul>
												<br>
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_lock_item3_price; ?></p>

											</div>
										</div>



									</div>
								</div>
								<div class="modal-footer">
									<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?= L::accessoires_modal_order; ?></span></a>
									<a class="button button-3d rounded effect icon-left" data-dismiss="modal"><span><i class="fa fa-close"></i><?= L::accessoires_modal_close; ?></span></a>
								</div>
							</div>
						</div>
					</div>

					<div class="separator"></div>

					<img class="col-md-8 hidden-sm hidden-xs" src="images/accessoires/casques.jpg" alt="Casque vélo Abus">

					<div class="col-md-4">
						<h4><?= L::accessoires_casques_title; ?></h4>
						<br>
						<p><?= L::accessoires_casques_subtitle; ?></p>

						<a class="button black-light button-3d effect fill-vertical" data-target="#casques" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i><?= L::accessoires_btn_ourSelection; ?></span></a>
					</div>

					<img class="col-md-8 visible-sm visible-xs" src="images/accessoires/casques.jpg" alt="">

					<div class="modal fade" id="casques" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
									<h4 id="modal-label-3" class="modal-title"><?= L::accessoires_casques_modal_title; ?></h4>
								</div>
								<div class="modal-body">
									<div class="row mb20">
										<div class="col-sm-12">
											<p><?= L::accessoires_casques_modal_subtitle; ?></p>
										</div>
									</div>
									<div class="row">

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_mask_item1_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Abus_Hyban.jpg" alt="Abus Hyban Core casque vélo">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>
												<ul>
													<?= L::accessoires_mask_item1; ?>
												</ul>
												<br>
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_mask_item1_price; ?></p>

											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_mask_item2_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Abus_Pedelec.jpg" alt="Abus Pedelec 2.0 casque vélo">

												<h4><?= L::accessoires_modal_characteristics; ?></h4>

												<ul>
													<?= L::accessoires_mask_item2; ?>
												</ul>

												<br />

												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_mask_item2_price; ?></p>

											</div>
										</div>

										<div class="col-sm-4">
											<div class="box-services-a">
												<h3><?= L::accessoires_mask_item3_title; ?></h3>
												<img class="img-responsive" src="images/accessoires/Abus_Scraper.jpg" alt="Abus Scraper 3.0 casque vélo">
												<h4><?= L::accessoires_modal_characteristics; ?></h4>
												<ul>
													<?= L::accessoires_mask_item3; ?>
												</ul>
												<br>
												<h4><?= L::accessoires_modal_price; ?></h4>
												<p><?= L::accessoires_mask_item3_price; ?></p>

											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?= L::accessoires_modal_order; ?></span></a>
									<a class="button button-3d rounded effect icon-left" data-dismiss="modal"><span><i class="fa fa-close"></i><?= L::accessoires_modal_close; ?></span></a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="separator"></div>

				<div class="col-md-4">
					<h4><?= L::accessoires_sac_sacoches_title; ?></h4>
					<br />
					<p><?= L::accessoires_sac_sacoches_subtitle; ?></p>
					<a class="button black-light button-3d effect fill-vertical" data-target="#sacoches" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i><?= L::accessoires_btn_ourSelection; ?></span></a>

				</div>

				<img class="col-md-8" src="images/accessoires/sacoche.jpg" alt="Sacs et sacoches pour vélo mobilité">

				<div class="modal fade" id="sacoches" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?= L::accessoires_ss_modal_title; ?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
										<p><?= L::accessoires_ss_modal_subtitle; ?></p>
									</div>
								</div>
								<div class="row">

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3><?= L::accessoires_ss_item1_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/Basil.jpg" alt="Basil B-Safe sac à dos vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>

											<ul>
											<?= L::accessoires_ss_item1; ?>
											</ul>

											<br />
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p><?= L::accessoires_ss_item1_price; ?></p>

										</div>
									</div>

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3><?= L::accessoires_ss_item2_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/Contec.jpg" alt="Contec Waterproof sac à dos vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>
											<ul>
											<?= L::accessoires_ss_item2; ?>
											</ul>
											<br>
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p><?= L::accessoires_ss_item2_price; ?></p>

										</div>
									</div>

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3><?= L::accessoires_ss_item3_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/NewLooxs.jpg" alt="New Looxs sac à dos vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>
											<ul>
											<?= L::accessoires_ss_item3; ?>
											</ul>
											<br>
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p><?= L::accessoires_ss_item3_price; ?></p>

										</div>
									</div>

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3><?= L::accessoires_ss_item4_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/Basil_Urban.jpg" alt="Basil Urban Fold sac à dos vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>
											<ul>
											<?= L::accessoires_ss_item4; ?>
											</ul>
											<br>
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p><?= L::accessoires_ss_item4_price; ?></p>

										</div>
									</div>



								</div>
							</div>
							<div class="modal-footer">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?= L::accessoires_modal_order; ?></span></a>
								<a class="button button-3d rounded effect icon-left" data-dismiss="modal"><span><i class="fa fa-close"></i><?= L::accessoires_modal_close; ?></span></a>
							</div>
						</div>
					</div>
				</div>

				<div class="separator"></div>

				<img class="col-md-8 hidden-sm hidden-xs" src="images/accessoires/kids.jpg" alt="Siège enfant vélo porte bagage">

				<div class="col-md-4">
					<h4><?= L::accessoires_siege_enfants_title; ?></h4>
					<br>
					<p><?= L::accessoires_siege_enfants_subtitle; ?></p>

					<a class="button black-light button-3d effect fill-vertical" data-target="#kids" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i><?= L::accessoires_btn_ourSelection; ?></span></a>
				</div>

				<img class="col-md-8 visible-sm visible-xs" src="images/accessoires/kids.jpg" alt="">

				<div class="modal fade" id="kids" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?= L::accessoires_se_modal_title; ?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
										<p><?= L::accessoires_se_modal_subtitle; ?></p>
									</div>
								</div>
								<div class="row">

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3><?= L::accessoires_se_item1_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/Hamax_Kiss.jpg" alt="Hamax Kiss porte enfant, siège vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>
											<ul>
											<?= L::accessoires_se_item1; ?>
											</ul>
											<br>
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p"><?= L::accessoires_se_item1_price; ?></p>

										</div>
									</div>

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3><?= L::accessoires_se_item2_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/Hamax_Zenith.jpg" alt="Hamax Zenith Relax porte enfant, siège vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>

											<ul>
											<?= L::accessoires_se_item2; ?>
											</ul>
											<br>
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p><?= L::accessoires_se_item2_price; ?></p>

										</div>
									</div>



								</div>
							</div>
							<div class="modal-footer">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?= L::accessoires_modal_order; ?></span></a>
								<a class="button button-3d rounded effect icon-left" data-dismiss="modal"><span><i class="fa fa-close"></i><?= L::accessoires_modal_close; ?></span></a>
							</div>
						</div>
					</div>
				</div>

				<div class="separator"></div>

				<div class="separator"></div>

				<div class="col-md-4">
					<h4><?= L::accessoires_textile_gants_title; ?></h4>
					<br />
					<p><?= L::accessoires_textile_gants_subtitle; ?></p>

					<a class="button black-light button-3d effect fill-vertical" data-target="#textile" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i><?= L::accessoires_btn_ourSelection; ?></span></a>

				</div>

				<img class="col-md-8" src="images/accessoires/textiles.jpg" alt="Textiles et gants pour vélo">

				<div class="modal fade" id="textile" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?= L::accessoires_tg_modal_title; ?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
										<p><?= L::accessoires_tg_modal_subtitle; ?></p>
									</div>
								</div>
								<div class="row">

									<div class="col-sm-4">
										<div class="box-services-a">
											<h3><?= L::accessoires_tx_item1_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/Gants_Contec.jpg" alt="Gants Contec Tout Plus vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>

											<ul>
											<?= L::accessoires_tx_item1; ?>
											</ul>
											<br />
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p><?= L::accessoires_tx_item1_price; ?></p>

										</div>
									</div>

									<div class="col-sm-4">
										<div class="box-services-a">
											<h3><?= L::accessoires_tx_item2_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/Gants_FLite.jpg" alt="Gants F-Lite Thermo GPS gants vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>
											<ul>
											<?= L::accessoires_tx_item2; ?>
											</ul>
											<br>
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p><?= L::accessoires_tx_item2_price; ?></p>

										</div>
									</div>

									<div class="col-sm-4">
										<div class="box-services-a">
											<h3><?= L::accessoires_tx_item3_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/Gants_Wowow.jpg" alt="WOWOW Wetland gants vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>
											<ul>
											<?= L::accessoires_tx_item3; ?>
											</ul>
											<br>
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p><?= L::accessoires_tx_item3_price; ?></p>

										</div>
									</div>



								</div>
							</div>
							<div class="modal-footer">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?= L::accessoires_modal_order; ?></span></a>
								<a class="button button-3d rounded effect icon-left" data-dismiss="modal"><span><i class="fa fa-close"></i><?= L::accessoires_modal_close; ?></span></a>
							</div>
						</div>
					</div>
				</div>

				<div class="separator"></div>

				<img class="col-md-8 hidden-sm hidden-xs" src="images/accessoires/securite.jpg" alt="Sécurité et visibilité fluo vélo">

				<div class="col-md-4">
					<h4><?= L::accessoires_security_title; ?></h4>
					<br>
					<p><?= L::accessoires_security_subtitle; ?></p>

					<a class="button black-light button-3d effect fill-vertical" data-target="#securite" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i><?= L::accessoires_btn_ourSelection; ?></span></a>
				</div>

				<img class="col-md-8 visible-sm visible-xs" src="images/accessoires/securite.jpg" alt="Sécurité et visibilité fluo vélo">

				<div class="modal fade" id="securite" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?= L::accessoires_security_modal_title; ?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
										<p><?= L::accessoires_security_modal_subtitle; ?></p>
									</div>
								</div>
								<div class="row">

									<div class="col-sm-6">
										<div class="box-services-a">
										<h3><?= L::accessoires_sec_item1_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/Securite_Wowow.jpg" alt="WOWOW Touser clip protège pantalon vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>
											<ul>
											<?= L::accessoires_sec_item1; ?>
											</ul>
											<br>
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p><?= L::accessoires_sec_item1_price; ?></p>

										</div>
									</div>

									<div class="col-sm-6">
										<div class="box-services-a">
										<h3><?= L::accessoires_sec_item2_title; ?></h3>
											<img class="img-responsive" src="images/accessoires/Securite_Gilet.jpg" alt="4 ACT gilet fluo vélo">
											<h4><?= L::accessoires_modal_characteristics; ?></h4>

											<ul>
											<p><?= L::accessoires_sec_item2; ?></p>
											</ul>

											<br>
											<h4><?= L::accessoires_modal_price; ?></h4>
											<p><?= L::accessoires_sec_item2_price; ?></p>

										</div>
									</div>



								</div>
							</div>
							<div class="modal-footer">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?= L::accessoires_modal_order; ?></span></a>
								<a class="button button-3d rounded effect icon-left" data-dismiss="modal"><span><i class="fa fa-close"></i><?= L::accessoires_modal_close; ?></span></a>
							</div>
						</div>
					</div>
				</div>


			</div>
	</div>
	</div>
	<!--End: Square icons-->

	<?php include 'include/footer.php'; ?>

	</div>
	<!-- END: WRAPPER -->

	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>



</body>

</html>
