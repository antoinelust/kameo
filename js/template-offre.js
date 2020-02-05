//AJAX

//liste des vélos
function get_all_bikes() {
  return  $.ajax({
    url: 'include/get_bikes_catalog.php',
    type: 'post',
    data: {},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
    }
  });
}

//récuperation du prix de leasing en fct du prix HTVA
function get_leasing_price(retailPrice){
  return  $.ajax({
    url: 'include/get_prices.php',
    method: 'post',
    data: {'retailPrice' : retailPrice},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
    }
  });
}

//liste des boxes
function get_all_boxes() {
  return  $.ajax({
    url: 'include/get_boxes_catalog.php',
    type: 'post',
    data: {},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
    }
  });
}

//liste des Accessoires
function get_all_accessories() {
  return  $.ajax({
    url: 'include/get_accessories_catalog.php',
    type: 'post',
    data: {},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
    }
  });
}


//FIN AJAX

//variables globales
var leasingDuration = $('.leasingDuration').val();
var bikesNumber = 0;
var boxesNumber = 0;
var accessoriesNumber = 0;
var othersNumber = 0;



//gestion affichage selon leasing ou non
//la class "removed" permet d'indiquer lors de l'envoi en bdd pour génération du pdf
//que ces informations ne sont pas utiles

$('#buyOrLeasingSelect').on('change',function(){
  var val =$(this).val();
  if(val == "leasing"){
    //Gestion de la partie achat/leasing
    $('.buyOrLeasing .leasingSpecific').removeClass('removed').addClass('required');
    $('.buyOrLeasing .leasingSpecific input').prop("disabled", false);
    $('.buyOrLeasing .leasingSpecific').fadeIn();
    //gestion bike
      //prix achat/leasing
    $('.bikeLeasing').fadeIn();
    $('.TD_bikeLeasing').addClass('inRecapLeasingBike');
    $('.bikepVenteHTVA').fadeOut();
    $('.TD_bikepVenteHTVA').removeClass('inRecapVenteBike');
      //marge
    $('.bikeMarge .margeLeasing').fadeIn();
    $('.bikeMarge .margeVente').fadeOut();
    $('.bikeMarge').fadeIn();
    //gestion du form complet
    $("#templateForm").addClass('isLeasing');

  }else if(val == "buy"){
    //Gestion de la partie achat/leasing
    $('.buyOrLeasing .leasingSpecific').addClass('removed').removeClass('required');
    $('.buyOrLeasing .leasingSpecific input').prop("disabled", true);
    $('.buyOrLeasing .leasingSpecific').fadeOut();
    //gestion bike
      //prix achat/leasing
    $('.bikeLeasing').fadeOut();
    $('.TD_bikeLeasing').removeClass('inRecapLeasingBike');
    $('.bikepVenteHTVA').fadeIn();
    $('.TD_bikepVenteHTVA').addClass('inRecapVenteBike');
      //marge
    $('.bikeMarge .margeLeasing').fadeOut();
    $('.bikeMarge .margeVente').fadeIn();
    $('.bikeMarge').fadeIn();
    //gestion du form complet
    $("#templateForm").removeClass('isLeasing');

  } else {
    //affiche les champs relatifs au leasing
    $('.buyOrLeasing .leasingSpecific').removeClass('removed').addClass('required');
    $('.buyOrLeasing .leasingSpecific input').prop("disabled", false);
    $('.buyOrLeasing .leasingSpecific').fadeIn();
    //gestion bike
      //prix achat/leasing
    $('.bikeLeasing').fadeIn();
    $('.TD_bikeLeasing').addClass('inRecapLeasingBike');
    $('.bikepVenteHTVA').fadeIn();
    $('.TD_bikepVenteHTVA').addClass('inRecapVenteBike');

      //marge
    $('.bikeMarge .margeLeasing').fadeOut();
    $('.bikeMarge .margeVente').fadeOut();
    $('.bikeMarge').fadeOut();

    //gestion du form complet
    $("#templateForm").addClass('isBoth');

  }
});
//gestion du input dureeLeasing
$('.buyOrLeasing .leasingDuration').on('change',function(){
  if ($(this).val() <= 0) {
    $(this).parent().addClass('has-error');
  } else {
    $(this).parent().removeClass('has-error');
  }
  leasingDuration = $('.leasingDuration').val();
  //changement de la valeur de la marge du leasing des vélos
  var leasingVal = [];
  var pAchat = [];
  //recuperation des valeur de leasing
  $('.bikeRow .bikeLeasing').each(function(){
    leasingVal.push($(this).html().split('€')[0]);
  });
  //recuperation des prix d'achat
  $('.bikeRow .bikepAchat').each(function(){
    pAchat.push($(this).html().split('€')[0]);
  });
  //calcul des marges
  var i = 0
  $('.bikeRow .bikeMarge').each(function(){
    if($(this).find('.margeLeasing').html() != 'non calculable'){
      var marge = (leasingDuration*leasingVal[i] - pAchat[i]).toFixed(2);
      $(this).find('.margeLeasing').html(marge + '€');
    }
    i++;
  });

});

