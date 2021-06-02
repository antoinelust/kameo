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
        <?php include 'include/tb_popup.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@13.0.1/dist/lazyload.min.js"></script>
        <script src="js/language.js"></script>
        <script src="js/global_functions.js"></script>

        <style>
            * {
                box-sizing: border-box;
            }

            body {
                font-family: sans-serif;
            }

        </style>

        <!-- CONTENT -->
        <section>
            <div class="container-fullwidth">
				<h1 class="text-green text-center"><?= L::accessoryCategories_title_portfolio; ?></h1>

				<div class="col-md-10 center">

					<a href="catalogue_accessoires.php?category=antivol">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_cadenas.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_cadenas; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<a href="catalogue_accessoires.php?category=casques">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_casques.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_casque; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<a href="catalogue_accessoires.php?category=textiles">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_textiles.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_textiles; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<a href="catalogue_accessoires.php?category=sacoche">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_bagagerie.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_sacoche; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<a href="catalogue_accessoires.php?category=phare">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_eclairage.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_lights; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<a href="catalogue_accessoires.php?category=siege_enfant">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_siegesenfants.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_siege_enfant; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<!--<a href="catalogue_accessoires.php?category=Remorques">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_remorquesvelo.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light">Remorques</h3></div>
							<div class="space"></div>
						</div>
					</a>-->

					<a href="catalogue_accessoires.php?category=gourde">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_portesbidons.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_gourde; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<a href="catalogue_accessoires.php?category=garde_boue">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_gardesboue.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_garde_boue; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<a href="catalogue_accessoires.php?category=outils">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_outils.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_tools; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<!--<a href="catalogue_accessoires.php?category=GPS">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_gps.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_GPS; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>-->

					<a href="catalogue_accessoires.php?category=pompe_a_velo">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_pompes.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_pompe_a_velo; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<a href="catalogue_accessoires.php?category=Produitsentretien">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_produitsentretien.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_produit_entretien; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

					<a href="catalogue_accessoires.php?category=selle">
						<div class="container col-md-4" style="position : relative ; text-align : center ; color : white">
							<img src="images/Cover_Catalogue/Cover_Catalogue_selle.jpg" alt="Snow" style="width:100%;">
							<div class="centered" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><h3 class="text-light"><?= L::accessoryCategories_selle; ?></h3></div>
							<div class="space"></div>
						</div>
					</a>

				</div>

            </div>
        </section>

        <script type="text/javascript">


        $('#inputHomeAddressInput').change(function(){
          $('#inputHomeAddress').removeClass('has-error');
          $('#inputHomeAddress').removeClass('has-success');
          $('#inputHomeAddress').addClass('has-warning');
          $('#inputHomeAddress2').removeClass('fa-check');
          $('#inputHomeAddress2').addClass('fa-info-circle');
          $('#inputHomeAddress2').removeClass('fa-close');

          var address=$('#inputHomeAddressInput').val();
          $.ajax({
            url: 'apis/Kameo/validate_address.php',
            method: 'get',
            data: {'address': address},
            success: function(response){
              if (response.response == "success") {
                  $('#inputHomeAddress').removeClass('has-error');
                  $('#inputHomeAddress').addClass('has-success');
                  $('#inputHomeAddress').removeClass('has-warning');
                  $('#inputHomeAddress2').addClass('fa-check');
                  $('#inputHomeAddress2').removeClass('fa-info-circle');
                  $('#inputHomeAddress2').removeClass('fa-close');
              }
              else{
                  $('#inputHomeAddress').addClass('has-error');
                  $('#inputHomeAddress').removeClass('has-success');
                  $('#inputHomeAddress').removeClass('has-warning');
                  $('#inputHomeAddress2').removeClass('fa-check');
                  $('#inputHomeAddress2').removeClass('fa-info-circle');
                  $('#inputHomeAddress2').addClass('fa-close');
              }
            }
          });
        });
        $('#inputWorkAddressInput').change(function(){
            $('#inputWorkAddress').removeClass('has-error');
            $('#inputWorkAddress').removeClass('has-success');
            $('#inputWorkAddress').addClass('has-warning');
            $('#inputWorkAddress2').removeClass('fa-check');
            $('#inputWorkAddress2').addClass('fa-info-circle');
            $('#inputWorkAddress2').removeClass('fa-close');


            var address=$('#inputWorkAddressInput').val();
            $.ajax({
                url: 'apis/Kameo/validate_address.php',
                method: 'get',
                data: {'address': address},
                success: function(response){
                  if (response.response == "success") {
                      $('#inputWorkAddress').removeClass('has-error');
                      $('#inputWorkAddress').addClass('has-success');
                      $('#inputWorkAddress').removeClass('has-warning');
                      $('#inputWorkAddress2').addClass('fa-check');
                      $('#inputWorkAddress2').removeClass('fa-info-circle');
                      $('#inputWorkAddress2').removeClass('fa-close');
                  }
                  else{
                      $('#inputWorkAddress').addClass('has-error');
                      $('#inputWorkAddress').removeClass('has-success');
                      $('#inputWorkAddress').removeClass('has-warning');
                      $('#inputWorkAddress2').removeClass('fa-check');
                      $('#inputWorkAddress2').removeClass('fa-info-circle');
                      $('#inputWorkAddress2').addClass('fa-close');
                  }
                }
            });
        });

        $('#cash4bike-form select[name=transport]').off();
        $('#transportSelect').change(function(){
          if($(this).val()=="personnalCar" || $(this).val() == "companyCar"){
            $('.essence').fadeIn("slow");
          }else{
            $('.essence').fadeOut("slow");
          }
        });


            var bikes;
            function loadPortfolio(revenuEmployee = null, type = null, homeAddress = null, workAddress = null, prime = null, transport = null, transportationEssence = null, frequenceBikePerWeek = null, size = '*') {
                $('.grid').html("");
                var $grid = $('.grid').isotope({});
                $grid.isotope('destroy');

                $.ajax({
                    url: 'apis/Kameo/load_portfolio.php',
                    type: 'get',
                    data: {
                        "action": "list",
                        "revenuEmployee" : revenuEmployee,
                        "frequenceBikePerWeek" : frequenceBikePerWeek,
                        "homeAddress" : homeAddress,
                        "workAddress" : workAddress,
                        "type" : type,
                        "prime" : prime,
                        "transport" : transport,
                        "transportationEssence" : transportationEssence,
                        "size" : size
                    },
                    success: function(response) {
                        if (response.response == 'error') {
                            $.notify({
                                message: response.message
                            }, {
                                type: 'error'
                            });
                        }
                        if (response.response == 'success') {
                            var $grid = $('.grid').isotope({
                                itemSelector: '.grid-item',
                            });

                            var i = 0;
                            while (i < response.bikeNumber) {
                                if (response.bike[i].display == 'Y') {
                                    if (response.bike[i].frameType.toLowerCase() == "h") {
                                        var frameType = "Homme";
                                    } else if (response.bike[i].frameType.toLowerCase() == "m") {
                                        var frameType = "Mixte";
                                    } else if (response.bike[i].frameType.toLowerCase() == "f") {
                                        var frameType = "Femme";
                                    } else {
                                      var frameType = "undefined";
                                    }

                                    if (parseInt(response.bike[i].price) <= "2000") {
                                        var price = "2000";
                                    } else if (parseInt(response.bike[i].price) <= "3000") {
                                        var price = "between-2000-3000";
                                    } else if (parseInt(response.bike[i].price) <= "4000") {
                                        var price = "between-3000-4000";
                                    } else if (parseInt(response.bike[i].price) <= "5000") {
                                        var price = "between-4000-5000";
                                    } else {
                                        var price = "5000";
                                    }

                                    if(response.bike[i].estimatedDeliveryDate != null){
                                      var estimatedDeliveryDate = new Date(response.bike[i].estimatedDeliveryDate);
                                      estimatedDeliveryDate.setDate(estimatedDeliveryDate.getDate() + 7);
                                    }
                                    var temp = "\
                                    <div style='display: block' class=\"col-md-2 grid-item " + response.bike[i].brand.toLowerCase() + " " + response.bike[i].frameType.toLowerCase() + " " + response.bike[i].utilisation.toLowerCase().replace(/ /g, '') + " " + response.bike[i].electric.toLowerCase().replace(/ /g, '') + " " + price + "\" \">\
                                        <div class=\"portfolio-image effect social-links\">\
                                            <img src=\"images_bikes/" + response.bike[i].ID + "_mini.jpg\" alt=\"image_" + response.bike[i].brand.toLowerCase().replace(/ /g, '-') + "_" + response.bike[i].model.toLowerCase().replace(/ /g, '-') + "_" + response.bike[i].frameType.toLowerCase() + "\" class=\"lazy\">\
                                            <div class=\"image-box-content\">\
                                                <p>\
                                                    <a data-target=\"#bikePicture\" data-toggle=\"modal\" href=\"#\" onclick=\"updateBikePicture('" + response.bike[i].ID + "', '" + response.bike[i].brand + "', '" + response.bike[i].model + "')\"><i class=\"fa fa-expand\"></i></a>\
                                                    <a href=\"offre.php?ID=" + response.bike[i].ID + "\"><i class=\"fa fa-link\"></i></a>\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class=\"portfolio-description\">\
                                          <a href=\"offre.php?ID="+response.bike[i].ID+"\"><h4 class=\"title\">" + response.bike[i].brand + "</h4></a>\
                                          <p>"+response.bike[i].model+"\
                                          <br>"+traduction.generic_frame+" : "+traduction["generic_"+frameType]+"\
                        									<br>"+traduction["generic_"+response.bike[i].utilisation.replace(/ /g, '_')]+"<br>";
                                          var stock = (response.bike[i].stockTotal > 0) ? "<span class='text-green'><strong>"+traduction.stock_de_stock+"</strong></span>" : ((response.bike[i].estimatedDeliveryDate != null) ? "<span class='text-orange'><strong>"+traduction.stock_available_soon+"</strong></span><sup><i class='fa fa-question-circle' rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='bottom' data-html='true' data-title=\"<div style='position:relative;overflow:auto'><div style='line-height:20px; float:left;border-radius: 3px;text-align:left'>"+traduction.stock_available_soon_text+get_date_string_european(estimatedDeliveryDate)+"</div></div\"></i></sup></strong>" : "<span class='text-red'><strong>"+traduction.stock_not_in_stock+"</strong></span><sup><i class='fa fa-question-circle' rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='bottom' data-html='true' data-title=\"<div style='position:relative;overflow:auto'><div style='line-height:20px; float:left;border-radius: 3px;text-align:left'>"+traduction.stock_not_in_stock_text+"</div> \"> </i></sup>");
                                          temp=temp.concat(stock);
                                    if (typeof response.bike[i].impactOnNetSalary !== 'undefined' && typeof response.bike[i].impactOnGrossSalary != 'undefined') {
                                      var textExplanation="Montant du leasing : "+response.bike[i].leasingPrice+" €/mois<br/>Impact salaire brut : "+Math.round(response.bike[i].impactOnGrossSalary*10)/10+" €/mois<br/>\
                                      <b>Impact salaire net : "+Math.round(response.bike[i].impactOnNetSalary*10)/10+" €/mois</b>";
                                      if(response.bike[i].impactBikeAllowance != 0 || response.bike[i].impactCarSavingMoney != 0){
                                        textExplanation = textExplanation.concat("<hr/>");
                                      }
                                      if(response.bike[i].impactBikeAllowance != 0){
                                        textExplanation = textExplanation.concat("Impact prime vélo : "+Math.round(response.bike[i].impactBikeAllowance*10)/10+" €/mois")
                                      }
                                      if(response.bike[i].impactCarSavingMoney != 0){
                                        textExplanation = textExplanation.concat("<br/>Economie véhicule : "+Math.round(response.bike[i].impactCarSavingMoney*10)/10+" €/mois")
                                      }
                                      textExplanation = textExplanation.concat("<hr/>");
                                      if(response.bike[i].realImpact > 0){
                                        textExplanation = textExplanation.concat("<b>Votre vélo vous coûtera réellement "+Math.round(response.bike[i].realImpact)+" €/mois</b>");
                                      }else{
                                        textExplanation = textExplanation.concat("<span class='text-green'>Votre vélo vous rapportera <br/><b>"+Math.abs(Math.round(response.bike[i].realImpact))+" €/mois !</b>");
                                      }

                                      if(response.bike[i].realImpact > 0){
                                        temp=temp.concat("<br><b>"+traduction.achat_achat+": " + Math.round(response.bike[i].price) + "  €</b>\
                                        <br>Coût brut : " + response.bike[i].leasingPrice + " €/mois<br>\
                                        <b class=\"text-red\" data-toggle=\"popover\" data-html=\"true\" data-trigger=\"hover\" data-container=\"body\"  data-placement=\"top\" title=\"Détail calcul\" data-content=\""+textExplanation+"\">\
                                        Cout réel : "+ Math.round(response.bike[i].realImpact)+" €/"+traduction.generic_mois+"  <i class='fa fa-question-circle'></i></b></p></div></div>");
                                      }else{
                                        temp=temp.concat("<br><b>"+traduction.achat_achat+": "+ Math.round(response.bike[i].price) + "  €</b>\
                                        <br>Coût brut : " + response.bike[i].leasingPrice + " €/mois<br>\
                                        <b class=\"text-green\" data-toggle=\"popover\" data-html=\"true\" data-trigger=\"hover\" data-container=\"body\"  data-placement=\"top\" title=\"Détail calcul\" data-content=\""+textExplanation+"\">\
                                        Gain réel : "+ Math.abs(Math.round(response.bike[i].realImpact))+" €/"+traduction.generic_mois+"  <i class='fa fa-question-circle'></i></b></p></div></div>");
                                      }
                                    }else{
                                      temp=temp.concat("<br>"+traduction.achat_achat+": " + Math.round(response.bike[i].price) + "  €\
                                      <br><b>Leasing : " + response.bike[i].leasingPrice + " €/"+traduction.generic_mois+"</b></p></div></div>");
                                    }
                                    var $item = $(temp);
                                    $grid.append($item)
                                        // add and lay out newly appended elements
                                        .isotope('appended', $item);
                                }
                                i++;
                            }

                            if(response.realImpactCalculated=="Y"){
                              $.notify({
                                  message: "Catalogue actualisé avec vos informations"
                              }, {
                                  type: 'success'
                              });
                              $('.ac-content').css("display","none");
                            }



                            var $elems = $('.grid-item img');
                            var elemsCount = $elems.length;
                            var loadedCount = 0;
                            $elems.on('load', function () {
                              // increase the loaded count
                              loadedCount++;
                              // if loaded count flag is equal to elements count
                              if (loadedCount == elemsCount) {
                                setTimeout(function(){
                                  $('.grid').isotope();
                                }, 500);
                                $('[data-toggle="tooltip"]').tooltip({
                                  container: "body",
                                })
                              }
                            });



                            $(function () {
                              $('[data-toggle="popover"]').popover();
                              // get all images and iframes
                            })

                            var filters = {};


                            var filterValue = "";
                            $('.portfolio').each(function(element){
                              var $cible = $(element.currentTarget);
                              var filterGroup = $cible.attr('data-filter-group');
                              filters[filterGroup] = $(this).children("option:selected").attr('data-filter');
                              filterValue += filters[filterGroup];
                              $grid.isotope({
                                  filter: filterValue
                              });
                            })

                            $('.portfolio').off();
                            $('.portfolio').on('change', function() {
                              var filterValue = "";
                              $('.portfolio').each(function(element){
                                var $cible = $(element.currentTarget);
                                var filterGroup = $cible.attr('data-filter-group');
                                filters[filterGroup] = $(this).children("option:selected").attr('data-filter');
                                filterValue += filters[filterGroup];
                                $grid.isotope({
                                    filter: filterValue
                                });
                              });
                              if ( !$grid.data('isotope').filteredItems.length ) {
                                $('.no_results').fadeIn('slow');
                              } else {
                                $('.no_results').fadeOut('fast');
                              }
                            });
                        }

                    }
                });
            }
            loadPortfolio();
            jQuery("#cash4bike-form").validate({
              submitHandler: function(form) {
                loadPortfolio($('#cash4bike-form input[name=revenu]').val(), $('#cash4bike-form input[name=type]').val(), $('#cash4bike-form input[name=domicile]').val(), $('#cash4bike-form input[name=travail]').val(), $('#cash4bike-form input[name=prime]:checked').val(), $('#cash4bike-form select[name=transport]').val(), $('#cash4bike-form input[name=transportationEssence]:checked').val(), $('#cash4bike-form select[name=frequence]').val(), $('#cash4bike-form select[name=size]').val());
              }
            });


            function updateBikePicture(ID, brand, model) {

                document.getElementById('bikePicturetitle').innerHTML = brand + " " + model;
                document.getElementById('bikePictureImage').src = "images_bikes/" + ID + ".jpg";

            }

            window.addEventListener( "pageshow", function ( event ) {
              $("#widget-bike-brand").val("*");
              $("#widget-bike-utilisation").val("*");
              $("#widget-contact-form-cadre").val("*");
              $("#widget-bike-electric").val("*");
              $("#widget-contact-form-prix").val("*");
            });

            $('#achat_sidebar select[name=size]').off();
            $('#achat_sidebar select[name=size]').change(function(){
              loadPortfolio($('#cash4bike-form input[name=revenu]').val(), $('#cash4bike-form input[name=type]').val(), $('#cash4bike-form input[name=domicile]').val(), $('#cash4bike-form input[name=travail]').val(), $('#cash4bike-form input[name=prime]:checked').val(), $('#cash4bike-form select[name=transport]').val(), $('#cash4bike-form input[name=transportationEssence]:checked').val(), $('#cash4bike-form select[name=frequence]').val(), $('#achat_sidebar select[name=size]').val());
            })



        </script>
        <?php include 'include/footer.php'; ?>
    </div>
    <!-- END: WRAPPER -->

    <!-- Theme Base, Components and Settings -->
    <script src="js/theme-functions.js"></script>
    <!-- TB Popup Redirection -->
    <script src="js/tb_popup.js"></script>
</body>




</html>
