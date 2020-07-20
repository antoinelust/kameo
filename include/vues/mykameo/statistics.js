var year= new Date().getFullYear();
document.getElementById('year').innerHTML=year;
var email=user_data['EMAIL'];
var addressDomicile=get_address_domicile();
var addressTravail=get_address_travail();

$.ajax({
  url: 'apis/Kameo/calendar.php',
  type: 'post',
  data: { "email":email, "year":year, action:"statistics"},
  success: function(text){
	if (text.response == 'error') {
	  console.log(text.message);
	}
	var count = text.count;

	$.ajax({
	  url: 'apis/Kameo/get_directions.php',
	  type: 'post',
	  data: {"address_start": addressDomicile, "address_end": addressTravail},
	  success: function(response){
		if (response.response == 'error') {
		  console.log(response.message);
		}else{
			var distance_bike= response.distance_bike;
			var total_distance= (distance_bike * 2 * count)/1000;
			document.getElementById('count_trips').innerHTML= count;
			if(distance_bike !== undefined){
				document.getElementById('total_trips').innerHTML= Math.round(total_distance)+" kms";
			}else{
				document.getElementById('total_trips').innerHTML= "Veuillez renseigner votre adresse";
			}
		}
	  }
	})
  }
});