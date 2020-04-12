<?php 
include 'include/header5.php';
?>

<script type="text/javascript">

                            
</script>

<div class="modal fade" id="GDPR" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h2 class="modal-title fr" id="modal-label">Vos données personnelles</h2>
				<h2 class="modal-title en" id="modal-label">Your personal data</h2>
				<h2 class="modal-title nl" id="modal-label">Uw persoonlijk gegevens</h2>
			</div>
			<div class="modal-body">
				<div class="row text-center">
					<div class="col-md-12">
						<p class="fr" >Dans le but de vous offrir une meilleure expérience utilisateur, nous utilisons des cookies spécifiques.</p>
						<p class="en" >In order to ahDans le but de vous offrir une meilleure expérience utilisateur, nous utilisons des cookies spécifiques.</p>
						<p class="nl" >Ja zeker.</p>
						<p>Veuillez accepter l'utilisation de ceux-ci ou découvrir à quoi ils nous sont utiles.</p>
						<p>Vous pourrez toujours changer vos paramètres plus tard.</p>
						<!--<p class="text-green">ATTENTION: Antoine doit paramétrer ce popup pour qu'il ne s'ouvre qu'à chaque nouvel utilisateur. Une fois validé il ne s'affichera plus.<br>
						Voir avec Megge et/ou Désiré les informations légales à y mettre.</p>-->
						<a class="button green button-3d effect fill-vertical GDPRaccept" href="#" data-dismiss="modal" aria-hidden="true"><span><i class="fa fa-thumbs-o-up"></i>J'accepte</span></a><br>
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
<script type='text/javascript'>
    
    function set_GDPR_cookie(){
        $.ajax({
            url: 'include/cookie_management.php',
            type: 'post',
            data: {action:"set", type: "GDPR"},
            success: function(response){
              if(response.response == 'error') {
                console.log(response.message);
              }
              if(response.response == 'success'){

              }
            }
        })
    }
    
    //document.getElementsByClassName('GDPRaccept')[0].addEventListener('click', function() { set_GDPR_cookie()}, false);
    
</script>

<br>
<h1 class="text-center text-uppercase text-medium fr" data-animation="fadeInUp">KAMEO Bikes, BITCH votre one stop shop pour vos vélos de société</h1>
<h1 class="text-center text-uppercase text-medium en" data-animation="fadeInUp">KAMEO Bikes, the complete solution for your company bikes</h1>
<h1 class="text-center text-uppercase text-medium nl" data-animation="fadeInUp">KAMEO Bikes, de complete oplossing voor uw bedrijfsfietsen</h1>



<img src="images/background_new.jpg" class="img-responsive img-rounded" alt="KAMEO Bikes, votre one stop shop pour vos vélos de société">


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
			<span class="">Pédalez complètement équipé sur le vélo de vos rêves avec KAMEO Bikes <br>
			<a class="button green button-3d effect fill-vertical"  data-target="#employe" data-toggle="modal" href="#"><span><i class="fa fa-plus"></i>En savoir plus</span></a>
			</span>
		</div>
		
		<!-- CALL TO ACTION -->
		<div class="jumbotron jumbotron-center jumbotron-fullwidthtext-light" style="background: url('images/Fond_Site_Black.jpg');" data-stellar-background-ratio="0.3">
		  <div class="container">
		    <h3> Louer un vélo et le payer via mon salaire brut par l’entreprise, un coût ou un gain d’argent ?</h3>
		    <p>Nous avons développé un calculateur permettant de le savoir.</p>
		    <a class="button large green button-3d effect icon-left" href="cash4bike.php"><span><i class="fa fa-calculator"></i>Faire le calcul</span></a> </div>
		</div>
		<!--END: CALL TO ACTION -->
		
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
				<h1 class="text-green">POURQUOI CHOISIR KAMEO BIKES?</h1>
				<br>
				<p class="fr text-justify">KAMEO Bikes est votre <strong class="text-green">one stop shop</strong> pour vos vélos de société.</p>
				<p class="text-justify">Nos solutions s’appuient sur <strong>des cycles de qualité</strong>, <strong>une maintenance continue</strong> et <strong>une gestion connectée de vos vélos</strong>. L’ensemble vous garantit une expérience cyclable optimale, quelles que soient les circonstances.</p>
				<p class="text-justify">Avec KAMEO, les entreprises ont accès à une solution de mobilité urbaine complète, flexible et sur mesure. Bref, vous êtes toujours en mouvement.</p>
				<p>Tous les services proposés sont gérés par KAMEO Bikes. On s'occupe de tout, vous restez concentré sur votre activité!</p>
			</div>
			<div class="col-md-6">
				<img src="images/RoueKameo.png" class="img-responsive img-rounded" alt="Roue des services KAMEO Bikes">
			</div>
			<div class="col-md-12 text-center" style="background: url('images/fond_degrade2.jpg');" data-stellar-background-ratio="0.6"><br>
					<h4>Respect de l'environnement</h4>
					<h4>Incitants fiscaux</h4>
					<h4>Gain de temps</h4>
					<h4>Productivité et bien-être des employés</h4>
					<h4>Combinaison avec la voiture de société</h4>
					<h4>Attirer de nouveaux talents</h4>
					<h4>Mode de vie sain</h4>
			</div>
		</div>
	</div>
</section>


<!-- Language management -->
<script type="text/javascript" src="js/language.js"></script>
				
				
<!-- SECTION CLIENTS -->
<section class="p-b-0">
	<div class="container">
		<h1 class="text-green">ILS NOUS FONT CONFIANCE</h1>
        
        <ul class="grid grid-4-columns">
				
				<li>
					<img src="images/clients/afelio.png" alt="Nos clients - Afelio">
				</li>
				<li>
					<img src="images/clients/atradius.png" alt="Nos clients - Atradius">
				</li>
				<li>
					<img src="images/clients/galler.png" alt="Nos clients - Galler Chocolatiers">
				</li>
				<li>
					<img src="images/clients/siapartners.png" alt="Nos clients - SiaPartners">
				</li>
				<li>
					<img src="images/clients/spi.png" alt="Nos clients - SPI">
				</li>
				<li>
					<img src="images/clients/agc.png" alt="Nos clients - AGC">
				</li>
				<li>
					<img src="images/clients/rayon9.png" alt="Nos clients - Rayon 9">
				</li>
				<li>
					<img src="images/clients/chu.png" alt="Nos clients - CHU Liège">
				</li>
				<li>
					<img src="images/clients/dedale.png" alt="Nos clients - Dedale Assurances">
				</li>
				<li>
					<img src="images/clients/elegis.png" alt="Nos clients - Elegis">
				</li>
				<li>
					<img src="images/clients/epsylon.png" alt="Nos clients - Epsylon">
				</li>
				<li>
					<img src="images/clients/infine.png" alt="Nos clients - In Fine">
				</li>
				<li>
					<img src="images/clients/idea.png" alt="Nos clients - IDEA">
				</li>
				<li>
					<img src="images/clients/bxlville.png" alt="Nos clients - Ville de Bruxelles">
				</li>
				<li>
					<img src="images/clients/prefer.png" alt="Nos clients - Prefer">
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
