
$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "customers" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        document.getElementById("counterClients").innerHTML =
          '<span data-speed="1" data-refresh-interval="4" data-to="' +
          response.companiesNumberClientOrProspect +
          '" data-from="0" data-  seperator="true">' +
          response.companiesNumberClientOrProspect +
          "</span>";
      }
    },
  });
});

$(".clientManagerClick").click(function (){
  get_company_listing();
  generateCompaniesGraphic(
    $(".form_date_start_client").val(),
    $(".form_date_end_client").val()
  );
});

function get_company_boxes(company) {
  $.ajax({
    url: "apis/Kameo/box_management.php",
    type: "get",
    data: { action: "list", company: company },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        var i = 0;
        var dest =
          '<a class="button small green button-3d rounded icon-right addBox" name="' +
          company +
          '" data-target="#boxManagement" data-toggle="modal" href="#"><span class="fr-inline"><i class="fa fa-plus"></i> Ajouter une borne</span></a>';
        if (response.boxesNumber > 0) {
          var temp =
            '<table class="table"><tbody><thead><tr><th>ID</th><th scope="col">Référence</th><th scope="col">Modèle</th><th scope="col">Facturation automatique</th><th>Début</th><th>Fin</th><th scope="col">Montant leasing</th><th></th></tr></thead>';
          dest = dest.concat(temp);
          while (i < response.boxesNumber) {
            if (
              response.box[i].automatic_billing == null ||
              response.box[i].automatic_billing == "N"
            ) {
              automatic_billing = "N";
            } else {
              automatic_billing = "Y";
            }

            if (response.box[i].amount == null) {
              amount = "0 €/mois";
            } else {
              amount = response.box[i].amount + " €/mois";
            }
            if (
              response.box[i].company != "KAMEO" &&
              response.box[i].company != "KAMEO VELOS TEST" &&
              response.box[i].start != null
            ) {
              var start =
                "<span>" + response.box[i].start.shortDate() + "</span>";
            } else if (
              response.box[i].company != "KAMEO" &&
              response.box[i].company != "KAMEO VELOS TEST" &&
              response.box[i].start == null
            ) {
              var start = '<span class="text-red">N/A</span>';
            } else if (
              response.box[i].company == "KAMEO" &&
              response.box[i].company == "KAMEO VELOS TEST" &&
              response.box[i].start == null
            ) {
              var start = "<span>N/A</span>";
            } else if (
              response.box[i].company == "KAMEO" &&
              response.box[i].company == "KAMEO VELOS TEST" &&
              response.box[i].start != null
            ) {
              var start =
                '<span class="text-red">' +
                response.box[i].start.shortDate() +
                "</span>";
            } else {
              var start = '<span class="text-red">ERROR</span>';
            }
            if (
              response.box[i].company != "KAMEO" &&
              response.box[i].company != "KAMEO VELOS TEST" &&
              response.box[i].end != null
            ) {
              var end = "<span>" + response.box[i].end.shortDate() + "</span>";
            } else if (
              response.box[i].company != "KAMEO" &&
              response.box[i].company != "KAMEO VELOS TEST" &&
              response.box[i].end == null
            ) {
              var end = '<span class="text-red">N/A</span>';
            } else if (
              response.box[i].company == "KAMEO" &&
              response.box[i].company == "KAMEO VELOS TEST" &&
              response.box[i].end == null
            ) {
              var end = "<span>N/A</span>";
            } else if (
              response.box[i].company == "KAMEO" &&
              response.box[i].company == "KAMEO VELOS TEST" &&
              response.box[i].end != null
            ) {
              var end =
                '<span class="text-red">' +
                response.box[i].end.shortDate() +
                "</span>";
            } else {
              var end = '<span class="text-red">ERROR</span>';
            }
            temp =
              '<tr><td><a href="#" class="text-green retrieveBox" data-target="#boxManagement" name="' +
              response.box[i].id +
              '" data-toggle="modal">' +
              response.box[i].id +
              "</a></td><td>" +
              response.box[i].reference +
              "</td><td>" +
              response.box[i].model +
              "</td><td>" +
              automatic_billing +
              "</td><td>" +
              start +
              "</td><td>" +
              end +
              "</td><td>" +
              amount +
              '</td><td><a href="#" class="text-green updateBox" data-target="#boxManagement" name="' +
              response.box[i].id +
              '" data-toggle="modal">Mettre à jour </a></th></tr>';
            dest = dest.concat(temp);
            i++;
          }
          var temp = "</tbody></table>";
          dest = dest.concat(temp);
        }
        $("#companyBoxes").html(dest);
        $(".addBox").click(function () {
          add_box(this.name);
        });
        $(".updateBox").click(function () {
          update_box(this.name);
        });
        $(".retrieveBox").click(function () {
          retrieve_box(this.name);
        });
      }
    },
  });
}

