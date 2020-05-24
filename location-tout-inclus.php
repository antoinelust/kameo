<?php 
include 'include/header5.php';
?>
		
		<!--Square icons-->
   <section>
	
	<div class="container">
		<div class="row">
				<h1 class="text-green fr">LOCATION TOUT INCLUS & ACHAT DE VÉLO</h1>
				<h1 class="text-green en">ALL INCLUSIVE RENTAL & PURCHASE</h1>
				<h1 class="text-green nl">ALL-INCLUSIVE VERHUUR & AANKOOP</h1>
				<br>
				<p class="fr">KAMEO Bikes se charge de définir le(s) moyen(s) de mobilité urbaine des entreprises selon leurs besoins, qu'il s'agisse de vélos classiques, électriques, pliants ou même de trottinettes électriques. <br /><br />
                Toutes les dépenses liées à la mobilité douce sont <strong class="text-green text-uppercase">déductibles à 100%</strong>, n'hésitez donc pas à en profiter ! </p>
                <p class="en">KAMEO Bikes defines the mobility plan that fits you the best. It can be made of electric, foldable or mechanical bikes or even electric scooters.<br /><br />
                All expenses linked to soft mobility are <strong class="text-green text-uppercase">100% deductible</strong>, make sure you make the most of it!</p>
				<p class="nl">KAMEO Bikes bepaalt het mobiliteitsplan dat het beste bij u past. Het wordt opgebouwd van elektrische, opvouwbare ofwel mechanische fietsen en zelfs elektrische scooters.<br /><br />
                Alle kosten in verband met zachte mobiliteit zijn <strong class="text-green text-uppercase">100% aftrekbaar</strong>, zorg dat u er het beste van maakt!</p>
				    <!-- Pricing Table -->
				    <div class="col-md-6">
						<div class="form-group">
							<label class="valeur fr" for="phone">Entrez le prix du vélo souhaité HTVA:</label>
							<label class="valeur en" for="phone">Bike buying price (excl. VAT):</label>
							<label class="valeur nl" for="phone">Fiets aankoopprijs (excl. BTW):</label>
							<input type="number" class="form-control required" name="prix" value="2000" id="prix" aria-required="true" onChange="updatePrices(this)">
						</div>
					</div>
					</div>
					<br>
				    <div class="row">
				      <div class="pricing-table">
				        <div class="col-md-6 col-sm-12 col-xs-12">
				          <div class="plan">
				            <div class="plan-header">
				              <h4 class="fr">Vente (HTVA)</h4>
				              <p class="text-muted fr">Paiement immédiat (HTVA)</p>
				              <h4 class="en">Sell (ex. VAT)</h4>
				              <p class="text-muted en">Immediate buy (excl. VAT)</p>
				              <h4 class="nl">Aankoop (Excl. BTW)</h4>
				              <p class="text-muted nl">Onmiddellijke aankoop (excl. BTW)</p>
				              <div class="plan-price" id="retailPrice"></div>
				            </div>
				            <div class="plan-list">
				                <ul class="fr">
                                    <li><i class="fa fa-globe"></i>Kilomètres illimités</li>
                                    <li><i class="fa fa-thumbs-up"></i>Garantie 2 ans</li>
                                    <li><i class="fa fa-cogs"></i>Entretien à la demande <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Cette formule ne prévoit pas d'entretien inclus dans le prix initial. Il est néanmoins possible d'en demander un via la plateforme MyKameo. Une facture séparée sera alors envoyée."></i></li>
                                    <li><i class="fa fa-lock"></i>Assurance à la demande</li>
                                    <li><i class="fa fa-user"></i>Accès à la plateforme MyKameo</li>
                                    <br>
                                    <a class="button small green button-3d rounded effect icon-left fr" data-target="#avantageRetailPrice" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i>Découvrez votre avantage fiscal</span></a>  
                                </ul> 
                                <ul class="en">
                                    <li><i class="fa fa-globe"></i>Unlimited kilometers</li>
                                    <li><i class="fa fa-thumbs-up"></i>2 years warranty</li>
                                    <li><i class="fa fa-cogs"></i>On-demand maintenance <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="This formula does not include maintenance in the initial price. It is nevertheless possible to request one via the MyKameo platform. A separate invoice will be sent."></i></li>
                                    <li><i class="fa fa-lock"></i>On-demand insurance</li>
                                    <li><i class="fa fa-user"></i>Full access to MyKameo</li>
                                    <br>
                                    <a class="button small green button-3d rounded effect icon-left en" data-target="#avantageRetailPrice" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i>Discover your fiscal advantage</span></a>  
                                </ul>
                                <ul class="nl">
                                    <li><i class="fa fa-globe"></i>Onbeperkte kilometers</li>
                                    <li><i class="fa fa-thumbs-up"></i>Garantie van 2 jaar</li>
                                    <li><i class="fa fa-cogs"></i>On-demand onderhoud <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Deze formule omvat niet het onderhoud in de initiële prijs. Het is niettemin mogelijk om er één aan te vragen via het MyKameo-platform. Er wordt een afzonderlijke factuur verzonden."></i></li>
                                    <li><i class="fa fa-lock"></i>On-demand verzekering</li>
                                    <li><i class="fa fa-user"></i>Volledige toegang tot MyKameo</li>
                                    <br>
                                    <a class="button small green button-3d rounded effect icon-left nl" data-target="#avantageRetailPrice" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i>Ontdek uw fiscale voordeel</span></a> 
				                </ul>
				            </div>
				          </div>
				        </div>
				        
				        <div class="col-md-6 col-sm-12 col-xs-12">
				          <div class="plan">
				            <div class="plan-header">
				              <h4 class="fr">Location tout inclus (HTVA)</h4>
				              <p class="text-muted fr">Durée: 3 ans</p>
				              <h4 class="en">All inclusive rental (excl. VAT)</h4>
				              <p class="text-muted en">Contract: 3 years</p>
				              <h4 class="nl">All-inclusive verhuur (excl. BTW)</h4>
				              <p class="text-muted nl">Contract: 3 jaar</p>
				              <div class="plan-price fr" id="leasingPriceFR"></div>
				              <div class="plan-price en" id="leasingPriceEN"></div>
				              <div class="plan-price nl" id="leasingPriceNL"></div>
				              </div>
				            <div class="plan-list">
				                <ul class="fr">
                                    <li><i class="fa fa-globe"></i>500 kilomètres par mois <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Le nombre de kilomètres est cumulable. Sur une location de 36 mois, il suffit de ne pas dépasser 36 * 500 = 18.000 kms au total."></i></li>
                                    <li><i class="fa fa-thumbs-up"></i>Garantie 2 ans</li>
                                    <li><i class="fa fa-lock"></i>Assurance comprise</li>
                                    <li><i class="fa fa-cogs"></i>4 entretiens sur la durée de la location <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="2 entretiens la première année puis un entretien par an. La planification exacte des entretiens se fait via la plateforme mykameo."></i></li>
                                    <li><i class="fa fa-user"></i>Accès à la plateforme MyKameo</li>
                                    <li><i class="fa fa-money"></i>Possibilité de rachat du vélo <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="A la fin de la location, le vélo peut être racheté à hauteur de 15% du prix d'achat du vélo."></i></li>
                                    <br>
                                    <a class="button small green button-3d rounded effect icon-left fr" data-target="#avantageLeasingPrice" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i>Découvrez votre avantage fiscal</span></a>
				                </ul>
				                <ul class="en">
                                    <li><i class="fa fa-globe"></i>500 kilometers per month <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Number of kilometers can be summed. For example, for a 36 months contract, the total number of kilometers must be lower than 36 * 500 = 18.000 kms"></i></li>
                                    <li><i class="fa fa-thumbs-up"></i>2 years warranty</li>
                                    <li><i class="fa fa-lock"></i>Insurance included</li>
                                    <li><i class="fa fa-cogs"></i>4 maintenances over the leasing contract <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="2 maintenances the first year, one per year afterwards. The exact timing of such maintenance can be configured via MyKameo platform"></i></li>
                                    <li><i class="fa fa-user"></i>Full access to MyKameo</li>
                                    <li><i class="fa fa-money"></i>End of contract: option to buy the bike <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="At the end of leasing contract, possibility to buy the bike for 15% of its retail price."></i></li>
                                    <br>
                                    <a class="button small green button-3d rounded effect icon-left en" data-target="#avantageLeasingPrice" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i>Discover your fiscal advantage</span></a>
				                </ul>
				                <ul class="nl">
                                    <li><i class="fa fa-globe"></i>500 kilometer per maand <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Het aantal kilometers kan worden opgeteld. Voor een contract van 36 maanden moet het totale aantal kilometers bijvoorbeeld lager zijn dan 36 * 500 = 18.000 km"></i></li>
                                    <li><i class="fa fa-thumbs-up"></i>Garantie van 2 jaar</li>
                                    <li><i class="fa fa-lock"></i>Verzekering inbegrepen</li>
                                    <li><i class="fa fa-cogs"></i>4 onderhoud tijdens leasecontract <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="2 onderhoudt het eerste jaar, één per jaar daarna. De exacte timing van dergelijk onderhoud kan worden geconfigureerd via het MyKameo-platform"></i></li>
                                    <li><i class="fa fa-user"></i>Volledige toegang tot MyKameo</li>
                                    <li><i class="fa fa-money"></i>Einde contract: mogelijkheid om de fiets te kopen <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Aan het einde van het leasecontract, mogelijkheid om de fiets te kopen voor 15% van de verkoopprijs."></i></li>
                                    <br>
                                    <a class="button small green button-3d rounded effect icon-left nl" data-target="#avantageLeasingPrice" data-toggle="modal" href="#"><span><i class="fa fa-eye"></i>Ontdek uw fiscale voordeel</span></a>
				                </ul>
                                
				            </div>
				          </div>
				        </div>
				      </div>
				    </div>
				    <!-- END: Pricing Table --> 
				    
				    <div class="modal fade" id="avantageRetailPrice" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
									<h4 id="modal-label-3" class="modal-title fr">AVANTAGE FISCAL</h4>
									<h4 id="modal-label-3" class="modal-title en">TAX INCENTIVE</h4>
									<h4 id="modal-label-3" class="modal-title nl">FISCAAL VOORDEEL</h4>
								</div>
								<div class="modal-body">
									<div class="row mb20">
										<div class="col-sm-12">
                                            
											<p class="fr">Toutes les dépenses liées aux vélos et vélos électriques sont déductibles à <strong>100%</strong> fiscalement, profitez-en ! <br />
                                            Sur base d'un achat de vélo d'une valeur de <span class="text-green" id="spanRetailPriceFR"></span>, voici l'avantage fiscal: <br /><br />
                                            <strong>Prix du vélo :</strong> <span id="spanRetailPriceFR2"></span><br />
                                            <strong>TVA :</strong> <span id="spanTVARetailPriceFR"></span><br />
                                            <strong>Prix HTVA :</strong> <span id="spanHTVARetailPriceFR"></span><br />
                                            <strong>Avantage fiscal :</strong> 100 % x <span id="spanHTVARetailPriceFR2"></span> x 34 % = <span id="spanAvantageFiscalRetailPriceFR" class="text-green"></span><br /><br />
                                            
                                                
                                            Grâce à l'avantage fiscal, le vélo d'une valeur de <span id="spanHTVARetailPriceFR3"></span> HTVA ne coûte réellement à la société que <strong><span id="spanFinalPriceRetailPriceFR" class="text-green"></span></strong> (<span id="spanHTVARetailPriceFR4"></span> - <span id="spanAvantageFiscalRetailPriceFR2"></span>).
                                            </p>
                                            
											<p class="en">All expenses linked to soft mobility are <strong>100%</strong> deductible, make sure you make the best of it! <br />
                                            Based on a retail value of <span class="text-green" id="spanRetailPriceEN"></span>, here is the fiscal advantage: <br /><br />
                                            <strong>Retail Price :</strong> <span id="spanRetailPriceEN2"></span><br />
                                            <strong>VAT :</strong> <span id="spanTVARetailPriceEN"></span><br />
                                            <strong>Price VAT excluded :</strong> <span id="spanHTVARetailPriceEN"></span><br />
                                            <strong>Fiscal advantage :</strong> 100 % x <span id="spanHTVARetailPriceEN2"></span> x 34 % = <span id="spanAvantageFiscalRetailPriceEN" class="text-green"></span><br /><br />
                                            
                                                
                                            Thanks to the fiscal advantage, bike with a retail price of <span id="spanHTVARetailPriceEN3"></span> (VAT excluded) only costs <strong><span id="spanFinalPriceRetailPriceEN" class="text-green"></span></strong> (<span id="spanHTVARetailPriceEN4"></span> - <span id="spanAvantageFiscalRetailPriceEN2"></span>).
                                            </p>
                                            
											<p class="nl">Alle uitgaven in verband met zachte mobiliteit zijn voor <strong>100%</strong> aftrekbaar, zorg dat u er het beste van maakt! <br />
                                            Gebaseerd op een winkelwaarde van <span class="text-green" id="spanRetailPriceNL"> </span>, is hier het fiscale voordeel:<br /><br />
                                            <strong>Verkoopprijs :</strong> <span id="spanRetailPriceNL2"></span><br />
                                            <strong>BTW :</strong> <span id="spanTVARetailPriceNL"></span><br />
                                            <strong>Prijs exclusief BTW :</strong> <span id="spanHTVARetailPriceNL"></span><br />
                                            <strong>Fiscaal voordeel :</strong> 100 % x <span id="spanHTVARetailPriceNL2"></span> x 34 % = <span id="spanAvantageFiscalRetailPriceNL" class="text-green"></span><br /><br />
                                            
                                                
                                            Dankzij het fiscale voordeel kost een fiets met een verkoopprijs van <span id="spanHTVARetailPriceNL3"> </span> (exclusief BTW) alleen <strong><span id="spanFinalPriceRetailPriceNL" class="text-green"></span></strong>
                                            (<span id="spanHTVARetailPriceNL4" > </span> - <span id="spanAvantageFiscalRetailPriceNL2"> </span>).
                                            </p>
                                            
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<a class="button button-3d rounded effect icon-left fr"  data-dismiss="modal"><span><i class="fa fa-close"></i>Fermer</span></a>
									<a class="button button-3d rounded effect icon-left en"  data-dismiss="modal"><span><i class="fa fa-close"></i>Close</span></a>
									<a class="button button-3d rounded effect icon-left nl"  data-dismiss="modal"><span><i class="fa fa-close"></i>Sluiten</span></a>
								</div>
							</div>
						</div>
					</div>
				    <div class="modal fade" id="avantageLeasingPrice" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
									<h4 id="modal-label-3" class="modal-title fr">AVANTAGE FISCAL</h4>
									<h4 id="modal-label-3" class="modal-title en">TAX INCENTIVE</h4>
									<h4 id="modal-label-3" class="modal-title nl">FISCAAL VOORDEEL</h4>
								</div>
								<div class="modal-body">
									<div class="row mb20">
										<div class="col-sm-12">
                                            
											<p class="fr">Toutes les dépenses liées aux vélos et vélos électriques sont déductibles à <strong>100%</strong> fiscalement, profitez-en ! <br />
                                            Sur base d'un achat de vélo d'une valeur de <span class="text-green" id="spanLeasingPriceFR"></span>, voici l'avantage fiscal: <br /><br />
                                            <strong>Montant de la location :</strong> <span id="spanLeasingPriceFR2"></span><br />
                                            <strong>Avantage fiscal :</strong> 100 % x <span id="spanHTVALeasingPriceFR2"></span> x 34 % = <span id="spanAvantageFiscalLeasingPriceFR" class="text-green"></span><br /><br />
                                            
                                                
                                            Grâce à l'avantage fiscal, la location d'une valeur de <span id="spanHTVALeasingPriceFR3"></span> HTVA ne coûte réellement à la société que <strong><span id="spanFinalPriceLeasingPriceFR" class="text-green"></span></strong> (<span id="spanHTVALeasingPriceFR4"></span> - <span id="spanAvantageFiscalLeasingPriceFR2"></span>).
                                            </p>			
                                            
											<p class="en">All expenses linked to green mobility are <strong>100%</strong> deductible, make sure you make the best of it! <br />
                                            Based on a retail value of <span class="text-green" id="spanLeasingPriceEN"></span>, here is the fiscal advantage: <br /><br />
                                            <strong>Leasing :</strong> <span id="spanLeasingPriceEN2"></span><br />
                                            <strong>Fiscal advantage :</strong> 100 % x <span id="spanHTVALeasingPriceEN2"></span> x 34 % = <span id="spanAvantageFiscalLeasingPriceEN" class="text-green"></span><br /><br />
                                            
                                                
                                            Thanks to the fiscal advantage, the leasing with a value of <span id="spanHTVALeasingPriceEN3"></span> (VAT excluded) only costs <strong><span id="spanFinalPriceLeasingPriceEN" class="text-green"></span></strong> (<span id="spanHTVALeasingPriceEN4"></span> - <span id="spanAvantageFiscalLeasingPriceEN2"></span>).
                                            </p>

											<p class="nl">Alle uitgaven in verband met groene mobiliteit zijn voor <strong>100%</strong> aftrekbaar, zorg dat u er het beste van maakt!<br />
                                            Gebaseerd op een winkelwaarde van <span class="text-green" id="spanLeasingPriceNL"> </span>, is hier het fiscale voordeel: <br /><br />
                                            <strong>Leasing :</strong> <span id="spanLeasingPriceNL2"></span><br />
                                            <strong>Fiscaal voordeel:</strong> 100 % x <span id="spanHTVALeasingPriceNL2"></span> x 34 % = <span id="spanAvantageFiscalLeasingPriceNL" class="text-green"></span><br /><br />
                                            
                                                
                                            Dankzij het fiscale voordeel, een leasing met een waarde van <span id="spanHTVALeasingPriceNL3"></span> (Exclusief BTW) kost alleen <strong><span id="spanFinalPriceLeasingPriceNL" class="text-green"></span></strong> (<span id="spanHTVALeasingPriceNL4"></span> - <span id="spanAvantageFiscalLeasingPriceNL2"></span>).
                                            </p>
                            
                                            
                                        </div>
									</div>
								</div>
								<div class="modal-footer">
									<a class="button button-3d rounded effect icon-left fr"  data-dismiss="modal"><span><i class="fa fa-close"></i>Fermer</span></a>
									<a class="button button-3d rounded effect icon-left en"  data-dismiss="modal"><span><i class="fa fa-close"></i>Close</span></a>
									<a class="button button-3d rounded effect icon-left nl"  data-dismiss="modal"><span><i class="fa fa-close"></i>Sluiten</span></a>
								</div>
							</div>
						</div>
					</div>
	
	