//gestion du input numberMaintenance
$('.buyOrLeasing .numberMaintenance').on('change',function(){
  if ($(this).val() < 0) {
    $(this).parent().addClass('has-error');
  } else {
    $(this).parent().removeClass('has-error');
  }
});

//création des variables
var bikes = [];
get_all_bikes().done(function(response){

  bikes = response.bike;
  //tableau bikes avec tout les champs
  var bikeModels = "<option hidden disabled selected value></option>";

  //tri du tableau par marques
  bikes.sort(compare);

  //gestion du moins au lancement de la page
  checkMinus('.templateBike','.bikesNumber');

  //generation des Options

  //velo

  for (var i = 0; i < bikes.length; i++) {
    var elec = "";
    if(bikes[i].electric == 'Y'){
      elec = ' - Elec';
    }
    bikeModels += '<option value="' + bikes[i].id + '">' + bikes[i].brand + ' - ' + bikes[i].model + ' - ' + bikes[i].frameType + elec + '</option>';
  }

  //a chaque modification du nombre de vélo
  //ajout
  $('.templateBike .glyphicon-plus')[0].addEventListener("click",function(){
    bikesNumber = $("#template").find('.bikesNumber').html()*1+1;
    $('#template').find('.bikesNumber').html(bikesNumber);
    $('#bikesNumber').val(bikesNumber);


    var hideBikepVenteHTVA;
    var hideBikeLeasing ='';
    var inRecapLeasingBike ='';
    var inRecapVenteBike ='';

    if ($("#templateForm").hasClass('isLeasing')) {
      hideBikepVenteHTVA = 'style="display:none"';
      inRecapLeasingBike ='inRecapLeasingBike';

    }else{
      hideBikeLeasing = 'style="display:none"';
      inRecapVenteBike = 'inRecapVenteBike';
    }
    //creation du div contenant
    $('#template').find('.templateBike tbody')
    .append(`<tr class="bikesNumberTable`+(bikesNumber)+` bikeRow form-group">
    <td class="bLabel"></td>
    <td class="bikeBrandModel"></td>
    <td class="bikepAchat"></td>
    <td class="bikepVenteHTVA TD_bikepVenteHTVA `+inRecapVenteBike+`"`+hideBikepVenteHTVA+`></td>
    <td class="bikeLeasing TD_bikeLeasing `+inRecapLeasingBike+`"`+hideBikeLeasing+`></td>
    <td class="bikeMarge"></td>
    </tr>`);

    //label selon la langue
    $('#template').find('.bikesNumberTable'+(bikesNumber)+'>.bLabel')
    .append('<label class="fr">Vélo '+ bikesNumber +'</label>');
    /*$('#template').find('.bikesNumberTable'+bikesNumber+'>.bLabel').append('<span class="en">Bike '+ bikesNumber +'</span>');
    $('#template').find('.bikesNumberTable'+bikesNumber+'>.bLabel').append('<span class="nl">Vélo '+ bikesNumber +'</span>');*/

    $('#template').find('.bikesNumberTable'+(bikesNumber)+'>.bikeBrandModel')
    .append(`<select name="bikeBrandModel`+bikesNumber+`" class="select`+bikesNumber+` form-control required">`+
    bikeModels+
    `</select>`);


    //gestion du select du velo
    $('.templateBike select').on('change',function(){

      var that ='.'+ $(this).attr('class').split(" ")[0];
      var id =$(that).val();

      //récupère le bon index même si le tableau est désordonné
      id = getIndex(bikes, id);

      var pAchat = bikes[id].buyingPrice + '€ ';
      var pVenteHTVA = bikes[id].priceHTVA + '€ ';
      var margeVente = (bikes[id].priceHTVA - bikes[id].buyingPrice).toFixed(2) + '€';
      get_leasing_price(bikes[id].priceHTVA).done(function(response){

        //recuperation du prix leasing
        var leasingPrice = response.leasingPrice;
        var margeLeasing = (leasingDuration*leasingPrice - bikes[id].buyingPrice).toFixed(2) + '€';
        //gestion de prix null
        if (bikes[id].buyingPrice == null) {
          pAchat = 'non renseigné';
          margeVente = 'non calculable';
          margeLeasing = 'non calculable';
        }
        //gestion de l'affichage de la bonne marge et du tableau recap
        if($("#templateForm").hasClass('isLeasing')){
          marge = '<span class="margeLeasing">'+margeLeasing+'<\/span><span class="margeVente" style="display:none">'+margeVente+'<\/span>';

        } else {
          marge = '<span class="margeLeasing" style="display:none">'+margeLeasing+'<\/span><span class="margeVente">'+margeVente+'<\/span>';
        }

        //gestion de prix null
        if (bikes[id].buyingPrice == null) {
          pAchat = 'non renseigné';
          marge = 'non calculable';
        }

        $(that).parents('.bikeRow').find('.bikepAchat').html(pAchat);
        $(that).parents('.bikeRow').find('.bikepVenteHTVA').html(pVenteHTVA);
        $(that).parents('.bikeRow').find('.bikeMarge').html(marge);
        $(that).parents('.bikeRow').find('.bikeLeasing').html(leasingPrice + '€');
      });
    });
    checkMinus('.templateBike','.bikesNumber');
  });

  //retrait
  $('.templateBike .glyphicon-minus')[0].addEventListener("click",function(){
    bikesNumber = $("#template").find('.bikesNumber').html();
    if(bikesNumber > 0){
      $('#template').find('.bikesNumber').html(bikesNumber*1 - 1);
      $('#bikesNumber').val(bikesNumber*1 - 1);
      $('#template').find('.bikesNumberTable'+bikesNumber).slideUp().remove();
      bikesNumber--;
    }
    checkMinus('.templateBike','.bikesNumber');
  });
});

