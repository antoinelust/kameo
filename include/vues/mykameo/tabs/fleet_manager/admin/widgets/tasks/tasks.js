$( ".fleetmanager" ).click(function() {

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


$('.tasksManagerClick').click(function(){
    list_tasks('*', $('.taskOwnerSelection').val(), "<?php echo $user_data['EMAIL'] ?>");
    generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val());
});


//FleetManager: Gérer les Actions | Displays the task graph by calling action_company.php and creating it
function generateTasksGraphic(company, owner, numberOfDays){
  $.ajax({
    url: 'api/tasks',
    type: 'get',
    data: { "action": "getGraphic", "company": company, "owner": owner, "numberOfDays": numberOfDays},
    success: function(response){
      if (response.response == 'error') {
		  console.log(response.message);
	  }
	  else {
        var ctx = document.getElementById('myChart2').getContext('2d');
        if (typeof myChart4 !== 'undefined')
          myChart4.destroy();

        myChart4 = new Chart(ctx, {
          type: 'bar',
          data: {
            datasets: [{
              label: 'Actions totales',
              fillColor: "rgba(151,187,205,1)",
              strokeColor: "rgba(151,187,205,1)",
              highlightFill: "rgba(151,187,205,1)",
              highlightStroke: "rgba(151,187,205,1)",
              stack: 'Stack 0',
              data: response.arrayTotalTasks
            },{
              label: 'Prises de contact',
              borderColor: 'rgba(60, 137, 207, 255)',
              backgroundColor:'rgba(145, 145, 145, 0)',
              stack: 'Stack 0',
              data: response.arrayContacts,
              hidden:true
            },{
              label: 'Rappels',
              borderColor: 'rgba(223, 109, 130, 2)',
              backgroundColor: 'rgba(60, 179, 149, 0)',
              stack: 'Stack 0',
              data: response.arrayReminder,
              hidden: true
            },{
              label: 'Planification de rendez-vous',
              borderColor: 'rgba(175, 223, 223, 2)',
              backgroundColor:'rgba(176, 0, 0, 0)',
              data: response.arrayRDVPlan,
              stack: 'Stack 0',
              hidden: true
            },{
              label: 'Rendez-vous',
              borderColor: 'rgba(120, 91, 232, 2)',
              backgroundColor: 'rgba(60, 179, 149, 0)',
              data: response.arrayRDV,
              stack: 'Stack 0',
              hidden: true
            },{
              label: 'Offres',
              borderColor: 'rgba(226, 211, 139, 2)',
              backgroundColor:'rgba(60, 179, 149, 0)',
              stack: 'Stack 0',
              hidden: true
            },{
              label: 'Signature de contrat',
              borderColor: 'rgba(226, 211, 139, 2)',
              backgroundColor: 'rgba(60, 179, 149, 0)',
              data: response.arrayOffersSigned,
              stack: 'Stack 0',
              hidden: true
            },{
              label: 'Livraisons vélo',
              borderColor: 'rgba(235, 149, 97, 2)',
              backgroundColor: 'rgba(60, 179, 149, 0)',
              data: response.arrayDelivery,
              stack: 'Stack 0',
              hidden: true
            },{
              label: 'Autre',
              borderColor: 'rgba(60, 179, 149, 2)',
              backgroundColor: 'rgba(60, 179, 149, 0)',
              data: response.arrayOther,
              stack: 'Stack 0',
              hidden: true
            }],
            labels: response.arrayDates
          },
          options: {
            scales: {
              xAxes: [{ stacked: true }],
              yAxes: [{ stacked: true }]
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Evolution du nombre de tâches'
                },
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
        url: 'api/tasks',
        type: 'get',
        data: { "company": '*', "status": status, "owner":owner2, 'action': "list"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var i=0;
                var dest="";
                var temp="<table data-order='[[ 0, \"desc\" ]]' class=\"table table-condensed\" id=\"task_listing\"><h4 class=\"text-green\">Actions :</h4><br><a class=\"button small green button-3d rounded icon-right\" data-target=\"#taskManagement\" data-toggle=\"modal\"\" data-action='add' href=\"#\"><i class=\"fa fa-plus\"></i> Ajouter une action</a><br/><a class=\"button small green button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"list_tasks('*', $('.taskOwnerSelection').val())\" href=\"#\"><i class=\"fa\"></i> Toutes les actions ("+response.actionNumberTotal+")</a> <div class=\"seperator seperator-small visible-xs\"></div><a class=\"button small orange button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"list_tasks('TO DO', $('.taskOwnerSelection').val())\" href=\"#\"><i class=\"fa\"></i> TO DO ("+response.actionNumberNotDone+")</a> <a class=\"button small red button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"list_tasks('LATE', $('.taskOwnerSelection').val())\" href=\"#\"><i class=\"fa\"></i> Actions en retard ("+response.actionNumberLate+")</a><thead><tr><th style=\"width: 2%\">ID</th><th>Société</th><th>Date</th><th>Type</th><th>Titre</th><th>Rappel</th><th>Statut</th><th>Owner</th><th></th></tr></thead><tbody>";
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


                    var temp="<tr><td><a href=\"#\" data-action='retrieve' data-target=\"#taskManagement\" data-toggle=\"modal\" name=\""+response.action[i].id+"\">"+response.action[i].id+"</a></td><td>"+response.action[i].companyName+"</td><td data-sort=\""+(new Date(response.action[i].date)).getTime()+"\">"+response.action[i].date.shortDate()+"</td><td>"+type+"<td>"+response.action[i].title+"</td>"+date_reminder+"<td>"+status+"</td><td>"+ownerSpan+"</td><td><ins><a class=\"text-green\" data-target=\"#taskManagement\" data-action='update' name=\""+response.action[i].id+"\" data-toggle=\"modal\" href=\"#\">Update</a></ins></td></tr>";
                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tbody></table>";
                dest=dest.concat(temp);
                document.getElementById('tasksListingSpan').innerHTML = dest;

                if(owner2){
                    $('.taskOwnerSelection').val(owner2);
                }else{
                    $('.taskOwnerSelection').val('*');
                }
                $('.taskOwnerSelection2').val('*');


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


$('#taskManagement').off();
$('#taskManagement').on('shown.bs.modal', function(event){
  var action=$(event.relatedTarget).data('action');
  var ID=$(event.relatedTarget).attr('name');

  $("#widget-taskManagement-form select[name=company]")
    .find("option")
    .remove()
    .end();
  $.ajax({
    url: "api/companies",
    type: "get",
    data: { type: "*", action: 'listMinimal' },
    success: function (response) {
      if (response.response == "success") {
        response.company.forEach(function(company){
          $("#widget-taskManagement-form select[name=company]").append(
            '<option value="' +
              company.ID +
              '">' +
              company.companyName +
              "</option>"
          );
        });
        if(action=="retrieve"){
          $('#widget-taskManagement-form input').attr("readonly", true);
          $('#widget-taskManagement-form textarea').attr("readonly", true);
          $('#widget-taskManagement-form select').attr("readonly", true);
          $('.taskManagementTitle').text("Informations sur l'action");
          $('#widget-taskManagement-form input[name=ID]').parent().addClass("hidden");
          $('.taskManagementSendButton').addClass("hidden");
          retrieveTask(ID);
        }else{
          $('#widget-taskManagement-form input').attr("readonly", false);
          $('#widget-taskManagement-form textarea').attr("readonly", false);
          $('#widget-taskManagement-form select').attr("readonly", false);
          $('.taskManagementSendButton').removeClass("hidden");
          if(action=="add"){
            $('#widget-taskManagement-form input[name=date]').val(get_date_string());
            $('.taskManagementTitle').text("Ajouter une action");
            $('#widget-taskManagement-form input[name=ID]').parent().addClass("hidden");
            $('#widget-taskManagement-form').trigger('reset');
            $('#widget-taskManagement-form input[name=action]').val('add');
            $('#widget-taskManagement-form select[name=company]').val(12);
            $('#widget-taskManagement-form select[name=owner]').val(user_data.EMAIL);
            $('#widget-taskManagement-form input[name=date]').val(get_date_string());
          }else{
            $('.taskManagementTitle').text("Mettre à jour l'action");
            $('#widget-taskManagement-form input[name=action]').val('update');
            $('#widget-taskManagement-form input[name=ID]').parent().removeClass("hidden");
            $('#widget-taskManagement-form input[name=ID]').attr('readonly', true);
            retrieveTask(ID);
          }
        }
      }
    }
  });
});

function retrieveTask(ID){
  $.ajax({
    url: 'api/tasks',
    type: 'get',
    data: {"id": ID, 'action': "retrieve"},
    success: function(response){
      if(response.response == 'error') {
          console.log(response.message);
      }
      if(response.response == 'success'){
        $('#widget-taskManagement-form input[name=ID]').val(response.action.id);
        $('#widget-taskManagement-form input[name=title]').val(response.action.title);
        $('#widget-taskManagement-form input[name=date]').val(response.action.date.substr(0,10));
        $('#widget-taskManagement-form select[name=owner]').val(response.action.owner);
        $('#widget-taskManagement-form select[name=status]').val(response.action.status);
        $('#widget-taskManagement-form select[name=company]').val(response.action.company);
        $('#widget-taskManagement-form textarea[name=description]').val(response.action.description);
        $('#widget-taskManagement-form select[name=type]').val(response.action.type);
        $('#widget-taskManagement-form select[name=company]').val(response.action.companyID);
        $('#widget-taskManagement-form select[name=channel]').val(response.action.channel);
        if(response.action.date_reminder != null){
            $('#widget-taskManagement-form input[name=date_reminder]').val(response.action.date_reminder.substr(0,10));
        }else{
            $('#widget-taskManagement-form input[name=date_reminder]').val("");
        }
      }
    }
  });
}

$('#tasksListing select[name=taskOwnerSelection]').change(function(){
  list_tasks('*', $(this).val());
})
