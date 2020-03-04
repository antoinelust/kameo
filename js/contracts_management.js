function list_contracts_offers(company) {
    $.ajax({
        url: 'include/offer_management.php',
        type: 'get',
        data: { "company": company, action: "retrieve"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var i=0;
                var dest="";
                var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Contrats signés :</h4><h4 class=\"en-inline text-green\">Contracts:</h4><h4 class=\"nl-inline text-green\">Contracts:</h4><br/><br/><div class=\"seperator seperator-small visible-xs\"></div><tbody><thead><tr><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Description</span><span class=\"en-inline\">Description</span><span class=\"nl-inline\">Description</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Debut</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Fin</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th></tr></thead>";
                dest=dest.concat(temp);
                while (i < response.contractsNumber){
                    if(response.contract[i].start!=null){
                        var contract_start=response.contract[i].start.substr(0,10);
                    }else{
                        var contract_start="<span class=\"text-red\">N/A</span>";
                    }
                    if(response.contract[i].end!=null){
                        var contract_end=response.contract[i].end.substr(0,10);
                    }else{
                        var contract_end="<span class=\"text-red\">N/A</span>";
                    }

                    var temp="<tr><td>"+response.contract[i].company+"</td><td>"+response.contract[i].description+"</td><td>"+Math.round(response.contract[i].amount)+" €/mois</td><td>"+contract_start+"</td><td>"+contract_end+"</td></tr>";
                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tobdy></table>";
                dest=dest.concat(temp);

                var temp="<p>Valeur actuelle des contrat en cours : <strong>"+Math.round(response.sumContractsCurrent)+" €/mois</strong></p>";
                dest=dest.concat(temp);

                document.getElementById('contractsListingSpan').innerHTML = dest;



                var i=0;
                var dest="";
                var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Offres en cours :</h4><h4 class=\"en-inline text-green\">Offers:</h4><h4 class=\"nl-inline text-green\">Offers:</h4><br/><br/><div class=\"seperator seperator-small visible-xs\"></div><tbody><thead><tr><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th>Type</th><th><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Debut</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Fin</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th>Probabilité</th><th></th></tr></thead>";
                dest=dest.concat(temp);
                while (i < response.offersNumber){
                    if(response.offer[i].start!=null){
                        var offer_start=response.offer[i].start.substr(0,10);
                    }else{
                        var offer_start="<span class=\"text-red\">N/A</span>";
                    }
                    if(response.offer[i].end!=null){
                        var offer_end=response.offer[i].end.substr(0,10);
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


                    var temp="<tr><td><a href=\"#\" class=\"retrieveOffer\" data-target=\"#offerManagement\" data-toggle=\"modal\" name=\""+response.offer[i].id+"\">"+response.offer[i].id+"</a></td><td>"+response.offer[i].company+"</td><td>"+type+"</td><td>"+response.offer[i].title+"</td><td>"+amount+" </td><td>"+offer_start+"</td><td>"+offer_end+"</td><td>"+probability+"</td><td><ins><a class=\"text-green offerManagement updateOffer\" data-target=\"#offerManagement\" name=\""+response.offer[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";


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
                        var cost_start=response.cost[i].start.substr(0,10);
                    }else{
                        var cost="N/A";
                    }
                    if(response.cost[i].end!=null){
                        var cost_end=response.cost[i].end.substr(0,10);
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
        url: 'include/offer_management.php',
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
        url: 'include/offer_management.php',
        type: 'get',
        data: {"ID": ID, "action": "retrieve"},
        success: function(response){
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

            }
        }
    })

}


function get_sold_bikes(){
  $.ajax({
      url: 'include/get_bikes_listing.php',
      method: 'post',
      data: {
        "email" : email,
        "admin" : "Y"
    },
      success: function(response){
        var soldBikes = new Array();
        for (var i = 0; i < response.bike.length; i++) {
          if(response.bike[i].contractType == "selling"){
            soldBikes.push(response.bike[i]);
          }
        }
        var dest = `
        <h4 class="fr-inline text-green">Vélos vendus</h4>
        <h4 class="en-inline text-green">Sold bikes</h4>
        <h4 class="nl-inline text-green">Sold Bikes</h4>
        <br />
        <br />
        <div class="seperator seperator-small visible-xs"></div>
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>
                <span class="fr-inline">Société</span>
                <span class="en-inline">Company</span>
                <span class="nl-inline">Company</span>
              </th>
              <th>
                <span class="fr-inline">Vélo</span>
                <span class="en-inline">Bike</span>
                <span class="nl-inline">Bike</span>
              </th>
              <th>
                <span class="fr-inline">Marque</span>
                <span class="en-inline">Brand</span>
                <span class="nl-inline">Brand</span>
              </th>
              <th>
                <span class="fr-inline">Modèle</span>
                <span class="en-inline">Model</span>
                <span class="nl-inline">Model</span>
              </th>
              <th>
                <span class="fr-inline">Prix de vente HTVA</span>
                <span class="en-inline">Sold price</span>
                <span class="nl-inline">Sold price</span>
              </th>
              <th>
                <span class="fr-inline">Date de vente</span>
                <span class="en-inline">Sold date</span>
                <span class="nl-inline">Sold date</span>
              </th>
            </tr>
          </thead>
          <tbody>
        `;

        soldBikes.forEach((soldBike) => {
          var soldBikeTd = `<td>`+soldBike.soldPrice+` €</td>`
          if(soldBike.soldPrice == 0){
            soldBikeTd = `<td class="text-red">N/A</td>`
          }
          dest += `
            <tr>
            <td>`+soldBike.company+`</td>
            <td>`+soldBike.frameNumber+`</td>
            <td>`+soldBike.brand+`</td>
            <td>`+soldBike.modelBike+`</td>
            `+soldBikeTd+`
            <td>`+soldBike.contractStart+`</td>
            </tr>
          `;
        });



        dest +="</tobdy></table>";
        $("#soldBikesListingSpan").html(dest);
      }

  });
}
