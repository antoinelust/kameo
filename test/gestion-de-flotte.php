<?php 
include 'include/header2.php';
?>
		
<script type="text/javascript">
window.addEventListener("DOMContentLoaded", function(event) {
    
    $.ajax({
            url: 'include/get_statistics.php',
            type: 'post',
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                }else{
                    document.getElementById('bookingCounter').innerHTML = "<span data-speed=\"3500\" data-refresh-interval=\"50\" data-to=\""+response.bookings+"\" data-from=\"0\" data-seperator=\"true\"></span>";
                }              

            }
    })
});
                    
</script>
<!-- SECTION -->
<section>
    <div class="container portfolio">
    
        <div class="heading heading text-left m-b-20">
            <h2 class="fr">Gestion de flotte</h2>
            <h2 class="en">Fleet managment</h2>
            <h2 class="nl">Vloot beheer</h2>
        </div>
    </div>

	<div class="jumbotron jumbotron-center jumbotron-fullwidth background-colored text-light">
	  <div class="container">
	    <h3>Pas le temps pour vous occuper de vos vélos?
	    Difficile d'assurer le suivi de votre flotte?
	    Envie de quantifier votre utilisation?</h3>
	    <p>Notre système intégré et modulable le fait pour vous. Composé d'une plateforme automatisée couplée à des bornes sécurisées, il vous assure un feedback et contrôle sur votre mobilité.</p>
	    
	    
	    <div class="col-md-6">
			<h2 class="text-white text-center fr"><a href="gestion-de-flotte.php">MyKAMEO</a></h2>
			<a href="#section1" class="scroll-to"><img src="images/plateforme.png"  alt="MyKAMEO"></a>
		</div>
		<div class="separator visible-sm visible-xs"></div>
		<div class="col-md-6">
			<h2 class="text-white text-center fr"><a href="gestion-de-flotte.php">MyBORNE</a></h2>
			<a href="#section2" class="scroll-to"><img src="images/myborne.png"  alt="MyBORNE"></a>
		</div>
      
	 </div>
	</div>

	<div class="container portfolio" id="section1">
	
	<h3 class="fr">MyKAMEO</h3>
	
        <p class="fr text-dark">
            La plateforme MyKAMEO permet d'établir un lien direct entre les employés et KAMEO Bikes. Qu'il s'agisse d'une réservation de vélo, d'une demande d'entretien ou encore de l'encodage des trajets parcourus, elle est facilement utilisable par les employés et paramétrable par les fleets managers.<br /><br>
            La plateforme offre différentes solutions en fonction de vos besoins:</p>
            <ul class="fr text-dark">
                <li>Vous optez pour un vélo personnel mis à disposition par KAMEO Bikes et vous utilisez la plateforme pour les entretiens et l'assurance</li>
                <li>Votre société mets des vélos partagés à disposition, vous utilisez la plateforme pour réserver un vélo ou mentionner la nécessité d'un entretien</li>
                <li>Vous êtes fleet manager et vous désirez configurer les actions possibles par les employés et avoir des statistiques sur l'utilisation des vélos</li>
            </ul>
            <br>
        <p class="en text-dark">
            The MyKameo IT solution offers a direct connection between employees and KAMEO Bikes. With this plateform, you can easily book a shared bike, ask for a maintenance or encode all you rides. It is useable by employees but also configurables by fleet managers.<br />   <br> 
            The platform offers different kind of solution, depending of your needs:</p>
            <ul class="en text-dark">
                <li>You bought or leased a bike via your company to KAMEO Bikes and you use the platform for maintenance and assurance</li>
                <li>Your company provides shared bikes and you use the plaform to book a bike or ask for a maintenance</li>
                <li>You are a fleet manager and wants to have statistics about the use of bike whithin your company</li>
            </ul>
            <br>
        <p class="nl text-dark">
            De MyKameo IT-oplossing biedt een directe verbinding tussen werknemers en KAMEO Bikes. Met deze plateform kunt u eenvoudig een gedeelde fiets boeken, een onderhoud aanvragen of alles coderen wat u doet. Het is bruikbaar voor werknemers, maar ook configureerbaar door fleetmanagers.<br /><br>
            Het platform biedt verschillende soorten oplossingen, afhankelijk van uw behoeften:</p>
             <ul class="nl text-dark">
                 <li> U kocht of huurde een fiets via uw bedrijf naar KAMEO Bikes en u gebruikt het platform voor onderhoud en verzekering </li>
                 <li> Uw bedrijf biedt gedeelde fietsen en u gebruikt de plaform om een fiets te boeken of om onderhoud te vragen </li>
                 <li> U bent vlootmanager en wilt statistieken hebben over het gebruik van fiets binnen uw bedrijf </li>
             </ul>
             <br>
             
             
		
		<div class="col-md-6">
        <div class="icon-box medium fancy">
          <div class="icon" data-animation="wobble infinite"> <a href="#"><i class="fa fa-bicycle"></i></a> </div>
          <div id="bookingCounter" class="counter bold"></div>
          <p>Nombre de réservations faites depuis notre plateforme</p>
        </div>
      </div>
      
		
		<div class="separator"></div>

        <!--Portfolio Filter-->
        <!--
        <div class="filter-active-title">Tous les modules</div>
        -->
        <ul class="portfolio-filter" id="portfolio-filter" data-isotope-nav="isotope">
       		<li class="ptf-active fr-inline" data-filter="*">Tous les modules</li>
       		<li class="ptf-active en-inline" data-filter="*">All features</li>
       		<li class="ptf-active nl-inline" data-filter="*">Alle functies</li>
            
			<li class="fr-inline" data-filter=".perso">Vélo Perso</li>
			<li class="en-inline" data-filter=".perso">Personal bike</li>
			<li class="nl-inline" data-filter=".perso">Persoonlijke fiets</li>
			<li class="fr-inline" data-filter=".partage">Vélo Partagé</li>
			<li class="en-inline" data-filter=".partage">Shared bike</li>
			<li class="nl-inline" data-filter=".partage">Gedeelde fiets</li>
			<li  class="fr-inline" data-filter=".fleet">Fleet Manager</li>
			<li  class="en-inline" data-filter=".fleet">Fleet Manager</li>
			<li  class="nl-inline" data-filter=".fleet">Fleet Manager</li>
		</ul>
        <!-- END: Portfolio Filter -->
		
        <!-- Portfolio Items -->
        <div id="isotope" class="isotope portfolio-items" data-isotope-item-space="2" data-isotope-mode="masonry" data-isotope-col="3" data-isotope-item=".portfolio-item">
        
        	<div class="portfolio-item partage perso">
				<div class="icon-box effect medium center">
				<br>
					<div class="icon"> <a data-target="" data-toggle="modal" href="#"><i class="fa fa-phone"></i></a> </div>
				    <h3 class="fr">Assistance et Entretien</h3>
				    <h3 class="en">Maintenance</h3>
				    <h3 class="nl">Onderhoud</h3>
				    <p class="fr">Grâce à cet outil vous pouvez joindre KAMEO Bikes ou l'assureur 24h/24.<br/><br/></p>
				    <p class="en">Thanks to that feature, you can join us or the insurer 24/7 in case of urgency or maintenance needed.<br/><br/></p>
				    <p class="nl">Dankzij die functie kunt u 24/7 bij ons of de verzekeraar komen in geval van urgentie of onderhoud.<br/><br/></p>
				</div>
            </div>
            
			<div class="portfolio-item partage">
				<div class="icon-box effect medium center">
				<br>
					<div class="icon"> <a data-target="" data-toggle="modal" href="#"><i class="fa fa-check-square-o"></i></a> </div>
				    <h3 class="fr">Réservation d'un vélo</h3>
				    <h3 class="en">Book a bike</h3>
				    <h3 class="nl">Boek een fiets</h3>
				    <p class="fr">Réservez un vélo mis à disposition par votre entreprise.<br /><br /></p>
				    <p class="en">Book a shared bike from your company.<br/><br/><br/></p>
				    <p class="nl">Boek een gedeelde fiets van uw bedrijf.<br/><br/><br/></p>
				</div>
            </div>
            
            <div class="portfolio-item perso">
				<div class="icon-box effect medium center">
				<br>
					<div class="icon"> <a data-target="" data-toggle="modal" href="#"><i class="fa fa-calendar"></i></a> </div>
				    <h3 class="fr">Calendrier</h3>
				    <h3 class="en">Calendar</h3>
				    <h3 class="nl">Kalender</h3>
				    <p class="fr">Un calendrier vous permet d'encoder vos trajets Domicile/Travail afin de percevoir une indemnité de 0,23€/km.<p>
				    <p class="en">A calendar allows you to encode your rides between your home and office and get an allowance of 0.23€ per km.<p>
				    <p class="nl">Met een kalender kunt u uw ritten coderen tussen uw huis en kantoor en krijgt u een vergoeding van 0,23 € per km.<p>
				</div>
            </div>
        
            <div class="portfolio-item partage perso">
				<div class="icon-box effect medium center">
				<br>
					<div class="icon"> <a data-target="" data-toggle="modal" href="#"><i class="fa fa-lock"></i></a> </div>
				    <h3 class="fr">Accès privé</h3>
				    <h3 class="en">Secured access</h3>
				    <h3 class="nl">Beveiligde toegang</h3>
				    <p class="fr">Plateforme sécurisée via login et mot de passe.<br/><br/></p>
				    <p class="en">Secure platform with login and password.<br/><br/><br/></p>
				    <p class="nl">Beveiligd platform met login en wachtwoord.<br/><br/><br/></p>
				</div>
            </div>
            
            <div class="portfolio-item perso">
				<div class="icon-box effect medium center">
				<br>
					<div class="icon"> <a data-target="" data-toggle="modal" href="#"><i class="fa fa-bicycle"></i></a> </div>
				    <h3 class="fr">Votre vélo</h3>
				    <h3 class="en">Your bike</h3>
				    <h3 class="nl">Jouw fiets</h3>
				    <p class="fr">Retrouverez toutes les informations concernant votre vélo.<br/><br/></p>
				    <p class="en">Get all information about your bike.<br/><br/><br/></p>
				    <p class="nl">Krijg alle informatie over uw fiets.<br/><br/><br/></p>
				</div>
            </div>
            
			 <div class="portfolio-item partage perso">
				<div class="icon-box effect medium center">
				<br>
					<div class="icon"> <a data-target="" data-toggle="modal" href="#"><i class="fa fa-cloud"></i></a> </div>
				    <h3 class="fr">Météo</h3>
				    <h3 class="en">Weather</h3>
				    <h3 class="nl">Weer</h3>
				    <p class="fr">Accès direct à la météo pour votre prochain trajet.<br/><br/></p>
				    <p class="en">Direct access to weather forecast for your next ride.<br/><br/><br/></p>
				    <p class="nl">Directe toegang tot weersvoorspelling voor uw volgende rit.<br/><br/><br/></p>
				</div>
            </div>
            
            <div class="portfolio-item fleet">
				<div class="icon-box effect medium center">
				<br>
					<div class="icon"> <a data-target="" data-toggle="modal" href="#"><i class="fa fa-bar-chart-o"></i></a> </div>
				    <h3 class="fr">Gestion de flotte</h3>
				    <h3 class="en">Fleet management</h3>
				    <h3 class="nl">Fleet management</h3>
				    <p class="fr">Rapport mensuel de la fréquence d'utilisation de votre flotte.<br/><br/></p>
				    <p class="en">Monthly report regarding the use of bikes within your company.<br/><br/></p>
				    <p class="nl">Maandelijks rapport met betrekking tot het gebruik van fietsen binnen uw bedrijf.<br/><br/></p>
				</div>
            </div>
            
            <div class="portfolio-item fleet">
				<div class="icon-box effect medium center">
				<br>
					<div class="icon"> <a data-target="" data-toggle="modal" href="#"><i class="fa fa-info-circle"></i></a> </div>
				    <h3 class="fr">Facturation</h3>
				    <h3 class="en">Billing</h3>
				    <h3 class="nl">Billing</h3>
				    <p class="fr">Retrouvez vos dernières factures.<br/><br/><br/></p>
				    <p class="en">Find all your bills in a central place.<br/><br/><br/></p>
				    <p class="nl">Vind al uw rekeningen op een centrale plaats.<br/><br/><br/></p>
				</div>
            </div>
            
             <div class="portfolio-item fleet">
				<div class="icon-box effect medium center">
				<br>
					<div class="icon"> <a data-target="" data-toggle="modal" href="#"><i class="fa fa-user"></i></a> </div>
				    <h3 class="fr">Contrats</h3>
				    <h3 class="en">Contracts</h3>
				    <h3 class="nl">Contracten</h3>
				    <p class="fr">Retrouvez rapidement tous les contrats des vélos KAMEO Bikes.<br/><br/></p>
				    <p class="en">Find easily all contracts of KAMEO Bikes.<br/><br/></p>
				    <p class="nl">Vind eenvoudig alle contracten van KAMEO Bikes.<br/><br/></p>
				</div>
            </div>
            

        </div>
        
        
        <!-- END: Portfolio Items -->

		<div class="separator" id="section2"></div>
		<h3 class="fr">MyBORNE</h3>
	
        <p class="fr text-dark">
        	La Borne développé par KAMEO Bikes permet de sécuriser toutes les clés de vos véhicules, quels qu'ils soient.<br>
        	Simple d'utilisation, elle deviendra un objet indispensable à votre gestion de flotte!
        </p>
        
        <div class="col-md-6">
        <img src="images/Borne_Web_Out.jpg" class="img-responsive img-rounded" alt="Borne extérieur">
        </div>
        
        <div class="col-md-6">
        <img src="images/Borne_Web_In.jpg" class="img-responsive img-rounded" alt="Borne extérieur">
        </div>
        <div class="separator"></div>
        <!--jumbotron pattern -->
   		 <div class="jumbotron jumbotron-border">
      		<h3>En savoir plus</h3>
      		<p>Télécharger le pdf reprenant une explication détaillée de nos modules et du fonctionnement de la borne</p>
      		<a class="button large color button-3d rounded effect icon-left text-light" href="docs/MyKameo_VeloPartage.pdf" target="_blank"><span><i class="fa fa-download"></i>Télécharger</span></a> </div>
    <!--END: jumbotron pattern --> 

    </div>

