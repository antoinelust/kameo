<?php 
include 'include/header5.php';
?>

<div class="modal fade" id="donnees" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h2 class="modal-title" id="modal-label">Vos données personnelles</h2>
			</div>
			<div class="modal-body">
				<div class="row text-center">
					<div class="col-md-12">
						<p>Dans le but de vous offrir une meilleure expérience utilisateur, nous utilisons des cookies spécifiques.</p>
						<p>Veuillez accepter l'utilisation de ceux-ci ou découvrir à quoi ils nous sont utiles.</p>
						<p>Vous pourrez toujours changer vos paramètres plus tard.</p>
						<p class="text-green">ATTENTION: Antoine doit paramétrer ce popup pour qu'il ne s'ouvre qu'à chaque nouvel utilisateur. Une fois validé il ne s'affichera plus.<br>
						Voir avec Megge et/ou Désiré les informations légales à y mettre.</p>
						<a class="button green button-3d effect fill-vertical" href="#" data-dismiss="modal" aria-hidden="true"><span><i class="fa fa-thumbs-o-up"></i>J'accepte</span></a><br>
						<a class="button button-3d effect fill-vertical" href="#" data-dismiss="modal" aria-hidden="true"><span><i class="fa fa-info"></i>En savoir plus</span></a>
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


<br>
<h1 class="text-center text-uppercase text-medium fr" data-animation="fadeInUp">KAMEO Bikes, la solution complète pour vos vélos de société</h1>
<h1 class="text-center text-uppercase text-medium en" data-animation="fadeInUp">KAMEO Bikes, the complete solution for your company bikes</h1>
<h1 class="text-center text-uppercase text-medium nl" data-animation="fadeInUp">KAMEO Bikes, de complete oplossing voor uw bedrijfsfietsen</h1>



<img src="images/background_new.jpg" class="img-responsive img-rounded" alt="">


<!-- MISSION & VISSION -->
<section class="box-fancy section-fullwidth text-light no-padding">
	<div class="row">
		<div class="col-md-6 text-center" style="background-color: #3cb395">
			<h2>JE SUIS UN EMPLOYEUR</h2>
			<span class="">KAMEO Bikes vous offre des solutions de mobilité urbaine pour entreprises.<br>
			<a class="button green button-3d effect fill-vertical"  data-target="#employeur" data-toggle="modal" href="#"><span><i class="fa fa-key"></i>Découvrir nos solutions</span></a>
			</span>
		</div>

		<div class="col-md-6 text-center" style="background-color: #1D9377">
			<h2>JE SUIS UN EMPLOYÉ</h2>
			<span class="">KAMEO Bikes vous fournit en vélos mais aussi en accessoires.<br>
			<a class="button green button-3d effect fill-vertical"  data-target="#employe" data-toggle="modal" href="#"><span><i class="fa fa-plus"></i>En savoir plus</span></a>
			</span>
		</div>
	</div>
</section>
<!-- END: MISSION & VISSION -->

<div class="modal fade" id="employeur" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h2 class="modal-title" id="modal-label">Je suis un employeur et je recherche</h2>
			</div>
			<div class="modal-body">
				<div class="row text-center">
					<div class="col-md-12">
						<h4 class="text-green">Des vélos partagés</h4>
						<p>Je souhaite mettre à disposition de mes employés une flotte de vélos.</p>
						<a class="button green button-3d effect fill-vertical" href="velo-partage.php"><span><i class="fa fa-users"></i>Je découvre</span></a>
					</div>
					<div class="separator"></div>
					<div class="col-md-12">
						<h4 class="text-green">Des vélos personnels</h4>
						<p>Je souhaite proposer à mes employés d'acheter un vélo via mon entreprise.</p>
						<a class="button green button-3d effect fill-vertical" href="velo-personnel.php"><span><i class="fa fa-user"></i>En savoir plus</span></a>
					</div>
					<div class="separator"></div>
					<div class="col-md-12">
						<h4 class="text-red">Un système de gestion de flotte</h4>
						<p>Je dispose déjà de vélos ou de véhicules mais j'aimerais optimiser leur utilisation avec un système simple et fluide.</p>
						<a class="button red button-3d effect fill-vertical" href="gestion-flotte.php"><span><i class="fa fa-laptop"></i>On vous propose</span></a>
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

<div class="modal fade" id="employe" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h2 class="modal-title" id="modal-label">Je suis un employé et je recherche</h2>
			</div>
			<div class="modal-body">
				<div class="row text-center">
					<div class="col-md-12">
						<h4 class="text-green">Un vélo</h4>
						<p>Je souhaite acheter un vélo.</p>
						<a class="button green button-3d effect fill-vertical" href="achat.php"><span><i class="fa fa-bicycle"></i>Catalogue vélos</span></a>
					</div>
					<div class="separator"></div>
					<div class="col-md-12">
						<h4 class="text-green">Des accessoires</h4>
						<p>Je souhaite m'équiper d'accessoires.</p>
						<a class="button green button-3d effect fill-vertical" href="accessoires.php"><span><i class="fa fa-diamond"></i>Catalogue accessoires</span></a>
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


<section>
	
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h1 class="text-green">EN QUELQUES MOTS...</h1>
				<br>
				<p class="fr">KAMEO Bikes est votre <strong class="text-green">one stop shop</strong> pour vos vélos de société.</p>
				<p>Nos solutions s’appuient sur <strong>des cycles de qualité</strong>, <strong>une maintenance continue</strong> et <strong>une gestion connectée de vos vélos</strong>. L’ensemble vous garantit une expérience cyclable optimale, quelles que soient les circonstances.</p>
				<p>Avec KAMEO, les entreprises ont accès à une solution de mobilité urbaine complète, flexible et sur mesure. Bref, vous êtes toujours en mouvement.</p>
			</div>
			<div class="col-md-6">
				<img src="images/RoueKameo.png" class="img-responsive img-rounded img-thumbnail" alt="">
			</div>
			<div class="separator"></div>
			<div class="col-md-12">
				<h1 class="text-green text-center">POURQUOI CHOISIR KAMEO BIKES?</h1>
				<br>
				<div class="col-md-6 text-right">
					<h3>Avantage pour l'employeur</h3>
					<ul class="list-unstyled">
						<li>Respect de l'environnement</li>
						<li>Incitants fiscaux</li>
						<li>Productivité et bien-être des employés</li>
						<li>Attirer de nouveaux talents</li>
						<li>Vélos de qualité et fiables</li>
						<li>Offre et services personnalisés</li>
					</ul>
				</div>
				<div class="col-md-6 text-left">
					<h3>Avantage pour l'employé</h3>
					<ul class="list-unstyled">
						<li>Respect de l'environnement</li>
						<li>Incitants fiscaux</li>
						<li>Gain de temps</li>
						<li>Combinaison avec la voiture de société</li>
						<li>Mode de vie sain</li>
						<li>Offre et services personnalisés</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

			
<!-- CALL TO ACTION -->
<div class="jumbotron jumbotron-center jumbotron-fullwidth background-green text-light">
	<div class="container">
		<h3>ENVIE D'EN SAVOIR PLUS SUR LES AVANTAGES?</h3>
		<a class="button large black-light button-3d effect icon-left" href="avantages.php"><span><i class="fa fa-plus"></i>En savoir plus</span></a> </div>
	</div>
</div>
<!--END: CALL TO ACTION -->

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


<script type="text/javascript">
    $(document).ready(function(){
        $("#donnees").modal('show');
    });
</script>

</body>

</html>
