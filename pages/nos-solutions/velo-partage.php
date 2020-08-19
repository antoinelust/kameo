<!DOCTYPE html>
<html lang="fr">
<?php
	include 'include/head.php';

    require_once 'include/i18n/i18n.php';
    $i18n = new i18n(['lang/lang_velopartage_{LANGUAGE}.ini'/*,'lang/lang_{LANGUAGE}_2.ini'*/]); //french by defaut, as many files as wanted can be added to the array
    $i18n->init();

?>
<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include 'include/topbar.php'; ?>
		<?php include 'include/header.php'; ?>
			<!-- SECTION SOLUTIONS -->
			<section class="" style="background: url('images/fond_degrade.jpg');" data-stellar-background-ratio="0.3">
				<div class="container">
				<a class="button black-light button-3d effect fill-vertical right"  href="velo-personnel.php"><?=L::btn_personalbikes_btnpbikes;?>&nbsp;<i class="fa fa-arrow-right"></i></a>
					<div class="row">
						<div class="col-md-7">
							<!-- <hr class="space"> -->
                            <h1 class="text-dark"><?=L::description_title;?></h1>
							<p class="lead text-light text-justify fr">Rendez les déplacements de vos employés plus <strong>dynamiques</strong> et <strong>écologiques</strong>, mettez leur des vélos partagés à disposition.<br> Que ce soit pour : des rendez-vous clients, les trajets domicile-travail, un trajet inter-sites ou se rendre à un lunch, soyez sûr de leur temps de parcours et améliorez leur forme.</p>
							<p class="lead text-light text-justify en">Make your employees' trips more <strong>dynamic</strong> and <strong>ecological</strong>, make shared bikes available to them.<br> Whether for: client meetings, home-work trips, an inter-site trip or going out for lunch, be sure of their journey time and improve their shape.</p>
							<p class="lead text-light text-justify nl">Maak de ritten van uw werknemers <strong>dynamischer</strong> en <strong>ecologischer</strong>, stel DEELFIETSEN ter beschikking.<br> Of het nu gaat om: klantbijeenkomsten, huis-werkreizen, een reis tussen de locaties of om te lunchen, wees zeker van hun reistijd en verbeter hun vorm.</p>
							<p class="lead text-light fr"><strong>KAMEO Bikes</strong> s’occupe de tout et se déplace chez vous:<br>
								- <strong>conseil</strong> sur le vélo adapté,<br>
								- <strong>maintenance</strong> sur site,<br>
								- <strong>assurance</strong> et <strong>assistance</strong> dépannage,<br>
								- <strong>gestion</strong> des vélos simple, connectée et sécurisée,<br>
								- installation d'<strong>infrastructures</strong>.</p>
							<p class="lead text-light en"><strong>KAMEO Bikes</strong> takes care of everything and comes to your place:<br>
								- <strong>advice</strong> for the best bike for your needs,<br>
								- on site <strong>maintenance</strong>,<br>
								- <strong>insurance</strong> and <strong>assistance</strong> in case of breakdown,<br>
								- simple, connected and safe<strong>management</strong> of your bikes,<br>
								- <strong>infrastructures</strong> installation.</p>
							<p class="lead text-light nl"><strong>KAMEO Bikes</strong> zorgt voor alles en komt bij uw site:<br>
								- <strong>advies</strong> voor de beste fiets voor uw behoeften,<br>
								- ter plaats <strong>onderhoud</strong>,<br>
								- <strong>verzekering</strong> en <strong>pechbijstand</strong>,<br>
								- eenvoudig, verbonden en veilig <strong>beheer</strong> van de fietsen,<br>
								- <strong>infrastructuur</strong> installatie.</p>

							<a class="button black-light button-3d effect fill-vertical scroll-to fr"  href="#plus"><span>Découvrez notre offre <i class="fa fa-arrow-down"></i></span></a>
							<a class="button black-light button-3d effect fill-vertical scroll-to en"  href="#plus"><span>Discover our offer <i class="fa fa-arrow-down"></i></span></a>
							<a class="button black-light button-3d effect fill-vertical scroll-to nl"  href="#plus"><span>Ontdekt onze aanbod <i class="fa fa-arrow-down"></i></span></a>

						</div>
						<div class="col-md-5">
							<img src="images/Atradius_Bikes.jpg" class="img-responsive img-rounded" alt="Vélos électriques BZEN brandés aux couleurs d'Atradius">
						</div>
						<hr class="space" id="plus">

					</div>
				</div>
			</section>
			<!-- END: SECTION SOLUTIONS -->

			<!-- SECTION PROCESS -->
			<!--
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<img src="images/infographie_fr.png" class="img-responsive img-rounded" alt="Infographie - Parcous client chez KAMEO Bikes">
						</div>
						<div class="col-md-4"> -->
							<!-- <hr class="space"> -->
							<!--
							<h1 class="text-green">COMMENT ÇA MARCHE</h1>
							<p><strong class="text-green">Vous restez concentré sur votre activité, on se charge de tout!</strong></p>
							<ul>
								<li>Nous définissons ensemble vos besoins réels pour votre future flotte</li>
								<li>Nous vous proposons différent vélos</li>
								<li>Nous organisons des sessions d'essais avec vos employés</li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			-->
			<!-- END: SECTION PROCESS -->


			<!-- SECTION FLOTTE -->
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<!-- <hr class="space"> -->
							<h1 class="text-green"><?=L::fleet_title;?></h1>
							<p class="text-justify fr">Nous avons l’expérience, laissez nous vous conseiller sur le modèle et le nombre de vélos adéquats pour votre projet.</p>
							<p class="text-justify en">We have the know how, let us advise you on the model and the number of bikes suitable for your project.</p>
							<p class="text-justify nl">Wij hebben de ervaring, laat ons u adviseren over het model en het aantal fietsen dat geschikt is voor uw project..</p>
							<p class="text-justify fr">Nous proposons des vélos adaptés pour une flotte partagée et travaillons avec des partenaires de qualité tels que <strong class="text-green">BZEN</strong>,<strong class="text-green">CONWAY</strong> et <strong class="text-green">AHOOGA</strong>.
							<p class="text-justify en">We offer bikes suitable for a shared fleet and work with quality partners such as <strong class="text-green">BZEN</strong>,<strong class="text-green">CONWAY</strong> et <strong class="text-green">AHOOGA</strong>.
							<p class="text-justify nl">Wij bieden geschikte fietsen aan voor een gedeelde vloot en werken samen met kwaliteitspartners zoals <strong class="text-green">BZEN</strong>,<strong class="text-green">CONWAY</strong> et <strong class="text-green">AHOOGA</strong>.
						    <p class="text-justify fr">Envie de brander vos vélos ? C’est comme si c’était fait !</p>
						    <p class="text-justify en">Want to brand your bikes ? Consider it done !</p>
						    <p class="text-justify nl">Zin in een gepersonnaliseerde branding ? Zo goed als klaar !</p>


						</div>
						<div class="col-md-6">
							<img src="images/Flotte_BZen.jpg" class="img-responsive img-rounded" alt="BZEN - flotte de vélos">
						</div>

						<!-- SELECTION -->
						<div class="col-md-12">
						<h3 class="text-green"><?=L::fleet_selection;?></h3>
							<div class="carousel" data-lightbox-type="gallery">
								<div class="portfolio-item">
									<div class="portfolio-image effect social-links">
										<img src="images_bikes/bzen_amsterdam_f_mini.jpg" alt="BZEN Amsterdam">
										<div class="image-box-content">
											<p>
												<a href="offre.php?brand=bzen&model=amsterdam&frameType=f"><i class="fa fa-eye"></i></a>
											</p>
										</div>
									</div>
									<div class="">
										<h4 class="title text-center"><?=L::bike_name_bzenamsterdam;?></h4>
									</div>
								</div>


								<div class="portfolio-item">
									<div class="portfolio-image effect social-links">
										<img src="images_bikes/conway_cairon-t-200-se-500_f_mini.jpg" alt="CONWAY Cairon">
										<div class="image-box-content">
											<p>
												<a href="offre.php?brand=conway&model=cairon t 200 se 500&frameType=f"><i class="fa fa-eye"></i></a>
											</p>
										</div>
									</div>
									<div class="">
										<h4 class="title text-center"><?=L::bike_name_conwaycairont200mixed;?></h4>
									</div>
								</div>

								<div class="portfolio-item">
									<div class="portfolio-image effect social-links">
										<img src="images_bikes/ahooga_modular-bike-low-step_f_mini.jpg" alt="AHOOGA Modular">
										<div class="image-box-content">
											<p>
												<a href="offre.php?brand=ahooga&model=modular bike hybrid&frameType=h"><i class="fa fa-eye"></i></a>
											</p>
										</div>
									</div>
									<div class="">
										<h4 class="title text-center"><?=L::bike_name_ahoogamodular;?></h4>
									</div>
								</div>

								<div class="portfolio-item">
									<div class="portfolio-image effect social-links">
										<img src="images_bikes/conway_cairon-t-200-se-500_m_mini.jpg" alt="AHOOGA Modular">
										<div class="image-box-content">
											<p>
												<a href="offre.php?brand=conway&model=cairon%20t%20200%20se%20500&frameType=m"><i class="fa fa-eye"></i></a>
											</p>
										</div>
									</div>
									<div class="">
                                        <h4 class="title text-center"><?=L::bike_name_conwaycairont200mixed;?></h4>
									</div>
								</div>


							</div>
						</div>
						<!-- END : SELECTION -->
					</div>
				</div>
			</section>
			<!-- END: SECTION FLOTTE -->

			<!-- SECTION GESTION FLOTTE -->
			<section class="p-b-0">
				<div class="container">
					<div class="row" style="background-color: white;">
					<!--
						<div class="col-md-4">
							<img src="images/Borne.jpg" class="img-responsive img-rounded" alt="MyBORNE - gestion des clés de votre flotte">
						</div>
						<div class="col-md-8">
					-->
							<!-- <hr class="space"> -->
					<!--
							<h1 class="text-green">GESTION DE LA FLOTTE</h1>
							<p class="text-justify">Gérer une flotte de vélos partagés demande de l’organisation et du temps ! MyKAMEO a été développé pour permettre à nos clients de profiter de leurs vélos sans devoir s’en soucier.</p>

							<p class="text-justify">La plateforme MyKAMEO est une solution IT de gestion qui permet à chaque utilisateur de se connecter sur un espace sécurisé, de réserver un vélo de la flotte partagée de l’entreprise et de donner du feedback quant à l’état et l’utilisation de celui-ci. Elle permet également au Fleet Manager de contrôler la flotte, les réservations et de paramétrer l’ensemble. Des statistiques mensuelles sur l’utilisation des vélos et leur état lui sont envoyées automatiquement afin de pouvoir suivre facilement l’évolution du projet vélo !</p>

							<p class="text-justify"">La demande d’un entretien, la gestion des factures ou encore le remplissage d’une déclaration de vol y sont entièrement automatisés.</p>
				-->
							<!-- <h1 class="text-green text-center fr">GESTION DE LA FLOTTE</h1>
							<h1 class="text-green text-center en">GESTION DE LA FLOTTE</h1>
							<h1 class="text-green text-center nl">GESTION DE LA FLOTTE</h1>	-->
			 				<div class="col-md-7"><br><br>
									<h4 class="col-md-6 text-center"><?=L::manage_fleet_secureacces;?></h4><br>
                                    <h4 class="col-md-6 text-center"><?=L::manage_fleet_keymanage;?></h4><br>
									<h4 class="col-md-6 text-center"><?=L::manage_fleet_maintrequest;?></h4><br>
                                    <h4 class="col-md-6 text-center"><?=L::manage_fleet_batterycharge;?></h4><br>
									<h4 class="col-md-6 text-center"><?=L::manage_fleet_accessoriesacces;?></h4><br>
                                    <h4 class="col-md-6 text-center"><?=L::manage_fleet_bikereserve;?></h4><br>
									<h4 class="col-md-6 text-center"><?=L::manage_fleet_usermanage;?></h4><br>
                                    <h4 class="col-md-6 text-center"><?=L::manage_fleet_termsofuse;?></h4>
									<h4 class="col-md-6 text-center"><?=L::manage_fleet_monitoring;?></h4><br>
							</div>
							<div class="col-md-5">
							<h1 class="text-green text-center"><?=L::manage_fleet_title;?></h1>
								<p class="text-justify background-white fr">Vous n’avez pas envie de vous tracasser de tout cela, vous voulez juste pouvoir prendre un vélo et l’utiliser?<br> Ça tombe bien, nous avons développé un système <strong>simple</strong>, <strong>connecté</strong> et <strong>intelligent</strong> pour la sécurisation et la bonne gestion des vélos partagés.</p>
								<p class="text-justify background-white en">You don't want to worry about all of this, you just want to be able to take a bicycle and use it?<br> No problem, we developed a <strong>smart</strong>, <strong>simple</strong> and <strong>connected</strong> system for securing and managing shared bikes.</p>
								<p class="text-justify background-white nl">U wilt zich hier geen zorgen over maken, u wilt gewoon een fiets kunnen pakken en gebruiken?<br> Geen probleem, we hebben een <strong>eenvoudig</strong>, <strong>slim</strong> en <strong>verbonden</strong> systeem ontwikkelt voor het beveiligen en beheren van deelfietsen.</p>

								<p class="text-justify background-white fr">Celui-ci fonctionne via une plateforme de réservation en ligne et un boitier connecté sécurisant l’accès aux clés. Le tout est paramétrable pour fonctionner selon votre politique interne. Demande d’entretien, monitoring et statistiques d’utilisation, tout y est intégré.</p>
								<p class="text-justify background-white en">Celui-ci fonctionne via une plateforme de réservation en ligne et un boitier connecté sécurisant l’accès aux clés. Le tout est paramétrable pour fonctionner selon votre politique interne. Demande d’entretien, monitoring et statistiques d’utilisation, tout y est intégré.</p>
								<p class="text-justify background-white nl">Celui-ci fonctionne via une plateforme de réservation en ligne et un boitier connecté sécurisant l’accès aux clés. Le tout est paramétrable pour fonctionner selon votre politique interne. Demande d’entretien, monitoring et statistiques d’utilisation, tout y est intégré.</p>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- END: SECTION GESTION FLOTTE -->

			<!-- CALL TO ACTION -->
			<div class="jumbotron jumbotron-center jumbotron-fullwidth background-dark text-light">
			  <div class="container">
			    <h3><?=L::action_test1month;?></h3>
			    <p class="fr">Nous vous proposons d'essayer nos solutions avant de vous engager.<br>Contactez-nous pour réserver votre essai.</p>
			    <p class="en">Nous vous proposons d'essayer nos solutions avant de vous engager.<br>Contactez-nous pour réserver votre essai.</p>
			    <p class="nl">Nous vous proposons d'essayer nos solutions avant de vous engager.<br>Contactez-nous pour réserver votre essai.</p>
		   		<div><a class="button large green button-3d effect icon-left" href="contact.php"><?=L::action_btncontact;?><span><i class="fa fa-send"></i></span></a></div>
			</div>
		</div>

			<!--END: CALL TO ACTION -->

			<!-- SECTION MAINTENANCE -->
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<!-- <hr class="space"> -->
							<h1 class="text-green"><?=L::insurance_title;?></h1>
							<p class="text-justify"><?=L::insurance_uppertext;?></p>

							<p class="text-justify fr">Les vélos ont une valeur non négligeable, il est important d’être couvert contre le vol ou toute autre dégradation. Pour cela, KAMEO Bikes collabore avec Aedes et Dedale afin d’offrir à ses clients l’assurance Omnium la plus complète et la plus flexible actuellement disponible sur le marché : <strong class="text-green">La P-Vélo</strong>.
							<p class="text-justify en">Les vélos ont une valeur non négligeable, il est important d’être couvert contre le vol ou toute autre dégradation. Pour cela, KAMEO Bikes collabore avec Aedes et Dedale afin d’offrir à ses clients l’assurance Omnium la plus complète et la plus flexible actuellement disponible sur le marché : <strong class="text-green">La P-Vélo</strong>.
							<p class="text-justify nl">Les vélos ont une valeur non négligeable, il est important d’être couvert contre le vol ou toute autre dégradation. Pour cela, KAMEO Bikes collabore avec Aedes et Dedale afin d’offrir à ses clients l’assurance Omnium la plus complète et la plus flexible actuellement disponible sur le marché : <strong class="text-green">La P-Vélo</strong>.

						</div>
						<div class="col-md-4">
							<img src="images/pvelo.png" class="img-responsive img-rounded" alt="PVELO - l'assurance vélo">
						</div>
					</div>
				</div>
			</section>
			<!-- END: SECTION MAINTENANCE -->

			<!-- SECTION INFRASTRUCTURES -->
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
						<!-- <h1 class="text-green fr">INSTALLATION D'INFRASTRUCTURES</h1>
						<h1 class="text-green en">INSTALLATION D'INFRASTRUCTURES</h1>
						<h1 class="text-green nl">INSTALLATION D'INFRASTRUCTURES</h1> -->
							<img src="images/infrastructure.png" class="img-responsive img-rounded" alt="Schéma d'une infrastructure réalisable par KAMEO Bikes">
						</div>
						<div class="col-md-6">
							<!-- <hr class="space"> -->
							<h1 class="text-green"><?=L::infra_title;?></h1>
							<p class="text-justify"><?=L::infra_subtitle;?><br><?=L::infra_text;?></p>

							<p class="text-justify"><?=L::infra_contact;?></p>

						</div>
					</div>
				</div>
			</section>
			<!-- END: SECTION INFRASTRUCTURES -->

			<!-- SECTION LOCATION TOUT INCLUS -->
			<!--
			<section class="p-b-0">
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="text-green">LOCATION TOUT INCLUS OU ACHAT?</h1>
							<p>Vous choisirez la formule qui vous convient le mieux.</p>
							<a class="button green button-3d effect fill-vertical" href="location-tout-inclus.php"><span><i class="fa fa-balance-scale"></i>Comparer</span></a>
						</div>
					</div>
				</div>
			</section>
			-->
			<!-- END: SECTION LOCATION TOUT INCLUS -->


				<hr class="space">
			<!-- CALL TO ACTION -->
				<div class="jumbotron jumbotron-center jumbotron-fullwidth text-light" style="background: url('images/fond_degrade3.jpg');" data-stellar-background-ratio="0.3">
				  <div class="container">
				    <h3><?=L::meet_title;?></h3>
				    <p><?=L::meet_pedal;?><br><?=L::meet_contact;?></p>
				    <div> <a class="button large black-light button-3d effect icon-left" href="contact.php"><span><i class="fa fa-send"></i><?=L::meet_btncontact;?></span></a> </div>
				</div>
			</div>

