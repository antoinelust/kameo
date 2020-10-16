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
/*
 console.log(construct_form_for_accessory_status_updateAdmin(ID));
function construct_form_for_accessory_status_updateAdmin(ID){

    $('#widget-addCatalogAccessory-form input[name=ID').fadeIn();
    $('#widget-addCatalogAccessory-form label[for=ID').fadeIn();

    $('#widget-addCatalogAccessory-form select[name=category')
        .find('option')
        .remove()
        .end()
    ;
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Poignées\">Poignées</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Sacoche\">Sacoche</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Sac à dos\">Sac à dos</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Antivol\">Antivol</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Casque\">Casque</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Selle\">Selle</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Panier\">Panier</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Cadenas\">Cadenas</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Textiles\">Textiles</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Entretien\">Entretien</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"other\">Other</option>");


    $('#widget-addCatalogAccessory-form select[name=category]')
    $('.accessoryAction').removeClass('hidden');
    $('#widget-addCatalogAccessory-form input[name=action]').val("update")
        .find('option')
        .remove()
        .end()
    ;
    $('#widget-addCatalogAccessory-form select[name=category]').unbind();

    $.ajax({
      url: 'apis/Kameo/accessories/retrieveAccessory.php',
      type: 'get',
      data: {"ID": ID},
      success: function(response){
          if (response.response == 'error') {
              console.log(response.message);
          } else{
              var i=0;
              while(i<response.ID){
                  $('#widget-addCatalogAccessory-form select[name=category]').append("<option value="+response.bike[i].ID+">"+response.bike[i].BRAND+"<br>");
                  i++;
              }
          }
      }
}).done(function(){

      var id;

      $.ajax({
        url: 'apis/Kameo/accessories/add_catalog_accessory.php',
        type: 'post',
        data: { ID: ID, "action": "update"},
        success: function(response){
            if (response.response == 'error') {
                console.log(response.message);
            } else{
                document.getElementById("imageID").src="images_accessories/"+response.img+".jpg";
                $('.imageID').removeClass('hidden');
                id=response.id;

                $('#widget-addCatalogAccessory-form input[name=ID]').val(ID);
                $('#widget-addCatalogAccessory-form input[name=brand]').val(response.BRAND);
                $('#widget-addCatalogAccessory-form input[name=description]').val(response.DESCRIPTION);
                $('#widget-addCatalogAccessory-form select[name=category]').val(response.CATEGORY);
                $('#widget-addCatalogAccessory-form input[name=buyingPrice]').val(response.BUYING_PRICE);
                $('#widget-addCatalogAccessory-form input[name=sellingPrice]').val(response.PRICE_HTVA);
                $('#widget-addCatalogAccessory-form input[name=stock]').val(response.STOCK);
                $('#widget-addCatalogAccessory-form input[name=display]').val(response.DISPLAY);

                document.getElementsByClassName("picture")[0].src="images_accessories/"+response.img+".jpg";

                $('#widget-addCatalogAccessory-form input[name=ID]').change(function(){
                  $.ajax({
                      url: 'apis/Kameo/accessories/listCatalog.php',
                      type: 'get',
                      data: {"ID": $('#widget-addCatalogAccessory-form input[name=ID]').val()},
                      success: function(response){
                          if (response.response == 'error') {
                              console.log(response.message);
                          } else{
                              $('#addPicture').attr('src', "images_accessories/"+response.img+".jpg");
                              $('.imageID').removeClass('hidden');


                          }
                      }
                  })
              });
            }
          }
        })
})
}
*/