//boxes
var boxes = [];
get_all_boxes().done(function(response){
  //variables
  boxes = response.boxes;

  //gestion bouton moins
  checkMinus('.templateBoxes','.boxesNumber');

  //ajout
  $('.templateBoxes .glyphicon-plus')[0].addEventListener("click",function(){
    //gestion boxNumber
    boxesNumber = $("#template").find('.boxesNumber').html()*1+1;
    $('#template').find('.boxesNumber').html(boxesNumber);
    $('#boxesNumber').val(boxesNumber);

    //boxModels
    var boxesModels = "<option hidden disabled selected value></option>";
    for (var i = 0; i < boxes.length; i++) {
      boxesModels += '<option value="'+boxes[i].id+'">'+boxes[i].model+'</option>';
    }




    //creation du tr contenant
    $('#template').find('.templateBoxes tbody')
    .append(`<tr class="boxesNumberTable`+(boxesNumber)+` boxRow form-group">
              <td class="boxLabel"></td><td class="boxModel"></td>
              <td class="boxProdPrice"></td>
              <td class="boxInstallationPrice"></td>
              <td class="boxLocationPrice"></td>
              <td class="boxMarge"></td>
              </tr>`);

    //label selon la langue
    $('#template').find('.boxesNumberTable'+(boxesNumber)+'>.boxLabel').append('<label class="fr">Box '+ boxesNumber +'</label>');

    //select boxModel
    $('#template').find('.boxesNumberTable'+(boxesNumber)+'>.boxModel')
    .append(`<select name="boxModel`+boxesNumber+`" class="select`+boxesNumber+` form-control required">`+
    boxesModels+`
    </select>`);

    //gestion du select de la box
    $('.templateBoxes select').on('change',function(){

      var that = '.' + $(this).attr('class').split(" ")[0];
      var boxId =$('.templateBoxes ' + that).val();

      //récupère le bon index même si le tableau est désordonné
      boxId = getIndex(boxes, boxId);
      var productionPrice = boxes[boxId].productionPrice + '€ ';
      var installationPrice = boxes[boxId].installationPrice + '€ ';
      var locationPrice = boxes[boxId].locationPrice + '€ ';
      var marge = (boxes[boxId].installationPrice - boxes[boxId].productionPrice*1 + (boxes[boxId].locationPrice*36)).toFixed(2) + '€';

      $(that).parents('.boxRow').find('.boxProdPrice').html(productionPrice)
      $(that).parents('.boxRow').find('.boxInstallationPrice').html(installationPrice).addClass('inRecapInstallBox');
      $(that).parents('.boxRow').find('.boxMarge').html(marge);
      $(that).parents('.boxRow').find('.boxLocationPrice').html(locationPrice).addClass('inRecapLocationBox');

    });
    checkMinus('.templateBoxes','.boxesNumber');
  });


  //retrait
  $('.templateBoxes .glyphicon-minus')[0].addEventListener("click",function(){
    boxesNumber = $("#template").find('.boxesNumber').html();
    if(boxesNumber > 0){
      $('#template').find('.boxesNumber').html(boxesNumber*1 - 1);
      $('#boxesNumber').val(boxesNumber*1 - 1);
      $('#template').find('.boxesNumberTable'+boxesNumber).slideUp().remove();
      boxesNumber--;
    }
    checkMinus('.templateBoxes','.boxesNumber');
  });
});


