<?php 
include 'include/header.php';
?>

		


 <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                    	<div class="heading heading text-left m-b-20">
                        <h2 class="fr">ESSAI</h2>
                        <h2 class="en">TRY ME</h2>
                        <h2 class="nl">TRY ME</h2>
                        </div>
                        <p class="fr">KAMEO Bikes vous propose de réserver un essai. Profitez-en!</p>
                        <p class="en">Schedule your test day!</p>
                        <p class="nl">Boek Uw Testdag!</p>
                        <div class="m-t-30">
                            <form id="widget-contact-form" action="include/try-form.php" role="form" method="post">
                                <div class="row">
                                	
                                    <div class="form-group col-sm-6">
                                        <label for="name" id="fr">Nom</label>
                                        <label for="name" id="en">Name</label>
                                        <label for="name" id="nl">Achternaam</label>
                                        <input type="text" aria-required="true" name="widget-contact-form-name" class="form-control required name">
                                    </div>
                                     <div class="form-group col-sm-6">
                                        <label for="firstName" id="fr">Prénom</label>
                                        <label for="firstName" id="en">Firstname</label>
                                        <label for="firstName" id="nl">Voornaam</label>
                                        <input type="text" aria-required="true" name="widget-contact-form-firstName" class="form-control required name">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="born" id="fr">Date de naissance</label>
                                        <label for="born" id="en">Birthdate</label>
                                        <label for="born" id="nl">Geboortedatum</label>
                                        <input type="text" name="widget-contact-form-birthDate" class="form-control required" placeholder="JJ-MM-AAAA">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="email" id="fr">Email</label>
                                        <label for="email" id="en">Email</label>
                                        <label for="email" id="nl">Email</label>
                                        <input type="email" aria-required="true" name="widget-contact-form-email" class="form-control required email">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="phone" id="fr">Numéro de téléphone</label>
                                        <label for="phone" id="en">Phone Number</label>
                                        <label for="phone" id="nl">Telefoonnummer</label>
                                        <input type="tel" aria-required="true" name="widget-contact-form-phone" class="form-control required phone" placeholder="+32 ">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="postCode" id="fr">Code postal</label>
                                        <label for="postCode" id="en">Zip code</label>
                                        <label for="postCode" id="nl">Postcode</label>
										<input type="text" aria-required="true" name="widget-contact-form-postalCode" class="form-control required">
                                    </div>
                                    <div class="form-group col-sm-6">
                                         <label for="velo" id="fr">Quel vélo désirez-vous tester ?</label>
                                         <label for="velo" id="en">Which bike would you like to test?</label>
                                         <label for="velo" id="nl">Welke fiets wilt U testen ?</label>
									       <select name="widget-contact-form-velo">
									           <option value="1">Romeo</option>
									           <option value="2">Zimeo</option>
									           <option value="3">Pameo</option>
									          <!--  <option value="3">Le pliable</option> -->
									       </select>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="born" id="fr">Date d'essai souhaitée</label>
                                        <label for="born" id="en">Desired trial day</label>
                                        <label for="born" id="nl">Gewenst testdag</label>
                                        <input type="text" name="widget-contact-form-dateEssai" class="form-control required" placeholder="JJ-MM-AAAA">
                                    </div>
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
                                                        message: "Demande d'essai enregistrée. Nous reviendrons vers vous dès que possible."
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
            </div>
        </section>
        <!-- END: CONTENT -->
        
        

		<!-- FOOTER -->
		<footer class="background-dark text-grey" id="footer">
    <div class="footer-content">
        <div class="container">
            <div class="row text-center">
                <div class="copyright-text text-center"> &copy; 2017 KAMEO Bikes
                </div>
                <div class="social-icons center">
							<ul>
								<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
								
								<li class="social-instagram"><a href="https://www.instagram.com/kameobikes/" target="_blank"><i class="fa fa-instagram"></i></a></li>
							</ul>
						</div>
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