//FleetManager: Gérer les clients | Displays the companies graph by calling get_companies_listing.php and creating it
function generateCompaniesGraphic(dateStart, dateEnd) {
  $.ajax({
    url: "api/companies",
    type: "get",
    data: {
      action: "graphic",
      numberOfDays: "30",
      dateStart: dateStart,
      dateEnd: dateEnd,
    },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        var ctx = document.getElementById("myChart3").getContext("2d");
        if (myChart3 != undefined) myChart3.destroy();
        var presets = window.chartColors;

        var myChart3 = new Chart(ctx, {
          type: "line",
          data: {
            datasets: [
              {
                label: "Entreprises pas intéressées",
                borderColor: "#99111C",
                backgroundColor: "#f6856f",
                data: response.companiesNotInterested,
              },
              {
                label: "Entreprises en contact",
                borderColor: "#333333",
                backgroundColor: "#fcdb76",
                data: response.companiesContact,
              },
              {
                label: "Entreprises sous offre",
                borderColor: "#333333",
                backgroundColor: "#b6db4d",
                data: response.companiesOffer,
              },
              {
                label: "Entreprises sous offre signée",
                borderColor: "#333333",
                backgroundColor: "#96c220",
                data: response.companiesOfferSigned,
              },
            ],
            labels: response.dates,
          },

          options: {
            scales: {
              yAxes: [
                {
                  stacked: true,
                  beginAtZero: true,
                },
              ],
            },
            elements: {
              line: { tension: 0 },
            },
          },
        });
        myChart3.update();
      }
    },
  });
}

function get_company_listing() {
  $("#companyListingTable").dataTable({
    destroy: true,
    paging : false,
    ajax: {
      url: "api/companies",
      contentType: "application/json",
      type: "GET",
      data: {
        action: "list",
      },
    },
    sAjaxDataProp: "company",
    columns: [
      {
        title: "Référence",
        data: "internalReference",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html('<a href="#" class="internalReferenceCompany text-green" data-target="#companyDetails" data-toggle="modal" name="'+oData.ID+'">'+sData+'</a>');
        },
      },
      { title: "Nom de la société", data: "companyName"},
      { title: "Type", data: "type"},
      { title: "Nombre de vélos", data: "companyBikeNumber"},
      { title: "Audience", data: "audience"},
      {
        title: "Recherche de vélos",
        data: "BOOKING",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html((sData == "Y") ? '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>' : '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>');
        },
      },
      {
        title: "Plan cafétéria",
        data: "CAFETARIA",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html((sData == "Y") ? '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>' : '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>');
        },
      },
      {
        title: "Accès aux vélos",
        data: "bikeAccessStatus",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html((sData == "OK") ? '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>' : '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>');
        },
      },
      {
        title: "Accès aux bâtiments",
        data: "customerBuildingAccess",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html((sData == "OK") ? '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>' : '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>');
        },
      }
    ],
    order: [
      [0, "asc"]
    ],
  });
  $('#companyDetails').off();
  $('#companyDetails').on('shown.bs.modal', function(event){
    get_company_details($(event.relatedTarget).attr('name'));
  })

  $("#companyListingTable thead tr").clone(true).appendTo("#test thead");

  $("#companyListingTable thead tr:eq(1) th").each(function (i) {
    var title = $(this).text();
    $(this).html('<input type="text" placeholder="Search" />');

    $("input", this).on("keyup change", function () {
      if (table.column(i).search() !== this.value) {
        table.column(i).search(this.value).draw();
      }
    })
  });
}

