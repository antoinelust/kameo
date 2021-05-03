window.addEventListener("DOMContentLoaded", function(event) {

  $('#widget-addBill-form select[name=billType]').change(function(){
    if($('#widget-addBill-form select[name=billType]').val()=="manual"){
      $('.manualBill').fadeIn("slow");
      $('.generateBillDetails').fadeOut("slow");
      $('#widget-addBill-form input[name=widget-addBill-form-amountHTVA]').addClass("required");
      $('#widget-addBill-form input[name=widget-addBill-form-amountTVAC]').addClass("required");
      $('#widget-addBill-form input[name=widget-addBill-form-file]').addClass("required");

    }else{
      $('.manualBill').fadeOut("slow");
      $('.generateBillDetails').fadeIn("slow");
      $('#widget-addBill-form input[name=widget-addBill-form-amountHTVA]').removeClass("required");
      $('#widget-addBill-form input[name=widget-addBill-form-amountTVAC]').removeClass("required");
      $('#widget-addBill-form input[name=widget-addBill-form-file]').removeClass("required");
    }
  });
  $('.billsManagerClick').off();
  $('.billsManagerClick').click(function(){
    get_bills_listing();
  });
});

$( ".fleetmanager" ).click(function() {
  $.ajax({
    url: 'apis/Kameo/initialize_counters.php',
    type: 'post',
    data: { "email": email, "type": "bills"},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){
        document.getElementById('counterBills').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.billsNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.billsNumber+"</span>";
      }
    }
  })
})


$('#widget-addBill-form select[name=company]').off();

$('.widget-addBill-form-company').change(function(){
  var e = document.getElementsByClassName('widget-addBill-form-company')[0];
  var valueSelect = e.options[e.selectedIndex].value;
  $('#widget-addBill-form input[name=communication]').attr('readonly', true);
  if(valueSelect=='other'){
    $('.widget-addBill-form-companyOther').removeClass("hidden");
    $('#widget-addBill-form input[name=communication]').val($('#widget-addBill-form input[name=communicationHidden]').val());
    $('input[name=beneficiaryCompany]').attr('readonly', true);
    $('input[name=beneficiaryCompany]').val('KAMEO');
  }else if(valueSelect=='KAMEO'){
    $('input[name=beneficiaryCompany]').attr('readonly', false);
    $('input[name=beneficiaryCompany]').val('');
    $('#widget-addBill-form input[name=communication]').attr('readonly', false);
    $('#widget-addBill-form input[name=communication]').val('');
  }
  else{
    $('.widget-addBill-form-companyOther').addClass("hidden");
    $('#widget-addBill-form input[name=communication]').val($('#widget-addBill-form input[name=communicationHidden]').val());
    $('input[name=beneficiaryCompany]').attr('readonly', true);
    $('input[name=beneficiaryCompany]').val('KAMEO');
    $('.IDAddBill').removeClass("hidden");
    $('.IDAddBillOut').removeClass("hidden");
  }
});
$('#widget-addBill-form input[name=beneficiaryCompany]').change(function(){
  if($('#widget-addBill-form input[name=beneficiaryCompany]').val()=='KAMEO'){
    $('.IDAddBill').removeClass("hidden");
    $('.IDAddBillOut').removeClass("hidden");
  }else{
    $('.IDAddBill').addClass("hidden");
    $('.IDAddBillOut').addClass("hidden");
  }
});
$('#widget-addBill-form select[name=type]').change(function(){
  if($('#widget-addBill-form select[name=type]').val()=="autre"){
    $('#widget-addBill-form input[name=typeOther]').removeClass("hidden");
    $('#widget-addBill-form input[name=typeOther]').addClass("required");
    $('#widget-addBill-form label[for=typeOther]').removeClass("hidden");
  }else{
    $('#widget-addBill-form input[name=typeOther]').addClass("hidden");
    $('#widget-addBill-form input[name=typeOther]').removeClass("required");
    $('#widget-addBill-form label[for=typeOther]').addClass("hidden");
  }
});
$('input[name=widget-addBill-form-amountHTVA]').change(function(){
  if($('input[name=widget-addBill-form-VAT]').is(':checked')){
    $('input[name=widget-addBill-form-amountTVAC]').val((1.21*$('input[name=widget-addBill-form-amountHTVA]').val()).toFixed(2));
  }else{
    $('input[name=widget-addBill-form-amountTVAC]').val($('input[name=widget-addBill-form-amountHTVA]').val());
  }
});
$('.widget-addBill-form-VAT').change(function(){
  if($('input[name=widget-addBill-form-VAT]').is(':checked')){
    $('input[name=widget-addBill-form-amountTVAC]').val((1.21*$('input[name=widget-addBill-form-amountHTVA]').val()).toFixed(2));
  }else{
    $('input[name=widget-addBill-form-amountTVAC]').val($('input[name=widget-addBill-form-amountHTVA]').val());
  }
});



