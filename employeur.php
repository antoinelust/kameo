<?php 
include 'include/header3.php';
?>

<div id="partage"></div>

<!-- TOP PAGE -->
<section class="box-fancy section-fullwidth text-light no-padding">
	<div class="row">
		<div class="col-md-6 text-center" style="background-color: #3cb395">
			<a class="button green button-3d effect fill-vertica scroll-to" href="#partage"><span><i class="fa fa-users"></i>  VÉLO PARTAGÉ  </span></a>
			</span>
		</div>

		<div class="col-md-6 text-center" style="background-color: #1D9377">
			<a class="button green button-3d effect fill-vertica scroll-to" href="#personel"><span><i class="fa fa-user"></i>  VÉLO PERSONNEL  </span></a>
			</span>
		</div>
	</div>
</section>
<!-- END: TOP PAGE -->

<!-- SECTION SOLUTIONS -->
<section class="p-b-0 background-green">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<!-- <hr class="space"> -->
				<h1 class="text-light">KAMEO Bikes,<br>
				URBAN MOBILITY SOLUTIONS<br>				FOR BUSINESSES</h1>
				<p class="lead"><strong class="text-light">KAMEO Bikes</strong>  offre des<br>					- <mark>Solutions :</mark> Nous ne fournissons pas uniquement le vélo, les services liés ou la plateforme de gestion mais bien l’ensemble ;<br>					- <mark>De mobilité urbaine :</mark> l’objectif de la solution est de simplifier et rendre plus efficace les déplacements en milieu urbain (dans le sens de proximité) ;<br>					- <mark>Pour entreprises :</mark> La cible et l’expertise de KAMEO sont les entreprises.<br></p>
			</div>
			<div class="col-md-4">
				<img src="images/Solution.png" class="img-responsive img-rounded" alt="">
			</div>
			<hr class="space">
			
		</div>
	</div>
</section>
<!-- END: SECTION SOLUTIONS -->



<!-- SECTION GESTION FLOTTE -->
<section class="p-b-0">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<!-- <hr class="space"> -->
				<h1 class="text-green">OFFRE DE PRIX</h1>
				<p>Dés vélos, un accès à MYKAMEO, une borne connectée, une assurance  et des accessoires. <br>				Retrouvez ici différentes propositions «types» pour vous aider à mieux comprendre notre solution.</p>
				<a class="button green button-3d effect fill-vertical" href=""><span><i class="fa fa-plus"></i>Achat ou Leasing</span></a>
			</div>
			<div class="col-md-8">
			</div>
		</div>
	</div>
</section>
<!-- END: SECTION GESTION FLOTTE -->

<!-- Language management -->
<script type="text/javascript" src="js/language.js"></script>
				
				
<!-- SECTION CLIENTS -->
<section class="p-b-0">
	<div class="container">
		<h1 class="text-green">ILS NOUS FONT CONFIANCE</h1>
        
        <ul class="grid grid-5-columns">
				
				<li>
					<img src="images/afelio.jpg" alt="client vélo électrique Bruxelles - Afelio">
				</li>
				<li>
					<img src="images/spi.jpg" alt="client vélo électrique Bruxelles - SPI">
				</li>
				<li>
					<img src="images/siapartners.jpg" alt="client vélo électrique Bruxelles - SiaPartners">
				</li>
				
				<li>
					<img src="images/DEDALE.jpg" alt="client vélo électrique Liège et Bruxelles - Deliveroo">
				</li>
				
				<li>
					<img src="images/deliveroo.jpg" alt="client vélo électrique Liège et Bruxelles - Deliveroo">
				</li>
				
				<li>
					<img src="images/ATRADIUS.jpg" alt="client vélo électrique Liège et Bruxelles - Deliveroo">
				</li>
				
				<li>
					<img src="images/venturelab.jpg" alt="client vélo électrique Liège - Venturelab">
				</li>
				
				<li>
					<img src="images/chu.jpg" alt="client vélo électrique Liège - CHU Liège">
				</li>
				
				<li>
					<img src="images/AGC.jpg" alt="client vélo électrique Liège - AGC">
				</li>
				
				<li>
					<img src="images/Epsylon.jpg" alt="client vélo électrique Liège - Epsylon">
				</li>
				
				<li>
					<img src="images/IDEA.jpg" alt="client vélo électrique Liège - IDEA">
				</li>
				
				<li>
					<img src="images/Galler.jpg" alt="client vélo électrique Liège - Galler">
				</li>
				
				<li>
					<img src="images/CIE.jpg" alt="client vélo électrique Liège - CIE">
				</li>
				
				<li>
					<img src="images/RAYON9.jpg" alt="client vélo électrique Liège et Bruxelles - Deliveroo">
				</li>
				
				<li>
					<img src="images/Kartell.jpg" alt="client vélo électrique Liège - Kartell+">
				</li>
		</ul>
        
	</div>
</section>
<!-- END: SECTION CLIENTS -->
		
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
                                                console.log(text);
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
	            
	           
					<div class="button green full-rounded"><a href="newsletter.php" class="text-light text-bold">Newsletter</a> <!--| <a href="faq.php" class="text-green text-bold">FAQ</a>--></div>
				
	            
	                <div class="copyright-text text-center"><ins>Kameo Bikes SPRL</ins> 
						<br>BE 0681.879.712 
						<br>+32 498 72 75 46 </div>
						<br>
	                <div class="social-icons center">
								<ul>
									<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
									
									<li class="social-linkedin"><a href="https://www.linkedin.com/company/kameobikes/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
									
									<li class="social-instagram"><a href="https://www.instagram.com/kameobikes/" target="_blank"><i class="fa fa-instagram"></i></a></li>
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
