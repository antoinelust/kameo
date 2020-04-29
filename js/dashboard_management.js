function list_errors(){
  $.ajax({
      url: 'include/error_management.php',
      method: 'get',
      data: {'action' : 'list'},
      success: function(response){
        if (response.response == "error") {
          console.log(response.message);
        }else{
            var i=0;
            console.log(response);
            
            
            var dest="<table id=\"error_images_bikes\" class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th>ID</th><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th>Description</th></thead><tbody>";
            while (i< response.bike.img.number){   
                var bike=response.bike.img[i];
                console.log(bike.path);
                var temp="<tr><td scope=\"row\">"+bike.id+"</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+bike.frameNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"set_required_image('false')\">"+bike.frameNumber+"</td><td>Image manquante sur le vélo "+bike.frameNumber+"</a></td><td></tr>";
                dest=dest.concat(temp);
                i++;
            }
                        
            dest=dest.concat("</tbody></table>");
            $('#dashboardBody').html(dest);
            
            displayLanguage();
            $(".updateBikeAdmin").click(function() {
                construct_form_for_bike_status_updateAdmin(this.name);
                $('#widget-bikeManagement-form input').attr('readonly', false);
                $('#widget-bikeManagement-form select').attr('readonly', false);
                $('.bikeManagementTitle').html('Modifier un vélo');
                $('.bikeManagementSend').removeClass('hidden');
                $('.bikeManagementSend').html('<i class="fa fa-plus"></i>Modifier');

            });
            

        }
      }
  });
}


