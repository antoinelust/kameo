$( ".fleetmanager" ).click(function() {
  $.ajax({
    url: 'apis/Kameo/initialize_counters.php',
    type: 'post',
    data: { "email": email, "type": "ordersAdmin"},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){
        document.getElementById("counterOrdersAdmin").innerHTML = '<span style="margin-left:20px">'+response.ordersNumber+'</span>';
      }
    }
  })
})


$("#ordersListing").on("show.bs.modal", function (event) {
  list_bikes();
});

var price;


function get_orders_listing() {
  var email= "<?php echo $user_data['EMAIL']; ?>";
  $.ajax({
    url: 'apis/Kameo/orders_management.php',
    type: 'get',
    data: {"action": "list"},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){
        var dest="";
        var temp="<table id=\"ordersListingTable\" data-page-length='25' class=\"table table-condensed\"><thead><tr><th>ID</th><th>Société</th><th>Utilisateur</th><th>Vélo</th><th>Taille</th><th>Status</th><th>Test ?</th><th>Date Livraison</th><th>Montant</th></tr></thead><tbody>";
        dest=dest.concat(temp);
        var i=0;
        while (i < response.ordersNumber){
          if(response.order[i].testBoolean){
            if(response.order[i].testStatus=="done"){
              var test = "Done";
            }else if(response.order[i].testStatus=="cancelled"){
              var test = "Cancelled";
            }else{
              if(response.order[i].testDate){
                var test = response.order[i].testDate.shortDate();
              }else{
                var test = "TBC";
              }
            }
          }else{
            var test = "N";
          }
          if(response.order[i].estimatedDeliveryDate == null){
            var estimatedDeliveryDate = "TBC";
          }else{
            var estimatedDeliveryDate = response.order[i].estimatedDeliveryDate.shortDate();
          }

          if(response.order[i].type=="leasing"){
            var price = Math.round(response.order[i].price*100)/100 + " €/mois";
          }else if(response.order[i].type=="annualLeasing"){
            var price = Math.round(response.order[i].price*100)/100 + " €/an";
          }else{
            var price = Math.round(response.order[i].price*100)/100 + " €";
          }
          if(response.order[i].status=='confirmed'){

           if(response.order[i].contract=='pending_delivery'){
            temp="<tr style =\"color:#3CB195;\"><td><a href=\"#\" class=\"updateCommand\" data-target=\"#orderManager\" data-toggle=\"modal\" name=\""+response.order[i].ID+"\" data-email=\""+response.order[i].email+"\" data-company=\""+response.order[i].companyID+"\">"+response.order[i].ID+"</td><td><a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+response.order[i].companyID+"\">"+response.order[i].companyName+"</a></td><td>"+response.order[i].email+"</td><td>"+response.order[i].brand+" - "+response.order[i].model+"</td><td>"+response.order[i].size+"</td><td>"+response.order[i].status+"</td><td>"+test+"</td><td>"+estimatedDeliveryDate+"</td><td>"+price+"</td></tr>";
          }
          else if(response.order[i].contract==null){
            temp="<tr style =\"color:red;\"><td><a href=\"#\" class=\"updateCommand\" data-target=\"#orderManager\" data-toggle=\"modal\" name=\""+response.order[i].ID+"\" data-email=\""+response.order[i].email+"\" data-company=\""+response.order[i].companyID+"\">"+response.order[i].ID+"</td><td><a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+response.order[i].companyID+"\">"+response.order[i].companyName+"</a></td><td>"+response.order[i].email+"</td><td>"+response.order[i].brand+" - "+response.order[i].model+"</td><td>"+response.order[i].size+"</td><td>"+response.order[i].status+"</td><td>"+test+"</td><td>"+estimatedDeliveryDate+"</td><td>"+price+"</td></tr>";
          }
          else if(response.order[i].contract=='order'){
            temp="<tr><td><a href=\"#\" class=\"updateCommand\" data-target=\"#orderManager\" data-toggle=\"modal\" name=\""+response.order[i].ID+"\" data-email=\""+response.order[i].email+"\" data-company=\""+response.order[i].companyID+"\">"+response.order[i].ID+"</td><td><a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+response.order[i].companyID+"\">"+response.order[i].companyName+"</a></td><td>"+response.order[i].email+"</td><td>"+response.order[i].brand+" - "+response.order[i].model+"</td><td>"+response.order[i].size+"</td><td>"+response.order[i].status+"</td><td>"+test+"</td><td>"+estimatedDeliveryDate+"</td><td>"+price+"</td></tr>";
          }
          else {
            temp="<tr style =\"color:blue;\"><td><a href=\"#\" class=\"updateCommand\" data-target=\"#orderManager\" data-toggle=\"modal\" name=\""+response.order[i].ID+"\" data-email=\""+response.order[i].email+"\" data-company=\""+response.order[i].companyID+"\">"+response.order[i].ID+"</td><td><a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+response.order[i].companyID+"\">"+response.order[i].companyName+"</a></td><td>"+response.order[i].email+"</td><td>"+response.order[i].brand+" - "+response.order[i].model+"</td><td>"+response.order[i].size+"</td><td>"+response.order[i].status+"</td><td>"+test+"</td><td>"+estimatedDeliveryDate+"</td><td>"+price+"</td></tr>";
          }
        }
        else{
          temp="<tr ><td><a href=\"#\" class=\"updateCommand\" data-target=\"#orderManager\" data-toggle=\"modal\" name=\""+response.order[i].ID+"\" data-company=\""+response.order[i].companyID+"\">"+response.order[i].ID+"</td><td><a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+response.order[i].companyID+"\">"+response.order[i].companyName+"</a></td><td>"+response.order[i].email+"</td><td>"+response.order[i].brand+" - "+response.order[i].model+"</td><td>"+response.order[i].size+"</td><td>"+response.order[i].status+"</td><td>"+test+"</td><td>"+estimatedDeliveryDate+"</td><td>"+price+"</td></tr>";
          //si closed et pas mail mettre en bleu sinon noir
        }

        dest=dest.concat(temp);
        i++;

      }
      var temp="</tobdy></table>";
      dest=dest.concat(temp);
      document.getElementById('ordersListingSpan').innerHTML = dest;
      $('#ordersListingTable thead tr').clone(true).appendTo('#test thead');

      $('#ordersListingTable thead tr:eq(1) th').each(function(i){
        var title=$(this).text();
        $(this).html('<input type="text" placeholder="Search" />');

        $('input', this).on('keyup change', function(){
          if (table.column(i).search() !== this.value){
            table
            .column(i)
            .search(this.value)
            .draw();
          }
        });
      });

      var table=$('#ordersListingTable').DataTable({
        "aaSorting": []
      });

      var classname = document.getElementsByClassName(
        "internalReferenceCompany"
        );
      for (var i = 0; i < classname.length; i++) {
        classname[i].addEventListener(
          "click",
          function () {
            get_company_details(this.name, email, true);
          },
          false
          );
      }


    }
  }
})

}

