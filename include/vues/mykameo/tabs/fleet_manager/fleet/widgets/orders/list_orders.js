window.addEventListener("DOMContentLoaded", function(event) {
	document.getElementsByClassName('commandFleetManagerClick')[0].addEventListener('click', function() { get_orders_fleet_listing()}, false);
});

$( ".fleetmanager" ).click(function() {
    $.ajax({
        url: 'apis/Kameo/initialize_counters.php',
        type: 'post',
        data: { "email": email, "type": "ordersFleet"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('counterOrdersFleet').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.ordersNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.ordersNumber+"</span>";
            }
        }
    })
})


function get_orders_fleet_listing() {
    var email= "<?php echo $user_data['EMAIL']; ?>";
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
          var temp="<table id=\"ordersFleetListingTable\" data-order='[[ 0, \"asc\" ]]' data-page-length='25' class=\"table table-condensed\"><thead><tr><th>ID</th><th><span class=\"fr-inline\">Utilisateur</span><span class=\"en-inline\">User</span><span class=\"nl-inline\">User</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Bike</span></th><th><span class=\"fr-inline\">Taille</span><span class=\"en-inline\">Size</span><span class=\"nl-inline\">Size</span></th><th><span class=\"fr-inline\">Status</span><span class=\"en-inline\">Status</span><span class=\"nl-inline\">Status</span></th><th>Montant</th><th></th></tr></thead><tbody>";
          dest=dest.concat(temp);
          var i=0;

          while (i < response.ordersNumber){

            if(response.order[i].status=="new"){
                var status="A confirmer";
            }else if(response.order[i].status=="confirmed"){
                var status="Commande confirmée";
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
							var price= Math.round(response.order[i].price*100)/100 + "€/mois";
						}else if(response.order[i].type=="annualLeasing"){
							var price= Math.round(response.order[i].price*100)/100 + "€/an";
						}else{
							var price= Math.round(response.order[i].price*100)/100 + "€";
						}


          	temp="<tr><td><a href=\"#\" class=\"updateCommand\" data-target=\"#orderManagerFleet\" data-toggle=\"modal\" name=\""+response.order[i].ID+"\">"+response.order[i].ID+"</td></td><td>"+response.order[i].user+"</td><td>"+response.order[i].brand+" - "+response.order[i].model+"</td><td>"+response.order[i].size+"</td><td>"+status+"</td><td>"+price+"</td>";

            if(response.order[i].status=="new"){
                temp=temp.concat("<td><a class=\"text-green\" onclick=\"validate_command('"+response.order[i].ID+"')\">Confirmer</a></td>")
            }else{
                temp=temp.concat("<td></td>")
            }
            temp=temp.concat("</tr>");

            dest=dest.concat(temp);

            i++;

          }
          var temp="</tobdy></table>";
          dest=dest.concat(temp);
          document.getElementById('ordersFleetListingSpan').innerHTML = dest;

          displayLanguage();

            $('#ordersFleetListingTable thead tr').clone(true).appendTo('#test thead');

            $('#ordersFleetListingTable thead tr:eq(1) th').each(function(i){
                var title=$(this).text();
                $(this).html('<input type="text" placeholder="Search" />');

                $('input', this).on('keyup change', function(){
                    if (table.column(i).search() !== this.value){
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table=$('#ordersFleetListingTable').DataTable({
            });

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
                    $('#widget-orderFleet-form .commandBike').attr('src', "images_bikes/"+response.brand.toLowerCase().replace(/ /g, '-    ')+"_"+response.model.toLowerCase().replace(/ /g, '-')+"_"+response.frameType.toLowerCase()+".jpg");

                }
          }
        });

    })
}

function retrieve_command_fleet(ID){
  $.ajax({
    url: 'apis/Kameo/orders_management.php',
    type: 'get',
    data: {"action": "retrieve", "ID": ID, "email": email},
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
					console.log(response.order.type);
					if(response.order.type=="leasing"){
						$('#widget-orderFleet-form input[name=price]').val(response.order.price+" €/mois");
					}else	if(response.order.type=="annualLeasing"){
						$('#widget-orderFleet-form input[name=price]').val(response.order.price+" €/an");
					}else{
						$('#widget-orderFleet-form input[name=price]').val(response.order.price+" €");
					}
          $('#widget-orderFleet-form input[name=name]').val(response.order.name);
          $('#widget-orderFleet-form input[name=firstName]').val(response.order.firstname);
          $('#widget-orderFleet-form input[name=mail]').val(response.order.email);
          $('#widget-orderFleet-form input[name=phone]').val(response.order.phone);


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
          $('#widget-orderFleet-form .commandBike').attr('src', "images_bikes/"+response.order.brand.toLowerCase().replace(/ /g, '-')+"_"+response.order.model.toLowerCase().replace(/ /g, '-')+"_"+response.order.frameType.toLowerCase()+".jpg");
      }
    }
  })
}
