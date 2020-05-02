function list_errors_bikes(){
  $.ajax({
      url: 'include/error_management.php',
      method: 'get',
      data: {'action' : 'list', 'item': 'bikes'},
      success: function(response){
        if (response.response == "error") {
          console.log(response.message);
        }else{
            var i=0;
            
            var dest="<table id=\"error_images_bikes\" class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th>ID</th><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th>Description</th></thead><tbody>";
            while (i< response.bike.img.number){   
                var bike=response.bike.img[i];
                var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+bike.frameNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"set_required_image('false')\">"+bike.frameNumber+"</a></td><td>Image manquante sur le vélo "+bike.frameNumber+"</td><td></tr>";
                dest=dest.concat(temp);
                i++;
            }
                        
            dest=dest.concat("</tbody></table>");
            $('#dashboardBody').html(dest);

            if(response.bike.img.number==0){
                document.getElementById('errorCounter').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bike.img.number+"\" data-from=\"0\" data-seperator=\"true\">"+response.bike.img.number+"</span>";
                $('#errorCounter').css('color', '#3cb395');                
            }else{
                document.getElementById('errorCounter').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bike.img.number+"\" data-from=\"0\" data-seperator=\"true\">"+response.bike.img.number+"</span>";
                $('#errorCounter').css('color', '#d80000');
                
            }
            
            
            
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
function list_errors_bills(){
  $.ajax({
      url: 'include/error_management.php',
      method: 'get',
      data: {'action' : 'list', 'item' : 'bills'},
      success: function(response){
        if (response.response == "error") {
            console.log(response.message);
        }else{
            
            var i=0;
            var dest="<table id=\"error_images_bikes\" class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th>ID</th><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th>Description</th></thead><tbody>";
            while (i< response.bike.bill.number){   
                var bill=response.bike.bill[i];
                var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+bill.bikeNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"set_required_image('false')\">"+bill.bikeNumber+"</a></td><td>"+bill.description+"</td><td></tr>";
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

window.addEventListener("DOMContentLoaded", function(event) {

    document.getElementsByClassName('dashboardBikes')[0].addEventListener('click', function() {list_errors_bikes()});
    document.getElementsByClassName('dashboardBills')[0].addEventListener('click', function() {list_errors_bills()});
});

