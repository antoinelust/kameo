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
        document.getElementById("counterMaintenance").innerHTML = '<span style="margin-left:20px">'+response.maintenancesNumberAuto+'</span>';
      }
    },
  });
  $('#widget-maintenanceManagement-form div[name=addExternalBikesDiv]').hide();
  getCompaniesInMaintenances();
});


$('#maintenanceManagementItem').on('shown.bs.modal', function(event){

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

      $('#maintenanceManagementItem .manualWorkload .glyphicon-plus').unbind();
      $('#maintenanceManagementItem .manualWorkload .glyphicon-plus').click(function(){
        //gestion travail manuel
        manualWorkloadNumber = $("#maintenanceManagementItem").find('.manualWorkloadNumber').html()*1+1;
        $('#maintenanceManagementItem').find('.manualWorkloadNumber').html(manualWorkloadNumber);
        $('#maintenanceManagementItem input[name=manualWorkloadNumber]').val(manualWorkloadNumber);

        //ajout d'une ligne au tableau de la main d'oeuvre
        $('#maintenanceManagementItem').find('.manualWorkload tbody')
        .append(`<tr class="manualWorkloadRow form-group">
          <td class="category"><select name="category[]" class="form-control required" value="">`+categories+`</select></td>
          <td class="service"><select name="service[]" class="form-control required"></select></td>
          <td class="manualWorkloadLength"><input type="number" step='5' class="form-control required" name="manualWorkloadLength[]" value="" /></td>
          <td class="manualWorkloadTotal"><input type="number" step='0.01' class="form-control required" name="manualWorkloadTotal[]" value="" /></td>
          <td class="manualWorkloadTotalTVAC"><input type="number" step='0.01' class="form-control required" name="manualWorkloadTotalTVAC[]" value="" /></td>
          </tr>`);

        $('#maintenanceManagementItem').find('.manualWorkload tbody tr:last-child select').val('');
        $('#maintenanceManagementItem .category select').off();
        $('#maintenanceManagementItem .category select').change(function(){
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
        });

        $('#maintenanceManagementItem .service select').off()
        $('#maintenanceManagementItem .service select').change(function(){
          $(this).closest('tr').find('.manualWorkloadLength input').val($(this).find(':selected').data('minutes'));
          $(this).closest('tr').find('.manualWorkloadTotal input').val($(this).find(':selected').data('htva'));
          $(this).closest('tr').find('.manualWorkloadTotalTVAC input').val($(this).find(':selected').data('tvac'));
        });

        $('#maintenanceManagementItem .manualWorkloadTotal input').off()
        $('#maintenanceManagementItem .manualWorkloadTotal input').change(function(){
          $(this).closest('tr').find('.manualWorkloadTotalTVAC input').val(Math.round($(this).val()*1.06*100)/100);
        });
        $('#maintenanceManagementItem .manualWorkloadTotalTVAC input').off()
        $('#maintenanceManagementItem .manualWorkloadTotalTVAC input').change(function(){
          $(this).closest('tr').find('.manualWorkloadTotal input').val(Math.round($(this).val()/1.06*100)/100);
        });

      });
      $('#maintenanceManagementItem .maintenanceAccessories .glyphicon-plus').unbind();
      $('#maintenanceManagementItem .maintenanceAccessories .glyphicon-plus').click(function(){
        //Accessoires
        $.ajax({
          url: 'api/accessories',
          type: 'get',
          data: {action: 'listStock'},
          success: function(response){
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
                if (category.name === accessory.CATEGORY) {
                  newCategory = false;
                }
              });
              if (newCategory === true) {
                categories.push({'id' : accessory.categoryId, 'name' : accessory.CATEGORY});
              }
            });

            //gestion accessoriesNumber
            accessoriesNumber = $("#maintenanceManagementItem").find('.accessoriesNumber').html()*1+1;
            $('#maintenanceManagementItem').find('.accessoriesNumber').html(accessoriesNumber);

            //ajout des options du select pour les catégories
            var categoriesOption = "<option hidden disabled selected value></option>";
            categories.forEach((category) => {
              categoriesOption += '<option value="'+category.id+'">'+traduction['accessoryCategories_'+category.name]+'</option>';
            });

            //ajout d'une ligne au tableau des accessoires
            $('#maintenanceManagementItem').find('.accessoriesTable tbody')
            .append(`<tr class="accessoriesRow form-group">
              <td class="aCategory"><select name="accessoryCategory[]" class="form-control required">`+categoriesOption+`</select></td>
              <td class="aAccessory"><select name="portfolioID[]" class="form-control required"></select></td>
              <td class="aBuyingPrice"><input name='buygingPrice' class='form-control' disabled></td>
              <td class="aPriceHTVA"><input type='number' step='0.01' name='accessoryAmount[]' class='form-control'></td>
              <td class="aPriceTVAC"><input type='number' step='0.01' name='accessoryAmountTVAC[]' class='form-control'></td>
              </tr>`);


            var $row=$('#maintenanceManagementItem .accessoriesTable tbody tr:last-child');


            $row.find('.aPriceHTVA input').change(function(){
              $row.find('.aPriceTVAC input').val(Math.round($(this).val()*1.21*100)/100);
            });
            $row.find('.aPriceTVAC input').change(function(){
              $row.find('.aPriceHTVA input').val(Math.round($(this).val()/1.21*100)/100);
            });


            //on change de la catégorie
            $('#maintenanceManagementItem .accessoriesTable tbody tr:last-child .aCategory select').on("change",function(){
              var accessoriesOption = "<option hidden disabled selected value>Veuillez choisir un accesoire</option>";
              var categoryId=$(this).val();
              //ne garde que les accessoires de cette catégorie
              var presence=false;
              accessories.forEach((accessory) => {
                if (categoryId == accessory.categoryId && (accessory.CONTRACT_TYPE == 'stock' || accessory.CONTRACT_TYPE=='pending_delivery')) {
                  presence=true;
                  accessoriesOption += '<option value="'+accessory.ID+'">'+accessory.COMPANY_NAME+' - '+accessory.CONTRACT_TYPE+' - '+accessory.MODEL+'</option>';
                }
              });
              if(!presence){
                $.notify({
                  message: "Pas d'accessoires de ce type en stock ou en attente de livraison"
                }, {
                  type: 'warning'
                });
              }
              //place les accessoires dans le select
              $row.find('.aAccessory select').append(accessoriesOption);

              //retire l'affichage d'éventuels prix
              $row.find('.aBuyingPrice').val('');
              $row.find('.aPriceHTVA').val('');
            });
            $row.find('.aAccessory select').on("change",function(){
              var accessoryId =$(this).val();
                //récupère le bon index même si le tableau est désordonné
                accessoryId = getIndex(accessories, accessoryId);
                $row.find('.aBuyingPrice input').val(accessories[accessoryId].buyingPriceCatalog);
                $row.find('.aPriceHTVA input').val(accessories[accessoryId].sellingPriceCatalog);
                $row.find('.aPriceTVAC input').val(Math.round(accessories[accessoryId].sellingPriceCatalog*1.21*100)/100);
              });
          }
        })
      });


      //retrait pour main d'oeuvre
      $('#maintenanceManagementItem .manualWorkload .glyphicon-minus').unbind();
      $('#maintenanceManagementItem .manualWorkload .glyphicon-minus').click(function(){
        manualWorkloadNumber = $("#maintenanceManagementItem .manualWorkloadRow").length;
        if(manualWorkloadNumber > 0){
          $('#maintenanceManagementItem').find('.manualWorkloadNumber').html(manualWorkloadNumber*1 - 1);
          $('#maintenanceManagementItem').find('.manualWorkload tbody tr:last-child').slideUp().remove();
        }
      });

      //retrait pour accessoires
      $('#maintenanceManagementItem .maintenanceAccessories .glyphicon-minus').unbind();
      $('#maintenanceManagementItem .maintenanceAccessories .glyphicon-minus').click(function(){
        maintenanceAccessoriesNumber = $("#maintenanceManagementItem .accessoriesRow").length;
        if(maintenanceAccessoriesNumber > 0){
          $('#maintenanceManagementItem').find('.accessoriesNumber').html(maintenanceAccessoriesNumber*1 - 1);
          $('#maintenanceManagementItem').find('.maintenanceAccessories tbody tr:last-child').slideUp().remove();
        }
      });


    }
  });
});



