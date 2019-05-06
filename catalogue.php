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
											<select class="portfolio" name="widget-contact-form-marque" id="widget-bike-brand">
									           <option data-filter="*" value="*">Toutes nos marques</option>
									           <option data-filter=".conway" value="Conway">Conway</option>
									           <option data-filter=".orbea">Orbea</option>
									           <option data-filter=".bzen">Bzen</option>
									           <option data-filter=".ahooga">Ahooga</option>
									           <option data-filter=".stevens">Stevens</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-utilisation">Utilisation</label>
											<select class="portfolio" name="widget-contact-form-utilisation" id="widget-bike-utilisation">
									           <option data-filter="*" value="*">Tous types d'utilisation</option>
									           <option data-filter=".villeetchemin">Ville et chemin</option>
									           <option data-filter=".ville">Ville</option>
									           <option data-filter=".toutchemin">Tout chemin</option>
									           <option data-filter=".pliant">Pliant</option>
									           <option data-filter=".speedpedelec">Speedpedelec</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-cadre">Type de cadre</label>
											<select class="portfolio" name="widget-contact-form-cadre" id="widget-bike-frame-type">
									           <option data-filter="*" value="*">Tous types de cadre</option>
									           <option data-filter=".m" value="M">Mixte</option>
									           <option data-filter=".f" value="F">Femme</option>
									           <option data-filter=".h" value="H">Homme</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-electrique">Assistance électrique</label>
											<select class="portfolio" name="widget-contact-form-electrique" id="widget-bike-electric">
                                                <option data-filter="*" value="*">Tous</option>
									            <option data-filter=".y">Oui</option>
									            <option data-filter=".n">Non</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-prix">Prix Achat (HTVA)</label>
											<select class="portfolio" name="widget-contact-form-prix" id="widget-bike-price">
                                                <option data-filter="*" value="*" selected>Tous les prix</option>
                                                <option data-filter="-1000"> -1000€</option>
                                                <option data-filter="between-1000-2000">1000 - 2000 €</option>
                                                <option data-filter="between-2000-3000">2000 - 3000 € </option>
                                                <option data-filter="between-3000-4000">3000 - 4000€</option>
                                                <option data-filter="between-4000-5000">4000 - 5000 €</option>
                                                <option data-filter="+5000"> +5000€</option>
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
                            <div id="bikeCatalog" class="grid"></div>
				            <!--<div class="portfolio-item">
				                <div class="portfolio-image effect social-links">
				                    <img src="catalogue/ets200se_Mixte_mini.jpg" alt="">
				                    <div class="image-box-content">
				                        <p>
				                            <a href="catalogue/ets200se_Mixte.jpg" data-lightbox-type="image" title="Conway ets 200 se - Cadre Mixte"><i class="fa fa-expand"></i></a>
				                            <a href="offre.php"><i class="fa fa-link"></i></a>
				                        </p>
				                    </div>
				                </div>
				                <div class="portfolio-description">
				                    <h4 class="title">Conway</h4>
				                    <p>ets 200 se - Mixte</p>
				                </div>
				                <div class="portfolio-date">
				                    <p class="small"><i class="fa fa-eur"></i>78</p>
				                </div>
				            </div>
				            
				            <div class="portfolio-item">
				                <div class="portfolio-image effect social-links">
				                    <img src="catalogue/ets200se_Femme_mini.jpg" alt="">
				                    <div class="image-box-content">
				                        <p>
				                            <a href="catalogue/ets200se_Femme.jpg" data-lightbox-type="image" title="Conway ets 200 se - Cadre Femme"><i class="fa fa-expand"></i></a>
				                            <a href="offre.php"><i class="fa fa-link"></i></a>
				                        </p>
				                    </div>
				                </div>
				                <div class="portfolio-description">
				                    <h4 class="title">Conway</h4>
				                    <p>ets 200 se - Femme</p>
				                </div>
				                <div class="portfolio-date">
				                    <p class="small"><i class="fa fa-eur"></i>78</p>
				                </div>
				            </div>
				            
				            <div class="portfolio-item">
				                <div class="portfolio-image effect social-links">
				                    <img src="catalogue/ets200se_Homme_mini.jpg" alt="">
				                    <div class="image-box-content">
				                        <p>
				                            <a href="catalogue/ets200se_Homme.jpg" data-lightbox-type="image" title="Conway ets 200 se - Cadre Homme"><i class="fa fa-expand"></i></a>
				                            <a href="offre.php"><i class="fa fa-link"></i></a>
				                        </p>
				                    </div>
				                </div>
				                <div class="portfolio-description">
				                    <h4 class="title">Conway</h4>
				                    <p>ets 200 se - Homme</p>
				                </div>
				                <div class="portfolio-date">
				                    <p class="small"><i class="fa fa-eur"></i>78</p>
				                </div>
				            </div>-->
				
				            
				
				        </div>
				        <!-- END: Portfolio Items -->
                        
                    </div>
                </div>
            </div>
        </section>
        <!-- END: CONTENT -->
        

        <script type="text/javascript">

            function loadPortfolio(){
                document.getElementById('bikeCatalog').innerHTML="";
                var utilisation=document.getElementById('widget-bike-utilisation').value;
                var frameType=document.getElementById('widget-bike-frame-type').value;
                var e=document.getElementById('widget-bike-price');
                var price=e.options[e.selectedIndex].value;
                var brand=document.getElementById('widget-bike-brand').options[document.getElementById('widget-bike-brand').selectedIndex].value;
                var e=document.getElementById('widget-bike-electric');
                var electric = e.options[e.selectedIndex].value;


                $.ajax({
                    url: 'include/load_portfolio.php',
                    type: 'post',
                    data: { "frameType": frameType, "utilisation": utilisation, "price": price, "brand": brand, "electric": electric},
                    success: function(response){
                        if (response.response == 'error') {
                            $.notify({
                                message: response.message
                            }, {
                                type: 'danger'
                            });
                        }
                        if(response.response == 'success'){
                            var i=0;
                            var dest="";
                            if(response.bikeNumber=="0"){
                                dest="<p>Aucun vélo ne correspond à votre sélection</p>";
                            }
                            while(i<response.bikeNumber){
                                if(response.bike[i].frameType.toLowerCase()=="h"){
                                    var frameType = "Homme";
                                } else if(response.bike[i].frameType.toLowerCase()=="m"){
                                    var frameType = "Mixte";
                                } else if(response.bike[i].frameType.toLowerCase()=="f"){
                                    var frameType = "Femme";
                                } else{
                                    var frameType = "undefined";
                                }
                                var temp="\
                                <div class=\"portfolio-item "+response.bike[i].brand.toLowerCase()+" "+response.bike[i].frameType.toLowerCase()+" "+response.bike[i].utilisation.toLowerCase().replace(/ /g, '')+"\" \">\
                                    <div class=\"portfolio-image effect social-links\">\
                                        <img src=\"images_bikes/"+response.bike[i].brand.toLowerCase()+"_"+response.bike[i].model.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].frameType.toLowerCase()+"_mini.jpg\" alt=\"\">\
                                        <div class=\"image-box-content\">\
                                            <p>\
                                                <a href=\"images_bikes/"+response.bike[i].brand.toLowerCase()+"_"+response.bike[i].model.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].frameType.toLowerCase()+".jpg\" data-lightbox-type=\"image\" title=\""+response.bike[i].brand+" "+response.bike[i].model+" "+frameType+" \"><i class=\"fa fa-expand\"></i></a>\
                                                <a href=\"offre.php?brand="+response.bike[i].brand.toLowerCase()+"&model="+response.bike[i].model.toLowerCase()+"&frameType="+response.bike[i].frameType.toLowerCase()+"\"><i class=\"fa fa-link\"></i></a>\
                                            </p>\
                                        </div>\
                                    </div>\
                                    <div class=\"portfolio-description\">\
                                        <h4 class=\"title\">"+response.bike[i].brand+"</h4>\
                                        <p>"+response.bike[i].model+" "+frameType+"</p>\
                                    </div>\
                                    <div class=\"portfolio-date\">\
                                        <p class=\"small\">Achat : <i class=\"fa fa-eur\"></i>"+response.bike[i].price+"</p>\
                                        <p class=\"small\">Leasing : <i class=\"fa fa-eur\"></i>"+response.bike[i].leasingPrice+"</p>\
                                    </div>\
                                </div>";
                                dest=dest.concat(temp);                                            
                                i++;
                            }
                            document.getElementById('bikeCatalog').innerHTML = dest;
                            
                            var $grid = $('.grid').isotope({
                            });

                            console.log($grid);
                            var filters = {};


                            $('.portfolio').on('change', function() {
                                var filterValue = $( this ).children("option:selected").attr('data-filter');
                                filterValue=filterValue;
                                console.log(filterValue);
                                console.log($grid);
                                $grid.isotope({ filter: filterValue });
                            });

                        }

                    }
                });
                                
                var classname = document.getElementsByClassName('portfolio');
                for (var i = 0; i < classname.length; i++) {
                    //classname[i].addEventListener('change', loadPortfolio, false);
                }

            }

            loadPortfolio();

        </script>

       
  
        
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
