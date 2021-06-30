window.addEventListener("DOMContentLoaded", function(event) {
	document.getElementsByClassName('commandFleetManagerClick')[0].addEventListener('click', function() { get_orders_fleet_listing()}, false);
});

function get_orders_fleet_listing() {
		document.getElementById('ordersFleetListingSpan').innerHTML='';
    $.ajax({
      url: 'apis/Kameo/orders_management.php',
      type: 'get',
      data: {"action": "list"},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          var dest="";
          var i=0;

          while (i < response.ordersNumber){

						if(response.order[i].status != 'done'){

	            if(response.order[i].status=="new"){
	                var status="A confirmer";
	            }else if(response.order[i].status=="confirmed"){
	                var status="En attente de livraison";
	            }else if(response.order[i].status=="refused"){
	                var status="Refusée";
	            }else{
	                var status=response.order[i].status;
	            }


	            if(response.order[i].testBoolean){
	                if(response.order[i].testStatus=="done"){
	                    var test = "Done";
	                }else if(response.order[i].testStatus=="cancelled"){
	                    var test = "Cancelled";
	                }else{
	                    if(response.order[i].testDate){
	                        var test = response.order[i].testDate.shortDate();
	                    }else{
	                        var test = "TBC";
	                    }
	                }
	            }else{
	                    var test = "N";
	            }
	            if(response.order[i].estimatedDeliveryDate == null){
	                var estimatedDeliveryDate = "TBC";
	            }else{
	                var estimatedDeliveryDate = response.order[i].estimatedDeliveryDate.shortDate();
	            }

							if(response.order[i].type=="leasing"){
								if(response.tvaIncluded=='Y'){
									var price= Math.round(response.order[i].price*1.21*100)/100 + "€/"+traduction.generic_moisTVAC;
								}else{
									var price= Math.round(response.order[i].price*100)/100 + "€/"+traduction.generic_mois;
								}
							}else if(response.order[i].type=="annualLeasing"){
								if(response.tvaIncluded=='Y'){
									var price= Math.round(response.order[i].price*1.21*100)/100 + "€/"/traduction.generic_yearTVAC;
								}else{
									var price= Math.round(response.order[i].price*100)/100 + "€/"/traduction.generic_year;
								}
							}else{
								if(response.tvaIncluded=="Y"){
									var price= Math.round(response.order[i].price*100*1.21)/100 + "€";
								}else{
									var price= Math.round(response.order[i].price*100)/100 + "€";
								}
							}

							var newDiv = document.createElement("div");
							newDiv.setAttribute("id", "progression");
							var newSpan = document.createElement("span");
							newSpan.setAttribute("class", "etape fait");
							var newDivDesc = document.createElement("div");
							newDivDesc.setAttribute("class", "desc");
							var newContent = document.createTextNode(traduction.status_new_command);
							newDivDesc.appendChild(newContent);
							newSpan.appendChild(newDivDesc);
							newDiv.appendChild(newSpan);


							var newSpan = document.createElement("span");

							if(response.order[i].status=="new"){
								newSpan.setAttribute("class", "ligne");
							}else{
								newSpan.setAttribute("class", "ligne fait");
							}


							newDiv.appendChild(newSpan);

							var newSpan = document.createElement("span");
							if(response.order[i].status=="confirmed"){
								newSpan.setAttribute("class", "etape fait");
							}else{
								newSpan.setAttribute("class", "etape");
							}
							var newDivDesc = document.createElement("div");
							newDivDesc.setAttribute("class", "desc");
							var newContent = document.createTextNode(traduction.status_confirmed_command);
							newDivDesc.appendChild(newContent);
							newSpan.appendChild(newDivDesc);
							newDiv.appendChild(newSpan);

							var newSpan = document.createElement("span");
							newSpan.setAttribute("class", "ligne");

							newDiv.appendChild(newSpan);

							var newSpan = document.createElement("span");
							newSpan.setAttribute("class", "etape");
							var newDivDesc = document.createElement("div");
							newDivDesc.setAttribute("class", "desc");
							var newContent = document.createTextNode(traduction.status_waiting_delivery);
							newDivDesc.appendChild(newContent);
							newSpan.appendChild(newDivDesc);
							newDiv.appendChild(newSpan);


							var ligne=document.createElement("tr");
							var column=document.createElement("td");
							var link=document.createElement("a");
							link.setAttribute("class", "updateCommand text-green");
							link.setAttribute("data-target", "#orderManagerFleet");
							link.setAttribute("data-toggle", "modal");
							link.setAttribute("href", "#");
							link.setAttribute("name", response.order[i].ID);
							var newContent = document.createTextNode(response.order[i].ID);
							link.appendChild(newContent);
							column.appendChild(link);
							ligne.appendChild(column);

							var column=document.createElement("td");
							var newContent = document.createTextNode(response.order[i].email);
							column.appendChild(newContent);
							ligne.appendChild(column);
							var column=document.createElement("td");
							var newContent = document.createTextNode(response.order[i].brand+" - "+response.order[i].model);
							column.appendChild(newContent);
							ligne.appendChild(column);
							var column=document.createElement("td");
							var newContent = document.createTextNode(response.order[i].size);
							column.appendChild(newContent);
							ligne.appendChild(column);
							var column=document.createElement("td");
							var newContent = document.createTextNode(price);
							column.appendChild(newContent);
							ligne.appendChild(column);

							var column=document.createElement("td");
							column.appendChild(newDiv);
							ligne.appendChild(column);

							if(response.order[i].status=="new"){
									var column=document.createElement("td");
									column.setAttribute("class", "text-green");
									column.setAttribute("onclick", "validate_command('"+response.order[i].ID+"')");
									var newContent = document.createTextNode(traduction.generic_confirm);
									column.appendChild(newContent);
							}else{
								var column=document.createElement("td");
							}
							ligne.appendChild(column);
							document.getElementById('ordersFleetListingSpan').append(ligne);

						}

            i++;

          }
          displayLanguage();

        $('.updateCommand').click(function(){
          construct_form_for_command_validation(this.name);
        });


        }
      }
    })
}