$('body').on('click', '.updateCommand',function(){
  $("#widget-order-form select[name=company]").val($(this).data('company'));
  retrieve_command(this.name);
  $("#widget-order-form div[name=ID]").show();
  $(".orderManagementTitle").html("Gestion de la commande client");
  $("#widget-order-form input[name=action]").val("update");
});


///////////////////////////////////////////////////////////////////////////////////

$('body').on('click', '.testAssignation',function(){

  $.ajax({
    url: 'apis/Kameo/companies/companies.php',
    type: 'get',
    data: {"action": "retrieve", "ID": $("#widget-order-form select[name=company]").val()},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){
        $('#widget-bikeManagement-form select[name=company]').val(response.internalReference);
        update_users_list(response.internalReference, $('#widget-order-form input[name=mail]').val());
      }
    }
  });

  $('.contractInfos').fadeOut("slow");
  $('.billingInfos').fadeOut("slow");
  $('.buyingInfos').fadeIn("slow");
  $('.orderInfos').fadeIn("slow");
  $('.billingPriceDiv').fadeOut("slow");
  $('.billingGroupDiv').fadeOut("slow");
  $('.billingDiv').fadeOut("slow");

  let today = new Date().toISOString().substr(0, 10);


  $("#widget-bikeManagement-form input[name=orderingDate]").val(today);
  $("#widget-bikeManagement-form input[name=action]").val("add");
  $("#bikeManagementPicture").attr("src","images_bikes/"+$("#widget-order-form select[name=portfolioID]").val()+".jpg");
  $("#widget-bikeManagement-form input[name=size]").val($("#widget-order-form select[name=size]").val());
  $("#widget-bikeManagement-form input[name=model]").val($("#widget-order-form input[name=model]").val());
  $("#widget-bikeManagement-form input[name=price]").val(price);
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"order\">Commande</option>");
  $("#widget-bikeManagement-form select[name=portfolioID]").val($("#widget-order-form select[name=portfolioID]").val());
  $("#widget-bikeManagement-form select[name=company]").val($("#widget-order-form select[name=company]").val());
});



