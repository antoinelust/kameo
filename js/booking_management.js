
function initializeDeleteReservation(reservationID){

    $.ajax({
        url: 'include/get_reservation_details.php',
        type: 'post',
        data: { "reservationID": reservationID},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('widget-deleteReservation-form-start').value = response.reservationStartBuilding+" le "+response.reservationStartDate;
                document.getElementById('widget-deleteReservation-form-end').value = response.reservationEndBuilding+" le "+response.reservationEndDate;
                document.getElementById('widget-deleteReservation-form-user').value = response.reservationEmail;
                document.getElementById('widget-deleteReservation-form-ID').value = reservationID;

            }

        }
    })
    $('#reservationDetails').modal('toggle');

}

function initializeUpdateReservation(reservationID){

    $.ajax({
        url: 'include/get_reservation_details.php',
        type: 'post',
        data: { "reservationID": reservationID},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('widget-updateReservation-form-start').value = response.reservationStartBuilding+" le "+response.reservationStartDate;
                document.getElementById('widget-updateReservation-form-end').value = response.reservationEndBuilding+" le "+response.reservationEndDate;
                document.getElementById('widget-updateReservation-form-user').value = response.reservationEmail;
                document.getElementById('widget-updateReservation-form-ID').value = reservationID;
            }

        }
    })
    $('#reservationDetails').modal('toggle');
}



function initialize_booking_counter(){
    var date_start=new Date();
    var date_end=new Date();

    date_start.setMonth(date_start.getMonth()-1);
    var timeStampStart=Math.round(date_start.valueOf()/1000);
    var timeStampEnd=Math.round(date_end.valueOf()/1000);
    var bikeValue="all";



    $.ajax({
        url: 'include/get_reservations_listing.php',
        type: 'post',
        data: { "email": email, "bikeValue": bikeValue, "timeStampStart": timeStampStart, "timeStampEnd": timeStampEnd},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('counterBookings').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bookingNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.bookingNumber+"</span>";
                var counter1=response.bookingNumber;


            }

            date_start.setMonth(date_start.getMonth()-1);
            date_end.setMonth(date_end.getMonth()-1);
            var timeStampStart=(date_start.valueOf()/1000);
            var timeStampEnd=(date_end.valueOf()/1000);

            $.ajax({
            url: 'include/get_reservations_listing.php',
            type: 'post',
            data: { "email": email, "bikeValue": bikeValue, "timeStampStart": timeStampStart, "timeStampEnd": timeStampEnd},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var counter2=response.bookingNumber;


                    if(counter2==0 && counter1>0){
                        var evolution=99999;
                    }
                    if(counter2==0 && counter1==0){
                        var evolution=0;
                    }else{
                        var evolution=Math.round((counter1-counter2)/counter2*100);
                    }


                    //if(evolution >0.1){
                    evolution=10;
                        var temp="\
                        <div class=\'col-md-4\">\
                            <p>Évolution du nombre de réservations rapport au mois précédent:<br>\
                            <strong class=\"text-green\">"+evolution+" %</strong></p>\
                            </div>\
                            <div class=\"col-md-8\">\
                                 <div class=\"progress-bar-container radius color\">\
                                      <div class=\"progress-bar\" data-percent=\""+evolution+"\" data-delay=\"100\" data-type=\"%\">\
                                      </div>\
                            </div>\
                        </div>";
                    document.getElementById('progress-bar-bookings').innerHTML=temp;
                    //}
                    //else if(evolution >= 0){
                    //    document.getElementById('progress-bar-bookings').innerHTML="<div class=\"progress-bar-container radius title-up color-sun-flower\"><div class=\"progress-bar\" data-percent=\""+evolution+"\" //data-delay=\"200\" data-type=\"%\"><div class=\"progress-title fr\">Évolution du nombre de réservations rapport au mois précédent</div></div></div>";
                    //}else{
                    //    document.getElementById('progress-bar-bookings').innerHTML="<div class=\"progress-bar-container radius title-up color-red \"><div class=\"progress-bar\" data-percent=\""+evolution+"\" data-delay=\"200\" data-type=\"%\"><div class=\"progress-title fr\">Évolution du nombre de réservations rapport au mois précédent</div></div></div>";
                    //}
                }
            }
            })
        }
    })
}


