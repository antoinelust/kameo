window.addEventListener("DOMContentLoaded", function(event) {
    
    
    $('#widget-addBill-form select[name=billType]').change(function(){
        console.log($('#widget-addBill-form select[name=billType]').val());
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
			var dest='<table class=\"table table-condensed\"><thead><tr><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Bike</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Comentaire</span><span class=\"en-inline\">Comment</span><span class=\"nl-inline\">Comment</span></th></tr></thead><tbody>';
			for(var i = 0; i<response.billDetailsNumber; i++){
			  dest=dest.concat("<tr><th>"+response.bill.billDetails[i].bikeID + " - " + response.bill.billDetails[i].frameNumber+"</th><th>"+response.bill.billDetails[i].amountHTVA+"</th><th>"+response.bill.billDetails[i].comments+"</th></tr>");
			}
			document.getElementById('billingDetails').innerHTML=dest.concat("</tbody><table>");
			displayLanguage();
        }
    }
  });
}

function create_bill(){
    $.ajax({
      url: 'apis/Kameo/get_companies_listing.php',
      type: 'post',
      data: {type: "*"},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          var i=0;
          var dest="<select name=\"widget-addBill-form-company\" class=\"widget-addBill-form-company2\">";
          while (i < response.companiesNumber){
            temp="<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>";
            dest=dest.concat(temp);
            i++;
          }
          dest=dest.concat("<option value=\"other\">Autre</option></select>");
          document.getElementsByClassName('widget-addBill-form-company')[0].innerHTML = dest;
          document.getElementsByClassName('widget-addBill-form-date')[0].value = "";
          document.getElementsByClassName('widget-addBill-form-amountHTVA')[0].value = "";
          document.getElementsByClassName('widget-addBill-form-amountTVAC')[0].value = "";
          document.getElementsByClassName('widget-addBill-form-sendingDate')[0].value = "";
          document.getElementsByClassName('widget-addBill-form-paymentDate')[0].value = "";
          $('.widget-addBill-form-companyOther').addClass("hidden");
          $('.IDAddBill').removeClass('hidden');
          $('.IDAddBillOut').removeClass('hidden');

        //création des variables
        var bikes = [];
        get_all_bikes_stock_command().done(function(response){
          bikes = response.bike;
          if(bikes == undefined){
            bikes =[];
            console.log('bikes => table vide');
          }
          //tableau bikes avec tout les champs
          var bikeModels = "<option hidden disabled selected value></option>";

          //gestion du moins au lancement de la page
          checkMinus('.generateBillBike','.bikesNumber');

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


            var hideBikepVenteHTVA;
            var hideBikeLeasing ='';
            var inRecapLeasingBike ='';
            var inRecapVenteBike ='';

            //creation du div contenant
            $('#addBill').find('.generateBillBike tbody')
            .append(`<tr class="bikesNumberTable`+(bikesNumber)+` bikeRow form-group">
            <td class="bLabel"></td>
            <td class="bikeID"></td>
            <td class="bikepAchat"></td>
            <td class="bikepCatalog"></td>
            <td contenteditable='true' class="bikepVenteHTVA TD_bikepVenteHTVA `+inRecapVenteBike+`"`+hideBikepVenteHTVA+`></td>
            <td class="bikeMarge"></td>
            <td><input type="number" name="bikeFinalPrice[]" class="bikeFinalPrice hidden"></td>
            </tr>`);

            //label selon la langue
            $('#addBill').find('.bikesNumberTable'+(bikesNumber)+'>.bLabel')
            .append('<label class="fr">Vélo '+ bikesNumber +'</label>');

            $('#addBill').find('.bikesNumberTable'+(bikesNumber)+'>.bikeID')
            .append(`<select name="bikeID[]" class="select`+bikesNumber+` bikeID form-control required">`+
            bikeModels+
            `</select>`);


            //gestion du select du velo
            $('.generateBillBike select').on('change',function(){

              var that ='.'+ $(this).attr('class').split(" ")[0];
              var id =$(that).val();

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

                $(that).parents('.bikeRow').find('.bikepAchat').html(pAchat + " <span class=\"text-red\">(-)</span>");
                $(that).parents('.bikeRow').find('.bikepCatalog').html(pVenteHTVA);
                $(that).parents('.bikeRow').find('.bikepVenteHTVA').html(pVenteHTVA);
                $(that).parents('.bikeRow').find('.bikepVenteHTVA').attr('data-orig',pVenteHTVA);
                $(that).parents('.bikeRow').find('.bikeMarge').html(marge);
                $(that).parents('.bikeRow').find('.bikeFinalPrice').val(bikes[id].priceHTVA);
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
        });

        //Accessoires
        get_all_accessories().done(function(response){
          //gestion du moins au lancement de la page
          checkMinus('.generateBillAccessories','.accessoriesNumber');
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
            .append('<label class="fr">Accessoire '+ accessoriesNumber +'</label>');

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
                  accessoriesOption += '<option value="'+accessory.id+'">'+accessory.name+'</option>';
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
            .append('<label class="fr">Accessoire '+ otherAccessoriesNumber +'</label>');
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

            
        var dateInOneMonth=new Date();
        dateInOneMonth.setMonth(dateInOneMonth.getMonth()+1);
        var year=dateInOneMonth.getFullYear();
        var month=("0" + (dateInOneMonth.getMonth()+1)).slice(-2)
        var day=("0" + dateInOneMonth.getDate()).slice(-2)
        var dateInOneMonthString=year+"-"+month+"-"+day;

        $('#widget-addBill-form input[name=widget-addBill-form-date]').val(get_dateNow_string());
        $('#widget-addBill-form input[name=widget-addBill-form-datelimite]').val(dateInOneMonthString);

        }
      }
    })
}



function get_bills_listing(company, sent, paid, direction, email) {    
    $.ajax({
        url: 'apis/Kameo/get_bills_listing.php',
        type: 'post',
        data: { "email": email, "company": company, "sent": sent, "paid": paid, "direction": direction},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){

                console.log(response);
                
                $('#widget-addBill-form input[name=ID_OUT]').val(parseInt(response.IDMaxBillingOut) +1);
                $('#widget-addBill-form input[name=ID]').val(parseInt(response.IDMaxBilling) +1);
                $('#widget-addBill-form input[name=communication]').val(response.communication);
                $('#widget-addBill-form input[name=communicationHidden]').val(response.communication);

                var i=0;
                var dest="";
                var dest3="";
                
                
                if(response.update){

                    var temp="<table id=\"billsListingTable\" class=\"table table-condensed\" data-order='[[ 1, \"desc\" ]]' data-page-length='50'><h4 class=\"fr-inline text-green\">Vos Factures:</h4><h4 class=\"en-inline text-green\">Your Bills:</h4><h4 class=\"nl-inline text-green\">Your Bills:</h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addBill\" data-toggle=\"modal\" onclick=\"create_bill()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une facture</span></a><thead><tr><th>Type</th><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Date d'initiation</span><span class=\"en-inline\">Generation Date</span><span class=\"nl-inline\">Generation Date</span></th><th><span class=\"fr-inline\">Montant (HTVA)</span><span class=\"en-inline\">Amount (VAT ex.)</span><span class=\"nl-inline\">Amount (VAT ex.)</span></th><th><span class=\"fr-inline\">Communication</span><span class=\"en-inline\">Communication</span><span class=\"nl-inline\">Communication</span></th><th><span class=\"fr-inline\">Envoi ?</span><span class=\"en-inline\">Sent</span><span class=\"nl-inline\">Sent</span></th><th><span class=\"fr-inline\">Payée ?</span><span class=\"en-inline\">Paid ?</span><span class=\"nl-inline\">Paid ?</span></th><th><span class=\"fr-inline\">Limite de paiement</span><span class=\"en-inline\">Limit payment date</span><span class=\"nl-inline\">Limit payment date</span></th><th>Comptable ?</th><th></th></tr></thead><tbody>";
                    var temp3="<table id=\"billsToSendListingTable\" class=\"table table-condensed\" data-order='[[ 1, \"desc\" ]]' data-page-length='50'><thead><tr><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount (VAT ex.)</span><span class=\"nl-inline\">Amount (VAT ex.)</span></th><th><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th>Email</th><th>Prénom</th><th>Nom</th><th></th></tr></thead><tbody>";
                }else{
                    var temp="<table id=\"billsListingTable\" class=\"table table-condensed\" data-order='[[ 1, \"desc\" ]]' data-page-length='50'><h4 class=\"fr-inline text-green\">Vos Factures:</h4><h4 class=\"en-inline text-green\">Your Bills:</h4><h4 class=\"nl-inline text-green\">Your Bills:</h4><br/><thead><tr><th>ID</th><th><span class=\"fr-inline\">Date d'initiation</span><span class=\"en-inline\">Generation Date</span><span class=\"nl-inline\">Generation Date</span></th><th><span class=\"fr-inline\">Montant (HTVA)</span><span class=\"en-inline\">Amount (VAT ex.)</span><span class=\"nl-inline\">Amount (VAT ex.)</span></th><th><span class=\"fr-inline\">Communication</span><span class=\"en-inline\">Communication</span><span class=\"nl-inline\">Communication</span></th><th><span class=\"fr-inline\">Envoyée ?</span><span class=\"en-inline\">Sent ?</span><span class=\"nl-inline\">Sent ?</span></th><th><span class=\"fr-inline\">Payée ?</span><span class=\"en-inline\">Paid ?</span><span class=\"nl-inline\">Paid ?</span></th><th><span class=\"fr-inline\">Limite de paiement</span><span class=\"en-inline\">Limit payment date</span><span class=\"nl-inline\">Limit payment date</span></th></tr></thead><tbody>";
                }
                dest=dest.concat(temp);
                dest3=dest3.concat(temp3);
                
                while (i < response.billNumber){
                    
                    
                    
                    if(response.bill[i].sentDate==null){
                        var sendDate="N/A";
                    }else{
                        var sendDate=response.bill[i].sentDate.shortDate();
                    }
                    if(response.bill[i].paidDate==null){
                        var paidDate="N/A";
                    }else{
                        var paidDate=response.bill[i].paidDate.shortDate();
                    }
                    if(response.bill[i].sent=="0"){
                        var sent="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                    }else{
                        var sent="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                    }
                    if(response.bill[i].paid=="0"){
                        var paid="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                    }else{
                        var paid="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                    }

                    if(response.bill[i].limitPaidDate && response.bill[i].paid=="0"){
                        var dateNow=new Date();
                        var dateLimit=new Date(response.bill[i].limitPaidDate);

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
                    }else if(response.bill[i].paid=="0"){
                        var paidLimit="<span class=\"text-red\">N/A</span>";
                    }else{
                        var paidLimit="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                    }

                    

                    if(response.update && response.bill[i].amountHTVA>=0){
                        var temp="<tr><td class=\"text-green\">IN</td>";
                    }else if(response.update && response.bill[i].amountHTVA<0){
                        var temp="<tr><td class=\"text-red\">OUT</td>";
                    }else{
                        var temp="<tr>";
                    }
                    dest=dest.concat(temp);

                    if(response.bill[i].fileName){
                        var temp="<td><a href=\"factures/"+response.bill[i].fileName+"\" target=\"_blank\">"+response.bill[i].ID+"</a></td>";
                    }
                    else{
                        var temp="<td><a href=\"#\" class=\"text-red\">"+response.bill[i].ID+"</a></td>";
                    }
                    dest=dest.concat(temp);
                    if(response.update && response.bill[i].amountHTVA>=0){
                        var temp="<td>"+response.bill[i].company+"</a></td>";
                        dest=dest.concat(temp);
                    }else if(response.update && response.bill[i].amountHTVA<0){
                        var temp="<td>"+response.bill[i].beneficiaryCompany+"</a></td>";
                        dest=dest.concat(temp);
                    }
                    var temp="<td data-sort=\""+(new Date(response.bill[i].date)).getTime()+"\">"+response.bill[i].date.shortDate()+"</td><td>"+Math.round(response.bill[i].amountHTVA)+" €</td><td>"+response.bill[i].communication+"</td>";
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
                        if(response.bill[i].communicationSentAccounting=="1"){
                            var temp="<td class=\"text-green\">OK</td>";
                        }else{
                            var temp="<td class=\"text-red\">KO</td>";
                        }
                        dest=dest.concat(temp);
                    }

                    if(response.update){
                        temp="<td><ins><a class=\"text-green updateBillingStatus\" data-target=\"#updateBillingStatus\" name=\""+response.bill[i].ID+"\" data-toggle=\"modal\" href=\"#\">Update</a></ins></td>";
                        dest=dest.concat(temp);
                    }

                    dest=dest.concat("</tr>");
                    
                    if(response.update){
                        if(response.bill[i].sent=='0'){
                            
                                                                
                            var temp3="<tr><td><a href=\"factures/"+response.bill[i].fileName+"\" target=\"_blank\"><i class=\"fa fa-file\"></i></a><input type=\"text\" class=\"form-control required hidden ID\" value=\""+response.bill[i].ID+"\" /></a></td>";
                            dest3=dest3.concat(temp3);
                            var temp3="<td>"+response.bill[i].company+"</a></td>";
                            dest3=dest3.concat(temp3);
                            var temp3="<td>"+Math.round(response.bill[i].amountHTVA)+" €</td>";
                            dest3=dest3.concat(temp3);
                            var temp3="<td data-sort=\""+(new Date(response.bill[i].date)).getTime()+"\">"+response.bill[i].date.shortDate()+"</td>";                 
                            dest3=dest3.concat(temp3);
                            var temp3="<td><input type=\"text\" class=\"form-control required email\" value=\""+response.emailContactBilling+"\"/></td>";                 
                            dest3=dest3.concat(temp3);
                            var temp3="<td><input type=\"text\" class=\"form-control required firstName\" value=\""+response.firstNameContactBilling+"\"/></td>";                 
                            dest3=dest3.concat(temp3);
                            var temp3="<td><input type=\"text\" class=\"form-control required lastName\" value=\""+response.lastNameContactBilling+"\"/></td>";                 
                            dest3=dest3.concat(temp3);
                            var temp3="<td><input type=\"text\" class=\"form-control required hidden date\" value=\""+response.bill[i].date+"\"/></td>";     
                            dest3=dest3.concat(temp3);                                
                            var temp3="<td><input type=\"text\" class=\"form-control required hidden fileName\" value=\""+response.bill[i].fileName+"\"/></td>";
                            dest3=dest3.concat(temp3);

                            dest3=dest3.concat("<td><button  class=\"sendBillButton button small green button-3d rounded icon-left\"><i class=\"fa fa-check\"></i>Envoyer</button></tr>");
                        }
                    }                    
                    i++;
                }
                var temp="</tbody></table>";
                dest=dest.concat(temp);
                var temp3="</tbody></table>";
                dest3=dest3.concat(temp3);
                
                if(response.update){
                    $('.billsToSendSpan').removeClass("hidden");
                }else{
                    $('.billsToSendSpan').addClass("hidden");
                }
                
                document.getElementById('billsListing').innerHTML = dest;
                document.getElementById('billsToSendListing').innerHTML = dest3;
                
                
                $('.sendBillButton').click(function() {
                    var email_client=$(this).parents('tr').find('.email').val();
                    var id=$(this).parents('tr').find('.ID').val();
                    var lastName=$(this).parents('tr').find('.lastName').val();
                    var firstName=$(this).parents('tr').find('.firstName').val();
                    var date=$(this).parents('tr').find('.date').val();
                    var fileName=$(this).parents('tr').find('.fileName').val();
                    
                    $.ajax({
                        url: 'apis/Kameo/send_bill.php',
                        type: 'post',
                        data: { "id": id, "email": email_client, "firstName": firstName, "lastName": lastName, "date": date, "fileName": fileName},
                        success: function(response){
                            if(response.response == 'error') {
                              $.notify({
                                message: response.message
                              }, {
                                type: 'danger'
                              });
                            }
                            if(response.response == 'success'){
                              get_bills_listing('*', '*', '*', '*',email);
                              $.notify({
                                message: response.message
                              }, {
                                type: 'success'
                              });
                            }
                        }
                    })

                    
                });                
                
                
                var classname = document.getElementsByClassName('updateBillingStatus');
                for (var i = 0; i < classname.length; i++) {
                    classname[i].addEventListener('click', function() {construct_form_for_billing_status_update(this.name)}, false);
                }
                displayLanguage();
                
                $('#billsListingTable').DataTable();
                //$('#billsToSendListingTable').DataTable();
                

            }
        }
    })
}

function get_all_bikes_stock_command() {
  return  $.ajax({
    url: 'apis/Kameo/get_bikes_listing.php',
    type: 'post',
    data: {"stockAndCommand": true,  admin: 'Y'},
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
  console.log(select);
  console.log($(select).find(valueLocation).html());
  if ($(select).find(valueLocation).html() == '0') {
    $(select).find('.glyphicon-minus').fadeOut();
    $(select).find('.hideAt0').hide();
  }else{
    $(select).find('.glyphicon-minus').fadeIn();
    $(select).find('.hideAt0').show();
  }
}
