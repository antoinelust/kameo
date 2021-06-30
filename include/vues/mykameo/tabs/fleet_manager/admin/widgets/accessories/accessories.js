$('.addStockAccessoryButton').click(function(){

 $("#widget-manageStockAccessory-form input[name=numberArticle]").parent().fadeIn();
 $("#widget-manageStockAccessory-form").trigger("reset");
 $("#widget-manageStockAccessory-form select[name=category]").val("");
 $("#widget-manageStockAccessory-form select[name=model]").val("");
 $("#widget-manageStockAccessory-form select[name=company]").val(12);
 $("#widget-manageStockAccessory-form select[name=user]").val("");
 $("#widget-manageStockAccessory-form select[name=user]").parent().fadeOut();
 $("#widget-manageStockAccessory-form select[name=bike]").val("");
 $("#widget-manageStockAccessory-form select[name=user]").parent().fadeOut();
 $("#widget-manageStockAccessory-form select[name=bike]").parent().fadeOut();
 $("#widget-manageStockAccessory-form input[name=action]").val("addStockAccessory");

 $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
 $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
 $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
 $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
 $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
 $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeIn();
 $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeOut();


 $('#widget-manageStockAccessory-form select[name=contractType]').val('order');
 $('#widget-manageStockAccessory-form select[name=contractType]').attr('readonly', true);
})



$('#widget-manageStockAccessory-form select[name=company]').change(function(){
 $('#widget-manageStockAccessory-form select[name=bike').find('option').remove().end();
 $('#widget-manageStockAccessory-form select[name=bike]').attr('disabled', false);

 $.ajax({
   url: "apis/Kameo/companies/companies.php",
   type: "get",
   data: {
     "action" : "retrieveCustommerBike",
     "ID" : $(this).val()
   },
   success : function(data) {
     $('#widget-manageStockAccessory-form select[name=user')
     .find('option')
     .remove()
     .end();

     if(data.user.length > 0 ){
      $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
      data.user.forEach(function(user, index){
        $("#widget-manageStockAccessory-form select[name=user]").append('<option data-bike="'+user['bikeId']+'"" value='+user['email']+'>'+user['name']+' '+user['firstName']+'</option>');
      });
      $("#widget-manageStockAccessory-form select[name=user]").val("");
      $("#widget-manageStockAccessory-form select[name=bike]").parent().fadeOut();
    }else{
      $("#widget-manageStockAccessory-form select[name=user]").parent().fadeOut();
      $.notify({
        message: "Pas d'utilisateurs définis pour cette société, il n'est possible d'attribuer l'accessoire qu'au niveau de la société"
      }, {
        type: 'info'
      });
      $("#widget-manageStockAccessory-form select[name=bike]").parent().fadeIn();
      $.ajax({
       url: "apis/Kameo/accessories/accessories.php",
       type: "get",
       data: {
         "action" : "getBikeFromCompany",
         "ID" : $('#widget-manageStockAccessory-form select[name=company]').val()
       },
       success : function(data) {
        $('#widget-manageStockAccessory-form select[name=bike')
        .find('option')
        .remove()
        .end();
        var i =0;
        data.bike.forEach(function(bike){
          $("#widget-manageStockAccessory-form select[name=bike]").append('<option value='+bike.id+'>'+bike.id+' :  '+bike.model+' - '+bike.contract+'</option>');
        })
        $("#widget-manageStockAccessory-form select[name=bike]").val("");
        if(data.bike.length==0){
          $.notify({
            message: "Pas de vélo lié à cette société, il n'est possible d'attribuer l'accessoire qu'au niveau de la société"
          }, {
            type: 'info'
          });
        }

      }
    });
    }
  }
});
 if($('#widget-manageStockAccessory-form select[name=contractType]').val()=='stock'){
   $("#widget-manageStockAccessory-form select[name=user]").parent().fadeOut();
 }
 else{
  $("#widget-manageStockAccessory-form select[name=user]").parent().fadeOut();
}
});


