
window.addEventListener("DOMContentLoaded", function(event) {
    $( ".orderBike" ).click(function() {
        get_command_user(email);
    });

    $('#achat_sidebar select[name=size]').off();
    $('.searchCol select[name=size]').change(function(){
      load_cafetaria($('.searchCol select[name=size]').val());
    })
});

function load_cafetaria(size='*'){
  $('.loaderOrderPortfolio').removeClass("hidden");
  $('.grid').addClass("hidden");
  $('.no_results').fadeOut();

  $('.grid').html("");
  var $grid = $('.grid').isotope({});
  $grid.isotope('destroy');

  $.ajax({
      url: 'apis/Kameo/orders/orders.php',
      type: 'get',
      data: { "action": "listOrderable"},
      success: function(responseOrderable){
        if(responseOrderable.response == 'error'){
          console.log(responseOrderable.message);
        }
        if(responseOrderable.response == 'success'){
          var bikes = [];
          responseOrderable.bike.forEach( function(bike){
            bikes.push(bike.BIKE_ID);
          });

            $.ajax({
                url: 'apis/Kameo/load_portfolio.php',
                type: 'get',
                data: {
                    "action": "list",
                    "size" : size
                },
                success: function(response){
                  if(responseOrderable.response == 'error'){
                    console.log(response.message);
                  }
                  if(response.response == 'success'){
                    var $grid = $('.grid').isotope({});

            				if (($('.grid').isotope('getItemElements').length == 0))
            				{
            					for (var i=0; i<response.bikeNumber; i++){
                        if(bikes.includes(response.bike[i].ID) && response.bike[i].display == "Y"){

            							if(response.bike[i].frameType.toLowerCase()=="h"){
            								var frameType = "Homme";
            							} else if(response.bike[i].frameType.toLowerCase()=="m"){
            								var frameType = "Mixte";
            							} else if(response.bike[i].frameType.toLowerCase()=="f"){
            								var frameType = "Femme";
            							} else{
            								var frameType = "undefined";
            							}

                          var prices="";
                          var dataprop="";
                          if(responseOrderable.cafeteriaTypes.includes("leasing")){
                            var price = (response.bike[i].leasingPrice*(100-responseOrderable.discount)/100);
                            if(responseOrderable.remainingPriceIncludedInLeasing == "Y"){
                              price = price + (0.16*response.bike[i].price/36);
                            }

                            if(responseOrderable.company == "City Dev"){
                                price = (price + (response.bike[i].price - 2000)/(4312-2000)*(142-135));
                            }

                            if(responseOrderable.tvaIncluded == "Y"){
                              price = (price*1.21*100)/100;
                              priceWithLabel = Math.round(price) + "€/"+traduction.generic_moisTVAC;
                            }else{
                              var priceWithLabel = Math.round(price) + "€/"+traduction.generic_mois;
                            }

                            prices = prices + traduction.mk_order_monthly_leasing_short+" : <strong>"+priceWithLabel+"</strong><br>";
                            dataprop += "data-leasing="+Math.round(price*100)/100+" ";
                            dataprop += 'data-TVA='+responseOrderable.tvaIncluded+' ';
                          }

                          var retailpriceTVAC = Math.round(response.bike[i].price*1.21*100)/100;
                          dataprop = dataprop.concat('data-retail='+retailpriceTVAC.toString()+' ');


                          if(responseOrderable.cafeteriaTypes.includes("annualleasing")){
                              var price = (response.bike[i].leasingPrice)*12;
                              if(responseOrderable.remainingPriceIncludedInLeasing == "Y"){
                                price = price + (0.16*response.bike[i].price/3);
                              }
                              if(responseOrderable.tvaIncluded == "Y"){
                                price = price*1.21;
                                var priceWithLabel = Math.round(price) + "€/"+traduction.generic_yearTVAC;
                              }else{
                                var priceWithLabel = Math.round(price) + "€/"+traduction.generic_year;
                              }
                              prices = prices + traduction.mk_order_annual_leasing_short+" : <strong>"+priceWithLabel+"</strong><br>";
                              dataprop += "data-annualleasing="+price+" ";
                              dataprop += 'data-TVA='+responseOrderable.tvaIncluded+' ';

                          }

                          if(responseOrderable.cafeteriaTypes.includes("achat")){
                            var price = response.bike[i].price;
                            if(responseOrderable.tvaIncluded == "Y"){
                              price = price*1.21;
                              priceWithLabel = Math.round(price) + "€ " + traduction.generic_VATInc;
                            }else{
                              var priceWithLabel = Math.round(price) + "€"
                            }

                            prices = prices + traduction.mk_order_buy_short+" : <strong>"+priceWithLabel+"</strong><br>";
                            dataprop += "data-achat="+price+" ";
                            dataprop += 'data-TVA='+responseOrderable.tvaIncluded+' ';
                          }
                          if(response.bike[i].estimatedDeliveryDate != null){
                            var estimatedDeliveryDate = new Date(response.bike[i].estimatedDeliveryDate);
                            estimatedDeliveryDate.setDate(estimatedDeliveryDate.getDate() + 7);
                          }
                          var stock = (response.bike[i].stockTotal > 0) ? "<span class='text-green'><strong>"+traduction.stock_de_stock+"</strong></span>" : ((response.bike[i].estimatedDeliveryDate != null) ? "<span class='text-orange'><strong>"+traduction.stock_available_soon+"</strong></span><sup><i class='fa fa-question-circle' rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='bottom' data-html='true' data-title=\"<div style='position:relative;overflow:auto'><div style='line-height:20px; float:left;border-radius: 3px;text-align:left'>"+traduction.stock_available_soon_text+get_date_string_european(estimatedDeliveryDate)+"</div> \"> </i></sup>" : "<span class='text-red'><strong>"+traduction.stock_not_in_stock+"</strong></span><sup><i class='fa fa-question-circle' rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='bottom' data-html='true' data-title=\"<div style='position:relative;overflow:auto'><div style='line-height:20px; float:left;border-radius: 3px;text-align:left'>"+traduction.stock_not_in_stock_text+"</div> \"> </i></sup>");


            							var temp="\
                          <div class=\"col-md-4 grid-item " + response.bike[i].brand.toLowerCase() + " " + response.bike[i].frameType.toLowerCase() + " " + response.bike[i].utilisation.toLowerCase().replace(/ /g, '') + " " + response.bike[i].electric.toLowerCase().replace(/ /g, '') +  "\" \">\
            								<div class=\"portfolio-image effect social-links\">\
            									<img src=\"images_bikes/"+response.bike[i].ID+"_mini.jpg\" alt=\"image_"+response.bike[i].brand.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].model.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].frameType.toLowerCase()+"\" class=\"portfolio-img\">\
            									<div class=\"image-box-content\">\
            										<p>\
            											<a data-target=\"#bikePicture\" data-toggle=\"modal\" href=\"#\" onclick=\"updateBikePicture('"+response.bike[i].brand+"', '"+response.bike[i].model+"', '"+response.bike[i].frameType+"')\"></a>\
            											<a data-target=\"#command\" class=\"orderBikeClick\" data-toggle=\"modal\" href=\"#\" name=\""+response.bike[i].ID+"\"><i class=\"fa fa-link\"></i></a>\
            										</p>\
            									</div>\
            								</div>\
            								<div class=\"portfolio-description\">\
            									<h4 class=\"title\">"+response.bike[i].brand+"</h4>\
            									<p>"+response.bike[i].model+"\
                              <br>"+traduction.generic_frame+" : "+traduction["generic_"+frameType]+"\
            									<br>"+traduction["generic_"+response.bike[i].utilisation.replace(/ /g, '_')]+"\
                              <br>"+prices+
                              "<br>"+traduction.mk_order_retail_price_TVAC+" : "+retailpriceTVAC+ " €\
                              <br>"+stock;

                              temp=temp+"\
                              <br><a class=\"button small green button-3d rounded icon-left orderBikeClick\" data-target=\"#command\" data-amount=\""+price+"\" data-type=\""+response.cafeteriaType+"\" data-toggle=\"modal\"\
                              href=\"#\" name=\""+response.bike[i].ID+"\" "+dataprop+">\
            									<span>"+traduction.tabs_order_title+"</span>\
            									</a>\
            									</p>\
            								</div>\
            							</div>";

                          var $item=$(temp);

            						  // add width and height class
            						  $grid.isotope( 'insert', $item );
                        }
            					}

                      $(function () {
                        $('[data-toggle="tooltip"]').tooltip({
                          container: "body",
                        })
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

                        if ( !$grid.data('isotope').filteredItems.length ) {
                          $('.no_results').fadeIn('slow');
                        } else {
                          $('.no_results').fadeOut('fast');
                        }
                      })


                      $('.portfolio').off();
                      $('.portfolio').on('change', function(event) {
                          var $cible = $(event.currentTarget);
                          var filterGroup = $cible.attr('data-filter-group');
                          filters[filterGroup] = $(this).children("option:selected").attr('data-filter');
                          var filterValue = concatValues(filters);
                          $grid.isotope({
                              filter: filterValue
                          });
                          if ( !$grid.data('isotope').filteredItems.length ) {
                            $('.no_results').fadeIn('slow');
                          } else {
                            $('.no_results').fadeOut('fast');
                          }

                      });


                      function concatValues(obj) {
                          var value = '';
                          for (var prop in obj) {
                              value += obj[prop];
                          }
                          return value;
                      }

            					setTimeout(function(){
            						$( ".orderBikeClick" ).click(function() {
            							fillCommandDetails(this.name, $(this).data('amount'), $(this).data('type'), Math.round($(this).data('leasing')*100)/100, Math.round($(this).data('annualleasing')*100)/100, Math.round($(this).data('achat')*100)/100, $(this).data('tva'), $(this).data('retail'));
            						});
            						$('.grid').isotope();
            					}, 1000);


                      $('.loaderOrderPortfolio').addClass("hidden");
                      $('.grid').removeClass("hidden");
            				}
                  }
                }
            })
        }
      }
  })
}


