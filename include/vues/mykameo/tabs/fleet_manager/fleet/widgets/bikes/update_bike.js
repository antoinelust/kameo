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
					get_bikes_listing();
					$('#updateBikeStatus').modal('toggle');
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
            url: 'apis/Kameo/get_bike_details.php',
            type: 'post',
            data: { "bikeID": bikeID},
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
                        if ($(this).val() == "partage" || $('#widget-updateBikeStatus-form input[name=name]').val() == "") {
                          $("#widget-updateBikeStatus-form div[id=user_name]").hide();
                          $("#widget-updateBikeStatus-form div[id=user_email]").hide();
                            for (var i = 0; i < response.userNumber; i++){
                                $("#widget-updateBikeStatus-form option[id=" + i + "]").remove();
                            }
                        } else {
                            $("#widget-updateBikeStatus-form div[id=user_name]").show();
                            for (var i = 0; i < response.userNumber; i++){
                                    $("#widget-updateBikeStatus-form option[id=" + i + "]").remove();
                                    $("#widget-updateBikeStatus-form select[name=name]").append("<option id= " + i + " value= " + response.user[i].name + " " + response.user[i].firstName + ">" + response.user[i].name + " " + response.user[i].firstName + "</option>");
                                    if(response.user[i].access == true){
                                        $("#widget-updateBikeStatus-form option[id=" + i + "]").prop("selected", true);
                                        $("#widget-bikeManagement-form div[id=user_email]").show();
                                        $("#widget-updateBikeStatus-form input[name=email").val(response.user[i].email);
                                    }
                                }
                            $(document).ready(function(){
                                    $("#widget-updateBikeStatus-form select[name=name]").change(function(){
                                        var id = parseInt($(this).find("option:selected").attr('id'));
                                        if($("#widget-updateBikeStatus-form select[name=name]").val() != ""){
                                            $("#widget-updateBikeStatus-form div[id=user_email]").show();
                                            $('#widget-updateBikeStatus-form input[name=email]').val(response.user[id].email);
                                        }else{
                                            $("#widget-updateBikeStatus-form div[id=user_email]").hide();
                                        }
                                    });
                            });
                        }
                    }).trigger("change");

                    document.getElementsByClassName("bikeImage")[1].src="images_bikes/"+response.img+"_mini.jpg";
                    
                    $("#bikeStatus").val(response.status);
                    i=0;
                    var dest="";
                    while(i<response.buildingNumber){
                        if(response.building[i].access==true){
                            temp="<input type=\"checkbox\" checked name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\">"+response.building[i].descriptionFR+"<br>";
                        }
                        else if(response.building[i].access==false){
                            temp="<input type=\"checkbox\" name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\">"+response.building[i].descriptionFR+"<br>";
                        }
                        dest=dest.concat(temp);
                        i++;
                    }
                    document.getElementById('bikeBuildingAccess').innerHTML = dest;
                }
            }
    })
}