<!--END: CALL TO ACTION -->

<!-- Language management -->
<script type="text/javascript" src="js/language.js"></script>

	<div class="modal fade" id="newPassword" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3 id="fr">Mot de passe oublié</h3>
                        <h3 id="en">Password lost</h3>
                        <h3 id="nl">Wachtwoord kwijt</h3>
						<form id="widget-lostPassword-form" action="apis/Kameo/lostPassword.php" role="form" method="post">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="subject"  id="fr">Nouveau mot de passe</label>
                                    <label for="subject"  id="en">New pasword</label>
                                    <label for="subject"  id="nl">Nieuw wachtwoord</label>
                                    <input type="password" name="widget-lostPassword-form-new-password" class="form-control required" autocomplete="new-password">
                                </div>
                            </div>
                            <input type="text" class="hidden" id="widget-lostPassword-form-antispam" name="widget-lostPassword-form-antispam" value="" />
                            <?php
                            if(isset($_GET['hash'])){
                                ?>
                                <input type="text" class="hidden" id="widget-lostPassword-form-hash" name="widget-lostPassword-form-hash" value="<?php echo $_GET['hash'] ?>" />
                                <?php
                            }
                            else{
                                ?>
                                <input type="text" class="hidden" id="widget-lostPassword-form-hash" name="widget-lostPassword-form-hash"/>
                                <?php
                            }
                            ?>
                            <button  id="fr" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Envoyer</button>
                            <button  id="en" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Confirm</button>
                            <button  id="nl" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Verzenden</button>
                        </form>
							<script type="text/javascript">
                                jQuery("#widget-lostPassword-form").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                console.log(text);
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
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
		</div>
	</div>
	</div>
	<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->

	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>

<?php
    if(isset($_GET['hash'])){
	?>
	<script type="text/javascript">
	$('#newPassword').modal('toggle');
	</script>

<?php
	checkHash($GET['HASH']);
}
?>


<?php
    if(isset($_GET['deconnexion']) && $_GET['deconnexion']==true ){
		if ($_SESSION['langue']=='en')
		{
			$message = "You have been disconnected due to inactivity";
		}
		elseif ($_SESSION['langue']=='nl')
		{
			$message = "U bent afgesloten vanwege inactiviteit";
		}
		else
		{
			$message = "Vous avez été déconnecté pour cause d\'inactivité";
		}
	?>
        <script type="text/javascript">
			test="<?php echo $message;?>";
			console.log("deconnexion:"+test);
            $.notify({
                message: '<?php echo $message; ?>'
            }, {
                type: 'danger'
            });
        </script>

<?php
}
?>

</body>

</html>
