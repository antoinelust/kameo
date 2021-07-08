$(".fleetmanager").click(function () {

  $(".bikeManagerClick").click(function(){
    list_bikes_admin();
  });


  $('#widget-bikeManagement-form select[name=billingType]').change(function(){
    if($('#widget-bikeManagement-form select[name=billingType]').val()=="paid"){
      $('.billingPriceDiv').fadeOut("slow");
      $('.billingGroupDiv').fadeOut("slow");
      $('.billingDiv').fadeOut("slow");
    }else{
      $('.billingPriceDiv').fadeIn("slow");
      $('.billingGroupDiv').fadeIn("slow");
      $('.billingDiv').fadeIn("slow");
      if($('#widget-bikeManagement-form select[name=billingType]').val()=="annual"){
        $('#widget-bikeManagement-form .billingPriceDiv .input-group-addon').html('€/an');
      }else{
        $('#widget-bikeManagement-form .billingPriceDiv .input-group-addon').html('€/mois');
      }
    }
  });

  $('#widget-bikeManagement-form select[name=contractType]').change(function(){
    updateDisplayBikeManagement($('#widget-bikeManagement-form select[name=contractType]').val());
  });
  $('#widget-bikeManagement-form select[name=company]').change(function(){
    if($('#widget-bikeManagement-form select[name=company]').val()=="KAMEO"){
      $('#widget-bikeManagement-form input[name=frameNumber]').removeClass("required");
    }else{
      $('#widget-bikeManagement-form input[name=frameNumber]').addClass("required");
    }
  });
});

function getListOfBikesBill(factureID){
  getLinkBikesBillsDetails(factureID).done(function(response){
    $("#bikesNotLinkedSelection").html("");
    $('#linkBikesToBillsManagement input[name=ID]').val(response.billingDetails.ID);
    $('#linkBikesToBillsManagement input[name=supplier]').val(response.billingDetails.BENEFICIARY_COMPANY);
    $('#linkBikesToBillsManagement input[name=date]').val(response.billingDetails.DATE.substring(0,10));
    $('#linkBikesToBillsManagement input[name=communication]').val(response.billingDetails.COMMUNICATION_STRUCTUREE);
    $("#linkBikesToBillsManagement .PDFFile").attr(
      "data",
      "factures/" + response.billingDetails.FILE_NAME
    );


    $('#linkBikesToBillsManagement select[name=bikesNotLinked]').find('option').remove().end();
    var i=0;
    $('#linkBikesToBillsManagement .bikeNumberTable tbody').html("");
    if(response.catalogDetails != null){
      response.catalogDetails.forEach(function(bike){
        $('#linkBikesToBillsManagement .bikeNumberTable').append('<tr><td>'+(i+1)+'</td><td>'+bike.BRAND+'</td><td>'+bike.MODEL+'</td><td>'+bike.catalogID+'</td><td>'+bike.SIZE+'</td><td>'+bike.BUYING_PRICE+'</td><td>'+bike.PRICE_HTVA+'</td><td>'+(bike.BIKE_ID == null ? '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>' : bike.BIKE_ID)+'</td></tr>');
        i++;
      });
      $('#linkBikesToBillsManagement .bikesNumber').html(i);
    }

    if(response.notLinkedBikes != null){
      response.notLinkedBikes.forEach(function(bike){
        $('#linkBikesToBillsManagement select[name=bikesNotLinked]').append("<option value='"+bike.ID+"' data-catalogid='"+bike.CATALOG_ID+"'>"+bike.BRAND+" - "+bike.MODEL+" - "+bike.FRAME_TYPE+" - "+bike.SEASON+"</option>");
        i++;
      });
      $('#linkBikesToBillsManagement select[name=bikesNotLinked]').val("");
    }
  })

}

$('#externalBikeManagement').on('shown.bs.modal', function(event){
  var companyID = $(event.relatedTarget).data("idCompany");
  $('#externalBikeManagement select[name=company]').val(companyID);
});


function fill_link_bikes_to_bills(){
  $("#linkBikesToBills").dataTable({
    destroy: true,
    ajax: {
      url: "api/bikes",
      contentType: "application/json",
      type: "GET",
      data: {
        action: "listBikeBills",
      },
    },
    sAjaxDataProp: "",
    columns: [
      {
        title: "",
        data: "ID",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            "<a href='#' data-target='#linkBikesToBillsManagement' class='text-green' data-correspondent='"+sData+"' data-toggle='modal'>"+sData+"</a>"
          );
        },
      },
      {
        title: "Date",
        data: "DATE",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            (sData == null) ? 'N/A' : sData.shortDate()
          );
        },
      },
      { title: "Fournisseur", data: "BENEFICIARY_COMPANY" },
      {
        title: "Montant total facture",
        data: "AMOUNT_HTVA",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            -sData+" €"
          );
        },
      },
      { title: "Nombre de vélos facture", data: "countBikesCatalog" },
      {
        title: "Montant des vélos facture",
        data: "sumBikesCatalog",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            (sData == null) ? "0 €" : Math.round(sData)+" €"
          );
        },
      },
      {
        title: "Nombre vélos réel",
        data: "countBikes",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            (sData == oData.countBikesCatalog) ? '<i class="fa fa-check" style="color:green" aria-hidden="true"></i> '+sData : '<i class="fa fa-close" style="color:red" aria-hidden="true"></i> '+sData
          );
        },
      },
      {
        title: "Montant des vélos réel",
        data: "sumBikes",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            (sData == null) ? "0 €" : Math.round(sData)+" €"
          );
        },
      },
      {
        title: "",
        data: "ID",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            "<a href='#' data-target='#linkBikesToBillsManagement' class='text-green' data-correspondent='"+sData+"' data-toggle='modal'>Update</a>"
          );
        },
      },
    ],
    order: [
      [0, "desc"]
    ],
    paging : false
  });

  $("#bikesNotLinkedTable").dataTable({
    destroy: true,
    ajax: {
      url: "api/bikes",
      contentType: "application/json",
      type: "GET",
      data: {
        action: "listOfBikesNotLinked",
      },
    },
    sAjaxDataProp: "",
    columns: [
      { title: "ID", data: "ID" },
      { title: "Frame number", data: "FRAME_NUMBER"},
      { title: "Marque", data: "BRAND"},
      { title: "Modèle", data: "MODEL"},
      { title: "Société", data: "COMPANY" },
      { title: "Type de contrat", data: "CONTRACT_TYPE"},
      { title: "Début du contrat", data: "CONTRACT_START"},
      { title: "Fin du contrat", data: "CONTRACT_END"}
    ],
    order: [
      [0, "asc"]
    ],
    paging : false
  });
}

$('#linkBikesToBillsManagement').on('hidden.bs.modal', function () {
  $("#linkBikesToBills").dataTable().api().ajax.reload();
  $("#bikesNotLinkedTable").dataTable().api().ajax.reload();
})

$('#linkBikesToBillsManagement').on('shown.bs.modal', function(event){
  var factureID = $(event.relatedTarget).data("correspondent");
  $('#linkBikesToBillsManagement tbody').html("");

  $("#summaryBikesLinked").dataTable({
    destroy: true,
    scrollX: true,
    ajax: {
      url: "api/bikes",
      contentType: "application/json",
      type: "GET",
      data: {
        action: "summaryBikesLinked",
        factureID: factureID
      }
    },
    sAjaxDataProp: "",
    columns: [
      { title: "ID", data: "ID" },
      { title: "Société", data: "COMPANY" },
      { title: "Taille", data: "SIZE" },
      { title: "Type de contrat", data: "CONTRACT_TYPE" },
      { title: "Début de contrat", data: "CONTRACT_START" },
      { title: "Fin de contrat", data: "CONTRACT_END" },
      {
        title: "Date d'achat",
        data: "BIKE_BUYING_DATE",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            (sData == null) ? 'N/A' : sData.shortDate()
          );
        },
      },
      {
        title: "Prix d'achat facture",
        data: "BUYING_PRICE",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            sData+' €'
          );
        },
      },
      {
        title: "Prix d'acchat ERP",
        data: "BIKE_PRICE",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            sData+' €'
          );
        },
      },
      {
        title: "Date de livraison",
        data: "DELIVERY_DATE",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            (sData == null) ? 'N/A' : sData.shortDate()
          );
        },
      },
    ],
    order: [
      [0, "asc"]
    ],
    paging : false,
    searching : false
  });



  $("#widget-bikesNotLinked-form select[name=bikesNotLinked]").find("option")
  .remove()
  .end();
  getListOfBikesBill(factureID);
});

