function get_meteo(date, address){
	return $.ajax({
	  url: 'apis/Kameo/weather.php',
	  type: 'post',
	  data: { "date": date, "address": address},
      success: function(text){
      }
        
	});
}