<script type="text/javascript">
    
    updatePrices(document.getElementById('prix'));       
    
	function updatePrices(ele) {
        var price=(ele.value);
        
        $.ajax({
            url: 'include/get_prices.php',
            type: 'post',
            data: { "retailPrice": price},
            success: function(response){
                document.getElementById('retailPrice').innerHTML = "<sup>€</sup>"+response.HTVARetailPrice+"<span></span>";                
                document.getElementById('leasingPriceFR').innerHTML = "<sup>€</sup>"+response.leasingPrice+"<span>/mois</span>";                
                //document.getElementById('rentingPriceFR').innerHTML = "<sup>€</sup>"+response.rentingPrice+"<span>/mois</span>";  
                document.getElementById('leasingPriceNL').innerHTML = "<sup>€</sup>"+response.leasingPrice+"<span>/mij</span>";                
                //document.getElementById('rentingPriceNL').innerHTML = "<sup>€</sup>"+response.rentingPrice+"<span>/mij</span>";  
                document.getElementById('leasingPriceEN').innerHTML = "<sup>€</sup>"+response.leasingPrice+"<span>/month</span>";                
                //document.getElementById('rentingPriceEN').innerHTML = "<sup>€</sup>"+response.rentingPrice+"<span>/month</span>";  

                document.getElementById('spanRetailPriceFR').innerHTML = response.retailPrice+" € ";
                document.getElementById('spanRetailPriceFR2').innerHTML = response.retailPrice+" € ";
                document.getElementById('spanHTVARetailPriceFR').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanHTVARetailPriceFR2').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanHTVARetailPriceFR3').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanHTVARetailPriceFR4').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanTVARetailPriceFR').innerHTML = response.TVARetailPrice+" € ";
                document.getElementById('spanAvantageFiscalRetailPriceFR').innerHTML = response.avantageFiscalRetailPrice+" € ";
                document.getElementById('spanAvantageFiscalRetailPriceFR2').innerHTML = response.avantageFiscalRetailPrice+" € ";
                document.getElementById('spanFinalPriceRetailPriceFR').innerHTML = response.finalPriceRetailPrice+" € ";
                document.getElementById('spanRetailPriceFR').innerHTML = response.retailPrice+" € ";
                document.getElementById('spanRetailPriceEN').innerHTML = response.retailPrice+" € ";
                document.getElementById('spanRetailPriceEN2').innerHTML = response.retailPrice+" € ";
                document.getElementById('spanHTVARetailPriceEN').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanHTVARetailPriceEN2').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanHTVARetailPriceEN3').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanHTVARetailPriceEN4').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanTVARetailPriceEN').innerHTML = response.TVARetailPrice+" € ";
                document.getElementById('spanAvantageFiscalRetailPriceEN').innerHTML = response.avantageFiscalRetailPrice+" € ";
                document.getElementById('spanAvantageFiscalRetailPriceEN2').innerHTML = response.avantageFiscalRetailPrice+" € ";
                document.getElementById('spanFinalPriceRetailPriceEN').innerHTML = response.finalPriceRetailPrice+" € ";
                document.getElementById('spanRetailPriceNL').innerHTML = response.retailPrice+" € ";
                document.getElementById('spanRetailPriceNL2').innerHTML = response.retailPrice+" € ";
                document.getElementById('spanHTVARetailPriceNL').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanHTVARetailPriceNL2').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanHTVARetailPriceNL3').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanHTVARetailPriceNL4').innerHTML = response.HTVARetailPrice+" € ";
                document.getElementById('spanTVARetailPriceNL').innerHTML = response.TVARetailPrice+" € ";
                document.getElementById('spanAvantageFiscalRetailPriceNL').innerHTML = response.avantageFiscalRetailPrice+" € ";
                document.getElementById('spanAvantageFiscalRetailPriceNL2').innerHTML = response.avantageFiscalRetailPrice+" € ";
                document.getElementById('spanFinalPriceRetailPriceNL').innerHTML = response.finalPriceRetailPrice+" € ";

                
                document.getElementById('spanLeasingPriceFR').innerHTML = response.retailPrice+" € ";
                document.getElementById('spanLeasingPriceFR2').innerHTML = response.leasingPrice+" €/mois ";
                document.getElementById('spanHTVALeasingPriceFR2').innerHTML = response.HTVALeasingPrice+" € ";
                document.getElementById('spanHTVALeasingPriceFR3').innerHTML = response.HTVALeasingPrice+" €/mois ";
                document.getElementById('spanHTVALeasingPriceFR4').innerHTML = response.HTVALeasingPrice+" € ";
                document.getElementById('spanAvantageFiscalLeasingPriceFR').innerHTML = response.avantageFiscalLeasingPrice+" €/mois ";
                document.getElementById('spanAvantageFiscalLeasingPriceFR2').innerHTML = response.avantageFiscalLeasingPrice+" € ";
                document.getElementById('spanFinalPriceLeasingPriceFR').innerHTML = response.finalPriceLeasingPrice+" €/mois ";
                document.getElementById('spanLeasingPriceEN').innerHTML = response.retailPrice+" € ";
                document.getElementById('spanLeasingPriceEN2').innerHTML = response.leasingPrice+" €/month ";
                document.getElementById('spanHTVALeasingPriceEN2').innerHTML = response.HTVALeasingPrice+" € ";
                document.getElementById('spanHTVALeasingPriceEN3').innerHTML = response.HTVALeasingPrice+" €/month ";
                document.getElementById('spanHTVALeasingPriceEN4').innerHTML = response.HTVALeasingPrice+" € ";
                document.getElementById('spanAvantageFiscalLeasingPriceEN').innerHTML = response.avantageFiscalLeasingPrice+" €/month ";
                document.getElementById('spanAvantageFiscalLeasingPriceEN2').innerHTML = response.avantageFiscalLeasingPrice+" € ";
                document.getElementById('spanFinalPriceLeasingPriceEN').innerHTML = response.finalPriceLeasingPrice+" €/month ";
                document.getElementById('spanLeasingPriceNL').innerHTML = response.retailPrice+" € ";
                document.getElementById('spanLeasingPriceNL2').innerHTML = response.leasingPrice+" €/maand ";
                document.getElementById('spanHTVALeasingPriceNL2').innerHTML = response.HTVALeasingPrice+" € ";
                document.getElementById('spanHTVALeasingPriceNL3').innerHTML = response.HTVALeasingPrice+" €/maand ";
                document.getElementById('spanHTVALeasingPriceNL4').innerHTML = response.HTVALeasingPrice+" € ";
                document.getElementById('spanAvantageFiscalLeasingPriceNL').innerHTML = response.avantageFiscalLeasingPrice+" €/maand ";
                document.getElementById('spanAvantageFiscalLeasingPriceNL2').innerHTML = response.avantageFiscalLeasingPrice+" € ";
                document.getElementById('spanFinalPriceLeasingPriceNL').innerHTML = response.finalPriceLeasingPrice+" €/maand ";
            }
        });
	}
</script>	


				
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


