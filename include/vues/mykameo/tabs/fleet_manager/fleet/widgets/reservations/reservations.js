function bikeFilter(e){
    document.getElementsByClassName('bikeSelectionText')[0].innerHTML=e;
	var starting_date = $(".form_date_start > input").val();
	if (starting_date == "")
		starting_date = "1970-01-01";
	
    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date(starting_date), new Date($(".form_date_end").data("datetimepicker").getDate()));
}