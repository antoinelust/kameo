  //FleetManager: Nombre d'utilisateurs | Display user details when "Mettre à jour" button is pressed
  function update_user_information_admin(email){
    $.ajax({
      url: 'apis/Kameo/get_user_details.php',
      type: 'post',
      data: { "email": email},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          $('#widget-updateUserAdmin-form input[name=widget-updateUser-form-firstname]').val(response.user.firstName);
          $('#widget-updateUserAdmin-form input[name=widget-updateUser-form-name]').val(response.user.name);
          $('#widget-updateUserAdmin-form input[name=widget-updateUser-form-mail]').val(response.user.email);
          $('#widget-updateUserAdmin-form input[name=widget-updateUser-form-phone]').val(response.user.phone);
          if(response.user.administrator=="Y"){
            $("#widget-updateUserAdmin-form input[name=fleetManager]").prop( "checked", true );
          }else{
            $("#widget-updateUserAdmin-form input[name=fleetManager]").prop( "checked", false );
          }
          if(response.user.staann != 'D'){
            $("#widget-updateUserAdmin-form input[name=status]").val("Actif");
          }else{
            $("#widget-updateUserAdmin-form input[name=status]").val("Inactif");
          }
          var i=0;
          var dest="<h4 class='text-green'>"+traduction.generic_accessToBuildings+"</h4>";
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
          $("#widget-updateUserAdmin-form span[name=buildingUpdateUserAdmin]").html(dest);
          var i=0;
          var dest="<h4>Accès aux vélos</h4><input type=\"checkbox\" id=\"select-all-update\" name=\"select_all\" value=\"1\" />"+traduction.generic_selectAll+"<br>";
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
          $("#widget-updateUserAdmin-form span[name=bikeUpdateUserAdmin]").html(dest);
          $("#widget-updateUserAdmin-form span[name=updateUserSendButtonAdmin]").html("<button class=\"button small green button-3d rounded icon-left\" type=\"submit\"><i class=\"fa fa-paper-plane\"></i>Envoyer</button>");
          $('widget-updateUserAdmin-form input[select-all-updateAdmin]').click(function(){
            var checkboxes = document.getElementsByName('bikeAccess[]');
            for (var checkbox of checkboxes) {
              checkbox.checked = this.checked;
            }
          });
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
            url: 'api/bikes',
            type: 'get',
            data: { "company": company, 'action': 'list'},
            success: function(response){
              if(response.response == 'error') {
                console.log(response.message);
              }
              if(response.response == 'success'){
                var i=0;
                var dest="";
                while (i < response.bike.length){
                  if(response.bike[i].biketype == 'partage'){
                    temp="<input type=\"checkbox\" name=\"bikeAccess[]\" checked value=\""+response.bike[i].id+"\">"+response.bike[i].frameNumber+" "+response.bike[i].model+"<br>";
                    dest=dest.concat(temp);
                  }
                  i++;
                }
                document.getElementById('bikeCreateUserAdmin').innerHTML = dest;
                $('#widget-addUserAdmin-form input[name=company]').val(company);
                console.log(company);
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
