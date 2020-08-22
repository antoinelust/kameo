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





function bikeFilter(e){
    document.getElementsByClassName('bikeSelectionText')[0].innerHTML=e;
	var starting_date = $(".form_date_start > input").val();
	if (starting_date == "")
		starting_date = "1970-01-01";
	
    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date(starting_date), new Date($(".form_date_end").data("datetimepicker").getDate()));
}