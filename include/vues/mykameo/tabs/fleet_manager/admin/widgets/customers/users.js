  //FleetManager: Nombre d'utilisateurs | Display user details when "Mettre à jour" button is pressed
  function update_user_information(email){
    $.ajax({
      url: 'apis/Kameo/get_user_details.php',
      type: 'post',
      data: { "email": email},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          document.getElementById('widget-updateUserAdmin-form-firstname').value = response.user.firstName;
          document.getElementById('widget-updateUserAdmin-form-name').value = response.user.name;
          document.getElementById('widget-updateUserAdmin-form-mail').value = response.user.email;
          document.getElementById('widget-updateUserAdmin-form-phone').value = response.user.phone;
          var dest="";
          if(response.user.staann=='D'){
            document.getElementById('widget-updateUserAdmin-form-status').value = "Inactif";
            $('#widget-updateUserAdmin-form-firstname').prop('readonly', true);
            $('#widget-updateUserAdmin-form-name').prop('readonly', true);
            $("#widget-updateUserAdmin-form input[name=fleetManager]").prop( "readonly", true );
            document.getElementById('buildingUpdateUser').innerHTML = "";
            document.getElementById('bikeUpdateUser').innerHTML = "";
            var dest="<a class=\"button small green button-3d rounded icon-right\" data-target=\"#reactivateUser\" onclick=\"initializeReactivateUser('"+response.user.email+"')\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Ré-activer</span><span class=\"en-inline\">Re-activate</span></a>";
            document.getElementById('updateUserSendButton').innerHTML="";
            document.getElementById('deleteUserButton').innerHTML=dest;
          }else{
            $('#widget-updateUserAdmin-form-firstname').prop('readonly', false);
            $('#widget-updateUserAdmin-form-name').prop('readonly', false);
            if(response.user.administrator=="Y"){
              $("#widget-updateUserAdmin-form input[name=fleetManager]").prop( "checked", true );
            }else{
              $("#widget-updateUserAdmin-form input[name=fleetManager]").prop( "checked", false );
            }

            document.getElementById('widget-updateUserAdmin-form-status').value = "Actif";
            var i=0;
            var dest="<h4>Accès aux bâtiments</h4>";
            while(i<response.buildingNumber){
              if(response.building[i].access==true){
                temp="<input type=\"checkbox\" checked name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\"> "+response.building[i].descriptionFR+"<br>";

              }
              else if(response.building[i].access==false){
                temp="<input type=\"checkbox\" name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\"> "+response.building[i].descriptionFR+"<br>";

              }
              dest=dest.concat(temp);
              i++;
            }
            document.getElementById('buildingUpdateUser').innerHTML = dest;

            var i=0;
            var dest="<h4>Accès aux vélos</h4><input type=\"checkbox\" id=\"select-all-update\" name=\"select_all\" value=\"1\" /><br>";
            while(i<response.bikeNumber){
              if(response.bike[i].access==true){
                temp="<input type=\"checkbox\" checked name=\"bikeAccess[]\" value=\""+response.bike[i].bikeID+"\"> "+response.bike[i].bikeID+" - "+response.bike[i].model+"<br>";
              }
              else if(response.bike[i].access==false){
                temp="<input type=\"checkbox\" name=\"bikeAccess[]\" value=\""+response.bike[i].bikeID+"\"> "+response.bike[i].bikeID+" - "+response.bike[i].model+"<br>";
              }
              dest=dest.concat(temp);
              i++;
            }
            document.getElementById('bikeUpdateUserAdmin').innerHTML = dest;
            var dest="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#deleteUser\" onclick=\"initializeDeleteUserAdmin('"+response.user.email+"')\" data-toggle=\"modal\" href=\"#\"><span>Supprimer</span></a>";
            document.getElementById('updateUserSendButtonAdmin').innerHTML="<button class=\"button small green button-3d rounded icon-left\" type=\"submit\"><i class=\"fa fa-paper-plane\"></i>Envoyer</button>";
            document.getElementById('deleteUserButtonAdmin').innerHTML=dest;

            document.getElementById('select-all-updateAdmin').onclick = function() {
              var checkboxes = document.getElementsByName('bikeAccess[]');
              for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
              }
            }
          }
        }
      }
    });
  }
    //FleetManager: Nombre d'utilisateurs | Display summary of user details when "Supprimer" button is pressed
  function initializeDeleteUserAdmin(email){
    $.ajax({
      url: 'apis/Kameo/get_user_details.php',
      type: 'post',
      data: { "email": email},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          document.getElementById('widget-deleteUserAdmin-form-firstname').value = response.user.firstName;
          document.getElementById('widget-deleteUserAdmin-form-name').value = response.user.name;
          document.getElementById('widget-deleteUserAdmin-form-mail').value = response.user.email;
        }
      }
    });
    $('#updateUserInformationAdmin').modal('toggle');
  }

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
          document.getElementById('widget-reactivateUserAdmin-form-firstname').value = response.user.firstName;
          document.getElementById('widget-reactivateUserAdmin-form-name').value = response.user.name;
          document.getElementById('widget-reactivateUserAdmin-form-mail').value = response.user.email;
        }

      }
    })
    $('#updateUserInformationAdmin').modal('toggle');

  }
  //FleetManager: Nombre d'utilisateurs | Displays the msg to confim user creation
  function confirm_add_userAdmin(){
    document.getElementById('confirmAddUserAdmin').innerHTML="<p><strong>Attention</strong>, la création d'un compte entraînera l'envoi d'un mail vers la personne en question.<br>Veuillez confirmer que les informations mentionées précédemment sont correctes.</p><button class=\"fr button small green button-3d rounded icon-left\" type=\"submit\"><i class=\"fa fa-paper-plane\"></i>Confirmer</button>";
  }

  //FleetManager: Nombre d'utilisateurs | List the building, bikes and display the create button
  function create_userAdmin(){
    var requestor= "<?php echo $user_data['EMAIL']; ?>";
    var company=$('#widget_companyDetails_internalReference').val();
    $.ajax({
      url: 'apis/Kameo/get_building_listing.php',
      type: 'post',
      data: { "company": company},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          var i=0;
          var dest="";
          while (i < response.buildingNumber){
            temp="<input type=\"checkbox\" name=\"buildingAccess[]\" checked value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"<br>";
            dest=dest.concat(temp);
            i++;

          }
          document.getElementById('buildingCreateUserAdmin').innerHTML = dest;

          $.ajax({
            url: 'apis/Kameo/get_bikes_listing.php',
            type: 'post',
            data: { "company": company},
            success: function(response){
              if(response.response == 'error') {
                console.log(response.message);
              }
              if(response.response == 'success'){
                var i=0;
                var dest="";
                while (i < response.bikeNumber){
                  if(response.bike[i].biketype == 'partage'){
                    temp="<input type=\"checkbox\" name=\"bikeAccess[]\" checked value=\""+response.bike[i].id+"\">"+response.bike[i].frameNumber+" "+response.bike[i].model+"<br>";
                    dest=dest.concat(temp);
                  }
                  i++;
                }
                document.getElementById('bikeCreateUserAdmin').innerHTML = dest;
                $('#widget-addUserAdmin-form input[name=company]').val("");
                document.getElementById('confirmAddUserAdmin').innerHTML="<button class=\"fr button small green button-3d rounded icon-left\" onclick=\"confirm_add_userAdmin()\">\
                <i class=\"fa fa-paper-plane\">\
                </i>\
                Confirmer\
                </button>";


                document.getElementById('select-allAdmin').onclick = function() {
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
  }
