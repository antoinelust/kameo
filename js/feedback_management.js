function initiatizeFeedback(id){
    
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

                document.getElementById('counterFeedbacks').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.feedbacksNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.feedbacksNumber+"</span>";


                var i=0;
                var dest="";
                var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Feedbacks:</h4><h4 class=\"en-inline text-green\">Feedbacks:</h4><h4 class=\"nl-inline text-green\">Feedbacks:</h4><br/><br/><div class=\"seperator seperator-small visible-xs\"></div><tbody><thead><tr><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th>Bike</th><th>Start</th><th>End</th><th><span class=\"fr-inline\">Note</span><span class=\"en-inline\">Note</span><span class=\"nl-inline\">Note</span></th><th><span class=\"fr-inline\">Commentaire</span><span class=\"en-inline\">Comment</span><span class=\"nl-inline\">Comment</span></th><th><span class=\"fr-inline\">Entretien</span><span class=\"en-inline\">Maintenance ?</span><span class=\"nl-inline\">Maintenance ?</span></th><th><span class=\"fr-inline\">E-mail</span><span class=\"en-inline\">E-mail</span><span class=\"nl-inline\">E-mail</span></th><th>Statut</th></tr></thead>";
                dest=dest.concat(temp);
                while (i < response.feedbacksNumber){
                    var unix_timestamp = response.feedback[i].start;
                    // Create a new JavaScript Date object based on the timestamp
                    // multiplied by 1000 so that the argument is in milliseconds, not seconds.
                    var date = new Date(unix_timestamp * 1000);
                    //day from the timestamp
                    var day = date.getDate();
                    //month from the timestamp
                    var month = date.getMonth();
                    //year from the timestamp
                    var year = date.getFullYear();
                    // Hours part from the timestamp
                    var hours = date.getHours();
                    // Minutes part from the timestamp
                    var minutes = "0" + date.getMinutes();
                    // Seconds part from the timestamp
                    var seconds = "0" + date.getSeconds();

                    // Will display time in 10:30:23 format
                    var formattedTimeStart = day +'/' + month + '/' + year + ' ' + hours + ':' + minutes.substr(-2);

                    var unix_timestamp = response.feedback[i].end;
                    // Create a new JavaScript Date object based on the timestamp
                    // multiplied by 1000 so that the argument is in milliseconds, not seconds.
                    var date = new Date(unix_timestamp * 1000);
                    //day from the timestamp
                    var day = date.getDate();
                    //month from the timestamp
                    var month = date.getMonth();
                    //year from the timestamp
                    var year = date.getFullYear();
                    // Hours part from the timestamp
                    var hours = date.getHours();
                    // Minutes part from the timestamp
                    var minutes = "0" + date.getMinutes();
                    // Seconds part from the timestamp
                    var seconds = "0" + date.getSeconds();

                    // Will display time in 10:30:23 format
                    var formattedTimeEnd = day +'/' + month + '/' + year + ' ' + hours + ':' + minutes.substr(-2);

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
                                                
                    var temp="<tr><td><a href=\"#\" class=\"text-green retrieveFeedback\" data-target=\"#feedbackManagement\" name=\""+response.feedback[i].IDReservation+"\" data-toggle=\"modal\">"+response.feedback[i].IDReservation+"</a></td><td>"+response.feedback[i].company+"</td><td>"+response.feedback[i].bikeNumber+"<td>"+formattedTimeStart+"</td><td>"+formattedTimeEnd+"</td><td>"+note+"</td><td>"+comment+"</td><td>"+entretien+"</td><td>"+response.feedback[i].firstName+" " +response.feedback[i].name+"</td><td>"+response.feedback[i].status+"</td></tr>";
                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tobdy></table>";
                dest=dest.concat(temp);

                document.getElementById('feedbacksListingSpan').innerHTML = dest;
                $('.retrieveFeedback').click(function(){
                    retrieve_feedback(this.name);
                });

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

                var unix_timestamp = response.start
                // Create a new JavaScript Date object based on the timestamp
                // multiplied by 1000 so that the argument is in milliseconds, not seconds.
                var date = new Date(unix_timestamp * 1000);
                //day from the timestamp
                var day = date.getDate();
                //month from the timestamp
                var month = date.getMonth();
                //year from the timestamp
                var year = date.getFullYear();
                // Hours part from the timestamp
                var hours = date.getHours();
                // Minutes part from the timestamp
                var minutes = "0" + date.getMinutes();
                // Seconds part from the timestamp
                var seconds = "0" + date.getSeconds();

                // Will display time in 10:30:23 format
                var formattedTimeStart = day +'/' + month + '/' + year + ' ' + hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);


                var unix_timestamp = response.end
                // Create a new JavaScript Date object based on the timestamp
                // multiplied by 1000 so that the argument is in milliseconds, not seconds.
                var date = new Date(unix_timestamp * 1000);
                //day from the timestamp
                var day = date.getDate();
                //month from the timestamp
                var month = date.getMonth();
                //year from the timestamp
                var year = date.getFullYear();
                // Hours part from the timestamp
                var hours = date.getHours();
                // Minutes part from the timestamp
                var minutes = "0" + date.getMinutes();
                // Seconds part from the timestamp
                var seconds = "0" + date.getSeconds();

                // Will display time in 10:30:23 format
                var formattedTimeEnd = day +'/' + month + '/' + year + ' ' + hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

                $('.feedbackManagementTitle').html("Consulter un feedback");
                $('#feedbackManagement input[name=bike]').val(response.bike);
                $('#feedbackManagement input[name=startDate]').val(formattedTimeStart);
                $('#feedbackManagement input[name=endDate]').val(formattedTimeEnd);
                $('#feedbackManagement input[name=ID]').val(response.ID);
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
