

document.getElementById('search-bikes-form-intake-hour').addEventListener('change', function () { update_deposit_form()}, false);



function update_deposit_form(){
    var hour=document.getElementById('search-bikes-form-intake-hour').value;
    var date=document.getElementById('search-bikes-form-day').value;
    var Date1=date.split('-');
    var day=Date1[0];
    var month=(Date1[1]-1);
    var year=Date1[2];
    var Hours=hour.split('h');

    var hour=Hours[0];
    var minute=Hours[1];

    var dateStart = new Date(year, month, day, hour, minute);

    var dateTemp = new Date(year, month, day, hour, minute);

    loadClientConditions()
    .done(function(response){
        bookingLength=parseInt(response.clientConditions.bookingLength);

        var hour=document.getElementById('search-bikes-form-intake-hour').value;
        var Hours1=hour.split('h');
        var hour=Hours1[0];
        var minute=Hours1[1];
        var date=document.getElementById('search-bikes-form-day').value;
        var Date1=date.split('-');
        var day=Date1[0];
        var month=(Date1[1]-1);
        var year=Date1[2];


        var dateEnd = new Date(year, month, day, hour, minute);
        dateEnd.setHours(dateEnd.getHours()+bookingLength);

        var currentDate = new Date(year, month, day, hour, minute);



        var numberOfDays=Math.round((dateEnd-currentDate)/(1000*60*60*24));


        // 1st step: days and month fileds

        var hour=document.getElementById('search-bikes-form-intake-hour').value;
        var date1=document.getElementById('search-bikes-form-day').value;
        var Date1=date.split('-');
        var day=Date1[0];
        var month=(Date1[1]-1);
        var year=Date1[2];
        var Hours1=hour.split('h');
        var hour=Hours1[0];
        var minute=Hours1[1];

        var tempDate = new Date(year, month, day, hour, minute);
        var i=0;
        var j=0;
        var dest ="<select id=\"search-bikes-form-day-deposit\" name=\"search-bikes-form-day-deposit\"  class=\"form-control\">";


        while(i<=numberOfDays){
            if((tempDate.getDay()=="1" && parseInt(response.clientConditions.mondayDeposit)) || (tempDate.getDay()=="2" && parseInt(response.clientConditions.tuesdayDeposit)) || (tempDate.getDay()=="3" && parseInt(response.clientConditions.wednesdayDeposit)) || (tempDate.getDay()=="4" && parseInt(response.clientConditions.thursdayDeposit)) || (tempDate.getDay()=="5" && parseInt(response.clientConditions.fridayDeposit)) || (tempDate.getDay()=="6" && parseInt(response.clientConditions.saturdayDeposit)) || (tempDate.getDay()=="0" && parseInt(response.clientConditions.sundayDeposit))){
                var dayTrad = daysTrad[tempDate.getDay()];
                var bookingDay="<option value=\""+tempDate.getDate()+"-"+(tempDate.getMonth()+1)+"-"+tempDate.getFullYear()+"\" class=\"form-control\">"+dayTrad+" "+tempDate.getDate()+" "+monthTrad[tempDate.getMonth()]+"</option>";
                dest = dest.concat(bookingDay);
            }
            tempDate.setDate(tempDate.getDate()+1);
            i++;

        }
        var bookingDay="</select>";
        dest = dest.concat(bookingDay);
        document.getElementById('booking_day_form_deposit').innerHTML=dest;

        document.getElementById('search-bikes-form-day-deposit').addEventListener('change', function () { update_deposit_hour_form()}, false);

        update_deposit_hour_form();

    });



}

function update_intake_hour_form(){
    loadClientConditions()
    .done(function(response){
        var date1=document.getElementById('search-bikes-form-day').value;
        var Date1=date1.split('-');
        var day=Date1[0];
        var currentDate=new Date();

        if(currentDate.getDate()==day){
            var hours=currentDate.getHours();
            var minutes=currentDate.getMinutes();

            var m = (((minutes + 7.5)/15 | 0) * 15) % 60;
            var h = ((((minutes/105) + .5) | 0) + hours) % 24;
            var dateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), h, m);
        }
        else{
            var m = 0;
            var h = response.clientConditions.hourStartIntakeBooking;
            var dateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), h, m);
        }

        var dest="";

        while(dateTemp.getHours()<response.clientConditions.hourEndIntakeBooking){
            if(dateTemp.getMinutes()=="0"){
                var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
            }else{
                var hourString=dateTemp.getHours()+"h"+dateTemp.getMinutes();
            }

            dateTemp.setMinutes(dateTemp.getMinutes()+ parseInt(15));

            var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
            dest=dest.concat(tempString);
        }

        var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
        var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
        dest=dest.concat(tempString);


        document.getElementById('search-bikes-form-intake-hour').innerHTML=dest;

        update_deposit_form();
    });
}