function construct_form_for_billing_status_update(ID){
  $.ajax({
    url: 'apis/Kameo/get_billing_details.php',
    type: 'post',
    data: { "ID": ID},
    success: function(response){
      if (response.response == 'error') {
        console.log(response.message);
      } else{
       $('input[name=widget-updateBillingStatus-form-billingReference]').val(ID);
       $('input[name=widget-updateBillingStatus-form-billingCompany]').val(response.bill.company);
       $('input[name=widget-updateBillingStatus-form-beneficiaryBillingCompany]').val(response.bill.beneficiaryCompany);
       $('input[name=widget-updateBillingStatus-form-type]').val(response.bill.type);
       $('input[name=widget-updateBillingStatus-form-communication]').val(response.bill.communication);
       $('input[name=widget-updateBillingStatus-form-date]').val(response.bill.date.substring(0,10));
       $('input[name=widget-updateBillingStatus-form-amountHTVA]').val(response.bill.amountHTVA);
       $('input[name=widget-updateBillingStatus-form-amountTVAC]').val(response.bill.amountTVAC);
       $('input[name=widget-updateBillingStatus-form-VAT]').prop('checked', Boolean(response.bill.amountHTVA != response.bill.amountTVAC));
       $('input[name=widget-updateBillingStatus-form-sent]').prop( 'checked', Boolean(response.bill.sent=="1"));
       $('input[name=widget-updateBillingStatus-form-paid]').prop( 'checked', Boolean(response.bill.paid=="1"));
       $('#widget-updateBillingStatus-form input[name=accounting]').prop( 'checked', Boolean(response.bill.communicationSentAccounting=="1"));
       $('input[name=widget-updateBillingStatus-form-currentFile]').val(response.bill.file);
       $("#widget-deleteBillingStatus-form input[name=reference]").val(ID);
       if(response.bill.sentDate)
         $('input[name=widget-updateBillingStatus-form-sendingDate]').val(response.bill.sentDate.substring(0,10));
       else
         $('input[name=widget-updateBillingStatus-form-sendingDate]').val('');
       if(response.bill.paidDate)
         $('input[name=widget-updateBillingStatus-form-paymentDate]').val(response.bill.paidDate.substring(0,10));
       else
         $('input[name=widget-updateBillingStatus-form-paymentDate]').val('');
       if(response.bill.paidLimitDate)
         $('input[name=widget-updateBillingStatus-form-datelimite]').val(response.bill.paidLimitDate.substring(0,10));
       else
         $('input[name=widget-updateBillingStatus-form-datelimite]').val('');
       if(response.bill.file != ''){
         $('.widget-updateBillingStatus-form-currentFile').attr("href", "factures/"+response.bill.file);
         $('.widget-updateBillingStatus-form-currentFile').unbind('click');
       }else{
         $('.widget-updateBillingStatus-form-currentFile').click(function(e) {
          e.preventDefault();
          $.notify({ message: "No file available for that bill" }, { type: 'danger' });
        });
       }
       var dest='<table class=\"table table-condensed\"><thead><tr><th>Objet</th><th>Montant</th><th>Comentaire</th></tr></thead><tbody>';
       for(var i = 0; i<response.billDetailsNumber; i++){
        if(response.bill.billDetails[i].itemType == "bike"){
          dest=dest.concat("<tr><td><ul><li>Type : Vélo </li><li>ID : "+response.bill.billDetails[i].itemID + " </li><li>Identification : " + response.bill.billDetails[i].frameNumber+"</li></ul><td>"+response.bill.billDetails[i].amountHTVA+" € HTVA</td><td>"+response.bill.billDetails[i].comments+"</td></tr>");
        }else if(response.bill.billDetails[i].itemType == "accessory"){
          dest=dest.concat("<tr><td><ul><li>Type : Accessoire </li><li>ID : "+response.bill.billDetails[i].itemID + " </li><li>Modèle : " + response.bill.billDetails[i].model+"</li></ul><td>"+response.bill.billDetails[i].amountHTVA+" € HTVA</td><td>"+response.bill.billDetails[i].comments+"</td></tr>");
        }else if(response.bill.billDetails[i].itemType == "box"){
          dest=dest.concat("<tr><td><ul><li>Type : Borne </li><li>ID : "+response.bill.billDetails[i].itemID + " </li><li>Identification : " + response.bill.billDetails[i].model+"</li></ul><td>"+response.bill.billDetails[i].amountHTVA+" € HTVA</td><td>"+response.bill.billDetails[i].comments+"</td></tr>");
        }else if(response.bill.billDetails[i].itemType == "maintenance"){
          dest=dest.concat("<tr><td><ul><li>Type : Entretien </li><li>ID : "+response.bill.billDetails[i].itemID + " </li><li>Description : " + response.bill.billDetails[i].description+"</li></ul><td>"+response.bill.billDetails[i].amountHTVA+" € HTVA</td><td>"+response.bill.billDetails[i].amountTVAC+" € TVAC</td></tr>");
        }
      }
      document.getElementById('billingDetails').innerHTML=dest.concat("</tbody><table>");
    }
  }
});
}

