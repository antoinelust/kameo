<?php 
header("HTTP/1.0 404 Not Found");
include 'include/header5.php';
?>
		
<script type="text/javascript">
window.addEventListener("DOMContentLoaded", function(event) {
    
    $.ajax({
            url: 'apis/Kameo/get_statistics.php',
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


<!-- 404 PAGE -->
<section class="m-t-80 p-b-150">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="page-error-404">404</div>
			</div>
			<div class="col-md-6">
				<div class="text-left">
					<h1 class="text-medium">Page non trouvée!</h1>
					<p class="lead">La page que vous cherchez a peut-être été supprimée ou est temporairement innaccessible.</p>
					<div class="seperator m-t-20 m-b-20"></div>
					<p class="lead">Vous cherchez peut-être :</p>
						<ul class="main-menu nav">
							<li><a href="velo-partage.php">Des vélos partagés</a> </li>
							<li><a href="velo-personnel.php">Des vélos personnels</a> </li>
							<li><a href="gestion-flotte.php">Un système de gestion de flotte</a> </li>
							<li><a href="cash4bike.php">Notre calculateur Cash For Bike</a> </li>
						</ul>

					

				</div>
			</div>
		</div>
	</div>
</section>
<!-- END:  404 PAGE -->





		
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
	
		<!-- CONTACT --> 
<a class="gototop gototop-button" href="#" data-target="#contact" data-toggle="modal"><i class="fa fa-envelope-o"></i></a> 

<div class="modal fade" id="contact" tabindex="-1" role="modal" aria-labelledby="modal-label-2" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h2 class="modal-title" id="modal-label">Contact</h2>
			</div>
			<div class="modal-body">
				<div class="row text-left">
					<div class="col-md-12">
						<form id="widget-contact-form" action="apis/contact-form.php" role="form" method="post">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="name" id="fr">Nom</label>
										<label for="name" id="en">Name</label>
										<label for="name" id="nl">Naam</label>
                                        <input type="text" aria-required="true" name="name" class="form-control required name">
                                    </div>
                                     <div class="form-group col-sm-6">
                                        <label for="firstName" id="fr">Prénom</label>
										<label for="firstName" id="en">First Name</label>
										<label for="firstName" id="nl">Voornaam</label>
                                        <input type="text" aria-required="true" name="firstName" class="form-control required name">

										</div>
                                    <div class="form-group col-sm-6">
                                        <label for="email"  id="fr">Email</label>
										<label for="email"  id="en">Email</label>
										<label for="email"  id="nl">Email</label>
                                        <input type="email" aria-required="true" name="email" class="form-control required email">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="phone"  id="fr">Numéro de téléphone</label>
										<label for="phone"  id="en">Phone number</label>
										<label for="phone"  id="nl">Telefoonnumber</label>
                                        <input type="phone" aria-required="true" name="phone" class="form-control required phone" placeholder="+32">
                                    </div>
                                    <div class="form-group col-sm-6">
		                                <div class="particulier">
											<label><input type="radio" name="type" value="particulier" checked> Je suis un particulier</label>
										</div>
										<div class="professionnel">
											<label><input type="radio" name="type" value="professionnel"> Je suis un professionnel</label>
										</div>
									</div>
									<div class="form-group col-sm-12 entreprise hidden">
	                                	<label for="entreprise" id="fr">Nom de votre entreprise</label>
										<label for="entreprise" id="en">Nom de votre entreprise</label>
										<label for="entreprise" id="nl">Nom de votre entreprise</label>
	                                	<input type="text" aria-required="true" name="entreprise" class="form-control">
	                                </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="subject"  id="fr">Votre sujet</label>
										<label for="subject"  id="en">Subject</label>
										<label for="subject"  id="nl">Onderwerp</label>
                                        <input type="text" name="subject" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message"  id="fr">Message</label>
									<label for="message"  id="en">Message</label>
									<label for="message"  id="nl">Bericht</label>
                                    <textarea type="text" name="message" rows="5" class="form-control required" placeholder="Votre message"></textarea>
                                </div>
                                
                                <div class="g-recaptcha" data-sitekey="6LfqMFgUAAAAADlCo3L6lqhdnmmkNvoS-kx00BMi"></div>
                                
                                <input type="text" class="hidden" name="antispam" value="" />
                                
                                <button  id="fr" class="button green button-3d effect fill-vertical" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Envoyer</button>
								<button  id="en" class="button green button-3d effect fill-vertical" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Send</button>
								<button  id="nl" class="button green button-3d effect fill-vertical" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Verzenden</button>
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
			</div>
			<!--
			<div class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-b">Save Changes</button>
			</div>
			-->
		</div>
	</div>
</div>



</body>

</html>