function update_deposit_hour_form(){
    var hour=document.getElementById('search-bikes-form-intake-hour').value;
    var date1=document.getElementById('search-bikes-form-day').value;
    var Date1=date1.split('-');
    var day=Date1[0];
    var month=Date1[1];


    var Hours1=hour.split('h');

    var hour=Hours1[0];
    var minute=Hours1[1];
    var dateStart = new Date(new Date().getFullYear(), month-1, day, hour, minute);

    loadClientConditions()
    .done(function(response){
        bookingLength=parseInt(response.clientConditions.bookingLength);
        var hour=document.getElementById('search-bikes-form-intake-hour').value;
        var date1=document.getElementById('search-bikes-form-day').value;
        var Date1=date1.split('-');
        var day=Date1[0];
        var month=Date1[1];
        var Hours1=hour.split('h');
        var hour=Hours1[0];
        var minute=Hours1[1];

        var dateEnd = new Date(new Date().getFullYear(), month-1, day, hour, minute);
        dateEnd.setHours(dateEnd.getHours()+bookingLength);

        var date1=document.getElementById('search-bikes-form-day-deposit').value;
        var Date1=date1.split('-');
        var day=Date1[0];
        var month=Date1[1];

        if( dateStart.getDate()!=day || (parseInt(hour) < parseInt(response.clientConditions.hourStartDepositBooking))){
            var currentDepositDate = new Date(new Date().getFullYear(), month-1, day, response.clientConditions.hourStartDepositBooking, '00');
            var dateTemp2 = new Date(new Date().getFullYear(), month-1, day, response.clientConditions.hourStartDepositBooking, '00');
        }else{
            var currentDepositDate = new Date(new Date().getFullYear(), month-1, day, hour, minute);
            var dateTemp2 = new Date(new Date().getFullYear(), month-1, day, hour, minute);
        }

        var Hours=[];
        while(dateTemp2<=dateEnd){
            hours=dateTemp2.getHours();
            if(dateTemp2.getMinutes()=="0"){
                minutes="00";
            }else{
                minutes=dateTemp2.getMinutes();
            }
            if(hours>=response.clientConditions.hourStartDepositBooking && hours<response.clientConditions.hourEndDepositBooking && dateTemp2.getDate()==currentDepositDate.getDate() && dateTemp2.getMonth()==currentDepositDate.getMonth()){
                var hourString=hours+'h'+minutes;
                Hours.push(hourString);
            }
            dateTemp2.setMinutes(dateTemp2.getMinutes()+15);
        }

        var hourBeforeLast=(response.clientConditions.hourEndDepositBooking-1)+'h45';
        var hourInArray=Hours[Hours.length-1];

        if(hourBeforeLast==hourInArray){
            Hours.push(response.clientConditions.hourEndDepositBooking+'h00');
        }


        var dest ="<select name=\"search-bikes-form-deposit-hour\" id=\"search-bikes-form-deposit-hour\" class=\"form-control\">";

        var i=0;
        while(i<Hours.length){
            var bookingHour="<option value=\""+Hours[i]+"\" class=\"form-control\">"+Hours[i]+"</option>";
            dest = dest.concat(bookingHour);
            i++;
        }
        var bookingHour="</select>";
        document.getElementById('booking_hour_form_deposit').innerHTML=dest;
    });
}