function create_bill(){
  $("#widget-addBill-form select[name=company]").val("");
  document.getElementsByClassName('widget-addBill-form-date')[0].value = "";
  document.getElementsByClassName('widget-addBill-form-amountHTVA')[0].value = "";
  document.getElementsByClassName('widget-addBill-form-amountTVAC')[0].value = "";
  document.getElementsByClassName('widget-addBill-form-sendingDate')[0].value = "";
  document.getElementsByClassName('widget-addBill-form-paymentDate')[0].value = "";
  $('.widget-addBill-form-companyOther').addClass("hidden");
  $('.IDAddBill').removeClass('hidden');
  $('.IDAddBillOut').removeClass('hidden');
  $('#widget-addBill-form textarea').val('');

  //Accessoires
  get_all_accessories().done(function(response){
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

    $('.generateBillAccessories .glyphicon-plus').unbind();
    $('.generateBillAccessories .glyphicon-plus').click(function(){
      console.log("test");
      //gestion accessoriesNumber
      accessoriesNumber = $("#addBill").find('.accessoriesNumber').html()*1+1;
      $('#addBill').find('.accessoriesNumber').html(accessoriesNumber);
      $('#accessoriesNumber').val(accessoriesNumber);

      //ajout des options du select pour les catégories
      var categoriesOption = "<option hidden disabled selected value></option>";
      categories.forEach((category) => {
        categoriesOption += '<option value="'+category.id+'">'+category.name+'</option>';
      });

      //ajout d'une ligne au tableau des accessoires
      $('#addBill').find('.otherCostsAccesoiresTable tbody')
      .append(`<tr class="otherCostsAccesoiresTable`+(accessoriesNumber)+` accessoriesRow form-group">
        <td class="aLabel"></td>
        <td class="aCategory"></td>
        <td class="aAccessory"></td>
        <td class="aBuyingPrice"></td>
        <td contenteditable='true' class="aPriceHTVA"></td>
        <td><input type="number" class="accessoryFinalPrice hidden" name="accessoryFinalPrice[]" /></td>
        </tr>`);
      //label selon la langue
      $('#addBill').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aLabel')
      .append('<label>Accessoire '+ accessoriesNumber +'</label>');

      //select catégorie
      $('#addBill').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aCategory')
      .append(`<select name="accessoryCategory`+accessoriesNumber+`" id="selectCategory`+accessoriesNumber+`" class="selectCategory form-control required">`+
        categoriesOption+`
        </select>`);
      //select Accessoire
      $('#addBill').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aAccessory')
      .append('<select name="accessoryID[]" id="selectAccessory'+
        accessoriesNumber+
        '"class="selectAccessory form-control required"></select>');

      checkMinus('.generateBillAccessories','.accessoriesNumber');

      //on change de la catégorie
      $('.generateBillAccessories').find('.selectCategory').on("change",function(){
        var that = '#' + $(this).attr('id');
        var categoryId =$(that).val();
        var accessoriesOption = "<option hidden disabled selected value>Veuillez choisir un accesoire</option>";

        //ne garde que les accessoires de cette catégorie
        accessories.forEach((accessory) => {
          if (categoryId == accessory.categoryId) {
            accessoriesOption += '<option value="'+accessory.id+'">'+accessory.model+'</option>';
          }
        });
        //place les accessoires dans le select
        $(that).parents('tr').find('.selectAccessory').html(accessoriesOption);

        //retire l'affichage d'éventuels prix
        $(that).parents('.accessoriesRow').find('.aBuyingPrice').html('');
        $(that).parents('.accessoriesRow').find('.aPriceHTVA').html('');
      });

      $('.generateBillAccessories').find('.selectAccessory').on("change",function(){
        var that = '#' + $(this).attr('id');
        var accessoryId =$(that).val();

          //récupère le bon index même si le tableau est désordonné
          accessoryId = getIndex(accessories, accessoryId);

          var buyingPrice = accessories[accessoryId].buyingPrice + '€';
          var priceHTVA = accessories[accessoryId].priceHTVA + '€';

          $(that).parents('.accessoriesRow').find('.aBuyingPrice').html(buyingPrice);
          $(that).parents('.accessoriesRow').find('.aPriceHTVA').html(priceHTVA);
          $(that).parents('.accessoriesRow').find('.aPriceHTVA').attr('data-orig',priceHTVA);
          $(that).parents('.accessoriesRow').find('.accessoryFinalPrice').val(accessories[accessoryId].priceHTVA);
        });

      $('#widget-addBill-form .accessoriesRow .aPriceHTVA ').blur(function(){
        var initialPrice=this.getAttribute('data-orig',this.innerHTML).split('€')[0];
        var newPrice=this.innerHTML.split('€')[0];
        if(initialPrice==newPrice){
          $(this).parents('.accessoriesRow').find('.aPriceHTVA').html(newPrice + '€ ' + " <span class=\"text-green\">(+)</span>");
        }else{
          var reduction=Math.round((newPrice*1-initialPrice*1)/(initialPrice*1)*100);
          $(this).parents('.accessoriesRow').find('.aPriceHTVA').html(newPrice + '€ ' + " <span class=\"text-green\">(+)</span> <br/><span class=\"text-red\">("+reduction+"%)</span> ");
        }
        var buyingPrice=$(this).parents('.accessoriesRow').find('.aBuyingPrice').html().split('€')[0];
        var marge = (newPrice*1 - buyingPrice).toFixed(0) + '€ (' + ((newPrice*1 - buyingPrice)/(buyingPrice*1)*100).toFixed(0) + '%)';
        $(this).parents('.accessoriesRow').find('.accessoryMarge').html(marge);
        $(this).parents('.accessoriesRow').find('.accessoryFinalPrice').val(newPrice);
      });

    });

    //retrait
    $('.generateBillAccessories .glyphicon-minus').unbind();
    $('.generateBillAccessories .glyphicon-minus').click(function(){
      accessoriesNumber = $("#addBill").find('.accessoriesNumber').html();
      if(accessoriesNumber > 0){
        $('#addBill').find('.accessoriesNumber').html(accessoriesNumber*1 - 1);
        $('#accessoriesNumber').val(accessoriesNumber*1 - 1);
        $('#addBill').find('.otherCostsAccesoiresTable'+accessoriesNumber).slideUp().remove();
        accessoriesNumber--;
      }
      checkMinus('.generateBillAccessories','.accessoriesNumber');
    });
  });


  $('.generateBillOtherAccessories .glyphicon-plus').unbind();
  $('.generateBillOtherAccessories .glyphicon-plus').click(function(){
    //gestion accessoriesNumber
    otherAccessoriesNumber = $("#addBill").find('.otherAccessoriesNumber').html()*1+1;
    $('#addBill').find('.otherAccessoriesNumber').html(otherAccessoriesNumber);
    $('#otherAccessoriesNumber').val(otherAccessoriesNumber);

    //ajout d'une ligne au tableau des accessoires
    $('#addBill').find('.otherCostsOtherAccesoiresTable tbody')
    .append(`<tr class="otherCostsOtherAccesoiresTable`+(otherAccessoriesNumber)+` otherAccessoriesRow form-group">
      <td class="aLabel"></td>
      <td class="aAccessory"><input type="text" class="otherAccessoryDescription form-control required" name="otherAccessoryDescription[]" /></td>
      <td><input type="number" class="otherAccessoryFinalPrice form-control required" name="otherAccessoryFinalPrice[]" /></td>
      </tr>`);
    //label selon la langue
    $('#addBill').find('.otherCostsOtherAccesoiresTable'+(otherAccessoriesNumber)+'>.aLabel')
    .append('<label>Accessoire '+ otherAccessoriesNumber +'</label>');
    checkMinus('.generateBillOtherAccessories','.otherAccessoriesNumber');
  });

  //retrait
  $('.generateBillOtherAccessories .glyphicon-minus').unbind();
  $('.generateBillOtherAccessories .glyphicon-minus').click(function(){
    otherAccessoriesNumber = $("#addBill").find('.otherAccessoriesNumber').html();
    if(otherAccessoriesNumber > 0){
      $('#addBill').find('.otherAccessoriesNumber').html(otherAccessoriesNumber*1 - 1);
      $('#otherAccessoriesNumber').val(otherAccessoriesNumber*1 - 1);
      $('#addBill').find('.otherCostsOtherAccesoiresTable'+otherAccessoriesNumber).slideUp().remove();
      otherAccessoriesNumber--;
    }
    checkMinus('.generateBillOtherAccessories','.otherAccessoriesNumber');
  });


  $.ajax({
    url: 'api/maintenances',
    type: 'get',
    data: {
      action: 'listCategories'
    },
    success: function(response){
      var categories = [];
      response.forEach(function(service){
        categories.push('<option value="'+service.CATEGORY+'">'+service.CATEGORY+'</option>');
      });
      $('.generateBillManualWorkload .glyphicon-plus').unbind();
      $('.generateBillManualWorkload .glyphicon-plus').click(function(){
        //gestion travail manuel
        manualWorkloadNumber = $("#addBill").find('.manualWorkloadNumber').html()*1+1;
        $('#addBill').find('.manualWorkloadNumber').html(manualWorkloadNumber);
        $('#manualWorkloadNumber').val(manualWorkloadNumber);

        //ajout d'une ligne au tableau de la main d'oeuvre
        $('#addBill').find('.otherCostsManualWorkloadTable tbody')
        .append(`<tr class="otherCostsManualWorkloadTable`+(manualWorkloadNumber)+` manualWorkloadRow form-group">
          <td class="category"><select name="category[]" class="form-control required" value="">`+categories+`</select></td>
          <td class="service"><select name="service[]" class="form-control required"></select></td>
          <td class="bikeMaintenance"><select name="bikeMaintenance[]" class="form-control required" value=""></select></td>
          <td class="manualWorkloadLength"><input type="number" step='5' class="form-control required" name="manualWorkloadLength[]" value="" /></td>
          <td class="manualWorkloadTotal"><input type="number" step='0.01' class="form-control required" name="manualWorkloadTotal[]" value="" /></td>
          <td class="manualWorkloadTotalTVAC"><input type="number" step='0.01' class="form-control required" name="manualWorkloadTotalTVAC[]" value="" /></td>
          </tr>`);
        $('.otherCostsManualWorkloadTable'+(manualWorkloadNumber)+' .category select').val('');

        //label selon la langue
        checkMinus('.generateBillManualWorkload','.manualWorkloadNumber');
        $('.category select').off();
        $('.category select').change(function(){
          var $select = $(this);
          $select.closest('tr').find('.service select').find('option')
          .remove();
          $.ajax({
            url: 'api/maintenances',
            type: 'get',
            data: {
              action: 'listServices',
              category: $(this).val()
            },
            success: function(response){
              response.forEach(function(service){
                $select.closest('tr').find('.service select').append('<option value="'+service.ID+'" data-minutes="'+service.MINUTES+'" data-htva="'+Math.round(service.PRICE_TVAC/1.06*100)/100+'" data-tvac="'+service.PRICE_TVAC+'">'+service.DESCRIPTION+'</option>');
              });
              $select.closest('tr').find('.service select').val("");
            }
          })

          $.ajax({
            url: 'api/maintenances',
            type: 'get',
            data: {
              action: 'list',
              company : $('#widget-addBill-form select[name=company]').val()
            },
            success: function(response){
              var maintenances = [];
              response.internalMaintenances.forEach(function(maintenance){
                $select.closest('tr').find('.bikeMaintenance select').append('<option value="'+maintenance.ID+'">'+maintenance.ID+' - '+maintenance.DATE.shortDate()+' - '+maintenance.BRAND+' '+maintenance.MODEL+'</option>');
              })
              response.externalMaintenances.forEach(function(maintenance){
                $select.closest('tr').find('.bikeMaintenance select').append('<option value="'+maintenance.ID+'">'+maintenance.ID+' - '+maintenance.DATE.shortDate()+' - '+maintenance.BRAND+' '+maintenance.MODEL+'</option>');
              })
              $select.closest('tr').find('.bikeMaintenance select').val("");
            }
          })
        });

        $('.service select').off()
        $('.service select').change(function(){
          $(this).closest('tr').find('.manualWorkloadLength input').val($(this).find(':selected').data('minutes'));
          $(this).closest('tr').find('.manualWorkloadTotal input').val($(this).find(':selected').data('htva'));
          $(this).closest('tr').find('.manualWorkloadTotalTVAC input').val($(this).find(':selected').data('tvac'));
        });

        $('.manualWorkloadTotal input').off()
        $('.manualWorkloadTotal input').change(function(){
          $(this).closest('tr').find('.manualWorkloadTotalTVAC input').val(Math.round($(this).val()*1.06*100)/100);
        });
        $('.manualWorkloadTotalTVAC input').off()
        $('.manualWorkloadTotalTVAC input').change(function(){
          $(this).closest('tr').find('.manualWorkloadTotal input').val(Math.round($(this).val()/1.06*100)/100);
        });

      });

      //retrait
      $('.generateBillManualWorkload .glyphicon-minus').unbind();
      $('.generateBillManualWorkload .glyphicon-minus').click(function(){
        manualWorkloadNumber = $("#addBill").find('.manualWorkloadNumber').html();
        if(manualWorkloadNumber > 0){
          $('#addBill').find('.manualWorkloadNumber').html(manualWorkloadNumber*1 - 1);
          $('#manualWorkloadNumber').val(manualWorkloadNumber*1 - 1);
          $('#addBill').find('.otherCostsManualWorkloadTable'+manualWorkloadNumber).slideUp().remove();
          otherAccessoriesNumber--;
        }
        checkMinus('.generateBillManualWorkload','.manualWorkloadNumber');
      });
    }
  });



  var dateInOneMonth=new Date();
  dateInOneMonth.setMonth(dateInOneMonth.getMonth()+1);
  var year=dateInOneMonth.getFullYear();
  var month=("0" + (dateInOneMonth.getMonth()+1)).slice(-2)
  var day=("0" + dateInOneMonth.getDate()).slice(-2)
  var dateInOneMonthString=year+"-"+month+"-"+day;

  $('#widget-addBill-form input[name=widget-addBill-form-date]').val(get_date_string());
  $('#widget-addBill-form input[name=widget-addBill-form-datelimite]').val(dateInOneMonthString);
}