function get_company_details(ID) {
  var internalReference;
  var companyID=ID;
  $.ajax({
    url: "apis/Kameo/companies/companies.php",
    type: "get",
    data: { action: "retrieve", ID: ID },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        $("#companyIdHidden").val(response.ID);
        $("#companyIdTemplate").val(response.ID);
        get_company_boxes(response.internalReference);
        get_company_contacts(response.ID);

        remove_contact_form(true);
        $("#widget-companyDetails-form input[name=ID]").val(response.ID);
        $("#widget-companyDetails-form select[name=audience]").val(response.audience);
        $("#widget-companyDetails-form input[name=companyName]").val(response.companyName);
        $("#widget-companyDetails-form input[name=companyStreet]").val(response.companyStreet);
        $("#widget-companyDetails-form input[name=companyZIPCode]").val(response.companyZIPCode);
        $("#widget-companyDetails-form input[name=companyTown]").val(response.companyTown);
        $("#widget-companyDetails-form input[name=companyVAT]").val(response.companyVAT);
        $("#widget-companyDetails-form input[name=internalReference]").val(response.internalReference);
        internalReference = response.internalReference;
        $("#widget-companyDetails-form select[name=type]").val(response.type);

        if (response.automaticBilling == "Y") {
          $("#widget-companyDetails-form input[name=billing]").prop(
            "checked",
            true
          );
        } else {
          $("#widget-companyDetails-form input[name=billing]").prop(
            "checked",
            false
          );
        }
        if (response.automaticStatistics == "Y") {
          $("#widget-companyDetails-form input[name=statistiques]").prop(
            "checked",
            true
          );
        } else {
          $("#widget-companyDetails-form input[name=statistiques]").prop(
            "checked",
            false
          );
        }
        if (response.booking == "Y") {
          $("#widget-companyDetails-form input[name=booking]").prop(
            "checked",
            true
          );
        } else {
          $("#widget-companyDetails-form input[name=booking]").prop(
            "checked",
            false
          );
        }
        if (response.assistance == "Y") {
          $("#widget-companyDetails-form input[name=assistance]").prop(
            "checked",
            true
          );
        } else {
          $("#widget-companyDetails-form input[name=assistance]").prop(
            "checked",
            false
          );
        }
        if (response.locking == "Y") {
          $("#widget-companyDetails-form input[name=locking]").prop(
            "checked",
            true
          );
        } else {
          $("#widget-companyDetails-form input[name=locking]").prop(
            "checked",
            false
          );
        }

        var i = 0;
        var dest =
        '<a class="button small green button-3d rounded icon-right addBikeAdmin" data-target="#bikeManagement" data-toggle="modal" href="#" name="' +
        response.ID +
        '"><i class="fa fa-plus"></i> Ajouter un vélo</a> <a class="button small green button-3d rounded icon-right" data-action="add" data-target="#externalBikeManagement" data-toggle="modal" href="#" name="' +
        response.ID +
        '"><i class="fa fa-plus"></i> Ajouter un vélo externe</a>';
        if (response.bikeNumber > 0) {
          var temp =
            '<table id="bike_company_listing" class="table table-condensed"  data-order=\'[[ 0, "asc" ]]\'><thead><tr><th scope="col">Référence</th><th scope="col">Modèle</th><th scope="col">Facturation automatique</th><th>Début</th><th>Fin</th><th scope="col">Montant location</th><th scope="col">Accès aux bâtiments</th><th></th></tr></thead><tbody>';
          dest = dest.concat(temp);
          while (i < response.bikeNumber) {
            if (response.bike[i].CONTRACT_TYPE != "order") {
              if (
                response.bike[i].COMPANY != "KAMEO" && response.bike[i].CONTRACT_START != null
              ) {
                var contractStart =
                  "<span>" +
                  response.bike[i].CONTRACT_START.shortDate() +
                  "</span>";
              } else if (
                response.bike[i].COMPANY != "KAMEO" && response.bike[i].CONTRACT_START == null
              ) {
                var contractStart = '<span class="text-red">N/A</span>';
              } else if (
                response.bike[i].COMPANY == "KAMEO" && response.bike[i].CONTRACT_START == null
              ) {
                var contractStart = "<span>N/A</span>";
              } else if (
                response.bike[i].COMPANY == "KAMEO" && response.bike[i].CONTRACT_START != null
              ) {
                var contractStart =
                  '<span class="text-red">' + response.bike[i].CONTRACT_START.shortDate() +"</span>";
              } else {
                var contractStart = '<span class="text-red">ERROR</span>';
              }
              if (
                response.bike[i].COMPANY != "KAMEO" && response.bike[i].CONTRACT_END != null
              ) {
                var contractEnd = "<span>" + response.bike[i].CONTRACT_END.shortDate() + "</span>";
              } else if (
                response.bike[i].COMPANY != "KAMEO" && response.bike[i].CONTRACT_END == null
              ) {
                var contractEnd = '<span class="text-red">N/A</span>';
              } else if (
                response.bike[i].COMPANY == "KAMEO" && response.bike[i].CONTRACT_END == null
              ) {
                var contractEnd = "<span>N/A</span>";
              } else if (
                response.bike[i].COMPANY == "KAMEO" && response.bike[i].CONTRACT_END != null
              ) {
                var contractEnd = '<span class="text-red">' + response.bike[i].CONTRACT_END.shortDate() + "</span>";
              } else {
                var contractEnd = '<span class="text-red">ERROR</span>';
              }

              if (response.bike[i].FRAME_NUMBER == null) {
                var frameNumber = "N/A " + response.bike[i].ID;
              } else {
                var frameNumber = response.bike[i].FRAME_NUMBER;
              }

              var temp =
                '<tr><td scope="row">' +
                frameNumber +
                "</td><td>" +
                response.bike[i].MODEL +
                "</td><td>" +
                response.bike[i].AUTOMATIC_BILLING +
                "</td><td>" +
                contractStart +
                "</td><td>" +
                contractEnd +
                "</td><td>" +
                response.bike[i].LEASING_PRICE +
                "</td><td>";
              dest = dest.concat(temp);

              var j = 0;
              while (j < response.bike[i].buildingNumber) {
                var temp = response.bike[i].building[j].buildingCode + "<br/>";
                dest = dest.concat(temp);
                j++;
              }
              if (response.bike[i].buildingNumber == 0) {
                var temp = '<span class="text-red">Non-défini</span>';
                dest = dest.concat(temp);
              }
              dest = dest.concat('<td><ins><a class="text-green text-green updateBikeAdmin" data-target="#bikeManagement" name="' +response.bike[i].ID +'" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>');
            }
            i++;
          }
          dest = dest.concat("</tbody></table>");
        }

        document.getElementById("companyBikes").innerHTML = dest;

        var dest="<thead><tr><th>ID</th><th>Marque</th><th>Modèle</th><th>Couleur</th><th></th></tr></thead>";
        dest+='<tbody>';
        response.externalBikes.forEach(function(bike){
          dest+='<tr><td>'+bike.ID+'</td><td>'+bike.BRAND+'</td><td>'+bike.MODEL+'</td><td>'+bike.COLOR+'</td><td><ins><a class="text-green text-green" data-target="#externalBikeManagement" data-action="update" data-id="' +bike.ID +'" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>'
        })
        dest+='</tbody>';

        $("#externalBikes").html(dest);
        $("#externalBikes").DataTable({
          destroy: true,
          searching: false,
          paging: false
        });



        $("#bike_company_listing").DataTable({
          searching: false,
          paging: false,
        });

        var i = 0;
        var dest = "";
        if (response.bikeNumber > 0) {
          var temp =
            '<table id="ordered_bike_company_listing" class="table table-condensed"  data-order=\'[[ 0, "asc" ]]\'><thead><tr><th scope="col">Référence</th><th scope="col">Modèle</th><th>Date commande</th><th>Date livraison</th><th scope="col">Numéro commande fournisseur</th><th></th></tr></thead><tbody>';
          dest = dest.concat(temp);
          while (i < response.bikeNumber) {
            if (response.bike[i].CONTRACT_TYPE == "order") {
              if (response.bike[i].DELIVERY_DATE == null) {
                var deliveryDate = "N/A ";
              } else {
                var deliveryDate = response.bike[i].DELIVERY_DATE.shortDate();
              }
              if (response.bike[i].BIKE_BUYING_DATE == null) {
                var bikeBuyingDate = "N/A ";
              } else {
                var bikeBuyingDate = response.bike[i].BIKE_BUYING_DATE.shortDate();
              }

              var temp =
                '<tr><td scope="row">' +
                response.bike[i].ID +
                "</td><td>" +
                response.bike[i].MODEL +
                "</td><td>" +
                bikeBuyingDate +
                "</td><td>" +
                deliveryDate +
                "</td><td>" +
                response.bike[i].ORDER_NUMBER +
                "</td>";
              dest = dest.concat(temp);

              dest = dest.concat(
                '<td><ins><a class="text-green text-green updateBikeAdmin" data-target="#bikeManagement" name="' +
                  response.bike[i].ID +
                  '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>'
              );
            }
            i++;
          }
          dest = dest.concat("</tbody></table>");
        }

        document.getElementById("companyBikesOrder").innerHTML = dest;

        $("#ordered_bike_company_listing").DataTable({
          searching: false,
          paging: false,
        });

        $(".updateBikeAdmin").click(function () {
          construct_form_for_bike_status_updateAdmin(this.name);
          $("#widget-bikeManagement-form input").attr("readonly", false);
          $("#widget-bikeManagement-form select").attr("readonly", false);
          $(".bikeManagementTitle").html("Modifier un vélo");
          $(".bikeManagementSend").removeClass("hidden");
        });

        $(".addBikeAdmin").click(function () {
          add_bike(this.name);
          $("#widget-bikeManagement-form input").attr("readonly", false);
          $("#widget-bikeManagement-form select").attr("readonly", false);
          $(".bikeManagementTitle").html("Ajouter un vélo");
          $(".bikeManagementSend").removeClass("hidden");
        });

        //Buildings management
        var dest =
          '<a class="button small green button-3d rounded icon-right" data-target="#addBuilding" data-toggle="modal" onclick="add_building(\'' +
          response.internalReference +
          '\')" href="#"><span class="fr-inline"><i class="fa fa-plus"></i> Ajouter un bâtiment</span></a>';

        if (response.buildingNumber > 0) {
          var i = 0;
          var temp =
            '<table class="table"><tbody><thead><tr><th scope="col">Référence</th><th scope="col">Description</th><th scope="col">Adresse</th></tr></thead>';
          dest = dest.concat(temp);
          while (i < response.buildingNumber) {
            var temp =
              '<tr><td scope="row">' +
              response.building[i].buildingReference +
              "</td><td>" +
              response.building[i].buildingFR +
              "</td><td>" +
              response.building[i].address +
              "</td></tr>";
            dest = dest.concat(temp);
            i++;
          }
          dest = dest.concat("</tbody></table>");
        }

        document.getElementById("companyBuildings").innerHTML = dest;


        update_company_users_list_admin(internalReference);


        //Offer Management

        var dest =
          '<a class="button small green button-3d rounded icon-right addOffer" name="' +
          internalReference +
          '" data-target="#offerManagement" data-toggle="modal" href="#"><i class="fa fa-plus"></i> Ajouter une offre</a>';
        dest +=
          '<a class="button small green button-3d rounded icon-right getTemplate" name="' +
          internalReference +
          '" href="#"><i class="fa fa-plus"></i>Nouveau Template Offre</a>';
        if (response.offerNumber + response.bikeContracts > 0) {
          var i = 0;
          var temp =
            '<h5 class="text-green">Contrats</h5><table class="table"><tbody><thead><tr><th scope="col">ID</th><th scope="col">PDF</th><th scope="col">Date</th><th scope="col">Titre</th><th scope="col">Chance</th><th>Montant</th><th>Debut</th><th>Fin</th><th>Statut</th><th></th></tr></thead>';
          dest = dest.concat(temp);
          while (i < response.bikeContracts) {
            if (response.offer[i].description) {
              var description = response.offer[i].description;
            } else {
              var description = "N/A";
            }
            if (response.offer[i].probability) {
              var probability = response.offer[i].probability;
            } else {
              var probability = "N/A";
            }
            if (response.offer[i].amount) {
              var amount = response.offer[i].amount;
            } else {
              var amount = "N/A";
            }
            if (response.offer[i].start) {
              var start = response.offer[i].start.shortDate();
            } else {
              var start = "N/A";
            }
            if (response.offer[i].end) {
              var end = response.offer[i].end.shortDate();
            } else {
              var end = "N/A";
            }
            if (response.offer[i].status) {
              var status = response.offer[i].status;
            } else {
              var status = "N/A";
            }

            var temp =
              "<tr><td>" +
              response.offer[i].id +
              "</td><td></td><td>Signé</td><td>" +
              description +
              "</td><td>" +
              probability +
              "</td><td>" +
              amount +
              "</td><td>" +
              start +
              "</td><td>" +
              end +
              "</td><td>" +
              status +
              "</td><td></td></tr>";
            dest = dest.concat(temp);
            i++;
          }

          while (i < response.offerNumber + response.bikeContracts) {
            if (!response.offer[i].date) {
              var date = "?";
            } else {
              var date = response.offer[i].date.shortDate();
            }
            if (!response.offer[i].start) {
              var start = "?";
            } else {
              var start = response.offer[i].start.shortDate();
            }
            if (!response.offer[i].end) {
              var end = "?";
            } else {
              var end = response.offer[i].end.shortDate();
            }

            if (response.offer[i].type == "leasing") {
              var amount = response.offer[i].amount + " €/mois";
            } else {
              var amount = response.offer[i].amount + " €";
            }
            if (response.offer[i].status) {
              var status = response.offer[i].status;
            } else {
              var status = "N/A";
            }

            if (
              response.offer[i].file != "" &&
              response.offer[i].file != null
            ) {
              var offerLink = "offres/" + response.offer[i].file;
              var temp =
                '<tr><td><a href="#" data-target="#offerManagement" data-action="retrieve" data-toggle="modal" data-id="' +
                response.offer[i].id +
                '">' +
                response.offer[i].id +
                "</a></td><td><a href=" +
                offerLink +
                ' target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td><td>' +
                date +
                "</td><td>" +
                response.offer[i].title +
                "</td><td>" +
                response.offer[i].probability +
                " %</td><td>" +
                amount +
                "</td><td>" +
                start +
                "</td><td>" +
                end +
                "</td><td>" +
                status +
                '</td><td><ins><a class="text-green offerManagement" data-action="update" data-target="#offerManagement" data-id="' +
                response.offer[i].id +
                '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>';
            } else {
              var temp =
                '<tr><td><a href="#" data-target="#offerManagement" data-toggle="modal" name="' +
                response.offer[i].id +
                '">' +
                response.offer[i].id +
                "</a></td><td></td><td>" +
                date +
                "</td><td>" +
                response.offer[i].title +
                "</td><td>" +
                response.offer[i].probability +
                " %</td><td>" +
                amount +
                "</td><td>" +
                start +
                "</td><td>" +
                end +
                "</td><td>" +
                status +
                '</td><td><ins><a class="text-green offerManagement updateOffer" data-target="#offerManagement" data-action="update" data-id="' +
                response.offer[i].id +
                '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>';
            }

            dest = dest.concat(temp);
            i++;
          }
          dest = dest.concat("</tbody></table>");
        }
        document.getElementById("companyContracts").innerHTML = dest;

        $("body").on("click", ".addOffer", function () {
          add_offer(this.name);
          $(".offerManagementSendButton").removeClass("hidden");
          $(".offerManagementSendButton").text("Ajouter");
        });


        var dest =
          '<table class="table table-condensed"><thead><tr><th>Type</th><th>ID</th><th>Société</th><th>Date d\'initiation</th><th>Montant (HTVA)</th><th>Communication</th><th>Envoi ?</th><th>Payée ?</th><th>Limite de paiement</th><th>Comptable ?</th></tr></thead><tbody>';

        var i = 0;
        while (i < response.billNumber) {
          if (response.bill[i].sentDate == null) {
            var sendDate = "N/A";
          } else {
            var sendDate = response.bill[i].sentDate.shortDate();
          }
          if (response.bill[i].paidDate == null) {
            var paidDate = "N/A";
          } else {
            var paidDate = response.bill[i].paidDate.shortDate();
          }
          if (response.bill[i].sent == "0") {
            var sent =
              '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>';
          } else {
            var sent =
              '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>';
          }
          if (response.bill[i].paid == "0") {
            var paid =
              '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>';
          } else {
            var paid =
              '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>';
          }

          if (response.bill[i].limitPaidDate && response.bill[i].paid == "0") {
            var dateNow = new Date();
            var dateLimit = new Date(response.bill[i].limitPaidDate);

            let month = String(dateLimit.getMonth() + 1);
            let day = String(dateLimit.getDate());
            let year = String(dateLimit.getFullYear());

            if (month.length < 2) month = "0" + month;
            if (day.length < 2) day = "0" + day;

            if (dateNow > dateLimit) {
              var paidLimit =
                '<span class="text-red">' +
                day +
                "/" +
                month +
                "/" +
                year.substr(2, 2) +
                "</span>";
            } else {
              var paidLimit =
                "<span>" +
                day +
                "/" +
                month +
                "/" +
                year.substr(2, 2) +
                "</span>";
            }
          } else if (response.bill[i].paid == "0") {
            var paidLimit = '<span class="text-red">N/A</span>';
          } else {
            var paidLimit =
              '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>';
          }

          if (response.bill[i].amountHTVA > 0) {
            var temp = '<tr><td class="text-green">IN</td>';
          } else if (response.bill[i].amountHTVA < 0) {
            var temp = '<tr><td class="text-red">OUT</td>';
          } else {
            var temp = "<tr>";
          }
          dest = dest.concat(temp);

          if (response.bill[i].fileName) {
            var temp =
              '<td><a href="factures/' +
              response.bill[i].fileName +
              '" target="_blank">' +
              response.bill[i].ID +
              "</a></td>";
          } else {
            var temp =
              '<td><a href="#" class="text-red">' +
              response.bill[i].ID +
              "</a></td>";
          }
          dest = dest.concat(temp);
          if (response.bill[i].amountHTVA > 0) {
            var temp = "<td>" + response.bill[i].company + "</a></td>";
            dest = dest.concat(temp);
          } else if (response.bill[i].amountHTVA < 0) {
            var temp =
              "<td>" + response.bill[i].beneficiaryCompany + "</a></td>";
            dest = dest.concat(temp);
          }
          var temp =
            "<td>" +
            response.bill[i].date.shortDate() +
            "</td><td>" +
            Math.round(response.bill[i].amountHTVA) +
            " €</td><td>" +
            response.bill[i].communication +
            "</td>";
          dest = dest.concat(temp);

          if (sent == "Y") {
            var temp = '<td class="text-green">' + sendDate + "</td>";
          } else {
            var temp = '<td class="text-red">' + sent + "</td>";
          }
          dest = dest.concat(temp);

          if (paid == "Y") {
            var temp = '<td class="text-green">' + paidDate + "</td>";
          } else {
            var temp = '<td class="text-red">' + paid + "</td>";
          }
          dest = dest.concat(temp);

          dest = dest.concat("<td>" + paidLimit + "</td>");

          if (response.bill[i].communicationSentAccounting == "1") {
            var temp = '<td class="text-green">OK</td>';
          } else {
            var temp = '<td class="text-red">KO</td>';
          }
          dest = dest.concat(temp);
          dest = dest.concat("</tr>");
          i++;
        }
        var temp = "</tbody></table>";
        dest = dest.concat(temp);
        document.getElementById("companyBills").innerHTML = dest;
        var classname = document.getElementsByClassName("updateBillingStatus");
        for (var i = 0; i < classname.length; i++) {
          classname[i].addEventListener(
            "click",
            function () {
              construct_form_for_billing_status_update(this.name);
            },
            false
          );
        }
      }
    },
    error: function (response) {
    },
  }).done(function () {
    $.ajax({
      url: "api/tasks",
      type: "get",
      data: { company: companyID, 'action': 'list'},
      success: function (response) {
        if (response.response == "error") {
          console.log(response.message);
        } else {
          var dest =
            '<a href="#" data-target="#taskManagement" name="' +
            internalReference +
            '" data-toggle="modal" data-action="add" class="button small green button-3d rounded icon-right"><i class="fa fa-plus"></i> Ajouter une action</a>';

          if (response.actionNumber > 0) {
            var i = 0;
            var temp =
              '<table class="table table-condensed"><tbody><thead><tr><th>ID</th><th>Date</th><th>Type</th><th>Titre</th><th>Owner</th><th>Statut</th><th></th></tr></thead> ';
            dest = dest.concat(temp);
            while (i < response.actionNumber) {
              if (!response.action[i].date_reminder) {
                $date_reminder = "N/A";
              } else {
                $date_reminder = response.action[i].date_reminder.substring(
                  0,
                  10
                );
              }
              var temp =
                '<tr><td><a href="#" data-target="#taskManagement" data-action="retrieve" data-toggle="modal" name="' +
                response.action[i].id +
                '">' +
                response.action[i].id +
                "</a></td><td>" +
                response.action[i].date.substring(0, 10) +
                "</td><td>" +
                response.action[i].type +
                "</td><td>" +
                response.action[i].title +
                "</td><td>" +
                response.action[i].ownerFirstName +
                " " +
                response.action[i].ownerName +
                "</td><td>" +
                response.action[i].status +
                '</td><td><ins><a class="text-green" data-target="#taskManagement" data-action="update" name="' +
                response.action[i].id +
                '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>';
              dest = dest.concat(temp);
              i++;
            }
            dest = dest.concat("</tbody></table>");
          }
          $("#action_company_log").html(dest);
        }
      },
    });
  });
}