// Goal of this function is to construct the reasearch fields
function constructSearchForm(daysToDisplay, bookingLength, administrator, assistance, hourStartIntakeBooking, hourEndIntakeBooking, hourStartDepositBooking, hourEndDepositBooking, mondayIntake, tuesdayIntake, wednesdayIntake, thursdayIntake, fridayIntake, saturdayIntake, sundayIntake, mondayDeposit, tuesdayDeposit, wednesdayDeposit, thursdayDeposit, fridayDeposit, saturdayDeposit, sundayDeposit, maxBookingsPerYear, maxBookingsPerMonth, email){
    if(assistance=="Y"){
      if(user_data['COMPANY']=="Actiris" || user_data['COMPANY']==""){
        $('#entretienPopUP').addClass('hidden');
        document.getElementById('assistanceSpan').innerHTML="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#assistance\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Assistance</span><span class=\"en-inline\">Assistance</span><span class=\"nl-inline\">Hulp</span></a>"
      }else{
        $('#entretienPopUP').removeClass('hidden');
        document.getElementById('assistanceSpan').innerHTML="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#assistance\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Assistance et Entretien</span><span class=\"en-inline\">Assistance and Maintenance</span><span class=\"nl-inline\">Hulp en Onderhoud</span></a>"
      }
    }
    // 1st step: days and month fields
    if(daysToDisplay>0){

        var i=0;
        var j=0;
        var dest ="<select id=\"search-bikes-form-day\" name=\"search-bikes-form-day\"  class=\"form-control\">";
        var dest2 ="<select id=\"search-bikes-form-day-deposit\" name=\"search-bikes-form-day-deposit\"  class=\"form-control\">";

        var tempDate = new Date();

        var tempDate2=tempDate;
        bookingLength=parseInt(bookingLength);
        while(i<=daysToDisplay){
            var afterHour=false;
            if(tempDate.getHours()>=hourEndIntakeBooking){
                tempDate.setHours(hourStartIntakeBooking);
                tempDate.setMinutes(0);
                tempDate.setDate(tempDate.getDate()+1);
                afterHour=true;
            }
            var dayTrad = daysTrad[tempDate.getDay()];
            if((tempDate.getDay()=="1" && parseInt(mondayIntake)) || (tempDate.getDay()=="2" && parseInt(tuesdayIntake)) || (tempDate.getDay()=="3" && parseInt(wednesdayIntake)) || (tempDate.getDay()=="4" && parseInt(thursdayIntake)) || (tempDate.getDay()=="5" && parseInt(fridayIntake)) || (tempDate.getDay()=="6" && parseInt(saturdayIntake)) || (tempDate.getDay()=="0" && parseInt(sundayIntake))){
                var bookingDay="<option value=\""+tempDate.getDate()+"-"+(tempDate.getMonth()+1)+"-"+tempDate.getFullYear()+"\" class=\"form-control\">"+dayTrad+" "+tempDate.getDate()+" "+monthTrad[tempDate.getMonth()]+"</option>";

                dest = dest.concat(bookingDay);
            }

            i++;
            if(afterHour==false){
                tempDate.setDate(tempDate.getDate()+1);
            }
        }
        var bookingDay="</select>";
        dest = dest.concat(bookingDay);
        document.getElementById('booking_day_form').innerHTML=dest;

        document.getElementById('search-bikes-form-day').addEventListener('change', function () { update_intake_hour_form()}, false);


        var currentDate=new Date();

        var hours=currentDate.getHours();
        var minutes=currentDate.getMinutes();

        var m = (((minutes + 15)/15 | 0) * 15) % 60;
        var h = minutes > 45 ? (hours === 23 ? 0 : ++hours) : hours;
        var dateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), h, m);



        var dest="";

        var dateActuelle=dateTemp.getDate()+'-'+(dateTemp.getMonth()+1)+'-'+dateTemp.getFullYear();

        var dateSearch=$('#search-bikes-form-day').val().split('-');


        if(dateActuelle!=$('#search-bikes-form-day').val()){
            dateTemp.setDate(dateSearch[0]);
            dateTemp.setMonth(dateSearch[1] - 1);
            dateTemp.setFullYear(dateSearch[2]);
            dateTemp.setHours(hourStartIntakeBooking);
            dateTemp.setMinutes(0);
        }
        else if(dateTemp.getHours()>=hourEndDepositBooking){
            dateTemp.setHours(hourStartIntakeBooking);
            dateTemp.setMinutes(0);
            dateTemp.setDate(dateTemp.getDate()+1);
        }

        while(dateTemp.getHours()<hourEndIntakeBooking){
            if(dateTemp.getMinutes()=="0"){
                var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
            }else{
                var hourString=dateTemp.getHours()+"h"+dateTemp.getMinutes();
            }

            dateTemp.setMinutes(dateTemp.getMinutes()+ parseInt(15));


            var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
            dest=dest.concat(tempString);
        }

        var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
        var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
        dest=dest.concat(tempString);


        document.getElementById('search-bikes-form-intake-hour').innerHTML=dest;



        // 2nd step: intake and deposit buildings
        var i=0;
        $.ajax({
            url: 'apis/Kameo/booking_building_form.php',
            type: 'post',
            data: { "email": email},
            success: function(response) {
              if(response.response=="success"){
                if(response.buildingNumber=="1"){
                    var dest="";
                    var building_fr=response.building[1].fr;
                    var building_en=response.building[1].en;
                    var building_nl=response.building[1].nl;

                    var tempBuilding="<select id=\"search-bikes-form-intake-building\" name=\"search-bikes-form-intake-building\" class=\"form-control hidden\"><option value=\""+response.building[1].building_code+"\" class=\"fr\" selected=\"selected\">"+building_fr+"</option><option value=\""+response.building[1].building_code+"\" class=\"nl\" selected=\"selected\">"+building_nl+"</option><option value=\""+response.building[1].building_code+"\" class=\"en\" selected=\"selected\">"+building_en+"</option></select><select id=\"search-bikes-form-deposit-building\" name=\"search-bikes-form-deposit-building\" class=\"form-control hidden\"><option value=\""+response.building[1].building_code+"\" class=\"fr\" selected=\"selected\">"+building_fr+"</option><option value=\""+response.building[1].building_code+"\" class=\"nl\" selected=\"selected\">"+building_nl+"</option><option value=\""+response.building[1].building_code+"\" class=\"en\" selected=\"selected\">"+building_en+"</option></select>";
                    dest=tempBuilding;
                } else{
                    var dest="";
                    var tempBuilding="<label for=\"search-bikes-form-intake-building\" class=\" fr\">Où voulez-vous prendre le vélo?</label><label for=\"search-bikes-form-intake-building\" class=\"en\">Where is your departure ?</label><label for=\"search-bikes-form-intake-building\" class=\"nl\">Where is your departure ?</label><select id=\"search-bikes-form-intake-building\" name=\"search-bikes-form-intake-building\" class=\"form-control\">";
                    dest = dest.concat(tempBuilding);
                    while (i < response.buildingNumber){
                        i++;
                        var building_code=response.building[i].building_code;
                        var building_fr=response.building[i].fr;
                        var building_en=response.building[i].en;
                        var building_nl=response.building[i].nl;

                        var tempBuilding="<option value=\""+building_code+"\" class=\"fr\">"+building_fr+"</option><option value=\""+building_code+"\" class=\"en\">"+building_en+"</option><option value=\""+building_code+"\" class=\"nl\">"+building_nl+"</option>";
                        dest = dest.concat(tempBuilding);
                    }
                    var tempBuilding="</select>";
                    dest = dest.concat(tempBuilding);
                    document.getElementById('start_building_form').innerHTML=dest;

                    var j=0;
                    var dest="";
                    var tempBuilding="<label for=\"search-bikes-form-deposit-building\" class=\"fr\">Où voulez-vous rendre le vélo?</label><label for=\"search-bikes-form-deposit-building\" class=\"en\">Where is your arrival ?</label><label for=\"search-bikes-form-deposit-building\" class=\"nl\">Where is your arrival ?</label><select id=\"search-bikes-form-deposit-building\" name=\"search-bikes-form-deposit-building\" class=\"form-control\">";
                    dest = dest.concat(tempBuilding);

                    while (j < response.buildingNumber){
                        j++;
                        var building_code=response.building[j].building_code;
                        var building_fr=response.building[j].fr;
                        var building_en=response.building[j].en;
                        var building_nl=response.building[j].nl;

                        var tempBuilding="<option value=\""+building_code+"\" class=\"fr\">"+building_fr+"</option><option value=\""+building_code+"\" class=\"en\">"+building_en+"</option><option value=\""+building_code+"\" class=\"nl\">"+building_nl+"</option>";
                        dest = dest.concat(tempBuilding);
                        var tempBuilding="</select>";

                    }
                    dest = dest.concat(tempBuilding);
                }
                document.getElementById('deposit_building_form').innerHTML=dest;
                document.getElementById('search-bikes-form-maxBookingPerYear').value=maxBookingsPerYear;
                document.getElementById('search-bikes-form-maxBookingPerMonth').value=maxBookingsPerMonth;
                $('#search-bikes-form-email').val(email);
                displayLanguage();

            }else{
                console.log(response.message);
            }
          }
        });


        update_deposit_form();
    }
}


