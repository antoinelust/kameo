//ajouter un contact
$('body').on('click', '.addContact', function(){
  //affichage de l ajout
  $('.contactAddIteration').fadeIn();
  //retrait du statut disabled des input
  $('.contactAddIteration').find('input').each(function(){
    $(this).prop('disabled', false);
  });
  $('.addContact').removeClass('glyphicon-plus').removeClass('green').removeClass('addContact').addClass('glyphicon-minus').addClass('red').addClass('removeContact');
});

//retrait de formulaire d'ajout
$(document).ready(function(){
  $('body').on('click', '.removeContact', function(){
    remove_contact_form();
  });
});

//Validation formulaire
$('body').on('click', '.addCompanyContact', function(){
  var responseContent = "";
  var sendData = true;
  //verification de la validité des champs
  $('.contactAddIteration').find('input').each(function(){
    //si un champ est invalide, on empèche la requete
    if(!$(this).valid()){
      sendData = false;
    }

  });
  if (sendData) {
    var that = $('.contactAddIteration');
    add_contact(that).done(function(response, selecteur = that){
      //response = JSON.parse(response);
      var bikesStatsChecked = "";
      if(response.bikesStats == "Y"){
        bikesStatsChecked = "checked";
      }
      var id = (response.id != undefined) ? response.id : '';
      var email = (response.emailContact != undefined) ? response.emailContact : '';
      var companyId = (response.companyId != undefined) ? response.companyId : '';
      var lastName = (response.lastName != undefined) ? response.lastName : '';
      var firstName = (response.firstName != undefined) ? response.firstName : '';
      var phone = (response.phone != undefined) ? response.phone : '';
      var fonction = (response.fonction != undefined) ? response.fonction : '';
      responseContent = `<tr class="form-group">
        <td>
          <input type="text" class="form-control required emailContact" readonly="true"  name="contactEmail`+id+`" id="contactEmail`+id+`" value="`+email+`" required/>
        </td>
        <td>
        <input type="text" class="form-control required lastName" readonly="true"  name="contactNom`+id+`" id="contactNom`+id+`" value="`+lastName+`" required/>
        </td>
        <td>
        <input type="text" class="form-control required firstName" readonly="true" name="contactPrenom`+id+`" id="contactPrenom`+id+`" value="`+firstName+`" required/>
        </td>
        <td>
        <input type="tel" class="form-control phone" readonly="true"  name="contactPhone`+id+`" id="contactPhone`+id+`" value="`+phone+`"/>
        </td>
        <td>
        <input type="text" class="form-control fonction" readonly="true"  name="contactFunction`+id+`" id="contactFunction`+id+`" value="`+fonction+`"/>
        </td>
        <td>
        <input type="checkbox" class="form-control bikesStats" readonly="true"  name="contactBikesStats`+id+`" id="contactBikesStats`+id+`" value="bikesStats" `+bikesStatsChecked+`/>
        </td>
        <td>
          <button class="modify button small green button-3d rounded icon-right glyphicon glyphicon-pencil" type="button"></button>
        </td>
        <td>
          <button class="delete button small red button-3d rounded icon-right glyphicon glyphicon-remove" type="button"></button>
        </td>
        <input type="hidden" class="contactIdHidden" name="contactId`+id+`" id="contactId`+id+`" value="`+id+`"/>
      </tr>`;
      $('.clientContactZone').find('.contactsTable').find('tbody').append(responseContent);
      nbContacts++;
    });

    //retirer le visuel d'ajout
    $(this).parents('.contactAddIteration').find('input').each(function(){
      $(this).val('');
    });
    remove_contact_form();
  }

});

function add_contact(that){
  return $.ajax({
      url: 'include/add_company_contact.php',
      method: 'post',
      data: {
        'companyId': $('#companyIdHidden').val(),
        'contactEmail':$(that).find('.emailContact').val(),
        'firstName': $(that).find('.firstNameContact').val(),
        'lastName': $(that).find('.lastNameContact').val(),
        'phone': $(that).find('.phoneContact').val(),
        'function': $(that).find('.functionContact').val(),
        'bikesStats': $(that).find('.bikeStatsContact').prop('checked')
      },
      success: function(response){
        console.log(response);
      }
  });
}
