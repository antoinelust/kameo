<?php
include 'include/head.php';
?>
<!DOCTYPE html>
<html lang="fr">

<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include 'include/topbar.php'; ?>
		<?php include 'include/header.php'; ?>
		<br>

            	<!-- Post item-->
                <div class="post-item">
                	<!--
                    <div class="post-image col-md-8 center">
                            <img alt="" src="images/blog/exo_fiscale_1.jpg">
                    </div>
                    -->
                    <div class="post-content-details col-md-8 center">
											<h1 class="text-green">Pénurie de vélo</h1>
											<span class="text-green">par KAMEO Bikes</span>

                        <div class="post-description">
													<br>
													<br>

												<h4 class="text-green">Quelles sont les causes ?</h4>
												<br>

                        <p class="text-justify text-dark"><strong>Cette pénurie est principalement due à deux facteurs :</strong>
													<ul>
														<li>Le premier est qu’avec le confinement de nombreuses usines de production ont dû cesser ou ralentir fortement leurs activités. A leurs réouvertures, ces entreprises avaient accumulé un retard conséquent qu’elles mettront plusieurs mois à combler. Un vélo complet nécessite environ 17 intervenants. Chaque intermédiaire a  accusé un léger retard, donc la chaine complète a été retardée, et le retard sur la livraison finale devient donc conséquent.</li>
														<li>Le second facteur est, évidemment, l’explosion de la demande de vélo. Les premières mesures de confinement, ayant obligées la population à rester chez elle, ont fait prendre conscience à de nombreuses personnes l’importance des activités extérieures. C’est pourquoi, dès l’assouplissement des règles et la réouverture des magasins, les revendeurs de vélos ont été pris d’assaut par les usagers. Entre mai et juin les ventes de vélos ont subi une hausse de 117% par rapport à la même période de l’année précédente. </li>
													</ul>
 												</p>

												<h4 class="text-green">Quelles sont les conséquences ?</h4>
												<br>

												<p class="text-justify text-dark"><strong>Les conséquences sont multiples : </strong>
													<ul>
														<li>Une rupture de stock pour 2021 déjà annoncée chez un bon nombre de marchands de vélos. Tant pour les vélos que pour les accessoires. </li>
														<li>Certains vélos disponibles seront montés différemment que sur le catalogue du commerçant, en raison du stock limité en accessoires. </li>
														<li>Les usines de composants ont des délais de livraison de plus de 300 jours </li>
														<li>On prévoit une hausse de prix des vélos pouvant aller jusque 300€ pour le même modèle par rapport à l’année précédente.</li>
													</ul>
												</p>



												<h4 class="text-green">Quels sont les délais ?  </h4>

                        <p class="text-justify text-dark">Rares sont les fournisseurs qui savent encore répondre à votre demande pour 2021. Quel que soit votre type de pratique, les délais de livraison sont prévus au plus tôt à partir de 2022.</p>

												<h4 class="text-green">La réaction de Kameo </h4>

												<p class="text-justify text-dark">Kameo a pensé à vous. L'équipe s'est démenée pour vous trouver les derniers vélos encore disponibles pour 2021. Une flotte d'une centaine de vélos électriques va arriver dans nos ateliers d'ici juin 2021.
													Ces vélos seront identiques à ceux en catalogue, aucune mauvaise surprise. Et nous n’augmenterons pas notre politique de prix.
												</p>

												<h4 class="text-green">Réservez le votre ! </h4>

												<p class="text-justify text-dark">Réservez sans trop tarder le vélo de votre choix. Ainsi vous aurez la certitude de faire partie des rares personnes à posséder un nouveau vélo en 2021.

													Pour se faire, un formulaire vous permettra de personnaliser votre demande, et ainsi à notre équipe de mettre votre vélo de côté, pour vous, dès son arrivée.

												</p>

												<form id="widget-contact-form" action="apis/Kameo/contact_form.php" role="form" method="post">
														<div class="col-sm-6 center">
																<div class="form-group col-sm-6">
																		<label for="name"><?=L::contact_name;?></label>
																		<input type="text" aria-required="true" name="name" class="form-control required name">
																</div>
																 <div class="form-group col-sm-6">
																		<label for="firstName"><?=L::contact_firstname;?></label>
																		<input type="text" aria-required="true" name="firstName" class="form-control required name">

																</div>
																<div class="form-group col-sm-6">
																		<label for="email"><?=L::contact_mail;?></label>
																		<input type="email" aria-required="true" name="email" class="form-control required email">
																</div>
																<div class="form-group col-sm-6">
																		<label for="phone"><?=L::contact_phone;?></label>
																		<input type="phone" aria-required="true" name="phone" class="form-control required phone" placeholder="+32">
																</div>
																<div class="col-sm-12 text-center">
																	<button class="button green button-3d effect fill-vertical" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp<?=L::contact_send_btn;?></button>
																</div>
														</div>
												</form>


		      					</div>

                <div class="space"></div>
                <div class="col-md-12">


					<h3 class="fr text-green text-center">Pour toutes questions supplémentaires n’hésitez pas à nous contacter. Bon ride et à bientôt sur les routes !</h3>


		<a class="read-more" href="blog.php"><i class="fa fa-long-arrow-left"></i> Retour aux articles</a>


                    </div></div>
        </div>

		<?php include 'include/footer.php'; ?>
	</div>

	<!-- END: WRAPPER -->


	<!-- Theme Base, Components and Settings -->
	<script src="/js/theme-functions.js"></script>

</body>

</html>
