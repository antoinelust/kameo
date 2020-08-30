<!DOCTYPE html>
<html lang="fr">
<?php 
	include 'include/head.php';
?>
<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include 'include/topbar.php'; ?>
		<?php include 'include/header.php'; ?>
<script type="text/javascript" src="js/cash4bike.js"></script>
		<!--Square icons-->
  <section>
	
	<div class="container">
		<div class="row">
				<h1 class="text-green"><?=L::cash4bike_title;?></h1>
				<br>
				<p><?=L::cash4bike_subtitle;?><br><?=L::cash4bike_subtitle2;?></p>
				
				<div class="m-t-30 col-md-12">
                	<form id="cash4bike-form" action="apis/Kameo/calculate_cash4bike.php" role="form" method="get">
                    <div class="row">
                        <div class="col-md-6" style= "background-color: #D3EFDD ; height: 500px">
                        <div class="space"></div>
                            <h4 class="text-green"><?=L::cash4bike_personalinfo_title;?></h4>

                            <div class="form-group col-md-12 ">
                                <div class="employe">
                                    <label><input type="radio" name="type" value="employe" checked><?=L::cash4bike_personalinfo_employee;?></label>
                                </div>
                                <div class="ouvrier">
                                    <label><input type="radio" name="type" value="ouvrier"><?=L::cash4bike_personalinfo_ouvrier;?></label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label class="revenu" for="phone"><?=L::cash4bike_personalinfo_brutsalary;?></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?=L::cash4bike_personalinfo_permonth;?></span>
                                            <input type="number" class="form-control required" min='0' placeholder="0" name="revenu" id="revenu" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <div class="col-md-12">  
                                <div id="inputHomeAddress" class="form-group has-error has-feedback">							
                                  <label class="control-label" for="domicile"><?=L::cash4bike_personalinfo_address;?></label>
                                  <input type="text" name="domicile" class="form-control" aria-describedby="inputSuccess1Status" placeholder="Rue, numéro, code postal, commune">							
                                  <span id="inputHomeAddress2" class="fa fa-close form-control-feedback" aria-hidden="true"></span> 
                                  <span id="inputSuccess1Status" class="sr-only">(success)</span> 
                                </div>                                
                                <div id="inputWorkAddress" class="form-group has-error has-feedback">							
                                  <label class="control-label" for="inputSuccess2"><?=L::cash4bike_personalinfo_workaddress;?></label>
                                  <input type="text" name="travail" class="form-control" aria-describedby="inputSuccess2Status" placeholder="Rue, numéro, code postal, commune">							
                                  <span id='inputWorkAddress2' class="fa fa-close form-control-feedback" aria-hidden="true"></span> 
                                  <span id="inputSuccess2Status" class="sr-only">(success)</span> 
                                </div>                                
                            </div>
                            <div class="space"></div>
                        </div>
                        <div class="col-md-6" style= "background-color: #E6E6E6 ; height: 500px">
                        <div class="space"></div>
                            <h4 class="text-green"><?=L::cash4bike_transport_title;?></h4>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <label for="transport"><?=L::cash4bike_transport_choice;?></label>
                                    <select class="form-control" name="transport">
                                        <option value="personnalCar" selected><?=L::cash4bike_tc_personalcar;?></option>
                                        <option value="companyCar"><?=L::cash4bike_tc_workcar;?></option>
                                        <option value="covoiturage"><?=L::cash4bike_tc_covoiturage;?></option>
                                        <option value="public transport"><?=L::cash4bike_tc_commun;?></option>
                                        <option value="personalBike"><?=L::cash4bike_tc_personalbike;?></option>
                                        <option value="walk"><?=L::cash4bike_tc_walk;?></option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6 essence">
                                    <div class="essence">
                                        <label><input type="radio" name="transportationEssence" value="essence" checked><?=L::cash4bike_essence;?></label>
                                    </div>
                                    <div class="diesel">
                                        <label><input type="radio" name="transportationEssence" value="diesel"><?=L::cash4bike_diesel;?></label>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group col-md-12">
                                                                
                                
                                
                                <div class="col-md-12">
                                    <div class="employeurremunere">
                                        <label><input type="radio" name="prime" value="1" checked><?=L::cash4bike_bike_kmpayback;?></label>
                                    </div>
                                    <div class="employeurneremunerepas">
                                        <label><input type="radio" name="prime" value="0"><?=L::cash4bike_bike_kmnopayback;?></label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <label for="frequence"><?=L::cash4bike_transport_frequence;?></label>
                                    <select class="form-control" name="frequence">
                                        <option value="1"><?=L::cash4bike_tf_once;?></option>
                                        <option value="2"><?=L::cash4bike_tf_twice;?></option>
                                        <option value="3"><?=L::cash4bike_tf_three;?></option>
                                        <option value="4" selected><?=L::cash4bike_tf_four;?></option>
                                        <option value="5"><?=L::cash4bike_tf_five;?></option>
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
                        
                        <h4 class="text-green"><?=L::cash4bike_wantedbike_title;?></h4>
                        
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <label for="brand"><?=L::cash4bike_wantedbike_brand;?></label>
                                <select class="from-control" name='brand'>
                                    <option value="selection"><?=L::cash4bike_wantedbike_choose;?></option>
                                </select>
                            </div>
                            <div class="col-md-4 model hidden">
                                <label for="brand"><?=L::cash4bike_wantedbike_model;?></label>
                                <select class="from-control" name='model'>
                                    <option value="selection"><?=L::cash4bike_wantedbike_choose;?></option>
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
                            <button class="button green button-3d effect fill-vertical" type="submit"><i class="fa fa-calculator"></i>&nbsp;<?=L::cash4bike_calculate_btn;?></button>
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
            <form id="cash4bike-form-contact" action="apis/Kameo/contact_cash4bike.php" role="form" method="post">
            
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h2 class="modal-title text-green" id="modal-label"><?=L::cash4bike_md_contact;?></h2>
			</div>
			<div class="modal-body">
				<div class="row text-left">
					<div class="form-group col-sm-12">
                    	<label for="firstName"><?=L::cash4bike_md_surname;?></label>
                        <input type="text" aria-required="true" name="firstName" class="form-control required is-invalid">
                    </div>
					<div class="form-group col-sm-12">
                    	<label for="name"><?=L::cash4bike_md_name;?></label>
                        <input type="text" aria-required="true" name="name" class="form-control required is-invalid">
                    </div>
                    <div class="form-group col-sm-12">
                    	<label for="email"><?=L::cash4bike_md_mail;?></label>
                        <input type="text" aria-required="true" name="email" class="form-control required is-invalid">
                    </div>
                    <div class="form-group col-sm-12">
                    	<label for="entreprise"><?=L::cash4bike_md_society;?></label>
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
				<button type="submit" class="button green button-3d effect fill-vertical"><?=L::cash4bike_md_send;?></button>
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
            
          <div class="modal-footer">
            <button type="button" class="btn btn-b" data-dismiss="modal"><?=L::cash4bike_md_close;?></button>
          </div>
		</div>
	</div>
</div>


		<!--END: RESULTAT -->
		
	</div>
        
    <h3><?=L::cash4bike_rent_gain_title;?></h3>
    <p><?=L::cash4bike_rent_gain_subtitle;?><br>
    Retrouvez <a href="https://www.securex.be/fr/gestion-du-personnel/couts-salariaux/optimaliser-votre-charge-salariale/plan-cafeteria" class="text-green" target="_blank">ici plus d’information</a> sur ce système.</p>
    <p><?=L::cash4bike_rent_gain_text;?></p>
    <p><?=L::cash4bike_rent_gain_text2;?><strong><?=L::cash4bike_rent_gain_text2sub;?></strong></p>
    <br>
        
    <div class="separator"></div>
        
        
	<h3><?=L::cash4bike_disclaimer_title;?></h3>
    <p><?=L::cash4bike_disclaimer_text;?></p>
    <p><?=L::cash4bike_disclaimer_text2;?></p>
    <p><?=L::cash4bike_disclaimer_text3;?></p>
    <p><?=L::cash4bike_disclaimer_text4;?></p>
      </div>
</section>

	<?php include 'include/footer.php'; ?>

	</div>
	<!-- END: WRAPPER -->

	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>



</body>

</html>
