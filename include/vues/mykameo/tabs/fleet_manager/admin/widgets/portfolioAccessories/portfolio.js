$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "portfolioAccessories" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        document.getElementById("counterAccessoriesPortfolio").innerHTML =
          '<span data-speed="1" data-refresh-interval="4" data-to="' +
          response.accessoriesNumberPortfolio +
          '" data-from="0" data-seperator="true">' +
          response.accessoriesNumberPortfolio +
          "</span>";
      }
    },
  });
});



//FleetManager: Gérer le catalogue | Displays the portfolio <table> by calling load_portfolio.php and creating it
$(".portfolioAccessoriesManagerClick").click(function () {
  $("#porfolioAccessoriesListing").dataTable({
    destroy: true,
    ajax: {
      url: "apis/Kameo/accessories/accessories.php",
      contentType: "application/json",
      type: "GET",
      data: {
        action: "listCatalog",
      },
    },
    sAjaxDataProp: "accessories",
    columns: [
      {
        title: "ID",
        data: "ID",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            '<a href="#" class="text-green getPortfolioDetails" data-target="#portfolioAccessoryManagement" name="' +
              sData +
              '" data-toggle="modal">' +
              sData +
              ' </a>'
          );
        },
      },
      {title: "Marque", data: "BRAND"},
      { title: "Modèle", data: "MODEL" },
      { title: "Prix d'achat", data: "BUYING_PRICE"},
      { title: "Prix de vente", data: "PRICE_HTVA" },
      {title: "Stock",data: "STOCK"},
      {title: "Afficher ?",data: "DISPLAY"},
      {title: "Fournisseur", data: "PROVIDER"},
      {title: "Référence",data: "REFERENCE"},
      {
        title: "",
        data: "ID",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            '<a href="#" class="text-green updateAccessoryAdmin" data-target="#portfolioAccessoryManagement" name="' +
            sData +'" data-toggle="modal" href="#">Update</a>'
          );
        },
      },

    ],
    order: [
      [0, "asc"]
    ],
    paging : false
  });
});

$(".addCatalogAccessory").click(function (){
  $("#widget-addCatalogAccessory-form input").attr("readonly", false);
  $("#widget-addCatalogAccessory-form textarea").attr("readonly", false);
  $("#widget-addCatalogAccessory-form select").attr("disabled", false);
  $(".accessoryManagementTitle").html("Ajouter un accessoire");
  $(".accessoryManagementSend").removeClass("hidden");
  $(".accessoryManagementSend").html('<i class="fa fa-plus"></i>Ajouter');
  $('.accessoryCatalogImage').addClass('hidden');
  document.getElementById('widget-addCatalogAccessory-form').reset();
  $("#widget-addCatalogAccessory-form .ID").addClass("hidden");
  $("#widget-addCatalogAccessory-form input[name=action]").val("add");
  $("#widget-addCatalogAccessory-form button[type=submit]").removeClass(
    "hidden"
  );

  if (
    !$("#widget-addCatalogAccessory-form [name=category]").find("option").length
  ) {
    $.ajax({
      url: "apis/Kameo/accessories/accessories.php",
      type: "get",
      data: { action: "listCategories" },
      success: function (response) {
        if (response.response == "error") {
          console.log(response.message);
        } else {
          const categoriesSorted=response.categories.sort(function(a, b){
            //note the minus before -cmp, for descending order
            return cmp(
              [cmp(a.CATEGORY, b.CATEGORY), cmp(a.ID, b.ID)],
              [cmp(b.CATEGORY, a.CATEGORY), cmp(b.ID, a.ID)]
              );
          });

          categoriesSorted.forEach((accessory) =>
            $("#widget-addCatalogAccessory-form [name=category]").append(
              new Option(accessory.CATEGORY, accessory.ID)
            )
          );

          $("#widget-addCatalogAccessory-form [name=category]").val("");
        }
      },
    });
  }
  $("#widget-addCatalogAccessory-form [name=category]").val("");
  $("#widget-addCatalogAccessory-form [name=provider]").val("");
})

