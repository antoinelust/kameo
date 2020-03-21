function get_company_details(ID, email ,getCompanyContacts = false) {
  var internalReference;
  $.ajax({
    url: 'include/get_company_details.php',
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
          var temp="<table class=\"table table-condensed\"><thead><tr><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th scope=\"col\"><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th scope=\"col\"><span class=\"fr-inline\">Facturation automatique</span><span class=\"en-inline\">Automatic billing ?</span><span class=\"nl-inline\">Automatic billing ?</span></th><th>Début</th><th>Fin</th><th scope=\"col\"><span class=\"fr-inline\">Montant leasing</span><span class=\"en-inline\">Leasing Price</span><span class=\"nl-inline\">Leasing Price</span></th><th scope=\"col\">Accès aux bâtiments</th></tr></thead><tbody>";
          dest=dest.concat(temp);
          while(i<response.bikeNumber){

            if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractStart != null){
              var contractStart="<span>"+response.bike[i].contractStart.substr(0,10)+"</span>";
            }else if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractStart == null){
              var contractStart="<span class=\"text-red\">N/A</span>";
            }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractStart == null){
              var contractStart="<span>N/A</span>";
            }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractStart != null){
              var contractStart="<span class=\"text-red\">"+response.bike[i].contractStart.substr(0,10)+"</span>";
            }else{
              var contractStart="<span class=\"text-red\">ERROR</span>";
            }
            if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractEnd != null){
              var contractEnd="<span>"+response.bike[i].contractEnd.substr(0,10)+"</span>";
            }else if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractEnd == null){
              var contractEnd="<span class=\"text-red\">N/A</span>";
            }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractEnd == null){
              var contractEnd="<span>N/A</span>";
            }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractEnd != null){
              var contractEnd="<span class=\"text-red\">"+response.bike[i].contractEnd.substr(0,10)+"</span>";
            }else{
              var contractEnd="<span class=\"text-red\">ERROR</span>";
            }


            var temp="<tr><td scope=\"row\">"+response.bike[i].frameNumber+"</td><td>"+response.bike[i].model+"</td><td>"+response.bike[i].facturation+"</td><td>"+contractStart+"</td><td>"+contractEnd+"</td><td>"+response.bike[i].leasingPrice+"</td><td>";
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
            dest=dest.concat("</td><td><ins><a class=\"text-green text-green updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+response.bike[i].frameNumber+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>");
            i++;
          }
          dest=dest.concat("</tbody></table>");
        }

        document.getElementById('companyBikes').innerHTML = dest;


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
        dest+="<a class=\"button small green button-3d rounded icon-right offerManagement getTemplate\" name=\""+internalReference+"\" data-target=\"#template\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i>Nouveau Template Offre</span></a>";
        if((response.offerNumber + response.bikeContracts)>0){
          var i=0;
          var temp="<h5 class=\"text-green\">Contrats</h5><table class=\"table\"><tbody><thead><tr><th scope=\"col\"><span class=\"fr-inline\">ID</span><span class=\"en-inline\">ID</span><span class=\"nl-inline\">ID</span></th><th scope=\"col\"><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th scope=\"col\"><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th scope=\"col\"><span class=\"fr-inline\">Chance</span><span class=\"en-inline\">Chance</span><span class=\"nl-inline\">Chance</span></th><th>Montant</th><th>Debut</th><th>Fin</th><th>Statut</th><th></th></tr></thead>";
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
              var start=response.offer[i].start.substr(0,10);
            }else{
              var start="N/A";
            }
            if(response.offer[i].end){
              var end=response.offer[i].end.substr(0,10);
            }else{
              var end="N/A";
            }
            if(response.offer[i].status){
              var status=response.offer[i].status;
            }else{
              var status="N/A";
            }

            var temp="<tr><td>"+response.offer[i].id+"</td><td>Signé</td><td>"+description+"</td><td>"+probability+"</td><td>"+amount+"</td><td>"+start+"</td><td>"+end+"</td><td>"+status+"</td><td></td></tr>";
            dest=dest.concat(temp);
            i++;
          }

          while(i<(response.offerNumber + response.bikeContracts)){

            if(!response.offer[i].date){
              var date="?";
            }else{
              var date=response.offer[i].date.substr(0,10);
            }
            if(!response.offer[i].start){
              var start="?";
            }else{
              var start=response.offer[i].start.substr(0,10);
            }
            if(!response.offer[i].end){
              var end="?";
            }else{
              var end=response.offer[i].end.substr(0,10);
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


            var temp="<tr><td><a href=\"#\" class=\"retrieveOffer\" data-target=\"#offerManagement\" data-toggle=\"modal\" name=\""+response.offer[i].id+"\">"+response.offer[i].id+"</a></td><td>"+date+"</td><td>"+response.offer[i].title+"</td><td>"+response.offer[i].probability+" %</td><td>"+amount+"</td><td>"+start+"</td><td>"+end+"</td><td>"+status+"</td><td><ins><a class=\"text-green offerManagement updateOffer\" data-target=\"#offerManagement\" name=\""+response.offer[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
            dest=dest.concat(temp);
            i++;
          }
          dest=dest.concat("</tbody></table>");
        }
        document.getElementById('companyContracts').innerHTML = dest;

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

        //affichage du tableau des PDF
        if (response.companyOffersNumber > 0) {
          var dest=`
          <h5 class=\"text-green\">Offres PDF</h5>
          <table class="table table-condensed">
          <tbody></tbody>
          <thead>
          <tr>
          <th>
          ID
          </th>
          <th>
          Type d'offre
          </th>
          <th>
          Fichier
          </th>
          <th>
          Nombre de vélos
          </th>
          <th>
          Nombre de boxes
          </th>
          <th></th>
          <th></th>
          </tr>
          </thead>
          <tbody class="tableBody">
          `;
          for (var i = 0; i < response.companyOffers.length; i++) {
            offerId = response.companyOffers[i].ID;
            offerLink = 'offres/' + response.companyOffers[i].FILE_NAME + '.pdf';
            offerBikeNumber = response.companyOffers[i].BIKE_NUMBER;
            offerBoxNumber = response.companyOffers[i].BOX_NUMBER;
            offerType = response.companyOffers[i].TYPE;

            if(offerType =='buy'){
              offerType = 'achat';
            } else if (offerType == 'both'){
              offerType = 'achat/leasing';
            }
            dest+=`
            <tr>
            <td>`+offerId+`</td>
            <td>`+offerType+`</td>
            <td><a href="`+offerLink+`" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>
            <td>`+offerBikeNumber+`</td>
            <td>`+offerBoxNumber+`</td>
            <td><a href="#" class="text-green deletePdfOffer" style="text-decoration:underline !important;">supprimer</a></td>
            </tr>
            `;
          }
          dest += "</tbody></table>"
          $('#companyContracts').append(dest);
        }




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
            url: 'include/get_building_listing.php',
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
                  url: 'include/get_bikes_listing.php',
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
                        temp="<input type=\"checkbox\" name=\"bikeAccess[]\" checked value=\""+response.bike[i].frameNumber+"\">"+response.bike[i].frameNumber+" "+response.bike[i].model+"<br>";
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
      url: 'include/action_company.php',
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
      url: 'include/delete_pdf_offer.php',
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