function getCompaniesInMaintenances(companyName = null){
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
        });
        if(companyName == null){
          $("#widget-maintenanceManagement-form select[name=company]").val("");
          $('#widget-maintenanceManagement-form div[name=addExternalBikesDiv]').hide();
        }else{
          $("#widget-maintenanceManagement-form select[name=company]").val(companyName);
        }
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
      url: "api/maintenances",
      contentType: "application/json",
      type: "get",
      data: {
        action : 'listAllMaintenances',
        dateStart: dateStartString,
        dateEnd: dateEndString
      },
    },
    sAjaxDataProp: "maintenance",
    columns: [
    {
      title: "ID",
      data: "ID",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
        $(nTd).html('<a  data-target="#maintenanceManagementItem" name="'+sData+'" data-toggle="modal" class="showMaintenance" href="#">'+sData+'</a>');
      },
    },
    { title: "Client", data: "COMPANY_NAME" },
    { title: "Modèle", data: "MODEL" },
    { title: "Date de sortie planifié", data: "OUT_DATE_PLANNED",
      fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
        if(sData == null ||sData == '0'){
          $(nTd).html("N/A");
        }else{
          $(nTd).html(sData.shortDate());
        }
      }
    },
    { title: "Date", data: "DATE",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html(sData.shortDate());
    }
  },
  {title: "Statut", data: "STATUS",
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
  {title: "Type", data: "type",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      if(sData == "partage"){
        $(nTd).html("Leasing - Vélo partagé");

      }else if(sData == "personnel"){
        $(nTd).html("Leasing - Vélo personnel");

      }else if(sData == "externe"){
        $(nTd).html("Vélo externe");
      }
      else if(sData == "vendu"){
        $(nTd).html("Vélo vendu");
      }
      else if(sData == "undefined"){
        $(nTd).html("<span  style =\"color:red;\">Inconnu</span>");
      }
      else{
        $(nTd).html("<span  style =\"color:red;\">ERROR</span>");
      }
    },
  },
  { title: "Adresse", data: "ADDRESS",
  fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
    if(sData != null){
      $(nTd).html(sData);
    }else{
      $(nTd).html(oData.street+" "+oData.zip_code+" "+oData.town);
    }
    $(nTd).data('sort', new Date(sData).getTime());
  },
  },
  { title: "N° téléphone", data: "PHONE"},
  { title: "Facturation", data: "paid",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      if(sData=='OK'){
        $(nTd).html('<span class="text-green">OK</a>');
      }else{
        $(nTd).html('<span class="text-red">KO</a>');
      }
    },
  },
  { title: "", data: "ID",
    fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html('<a href="#" class="text-green editMaintenance" data-target="#maintenanceManagementItem" name="'+sData+'" data-toggle="modal">Modifier</a>');
    },
  }
  ],
  order: [[5, "asc"]],
  paging : false,
  columnDefs: [
    { width: "20%", "targets": 1},
    { width: "15%", "targets": 6}
  ]
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
        $('#widget-maintenanceManagement-form .manualWorkload tbody').html('');

        $('#widget-maintenanceManagement-form input[name=ID]').val(response.maintenance.id);
        $('#widget-maintenanceManagement-form .maintenanceManagementDeleteButton').attr('name', response.maintenance.id);
        $('#widget-maintenanceManagement-form select[name=velo]').val(response.maintenance.bike_id);
        $('#widget-maintenanceManagement-form select[name=company]').val(response.maintenance.company);
        $('#widget-maintenanceManagement-form input[name=model]').val(response.maintenance.model);
        $('#widget-maintenanceManagement-form select[name=status]').val(response.maintenance.status);
        $('#widget-maintenanceManagement-form input[name=dateMaintenance]').val(response.maintenance.dateMaintenance.substring(0,10));
        if(response.maintenance.dateOutPlanned){
          $('#widget-maintenanceManagement-form input[name=dateOutPlanned]').val(response.maintenance.dateOutPlanned.substring(0,10));
        }else{
          $('#widget-maintenanceManagement-form input[name=dateOutPlanned]').val("");
        }
        $('#widget-maintenanceManagement-form textarea[name=comment]').val(response.maintenance.comment);
        $('#widget-maintenanceManagement-form textarea[name=internalComment]').val(response.maintenance.internalComment);

        if(response.maintenance.clientWarned){
          $('#widget-maintenanceManagement-form input[name=clientWarned]').prop('checked', true);
        }else{
          $('#widget-maintenanceManagement-form input[name=clientWarned]').prop('checked', false);
        }

        if(response.maintenance.address=="8 Rue de la Brasserie, 4000 Liège"){
          $('#widget-maintenanceManagement-form input[name=maintenanceatKAMEO]').prop('checked', true);
          $('#widget-maintenanceManagement-form input[name=address]').fadeOut("slow");
          $('#widget-maintenanceManagement-form label[for=address]').fadeOut("slow");
        }else{
          $('#widget-maintenanceManagement-form input[name=maintenanceatKAMEO]').prop('checked', false);
          $('#widget-maintenanceManagement-form input[name=address]').fadeIn("slow");
          $('#widget-maintenanceManagement-form label[for=address]').fadeIn("slow");
        }
        $('#widget-maintenanceManagement-form input[name=address]').val(response.maintenance.address)

        response.maintenance.services.forEach(function(service){
          $('#widget-maintenanceManagement-form .manualWorkload tbody').append('<tr><td>'+service.CATEGORY+'</td><td>'+service.DESCRIPTION+'</td><td>'+service.DURATION+'</td><td>'+service.AMOUNT+' €</td><td>'+Math.round(service.AMOUNT*1.21*100)/100+' €</td></tr>');
          $('#widget-maintenanceManagement-form .manualWorkloadNumber').html(response.maintenance.services.length);
        })
        response.maintenance.accessories.forEach(function(accessory){
          $('#widget-maintenanceManagement-form .accessoriesTable tbody').append('<tr><td>'+traduction['accessoryCategories_'+accessory.CATEGORY]+'</td><td>'+accessory.BRAND+' - '+ accessory.MODEL+'</td><td>'+accessory.BUYING_PRICE+'</td><td>'+accessory.AMOUNT+' €</td><td>'+Math.round(accessory.AMOUNT*1.21*100)/100+' €</td></tr>');
          $('#widget-maintenanceManagement-form .accessoriesNumber').html(response.maintenance.accessories.length);
        })

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

        $('a.deleteFile').off();
        $('a.deleteFile').click(function(){
          $.notify(
            {
              message: "Fichier supprimé",
            },
            {
              type: "success",
            }
          );
          var fileName=this.name;
          $(this).closest('div').remove();
          $.ajax({
            url:'api/maintenances',
            data:{'action' : 'deleteImage', 'url' : 'images_entretiens/'+fileName},
            method:'POST',
            success:function(response){
              if(response.response == "error"){
                $.notify({
                  message: response.message,
                }, {
                  type: "danger",
                });
              }
            }
          });
        });
      }
    }
  });
}

