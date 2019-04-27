<?php 
include 'include/header2.php';
?>

		


 <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        
                        <img src="catalogue/ets200se_Mixte.jpg" class="img-responsive img-rounded" alt="">
                        <br>
                        <dl class="dl">
							<dt>Caractéristiques techniques</dt>
							<dd>Voir le <ins><a href="https://www.conway-bikes.de/modell/ets-200-se/" target="_blank">site de la marque</a></ins>.</dd>
						</dl>
                        
                                          
                    </div>
                    <div class="col-md-6">
                    	<div class="heading heading text-left m-b-20">
                        <h2 class="fr">Conway ets 200 se</h2>
                        </div>
                        
                        <dl class="dl col-md-6">
							<dt>Utilisation</dt>
							<dd>Ville et chemin</dd>
							<br>
							<dt>Type de cadre</dt>
							<dd>Mixte</dd>
						</dl>
						
						<dl class="dl col-md-6">
							<dt>Assistance électrique</dt>
							<dd>Oui</dd>
							<br>
							<dt>Type de cadre</dt>
							<dd>Mixte</dd>
						</dl>
						
						<div class="col-md-12">
						<h3>Prix : <b class="text-green">78€</b> <small>htva</small></h3>
						</div>
                        
                        <div class="col-md-12">
                        <p>Le prix comprend un entretien annuel, une assurance P-vélo, bla bla bla </p>
                        </div>
                        
                        <div class="separator"></div>
                        
                        <div class="m-t-30">
                            <form id="widget-contact-form" action="include/order-form.php" role="form" method="post">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="name" id="fr">Nom</label>
										<label for="name" id="en">Name</label>
										<label for="name" id="nl">Naam</label>
                                        <input type="text" aria-required="true" name="widget-contact-form-name" class="form-control required name">
                                    </div>
                                     <div class="form-group col-sm-6">
                                        <label for="firstName" id="fr">Prénom</label>
										<label for="firstName" id="en">First Name</label>
										<label for="firstName" id="nl">Voornaam</label>
                                        <input type="text" aria-required="true" name="widget-contact-form-firstName" class="form-control required name">

										</div>
                                    <div class="form-group col-sm-6">
                                        <label for="email"  id="fr">Email</label>
										<label for="email"  id="en">Email</label>
										<label for="email"  id="nl">Email</label>
                                        <input type="email" aria-required="true" name="widget-contact-form-email" class="form-control required email">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="phone"  id="fr">Numéro de téléphone</label>
										<label for="phone"  id="en">Phone number</label>
										<label for="phone"  id="nl">Telefoonnumber</label>
                                        <input type="phone" aria-required="true" name="widget-contact-form-phone" class="form-control required phone" placeholder="+32">
                                    </div>
                                    
                                    
                                </div>
                                <!--
                                <div class="form-group">
                                    <label for="message"  id="fr">Message</label>
									<label for="message"  id="en">Message</label>
									<label for="message"  id="nl">Bericht</label>
                                    <textarea type="text" name="widget-contact-form-message" rows="5" class="form-control required" placeholder="Votre message"></textarea>
                                </div>
                                -->
                                
                                <div class="g-recaptcha" data-sitekey="6LfqMFgUAAAAADlCo3L6lqhdnmmkNvoS-kx00BMi"></div>
                                
                                <input type="text" class="hidden" id="widget-contact-form-antispam" name="widget-contact-form-antispam" value="" />
                                <button  id="fr" class="button green button-3d rounded effect" type="submit" id="form-submit"></i>Demander une offre</button>
                            </form>   
                            </div>
                        
                    </div>
                </div>
            </div>
        </section>
        <!-- END: CONTENT -->
        
       
  
        
		<!-- FOOTER -->
	<footer class="background-dark text-grey" id="footer">
    <div class="footer-content">
        <div class="container">
        
        <br><br>
        
            <div class="row text-center">
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
				<!--
				<div class="copyright-text text-center"><a href="blog.php" class="text-green text-bold">Le blog</a> | <a href="faq.php" class="text-green text-bold">FAQ</a></div>
				-->
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

	<!-- Custom js file -->
	<script src="js/language.js"></script>



</body>

</html>
