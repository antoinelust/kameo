$(".fleetmanager").click(function () {
  var dateStart = new Date();
  var dateEnd = new Date();
  dateEnd.setMonth(dateEnd.getMonth()+1);
  var dateStart = new Date();
  $("#planningsListing input[name=dateStart]").val(get_date_string(dateStart));
  var dateEnd = new Date();
  dateEnd.setMonth(dateEnd.getMonth()+1);
  $("#planningsListing input[name=dateEnd]").val(get_date_string(dateEnd));
});


$('#planningsListing').on('shown.bs.modal', function(event){
  listPlannings();
});

$("#planningsListing input[name=dateStart], #planningsListing input[name=dateEnd]").change(function(){
  listPlannings();
})

function listPlannings(){
  $("#planningsListingTable").dataTable({
    destroy: true,
    paging: false,
    autoWidth: true,
    ajax: {
      url: "api/plannings",
      contentType: "application/json",
      type: "get",
      data: {
        action : 'listPlannings',
        dateStart : $("#planningsListing input[name=dateStart]").val(),
        dateEnd : $("#planningsListing input[name=dateEnd]").val()
      },
    },
    sAjaxDataProp: "plannings",
    columns: [
      { title: "", data: "DATE",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol){
          $(nTd).html('<a href="#" class="text-green" data-target="#planningManagement" data-date="'+sData+'" data-action="update" data-toggle="modal">'+sData+'</a>');
        }
      },
      { title: "Nombre de livraison", data: "nombreCommande" },
      { title: "Nombre d'entretien", data: "nombreEntretiens" },
      { title: "", data: "status",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol){
          if(sData=="confirmed"){
            $(nTd).html('<span class="text-green">CONFIRMED</span>');
          }else{
            $(nTd).html(sData);
          }
        },
      }
    ]
  });
}