$('#widget-manageStockAccessory-form select[name=contractType]').change(function(){
  if($(this).val()=="leasing"){
    $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeOut();
    $('#widget-manageStockAccessory-form select[name=company]').attr('readonly', false);
    $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
  }
  else if($(this).val()=="selling"){
    $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
    $("#widget-manageStockAccessory-form label[for=sellingDate]").html("Date de vente");
    $("#widget-manageStockAccessory-form label[for=sellingAmount]").html("Montant vente");
    $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeOut();
    $('#widget-manageStockAccessory-form select[name=company]').attr('readonly', false);
    $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
  }
  else if($(this).val()=="stolen"){
    $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
    $("#widget-manageStockAccessory-form label[for=sellingDate]").html("Date du vol");
    $("#widget-manageStockAccessory-form label[for=sellingAmount]").html("Montant remboursé par assurance");
    $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeOut();
    $('#widget-manageStockAccessory-form select[name=company]').attr('readonly', false);
    $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
  }
  else if($(this).val()=="order"){
    $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeOut();
    $('#widget-manageStockAccessory-form select[name=company]').attr('readonly', false);
    $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
  }
  else {
    $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
    $('#widget-manageStockAccessory-form select[name=company]').attr('readonly', true);
    $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeOut();
    $("#widget-manageStockAccessory-form select[name=user]").parent().fadeOut();
  }
});

$('#widget-manageStockAccessory-form select[name=category]').change(function(){
  $.ajax({
   url: "apis/Kameo/accessories/accessories.php",
   type: "get",
   data: {
     "action": "getModelsCategory",
     "category" : $(this).val()
   },
   success : function(data) {
     $('#widget-manageStockAccessory-form select[name=model')
     .find('option')
     .remove()
     .end();

     if(data.models==null){
      $.notify({
        message: "Pas d'accessoires dans notre catalogue pour cette catégorie"
      }, {
        type: 'danger'
      });
    }else{
      data.models.forEach(function(model, index){
        $("#widget-manageStockAccessory-form select[name=model]").append('<option value='+model['ID']+'>'+model['BRAND'] + " - " +model['MODEL']+'</option>');
      });
      $("#widget-manageStockAccessory-form select[name=model]").val("");
    }

  }
});
});


$('#widget-manageStockAccessory-form select[name=model]').change(function(){
 $.ajax({
  url: "apis/Kameo/accessories/accessories.php",
  type: "get",
  data: {
    "action": "retrieveCatalog",
    "ID" : $(this).val()
  },
  success : function(data) {
    $('#widget-manageStockAccessory-form input[name=leasingAmount]').val(data.accessory.PRICE_HTVA*1.25/36);
    $('#widget-manageStockAccessory-form input[name=sellingAmount]').val(data.PRICE_HTVA);
  }
});
});





$(".stockAccessoriesClick").click(function(){
  $("#widget-manageStockAccessory-form input[name=numberArticle]").parent().fadeOut();
  $("#widget-manageStockAccessory-form").trigger("reset");
  $('#widget-manageStockAccessory-form select[name=bike').find('option').remove().end();
  $("#widget-manageStockAccessory-form select[name=category]").val("");
  $("#widget-manageStockAccessory-form select[name=model]").val("");
  $("#widget-manageStockAccessory-form select[name=company]").val("");
  $("#widget-manageStockAccessory-form select[name=user]").val("");

  $("#widget-manageStockAccessory-form input[name=numberArticle]").parent().fadeOut();

  list_stock_accessories();
  if ($('#widget-manageStockAccessory-form select[name=category] option').length == 0) {
    $.ajax({
     url: "apis/Kameo/accessories/accessories.php",
     type: "get",
     data: {
       "action": "getCategories",
     },
     success : function(data) {
       data.categories.forEach(function(category, index){
         $("#widget-manageStockAccessory-form select[name=category]").append('<option value='+category['ID']+'>'+category['CATEGORY']+'</option>');
       });
       $("#widget-manageStockAccessory-form select[name=category]").val("");
     }
   });
  }
});





