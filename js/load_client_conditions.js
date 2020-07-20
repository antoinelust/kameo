//Promise containing Client Conditions
function loadClientConditions(){
	var email=user_data["EMAIL"];
	return $.ajax({
		url: 'apis/Kameo/load_client_conditions.php',
		type: 'post',
		data: {"email": email},
		success: function(response){
			if (response.response == 'error') {
				console.log(response.message);
			}
		}
	});
}