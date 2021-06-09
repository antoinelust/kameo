$( ".fleetmanager" ).click(function() {
    $.ajax({
        url: 'apis/Kameo/initialize_counters.php',
        type: 'post',
        data: { "email": email, "type": "users"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById("counterUsers").innerHTML = '<span style="margin-left:20px">'+response.usersNumber+'</span>';
            }
        }
    })
})

function get_users_listing(){
  console.log("test");
  $.ajax({
     url: "apis/Kameo/get_users_listing",
     success : function(data) {
      $('#usersList').dataTable( {
        destroy: true,
        sAjaxDataProp: "",
        data : data.users,
        columns: [
         { title: traduction.sidebar_last_name, data: "name" },
         { title: traduction.sidebar_first_name, data: "firstName" },
         { className: "hidden-xs", title: "E-Mail", data: "email" },
         {
           title: traduction.generic_status,
           data: "staann",
           fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
             if (sData == 'D') $(nTd).html("Supprimé");
             else $(nTd).html("Actif");
           },
         },
         {
           data: "email",
           fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
             $(nTd).html("<a href='#' data-target='#updateUserInformation' onclick=\"update_user_information('" +
               sData +
               "')\" class='text-green' data-toggle='modal'>"+traduction.generic_update+" </a>");
             },
          }

        ],
        order: [
         [0, "asc"]
        ]
      });
   }
  });
}

  //FleetManager: Nombre d'utilisateurs | Display user details when "Mettre à jour" button is pressed
  function update_user_information(email){
    $.ajax({
      url: 'api/users',
      type: 'get',
      data: { action: "retrieve", email: email},
      success: function(response){
        $('#widget-updateUser-form input[name=firstname]').val(response.user.PRENOM);
        $('#widget-updateUser-form input[name=name]').val(response.user.NOM);
        $('#widget-updateUser-form input[name=mail]').val(response.user.EMAIL);
        $('#widget-updateUser-form input[name=phone]').val(response.user.PHONE);
        var dest="";
        if(response.user.STAANN=='D'){
          $('#widget-updateUser-form input[name=status]').val(traduction.generic_deleted);

          $('#widget-updateUser-form input[name=firstname]').prop('readonly', true);
          $('#widget-updateUser-form input[name=name]').prop('readonly', true);
          $("#widget-updateUser-form input[name=fleetManager]").prop( "readonly", true );
          document.getElementById('buildingUpdateUser').innerHTML = "";
          document.getElementById('bikeUpdateUser').innerHTML = "";
          var dest="<a class=\"button small green button-3d rounded icon-right\" data-target=\"#reactivateUser\" onclick=\"initializeReactivateUser('"+response.user.email+"')\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Ré-activer</span><span class=\"en-inline\">Re-activate</span></a>";
          document.getElementById('updateUserSendButton').innerHTML="";
          document.getElementById('deleteUserButton').innerHTML=dest;
        }else{
          $('#widget-updateUser-form input[name=status]').val(traduction.generic_active);
          $('#widget-updateUser-form input[name=firstname]').prop('readonly', false);
          $('#widget-updateUser-form input[name=name]').prop('readonly', false);
          $("#widget-updateUser-form input[name=fleetManager]").prop( "readonly", false );
          if(response.user.fleetManager=="Y"){
            $("#widget-updateUser-form input[name=fleetManager]").prop( "checked", true );
          }else{
            $("#widget-updateUser-form input[name=fleetManager]").prop( "checked", false );
          }
          if(user_data.BOOKING=='Y'){
            var dest="<h4>"+traduction.generic_accessToBuildings+"</h4>";
            if(response.building.length > 0){
              response.building.forEach(function(building){
                if(building.access=='true'){
                  temp="<input type=\"checkbox\" checked name=\"buildingAccess[]\" value=\""+building.buildingCode+"\"> "+building.descriptionFR+"<br>";

                }
                else if(building.access=='false'){
                  temp="<input type=\"checkbox\" name=\"buildingAccess[]\" value=\""+building.buildingCode+"\"> "+building.descriptionFR+"<br>";
                }else{
                  console.log("error...");
                }
                dest=dest.concat(temp);
              })
            }
            document.getElementById('buildingUpdateUser').innerHTML = dest;

            var i=0;
            var dest="<h4>"+traduction.generic_accessToBikes+"</h4><input type=\"checkbox\" id=\"select-all-update\" name=\"select_all\" value=\"1\" /><strong>"+traduction.generic_selectAll+"</strong><br>";
            if(response.bike.length>0){
              response.bike.forEach(function(bike){
                if(bike.access=='true'){
                  temp="<input type=\"checkbox\" checked name=\"bikeAccess[]\" value=\""+bike.bikeID+"\"> "+bike.bikeID+" - "+bike.model+"<br>";
                }
                else if(bike.access=='false'){
                  temp="<input type=\"checkbox\" name=\"bikeAccess[]\" value=\""+bike.bikeID+"\"> "+bike.bikeID+" - "+bike.model+"<br>";
                }else{
                  console.log("error...");
                }
                dest=dest.concat(temp);
              })
            }
            document.getElementById('bikeUpdateUser').innerHTML = dest;
            document.getElementById('select-all-update').onclick = function() {
              var checkboxes = document.getElementsByName('bikeAccess[]');
              for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
              }
            }
          }
          var dest="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#deleteUser\" data-email=\""+response.user.EMAIL+"\" data-name=\""+response.user.NOM+"\" data-firstname=\""+response.user.PRENOM+"\" name=\""+response.user.EMAIL+"\" data-toggle=\"modal\" href=\"#\">"+traduction.generic_delete+"</a>";
          document.getElementById('updateUserSendButton').innerHTML="<button class=\"button small green button-3d rounded icon-left\" type=\"submit\"><i class=\"fa fa-paper-plane\"></i>"+traduction.generic_send+"</button>";
          document.getElementById('deleteUserButton').innerHTML=dest;
        }
      }
    });
  }

  $("#deleteUser").on("show.bs.modal", function (event) {
    document.getElementById('widget-deleteUser-form-firstname').value =  $(event.relatedTarget).data("firstname");
    document.getElementById('widget-deleteUser-form-name').value =  $(event.relatedTarget).data("name");
    document.getElementById('widget-deleteUser-form-mail').value =  $(event.relatedTarget).data("email");
  });

  //FleetManager: Nombre d'utilisateurs | Display summary of user details when "Réactiver" button is pressed
  function initializeReactivateUser(email){

    $.ajax({
      url: 'apis/Kameo/get_user_details.php',
      type: 'post',
      data: { "email": email},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          document.getElementById('widget-reactivateUser-form-firstname').value = response.user.firstName;
          document.getElementById('widget-reactivateUser-form-name').value = response.user.name;
          document.getElementById('widget-reactivateUser-form-mail').value = response.user.email;
        }

      }
    })
    $('#updateUserInformation').modal('toggle');

  }
  //FleetManager: Nombre d'utilisateurs | Displays the msg to confim user creation
  function confirm_add_user(){
    document.getElementById('confirmAddUser').innerHTML=traduction.generic_confirmSentenceforAddUser;
  }

  //FleetManager: Nombre d'utilisateurs | List the building, bikes and display the create button
  function create_user(){
    document.getElementById('confirmAddUser').innerHTML="<button class=\"fr button small green button-3d rounded icon-left\" onclick=\"confirm_add_user()\">\
    <i class=\"fa fa-paper-plane\">\
    </i>"+traduction.generic_confirm+"</button>";
    if(user_data.BOOKING == "Y"){
      $('#widget-addUser-form .accessToBikes').removeClass("hidden");
      $('#widget-addUser-form .accessToBuildings').removeClass("hidden");

      $.ajax({
        url: 'apis/Kameo/get_building_listing.php',
        type: 'post',
        data: { "email": user_data.EMAIL},
        success: function(response){
          if(response.response == 'error') {
            console.log(response.message);
          }
          if(response.response == 'success'){
            var i=0;
            var dest="";
            while (i < response.buildingNumber){
              temp="<input type=\"checkbox\" name=\"buildingAccess[]\" checked value=\""+response.building[i].code+"\"> "+response.building[i].descriptionFR+"<br>";
              dest=dest.concat(temp);
              i++;
            }
            document.getElementById('buildingCreateUser').innerHTML = dest;

            $.ajax({
              url: 'api/bikes',
              type: 'get',
              data: { 'action': 'list'},
              success: function(response){
                if(response.response == 'error') {
                  console.log(response.message);
                }
                if(response.response == 'success'){
                  var i=0;
                  var dest="";
                  if(response.bike.length>0){
                    response.bike.forEach(function(bike){
                      if(bike.biketype == 'partage'){
                        temp="<input type=\"checkbox\" name=\"bikeAccess[]\" checked value=\""+bike.id+"\"> "+bike.frameNumber+" "+bike.model+"<br>";
                        dest=dest.concat(temp);
                      }
                    })
                  }
                  document.getElementById('bikeCreateUser').innerHTML = dest;
                  $('#widget-addUser-form input[name=company]').val("");

                  document.getElementById('select-all').onclick = function() {
                    var checkboxes = document.getElementsByName('bikeAccess[]');
                    for (var checkbox of checkboxes) {
                      checkbox.checked = this.checked;
                    }
                  }
                }
              }
            });
          }
        }
      });
    }else{
      $('#widget-addUser-form .accessToBikes').addClass("hidden");
      $('#widget-addUser-form .accessToBuildings').addClass("hidden");
      document.getElementById('buildingCreateUser').innerHTML = "";
      document.getElementById('bikeCreateUser').innerHTML = "";
    }
  }
