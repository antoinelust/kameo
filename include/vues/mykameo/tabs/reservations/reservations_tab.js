function initializeDeleteReservation(reservationID) {
  $.ajax({
    url: "apis/Kameo/get_reservation_details.php",
    type: "post",
    data: { reservationID: reservationID },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        document.getElementById("widget-deleteReservation-form-start").value =
          response.reservationStartBuilding +
          " le " +
          response.reservation.start.shortDateHours();
        document.getElementById("widget-deleteReservation-form-end").value =
          response.reservationEndBuilding +
          " le " +
          response.reservation.end.shortDateHours();
        document.getElementById("widget-deleteReservation-form-user").value =
          response.reservationEmail;
        document.getElementById(
          "widget-deleteReservation-form-ID"
        ).value = reservationID;
      }
    },
  });
  $("#reservationDetails").modal("toggle");
}

function initializeUpdateReservation(reservationID) {
  $.ajax({
    url: "apis/Kameo/get_reservation_details.php",
    type: "post",
    data: { reservationID: reservationID },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        document.getElementById("widget-updateReservation-form-start").value =
          response.reservationStartBuilding +
          " le " +
          response.reservation.start.shortDateHours();
        document.getElementById("widget-updateReservation-form-end").value =
          response.reservationEndBuilding +
          " le " +
          response.reservation.end.shortDateHours();
        document.getElementById("widget-updateReservation-form-user").value =
          response.reservationEmail;
        document.getElementById(
          "widget-updateReservation-form-ID"
        ).value = reservationID;
      }
    },
  });
  $("#reservationDetails").modal("toggle");
}

function showBooking(bookingID) {
  var dest = "";
  $.ajax({
    url: "apis/Kameo/get_future_booking.php",
    type: "post",
    data: { bookingID: bookingID },
    success: function (response) {
      if (response.response == "success") {
        //Current Booking

        var ID = response.booking.ID;
        var code = response.booking.code;

        temp = "<li><?= L::mk_reservations_reservation_nbr; ?>" + ID + "</li>";
        dest = temp;
        if (code) {
          if (code.length == 3) {
            code = "0" + code;
          } else if (code.length == 2) {
            code = "00" + code;
          } else if (code.length == 1) {
            code = "000" + length;
          }
          temp = "<li><?= L::mk_reservations_code2; ?>" + code + "</li>";
          dest = dest.concat(temp);
        }
        dest = dest.concat(
          "<li><?= L::mk_reservations_start2; ?>" +
            response.booking.start.shortDateHours());
            if(response.booking.buildingStart != response.booking.buildingEnd){
              dest = dest.concat(
              " au bâtiment " +
              response.booking.buildingStart +
              "</li>");
            }else{
              dest = dest.concat("</li>");
            }
        dest = dest.concat(
          "<li><?= L::mk_reservations_end2; ?>" +
            response.booking.end.shortDateHours());
            if(response.booking.buildingStart != response.booking.buildingEnd){
              dest = dest.concat(
              " au bâtiment " +
              response.booking.buildingStart +
              "</li>");
            }else{
              dest = dest.concat("</li>");
            }
        document.getElementById("bookingInformation").innerHTML = dest;

        var dest = "";
        dest = dest.concat(
          "<li><?= L::mk_reservations_bikeNbr; ?>" +
            response.booking.frameNumber +
            "</li>"
        );
        dest = dest.concat(
          "<li><?= L::mk_reservations_model2; ?>" +
            response.booking.model +
            "</li>"
        );
        document.getElementById("bookingInformationBike").innerHTML = dest;

        document.getElementById("imageNextBooking").src =
          "images_bikes/" + response.booking.img + "?date="+Date.now();

        $("#futureBooking").modal("toggle");
      } else {
        console.log(response.message);
      }
    },
  });
}