function add_accessory(ID){

    $('#widget-addCatalogAccessory-form select[name=category')
        .find('option')
        .remove()
        .end()
    ;

    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Poignées\">Poignées</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Sacoche\">Sacoche</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Sac à dos\">Sac à dos</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Antivol\">Antivol</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Casque\">Casque</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Selle\">Selle</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Panier\">Panier</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Cadenas\">Cadenas</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Textiles\">Textiles</option>");
    $('#widget-addCatalogAccessory-form select[name=category').append("<option value=\"Entretien\">Entretien</option>");

    $('.imageID').addClass('hidden');
    $('.accessoryActions').addClass('hidden');
    document.getElementById('widget-addCatalogAccessory-form').reset();

    $('#widget-addCatalogAccessory-form input[name=action]').val("add");
    $('#widget-addCatalogAccessory-form select[name=category]').val("");
    $('#widget-addCatalogAccessory-form input[name=brand]').val("");
    $('#widget-addCatalogAccessory-form input[name=description]').val("");
    $('#widget-addCatalogAccessory-form select[name=provider]').val("");
    $('#widget-addCatalogAccessory-form input[name=articleNbr]').val("");
    $('#widget-addCatalogAccessory-form input[name=stock]').val("");
    $('#widget-addCatalogAccessory-form input[name=display]').val("");
    $('#widget-addCatalogAccessory-form input[name=buyingPrice]').val("");
    $('#widget-addCatalogAccessory-form input[name=sellingPrice]').val("");


    $('#widget-bikeManagement-form input[name=brand]').change(function(){
      $.ajax({
          url: 'apis/Kameo/accessories/accessories.php',
          type: 'get',
          data: { "action": "retrieve"},
          success: function(response){
              if (response.response == 'error') {
                  console.log(response.message);
              } else{
                  $('#widget-addCatalogAccessory-form input[name=description]').val(response.DESCRIPTION);
                  $('#widget-addCatalogAccessory-form select[name=category]').val(response.CATEGORY);
                  $('#widget-addCatalogAccessory-form select[name=provider]').val(response.PROVIDER);
                  $('#widget-addCatalogAccessory-form input[name=articleNbr]').val(response.ARTICLE_NBR);
                  $('#widget-addCatalogAccessory-form input[name=buyingPrice]').val(response.BUYING_PRICE);
                  $('#widget-addCatalogAccessory-form input[name=sellingPrice]').val(response.PRICE_HTVA);
                  $('#widget-addCatalogAccessory-form input[name=stock]').val(response.STOCK);
                  $('#widget-addCatalogAccessory-form input[name=display]').val(response.DISPLAY);

                  $('#addPicture').attr('src', "images_accessories/"+response.img+".jpg");
                  $('.imageID').removeClass('hidden');
              }
          }
      })

  });

    $('#widget-addCatalogAccessory-form input[name=ID').val("");
    $('#widget-addCatalogAccessory-form input[name=ID').fadeOut();
    $('#widget-addCatalogAccessory-form label[for=ID').fadeOut();
}




$(".portfolioAccessoriesManagerClick").click(function () {
  listPortfolioAccessories();
});

