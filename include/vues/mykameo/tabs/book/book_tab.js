

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
  jQuery("#search-bikes-form").validate({
	submitHandler: function(form) {
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
			get_address_building(text.buildingStart)
			.done(function(response){
			  addressStart=response.address;
			  buildingStartFr=response.building_fr;
			  buildingStartEn=response.building_en;
			  buildingStartNl=response.building_nl;
			  get_address_building(text.buildingEnd)
			  .done(function(response){
				addressEnd=response.address;
				buildingEndFr=response.building_fr;
				buildingEndEn=response.building_en;
				buildingEndNl=response.building_nl;
				document.getElementById("meteoStart1FR").innerHTML=buildingStartFr;
				document.getElementById("meteoStart2FR").innerHTML=buildingStartFr;
				document.getElementById("meteoStart3FR").innerHTML=buildingStartFr;
				document.getElementById("meteoStart4FR").innerHTML=buildingStartFr;
				document.getElementById("meteoStart1EN").innerHTML=buildingStartEn;
				document.getElementById("meteoStart2EN").innerHTML=buildingStartEn;
				document.getElementById("meteoStart3EN").innerHTML=buildingStartEn;
				document.getElementById("meteoStart4EN").innerHTML=buildingStartEn;
				document.getElementById("meteoStart1NL").innerHTML=buildingStartNl;
				document.getElementById("meteoStart2NL").innerHTML=buildingStartNl;
				document.getElementById("meteoStart3NL").innerHTML=buildingStartNl;
				document.getElementById("meteoStart4NL").innerHTML=buildingStartNl;
				document.getElementById("meteoEnd1FR").innerHTML=buildingEndFr;
				document.getElementById("meteoEnd2FR").innerHTML=buildingEndFr;
				document.getElementById("meteoEnd3FR").innerHTML=buildingEndFr;
				document.getElementById("meteoEnd4FR").innerHTML=buildingEndFr;
				document.getElementById("meteoEnd1EN").innerHTML=buildingEndEn;
				document.getElementById("meteoEnd2EN").innerHTML=buildingEndEn;
				document.getElementById("meteoEnd3EN").innerHTML=buildingEndEn;
				document.getElementById("meteoEnd4EN").innerHTML=buildingEndEn;
				document.getElementById("meteoEnd1NL").innerHTML=buildingEndNl;
				document.getElementById("meteoEnd2NL").innerHTML=buildingEndNl;
				document.getElementById("meteoEnd3NL").innerHTML=buildingEndNl;
				document.getElementById("meteoEnd4NL").innerHTML=buildingEndNl;
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
				})
			  })
			});
			var i=1;
			var dest = "";
			while (i <= text.length)
			{
				timestampStart=text.timestampStartBooking;
				buildingStart=text.buildingStart;
				timestampEnd=text.timestampEndBooking;
				buildingEnd=text.buildingEnd;
				var bikeID=text.bike[i].bikeID;
				var bikeFrameNumber=text.bike[i].frameNumber;
				var bikeType=text.bike[i].typeDescription;
				if(text.bike[i].brand && text.bike[i].model && text.bike[i].size){
				var title= "Marque : "+text.bike[i].brand+" <br/>Modèle : "+text.bike[i].model+" <br/>Taille : "+text.bike[i].size;
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
				<a class=\"button large green button-3d rounded icon-left\" name=\""+bikeID+"\" id=\"fr\" data-target=\"#resume\" data-toggle=\"modal\" href=\"#\" onclick=\"bookBike(this.name)\"><span>Réserver</span></a>\
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
				  temp=temp.concat(Math.floor(Math.random() * 10).toString());
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
  function bookBike(ID)
  {
	$('#widget-new-booking input[name=bikeID]').val(ID);
	document.getElementById("resumeBikeImage").src="images_bikes/"+ID+".jpg";

  }