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

  var logBoxes;

  document.getElementsByClassName('boxManagerClick')[0].addEventListener('click', function() {
    list_boxes_admin();
    getLogsBoxes();
    logBoxes = setInterval(function(){ getLogsBoxes() }, 3000);
    var objDiv = document.getElementById("logsBoxes");
    objDiv.scrollTop = objDiv.scrollHeight;
  }, false);

  $("#boxesListingAdmin").on("hidden.bs.modal", function () {
    clearInterval(logBoxes);
  });

});

function getLogsBoxes () {
  var url = "apis/Kameo/lock/logs/logs_boxes.log";
  var xmlhttp = new XMLHttpRequest;
  xmlhttp.open ("GET", url, false);    // synchron
  xmlhttp.send (null);
  var data = xmlhttp.response+"<br><br>";
  document.getElementById("logsBoxes").innerHTML=data.replace(/(\r\n|\n|\r)/g,"<br />");
}


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
  document.getElementById("widget-boxManagementAdmin-form").reset();
  $("#widget-boxManagementAdmin-form input").attr("readonly", false);
  $("#widget-boxManagementAdmin-form textarea").attr("readonly", false);
  $("#widget-boxManagementAdmin-form select").attr("readonly", false);
  $("#widget-boxManagementAdmin-form input[name=action]").val("add");
  $("#widget-boxManagementAdmin-form-title").text("Ajouter une borne");
  $("#widget-boxManagementAdmin-form-send").text("Ajouter");
  $("#widget-boxManagementAdmin-form-send").removeClass("hidden");
  $("#widget-boxManagementAdmin-form select[name=company]").val(company);
}

function update_box(id) {
  retrieve_box_admin(id);
  $("#widget-boxManagementAdmin-form-send").removeClass("hidden");
  $("#widget-boxManagementAdmin-form input").attr("readonly", false);
  $("#widget-boxManagementAdmin-form textarea").attr("readonly", false);
  $("#widget-boxManagementAdmin-form select").attr("readonly", false);
  $("#widget-boxManagementAdmin-form input[name=action]").val("update");
  $("#widget-boxManagementAdmin-form input[name=action]").val("update");
  $("#widget-boxManagementAdmin-form-title").text("Modifier une borne");
  $("#widget-boxManagementAdmin-form-send").text("Modifier");
  $("#widget-boxManagementAdmin-form div[name=in]").removeClass("hidden");
}

