$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "bikesAdmin" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        document.getElementById("counterBikeAdmin").innerHTML =
        '<span style="color:#3cb395" data-speed="1" data-refresh-interval="4" data-to="' +
        response.bikeNumber +
        '" data-from="0" data-seperator="false">' +
        response.bikeNumber +
        "/</span>" +
        '<span style="color: rgb(216, 0, 0); margin:0;" data-speed="1" data-refresh-interval="4" data-to="' +
        response.bikeOrdersLate +
        '" data-from="0" data-seperator="false">' +
        response.bikeOrdersLate +
        "</span>";
      }
    },
  });


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
  $('#insuranceBikeCheck').click(function(){
    if($('#insuranceBikeCheck').is(":checked")){
      $('#widget-bikeManagement-form label[for=contractEnd]').html("Date de fin d'assurance");
      $('#widget-bikeManagement-form .contractEndBloc').fadeIn();
    } else{
      $('#widget-bikeManagement-form .contractEndBloc').fadeOut();
    }
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
  if(type=="selling"){
    $('#widget-bikeManagement-form input[name=bikeID]').attr('readonly', true);
    $('#widget-bikeManagement-form .contractEndBloc').fadeOut();
    $('#widget-bikeManagement-form label[for=contractStart]').html("Date de vente");
    $('#widget-bikeManagement-form .soldPrice').show();
    $('#widget-bikeManagement-form .soldPrice input').removeAttr("disabled");
    $('.contractInfos').fadeIn("slow");
    $('.billingInfos').fadeIn("slow");
    $('.buyingInfos').fadeIn("slow");
    $('.orderInfos').fadeOut("slow");
    $('.billingPriceDiv').fadeOut("slow");
    $('.billingGroupDiv').fadeOut("slow");
    $('.billingDiv').fadeOut("slow");
    $('#widget-bikeManagement-form select[name=billingType]').val("paid");
    $('#widget-bikeManagement-form select[name=billingType]').attr('readonly', true);
    $('#addBike_firstBuilding').fadeOut("slow");
    $('#addBike_buildingListing').fadeOut("slow");
    $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
    $('#bikeUserAccessAdminDiv').fadeOut("slow");
    $('#bikeBuildingAccessAdmin').fadeOut("slow");
    $('#bikeUserAccessAdmin').fadeOut("slow");
    $('#widget-bikeManagement-form label[for=address]').removeClass("hidden");
    $('#widget-bikeManagement-form input[name=address]').removeClass("hidden");
  }else if(type=="stock"){
    $('#widget-bikeManagement-form input[name=bikeID]').attr('readonly', true);
    $('.contractInfos').fadeOut("slow");
    $('.billingInfos').fadeOut("slow");
    $('.buyingInfos').fadeIn("slow");
    $('.orderInfos').fadeOut("slow");
    $('.billingPriceDiv').fadeOut("slow");
    $('.billingGroupDiv').fadeOut("slow");
    $('.billingDiv').fadeOut("slow");
    $('#widget-bikeManagement-form select[name=billingType]').val("paid");
    $('#widget-bikeManagement-form select[name=billingType]').attr('readonly', true);
    $('#addBike_firstBuilding').fadeOut("slow");
    $('#addBike_buildingListing').fadeOut("slow");
    $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
    $('#bikeUserAccessAdminDiv').fadeOut("slow");
    $('#bikeBuildingAccessAdmin').fadeOut("slow");
    $('#bikeUserAccessAdmin').fadeOut("slow");
    $('#widget-bikeManagement-form input[name=address]').val("");
    $('#widget-bikeManagement-form label[for=address]').addClass("hidden");
    $('#widget-bikeManagement-form input[name=address]').addClass("hidden");
  }else if(type=="order"){
    $('.contractInfos').fadeOut("slow");
    $('.billingInfos').fadeOut("slow");
    $('.buyingInfos').fadeIn("slow");
    $('.orderInfos').fadeIn("slow");
    $('.billingPriceDiv').fadeOut("slow");
    $('.billingGroupDiv').fadeOut("slow");
    $('.billingDiv').fadeOut("slow");
    $('#addBike_firstBuilding').fadeOut("slow");
    $('#addBike_buildingListing').fadeOut("slow");
    $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
    $('#bikeBuildingAccessAdmin').fadeOut("slow");
    $('#bikeUserAccessAdminDiv').fadeOut("slow");
    $('addBike_firstBuilding').fadeOut("slow");
    $('#widget-bikeManagement-form input[name=bikeID]').attr('readonly', true);
    $('#widget-bikeManagement-form label[for=address]').addClass("hidden");
    $('#widget-bikeManagement-form input[name=address]').addClass("hidden");
  }else{
    $('#widget-bikeManagement-form input[name=bikeID]').attr('readonly', true);
    $('.buyingInfos').fadeIn("slow");
    $('.contractInfos').fadeIn("slow");
    $('.billingInfos').fadeIn("slow");
    $('.orderInfos').fadeOut("slow");
    if($('#widget-bikeManagement-form select[name=billingType]').val()!="paid"){
      $('.billingPriceDiv').fadeIn("slow");
      $('.billingGroupDiv').fadeIn("slow");
      $('.billingDiv').fadeIn("slow");
    }
    $('#addBike_firstBuilding').fadeIn("slow");
    $('#addBike_buildingListing').fadeIn("slow");
    $('#bikeBuildingAccessAdminDiv').fadeIn("slow");
    $('#bikeBuildingAccessAdmin').fadeIn("slow");
    $('#bikeUserAccessAdminDiv').fadeIn("slow");
    $('#bikeUserAccessAdmin').fadeIn("slow");
    $('addBike_firstBuilding').fadeIn("slow");
    $('#widget-bikeManagement-form select[name=billingType]').attr('readonly', false);
    $('#widget-bikeManagement-form label[for=address]').removeClass("hidden");
    $('#widget-bikeManagement-form input[name=address]').removeClass("hidden");
  }
}

function add_bike(ID){
  var company;
  $('#widget-bikeManagement-form select[name=name]').val("");
  $('#widget-bikeManagement-form input[name=email]').val("");
  $('#widget-bikeManagement-form input[name=phone]').val("");
  $('#widget-bikeManagement-form #user_name').fadeOut();
  $('#widget-bikeManagement-form #user_email').fadeOut();
  $('#widget-bikeManagement-form #user_phone').fadeOut();

  $.ajax({
    url: 'apis/Kameo/get_company_details.php',
    type: 'post',
    data: { "ID": ID},
    success: function(response){
      if (response.response == 'error') {
        console.log(response.message);
      } else if(response.response == 'success'){
        company=response.companyName;

        $("#widget-bikeManagement-form select[name=bikeType]").off();
        $("#widget-bikeManagement-form select[name=bikeType]").change(function() {

          if ($(this).val() == "partage") {
            $("#widget-bikeManagement-form div[id=user_name]").hide();
            $("#widget-bikeManagement-form div[id=user_email]").hide();
            $("#widget-bikeManagement-form div[id=user_phone]").hide();
            $("#widget-bikeManagement-form div[id=utilisateur_bike]").hide();
          } else {
            $('#widget-bikeManagement-form input[name=email]').val("");
            $('#widget-bikeManagement-form input[name=phone]').val("");
            $("#widget-bikeManagement-form select[name=name]").find("option")
            .remove()
            .end();

            $("#widget-bikeManagement-form div[id=user_name]").show();
            $("#widget-bikeManagement-form div[id=utilisateur_bike]").show();
          }
          update_users_list(company);
        }).trigger("change");
      }
    }
  });

  $('#widget-bikeManagement-form select[name=contractType')
  .find('option')
  .remove()
  .end()
  ;
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"order\">Commande</option>");


  $('.contractInfos').fadeOut("slow");
  $('.billingInfos').fadeOut("slow");
  $('.buyingInfos').fadeOut("slow");
  $('.orderInfos').fadeOut("slow");
  $('.billingPriceDiv').fadeOut("slow");
  $('.billingGroupDiv').fadeOut("slow");
  $('.billingDiv').fadeOut("slow");
  $('#addBike_firstBuilding').fadeOut("slow");
  $('#addBike_buildingListing').fadeOut("slow");
  $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
  $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
  $('#bikeBuildingAccessAdmin').fadeOut("slow");
  $('#bikeUserAccessAdmin').fadeOut("slow");
  $('addBike_firstBuilding').fadeOut("slow");




  $('.bikeManagementPicture').addClass('hidden');
  $('.bikeActions').addClass('hidden');
  document.getElementById('addBike_firstBuilding').innerHTML = "";
  document.getElementById('widget-bikeManagement-form').reset();

  $('#widget-bikeManagement-form input[name=action]').val("add");
  $('#widget-bikeManagement-form select[name=contractType]').val("");
  $('#widget-bikeManagement-form select[name=billingType]').val("monthly");
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
    }



  $('#widget-bikeManagement-form select[name=portfolioID]').change(function(){
    $.ajax({
      url: 'apis/Kameo/load_portfolio.php',
      type: 'get',
      data: {"ID": $('#widget-bikeManagement-form select[name=portfolioID]').val(), "action": "retrieve"},
      success: function(response){
        if (response.response == 'error') {
          console.log(response.message);
        } else{
          $('#widget-bikeManagement-form input[name=price]').val(response.buyingPrice);
          $('#widget-bikeManagement-form input[name=model]').val(response.model);
          $('#bikeManagementPicture').attr('src', "images_bikes/"+response.img+"_mini.jpg?date="+Date.now());
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


  $('#widget-bikeManagement-form select[name=company]').val("");


  var buildingNumber;
  var company;

  if(ID){
    $.ajax({
      url: 'apis/Kameo/get_company_details.php',
      type: 'post',
      data: { "ID": ID},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          buildingNumber=response.buildingNumber;
          company=response.internalReference;
          $('#widget-boxManagement-form select[name=company]').val(company);
        }
      }
    }).done(function(){
      $.ajax({
        url: 'apis/Kameo/get_building_listing.php',
        type: 'post',
        data: { "company": company},
        success: function(response){
          if(response.response == 'error') {
            console.log(response.message);
          }
          if(response.response == 'success'){
            var i=0;
            var dest="";
            var dest2="<label for=\"firstBuilding\">Bâtiment pour initialisation</label><select name=\"firstBuilding\">";

            while (i < response.buildingNumber){
              temp="<input type=\"checkbox\" name=\"buildingAccess[]\" checked value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"<br>";
              dest=dest.concat(temp);
              temp2="<option value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"</option>";
              dest2=dest2.concat(temp2);
              i++;

            }
            dest2=dest2.concat("</select>");
            document.getElementById('bikeBuildingAccessAdmin').innerHTML = dest;
            document.getElementById('addBike_firstBuilding').innerHTML = dest2;
          }
        }
      })

      $.ajax({
        url: 'apis/Kameo/get_users_listing.php',
        type: 'post',
        data: { "company": company},
        success: function(response){
          if(response.response == 'error') {
            console.log(response.message);
          }
          if(response.response == 'success'){
            var i=0;
            var dest="";
            while (i < response.usersNumber){
              $('#widget-bikeManagement-form select[name=name]').append("<option value="+response.users[i].email+">"+response.users[i].name+" - " +response.users[i].firstName+"<br>");
              $('#widget-bikeManagement-form input[name=email]').val(response.users[i].email);
              $('#widget-bikeManagement-form input[name=phone]').val(response.users[i].phone);
              temp="<input type=\"checkbox\" name=\"userAccess[]\" checked value=\""+response.users[i].email+"\">"+response.users[i].firstName+" - "+response.users[i].name+"<br>";
              dest=dest.concat(temp);
              i++;
            }
            document.getElementById('bikeUserAccessAdmin').innerHTML = dest;
          }
        }
      });

      $('#widget-bikeManagement-form select[name=company]').val(company);

      update_offer_list(company);
      update_users_list(company);
    })
  }


  $('#widget-bikeManagement-form input[name=bikeID').val("");
  $('#widget-bikeManagement-form input[name=bikeID').fadeOut();
  $('#widget-bikeManagement-form label[for=bikeID').fadeOut();
  $('#widget-bikeManagement-form select[name=contractType').val("order");

  updateDisplayBikeManagement("order");

}

function construct_form_for_bike_status_updateAdmin(bikeID){

  document.getElementById('widget-bikeManagement-form').reset();

  $('#widget-bikeManagement-form input[name=bikeID').fadeIn();
  $('#widget-bikeManagement-form label[for=bikeID').fadeIn();


  $('#widget-bikeManagement-form select[name=contractType')
  .find('option')
  .remove()
  .end()
  ;
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"leasing\">Location LT</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"renting\">Location CT</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"selling\">Vente</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"test\">Vélo de test</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"stock\">Vélo de stock</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"order\">Commande</option>");
  $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"pending_delivery\">Attente Livraison Client</option>");


  var company;
  var frameNumber=frameNumber;

  $('#widget-addActionBike-form input[name=bikeNumber]').val(frameNumber);
  $('.bikeActions').removeClass('hidden');
  $('#widget-bikeManagement-form input[name=action]').val("update");
  $('#widget-bikeManagement-form select[name=portfolioID]')
  .find('option')
  .remove()
  .end()
  ;
  $('#widget-bikeManagement-form select[name=portfolioID]').unbind();

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
          $('#widget-bikeManagement-form input[name=lockerReference]').val(response.lockerReference);
          $('#widget-bikeManagement-form input[name=gpsID]').val(response.gpsID);
          $('#widget-bikeManagement-form input[name=price]').val(response.bikePrice);
          $('#widget-bikeManagement-form input[name=buyingDate]').val(response.bikeBuyingDate);
          $('#widget-bikeManagement-form select[name=billingType]').val(response.billingType);
          if(response.billingType=="monthly"){
            $('#widget-bikeManagement-form .billingPriceDiv .input-group-addon').html('€/mois');
          }else if(response.billingType=="annual"){
            $('#widget-bikeManagement-form .billingPriceDiv .input-group-addon').html('€/an');
          }
          $('#widget-bikeManagement-form select[name=contractType]').val(response.contractType);
          $('#widget-bikeManagement-form input[name=bikeSoldPrice]').val(response.soldPrice);
          $('#widget-bikeManagement-form input[name=orderNumber]').val(response.orderNumber);
          $("#widget-bikeManagement-form select[name=bikeType]").val(response.biketype);

          $('#widget-bikeManagement-form select[name=size]')
          .find('option')
          .remove()
          .end()
          ;
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



          $("#widget-bikeManagement-form select[name=bikeType]").off();
          $("#widget-bikeManagement-form select[name=bikeType]").change(function() {

            if ($(this).val() == "partage"){
              $("#widget-bikeManagement-form div[id=user_name]").hide();
              $("#widget-bikeManagement-form div[id=user_email]").hide();
              $("#widget-bikeManagement-form div[id=user_phone]").hide();
              $("#widget-bikeManagement-form div[id=utilisateur_bike]").hide();
            }else{
              $('#widget-bikeManagement-form input[name=email]').val("");
              $('#widget-bikeManagement-form input[name=phone]').val("test2");
              $("#widget-bikeManagement-form div[id=user_name]").show();
              $("#widget-bikeManagement-form div[id=utilisateur_bike]").show();
              update_users_list(company);
            }
          }).trigger("change");
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
          if(response.bikeBuyingDate == null){
            $('#widget-bikeManagement-form input[name=orderingDate]').val("");
          }else{
            $('#widget-bikeManagement-form input[name=orderingDate]').val(response.bikeBuyingDate.substr(0,10));
          }
          if(response.offerID != null){
            $('#widget-bikeManagement-form select[name=offerReference]').val(response.offerID);
          }else{
            $('#widget-bikeManagement-form select[name=offerReference]').val("");
          }
          update_users_list(company, response.bikeOwner);

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

          $('#widget-bikeManagement-form input[name=billingPrice]').val(response.leasingPrice);

          $('#widget-bikeManagement-form input[name=billingGroup]').val(response.billingGroup);


          document.getElementsByClassName("bikeManagementPicture")[0].src="images_bikes/"+response.img+"_mini.jpg?date="+Date.now();

          if(response.status=="OK"){
            $('#widget-bikeManagement-form input[name=bikeStatus]').val('OK');
          }
          else{
            $('#widget-bikeManagement-form input[name=bikeStatus]').val('KO');
          }
          i=0;
          var dest="";
          if(response.building.length==0){
            temp="<div class=\"col-sm-12 fr\"><p><trong>Pas de bâtiments</strong> définis pour cette société, commencez par en créer un et vous pourrez ensuite y assigner ce vélo</p></div>";
            temp=temp.concat("<div class=\"col-sm-12 en\"><p><strong>Nos building</strong> defined for that company, please first create one and then you will be able to link that building and the bike</p></div>");
            temp=temp.concat("<div class=\"col-sm-12 nl\"><p><strong>Nos building</strong> defined for that company, please first create one and then you will be able to link that building and the bike</p></div>");
            dest=dest.concat(temp);

          }else{
            response.building.forEach(function(building){
              if(building.access=='true'){
                temp="<div class=\"col-sm-3\"><input type=\"checkbox\" checked name=\"buildingAccess[]\" value=\""+building.buildingCode+"\">"+building.descriptionFR+"</div>";
              }
              else{
                temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"buildingAccess[]\" value=\""+building.buildingCode+"\">"+building.descriptionFR+"</div>";
              }
              dest=dest.concat(temp);
            })
          }

          document.getElementById('bikeBuildingAccessAdmin').innerHTML = dest;

          i=0;
          var dest="";

          if(response.user.length==0){
            temp="<div class=\"col-sm-12 fr\"><p><trong>Pas d'utilitisateurs</strong> définis pour cette société, commencez par en créer un et vous pourrez ensuite luis donner accès à ce vélo </p></div>";
            temp=temp.concat("<div class=\"col-sm-12 en\"><p><strong>Nos user</strong> defined for that company, please first create one and then you will be able to link that user and the bike</p></div>");
            temp=temp.concat("<div class=\"col-sm-12 nl\"><p><strong>Nos user</strong> defined for that company, please first create one and then you will be able to link that user and the bike</p></div>");
            dest=dest.concat(temp);

          }else{
            response.user.forEach(function(user){
              if(user.access=='true'){
                temp="<div class=\"col-sm-3\"><input type=\"checkbox\" checked name=\"userAccess[]\" value=\""+user.email+"\">"+user.name+" "+user.firstName+"</div>";
              }
              else if(user.access=='false'){
                temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"userAccess[]\" value=\""+user.email+"\">"+user.name+" "+user.firstName+"</div>";
              }
              dest=dest.concat(temp);
            })
          }
          document.getElementById('bikeUserAccessAdmin').innerHTML = dest;
          $('#widget-bikeManagement-form select[name=company]').val(company);

          $('#widget-bikeManagement-form select[name=company]').change(function(){
            updateAccessAdmin($(this).val());
            update_users_list($('#widget-bikeManagement-form select[name=company]').val());
          });

          $('#widget-bikeManagement-form select[name=portfolioID]').change(function(){
            $.ajax({
              url: 'apis/Kameo/load_portfolio.php',
              type: 'get',
              data: {"ID": $('#widget-bikeManagement-form select[name=portfolioID]').val(), "action": "retrieve"},
              success: function(response){
                if (response.response == 'error') {
                  console.log(response.message);
                } else{
                  $('#bikeManagementPicture').attr('src', "images_bikes/"+response.img+"_mini.jpg?date="+Date.now());
                  $('.bikeManagementPicture').removeClass('hidden');


                }
              }
            })
          });
          updateDisplayBikeManagement(response.contractType);
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
            //displayLanguage();

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
        url: 'apis/Kameo/get_bills_details_listing.php',
        type: 'post',
        data: { "bikeID": id},
        success: function(response){
          if (response.response == 'error') {
            console.log(response.message);
          } else{
            var i=0;
            var dest="<table id=\"bills_details_listing\" class=\"table table-condensed\"  data-order='[[ 0, \"desc\" ]]'><thead><tr><th><span class=\"fr-inline\">ID</span></th><th>Date</th><th>Montant</th><th>Envoyée ?</th><th>Payée ?</th></tr></thead><tbody>";
            while(i<response.billNumber){
              if(response.bill[i].sent=="1"){
                sent="<span class=\"text-green\">Y</span>"
              }else{
                sent="<span class=\"text-red\">N</span>"
              }
              if(response.bill[i].paid=="1"){
                paid="<span class=\"text-green\">Y</span>"
              }else{
                paid="<span class=\"text-red\">N</span>"
              }
              var temp="<tr><td><a href=\"factures/"+response.bill[i].fileName+"\" target=\"_blank\">"+response.bill[i].FACTURE_ID+"</a></td><td data-sort=\""+(new Date(response.bill[i].date)).getTime()+"\">"+response.bill[i].date.shortDate()+"</td><td>"+response.bill[i].amountHTVA+" €</td><td>"+sent+"</td><td>"+paid+"</td></tr>";
              dest=dest.concat(temp);
              i++;
            }
            dest=dest.concat("</tbody></table>");
            $('#bills_bike').html(dest);
            displayLanguage();

            $('#bills_details_listing').DataTable({
              "searching": false,
              "paging": false
            }
            );
          }
        }
      })
    })
})
}



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