$('#widget-addBill-form select[name=company]').change(function(){
  //création des variables

  var bikesNumber=0;
  $('#addBill').find('.bikesNumber').html(bikesNumber);
  $('#addBill').find('#bikesNumberBill').val(bikesNumber);
  $('#addBill').find('.generateBillBike tbody').html("");
  var bikeModels = "<option hidden disabled selected value></option>";
  var bikes = [];



  $.ajax({
    url: 'api/bikes',
    type: 'get',
    data: {
      'action' : 'listPendingDeliveryBikes',
      'company' : $(this).val()
    },
    success: function(response){
        if(response == null){
          $.notify({
            message: "Pas de vélos en attente de livraison pour ce client"
          }, {
            type: 'warning'
          });
          $('.generateBillBike .glyphicon-plus').unbind();
          $('.generateBillBike .glyphicon-plus').click(function(){
            $.notify({
              message: "Pas de vélos en attente de livraison pour ce client"
            }, {
              type: 'warning'
            });
          });
          return false;
        }
        bikes = response;
        //tableau bikes avec tout les champs

        //gestion du moins au lancement de la page
        checkMinus('.generateBillBike','.bikesNumber');
        checkMinus('.generateBillAccessories','.accessoriesNumber');
        checkMinus('.generateBillOtherAccessories','.otherAccessoriesNumber');
        checkMinus('.generateBillManualWorkload','.manualWorkloadNumber');

        //velo

        for (var i = 0; i < bikes.length; i++) {
          bikeModels += '<option value="' + bikes[i].id + '">' + bikes[i].id + ' - ' + bikes[i].brand + ' - ' + bikes[i].model + '</option>';
        }

        //a chaque modification du nombre de vélo
        //ajout
        $('.generateBillBike .glyphicon-plus').unbind();
        $('.generateBillBike .glyphicon-plus').click(function(){
          bikesNumber = $("#addBill").find('.bikesNumber').html()*1+1;
          $('#addBill').find('.bikesNumber').html(bikesNumber);
          $('#addBill').find('#bikesNumberBill').val(bikesNumber);

          //creation du div contenant
          $('#addBill').find('.generateBillBike tbody')
          .append(`<tr class="bikesNumberTable`+(bikesNumber)+` bikeRow form-group">
            <td class="bLabel"></td>
            <td class="bikeID"></td>
            <td class="bikepAchat"></td>
            <td class="bikepCatalog"></td>
            <td contenteditable='true' class="bikepVenteHTVA TD_bikepVenteHTVA"></td>
            <td class="bikeMarge"></td>
            <td class="hidden"><input type="number" name="bikeFinalPrice[]" class="bikeFinalPrice"></td>
            </tr>`);

          //label selon la langue
          $('#addBill').find('.bikesNumberTable'+(bikesNumber)+'>.bLabel')
          .append('<label>Vélo '+ bikesNumber +'</label>');

          $('#addBill').find('.bikesNumberTable'+(bikesNumber)+'>.bikeID')
          .append(`<select name="bikeID[]" class="select`+bikesNumber+` bikeID form-control required">`+
            bikeModels+`</select>`);


          //gestion du select du velo
          $('.generateBillBike .bikeID select').on('change',function(){

            var id =$(this).val();


            //récupère le bon index même si le tableau est désordonné
            id = getIndex(bikes, id);
              //gestion de prix null
              if (bikes[id].buyingPrice == null) {
                pAchat = 'non renseigné';
                pVenteHTVA = 'non renseigné';
                var marge = 'non calculable';
              }else{
                var pAchat = bikes[id].buyingPrice + '€ ';
                var pVenteHTVA = bikes[id].priceHTVA + '€ ';
                var marge = (bikes[id].priceHTVA - bikes[id].buyingPrice).toFixed(0) + '€ (' + ((bikes[id].priceHTVA - bikes[id].buyingPrice)/(bikes[id].buyingPrice)*100).toFixed(0) + '%)';
              }

              $(this).parents('.bikeRow').find('.bikepAchat').html(pAchat + " <span class=\"text-red\">(-)</span>");
              $(this).parents('.bikeRow').find('.bikepCatalog').html(pVenteHTVA);
              $(this).parents('.bikeRow').find('.bikepVenteHTVA').html(pVenteHTVA);
              $(this).parents('.bikeRow').find('.bikepVenteHTVA').attr('data-orig',pVenteHTVA);
              $(this).parents('.bikeRow').find('.bikeMarge').html(marge);
              $(this).parents('.bikeRow').find('.bikeFinalPrice').val(bikes[id].priceHTVA);
            });
          checkMinus('.generateBillBike','.bikesNumber');

          $('#widget-addBill-form .bikeRow .bikepVenteHTVA').blur(function(){
            var initialPrice=this.getAttribute('data-orig',this.innerHTML).split('€')[0];
            var newPrice=this.innerHTML.split('€')[0];
            if(initialPrice==newPrice){
              $(this).parents('.bikeRow').find('.bikepVenteHTVA').html(newPrice + '€ ' + " <span class=\"text-green\">(+)</span>");
            }else{
              var reduction=Math.round((newPrice*1-initialPrice*1)/(initialPrice*1)*100);
              $(this).parents('.bikeRow').find('.bikepVenteHTVA').html(newPrice + '€ ' + " <span class=\"text-green\">(+)</span> <br/><span class=\"text-red\">("+reduction+"%)</span> ");
            }
            var buyingPrice=$(this).parents('.bikeRow').find('.bikepAchat').html().split('€')[0];
            var marge = (newPrice*1 - buyingPrice).toFixed(0) + '€ (' + ((newPrice*1 - buyingPrice)/(buyingPrice*1)*100).toFixed(0) + '%)';
            $(this).parents('.bikeRow').find('.bikeMarge').html(marge);
            $(this).parents('.bikeRow').find('.bikeFinalPrice').val(newPrice);
          });
        });

        //retrait
        $('.generateBillBike .glyphicon-minus').unbind();

        $('.generateBillBike .glyphicon-minus').click(function(){
          bikesNumber = $("#addBill").find('.bikesNumber').html();
          if(bikesNumber > 0){
            $('#addBill').find('.bikesNumber').html(bikesNumber*1 - 1);
            $('#bikesNumberBill').val(bikesNumber*1 - 1);
            $('#addBill').find('.bikesNumberTable'+bikesNumber).slideUp().remove();
            bikesNumber--;
          }
          checkMinus('.generateBillBike','.bikesNumber');
        });
    }
  })
});


