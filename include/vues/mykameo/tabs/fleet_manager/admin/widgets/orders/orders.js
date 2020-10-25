$( ".fleetmanager" ).click(function() {
    $.ajax({
        url: 'apis/Kameo/initialize_counters.php',
        type: 'post',
        data: { "email": email, "type": "ordersAdmin"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('counterOrdersAdmin').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.ordersNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.ordersNumber+"</span>";
            }
        }
    })
})



function get_orders_listing() {
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
          var temp="<table id=\"ordersListingTable\" data-order='[[ 0, \"asc\" ]]' data-page-length='25' class=\"table table-condensed\"><thead><tr><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Utilisateur</span><span class=\"en-inline\">User</span><span class=\"nl-inline\">User</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Bike</span></th><th><span class=\"fr-inline\">Taille</span><span class=\"en-inline\">Size</span><span class=\"nl-inline\">Size</span></th><th><span class=\"fr-inline\">Status</span><span class=\"en-inline\">Status</span><span class=\"nl-inline\">Status</span></th><th>Test ?</th><th>Date Livraison</th><th>Montant</th></tr></thead><tbody>";
          dest=dest.concat(temp);
          var i=0;

          while (i < response.ordersNumber){
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
            temp="<tr><td><a href=\"#\" class=\"updateCommand\" data-target=\"#orderManager\" data-toggle=\"modal\" name=\""+response.order[i].ID+"\">"+response.order[i].ID+"</td><td><a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+response.order[i].companyID+"\">"+response.order[i].companyName+"</a></td><td>"+response.order[i].user+"</td><td>"+response.order[i].brand+" - "+response.order[i].model+"</td><td>"+response.order[i].size+"</td><td>"+response.order[i].status+"</td><td>"+test+"</td><td>"+estimatedDeliveryDate+"</td><td>"+response.order[i].leasingPrice+" €/mois</td></tr>";
            dest=dest.concat(temp);
            i++;

          }
          var temp="</tobdy></table>";
          dest=dest.concat(temp);
          document.getElementById('ordersListingSpan').innerHTML = dest;

          displayLanguage();

            $('#ordersListingTable thead tr').clone(true).appendTo('#test thead');

            $('#ordersListingTable thead tr:eq(1) th').each(function(i){
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

            var table=$('#ordersListingTable').DataTable({
            });

        $('.updateCommand').click(function(){
          construct_form_for_command_update(this.name);
        });

        var classname = document.getElementsByClassName(
            "internalReferenceCompany"
          );
          for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener(
              "click",
              function () {
                get_company_details(this.name, email, true);
              },
              false
            );
          }


        }
      }
    })

}


function construct_form_for_command_update(ID){
    retrieve_command(ID);

    $('#widget-order-form input[name=testBoolean]').change(function(){
        if($('#widget-order-form input[name=testBoolean]').is(':checked')){
            $('#widget-order-form .testAddress').removeClass("hidden");
            $('#widget-order-form .testDate').removeClass("hidden");
            $('#widget-order-form .testStatus').removeClass("hidden");
            $('#widget-order-form .testResult').removeClass("hidden");
        }else{
            $('#widget-order-form .testAddress').addClass("hidden");
            $('#widget-order-form .testDate').addClass("hidden");
            $('#widget-order-form .testStatus').addClass("hidden");
            $('#widget-order-form .testResult').addClass("hidden");
        }
    });

    $('#widget-order-form select[name=portfolioID]').change(function(){
        $.ajax({
          url: 'apis/Kameo/load_portfolio.php',
          type: 'get',
          data: {"action": "retrieve", "ID": $('#widget-order-form select[name=portfolioID]').val()},
          success: function(response){
              console.log(response);
                if(response.response == 'error') {
                  console.log(response.message);
                }
                if(response.response == 'success'){
                    $('#widget-order-form input[name=brand]').val(response.brand);
                    $('#widget-order-form input[name=model]').val(response.model);
                    $('#widget-order-form select[name=frameType]').val(response.frameType);
                    $('#widget-order-form .commandBike').attr('src', "images_bikes/"+response.brand.toLowerCase().replace(/ /g, '-    ')+"_"+response.model.toLowerCase().replace(/ /g, '-')+"_"+response.frameType.toLowerCase()+".jpg");

                }
          }
        });

    })

}