function update_company_users_list_admin(company){
  $.ajax({
    url: "apis/Kameo/get_users_listing.php",
    type: "get",
    data: { company: company },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        //users management
        var dest =
          '<a class="button small green button-3d rounded icon-right" data-company="'+company+'" data-target="#addUserAdmin" data-toggle="modal" href="#"><i class="fa fa-plus"></i><?= L::generic_addUser; ?></a>';
        if (response.users.length > 0) {
          var i = 0;
          var temp =
            '<table class="table"><tbody><thead><tr><th scope="col">'+traduction.sidebar_last_name+'</th><th scope="col">'+traduction.sidebar_first_name+'</th><th scope="col">'+traduction.login_email+'</th><th>'+traduction.sidebar_phone+'</th><th></th><th></th></tr></thead>';
          dest = dest.concat(temp);
          while (i < response.usersNumber) {
            var temp =
              '<tr><td scope="row">' +
              response.users[i].name +
              "</td><td>" +
              response.users[i].firstName +
              "</td><td>" +
              response.users[i].email +
              "</td><td>" +
              response.users[i].phone +
              "</td><td><a href='#' data-target='#updateUserAdmin' data-toggle='modal' data-email='"+response.users[i].email+"' class='text-green updateUserAdmin'>Update</a></td><td><a href='#' class='text-red deleteUserAdmin' data-email='"+response.users[i].email+"' data-company='"+company+"'>Delete</a></td></tr>";
            dest = dest.concat(temp);
            i++;
          }
          dest = dest.concat("</tbody></table>");
        }else{
          document.getElementById("companyUsers").innerHTML = "";
        }
        document.getElementById("companyUsers").innerHTML = dest;
      }

      $('.updateUserAdmin').off();
      $('.updateUserAdmin').click(function(){
        var email = $(this).data("email");
        update_user_information_admin(email);
      });


      $('.deleteUserAdmin').off();
      $('.deleteUserAdmin').click(function(){
        var email = $(this).data("email");
        var company = $(this).data("company");
        $.ajax({
          url: "apis/Kameo/users/users.php",
          type: "post",
          data: { email: email, action : 'deleteUserAdmin' },
          success: function (response) {
            if (response.response == "error") {
              $.notify({
    						message: response.message
    					}, {
    						type: 'danger'
    					});
            }
            if (response.response == "success") {
              $.notify({
                message: response.message
              }, {
                type: 'success'
              });
              update_company_users_list_admin(company);
            }
          },
        });
      });
    }
  });
}


