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

function get_reservations_listing(bike, date_start, date_end) {
  var frameNumber = "";
  var d = new Date(date_start),
    month = "" + (d.getMonth() + 1),
    day = "" + d.getDate(),
    year = d.getFullYear();

  if (month.length < 2) month = "0" + month;
  if (day.length < 2) day = "0" + day;

  var date_start_string = [year, month, day].join("-");

  var d = new Date(date_end),
    month = "" + (d.getMonth() + 1),
    day = "" + (d.getDate() + 1),
    year = d.getFullYear();

  if (month.length < 2) month = "0" + month;
  if (day.length < 2) day = "0" + day;

  var date_end_string = [year, month, day].join("-");

  if (typeof bike == "undefined" || bike == "" || bike == "Sélection de vélo") {
    var bikeValue = "all";
  } else {
    var bikeValue = bike;
  }

  $.ajax({
    url: "apis/Kameo/get_reservations_listing.php",
    type: "post",
    data: {
      email: email,
      bikeValue: bikeValue,
      dateStart: date_start_string,
      frameNumber: frameNumber,
      dateEnd: date_end_string,
    },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        var i = 0;
        var dest = "";
        var temp =
          '<table class="table table-condensed"><thead><tr><th><span class="text-green"><?= L::mk_reservations_ref; ?></span></th><th><span class="text-green"><?= L::mk_reservations_bike; ?></span></th><th><span class="text-green"><?= L::mk_reservations_start; ?></span></th><th><span class="text-green"><?= L::mk_reservations_fin; ?></span></th><th><span class="text-green"><?= L::mk_reservations_user; ?></span></th></tr></thead><tbody>';
        dest = dest.concat(temp);

        var bikes = [];

        var dest2 = "";
        temp2 =
          '<li><a href="#" onclick="bikeFilter(\'Sélection de vélo\')"><?= L::mk_reservations_allbikes; ?></a></li><li class="divider"></li>';
        dest2 = dest2.concat(temp2);

        while (i < response.bookingNumber) {
          var temp =
            '<tr><td><a data-target="#reservationDetails" name="' +
            response.booking[i].reservationID +
            '" data-toggle="modal" href="#" onclick="fillReservationDetails(this.name)">' +
            response.booking[i].reservationID +
            "</a></td><td>" +
            response.booking[i].bikeID +
            " - " +
            response.booking[i].frameNumber +
            "</td><td>" +
            response.booking[i].dateStart.shortDate() +
            " " +
            response.booking[i].dateStart.substring(11, 16) +
            " - " +
            response.booking[i].buildingStart +
            "</td><td>" +
            response.booking[i].dateEnd.shortDate() +
            " " +
            response.booking[i].dateEnd.substring(11, 16) +
            " - " +
            response.booking[i].buildingEnd +
            "</td><td>" +
            response.booking[i].user +
            "</td></tr>";
          dest = dest.concat(temp);

          if (!bikes.includes(response.booking[i].bikeID)) {
            bikes.push(response.booking[i].bikeID);
          }
          i++;
        }
        var temp = "</tbody></table>";
        dest = dest.concat(temp);
        document.getElementById("ReservationsList").innerHTML = dest;

        bikes.sort();
        bikes.forEach(function (bike) {
          var temp2 =
            '<li><a href="#" onclick="bikeFilter(\'' +
            bike +
            "')\">" +
            bike +
            "</a></li>";
          dest2 = dest2.concat(temp2);
        });
        document.getElementsByClassName("bikeSelection")[0].innerHTML = dest2;

        displayLanguage();
      }
    },
  });
}

