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
							$("#widget-updateBikeStatus-form input[name=bikeType]").val(response.biketype);

              document.getElementsByClassName("bikeImage")[1].src="images_bikes/"+response.img+"_mini.jpg";

              $("#bikeStatus").val(response.status);
              i=0;
              var dest="";
							if(response.bikeType=='partage'){
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
								$('.bikeBuildingAccess').removeClass("hidden");
								$('#user_email').addClass('hidden');
								$("#widget-updateBikeStatus-form input[name=email]").val("");
							}else{
								$("#widget-updateBikeStatus-form input[name=email]").val(response.bikeOwner);
								$('.bikeBuildingAccess').addClass("hidden");
								$('#user_email').removeClass('hidden');

							}
          }
      }
    })
}