$('#planningManagement').on('shown.bs.modal', function(event){
  var date=$(event.relatedTarget).data('date');
  $('#planningManagement input[name=date]').val(date);
  $.ajax({
    url: "api/plannings",
    type: "get",
    data: {action: 'getPlanning', date: date },
    success: function (response){
      if (response.response == "error") {
        console.log(response.message);
      }else{
        $('#planningManagement span[name=dateTitle]').html(date);
        $('#planningManagement .planningDetails').html("");

        if(response.status=='new'){
          $('#planningManagement a[name=cancel_tour]').fadeOut();
          $('#planningManagement input[name=startHourTour]').attr('readonly', false);
          $('#planningManagement input[name=startAddress]').attr('readonly', false);
          $('#planningManagement input[name=endHourTour]').attr('readonly', false);
          $('#planningManagement input[name=endAddress]').attr('readonly', false);
          var i=1;
          response.internalEntretiens.forEach(function(entretien){
            var externalComment = (entretien.COMMENT != null && entretien.COMMENT != '') ? entretien.COMMENT : "N/A";
            var internalComment = (entretien.INTERNAL_COMMENT != null && entretien.INTERNAL_COMMENT != '') ? entretien.INTERNAL_COMMENT : "N/A";
            $('#planningManagement .planningDetails').append("<div class='col-md-12 newRow d-flex' style='border: 1px solid grey; margin-top: 10px; margin-bottom: 10px; cursor: pointer'>"+
              '<div class="col-md-1" style="margin:0px; padding:0px">'+
                '<input type="number" name="order[]" class="form-control required order" value="'+i+'" disabled><br>'+
                '<button type="button" class="btn btn-secondary moveUp"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"></path></svg></button>'+
                '<button type="button" class="btn btn-secondary moveDown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path></svg></button>'+
              "</div>"+
              "<div class='col-md-11'>"+
                "<div class='col-md-8' style='background-color: #3cb395'><strong>Informations</strong></div>"+
                "<div class='col-md-4'style='background-color: #3cb395'><strong>Heure de passage</strong></div>"+
                "<div class='col-md-8'>"+
                  "<div class='col-md-6'>"+
                    "<strong class='text-green'>Entretien sur vélo interne n°"+entretien.ID+"</strong><br>"+
                    "<strong>Société : </strong>"+entretien.COMPANY_NAME+"<br>"+
                    "<strong>E-mail : </strong>"+entretien.EMAIL+"<br>"+
                    "<strong>Numéro de téléphone : </strong>"+entretien.PHONE+"<br>"+
                    "<strong>Numéro de cadre : </strong> "+entretien.FRAME_REFERENCE+"</div>"+
                  "<div class='col-md-6'><strong>Adresse : </strong><input type='text' class='form-control address required' name='address[]' value='"+entretien.ADDRESS+"'<br>"+
                    "<strong>Temps de déplacement : </strong> <span class='travelTime'></span> min<br><input type='number' name='deplacement[]' class='form-control required hidden'>"+
                    "<strong>Temps d'exécution : </strong><input type='number' name='execution[]' class='form-control execution' value='20'>"+
                  "</div>"+
                "</div>"+
                "<div class='col-md-4'>"+
                "Heure d'arrivée : <input type='time' name='startHour[]' class='form-control required startHour' readonly>"+
                "Heure de fin : <input type='time' name='endHour[]' class='form-control required endHour' readonly>"+
                "</div>"+
                "<div class='separator'></div>"+
                "<div class='col-md-12'><strong>Commentaire pour le client :</strong> "+externalComment+"<br><br><strong>Commentaire interne :</strong> "+internalComment+"</div>"+
                "<input type='text' class='hidden' value='internalMaintenance' name='type[]'><input type='text' class='hidden' name='id[]' value='"+entretien.ID+"'>"+
              "</div>");
            i++;
          });
          response.externalEntretiens.forEach(function(entretien){
            var externalComment = (entretien.COMMENT != null && entretien.COMMENT != '') ? entretien.COMMENT : "N/A";
            var internalComment = (entretien.INTERNAL_COMMENT != null && entretien.INTERNAL_COMMENT != '') ? entretien.INTERNAL_COMMENT : "N/A";

            $('#planningManagement .planningDetails').append("<div class='col-md-12 newRow d-flex' style='border: 1px solid grey; margin-top: 10px; margin-bottom: 10px; cursor: pointer'>"+
              '<div class="col-md-1" style="margin:0px; padding:0px">'+
                '<input type="number" name="order[]" class="form-control required order" value="'+i+'" disabled><br>'+
                '<button type="button" class="btn btn-secondary moveUp"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"></path></svg></button>'+
                '<button type="button" class="btn btn-secondary moveDown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path></svg></button>'+
              "</div>"+
              "<div class='col-md-11'>"+
                "<div class='col-md-8' style='background-color: #3cb395'><strong>Informations</strong></div>"+
                "<div class='col-md-4'style='background-color: #3cb395'><strong>Heure de passage</strong></div>"+
                "<div class='col-md-8'>"+
                  "<div class='col-md-6'>"+
                    "<strong class='text-green'>Entretien externe n°"+entretien.ID+"</strong><br>"+
                    "<strong>Société : </strong>"+entretien.COMPANY_NAME+"<br>"+
                    "<strong>Numéro de téléphone : </strong>"+entretien.PHONE+"<br>"+
                    "<strong>Numéro de cadre : </strong> "+entretien.FRAME_REFERENCE+"</div>"+
                  "<div class='col-md-6'><strong>Adresse : </strong><input type='text' class='form-control address required' name='address[]' value='"+entretien.ADDRESS+"'<br>"+
                    "<strong>Temps de déplacement : </strong> <span class='travelTime'></span> min<br><input type='number' name='deplacement[]' class='form-control required hidden'>"+
                    "<strong>Temps d'exécution : </strong><input type='number' name='execution[]' class='form-control execution' value='20'>"+
                  "</div>"+
                "</div>"+
                "<div class='col-md-4'>"+
                "Heure d'arrivée : <input type='time' name='startHour[]' class='form-control required startHour' readonly>"+
                "Heure de fin : <input type='time' name='endHour[]' class='form-control required endHour' readonly>"+
                "</div>"+
                "<div class='separator'></div>"+
                "<div class='col-md-12'><strong>Commentaire pour le client :</strong> "+externalComment+"<br><br><strong>Commentaire interne :</strong> "+internalComment+"</div>"+
                "<input type='text' class='hidden' value='externalMaintenance' name='type[]'><input type='text' class='hidden' name='id[]' value='"+entretien.ID+"'>"+
              "</div>");
            i++;
          });
          response.orders.forEach(function(order){
            $('#planningManagement .planningDetails').append("<div class='col-md-12 newRow d-flex' style='border: 1px solid grey; margin-top: 10px; margin-bottom: 10px; cursor: pointer'>"+
              '<div class="col-md-1" style="margin:0px; padding:0px">'+
                '<input type="number" name="order[]" class="form-control required order" value="'+i+'" disabled><br>'+
                '<button type="button" class="btn btn-secondary moveUp"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"></path></svg></button>'+
                '<button type="button" class="btn btn-secondary moveDown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path></svg></button>'+
              "</div>"+
              "<div class='col-md-11'>"+
                "<div class='col-md-8' style='background-color: #3cb395'><strong>Informations</strong></div>"+
                "<div class='col-md-4'style='background-color: #3cb395'><strong>Heure de passage</strong></div>"+
                "<div class='col-md-8'>"+
                  "<div class='col-md-6'>"+
                    "<strong class='text-green'>Commande n°"+order.ID+"</strong><br>"+
                    "<strong>Société : </strong>"+order.COMPANY_NAME+"<br>"+
                    "<strong>E-mail : </strong>"+order.EMAIL+"<br>"+
                    "<strong>Numéro de téléphone : </strong>"+order.PHONE+"<br>"+
                    "<strong>Numéro de cadre : </strong> "+order.FRAME_REFERENCE+"</div>"+
                  "<div class='col-md-6'><strong>Adresse : </strong><input type='text' class='form-control address required' name='address[]' value='"+order.ADDRESS+"'<br>"+
                    "<strong>Temps de déplacement : </strong> <span class='travelTime'></span> min<br><input type='number' name='deplacement[]' class='form-control  required hidden'>"+
                    "<strong>Temps d'exécution : </strong><input type='number' name='execution[]' class='form-control execution' value='20'>"+
                  "</div>"+
                "</div>"+
                "<div class='col-md-4'>"+
                "Heure d'arrivée : <input type='time' name='startHour[]' class='form-control required startHour' readonly>"+
                "Heure de fin : <input type='time' name='endHour[]' class='form-control required endHour' readonly>"+
                "</div>"+
                "<input type='text' class='hidden' value='order' name='type[]'><input type='text' class='hidden' name='id[]' value='"+order.ID+"'>"+
              "</div>");
              i++;
          });
        }else{
          $('#planningManagement a[name=cancel_tour]').fadeIn();
          $($("#planningManagement input[name='startHourTour']")).val(response.steps[0].PLANNED_START_HOUR);
          $('#planningManagement input[name=startHourTour]').attr('readonly', true);
          $('#planningManagement input[name=startAddress]').attr('readonly', true);
          $('#planningManagement input[name=endHourTour]').css("background-color", "");
          $('#planningManagement input[name=endHourTour]').attr('readonly', true);
          $('#planningManagement input[name=endAddress]').attr('readonly', true);

          var i=1;
          response.steps.forEach(function(item){
            if(item.ITEM_TYPE=='internalMaintenance'){
              var externalComment = (item.COMMENT != null && item.COMMENT != '') ? item.COMMENT : "N/A";
              var internalComment = (item.INTERNAL_COMMENT != null && item.INTERNAL_COMMENT != '') ? item.INTERNAL_COMMENT : "N/A";

              $('#planningManagement .planningDetails').append(
              "<div class='col-md-12 newRow d-flex' style='border: 1px solid grey; margin-top: 10px; margin-bottom: 10px; cursor: pointer'>"+
                '<div class="col-md-1" style="margin:0px; padding:0px">'+
                  '<input type="number" name="order[]" class="form-control required order" value="'+(item.STEP)+'" disabled><br>'+
                "</div>"+
                "<div class='col-md-8'>"+
                  "<strong class='text-green'>Entretien n°"+item.ITEM_ID+"</strong><br><br>"+
                  "<strong class='text-green'>Informations</strong><br>"+
                  "<strong>Société : </strong>"+item.COMPANY_NAME+"<br>"+
                  "<strong>Nom : </strong>"+item.NAME+"<br>"+
                  "<strong>E-mail : </strong>"+item.EMAIL+"<br>"+
                  "<strong>Numéro de téléphone : </strong>"+item.PHONE+"<br>"+
                  "<strong>Numéro de cadre : </strong> "+item.FRAME_REFERENCE+"<br>"+
                  "<strong>Adresse : </strong>"+item.ADDRESS+"<br>"+
                  "<strong>Temps de déplacement : </strong> <span class='travelTime'>"+item.MOVING_TIME+"</span> min<br>"+
                  "<strong>Temps d'exécution : </strong>"+item.EXECUTION_TIME+"min<br><br>"+
                  '<a class="button small green button-3d rounded icon-left" href="https://waze.com/ul?q='+item.ADDRESS+'"><span>Lancer WAZE</span></a><br><br>'+
                  "<strong class='text-green'>Heures de passage</strong><br><br>"+
                  "<div class='col-md-4'>Heure d'arrivée : <input type='time' name='startHour[]' class='form-control required' value='"+item.PLANNED_START_HOUR+"' readonly></div>"+
                  "<div class='col-md-4'>Heure de fin : <input type='time' name='endHour[]' class='form-control required' value='"+item.PLANNED_END_HOUR+"' readonly></div>"+
                "</div>"+
                "<div class='col-md-3'>"+
                "<strong class='text-green'>Execution</strong><br><br>"+
                "<label for='validate_task_arrival'>Je suis arrivé à : </label>"+
                "<input type='time' class='form-control validate_task_arrival' name='validate_task_arrival[]' value='"+item.REAL_START_HOUR+"'>"+
                "<label for='validate_task_departure'>Je suis parti à : </label>"+
                "<input type='time' class='form-control validate_task_departure' name='validate_task_departure[]' value='"+item.REAL_END_HOUR+"'>"+
                "<a href='#' class='validate_task button small green button-3d rounded icon-right' data-id='"+item.ID+"'>Valider</a>"+
                "</div>"+
                "<div class='separator'></div>"+
                "<strong class='text-green'>Informations spécifiques entretien</strong><br><br>"+
                "<div class='col-md-12'><strong>Commentaire pour le client :</strong> "+externalComment+"<br><br><strong>Commentaire interne :</strong> "+internalComment+"<br><br></div>"+
                "</div>");
            };
            if(item.ITEM_TYPE=='externalMaintenance'){
              var externalComment = (item.COMMENT != null && item.COMMENT != '') ? item.COMMENT : "N/A";
              var internalComment = (item.INTERNAL_COMMENT != null && item.INTERNAL_COMMENT != '') ? item.INTERNAL_COMMENT : "N/A";
              $('#planningManagement .planningDetails').append(
              "<div class='col-md-12 newRow d-flex' style='border: 1px solid grey; margin-top: 10px; margin-bottom: 10px; cursor: pointer'>"+
                  '<div class="col-md-1" style="margin:0px; padding:0px">'+
                    '<input type="number" name="order[]" class="form-control required order" value="'+(item.STEP)+'" disabled><br>'+
                  "</div>"+
                  "<div class='col-md-8'>"+
                    "<strong class='text-green'>Entretien sur vélo externe n°"+item.ITEM_ID+"</strong><br><br>"+
                    "<strong class='text-green'>Informations</strong><br>"+
                    "<strong>Société : </strong>"+item.COMPANY_NAME+"<br>"+
                    "<strong>Nom : </strong>"+item.NAME+"<br>"+
                    "<strong>E-mail : </strong>"+item.EMAIL+"<br>"+
                    "<strong>Numéro de téléphone : </strong>"+item.PHONE+"<br>"+
                    "<strong>Numéro de cadre : </strong> "+item.FRAME_REFERENCE+"<br>"+
                    "<strong>Adresse : </strong>"+item.ADDRESS+"<br>"+
                    "<strong>Temps de déplacement : </strong> <span class='travelTime'>"+item.MOVING_TIME+"</span> min<br>"+
                    "<strong>Temps d'exécution : </strong>"+item.EXECUTION_TIME+"min<br><br>"+
                    '<a class="button small green button-3d rounded icon-left" href="https://waze.com/ul?q='+item.ADDRESS+'"><span>Lancer WAZE</span></a><br><br>'+
                    "<strong class='text-green'>Heures de passage</strong><br><br>"+
                    "<div class='col-md-4'>Heure d'arrivée : <input type='time' name='startHour[]' class='form-control required' value='"+item.PLANNED_START_HOUR+"' readonly></div>"+
                    "<div class='col-md-4'>Heure de fin : <input type='time' name='endHour[]' class='form-control required' value='"+item.PLANNED_END_HOUR+"' readonly></div>"+
                  "</div>"+
                  "<div class='col-md-3'>"+
                  "<strong class='text-green'>Execution</strong><br><br>"+
                  "<label for='validate_task_arrival'>Je suis arrivé à : </label>"+
                  "<input type='time' class='form-control validate_task_arrival' name='validate_task_arrival[]' value='"+item.REAL_START_HOUR+"'>"+
                  "<label for='validate_task_departure'>Je suis parti à : </label>"+
                  "<input type='time' class='form-control validate_task_departure' name='validate_task_departure[]' value='"+item.REAL_END_HOUR+"'>"+
                  "<a href='#' class='validate_task button small green button-3d rounded icon-right' data-id='"+item.ID+"'>Valider</a>"+
                  "</div>"+
                  "<div class='separator'></div>"+
                  "<strong class='text-green'>Informations spécifiques entretien</strong><br><br>"+
                  "<div class='col-md-12'><strong>Commentaire pour le client :</strong> "+externalComment+"<br><br><strong>Commentaire interne :</strong> "+internalComment+"<br><br></div>"+
                "</div>");
            };
            if(item.ITEM_TYPE=='order'){
              $('#planningManagement .planningDetails').append(
                "<div class='col-md-12 newRow d-flex' style='border: 1px solid grey; margin-top: 10px; margin-bottom: 10px; cursor: pointer'>"+
                  '<div class="col-md-1" style="margin:0px; padding:0px">'+
                    '<input type="number" name="order[]" class="form-control required order" value="'+(item.STEP)+'" disabled><br>'+
                    '<input type="text" class="form-control required stepType hidden" value="'+(item.ITEM_TYPE)+'">'+
                  "</div>"+
                  "<div class='col-md-8'>"+
                    "<strong class='text-green'>Commande n°"+item.ITEM_ID+"</strong><br><br>"+
                    "<strong class='text-green'>Informations</strong><br>"+
                    "<strong>Société : </strong>"+item.COMPANY_NAME+"<br>"+
                    "<strong>Nom : </strong>"+item.NAME+"<br>"+
                    "<strong>E-mail : </strong>"+item.EMAIL+"<br>"+
                    "<strong>Numéro de téléphone : </strong>"+item.PHONE+"<br>"+
                    "<strong>Numéro de cadre : </strong> "+item.FRAME_REFERENCE+"<br>"+
                    "<strong>Adresse : </strong>"+item.ADDRESS+"<br>"+
                    "<strong>Temps de déplacement : </strong> <span class='travelTime'>"+item.MOVING_TIME+"</span> min<br>"+
                    "<strong>Temps d'exécution : </strong>"+item.EXECUTION_TIME+"min<br><br>"+
                    '<a class="button small green button-3d rounded icon-left" href="https://waze.com/ul?q='+item.ADDRESS+'"><span>Lancer WAZE</span></a><br><br>'+
                    "<strong class='text-green'>Heures de passage</strong><br><br>"+
                    "<div class='col-md-4'>Heure d'arrivée : <input type='time' name='startHour[]' class='form-control required' value='"+item.PLANNED_START_HOUR+"' readonly></div>"+
                    "<div class='col-md-4'>Heure de fin : <input type='time' name='endHour[]' class='form-control required' value='"+item.PLANNED_END_HOUR+"' readonly></div>"+
                  "</div>"+
                  "<div class='col-md-3'>"+
                  "<strong class='text-green'>Execution</strong><br><br>"+
                  "<label for='validate_task_arrival'>Je suis arrivé à : </label>"+
                  "<input type='time' class='form-control validate_task_arrival' name='validate_task_arrival[]' value='"+item.REAL_START_HOUR+"'>"+
                  "<label for='validate_task_departure'>Je suis parti à : </label>"+
                  "<input type='time' class='form-control validate_task_departure' name='validate_task_departure[]' value='"+item.REAL_END_HOUR+"'>"+
                  "<a href='#' class='validate_task button small green button-3d rounded icon-right' data-id='"+item.ID+"'>Valider</a>"+
                  "</div>"+
                "</div>");
            };
            if(item.ITEM_TYPE=='additionalStep'){
              $('#planningManagement .planningDetails').append("<div class='col-md-12 newRow d-flex' style='border: 1px solid grey; margin-top: 10px; margin-bottom: 10px; cursor: pointer'>"+
                '<div class="col-md-1" style="margin:0px; padding:0px">'+
                  '<input type="number" name="order[]" class="form-control required order" value="'+(item.STEP)+'" disabled><br>'+
                "</div>"+
                "<div class='col-md-8'>"+
                  "<strong class='text-green'>Etape manuelle</strong><br><br>"+
                  "<strong class='text-green'>Informations</strong><br>"+
                  "<strong>Description : </strong>"+item.DESCRIPTION+"<br>"+
                  "<strong>Adresse : </strong>"+item.ADDRESS+"<br>"+
                  "<strong>Temps de déplacement : </strong> <span class='travelTime'>"+item.MOVING_TIME+"</span> min<br>"+
                  "<strong>Temps d'exécution : </strong>"+item.EXECUTION_TIME+"min<br><br>"+
                  '<a class="button small green button-3d rounded icon-left" href="https://waze.com/ul?q='+item.ADDRESS+'"><span>Lancer WAZE</span></a><br><br>'+
                  "<strong class='text-green'>Heures de passage</strong><br><br>"+
                  "<div class='col-md-4'>Heure d'arrivée : <input type='time' name='startHour[]' class='form-control required' value='"+item.PLANNED_START_HOUR+"' readonly></div>"+
                  "<div class='col-md-4'>Heure de fin : <input type='time' name='endHour[]' class='form-control required' value='"+item.PLANNED_END_HOUR+"' readonly></div>"+
                "</div>"+
                "<div class='col-md-3'>"+
                "<strong class='text-green'>Execution</strong><br><br>"+
                "<label for='validate_task_arrival'>Je suis arrivé à : </label>"+
                "<input type='time' class='form-control validate_task_arrival' name='validate_task_arrival[]' value='"+item.REAL_START_HOUR+"'>"+
                "<label for='validate_task_departure'>Je suis parti à : </label>"+
                "<input type='time' class='form-control validate_task_departure' name='validate_task_departure[]' value='"+item.REAL_END_HOUR+"'>"+
                "<a href='#' class='validate_task button small green button-3d rounded icon-right' data-id='"+item.ID+"'>Valider</a>"+
                "</div>");
            };
            $($("#planningManagement input[name='endAddress']")).val(response.steps[response.steps.length-1].ADDRESS);
            $($("#planningManagement input[name='endHourTour']")).val(response.steps[response.steps.length-1].PLANNED_START_HOUR);
            $($("#planningManagement span[name='endPointDuration']")).val(response.steps[response.steps.length-1].MOVING_TIME);

          });

        }
        $('#planningManagement .validate_task').off();
        $('#planningManagement .validate_task').click(function(){
          $.ajax({
            url: "api/plannings",
            type: "post",
            data: {action: "confirmTask", id: $(this).data('id'), arrival: $(this).parent().find('.validate_task_arrival').val(), departure: $(this).parent().find('.validate_task_departure').val()},
            success: function (response) {
              $.notify(
                {
                  message: response.message,
                },
                {
                  type: "success",
                }
              );
            }
          });
        })


        $("#planningManagement .moveUp").click(function(){
          var itemlist = $('#planningManagement .newRow');
          selected= $(this).closest('.newRow').index();
          if(selected>0)
          {
            jQuery($(itemlist).eq(selected-1)).before(jQuery($(itemlist).eq(selected)));
          }
        });

        $("#planningManagement .moveDown").click(function(){
          var itemlist = $('#planningManagement .newRow');
          selected= $(this).closest('.newRow').index();
          if(selected<($('#planningManagement .newRow').length - 1))
          {
             jQuery($(itemlist).eq(selected+1)).after(jQuery($(itemlist).eq(selected)));
          }
        });
      }
    }
  });
});