function load_cafetaria_accessories(){
  $('.loaderOrderPortfolioAccessories').removeClass("hidden");
    $('.gridForAccessories').html("");
    var $grid = $('.gridForAccessories').isotope({});
    $grid.isotope('destroy');

    $.ajax({
        url: 'apis/Kameo/orders/orders.php',
        type: 'get',
        data: { "action": "listOrderableAccessories"},
        success: function(responseOrderable){
          if(responseOrderable.response == 'error'){
            console.log(responseOrderable.message);
          }
          if(responseOrderable.response == 'success'){
            var accessoriesMandatory = [];
            var accessoriesRefused = [];
            var accessoriesAvailable = [];
            var allAllowed = false;
            if(responseOrderable.accessories != null){
              responseOrderable.accessories.forEach( function(accessory){
                if(accessory.TYPE=='Mandatory'){
                  accessoriesMandatory.push(parseInt(accessory.ACCESSORY_ID));
                }else if(accessory.TYPE=='Available'){
                  if(accessory.ACCESSORY_ID=='*'){
                    allAllowed=true;
                  }else{
                    accessoriesAvailable.push(parseInt(accessory.ACCESSORY_ID));
                  }
                }else if(accessory.TYPE=='Available'){
                  accessoriesRefused.push(parseInt(accessory.ACCESSORY_ID));
                }
              });
            }
            $.ajax({
                url: 'apis/Kameo/load_portfolio_accessories.php',
                type: 'get',
                data: {
                    "action": "list"
                },
                success: function(response){
                  if(responseOrderable.response == 'error'){
                    console.log(response.message);
                  }
                  if(response.response == 'success'){
                      var categories = "";
                      var i = 0;
                      $('#accessoriesBasket').html("");
                      var $grid = $('.gridForAccessories').isotope({});
                      categories=[];
                      var category='';
                      response.accessories.forEach( function(accessory){
                        if(accessoriesMandatory.includes(accessory.catalogID)){
                          $('#accessoriesBasket').append('<div class="col-md-4 accessoryCard d-flex" style="margin-bottom : 10px"><div class="card" style="border: 1px solid black; padding : 18px"><div class="card-body text-center">\
                            <h5 class="card-title">'+traduction['accessoryCategories_'+accessory.CATEGORY]+' - <span style="background-color:red">'+traduction.generic_mandatory+'</h5>\
                            <h6 class="card-subtitle mb-2 text-muted">'+accessory.BRAND+accessory.MODEL+'</h6>\
                            <img src="images_accessories/'+accessory.catalogID+'.jpg" style="height:100px; width: auto"/>\
                            <br><span>'+traduction.generic_amount+' : '+Math.round(accessory.PRICE_HTVA/36*1.25*100)/100+' €/'+traduction.generic_mois+'</span><br>\
                            <input type="text" class="hidden" name="accessory[]" value="'+accessory.catalogID+'">\
                            <input type="text" class="hidden" name="accessoryBillingType[]" value="leasing">\
                            <input type="text" class="hidden" name="accessoryAmount[]" value="'+Math.round(accessory.PRICE_HTVA/36*1.25*100)/100+'">\
                            </div></div></div>');
                          $('#widget-command-form #accessoriesBasketEmpy').html("");
                        }
                        if(accessory.DISPLAY == "Y"){
                          if(category != accessory.CATEGORY){
                            categories.push([accessory.CATEGORY, traduction["accessoryCategories_"+accessory.CATEGORY]]);
                            category=accessory.CATEGORY;
                          }
                          var prices="";
                          var dataprop="";

                          price = Math.round(accessory.PRICE_HTVA*1.21*100)/100;
                          prices = traduction.leasingType_buying+" : "+price+" €<br>";
                          dataprop += "data-achat="+price+" ";

                          if(responseOrderable.cafeteriaTypes.includes("leasing")){
                            if(responseOrderable.tvaIncluded == "Y"){
                              price = accessory.PRICE_HTVA*1.21;
                              price = (price*1.25/36);
                              priceWithLabel = Math.round(price*100)/100 + "€/"+traduction.generic_moisTVAC;
                            }else{
                              price = accessory.PRICE_HTVA*1.25/36;
                              var priceWithLabel = Math.round(price*100)/100 + "€/"+traduction.generic_mois;
                            }
                            prices = prices + " "+traduction.leasingType_monthlyLeasing+" : "+priceWithLabel+"<br>";
                            dataprop += "data-leasing="+price+" ";
                            dataprop += 'data-TVA='+responseOrderable.tvaIncluded+' ';
                          }

                          if(responseOrderable.cafeteriaTypes.includes("annualleasing")){
                              if(responseOrderable.tvaIncluded == "Y"){
                                price = accessory.PRICE_HTVA*1.21;
                                price = (price*1.25/3);
                                var priceWithLabel = Math.round(price*100)/100 + "€/"+traduction.generic_yearTVAC;
                              }else{
                                price = (accessory.PRICE_HTVA*1.25/3);
                                var priceWithLabel = Math.round(price*100)/100 + "€/"+traduction.generic_year;
                              }
                              prices = prices + " "+traduction.leasingType_annualLeasing+": "+priceWithLabel+"<br>";
                              dataprop += "data-annualleasing="+price+" ";
                              dataprop += 'data-TVA='+responseOrderable.tvaIncluded+' ';
                          }

            							var temp="\
                          <div class=\"col-md-4 grid-item " + accessory.CATEGORY.toLowerCase() +"\">\
            								<div class=\"portfolio-image effect social-links\">\
            									<img src=\"images_accessories/"+accessory.catalogID+".jpg\" alt=\"image_"+accessory.BRAND.toLowerCase().replace(/ /g, '-')+"_"+accessory.MODEL.toLowerCase().replace(/ /g, '-')+"\" style='height:100px; width: auto'>\
            									<div class=\"image-box-content\">\
            										<p>\
            											<a data-target=\"#commandAccessory\" class=\"orderAccessoryClick\" data-toggle=\"modal\" href=\"#\" name=\""+accessory.catalogID+"\"><i class=\"fa fa-link\"></i></a>\
            										</p>\
            									</div>\
            								</div>\
            								<div class=\"portfolio-description\">\
            									<h4 class=\"title\">"+traduction["accessoryCategories_"+accessory.CATEGORY]+"</h4>\
                              <p>"+traduction.generic_brand+" : "+accessory.BRAND+"<br>"+
            									traduction.generic_model+" : "+accessory.MODEL+"\
                              <br>"+prices;

                              temp=temp+"\
                              <br><a class=\"button small green button-3d rounded icon-left orderAccessoryClick\" data-target=\"#commandAccessory\" data-toggle=\"modal\"\
                              href=\"#\" name=\""+accessory.catalogID+"\" "+dataprop+">\
            									<span>"+traduction.tabs_order_title+"</span>\
            									</a>\
            									</p>\
            								</div>\
            							</div>";
                          var $item=$(temp);

            						  // add width and height class
            						  $grid.isotope( 'insert', $item );
                        }
            					});

                      categories.sort(compareSecondColumn);

                      function compareSecondColumn(a, b) {
                          if (a[1] === b[1]) {
                              return 0;
                          }
                          else {
                              return (a[1] < b[1]) ? -1 : 1;
                          }
                      }


                      $('#widget-command-form select[name=accessoriesCategories]').empty();
                      $("#widget-command-form select[name=accessoriesCategories]").append(new Option(traduction.generic_all, '*'));
                      categories.forEach( function(accessory){
                        $("#widget-command-form select[name=accessoriesCategories]").append(new Option(accessory[1], accessory[0]));
                      });

                      setTimeout(function(){
                        $grid.isotope( 'reloadItems' ).isotope();
                        $( "img.portfolio-img" ).load(function(){
                          $('.gridForAccessories').isotope();
                        });
                      }, 300);

                      var filters = {};

                      $("#widget-command-form select[name=accessoriesCategories]").on('change', function(event) {
                        if(this.value=='*'){
                          var filterValue=this.value;
                        }else{
                          var filterValue='.'+this.value.toLowerCase();
                        }
                        $grid.isotope({
                            filter: filterValue
                        });
                      });

                      $('.loaderOrderPortfolioAccessories').addClass("hidden");

                      $( ".orderAccessoryClick" ).click(function() {
                        fillCommandAccessoryDetails(this.name, $(this).data('amount'), $(this).data('type'), Math.round($(this).data('leasing')*100)/100, Math.round($(this).data('annualleasing')*100)/100, Math.round($(this).data('achat')*100)/100, $(this).data('tva'));
                      });
                  }
                }
            })
          }
        }
    })
}

