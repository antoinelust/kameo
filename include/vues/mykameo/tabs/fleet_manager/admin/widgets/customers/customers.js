//FleetManager: Gérer les clients | Displays the companies graph by calling get_companies_listing.php and creating it
function generateCompaniesGraphic(dateStart, dateEnd){

  var dateStartString=dateStart.getFullYear()+"-"+("0" + (dateStart.getMonth() + 1)).slice(-2)+"-"+("0" + dateStart.getDate()).slice(-2);
  var dateEndString=dateEnd.getFullYear()+"-"+("0" + (dateEnd.getMonth() + 1)).slice(-2)+"-"+("0" + dateEnd.getDate()).slice(-2);

  $.ajax({
    url: 'apis/Kameo/get_companies_listing.php',
    type: 'get',
    data: { "action": "graphic", "numberOfDays": "30", "dateStart": dateStartString, "dateEnd": dateEndString},
    success: function(response){
      if (response.response == 'error') {
		console.log(response.message);
	  }
	  else {
        var ctx = document.getElementById('myChart3').getContext('2d');
        if (myChart3 != undefined)
          myChart3.destroy();

        var presets=window.chartColors;

        var myChart3 = new Chart(ctx, {
          type: 'line',
          data: {
            datasets: [{
              label: 'Entreprises pas intéressées',
              borderColor: "#99111C",
              backgroundColor: "#f6856f",
              data: response.companiesNotInterested
            },{
              label: 'Entreprises en contact',
              borderColor: "#333333",
              backgroundColor: "#fcdb76",
              data: response.companiesContact
            },{
              label: 'Entreprises sous offre',
              borderColor: "#333333",
              backgroundColor: "#b6db4d",
              data: response.companiesOffer
            },{
              label: 'Entreprises sous offre signée',
              borderColor: "#333333",
              backgroundColor: "#96c220",
              data: response.companiesOfferSigned
            }],
            labels:response.dates
          },

          options: {
            scales: {
              yAxes: [{
                stacked: true,
                beginAtZero: true
              }]
            },
            elements: {
              line: { tension: 0 }
            }
          }
        });
        myChart3.update();
      }
    }
  });
}
function get_company_listing(type) {

  var filter=$('#companyListingFilter').html();

    var email= "<?php echo $user_data['EMAIL']; ?>";
    $.ajax({
      url: 'apis/Kameo/get_companies_listing.php',
      type: 'get',
      data: {"type": type, "filter": filter},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }


        if(response.response == 'success'){
          var dest="";
          var temp="<table id=\"test\" data-order='[[ 0, \"asc\" ]]' data-page-length='25' class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Clients:</h4><h4 class=\"en-inline text-green\">Clients:</h4><h4 class=\"nl-inline text-green\">Clients:</h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addClient\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un client</span></a><br/><thead><tr><th><span class=\"fr-inline\">Référence interne</span><span class=\"en-inline\">Internal reference</span><span class=\"nl-inline\">Internal reference</span></th><th><span class=\"fr-inline\">Client</span><span class=\"en-inline\">Client</span><span class=\"nl-inline\">Client</span></th><th><span class=\"fr-inline\"># vélos</span><span class=\"en-inline\"># bikes</span><span class=\"nl-inline\"># bikes</span></th><th>Rappeler ?</th><th>Mise à jour</th><th><span class=\"fr-inline\">Accès vélos</span><span class=\"en-inline\">Bike Access</span><span class=\"nl-inline\">Bike Access</span></th><th><span class=\"fr-inline\">Accès Bâtiments</span><span class=\"en-inline\">Building Access</span><span class=\"nl-inline\">Building Access</span></th><th>Type</th></tr></thead><tbody>";
          dest=dest.concat(temp);
          var i=0;

          while (i < response.companiesNumber){
            temp="<tr><td><a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+response.company[i].ID+"\">"+response.company[i].internalReference+"</a></td><td>"+response.company[i].companyName+"</td><td>"+response.company[i].companyBikeNumber+"</td>";
            dest=dest.concat(temp);


            var heuMaj=new Date(response.company[i].HEU_MAJ);
            var now=new Date();


            var difference= ((now.getTime()-heuMaj.getTime())/86400000).toFixed(0);

            if(response.company[i].type=='PROSPECT' && difference >=60){
                var rappeler="Y";
            }else{
                var rappeler="N";
            }

            var dest=dest.concat("<td>"+rappeler+"</td><td data-sort=\""+(new Date(response.company[i].HEU_MAJ)).getTime()+"\">"+response.company[i].HEU_MAJ.shortDate()+"</td>");



            if(response.company[i].bikeAccessStatus=="OK"){
              var temp="<td class=\"text-green\">"+response.company[i].bikeAccessStatus+"</td>";
            }else{
              var temp="<td class=\"text-red\">"+response.company[i].bikeAccessStatus+"</td>";
            }
            dest=dest.concat(temp);
            if(response.company[i].customerBuildingAccess=="OK"){
              var temp="<td class=\"text-green\">"+response.company[i].customerBuildingAccess+"</td>";
            }else{
              var temp="<td class=\"text-red\">"+response.company[i].customerBuildingAccess+"</td>";
            }
            dest=dest.concat(temp);




            dest=dest.concat("<td>"+response.company[i].type+"</td>");

            var temp="</tr>";
            dest=dest.concat(temp);
            i++;

          }
          var temp="</tobdy></table>";
          dest=dest.concat(temp);
          document.getElementById('companyListingSpan').innerHTML = dest;

          var classname = document.getElementsByClassName('internalReferenceCompany');
          for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener('click', function() {get_company_details(this.name,email, true)}, false);
          }
          var classname = document.getElementsByClassName('updateCompany');
          for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener('click', function() {construct_form_for_company_update(this.name)}, false);
          }
          displayLanguage();

            $('#test thead tr').clone(true).appendTo('#test thead');

            $('#test thead tr:eq(1) th').each(function(i){
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

            var table=$('#test').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                scrollX: true,
                  "columns": [
                    { "width": "100px" },
                    { "width": "100px" },
                    { "width": "50px" },
                    { "width": "50px" },
                    { "width": "50px" },
                    { "width": "50px" },
                    { "width": "50px" },
                    { "width": "50px" }                  
                  ]
            });
        }
      }
    })
}