function get_reservations_listing(bike, date_start, date_end){
    var frameNumber='';
    var timeStampStart=(date_start.valueOf()/1000);
    var timeStampEnd=(date_end.valueOf()/1000);
    if((typeof bike == "undefined") || bike == "" || bike=="Sélection de vélo"){
        var bikeValue="all";
    } else {
        var bikeValue=bike;
    }
    if(timeStampStart==''){
        d = new Date(new Date().getFullYear(), 0, 1);
        timeStampStart=+d;
        timeStampStart=timeStampStart/1000;
    }
    if(timeStampEnd==''){
        timeStampEnd=Date.now();
        timeSt
        ampEnd=Math.round(timeStampEnd/1000);
    }
    $.ajax({
        url: 'include/get_reservations_listing.php',
        type: 'post',
        data: { "email": email, "bikeValue": bikeValue, "timeStampStart": timeStampStart, "frameNumber": frameNumber, "timeStampEnd": timeStampEnd},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var i=0;
                var dest="";
                var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline\"></div><tbody><thead><tr><th><span class=\"fr-inline text-green\">Réf.</span></th><th><span class=\"fr-inline text-green\">Vélo</span><span class=\"en-inline text-green\">Bike</span><span class=\"nl-inline text-green\">Bike</span></th><th><span class=\"fr-inline text-green\">Départ</span><span class=\"en-inline text-green\">Depart</span><span class=\"nl-inline text-green\">Depart</span></th><th><span class=\"fr-inline text-green\">Fin</span><span class=\"en-inline text-green\">End</span><span class=\"nl-inline text-green\">End</span></th><th><span class=\"fr-inline text-green\">Utilisateur</span><span class=\"en-inline text-green\">User</span><span class=\"nl-inline text-green\">User</span></th></tr></thead>";
                dest=dest.concat(temp);
                while (i < response.bookingNumber){

                    var temp="<tr><th><a data-target=\"#reservationDetails\" name=\""+response.booking[i].reservationID+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillReservationDetails(this.name)\">"+response.booking[i].reservationID+"</a></th><th><a  data-target=\"#bikeDetailsFull\" name=\""+response.booking[i].frameNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillBikeDetails(this.name)\">"+response.booking[i].frameNumber+"</a></th><th class=\"fr-cell\">"+response.booking[i].dateStartFR+"</th><th class=\"en-cell\">"+response.booking[i].dateStartEN+"</th><th class=\"nl-cell\">"+response.booking[i].dateStartNL+"</th><th class=\"fr-cell\">"+response.booking[i].dateEndFR+"</th><th class=\"en-cell\">"+response.booking[i].dateEndEN+"</th><th class=\"nl-cell\">"+response.booking[i].dateEndNL+"</th><th>"+response.booking[i].user+"</th></tr>";
                    dest=dest.concat(temp);

                    i++;

                }
                var temp="</tobdy></table>";
                dest=dest.concat(temp);
                document.getElementById('ReservationsList').innerHTML = dest;

                displayLanguage();

            }
        }
    })

}


function reservation_listing(){
    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));
    $('#ReservationsListing').modal('toggle');

}


