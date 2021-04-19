$( document ).ready(function() {
  $(".espaceCollaboratif").click(function () {
    $.ajax({
      url: "apis/Kameo/initialize_counters.php",
      type: "post",
      data: { type: "customersCollab" },
      success: function (response) {
        if (response.response == "error") {
          console.log(response.message);
        }
        if (response.response == "success") {
          document.getElementById("counterClientsCollab").innerHTML =
            '<span data-speed="1" data-refresh-interval="4" data-to="' +
            response.companiesNumber +
            '" data-from="0" data-  seperator="true">' +
            response.companiesNumber +
            "</span>";
        }
      },
    });
  });
})


$("#customerCollabListing").on("show.bs.modal", function (event) {
  $("#companyCollabListingTable").dataTable({
    destroy: true,
    ajax: {
      url: "api/customerCollab",
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
      { title: "Nom", data: "COMPANY_NAME" },
      { title: "Nombre&nbsp;de&nbsp;vélos", data: "bikeNumber" }
    ],
    order: [
      [0, "asc"],
    ],
  });
})
