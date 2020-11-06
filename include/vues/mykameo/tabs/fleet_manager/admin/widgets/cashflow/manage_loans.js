// get customers bikes
function get_customers_bikes() {
  return $.ajax({
    url: "apis/Kameo/get_bikes_listing.php",
    type: "post",
    data: { admin: "Y", customersCompanies: "Y" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
    },
  });
}

// get bike details
function get_bike_details(bikeID) {
  return $.ajax({
    url: "apis/Kameo/get_bike_details.php",
    type: "post",
    data: { bikeID: bikeID },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
    },
  });
}

var bikesNumber = 0;

//création des variables
var bikes = [];
get_customers_bikes().done(function (response) {
  bikes = response.bike;
  if (bikes == undefined) {
    bikes = [];
    console.log("bikes => table vide");
  }

  //tableau bikes avec tout les champs
  var bikeModels =
    '<option hidden disabled selected value id ="bikeBrandModelSel" name="bikeBrandModelSel"></option>';

  //tri du tableau par marques
  bikes.sort(compare);

  //gestion du moins au lancement de la page
  checkMinus(".costsManagementBike", ".bikesNumber");

  //generation des Options

  //velo
  for (var i = 0; i < bikes.length; i++) {
    bikeModels +=
      '<option value="' +
      bikes[i].id +
      '">' +
      bikes[i].model +
      " - " +
      bikes[i].frameType +
      "</option>";
  }
  //a chaque modification du nombre de vélo
  //ajout
  $(".costsManagementBike .glyphicon-plus").on("click", function () {
    bikesNumber = $("#costsManagement").find(".bikesNumber").html() * 1 + 1;
    $("#costsManagement").find(".bikesNumber").html(bikesNumber);
    $("#bikesNumber").val(bikesNumber);

    //creation du div contenant
    $("#costsManagement")
      .find(".costsManagementBike tbody")
      .append(
        `<tr class="bikesNumberTable` +
          bikesNumber +
          ` bikeRow form-group">
      <td class="bLabel"></td>
      <td class="loanBikeID"></td>
      <td class="bikeBrandModel"></td>
      <td class="loanFrameNumber"></td>
      <td class="loanBrand"></td>
      <td class="bikepAchat"></td>
      </tr>`
      );

    //label selon la langue
    $("#costsManagement")
      .find(".bikesNumberTable" + bikesNumber + ">.bLabel")
      .append('<label class="fr">Vélo ' + bikesNumber + "</label>");
    /*$('#costsManagement').find('.bikesNumberTable'+bikesNumber+'>.bLabel').append('<span class="en">Bike '+ bikesNumber +'</span>');
      $('#costsManagement').find('.bikesNumberTable'+bikesNumber+'>.bLabel').append('<span class="nl">Vélo '+ bikesNumber +'</span>');*/

    // $("#costsManagement")
    //   .find(".bikesNumberTable" + bikesNumber + ">.loanBikeID")
    //   .append();

    $("#costsManagement")
      .find(".bikesNumberTable" + bikesNumber + ">.bikeBrandModel")
      .append(
        `<select name="bikeBrandModel` +
          bikesNumber +
          `" class="select` +
          bikesNumber +
          ` form-control required">` +
          bikeModels +
          `</select>`
      );

    //gestion du select du velo
    $(".costsManagementBike select").on("change", function () {
      var that = "." + $(this).attr("class").split(" ")[0];
      var id = $(that).val();
      //récupère le bon index même si le tableau est désordonné
      id = getIndex(bikes, id);

      $(that)
        .parents(".bikeRow")
        .find(".loanBikeID")
        .html(
          '<input readonly style="all: unset;" type="text" name="loanBikeID[]" value="' +
            $(that).val() +
            '"/>'
        );

      $(that)
        .parents(".bikeRow")
        .find(".loanFrameNumber")
        .html(
          '<input readonly style="all: unset;" type="text" name="loanFrameNumber[]" value="' +
            bikes[id].frameNumber +
            '"/>'
        );

      get_bike_details($(that).val()).done(function (response) {
        $(that)
          .find("#bikeBrandModelSel")
          .html(
            '<input readonly style="all: unset;" type="text" name="bikeBrandModelSel[]" value="' +
              response.modelCatalog +
              '"/>'
          );

        $(that)
          .parents(".bikeRow")
          .find(".loanBrand")
          .html(
            '<input readonly style="all: unset;" type="text" name="loanBrand[]" value="' +
              response.brand +
              '"/>'
          );

        $(that)
          .parents(".bikeRow")
          .find(".bikepAchat")
          .html(
            '<input readonly style="all: unset;" type="text" name="bikepAchat[]" value="' +
              response.catalogPrice +
              '"/>'
          );
      });

      // var pAchat = bikes[id].buyingPrice + "€ ";
      // $(that)
      //   .parents(".bikeRow")
      //   .find(".bikepAchat")
      //   .html(pAchat + ' <span class="text-red">(-)</span>');

      // update_elements_price();
    });
    checkMinus(".costsManagementBike", ".bikesNumber");
  });

  //retrait
  $(".costsManagementBike .glyphicon-minus").on("click", function () {
    bikesNumber = $("#costsManagement").find(".bikesNumber").html();
    if (bikesNumber > 0 && bikesNumber > loanBikesNumber) {
      $("#costsManagement")
        .find(".bikesNumber")
        .html(bikesNumber * 1 - 1);
      $("#bikesNumber").val(bikesNumber * 1 - 1);
      $("#costsManagement")
        .find(".bikesNumberTable" + bikesNumber)
        .slideUp()
        .remove();
      bikesNumber--;
    }
    checkMinus(".costsManagementBike", ".bikesNumber");
  });
});

//gestion du bouton moins et du tableau
function checkMinus(select, valueLocation) {
  if ($(select).find(valueLocation).html() == "0") {
    $(select).find(".glyphicon-minus").fadeOut();
    $(select).find(".hideAt0").hide();
  } else {
    $(select).find(".glyphicon-minus").fadeIn();
    $(select).find(".hideAt0").show();
  }
}

//tri de tableau d'objets via une propriété string_calendar
function compare(a, b) {
  // Use toUpperCase() to ignore character casing
  const varA = a.brand.toUpperCase();
  const varB = b.brand.toUpperCase();

  let comparison = 0;
  if (varA > varB) {
    comparison = 1;
  } else if (varA < varB) {
    comparison = -1;
  }
  return comparison;
}

//récupère l'index de l'item dont l'id correspond
function getIndex(table, id) {
  for (var i = 0; i < table.length; i++) {
    if (table[i].id == id) {
      return i;
    }
  }
}
