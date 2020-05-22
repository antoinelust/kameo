function initiatizeFeedback(id, notificationId = -1){

  document.getElementById('widget-feedbackManagement-form').reset();

    $.ajax({
        url: 'include/feedback_management.php',
        type: 'get',
        data: {"action": "retrieveBooking", "ID": id},
        success: function(response){

            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
              
                $('#feedbackManagement input[name=notificationID]').val(notificationId);
                $('.feedbackManagementTitle').html("Ajouter un feedback");
                $('#feedbackManagement input[name=bike]').val(response.bikeNumber);
                $('#feedbackManagement input[name=startDate]').val(response.start);
                $('#feedbackManagement input[name=endDate]').val(response.end);
                $('#feedbackManagement input[name=ID]').val(response.ID);
                $('#feedbackManagement input[name=utilisateur]').val(response.email);
                document.getElementsByClassName("feedbackBikeImage")[0].src="images_bikes/"+response.bikeNumber+"_mini.jpg";
                $('#feedbackManagement select[name=note]').attr("readonly", false);
                $('#feedbackManagement textarea[name=comment]').attr("readonly", false);
                if(response.status=='DONE'){
                    $('#feedbackManagement select[name=note]').val(response.note);

                        $('#feedbackManagement select[name=note]').attr("readonly", true);
                    $('#feedbackManagement textarea[name=comment]').attr("readonly", 'true');

                    if(response.feedback=='1'){
                        $('#feedbackManagement input[name=entretien]').prop("checked", true);

                    }else{
                        $('#feedbackManagement input[name=entretien]').prop("checked", false);
                    }
                    $('#feedbackManagement textarea[name=comment]').val(response.comment);
                    $('.feedbackManagementSendButton').addClass('hidden');
                }else{
                    $('.feedbackManagementSendButton').removeClass('hidden');
                }
                $('#feedbackManagement').modal('toggle');
            }
        }
    });
}





function list_feedbacks() {
    $.ajax({
        url: 'include/feedback_management.php',
        type: 'get',
        data: {action: "list"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){


                var i=0;
                var dest="";
                var temp="<table class=\"table table-condensed\" id=\"feedbackListing\" data-order='[[ 0, \"desc\" ]]'><h4 class=\"fr-inline text-green\">Feedbacks:</h4><h4 class=\"en-inline text-green\">Feedbacks:</h4><h4 class=\"nl-inline text-green\">Feedbacks:</h4><br/><br/><div class=\"seperator seperator-small visible-xs\"></div><thead><tr><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th>Bike</th><th>Start</th><th>End</th><th><span class=\"fr-inline\">Note</span><span class=\"en-inline\">Note</span><span class=\"nl-inline\">Note</span></th><th><span class=\"fr-inline\">Commentaire</span><span class=\"en-inline\">Comment</span><span class=\"nl-inline\">Comment</span></th><th><span class=\"fr-inline\">Entretien</span><span class=\"en-inline\">Maintenance ?</span><span class=\"nl-inline\">Maintenance ?</span></th><th><span class=\"fr-inline\">E-mail</span><span class=\"en-inline\">E-mail</span><span class=\"nl-inline\">E-mail</span></th><th>Statut</th><th>Lu ?</th></tr></thead><tbody>";
                dest=dest.concat(temp);
                while (i < response.feedbacksNumber){

                    if(response.feedback[i].entretien==null){
                        entretien="<span>N/A</span>";
                    } else if (response.feedback[i].entretien=="0"){
                        entretien="<span class=\"text-green\">Non</span>";
                    }else{
                        entretien="<span class=\"text-red\">Oui</span>";
                    }


                    if(response.feedback[i].comment== null){
                        var comment = 'N/A';
                    }else{
                        var comment = response.feedback[i].comment.substr(0,20);
                    }

                    if(response.feedback[i].note== null){
                        var note = 'N/A';
                    }else{
                        var note = response.feedback[i].note;
                    }

                    var temp="<tr><td><a href=\"#\" class=\"text-green retrieveFeedback\" data-target=\"#feedbackManagement\" name=\""+response.feedback[i].IDReservation+"\" data-toggle=\"modal\">"+response.feedback[i].IDReservation+"</a></td><td>"+response.feedback[i].company+"</td><td>"+response.feedback[i].bikeNumber+"<td>"+response.feedback[i].start.shortDateHours()+"</td><td>"+response.feedback[i].end.shortDateHours()+"</td><td>"+note+"</td><td>"+comment+"</td><td>"+entretien+"</td><td>"+response.feedback[i].firstName+" " +response.feedback[i].name+"</td><td>"+response.feedback[i].status+"</td><td>"+response.feedback[i].read+"</tr>";
                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tbody></table>";
                dest=dest.concat(temp);

                document.getElementById('feedbacksListingSpan').innerHTML = dest;
                $('.retrieveFeedback').click(function(){
                    retrieve_feedback(this.name);
                });
                
                if ( $.fn.dataTable.isDataTable( '#feedbackListing' ) ) {
                    table = $('#feedbackListing').DataTable();
                }
                else {
                    table = $('#feedbackListing').DataTable( {
                        "language": {
                          "emptyTable": "Pas de feedbacks"
                        }                        
                    } );
                }
                

            }

            displayLanguage();
        }
    })
}

function retrieve_feedback(ID) {
    $.ajax({
        url: 'include/feedback_management.php',
        type: 'get',
        data: {"action": "retrieveFeedback", "ID": ID},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                
                $('.feedbackManagementTitle').html("Consulter un feedback");
                $('#feedbackManagement input[name=bike]').val(response.bike);
                $('#feedbackManagement input[name=startDate]').val(response.start.shortDateHours());
                $('#feedbackManagement input[name=endDate]').val(response.end.shortDateHours());
                $('#feedbackManagement input[name=ID]').val(ID);
                $('#feedbackManagement input[name=feedbackID]').val(response.ID);
                $('#feedbackManagement input[name=utilisateur]').val(response.email);

                $('#feedbackManagement select[name=note]').attr("readonly", true);

                if(response.note==null){
                    $('.spanNote').addClass("hidden");
                    $('#feedbackManagement select[name=note]').val('5');
                }else{
                    $('#feedbackManagement select[name=note]').val(response.note);
                    $('.spanNote').removeClass("hidden");

                }

                $('#feedbackManagement textarea[name=comment]').attr("readonly", true);
                if(response.comment==null){
                    $('.textAreaComment').addClass("hidden");
                }else{
                    $('#feedbackManagement textarea[name=comment]').val(response.comment);
                    $('.textAreaComment').removeClass("hidden");

                }
                document.getElementsByClassName("feedbackBikeImage")[0].src="images_bikes/"+response.bike+"_mini.jpg";

                if(response.entretien==null){
                    $('.spanEntretien').addClass("hidden");
                }
                else if(response.entretien=="1"){
                    $('#feedbackManagement input[name=entretien]').prop("checked", true);
                    $('.spanEntretien').removeClass("hidden");

                }else{
                    $('#feedbackManagement input[name=entretien]').prop("checked", false);
                    $('.spanEntretien').removeClass("hidden");

                }

                $('.feedbackManagementSendButton').addClass('hidden');

            }

            displayLanguage();
        }
    })
}
