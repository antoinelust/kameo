$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "maintenances" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        var dest = '<span data-speed="1" data-refresh-interval="4" data-to="'+response.maintenancesNumberGlobal+'" data-from="0" data-seperator="true">';
        dest += response.maintenancesNumberGlobal + '/</span><span style="color:red; margin:0;" data-speed="1" data-refresh-interval="4" data-to="'+response.maintenancesNumberAuto+'" data-from="0" data-seperator="false">';
        dest += response.maintenancesNumberAuto + '</span>';
        document.getElementById("counterMaintenance").innerHTML = dest;
        
      }
    },
  });
});

function list_maintenances() {
  var dateStart = $(".form_date_start_maintenance").data("datetimepicker").getDate();
  var dateEnd = $(".form_date_end_maintenance").data("datetimepicker").getDate();
  var dateStartString =
    dateStart.getFullYear() +
    "-" +
    ("0" + (dateStart.getMonth() + 1)).slice(-2) +
    "-" +
    ("0" + dateStart.getDate()).slice(-2);
  var dateEndString =
    dateEnd.getFullYear() +
    "-" +
    ("0" + (dateEnd.getMonth() + 1)).slice(-2) +
    "-" +
    ("0" + dateEnd.getDate()).slice(-2);

  $.ajax({
      url: 'apis/Kameo/maintenance_management.php',
      method: 'get',
      data: {'action' : 'list',
      dateStart: dateStartString,
      dateEnd: dateEndString,},
      success: function(response){
        if (response.response == "error") {
          console.log(response.message);
        } else {
          if(typeof(response.maintenance) != 'undefined' && response.maintenance.length > 0){
            var dest2 = `
                      <table class="table table-condensed">
                        <tbody></tbody>
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Vélo</th>
                            <th>Model</th>
                            <th>Société</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Adresse</th>
                            <th></th>
                          </tr>
                        </thead>
                      <tbody>`;
            for (var i = 0; i < response.maintenance.length; i++) {
              var date = new Date(response.maintenance[i].date).toLocaleDateString();
              var status = '';

              if (response.maintenance[i].status == 'AUTOMATICALY_PLANNED') {
                status = '<span class="text-red">'+response.maintenance[i].status+'</span>';
              } else if(response.maintenance[i].status == 'DONE'){
                status = '<span class="text-green">'+response.maintenance[i].status+'</span>';
              }else{
                status = response.maintenance[i].status;
              }
              dest2 += `
                <tr>
                <td><a  data-target="#maintenanceManagementItem" name="`+response.maintenance[i].id+
                '" data-toggle="modal" class="showMaintenance" href="#">'+response.maintenance[i].id+`</a></td>
                <td>`+response.maintenance[i].bike_id+`</td>
                <td>`+response.maintenance[i].model+`</td>
                <td>`+response.maintenance[i].company+`</td>
                <td>`+date+`</td>
                <td>`+status+`</td>
                <td>`+response.maintenance[i].street+ ', ' + response.maintenance[i].zip_code + ' ' + response.maintenance[i].town +`</td>
                <td><a href="#" class="text-green editMaintenance" data-target="#maintenanceManagementItem" name="`+response.maintenance[i].id+`" data-toggle="modal">Modifier</a></td>
                </tr>
              `;
            }
            dest2 += '</tbody></table>'
            $('#maintenanceListingSpan').html(dest2);

            var dest = '<span data-speed="1" data-refresh-interval="4" data-to="'+response.maintenancesNumberGlobal+'" data-from="0" data-seperator="true">';
            dest += response.maintenancesNumberGlobal + '/</span><span style="color:red; margin:0;" data-speed="1" data-refresh-interval="4" data-to="'+response.maintenancesNumberAuto+'" data-from="0" data-seperator="false">';
            dest += response.maintenancesNumberAuto + '</span>';
            $('#counterMaintenance').html(dest);
          }
          else{
            var dest2 = '<div>Pas d\'entretiens.</div>';
            $('#maintenanceListingSpan').html(dest2);
            var dest = '<span data-speed="1" data-refresh-interval="4" data-to="'+response.maintenancesNumberGlobal+'" data-from="0" data-seperator="true">';
            dest += response.maintenancesNumberGlobal + '/</span><span style="color:red; margin:0;" data-speed="1" data-refresh-interval="4" data-to="'+response.maintenancesNumberAuto+'" data-from="0" data-seperator="false">';
            dest += response.maintenancesNumberAuto + '</span>';
            $('#counterMaintenance').html(dest);
          }
        }
      }
  });
}

