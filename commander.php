<?php 
include 'include/header2.php';
?>

		


 <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                    	<div class="heading heading text-left m-b-20">
                        <h2 class="fr">COMMANDER</h2>
						<h2 class="en">ORDER</h2>
						<h2 class="nl">BEVELEN</h2>
                        </div>
                        <p class="fr">Vous voulez commander un vélo ou des accessoires? 
                        <br><strong class="text-green">Félicitations!</strong>
                        <br>Laissez nous vos coordonnées, nous reviendrons vers vous dès que possible.</p>
                        
						<p class="en">Want to order a bike or accessories?
						<br><strong class="text-green">Congratulations!</strong>
						<br>Leave us your details, we will get back to you as soon as possible.</p>
						
						<p class="nl">Wilt u een fiets of accessoires bestellen?
						<br><strong class="text-green">Gefeliciteerd!</strong>
						<br>Laat ons uw gegevens achter, wij nemen zo spoedig mogelijk contact met u op.</p>
						
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
                                    <div class="form-group col-sm-6">
                                        <label for="entreprise"  id="fr">Entreprise</label>
										<label for="entreprise"  id="en">Business</label>
										<label for="entreprise"  id="nl">Onderneming</label>
                                        <input type="entreprise" aria-required="true" name="widget-contact-form-entreprise" class="form-control required entreprise" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message"  id="fr">Message</label>
									<label for="message"  id="en">Message</label>
									<label for="message"  id="nl">Bericht</label>
                                    <textarea type="text" name="widget-contact-form-message" rows="5" class="form-control required" placeholder="Votre message"></textarea>
                                </div>
                                
                                <div class="g-recaptcha" data-sitekey="6LfqMFgUAAAAADlCo3L6lqhdnmmkNvoS-kx00BMi"></div>
                                
                                <input type="text" class="hidden" id="widget-contact-form-antispam" name="widget-contact-form-antispam" value="" />
                                <button  id="fr" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Envoyer</button>
								<button  id="en" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Send</button>
								<button  id="nl" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Verzenden</button>
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
            </div>
        </section>
        <!-- END: CONTENT -->
        
        

		<!-- FOOTER -->
	<footer class="background-dark text-grey" id="footer">
    <div class="footer-content">
        <div class="container">
        
        <br><br>
        
            <div class="row text-center">
                <div class="copyright-text text-center"> &copy; 2019 KAMEO Bikes</div>
                <div class="social-icons center">
							<ul>
								<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
								
								<li class="social-instagram"><a href="https://www.instagram.com/kameobikes/" target="_blank"><i class="fa fa-instagram"></i></a></li>
							</ul>
				</div>
				<div class="copyright-text text-center"><a href="blog.php" class="text-green text-bold">Le blog</a> | <a href="faq.php" class="text-green text-bold">FAQ</a></div>
				
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
