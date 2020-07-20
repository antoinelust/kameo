var contactInfo  = [];
var contactKeys = [];

$('.clientContactZone').on('click','.modify', function(){
  $(this).removeClass('modify').addClass('validate').removeClass('glyphicon-pencil').addClass('glyphicon-ok');
  $(this).parents('tr').find('.delete').removeClass('delete').removeClass('red').addClass('white').addClass('annuler').removeClass('glyphicon-remove').addClass('glyphicon-repeat');
  $(this).parents('tr').find('input').each(function(){
	contactInfo.push($(this).val());
	contactKeys.push($(this).attr('id'));
	$(this).prop('readonly', false);
  });
});

$('.clientContactZone').on('click','.annuler', function(){
  $(this).parents('tr').find('.validate').removeClass('validate').addClass('modify').addClass('glyphicon-pencil').removeClass('glyphicon-ok');
  $(this).removeClass('annuler').removeClass('white').addClass('delete').addClass('red').addClass('glyphicon-remove').removeClass('glyphicon-repeat');
  $(this).parents('tr').find('input').each(function(){
	var that = $(this);
	for (var i = contactKeys.length -1; i >= 0; i--) {
	  //si l'id correspond a l'input
	  if (contactKeys[i] == $(that).attr('id')) {
		//on remet l'ancienne valeur
		$(that).val(contactInfo[i]);
		//on retire l'entrée du tableau de clés
		contactKeys.splice(i,1);
		//on retire l'entrée du tableau contactInfo
		contactInfo.splice(i,1);
	  }
	}
	$(that).prop('readonly', true);
  });
});

$('.clientContactZone').on('click', '.validate', function(){
  var valid = true;
  var that = $(this);
  $(this).parents('tr').find('input').each(function(){
	//verification de la validité des champs
	if (!$(this).valid()) {
	  valid = false;
	}
  });
  if (valid) {
	edit_contact($(this).parents('tr')).done(function(response){
	  $(that).parents('tr').find('.validate').removeClass('validate').addClass('modify').addClass('glyphicon-pencil').removeClass('glyphicon-ok');
	  $(that).parents('tr').find('.annuler').removeClass('annuler').removeClass('white').addClass('delete').addClass('red').addClass('glyphicon-remove').removeClass('glyphicon-repeat');
	  $(that).parents('tr').find('input').each(function(){
		//suppression des valeurs dans les tableaux
		var that = $(this);
		for (var i = contactKeys.length -1; i >= 0; i--) {
		  //si l'id correspond a l'input
		  if (contactKeys[i] == $(that).attr('id')) {
			//on retire l'entrée du tableau de clés
			contactKeys.splice(i,1);
			//on retire l'entrée du tableau contactInfo
			contactInfo.splice(i,1);
		  }
		}
		$(this).prop('readonly', true);
	  });
	});
  }

});


$('.clientContactZone').on('click', '.delete', function(){
  if(confirm('Êtes-vous sur de vouloir supprimer ce contact ? Cette action est irréversible.')){
	that = $(this);
	if( nbContacts > 1) {
	  delete_contact($(this).parents('tr'), $(this).parents('tr').find('.contactIdHidden').val()).done(function(response){
		$(that).parents('tr').fadeOut(function(){
		  $(that).parents('tr').remove();
		  nbContacts--;
		});
	  });
	}
	else{
	  $.notify({
		message: "Impossible d'effectuer la suppression, il faut au minimum une personne de contact"
	  }, {
		type: 'danger'
	  });
	}

  }
});