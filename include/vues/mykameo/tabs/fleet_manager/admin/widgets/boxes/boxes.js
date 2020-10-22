$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "boxes" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        document.getElementById("counterBoxes").innerHTML =
          '<span data-speed="1" data-refresh-interval="4" data-to="' +
          response.boxesNumberTotal +
          '" data-from="0" data-seperator="true">' +
          response.boxesNumberTotal +
          "</span>";
      }
    },
  });
  document.getElementsByClassName('boxManagerClick')[0].addEventListener('click', function() { list_boxes('*')}, false);  
});

function list_boxes(company) {
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
            '<table id="boxesListingTable" class="table"><thead><tr><th>ID</th><th scope="col"><span class="fr-inline">Société</span><span class="en-inline">Company</span><span class="nl-inline">Company</span></th><th scope="col"><span class="fr-inline">Référence</span><span class="en-inline">Reference</span><span class="nl-inline">Reference</span></th><th scope="col"><span class="fr-inline">Modèle</span><span class="en-inline">Model</span><span class="nl-inline">Model</span></th><th scope="col"><span class="fr-inline">Facturation</span><span class="en-inline">Automatic billing ?</span><span class="nl-inline">Automatic billing ?</span></th><th scope="col"><span class="fr-inline">Montant leasing</span><span class="en-inline">Leasing Price</span><span class="nl-inline">Leasing Price</span></th><th>Début de contrat</th><th>Fin de contrat</th><th></th></tr></thead><tbody>';
          dest = dest.concat(temp);

          while (i < response.boxesNumber) {
            if (
              response.box[i].automatic_billing == null ||
              response.box[i].automatic_billing == "N"
            ) {
              automatic_billing =
                '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>';
            } else {
              automatic_billing =
                '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>';
            }

            if (response.box[i].amount == null) {
              amount = "0 €/mois";
            } else {
              amount = response.box[i].amount + " €/mois";
            }

            if (
              response.box[i].start != null &&
              response.box[i].company != "KAMEO" &&
              response.box[i].company != "KAMEO VELOS TEST"
            ) {
              start =
                '<span class="text-green">' +
                response.box[i].start.shortDate() +
                "</span>";
            } else if (
              response.box[i].start == null &&
              response.box[i].company != "KAMEO" &&
              response.box[i].company != "KAMEO VELOS TEST"
            ) {
              start = '<span class="text-red">N/A</span>';
            } else if (
              response.box[i].start == null &&
              (response.box[i].company == "KAMEO" ||
                response.box[i].company == "KAMEO VELOS TEST")
            ) {
              start = '<span class="text-green">N/A</span>';
            } else if (
              response.box[i].start != null &&
              (response.box[i].company == "KAMEO" ||
                response.box[i].company == "KAMEO VELOS TEST")
            ) {
              start =
                '<span class="text-red">' +
                response.box[i].start.shortDate() +
                "</span>";
            } else {
              start = '<span class="text-red">ERROR</span>';
            }

            if (
              response.box[i].end &&
              response.box[i].company != "KAMEO" &&
              response.box[i].company != "KAMEO VELOS TEST"
            ) {
              end =
                '<span class="text-green">' +
                response.box[i].end.shortDate() +
                "</span>";
            } else if (
              response.box[i].end == null &&
              response.box[i].company != "KAMEO" &&
              response.box[i].company != "KAMEO VELOS TEST"
            ) {
              end = '<span class="text-red">N/A</span>';
            } else if (
              response.box[i].end == null &&
              (response.box[i].company == "KAMEO" ||
                response.box[i].company == "KAMEO VELOS TEST")
            ) {
              end = '<span class="text-green">N/A</span>';
            } else if (
              response.box[i].end != null &&
              (response.box[i].company == "KAMEO" ||
                response.box[i].company == "KAMEO VELOS TEST")
            ) {
              end =
                '<span class="text-red">' +
                response.box[i].end.shortDate() +
                "</span>";
            } else {
              end = '<span class="text-red">ERROR</span>';
            }

            temp =
              '<tr><td><a href="#" class="text-green retrieveBox" data-target="#boxManagement" name="' +
              response.box[i].id +
              '" data-toggle="modal">' +
              response.box[i].id +
              "</a></td><td>" +
              response.box[i].company +
              "</td><td>" +
              response.box[i].reference +
              "</td><td>" +
              response.box[i].model +
              "</td><td>" +
              automatic_billing +
              "</td><td>" +
              amount +
              "</td><td>" +
              start +
              "</td><td>" +
              end +
              '</td><td><a href="#" class="text-green updateBox" data-target="#boxManagement" name="' +
              response.box[i].id +
              '" data-toggle="modal">Mettre à jour </a></th></tr>';
            dest = dest.concat(temp);
            i++;
          }

          var temp = "</tbody></table>";
          dest = dest.concat(temp);
        }
        if (company == "*") {
          document.getElementById("counterBoxes").innerHTML =
            '<span data-speed="1" data-refresh-interval="4" data-to="' +
            response.boxesNumberTotal +
            '" data-from="0" data-seperator="true">' +
            response.boxesNumberTotal +
            "</span>";
        }

        $("#boxesListingSpan").html(dest);

        $("#boxesListingTable").DataTable({
          searching: false,
          paging: false,
          info: false,
        });

        displayLanguage();

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

