function list_maintenances() {
  $.ajax({
      url: 'apis/Kameo/maintenance_management.php',
      method: 'get',
      data: {'action' : 'list'},
      success: function(response){
        if (response.response == "error") {
          console.log(response.message);
        } else {
          if(typeof(response.maintenance) != 'undefined'){
            var dest2 = `<h4 class="fr-inline text-green" style="display: inline;">Entretiens:</h4>
                      <table class="table table-condensed">
                        <tbody></tbody>
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Vélo</th>
                            <th>Société</th>
                            <th>Date</th>
                            <th>Status</th>
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
                <td>`+response.maintenance[i].company+`</td>
                <td>`+date+`</td>
                <td>`+status+`</td>
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
            var dest2 = '<h4 class="fr-inline text-green" style="display: inline;">Entretiens:</h4><div>Pas d\'entretiens.</div>';
            $('#maintenanceListingSpan').html(dest2);
            var dest = '<span data-speed="1" data-refresh-interval="4" data-to="0" data-from="0" data-seperator="true">0</span>';
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
          var date = new Date(response.maintenance.dateMaintenance).toLocaleDateString();
          date = date.split("/");
          $('#widget-maintenanceManagement-form input[name=ID]').val(response.maintenance.id);
          $('#widget-maintenanceManagement-form input[name=velo]').val(response.maintenance.bike_id);
          $('#widget-maintenanceManagement-form input[name=company]').val(response.maintenance.company);

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
  $("#widget-maintenanceManagement-form div[name=image]").remove();
  get_maintenance(this.name);
  $("#widget-maintenanceManagement-form input").attr("readonly", true);
  $("#widget-maintenanceManagement-form input[name=dateMaintenance]").attr("readonly", false);
  $("#widget-maintenanceManagement-form select").attr("readonly", false);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", false);
  $(".maintenanceManagementTitle").html("Éditer un entretien");
  $("#widget-maintenanceManagement-form button").show();
  $("#widget-maintenanceManagement-form div[name=file]").show();
  $("#widget-maintenanceManagement-form button[name=delete]").show();
});

$('body').on('click', '.showMaintenance',function(){
  $("#widget-maintenanceManagement-form div[name=image]").remove();
  get_maintenance(this.name);
  $("#widget-maintenanceManagement-form input").attr("readonly", true);
  $("#widget-maintenanceManagement-form select").attr("readonly", true);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", true);
  $(".maintenanceManagementTitle").html("Vu sur un entretien");
  $("#widget-maintenanceManagement-form button").hide();
  $("#widget-maintenanceManagement-form div[name=file]").hide();
  $("#widget-maintenanceManagement-form button[name=delete]").hide();
});
