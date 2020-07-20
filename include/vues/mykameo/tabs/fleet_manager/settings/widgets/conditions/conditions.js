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