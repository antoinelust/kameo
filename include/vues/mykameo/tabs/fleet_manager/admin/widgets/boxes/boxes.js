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
  document.getElementsByClassName('boxManagerClick')[0].addEventListener('click', function() { list_boxes_admin()}, false);
});

function list_boxes_admin(company) {
  $.ajax({
    url: "apis/Kameo/boxes/box_management.php",
    type: "get",
    data: { action: "list"},
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
            '<table id="boxesListingAdminTable" class="table"><thead><tr><th>ID</th><th scope="col"><span class="fr-inline">Société</span><span class="en-inline">Company</span><span class="nl-inline">Company</span></th><th scope="col"><span class="fr-inline">Référence</span><span class="en-inline">Reference</span><span class="nl-inline">Reference</span></th><th scope="col"><span class="fr-inline">Modèle</span><span class="en-inline">Model</span><span class="nl-inline">Model</span></th><th scope="col"><span class="fr-inline">Facturation</span><span class="en-inline">Automatic billing ?</span><span class="nl-inline">Automatic billing ?</span></th><th scope="col"><span class="fr-inline">Montant leasing</span><span class="en-inline">Leasing Price</span><span class="nl-inline">Leasing Price</span></th><th>Début de contrat</th><th>Fin de contrat</th><th></th></tr></thead><tbody>';
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
              '<tr><td><a href="#" class="text-green retrieveBoxAdmin" data-target="#boxManagementAdmin" name="' +
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
              '</td><td><a href="#" class="text-green updateBox" data-target="#boxManagementAdmin" name="' +
              response.box[i].id +
              '" data-toggle="modal">Mettre à jour </a></th></tr>';
            dest = dest.concat(temp);
            i++;
          }

          var temp = "</tbody></table>";
          dest = dest.concat(temp);
        }

        $("#boxesListingAdminSpan").html(dest);

        $("#boxesListingAdminTable").DataTable({
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
        $(".retrieveBoxAdmin").click(function () {
          retrieve_box_admin(this.name);
        });
      }
    },
  });
}

function add_box(company) {
  document.getElementById("widget_boxManagementAdmin-form").reset();
  $("#widget_boxManagementAdmin-form input").attr("readonly", false);
  $("#widget_boxManagementAdmin-form textarea").attr("readonly", false);
  $("#widget_boxManagementAdmin-form select").attr("readonly", false);

  $("#widget_boxManagementAdmin-form input[name=action]").val("add");
  $("#widget_boxManagementAdmin-form-title").text("Ajouter une borne");

  $("#widget_boxManagementAdmin-form-send").text("Ajouter");
  $("#widget_boxManagementAdmin-form-send").removeClass("hidden");
  $("#widget_boxManagementAdmin-form select[name=company]").val(company);
}

function update_box(id) {
  retrieve_box_admin(id);
  $("#widget_boxManagementAdmin-form-send").removeClass("hidden");

  $("#widget_boxManagementAdmin-form input").attr("readonly", false);
  $("#widget_boxManagementAdmin-form textarea").attr("readonly", false);
  $("#widget_boxManagementAdmin-form select").attr("readonly", false);
  $("#widget_boxManagementAdmin-form input[name=action]").val("update");

  $("#widget_boxManagementAdmin-form input[name=action]").val("update");
  $("#widget_boxManagementAdmin-form-title").text("Modifier une borne");
  $("#widget_boxManagementAdmin-form-send").text("Modifier");
}

