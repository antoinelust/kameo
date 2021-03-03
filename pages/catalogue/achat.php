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
                    <div class="col-md-12 searchCol background-green" style ="margin-top : -40px">
                      <div class="container" id="achat_sidebar">
                        <h1 class="text-light"><?= L::achat_searchbar_title; ?></h1>
                          <div class="form-group col-md-2">
                              <label for="widget-contact-form-marque"><?= L::achat_searchbar_brand; ?></label>
                              <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })" class="portfolio" data-filter-group="brand" name="widget-contact-form-marque" id="widget-bike-brand">
                                  <option data-filter="" value="*"><?= L::achat_brand_option1; ?></option>
                                  <option data-filter=".ahooga"><?= L::achat_brand_option2; ?></option>
                                  <option data-filter=".benno"><?= L::achat_brand_option3; ?></option>
                                  <option data-filter=".bzen"><?= L::achat_brand_option4; ?></option>
                                  <option data-filter=".conway"><?= L::achat_brand_option5; ?></option>
                                  <option data-filter=".douze"><?= L::achat_brand_option6; ?></option>
                                  <option data-filter=".hnf"><?= L::achat_brand_option7; ?></option>
                                  <option data-filter=".kayza"><?= L::achat_brand_option8; ?></option>
                                  <option data-filter=".moustache">Moustache</option>
                                  <option data-filter=".victoria"><?= L::achat_brand_option10; ?></option>
                              </select>
                          </div>

                          <div class="form-group col-md-2">
                              <label for="widget-contact-form-utilisation"><?= L::achat_searchbar_use; ?></label>
                              <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })" class="portfolio" data-filter-group="utilisation" name="widget-contact-form-utilisation" id="widget-bike-utilisation">
                                  <option data-filter="" value="*"><?= L::achat_use_option1; ?></option>
                                  <option data-filter=".villeetchemin"><?= L::achat_use_option2; ?></option>
                                  <option data-filter=".ville"><?= L::achat_use_option3; ?></option>
                                  <option data-filter=".toutchemin"><?= L::achat_use_option4; ?></option>
                                  <option data-filter=".pliant"><?= L::achat_use_option5; ?></option>
                                  <option data-filter=".speedpedelec"><?= L::achat_use_option6; ?></option>
                                  <option data-filter=".gravel"><?= L::achat_use_option7; ?></option>
                                  <option data-filter=".vtt"><?= L::achat_use_option8; ?></option>
                                  <option data-filter=".cargo"><?= L::achat_use_option9; ?></option>
                                  <option data-filter=".enfant"><?= L::achat_use_option10; ?></option>
                              </select>
                          </div>

                          <div class="form-group col-md-2">
                              <label for="size">Taille</label>
                              <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })"  name="size">
                                <option data-filter="" value="*">Toutes</option>
                                <option data-filter="" value="XS">XS</option>
                                <option data-filter="" value="S">S</option>
                                <option data-filter="" value="M">M</option>
                                <option data-filter="" value="L">L</option>
                                <option data-filter="" value="XL">XL</option>
                                <option data-filter="" value="unique">Unique</option>
                              </select>
                          </div>


                          <div class="form-group col-md-2">
                              <label for="widget-contact-form-cadre"><?= L::achat_searchbar_cadre; ?></label>
                              <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })" class="portfolio" data-filter-group="cadre" name="widget-contact-form-cadre" id="widget-bike-frame-type">
                                  <option data-filter="" value="*"><?= L::achat_cadre_option1; ?></option>
                                  <option data-filter=".m"><?= L::achat_cadre_option2; ?></option>
                                  <option data-filter=".f"><?= L::achat_cadre_option3; ?></option>
                                  <option data-filter=".h"><?= L::achat_cadre_option4; ?></option>
                                  <option data-filter=".unisex"><?= L::achat_cadre_option5; ?></option>
                              </select>
                          </div>

                          <div class="form-group col-md-2">
                              <label for="widget-contact-form-electrique"><?= L::generic_electric; ?></label>
                              <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })" class="portfolio" data-filter-group="electrique" name="widget-contact-form-electrique" id="widget-bike-electric">
                                  <option data-filter="" value="*"><?= L::achat_assist_option1; ?></option>
                                  <option data-filter=".y"><?= L::achat_assist_option2; ?></option>
                                  <option data-filter=".n"><?= L::achat_assist_option3; ?></option>
                              </select>
                          </div>

                          <div class="form-group col-md-2">
                              <label for="widget-contact-form-prix"><?= L::generic_price." (".L::generic_VATExc.")"; ?></label>
                              <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })" data-filter-group="prix" name="widget-contact-form-prix" id="widget-bike-price">
                                  <option data-filter="" value="*" selected><?= L::achat_buyprice_option1; ?></option>
                                  <option data-filter=".2000"><?= L::achat_buyprice_option2; ?></option>
                                  <option data-filter=".between-2000-3000"><?= L::achat_buyprice_option3; ?></option>
                                  <option data-filter=".between-3000-4000"><?= L::achat_buyprice_option4; ?></option>
                                  <option data-filter=".between-4000-5000"><?= L::achat_buyprice_option5; ?></option>
                                  <option data-filter=".5000"><?= L::achat_buyprice_option6; ?></option>
                              </select>
                          </div>
                          <div class="col-md-2">
                              <a class="button rounded black-light button-3d effect icon-left" style="background-color: #ffc300; text-align: center;" href="bons-plans.php"><span><i class="fas fa-percent"></i><?= L::achat_bonsplans_btn; ?></span></a>
                          </div>
                        </div>
                    </div>
          					<div class="space"></div>
                    <div class="col-md-12 catalog">
          						<div style="background-color: #D3EFDD">
          							<h3 class="text-dark text-center">Afin de calculer au mieux le coût réel de votre vélo, merci de renseigner les champs ci-dessous avec vos informations.</h3>
                        <a data-target="#informationsCalcul" data-toggle="modal" href="#" class="text-green"><ins><i class="fas fa-plus"></i> En savoir plus</ins></a>

          							<div class="accordion color">
          								<div class="ac-item" style= "background-color: #D3EFDD">
          									<h1 class="ac-title">Calcul de coût réel</h1>
          									<div class="ac-content">

          										<form id="cash4bike-form" action="apis/Kameo/load_portfolio" role="form" method="get">
          										<div class="row">
          											<div class="col-md-12" style= "background-color: #D3EFDD">
          												<small class="text-dark">*Les informations seront utilisées à des fins commerciales uniquement par KAMEO Bikes et non communiquées à des tiers.</small>
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
          													  <input type="text" name="domicile" class="form-control required" aria-describedby="inputSuccess1Status" placeholder="Rue, numéro, code postal, commune">
          													  <span id="inputHomeAddress2" class="fa fa-close form-control-feedback" aria-hidden="true"></span>
          													  <span id="inputSuccess1Status" class="sr-only">(success)</span>
          													</div>
          													<div id="inputWorkAddress" class="form-group has-error has-feedback">
          													  <label class="control-label" for="inputSuccess2"><?=L::cash4bike_personalinfo_workaddress;?></label>
          													  <input type="text" name="travail" class="form-control required" aria-describedby="inputSuccess2Status" placeholder="Rue, numéro, code postal, commune">
          													  <span id='inputWorkAddress2' class="fa fa-close form-control-feedback" aria-hidden="true"></span>
          													  <span id="inputSuccess2Status" class="sr-only">(success)</span>
          													</div>
          												</div>
          												<div class="space"></div>
          											</div>
          											<div class="col-md-12" style= "background-color: #D3EFDD">
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
          											</div>

          											<div class="form-group col-md-2 center" >
          												<button class="button green button-3d effect fill-vertical" type="submit"><i class="fa fa-calculator"></i>&nbsp;<?=L::cash4bike_calculate_btn;?></button>
          											</div>
          										</div>
          										</form>

          									</div>
          								</div>
          							</div>
          						</div>
                      <h1 class="text-green"><?= L::achat_bikes_title; ?></h1>
                      <div class="grid"></div>
                        <!-- END: Portfolio Items -->
                    </div>
                </div>

                <button onclick="topFunction()" id="btn_goto_top_catalog" title="Go to top"><i class="fas fa-arrow-circle-up"></i></button>
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
                        <button data-dismiss="modal" class="btn btn-b" type="button"><?= L::achat_modal_close; ?></button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
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
                                    } else if (response.bike[i].frameType.toLowerCase() == "Unisex") {
                                        var frameType = "Unisex";
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
                                          <p>" + (response.bike[i].model + " " + frameType).substr(0, 25) + "\
                                          <br>" + response.bike[i].utilisation +"<br>";
                                          var stock = (response.bike[i].stockTotal > 0) ? "<span class='background-green text-light'>De stock</span>" : ((response.bike[i].estimatedDeliveryDate != null) ? "<span class='background-green'>Prochainement</span><sup><i class='fa fa-question-circle' rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='bottom' data-html='true' data-title=\"<div style='position:relative;overflow:auto'><div style='line-height:20px; float:left;border-radius: 3px;text-align:left'>Prochaine arrivée prévue le "+get_date_string_european(estimatedDeliveryDate)+"</div></div\"></i></sup></strong>" : "<span class='background-orange'>Pas en stock</span>");
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
                                        temp=temp.concat("<br><b>Achat : " + Math.round(response.bike[i].price) + "  €</b>\
                                        <br>Coût brut : " + response.bike[i].leasingPrice + " €/mois<br>\
                                        <b class=\"text-red\" data-toggle=\"popover\" data-html=\"true\" data-trigger=\"hover\" data-container=\"body\"  data-placement=\"top\" title=\"Détail calcul\" data-content=\""+textExplanation+"\">\
                                        Cout réel : "+ Math.round(response.bike[i].realImpact)+" €/mois  <i class='fa fa-question-circle'></i></b></p></div></div>");
                                      }else{
                                        temp=temp.concat("<br><b>Achat : " + Math.round(response.bike[i].price) + "  €</b>\
                                        <br>Coût brut : " + response.bike[i].leasingPrice + " €/mois<br>\
                                        <b class=\"text-green\" data-toggle=\"popover\" data-html=\"true\" data-trigger=\"hover\" data-container=\"body\"  data-placement=\"top\" title=\"Détail calcul\" data-content=\""+textExplanation+"\">\
                                        Gain réel : "+ Math.abs(Math.round(response.bike[i].realImpact))+" €/mois  <i class='fa fa-question-circle'></i></b></p></div></div>");
                                      }
                                    }else{
                                      temp=temp.concat("<br>Achat : " + Math.round(response.bike[i].price) + "  €\
                                      <br><b>Leasing : " + response.bike[i].leasingPrice + " €/mois</b></p></div></div>");
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
                                window.scrollTo(0, 0);
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

                            $('.portfolio').on('change', function(event) {
                                var $cible = $(event.currentTarget);
                                var filterGroup = $cible.attr('data-filter-group');
                                filters[filterGroup] = $(this).children("option:selected").attr('data-filter');
                                var filterValue = concatValues(filters);
                                $grid.isotope({
                                    filter: filterValue
                                });
                            });


                            function concatValues(obj) {
                                var value = '';
                                for (var prop in obj) {
                                    value += obj[prop];
                                }
                                return value;
                            }
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
    <!-- Search Bar Scroll Fixed -->
    <script src="js/achat_scroll.js"></script>
    <!-- Scroll to top button -->
    <script src="js/achat_scroll_to_top.js"></script>
    <!-- TB Popup Redirection -->
    <script src="js/tb_popup.js"></script>
</body>


<div
  class="modal fade"
  id="informationsCalcul"
  tabindex="-1"
  role="modal"
  aria-labelledby="modal-label"
  aria-hidden="true"
  style="z-index: 1500; display: none; overflow-y: auto !important"
>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button
          type="button"
          class="close"
          data-dismiss="modal"
          aria-hidden="true"
        >
          ×
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="companyIdHidden" name="companyId" value="" />
        <div class="row">
          <div class="col-sm-12">
            <h4 class="text-green">Informations calcul de coût réel</h4>
            <p><strong>Le module de calcul de coût réel n'est valable que dans le cadre d'un vélo acquis via le salaire brut de l'employé ou de l'ouvrier.</strong><br/>
            Dans un premier temps, nous identifions l'impact sur votre salaire net d'une diminution de votre salaire brut à hauteur du montant du leasing.<br/>
            Ensuite, nous calculons la distance entre votre domicile et votre travail, permettant de calculer le montant des primes kilométriques versées chaque mois (24 cents / km).<br/>
            Enfin, si vous utilisez votre voiture personnelle, nous calculons le montant économisé via l'utilisation du carburant (essence).</p>
            <p>L'acquisition d'un vélo comme moyen de déplacement entre votre domicile et votre travail est très avantageux d'un point de vue fiscal en Belgique, étant déductible à 100%.</p>

			<iframe id="ytplayer" type="text/html" width="720" height="405" src="https://www.youtube.com/embed/e6_4e5RlSGo?controls=0&color=white" frameborder="0" allowfullscreen>

		  </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-b" data-dismiss="modal">
          Fermer
        </button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

  $('#cash4bike-form input[name=domicile]').change(function(){
      $('#inputHomeAddress').removeClass('has-error');
      $('#inputHomeAddress').removeClass('has-success');
      $('#inputHomeAddress').addClass('has-warning');
      $('#inputHomeAddress2').removeClass('fa-check');
      $('#inputHomeAddress2').addClass('fa-info-circle');
      $('#inputHomeAddress2').removeClass('fa-close');

      var address=$('#cash4bike-form input[name=domicile]').val();
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
  $('#cash4bike-form input[name=travail]').change(function(){
      $('#inputWorkAddress').removeClass('has-error');
      $('#inputWorkAddress').removeClass('has-success');
      $('#inputWorkAddress').addClass('has-warning');
      $('#inputWorkAddress2').removeClass('fa-check');
      $('#inputWorkAddress2').addClass('fa-info-circle');
      $('#inputWorkAddress2').removeClass('fa-close');


      var address=$('#cash4bike-form input[name=travail]').val();
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



</script>

</html>
