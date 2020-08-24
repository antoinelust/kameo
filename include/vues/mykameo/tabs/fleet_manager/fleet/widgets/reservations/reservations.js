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
	var starting_date = $(".form_date_start > input").val();
	if (starting_date == "")
		starting_date = "1970-01-01";
	
    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date(starting_date), new Date($(".form_date_end").data("datetimepicker").getDate()));
}


function reservation_listing(){
    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));
}


function get_reservations_listing(bike, date_start, date_end){
        
    var frameNumber='';
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
        day = '' + (d.getDate() + 1),
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
        data: { "email": email, "bikeValue": bikeValue, "dateStart": date_start_string, "frameNumber": frameNumber, "dateEnd": date_end_string},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var i=0;
                var dest="";
                var temp="<table class=\"table table-condensed\"><thead><tr><th><span class=\"fr-inline text-green\">Réf.</span></th><th><span class=\"fr-inline text-green\">Vélo</span><span class=\"en-inline text-green\">Bike</span><span class=\"nl-inline text-green\">Bike</span></th><th><span class=\"fr-inline text-green\">Départ</span><span class=\"en-inline text-green\">Depart</span><span class=\"nl-inline text-green\">Depart</span></th><th><span class=\"fr-inline text-green\">Fin</span><span class=\"en-inline text-green\">End</span><span class=\"nl-inline text-green\">End</span></th><th><span class=\"fr-inline text-green\">Utilisateur</span><span class=\"en-inline text-green\">User</span><span class=\"nl-inline text-green\">User</span></th></tr></thead><tbody>";
                dest=dest.concat(temp);
                
                var bikes = [];
                
                var dest2="";
                temp2="<li><a href=\"#\" onclick=\"bikeFilter('Sélection de vélo')\">Tous les vélos</a></li><li class=\"divider\"></li>";
                dest2=dest2.concat(temp2);
                
                
                while (i < response.bookingNumber){
                    var temp="<tr><td><a data-target=\"#reservationDetails\" name=\""+response.booking[i].reservationID+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillReservationDetails(this.name)\">"+response.booking[i].reservationID+"</a></td><td>"+response.booking[i].bikeID+" - "+response.booking[i].frameNumber+"</td><td>"+response.booking[i].dateStart.shortDate()+" "+response.booking[i].dateStart.substring(11,16)+" - "+response.booking[i].buildingStart+"</td><td>"+response.booking[i].dateEnd.shortDate()+" "+response.booking[i].dateEnd.substring(11,16)+" - "+response.booking[i].buildingEnd+"</td><td>"+response.booking[i].user+"</td></tr>";
                    dest=dest.concat(temp);
                    
                    if(! bikes.includes(response.booking[i].bikeID)){
                        bikes.push(response.booking[i].bikeID);
                    }
                    i++;
                }
                var temp="</tbody></table>";
                dest=dest.concat(temp);
                document.getElementById('ReservationsList').innerHTML = dest;

                
                bikes.sort();
                bikes.forEach(function(bike){
                    var temp2="<li><a href=\"#\" onclick=\"bikeFilter('"+bike+"')\">"+bike+"</a></li>";
                    dest2=dest2.concat(temp2);
                });
                document.getElementsByClassName('bikeSelection')[0].innerHTML=dest2;
                
                displayLanguage();

            }
        }
    })

}


