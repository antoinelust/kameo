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
          <div class="post-content-details col-md-10 center">
						<h1 class="text-green">MyBorne</h1><sup>par KAMEO Bikes</sup>

            <div class="post-description">
						<br>
						<br>
						<h3 class="text-green">Introduction</h3>
						<div class="col-md-12">
							Dans son objectif d’amélioration continue de la mobilité Kameo vous présente son produit de gestion de flotte : MyBorne<br><br>
							Cette borne de gestion de clefs est l’outil parfait pour l’utilisation et la gestion de vos clefs de manière optimale. En fonction de vos besoins, différentes solutions s’offrent à vous :
							<div class="space"></div>
						</div>
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item active">
								<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Gestion et optimisation de flotte</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Vélos partagés</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
						    <h4 class="text-center text-green">Gestion et optimisation de flotte</h4>
						    <p>Dans son objectif d’amélioration continue de la mobilité, Kameo vous présente son produit de gestion de flotte de véhicule : MyBorne.<br>
								Cette borne de gestion de clefs est l’outil parfait pour l’utilisation et la gestion de vos véhicules de manière optimale. </p>
								<h4 class="text-green">Comment ça marche ?</h4>
								<p>Vous avez une borne physique qui contient toutes les clefs de vos véhicules. Cette borne est reliée à un serveur qui permet, à distance, d’assigner quelle clef sera pour quel employé. Cet assignement de véhicules aux employés sera fait via un algorithme en fonction de différents facteurs : </p>
								<ul>
									<li><strong>L’état du véhicule </strong> : Si un de vos véhicule n’est plus en état pour rouler (trop vieux, accident, panne…), le fleet manager pourra simplement le mentionner dans le programme MyBorne et le véhicule en question sera automatiquement mis de côté par le programme et donc sa clef ne sera plus assignée à un chauffeur</li>
									<li><strong>La consommation du véhicule</strong> : : En fonction des données de consommation que le programme enregistrera à la fin de chaque tournée. Il sera possible d’évaluer la consommation des véhicules et donc d’assigner les véhicules de sorte à minimiser la consommation totale de votre flotte. Concrètement, les véhicules qui consomment le moins pourront être automatiquement assignés pour les tournées les plus longues</li>
									<li><strong>Le statut du véhicule</strong> :
											<li><strong>le véhicule est en leasing</strong> : Si l’ensemble de votre flotte est en leasing, vous avez très certainement des contraintes kilométriques à ne pas dépasser. Avec MyBorne le programme assignera les véhicules de telle sorte que le nombre de kilomètres de ces derniers n’excèdent pas leurs plafonds ou du moins que s’ils doivent excéder ce plafond, les coûts soient minimisés. La borne vous permettra donc d’optimiser au mieux votre flotte et de minimiser vos surcoûts:</li>
											<li><strong>Le véhicule n’est pas en leasing</strong> : Si votre flotte est composée de véhicules qui ne sont pas en leasing, MyBorne s’occupera d’optimiser l’assignement des véhicules pour que le nombre de kilomètre de chaque véhicule évolue à la même fréquence</li>
									Au-delà d’une simple boite à clefs, MyBorne est votre nouveau compagnon de gestion de flotte qui vous permettra d’optimiser l’utilisation de vos véhicules et les coûts qui en découlent</li>
								</ul>
								<h4 class="text-green">Fonctionnalités pour le fleet manager</h4>
								<p>Le fleet manager aura une vue complète en temps réel de la flotte de véhicule dont il est en charge. <br><br>
								De nombreux réglages personnalisables seront à sa disposition : </p>
								<ul>
									<li>L’heure à partir de laquelle les véhicules sont disponibles</li>
									<li>Mettre un véhicule de côté pour diverses raisons (panne, accident, entretien, vol.…)</li>
									<li>Et diverses autre applications possibles à définir avec vous</li>
								</ul>
								<h4 class="text-green">Fonctionnalités pour l'utilisateur du véhicule</h4>
								<p class="card-text">Le but premier de cette borne est de nouveau de faciliter la gestion et l’utilisation de la flotte. Pour les chauffeurs rien de plus simple. Ils recevront un code tous les matins. Ce code va les assigner à un véhicule et leur permettra de débloquer la clef du véhicule. Une fois leur tournée finie, ils rentrent la clef dans le boitier.</p>
								<p class="card-text">Pour les chauffeurs il sera également possible de leur offrir la possibilité de choisir le véhicule de leur préférence. Le programme My Borne, pourra ainsi mettre à disposition de chaque travailleur, un véhicule qui se rapproche le plus de celui de leur préférence. </p>
								<p class="card-text text-center"><strong>Vous l’aurez compris, MyBorne est un outil de travail complet. Ce système simple, connecté et intelligent vous assure une sécurité sans faille et une excellente gestion de votre flotte</strong></p>
						  </div>
							<div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="home-tab">
								<h4 class="text-center text-green">Vélos partagés</h4>
						    <p>Vous possédez une flotte de vélo partagés dans votre entreprise et souhaiter mettre en place un système qui permet d’avoir un meilleur contrôle sur la gestion de vos vélos ? <br><br>
									La solution MyBorne assurera une parfaite gestion de vos deux roues.</p>
								<h4 class="text-green">Comment ça marche ?</h4>
								<p>
									Chacun des vélos de votre flotte sera verrouillé avec un cadenas. Pour pouvoir l’utiliser, il faut la clef du cadenas. C’est là que MyBorne intervient/<br><br>
									Via notre plateforme web vous pouvez réserver un vélo pour un créneau horaire. Une fois la réservation faite, un code vous sera envoyé. Avec ce code vous pourrez ouvrir la borne et récupérer la clef du cadenas du vélo que vous avez choisi.
								</p>
								<h4 class="text-green">Fonctionnalités pour le fleet manager</h4>
								<p>Le fleet manager a une vue d’ensemble sur la gestion des réservations des vélos. Il peut y insérer de nombreux critères pour éviter les abus et les débordements : </p>
								<ul>
									<li>Durée maximale de réservation</li>
									<li>Nombre de réservation par mois</li>
									<li>Tranche horaire de disponibilité des vélos</li>
								</ul>
								<h4 class="text-green">Fonctionnalités pour l'utilisateur du vélo</h4>
								<p>L’utilisateur, lui, aura accès aussi à la plateforme MyKameo sur laquelle il pourra réserver son vélo en fonction des disponibilités et des critères imposés par le fleet manager. Il aura également un aperçu de son historique des réservations.</p>
								<p>Lorsqu’un utilisateur veut réserver un vélo, il se connecte à la plateforme MyKameo. Ensuite il choisit le créneau horaire où il désire réserver un vélo. Une liste des vélos disponibles apparaitra et il pourra sélectionner le vélo de son choix. Si aucun vélo n’apparaît c’est qu’aucun vélo n’est disponible pour ce créneau horaire.<br>
								Une fois la réservation terminée, l’utilisateur reçoit un code qui lui permet de d’obtenir la clef du vélo. Il récupère la clef dans la borne, et peut utiliser le vélo. <br>
								A son retour, il doit ranger le vélo à sa place, scanner la borne avec la clef pour l’ouvrir, ranger la clef à l’endroit imparti, refermer la borne et le tour est joué. </p>
								<p class="text-center"><strong>Vous l’aurez compris, MyBorne est un outil de travail complet. Ce système simple, connecté et intelligent vous assure une sécurité sans faille et une excellente gestion de votre flotte</strong></p>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="space"></div>
<?php include 'include/footer.php'; ?>
	<!-- END: WRAPPER -->


	<!-- Theme Base, Components and Settings -->
	<script src="/js/theme-functions.js"></script>

</body>

</html>
