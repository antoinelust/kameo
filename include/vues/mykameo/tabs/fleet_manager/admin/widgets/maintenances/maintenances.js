$(".fleetmanager").click(function () {
  document.getElementsByClassName('maintenanceManagementClick')[0].addEventListener('click', function() { list_maintenances()}, false);

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
        dest += response.maintenancesNumberGlobal + '/</span><span style="color: rgb(216, 0, 0); margin:0;" data-speed="1" data-refresh-interval="4" data-to="'+response.maintenancesNumberAuto+'" data-from="0" data-seperator="false">';
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

  $("#maintenanceListingSpan").dataTable({
    destroy: true,
    ajax: {
      url: "apis/Kameo/maintenance_management.php",
      contentType: "application/json",
      type: "get",
      data: {
        'action' : 'list',
        dateStart: dateStartString,
        dateEnd: dateEndString
      },
    },
    sAjaxDataProp: "maintenance",
    columns: [
      {
        title: "ID",
        data: "id",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html('<a  data-target="#maintenanceManagementItem" name="'+sData+'" data-toggle="modal" class="showMaintenance" href="#">'+sData+'</a>');
        },
      },
      { title: "Vélo", data: "frame_number" },
      { title: "Modèle", data: "model" },
      { title: "Client", data: "company" },
      {
        title: "Date",
        data: "date",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(sData.shortDate());
        },
      },

      {
        title: "Statut",
        data: "status",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            (sData == "AUTOMATICALY_PLANNED" || sData == "MANUALLY_PLANNED") ? "<span class='text-red'>"+sData+"<sData>" : sData
          );
        },
      },
      { title: "Type", data: "type" },
      {
        title: "Adresse",
        data: "street",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            sData+" "+oData.zip_code+" "+oData.town
          );
          $(nTd).data('sort', new Date(sData).getTime());
        },
      },
      { title: "N° téléphone", data: "phone" },
      {
        title: "",
        data: "id",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html('<a href="#" class="text-green editMaintenance" data-target="#maintenanceManagementItem" name="'+sData+'" data-toggle="modal">Modifier</a>');
        },
      }

    ],
    order: [
      [4, "asc"]
    ],
    paging : false
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
        $('#widget-maintenanceManagement-form select[name=velo]').append('<option value="'+response.maintenance.bike_id+'">'+response.maintenance.bike_id + " - " + response.maintenance.model+'</option>');
        $("#widget-maintenanceManagement-form select[name=velo]").attr("disabled", true);
        $("#widget-maintenanceManagement-form div[name=image]").remove();
        $("#widget-maintenanceManagement-form select[name=company]").attr("disabled", true);

        var date = new Date(response.maintenance.dateMaintenance).toLocaleDateString();
        date = date.split("/");
        $('#widget-maintenanceManagement-form input[name=ID]').val(response.maintenance.id);
        $('#widget-maintenanceManagement-form .maintenanceManagementDeleteButton').attr('name', response.maintenance.id);
        $('#widget-maintenanceManagement-form select[name=velo]').val(response.maintenance.bike_id);
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

$('.maintenanceManagementDeleteButton').off();
$('.maintenanceManagementDeleteButton').click(function(){
  $.ajax({
  url:'apis/Kameo/maintenance_management.php',
  data:{'action' : 'deleteEntretien', 'id' : this.name},
  method:'POST',
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
      list_maintenances();
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
})


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
  $("#widget-maintenanceManagement-form div[name=image]").remove();
  empty_form();
  $("#widget-maintenanceManagement-form input[name=action]").val("add");
  $("#widget-maintenanceManagement-form input").attr("readonly", false);
  $("#widget-maintenanceManagement-form select").attr("disabled", false);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", false);
  $("#widget-maintenanceManagement-form select[name=velo]").attr("disabled", false);
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
  $('#widget-maintenanceManagement-form select[name=velo]').val("");
  $('#widget-maintenanceManagement-form input[name=model]').val("");
  $('#widget-maintenanceManagement-form select[name=company]').val("");
  $('#widget-maintenanceManagement-form input[name=address]').val("");
  $('#widget-maintenanceManagement-form select[name=status]').val("MANUALLY_PLANNED");
  $('#widget-maintenanceManagement-form input[name=dateMaintenance]').val("");
  $('#widget-maintenanceManagement-form textarea[name=comment]').val("");
}

$('#widget-maintenanceManagement-form select[name=company]').change(function(){
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
          $('#widget-maintenanceManagement-form select[name=velo]').append(
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
      url: "api/companies",
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