function list_stock_accessories(){
  $("#stockAccessoriesList").DataTable({
    destroy: true,
    ajax: {
      url: "api/accessories",
      contentType: "application/json",
      type: "get",
      data: {
        action: "listStock",
      },
    },
    sAjaxDataProp: "accessories",
    columns: [
    {
      title: "ID",
      data: "ID",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
        $(nTd).html(
          "<a href='#' data-target='#manageStockAccessory' data-toggle='modal' href=''#'' class='updateStockAccessorylink' data-ID='" +
          sData +"'> "+sData+"</a>");
      },
    },
    {
      title: "Société",
      data: "COMPANY_NAME",
    },
    {
      title: "Client",
      data: "USER_EMAIL",
    },
    {
      title: "Marque",
      data: "BRAND",
    },
    {
      title: "Modèle",
      data: "MODEL",
    },
    {
      title: "Catégorie",
      data: "CATEGORY",
    },
    {
      title: "Type de contrat",
      data: "CONTRACT_TYPE",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
        if (sData == 'selling') $(nTd).html("Vendu");
        else if (sData == 'leasing') $(nTd).html("Leasing");
        else if (sData == 'stock') $(nTd).html("En stock");
        else if (sData == 'pending_delivery') $(nTd).html("En attente de livraison");
        else if (sData == 'order') $(nTd).html("Commande chez fournisseur");
        else if (sData == 'stolen') $(nTd).html("Volé");
        else $(nTd).html(sData);
      },
    },
    {
      title: "Montant",
      data: "CONTRACT_AMOUNT",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
        if (oData.CONTRACT_TYPE == 'leasing') $(nTd).html(sData + '€/mois');
        else if (oData.CONTRACT_TYPE == 'selling') $(nTd).html(oData.SELLING_AMOUNT + ' €');
      },
    },
    {
      title: "Début contrat",
      data: "CONTRACT_START",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
       if(sData==null){
        $(nTd).html('N/A');
      }
      else {
        $(nTd).html(get_date_string_european(sData));
      }
        $(nTd).data('sort', new Date(sData).getTime());
      },
    },
    {
     title: "Fin contrat",
     data: "CONTRACT_END",
     fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      if(sData==null){
        $(nTd).html('N/A');
      }
      else {
        $(nTd).html(get_date_string_european(sData));
      }
        $(nTd).data('sort', new Date(sData).getTime());
      },
    }
  ],
    order: [
      [0, "desc"]
    ],
    paging : true,
    "pageLength": 50
  }).column(6).search("leasing", false, true ).draw();




  $('#stockAccessoriesList').on( 'draw.dt', function () {
    $('.updateStockAccessorylink').off();
    $('.updateStockAccessorylink').click(function(){
      get_stock_accessory($(this).data("id"));
      $("#widget-manageStockAccessory-form input[name=numberArticle]").parent().fadeOut();
    })
  })
}

$('#stockAccessoriesListing .accessoriesInLeasing').click(function(){
  var table = $('#stockAccessoriesList').DataTable()
  .column(6)
  .search( "leasing", true, false )
  .draw();
})
$('#stockAccessoriesListing .accessoriesInStock').click(function(){
  var table = $('#stockAccessoriesList').DataTable()
  .column(6)
  .search( "stock", true, false )
  .draw();
})
$('#stockAccessoriesListing .accessoriesPendingDelivery').click(function(){
  var table = $('#stockAccessoriesList').DataTable()
  .column(6)
  .search( "pending", true, false )
  .draw();
})

$('#stockAccessoriesListing .accessoriesInOrder').click(function(){
  var table = $('#stockAccessoriesList').DataTable()
  .column(6)
  .search("order", true, false )
  .draw();
})
$('#stockAccessoriesListing .soldAccessories').click(function(){
  var table = $('#stockAccessoriesList').DataTable()
  .column(6)
  .search("selling", true, false )
  .draw();
})
$('#stockAccessoriesListing .stolenAccessories').click(function(){
  var table = $('#stockAccessoriesList').DataTable()
  .column(6)
  .search("stolen", true, false )
  .draw();
})