function get_maintenance(ID){
  document.getElementById('widget-maintenanceManagement-form').reset();

  $.ajax({
    url: 'apis/Kameo/maintenance_management.php',
    method: 'get',
    data: {'action' : 'getOne', 'ID' : ID},
    success: function(response){
      if (response.response == "error") {
        console.log(response.message);
      } else{
        $("#widget-maintenanceManagement-form select[name=velo]").remove();
        $("#widget-maintenanceManagement-form input[name=velo]").remove();
        $("#widget-maintenanceManagement-form label[id=velo]").append('<input type="text" title="velo" class="form-control required" name="velo" readonly="readonly"/>');
        $("#widget-maintenanceManagement-form div[name=image]").remove();
        $("#widget-maintenanceManagement-form select[name=company]").attr("disabled", true);

        var date = new Date(response.maintenance.dateMaintenance).toLocaleDateString();
        date = date.split("/");
        $('#widget-maintenanceManagement-form input[name=ID]').val(response.maintenance.id);
        $('#widget-maintenanceManagement-form input[name=velo]').val(response.maintenance.bike_id);
        $('#widget-maintenanceManagement-form select[name=company]').val(response.maintenance.company);
        $('#widget-maintenanceManagement-form input[name=model]').val(response.maintenance.model);
        $('#widget-maintenanceManagement-form input[name=address]').val(response.maintenance.street+ ', ' + response.maintenance.zip_code + ' ' + response.maintenance.town);
        $('#widget-maintenanceManagement-form select[name=status]').val(response.maintenance.status);
        $('#widget-maintenanceManagement-form input[name=dateMaintenance]').val(date[2] + '-' + date[1] + '-' + date[0]);
        $('#widget-maintenanceManagement-form textarea[name=comment]').val(response.maintenance.comment);

        response.maintenance.images.forEach(function(image){
          $("#widget-maintenanceManagement-form div[name=images]").append('<div class="col-md-4" name="image">\
          <img src="images_entretiens/'+image+'">\
          <a class="button small red button-3d rounded icon-left deleteImage" name="'+image+'"> \
          <i class="fa fa-paper-plane"></i>Supprimer l\'image </a></div>');
        })

        $(function(){
          $('a.deleteImage').click(function(){
            $.ajax({
            url:'apis/Kameo/maintenance_management.php',
            data:{'action' : 'deleteImage', 'url' : 'images_entretiens/'+this.name},
            method:'GET',
            success:function(response){
              if(response.response == "success"){
                $.notify(
                  {
                    message: response.message,
                  },
                  {
                    type: "success",
                  }
                );
                get_maintenance(response.id);
                $("#maintenanceManagementItem").modal("toggle");
                document
                  .getElementById("widget-maintenanceManagement-form")
                  .reset();
              }else{
                $.notify({
                  message: response.message,
                }, {
                  type: "danger",
                });
              }
            }
            });
          });
        });
      }
    }
  });
}

$('body').on('click', '.editMaintenance',function(){
  get_maintenance(this.name);
  $("#widget-maintenanceManagement-form input[name=action]").val("edit");
  $("#widget-maintenanceManagement-form input").attr("readonly", true);
  $("#widget-maintenanceManagement-form input[name=dateMaintenance]").attr("readonly", false);
  $("#widget-maintenanceManagement-form select").attr("disabled", false);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", false);
  $(".maintenanceManagementTitle").html("Éditer un entretien");
  $("#widget-maintenanceManagement-form button").show();
  $("#widget-maintenanceManagement-form div[name=file]").show();
  $("#widget-maintenanceManagement-form button[name=delete]").show();
  $("#widget-maintenanceManagement-form div[name=status]").show();
  $("#widget-maintenanceManagement-form div[name=id]").show();
});

$('body').on('click', '.showMaintenance',function(){
  get_maintenance(this.name);
  $("#widget-maintenanceManagement-form input").attr("readonly", true);
  $("#widget-maintenanceManagement-form select").attr("disabled", true);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", true);
  $(".maintenanceManagementTitle").html("Vu sur un entretien");
  $("#widget-maintenanceManagement-form div[name=status]").show();
  $("#widget-maintenanceManagement-form button").hide();
  $("#widget-maintenanceManagement-form div[name=file]").hide();
  $("#widget-maintenanceManagement-form button[name=delete]").hide();
  $("#widget-maintenanceManagement-form div[name=id]").show();
});

