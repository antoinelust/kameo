function add_task(company){
$('#widget-taskManagement-form label[for=channel]').addClass("required");
$('#widget-taskManagement-form label[for=channel]').removeClass("hidden");

$('#widget-taskManagement-form select[name=channel]').addClass("required");
$('#widget-taskManagement-form select[name=channel]').removeClass("hidden");
document.getElementById('widget-taskManagement-form').reset();
//$('#widget-taskManagement-form select[name=company]').val(company);
$('#widget-taskManagement-form select[name=type]').val("contact");
$('#widget-taskManagement-form input').attr("readonly", false);
$('#widget-taskManagement-form textarea').attr("readonly", false);
$('#widget-taskManagement-form select').attr("readonly", false);
$('.taskManagementTitle').text("Ajouter une action");


}



function list_tasks(status, owner2, numberOfResults, email) {
    if(!owner2){
        owner2=email;
    }
    $.ajax({
        url: 'include/action_company.php',
        type: 'get',
        data: { "company": '*', "status": status, "owner":owner2, "numberOfResults": numberOfResults},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var i=0;
                var dest="";
                var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Actions :</h4><h4 class=\"en-inline text-green\">Actions:</h4><h4 class=\"nl-inline text-green\">Actions:</h4><br><a class=\"button small green button-3d rounded icon-right addTask\" data-target=\"#taskManagement\" data-toggle=\"modal\"\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une action</span></a><br/><a class=\"button small green button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"list_tasks('*', $('.taskOwnerSelection').val(), $('.tasksListing_number').val())\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Toutes les actions ("+response.actionNumberTotal+")</span></a> <div class=\"seperator seperator-small visible-xs\"></div><a class=\"button small orange button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"list_tasks('TO DO', $('.taskOwnerSelection').val(), $('.tasksListing_number').val())\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> TO DO ("+response.actionNumberNotDone+")</span></a> <a class=\"button small red button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"list_tasks('LATE', $('.taskOwnerSelection').val(), $('.tasksListing_number').val())\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Actions en retard ("+response.actionNumberLate+")</span></a><tbody><thead><tr><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th>Type</th><th><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th><span class=\"fr-inline\">Rappel</span><span class=\"en-inline\">Reminder</span><span class=\"nl-inline\">Reminder</span></th><th><span class=\"fr-inline\">Statut</span><span class=\"en-inline\">Status</span><span class=\"nl-inline\">Status</span></th><th>Owner</th><th></th></tr></thead>";
                dest=dest.concat(temp);
                while (i < response.actionNumber){
                    if(response.action[i].date_reminder!=null){
                        var date_reminder=response.action[i].date_reminder.substr(0,10);
                    }else{
                        var date_reminder="N/A";
                    }

                    var status=response.action[i].status;
                    var ownerSpan=response.action[i].ownerFirstName+" "+response.action[i].ownerName;

                    if(response.action[i].late && response.action[i].status=='TO DO'){
                        date_reminder="<span class='text-red'>"+date_reminder+"</span>";
                        status="<span class='text-red'>"+status+"</span>";
                        owner="<span class='text-red'>"+ownerSpan+"</span>";
                    }else if(response.action[i].status=='DONE'){
                        date_reminder="<span class='text-green'>"+date_reminder+"</span>";
                        status="<span class='text-green'>"+status+"</span>";
                        owner="<span class='text-green'>"+ownerSpan+"</span>";
                    }else if(status='TO DO'){
                        date_reminder="<span class='text-orange'>"+date_reminder+"</span>";
                        status="<span class='text-orange'>"+status+"</span>";
                        owner="<span class='text-orange'>"+ownerSpan+"</span>";
                    }

                    if(response.action[i].type=="other"){
                        type="Autre";
                    }else if(response.action[i].type=="offer"){
                        type="Offre";
                    }else if(response.action[i].type=="offreSigned"){
                        type="Offre Signée";
                    }else if(response.action[i].type=="rdv"){
                        type="Rendez-vous";
                    }else if(response.action[i].type=="rdv plan"){
                        type="Planification rdv";
                    }else if(response.action[i].type=="contact"){
                        type="Contact";
                    }else if(response.action[i].type=="rappel"){
                        type="Rappel";
                    }else{
                        type=response.action[i].type;
                    }


                    var temp="<tr><td><a href=\"#\" class=\"retrieveTask\" data-target=\"#taskManagement\" data-toggle=\"modal\" name=\""+response.action[i].id+"\">"+response.action[i].id+"</a></td><td>"+response.action[i].company+"</td><td>"+response.action[i].date.substr(0,10)+"</td><td>"+type+"<td>"+response.action[i].title+"</td><td>"+date_reminder+"</td><td>"+status+"</td><td>"+ownerSpan+"</td><td><ins><a class=\"text-green updateAction\" data-target=\"#updateAction\" name=\""+response.action[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tobdy></table>";
                dest=dest.concat(temp);
                document.getElementById('tasksListingSpan').innerHTML = dest;


                $(".retrieveTask").click(function() {
                    retrieve_task(this.name, "retrieve");
                    $('.taskManagementSendButton').addClass("hidden");


                });

                $(".updateTask").click(function() {
                    update_task(this.name, "update");
                });
                $(".addTask").click(function() {
                    add_task(this.name);
                    $('.taskManagementSendButton').removeClass("hidden");
                    $('.taskManagementSendButton').text("Ajouter")

                });


                $('.taskOwnerSelection')
                    .find('option')
                    .remove()
                    .end()
                ;
                $('.taskOwnerSelection').append("<option value='*'>Tous<br>");

                var i=0;
                while (i < response.ownerNumber){
                    $('.taskOwnerSelection').append("<option value="+response.owner[i].email+">"+response.owner[i].firstName+" "+response.owner[i].name+"<br>");
                    i++;

                }

                if(owner2){
                    $('.taskOwnerSelection').val(owner2);
                }else{
                    $('.taskOwnerSelection').val('*');
                }

                $('.taskOwnerSelection2')
                    .find('option')
                    .remove()
                    .end()
                ;
                $('.taskOwnerSelection2').append("<option value='*'>Tous<br>");

                var i=0;
                while (i < response.ownerNumber){
                    $('.taskOwnerSelection2').append("<option value="+response.owner[i].email+">"+response.owner[i].firstName+" "+response.owner[i].name+"<br>");
                    i++;

                }

                $('.taskOwnerSelection2').val('*');

                $('#widget-taskManagement-form select[name=owner]')
                    .find('option')
                    .remove()
                    .end()
                ;
                $('#widget-taskManagement-form select[name=owner]').append("<option value='*'>Tous<br>");

                var i=0;
                while (i < response.ownerNumber){
                    $('#widget-taskManagement-form select[name=owner]').append("<option value="+response.owner[i].email+">"+response.owner[i].firstName+" "+response.owner[i].name+"<br>");
                    i++;

                }

                document.getElementById('counterTasks').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.actionNumberNotDone+"\" data-from=\"0\" data-seperator=\"true\">"+response.actionNumberNotDone+"</span>";

                displayLanguage();

                var classname = document.getElementsByClassName('updateAction');
                for (var i = 0; i < classname.length; i++) {
                    classname[i].addEventListener('click', function() {construct_form_for_action_update(this.name)}, false);
                }

            }
        }
    })
}