$('#linkBikesToBillsManagement select[name=bikesNotLinked]').change(function(){
  var catalogID=$(this).children("option:selected").data('catalogid');
  $("#bikesNotLinkedSelection").dataTable({
    destroy: true,
    scrollX: true,
    ajax: {
      url: "api/bills",
      contentType: "application/json",
      type: "GET",
      data: {
        action: "listBikesNotLinkedToBill",
        catalogID: catalogID
      }
    },
    sAjaxDataProp: "",
    columns: [
      { title: "ID", data: "ID" },
      { title: "Identification", data: "MODEL" },
      { title: "Société", data: "COMPANY" },
      { title: "Taille", data: "SIZE" },
      { title: "Type de contrat", data: "CONTRACT_TYPE" },
      { title: "Début de contrat", data: "CONTRACT_START" },
      { title: "Fin de contrat", data: "CONTRACT_END" },
      {
        title: "Date d'achat",
        data: "BIKE_BUYING_DATE",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            (sData == null) ? 'N/A' : sData.shortDate()
          );
        },
      },
      {
        title: "Date de livraison",
        data: "DELIVERY_DATE",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            (sData == null) ? 'N/A' : sData.shortDate()
          );
        },
      },
      {
        title: "Numéro de commande",
        data: "ORDER_NUMBER",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(
            (sData == null || sData=='') ? 'N/A' : sData.shortDate()
          );
        },
      },
      {
        title: "",
        data: "ID",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html("<a class='button small green button-3d rounded icon-right linkBikeToBill' data-correspondent='"+sData+"'>Assigner</a>");
        },
      }
    ],
    order: [
      [0, "asc"]
    ],
    paging : false,
    searching : false
  });

  $('#bikesNotLinkedSelection').on( 'draw.dt', function () {
    $('#bikesNotLinkedSelection .linkBikeToBill').off();
    $('#bikesNotLinkedSelection .linkBikeToBill').click(function(){
      $.ajax({
        url: 'api/bills.php',
        type: 'post',
        data: { "action": "linkBikeToBill", "ID": $('#linkBikesToBillsManagement select[name=bikesNotLinked]').val(), "bikeID" : $(this).data('correspondent')},
        success: function(response){
          $.notify(
            {
              message: response.message,
            },
            {
              type: response.response,
            }
          );
          getListOfBikesBill($('#linkBikesToBillsManagement input[name=ID]').val());
          $("#bikesNotLinkedSelection").html("");
        }
      });
    });
  })
});


function getLinkBikesBillsDetails(id){
  return $.ajax({
    url: 'api/bills.php',
    type: 'get',
    data: { "billingID": id, "action" : "getLinkBikesBillsDetails"},
    success: function(response){
    }
  });
}

function updateDisplayBikeManagement(type){


  $('#widget-bikeManagement-form input[name=bikeID]').attr('readonly', true);
  if(type=="selling" || type=='stolen'){
    $('#widget-bikeManagement-form input[name=address]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=frameReference]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=gpsID]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form select[name=localisation]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=deliveryDate]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=plateNumber]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=bikeKeyReference]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=lockerReference]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=frameNumber]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=model]').closest("div").fadeIn("slow");
    $('.buyingInfos').fadeIn("slow");
    $('.orderInfos').fadeIn("slow");
    $('#widget-bikeManagement-form input[name=estimatedDeliveryDate]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=deliveryDate]').closest("div").fadeIn("slow");


    $('.contractInfos').fadeIn("slow");
    if(type=='selling'){
      $('#widget-bikeManagement-form label[for=contractStart]').html("Date de vente");
      $('#widget-bikeManagement-form label[for=bikeSoldPrice]').html("Prix de vente");
    }else{
      $('#widget-bikeManagement-form label[for=contractStart]').html("Date de vol");
      $('#widget-bikeManagement-form label[for=bikeSoldPrice]').html("Montant remboursé par assurance");
    }
    $('#widget-bikeManagement-form .contractEndBloc').fadeOut("slow");

    $('.billingInfos').fadeIn("slow");
    $('.billingPriceDiv').fadeOut("slow");
    $('#widget-bikeManagement-form select[name=billingType]').val("paid");
    $('#widget-bikeManagement-form select[name=billingType]').attr('readonly', true);
    $('.billingGroupDiv').fadeOut("slow");
    $('.billingDiv').fadeOut("slow");


    $('#widget-bikeManagement-form .soldPrice').fadeIn();
    $('#widget-bikeManagement-form .soldPrice input').removeAttr("disabled");

  }else if(type=="stock"){

    $('#widget-bikeManagement-form input[name=frameReference]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=gpsID]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form select[name=localisation]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=address]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=deliveryDate]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=plateNumber]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=bikeKeyReference]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=lockerReference]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=frameNumber]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=model]').closest("div").fadeIn("slow");


    $('.buyingInfos').fadeIn("slow");
    $('.orderInfos').fadeIn("slow");
    $('#widget-bikeManagement-form input[name=estimatedDeliveryDate]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=deliveryDate]').closest("div").fadeIn("slow");

    $('.contractInfos').fadeOut("slow");

    $('.billingInfos').fadeOut("slow");
    $('#widget-bikeManagement-form select[name=billingType]').val("paid");
    $('.billingPriceDiv').fadeOut("slow");
    $('.billingGroupDiv').fadeOut("slow");
    $('.billingDiv').fadeOut("slow");

    $('#widget-bikeManagement-form .soldPrice').fadeOut("slow")
    $('#widget-bikeManagement-form .soldPrice input').attr("disabled");

  }else if(type=="order"){
    $('#widget-bikeManagement-form input[name=frameReference]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=gpsID]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form select[name=localisation]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=address]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=deliveryDate]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=plateNumber]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=bikeKeyReference]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=lockerReference]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=frameNumber]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=model]').closest("div").fadeOut("slow");


    $('.buyingInfos').fadeIn("slow");
    $('.orderInfos').fadeIn("slow");
    $('#widget-bikeManagement-form input[name=estimatedDeliveryDate]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=deliveryDate]').closest("div").fadeOut("slow");

    $('.contractInfos').fadeOut("slow");

    $('.billingInfos').fadeOut("slow");
    $('#widget-bikeManagement-form select[name=billingType]').val("paid");
    $('.billingPriceDiv').fadeOut("slow");
    $('.billingGroupDiv').fadeOut("slow");
    $('.billingDiv').fadeOut("slow");

    $('#widget-bikeManagement-form .soldPrice').fadeOut("slow")
    $('#widget-bikeManagement-form .soldPrice input').attr("disabled");
  }else if(type=="test"){
    $('#widget-bikeManagement-form input[name=frameReference]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form select[name=localisation]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=gpsID]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=address]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=deliveryDate]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=plateNumber]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=bikeKeyReference]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=lockerReference]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=frameNumber]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=model]').closest("div").fadeIn("slow");


    $('.buyingInfos').fadeOut("slow")
    $('.orderInfos').fadeOut("slow");

    $('.contractInfos').fadeOut("slow");

    $('.billingInfos').fadeOut("slow");
    $('#widget-bikeManagement-form select[name=billingType]').val("paid");
    $('#widget-bikeManagement-form .soldPrice input').removeAttr("disabled");
    $('.billingPriceDiv').fadeOut("slow");
    $('.billingGroupDiv').fadeOut("slow");
    $('.billingDiv').fadeOut("slow");

    $('#widget-bikeManagement-form .soldPrice').fadeOut("slow")
    $('#widget-bikeManagement-form .soldPrice input').attr("disabled");
  }else{
    $('#widget-bikeManagement-form input[name=frameReference]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=gpsID]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form select[name=localisation]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=address]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=deliveryDate]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=plateNumber]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=bikeKeyReference]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=lockerReference]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=frameNumber]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=model]').closest("div").fadeIn("slow");
    $('#widget-bikeManagement-form input[name=insurance]').prop('checked', false);

    $('.buyingInfos').fadeIn("slow");
    $('.orderInfos').fadeIn("slow");
    $('#widget-bikeManagement-form input[name=estimatedDeliveryDate]').closest("div").fadeOut("slow");
    $('#widget-bikeManagement-form input[name=deliveryDate]').closest("div").fadeIn("slow");

    $('.contractInfos').fadeIn("slow");
    $('#widget-bikeManagement-form label[for=contractStart]').html("Date de début de contrat");
    $('#widget-bikeManagement-form .contractEndBloc').fadeIn("slow");

    $('.billingInfos').fadeIn("slow");
    $('#widget-bikeManagement-form select[name=billingType]').val("monthly");
    $('.billingPriceDiv').fadeIn("slow");
    $('.billingGroupDiv').fadeIn("slow");
    $('.billingDiv').fadeIn("slow");

    $('#widget-bikeManagement-form .soldPrice').fadeOut("slow")
    $('#widget-bikeManagement-form .soldPrice input').attr("disabled");

  }
}

