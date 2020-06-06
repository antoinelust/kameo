<?php 
include 'include/header5.php';
?>

		


 <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                    	<div class="heading heading text-left m-b-20">
                        <h2 class="fr">Newsletter</h2>
						<h2 class="en">Newsletter</h2>
						<h2 class="nl">Newsletter</h2>
                        </div>
                        
                        <p>Restez au courant de nos dernières actualités en souscrivant à notre newsletter.</p>
                        
                        <!--Form inline-->
                        <div class="col-md-8 col-md-offset center">
                            <form id="widget-contact-form" action="include/newsletter-form.php" role="form" method="post" class="form-inline">
                                <div class="row">
                                    <div class="form-group" class="text-left">
                                        <label for="name" id="fr">Nom</label>
                                        <input type="text" aria-required="true" name="widget-newsletter-form-name" class="form-control required name">
                                    </div>
                                     <div class="form-group" class="text-left">
                                        <label for="firstName" id="fr">Prénom</label>
                                        <input type="text" aria-required="true" name="widget-newsletter-form-firstName" class="form-control required name">

										</div>
                                    <div class="form-group " class="text-left">
                                        <label for="email"  id="fr">Email</label>
                                        <input type="email" aria-required="true" name="widget-newsletter-form-email" class="form-control required email">
                                    </div>
                                    <input type="text" class="hidden" name="widget-newsletter-form-antispam" value="" />

									<div class="form-group">
										<button class="m-t-35 btn btn-primary" type="submit">S'inscrire</button>
									</div>
                                </div>
                            </form>
                            <script type="text/javascript">
                                jQuery("#widget-contact-form").validate({
                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                console.log(text);
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: "Merci de nous suivre et à bientôt !"
                                                    }, {
                                                        type: 'success'
                                                    });
                                                    $(form)[0].reset();
                                                    
                                                    gtag('event', 'send', {
                                                      'event_category': 'newsletter',
                                                      'event_label': 'newsletter.php'
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
                        
                        <br>
                        <br>
                        <br>
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

