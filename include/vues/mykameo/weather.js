  $('#travel_information_2_loading').removeClass("hidden");
  $('#travel_information_2_error').addClass("hidden");
  $('#travel_information_2').addClass("hidden");

  var day= new Date().getDate();
  var month= new Date().getMonth() + 1;
  var year= new Date().getFullYear();
  var hours= new Date().getHours();
  var minutes= new Date().getMinutes();
  minutes=minutes.toString();
  var kameo_score_loaded=false;

  if (minutes.length ==1){
	minutes="0"+minutes;
  }

  document.getElementById('meteoDate1').innerHTML = day+"/"+ month+"/"+year;
  document.getElementById('meteoDate2').innerHTML = day+"/"+ month+"/"+year;
  document.getElementById('meteoDate3').innerHTML = day+"/"+ month+"/"+year;
  document.getElementById('meteoDate4').innerHTML = day+"/"+ month+"/"+year;
  document.getElementById('meteoHour1').innerHTML = hours+"h"+ minutes;
  document.getElementById('meteoHour2').innerHTML = hours+"h"+ minutes;
  document.getElementById('meteoHour3').innerHTML = hours+"h"+ minutes;
  document.getElementById('meteoHour4').innerHTML = hours+"h"+ minutes;

  var addressDomicile=get_address_domicile();
  var addressTravail=get_address_travail();

  var timestamp=new Date();
  timestamp=(timestamp.toISOString().split('T')[0]+" "+timestamp.toISOString().split('T')[1]).split('.')[0];
  get_meteo(timestamp, addressDomicile).done(function(response){
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
	  document.getElementById('precipitation_widget1').innerHTML = precipitation+" %";
	  document.getElementById('wind_widget1').innerHTML = Math.round(windSpeed*3.6)+" km/h";
	  document.getElementById("logo_meteo2").src="images/meteo/"+weather+".png";
	  document.getElementById('temperature_widget2').innerHTML = Math.round(temperature)+" °C";
	  document.getElementById('precipitation_widget2').innerHTML = precipitation+" %";
	  document.getElementById('wind_widget2').innerHTML = Math.round(windSpeed*3.6)+" km/h";
	  document.getElementById("logo_meteo3").src="images/meteo/"+weather+".png";
	  document.getElementById('temperature_widget3').innerHTML = Math.round(temperature)+" °C";
	  document.getElementById('precipitation_widget3').innerHTML = precipitation+" %";
	  document.getElementById('wind_widget3').innerHTML = Math.round(windSpeed*3.6)+" km/h";
	  document.getElementById("logo_meteo4").src="images/meteo/"+weather+".png";
	  document.getElementById('temperature_widget4').innerHTML = Math.round(temperature)+" °C";
	  document.getElementById('precipitation_widget4').innerHTML = precipitation+" %";
	  document.getElementById('wind_widget4').innerHTML = Math.round(windSpeed*3.6)+" km/h";

	  get_travel_time("now", addressDomicile, addressTravail).done(function(response){
		  if(response.duration_walking === undefined || response.duration_bike === undefined || response.duration_car === undefined){
			$('#travel_information_2_loading').addClass("hidden")
			$('#travel_information_2_error').removeClass("hidden")
			$('#travel_information_2').addClass("hidden")
		  }else{
			document.getElementById('walking_duration_widget1').innerHTML = response.duration_walking+" min";
			document.getElementById('bike_duration_widget1').innerHTML = response.duration_bike+" min";
			document.getElementById('car_duration_widget1').innerHTML = response.duration_car+" min";
			document.getElementById('bike_duration_widget2').innerHTML = response.duration_bike+" min";
			document.getElementById('walking_duration_widget2').innerHTML = response.duration_walking+" min";
			document.getElementById('car_duration_widget2').innerHTML = response.duration_car+" min";
			document.getElementById('walking_duration_widget3').innerHTML = response.duration_walking+" min";
			document.getElementById('bike_duration_widget3').innerHTML = response.duration_bike+" min";
			document.getElementById('car_duration_widget3').innerHTML = response.duration_car+" min";
			document.getElementById('walking_duration_widget4').innerHTML = response.duration_walking+" min";
			document.getElementById('bike_duration_widget4').innerHTML = response.duration_bike+" min";
			document.getElementById('car_duration_widget4').innerHTML = response.duration_car+" min";
			var img1= new Image();
			var image=get_kameo_score(weather, precipitation, temperature, windSpeed, response.duration_bike, response.duration_car);
			img1.onload = function() {
				kameo_score_loaded=true;
				$('#travel_information_2_loading').addClass("hidden")
				$('#travel_information_2_error').addClass("hidden")
				$('#travel_information_2').removeClass("hidden")
			};
			img1.onerror = function() {
				$('#travel_information_2_loading').addClass("hidden")
				$('#travel_information_2_error').removeClass("hidden")
				$('#travel_information_2').addClass("hidden")
			};
			img1.src=image;
		  }
	  });
	} else{
	  console.log(response.message)
	}
  });