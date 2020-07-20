<?php 
include 'include/header5.php';
?>
<script type="text/javascript" src="js/cash4bike.js"></script>
		<!--Square icons-->
  <section>
	
	<div class="container">
		<div class="row">
				<h1 class="text-green">CALCULATEUR CASH FOR BIKE</h1>
				<br>
				<p>Les informations demandées ci-dessous ne seront en aucun cas enregistrées dans nos bases de données.<br>Elles servent à vous communiquer un montant le plus proche de la réalité.</p>
				
				<div class="m-t-30 col-md-12">
                	<form id="cash4bike-form" action="apis/Kameo/calculate_cash4bike.php" role="form" method="get">
                    <div class="row">
                        <div class="col-md-6" style= "background-color: #D3EFDD ; height: 500px">
                        <div class="space"></div>
                            <h4 class="text-green">Informations personnelles</h4>

                            <div class="form-group col-md-12 ">
                                <div class="employe">
                                    <label><input type="radio" name="type" value="employe" checked> Je suis un employé</label>
                                </div>
                                <div class="ouvrier">
                                    <label><input type="radio" name="type" value="ouvrier"> Je suis un ouvrier</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label class="revenu" for="phone">Votre revenu mensuel brut :</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">€/mois</span>
                                            <input type="number" class="form-control required" min='0' placeholder="0" name="revenu" id="revenu" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <div class="col-md-12">  
                                <div id="inputHomeAddress" class="form-group has-error has-feedback">							
                                  <label class="control-label" for="domicile">Adresse du domicile</label>							
                                  <input type="text" name="domicile" class="form-control" aria-describedby="inputSuccess1Status" placeholder="Rue, numéro, code postal, commune">							
                                  <span id="inputHomeAddress2" class="fa fa-close form-control-feedback" aria-hidden="true"></span> 
                                  <span id="inputSuccess1Status" class="sr-only">(success)</span> 
                                </div>                                
                                <div id="inputWorkAddress" class="form-group has-error has-feedback">							
                                  <label class="control-label" for="inputSuccess2">Adresse du lieu de travail</label>							
                                  <input type="text" name="travail" class="form-control" aria-describedby="inputSuccess2Status" placeholder="Rue, numéro, code postal, commune">							
                                  <span id='inputWorkAddress2' class="fa fa-close form-control-feedback" aria-hidden="true"></span> 
                                  <span id="inputSuccess2Status" class="sr-only">(success)</span> 
                                </div>                                
                            </div>
                            <div class="space"></div>
                        </div>
                        <div class="col-md-6" style= "background-color: #E6E6E6 ; height: 500px">
                        <div class="space"></div>
                            <h4 class="text-green">Moyen de transport</h4>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <label for="transport" class="fr">Votre moyen de transport actuel :</label>
                                    <select class="form-control" name="transport">
                                        <option value="personnalCar" selected>Voiture personnelle</option>
                                        <option value="companyCar">Voiture de société</option>
                                        <option value="covoiturage">Covoiturage</option>
                                        <option value="public transport">Transport en commun</option>
                                        <option value="personalBike">Vélo personnel</option>
                                        <option value="walk">Marche</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6 essence">
                                    <div class="essence">
                                        <label><input type="radio" name="transportationEssence" value="essence" checked> Essence</label>
                                    </div>
                                    <div class="diesel">
                                        <label><input type="radio" name="transportationEssence" value="diesel"> Diesel</label>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group col-md-12">
                                                                
                                
                                
                                <div class="col-md-12">
                                    <div class="employeurremunere">
                                        <label><input type="radio" name="prime" value="1" checked> Mon employeur rémunère mes kilomètres vélo</label>
                                    </div>
                                    <div class="employeurneremunerepas">
                                        <label><input type="radio" name="prime" value="0"> Mon employeur ne me rémunère par les kilomètres vélo</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <label for="frequence" class="fr">Vous comptez aller au vélo :</label>
                                    <select class="form-control" name="frequence">
                                        <option value="1">1 fois par semaine</option>
                                        <option value="2">2 fois par semaine</option>
                                        <option value="3">3 fois par semaine</option>
                                        <option value="4" selected>4 fois par semaine</option>
                                        <option value="5">5 fois par semaine</option>
                                    </select>
                                </div>
                            </div>
                            
                            
                            
                            <div class="space visible-md visible-lg"></div>
                            <div class="space visible-md visible-lg"></div>
                            <div class="space visible-md visible-lg"></div>
                            <div class="space visible-md visible-lg"></div>
                            <br><br>
                        </div>                                                
                        <div class="separator"></div>
                        
                        <h4 class="text-green">Vélo souhaité</h4>
                        
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <label for="brand">Marque</label>
                                <select class="from-control" name='brand'>
                                    <option value="selection">Veuillez sélectionner</option>
                                </select>
                            </div>
                            <div class="col-md-4 model hidden">
                                <label for="brand">Modèle</label>
                                <select class="from-control" name='model'>
                                    <option value="selection">Veuillez sélectionner</option>
                                </select>
                            </div>
                            
                        </div>
                        
                        <div class="col-md-12 bike_picture hidden">
                        	<div class="space"></div>
                            <h4 id="bike_price" class="text-center"></h4>
                            <h4 id="bike_leasing_price" class="text-center"></h4>
                            <img id="bike_picture" alt="image" class="centerimg" />
                        </div>
                        
                        <input type="int" name="leasingAmount" class="hidden">							
						
						<div class="separator"></div>
                        
						<div class="form-group col-md-2 center">
                            <button class="button green button-3d effect fill-vertical fr " type="submit"><i class="fa fa-calculator"></i>&nbsp;Calculer</button>
                        </div>
                    </div>
                    </form>
                    
                <script type="text/javascript">
                  jQuery("#cash4bike-form").validate({

                    submitHandler: function(form) {
                      
                        jQuery(form).ajaxSubmit({
                            success: function(response) {
                                if (response.response == 'error'){
                                    $.notify({
                                      message: response.message
                                    }, {
                                      type: 'danger'
                                    });
                                }else{
                                    $.notify({
                                      message: response.message
                                    }, {
                                      type: 'success'
                                    });
                                    
                                    $('#resultCash4Bike').removeClass('hidden');
                                    
                                    console.log(response);
                                    
                                    if(response.totalImpact>=0){
                                        $('#impactOnNetSalary').html("Coût réel du vélo : "+response.totalImpact+" €/mois")
                                        $('#impactOnNetSalaryText').html("En souscrivant à une location, cela vous coutera réellement "+response.totalImpact+"€ par mois. <br>Ce montant comprend votre vélo (référence/modèle), une assurance p-vélo et un entretien annuel.")
                                    }else if(response.totalImpact<0){
                                        $('#impactOnNetSalary').html("Gain réalisé grâce au vélo : "+Math.abs(response.totalImpact)+" €/mois")
                                        $('#impactOnNetSalaryText').html("En souscrivant à une location, vous économiserez "+Math.abs(response.totalImpact)+"€ par mois. En d'autre termes, avoir un vélo en leasing vous fera gagner de l'argent !<br>Votre vélo, une assurance p-vélo et un entretien annuel sont inclus.")
                                        
                                    }
                                    
                                    if(response.impactCarSavingCO2>0){
                                        $('#impactOnCO2').html("Gain de CO2 réalisé par mois : "+response.impactCarSavingCO2+" kg.CO2/mois")
                                    }
                                }
                            }
                        })
                    }
                  })
                </script>
                            
                    
                    
                            
				</div>
		</div>
		
		<div class="space"></div>
		
		<!-- RESULTAT -->
		<div id='resultCash4Bike' class="jumbotron jumbotron-center jumbotron-fullwidth background-green hidden">
		  <div class="container">
		    <h3 class="text-light" id='impactOnNetSalary'></h3>
		    <h3 class="text-light" id='impactOnCO2'></h3>
		    <p class="text-light" id='impactOnNetSalaryText'></p>
		  
		    <a class="button black-light button-3d effect fill-vertical"  data-target="#detail" data-toggle="modal" href="#"><span><i class="fa fa-send"></i>Demandez le détail de votre calcul</span></a>
		</div>            
            
		
            

            
            
