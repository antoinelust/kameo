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

function addCost () {
  $("#widget-costsManagement-form input").attr("readonly", false);
  $("#widget-costsManagement-form textarea").attr("readonly", false);
  $("#widget-costsManagement-form select").attr("readonly", false);
  $(".costManagementTitle").text("Ajouter un coût");
  $(".costManagementSendButton").removeClass("hidden");
  document.getElementById("widget-costsManagement-form").reset();
  $(".costManagementSendButton").text("Ajouter");
};
function retrieveCost (ID) {
  retrieve_cost(ID, "retrieve");
  $(".costManagementTitle").text("Consulter un coût");
  $(".costManagementSendButton").addClass("hidden");
};

function updateCost (ID) {
  retrieve_cost(ID, "update");
  $(".costManagementTitle").text("Mettre à jour un coût");
  $(".costManagementSendButton").removeClass("hidden");
  $(".costManagementSendButton").text("Mettre à jour");
};

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
        if (action == "retrieve") {
          $("#widget-costsManagement-form input").attr("readonly", true);
          $("#widget-costsManagement-form textarea").attr("readonly", true);
          $("#widget-costsManagement-form select").attr("readonly", true);
        } else {
          $("#widget-costsManagement-form input").attr("readonly", false);
          $("#widget-costsManagement-form textarea").attr("readonly", false);
          $("#widget-costsManagement-form select").attr("readonly", false);
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
          if (action != "retrieve") {
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
