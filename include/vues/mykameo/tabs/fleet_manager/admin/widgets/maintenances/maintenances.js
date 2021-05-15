$(".fleetmanager").click(function () {
  document.getElementsByClassName('maintenanceManagementClick')[0].addEventListener('click', function() { list_maintenances(); getCompaniesInMaintenances();}, false);

  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "maintenances" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        var dest = '<span data-speed="1" data-refresh-interval="4" data-to="'+response.maintenancesNumberGlobal+'" data-from="0" data-seperator="true">';
        dest += response.maintenancesNumberGlobal + '/</span><span style="color: rgb(216, 0, 0); margin:0;" data-speed="1" data-refresh-interval="4" data-to="'+response.maintenancesNumberAuto+'" data-from="0" data-seperator="false">';
        dest += response.maintenancesNumberAuto + '</span>';
        document.getElementById("counterMaintenance").innerHTML = dest;
      }
    },
  });
  $('#widget-maintenanceManagement-form div[name=addExternalBikesDiv]').hide();
  getCompaniesInMaintenances();
  getCategoriesFromBillsToDevis();
});

function getCompaniesInMaintenances(){
 $.ajax({
  url: "apis/Kameo/companies/companies.php",
  type: "get",
  data: { action:'listMinimal' },
  success: function (response) {
    if (response.response == "error") {
      console.log(response.message);
    }
    if (response.response == "success") {
      $("#widget-maintenanceManagement-form select[name=company]")
      .find("option")
      .remove()
      .end();
      response.company.forEach(function(company){
       $("#widget-maintenanceManagement-form select[name=company]").append(
        '<option id= "'+ company.ID + '" value= "' +company.internalReference +'" data-idCompany="'+company.ID+'">' +company.companyName +  "<br>"
        );
     })
    }
  },
});
}


function list_maintenances() {

  var dateStart = $(".form_date_start_maintenance").data("datetimepicker").getDate();
  var dateEnd = $(".form_date_end_maintenance").data("datetimepicker").getDate();
  var dateStartString =
  dateStart.getFullYear() +
  "-" +
  ("0" + (dateStart.getMonth() + 1)).slice(-2) +
  "-" +
  ("0" + dateStart.getDate()).slice(-2);
  var dateEndString =
  dateEnd.getFullYear() +
  "-" +
  ("0" + (dateEnd.getMonth() + 1)).slice(-2) +
  "-" +
  ("0" + dateEnd.getDate()).slice(-2);

  $("#maintenanceListingSpan").dataTable({
    destroy: true,
    ajax: {
      url: "apis/Kameo/maintenance_management.php",
      contentType: "application/json",
      type: "get",
      data: {
        action : 'list',
        dateStart: dateStartString,
        dateEnd: dateEndString
      },
    },
    sAjaxDataProp: "maintenance",
    columns: [
    {
      title: "ID",
      data: "id",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
        $(nTd).html('<a  data-target="#maintenanceManagementItem" name="'+sData+'" data-toggle="modal" class="showMaintenance" href="#">'+sData+'</a>');
      },
    },
    { title: "Vélo", data: "frame_number" },
    { title: "Modèle", data: "model" },
    { title: "Client", data: "company" },
    { title: "Date de sortie planifié", data: "OUT_DATE_PLANNED",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html(sData.shortDate());
    }
  },
  { title: "Date", data: "date",
  fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
    $(nTd).html(sData.shortDate());
  }
},
{title: "Statut", data: "status",
fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
  if(sData == "AUTOMATICALY_PLANNED"){
    $(nTd).html("<span class='text-red'>Automatique</span>");

  }else if(sData == "MANUALLY_PLANNED"){
    $(nTd).html("<span class='text-red'>Manuelle</span>");

  }else if(sData == "CONFIRMED"){
    $(nTd).html("<span class='text-green'>Confirmé</span>");
  }
  else if(sData == "DONE"){
    $(nTd).html("<span class='text-green'>Fait</span>");
  }
  else if(sData == "IN_SHOP"){  
    $(nTd).html("<span  style =\"color:blue;\">En atelier</span>");
  }
  else if(sData == "TO_PLAN"){
    $(nTd).html("<span  style =\"color:yellow;\">A planifier</span>");
  }
  else if(sData == "WAITING_PIECES"){
    $(nTd).html("<span  style =\"color:blue;\">En attente de pièces</span>");
  }
  else{
    $(nTd).html("<span  style =\"color:black;\">Récupéré par le client</span>");
  }
},
},
{ title: "Type", data: "type" },
{ title: "Adresse", data: "bikeAddress",
fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
  if(sData != null){
    $(nTd).html(sData);
  }else{
    $(nTd).html(oData.street+" "+oData.zip_code+" "+oData.town);
  }
  $(nTd).data('sort', new Date(sData).getTime());
},
},
{ title: "N° téléphone", data: "phone"},
{ title: "", data: "id",
fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
  $(nTd).html('<a href="#" class="text-green editMaintenance" data-target="#maintenanceManagementItem" name="'+sData+'" data-toggle="modal">Modifier</a>');
},
}
],
order: [[0, "asc"]],
paging : false
});
}

