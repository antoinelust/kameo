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