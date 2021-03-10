$( ".fleetmanager" ).click(function() {
    $.ajax({
        url: 'apis/Kameo/initialize_counters.php',
        type: 'post',
        data: { "email": email, "type": "bookings"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('counterBookings').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bookingNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.bookingNumber+"</span>";
            }
        }
    })
})


window.addEventListener("DOMContentLoaded", (event) => {
    document.getElementsByClassName('reservationlisting')[0].addEventListener('click', function () { reservation_listing()}, false);
});



function bikeFilter(e){
  document.getElementsByClassName('bikeSelectionText')[0].innerHTML=e;
  get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));
}


function reservation_listing(){
  get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));
}


function get_reservations_listing(bike, date_start, date_end){

    var d = new Date(date_start),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    var date_start_string=([year, month, day].join('-'));

    var d = new Date(date_end),
        month = '' + (d.getMonth() + 1),
        day = '' + (d.getDate()),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    var date_end_string=([year, month, day].join('-'));


    if((typeof bike == "undefined") || bike == "" || bike=="Sélection de vélo"){
        var bikeValue="all";
    } else {
        var bikeValue=bike;
    }

    $.ajax({
        url: 'apis/Kameo/get_reservations_listing.php',
        type: 'post',
        data: { "email": email, "bikeValue": bikeValue, "dateStart": date_start_string, "dateEnd": date_end_string},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var i=0;
                var dest="";
                var temp="<table class=\"table table-condensed\"><thead><tr><th><span class=\"fr-inline text-green\">Réf.</span></th><th><span class=\"fr-inline text-green\">Vélo</span><span class=\"en-inline text-green\">Bike</span><span class=\"nl-inline text-green\">Bike</span></th><th><span class=\"fr-inline text-green\">Départ</span><span class=\"en-inline text-green\">Depart</span><span class=\"nl-inline text-green\">Depart</span></th><th><span class=\"fr-inline text-green\">Fin</span><span class=\"en-inline text-green\">End</span><span class=\"nl-inline text-green\">End</span></th><th><span class=\"fr-inline text-green\">Utilisateur</span><span class=\"en-inline text-green\">User</span><span class=\"nl-inline text-green\">User</span></th><th class=\"text-green\">Statut</th></tr></thead><tbody>";
                dest=dest.concat(temp);

                var bikes = [];
                var bikeModels = [];

                while (i < response.bookingNumber){

                  var end = new Date(response.booking[i].dateEnd);
                  var dateNow = new Date();

                  if(response.booking[i].status=="Closed"){
                    var status="Clôturée";
                  }else if(response.booking[i].status=="Ongoing"){
                    var status="En cours";
                  }else if(response.booking[i].status=="bikeNotTaken"){
                    var status="Vélo non pris";
                  }else if(response.booking[i].status=="bikeTaken"){
                    if(end < dateNow){
                      var status="<span class='text-red'>Remise de clé en retard</span>";
                    }else{
                      var status="En cours";
                    }
                  }else if(response.booking[i].status=="NotStarted"){
                    var status="Res. Future";
                  }else{
                    var status=response.booking[i].status;
                  }

                  var temp="<tr><td><a data-target=\"#reservationDetails\" name=\""+response.booking[i].reservationID+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillReservationDetails(this.name)\">"+response.booking[i].reservationID+"</a></td><td>"+response.booking[i].bikeID+" - "+response.booking[i].model+"</td><td>"+response.booking[i].dateStart.shortDate()+" "+response.booking[i].dateStart.substring(11,16)+" - "+response.booking[i].buildingStart+"</td><td>"+response.booking[i].dateEnd.shortDate()+" "+response.booking[i].dateEnd.substring(11,16)+" - "+response.booking[i].buildingEnd+"</td><td>"+response.booking[i].user+"</td><td>"+status+"</td></tr>";
                  dest=dest.concat(temp);

                  if(! bikes.includes(response.booking[i].bikeID)){
                      bikes.push(response.booking[i].bikeID);
                      bikeModels.push(response.booking[i].model)
                  }
                  i++;
                }
                var temp="</tbody></table>";
                dest=dest.concat(temp);
                document.getElementById('ReservationsList').innerHTML = dest;

                if(bikeValue == "all"){
                  var dest2="";
                  temp2="<li><a href=\"#\" onclick=\"bikeFilter('Sélection de vélo')\">Tous les vélos</a></li><li class=\"divider\"></li>";
                  dest2=dest2.concat(temp2);

                  bikes.forEach(function callback(bike, index){
                      var temp2="<li><a href=\"#\" onclick=\"bikeFilter('"+bike+"')\">"+bike+" - "+bikeModels[index]+"</a></li>";
                      dest2=dest2.concat(temp2);
                  });
                  document.getElementsByClassName('bikeSelection')[0].innerHTML=dest2;
                }

                displayLanguage();

            }
        }
    })

}