function get_maintenance(ID){
  document.getElementById('widget-maintenanceManagement-form').reset();

  $.ajax({
    url: 'api/maintenances',
    method: 'get',
    data: {'action' : 'retrieve', 'ID' : ID},
    success: function(response){
      if (response.response == "error") {
        console.log(response.message);
      } else{
        $('#widget-maintenanceManagement-form select[name=velo]').append('<option value="'+response.maintenance.bike_id+'">'+response.maintenance.bike_id + " - " + response.maintenance.model+'</option>');
        $("#widget-maintenanceManagement-form select[name=velo]").attr("disabled", true);
        $("#widget-maintenanceManagement-form div[name=image]").remove();
        $("#widget-maintenanceManagement-form select[name=company]").attr("disabled", true);

        var date = new Date(response.maintenance.dateMaintenance).toLocaleDateString();
        date = date.split("/");
        var dateOut = new Date(response.maintenance.dateOutPlanned).toLocaleDateString();
        dateOut = dateOut.split("/");
        $('#widget-maintenanceManagement-form input[name=ID]').val(response.maintenance.id);
        $('#widget-maintenanceManagement-form .maintenanceManagementDeleteButton').attr('name', response.maintenance.id);
        $('#widget-maintenanceManagement-form select[name=velo]').val(response.maintenance.bike_id);
        $('#widget-maintenanceManagement-form select[name=company]').val(response.maintenance.company);
        $('#widget-maintenanceManagement-form input[name=model]').val(response.maintenance.model);
        $('#widget-maintenanceManagement-form select[name=status]').val(response.maintenance.status);
        $('#widget-maintenanceManagement-form input[name=dateMaintenance]').val(date[2] + '-' + date[1] + '-' + date[0]);
        $('#widget-maintenanceManagement-form input[name=dateOutPlanned]').val(dateOut[2] + '-' + dateOut[1] + '-' + dateOut[0]);
        $('#widget-maintenanceManagement-form textarea[name=comment]').val(response.maintenance.comment);
        $('#widget-maintenanceManagement-form textarea[name=internalComment]').val(response.maintenance.internalComment);

        $.ajax({
          url: "api/bikes",
          method: "get",
          data: {action: "getAddress", ID: response.maintenance.bike_id },
          success: function (response){
            if (response.response == "error") {
              console.log(response.message);
            }else{
              $('#widget-maintenanceManagement-form input[name=address]').val(response);
            }
          }
        });


        response.maintenance.publicFiles.forEach(function(file){
          var extension=/[^.]*$/.exec(file)[0];
          if(extension=="pdf"){
            $("#widget-maintenanceManagement-form div[name=images]").append('<div class="col-md-4" name="image">\
              <embed src="images_entretiens/'+ID+'/publicFile/'+file+'" height="100%" />\
              <a class="button small green button-3d rounded icon-left" href="images_entretiens/'+ID+'/publicFile/'+file+'" target="_blank"><i class="fa fa-paper-plane"></i>Ouvrir </a>\
              <a class="button small red button-3d rounded icon-left deleteFile" name="'+ID+'/publicFile/'+file+'"><i class="fa fa-paper-plane"></i>Supprimer le fichier </a></div>');

          }else{
            $("#widget-maintenanceManagement-form div[name=images]").append('<div class="col-md-4" name="image">\
              <img src="images_entretiens/'+ID+'/publicFile/'+file+'">\
              <a class="button small red button-3d rounded icon-left deleteFile" name="'+ID+'/publicFile/'+file+'"> \
              <i class="fa fa-paper-plane"></i>Supprimer l\'image </a></div>');
          }
        })


        response.maintenance.internalFiles.forEach(function(file){
          var extension=/[^.]*$/.exec(file)[0];
          if(extension=="pdf"){
            $("#widget-maintenanceManagement-form div[name=internalImages]").append('<div class="col-md-4" name="image">\
              <embed src="images_entretiens/'+ID+'/internalFile/'+file+'" height="100%" />\
              <a class="button small green button-3d rounded icon-left" href="images_entretiens/'+ID+'/internalFile/'+file+'" target="_blank"><i class="fa fa-paper-plane"></i>Ouvrir </a>\
              <a class="button small red button-3d rounded icon-left deleteFile" name="'+ID+'/internalFile/'+file+'"><i class="fa fa-paper-plane"></i>Supprimer le fichier </a></div>');

          }else{
            $("#widget-maintenanceManagement-form div[name=internalImages]").append('<div class="col-md-4" name="image">\
              <img src="images_entretiens/'+ID+'/internalFile/'+file+'">\
              <a class="button small red button-3d rounded icon-left deleteFile" name="'+ID+'/internalFile/'+file+'"> \
              <i class="fa fa-paper-plane"></i>Supprimer l\'image </a></div>');
          }
        })


        $(function(){
          $('a.deleteFile').click(function(){
            $.ajax({
              url:'api/maintenances',
              data:{'action' : 'deleteImage', 'url' : 'images_entretiens/'+this.name},
              method:'POST',
              success:function(response){
                if(response.response == "success"){
                  $.notify(
                  {
                    message: response.message,
                  },
                  {
                    type: "success",
                  }
                  );
                  get_maintenance(response.id);
                  document
                  .getElementById("widget-maintenanceManagement-form")
                  .reset();
                }else{
                  $.notify({
                    message: response.message,
                  }, {
                    type: "danger",
                  });
                }
              }
            });
          });
        });
      }
    }
  });
}

