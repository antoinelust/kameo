//CONSTANTES
const PRIX_ENTRETIEN = 100;
const PRIX_ASSURANCE = 84;
const box_maintenance_year = 500;

//liste des contacts
function get_company_contacts_list(ID) {
  return  $.ajax({
    url: 'api/companies',
    type: 'get',
    data: {
      'action' : 'getCompanyContacts',
      'ID' : ID
     },
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
    }
  });
}

//liste des vélos
function get_all_bikes() {
  return  $.ajax({
    url: 'apis/Kameo/get_bikes_catalog.php',
    type: 'post',
    data: {},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
    }
  });
}
$( document ).ready(function() {
  $("#offerManagement").on("show.bs.modal", function (event) {
    var action = $(event.relatedTarget).data("action");
    var ID = $(event.relatedTarget).data("id");
    if(action == 'retrieve'){
      $(".offerManagementTitle").text("Consulter une offre");
      $(".offerManagementSendButton").aeddClass("hidden");
    }else if(action == "update"){
      $(".offerManagementTitle").text("Mettre à jour une offre");
      $(".offerManagementSendButton").removeClass("hidden");
      $(".offerManagementSendButton").text("Mettre à jour");
    }else if(action == "add"){

      $(".offerManagementSendButton").removeClass("hidden");
      $(".offerManagementSendButton").text("Ajouter");
    }
    $.ajax({
      url: "api/companies",
      type: "get",
      data: { action: "retrieveOffer", ID: ID},
      success: function (response) {
        $("#offerManagementPDF").attr("data", "");
        if (action == "retrieve") {
          $("#widget-offerManagement-form input").attr("readonly", true);
          $("#widget-offerManagement-form textarea").attr("readonly", true);
          $("#widget-offerManagement-form select").attr("readonly", true);
          $("#offerManagementDelete").addClass("hidden");
        } else {
          $("#widget-offerManagement-form input").attr("readonly", false);
          $("#widget-offerManagement-form textarea").attr("readonly", false);
          $("#widget-offerManagement-form select").attr("readonly", false);
          $("#offerManagementDelete").removeClass("hidden");
          $("#offerManagementDelete").attr("name", ID);
        }

        $("#widget-offerManagement-form input[name=title]").val(response.TITRE);
        $("#widget-offerManagement-form textarea[name=description]").val(response.DESCRIPTION);
        $("#widget-offerManagement-form select[name=type]").val(response.TYPE);
        $("#widget-offerManagement-form select[name=status]").val(response.STATUS);
        $("#widget-offerManagement-form input[name=margin]").val(response.MARGIN);
        $("#widget-offerManagement-form input[name=probability]").val(response.PROBABILITY);
        $("#widget-offerManagement-form input[name=company]").val(response.COMPANY);
        $("#widget-offerManagement-form input[name=action]").val(action);
        $("#widget-offerManagement-form input[name=ID]").val(ID);

        $("#thickBoxProductLists").empty();
        $("#offerManagementDetails").html("");
        if (response.item.length > 0) {
          response.item.forEach(function(item) {
            if (item.ITEM_TYPE == "box") {
              $("#offerManagementDetails").append("<li>1 borne " +item.model +" au prix de " +item.ITEM_LOCATION_PRICE +" €/mois et un coût d'installation de " +item.ITEM_INSTALLATION_PRICE +" €</a></li>");
            } else if (item.ITEM_TYPE=='bike') {
              if(item.ITEM_LOCATION_PRICE == '0'){
                $("#offerManagementDetails").append("<li>Achat de vélo " +item.brand +" " +item.model +" au prix de " +item.ITEM_INSTALLATION_PRICE +" €</a></li>");
              }else{
                $("#offerManagementDetails").append("<li>Location de vélo " +item.brand +" " +item.model +" au prix de " +item.ITEM_LOCATION_PRICE +" €/mois</a></li>");
              }
            } else if (item.ITEM_TYPE='accessory') {
              if(item.ITEM_LOCATION_PRICE == '0'){
                $("#offerManagementDetails").append("<li>Achat d'accessoire " +item.brand +" " +item.model +" au prix de " +item.ITEM_INSTALLATION_PRICE +" €</a></li>");
              }else{
                $("#offerManagementDetails").append("<li>Location d'accessoire   " +item.brand +" " +item.model +" au prix de " +item.ITEM_LOCATION_PRICE +" €/mois</a></li>");
              }
            }
          })
        }

        if ($("#widget-offerManagement-form select[name=type]").val() == "achat") {
          if (response.DATE) {
            $("#widget-offerManagement-form input[name=date]").val(response.DATE.substring(0, 10));
          } else {
            $("#widget-offerManagement-form input[name=date]").val("");
          }
          $("#widget-offerManagement-form input[name=start]").attr("readonly",true);
          $("#widget-offerManagement-form input[name=end]").attr("readonly",true);
          $("#widget-offerManagement-form input[name=start]").val("");
          $("#widget-offerManagement-form input[name=end]").val("");
        } else {
          if (response.DATE) {
            $("#widget-offerManagement-form input[name=date]").val(response.DATE.substring(0, 10));
          } else {
            $("#widget-offerManagement-form input[name=date]").val("");
          }
          if (response.START) {
            $("#widget-offerManagement-form input[name=start]").val(response.START.substring(0, 10));
          } else {
            $("#widget-offerManagement-form input[name=start]").val("");
          }
          if (response.END) {
            $("#widget-offerManagement-form input[name=end]").val(response.END.substring(0, 10));
          } else {
            $("#widget-offerManagement-form input[name=end]").val("");
          }
        }
        if (response.AMOUNT) {
          $("#widget-offerManagement-form input[name=amount]").val(response.AMOUNT);
        }
        if (response.FILE_NAME != null && response.FILE_NAME != "") {
          $(".offerManagementPDF").removeClass("hidden");
          var new_url = "offres/" + response.FILE_NAME + ".pdf";
          $("#offerManagementPDF").attr("data", new_url);
          $('#offerManagementPDF').load(new_url);

        } else {
          $(".offerManagementPDF").addClass("hidden");
          $("#offerManagementPDF").attr("data", "");
        }
      }
    });
  })
})