function add_bike(ID){
  var company;
  $('#widget-bikeManagement-form select[name=name]').val("");
  $('#widget-bikeManagement-form input[name=email]').val("");
  $('#widget-bikeManagement-form input[name=bikeID]').fadeOut();
  $('#widget-bikeManagement-form label[for=bikeID]').fadeOut();


  updateDisplayBikeManagement("order");

  $('#widget-bikeManagement-form select[name=contractType')
  .find('option')
  .remove()
  .end()
  ;
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"order\">Commande</option>");
  $('.bikeManagementPicture').addClass('hidden');
  $('.bikeActions').addClass('hidden');
  document.getElementById('widget-bikeManagement-form').reset();

  $('#widget-bikeManagement-form input[name=action]').val("add");
  $('#widget-bikeManagement-form select[name=billingType]').val("paid");
  if($("#widget-bikeManagement-form select[name=portfolioID] option").length==0){
    $.ajax({
      url: 'apis/Kameo/load_portfolio.php',
      type: 'get',
      data: {"action": "addBike"},
      success: function(response){
        if (response.response == 'error') {
          console.log(response.message);
        } else{
          var i=0;
          while(i<response.bikeNumber){
            $('#widget-bikeManagement-form select[name=portfolioID]').append("<option value="+response.bike[i].ID+">"+response.bike[i].brand+" - "+response.bike[i].model+" - "+response.bike[i].frameType+' - '+response.bike[i].season+' - ID catalogue :'+response.bike[i].ID+'</option>');
            i++;
          }
          $('#widget-bikeManagement-form select[name=portfolioID]').val("");
        }
      }
    });
  }else{
    $('#widget-bikeManagement-form select[name=portfolioID]').val("");
  }
  $('#widget-bikeManagement-form select[name=company]').val("");

  $('#widget-bikeManagement-form select[name=bikeType]').off();
  $('#widget-bikeManagement-form select[name=company]').off();
  $('#widget-bikeManagement-form select[name=bikeType], #widget-bikeManagement-form select[name=company]').change(function(bikeType){
    if($('#widget-bikeManagement-form select[name=bikeType]').val()=='personnel' && $('#widget-bikeManagement-form select[name=company]').val() != null){
      $('#widget-bikeManagement-form select[name=name]').closest("div").fadeIn();
      $.ajax({
        url: 'api/companies',
        type: 'get',
        data: {action: "listUsers", company: $('#widget-bikeManagement-form select[name=company]').val()},
        success: function(response){
          $('#widget-bikeManagement-form select[name=name]')
          .find('option')
          .remove()
          .end()
          ;
          if(response == null){
            $.notify(
              {
                message: "Aucun utilisateur défini pour cette société, impossible de l'assigner à quelqu'un"
              },
              {
                type: "info",
              }
            );
            $('#widget-bikeManagement-form select[name=name]').closest("div").fadeOut();
            $('#widget-bikeManagement-form select[name=bikeType]').val('partage');
          }else{
            $('#widget-bikeManagement-form select[name=name]').closest("div").fadeIn();
            response.forEach(function(user){
              $('#widget-bikeManagement-form select[name=name]').append("<option value="+user.EMAIL+">"+user.PRENOM+" "+user.NOM+"<br>");
            });
            $('#widget-bikeManagement-form select[name=name]').val("");
          }
        }
      })
    }else{
      $('#widget-bikeManagement-form select[name=name]').closest('div').fadeOut();
    }
  })
}

$('#widget-bikeManagement-form select[name=portfolioID]').off();
$('#widget-bikeManagement-form select[name=portfolioID]').change(function(){
  $.ajax({
    url: 'apis/Kameo/load_portfolio.php',
    type: 'get',
    data: {"ID": $('#widget-bikeManagement-form select[name=portfolioID]').val(), "action": "retrieve"},
    success: function(response){
      if (response.response == 'error') {
        console.log(response.message);
      } else{
        $('#bikeManagementPicture').attr('src', "images_bikes/"+response.ID+"_mini.jpg?date="+Date.now());
        $('.bikeManagementPicture').removeClass('hidden');
        $('#widget-bikeManagement-form input[name=price]').val(response.buyingPrice);
        $('#widget-bikeManagement-form input[name=model]').val(response.model);
        $('#bikeManagementPicture').attr('src', "images_bikes/"+response.ID+"_mini.jpg?date="+Date.now());
        $('.bikeManagementPicture').removeClass('hidden');

        $('#widget-bikeManagement-form select[name=size]')
        .find('option')
        .remove()
        .end()
        ;
        var sizes = response.sizes.split(',');
        sizes.forEach(size => $('#widget-bikeManagement-form select[name=size]').append('<option value="'+size+'">'+size+'</option>'));
        $('#widget-bikeManagement-form select[name=size]').val("");
      }
    }
  })
});