$('body').on('click', '.addMaintenance',function(){
  $("#widget-maintenanceManagement-form input[name=velo]").remove();
  $("#widget-maintenanceManagement-form select[name=velo]").remove();
  $("#widget-maintenanceManagement-form label[id=velo]").append('<select title="velo" class="form-control required form_velo" name="velo"></select>');
  $("#widget-maintenanceManagement-form div[name=image]").remove();
  empty_form();
  $("#widget-maintenanceManagement-form input[name=action]").val("add");
  $("#widget-maintenanceManagement-form input").attr("readonly", true);
  $("#widget-maintenanceManagement-form select").attr("disabled", false);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", false);
  $("#widget-maintenanceManagement-form select[name=velo]").attr("disabled", true);
  $("#widget-maintenanceManagement-form input[name=dateMaintenance]").attr("readonly", false);
  $(".maintenanceManagementTitle").html("Ajouter un entretien");
  $("#widget-maintenanceManagement-form button").show();
  $("#widget-maintenanceManagement-form div[name=id]").hide();
  $("#widget-maintenanceManagement-form div[name=file]").hide();
  $("#widget-maintenanceManagement-form button[name=delete]").hide();
});

$('body').on('change', '.form_date_start_maintenance',function(){
  var dateStart = $(".form_date_start_maintenance").data("datetimepicker").getDate();
  var dateStartString = ("0" + dateStart.getDate()).slice(-2) + "/" + ("0" + (dateStart.getMonth() + 1)).slice(-2) + "/" + dateStart.getFullYear();
  $(".form_date_end_maintenance").datetimepicker('setStartDate', dateStartString);
});


$('body').on('change', '.form_date_start_maintenance',function(){
  list_maintenances();
});

$('body').on('change', '.form_date_end_maintenance',function(){
  list_maintenances();
});


function empty_form(){
  $('#widget-maintenanceManagement-form input[name=ID]').val("");
  $('#widget-maintenanceManagement-form input[name=velo]').val("");
  $('#widget-maintenanceManagement-form input[name=model]').val("");
  $('#widget-maintenanceManagement-form select[name=company]').val("");
  $('#widget-maintenanceManagement-form input[name=address]').val("");
  $('#widget-maintenanceManagement-form select[name=status]').val("MANUALLY_PLANNED");
  $('#widget-maintenanceManagement-form input[name=dateMaintenance]').val("");
  $('#widget-maintenanceManagement-form textarea[name=comment]').val("");
}

$('body').on('change', '.form_company',function(){
  $('#widget-maintenanceManagement-form input[name=model]').val("");
  $("#widget-maintenanceManagement-form select[name=velo]").attr("disabled", false);
  
  $.ajax({
    url: "apis/Kameo/get_bikes_listing.php",
    type: "post",
    data: {company: $('#widget-maintenanceManagement-form select[name=company]').val() },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }else{
        $("#widget-maintenanceManagement-form select[name=velo]")
          .find("option")
          .remove()
          .end();
        
        for (var i = 0; i < response.bikeNumber; i++) {
          $("#widget-maintenanceManagement-form select[name=velo]").append(
            '<option value="' +
              response.bike[i].id +
              '">' +
              response.bike[i].id + ' - ' + response.bike[i].model + ' : ' + response.bike[i].size +
              "<br>"
          );
          $('#widget-maintenanceManagement-form input[name=model]').val(response.bike[0].model);
        }
      }
    },
  }).done(function(response){
  $.ajax({
    url: "apis/Kameo/get_companies_listing.php",
    method: "get",
    data: {action: "name", company: $('#widget-maintenanceManagement-form select[name=company]').val() },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }else{
          $('#widget-maintenanceManagement-form input[name=address]').val(response.street + ", " + response.zip_code + " " + response.town);
        }
      }
    });
  });
});

$('body').on('change', '.form_velo',function(){
  var res = $('#widget-maintenanceManagement-form select[name=velo] option:selected').text().split(" - ");
  var model = res[1].split(" : ")[0];
  $('#widget-maintenanceManagement-form input[name=model]').val(model);
});