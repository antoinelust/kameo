// Goal of this function is to delete the block with result of research
function hideResearch(){
    document.getElementById('velos').innerHTML = "";        
}
    // Goal of this function is to construct the reasearch fields for intake building and deposif buildings.
function constructBuidlingForm() {
    
    var userID= "<?php echo $userID; ?>";
    var langue= "<?php echo $langue; ?>";
    var userFrameNumber = "<?php echo $userFrameNumber; ?>";
    var i=0;
    
    $.ajax({
        url: 'include/booking_building_form.php',
        type: 'post',
        data: { "userFrameNumber": userFrameNumber},
        success: function(response) {
            
            var dest="";
            var tempBuilding="<label for=\"search-bikes-form-intake-building\" class=\"fr\">Où voulez-vous prendre le vélo?</label><label for=\"search-bikes-form-intake-building\" class=\"en\">Where is your departure ?</label><label for=\"search-bikes-form-intake-building\" class=\"nl\">Where is your departure ?</label><br /><select id=\"search-bikes-form-intake-building\" name=\"search-bikes-form-intake-building\">";        
            dest = dest.concat(tempBuilding);

            while (i < response.buildingNumber){
                i++;
                var building_code=response.building[i].building_code;
                var building_fr=response.building[i].fr;
                var building_en=response.building[i].en;
                var building_nl=response.building[i].nl;
                
                var tempBuilding="<option value=\""+building_code+"\">"+building_fr+"</option>";
                dest = dest.concat(tempBuilding);
            }
            var tempBuilding="</select>";
            dest = dest.concat(tempBuilding);
            document.getElementById('start_building_form').innerHTML=dest;
            
            var j=0;
            var dest="";
            var tempBuilding="<label for=\"search-bikes-form-deposit-building\" class=\"fr\">Où voulez-vous prendre le vélo?</label><label for=\"search-bikes-form-deposit-building\" class=\"en\">Where is your departure ?</label><label for=\"search-bikes-form-deposit-building\" class=\"nl\">Where is your departure ?</label><br /><select id=\"search-bikes-form-deposit-building\" name=\"search-bikes-form-deposit-building\">";        
            dest = dest.concat(tempBuilding);

            while (j < response.buildingNumber){
                j++;
                var building_code=response.building[j].building_code;
                var building_fr=response.building[j].fr;
                var building_en=response.building[j].en;
                var building_nl=response.building[j].nl;
                
                var tempBuilding="<option value=\""+building_code+"\">"+building_fr+"</option>";
                dest = dest.concat(tempBuilding);
            }
            var tempBuilding="</select>";
            dest = dest.concat(tempBuilding);
            document.getElementById('deposit_building_form').innerHTML=dest;
            
        }
    });
}
constructBuidlingForm();
 
    
function showBooking(bookingID){
    var dest="";
    var langue= "<?php echo $langue; ?>";

    $.ajax({
        url: 'include/get_future_booking.php',
        type: 'post',
        data: { "bookingID": bookingID},
        success: function(response){
            var name = response.clientBefore.name;
            var surname = response.clientBefore.surname;
            var phone = response.clientBefore.phone;
            var mail = response.clientBefore.mail;
            var depositDay = response.clientBefore.depositDay;
            var depositHour = response.clientBefore.depositHour;

            if(langue=="nl"){
                    var dest="<li>Name: "+name+" "+surname+"</li><li>Phone Number:"+phone+"</li><li>Mail: "+mail+"</li><li>Deposit bike on"+depositDay+" at "+depositHour+"</li>";
            }
            else if (langue=="en"){
                    var dest="<li>Name: "+name+" "+surname+"</li><li>Phone Number:"+phone+"</li><li>Mail: "+mail+"</li><li>Deposit bike on"+depositDay+" at "+depositHour+"</li>";
            } else{
                    var dest="<li>Nom et prénom: "+name+" "+surname+"</li><li>Numéro de téléphone:"+phone+"</li><li>Adresse mail: "+mail+"</li><li>Dépose le vélo le "+depositDay+" à "+depositHour+"</li>";
            }
            document.getElementById('futureBookingBefore').innerHTML = dest;

            var name = response.clientAfter.name;
            var surname = response.clientAfter.surname;
            var phone = response.clientAfter.phone;
            var mail = response.clientAfter.mail;
            var intakeDay = response.clientAfter.intakeDay;
            var intakeHour = response.clientAfter.intakeHour;
            
            if(langue=="nl"){
                    var dest="<li>Name: "+name+" "+surname+"</li><li>Phone Number:"+phone+"</li><li>Mail: "+mail+"</li><li>Will take bike on"+intakeDay+" at "+intakeHour+"</li>";
            }
            else if (langue=="en"){
                    var dest="<li>Name: "+name+" "+surname+"</li><li>Phone Number:"+phone+"</li><li>Mail: "+mail+"</li><li>Will take bike on"+intakeDay+" at "+intakeHour+"</li>";
            } else{
                    var dest="<li>Nom et prénom: "+name+" "+surname+"</li><li>Numéro de téléphone:"+phone+"</li><li>Adresse mail: "+mail+"</li><li>Reprendra le vélo le "+intakeDay+" à "+intakeHour+"</li>";
            }
            document.getElementById('futureBookingAfter').innerHTML = dest;
	       $('#futureBooking').modal('toggle');

        }
    });
    
    
    
}
    
