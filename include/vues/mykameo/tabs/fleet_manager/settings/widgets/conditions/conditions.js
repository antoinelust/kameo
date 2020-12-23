$( ".fleetmanager" ).click(function() {
    $.ajax({
        url: 'apis/Kameo/initialize_counters.php',
        type: 'post',
        data: { "email": email, "type": "conditions"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('counterConditions').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.conditionsNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.conditionsNumber+"</span>";
            }
        }
    })
})



$( "#settings" ).click(function() {
	list_condition();
});
//APPELER QUAND TU CLIQUE SUR Ajouter un groupe de conditions
  function create_condition(){
    var email= "<?php echo $user_data['EMAIL']; ?>";
    $('#widget-updateCompanyConditions-form input[name=name]').val("");
    $('#widget-updateCompanyConditions-form input[name=name]').prop('readonly', false);

    $('#widget-updateCompanyConditions-form input[name=daysInAdvance]').val("");
    $('#widget-updateCompanyConditions-form input[name=bookingLength]').val("");
    $('#widget-updateCompanyConditions-form input[name=bookingsPerYear]').val("");
    $('#widget-updateCompanyConditions-form input[name=bookingsPerMonth]').val("");
    $('#widget-updateCompanyConditions-form input[name=minutesBeforeCancellation]').val("");
    $('#widget-updateCompanyConditions-form input[name=startIntakeBooking]').val("");
    $('#widget-updateCompanyConditions-form input[name=endIntakeBooking]').val("");
    $('#widget-updateCompanyConditions-form input[name=startDepositBooking]').val("");
    $('#widget-updateCompanyConditions-form input[name=endDepositBooking]').val("");

    var temp="<input type=\"checkbox\" name=\"intakeBookingMonday\" value=\"\">Lundi<br><input type=\"checkbox\" name=\"intakeBookingTuesday\" value=\"\">Mardi<br><input type=\"checkbox\" name=\"intakeBookingWednesday\" value=\"\">Mercredi<br><input type=\"checkbox\" name=\"intakeBookingThursday\" value=\"\">Jeudi<br><input type=\"checkbox\" name=\"intakeBookingFriday\" value=\"\">Vendredi<br><input type=\"checkbox\" name=\"intakeBookingSaturday\" value=\"\">Samedi<br><input type=\"checkbox\" name=\"intakeBookingSunday\" value=\"\">Dimanche<br>";
    var temp2="<input type=\"checkbox\" name=\"depositBookingMonday\" value=\"\">Lundi<br><input type=\"checkbox\" name=\"depositBookingTuesday\" value=\"\">Mardi<br><input type=\"checkbox\" name=\"depositBookingWednesday\" value=\"\">Mercredi<br><input type=\"checkbox\" name=\"depositBookingThursday\" value=\"\">Jeudi<br><input type=\"checkbox\" name=\"depositBookingFriday\" value=\"\">Vendredi<br><input type=\"checkbox\" name=\"depositBookingSaturday\" value=\"\">Samedi<br><input type=\"checkbox\" name=\"depositBookingSunday\" value=\"\">Dimanche<br>";
    document.getElementsByClassName('intakeBookingDays')[0].innerHTML = temp;
    document.getElementsByClassName('depositBookingDays')[0].innerHTML = temp2;
    $('#widget-updateCompanyConditions-form input[name=action]').val("create");

    $.ajax({
      url: 'apis/Kameo/get_company_details.php',
      type: 'post',
      data: { "email": email},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          var i=0;
          var dest="";
          while (i < response.userNumber){
            temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"userAccess[]\" value=\""+response.user[i].email+"\"> "+response.user[i].firstName+" "+response.user[i].name+"</div>";
            dest=dest.concat(temp);
            i++;
          }
          document.getElementById('groupConditionUsers').innerHTML = dest;
        }
      }
    })
  }
  function list_condition() {
    var email= "<?php echo $user_data['EMAIL'] ?>";
    $.ajax({
      url: 'apis/Kameo/get_conditions_listing.php',
      type: 'get',
      data: { "email": email},
      success: function(response){
        if(response.response == 'success'){
          var dest="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Groupes de condition :</h4><h4 class=\"en-inline\">Condition groups:</h4><h4 class=\"nl-inline\">Condition groups:</h4><br><a class=\"button small green button-3d rounded icon-right\" data-target=\"#companyConditions\" data-toggle=\"modal\" onclick=\"create_condition()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un groupe de conditions</span></a><tbody><thead><tr><th><span class=\"fr-inline\">Nom</span><span class=\"en-inline\">Name</span><span class=\"nl-inline\">Naam</span></th><th><span class=\"fr-inline\">Nombre d'utilisateurs</span><span class=\"en-inline\">Groupe size</span><span class=\"nl-inline\">Group size</span></th><th></th></tr></thead>";

          for (var i = 0; i < response.conditionNumber; i++){
            if(response.condition[i].name=="generic"){
              var temp="<tr><th>Conditions génériques</th>";
            }
            else{
              var temp="<tr><th>"+response.condition[i].name+"</th>";
            }
            dest=dest.concat(temp);
            var temp="<th>"+response.condition[i].length+"</th><th><a  data-target=\"#companyConditions\" data-toggle=\"modal\" class=\"text-green\" href=\"#\" onclick=\"get_company_conditions('"+response.condition[i].id+"')\">Mettre à jour</a></th></tr>";
            dest=dest.concat(temp);
          }
          document.getElementById('spanConditionListing').innerHTML = dest;
          displayLanguage();
        }
        else {
          console.log(response.response + ': ' + response.message);
        }
      }
    });
}
function get_company_conditions(id){
var emailArray;
var email= "<?php echo $user_data['EMAIL']; ?>";
$.ajax({
  url: 'apis/Kameo/conditions/conditions.php',
  type: 'get',
  data: { "action": "get_condition_details", "ID": id},

  success: function(response){
	if(response.response == 'error') {
	  console.log(response.message);
	}else	if(response.response == 'success'){
	  $('#widget-updateCompanyConditions-form input[name=action]').val("update");

	  $('#widget-updateCompanyConditions-form input[name=id]').val(id);
	  if(response.name=="generic"){
		$('#widget-updateCompanyConditions-form input[name=name]').val("Conditions génériques");
		$('#widget-updateCompanyConditions-form input[name=name]').prop('readonly', true);

	  }else{
		$('#widget-updateCompanyConditions-form input[name=name]').val(response.NAME);
		$('#widget-updateCompanyConditions-form input[name=name]').prop('readonly', false);

	  }
	  $('#widget-updateCompanyConditions-form input[name=daysInAdvance]').val(response.BOOKING_DAYS);
	  $('#widget-updateCompanyConditions-form input[name=bookingLength]').val(response.BOOKING_LENGTH);
	  $('#widget-updateCompanyConditions-form input[name=bookingsPerYear]').val(response.MAX_BOOKINGS_YEAR);
    $('#widget-updateCompanyConditions-form input[name=bookingsPerMonth]').val(response.MAX_BOOKINGS_MONTH);
    $('#widget-updateCompanyConditions-form input[name=minutesBeforeCancellation]').val(response.MINUTES_FOR_AUTOMATIC_CANCEL);
	  $('#widget-updateCompanyConditions-form input[name=startIntakeBooking]').val(response.HOUR_START_INTAKE_BOOKING);
	  $('#widget-updateCompanyConditions-form input[name=endIntakeBooking]').val(response.HOUR_END_INTAKE_BOOKING);
	  $('#widget-updateCompanyConditions-form input[name=startDepositBooking]').val(response.HOUR_START_DEPOSIT_BOOKING);
	  $('#widget-updateCompanyConditions-form input[name=endDepositBooking]').val(response.HOUR_END_DEPOSIT_BOOKING);


    if(response.BOOKING='Y'){
      $('#widget-updateCompanyConditions-form input[name=booking]').prop("checked", true);
      $('.booking').removeClass("hidden");
    }else{
      $('#widget-updateCompanyConditions-form input[name=booking]').prop("checked", false);
      $('.booking').addClass("hidden");
    }


	  var dest="";
	  if(response.MONDAY_INTAKE==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingMonday\" checked value=\""+response.MONDAY_INTAKE+"\">Lundi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingMonday\" value=\""+response.MONDAY_INTAKE+"\">Lundi<br>";
	  }
	  dest=dest.concat(temp);

	  if(response.TUESDAY_INTAKE==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingTuesday\" checked value=\""+response.TUESDAY_INTAKE+"\">Mardi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingTuesday\" value=\""+response.TUESDAY_INTAKE+"\">Mardi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.WEDNESDAY_INTAKE==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingWednesday\" checked value=\""+response.WEDNESDAY_INTAKE+"\">Mercredi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingWednesday\" value=\""+response.WEDNESDAY_INTAKE+"\">Mercredi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.THURSDAY_INTAKE==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingThursday\" checked value=\""+response.THURSDAY_INTAKE+"\">Jeudi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingThursday\" value=\""+response.THURSDAY_INTAKE+"\">Jeudi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.FRIDAY_INTAKE==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingFriday\" checked value=\""+response.FRIDAY_INTAKE+"\">Vendredi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingFriday\"  value=\""+response.FRIDAY_INTAKE+"\">Vendredi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.SATURDAY_INTAKE==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingSaturday\" checked value=\""+response.SATURDAY_INTAKE+"\">Samedi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingSaturday\" value=\""+response.SATURDAY_INTAKE+"\">Samedi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.SUNDAY_INTAKE==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingSunday\" checked value=\""+response.SUNDAY_INTAKE+"\">Dimanche<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingSunday\" value=\""+response.SUNDAY_INTAKE+"\">Dimanche<br>";
	  }
	  dest=dest.concat(temp);
	  document.getElementsByClassName('intakeBookingDays')[0].innerHTML = dest;

	  var dest="";
	  if(response.MONDAY_DEPOSIT==1){
		temp="<input type=\"checkbox\" name=\"depositBookingMonday\" checked value=\""+response.MONDAY_DEPOSIT+"\">Lundi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingMonday\" value=\""+response.MONDAY_DEPOSIT+"\">Lundi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.TUESDAY_DEPOSIT==1){
		temp="<input type=\"checkbox\" name=\"depositBookingTuesday\" checked value=\""+response.TUESDAY_DEPOSIT+"\">Mardi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingTuesday\" value=\""+response.TUESDAY_DEPOSIT+"\">Mardi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.WEDNESDAY_DEPOSIT==1){
		temp="<input type=\"checkbox\" name=\"depositBookingWednesday\" checked value=\""+response.WEDNESDAY_DEPOSIT+"\">Mercredi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingWednesday\" value=\""+response.WEDNESDAY_DEPOSIT+"\">Mercredi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.THURSDAY_DEPOSIT==1){
		temp="<input type=\"checkbox\" name=\"depositBookingThursday\" checked value=\""+response.THURSDAY_DEPOSIT+"\">Jeudi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingThursday\" value=\""+response.THURSDAY_DEPOSIT+"\">Jeudi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.FRIDAY_DEPOSIT==1){
		temp="<input type=\"checkbox\" name=\"depositBookingFriday\" checked value=\""+response.FRIDAY_DEPOSIT+"\">Vendredi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingFriday\" value=\""+response.FRIDAY_DEPOSIT+"\">Vendredi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.SATURDAY_DEPOSIT==1){
		temp="<input type=\"checkbox\" name=\"depositBookingSaturday\" checked value=\""+response.SATURDAY_DEPOSIT+"\">Samedi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingSaturday\" value=\""+response.SATURDAY_DEPOSIT+"\">Samedi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.SUNDAY_DEPOSIT==1){
		temp="<input type=\"checkbox\" name=\"depositBookingSunday\" checked value=\""+response.SUNDAY_DEPOSIT+"\">Dimanche<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingSunday\" value=\""+response.SUNDAY_DEPOSIT+"\">Dimanche<br>";
	  }
	  dest=dest.concat(temp);

    if(response.LOCKING=="Y")
      $("#widget-updateCompanyConditions-form input[name=box]").prop('checked', true);
    else
      $("#widget-updateCompanyConditions-form input[name=box]").prop('checked', false);

		emailArray=[];
    response.emails.forEach(function (value, i) {
      emailArray.push(value.EMAIL);
    });
	  $.ajax({
		url: 'apis/Kameo/get_company_details.php',
		type: 'post',
		data: { "email": email},
		success: function(response){
		  if(response.response == 'error') {
			console.log(response.message);
		  }
		  if(response.response == 'success'){
			var i=0;
			var dest="";
			while (i < response.userNumber){
			  if(emailArray.includes(response.user[i].email)){
				temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"userAccess[]\" checked value=\""+response.user[i].email+"\"> "+response.user[i].firstName+" "+response.user[i].name+"</div>";
			  }else{
				temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"userAccess[]\" value=\""+response.user[i].email+"\"> "+response.user[i].firstName+" "+response.user[i].name+"</div>";
			  }
			  dest=dest.concat(temp);
			  i++;
			}
			document.getElementById('groupConditionUsers').innerHTML = dest;
		  }
		}
	  })
	  document.getElementsByClassName('depositBookingDays')[0].innerHTML = dest;
	}
  }
})
}

$('#widget-updateCompanyConditions-form input[name=booking]').change(function(){
  var is_checked = $('#widget-updateCompanyConditions-form input[name=booking]').is(":checked");
  if(is_checked){
    $('.booking').removeClass("hidden");
  }
  else {
    $('.booking').addClass("hidden");
  }
})
