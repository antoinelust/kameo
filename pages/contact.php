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
<script type="text/javascript">

    window.addEventListener("DOMContentLoaded", function(event) {
        $('#widget-contact-form input[name=type]').change(function(){
            if($('#widget-contact-form input[name=type]:checked').val()=="particulier"){
                $('.entreprise').addClass("hidden");
                $('#widget-contact-form input[name=entreprise]').removeClass("required");
            }else{
                $('.entreprise').removeClass("hidden");
                $('#widget-contact-form input[name=entreprise]').addClass("required");
            }
        });


    })

</script>




 <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                    	<h1 class="text-green"><?=L::contact_title;?></h1>
                        <p><?=L::contact_subtitle;?></p>
                        <div class="m-t-30">
                            <form id="widget-contact-form" action="apis/Kameo/contact_form.php" role="form" method="post">
                                <div class="row">
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
                                    <div class="form-group col-sm-6">
		                                <div class="particulier">
											<label><input type="radio" name="type" value="particulier" checked><?=L::contact_particulier;?></label>
										</div>
										<div class="professionnel">
											<label><input type="radio" name="type" value="professionnel"><?=L::contact_pro;?></label>
										</div>
									</div>
									<div class="form-group col-sm-12 entreprise hidden">
	                                	<label for="entreprise"><?=L::contact_society;?></label>
	                                	<input type="text" aria-required="true" name="entreprise" class="form-control">
	                                </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="subject"><?=L::contact_subject;?></label>
                                        <input type="text" name="subject" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message"><?=L::contact_message;?></label>
                                    <textarea type="text" name="message" rows="5" class="form-control required" placeholder="Votre message"></textarea>
                                </div>

                                <div class="g-recaptcha" data-sitekey="6LfqMFgUAAAAADlCo3L6lqhdnmmkNvoS-kx00BMi"></div>

                                <input type="text" class="hidden" name="antispam" value="" />

                                <button class="button green button-3d effect fill-vertical" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp<?=L::contact_send_btn;?></button>
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
                    <div class="col-md-6">
                    	<h1><?=L::contact_findus;?></h1>
                        <div class="row">
                            <div class="col-md-6">
                                <address>
                                  <strong>KAMEO Bikes</strong><br>
                                  Rue de la Brasserie 8,<br>
                                  4000 Liège<br>
                                  Belgique<br>
                                  </address>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1264.7922885746027!2d5.590069958227898!3d50.65340589487594!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c0fa03fbcae78b%3A0xdb098129a125e630!2sKAMEO%20Bikes!5e0!3m2!1sfr!2sbe!4v1584177978200!5m2!1sfr!2sbe" width="400" height="300" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                            </div>
                        </div>

						<br>
						<!--
						<div id="slider">
							<div id="slider-carousel">

								<img src="images/shopvelo1.jpg" class="img-responsive img-rounded" alt="">
								<img src="images/shopvelo2.jpg" class="img-responsive img-rounded" alt="">
								<img src="images/shopvelo3.jpg" class="img-responsive img-rounded" alt="">

							</div>
						</div>
						-->
                        <div class="social-icons social-icons-large social-icons-colored">
							<ul>
								<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
								<li class="social-linkedin"><a href="https://www.linkedin.com/company/kameobikes/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
							</ul>
						</div>

                    </div>
                </div>
            </div>
        </section>
        <!-- END: CONTENT -->

        <!-- TEAM -->
		<section id="section5" class="background-grey">
			<div class="container">
				<h1 class="text-green"><?=L::contact_team;?></h1>
				<!--
					<p class="fr">KAMEO Bikes a été créé en 2017 par 4 jeunes désireux de proposer un service complet pour la mobilité en entreprise.<br> Nous constations tous les jours les mêmes problèmes de trafic, retard et ne trouvions pas la solution que nous avions en tête.<br> <strong>Nous avons alors décidé de la créer.</strong></p>
					<p class="en">KAMEO Bikes was created in 2017 by 4 young people eager to prove that it is possible to design innovative E-Bikes adapted to the tastes and needs of each.</p>
					<p class="nl">KAMEO Bikes is in 2017 opgericht door 4 jonge mensen die graag willen aantonen dat het mogelijk is om innovatieve elektrische fietsen te ontwerpen die aangepast zijn op de smaken en behoeften van elk. </p>
					-->
				<div class="row">
					<div class="col-md-3">
						<div class="image-box circle-image small"> <img class="" src="images/Jams.jpg" alt="Julien - Responsable Technique vélo entretien mobilité"> </div>
						<div class="image-box-description text-center">
							<h4><?=L::contact_team_julien;?></h4>
							<p class="subtitle"><?=L::contact_team_manager;?></p>
							<hr class="line">
							<!--
							<div class="fr">Aussi loin qu'on s'en souvienne, Julien a toujours été passionné de vélo. Habile mécanicien et ingénieur industriel, il se tient au courant de toutes les nouveautés afin de pouvoir vous conseiller le vélo qui répondra au mieux à votre besoin.</div>
							<div class="en">As far as we can go back, Julien has always been passionate about cycling. Skilled mechanic and industrial engineer, he knows inside out the technical details of each of our bikes and constantly strives to improve them. </div>
							<div class="nl">Voor zover we terug kunnen gaan, heeft Julien altijd een passie gehad voor fietsen. Bekwaam mechanicus en industriële ingenieur, hij kent de technische details van elk van onze fietsen en streeft om ze te verbeteren. </div>
							-->

						</div>
					</div>
					<div class="col-md-3">
						<div class="image-box circle-image small"> <img class="" src="images/PY.jpg" alt="Pierre-Yves - Responsable stratégique vélo entretien mobilité"> </div>
						<div class="image-box-description text-center">
							<h4><?=L::contact_team_pierreyves;?></h4>
							<p class="subtitle"><?=L::contact_team_businessmanager;?></p>
							<hr class="line">
							<!--
							<div class="fr">Quel que soit le sport ou le terrain, Pierre-Yves veut toujours être en mouvement. La monotonie de ses transports urbains lui a donné l'envie de créer KAMEO Bikes. </div>
							<div class="en">Whatever the sport whatever the field, Pierre-Yves always wants to be in motion. The monotony of his urban transports made him want to create KAMEO Bikes. </div>
							<div class="nl">Wat de sport of het terrein betreft, zal Pierre-Yves altijd in beweging zijn. De monotoon van zijn stedelijk vervoer zorgde ervoor dat hij KAMEO Bikes wilde maken. </div>
							-->

						</div>
					</div>
					<div class="col-md-3">
						<div class="image-box circle-image small"> <img class="" src="images/Lust.jpg" alt="Antoine - Responsable Financier vélo entretien mobilité"> </div>
						<div class="image-box-description text-center">
							<h4><?=L::contact_team_antoine;?></h4>
							<p class="subtitle"><?=L::contact_team_itmanager;?></p>
							<hr class="line">
							<!--
							<div  class="fr">Peu importe la technologie, Antoine est un éternel curieux qui veut tout comprendre et maitriser. Ingénieur civil, il est en charge des aspects financiers du projet. </div>
							<div  class="en">Challenge him on one something and Antoine will want to understand and master it. As a civil engineer, he is in charge of the financial aspects of the project. </div>
							<div  class="nl">Wat de technologie ook is, Antoine is een eeuwig nieuwsgierig dat alles alles wil begrijpen en beheersen. Hij is verantwoordelijk voor de financiële aspecten van het project. </div>
							-->

						</div>
					</div>
					<div class="col-md-3">
						<div class="image-box circle-image small"> <img class="" src="images/Thib.jpg" alt="Thibaut - Responsable marketing vélo entretien mobilité"> </div>
						<div class="image-box-description text-center">
							<h4><?=L::contact_team_thibaut;?></h4>
							<p class="subtitle"><?=L::contact_team_marketingmanager;?></p>
							<hr class="line">
							<!--
							<div class="fr">En quelques clics sur son ordi, Thibaut transforme n'importe quel schéma en un design simple et élégant. Graphiste, il s'assure que la qualité visuelle de KAMEO Bikes soit à la hauteur de la qualité de son service.</div>
							<div class="en">Just give Thibaut 5 minutes with his computer and he will transform any random sketch in a beautiful and clean design. As graphic designer, he makes sure that the visual quality of our products is as good as their technical quality. </div>
							<div class="nl">Met een paar klikken op zijn computer verandert Thibaut elke schets in een schoon en elegant ontwerp. Als graficus, zorgt hij ervoor dat de visuele kwaliteit van KAMEO Bikes-producten aan hun technische kwaliteit voldoet.</div>
							-->
							<br>

						</div>
					</div>

					<div class="col-md-6 center">
						<div class="image-box circle-image small"> <img class="" src="images/Justine.jpg" alt=""> </div>
							<div class="image-box-description text-center">
								<h4><?=L::contact_team_justine;?></h4>
								<hr class="line">
								<div><?=L::contact_team_responsableadmin;?></div>
							</div>
					</div>
					
					<div class="col-md-6 center">
						<div class="image-box circle-image small"> <img class="" src="images/you.jpg" alt=""> </div>
							<div class="image-box-description text-center">
								<h4><?=L::contact_team_you;?></h4>
								<hr class="line">
								<div><?=L::contact_join_team;?></div>
							</div>
					</div>

				</div>
			</div>
		</section>
		<div id="formulaire"">
		</div>
		<!-- END: TEAM -->
		<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->


	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Custom js file -->
	<script src="js/language.js"></script>



</body>

</html>
