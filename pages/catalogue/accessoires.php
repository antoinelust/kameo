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
		<!--Square icons-->
   <section>
	
	<div class="container">
		<div class="row">
								
				<h1 class="text-green"><?=L::accessoires_title;?></h1>
				<br>
				<p><?=L::accessoires_subtitle1;?></p>
				<p><?=L::accessoires_subtitle2;?></p>
			
		<!--	
		</div>
	</div>
	</section>
		-->
					
	
	
	<div class="separator"></div>
				
					<div class="col-md-4">
					<h4><?=L::accessoires_starterpack_title;?></h4>
					<br />
					<p><?=L::accessoires_starterpack_subtitle;?></p>
					
					<a class="button black-light button-3d effect fill-vertical fr" data-target="#starterpack" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Notre sélection</span></a>
					<a class="button black-light button-3d effect fill-vertical en" data-target="#starterpack" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Our selection</span></a>
					<a class="button black-light button-3d effect fill-vertical nl" data-target="#starterpack" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Onze selectie</span></a>
					
				</div>
				
				<img class="col-md-8" src="images/accessoires/starterpack.jpg" alt="Starter Pack accessoires vélo électrique mobilité">
				
				<div class="modal fade" id="starterpack" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?=L::accessoires_sp_modal_title;?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
                                       <p><?=L::accessoires_sp_modal_subtitle;?></p>
									</div>
								</div>
								<div class="row">
								
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>ABUS Bordo</h3>
											<img class="img-responsive" src="images/accessoires/Abus_Bordo_6000.jpg" alt="Abus Bordo 6000 cadenas vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Niveau de sécurité 10 = convient pour l'assurance vol AEDES</li>
                                                <li>120 cm de long</li>
                                                <li>Gainage sur le cadenas pour ne pas abimer le cadre</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Level of security 10 = compatible for insurance AEDES</li>
                                                <li>120cm long</li>
                                                <li>Sheathing on the padlock to avoid frame damages</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Niveau van beveiliging 10 = compatibel voor verzekering AEDES</li>
                                                <li>120cm lang</li>
                                                <li>Omhulsel op het hangslot om framebeschadigingen te voorkomen</li>
                                            </ul>
											<br>	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">70€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">70€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">70€</strong> (Ex. BTW)</p>
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>ABUS Hyban core</h3>
											<img class="img-responsive" src="images/accessoires/Abus_Hyban.jpg" alt="Abus Hyban Casque vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Led arrière rouge pour une bonne visibilité</li>
                                                <li>Différentes tailles</li>
                                                <li>Couleurs: Noir, bleu, vert, gris, mauve</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Led light at the back for an optimal visibility</li>
                                                <li>Different sizes</li>
                                                <li>Colors: Black, blue, green, grey, purple</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Led-licht aan de achterkant voor een optimale zichtbaarheid</li>
                                                <li>Verschillende maten</li>
                                                <li>Kleuren: zwart, blauw, groen, grijs, paars</li>
                                            </ul>
											<br>	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">53€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">53€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">53€</strong> (Ex. BTW)</p>
                                            
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>BASIL B-safe</h3>
											<img class="img-responsive" src="images/accessoires/Basil.jpg" alt="Basil B-safe sac à dos vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            
                                            <ul class="fr">
                                                <li>18L</li>
                                                <li>Rangement ordinateur 15" </li>
                                                <li>Sac à dos avec accroche pour porte bagage</li>
                                                <li>Waterproof</li>
                                            </ul>
                                            <ul class="en">
                                                <li>18L</li>
                                                <li>Can contain a 15" laptop</li>
                                                <li>Backpack with luggage hook</li>
                                                <li>Waterproof</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>18L</li>
                                                <li>Kan een 15 "-laptop bevatten</li>
                                                <li>Rugzak met bagagehaak</li>
                                                <li>Waterproof</li>
                                            </ul>
                                            
											<br />	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">99€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">99€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">99€</strong> (Ex. BTW)</p>
                                            
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>F-LITE Thermo GPS</h3>
											<img class="img-responsive" src="images/accessoires/Gants_FLite.jpg" alt="F-Lite Thermo gants tactile vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Fins et confortables</li>
                                                <li>S - XL</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Fine and comfortable</li>
                                                <li>S - XL</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Fijn en comfortabel</li>
                                                <li>S - XL</li>
                                            </ul>
											<br class="hidden-xs hidden-sm">
											<br>	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">15€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">15€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">15€</strong> (Ex. BTW)</p>
                                            
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>WOWOW Trouser Clip</h3>
											<img class="img-responsive" src="images/accessoires/Securite_Wowow.jpg" alt="WOWOW Trouser Clip protège pantalon vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Réfléchissant</li>
                                                <li>Protège votre pantalon</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Reflective</li>
                                                <li>Protect your pants</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Reflecterende</li>
                                                <li>Bescherm je broek</li>
                                            </ul>
											<br class="hidden-xs hidden-sm">
											<br>	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">6€</strong> (HTVA) la paire</p>
								            <p class="en"><strong class="text-green bold">6€</strong> (Ex. VAT) the pair</p>
								            <p class="nl"><strong class="text-green bold">6€</strong> (Ex. BTW) het paar</p>
                                            
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>4 ACT</h3>
											<img class="img-responsive" src="images/accessoires/Securite_Gilet.jpg" alt="A ACT gilet fluo vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            
                                            <ul class="fr">
                                            	<li>Réfléchissant</li>
                                            	<li>Compacte</li>
                                            	<li>Moulant</li>
                                            </ul>
                                            <ul class="en">
                                            	<li>Reflective</li>
                                            	<li>Compact</li>
                                            	<li>Skinny</li>
                                            </ul>
                                            <ul class="nl">
                                            	<li>Reflecterende</li>
                                            	<li>Compact</li>
                                            	<li>Broodmager</li>
                                            </ul>
                                            
											<br>	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">12€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">12€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">12€</strong> (Ex. BTW)</p>
                                            
										</div>
									</div>
								</div>
								
								<h4 class="fr text-right">Prix total du pack: <strong class="text-green">255€</strong> (TVAC)</h4>
								<h4 class="en text-right">Total price of the pack: <strong class="text-green">255€</strong> (VAT included)</h4>
								<h4 class="nl text-right">Totale prijs van het pakket: <strong class="text-green">255€</strong> (Incl. BTW)</h4>
								
							</div>
							<div class="modal-footer">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?=L::accessoires_modal_order;?></span></a>
								<a class="button button-3d rounded effect icon-left"  data-dismiss="modal"><span><i class="fa fa-close"></i><?=L::accessoires_modal_close;?></span></a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="separator"></div>
				
				<img class="col-md-8  hidden-sm hidden-xs" src="images/accessoires/packoutils.jpg" alt="Pack Outils réparation vélo mobilité">
				
				<div class="col-md-4">
					<h4><?=L::accessoires_autorepairpack_title;?></h4>
					<br>
					<p><?=L::accessoires_autorepairpack_subtitle;?></p>
					
					<a class="button black-light button-3d effect fill-vertical fr" data-target="#packoutils" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Notre sélection</span></a>
					<a class="button black-light button-3d effect fill-vertical en" data-target="#packoutils" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Our selection</span></a>
					<a class="button black-light button-3d effect fill-vertical nl" data-target="#packoutils" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Onze selectie</span></a>
				</div>
				
				<img class="col-md-8 visible-sm visible-xs" src="images/accessoires/packoutils.jpg" alt="">
				
				<div class="modal fade" id="packoutils" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?=L::accessoires_arp_modal_title;?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
                                        <p><?=L::accessoires_arp_modal_subtitle;?></p>
									</div>
								</div>
								<div class="row">
								
									<div class="col-sm-4">
										<div class="box-services-a">
											<h4 class="fr">Pompe CONTEC</h4>
											<h4 class="en">CONTEC Pump</h4>
											<h4 class="nl">CONTEC pomp</h4>
											<img class="img-responsive" src="images/accessoires/Pompe_Contec.jpg" alt="Pompe à vélo Contec">
											<br class="hidden-xs hidden-sm">	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">24€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">24€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">24€</strong> (Ex. BTW)</p>
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h4 class="fr">Chambre a air HARTJE (3 unités)</h4>
											<h4 class="en">Hartje Air Chamber (3 units)</h4>
											<h4 class="nl">Hartje Air Chamber (3 eenheden)</h4>
											<img class="img-responsive" src="images/accessoires/ChambreAAir_Hartje.jpg" alt="Chambre à air HARTJE vélo">	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">14€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">14€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">14€</strong> (Ex. BTW)</p>
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3 class="fr">SCHWALBE démonte pneus</h3>
											<h3 class="en">Schwalbe tire changer</h3>
											<h3 class="nl">Schwalbe bandenwisselaar</h3>
											<img class="img-responsive" src="images/accessoires/DemontePneu_Schwalbe.jpg" alt="Démonte pneu vélo SCHWALBE">
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">3€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">3€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">3€</strong> (Ex. BTW)</p>
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3 class="fr">SIGMA Outils de poche</h3>
											<h3 class="en">SIGMA Pocket tools</h3>
											<h3 class="nl">SIGMA Handgereedschap</h3>
											<img class="img-responsive" src="images/accessoires/Outils_Sigma.jpg" alt="Outils de vélo de poche SIGMA">
											<br class="hidden-xs hidden-sm">
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">19€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">19€</strong> (Ex VAT)</p>
								            <p class="nl"><strong class="text-green bold">19€</strong> (Ex. BTW)</p>
										</div>
									</div>
									
								</div>
								
								<h4 class="fr text-right">Prix total du pack: <strong class="text-green">60€</strong> (HTVA)</h4>
								<h4 class="en text-right">Total price of the pack: <strong class="text-green">60€</strong> (Ex. VAT)</h4>
								<h4 class="nl text-right">Totale prijs van het pakket: <strong class="text-green">60€</strong> (Ex. BTW)</h4>
								
							</div>
							<div class="modal-footer">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?=L::accessoires_modal_order;?></span></a>
								<a class="button button-3d rounded effect icon-left"  data-dismiss="modal"><span><i class="fa fa-close"></i><?=L::accessoires_modal_close;?></span></a>
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="separator"></div>
				
	
				<div class="col-md-4">
					<h4><?=L::accessoires_cadenas_title;?></h4>
					<br>
					<p><?=L::accessoires_cadenas_subtitle;?></p>
					
					<a class="button black-light button-3d effect fill-vertical fr" data-target="#cadenas" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Notre sélection</span></a>
					<a class="button black-light button-3d effect fill-vertical en" data-target="#cadenas" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Our selection</span></a>
					<a class="button black-light button-3d effect fill-vertical nl" data-target="#cadenas" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Onze selectie</span></a>
					
				</div>
				
				<img class="col-md-8" src="images/accessoires/cadenas.jpg" alt="Cadenas ABUS">
				
				<div class="modal fade" id="cadenas" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?=L::accessoires_cadenas_modal_title;?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
                                        <p><?=L::accessoires_cadenas_modal_subtitle;?></p>
									</div>
								</div>
								<div class="row">
								
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>ABUS Bordo <br>6000/120</h3>
											<img class="img-responsive" src="images/accessoires/Abus_Bordo_6000.jpg" alt="Abus Bordo 6000/120 cadenas">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Niveau de sécurité 10 = convient pour l'assurance vol AEDES</li>
                                                <li>120 cm de long</li>
                                                <li>Gainage sur le cadenas pour ne pas abimer le cadre</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Level of security 10 = compatible for insurance AEDES</li>
                                                <li>120cm long</li>
                                                <li>Sheathing on the padlock to avoid frame damages</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Niveau van beveiliging 10 = compatibel voor verzekering AEDES</li>
                                                <li>120cm lang</li>
                                                <li>Omhulsel op het hangslot om framebeschadigingen te voorkomen</li>
                                            </ul>
											<br>	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">81€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">81€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">81€</strong> (Ex. BTW)</p>
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>ABUS Bordo GRANIT XPLUS 6500</h3>
											<img class="img-responsive" src="images/accessoires/Abus_Bordo_Granit.jpg" alt="Abus Bordo Granit XPlus 6500 cadenas">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Niveau de sécurité 15, le plus haut de ABUS</li>
                                                <li>110 cm de long</li>
                                                <li>Gainage sur le cadenas pour ne pas abimer le cadre</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Level of security 15, the highest for ABUS</li>
                                                <li>110cm long</li>
                                                <li>Sheathing on the padlock to avoid frame damages</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Niveau van beveiliging 15, het hoogste voor ABUS</li>
                                                <li>110cm lang</li>
                                                <li>Omhulsel op het hangslot om framebeschadigingen te voorkomen</li>
                                            </ul>
											<br>	
											<h4><?=L::accessoires_modal_price;?></h4>
                                            <p class="fr"><strong class="text-green bold">119€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">119€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">119€</strong> (Ex. BTW)</p>

										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>ABUS Bordo Alarm 6000A</h3>
											<img class="img-responsive" src="images/accessoires/Abus_Bordo_Alarm.jpg" alt="Abus Bordo Alarm 6000A cadenas">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            
                                            <ul class="fr">
                                                <li>Niveau de sécurité 10</li>
                                                <li>120 cm de long</li>
                                                <li>Gainage sur le cadenas pour ne pas abimer le cadre</li>
                                                <li>Alarme sonore de 100 dB </li>
                                            </ul>
                                            <ul class="en">
                                                <li>Level of security 10</li>
                                                <li>120cm long</li>
                                                <li>Sheathing on the padlock to avoid frame damages</li>
                                                <li>Sound alarm of 100 dB </li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Niveau de sécurité 10 (hors alarme qui augmente la sécurité)</li>
                                                <li>120 cm de long</li>
                                                <li>Omhulsel op het hangslot om framebeschadigingen te voorkomen</li>
                                                <li>Geluidsalarm van 100 dB </li>
                                            </ul>
											<br>	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">131€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">131€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">131€</strong> (Ex. BTW)</p>
                                            
										</div>
									</div>
									
									
									
								</div>
							</div>
							<div class="modal-footer">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?=L::accessoires_modal_order;?></span></a>
								<a class="button button-3d rounded effect icon-left"  data-dismiss="modal"><span><i class="fa fa-close"></i><?=L::accessoires_modal_close;?></span></a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="separator"></div>
				
				<img class="col-md-8 hidden-sm hidden-xs" src="images/accessoires/casques.jpg" alt="Casque vélo Abus">
				
				<div class="col-md-4">
					<h4><?=L::accessoires_casques_title;?></h4>
					<br>
					<p><?=L::accessoires_casques_subtitle;?></p>
					
					<a class="button black-light button-3d effect fill-vertical fr" data-target="#casques" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Notre sélection</span></a>
					<a class="button black-light button-3d effect fill-vertical en" data-target="#casques" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Our selection</span></a>
					<a class="button black-light button-3d effect fill-vertical nl" data-target="#casques" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Onze selectie</span></a>
				</div>
				
				<img class="col-md-8 visible-sm visible-xs" src="images/accessoires/casques.jpg" alt="">
				
				<div class="modal fade" id="casques" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?=L::accessoires_casques_modal_title;?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
                                        <p><?=L::accessoires_casques_modal_subtitle;?></p>
									</div>
								</div>
								<div class="row">
								
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>ABUS Hyban core</h3>
											<img class="img-responsive" src="images/accessoires/Abus_Hyban.jpg" alt="Abus Hyban Core casque vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Led arrière rouge pour une bonne visibilité</li>
                                                <li>Différentes tailles</li>
                                                <li>Couleurs: Noir, bleu, vert, gris, mauve</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Led light at the back for an optimal visibility</li>
                                                <li>Different sizes</li>
                                                <li>Colors: Black, blue, green, grey, purple</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Led-licht aan de achterkant voor een optimale zichtbaarheid</li>
                                                <li>Verschillende maten</li>
                                                <li>Kleuren: zwart, blauw, groen, grijs, paars</li>
                                            </ul>
											<br>	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">53€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">53€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">53€</strong> (Ex. BTW)</p>
                                            
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>ABUS Pedelec 2.0</h3>
											<img class="img-responsive" src="images/accessoires/Abus_Pedelec.jpg" alt="Abus Pedelec 2.0 casque vélo">
                                            
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            
                                            <ul class="fr">
                                                <li>Led arrière rouge pour une bonne visibilité + bande réfléchissante</li>
                                                <li>Haute sécurité</li>
                                                <li>Capuche imperméable intégrée</li>
                                                <li>Différentes tailles</li>
                                                <li>Couleurs: Noir, bleu, gris, jaune, blanc</li>
                                            </ul>
                                            <ul class="en">
                                                <li>LED lights at the back for good visibility + reflective tape</li>
                                                <li>High security</li>
                                                <li>Integrated rain hood</li>
                                                <li>Different sizes</li>
                                                <li>Colors: Black, blue, grey, yellow, white</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>LED-verlichting aan de achterkant voor goede zichtbaarheid + reflecterende tape</li>
                                                <li>Hoge beveiliging</li>
                                                <li>Geïntegreerde regenkap</li>
                                                <li>Verschillende maten</li>
                                                <li>Kleuren: zwart, blauw, grijs, geel, wit</li>
                                            </ul>
                                            
											<br />
                                            
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">114€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">114€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">114€</strong> (Ex. BTW)</p>
                                            
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>ABUS Scraper 3.0</h3>
											<img class="img-responsive" src="images/accessoires/Abus_Scraper.jpg" alt="Abus Scraper 3.0 casque vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Look indémodable</li>
                                                <li>Kit hiver amovible</li>
                                                <li>Différentes tailles</li>
                                                <li>Couleurs: Noir mat, noir brillant, bleu, blanc</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Timeless look</li>
                                                <li>Removable winter kit</li>
                                                <li>Different sizes</li>
                                                <li>Colors: Matt Black, Glossy Black, Blue, White</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Tijdloze uitstraling</li>
                                                <li>Afneembare winterkit</li>
                                                <li>Verschillende maten</li>
                                                <li>Kleuren: mat zwart, glanzend zwart, blauw, wit</li>
                                            </ul>
											<br>	
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">73€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">73€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">73€</strong> (Ex. BTW)</p>
                                            
										</div>
									</div>



								</div>
							</div>
                            <div class="modal-footer">
                                <a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?=L::accessoires_modal_order;?></span></a>
                                <a class="button button-3d rounded effect icon-left"  data-dismiss="modal"><span><i class="fa fa-close"></i><?=L::accessoires_modal_close;?></span></a>
                            </div>
							</div>
						</div>
					</div>
				</div>

				<div class="separator"></div>

					<div class="col-md-4">
					<h4><?=L::accessoires_sac_sacoches_title;?></h4>
					<br />
					<p><?=L::accessoires_sac_sacoches_subtitle;?></p>
					<a class="button black-light button-3d effect fill-vertical fr" data-target="#sacoches" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Notre sélection</span></a>
					<a class="button black-light button-3d effect fill-vertical en" data-target="#sacoches" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Our selection</span></a>
					<a class="button black-light button-3d effect fill-vertical nl" data-target="#sacoches" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Onze selectie</span></a>

				</div>

				<img class="col-md-8" src="images/accessoires/sacoche.jpg" alt="Sacs et sacoches pour vélo mobilité">

				<div class="modal fade" id="sacoches" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?=L::accessoires_ss_modal_title;?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
                                        <p><?=L::accessoires_ss_modal_subtitle;?></p>
									</div>
								</div>
								<div class="row">

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3>BASIL B-safe Backpack</h3>
											<img class="img-responsive" src="images/accessoires/Basil.jpg" alt="Basil B-Safe sac à dos vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>

                                            <ul class="fr">
                                                <li>18L</li>
                                                <li>Rangement ordinateur 15" </li>
                                                <li>Sac à dos avec accroche pour porte bagage</li>
                                                <li>Waterproof</li>
                                            </ul>
                                            <ul class="en">
                                                <li>18L</li>
                                                <li>Can contain a 15" laptop</li>
                                                <li>Backpack with luggage hook</li>
                                                <li>Waterproof</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>18L</li>
                                                <li>Kan een 15 "-laptop bevatten</li>
                                                <li>Rugzak met bagagehaak</li>
                                                <li>Waterproof</li>
                                            </ul>

											<br />
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">90€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">90€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">90€</strong> (Ex. BTW)</p>

										</div>
									</div>

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3>CONTEC Waterproof 24</h3>
											<img class="img-responsive" src="images/accessoires/Contec.jpg" alt="Contec Waterproof sac à dos vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>24L</li>
                                                <li>Disponible en 3 couleurs</li>
                                                <li>Sac à dos avec accroche pour porte bagage</li>
                                                <li>Waterproof</li>
                                            </ul>
                                            <ul class="en">
                                                <li>24L</li>
                                                <li>Available in 3 colors</li>
                                                <li>Backpack with luggage hook</li>
                                                <li>Waterproof</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>24L</li>
                                                <li>Verkrijgbaar in 3 kleuren</li>
                                                <li>Rugzak met bagagehaak</li>
                                                <li>Waterproof</li>
                                            </ul>
											<br>
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">41€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">41€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">41€</strong> (Ex. BTW)</p>

										</div>
									</div>

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3>NEW LOOXS</h3>
											<img class="img-responsive" src="images/accessoires/NewLooxs.jpg" alt="New Looxs sac à dos vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>16,5L</li>
                                                <li>Rangement ordinateur 15" </li>
                                                <li>Sac à bandoulière avec accroche pour porte bagage</li>
                                            </ul>
                                            <ul class="en">
                                                <li>16,5L</li>
                                                <li>Can contain a 15" laptop</li>
                                                <li>Shoulder bag with hook for luggage rack</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>16,5L</li>
                                                <li>Kan een 15 "-laptop bevatten</li>
                                                <li>Schoudertas met haak voor bagagerek</li>
                                            </ul>
											<br>
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">54€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">54€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">54€</strong> (Ex. BTW)</p>

										</div>
									</div>

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3>BASIL Urban Fold</h3>
											<img class="img-responsive" src="images/accessoires/Basil_Urban.jpg" alt="Basil Urban Fold sac à dos vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>25L</li>
                                                <li>Sac à bandoulière avec accroche pour porte bagage</li>
                                            </ul>
                                            <ul class="en">
                                                <li>25L</li>
                                                <li>Shoulder bag with hook for luggage rack</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>25L</li>
                                                <li>Schoudertas met haak voor bagagerek</li>
                                            </ul>
											<br>
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">57€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">57€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">57€</strong> (Ex. BTW)</p>

										</div>
									</div>



								</div>
							</div>
							<div class="modal-footer">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?=L::accessoires_modal_order;?></span></a>
								<a class="button button-3d rounded effect icon-left"  data-dismiss="modal"><span><i class="fa fa-close"></i><?=L::accessoires_modal_close;?></span></a>
							</div>
						</div>
					</div>
				</div>

				<div class="separator"></div>

				<img class="col-md-8 hidden-sm hidden-xs" src="images/accessoires/kids.jpg" alt="Siège enfant vélo porte bagage">

				<div class="col-md-4">
					<h4><?=L::accessoires_siege_enfants_title;?></h4>
					<br>
					<p><?=L::accessoires_siege_enfants_subtitle;?></p>

					<a class="button black-light button-3d effect fill-vertical fr" data-target="#kids" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Notre sélection</span></a>
					<a class="button black-light button-3d effect fill-vertical en" data-target="#kids" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Our selection</span></a>
					<a class="button black-light button-3d effect fill-vertical nl" data-target="#kids" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Onze selectie</span></a>
				</div>

				<img class="col-md-8 visible-sm visible-xs" src="images/accessoires/kids.jpg" alt="">

				<div class="modal fade" id="kids" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?=L::accessoires_se_modal_title;?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
                                        <p><?=L::accessoires_se_modal_subtitle;?></p>
									</div>
								</div>
								<div class="row">

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3>HAMAX Kiss</h3>
											<img class="img-responsive" src="images/accessoires/Hamax_Kiss.jpg" alt="Hamax Kiss porte enfant, siège vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Parfaite sécurité et confort de l'enfant</li>
                                                <li>Easy clips sur la fixation HAMAX</li>
                                                <li>Adaptable sur la plupart des vélos</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Maximum security and comfort for the child</li>
                                                <li>Easy clips</li>
                                                <li>Compatible with most of bikes</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Maximale veiligheid en comfort voor het kind</li>
                                                <li>Easy clips</li>
                                                <li>Compatibel met de meeste fietsen</li>
                                            </ul>
											<br>
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">57€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">57€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">57€</strong> (Ex. BTW)</p>

										</div>
									</div>

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3>HAMAX Zenith Relax</h3>
											<img class="img-responsive" src="images/accessoires/Hamax_Zenith.jpg" alt="Hamax Zenith Relax porte enfant, siège vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>

                                            <ul class="fr">
                                                <li>Possibilité d'incliner le siège pour laisser dormir l'enfant</li>
                                                <li>Parfaite sécurité et confort de l'enfant</li>
                                                <li>Easy clips sur la fixation HAMAX</li>
                                                <li>Adaptable sur la plupart des vélos</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Possible to tilt the seat, allowing the child to sleep</li>
                                                <li>Maximum security and comfort for the child</li>
                                                <li>Easy clips</li>
                                                <li>Compatible with most of bikes</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Mogelijk om de stoel te kantelen, zodat het kind kan slapen</li>
                                                <li>Maximale veiligheid en comfort voor het kind</li>
                                                <li>Easy clips</li>
                                                <li>Compatibel met de meeste fietsen</li>
                                            </ul>

											<br>
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">98€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">98€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">98€</strong> (Ex. BTW)</p>

										</div>
									</div>



								</div>
							</div>
							<div class="modal-footer">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?=L::accessoires_modal_order;?></span></a>
								<a class="button button-3d rounded effect icon-left"  data-dismiss="modal"><span><i class="fa fa-close"></i><?=L::accessoires_modal_close;?></span></a>
							</div>
						</div>
					</div>
				</div>

				<div class="separator"></div>

				<div class="separator"></div>

					<div class="col-md-4">
					<h4><?=L::accessoires_textile_gants_title;?></h4>
					<br />
					<p><?=L::accessoires_textile_gants_subtitle;?></p>

					<a class="button black-light button-3d effect fill-vertical fr" data-target="#textile" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Notre sélection</span></a>
					<a class="button black-light button-3d effect fill-vertical en" data-target="#textile" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Our selection</span></a>
					<a class="button black-light button-3d effect fill-vertical nl" data-target="#textile" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Onze selectie</span></a>

				</div>

				<img class="col-md-8" src="images/accessoires/textiles.jpg" alt="Textiles et gants pour vélo">

				<div class="modal fade" id="textile" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?=L::accessoires_tg_modal_title;?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
                                        <p><?=L::accessoires_tg_modal_subtitle;?></p>
									</div>
								</div>
								<div class="row">

									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>CONTEC Tour Plus</h3>
											<img class="img-responsive" src="images/accessoires/Gants_Contec.jpg" alt="Gants Contec Tout Plus vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>

                                            <ul class="fr">
                                                <li>Coupe Vent</li>
                                                <li>Parfait pour l'hiver</li>
                                                <li>S - XXL</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Windbreaker</li>
                                                <li>Perfect for winter</li>
                                                <li>S - XXL</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Windjak</li>
                                                <li>Perfect voor de winter</li>
                                                <li>S - XXL</li>
                                            </ul>
											<br/>
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">32€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">32€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">32€</strong> (Ex. BTW)</p>

										</div>
									</div>

									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>F-LITE Thermo GPS</h3>
											<img class="img-responsive" src="images/accessoires/Gants_FLite.jpg" alt="Gants F-Lite Thermo GPS gants vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Fins et confortables</li>
                                                <li>S - XL</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Fine and comfortable</li>
                                                <li>S - XL</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Fijn en comfortabel</li>
                                                <li>S - XL</li>
                                            </ul>
											<br>
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">15€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">15€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">15€</strong> (Ex. BTW)</p>

										</div>
									</div>

									<div class="col-sm-4">
										<div class="box-services-a">
											<h3>WOWOW Wetland</h3>
											<img class="img-responsive" src="images/accessoires/Gants_Wowow.jpg" alt="WOWOW Wetland gants vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                           <ul class="fr">
                                           		<li>Réfléchissants et fluo</li>
                                                <li>Coupe Vent</li>
                                                <li>Parfait pour l'hiver</li>
                                                <li>S - XL</li>
                                            </ul>
                                            <ul class="en">
                                            	<li>Reflective and fluo</li>
                                                <li>Windbreaker</li>
                                                <li>Perfect for winter</li>
                                                <li>S - XL</li>
                                            </ul>
                                            <ul class="nl">
                                            	<li>Reflecterend en fluo</li>
                                                <li>Windjak</li>
                                                <li>Perfect voor de winter</li>
                                                <li>S - XL</li>
                                            </ul>
											<br>
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">34€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">34€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">34€</strong> (Ex. BTW)</p>

										</div>
									</div>



								</div>
							</div>
							<div class="modal-footer fr">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?=L::accessoires_modal_order;?></span></a>
								<a class="button button-3d rounded effect icon-left"  data-dismiss="modal"><span><i class="fa fa-close"></i><?=L::accessoires_modal_close;?></span></a>
							</div>
						</div>
					</div>
				</div>

				<div class="separator"></div>

				<img class="col-md-8 hidden-sm hidden-xs" src="images/accessoires/securite.jpg" alt="Sécurité et visibilité fluo vélo">

				<div class="col-md-4">
					<h4><?=L::accessoires_security_title;?></h4>
					<br>
					<p><?=L::accessoires_security_subtitle;?></p>

					<a class="button black-light button-3d effect fill-vertical fr" data-target="#securite" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Notre sélection</span></a>
					<a class="button black-light button-3d effect fill-vertical en" data-target="#securite" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Our selection</span></a>
					<a class="button black-light button-3d effect fill-vertical nl" data-target="#securite" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i> Onze selectie</span></a>
				</div>

				<img class="col-md-8 visible-sm visible-xs" src="images/accessoires/securite.jpg" alt="Sécurité et visibilité fluo vélo">

				<div class="modal fade" id="securite" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
								<h4 id="modal-label-3" class="modal-title"><?=L::accessoires_security_modal_title;?></h4>
							</div>
							<div class="modal-body">
								<div class="row mb20">
									<div class="col-sm-12">
                                        <p><?=L::accessoires_security_modal_subtitle;?></p>
									</div>
								</div>
								<div class="row">

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3>WOWOW Trouser Clip</h3>
											<img class="img-responsive" src="images/accessoires/Securite_Wowow.jpg" alt="WOWOW Touser clip protège pantalon vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>
                                            <ul class="fr">
                                                <li>Réfléchissant</li>
                                                <li>Protège votre pantalon</li>
                                            </ul>
                                            <ul class="en">
                                                <li>Reflective</li>
                                                <li>Protect your pants</li>
                                            </ul>
                                            <ul class="nl">
                                                <li>Reflecterende</li>
                                                <li>Bescherm je broek</li>
                                            </ul>
											<br>
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">6€</strong> (HTVA) la paire</p>
								            <p class="en"><strong class="text-green bold">6€</strong> (Ex. VAT) the pair</p>
								            <p class="nl"><strong class="text-green bold">6€</strong> (Ex. BTW) het paar</p>

										</div>
									</div>

									<div class="col-sm-6">
										<div class="box-services-a">
											<h3>4 ACT</h3>
											<img class="img-responsive" src="images/accessoires/Securite_Gilet.jpg" alt="4 ACT gilet fluo vélo">
											<h4><?=L::accessoires_sp_modal_characteristics;?></h4>

                                            <ul class="fr">
                                            	<li>Réfléchissant</li>
                                            	<li>Compacte</li>
                                            	<li>Moulant</li>
                                            </ul>
                                            <ul class="en">
                                            	<li>Reflective</li>
                                            	<li>Compact</li>
                                            	<li>Skinny</li>
                                            </ul>
                                            <ul class="nl">
                                            	<li>Reflecterende</li>
                                            	<li>Compact</li>
                                            	<li>Broodmager</li>
                                            </ul>

											<br>
											<h4><?=L::accessoires_modal_price;?></h4>
								            <p class="fr"><strong class="text-green bold">12€</strong> (HTVA)</p>
								            <p class="en"><strong class="text-green bold">12€</strong> (Ex. VAT)</p>
								            <p class="nl"><strong class="text-green bold">12€</strong> (Ex. BTW)</p>

										</div>
									</div>



								</div>
							</div>
							<div class="modal-footer">
								<a class="button green button-3d rounded effect icon-left" href="commander.php"><span><i class="fa fa-check"></i><?=L::accessoires_modal_order;?></span></a>
								<a class="button button-3d rounded effect icon-left"  data-dismiss="modal"><span><i class="fa fa-close"></i><?=L::accessoires_modal_close;?></span></a>
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