loadClientConditions()
  .done(function(response){
	constructSearchForm(response.clientConditions.bookingDays, response.clientConditions.bookingLength, response.clientConditions.administrator, response.clientConditions.assistance, response.clientConditions.hourStartIntakeBooking, response.clientConditions.hourEndIntakeBooking, response.clientConditions.hourStartDepositBooking, response.clientConditions.hourEndDepositBooking, response.clientConditions.mondayIntake, response.clientConditions.tuesdayIntake, response.clientConditions.wednesdayIntake, response.clientConditions.thursdayIntake, response.clientConditions.fridayIntake, response.clientConditions.saturdayIntake, response.clientConditions.sundayIntake, response.clientConditions.mondayDeposit, response.clientConditions.tuesdayDeposit, response.clientConditions.wednesdayDeposit, response.clientConditions.thursdayDeposit, response.clientConditions.fridayDeposit, response.clientConditions.saturdayDeposit, response.clientConditions.sundayDeposit, response.clientConditions.maxBookingsPerYear, response.clientConditions.maxBookingsPerMonth, email);
	if (response.clientConditions.cafetaria == "Y"){
		$(".orderBike").removeClass("hidden");
	}
	if (response.clientConditions.administrator == "Y"){
	  $(".fleetmanager").removeClass("hidden");
	}
});
function get_address_building(buildingReference){
  return $.ajax({
    url: 'apis/Kameo/get_address_building.php',
    type: 'post',
    data: { "buildingReference": buildingReference},
    success: function(text){
    }
  });
}
function get_user_address(){
  return $.ajax({
    url: 'apis/Kameo/get_user_address.php',
    type: 'post',
    success: function(response){
    }
  });
}


