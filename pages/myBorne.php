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

		<section>
			<div class="container">
				<h1 class="text-green">MyBorne</h1><sup>par KAMEO Bikes</sup>
					<br><br>
				<div class="col-md-6">
					<h2 class="text-green">Introduction</h2>
						<p class="text-dark">Dans son objectif d’amélioration continue de la mobilité Kameo vous présente son produit de gestion de flotte : MyBorne<br><br>
						Cette borne de gestion de clefs est l’outil parfait pour l’utilisation et la gestion de vos clefs de manière optimale. En fonction de vos besoins, différentes solutions s’offrent à vous :</p>
					<div class="space"></div>
				</div>

				<div class="col-md-3">
					<img src="images/Borne_Web_Out.jpg" class="img-responsive img-rounded">
					<div class="space"></div>
				</div>

				<div class="col-md-3">
					<img src="images/Borne_Web_In.jpg" class="img-responsive img-rounded">
					<div class="space"></div>
				</div>

				<div class="space"></div>

				<div id="tabs-05c" class="tabs color">
					<ul class="tabs-navigation">
						<li class="active"><a href="#gestion"><i class="fa fa-cogs"></i>Gestion et optimisation de flotte</a> </li>
						<li><a href="#velos"><i class="fa fa-bicycle"></i>Vélos partagés</a> </li>
					</ul>
					<div class="tabs-content">
						<div class="tab-pane active" id="gestion">
						<h2 class="text-green">Gestion et optimisation de flotte</h2>
						<h3 class="text-green">Comment ça marche ?</h3>
						<p class="text-dark">Vous avez une borne physique qui contient toutes les clefs de vos véhicules. Cette borne est reliée à un serveur qui permet, à distance, d’assigner quelle clef sera pour quel employé. Cet assignement de véhicules aux employés sera fait via un algorithme en fonction de différents facteurs : </p>
						<ul class="text-dark">
							<li><strong>L’état du véhicule </strong> : Si un de vos véhicule n’est plus en état pour rouler (trop vieux, accident, panne…), le fleet manager pourra simplement le mentionner dans le programme MyBorne et le véhicule en question sera automatiquement mis de côté par le programme et donc sa clef ne sera plus assignée à un chauffeur</li>
							<li><strong>La consommation du véhicule</strong> : : En fonction des données de consommation que le programme enregistrera à la fin de chaque tournée. Il sera possible d’évaluer la consommation des véhicules et donc d’assigner les véhicules de sorte à minimiser la consommation totale de votre flotte. Concrètement, les véhicules qui consomment le moins pourront être automatiquement assignés pour les tournées les plus longues</li>
							<li><strong>Le statut du véhicule</strong> :</li>
							<ul>
								<li><strong>le véhicule est en leasing</strong> : Si l’ensemble de votre flotte est en leasing, vous avez très certainement des contraintes kilométriques à ne pas dépasser. Avec MyBorne le programme assignera les véhicules de telle sorte que le nombre de kilomètres de ces derniers n’excèdent pas leurs plafonds ou du moins que s’ils doivent excéder ce plafond, les coûts soient minimisés. La borne vous permettra donc d’optimiser au mieux votre flotte et de minimiser vos surcoûts</li>
								<li><strong>Le véhicule n’est pas en leasing</strong> : Si votre flotte est composée de véhicules qui ne sont pas en leasing, MyBorne s’occupera d’optimiser l’assignement des véhicules pour que le nombre de kilomètre de chaque véhicule évolue à la même fréquence</li>
							</ul>
						</ul>
						<p class="text-green">Au-delà d’une simple boite à clefs, MyBorne est votre nouveau compagnon de gestion de flotte qui vous permettra d’optimiser l’utilisation de vos véhicules et les coûts qui en découlent</p>

						<h3 class="text-green">Fonctionnalités pour le fleet manager</h3>
						<p class="text-dark">Le fleet manager aura une vue complète en temps réel de la flotte de véhicule dont il est en charge. <br><br>
							De nombreux réglages personnalisables seront à sa disposition : </p>
						<ul class="text-dark">
							<li>L’heure à partir de laquelle les véhicules sont disponibles</li>
							<li>Mettre un véhicule de côté pour diverses raisons (panne, accident, entretien, vol.…)</li>
							<li>Et diverses autre applications possibles à définir avec vous</li>
						</ul><br>

						<h3 class="text-green">Fonctionnalités pour l'utilisateur du véhicule</h3>
						<p class="text-dark">Le but premier de cette borne est de nouveau de faciliter la gestion et l’utilisation de la flotte. Pour les chauffeurs rien de plus simple. Ils recevront un code tous les matins. Ce code va les assigner à un véhicule et leur permettra de débloquer la clef du véhicule. Une fois leur tournée finie, ils rentrent la clef dans le boitier.</p>
						<div class="col-md-12">
							<h4 class="text-green">Prendre la clé d'un véhicule</h4>
							<div class="col-md-3">
								<img src="images/Icones_Borne_mykameo.png" class="img-responsive img-thumbnail" alt="">
								<p class="text-dark text-center">Connectez-vous sur MyKameo pour voir le code qui vous a été attribué</p>
							</div>
							<div class="col-md-3">
								<img src="images/Icones_Borne_bornefermee.png" class="img-responsive img-thumbnail" alt="">
								<p class="text-dark text-center">Entrer le code reçu pour déverrouiller la borne</p>
							</div>
							<div class="col-md-3">
								<img src="images/Icones_Borne_borneouvertefull.png" class="img-responsive img-thumbnail" alt="">
								<p class="text-dark text-center">Prenez la clef du véhicule sous le led illuminé</p>
							</div>
							<div class="col-md-3">
								<img src="images/Icones_Borne_camion.png" class="img-responsive img-thumbnail" alt="">
								<p class="text-dark text-center">Vous pouvez partir en tournée</p>
							</div>
						</div>
						<div class="col-md-12">
							<h4 class="text-green">Rendre la clé d'un véhicule</h4>
							<div class="col-md-3">
								<img src="images/Icones_Borne_camion.png" class="img-responsive img-thumbnail" alt="">
								<p class="text-dark text-center">Retour au dépôt</p>
							</div>
							<div class="col-md-3">
								<img src="images/Icones_Borne_bornefermeebadge.png" class="img-responsive img-thumbnail" alt="">
								<p class="text-dark text-center">Ouvrez la borne en scannant le badge de votre clef</p>
							</div>
							<div class="col-md-3">
								<img src="images/Icones_Borne_borneouverteremise.png" class="img-responsive img-thumbnail" alt="">
								<p class="text-dark text-center">Remettez la clef du véhicule sous le led illuminé</p>
							</div>
							<div class="col-md-3">
								<img src="images/Icones_Borne_chack.png" class="img-responsive img-thumbnail" alt="">
								<p class="text-dark text-center">Le tour est joué</p>
							</div>
						</div>
						<p class="text-dark">Pour les chauffeurs il sera également possible de choisir le véhicule de leur préférence. Le programme My Borne, pourra ainsi mettre à disposition de chaque travailleur, un véhicule qui se rapproche le plus de celui de leur préférence.</p>
						<p class="text-dark"><strong>Vous l’aurez compris, MyBorne est un outil de travail complet. Ce système simple, connecté et intelligent vous assure une sécurité sans faille et une excellente gestion de votre flotte</strong></p>
						</div>



						<div class="tab-pane" id="velos">
							<h2 class="text-green">Vélos partagés</h2>
						    <p class="text-dark">Vous possédez une flotte de vélo partagés dans votre entreprise et souhaiter mettre en place un système qui permet d’avoir un meilleur contrôle sur la gestion de vos vélos ?<br>
									La solution MyBorne assurera une parfaite gestion de vos deux roues.</p>

							<h3 class="text-green">Comment ça marche ?</h3>
							<p class="text-dark">Chacun des vélos de votre flotte sera verrouillé avec un cadenas. Pour pouvoir l’utiliser, il faut la clef du cadenas. C’est là que MyBorne intervient.<br>
									Via notre plateforme web vous pouvez réserver un vélo pour un créneau horaire. Une fois la réservation faite, un code vous sera envoyé. Avec ce code vous pourrez ouvrir la borne et récupérer la clef du cadenas du vélo que vous avez choisi.</p>

							<h3 class="text-green">Fonctionnalités pour le fleet manager</h3>
							<p class="text-dark">Le fleet manager a une vue d’ensemble sur la gestion des réservations des vélos. Il peut y insérer de nombreux critères pour éviter les abus et les débordements : </p>
							<ul class="text-dark">
								<li>Durée maximale de réservation</li>
								<li>Nombre de réservation par mois</li>
								<li>Tranche horaire de disponibilité des vélos</li>
							</ul>
							<br>

							<h3 class="text-green">Fonctionnalités pour l'utilisateur du vélo</h3>
							<p class="text-dark">L’utilisateur, lui, aura accès aussi à la plateforme MyKameo sur laquelle il pourra réserver son vélo en fonction des disponibilités et des critères imposés par le fleet manager. Il aura également un aperçu de son historique des réservations.</p>
							<p class="text-dark">Lorsqu’un utilisateur veut réserver un vélo, il se connecte à la plateforme MyKameo. Ensuite il choisit le créneau horaire où il désire réserver un vélo. Une liste des vélos disponibles apparaitra et il pourra sélectionner le vélo de son choix.<br>
								Une fois la réservation terminée, l’utilisateur reçoit un code qui lui permet de d’obtenir la clef du vélo. Il récupère la clef dans la borne, et peut utiliser le vélo. <br>
								A son retour, il doit ranger le vélo à sa place, scanner la borne avec la clef pour l’ouvrir, ranger la clef à l’endroit imparti, refermer la borne et le tour est joué. </p>

							<div class="col-md-12">
								<h4 class="text-green">Prendre la clé d'un vélo</h4>
								<div class="col-md-3">
									<img src="images/Icones_Borne_mykameo.png" class="img-responsive img-thumbnail" alt="">
									<p class="text-dark text-center">Connectez-vous sur MyKameo pour voir le code qui vous a été attribué</p>
								</div>
								<div class="col-md-3">
									<img src="images/Icones_Borne_bornefermee.png" class="img-responsive img-thumbnail" alt="">
									<p class="text-dark text-center">Entrer le code reçu pour déverrouiller la borne</p>
								</div>
								<div class="col-md-3">
									<img src="images/Icones_Borne_borneouvertefull.png" class="img-responsive img-thumbnail" alt="">
									<p class="text-dark text-center">Prenez la clef du vélo sous le led illuminé</p>
								</div>
								<div class="col-md-3">
									<img src="images/Icones_Borne_velo.png" class="img-responsive img-thumbnail" alt="">
									<p class="text-dark text-center">Déverrouillez le cadenas du vélo à l’aide de la clef reçue</p>
								</div>
							</div>
							<div class="col-md-12">
								<h4 class="text-green">Rendre la clé d'un vélo</h4>
								<div class="col-md-3">
									<img src="images/Icones_Borne_velo.png" class="img-responsive img-thumbnail" alt="">
									<p class="text-dark text-center">Verrouillez votre vélo à l’aide de votre clef</p>
								</div>
								<div class="col-md-3">
									<img src="images/Icones_Borne_bornefermeebadge.png" class="img-responsive img-thumbnail" alt="">
									<p class="text-dark text-center">Ouvrez la borne en scannant le badge de votre clef</p>
								</div>
								<div class="col-md-3">
									<img src="images/Icones_Borne_borneouverteremise.png" class="img-responsive img-thumbnail" alt="">
									<p class="text-dark text-center">Remettez la clef du véhicule sous le led illuminé</p>
								</div>
								<div class="col-md-3">
									<img src="images/Icones_Borne_chack.png" class="img-responsive img-thumbnail" alt="">
									<p class="text-dark text-center">Le tour est joué</p>
								</div>
							</div>

							<p class="text-dark"><strong>Vous l’aurez compris, MyBorne est un outil de travail complet. Ce système simple, connecté et intelligent vous assure une sécurité sans faille et une excellente gestion de votre flotte</strong></p>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="space"></div>
<?php include 'include/footer.php'; ?>
	<!-- END: WRAPPER -->


	<!-- Theme Base, Components and Settings -->
	<script src="/js/theme-functions.js"></script>

</body>

</html>
