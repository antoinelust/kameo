//FONCTION QUI GERE LES PERMISSION (à refaire), SEPARER LA PARTIE QUI GERE LES CONDITIONS
$( ".fleetmanager" ).click(function() {
	get_company_conditions();
	list_condition();
	initialize_counters();
	list_maintenances();
});
//MARCHE QUAND TU CLIQUE SUR CLICK MANAGER MAIS DOIS ETRE DEPLACE SUR MODIFIER LES REGLAGES
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

	  if(response.update){
		document.getElementById('search-bikes-form-intake-hour').addEventListener('change', function () { update_deposit_form()}, false);
		document.getElementsByClassName('clientBikesManagerClick')[0].addEventListener('click', function() { get_bikes_listing()}, false);
		document.getElementsByClassName('usersManagerClick')[0].addEventListener('click', function() { get_users_listing()}, false);
		document.getElementsByClassName('reservationlisting')[0].addEventListener('click', function () { reservation_listing()}, false);
		document.getElementsByClassName('portfolioManagerClick')[0].addEventListener('click', function() { listPortfolioBikes()}, false);

		document.getElementsByClassName('boxManagerClick')[0].addEventListener('click', function() { list_boxes('*')}, false);
		$('.tasksManagerClick').click(function(){
			list_tasks('*', $('.taskOwnerSelection').val(), '<?php echo $user_data['EMAIL'] ?>');
			generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val());
		});
		$('#offerManagerClick').click(function(){
			list_contracts_offers('*');
		});
		$('.ordersManagerClick').click(function(){get_orders_listing()});
		document.getElementsByClassName('feedbackManagerClick')[0].addEventListener('click', function() {list_feedbacks()});
		document.getElementsByClassName('taskOwnerSelection')[0].addEventListener('change', function() { taskFilter()}, false);
		document.getElementsByClassName('taskOwnerSelection2')[0].addEventListener('change', function() { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);
		document.getElementsByClassName('numberOfDays')[0].addEventListener('change', function() { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);
		document.getElementsByClassName('maintenanceManagementClick')[0].addEventListener('click', function() { list_maintenances()}, false);
		if(email=='julien@kameobikes.com' || email=='antoine@kameobikes.com' || email=='thibaut@kameobikes.com' || email=='pierre-yves@kameobikes.com' || email=='test3@kameobikes.com'){
			document.getElementsByClassName('billsManagerClick')[0].addEventListener('click', function() {get_bills_listing('*', '*', '*', '*', email)});
			document.getElementById('cashFlowManagement').classList.remove("hidden");
			document.getElementById('billsManagement').classList.remove("hidden");
			$('.billsTitle').removeClass("hidden");
		}
		var classname = document.getElementsByClassName('administrationKameo');
		for (var i = 0; i < classname.length; i++) {
		  classname[i].classList.remove("hidden");
		}
		document.getElementById('clientManagement').classList.remove("hidden");
		document.getElementById('orderManagement').classList.remove("hidden");
		document.getElementById('portfolioManagement').classList.remove("hidden");
		document.getElementById('bikesManagement').classList.remove("hidden");
		document.getElementById('boxesManagement').classList.remove("hidden");
		document.getElementById('tasksManagement').classList.remove("hidden");
		document.getElementById('feedbacksManagement').classList.remove("hidden");
		document.getElementById('maintenanceManagement').classList.remove("hidden");
		document.getElementById('dashBoardManagement').classList.remove("hidden");
	  }else if(response.companyConditions.administrator=="Y"){
		  document.getElementsByClassName('usersManagerClick')[0].addEventListener('click', function() { get_users_listing()}, false);
		  document.getElementsByClassName('clientBikesManagerClick')[0].addEventListener('click', function() { get_bikes_listing()}, false);
		  document.getElementsByClassName('reservationlisting')[0].addEventListener('click', function () { reservation_listing()}, false);
		  $('.billsTitle').removeClass("hidden");
		  document.getElementById('billsManagement').classList.remove("hidden");
		  document.getElementsByClassName('billsManagerClick')[0].addEventListener('click', function() {get_bills_listing('*', '*', '*', '*', email)});
	  }

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