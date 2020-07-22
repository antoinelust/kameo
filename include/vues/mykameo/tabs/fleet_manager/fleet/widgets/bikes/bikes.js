window.addEventListener("DOMContentLoaded", function(event) {
	/** @TODO: rename clientBikesManagerClick class**/
	document.getElementsByClassName('clientBikesManagerClick')[0].addEventListener('click', function() { get_bikes_listing()}, false);
});
function get_bikes_listing() {
    $.ajax({
        url: 'apis/Kameo/get_bikes_listing.php',
        type: 'post',
        data: { "email": email},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var i=0;
                var dest="";
                var temp="<h4 class=\"fr-inline text-green\">Vos vélos:</h4><h4 class=\"en-inline text-green\">Your Bikes:</h4><h4 class=\"nl-inline text-green\">Jouw fietsen:</h4><table class=\"table table-condensed\"><thead><tr><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fiet</span></th><th><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th><span class=\"fr-inline\">Type de contrat</span><span class=\"en-inline\">Contract type</span><span class=\"nl-inline\">Contract type</span></th><th><span class=\"fr-inline\">Début du contrat</span><span class=\"en-inline\">Contract start</span><span class=\"nl-inline\">Contract start</span></th><th><span class=\"fr-inline\">Fin du contrat</span><span class=\"en-inline\">Contract End</span><span class=\"nl-inline\">Contract End</span></th><th><span class=\"fr-inline\">Etat du vélo</span><span class=\"en-inline\">Bike status</span><span class=\"nl-inline\">Bike status</span></th><th></th></tr></thead><tbody>";
                dest=dest.concat(temp);
                while (i < response.bikeNumber){

                    if(response.bike[i].contractStart){
                        var contractStart="<td data-sort=\""+(new Date(response.bike[i].contractStart)).getTime()+"\">"+response.bike[i].contractStart.shortDate()+"</td>";
                    }else{
                        var contractStart="<td>N/A</td>";
                    }
                    if(response.bike[i].contractEnd){
                        var contractEnd="<td data-sort=\""+(new Date(response.bike[i].contractEnd)).getTime()+"\">"+response.bike[i].contractEnd.shortDate()+"</td>";
                    }else{
                        var contractEnd="<td>N/A</td>";
                    }
					
                    if(response.bike[i].status==null || response.bike[i].status=="KO"){
                        status="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                    }else{
                        status="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                    }

                    if(response.bike[i].frameNumber == null || response.bike[i].frameNumber == ''){
                        var frameNumber = "N/A - "+response.bike[i].id;
                    }else{
                        var frameNumber = response.bike[i].frameNumber;
                    }

                    var temp="<tr><td><a  data-target=\"#bikeDetailsFull\" name=\""+response.bike[i].id+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillBikeDetails(this.name)\">"+frameNumber+"</a></td><td>"+response.bike[i].model+"</td><td>"+response.bike[i].contractType+"</td>"+contractStart+contractEnd+"<td>"+status+"</td><td><ins><a class=\"text-green updateBikeStatus\" data-target=\"#updateBikeStatus\" name=\""+response.bike[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tbody></table>";
                dest=dest.concat(temp);
                document.getElementById('bikeDetails').innerHTML = dest;
                displayLanguage();
                var classname = document.getElementsByClassName('updateBikeStatus');
                for (var i = 0; i < classname.length; i++) {
                    classname[i].addEventListener('click', function() {construct_form_for_bike_status_update(this.name)}, false);
                }
            }
        }
    });
}