function fillCommandAccessoryDetails(ID, price, type, leasing, annualleasing, achat, tva){
  $('#orderAccessoryBillingType').html("");  // delete with id_name


  if(achat)
    $('#orderAccessoryBillingType').append('<div class="form-check"><input class="form-check-input" type="radio" name="billingType" id="billingType1" value="achat" data-amount="'+achat+'" checked><label class="form-check-label" for="billingType1">&nbsp; '+traduction.leasingType_buying+' - '+achat+' €</label></div>');

  if(leasing)
    $('#orderAccessoryBillingType').append('<div class="form-check"><input class="form-check-input" type="radio" name="billingType" id="billingType2" value="leasing" data-amount="'+leasing+'"><label class="form-check-label" for="billingType2">&nbsp; '+traduction.leasingType_monthlyLeasing+' - '+leasing+' €/'+traduction.generic_mois+'</label></div>');

  if(annualleasing)
    $('#orderAccessoryBillingType').append('<div class="form-check"><input class="form-check-input" type="radio" name="billingType" id="billingType3" value="annualleasing" data-amount="'+annualleasing+'"><label class="form-check-label" for="billingType3">&nbsp; '+traduction.leasingType_annualLeasing+' - '+annualleasing+' €/'+traduction.generic_year+'</label></div>');

  if(tva == 'Y'){
    $('#widget-command-form label[for=order_amount]').html(traduction.mk_order_amounttvac);
  }else{
    $('#widget-command-form label[for=order_amount]').html(traduction.mk_order_amounthtva);
  }


  $.ajax({
  url: 'apis/Kameo/load_portfolio_accessories.php',
  type: 'get',
  data: { "action": "retrieve", "catalogID": ID},
  success: function(response){
    if (response.response == 'error') {
      console.log(response.message);
    } else{
      $('#widget-commandAccessory-form input[name=ID]').val(response.accessory.ID);
      $('#widget-commandAccessory-form input[name=category]').val(traduction["accessoryCategories_"+response.accessory.CATEGORY]);
      $('#widget-commandAccessory-form input[name=brand]').val(response.accessory.BRAND);
      $('#widget-commandAccessory-form input[name=model]').val(response.accessory.MODEL);
      $(".commandAccessoryImage").attr("src", "images_accessories/"+response.accessory.ID+".jpg");
    }

  }
})

}