$('body').on('click', '.addOrder',function(){
  $('#widget-order-form div[name=assignationBikeHide]').addClass("hidden");
  $('#widget-order-form')[0].reset();
  $("#widget-order-form div[name=ID]").hide();
  $(".orderManagementTitle").html("Ajouter une commande");
  $("#widget-order-form input[name=action]").val("add");
  $('.accessoriesNumber').html('');
});

function list_bikes(){
  $.ajax({
    url: 'apis/Kameo/load_portfolio.php',
    type: 'get',
    data: {"action": "list"},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){
        const portfolioSorted=response.bike.sort(function(a, b){
                //note the minus before -cmp, for descending order
                return cmp(
                  [cmp(a.brand, b.brand), cmp(a.model, b.model)],
                  [cmp(b.brand, a.brand), cmp(b.model, a.model)]
                  );
              });

        $('#widget-order-form select[name=portfolioID]').empty();
        portfolioSorted.forEach(function(bike){
          $('#widget-order-form select[name=portfolioID]').append('<option value='+bike.ID+'>'+bike.brand+' '+bike.model+' - '+bike.frameType+' - '+bike.season+' - ID catalogue :'+bike.ID+'</option>');
        })
     }
   }
 });
}

function retrieve_command(ID){
  $('.accessoriesNumber').html('');
  $("#ExistingAccessory tbody").html("");
    $.ajax({
      url: 'apis/Kameo/orders_management.php',
      type: 'get',
      data: {"action": "retrieve", "ID": ID, "email": email},
      success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){
        $('#widget-order-form input[name=ID]').val(ID);
        $('#widget-order-form select[name=type]').val(response.order.type);
        $('#widget-order-form input[name=deliveryDate]').val(response.order.estimatedDeliveryDate);
        $('#widget-order-form input[name=price]').val(response.order.price);
        $('#widget-order-form select[name=portfolioID]').val(response.order.portfolioID).attr('disabled', false);
        $('#widget-order-form input[name=brand]').val(response.order.brand).attr('disabled', false);
        $('#widget-order-form input[name=model]').val(response.order.model).attr('disabled', false);
        $('#widget-order-form select[name=frameType]').val(response.order.frameType).attr('disabled', false);
        $('#widget-order-form select[name=size]').val(response.order.size).attr('disabled', false);
        $('#widget-order-form select[name=status]').val(response.order.status).attr('disabled', false);
        $('#widget-order-form input[name=mail]').val(response.order.email);
        $('#widget-order-form input[name=phone]').val(response.order.phone);
        $('#widget-order-form textarea[name=comment]').val(response.order.comment);

        if(response.order.testBoolean=="Y"){
          $('#widget-order-form input[name=testBoolean]').prop('checked', true);
          $('#widget-order-form .testAddress').removeClass("hidden");
          $('#widget-order-form input[name=testAddress]').val(response.order.testAddress);
          $('#widget-order-form .testDate').removeClass("hidden");
          $('#widget-order-form input[name=testDate]').val(response.order.testDate);
          $('#widget-order-form .testStatus').removeClass("hidden");
          $('#widget-order-form select[name=testStatus]').val(response.order.testStatus);
          $('#widget-order-form .testResult').removeClass("hidden");
          $('#widget-order-form textarea[name=testResult]').val(response.order.testResult);
        }else{
          $('#widget-order-form input[name=testBoolean]').prop('checked', false);
          $('#widget-order-form .testAddress').addClass("hidden");
          $('#widget-order-form input[name=testAddress]').val("");
          $('#widget-order-form .testDate').addClass("hidden");
          $('#widget-order-form input[name=testDate]').val("");
          $('#widget-order-form .testStatus').addClass("hidden");
          $('#widget-order-form select[name=testStatus]').val("");
          $('#widget-order-form .testResult').addClass("hidden");
          $('#widget-order-form textarea[name=testResult]').val("");
        }
        $('#widget-order-form input[name=testDate]').val(response.order.testDate);
        $('#widget-order-form input[name=testAddress]').val(response.order.testAddress);
        $('#widget-order-form input[name=deliveryAddress]').val(response.order.deliveryAddress);
        $('#widget-order-form .commandBike').attr('src', "images_bikes/"+response.order.portfolioID+".jpg?date="+Date.now());
        $('#widget-order-form textarea[name=commentsAdmin]').val(response.order.commentsAdmin);

        if(response.order.stockBikeID != null){
          $('#orderManager p[name=assignation]').html("Commande assignée au vélo "+response.order.stockBikeID);
          $('#orderManager select[name=linkOfferToBike]').parent().fadeOut()
          $('.testAssignation').fadeOut();
        }else{
          $('#orderManager p[name=assignation]').html("");
          $.ajax({
            url: 'api/bikes',
            type: 'get',
            data: {
              "action": "listBikesNotLinkedToOrder",
               catalogID: response.order.portfolioID
             },
            success: function(bikes){
              $('#orderManager select[name=linkOfferToBike]').find('option').remove();
              bikes.forEach(function(bike){
                $('#orderManager select[name=linkOfferToBike]').append('<option value="'+bike.ID+'">'+bike.ID+' - '+bike.CONTRACT_TYPE+' - '+bike.COMPANY+' - '+bike.BRAND+' '+bike.MODEL+'</option>');
              })
              $('#orderManager select[name=linkOfferToBike]').val('');
            }
          })
          $('#orderManager select[name=linkOfferToBike]').parent().fadeIn()
          $('.testAssignation').fadeIn();
        }

        if(response.order.accessories){
          response.order.accessories.forEach(function (accessory, i) {
            if(accessory.TYPE=="achat"){
              var currency = "€";
            }else if(accessory.TYPE='leasing'){
              var currency = "€/mois";
            }else if(accessory.TYPE='annualleasing'){
              var currency = "€/an";
            }
            var temp =
            '<tr><td scope="row" name="categoryAcc" id="categoryAcc">' +
            accessory.CATEGORY +
            '</td><td name="accessoryAcc" id="accessoryAcc">' +
            accessory.MODEL +
            "</td><td>" +
            accessory.TYPE +
            "</td><td>" +
            accessory.BUYING_PRICE +
            " €</td><td>" +
            accessory.PRICE_HTVA + " "+currency+
            "</td></tr>";
            $('#orderAccessories tbody').append(temp);
          })
        }

        $(".deleteAccessory").click(function (){
          $.ajax({
            url: 'apis/Kameo/orders_management.php',
            type: 'get',
            data: {"action": "delete", "ID": this.name, "email": email, },
            success: function(response){
              if(response.response == 'success'){
                $.notify(
                {
                  message: response.message,
                },
                {
                  type: "success",
                }
                );
                retrieve_command(ID);
                //document.getElementById("ExistingAccessory").reset();
              }else {
                $.notify(
                {
                  message: response.message,
                },
                {
                  type: "danger",
                }
                );
              }
            }
          });
        });
      }
    }
  });
}