function construct_form_for_bike_status_updateAdmin(bikeID){

  document.getElementById('widget-bikeManagement-form').reset();
  $('#widget-bikeManagement-form input[name=bikeID]').fadeIn();
  $('#widget-bikeManagement-form label[for=bikeID]').fadeIn();


  $('#widget-bikeManagement-form select[name=contractType')
  .find('option')
  .remove()
  .end()
  ;
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"leasing\">Location LT</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"renting\">Location CT</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"selling\">Vélo vendu</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"stock\">Vélo de stock</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"order\">Commande fournisseur</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"pending_delivery\">Attente Livraison Client</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"stolen\">Volé</option>");

  var company;
  var frameNumber=frameNumber;

  $('#widget-addActionBike-form input[name=bikeNumber]').val(frameNumber);
  $('.bikeActions').removeClass('hidden');
  $('#widget-bikeManagement-form input[name=action]').val("update");
  $('#widget-bikeManagement-form select[name=portfolioID]').find('option').remove().end();

  $.ajax({
    url: 'apis/Kameo/load_portfolio.php',
    type: 'get',
    data: {"action": "addBike"},
    success: function(response){
      if (response.response == 'error') {
        console.log(response.message);
      } else{
        var i=0;
        while(i<response.bikeNumber){
          $('#widget-bikeManagement-form select[name=portfolioID]').append("<option value="+response.bike[i].ID+">"+response.bike[i].brand+" - "+response.bike[i].model+" - "+response.bike[i].frameType+"<br>");
          i++;
        }
      }
    }
  }).done(function(){

    document.getElementById('bikeBuildingAccessAdmin').innerHTML = "";
    document.getElementById('bikeUserAccessAdmin').innerHTML = "";
    var id;
    $.ajax({
      url: 'api/bikes',
      type: 'get',
      data: { action: "retrieve", "bikeID": bikeID},
      success: function(response){
        if (response.response == 'error') {
          console.log(response.message);
        } else{
          $('#widget-bikeManagement-form select[name=bikeType]').off();
          $('#widget-bikeManagement-form select[name=company]').off();
          $('#widget-bikeManagement-form select[name=bikeType], #widget-bikeManagement-form select[name=company]').change(function(){
            if($('#widget-bikeManagement-form select[name=bikeType]').val()=='personnel' && $('#widget-bikeManagement-form select[name=company]').val() != null){
              $('#widget-bikeManagement-form select[name=name]').closest("div").fadeIn();
              $('#bikeBuildingAccessAdminDiv').addClass("hidden");
              $('#bikeBuildingAccessAdmin').addClass("hidden");
              $('#bikeUserAccessAdminDiv').addClass("hidden");
              $('#bikeUserAccessAdmin').addClass("hidden");
              update_users_list($('#widget-bikeManagement-form select[name=company]').val(), response.bikeOwner);
            }else{
              $('#bikeBuildingAccessAdminDiv').removeClass("hidden");
              $('#bikeBuildingAccessAdmin').removeClass("hidden");
              $('#bikeUserAccessAdminDiv').removeClass("hidden");
              $('#bikeUserAccessAdmin').removeClass("hidden");

              $('#widget-bikeManagement-form select[name=name]').closest("div").fadeOut();
              $('#widget-bikeManagement-form select[name=name]').val("");
            }
          })

          document.getElementById("bikeManagementPicture").src="images_bikes/"+response.img+"_mini.jpg?date="+Date.now();
          $('.bikeManagementPicture').removeClass('hidden');
          id=response.id;
          company=response.company;
          $('#widget-bikeManagement-form input[name=bikeID]').val(bikeID);
          $('#widget-bikeManagement-form input[name=frameNumber]').val(response.frameNumber);
          $('#widget-deleteBike-form input[name=bikeID]').val(bikeID);
          $('#widget-bikeManagement-form input[name=frameNumberOriginel]').val(response.frameNumber);
          $('#widget-bikeManagement-form input[name=model]').val(response.model);
          $('#widget-bikeManagement-form input[name=color]').val(response.color);
          $('#widget-bikeManagement-form input[name=frameReference]').val(response.frameReference);
          $('#widget-bikeManagement-form input[name=plateNumber]').val(response.plateNumber);
          $('#widget-bikeManagement-form input[name=bikeKeyReference]').val(response.bikeKeyReference);
          $('#widget-bikeManagement-form input[name=lockerReference]').val(response.lockerReference);
          $('#widget-bikeManagement-form input[name=gpsID]').val(response.gpsID);
          $('#widget-bikeManagement-form select[name=localisation]').val(response.localisation);
          $('#widget-bikeManagement-form input[name=price]').val(response.bikePrice);
          $('#widget-bikeManagement-form input[name=insuranceCivilResponsibilityContract]').val(response.insuranceCivilResponsibilityContract);


          if(response.billingType=="monthly"){
            $('#widget-bikeManagement-form .billingPriceDiv .input-group-addon').html('€/mois');
          }else if(response.billingType=="annual"){
            $('#widget-bikeManagement-form .billingPriceDiv .input-group-addon').html('€/an');
          }
          $('#widget-bikeManagement-form select[name=contractType]').val(response.contractType);
          $('#widget-bikeManagement-form input[name=bikeSoldPrice]').val(response.soldPrice);
          $('#widget-bikeManagement-form input[name=orderNumber]').val(response.orderNumber);
          $("#widget-bikeManagement-form select[name=bikeType]").val(response.biketype);

          $('#widget-bikeManagement-form select[name=size]').find('option').remove().end();
          if(response.possibleSizes != '' && response.possibleSizes != null){
            var sizes = response.possibleSizes.split(',');
            sizes.forEach(size => $('#widget-bikeManagement-form select[name=size]').append('<option value="'+size+'">'+size+'</option>'));
            if(sizes.includes(response.size)){
              $('#widget-bikeManagement-form select[name=size]').val(response.size);
            }else{
              $.notify(
                {
                  message: "Incompatibilité entre la taille du vélo et les tailles possible du modèle en catalogue. Taille du vélo : "+response.size,
                },
                {
                  type: "danger",
                }
              );
              $('#widget-bikeManagement-form select[name=size]').val("");
            }
          }else{
            $.notify(
              {
                message: "Ce vélo n'a aucune taille possible configurée dans le catalogue, veuillez commencer par les définir",
              },
              {
                type: "danger",
              }
            );
          }
          if(response.contractType=="selling"){
            $('#widget-bikeManagement-form input[name=contractStart]').val(response.sellingDate.substr(0,10));
          }else{
            if(response.contractStart){
              $('#widget-bikeManagement-form input[name=contractStart]').val(response.contractStart.substr(0,10));
            }else{
              $('#widget-bikeManagement-form input[name=contractStart]').val("");
            }
          }
          if(response.contractEnd){
            $('#widget-bikeManagement-form input[name=contractEnd]').val(response.contractEnd.substr(0,10));
          }else{
            $('#widget-bikeManagement-form input[name=contractEnd]').val("");
          }
          if(response.type==0){
            $('#widget-bikeManagement-form select[name=portfolioID]').val("");
          }else{
            $('#widget-bikeManagement-form select[name=portfolioID]').val(response.type);
          }
          if(response.bikeBuyingDate == null){
            $('#widget-bikeManagement-form input[name=orderingDate]').val("");
          }else{
            $('#widget-bikeManagement-form input[name=orderingDate]').val(response.bikeBuyingDate.substr(0,10));
          }

          if(response.estimatedDeliveryDate == null){
            $('#widget-bikeManagement-form input[name=estimatedDeliveryDate]').val("");
          }else{
            $('#widget-bikeManagement-form input[name=estimatedDeliveryDate]').val(response.estimatedDeliveryDate.substr(0,10));
          }
          if(response.deliveryDate == null){
            $('#widget-bikeManagement-form input[name=deliveryDate]').val("");
          }else{
            $('#widget-bikeManagement-form input[name=deliveryDate]').val(response.deliveryDate.substr(0,10));
          }
          if(response.offerID != null){
            $('#widget-bikeManagement-form select[name=offerReference]').val(response.offerID);
          }else{
            $('#widget-bikeManagement-form select[name=offerReference]').val("");
          }
          if(response.bikeOwner != null){
            update_users_list(company, response.bikeOwner);
            $('#widget-bikeManagement-form select[name=name]').closest("div").fadeIn();
          }else{
            $('#widget-bikeManagement-form select[name=name]').val("");
            $('#widget-bikeManagement-form select[name=name]').closest("div").fadeOut();
          }
          if(response.leasing=="Y"){
            $('#widget-bikeManagement-form input[name=billing]').prop("checked", true);
          }else{
            $('#widget-bikeManagement-form input[name=billing]').prop("checked", false);
          }

          if(response.insurance=="Y"){
            $('#widget-bikeManagement-form input[name=insurance]').prop("checked", true);
          }else{
            $('#widget-bikeManagement-form input[name=insurance]').prop("checked", false);
          }
          if(response.insuranceIndividual){
            $('#widget-bikeManagement-form input[name=insuranceIndividual]').prop("checked", true);
          }else{
            $('#widget-bikeManagement-form input[name=insuranceIndividual]').prop("checked", false);
          }
          if(response.insuranceCivilResponsibility){
            $('#widget-bikeManagement-form input[name=insuranceCivilResponsibility]').prop("checked", true);
            $('#widget-bikeManagement-form input[name=insuranceCivilResponsibilityContract]').parent().removeClass("hidden");
            $('#widget-bikeManagement-form input[name=insuranceCivilResponsibilityContract]').addClass("required");
          }else{
            $('#widget-bikeManagement-form input[name=insuranceCivilResponsibility]').prop("checked", false);
            $('#widget-bikeManagement-form input[name=insuranceCivilResponsibilityContract]').parent().addClass("hidden");
            $('#widget-bikeManagement-form input[name=insuranceCivilResponsibilityContract]').removeClass("required");
          }
          $('#widget-bikeManagement-form input[name=billingPrice]').val(response.leasingPrice);
          $('#widget-bikeManagement-form input[name=billingGroup]').val(response.billingGroup);

          document.getElementsByClassName("bikeManagementPicture")[0].src="images_bikes/"+response.img+"_mini.jpg?date="+Date.now();

          if(response.status=="OK"){
            $('#widget-bikeManagement-form input[name=bikeStatus]').val('OK');
          }
          else{
            $('#widget-bikeManagement-form input[name=bikeStatus]').val('KO');
          }
          if (response.biketype == "partage"){
            $('#bikeUserAccessAdminDiv').removeClass('hidden');
            $('#bikeUserAccessAdmin').removeClass('hidden');
            $('#bikeBuildingAccessAdminDiv').removeClass('hidden');
            $('#bikeBuildingAccessAdmin').removeClass('hidden');
          }else{
            $('#bikeUserAccessAdminDiv').addClass('hidden');
            $('#bikeUserAccessAdmin').addClass('hidden');
            $('#bikeBuildingAccessAdmin').addClass('hidden');
          }

          $('#widget-bikeManagement-form select[name=company]').val(company);
          $('#widget-bikeManagement-form select[name=company]').change(function(){
            update_users_list($('#widget-bikeManagement-form select[name=company]').val());
          });

          updateDisplayBikeManagement(response.contractType);

          if(response.utilisation != 'Speedpedelec'){
            $('#widget-bikeManagement-form input[name=plateNumber]').closest("div").fadeOut("slow");
          }
          $('#widget-bikeManagement-form select[name=billingType]').val(response.billingType);
        }
      }
    }).done(function(response){
      $.ajax({
        url: 'apis/Kameo/action_bike_management.php',
        type: 'post',
        data: { "readActionBike-action": "read", "bikeID": bikeID, "readActionBike-user": "<?php echo $user_data['EMAIL']; ?>"},
        success: function(response){
          if (response.response == 'error') {
            console.log(response.message);
          } else{
            var i=0;
            var dest="<table class=\"table table-condensed\"><a class=\"button small green button-3d rounded icon-right addActionBikeButton\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une action</span></a><tbody><thead><tr><th>Date</th><th>Description</th><th>Public ?</th></tr></thead> ";
            while(i<response.actionNumber){
              if(response.action[i].public=="1"){
                var public="Yes";
              }else{
                var public="No";
              }
              var temp="<tr><td>"+response.action[i].date.substring(0,10)+"</td><td>"+response.action[i].description+"</td><td>"+public+"</td></tr>";
              dest=dest.concat(temp);
              i++;
            }
            dest=dest.concat("</tbody></table>");
            $('#action_bike_log').html(dest);
            $(".widget-deleteBike-form[name='bikeID']").val(bikeID);

            document.getElementsByClassName("addActionBikeButton")[0].addEventListener('click', function(){
              $("label[for='widget-addActionBike-form-date']").removeClass("hidden");
              $('input[name=widget-addActionBike-form-date]').removeClass("hidden");
              $("label[for='widget-addActionBike-form-description']").removeClass("hidden");
              $('textarea[name=widget-addActionBike-form-description]').removeClass("hidden");
              $("label[for='widget-addActionBike-form-public']").removeClass("hidden");
              $('input[name=widget-addActionBike-form-public]').removeClass("hidden");
              $('.addActionConfirmButton').removeClass("hidden");
            });

          }

        }
      })

      $.ajax({
        url: 'api/bikes',
        type: 'get',
        data: {
          "bikeID": id,
          "action" : "getListofBills"
        },
        success: function(response){
          if (response.response == 'error') {
            console.log(response.message);
          } else{
            var i=0;
            var dest="<table id=\"bills_details_listing\" class=\"table table-condensed\"  data-order='[[ 0, \"desc\" ]]'><thead><tr><th><span class=\"fr-inline\">ID</span></th><th>Date</th><th>Montant</th><th>Envoyée ?</th><th>Payée ?</th></tr></thead><tbody>";
            response.bills.forEach(function(bill){
              if(bill.FACTURE_SENT=="1"){
                sent="<span class=\"text-green\">Y</span>"
              }else{
                sent="<span class=\"text-red\">N</span>"
              }
              if(bill.FACTURE_PAID=="1"){
                paid="<span class=\"text-green\">Y</span>"
              }else{
                paid="<span class=\"text-red\">N</span>"
              }
              if(bill.direction == 'OUT'){
                var amount = bill.AMOUNT_HTVA + " €";
              }else{
                var amount = "<span class='text-red'>"+(-bill.AMOUNT_HTVA)+" €</span>";
              }
              var temp="<tr><td><a href=\"factures/"+bill.FILE_NAME+"\" target=\"_blank\">"+bill.FACTURE_ID+"</a></td><td data-sort=\""+(new Date(bill.DATE)).getTime()+"\">"+bill.DATE.shortDate()+"</td><td>"+amount+"</td><td>"+sent+"</td><td>"+paid+"</td></tr>";
              dest=dest.concat(temp);
            })
            dest=dest.concat("</tbody></table>");
            $('#bills_bike').html(dest);
            //displayLanguage();

            $('#bills_details_listing').DataTable({
              "searching": false,
              "paging": false
            });
          }
        }
      })
    })
})
}

