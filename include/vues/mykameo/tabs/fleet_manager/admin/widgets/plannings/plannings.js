$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "plannings" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
      }
    },
  });
});


$('#planningsListing').on('shown.bs.modal', function(event){
  $("#planningsListingTable").dataTable({
    destroy: true,
    paging: false,
    ajax: {
      url: "api/plannings",
      contentType: "application/json",
      type: "get",
      data: {
        action : 'listPlannings'
      },
    },
    sAjaxDataProp: "plannings",
    columns: [
      { title: "Date", data: "DATE" },
      { title: "Nombre de livraison", data: "nombreCommande" },
      { title: "Nombre d'entretien", data: "nombreEntretiens" },
      { title: "", data: "DATE",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol){
          $(nTd).html('<a href="#" class="text-green" data-target="#planningManagement" data-date="'+sData+'" data-action="update" data-toggle="modal">Modifier</a>');
        },
      }
    ]
  });
});


$('#planningManagement').on('shown.bs.modal', function(event){
  var date=$(event.relatedTarget).data('date');
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
        var i=1;
        response.internalEntretiens.forEach(function(entretien){
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
                  "<strong class='text-green'>Entretien sur vélo de stock n°"+entretien.ID+"</strong><br>"+
                  "<strong>Société : </strong>"+entretien.COMPANY_NAME+"<br>"+
                  "<strong>E-mail : </strong>"+entretien.EMAIL+"<br>"+
                  "<strong>Numéro de téléphone : </strong>"+entretien.PHONE+"<br>"+
                  "<strong>Numéro de cadre : </strong> "+entretien.FRAME_REFERENCE+"</div>"+
                "<div class='col-md-6'><strong>Adresse : </strong><input type='text' class='form-control address' name='address[]' value='"+entretien.ADDRESS+"'<br>"+
                  "<strong>Temps de déplacement : </strong> <span class='travelTime'></span> min<br>"+
                  "<strong>Temps d'exécution : </strong><input type='number' name='execution[]' class='form-control execution' value='20'>"+
                "</div>"+
              "</div>"+
              "<div class='col-md-4'>"+
              "Heure d'arrivée : <input type='time' name='startHour[]' class='form-control required startHour' readonly>"+
              "Heure de fin : <input type='time' name='startHour[]' class='form-control required endHour' readonly>"+
              "</div>"+
              "<div class='separator'></div>"+
              "<div class='col-md-12'>Commentaire pour le client : "+entretien.COMMENT+"<br>Commentaire interne : "+entretien.INTERNAL_COMMENT+"</div>"+
              "<input type='text' class='hidden' value='internalMaintenance' name='type[]'><input type='text' class='hidden' name='id[]' value='"+entretien.ID+"'>"+
            "</div>");
          i++;
        });
        response.externalEntretiens.forEach(function(entretien){
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
                "<div class='col-md-6'><strong>Adresse : </strong><input type='text' class='form-control address' name='address[]' value='"+entretien.ADDRESS+"'<br>"+
                  "<strong>Temps de déplacement : </strong> <span class='travelTime'></span> min<br>"+
                  "<strong>Temps d'exécution : </strong><input type='number' name='execution[]' class='form-control execution' value='20'>"+
                "</div>"+
              "</div>"+
              "<div class='col-md-4'>"+
              "Heure d'arrivée : <input type='time' name='startHour[]' class='form-control required startHour' readonly>"+
              "Heure de fin : <input type='time' name='startHour[]' class='form-control required endHour' readonly>"+
              "</div>"+
              "<div class='separator'></div>"+
              "<div class='col-md-12'>Commentaire pour le client : "+entretien.COMMENT+"<br>Commentaire interne : "+entretien.INTERNAL_COMMENT+"</div>"+
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
                "<div class='col-md-6'><strong>Adresse : </strong><input type='text' class='form-control address' name='address[]' value='"+order.ADDRESS+"'<br>"+
                  "<strong>Temps de déplacement : </strong> <span class='travelTime'></span> min<br>"+
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

        get_tour_travel_time();



        $("#planningManagement .moveUp").click(function(){
          var itemlist = $('#planningManagement .newRow');
          selected= $(this).closest('.newRow').index();
          if(selected>0)
          {
            jQuery($(itemlist).eq(selected-1)).before(jQuery($(itemlist).eq(selected)));
            get_tour_travel_time();
          }
        });

        $("#planningManagement .moveDown").click(function(){
          var itemlist = $('#planningManagement .newRow');
          selected= $(this).closest('.newRow').index();
          if(selected<($('#planningManagement .newRow').length - 1))
          {
             jQuery($(itemlist).eq(selected+1)).after(jQuery($(itemlist).eq(selected)));
             get_tour_travel_time();
          }
        });

        $('#planningManagement .address, #planningManagement .execution, #planningManagement input[name=startAddress], #planningManagement input[name=endAddress]').change(function(){
          get_tour_travel_time();
        })

      }
    }
  });
});