function get_bills_listing() {

  $('#billsToSendListing').html("");

  $.ajax({
    url: 'api/bills',
    type: 'get',
    data: {"action" : "list"},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){

        $('#widget-addBill-form input[name=ID_OUT]').val(parseInt(response.IDMaxBillingOut) +1);
        $('#widget-addBill-form input[name=ID]').val(parseInt(response.IDMaxBilling) +1);
        $('#widget-addBill-form input[name=communication]').val(response.communication);
        $('#widget-addBill-form input[name=communicationHidden]').val(response.communication);

        var i=0;
        var dest="";


        if(response.update){
          var temp="<table id=\"billsListingTable\" class=\"table table-condensed\" data-order='[[ 1, \"desc\" ]]' data-page-length='50'><h4 class=\"text-green\">Vos Factures:</h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addBill\" data-toggle=\"modal\" onclick=\"create_bill()\" href=\"#\"><i class=\"fa fa-plus\"></i> Ajouter une facture</a><thead><tr><th>Type</th><th>ID</th><th style='width : 5%;'>Société</th><th style='width : 5%;'>Date d'initiation</th><th style='width : 5%;'>Montant (HTVA)</th><th style='width : 5%;'>Communication</th><th style='width : 5%;'>Envoi ?</th><th style='width : 5%;'>Payée ?</th><th style='width : 5%;'>Limite de paiement</th><th style='width : 5%;'>Comptable ?</th><th></th></tr></thead><tbody>";
        }else{
          var temp="<table id=\"billsListingTable\" class=\"table table-condensed\" data-order='[[ 1, \"desc\" ]]' data-page-length='50'><h4 class=\"text-green\">Vos Factures:</h4><br/><thead><tr><th>ID</th><th>Date d'initiation</th><th>Montant (HTVA)</th><th>Communication</th><th>Envoyée ?</th><th>Payée ?</th><th>>Limite de paiement</th></tr></thead><tbody>";
        }
        dest=dest.concat(temp);

        response.bill.forEach(function(bill){

          if(bill.sentDate==null){
            var sendDate="N/A";
          }else{
            var sendDate=bill.sentDate.shortDate();
          }
          if(bill.paidDate==null){
            var paidDate="N/A";
          }else{
            var paidDate=bill.paidDate.shortDate();
          }
          if(bill.sent=="0"){
            var sent="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"><span class='hidden'>N</span></i>";
          }else{
            var sent="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"><span class='hidden'>Y</span></i>";
          }
          if(bill.paid=="0"){
            var paid="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"><span class='hidden'>N</span></i>";
          }else{
            var paid="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"><span class='hidden'>Y</span></i>";
          }

          if(bill.limitPaidDate && bill.paid=="0"){
            var dateNow=new Date();
            var dateLimit=new Date(bill.limitPaidDate);

            let month = String(dateLimit.getMonth() + 1);
            let day = String(dateLimit.getDate());
            let year = String(dateLimit.getFullYear());

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;


            if(dateNow>dateLimit){
              var paidLimit="<span class=\"text-red\">"+day+"/"+month+"/"+year.substr(2,2)+"</span>";
            }else{
              var paidLimit="<span>"+day+"/"+month+"/"+year.substr(2,2)+"</span>";
            }
          }else if(bill.paid=="0"){
            var paidLimit="<span class=\"text-red\">N/A</span>";
          }else{
            var paidLimit="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
          }



          if(response.update && bill.amountHTVA>=0){
            var temp="<tr><td class=\"text-green\">IN</td>";
          }else if(response.update && bill.amountHTVA<0){
            var temp="<tr><td class=\"text-red\">OUT</td>";
          }else{
            var temp="<tr>";
          }
          dest=dest.concat(temp);

          if(bill.fileName){
            var temp="<td><a href=\"factures/"+bill.fileName+"\" target=\"_blank\">"+bill.ID+"</a></td>";
          }
          else{
            var temp="<td><a href=\"#\" class=\"text-red\">"+bill.ID+"</a></td>";
          }
          dest=dest.concat(temp);
          if(response.update && bill.amountHTVA>=0){
            var temp="<td>"+bill.company+"</a></td>";
            dest=dest.concat(temp);
          }else if(response.update && bill.amountHTVA<0){
            var temp="<td>"+bill.beneficiaryCompany+"</a></td>";
            dest=dest.concat(temp);
          }
          var temp="<td data-sort=\""+(new Date(bill.date)).getTime()+"\">"+bill.date.shortDate()+"</td><td>"+Math.round(bill.amountHTVA)+" €</td><td>"+bill.communication+"</td>";
          dest=dest.concat(temp);

          if(sent=="Y"){
            var temp="<td class=\"text-green\">"+sendDate+"</td>";
          }else{
            var temp="<td class=\"text-red\">"+sent+"</td>";
          }
          dest=dest.concat(temp);

          if(paid=="Y"){
            var temp="<td class=\"text-green\">"+paidDate+"</td>";
          }else{
            var temp="<td class=\"text-red\">"+paid+"</td>";
          }
          dest=dest.concat(temp);

          dest=dest.concat("<td>"+paidLimit+"</td>");


          if(response.update){
            if(bill.communicationSentAccounting=="1"){
              var temp="<td class=\"text-green\">OK</td>";
            }else{
              var temp="<td class=\"text-red\">KO</td>";
            }
            dest=dest.concat(temp);
          }

          if(response.update){
            temp="<td><ins><a class=\"text-green updateBillingStatus\" data-target=\"#updateBillingStatus\" name=\""+bill.ID+"\" data-toggle=\"modal\" href=\"#\">Update</a></ins></td>";
            dest=dest.concat(temp);
          }

          dest=dest.concat("</tr>");

          if(response.update){
            if(bill.sent=='0'){
              $.ajax({
                url: 'api/bills',
                type: 'get',
                data: {"action" : "getContactsForBillingSending", 'company' : bill.companyID},
                success: function(contacts){
                  var dest3="";
                  dest3+="<div class='row' style='border-top: 1px solid grey'><form action='api/bills' class='sendBillForm' role='form' method='post'><div class='col-md-4'><table class=\"table table-condensed\"><thead><tr><th>ID</th><th>Société</th><th>Montant</th><th>Date</th></tr></thead><tbody>";
                  dest3+="<tr><td><a href=\"factures/"+bill.fileName+"\" target=\"_blank\"><i class=\"fa fa-file\"></i></a><input type=\"text\" class=\"form-control required hidden\" name='ID' value=\""+bill.ID+"\" /></a></td>";
                  dest3+="<td>"+bill.company+"</a></td>";
                  dest3+="<td>"+Math.round(bill.amountHTVA)+" €</td>";
                  dest3+="<td data-sort=\""+(new Date(bill.date)).getTime()+"\">"+bill.date.shortDate()+"</td></tr></tbody></table></div>";
                  dest3+="<div class='col-md-8'><table class='table table-condensed'>";
                  if(contacts.beneficiaries.length==0){
                    dest3+="<tr><strong>Destinataire</strong> : Pas de destinataire défini, veuillez en définir un dans le descriptif de la société</tr>";
                  }else {
                    contacts.beneficiaries.forEach(function(beneficiary){
                      dest3+="<tr class='beneficiary'><td><strong>Destinataire</strong></td><td><input type=\"text\" class=\"form-control required\" name='email' value=\""+beneficiary.EMAIL+"\"/></td><td><input type=\"text\" class=\"form-control required\" name='firstName' value=\""+beneficiary.PRENOM+"\"/></td><td><input type=\"text\" name='name' class=\"form-control required\" value=\""+beneficiary.NOM+"\"/></td><td><button class='button small red button-3d rounded deleteContact' type='button'>Supprimer</button></td></tr>";
                    })
                  }
                  contacts.cc.forEach(function(cc){
                    dest3+="<tr class='cc'><td><strong>En copie</strong></td><td><input type=\"text\" class=\"form-control\" name='email' value=\""+cc.EMAIL+"\"/></td><td><input type=\"text\" class=\"form-control required\" name='firstName' value=\""+cc.PRENOM+"\"/></td><td><input type=\"text\" name='name' class=\"form-control required\" value=\""+cc.NOM+"\"/></td><td><button class='button small red button-3d rounded deleteContact' type='button'>Supprimer</button></td></tr>";
                  })
                  dest3+="</table>";
                  dest3+="<button class='button small green button-3d rounded sendBillButton' type='button'>Envoyer</button></div></form></div>"
                  $('#billsToSendListing').append(dest3);
                  $('.sendBillButton').off();
                  $('.sendBillButton').click(function() {
                    sendBill($(this));
                  })

                  $('.deleteContact').off();
                  $('.deleteContact').click(function(){
                    $(this).closest('tr').remove();
                  })
                }
              })
            }
          }
        })
        if(response.update){
          $('.billsToSendSpan').removeClass("hidden");
        }else{
          $('.billsToSendSpan').addClass("hidden");
        }

        document.getElementById('billsListing').innerHTML = dest;


        function sendBill(element){
          var $form=$(element).closest('form');
          var id=$form.find('table tbody input[name=ID]').val();
          var i=0;
          var beneficiaries = [];
          $form.find('table .beneficiary').each(function(mail){
            var beneficiary={};
            beneficiary.email=($(this).find('input[name=email]').val());
            beneficiary.name=($(this).find('input[name=name]').val());
            beneficiary.firstName=($(this).find('input[name=firstName]').val());
            beneficiaries.push(beneficiary);
            i++;
          })

          var i=0;
          var ccs = [];
          $form.find('table .cc').each(function(mail){
            var cc={};
            cc.email=($(this).find('input[name=email]').val());
            cc.name=($(this).find('input[name=name]').val());
            cc.firstName=($(this).find('input[name=firstName]').val());
            ccs.push(cc);
            i++;
          })


          $.ajax({
            url: 'api/bills',
            type: 'post',
            data: { action:'sendBill', ID:id, beneficiaries: beneficiaries, ccs: ccs},
            success: function(response){
              if(response.response == 'error') {
                $.notify({
                  message: response.message
                }, {
                  type: 'danger'
                });
              }
              if(response.response == 'success'){
                get_bills_listing();
                $.notify({
                  message: response.message
                }, {
                  type: 'success'
                });
              }
            }
          })


        };


        var classname = document.getElementsByClassName('updateBillingStatus');
        for (var i = 0; i < classname.length; i++) {
          classname[i].addEventListener('click', function() {construct_form_for_billing_status_update(this.name)}, false);
        }

        $("#billsListingTable thead tr").clone(true).appendTo("#billsListingTable thead");

        $("#billsListingTable thead tr:eq(1) th").each(function (i) {
          var title = $(this).text();
          $(this).html('<input style="width: 100%" type="text" />');

          $("input", this).on("keyup change", function () {
            if (table.column(i).search() !== this.value) {
              table.column(i).search(this.value).draw();
            }
          });
        });


        var table = $('#billsListingTable').DataTable({
          orderCellsTop: true,
          fixedHeader: true,
          scrollX: false,
          paging: false,
          search: false
        });
      }
    }
  })
}

//liste des Accessoires
function get_all_accessories() {
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