$('#widget-bikeManagement-form input[name=insuranceCivilResponsibility]').change(function(){
  if($(this).is(":checked")){
    $('#widget-bikeManagement-form input[name=insuranceCivilResponsibilityContract]').parent().removeClass("hidden");
    $('#widget-bikeManagement-form input[name=insuranceCivilResponsibilityContract]').addClass("required");
  }else{
    $('#widget-bikeManagement-form input[name=insuranceCivilResponsibilityContract]').parent().addClass("hidden");
    $('#widget-bikeManagement-form input[name=insuranceCivilResponsibilityContract]').removeClass("required");
  }
})


function update_offer_list(company){
  $.ajax({
    url: 'api/companies',
    method: 'get',
    data: {'company' : company, 'action': 'retrieveOffers'},
    success: function(response){
      $('#widget-bikeManagement-form select[name=offerReference]')
      .find('option')
      .remove()
      .end()
      ;
      var i=0;
      while (i < response.length){
        $('#widget-bikeManagement-form select[name=offerReference]').append("<option value="+response.ID+">"+response.title+"<br>");
        i++;
      }
      if(response.length == 0){
        $('.offerReference').fadeOut();
      }else{
        $('.offerReference').fadeIn();
      }
      $('#widget-bikeManagement-form select[name=offerReference').val("");
    }
  });
}

function update_users_list(company, userEMAIL = null){
  $.ajax({
    url: 'api/companies',
    type: 'get',
    data: { action: 'listUsers', "company": company},
    success: function(response){
      $('#widget-bikeManagement-form select[name=name]')
      .find('option')
      .remove()
      .end()
      ;
      response.forEach(function(user){
        $('#widget-bikeManagement-form select[name=name]').append("<option value="+user.EMAIL+">"+user.PRENOM+" "+user.NOM+"<br>");
      })
      if(userEMAIL){
        $('#widget-bikeManagement-form select[name=name]').val(userEMAIL);
      }else{
        $('#widget-bikeManagement-form select[name=name]').val("");
      }
    }
  })
}



//Affichage des vélos vendus
$('#BikesListingAdmin .showSoldBikes').click(function(){
  var buttonContent = "Afficher les autres vélos";
  var titleContent = "Vélos: Vendus";
  var table = $('#bikesListingTable').DataTable()
  .column(4)
  .search("selling", true, false )
  .draw();
  table.column( 4 ).visible( false, true );
  table.column( 5 ).visible( true, true );
  table.column( 6 ).visible( true, true );
  table.column( 7 ).visible( true, true );
  table.column( 8 ).visible( false, true );
  table.column( 9 ).visible( true, true );
  table.column( 10 ).visible( true, true );
  table.column( 11 ).visible( true, true );
  table.column( 12 ).visible( true, true );
  table.column( 13 ).visible( false, true );
  table.column( 14 ).visible( false, true );
  table.column( 15 ).visible( false, true );
  table.column( 16 ).visible( false, true );
  table.column( 17 ).visible( false, true );

  $(table.column(5).header()).text('Date vente');
  $(table.column(6).header()).text('Fin assurance');

  switch_showed_bikes('showSoldBikes', 'hideSoldBikes', buttonContent, titleContent);

});

//Affichage des vélos volés
$('#BikesListingAdmin .showStolenBikes').click(function(){
  var buttonContent = "Afficher les autres vélos";
  var titleContent = "Vélos: volés";
  var table = $('#bikesListingTable').DataTable()
  .column(4)
  .search( "stolen", true, false )
  .draw();

  table.column( 4 ).visible( false, true );
  table.column( 5 ).visible( true, true );
  table.column( 6 ).visible( true, true );
  table.column( 7 ).visible( true, true );
  table.column( 8 ).visible( false, true );
  table.column( 9 ).visible( true, true );
  table.column( 10 ).visible( true, true );
  table.column( 11 ).visible( true, true );
  table.column( 12 ).visible( true, true );
  table.column( 13 ).visible( false, true );
  table.column( 14 ).visible( false, true );
  table.column( 15 ).visible( false, true );
  table.column( 16 ).visible( false, true );
  table.column( 17 ).visible( false, true );

  $(table.column(5).header()).text('Date de vol');
  $(table.column(6).header()).text('Fin assurance');

  switch_showed_bikes ('showStolenBikes', 'hidenStolenBikes', buttonContent, titleContent);
});



