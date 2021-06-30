$(".fleetmanager").click(function () {
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
            '<table id="boxesListingTable" class="table"><thead><tr><th>ID</th><th scope="col">'+traduction.generic_company+'</th><th scope="col">'+traduction.generic_reference+'</th><th scope="col">'+traduction.generic_reference+'</th><th scope="col">'+traduction.leasingType_amountLeasing+'</th><th>'+traduction.bike_description_contract_start+'</th></tr></thead><tbody>';
          dest = dest.concat(temp);

          while (i < response.boxesNumber) {

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
              response.box[i].amount + " €/" +traduction.generic_mois+
              "</td><td>" +
              response.box[i].start.shortDate() +
              "</td></tr>";
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
  $("#boxManagement-title").text("Informations de la borne");
  $("#boxManagement-send").addClass("hidden");
  $("#boxManagement input").attr("readonly", true);
  $("#boxManagement textarea").attr("readonly", true);
  $("#boxManagement select").attr("readonly", true);
  $("#boxManagement div[name=key]").remove();
  $("#boxManagement div[name=bike]").remove();
  $("#boxManagement div[name=severalBoxes]").html("");
  $("#boxManagement div[name=keys]").html("");

  $.ajax({
    url: "apis/Kameo/boxes/boxes.php",
    type: "get",
    data: { action: "retrieveBox", id: id },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        $("#boxManagement input[name=reference]").val(
          response.reference
        );
        $("#boxManagement select[name=boxModel]").val(
          response.model
        );
        $("#boxManagement input[name=amount]").val(response.amount);
        if (response.start) {
          $("#boxManagement input[name=contractStart]").val(
            response.start.substr(0, 10)
          );
        } else {
          $("#boxManagement input[name=contractStart]").val("");
        }
        if (response.end) {
          $("#boxManagement input[name=contractEnd]").val(
            response.end.substr(0, 10)
          );
        } else {
          $("#boxManagement input[name=contractEnd]").val("");
        }
        if(response.doorPosition=="Open"){
          $("#boxManagement span[name=doorPosition]").html(traduction.generic_open);
        }else if(response.doorPosition=="Closed"){
          $("#boxManagement span[name=doorPosition]").html(traduction.generic_closed);
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
            new_div.setAttribute('style', 'height: 161px;');
            new_div.setAttribute('id', response.keys_in[place].id + '_' + id);

            var new_paragraph=document.createElement('p');
            new_paragraph.style.textAlign = "center";
            new_paragraph.style.fontWeight = "900";
            new_paragraph.appendChild(document.createTextNode(response.keys_in[place].place));
            new_paragraph.appendChild(document.createElement("br"));


            var image = document.createElement("img");
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
            new_paragraph2.appendChild(document.createTextNode(traduction.box_free));
            new_paragraph.appendChild(new_paragraph2);

            new_div.appendChild(new_paragraph);
            new_row.appendChild(new_div);
          }

          if(row == (range-1) || i == (box_keys -1)){
            $("#boxManagement div[name=keys]").append(new_row);
          }


          row++;
        }

        // Vélos en déplacement
        if(response.keys_out){
          var dateNow = new Date();
          response.keys_out.forEach(key => {
            var dateEnd = new Date(key.dateEnd);
            if(dateEnd<dateNow){
              var classSpan="text-red";
            }else{
              var classSpan="";
            }
            $("#boxManagement div[name=in]").before('<div class="col-md-4" name="bike">\
            <center><img draggable="false" src="images_bikes/'+key.img+'_mini.jpg" style="height:136px;"></center>\
            <p><center><B>'+ key.model + '</B><br>E-mail : ' + key.email + '<br>'+traduction.generic_start_date+' : ' + key.dateStart.shortDateHours() + '<br><span class="'+classSpan+'">'+traduction.generic_end_date+' : '+ key.dateEnd.shortDateHours() + '</span></center></p></div>');
          });
        }

        if(response.keys_other_box){
          $('.severalBoxes').removeClass("hidden");
          $("#boxManagement div[name=severalBoxes]").removeClass("hidden");
          response.keys_other_box.forEach(key => {
            $("#boxManagement div[name=severalBoxes]").append('<div class="col-md-4">\
            <img draggable="false" src="images_bikes/'+key.img+'_mini.jpg">\
            <p><center><B>'+ key.model + '</B><br>Bâtiment : ' + key.building + '</center></p></div>');
          });
        }else{
          $('.severalBoxes').addClass("hidden");
        }
      }
    },
  });
}