//Accessoires
get_all_accessories().done(function(response){
  //gestion du moins au lancement de la page
  checkMinus('.templateAccessories','.accessoriesNumber');
  //variables
  var accessories = response.accessories;
  var categories = [];

  //generation du tableau de catégories
  accessories.forEach((accessory) => {
    var newCategory = true;
    categories.forEach((category) => {
      if (category.name === accessory.category) {
        newCategory = false;
      }
    });
    if (newCategory === true) {
      categories.push({'id' : accessory.categoryId, 'name' : accessory.category});
    }
  });


  $('.templateAccessories .glyphicon-plus')[0].addEventListener("click",function(){
    //gestion accessoriesNumber
    accessoriesNumber = $("#template").find('.accessoriesNumber').html()*1+1;
    $('#template').find('.accessoriesNumber').html(accessoriesNumber);
    $('#accessoriesNumber').val(accessoriesNumber);

    //ajout des options du select pour les catégories
    var categoriesOption = "<option hidden disabled selected value></option>";
    categories.forEach((category) => {
      categoriesOption += '<option value="'+category.id+'">'+category.name+'</option>';
    });

    //ajout d'une ligne au tableau des accessoires
    $('#template').find('.otherCostsAccesoiresTable tbody')
    .append(`<tr class="otherCostsAccesoiresTable`+(accessoriesNumber)+` accessoriesRow form-group">
    <td class="aLabel"></td><td class="aCategory"></td><td class="aAccessory"></td>
    <td class="aBuyingPrice"></td><td class="aPriceHTVA"></td>
    </tr>`);
    //label selon la langue
    $('#template').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aLabel')
    .append('<label class="fr">Accessoire '+ accessoriesNumber +'</label>');

    //select catégorie
    $('#template').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aCategory')
    .append(`<select name="accessoryCategory`+accessoriesNumber+`" id="selectCategory`+accessoriesNumber+`" class="selectCategory form-control required">`+
    categoriesOption+`
    </select>`);
    //select Accessoire
    $('#template').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aAccessory')
    .append('<select name="accessoryAccessory'+accessoriesNumber+'" id="selectAccessory'+
    accessoriesNumber+
    '"class="selectAccessory form-control required"></select>');

    checkMinus('.templateAccessories','.accessoriesNumber');

    //on change de la catégorie
    $('.templateAccessories').find('.selectCategory').on("change",function(){
      var that = '#' + $(this).attr('id');
      var categoryId =$(that).val();
      var accessoriesOption = "<option hidden disabled selected value>Veuillez choisir un accesoire</option>";

      //ne garde que les accessoires de cette catégorie
      accessories.forEach((accessory) => {
        if (categoryId == accessory.categoryId) {
          accessoriesOption += '<option value="'+accessory.id+'">'+accessory.name+'</option>';
        }
      });
      //place les accessoires dans le select
      $(that).parents('tr').find('.selectAccessory').html(accessoriesOption);

      //retire l'affichage d'éventuels prix
      $(that).parents('.accessoriesRow').find('.aBuyingPrice').html('');
      $(that).parents('.accessoriesRow').find('.aPriceHTVA').html('').removeClass('inRecapVenteAccessory');
    });

    $('.templateAccessories').find('.selectAccessory').on("change",function(){
      var that = '#' + $(this).attr('id');
      var accessoryId =$(that).val();

      //récupère le bon index même si le tableau est désordonné
      accessoryId = getIndex(accessories, accessoryId);

      var buyingPrice = accessories[accessoryId].buyingPrice + '€';
      var priceHTVA = accessories[accessoryId].priceHTVA + '€';

      $(that).parents('.accessoriesRow').find('.aBuyingPrice').html(buyingPrice);
      $(that).parents('.accessoriesRow').find('.aPriceHTVA').html(priceHTVA).addClass('inRecapVenteAccessory');
    });


  });

  //retrait
  $('.templateAccessories .glyphicon-minus')[0].addEventListener("click",function(){
    accessoriesNumber = $("#template").find('.accessoriesNumber').html();
    if(accessoriesNumber > 0){
      $('#template').find('.accessoriesNumber').html(accessoriesNumber*1 - 1);
      $('#accessoriesNumber').val(accessoriesNumber*1 - 1);
      $('#template').find('.otherCostsAccesoiresTable'+accessoriesNumber).slideUp().remove();
      accessoriesNumber--;
    }
    checkMinus('.templateAccessories','.accessoriesNumber');
  });

});