function fillReservationDetails(element)
{
    var reservationID=element;
    $.ajax({
            url: 'include/get_reservation_details.php',
            type: 'post',
            data: { "reservationID": reservationID},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{
                    document.getElementsByClassName("reservationNumber")[0].innerHTML=reservationID;
                    document.getElementsByClassName("reservationStartDate")[0].innerHTML=response.reservationStartDate;
                    document.getElementsByClassName("reservationEndDate")[0].innerHTML=response.reservationEndDate;
                    document.getElementsByClassName("reservationStartBuilding")[0].innerHTML=response.reservationStartBuilding;
                    document.getElementsByClassName("reservationEndBuilding")[0].innerHTML=response.reservationEndBuilding;
                    document.getElementsByClassName("reservationBikeNumber")[0].innerHTML=response.reservationBikeNumber;
                    document.getElementsByClassName("reservationEmail")[0].innerHTML=response.reservationEmail;
                    document.getElementsByClassName("reservationBikeImage")[0].src="images_bikes/"+response.reservationBikeNumber+"_mini.jpg";

                    //document.getElementById('updateReservationdiv').innerHTML="<a class=\"button small green button-3d rounded icon-right\" data-target=\"#updateReservation\" onclick=\"initializeUpdateReservation('"+reservationID+"')\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Modifier</span><span class=\"en-inline\">Update</span></a>";
                    document.getElementById('deleteReservationdiv').innerHTML="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#deleteReservation\" onclick=\"initializeDeleteReservation('"+reservationID+"')\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Supprimer</span><span class=\"en-inline\">Delete</span></a>";

                    displayLanguage();
                }

                }
            })

}




function showBooking(bookingID){
    var dest="";

    $.ajax({
        url: 'include/get_future_booking.php',
        type: 'post',
        data: { "bookingID": bookingID},
        success: function(response){
            if(response.response=="success"){
                var name = response.clientBefore.name;
                var surname = response.clientBefore.surname;
                var phone = response.clientBefore.phone;
                var mail = response.clientBefore.mail;
                var depositDay = response.clientBefore.depositDay;
                var depositHour = response.clientBefore.depositHour;
                var code=response.booking.code
                var ID=response.booking.ID


                if(langue=="nl"){
                    var dest="<li class=\"nl\">Naam: "+name+" "+surname+"</li><li class=\"nl\">Telefoonnummer:"+phone+"</li><li class=\"nl\">Mail: "+mail+"</li><li class=\"nl\">Stort fiets op "+depositDay+" om "+depositHour+"</li>";
                } else if (langue == "en"){
                    var dest="<li class=\"en\">Name: "+name+" "+surname+"</li><li class=\"en\">Phone Number:"+phone+"</li><li class=\"en\">Mail: "+mail+"</li><li class=\"en\">Returns bike on" +depositDay+" at "+depositHour+"</li>";
                } else {
                    var dest="<li class=\"fr\">Nom et prénom: "+name+" "+surname+"</li><li class=\"fr\">Numéro de téléphone: "+phone+"</li><li class=\"fr\">Adresse mail: "+mail+"</li><li class=\"fr\">Dépose le vélo le "+depositDay+" à "+depositHour+"</li>";
                }
                document.getElementById('futureBookingBefore').innerHTML = dest;

                temp="<li class=\"fr\">Numéro de réservation : "+ID+"</li>";
                dest=temp;
                if(code){
                    if(code.length==3){
                        code="0"+code;
                    }else if(code.length==2){
                        code="00"+code;
                    }else if(code.length==1){
                        code="000"+length;
                    }
                    temp="<li class=\"fr\">Code : "+code+"</li>";
                    dest=dest.concat(temp);
                }
                dest=dest.concat("<li class=\"fr\">Début : "+response.booking.intakeDay+"-"+response.booking.intakeHour+" au bâtiment "+response.booking.buildingStart+"</li>")
                dest=dest.concat("<li class=\"fr\">Fin : "+response.booking.depositDay+"-"+response.booking.depositHour+" au bâtiment "+response.booking.buildingEnd+"</li>")
                document.getElementById('bookingInformation').innerHTML=dest;


                var name = response.clientAfter.name;
                var surname = response.clientAfter.surname;
                var phone = response.clientAfter.phone;
                var mail = response.clientAfter.mail;
                var intakeDay = response.clientAfter.intakeDay;
                var intakeHour = response.clientAfter.intakeHour;

                if(typeof response.clientAfter.name == 'undefined' || response.clientAfter.name==''){
                    if(langue=="nl"){
                        var dest="Niemand.";
                    }
                    else if (langue=="en"){
                            var dest="Nobody.";
                    } else{
                            var dest="Personne.";
                    }
                }
                else{
                    if(langue=="nl"){
                        var dest="<li>Naam: "+name+" "+surname+"</li><li>Telefoonnummer:"+phone+"</li><li>Mail: "+mail+"</li><li>Neem de fiets mee"+intakeDay+" om "+intakeHour+"</li>";
                    }
                    else if (langue=="en"){
                            var dest="<li>Name: "+name+" "+surname+"</li><li>Phone Number:"+phone+"</li><li>Mail: "+mail+"</li><li>Will take bike on"+intakeDay+" at "+intakeHour+"</li>";
                    } else{
                            var dest="<li>Nom et prénom: "+name+" "+surname+"</li><li>Numéro de téléphone:"+phone+"</li><li>Adresse mail: "+mail+"</li><li>Reprendra le vélo le "+intakeDay+" à "+intakeHour+"</li>";
                    }
                }

                document.getElementById('futureBookingAfter').innerHTML = dest;
               $('#futureBooking').modal('toggle');

            }else{
                console.log(response.message);
            }

        }
    });



}