var get_tour_travel_time = function(num){
  var i= num || 0; // uses i if it's set, otherwise uses 0
  if(i <= $('#planningManagement .address').length) {
    $('#planningManagement .newRow .order').eq(i).val(i+1);
    if(i<$('#planningManagement .address').length){
      var hourTemp = new Date($('#planningManagement span[name=dateTitle]').html());
      if(i==0){
        var startHour = $('#planningManagement input[name=startHour]').val();
        var startingPoint=$('#planningManagement input[name=startAddress]').val();
      }else{
        var startHour = $('#planningManagement .endHour').eq(i-1).val();
        var startingPoint=$('#planningManagement .address')[i-1].value;
      }
      var nextPoint=$('#planningManagement .address')[i].value;

      var hour = ('0'+startHour.split(':')[0]).slice(-2);
      var min = ('0'+startHour.split(':')[1]).slice(-2);
      hourTemp.setHours(hour, min);

      get_travel_time("now", startingPoint, nextPoint).done(function(response){

        $('#planningManagement .travelTime')[i].innerHTML=response.duration_car;

        hourTemp.setMinutes(hourTemp.getMinutes()+response.duration_car);

        if(i<$('#planningManagement .address').length){
          var temp = ('0'+hourTemp.getHours()).slice(-2)+":"+('0'+hourTemp.getMinutes()).slice(-2);
          $('#planningManagement .startHour').eq(i).val(temp);
          hourTemp.setMinutes(hourTemp.getMinutes()+$('#planningManagement .execution').eq(i).val()*1);
          var temp = ('0'+hourTemp.getHours()).slice(-2)+":"+('0'+hourTemp.getMinutes()).slice(-2);
          $('#planningManagement .endHour').eq(i).val(temp);
        }
        var $el = $("#planningManagement .newRow").eq(i),
            x = 1000,
            originalColor = $el.css("background");

        $el.css("background", "#3cb395");
        setTimeout(function(){
          $el.css("background", originalColor);
        }, x);
        get_tour_travel_time(i+1);
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

      get_travel_time("now", startingPoint, nextPoint).done(function(response){
        $('#planningManagement span[name=endPointDuration]').html(response.duration_car);
        hourTemp.setMinutes(hourTemp.getMinutes()+response.duration_car);
        var temp = ('0'+hourTemp.getHours()).slice(-2)+":"+('0'+hourTemp.getMinutes()).slice(-2);
        $('#planningManagement input[name=endHour]').val(temp);


        var $el = $("#planningManagement span[name=endPointDuration], #planningManagement input[name=endHour]"),
            x = 1000,
            originalColor = $el.css("background");

        $el.css("background", "#3cb395");
        setTimeout(function(){
          $el.css("background", originalColor);
        }, x);
        getTotalTourLength();
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
  var m = total % 60;
  var h = (total-m)/60;

var HHMM = h.toString() + "h" + (m<10?"0":"") + m.toString() +'m';

  $('#planningManagement span[name=totalTourLength]').html(HHMM);
}
