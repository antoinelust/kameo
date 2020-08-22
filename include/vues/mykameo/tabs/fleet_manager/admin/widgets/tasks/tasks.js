$( ".fleetmanager" ).click(function() {
    initialize_task_owner_sales_selection();
    
    $.ajax({
        url: 'apis/Kameo/initialize_counters.php',
        type: 'post',
        data: { "email": email, "type": "tasks"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('counterTasks').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.actionNumberNotDone+"\" data-from=\"0\" data-seperator=\"true\">"+response.actionNumberNotDone+"</span>";
            }
        }
    })
    
    
    
});





function add_task(company){
document.getElementById('widget-taskManagement-form').reset();

    $('#widget-taskManagement-form label[for=channel]').addClass("required");
    $('#widget-taskManagement-form label[for=channel]').removeClass("hidden");
    $('#widget-taskManagement-form select[name=channel]').addClass("required");
    $('#widget-taskManagement-form select[name=channel]').removeClass("hidden");    
    $('#widget-taskManagement-form select[name=company]').val(company);
    $('#widget-taskManagement-form select[name=type]').val("contact");
    $('#widget-taskManagement-form input').attr("readonly", false);
    $('#widget-taskManagement-form textarea').attr("readonly", false);
    $('#widget-taskManagement-form select').attr("readonly", false);
    $('.taskManagementTitle').text("Ajouter une action");
    $('#widget-taskManagement-form select[name=owner]').val(email);
    $('#widget-taskManagement-form input[name=date]').val(get_dateNow_string());
}

//FleetManager: Gérer les Actions | List user task on <select> call
function taskFilter(e){
  list_tasks('*', $('.taskOwnerSelection').val(),'<?php echo $user_data['EMAIL'] ?>');
}

//FleetManager: Gérer les Actions | Displays the task graph by calling action_company.php and creating it
function generateTasksGraphic(company, owner, numberOfDays){
  $.ajax({
    url: 'apis/Kameo/action_company.php',
    type: 'get',
    data: { "action": "graphic", "company": company, "owner": owner, "numberOfDays": numberOfDays},
    success: function(response){
      if (response.response == 'error') {
		  console.log(response.message);
	  }
	  else {
        var ctx = document.getElementById('myChart2').getContext('2d');
        if (typeof myChart4 !== 'undefined')
          myChart4.destroy();

        myChart4 = new Chart(ctx, {
          type: 'line',
          data: {
            datasets: [{
              label: 'Actions totales',
              fillColor: "rgba(151,187,205,1)",
              strokeColor: "rgba(151,187,205,1)",
              highlightFill: "rgba(151,187,205,1)",
              highlightStroke: "rgba(151,187,205,1)",
              data: response.arrayTotalTasks
            },{
              label: 'Prises de contact',
              borderColor: 'rgba(60, 137, 207, 255)',
              backgroundColor:'rgba(145, 145, 145, 0)',
              data: response.arrayContacts,
              hidden:true
            },{
              label: 'Rappels',
              borderColor: 'rgba(223, 109, 130, 2)',
              backgroundColor:'rgba(60, 179, 149, 0)',
              data: response.arrayReminder,
              hidden: true
            },{
              label: 'Planification de rendez-vous',
              borderColor: 'rgba(175, 223, 223, 2)',
              backgroundColor:'rgba(176, 0, 0, 0)',
              data: response.arrayRDVPlan,
              hidden: true
            },{
              label: 'Rendez-vous',
              borderColor: 'rgba(120, 91, 232, 2)',
              backgroundColor:'rgba(60, 179, 149, 0)',
              data: response.arrayRDV,
              hidden: true
            },{
              label: 'Offres',
              borderColor: 'rgba(226, 211, 139, 2)',
              backgroundColor:'rgba(60, 179, 149, 0)',
              data: response.arrayOffers,
              hidden: true
            },{
              label: 'Signature de contrat',
              borderColor: 'rgba(226, 211, 139, 2)',
              backgroundColor:'rgba(60, 179, 149, 0)',
              data: response.arrayOffersSigned,
              hidden: true
            },{
              label: 'Livraisons vélo',
              borderColor: 'rgba(235, 149, 97, 2)',
              backgroundColor:'rgba(60, 179, 149, 0)',
              data: response.arrayDelivery,
              hidden: true
            },{
              label: 'Autre',
              borderColor: 'rgba(60, 179, 149, 2)',
              backgroundColor:'rgba(60, 179, 149, 0)',
              data: response.arrayOther,
              hidden: true
            }],
            labels: response.arrayDates
          },
          options: {
            scales: {
              yAxes: [{
                ticks: { beginAtZero: true, stacked: true }
              }]
            },
            elements: {
              line: { tension: 0 }
            }
          }
        });
        if(response.presenceContacts=="1")
          myChart4.data.datasets[1].hidden=false;
        if(response.presenceReminder=="1")
          myChart4.data.datasets[2].hidden=false;
        if(response.presenceRDVPlan=="1")
          myChart4.data.datasets[3].hidden=false;
        if(response.presenceRDV=="1")
          myChart4.data.datasets[4].hidden=false;
        if(response.presenceOffers=="1")
          myChart4.data.datasets[5].hidden=false;
        if(response.presenceOffersSigned=="1")
          myChart4.data.datasets[6].hidden=false;
        if(response.presenceDelivery=="1")
          myChart4.data.datasets[7].hidden=false;
        if(response.presenceOther=="1")
          myChart4.data.datasets[8].hidden=false;

        myChart4.update();
      }
    }
  });
}