//Module gérer les clients ==> un client ==> modifier un contact
function edit_contact(contact) {
  return $.ajax({
    url: "api/companies",
    method: "post",
    data: {
      action: "editCompanyContact",
      id: $(contact).find(".contactIdHidden").val(),
      contactEmail: $(contact).find(".emailContact").val(),
      firstName: $(contact).find(".firstName").val(),
      lastName: $(contact).find(".lastName").val(),
      phone: $(contact).find(".phone").val(),
      function: $(contact).find(".fonction").val(),
      bikesStats: $(contact).find(".bikesStats").prop("checked"),
      companyId: $("#companyIdHidden").val()
    },
    success: function (response) {},
  });
}

//Module gérer les clients ==> un client ==> supprimer un de la base de donnée, ne touche pas le front end contact
function delete_contact(id) {
  return $.ajax({
    url: "api/companies",
    method: "post",
    data: {
      id: id,
      action: 'deleteContact'
    },
    success: function (response) {},
  });
}

//Module gérer les clients ==> un client ==> list les contacts
function get_company_contacts(ID) {
  $.ajax({
    url: "api/companies",
    method: "get",
    data: {
      action : 'getCompanyContacts',
      ID: ID
    },
    success: function (response) {
      initialize_company_contacts();
      var contactContent = `
    	<table class="table contactsTable">
    	<thead>
    	<tr>
    	<th><label>Email: </label></th><th><label>Nom: </label></th><th><label>Prénom: </label></th><th><label>Téléphone: </label></th><th>Fonction: </label></th><th>Type</th><th></th><th></th>
    	</tr>
    	</thead>
    	<tbody>`;
      if(response != null){
        response.forEach(function(contact){
          var contactId =
            contact.contactId != undefined ? contact.contactId : "";
          var email =
            contact.emailContact != undefined ? contact.emailContact : "";
          var lastName =
            contact.lastNameContact != undefined
              ? contact.lastNameContact
              : "";
          var firstName =
            contact.firstNameContact != undefined
              ? contact.firstNameContact
              : "";
          var phone = contact.phone != undefined ? contact.phone : "";
          var fonction =
            contact.fonction != undefined ? contact.fonction : "";
          var bikesStatsChecked = "";
          if (contact.bikesStats == "Y") {
            bikesStatsChecked = "checked";
          }
          if(contact.TYPE=='contact'){
            var type="contact";
          }else if(contact.TYPE=='billing'){
            var type = 'Destinataire Facture';
          }else if(contact.TYPE=="ccBilling"){
            var type = 'En copie pour facture';
          }else{
            var type = 'Error';
          }


          contactContent +=
            `
        	  <tr class="form-group">
        	  <td>
        	  <input type="text" class="form-control required emailContact" readonly="true"  name="contactEmail` +
                  contact.contactId +
                  `" id="contactEmail` +
                  contact.contactId +
                  `" value="` +
                  email +
                  `" required/>
        	  </td>
        	  <td>
        	  <input type="text" class="form-control required lastName" readonly="true"  name="contactNom` +
                  contact.contactId +
                  `" id="contactNom` +
                  contact.contactId +
                  `" value="` +
                  lastName +
                  `" required/>
        	  </td>
        	  <td>
        	  <input type="text" class="form-control required firstName" readonly="true" name="contactPrenom` +
                  contact.contactId +
                  `" id="contactPrenom` +
                  contact.contactId +
                  `" value="` +
                  firstName +
                  `" required/>
        	  </td>
        	  <td>
        	  <input type="tel" class="form-control phone" readonly="true"  name="contactPhone` +
                  contact.contactId +
                  `" id="contactPhone` +
                  contact.contactId +
                  `" value="` +
                  phone +
                  `"/>
        	  </td>
        	  <td>
        	  <input type="text" class="form-control fonction" readonly="true"  name="contactFunction` +
                  contact.contactId +
                  `" id="contactFunction` +
                  contact.contactId +
                  `" value="` +
                  fonction +
                  `"/>
        	  </td>
        	  <td>`+type+`
        	  </td>
        	  <td>
        	  <button class="modify button small green button-3d rounded icon-right glyphicon glyphicon-pencil" type="button"></button>
        	  </td>
        	  <td>
        	  <button class="delete button small red button-3d rounded icon-right glyphicon glyphicon-remove" type="button"></button>
        	  </td>
        	  <input type="hidden" class="contactIdHidden" name="contactId` +
                  contact.contactId +
                  `" id="contactId` +
                  contact.contactId +
                  `" value="` +
                  contactId +
                  `" />
        	  </tr>`;
        })
      }
      contactContent += "</tbody></table>";
      $(".clientContactZone").append(contactContent);
    },
  });
}