//FleetManager: Gérer le catalogue | Displays the portfolio <table> by calling load_portfolio.php and creating it
function listPortfolioAccessories() {
  $.ajax({
    url: "apis/Kameo/accessories/accessories.php",
    type: "get",
    data: { action: "listCatalog" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        var dest =
          '<table class="table table-condensed" id="porfolioAccessoriesListing"><h4 class="text-green"><?=L::accessories_title_listing;?></h4><br/><a class="button small green button-3d rounded icon-right addCatalogAccessory" data-target="#portfolioAccessoryManagement" data-toggle="modal" onclick="initializeCreatePortfolioAccessories()" href="#"><span><i class="fa fa-plus"></i><?=L::accessories_add_accessory;?></span></a><thead><tr><th>ID</th><th>Modèle</th><th><?=L::accessories_description;?></th><th><?=L::accessories_buying_price;?></th><th><?=L::accessories_selling_price;?></th><th><?=L::accessories_stock;?></th><th><?=L::accessories_display;?></th><th><?=L::accessories_type;?></th><th>Fournisseur</th><th>Numéro d\'article</th><th></th></tr></thead><tbody>';

        response.accessories.forEach(
          (accessory) =>
            (dest = dest.concat(
              '<tr><td><a href="#" class="text-green getPortfolioDetails retrieveAccessoryAdmin" data-target="#portfolioAccessoryManagement" onclick="initializeUpdatePortfolioAccessory(\'' +
                accessory.ID +
                '\')" data-toggle="modal">' +
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
                accessory.SHOW_ACCESSORIES +
                "</td><td>" +
                accessory.CATEGORY +
                "</td><td>" +
                accessory.PROVIDER +
                "</td><td>" +
                accessory.ARTICLE_NBR +
                '</td><td><a href="#" class="text-green updateAccessoryAdmin" data-target="#portfolioAccessoryManagement" onclick="initializeUpdatePortfolioAccessory(\'' +
                accessory.ID +
                '\')" data-toggle="modal" href="#">Mettre à jour </a></td></tr>'
            ))
        );

        document.getElementById(
          "portfolioAccessoriesListing"
        ).innerHTML = dest.concat("</tbody></table>");
        displayLanguage();

        $(".updateAccessoryAdmin").click(function () {
          //construct_form_for_accessory_status_updateAdmin(this.name);
          $("#widget-addCatalogAccessory-form input").attr("readonly", false);
          $("#widget-addCatalogAccessory-form select").attr("readonly", false);
          $(".accessoryManagementTitle").html("Modifier un accessoire");
          $(".accessoryManagementSend").removeClass("hidden");
          $(".accessoryManagementSend").html('<i class="fa fa-plus"></i>Modifier');
        });

        $(".retrieveAccessoryAdmin").click(function () {
          //construct_form_for_accessory_status_updateAdmin(this.name);
          $("#widget-addCatalogAccessory-form input").attr("readonly", true);
          $("#widget-addCatalogAccessory-form select").attr("readonly", true);
          $(".accessoryManagementTitle").html("Consulter un accessoire");
          $(".accessoryManagementSend").addClass("hidden");
        });

        $(".addCatalogAccessory").click(function () {
          //add_accessory(this.name);
          $("#widget-addCatalogAccessory-form input").attr("readonly", false);
          $("#widget-addCatalogAccessory-form select").attr("readonly", false);
          $(".accessoryManagementTitle").html("Ajouter un accessoire");
          $(".accessoryManagementSend").removeClass("hidden");
          $(".accessoryManagementSend").html('<i class="fa fa-plus"></i>Ajouter');
        });

        $("#porfolioAccessoriesListing").DataTable({});

        var d = new Date();

        $(".getPortfolioDetails").click(function () {
          $("#widget-addCatalogAccessory-form .ID").removeClass("hidden");
          $("#widget-addCatalogAccessory-form input[name=ID]").val(this.name);
          $("#widget-addCatalogAccessory-form button[type=submit]").addClass(
            "hidden"
          );
          $("#widget-addCatalogAccessory-form .accessoryCatalogImage").attr(
            "src",
            "images_accessories/" + this.name + ".jpg?" + d.getTime()
          );
          $("#widget-addCatalogAccessory-form input").attr("readonly", true);
          $("#widget-addCatalogAccessory-form select").attr("disabled", true);
          $("#widget-addCatalogAccessory-form input[name=display]").attr(
            "disabled",
            true
          );
          getPortfolioDetails(this.name);
        });

         $(".updatePortfolioClick").click(function () {
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
      }
    },
  });
}

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
}

  function initializeUpdatePortfolioAccessory(ID) {
    $.ajax({
    url: "apis/Kameo/accessories/accessories.php",
    type: "get",
    data: { action: "retrieve", ID: ID },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        $("#widget-addCatalogAccessory-form input[name=ID]").val(
          response.accessory.ID
        ).attr("readonly",true);
        $("#widget-addCatalogAccessory-form [name=brand]").val(
          response.accessory.BRAND
        );
        $("#widget-addCatalogAccessory-form [name=description]").val(
          response.accessory.DESCRIPTION
        );
        $("#widget-addCatalogAccessory-form select[name=category]").val(
          response.accessory.CATEGORY
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
          response.accessory.ARTICLE_NBR
        );
        $("#widget-addCatalogAccessory-form [name=stock]").val(
          response.accessory.STOCK
        );
        document.getElementsByClassName("accessoryCatalogImage")[0].src =
          "/images_accessories/" +
          response.accessory.ID +
          ".jpg";

        $("#widget-addCatalogAccessory-form [name=display]").val(
          response.accessory.SHOW_ACCESSORIES
        );

        if (response.accessory.SHOW_ACCESSORIES == "Y") {
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



//FleetManager: Gérer le catalogue | Reset the form to add a bike to the catalogue
function initializeCreatePortfolioAccessories() {
  document.getElementById("widget-addCatalogAccessory-form").reset();
  $("#widget-addCatalogAccessory-form .ID").addClass("hidden");
  $("#widget-addCatalogAccessory-form input[name=action]").val("add");  
  $("#widget-addCatalogAccessory-form button[type=submit]").removeClass(
    "hidden"
  );
  $("#widget-addCatalogAccessory-form input[name=action]").val("add");
  //$("#widget-addCatalogAccessory-form input[name=file]").addClass("required");
  $("#widget-addCatalogAccessory-form .accessoryCatalogImage").addClass(
    "hidden"
  );
  $("#widget-addCatalogAccessory-form input").attr("readonly", false);
  $("#widget-addCatalogAccessory-form select").attr("disabled", false);
  $("#widget-addCatalogAccessory-form input[name=display]").attr(
    "disabled",
    false
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
          $("#widget-addCatalogAccessory-form [name=category]").val("");
        }
      },
    });
  }
}
