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
    url: "apis/Kameo/offer_management.php",
    type: "get",
    data: { graphics: "Y", action: "retrieve" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
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
    },
  });
}


function list_contracts_offers(company) {
  $.ajax({
    url: "apis/Kameo/offer_management.php",
    type: "get",
    data: { company: company, action: "retrieve" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        var i = 0;
        var dest = "";
        var temp =
          '<table class="table table-condensed"><h4 class="fr-inline text-green">Contrats signés :</h4><br/><br/><div class="seperator seperator-small visible-xs"></div><thead><tr><th><span class="fr-inline">Société</span></th><th><span class="fr-inline">Description</span></th><th><span class="fr-inline">Montant</span></th><th><span class="fr-inline">Debut</span></th><th><span class="fr-inline">Fin</span></th></tr></thead>';
        dest = dest.concat(temp);
        while (i < response.contractsNumber) {
          if (response.contract[i].start != null) {
            var contract_start = response.contract[i].start.shortDate();
          } else {
            var contract_start = '<span class="text-red">N/A</span>';
          }
          if (response.contract[i].end != null) {
            var contract_end = response.contract[i].end.shortDate();
          } else {
            var contract_end = '<span class="text-red">N/A</span>';
          }

          var temp =
            '<tr><td><a href="#" class="internalReferenceCompany" data-target="#companyDetails" data-toggle="modal" name="' +
            response.contract[i].companyID +
            '">' +
            response.contract[i].company +
            "</a></td><td>" +
            response.contract[i].description +
            "</td><td>" +
            Math.round(response.contract[i].amount) +
            " €/mois</td><td>" +
            contract_start +
            "</td><td>" +
            contract_end +
            "</td></tr>";
          dest = dest.concat(temp);
          i++;
        }
        var temp = "</tobdy></table>";
        dest = dest.concat(temp);

        var temp =
          "<p>Valeur actuelle des contrat en cours : <strong>" +
          Math.round(response.sumContractsCurrent) +
          " €/mois</strong></p>";
        dest = dest.concat(temp);

        document.getElementById("contractsListingSpan").innerHTML = dest;

        var i = 0;
        var dest = "";
        var temp =
          '<h4 class="fr-inline text-green">Offres en cours :</h4><h4 class="en-inline text-green">Offers:</h4><h4 class="nl-inline text-green">Offers:</h4><br/><br/><div class="seperator seperator-small visible-xs"></div><table class="table table-condensed"><tbody><thead><tr><th>ID</th><th>PDF</th><th><span class="fr-inline">Société</span><span class="en-inline">Company</span><span class="nl-inline">Company</span></th><th>Type</th><th><span class="fr-inline">Titre</span><span class="en-inline">Title</span><span class="nl-inline">Title</span></th><th><span class="fr-inline">Montant</span><span class="en-inline">Amount</span><span class="nl-inline">Amount</span></th><th><span class="fr-inline">Debut</span><span class="en-inline">Start</span><span class="nl-inline">Start</span></th><th><span class="fr-inline">Fin</span><span class="en-inline">End</span><span class="nl-inline">End</span></th><th>Probabilité</th><th></th></tr></thead>';
        dest = dest.concat(temp);
        while (i < response.offersNumber) {
          if (response.offer[i].start != null) {
            var offer_start = response.offer[i].start.shortDate();
          } else {
            var offer_start = '<span class="text-red">N/A</span>';
          }
          if (response.offer[i].end != null) {
            var offer_end = response.offer[i].end.shortDate();
          } else {
            var offer_end = '<span class="text-red">N/A</span>';
          }

          if (response.offer[i].type == "leasing") {
            var amount = Math.round(response.offer[i].amount) + "€/mois";
          } else {
            var amount = Math.round(response.offer[i].amount) + "€";
          }

          if (response.offer[i].amount == 0) {
            var amount = '<span class="text-red">' + amount + "</span>";
          }

          if (response.offer[i].type == "leasing") {
            var type = "Leasing";
          } else if (response.offer[i].type == "achat") {
            var type = "Achat";
          }

          if (
            response.offer[i].probability == 0 ||
            response.offer[i].probability == 0
          ) {
            var probability =
              '<span class="text-red">' +
              response.offer[i].probability +
              " %</span>";
          } else {
            var probability =
              "<span>" + response.offer[i].probability + " %</span>";
          }

          if (response.offer[i].file != "" && response.offer[i].file != null) {
            var offerLink = "offres/" + response.offer[i].file;

            var temp =
              '<tr><td><a href="#" class="retrieveOffer" data-target="#offerManagement" data-toggle="modal" name="' +
              response.offer[i].id +
              '">' +
              response.offer[i].id +
              "</a></td><td><a href=" +
              offerLink +
              ' target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td><td>' +
              response.offer[i].company +
              "</td><td>" +
              type +
              "</td><td>" +
              response.offer[i].title +
              "</td><td>" +
              amount +
              " </td><td>" +
              offer_start +
              "</td><td>" +
              offer_end +
              "</td><td>" +
              probability +
              '</td><td><ins><a class="text-green offerManagement updateOffer" data-target="#offerManagement" name="' +
              response.offer[i].id +
              '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>';
          } else {
            var temp =
              '<tr><td><a href="#" class="retrieveOffer" data-target="#offerManagement" data-toggle="modal" name="' +
              response.offer[i].id +
              '">' +
              response.offer[i].id +
              "</a></td><td></td><td>" +
              response.offer[i].company +
              "</td><td>" +
              type +
              "</td><td>" +
              response.offer[i].title +
              "</td><td>" +
              amount +
              " </td><td>" +
              offer_start +
              "</td><td>" +
              offer_end +
              "</td><td>" +
              probability +
              '</td><td><ins><a class="text-green offerManagement updateOffer" data-target="#offerManagement" name="' +
              response.offer[i].id +
              '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>';
          }

          dest = dest.concat(temp);
          i++;
        }
        var temp = "</tbody></table>";
        dest = dest.concat(temp);
        document.getElementById("cashListingSpan").innerHTML = dest;

        var i = 0;
        var dest = "";
        var temp =
          '<table class="table table-condensed"><h4 class="fr-inline text-green">Coûts :</h4><br/><br/><a class="button small green button-3d rounded icon-right" data-target="#costsManagement" data-toggle="modal" href="#" onclick=\"addCost()\"><span class="fr-inline"><i class="fa fa-plus"></i> Ajouter un coût</span></a><div class="seperator seperator-small visible-xs"></div><tbody><thead><tr><th>ID</th><th><span class="fr-inline">Titre</span></th><th><span class="fr-inline">Montant</span></th><th><span class="fr-inline">Debut</span></th><th><span class="fr-inline">Fin</span></th><th>Type</th><th></th></tr></thead>';
        dest = dest.concat(temp);
        while (i < response.costsNumber) {
          if (response.cost[i].start != null) {
            var cost_start = response.cost[i].start.shortDate();
          } else {
            var cost_start = "N/A";
          }
          if (response.cost[i].end != null) {
            var cost_end = response.cost[i].end.shortDate();
          } else {
            var cost_end = "N/A";
          }

          if (response.cost[i].type == "monthly" || response.cost[i].type == "loan") {
            var amount = Math.round(response.cost[i].amount) + "€ /mois";
          } else {
            var amount = Math.round(response.cost[i].amount) + " €";
          }

          if (response.cost[i].type === "loan") {
            var updateCostLoan = 'updateLoan(this.name)'
            var retrieveCostLoan = 'retrieveLoan(this.name)'
          } else {
            var updateCostLoan = 'updateCost(this.name)'
            var retrieveCostLoan = 'retrieveCost(this.name)'
          }

          var temp =
            '<tr><td><a href="#" onclick=\"' + retrieveCostLoan + '\" data-target="#costsManagement" data-toggle="modal" name="' +
            response.cost[i].id +
            '">' +
            response.cost[i].id +
            "</a></td><td>" +
            response.cost[i].title +
            "</td><td>" +
            amount +
            " </td><td>" +
            cost_start +
            "</td><td>" +
            cost_end +
            '</td><td><ins><a class="text-green costsManagement" data-target="#costsManagement" name="' +
            response.cost[i].id +
            '" data-toggle="modal" href="#" onclick=\"' + updateCostLoan + '\">Mettre à jour</a></ins></td></tr>';
          dest = dest.concat(temp);
          i++;
        }
        var temp = "</tbody></table>";
        dest = dest.concat(temp);
        document.getElementById("costsListingSpan").innerHTML = dest;

        $(".retrieveOffer").click(function () {
          retrieve_offer(this.name, "retrieve");
          $(".offerManagementTitle").text("Consulter une offre");
          $(".offerManagementSendButton").addClass("hidden");
        });
        $(".updateOffer").click(function () {
          retrieve_offer(this.name, "update");
          $(".offerManagementTitle").text("Mettre à jour une offre");
          $(".offerManagementSendButton").removeClass("hidden");
          $(".offerManagementSendButton").text("Mettre à jour");
        });
        displayLanguage();
      }
    },
  });
}