function get_company_details(ID, email ,getCompanyContacts = false) {
  var internalReference;    

  $.ajax({
    url: 'apis/Kameo/get_company_details.php',
    type: 'post',
    data: {"ID": ID},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){
        $("#companyIdHidden").val(response.ID);
        $('#companyIdTemplate').val(response.ID);
        get_company_boxes(response.internalReference);
        if(getCompanyContacts == true){
          get_company_contacts(response.ID);
        }

        remove_contact_form(true);

        $('#widget-companyDetails-form input[name=ID]').val(response.ID);
        document.getElementById('companyName').value = response.companyName;
        document.getElementById('companyStreet').value = response.companyStreet;
        document.getElementById('companyZIPCode').value = response.companyZIPCode;
        document.getElementById('companyTown').value = response.companyTown;
        document.getElementById('companyVAT').value = response.companyVAT;
        document.getElementById('widget_companyDetails_internalReference').value=response.internalReference;
        internalReference=response.internalReference;
        $('#widget-companyDetails-form select[name=type]').val(response.type);
        $('#widget-companyDetails-form input[name=email_billing]').val(response.emailContactBilling);
        $('#widget-companyDetails-form input[name=firstNameContactBilling]').val(response.firstNameContactBilling);
        $('#widget-companyDetails-form input[name=lastNameContactBilling]').val(response.lastNameContactBilling);
        $('#widget-companyDetails-form input[name=phoneBilling]').val(response.phoneContactBilling);

        if(response.automaticBilling=="Y"){
          $('#widget-companyDetails-form input[name=billing]').prop( "checked", true );
        }else{
          $('#widget-companyDetails-form input[name=billing]').prop( "checked", false );
        }
        if(response.automaticStatistics=="Y"){
          $('#widget-companyDetails-form input[name=statistiques]').prop( "checked", true );
        }else{
          $('#widget-companyDetails-form input[name=statistiques]').prop( "checked", false );
        }
        if(response.assistance=='Y'){
          $("#widget-companyDetails-form input[name=assistance]").prop( "checked", true );
        }else{
          $("#widget-companyDetails-form input[name=assistance]").prop( "checked", false );
        }
        if(response.locking=='Y'){
          $("#widget-companyDetails-form input[name=locking]").prop( "checked", true );
        }else{
          $("#widget-companyDetails-form input[name=locking]").prop( "checked", false );
        }

        var i=0;
        var dest="<a class=\"button small green button-3d rounded icon-right addBikeAdmin\" data-target=\"#bikeManagement\" data-toggle=\"modal\" href=\"#\" name=\""+response.ID+"\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un vélo</span></a>";
        if(response.bikeNumber>0){
          var temp="<table id=\"bike_company_listing\" class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th scope=\"col\"><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th scope=\"col\"><span class=\"fr-inline\">Facturation automatique</span><span class=\"en-inline\">Automatic billing ?</span><span class=\"nl-inline\">Automatic billing ?</span></th><th>Début</th><th>Fin</th><th scope=\"col\"><span class=\"fr-inline\">Montant location</span><span class=\"en-inline\">Location Price</span><span class=\"nl-inline\">Location Price</span></th><th scope=\"col\">Accès aux bâtiments</th><th>Mise à jour</th><th></th></tr></thead><tbody>";
          dest=dest.concat(temp);
          while(i<response.bikeNumber){
            if(response.bike[i].contractType != "order"){
                if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractStart != null){
                    var contractStart="<span>"+response.bike[i].contractStart.shortDate()+"</span>";
                }else if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractStart == null){
                    var contractStart="<span class=\"text-red\">N/A</span>";
                }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractStart == null){
                    var contractStart="<span>N/A</span>";
                }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractStart != null){
                    var contractStart="<span class=\"text-red\">"+response.bike[i].contractStart.shortDate()+"</span>";
                }else{
                    var contractStart="<span class=\"text-red\">ERROR</span>";
                }
                if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractEnd != null){
                    var contractEnd="<span>"+response.bike[i].contractEnd.shortDate()+"</span>";
                }else if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractEnd == null){
                    var contractEnd="<span class=\"text-red\">N/A</span>";
                }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractEnd == null){
                    var contractEnd="<span>N/A</span>";
                }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractEnd != null){
                    var contractEnd="<span class=\"text-red\">"+response.bike[i].contractEnd.shortDate()+"</span>";
                }else{
                    var contractEnd="<span class=\"text-red\">ERROR</span>";
                }
                
                if(response.bike[i].frameNumber == null){
                    var frameNumber = "N/A "+response.bike[i].id;
                }else{
                    var frameNumber = response.bike[i].frameNumber;
                }


                var temp="<tr><td scope=\"row\">"+frameNumber+"</td><td>"+response.bike[i].model+"</td><td>"+response.bike[i].facturation+"</td><td>"+contractStart+"</td><td>"+contractEnd+"</td><td>"+response.bike[i].leasingPrice+"</td><td>";
                dest=dest.concat(temp);

                var j=0;
                while(j<response.bike[i].buildingNumber){
                    var temp=response.bike[i].building[j].buildingCode+"<br/>"
                    dest=dest.concat(temp);
                    j++;
                }
                if(response.bike[i].buildingNumber==0){
                    var temp="<span class=\"text-red\">Non-défini</span>";
                    dest=dest.concat(temp);
                }
                dest=dest.concat("<td data-sort=\""+(new Date(response.bike[i].heuMaj)).getTime()+"\">"+response.bike[i].heuMaj.shortDate()+"</td>");
                dest=dest.concat("<td><ins><a class=\"text-green text-green updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+response.bike[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>");
              }
            i++;
          }
          dest=dest.concat("</tbody></table>");
        }

        document.getElementById('companyBikes').innerHTML = dest;
          
        $('#bike_company_listing').DataTable({
            "searching": false,
            "paging": false
        });
          
          

        var i=0;
        var dest="";
        if(response.bikeNumber>0){
          var temp="<table id=\"ordered_bike_company_listing\" class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th scope=\"col\"><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th>Date commande</th><th>Date livraison</th><th scope=\"col\"><span class=\"fr-inline\">Numéro commande fournisseur</span></th><th></th></tr></thead><tbody>";
          dest=dest.concat(temp);
          while(i<response.bikeNumber){
              if(response.bike[i].contractType == "order")
              {                      
                  
                  
                if(response.bike[i].frameNumber == null){
                    var frameNumber = "N/A - "+response.bike[i].id;
                }else{
                    var frameNumber = response.bike[i].frameNumber;
                }
                if(response.bike[i].deliveryDate == null){
                    var deliveryDate = "N/A ";
                }else{
                    var deliveryDate = response.bike[i].deliveryDate.shortDate();
                }
                if(response.bike[i].bikeBuyingDate == null){
                    var bikeBuyingDate = "N/A ";
                }else{
                    var bikeBuyingDate = response.bike[i].bikeBuyingDate.shortDate();
                }                  

                var temp="<tr><td scope=\"row\">"+frameNumber+"</td><td>"+response.bike[i].model+"</td><td>"+bikeBuyingDate+"</td><td>"+deliveryDate+"</td><td>"+response.bike[i].orderNumber+"</td>";
                dest=dest.concat(temp);

                dest=dest.concat("<td><ins><a class=\"text-green text-green updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+response.bike[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>");
              }
              i++;
          }
          dest=dest.concat("</tbody></table>");
        }

        document.getElementById('companyBikesOrder').innerHTML = dest;
          
        $('#ordered_bike_company_listing').DataTable({
            "searching": false,
            "paging": false
        }
        );
          
          


        $('.updateBikeAdmin').click(function(){
          construct_form_for_bike_status_updateAdmin(this.name);
          $('#widget-bikeManagement-form input').attr('readonly', false);
          $('#widget-bikeManagement-form select').attr('readonly', false);
          $('.bikeManagementTitle').html('Modifier un vélo');
          $('.bikeManagementSend').removeClass('hidden');
        });

        $('.addBikeAdmin').click(function(){
          add_bike(this.name);
          $('#widget-bikeManagement-form input').attr('readonly', false);
          $('#widget-bikeManagement-form select').attr('readonly', false);
          $('.bikeManagementTitle').html('Ajouter un vélo');
          $('.bikeManagementSend').removeClass('hidden');
        });


        //Ajouter un bâtiment
        var dest="<a class=\"button small green button-3d rounded icon-right\" data-target=\"#addBuilding\" data-toggle=\"modal\" onclick=\"add_building('"+response.internalReference+"')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un bâtiment</span></a>";

        if(response.buildingNumber>0){
          var i=0;
          var temp="<table class=\"table\"><tbody><thead><tr><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Reference</span><span class=\"nl-inline\">Reference</span></th><th scope=\"col\"><span class=\"fr-inline\">Description</span><span class=\"en-inline\">Description</span><span class=\"nl-inline\">Description</span></th><th scope=\"col\"><span class=\"fr-inline\">Adresse</span><span class=\"en-inline\">Address</span><span class=\"nl-inline\">Address</span></th></tr></thead>";
          dest=dest.concat(temp);
          while(i<response.buildingNumber){
            var temp="<tr><td scope=\"row\">"+response.building[i].buildingReference+"</td><td>"+response.building[i].buildingFR+"</td><td>"+response.building[i].address+"</td></tr>";
            dest=dest.concat(temp);
            i++;
          }
          dest=dest.concat("</tbody></table>");
        }

        document.getElementById('companyBuildings').innerHTML = dest;


        //Ajouter une offre

        var dest="<a class=\"button small green button-3d rounded icon-right offerManagement addOffer\" name=\""+internalReference+"\" data-target=\"#offerManagement\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une offre</span></a>";
        dest+="<a class=\"button small green button-3d rounded icon-right offerManagement getTemplate\" name=\""+internalReference+"\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i>Nouveau Template Offre</span></a>";
        if((response.offerNumber + response.bikeContracts)>0){
          var i=0;
          var temp="<h5 class=\"text-green\">Contrats</h5><table class=\"table\"><tbody><thead><tr><th scope=\"col\"><span class=\"fr-inline\">ID</span><span class=\"en-inline\">ID</span><span class=\"nl-inline\">ID</span></th><th>PDF</th><th scope=\"col\"><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th scope=\"col\"><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th scope=\"col\"><span class=\"fr-inline\">Chance</span><span class=\"en-inline\">Chance</span><span class=\"nl-inline\">Chance</span></th><th>Montant</th><th>Debut</th><th>Fin</th><th>Statut</th><th></th></tr></thead>";
          dest=dest.concat(temp);
          while(i<response.bikeContracts){
            if(response.offer[i].description){
              var description=response.offer[i].description;
            }else{
              var description="N/A";
            }
            if(response.offer[i].probability){
              var probability=response.offer[i].probability;
            }else{
              var probability="N/A";
            }
            if(response.offer[i].amount){
              var amount=response.offer[i].amount;
            }else{
              var amount="N/A";
            }
            if(response.offer[i].start){
              var start=response.offer[i].start.shortDate();
            }else{
              var start="N/A";
            }
            if(response.offer[i].end){
              var end=response.offer[i].end.shortDate();
            }else{
              var end="N/A";
            }
            if(response.offer[i].status){
              var status=response.offer[i].status;
            }else{
              var status="N/A";
            }

            var temp="<tr><td>"+response.offer[i].id+"</td><td></td><td>Signé</td><td>"+description+"</td><td>"+probability+"</td><td>"+amount+"</td><td>"+start+"</td><td>"+end+"</td><td>"+status+"</td><td></td></tr>";
            dest=dest.concat(temp);
            i++;
          }

          while(i<(response.offerNumber + response.bikeContracts)){

            if(!response.offer[i].date){
              var date="?";
            }else{
              var date=response.offer[i].date.shortDate();
            }
            if(!response.offer[i].start){
              var start="?";
            }else{
              var start=response.offer[i].start.shortDate();
            }
            if(!response.offer[i].end){
              var end="?";
            }else{
              var end=response.offer[i].end.shortDate();
            }

            if(response.offer[i].type=="leasing"){
              var amount = response.offer[i].amount+" €/mois";
            }else{
              var amount = response.offer[i].amount+" €";
            }
            if(response.offer[i].status){
              var status=response.offer[i].status;
            }else{
              var status="N/A";
            }
              
            if(response.offer[i].file != '' && response.offer[i].file != null){
                var offerLink = 'offres/' + response.offer[i].file;
                var temp="<tr><td><a href=\"#\" class=\"retrieveOffer\" data-target=\"#offerManagement\" data-toggle=\"modal\" name=\""+response.offer[i].id+"\">"+response.offer[i].id+"</a></td><td><a href="+offerLink+" target=\"_blank\"><i class=\"fa fa-file-pdf-o\" aria-hidden=\"true\"></i></a></td><td>"+date+"</td><td>"+response.offer[i].title+"</td><td>"+response.offer[i].probability+" %</td><td>"+amount+"</td><td>"+start+"</td><td>"+end+"</td><td>"+status+"</td><td><ins><a class=\"text-green offerManagement updateOffer\" data-target=\"#offerManagement\" name=\""+response.offer[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
            }else{
                var temp="<tr><td><a href=\"#\" class=\"retrieveOffer\" data-target=\"#offerManagement\" data-toggle=\"modal\" name=\""+response.offer[i].id+"\">"+response.offer[i].id+"</a></td><td></td><td>"+date+"</td><td>"+response.offer[i].title+"</td><td>"+response.offer[i].probability+" %</td><td>"+amount+"</td><td>"+start+"</td><td>"+end+"</td><td>"+status+"</td><td><ins><a class=\"text-green offerManagement updateOffer\" data-target=\"#offerManagement\" name=\""+response.offer[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
            }
              
            dest=dest.concat(temp);
            i++;
          }
          dest=dest.concat("</tbody></table>");
        }
        document.getElementById('companyContracts').innerHTML = dest;
          
          
        var dest="<table class=\"table table-condensed\"><thead><tr><th>Type</th><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Date d'initiation</span><span class=\"en-inline\">Generation Date</span><span class=\"nl-inline\">Generation Date</span></th><th><span class=\"fr-inline\">Montant (HTVA)</span><span class=\"en-inline\">Amount (VAT ex.)</span><span class=\"nl-inline\">Amount (VAT ex.)</span></th><th><span class=\"fr-inline\">Communication</span><span class=\"en-inline\">Communication</span><span class=\"nl-inline\">Communication</span></th><th><span class=\"fr-inline\">Envoi ?</span><span class=\"en-inline\">Sent</span><span class=\"nl-inline\">Sent</span></th><th><span class=\"fr-inline\">Payée ?</span><span class=\"en-inline\">Paid ?</span><span class=\"nl-inline\">Paid ?</span></th><th><span class=\"fr-inline\">Limite de paiement</span><span class=\"en-inline\">Limit payment date</span><span class=\"nl-inline\">Limit payment date</span></th><th>Comptable ?</th><th></th></tr></thead><tbody>";

        var i=0;
        while (i < response.billNumber){
            if(response.bill[i].sentDate==null){
                var sendDate="N/A";
            }else{
                var sendDate=response.bill[i].sentDate.shortDate();
            }
            if(response.bill[i].paidDate==null){
                var paidDate="N/A";
            }else{
                var paidDate=response.bill[i].paidDate.shortDate();
            }
            if(response.bill[i].sent=="0"){
                var sent="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
            }else{
                var sent="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
            }
            if(response.bill[i].paid=="0"){
                var paid="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
            }else{
                var paid="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
            }

            if(response.bill[i].limitPaidDate && response.bill[i].paid=="0"){
                var dateNow=new Date();
                var dateLimit=new Date(response.bill[i].limitPaidDate);

                  let month = String(dateLimit.getMonth() + 1);
                  let day = String(dateLimit.getDate());
                  let year = String(dateLimit.getFullYear());

                  if (month.length < 2) month = '0' + month;
                  if (day.length < 2) day = '0' + day;


                if(dateNow>dateLimit){
                    var paidLimit="<span class=\"text-red\">"+day+"/"+month+"/"+year.substr(2,2)+"</span>";
                }else{
                    var paidLimit="<span>"+day+"/"+month+"/"+year.substr(2,2)+"</span>";
                }
            }else if(response.bill[i].paid=="0"){
                var paidLimit="<span class=\"text-red\">N/A</span>";
            }else{
                var paidLimit="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
            }



            if(response.bill[i].amountHTVA>0){
                var temp="<tr><td class=\"text-green\">IN</td>";
            }else if(response.bill[i].amountHTVA<0){
                var temp="<tr><td class=\"text-red\">OUT</td>";
            }else{
                var temp="<tr>";
            }
            dest=dest.concat(temp);

            if(response.bill[i].fileName){
                var temp="<td><a href=\"factures/"+response.bill[i].fileName+"\" target=\"_blank\">"+response.bill[i].ID+"</a></td>";
            }
            else{
                var temp="<td><a href=\"#\" class=\"text-red\">"+response.bill[i].ID+"</a></td>";
            }
            dest=dest.concat(temp);
            if(response.bill[i].amountHTVA>0){
                var temp="<td>"+response.bill[i].company+"</a></td>";
                dest=dest.concat(temp);
            }else if(response.bill[i].amountHTVA<0){
                var temp="<td>"+response.bill[i].beneficiaryCompany+"</a></td>";
                dest=dest.concat(temp);
            }
            var temp="<td>"+response.bill[i].date.shortDate()+"</td><td>"+Math.round(response.bill[i].amountHTVA)+" €</td><td>"+response.bill[i].communication+"</td>";
            dest=dest.concat(temp);

            if(sent=="Y"){
                var temp="<td class=\"text-green\">"+sendDate+"</td>";
            }else{
                var temp="<td class=\"text-red\">"+sent+"</td>";
            }
            dest=dest.concat(temp);

            if(paid=="Y"){
                var temp="<td class=\"text-green\">"+paidDate+"</td>";
            }else{
                var temp="<td class=\"text-red\">"+paid+"</td>";
            }
            dest=dest.concat(temp);


            dest=dest.concat("<td>"+paidLimit+"</td>");


            if(response.bill[i].communicationSentAccounting=="1"){
                var temp="<td class=\"text-green\">OK</td>";
            }else{
                var temp="<td class=\"text-red\">KO</td>";
            }
            dest=dest.concat(temp);

            temp="<td><ins><a class=\"text-green updateBillingStatus\" data-target=\"#updateBillingStatus\" name=\""+response.bill[i].ID+"\" data-toggle=\"modal\" href=\"#\">Update</a></ins></td>";
            dest=dest.concat(temp);

            dest=dest.concat("</tr>");
            i++;

        }
        var temp="</tbody></table>";
        dest=dest.concat(temp);
        document.getElementById('companyBills').innerHTML = dest;
        var classname = document.getElementsByClassName('updateBillingStatus');
        for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener('click', function() {construct_form_for_billing_status_update(this.name)}, false);
        }
          
          

        $(".retrieveOffer").click(function() {
          retrieve_offer(this.name, "retrieve");
        });

        $(".updateOffer").click(function() {
          retrieve_offer(this.name, "update");
        });
        $("body").on('click','.addOffer',function() {
          add_offer(this.name);
          $('.offerManagementSendButton').removeClass("hidden");
          $('.offerManagementSendButton').text("Ajouter")

        });




        //Ajouter un utilisateur
        var dest="<a class=\"button small green button-3d rounded icon-right addUser\" data-target=\"#addUser\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un Utilisateur</span></a>";
        if(response.userNumber>0){
          var i=0;
          var temp="<table class=\"table\"><tbody><thead><tr><th scope=\"col\"><span class=\"fr-inline\">Nom</span><span class=\"en-inline\">Name</span><span class=\"nl-inline\">Name</span></th><th scope=\"col\"><span class=\"fr-inline\">Prénom</span><span class=\"en-inline\">First Name</span><span class=\"nl-inline\">First Name</span></th><th scope=\"col\"><span class=\"fr-inline\">E-mail</span><span class=\"en-inline\">E-Mail</span><span class=\"nl-inline\">E-Mail</span></th></tr></thead>";
          dest=dest.concat(temp);
          while(i<response.userNumber){
            var temp="<tr><td scope=\"row\">"+response.user[i].name+"</td><td>"+response.user[i].firstName+"</td><td>"+response.user[i].email+"</td></tr>";
            dest=dest.concat(temp);
            i++;
          }
          dest=dest.concat("</tbody></table>");
        }
        document.getElementById('companyUsers').innerHTML = dest;


        $('.addUser').click(function(){
          $('#widget-addUser-form input[name=company]').val(response.internalReference);


          var company=response.internalReference;

          $.ajax({
            url: 'apis/Kameo/get_building_listing.php',
            type: 'post',
            data: { "company": response.internalReference},
            success: function(response){
              if(response.response == 'error') {
                console.log(response.message);
              }
              if(response.response == 'success'){
                var i=0;
                var dest="";
                while (i < response.buildingNumber){
                  temp="<input type=\"checkbox\" name=\"buildingAccess[]\" checked value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"<br>";
                  dest=dest.concat(temp);
                  i++;

                }
                document.getElementById('buildingCreateUser').innerHTML = dest;

                $.ajax({
                  url: 'apis/Kameo/get_bikes_listing.php',
                  type: 'post',
                  data: { "company": company, "admin": "N"},
                  success: function(response){
                    if(response.response == 'error') {
                      console.log(response.message);
                    }
                    if(response.response == 'success'){
                      var i=0;
                      var dest="";
                      while (i < response.bikeNumber){
                        temp="<input type=\"checkbox\" name=\"bikeAccess[]\" checked value=\""+response.bike[i].id+"\">"+response.bike[i].frameNumber+" "+response.bike[i].model+"<br>";
                        dest=dest.concat(temp);
                        i++;

                      }
                      document.getElementById('bikeCreateUser').innerHTML = dest;
                      document.getElementById('confirmAddUser').innerHTML="<button class=\"fr button small green button-3d rounded icon-left\" onclick=\"confirm_add_user()\">\
                      <i class=\"fa fa-paper-plane\">\
                      </i>\
                      Confirmer\
                      </button>";

                    }
                  }
                })
              }
            }
          })
        });

        displayLanguage();
      }
    },error: function(response){

      console.log(response);
    }
  }).done(function(){
    $.ajax({
      url: 'apis/Kameo/action_company.php',
      type: 'get',
      data: { "company": internalReference, "user": email},
      success: function(response){
        if (response.response == 'error') {
          console.log(response.message);
        } else{


          var dest="<a href=\"#\" data-target=\"#taskManagement\" name=\""+internalReference+"\" data-toggle=\"modal\" class=\"button small green button-3d rounded icon-right addTask\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une action</span></a>";

          if(response.actionNumber>0){
            var i=0;
            var temp="<table class=\"table table-condensed\"><tbody><thead><tr><th>ID</th><th><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th>Type</th><th><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th><span class=\"fr-inline\">Owner</span><span class=\"en-inline\">Owner</span><span class=\"nl-inline\">Owner</span></th><th><span class=\"fr-inline\">Statut</span><span class=\"en-inline\">Status</span><span class=\"nl-inline\">Status</span></th><th></th></tr></thead> ";
            dest=dest.concat(temp);
            while(i<response.actionNumber){
              if(!(response.action[i].date_reminder)){
                $date_reminder="N/A"
              }else{
                $date_reminder=response.action[i].date_reminder.substring(0,10);
              }
              var temp="<tr><td><a href=\"#\" class=\"retrieveTask\" data-target=\"#taskManagement\" data-toggle=\"modal\" name=\""+response.action[i].id+"\">"+response.action[i].id+"</a></td><td>"+response.action[i].date.substring(0,10)+"</td><td>"+response.action[i].type+"</td><td>"+response.action[i].title+"</td><td>"+response.action[i].ownerFirstName+" "+response.action[i].ownerName+"</td><td>"+response.action[i].status+"</td><td><ins><a class=\"text-green updateAction\" data-target=\"#updateAction\" name=\""+response.action[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
              dest=dest.concat(temp);
              i++;
            }
            dest=dest.concat("</tbody></table>");
          }

          $('#action_company_log').html(dest);



          $(".retrieveTask").click(function() {
            retrieve_task(this.name, "retrieve");
            $('.taskManagementSendButton').addClass("hidden");


          });

          $(".updateTask").click(function() {
            update_task(this.name, "update");
          });
          $(".addTask").click(function() {
            add_task(this.name);
            $('.taskManagementSendButton').removeClass("hidden");
            $('.taskManagementSendButton').text("Ajouter")

          });



          displayLanguage();

          var classname = document.getElementsByClassName('updateAction');
          for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener('click', function() {construct_form_for_action_update(this.name)}, false);
          }
          list_kameobikes_member();


        }

      }
    })
  })
}