<div class="jumbotron jumbotron-center jumbotron-fullwidth background-colored text-light">
	  <div class="container">
	    <h3>Besoin d'un complément d'information?</h3>
	    <p>N'hésitez pas à nous contacter si vous voulez en savoir plus sur nos solutions.</p>
	    <a class="button large black-light button-3d effect icon-left" href="contact.php"><span><i class="fa fa-paper-plane-o"></i>Contactez-nous!</span></a> </div>
	</div>
</section>




		
			<!-- FOOTER -->
		<footer class="background-dark text-grey" id="footer">
	    <div class="footer-content">
	        <div class="container">
	        
	        <br><br>
	        
	            <div class="row text-center">
	            
	           
					<div class="copyright-text text-center"><a href="newsletter.php" class="text-green text-bold">Newsletter</a> <!--| <a href="faq.php" class="text-green text-bold">FAQ</a>--></div>
				
	            
	                <div class="copyright-text text-center"><ins>Kameo Bikes SPRL</ins> 
						<br>BE 0681.879.712 
						<br>+32 498 72 75 46 </div>
						<br>
	                <div class="social-icons center">
								<ul>
									<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
									
									<li class="social-instagram"><a href="https://www.instagram.com/kameobikes/" target="_blank"><i class="fa fa-instagram"></i></a></li>
								</ul>
					</div>
					
					<br>
					<br>
					
	            </div>
	        </div>
	    </div>
	</footer>
			<!-- END: FOOTER -->


	</div>
	<!-- END: WRAPPER -->

	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>



</body>

</html>
