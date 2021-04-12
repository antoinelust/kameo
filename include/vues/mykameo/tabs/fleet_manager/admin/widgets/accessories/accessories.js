$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "stockAccessories" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        document.getElementById("counterStockAccessoriesCounter").innerHTML =
        '<span style="color:#3cb395" data-speed="1" data-refresh-interval="4" data-to="' +
        response.accessoriesNumber +
        '" data-from="0" data-seperator="false">' +
        response.accessoriesNumber +
        "</span>";
      }
    },
  });
});


$('.addStockAccessoryButton').click(function(){
  $("#widget-manageStockAccessory-form").trigger("reset");
  $("#widget-manageStockAccessory-form select[name=category]").val("");
  $("#widget-manageStockAccessory-form select[name=model]").val("");
  $("#widget-manageStockAccessory-form select[name=company]").val("");
  $("#widget-manageStockAccessory-form select[name=user]").val("");
  $("#widget-manageStockAccessory-form input[name=action]").val("addStockAccessory");
  $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeIn();
  $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeIn();
  $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeIn();
  $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
  $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
})

$('#widget-manageStockAccessory-form select[name=company]').change(function(){
 
  if($('#widget-manageStockAccessory-form select[name=company]').val()=='KAMEO'){
    $('#widget-manageStockAccessory-form select[name=contractType]').val('order');
    $('#widget-manageStockAccessory-form select[name=contractType]').attr('disabled', true);
  }
  else{
   $('#widget-manageStockAccessory-form select[name=contractType]').attr('disabled', false);
 }

 $.ajax({
   url: "apis/Kameo/companies/companies.php",
   type: "get",
   data: {
     "action" : "retrieve",
     "ID" : $(this).val()
   },
   success : function(data) {
     $('#widget-manageStockAccessory-form select[name=user')
     .find('option')
     .remove()
     .end();
     if(data.userNumber > 0 ){
      $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
      data.user.forEach(function(user, index){
        $("#widget-manageStockAccessory-form select[name=user]").append('<option value='+user['email']+'>'+user['name']+' '+user['firstName']+'</option>');
      });
      $("#widget-manageStockAccessory-form select[name=user]").val("");
    }else{
      $("#widget-manageStockAccessory-form select[name=user]").parent().fadeOut();
      $.notify({
        message: "Pas d'utilisateurs définis pour cette société, il n'est possible d'attribuer l'accessoire qu'au niveau de la société"
      }, {
        type: 'info'
      });
    }
  }
});
});


$('#widget-manageStockAccessory-form select[name=contractType]').change(function(){
  if($(this).val()=="leasing"){
    $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
  }
  else{
    $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
    $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeIn();
    $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeIn();
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
    "action": "retrieve",
    "ID" : $(this).val()
  },
  success : function(data) {
    $('#widget-manageStockAccessory-form input[name=leasingAmount]').val(data.accessory.PRICE_HTVA*1.25/36);
    $('#widget-manageStockAccessory-form input[name=sellingAmount]').val(data.PRICE_HTVA);
  }
});
});





$(".stockAccessoriesClick").click(function(){

  $("#widget-manageStockAccessory-form").trigger("reset");
  $("#widget-manageStockAccessory-form select[name=category]").val("");
  $("#widget-manageStockAccessory-form select[name=model]").val("");
  $("#widget-manageStockAccessory-form select[name=company]").val("");
  $("#widget-manageStockAccessory-form select[name=user]").val("");

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
  $("#stockAccessoriesList").dataTable({
    destroy: true,
    ajax: {
      url: "apis/Kameo/accessories/accessories.php",
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
          sData +
          "'> "+sData+"</a>"
          );
      },
    },
    {
      title: "Company",
      data: "COMPANY_NAME",
    },
    {
      title: "User",
      data: "USER_EMAIL",
    },
    {
      title: "Brand",
      data: "BRAND",
    },
    {
      title: "Model",
      data: "MODEL",
    },
    {
      title: "Category",
      data: "CATEGORY",
    },
    {
      title: "Contract Type",
      data: "CONTRACT_TYPE",
    },
    {
      title: "Amount",
      data: "CONTRACT_AMOUNT",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
        if (oData.CONTRACT_TYPE == 'leasing') $(nTd).html(sData + '€/mois');
        else if (oData.CONTRACT_TYPE == 'achat') $(nTd).html(oData.SELLING_AMOUNT + ' €');
      },
    },
    {
      title: "Contract start",
      data: "CONTRACT_START",
    },
    {
     title: "Contract end",
     data: "CONTRACT_END",
   }
   ],
   order: [
   [0, "desc"]
   ],
   paging : false
 });

  $('.updateStockAccessorylink').off();
  $('.updateStockAccessorylink').click(function(){
    get_stock_accessory($(this).data("id"));
  });
}


function get_stock_accessory(ID){
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
       $("#widget-manageStockAccessory-form input[name=user]").val(data.USER_EMAIL);
       $("#widget-manageStockAccessory-form select[name=contractType]").val(data.CONTRACT_TYPE);
       if(data.CONTRACT_TYPE == "leasing"){
         $("#widget-manageStockAccessory-form input[name=contractStart]").val(data.CONTRACT_START);
         $("#widget-manageStockAccessory-form input[name=contractEnd]").val(data.CONTRACT_END);
         $("#widget-manageStockAccessory-form input[name=leasingAmount]").val(data.CONTRACT_AMOUNT);
         $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeOut();
       }else if(data.CONTRACT_TYPE == 'achat'){
         $("#widget-manageStockAccessory-form input[name=sellingDate]").val(data.SELLING_DATE);
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").val(data.SELLING_AMOUNT);
         $("#widget-manageStockAccessory-form input[name=contractStart]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=contractEnd]").parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=leasingAmount]").parent().parent().fadeOut();
         $("#widget-manageStockAccessory-form input[name=sellingDate]").parent().fadeIn();
         $("#widget-manageStockAccessory-form input[name=sellingAmount]").parent().parent().fadeIn();
       }

       var user= data.USER_EMAIL;
       $.ajax({
         url: "apis/Kameo/companies/companies.php",
         type: "get",
         data: {
           "action" : "retrieve",
           "ID" : data.COMPANY_ID
         },
         success : function(data) {
           $('#widget-manageStockAccessory-form select[name=user')
           .find('option')
           .remove()
           .end();
           if(data.userNumber > 0 ){
            $("#widget-manageStockAccessory-form select[name=user]").parent().fadeIn();
            data.user.forEach(function(user, index){
              $("#widget-manageStockAccessory-form select[name=user]").append('<option value='+user['email']+'>'+user['name']+' '+user['firstName']+'</option>');
            });
            $("#widget-manageStockAccessory-form select[name=user]").val("");
          }else{
            $("#widget-manageStockAccessory-form select[name=user]").parent().fadeOut();
          }
          $("#widget-manageStockAccessory-form select[name=user]").val(user);

        }
      });
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
