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