function cancelBooking(bookingID){
    var dest="";

    $.ajax({
        url: 'include/cancel_booking.php',
        type: 'post',
        data: { "bookingID": bookingID},
        success: function(text){

            if (text.response == 'error') {
                $.notify({
                    message: text.message
                }, {
                    type: 'danger'
                });
            }
            else if (text.response == 'success'){
                $.notify({
                    message: text.message
                }, {
                    type: text.response
                });
                getHistoricBookings();
            }

        }
    });
}



function getHistoricBookings() {
    $.ajax({
        url: 'include/get_historic_bookings.php',
        type: 'post',
        data: { "user": email},
        success: function(response) {
            if(response.response=="success"){
                var i=0;
                var dest="";

                var tempHistoricBookings="<table class=\"table table-condensed\"><h4 class=\"fr-inline\">Réservations précédentes:</h4><h4 class=\"en-inline\">Previous Bookings:</h4><h4 class=\"nl-inline\">Vorige reservaties:</h4>";
                dest=dest.concat(tempHistoricBookings);

                var tempHistoricBookings="<ul><li>Depuis le début de l'année : "+response.maxBookingsPerYear+" réservations";
                dest = dest.concat(tempHistoricBookings);

                if(response.maxBookingsPerYearCondition != '9999'){
                    var tempHistoricBookings=" (maximum "+response.maxBookingsPerYearCondition+")</li><li>Depuis le début du mois : "+response.maxBookingsPerMonth+" réservations";
                }else{
                    var tempHistoricBookings="</li><li>Depuis le début du mois : "+response.maxBookingsPerMonth+" réservations";
                }
                dest = dest.concat(tempHistoricBookings);


                if(response.maxBookingsPerMonthCondition != '9999'){
                    var tempHistoricBookings=" (maximum "+response.maxBookingsPerMonthCondition+")</li></ul>";
                }else{
                    var tempHistoricBookings="</li></ul>";
                }

                dest = dest.concat(tempHistoricBookings);




                var tempHistoricBookings="<thead><tr><th><span class=\"fr-inline\">Départ</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Arrivée</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fitse</span></th><th></th></tr></thead><tbody>";
                dest = dest.concat(tempHistoricBookings);

                while (i < response.previous_bookings)
                {
                    var dayStart=response.booking[i].dayStart;
                    var dayEnd=response.booking[i].dayEnd;
                    var hour_start=response.booking[i].hour_start;
                    var hour_end=response.booking[i].hour_end;
                    var building_start_fr = response.booking[i].building_start_fr;
                    var building_start_en = response.booking[i].building_start_en;
                    var building_start_nl = response.booking[i].building_start_nl;
                    var building_end_fr = response.booking[i].building_end_fr;
                    var building_end_en = response.booking[i].building_end_en;
                    var building_end_nl = response.booking[i].building_end_nl;
                    var frame_number=response.booking[i].frameNumber;


                    var tempHistoricBookings ="<tr><td>"+dayStart+ " - "+building_start_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_start+"</td><td>"+dayEnd+" - "+building_end_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_end+"</td><td>"+frame_number+"</td><td><a class=\"button small red rounded effect\" data-target=\"#entretien2\" data-toggle=\"modal\" href=\"#\" onclick=\"initializeEntretien2('"+frame_number+"')\"><i class=\"fa fa-wrench\"></i><span>Entretien</span></a></td></tr>";

                    dest = dest.concat(tempHistoricBookings);
                    i++;

                }


                var tempHistoricBookings="</tbody></table>";
                dest = dest.concat(tempHistoricBookings);

                //affichage du résultat de la recherche
                document.getElementById('historicBookings').innerHTML = dest;

                //Booking futurs

                var dest="";
                if(response.booking.codePresence==false){
                    var tempFutureBookings="<table class=\"table table-condensed\"><h4 class=\"fr-inline\">Réservations futures:</h4><h4 class=\"en-inline\">Next bookings:</h4><h4 class=\"nl-inline\">Volgende boekingen:</h4><thead><tr><th><span class=\"fr-inline\">Départ</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Arrivée</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fitse</span></th></tr></thead><tbody>";
                } else{
                    var tempFutureBookings="<table class=\"table table-condensed\"><h4 class=\"fr-inline\">Réservations futures:</h4><h4 class=\"en-inline\">Next bookings:</h4><h4 class=\"nl-inline\">Volgende boekingen:</h4><thead><tr><th><span class=\"fr-inline\">Départ</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Arrivée</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fitse</span></th><th>Code</th></tr></thead><tbody>";
                }
                dest = dest.concat(tempFutureBookings);
                var length = parseInt(response.future_bookings)+parseInt(response.previous_bookings);
                while (i < length)
                {
                    var dayStart=response.booking[i].dayStart;
                    var dayEnd=response.booking[i].dayEnd;
                    var hour_start=response.booking[i].hour_start;
                    var hour_end=response.booking[i].hour_end;
                    var building_start_fr = response.booking[i].building_start_fr;
                    var building_start_en = response.booking[i].building_start_en;
                    var building_start_nl = response.booking[i].building_start_nl;
                    var building_end_fr = response.booking[i].building_end_fr;
                    var building_end_en = response.booking[i].building_end_en;
                    var building_end_nl = response.booking[i].building_end_nl;
                    var frame_number=response.booking[i].frameNumber;
                    var booking_id=response.booking[i].bookingID;
                    var annulation=response.booking[i].annulation;

                    if(response.booking.codePresence==false){
                        var tempFutureBookings ="<tr><td>"+dayStart+ " - "+building_start_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_start+"</td><td>"+dayEnd+" - "+building_end_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_end+"</td><td>"+frame_number+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a></td>";
                    }else{
                        code=response.booking[i].codeValue;
                        if(code.length==3){
                            code="0"+code;
                        }else if(code.length==2){
                            code="00"+code;
                        }else if(code.length==1){
                            code="000"+length;
                        }

                        var tempFutureBookings ="<tr><td>"+dayStart+ " - "+building_start_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_start+"</td><td>"+dayEnd+" - "+building_end_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_end+"</td><td>"+frame_number+"</td><td>"+code+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a></td>";

                    }
                    if(annulation){
                        var tempAnnulation = "<td><a class=\"button small red rounded effect\" onclick=\"cancelBooking("+booking_id+")\"><i class=\"fa fa-times\"></i><span>annuler</span></a></td></td></tr>";
                        tempFutureBookings = tempFutureBookings.concat(tempAnnulation);
                    } else{
                        var tempAnnulation = "</td></tr>";
                        tempFutureBookings = tempFutureBookings.concat(tempAnnulation);
                    }
                    dest = dest.concat(tempFutureBookings);

                    i++;

                }
                var tempFutureBookings="</tbody></table>";
                dest = dest.concat(tempFutureBookings);

                //affichage du résultat de la recherche
                document.getElementById('futureBookings').innerHTML = dest;
                displayLanguage();
            }else{
                console.log(response.message);
            }
        }
    });
}
