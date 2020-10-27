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
                document.getElementById('counterUsers').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.usersNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.usersNumber+"</span>";
            }
        }
    })
})

window.addEventListener("DOMContentLoaded", (event) => {
    document.getElementsByClassName('usersManagerClick')[0].addEventListener('click', function() { get_users_listing()}, false);
});





//FleetManager: Nombre d'utilisateurs | Displays the user list <table> by calling get_users_listing.php and creating it
  function get_users_listing(){
    var email= "<?php echo $user_data['EMAIL']; ?>";
    $.ajax({
      url: 'apis/Kameo/get_users_listing.php',
      type: 'post',
      data: { "email": email},
      success: function(response){
        if(response.response == 'success'){
          var dest="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Utilisateurs :</h4><h4 class=\"en-inline\">Users:</h4><h4 class=\"nl-inline\">Gebruikers:</h4><br><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addUser\" data-toggle=\"modal\" onclick=\"create_user()\" href=\"#\"><i class=\"fa fa-plus\"></i><?= L::generic_addUser; ?></a><tbody><thead><tr><th><span class=\"fr-inline\">Nom</span><span class=\"en-inline\">Name</span><span class=\"nl-inline\">Naam</span></th><th><span class=\"fr-inline\">Prénom</span><span class=\"en-inline\">Firstname</span><span class=\"nl-inline\">Voorname</span></th><th><span class=\"fr-inline\">e-mail</span><span class=\"en-inline\">mail</span><span class=\"nl-inline\">mail</span></th><th>Status</th><th></th></tr></thead>";
          for (var i = 0; i < response.usersNumber; i++){
            if(response.users[i].staann=='D'){
              var status="<span class=\"text-red\">Inactif</span>";
            }else{
              var status="Actif";
            }
            dest = dest.concat("<tr><td>"+response.users[i].name+"</td><td>"+response.users[i].firstName+"</td><td>"+response.users[i].email+"</td><td>"+status+"</td><td><a  data-target=\"#updateUserInformation\" name=\""+response.users[i].email+"\" data-toggle=\"modal\" class=\"text-green\" href=\"#\" onclick=\"update_user_information('"+response.users[i].email+"')\">Mettre à jour</a></td></tr>");
          }
          document.getElementById('usersList').innerHTML = dest;
          displayLanguage();
        }
        else {
          console.log(response.response + ': ' + response.message);
        }
      }
    })
  }
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
          document.getElementById('widget-updateUser-form-firstname').value = response.user.firstName;
          document.getElementById('widget-updateUser-form-name').value = response.user.name;
          document.getElementById('widget-updateUser-form-mail').value = response.user.email;
          document.getElementById('widget-updateUser-form-phone').value = response.user.phone;
          var dest="";
          if(response.user.staann=='D'){
            document.getElementById('widget-updateUser-form-status').value = "Inactif";
            $('#widget-updateUser-form-firstname').prop('readonly', true);
            $('#widget-updateUser-form-name').prop('readonly', true);
            $("#widget-updateUser-form input[name=fleetManager]").prop( "readonly", true );
            document.getElementById('buildingUpdateUser').innerHTML = "";
            document.getElementById('bikeUpdateUser').innerHTML = "";
            var dest="<a class=\"button small green button-3d rounded icon-right\" data-target=\"#reactivateUser\" onclick=\"initializeReactivateUser('"+response.user.email+"')\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Ré-activer</span><span class=\"en-inline\">Re-activate</span></a>";
            document.getElementById('updateUserSendButton').innerHTML="";
            document.getElementById('deleteUserButton').innerHTML=dest;
          }else{
            $('#widget-updateUser-form-firstname').prop('readonly', false);
            $('#widget-updateUser-form-name').prop('readonly', false);
            if(response.user.administrator=="Y"){
              $("#widget-updateUser-form input[name=fleetManager]").prop( "checked", true );
            }else{
              $("#widget-updateUser-form input[name=fleetManager]").prop( "checked", false );
            }

            document.getElementById('widget-updateUser-form-status').value = "Actif";
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
            document.getElementById('bikeUpdateUser').innerHTML = dest;
            var dest="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#deleteUser\" onclick=\"initializeDeleteUser('"+response.user.email+"')\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Supprimer</span><span class=\"en-inline\">Delete</span></a>";
            document.getElementById('updateUserSendButton').innerHTML="<button class=\"fr button small green button-3d rounded icon-left\" type=\"submit\"><i class=\"fa fa-paper-plane\"></i>Envoyer</button><button  class=\"en button small green button-3d rounded icon-left\" type=\"submit\" ><i class=\"fa fa-paper-plane\"></i>Send</button><button  class=\"nl button small green button-3d rounded icon-left\" type=\"submit\" ><i class=\"fa fa-paper-plane\"></i>Verzenden</button>";
            document.getElementById('deleteUserButton').innerHTML=dest;

            document.getElementById('select-all-update').onclick = function() {
              var checkboxes = document.getElementsByName('bikeAccess[]');
              for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
              }
            }
          }
        }
        $('#usersListing').modal('toggle');
        displayLanguage();
      }
    });
  }
    //FleetManager: Nombre d'utilisateurs | Display summary of user details when "Supprimer" button is pressed
  function initializeDeleteUser(email){
    $.ajax({
      url: 'apis/Kameo/get_user_details.php',
      type: 'post',
      data: { "email": email},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          document.getElementById('widget-deleteUser-form-firstname').value = response.user.firstName;
          document.getElementById('widget-deleteUser-form-name').value = response.user.name;
          document.getElementById('widget-deleteUser-form-mail').value = response.user.email;
        }
      }
    });
    $('#updateUserInformation').modal('toggle');
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
    document.getElementById('confirmAddUser').innerHTML="<p><strong>Attention</strong>, la création d'un compte entraînera l'envoi d'un mail vers la personne en question.<br>Veuillez confirmer que les informations mentionées précédemment sont correctes.</p><button class=\"fr button small green button-3d rounded icon-left\" type=\"submit\"><i class=\"fa fa-paper-plane\"></i>Confirmer</button>";
  }

  //FleetManager: Nombre d'utilisateurs | List the building, bikes and display the create button
  function create_user(){
    var email= "<?php echo $user_data['EMAIL']; ?>";
    $.ajax({
      url: 'apis/Kameo/get_building_listing.php',
      type: 'post',
      data: { "email": email},
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
          document.getElementById('buildingCreateUser').innerHTML = dest;

          $.ajax({
            url: 'apis/Kameo/get_bikes_listing.php',
            type: 'post',
            data: { "email": email},
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
                document.getElementById('bikeCreateUser').innerHTML = dest;
                $('#widget-addUser-form input[name=company]').val("");
                document.getElementById('confirmAddUser').innerHTML="<button class=\"fr button small green button-3d rounded icon-left\" onclick=\"confirm_add_user()\">\
                <i class=\"fa fa-paper-plane\">\
                </i>\
                Confirmer\
                </button>";


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
  }