function fillCommandDetails(ID, price, type, leasing, annualleasing, achat, tva, retailPriceTVAC){
    load_cafetaria_accessories();

    var count=0;


    $('#widget-command-form input[name=retailPriceTVAC]').val(retailPriceTVAC);

    if(tva == 'Y'){
      $('#widget-command-form label[for=order_amount]').html(traduction.mk_order_amounttvac);
    }else{
      $('#widget-command-form label[for=order_amount]').html(traduction.mk_order_amounthtva);
    }

    if(leasing){
      $('#widget-command-form select[name=leasing_type]')
        .append('<option value="leasing">'+traduction.leasingType_monthlyLeasing+'</option>');
      $('.order_amount_order').html("€/"+traduction.generic_mois);
      $('#widget-command-form input[name=order_amount]').val(leasing);
      count++;
    }
    if(annualleasing){
      $('#widget-command-form select[name=leasing_type]')
        .append('<option value="annualleasing">'+traduction.leasingType_annualLeasing+'</option>');
      $('#widget-command-form input[name=order_amount]').val(annualleasing);
      $('.order_amount_order').html("€/"+traduction.generic_year);
      count++;
    }
    if(achat){
      $('#widget-command-form select[name=leasing_type]')
        .append('<option value="achat">'+traduction.leasingType_buying+'</option>');
      $('#widget-command-form input[name=order_amount]').val(achat);
      $('.order_amount_order').html("€");
      count++;
    }
    if(count>1){
      $('#widget-command-form select[name=leasing_type]').val("");
      $('.order_amount_order').html("");
    }else{
      $('#widget-command-form select[name=leasing_type]').attr('readonly', true);
    }

    $('#widget-command-form select[name=leasing_type]').change(function(){
      if($('#widget-command-form select[name=leasing_type]').val()=="leasing"){
        $('.order_amount_order').html("€/"+traduction.generic_mois);
        $('#widget-command-form input[name=order_amount]').val(leasing);
      }else if($('#widget-command-form select[name=leasing_type]').val()=="annualleasing"){
        $('.order_amount_order').html("€/"+traduction.generic_year);
        $('#widget-command-form input[name=order_amount]').val(annualleasing);
      }else if($('#widget-command-form select[name=leasing_type]').val()=="achat"){
        $('.order_amount_order').html("€");
        $('#widget-command-form input[name=order_amount]').val(achat);
      }else{
        $('#widget-command-form input[name=order_amount]').val('');
      }
    })
    if(count>1){
      $('#widget-command-form input[name=order_amount]').val('');
    }

    $.ajax({
    url: 'apis/Kameo/load_portfolio.php',
    type: 'get',
    data: { "action": "retrieve", "ID": ID},
    success: function(response){
      if (response.response == 'error') {
        console.log(response.message);
      } else{
        $('#widget-command-form input[name=ID]').val(response.ID);
        $('#widget-command-form select[name=brand]').val(response.brand);
        $('#widget-command-form input[name=model]').val(response.model);
        $('#widget-command-form select[name=frame]').val(response.frameType);
        $('#widget-command-form select[name=utilisation]').val(response.utilisation);
        $('#widget-command-form select[name=electric]').val(response.electric);
        document.getElementsByClassName("commandImage")[0].src="images_bikes/"+response.ID+".jpg";
      }

    }
  })

}