//Module gérer les clients ==> id d'un client ==> ajouter une offre
function add_offer(company) {
  $("#companyHiddenOffer").val(company);
  $("#widget-offerManagement-form select[name=type]").val("leasing");
  $("#widget-offerManagement-form input[name=action]").val("addManualOffer");
  $("#widget-offerManagement-form input").attr("readonly", false);
  $("#widget-offerManagement-form textarea").attr("readonly", false);
  $("#widget-offerManagement-form select").attr("readonly", false);
  document.getElementById("widget-offerManagement-form").reset();
}




//liste des boxes
function get_all_boxes() {
  return  $.ajax({
    url: 'apis/Kameo/get_boxes_catalog.php',
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
function get_all_accessories_catalog() {
  return  $.ajax({
    url: 'apis/Kameo/get_accessories_catalog.php',
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
var contacts;
var bikes;
var bikesTemp;



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
    $('.contractLeasing').fadeIn();
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
    $('.contractLeasing').fadeOut();
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
    $('.contractLeasing').fadeIn();
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




function update_elements_price(){

    var editable = document.querySelectorAll('td[contentEditable]');

    for (var i=0, len = editable.length; i<len; i++){
        editable[i].onblur = function(){
            if(this.classList.contains("bikeLeasing")){
                var initialPrice=this.getAttribute('data-orig',this.innerHTML).split('€')[0];
                var newPrice=this.innerHTML.split('€')[0];
                if(initialPrice==newPrice){
                    $(this).parents('.bikeRow').find('.bikeLeasing').html(newPrice + '€/mois' + " <span class=\"text-green\">(+)</span>");
                }else{
                    var reduction=Math.round((newPrice*1-initialPrice*1)/(initialPrice*1)*100);
                    $(this).parents('.bikeRow').find('.bikeLeasing').html(newPrice + '€/mois' + " <span class=\"text-green\">(+)</span> <br/><span class=\"text-red\">("+reduction+"%)</span> ");
                }
                var contractLeasing=leasingDuration*newPrice*1;
                $(this).parents('.bikeRow').find('.contractLeasing').html(contractLeasing + '€');
                var buyingPrice=$(this).parents('.bikeRow').find('.bikepAchat').html().split('€')[0];
                var costs=$(this).parents('.bikeRow').find('.bikepCosts').html().split('€')[0];
                var priceHTVA=$(this).parents('.bikeRow').find('.bikepCatalog').html().split('€')[0];
                $(this).parents('.bikeRow').find('.bikeFinalPrice').html("<input type=\"int\" step='0.01' name=\"bikeFinalPrice[]\" value=\""+newPrice+"\"/>");

                var margeVente = (priceHTVA*1 - buyingPrice*1).toFixed(0) + '€';
                var margeLeasing = (leasingDuration*newPrice*1 - buyingPrice - costs).toFixed(0) + '€ (' + ((leasingDuration*newPrice*1 - buyingPrice - costs*1)/(buyingPrice*1 + costs*1)*100).toFixed(0) + '%)';
                if($("#templateForm").hasClass('isLeasing')){
                  marge = '<span class="margeLeasing">'+margeLeasing+'<\/span><span class="margeVente" style="display:none">'+margeVente+'<\/span>';

                } else {
                  marge = '<span class="margeLeasing" style="display:none">'+margeLeasing+'<\/span><span class="margeVente">'+margeVente+'<\/span>';
                }
                $(this).parents('.bikeRow').find('.bikeMarge').html(marge);
            }
            else if(this.classList.contains("boxLocationPrice") || this.classList.contains("boxInstallationPrice")){
                if(this.classList.contains("boxLocationPrice")){
                    var initialPrice=this.getAttribute('data-orig',this.innerHTML).split('€')[0];
                    var newPrice=this.innerHTML.split('€')[0];
                    if(initialPrice==newPrice){
                        $(this).parents('.boxRow').find('.boxLocationPrice').html(newPrice + '€/mois' + " <span class=\"text-green\">(+)</span>");
                    }else{
                        var reduction=Math.round((newPrice*1-initialPrice*1)/(initialPrice*1)*100);
                        $(this).parents('.boxRow').find('.boxLocationPrice').html(newPrice + '€/mois' + " <span class=\"text-green\">(+)</span> <br/><span class=\"text-red\">("+reduction+"%)</span> ");
                    }
                    $(this).parents('.boxRow').find('.boxFinalLocationPrice').html("<input type=\"text\" name=\"boxFinalLocationPrice[]\" value=\""+newPrice+"\"/>");



                }else if(this.classList.contains("boxInstallationPrice")){
                    var initialPrice=this.getAttribute('data-orig',this.innerHTML).split('€')[0];
                    var newPrice=this.innerHTML.split('€')[0];
                    if(initialPrice==newPrice){
                        $(this).parents('.boxRow').find('.boxInstallationPrice').html(newPrice + '€' + " <span class=\"text-green\">(+)</span>");
                    }else{
                        var reduction=Math.round((newPrice*1-initialPrice*1)/(initialPrice*1)*100);
                        $(this).parents('.boxRow').find('.boxInstallationPrice').html(newPrice + '€' + " <span class=\"text-green\">(+)</span> <br/><span class=\"text-red\">("+reduction+"%)</span> ");
                    }
                    $(this).parents('.boxRow').find('.boxFinalInstallationPrice').html("<input type=\"text\" name=\"boxFinalInstallationPrice[]\" value=\""+newPrice+"\"/>");

                }
                var contractLeasing=($(this).parents('.boxRow').find('.boxLocationPrice').html().split('€')[0])*leasingDuration;
                var installationPrice=$(this).parents('.boxRow').find('.boxInstallationPrice').html().split('€')[0];

                $(this).parents('.boxRow').find('.boxContractPrice').html((contractLeasing*1+ installationPrice*1)+ '€');




                var boxProdPrice=$(this).parents('.boxRow').find('.boxProdPrice').html().split('€')[0];
                var maintenance=$(this).parents('.boxRow').find('.boxMaintenance').html().split('€')[0];


                var marge = (installationPrice*1 - boxProdPrice*1 - maintenance + (contractLeasing*1)).toFixed(0) + '€ (' + ((installationPrice*1 - boxProdPrice*1 - maintenance + (contractLeasing*1))/(boxProdPrice*1 + maintenance*1)*100).toFixed(0) + '%)';
                $(this).parents('.boxRow').find('.boxMarge').html(marge);
            }else if(this.classList.contains("aPriceHTVA")){
                var initialPrice=this.getAttribute('data-orig',this.innerHTML).split('€')[0];
                var newPrice=this.innerHTML.split('€')[0];
                $(this).parents('.accessoriesRow').find('.accessoryFinalPrice').html("<input type=\"int\" step='0.01' name=\"accessoryFinalPrice[]\" value=\""+newPrice+"\"/>");
                if(initialPrice==newPrice){
                    $(this).parents('.accessoriesRow').find('.aPriceHTVA').html(newPrice + '€/mois' + " <span class=\"text-green\">(+)</span>");
                }else{
                    var reduction=Math.round((newPrice*1-initialPrice*1)/(initialPrice*1)*100);
                    $(this).parents('.accessoriesRow').find('.aPriceHTVA').html(newPrice + '€/mois' + " <span class=\"text-green\">(+)</span> <br/><span class=\"text-red\">("+reduction+"%)</span> ");
                }
            }


        }
    }
}




$('body').on('click','.getTemplate', function(){

  //création des variables
  var bikes = [];
  get_all_bikes().done(function(response){

    if(response.bike == undefined){
      bikes =[];
      console.log('bikes => table vide');
    }else{
      bikes = response.bike;
    }

    var bikesTemp=bikes;

    //tableau bikes avec tout les champs
    var bikeModels = "<option hidden disabled selected value></option>";

    //tri du tableau par marques
    bikes.sort(compare);

    //gestion du moins au lancement de la page
    checkMinus('.templateBike','.bikesNumber');

    //generation des Options

    //velo

    for (var i = 0; i < bikes.length; i++){
      var elec = "";
      if(bikes[i].electric == 'Y'){
        elec = ' - Elec';
      }
      bikeModels += '<option value="' + bikes[i].id + '">' + bikes[i].brand + ' - ' + bikes[i].model + ' - ' + bikes[i].frameType + elec + '</option>';
    }

    //a chaque modification du nombre de vélo
    //ajout
    $('.templateBike .glyphicon-plus').off();
    $('.templateBike .glyphicon-plus').click(function(){
      bikesNumber = $("#template").find('.bikesNumber').html()*1+1;
      $('#template').find('.bikesNumber').html(bikesNumber);
      $('#bikesNumberTemplate').val(bikesNumber);

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
      <td class="brand"><select><option value="Ahooga">Ahooga</option><option value="Benno">Benno</option><option value="Bzen">BZEN</option><option value="Conway">Conway</option><option value="Douze Cycle">Douze cycle</option><option value="HNF Nicolai">HNF Nicolai</option><option value="Kayza">Kayza</option><option value="Moustache Bikes">Moustache</option><option value="Victoria">Victoria</option></select></td>
      <td class="bikeBrandModel"><select name="bikeBrandModel[]" class="select`+bikesNumber+` form-control required"></select></td>
      <td class="bikeSize"></td>
      <td class="bikeNumber"></td>
      <td class="bikepAchat"></td>
      <td class="bikepCosts"></td>
      <td class="bikepCatalog"></td>
      <td class="bikepVenteHTVA TD_bikepVenteHTVA `+inRecapVenteBike+`"`+hideBikepVenteHTVA+`></td>
      <td contenteditable='true' class="bikeLeasing TD_bikeLeasing `+inRecapLeasingBike+`"`+hideBikeLeasing+`></td>
      <td class="contractLeasing"></td>
      <td class="bikeMarge"></td>
      <td class="bikeInitialPrice hidden"></td>
      <td class="bikeFinalPrice hidden"></td>
      <td class="bikeFinalPriceAchat hidden"></td>
      </tr>`);

      $('#template').find('.bikesNumberTable'+(bikesNumber)+'>.bikeSize').html("<select name='bikeSize[]'><option value='XS'>XS</option><option value='S'>S</option><option value='M'>M</option><option value='L'>L</option><option value'XL'>XL</option><option value='unique'>Unique</option></select>");
      $('#template').find('.bikesNumberTable'+(bikesNumber)+'>.brand select').val("");
      $('#template').find('.bikesNumberTable'+(bikesNumber)+'>.bikeSize select').val("");
      $('#template').find('.bikesNumberTable'+(bikesNumber)+'>.bikeNumber').html("<input type='number' name='bikeNumber[]' value='1' class='form-control required'>");


      $('#template .bikeNumberTable .brand select').change(function(){
    		$modelSelect=$(this).closest('tr').find('.bikeBrandModel select');
    		$.ajax({
    			url: 'api/portfolioBikes',
    			type: 'get',
    			data: { action: "listModelsFromBrand", 'brand': $(this).val()},
    			success: function(response){
    				$modelSelect.find('option')
    				.remove()
    				.end();
    				response.forEach(function(bike){
    					$modelSelect.append('<option value="'+bike.ID+'" data-buyingprice="'+bike.BUYING_PRICE+'" data-retailprice="'+bike.PRICE_HTVA+'">'+bike.MODEL+' - '+bike.FRAME_TYPE+' - '+bike.SEASON+'</option>');
    				})
    				$modelSelect.val('');
    			}
    		})
    	});

      //gestion du select du velo
      $('.bikeBrandModel select').off();
      $('.bikeBrandModel select').on('change',function(){

        var pAchat = $(this).children("option:selected").data('buyingprice');
        var pVenteHTVA = $(this).children("option:selected").data('retailprice');
        var margeVente = $(this).children("option:selected").data('retailprice').toFixed(2) - $(this).children("option:selected").data('buyingprice').toFixed(2) + '€';
        var $row= $(this).closest('tr');
        get_leasing_price($(this).children("option:selected").data('retailprice')).done(function(response){
          var nbreEntretiens = $('.numberMaintenance').val();
          var pCosts = nbreEntretiens*PRIX_ENTRETIEN;
          var assurance = 0;
          if ($('.assuranceCheck ').prop('checked')){
            assurance = PRIX_ASSURANCE*leasingDuration/12;
          }
          pCosts+=assurance;


          //recuperation du prix leasing
          var leasingPrice = response.leasingPrice;
          var margeLeasing = (leasingDuration*leasingPrice*1 - pAchat - pCosts).toFixed(2) + '€ (' + ((leasingDuration*leasingPrice*1 - pAchat - pCosts)/(pAchat*1 + parseInt(pCosts))*100).toFixed(2) + '%)';
          var contractLeasing=leasingDuration*leasingPrice*1;

          //gestion de prix null
          if (pAchat == null) {
            pAchat = 'non renseigné';
            pVenteHTVA = 'non renseigné';
            margeVente = 'non calculable';
            margeLeasing = 'non calculable';
          }
          //gestion de l'affichage de la bonne marge et du tableau recap
          if($("#templateForm").hasClass('isLeasing')){
            marge = '<span class="margeLeasing">'+margeLeasing+'<\/span><span class="margeVente" style="display:none">'+margeVente+'<\/span>';
          } else {
            marge = '<span class="margeLeasing" style="display:none">'+margeLeasing+'<\/span><span class="margeVente">'+margeVente+'<\/span>';
          }
          $row.find('.bikepAchat').html(pAchat + " € <span class=\"text-red\">(-)</span>");
          $row.find('.bikepCosts').html(pCosts+"€ <span class=\"text-red\">(-)</span>");
          $row.find('.bikepCatalog').html(pVenteHTVA);
          $row.find('.bikepVenteHTVA').html(pVenteHTVA);
          $row.find('.bikeFinalPriceAchat').html("<input type=\"number\" name=\"bikeFinalPriceAchat[]\" value=\""+pVenteHTVA+"\"/>");
          $row.find('.bikeMarge').html(marge);
          $row.find('.bikeLeasing').html(leasingPrice + '€/mois' + " <span class=\"text-green\">(+)</span>");
          $row.find('.bikeLeasing').attr('data-orig',leasingPrice);
          $row.find('.bikeInitialPrice').html("<input type=\"number\" step='0.01' name=\"bikeInitialPrice[]\" value=\""+leasingPrice+"\"/>");
          $row.find('.bikeFinalPrice').html("<input type=\"text\" name=\"bikeFinalPrice[]\" value=\""+leasingPrice+"\"/>");
          $row.find('.contractLeasing').html(contractLeasing + '€');
        });
          update_elements_price();

      });
      checkMinus('.templateBike','.bikesNumber');
    });

    //retrait
    $('.templateBike .glyphicon-minus')[0].addEventListener("click",function(){
      bikesNumber = $("#template").find('.bikesNumber').html();
      if(bikesNumber > 0){
        $('#template').find('.bikesNumber').html(bikesNumber*1 - 1);
        $('#bikesNumberTemplate').val(bikesNumber*1 - 1);
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
    if(boxes == undefined){
      boxes =[];
      console.log('boxes => table vide');
    }

    //gestion bouton moins
    checkMinus('.templateBoxes','.boxesNumber');

    //ajout
    $('.templateBoxes .glyphicon-plus').off();
    $('.templateBoxes .glyphicon-plus').click(function(){
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
                <td class="boxMaintenance"></td>
                <td contenteditable='true'  class="boxInstallationPrice"></td>
                <td class="boxFinalInstallationPrice hidden"></td>
                <td contenteditable='true'  class="boxLocationPrice"></td>
                <td class="boxFinalLocationPrice hidden"></td>
                <td class="boxContractPrice"></td>
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
        var productionPrice = (boxes[boxId].productionPrice*1) + '€ ';
        var installationPrice = boxes[boxId].installationPrice + '€';
        var locationPrice = boxes[boxId].locationPrice + '€/mois';
        var boxMaintenance = leasingDuration*box_maintenance_year/12;

        var marge = (boxes[boxId].installationPrice - boxes[boxId].productionPrice*1 - boxMaintenance + (boxes[boxId].locationPrice*leasingDuration)).toFixed(0) + '€ (' + ((boxes[boxId].installationPrice - boxes[boxId].productionPrice*1 - boxMaintenance + (boxes[boxId].locationPrice*leasingDuration))/(boxes[boxId].productionPrice*1 + boxMaintenance)*100).toFixed(0) + '%)';


          $(that).parents('.boxRow').find('.boxProdPrice').html(productionPrice + " <span class=\"text-red\">(-)</span>");
          $(that).parents('.boxRow').find('.boxMaintenance').html(boxMaintenance+" €" + " <span class=\"text-red\">(-)</span>");
          $(that).parents('.boxRow').find('.boxInstallationPrice').html(installationPrice + " <span class=\"text-green\">(+)</span>").addClass('inRecapInstallBox');
          $(that).parents('.boxRow').find('.boxInstallationPrice').attr('data-orig',boxes[boxId].installationPrice);
          $(that).parents('.boxRow').find('.boxFinalInstallationPrice').html("<input type=\"text\" name=\"boxFinalInstallationPrice[]\" value=\""+boxes[boxId].installationPrice+"\"/>");
          $(that).parents('.boxRow').find('.boxMarge').html(marge);
          $(that).parents('.boxRow').find('.boxLocationPrice').html(locationPrice + " <span class=\"text-green\">(+)</span>").addClass('inRecapLocationBox');
          $(that).parents('.boxRow').find('.boxLocationPrice').attr('data-orig',boxes[boxId].locationPrice);
          $(that).parents('.boxRow').find('.boxFinalLocationPrice').html("<input type=\"text\" name=\"boxFinalLocationPrice[]\" value=\""+boxes[boxId].locationPrice+"\"/>");

        $(that).parents('.boxRow').find('.boxContractPrice').html((boxes[boxId].locationPrice*leasingDuration*1+boxes[boxId].installationPrice*1) + "€").addClass('inRecapLocationBox');

      });
      update_elements_price();

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
  get_all_accessories_catalog().done(function(response){
    //gestion du moins au lancement de la page
    checkMinus('.templateAccessories','.accessoriesNumber');
    //variables
    var accessories = response.accessories;
    if(accessories == undefined){
      accessories =[];
      console.log('accessories => table vide');
    }
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

    $('.templateAccessories .glyphicon-plus').off();
    $('.templateAccessories .glyphicon-plus').click(function(){
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
      <td class="aLabel"></td>
      <td class="aCategory"></td>
      <td class="aAccessory">
        <select name='accessoryAccessory[]' class='form-control required'></select>
      </td>
      <td class="accessoryNumber"></td>
      <td class="aFinance"></td>
      <td class="aBuyingPrice"></td>
      <td contenteditable="true" class="aPriceHTVA"></td>
      <td class="accessoryFinalPrice hidden"></td>
      </tr>`);
      //label selon la langue
      $('#template').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aLabel')
      .append('<label>Accessoire '+ accessoriesNumber +'</label>');

      //select catégorie
      $('#template').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aCategory')
      .append(`<select name="accessoryCategory[]" id="selectCategory`+accessoriesNumber+`" class="selectCategory form-control required">`+
      categoriesOption+`
      </select>`);
      //select Accessoire
      $('#template').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.accessoryNumber')
      .append('<input name="accessoryNumber[]" class="form-control required" type="number" min="0">');

      $('#template').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aFinance')
      .append('<select name="accessoryFinance[]" id="selectFinance'+
      accessoriesNumber+
      '"class="form-control required aFinance"><option value="achat">Achat</option><option value="leasing">Leasing</option></select>');
      $('#selectFinance'+accessoriesNumber).val("");

      $('#template').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.accessoryFinalPrice')
      .append('<input type="text" name="accessoryFinalPrice[]" class="form-control required">');
      $('#template').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.accessoryFinalprice').val("");


      checkMinus('.templateAccessories','.accessoriesNumber');

      //on change de la catégorie
      $('.templateAccessories').find('.selectCategory').off();
      $('.templateAccessories').find('.selectCategory').on("change",function(){
        var categoryId =$(this).val();
        $(this).closest('.accessoriesRow').find('.aFinance select').val("");

        var accessoriesOption = "";
        //ne garde que les accessoires de cette catégorie
        accessories.forEach((accessory) => {
          if (categoryId == accessory.categoryId) {
            accessoriesOption += '<option value="'+accessory.id+'" data-buyingprice="'+accessory.buyingPrice+'" data-sellingPrice="'+accessory.priceHTVA+'" data-leasingprice="'+accessory.leasingPrice+'">'+accessory.model+'</option>';
          }
        });
        //place les accessoires dans le select
        $(this).closest('.accessoriesRow').find('.aAccessory select').html(accessoriesOption);
        $(this).closest('.accessoriesRow').find('.aAccessory select').val("");

        //retire l'affichage d'éventuels prix
        $(this).closest('.accessoriesRow').find('.aBuyingPrice').html('');
        $(this).closest('.accessoriesRow').find('.aPriceHTVA').html('');
        $(this).closest('.accessoriesRow').find('.accessoryFinalPrice input').val('');
      });
      $('.selectAccessory').off();
      $('.selectAccessory').on("change",function(){
        $(this).closest('.accessoriesRow').find('.aFinance select').val("");
        $(this).closest('.accessoriesRow').find('.aBuyingPrice').html('');
        $(this).closest('.accessoriesRow').find('.aPriceHTVA').html('');
        $(this).closest('.accessoriesRow').find('.accessoryFinalPrice input').val('');
      });
      $('.aFinance select').off();
      $('.aFinance select').on("change",function(){
        //récupère le bon index même si le tableau est désordonné
        $(this).closest('.accessoriesRow').find('.aBuyingPrice').html($(this).closest('.accessoriesRow').find('.aAccessory select').children("option:selected").data("buyingprice") + ' €');
        if($(this).val()=='achat'){
          $(this).closest('.accessoriesRow').find('.aPriceHTVA').html($(this).closest('.accessoriesRow').find('.aAccessory select').children("option:selected").data("sellingprice") +' €');
          $(this).closest('.accessoriesRow').find('.aPriceHTVA').attr('data-orig', $(this).closest('.accessoriesRow').find('.aAccessory select').children("option:selected").data("sellingprice") +' €');
          $(this).closest('.accessoriesRow').find('.accessoryFinalPrice input').val($(this).closest('.accessoriesRow').find('.aAccessory select').children("option:selected").data("sellingprice"));
        }else{
          $(this).closest('.accessoriesRow').find('.aPriceHTVA').html(Math.round(parseFloat($(this).closest('.accessoriesRow').find('.aAccessory select').children("option:selected").data("leasingprice")*100))/100 + ' €/mois');
          $(this).closest('.accessoriesRow').find('.aPriceHTVA').attr('data-orig', ($(this).closest('.accessoriesRow').find('.aAccessory select').children("option:selected").data("leasingprice") + ' €/mois') + ' €/mois');
          $(this).closest('.accessoriesRow').find('.accessoryFinalPrice input').val($(this).closest('.accessoriesRow').find('.aAccessory select').children("option:selected").data("leasingprice"));
        }
      });
      update_elements_price();
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






  get_company_contacts_list($('#companyIdHidden').val()).done(function(response){
    if(response.length==0){
      $.notify({
        message: 'Veuillez d\'abord définir une personne de contact'
      }, {
        type: 'danger'
      });
    }else{
      var content = `
        <select name="contactSelect" id="contactSelect" class="form-control required valid">
      `;
      for (var i = 0; i < response.length; i++) {
        var selected ='';
        if (i == 0) {
          selected = 'selected';
        }
        content += `<option `+selected+` value="`+response[i].contactId+`">` + response[i].firstNameContact + ` `+ response[i].lastNameContact + `</option>`;
      }
      content += "</select>";
      $('.companyContactDiv').html(content);
      $('#template').modal('toggle');
    }
  });
})

//Kameo contactsTable
try{
  $('#templateForm select[name=offer_template_kameo_contact]')
    .find('option')
    .remove()
    .end();

  $.ajax({
    url: 'apis/Kameo/get_kameobikes_members.php',
    type: 'get',
    success: function(response){
      if (response.response == 'error')
        console.log(response.message);
      else if (response.response == 'success') {
        for (var i = 0; i < response.membersNumber; i++)
        $('#templateForm select[name=offer_template_kameo_contact]')
          .append("<option value=" + response.member[i].email + ">" + response.member[i].firstName + " " + response.member[i].name + "<br>");

        $('#templateForm select[name=offer_template_kameo_contact]').val(email);
      }
    }
  });
}catch(error){
  console.log(error);
}

//Autres

$(document).ready(function(){

  //affichage de la liste des personnes de contact

  checkMinus('.templateOthers','.othersNumber');
  //ajout
  $('.templateOthers .glyphicon-plus').off();
  $('.templateOthers .glyphicon-plus').click(function(){
    //gestion boxNumber
    othersNumber = $("#template").find('.othersNumber').html()*1+1;
    $('#template').find('.othersNumber').html(othersNumber);
    $('#othersNumber').val(othersNumber);

    //creation du tr contenant
    $('#template').find('.templateOthers tbody')
                  .append(`<tr class="othersNumberTable`+(othersNumber)+` otherRow form-group">
                            <td class="othersLabel"></td>
                            <td class="othersDescription">
                              <input type="textArea" class="form-control required" name="othersDescription[]" placeholder="Description"/>
                            </td>
                            <td class="othersBuyingCost">
                              <input type="number" class="form-control currency required inRecapOthersBuyingCost" name="othersBuyingCost`+othersNumber+`" min="0" />
                            </td>
                            <td class="othersSellingCost">
                              <input type="number" class="form-control currency required inRecapOthersSellingCost" name="othersSellingPrice[]" min="0" />
                            </td>
                            <td class="othersSellingCostFinal">
                              <input type="number" class="form-control currency required inRecapOthersSellingCostFinal" name="othersSellingPriceFinal[]" min="0" />
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
  var prixLocationTotalMois = 0;
  var coutsTotaux = 0;

  var tabMarge = 0;
  var tabMargePourcent = 0;

  var bikesRecap = {
    'leasing' : [],
    'vente' : [],
    'pAchat' : []
  };

    //boxes
  var boxesRecap ={
    'install' : [],
    'location' : [],
    'productionPrice' : [],
    'maintenancePrice' : []
  };

    //accessories
  var accessoriesRecap = {
    'vente' : [],
    'pAchat': []
  };
    //Autres
  var othersRecap = {
    'description' : [],
    'vente' : [],
    'venteFinal' : [],
    'pAchat' : []
  };

    //compteur
  var count = 0;
  var issetPAchat = false;
  //génération des valeurs pour la partie bikes
    //valeurs venteBike
  $('.inRecapVenteBike').each(function(){
    bikesRecap.vente.push($(this).html().split('€')[0]);
    bikesRecap.pAchat.push($(this).parents('tr').find('.bikepAchat').html().split('€')[0]);
    issetPAchat = true;
    count++;
  });
  count=0;
    //valeurs leasingBike
  $('.inRecapLeasingBike').each(function(){
    bikesRecap.leasing.push($(this).html().split('€')[0]);
    if (!issetPAchat) {
      bikesRecap.pAchat.push($(this).parents('tr').find('.bikepAchat').html().split('€')[0]);
    }
    count++;
  });
  count=0
    //valeurs installBox
  $('.inRecapInstallBox').each(function(){
    boxesRecap.install.push($(this).html().split('€')[0]);
    boxesRecap.productionPrice.push($(this).parents('tr').find('.boxProdPrice').html().split('€')[0]);
    boxesRecap.maintenancePrice.push($(this).parents('tr').find('.boxMaintenance').html().split('€')[0]);
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
    accessoriesRecap.pAchat.push($(this).parents('tr').find('.aBuyingPrice').html().split('€')[0]);
    count++;
  });
  count=0
    //valeurs othersDescription & othersCost
  $('.inRecapOthersSellingCost').each(function(){
    othersRecap.vente.push($(this).val());
    othersRecap.description.push($(this).parents('tr').find('.othersDescription input').val());
    othersRecap.pAchat.push($(this).parents('tr').find('.othersBuyingCost input').val());
    othersRecap.venteFinal.push($(this).parents('tr').find('.othersSellingCostFinal input').val());
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
      prixLocationTotalMois += bikesRecap.leasing[i]*1;
    }
    if (bikesRecap.pAchat[i] == undefined) {
      bikesRecap.pAchat[i] = '/';
    }else{
      if (bikesRecap.pAchat[i] != 'non renseigné') {
        coutsTotaux += bikesRecap.pAchat[i]*1;
      }

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
    prixLocationTotalMois += boxesRecap.location[i]*1;
    coutsTotaux += boxesRecap.productionPrice[i]*1 + boxesRecap.maintenancePrice[i]*1;
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
    coutsTotaux += accessoriesRecap.pAchat[i]*1;
    content += `
    <tr>
      <td>Accessoire `+(i*1+1)+`</td>
      <td>`+accessoriesRecap.vente[i]+` €</td>
      <td>/</td>
    </tr>`;
  }

  //vue others
  for (var i = 0; i < othersNumber; i++) {
    prixAchatTotal += othersRecap.venteFinal[i]*1;
    coutsTotaux += othersRecap.pAchat[i]*1;
    content += `
    <tr>
      <td>`+othersRecap.description[i]+`</td>
      <td>`+othersRecap.venteFinal[i]+` €</td>
      <td>/</td>
    </tr>`;
  }
  //ajout de la ligne total
  content += `
  <tr style="font-weight:700">
    <td>Sous total: </td>
    <td>`+prixAchatTotal+` €</td>
    <td>`+prixLocationTotalMois+` €/mois</td>
  </tr>
  <tr style="font-weight:700">
    <td></td>
    <td></td>
    <td></td>
  </tr>
  `;

  //calcul de la marge
  var coutsLocatifs = 0;
  var prixLocationTotalSansAjouts = 0;
  var prixTotal = 0;
  //cas du calcul avec vélos en leasing
  if (($('#buyOrLeasingSelect').val() == "leasing" || $('#buyOrLeasingSelect').val() == "both") &&  prixLocationTotalMois != 0) {
    //variables utiles
    var leasingDuration = $('.leasingDuration').val();
    var nbreEntretiens = $('.numberMaintenance').val();
    var assurance = 0;
    if ($('.assuranceCheck ').prop('checked')) {
      assurance = bikesNumber*PRIX_ASSURANCE;
    }
    //calcul pour les entretiens des vélos (par an)

    nbreEntretiens=nbreEntretiens/leasingDuration*12;

    coutsLocatifs += (nbreEntretiens*bikesNumber)*PRIX_ENTRETIEN;
    //calcul pour l'assurance des vélos(par an)
    coutsLocatifs += assurance*1;

    //modification de la valeur pour qu'elle colle au nombre de mois de leasing
    coutsLocatifs = ((coutsLocatifs/12)*leasingDuration).toFixed(2);

    //modification de la valeur du cout de location total sans entretiens et assurance
    prixLocationTotal = prixLocationTotalMois*leasingDuration;
    //Ajout des charges dans les frais de l'entreprise
    coutsTotaux += coutsLocatifs*1;
    //calcul du prix total cumulé
    prixTotal = (prixAchatTotal*1 + prixLocationTotal*1)*1;

    tabMarge = (prixTotal - coutsTotaux);
    tabMarge = tabMarge.toFixed(2);

    tabMargePourcent = (tabMarge/coutsTotaux)*100;
    tabMargePourcent = tabMargePourcent.toFixed(2);

  }else{
    //cas ou on est en achat de vélo
    prixTotal = prixAchatTotal*1 + prixLocationTotal*1;

    tabMarge = (prixTotal - coutsTotaux);
    tabMarge = tabMarge.toFixed(2);

    tabMargePourcent = (tabMarge/coutsTotaux)*100;
    tabMargePourcent = tabMargePourcent.toFixed(2);

  }
  var footer = "";
  if ($('#buyOrLeasingSelect').val() == "leasing" || $('#buyOrLeasingSelect').val() == "both") {
    footer += `
    <tr style="font-weight:bold">
      <td>Récapitulatif leasing</td>
      <td>Frais (total)</td>
      <td>Location (Durée totale)</td>
    </tr>
    <tr>
      <td></td>
      <td>- `+coutsTotaux+` €</td>
      <td>`+prixLocationTotal+` €</td>
    </tr>`;
  }
  footer += `
<tr style="font-weight:bold">
    <td>PRIX TOTAL</td>
    <td>MARGE (€)</td>
    <td>MARGE (%)</td>
  </tr>
  <tr style="font-weight:bold; font-size:18px; color:#3CB295;">
    <td>`+prixTotal+` €</td>
    <td>`+tabMarge+` €</td>
    <td>`+tabMargePourcent+` %</td>
  </tr>`;


  $('.summaryTable tbody').append(content);
  $('.summaryTable tfoot').append(footer);
  $('.summaryTable').fadeIn();

});

//validation du formulaire
//
$("#templateForm").validate({
  ignore: '',
  submitHandler: function(form) {
    var buttonContent = `
    <i class="fa fa-circle-o-notch fa-spin"></i>Chargement...
    `;
    $('.generatePDF').html(buttonContent);
    jQuery(form).ajaxSubmit({
      success: function(response) {
        if(response.response == 'true'){
          $('.generatePDF').html('Générer PDF');
          $.notify({
            message: 'Le pdf a bien été généré !'
          }, {
            type: 'success'
          });

          document.getElementById('templateForm').reset();
          $('#template').modal('toggle');
          get_company_details($('#widget-companyDetails-form input[name=ID]').val(), email);

        }else{
          $('.generatePDF').html('Générer PDF');
          $.notify({
            message: response.message
          }, {
            type: 'danger'
          });
        }

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