$('.maintenanceManagementDeleteButton').off();
$('.maintenanceManagementDeleteButton').click(function(){
  $.ajax({
    url:'api/maintenances',
    data:{'action' : 'deleteEntretien', 'id' : this.name},
    method:'POST',
    success:function(response){
      if(response.response == "success"){
        $.notify(
        {
          message: response.message,
        },
        {
          type: "success",
        }
        );
        list_maintenances();
        $("#maintenanceManagementItem").modal("toggle");
        document
        .getElementById("widget-maintenanceManagement-form")
        .reset();
      }else{
        $.notify({
          message: response.message,
        }, {
          type: "danger",
        });
      }
    }
  });
})


$('body').on('click', '.editMaintenance',function(){
  get_maintenance(this.name);
  $("#widget-maintenanceManagement-form input[name=action]").val("update");
  $("#widget-maintenanceManagement-form input").attr("readonly", true);
  $("#widget-maintenanceManagement-form input[name=dateMaintenance]").attr("readonly", false);
  $("#widget-maintenanceManagement-form input[name=dateOutPlanned]").attr("readonly", false);
  $("#widget-maintenanceManagement-form select").attr("disabled", false);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", false);
  $(".maintenanceManagementTitle").html("Éditer un entretien");
  $("#widget-maintenanceManagement-form button").show();
  $("#widget-maintenanceManagement-form div[name=file]").show();
  $("#widget-maintenanceManagement-form div[name=internalFile]").show();
  $("#widget-maintenanceManagement-form button[name=delete]").show();
  $("#widget-maintenanceManagement-form div[name=status]").show();
  $("#widget-maintenanceManagement-form div[name=id]").show();
});

$('body').on('click', '.showMaintenance',function(){
  get_maintenance(this.name);
  $("#widget-maintenanceManagement-form input").attr("readonly", true);
  $("#widget-maintenanceManagement-form select").attr("disabled", true);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", true);
  $(".maintenanceManagementTitle").html("Vu sur un entretien");
  $("#widget-maintenanceManagement-form div[name=status]").show();
  $("#widget-maintenanceManagement-form button").hide();
  $("#widget-maintenanceManagement-form div[name=file]").hide();
  $("#widget-maintenanceManagement-form button[name=delete]").hide();
  $("#widget-maintenanceManagement-form div[name=id]").show();
});