function retrieve_box_admin(id) {
  $("#widget-boxManagementAdmin-form-title").text("Informations de la borne");
  $("#widget-boxManagementAdmin-form-send").addClass("hidden");
  $("#widget-boxManagementAdmin-form input").attr("readonly", true);
  $("#widget-boxManagementAdmin-form textarea").attr("readonly", true);
  $("#widget-boxManagementAdmin-form select").attr("readonly", true);
  $("#widget-boxManagementAdmin-form div[name=key]").remove();
  $("#widget-boxManagementAdmin-form div[name=bike]").remove();
  $("#widget-boxManagementAdmin-form div[name=in]").addClass("hidden");

  $.ajax({
    url: "apis/Kameo/boxes/box_management.php",
    type: "get",
    data: { action: "retrieve", id: id },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        $("#widget-boxManagementAdmin-form input[name=id]").val(response.id);
        $("#widget-boxManagementAdmin-form input[name=reference]").val(
          response.reference
        );
        $("#widget-boxManagementAdmin-form select[name=boxModel]").val(
          response.model
        );
        $("#widget-boxManagementAdmin-form select[name=company]").val(
          response.company
        );
        $("#widget-boxManagementAdmin-form input[name=amount]").val(response.amount);
        $("#widget-boxManagementAdmin-form input[name=billingGroup]").val(
          response.billing_group
        );
        if (response.start) {
          $("#widget-boxManagementAdmin-form input[name=contractStart]").val(
            response.start.substr(0, 10)
          );
        } else {
          $("#widget-boxManagementAdmin-form input[name=contractStart]").val("");
        }
        if (response.end) {
          $("#widget-boxManagementAdmin-form input[name=contractEnd]").val(
            response.end.substr(0, 10)
          );
        } else {
          $("#widget-boxManagementAdmin-form input[name=contractEnd]").val("");
        }

        if (response.automatic_billing == "Y") {
          $("#widget-boxManagementAdmin-form input[name=billing]").prop(
            "checked",
            true
          );
        } else {
          $("#widget-boxManagementAdmin-form input[name=billing]").prop(
            "checked",
            false
          );
        }


        // Placement des clés
        box_keys = parseInt(response.model.split("k")[0]);
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

        place = 0;
        for (let i = 0; i < box_keys; i++) {

          if (row == range || row == 0) {
            var new_row = document.createElement('div');
            new_row.className = "row";
            classe = "col-md-"+md+" col-md-offset-1";
            row = 0;
          }else{
            classe = "col-md-"+md;
          }
          if (typeof response.keys_in[place] !=='undefined' && response.keys_in[place].place == i+1) {
            var new_div=document.createElement('div');
            new_div.className = classe;
            new_div.style.textAlign = "center";
            new_div.setAttribute('name', 'key');
            new_div.setAttribute('style', 'height: 161px;');
            new_div.setAttribute('draggable', 'true');
            new_div.setAttribute('ondragstart', 'drag(event)');
            new_div.setAttribute('id', response.keys_in[place].id + '_' + id);

            var new_paragraph=document.createElement('p');
            new_paragraph.style.textAlign = "center";
            new_paragraph.style.fontWeight = "900";
            new_paragraph.appendChild(document.createTextNode(response.keys_in[place].place));
            new_paragraph.appendChild(document.createElement("br"));

            var image = document.createElement("img");
            image.setAttribute('draggable', 'false');
            image.src = 'images/key_in.png';

            new_paragraph.appendChild(image);

            var new_paragraph2=document.createElement('p');
            new_paragraph2.setAttribute('style', 'font-size:'+size+';');
            new_paragraph2.style.textAlign = "center";
            new_paragraph2.style.fontWeight = "700";
            new_paragraph2.appendChild(document.createTextNode(response.keys_in[place].model));

            new_paragraph.appendChild(new_paragraph2);

            new_div.appendChild(new_paragraph);
            new_row.appendChild(new_div);
            place++;
          }else{
            var new_div=document.createElement('div');
            new_div.className = classe;
            new_div.style.textAlign = "center";
            new_div.setAttribute('name', 'key');
            new_div.setAttribute('style', 'height: 161px;');
            new_div.setAttribute('ondrop', 'drop(event, this)');
            new_div.setAttribute('ondragover', 'allowDrop(event)');
            new_div.setAttribute('id', i+1);

            var new_paragraph=document.createElement('p');
            new_paragraph.style.textAlign = "center";
            new_paragraph.style.fontWeight = "900";
            new_paragraph.appendChild(document.createTextNode(i+1));
            new_paragraph.appendChild(document.createElement("br"));

            var image = document.createElement("img");
            image.setAttribute('draggable', 'false');
            image.src = 'images/key_out2.png';

            new_paragraph.appendChild(image);

            var new_paragraph2=document.createElement('p');
            new_paragraph2.setAttribute('style', 'font-size:'+size+';');
            new_paragraph2.style.textAlign = "center";
            new_paragraph2.style.fontWeight = "700";
            new_paragraph2.appendChild(document.createTextNode('LIBRE'));
            new_paragraph.appendChild(new_paragraph2);

            new_div.appendChild(new_paragraph);
            new_row.appendChild(new_div);
          }

          if(row == (range-1) || i == (box_keys -1)){
            $("#widget-boxManagementAdmin-form div[name=keys]").append(new_row);
          }


          row++;
        }
        // Vélos en déplacement
        if(response.keys_out){
          response.keys_out.forEach(key => {
            $("#widget-boxManagementAdmin-form div[name=in]").before('<div class="col-md-4" name="bike" draggable="true" ondragstart="drag(event)" id="'+ key.id + '_' + id + '">\
            <img draggable="false" src="images_bikes/'+key.img+'_mini.jpg">\
            <p><center><B>'+ key.model + '</B><br>E-mail : ' + key.email + '<br>Début : ' + key.dateStart + '<br>Fin : ' + key.dateEnd + '</center></p></div>');
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
          .getElementById("widget-boxManagementAdmin-form")
          .reset();
      }
    },
  });
}