function retrieve_box_admin(id) {
  $("#widget_boxManagementAdmin-form-title").text("Informations de la borne");
  $("#widget_boxManagementAdmin-form-send").addClass("hidden");
  $("#widget_boxManagementAdmin-form input").attr("readonly", true);
  $("#widget_boxManagementAdmin-form textarea").attr("readonly", true);
  $("#widget_boxManagementAdmin-form select").attr("readonly", true);
  $("#widget_boxManagementAdmin-form div[name=key]").remove();
  $("#widget_boxManagementAdmin-form div[name=bike]").remove();

  $.ajax({
    url: "apis/Kameo/boxes/box_management.php",
    type: "get",
    data: { action: "retrieve", id: id },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        $("#widget_boxManagementAdmin-form input[name=id]").val(response.id);
        $("#widget_boxManagementAdmin-form input[name=reference]").val(
          response.reference
        );
        $("#widget_boxManagementAdmin-form select[name=boxModel]").val(
          response.model
        );
        $("#widget_boxManagementAdmin-form select[name=company]").val(
          response.company
        );
        $("#widget_boxManagementAdmin-form input[name=amount]").val(response.amount);
        $("#widget_boxManagementAdmin-form input[name=billingGroup]").val(
          response.billing_group
        );
        if (response.start) {
          $("#widget_boxManagementAdmin-form input[name=contractStart]").val(
            response.start.substr(0, 10)
          );
        } else {
          $("#widget_boxManagementAdmin-form input[name=contractStart]").val("");
        }
        if (response.end) {
          $("#widget_boxManagementAdmin-form input[name=contractEnd]").val(
            response.end.substr(0, 10)
          );
        } else {
          $("#widget_boxManagementAdmin-form input[name=contractEnd]").val("");
        }

        if (response.automatic_billing == "Y") {
          $("#widget_boxManagementAdmin-form input[name=billing]").prop(
            "checked",
            true
          );
        } else {
          $("#widget_boxManagementAdmin-form input[name=billing]").prop(
            "checked",
            false
          );
        }


        // Placement des clés
        box_keys = parseInt(response.model.split("k")[0]);
        row = 0;
        var classe, md, range, size, space;

        if (box_keys == 5 || box_keys == 10) {
          range = 5;
          md ="2";
          size = "100%";
          space = "</Br></Br></Br>";
        }else{
          range = 10;
          md = "1";
          size = "70%";
          space = "</Br></Br></Br></Br></Br>";
        }

        place = 0;
        for (let i = 0; i < box_keys; i++) {

          if (row == range || row == 0) {
            classe = "col-md-"+md+" col-md-offset-1";
            row = 0;
          }else{
            classe = "col-md-"+md;
          }
          if (typeof response.keys_in[place] !=='undefined' && response.keys_in[place].place == i+1) {
            $("#widget_boxManagementAdmin-form div[name=keys]").append('<div class="'+ classe + '" name="key" style="height: 161px;" draggable="true" ondragstart="drag(event)" id="'+ response.keys_in[place].id + '_' + id +'">\
            <p><center><B>'+ response.keys_in[place].place +'</B></br><img draggable="false" src="images/key_in.png">\
            </br><p style="font-size:'+size+';"><B>'+response.keys_in[place].model +'</B></p></center></p></div>');
            place++;
          }else{
            $("#widget_boxManagementAdmin-form div[name=keys]").append('<div class="'+ classe + '" name="key" ondrop="drop(event, this)" ondragover="allowDrop(event)" id="'+ (i + 1) +'">\
            <p><center><B>'+ (i + 1) +'</B></br><img draggable="false" src="images/key_out2.png"></br><p style="font-size:'+size+';"><B>LIBRE'+ space +'</B></p></center></p></div>');
          }
          row++;
        }

        // Vélos en déplacement
        if(response.keys_out){
          response.keys_out.forEach(key => {
            $("#widget_boxManagementAdmin-form div[name=in]").before('<div class="col-md-4" name="bike" draggable="true" ondragstart="drag(event)" id="'+ key.id + '_' + id + '">\
            <img draggable="false" src="images_bikes/'+key.img+'_mini.jpg">\
            <p><center><B>'+ key.model + '</B><br>' + key.email + '</center></p></div>');
          });
        }
      }
    },
  });
}

function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev, target) {
  ev.preventDefault();
  var ids = ev.dataTransfer.getData("text");
  var id= ids.split("_")[0];
  var box_id= ids.split("_")[1];
  var place = target.id;
  if(!place){
    place = "-1";
  }

  $.ajax({
    url: "apis/Kameo/boxes/box_management.php",
    type: "post",
    data: { action: "switch", id: id, place: place},
    success: function (response) {
      if (response.response == "error") {
        $.notify(
          {
            message: response.message,
          },
          {
            type: "danger",
          }
        );
      }
      if (response.response == "success") {
        $.notify(
          {
            message: response.message,
          },
          {
            type: "success",
          }
        );
        retrieve_box_admin(box_id);
        document
          .getElementById("widget_boxManagementAdmin-form")
          .reset();
      }
    },
  });
}
