<?php 
include 'include/header2.php';
?>


<br>
<h1 class="text-center text-uppercase text-medium fr" data-animation="fadeInUp">KAMEO Bikes, la solution complète pour vos vélos de société</h1>
<h1 class="text-center text-uppercase text-medium en" data-animation="fadeInUp">KAMEO Bikes, the complete solution for your company bikes</h1>
<h1 class="text-center text-uppercase text-medium nl" data-animation="fadeInUp">KAMEO Bikes, de complete oplossing voor uw bedrijfsfietsen</h1>



<!-- SECTION IMAGE FULLSCREEN -->
<section class="text-light" style="background: url('images/background.png')">
	<div class="container container-fullscreen">
        <div class="text-center text-light">
            	<div class="container"> 
						<div class="col-md-4">
							<h2 class="text-white text-center fr"><a href="vente-leasing-location.php">Vente, Leasing et Location</a></h2>
							<h2 class="text-white text-center en"><a href="vente-leasing-location.php">Sell, Lease and Renting</a></h2>
					        <h2 class="text-white text-center nl"><a href="vente-leasing-location.php">Verkoop, Leasing en Huren</a></h2>
					        <a href="vente-leasing-location.php"><img src="images/flotte.png" class="center img-responsive" alt="Vente, Leasin, Location de vélos"></a>
						</div>
						<div class="separator visible-sm visible-xs"></div>
						<div class="col-md-4">
							<h2 class="text-white text-center fr"><a href="maintenance-assurance.php">Maintenance et Assurance</a></h2>
					        <h2 class="text-white text-center en"><a href="maintenance-assurance.php">Maintain and Insurance</a></h2>
					        <h2 class="text-white text-center nl"><a href="maintenance-assurance.php">Onderhouden en Verzekering</a></h2>
											<br class="hidden-xs hidden-sm">
							<a href="entretien-assurance.php"><img src="images/entretien.png" class="center img-responsive" alt="Entretien, Assurance de vélos"></a>
						</div>
						<div class="separator visible-sm visible-xs"></div>
						<div class="col-md-4">
							<h2 class="text-white text-center fr"><a href="gestion-de-flotte.php">Gestion de flotte</a></h2>
						    <h2 class="text-white text-center en"><a href="gestion-de-flotte.php">Fleet managment</a></h2>
							<h2 class="text-white text-center nl"><a href="gestion-de-flotte.php">Vloot beheer</a></h2>
							<br class="hidden-xs hidden-sm">
											<br class="hidden-xs hidden-sm">
							<a href="gestion-de-flotte.php"><img src="images/plateforme.png" class="center img-responsive" alt="Gestion de flotte de vélos"></a>
						</div>
						</div>
				</div>
			</div>
        </div>
    </div>      
</section>

<!-- Language management -->
<script type="text/javascript" src="js/language.js"></script>

<!-- END: SECTION IMAGE FULLSCREEN -->
<br>
<video class="img-responsive" style="display:block; margin: 0 auto;" width="768" height="432" controls poster="images/kameo.png" preload="none">
	<source src="images/kameo.mp4">
	<source src="images/kameo.webm"> 
	Votre navigateur ne supporte pas la balise vidéo.
</video>
<br>

<div class="separator"></div>

<section id="section5" class="">
	<div class="container">
		<div class="heading heading text-left m-b-20">
        	<h2 class="fr">Ils nous font confiance</h2>
        	<h2 class="en">Our clients:</h2>
        	<h2 class="nl">Onze klanten:</h2>
        </div>
        
        <ul class="grid grid-3-columns">
				<li>
					<img src="images/siapartners.jpg" alt="client vélo électrique Bruxelles - SiaPartners">
				</li>
				
				<li>
					<img src="images/deliveroo.jpg" alt="client vélo électrique Liège et Bruxelles - Deliveroo">
				</li>
				
				<li>
					<img src="images/venturelab.jpg" alt="client vélo électrique Liège - Venturelab">
				</li>
		</ul>
        
	</div>
</section>
		
		
	<div class="modal fade" id="newPassword" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3 id="fr">Mot de passe oublié</h3>                        
                        <h3 id="en">Password lost</h3>                        
                        <h3 id="nl">Wachtwoord kwijt</h3>                        
						<form id="widget-lostPassword-form" action="include/lostPassword.php" role="form" method="post">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="subject"  id="fr">Nouveau mot de passe</label>
                                    <label for="subject"  id="en">New pasword</label>
                                    <label for="subject"  id="nl">Nieuw wachtwoord</label>
                                    <input type="password" name="widget-lostPassword-form-new-password" class="form-control required" autocomplete="new-password">
                                </div>
                            </div>
                            <input type="text" class="hidden" id="widget-lostPassword-form-antispam" name="widget-lostPassword-form-antispam" value="" />
                            <?php
                            if(isset($_GET['hash'])){
                                ?>
                                <input type="text" class="hidden" id="widget-lostPassword-form-hash" name="widget-lostPassword-form-hash" value="<?php echo $_GET['hash'] ?>" />
                                <?php
                            }
                            else{
                                ?>
                                <input type="text" class="hidden" id="widget-lostPassword-form-hash" name="widget-lostPassword-form-hash"/>
                                <?php
                            }
                            ?>
                            <button  id="fr" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Envoyer</button>
                            <button  id="en" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Confirm</button>
                            <button  id="nl" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Verzenden</button>
                        </form>
							<script type="text/javascript">
                                jQuery("#widget-lostPassword-form").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
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
		</div>
	</div>
	</div>

		


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

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>

<?php
    if(isset($_GET['hash'])){
	?>
	<script type="text/javascript">
	$('#newPassword').modal('toggle');
	</script>
	
<?php
	checkHash($GET['HASH']);
}
?>


<?php
    if(isset($_GET['deconnexion']) && $_GET['deconnexion']==true ){
		if ($_SESSION['langue']=='en')
		{
			$message = "You have been disconnected due to inactivity";
		}
		elseif ($_SESSION['langue']=='nl')
		{
			$message = "U bent afgesloten vanwege inactiviteit";		
		}
		else
		{
			$message = "Vous avez été déconnecté pour cause d\'inactivité";		
		}
	?>
        <script type="text/javascript">
			test="<?php echo $message;?>";
			console.log("deconnexion:"+test);
            $.notify({
                message: '<?php echo $message; ?>'
            }, {
                type: 'danger'
            });
        </script>
	
<?php
}
?>

</body>

</html>