$('#widget-maintenanceManagement-form input[name=maintenanceatKAMEO]').change(function(){
  if($('#widget-maintenanceManagement-form input[name=maintenanceatKAMEO]').prop('checked')){
    $('#widget-maintenanceManagement-form input[name=address]').fadeOut('slow');
    $('#widget-maintenanceManagement-form label[for=address]').fadeOut('slow');
  }else{
    $('#widget-maintenanceManagement-form input[name=address]').fadeIn('slow');
    $('#widget-maintenanceManagement-form label[for=address]').fadeIn('slow');
  }
})

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
  $('#widget-maintenanceManagement-form .manualWorkload tbody').html('');
  $('#widget-maintenanceManagement-form .accessoriesTable tbody').html('');
  get_maintenance(this.name);
  $("#widget-maintenanceManagement-form input[name=action]").val("update");
  $("#widget-maintenanceManagement-form input").attr("readonly", true);
  $("#widget-maintenanceManagement-form input[name=address]").attr("readonly", false);
  $("#widget-maintenanceManagement-form input[name=dateMaintenance]").attr("readonly", false);
  $("#widget-maintenanceManagement-form input[name=dateOutPlanned]").attr("readonly", false);
  $("#widget-maintenanceManagement-form select").attr("disabled", false);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", false);
  $(".maintenanceManagementTitle").html("Éditer un entretien");
  $("#widget-maintenanceManagement-form button").fadeIn("slow");
  $("#widget-maintenanceManagement-form div[name=file]").fadeIn("slow");
  $("#widget-maintenanceManagement-form div[name=internalFile]").fadeIn("slow");
  $("#widget-maintenanceManagement-form button[name=delete]").fadeIn("slow");
  $("#widget-maintenanceManagement-form div[name=status]").fadeIn("slow");
  $("#widget-maintenanceManagement-form div[name=id]").fadeIn("slow");
});

