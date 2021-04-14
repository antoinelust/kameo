jQuery("#widget-updateBikeStatus-form").validate({
	submitHandler: function(form) {
		jQuery(form).ajaxSubmit({
			success: function(response) {
				if (response.response == 'success') {
					$.notify({
						message: response.message
					}, {
						type: 'success'
					});
					$('#updateBikeStatus').modal('toggle');
					$('#bikeDetails').dataTable().api().ajax.reload();
				} else {
					$.notify({
						message: response.message
					}, {
						type: 'danger'
					});
				}
			}
		});
	}
});
function construct_form_for_bike_status_update(bikeID){
    $.ajax({
            url: 'api/bikes',
            type: 'get',
            data: { action: 'retrieve', "bikeID": bikeID},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{
                    $('#widget-updateBikeStatus-form input[name=bikeID]').val(bikeID);
                    $('#widget-updateBikeStatus-form input[name=bikeModel]').val(response.model);
                    $('#widget-updateBikeStatus-form input[name=bikeNumber]').val(response.frameNumber);
                    $('#widget-updateBikeStatus-form input[name=frameReference]').val(response.frameReference);
                    $('#widget-updateBikeStatus-form input[name=contractType]').val(response.contractType);
                    $('#widget-updateBikeStatus-form input[name=startDateContract]').val(response.contractStart);
                    $('#widget-updateBikeStatus-form input[name=endDateContract]').val(response.contractEnd);
                    $("#widget-updateBikeStatus-form select[name=bikeType]").val(response.biketype);
                    $("#widget-updateBikeStatus-form select[name=bikeType]").change(function() {
	                    if ($(this).val() == "partage") {
	                      $("#widget-updateBikeStatus-form div[id=user_name]").hide();
	                      $("#widget-updateBikeStatus-form div[id=user_email]").hide();
	                    } else {
	                        $('#widget-updateBikeStatus-form input[name=email]').val("");
	                        $("#widget-updateBikeStatus-form select[name=name]").find("option")
	                          .remove()
	                          .end();

	                        $("#widget-updateBikeStatus-form div[id=user_name]").show();

	                        for (var i = 0; i < response.userNumber; i++){
	                            $("#widget-updateBikeStatus-form select[name=name]").append('<option value= "' + response.user[i].email +  '">' + response.user[i].name + ' ' + response.user[i].firstName + "<br>");
	                        }
	                        if($("#widget-updateBikeStatus-form select[name=name]").has('option').length > 0){
	                            $("#widget-updateBikeStatus-form input[name=email]").val(response.user[0].email);
	                            $("#widget-updateBikeStatus-form div[id=user_email]").show();
	                        }else{
	                            $("#widget-updateBikeStatus-form div[id=user_email]").hide();
	                        }
	                        $("#widget-updateBikeStatus-form select[name=name]").change(function(){
	                            var user_email = $(this).children("option:selected").val();
	                            $('#widget-updateBikeStatus-form input[name=email]').val(user_email);
	                        });
	                    }
                    }).trigger("change");

                    document.getElementsByClassName("bikeImage")[1].src="images_bikes/"+response.img+"_mini.jpg";

                    $("#bikeStatus").val(response.status);
                    i=0;
                    var dest="";
                    while(i<response.building.length){
                        if(response.building[i].access=='true'){
                            temp="<input type=\"checkbox\" checked name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\">"+response.building[i].descriptionFR+"<br>";
                        }
                        else if(response.building[i].access=='false'){
                            temp="<input type=\"checkbox\" name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\">"+response.building[i].descriptionFR+"<br>";
                        }else{
													console.log("error")
												}
                        dest=dest.concat(temp);
                        i++;
                    }
                    document.getElementById('bikeBuildingAccess').innerHTML = dest;
                }
            }
    })
}