<div class="modal fade" id="detail" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
            <form id="cash4bike-form-contact" action="include/contact_cash4bike.php" role="form" method="post">
            
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h2 class="modal-title text-green" id="modal-label">Veuillez compléter vos informations de contact</h2>
			</div>
			<div class="modal-body">
				<div class="row text-left">
					<div class="form-group col-sm-12">
                    	<label for="firstName" class="fr">Prénom</label>
                        <input type="text" aria-required="true" name="firstName" class="form-control required is-invalid">
                    </div>
					<div class="form-group col-sm-12">
                    	<label for="name" class="fr">Nom</label>
                        <input type="text" aria-required="true" name="name" class="form-control required is-invalid">
                    </div>
                    <div class="form-group col-sm-12">
                    	<label for="email" class="fr">Email</label>
                        <input type="text" aria-required="true" name="email" class="form-control required is-invalid">
                    </div>
                    <div class="form-group col-sm-12">
                    	<label for="entreprise" class="fr">Entreprise</label>
                        <input type="text" aria-required="true" name="entreprise" class="form-control required is-invalid">
                    </div>
				</div>
			</div>
            <input type="text" name="type" class="form-control hidden">
            <input type="text" name="revenu" class="form-control hidden">
            <input type="text" name="domicile" class="form-control hidden">
            <input type="text" name="travail" class="form-control hidden">
            <input type="text" name="transport" class="form-control hidden">
            <input type="text" name="transportationEssence" class="form-control hidden">
            <input type="text" name="frequence" class="form-control hidden">
            <input type="text" name="model" class="form-control hidden">
            <input type="text" name="prime" class="form-control hidden">
			<div class="modal-footer">
				<button type="submit" class="button green button-3d effect fill-vertical">Envoyer</button>
			</div>
            </form>
                    
            <script type="text/javascript">
              jQuery("#cash4bike-form-contact").validate({                  
                submitHandler: function(form) {
                  jQuery(form).ajaxSubmit({
                    success: function(response) {                        
                      if (response.response == 'error'){
                            $.notify({
                              message: response.message
                            }, {
                              type: 'danger'
                            });
                      }else{
                            $.notify({
                              message: response.message
                            }, {
                              type: 'success'
                            });
                            document.getElementById('cash4bike-form-contact').reset();
                      }
                    }
                  })
                }
              });
                
            </script>
            
          <div class="fr" class="modal-footer">
            <button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
          </div>
          <div class="en" class="modal-footer">
            <button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
          </div>
          <div class="nl" class="modal-footer">
            <button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
          </div>
		</div>
	</div>