jQuery("#search-bikes-form").validate({
	submitHandler: function(form){
    var url = form.action;

    $('#travel_information_loading').removeClass("hidden");
    $('#travel_information_error').addClass("hidden");
    $('#travel_information').addClass("hidden");

    var data = new Array();
    data.push({ name: "action", value: "newBooking" });
    data.push({
      name: "intakeDay",
      value: $("#search-bikes-form-day").val(),
    });
    data.push({
      name: "intakeHour",
      value: $("#search-bikes-form-intake-hour").val(),
    });
    data.push({
      name: "intakeBuilding",
      value: $("#search-bikes-form-intake-building").val(),
    });
    data.push({
      name: "depositDay",
      value: $("#search-bikes-form-day-deposit").val(),
    });
    data.push({
      name: "depositHour",
      value: $("#search-bikes-form-deposit-hour").val(),
    });
    data.push({
      name: "depositBuilding",
      value: $("#search-bikes-form-deposit-building").val(),
    });

    $.ajax({
      type: "POST",
      url: url,
      data: data,
      success: function (text) {
        if (text.response == "success") {
          var loaded1=false;
          var loaded2=false;
          //uniquement en dev
          $("body").addClass("loading");
          document.getElementById("travel_information").style.display = "none";
          document.getElementById("velos").style.display = "none";
          var successMessage=text.message;
          var weather;
          var temperature;
          var precipitation;
          var windSpeed;
          var travel_time_bike;
          var travel_time_car;

          if(text.buildingStart == text.buildingEnd){
            get_user_address()
            .done(function(response){
              buildingEnd="<?= L::meteo_home; ?>";
              addressEnd=response.address;
              get_address_building(text.buildingStart)
              .done(function(response){
              addressStart=response.address;
              buildingStart=response.building_fr;
              document.getElementById("meteoStart1").innerHTML=buildingStart;
              document.getElementById("meteoStart2").innerHTML=buildingStart;
              document.getElementById("meteoStart3").innerHTML=buildingStart;
              document.getElementById("meteoStart4").innerHTML=buildingStart;
              document.getElementById("meteoEnd1").innerHTML=buildingEnd;
              document.getElementById("meteoEnd2").innerHTML=buildingEnd;
              document.getElementById("meteoEnd3").innerHTML=buildingEnd;
              document.getElementById("meteoEnd4").innerHTML=buildingEnd;
              displayLanguage();

              get_meteo(text.dateStart, addressStart)
              .done(function(response){
                if(response.response=="success")
                {
                var find = '-';
                var re = new RegExp(find, 'g');
                weather=response.icon.replace(re,"");
                temperature=response.temperature;
                precipitation=response.precipProbability;
                windSpeed=response.windSpeed;
                get_travel_time(text.dateStart, addressStart, addressEnd)
                .done(function(response){
                  if(response.response=="success"){
                    travel_time_bike=response.duration_bike;
                    travel_time_car=response.duration_car;
                    document.getElementById("logo_meteo1").src="images/meteo/"+weather+".png";
                    document.getElementById('temperature_widget1').innerHTML = Math.round(temperature)+" °C";
                    document.getElementById('precipitation_widget1').innerHTML = Math.round(precipitation)+" %";
                    document.getElementById('wind_widget1').innerHTML = windSpeed+" m/s";
                    document.getElementById("logo_meteo2").src="images/meteo/"+weather+".png";
                    document.getElementById('temperature_widget2').innerHTML = Math.round(temperature)+" °C";
                    document.getElementById('precipitation_widget2').innerHTML = Math.round(precipitation)+" %";
                    document.getElementById('wind_widget2').innerHTML = windSpeed+" m/s";
                    document.getElementById("logo_meteo3").src="images/meteo/"+weather+".png";
                    document.getElementById('temperature_widget3').innerHTML = Math.round(temperature)+" °C";
                    document.getElementById('precipitation_widget3').innerHTML = Math.round(precipitation)+" %";
                    document.getElementById('wind_widget3').innerHTML = windSpeed+" m/s";
                    document.getElementById("logo_meteo4").src="images/meteo/"+weather+".png";
                    document.getElementById('temperature_widget4').innerHTML = Math.round(temperature)+" °C";
                    document.getElementById('precipitation_widget4').innerHTML = Math.round(precipitation)+" %";
                    document.getElementById('wind_widget4').innerHTML = windSpeed+" m/s";

                    document.getElementById('walking_duration_widget1').innerHTML = response.duration_walking+" min";
                    document.getElementById('bike_duration_widget1').innerHTML = travel_time_bike+" min";
                    document.getElementById('car_duration_widget1').innerHTML = travel_time_car+" min";
                    document.getElementById('walking_duration_widget2').innerHTML = response.duration_walking+" min";
                    document.getElementById('bike_duration_widget2').innerHTML = travel_time_bike+" min";
                    document.getElementById('car_duration_widget2').innerHTML = travel_time_car+" min";
                    document.getElementById('walking_duration_widget3').innerHTML = response.duration_walking+" min";
                    document.getElementById('bike_duration_widget3').innerHTML = travel_time_bike+" min";
                    document.getElementById('car_duration_widget3').innerHTML = travel_time_car+" min";
                    document.getElementById('walking_duration_widget4').innerHTML = response.duration_walking+" min";
                    document.getElementById('bike_duration_widget4').innerHTML = travel_time_bike+" min";
                    document.getElementById('car_duration_widget4').innerHTML = travel_time_car+" min";
                    get_kameo_score(weather, precipitation, temperature, windSpeed, travel_time_bike, travel_time_car);
                    $('#travel_information_loading').addClass("hidden");
                    $('#travel_information_error').addClass("hidden");
                    $('#travel_information').removeClass("hidden");
                  }else{
                    $('#travel_information_loading').addClass("hidden");
                    $('#travel_information_error').removeClass("hidden");
                    $('#travel_information').addClass("hidden");
                  }


                  loaded1=true;
                  if (loaded2){
                  $.notify({
                    message: successMessage
                  }, {
                    type: 'success'
                  });
                  document.getElementById("travel_information").style.display = "block";
                  document.getElementById("velos").style.display = "block";
                  $("body").removeClass("loading");
                  }
                })
                }else{
                    $("body").removeClass("loading");
                }
              });


              })



            })
          }else{
            get_address_building(text.buildingStart)
            .done(function(response){
              addressStart=response.address;
              buildingStartFr=response.building_fr;
              buildingStartEn=response.building_en;
              buildingStartNl=response.building_nl;
              get_address_building(text.buildingEnd)
              .done(function(response){
              addressEnd=response.address;
              buildingEnd=response.building_fr;
              })
              document.getElementById("meteoStart1").innerHTML=buildingStartFr;
              document.getElementById("meteoStart2").innerHTML=buildingStartFr;
              document.getElementById("meteoStart3").innerHTML=buildingStartFr;
              document.getElementById("meteoStart4").innerHTML=buildingStartFr;
              document.getElementById("meteoEnd1").innerHTML=buildingEnd;
              document.getElementById("meteoEnd2").innerHTML=buildingEnd;
              document.getElementById("meteoEnd3").innerHTML=buildingEnd;
              document.getElementById("meteoEnd4").innerHTML=buildingEnd;

              get_meteo(text.dateStart, addressStart)
              .done(function(response){
                if(response.response=="success")
                {
                var find = '-';
                var re = new RegExp(find, 'g');
                weather=response.icon.replace(re,"");
                temperature=response.temperature;
                precipitation=response.precipProbability;
                windSpeed=response.windSpeed;
                document.getElementById("logo_meteo1").src="images/meteo/"+weather+".png";
                document.getElementById('temperature_widget1').innerHTML = Math.round(temperature)+" °C";
                document.getElementById('precipitation_widget1').innerHTML = Math.round(precipitation)+" %";
                document.getElementById('wind_widget1').innerHTML = windSpeed+" m/s";
                document.getElementById("logo_meteo2").src="images/meteo/"+weather+".png";
                document.getElementById('temperature_widget2').innerHTML = Math.round(temperature)+" °C";
                document.getElementById('precipitation_widget2').innerHTML = Math.round(precipitation)+" %";
                document.getElementById('wind_widget2').innerHTML = windSpeed+" m/s";
                document.getElementById("logo_meteo3").src="images/meteo/"+weather+".png";
                document.getElementById('temperature_widget3').innerHTML = Math.round(temperature)+" °C";
                document.getElementById('precipitation_widget3').innerHTML = Math.round(precipitation)+" %";
                document.getElementById('wind_widget3').innerHTML = windSpeed+" m/s";
                document.getElementById("logo_meteo4").src="images/meteo/"+weather+".png";
                document.getElementById('temperature_widget4').innerHTML = Math.round(temperature)+" °C";
                document.getElementById('precipitation_widget4').innerHTML = Math.round(precipitation)+" %";
                document.getElementById('wind_widget4').innerHTML = windSpeed+" m/s";
                get_travel_time(text.dateStart, addressStart, addressEnd)
                .done(function(response){
                  travel_time_bike=response.duration_bike;
                  travel_time_car=response.duration_car;
                  document.getElementById('walking_duration_widget1').innerHTML = response.duration_walking+" min";
                  document.getElementById('bike_duration_widget1').innerHTML = travel_time_bike+" min";
                  document.getElementById('car_duration_widget1').innerHTML = travel_time_car+" min";
                  document.getElementById('walking_duration_widget2').innerHTML = response.duration_walking+" min";
                  document.getElementById('bike_duration_widget2').innerHTML = travel_time_bike+" min";
                  document.getElementById('car_duration_widget2').innerHTML = travel_time_car+" min";
                  document.getElementById('walking_duration_widget3').innerHTML = response.duration_walking+" min";
                  document.getElementById('bike_duration_widget3').innerHTML = travel_time_bike+" min";
                  document.getElementById('car_duration_widget3').innerHTML = travel_time_car+" min";
                  document.getElementById('walking_duration_widget4').innerHTML = response.duration_walking+" min";
                  document.getElementById('bike_duration_widget4').innerHTML = travel_time_bike+" min";
                  document.getElementById('car_duration_widget4').innerHTML = travel_time_car+" min";
                  get_kameo_score(weather, precipitation, temperature, windSpeed, travel_time_bike, travel_time_car);
                  loaded1=true;
                  if (loaded2){
                  $.notify({
                    message: successMessage
                  }, {
                    type: 'success'
                  });
                  document.getElementById("travel_information").style.display = "block";
                  document.getElementById("velos").style.display = "block";
                  $("body").removeClass("loading");
                  }
                })
                }else{
                    $("body").removeClass("loading");
                }
            });
            })
          }


            date= new Date(text.dateStart);
            var day=date.getDate();
            var month=date.getMonth() + 1;
            var year=date.getFullYear();
            var hours= date.getHours();
            var minutes= date.getMinutes();
            minutes=minutes.toString();
            if (minutes.length ==1){
              minutes="0"+minutes;
            }

            document.getElementById('meteoDate1').innerHTML = day+"/"+ month+"/"+year;
            document.getElementById('meteoDate2').innerHTML = day+"/"+ month+"/"+year;
            document.getElementById('meteoDate3').innerHTML = day+"/"+ month+"/"+year;
            document.getElementById('meteoDate4').innerHTML = day+"/"+ month+"/"+year;

            document.getElementById("meteoHour1").innerHTML=hours+"h"+minutes;
            document.getElementById("meteoHour2").innerHTML=hours+"h"+minutes;
            document.getElementById("meteoHour3").innerHTML=hours+"h"+minutes;
            document.getElementById("meteoHour4").innerHTML=hours+"h"+minutes;

          var i=0;
          var dest = "";
          var displayGroup = '1generic';

          while (i < text.length)
          {
            if(text.bike[i].displayGroup != displayGroup){
              dest = dest.concat("<hr>");
              dest = dest.concat("<h2 class='text-green'>"+text.bike[i].displayGroup+"</h2>");
              displayGroup = text.bike[i].displayGroup;
            }
            timestampStart=text.timestampStartBooking;
            buildingStart=text.buildingStart;
            timestampEnd=text.timestampEndBooking;
            buildingEnd=text.buildingEnd;
            var bikeID=text.bike[i].bikeID;
            var catalogID=text.bike[i].type;
            var bikeFrameNumber=text.bike[i].frameNumber;
            var bikeType=text.bike[i].typeDescription;
            var brand = text.bike[i].brand;
            var model = text.bike[i].model;
            var frameType = text.bike[i].frameType;
            if(brand && model && text.bike[i].size){
            var title= "<?= L::reserver_brand; ?> : "+brand+" <br/><?= L::reserver_model; ?> : "+model+" <br/><?= L::reserver_size; ?> : "+text.bike[i].size;
            }else{
            var title=bikeFrameNumber;
            }
            var codeVeloTemporaire ="<div class=\"col-md-4\">\
            <div class=\"featured-box\">\
            <div class=\"effect social-links\"> <img src=\"images_bikes/"+text.bike[i].img+".jpg\" alt=\"image\" />\
            <div class=\"image-box-content\">\
            <p> <a href=\"images_bikes/"+text.bike[i].img+".jpg\" data-lightbox-type=\"image\" title=\"\"><i class=\"fa fa-expand\"></i></a> </p>\
            </div>\
            </div>\
            </div>\
            </div>\
            <div class=\"col-md-4\">\
            <h4>"+ bikeType +"</h4>\
            <p class=\"subtitle\">"+ title +"</p>\
            </div>\
            <div class=\"col-md-2\">\
            <a class=\"button large green button-3d rounded icon-left\" name=\""+brand + "','"+ model + "','"+ frameType +"\" data-target=\"#resume\" data-toggle=\"modal\" href=\"#\" onclick=\"bookBike('"+ bikeID + "','" + catalogID + "')\"><span><?= L::reserver_reserver; ?></span></a>\
            </div>\
            <div class=\"seperator\"></div>";
            dest = dest.concat(codeVeloTemporaire);
            i++;

          }
          //affichage du résultat de la recherche
          document.getElementById('velos').innerHTML = dest;

          //modification du pop-up de réservation avec les informations de réservation
          $date=document.getElementById("search-bikes-form-day").value;
          $dayAndMonth=$date.split("-");
          document.getElementById('daySpan').innerHTML = $dayAndMonth[0];
          document.getElementById('monthSpan').innerHTML = $dayAndMonth[1];
          document.getElementById('yearSpan').innerHTML = $dayAndMonth[2];

          $date=document.getElementById("search-bikes-form-day-deposit").value;
          $dayAndMonth=$date.split("-");
          document.getElementById('dayDepositSpan').innerHTML = $dayAndMonth[0];
          document.getElementById('monthDepositSpan').innerHTML = $dayAndMonth[1];
          document.getElementById('yearDepositSpan').innerHTML = $dayAndMonth[2];
          document.getElementById('hourStartSpan').innerHTML = document.getElementById("search-bikes-form-intake-hour").value
          document.getElementById('hourEndSpan').innerHTML = document.getElementById("search-bikes-form-deposit-hour").value
          document.getElementById('startBuildingSpan').innerHTML = document.getElementById('search-bikes-form-intake-building').options[document.getElementById('search-bikes-form-intake-building').selectedIndex].text;

          document.getElementById('endBuildingSpan').innerHTML = document.getElementById('search-bikes-form-deposit-building').options[document.getElementById('search-bikes-form-deposit-building').selectedIndex].text;

          loadClientConditions()
          .done(function(response){
            if(response.clientConditions.locking=="Y")
            {
            var i;
            var temp="";
            for(i=0; i<4; i++){
              var number=Math.floor(Math.random() * 10).toString();
              while(document.getElementById('search-bikes-form-intake-building').options[document.getElementById('search-bikes-form-intake-building').selectedIndex].value == "infrabelnamur" && number == "3"){
                number=Math.floor(Math.random() * 10).toString();
              }
              temp=temp.concat(number);
            }
            document.getElementById('lockingCodeDiv').style.display="block";
            document.getElementById('lockingCode').innerHTML=temp;
            document.getElementById('widget-new-booking-locking-code').value = temp;


            }else{
            document.getElementById('lockingCodeDiv').style.display="none";
            document.getElementById('widget-new-booking-locking-code').value = "";
            }
          });

          document.getElementById('widget-new-booking-date-start').value = text.dateStart;
          document.getElementById('widget-new-booking-date-end').value = text.dateEnd;
          document.getElementById('widget-new-booking-building-start').value = document.getElementById("search-bikes-form-intake-building").value;;
          document.getElementById('widget-new-booking-building-end').value = document.getElementById("search-bikes-form-deposit-building").value;

          loaded2=true;
          if (loaded1)
          {
            $.notify({
            message: successMessage
            }, {
            type: 'success'
            });
            document.getElementById("travel_information").style.display = "block";
            document.getElementById("velos").style.display = "block";
            $("body").removeClass("loading");
          }

        }else{
          if (text.response == 'error') {

          $.notify({
            message: text.message
          }, {
            type: 'danger'
          });
          document.getElementById('velos').innerHTML = "";
          document.getElementById("travel_information").style.display = "none";
          document.getElementById("velos").style.display = "none";
        }
      }
    }
  })
  }
});