function list_tasks(status, owner2, email) {
        
    if(!owner2){
        owner2=email;
    }
    $.ajax({
        url: 'apis/Kameo/action_company.php',
        type: 'get',
        data: { "company": '*', "status": status, "owner":owner2},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var i=0;
                var dest="";
                var temp="<table data-order='[[ 0, \"desc\" ]]' class=\"table table-condensed\" id=\"task_listing\"><h4 class=\"fr-inline text-green\">Actions :</h4><h4 class=\"en-inline text-green\">Actions:</h4><h4 class=\"nl-inline text-green\">Actions:</h4><br><a class=\"button small green button-3d rounded icon-right addTask\" data-target=\"#taskManagement\" data-toggle=\"modal\"\" href=\"#\" name=\"KAMEO\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une action</span></a><br/><a class=\"button small green button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"list_tasks('*', $('.taskOwnerSelection').val())\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Toutes les actions ("+response.actionNumberTotal+")</span></a> <div class=\"seperator seperator-small visible-xs\"></div><a class=\"button small orange button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"list_tasks('TO DO', $('.taskOwnerSelection').val())\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> TO DO ("+response.actionNumberNotDone+")</span></a> <a class=\"button small red button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"list_tasks('LATE', $('.taskOwnerSelection').val())\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Actions en retard ("+response.actionNumberLate+")</span></a><thead><tr><th style=\"width: 2%\">ID</th><th><span class=\"fr-inline\">Société</span></th><th><span class=\"fr-inline\">Date</span></th><th>Type</th><th><span class=\"fr-inline\">Titre</span></th><th><span class=\"fr-inline\">Rappel</span></th><th><span class=\"fr-inline\">Statut</span></th><th>Owner</th><th></th></tr></thead><tbody>";
                dest=dest.concat(temp);
                while (i < response.actionNumber){
                    if(response.action[i].date_reminder!=null){
                        var date_reminder=response.action[i].date_reminder.shortDate();
                    }else{
                        var date_reminder="N/A";
                    }

                    var status=response.action[i].status;
                    var ownerSpan=response.action[i].ownerFirstName+" "+response.action[i].ownerName;

                    if(response.action[i].late && response.action[i].status=='TO DO'){
                        date_reminder="<td class=\"text-red\" data-sort=\""+(new Date(response.action[i].date_reminder)).getTime()+"\">"+date_reminder+"</span>";
                        status="<span class='text-red'>"+status+"</span>";
                        owner="<span class='text-red'>"+ownerSpan+"</span>";
                    }else if(response.action[i].status=='DONE'){
                        date_reminder="<td class=\"text-green\" data-sort=\""+(new Date(response.action[i].date_reminder)).getTime()+"\">"+date_reminder+"</span>";
                        status="<span class='text-green'>"+status+"</span>";
                        owner="<span class='text-green'>"+ownerSpan+"</span>";
                    }else if(status='TO DO'){
                        date_reminder="<td class=\"text-orange\" data-sort=\""+(new Date(response.action[i].date_reminder)).getTime()+"\">"+date_reminder+"</span>";
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


                    var temp="<tr><td><a href=\"#\" class=\"retrieveTask\" data-target=\"#taskManagement\" data-toggle=\"modal\" name=\""+response.action[i].id+"\">"+response.action[i].id+"</a></td><td>"+response.action[i].company+"</td><td data-sort=\""+(new Date(response.action[i].date)).getTime()+"\">"+response.action[i].date.shortDate()+"</td><td>"+type+"<td>"+response.action[i].title+"</td>"+date_reminder+"<td>"+status+"</td><td>"+ownerSpan+"</td><td><ins><a class=\"text-green updateAction\" data-target=\"#updateAction\" name=\""+response.action[i].id+"\" data-toggle=\"modal\" href=\"#\">Update</a></ins></td></tr>";
                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tbody></table>";
                dest=dest.concat(temp);
                document.getElementById('tasksListingSpan').innerHTML = dest;


                $(".retrieveTask").click(function() {
                    retrieve_task(this.name, "retrieve");
                    $('.taskManagementSendButton').addClass("hidden");


                });

                $(".updateAction").click(function() {
                    construct_form_for_action_update(this.name);
                    $('.taskManagementSendButton').removeClass("hidden");
                    $('.taskManagementSendButton').text("Modifier")
                    
                });
                $(".addTask").click(function() {
                    add_task(this.name);
                    $('.taskManagementSendButton').removeClass("hidden");
                    $('.taskManagementSendButton').text("Ajouter")

                });
                
                if(owner2){
                    $('.taskOwnerSelection').val(owner2);
                }else{
                    $('.taskOwnerSelection').val('*');
                }
                $('.taskOwnerSelection2').val('*');


                displayLanguage();

                $('#task_listing').DataTable( {
                    paging: true,
                  "columns": [
                    { "width": "50px" },
                    { "width": "50px" },
                    { "width": "200px" },
                    { "width": "180px" },
                    { "width": "500px" },
                    { "width": "100px" },
                    { "width": "100px" },
                    { "width": "100px" },
                    { "width": "100px" }                          
                    ]
                })
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
            url: 'apis/Kameo/get_kameobikes_members.php',
            type: 'get',
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    while (i < response.membersNumber){
                        if(response.member[i].staann == 'D'){
                            $('#widget-updateAction-form select[name=owner]').append("<option value="+response.member[i].email+">"+response.member[i].firstName+" "+response.member[i].name+" - Supprimé <br>");
                        }else{
                            $('#widget-updateAction-form select[name=owner]').append("<option value="+response.member[i].email+">"+response.member[i].firstName+" "+response.member[i].name+"<br>");
                        }
                        i++;
                    }
                }
            }
        }).done(function(){
            $.ajax({
                url: 'apis/Kameo/action_company.php',
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
        url: 'apis/Kameo/action_company.php',
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
                $('#widget-taskManagement-form input[name=date]').val(response.action.date.substr(0,10));                
                $('#widget-taskManagement-form select[name=owner]').val(response.action.owner);
                $('#widget-taskManagement-form select[name=status]').val(response.action.status);
                $('#widget-taskManagement-form select[name=company]').val(response.action.company);
                $('#widget-taskManagement-form textarea[name=description]').val(response.action.description);
                $('#widget-taskManagement-form select[name=type]').val(response.action.type);
                $('#widget-offerTask-form select[name=company]').val(response.action.company);
                if(response.action.date_reminder != null){
                    $('#widget-taskManagement-form input[name=date_reminder]').val(response.action.date_reminder.substr(0,10));
                }else{
                    $('#widget-taskManagement-form input[name=date_reminder]').val("");
                }
                $('.taskManagementTitle').text("Informations sur l'action");
                $('.taskManagementSendButton').addClass("hidden");
            }
        }
    })

}
