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


					<h1 class="text-green"><?=L::blog_title;?></h1>
					<br>

					<!--Article 1-->
					<div class="text-center">

						<div class="post-item">

							<a href="blog_Choisir-son-velo-electrique.html">
								<img alt="" src="images/blog/ChoixVelo_Cover.jpg">
							</a>

							<div class="post-content-details">
								<div class="post-title">
									<h3><a href="blog_Choisir-son-velo-electrique.html">Comment choisir son nouveau vélo électrique</a></h3>
								</div>
								<div class="post-description">
									<p>Suivez notre guide pour faire votre meilleur choix.</p>
									
									<?php
									if (isset($_GET['idArticle'])) {
										$idArticle = $_GET['idArticle'];
										if($idArticle=='blog_Choisir-son-velo-electrique'){
											
											echo "<details open>";
											
										}
										else 
										{
											
											echo "<details>";
											
										}

									}
									else 
									{
										
										echo "<details>";
										
									}

									?>
									<summary>Lire La suite <i class="fa fa-long-arrow-right"></i></summary>
									<h1 class="fr text-green col-md-10 center text-center">Comment choisir son nouveau vélo électrique?<br>par KAMEO Bikes</h1>

									<div class="post-content-details col-md-8 center">
										<div class="post-description">
											<br>


											<p class="text-justify text-dark">Les ventes et leasing de vélos sont en pleine croissance. Chez KAMEO Bikes, on constate également une demande de plus en plus forte. Que cela soit pour des vélos de loisir ou comme nouveau moyen de transport, tout le monde veut un nouveau vélo !</P>

												<p class="text-justify text-dark">Cependant, si beaucoup souhaitent s’équiper d’un vélo électrique, peu savent comment par où commencer. De multiples gammes -<strong> urbain, trekking, VTC, VTT, speed pédélec, pliable, route, gravel, cargo</strong> - s’offrent à vous avec des terminologies parfois obscures pour les non-initiés. On fait le point ensemble sur les critères essentiels afin de définir vos besoins et que vous puissiez ainsi choisir, en toute connaissance de cause, votre futur vélo préféré !</p>

												<h3 class="fr text-green text-center">On commence par une question simple :<br> Sur quel type de parcours comptez-vous rouler ?</h3>

												<p class="text-justify text-dark">Route bétonnée ? Ravel ? Chemin de terre damé ? Chemin dans les bois ? Off-road dans les bois ? Le graphique ci-dessous vous indique les gammes de vélos compatibles avec les différents terrain de jeu.</p>

												<div class="portfolio-image effect social-links">
													<img src="images/blog/ChoixVelo1.jpg" alt="">
													<div class="image-box-content">
														<p>
															<a href="images/blog/ChoixVelo1.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a>
														</p>
													</div>
												</div>

												<h3 class="fr text-green text-center">Votre vélo est-il un outil professionnel ?<br>Votre compagnon de loisir préféré ?<br>Ou bien, vous envisagez une utilisation mixte ? </h3>

												<p class="text-justify text-dark">Si vous comptez utiliser votre vélo pour vos trajets domicile-travail, veillez à prendre un vélo tout équipé. L’équipement conseillé est :
													<ul>
														<li class=" text-dark">Un porte paquet robuste pouvant supporter 25 kg</li>
														<li class=" text-dark">Des garde-boues rigides et, si possible, fixés au cadre ainsi qu’au porte paquet. Plus ils sont fixés, moins vous aurez de bruits et/ou de risque de frottement sur le pneu.</li>
														<li class=" text-dark">Une béquille</li>
														<li class=" text-dark">Des lampes connectées au moteur et à la batterie afin de ne pas devoir recharger des piles ou une batterie supplémentaire</li>
													</ul>
												</p>

												<div class="portfolio-image effect social-links">
													<img src="images/blog/ChoixVelo_Velo.jpg" alt="">
												</div>

												<p class="text-justify text-green">Pensez évidement aussi à vous équiper pour vos trajets quotidien : casque, sacoche, vêtements imperméables en cas de pluie et gilet fluo.</p>

												<p class="text-justify text-dark">Certaines gammes de vélo sont plus orientées loisir et d’autres pour les déplacements quotidiens. D’un côté, les vélos urbains, speed pédélec, cargo et les vélos pliants sont 4 gammes adaptées pour les trajets domicile-travail. A l’opposé, les routes, gravels et VTT ont plutôt une orientation loisir. Les vélos Trekking et VTC sont, eux, les vrais polyvalents !</p>

												<p class="text-justify text-dark">Il est important de souligner que certaines gammes sont prévues pour des usages tout à fait spécifiques comme les Speed pédélec, pliants et cargo.</p>

												<p class="text-justify text-dark">Les Speed pédélec sont des vélos avec une assistance électrique jusqu’à 45 km/h. Ils sont conçus pour les long trajets quotidiens. Ils demandent une immatriculation et un permis de conduire.</p>

												<p class="text-justify text-dark">Les Pliants sont vos alliés pour combinés les différents modes de transport. Vous l’emmenez facilement en train ou dans le coffre d’une voiture.</p>

												<p class="text-justify text-dark">Les Cargo et longtail sont là pour vous aider à transporter du matériel ou vos enfants à vélo</p>

												<div class="portfolio-image effect social-links">
													<img src="images/blog/ChoixVelo_Velo2.jpg" alt="">
												</div>

												<h3 class="fr text-green text-center">Comment vous positionnez-vous pour être à l’aise à vélo ?<br>Le type de cadre et sa taille influencent directement votre posture, il est impératif de les choisir pour avoir une position confortable. </h3>

												<p class="text-justify text-dark"><strong>Le vélo qui vous convient est celui sur lequel vous vous sentez bien !</strong> Dans la plupart des gammes, vous retrouverez 3 types de cadres différents Wave (col de cygne), Trapèze, Diamant (tube supérieur à l’horizontale) – pour s’adapter à tous les cyclistes. Un cadre Wave est plus simple à enjamber qu’un cadre diamant et le trapèze est un intermédiaire.</p>

												<div class="portfolio-image effect social-links">
													<img src="images/blog/ChoixVelo2.jpg" alt="">
												</div>

												<p class="text-justify text-dark">Le genre ne définit pas le type de cadre. Les dames comme les hommes peuvent préférer un modèle droit ou un col de cygne, ce qui importe encore une fois, c’est votre confort !</p>

												<p class="text-justify text-dark">Outre la capacité d’enjambement, la posture a de l’importance. Vous préférez une position plus sportive et penchée sur le vélo ou plus droite ? Prêtez-y attention, la géométrie du cadre joue énormément. N’hésitez pas à demander un conseil à votre vendeur. Un vélo avec une potence (la pièce reliant le guidon à votre fourche ) réglable en inclinaison vous permettra certainement de trouver l’ ajustement optimal. Cela aura néanmoins une limite liée au cadre, d’où l’importance de se faire conseiller.</p>

												<p class="text-justify text-dark">Quant à la taille du vélo, elle est comparable à celle des vêtements. On retrouve des cadres de S à XL. Comme pour les vêtements, les tailles changent d’une marque à l’autre. Demandez donc le conseil de votre vendeur qui connait bien sa marque. En moyenne, vous pouvez considérer les valeurs suivantes : 
													<ul>
														<li class=" text-dark">>1m60 : <strong class="text-green">S</strong></li>
														<li class=" text-dark">1m60< >1m75 : <strong class="text-green">M</strong></li>
														<li class=" text-dark">1m75< >1m85 : <strong class="text-green">L</strong></li>
														<li class=" text-dark">>1m85 : <strong class="text-green">XL</strong></li>
													</ul>
												</p>

												<h3 class="fr text-green text-center">Choix final</h3>

												<p class="text-justify text-dark">Maintenant que vous savez le type de vélo que vous voulez acquérir, vous devez faire votre choix entre les différents modèles au sein d’une même gamme. Pour cela, il faudra prendre en considération votre fréquence d’utilisation du vélo, les kms que vous comptez parcourir, votre budget et naturellement, vos goûts et couleurs pour le look ! Pour l’assistance électrique, deux pièces complémentaires sont à spécifier : le moteur et la batterie. La partie qui suit est assez technique mais elle vous permettra de maitrisez tout ce qu’il faut pour choisir ! </p>

												<p class="text-justify text-dark">Nous prenons pour exemple les moteurs Bosch car ce sont les leaders du marché.</p>

												<h3 class="fr text-green text-center">Comment comparer différents moteurs électriques entre eux ?</h3>

												<p class="text-justify text-dark">Le moteur a une puissance exprimée en watts (symbole W), généralement 250 W qui est la limite légale pour les vélos dont l’assistance fonctionne jusque 25 km/h. Ils ont donc tous la même puissance. La donnée qui est importante pour vous est le couple du moteur. Il correspond à la force d’entrainement du moteur. Plus il est élevé, plus votre vélo électrique grimpera facilement les côtes. Chez Bosch on retrouve des moteurs avec des couples allant de 40 à 75 Nm. </p>

												<p class="text-justify text-dark">Vous retrouverez sur ce graphique les utilisations de prédilection de chaque moteur :</p>


												<div class="portfolio-image effect social-links">
													<img src="images/blog/ChoixVelo3.jpg" alt="">
													<div class="image-box-content">
														<p>
															<a href="images/blog/ChoixVelo3.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a>
														</p>
													</div>
												</div>

												<div class="space"></div>

												<h3 class="fr text-green text-center">Conseils de KAMEO Bikes</h3>

												<p class="text-justify text-dark">En Belgique nous avons différents types de dénivelé. Nous vous conseillons au minimum les moteurs suivants selon votre ville et pour une utilisation vélo-taff  :
													<ul>
														<li class=" text-dark">Bruxelles : Active line plus, 50 Nm </li>
														<li class=" text-dark">Liège : Performance line, 63 Nm</li>
														<li class=" text-dark">Namur : Performance line, 63 Nm</li>
														<li class=" text-dark">Charleroi : Active line plus, 50 Nm</li>
														<li class=" text-dark">Campagne relativement plate : Active line plus, 50 Nm</li>
														<li class=" text-dark">Campagne vallonnée : Performance line, 63 Nm</li>
														<li class=" text-dark">Ardennes : Performance line CX, 75Nm</li>
													</ul>
												</p>

												<h3 class="fr text-green text-center">De quelle batterie ai-je besoin ?</h3>

												<p class="text-justify text-dark">Chaque utilisateur souhaite maximiser l’autonomie de son vélo électrique. Il est difficile de quantifier exactement l’autonomie d’une batterie, un peu comme il est compliqué de prédire une distance réalisée par une voiture avec une quantité de carburant. Cela dépend fortement des conditions du trajet comme la vitesse et le profil du parcours. Cependant, il y a une donnée objective qui est la capacité de la batterie = l’énergie électrique stockée dans celle-ci. Cette information est donnée en Wh ( watt heure, c’est l’unité ). On peut comparer ceci au volume d’un réservoir d’une voiture. </p>

												<p class="text-justify text-dark">Pour un même moteur et une même conduite, plus le réservoir est grand, plus on peut faire de km. C’est pareil à vélo ! Bosch le leader pour les batteries et moteur possède des batteries de 300, 400, 500 et 625 Wh. Pour vous faire une idée de l’autonomie nous vous invitions à <a href="https://www.bosch-ebike.com/fr/service/assistant-dautonomie/" target="_blank"><ins class="text-green">simuler votre autonomie ici</ins></a>.</p>

												<div class="portfolio-image effect social-links">
													<img src="images/blog/ChoixVelo_Velo3.jpg" alt="">
												</div>

												<h3 class="fr text-green text-center">Autres composants</h3>

												<p class="text-justify text-dark">Le dérailleur, les freins, les suspensions sont les autres composants d’un vélo électrique. Il est difficile sans connaissances techniques ou une maîtrise du marché de différencier un bon composant d’un autre. Demandez conseil à des professionnels, cependant évitez les marques no-name et fiez-vous aux leaders de chaque catégorie comme SRAM, Shimano pour les dérailleurs qui vous proposeront des modèles robustes.</p>

												<h3 class="fr text-green text-center">Rappel des points d’attention :</h3>
												<h4 class="fr text-dark text-center">1. Type de parcours</h4>
												<h4 class="fr text-dark text-center">2. Fonction du vélo</h4>
												<h4 class="fr text-dark text-center">3. Position et taille</h4>
												<h4 class="fr text-dark text-center">4. Fréquence d’utilisation et budget</h4>
												<h4 class="fr text-dark text-center">5. Données techniques et situation</h4>

												<div class="space"></div>

												<h3 class="fr text-green text-center">Finalement et avant tout, pensez à tester votre vélo avant de l’acheter c’est l’étape la plus importante qui vous fera savoir s’il vous est adapté.</h3>

												<p class="text-justify text-dark">Le dérailleur, les freins, les suspensions sont les autres composants d’un vélo électrique. Il est difficile sans connaissances techniques ou une maîtrise du marché de différencier un bon composant d’un autre. Demandez conseil à des professionnels, cependant évitez les marques no-name et fiez-vous aux leaders de chaque catégorie comme SRAM, Shimano pour les dérailleurs qui vous proposeront des modèles robustes.</p>
											</p>
										</div>





									</div>

									<!-- CALL TO ACTION -->
									<div class="space"></div>
									<div class="col-md-12">
										<div class="jumbotron jumbotron-center jumbotron-fullwidth text-light" style="background: url('images/fond_degrade3.jpg');" data-stellar-background-ratio="0.3">
											<div class="container">
												<h3>Finalement et avant tout pensez à tester votre vélo avant de l’acheter c’est l’étape la plus importante qui vous fera savoir s’il vous est adapté.</h3>

												<p class="text-light">Vous souhaitez organiser un test de vélos dans votre entreprise ?</p>

												<a class="button black-light button-3d effect fill-vertical scroll-to"  href="contact2.php"><i class="fa fa-check"></i><span>Contactez-nous !</span></a>

											</div>

										</div>


										<h3 class="fr text-green text-center">Pour toutes questions supplémentaires n’hésitez pas à nous contacter. Bon ride et à bientôt sur les routes !</h3>	
									</div>
								</div>
							</details>
						</div>
					</div>


					<!--Article 2-->


					<div class="text-center">
						<div class="post-item">
							<a href="blog_Infrastructures-cyclables-a-Liege-et-a-Bruxelles-pendant-le-deconfinement-et-apres.html">
								<img alt="" src="images/blog/infra_velo.jpg">
							</a>
							<div class="post-content-details">
								<div class="post-title">
									<h3><a href="blog_Infrastructures-cyclables-a-Liege-et-a-Bruxelles-pendant-le-deconfinement-et-apres.html">Infrastructures cyclables</a></h3>
								</div>
								<div class="post-description">
									<p>à Liège et à Bruxelles, pendant le déconfinement et après.</p>


									<?php
									if (isset($_GET['idArticle'])) {
										$idArticle = $_GET['idArticle'];
										if($idArticle=='blog_Infrastructures-cyclables-a-Liege-et-a-Bruxelles-pendant-le-deconfinement-et-apres'){
											
											echo "<details open>";
											
										}
										else 
										{

											echo "<details>";

										}

									}
									else 
									{

										echo "<details>";

									}

									?>
									<summary>Lire La suite <i class="fa fa-long-arrow-right"></i></summary>
									<h1 class="fr text-green col-md-10 center text-center">Infrastructures cyclables à Liège et à Bruxelles : pendant le déconfinement et après ?</h1>

									<!-- Post item-->
									<div class="post-item">
						<!--
                    <div class="post-image col-md-8 center">
                            <img alt="" src="images/blog/exo_fiscale_1.jpg">
                    </div>
                -->
                <div class="post-content-details col-md-8 center">
                	<div class="post-description">
                		<br>
                		<p class="text-justify text-dark">Le manque d’infrastructures cyclables de qualité est souvent pointé du doigt par les cyclistes potentiels comme confirmés. Cependant, si tout n’est pas rose, de nombreuses voies cyclables existent et d'avantage sont en construction. De plus, la crise récente a amené plusieurs villes à créer des pistes cyclables temporaires en vue du confinement : une excellente nouvelle !</p>

                		<p class="text-justify text-dark">À Bruxelles, 40 km de pistes cyclables sécurisées ont été créées sur les axes principaux. En effet, afin de diminuer la demande de la STIB, la ministre de la mobilité Bruxelloise Elke Van Brandt a dû créer des alternatives. Nous souhaiterions bien sûr que cette mesure s’inscrive d'avantage dans le temps mais profitons de ces nouvelles infrastructures tant qu’elles sont réservées aux cyclistes ! </p>

                		<div class="portfolio-image effect social-links">
                			<img src="images/blog/infra_velo_map1.jpg" alt="">
                			<div class="image-box-content">
                				<p>
                					<a href="images/blog/infra_velo_map1_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a>
                				</p>
                			</div>
                		</div>

                		<p class="text-justify text-dark">La ville de Liège a également adopté cette stratégie en créant 35km de voiries sécurisées pour les cyclistes. Les initiatives peuvent se regrouper en 3 catégories:
                			<ul>
                				<li class="text-dark">mise en place de couloirs-vélos vers les quartiers périphériques et en centre-ville</li>
                				<li class="text-dark">transformation de rues en pistes cyclables</li>
                				<li class="text-dark">aménagement de stationnements sécurisés et temporaires pour vélos près des pôles d'activité</li>
                			</ul>
                			<p class="text-justify text-dark">Ces mesures permettront de favoriser une mobilité douce respectueuse des mesures de distanciation sociale.</p>


                			<div class="portfolio-image effect social-links">
                				<img src="images/blog/infra_velo_map2.jpg" alt="">
                				<div class="image-box-content">
                					<p>
                						<a href="images/blog/infra_velo_map2_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a>
                					</p>
                				</div>
                			</div>

                			<div class="space"></div>

                			<p class="text-justify text-dark">Les pistes cyclables représentées sur l'image ci-dessous viennent se rajouter un réseau déjà bien développé. D’un point de vue global, la Belgique est relativement bien équipée pour offrir un certain confort à ses cyclistes. Notre Royaume compte plus de 1440 kilomètres de RAVeL. Sur ces routes, pas de risque de rencontrer des voitures ou autres véhicules motorisés puisque seuls les cyclistes, les cavaliers, les piétons et les personnes à mobilité réduite peuvent y circuler. Ils sont généralement situés le long des voies hydrauliques, sur des anciennes lignes ferrées ou sur d’anciennes voiries réaffectées. Ces itinéraires plutôt plats peuvent donc être utilisées aussi bien pour des balades que pour vos trajets quotidiens domicile-travail.</p>

                			<a href="https://ravel.wallonie.be/home/carte-interactive.html" target="_blank"><ins>Retrouvez ici la carte qui vous permettra de trouver les portions présentes dans votre région.</ins></a>
                			<div class="space"></div>

                			<p class="text-justify text-dark">Au niveau plus local, chaque ville a développé des itinéraires vélos. À Bruxelles, par exemple, il s’agit des 19 Itinéraires Cyclables régionaux (ICR), qui connectent la ville par des voies radiales ou circulaires. </p>

                			<div class="space"></div>

                			<h3 class="fr text-green">L'après COVID-19</h3>

                			<p class="text-justify text-dark">Nous espérons évidement que les infrastructures temporaires mises en place durant le confinement seront conservées et/ou remplacées par d’autres de façon permanente. Ces aménagements répondent à un besoin réel de la demande de transport.</p>


                			<p class="text-justify text-dark">Le vélo fait partie des leviers importants pour améliorer la mobilité et la qualité de vie des citoyens belges, surtout ceux vivant dans les centres urbains ou péri-urbains. Les pouvoirs publics l’ont de mieux en mieux compris et investissent dans des infrastructures adaptées. Nous vous proposons de nous concentrer sur les plans d’évolution de 2 villes ; Liège et Bruxelles qui avancent vers un mieux pour les cyclistes.</p>

                			<div class="col-md-10">
                				<h4 class="fr">Liège</h5>

                					<blockquote>
                						<p>« À Liège, la croissance de la demande est assez nette :<br>
                						le nombre de cyclistes sur le terrain a quadruplé en 10 ans »</p>
                						<small><cite>affirme le Plan Urbain de Mobilité (PUM) de l’arrondissement.</cite></small>
                					</blockquote>

                					<h5 class="text-justify text-dark">Dans les actions importantes qui vont être mises en place, on retrouve :</h5>
                					<ul>
                						<li class="text-dark">15 nouveaux corridors prévus dans le PUM afin de ramener les cyclistes depuis la périphérie de Liège jusqu'au centre-ville</li>
                						<li class="text-dark">Un grand parking sécurisé</li>
                						<li class="text-dark">L’installation de box vélos</li>
                					</ul>

                					<p class="text-justify text-dark">
                						D'un part, ces aménagements vont permettre un déplacement sécurisé sur des voies propres pour les vélos. Ces voies traverseront la ville et relieront les zones d’activités à celles de résidence en bordure du centre. <br>
                					D’autre part, des investissements seront faits pour sécuriser votre vélo lorsque vous le stationnez en ville. Avec le parking du centre-ville et les box pour vélos sécurisés, les cyclistes du centre-ville comme de la zone périphérique devraient trouver une solution de stationnement à leur goût. Cet ensemble cohérent d’initiatives pour une mobilité plus douce rendra Liège accessible aux cyclistes.</p>

                					<a href="http://mobilite.wallonie.be/files/PUM-LIEGE/PUM-LIEGE-rapport-final-mai-2019.pdf" target="_blank"><ins>Plus d’infos sur le PUM</ins></a>

                					<div class="space"></div>

                					<div class="portfolio-image effect social-links">
                						<img src="images/blog/infra_velo_map3.jpg" alt="">
                						<div class="image-box-content">
                							<p>
                								<a href="images/blog/infra_velo_map3_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a>
                							</p>
                						</div>
                					</div>

                					<div class="space"></div>

                					<h4 class="fr">Bruxelles et alentours</h4>

                					<p class="text-justify text-dark">À Bruxelles comme ailleurs, la progression du vélo est marquée. On croise aujourd’hui deux fois plus de vélo dans la capitale qu’il y a 5 ans (comptabilisé par Pro Vélo).</p>

                					<h5 class="text-justify text-dark">Pour accueillir ces nouveaux cyclistes et atteindre l’ambition de Bruxelles de devenir une capitale cyclable, de nombreux nouveaux aménagements sont prévus :</h5>
                					<ul>
                						<li class="text-dark">Côté wallon, les voies express cyclables (VER) sont à l'étude. Il s’agira « d’autoroutes vélos », elles seraient acheminées le long de la ligne ferroviaire 124 reliant Waterloo et Bruxelles, le long de la E411, sur les Chaussées de Waterloo et de La Hulpe et également le long du canal vers Tubize.<br> A titre d'exemple, sur la ligne Bruxelles Nivelles qui traverse Waterloo et Braine l’Alleud, seize kilomètres seront à terme aménagés. Même si cela avait déjà été acté sous la législature précédente, des moyens plus conséquents y ont été affectés dans le cadre du Plan infrastructure wallon 2019-2024. </li>

                						<div class="portfolio-image effect social-links">
                							<img src="images/blog/infra_velo_map4.jpg" alt="">
                							<div class="image-box-content">
                								<p>
                									<a href="images/blog/infra_velo_map4_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a>
                								</p>
                							</div>
                						</div>

                						<br>
                						<li class="text-dark">Côté Nord, les Fietsnelweg sont déjà une réalité et connectent Bruxelles au reste de la Flandre. Certains tronçons restent en cours de réalisation mais plus de 2400 km sont déjà complétés. Et si on s’en inspirait davantage ?</li>


                						<div class="portfolio-image effect social-links">
                							<img src="images/blog/infra_velo_map5.jpg" alt="">
                							<div class="image-box-content">
                								<p>
                									<a href="images/blog/infra_velo_map5_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a>
                								</p>
                							</div>
                						</div>

                					</ul>

                					<div class="space"></div>
                					<p class="text-justify text-dark">Vous voulez retrouver toutes les cartes avec les infrastructures vélos disponibles en ligne ?</p>

                					<a href="https://www.provelo.org/fr/page/cartes-interactives-itineraires-cyclistes" target="_blank"><ins>N’hésitez pas à consulter l’article très complet de Pro Vélo à ce sujet </ins></a>
                				</div>
                			</div>
                		</div>
                		<div class="space"></div>
                		<div class="separator"></div>
                		<div class="col-md-12">
                			<p><small>Sources: <br>
                				<a href="https://www.lesoir.be/297567/article/2020-04-29/deconfinement-40-kilometres-de-pistes-cyclables-securisees-bruxelles-carte" target="_blank">https://www.lesoir.be/297567/article/2020-04-29/deconfinement-40-kilometres-de-pistes-cyclables-securisees-bruxelles-carte</a><br>
                				<a href="https://ravel.wallonie.be/home/carte-interactive.html" target="_blank">https://ravel.wallonie.be/home/carte-interactive.html</a><br>
                				<a href="https://mobilite-mobiliteit.brussels/en/node/5" target="_blank">https://mobilite-mobiliteit.brussels/en/node/5</a><br>
                				<a href="https://www.lameuse.be/500481/article/2020-01-12/un-parking-couvert-pour-velos-bientot-liege?fbclid=IwAR0rk2aCRHZICBjTQkIVEECY9IJwQ70o0KnGXZLs9mgtjIiNOH-eka7fUbM" target="_blank">https://www.lameuse.be/500481/article/2020-01-12/un-parking-couvert-pour-velos-bientot-liege?fbclid=IwAR0rk2aCRHZICBjTQkIVEECY9IJwQ70o0KnGXZLs9mgtjIiNOH-eka7fUbM</a><br>
                				<a href="http://mobilite.wallonie.be/files/PUM-LIEGE/PUM-LIEGE-rapport-final-mai-2019.pdf" target="_blank">http://mobilite.wallonie.be/files/PUM-LIEGE/PUM-LIEGE-rapport-final-mai-2019.pdf</a><br>
                				<a href="https://www.dhnet.be/actu/belgique/la-region-wallonne-veut-cinq-autoroutes-pour-velos-vers-bruxelles-5ddd2198d8ad58130db81ca0" target="_blank">https://www.dhnet.be/actu/belgique/la-region-wallonne-veut-cinq-autoroutes-pour-velos-vers-bruxelles-5ddd2198d8ad58130db81ca0</a><br>
                				<a href="https://www.rtbf.be/info/regions/detail_bruxelles-le-nombre-de-cyclistes-a-augmente-en-un-an-et-double-en-cinq-ans?id=10136723" target="_blank">https://www.rtbf.be/info/regions/detail_bruxelles-le-nombre-de-cyclistes-a-augmente-en-un-an-et-double-en-cinq-ans?id=10136723</a><br>
                				<a href="https://www.dhnet.be/actu/belgique/la-region-wallonne-veut-cinq-autoroutes-pour-velos-vers-bruxelles-5ddd2198d8ad58130db81ca0" target="_blank">https://www.dhnet.be/actu/belgique/la-region-wallonne-veut-cinq-autoroutes-pour-velos-vers-bruxelles-5ddd2198d8ad58130db81ca0</a><br>
                				<a href="https://docs.google.com/viewerng/viewer?url=https://www.todayinliege.be/wp-content/uploads/2020/05/COVID-carte-des-cibles.pdf&hl=fr" target="_blank">https://docs.google.com/viewerng/viewer?url=https://www.todayinliege.be/wp-content/uploads/2020/05/COVID-carte-des-cibles.pdf&hl=fr</a><br>
                				<a href="https://www.provelo.org/fr/page/cartes-interactives-itineraires-cyclistes" target="_blank">https://www.provelo.org/fr/page/cartes-interactives-itineraires-cyclistes</a><br>
                			</small></p>
                		</div>

                	</div>

                	<!-- CALL TO ACTION -->
                	<div class="space"></div>
                	<div class="col-md-12">
                		<div class="jumbotron jumbotron-center jumbotron-fullwidth text-light" style="background: url('images/fond_degrade3.jpg');" data-stellar-background-ratio="0.3">
                			<div class="container">
                				<h3>Vous aussi vous voulez changer la mobilité de nos villes ?<br> Contactez-nous pour en discuter !</h3>
                				<div class="col-md-12 text-left">
                					<form id="widget-contact-form" action="apis/Kameo/contact_form.php" role="form" method="post">
                						<div class="row">
                							<div class="form-group col-sm-3">
                								<label for="name"><?= L::blog_infra_name; ?></label>
                								<input type="text" aria-required="true" name="name" class="form-control required name">
                							</div>
                							<div class="form-group col-sm-3">
                								<label for="firstName"><?= L::blog_infra_firstname; ?></label>
                								<input type="text" aria-required="true" name="firstName" class="form-control required name">

                							</div>
                							<div class="form-group col-sm-3">
                								<label for="email"><?= L::blog_infra_mail; ?></label>
                								<input type="email" aria-required="true" name="email" class="form-control required email">
                							</div>
                							<div class="form-group col-sm-3">
                								<label for="phone"><?= L::blog_infra_phone; ?></label>
                								<input type="phone" aria-required="true" name="phone" class="form-control required phone" placeholder="+32">
                							</div>
                							<div class="form-group col-sm-12">
                								<div class="particulier col-sm-3">
                									<label><input type="radio" name="type" value="particulier" checked><?= L::blog_infra_particulier; ?></label>
                								</div>
                								<div class="professionnel col-sm-3">
                									<label><input type="radio" name="type" value="professionnel"><?= L::blog_infra_pro; ?></label>
                								</div>
                							</div>
                							<div class="form-group col-sm-6 entreprise hidden">
                								<label for="entreprise"><?= L::blog_infra_entreprise; ?></label>
                								<input type="text" aria-required="true" name="entreprise" class="form-control">
                							</div>
                						</div>
                						<div class="row col-sm-4">
                							<div class="form-group">
                								<label for="subject"><?= L::blog_infra_subject; ?></label>
                								<input type="text" name="subject" class="form-control required">
                							</div>
                						</div>
                						<div class="form-group  col-sm-8">
                							<label for="message"><?= L::blog_infra_message; ?></label>
                							<textarea type="text" name="message" rows="5" class="form-control required" placeholder="Votre message"></textarea>
                						</div>

                						<div class="col-sm-12">
                							<div class="col-sm-4">

                								<div class="g-recaptcha" data-sitekey="6LfqMFgUAAAAADlCo3L6lqhdnmmkNvoS-kx00BMi"></div>


                								<input type="text" class="hidden" name="antispam" value="" />
                							</div>

                							<div class="col-sm-8">
                								<button class="button green button-3d effect fill-vertical " type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;<?= L::blog_infra_send; ?></button>
                							</div>
                						</div>
                					</form>
                					<script type="text/javascript">
                						jQuery("#widget-contact-form").validate({
                							submitHandler: function(form) {

                								jQuery(form).ajaxSubmit({
                									success: function(text) {
                										if (text.response == 'success') {
                											$.notify({
                												message: "Nous avons <strong>bien</strong> reçu votre message et nous reviendrons vers vous dès que possible."
                											}, {
                												type: 'success'
                											});
                											$(form)[0].reset();

                											gtag('event', 'send', {
                												'event_category': 'mail',
                												'event_label': 'contact.php'
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


                	</details>
                </div>
            </div>
        </div>
    </div>

    <!-- pagination nav -->
				            <!--
				            <div class="text-center">
									<div class="pagination-wrap">
										<ul class="pagination">
											<li>
												<a aria-label="Previous" href="#">
													<span aria-hidden="true"><i class="fa fa-angle-left"></i></span>

												</a>
											</li>
											<li><a href="#">1</a>
											</li>
											<li><a href="#">2</a>
											</li>
											<li class="active"><a href="#">3</a>
											</li>
											<li><a href="#">4</a>
											</li>
											<li><a href="#">5</a>
											</li>
											<li>
												<a aria-label="Next" href="#">
													<span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
												</a>
											</li>
										</ul>
									</div>
								</div>
				        </div>
				    -->
				    <!-- END: Blog post-->
				    <!-- END: SECTION -->
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

	<!-- CONTACT -->


</body>

</html>