function update_users_list(company, userEMAIL = null, userPHONE = null){
  $.ajax({
    url: 'apis/Kameo/get_users_listing.php',
    type: 'post',
    data: { "company": company},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){
        $('#widget-bikeManagement-form select[name=clientReference]')
        .find('option')
        .remove()
        .end()
        ;
        $('#widget-bikeManagement-form select[name=name]')
        .find('option')
        .remove()
        .end()
        ;
        var i=0;
        var toSelect = null;
        while (i < response.usersNumber){
          $('#widget-bikeManagement-form select[name=clientReference]').append("<option value="+response.users[i].email+">"+response.users[i].firstName+" - "+response.users[i].name+"<br>");
          $('#widget-bikeManagement-form select[name=name]').append("<option value="+i+">"+response.users[i].name+" - "+response.users[i].firstName+"<br>");
          if(response.users[i].email == userEMAIL){
            var toSelect = i;
          }
          i++;
        }
        if(response.usersNumber == 0){
          $('.clientReference').fadeOut();
        }else{
          $('.clientReference').fadeIn();
        }

        if(toSelect != null){
          $('#widget-bikeManagement-form select[name=name]').val(toSelect);
          $('#widget-bikeManagement-form #user_name').fadeIn();
          $('#widget-bikeManagement-form #user_email').fadeIn();
          $('#widget-bikeManagement-form #user_phone').fadeIn();
          var user_email = response.users[$($('#widget-bikeManagement-form select[name=name]')).children("option:selected").val()].email;
          $('#widget-bikeManagement-form input[name = email]').val(user_email);
          if(response.users[$('#widget-bikeManagement-form select[name=name]').children("option:selected").val()].phone=='' ||response.users[$($('#widget-bikeManagement-form select[name=name]')).children("option:selected").val()].phone=='/' || response.users[$($('#widget-bikeManagement-form select[name=name]')).children("option:selected").val()].phone==null ){
            var user_phone = 'N/A';
          }else{
            var user_phone = response.users[$($('#widget-bikeManagement-form select[name=name]')).children("option:selected").val()].phone;
          }
          $('#widget-bikeManagement-form input[name = phone]').val(user_phone);
        }else{
          $('#widget-bikeManagement-form select[name=name]').val("");
          $('#widget-bikeManagement-form #user_email').fadeOut();
          $('#widget-bikeManagement-form #user_phone').fadeOut();
        }

        $("#widget-bikeManagement-form select[name=name]").off();
        $("#widget-bikeManagement-form select[name=name]").change(function(){

        if(response.users[$(this).children("option:selected").val()].phone=='' ||response.users[$(this).children("option:selected").val()].phone=='/' || response.users[$(this).children("option:selected").val()].phone==null ){
          var user_phone = 'N/A';
        }else{
          var user_phone = response.users[$(this).children("option:selected").val()].phone;
        }
        var user_email = response.users[$(this).children("option:selected").val()].email;
        $('#widget-bikeManagement-form input[name = email]').val(user_email);
        $('#widget-bikeManagement-form input[name = phone]').val(user_phone);
        $('#widget-bikeManagement-form #user_email').fadeIn();
        $('#widget-bikeManagement-form #user_phone').fadeIn();
      })
    }
  }
})
}