function bookBike(ID, catalogID)
{
  $('#widget-new-booking input[name=bikeID]').val(ID);
  document.getElementById("resumeBikeImage").src="images_bikes/" + catalogID + ".jpg";
}


function initiatizeFeedback(id, notificationId = -1) {
  document.getElementById("widget-feedbackManagement-form").reset();

  $.ajax({
    url: "apis/Kameo/feedback_management.php",
    type: "get",
    data: { action: "retrieveBooking", ID: id },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      if (response.response == "success") {
        $("#feedbackManagement input[name=notificationID]").val(notificationId);
        $("#feedbackManagement input[name=bike]").val(response.bike);
        $("#feedbackManagement input[name=startDate]").val(response.start);
        $("#feedbackManagement input[name=endDate]").val(response.end);
        $("#feedbackManagement input[name=ID]").val(response.ID);
        $("#feedbackManagement input[name=utilisateur]").val(response.email);
        document.getElementsByClassName("feedbackBikeImage")[0].src =
          "images_bikes/" + response.img + "_mini.jpg";
        $("#feedbackManagement select[name=note]").attr("readonly", false);
        $("#feedbackManagement textarea[name=comment]").attr("readonly", false);
        if (response.status == "DONE") {
          $("#feedbackManagement select[name=note]").val(response.note);

          $("#feedbackManagement select[name=note]").attr("readonly", true);
          $("#feedbackManagement textarea[name=comment]").attr(
            "readonly",
            "true"
          );

          if (response.feedback == "1") {
            $("#feedbackManagement input[name=entretien]").prop(
              "checked",
              true
            );
          } else {
            $("#feedbackManagement input[name=entretien]").prop(
              "checked",
              false
            );
          }
          $("#feedbackManagement textarea[name=comment]").val(response.comment);
          $(".feedbackManagementSendButton").addClass("hidden");
        } else {
          $(".feedbackManagementSendButton").removeClass("hidden");
        }
        $("#feedbackManagement").modal("toggle");
      }
    },
  });
}
