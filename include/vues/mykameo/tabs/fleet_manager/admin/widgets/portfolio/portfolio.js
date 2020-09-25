$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "portfolio" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        document.getElementById("counterBikePortfolio").innerHTML =
          '<span data-speed="1" data-refresh-interval="4" data-to="' +
          response.bikeNumberPortfolio +
          '" data-from="0" data-seperator="true">' +
          response.bikeNumberPortfolio +
          "</span>";
      }
    },
  });
});

//FleetManager: Gérer le catalogue | Displays the portfolio <table> by calling load_portfolio.php and creating it
function listPortfolioBikes() {
  $.ajax({
    url: "apis/Kameo/load_portfolio.php",
    type: "get",
    data: { action: "list" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        var dest =
          '<table class="table table-condensed" id="portfolioBikeListing"><h4 class="fr-inline text-green">Vélos du catalogue:</h4><h4 class="en-inline text-green">Portfolio bikes:</h4><h4 class="nl-inline text-green">Portfolio bikes:</h4><br/><a class="button small green button-3d rounded icon-right" data-target="#addPortfolioBike" data-toggle="modal" onclick="initializeCreatePortfolioBike()" href="#"><span class="fr-inline"><i class="fa fa-plus"></i> Ajouter un vélo</span></a><thead><tr><th>ID</th><th><span class="fr-inline">Marque</span><span class="en-inline">Brand</span><span class="nl-inline">Brand</span></th><th><span class="fr-inline">Modèle</span><span class="en-inline">Model</span><span class="nl-inline">Model</span></th><th><span class="fr-inline">Utilisation</span><span class="en-inline">Use</span><span class="nl-inline">Use</span></th><th><span class="fr-inline">Electrique ?</span><span class="en-inline">Electric</span><span class="nl-inline">Electric</span></th><th><span class="fr-inline">Cadre</span><span class="en-inline">Frame</span><span class="nl-inline">Frame</span></th><th><span class="fr-inline">Prix</span><span class="en-inline">Price</span><span class="nl-inline">Price</span></th><th>Afficher</th><th></th></tr></thead><tbody>';
        for (i = 0; i < response.bikeNumber; i++) {
          dest = dest.concat(
            "<tr><td>" +
              response.bike[i].ID +
              "</td><td>" +
              response.bike[i].brand +
              "</td><td>" +
              response.bike[i].model +
              "</td><td>" +
              response.bike[i].utilisation +
              "</td><td>" +
              response.bike[i].electric +
              "</td><td>" +
              response.bike[i].frameType +
              "</td><td>" +
              Math.round(response.bike[i].price) +
              " €</td><td>" +
              response.bike[i].display +
              '<td><a href="#" class="text-green updatePortfolioClick" onclick="initializeUpdatePortfolioBike(\'' +
              response.bike[i].ID +
              '\')" data-target="#updatePortfolioBike" data-toggle="modal">Mettre à jour </a></td></tr>'
          );
        }
        document.getElementById(
          "portfolioBikesListing"
        ).innerHTML = dest.concat("</tbody></table>");
        displayLanguage();
        $("#portfolioBikeListing").DataTable({
          paging: false,
        });
      }
    },
  });
}
//FleetManager: Gérer le catalogue | Displays the bike information when "Mettre à jour" is pressed
function initializeUpdatePortfolioBike(ID) {
  $.ajax({
    url: "apis/Kameo/load_portfolio.php",
    type: "get",
    data: { action: "retrieve", ID: ID },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        $("#widget-updateCatalog-form input[name=ID]").val(response.ID);
        $("#widget-deletePortfolioBike-form [name=id]").val(response.ID);
        $("#widget-updateCatalog-form select[name=brand]").val(response.brand);
        $("#widget-updateCatalog-form input[name=model]").val(response.model);
        $("#widget-updateCatalog-form select[name=frame]").val(
          response.frameType
        );
        $("#widget-updateCatalog-form select[name=utilisation]").val(
          response.utilisation
        );
        $("#widget-updateCatalog-form select[name=electric]").val(
          response.electric
        );
        $("#widget-updateCatalog-form input[name=electric]").val(
          response.electric
        );
        $("#widget-updateCatalog-form input[name=buyPrice]").val(
          response.buyingPrice
        );
        $("#widget-updateCatalog-form input[name=price]").val(
          response.portfolioPrice
        );
        $("#widget-updateCatalog-form input[name=stock]").val(response.stock);
        $("#widget-updateCatalog-form input[name=link]").val(response.url);
        document.getElementsByClassName("bikeCatalogImage")[0].src =
          "images_bikes/" +
          response.brand.toLowerCase().replace(/ /g, "-") +
          "_" +
          response.model.toLowerCase().replace(/ /g, "-") +
          "_" +
          response.frameType.toLowerCase() +
          ".jpg";
        document.getElementsByClassName("bikeCatalogImageMini")[0].src =
          "images_bikes/" +
          response.brand.toLowerCase().replace(/ /g, "-") +
          "_" +
          response.model.toLowerCase().replace(/ /g, "-") +
          "_" +
          response.frameType.toLowerCase() +
          "_mini.jpg";
        $("#widget-updateCatalog-form input[name=file]").val("");
        $("#widget-updateCatalog-form input[name=fileMini]").val("");
        $("#widget-updateCatalog-form input[name=display]").prop(
          "checked",
          Boolean(response.display == "Y")
        );
      }
    },
  });
}

//FleetManager: Gérer le catalogue | Reset the form to add a bike to the catalogue
function initializeCreatePortfolioBike() {
  document.getElementById("widget-addCatalog-form").reset();
}
