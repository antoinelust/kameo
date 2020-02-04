//ajouter un contact
$('.addContact')[0].addEventListener('click', function(){
  var content = "";
  content +=`
  <div class="contactAddIteration">
    <div class="col-sm-12 form-group">
      <label for="contact">Ajout de contact</label>
      <button class="removeContact button small red button-3d rounded icon-right glyphicon glyphicon-minus" type="button"></button>
    </div>
    <div class="col-md-3 form-group">
      <label for="email_billing" class="fr"> Email : </label>
      <input type="text" class="form-control emailContact required" name="email" placeholder="email" />
    </div>
    <div class="col-md-3 form-group">
      <label class="fr" >Nom :</label>
      <input type="text" class="form-control lastNameContact required" name="lastName" placeholder="nom" />
    </div>
    <div class="col-md-3 form-group">
      <label class="fr" >Prénom :</label>
      <input type="text" class="form-control firstNameContact required" name="firstName" placeholder="prenom" />
    </div>
    <div class="col-md-3 form-group">
      <label class="fr" >Téléphone :</label>
      <input type="text" class="form-control phoneContact" name="phone" placeholder="téléphone" />
    </div>
    <div class="col-md-3 form-group">
      <label class="fr" >Fonction :</label>
      <input type="text" name="function" class="form-control functionContact required" placeholder="Fonction" />
    </div>
    <div class="col-md-6 form-group">
      <label class="fr" >Recevoir les statistiques d'utilisation des vélos :</label>
      <input type="checkbox" name="bikesStats" class="form-control bikeStatsContact" value="true" />
    </div>
    <div class="col-sm-12 form-group" style="margin-top:20px;">
      <button class="button small green button-3d rounded icon-right addCompanyContact">
      <span class="fr-inline" style="display: inline;">
      <i class="fa fa-plus"></i> Ajouter le contact</span></button>
    </div>
    <input type="hidden" class="companyIdHidden" name="companyId" value="`+companyId+`" />
    <div class="separator separator-small"></div>
  </div>

  `;
  $('.clientContactZone').append(content);
});

//retrait de formulaire d'ajout
$(document).ready(function(){
  $('body').on('click', '.removeContact', function(){
    $(this).parents('.contactAddIteration').fadeOut('600',function(){
      $(this).remove();
    });
  });
});

//Validation formulaire
$('body').on('click', '.addCompanyContact', function(){
  var sendData = true;
  //verification de la validité des champs
  $(this).parents('.contactAddIteration').find('input').each(function(){
    //si un champ est invalide, on empèche la requete
    if(!$(this).valid()){
      sendData = false;
    }

  });
  if (sendData) {
    var that = $(this).parents('.contactAddIteration');
    $.ajax({
        url: 'include/add_company_contact.php',
        method: 'post',
        data: {
          'companyId': $(that).find('.companyIdHidden').val(),
          'email': $(that).find('.emailContact').val(),
          'firstName': $(that).find('.firstNameContact').val(),
          'lastName': $(that).find('.lastNameContact').val(),
          'function': $(that).find('.functionContact').val(),
          'bikesStats': $(that).find('.bikeStatsContact').val()
        },
        success: function(response){
          console.log(response);
        }
    });
  }

});