function reservation_listing() {
  get_reservations_listing(
    document.getElementsByClassName("bikeSelectionText")[0].innerHTML,
    new Date($(".form_date_start").data("datetimepicker").getDate()),
    new Date($(".form_date_end").data("datetimepicker").getDate())
  );
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
        document.getElementsByClassName(
          "reservationEndDate"
        )[0].innerHTML = response.reservation.end.shortDateHours();
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
          "images_bikes/" + response.bikeID + "_mini.jpg";

        document.getElementById("deleteReservationdiv").innerHTML =
          '<a class="button small red-dark button-3d rounded icon-right" data-target="#deleteReservation" onclick="initializeDeleteReservation(\'' +
          reservationID +
          '\')" data-toggle="modal" href="#"><span><?= L::mk_reservations_delete; ?></span></a>';

        displayLanguage();
      }
    },
  });
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
            response.booking.start.shortDateHours() +
            " au bâtiment " +
            response.booking.buildingStart +
            "</li>"
        );
        dest = dest.concat(
          "<li><?= L::mk_reservations_end2; ?>" +
            response.booking.end.shortDateHours() +
            " au bâtiment " +
            response.booking.buildingEnd +
            "</li>"
        );
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
          "images_bikes/" + response.booking.brand.toLowerCase().replace(/ /g, '-') + "_" + response.booking.model.toLowerCase().replace(/ /g, '-') + "_" + response.booking.frameType.toLowerCase() + ".jpg";

        //Client Before

        var name = response.clientBefore.name;
        var surname = response.clientBefore.surname;
        var phone = response.clientBefore.phone;
        var mail = response.clientBefore.mail;

        if (
          typeof response.clientBefore.name == "undefined" ||
          response.clientBefore.name == ""
        ) {
          var dest = "<?= L::mk_reservations_nobody; ?>";
        } else {
          var dest =
            "<li><?= L::mk_reservations_nameNfirst; ?>" +
            name +
            " " +
            surname +
            "</li><li><?= L::mk_reservations_phoneNbr2; ?>" +
            phone +
            "</li><li><?= L::mk_reservations_mail2; ?>" +
            mail +
            "</li><li><?= L::mk_reservations_returnBikeThe; ?>" +
            response.clientBefore.end.shortDateHours() +
            "</li>";
        }

        document.getElementById("futureBookingBefore").innerHTML = dest;

        //Client After

        var name = response.clientAfter.name;
        var surname = response.clientAfter.surname;
        var phone = response.clientAfter.phone;
        var mail = response.clientAfter.mail;

        if (
          typeof response.clientAfter.name == "undefined" ||
          response.clientAfter.name == ""
        ) {
          var dest = "<?= L::mk_reservations_nobody; ?>";
        } else {
          var dest =
            "<li><?= L::mk_reservations_nameNfirst; ?>" +
            name +
            " " +
            surname +
            "</li><li><?= L::mk_reservations_phoneNbr2; ?>" +
            phone +
            "</li><li><?= L::mk_reservations_mail2; ?>" +
            mail +
            "</li><li><?= L::mk_reservations_takeBackThe; ?>" +
            response.clientAfter.start.shortDateHours() +
            "</li>";
        }

        document.getElementById("futureBookingAfter").innerHTML = dest;
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
          '<table class="table table-condensed" id="previousBookingsTable" data-order=\'[[ 0, "desc" ]]\' data-page-length=\'5\'><h4><?= L::mk_reservations_past; ?></h4>';
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
          var building_start_en = response.booking[i].building_start_en;
          var building_start_nl = response.booking[i].building_start_nl;
          var building_end_fr = response.booking[i].building_end_fr;
          var building_end_en = response.booking[i].building_end_en;
          var building_end_nl = response.booking[i].building_end_nl;
          var frame_number = response.booking[i].frameNumber;
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
            building_start_fr +
            " <span><?= L::mk_reservations_at; ?></span> " +
            response.booking[i].start.shortHours() +
            '</td><td data-sort="' +
            new Date(response.booking[i].start).getTime() +
            '">' +
            response.booking[i].end.shortDate() +
            " - " +
            building_end_fr +
            " <span><?= L::mk_reservations_at; ?></span> " +
            response.booking[i].end.shortHours() +
            "</td><td>" +
            frame_number +
            '</td></tr>';
            //<td><a class="button small red rounded effect" data-target="#entretien2" data-toggle="modal" href="#" onclick="initializeEntretien2(\'' +
            //bikeID +
            //'\')"><i class="fa fa-wrench"></i><span><?= L::mk_reservations_maintenance; ?></span></a></td>


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

        //Booking futurs

        var dest = "";
        if (response.booking.codePresence == false) {
          var tempFutureBookings =
            '<table class="table table-condensed" id="futureBookingsTable" data-order=\'[[ 0, "desc" ]]\' data-page-length=\'5\'><h4><?= L::mk_reservations_futur_reservations; ?></h4><thead><tr><th><?= L::mk_reservations_id; ?></th><th><span><?= L::mk_reservations_start; ?></span></th><th><span><?= L::mk_reservations_stop; ?></span></th><th><span><?= L::mk_reservations_bike; ?></span></th></tr></thead><tbody>';
        } else {
          var tempFutureBookings =
            '<table class="table table-condensed" id="futureBookingsTable" data-order=\'[[ 0, "desc" ]]\' data-page-length=\'5\'><h4><?= L::mk_reservations_futur_reservations; ?></h4><thead><tr><th><?= L::mk_reservations_id; ?></th><th><span><?= L::mk_reservations_start; ?></span></th><th><span><?= L::mk_reservations_stop; ?></span></th><th><span><?= L::mk_reservations_bike; ?></span></th><th><?= L::mk_reservations_code; ?></th></tr></thead><tbody>';
        }
        dest = dest.concat(tempFutureBookings);
        var length =
          parseInt(response.future_bookings) +
          parseInt(response.previous_bookings);
        while (i < length) {
          var building_start_fr = response.booking[i].building_start_fr;
          var building_start_en = response.booking[i].building_start_en;
          var building_start_nl = response.booking[i].building_start_nl;
          var building_end_fr = response.booking[i].building_end_fr;
          var building_end_en = response.booking[i].building_end_en;
          var building_end_nl = response.booking[i].building_end_nl;
          var frame_number = response.booking[i].frameNumber;
          var booking_id = response.booking[i].bookingID;
          var annulation = response.booking[i].annulation;

          if (response.booking.codePresence == false) {
            var tempFutureBookings =
              '<tr><td><a href="#" name="' +
              response.booking[i].bookingID +
              '" class="showBooking">' +
              response.booking[i].bookingID +
              "</a></td><td>" +
              response.booking[i].start.shortDate() +
              " - " +
              building_start_fr +
              " <span><?= L::mk_reservations_at; ?></span> " +
              response.booking[i].start.shortHours() +
              "</td><td>" +
              response.booking[i].end.shortDate() +
              " - " +
              building_end_fr +
              " <span><?= L::mk_reservations_at; ?></span> " +
              response.booking[i].end.shortHours() +
              "</td><td>" +
              frame_number +
              '</td><td><a class="button small green rounded effect" onclick="showBooking(' +
              booking_id +
              ')"><span>+</span></a></td>';
          } else {
            code = response.booking[i].codeValue;
            if (code.length == 3) {
              code = "0" + code;
            } else if (code.length == 2) {
              code = "00" + code;
            } else if (code.length == 1) {
              code = "000" + length;
            }

            var tempFutureBookings =
              '<tr><td><a href="#" name="' +
              response.booking[i].bookingID +
              '" class="showBooking">' +
              response.booking[i].bookingID +
              "</a></td><td>" +
              response.booking[i].start.shortDate() +
              " - " +
              building_start_fr +
              " <span><?= L::mk_reservations_at; ?></span> " +
              response.booking[i].start.shortHours() +
              "</td><td>" +
              response.booking[i].end.shortDate() +
              " - " +
              building_end_fr +
              " <span><?= L::mk_reservations_at; ?></span> " +
              response.booking[i].end.shortHours() +
              "</td><td>" +
              frame_number +
              "</td><td>" +
              code +
              '</td><td><a class="button small green rounded effect" onclick="showBooking(' +
              booking_id +
              ')"><span>+</span></a></td>';
          }
          if (annulation) {
            var tempAnnulation =
              '<td><a class="button small red rounded effect" onclick="cancelBooking(' +
              booking_id +
              ')"><i class="fa fa-times"></i><span>annuler</span></a></td></td></tr>';
            tempFutureBookings = tempFutureBookings.concat(tempAnnulation);
          } else {
            var tempAnnulation = "</td></tr>";
            tempFutureBookings = tempFutureBookings.concat(tempAnnulation);
          }
          dest = dest.concat(tempFutureBookings);

          i++;
        }
        var tempFutureBookings = "</tbody></table>";
        dest = dest.concat(tempFutureBookings);

        //affichage du résultat de la recherche
        document.getElementById("futureBookings").innerHTML = dest;

        displayLanguage();

        var classname = document.getElementsByClassName("showBooking");
        for (var j = 0; j < classname.length; j++) {
          classname[j].addEventListener(
            "click",
            function () {
              showBooking(this.name);
            },
            false
          );
        }

        /*if ( $.fn.dataTable.isDataTable( '#futureBookingsTable' ) ) {
                    table = $('#futureBookingsTable').DataTable();
                }
                else {
                    table = $('#futureBookingsTable').DataTable( {
                        paging: true,
                        "lengthChange": false,
                        searching: false,
                        "language": {
                          "emptyTable": "Pas de réservations futures"
                        }
                    } );
                }*/
      } else {
        console.log(response.message);
      }
    },
  });
}
