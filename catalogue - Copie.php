<?php 
include 'include/header2.php';
?>
 <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                    	<div class="heading heading text-left m-b-20">
                        <h2 class="fr">Rechercher</h2>
                        </div>
                        
                        <div class="m-t-30">
                                <div class="row">
                                
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-marque">Marque</label>
											<select class="portfolio" name="widget-contact-form-marque" id="widget-bike-brand" data-filter-group="brand">
									           <option data-filter="" value="*">Toutes nos marques</option>
									           <option data-filter=".conway" value="Conway">Conway</option>
									           <option data-filter=".orbea">Orbea</option>
									           <option data-filter=".bzen">Bzen</option>
									           <option data-filter=".ahooga">Ahooga</option>
									           <option data-filter=".stevens">Stevens</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-utilisation">Utilisation</label>
											<select class="portfolio" name="widget-contact-form-utilisation" id="widget-bike-utilisation" data-filter-group="utilisation">
									           <option data-filter="" value="*">Tous types d'utilisation</option>
									           <option data-filter=".villeetchemin">Ville et chemin</option>
									           <option data-filter=".ville">Ville</option>
									           <option data-filter=".toutchemin">Tout chemin</option>
									           <option data-filter=".pliant">Pliant</option>
									           <option data-filter=".speedpedelec">Speedpedelec</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-cadre">Type de cadre</label>
											<select class="portfolio" name="widget-contact-form-cadre" id="widget-bike-frame-type" data-filter-group="cadre">
									           <option data-filter="" value="*">Tous types de cadre</option>
									           <option data-filter=".m" value="M">Mixte</option>
									           <option data-filter=".f" value="F">Femme</option>
									           <option data-filter=".h" value="H">Homme</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-electrique">Assistance électrique</label>
											<select class="portfolio" name="widget-contact-form-electrique" id="widget-bike-electric" data-filter-group="electrique">
                                                <option data-filter="" value="*">Tous</option>
									            <option data-filter=".y">Oui</option>
									            <option data-filter=".n">Non</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-prix">Prix Achat (HTVA)</label>
											<select class="portfolio" name="widget-contact-form-prix" id="widget-bike-price" data-filter-group="prix">
                                                <option data-filter="" value="*" selected>Tous les prix</option>
                                                <option data-filter=".2000"> Moins de 2000€</option>
                                                <option data-filter=".between-2000-3000">2000 - 3000 € </option>
                                                <option data-filter=".between-3000-4000">3000 - 4000€</option>
                                                <option data-filter=".between-4000-5000">4000 - 5000 €</option>
                                                <option data-filter=".5000"> Plus de 5000€</option>
									       </select>
                                    </div>
									
										                                     
                                
                                </div>
                           </div>
                   
                    </div>
                    
                    
                    
                    
                    <div class="col-md-8">
                    	<div class="heading heading text-left m-b-20">

                            <h2 class="fr">Nos vélos</h2>
                        </div>
                        
                        <!-- Portfolio Items -->
				        <div id="isotope" class="isotope portfolio-items" data-isotope-item-space="2" data-isotope-mode="masonry" data-isotope-col="3" data-isotope-item=".portfolio-item">
                            
                            
                                <?php
                            
                                $domain = $_SERVER['HTTP_HOST'];
                                if(substr($domain, 0, 9) == "localhost"){
                                    $relative = '/kameo/include/load_portfolio.php';
                                    $prefix='';
                                }else{
                                    $relative = '/test/include/load_portfolio.php';      
                                    $prefix='https://';
                                }
                            
                                $url=$prefix.$domain.$relative;
                            
                                $curl = curl_init();
                                // Set some options - we are passing in a useragent too here
                                curl_setopt_array($curl, [
                                    CURLOPT_RETURNTRANSFER => 1,
                                    CURLOPT_URL => $url,
                                    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
                                ]);

                                if(curl_exec($curl) === false)
                                {
                                    echo "<p>".curl_error($curl)."</p>";
                                }else{
                                    $resp=curl_exec($curl);
                                    $obj = json_decode($resp);                                
                                    foreach($obj->{'bike'} as $test){


                                    if($test->{'price'}<="2000"){
                                        $price="2000";
                                    }else if($test->{'price'}<="3000"){
                                        $price="between-2000-3000";
                                    }else if($test->{'price'}<="4000"){
                                        $price="between-3000-4000";
                                    }else if($test->{'price'}<="5000"){
                                        $price="between-4000-5000";
                                    }else{
                                        $price="5000";
                                    }

                                    if($test->{'frameType'}=="F"){
                                        $frameType="Femme";
                                    }else if($test->{'frameType'}=="H"){
                                        $frameType="Homme";
                                    }else if($test->{'frameType'}=="M"){
                                        $frameType="Mixte";
                                    }else{
                                        $frameType="error";
                                    }               

                                    echo "<div class=\"portfolio-item ".str_replace(" ", "-", strtolower($test->{'brand'}))." ".str_replace(" ", "-", strtolower($test->{'frameType'}))." ".str_replace(" ", "-", strtolower($test->{'utilisation'}))." ".str_replace(" ", "-", strtolower($test->{'electric'}))." ".$price."\" \">
                                        <div class=\"portfolio-image effect social-links\">
                                            <img src=\"images_bikes/".str_replace(" ", "-", strtolower($test->{'brand'}))."_".str_replace(" ", "-", strtolower($test->{'model'}))."_".str_replace(" ", "-", strtolower($test->{'frameType'}))."_mini.jpg\" alt=\"\">
                                            <div class=\"image-box-content\">
                                                <p>
                                                    <a href=\"images_bikes/".$test->{'brand'}."_".$test->{'model'}."_".$test->{'frameType'}.".jpg\" data-lightbox-type=\"image\" title=\"".$test->{'brand'}." ".$test->{'model'}." ".$test->{'frameType'}." \"><i class=\"fa fa-expand\"></i></a>
                                                    <a href=\"offre.php?brand=".$test->{'brand'}."&model=".$test->{'model'}."&frameType=".$test->{'frameType'}."\"><i class=\"fa fa-link\"></i></a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class=\"portfolio-description\">
                                            <a href=\"offre.php?brand=".$test->{'brand'}."&model=".$test->{'model'}."&frameType=".$test->{'frameType'}."\"><h4 class=\"title\">".$test->{'brand'}."</h4></a>
                                            <p>".$test->{'model'}." ".$frameType."
                                            <br><b class=\"text-green\">Achat :".$test->{'price'}."  €</b>
                                            <br><b class=\"text-green\">Leasing :".$test->{'leasingPrice'}." €/mois</b></p>
                                        </div>
                                    </div>";


                                    }                                    
                                }
                                curl_close($curl);

                            
                                
                                
                            ?>

                            
                        </div>
                        <script type="text/javascript">
                            var $grid = $('.isotope').isotope({
                            });

                            var filters = {};
                            
                            $('.portfolio').on( 'change', function(event) {
                                var $cible = $( event.currentTarget );
                                var filterGroup = $cible.attr('data-filter-group');
                                filters[ filterGroup ] = $( this ).children("option:selected").attr('data-filter');
                                var filterValue = concatValues( filters );
                                $grid.isotope({ filter: filterValue });
                            });
                            
                            function concatValues( obj ) {
                              var value = '';
                              for ( var prop in obj ) {
                                value += obj[ prop ];
                              }
                              return value;
                            }

                        </script>

                        
        				
				            
				
                    </div>
                    <!-- END: Portfolio Items -->
                        
                </div>
            </div>
        </section>
        <!-- END: CONTENT -->
  
        
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

	<!-- Custom js file -->
	<script src="js/language.js"></script>



</body>

</html>
