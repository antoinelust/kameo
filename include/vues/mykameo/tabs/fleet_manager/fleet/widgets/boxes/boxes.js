$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "boxesFleet" },
    success: function (response) {
      if (response.response == "error"){
        console.log(response.message);
      }
      if (response.response == "success") {
        document.getElementById("counterBoxesFleet").innerHTML =
          '<span data-speed="1" data-refresh-interval="4" data-to="' +
          response.boxesNumberTotal +
          '" data-from="0" data-seperator="true">' +
          response.boxesNumberTotal +
          "</span>";
      }
    },
  });
  document.getElementsByClassName('boxViewClick')[0].addEventListener('click', function() { list_boxes()}, false);
});

function list_boxes(){
  $.ajax({
    url: "apis/Kameo/boxes/boxes.php",
    type: "get",
    data: { action: "listBoxes"},
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        var i = 0;
        var dest = "";
        if (response.boxesNumber > 0) {
          var temp =
            '<table id="boxesListingTable" class="table"><thead><tr><th>ID</th><th scope="col"><span class="fr-inline">Société</span><span class="en-inline">Company</span><span class="nl-inline">Company</span></th><th scope="col"><span class="fr-inline">Référence</span><span class="en-inline">Reference</span><span class="nl-inline">Reference</span></th><th scope="col"><span class="fr-inline">Modèle</span><span class="en-inline">Model</span><span class="nl-inline">Model</span></th><th scope="col"><span class="fr-inline">Montant leasing</span><span class="en-inline">Leasing Price</span><span class="nl-inline">Leasing Price</span></th><th>Début de contrat</th><th>Fin de contrat</th></tr></thead><tbody>';
          dest = dest.concat(temp);

          while (i < response.boxesNumber) {
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
              amount +
              "</td><td>" +
              start +
              "</td><td>" +
              end +
              '</td></tr>';
            dest = dest.concat(temp);
            i++;
          }

          var temp = "</tbody></table>";
          dest = dest.concat(temp);
        }
        $("#boxesListingSpan").html(dest);
        $("#boxesListingTable").DataTable({
          searching: false,
          paging: false,
          info: false,
        });
        displayLanguage();
        $(".retrieveBox").click(function () {
          retrieve_box(this.name);
        });
      }
    },
  });
}



function retrieve_box(id) {
  $("#widget-boxManagement-form-title").text("Informations de la borne");
  $("#widget-boxManagement-form-send").addClass("hidden");
  $("#widget-boxManagement-form input").attr("readonly", true);
  $("#widget-boxManagement-form textarea").attr("readonly", true);
  $("#widget-boxManagement-form select").attr("readonly", true);
  $("#widget-boxManagement-form div[name=key]").remove();
  $("#widget-boxManagement-form div[name=bike]").remove();

  $.ajax({
    url: "apis/Kameo/boxes/boxes.php",
    type: "get",
    data: { action: "retrieveBox", id: id },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
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
            $("#widget-boxManagement-form div[name=keys]").append('<div class="'+ classe + '" name="key" style="height: 161px;">\
            <p><center><B>'+ response.keys_in[place].place +'</B></br><img draggable="false" src="images/key_in.png">\
            </br><p style="font-size:'+size+';"><B>'+response.keys_in[place].model +'</B></p></center></p></div>');
            place++;
          }else{
            $("#widget-boxManagement-form div[name=keys]").append('<div class="'+ classe + '" name="key">\
            <p><center><B>'+ (i + 1) +'</B></br><img draggable="false" src="images/key_out2.png"></br><p style="font-size:'+size+';"><B>LIBRE'+ space +'</B></p></center></p></div>');
          }
          row++;
        }


        if(response.keys_out){
          response.keys_out.forEach(key => {
            $("#widget-boxManagement-form div[name=in]").before('<div class="col-md-4" name="bike">\
            <img draggable="false" src="images_bikes/'+key.img+'_mini.jpg">\
            <p><center><B>'+ key.model + '</B><br>' + key.email + '</center></p></div>');
          });
        }
      }
    },
  });
}