function validate_command(ID){
    $.ajax({
      url: 'apis/Kameo/orders_management.php',
      type: 'post',
      data: {"action": "confirmCommand", "ID": ID},
      success: function(response){
            if(response.response == 'error') {
              console.log(response.message);
            }
            if(response.response == 'success'){
                get_orders_fleet_listing();
            }
      }
    });
}

function construct_form_for_command_validation(ID){
    retrieve_command_fleet(ID);

    $('#widget-orderFleet-form input[name=testBoolean]').change(function(){
        if($('#widget-orderFleet-form input[name=testBoolean]').is(':checked')){
            $('#widget-orderFleet-form .testAddress').removeClass("hidden");
            $('#widget-orderFleet-form .testDate').removeClass("hidden");
            $('#widget-orderFleet-form .testStatus').removeClass("hidden");
            $('#widget-orderFleet-form .testResult').removeClass("hidden");
        }else{
            $('#widget-orderFleet-form .testAddress').addClass("hidden");
            $('#widget-orderFleet-form .testDate').addClass("hidden");
            $('#widget-orderFleet-form .testStatus').addClass("hidden");
            $('#widget-orderFleet-form .testResult').addClass("hidden");
        }
    });

    $('#widget-orderFleet-form select[name=portfolioID]').change(function(){
        $.ajax({
          url: 'apis/Kameo/load_portfolio.php',
          type: 'get',
          data: {"action": "retrieve", "ID": $('#widget-orderFleet-form select[name=portfolioID]').val()},
          success: function(response){
                if(response.response == 'error') {
                  console.log(response.message);
                }
                if(response.response == 'success'){
                    $('#widget-orderFleet-form input[name=brand]').val(response.brand);
                    $('#widget-orderFleet-form input[name=model]').val(response.model);
                    $('#widget-orderFleet-form select[name=frameType]').val(response.frameType);
                    $('#widget-orderFleet-form .commandBike').attr('src', "images_bikes/"+response.order.portfolioID+".jpg");
                }
          }
        });

    })
}