function get_stock_accessory(ID){
  $('#widget-manageStockAccessory-form select[name=contractType]').attr('readonly', false);

  $.ajax({
   url: "apis/Kameo/accessories/accessories.php",
   type: "get",
   data: {
     "action": "getAccessoryStock",
     "ID" : ID
   },
   success : function(data) {
     if(data.response="success"){
       $("#widget-manageStockAccessory-form").trigger("reset");
       $("#widget-manageStockAccessory-form input[name=action]").val("updateStockAccessory");
       $("#widget-manageStockAccessory-form input[name=ID]").val(data.ID);
       $("#widget-manageStockAccessory-form select[name=category]").val(data.ACCESSORIES_CATEGORIES);
       $("#widget-manageStockAccessory-form select[name=company]").val(data.COMPANY_ID);
       $("#widget-manageStockAccessory-form select[name=contractType]").val(data.CONTRACT_TYPE);
       var email = data.USER_EMAIL;
       var bikeID = data.BIKE_ID;

       $.ajax({
         url: "apis/Kameo/companies/companies.php",
         type: "get",
         data: {
           "action" : "retrieveCustommerBike",
           "ID" : data.COMPANY_ID
         },
         success : function(data) {
           $('#widget-manageStockAccessory-form select[name=user')
           .find('option')
           .remove()
           .end();
           if(data.user.length > 0 ){
            $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
            data.user.forEach(function(user, index){
             $("#widget-manageStockAccessory-form select[name=user]").append('<option data-bike="'+user['bikeId']+'" value='+user['email']+'>'+user['name']+' '+user['firstName']+'</option>');
           });
            $("#widget-manageStockAccessory-form select[name=bike]").parent().fadeOut();

            if(email != 'null'){
              $("#widget-manageStockAccessory-form select[name=user]").val(email);
            }else{
              $("#widget-manageStockAccessory-form select[name=user]").val("");
            }

          }else{
            $("#widget-manageStockAccessory-form select[name=user]").parent().fadeOut();
            $.notify({
              message: "Pas d'utilisateurs définis pour cette société, il n'est possible d'attribuer l'accessoire qu'au niveau de la société"
            }, {
              type: 'info'
            });

            $("#widget-manageStockAccessory-form select[name=bike]").parent().fadeIn();
            $.ajax({
             url: "apis/Kameo/accessories/accessories.php",
             type: "get",
             data: {
               "action" : "getBikeFromCompany",
               "ID" : $('#widget-manageStockAccessory-form select[name=company]').val()
             },
             success : function(data) {
              $('#widget-manageStockAccessory-form select[name=bike')
              .find('option')
              .remove()
              .end();
              var i =0;
              data.bike.forEach(function(bike){
                $("#widget-manageStockAccessory-form select[name=bike]").append('<option value='+bike.id+'>'+bike.id+' :  '+bike.model+' - '+bike.contract+'</option>');
              })
              if(bikeID != 'null'){
                $("#widget-manageStockAccessory-form select[name=bike]").val(bikeID);
              }else{
                $("#widget-manageStockAccessory-form select[name=bike]").val("");
              }

              if(data.bike.length==0){
                $.notify({
                  message: "Pas de vélo lié à cette société, il n'est possible d'attribuer l'accessoire qu'au niveau de la société"
                }, {
                  type: 'info'
                });
              }

            }
          });
          }
        }
      });


       if(data.CONTRACT_TYPE == "leasing"){
         $("#widget-manageStockAccessory-form input[name=contractStart]").val(data.CONTRACT_START);
         $("#widget-manageStockAccessory-form input[name=contractEnd]").val(data.CONTRACT_END);
         $("#widget-manageStockAccessory-form input[name=leasingAmount]").val(data.CONTRACT_AMOUNT);
         $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
         $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeOut();

       }else if(data.CONTRACT_TYPE == 'selling'){
         $("#widget-manageStockAccessory-form input[name=sellingDate]").val(data.SELLING_DATE);
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").val(data.SELLING_AMOUNT);
         $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
         $("#widget-manageStockAccessory-form label[for=sellingDate]").html("Date de vente");
         $("#widget-manageStockAccessory-form label[for=sellingAmount]").html("Montant vente");
         $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
       }else if(data.CONTRACT_TYPE == 'stolen'){
         $("#widget-manageStockAccessory-form input[name=sellingDate]").val(data.SELLING_DATE);
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").val(data.SELLING_AMOUNT);
         $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeIn();
         $("#widget-manageStockAccessory-form label[for=sellingDate]").html("Date du vol");
         $("#widget-manageStockAccessory-form label[for=sellingAmount]").html("Montant remboursé par assurance");
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
       }
       else if(data.CONTRACT_TYPE=="order"){
         $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").val(data.ESTIMATED_DELIVERY_DATE);
       }
       else if(data.CONTRACT_TYPE=="stock"){
         $("#widget-manageStockAccessory-form select[name=user]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
         $('#widget-manageStockAccessory-form select[name=company]').val(12);
         $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=deliveryDate]").val(data.DELIVERY_DATE);
       }
       else{
         $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
         $('#widget-manageStockAccessory-form select[name=company]').val(12);
         $("#widget-manageStockAccessory-form input[name=deliveryDate]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=estimateDeliveryDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=deliveryDate]").val(data.DELIVERY_DATE);
       }
       var catalogID = data.CATALOG_ID;

       $.ajax({
        url: "apis/Kameo/accessories/accessories.php",
        type: "get",
        data: {
          "action": "getModelsCategory",
          "category" : data.ACCESSORIES_CATEGORIES
        },
        success : function(data) {
          $('#widget-manageStockAccessory-form select[name=model]')
          .find('option')
          .remove()
          .end();

          data.models.forEach(function(model, index){
           $("#widget-manageStockAccessory-form select[name=model]").append('<option value='+model['ID']+'>'+model['BRAND'] + " - " +model['MODEL']+'</option>');
         });
          $("#widget-manageStockAccessory-form select[name=model]").val(catalogID);
        }
      });
     }
   }
 });
}