function construct_form_for_bike_access_updateAdmin(company){
    $.ajax({
      url: 'api/companies',
      type: 'get',
      data: { action: "retrieve", "company": company},
      success: function(response){
        if (response.response == 'error') {
          console.log(response.message);
        } else{
          i=0;
          var dest="";
          var dest2="<label for=\"firstBuilding\">Bâtiment pour initialisation</label><select name=\"firstBuilding\">";

          if(response.building.length==0){
            temp="<div class=\"col-sm-12\"><p><trong>Pas de bâtiments</strong> définis pour cette société, commencez par en créer un et vous pourrez ensuite y assigner ce vélo</p></div>";
            dest=dest.concat(temp);

          }else{
            response.building.forEach(function(building){
              temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"buildingAccess[]\" value=\""+building.buildingReference+"\">"+building.buildingFR+"</div>";
              dest=dest.concat(temp);
            })
          }
          dest2=dest2.concat("</select>");
          document.getElementById('addBike_firstBuilding').innerHTML = dest2;
          console.log(dest);

          document.getElementById('bikeBuildingAccessAdmin').innerHTML = dest;
          i=0;
          var dest="";
          if(response.user.length==0){
            temp="<div class=\"col-sm-12\"><p><trong>Pas d'utilitisateurs</strong> définis pour cette société, commencez par en créer un et vous pourrez ensuite luis donner accès à ce vélo </p></div>";
            dest=dest.concat(temp);
          }else{
            response.user.forEach(function(user){
              temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"userAccess[]\" value=\""+user.email+"\">"+user.name+" "+user.firstName+"</div>";
              dest=dest.concat(temp);
            })
          }
          console.log(dest);
          document.getElementById('bikeUserAccessAdmin').innerHTML = dest;
          displayLanguage();


        }

      }
    })
}