$('body').on('click', '.showMaintenance',function(){
  get_maintenance(this.name);
  $("#widget-maintenanceManagement-form input").attr("readonly", true);
  $("#widget-maintenanceManagement-form select").attr("disabled", true);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", true);
  $(".maintenanceManagementTitle").html("Vu sur un entretien");
  $("#widget-maintenanceManagement-form div[name=status]").fadeIn("slow");
  $("#widget-maintenanceManagement-form button").hide();
  $("#widget-maintenanceManagement-form div[name=file]").hide();
  $("#widget-maintenanceManagement-form button[name=delete]").hide();
  $("#widget-maintenanceManagement-form div[name=id]").fadeIn("slow");
});

$('body').on('click', '.addMaintenance',function(){
  $("#widget-maintenanceManagement-form div[name=image]").remove();
  $("#widget-maintenanceManagement-form div[name=internalImages]").remove();
  $('#widget-maintenanceManagement-form .manualWorkload tbody').html('');
  $('#widget-maintenanceManagement-form .accessoriesTable tbody').html('');
  $('#widget-maintenanceManagement-form').trigger('reset');
  $('#widget-maintenanceManagement-form select[name=company]').val("");
  $('#widget-maintenanceManagement-form select[name=velo]').val("");
  $('#widget-maintenanceManagement-form .manualWorkloadNumber').html(0);
  $('#widget-maintenanceManagement-form .accessoriesNumber').html(0);
  $("#widget-maintenanceManagement-form input[name=action]").val("add");
  $("#widget-maintenanceManagement-form input").attr("readonly", false);
  $("#widget-maintenanceManagement-form select").attr("disabled", false);
  $("#widget-maintenanceManagement-form textarea").attr("readonly", false);
  $("#widget-maintenanceManagement-form select[name=velo]").attr("disabled", false);
  $("#widget-maintenanceManagement-form input[name=dateMaintenance]").attr("readonly", false);
  $("#widget-maintenanceManagement-form input[name=dateOutPlanned]").attr("readonly", false);
  $(".maintenanceManagementTitle").html("Ajouter un entretien");
  $("#widget-maintenanceManagement-form button").fadeIn("slow");
  $("#widget-maintenanceManagement-form div[name=id]").fadeOut('slow');
  $("#widget-maintenanceManagement-form div[name=file]").fadeOut('slow');
  $("#widget-maintenanceManagement-form div[name=internalFile]").fadeOut('slow');
  $("#widget-maintenanceManagement-form button[name=delete]").fadeOut('slow');
  $("#widget-maintenanceManagement-form input[name=maintenanceatKAMEO]").prop("checked", true);
  $("#widget-maintenanceManagement-form label[for=address]").fadeOut("slow");
  $("#widget-maintenanceManagement-form input[name=address]").fadeOut("slow");
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


$('#widget-maintenanceManagement-form select[name=company]').change(function(){
  getBikesToMaintenance();
  $('#widget-maintenanceManagement-form div[name=addExternalBikesDiv]').fadeIn("slow");

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
      var extension=/[^.]*$/.exec(response.fileName)[0];
      if(response.folderName=='publicFile'){
        if(extension=="pdf"){
          $("#widget-maintenanceManagement-form div[name=images]").append('<div class="col-md-4" name="image">\
            <embed src="images_entretiens/'+response.ID+'/publicFile/'+response.fileName+'" height="100%" />\
            <a class="button small green button-3d rounded icon-left" href="images_entretiens/'+response.ID+'/publicFile/'+response.fileName+'" target="_blank"><i class="fa fa-paper-plane"></i>Ouvrir </a>\
            <a class="button small red button-3d rounded icon-left deleteFile" name="'+response.ID+'/publicFile/'+response.fileName+'"><i class="fa fa-paper-plane"></i>Supprimer le fichier </a></div>');

        }else{
          $("#widget-maintenanceManagement-form div[name=images]").append('<div class="col-md-4" name="image">\
            <img src="images_entretiens/'+response.ID+'/publicFile/'+response.fileName+'">\
            <a class="button small red button-3d rounded icon-left deleteFile" name="'+response.ID+'/publicFile/'+response.fileName+'"> \
            <i class="fa fa-paper-plane"></i>Supprimer l\'image </a></div>');
        }
      }else{
        if(extension=="pdf"){
          $("#widget-maintenanceManagement-form div[name=internalImages]").append('<div class="col-md-4" name="image">\
            <embed src="images_entretiens/'+response.ID+'/internalFile/'+response.fileName+'" height="100%" />\
            <a class="button small green button-3d rounded icon-left" href="images_entretiens/'+response.ID+'/internalFile/'+response.fileName+'" target="_blank"><i class="fa fa-paper-plane"></i>Ouvrir </a>\
            <a class="button small red button-3d rounded icon-left deleteFile" name="'+response.ID+'/internalFile/'+response.fileName+'"><i class="fa fa-paper-plane"></i>Supprimer le fichier </a></div>');

        }else{
          $("#widget-maintenanceManagement-form div[name=internalImages]").append('<div class="col-md-4" name="image">\
            <img src="images_entretiens/'+response.ID+'/internalFile/'+response.fileName+'">\
            <a class="button small red button-3d rounded icon-left deleteFile" name="'+response.ID+'/internalFile/'+response.fileName+'"> \
            <i class="fa fa-paper-plane"></i>Supprimer l\'image </a></div>');
        }
      }
      $('a.deleteFile').off();
      $('a.deleteFile').click(function(){
        $.notify(
          {
            message: "Fichier supprimé",
          },
          {
            type: "success",
          }
        );
        var fileName=this.name;
        $(this).closest('div').remove();
        $.ajax({
          url:'api/maintenances',
          data:{'action' : 'deleteImage', 'url' : 'images_entretiens/'+fileName},
          method:'POST',
          success:function(response){
            if(response.response == "error"){
              $.notify({
                message: response.message,
              }, {
                type: "danger",
              });
            }
          }
        });
      });
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
          $(nTd).html('<a target="_blank" href="devisEntretien/'+oData.DATE_DEVIS.substr(0,10)+'_'+oData.COMPANY+'_'+sData+'.pdf">'+sData+'</a>');
        },
      },
      { title: "Statut", data: "STATUS" },
      {
        title: "Date",
        data: "DATE_DEVIS",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(sData.substr(0,10).shortDate());
        },
      },

      { title: "Société", data: "COMPANY" },
      { title: "Validité", data: "VALID" },
      {
        title: "Prix HTVA",
        data: "AMOUNT_HTVA",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(sData+" €");
        },
      },
      {
        title: "Prix TVAC",
        data: "AMOUNT_TVAC",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(sData+" €");
        },
      }
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
  }
});

