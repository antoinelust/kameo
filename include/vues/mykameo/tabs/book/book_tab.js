

document.getElementById('search-bikes-form-intake-hour').addEventListener('change', function () { update_deposit_form()}, false);




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
	submitHandler: function(form) {
    $('#travel_information_loading').removeClass("hidden");
    $('#travel_information_error').addClass("hidden");
    $('#travel_information').addClass("hidden");

	  jQuery(form).ajaxSubmit({
		success: function(text) {
		  if (text.response == 'error') {

			$.notify({
			  message: text.message
			}, {
			  type: 'danger'
			});
			document.getElementById('velos').innerHTML = "";
			document.getElementById("travel_information").style.display = "none";
			document.getElementById("velos").style.display = "none";
		  } else {
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
  				buildingStart="<span class=\"fr-inline\">"+response.building_fr+"</span><span class=\"en-inline\">"+response.building_en+"</span><span class=\"nl-inline\">"+response.building_nl+"</span>";
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
                console.log(response);
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
                console.log(response);
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

			var i=1;
			var dest = "";
			while (i <= text.length)
			{
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
		  }
		}
	  });
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