function cancelBooking(bookingID) {
  var dest = "";

  $.ajax({
    url: "apis/Kameo/cancel_booking.php",
    type: "post",
    data: { bookingID: bookingID },
    success: function (text) {
      if (text.response == "error") {
        $.notify(
          {
            message: text.message,
          },
          {
            type: "danger",
          }
        );
      } else if (text.response == "success") {
        $.notify(
          {
            message: text.message,
          },
          {
            type: text.response,
          }
        );
        getHistoricBookings();
      }
    },
  });
}

function getHistoricBookings() {
  $.ajax({
    url: "apis/Kameo/get_historic_bookings.php",
    type: "post",
    data: { user: email },
    success: function (response) {
      if (response.response == "success") {
        var i = 0;
        var dest = "";

        var tempHistoricBookings =
          '<table class="table table-condensed" id="previousBookingsTable" data-order=\'[[ 0, "desc" ]]\' data-page-length=\'5\'><h4 class="text-green"><?= L::mk_reservations_past; ?></h4>';
        dest = dest.concat(tempHistoricBookings);

        var tempHistoricBookings =
          "<ul><li><?= L::mk_reservations_from_start_year; ?>" +
          response.maxBookingsPerYear +
          "<?= L::mk_reservations_reservations; ?>";
        dest = dest.concat(tempHistoricBookings);

        if (response.maxBookingsPerYearCondition != "9999") {
          var tempHistoricBookings =
            " (maximum " +
            response.maxBookingsPerYearCondition +
            ")</li><li><?= L::mk_reservations_from_start_month; ?>" +
            response.maxBookingsPerMonth +
            "<?= L::mk_reservations_reservations; ?>";
        } else {
          var tempHistoricBookings =
            "</li><li><?= L::mk_reservations_from_start_month; ?>" +
            response.maxBookingsPerMonth +
            "<?= L::mk_reservations_reservations; ?>";
        }
        dest = dest.concat(tempHistoricBookings);

        if (response.maxBookingsPerMonthCondition != "9999") {
          var tempHistoricBookings =
            " (maximum " +
            response.maxBookingsPerMonthCondition +
            ")</li></ul>";
        } else {
          var tempHistoricBookings = "</li></ul>";
        }

        dest = dest.concat(tempHistoricBookings);

        var tempHistoricBookings =
          "<thead><tr><th><?= L::mk_reservations_id; ?></th><th><span><?= L::mk_reservations_start; ?></span></th><th><span><?= L::mk_reservations_stop; ?></span></th><th><span><?= L::mk_reservations_bike; ?></span></th></tr></thead><tbody>";
        dest = dest.concat(tempHistoricBookings);

        while (i < response.previous_bookings) {
          var building_start_fr = response.booking[i].building_start_fr;
          var building_end_fr = response.booking[i].building_end_fr;
          var frame_number = response.booking[i].frameNumber;
          var model = response.booking[i].model;
          var bikeID = response.booking[i].bikeID;

          var tempHistoricBookings =
            '<tr><td><a href="#" name="' +
            response.booking[i].ID +
            '" class="showBooking">' +
            response.booking[i].ID +
            '</a></td><td data-sort="' +
            new Date(response.booking[i].start).getTime() +
            '">' +
            response.booking[i].start.shortDate() +
            " - " +
            "<span>"+building_start_fr+"</span>"+
            " <span><?= L::mk_reservations_at; ?></span> " +
            response.booking[i].start.shortHours() +
            '</td><td data-sort="' +
            new Date(response.booking[i].start).getTime() +
            '">' +
            response.booking[i].end.shortDate() +
            " - " +
            "<span>"+building_end_fr+"</span>"+
            " <span><?= L::mk_reservations_at; ?></span> " +
            response.booking[i].end.shortHours() +
            "</td><td>" +
            model +
            '</td></tr>';

          dest = dest.concat(tempHistoricBookings);
          i++;
        }

        var tempHistoricBookings = "</tbody></table>";
        dest = dest.concat(tempHistoricBookings);

        //affichage du résultat de la recherche
        document.getElementById("historicBookings").innerHTML = dest;

        if ($.fn.dataTable.isDataTable("#previousBookingsTable")) {
          table = $("#previousBookingsTable").DataTable();
        } else {
          table = $("#previousBookingsTable").DataTable({
            paging: true,
            lengthChange: false,
            searching: false,
            language: {
              emptyTable: "<?= L::mk_reservations_noPastReservations; ?>",
            },
          });
        }

        //Ongoing bookings

        var dest = "";
        var dest2 = "";
        if (response.booking.codePresence == false) {
          var tempOngoingBookings =
            '<table class="table table-condensed hidden-xs" id="ongoingBookingsTable" data-order=\'[[ 0, "desc" ]]\' data-page-length=\'5\'><thead><tr><th><?= L::mk_reservations_id; ?></th><th><span><?= L::mk_reservations_start; ?></span></th><th><span><?= L::mk_reservations_stop; ?></span></th><th><span><?= L::mk_reservations_bike; ?></span></th><th></th></tr></thead><tbody>';
        } else {
          var tempOngoingBookings =
            '<table class="table table-condensed hidden-xs" id="ongoingBookingsTable" data-order=\'[[ 0, "desc" ]]\' data-page-length=\'5\'><thead><tr><th><?= L::mk_reservations_id; ?></th><th><span><?= L::mk_reservations_start; ?></span></th><th><span><?= L::mk_reservations_stop; ?></span></th><th><span><?= L::mk_reservations_bike; ?></span></th><th><?= L::mk_reservations_code; ?></th><th></th></tr></thead><tbody>';
        }
        dest = dest.concat(tempOngoingBookings);
        var length =
          parseInt(response.ongoing_bookings) +
          parseInt(response.previous_bookings);

          if(response.ongoing_bookings == 0){
            $("#ongoingBookingsSmartphone").addClass("hidden");
          }else{
            $("#ongoingBookingsSmartphone").removeClass("hidden");
          }


        while (i < length) {
          var building_start_fr = response.booking[i].building_start_fr;
          var building_start_en = response.booking[i].building_start_en;
          var building_start_nl = response.booking[i].building_start_nl;
          var building_end_fr = response.booking[i].building_end_fr;
          var building_end_en = response.booking[i].building_end_en;
          var building_end_nl = response.booking[i].building_end_nl;
          var frame_number = response.booking[i].frameNumber;
          var model = response.booking[i].model;
          var booking_id = response.booking[i].bookingID;
          var annulation = response.booking[i].annulation;
          if(typeof response.booking[i].nextBookingDate != 'undefined'){
            var nextBookingDate = new Date(response.booking[i].nextBookingDate);
          }else{
            var nextBookingDate = false;
          }

          if (response.booking.codePresence == false) {
            var tempOngoingBookings =
              '<tr><td><a href="#" name="' +
              response.booking[i].bookingID +
              '" class="showBooking">' +
              response.booking[i].bookingID +
              "</a></td><td>" +
              response.booking[i].start.shortDate() +
              " - " +
              "<span>"+building_start_fr+"</span>" +
              " <span><?= L::mk_reservations_at; ?></span> " +
              response.booking[i].start.shortHours() +
              "</td><td>" +
              response.booking[i].end.shortDate() +
              " - " +
              building_end_fr +
              " <span><?= L::mk_reservations_at; ?></span> " +
              response.booking[i].end.shortHours() +
              "</td><td>" +
              model +
              '</td><td><a class="button small green rounded effect" onclick="showBooking(' +
              booking_id +
              ')"><span>+</span></a>';
          } else {
            code = response.booking[i].codeValue;
            if (code.length == 3) {
              code = "0" + code;
            } else if (code.length == 2) {
              code = "00" + code;
            } else if (code.length == 1) {
              code = "000" + length;
            }

            var tempOngoingBookings =
              '<tr><td><a href="#" name="' +
              response.booking[i].bookingID +
              '" class="showBooking">' +
              response.booking[i].bookingID +
              "</a></td><td>" +
              response.booking[i].start.shortDate() +
              " - " +building_start_fr+
              "<?= L::mk_reservations_at; ?>" +
              response.booking[i].start.shortHours() +
              "</td><td>" +
              response.booking[i].end.shortDate() +
              " - " +building_end_fr+
              " <?= L::mk_reservations_at; ?> " +
              response.booking[i].end.shortHours() +
              "</td><td>" +
              model +
              "</td><td>" +
              code +
              '</td><td style="text-align: center;"><a class="button small green rounded effect" onclick="showBooking(' +
              booking_id +
              ')"><span>'+traduction.generic_moreInfo+'</span></a>';
          }
          if(response.booking[i].extension == '0'){
            tempOngoingBookings=tempOngoingBookings.concat("<br/><a class=\"button small green rounded effect updateEndBookingDate\" data-nextBooking='"+nextBookingDate+"' data-ID='"+response.booking[i].bookingID+"' data-start='"+response.booking[i].start+"' data-end='"+response.booking[i].end+"' data-model='"+response.booking[i].model+"'><span>"+traduction.generic_extend+"</span></a>");
          }

          tempOngoingBookings = tempOngoingBookings.concat("</td></tr>");
          dest = dest.concat(tempOngoingBookings);

          var temp="<p class='text-dark'><strong>ID :</strong> "+
          response.booking[i].bookingID
          +"<br><strong>Départ : </strong>"+
          response.booking[i].start.shortDate()
          +"<span><?= L::mk_reservations_at; ?></span> " +
          response.booking[i].start.shortHours()
          +" "+
          building_start_fr
          +"<br><strong>Arrivée : </strong>"+
          response.booking[i].end.shortDate()
          +"<span><?= L::mk_reservations_at; ?></span> " +
          response.booking[i].end.shortHours()
          +" "+
          building_end_fr
          +"<br><strong>Vélo :</strong> "+
          model;

          if(response.booking[i].codePresence){
            temp=temp.concat("<br><strong>Code : </strong>"+code+"</p>");
          }
          temp=temp.concat("<a class='button small green rounded effect' onclick=\"showBooking('"+booking_id +"')\"><span>+</span></a>");
          if(response.booking[i].extension == '0'){
            temp=temp.concat("<br/><a class=\"button small green rounded effect updateEndBookingDate\" data-nextBooking='"+nextBookingDate+"' data-ID='"+response.booking[i].bookingID+"' data-start='"+response.booking[i].start+"' data-end='"+response.booking[i].end+"' data-model='"+response.booking[i].model+"'><span>"+traduction.generic_extend+"</span></a>");
          }

          dest2=dest2.concat(temp);

          i++;
        }
        var tempOngoingBookings = "</tbody></table>";
        dest = dest.concat(tempOngoingBookings);

        //affichage du résultat de la recherche
        document.getElementById("ongoingBookings").innerHTML = dest;
        document.getElementById("ongoingBookingsSmartphone").innerHTML = dest2;

        //Booking futurs

        var dest = "";
        var dest2 = "";
        if (response.booking.codePresence == false) {
          var tempFutureBookings =
            '<table class="table table-condensed hidden-xs" id="futureBookingsTable" data-order=\'[[ 0, "desc" ]]\' data-page-length=\'5\'><thead><tr><th><?= L::mk_reservations_id; ?></th><th><span><?= L::mk_reservations_start; ?></span></th><th><span><?= L::mk_reservations_stop; ?></span></th><th><span><?= L::mk_reservations_bike; ?></span></th><th></th></tr></thead><tbody>';
        } else {
          var tempFutureBookings =
            '<table class="table table-condensed hidden-xs" id="futureBookingsTable" data-order=\'[[ 0, "desc" ]]\' data-page-length=\'5\'><thead><tr><th><?= L::mk_reservations_id; ?></th><th><span><?= L::mk_reservations_start; ?></span></th><th><span><?= L::mk_reservations_stop; ?></span></th><th><span><?= L::mk_reservations_bike; ?></span></th><th><?= L::mk_reservations_code; ?></th><th></th></tr></thead><tbody>';
        }
        dest = dest.concat(tempFutureBookings);
        var length =
          parseInt(response.future_bookings) +
          parseInt(response.ongoing_bookings) +
          parseInt(response.previous_bookings);

          if(response.future_bookings == 0){
            $("#futureBookingsSmartphone").addClass("hidden");
          }else{
            $("#futureBookingsSmartphone").removeClass("hidden");
          }


        while (i < length) {
          var building_start_fr = response.booking[i].building_start_fr;
          var building_start_en = response.booking[i].building_start_en;
          var building_start_nl = response.booking[i].building_start_nl;
          var building_end_fr = response.booking[i].building_end_fr;
          var building_end_en = response.booking[i].building_end_en;
          var building_end_nl = response.booking[i].building_end_nl;
          var frame_number = response.booking[i].frameNumber;
          var model = response.booking[i].model;
          var booking_id = response.booking[i].bookingID;
          var annulation = response.booking[i].annulation;
          if(typeof response.booking[i].nextBookingDate != 'undefined'){
            var nextBookingDate = new Date(response.booking[i].nextBookingDate);
          }else{
            var nextBookingDate = false;
          }

          if (response.booking.codePresence == false) {
            var tempFutureBookingsTable =
              '<tr><td><a href="#" name="' +
              response.booking[i].bookingID +
              '" class="showBooking">' +
              response.booking[i].bookingID +
              "</a></td><td>" +
              response.booking[i].start.shortDate() +
              " - " +building_start_fr+
              " <span><?= L::mk_reservations_at; ?></span> " +
              response.booking[i].start.shortHours() +
              "</td><td>" +
              response.booking[i].end.shortDate() +
              " - " +
              building_end_fr +
              " <span><?= L::mk_reservations_at; ?></span> " +
              response.booking[i].end.shortHours() +
              "</td><td>" +
              model +
              '</td><td><a class="button small green rounded effect" onclick="showBooking(' +
              booking_id +
              ')"><span>+</span></a>';
          } else {
            code = response.booking[i].codeValue;
            if (code.length == 3) {
              code = "0" + code;
            } else if (code.length == 2) {
              code = "00" + code;
            } else if (code.length == 1) {
              code = "000" + length;
            }

            var tempFutureBookingsTable =
              '<tr><td><a href="#" name="' +
              response.booking[i].bookingID +
              '" class="showBooking">' +
              response.booking[i].bookingID +
              "</a></td><td>" +
              response.booking[i].start.shortDate() +
              " - " +building_start_fr+
              " <span><?= L::mk_reservations_at; ?></span> " +
              response.booking[i].start.shortHours() +
              "</td><td>" +
              response.booking[i].end.shortDate() +
              " - " +building_end_fr+
              " <span><?= L::mk_reservations_at; ?></span> " +
              response.booking[i].end.shortHours() +
              "</td><td>" +
              model +
              "</td><td>" +
              code +
              '</td><td style="text-align: center;"><a class="button small green rounded effect" onclick="showBooking(' +
              booking_id +
              ')"><span>'+traduction.generic_moreInfo+'</span></a>';
          }
          if (annulation) {
            var tempAnnulation =
              '<br/><a class="button small red rounded effect" onclick="cancelBooking(' +
              booking_id +
              ')"><i class="fa fa-times"></i><span>'+traduction.generic_cancel+'</span></a>';
            tempFutureBookingsTable = tempFutureBookingsTable.concat(tempAnnulation);
          }

          if(response.booking[i].extension == '0'){
            tempFutureBookingsTable=tempFutureBookingsTable.concat("<br/><a class=\"button small green rounded effect updateEndBookingDate\" data-nextBooking='"+nextBookingDate+"' data-ID='"+response.booking[i].bookingID+"' data-start='"+response.booking[i].start+"' data-end='"+response.booking[i].end+"' data-model='"+response.booking[i].model+"'><span>"+traduction.generic_extend+"</span></a>");
          }

          tempFutureBookingsTable = tempFutureBookingsTable.concat("</td></tr>");
          dest = dest.concat(tempFutureBookingsTable);

          var temp="<p class='text-dark'><strong>ID :</strong> "+
          response.booking[i].bookingID
          +"<br><strong>Départ : </strong>"+
          response.booking[i].start.shortDate()
          +"<span><?= L::mk_reservations_at; ?></span> " +
          response.booking[i].start.shortHours()
          +" "+
          building_start_fr
          +"<br><strong>Arrivée : </strong>"+
          response.booking[i].end.shortDate()
          +"<span><?= L::mk_reservations_at; ?></span> " +
          response.booking[i].end.shortHours()
          +" "+
          building_end_fr
          +"<br><strong>Vélo :</strong> "+
          model;

          if(response.booking.codePresence){
            temp=temp.concat("<br><strong>Code : </strong>"+code+"</p>");
          }
          temp=temp.concat("<a class='button small green rounded effect' onclick=\"showBooking('"+booking_id +"')\"><span>+</span></a>");
          if (annulation) {
            temp=temp.concat("<br/><a class=\"button small red rounded effect\" onclick=\"cancelBooking(' "+booking_id +"')\"><i class=\"fa fa-times\"></i><span>"+traduction.generic_cancel+"</span></a>");
          }

          if(response.booking[i].extension == '0'){
            temp=temp.concat("<br/><a class=\"button small green rounded effect updateEndBookingDate\" data-nextBooking='"+nextBookingDate+"' data-ID='"+response.booking[i].bookingID+"' data-start='"+response.booking[i].start+"' data-end='"+response.booking[i].end+"' data-model='"+response.booking[i].model+"'><span>"+traduction.generic_extend+"</span></a>");
          }

          dest2=dest2.concat(temp);

          i++;
        }
        var tempFutureBookings = "</tbody></table>";
        dest = dest.concat(tempFutureBookings);

        //affichage du résultat de la recherche
        document.getElementById("futureBookings").innerHTML = dest;
        document.getElementById("futureBookingsSmartphone").innerHTML = dest2;

        //displayLanguage();

        $(".showBooking").click(function(){
          showBooking(this.name);
        });


        $(".updateEndBookingDate").click(function(){

          if($(this).data("nextbooking") == false){
            var nextBookingDate=false;
          }else{
            var nextBookingDate = new Date($(this).data("nextbooking"));
          }
          if( !nextBookingDate || (nextBookingDate && nextBookingDate > new Date())){
            $('#widget_updateDepositHour_booking input[name=ID]').val($(this).data("id"));
            $('#widget_updateDepositHour_booking input[name=model]').val($(this).data("model"));
            $('#widget_updateDepositHour_booking input[name=start]').val($(this).data("start"));
            $('#widget_updateDepositHour_booking input[name=end]').val($(this).data("end"));

            if((new Date($(this).data("end"))) < (new Date())) {
              var dateEndBooking = new Date();
            }else{
              var dateEndBooking = new Date($(this).data("end"));
            }

            var end = get_date_string_european_with_hours2(dateEndBooking).split(' ');
            $('#widget_updateDepositHour_booking input[name=newEndDate').val(end[0]);
            $('#widget_updateDepositHour_booking input[name=newEndHour').val(end[1]);
            $('#widget_updateDepositHour_booking input[name=newEndDate').prop("min",end[0]);



            if(nextBookingDate){
              $('#widget_updateDepositHour_booking .nextBooking').removeClass("hidden");
              $('#widget_updateDepositHour_booking strong[name=nextBookingDate]').html(get_date_string_european_with_hours(nextBookingDate));
            }else{
              $('#widget_updateDepositHour_booking .nextBooking').addClass("hidden");
              $('#widget_updateDepositHour_booking strong[name=nextBookingDate]').html("");
            }
            $("#updateDepositHour").modal("toggle");
          }else{
            $.notify(
              {
                message: traduction.error_giveBackKeys,
              },
              {
                type: "danger",
              }
            )
          }
        })
      } else {
        console.log(response.message);
      }
    },
  });
}