</div>


		<!--END: RESULTAT -->
		
	</div>
        
    <h3> Louer un vélo et le payer via mon salaire brut par l’entreprise, un coût ou un gain d’argent ?</h3>
    <p>En Belgique vous avez la possibilité d'échanger une partie de votre rémunération brute totale pour la placer dans un autre avantage. C’est le principe d’un plan caféteria.<br>
    Retrouvez <a href="https://www.securex.be/fr/gestion-du-personnel/couts-salariaux/optimaliser-votre-charge-salariale/plan-cafeteria" class="text-green" target="_blank">ici plus d’information</a> sur ce système.</p>
    <p class="fr">Nous vous proposons de calculer le gain ou la perte de rémunération net si vous décidez de diminuer votre rémunération mensuelle brute afin de prendre un vélo pour vos trajets domicile-travail.</p>
    <p class="en">Nous vous proposons de calculer le gain ou la perte de rémunération net si vous décidez de diminuer votre rémunération mensuelle brute afin de prendre un vélo pour vos trajets domicile-travail.</p>
    <p class="nl">Nous vous proposons de calculer le gain ou la perte de rémunération net si vous décidez de diminuer votre rémunération mensuelle brute afin de prendre un vélo pour vos trajets domicile-travail.</p>
    <p class="fr">Attention, ceci doit se faire à cout équivalent pour l’employeur. Si votre salaire brut est diminué (par exemple) de 50€, vous avez accès à un budget plus important pour le choix de votre vélo. En effet sur vos 50€ brut, l‘employeur paye des taxes supplémentaires. Puisque ceci doit se faire à coût équivalent pour l’employeur, ce montant sera à votre disposition. <strong>C’est un avantage supplémentaire !</strong></p>
    <p class="en">Attention, ceci doit se faire à cout équivalent pour l’employeur. Si votre salaire brut est diminué (par exemple) de 50€, vous avez accès à un budget plus important pour le choix de votre vélo. En effet sur vos 50€ brut, l‘employeur paye des taxes supplémentaires. Puisque ceci doit se faire à coût équivalent pour l’employeur, ce montant sera à votre disposition. <strong>C’est un avantage supplémentaire !</strong></p>
    <p class="nl">Attention, ceci doit se faire à cout équivalent pour l’employeur. Si votre salaire brut est diminué (par exemple) de 50€, vous avez accès à un budget plus important pour le choix de votre vélo. En effet sur vos 50€ brut, l‘employeur paye des taxes supplémentaires. Puisque ceci doit se faire à coût équivalent pour l’employeur, ce montant sera à votre disposition. <strong>C’est un avantage supplémentaire !</strong></p>
    <br>
        
    <div class="separator"></div>
        
        
	<h3>Disclaimer</h3>
    <p>Cet outil est mis à votre disposition à titre exclusivement informatif et il s'agit d'une simulation de calcul effectuée à titre purement indicatif.</p>
    <p>L’outil a été élaboré avec le plus grand soin et nous nous efforçons, dans la mesure du raisonnable, à l’actualiser et à maintenir l'exactitude des informations qui s’y trouvent, sachant que les législations changent fréquemment. De plus, pour faciliter l’utilisation de l’outil, certaines données ne sont pas prises en considération pour le calcul. Dès lors, il se peut qu’il y ait une différence entre le montant calculé et le montant réel.</p>
    <p>Les informations reprises ne remplacent en aucun cas un avis juridique ou l’assistance personnalisée d’un professionnel.</p>
    <p>Dans la mesure autorisée par la loi, nous ne sommes en aucun cas être tenus responsables de tout dommage, direct ou indirect, de quelque nature et importance qu’il soit, qui pourrait être causé directement ou indirectement par la consultation ou, plus généralement, par toute utilisation quelconque qui serait faite de cet outil et notamment des informations qui s’y trouvent.</p>
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