$('body').on('click','.showOrders', function(){

  var titleContent = "Vélos: Commandes";
  var table = $('#bikesListingTable').DataTable()
  .column(4)
  .search( "order", true, false )
  .draw();

  table.column( 4 ).visible( false, true );
  table.column( 5 ).visible( false, true );
  table.column( 6 ).visible( false, true );
  table.column( 7 ).visible( false, true );
  table.column( 8 ).visible( false, true );
  table.column( 9 ).visible( false, true );
  table.column( 10 ).visible( false, true );
  table.column( 11 ).visible( false, true );
  table.column( 12 ).visible( false, true );
  table.column( 13 ).visible( true, true );
  table.column( 14 ).visible( true, true );
  table.column( 15 ).visible( true, true );
  table.column( 16 ).visible( true, true );
  table.column( 17 ).visible( true, true );
  $(table.column(5).header()).text('Date de commande');
  $(table.column(6).header()).text('Date d\'arrivée');
  table.draw();

});

//Affichage des autres vélos
$('.displayAllBikes').click(function(){
  var table = $('#bikesListingTable').DataTable()
  .column(4)
  .search( "")
  .draw();
  table.column( 4 ).visible( true, true );
  table.column( 5 ).visible( true, true );
  table.column( 6 ).visible( true, true );
  table.column( 7 ).visible( true, true );
  table.column( 8 ).visible( true, true );
  table.column( 9 ).visible( true, true );
  table.column( 10 ).visible( true, true );
  table.column( 11 ).visible( true, true );
  table.column( 12 ).visible( true, true );
  table.column( 13 ).visible( false, true );
  table.column( 14 ).visible( false, true );
  table.column( 15 ).visible( false, true );
  table.column( 16 ).visible( false, true );
  table.column( 17 ).visible( false, true );
  $(table.column(5).header()).text('Début contrat');
  $(table.column(6).header()).text('Fin contrat');
});


//Affichage les vélos en stock
$('body').on('click','.showStockBikes', function(){

  var titleContent = "Vélos : Stock";
  var table = $('#bikesListingTable').DataTable()
  .column(4)
  .search( "stock", true, false )
  .draw();

  table.column( 4 ).visible( false, true );
  table.column( 5 ).visible( false, true );
  table.column( 6 ).visible( false, true );
  table.column( 7 ).visible( true, true );
  table.column( 8 ).visible( false, true );
  table.column( 9 ).visible( true, true );
  table.column( 10 ).visible( false, true );
  table.column( 11 ).visible( true, true );
  table.column( 12 ).visible( false, true );
  table.column( 13 ).visible( false, true );
  table.column( 14 ).visible( false, true );
  table.column( 15 ).visible( false, true );
  table.column( 16 ).visible( true, true );
  table.column( 17 ).visible( true, true );
  //$(table.column(5).header()).text('Date de commande');
  //$(table.column(6).header()).text('Date d\'arrivée');
    table.draw();
  });

$('body').on('click','.showPendingDeliveryBike', function(){
  var table = $('#bikesListingTable').DataTable()
  .column(4)
  .search( "pending_delivery", true, false )
  .draw();

  table.column( 4 ).visible( true, true );
  table.column( 5 ).visible( false, true );
  table.column( 6 ).visible( false, true );
  table.column( 7 ).visible( true, true );
  table.column( 8 ).visible( false, true );
  table.column( 9 ).visible( true, true );
  table.column( 10 ).visible( false, true );
  table.column( 11 ).visible( true, true );
  table.column( 12 ).visible( false, true );
  table.column( 13 ).visible( false, true );
  table.column( 14 ).visible( false, true );
  table.column( 15 ).visible( false, true );
  table.column( 16 ).visible( true, true );
  table.column( 17 ).visible( true, true );
  table.draw();
});


function switch_showed_bikes(buttonRemove, buttonAdd, buttonContent, titleContent){
  //modification du bouton
  $('.'+buttonRemove).removeClass(buttonRemove).addClass(buttonAdd).find('.fr-inline').html(buttonContent);
  //modification du Titre
  $('#BikesListingAdmin').find('h4').html(titleContent);
}

