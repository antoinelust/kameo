
window.addEventListener("DOMContentLoaded", function(event) {
    $( ".orderBike" ).click(function() {
        get_command_user(email);
    });

});

function load_cafetaria(){
    $.ajax({
        url: 'apis/Kameo/orders/orders.php',
        type: 'get',
        data: { "action": "listOrderable"},
        success: function(response){
            if(response.response == 'error'){
              console.log(response.message);
            }
            if(response.response == 'success'){
				var $grid = $('.grid').isotope();


				if (($('.grid').isotope('getItemElements').length == 0))
				{
					for (var i=0; i<response.bikeNumber; i++){

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

              if((response.bike[i].stock)=="0"){
                  var stock="commande";
              }else{
                  var stock="stock";
              }

              var prices="";
              var dataprop="";
              if(response.cafeteriaTypes.includes("leasing")){
                var price = response.bike[i].leasingPrice;
                if(response.bike[i].company == "City Dev"){
                    price = Math.round(price + (response.bike[i].price - 2000)/(4312-2000)*(142-135));
                }

                if(response.tvaIncluded == "Y"){
                  var priceWithLabel = Math.round(price*1.21*100)/100;
                  priceWithLabel = priceWithLabel + "€/"+traduction.generic_moisTVAC;
                }else{
                  var priceWithLabel = price + "€/"+traduction.generic_mois;
                }

                prices = prices + "Leasing men. : "+priceWithLabel+"<br>";
                dataprop += "data-leasing="+price+" ";

              }

              if(response.cafeteriaTypes.includes("annualleasing")){
                  var price = (response.bike[i].leasingPrice)*12;
                  if(response.tvaIncluded == "Y"){
                    var priceWithLabel = Math.round(price*1.21*100)/100;
                    priceWithLabel = priceWithLabel + "€/"+traduction.generic_yearTVAC;
                  }else{
                    var priceWithLabel = price + "€/"+traduction.generic_year;
                  }
                  prices = prices + "Leasing an. : "+priceWithLabel+"<br>";
                  dataprop += "data-annualleasing="+price+" ";

              }

              if(response.cafeteriaTypes.includes("achat")){
                var price = response.bike[i].price;

                if(response.tvaIncluded == "Y"){
                  var priceWithLabel = Math.round(price*1.21*100)/100;
                  priceWithLabel = priceWithLabel + "€ " + traduction.genericTVAC;
                }else{
                  var priceWithLabel = price + "€"
                }

                prices = prices + "Achat : "+priceWithLabel+"<br>";
                dataprop += "data-achat="+price+" ";

              }


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
									<a href=\"offre.php?brand="+response.bike[i].brand.toLowerCase()+"&model="+response.bike[i].model.toLowerCase()+"&frameType="+response.bike[i].frameType.toLowerCase()+"\"><h4 class=\"title\">"+response.bike[i].brand+"</h4></a>\
									<p>"+response.bike[i].model+"\
                  <br>"+traduction.generic_frame+" : "+traduction["generic_"+frameType]+"\
									<br>"+traduction["generic_"+response.bike[i].utilisation.replace(/ /g, '_')]+"\
									<br>"+prices;

                  if(stock==="stock"){
                      temp=temp+"<strong style='font-size: 1em' class=\"background-green text-dark center text-center\">"+traduction.mk_ordertab_underStock+"</strong>";
                  }else{
                      temp=temp+"<strong style='font-size= 1em' class=\"text-green center text-center\">"+traduction.mk_ordertab_preOrder+" <sup><i class='fa fa-question-circle' rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='bottom' data-html='true' data-title=\"<div style='position:relative;overflow:auto'>"+
                      "<div style='line-height:20px; float:left;border-radius: 3px;text-align:left'>"+traduction.order_not_in_stock+"</div></div>"+
                      "\"></i></sup></strong>";
                  }

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
          $('.loaderOrderPortfolio').addClass("hidden");

          $(function () {
            $('[data-toggle="tooltip"]').tooltip({
              container: "body",
            })
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

					//Fix Isotope not displayed after insert/append bug
					setTimeout(function(){
						$grid.isotope( 'reloadItems' ).isotope();
						$( ".orderBikeClick" ).click(function() {
							fillCommandDetails(this.name, $(this).data('amount'), $(this).data('type'), $(this).data('leasing'), $(this).data('annualleasing'), $(this).data('achat'));
						});
						$( "img.portfolio-img" ).load(function(){
							$('.grid').isotope();
						});
						displayLanguage();
					}, 300);
				}
            }
        }
    })
}


function fillCommandDetails(ID, price, type, leasing, annualleasing, achat){
    $('#widget-command-form select[name=leasing_type]')
        .find('option')
        .remove()
        .end()
    ;

    var count=0;
    if(leasing){
      $('#widget-command-form select[name=leasing_type]')
        .append('<option value="leasing">Leasing</option>');
      $('.order_amount_order').html("€/mois");
      $('#widget-command-form input[name=order_amount]').val(leasing);
      count++;
    }
    if(annualleasing){
      $('#widget-command-form select[name=leasing_type]')
        .append('<option value="annualleasing">annualLeasing</option>');
      $('#widget-command-form input[name=order_amount]').val(annualleasing);
      $('.order_amount_order').html("€/mois");
      count++;
    }
    if(achat){
      $('#widget-command-form select[name=leasing_type]')
        .append('<option value="achat">Achat</option>');
      $('#widget-command-form input[name=order_amount]').val(achat);
      $('.order_amount_order').html("€/mois");
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
        $('.order_amount_order').html("€/mois");
        $('#widget-command-form input[name=order_amount]').val(leasing);
      }else if($('#widget-command-form select[name=leasing_type]').val()=="annualleasing"){
        $('.order_amount_order').html("€/mois");
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
                      $('#orderBike .remark').html("<span class=\"fr\">Pas de remarques spécifiques</span><span class=\"en\">No remark</span><span class=\"nl\">No remark</span>");
                  }else{
                      $('#orderBike .remark').html(response[i].remark);
                  }
                  if(response[i].testDate=="" || response[i].testDate == null){
                      $('#orderBike .fr .testDate').html("A confirmer");
                      $('#orderBike .nl .testDate').html("To be confirmed");
                      $('#orderBike .en .testDate').html("To be confirmed");
                  }else{
                      $('#orderBike .testDate').html(response[i].testDate);
                  }
                  if(response[i].testAddress=="" || response[i].testAddress == null){
                      $('#orderBike .fr .testPlace').html("A confirmer");
                      $('#orderBike .nl .testPlace').html("To be confirmed");
                      $('#orderBike .en .testPlace').html("To be confirmed");
                  }else{
                      $('#orderBike .testPlace').html(response[i].testAddress);
                  }
                  if(response[i].deliveryDate=="" || response[i].deliveryDate == null){
                      $('#orderBike .fr .deliveryDate').html("A confirmer");
                      $('#orderBike .nl .deliveryDate').html("To be confirmed");
                      $('#orderBike .en .deliveryDate').html("To be confirmed");
                  }else{
                      $('#orderBike .deliveryDate').html(response[i].deliveryDate);
                  }
                  if(response[i].deliveryAddress=="" || response[i].deliveryAddress == null){
                      $('#orderBike .fr .deliveryPlace').html("A confirmer");
                      $('#orderBike .nl .deliveryPlace').html("To be confirmed");
                      $('#orderBike .en .deliveryPlace').html("To be confirmed");
                  }else{
                      $('#orderBike .deliveryPlace').html(response[i].deliveryAddress);
                  }
                  $('#orderBike .image').attr('src', "images_bikes/"+response[i].catalogID+".jpg");
                  i++;
              }
              displayLanguage();

          }else{
              load_cafetaria();
          }
      }

    }
  });
}