function getHistoricBookings() {
    var userID= "<?php echo $userID; ?>";
    var langue= "<?php echo $langue; ?>";
        $.ajax({
        url: 'include/get_historic_bookings.php',
        type: 'post',
        data: { "userID": userID},
        success: function(response) {
        var i=0;
        var dest="";
            
        if(langue=="nl"){
                    var tempHistoricBookings="<table class=\"table table-condensed\"><h4>Previous Bookings:</h4><thead><tr><th>Date</th><th>Start</th><th>End</th><th>Bike</th></tr></thead><tbody>";
        }
        else if (langue=="en"){
                    var tempHistoricBookings="<table class=\"table table-condensed\"><h4>Previous Bookings:</h4><thead><tr><th>Date</th><th>Start</th><th>End</th><th>Bike</th></tr></thead><tbody>";
        } else{
                    var tempHistoricBookings="<table class=\"table table-condensed\"><h4>Réservations précédentes:</h4><thead><tr><th>Date</th><th>Start</th><th>End</th><th>Bike</th></tr></thead><tbody>";
        }
        
        dest = dest.concat(tempHistoricBookings);
        while (i < response.previous_bookings)
        {
            var day=response.booking[i].day;            
            var hour_start=response.booking[i].hour_start;
            var hour_end=response.booking[i].hour_end;
            var building_start = response.booking[i].building_start;
            var building_end = response.booking[i].building_end;
            var frame_number=response.booking[i].frameNumber;
            
            if(langue=="nl"){
                var tempHistoricBookings ="<tr><td>"+day+"</td><td>"+building_start+" at "+hour_start+"</td><td>"+building_end+" at "+hour_end+"</td><td>"+frame_number+"</td></tr>";
            }
            else if (langue=="en"){
                var tempHistoricBookings ="<tr><td>"+day+"</td><td>"+building_start+" at "+hour_start+"</td><td>"+building_end+" at "+hour_end+"</td><td>"+frame_number+"</td></tr>";
            } else{
                var tempHistoricBookings ="<tr><td>"+day+"</td><td>"+building_start+" à "+hour_start+"</td><td>"+building_end+" à "+hour_end+"</td><td>"+frame_number+"</td></tr>";
            }
            dest = dest.concat(tempHistoricBookings);
            i++;

        }
        var tempHistoricBookings="</tbody></table>";
        dest = dest.concat(tempHistoricBookings);

        //affichage du résultat de la recherche
        document.getElementById('historicBookings').innerHTML = dest;

        //Booking futurs
        
        var dest="";
            
        if(langue=="nl"){
                    var tempFutureBookings="<table class=\"table table-condensed\"><h4>Next Bookings:</h4><thead><tr><th>Date</th><th>Start</th><th>End</th><th>Bike</th></tr></thead><tbody>";
        }else if (langue=="en"){
                    var tempFutureBookings="<table class=\"table table-condensed\"><h4>Next Bookings:</h4><thead><tr><th>Date</th><th>Start</th><th>End</th><th>Bike</th></tr></thead><tbody>";
        } else{
                    var tempFutureBookings="<table class=\"table table-condensed\"><h4>Vos réservations futures:</h4><thead><tr><th>Date</th><th>Départ</th><th>Arrivée</th><th>Vélo</th></tr></thead><tbody>";
        }
        
        dest = dest.concat(tempFutureBookings);
        var length = parseInt(response.future_bookings)+parseInt(response.previous_bookings);
        while (i < length)
        {
            var day=response.booking[i].day;
            var hour_start=response.booking[i].hour_start;
            var hour_end=response.booking[i].hour_end;
            var building_start = response.booking[i].building_start;
            var building_end = response.booking[i].building_end;
            var frame_number=response.booking[i].frameNumber;
            var booking_id=response.booking[i].bookingID;
            
            if(langue=="nl"){
                var tempFutureBookings ="<tr><td>"+day+"</td><td>"+building_start+" at "+hour_start+"</td><td>"+building_end+" at "+hour_end+"</td><td>"+frame_number+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a></td></td></tr>";
            } else if (langue=="en"){
                var tempFutureBookings ="<tr><td>"+day+"</td><td>"+building_start+" at "+hour_start+"</td><td>"+building_end+" at "+hour_end+"</td><td>"+frame_number+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a></td></td></tr>";
            } else{
                var tempFutureBookings ="<tr><td>"+day+"</td><td>"+building_start+" à "+hour_start+"</td><td>"+building_end+" à "+hour_end+"</td><td>"+frame_number+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a></td></td></tr>";
            }
            dest = dest.concat(tempFutureBookings);
            i++;

        }
        var tempFutureBookings="</tbody></table>";
        dest = dest.concat(tempFutureBookings);

        //affichage du résultat de la recherche
        document.getElementById('futureBookings').innerHTML = dest;

		}
    });
}
getHistoricBookings();