function retrieve_command_fleet(ID){
  $.ajax({
    url: 'api/orders',
    type: 'get',
    data: {"action": "retrieve", "ID": ID},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){

          document.getElementById('widget-orderFleet-form').reset();
          $('#widget-orderFleet-form input[name=ID]').val(ID);
          $('#widget-refuseCommand-form input[name=ID]').val(ID);
          $('#widget-orderFleet-form input[name=brand]').val(response.order.brand);
          $('#widget-orderFleet-form input[name=model]').val(response.order.model);
          $('#widget-orderFleet-form select[name=frameType]').val(response.order.frameType);
          $('#widget-orderFleet-form select[name=size]').val(response.order.size);
          $('#widget-orderFleet-form select[name=status]').val(response.order.status);
          $('#widget-orderFleet-form input[name=retailPrice]').val((Math.round(response.order.priceHTVA*1.21*100)/100)+" €");
					$('#widget-orderFleet-form select[name=type]').val(response.order.type);
					if(response.order.type=="leasing"){
						$('#widget-orderFleet-form input[name=price]').val(response.order.price+" €/"+traduction.generic_mois);
					}else	if(response.order.type=="annualLeasing"){
						$('#widget-orderFleet-form input[name=price]').val(response.order.price+" €/"+traduction.generic_year);
					}else{
						$('#widget-orderFleet-form input[name=price]').val(response.order.price+" €");
					}
          $('#widget-orderFleet-form input[name=name]').val(response.order.name);
          $('#widget-orderFleet-form input[name=firstName]').val(response.order.firstname);
          $('#widget-orderFleet-form input[name=mail]').val(response.order.email);
          $('#widget-orderFleet-form input[name=phone]').val(response.order.phone);

					$('#widget-orderFleet-form #accessoriesOrderedFleet').html("");
					response.order.accessories.forEach(function(accessory){
						if(accessory.TYPE=="achat"){
							var currency = '€';
						}else if(accessory.TYPE=="leasing"){
							var currency = "€/"+traduction.generic_mois;
						}else if(accessory.TYPE=="annualleasing"){
							var currency = "€/"+traduction.generic_year;
						}
						$('#widget-orderFleet-form #accessoriesOrderedFleet').append('<div class="col-md-4 accessoryCard d-flex" style="margin-bottom : 10px"><div class="card" style="border: 1px solid black; padding : 18px"><div class="card-body text-center">\
							<h5 class="card-title">'+traduction['accessoryCategories_'+accessory.CATEGORY]+'</h5>\
							<h6 class="card-subtitle mb-2 text-muted">'+accessory.BRAND+' - '+accessory.MODEL+'</h6>\
							<img src="images_accessories/'+accessory.catalogID+'.jpg" style="height:100px; width:auto"/>\
							<br><br><span>'+traduction.generic_amount+' : '+accessory.PRICE_HTVA+' '+currency+'</span><br>\
							</div></div></div>');
					})




          var element = document.getElementById("widget-refuseCommand-form");
          element.classList.add("hidden");


          if(response.order.status=="new"){

              var element = document.getElementById("initializeRefuseCommandButton");
              element.classList.remove("hidden");

              var element = document.getElementById("confirmCommandButton");
              element.classList.remove("hidden");


          }else{

              var element = document.getElementById("initializeRefuseCommandButton");
              element.classList.add("hidden");

              var element = document.getElementById("confirmCommandButton");
              element.classList.add("hidden");
          }

          document.getElementById('initializeRefuseCommandButton').addEventListener('click', function() {

                var element = document.getElementById("widget-refuseCommand-form");
                element.classList.remove("hidden");

                var element = document.getElementById("initializeRefuseCommandButton");
                element.classList.add("hidden");

                var element = document.getElementById("confirmCommandButton");
                element.classList.add("hidden");

          }, false);


          $('#widget-orderFleet-form input[name=emailUser]').val(response.order.email);
          $('#widget-orderFleet-form .commandBike').attr('src', "images_bikes/"+response.order.portfolioID+".jpg");
      }
    }
  })
}