function updateAccessAdmin(company){
  construct_form_for_bike_access_updateAdmin(company);
}

//Affichage des vélos vendus
$('body').on('click','.showSoldBikes', function(){

  var buttonContent = "Afficher les autres vélos";
  var titleContent = "Vélos: Vendus";
  var table = $('#bikesListingTable').DataTable()
  .column(4)
  .search( "selling", true, false )
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

  switch_showed_bikes ('showSoldBikes', 'hideSoldBikes', buttonContent, titleContent);
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
$('body').on('click','.hideSoldBikes', function(){
  var buttonContent = "Afficher vélos vendus";
  var titleContent = "Vélos: Leasing et autres";

  table=$('#bikesListingTable').DataTable()
  .column(4)
  .search( "test|renting|leasing", true, false )
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


  switch_showed_bikes ('hideSoldBikes', 'showSoldBikes', buttonContent, titleContent);
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
    $('#bikeDetailsAdmin').find('h4.fr-inline').html(titleContent);
  });

$('body').on('click','.showPendingDeliveryBike', function(){

  var titleContent = "Vélos : En attente livraison";
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
  $('#bikeDetailsAdmin').find('h4.fr-inline').html(titleContent);
});


function switch_showed_bikes(buttonRemove, buttonAdd, buttonContent, titleContent){
  //modification du bouton
  $('.'+buttonRemove).removeClass(buttonRemove).addClass(buttonAdd).find('.fr-inline').html(buttonContent);
  //modification du Titre
  $('#bikeDetailsAdmin').find('h4.fr-inline').html(titleContent);
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
        var temp = `<h4 class="text-green">Vélos: Leasing et autres</h4>
        <a class="button small green button-3d rounded icon-right addBikeAdmin" data-target="#bikeManagement" data-toggle="modal" href="#">
        <span class="fr-inline"><i class="fa fa-plus"></i> Ajouter un vélo</span>
        </a>
        <span class="button small green button-3d rounded icon-right showSoldBikes">
        <span>Afficher les vélos vendus</span>
        </span>
        <span class="button small green button-3d rounded icon-right showOrders">
        <span>Afficher les commandes</span>
        </span>
        <span class="button small green button-3d rounded icon-right showStockBikes">
        <span">Afficher les vélos en stock</span>
        </span>
        <span class="button small green button-3d rounded icon-right showPendingDeliveryBike">
          <span">Afficher les vélos en attente de livraison</span>
        </span>
        <span class="button small green button-3d rounded icon-right linkBikesToBills">Liason factures - vélos</span>
        <br/>
        <table class="table table-condensed" id=\"bikesListingTable\" data-order='[[ 0, \"desc\" ]]' data-page-length='25'>
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
      start =
      '<span class="text-green">' +
      response.bike[i].sellingDate.shortDate() +
      "</span>";
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
      response.bike[i].company != "KAMEO VELOS TEST" &&
      response.bike[i].contractType == "leasing"
      ) {
      end = '<span class="text-red">N/A</span>';
  } else if (
    response.bike[i].contractEnd != null &&
    response.bike[i].company != "KAMEO" &&
    response.bike[i].company != "KAMEO VELOS TEST"
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
      response.bike[i].company == "KAMEO VELOS TEST" ||
      response.bike[i].contractType == "renting" ||
      response.bike[i].contractType == "test")
    ) {
    end = '<span class="text-green">N/A</span>';
  } else if (
    response.bike[i].contractEnd != null &&
    (response.bike[i].company == "KAMEO" ||
      response.bike[i].company == "KAMEO VELOS TEST")
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

  if (response.bike[i].GPS_ID != null && response.bike[i].GPS_ID != '/' && response.bike[i].GPS_ID != '-') {
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
  var temp = "</tbody></table><table id='linkBikesToBills' class='table' style='width: 100%''></table><div class='separator'></div><h4 class='text-green'>Vélos encore à lier</h4><table id='bikesNotLinkedTable' class='table' style='width: 100%''></table>";
  dest = dest.concat(temp);
  document.getElementById("bikeDetailsAdmin").innerHTML = dest;

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
  .search("test|stock|renting|leasing", true, false)
  .draw();
  }
  $("#load").addClass('hidden');
  },
  });
}