function get_command_user(email){
    $.ajax({
    url: 'apis/Kameo/command.php',
    type: 'get',
    data: { "action": "list", "email": email},
    success: function(response){

      if (response.response == 'error') {
        console.log(response.message);
      } else{
          if(response.commandNumber>0){
              $('.gridForCatalog').addClass("hidden");
              $('.bikeOrdered').removeClass("hidden");

              var i = 0;
              while(i<response.commandNumber){
                  $('#orderBike .brand').html(response[i].brand);
                  $('#orderBike .model').html(response[i].model);
                  $('#orderBike .size').html(response[i].size);
                  $('#orderBike .color').html(response[i].color);
                  $('#orderBike .status').html(response[i].status);
                  if(response[i].remark==""){
                      $('#orderBike .remark').html(traduction.mk_order_no_remark);
                  }else{
                      $('#orderBike .remark').html(response[i].remark);
                  }
                  if(response[i].testDate=="" || response[i].testDate == null){
                      $('#orderBike .testDate').html(traduction.generic_to_confirm);
                  }else{
                      $('#orderBike .testDate').html(response[i].testDate);
                  }
                  if(response[i].testAddress=="" || response[i].testAddress == null){
                      $('#orderBike .testPlace').html(traduction.generic_to_confirm);
                  }else{
                      $('#orderBike .testPlace').html(response[i].testAddress);
                  }
                  if(response[i].deliveryDate=="" || response[i].deliveryDate == null){
                      $('#orderBike .deliveryDate').html(traduction.generic_to_confirm);
                  }else{
                      $('#orderBike .deliveryDate').html(response[i].deliveryDate.shortDate());
                  }
                  if(response[i].deliveryAddress=="" || response[i].deliveryAddress == null){
                      $('#orderBike .deliveryPlace').html(traduction.generic_to_confirm);
                  }else{
                      $('#orderBike .deliveryPlace').html(response[i].deliveryAddress);
                  }
                  $('#orderBike .image').attr('src', "images_bikes/"+response[i].catalogID+".jpg");

                  $('#accessoriesOrdered').html("");
                  response.accessories.forEach( function(accessory){
                    if(accessory.TYPE=="achat"){
                      var currency = '€';
                    }else if(accessory.TYPE=="leasing"){
                      var currency = "€/"+traduction.generic_mois;
                    }else if(accessory.TYPE=="annualleasing"){
                      var currency = "€/"+traduction.generic_year;
                    }

                    $('#accessoriesOrdered').append('<div class="col-md-4 accessoryCard d-flex" style="margin-bottom : 10px"><div class="card" style="border: 1px solid black; padding : 18px"><div class="card-body text-center">\
                      <h5 class="card-title">'+traduction['accessoryCategories_'+accessory.CATEGORY]+'</h5>\
                      <h6 class="card-subtitle mb-2 text-muted">'+accessory.BRAND+' - '+accessory.MODEL+'</h6>\
                      <img src="images_accessories/'+accessory.catalogID+'.jpg" style="height:100px; width:auto"/>\
                      <br><br><span>'+traduction.generic_amount+' : '+accessory.PRICE_HTVA+' '+currency+'</span><br>\
                      </div></div></div>');
                    });
                  i++;
              }
          }else{
              load_cafetaria('*');
          }
      }

    }
  });
}
