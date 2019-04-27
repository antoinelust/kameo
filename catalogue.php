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
                            <form id="widget-contact-form" action="include/contact-form.php" role="form" method="post">
                                <div class="row">
                                
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-marque">Marque</label>
											<select name="widget-contact-form-marque" id="widget-contact-form-marque">
									           <option value="Toutes">Toutes nos marques</option>
									           <option value="Conway">Conway</option>
									           <option value="Orbea">Orbea</option>
									           <option value="Bzen">Bzen</option>
									           <option value="Ahooga">Ahooga</option>
									           <option value="Stevens">Stevens</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-utilisation">Utilisation</label>
											<select name="widget-contact-form-utilisation" id="widget-contact-form-utilisation">
									           <option value="Tous">Tous types d'utilisation</option>
									           <option value="Villeetchemin">Ville et chemin</option>
									           <option value="Ville">Ville</option>
									           <option value="Toutchemin">Tout chemin</option>
									           <option value="Pliant">Pliant</option>
									           <option value="Speedpedelec">Speedpedelec</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-cadre">Type de cadre</label>
											<select name="widget-contact-form-cadre" id="widget-contact-form-cadre">
									           <option value="Tous">Tous types de cadre</option>
									           <option value="Mixte">Mixte</option>
									           <option value="Femme">Femme</option>
									           <option value="Homme">Homme</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-electrique">Assistance électrique</label>
											<select name="widget-contact-form-electrique" id="widget-contact-form-electrique">
									           <option value="Oui">Oui</option>
									           <option value="Non">Non</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                        <label for="widget-contact-form-prix">Prix Leasing (HTVA)</label>
											<select name="widget-contact-form-prix" id="widget-contact-form-prix">
									           <option value="-50"> -50€</option>
									           <option value="50-75">50€ - 75€</option>
									           <option value="75-100" selected>75€ - 100€</option>
									           <option value="100-125">100€ - 125€</option>
									           <option value="125-150">125€ - 150€</option>
									           <option value="+150"> +150€</option>
									       </select>
                                    </div>
									
										                                     
                                
                                <input type="text" class="hidden" id="widget-contact-form-antispam" name="widget-contact-form-antispam" value="" />
                                <button  id="fr" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-search"></i>&nbsp;Rechercher</button>
                                </div>
                            </form>
                           </div>
                   
                    </div>
                    <div class="col-md-8">
                    	<div class="heading heading text-left m-b-20">
                        <h2 class="fr">Nos vélos</h2>
                        </div>
                        
                        <!-- Portfolio Items -->
				        <div id="isotope" class="isotope portfolio-items" data-isotope-item-space="2" data-isotope-mode="masonry" data-isotope-col="3" data-isotope-item=".portfolio-item">
				            <div class="portfolio-item">
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
				            </div>
				
				            
				
				        </div>
				        <!-- END: Portfolio Items -->
                        
                    </div>
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
