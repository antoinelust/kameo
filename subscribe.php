<?php 
include 'include/header5.php';
?>

		


 <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                    	<div class="heading heading text-left m-b-20">
                        <h2 class="fr">ENREGISTREZ VOUS</h2>
						<h2 class="en">CONTACT US</h2>
						<h2 class="nl">CONTACT</h2>
                        </div>
                        <p class="fr">Vous voulez rester informé de toutes nos actions? Complétez ce formulaire.</p>
						<p class="en">Would you like more information or simply to meet us? It's easy, fill out this form and we will contact you as soon as possible.</p>
						<p class="nl">Wilt u meer informatie of gewoon ons te ontmoeten? Het is gemakkelijk, vul dit formulier in en we nemen zo snel mogelijk contact met u op.</p>
                        <div class="m-t-30">
                            <form id="widget-contact-form" action="include/contact-form.php" role="form" method="post">
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
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="societe"  id="fr">Nom de votre société</label>
										<label for="societe"  id="en"></label>
										<label for="societe"  id="nl"></label>
                                        <input type="text" name="widget-contact-form-subject" class="form-control required">
                                    </div>
                                </div>
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
									
									<li class="social-linkedin"><a href="https://www.linkedin.com/company/kameobikes/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
									
								</ul>
					</div>
					
					<div><a href="faq.php" class="text-green text-bold"><h3 class="text-green">FAQ</h3></a><!-- | <a href="bonsplans.php" class="text-green text-bold">Les bons plans</a>--></div>
					
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


