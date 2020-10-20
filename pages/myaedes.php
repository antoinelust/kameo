<!DOCTYPE html>
<html lang="fr">
<?php
	include 'include/head_aedes.php';
?>
<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include 'include/topbar_aedes.php'; ?>
		<?php include 'include/header_aedes.php'; ?>
<section>
	<div class="container">
		<div class="row">
			<div id="tabs-01111" class="tabs radius color">
				<ul class="tabs-navigation">
					<li class="active_blue"><a href="#informations"><i class="fa fa-user"></i>Vos informations</a> </li>
					<li><a href="#contrats"><i class="fa fa-folder-open-o"></i>Vos contrats</a> </li>
					<li><a href="#declaration" class="text-center"><i class="fa fa-wrench"></i>Déclarer un sinistre ou un vol</a> </li>
				</ul>
				<div class="tabs-content">
					<div class="tab-pane active" id="informations">
						<h2 class="text-blue">Vos informations</h4>
						<div class="col-md-4">
							<p class="text-dark"> Nom: <em class="">Mativa</em></p>
							<p class="text-dark"> Prénom: <em class="">Thibaut</em></p>
							<p class="text-dark"> Adresse: <em class="">Ch. de Tongres 478, 4000 Rocourt</em></p>
							<p class="text-dark"> Numéro de téléphone: <em class="">0499 62 33 06</em></p>
							<p class="text-dark"> Mot de passe : <em class="">******</em></p>
							<a class="button small color button-3d rounded effect icon-left text-light" data-target="#actualiser" data-toggle="modal" href="#"><span><i class="fa fa-pencil"></i>Actualiser mes informations</span></a>
						</div>
					</div>					
					<div class="tab-pane" id="contrats">
						<h2 class="text-blue">Vos contrats</h2>
						<div class="col-md-4">
							<img src="../images_bikes/157.jpg" alt="" style="width: 100%;">
							<p>Conway Cairon T200 SE 500<br>
							Taille : M<br>
							Date de début de contrat : 21/09/2020<br>
							Date de fin de contrat : 20/09/2021</p>
							<a class="button small color button-3d rounded effect icon-left text-light" data-target="#actualiser" data-toggle="modal" href="#"><span><i class="fa fa-recycle"></i>Renouveler mon contrat</span></a>
							<a class="button small color button-3d rounded effect icon-left text-light" data-target="#actualiser" data-toggle="modal" href="#"><span><i class="fa fa-download"></i>Télécharger mon contrat</span></a>
						</div>
						<div class="col-md-4">
							<img src="../images_bikes/157.jpg" alt="" style="width: 100%;">
							<p>Conway Cairon T200 SE 500<br>
							Taille : M<br>
							Date de début de contrat : 21/09/2020<br>
							Date de fin de contrat : 20/09/2021</p>
							<a class="button small color button-3d rounded effect icon-left text-light" data-target="#actualiser" data-toggle="modal" href="#"><span><i class="fa fa-recycle"></i>Renouveler mon contrat</span></a>
							<a class="button small color button-3d rounded effect icon-left text-light" data-target="#actualiser" data-toggle="modal" href="#"><span><i class="fa fa-download"></i>Télécharger mon contrat</span></a>
						</div>
						<div class="col-md-4">
							<img src="../images_bikes/157.jpg" alt="" style="width: 100%;">
							<p>Conway Cairon T200 SE 500<br>
							Taille : M<br>
							Date de début de contrat : 21/09/2020<br>
							Date de fin de contrat : 20/09/2021</p>
							<a class="button small color button-3d rounded effect icon-left text-light" data-target="#actualiser" data-toggle="modal" href="#"><span><i class="fa fa-recycle"></i>Renouveler mon contrat</span></a>
							<a class="button small color button-3d rounded effect icon-left text-light" data-target="#actualiser" data-toggle="modal" href="#"><span><i class="fa fa-download"></i>Télécharger mon contrat</span></a>
						</div>
						<div class="col-md-4">
							<img src="../images_bikes/157.jpg" alt="" style="width: 100%;">
							<p>Conway Cairon T200 SE 500<br>
							Taille : M<br>
							Date de début de contrat : 21/09/2020<br>
							Date de fin de contrat : 20/09/2021</p>
							<a class="button small color button-3d rounded effect icon-left text-light" data-target="#actualiser" data-toggle="modal" href="#"><span><i class="fa fa-recycle"></i>Renouveler mon contrat</span></a>
							<a class="button small color button-3d rounded effect icon-left text-light" data-target="#actualiser" data-toggle="modal" href="#"><span><i class="fa fa-download"></i>Télécharger mon contrat</span></a>
						</div>
					</div>
					<div class="tab-pane" id="declaration">
						<h2 class="text-blue">Déclarer un sinistre ou un vol</h2>
						<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
					</div>
				</div>
			</div>	
		</div>	
	</div>	
	
</section>

<div class="modal fade" id="actualiser" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 id="modal-label-3" class="modal-title">Actualiser mes informations</h4>
			</div>
			<div class="modal-body">
				<p> FORMULAIRE A AJOUTER ?? </p>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-b" type="button">Fermer</button>
				<button class="btn btn-b" type="button">Enregistrer</button>
			</div>
		</div>
	</div>
</div>

<?php include 'include/footer.php' ?>

	</div>
	<!-- END: WRAPPER -->

	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>



</body>

</html>