$('body').on('click', '.addMaintenance',function(){
  $("#widget-maintenanceManagement-form div[name=image]").remove();
  $("#widget-maintenanceManagement-form div[name=internalImages]").remove();
  empty_form();
  $("#widget-maintenanceManagement-form input[name=action]").val("add");
  $("#widget-maintenanceManagement-form input").attr("readonly", false);
  $("#widget-maintenanceManagement-form select").attr("disabled", false);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", false);
  $("#widget-maintenanceManagement-form select[name=velo]").attr("disabled", false);
  $("#widget-maintenanceManagement-form input[name=dateMaintenance]").attr("readonly", false);
  $("#widget-maintenanceManagement-form input[name=dateOutPlanned]").attr("readonly", false);
  $(".maintenanceManagementTitle").html("Ajouter un entretien");
  $("#widget-maintenanceManagement-form button").show();
  $("#widget-maintenanceManagement-form div[name=id]").hide();
  $("#widget-maintenanceManagement-form div[name=file]").hide();
  $("#widget-maintenanceManagement-form div[name=internalFile]").hide();
  $("#widget-maintenanceManagement-form button[name=delete]").hide();
});

$('body').on('change', '.form_date_start_maintenance',function(){
  var dateStart = $(".form_date_start_maintenance").data("datetimepicker").getDate();
  var dateStartString = ("0" + dateStart.getDate()).slice(-2) + "/" + ("0" + (dateStart.getMonth() + 1)).slice(-2) + "/" + dateStart.getFullYear();
  $(".form_date_end_maintenance").datetimepicker('setStartDate', dateStartString);
});


$('body').on('change', '.form_date_start_maintenance',function(){
  list_maintenances();
});

$('body').on('change', '.form_date_end_maintenance',function(){
  list_maintenances();
});


function empty_form(){
  $('#widget-maintenanceManagement-form input[name=ID]').val("");
  $('#widget-maintenanceManagement-form select[name=velo]').val("");
  $('#widget-maintenanceManagement-form input[name=model]').val("");
  $('#widget-maintenanceManagement-form select[name=company]').val("");
  $('#widget-maintenanceManagement-form input[name=address]').val("");
  $('#widget-maintenanceManagement-form select[name=status]').val("MANUALLY_PLANNED");
  $('#widget-maintenanceManagement-form input[name=dateMaintenance]').val("");
  $('#widget-maintenanceManagement-form input[name=dateOutPlanned]').val("");
  $('#widget-maintenanceManagement-form textarea[name=comment]').val("");
  $('#widget-maintenanceManagement-form textarea[name=internalComment]').val("");
}

$('#widget-maintenanceManagement-form select[name=company]').change(function(){
  getBikesToMaintenance();
  $('#widget-maintenanceManagement-form div[name=addExternalBikesDiv]').show();

  $('#widget-maintenanceManagement-form a .addExternalBikes').data('idCompany',$(this).val());
  
});

function getBikesToMaintenance(){
  $('#widget-maintenanceManagement-form input[name=model]').val("");
  $("#widget-maintenanceManagement-form select[name=velo]").attr("disabled", false);

  $.ajax({
    url: "api/bikes",
    type: "get",
    data: {company: $('#widget-maintenanceManagement-form select[name=company]').val(), action: 'list' },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }else{
        $("#widget-maintenanceManagement-form select[name=velo]")
        .find("option")
        .remove()
        .end();

        response.bike.forEach(function(bike){
          $('#widget-maintenanceManagement-form select[name=velo]').append(
            '<option value="' +
            bike.id +
            '">' +
            bike.id + ' - ' + bike.model + ' : ' + bike.size +
            "</option>"
            );
        })
        response.externalBike.forEach(function(bike){
          $('#widget-maintenanceManagement-form select[name=velo]').append(
            '<option data-external value="' +
            bike.ID +
            '"> EXTERNAL - ' +
            bike.ID + ' - ' + bike.BRAND + ' ' + bike.MODEL +
            "</option>"
            );
        })
        $('#widget-maintenanceManagement-form select[name=velo]').val("");


        $('#widget-maintenanceManagement-form select[name=velo]').change(function(){
          if($(this).children("option:selected").data("external") != undefined){
            $('#widget-maintenanceManagement-form input[name=external]').val(1);
          }else{
            var external = false;
            $('#widget-maintenanceManagement-form input[name=external]').val(0);
          }
        })

      }
    },
  })
}