//Suppression d'une offre Pdf
$('body').on('click', '.deletePdfOffer', function(e){
  //empèche le comportement normal du lien
  e.preventDefault();
  id = $(this).parents('tr').find('td:first').html();
  file = $(this).parents('tr').find('td a').attr('href');
  that = $(this);
  if(confirm('Êtes-vous sur de vouloir supprimer ce PDF ? Cette action est irréversible.')){
    $.ajax({
      url: 'apis/Kameo/delete_pdf_offer.php',
      method: 'post',
      data: {'id' : id,
             'file' : file
    },
    success: function(response){
      if (response.response == true) {
        $(that).parents('tr').slideUp('',function(){
          $(this).remove();
        });
      } else{
        console.log(response);
      }
    }
  });

}

});


function list_contracts_offers(company) {
    $.ajax({
        url: 'apis/Kameo/offer_management.php',
        type: 'get',
        data: { "company": company, action: "retrieve"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var i=0;
                var dest="";
                var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Contrats signés :</h4><h4 class=\"en-inline text-green\">Contracts:</h4><h4 class=\"nl-inline text-green\">Contracts:</h4><br/><br/><div class=\"seperator seperator-small visible-xs\"></div><thead><tr><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Description</span><span class=\"en-inline\">Description</span><span class=\"nl-inline\">Description</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Debut</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Fin</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th></tr></thead>";
                dest=dest.concat(temp);
                while (i < response.contractsNumber){
                    if(response.contract[i].start!=null){
                        var contract_start=response.contract[i].start.shortDate();
                    }else{
                        var contract_start="<span class=\"text-red\">N/A</span>";
                    }
                    if(response.contract[i].end!=null){
                        var contract_end=response.contract[i].end.shortDate();
                    }else{
                        var contract_end="<span class=\"text-red\">N/A</span>";
                    }

                    var temp="<tr><td><a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+response.contract[i].companyID+"\">"+response.contract[i].company+"</a></td><td>"+response.contract[i].description+"</td><td>"+Math.round(response.contract[i].amount)+" €/mois</td><td>"+contract_start+"</td><td>"+contract_end+"</td></tr>";
                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tobdy></table>";
                dest=dest.concat(temp);
                
                

                var temp="<p>Valeur actuelle des contrat en cours : <strong>"+Math.round(response.sumContractsCurrent)+" €/mois</strong></p>";
                dest=dest.concat(temp);

                document.getElementById('contractsListingSpan').innerHTML = dest;
                
                var classname = document.getElementsByClassName('internalReferenceCompany');
                for (var i = 0; i < classname.length; i++) {
                    classname[i].addEventListener('click', function() {get_company_details(this.name,email, true)}, false);
                }
                



                var i=0;
                var dest="";
                var temp="<h4 class=\"fr-inline text-green\">Offres en cours :</h4><h4 class=\"en-inline text-green\">Offers:</h4><h4 class=\"nl-inline text-green\">Offers:</h4><br/><br/><div class=\"seperator seperator-small visible-xs\"></div><table class=\"table table-condensed\"><tbody><thead><tr><th>ID</th><th>PDF</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th>Type</th><th><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Debut</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Fin</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th>Probabilité</th><th></th></tr></thead>";
                dest=dest.concat(temp);
                while (i < response.offersNumber){
                    if(response.offer[i].start!=null){
                        var offer_start=response.offer[i].start.shortDate();
                    }else{
                        var offer_start="<span class=\"text-red\">N/A</span>";
                    }
                    if(response.offer[i].end!=null){
                        var offer_end=response.offer[i].end.shortDate();
                    }else{
                        var offer_end="<span class=\"text-red\">N/A</span>";
                    }

                    if(response.offer[i].type=="leasing"){
                        var amount=Math.round(response.offer[i].amount)+ "€/mois";
                    }else{
                        var amount=Math.round(response.offer[i].amount)+ "€";
                    }

                    if(response.offer[i].amount==0){
                        var amount="<span class=\"text-red\">"+amount+"</span>";
                    }

                    if(response.offer[i].type=="leasing"){
                        var type="Leasing";
                    }else if(response.offer[i].type=="achat"){
                        var type="Achat";
                    }

                    if(response.offer[i].probability==0 || response.offer[i].probability==0){
                        var probability="<span class=\"text-red\">"+response.offer[i].probability+" %</span>";
                    }else{
                        var probability="<span>"+response.offer[i].probability+" %</span>";
                    }
                    
                    if(response.offer[i].file != '' && response.offer[i].file != null ){
                        var offerLink = 'offres/' + response.offer[i].file;

                        var temp="<tr><td><a href=\"#\" class=\"retrieveOffer\" data-target=\"#offerManagement\" data-toggle=\"modal\" name=\""+response.offer[i].id+"\">"+response.offer[i].id+"</a></td><td><a href="+offerLink+" target=\"_blank\"><i class=\"fa fa-file-pdf-o\" aria-hidden=\"true\"></i></a></td><td>"+response.offer[i].company+"</td><td>"+type+"</td><td>"+response.offer[i].title+"</td><td>"+amount+" </td><td>"+offer_start+"</td><td>"+offer_end+"</td><td>"+probability+"</td><td><ins><a class=\"text-green offerManagement updateOffer\" data-target=\"#offerManagement\" name=\""+response.offer[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
                    }else{
                        var temp="<tr><td><a href=\"#\" class=\"retrieveOffer\" data-target=\"#offerManagement\" data-toggle=\"modal\" name=\""+response.offer[i].id+"\">"+response.offer[i].id+"</a></td><td></td><td>"+response.offer[i].company+"</td><td>"+type+"</td><td>"+response.offer[i].title+"</td><td>"+amount+" </td><td>"+offer_start+"</td><td>"+offer_end+"</td><td>"+probability+"</td><td><ins><a class=\"text-green offerManagement updateOffer\" data-target=\"#offerManagement\" name=\""+response.offer[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
                    }



                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tobdy></table>";
                dest=dest.concat(temp);
                document.getElementById('offersListingSpan').innerHTML = dest;

                var i=0;
                var dest="";
                var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Coûts:</h4><h4 class=\"en-inline text-green\">Costs:</h4><h4 class=\"nl-inline text-green\">Costs:</h4><br/><br/><a class=\"button small green button-3d rounded icon-right addCost\" data-target=\"#costsManagement\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un coût</span></a><div class=\"seperator seperator-small visible-xs\"></div><tbody><thead><tr><th>ID</th><th><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Debut</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Fin</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th>Type</th><th></th></tr></thead>";
                dest=dest.concat(temp);
                while (i < response.costsNumber){
                    if(response.cost[i].start!=null){
                        var cost_start=response.cost[i].start.shortDate();
                    }else{
                        var cost="N/A";
                    }
                    if(response.cost[i].end!=null){
                        var cost_end=response.cost[i].end.shortDate();
                    }else{
                        var cost_end="N/A";
                    }

                    if(response.cost[i].type=="monthly"){
                        var amount=Math.round(response.cost[i].amount)+ "€/mois";
                    }else{
                        var amount=Math.round(response.cost[i].amount)+ "€";
                    }
                    var temp="<tr><td><a href=\"#\" class=\"retrieveCost\" data-target=\"#costsManagement\" data-toggle=\"modal\" name=\""+response.cost[i].id+"\">"+response.cost[i].id+"</a></td><td>"+response.cost[i].title+"</td><td>"+amount+" </td><td>"+cost_start+"</td><td>"+cost_end+"</td><td><ins><a class=\"text-green costsManagement updateCost\" data-target=\"#costsManagement\" name=\""+response.cost[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";


                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tobdy></table>";
                dest=dest.concat(temp);
                document.getElementById('costsListingSpan').innerHTML = dest;

                $(".retrieveOffer").click(function() {
                    retrieve_offer(this.name, "retrieve");
                    $('.offerManagementTitle').text("Consulter une offre");
                    $('.offerManagementSendButton').addClass("hidden");

                });
                $(".updateOffer").click(function() {
                    retrieve_offer(this.name, "update");
                    $('.offerManagementTitle').text("Mettre à jour une offre");
                    $('.offerManagementSendButton').removeClass("hidden");
                    $('.offerManagementSendButton').text("Mettre à jour")

                });


                $(".addCost").click(function() {
                    $('#widget-costsManagement-form input').attr("readonly", false);
                    $('#widget-costsManagement-form textarea').attr("readonly", false);
                    $('#widget-costsManagement-form select').attr("readonly", false);
                    $('.costManagementTitle').text("Ajouter un coût");
                    $('.costManagementSendButton').removeClass("hidden");
                    document.getElementById('widget-costsManagement-form').reset();
                    $('.costManagementSendButton').text("Ajouter")

                });
                $(".retrieveCost").click(function() {
                    retrieve_cost(this.name, "retrieve");
                    $('.costManagementTitle').text("Consulter un coût");
                    $('.costManagementSendButton').addClass("hidden");
                });
                $(".updateCost").click(function() {
                    retrieve_cost(this.name, "update");
                    $('.costManagementTitle').text("Mettre à jour un coût");

                    $('.costManagementSendButton').removeClass("hidden");
                    $('.costManagementSendButton').text("Mettre à jour")

                });


                displayLanguage();

            }
        }
    })





    $.ajax({
        url: 'apis/Kameo/offer_management.php',
        type: 'get',
        data: { "graphics": "Y", action: "retrieve"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var threeYearsFromNow = new Date();
                threeYearsFromNow.setFullYear(threeYearsFromNow.getFullYear() + 1);
                var maxXAxis=threeYearsFromNow.toISOString().split('T')[0];

                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        datasets: [{
                            label: 'Contrats signés',
                            borderColor: 'rgba(44, 132, 109, 0.5)',
                            backgroundColor:'rgba(44, 132, 109, 0)',
                            data: response.arrayContracts
                        },{
                            label: 'Offres',
                            borderColor: 'rgba(145, 145, 145, 0.5)',
                            backgroundColor:'rgba(145, 145, 145, 0)',
                            data: response.arrayOffers
                        },{
                            label: 'Chiffre d\'affaire',
                            borderColor: 'rgba(60, 179, 149, 0.5)',
                            backgroundColor:'rgba(60, 179, 149, 0)',
                            data: response.totalIN
                        },{
                            label: 'Frais',
                            borderColor: 'rgba(176, 0, 0, 0.5)',
                            backgroundColor:'rgba(176, 0, 0, 0)',
                            data: response.arrayCosts
                        },{
                            label: 'Cash flow',
                            borderColor: 'rgba(60, 179, 149, 0.5)',
                            backgroundColor:'rgba(60, 179, 149, 0.5)',
                            data: response.arrayFreeCashFlow
                        }],
                    labels: response.arrayDates

                    },

                    options: {
                        scales: {
                            xAxes:[{
                                ticks:{
                                    max: "2020-12-19"
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        },
                        elements: {
                            line: {
                                tension: 0
                            }
                        }

                    }
                });

            }
        }
    })


}



function retrieve_offer(ID, action){
    $.ajax({
        url: 'apis/Kameo/offer_management.php',
        type: 'get',
        data: {"ID": ID, "action": "retrieve"},
        success: function(response){
            $('#offerManagementPDF').attr('data','');                    
            
            
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){

                if(action=="retrieve"){
                    $('#widget-offerManagement-form input').attr("readonly", true);
                    $('#widget-offerManagement-form textarea').attr("readonly", true);
                    $('#widget-offerManagement-form select').attr("readonly", true);
                }else{
                    $('#widget-offerManagement-form input').attr("readonly", false);
                    $('#widget-offerManagement-form textarea').attr("readonly", false);
                    $('#widget-offerManagement-form select').attr("readonly", false);

                }


                $('#widget-offerManagement-form input[name=title]').val(response.title);
                $('#widget-offerManagement-form textarea[name=description]').val(response.description);
                $('#widget-offerManagement-form select[name=type]').val(response.type);
                $('#widget-offerManagement-form select[name=status]').val(response.status);
                $('#widget-offerManagement-form input[name=margin]').val(response.margin);
                $('#widget-offerManagement-form input[name=probability]').val(response.probability);
                $('#widget-offerManagement-form input[name=company]').val(response.company);
                $('#widget-offerManagement-form input[name=action]').val(action);
                $('#widget-offerManagement-form input[name=ID]').val(ID);
                
                
                $('#thickBoxProductLists').empty();
                var i=0;
                if(response.itemsNumber>0){
                    console.log(response);
                    while(i<response.itemsNumber){
                        if(response.item[i].type == "box"){
                            $("#offerManagementDetails").append('<li>1 borne '+response.item[i].model+' au prix de '+response.item[i].locationPrice+' €/mois et un coût d\'installation de '+response.item[i].installationPrice+' €</a></li>');
                        }else{
                            $("#offerManagementDetails").append('<li>1 vélo '+response.item[i].brand+' '+response.item[i].model+' au prix de '+response.item[i].locationPrice+' €/mois</a></li>');
                        }
                        i++;
                    }
                }else{
                    
                }
                
                if($("#widget-offerManagement-form select[name=type]").val()=="achat"){
                    $("#widget-offerManagement-form input[name=start]").attr("readonly", true);
                    $("#widget-offerManagement-form input[name=end]").attr("readonly", true);
                    $("#widget-offerManagement-form input[name=start]").val("");
                    $("#widget-offerManagement-form input[name=end]").val("");

                }else{
                    if(action!="retrieve"){
                        $("#widget-offerManagement-form input[name=start]").attr("readonly", false);
                        $("#widget-offerManagement-form input[name=end]").attr("readonly", false);
                    }

                    if(response.date){
                        $('#widget-offerManagement-form input[name=date]').val(response.date.substring(0,10));
                    }else{
                        $('#widget-offerManagement-form input[name=date]').val("");
                    }
                    if(response.start){
                        $('#widget-offerManagement-form input[name=start]').val(response.start.substring(0,10));
                    }else{
                        $('#widget-offerManagement-form input[name=start]').val("");
                    }
                    if(response.end){
                        $('#widget-offerManagement-form input[name=end]').val(response.end.substring(0,10));
                    }else{
                        $('#widget-offerManagement-form input[name=end]').val("");
                    }
                }

                if(response.amount){
                    $('#widget-offerManagement-form input[name=amount]').val(response.amount);
                }
                
                $('#offerManagement').on('shown.bs.modal', function () {
                    if(response.file != null && response.file != ''){
                        $('.offerManagementPDF').removeClass('hidden');
                        $('#offerManagementPDF').attr('data','offres/'+response.file+'.pdf');                    
                    }else{
                        $('.offerManagementPDF').addClass('hidden');
                        $('#offerManagementPDF').attr('data',"");                    
                    }
                })                
            }
        }
    })
}
//Module gérer les clients ==> id d'un client ==> ajouter une offre
function add_offer(company){
$('#companyHiddenOffer').val(company);
$('#widget-offerManagement-form select[name=type]').val("leasing");
$('#widget-offerManagement-form input[name=action]').val("add");
$('#widget-offerManagement-form input').attr("readonly", false);
$('#widget-offerManagement-form textarea').attr("readonly", false);
$('#widget-offerManagement-form select').attr("readonly", false);
document.getElementById('widget-offerManagement-form').reset();
}

//Module gérer les clients ==> un client ==> modifier un contact
function edit_contact(contact){
return $.ajax({
  url: 'apis/Kameo/edit_company_contact.php',
  method: 'post',
  data: {
	'id': $(contact).find('.contactIdHidden').val(),
	'contactEmail':$(contact).find('.emailContact').val(),
	'firstName': $(contact).find('.firstName').val(),
	'lastName': $(contact).find('.lastName').val(),
	'phone': $(contact).find('.phone').val(),
	'function': $(contact).find('.fonction').val(),
	'bikesStats': $(contact).find('.bikesStats').prop('checked'),
	'companyId': $('#companyIdHidden').val(),
	'email': email
  },
  success: function(response){
  }
});
}

//Module gérer les clients ==> un client ==> supprimer un de la base de donnée, ne touche pas le front end contact
function delete_contact(contact, id){
return $.ajax({
  url: 'apis/Kameo/delete_company_contact.php',
  method: 'post',
  data: {
	'id' : id
  },
  success: function(response){
  }
});
}

//Module gérer les clients ==> un client ==> list les contacts
function get_company_contacts(ID){
$.ajax({
  url: 'apis/Kameo/get_company_contact.php',
  method: 'post',
  data: { 'ID' : ID },
  success: function(response){
	initialize_company_contacts();
	var contactContent = `
	<table class="table contactsTable">
	<thead>
	<tr>
	<th><label class="fr">Email: </label><label class="en">Email: </label><label class="nl">Email: </label></th>
	<th><label class="fr">Nom: </label><label class="en">Lastname: </label><label class="nl">Lastname: </label></th>
	<th><label class="fr">Prénom: </label><label class="en">Firstname: </label><label class="nl">Firstname: </label></th>
	<th><label class="fr">Téléphone: </label><label class="en">Phone: </label><label class="nl">Phone: </label></th>
	<th><label class="fr">Fonction: </label><label class="en">Function: </label><label class="nl">Function: </label></th>
	<th><label class="fr">Statistiques vélos: </label><label class="en">Bikes stats: </label><label class="nl">Bikes stats: </label></th>
	<th></th>
	<th></th>
	</tr>
	</thead>
	<tbody>`;
	nbContacts = response.length;
	for (var i = 0; i < response.length; i++) {
	  var contactId = (response[i].contactId != undefined) ? response[i].contactId : '';
	  var email = (response[i].emailContact != undefined) ? response[i].emailContact : '';
	  var lastName = (response[i].lastNameContact != undefined) ? response[i].lastNameContact : '';
	  var firstName = (response[i].firstNameContact != undefined) ? response[i].firstNameContact : '';
	  var phone = (response[i].phone != undefined) ? response[i].phone : '';
	  var fonction = (response[i].fonction != undefined) ? response[i].fonction : '';
	  var bikesStatsChecked = "";
	  if (response[i].bikesStats == "Y") {
		bikesStatsChecked = "checked";
	  }
	  contactContent += `
	  <tr class="form-group">
	  <td>
	  <input type="text" class="form-control required emailContact" readonly="true"  name="contactEmail`+response[i].contactId+`" id="contactEmail`+response[i].contactId+`" value="`+email+`" required/>
	  </td>
	  <td>
	  <input type="text" class="form-control required lastName" readonly="true"  name="contactNom`+response[i].contactId+`" id="contactNom`+response[i].contactId+`" value="`+lastName+`" required/>
	  </td>
	  <td>
	  <input type="text" class="form-control required firstName" readonly="true" name="contactPrenom`+response[i].contactId+`" id="contactPrenom`+response[i].contactId+`" value="`+firstName+`" required/>
	  </td>
	  <td>
	  <input type="tel" class="form-control phone" readonly="true"  name="contactPhone`+response[i].contactId+`" id="contactPhone`+response[i].contactId+`" value="`+phone+`"/>
	  </td>
	  <td>
	  <input type="text" class="form-control fonction" readonly="true"  name="contactFunction`+response[i].contactId+`" id="contactFunction`+response[i].contactId+`" value="`+fonction+`"/>
	  </td>
	  <td>
	  <input type="checkbox" class="form-control bikesStats" readonly="true"  name="contactBikesStats`+response[i].contactId+`" id="contactBikesStats`+response[i].contactId+`" value="bikesStats" `+bikesStatsChecked+`/>
	  </td>
	  <td>
	  <button class="modify button small green button-3d rounded icon-right glyphicon glyphicon-pencil" type="button"></button>
	  </td>
	  <td>
	  <button class="delete button small red button-3d rounded icon-right glyphicon glyphicon-remove" type="button"></button>
	  </td>
	  <input type="hidden" class="contactIdHidden" name="contactId`+response[i].contactId+`" id="contactId`+response[i].contactId+`" value="`+contactId+`" />
	  </tr>`;
	}
	contactContent += "</tbody></table>";
	$('.clientContactZone').append(contactContent);
  }
});

}

//Module gérer les clients ==> ajouter un batiment à un client
  function add_building(company){
    $.ajax({
      url: 'apis/Kameo/get_bikes_listing.php',
      type: 'post',
      data: { "company": company},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          var i=0;
          var dest="";
          while (i < response.bikeNumber){
            temp="<input type=\"checkbox\" name=\"bikeAccess[]\" checked value=\""+response.bike[i].frameNumber+"\">"+response.bike[i].frameNumber+" - "+response.bike[i].model+"<br>";
            dest=dest.concat(temp);
            i++;

          }
          document.getElementById('add_bikeListing').innerHTML = dest;
        }
      }
    })
    $.ajax({
      url: 'apis/Kameo/get_users_listing.php',
      type: 'post',
      data: { "company": company},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          var i=0;
          var dest="";
          while (i < response.usersNumber){
            temp="<input type=\"checkbox\" name=\"userAccess[]\" checked value=\""+response.user[i].email+"\">"+response.user[i].firstName+" - "+response.user[i].name+"<br>";
            dest=dest.concat(temp);
            i++;

          }
          document.getElementById('addBuilding_usersListing').innerHTML = dest;
        }
      }
    })
    document.getElementById('widget-addBuilding-form-company').value = company;
  }

//Module gérer les clients ==> un client ==> reset contact
function initialize_company_contacts (){
$('.clientContactZone').html('');
}

//Module gérer les clients ==> un client ==> Modifie le front end quand tu delete un contact
function remove_contact_form(removeContent = false){
//retrait de l ajout
$('.contactAddIteration').fadeOut();
//ajout du statut disabled des input
$('.contactAddIteration').find('input').each(function(){
  $(this).prop('disabled', true);
  if (removeContent) {
	$(this).val('');
  }
});
$('.removeContact').addClass('glyphicon-plus').addClass('green').addClass('addContact').removeClass('glyphicon-minus').removeClass('red').removeClass('removeContact');
}