$('#portfolioAccessoryManagement').on('shown.bs.modal', function(event){
  var ID = $(event.relatedTarget).attr('name');
  $("#widget-addCatalogAccessory-form input").attr("readonly", false);
  $("#widget-addCatalogAccessory-form textarea").attr("readonly", false);
  $("#widget-addCatalogAccessory-form input[name=ID]").attr("readonly", true);
  $("#widget-addCatalogAccessory-form select").attr("disabled", false);
  $(".accessoryManagementTitle").html("Modifier un accessoire");
  $(".accessoryManagementSend").removeClass("hidden");
  $(".accessoryManagementSend").html('<i class="fa fa-plus"></i>Modifier');
  $("#widget-addCatalogAccessory-form .ID").removeClass("hidden");
  $("#widget-addCatalogAccessory-form input[name=file]").removeClass(
    "required"
  );
  $("#widget-addCatalogAccessory-form input[name=ID]").val(this.name);
  $("#widget-addCatalogAccessory-form button[type=submit]").removeClass(
    "hidden"
  );

  $("#widget-addCatalogAccessory-form input[name=action]").val(
    "update"
  );

  var d = new Date();
  $("#widget-addCatalogAccessory-form .accessoryCatalogImage").attr(
    "src",
    "images_accessories/" + ID + ".jpg?" + d.getTime()
  );
  $("#widget-addCatalogAccessory-form .accessoryCatalogImage").removeClass('hidden');

  getPortfolioDetails(ID);
});


function getPortfolioDetails(ID) {
  if (
    !$("#widget-addCatalogAccessory-form [name=category]").find("option").length
  ) {
    $.ajax({
      url: "api/accessories",
      type: "get",
      data: { action: "listCategories" },
      success: function (response) {
        if (response.response == "error") {
          console.log(response.message);
        } else{
          const categoriesSorted=response.categories.sort(function(a, b){
            //note the minus before -cmp, for descending order
            return cmp(
              [cmp(a.CATEGORY, b.CATEGORY), cmp(a.ID, b.ID)],
              [cmp(b.CATEGORY, a.CATEGORY), cmp(b.ID, a.ID)]
              );
          });

          categoriesSorted.forEach((accessory) =>
            $("#widget-addCatalogAccessory-form [name=category]").append(
              new Option(accessory.CATEGORY, accessory.ID)
            )
          );
          $("#widget-addCatalogAccessory-form [name=category]").val("");
        }
      },
    });
  }

  $.ajax({
  url: "api/accessories",
  type: "get",
  data: { action: "retrieveCatalog", ID: ID },
  success: function (response) {
    if (response.response == "error") {
      console.log(response.message);
    } else {
      $("#widget-addCatalogAccessory-form [name=ID]").val(
        ID
      );
      $("#widget-addCatalogAccessory-form [name=brand]").val(
        response.accessory.BRAND
      );
      $("#widget-addCatalogAccessory-form [name=model]").val(
        response.accessory.MODEL
      );
      $("#widget-addCatalogAccessory-form [name=description]").val(
        response.accessory.DESCRIPTION
      );
      $("#widget-addCatalogAccessory-form select[name=category]").val(
        response.accessory.ACCESSORIES_CATEGORIES
      );
      $("#widget-addCatalogAccessory-form [name=buyingPrice]").val(
        response.accessory.BUYING_PRICE
      );
      $("#widget-addCatalogAccessory-form [name=sellingPrice]").val(
        response.accessory.PRICE_HTVA
      );
      $("#widget-addCatalogAccessory-form select[name=provider]").val(
        response.accessory.PROVIDER
      );
      $("#widget-addCatalogAccessory-form [name=articleNbr]").val(
        response.accessory.REFERENCE
      );
      $("#widget-addCatalogAccessory-form [name=stock]").val(
        response.accessory.STOCK
      );
       $("#widget-addCatalogAccessory-form [name=minimalStockAccessory]").val(
        response.accessory.MINIMAL_STOCK
      );
      if (response.accessory.DISPLAY == "Y") {
        $("#widget-addCatalogAccessory-form [name=display]").prop(
          "checked",
          true
        );
      } else {
        $("#widget-addCatalogAccessory-form [name=display]").prop(
          "checked",
          false
        );
      }
    }
  },
  });
}