function list_bikes_admin(){
  var bikeMap;
  $.ajax({
    url: "api/bikes",
    type: "get",
    data: { action: "list", admin: "Y" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        $("#load").removeClass('hidden');
        var i = 0;
        var dest = "";
        var temp = `
        <thead>
        <tr>
        <th>
        ID
        </th>
        <th>
        <span>Société</span>
        </th>
        <th>
        <span>Vélo</span>
        </th>
        <th>
        <span>Marque - Modèle</span>
        </th>
        <th>
        <span>Type de contrat</span>
        </th>
        <th>
        <span>Début contrat</span>
        </th>
        <th>
        <span>Fin contrat</span>
        </th>
        <th>
        <span>Montant</span>
        </th>
        <th>Facturation</th>
        <th>
        <span class="fr-inline">Etat du vélo</span>
        </th>
        <th>Assurance ?</th>
        <th>Mise à jour </th>
        <th>Rentabilité </th>
        <th>Date de commande </th>
        <th>Date de livraison </th>
        <th>Numéro commande fournisseur </th>
        <th>Taille </th>
        <th>Couleur </th>
        <th>Assignation </th>
        </tr>
        </thead><tbody>`;
        dest = dest.concat(temp);

    var now = new Date();
    while (i < response.bike.length) {
    var error = false;
    if (
      response.bike[i].automatic_billing == null ||
      response.bike[i].automatic_billing == "N"
      )
    {
      automatic_billing =
      '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>';
    } else {
      automatic_billing =
      '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>';
    }

    if (
      response.bike[i].status == null ||
      response.bike[i].status == "KO"
      ) {
      status =
    '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>';
    } else {
      status =
      '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>';
    }

    if (
      response.bike[i].contractStart == null &&
      response.bike[i].company != "KAMEO" && response.bike[i].contractType != 'selling'
      ) {
      start = '<span class="text-red">N/A</span>';
    } else if(response.bike[i].contractType == "selling"){
      if(response.bike[i].sellingDate== null){
        start=
        '<span class="text-red">ERROR</span>';
      }else{
        start =
        '<span class="text-green">' +
        response.bike[i].sellingDate.shortDate() +
        "</span>";
      }
    }else if (
      response.bike[i].contractStart != null &&
      response.bike[i].company != "KAMEO") {
      start =
      '<span class="text-green">' +
      response.bike[i].contractStart.substr(8, 2) +
      "/" +
      response.bike[i].contractStart.substr(5, 2) +
      "/" +
      response.bike[i].contractStart.substr(2, 2) +
      "</span>";
    } else if (
      response.bike[i].contractStart == null &&
      (response.bike[i].company == "KAMEO")
      ) {
      start = '<span class="text-green">N/A</span>';
    } else if (
      response.bike[i].contractStart != null &&
      (response.bike[i].company == "KAMEO")
      ) {
      start =
      '<span class="text-red">' +
      response.bike[i].contractStart.substr(8, 2) +
      "/" +
      response.bike[i].contractStart.substr(5, 2) +
      "/" +
      response.bike[i].contractStart.substr(2, 2) +
      "</span>";
    }else {
      start = '<span class="text-red">ERROR</span>';
    }

    if (
      response.bike[i].contractEnd == null &&
      response.bike[i].company != "KAMEO" &&
      response.bike[i].contractType == "leasing"
      ) {
      end = '<span class="text-red">N/A</span>';
  } else if (
    response.bike[i].contractEnd != null &&
    response.bike[i].company != "KAMEO"
    ) {
    end =
    '<span class="text-green">' +
    response.bike[i].contractEnd.substr(8, 2) +
    "/" +
    response.bike[i].contractEnd.substr(5, 2) +
    "/" +
    response.bike[i].contractEnd.substr(2, 2) +
    "</span>";
  } else if (
    response.bike[i].contractEnd == null &&
    (response.bike[i].company == "KAMEO" ||
      response.bike[i].contractType == "renting" ||
      response.bike[i].contractType == "test")
    ) {
    end = '<span class="text-green">N/A</span>';
  } else if (
    response.bike[i].contractEnd != null &&
    (response.bike[i].company == "KAMEO")
    ) {
    end =
    '<span class="text-red">' +
    response.bike[i].contractEnd.substr(8, 2) +
    "/" +
    response.bike[i].contractEnd.substr(5, 2) +
    "/" +
    response.bike[i].contractEnd.substr(2, 2) +
    "</span>";
  } else if (
    response.bike[i].contractEnd == null &&
    response.bike[i].company != "KAMEO" &&
    response.bike[i].contractType == "selling"
    ) {
    end = '<span class="text-green">N/A</span>';
  } else {
    end = '<span class="text-red">ERROR</span>';
  }

  if (response.bike[i].brand == null) {
    var brandAndModel = '<span class="text-red">N/A</span>';
  } else {
    var brandAndModel =
    '<span class="">' +
    response.bike[i].brand +
    " - " +
    response.bike[i].modelBike +
    " - " +
    response.bike[i].frameType +
    "</span>";
  }
  if (response.bike[i].insurance == "Y") {
    insurance =
    '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>';
  } else {
    insurance =
    '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>';
  }

  if (response.bike[i].contractType == "selling") {
    var leasingPrice =
    '<span class="text-green">' +
    response.bike[i].soldPrice +
    "</span>";
  } else if (
    (response.bike[i].leasingPrice == null ||
      response.bike[i].leasingPrice == 0) &&
    (response.bike[i].contractType == "renting" ||
      response.bike[i].contractType == "leasing") &&
    response.bike[i].billingType != "paid"
    ) {
    var leasingPrice = '<span class="text-red">0</span>';
  } else if (
    response.bike[i].leasingPrice != null &&
    response.bike[i].leasingPrice != 0 &&
    (response.bike[i].contractType == "renting" ||
      response.bike[i].contractType == "leasing")
    ){
      if(response.bike[i].billingType=='annual'){
        var leasingPrice =
        '<span class="text-green">' +
        response.bike[i].leasingPrice +
        " €/an</span>";
      }else if(response.bike[i].billingType=='monthly'){
        var leasingPrice =
        '<span class="text-green">' +
        response.bike[i].leasingPrice +
        " €/mois</span>";
      }
  } else if (
    response.bike[i].leasingPrice != null &&
    response.bike[i].leasingPrice != 0 &&
    (response.bike[i].contractType == "stock" ||
      response.bike[i].contractType == "test")
    ) {
    var leasingPrice =
    '<span class="text-red">' +
    response.bike[i].leasingPrice +
    " €/mois</span>";
  } else if (
    (response.bike[i].leasingPrice == null ||
      response.bike[i].leasingPrice == 0) &&
    (response.bike[i].contractType == "stock" ||
      response.bike[i].contractType == "test" ||
      response.bike[i].billingType == "paid")
    ) {
    var leasingPrice = '<span class="text-green">0</span>';
  } else {
    var leasingPrice = '<span class="text-red">ERROR</span>';
  }

  if (
    (response.bike[i].contractType == "stock" &&
      response.bike[i].company != "KAMEO") ||
    ((response.bike[i].contractType == "leasing" ||
      response.bike[i].contractType == "renting") &&
    response.bike[i].company == "KAMEO")
    ) {
   error = true;
  }

  if (response.bike[i].rentability != "N/A") {
    var rentability =
    '<td data-sort="' +
    Math.round(response.bike[i].rentability) +
    '">' +
    Math.round(response.bike[i].rentability) +
    " %</td>";
  } else {
    var rentability =
    '<td data-sort="0">' + response.bike[i].rentability + "</td>";
  }

  if (response.bike[i].GPS_ID != null && response.bike[i].GPS_ID != '/' && response.bike[i].GPS_ID != '-' && response.bike[i].GPS_ID != 'TBC' && response.bike[i].GPS_ID != '') {
    var GPS = '<a data-target="#bikePositionAdmin" name="' +
    response.bike[i].id +
    '" data-toggle="modal" class="getBikePosition" href="#"><i class="fa fa-map-pin" aria-hidden="true"></i> </a>';
  }else{
    var GPS = "";
  }

  if (response.bike[i].bikeBuyingDate == null) {
    var bikeBuyingDate = '<span class="text-red">N/A</span>';
  } else {
    var bikeBuyingDate =
    '<span class="">' +
    response.bike[i].bikeBuyingDate.shortDate() +
    "</span>";
  }

  if (((response.bike[i].contractType=="leasing" || response.bike[i].contractType=="location") && response.bike[i].deliveryDate == null) || (response.bike[i].contractType=="order" && (response.bike[i].estimatedDeliveryDate == "0000-00-00" || response.bike[i].estimatedDeliveryDate == null))) {
    var deliveryDate = '<td class="text-red">N/A</td>';
  } else {
    if(response.bike[i].contractType=="leasing" || response.bike[i].contractType=="location")
    {
      var deliveryDate =
      '<td data-sort="'+(new Date(response.bike[i].deliveryDate)).getTime()+'">' +
      response.bike[i].deliveryDate.shortDate() +
      "</td>";
    }else if (response.bike[i].contractType=="order"){
      if((new Date(response.bike[i].estimatedDeliveryDate)) < new Date()){
        var deliveryDate =
        '<td class="text-red" data-sort="'+(new Date(response.bike[i].estimatedDeliveryDate)).getTime()+'">' +
        response.bike[i].estimatedDeliveryDate.shortDate() +
        "</td>";
      }else{
        var deliveryDate =
        '<td data-sort="'+(new Date(response.bike[i].estimatedDeliveryDate)).getTime()+'">' +
        response.bike[i].estimatedDeliveryDate.shortDate() +
        "</td>";
      }
    }else{
      var deliveryDate = '<td>N/A</td>';
    }
  }

  if (response.bike[i].color == "" || response.bike[i].color == null) {
    var color = "N/A";
  } else {
    var color = response.bike[i].color;
  }
  if(response.bike[i].contractType == "order"){
    if(response.bike[i].estimatedDeliveryDate != null){
      var dateOrder = new Date(response.bike[i].estimatedDeliveryDate);
      if(dateOrder < now){
        error=true;
      }
    }else{
      error=true;
    }
  }
  if(response.bike[i].contractType == "order" && (response.bike[i].estimatedDeliveryDate == null || response.bike[i].estimatedDeliveryDate == "0000-00-00")){
    error = true;
  }

  var style = "";
  if(error){
    var style = " class='text-red' "
  }else{
    var style = " class='text-green' "
  }

  if(response.bike[i].type=='personnel'){
    var assignation = response.bike[i].ownerFirstName + " " + response.bike[i].ownerName;
  }else{
    var assignation = "/";
  }

  temp =
  '<tr><td>'+
  GPS+
  '<a  data-target="#bikeManagement" name="' +
  response.bike[i].id +
  '" data-toggle="modal" class="updateBikeAdmin" href="#">' +
  response.bike[i].id +
  "</a></td><td>" +
  response.bike[i].company +
  "</td><td>" +
  response.bike[i].frameNumber +
  "</td><td>" +
  brandAndModel +
  "</td><td "+style+" >" +
  response.bike[i].contractType +
  "</td><td "+style+" data-sort='"+(new Date(response.bike[i].contractStart)).getTime()+"'>" +
  start +
  "</td><td "+style+" data-sort='"+(new Date(response.bike[i].contractEnd)).getTime()+"'>" +
  end +
  "</td><td>" +
  leasingPrice +
  "</td><td>" +
  automatic_billing +
  "</td><td>" +
  status +
  "</td><td>" +
  insurance +
  '</td><td data-sort="' +
  new Date(response.bike[i].HEU_MAJ).getTime() +
  '">' +
  response.bike[i].HEU_MAJ.shortDate() +
  "</td>" +
  rentability +
  "<td>" +
  bikeBuyingDate +
  "</td>" +
  deliveryDate +
  "<td>" +
  response.bike[i].orderNumber +
  "</td><td>" +
  response.bike[i].size +
  "</td><td>" +
  color +
  "</td><td>" +
  assignation +
  "</td></tr>";
  dest = dest.concat(temp);
  i++;
  }
  var temp = "</tbody>";
  dest = dest.concat(temp);
  document.getElementById("bikesListingTable").innerHTML = dest;

  $('.linkBikesToBills').off();
  $('.linkBikesToBills').click(function(){
    fill_link_bikes_to_bills();
    $('#linkBikesToBills').removeClass("hidden");
    $('#bikesListingTable_wrapper').addClass("hidden");
  });

  $('#linkBikesToBillsTable .glyphicon-plus').unbind();
  $('#linkBikesToBillsTable .glyphicon-plus').click(function(){
    bikesNumber = $("#widget-linkBikesToBills-form").find('.bikesNumber').html()*1+1;
    $('#widget-linkBikesToBills-form').find('.bikesNumber').html(bikesNumber);

    var hideBikepVenteHTVA;
    var hideBikeLeasing ='';
    var inRecapLeasingBike ='';
    var inRecapVenteBike ='';

    //creation du div contenant
    $('#linkBikesToBillsTable').find('tbody')
    .append(`<tr class="bikeNumber`+(bikesNumber)+` bikeRow form-group">
    <td class="bLabel"></td>
    <td class="brand"></td>
    <td class="model"></td>
    <td class="catalogID"></td>
    <td class="size"></td>
    <td class="priceCatalog"></td>
    <td class="sellingPrice"></td>
    <td class="bikeID"></td>
    </tr>`);

    //label selon la langue
    $('#linkBikesToBillsTable').find('.bikeNumber'+(bikesNumber)+'>.bLabel')
    .append('<label>'+ bikesNumber +'</label>');

    $('#linkBikesToBillsTable').find('.bikeNumber'+(bikesNumber)+'>.brand')
    .append("<select class='brand'>\
      <option value='ahooga'>Ahooga</option>\
      <option value'benno'>Benno</option>\
      <option value'bzen'>BZEN</option>\
      <option value'conway'>Conway</option>\
      <option value'Douze Cycle'>Douze Cycle</option>\
      <option value'hnf'>HNF Nicolai</option>\
      <option value'kayza'>Kayza</option>\
      <option value'Moustache Bikes'>Moustache Bikes</option>\
      <option value'Orbea'>Orbea</option>\
      <option value'other'>Other</option>\
      <option value'victoria'>Victoria</option>\
      </select>");
    $('#linkBikesToBillsTable').find('.bikeNumber'+(bikesNumber)+'>.brand>select').val("");

    $('#linkBikesToBillsTable').find('.bikeNumber'+(bikesNumber)+'>.model')
    .append("<select class='pModel'></select>");

    $('#linkBikesToBillsTable').find('.bikeNumber'+(bikesNumber)+'>.catalogID')
    .append("<input type='number' name='catalogID[]' class='form-control required' readonly>");

    $('#linkBikesToBillsTable').find('.bikeNumber'+(bikesNumber)+'>.size')
    .append("<select class='size form-control required' name='size[]'>\
      <option value='XS'>XS</option>\
      <option value'S'>S</option>\
      <option value'M'>M</option>\
      <option value'L'>L</option>\
      <option value'XL'>XL</option>\
      <option value'unique'>Unique</option>\
      </select>");
    $('#linkBikesToBillsTable').find('.bikeNumber'+(bikesNumber)+'>.size>select').val("");

    $('#linkBikesToBillsTable').find('.bikeNumber'+(bikesNumber)+'>.priceCatalog')
    .append("<input type='number' name='buyingPrice[]' step='0.01' class='form-control required'>");

    $('#linkBikesToBillsTable').find('.bikeNumber'+(bikesNumber)+'>.sellingPrice')
    .append("<input type='number' step='0.01' class='form-control required' readonly>");

    //gestion du select du velo
    $('#linkBikesToBillsTable .brand select').off();
    $('#linkBikesToBillsTable .brand select').on('change',function(){

      var $modelSelect=$(this).closest('.bikeRow').find('.pModel');

      $modelSelect.find("option")
      .remove()
      .end();
      $.ajax({
        url: "api/portfolioBikes",
        type: "get",
        data: { action: "listModelsFromBrand", brand:$(this).val()},
        success: function (response){
          response.forEach(model => $modelSelect.append('<option value="'+model.ID+'" data-buyingprice="'+model.BUYING_PRICE+'" data-sellingprice="'+model.PRICE_HTVA+'">'+model.MODEL+' - '+model.FRAME_TYPE+' -'+model.SEASON+'</option>'));
          $modelSelect.val("");
        }
      });
    });

    $('#linkBikesToBillsTable .pModel').on('change',function(){
      var catalogID = $(this).val();
      var buyingPrice=$(this).children("option:selected").data("buyingprice");
      var sellingPrice=$(this).children("option:selected").data("sellingprice");
      $(this).closest('.bikeRow').find('.catalogID input').val(catalogID);
      $(this).closest('.bikeRow').find('.priceCatalog input').val(buyingPrice);
      $(this).closest('.bikeRow').find('.sellingPrice input').val(sellingPrice);
    });
    checkMinus('#widget-linkBikesToBills-form','.bikesNumber');
  });



  $('.getBikePosition').click(function(){
    bikeMap=this.name;
  })


  $("#bikePositionAdmin").off();
  $("#bikePositionAdmin").on('shown.bs.modal', function(){
    $.ajax({
      url: "apis/Kameo/get_position.php",
      type: "get",
      data: { bikeId: bikeMap },
      xhrFields: {
        withCredentials: true,
      },
      headers: {
        Authorization: "Basic " + btoa("antoine@kameobikes.com:test"),
      },

      success: function (response) {
        if (response.response == "error") {
          console.log(response.message);
        } else {
          $("#demoMapAdmin").html("");
          var lon = response.longitude;
          var lat = response.latitude;
          var zoom = 15;
          var position = new OpenLayers.LonLat(lat, lon);
          var fromProjection = new OpenLayers.Projection("EPSG:4326"); // Transform from WGS 1984
          var toProjection = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
          var position = new OpenLayers.LonLat(lon, lat).transform(
            fromProjection,
            toProjection
            );

          map = new OpenLayers.Map("demoMapAdmin");
          var mapnik = new OpenLayers.Layer.OSM();
          map.addLayer(mapnik);
          var markers = new OpenLayers.Layer.Markers("Markers");
          map.addLayer(markers);
          markers.addMarker(new OpenLayers.Marker(position));

          map.setCenter(position, zoom);

          $('#bikePositionAdmin span[name=bikeInformation]').html('<p><i class="fa fa-bicycle" aria-hidden="true"></i> Identification du vélo : '+response.frameNumber+'<br><i class="fa fa-building" aria-hidden="true"></i> Société : '+response.company+'<br></p>');
          $('#bikePositionAdmin span[name=informationGPS]').html('<p><i class="fa fa-location-arrow" aria-hidden="true"></i> Coordonnées GPS : ('+lat+', '+lon+')<br><i class="fa fa-calendar" aria-hidden="true"></i> Dernière position : '+get_date_string_european_with_hours(new Date(response.timestamp))+'<br><i class="fa fa-battery-full" aria-hidden="true"></i> Niveau batterie : '+response.batteryLevel+' %<br></p>');
          $('#bikePositionAdmin span[name=informationGPS]').html($('#bikePositionAdmin span[name=informationGPS]').html()+'<img src="images_bikes/'+response.catalogID+'_mini.jpg" >');
        }
      },
    });
  });




  //displayLanguage();

  $(".updateBikeAdmin").off();
  $(".updateBikeAdmin").click(function () {
    construct_form_for_bike_status_updateAdmin(this.name);
    $("#widget-bikeManagement-form input").attr("readonly", false);
    $("#widget-bikeManagement-form select").attr("readonly", false);
    $(".bikeManagementTitle").html("Modifier un vélo");
    $(".bikeManagementSend").removeClass("hidden");
    $(".bikeManagementSend").html('<i class="fa fa-plus"></i>Modifier');
  });

  $(".retrieveBikeAdmin").click(function () {
    construct_form_for_bike_status_updateAdmin(this.name);
    $("#widget-bikeManagement-form input").attr("readonly", true);
    $("#widget-bikeManagement-form select").attr("readonly", true);
    $(".bikeManagementTitle").html("Consulter un vélo");
    $(".bikeManagementSend").addClass("hidden");
  });

  $(".addBikeAdmin").click(function () {
    add_bike();
    $("#widget-bikeManagement-form input").attr("readonly", false);
    $("#widget-bikeManagement-form select").attr("readonly", false);
    $(".bikeManagementTitle").html("Ajouter un vélo");
    $(".bikeManagementSend").removeClass("hidden");
    $(".bikeManagementSend").html('<i class="fa fa-plus"></i>Ajouter');
  });


  table = $("#bikesListingTable").DataTable({
    paging: true,
    searching: true,
    scrollX: true,
    destroy: true,
    lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "Tous"],
    ],
    columns: [
    { width: "50px" },
    { width: "50px" },
    { width: "100px" },
    { width: "180px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    { width: "100px" },
    ],
    columnDefs: [
    {
      targets: [13],
      visible: false,
      searchable: false,
    },
    {
      targets: [14],
      visible: false,
      searchable: false,
    },
    {
      targets: [15],
      visible: false,
    },
    {
      targets: [16],
      visible: false,
    },
    {
      targets: [17],
      visible: false,
    },
    ],
  });

  table
  .column(4)
  .search("test|renting|leasing", true, false)
  .draw();
  }
  $("#load").addClass('hidden');
  },
  });
}
