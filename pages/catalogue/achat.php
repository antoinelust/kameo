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
<script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@13.0.1/dist/lazyload.min.js"></script>
<script src="js/language.js"></script>


<style>
* { box-sizing: border-box; }

body { font-family: sans-serif; }

/* ---- grid ---- */

.grid-item--width3 { width: 250px; }
.grid-item--height3 { height: 320px; }


</style>

 <!-- CONTENT -->
		<section class="background-green">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                    	<h1 class="text-light">RECHERCHER</h1>
                        
                        <div class="m-t-30">
                                <div class="row">
                                
                                    <div class="form-group col-md-2">
                                        <label for="widget-contact-form-marque">Marque</label>
											<select class="portfolio" data-filter-group="brand" name="widget-contact-form-marque" id="widget-bike-brand">
									           <option data-filter="" value="*">Toutes nos marques</option>
									           <option data-filter=".ahooga">Ahooga</option>             
									           <option data-filter=".benno">Benno</option>                                                
									           <option data-filter=".bzen">Bzen</option>
									           <option data-filter=".conway">Conway</option>                                                
									           <option data-filter=".douze">Douze</option>
									           <option data-filter=".hnf">HNF Nicolai</option>
									           <option data-filter=".kayza">Kayza</option>                                                
									           <option data-filter=".orbea">Orbea</option>                                            
									           <option data-filter=".victoria">Victoria</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-md-2">
                                        <label for="widget-contact-form-utilisation">Utilisation</label>
											<select class="portfolio" data-filter-group="utilisation" name="widget-contact-form-utilisation" id="widget-bike-utilisation">
                                                <option data-filter="" value="*">Tous types</option>
                                                <option data-filter=".villeetchemin">Ville et chemin</option>
                                                <option data-filter=".ville">Ville</option>
                                                <option data-filter=".toutchemin">Tout chemin</option>
                                                <option data-filter=".pliant">Pliant</option>
                                                <option data-filter=".speedpedelec">Speedpedelec</option>
                                                <option data-filter=".gravel">Gravel</option>
                                                <option data-filter=".vtt">VTT</option>
                                                <option data-filter=".cargo">Cargo</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-md-2">
                                        <label for="widget-contact-form-cadre">Type de cadre</label>
											<select class="portfolio" data-filter-group="cadre" name="widget-contact-form-cadre" id="widget-bike-frame-type">
									           <option data-filter="" value="*">Tous types de cadre</option>
									           <option data-filter=".m" value="M">Mixte</option>
									           <option data-filter=".f" value="F">Femme</option>
									           <option data-filter=".h" value="H">Homme</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-md-3">
                                        <label for="widget-contact-form-electrique">Assistance électrique</label>
											<select class="portfolio" data-filter-group="electrique" name="widget-contact-form-electrique" id="widget-bike-electric">
                                                <option data-filter= "" value="*">Tous</option>
									            <option data-filter=".y">Oui</option>
									            <option data-filter=".n">Non</option>
									       </select>
                                    </div>
                                    
                                    <div class="form-group col-md-2">
                                        <label for="widget-contact-form-prix">Prix Achat (HTVA)</label>
											<select class="portfolio" data-filter-group="prix" name="widget-contact-form-prix" id="widget-bike-price">
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
                  </div>
                  <br><a class="button black-light button-3d effect fill-vertical"  href="bonsplans.php"><span>Retrouvez nos bons plans  <i class="fa fa-arrow-right"></i></span></a>
              </div>
         </section>
                    
                    
         <section>
            <div class="container">
                <div class="row">         
                	<div class="col-md-12">
                    	<h1 class="text-green">NOS VÉLOS</h1>
                        
                        <div class="grid">
                        </div>
                        
				        <!-- END: Portfolio Items -->
                        
                    </div>
                </div>
            </div>
        </section>
        <!-- END: CONTENT -->
        <div class="modal fade" id="bikePicture" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 id="bikePicturetitle" class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row mb20">
                            <img id="bikePictureImage" class="img-responsive img-rounded" alt="" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-b" type="button">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var bikes;
            function loadPortfolio(){
                
                

                var $grid = $('.grid').isotope({
                });
                
                
                //document.getElementById('bikeCatalog').innerHTML="";
                var utilisation=document.getElementById('widget-bike-utilisation').value;
                var frameType=document.getElementById('widget-bike-frame-type').value;
                var e=document.getElementById('widget-bike-price');
                var price=e.options[e.selectedIndex].value;
                var brand=document.getElementById('widget-bike-brand').options[document.getElementById('widget-bike-brand').selectedIndex].value;
                var e=document.getElementById('widget-bike-electric');
                var electric = e.options[e.selectedIndex].value;
                $.ajax({
                    url: 'apis/Kameo/load_portfolio.php',
                    type: 'get',
                    data: { "action": "list", "frameType": frameType, "utilisation": utilisation, "price": price, "brand": brand, "electric": electric},
                    success: function(response){
                        if (response.response == 'error') {
                            $.notify({
                                message: response.message
                            }, {
                                type: 'danger'
                            });
                        }
                        if(response.response == 'success'){
                            var $grid = $('.grid').isotope({
                              itemSelector: '.grid-item',
                            });
                            
                            var i=0;
                            while (i<response.bikeNumber){
                                if(response.bike[i].display=='Y'){
                                    if(response.bike[i].frameType.toLowerCase()=="h"){
                                        var frameType = "Homme";
                                    } else if(response.bike[i].frameType.toLowerCase()=="m"){
                                        var frameType = "Mixte";
                                    } else if(response.bike[i].frameType.toLowerCase()=="f"){
                                        var frameType = "Femme";
                                    } else{
                                        var frameType = "undefined";
                                    }

                                    if(parseInt(response.bike[i].price)<="2000"){
                                        var price="2000";
                                    }else if(parseInt(response.bike[i].price)<="3000"){
                                        var price="between-2000-3000";
                                    }else if(parseInt(response.bike[i].price)<="4000"){
                                        var price="between-3000-4000";
                                    }else if(parseInt(response.bike[i].price)<="5000"){
                                        var price="between-4000-5000";
                                    }else{
                                        var price="5000";
                                    }

                                    var temp="\
                                    <div class=\"grid-item "+response.bike[i].brand.toLowerCase()+" "+response.bike[i].frameType.toLowerCase()+" "+response.bike[i].utilisation.toLowerCase().replace(/ /g, '')+" "+response.bike[i].electric.toLowerCase().replace(/ /g, '')+" "+price+"\" \">\
                                        <div class=\"portfolio-image effect social-links\">\
                                            <img src=\"images_bikes/"+response.bike[i].brand.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].model.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].frameType.toLowerCase()+"_mini.jpg\" alt=\"image_"+response.bike[i].brand.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].model.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].frameType.toLowerCase()+"\" class=\"lazy\">\
                                            <div class=\"image-box-content\">\
                                                <p>\
                                                    <a data-target=\"#bikePicture\" data-toggle=\"modal\" href=\"#\" onclick=\"updateBikePicture('"+response.bike[i].brand+"', '"+response.bike[i].model+"', '"+response.bike[i].frameType+"')\"><i class=\"fa fa-expand\"></i></a>\
                                                    <a href=\"offre.php?brand="+response.bike[i].brand.toLowerCase()+"&model="+response.bike[i].model.toLowerCase()+"&frameType="+response.bike[i].frameType.toLowerCase()+"\"><i class=\"fa fa-link\"></i></a>\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class=\"portfolio-description\">\
                                            <a href=\"offre.php?brand="+response.bike[i].brand.toLowerCase()+"&model="+response.bike[i].model.toLowerCase()+"&frameType="+response.bike[i].frameType.toLowerCase()+"\"><h4 class=\"title\">"+response.bike[i].brand+"</h4></a>\
                                            <p>"+response.bike[i].model+" "+frameType+"\
                                            <br>"+response.bike[i].utilisation+"\
                                            <br><b class=\"text-green\">Achat :"+response.bike[i].price+"  €</b>\
                                            <br><b class=\"text-green\">Location :"+response.bike[i].leasingPrice+" €/mois</b></p>\
                                        </div>\
                                    </div>";

                                  var $item = $(temp);
                                  // add width and height class
                                  $item.addClass( 'grid-item--width3').addClass('grid-item--height3');
                                  $grid.append( $item )
                                    // add and lay out newly appended elements
                                    .isotope( 'appended', $item );                                            
                                }
                                i++;
                            }
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

                            $('.grid').isotope("layout");                                


                    }                        

                    }
                });                                
            }
            loadPortfolio();
            
            
            
            
            function updateBikePicture(brand, model, frameType)
            {

                document.getElementById('bikePicturetitle').innerHTML=brand+" "+model;
                document.getElementById('bikePictureImage').src="images_bikes/"+brand.toLowerCase().replace(/ /g, '-')+"_"+model.toLowerCase().replace(/ /g, '-')+"_"+frameType.toLowerCase()+".jpg";
                
            }
        </script>
	<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->


	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>


</body>

</html>

