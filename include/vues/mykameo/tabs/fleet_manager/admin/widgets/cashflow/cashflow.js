$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "cashFlow" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        if (response.sumContractsCurrent > 0) {
          document.getElementById("cashFlowSpan").innerHTML =
            '<span data-speed="1" data-refresh-interval="4" data-to="' +
            Math.round(response.sumContractsCurrent) +
            '" data-from="0" data-    seperator="true">' +
            Math.round(response.sumContractsCurrent) +
            "</span>";
          $("#cashFlowSpan").css("color", "#3cb395");
        } else {
          document.getElementById("cashFlowSpan").innerHTML =
            '<span data-speed="1" data-refresh-interval="4" data-to="' +
            Math.round(response.sumContractsCurrent) +
            '" data-from="0" data-      seperator="true">' +
            Math.round(response.sumContractsCurrent) +
            "</span>";
          $("#cashFlowSpan").css("color", "#d80000");
        }
      }
    },
  });
});


$('#offerManagerClick').click(function(){
    list_contracts_offers('*');
    generateCashGraphic();
});


function addCost() {
  $("#widget-costsManagement-form input").attr("readonly", false);
  $("#widget-costsManagement-form textarea").attr("readonly", false);
  $("#widget-costsManagement-form select").attr("readonly", false);
  $("#widget-costsManagement-form select").attr("disabled", false);
  $(".costManagementTitle").text("Ajouter un coût");
  $(".costManagementSendButton").removeClass("hidden");
  document.getElementById("widget-costsManagement-form").reset();
  $(".costManagementSendButton").text("Ajouter");
  $(".loanTotAmount").addClass("hidden");
  $(".costsManagementBike").addClass("hidden");
}
function retrieveCost(ID) {
  retrieve_cost(ID, "retrieve");
  $(".costManagementTitle").text("Consulter un coût");
  $(".costManagementSendButton").addClass("hidden");
  $(".loanTotAmount").addClass("hidden");
  $(".costsManagementBike").addClass("hidden");
}
function retrieveLoan(ID) {
  retrieve_cost(ID, "retrieveLoan");
  $(".costManagementTitle").text("Consulter un coût");
  $(".costManagementSendButton").addClass("hidden");
  $(".loanTotAmount").removeClass("hidden");
  $(".costsManagementBike").removeClass("hidden");
  $(".addRemoveBikesBtns").addClass("hidden");
  $(".addRemoveBikes").removeClass("hidden");
}

function updateCost(ID) {
  retrieve_cost(ID, "update");
  $(".costManagementTitle").text("Mettre à jour un coût");
  $(".costManagementSendButton").removeClass("hidden");
  $(".costManagementSendButton").text("Mettre à jour");
  $(".loanTotAmount").addClass("hidden");
  $(".costsManagementBike").addClass("hidden");
}

function updateLoan(ID) {
  retrieve_cost(ID, "updateLoan");
  $(".costManagementTitle").text("Mettre à jour un coût");
  $(".costManagementSendButton").removeClass("hidden");
  $(".costManagementSendButton").text("Mettre à jour");
  $(".loanTotAmount").removeClass("hidden");
  $(".costsManagementBike").removeClass("hidden");
  $(".addRemoveBikes").removeClass("hidden");
  $(".addRemoveBikesBtns").removeClass("hidden");
  $('#widget-costsManagement-form input[name=ID]').val(ID);
}

function toggleLoanUAmount(priceType) {
  var type = priceType.value;
  if (type === "loan") {
    $(".loanTotAmount").removeClass("hidden");
  } else {
    $(".loanTotAmount").addClass("hidden");
  }
}

