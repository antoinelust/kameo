window.addEventListener("DOMContentLoaded", function (event) {
  document.getElementById("clientManagement").classList.remove("hidden");
  document.getElementsByClassName('taskOwnerSelection')[0].addEventListener('change', function() { taskFilter()}, false);
  document.getElementsByClassName('taskOwnerSelection2')[0].addEventListener('change', function() { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);
  document.getElementsByClassName('numberOfDays')[0].addEventListener('change', function() { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);

});

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
    url: "apis/Kameo/get_companies_listing.php",
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
          $(nTd).html((sData == "OK") ? '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>' : '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>');
        },
      },
      {
        title: "Plan cafétéria",
        data: "CAFETARIA",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html((sData == "OK") ? '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>' : '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>');
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
      },
      { title: "Date mise à jour", data: "HEU_MAJ"}
    ],
    order: [
      [0, "asc"]
    ],
  });
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

function get_company_details(ID, getCompanyContacts = false) {
  var internalReference;
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
        if (getCompanyContacts == true) {
          get_company_contacts(response.ID);
        }

        remove_contact_form(true);
        $("#widget-companyDetails-form input[name=ID]").val(response.ID);
        $("#widget-companyDetails-form select[name=audience]").val(response.audience);
        document.getElementById("companyName").value = response.companyName;
        document.getElementById("companyStreet").value = response.companyStreet;
        document.getElementById("companyZIPCode").value =
          response.companyZIPCode;
        document.getElementById("companyTown").value = response.companyTown;
        document.getElementById("companyVAT").value = response.companyVAT;
        document.getElementById("widget_companyDetails_internalReference").value = response.internalReference;
        internalReference = response.internalReference;
        $("#widget-companyDetails-form select[name=type]").val(response.type);
        $("#widget-companyDetails-form input[name=email_billing]").val(
          response.emailContactBilling
        );
        $(
          "#widget-companyDetails-form input[name=firstNameContactBilling]"
        ).val(response.firstNameContactBilling);
        $("#widget-companyDetails-form input[name=lastNameContactBilling]").val(
          response.lastNameContactBilling
        );
        $("#widget-companyDetails-form input[name=phoneBilling]").val(
          response.phoneContactBilling
        );

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
          '"><span class="fr-inline"><i class="fa fa-plus"></i> Ajouter un vélo</span></a>';
        if (response.bikeNumber > 0) {
          var temp =
            '<table id="bike_company_listing" class="table table-condensed"  data-order=\'[[ 0, "asc" ]]\'><thead><tr><th scope="col">Référence</th><th scope="col">Modèle</th><th scope="col">Facturation automatique</th><th>Début</th><th>Fin</th><th scope="col">Montant location</th><th scope="col">Accès aux bâtiments</th><th>Mise à jour</th><th></th></tr></thead><tbody>';
          dest = dest.concat(temp);
          while (i < response.bikeNumber) {
            if (response.bike[i].contractType != "order") {
              if (
                response.bike[i].company != "KAMEO" &&
                response.bike[i].company != "KAMEO VELOS TEST" &&
                response.bike[i].contractStart != null
              ) {
                var contractStart =
                  "<span>" +
                  response.bike[i].contractStart.shortDate() +
                  "</span>";
              } else if (
                response.bike[i].company != "KAMEO" &&
                response.bike[i].company != "KAMEO VELOS TEST" &&
                response.bike[i].contractStart == null
              ) {
                var contractStart = '<span class="text-red">N/A</span>';
              } else if (
                response.bike[i].company == "KAMEO" &&
                response.bike[i].company == "KAMEO VELOS TEST" &&
                response.bike[i].contractStart == null
              ) {
                var contractStart = "<span>N/A</span>";
              } else if (
                response.bike[i].company == "KAMEO" &&
                response.bike[i].company == "KAMEO VELOS TEST" &&
                response.bike[i].contractStart != null
              ) {
                var contractStart =
                  '<span class="text-red">' +
                  response.bike[i].contractStart.shortDate() +
                  "</span>";
              } else {
                var contractStart = '<span class="text-red">ERROR</span>';
              }
              if (
                response.bike[i].company != "KAMEO" &&
                response.bike[i].company != "KAMEO VELOS TEST" &&
                response.bike[i].contractEnd != null
              ) {
                var contractEnd =
                  "<span>" +
                  response.bike[i].contractEnd.shortDate() +
                  "</span>";
              } else if (
                response.bike[i].company != "KAMEO" &&
                response.bike[i].company != "KAMEO VELOS TEST" &&
                response.bike[i].contractEnd == null
              ) {
                var contractEnd = '<span class="text-red">N/A</span>';
              } else if (
                response.bike[i].company == "KAMEO" &&
                response.bike[i].company == "KAMEO VELOS TEST" &&
                response.bike[i].contractEnd == null
              ) {
                var contractEnd = "<span>N/A</span>";
              } else if (
                response.bike[i].company == "KAMEO" &&
                response.bike[i].company == "KAMEO VELOS TEST" &&
                response.bike[i].contractEnd != null
              ) {
                var contractEnd =
                  '<span class="text-red">' +
                  response.bike[i].contractEnd.shortDate() +
                  "</span>";
              } else {
                var contractEnd = '<span class="text-red">ERROR</span>';
              }

              if (response.bike[i].frameNumber == null) {
                var frameNumber = "N/A " + response.bike[i].id;
              } else {
                var frameNumber = response.bike[i].frameNumber;
              }

              var temp =
                '<tr><td scope="row">' +
                frameNumber +
                "</td><td>" +
                response.bike[i].model +
                "</td><td>" +
                response.bike[i].facturation +
                "</td><td>" +
                contractStart +
                "</td><td>" +
                contractEnd +
                "</td><td>" +
                response.bike[i].leasingPrice +
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
              dest = dest.concat(
                '<td data-sort="' +
                  new Date(response.bike[i].heuMaj).getTime() +
                  '">' +
                  response.bike[i].heuMaj.shortDate() +
                  "</td>"
              );
              dest = dest.concat(
                '<td><ins><a class="text-green text-green updateBikeAdmin" data-target="#bikeManagement" name="' +
                  response.bike[i].id +
                  '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>'
              );
            }
            i++;
          }
          dest = dest.concat("</tbody></table>");
        }

        document.getElementById("companyBikes").innerHTML = dest;

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
            if (response.bike[i].contractType == "order") {
              if (response.bike[i].frameNumber == null) {
                var frameNumber = "N/A - " + response.bike[i].id;
              } else {
                var frameNumber = response.bike[i].frameNumber;
              }
              if (response.bike[i].deliveryDate == null) {
                var deliveryDate = "N/A ";
              } else {
                var deliveryDate = response.bike[i].deliveryDate.shortDate();
              }
              if (response.bike[i].bikeBuyingDate == null) {
                var bikeBuyingDate = "N/A ";
              } else {
                var bikeBuyingDate = response.bike[
                  i
                ].bikeBuyingDate.shortDate();
              }

              var temp =
                '<tr><td scope="row">' +
                frameNumber +
                "</td><td>" +
                response.bike[i].model +
                "</td><td>" +
                bikeBuyingDate +
                "</td><td>" +
                deliveryDate +
                "</td><td>" +
                response.bike[i].orderNumber +
                "</td>";
              dest = dest.concat(temp);

              dest = dest.concat(
                '<td><ins><a class="text-green text-green updateBikeAdmin" data-target="#bikeManagement" name="' +
                  response.bike[i].id +
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
          '<a class="button small green button-3d rounded icon-right offerManagement addOffer" name="' +
          internalReference +
          '" data-target="#offerManagement" data-toggle="modal" href="#"><i class="fa fa-plus"></i> Ajouter une offre</a>';
        dest +=
          '<a class="button small green button-3d rounded icon-right offerManagement getTemplate" name="' +
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
                '<tr><td><a href="#" class="retrieveOffer" data-target="#offerManagement" data-toggle="modal" name="' +
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
                '</td><td><ins><a class="text-green offerManagement updateOffer" data-target="#offerManagement" name="' +
                response.offer[i].id +
                '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>';
            } else {
              var temp =
                '<tr><td><a href="#" class="retrieveOffer" data-target="#offerManagement" data-toggle="modal" name="' +
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
                '</td><td><ins><a class="text-green offerManagement updateOffer" data-target="#offerManagement" name="' +
                response.offer[i].id +
                '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>';
            }

            dest = dest.concat(temp);
            i++;
          }
          dest = dest.concat("</tbody></table>");
        }
        document.getElementById("companyContracts").innerHTML = dest;

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

        $(".retrieveOffer").click(function () {
          retrieve_offer(this.name, "retrieve");
        });

        $(".updateOffer").click(function () {
          retrieve_offer(this.name, "update");
        });
        $("body").on("click", ".addOffer", function () {
          add_offer(this.name);
          $(".offerManagementSendButton").removeClass("hidden");
          $(".offerManagementSendButton").text("Ajouter");
        });
      }
    },
    error: function (response) {
    },
  }).done(function () {
    $.ajax({
      url: "apis/Kameo/action_company.php",
      type: "get",
      data: { company: internalReference},
      success: function (response) {
        if (response.response == "error") {
          console.log(response.message);
        } else {
          var dest =
            '<a href="#" data-target="#taskManagement" name="' +
            internalReference +
            '" data-toggle="modal" class="button small green button-3d rounded icon-right addTask"><i class="fa fa-plus"></i> Ajouter une action</a>';

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
                '<tr><td><a href="#" class="retrieveTask" data-target="#taskManagement" data-toggle="modal" name="' +
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
                '</td><td><ins><a class="text-green updateAction" data-target="#updateAction" name="' +
                response.action[i].id +
                '" data-toggle="modal" href="#">Mettre à jour</a></ins></td></tr>';
              dest = dest.concat(temp);
              i++;
            }
            dest = dest.concat("</tbody></table>");
          }

          $("#action_company_log").html(dest);

          $(".retrieveTask").click(function () {
            retrieve_task(this.name, "retrieve");
            $(".taskManagementSendButton").addClass("hidden");
          });

          $(".updateTask").click(function () {
            update_task(this.name, "update");
          });
          $(".addTask").click(function () {
            add_task(this.name);
            $(".taskManagementSendButton").removeClass("hidden");
            $(".taskManagementSendButton").text("Ajouter");
          });

          var classname = document.getElementsByClassName("updateAction");
          for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener(
              "click",
              function () {
                construct_form_for_action_update(this.name);
              },
              false
            );
          }
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
          '<a class="button small green button-3d rounded icon-right addUserAdmin" data-target="#addUserAdmin" data-toggle="modal" href="#"><i class="fa fa-plus"></i><?= L::generic_addUser; ?></a>';
        if (response.usersNumber > 0) {
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
      $('.addUserAdmin').off();
      $('.addUserAdmin').click(function() {
        create_userAdmin();
      });


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

//Suppression d'une offre Pdf
$("body").on("click", ".deletePdfOffer", function (e) {
  //empèche le comportement normal du lien
  e.preventDefault();
  id = $(this).parents("tr").find("td:first").html();
  file = $(this).parents("tr").find("td a").attr("href");
  that = $(this);
  if (
    confirm(
      "Êtes-vous sur de vouloir supprimer ce PDF ? Cette action est irréversible."
    )
  ) {
    $.ajax({
      url: "apis/Kameo/delete_pdf_offer.php",
      method: "post",
      data: { id: id, file: file },
      success: function (response) {
        if (response.response == true) {
          $(that)
            .parents("tr")
            .slideUp("", function () {
              $(this).remove();
            });
        } else {
          console.log(response);
        }
      },
    });
  }
});


function retrieve_offer(ID, action) {
  $.ajax({
    url: "apis/Kameo/offer_management.php",
    type: "get",
    data: { ID: ID, action: "retrieve" },
    success: function (response) {
      $("#offerManagementPDF").attr("data", "");

      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        if (action == "retrieve") {
          $("#widget-offerManagement-form input").attr("readonly", true);
          $("#widget-offerManagement-form textarea").attr("readonly", true);
          $("#widget-offerManagement-form select").attr("readonly", true);
        } else {
          $("#widget-offerManagement-form input").attr("readonly", false);
          $("#widget-offerManagement-form textarea").attr("readonly", false);
          $("#widget-offerManagement-form select").attr("readonly", false);
        }

        $("#widget-offerManagement-form input[name=title]").val(response.title);
        $("#widget-offerManagement-form textarea[name=description]").val(
          response.description
        );
        $("#widget-offerManagement-form select[name=type]").val(response.type);
        $("#widget-offerManagement-form select[name=status]").val(
          response.status
        );
        $("#widget-offerManagement-form input[name=margin]").val(
          response.margin
        );
        $("#widget-offerManagement-form input[name=probability]").val(
          response.probability
        );
        $("#widget-offerManagement-form input[name=company]").val(
          response.company
        );
        $("#widget-offerManagement-form input[name=action]").val(action);
        $("#widget-offerManagement-form input[name=ID]").val(ID);

        $("#thickBoxProductLists").empty();
        var i = 0;
        if (response.itemsNumber > 0) {
          while (i < response.itemsNumber) {
            if (response.item[i].type == "box") {
              $("#offerManagementDetails").append(
                "<li>1 borne " +
                  response.item[i].model +
                  " au prix de " +
                  response.item[i].locationPrice +
                  " €/mois et un coût d'installation de " +
                  response.item[i].installationPrice +
                  " €</a></li>"
              );
            } else {
              $("#offerManagementDetails").append(
                "<li>1 vélo " +
                  response.item[i].brand +
                  " " +
                  response.item[i].model +
                  " au prix de " +
                  response.item[i].locationPrice +
                  " €/mois</a></li>"
              );
            }
            i++;
          }
        } else {
        }

        if (
          $("#widget-offerManagement-form select[name=type]").val() == "achat"
        ) {
          $("#widget-offerManagement-form input[name=start]").attr(
            "readonly",
            true
          );
          $("#widget-offerManagement-form input[name=end]").attr(
            "readonly",
            true
          );
          $("#widget-offerManagement-form input[name=start]").val("");
          $("#widget-offerManagement-form input[name=end]").val("");
        } else {
          if (action != "retrieve") {
            $("#widget-offerManagement-form input[name=start]").attr(
              "readonly",
              false
            );
            $("#widget-offerManagement-form input[name=end]").attr(
              "readonly",
              false
            );
          }

          if (response.date) {
            $("#widget-offerManagement-form input[name=date]").val(
              response.date.substring(0, 10)
            );
          } else {
            $("#widget-offerManagement-form input[name=date]").val("");
          }
          if (response.start) {
            $("#widget-offerManagement-form input[name=start]").val(
              response.start.substring(0, 10)
            );
          } else {
            $("#widget-offerManagement-form input[name=start]").val("");
          }
          if (response.end) {
            $("#widget-offerManagement-form input[name=end]").val(
              response.end.substring(0, 10)
            );
          } else {
            $("#widget-offerManagement-form input[name=end]").val("");
          }
        }

        if (response.amount) {
          $("#widget-offerManagement-form input[name=amount]").val(
            response.amount
          );
        }

        $("#offerManagement").on("shown.bs.modal", function () {
          if (response.file != null && response.file != "") {
            $(".offerManagementPDF").removeClass("hidden");
            $("#offerManagementPDF").attr(
              "data",
              "offres/" + response.file + ".pdf"
            );
          } else {
            $(".offerManagementPDF").addClass("hidden");
            $("#offerManagementPDF").attr("data", "");
          }
        });
      }
    },
  });
}
//Module gérer les clients ==> id d'un client ==> ajouter une offre
function add_offer(company) {
  $("#companyHiddenOffer").val(company);
  $("#widget-offerManagement-form select[name=type]").val("leasing");
  $("#widget-offerManagement-form input[name=action]").val("add");
  $("#widget-offerManagement-form input").attr("readonly", false);
  $("#widget-offerManagement-form textarea").attr("readonly", false);
  $("#widget-offerManagement-form select").attr("readonly", false);
  document.getElementById("widget-offerManagement-form").reset();
}

//Module gérer les clients ==> un client ==> modifier un contact
function edit_contact(contact) {
  return $.ajax({
    url: "apis/Kameo/edit_company_contact.php",
    method: "post",
    data: {
      id: $(contact).find(".contactIdHidden").val(),
      contactEmail: $(contact).find(".emailContact").val(),
      firstName: $(contact).find(".firstName").val(),
      lastName: $(contact).find(".lastName").val(),
      phone: $(contact).find(".phone").val(),
      function: $(contact).find(".fonction").val(),
      bikesStats: $(contact).find(".bikesStats").prop("checked"),
      companyId: $("#companyIdHidden").val(),
      email: email,
    },
    success: function (response) {},
  });
}

//Module gérer les clients ==> un client ==> supprimer un de la base de donnée, ne touche pas le front end contact
function delete_contact(contact, id) {
  return $.ajax({
    url: "apis/Kameo/delete_company_contact.php",
    method: "post",
    data: {
      id: id,
    },
    success: function (response) {},
  });
}

//Module gérer les clients ==> un client ==> list les contacts
function get_company_contacts(ID) {
  $.ajax({
    url: "apis/Kameo/get_company_contact.php",
    method: "post",
    data: { ID: ID },
    success: function (response) {
      initialize_company_contacts();
      var contactContent = `
	<table class="table contactsTable">
	<thead>
	<tr>
	<th><label class="fr">Email: </label><label class="en">Email: </label><label class="nl">Email: </label></th>
	<th><label class="fr">Nom: </label><label class="en">Lastname: </label><label class="nl">Lastname: </label></th>
	<th><label class="fr">Prénom: </label><label class="en">Firstname: </label><label class="nl">Firstname: </label></th>
	<th><label class="fr">Téléphone: </label><label class="en">Phone: </label><label class="nl">Phone: </label></th>
	<th><label class="fr">Fonction: </label><label class="en">Function: </label><label class="nl">Function: </label></th>
	<th><label class="fr">Statistiques vélos: </label><label class="en">Bikes stats: </label><label class="nl">Bikes stats: </label></th>
	<th></th>
	<th></th>
	</tr>
	</thead>
	<tbody>`;
      nbContacts = response.length;
      for (var i = 0; i < response.length; i++) {
        var contactId =
          response[i].contactId != undefined ? response[i].contactId : "";
        var email =
          response[i].emailContact != undefined ? response[i].emailContact : "";
        var lastName =
          response[i].lastNameContact != undefined
            ? response[i].lastNameContact
            : "";
        var firstName =
          response[i].firstNameContact != undefined
            ? response[i].firstNameContact
            : "";
        var phone = response[i].phone != undefined ? response[i].phone : "";
        var fonction =
          response[i].fonction != undefined ? response[i].fonction : "";
        var bikesStatsChecked = "";
        if (response[i].bikesStats == "Y") {
          bikesStatsChecked = "checked";
        }
        contactContent +=
          `
	  <tr class="form-group">
	  <td>
	  <input type="text" class="form-control required emailContact" readonly="true"  name="contactEmail` +
          response[i].contactId +
          `" id="contactEmail` +
          response[i].contactId +
          `" value="` +
          email +
          `" required/>
	  </td>
	  <td>
	  <input type="text" class="form-control required lastName" readonly="true"  name="contactNom` +
          response[i].contactId +
          `" id="contactNom` +
          response[i].contactId +
          `" value="` +
          lastName +
          `" required/>
	  </td>
	  <td>
	  <input type="text" class="form-control required firstName" readonly="true" name="contactPrenom` +
          response[i].contactId +
          `" id="contactPrenom` +
          response[i].contactId +
          `" value="` +
          firstName +
          `" required/>
	  </td>
	  <td>
	  <input type="tel" class="form-control phone" readonly="true"  name="contactPhone` +
          response[i].contactId +
          `" id="contactPhone` +
          response[i].contactId +
          `" value="` +
          phone +
          `"/>
	  </td>
	  <td>
	  <input type="text" class="form-control fonction" readonly="true"  name="contactFunction` +
          response[i].contactId +
          `" id="contactFunction` +
          response[i].contactId +
          `" value="` +
          fonction +
          `"/>
	  </td>
	  <td>
	  <input type="checkbox" class="form-control bikesStats" readonly="true"  name="contactBikesStats` +
          response[i].contactId +
          `" id="contactBikesStats` +
          response[i].contactId +
          `" value="bikesStats" ` +
          bikesStatsChecked +
          `/>
	  </td>
	  <td>
	  <button class="modify button small green button-3d rounded icon-right glyphicon glyphicon-pencil" type="button"></button>
	  </td>
	  <td>
	  <button class="delete button small red button-3d rounded icon-right glyphicon glyphicon-remove" type="button"></button>
	  </td>
	  <input type="hidden" class="contactIdHidden" name="contactId` +
          response[i].contactId +
          `" id="contactId` +
          response[i].contactId +
          `" value="` +
          contactId +
          `" />
	  </tr>`;
      }
      contactContent += "</tbody></table>";
      $(".clientContactZone").append(contactContent);
    },
  });
}

//Module gérer les clients ==> ajouter un batiment à un client
function add_building(company) {
  $.ajax({
    url: "apis/Kameo/get_bikes_listing.php",
    type: "post",
    data: { company: company },
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