function fillReservationDetails(element) {
  var reservationID = element;
  $.ajax({
    url: "apis/Kameo/get_reservation_details.php",
    type: "post",
    data: { reservationID: reservationID },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        document.getElementsByClassName(
          "reservationNumber"
        )[0].innerHTML = reservationID;
        document.getElementsByClassName(
          "reservationStartDate"
        )[0].innerHTML = response.reservation.start.shortDateHours();

        if(response.reservation.initialEndDate == null){
          document.getElementsByClassName(
            "reservationEndDate"
          )[0].innerHTML = response.reservation.end.shortDateHours();
        }else{
          document.getElementsByClassName(
            "reservationEndDate"
          )[0].innerHTML = "<del>"+response.reservation.initialEndDate.shortDateHours()+"</del>&nbsp;<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-arrow-right' viewBox='0 0 16 16'><path fill-rule='evenodd' d='M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z'></path></svg>&nbsp;" + response.reservation.end.shortDateHours();
        }
        document.getElementsByClassName(
          "reservationStartBuilding"
        )[0].innerHTML = response.reservationStartBuilding;
        document.getElementsByClassName("reservationEndBuilding")[0].innerHTML =
          response.reservationEndBuilding;
        document.getElementsByClassName("reservationBikeNumber")[0].innerHTML =
          response.reservationBikeNumber;
        document.getElementsByClassName("reservationEmail")[0].innerHTML =
          response.reservationEmail;
        document.getElementsByClassName("reservationBikeImage")[0].src =
          "images_bikes/" + response.img + "_mini.jpg";
        document.getElementById("deleteReservationdiv").innerHTML =
          '<a class="button small red-dark button-3d rounded icon-right" data-target="#deleteReservation" onclick="initializeDeleteReservation(\'' +
          reservationID +
          '\')" data-toggle="modal" href="#"><span><?= L::mk_reservations_delete; ?></span></a>';

          if(response.reservationsLogs != null){

            var dest="<tr><th>Date</th><th>Action</th><th>Réponse serveur</th></tr>";
            var action = "";
            var output = "";
            response.reservationsLogs.forEach((reservationLog) => {
              console.log(reservationLog.ACTION);
              if(reservationLog.ACTION == "new_booking"){
                var infos = reservationLog.OUTCOME.split('/');
                var dateStart = infos[0];
                var dateEnd = infos[1];
                var buildingStart = infos[2];
                var buildingEnd = infos[3];
                action = "Nouvelle réservation";
                output = "<ul><li>Date de début : "+dateStart.shortDateHours()+"</li><li>Date de fin : "+dateEnd.shortDateHours()+"</li>";
                if(buildingStart != buildingEnd){
                  output += "<li>Bâtiment de début : "+buildingStart+"</li><li>Bâtiment de fin : "+buildingEnd+"</li></ul>";
                }else{
                  output += '</ul>';
                }
              }else if(reservationLog.ACTION == "prolongation"){
                var infos = reservationLog.OUTCOME.split('/');
                var dateEnd = infos[0];
                var newDateEnd = infos[1];
                action = "Prolongation de réservation";
                output = "<ul><li>Ancienne date de fin : "+dateEnd.shortDateHours()+"</li><li>Nouvelle date de fin : "+newDateEnd.shortDateHours()+"</li></ul>";
              }else if(reservationLog.ACTION == "cancel"){
                action = "Annulation de réservation";
                output = "Fin du processus de réservation";
              }else if(reservationLog.ACTION == "verifier_code"){
                action = "Vérification du code";
                if(reservationLog.OUTCOME.substring(0, 2) == "-4"){
                  output = "Code hors délai"
                }else if(reservationLog.OUTCOME.substring(0, 2) == "-1"){
                  output = "Code bon mais le vélo était déjà considéré comme sorti";
                }else{
                  output = "Code bon";
                }
              }else if(reservationLog.ACTION == "open_door"){
                action = "Ouverture de porte";
                output = "Ouverture OK";
              }else if(reservationLog.ACTION == "close_door"){
                action ="Fermeture de porte";
                output = "Fermeture OK";
              }else if(reservationLog.ACTION == "prise_cle"){
                action = "Prise de la clé";
                output = "Prise de clé OK";
              }else if(reservationLog.ACTION == "verifier_rfid"){
                action = "Vérification du badge RFID";
                if(reservationLog.OUTCOME.substring(0, 2) == "-1"){
                  output = "Vérification OK";
                }else{
                  output = "Le vélo n'était pas considéré comme en dehors de la borne - KO";
                }
              }else if(reservationLog.ACTION == "update_remise_cle"){
                action = "Remise de la clé";
                output = "Remise de clé OK";
              }
              dest = dest.concat("<tr><td>"+reservationLog.TIMESTAMP+"</td><td>"+action+"</td><td>"+output+"</td></tr>");
            })
          }else{
            dest = "";
          }
          $('#detailsReservationsLogs').html(dest);
      }
    },
  });
}