$('a[name=get_tour_travel_time]').click(function(){
  get_tour_travel_time();
})

$('a[name=cancel_tour]').click(function(){
  $.ajax({
    url: "api/plannings",
    type: "post",
    data: {action: "delete", date:$('#widget-form-planning span[name=dateTitle]').html()},
    success: function (response) {
      $('#planningManagement').modal('toggle');
      listPlannings();
    }
  });
})

var get_tour_travel_time = function(num){
  var i= num || 0; // uses i if it's set, otherwise uses 0
  if(i <= $('#planningManagement .address').length) {
    $('#planningManagement .newRow .order').eq(i).val(i+1);
    if(i<$('#planningManagement .address').length){
      var hourTemp = new Date($('#planningManagement span[name=dateTitle]').html());
      if(i==0){
        var startHour = $('#planningManagement input[name=startHourTour]').val();
        var startingPoint=$('#planningManagement input[name=startAddress]').val();
      }else{
        var startHour = $('#planningManagement .endHour').eq(i-1).val();
        var startingPoint=$('#planningManagement .address')[i-1].value;
      }
      var nextPoint=$('#planningManagement .address')[i].value;
      var hour = ('0'+startHour.split(':')[0]).slice(-2);
      var min = ('0'+startHour.split(':')[1]).slice(-2);
      hourTemp.setHours(hour, min);
      get_travel_time(hourTemp.getTime()/1000, startingPoint, nextPoint).done(function(response){
        if(response.response=='error'){
          var $el = $("#planningManagement .newRow").eq(i),
              x = 1000;
          if(typeof response.message != 'undefined'){
            if(response.message.substr(0, 16)=="Connection timed"){
              $el.css("background", "#F08080");
              setTimeout(function(){
                $el.css("background", "#ffffff");
              }, x);

              get_tour_travel_time(i);
            }
          }

          $el.css("background", "#FF0000");
          setTimeout(function(){
            $el.css("background", "#ffffff");
          }, x);
        }else{
          $('#planningManagement .travelTime')[i].innerHTML=response.duration_car;
          $('#planningManagement input[name="deplacement[]"]').eq(i).val(response.duration_car);

          hourTemp.setMinutes(hourTemp.getMinutes()+response.duration_car);

          if(i<$('#planningManagement .address').length){
            var temp = ('0'+hourTemp.getHours()).slice(-2)+":"+('0'+hourTemp.getMinutes()).slice(-2);
            $('#planningManagement .startHour').eq(i).val(temp);
            hourTemp.setMinutes(hourTemp.getMinutes()+$('#planningManagement .execution').eq(i).val()*1);
            var temp = ('0'+hourTemp.getHours()).slice(-2)+":"+('0'+hourTemp.getMinutes()).slice(-2);
            $('#planningManagement .endHour').eq(i).val(temp);
          }
          var $el = $("#planningManagement .newRow").eq(i),
              x = 1000;

          $el.css("background", "#3cb395");
          setTimeout(function(){
            $el.css("background", "#ffffff");
          }, x);
          get_tour_travel_time(i+1);
        }
      })
    }
    else if(i==$('#planningManagement .address').length){
      var startingPoint=$('#planningManagement .address')[i-1].value;
      var nextPoint=$('#planningManagement input[name=endAddress]').val();

      var hourTemp = new Date($('#planningManagement span[name=dateTitle]').html());
      var startHour = $('#planningManagement .endHour').eq(i-1).val();
      var hour = ('0'+startHour.split(':')[0]).slice(-2);
      var min = ('0'+startHour.split(':')[1]).slice(-2);
      hourTemp.setHours(hour, min);
      get_travel_time(hourTemp.getTime()/1000, startingPoint, nextPoint).done(function(response){
        if(response.response=='error'){
          var $el = $("#planningManagement span[name=endPointDuration], #planningManagement input[name=endHourTour]"),
              x = 1000,
              originalColor = $el.css("background");

          if(typeof response.message != 'undefined'){
            if(response.message.substr(0, 16)=="Connection timed"){
              $el.css("background", "#F08080");
              setTimeout(function(){
                $el.css("background", "#ffffff");
              }, x);

              get_tour_travel_time(i);
            }
          }


          $el.css("background", "#FF0000");
          setTimeout(function(){
            $el.css("background", "#ffffff");
          }, x);
        }else{
          $('#planningManagement span[name=endPointDuration]').html(response.duration_car);
          $('#planningManagement input[name=endPointDeplacement]').val(response.duration_car);

          hourTemp.setMinutes(hourTemp.getMinutes()+response.duration_car);
          var temp = ('0'+hourTemp.getHours()).slice(-2)+":"+('0'+hourTemp.getMinutes()).slice(-2);
          $('#planningManagement input[name=endHourTour]').val(temp);


          var $el = $("#planningManagement span[name=endPointDuration], #planningManagement input[name=endHourTour]"),
              x = 1000,
              originalColor = $el.css("background");

          $el.css("background", "#3cb395");
          setTimeout(function(){
            $el.css("background", "#ffffff");
          }, x);
          getTotalTourLength();
        }
      });
    }
  }
};

