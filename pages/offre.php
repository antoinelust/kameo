<!DOCTYPE html>
<html lang="fr">
<?php 
	include 'include/head.php';
	header("HTTP/1.0 404 Not Found");
?>
<?php
$brand=isset($_GET['brand']) ? $_GET['brand']:NULL;
$model=isset($_GET['model']) ? $_GET['model']:NULL;
$frameType=isset($_GET['frameType']) ? $_GET['frameType']:NULL;
include 'apis/Kameo/connexion.php';
$brandUPPER=strtoupper($brand);
$modelUPPER=strtoupper($model);
$frameTypeUPPER=strtoupper($frameType);

$sql="SELECT * FROM bike_catalog WHERE UPPER(BRAND)='$brandUPPER' AND UPPER(MODEL)='$modelUPPER' AND UPPER(FRAME_TYPE)='$frameTypeUPPER'";

if ($conn->query($sql) === FALSE) {
    echo $conn->error;
}
$result = mysqli_query($conn, $sql);        
$row = mysqli_fetch_assoc($result);
?>
<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include 'include/topbar.php'; ?>
		<?php include 'include/header.php'; ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        
                        <img src="images_bikes/<?php echo str_replace(' ', '-', $brand).'_'.str_replace(' ', '-', $model).'_'.$frameType; ?>.jpg" class="img-responsive img-rounded" alt="">
                        <br>
                        <dl class="dl">
							<dt>Caractéristiques techniques</dt>
							<dd>Voir le <ins><a href="<?php echo $row['LINK']; ?>" target="_blank">site de la marque</a></ins>.</dd>
						</dl>               
                    </div>
                    <div class="col-md-6">
                    	<div class="heading heading text-left m-b-20">
                        <h2 class="fr"><?php echo $brand.' '.$model; ?></h2>
                        </div>
                        
                        <dl class="dl col-md-6">
							<dt>Utilisation</dt>
							<dd><?php echo $row['UTILISATION']; ?></dd>
							<br>
							<dt>Type de cadre</dt>
							<dd><?php if($row['FRAME_TYPE']=="H"){echo "Homme";} else if($row['FRAME_TYPE']=="M"){echo "Mixte";}else if($row['FRAME_TYPE']=="F"){echo "Femme";}else{echo "undefined";} ?></dd>
						</dl>
						
						<dl class="dl col-md-6">
							<dt>Assistance électrique</dt>
							<dd><?php if($row['ELECTRIC']=="Y"){echo "Oui";} else if($row['ELECTRIC']=="N"){echo "Non";}else{echo "undefined";} ?></dd>
							<br>
						</dl>
						
						<div class="col-md-12">
						<!--
						<h3>Prix Achat (HTVA): <b class="text-green"><?php echo $row['PRICE_HTVA']; ?></b> <small>€</small></h3>
						<h3>Prix Achat (TVAC): <b class="text-green"><?php echo $row['PRICE_HTVA']*1.21; ?></b> <small>€</small></h3>
						-->
                        <?php
        
                            $priceTemp=($row['PRICE_HTVA']+3*75+4*100+4*100);


                            // Calculation of coefficiant for leasing price

                            if($priceTemp<2500){
                                $coefficient=3.289;
                            }elseif ($priceTemp<=5000){
                                $coefficient=3.056;
                            }elseif ($priceTemp<=12500){
                                $coefficient=2.965;
                            }elseif ($priceTemp<=25000){
                                $coefficient=2.921;
                            }elseif ($priceTemp<=75000){
                                $coefficient=2.898;
                            }else{
                                errorMessage(ES0012);
                            }

                            // Calculation of leasing price based on coefficient and retail price

                            $leasingPrice=round(($priceTemp)*($coefficient)/100); 	
                        ?>
                            
                            <!--<h3>Prix Leasing (HTVA): <b class="text-green"><?php echo $leasingPrice; ?></b> <small>€/mois HTVA</small></h3>-->

                        
                        <!-- Pricing Table Colored -->
						    <div class="row">
						      <div class="pricing-table col-no-margin">
						        <div class="col-md-4 col-sm-4 col-xs-4">
						          <div class="plan">
						            <div class="plan-header">
						              <h4>Prix Achat (HTVA)</h4>
						              <h2 class="text-green"><sup>€</sup><?php echo $row['PRICE_HTVA']; ?></h2>
						            </div>
						          </div>
						        </div>
						        
						        <div class="col-md-4 col-sm-4 col-xs-4">
						          <div class="plan">
						            <div class="plan-header">
						              <h4>Prix Achat (TVAC)</h4>
						              <h2 class="text-green"><sup>€</sup><?php echo round($row['PRICE_HTVA']*1.21); ?></h2>
						            </div>
						          </div>
						        </div>
						        
						        <div class="col-md-4 col-sm-4 col-xs-4">
						          <div class="plan">
						            <div class="plan-header">
						              <h4>Prix Leasing (HTVA)</h4>
						              <h2 class="text-green"><sup>€</sup><?php echo $leasingPrice; ?><span><small>/mois</small></span></h2>
						              
						            </div>
						          </div>
						        </div>
						        
						      </div>
						    </div>
						    <!-- END: Pricing Table Colored --> 
                        
                        
                            
						</div>
                        
                        <div class="col-md-12">
                            <p>L'option leasing sur 36 mois comprend les services suivants:</p>
                            <ul>
                                <li>Assurance P-Vélo contre le vol et la casse</li>
                                <li>4 entretiens sur les 36 mois de la durée de vie du leasing</li>
                            </ul>
                        </div>
                        
                        <div class="separator"></div>
                        
                        <div class="m-t-30">
                            <form id="widget-offer" action="include/offer_form.php" role="form" method="post">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="name" class="fr">Nom</label>
										<label for="name" class="en">Name</label>
										<label for="name" class="nl">Naam</label>
                                        <input type="text" aria-required="true" name="widget-offer-name" class="form-control required name">
                                    </div>
                                     <div class="form-group col-sm-6">
                                        <label for="firstName" class="fr">Prénom</label>
										<label for="firstName" class="en">First Name</label>
										<label for="firstName" class="nl">Voornaam</label>
                                        <input type="text" aria-required="true" name="widget-offer-firstName" class="form-control required name">

										</div>
                                    <div class="form-group col-sm-6">
                                        <label for="email"  class="fr">Email</label>
										<label for="email"  class="en">Email</label>
										<label for="email"  class="nl">Email</label>
                                        <input type="email" aria-required="true" name="widget-offer-email" class="form-control required email">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="phone"  class="fr">Numéro de téléphone</label>
										<label for="phone"  class="en">Phone number</label>
										<label for="phone"  class="nl">Telefoonnumber</label>
                                        <input type="phone" aria-required="true" name="widget-offer-phone" class="form-control required phone" placeholder="+32">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="widget-offer-leasing">Type d'acquisition</label>
											<select name="widget-offer-leasing">
                                                <option value="achat">Achat</option>
                                                <option value="leasing">Leasing</option>
									       </select>
                                    </div>
                                    
                                    
                                </div>
                                
                                <input type="text" class="hidden" id="widget-offer-brand" name="widget-offer-brand" value="<?php echo $brand; ?>" />
                                <input type="text" class="hidden" id="widget-offer-model" name="widget-offer-model" value="<?php echo $model; ?>" />
                                <input type="text" class="hidden" id="widget-offer-model" name="widget-offer-frame-type" value="<?php echo $frameType; ?>" />
                                <input type="text" class="hidden" id="widget-offer-antispam" name="widget-offer-antispam" value="" />
                                <button  id="fr" class="button green button-3d rounded effect" type="submit" id="form-submit">Demander une offre</button>
                            </form>   
                            <script type="text/javascript">
                                jQuery("#widget-offer").validate({
                                    submitHandler: function(form) {
                                        console.log("test");
                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: "Nous avons bien reçu votre message et nous reviendrons vers vous dès que possible."
                                                    }, {
                                                        type: 'success'
                                                    });
                                                    $(form)[0].reset();
                                                    
                                                    gtag('event', 'send', {
                                                      'event_category': 'mail',
                                                      'event_label': 'offre.php'
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
        </section>
        <!-- END: CONTENT -->
		<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->


	<!-- Theme Base, Components and Settings -->
	<script src="/js/theme-functions.js"></script>

	<!-- Custom js file -->
	<script src="/js/language.js"></script>



</body>

</html>