$("#widget-manageStockAccessory-form select[name=user]").change(function(){
  $("#widget-manageStockAccessory-form select[name=bike]").parent().fadeIn();
  $('#widget-manageStockAccessory-form select[name=bike')
  .find('option')
  .remove()
  .end();
  var idBike =  $("#widget-manageStockAccessory-form select[name=user]").find(':selected').data('bike');
  $("#widget-manageStockAccessory-form select[name=bike]").append('<option value='+idBike+'>'+idBike+'</option>');
})


///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

function rempDataTable(){
  $("#accessoriesBillsTable").dataTable({
    destroy: true,
    ajax: {
      url: "api/accessories",
      contentType: "application/json",
      type: "get",
      data: {
        action: "getFacturesBillsAccessory",
      },
    },
    sAjaxDataProp: "",
    columns: [
    {
      title: "ID",
      data: "ID",
    },
    {
      title: "Date",
      data: "DATE",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
       if(sData==null){
        $(nTd).html('N/A');
      }
      else {
        $(nTd).html(get_date_string_european(sData));
      }
    },
  },
  {
    title: "Fournisseur",
    data: "BENEFICIARY_COMPANY",
  },
  {
    title: "Montant total de la facture",
    data: "AMOUNT_HTVA",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html(
        -sData+" €"
        );
    },
  },
  {
    title: "Montant des vélos facture",
    data: "sumBikesCatalog",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html(
        (sData == null) ? "0 €" : Math.round(sData)+" €"
        );
    },
  },
  { title: "Nombre d'accessoire facture", data: "countAccessoriesCatalog" },
  {
    title: "Montant des accessoires facture",
    data: "sumAccessoriesCatalog",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html(
        (sData == null) ? "0 €" : Math.round(sData)+" €"
        );
    },
  },
  {
    title: "Nombre d'accessoire réel",
    data: "countAccessories",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html(
        (sData == oData.countAccessoriesCatalog) ? '<i class="fa fa-check" style="color:green" aria-hidden="true"></i> '+sData : '<i class="fa fa-close" style="color:red" aria-hidden="true"></i> '+sData
        );
    },
  },
  {
    title: "Montant des accessoires réel",
    data: "sumAccessories",
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
        "<a href='#' data-target='#linkAccessoriesToBillsManagement' class='text-green updateBillsAccessorylink' data-correspondent='"+sData+"' data-toggle='modal'>Update</a>"
        );
    },
  },
  ],
  order: [
  [0, "desc"]
  ],
  paging : false,
});

  $('#accessoriesBillsTable').on( 'draw.dt', function () {

    $('#accessoriesBillsTable .updateBillsAccessorylink').off();
    $('#accessoriesBillsTable .updateBillsAccessorylink').click(function(){
      getListAccessoriesBillsDetails($(this).data('correspondent'));
    })
  })

  $("#accessoriesBillsTableNotBind").dataTable({
    destroy: true,
    ajax: {
      url: "api/accessories",
      contentType: "application/json",
      type: "get",
      data: {
        action: "getFacturesBillsNotLinkedAccessory",
      },
    },
    sAjaxDataProp: "",
    columns: [
    { title: "ID", data: "ID" },
    { title: "Marque", data: "BRAND"},
    { title: "Modèle", data: "MODEL"},
    { title: "Société", data: "COMPANY_NAME" },
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

// Recuperation des donnees de la facture
function getListAccessoriesBillsDetails(factureID){
  getLinkAccessoriesBillsDetails(factureID).done(function(response){
    $("#AccessoriesNotLinkedSelection").html("");
    $('#linkAccessoriesToBillsManagement input[name=ID]').val(response.billingDetails.ID);
    $('#linkAccessoriesToBillsManagement input[name=supplier]').val(response.billingDetails.BENEFICIARY_COMPANY);
    $('#linkAccessoriesToBillsManagement input[name=date]').val(response.billingDetails.DATE.substring(0,10));
    $('#linkAccessoriesToBillsManagement input[name=communication]').val(response.billingDetails.COMMUNICATION_STRUCTUREE);
    $("#linkAccessoriesToBillsManagement .PDFFile").attr("data","factures/" + response.billingDetails.FILE_NAME);


    var i=0;
    $('#linkAccessoriesToBillsManagement .accessoryNumberTable tbody').html("");
    if(response.catalogDetails != null){
      response.catalogDetails.forEach(function(accessory){
        $('#linkAccessoriesToBillsManagement .accessoryNumberTable').append('<tr><td>'+(i+1)+'</td><td>'+accessory.BRAND+'</td><td>'+accessory.MODEL+'</td><td>'+accessory.catalogID+'</td><td>'+accessory.BUYING_PRICE+'</td><td>'+accessory.PRICE_HTVA+'</td><td>'+(accessory.ACCESSORY_ID == null ? '<i class="fa fa-close" style="color:red" aria-hidden="true"></i>' : accessory.ACCESSORY_ID)+'</td></tr>');
        i++;
      });
      $('#linkAccessoriesToBillsManagement .accessoriesNumber').html(i);
    }
    $('#linkAccessoriesToBillsManagement select[name=accessoriesNotLinked]').find("option")
    .remove()
    .end();
    if(response.notLinkedAccessories != null){
      response.notLinkedAccessories.forEach(function(accessory){
        $('#linkAccessoriesToBillsManagement select[name=accessoriesNotLinked]').append("<option value='"+accessory.ID+"' data-catalogid='"+accessory.CATALOG_ID+"'>"+accessory.BRAND+" - "+accessory.MODEL+" - "+accessory.CATEGORY+"</option>");
        i++;
      });
      $('#linkAccessoriesToBillsManagement select[name=accessoriesNotLinked]').val("");
    }

  })


  $("#summaryAccessoriesLinked").dataTable({
    destroy: true,
    ajax: {
      url: "api/accessories",
      contentType: "application/json",
      type: "GET",
      data: {
        action: "summaryAccessoriesLinked",
        factureID: factureID
      }
    },
    sAjaxDataProp: "",
    columns: [
    { title: "ID", data: "ID" },
    { title: "Société", data: "COMPANY_NAME" },
    { title: "Type de contrat", data: "CONTRACT_TYPE" },
    { title: "Début de contrat", data: "CONTRACT_START" },
    { title: "Fin de contrat", data: "CONTRACT_END" },
    { title: "Prix d'achat facture",data: "BUYING_PRICE",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html(
        sData+' €'
        );
    },
  }, { title: "Date de livraison", data: "DELIVERY_DATE",
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

}


$('#linkAccessoriesToBillsManagement select[name=accessoriesNotLinked]').change(function(){
  var catalogID=$(this).children("option:selected").data('catalogid');

  $("#AccessoriesNotLinkedSelection").dataTable({
    destroy: true,
    ajax: {
      url: "api/accessories",
      contentType: "application/json",
      type: "GET",
      data: {
        action: "listAccessoriesNotLinkedToBill",
        catalogID: catalogID
      }
    },
    columns: [
    { title: "ID", data: "ID" },
    { title: "Identification", data: "MODEL" },
    { title: "Société", data: "COMPANY_NAME",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html(
        (sData == 'NONE') ? 'N/A' : sData
        );
    },
  },
  { title: "Type de contrat", data: "CONTRACT_TYPE" },
  {
    title: "Début de contrat",
    data: "CONTRACT_START",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html(
        (sData == null) ? 'N/A' : sData.shortDate()
        );
    },
  },
  {
    title: "Fin de contrat",
    data: "CONTRACT_END",
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
    title: "",
    data: "ID",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html("<a class='button small green button-3d rounded icon-right linkAccessoryToBill' data-correspondent='"+sData+"'>Assigner</a>");
    },
  }
  ],
  order: [
  [0, "asc"]
  ],
  paging : false,
  searching : false
});

  $('#AccessoriesNotLinkedSelection').on( 'draw.dt', function () {
    $('#AccessoriesNotLinkedSelection .linkAccessoryToBill').off();
    $('#AccessoriesNotLinkedSelection .linkAccessoryToBill').click(function(){
      $.ajax({
        url: 'api/accessories',
        type: 'get',
        data: { "action": "linkAccessoryToBill", "ID": $('#linkAccessoriesToBillsManagement select[name=accessoriesNotLinked]').val(), "accessoryID" : $(this).data('correspondent')},
        success: function(response){
          $.notify(
          {
            message: response.message,
          },
          {
            type: response.response,
          }
          );
          getListAccessoriesBillsDetails($('#linkAccessoriesToBillsManagement input[name=ID]').val());
          $("#AccessoriesNotLinkedSelection").html("");
          $("#accessoriesBillsTableNotBind").dataTable().api().ajax.reload();
          $("#accessoriesBillsTable").dataTable().api().ajax.reload();
        }
      });
    });
  })
});







function getLinkAccessoriesBillsDetails(id){
  return $.ajax({
    url: 'apis/Kameo/accessories/accessories.php',
    type: 'get',
    data: { "billingID": id, "action" : "getLinkAccessoriesBillsDetails"},
    success: function(response){
    }
  });
}
///////////////////Datatable de tous les accessorie de la facture




/////////////// Ajout d'accessoire à la facture
$('#linkAccessoriesToBillsManagement .glyphicon-plus').unbind();
$('#linkAccessoriesToBillsManagement .glyphicon-plus').click(function(){

  accessoriesNumber = $("#widget-linkAccessoriesToBills-form").find('.accessoriesNumber').html()*1+1;
  $('#widget-linkAccessoriesToBills-form').find('.accessoriesNumber').html(accessoriesNumber);

    //creation du div contenant
    $('#linkAccessoriesToBillsManagement').find('.tbodyAccessoriesTable')
    .append(`<tr class="accessoryNumber`+(accessoriesNumber)+` accessoryRow form-group">
      <td class="bLabel"></td>
      <td class="brand"></td>
      <td class="model"></td>
      <td class="catalogID"></td>
      <td class="priceCatalog"></td>
      <td class="sellingPrice"></td>
      <td class="accessoryID"></td>
      </tr>`);

    //label selon la langue
    $('#linkAccessoriesToBillsManagement').find('.accessoryNumber'+(accessoriesNumber)+'>.bLabel')
    .append('<label>'+ accessoriesNumber +'</label>');

    $('#linkAccessoriesToBillsManagement').find('.accessoryNumber'+(accessoriesNumber)+'>.brand')
    .append("<select class='brand'>\
      <option value='abus'>Abus</option>\
      <option value='atlantic'>Atlantic</option>\
      <option value='atran'>Atran</option>\
      <option value='axa'>Axa</option>\
      <option value'basil'>Basil</option>\
      <option value'benno'>Benno</option>\
      <option value'bosch'>Bosch</option>\
      <option value'contec'>Contec</option>\
      <option value'continental'>Continental</option>\
      <option value'elite'>Elite</option>\
      <option value'ergoTec'>ErgoTec</option>\
      <option value'hartje'>Hartje</option>\
      <option value'hebie'>Hebie</option>\
      <option value'hock'>hock</option>\
      <option value'invoxia'>Invoxia</option>\
      <option value'wetLand'>WetLand</option>\
      </select>");

    $('#linkAccessoriesToBillsManagement').find('.accessoryNumber'+(accessoriesNumber)+'>.brand>select').val("");

    $('#linkAccessoriesToBillsManagement').find('.accessoryNumber'+(accessoriesNumber)+'>.model')
    .append("<select class='pModel'></select>");

    $('#linkAccessoriesToBillsManagement').find('.accessoryNumber'+(accessoriesNumber)+'>.catalogID')
    .append("<input type='number' name='catalogID[]' class='form-control required' readonly>");

    $('#linkAccessoriesToBillsManagement').find('.accessoryNumber'+(accessoriesNumber)+'>.priceCatalog')
    .append("<input type='number' name='buyingPrice[]' step='0.01' class='form-control required'>");

    $('#linkAccessoriesToBillsManagement').find('.accessoryNumber'+(accessoriesNumber)+'>.sellingPrice')
    .append("<input type='number' step='0.01' class='form-control required' readonly>");


    //gestion du select du velo
    $('#linkAccessoriesToBillsManagement .brand select').off();

    $('#linkAccessoriesToBillsManagement .brand select').on('change',function(){

      var $modelSelect=$(this).closest('.accessoryRow').find('.pModel');

      $modelSelect.find("option")
      .remove()
      .end();
      $.ajax({
        url: "apis/Kameo/accessories/accessories.php",
        type: "get",
        data: { action: "listModelsFromBrandAccessories", brand:$(this).val()},
        success: function (response){
          response.forEach(model => $modelSelect.append('<option value="'+model.ID+'" data-buyingprice="'+model.BUYING_PRICE+'" data-sellingprice="'+model.PRICE_HTVA+'">'+model.MODEL+' - '+model.CATEGORY +'</option>'));
          $modelSelect.val("");
        }
      });
    });

    $('#linkAccessoriesToBillsManagement .pModel').on('change',function(){
      var catalogID = $(this).val();
      var buyingPrice=$(this).children("option:selected").data("buyingprice");
      var sellingPrice=$(this).children("option:selected").data("sellingprice");

      $(this).closest('.accessoryRow').find('.catalogID input').val(catalogID);
      $(this).closest('.accessoryRow').find('.priceCatalog input').val(buyingPrice);
      $(this).closest('.accessoryRow').find('.sellingPrice input').val(sellingPrice);
    });
    checkMinus('#widget-linkAccessoriesToBills-form','.accessoriesNumber');
  });