function construct_form_for_action_update(id){



        $('#widget-updateAction-form select[name=owner]')
            .find('option')
            .remove()
            .end()
        ;

        $.ajax({
            url: 'include/get_kameobikes_members.php',
            type: 'get',
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    while (i < response.membersNumber){
                        $('#widget-updateAction-form select[name=owner]').append("<option value="+response.member[i].email+">"+response.member[i].firstName+" "+response.member[i].name+"<br>");
                        i++;
                    }
                }
            }
        }).done(function(){
            $.ajax({
                url: 'include/action_company.php',
                type: 'get',
                data: { "id": id},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{
                        document.getElementById('widget-updateAction-form').reset();
                        $('#widget-updateAction-form input[name=id]').val(response.action.id);
                        $('#widget-updateAction-form select[name=type]').val(response.action.type);
                        $('#widget-updateAction-form input[name=date]').val(response.action.date.substr(0,10));
                        $('#widget-updateAction-form textarea[name=description]').val(response.action.description);
                        $('#widget-updateAction-form input[name=title]').val(response.action.title);
                        if(response.action.date_reminder != null){
                            $('#widget-updateAction-form input[name=date_reminder]').val(response.action.date_reminder.substr(0,10));
                        }

                        $('#widget-updateAction-form select[name=company]').val(response.action.company);
                        $('#widget-updateAction-form select[name=status]').val(response.action.status);
                        $('#widget-updateAction-form select[name=owner]').val(response.action.owner);
                    }

                }
            })

        })
}


function retrieve_task(ID, action = "retrieve"){
    $.ajax({
        url: 'include/action_company.php',
        type: 'get',
        data: {"id": ID, "action": action},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
              initializeFields();
              list_kameobikes_member();
                if(action=="retrieve"){
                    $('#widget-taskManagement-form input').attr("readonly", true);
                    $('#widget-taskManagement-form textarea').attr("readonly", true);
                    $('#widget-taskManagement-form select').attr("readonly", true);
                }else{
                    $('#widget-taskManagement-form input').attr("readonly", false);
                    $('#widget-taskManagement-form textarea').attr("readonly", false);
                    $('#widget-taskManagement-form select').attr("readonly", false);

                }

                $('#widget-taskManagement-form input[name=title]').val(response.action.title);
                $('#widget-taskManagement-form select[name=owner]').val(response.action.owner);
                $('#widget-taskManagement-form select[name=company]').val(response.action.company);
                $('#widget-taskManagement-form textarea[name=description]').val(response.action.description);
                $('#widget-taskManagement-form select[name=type]').val(response.action.type);
                $('#widget-offerTask-form select[name=company]').val(response.action.company);
                $('.taskManagementTitle').text("Informations");
            }
        }
    })

}