/////////////////////////////////////////////////////////////////////////
//////////////::ACCESSOIRES


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

  $('.generateBillAccessoriesDevis .glyphicon-plus').unbind();
  $('.generateBillAccessoriesDevis .glyphicon-plus').click(function(){
    //gestion accessoriesNumber
    accessoriesNumber = $("#addDevis").find('.accessoriesNumber').html()*1+1;
    $('#addDevis').find('.accessoriesNumber').html(accessoriesNumber);
    $('#accessoriesNumber').val(accessoriesNumber);

    //ajout d'une ligne au tableau des accessoires
    $('#addDevis').find('.otherCostsAccesoiresTable tbody')
    .append(`<tr class="otherCostsAccesoiresTable`+(accessoriesNumber)+` accessoriesRow form-group">
      <td class="aLabel"></td>
      <td class="aCategory"><select name="accessoryCategory[]" class="selectCategory form-control required"></select></td>
      <td class="aAccessory"></td>
      <td class="aBuyingPrice"></td>
      <td contenteditable='true' class="aPriceHTVA"></td>
      <td><input type="number" class="accessoryFinalPrice hidden" name="accessoryFinalPrice[]" /></td>
      </tr>`);


    categories.forEach(function(category){
      $('#addDevis .otherCostsAccesoiresTable .accessoriesRow:last-child .aCategory select').append(
        new Option(category.name, category.id)
      )
    });
    $('#addDevis .otherCostsAccesoiresTable .accessoriesRow:last-child .aCategory select').val('');

    //label selon la langue
    $('#addDevis').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aLabel')
    .append('<label>Accessoire '+ accessoriesNumber +'</label>');

    //select Accessoire
    $('#addDevis').find('.otherCostsAccesoiresTable'+(accessoriesNumber)+'>.aAccessory')
    .append('<select name="accessoryID[]" id="selectAccessory'+
      accessoriesNumber+
      '"class="selectAccessory form-control required"></select>');

    checkMinus('.generateBillAccessoriesDevis','.accessoriesNumber');

    //on change de la catégorie
    $('.generateBillAccessoriesDevis').find('.selectCategory').on("change",function(){
      var $row = $(this).closest('.accessoriesRow');
      var categoryId = $(this).val();
      var accessoriesOption = "<option hidden disabled selected value>Veuillez choisir un accesoire</option>";

      //ne garde que les accessoires de cette catégorie
      accessories.forEach((accessory) => {
        if (categoryId == accessory.categoryId) {
          accessoriesOption += '<option value="'+accessory.id+'">'+accessory.model+'</option>';
        }
      });
      //place les accessoires dans le select
      $row.find('.selectAccessory').html(accessoriesOption);
      //retire l'affichage d'éventuels prix
      $row.find('.aBuyingPrice').html('');
      $row.find('.aPriceHTVA').html('');
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