$('.ordersManagerClick').off();

$(".ordersManagerClick").click(function () {

  $("#companiesOrderable").dataTable({
    destroy: true,
    ajax: {
      url: "apis/Kameo/companies/companies.php",
      contentType: "application/json",
      type: "GET",
      data: {
        action: "listCafetariaCompanies",
      },
    },
    sAjaxDataProp: "",
    columnDefs: [{ width: "100%", targets: 0 }],
    columns: [
      {
        title: "Name",
        data: "COMPANY_NAME",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          var cafetaria = oData.CAFETARIA === "Y" ? true : false;
          var discount = oData.DISCOUNT;
          var tva = oData.TVA_INCLUDED === "Y" ? true : false;
          var type = oData.CAFETERIA_TYPE;
          var types = oData.CAFETERIA_TYPES;
          $(nTd).html(
            "<a href='#' data-target='#manageOrderable' data-toggle='modal' data-cafetaria='" +
              cafetaria +
              "'data-discount='" +
              discount +
              "'data-tva='" +
              tva +
              "'data-types='" +
              types +
              "'data-type='" +
              type +
              "' data-company='" +
              sData +
              "'>" +
              sData +
              "</a>"
          );
        },
      },
      { title: "Bikes", data: "NUM_OF_ORDERABLE" },
      { title: "Cafetaria", data: "CAFETARIA" },
    ],
    order: [
      [2, "desc"],
      [0, "asc"],
    ],
  });
});