function retrieve_command(ID){
    $.ajax({
      url: 'apis/Kameo/load_portfolio.php',
      type: 'get',
      data: {"action": "list"},
      success: function(response){
            if(response.response == 'error') {
              console.log(response.message);
            }
            if(response.response == 'success'){
                $('#widget-order-form select[name=portfolioID]').empty();
                var i=0;
                while(i<response.bikeNumber){
                    $('#widget-order-form select[name=portfolioID]').append('<option value='+response.bike[i].ID+'>'+response.bike[i].ID+' - '+response.bike[i].brand+' '+response.bike[i].model+' - '+response.bike[i].frameType+'</option>');
                    i++;
                }
            }
      }
    }).done(function(){

        $.ajax({
          url: 'apis/Kameo/orders_management.php',
          type: 'get',
          data: {"action": "retrieve", "ID": ID, "email": email},
          success: function(response){
            if(response.response == 'error') {
              console.log(response.message);
            }
            if(response.response == 'success'){
                $('#widget-order-form input[name=ID]').val(ID);
                $('#widget-order-form input[name=leasingPrice]').val(response.order.leasingPrice);
                $('#widget-order-form select[name=portfolioID]').val(response.order.portfolioID).attr('disabled', false);
                $('#widget-order-form input[name=brand]').val(response.order.brand).attr('disabled', false);
                $('#widget-order-form input[name=model]').val(response.order.model).attr('disabled', false);
                $('#widget-order-form select[name=frameType]').val(response.order.frameType).attr('disabled', false);
                $('#widget-order-form select[name=size]').val(response.order.size).attr('disabled', false);
                $('#widget-order-form select[name=status]').val(response.order.status).attr('disabled', false);
                $('#widget-order-form input[name=name]').val(response.order.name).attr('disabled', false);
                $('#widget-order-form input[name=firstName]').val(response.order.firstname).attr('disabled', false);
                $('#widget-order-form input[name=mail]').val(response.order.email).attr('disabled', false);
                $('#widget-order-form input[name=phone]').val(response.order.phone).attr('disabled', false);

                if(response.order.testBoolean=="Y"){
                    $('#widget-order-form input[name=testBoolean]').prop('checked', true);
                    $('#widget-order-form .testAddress').removeClass("hidden");
                    $('#widget-order-form input[name=testAddress]').val(response.order.testAddress);
                    $('#widget-order-form .testDate').removeClass("hidden");
                    $('#widget-order-form input[name=testDate]').val(response.order.testDate);
                    $('#widget-order-form .testStatus').removeClass("hidden");
                    $('#widget-order-form select[name=testStatus]').val(response.order.testStatus);
                    $('#widget-order-form .testResult').removeClass("hidden");
                    $('#widget-order-form textarea[name=testResult]').val(response.order.testResult);
                }else{
                    $('#widget-order-form input[name=testBoolean]').prop('checked', false);
                    $('#widget-order-form .testAddress').addClass("hidden");
                    $('#widget-order-form input[name=testAddress]').val("");
                    $('#widget-order-form .testDate').addClass("hidden");
                    $('#widget-order-form input[name=testDate]').val("");
                    $('#widget-order-form .testStatus').addClass("hidden");
                    $('#widget-order-form select[name=testStatus]').val("");
                    $('#widget-order-form .testResult').addClass("hidden");
                    $('#widget-order-form textarea[name=testResult]').val("");
                }
                $('#widget-order-form input[name=testDate]').val(response.order.testDate);
                $('#widget-order-form input[name=testAddress]').val(response.order.testAddress);
                $('#widget-order-form input[name=deliveryAddress]').val(response.order.deliveryAddress);
                $('#widget-order-form input[name=emailUser]').val(response.order.email);
                $('#widget-order-form .commandBike').attr('src', "images_bikes/"+response.order.brand.toLowerCase().replace(/ /g, '-')+"_"+response.order.model.toLowerCase().replace(/ /g, '-')+"_"+response.order.frameType.toLowerCase()+".jpg");

                if(response.order.estimatedDeliveryDate != null){
                    $('#widget-order-form input[name=deliveryDate]').val(response.order.estimatedDeliveryDate);
                }
            }
          }
        })
    })
}