$('body').on('change', '.form_velo',function(){
  var res = $('#widget-maintenanceManagement-form select[name=velo] option:selected').text().split(" - ");
  var model = res[1].split(" : ")[0];
  $('#widget-maintenanceManagement-form input[name=model]').val(model);

  $.ajax({
    url: "api/bikes",
    method: "get",
    data: {action: "getAddress", ID: $('#widget-maintenanceManagement-form select[name=velo]').val() },
    success: function (response){
      if (response.response == "error") {
        console.log(response.message);
      }else{
        $('#widget-maintenanceManagement-form input[name=address]').val(response);
      }
    }
  });
});




$('#widget-maintenanceManagement-form input[name=publicFile], #widget-maintenanceManagement-form input[name=internalFile]').off();
$('#widget-maintenanceManagement-form input[name=publicFile], #widget-maintenanceManagement-form input[name=internalFile]').change(function(){
  var file = this.files[0];
  var form = new FormData();
  form.append('media', file);
  form.append('action', 'addImage');
  form.append('name', this.name);
  form.append('ID', $('#widget-maintenanceManagement-form input[name=ID]').val());
  $.ajax({
    url : "api/maintenances",
    type: "POST",
    cache: false,
    contentType: false,
    processData: false,
    data : form,
    success: function(response){
      get_maintenance($('#widget-maintenanceManagement-form input[name=ID]').val());
    }
  });
});


$('body').on('click','.displayToPlan', function(){
  var table = $('#maintenanceListingSpan').DataTable()
  .search( "TO_PLAN", true, false )
  .draw();
});



$('body').on('click','.displayInShop', function(){
  var table = $('#maintenanceListingSpan').DataTable()
  .search( "IN_SHOP", true, false )
  .draw();
});


$('body').on('click','.displayInWaitingPieces', function(){
  var table = $('#maintenanceListingSpan').DataTable()
  .search( "WAITING_PIECES", true, false )
  .draw();
});