//Module gérer les clients ==> ajouter un batiment à un client
function add_building(company){
  $.ajax({
    url: "api/bikes",
    type: "get",
    data: { company: company, action:'list'},
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        var i = 0;
        var dest = "";
        while (i < response.bikeNumber) {
          temp =
            '<input type="checkbox" name="bikeAccess[]" checked value="' +
            response.bike[i].id +
            '">' +
            response.bike[i].frameNumber +
            " - " +
            response.bike[i].model +
            "<br>";
          dest = dest.concat(temp);
          i++;
        }
        document.getElementById("addBuilding_bikeListing").innerHTML = dest;
      }
    },
  });
  $.ajax({
    url: "apis/Kameo/get_users_listing.php",
    type: "post",
    data: { company: company },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        var i = 0;
        var dest = "";
        while (i < response.usersNumber) {
          temp =
            '<input type="checkbox" name="userAccess[]" checked value="' +
            response.users[i].email +
            '">' +
            response.users[i].firstName +
            " - " +
            response.users[i].name +
            "<br>";
          dest = dest.concat(temp);
          i++;
        }
        document.getElementById("addBuilding_usersListing").innerHTML = dest;
      }
    },
  });
  document.getElementById("widget-addBuilding-form-company").value = company;
}

//Module gérer les clients ==> un client ==> reset contact
function initialize_company_contacts() {
  $(".clientContactZone").html("");
}

//Module gérer les clients ==> un client ==> Modifie le front end quand tu delete un contact
function remove_contact_form(removeContent = false) {
  //retrait de l ajout
  $(".contactAddIteration").fadeOut();
  //ajout du statut disabled des input
  $(".contactAddIteration")
    .find("input")
    .each(function () {
      $(this).prop("disabled", true);
      if (removeContent) {
        $(this).val("");
      }
    });
  $(".removeContact")
    .addClass("glyphicon-plus")
    .addClass("green")
    .addClass("addContact")
    .removeClass("glyphicon-minus")
    .removeClass("red")
    .removeClass("removeContact");
}
