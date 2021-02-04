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
<!-- SHOP PRODUCT PAGE -->
<section id="product-page" class="product-page p-b-0">
	<div class="container">
		<div class="product">
			<div class="row">
				<div class="col-md-5">
					<div class="product-image">
						<div class="carousel" data-carousel-col="1" data-lightbox-type="gallery">
							<img alt="Shop product image!" src="images_bikes/bonsplans/conway_ets370_f2_1.jpg">
							<img alt="Shop product image!" src="images_bikes/bonsplans/conway_ets370_f2_2.jpg">
							<img alt="Shop product image!" src="images_bikes/bonsplans/conway_ets370_f2_3.jpg">

						</div>
					</div>
				</div>


				<div class="col-md-7">
					<div class="product-description">
						<div class="product-category">Réf. : CW-370f2</div>
						<div class="product-title">
							<h3><a href="#">Conway</a></h3>
							<p>Ets 370</p>
							<p><strong>Cadre Femme - Taille S</strong></p>
							<p><strong>Kilomètres parcourus : 590,4 km</strong></p>
						</div>
						<div class="product-price"><ins class="text-green">1652€</ins> htva
						<p class="right text-right">Location tout inclus:<br> <strong class="text-green">82€</strong> / mois</p>
						</div>
						

						<div class="seperator m-b-10"></div>
						<h4>Caractéristiques techniques</H4>
						<p>Voir le <a href="https://conway-bikes.de/modell/ets-370/" target="_blank">site de la marque</a>.</p>
						
					<div class="m-t-20">
						<a class="button color button-3d rounded effect icon-left text-light" data-target="#acheter" data-toggle="modal" href="#"><span><i class="fa fa-shopping-cart"></i> Acheter</span></a>
					</div>

				</div>
				
				<a class="read-more" href="bons-plans.php"><i class="fa fa-long-arrow-left"></i> Tous les articles</a>

			</div>
		</div>
	</div>
</section>
<!-- END: SHOP PRODUCT PAGE -->        

<div class="modal fade" id="acheter" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h2 id="modal-label-2" class="modal-title">Acheter un véo</h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="text-green">Ce vélo vous intéresse?</h4>
						 <form id="widget-offerBonsPlan" action="apis/Kameo/offer_bonsplan_form.php" role="form" method="post">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="name"><?= L::offre_nom; ?></label>
                                        <input type="text" aria-required="true" name="name" class="form-control required name">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="firstName"><?= L::offre_prenom; ?></label>
                                        <input type="text" aria-required="true" name="firstName" class="form-control required name">

                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="email"><?= L::offre_mail; ?></label>
                                        <input type="email" aria-required="true" name="email" class="form-control required email">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="phone"><?= L::offre_phone; ?></label>
                                        <input type="phone" aria-required="true" name="phone" class="form-control required phone" placeholder="+32">
                                    </div>
                                     <div class="form-group col-sm-6">
                                        <input type="text" class="hidden" id="offer" name="offer" value="Conway ets-370" />
                                    </div>
                                </div>
                                <button class="button green button-3d rounded effect" type="submit" id="form-submit"><?= L::offre_askoffer_btn; ?></button>
                            </form>
                             <script type="text/javascript">
                                jQuery("#widget-offerBonsPlan").validate({
                                    submitHandler: function(form) {
                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });
                                                    $(form)[0].reset();

                                                    gtag('event', 'send', {
                                                        'event_category': 'mail',
                                                        'event_label': 'offre.php',
                                                        'config': 'UA-108429655-1'
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

                             <br/>
						<p>Si vous avez besoin de plus d'information envoyez nous un mail à l'adresse suivante <a href="mailto:info@kameobikes.com?subject=Achat CW-370f2 femme&body=Merci de renseigner vos coordonnées: Nom, Prénom, mail, téléphone. Nous vous contacterons dès que possible.">info@kameobikes.com</a> en nous communiquant vos coordonnées complètes.</p>
						<p><strong class="text-green">Ou</strong> téléphonez au (+32) 498 72 75 48</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="pull-left">
					<button data-dismiss="modal" class="btn btn-b" type="button">Fermer</button>
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

	<!-- Custom js file -->
	<script src="js/language.js"></script>



</body>

</html>