$("#maintenanceDevis").on("show.bs.modal", function (event) {
  $("#listDevisNone").dataTable({
    destroy: true,
    ajax: {
      url: "api/maintenances",
      contentType: "application/json",
      type: "get",
      data: {
        action : 'listNoneDevis',
      },
    },
    sAjaxDataProp: "",
    columns: [
    {
      title: "ID",
      data: "ID",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
        $(nTd).html('<a name="'+sData+'" class="showDevis" href="#">'+sData+'</a>');
      },
    },
    { title: "Statut", data: "STATUS" },
    { title: "Date", data: "DATE_DEVIS" },
    { title: "Société", data: "COMPANY" },
    { title: "Validité", data: "VALID" },
    { title: "Prix TVAC", data: "AMOUNT_TVAC" },
    { title: "Prix HTVA", data: "AMOUNT_HTVA" },
    ],
    order: [[0, "asc"]],
    paging : false
  });

  $("#listDevisDone").dataTable({
    destroy: true,
    ajax: {
      url: "api/maintenances",
      contentType: "application/json",
      type: "get",
      data: {
        action : 'listDoneDevis',
      },
    },
        sAjaxDataProp: "",
    columns: [
    {
      title: "ID",
      data: "ID",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
        $(nTd).html('<a name="'+sData+'" class="showDevis" href="#">'+sData+'</a>');
      },
    },
   { title: "Statut", data: "STATUS" },
    { title: "Date", data: "DATE_DEVIS" },
    { title: "Société", data: "COMPANY" },
    { title: "Validité", data: "VALID" },
    { title: "Prix TVAC", data: "AMOUNT_TVAC" },
    { title: "Prix HTVA", data: "AMOUNT_HTVA" },
    ],
    order: [[0, "asc"]],
    paging : false
  });
});
/////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////DEVIS /////////////////////////////////
/////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////
//////////////////////::Main d'oeuvre

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
function getCategoriesFromBillsToDevis(){
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

    $('.generateBillAccessoriesDevis .glyphicon-plus').unbind();
    $('.generateBillAccessoriesDevis .glyphicon-plus').click(function(){
      console.log("test");
      //gestion accessoriesNumber
      accessoriesNumber = $("#addDevis").find('.accessoriesNumber').html()*1+1;
      $('#addDevis').find('.accessoriesNumber').html(accessoriesNumber);
      $('#accessoriesNumber').val(accessoriesNumber);

      //ajout des options du select pour les catégories
      var categoriesOption = "<option hidden disabled selected value></option>";
      categories.forEach((category) => {
        categoriesOption += '<option value="'+category.id+'">'+category.name+'</option>';
      });

      //ajout d'une ligne au tableau des accessoires
      $('#addDevis').find('.otherCostsAccesoiresTable tbody')
      .append(`<tr class="otherCostsAccesoiresTable`+(accessoriesNumber)+` accessoriesRow form-group">
        <td class="aLabel"></td>
        <td class="aCategory"></td>
        <td class="aAccessory"></td>
        <td class="aBuyingPrice"></td>
        <td contenteditable='true' class="aPriceHTVA"></td>
        <td><input type="number" class="accessoryFinalPrice hidden" name="accessoryFinalPrice[]" /></td>
        </tr>`);
      //label selon la langue
      $('#addDevis').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aLabel')
      .append('<label>Accessoire '+ accessoriesNumber +'</label>');

      //select catégorie
      $('#addDevis').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aCategory')
      .append(`<select name="accessoryCategory`+accessoriesNumber+`" id="selectCategory`+accessoriesNumber+`" class="selectCategory form-control required">`+
        categoriesOption+`
        </select>`);
      //select Accessoire
      $('#addDevis').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aAccessory')
      .append('<select name="accessoryID[]" id="selectAccessory'+
        accessoriesNumber+
        '"class="selectAccessory form-control required"></select>');

      checkMinus('.generateBillAccessoriesDevis','.accessoriesNumber');

      //on change de la catégorie
      $('.generateBillAccessoriesDevis').find('.selectCategory').on("change",function(){
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

      $('.generateBillAccessoriesDevis').find('.selectAccessory').on("change",function(){
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

      $('#widget-addDevis-form .accessoriesRow .aPriceHTVA ').blur(function(){
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
    $('.generateBillAccessoriesDevis .glyphicon-minus').unbind();
    $('.generateBillAccessoriesDevis .glyphicon-minus').click(function(){
      accessoriesNumber = $("#addDevis").find('.accessoriesNumber').html();
      if(accessoriesNumber > 0){
        $('#addDevis').find('.accessoriesNumber').html(accessoriesNumber*1 - 1);
        $('#accessoriesNumber').val(accessoriesNumber*1 - 1);
        $('#addDevis').find('.otherCostsAccesoiresTable'+accessoriesNumber).slideUp().remove();
        accessoriesNumber--;
      }
      checkMinus('.generateBillAccessoriesDevis','.accessoriesNumber');
    });
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
      $('.generateBillManualWorkloadDevis .glyphicon-plus').unbind();
      $('.generateBillManualWorkloadDevis .glyphicon-plus').click(function(){
        //gestion travail manuel
        manualWorkloadNumber = $("#addDevis").find('.manualWorkloadNumber').html()*1+1;
        $('#addDevis').find('.manualWorkloadNumber').html(manualWorkloadNumber);
        $('#manualWorkloadNumber').val(manualWorkloadNumber);

        //ajout d'une ligne au tableau de la main d'oeuvre
        $('#addDevis').find('.otherCostsManualWorkloadTable tbody')
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
        checkMinus('.generateBillManualWorkloadDevis','.manualWorkloadNumber');
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
              company : $('#widget-addDevis-form select[name=company]').val()
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
      $('.generateBillManualWorkloadDevis .glyphicon-minus').unbind();
      $('.generateBillManualWorkloadDevis .glyphicon-minus').click(function(){
        manualWorkloadNumber = $("#addDevis").find('.manualWorkloadNumber').html();
        if(manualWorkloadNumber > 0){
          $('#addDevis').find('.manualWorkloadNumber').html(manualWorkloadNumber*1 - 1);
          $('#manualWorkloadNumber').val(manualWorkloadNumber*1 - 1);
          $('#addDevis').find('.otherCostsManualWorkloadTable'+manualWorkloadNumber).slideUp().remove();
          otherAccessoriesNumber--;
        }
        checkMinus('.generateBillManualWorkloadDevis','.manualWorkloadNumber');
      });
    }
  });
}





$('.generateBillManualWorkloadDevis .glyphicon-plus').unbind();
$('.generateBillManualWorkloadDevis .glyphicon-plus').click(function(){
  console.log('ca passe');
        //gestion travail manuel
        manualWorkloadNumber = $("#addDevis").find('.manualWorkloadNumber').html()*1+1;
        $('#addDevis').find('.manualWorkloadNumber').html(manualWorkloadNumber);
        $('#manualWorkloadNumber').val(manualWorkloadNumber);

        //ajout d'une ligne au tableau de la main d'oeuvre
        $('#addDevis').find('.otherCostsManualWorkloadTable tbody')
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
        checkMinus('.generateBillManualWorkloadDevis','.manualWorkloadNumber');
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
              company : $('#widget-addDevis-form select[name=company]').val()
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

/////////////////////////////////////////////////////////////////////////
//////////////::ACCESSOIRES


$('.generateBillAccessoriesDevis .glyphicon-plus').unbind();
$('.generateBillAccessoriesDevis .glyphicon-plus').click(function(){
  console.log("test");
      //gestion accessoriesNumber
      accessoriesNumber = $("#addDevis").find('.accessoriesNumber').html()*1+1;
      $('#addDevis').find('.accessoriesNumber').html(accessoriesNumber);
      $('#accessoriesNumber').val(accessoriesNumber);

      //ajout des options du select pour les catégories
      var categoriesOption = "<option hidden disabled selected value></option>";
      categories.forEach((category) => {
        categoriesOption += '<option value="'+category.id+'">'+category.name+'</option>';
      });

      //ajout d'une ligne au tableau des accessoires
      $('#addDevis').find('.otherCostsAccesoiresTable tbody')
      .append(`<tr class="otherCostsAccesoiresTable`+(accessoriesNumber)+` accessoriesRow form-group">
        <td class="aLabel"></td>
        <td class="aCategory"></td>
        <td class="aAccessory"></td>
        <td class="aBuyingPrice"></td>
        <td contenteditable='true' class="aPriceHTVA"></td>
        <td><input type="number" class="accessoryFinalPrice hidden" name="accessoryFinalPrice[]" /></td>
        </tr>`);
      //label selon la langue
      $('#addDevis').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aLabel')
      .append('<label>Accessoire '+ accessoriesNumber +'</label>');

      //select catégorie
      $('#addDevis').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aCategory')
      .append(`<select name="accessoryCategory`+accessoriesNumber+`" id="selectCategory`+accessoriesNumber+`" class="selectCategory form-control required">`+
        categoriesOption+`
        </select>`);
      //select Accessoire
      $('#addDevis').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aAccessory')
      .append('<select name="accessoryID[]" id="selectAccessory'+
        accessoriesNumber+
        '"class="selectAccessory form-control required"></select>');

      checkMinus('.generateBillAccessoriesDevis','.accessoriesNumber');

      //on change de la catégorie
      $('.generateBillAccessoriesDevis').find('.selectCategory').on("change",function(){
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

      $('.generateBillAccessoriesDevis').find('.selectAccessory').on("change",function(){
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

      $('#widget-addDevis-form .accessoriesRow .aPriceHTVA ').blur(function(){
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



$('.generateBillOtherAccessoriesDevis .glyphicon-plus').unbind();
$('.generateBillOtherAccessoriesDevis .glyphicon-plus').click(function(){
    //gestion accessoriesNumber
    otherAccessoriesNumber = $("#addDevis").find('.otherAccessoriesNumber').html()*1+1;
    $('#addDevis').find('.otherAccessoriesNumber').html(otherAccessoriesNumber);
    $('#otherAccessoriesNumber').val(otherAccessoriesNumber);

    //ajout d'une ligne au tableau des accessoires
    $('#addDevis').find('.otherCostsOtherAccesoiresTable tbody')
    .append(`<tr class="otherCostsOtherAccesoiresTable`+(otherAccessoriesNumber)+` otherAccessoriesRow form-group">
      <td class="aLabel"></td>
      <td class="aAccessory"><input type="text" class="otherAccessoryDescription form-control required" name="otherAccessoryDescription[]" /></td>
      <td><input type="number" class="otherAccessoryFinalPrice form-control required" name="otherAccessoryFinalPrice[]" /></td>
      </tr>`);
    //label selon la langue
    $('#addDevis').find('.otherCostsOtherAccesoiresTable'+(otherAccessoriesNumber)+'>.aLabel')
    .append('<label>Accessoire '+ otherAccessoriesNumber +'</label>');
    checkMinus('.generateBillOtherAccessoriesDevis','.otherAccessoriesNumber');
  });