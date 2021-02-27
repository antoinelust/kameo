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
  document.getElementsByClassName('portfolioManagerClick')[0].addEventListener('click', function() { listPortfolioBikes()}, false);
});

//FleetManager: Gérer le catalogue | Displays the portfolio <table> by calling load_portfolio.php and creating it
function listPortfolioBikes(){
  $.ajax({
    url: "apis/Kameo/load_portfolio.php",
    type: "get",
    data: { action: "list" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        var dest =
          '<table class="table table-condensed" id="portfolioBikeListing"><h4 class="fr-inline text-green">Vélos du catalogue:</h4><h4 class="en-inline text-green">Portfolio bikes:</h4><h4 class="nl-inline text-green">Portfolio bikes:</h4><br/><a class="button small green button-3d rounded icon-right" data-target="#addPortfolioBike" data-toggle="modal" onclick="initializeCreatePortfolioBike()" href="#"><span class="fr-inline"><i class="fa fa-plus"></i> Ajouter un vélo</span></a><thead><tr><th>ID</th><th><span class="fr-inline">Marque</span><span class="en-inline">Brand</span><span class="nl-inline">Brand</span></th><th><span class="fr-inline">Modèle</span><span class="en-inline">Model</span><span class="nl-inline">Model</span></th><th><span class="fr-inline">Utilisation</span><span class="en-inline">Use</span><span class="nl-inline">Use</span></th><th><span class="fr-inline">Electrique ?</span><span class="en-inline">Electric</span><span class="nl-inline">Electric</span></th><th><span class="fr-inline">Cadre</span><span class="en-inline">Frame</span><span class="nl-inline">Frame</span></th><th><span class="fr-inline">Prix</span><span class="en-inline">Price</span><span class="nl-inline">Price</span></th><th>Afficher</th><th>Saison</th><th>XS</th><th>S</th><th>M</th><th>L</th><th>XL</th><th>Uni</th><th>Total</th><th></th></tr></thead><tbody>';
        for (i = 0; i < response.bikeNumber; i++) {

          var sizes= response.bike[i].sizes==null ? [""] : response.bike[i].sizes.split(",");

          dest = dest.concat(
            "<tr><td class='tooltipPortfolioBikes' rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='bottom' data-html='true' data-title=\"<div style='position:relative;overflow:auto'>"+
            "<img src='images_bikes/"+response.bike[i].ID+"_mini.jpg' /></div>"+
            "\"></i></sup>" +
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
              "</td><td>" +
              response.bike[i].season +
              "</td><td>"+
              (sizes.includes("XS") ? response.bike[i].stockXS : (response.bike[i].stockXS>0 ? ("<span class='text-red'>"+response.bike[i].stockXS+"</span>") : ""))+
              "</td><td>"+
              (sizes.includes("S") ? response.bike[i].stockS : (response.bike[i].stockS>0 ? ("<span class='text-red'>"+response.bike[i].stockS+"</span>") : ""))+
              "</td><td>"+
              (sizes.includes("M") ? response.bike[i].stockM : (response.bike[i].stockM>0 ? ("<span class='text-red'>"+response.bike[i].stockM+"</span>") : ""))+
              "</td><td>"+
              (sizes.includes("L") ? response.bike[i].stockL : (response.bike[i].stockL>0 ? ("<span class='text-red'>"+response.bike[i].stockL+"</span>") : ""))+
              "</td><td>"+
              (sizes.includes("XL") ? response.bike[i].stockXL : (response.bike[i].stockXL>0 ? ("<span class='text-red'>"+response.bike[i].stockXL+"</span>") : ""))+
              "</td><td>"+
              (sizes.includes("unique") ? response.bike[i].stockUni : (response.bike[i].stockUni>0 ? ("<span class='text-red'>"+response.bike[i].stockUni+"</span>") : ""))+
              "</td><td>"+response.bike[i].stockTotal+"</td><td><a href='#' class='text-green updatePortfolioClick' onclick='initializeUpdatePortfolioBike(\"" +
              response.bike[i].ID +
              "\")' data-target='#updatePortfolioBike' data-toggle='modal'>Mettre à jour </a></td></tr>"
          );
        }
        document.getElementById(
          "portfolioBikesListing"
        ).innerHTML = dest.concat("</tbody></table>");
        displayLanguage();

        $("#portfolioBikeListing thead tr").clone(true).appendTo("#portfolioBikeListing thead");

        $("#portfolioBikeListing thead tr:eq(1) th").each(function (i) {
          var title = $(this).text();
          $(this).html('<input style="width: 100%" type="text" />');

          $("input", this).on("keyup change", function () {
            if (table.column(i).search() !== this.value) {
              table.column(i).search(this.value).draw();
            }
          });
        });



        var table = $("#portfolioBikeListing").DataTable({
          orderCellsTop: true,
          fixedHeader: true,
          scrollX: false,
          paging: false,
          search: false
        });
        $(function () {
          $('.tooltipPortfolioBikes').tooltip({
            container: "body",
          })
        })

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
      if(response.sizes != null){
        var sizes = response.sizes.split(",");
        const selectValues = [1, 2];
        const select = document.getElementById('updateBikeSizes');

        /* Iterate options of select element */
        for (const option of document.querySelectorAll('#updateBikeSizes option')){
          /* Parse value to integer */
          const value = option.value;
          /* If option value contained in values, set selected attribute */
          if (sizes.indexOf(value) !== -1) {
            option.setAttribute('selected', 'selected');
          }
          /* Otherwise ensure no selected attribute on option */
          else {
            option.removeAttribute('selected');
          }
        }
      }


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

        /*if(response.utilisation == "Speedpedelec"){
          $("#licenseUpdate").removeClass("hidden");
        }else{
          $("#licenseUpdate").addClass("hidden");
        }

        $("#widget-updateCatalog-form select[name=utilisation]").change(function () {
          if($("#widget-updateCatalog-form select[name=utilisation]").val()=="Speedpedelec"){
            $("#licenseUpdate").removeClass("hidden");
          } else{
            $("#licenseUpdate").addClass("hidden");
          }
        });*/

        $("#widget-updateCatalog-form select[name=electric]").val(
          response.electric
        );
        $("#widget-updateCatalog-form input[name=electric]").val(
          response.electric
        );
        /*$("#widget-updateCatalog-form input[name=license]").val(
          response.license
        );*/
        $("#widget-updateCatalog-form select[name=season]").val(
          response.season
        );
        $("#widget-updateCatalog-form input[name=buyPrice]").val(
          response.buyingPrice
        );
        $("#widget-updateCatalog-form input[name=price]").val(
          response.portfolioPrice
        );
        $("#widget-updateCatalog-form input[name=stock]").val(response.stock);
        $("#widget-updateCatalog-form input[name=motor]").val(response.motor);
        $("#widget-updateCatalog-form input[name=battery]").val(response.battery);
        $("#widget-updateCatalog-form input[name=transmission]").val(response.transmission);
        $("#widget-updateCatalog-form input[name=priority]").val(response.priority);

        document.getElementsByClassName("bikeCatalogImage")[0].src =
          "images_bikes/" +
          response.img +
          ".jpg?date="+Date.now();;
        document.getElementsByClassName("bikeCatalogImageMini")[0].src =
          "images_bikes/" +
          response.img +
          "_mini.jpg?date="+Date.now();;
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
  $("#widget-addCatalog-form select").val('');
}