function add_box(company) {
  document.getElementById("widget-boxManagement-form").reset();
  $("#widget-boxManagement-form input").attr("readonly", false);
  $("#widget-boxManagement-form textarea").attr("readonly", false);
  $("#widget-boxManagement-form select").attr("readonly", false);

  $("#widget-boxManagement-form input[name=action]").val("add");
  $("#widget-boxManagement-form-title").text("Ajouter une borne");

  $("#widget-boxManagement-form-send").text("Ajouter");
  $("#widget-boxManagement-form-send").removeClass("hidden");
  $("#widget-boxManagement-form select[name=company]").val(company);
}

function update_box(id) {
  retrieve_box(id);
  $("#widget-boxManagement-form-send").removeClass("hidden");

  $("#widget-boxManagement-form input").attr("readonly", false);
  $("#widget-boxManagement-form textarea").attr("readonly", false);
  $("#widget-boxManagement-form select").attr("readonly", false);
  $("#widget-boxManagement-form input[name=action]").val("update");

  $("#widget-boxManagement-form input[name=action]").val("update");
  $("#widget-boxManagement-form-title").text("Modifier une borne");
  $("#widget-boxManagement-form-send").text("Modifier");
}

function retrieve_box(id) {
  $("#widget-boxManagement-form-title").text("Informations de la borne");
  $("#widget-boxManagement-form-send").addClass("hidden");
  $("#widget-boxManagement-form input").attr("readonly", true);
  $("#widget-boxManagement-form textarea").attr("readonly", true);
  $("#widget-boxManagement-form select").attr("readonly", true);
  $("#widget-boxManagement-form div[name=key]").remove();

  $.ajax({
    url: "apis/Kameo/box_management.php",
    type: "get",
    data: { action: "retrieve", id: id },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        $("#widget-boxManagement-form input[name=id]").val(response.id);
        $("#widget-boxManagement-form input[name=reference]").val(
          response.reference
        );
        $("#widget-boxManagement-form select[name=boxModel]").val(
          response.model
        );
        $("#widget-boxManagement-form select[name=company]").val(
          response.company
        );
        $("#widget-boxManagement-form input[name=amount]").val(response.amount);
        $("#widget-boxManagement-form input[name=billingGroup]").val(
          response.billing_group
        );
        if (response.start) {
          $("#widget-boxManagement-form input[name=contractStart]").val(
            response.start.substr(0, 10)
          );
        } else {
          $("#widget-boxManagement-form input[name=contractStart]").val("");
        }
        if (response.end) {
          $("#widget-boxManagement-form input[name=contractEnd]").val(
            response.end.substr(0, 10)
          );
        } else {
          $("#widget-boxManagement-form input[name=contractEnd]").val("");
        }

        if (response.automatic_billing == "Y") {
          $("#widget-boxManagement-form input[name=billing]").prop(
            "checked",
            true
          );
        } else {
          $("#widget-boxManagement-form input[name=billing]").prop(
            "checked",
            false
          );
        }
        box_keys = 40;
        row = 0;
        var classe, md, range, size;

        if (box_keys == 5 || box_keys == 10) {
          range = 5;
          md ="2";
          size = "100%";
        }else{
          range = 10;
          md = "1";
          size = "70%";
        }

        for (let i = 0; i < box_keys; i++) {
          if (row == range || row == 0) {
            classe = "col-md-"+md+" col-md-offset-1";
            row = 0;
          }else{
            classe = "col-md-"+md;
          }
          if (typeof response.keys[i] !=='undefined' && response.keys[i].place != "-1" && response.keys[i].place == i+1) {
            $("#widget-boxManagement-form div[name=keys]").append('<div class="'+ classe + '" name="key">\
            <center><B>'+ response.keys[i].place +'</B></br><img src="images/key_in.png">\
            </br><p style="font-size:'+size+';"><B>'+response.keys[i].frame_number +'</B></p></center></div>');
          }else{
            $("#widget-boxManagement-form div[name=keys]").append('<div class="'+ classe + '" name="key">\
            <center><B>'+ (i + 1) +'</B></br><img src="images/key_out.png"></br><p style="font-size:'+size+';"><B>NO</B></p></center></div>');
          }
          row++;
          
          
        }
      }
    },
  });
}