function getTotalTourLength(){
  var total=0;
  $('#planningManagement .newRow .execution').each(function(){
    total=total*1+(this.value)*1;
  });
  $('#planningManagement .travelTime').each(function(){
    total=total*1+(this.innerHTML)*1;
  })
  total=total*1+($('span[name=endPointDuration]').html())*1;
  var m = total % 60;
  var h = (total-m)/60;

var HHMM = h.toString() + "h" + (m<10?"0":"") + m.toString() +'m';

  $('#planningManagement span[name=totalTourLength]').html(HHMM);
}


$('#planningManagement a[name=addPlanningStep]').click(function(){
  $('#planningManagement .planningDetails').append("<div class='col-md-12 newRow d-flex' style='border: 1px solid grey; margin-top: 10px; margin-bottom: 10px; cursor: pointer'>"+
    '<div class="col-md-1" style="margin:0px; padding:0px">'+
      '<input type="number" name="order[]" class="form-control required order" value="" disabled><br>'+
      '<button type="button" class="btn btn-secondary moveUp"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"></path></svg></button>'+
      '<button type="button" class="btn btn-secondary moveDown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path></svg></button>'+
    "</div>"+
    "<div class='col-md-11'>"+
      "<div class='col-md-8' style='background-color: #3cb395'><strong>Informations</strong></div>"+
      "<div class='col-md-4'style='background-color: #3cb395'><strong>Heure de passage</strong></div>"+
      "<div class='col-md-8'>"+
        "<div class='col-md-6'>"+
          "<strong class='text-green'>Etape manuelle</strong><br>"+
          "<label for='description'>Description</label><input type='text' name='description[]' class='form-control'>"+
          "</div>"+
        "<div class='col-md-6'><strong>Adresse : </strong><input type='text' class='form-control address required' name='address[]' value=''><br>"+
          "<strong>Temps de déplacement : </strong> <span class='travelTime'></span> min<br><input type='number' name='deplacement[]' class='form-control required hidden'>"+
          "<strong>Temps d'exécution : </strong><input type='number' name='execution[]' class='form-control execution' value='30'>"+
        "</div>"+
      "</div>"+
      "<div class='col-md-4'>"+
      "Heure d'arrivée : <input type='time' name='startHour[]' class='form-control required startHour' value='' readonly>"+
      "Heure de fin : <input type='time' name='endHour[]' class='form-control required endHour' value='' readonly>"+
      "<input type='text' class='hidden' value='additionalStep' name='type[]'>"+
      "</div>"+
    "</div>");
    get_tour_travel_time($('#planningManagement .address').length-1);



    $("#planningManagement .moveUp").off();
    $("#planningManagement .moveUp").click(function(){
      var itemlist = $('#planningManagement .newRow');
      selected= $(this).closest('.newRow').index();
      if(selected>0)
      {
        jQuery($(itemlist).eq(selected-1)).before(jQuery($(itemlist).eq(selected)));
        get_tour_travel_time();
      }
    });
    $("#planningManagement .moveDown").off();
    $("#planningManagement .moveDown").click(function(){
      var itemlist = $('#planningManagement .newRow');
      selected= $(this).closest('.newRow').index();
      if(selected<($('#planningManagement .newRow').length - 1))
      {
         jQuery($(itemlist).eq(selected+1)).after(jQuery($(itemlist).eq(selected)));
         get_tour_travel_time();
      }
    });

    $('#planningManagement .address, #planningManagement .execution, #planningManagement input[name=startAddress], #planningManagement input[name=endAddress], #planningManagement input[name=startHourTour]').off();
    $('#planningManagement .address, #planningManagement .execution, #planningManagement input[name=startAddress], #planningManagement input[name=endAddress], #planningManagement input[name=startHourTour]').change(function(){
      get_tour_travel_time();
    })


});