$('.ordersManagerClick').click(function(){
  get_orders_listing()});
$('.ordersManagerClick').click(function(){

  //Accessoires
  get_all_accessories().done(function(response){
    //gestion du moins au lancement de la page
    checkMinus('.orderAccessories','.accessoriesNumber');
    //variables
    var accessoriesOrder = response.accessories;
    if(accessoriesOrder == undefined){
      accessoriesOrder =[];
      console.log('accessories => table vide');
    }
    var categories = [];

    //generation du tableau de catégories
    accessoriesOrder.forEach((accessory) => {
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

    $('.orderAccessories .glyphicon-plus').click(function(){
      //gestion accessoriesNumber
      accessoriesOrderNumber = $("#orderManager").find('.accessoriesNumber').html()*1+1;
      $('#orderManager').find('.accessoriesNumber').html(accessoriesOrderNumber);
      $('#accessoriesNumber').val(accessoriesOrderNumber);

      //ajout des options du select pour les catégories
      var categoriesOption = "<option hidden disabled selected value></option>";
      categories.forEach((category) => {
        categoriesOption += '<option value="'+category.id+'">'+category.name+'</option>';
      });

      //ajout d'une ligne au tableau des accessoires
      $('#orderManager').find('#ExistingAccessory tbody')
      .append(`<tr class="otherCostsAccesoiresTable`+(accessoriesOrderNumber)+` accessoriesRow form-group">
        <td class="aCategory"></td><td class="aAccessory"></td><td class="aType"></td>
        <td class="aBuyingPrice"></td><td class="aPriceHTVA"></td>
        </tr>`);
      //select catégorie
      $('#orderManager').find('.otherCostsAccesoiresTable'+(accessoriesOrderNumber)+'>.aCategory')
      .append('<select name="accessoryCategory[]" id="selectCategory'+accessoriesOrderNumber+`" class="selectCategory form-control required">`+
        categoriesOption+`
        </select>`);
      //select Accessoire
      $('#orderManager').find('.otherCostsAccesoiresTable'+(accessoriesOrderNumber)+'>.aAccessory')
      .append('<select name="accessoryAccessory[]" id="selectAccessory'+
        accessoriesOrderNumber+
        '"class="selectAccessory form-control required"></select>');
      $('#orderManager').find('.otherCostsAccesoiresTable'+(accessoriesOrderNumber)+'>.aType').append('<select name="financialTypeAccessory[]" class="selectType form-control required"><option value="achat">Achat</option><option value="leasing">Leasing</option></select>');
      $('#orderManager').find('.otherCostsAccesoiresTable'+(accessoriesOrderNumber)+'>.aBuyingPrice').append('<div class="input-group"><span class="input-group-addon">€</span><input type="number" min="0" name="buyingPriceAcc[]" class="buyingPriceInput form-control required"></div>');
      $('#orderManager').find('.otherCostsAccesoiresTable'+(accessoriesOrderNumber)+'>.aPriceHTVA').append('<div class="input-group"><span class="input-group-addon">€</span><input type="number" min="0" name="sellingPriceAcc[]" class="sellingPriceInput form-control required"></div>');
      checkMinus('.orderAccessories','.accessoriesNumber');

      //on change de la catégorie
      $('.orderAccessories').find('.selectCategory').on("change",function(){
        var that = '#' + $(this).attr('id');
        var categoryId =$(that).val();
        var accessoriesOrderOption = "<option hidden disabled selected value>Veuillez choisir un accesoire</option>";

        //ne garde que les accessoires de cette catégorie
        accessoriesOrder.forEach((accessory) => {
          if (categoryId == accessory.categoryId) {
            accessoriesOrderOption += '<option value="'+accessory.id+'">'+accessory.model+'</option>';
          }
        });
        //place les accessoires dans le select
        $(that).parents('tr').find('.selectAccessory').html(accessoriesOrderOption);
      });

      $('.orderAccessories').find('.selectAccessory').on("change",function(){
        var that = '#' + $(this).attr('id');
        var accessoryId =$(that).val();

        //récupère le bon index même si le tableau est désordonné
        accessoryId = getIndex(accessoriesOrder, accessoryId);

        var buyingPrice = accessoriesOrder[accessoryId].buyingPrice;
        var priceHTVA = accessoriesOrder[accessoryId].priceHTVA ;
        $(that).parents('tr').find('.aBuyingPrice input').val(buyingPrice);

        if($(that).parents('tr').find('.aType select').val()=="leasing"){
          $(that).parents('tr').find('.aPriceHTVA input').val(priceHTVA/36);
        }else{
          $(that).parents('tr').find('.aPriceHTVA input').val(priceHTVA);
        }

        //affichage des champs buying price et selling price

      });

      $('.selectType').off();
      $('.selectType').on("change",function(){
        var type =$(this).val();
        var accessoryId = $(this).parents('tr').find('.aAccessory select').val();
        accessoryId = getIndex(accessoriesOrder, accessoryId);
        var buyingPrice = accessoriesOrder[accessoryId].buyingPrice;
        var priceHTVA = accessoriesOrder[accessoryId].priceHTVA ;

        if(type=='leasing'){
          $(this).parents('tr').find('.aPriceHTVA .input-group-addon').html("€/mois");
          $(this).parents('tr').find('.aPriceHTVA input').val(priceHTVA/36);
        }else{
          $(this).parents('tr').find('.aPriceHTVA .input-group-addon').html("€");
          $(this).parents('tr').find('.aPriceHTVA input').val(priceHTVA);
        }
      });
    });
  });
});

//retrait
$('.orderAccessories .glyphicon-minus')[0].addEventListener("click",function(){
  accessoriesOrderNumber = $("#orderManager").find('.accessoriesNumber').html();
  if(accessoriesOrderNumber > 0){
    $('#orderManager').find('.accessoriesNumber').html(accessoriesOrderNumber*1 - 1);
    $('#accessoriesNumber').val(accessoriesOrderNumber*1 - 1);
    $('#orderManager').find('.otherCostsAccesoiresTable'+accessoriesOrderNumber).slideUp().remove();
    accessoriesOrderNumber--;
  }
  checkMinus('.orderAccessories','.accessoriesNumber');
});



$('body').on('change', '#widget-order-form input[name=testBoolean]',function(){
  if($('#widget-order-form input[name=testBoolean]').is(':checked')){
    $('#widget-order-form .testAddress').removeClass("hidden");
    $('#widget-order-form .testDate').removeClass("hidden");
    $('#widget-order-form .testStatus').removeClass("hidden");
    $('#widget-order-form .testResult').removeClass("hidden");
  }else{
    $('#widget-order-form .testAddress').addClass("hidden");
    $('#widget-order-form .testDate').addClass("hidden");
    $('#widget-order-form .testStatus').addClass("hidden");
    $('#widget-order-form .testResult').addClass("hidden");
  }
});