var loanBikesNumber = 0;
//Module CASHFLOW ==> Cout ==> retrieve cost
function retrieve_cost(ID, action) {
  $.ajax({
    url: "apis/Kameo/costs_management.php",
    type: "get",
    data: { ID: ID, action: "retrieve" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        loanBikesNumber = response.loanBikesNumber;
        if (action == "retrieve" || action == "retrieveLoan") {
          $("#widget-costsManagement-form input").attr("readonly", true);
          $("#widget-costsManagement-form textarea").attr("readonly", true);
          $("#widget-costsManagement-form select").attr("readonly", true);
          $("#widget-costsManagement-form select").attr("disabled", true);
        } else {
          $("#widget-costsManagement-form input").attr("readonly", false);
          $("#widget-costsManagement-form textarea").attr("readonly", false);
          $("#widget-costsManagement-form select").attr("readonly", false);
          $("#widget-costsManagement-form select").attr("disabled", false);
        }
        $("#widget-costsManagement-form input[name=title]").val(response.title);
        $("#widget-costsManagement-form textarea[name=description]").val(
          response.description
        );
        $("#widget-costsManagement-form select[name=type]").val(response.type);

        if (response.start) {
          $("#widget-costsManagement-form input[name=start]").val(
            response.start.substring(0, 10)
          );
        }
        if (
          $("#widget-costsManagement-form select[name=type]").val() ==
          "one-shot"
        ) {
          $("#widget-costsManagement-form input[name=end]").attr(
            "readonly",
            true
          );
          $("#widget-costsManagement-form input[name=end]").val("");
        } else {
          if (action != "retrieve" && action != "retrieveLoan") {
            $("#widget-costsManagement-form input[name=start]").attr(
              "readonly",
              false
            );
            $("#widget-costsManagement-form input[name=end]").attr(
              "readonly",
              false
            );
          }
          if (response.end) {
            $("#widget-costsManagement-form input[name=end]").val(
              response.end.substring(0, 10)
            );
          }
        }
        $("#widget-costsManagement-form input[name=action]").val("update");
        $("#widget-costsManagement-form input[name=ID]").val(ID);
        if (response.amount) {
          $("#widget-costsManagement-form input[name=amount]").val(
            response.amount
          );
        }
        if (response.amount_total) {
          $("#widget-costsManagement-form input[name=loanTotAmount]").val(
            response.amount_total
          );
        }
        if (action === "retrieveLoan" || action === "updateLoan") {
          if (response.loanResponse === "success") {
            $(".loanListTable").empty();
            $(".loanListTable").append(`
                <table class="table table-condensed tableFixed bikeNumberTable hideAt0">
                <thead>
                  <tr>
                    <th class="bLabel"></th>
                    <th class="loanBikeID">
                      <label for="loanBikeID">ID</label>
                    </th>
                    <th class="company">
                      <label for="company">Société</label>
                    </th>
                    <th class="bikeBrandModel">
                      <label for="bikeBrandModel">Modèle</label>
                    </th>
                    <th class="loanFrameNumber">
                      <label for="loanFrameNumber">Frame Number</label>
                    </th>
                    <th class="loanBrand">
                      <label for="loanBrand">Marque</label>
                    </th>
                    <th class="bikepAchat">
                      <label for="pAchat">Prix d'Achat</label>
                    </th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>`);

              $("#costsManagement")
                .find(".bikesNumberLoan")
                .html(response.loanBikesNumber);

            var i = 0;
            while (i < response.loanBikesNumber) {
              $("#costsManagement")
                .find(".costsManagementBike tbody")
                .append(
                  `<tr class="bikesNumberTable` +
                    response.loanBikesNumber +
                    ` bikeRow form-group">
                    <td class="bLabel"></td>
                    <td class="loanBikeID">` + response.loan[i].idBike + `</td>
                    <td class="company">` + response.loan[i].company + `</td>
                    <td class="bikeBrandModel">` + response.loan[i].model + `</td>
                    <td class="loanFrameNumber">` + response.loan[i].frameNumber + `</td>
                    <td class="loanBrand">` + response.loan[i].brand + `</td>
                    <td class="bikepAchat">` + response.loan[i].buyPrice + `</td>
                  </tr>`
                );

              i++;
            }

            $("#widget-costsManagement-form input[name=sumBuyBikes]").val(
              response.sumBikesIncluded
            );

          }
        }
      }
    },
  });
}
function generateCashGraphic() {
  $.ajax({
    url: "api/cashFlow",
    type: "get",
    data: { action: "getGraphics" },
    success: function (response) {
      var threeYearsFromNow = new Date();
      threeYearsFromNow.setFullYear(threeYearsFromNow.getFullYear() + 1);
      var maxXAxis = threeYearsFromNow.toISOString().split("T")[0];

      var ctx = document.getElementById("myChart").getContext("2d");
      var myChart = new Chart(ctx, {
        type: "line",
        data: {
          datasets: [
            {
              label: "Contrats signés",
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(44, 132, 109, 0)",
              data: response.arrayContracts,
            },
            {
              label: "Offres",
              borderColor: "rgba(145, 145, 145, 0.5)",
              backgroundColor: "rgba(145, 145, 145, 0)",
              data: response.arrayOffers,
            },
            {
              label: "Chiffre d'affaire",
              borderColor: "rgba(60, 179, 149, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0)",
              data: response.totalIN,
            },
            {
              label: "Frais",
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(176, 0, 0, 0)",
              data: response.arrayCosts,
            },
            {
              label: "Cash flow",
              borderColor: "rgba(60, 179, 149, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              data: response.arrayFreeCashFlow,
            },
          ],
          labels: response.arrayDates,
        },

        options: {
          scales: {
            xAxes: [
              {
                ticks: {
                  max: "2020-12-19",
                },
              },
            ],
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
              },
            ],
          },
          elements: {
            line: {
              tension: 0,
            },
          },
        },
      });
      myChart.update();
    }
  });
}


