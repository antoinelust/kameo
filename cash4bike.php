<?php 
include 'include/header5.php';
?>
		
		<!--Square icons-->
  <section>
	
	<div class="container">
		<div class="row">
				<h1 class="text-green">CASH FOR BIKE</h1>
				<br>
				
				<p>CASH FOR BIKE est un module puissant vous permettant de calculer le coût réel de votre vélo.</p>
				<br>
				<h3>Le calculateur</h3>
				
				<div class="m-t-30">
                	<form id="widget-contact-form" action="include/contact-form.php" role="form" method="post">
                    <div class="row">
                    
                    	<div class="form-group col-sm-6">
		                	<div class="employe">
								<label><input type="radio" name="type" value="employe" checked> Je suis un employé</label>
							</div>
							<div class="ouvrier">
								<label><input type="radio" name="type" value="ouvrier"> Je suis un ouvrier</label>
							</div>
						</div>
						
						<div class="separator"></div>
						
                   		<div class="form-group col-sm-6">
	                   		<div class="form-group">
								<label class="revenu" for="phone">Votre revenu mensuel brut :</label>
								<input type="number" class="form-control required" name="revenu" value="3000" id="revenu" aria-required="true">
							</div>
                        </div>
                        
                        <div class="separator"></div>
						
                   		<div class="form-group col-sm-6">
	                   		<div class="form-group">
								<label class="prixvelo" for="phone">Le prix HTVA du vélo souhaité :</label>
								<input type="number" class="form-control required" name="prixvelo" value="1700" id="prixvelo" aria-required="true">
							</div>
                        </div>
                        
                        <div class="separator"></div>
                        
                        <div class="form-group col-sm-6">
		                	<div class="employeurremunere">
								<label><input type="radio" name="type" value="employeurremunere" checked> Mon employeur rémunère mes kilomètres vélo</label>
							</div>
							<div class="employeurneremunerepas">
								<label><input type="radio" name="type" value="employeurneremunerepas"> Mon employeur ne me rémunère par les kilomètres vélo</label>
							</div>
						</div>
						
						<div class="space"></div>
						
						<div class="form-group col-sm-6">
                        	<label for="domicile" id="fr">Adresse du domicile</label>
                            <input type="text" aria-required="true" name="domicile" class="form-control required domicile">
						</div>
						<div class="form-group col-sm-6">
                        	<label for="travail" id="fr">Adresse du lieu de travail</label>
                            <input type="text" aria-required="true" name="travail" class="form-control required travail">
						</div>
						
						<div class="separator"></div>
						
						<div class="form-group col-sm-6">
							<label for="transport" id="fr">Votre moyen de transport actuel :</label>
							<select class="form-control">
								<option>Voiture personnelle</option>
								<option>Voiture de société</option>
								<option>Covoiturage</option>
								<option>Transport en commun</option>
								<option>Vélo personnel</option>
								<option>Marche</option>
							</select>
						</div>
						<div class="form-group col-sm-6">
		                	<div class="essence">
								<label><input type="radio" name="type" value="essence" checked> Essence</label>
							</div>
							<div class="diesel">
								<label><input type="radio" name="type" value="diesel"> Diesel</label>
							</div>
							<div class="lpg">
								<label><input type="radio" name="type" value="lpg"> LPG</label>
							</div>
						</div>
						
						<div class="separator"></div>
                                
                                
                            <button  id="fr" class="button green button-3d effect fill-vertical" type="submit" id="form-submit"><i class="fa fa-calculator"></i>&nbsp;Calculer</button>
                    </form>
                            
					</div>
				</div>
		</div>
		
		<div class="space"></div>
		
		<!-- RESULTAT -->
		<div class="jumbotron jumbotron-center jumbotron-fullwidth background-green text-light">
		  <div class="container">
		    <h3>VALEUR DU RÉSULTAT</h3>
		    <p>Explication de ce résultat.</p>
		</div>

		<!--END: RESULTAT -->
		
	</div>
	<h3>Disclaimer</h3>
		<p>Cet outil est mis à votre disposition à titre exclusivement informatif et il s'agit d'une simulation de calcul effectuée à titre purement indicatif.</p>
		<p>L’outil a été élaboré avec le plus grand soin et nous nous efforçons, dans la mesure du raisonnable, à l’actualiser et à maintenir l'exactitude des informations qui s’y trouvent, sachant que les législations changent fréquemment. De plus, pour faciliter l’utilisation de l’outil, certaines données ne sont pas prises en considération pour le calcul. Dès lors, il se peut qu’il y ait une différence entre le montant calculé et le montant réel.</p>
		<p>Les informations reprises ne remplacent en aucun cas un avis juridique ou l’assistance personnalisée d’un professionnel.</p>
		<p>Dans la mesure autorisée par la loi, nous ne sommes en aucun cas être tenus responsables de tout dommage, direct ou indirect, de quelque nature et importance qu’il soit, qui pourrait être causé directement ou indirectement par la consultation ou, plus généralement, par toute utilisation quelconque qui serait faite de cet outil et notamment des informations qui s’y trouvent.</p>
</section>





		
		<!-- FOOTER -->
		<footer class="background-dark text-grey" id="footer">
	    <div class="footer-content">
	        <div class="container">
	        
	        <br><br>
	        
	            <div class="row text-center">
	            
	           <!--
					<div class="button green full-rounded"><a href="newsletter.php" class="text-light text-bold">Newsletter</a> | <a href="faq.php" class="text-green text-bold">FAQ</a></div>
					-->
	            
	                <div class="copyright-text text-center"><ins>Kameo Bikes SPRL</ins> 
						<br>BE 0681.879.712 
						<br>+32 498 72 75 46 </div>
						<br>
	                <div class="social-icons center">
								<ul>
									<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
									
									<li class="social-linkedin"><a href="https://www.linkedin.com/company/kameobikes/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
									
								</ul>
					</div>
					
					<div class="copyright-text text-center"><a href="blog.php" class="text-green text-bold">Le blog</a> | <a href="bonsplans.php" class="text-green text-bold">Les bons plans</a></div>
					
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
