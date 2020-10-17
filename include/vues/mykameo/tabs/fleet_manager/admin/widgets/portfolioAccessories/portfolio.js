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
  $.ajax({
    url: "apis/Kameo/accessories/accessories.php",
    type: "get",
    data: { action: "listCatalog" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        var dest =
          '<table class="table table-condensed" id="porfolioAccessoriesListing"><h4 class="text-green"><?=L::accessories_title_listing;?></h4><br/><a class="button small green button-3d rounded icon-right addCatalogAccessory" data-target="#portfolioAccessoryManagement" data-toggle="modal" href="#"><span><i class="fa fa-plus"></i><?=L::accessories_add_accessory;?></span></a><thead><tr><th>ID</th><th>Modèle</th><th><?=L::accessories_description;?></th><th><?=L::accessories_buying_price;?></th><th><?=L::accessories_selling_price;?></th><th><?=L::accessories_stock;?></th><th><?=L::accessories_display;?></th><th><?=L::accessories_type;?></th><th>Fournisseur</th><th>Numéro d\'article</th><th></th></tr></thead><tbody>';

        response.accessories.forEach(
          (accessory) =>
            (dest = dest.concat(
              '<tr><td><a href="#" class="text-green getPortfolioDetails" data-target="#portfolioAccessoryManagement" name="' +
                accessory.ID +
                '" data-toggle="modal">' +
                accessory.ID +
                " </a></td><td>" +
                accessory.BRAND +
                "</td><td>" +
                accessory.DESCRIPTION +
                "</td><td>" +
                accessory.BUYING_PRICE +
                " €</td><td>" +
                accessory.PRICE_HTVA +
                " €</td><td>" +
                accessory.STOCK +
                "</td><td>" +
                accessory.DISPLAY +
                "</td><td>" +
                accessory.CATEGORY +
                "</td><td>" +
                accessory.PROVIDER +
                "</td><td>" +
                accessory.REFERENCE +
                '</td><td><a href="#" class="text-green updateAccessoryAdmin" data-target="#portfolioAccessoryManagement" name="' +
                accessory.ID +'" data-toggle="modal" href="#">Mettre à jour </a></td></tr>'
            ))
        );

        document.getElementById(
          "portfolioAccessoriesListing"
        ).innerHTML = dest.concat("</tbody></table>");

        var d = new Date();

        $(".getPortfolioDetails").click(function () {
          $("#widget-addCatalogAccessory-form input").attr("readonly", true);
          $("#widget-addCatalogAccessory-form select").attr("disabled", true);
          $("#widget-addCatalogAccessory-form input[name=display]").attr(
            "disabled",
            true
          );

          $(".accessoryManagementTitle").html("Consulter un accessoire");
          $(".accessoryManagementSend").addClass("hidden");

          $("#widget-addCatalogAccessory-form .ID").removeClass("hidden");
          $("#widget-addCatalogAccessory-form input[name=ID]").val(this.name);
          $("#widget-addCatalogAccessory-form button[type=submit]").addClass(
            "hidden"
          );
          $("#widget-addCatalogAccessory-form .accessoryCatalogImage").attr(
            "src",
            "images_accessories/" + this.name + ".jpg?" + d.getTime()
          );
          getPortfolioDetails(this.name);
        });


        $(".updateAccessoryAdmin").click(function () {
          $("#widget-addCatalogAccessory-form input").attr("readonly", false);
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
            "images_accessories/" + this.name + ".jpg?" + d.getTime()
          );
          $("#widget-addCatalogAccessory-form input").attr("readonly", false);
          $("#widget-addCatalogAccessory-form select").attr("disabled", false);
          $("#widget-addCatalogAccessory-form input[name=display]").attr(
            "disabled",
            false
          );
          getPortfolioDetails(this.name);
        });

        $(".addCatalogAccessory").click(function () {
          $("#widget-addCatalogAccessory-form input").attr("readonly", false);
          $("#widget-addCatalogAccessory-form input").attr("disabled", false);
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
                  response.categories.forEach((accessory) =>
                    $("#widget-addCatalogAccessory-form [name=category]").append(
                      new Option(accessory.CATEGORY, accessory.ID)
                    )
                  );
                }
              },
            });
          }

          $("#widget-addCatalogAccessory-form [name=category]").val("");



        });

        $("#porfolioAccessoriesListing").DataTable({
           "paging":   false
        });
      }
    },
  });
});

function getPortfolioDetails(ID) {
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
          response.categories.forEach((accessory) =>
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
  url: "apis/Kameo/accessories/accessories.php",
  type: "get",
  data: { action: "retrieve", ID: ID },
  success: function (response) {
    console.log(response);
    if (response.response == "error") {
      console.log(response.message);
    } else {
      $("#widget-addCatalogAccessory-form [name=brand]").val(
        response.accessory.BRAND
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
      document.getElementsByClassName("accessoryCatalogImage")[0].src =
        "/images_accessories/" +
        ID +
        ".jpg";

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
