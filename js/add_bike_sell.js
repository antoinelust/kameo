var insuranceBool = $('#insuranceBikeCheck').prop("checked");
var contactStartBloc = $('.contractInfos .contractStartBloc');
var contactEndBloc = $('.contractInfos .contractEndBloc');
//A chaque changement d'état du select
$('.contractInfos').on('change','select',function(){
  //cas vente
  if ($(this).val() == "selling") {
    //changement des labels
    $(contactStartBloc).find('.fr').html('Date de vente');
    $(contactStartBloc).find('.en').html('Sell date');
    $(contactStartBloc).find('.nl').html('Sell date');

    $(contactEndBloc).find('.fr').html("Date de fin d'assurance");
    $(contactEndBloc).find('.en').html('Insurance end date');
    $(contactEndBloc).find('.nl').html('Insurance end date');

    if (!insuranceBool) {
      $('.contractInfos').find('.contractEndBloc input').prop('disabled','disabled');
      $('.contractInfos').find('.contractEndBloc').fadeOut();
    } else{
      $('.contractInfos').find('.contractEndBloc input').removeAttr('disabled');
      $('.contractInfos').find('.contractEndBloc').fadeIn();
    }
    //affichage de l'input pour le prix de vente
    $('#bikeSoldPrice').addClass('required').removeAttr('disabled').prop('required','required');
    $('.soldPrice').fadeIn();

    //autres types de vente/location de vélo
  } else{
    //changement des labels
    $(contactStartBloc).find('.fr').html('Début de contrat');
    $(contactStartBloc).find('.en').html('Contract start');
    $(contactStartBloc).find('.nl').html('Contract start');

    $(contactEndBloc).find('.fr').html("Fin de contrat");
    $(contactEndBloc).find('.en').html('Contract End');
    $(contactEndBloc).find('.nl').html('Contract End');

    $('.contractInfos').find('.contractEndBloc input').removeAttr('disabled');
    $('.contractInfos').find('.contractEndBloc').fadeIn();

    //affichage de l'input pour le prix de vente
    $('#bikeSoldPrice').removeClass('required').removeAttr('required').prop('disabled','disabled');
    $('.soldPrice').fadeOut();
  }
  currentOption = $('.contractInfos select').val();
});

//gestion assurance
$('.contractInfos .insurance').on('click','input',function(){
  insuranceBool = $('#insuranceBikeCheck').prop('checked');
  if ($('.contractInfos select[name=contractType]').val() == "selling") {
    //si on a une assurance, on affiche le bloc de fin de contrat
    if(insuranceBool){
      $('.contractInfos').find('.contractEndBloc input').removeAttr('disabled');
      $('.contractInfos').find('.contractEndBloc').fadeIn();
    }else{
      $('.contractInfos').find('.contractEndBloc input').prop('disabled','disabled');
      $('.contractInfos').find('.contractEndBloc').fadeOut();
    }

  }
});
