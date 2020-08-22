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
  url: 'apis/Kameo/get_company_conditions.php',
  type: 'post',
  data: { "email": email, "id": id},

  success: function(response){
	if(response.response == 'error') {
	  console.log(response.message);
	}
	if(response.response == 'success'){
	  $('#widget-updateCompanyConditions-form input[name=action]').val("update");

	  $('#widget-updateCompanyConditions-form input[name=id]').val(id);
	  if(response.companyConditions.name=="generic"){
		$('#widget-updateCompanyConditions-form input[name=name]').val("Conditions génériques");
		$('#widget-updateCompanyConditions-form input[name=name]').prop('readonly', true);

	  }else{
		$('#widget-updateCompanyConditions-form input[name=name]').val(response.companyConditions.name);
		$('#widget-updateCompanyConditions-form input[name=name]').prop('readonly', false);

	  }
	  $('#widget-updateCompanyConditions-form input[name=daysInAdvance]').val(response.companyConditions.bookingDays);
	  $('#widget-updateCompanyConditions-form input[name=bookingLength]').val(response.companyConditions.bookingLength);
	  $('#widget-updateCompanyConditions-form input[name=bookingsPerYear]').val(response.companyConditions.maxBookingsPerYear);
	  $('#widget-updateCompanyConditions-form input[name=bookingsPerMonth]').val(response.companyConditions.maxBookingsPerMonth);
	  $('#widget-updateCompanyConditions-form input[name=startIntakeBooking]').val(response.companyConditions.hourStartIntakeBooking);
	  $('#widget-updateCompanyConditions-form input[name=endIntakeBooking]').val(response.companyConditions.hourEndIntakeBooking);
	  $('#widget-updateCompanyConditions-form input[name=startDepositBooking]').val(response.companyConditions.hourStartDepositBooking);
	  $('#widget-updateCompanyConditions-form input[name=endDepositBooking]').val(response.companyConditions.hourEndDepositBooking);

	  var dest="";
	  if(response.companyConditions.mondayIntake==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingMonday\" checked value=\""+response.companyConditions.mondayIntake+"\">Lundi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingMonday\" value=\""+response.companyConditions.mondayIntake+"\">Lundi<br>";
	  }
	  dest=dest.concat(temp);

	  if(response.companyConditions.tuesdayIntake==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingTuesday\" checked value=\""+response.companyConditions.tuesdayIntake+"\">Mardi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingTuesday\" value=\""+response.companyConditions.tuesdayIntake+"\">Mardi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.wednesdayIntake==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingWednesday\" checked value=\""+response.companyConditions.wednesdayIntake+"\">Mercredi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingWednesday\" value=\""+response.companyConditions.wednesdayIntake+"\">Mercredi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.thursdayIntake==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingThursday\" checked value=\""+response.companyConditions.thursdayIntake+"\">Jeudi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingThursday\" value=\""+response.companyConditions.thursdayIntake+"\">Jeudi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.fridayIntake==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingFriday\" checked value=\""+response.companyConditions.fridayIntake+"\">Vendredi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingFriday\"  value=\""+response.companyConditions.fridayIntake+"\">Vendredi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.saturdayIntake==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingSaturday\" checked value=\""+response.companyConditions.saturdayIntake+"\">Samedi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingSaturday\" value=\""+response.companyConditions.saturdayIntake+"\">Samedi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.sundayIntake==1){
		temp="<input type=\"checkbox\" name=\"intakeBookingSunday\" checked value=\""+response.companyConditions.sundayIntake+"\">Dimanche<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"intakeBookingSunday\" value=\""+response.companyConditions.sundayIntake+"\">Dimanche<br>";
	  }
	  dest=dest.concat(temp);
	  document.getElementsByClassName('intakeBookingDays')[0].innerHTML = dest;

	  var dest="";
	  if(response.companyConditions.mondayDeposit==1){
		temp="<input type=\"checkbox\" name=\"depositBookingMonday\" checked value=\""+response.companyConditions.mondayDeposit+"\">Lundi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingMonday\" value=\""+response.companyConditions.mondayDeposit+"\">Lundi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.tuesdayDeposit==1){
		temp="<input type=\"checkbox\" name=\"depositBookingTuesday\" checked value=\""+response.companyConditions.tuesdayDeposit+"\">Mardi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingTuesday\" value=\""+response.companyConditions.tuesdayDeposit+"\">Mardi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.wednesdayDeposit==1){
		temp="<input type=\"checkbox\" name=\"depositBookingWednesday\" checked value=\""+response.companyConditions.wednesdayDeposit+"\">Mercredi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingWednesday\" value=\""+response.companyConditions.wednesdayDeposit+"\">Mercredi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.thursdayDeposit==1){
		temp="<input type=\"checkbox\" name=\"depositBookingThursday\" checked value=\""+response.companyConditions.thursdayDeposit+"\">Jeudi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingThursday\" value=\""+response.companyConditions.thursdayDeposit+"\">Jeudi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.fridayDeposit==1){
		temp="<input type=\"checkbox\" name=\"depositBookingFriday\" checked value=\""+response.companyConditions.fridayDeposit+"\">Vendredi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingFriday\" value=\""+response.companyConditions.fridayDeposit+"\">Vendredi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.saturdayDeposit==1){
		temp="<input type=\"checkbox\" name=\"depositBookingSaturday\" checked value=\""+response.companyConditions.saturdayDeposit+"\">Samedi<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingSaturday\" value=\""+response.companyConditions.saturdayDeposit+"\">Samedi<br>";
	  }
	  dest=dest.concat(temp);
	  if(response.companyConditions.sundayDeposit==1){
		temp="<input type=\"checkbox\" name=\"depositBookingSunday\" checked value=\""+response.companyConditions.sundayDeposit+"\">Dimanche<br>";
	  }else{
		temp="<input type=\"checkbox\" name=\"depositBookingSunday\" value=\""+response.companyConditions.sundayDeposit+"\">Dimanche<br>";
	  }
	  dest=dest.concat(temp);
        
      if(response.companyConditions.box=="Y")
          $("#widget-updateCompanyConditions-form input[name=box]").prop('checked', true);
        else
          $("#widget-updateCompanyConditions-form input[name=box]").prop('checked', false);

	  if(response.userAccessNumber==0){
		emailArray=[];
	  }else{
		emailArray=response.companyConditions.email;
	  }
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