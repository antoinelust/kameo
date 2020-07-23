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
							<img alt="Shop product image!" src="images_bikes/bonsplans/hnf_xd2_h1.jpg">
							<img alt="Shop product image!" src="images_bikes/bonsplans/hnf_xd2_h2.jpg">
							<img alt="Shop product image!" src="images_bikes/bonsplans/hnf_xd2_h3.jpg">
							<img alt="Shop product image!" src="images_bikes/bonsplans/hnf_xd2_h4.jpg">

						</div>
					</div>
				</div>


				<div class="col-md-7">
					<div class="product-description">
						<div class="product-category">Réf. : HNF_XD2</div>
						<div class="product-title">
							<h3><a href="#">HNF Nicolai</a></h3>
							<p>XD2</p>
							<p><strong>Cadre Homme - Taille M</strong></p>
							<p><strong>Kilomètres parcourus : 202 km</strong></p>
						</div>
						<div class="product-price"><ins class="text-green">3140€</ins>
						<p class="right text-right">Location tout inclus:<br> <strong class="text-green">127€</strong> / mois</p>
						</div>
						

						<div class="seperator m-b-10"></div>
						<h4>Caractéristiques techniques</H4>
						<p>Voir le <a href="https://www.hnf-nicolai.com/ebike/xd-2-urban-e-bike-2/" target="_blank">site de la marque</a>.</p>
						
					<div class="m-t-20">
						<a class="button color button-3d rounded effect icon-left text-light" data-target="#acheter" data-toggle="modal" href="#"><span><i class="fa fa-shopping-cart"></i> Acheter</span></a>
					</div>

				</div>
				
				<a class="read-more" href="bonsplans.php"><i class="fa fa-long-arrow-left"></i> Tous les articles</a>

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
						<p>Envoyez nous un mail à l'adresse suivante <a href="mailto:info@kameobikes.com?subject=Achat HNF_XD2 homme&body=Merci de renseigner vos coordonnées: Nom, Prénom, mail, téléphone. Nous vous contacterons dès que possible.">info@kameobikes.com</a> en nous communiquant vos coordonnées complètes.</p>
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