//Autres

$(document).ready(function(){

  checkMinus('.templateOthers','.othersNumber');
  //ajout
  $('.templateOthers .glyphicon-plus')[0].addEventListener("click",function(){
    //gestion boxNumber
    othersNumber = $("#template").find('.othersNumber').html()*1+1;
    $('#template').find('.othersNumber').html(othersNumber);
    $('#othersNumber').val(othersNumber);

    //creation du tr contenant
    $('#template').find('.templateOthers tbody')
                  .append(`<tr class="othersNumberTable`+(othersNumber)+` otherRow form-group">
                            <td class="othersLabel"></td>
                            <td class="othersDescription">
                              <input type="textArea" class="form-control required" name="othersDescription`+othersNumber+`" placeholder="Description"/>
                            </td>
                            <td class="othersCost input-group">
                              <span class="input-group-addon">€</span>
                              <input type="number" class="form-control currency required inRecapOthersCost" name="othersCost`+othersNumber+`" min="0" />
                            </td>
                          </tr>`);

    //label selon la langue
    $('#template').find('.othersNumberTable'+(othersNumber)+'>.othersLabel').append('<label class="fr">Autre '+ othersNumber +'</label>');


    checkMinus('.templateOthers','.othersNumber');
  });

  //retrait
  $('.templateOthers .glyphicon-minus')[0].addEventListener("click",function(){
    othersNumber = $("#template").find('.othersNumber').html();
    if(othersNumber > 0){
      $('#template').find('.othersNumber').html(othersNumber*1 - 1);
      $('#othersNumber').val(othersNumber*1 - 1);
      $('#template').find('.othersNumberTable'+othersNumber).slideUp().remove();
      othersNumber--;
    }
    checkMinus('.templateOthers','.othersNumber');
  });
})

