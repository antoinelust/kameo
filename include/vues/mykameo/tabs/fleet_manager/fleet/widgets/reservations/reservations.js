
$("#ReservationsListing").on('shown.bs.modal', function(event){
  get_reservations_listing();
})

function get_reservations_listing(){
  var d = new Date($(".form_date_start").data("datetimepicker").getDate()),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();

  if (month.length < 2)
      month = '0' + month;
  if (day.length < 2)
      day = '0' + day;

  var date_start_string=([year, month, day].join('-'));

  var d = new Date($(".form_date_end").data("datetimepicker").getDate()),
      month = '' + (d.getMonth() + 1),
      day = '' + (d.getDate()),
      year = d.getFullYear();

  if (month.length < 2)
      month = '0' + month;
  if (day.length < 2)
      day = '0' + day;

  var date_end_string=([year, month, day].join('-'));

  var table = $("#ReservationsList").dataTable({
    destroy: true,
    paging: false,
    ajax: {
      url: "apis/Kameo/get_reservations_listing.php",
      contentType: "application/json",
      type: "GET",
      data: { "email": email, "dateStart": date_start_string, "dateEnd": date_end_string},
    },
    sAjaxDataProp: "bookings",
    columns: [
      {
        title: "Reference",
        data: "ID",
        width: "5%",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
            $(nTd).html("<a data-target=\"#reservationDetails\" name=\""+sData+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillReservationDetails(this.name)\">"+sData+"</a>");
        },
      },
      {
        title: "Vélo",
        data: "MODEL",
        width: "15%",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {$(nTd).html(oData.BIKE_ID+" - "+sData)},
      },
       {
         title: "Début",
         data: "DATE_START_2",
         width: "20%",
         fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {$(nTd).html(sData.shortDate()+' '+sData.substring(11, 16)+' <br> '+oData.building_start_fr)},
       },
       {
         title: "Fin",
         data: "DATE_END_2",
         width: "20%",
         fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {$(nTd).html(sData.shortDate()+' '+sData.substring(11, 16)+' <br> '+oData.building_end_fr)},
       },
       {
         title: "Utilisateur",
         data: "EMAIL",
         width: "20%"
       },
       {
         title: "Statut",
         data: "status",
         width: "20%",
         fnCreatedCell: function (nTd, sData, oData, iRow, iCol){
           if(sData=="Closed"){
             $(nTd).html("Clôturée")
           }else if(sData=='bikeTaken' && new Date() > new Date(oData.DATE_END_2)){
             $(nTd).html("<span class='text-red'>Remise de clé en retard</span>")
           }else if(sData=='bikeNotTaken' && new Date() > new Date(oData.DATE_START_2)){
             $(nTd).html("Vélo non pris")
           }else if(sData=="Open" && new Date() < new Date(oData.DATE_START_2)){
             $(nTd).html("Réservation future")
           }else if(sData=="Open" && new Date() > new Date(oData.DATE_END_2)){
             $(nTd).html("<span class='text-red'>Remise de clé en retard</span>")
           }else if(sData=="Open" && new Date() > new Date(oData.DATE_END_2)){
             $(nTd).html("<span class='text-red'>Remise de clé en retard</span>")
           }else if(sData=="Open"){
             $(nTd).html("En cours")
           }else if(sData=="No Box"){
             $(nTd).html("Pas de statut possible sans borne")
           }else{
             $(nTd).html("Statut inconnu")
           }
         },
       }

    ],
    order: [
     [0, "desc"]
    ]
  });
};

$('#ReservationsListing .form_date_start, #ReservationsListing .form_date_end').change(function(){
  get_reservations_listing();
})



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