function list_contracts_offers(company) {
  $.ajax({
    url: "api/cashFlow",
    type: "get",
    data: { action: "getContracts" },
    success: function (response) {
        var dest = "";
        var temp =
          '<table class="table table-condensed"><h4 class="text-green">Contrats signés (vélos) :</h4><br/><div class="seperator seperator-small visible-xs"></div><thead><tr><th><span>Société</span></th><th><span>Description</span></th><th><span>Montant</span></th></tr></thead>';
        dest = dest.concat(temp);
        var total=0;
        response.contracts.bikes.forEach(function(contractBike){
          var temp =
            '<tr><td><a href="#" class="internalReferenceCompany" data-target="#companyDetails" data-toggle="modal" name="' +
            contractBike.companyID +
            '">' +
            contractBike.company +
            "</a></td><td>" +
            contractBike.bikeNumber + " vélos</td><td>" +
            Math.round(contractBike.leasingAmount) +
            " €/mois</td></tr>";
          dest = dest.concat(temp);
          total+=contractBike.leasingAmount;
        })
        var temp = "</tobdy></table>";
        dest = dest.concat(temp);

        var temp =
          "<p>Valeur actuelle des contrat en cours : <strong>" +
          Math.round(total) +
          " €/mois</strong></p>";
        dest = dest.concat(temp);

        document.getElementById("contractsBikesListingSpan").innerHTML = dest;


        var dest = "";
        var temp =
          '<table class="table table-condensed"><h4 class="text-green">Contrats signés (bornes):</h4><br/><div class="seperator seperator-small visible-xs"></div><thead><tr><th><span>Société</span></th><th><span>Description</span></th><th><span>Montant</span></th></tr></thead>';
        dest = dest.concat(temp);
        var total=0;
        response.contracts.boxes.forEach(function(contractBike){
          var temp =
            '<tr><td><a href="#" class="internalReferenceCompany" data-target="#companyDetails" data-toggle="modal" name="' +
            contractBike.companyID +
            '">' +
            contractBike.company +
            "</a></td><td>" +
            contractBike.boxesNumber + " bornes</td><td>" +
            Math.round(contractBike.amount) +
            " €/mois</td></tr>";
          dest = dest.concat(temp);
          total+=contractBike.amount;
        })
        var temp = "</tobdy></table>";
        dest = dest.concat(temp);

        var temp =
          "<p>Valeur actuelle des contrat en cours : <strong>" +
          Math.round(total) +
          " €/mois</strong></p>";
        dest = dest.concat(temp);

        document.getElementById("contractsBoxesListingSpan").innerHTML = dest;

        var dest = "";
        var temp =
          '<h4 class="text-green">Offres en cours :</h4><br/><div class="seperator seperator-small visible-xs"></div><table class="table table-condensed"><tbody><thead><tr><th>ID</th><th>PDF</th><th>Société</th><th>Type</th><th>Titre</th><th>Montant</th><th>Debut</th><th>Fin</th><th>Probabilité</th><th></th></tr></thead>';
        dest = dest.concat(temp);
        response.offers.forEach(function(offer){
          if (offer.START != null) {
            var offer_start = offer.START.shortDate();
          } else {
            var offer_start = '<span class="text-red">N/A</span>';
          }
          if (offer.END != null) {
            var offer_end = offer.END.shortDate();
          } else {
            var offer_end = '<span class="text-red">N/A</span>';
          }

          if (offer.TYPE == "leasing") {
            var amount = Math.round(offer.AMOUNT) + "€/mois";
          } else {
            var amount = Math.round(offer.AMOUNT) + "€";
          }

          if (offer.AMOUNT == 0) {
            var amount = '<span class="text-red">' + amount + "</span>";
          }

          if (offer.TYPE == "leasing") {
            var type = "Leasing";
          } else if (offer.type == "achat") {
            var type = "Achat";
          }

          if (
            offer.PROBABILITY == 0 ||
            offer.PROBABILITY == 0
          ) {
            var probability =
              '<span class="text-red">' +
              offer.PROBABILITY +
              " %</span>";
          } else {
            var probability =
              "<span>" + offer.PROBABILITY + " %</span>";
          }

          if (offer.FILE_NAME != "" && offer.FILE_NAME != null) {
            var offerLink = "offres/" + offer.FILE_NAME;

            var temp =
              '<tr><td><a href="#" data-target="#offerManagement" data-action="retrieve" data-toggle="modal" data-id="' +
              offer.ID +
              '">' +
              offer.ID +
              "</a></td><td><a href=" +
              offerLink +
              ' target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td><td>' +
              offer.COMPANY +
              "</td><td>" +
              type +
              "</td><td>" +
              offer.TITRE +
              "</td><td>" +
              amount +
              " </td><td>" +
              offer_start +
              "</td><td>" +
              offer_end +
              "</td><td>" +
              probability +
              '</td><td><ins><a class="text-green" data-target="#offerManagement" data-action="update" data-id="' +
              offer.ID +
              '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>';
          } else {
            var temp =
              '<tr><td><a href="#" data-target="#offerManagement" data-action="retrieve" data-toggle="modal" data-id="' +
              offer.ID +
              '">' +
              offer.ID +
              "</a></td><td></td><td>" +
              offer.COMPANY +
              "</td><td>" +
              type +
              "</td><td>" +
              offer.TITRE +
              "</td><td>" +
              amount +
              " </td><td>" +
              offer_start +
              "</td><td>" +
              offer_end +
              "</td><td>" +
              probability +
              '</td><td><ins><a class="text-green" data-action="update" data-target="#offerManagement" data-id="' +
              offer.ID +
              '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>';
          }

          dest = dest.concat(temp);
        });
        var temp = "</tbody></table>";
        dest = dest.concat(temp);
        document.getElementById("cashListingSpan").innerHTML = dest;
      }
    });
    $.ajax({
      url: "api/cashFlow",
      type: "get",
      data: { action: "getCosts" },
      success: function (response) {
        var dest = "";
        var temp =
          '<table class="table table-condensed"><h4 class="text-green">Coûts :</h4><br/><a class="button small green button-3d rounded icon-right" data-target="#costsManagement" data-toggle="modal" href="#" onclick=\"addCost()\"><i class="fa fa-plus"></i> Ajouter un coût</a><div class="seperator seperator-small visible-xs"></div><tbody><thead><tr><th>ID</th><th>Titre</th><th>Montant</th><th>Debut</th><th>Fin</th><th>Type</th><th></th></tr></thead>';
        dest = dest.concat(temp);
        response.forEach(function(cost){
          if (cost.START != null) {
            var cost_start = cost.START.shortDate();
          } else {
            var cost_start = "N/A";
          }
          if (cost.END != null) {
            var cost_end = cost.END.shortDate();
          } else {
            var cost_end = "N/A";
          }

          if (cost.TYPE == "monthly" || cost.TYPE == "loan") {
            var amount = Math.round(cost.AMOUNT) + "€ /mois";
          } else {
            var amount = Math.round(cost.AMOUNT) + " €";
          }

          if (cost.TYPE === "loan") {
            var updateCostLoan = 'updateLoan(this.name)'
            var retrieveCostLoan = 'retrieveLoan(this.name)'
          } else {
            var updateCostLoan = 'updateCost(this.name)'
            var retrieveCostLoan = 'retrieveCost(this.name)'
          }

          var temp =
            '<tr><td><a href="#" onclick=\"' + retrieveCostLoan + '\" data-target="#costsManagement" data-toggle="modal" name="' +
            cost.ID +
            '">' +
            cost.ID +
            "</a></td><td>" +
            cost.TITLE +
            "</td><td>" +
            amount +
            " </td><td>" +
            cost_start +
            "</td><td>" +
            cost_end +
            '</td><td><ins><a class="text-green costsManagement" data-target="#costsManagement" name="' +
            cost.ID +
            '" data-toggle="modal" href="#" onclick=\"' + updateCostLoan + '\">Mettre à jour</a></ins></td></tr>';
          dest = dest.concat(temp);
        });
        var temp = "</tbody></table>";
        dest = dest.concat(temp);
        document.getElementById("costsListingSpan").innerHTML = dest;
    }
  });
}