//Generation du tableau recap
$('#generateTableRecap')[0].addEventListener('click',function(){
  //destruction d'un éventuel tableau déjà présent
  $('.summaryTable tbody').html('');
  $('.summaryTable tfoot').html('');
  //variables
  var prixAchatTotal = 0;
  var prixLocationTotal = 0;
    //bikes

  var bikesRecap = {
    'leasing' : [],
    'vente' : []
  };

    //boxes
  var boxesRecap ={
    'install' : [],
    'location' : []
  };

    //accessories
  var accessoriesRecap = {
    'vente' : []
  };
    //Autres
  var othersRecap = {
    'description' : [],
    'cost' : []
  };

    //compteur
  var count = 0;
  //génération des valeurs pour la partie bikes
    //valeurs venteBike
  $('.inRecapVenteBike').each(function(){
    bikesRecap.vente.push($(this).html().split('€')[0]);
    count++;
  });
  count=0;
    //valeurs leasingBike
  $('.inRecapLeasingBike').each(function(){
    bikesRecap.leasing.push($(this).html().split('€')[0]);
    count++;
  });
  count=0
    //valeurs installBox
  $('.inRecapInstallBox').each(function(){
    boxesRecap.install.push($(this).html().split('€')[0]);
    count++;
  });
  count=0

    //valeurs locationBox
  $('.inRecapLocationBox').each(function(){
    boxesRecap.location.push($(this).html().split('€')[0]);
    count++;
  });
  count=0
    //valeurs venteAccessory
  $('.inRecapVenteAccessory').each(function(){
    accessoriesRecap.vente.push($(this).html().split('€')[0]);
    count++;
  });
  count=0
    //valeurs othersDescription & othersCost
  $('.inRecapOthersCost').each(function(){
    othersRecap.cost.push($(this).val());
    othersRecap.description.push($(this).parents('tr').find('.othersDescription input').val());
    count++;
  });

  //génération de la vue
  var content = "";

  //vue bikes
  for (var i = 0; i < bikesNumber ; i++) {
    //gestion vente/leasing
    if (bikesRecap.vente[i] == undefined) {
      bikesRecap.vente[i] = '/';
    }else{
      prixAchatTotal += bikesRecap.vente[i]*1;
    }

    if (bikesRecap.leasing[i] == undefined) {
      bikesRecap.leasing[i] = '/';
    }else{
      prixLocationTotal += bikesRecap.leasing[i]*1;
    }
    content += `
    <tr>
      <td>Vélo `+(i*1+1)+`</td>
      <td>`+bikesRecap.vente[i]+` €</td>
      <td>`+bikesRecap.leasing[i]+` €</td>
    </tr>`;
  }

  //vue boxes
  for (var i = 0; i < boxesNumber; i++) {
    prixAchatTotal += boxesRecap.install[i]*1;
    prixLocationTotal += boxesRecap.location[i]*1;
    content += `
    <tr>
      <td>Box `+(i*1+1)+`</td>
      <td>`+boxesRecap.install[i]+` €</td>
      <td>`+boxesRecap.location[i]+` €</td>
    </tr>`;
  }

  //vue accessories
  for (var i = 0; i < accessoriesNumber; i++) {
    prixAchatTotal += accessoriesRecap.vente[i]*1;
    content += `
    <tr>
      <td>Accessoire `+(i*1+1)+`</td>
      <td>`+accessoriesRecap.vente[i]+` €</td>
      <td>/</td>
    </tr>`;
  }

  //vue others
  for (var i = 0; i < othersNumber; i++) {
    prixAchatTotal += othersRecap.cost[i]*1;
    content += `
    <tr>
      <td>`+othersRecap.description[i]+`</td>
      <td>`+othersRecap.cost[i]+` €</td>
      <td>/</td>
    </tr>`;
  }

  //ajout de la ligne total
  var footer = `
  <tr style="font-weight:bold">
    <td>Total: </td>
    <td>`+prixAchatTotal+` €</td>
    <td>`+prixLocationTotal+` €</td>
  </tr>`;

  $('.summaryTable tbody').append(content);
  $('.summaryTable tfoot').append(footer);
  $('.summaryTable').fadeIn();

});

//validation du formulaire
//
$("#templateForm").validate({
  submitHandler: function(form) {
    $("#companyIdTemplate").val(companyId);
    jQuery(form).ajaxSubmit({
      success: function(response) {
        console.log(response);
        alert('Le pdf a bien été généré !');
      }
    });
  }
});




//gestion du bouton moins et du tableau
function checkMinus(select, valueLocation){
  if ($(select).find(valueLocation).html() == '0') {
    $(select).find('.glyphicon-minus').fadeOut();
    $(select).find('.hideAt0').hide();
  }else{
    $(select).find('.glyphicon-minus').fadeIn();
    $(select).find('.hideAt0').show();
  }
}

//tri de tableau d'objets via une propriété string_calendar
function compare(a, b) {
  // Use toUpperCase() to ignore character casing
  const varA = a.brand.toUpperCase();
  const varB = b.brand.toUpperCase();

  let comparison = 0;
  if (varA > varB) {
    comparison = 1;
  } else if (varA < varB) {
    comparison = -1;
  }
  return comparison;
}

//récupère l'index de l'item dont l'id correspond
function getIndex(table, id){
  for (var i = 0; i < table.length; i++) {
    if(table[i].id == id){
      return i;
    }
  }
}
