$( document ).ready(function() {
  $(".espaceCollaboratif").click(function () {
    $.ajax({
      url: "apis/Kameo/initialize_counters.php",
      type: "post",
      data: { type: "bikesCollab" },
      success: function (response) {
        if (response.response == "error") {
          console.log(response.message);
        }
        if (response.response == "success") {
          document.getElementById("counterBikesCollab").innerHTML =
            '<span data-speed="1" data-refresh-interval="4" data-to="' +
            response.bikesNumber +
            '" data-from="0" data-  seperator="true">' +
            response.bikesNumber +
            "</span>";
        }
      },
    });
  });
})


$("#bikesCollabListing").on("show.bs.modal", function (event) {
  $("#bikesCollabListingTable").dataTable({
    destroy: true,
    ajax: {
      url: "api/bikesCollab",
      contentType: "application/json",
      type: "GET",
      data: {
        action: "list",
      },
    },
    sAjaxDataProp: "",
    columnDefs: [{ width: "100%", targets: 0 }],
    columns: [
      { title: "ID", data: "ID" },
      { title: "Société", data: "COMPANY_NAME" },
      { title: "Marque", data: "BRAND" },
      { title: "Modèle", data: "MODEL" },
      { title: "Début de contrat", data: "CONTRACT_START" },
      { title: "Fin de contrat", data: "CONTRACT_END" },
      { title: "Montant", data: "LEASING_PRICE" }
    ],
    order: [
      [1, "asc"],
    ],
    "pageLength": 50
  });
})
