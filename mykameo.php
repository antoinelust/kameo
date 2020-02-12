<?php
ob_start();
session_start();
include 'include/header2.php';
// checkAccess();
$user=$_SESSION['userID'];


if($user==NULL){
    $connected=false;
}else{
    $connected=true;
}

$langue=$_SESSION['langue'];
include 'include/activitylog.php';
?>

<!-- Language management -->
<script type="text/javascript" src="js/language.js"></script>
<script type="text/javascript" src="./js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript" src="./node_modules/chart.js/dist/Chart.js" charset="UTF-8"></script>
<style media="screen">
.tableFixed {
  table-layout: fixed;
}
.separator-small{
  padding-top:20px;
  width:60%;
  opacity: 0.5;
}
</style>


<script type="text/javascript">
var color=Chart.helpers.color;

//id de la compagnie selectionnée si il y en a une sélectionnée
var companyId;

//varibles des charts chartJS
var myChart;
var myChart2;
var myChart3;


window.addEventListener("DOMContentLoaded", function(event) {

    var classname = document.getElementsByClassName('fleetmanager');
    for (var i = 0; i < classname.length; i++) {
        classname[i].addEventListener('click', hideResearch, false);
        classname[i].addEventListener('click', get_bikes_listing, false);
        classname[i].addEventListener('click', get_users_listing, false);
        classname[i].addEventListener('click', get_company_conditions(), false);
        classname[i].addEventListener('click', list_condition, false);

        classname[i].addEventListener('click', function () { get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()))}, false);
        classname[i].addEventListener('click', function () { get_bills_listing(document.getElementsByClassName('billSelectionText')[0].innerHTML, '*', '*', '*')}, false);
        classname[i].addEventListener('click', function () { get_company_listing('*')}, false);
        classname[i].addEventListener('click', function () { listPortfolioBikes()}, false);
        classname[i].addEventListener('click', function () { list_feedbacks()}, false);
        classname[i].addEventListener('click', function () { list_bikes_admin()}, false);
        classname[i].addEventListener('click', function () { list_tasks('*', $('.taskOwnerSelection').val(), $('.tasksListing_number').val())}, false);
        classname[i].addEventListener('click', function () { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);
        classname[i].addEventListener('click', initialize_booking_counter, false);



        var tempDate=new Date();
        $(".form_date_end_client").data("datetimepicker").setDate(tempDate);
        tempDate.setMonth(tempDate.getMonth()-6);
        $(".form_date_start_client").data("datetimepicker").setDate(tempDate);

        classname[i].addEventListener('click', function () { generateCompaniesGraphic($('.form_date_start_client').data("datetimepicker").getDate(), $('.form_date_end_client').data("datetimepicker").getDate())}, false);


        classname[i].addEventListener('click', function () { list_boxes("*")}, false);
        classname[i].addEventListener('click', function () { initializeFields()}, false);

    }

    var classname = document.getElementsByClassName('reservations');
    for (var i = 0; i < classname.length; i++) {
        classname[i].addEventListener('click', hideResearch, false);

    }



    document.getElementById('search-bikes-form-intake-hour').addEventListener('change', function () { update_deposit_form()}, false);
    document.getElementsByClassName('reservationlisting')[0].addEventListener('click', function () { reservation_listing()}, false);
    document.getElementsByClassName('portfolioManagerClick')[0].addEventListener('click', function() { listPortfolioBikes()}, false);
    document.getElementsByClassName('bikeManagerClick')[0].addEventListener('click', function() { list_bikes_admin()}, false);
    document.getElementsByClassName('tasksManagerClick')[0].addEventListener('click', function() { list_tasks('*', $('.taskOwnerSelection').val(), $('.tasksListing_number').val());
}, false);
    document.getElementsByClassName('offerManagerClick')[0].addEventListener('click', function() { list_contracts_offers('*')}, false);
    document.getElementsByClassName('feedbackManagementClick')[0].addEventListener('click', function() { list_feedbacks()}, false);
    document.getElementsByClassName('taskOwnerSelection')[0].addEventListener('change', function() { taskFilter()}, false);
    document.getElementsByClassName('taskOwnerSelection2')[0].addEventListener('change', function() { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);
    document.getElementsByClassName('tasksListing_number')[0].addEventListener('change', function() { taskFilter()}, false);
    document.getElementsByClassName('numberOfDays')[0].addEventListener('change', function() { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);


    var tempDate=new Date();
    $(".form_date_end").data("datetimepicker").setDate(tempDate);
    tempDate.setMonth(tempDate.getMonth()-1);
    $(".form_date_start").data("datetimepicker").setDate(tempDate);

    <?php
    if(isset($_GET['feedback'])){
    ?>
        initiatizeFeedback(<?php echo $_GET['feedback']; ?>);
    <?php
    }
    ?>

});

function initiatizeFeedback(id){
    $.ajax({
        url: 'include/feedback_management.php',
        type: 'get',
        data: {"action": "retrieveBooking", "ID": id},
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


                $('.feedbackManagementTitle').html("Ajouter un feedback");
                $('#feedbackManagement input[name=bike]').val(response.bikeNumber);
                $('#feedbackManagement input[name=startDate]').val(formattedTimeStart);
                $('#feedbackManagement input[name=endDate]').val(formattedTimeEnd);
                $('#feedbackManagement input[name=ID]').val(response.ID);
                $('#feedbackManagement input[name=utilisateur]').val(response.email);
                document.getElementsByClassName("feedbackBikeImage")[0].src="images_bikes/"+response.bikeNumber+"_mini.jpg";
                $('#feedbackManagement select[name=note]').attr("readonly", false);
                $('#feedbackManagement textarea[name=comment]').attr("readonly", false);
                $('.feedbackManagementSendButton').removeClass('hidden');

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
                var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Feedbacks:</h4><h4 class=\"en-inline text-green\">Feedbacks:</h4><h4 class=\"nl-inline text-green\">Feedbacks:</h4><br/><br/><div class=\"seperator seperator-small visible-xs\"></div><tbody><thead><tr><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th>Start</th><th>End</th><th><span class=\"fr-inline\">Note</span><span class=\"en-inline\">Note</span><span class=\"nl-inline\">Note</span></th><th><span class=\"fr-inline\">Commentaire</span><span class=\"en-inline\">Comment</span><span class=\"nl-inline\">Comment</span></th><th><span class=\"fr-inline\">Entretien ? </span><span class=\"en-inline\">Maintenance ?</span><span class=\"nl-inline\">Maintenance ?</span></th><th><span class=\"fr-inline\">E-mail</span><span class=\"en-inline\">E-mail</span><span class=\"nl-inline\">E-mail</span></th></tr></thead>";
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
                    var formattedTimeStart = day +'/' + month + '/' + year + ' ' + hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

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
                    var formattedTimeEnd = day +'/' + month + '/' + year + ' ' + hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

                    if(response.feedback[i].entretien==null || response.feedback[i].entretien=="0"){
                        entretien="<span class=\"text-red\">OUI</span>";
                    }else{
                        entretien="<span class=\"text-green\">Non</span>";
                    }



                    var temp="<tr><td><a href=\"#\" class=\"text-green retrieveFeedback\" data-target=\"#feedbackManagement\" name=\""+response.feedback[i].IDReservation+"\" data-toggle=\"modal\">"+response.feedback[i].IDReservation+"</a></td><td>"+response.feedback[i].company+"</td><td>"+formattedTimeStart+"</td><td>"+formattedTimeEnd+"</td><td>"+response.feedback[i].note+"</td><td>"+response.feedback[i].comment.substr(0,20)+"</td><td>"+entretien+"</td><td>"+response.feedback[i].email+"</td></tr>";
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
                $('#feedbackManagement textarea[name=comment]').val(response.comment);
                document.getElementsByClassName("feedbackBikeImage")[0].src="images_bikes/"+response.bike+"_mini.jpg";

                $('#feedbackManagement select[name=note]').attr("readonly", true);
                $('#feedbackManagement textarea[name=comment]').attr("readonly", true);

                if(response.entretien=="1"){
                    $('#feedbackManagement input[name=entretien]').prop("checked", true);
                }else{
                    $('#feedbackManagement input[name=entretien]').prop("checked", false);
                }

                $('.feedbackManagementSendButton').addClass('hidden');

            }

            displayLanguage();
        }
    })
}



function initializeFields(){

    $('#widget-bikeManagement-form select[name=company]')
        .find('option')
        .remove()
        .end()
    ;

    $('#widget-updateAction-form select[name=company]')
        .find('option')
        .remove()
        .end()
    ;

    $('#widget-taskManagement-form select[name=company]')
        .find('option')
        .remove()
        .end()
    ;
    $('#widget-boxManagement-form select[name=company]')
        .find('option')
        .remove()
        .end()
    ;


    $.ajax({
        url: 'include/get_companies_listing.php',
        type: 'post',
        data: {type: "*"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var i=0;
                while (i < response.companiesNumber){
                    $('#widget-bikeManagement-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>");
                    $('#widget-updateAction-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>");
                    $('#widget-taskManagement-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>");
                    $('#widget-boxManagement-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>");
                    i++;
                }

            }
        }
    })


}

function reservation_listing(){
    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));
    $('#ReservationsListing').modal('toggle');

}

function bikeFilter(e){
    document.getElementsByClassName('bikeSelectionText')[0].innerHTML=e;
    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));

}
function taskFilter(e){
    list_tasks('*', $('.taskOwnerSelection').val(), $('.tasksListing_number').val());

}

function billFilter(e){
    document.getElementsByClassName('billSelectionText')[0].innerHTML=e;
    get_bills_listing(document.getElementsByClassName('billSelectionText')[0].innerHTML, '*', '*', '*');

}

function generateTasksGraphic(company, owner, numberOfDays){


    $.ajax({
        url: 'include/action_company.php',
        type: 'get',
        data: { "action": "graphic", "company": company, "owner": owner, "numberOfDays": numberOfDays},
        success: function(response){
            if (response.response == 'error') {
                console.log(response.message);
            } else{


                var ctx = document.getElementById('myChart2').getContext('2d');
                if (myChart2 != undefined) {
                  myChart2.destroy();
                }
                myChart2 = new Chart(ctx, {
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
                                ticks: {
                                    beginAtZero: true,
                                    stacked: true

                                }
                            }]
                        },
                        elements: {
                            line: {
                                tension: 0
                            }
                        }

                    }

                });
                if(response.presenceContacts=="1"){
                    myChart2.data.datasets[1].hidden=false;
                }
                if(response.presenceReminder=="1"){
                    myChart2.data.datasets[2].hidden=false;
                }
                if(response.presenceRDVPlan=="1"){
                    myChart2.data.datasets[3].hidden=false;
                }
                if(response.presenceRDV=="1"){
                    myChart2.data.datasets[4].hidden=false;
                }
                if(response.presenceOffers=="1"){
                    myChart2.data.datasets[5].hidden=false;
                }
                if(response.presenceOffersSigned=="1"){
                    myChart2.data.datasets[6].hidden=false;
                }
                if(response.presenceDelivery=="1"){
                    myChart2.data.datasets[7].hidden=false;
                }
                if(response.presenceOther=="1"){
                    myChart2.data.datasets[8].hidden=false;
                }
                myChart2.update();

            }

        }
    })



}

function generateCompaniesGraphic(dateStart, dateEnd){


    dateStartString=dateStart.getFullYear()+"-"+("0" + (dateStart.getMonth() + 1)).slice(-2)+"-"+("0" + dateStart.getDate()).slice(-2);
    dateEndString=dateEnd.getFullYear()+"-"+("0" + (dateEnd.getMonth() + 1)).slice(-2)+"-"+("0" + dateEnd.getDate()).slice(-2);

    $.ajax({
        url: 'include/get_companies_listing.php',
        type: 'get',
        data: { "action": "graphic", "numberOfDays": "30", "dateStart": dateStartString, "dateEnd": dateEndString},
        success: function(response){
            if (response.response == 'error') {
                console.log(response.message);
            } else{

                var ctx = document.getElementById('myChart3').getContext('2d');
                if (myChart3 != undefined) {
                  myChart3.destroy();
                }

                var presets=window.chartColors;

                var myChart3 = new Chart(ctx, {
                    type: 'line',
                    data: {
                        datasets: [{
                            label: 'Entreprises en contact',
                            borderColor: "#0F7096",
                            backgroundColor: "#288DBF",
                            data: response.companiesContact
                        },{
                            label: 'Entreprises sous offre',
                            borderColor: "#99111C",
                            backgroundColor: "#C1272D",
                            data: response.companiesOffer
                        },{
                            label: 'Entreprises sous offre signée',
                            borderColor: "#1D9377",
                            backgroundColor: "#3cb395",
                            data: response.companiesOfferSigned
                        }],
                        labels:response.dates
                    },


                    options: {
                        scales: {
                            yAxes: [{
                                stacked: true,
                                beginAtZero: true
                            }]
                        },
                        elements: {
                            line: {
                                tension: 0
                            }
                        }
                    }
                });
                myChart3.update();
            }
        }
    })
}

function construct_form_for_bike_status_update(frameNumber){
    var frameNumber=frameNumber;

    $.ajax({
            url: 'include/get_bike_details.php',
            type: 'post',
            data: { "frameNumber": frameNumber},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{
                    document.getElementsByClassName("bikeReference")[1].innerHTML=frameNumber;
                    document.getElementsByClassName("bikeModel")[1].value=response.model;
                    document.getElementsByClassName("frameReference")[1].innerHTML=response.frameReference;
                    document.getElementsByClassName("contractType")[1].innerHTML=response.contractType;
                    document.getElementsByClassName("startDateContract")[1].innerHTML=response.contractStart;
                    document.getElementsByClassName("endDateContract")[1].innerHTML=response.contractEnd;
                    document.getElementsByClassName("bikeImage")[1].src="images_bikes/"+frameNumber+"_mini.jpg";

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

                    document.getElementById('widget-updateBikeStatus-form-frameNumber').value = frameNumber;
                    document.getElementById('bikeBuildingAccess').innerHTML = dest;

                }

            }
    })
}

function construct_form_for_bike_status_updateAdmin(frameNumber){

    var company;
    var frameNumber=frameNumber;

    $('#widget-addActionBike-form input[name=bikeNumber]').val(frameNumber);
    $('.bikeActions').removeClass('hidden');
    $('#widget-bikeManagement-form input[name=action]').val("update");
    $('#widget-bikeManagement-form select[name=portfolioID]')
        .find('option')
        .remove()
        .end()
    ;
    $('#widget-bikeManagement-form select[name=portfolioID]').unbind();

    $.ajax({
            url: 'include/load_portfolio.php',
            type: 'get',
            data: {"action": "list"},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{
                    var i=0;
                    while(i<response.bikeNumber){
                        $('#widget-bikeManagement-form select[name=portfolioID]').append("<option value="+response.bike[i].ID+">"+response.bike[i].brand+" - "+response.bike[i].model+" - "+response.bike[i].frameType+"<br>");
                        i++;
                    }
                }
            }
    }).done(function(){
        document.getElementById('bikeBuildingAccessAdmin').innerHTML = "";
        document.getElementById('bikeUserAccessAdmin').innerHTML = "";
        $.ajax({
                url: 'include/get_bike_details.php',
                type: 'post',
                data: { "frameNumber": frameNumber},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{
                        document.getElementById("bikeManagementPicture").src="images_bikes/"+response.frameNumber+"_mini.jpg";
                        $('bikeManagementPicture').removeClass('hidden');

                        $('#widget-bikeManagement-form input[name=frameNumber]').val(frameNumber);
                        $('#widget-deleteBike-form input[name=frameNumber]').val(frameNumber);
                        $('#widget-bikeManagement-form input[name=frameNumberOriginel]').val(frameNumber);
                        $('#widget-bikeManagement-form input[name=model]').val(response.model);
                        $('#widget-bikeManagement-form input[name=size]').val(response.size);
                        $('#widget-bikeManagement-form input[name=frameReference]').val(response.frameReference);
                        $('#widget-bikeManagement-form input[name=price]').val(response.bikePrice);
                        $('#widget-bikeManagement-form input[name=buyingDate]').val(response.buyingDate);
                        $('#widget-bikeManagement-form select[name=billingType]').val(response.billingType);
                        $('#widget-bikeManagement-form select[name=contractType]').val(response.contractType);
                        if(response.contractStart){
                            $('#widget-bikeManagement-form input[name=contractStart]').val(response.contractStart.substr(0,10));
                        }else{
                            $('#widget-bikeManagement-form input[name=contractStart]').val("");
                        }
                        if(response.contractEnd){
                            $('#widget-bikeManagement-form input[name=contractEnd]').val(response.contractEnd.substr(0,10));
                        }else{
                            $('#widget-bikeManagement-form input[name=contractEnd]').val("");
                        }
                        if(response.type==0){
                            $('#widget-bikeManagement-form select[name=portfolioID]').val("");
                        }else{
                            $('#widget-bikeManagement-form select[name=portfolioID]').val(response.type);
                        }

                        company=response.company;

                        if(response.leasing=="Y"){
                            $('#widget-bikeManagement-form input[name=billing]').prop("checked", true);
                        }else{
                            $('#widget-bikeManagement-form input[name=billing]').prop("checked", false);
                        }

                        if(response.insurance=="Y"){
                            $('#widget-bikeManagement-form input[name=insurance]').prop("checked", true);
                        }else{
                            $('#widget-bikeManagement-form input[name=insurance]').prop("checked", false);
                        }


                        $('#widget-bikeManagement-form input[name=billingPrice]').val(response.leasingPrice);

                        $('#widget-bikeManagement-form input[name=billingGroup]').val(response.billingGroup);


                        document.getElementsByClassName("bikeManagementPicture")[0].src="images_bikes/"+frameNumber+"_mini.jpg";

                        if(response.status=="OK"){
                            $('#widget-bikeManagement-form input[name=bikeStatus]').val('OK');
                        }
                        else{
                            $('#widget-bikeManagement-form input[name=bikeStatus]').val('KO');
                        }
                        i=0;
                        var dest="";
                        if(response.buildingNumber==0){
                            temp="<div class=\"col-sm-12 fr\"><p><trong>Pas de bâtiments</strong> définis pour cette société, commencez par en créer un et vous pourrez ensuite y assigner ce vélo</p></div>";
                            temp=temp.concat("<div class=\"col-sm-12 en\"><p><strong>Nos building</strong> defined for that company, please first create one and then you will be able to link that building and the bike</p></div>");
                            temp=temp.concat("<div class=\"col-sm-12 nl\"><p><strong>Nos building</strong> defined for that company, please first create one and then you will be able to link that building and the bike</p></div>");
                            dest=dest.concat(temp);

                        }else{
                            while(i<response.buildingNumber){
                                if(response.building[i].access==true){
                                    temp="<div class=\"col-sm-3\"><input type=\"checkbox\" checked name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\">"+response.building[i].descriptionFR+"</div>";
                                }
                                else{
                                    temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\">"+response.building[i].descriptionFR+"</div>";
                                }
                                dest=dest.concat(temp);
                                i++;
                            }
                        }

                        document.getElementById('bikeBuildingAccessAdmin').innerHTML = dest;

                        i=0;
                        var dest="";

                        if(response.userNumber==0){
                            temp="<div class=\"col-sm-12 fr\"><p><trong>Pas d'utilitisateurs</strong> définis pour cette société, commencez par en créer un et vous pourrez ensuite luis donner accès à ce vélo </p></div>";
                            temp=temp.concat("<div class=\"col-sm-12 en\"><p><strong>Nos user</strong> defined for that company, please first create one and then you will be able to link that user and the bike</p></div>");
                            temp=temp.concat("<div class=\"col-sm-12 nl\"><p><strong>Nos user</strong> defined for that company, please first create one and then you will be able to link that user and the bike</p></div>");
                            dest=dest.concat(temp);

                        }else{
                            while(i<response.userNumber){
                                if(response.user[i].access==true){
                                    temp="<div class=\"col-sm-3\"><input type=\"checkbox\" checked name=\"userAccess[]\" value=\""+response.user[i].email+"\">"+response.user[i].name+" "+response.user[i].firstName+"</div>";
                                }
                                else if(response.user[i].access==false){
                                    temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"userAccess[]\" value=\""+response.user[i].email+"\">"+response.user[i].name+" "+response.user[i].firstName+"</div>";
                                }
                                dest=dest.concat(temp);
                                i++;
                            }
                        }
                        document.getElementById('bikeUserAccessAdmin').innerHTML = dest;

                        $('#widget-bikeManagement-form select[name=company]').val(company);
                        $('#widget-bikeManagement-form select[name=company]').change(function(){
                            updateAccessAdmin($('#widget-bikeManagement-form input[name=frameNumber]').val(), $('#widget-bikeManagement-form select[name=company]').val());
                        });
                    }

                }
        }).done(function(response){
            $.ajax({
                url: 'include/action_bike_management.php',
                type: 'post',
                data: { "readActionBike-action": "read", "readActionBike-bikeNumber": frameNumber, "readActionBike-user": "<?php echo $user; ?>"},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{

                        var i=0;
                        var dest="<table class=\"table table-condensed\"><a class=\"button small green button-3d rounded icon-right addActionBikeButton\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une action</span></a><tbody><thead><tr><th><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th><span class=\"fr-inline\">Description</span><span class=\"en-inline\">Description</span><span class=\"nl-inline\">Description</span></th><th><span class=\"fr-inline\">Public ?</span><span class=\"en-inline\">Public ?</span><span class=\"nl-inline\">Public ?</span></th></tr></thead> ";
                        while(i<response.actionNumber){
                            if(response.action[i].public=="1"){
                                var public="Yes";
                            }else{
                                var public="No";
                            }
                            var temp="<tr><td>"+response.action[i].date.substring(0,10)+"</td><td>"+response.action[i].description+"</td><td>"+public+"</td></tr>";
                            dest=dest.concat(temp);
                            i++;
                        }
                        dest=dest.concat("</tbody></table>");
                        $('#action_bike_log').html(dest);
                        $(".widget-deleteBike-form[name='frameNumber']").val(frameNumber);


                        displayLanguage();

                        document.getElementsByClassName("addActionBikeButton")[0].addEventListener('click', function(){
                            $("label[for='widget-addActionBike-form-date']").removeClass("hidden");
                            $('input[name=widget-addActionBike-form-date]').removeClass("hidden");
                            $("label[for='widget-addActionBike-form-description']").removeClass("hidden");
                            $('input[name=widget-addActionBike-form-description]').removeClass("hidden");
                            $("label[for='widget-addActionBike-form-public']").removeClass("hidden");
                            $('input[name=widget-addActionBike-form-public]').removeClass("hidden");
                            $('.addActionConfirmButton').removeClass("hidden");
                        });

                    }

                }
            })
        })
    })
}

function construct_form_for_bike_access_updateAdmin(frameNumber, company){
    if(frameNumber){
        $.ajax({
                url: 'include/get_bike_details.php',
                type: 'post',
                data: { "frameNumber": frameNumber, "company": company},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{
                        i=0;
                        var dest="";
                        var dest2="<label for=\"firstBuilding\">Bâtiment pour initialisation</label><select name=\"firstBuilding\">";

                        if(response.buildingNumber==0){
                            temp="<div class=\"col-sm-12 fr\"><p><trong>Pas de bâtiments</strong> définis pour cette société, commencez par en créer un et vous pourrez ensuite y assigner ce vélo</p></div>";
                            temp=temp.concat("<div class=\"col-sm-12 en\"><p><strong>Nos building</strong> defined for that company, please first create one and then you will be able to link that building and the bike</p></div>");
                            temp=temp.concat("<div class=\"col-sm-12 nl\"><p><strong>Nos building</strong> defined for that company, please first create one and then you will be able to link that building and the bike</p></div>");
                            dest=dest.concat(temp);

                        }else{
                            while(i<response.buildingNumber){
                                temp2="<option value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"</option>";
                                dest2=dest2.concat(temp2);

                                if(response.building[i].access==true){
                                    temp="<div class=\"col-sm-3\"><input type=\"checkbox\" checked name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\">"+response.building[i].descriptionFR+"</div>";
                                }
                                else if(response.building[i].access==false){
                                    temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\">"+response.building[i].descriptionFR+"</div>";
                                }
                                dest=dest.concat(temp);
                                i++;
                            }
                        }
                        dest2=dest2.concat("</select>");
                        document.getElementById('addBike_firstBuilding').innerHTML = dest2;

                        document.getElementById('bikeBuildingAccessAdmin').innerHTML = dest;
                        i=0;
                        var dest="";
                        if(response.userNumber==0){
                            temp="<div class=\"col-sm-12 fr\"><p><trong>Pas d'utilitisateurs</strong> définis pour cette société, commencez par en créer un et vous pourrez ensuite luis donner accès à ce vélo </p></div>";
                            temp=temp.concat("<div class=\"col-sm-12 en\"><p><strong>Nos user</strong> defined for that company, please first create one and then you will be able to link that user and the bike</p></div>");
                            temp=temp.concat("<div class=\"col-sm-12 nl\"><p><strong>Nos user</strong> defined for that company, please first create one and then you will be able to link that user and the bike</p></div>");
                            dest=dest.concat(temp);
                        }else{
                            while(i<response.userNumber){
                                if(response.user[i].access==true){
                                    temp="<div class=\"col-sm-3\"><input type=\"checkbox\" checked name=\"userAccess[]\" value=\""+response.user[i].email+"\">"+response.user[i].name+" "+response.user[i].firstName+"</div>";
                                }
                                else if(response.user[i].access==false){
                                    temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"userAccess[]\" value=\""+response.user[i].email+"\">"+response.user[i].name+" "+response.user[i].firstName+"</div>";
                                }
                                dest=dest.concat(temp);
                                i++;
                            }
                        }
                        document.getElementById('bikeUserAccessAdmin').innerHTML = dest;

                        displayLanguage();


                    }

                }
        })
    }else{
        $.ajax({
                url: 'include/get_building_listing.php',
                type: 'post',
                data: { "company": company},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{
                        i=0;
                        var dest="";
                        var dest2="<label for=\"firstBuilding\">Bâtiment pour initialisation</label><select name=\"firstBuilding\">";

                        if(response.buildingNumber==0){
                            temp="<div class=\"col-sm-12 fr\"><p><trong>Pas de bâtiments</strong> définis pour cette société, commencez par en créer un et vous pourrez ensuite y assigner ce vélo</p></div>";
                            temp=temp.concat("<div class=\"col-sm-12 en\"><p><strong>Nos building</strong> defined for that company, please first create one and then you will be able to link that building and the bike</p></div>");
                            temp=temp.concat("<div class=\"col-sm-12 nl\"><p><strong>Nos building</strong> defined for that company, please first create one and then you will be able to link that building and the bike</p></div>");
                            dest=dest.concat(temp);

                        }else{
                            while(i<response.buildingNumber){
                                temp="<div class=\"col-sm-3\"><input type=\"checkbox\" checked name=\"buildingAccess[]\" value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"</div>";
                                dest=dest.concat(temp);
                                temp2="<option value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"</option>";
                                dest2=dest2.concat(temp2);


                                i++;
                            }
                        }
                        document.getElementById('bikeBuildingAccessAdmin').innerHTML = dest;
                        dest2=dest2.concat("</select>");
                        document.getElementById('addBike_firstBuilding').innerHTML = dest2;



                    }
                }
        });


        $.ajax({
                url: 'include/get_users_listing.php',
                type: 'post',
                data: { "company": company},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{

                        i=0;
                        var dest="";
                        if(response.usersNumber==0){
                            temp="<div class=\"col-sm-12 fr\"><p><trong>Pas d'utilitisateurs</strong> définis pour cette société, commencez par en créer un et vous pourrez ensuite luis donner accès à ce vélo </p></div>";
                            temp=temp.concat("<div class=\"col-sm-12 en\"><p><strong>Nos user</strong> defined for that company, please first create one and then you will be able to link that user and the bike</p></div>");
                            temp=temp.concat("<div class=\"col-sm-12 nl\"><p><strong>Nos user</strong> defined for that company, please first create one and then you will be able to link that user and the bike</p></div>");
                            dest=dest.concat(temp);

                        }else{

                            while(i<response.usersNumber){

                                temp="<div class=\"col-sm-3\"><input type=\"checkbox\" checked name=\"userAccess[]\" value=\""+response.user[i].email+"\">"+response.user[i].name+" "+response.user[i].firstName+"</div>";
                                dest=dest.concat(temp);
                                i++;
                            }
                        }

                        document.getElementById('bikeUserAccessAdmin').innerHTML = dest;

                    }
                }
        });
        displayLanguage();
    }

}

function updateAccessAdmin(frame_number, company){
    construct_form_for_bike_access_updateAdmin(frame_number, company);
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



function construct_form_for_billing_status_update(ID){
    $.ajax({
        url: 'include/get_billing_details.php',
        type: 'post',
        data: { "ID": ID},
        success: function(response){
            if (response.response == 'error') {
                console.log(response.message);
            } else{
                $('input[name=widget-updateBillingStatus-form-billingReference]').val(ID);
                $('input[name=widget-updateBillingStatus-form-billingCompany]').val(response.bill.company);
                $('input[name=widget-updateBillingStatus-form-beneficiaryBillingCompany]').val(response.bill.beneficiaryCompany);
                $('input[name=widget-updateBillingStatus-form-type]').val(response.bill.type);
                $('input[name=widget-updateBillingStatus-form-communication]').val(response.bill.communication);
                $('input[name=widget-updateBillingStatus-form-date]').val(response.bill.date.substring(0,10));
                $('input[name=widget-updateBillingStatus-form-amountHTVA]').val(response.bill.amountHTVA);
                $('input[name=widget-updateBillingStatus-form-amountTVAC]').val(response.bill.amountTVAC);
                if(response.bill.amountHTVA == response.bill.amountTVAC){
                    $('input[name=widget-updateBillingStatus-form-VAT]').prop('checked', false);
                }else{
                    $('input[name=widget-updateBillingStatus-form-VAT]').prop('checked', true);
                }


                if(response.bill.sent=="1"){
                    $('input[name=widget-updateBillingStatus-form-sent]').prop( 'checked', true);
                }else{
                    $('input[name=widget-updateBillingStatus-form-sent]').prop( 'checked', false);
                }
                if(response.bill.sentDate){
                    $('input[name=widget-updateBillingStatus-form-sendingDate]').val(response.bill.sentDate.substring(0,10));
                }else{
                    $('input[name=widget-updateBillingStatus-form-sendingDate]').val('');
                }

                if(response.bill.paid=="1"){
                    $('input[name=widget-updateBillingStatus-form-paid]').prop( 'checked', true);
                }else{
                    $('input[name=widget-updateBillingStatus-form-paid]').prop( 'checked', false);
                }
                if(response.bill.paidDate){
                    $('input[name=widget-updateBillingStatus-form-paymentDate]').val(response.bill.paidDate.substring(0,10));
                }else{
                    $('input[name=widget-updateBillingStatus-form-paymentDate]').val('');
                }


                if(response.bill.paidLimitDate){
                    $('input[name=widget-updateBillingStatus-form-datelimite]').val(response.bill.paidLimitDate.substring(0,10));
                }else{
                    $('input[name=widget-updateBillingStatus-form-datelimite]').val('');
                }
                if(response.bill.file != '' ){
                    $('.widget-updateBillingStatus-form-currentFile').attr("href", "factures/"+response.bill.file);
                    $('.widget-updateBillingStatus-form-currentFile').unbind('click');
                }else{
                    $('.widget-updateBillingStatus-form-currentFile').click(function(e) {
                        e.preventDefault();
                        $.notify({
                            message: "No file available for that bill"
                        }, {
                            type: 'danger'
                        });
                    });
                }

                if(response.bill.communicationSentAccounting=="1"){
                    $('#widget-updateBillingStatus-form input[name=accounting]').prop( 'checked', true);
                }else{
                    $('#widget-updateBillingStatus-form input[name=accounting]').prop( 'checked', false);
                }


                $('input[name=widget-updateBillingStatus-form-currentFile]').val(response.bill.file);
                $("#widget-deleteBillingStatus-form input[name=reference]").val(ID);


                var i=0;
                var dest='<table class=\"table table-condensed\"><thead><tr><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Bike</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Comentaire</span><span class=\"en-inline\">Comment</span><span class=\"nl-inline\">Comment</span></th></tr></thead><tbody>';
                while(i<response.billDetailsNumber){
                    var temp="<tr>";
                    temp=temp.concat("<th>"+response.bill.billDetails[i].frameNumber+"</th><th>"+response.bill.billDetails[i].amountHTVA+"</th><th>"+response.bill.billDetails[i].comments+"</th></tr>")
                    i++;
                    dest=dest.concat(temp);
                }
                dest=dest.concat("</tbody><table>");
                document.getElementById('billingDetails').innerHTML=dest;
                displayLanguage();

            }
        }
    })
}


function listPortfolioBikes(){
    $.ajax({
            url: 'include/load_portfolio.php',
            type: 'get',
            data: {"action": "list"},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{
                    var i=0;
                    var dest="";
                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Vélos du catalogue:</h4><h4 class=\"en-inline text-green\">Portfolio bikes:</h4><h4 class=\"nl-inline text-green\">Portfolio bikes:</h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addPortfolioBike\" data-toggle=\"modal\" onclick=\"initializeCreatePortfolioBike()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un vélo</span></a><tbody><thead><tr><th>ID</th><th><span class=\"fr-inline\">Marque</span><span class=\"en-inline\">Brand</span><span class=\"nl-inline\">Brand</span></th><th><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th><span class=\"fr-inline\">Utilisation</span><span class=\"en-inline\">Use</span><span class=\"nl-inline\">Use</span></th><th><span class=\"fr-inline\">Electrique ?</span><span class=\"en-inline\">Electric</span><span class=\"nl-inline\">Electric</span></th><th><span class=\"fr-inline\">Cadre</span><span class=\"en-inline\">Frame</span><span class=\"nl-inline\">Frame</span></th><th><span class=\"fr-inline\">Prix</span><span class=\"en-inline\">Price</span><span class=\"nl-inline\">Price</span></th><th></th></tr></thead>";
                    dest=dest.concat(temp);

                    while(i<response.bikeNumber){
                        var temp="<tr><th>"+response.bike[i].ID+"</th><th>"+response.bike[i].brand+"</th><th>"+response.bike[i].model+"</th><th>"+response.bike[i].utilisation+"</th><th>"+response.bike[i].electric+"</th><th>"+response.bike[i].frameType+"</th><th>"+response.bike[i].price+"</th><th><a href=\"#\" class=\"text-green updatePortfolioClick\" onclick=\"initializeUpdatePortfolioBike('"+response.bike[i].ID+"')\" data-target=\"#updatePortfolioBike\" data-toggle=\"modal\">Mettre à jour </a></th></tr>";
                        dest=dest.concat(temp);
                        i++;
                    }
                    document.getElementById('portfolioBikesListing').innerHTML=dest;
                    document.getElementById('counterBikePortfolio').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bikeNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.bikeNumber+"</span>";

                    displayLanguage();

                }

            }
    })
}

function initializeUpdatePortfolioBike(ID){
    $.ajax({
            url: 'include/load_portfolio.php',
            type: 'get',
            data: { "action": "retrieve", "ID": ID},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{
                    $('#widget-updateCatalog-form input[name=ID]').val(response.ID);

                    $('#widget-deletePortfolioBike-form [name=id]').val(response.ID);

                    console.log(response.brand);
                    $('#widget-updateCatalog-form select[name=brand]').val(response.brand);
                    $('#widget-updateCatalog-form input[name=model]').val(response.model);
                    $('#widget-updateCatalog-form select[name=frame]').val(response.frameType);
                    $('#widget-updateCatalog-form select[name=utilisation]').val(response.utilisation);
                    $('#widget-updateCatalog-form select[name=electric]').val(response.electric);

                    $('#widget-updateCatalog-form input[name=electric]').val(response.electric);
                    $('#widget-updateCatalog-form input[name=buyPrice]').val(response.buyingPrice);
                    $('#widget-updateCatalog-form input[name=price]').val(response.portfolioPrice);

                    $('#widget-updateCatalog-form input[name=stock]').val(response.stock);
                    $('#widget-updateCatalog-form input[name=link]').val(response.url);

                    document.getElementsByClassName("bikeCatalogImage")[0].src="images_bikes/"+response.brand.toLowerCase()+"_"+response.model.toLowerCase().replace(/ /g, '-')+"_"+response.frameType.toLowerCase()+".jpg";
                    document.getElementsByClassName("bikeCatalogImageMini")[0].src="images_bikes/"+response.brand.toLowerCase()+"_"+response.model.toLowerCase().replace(/ /g, '-')+"_"+response.frameType.toLowerCase()+"_mini.jpg";
                    $('#widget-updateCatalog-form input[name=file]').val('');
                    $('#widget-updateCatalog-form input[name=fileMini]').val('');

                    if(response.display=='Y'){
                        $('#widget-updateCatalog-form input[name=display]').prop("checked", true);
                    }else{
                        $('#widget-updateCatalog-form input[name=display]').prop("checked", false);
                    }
                }

            }
    })

}

function initializeCreatePortfolioBike(){
    document.getElementById('widget-addCatalog-form').reset();
}


function update_deposit_form(){
    var hour=document.getElementById('search-bikes-form-intake-hour').value;
    var date=document.getElementById('search-bikes-form-day').value;
    var Date1=date.split('-');
    var day=Date1[0];
    var month=(Date1[1]-1);
    var Hours=hour.split('h');

    var hour=Hours[0];
    var minute=Hours[1];


    var dateStart = new Date(new Date().getFullYear(), month, day, hour, minute);


    var dateTemp = new Date(new Date().getFullYear(), month, day, hour, minute);

    loadClientConditions()
    .done(function(response){
        bookingLength=parseInt(response.clientConditions.bookingLength);

        var hour=document.getElementById('search-bikes-form-intake-hour').value;
        var Hours1=hour.split('h');
        var hour=Hours1[0];
        var minute=Hours1[1];
        var date=document.getElementById('search-bikes-form-day').value;
        var Date1=date.split('-');
        var day=Date1[0];
        var month=(Date1[1]-1);


        var dateEnd = new Date(new Date().getFullYear(), month, day, hour, minute);
        dateEnd.setHours(dateEnd.getHours()+bookingLength);

        var currentDate = new Date(new Date().getFullYear(), month, day, hour, minute);



        var numberOfDays=Math.round((dateEnd-currentDate)/(1000*60*60*24));


        // 1st step: days and month fileds

        var hour=document.getElementById('search-bikes-form-intake-hour').value;
        var date1=document.getElementById('search-bikes-form-day').value;
        var Date1=date.split('-');
        var day=Date1[0];
        var month=(Date1[1]-1);
        var Hours1=hour.split('h');
        var hour=Hours1[0];
        var minute=Hours1[1];

        var daysFR=['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        var daysEN=['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var daysNL=['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'];
        var monthFR=['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        var monthEN=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var monthNL=['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];


        var tempDate = new Date(new Date().getFullYear(), month, day, hour, minute);
        var i=0;
        var j=0;
        var dest ="<select id=\"search-bikes-form-day-deposit\" name=\"search-bikes-form-day-deposit\"  class=\"form-control\">";


        while(i<=numberOfDays){
            if((tempDate.getDay()=="1" && parseInt(response.clientConditions.mondayDeposit)) || (tempDate.getDay()=="2" && parseInt(response.clientConditions.tuesdayDeposit)) || (tempDate.getDay()=="3" && parseInt(response.clientConditions.wednesdayDeposit)) || (tempDate.getDay()=="4" && parseInt(response.clientConditions.thursdayDeposit)) || (tempDate.getDay()=="5" && parseInt(response.clientConditions.fridayDeposit)) || (tempDate.getDay()=="6" && parseInt(response.clientConditions.saturdayDeposit)) || (tempDate.getDay()=="0" && parseInt(response.clientConditions.sundayDeposit))){
                var dayFR = daysFR[tempDate.getDay()];
                var dayEN = daysEN[tempDate.getDay()];
                var dayNL = daysNL[tempDate.getDay()];
                var bookingDay="<option value=\""+tempDate.getDate()+"-"+(tempDate.getMonth()+1)+"-"+tempDate.getFullYear()+"\" class=\"form-control fr\">"+dayFR+" "+tempDate.getDate()+" "+monthFR[tempDate.getMonth()]+"</option>";
                dest = dest.concat(bookingDay);
            }
            tempDate.setDate(tempDate.getDate()+1);
            i++;

        }
        var bookingDay="</select>";
        dest = dest.concat(bookingDay);
        document.getElementById('booking_day_form_deposit').innerHTML=dest;

        document.getElementById('search-bikes-form-day-deposit').addEventListener('change', function () { update_deposit_hour_form()}, false);

        update_deposit_hour_form();

    });



}

function update_intake_hour_form(){
    loadClientConditions()
    .done(function(response){
        var date1=document.getElementById('search-bikes-form-day').value;
        var Date1=date1.split('-');
        var day=Date1[0];
        var currentDate=new Date();

        if(currentDate.getDate()==day){
            var hours=currentDate.getHours();
            var minutes=currentDate.getMinutes();

            var m = (((minutes + 7.5)/15 | 0) * 15) % 60;
            var h = ((((minutes/105) + .5) | 0) + hours) % 24;

            var dateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), h, m);

        }
        else{
            var m = 0;
            var h = response.clientConditions.hourStartIntakeBooking;
            var dateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), h, m);
        }

        var dest="";

        while(dateTemp.getHours()<response.clientConditions.hourEndIntakeBooking){
            if(dateTemp.getMinutes()=="0"){
                var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
            }else{
                var hourString=dateTemp.getHours()+"h"+dateTemp.getMinutes();
            }

            dateTemp.setMinutes(dateTemp.getMinutes()+ parseInt(15));

            var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
            dest=dest.concat(tempString);
        }

        var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
        var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
        dest=dest.concat(tempString);


        document.getElementById('search-bikes-form-intake-hour').innerHTML=dest;

        update_deposit_form();
    });
}


function update_deposit_hour_form(){
    var hour=document.getElementById('search-bikes-form-intake-hour').value;
    var date1=document.getElementById('search-bikes-form-day').value;
    var Date1=date1.split('-');
    var day=Date1[0];
    var month=Date1[1];


    var Hours1=hour.split('h');

    var hour=Hours1[0];
    var minute=Hours1[1];
    var dateStart = new Date(new Date().getFullYear(), month-1, day, hour, minute);

    loadClientConditions()
    .done(function(response){
        bookingLength=parseInt(response.clientConditions.bookingLength);
        var hour=document.getElementById('search-bikes-form-intake-hour').value;
        var date1=document.getElementById('search-bikes-form-day').value;
        var Date1=date1.split('-');
        var day=Date1[0];
        var month=Date1[1];
        var Hours1=hour.split('h');
        var hour=Hours1[0];
        var minute=Hours1[1];

        var dateEnd = new Date(new Date().getFullYear(), month-1, day, hour, minute);
        dateEnd.setHours(dateEnd.getHours()+bookingLength);

        var date1=document.getElementById('search-bikes-form-day-deposit').value;
        var Date1=date1.split('-');
        var day=Date1[0];
        var month=Date1[1];

        if( dateStart.getDate()!=day || (parseInt(hour) < parseInt(response.clientConditions.hourStartDepositBooking))){
            var currentDepositDate = new Date(new Date().getFullYear(), month-1, day, response.clientConditions.hourStartDepositBooking, '00');
            var dateTemp2 = new Date(new Date().getFullYear(), month-1, day, response.clientConditions.hourStartDepositBooking, '00');
        }else{
            var currentDepositDate = new Date(new Date().getFullYear(), month-1, day, hour, minute);
            var dateTemp2 = new Date(new Date().getFullYear(), month-1, day, hour, minute);
        }

        var Hours=[];
        while(dateTemp2<=dateEnd){
            hours=dateTemp2.getHours();
            if(dateTemp2.getMinutes()=="0"){
                minutes="00";
            }else{
                minutes=dateTemp2.getMinutes();
            }
            if(hours>=response.clientConditions.hourStartDepositBooking && hours<response.clientConditions.hourEndDepositBooking && dateTemp2.getDay()==currentDepositDate.getDay()){
                var hourString=hours+'h'+minutes;
                Hours.push(hourString);
            }
            dateTemp2.setMinutes(dateTemp2.getMinutes()+15);
        }

        var hourBeforeLast=(response.clientConditions.hourEndDepositBooking-1)+'h45';
        var hourInArray=Hours[Hours.length-1];

        if(hourBeforeLast==hourInArray){
            Hours.push(response.clientConditions.hourEndDepositBooking+'h00');
        }


        var dest ="<select name=\"search-bikes-form-deposit-hour\" id=\"search-bikes-form-deposit-hour\" class=\"form-control\">";

        var i=0;
        while(i<Hours.length){
            var bookingHour="<option value=\""+Hours[i]+"\" class=\"form-control\">"+Hours[i]+"</option>";
            dest = dest.concat(bookingHour);
            i++;
        }
        var bookingHour="</select>";
        document.getElementById('booking_hour_form_deposit').innerHTML=dest;
    });
}


function fillBikeDetails(element)
{
    var frameNumber=element;
    $.ajax({
            url: 'include/get_bike_details.php',
            type: 'post',
            data: { "frameNumber": frameNumber},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{
                    document.getElementsByClassName("bikeReference")[0].innerHTML=frameNumber;
                    document.getElementsByClassName("bikeModel")[0].innerHTML=response.model;
                    document.getElementsByClassName("frameReference")[0].innerHTML=response.frameReference;
                    document.getElementsByClassName("contractType")[0].innerHTML=response.contractType;
                    document.getElementsByClassName("startDateContract")[0].innerHTML=response.contractStart;
                    document.getElementsByClassName("endDateContract")[0].innerHTML=response.contractEnd;
                    document.getElementsByClassName("bikeImage")[0].src="images_bikes/"+frameNumber+"_mini.jpg";

                }

                }
            })

    $.ajax({
            url: 'include/action_bike_management.php',
            type: 'post',
            data: { "readActionBike-action": "read", "readActionBike-bikeNumber": frameNumber, "readActionBike-user": "<?php echo $user; ?>"},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{

                    var i=0;
                    var dest="<table class=\"table table-condensed\"><tbody><thead><tr><th><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th><span class=\"fr-inline\">Description</span><span class=\"en-inline\">Description</span><span class=\"nl-inline\">Description</span></th></tr></thead> ";
                    while(i<response.actionNumber){
                        if(response.action[i].public=="1"){
                            var temp="<tr><td>"+response.action[i].date.substring(0,10)+"</td><td>"+response.action[i].description+"</td></tr>";
                            dest=dest.concat(temp);
                        }
                        i++;

                    }
                    dest=dest.concat("</tbody></table>");
                    $('#action_bike_log_user').html(dest);
                    displayLanguage();

                }

            }
    })
}

function fillReservationDetails(element)
{
    var reservationID=element;
    $.ajax({
            url: 'include/get_reservation_details.php',
            type: 'post',
            data: { "reservationID": reservationID},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{
                    document.getElementsByClassName("reservationNumber")[0].innerHTML=reservationID;
                    document.getElementsByClassName("reservationStartDate")[0].innerHTML=response.reservationStartDate;
                    document.getElementsByClassName("reservationEndDate")[0].innerHTML=response.reservationEndDate;
                    document.getElementsByClassName("reservationStartBuilding")[0].innerHTML=response.reservationStartBuilding;
                    document.getElementsByClassName("reservationEndBuilding")[0].innerHTML=response.reservationEndBuilding;
                    document.getElementsByClassName("reservationBikeNumber")[0].innerHTML=response.reservationBikeNumber;
                    document.getElementsByClassName("reservationEmail")[0].innerHTML=response.reservationEmail;
                    document.getElementsByClassName("reservationBikeImage")[0].src="images_bikes/"+response.reservationBikeNumber+"_mini.jpg";

                    //document.getElementById('updateReservationdiv').innerHTML="<a class=\"button small green button-3d rounded icon-right\" data-target=\"#updateReservation\" onclick=\"initializeUpdateReservation('"+reservationID+"')\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Modifier</span><span class=\"en-inline\">Update</span></a>";
                    document.getElementById('deleteReservationdiv').innerHTML="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#deleteReservation\" onclick=\"initializeDeleteReservation('"+reservationID+"')\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Supprimer</span><span class=\"en-inline\">Delete</span></a>";

                    displayLanguage();
                }

                }
            })

}

</script>
<?php
if($connected){

    include 'include/connexion.php';
    $sql = "select aa.EMAIL, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY, bb.TYPE from customer_referential aa, customer_bike_access bb where aa.EMAIL='$user' and aa.EMAIL=bb.EMAIL LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['TYPE']="partage"){
        $company=true;
    }
    else{
        $company=false;
    }
    $conn->close();
    ?>

    <script type="text/javascript">
    var connected="<?php echo $connected; ?>";

    var langueJava = "<?php echo $_SESSION['langue']; ?>";



    function loadClientConditions(){
            var email= "<?php echo $user; ?>";
            return $.ajax({
                url: 'include/load_client_conditions.php',
                type: 'post',
                data: { "email": email},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    }
                }
                })
    }

    // Goal of this function is to delete the block with result of research
    function hideResearch(){
        document.getElementById('velos').innerHTML = "";
        document.getElementById("velos").style.display = "none";
        document.getElementById("travel_information").style.display = "none";
        getHistoricBookings();

    }
    // Goal of this function is to construct the reasearch fields
    function constructSearchForm(daysToDisplay, bookingLength, administrator, assistance, hourStartIntakeBooking, hourEndIntakeBooking, hourStartDepositBooking, hourEndDepositBooking, mondayIntake, tuesdayIntake, wednesdayIntake, thursdayIntake, fridayIntake, saturdayIntake, sundayIntake, mondayDeposit, tuesdayDeposit, wednesdayDeposit, thursdayDeposit, fridayDeposit, saturdayDeposit, sundayDeposit, maxBookingsPerYear, maxBookingsPerMonth){
        if(assistance=="Y"){
            document.getElementById('assistanceSpan').innerHTML="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#assistance\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Assistance et Entretien</span><span class=\"en-inline\">Assistance and Maintenance</span><span class=\"nl-inline\">Hulp en Onderhoud</span></a>"
        }
        // 1st step: days and month fileds

        var daysFR=['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        var daysEN=['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var daysNL=['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'];
        var monthFR=['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        var monthEN=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var monthNL=['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];


        var startDate = new Date();
        var i=0;
        var j=0;
        var dest ="<select id=\"search-bikes-form-day\" name=\"search-bikes-form-day\"  class=\"form-control\">";
        var dest2 ="<select id=\"search-bikes-form-day-deposit\" name=\"search-bikes-form-day-deposit\"  class=\"form-control\">";

        var tempDate = new Date();
        var tempDate2=tempDate;
        bookingLength=parseInt(bookingLength);
        while(i<=daysToDisplay){
            if(tempDate.getHours()>=hourEndDepositBooking){
                tempDate.setHours(hourStartIntakeBooking);
                tempDate.setMinutes(0);
                tempDate.setDate(tempDate.getDate()+1);
            }
            var dayFR = daysFR[tempDate.getDay()];
            var dayEN = daysEN[tempDate.getDay()];
            var dayNL = daysNL[tempDate.getDay()];
            if((tempDate.getDay()=="1" && parseInt(mondayIntake)) || (tempDate.getDay()=="2" && parseInt(tuesdayIntake)) || (tempDate.getDay()=="3" && parseInt(wednesdayIntake)) || (tempDate.getDay()=="4" && parseInt(thursdayIntake)) || (tempDate.getDay()=="5" && parseInt(fridayIntake)) || (tempDate.getDay()=="6" && parseInt(saturdayIntake)) || (tempDate.getDay()=="0" && parseInt(sundayIntake))){
                var bookingDay="<option value=\""+tempDate.getDate()+"-"+(tempDate.getMonth()+1)+"-"+tempDate.getFullYear()+"\" class=\"form-control fr\">"+dayFR+" "+tempDate.getDate()+" "+monthFR[tempDate.getMonth()]+"</option><option value=\""+tempDate.getDate()+"-"+(tempDate.getMonth()+1)+"-"+tempDate.getFullYear()+"\" class=\"form-control en\">"+dayEN+" "+tempDate.getDate()+" "+monthEN[tempDate.getMonth()]+"</option><option value=\""+tempDate.getDate()+"-"+(tempDate.getMonth()+1)+"-"+tempDate.getFullYear()+"\" class=\"form-control nl\">"+dayNL+" "+tempDate.getDate()+" "+monthNL[tempDate.getMonth()]+"</option>";

                dest = dest.concat(bookingDay);
            }
            else{

            }

            i++;
            tempDate.setDate(tempDate.getDate()+1);
        }
        var bookingDay="</select>";
        dest = dest.concat(bookingDay);
        document.getElementById('booking_day_form').innerHTML=dest;

        document.getElementById('search-bikes-form-day').addEventListener('change', function () { update_intake_hour_form()}, false);


        var currentDate=new Date();

        var hours=currentDate.getHours();
        var minutes=currentDate.getMinutes();

        var m = (((minutes + 7.5)/15 | 0) * 15) % 60;
        var h = ((((minutes/105) + .5) | 0) + hours) % 24;

        var dateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), h, m);



        var dest="";
        if(dateTemp.getHours()>=hourEndDepositBooking){
            dateTemp.setHours(hourStartIntakeBooking);
            dateTemp.setMinutes(0);
            dateTemp.setDate(dateTemp.getDate()+1);
        }
        while(dateTemp.getHours()<hourEndIntakeBooking){
            if(dateTemp.getMinutes()=="0"){
                var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
            }else{
                var hourString=dateTemp.getHours()+"h"+dateTemp.getMinutes();
            }

            dateTemp.setMinutes(dateTemp.getMinutes()+ parseInt(15));


            var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
            dest=dest.concat(tempString);
        }

        var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
        var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
        dest=dest.concat(tempString);


        document.getElementById('search-bikes-form-intake-hour').innerHTML=dest;



        // 2nd step: intake and deposit buildings
        var langue= "<?php echo $_SESSION['langue']; ?>";
        var email="<?php echo $user; ?>";
        var i=0;
        $.ajax({
            url: 'include/booking_building_form.php',
            type: 'post',
            data: { "email": email},
            success: function(response) {
                if(response.buildingNumber=="1"){
                    var dest="";
                    var building_fr=response.building[1].fr;
                    var building_en=response.building[1].en;
                    var building_nl=response.building[1].nl;

                    var tempBuilding="<select id=\"search-bikes-form-intake-building\" name=\"search-bikes-form-intake-building\" class=\"form-control hidden\"><option value=\""+response.building[1].building_code+"\" class=\"fr\" selected=\"selected\">"+building_fr+"</option><option value=\""+response.building[1].building_code+"\" class=\"nl\" selected=\"selected\">"+building_nl+"</option><option value=\""+response.building[1].building_code+"\" class=\"en\" selected=\"selected\">"+building_en+"</option></select><select id=\"search-bikes-form-deposit-building\" name=\"search-bikes-form-deposit-building\" class=\"form-control hidden\"><option value=\""+response.building[1].building_code+"\" class=\"fr\" selected=\"selected\">"+building_fr+"</option><option value=\""+response.building[1].building_code+"\" class=\"nl\" selected=\"selected\">"+building_nl+"</option><option value=\""+response.building[1].building_code+"\" class=\"en\" selected=\"selected\">"+building_en+"</option></select>";
                    dest=tempBuilding;
                } else{
                    var dest="";
                    var tempBuilding="<label for=\"search-bikes-form-intake-building\" class=\" fr\">Où voulez-vous prendre le vélo?</label><label for=\"search-bikes-form-intake-building\" class=\"en\">Where is your departure ?</label><label for=\"search-bikes-form-intake-building\" class=\"nl\">Where is your departure ?</label><select id=\"search-bikes-form-intake-building\" name=\"search-bikes-form-intake-building\" class=\"form-control\">";
                    dest = dest.concat(tempBuilding);
                    while (i < response.buildingNumber){
                        i++;
                        var building_code=response.building[i].building_code;
                        var building_fr=response.building[i].fr;
                        var building_en=response.building[i].en;
                        var building_nl=response.building[i].nl;

                        var tempBuilding="<option value=\""+building_code+"\" class=\"fr\">"+building_fr+"</option><option value=\""+building_code+"\" class=\"en\">"+building_en+"</option><option value=\""+building_code+"\" class=\"nl\">"+building_nl+"</option>";
                        dest = dest.concat(tempBuilding);
                    }
                    var tempBuilding="</select>";
                    dest = dest.concat(tempBuilding);
                    document.getElementById('start_building_form').innerHTML=dest;
                    document.getElementById('start_building_form').innerHTML=dest;

                    var j=0;
                    var dest="";
                    var tempBuilding="<label for=\"search-bikes-form-deposit-building\" class=\"fr\">Où voulez-vous rendre le vélo?</label><label for=\"search-bikes-form-deposit-building\" class=\"en\">Where is your arrival ?</label><label for=\"search-bikes-form-deposit-building\" class=\"nl\">Where is your arrival ?</label><select id=\"search-bikes-form-deposit-building\" name=\"search-bikes-form-deposit-building\" class=\"form-control\">";
                    dest = dest.concat(tempBuilding);

                    while (j < response.buildingNumber){
                        j++;
                        var building_code=response.building[j].building_code;
                        var building_fr=response.building[j].fr;
                        var building_en=response.building[j].en;
                        var building_nl=response.building[j].nl;

                        var tempBuilding="<option value=\""+building_code+"\" class=\"fr\">"+building_fr+"</option><option value=\""+building_code+"\" class=\"en\">"+building_en+"</option><option value=\""+building_code+"\" class=\"nl\">"+building_nl+"</option>";
                        dest = dest.concat(tempBuilding);
                        var tempBuilding="</select>";

                    }
                    dest = dest.concat(tempBuilding);
                }
                document.getElementById('deposit_building_form').innerHTML=dest;
                document.getElementById('search-bikes-form-maxBookingPerYear').value=maxBookingsPerYear;
                document.getElementById('search-bikes-form-maxBookingPerMonth').value=maxBookingsPerMonth;
                displayLanguage();
            }
        });


        update_deposit_form();
    }

    function showBooking(bookingID){
        var dest="";
        var langue= "<?php echo $_SESSION['langue']; ?>";

        $.ajax({
            url: 'include/get_future_booking.php',
            type: 'post',
            data: { "bookingID": bookingID},
            success: function(response){
                if(response.response=="success"){
                    var name = response.clientBefore.name;
                    var surname = response.clientBefore.surname;
                    var phone = response.clientBefore.phone;
                    var mail = response.clientBefore.mail;
                    var depositDay = response.clientBefore.depositDay;
                    var depositHour = response.clientBefore.depositHour;
                    var code=response.booking.code
                    var ID=response.booking.ID


                    if(langue=="nl"){
                        var dest="<li class=\"nl\">Naam: "+name+" "+surname+"</li><li class=\"nl\">Telefoonnummer:"+phone+"</li><li class=\"nl\">Mail: "+mail+"</li><li class=\"nl\">Stort fiets op "+depositDay+" om "+depositHour+"</li>";
                    } else if (langue == "en"){
                        var dest="<li class=\"en\">Name: "+name+" "+surname+"</li><li class=\"en\">Phone Number:"+phone+"</li><li class=\"en\">Mail: "+mail+"</li><li class=\"en\">Returns bike on" +depositDay+" at "+depositHour+"</li>";
                    } else {
                        var dest="<li class=\"fr\">Nom et prénom: "+name+" "+surname+"</li><li class=\"fr\">Numéro de téléphone: "+phone+"</li><li class=\"fr\">Adresse mail: "+mail+"</li><li class=\"fr\">Dépose le vélo le "+depositDay+" à "+depositHour+"</li>";
                    }
                    document.getElementById('futureBookingBefore').innerHTML = dest;

                    temp="<li class=\"fr\">Numéro de réservation : "+ID+"</li>";
                    dest=temp;
                    if(code){
                        if(code.length==3){
                            code="0"+code;
                        }else if(code.length==2){
                            code="00"+code;
                        }else if(code.length==1){
                            code="000"+length;
                        }
                        temp="<li class=\"fr\">Code : "+code+"</li>";
                        dest=dest.concat(temp);
                    }
                    dest=dest.concat("<li class=\"fr\">Début : "+response.booking.intakeDay+"-"+response.booking.intakeHour+" au bâtiment "+response.booking.buildingStart+"</li>")
                    dest=dest.concat("<li class=\"fr\">Fin : "+response.booking.depositDay+"-"+response.booking.depositHour+" au bâtiment "+response.booking.buildingEnd+"</li>")
                    document.getElementById('bookingInformation').innerHTML=dest;


                    var name = response.clientAfter.name;
                    var surname = response.clientAfter.surname;
                    var phone = response.clientAfter.phone;
                    var mail = response.clientAfter.mail;
                    var intakeDay = response.clientAfter.intakeDay;
                    var intakeHour = response.clientAfter.intakeHour;

                    if(typeof response.clientAfter.name == 'undefined' || response.clientAfter.name==''){
                        if(langue=="nl"){
                            var dest="Niemand.";
                        }
                        else if (langue=="en"){
                                var dest="Nobody.";
                        } else{
                                var dest="Personne.";
                        }
                    }
                    else{
                        if(langue=="nl"){
                            var dest="<li>Naam: "+name+" "+surname+"</li><li>Telefoonnummer:"+phone+"</li><li>Mail: "+mail+"</li><li>Neem de fiets mee"+intakeDay+" om "+intakeHour+"</li>";
                        }
                        else if (langue=="en"){
                                var dest="<li>Name: "+name+" "+surname+"</li><li>Phone Number:"+phone+"</li><li>Mail: "+mail+"</li><li>Will take bike on"+intakeDay+" at "+intakeHour+"</li>";
                        } else{
                                var dest="<li>Nom et prénom: "+name+" "+surname+"</li><li>Numéro de téléphone:"+phone+"</li><li>Adresse mail: "+mail+"</li><li>Reprendra le vélo le "+intakeDay+" à "+intakeHour+"</li>";
                        }
                    }

                    document.getElementById('futureBookingAfter').innerHTML = dest;
                   $('#futureBooking').modal('toggle');

                }else{
                    console.log(response.message);
                }

            }
        });



    }

    function cancelBooking(bookingID){
        var dest="";
        var langue= "<?php echo $_SESSION['langue']; ?>";

        $.ajax({
            url: 'include/cancel_booking.php',
            type: 'post',
            data: { "bookingID": bookingID},
            success: function(text){

                if (text.response == 'error') {
                    $.notify({
                        message: text.message
                    }, {
                        type: 'danger'
                    });
                }
                else if (text.response == 'success'){
                    $.notify({
                        message: text.message
                    }, {
                        type: text.response
                    });
                    getHistoricBookings();
                }

            }
        });
    }

    function get_bikes_listing() {
        var email= "<?php echo $user; ?>";
        $.ajax({
            url: 'include/get_bikes_listing.php',
            type: 'post',
            data: { "email": email},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="";
                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Vos vélos:</h4><h4 class=\"en-inline text-green\">Your Bikes:</h4><h4 class=\"nl-inline text-green\">Jouw fietsen:</h4><tbody><thead><tr><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fiet</span></th><th><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th><span class=\"fr-inline\">Type de contrat</span><span class=\"en-inline\">Contract type</span><span class=\"nl-inline\">Contract type</span></th><th><span class=\"fr-inline\">Début du contrat</span><span class=\"en-inline\">Contract start</span><span class=\"nl-inline\">Contract start</span></th><th><span class=\"fr-inline\">Fin du contrat</span><span class=\"en-inline\">Contract End</span><span class=\"nl-inline\">Contract End</span></th><th><span class=\"fr-inline\">Etat du vélo</span><span class=\"en-inline\">Bike status</span><span class=\"nl-inline\">Bike status</span></th><th></th></tr></thead>";
                    dest=dest.concat(temp);

                    var dest2="";
                    temp2="<li><a href=\"#\" onclick=\"bikeFilter('Sélection de vélo')\">Tous les vélos</a></li><li class=\"divider\"></li>";
                    dest2=dest2.concat(temp2);


                    while (i < response.bikeNumber){

                        if(response.bike[i].contractStart){
                            var contractStart=response.bike[i].contractStart.substr(0,10);
                        }else{
                            var contractStart="N/A";
                        }
                        if(response.bike[i].contractEnd){
                            var contractEnd=response.bike[i].contractEnd.substr(0,10);
                        }else{
                            var contractEnd="N/A";
                        }



                        if(response.bike[i].status==null || response.bike[i].status=="KO"){
                            status="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                        }else{
                            status="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                        }


                        var temp="<tr><td><a  data-target=\"#bikeDetailsFull\" name=\""+response.bike[i].frameNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillBikeDetails(this.name)\">"+response.bike[i].frameNumber+"</a></td><td>"+response.bike[i].model+"</td><td>"+response.bike[i].contractType+"</td><td>"+contractStart+"</td><td>"+contractEnd+"</td><td>"+status+"</td><td><ins><a class=\"text-green updateBikeStatus\" data-target=\"#updateBikeStatus\" name=\""+response.bike[i].frameNumber+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
                        dest=dest.concat(temp);

                        var temp2="<li><a href=\"#\" onclick=\"bikeFilter('"+response.bike[i].frameNumber+"')\">"+response.bike[i].frameNumber+"</a></li>";
                        dest2=dest2.concat(temp2);

                        i++;

                    }
                    var temp="</tobdy></table>";
                    dest=dest.concat(temp);
                    document.getElementById('bikeDetails').innerHTML = dest;
                    document.getElementsByClassName('bikeSelection')[0].innerHTML=dest2;

                    document.getElementById('counterBike').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bikeNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.bikeNumber+"</span>";
                    displayLanguage();

                    var classname = document.getElementsByClassName('updateBikeStatus');
                    for (var i = 0; i < classname.length; i++) {
                        classname[i].addEventListener('click', function() {construct_form_for_bike_status_update(this.name)}, false);
                    }



                }
            }
        })
    }
    function list_bikes_admin() {
        var email= "<?php echo $user; ?>";
        $.ajax({
            url: 'include/get_bikes_listing.php',
            type: 'post',
            data: { "email": email, "admin": "Y"},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="";
                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Vélos:</h4><br/><a class=\"button small green button-3d rounded icon-right addBikeAdmin\" data-target=\"#bikeManagement\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un vélo</span></a><br/><h4 class=\"en-inline text-green\">Bikes:</h4><h4 class=\"nl-inline text-green\">Fietsen:</h4><tbody><thead><tr><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fiet</span></th><th><span class=\"fr-inline\">Marque - Modèle</span><span class=\"en-inline\">Brand - Model</span><span class=\"nl-inline\">Brand - Model</span></th><th><span class=\"fr-inline\">Type de contrat</span><span class=\"en-inline\">Contract type</span><span class=\"nl-inline\">Contract type</span></th><th><span class=\"fr-inline\">Début contrat</span><span class=\"en-inline\">Contract Start</span><span class=\"nl-inline\">Contract Start</span></th><th><span class=\"fr-inline\">Fin contrat</span><span class=\"en-inline\">Contract End</span><span class=\"nl-inline\">Contract End</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th>Facturation</th><th><span class=\"fr-inline\">Etat du vélo</span><span class=\"en-inline\">Bike status</span><span class=\"nl-inline\">Bike status</span></th><th>Assurance ?</th><th></th></tr></thead>";
                    dest=dest.concat(temp);

                    while (i < response.bikeNumber){


                        if(response.bike[i].automatic_billing==null || response.bike[i].automatic_billing=="N"){
                            automatic_billing="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                        }else{
                            automatic_billing="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                        }

                        if(response.bike[i].status==null || response.bike[i].status=="KO"){
                            status="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                        }else{
                            status="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                        }


                        if(response.bike[i].contractStart==null && (response.bike[i].company!="KAMEO" && response.bike[i].company != 'KAMEO VELOS TEST')){
                            start="<span class=\"text-red\">N/A</span>";
                        }else if(response.bike[i].contractStart!=null && (response.bike[i].company!="KAMEO" && response.bike[i].company != 'KAMEO VELOS TEST')){
                            start="<span class=\"text-green\">"+response.bike[i].contractStart.substr(0,10)+"</span>";
                        }else if(response.bike[i].contractStart==null && (response.bike[i].company=="KAMEO" || response.bike[i].company == 'KAMEO VELOS TEST')){
                            start="<span class=\"text-green\">N/A</span>";
                        }else if(response.bike[i].contractStart!=null && (response.bike[i].company=="KAMEO" || response.bike[i].company == 'KAMEO VELOS TEST')){
                            start="<span class=\"text-red\">"+response.bike[i].contractStart.substr(0,10)+"</span>";
                        }else{
                            start="<span class=\"text-red\">ERROR</span>";
                        }



                        if(response.bike[i].contractEnd==null && (response.bike[i].company!="KAMEO" && response.bike[i].company != 'KAMEO VELOS TEST')){
                            end="<span class=\"text-red\">N/A</span>";
                        }else if(response.bike[i].contractEnd!=null && (response.bike[i].company!="KAMEO" && response.bike[i].company != 'KAMEO VELOS TEST')){
                            end="<span class=\"text-green\">"+response.bike[i].contractEnd.substr(0,10)+"</span>";
                        }else if(response.bike[i].contractEnd==null && (response.bike[i].company=="KAMEO" || response.bike[i].company == 'KAMEO VELOS TEST')){
                            end="<span class=\"text-green\">N/A</span>";
                        }else if(response.bike[i].contractEnd!=null && (response.bike[i].company=="KAMEO" || response.bike[i].company == 'KAMEO VELOS TEST')){
                            end="<span class=\"text-red\">"+response.bike[i].contractEnd.substr(0,10)+"</span>";
                        }else{
                            start="<span class=\"text-red\">ERROR</span>";
                        }

                        if(response.bike[i].brand==null){
                            var brandAndModel="<span class=\"text-red\">N/A</span>";
                        }else{
                            var brandAndModel="<span class=\"\">"+response.bike[i].brand+" - "+response.bike[i].modelBike+" - "+response.bike[i].frameType+"</span>";
                        }
                        if(response.bike[i].insurance=="Y"){
                            insurance="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                        }else{
                            insurance="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                        }


                        if((response.bike[i].leasingPrice==null || response.bike[i].leasingPrice==0) && (response.bike[i].contractType== 'renting' || response.bike[i].contractType=='leasing') && response.bike[i].billingType != 'paid'){
                            var leasingPrice="<span class=\"text-red\">0</span>";
                        }else if((response.bike[i].leasingPrice!=null && response.bike[i].leasingPrice!=0) && (response.bike[i].contractType== 'renting' || response.bike[i].contractType=='leasing')){
                            var leasingPrice="<span class=\"text-green\">"+response.bike[i].leasingPrice+"</span>";
                        }else if((response.bike[i].leasingPrice!=null && response.bike[i].leasingPrice!=0) && (response.bike[i].contractType== 'stock' || response.bike[i].contractType=='test')){
                            var leasingPrice="<span class=\"text-red\">"+response.bike[i].leasingPrice+"</span>";
                        }else if((response.bike[i].leasingPrice==null || response.bike[i].leasingPrice==0) && (response.bike[i].contractType== 'stock' || response.bike[i].contractType=='test' || response.bike[i].billingType=='paid')){
                            var leasingPrice="<span class=\"text-green\">0</span>";
                        }else{
                            var leasingPrice="<span class=\"text-red\">ERROR</span>";
                        }


                        if((response.bike[i].contractType=="stock" && response.bike[i].company != 'KAMEO') || ((response.bike[i].contractType=="leasing" || response.bike[i].contractType=="renting") && response.bike[i].company=="KAMEO")){
                            var contractType="<span class=\"text-red\">"+response.bike[i].contractType+"</span>";
                        }else{
                            var contractType="<span class=\"text-green\">"+response.bike[i].contractType+"</span>";
                        }

                        var temp="<tr><td>"+response.bike[i].company+"</td><td><a  data-target=\"#bikeManagement\" name=\""+response.bike[i].frameNumber+"\" data-toggle=\"modal\" class=\"retrieveBikeAdmin\" href=\"#\">"+response.bike[i].frameNumber+"</a></td><td>"+brandAndModel+"</td><td>"+contractType+"</td><td>"+start+"</td><td>"+end+"</td><td>"+leasingPrice+"</td><td>"+automatic_billing+"</td><td>"+status+"</td><td>"+insurance+"</td><td><ins><a class=\"text-green updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+response.bike[i].frameNumber+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
                        dest=dest.concat(temp);
                        i++;

                    }
                    var temp="</tobdy></table>";
                    dest=dest.concat(temp);
                    document.getElementById('bikeDetailsAdmin').innerHTML = dest;

                    document.getElementById('counterBikeAdmin').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bikeNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.bikeNumber+"</span>";

                    displayLanguage();

                    $(".updateBikeAdmin").click(function() {
                        construct_form_for_bike_status_updateAdmin(this.name);
                        $('#widget-bikeManagement-form input').attr('readonly', false);
                        $('#widget-bikeManagement-form select').attr('readonly', false);
                        $('.bikeManagementTitle').html('Modifier un vélo');
                        $('.bikeManagementSend').removeClass('hidden');
                        $('.bikeManagementSend').html('<i class="fa fa-plus"></i>Modifier');

                    });


                    $(".retrieveBikeAdmin").click(function() {
                        construct_form_for_bike_status_updateAdmin(this.name);
                        $('#widget-bikeManagement-form input').attr('readonly', true);
                        $('#widget-bikeManagement-form select').attr('readonly', true);
                        $('.bikeManagementTitle').html('Consulter un vélo');
                        $('.bikeManagementSend').addClass('hidden');
                    });

                    $('.addBikeAdmin').click(function(){
                        add_bike();
                        $('#widget-bikeManagement-form input').attr('readonly', false);
                        $('#widget-bikeManagement-form select').attr('readonly', false);
                        $('.bikeManagementTitle').html('Ajouter un vélo');
                        $('.bikeManagementSend').removeClass('hidden');
                        $('.bikeManagementSend').html('<i class="fa fa-plus"></i>Ajouter');

                    });



                }
            }
        })
    }
    function list_boxes(company) {
        $.ajax({
            url: 'include/box_management.php',
            type: 'get',
            data: {"action": "list", "company": company},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="<a class=\"button small green button-3d rounded icon-right addBox\" name=\""+company+"\" data-target=\"#boxManagement\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une borne</span></a>";
                    if(response.boxesNumber>0){
                        var temp="<table class=\"table\"><tbody><thead><tr><th>ID</th><th scope=\"col\"><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Reference</span><span class=\"nl-inline\">Reference</span></th><th scope=\"col\"><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th scope=\"col\"><span class=\"fr-inline\">Facturation</span><span class=\"en-inline\">Automatic billing ?</span><span class=\"nl-inline\">Automatic billing ?</span></th><th scope=\"col\"><span class=\"fr-inline\">Montant leasing</span><span class=\"en-inline\">Leasing Price</span><span class=\"nl-inline\">Leasing Price</span></th><th>Début de contrat</th><th>Fin de contrat</th><th></th></tr></thead>";
                        dest=dest.concat(temp);

                        while (i < response.boxesNumber){

                            if(response.box[i].automatic_billing==null || response.box[i].automatic_billing=="N"){
                                automatic_billing="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                            }else{
                                automatic_billing="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                            }

                            if(response.box[i].amount==null){
                                amount="0 €/mois";
                            }else{
                                amount=response.box[i].amount+" €/mois";
                            }

                            if(response.box[i].start!=null && (response.box[i].company != 'KAMEO' && response.box[i].company != 'KAMEO VELOS TEST')){
                                start="<span class=\"text-green\">"+response.box[i].start.substr(0,10)+"</span>";
                            }else if (response.box[i].start == null && (response.box[i].company != 'KAMEO' && response.box[i].company != 'KAMEO VELOS TEST')){
                                start="<span class=\"text-red\">N/A</span>";
                            }else if(response.box[i].start == null && (response.box[i].company == 'KAMEO' || response.box[i].company == 'KAMEO VELOS TEST')){
                                start="<span class=\"text-green\">N/A</span>";
                            }else if(response.box[i].start != null && (response.box[i].company == 'KAMEO' || response.box[i].company == 'KAMEO VELOS TEST')){
                                start="<span class=\"text-red\">"+response.box[i].start.substr(0,10)+"</span>";
                            }else{
                                start="<span class=\"text-red\">ERROR</span>";
                            }


                            if(response.box[i].end && (response.box[i].company != 'KAMEO' && response.box[i].company != 'KAMEO VELOS TEST')){
                                end="<span class=\"text-green\">"+response.box[i].end.substr(0,10)+"</span>";
                            }else if (response.box[i].end == null && (response.box[i].company != 'KAMEO' && response.box[i].company != 'KAMEO VELOS TEST')){
                                end="<span class=\"text-red\">N/A</span>";
                            }else if(response.box[i].end == null && (response.box[i].company == 'KAMEO' || response.box[i].company == 'KAMEO VELOS TEST')){
                                end="<span class=\"text-green\">N/A</span>";
                            }else if(response.box[i].end != null && (response.box[i].company == 'KAMEO' || response.box[i].company == 'KAMEO VELOS TEST')){
                                end="<span class=\"text-red\">"+response.box[i].end.substr(0,10)+"</span>";
                            }else{
                                end="<span class=\"text-red\">ERROR</span>";
                            }


                            temp="<tr><td><a href=\"#\" class=\"text-green retrieveBox\" data-target=\"#boxManagement\" name=\""+response.box[i].id+"\" data-toggle=\"modal\">"+response.box[i].id+"</a></td><td>"+response.box[i].company+"</td><td>"+response.box[i].reference+"</td><td>"+response.box[i].model+"</td><td>"+automatic_billing+"</td><td>"+amount+"</td><td>"+start+"</td><td>"+end+"</td><td><a href=\"#\" class=\"text-green updateBox\" data-target=\"#boxManagement\" name=\""+response.box[i].id+"\" data-toggle=\"modal\">Mettre à jour </a></th></tr>";
                            dest=dest.concat(temp);
                            i++;
                        }

                        var temp="</tbody></table>";
                        dest=dest.concat(temp);
                    }
                    if(company=="*"){
                        document.getElementById('counterBoxes').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.boxesNumberTotal+"\" data-from=\"0\" data-seperator=\"true\">"+response.boxesNumberTotal+"</span>";
                    }



                    $('#boxesListingSpan').html(dest);
                    $('.addBox').click(function(){
                        add_box(this.name);
                    });
                    $('.updateBox').click(function(){
                        update_box(this.name);
                    });
                    $('.retrieveBox').click(function(){
                        retrieve_box(this.name);
                    });


                }
            }
        })
    }

    function list_tasks(status, owner2, numberOfResults) {
        var email= "<?php echo $user; ?>";
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



    function list_contracts_offers(company) {
        $.ajax({
            url: 'include/offer_management.php',
            type: 'get',
            data: { "company": company, action: "retrieve"},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="";
                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Contrats signés :</h4><h4 class=\"en-inline text-green\">Contracts:</h4><h4 class=\"nl-inline text-green\">Contracts:</h4><br/><br/><div class=\"seperator seperator-small visible-xs\"></div><tbody><thead><tr><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Description</span><span class=\"en-inline\">Description</span><span class=\"nl-inline\">Description</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Debut</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Fin</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th></tr></thead>";
                    dest=dest.concat(temp);
                    while (i < response.contractsNumber){
                        if(response.contract[i].start!=null){
                            var contract_start=response.contract[i].start.substr(0,10);
                        }else{
                            var contract_start="<span class=\"text-red\">N/A</span>";
                        }
                        if(response.contract[i].end!=null){
                            var contract_end=response.contract[i].end.substr(0,10);
                        }else{
                            var contract_end="<span class=\"text-red\">N/A</span>";
                        }

                        var temp="<tr><td>"+response.contract[i].company+"</td><td>"+response.contract[i].description+"</td><td>"+Math.round(response.contract[i].amount)+" €/mois</td><td>"+contract_start+"</td><td>"+contract_end+"</td></tr>";
                        dest=dest.concat(temp);
                        i++;

                    }
                    var temp="</tobdy></table>";
                    dest=dest.concat(temp);

                    var temp="<p>Valeur actuelle des contrat en cours : <strong>"+Math.round(response.sumContractsCurrent)+" €/mois</strong></p>";
                    dest=dest.concat(temp);

                    document.getElementById('contractsListingSpan').innerHTML = dest;



                    var i=0;
                    var dest="";
                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Offres en cours :</h4><h4 class=\"en-inline text-green\">Offers:</h4><h4 class=\"nl-inline text-green\">Offers:</h4><br/><br/><div class=\"seperator seperator-small visible-xs\"></div><tbody><thead><tr><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th>Type</th><th><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Debut</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Fin</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th>Probabilité</th><th></th></tr></thead>";
                    dest=dest.concat(temp);
                    while (i < response.offersNumber){
                        if(response.offer[i].start!=null){
                            var offer_start=response.offer[i].start.substr(0,10);
                        }else{
                            var offer_start="<span class=\"text-red\">N/A</span>";
                        }
                        if(response.offer[i].end!=null){
                            var offer_end=response.offer[i].end.substr(0,10);
                        }else{
                            var offer_end="<span class=\"text-red\">N/A</span>";
                        }

                        if(response.offer[i].type=="leasing"){
                            var amount=Math.round(response.offer[i].amount)+ "€/mois";
                        }else{
                            var amount=Math.round(response.offer[i].amount)+ "€";
                        }

                        if(response.offer[i].amount==0){
                            var amount="<span class=\"text-red\">"+amount+"</span>";
                        }

                        if(response.offer[i].type=="leasing"){
                            var type="Leasing";
                        }else if(response.offer[i].type=="achat"){
                            var type="Achat";
                        }

                        if(response.offer[i].probability==0 || response.offer[i].probability==0){
                            var probability="<span class=\"text-red\">"+response.offer[i].probability+" %</span>";
                        }else{
                            var probability="<span>"+response.offer[i].probability+" %</span>";
                        }


                        var temp="<tr><td><a href=\"#\" class=\"retrieveOffer\" data-target=\"#offerManagement\" data-toggle=\"modal\" name=\""+response.offer[i].id+"\">"+response.offer[i].id+"</a></td><td>"+response.offer[i].company+"</td><td>"+type+"</td><td>"+response.offer[i].title+"</td><td>"+amount+" </td><td>"+offer_start+"</td><td>"+offer_end+"</td><td>"+probability+"</td><td><ins><a class=\"text-green offerManagement updateOffer\" data-target=\"#offerManagement\" name=\""+response.offer[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";


                        dest=dest.concat(temp);
                        i++;

                    }
                    var temp="</tobdy></table>";
                    dest=dest.concat(temp);
                    document.getElementById('offersListingSpan').innerHTML = dest;

                    var i=0;
                    var dest="";
                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Coûts:</h4><h4 class=\"en-inline text-green\">Costs:</h4><h4 class=\"nl-inline text-green\">Costs:</h4><br/><br/><a class=\"button small green button-3d rounded icon-right addCost\" data-target=\"#costsManagement\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un coût</span></a><div class=\"seperator seperator-small visible-xs\"></div><tbody><thead><tr><th>ID</th><th><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Debut</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Fin</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th>Type</th><th></th></tr></thead>";
                    dest=dest.concat(temp);
                    while (i < response.costsNumber){
                        if(response.cost[i].start!=null){
                            var cost_start=response.cost[i].start.substr(0,10);
                        }else{
                            var cost="N/A";
                        }
                        if(response.cost[i].end!=null){
                            var cost_end=response.cost[i].end.substr(0,10);
                        }else{
                            var cost_end="N/A";
                        }

                        if(response.cost[i].type=="monthly"){
                            var amount=Math.round(response.cost[i].amount)+ "€/mois";
                        }else{
                            var amount=Math.round(response.cost[i].amount)+ "€";
                        }
                        var temp="<tr><td><a href=\"#\" class=\"retrieveCost\" data-target=\"#costsManagement\" data-toggle=\"modal\" name=\""+response.cost[i].id+"\">"+response.cost[i].id+"</a></td><td>"+response.cost[i].title+"</td><td>"+amount+" </td><td>"+cost_start+"</td><td>"+cost_end+"</td><td><ins><a class=\"text-green costsManagement updateCost\" data-target=\"#costsManagement\" name=\""+response.cost[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";


                        dest=dest.concat(temp);
                        i++;

                    }
                    var temp="</tobdy></table>";
                    dest=dest.concat(temp);
                    document.getElementById('costsListingSpan').innerHTML = dest;

                    $(".retrieveOffer").click(function() {
                        retrieve_offer(this.name, "retrieve");
                        $('.offerManagementTitle').text("Consulter une offre");
                        $('.offerManagementSendButton').addClass("hidden");

                    });
                    $(".updateOffer").click(function() {
                        retrieve_offer(this.name, "update");
                        $('.offerManagementTitle').text("Mettre à jour une offre");
                        $('.offerManagementSendButton').removeClass("hidden");
                        $('.offerManagementSendButton').text("Mettre à jour")

                    });


                    $(".addCost").click(function() {
                        $('#widget-costsManagement-form input').attr("readonly", false);
                        $('#widget-costsManagement-form textarea').attr("readonly", false);
                        $('#widget-costsManagement-form select').attr("readonly", false);
                        $('.costManagementTitle').text("Ajouter un coût");
                        $('.costManagementSendButton').removeClass("hidden");
                        document.getElementById('widget-costsManagement-form').reset();
                        $('.costManagementSendButton').text("Ajouter")

                    });
                    $(".retrieveCost").click(function() {
                        retrieve_cost(this.name, "retrieve");
                        $('.costManagementTitle').text("Consulter un coût");
                        $('.costManagementSendButton').addClass("hidden");
                    });
                    $(".updateCost").click(function() {
                        retrieve_cost(this.name, "update");
                        $('.costManagementTitle').text("Mettre à jour un coût");

                        $('.costManagementSendButton').removeClass("hidden");
                        $('.costManagementSendButton').text("Mettre à jour")

                    });


                    displayLanguage();

                }
            }
        })





        $.ajax({
            url: 'include/offer_management.php',
            type: 'get',
            data: { "graphics": "Y", action: "retrieve"},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var threeYearsFromNow = new Date();
                    threeYearsFromNow.setFullYear(threeYearsFromNow.getFullYear() + 1);
                    var maxXAxis=threeYearsFromNow.toISOString().split('T')[0];

                    var ctx = document.getElementById('myChart').getContext('2d');
                    if (myChart != undefined) {
                      myChart.destroy();
                    }
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            datasets: [{
                                label: 'Contrats signés',
                                borderColor: 'rgba(44, 132, 109, 0.5)',
                                backgroundColor:'rgba(44, 132, 109, 0)',
                                data: response.arrayContracts
                            },{
                                label: 'Offres',
                                borderColor: 'rgba(145, 145, 145, 0.5)',
                                backgroundColor:'rgba(145, 145, 145, 0)',
                                data: response.arrayOffers
                            },{
                                label: 'Chiffre d\'affaire',
                                borderColor: 'rgba(60, 179, 149, 0.5)',
                                backgroundColor:'rgba(60, 179, 149, 0)',
                                data: response.totalIN
                            },{
                                label: 'Frais',
                                borderColor: 'rgba(176, 0, 0, 0.5)',
                                backgroundColor:'rgba(176, 0, 0, 0)',
                                data: response.arrayCosts
                            },{
                                label: 'Cash flow',
                                borderColor: 'rgba(60, 179, 149, 0.5)',
                                backgroundColor:'rgba(60, 179, 149, 0.5)',
                                data: response.arrayFreeCashFlow
                            }],
                        labels: response.arrayDates

                        },

                        options: {
                            scales: {
                                xAxes:[{
                                    ticks:{
                                        max: "2020-12-19"
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            },
                            elements: {
                                line: {
                                    tension: 0
                                }
                            }

                        }
                    });

                }
            }
        })


    }

    function get_users_listing(){
        var email= "<?php echo $user; ?>";
        $.ajax({
            url: 'include/get_users_listing.php',
            type: 'post',
            data: { "email": email},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="";

                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Utilisateurs :</h4><h4 class=\"en-inline\">Users:</h4><h4 class=\"nl-inline\">Gebruikers:</h4><br><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addUser\" data-toggle=\"modal\" onclick=\"create_user()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un utilisateur</span></a><tbody><thead><tr><th><span class=\"fr-inline\">Nom</span><span class=\"en-inline\">Name</span><span class=\"nl-inline\">Naam</span></th><th><span class=\"fr-inline\">Prénom</span><span class=\"en-inline\">Firstname</span><span class=\"nl-inline\">Voorname</span></th><th><span class=\"fr-inline\">e-mail</span><span class=\"en-inline\">mail</span><span class=\"nl-inline\">mail</span></th><th>Status</th><th></th></tr></thead>";
                    dest=dest.concat(temp);

                    while (i < response.usersNumber){
                        if(response.user[i].staann=='D'){
                            var status="<span class=\"text-red\">Inactif</span>";
                        }else{
                            var status="Actif";
                        }
                        var temp="<tr><th>"+response.user[i].name+"</th><th>"+response.user[i].firstName+"</th><th>"+response.user[i].email+"</th><th>"+status+"</th><th><a  data-target=\"#updateUserInformation\" name=\""+response.user[i].email+"\" data-toggle=\"modal\" class=\"text-green\" href=\"#\" onclick=\"update_user_information('"+response.user[i].email+"')\">Mettre à jour</a></th></tr>";
                        dest=dest.concat(temp);

                        i++;
                    }
                    document.getElementById('counterUsers').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.usersNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.usersNumber+"</span>";
                    document.getElementById('usersList').innerHTML = dest;
                    displayLanguage();

                }
            }
        })
    }



    function confirm_add_user(){

        document.getElementById('confirmAddUser').innerHTML="<p><strong>Attention</strong>, la création d'un compte entraînera l'envoi d'un mail vers la personne en question.<br>\
        Veuillez confirmer que les informations mentionées précédemment sont correctes.</p><button class=\"fr button small green button-3d rounded icon-left\" type=\"submit\"><i class=\"fa fa-paper-plane\"></i>Confirmer</button>";
    }

    function list_condition(){
        var email= "<?php echo $user; ?>";
        $.ajax({
            url: 'include/get_conditions_listing.php',
            type: 'get',
            data: { "email": email},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="";
                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Groupes de condition :</h4><h4 class=\"en-inline\">Condition groups:</h4><h4 class=\"nl-inline\">Condition groups:</h4><br><a class=\"button small green button-3d rounded icon-right\" data-target=\"#companyConditions\" data-toggle=\"modal\" onclick=\"create_condition()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un groupe de conditions</span></a><tbody><thead><tr><th><span class=\"fr-inline\">Nom</span><span class=\"en-inline\">Name</span><span class=\"nl-inline\">Naam</span></th><th><span class=\"fr-inline\">Nombre d'utilisateurs</span><span class=\"en-inline\">Groupe size</span><span class=\"nl-inline\">Group size</span></th><th></th></tr></thead>";
                    dest=dest.concat(temp);

                    while (i < response.conditionNumber){

                        if(response.condition[i].name=="generic"){
                            var temp="<tr><th>Conditions génériques</th>";
                        }
                        else{
                            var temp="<tr><th>"+response.condition[i].name+"</th>";
                        }
                        dest=dest.concat(temp);
                        var temp="<th>"+response.condition[i].length+"</th><th><a  data-target=\"#companyConditions\" data-toggle=\"modal\" class=\"text-green\" href=\"#\" onclick=\"get_company_conditions('"+response.condition[i].id+"')\">Mettre à jour</a></th></tr>";
                        dest=dest.concat(temp);

                        i++;
                    }
                    document.getElementById('spanConditionListing').innerHTML = dest;
                    displayLanguage();

                }
            }
        })

    }


    function get_company_conditions(id){
        var emailArray;
        var email= "<?php echo $user; ?>";
        $.ajax({
            url: 'include/get_company_conditions.php',
            type: 'post',
            data: { "email": email, "id": id},

            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    $('#widget-updateCompanyConditions-form input[name=action]').val("update");

                    if(response.update){

                        var classname = document.getElementsByClassName('kameo');
                        for (var i = 0; i < classname.length; i++) {
                            classname[i].classList.remove("hidden");
                        }

                        document.getElementById('clientManagement').classList.remove("hidden");
                        document.getElementById('portfolioManagement').classList.remove("hidden");
                        document.getElementById('bikesManagement').classList.remove("hidden");
                        document.getElementById('boxesManagement').classList.remove("hidden");
                        document.getElementById('tasksManagement').classList.remove("hidden");
                        document.getElementById('cashFlowManagement').classList.remove("hidden");
                        document.getElementById('feedbacksManagement').classList.remove("hidden");
                    }


                    $('#widget-updateCompanyConditions-form input[name=id]').val(id);
                    if(response.companyConditions.name=="generic"){
                        $('#widget-updateCompanyConditions-form input[name=name]').val("Conditions génériques");
                        $('#widget-updateCompanyConditions-form input[name=name]').prop('readonly', true);

                    }else{
                        $('#widget-updateCompanyConditions-form input[name=name]').val(response.companyConditions.name);
                        $('#widget-updateCompanyConditions-form input[name=name]').prop('readonly', false);

                    }
                    $('#widget-updateCompanyConditions-form input[name=daysInAdvance]').val(response.companyConditions.bookingDays);
                    $('#widget-updateCompanyConditions-form input[name=bookingLength]').val(response.companyConditions.bookingLength);
                    $('#widget-updateCompanyConditions-form input[name=bookingsPerYear]').val(response.companyConditions.maxBookingsPerYear);
                    $('#widget-updateCompanyConditions-form input[name=bookingsPerMonth]').val(response.companyConditions.maxBookingsPerMonth);
                    $('#widget-updateCompanyConditions-form input[name=startIntakeBooking]').val(response.companyConditions.hourStartIntakeBooking);
                    $('#widget-updateCompanyConditions-form input[name=endIntakeBooking]').val(response.companyConditions.hourEndIntakeBooking);
                    $('#widget-updateCompanyConditions-form input[name=startDepositBooking]').val(response.companyConditions.hourStartDepositBooking);
                    $('#widget-updateCompanyConditions-form input[name=endDepositBooking]').val(response.companyConditions.hourEndDepositBooking);


                    var dest="";
                    if(response.companyConditions.mondayIntake==1){
                        temp="<input type=\"checkbox\" name=\"intakeBookingMonday\" checked value=\""+response.companyConditions.mondayIntake+"\">Lundi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"intakeBookingMonday\" value=\""+response.companyConditions.mondayIntake+"\">Lundi<br>";
                    }
                    dest=dest.concat(temp);

                    if(response.companyConditions.tuesdayIntake==1){
                        temp="<input type=\"checkbox\" name=\"intakeBookingTuesday\" checked value=\""+response.companyConditions.tuesdayIntake+"\">Mardi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"intakeBookingTuesday\" value=\""+response.companyConditions.tuesdayIntake+"\">Mardi<br>";
                    }
                        dest=dest.concat(temp);
                    if(response.companyConditions.wednesdayIntake==1){
                        temp="<input type=\"checkbox\" name=\"intakeBookingWednesday\" checked value=\""+response.companyConditions.wednesdayIntake+"\">Mercredi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"intakeBookingWednesday\" value=\""+response.companyConditions.wednesdayIntake+"\">Mercredi<br>";
                    }
                    dest=dest.concat(temp);
                    if(response.companyConditions.thursdayIntake==1){
                        temp="<input type=\"checkbox\" name=\"intakeBookingThursday\" checked value=\""+response.companyConditions.thursdayIntake+"\">Jeudi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"intakeBookingThursday\" value=\""+response.companyConditions.thursdayIntake+"\">Jeudi<br>";
                    }
                    dest=dest.concat(temp);
                    if(response.companyConditions.fridayIntake==1){
                        temp="<input type=\"checkbox\" name=\"intakeBookingFriday\" checked value=\""+response.companyConditions.fridayIntake+"\">Vendredi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"intakeBookingFriday\"  value=\""+response.companyConditions.fridayIntake+"\">Vendredi<br>";
                    }
                    dest=dest.concat(temp);
                    if(response.companyConditions.saturdayIntake==1){
                        temp="<input type=\"checkbox\" name=\"intakeBookingSaturday\" checked value=\""+response.companyConditions.saturdayIntake+"\">Samedi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"intakeBookingSaturday\" value=\""+response.companyConditions.saturdayIntake+"\">Samedi<br>";
                    }
                    dest=dest.concat(temp);
                    if(response.companyConditions.sundayIntake==1){
                        temp="<input type=\"checkbox\" name=\"intakeBookingSunday\" checked value=\""+response.companyConditions.sundayIntake+"\">Dimanche<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"intakeBookingSunday\" value=\""+response.companyConditions.sundayIntake+"\">Dimanche<br>";
                    }
                    dest=dest.concat(temp);
                    document.getElementsByClassName('intakeBookingDays')[0].innerHTML = dest;

                    var dest="";
                    if(response.companyConditions.mondayDeposit==1){
                        temp="<input type=\"checkbox\" name=\"depositBookingMonday\" checked value=\""+response.companyConditions.mondayDeposit+"\">Lundi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"depositBookingMonday\" value=\""+response.companyConditions.mondayDeposit+"\">Lundi<br>";
                    }
                    dest=dest.concat(temp);
                    if(response.companyConditions.tuesdayDeposit==1){
                        temp="<input type=\"checkbox\" name=\"depositBookingTuesday\" checked value=\""+response.companyConditions.tuesdayDeposit+"\">Mardi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"depositBookingTuesday\" value=\""+response.companyConditions.tuesdayDeposit+"\">Mardi<br>";
                    }
                    dest=dest.concat(temp);
                    if(response.companyConditions.wednesdayDeposit==1){
                        temp="<input type=\"checkbox\" name=\"depositBookingWednesday\" checked value=\""+response.companyConditions.wednesdayDeposit+"\">Mercredi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"depositBookingWednesday\" value=\""+response.companyConditions.wednesdayDeposit+"\">Mercredi<br>";
                    }
                    dest=dest.concat(temp);
                    if(response.companyConditions.thursdayDeposit==1){
                        temp="<input type=\"checkbox\" name=\"depositBookingThursday\" checked value=\""+response.companyConditions.thursdayDeposit+"\">Jeudi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"depositBookingThursday\" value=\""+response.companyConditions.thursdayDeposit+"\">Jeudi<br>";
                    }
                    dest=dest.concat(temp);
                    if(response.companyConditions.fridayDeposit==1){
                        temp="<input type=\"checkbox\" name=\"depositBookingFriday\" checked value=\""+response.companyConditions.fridayDeposit+"\">Vendredi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"depositBookingFriday\" value=\""+response.companyConditions.fridayDeposit+"\">Vendredi<br>";
                    }
                    dest=dest.concat(temp);
                    if(response.companyConditions.saturdayDeposit==1){
                        temp="<input type=\"checkbox\" name=\"depositBookingSaturday\" checked value=\""+response.companyConditions.saturdayDeposit+"\">Samedi<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"depositBookingSaturday\" value=\""+response.companyConditions.saturdayDeposit+"\">Samedi<br>";
                    }
                    dest=dest.concat(temp);
                    if(response.companyConditions.sundayDeposit==1){
                        temp="<input type=\"checkbox\" name=\"depositBookingSunday\" checked value=\""+response.companyConditions.sundayDeposit+"\">Dimanche<br>";
                    }else{
                        temp="<input type=\"checkbox\" name=\"depositBookingSunday\" value=\""+response.companyConditions.sundayDeposit+"\">Dimanche<br>";
                    }
                    dest=dest.concat(temp);

                    if(response.userAccessNumber==0){
                        emailArray=[];
                    }else{
                        emailArray=response.companyConditions.email;
                    }
                    $.ajax({
                        url: 'include/get_company_details.php',
                        type: 'post',
                        data: { "email": email},
                        success: function(response){
                            if(response.response == 'error') {
                                console.log(response.message);
                            }
                            if(response.response == 'success'){
                                var i=0;
                                var dest="";
                                while (i < response.userNumber){
                                    if(emailArray.includes(response.user[i].email)){
                                        temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"userAccess[]\" checked value=\""+response.user[i].email+"\"> "+response.user[i].firstName+" "+response.user[i].name+"</div>";
                                    }else{
                                        temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"userAccess[]\" value=\""+response.user[i].email+"\"> "+response.user[i].firstName+" "+response.user[i].name+"</div>";
                                    }
                                    dest=dest.concat(temp);
                                    i++;

                                }
                                document.getElementById('groupConditionUsers').innerHTML = dest;
                            }
                        }
                    })

                    document.getElementsByClassName('depositBookingDays')[0].innerHTML = dest;


                }
            }
        })
    }


    function create_condition(){
        var email= "<?php echo $user; ?>";
        $('#widget-updateCompanyConditions-form input[name=name]').val("");
        $('#widget-updateCompanyConditions-form input[name=name]').prop('readonly', false);

        $('#widget-updateCompanyConditions-form input[name=daysInAdvance]').val("");
        $('#widget-updateCompanyConditions-form input[name=bookingLength]').val("");
        $('#widget-updateCompanyConditions-form input[name=bookingsPerYear]').val("");
        $('#widget-updateCompanyConditions-form input[name=bookingsPerMonth]').val("");
        $('#widget-updateCompanyConditions-form input[name=startIntakeBooking]').val("");
        $('#widget-updateCompanyConditions-form input[name=endIntakeBooking]').val("");
        $('#widget-updateCompanyConditions-form input[name=startDepositBooking]').val("");
        $('#widget-updateCompanyConditions-form input[name=endDepositBooking]').val("");

        var temp="<input type=\"checkbox\" name=\"depositBookingMonday\" value=\"\">Lundi<br><input type=\"checkbox\" name=\"depositBookingTuesday\" value=\"\">Mardi<br><input type=\"checkbox\" name=\"depositBookingWednesday\" value=\"\">Mercredi<br><input type=\"checkbox\" name=\"depositBookingThursday\" value=\"\">Jeudi<br><input type=\"checkbox\" name=\"depositBookingFriday\" value=\"\">Vendredi<br><input type=\"checkbox\" name=\"depositBookingSaturday\" value=\"\">Samedi<br><input type=\"checkbox\" name=\"depositBookingSunday\" value=\"\">Dimanche<br>";
        document.getElementsByClassName('intakeBookingDays')[0].innerHTML = temp;
        document.getElementsByClassName('depositBookingDays')[0].innerHTML = temp;
        $('#widget-updateCompanyConditions-form input[name=action]').val("create");


        $.ajax({
            url: 'include/get_company_details.php',
            type: 'post',
            data: { "email": email},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="";
                    while (i < response.userNumber){
                        temp="<div class=\"col-sm-3\"><input type=\"checkbox\" name=\"userAccess[]\" value=\""+response.user[i].email+"\"> "+response.user[i].firstName+" "+response.user[i].name+"</div>";
                        dest=dest.concat(temp);
                        i++;

                    }
                    document.getElementById('groupConditionUsers').innerHTML = dest;



                }
            }
        })


    }


    function create_user(){
        var email= "<?php echo $user; ?>";
        $.ajax({
            url: 'include/get_building_listing.php',
            type: 'post',
            data: { "email": email},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="";
                    while (i < response.buildingNumber){
                        temp="<input type=\"checkbox\" name=\"buildingAccess[]\" checked value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"<br>";
                        dest=dest.concat(temp);
                        i++;

                    }
                    document.getElementById('buildingCreateUser').innerHTML = dest;

                    $.ajax({
                        url: 'include/get_bikes_listing.php',
                        type: 'post',
                        data: { "email": email},
                        success: function(response){
                            if(response.response == 'error') {
                                console.log(response.message);
                            }
                            if(response.response == 'success'){
                                var i=0;
                                var dest="";
                                while (i < response.bikeNumber){
                                    temp="<input type=\"checkbox\" name=\"bikeAccess[]\" checked value=\""+response.bike[i].frameNumber+"\">"+response.bike[i].frameNumber+" "+response.bike[i].model+"<br>";
                                    dest=dest.concat(temp);
                                    i++;

                                }
                            document.getElementById('bikeCreateUser').innerHTML = dest;
                            $('#widget-addUser-form input[name=company]').val("");
                            document.getElementById('confirmAddUser').innerHTML="<button class=\"fr button small green button-3d rounded icon-left\" onclick=\"confirm_add_user()\">\
                                    <i class=\"fa fa-paper-plane\">\
                                    </i>\
                                    Confirmer\
                                    </button>";
                            }
                        }
                    })
                }
            }
        })
    }

    function create_bill(){
        $.ajax({
            url: 'include/get_companies_listing.php',
            type: 'post',
            data: {type: "*"},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="<select name=\"widget-addBill-form-company\" class=\"widget-addBill-form-company2\">";
                    while (i < response.companiesNumber){
                        temp="<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>";
                        dest=dest.concat(temp);
                        i++;

                    }
                    dest=dest.concat("<option value=\"other\">Autre</option></select>");
                    document.getElementsByClassName('widget-addBill-form-company')[0].innerHTML = dest;
                    document.getElementsByClassName('widget-addBill-form-date')[0].value = "";
                    document.getElementsByClassName('widget-addBill-form-amountHTVA')[0].value = "";
                    document.getElementsByClassName('widget-addBill-form-amountTVAC')[0].value = "";
                    document.getElementsByClassName('widget-addBill-form-sendingDate')[0].value = "";
                    document.getElementsByClassName('widget-addBill-form-paymentDate')[0].value = "";
                    $('.widget-addBill-form-companyOther').addClass("hidden");
                    $('.IDAddBill').removeClass('hidden');
                    $('.IDAddBillOut').removeClass('hidden');

                }
            }
        })
    }


    function update_user_information(email){
        $.ajax({
            url: 'include/get_user_details.php',
            type: 'post',
            data: { "email": email},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    document.getElementById('widget-updateUser-form-firstname').value = response.user.firstName;
                    document.getElementById('widget-updateUser-form-name').value = response.user.name;
                    document.getElementById('widget-updateUser-form-mail').value = response.user.email;
                    var dest="";
                    if(response.user.staann=='D'){
                        document.getElementById('widget-updateUser-form-status').value = "Inactif";
                        $('#widget-updateUser-form-firstname').prop('readonly', true);
                        $('#widget-updateUser-form-name').prop('readonly', true);
                        $("#widget-updateUser-form input[name=fleetManager]").prop( "readonly", true );
                        document.getElementById('buildingUpdateUser').innerHTML = "";
                        document.getElementById('bikeUpdateUser').innerHTML = "";
                        var dest="<a class=\"button small green button-3d rounded icon-right\" data-target=\"#reactivateUser\" onclick=\"initializeReactivateUser('"+response.user.email+"')\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Ré-activer</span><span class=\"en-inline\">Re-activate</span></a>";
                        document.getElementById('updateUserSendButton').innerHTML="";
                        document.getElementById('deleteUserButton').innerHTML=dest;



                    }else{
                        $('#widget-updateUser-form-firstname').prop('readonly', false);
                        $('#widget-updateUser-form-name').prop('readonly', false);
                        if(response.user.administrator=="Y"){
                            $("#widget-updateUser-form input[name=fleetManager]").prop( "checked", true );
                        }else{
                            $("#widget-updateUser-form input[name=fleetManager]").prop( "checked", false );
                        }

                        document.getElementById('widget-updateUser-form-status').value = "Actif";
                        var i=0;
                        var dest="<h4>Accès aux bâtiments</h4>";
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
                        document.getElementById('buildingUpdateUser').innerHTML = dest;

                        var i=0;
                        var dest="<h4>Accès aux vélos</h4>";

                        while(i<response.bikeNumber){
                            if(response.bike[i].access==true){
                                temp="<input type=\"checkbox\" checked name=\"bikeAccess[]\" value=\""+response.bike[i].bikeCode+"\">"+response.bike[i].bikeCode+" "+response.bike[i].model+"<br>";

                            }
                            else if(response.bike[i].access==false){
                                temp="<input type=\"checkbox\" name=\"bikeAccess[]\" value=\""+response.bike[i].bikeCode+"\">"+response.bike[i].bikeCode+" "+response.bike[i].model+"<br>";

                            }
                            dest=dest.concat(temp);
                            i++;
                        }
                        document.getElementById('bikeUpdateUser').innerHTML = dest;

                        var dest="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#deleteUser\" onclick=\"initializeDeleteUser('"+response.user.email+"')\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Supprimer</span><span class=\"en-inline\">Delete</span></a>";
                        document.getElementById('updateUserSendButton').innerHTML="<button class=\"fr button small green button-3d rounded icon-left\" type=\"submit\"><i class=\"fa fa-paper-plane\"></i>Envoyer</button><button  class=\"en button small green button-3d rounded icon-left\" type=\"submit\" ><i class=\"fa fa-paper-plane\"></i>Send</button><button  class=\"nl button small green button-3d rounded icon-left\" type=\"submit\" ><i class=\"fa fa-paper-plane\"></i>Verzenden</button>";

                        document.getElementById('deleteUserButton').innerHTML=dest;

                    }
                }


                $('#usersListing').modal('toggle');
                displayLanguage();



            }
        })
    }

    function initializeDeleteUser(email){

        $.ajax({
            url: 'include/get_user_details.php',
            type: 'post',
            data: { "email": email},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    document.getElementById('widget-deleteUser-form-firstname').value = response.user.firstName;
                    document.getElementById('widget-deleteUser-form-name').value = response.user.name;
                    document.getElementById('widget-deleteUser-form-mail').value = response.user.email;
                }

            }
        })
        $('#updateUserInformation').modal('toggle');

    }

    function initializeDeleteReservation(reservationID){

        $.ajax({
            url: 'include/get_reservation_details.php',
            type: 'post',
            data: { "reservationID": reservationID},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    document.getElementById('widget-deleteReservation-form-start').value = response.reservationStartBuilding+" le "+response.reservationStartDate;
                    document.getElementById('widget-deleteReservation-form-end').value = response.reservationEndBuilding+" le "+response.reservationEndDate;
                    document.getElementById('widget-deleteReservation-form-user').value = response.reservationEmail;
                    document.getElementById('widget-deleteReservation-form-ID').value = reservationID;

                }

            }
        })
        $('#reservationDetails').modal('toggle');

    }

    function initializeUpdateReservation(reservationID){

        $.ajax({
            url: 'include/get_reservation_details.php',
            type: 'post',
            data: { "reservationID": reservationID},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    document.getElementById('widget-updateReservation-form-start').value = response.reservationStartBuilding+" le "+response.reservationStartDate;
                    document.getElementById('widget-updateReservation-form-end').value = response.reservationEndBuilding+" le "+response.reservationEndDate;
                    document.getElementById('widget-updateReservation-form-user').value = response.reservationEmail;
                    document.getElementById('widget-updateReservation-form-ID').value = reservationID;
                }

            }
        })
        $('#reservationDetails').modal('toggle');

    }


    function initializeReactivateUser(email){

        $.ajax({
            url: 'include/get_user_details.php',
            type: 'post',
            data: { "email": email},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    document.getElementById('widget-reactivateUser-form-firstname').value = response.user.firstName;
                    document.getElementById('widget-reactivateUser-form-name').value = response.user.name;
                    document.getElementById('widget-reactivateUser-form-mail').value = response.user.email;
                }

            }
        })
        $('#updateUserInformation').modal('toggle');

    }


    function get_reservations_listing(bike, date_start, date_end){
        var email= "<?php echo $user; ?>";
        var frameNumber='';
        var timeStampStart=(date_start.valueOf()/1000);
        var timeStampEnd=(date_end.valueOf()/1000);
        if((typeof bike == "undefined") || bike == "" || bike=="Sélection de vélo"){
            var bikeValue="all";
        } else {
            var bikeValue=bike;
        }
        if(timeStampStart==''){
            d = new Date(new Date().getFullYear(), 0, 1);
            timeStampStart=+d;
            timeStampStart=timeStampStart/1000;
        }
        if(timeStampEnd==''){
            timeStampEnd=Date.now();
            timeSt
            ampEnd=Math.round(timeStampEnd/1000);
        }
        $.ajax({
            url: 'include/get_reservations_listing.php',
            type: 'post',
            data: { "email": email, "bikeValue": bikeValue, "timeStampStart": timeStampStart, "frameNumber": frameNumber, "timeStampEnd": timeStampEnd},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="";
                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline\"></div><tbody><thead><tr><th><span class=\"fr-inline text-green\">Réf.</span></th><th><span class=\"fr-inline text-green\">Vélo</span><span class=\"en-inline text-green\">Bike</span><span class=\"nl-inline text-green\">Bike</span></th><th><span class=\"fr-inline text-green\">Départ</span><span class=\"en-inline text-green\">Depart</span><span class=\"nl-inline text-green\">Depart</span></th><th><span class=\"fr-inline text-green\">Fin</span><span class=\"en-inline text-green\">End</span><span class=\"nl-inline text-green\">End</span></th><th><span class=\"fr-inline text-green\">Utilisateur</span><span class=\"en-inline text-green\">User</span><span class=\"nl-inline text-green\">User</span></th></tr></thead>";
                    dest=dest.concat(temp);
                    while (i < response.bookingNumber){

                        var temp="<tr><th><a data-target=\"#reservationDetails\" name=\""+response.booking[i].reservationID+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillReservationDetails(this.name)\">"+response.booking[i].reservationID+"</a></th><th><a  data-target=\"#bikeDetailsFull\" name=\""+response.booking[i].frameNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillBikeDetails(this.name)\">"+response.booking[i].frameNumber+"</a></th><th class=\"fr-cell\">"+response.booking[i].dateStartFR+"</th><th class=\"en-cell\">"+response.booking[i].dateStartEN+"</th><th class=\"nl-cell\">"+response.booking[i].dateStartNL+"</th><th class=\"fr-cell\">"+response.booking[i].dateEndFR+"</th><th class=\"en-cell\">"+response.booking[i].dateEndEN+"</th><th class=\"nl-cell\">"+response.booking[i].dateEndNL+"</th><th>"+response.booking[i].user+"</th></tr>";
                        dest=dest.concat(temp);

                        i++;

                    }
                    var temp="</tobdy></table>";
                    dest=dest.concat(temp);
                    document.getElementById('ReservationsList').innerHTML = dest;

                    displayLanguage();

                }
            }
        })

    }




    function initialize_booking_counter(){
        var email= "<?php echo $user; ?>";

        var date_start=new Date();
        var date_end=new Date();

        date_start.setMonth(date_start.getMonth()-1);
        var timeStampStart=Math.round(date_start.valueOf()/1000);
        var timeStampEnd=Math.round(date_end.valueOf()/1000);
        var bikeValue="all";



        $.ajax({
            url: 'include/get_reservations_listing.php',
            type: 'post',
            data: { "email": email, "bikeValue": bikeValue, "timeStampStart": timeStampStart, "timeStampEnd": timeStampEnd},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    document.getElementById('counterBookings').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bookingNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.bookingNumber+"</span>";
                    var counter1=response.bookingNumber;


                }

                date_start.setMonth(date_start.getMonth()-1);
                date_end.setMonth(date_end.getMonth()-1);
                var timeStampStart=(date_start.valueOf()/1000);
                var timeStampEnd=(date_end.valueOf()/1000);

                $.ajax({
                url: 'include/get_reservations_listing.php',
                type: 'post',
                data: { "email": email, "bikeValue": bikeValue, "timeStampStart": timeStampStart, "timeStampEnd": timeStampEnd},
                success: function(response){
                    if(response.response == 'error') {
                        console.log(response.message);
                    }
                    if(response.response == 'success'){
                        var counter2=response.bookingNumber;


                        if(counter2==0 && counter1>0){
                            var evolution=99999;
                        }
                        if(counter2==0 && counter1==0){
                            var evolution=0;
                        }else{
                            var evolution=Math.round((counter1-counter2)/counter2*100);
                        }


                        //if(evolution >0.1){
                        evolution=10;
                            var temp="\
                            <div class=\'col-md-4\">\
                                <p>Évolution du nombre de réservations rapport au mois précédent:<br>\
                                <strong class=\"text-green\">"+evolution+" %</strong></p>\
                                </div>\
                                <div class=\"col-md-8\">\
                                     <div class=\"progress-bar-container radius color\">\
                                          <div class=\"progress-bar\" data-percent=\""+evolution+"\" data-delay=\"100\" data-type=\"%\">\
                                          </div>\
                                </div>\
                            </div>";
                        document.getElementById('progress-bar-bookings').innerHTML=temp;
                        //}
                        //else if(evolution >= 0){
                        //    document.getElementById('progress-bar-bookings').innerHTML="<div class=\"progress-bar-container radius title-up color-sun-flower\"><div class=\"progress-bar\" data-percent=\""+evolution+"\" //data-delay=\"200\" data-type=\"%\"><div class=\"progress-title fr\">Évolution du nombre de réservations rapport au mois précédent</div></div></div>";
                        //}else{
                        //    document.getElementById('progress-bar-bookings').innerHTML="<div class=\"progress-bar-container radius title-up color-red \"><div class=\"progress-bar\" data-percent=\""+evolution+"\" data-delay=\"200\" data-type=\"%\"><div class=\"progress-title fr\">Évolution du nombre de réservations rapport au mois précédent</div></div></div>";
                        //}
                    }
                }
                })
            }
        })
    }


    function getHistoricBookings() {
        var user= "<?php echo $user; ?>";
        var langue= "<?php echo $_SESSION['langue']; ?>";
        $.ajax({
            url: 'include/get_historic_bookings.php',
            type: 'post',
            data: { "user": user},
            success: function(response) {
                if(response.response=="success"){
                    var i=0;
                    var dest="";

                    var tempHistoricBookings="<table class=\"table table-condensed\"><h4 class=\"fr-inline\">Réservations précédentes:</h4><h4 class=\"en-inline\">Previous Bookings:</h4><h4 class=\"nl-inline\">Vorige reservaties:</h4>";
                    dest=dest.concat(tempHistoricBookings);

                    var tempHistoricBookings="<ul><li>Depuis le début de l'année : "+response.maxBookingsPerYear+" réservations";
                    dest = dest.concat(tempHistoricBookings);

                    if(response.maxBookingsPerYearCondition != '9999'){
                        var tempHistoricBookings=" (maximum "+response.maxBookingsPerYearCondition+")</li><li>Depuis le début du mois : "+response.maxBookingsPerMonth+" réservations";
                    }else{
                        var tempHistoricBookings="</li><li>Depuis le début du mois : "+response.maxBookingsPerMonth+" réservations";
                    }
                    dest = dest.concat(tempHistoricBookings);


                    if(response.maxBookingsPerMonthCondition != '9999'){
                        var tempHistoricBookings=" (maximum "+response.maxBookingsPerMonthCondition+")</li></ul>";
                    }else{
                        var tempHistoricBookings="</li></ul>";
                    }

                    dest = dest.concat(tempHistoricBookings);




                    var tempHistoricBookings="<thead><tr><th><span class=\"fr-inline\">Départ</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Arrivée</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fitse</span></th><th></th></tr></thead><tbody>";
                    dest = dest.concat(tempHistoricBookings);

                    while (i < response.previous_bookings)
                    {
                        var dayStart=response.booking[i].dayStart;
                        var dayEnd=response.booking[i].dayEnd;
                        var hour_start=response.booking[i].hour_start;
                        var hour_end=response.booking[i].hour_end;
                        var building_start_fr = response.booking[i].building_start_fr;
                        var building_start_en = response.booking[i].building_start_en;
                        var building_start_nl = response.booking[i].building_start_nl;
                        var building_end_fr = response.booking[i].building_end_fr;
                        var building_end_en = response.booking[i].building_end_en;
                        var building_end_nl = response.booking[i].building_end_nl;
                        var frame_number=response.booking[i].frameNumber;


                        var tempHistoricBookings ="<tr><td>"+dayStart+ " - "+building_start_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_start+"</td><td>"+dayEnd+" - "+building_end_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_end+"</td><td>"+frame_number+"</td><td><a class=\"button small red rounded effect\" data-target=\"#entretien2\" data-toggle=\"modal\" href=\"#\" onclick=\"initializeEntretien2('"+frame_number+"')\"><i class=\"fa fa-wrench\"></i><span>Entretien</span></a></td></tr>";

                        dest = dest.concat(tempHistoricBookings);
                        i++;

                    }


                    var tempHistoricBookings="</tbody></table>";
                    dest = dest.concat(tempHistoricBookings);

                    //affichage du résultat de la recherche
                    document.getElementById('historicBookings').innerHTML = dest;

                    //Booking futurs

                    var dest="";
                    if(response.booking.codePresence==false){
                        var tempFutureBookings="<table class=\"table table-condensed\"><h4 class=\"fr-inline\">Réservations futures:</h4><h4 class=\"en-inline\">Next bookings:</h4><h4 class=\"nl-inline\">Volgende boekingen:</h4><thead><tr><th><span class=\"fr-inline\">Départ</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Arrivée</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fitse</span></th></tr></thead><tbody>";
                    } else{
                        var tempFutureBookings="<table class=\"table table-condensed\"><h4 class=\"fr-inline\">Réservations futures:</h4><h4 class=\"en-inline\">Next bookings:</h4><h4 class=\"nl-inline\">Volgende boekingen:</h4><thead><tr><th><span class=\"fr-inline\">Départ</span><span class=\"en-inline\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Arrivée</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fitse</span></th><th>Code</th></tr></thead><tbody>";
                    }
                    dest = dest.concat(tempFutureBookings);
                    var length = parseInt(response.future_bookings)+parseInt(response.previous_bookings);
                    while (i < length)
                    {
                        var dayStart=response.booking[i].dayStart;
                        var dayEnd=response.booking[i].dayEnd;
                        var hour_start=response.booking[i].hour_start;
                        var hour_end=response.booking[i].hour_end;
                        var building_start_fr = response.booking[i].building_start_fr;
                        var building_start_en = response.booking[i].building_start_en;
                        var building_start_nl = response.booking[i].building_start_nl;
                        var building_end_fr = response.booking[i].building_end_fr;
                        var building_end_en = response.booking[i].building_end_en;
                        var building_end_nl = response.booking[i].building_end_nl;
                        var frame_number=response.booking[i].frameNumber;
                        var booking_id=response.booking[i].bookingID;
                        var annulation=response.booking[i].annulation;

                        if(response.booking.codePresence==false){
                            var tempFutureBookings ="<tr><td>"+dayStart+ " - "+building_start_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_start+"</td><td>"+dayEnd+" - "+building_end_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_end+"</td><td>"+frame_number+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a></td>";
                        }else{
                            code=response.booking[i].codeValue;
                            if(code.length==3){
                                code="0"+code;
                            }else if(code.length==2){
                                code="00"+code;
                            }else if(code.length==1){
                                code="000"+length;
                            }

                            var tempFutureBookings ="<tr><td>"+dayStart+ " - "+building_start_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_start+"</td><td>"+dayEnd+" - "+building_end_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_end+"</td><td>"+frame_number+"</td><td>"+code+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a></td>";

                        }
                        if(annulation){
                            var tempAnnulation = "<td><a class=\"button small red rounded effect\" onclick=\"cancelBooking("+booking_id+")\"><i class=\"fa fa-times\"></i><span>annuler</span></a></td></td></tr>";
                            tempFutureBookings = tempFutureBookings.concat(tempAnnulation);
                        } else{
                            var tempAnnulation = "</td></tr>";
                            tempFutureBookings = tempFutureBookings.concat(tempAnnulation);
                        }
                        dest = dest.concat(tempFutureBookings);

                        i++;

                    }
                    var tempFutureBookings="</tbody></table>";
                    dest = dest.concat(tempFutureBookings);

                    //affichage du résultat de la recherche
                    document.getElementById('futureBookings').innerHTML = dest;
                    displayLanguage();
                }else{
                    console.log(response.message);
                }
            }
        });
    }

    function get_address_building(buildingReference){
        return $.ajax({
            url: 'include/get_address_building.php',
            type: 'post',
            data: { "buildingReference": buildingReference},
            success: function(text){
            }
            })
    }

    function get_address_domicile(){
        <?php include 'include/connexion.php';
        $sql = "select aa.EMAIL, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY from customer_referential aa where aa.EMAIL='$user'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $conn->close();?>

        addressDomicile="<?php
        $address=$row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'];
        echo $address;?>";
        return addressDomicile;
    }

    function get_address_travail(){
        <?php include 'include/connexion.php';
        $sql = "select aa.EMAIL, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY from customer_referential aa where aa.EMAIL='$user'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $conn->close();?>

        addressTravail="<?php
        $address=$row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'];
        echo $address;?>";
        return addressTravail;

    }

    function get_meteo(timestamp, address){
        return $.ajax({
            url: 'include/meteo.php',
            type: 'post',
            data: { "timestamp": timestamp, "address": address}
        })
    }

    function get_travel_time(timestamp, address_start, address_end){
        return $.ajax({
            url: 'include/get_directions.php',
            type: 'post',
            data: {"timestamp": timestamp, "address_start": address_start, "address_end": address_end},
            success: function(text){
            }
        });
    }

    function get_kameo_score(weather, precipitation, temperature, wind_speed, travel_time_bike, travel_time_car){
        /* L'icone du temps est-elle vraiment nécessaire ? ne se baserions nous pas uniquement sur les chances de précipitation etc... ? Surtout que d'autres icones pourraient se rajouter dans le futur */
        var weather_score={clearday:10, rain:4, snow:0, sleet:2, wind:6, fog:6, cloudy:8, partlycloudyday:9, clearnight:10, partlycloudynight:9};
        var difference_travel_time= ( travel_time_car - travel_time_bike ) / (travel_time_bike);

        if (difference_travel_time > 0.2){
            travel_score=2;
        } else if (difference_travel_time > 0.1){
            travel_score=1;
        } else if (difference_travel_time < -0.2){
            travel_score=-2
        } else if (difference_travel_time < -0.1){
            travel_score=-1
        } else
        {
            travel_score=0;
        }

        if (travel_time_bike<10){
                travel_score=travel_score+3;
        }


        if (temperature > 30 || temperature < 5){
            temperature_score=-2;
        } else if (temperature > 25 || temperature < 10){
            temperature_score=-1;
        } else{
            temperature_score=0;
        }

        if (wind_speed > 20){
            wind_score=-3;
        } else if (wind_speed > 20){
            wind_score=-2;
        } else if (wind_speed>10){
            wind_score=-1;
        } else{
            wind_score=0;
        }


        kameo_score= (weather_score[weather]+travel_score+temperature_score+wind_score);
        if (kameo_score>10){
            kameo_score=10;
        } else if(kameo_score<0 || travel_time_bike > 120){
            kameo_score=0;
        }
        document.getElementById("score_kameo1").src="images/meteo/"+kameo_score+"_10.png";
        document.getElementById("score_kameo2").src="images/meteo/"+kameo_score+"_10.png";
        document.getElementById("score_kameo3").src="images/meteo/"+kameo_score+"_10.png";
        document.getElementById("score_kameo4").src="images/meteo/"+kameo_score+"_10.png";

        var image="images/meteo/"+kameo_score+"_10.png";

        return image;

    }


    function clickBikeDay(e){

        var email="<?php echo $user; ?>";
        var timestampDay=e.id;

        if (e.classList.contains("green")){
            e.classList.remove("green");
            var lien = e.getElementsByTagName("I")[0];
            lien.parentNode.removeChild(lien);
            $.ajax({
                url: 'include/calendar_management.php',
                type: 'post',
                data: { "email": email, "timestamp":timestampDay, action:"remove"},
                success: function(text){
                    if (text.response == 'error') {
                        console.log(text.message);
                    }
                }
            });
        }
        else{
            e.classList.add("green");
            var temp=e.innerHTML;
            e.innerHTML=temp+"<i class=\"fa fa-bicycle\"></i>";
            $.ajax({
                url: 'include/calendar_management.php',
                type: 'post',
                data: { "email": email, "timestamp":timestampDay, action:"add"},
                success: function(text){
                    if (text.response == 'error') {
                        console.log(text.message);
                    }
                }
            });
        }
    }

    function get_bills_listing(company, sent, paid, direction) {
        var email= "<?php echo $user; ?>";
        $.ajax({
            url: 'include/get_bills_listing.php',
            type: 'post',
            data: { "email": email, "company": company, "sent": sent, "paid": paid, "direction": direction},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){

                    $('#widget-addBill-form input[name=ID_OUT]').val(parseInt(response.IDMaxBillingOut) +1);
                    $('#widget-addBill-form input[name=ID]').val(parseInt(response.IDMaxBilling) +1);
                    $('#widget-addBill-form input[name=communication]').val(response.communication);
                    $('#widget-addBill-form input[name=communicationHidden]').val(response.communication);

                    var i=0;
                    var dest="";
                    if(response.update){
                        var temp="<a class=\"button small green button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_bills_listing(document.getElementsByClassName(\'billSelectionText\')[0].innerHTML, '*', '*', '*')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Toutes les factures ("+response.billNumberTotal+")</span></a><br/>";
                    }else{
                        var temp="<a class=\"button small green button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_bills_listing(document.getElementsByClassName(\'billSelectionText\')[0].innerHTML, '*', '*', '*')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Toutes les factures("+response.billNumberTotal+")</span></a>  <a class=\"button small red button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_bills_listing(document.getElementsByClassName(\'billSelectionText\')[0].innerHTML, '1', '0', '*')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Factures non payées ("+response.billINNumberNotPaid+")</span></a><br/>";
                    }
                    dest=dest.concat(temp);

                    if(response.update){
                        var temp="<a class=\"button small green button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_bills_listing(document.getElementsByClassName(\'billSelectionText\')[0].innerHTML, '*', '*', 'IN')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Factures émises ("+response.billINNumber+")</span></a> <a class=\"button small green button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_bills_listing(document.getElementsByClassName(\'billSelectionText\')[0].innerHTML, '0', '0', 'IN')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Factures émises non envoyées ("+response.billINNumberNotSent+")</span></a><a class=\"button small green button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_bills_listing(document.getElementsByClassName(\'billSelectionText\')[0].innerHTML, '1', '0', 'IN')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Facture émises envoyées mais non payées ("+response.billINNumberNotPaid+")</span></a><br /><a class=\"button small red button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_bills_listing(document.getElementsByClassName(\'billSelectionText\')[0].innerHTML, '*', '*', 'OUT')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Factures reçues ("+response.billOUTNumber+")</span></a> <a class=\"button small red button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_bills_listing(document.getElementsByClassName(\'billSelectionText\')[0].innerHTML, '*', '0', 'OUT')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Factures reçues non-payées  ("+response.billOUTNumberNotPaid+")</span></a><br/>";
                        dest=dest.concat(temp);
                        document.getElementsByClassName('companyBillSelection')[0].hidden=false;
                        document.getElementsByClassName('companyBillSelection')[1].hidden=false;

                        var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Vos Factures:</h4><h4 class=\"en-inline text-green\">Your Bills:</h4><h4 class=\"nl-inline text-green\">Your Bills:</h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addBill\" data-toggle=\"modal\" onclick=\"create_bill()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une facture</span></a><tbody><thead><tr><th>Type</th><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Date d'initiation</span><span class=\"en-inline\">Generation Date</span><span class=\"nl-inline\">Generation Date</span></th><th><span class=\"fr-inline\">Montant (HTVA)</span><span class=\"en-inline\">Amount (VAT ex.)</span><span class=\"nl-inline\">Amount (VAT ex.)</span></th><th><span class=\"fr-inline\">Communication</span><span class=\"en-inline\">Communication</span><span class=\"nl-inline\">Communication</span></th><th><span class=\"fr-inline\">Envoi ?</span><span class=\"en-inline\">Sent</span><span class=\"nl-inline\">Sent</span></th><th><span class=\"fr-inline\">Payée ?</span><span class=\"en-inline\">Paid ?</span><span class=\"nl-inline\">Paid ?</span></th><th><span class=\"fr-inline\">Limite de paiement</span><span class=\"en-inline\">Limit payment date</span><span class=\"nl-inline\">Limit payment date</span></th><th>Comptable ?</th><th></th></tr></thead>";
                    }else{
                        document.getElementsByClassName('companyBillSelection')[0].hidden=true;
                        document.getElementsByClassName('companyBillSelection')[1].hidden=true;

                        var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Vos Factures:</h4><h4 class=\"en-inline text-green\">Your Bills:</h4><h4 class=\"nl-inline text-green\">Your Bills:</h4><br/><tbody><thead><tr></th><th>ID</th><th><span class=\"fr-inline\">Date d'initiation</span><span class=\"en-inline\">Generation Date</span><span class=\"nl-inline\">Generation Date</span></th><th><span class=\"fr-inline\">Montant (HTVA)</span><span class=\"en-inline\">Amount (VAT ex.)</span><span class=\"nl-inline\">Amount (VAT ex.)</span></th><th><span class=\"fr-inline\">Communication</span><span class=\"en-inline\">Communication</span><span class=\"nl-inline\">Communication</span></th><th><span class=\"fr-inline\">Envoyée ?</span><span class=\"en-inline\">Sent ?</span><span class=\"nl-inline\">Sent ?</span></th><th><span class=\"fr-inline\">Payée ?</span><span class=\"en-inline\">Paid ?</span><span class=\"nl-inline\">Paid ?</span></th><th><span class=\"fr-inline\">Limite de paiement</span><span class=\"en-inline\">Limit payment date</span><span class=\"nl-inline\">Limit payment date</span></th></tr></thead>";

                    }
                    dest=dest.concat(temp);

                    if(response.update){
                        $.ajax({
                            url: 'include/get_companies_listing.php',
                            type: 'post',
                            data: {type: "*"},
                            success: function(response){
                                if(response.response == 'error') {
                                    console.log(response.message);
                                }
                                if(response.response == 'success'){
                                    var i=0;
                                    var dest2="";
                                    temp2="<li><a href=\"#\" onclick=\"billFilter('Choix de la société')\">Toutes les sociétés</a></li><li class=\"divider\"></li>";
                                    dest2=dest2.concat(temp2);
                                    while (i < response.companiesNumber){
                                        var temp2="<li><a href=\"#\" onclick=\"billFilter('"+response.company[i].internalReference+"')\">"+response.company[i].companyName+"</a></li>";
                                        dest2=dest2.concat(temp2);
                                        i++;

                                    }
                                    document.getElementsByClassName('billSelection')[0].innerHTML=dest2;

                                }
                            }
                        })
                    }
                    while (i < response.billNumber){
                        if(response.bill[i].sentDate==null){
                            var sendDate="N/A";
                        }else{
                            var sendDate=response.bill[i].sentDate.substr(0,10);
                        }
                        if(response.bill[i].paidDate==null){
                            var paidDate="N/A";
                        }else{
                            var paidDate=response.bill[i].paidDate.substr(0,10);
                        }
                        if(response.bill[i].sent=="0"){
                            var sent="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                        }else{
                            var sent="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                        }
                        if(response.bill[i].paid=="0"){
                            var paid="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                        }else{
                            var paid="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                        }

                        if(response.bill[i].limitPaidDate && response.bill[i].paid=="0"){
                            var dateNow=new Date();
                            var dateLimit=new Date(response.bill[i].limitPaidDate);

                              let month = String(dateLimit.getMonth() + 1);
                              let day = String(dateLimit.getDate());
                              let year = String(dateLimit.getFullYear());

                              if (month.length < 2) month = '0' + month;
                              if (day.length < 2) day = '0' + day;


                            if(dateNow>dateLimit){
                                var paidLimit="<span class=\"text-red\">"+day+"/"+month+"/"+year.substr(2,2)+"</span>";
                            }else{
                                var paidLimit="<span>"+day+"/"+month+"/"+year.substr(2,2)+"</span>";
                            }
                        }else if(response.bill[i].paid=="0"){
                            var paidLimit="<span class=\"text-red\">N/A</span>";
                        }else{
                            var paidLimit="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                        }



                        if(response.update && response.bill[i].amountHTVA>0){
                            var temp="<tr><th class=\"text-green\">IN</th>";
                        }else if(response.update && response.bill[i].amountHTVA<0){
                            var temp="<tr><th class=\"text-red\">OUT</th>";
                        }else{
                            var temp="<tr>";
                        }
                        dest=dest.concat(temp);

                        if(response.bill[i].fileName){
                            var temp="<th><a href=\"factures/"+response.bill[i].fileName+"\" target=\"_blank\">"+response.bill[i].ID+"</a></th>";
                        }
                        else{
                            var temp="<th><a href=\"#\" class=\"text-red\">"+response.bill[i].ID+"</a></th>";
                        }
                        dest=dest.concat(temp);
                        if(response.update && response.bill[i].amountHTVA>0){
                            var temp="<th>"+response.bill[i].company+"</a></th>";
                            dest=dest.concat(temp);
                        }else if(response.update && response.bill[i].amountHTVA<0){
                            var temp="<th>"+response.bill[i].beneficiaryCompany+"</a></th>";
                            dest=dest.concat(temp);
                        }
                        var temp="<th>"+response.bill[i].date.substr(0,10)+"</th><th>"+Math.round(response.bill[i].amountHTVA)+" €</th><th>"+response.bill[i].communication+"</th>";
                        dest=dest.concat(temp);

                        if(sent=="Y"){
                            var temp="<th class=\"text-green\">"+sendDate+"</th>";
                        }else{
                            var temp="<th class=\"text-red\">"+sent+"</th>";
                        }
                        dest=dest.concat(temp);

                        if(paid=="Y"){
                            var temp="<th class=\"text-green\">"+paidDate+"</th>";
                        }else{
                            var temp="<th class=\"text-red\">"+paid+"</th>";
                        }
                        dest=dest.concat(temp);


                        dest=dest.concat("<th>"+paidLimit+"</th>");


                        if(response.update){
                            if(response.bill[i].communicationSentAccounting=="1"){
                                var temp="<th class=\"text-green\">OK</th>";
                            }else{
                                var temp="<th class=\"text-red\">KO</th>";
                            }
                            dest=dest.concat(temp);
                        }

                        if(response.update){
                            temp="<th><ins><a class=\"text-green updateBillingStatus\" data-target=\"#updateBillingStatus\" name=\""+response.bill[i].ID+"\" data-toggle=\"modal\" href=\"#\">Update</a></ins></th>";
                            dest=dest.concat(temp);
                        }

                        dest=dest.concat("</tr>");
                        i++;

                    }
                    var temp="</tobdy></table>";
                    dest=dest.concat(temp);
                    document.getElementById('billsListing').innerHTML = dest;
                    document.getElementById('counterBills').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+(parseInt(response.billINNumberNotPaid)+parseInt(response.billOUTNumberNotPaid))+"\" data-from=\"0\" data-seperator=\"true\">"+(parseInt(response.billINNumberNotPaid)+parseInt(response.billOUTNumberNotPaid))+"</span>";

                    var classname = document.getElementsByClassName('updateBillingStatus');
                    for (var i = 0; i < classname.length; i++) {
                        classname[i].addEventListener('click', function() {construct_form_for_billing_status_update(this.name)}, false);
                    }
                    displayLanguage();

                }
            }
        })
    }
    function get_company_listing(type) {

        var email= "<?php echo $user; ?>";
        $.ajax({
            url: 'include/get_companies_listing.php',
            type: 'post',
            data: {"type": type},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var dest="";
                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Clients:</h4><h4 class=\"en-inline text-green\">Clients:</h4><h4 class=\"nl-inline text-green\">Clients:</h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addClient\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un client</span></a><br/><a class=\"button small green button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_company_listing('CLIENT')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Clients</span></a> <a class=\"button small orange button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_company_listing('PROSPECT')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Prospects</span></a><a class=\"button small orange button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_company_listing('ANCIEN PROSPECT')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Ancien Prospects</span></a><a class=\"button small red button-3d rounded icon-right\" data-toggle=\"modal\" onclick=\"get_company_listing('ANCIEN CLIENT')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa\"></i> Ancien clients</span></a><br/><tbody><thead><tr><th><span class=\"fr-inline\">Référence interne</span><span class=\"en-inline\">Internal reference</span><span class=\"nl-inline\">Internal reference</span></th><th><span class=\"fr-inline\">Client</span><span class=\"en-inline\">Client</span><span class=\"nl-inline\">Client</span></th><th><span class=\"fr-inline\"># vélos</span><span class=\"en-inline\"># bikes</span><span class=\"nl-inline\"># bikes</span></th><th><span class=\"fr-inline\">Accès vélos</span><span class=\"en-inline\">Bike Access</span><span class=\"nl-inline\">Bike Access</span></th><th><span class=\"fr-inline\">Accès Bâtiments</span><span class=\"en-inline\">Building Access</span><span class=\"nl-inline\">Building Access</span></th><th>Type</th></tr></thead>";
                    dest=dest.concat(temp);
                    var i=0;

                    while (i < response.companiesNumber){
                        temp="<tr><th><a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+response.company[i].ID+"\">"+response.company[i].internalReference+"</a></th><th>"+response.company[i].companyName+"</th><th>"+response.company[i].companyBikeNumber+"</th>";
                        dest=dest.concat(temp);

                        if(response.company[i].bikeAccessStatus=="OK"){
                            var temp="<th class=\"text-green\">"+response.company[i].bikeAccessStatus+"</th>";
                        }else{
                            var temp="<th class=\"text-red\">"+response.company[i].bikeAccessStatus+"</th>";
                        }
                        dest=dest.concat(temp);
                        if(response.company[i].customerBuildingAccess=="OK"){
                            var temp="<th class=\"text-green\">"+response.company[i].customerBuildingAccess+"</th>";
                        }else{
                            var temp="<th class=\"text-red\">"+response.company[i].customerBuildingAccess+"</th>";
                        }
                        dest=dest.concat(temp);

                        dest=dest.concat("<th>"+response.company[i].type+"</th>");

                        var temp="</tr>";
                        dest=dest.concat(temp);
                        i++;

                    }
                    var temp="</tobdy></table>";
                    dest=dest.concat(temp);
                    document.getElementById('companyListingSpan').innerHTML = dest;


                    document.getElementById('counterClients').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.companiesNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.companiesNumber+"</span>";

                    document.getElementById('cashFlowSpan').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+Math.round(response.sumContractsCurrent)+"\" data-from=\"0\" data-seperator=\"true\">"+Math.round(response.sumContractsCurrent)+"</span>";


                    var classname = document.getElementsByClassName('internalReferenceCompany');
                    for (var i = 0; i < classname.length; i++) {
                        classname[i].addEventListener('click', function() {get_company_details(this.name)}, false);
                    }
                    var classname = document.getElementsByClassName('updateCompany');
                    for (var i = 0; i < classname.length; i++) {
                        classname[i].addEventListener('click', function() {construct_form_for_company_update(this.name)}, false);
                    }
                    displayLanguage();

                }
            }
        })
    }

    function retrieve_offer(ID, action){
        $.ajax({
            url: 'include/offer_management.php',
            type: 'get',
            data: {"ID": ID, "action": "retrieve"},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){

                    if(action=="retrieve"){
                        $('#widget-offerManagement-form input').attr("readonly", true);
                        $('#widget-offerManagement-form textarea').attr("readonly", true);
                        $('#widget-offerManagement-form select').attr("readonly", true);
                    }else{
                        $('#widget-offerManagement-form input').attr("readonly", false);
                        $('#widget-offerManagement-form textarea').attr("readonly", false);
                        $('#widget-offerManagement-form select').attr("readonly", false);

                    }


                    $('#widget-offerManagement-form input[name=title]').val(response.title);
                    $('#widget-offerManagement-form textarea[name=description]').val(response.description);
                    $('#widget-offerManagement-form select[name=type]').val(response.type);
                    $('#widget-offerManagement-form select[name=status]').val(response.status);
                    $('#widget-offerManagement-form input[name=margin]').val(response.margin);
                    $('#widget-offerManagement-form input[name=probability]').val(response.probability);
                    $('#widget-offerManagement-form input[name=company]').val(response.company);
                    $('#widget-offerManagement-form input[name=action]').val(action);
                    $('#widget-offerManagement-form input[name=ID]').val(ID);

                    if($("#widget-offerManagement-form select[name=type]").val()=="achat"){
                        $("#widget-offerManagement-form input[name=start]").attr("readonly", true);
                        $("#widget-offerManagement-form input[name=end]").attr("readonly", true);
                        $("#widget-offerManagement-form input[name=start]").val("");
                        $("#widget-offerManagement-form input[name=end]").val("");

                    }else{
                        if(action!="retrieve"){
                            $("#widget-offerManagement-form input[name=start]").attr("readonly", false);
                            $("#widget-offerManagement-form input[name=end]").attr("readonly", false);
                        }

                        if(response.date){
                            $('#widget-offerManagement-form input[name=date]').val(response.date.substring(0,10));
                        }else{
                            $('#widget-offerManagement-form input[name=date]').val("");
                        }
                        if(response.start){
                            $('#widget-offerManagement-form input[name=start]').val(response.start.substring(0,10));
                        }else{
                            $('#widget-offerManagement-form input[name=start]').val("");
                        }
                        if(response.end){
                            $('#widget-offerManagement-form input[name=end]').val(response.end.substring(0,10));
                        }else{
                            $('#widget-offerManagement-form input[name=end]').val("");
                        }
                    }

                    if(response.amount){
                        $('#widget-offerManagement-form input[name=amount]').val(response.amount);
                    }

                }
            }
        })

    }

    function retrieve_task(ID, action){
        $.ajax({
            url: 'include/action_company.php',
            type: 'get',
            data: {"id": ID, "action": action},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
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
    function retrieve_cost(ID, action){
        $.ajax({
            url: 'include/costs_management.php',
            type: 'get',
            data: {"ID": ID, "action": "retrieve"},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    if(action=="retrieve"){
                        $('#widget-costsManagement-form input').attr("readonly", true);
                        $('#widget-costsManagement-form textarea').attr("readonly", true);
                        $('#widget-costsManagement-form select').attr("readonly", true);
                    }else{
                        $('#widget-costsManagement-form input').attr("readonly", false);
                        $('#widget-costsManagement-form textarea').attr("readonly", false);
                        $('#widget-costsManagement-form select').attr("readonly", false);

                    }


                    $('#widget-costsManagement-form input[name=title]').val(response.title);
                    $('#widget-costsManagement-form textarea[name=description]').val(response.description);
                    $('#widget-costsManagement-form select[name=type]').val(response.type);

                    if(response.start){
                        $('#widget-costsManagement-form input[name=start]').val(response.start.substring(0,10));
                    }

                    if($("#widget-costsManagement-form select[name=type]").val()=="one-shot"){
                        $("#widget-costsManagement-form input[name=end]").attr("readonly", true);
                        $("#widget-costsManagement-form input[name=end]").val("");


                    }else{
                        if(action!="retrieve"){
                            $("#widget-costsManagement-form input[name=start]").attr("readonly", false);
                            $("#widget-costsManagement-form input[name=end]").attr("readonly", false);
                        }

                        if(response.end){
                            $('#widget-costsManagement-form input[name=end]').val(response.end.substring(0,10));
                        }
                    }

                    $('#widget-costsManagement-form input[name=action]').val("update");
                    $('#widget-costsManagement-form input[name=ID]').val(ID);
                    if(response.amount){
                        $('#widget-costsManagement-form input[name=amount]').val(response.amount);
                    }

                }
            }
        })

    }

    function add_offer(company){
        $('#widget-offerManagement-form select[name=type]').val("leasing");
        $('#widget-offerManagement-form input[name=company]').val(company);
        $('#widget-offerManagement-form input[name=action]').val("add");
        $('#widget-offerManagement-form input').attr("readonly", false);
        $('#widget-offerManagement-form textarea').attr("readonly", false);
        $('#widget-offerManagement-form select').attr("readonly", false);
        document.getElementById('widget-offerManagement-form').reset();

    }
    function add_task(company){
        document.getElementById('widget-taskManagement-form').reset();
        $('#widget-taskManagement-form select[name=company]').val(company);
        $('#widget-taskManagement-form select[name=type]').val("contact");
        $('#widget-taskManagement-form input').attr("readonly", false);
        $('#widget-taskManagement-form textarea').attr("readonly", false);
        $('#widget-taskManagement-form select').attr("readonly", false);
        $('.taskManagementTitle').text("Ajouter une action");

    }

    function get_company_details(ID) {
        var email= "<?php echo $user; ?>";
        var internalReference;
        $.ajax({
            url: 'include/get_company_details.php',
            type: 'post',
            data: {"ID": ID},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                  $("#companyIdHidden").val(response.ID);
                  $('#companyIdTemplate').val(response.ID);
                    get_company_boxes(response.internalReference);

                    $('#widget-companyDetails-form input[name=ID]').val(response.ID);
                    document.getElementById('companyName').value = response.companyName;
                    document.getElementById('companyStreet').value = response.companyStreet;
                    document.getElementById('companyZIPCode').value = response.companyZIPCode;
                    document.getElementById('companyTown').value = response.companyTown;
                    document.getElementById('companyVAT').value = response.companyVAT;
                    document.getElementById('widget_companyDetails_internalReference').value=response.internalReference;
                    internalReference=response.internalReference;
                    $('#widget-companyDetails-form select[name=type]').val(response.type);
                    $('#widget-companyDetails-form input[name=email_billing]').val(response.emailContactBilling);
                    $('#widget-companyDetails-form input[name=firstNameContactBilling]').val(response.firstNameContactBilling);
                    $('#widget-companyDetails-form input[name=lastNameContactBilling]').val(response.lastNameContactBilling);
                    $('#widget-companyDetails-form input[name=phoneBilling]').val(response.phoneContactBilling);

                    var contactContent = `
                      <table class="table contactsTable">
                        <thead>
                          <tr>
                            <th><label class="fr">Email: </label><label class="en">Email: </label><label class="nl">Email: </label></th>
                            <th><label class="fr">Nom: </label><label class="en">Lastname: </label><label class="nl">Lastname: </label></th>
                            <th><label class="fr">Prénom: </label><label class="en">Firstname: </label><label class="nl">Firstname: </label></th>
                            <th><label class="fr">Téléphone: </label><label class="en">Phone: </label><label class="nl">Phone: </label></th>
                            <th><label class="fr">Fonction: </label><label class="en">Function: </label><label class="nl">Function: </label></th>
                            <th><label class="fr">Statistiques vélos: </label><label class="en">Bikes stats: </label><label class="nl">Bikes stats: </label></th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>`;
                    for (var i = 0; i < response.emailContact.length; i++) {
                      var contactId = (response.contactId[i] != undefined) ? response.contactId[i] : '';
                      var email = (response.emailContact[i] != undefined) ? response.emailContact[i] : '';
                      var lastName = (response.lastNameContact[i] != undefined) ? response.lastNameContact[i] : '';
                      var firstName = (response.firstNameContact[i] != undefined) ? response.firstNameContact[i] : '';
                      var phone = (response.phone[i] != undefined) ? response.phone[i] : '';
                      var fonction = (response.fonction[i] != undefined) ? response.fonction[i] : '';
                      var bikesStatsChecked = "";
                      if (response.bikesStats[i] == "Y") {
                        bikesStatsChecked = "checked";
                      }
                      contactContent += `
                      <tr class="form-group">
                        <td>
                          <input type="text" class="form-control required" readonly="true"  name="contactEmail`+response.contactId[i]+`" id="contactEmail`+response.contactId[i]+`" value="`+email+`" required/>
                        </td>
                        <td>
                        <input type="text" class="form-control required" readonly="true"  name="contactNom`+response.contactId[i]+`" id="contactNom`+response.contactId[i]+`" value="`+lastName+`" required/>
                        </td>
                        <td>
                        <input type="text" class="form-control required" readonly="true" name="contactPrenom`+response.contactId[i]+`" id="contactPrenom`+response.contactId[i]+`" value="`+firstName+`" required/>
                        </td>
                        <td>
                        <input type="tel" class="form-control" readonly="true"  name="contactPhone`+response.contactId[i]+`" id="contactPhone`+response.contactId[i]+`" value="`+phone+`"/>
                        </td>
                        <td>
                        <input type="text" class="form-control" readonly="true"  name="contactFunction`+response.contactId[i]+`" id="contactFunction`+response.contactId[i]+`" value="`+fonction+`"/>
                        </td>
                        <td>
                        <input type="checkbox" class="form-control" readonly="true"  name="contactBikesStats`+response.contactId[i]+`" id="contactBikesStats`+response.contactId[i]+`" value="bikesStats" `+bikesStatsChecked+`/>
                        </td>
                        <td>
                          <button class="modify button small green button-3d rounded icon-right glyphicon glyphicon-pencil"></button>
                        </td>
                        <td>
                          <button class="delete button small red button-3d rounded icon-right glyphicon glyphicon-remove"></button>
                        </td>
                        <input type="hidden" name="contactId`+response.contactId[i]+`" id="contactId`+response.contactId[i]+`" value="`+contactId+`" />
                      </tr>`;
                    }
                    contactContent += "</tbody></table>";
                    var contactInfo  = [];
                    var contactKeys = [];
                    $('.clientContactZone').append(contactContent);

                    $('.clientContactZone').on('click','.modify', function(){

                      $(this).removeClass('modify').addClass('validate').removeClass('glyphicon-pencil').addClass('glyphicon-ok');
                      $(this).parents('tr').find('.delete').removeClass('delete').removeClass('red').addClass('white').addClass('annuler').removeClass('glyphicon-remove').addClass('glyphicon-repeat');
                      $(this).parents('tr').find('input').each(function(){
                        contactInfo.push($(this).val());
                        contactKeys.push($(this).attr('id'));
                        $(this).prop('readonly', false);
                      });
                      console.log(contactInfo);
                    });

                    $('.clientContactZone').on('click','.annuler', function(){
                      $(this).parents('tr').find('.validate').removeClass('validate').addClass('modify').addClass('glyphicon-pencil').removeClass('glyphicon-ok');
                      $(this).removeClass('annuler').removeClass('white').addClass('delete').addClass('red').addClass('glyphicon-remove').removeClass('glyphicon-repeat');
                      $(this).parents('tr').find('input').each(function(){
                        var that = $(this);
                        for (var i = contactKeys.length -1; i >= 0; i--) {
                          //si l'id correspond a l'input
                          if (contactKeys[i] == $(that).attr('id')) {
                            //on remet l'ancienne valeur
                            $(that).val(contactInfo[i]);
                            //on retire l'entrée du tableau de clés
                            contactKeys.splice(i,1);
                            //on retire l'entrée du tableau contactInfo
                            contactInfo.splice(i,1);
                          }
                        }
                        $(that).prop('readonly', true);
                      });
                    });

                    $('.clientContactZone').on('click', '.validate', function(){
                      var valid = true;
                        $(this).parents('tr').find('input').each(function(){
                          //verification de la validité des champs
                          if (!$(this).valid()) {
                            valid = false;
                          }
                        });
                        if (valid) {
                          $(this).parents('tr').find('.validate').removeClass('validate').addClass('modify').addClass('glyphicon-pencil').removeClass('glyphicon-ok');
                          $(this).parents('tr').find('.annuler').removeClass('annuler').removeClass('white').addClass('delete').addClass('red').addClass('glyphicon-remove').removeClass('glyphicon-repeat');
                          $(this).parents('tr').find('input').each(function(){
                            //suppression des valeurs dans les tableaux
                            var that = $(this);
                            for (var i = contactKeys.length -1; i >= 0; i--) {
                              //si l'id correspond a l'input
                              if (contactKeys[i] == $(that).attr('id')) {
                                //on retire l'entrée du tableau de clés
                                contactKeys.splice(i,1);
                                //on retire l'entrée du tableau contactInfo
                                contactInfo.splice(i,1);
                              }
                            }
                            $(this).prop('readonly', true);
                          });
                        }else{ console.log('invalide');}

                    });

                    $('.clientContactZone').on('click', '.delete', function(){
                      if(confirm('Êtes-vous sur de vouloir supprimer ce contact ? Cette action est irréversible.')){
                        $(this).parents('tr').fadeOut(function(){
                          $(this).parents('tr').remove();
                        });
                      }
                    })

                    if(response.automaticBilling=="Y"){
                        $('#widget-companyDetails-form input[name=billing]').prop( "checked", true );
                    }else{
                        $('#widget-companyDetails-form input[name=billing]').prop( "checked", false );
                    }
                    if(response.automaticStatistics=="Y"){
                        $('#widget-companyDetails-form input[name=statistiques]').prop( "checked", true );
                    }else{
                        $('#widget-companyDetails-form input[name=statistiques]').prop( "checked", false );
                    }
                    if(response.assistance=='Y'){
                        $("#widget-companyDetails-form input[name=assistance]").prop( "checked", true );
                    }else{
                        $("#widget-companyDetails-form input[name=assistance]").prop( "checked", false );
                    }
                    if(response.locking=='Y'){
                        $("#widget-companyDetails-form input[name=locking]").prop( "checked", true );
                    }else{
                        $("#widget-companyDetails-form input[name=locking]").prop( "checked", false );
                    }


                    var i=0;
                    var dest="<a class=\"button small green button-3d rounded icon-right addBikeAdmin\" data-target=\"#bikeManagement\" data-toggle=\"modal\" href=\"#\" name=\""+response.ID+"\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un vélo</span></a>";
                    if(response.bikeNumber>0){
                        var temp="<table class=\"table\"><tbody><thead><tr><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th scope=\"col\"><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th scope=\"col\"><span class=\"fr-inline\">Facturation automatique</span><span class=\"en-inline\">Automatic billing ?</span><span class=\"nl-inline\">Automatic billing ?</span></th><th>Début</th><th>Fin</th><th scope=\"col\"><span class=\"fr-inline\">Montant leasing</span><span class=\"en-inline\">Leasing Price</span><span class=\"nl-inline\">Leasing Price</span></th><th scope=\"col\">Accès aux bâtiments</th></tr></thead>";
                        dest=dest.concat(temp);
                        while(i<response.bikeNumber){

                            if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractStart != null){
                                var contractStart="<span>"+response.bike[i].contractStart.substr(0,10)+"</span>";
                            }else if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractStart == null){
                                var contractStart="<span class=\"text-red\">N/A</span>";
                            }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractStart == null){
                                var contractStart="<span>N/A</span>";
                            }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractStart != null){
                                var contractStart="<span class=\"text-red\">"+response.bike[i].contractStart.substr(0,10)+"</span>";
                            }else{
                                var contractStart="<span class=\"text-red\">ERROR</span>";
                            }
                            if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractEnd != null){
                                var contractEnd="<span>"+response.bike[i].contractEnd.substr(0,10)+"</span>";
                            }else if(response.bike[i].company != 'KAMEO' && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractEnd == null){
                                var contractEnd="<span class=\"text-red\">N/A</span>";
                            }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractEnd == null){
                                var contractEnd="<span>N/A</span>";
                            }else if((response.bike[i].company == 'KAMEO' && response.bike[i].company == 'KAMEO VELOS TEST') && response.bike[i].contractEnd != null){
                                var contractEnd="<span class=\"text-red\">"+response.bike[i].contractEnd.substr(0,10)+"</span>";
                            }else{
                                var contractEnd="<span class=\"text-red\">ERROR</span>";
                            }


                            var temp="<tr><td scope=\"row\">"+response.bike[i].frameNumber+"</td><td>"+response.bike[i].model+"</td><td>"+response.bike[i].facturation+"</td><td>"+contractStart+"</td><td>"+contractEnd+"</td><td>"+response.bike[i].leasingPrice+"</td><td>";
                            dest=dest.concat(temp);

                            var j=0;
                            while(j<response.bike[i].buildingNumber){
                                var temp=response.bike[i].building[j].buildingCode+"<br/>"
                                dest=dest.concat(temp);
                                j++;
                            }
                            if(response.bike[i].buildingNumber==0){
                                var temp="<span class=\"text-red\">Non-défini</span>";
                                dest=dest.concat(temp);
                            }
                            dest=dest.concat("</td><td><ins><a class=\"text-green text-green updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+response.bike[i].frameNumber+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>");
                            i++;
                        }
                        dest=dest.concat("</tbody></table>");
                    }

                    document.getElementById('companyBikes').innerHTML = dest;


                    $('.updateBikeAdmin').click(function(){
                        construct_form_for_bike_status_updateAdmin(this.name);
                        $('#widget-bikeManagement-form input').attr('readonly', false);
                        $('#widget-bikeManagement-form select').attr('readonly', false);
                        $('.bikeManagementTitle').html('Modifier un vélo');
                        $('.bikeManagementSend').removeClass('hidden');
                    });

                    $('.addBikeAdmin').click(function(){
                        add_bike(this.name);
                        $('#widget-bikeManagement-form input').attr('readonly', false);
                        $('#widget-bikeManagement-form select').attr('readonly', false);
                        $('.bikeManagementTitle').html('Ajouter un vélo');
                        $('.bikeManagementSend').removeClass('hidden');
                    });


                    //Ajouter un bâtiment
                    var dest="<a class=\"button small green button-3d rounded icon-right\" data-target=\"#addBuilding\" data-toggle=\"modal\" onclick=\"add_building('"+response.internalReference+"')\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un bâtiment</span></a>";

                    if(response.buildingNumber>0){
                        var i=0;
                        var temp="<table class=\"table\"><tbody><thead><tr><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Reference</span><span class=\"nl-inline\">Reference</span></th><th scope=\"col\"><span class=\"fr-inline\">Description</span><span class=\"en-inline\">Description</span><span class=\"nl-inline\">Description</span></th><th scope=\"col\"><span class=\"fr-inline\">Adresse</span><span class=\"en-inline\">Address</span><span class=\"nl-inline\">Address</span></th></tr></thead>";
                        dest=dest.concat(temp);
                        while(i<response.buildingNumber){
                            var temp="<tr><td scope=\"row\">"+response.building[i].buildingReference+"</td><td>"+response.building[i].buildingFR+"</td><td>"+response.building[i].address+"</td></tr>";
                            dest=dest.concat(temp);
                            i++;
                        }
                        dest=dest.concat("</tbody></table>");
                    }

                    document.getElementById('companyBuildings').innerHTML = dest;


                    //Ajouter une offre

                    var dest="<a class=\"button small green button-3d rounded icon-right offerManagement addOffer\" name=\""+internalReference+"\" data-target=\"#offerManagement\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une offre</span></a>";
                    dest+="<a class=\"button small green button-3d rounded icon-right offerManagement getTemplate\" name=\""+internalReference+"\" data-target=\"#template\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Template Offre</span></a>";
                    if((response.offerNumber + response.bikeContracts)>0){
                        var i=0;
                        var temp="<table class=\"table\"><tbody><thead><tr><th scope=\"col\"><span class=\"fr-inline\">ID</span><span class=\"en-inline\">ID</span><span class=\"nl-inline\">ID</span></th><th scope=\"col\"><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th scope=\"col\"><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th scope=\"col\"><span class=\"fr-inline\">Chance</span><span class=\"en-inline\">Chance</span><span class=\"nl-inline\">Chance</span></th><th>Montant</th><th>Debut</th><th>Fin</th><th>Statut</th><th></th></tr></thead>";
                        dest=dest.concat(temp);
                        while(i<response.bikeContracts){
                            if(response.offer[i].description){
                                var description=response.offer[i].description;
                            }else{
                                var description="N/A";
                            }
                            if(response.offer[i].probability){
                                var probability=response.offer[i].probability;
                            }else{
                                var probability="N/A";
                            }
                            if(response.offer[i].amount){
                                var amount=response.offer[i].amount;
                            }else{
                                var amount="N/A";
                            }
                            if(response.offer[i].start){
                                var start=response.offer[i].start.substr(0,10);
                            }else{
                                var start="N/A";
                            }
                            if(response.offer[i].end){
                                var end=response.offer[i].end.substr(0,10);
                            }else{
                                var end="N/A";
                            }
                            if(response.offer[i].status){
                                var status=response.offer[i].status;
                            }else{
                                var status="N/A";
                            }

                            var temp="<tr><td>"+response.offer[i].id+"</td><td>Signé</td><td>"+description+"</td><td>"+probability+"</td><td>"+amount+"</td><td>"+start+"</td><td>"+end+"</td><td>"+status+"</td><td></td></tr>";
                            dest=dest.concat(temp);
                            i++;
                        }

                        while(i<(response.offerNumber + response.bikeContracts)){

                            if(!response.offer[i].date){
                                var date="?";
                            }else{
                                var date=response.offer[i].date.substr(0,10);
                            }
                            if(!response.offer[i].start){
                                var start="?";
                            }else{
                                var start=response.offer[i].start.substr(0,10);
                            }
                            if(!response.offer[i].end){
                                var end="?";
                            }else{
                                var end=response.offer[i].end.substr(0,10);
                            }

                            if(response.offer[i].type=="leasing"){
                                var amount = response.offer[i].amount+" €/mois";
                            }else{
                                var amount = response.offer[i].amount+" €";
                            }
                            if(response.offer[i].status){
                                var status=response.offer[i].status;
                            }else{
                                var status="N/A";
                            }


                            var temp="<tr><td><a href=\"#\" class=\"retrieveOffer\" data-target=\"#offerManagement\" data-toggle=\"modal\" name=\""+response.offer[i].id+"\">"+response.offer[i].id+"</a></td><td>"+date+"</td><td>"+response.offer[i].title+"</td><td>"+response.offer[i].probability+" %</td><td>"+amount+"</td><td>"+start+"</td><td>"+end+"</td><td>"+status+"</td><td><ins><a class=\"text-green offerManagement updateOffer\" data-target=\"#offerManagement\" name=\""+response.offer[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
                            dest=dest.concat(temp);
                            i++;
                        }
                        dest=dest.concat("</tbody></table>");
                    }
                    document.getElementById('companyContracts').innerHTML = dest;

                    $(".retrieveOffer").click(function() {
                        retrieve_offer(this.name, "retrieve");
                    });

                    $(".updateOffer").click(function() {
                        retrieve_offer(this.name, "update");
                    });
                    $(".addOffer").click(function() {
                        add_offer(this.name);
                        $('.offerManagementSendButton').removeClass("hidden");
                        $('.offerManagementSendButton').text("Ajouter")

                    });




                    //Ajouter un utilisateur
                    var dest="<a class=\"button small green button-3d rounded icon-right addUser\" data-target=\"#addUser\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un Utilisateur</span></a>";
                    if(response.userNumber>0){
                        var i=0;
                        var temp="<table class=\"table\"><tbody><thead><tr><th scope=\"col\"><span class=\"fr-inline\">Nom</span><span class=\"en-inline\">Name</span><span class=\"nl-inline\">Name</span></th><th scope=\"col\"><span class=\"fr-inline\">Prénom</span><span class=\"en-inline\">First Name</span><span class=\"nl-inline\">First Name</span></th><th scope=\"col\"><span class=\"fr-inline\">E-mail</span><span class=\"en-inline\">E-Mail</span><span class=\"nl-inline\">E-Mail</span></th></tr></thead>";
                        dest=dest.concat(temp);
                        while(i<response.userNumber){
                            var temp="<tr><td scope=\"row\">"+response.user[i].name+"</td><td>"+response.user[i].firstName+"</td><td>"+response.user[i].email+"</td></tr>";
                            dest=dest.concat(temp);
                            i++;
                        }
                        dest=dest.concat("</tbody></table>");
                    }
                    document.getElementById('companyUsers').innerHTML = dest;


                    $('.addUser').click(function(){
                        $('#widget-addUser-form input[name=company]').val(response.internalReference);


                    var company=response.internalReference;

                    $.ajax({
                        url: 'include/get_building_listing.php',
                        type: 'post',
                        data: { "company": response.internalReference},
                        success: function(response){
                            if(response.response == 'error') {
                                console.log(response.message);
                            }
                            if(response.response == 'success'){
                                var i=0;
                                var dest="";
                                while (i < response.buildingNumber){
                                    temp="<input type=\"checkbox\" name=\"buildingAccess[]\" checked value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"<br>";
                                    dest=dest.concat(temp);
                                    i++;

                                }
                                document.getElementById('buildingCreateUser').innerHTML = dest;

                                $.ajax({
                                    url: 'include/get_bikes_listing.php',
                                    type: 'post',
                                    data: { "company": company, "admin": "N"},
                                    success: function(response){
                                        if(response.response == 'error') {
                                            console.log(response.message);
                                        }
                                        if(response.response == 'success'){
                                            var i=0;
                                            var dest="";
                                            while (i < response.bikeNumber){
                                                temp="<input type=\"checkbox\" name=\"bikeAccess[]\" checked value=\""+response.bike[i].frameNumber+"\">"+response.bike[i].frameNumber+" "+response.bike[i].model+"<br>";
                                                dest=dest.concat(temp);
                                                i++;

                                            }
                                        document.getElementById('bikeCreateUser').innerHTML = dest;
                                        document.getElementById('confirmAddUser').innerHTML="<button class=\"fr button small green button-3d rounded icon-left\" onclick=\"confirm_add_user()\">\
                                                <i class=\"fa fa-paper-plane\">\
                                                </i>\
                                                Confirmer\
                                                </button>";

                                        }
                                    }
                                })
                            }
                        }
                    })
                    });

                    displayLanguage();
                }
            },error: function(response){

                    console.log(response);
            }
        }).done(function(){
            $.ajax({
                url: 'include/action_company.php',
                type: 'get',
                data: { "company": internalReference, "user": email},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{


                        var dest="<a href=\"#\" data-target=\"#taskManagement\" name=\""+internalReference+"\" data-toggle=\"modal\" class=\"button small green button-3d rounded icon-right addTask\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une action</span></a>";

                        if(response.actionNumber>0){
                            var i=0;
                            var temp="<table class=\"table table-condensed\"><tbody><thead><tr><th>ID</th><th><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th>Type</th><th><span class=\"fr-inline\">Titre</span><span class=\"en-inline\">Title</span><span class=\"nl-inline\">Title</span></th><th><span class=\"fr-inline\">Owner</span><span class=\"en-inline\">Owner</span><span class=\"nl-inline\">Owner</span></th><th><span class=\"fr-inline\">Statut</span><span class=\"en-inline\">Status</span><span class=\"nl-inline\">Status</span></th><th></th></tr></thead> ";
                            dest=dest.concat(temp);
                            while(i<response.actionNumber){
                                if(!(response.action[i].date_reminder)){
                                    $date_reminder="N/A"
                                }else{
                                    $date_reminder=response.action[i].date_reminder.substring(0,10);
                                }
                                var temp="<tr><td><a href=\"#\" class=\"retrieveTask\" data-target=\"#taskManagement\" data-toggle=\"modal\" name=\""+response.action[i].id+"\">"+response.action[i].id+"</a></td><td>"+response.action[i].date.substring(0,10)+"</td><td>"+response.action[i].type+"</td><td>"+response.action[i].title+"</td><td>"+response.action[i].ownerFirstName+" "+response.action[i].ownerName+"</td><td>"+response.action[i].status+"</td><td><ins><a class=\"text-green updateAction\" data-target=\"#updateAction\" name=\""+response.action[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
                                dest=dest.concat(temp);
                                i++;
                            }
                            dest=dest.concat("</tbody></table>");
                        }

                        $('#action_company_log').html(dest);



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



                        displayLanguage();

                        var classname = document.getElementsByClassName('updateAction');
                        for (var i = 0; i < classname.length; i++) {
                            classname[i].addEventListener('click', function() {construct_form_for_action_update(this.name)}, false);
                        }
                        list_kameobikes_member();


                    }

                }
            })
        })
    }

    function list_kameobikes_member(){
        $('#widget-addActionCompany-form select[name=owner]')
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
                        $('#widget-addActionCompany-form select[name=owner]').append("<option value="+response.member[i].email+">"+response.member[i].firstName+" "+response.member[i].name+"<br>");
                        i++;
                    }
                    $('#widget-addActionCompany-form select[name=owner]').val('julien.jamar@kameobikes.com');

                }
            }
        })
    }

    function add_bike(ID){
        $('.bikeManagementPicture').addClass('hidden');
        $('.bikeActions').addClass('hidden');
        document.getElementById('addBike_firstBuilding').innerHTML = "";
        document.getElementById('widget-bikeManagement-form').reset();

        $('#widget-bikeManagement-form input[name=action]').val("add");
        $('#widget-bikeManagement-form select[name=contractType]').val("");
        $('#widget-bikeManagement-form select[name=billingType]').val("monthly");
        $('#widget-bikeManagement-form select[name=portfolioID]')
            .find('option')
            .remove()
            .end()
        ;

        $.ajax({
                url: 'include/load_portfolio.php',
                type: 'get',
                data: {"action": "list"},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{
                        var i=0;
                        while(i<response.bikeNumber){
                            $('#widget-bikeManagement-form select[name=portfolioID]').append("<option value="+response.bike[i].ID+">"+response.bike[i].brand+" - "+response.bike[i].model+" - "+response.bike[i].frameType+"<br>");
                            i++;
                        }
                        $('#widget-bikeManagement-form select[name=portfolioID]').val("");

                    }
                }
        })

        $('#widget-bikeManagement-form select[name=portfolioID]').change(function(){
            $.ajax({
                url: 'include/load_portfolio.php',
                type: 'get',
                data: {"ID": $('#widget-bikeManagement-form select[name=portfolioID]').val(), "action": "retrieve"},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{
                        $('#widget-bikeManagement-form input[name=price]').val(response.buyingPrice);
                    }
                }
            })
        });


        $('#widget-bikeManagement-form select[name=company]').val("");


        var buildingNumber;
        var company;

        if(ID){
            $.ajax({
                url: 'include/get_company_details.php',
                type: 'post',
                data: { "ID": ID},
                success: function(response){
                    if(response.response == 'error') {
                        console.log(response.message);
                    }
                    if(response.response == 'success'){
                        buildingNumber=response.buildingNumber;
                        company=response.internalReference;
                        $('#widget-boxManagement-form select[name=company]').val(company);

                        if(buildingNumber==0){
                            $.notify({
                                message: "Veuillez d'abord définir au moins un bâtiment"
                            }, {
                                type: 'danger'
                            });
                        }
                    }
                }
            }).done(function(){
                $.ajax({
                    url: 'include/get_building_listing.php',
                    type: 'post',
                    data: { "company": company},
                    success: function(response){
                        if(response.response == 'error') {
                            console.log(response.message);
                        }
                        if(response.response == 'success'){
                            var i=0;
                            var dest="";
                            var dest2="<label for=\"firstBuilding\">Bâtiment pour initialisation</label><select name=\"firstBuilding\">";

                            while (i < response.buildingNumber){
                                temp="<input type=\"checkbox\" name=\"buildingAccess[]\" checked value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"<br>";
                                dest=dest.concat(temp);
                                temp2="<option value=\""+response.building[i].code+"\">"+response.building[i].descriptionFR+"</option>";
                                dest2=dest2.concat(temp2);
                                i++;

                            }
                            dest2=dest2.concat("</select>");
                            document.getElementById('bikeBuildingAccessAdmin').innerHTML = dest;
                            document.getElementById('addBike_firstBuilding').innerHTML = dest2;
                        }
                    }
                })

                $.ajax({
                    url: 'include/get_users_listing.php',
                    type: 'post',
                    data: { "company": company},
                    success: function(response){
                        if(response.response == 'error') {
                            console.log(response.message);
                        }
                        if(response.response == 'success'){
                            var i=0;
                            var dest="";
                            while (i < response.usersNumber){
                                temp="<input type=\"checkbox\" name=\"userAccess[]\" checked value=\""+response.user[i].email+"\">"+response.user[i].firstName+" - "+response.user[i].name+"<br>";
                                dest=dest.concat(temp);
                                i++;

                            }
                            document.getElementById('bikeUserAccessAdmin').innerHTML = dest;
                        }
                    }
                })
                $('#widget-bikeManagement-form select[name=company]').val(company);




            })
        }
        $('#widget-bikeManagement-form select[name=company]').change(function(){
            updateAccessAdmin($('#widget-bikeManagement-form input[name=frameNumber]').val(), $('#widget-bikeManagement-form select[name=company]').val());
        });


    }


    function add_box(company){
        document.getElementById('widget-boxManagement-form').reset();
        $('#widget-boxManagement-form input').attr("readonly", false);
        $('#widget-boxManagement-form textarea').attr("readonly", false);
        $('#widget-boxManagement-form select').attr("readonly", false);

        $('#widget-boxManagement-form input[name=action]').val("add");
        $('#widget-boxManagement-form-title').text("Ajouter une borne");


        $('#widget-boxManagement-form-send').text("Ajouter");
        $('#widget-boxManagement-form-send').removeClass("hidden");
        $('#widget-boxManagement-form select[name=company]').val(company);

    }


    function update_box(id){
        retrieve_box(id);
        $('#widget-boxManagement-form-send').removeClass("hidden");

        $('#widget-boxManagement-form input').attr("readonly", false);
        $('#widget-boxManagement-form textarea').attr("readonly", false);
        $('#widget-boxManagement-form select').attr("readonly", false);
        $('#widget-boxManagement-form input[name=action]').val("update");


        $('#widget-boxManagement-form input[name=action]').val("update");
        $('#widget-boxManagement-form-title').text("Modifier une borne");
        $('#widget-boxManagement-form-send').text("Modifier");

    }


    function retrieve_box(id){
        $('#widget-boxManagement-form-title').text("Informations de la borne");
        $('#widget-boxManagement-form-send').addClass("hidden");
        $('#widget-boxManagement-form input').attr("readonly", true);
        $('#widget-boxManagement-form textarea').attr("readonly", true);
        $('#widget-boxManagement-form select').attr("readonly", true);

        $.ajax({
            url: 'include/box_management.php',
            type: 'get',
            data: {"action": "retrieve", "id": id},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    $('#widget-boxManagement-form input[name=id]').val(response.id);
                    $('#widget-boxManagement-form input[name=reference]').val(response.reference);
                    $('#widget-boxManagement-form select[name=boxModel]').val(response.model);
                    $('#widget-boxManagement-form select[name=company]').val(response.company);
                    $('#widget-boxManagement-form input[name=amount]').val(response.amount);
                    $('#widget-boxManagement-form input[name=billingGroup]').val(response.billing_group);
                    if(response.start){
                        $('#widget-boxManagement-form input[name=contractStart]').val(response.start.substr(0,10));
                    }else{
                        $('#widget-boxManagement-form input[name=contractStart]').val("");
                    }
                    if(response.end){
                        $('#widget-boxManagement-form input[name=contractEnd]').val(response.end.substr(0,10));
                    }else{
                        $('#widget-boxManagement-form input[name=contractEnd]').val("");
                    }

                    if(response.automatic_billing=="Y"){
                        $('#widget-boxManagement-form input[name=billing]').prop("checked", true);
                    }else{
                        $('#widget-boxManagement-form input[name=billing]').prop("checked", false);
                    }

                }
            }
        })
    }

    function get_company_boxes(company){


        $.ajax({
            url: 'include/box_management.php',
            type: 'get',
            data: {"action": "list", "company": company},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="<a class=\"button small green button-3d rounded icon-right addBox\" name=\""+company+"\" data-target=\"#boxManagement\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une borne</span></a>";
                    if(response.boxesNumber>0){
                        var temp="<table class=\"table\"><tbody><thead><tr><th>ID</th><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Reference</span><span class=\"nl-inline\">Reference</span></th><th scope=\"col\"><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th scope=\"col\"><span class=\"fr-inline\">Facturation automatique</span><span class=\"en-inline\">Automatic billing ?</span><span class=\"nl-inline\">Automatic billing ?</span></th><th>Début</th><th>Fin</th><th scope=\"col\"><span class=\"fr-inline\">Montant leasing</span><span class=\"en-inline\">Leasing Price</span><span class=\"nl-inline\">Leasing Price</span></th><th></th></tr></thead>";
                        dest=dest.concat(temp);

                        while (i < response.boxesNumber){

                            if(response.box[i].automatic_billing==null || response.box[i].automatic_billing=="N"){
                                automatic_billing='N';
                            }else{
                                automatic_billing='Y';
                            }

                            if(response.box[i].amount==null){
                                amount="0 €/mois";
                            }else{
                                amount=response.box[i].amount+" €/mois";
                            }



                            if(response.box[i].company != 'KAMEO' && response.box[i].company != 'KAMEO VELOS TEST' && response.box[i].start != null){
                                var start="<span>"+response.box[i].start.substr(0,10)+"</span>";
                            }else if(response.box[i].company != 'KAMEO' && response.box[i].company != 'KAMEO VELOS TEST' && response.box[i].start == null){
                                var start="<span class=\"text-red\">N/A</span>";
                            }else if((response.box[i].company == 'KAMEO' && response.box[i].company == 'KAMEO VELOS TEST') && response.box[i].start == null){
                                var start="<span>N/A</span>";
                            }else if((response.box[i].company == 'KAMEO' && response.box[i].company == 'KAMEO VELOS TEST') && response.box[i].start != null){
                                var start="<span class=\"text-red\">"+response.box[i].start.substr(0,10)+"</span>";
                            }else{
                                var start="<span class=\"text-red\">ERROR</span>";
                            }
                            if(response.box[i].company != 'KAMEO' && response.box[i].company != 'KAMEO VELOS TEST' && response.box[i].end != null){
                                var end="<span>"+response.box[i].end.substr(0,10)+"</span>";
                            }else if(response.box[i].company != 'KAMEO' && response.box[i].company != 'KAMEO VELOS TEST' && response.box[i].end == null){
                                var end="<span class=\"text-red\">N/A</span>";
                            }else if((response.box[i].company == 'KAMEO' && response.box[i].company == 'KAMEO VELOS TEST') && response.box[i].end == null){
                                var end="<span>N/A</span>";
                            }else if((response.box[i].company == 'KAMEO' && response.box[i].company == 'KAMEO VELOS TEST') && response.box[i].end != null){
                                var end="<span class=\"text-red\">"+response.box[i].end.substr(0,10)+"</span>";
                            }else{
                                var end="<span class=\"text-red\">ERROR</span>";
                            }







                            temp="<tr><td><a href=\"#\" class=\"text-green retrieveBox\" data-target=\"#boxManagement\" name=\""+response.box[i].id+"\" data-toggle=\"modal\">"+response.box[i].id+"</a></td><td>"+response.box[i].reference+"</td><td>"+response.box[i].model+"</td><td>"+automatic_billing+"</td><td>"+start+"</td><td>"+end+"</td><td>"+amount+"</td><td><a href=\"#\" class=\"text-green updateBox\" data-target=\"#boxManagement\" name=\""+response.box[i].id+"\" data-toggle=\"modal\">Mettre à jour </a></th></tr>";
                            dest=dest.concat(temp);
                            i++;
                        }

                        var temp="</tbody></table>";
                        dest=dest.concat(temp);
                    }


                    $('#companyBoxes').html(dest);
                    $('.addBox').click(function(){
                        add_box(this.name);
                    });
                    $('.updateBox').click(function(){
                        update_box(this.name);
                    });
                    $('.retrieveBox').click(function(){
                        retrieve_box(this.name);
                    });


                }
            }
        })



    }


    function add_building(company){
            $.ajax({
            url: 'include/get_bikes_listing.php',
            type: 'post',
            data: { "company": company},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="";
                    while (i < response.bikeNumber){
                        temp="<input type=\"checkbox\" name=\"bikeAccess[]\" checked value=\""+response.bike[i].frameNumber+"\">"+response.bike[i].frameNumber+" - "+response.bike[i].model+"<br>";
                        dest=dest.concat(temp);
                        i++;

                    }
                    document.getElementById('addBuilding_bikeListing').innerHTML = dest;
                }
            }
        })
        $.ajax({
            url: 'include/get_users_listing.php',
            type: 'post',
            data: { "company": company},
            success: function(response){
                if(response.response == 'error') {
                    console.log(response.message);
                }
                if(response.response == 'success'){
                    var i=0;
                    var dest="";
                    while (i < response.usersNumber){
                        temp="<input type=\"checkbox\" name=\"userAccess[]\" checked value=\""+response.user[i].email+"\">"+response.user[i].firstName+" - "+response.user[i].name+"<br>";
                        dest=dest.concat(temp);
                        i++;

                    }
                    document.getElementById('addBuilding_usersListing').innerHTML = dest;
                }
            }
        })
        document.getElementById('widget-addBuilding-form-company').value = company;

    }


    function deconnexion(){
        <?php $_SESSION['login']="false"; ?>
        window.location.href = "http://www.kameobikes.com/index.php";
    }
    </script>

    <!-- CONTENT -->
    <section class="content">
        <div class="container">
            <div class="row">


                <!-- post content -->
                <div class="post-content float-right col-md-9">
                    <!-- Post item-->
                    <div class="post-item">
                        <div class="post-content-details">
                            <div class="heading heading text-left m-b-20">
                                <h2>MY KAMEO</h2>
                            </div>
                            <br />
                            <div class="col-md-12">

                            <span id="assistanceSpan"></span>

                            <?php if(!$company){
                            ?>

                            <a class="button small green button-3d rounded icon-right" data-target="#calendrier" data-toggle="modal" href="#">
                            <span class="fr-inline">Mon calendrier</span>
                            <span class="en-inline">My calendar</span>
                            <span class="nl-inline">Mijn kalender</span>
                            </a>
                            <?php
                            }
                            ?>
                            </div>
                            <br>
                            <?php
                            if ($company){

    ?>


                   <!--ce form ci permet de ne pas avoir un bug.-->
                   <form action="#" method="post">
                   </form>

                    <div class="col-md-12">
                        <div id="tabs-05c" class="tabs color tabs radius">
                            <ul id="mainTab" class="tabs-navigation">
                                <li class="reserver active fr"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Réserver un vélo</a> </li>
                                <li class="reserver active en"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Book a bike</a> </li>
                                <li class="reserver active nl"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Boek een fiets</a> </li>
                                <li class="fr"><a href="#reservations" class="reservations"><i class="fa fa-check-square-o"></i>Vos réservations</a> </li>
                                <li class="en"><a href="#reservations" class="reservations"><i class="fa fa-check-square-o"></i>Your bookings</a> </li>
                                <li class="nl"><a href="#reservations" class="reservations"><i class="fa fa-check-square-o"></i>Uw boekingen</a> </li>
                                <li class="fr hidden fleetmanager"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-user"></i>Fleet manager</a> </li>
                                <li class="en hidden fleetmanager"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-user"></i>Fleet manager</a> </li>
                                <li class="nl hidden fleetmanager"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-user"></i>Fleet manager</a> </li>
<!--                                    <li class="fr"><a href="#routes" class="routes"><i class="fa fa-road"></i>Itinéraires</a> </li>
                                    <li class="en"><a href="#routes" class="routes"><i class="fa fa-road"></i>Roads</a> </li>
                                    <li class="nl"><a href="#routes" class="routes"><i class="fa fa-road"></i>Routes</a> </li>-->
                            </ul>

                            <div class="tabs-content">
                                <div class="tab-pane active" id="reserver">
                                    <form id="search-bikes-form" action="include/search-bikes.php" method="post">
                                        <div class="form-group">
                                           <label for="booking_day_form" class="col-sm-12 fr">A quelle date voulez-vous prendre le vélo ?</label>
                                           <label for="booking_day_form" class="col-sm-12 en">When do you want to book a bike ?</label>
                                           <label for="booking_day_form" class="col-sm-12 nl">Wanneer wil je een fiets boeken?</label>
                                            <div class="form-group col-sm-5" id="booking_day_form"></div>

                                            <div class="form-group col-sm-5">
                                                 <select id="search-bikes-form-intake-hour" name="search-bikes-form-intake-hour" class="form-control">
                                                    <option value="8h00">8h00</option>
                                                    <option value="8h15">8h15</option>
                                                    <option value="8h30">8h30</option>
                                                    <option value="8h45">8h45</option>
                                                    <option value="9h00">9h00</option>
                                                    <option value="9h15">9h15</option>
                                                    <option value="9h30">9h30</option>
                                                    <option value="9h45">9h45</option>
                                                    <option value="10h00">10h00</option>
                                                    <option value="10h15">10h15</option>
                                                    <option value="10h30">10h30</option>
                                                    <option value="10h45">10h45</option>
                                                    <option value="11h00">11h00</option>
                                                    <option value="11h15">11h15</option>
                                                    <option value="11h30">11h30</option>
                                                    <option value="11h45">11h45</option>
                                                    <option value="12h00">12h00</option>
                                                    <option value="12h15">12h15</option>
                                                    <option value="12h30">12h30</option>
                                                    <option value="12h45">12h45</option>
                                                    <option value="13h00">13h00</option>
                                                    <option value="13h15">13h15</option>
                                                    <option value="13h30">13h30</option>
                                                    <option value="13h45">13h45</option>
                                                    <option value="14h00">14h00</option>
                                                    <option value="14h15">14h15</option>
                                                    <option value="14h30">14h30</option>
                                                    <option value="14h45">14h45</option>
                                                    <option value="15h00">15h00</option>
                                                    <option value="15h15">15h15</option>
                                                    <option value="15h30">15h30</option>
                                                    <option value="15h45">15h45</option>
                                                    <option value="16h00">16h00</option>
                                                    <option value="16h15">16h15</option>
                                                    <option value="16h30">16h30</option>
                                                    <option value="16h45">16h45</option>
                                                    <option value="17h00">17h00</option>
                                                    <option value="17h15">17h15</option>
                                                    <option value="17h30">17h30</option>
                                                    <option value="17h45">17h45</option>
                                                    <option value="18h00">18h00</option>
                                                    <option value="18h15">18h15</option>
                                                    <option value="18h30">18h30</option>
                                                    <option value="18h45">18h45</option>
                                                  </select>
                                            </div>
                                           <label for="booking_day_form_deposit" class="col-sm-12 fr">A quelle date voulez-vous rendre le vélo ?</label>
                                           <label for="booking_day_form_deposit" class="col-sm-12 en">When do you want to deposit the bike?</label>
                                           <label for="booking_day_form_deposit" class="col-sm-12 nl">Wanneer wil je de fiets storten?</label>
                                            <div class="form-group col-sm-5" id="booking_day_form_deposit"></div>
                                            <div class="form-group col-sm-5" id="booking_hour_form_deposit">
                                            </div>

                                            <div class="form-group col-sm-5" id="start_building_form"></div>
                                            <div class="form-group col-sm-5" id="deposit_building_form"></div>
                                        </div>
                                        <input type="text" class="hidden" id="search-bikes-form-email" name="search-bikes-form-email" value="<?php echo $user; ?>" />
                                        <input type="text" class="hidden" id="search-bikes-form-maxBookingPerYear" name="search-bikes-form-maxBookingPerYear" />
                                        <input type="text" class="hidden" id="search-bikes-form-maxBookingPerMonth" name="search-bikes-form-maxBookingPerMonth"/>

                                        <br />
                                        <div class="form-group col-sm-6">
                                            <button class="button effect fill fr" type="submit">Rechercher</button>
                                            <button class="button effect fill en" type="submit">Search</button>
                                            <button class="button effect fill nl" type="submit">Zoeken</button>
                                        </div>
                                    </form>

                                    <script type="text/javascript">
                                        loadClientConditions()
                                        .done(function(response){
                                            constructSearchForm(response.clientConditions.bookingDays, response.clientConditions.bookingLength, response.clientConditions.administrator, response.clientConditions.assistance, response.clientConditions.hourStartIntakeBooking, response.clientConditions.hourEndIntakeBooking, response.clientConditions.hourStartDepositBooking, response.clientConditions.hourEndDepositBooking, response.clientConditions.mondayIntake, response.clientConditions.tuesdayIntake, response.clientConditions.wednesdayIntake, response.clientConditions.thursdayIntake, response.clientConditions.fridayIntake, response.clientConditions.saturdayIntake, response.clientConditions.sundayIntake, response.clientConditions.mondayDeposit, response.clientConditions.tuesdayDeposit, response.clientConditions.wednesdayDeposit, response.clientConditions.thursdayDeposit, response.clientConditions.fridayDeposit, response.clientConditions.saturdayDeposit, response.clientConditions.sundayDeposit, response.clientConditions.maxBookingsPerYear, response.clientConditions.maxBookingsPerMonth);
                                            if (response.clientConditions.administrator == "Y"){
                                                    $(".fleetmanager").removeClass("hidden");
                                            }

                                        });
                                    </script>

                                    <script type="text/javascript">
                                        jQuery("#search-bikes-form").validate({
                                            submitHandler: function(form) {
                                                jQuery(form).ajaxSubmit({
                                                    success: function(text) {
                                                        if (text.response == 'error') {

                                                            $.notify({
                                                                message: text.message
                                                            }, {
                                                                type: 'danger'
                                                            });
                                                            document.getElementById('velos').innerHTML = "";
                                                            document.getElementById("travel_information").style.display = "none";
                                                            document.getElementById("velos").style.display = "none";



                                                        } else {
                                                            var loaded1=false;
                                                            var loaded2=false;
                                                            $("body").addClass("loading");
                                                            document.getElementById("travel_information").style.display = "none";
                                                            document.getElementById("velos").style.display = "none";


                                                            var successMessage=text.message;

                                                            var weather;
                                                            var temperature;
                                                            var precipitation;
                                                            var windSpeed;
                                                            var travel_time_bike;
                                                            var travel_time_car;

                                                            get_address_building(text.buildingStart)
                                                                .done(function(response){
                                                                    addressStart=response.address;
                                                                    buildingStartFr=response.building_fr;
                                                                    buildingStartEn=response.building_en;
                                                                    buildingStartNl=response.building_nl;
                                                                    get_address_building(text.buildingEnd)
                                                                    .done(function(response){
                                                                        addressEnd=response.address;
                                                                        buildingEndFr=response.building_fr;
                                                                        buildingEndEn=response.building_en;
                                                                        buildingEndNl=response.building_nl;

                                                                        document.getElementById("meteoStart1FR").innerHTML=buildingStartFr;
                                                                        document.getElementById("meteoStart2FR").innerHTML=buildingStartFr;
                                                                        document.getElementById("meteoStart3FR").innerHTML=buildingStartFr;
                                                                        document.getElementById("meteoStart4FR").innerHTML=buildingStartFr;
                                                                        document.getElementById("meteoStart1EN").innerHTML=buildingStartEn;
                                                                        document.getElementById("meteoStart2EN").innerHTML=buildingStartEn;
                                                                        document.getElementById("meteoStart3EN").innerHTML=buildingStartEn;
                                                                        document.getElementById("meteoStart4EN").innerHTML=buildingStartEn;
                                                                        document.getElementById("meteoStart1NL").innerHTML=buildingStartNl;
                                                                        document.getElementById("meteoStart2NL").innerHTML=buildingStartNl;
                                                                        document.getElementById("meteoStart3NL").innerHTML=buildingStartNl;
                                                                        document.getElementById("meteoStart4NL").innerHTML=buildingStartNl;
                                                                        document.getElementById("meteoEnd1FR").innerHTML=buildingEndFr;
                                                                        document.getElementById("meteoEnd2FR").innerHTML=buildingEndFr;
                                                                        document.getElementById("meteoEnd3FR").innerHTML=buildingEndFr;
                                                                        document.getElementById("meteoEnd4FR").innerHTML=buildingEndFr;
                                                                        document.getElementById("meteoEnd1EN").innerHTML=buildingEndEn;
                                                                        document.getElementById("meteoEnd2EN").innerHTML=buildingEndEn;
                                                                        document.getElementById("meteoEnd3EN").innerHTML=buildingEndEn;
                                                                        document.getElementById("meteoEnd4EN").innerHTML=buildingEndEn;
                                                                        document.getElementById("meteoEnd1NL").innerHTML=buildingEndNl;
                                                                        document.getElementById("meteoEnd2NL").innerHTML=buildingEndNl;
                                                                        document.getElementById("meteoEnd3NL").innerHTML=buildingEndNl;
                                                                        document.getElementById("meteoEnd4NL").innerHTML=buildingEndNl;

                                                                        date= new Date(text.timestampStartBooking * 1000);

                                                                        var day=date.getDate();
                                                                        var month=date.getMonth() + 1;
                                                                        var year=date.getFullYear();
                                                                        var hours= date.getHours();
                                                                        var minutes= date.getMinutes();
                                                                        minutes=minutes.toString();

                                                                        if (minutes.length ==1){
                                                                            minutes="0"+minutes;
                                                                        }

                                                                        document.getElementById('meteoDate1').innerHTML = day+"/"+ month+"/"+year;
                                                                        document.getElementById('meteoDate2').innerHTML = day+"/"+ month+"/"+year;
                                                                        document.getElementById('meteoDate3').innerHTML = day+"/"+ month+"/"+year;
                                                                        document.getElementById('meteoDate4').innerHTML = day+"/"+ month+"/"+year;

                                                                        document.getElementById("meteoHour1").innerHTML=hours+"h"+minutes;
                                                                        document.getElementById("meteoHour2").innerHTML=hours+"h"+minutes;
                                                                        document.getElementById("meteoHour3").innerHTML=hours+"h"+minutes;
                                                                        document.getElementById("meteoHour4").innerHTML=hours+"h"+minutes;
                                                                        get_meteo(text.timestampStartBooking, addressStart)
                                                                        .done(function(response){
                                                                            if(response.response=="success")
                                                                            {
                                                                                var find = '-';
                                                                                var re = new RegExp(find, 'g');

                                                                                weather=response.icon.replace(re,"");
                                                                                temperature=response.temperature;
                                                                                precipitation=response.precipProbability;
                                                                                windSpeed=response.windSpeed;

                                                                                document.getElementById("logo_meteo1").src="images/meteo/"+weather+".png";
                                                                                document.getElementById('temperature_widget1').innerHTML = Math.round(temperature)+" °C";
                                                                                document.getElementById('precipitation_widget1').innerHTML = Math.round(precipitation)+" %";
                                                                                document.getElementById('wind_widget1').innerHTML = windSpeed+" m/s";
                                                                                document.getElementById("logo_meteo2").src="images/meteo/"+weather+".png";
                                                                                document.getElementById('temperature_widget2').innerHTML = Math.round(temperature)+" °C";
                                                                                document.getElementById('precipitation_widget2').innerHTML = Math.round(precipitation)+" %";
                                                                                document.getElementById('wind_widget2').innerHTML = windSpeed+" m/s";
                                                                                document.getElementById("logo_meteo3").src="images/meteo/"+weather+".png";
                                                                                document.getElementById('temperature_widget3').innerHTML = Math.round(temperature)+" °C";
                                                                                document.getElementById('precipitation_widget3').innerHTML = Math.round(precipitation)+" %";
                                                                                document.getElementById('wind_widget3').innerHTML = windSpeed+" m/s";
                                                                                document.getElementById("logo_meteo4").src="images/meteo/"+weather+".png";
                                                                                document.getElementById('temperature_widget4').innerHTML = Math.round(temperature)+" °C";
                                                                                document.getElementById('precipitation_widget4').innerHTML = Math.round(precipitation)+" %";
                                                                                document.getElementById('wind_widget4').innerHTML = windSpeed+" m/s";
                                                                                get_travel_time(text.timestampStartBooking, addressStart, addressEnd)
                                                                                .done(function(response){
                                                                                    travel_time_bike=response.duration_bike;
                                                                                    travel_time_car=response.duration_car;
                                                                                    document.getElementById('walking_duration_widget1').innerHTML = response.duration_walking+" min";
                                                                                    document.getElementById('bike_duration_widget1').innerHTML = travel_time_bike+" min";
                                                                                    document.getElementById('car_duration_widget1').innerHTML = travel_time_car+" min";
                                                                                    document.getElementById('walking_duration_widget2').innerHTML = response.duration_walking+" min";
                                                                                    document.getElementById('bike_duration_widget2').innerHTML = travel_time_bike+" min";
                                                                                    document.getElementById('car_duration_widget2').innerHTML = travel_time_car+" min";
                                                                                    document.getElementById('walking_duration_widget3').innerHTML = response.duration_walking+" min";
                                                                                    document.getElementById('bike_duration_widget3').innerHTML = travel_time_bike+" min";
                                                                                    document.getElementById('car_duration_widget3').innerHTML = travel_time_car+" min";
                                                                                    document.getElementById('walking_duration_widget4').innerHTML = response.duration_walking+" min";
                                                                                    document.getElementById('bike_duration_widget4').innerHTML = travel_time_bike+" min";
                                                                                    document.getElementById('car_duration_widget4').innerHTML = travel_time_car+" min";
                                                                                    get_kameo_score(weather, precipitation, temperature, windSpeed, travel_time_bike, travel_time_car);
                                                                                    loaded1=true;
                                                                                    if (loaded2){
                                                                                        $.notify({
                                                                                            message: successMessage
                                                                                        }, {
                                                                                            type: 'success'
                                                                                        });
                                                                                        document.getElementById("travel_information").style.display = "block";
                                                                                        document.getElementById("velos").style.display = "block";
                                                                                        $("body").removeClass("loading");

                                                                                    }
                                                                                })
                                                                            }else{
                                                                                console.log(response.message);
                                                                            }
                                                                        })
                                                                        })
                                                                });

                                                            var i=1;
                                                            var dest = "";
                                                            while (i <= text.length)
                                                            {
                                                                timestampStart=text.timestampStartBooking;
                                                                buildingStart=text.buildingStart;
                                                                timestampEnd=text.timestampEndBooking;
                                                                buildingEnd=text.buildingEnd;

                                                                var bikeFrameNumber=text.bike[i].frameNumber;
                                                                var bikeType=text.bike[i].typeDescription;

                                                                if(text.bike[i].brand && text.bike[i].model && text.bike[i].size){
                                                                    var title= "Marque : "+text.bike[i].brand+" <br/>Modèle : "+text.bike[i].model+" <br/>Taille : "+text.bike[i].size;
                                                                }else{
                                                                    var title=bikeFrameNumber;
                                                                }


                                                                var codeVeloTemporaire ="<div class=\"col-md-4\">\
                                                                    <div class=\"featured-box\">\
                                                                        <div class=\"effect social-links\"> <img src=\"images_bikes/"+bikeFrameNumber+".jpg\" alt=\"image\" />\
                                                                            <div class=\"image-box-content\">\
                                                                                <p> <a href=\"images_bikes/"+bikeFrameNumber+".jpg\" data-lightbox-type=\"image\" title=\"\"><i class=\"fa fa-expand\"></i></a> </p>\
                                                                            </div>\
                                                                        </div>\
                                                                    </div>\
                                                                    </div>\
                                                                    <div class=\"col-md-4\">\
                                                                    <h4>"+ bikeType +"</h4>\
                                                                    <p class=\"subtitle\">"+ title +"</p>\
                                                                    </div>\
                                                                    <div class=\"col-md-2\">\
                                                                        <a class=\"button large green button-3d rounded icon-left\" name=\""+bikeFrameNumber+"\" id=\"fr\" data-target=\"#resume\" data-toggle=\"modal\" href=\"#\" onclick=\"bookBike(this.name)\"><span>Réserver</span></a>\
                                                                    </div>\
                                                                    <div class=\"seperator\"></div>";
                                                                dest = dest.concat(codeVeloTemporaire);
                                                                i++;

                                                            }
                                                            //affichage du résultat de la recherche
                                                            document.getElementById('velos').innerHTML = dest;

                                                            //modification du pop-up de réservation avec les informations de réservation
                                                            $date=document.getElementById("search-bikes-form-day").value;
                                                            $dayAndMonth=$date.split("-");
                                                            document.getElementById('daySpan').innerHTML = $dayAndMonth[0];
                                                            document.getElementById('monthSpan').innerHTML = $dayAndMonth[1];
                                                            document.getElementById('yearSpan').innerHTML = $dayAndMonth[2];

                                                            $date=document.getElementById("search-bikes-form-day-deposit").value;
                                                            $dayAndMonth=$date.split("-");
                                                            document.getElementById('dayDepositSpan').innerHTML = $dayAndMonth[0];
                                                            document.getElementById('monthDepositSpan').innerHTML = $dayAndMonth[1];
                                                            document.getElementById('yearDepositSpan').innerHTML = $dayAndMonth[2];
                                                            document.getElementById('hourStartSpan').innerHTML = document.getElementById("search-bikes-form-intake-hour").value
                                                            document.getElementById('hourEndSpan').innerHTML = document.getElementById("search-bikes-form-deposit-hour").value
                                                            document.getElementById('startBuildingSpan').innerHTML = document.getElementById('search-bikes-form-intake-building').options[document.getElementById('search-bikes-form-intake-building').selectedIndex].text;

                                                            document.getElementById('endBuildingSpan').innerHTML = document.getElementById('search-bikes-form-deposit-building').options[document.getElementById('search-bikes-form-deposit-building').selectedIndex].text;

                                                            loadClientConditions()
                                                            .done(function(response){
                                                                if(response.clientConditions.locking=="Y")
                                                                {
                                                                    var i;
                                                                    var temp="";
                                                                    for(i=0; i<4; i++){
                                                                        temp=temp.concat(Math.floor(Math.random() * 10).toString());
                                                                    }
                                                                    document.getElementById('lockingCodeDiv').style.display="block";
                                                                    document.getElementById('lockingCode').innerHTML=temp;
                                                                    document.getElementById('widget-new-booking-locking-code').value = temp;


                                                                }else{
                                                                    document.getElementById('lockingCodeDiv').style.display="none";
                                                                    document.getElementById('widget-new-booking-locking-code').value = "";
                                                                }
                                                            });

                                                            document.getElementById('widget-new-booking-date-start').value = text.dateStart.date;
                                                            document.getElementById('widget-new-booking-date-end').value = text.dateEnd.date;
                                                            document.getElementById('widget-new-booking-timestamp-start').value = text.timestampStartBooking;
                                                            document.getElementById('widget-new-booking-timestamp-end').value = text.timestampEndBooking;
                                                            document.getElementById('widget-new-booking-building-start').value = document.getElementById("search-bikes-form-intake-building").value;;
                                                            document.getElementById('widget-new-booking-building-end').value = document.getElementById("search-bikes-form-deposit-building").value;

                                                            loaded2=true;
                                                            if (loaded1)
                                                            {
                                                                $.notify({
                                                                    message: successMessage
                                                                }, {
                                                                    type: 'success'
                                                                });
                                                                document.getElementById("travel_information").style.display = "block";
                                                                document.getElementById("velos").style.display = "block";
                                                                $("body").removeClass("loading");
                                                            }

                                                        }
                                                    }
                                                });
                                            }
                                        });
                                    </script>

                                    <script type="text/javascript">
                                        function bookBike(bikeNumber)
                                        {
                                            document.getElementById('widget-new-booking-frame-number').value = bikeNumber;
                                            document.getElementById("resumeBikeImage").src="images_bikes/"+bikeNumber+"_mini.jpg";

                                        }
                                    </script>


                                    <div id="travel_information" style="display:none">
                                        <!-- Pour un écran large -->
                                        <div class="visible-lg">
                                            <div class="col-lg-12 backgroundgreen">
                                                <p class="text-white down">
                                                    <span class="fr-inline text-white">Votre trajet de </span><span class="en-inline text-white">Your trip</span><span class="nl-inline text-white">Uw reis van </span>
                                                    <span class="text-white fr-inline" id="meteoStart1FR"></span>
                                                    <span class="text-white en-inline" id="meteoStart1EN"></span>
                                                    <span class="text-white nl-inline" id="meteoStart1NL"></span>
                                                    <span class="fr-inline text-white">à </span><span class="en-inline text-white">to </span><span class="nl-inline text-white">naar </span>
                                                    <span class="text-white fr-inline" id="meteoEnd1FR"></span>
                                                    <span class="text-white en-inline" id="meteoEnd1EN"></span>
                                                    <span class="text-white nl-inline" id="meteoEnd1NL"></span>

                                                    <span class="fr-inline text-white">le </span><span class="en-inline text-white">on </span><span class="nl-inline text-white">op </span>
                                                    <span class="text-white" id="meteoDate1"></span>

                                                    <span class="fr-inline text-white">à </span><span class="en-inline text-white">at </span><span class="nl-inline text-white">om </span>
                                                    <span class="text-white" id="meteoHour1"></span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="visible-lg">
                                            <div class="col-lg-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                    <div class="col-lg-3">
                                                        <img id="logo_meteo1" alt="image" class="centerimg" />
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <ul>
                                                            <li id="temperature_widget1" class="temperature text-center"></li>
                                                            <li id="precipitation_widget1" class="humidite text-center"></li>
                                                            <li id="wind_widget1" class="vent text-center"></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <ul class="bords">
                                                            <li id="walking_duration_widget1" class="marche grid-col-demo text-center"></li>
                                                            <li id="car_duration_widget1" class="voiture grid-col-demo text-center"></li>
                                                            <li id="bike_duration_widget1" class="bike grid-col-demo text-center"></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <img id="score_kameo1" alt="image" class="centerimg" data-toggle="tooltip" data-placement="top" title="L'indice mykameo est une combinaison de la météo et du temps de trajet en vélo par rapport à la voiture" />
                                                    </div>
                                            </div>
                                        </div>

                                        <!-- Pour un écran médium -->
                                        <div class="visible-md">
                                            <div class="col-md-12 backgroundgreen">
                                                <p class="text-white down">
                                                    <span class="fr-inline text-white">Votre trajet de </span><span class="en-inline text-white">Your trip</span><span class="nl-inline text-white">Uw reis van </span>
                                                    <span class="text-white fr-inline" id="meteoStart2FR"></span>
                                                    <span class="text-white en-inline" id="meteoStart2EN"></span>
                                                    <span class="text-white nl-inline" id="meteoStart2NL"></span>
                                                    <span class="fr-inline text-white">à </span><span class="en-inline text-white">to </span><span class="nl-inline text-white">naar </span>
                                                    <span class="text-white fr-inline" id="meteoEnd2FR"></span>
                                                    <span class="text-white en-inline" id="meteoEnd2EN"></span>
                                                    <span class="text-white nl-inline" id="meteoEnd2NL"></span>

                                                    <span class="fr-inline text-white">le </span><span class="en-inline text-white">on </span><span class="nl-inline text-white">op </span>
                                                    <span class="text-white" id="meteoDate2"></span>

                                                    <span class="fr-inline text-white">à </span><span class="en-inline text-white">at </span><span class="nl-inline text-white">om </span>
                                                    <span class="text-white" id="meteoHour2"></span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="visible-md">
                                            <div class="col-md-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                    <div class="col-md-3">
                                                        <img id="logo_meteo2" alt="image" class="centerimg" />
                                                    </div>

                                                    <div class="col-md-3">
                                                        <ul>
                                                            <li id="temperature_widget2" class="temperature text-center"></li>
                                                            <li id="precipitation_widget2" class="humidite text-center"></li>
                                                            <li id="wind_widget2" class="vent text-center"></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <ul class="bords">
                                                            <li id="walking_duration_widget2" class="marche grid-col-demo text-center"></li>
                                                            <li id="car_duration_widget2" class="voiture grid-col-demo text-center"></li>
                                                            <li id="bike_duration_widget2" class="bike grid-col-demo text-center"></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <img id="score_kameo2" alt="image" class="centerimg" data-toggle="tooltip" data-placement="top" title="L'indice mykameo est une combinaison de la météo et du temps de trajet en vélo par rapport à la voiture"/>
                                                    </div>
                                            </div>
                                        </div>

                                        <!-- Pour une tablette -->
                                        <div class="visible-sm">
                                            <div class="col-sm-12 backgroundgreen">
                                                <p class="text-white down">
                                                    <span class="fr-inline text-white">Votre trajet de </span><span class="en-inline text-white">Your trip</span><span class="nl-inline text-white">Uw reis van </span>
                                                    <span class="text-white fr-inline" id="meteoStart3FR"></span>
                                                    <span class="text-white en-inline" id="meteoStart3EN"></span>
                                                    <span class="text-white nl-inline" id="meteoStart3NL"></span>
                                                    <span class="fr-inline text-white">à </span><span class="en-inline text-white">to </span><span class="nl-inline text-white">naar </span>
                                                    <span class="text-white fr-inline" id="meteoEnd3FR"></span>
                                                    <span class="text-white en-inline" id="meteoEnd3EN"></span>
                                                    <span class="text-white nl-inline" id="meteoEnd3NL"></span>

                                                    <span class="fr-inline text-white">le </span><span class="en-inline text-white">on </span><span class="nl-inline text-white">op </span>
                                                    <span class="text-white" id="meteoDate3"></span>

                                                    <span class="fr-inline text-white">à </span><span class="en-inline text-white">at </span><span class="nl-inline text-white">om </span>
                                                    <span class="text-white" id="meteoHour3"></span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="visible-sm">
                                            <div class="col-sm-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                    <div class="col-sm-12">
                                                        <img id="logo_meteo3" alt="image" class="centerimg" />
                                                    </div>

                                                    <div class="seperator"></div>

                                                    <div class="col-sm-6">
                                                        <ul>
                                                            <li id="temperature_widget3" class="temperature2 text-center"></li>
                                                            <li id="precipitation_widget3" class="humidite2 text-center"></li>
                                                            <li id="wind_widget3" class="vent2 text-center"></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <ul class="bords">
                                                            <li id="walking_duration_widget3" class="marche2 grid-col-demo text-center"></li>
                                                            <li id="car_duration_widget3" class="voiture2 grid-col-demo text-center"></li>
                                                            <li id="bike_duration_widget3" class="bike2 grid-col-demo text-center"></li>
                                                        </ul>
                                                    </div>

                                                    <div class="seperator"></div>

                                                    <div class="col-sm-12">
                                                        <img id="score_kameo3" alt="image" class="centerimg" />
                                                    </div>
                                            </div>
                                        </div>

                                        <!-- Pour un smartphone -->
                                        <div class="visible-xs">
                                            <div class="col-xs-12 backgroundgreen">
                                                <p class="text-white down">
                                                    <span class="fr-inline text-white">Votre trajet de </span><span class="en-inline text-white">Your trip</span><span class="nl-inline text-white">Uw reis van </span>
                                                    <span class="text-white fr-inline" id="meteoStart4FR"></span>
                                                    <span class="text-white en-inline" id="meteoStart4EN"></span>
                                                    <span class="text-white nl-inline" id="meteoStart4NL"></span>
                                                    <span class="fr-inline text-white">à </span><span class="en-inline text-white">to </span><span class="nl-inline text-white">naar </span>
                                                    <span class="text-white fr-inline" id="meteoEnd4FR"></span>
                                                    <span class="text-white en-inline" id="meteoEnd4EN"></span>
                                                    <span class="text-white nl-inline" id="meteoEnd4NL"></span>

                                                    <span class="fr-inline text-white">le </span><span class="en-inline text-white">on </span><span class="nl-inline text-white">op </span>
                                                    <span class="text-white" id="meteoDate4"></span>


                                                    <span class="fr-inline text-white">à </span><span class="en-inline text-white">at </span><span class="nl-inline text-white">om </span>
                                                    <span class="text-white" id="meteoHour4"></span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="visible-xs">
                                            <div class="col-xs-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                    <div class="col-xs-12">
                                                        <img id="logo_meteo4" alt="image" class="centerimg" />
                                                    </div>

                                                    <div class="seperator"></div>

                                                    <div class="col-xs-12">
                                                        <ul>
                                                            <li id="temperature_widget4" class="temperature3 text-center"></li>
                                                            <li id="precipitation_widget4" class="humidite3 text-center"></li>
                                                            <li id="wind_widget4" class="vent3 text-center"></li>
                                                        </ul>
                                                    </div>

                                                    <div class="seperator"></div>

                                                    <div class="col-xs-12">
                                                        <ul class="bords">
                                                            <li id="walking_duration_widget4" class="marche3 grid-col-demo text-center"></li>
                                                            <li id="car_duration_widget4" class="voiture3 grid-col-demo text-center"></li>
                                                            <li id="bike_duration_widget4" class="bike3 grid-col-demo text-center"></li>
                                                        </ul>
                                                    </div>

                                                    <div class="seperator"></div>

                                                    <div class="col-xs-12">
                                                        <img id="score_kameo4" alt="image" class="centerimg" />
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="reservations">

                                    <div data-example-id="contextual-table" class="bs-example">
                                      <span id="historicBookings"></span>
                                    </div>

                                    <div class="seperator"></div>

                                    <div data-example-id="contextual-table" class="bs-example">
                                        <span id="futureBookings"></span>
                                    </div>

                                </div>

                                 <div class="tab-pane" id="routes">

                                    <h4 class="fr">Itinéraires conseillés</h4>
                                    <p>Nous avons créé pour vous quelques itinéraires agréables pour vous rendre au travail.</p>
                                    <div class="separator"></div>

                                    <h5>Place du XX-Août - CHU Sart-Tilman</h5>
                                    <div class="visible-md visible-lg visible-sm">
                                    <iframe width="450px" height="580px" src="https://www.openrunner.com/route/9725042/embed/fr/4d797178424b307264565857544b58755a6955334d6172376b6a434f45314154723253704b6a50515834303d3a3a0c5f9b347e3f087700118732ba812fb7" frameborder="0" allowfullscreen></iframe>
                                    </div>

                                    <div class="visible-xs">
                                    <iframe width="250px" height="280px" src="https://www.openrunner.com/route/9725042/embed/fr/4d797178424b307264565857544b58755a6955334d6172376b6a434f45314154723253704b6a50515834303d3a3a0c5f9b347e3f087700118732ba812fb7" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <a href="http://www.kameobikes.com/docs/Routes/XXAout_CHU_Itinineraire.pdf" download="XXAout_CHU_Itinineraire.pdf" target="_blank"><i class="fa fa-download"></i> Télécharger les instructions</a>

                                    <div class="separator"></div>

                                    <h5>Embourg - CHU Sart-Tilman</h5>
                                    <div class="visible-md visible-lg visible-sm">
                                    <iframe width="450px" height="580px" src="https://www.openrunner.com/route/9733246/embed/fr/537333576a65315635515657574578527375722b54564147524e61644a6b6c51666d34705a4677666f70733d3a3a30ca4562f0a633da7b53a0ba8bd2c6aa" frameborder="0" allowfullscreen></iframe>
                                    </div>

                                    <div class="visible-xs">
                                    <iframe width="250px" height="280px" src="https://www.openrunner.com/route/9733246/embed/fr/537333576a65315635515657574578527375722b54564147524e61644a6b6c51666d34705a4677666f70733d3a3a30ca4562f0a633da7b53a0ba8bd2c6aa" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <a href="http://www.kameobikes.com/docs/Routes/XXAout_CHU_Itinineraire.pdf" download="XXAout_CHU_Itinineraire.pdf" target="_blank"><i class="fa fa-download"></i> Télécharger les instructions</a>

                                    <div class="separator"></div>

                                    <h5>Esneux - CHU Sart-Tilman</h5>
                                    <div class="visible-md visible-lg visible-sm">
                                    <iframe width="450px" height="580px" src="https://www.openrunner.com/route/9733176/embed/fr/786d4c57563377754e4f7131376630595674427a49544b385038702f73513274323956536e732b4c37374d3d3a3a2a1c2e0c4b78d238839ef9bd4487f60a" frameborder="0" allowfullscreen></iframe>
                                    </div>

                                    <div class="visible-xs">
                                    <iframe width="250px" height="280px" src="https://www.openrunner.com/route/9733176/embed/fr/786d4c57563377754e4f7131376630595674427a49544b385038702f73513274323956536e732b4c37374d3d3a3a2a1c2e0c4b78d238839ef9bd4487f60a" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <a href="http://www.kameobikes.com/docs/Routes/XXAout_CHU_Itinineraire.pdf" download="XXAout_CHU_Itinineraire.pdf" target="_blank"><i class="fa fa-download"></i> Télécharger les instructions</a>


                                </div>


                                <div class="tab-pane" id="fleetmanager">

                                        <tbody>

                                            <h4 class="fr">Votre flotte</h4><br/><br />

										     <div class="row">
										     	<div class="col-md-4">
											        <div class="icon-box medium fancy">
											          <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#BikesListing" href="#" ><i class="fa fa-bicycle"></i></a> </div>
											          <div class="counter bold" id="counterBike" style="color:#3cb395"></div>
											          <p>Nombre de vélos</p>
											        </div>
											     </div>

											     <div class="seperator seperator-small visible-xs"><br/><br/></div>

											     <div class="col-md-4">
											        <div class="icon-box medium fancy">
											          <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#usersListing" href="#" ><i class="fa fa-users"></i></a> </div>
											          <div class="counter bold" id="counterUsers" style="color:#3cb395"></div>
											          <p>Nombre d'utilisateurs</p>
											        </div>
											     </div>

											     <div class="seperator seperator-small visible-xs"><br/><br/></div>

											     <div class="col-md-4">
											        <div class="icon-box medium fancy">
											          <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" href="#"><i class="fa fa-calendar-plus-o reservationlisting"></i></a></div>
											          <div class="counter bold" id="counterBookings" style="color:#3cb395"></div>
											          <p>Nombre de réservations sur le mois passé</p>
											        </div>
											     </div>
										     </div>

											<div class="separator"></div>

                                            <h4 class="fr">Réglages</h4>
                                            <h4 class="en">Settings</h4>
                                            <h4 class="en">Settings</h4><br/><br />

										     <div class="row">
										     	<div class="col-md-4">
											        <div class="icon-box medium fancy">
											          <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#conditionListing" href="#" ><i class="fa fa-cog"></i></a> </div>
											          <div class="counter bold" style="color:#3cb395"></div>
											          <p>Modifier les réglages</p>
											        </div>
											     </div>
                                            </div>

											<div class="separator"></div>

                                            <h4 class="fr hidden kameo">Administration Kameo</h4>
                                            <h4 class="en hidden kameo">Kameo administration</h4>
                                            <h4 class="en hidden kameo">Kameo administration</h4><br/><br />
										     <div class="row">
										     	<div class="col-md-4 hidden" id="clientManagement">
											        <div class="icon-box medium fancy">
											          <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#companyListing" href="#" ><i class="fa fa-users"></i></a> </div>
											          <div class="counter bold" id="counterClients" style="color:#3cb395"></div>
											          <p>Gérer les clients</p>
											        </div>
											     </div>




											    <div class="seperator seperator-small visible-xs"><br/><br/></div>

										     	<div class="col-md-4 hidden" id="portfolioManagement">
											        <div class="icon-box medium fancy">
											          <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#portfolioManager" href="#" class="portfolioManagerClick"><i class="fa fa-book"></i></a> </div>
											          <div class="counter bold" id='counterBikePortfolio' style="color:#3cb395"></div>
											          <p>Gérer le catalogue</p>
											        </div>
											     </div>

											    <div class="seperator seperator-small visible-xs"><br/><br/></div>

										     	<div class="col-md-4 hidden" id="bikesManagement">
											         <div class="icon-box medium fancy">
											             <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#BikesListingAdmin" href="#" class="bikeManagerClick"><i class="fa fa-bicycle"></i></a></div>
											             <div class="counter bold" id="counterBikeAdmin" style="color:#3cb395"></div>
											             <p>Gérer les vélos</p>
											        </div>
											     </div>
											     <br/><br/>
											    <div class="col-md-12"><br/><br/></div>
											    <div class="seperator seperator-small visible-xs"><br/><br/></div>

										     	<div class="col-md-4 hidden" id="boxesManagement">
											         <div class="icon-box medium fancy">
											             <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#boxesListing" href="#" class="boxManagerClick"><i class="fa fa-cube"></i></a></div>
											             <div class="counter bold" id="counterBoxes" style="color:#3cb395"></div>
											             <p>Gérer les Bornes</p>
											        </div>
											     </div>

                                                 <div class="col-md-4 hidden" id="tasksManagement">
											         <div class="icon-box medium fancy">
											             <div class="icon bold" data-animation="pulse infinite">
                                                             <a data-toggle="modal" data-target="#tasksListing" href="#" class="tasksManagerClick"><i class="fa fa-tasks"></i></a>
                                                        </div>
											             <div class="counter bold" id="counterTasks" style="color:#3cb395"></div>
											             <p>Gérer les Actions</p>
											        </div>
											     </div>

										     	<div class="col-md-4 hidden" id="cashFlowManagement">
											         <div class="icon-box medium fancy">
											             <div class="icon bold" data-animation="pulse infinite">
                                                             <a data-toggle="modal" data-target="#offersListing" href="#" class="offerManagerClick"><i class="fa fa-money"></i></a>
                                                        </div>
											             <div class="counter bold" id="cashFlowSpan" style="color:#3cb395"></div>
											             <p>Vue sur le cash-flow</p>
											        </div>
											     </div>
										     	<div class="col-md-4 hidden" id="feedbacksManagement">
											         <div class="icon-box medium fancy">
											             <div class="icon bold" data-animation="pulse infinite">
                                                             <a data-toggle="modal" data-target="#feedbacksListing" href="#" class="feedbackManagementClick"><i class="fa fa-comments"></i></a>
                                                        </div>
											             <div class="counter bold" id="counterFeedbacks" style="color:#3cb395"></div>
											             <p>Vue sur les feedbacks</p>
											        </div>
											     </div>
                                            </div>


                                            <div class="separator hidden kameo"></div>

                                            <h4 class="fr">Factures</h4>
                                            <h4 class="en">Billing</h4>
                                            <h4 class="en">Billing</h4><br/><br />

										     <div class="row">
										     	<div class="col-md-4">
											        <div class="icon-box medium fancy">
											          <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#billingListing" href="#" ><i class="fa fa-folder-open-o"></i></a> </div>
											          <div class="counter bold" id='counterBills' style="color:#3cb395"></div>
											          <p>Aperçu des factures</p>
											        </div>
											     </div>
										     </div>



										      <div class="col-md-12" id="progress-bar-bookings">
										      </div>
                                        </tbody>
                                </div>
                        </div>
                    </div>



                        <div class="modal fade" id="futureBooking" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                            <h4 class="fr">Informations relatives à la réservation</h4>
                                                <span id="bookingInformation"></span>
                                            <h4 class="fr">Personne avant vous:</h4>
                                            <h4 class="nl">Persoon voor jou:</h4>
                                            <h4 class="en">Person before you:</h4>
                                                <ul>
                                                   <span id="futureBookingBefore"></span>
                                                </ul>
                                            <h4 class="fr">Personne après vous:</h4>
                                            <h4 class="nl">Persoon na jou:</h4>
                                            <h4 class="en">Person after you:</h4>

                                                   <span id="futureBookingAfter"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="pull-left">
                                            <button data-dismiss="modal" class="btn btn-b fr" type="button">Fermer</button>
                                            <button data-dismiss="modal" class="btn btn-b nl" type="button">Sluiten</button>
                                            <button data-dismiss="modal" class="btn btn-b en" type="button">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="2" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h4>Personne avant vous:</h4>
                                                <ul>
                                                        <li>Nom et prénom: Antoine Lust</li>
                                                        <li>Numéro de téléphone: 0478 99 66 98</li>
                                                        <li>Adresse mail: antoine.lust@kameobikes.com</li>
                                                        <li>Remise du vélo à 15h.</li>
                                                </ul>
                                                <h4>Personne après vous:</h4>
                                                    <ul>
                                                        <li>Nom et prénom: Julien Jamar</li>
                                                        <li>Numéro de téléphone: 0487 65 44 83</li>
                                                        <li>Adresse mail: pierre-yves.adant@kameobikes.com</li>
                                                        <li>Prise en charge du vélo à 18h.</li>
                                                    </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="pull-left">
                                            <button data-dismiss="modal" class="btn btn-b fr" type="button">Fermer</button>
                                            <button data-dismiss="modal" class="btn btn-b en" type="button">Close</button>
                                            <button data-dismiss="modal" class="btn btn-b nl" type="button">Sluiten</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div id="velos"style="display: none;"></div>
                    </div>
    <?php

                            }
                            else
                            {

                                include 'include/connexion.php';
                                $sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY,
                                bb.CONTRACT_REFERENCE, bb.CONTRACT_START, bb.CONTRACT_END, cc.MODEL_FR \"bike_Model_FR\", cc.MODEL_EN \"bike_Model_EN\", cc.MODEL_NL \"bike_Model_NL\"
                                from customer_referential aa, customer_bikes bb, bike_models cc
                                where aa.EMAIL='$user' and aa.FRAME_NUMBER=bb.FRAME_NUMBER and bb.TYPE=cc.ID";

                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);
                                $contractNumber=$row['CONTRACT_REFERENCE'];
                                $contractStart=$row['CONTRACT_START'];
                                $contractEnd=$row['CONTRACT_END'];

                                ?>

                                <div id="travel_information_2" style="display: none;">
                                    <!-- Pour un écran large -->
                                    <div class="visible-lg">
                                        <div class="col-lg-12 backgroundgreen down">
                                            <p class="text-white down">
                                            <span class="fr-inline text-white">Votre trajet domicile - travail le </span>
                                            <span class="en-inline text-white">Your trip home - work on </span>
                                            <span class="nl-inline text-white">Uw reis naar huis - werk op </span>
                                            <span class="text-white" id="meteoDate1"></span>
                                            <span class="fr-inline text-white"> à </span>
                                            <span class="en-inline text-white"> at </span>
                                            <span class="nl-inline text-white"> om </span>
                                            <span class="text-white" id="meteoHour1"></span>

                                            </p>
                                        </div>
                                    </div>

                                    <div class="visible-lg">
                                        <div class="col-lg-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <div class="col-lg-3">
                                                    <img id="logo_meteo1" alt="image" class="centerimg" />
                                                </div>

                                                <div class="col-lg-3">
                                                    <ul>
                                                        <li id="temperature_widget1" class="temperature text-center"></li>
                                                        <li id="precipitation_widget1" class="humidite text-center"></li>
                                                        <li id="wind_widget1" class="vent text-center"></li>
                                                    </ul>
                                                </div>

                                                <div class="col-lg-3">
                                                    <ul class="bords">
                                                        <li id="walking_duration_widget1" class="marche grid-col-demo text-center"></li>
                                                        <li id="car_duration_widget1" class="voiture grid-col-demo text-center"></li>
                                                        <li id="bike_duration_widget1" class="bike grid-col-demo text-center"></li>
                                                    </ul>
                                                </div>

                                                <div class="col-lg-3">
                                                    <img id="score_kameo1" alt="image" class="centerimg" />
                                                </div>
                                        </div>
                                    </div>



                                    <!-- Pour un écran médium -->
                                    <div class="visible-md">
                                        <div class="col-md-12 backgroundgreen">
                                            <p class="text-white down">
                                            <span class="fr-inline text-white">Votre trajet domicile - travail le </span>
                                            <span class="en-inline text-white">Your trip home - work on </span>
                                            <span class="nl-inline text-white">Uw reis naar huis - werk op </span>
                                            <span class="text-white" id="meteoDate2"></span>
                                            <span class="fr-inline text-white"> à </span>
                                            <span class="en-inline text-white"> at </span>
                                            <span class="nl-inline text-white"> om </span>
                                            <span class="text-white" id="meteoHour2"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="visible-md">
                                        <div class="col-md-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <div class="col-md-3">
                                                    <img id="logo_meteo2" alt="image" class="centerimg" />
                                                </div>

                                                <div class="col-md-3">
                                                    <ul>
                                                        <li id="temperature_widget2" class="temperature text-center"></li>
                                                        <li id="precipitation_widget2" class="humidite text-center"></li>
                                                        <li id="wind_widget2" class="vent text-center"></li>
                                                    </ul>
                                                </div>

                                                <div class="col-md-3">
                                                    <ul class="bords">
                                                        <li id="walking_duration_widget2" class="marche grid-col-demo text-center"></li>
                                                        <li id="car_duration_widget2" class="voiture grid-col-demo text-center"></li>
                                                        <li id="bike_duration_widget2" class="bike grid-col-demo text-center"></li>
                                                    </ul>
                                                </div>

                                                <div class="col-md-3">
                                                        <img id="score_kameo2" alt="image" class="centerimg" data-toggle="tooltip" data-placement="top" title="L'indice mykameo est une combinaison de la météo et du temps de trajet en vélo par rapport à la voiture"/>
                                                </div>
                                        </div>
                                    </div>

                                    <!-- Pour une tablette -->
                                    <div class="visible-sm">
                                        <div class="col-sm-12 backgroundgreen">
                                            <p class="text-white down">
                                            <span class="fr-inline text-white">Votre trajet domicile - travail le </span>
                                            <span class="en-inline text-white">Your trip home - work on </span>
                                            <span class="nl-inline text-white">Uw reis naar huis - werk op </span>
                                            <span class="text-white" id="meteoDate3"></span>
                                            <span class="fr-inline text-white"> à </span>
                                            <span class="en-inline text-white"> at </span>
                                            <span class="nl-inline text-white"> om </span>
                                            <span class="text-white" id="meteoHour3"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="visible-sm">
                                        <div class="col-sm-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <div class="col-sm-12">
                                                    <img id="logo_meteo3" alt="image" class="centerimg" />
                                                </div>

                                                <div class="seperator"></div>

                                                <div class="col-sm-6">
                                                    <ul>
                                                        <li id="temperature_widget3" class="temperature2 text-center"></li>
                                                        <li id="precipitation_widget3" class="humidite2 text-center"></li>
                                                        <li id="wind_widget3" class="vent2 text-center"></li>
                                                    </ul>
                                                </div>

                                                <div class="col-sm-6">
                                                    <ul class="bords">
                                                        <li id="walking_duration_widget3" class="marche2 grid-col-demo text-center"></li>
                                                        <li id="car_duration_widget3" class="voiture2 grid-col-demo text-center"></li>
                                                        <li id="bike_duration_widget3" class="bike2 grid-col-demo text-center"></li>
                                                    </ul>
                                                </div>

                                                <div class="seperator"></div>

                                                <div class="col-sm-12">
                                                    <img id="score_kameo3" alt="image" class="centerimg" />
                                                </div>
                                        </div>
                                    </div>

                                    <!-- Pour un smartphone -->
                                    <div class="visible-xs">
                                        <div class="col-xs-12 backgroundgreen">
                                            <p class="text-white down">
                                            <span class="fr-inline text-white">Votre trajet domicile - travail le </span>
                                            <span class="en-inline text-white">Your trip home - work on </span>
                                            <span class="nl-inline text-white">Uw reis naar huis - werk op </span>
                                            <span class="text-white" id="meteoDate4"></span>
                                            <span class="fr-inline text-white"> à </span>
                                            <span class="en-inline text-white"> at </span>
                                            <span class="nl-inline text-white"> om </span>
                                            <span class="text-white" id="meteoHour4"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="visible-xs">
                                        <div class="col-xs-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <div class="col-xs-12">
                                                    <img id="logo_meteo4" alt="image" class="centerimg" />
                                                </div>

                                                <div class="seperator"></div>

                                                <div class="col-xs-12">
                                                    <ul>
                                                        <li id="temperature_widget4" class="temperature3 text-center"></li>
                                                        <li id="precipitation_widget4" class="humidite3 text-center"></li>
                                                        <li id="wind_widget4" class="vent3 text-center"></li>
                                                    </ul>
                                                </div>

                                                <div class="seperator"></div>

                                                <div class="col-xs-12">
                                                    <ul class="bords">
                                                        <li id="walking_duration_widget4" class="marche3 grid-col-demo text-center"></li>
                                                        <li id="car_duration_widget4" class="voiture3 grid-col-demo text-center"></li>
                                                        <li id="bike_duration_widget4" class="bike3 grid-col-demo text-center"></li>
                                                    </ul>
                                                </div>

                                                <div class="seperator"></div>

                                                <div class="col-xs-12">
                                                    <img id="score_kameo4" alt="image" class="centerimg" />
                                                </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="travel_information_2_error" style="display: none;">
                                    <!-- Pour un écran large -->
                                    <div class="visible-lg">
                                        <div class="col-lg-12 backgroundgreen down">
                                            <p class="text-white down">
                                            <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                                            <span class="en-inline text-white">Your trip home - work at </span>
                                            <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                                            <span class="text-white" id="meteoHour1"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="visible-lg">
                                        <div class="col-lg-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <h2 class="text-white text-center">ERROR</h2>
                                                <p class="text-white text-center fr">Erreur dans le chargement de vos données. Veuillez vérifier votre adresse de domicile et lieu de travail</p>
                                                <p class="text-white text-center en">Error when loading travel information. Please check your work place and house address information.</p>
                                                <p class="text-white text-center nl">Fout bij het laden van reisinformatie. Controleer uw werkplaats en huisadresgegevens.</p>

                                        </div>
                                    </div>



                                    <!-- Pour un écran médium -->
                                    <div class="visible-md">
                                        <div class="col-md-12 backgroundgreen">
                                            <p class="text-white down">
                                            <span class="fr-inline">Votre trajet domicile - travail à </span>
                                            <span class="en-inline">Your trip home - work at </span>
                                            <span class="nl-inline">Uw reis naar huis - werk bij </span>
                                            <span id="meteoHour2"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="visible-md">
                                        <div class="col-md-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <h2 class="text-white text-center">ERROR</h2>
                                                <p class="text-white text-center fr">Erreur dans le chargement de vos données. Veuillez vérifier votre adresse de domicile et lieu de travail</p>
                                                <p class="text-white text-center en">Error when loading travel information. Please check your work place and house address information.</p>
                                                <p class="text-white text-center nl">Fout bij het laden van reisinformatie. Controleer uw werkplaats en huisadresgegevens.</p>

                                        </div>
                                    </div>

                                    <!-- Pour une tablette -->
                                    <div class="visible-sm">
                                        <div class="col-sm-12 backgroundgreen">
                                            <p class="text-white down">
                                            <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                                            <span class="en-inline text-white">Your trip home - work at </span>
                                            <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                                            <span class="text-white" id="meteoHour3"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="visible-sm">
                                        <div class="col-sm-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <h2 class="text-white text-center">ERROR</h2>
                                                <p class="text-white text-center fr">Erreur dans le chargement de vos données. Veuillez vérifier votre adresse de domicile et lieu de travail</p>
                                                <p class="text-white text-center en">Error when loading travel information. Please check your work place and house address information.</p>
                                                <p class="text-white text-center nl">Fout bij het laden van reisinformatie. Controleer uw werkplaats en huisadresgegevens.</p>

                                        </div>
                                    </div>

                                    <!-- Pour un smartphone -->
                                    <div class="visible-xs">
                                        <div class="col-xs-12 backgroundgreen">
                                            <p class="text-white down">
                                            <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                                            <span class="en-inline text-white">Your trip home - work at </span>
                                            <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                                            <span class="text-white" id="meteoHour4"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="visible-xs">
                                        <div class="col-xs-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <h2 class="text-white text-center">ERROR</h2>
                                                <p class="text-white text-center fr">Erreur dans le chargement de vos données. Veuillez vérifier votre adresse de domicile et lieu de travail</p>
                                                <p class="text-white text-center en">Error when loading travel information. Please check your work place and house address information.</p>
                                                <p class="text-white text-center nl">Fout bij het laden van reisinformatie. Controleer uw werkplaats en huisadresgegevens.</p>

                                        </div>
                                    </div>
                                </div>

                                <div id="travel_information_2_loading" style="display: block;">
                                    <!-- Pour un écran large -->
                                    <div class="visible-lg">
                                        <div class="col-lg-12 backgroundgreen down">
                                            <p class="text-white down">
                                            <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                                            <span class="en-inline text-white">Your trip home - work at </span>
                                            <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                                            <span class="text-white" id="meteoHour1"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="visible-lg">
                                        <div class="col-lg-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <h2 class="text-white text-center">LOADING</h2>
                                                <p class="text-white text-center fr">Chargement des informations entre votre domicile et votre lieu de travail</p>
                                                <p class="text-white text-center en">Loading of travel time between your house and work place</p>
                                                <p class="text-white text-center nl">Laden van reistijd tussen uw huis en uw werkplek</p>

                                        </div>
                                    </div>



                                    <!-- Pour un écran médium -->
                                    <div class="visible-md">
                                        <div class="col-md-12 backgroundgreen">
                                            <p class="text-white down">
                                            <span class="fr-inline">Votre trajet domicile - travail à </span>
                                            <span class="en-inline">Your trip home - work at </span>
                                            <span class="nl-inline">Uw reis naar huis - werk bij </span>
                                            <span id="meteoHour2"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="visible-md">
                                        <div class="col-md-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <h2 class="text-white text-center">LOADING</h2>
                                                <p class="text-white text-center fr">Chargement des informations entre votre domicile et votre lieu de travail</p>
                                                <p class="text-white text-center en">Loading of travel time between your house and work place</p>
                                                <p class="text-white text-center nl">Laden van reistijd tussen uw huis en uw werkplek</p>

                                        </div>
                                    </div>

                                    <!-- Pour une tablette -->
                                    <div class="visible-sm">
                                        <div class="col-sm-12 backgroundgreen">
                                            <p class="text-white down">
                                            <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                                            <span class="en-inline text-white">Your trip home - work at </span>
                                            <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                                            <span class="text-white" id="meteoHour3"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="visible-sm">
                                        <div class="col-sm-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <h2 class="text-white text-center">LOADING</h2>
                                                <p class="text-white text-center fr">Chargement des informations entre votre domicile et votre lieu de travail</p>
                                                <p class="text-white text-center en">Loading of travel time between your house and work place</p>
                                                <p class="text-white text-center nl">Laden van reistijd tussen uw huis en uw werkplek</p>

                                        </div>
                                    </div>

                                    <!-- Pour un smartphone -->
                                    <div class="visible-xs">
                                        <div class="col-xs-12 backgroundgreen">
                                            <p class="text-white down">
                                            <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                                            <span class="en-inline text-white">Your trip home - work at </span>
                                            <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                                            <span class="text-white" id="meteoHour4"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="visible-xs">
                                        <div class="col-xs-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                                                <h2 class="text-white text-center">LOADING</h2>
                                                <p class="text-white text-center fr">Chargement des informations entre votre domicile et votre lieu de travail</p>
                                                <p class="text-white text-center en">Loading of travel time between your house and work place</p>
                                                <p class="text-white text-center nl">Laden van reistijd tussen uw huis en uw werkplek</p>

                                        </div>
                                    </div>
                                </div>


                                <img src="images_bikes/<?php echo $row['FRAME_NUMBER']; ?>.jpg" class="img-responsive img-rounded" alt="Infographie">

                                <br />
                                <div class="table-responsive">
                                  <table class="table table-striped">
                                    <caption class="fr"> Descriptif de votre vélo </caption>
                                    <caption class="en"> Description of your bike </caption>
                                    <caption class="nl"> Beschrijving van je fiets </caption>
                                    <tbody>
                                      <tr>
                                        <td class="fr">Modèle</td>
                                        <td class="en">Bike model</td>
                                        <td class="nl">Fietsmodel</td>
                                        <td class="fr-cell"><?php echo $row["bike_Model_FR"] ?></td>
                                        <td class="en-cell"><?php echo $row["bike_Model_EN"] ?></td>
                                        <td class="nl-cell"><?php echo $row["bike_Model_NL"] ?></td>
                                      </tr>
                                      <tr>
                                        <td class="fr">Date de début de contrat</td>
                                        <td class="en">Start date of contract</td>
                                        <td class="nl">Startdatum van het contract</td>
                                        <td><?php echo $row["CONTRACT_START"]; ?></td>
                                      </tr>
                                      <tr>
                                        <td class="fr">Date de fin de contrat</td>
                                        <td class="en">End date of contract</td>
                                        <td class="nl">Einddatum van het contract</td>
                                        <td><?php echo $row["CONTRACT_END"]; ?></td>
                                      </tr>


                                    </tbody>
                                  </table>

                                </div>
                                <script type="text/javascript">

                                    var day= new Date().getDate();
                                    var month= new Date().getMonth() + 1;
                                    var year= new Date().getFullYear();
                                    var hours= new Date().getHours();
                                    var minutes= new Date().getMinutes();
                                    minutes=minutes.toString();
                                    var kameo_score_loaded=false;

                                    if (minutes.length ==1){
                                        minutes="0"+minutes;
                                    }

                                    document.getElementById('meteoDate1').innerHTML = day+"/"+ month+"/"+year;
                                    document.getElementById('meteoDate2').innerHTML = day+"/"+ month+"/"+year;
                                    document.getElementById('meteoDate3').innerHTML = day+"/"+ month+"/"+year;
                                    document.getElementById('meteoDate4').innerHTML = day+"/"+ month+"/"+year;
                                    document.getElementById('meteoHour1').innerHTML = hours+"h"+ minutes;
                                    document.getElementById('meteoHour2').innerHTML = hours+"h"+ minutes;
                                    document.getElementById('meteoHour3').innerHTML = hours+"h"+ minutes;
                                    document.getElementById('meteoHour4').innerHTML = hours+"h"+ minutes;

                                    var addressDomicile=get_address_domicile();
                                    var addressTravail=get_address_travail();

                                    var timestamp=Date.now().toString();
                                    get_meteo(timestamp.substring(0,10), addressDomicile)
                                    .done(function(response){
                                        if(response.response=="success")
                                        {
                                            var find = '-';
                                            var re = new RegExp(find, 'g');

                                            weather=response.icon.replace(re,"");
                                            temperature=response.temperature;
                                            precipitation=response.precipProbability;
                                            windSpeed=response.windSpeed;

                                            document.getElementById("logo_meteo1").src="images/meteo/"+weather+".png";
                                            document.getElementById('temperature_widget1').innerHTML = Math.round(temperature)+" °C";
                                            document.getElementById('precipitation_widget1').innerHTML = precipitation+" %";
                                            document.getElementById('wind_widget1').innerHTML = Math.round(windSpeed*3.6)+" km/h";
                                            document.getElementById("logo_meteo2").src="images/meteo/"+weather+".png";
                                            document.getElementById('temperature_widget2').innerHTML = Math.round(temperature)+" °C";
                                            document.getElementById('precipitation_widget2').innerHTML = precipitation+" %";
                                            document.getElementById('wind_widget2').innerHTML = Math.round(windSpeed*3.6)+" km/h";
                                            document.getElementById("logo_meteo3").src="images/meteo/"+weather+".png";
                                            document.getElementById('temperature_widget3').innerHTML = Math.round(temperature)+" °C";
                                            document.getElementById('precipitation_widget3').innerHTML = precipitation+" %";
                                            document.getElementById('wind_widget3').innerHTML = Math.round(windSpeed*3.6)+" km/h";
                                            document.getElementById("logo_meteo4").src="images/meteo/"+weather+".png";
                                            document.getElementById('temperature_widget4').innerHTML = Math.round(temperature)+" °C";
                                            document.getElementById('precipitation_widget4').innerHTML = precipitation+" %";
                                            document.getElementById('wind_widget4').innerHTML = Math.round(windSpeed*3.6)+" km/h";

                                            get_travel_time(timestamp.substring(0,10), addressDomicile, addressTravail)
                                            .done(function(response){
                                                document.getElementById('walking_duration_widget1').innerHTML = response.duration_walking+" min";
                                                document.getElementById('bike_duration_widget1').innerHTML = response.duration_bike+" min";
                                                document.getElementById('car_duration_widget1').innerHTML = response.duration_car+" min";
                                                document.getElementById('bike_duration_widget2').innerHTML = response.duration_bike+" min";
                                                document.getElementById('walking_duration_widget2').innerHTML = response.duration_walking+" min";
                                                document.getElementById('car_duration_widget2').innerHTML = response.duration_car+" min";
                                                document.getElementById('walking_duration_widget3').innerHTML = response.duration_walking+" min";
                                                document.getElementById('bike_duration_widget3').innerHTML = response.duration_bike+" min";
                                                document.getElementById('car_duration_widget3').innerHTML = response.duration_car+" min";
                                                document.getElementById('walking_duration_widget4').innerHTML = response.duration_walking+" min";
                                                document.getElementById('bike_duration_widget4').innerHTML = response.duration_bike+" min";
                                                document.getElementById('car_duration_widget4').innerHTML = response.duration_car+" min";
                                                var img1= new Image();
                                                var image=get_kameo_score(weather, precipitation, temperature, windSpeed, response.duration_bike, response.duration_car);
                                                img1.onload = function() {
                                                    kameo_score_loaded=true;
                                                    document.getElementById("travel_information_2").style.display = "block";
                                                    document.getElementById("travel_information_2_loading").style.display = "none";
                                                    document.getElementById("travel_information_2_error").style.display = "none";
                                                };
                                                img1.onerror = function() {
                                                    document.getElementById("travel_information_2").style.display = "none";
                                                    document.getElementById("travel_information_2_loading").style.display = "none";
                                                    document.getElementById("travel_information_2_error").style.display = "block";
                                                };
                                                img1.src=image;

                                            });
                                        } else{
                                            console.log(response.message)
                                        }





                                    });

                                </script>
                            <?php
                            }
                            ?>

                        </div>

                    </div>

                </div>


                <!-- END: post content -->


                  <!-- Sidebar-->
                <div class="col-md-3 sidebar">

                    <!--widget blog articles-->
                    <div class="widget clearfix widget-blog-articles">
                        <h4 class="widget-title fr">Vos informations</h4>
                        <h4 class="widget-title en">Your data</h4>
                        <h4 class="widget-title nl">Uw gegevens</h4>

                        <ul class="list-posts list-medium">
                             <li class="fr">Nom
                                <small><?php echo $row["NOM"] ?></small>
                            </li>
                            <li class="en">Name
                                <small><?php echo $row["NOM"] ?></small>
                            </li>
                            <li class="nl">Naam
                                <small><?php echo $row["NOM"] ?></small>
                            </li>
                            <?php if($row["PRENOM"]!=''){
                                ?>
                                <li class="fr">Prénom
                                    <small><?php echo $row["PRENOM"] ?></small>
                                </li>
                                <li class="en">First Name
                                    <small><?php echo $row["PRENOM"] ?></small>
                                </li>
                                <li class="nl">Voornaam
                                    <small><?php echo $row["PRENOM"] ?></small>
                                </li>
                            <?php
                            } ?>


                            <?php if($row["PHONE"]!=''){
                            ?>
                                <li class="fr">Numéro de téléphone
                                    <small class="phone"><?php echo $row["PHONE"] ?></small>
                                </li>
                                <li class="en">Phone number
                                    <small class="phone"><?php echo $row["PHONE"] ?></small>
                                </li>
                                <li class="nl">Telefoonnummer
                                    <small class="phone"><?php echo $row["PHONE"] ?></small>
                                </li>
                            <?php
                            } ?>

                            <?php if($row["ADRESS"]!=''){
                            ?>
                                <li class="fr">Adresse du domicile
                                    <small><?php echo $row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'] ?></small>
                                </li>

                                <li class="en">Home adress
                                    <small><?php echo $row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'] ?></small>
                                </li>

                                <li class="nl">Adress
                                    <small><?php echo $row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'] ?></small>
                                </li>
                            <?php
                            } ?>



                            <?php if($row["WORK_ADRESS"]!=''){
                            ?>

                                <li class="fr">Lieu de travail
                                    <small><?php echo $row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'] ?></small>
                                </li>

                                <li class="en">Work place
                                    <small><?php echo $row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'] ?></small>
                                </li>

                                <li class="nl">Werk adress
                                    <small><?php echo $row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'] ?></small>
                                </li>

                            <?php
                            } ?>

                            <li class="fr">Mot de passe
                                <small>********</small>
                            </li>
                            <li class="en">Password
                                <small>********</small>
                            </li>
                            <li class="nl">Wachtwoord
                                <small>********</small>
                            </li>

                        </ul>
                        <a class="button small green button-3d rounded icon-left" data-target="#update" data-toggle="modal" href="#" onclick="initializeUpdate()">
                            <span class="fr">ACTUALISER</span>
                            <span class="en">UPDATE</span>
                            <span class="nl">UPDATE</span>
                        </a>
                        <br>

                        <?php if(!$company){
                        ?>
                        <br>
                        <br>
                            <h4 class="widget-title">
                                <span class="fr-inline">Vos statistiques en </span>
                                <span class="en-inline">Your statistics in </span>
                                <span class="nl-inline">Uw statistieken in </span>
                                <span id="year"></span>
                            </h4>
                            <ul class="list-posts list-medium">
                                <li> <span class="fr-inline"> Nombre de trajets</span><span class="en-inline"> Number of trips</span><span class="nl-inline"> Aantal reizen</span>
                                    <small id="count_trips"></small>
                                </li>
                                <li> <span class="fr-inline"> Nombre de kms</span><span class="en-inline"> Number of kms</span><span class="nl-inline"> Aantal kms</span>
                                    <small id="total_trips"></small>
                                </li>

                            </ul>

                            <script type="text/javascript">
                                var year= new Date().getFullYear();
                                document.getElementById('year').innerHTML= year;
                                var email="<?php echo $user; ?>";
                                var addressDomicile=get_address_domicile();
                                var addressTravail=get_address_travail();

                                $.ajax({
                                    url: 'include/calendar_management.php',
                                    type: 'post',
                                    data: { "email":email, "year":year, action:"statistics"},
                                    success: function(text){
                                        if (text.response == 'error') {
                                            console.log(text.message);
                                        }
                                        var count = text.count;

                                        $.ajax({
                                            url: 'include/get_directions.php',
                                            type: 'post',
                                            data: {"address_start": addressDomicile, "address_end": addressTravail},
                                            success: function(text){
                                                if (text.response == 'error') {
                                                    console.log(text.message);
                                                }
                                                var distance_bike= text.distance_bike;
                                                var total_distance= (distance_bike * 2 * count)/1000;
                                                document.getElementById('count_trips').innerHTML= count;
                                                document.getElementById('total_trips').innerHTML= Math.round(total_distance)+" kms";

                                            }
                                        })
                                    }
                                });

                            </script>
                            <div class="modal fade" id="calendrier" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">

                                                <h4>Mes trajets "maison - boulot" à vélo </h4>
                                                <div id="my_calendar_header" class="pager pager-modern text-center">
                                                </div><br>

                                                <div id="my_calendar_body" class="container">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fr" class="modal-footer">
                                            <button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
                                        </div>
                                        <div class="en" class="modal-footer">
                                            <button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
                                        </div>
                                        <div class="nl" class="modal-footer">
                                            <button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script type="text/javascript">
                                    function construct_calendar_header(month){
                                    var daysFR=['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                                    var daysEN=['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    var daysNL=['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'];

                                    var monthFR=['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                                    var monthEN=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    var monthNL=['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];

                                    var string_header_calendar="";

                                    if (month+3 > new Date().getMonth()){
                                        var temp="<a class=\"pager-prev\" href=\"#\" onclick=construct_calendar_header("+(month-1)+")><span><i class=\"fa fa-chevron-left\"></i>"+monthFR[month-1]+"</span></a>";
                                        string_header_calendar=string_header_calendar.concat(temp);

                                    }

                                    var temp="<a class=\"pager-all\" href=\"#\"><span class=\"text-green\">"+monthFR[month]+"</span></a>"
                                    string_header_calendar=string_header_calendar.concat(temp);

                                    if( month < new Date().getMonth()){
                                        var temp="<a class=\"pager-next\" href=\"#\" onclick=construct_calendar_header("+(month+1)+")><span>Septembre<i class=\"fa fa-chevron-right\"></i></span></a>";
                                        string_header_calendar=string_header_calendar.concat(temp);
                                    }

                                    document.getElementById("my_calendar_header").innerHTML=string_header_calendar;

                                    construct_calendar_body(month);

                                }

                                function construct_calendar_body(month){
                                    var daysFR=['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
                                    var daysEN=['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                    var daysNL=['Zon', 'Maa', 'Din', 'Woe', 'Don', 'Vri', 'Zat'];
                                    var year= new Date().getFullYear();
                                    var date_start=new Date(year, month, 1);
                                    var date_end= new Date(date_start);
                                    var email = "<?php echo $user; ?>";
                                    date_end.setMonth(date_end.getMonth()+1);


                                    /* Initialisation part. We define the beginning of first line only. All the other lines will be defined in the body section here below */

                                    var date_temp = new Date(date_start);
                                    start_day=date_temp.getDay();
                                    var i=1;
                                    var string_calendar="<div class=\"row seven-cols\">";
                                    var temp="<div class=\"col-md-1\"  style=\"margin-right: 8px\"></div>";

                                    var current_month=new Date().getMonth();
                                    current_month=current_month+1;
                                    month=month+1;
                                    month=(month>9 ? '' : '0') + month;

                                    /* We get all the already booked days*/
                                    var Days;
                                    $.ajax({
                                        url: 'include/calendar_management.php',
                                        type: 'post',
                                        data: { "email": email, "month":month, "year":year, action:"retrieve"},
                                        success: function(text){
                                            if (text.response == 'error') {
                                                console.log(text.message);
                                            }
                                            Days = text.days;


                                            /* If first day is Sunday, we should consider it at 7th day of the week and not first one */
                                            if(start_day==0){
                                                start_day=7;
                                            }
                                            while (i<start_day){
                                                string_calendar=string_calendar.concat(temp);
                                                i++;
                                            }


                                            while (date_temp<date_end){
                                                var start_string="";
                                                var end_string="";

                                                /* First, we construct the new line. If the day is the first one of the month, we must avoir to add the new line insertion as already foreseen in the initialisation part */

                                                if(date_temp.getDay()==1 && date_temp.getDate()!=1){
                                                    start_string="<div class=\"row seven-cols\">";
                                                }

                                                /* If the day is a sunday, we close the line */
                                                else if(date_temp.getDay()==0){
                                                    end_string="</div>";
                                                }

                                                string_calendar=string_calendar.concat(start_string);
                                                /* If it's saturday on sunday, we avoid to display the checkbox */
                                                if(date_temp.getDay()==6 || date_temp.getDay()==0){
                                                    var body_string="<div class=\"col-md-1 button small grey-light text-white\" style=\"margin-right: 8px\">"+daysFR[date_temp.getDay()]+" <b>"+date_temp.getDate()+"</b></div>";
                                                }
                                                else if (month-1== new Date().getMonth() && date_temp.getDate() == new Date().getDate()){
                                                    var body_string="<div class=\"col-md-1 button small red\" style=\"margin-right: 8px\">"+daysFR[date_temp.getDay()]+" <b>"+date_temp.getDate()+"</b></div>";
                                                }
                                                else if ((month-1== new Date().getMonth() && date_temp.getDate() > new Date().getDate()) || month-1 > new Date().getMonth()){
                                                    var body_string="<div class=\"col-md-1 button small\"  style=\"margin-right: 8px\">"+daysFR[date_temp.getDay()]+" <b>"+date_temp.getDate()+"</b></div>";
                                                }

                                                /*if day already selected, we display it as such*/
                                                else if (Days.includes(date_temp.getDate()))
                                                {
                                                    var body_string="<div class=\"col-md-1 button small green\"  style=\"margin-right: 8px\" id=\""+[date_temp.getFullYear(), month, (date_temp.getDate()>9 ? '' : '0') + date_temp.getDate()].join('')+"\" onclick=\"clickBikeDay(this)\">"+daysFR[date_temp.getDay()]+" <b>"+date_temp.getDate()+"</b> <i class=\"fa fa-bicycle\"></i> </div>";
                                                }
                                                else{
                                                    var body_string="<div class=\"col-md-1 button small\"  style=\"margin-right: 8px\" id=\""+[date_temp.getFullYear(), month, (date_temp.getDate()>9 ? '' : '0') + date_temp.getDate()].join('')+"\" onclick=\"clickBikeDay(this)\">"+daysFR[date_temp.getDay()]+" <b>"+date_temp.getDate()+"</b> </div>";
                                                }
                                                string_calendar=string_calendar.concat(body_string);
                                                string_calendar=string_calendar.concat(end_string);

                                                date_temp.setDate(date_temp.getDate()+1);
                                            }

                                            if(date_temp.getDay!=0){
                                                string_calendar=string_calendar.concat("</div>");
                                            }
                                            document.getElementById("my_calendar_body").innerHTML=string_calendar;
                                        }
                                    });
                                }
                            construct_calendar_header(new Date().getMonth());
                            </script>
                        <?php
                        }
                        ?>
                        <br>
                        <a href="docs/cgvfr.pdf" target="_blank" title="Pdf" class="fr">Conditions générales</a>
                        <a href="docs/cgven.pdf" target="_blank" title="Pdf" class="en">Terms and Conditions</a>
                        <a href="docs/cgven.pdf" target="_blank" title="Pdf" class="nl">Algemene voorwaarden</a>
                        <br>
                        <a href="#" title="Pdf">Bike policy</a>
                        <br><br>
                        <a href="docs/manueldutilisationmykameo.pdf" target="_blank" title="Manuel d\'utilisation" class="fr">Manuel d'utilisation</a>
                        <br>
                        <a class="button small green button-3d rounded icon-left" data-target="#tellus" data-toggle="modal" href="#" onclick="initializeTellUs()">
                            <span class="fr">Partagez vos impressions</span>
                            <span class="en">Tell us what you feel</span>
                            <span class="nl">Vertel ons wat je voelt</span>
                        </a>
                        <br>
                        <a class="button small red button-3d rounded icon-left" onclick="deconnexion()">
                            <span class="fr">Déconnexion</span>
                            <span class="en">Disconnect</span>
                            <span class="nl">Loskoppelen</span>
                        </a>
                    </div>
                    <!--end: widget blog articles-->
                </div>
                <!-- END: Sidebar-->


            </div>
        </div>
    </section>
    <!-- END: SECTION -->

    <?php
}else{

?>

    <!-- CONTENT -->
    <section class="content">
        <div class="container">
            <div class="row">


                <!-- post content -->
                <div class="post-content float-right col-md-9">
                    <!-- Post item-->
                    <div class="post-item">
                        <div class="post-content-details">
                            <div class="heading heading text-left m-b-20">
                                <h2 class="fr">Connexion à MyKameo</h2>
                                <h2 class="en">Log-in to MyKameo</h2>
                                <h2 class="nl">Log in op MyKameo</h2>


                                <form id="re-connexion" class="form-transparent-grey" action="include/access_management.php" role="form" method="post">
                                    <div class="form-group">
                                        <label class="sr-only fr">Adresse mail</label>
                                        <label class="sr-only en">E-mail</label>
                                        <label class="sr-only nl">Mail</label>
                                        <input type="email" name="userID" class="form-control" placeholder="Adresse mail" autocomplete="username">
                                    </div>
                                    <div class="form-group m-b-5">
                                        <label class="sr-only fr">Mot de passe</label>
                                        <label class="sr-only en">Password</label>
                                        <label class="sr-only nl">Wachtwoord</label>
                                        <input type="password" name="password" class="form-control" placeholder="Mot de passe" autocomplete="current-password">
                                    </div>
                                    <div class="form-group form-inline text-left ">


                                        <a data-target="#lostPassword" data-toggle="modal" data-dismiss="modal" href="#" class="right fr"><small>Mot de passe oublié?</small></a>
                                        <a data-target="#lostPassword" data-toggle="modal" data-dismiss="modal" href="#" class="right nl"><small>Wachtwoord kwijt?</small></a>
                                        <a data-target="#lostPassword" data-toggle="modal" data-dismiss="modal" href="#" class="right en"><small>Password lost?</small></a>
                                    </div>
                                    <div class="text-left form-group">
                                        <button class="button effect fill fr" type="submit">Accéder</button>
                                        <button class="button effect fill en" type="submit">Confirm</button>
                                        <button class="button effect fill nl" type="submit">Bevestingen</button>
                                    </div>
                                </form>
                                <script type="text/javascript">
                                    jQuery("#re-connexion").validate({

                                        submitHandler: function(form) {
                                            jQuery(form).ajaxSubmit({
                                                success: function(text) {
                                                    if (text.response == 'success') {
                                                        <?php
                                                            if(isset($_GET['feedback'])){
                                                                ?>
                                                                    window.location.href = "mykameo.php"+<?php echo "feedback=".$_GET['feedback']; ?>;
                                                                <?php
                                                            }else{
                                                                ?>
                                                                    window.location.href = "mykameo.php";
                                                                <?php
                                                            }
                                                            ?>
                                                    } else {
                                                        $.notify({
                                                            message: text.message
                                                        }, {
                                                            type: 'danger'
                                                        });
                                                    }
                                                }
                                            });
                                        }
                                    });

                                </script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
}
?>




<div class="modal fade" id="resume" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3 class="fr">Résumé de votre commande</h3>
						<h3 class="en">Resume</h3>
						<h3 class="nl">Geresumeerd</h3>

                        <div class="col-sm-10">
						<h4>Prise en charge du vélo</h4>
						</div>

						<div class="col-sm-4">
                        <h4><span class="fr"> Jour : </span></h4>
                        <h4><span class="en"> Start : </span></h4>
                        <h4><span class="nl"> Start : </span></h4>

                        <p><span id="daySpan"></span>
                        /
                        <span id="monthSpan"></span>
                        /
                        <span id="yearSpan"></span></p>
                        </div>



                        <div class="col-sm-4">
                            <h4><span class="fr"> Heure : </span></h4>
                            <h4><span class="en"> at : </span></h4>
                            <h4><span class="nl"> at : </span></h4>


                            <p><span id="hourStartSpan"></span></p>
                       	</div>

                       <div class="col-sm-4">
                        <h4><span class="fr" >Lieu :</span></h4>
                        <h4><span class="en" >from</span></h4>
                        <h4><span class="nl" >from</span></h4>

                        <p><span id="startBuildingSpan"></span></p>
                        </div>



						<div class="col-sm-10">
						<h4>Remise du vélo</h4>
						</div>
				        <div class="col-sm-4">
                            <h4><span class="fr"> Jour : </span></h4>
                            <h4><span class="en"> Start : </span></h4>
                            <h4><span class="nl"> Start : </span></h4>

                            <p><span id="dayDepositSpan"></span>
                            /
                            <span id="monthDepositSpan"></span>
                            /
                            <span id="yearDepositSpan"></span></p>
                        </div>
						<div class="col-sm-4">
                        <h4><span class="fr">Heure : </span></h4>
                        <h4><span class="en">Bike deposit : </span></h4>
                        <h4><span class="nl">Bike deposit : </span></h4>

                        <p><span id="hourEndSpan"></span></p>
                        </div>

						<div class="col-sm-4">
                        <h4><span class="fr" >Lieu :</span></h4>
                        <h4><span class="en" >from</span></h4>
                        <h4><span class="nl" >from</span></h4>

                        <p><span id="endBuildingSpan"></span></p>
                        </div>

						<div class="col-sm-12" id="lockingCodeDiv">
                            <h4><span class="fr" >Code de réservation :</span></h4>
                            <h4><span class="en" >Locking code</span></h4>
                            <h4><span class="nl" >Locking code</span></h4>

                            <p><span id="lockingCode"></span></p>
                        </div>

                       <div class="col-sm-10">
                        <h4>Votre vélo: </h4>
                            <div class="col-md-4">
                            <img src="" id="resumeBikeImage" alt="image" />
                            </div>
                        </div>
                        <form id="widget-new-booking" class="form-transparent-grey" action="include/new_booking.php" role="form" method="post">
                            <!--
                            <label for="widget-new-booking=trip-type">Type de voyage</label>
                            <select title="trip type" class="selectpicker" id="widget-new-booking=trip-type" name="widget-new-booking=trip-type">
                              <option value="domiciletravail">Trajet domicile-travail</option>
                              <option value="mission">Déplacement pour travail</option>
                              <option value="loisir">Loisir</option>
                            </select>
                            <script type="text/javascript">
                            $('.widget-new-booking=trip-type').change(function(){
                                manage_elegibility_ecoprime(document.getElementsById('widget-new-booking=trip-type').value);
                            });
                            </script>

                            <p id="text-eligibility-prime" class="fr text-green">Ce trajet est éligible pour le paiement de prime écologique. Les informations liées à votre trajet vous seront demandées à l'étape suivante.</p>-->
                            <input id="widget-new-booking-timestamp-start" name="widget-new-booking-timestamp-start" type="hidden">
                            <input id="widget-new-booking-timestamp-end" name="widget-new-booking-timestamp-end" type="hidden">
                            <input id="widget-new-booking-building-start" name="widget-new-booking-building-start" type="hidden">
                            <input id="widget-new-booking-building-end" name="widget-new-booking-building-end" type="hidden">
                            <input id="widget-new-booking-frame-number" name="widget-new-booking-frame-number" type="hidden">
                            <input id="widget-new-booking-mail-customer" name="widget-new-booking-mail-customer" type="hidden" value="<?php echo $user; ?>">
                            <input id="widget-new-booking-locking-code" name="widget-new-booking-locking-code" type="hidden">
                            <input id="widget-new-booking-date-start" name="widget-new-booking-date-start" type="hidden">
                            <input id="widget-new-booking-date-end" name="widget-new-booking-date-end" type="hidden">

                            <br>
                            <div class="text-left form-group">
                                <button  class="button effect fill fr" type="submit"><i class="fa fa-check"></i>&nbsp;Confirmer</button>
                                <button  class="button effect fill en" type="submit"><i class="fa fa-check"></i>&nbsp;Confirm</button>
                                <button  class="button effect fill nl" type="submit"><i class="fa fa-check"></i>&nbsp;Verzenden</button>

                            </div>
                        </form>
					</div>
				</div>
			</div>
            <script type="text/javascript">
                jQuery("#widget-new-booking").validate({

                    submitHandler: function(form) {

                        jQuery(form).ajaxSubmit({
                            success: function(text) {
                                if (text.response == 'success') {
                                    $.notify({
                                        message: text.message
                                    }, {
                                        type: 'success'
                                    });
                                    $('#resume').modal('toggle');
                                    document.getElementById('velos').innerHTML= "";
									document.getElementById("velos").style.display = "none";
									document.getElementById("travel_information").style.display = "none";

                                    window.scrollTo(0, 0);
                                    getHistoricBookings();

                                } else {
                                    $.notify({
                                        message: text.message
                                    }, {
                                        type: 'danger'
                                    });
                                }
                            }
                        });
                    }
                });

            </script>

		</div>
	</div>
</div>

<div class="modal fade" id="usersListing" tabindex="1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
            <div data-example-id="contextual-table" class="bs-example">
                        <span id="usersList"></span>
            </div>

			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>





<div class="modal fade" id="deleteUser" tabindex="1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr">Supprimer un utilisateur</h4>

						<form id="widget-deleteUser-form" action="include/delete-user.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                                <label for="widget-deleteUser-form-firstname"  class="fr">Prénom</label>
                                <label for="widget-deleteUser-form-firstname"  class="en">Firstname</label>
                                <label for="widget-deleteUser-form-firstname"  class="nl">Voornaam</label>
                                <input type="text" id="widget-deleteUser-form-firstname" readonly="readonly" name="widget-deleteUser-form-firstname" class="form-control required">

                                <label for="widget-deleteUser-form-name"  class="fr">Nom</label>
                                <label for="widget-deleteUser-form-name"  class="en">Name</label>
                                <label for="widget-deleteUser-form-name"  class="nl">Achternaam</label>
                                <input type="text" id="widget-deleteUser-form-name" readonly="readonly" name="widget-deleteUser-form-name" class="form-control required">


                                <label for="widget-deleteUser-form-mail"  class="fr">E-mail</label>
                                <label for="widget-deleteUser-form-mail"  class="en">E-mail</label>
                                <label for="widget-deleteUser-form-mail"  class="nl">E-mail</label>
                                <input type="text" id="widget-deleteUser-form-mail" readonly="readonly" name="widget-deleteUser-form-mail" class="form-control">
                                <input type="text" id="widget-deleteUser-form-requestor" name="widget-deleteUser-form-requestor" class="form-control hidden" value="<?php echo $user; ?>">


                            </div>
                            <h4>Confirmation de suppression</h4>
                            <label for="widget-deleteUser-form-confirmation" class="fr">Veuillez écrire "DELETE" afin de confirmer la suppression</label>
                            <input type="text" id="widget-deleteUser-form-confirmation" name="widget-deleteUser-form-confirmation" class="form-control">


							<button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
							<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
							<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>

						</form>
						<script type="text/javascript">
							jQuery("#widget-deleteUser-form").validate({
								submitHandler: function(form) {

									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                $('#usersListing').modal('toggle');
                                                get_users_listing();
												$('#deleteUser').modal('toggle');

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

						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="reactivateUser" tabindex="1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr">Réactiver un utilisateur</h4>

						<form id="widget-reactivateUser-form" action="include/reactivate-user.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                                <label for="widget-reactivateUser-form-firstname"  class="fr">Prénom</label>
                                <label for="widget-reactivateUser-form-firstname"  class="en">Firstname</label>
                                <label for="widget-reactivateUser-form-firstname"  class="nl">Voornaam</label>
                                <input type="text" id="widget-reactivateUser-form-firstname" readonly="readonly" name="widget-reactivateUser-form-firstname" class="form-control required">

                                <label for="widget-reactivateUser-form-name"  class="fr">Nom</label>
                                <label for="widget-reactivateUser-form-name"  class="en">Name</label>
                                <label for="widget-reactivateUser-form-name"  class="nl">Achternaam</label>
                                <input type="text" id="widget-reactivateUser-form-name" readonly="readonly" name="widget-reactivateUser-form-name" class="form-control required">


                                <label for="widget-reactivateUser-form-mail"  class="fr">E-mail</label>
                                <label for="widget-reactivateUser-form-mail"  class="en">E-mail</label>
                                <label for="widget-reactivateUser-form-mail"  class="nl">E-mail</label>
                                <input type="text" id="widget-reactivateUser-form-mail" readonly="readonly" name="widget-reactivateUser-form-mail" class="form-control">
                                <input type="text" id="widget-reactivateUser-form-requestor" name="widget-reactivateUser-form-requestor" class="form-control hidden" value="<?php echo $user; ?>">


                            </div>

							<button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Confirmer</button>
							<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Confirm</button>
							<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Confirm</button>

						</form>
						<script type="text/javascript">
							jQuery("#widget-reactivateUser-form").validate({
								submitHandler: function(form) {

									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                $('#usersListing').modal('toggle');
                                                $('#reactivateUser').modal('toggle');

                                                get_users_listing();

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

						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="updateUserInformation" tabindex="1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr text-green">Mise à jour des informations</h4>

						<form id="widget-updateUser-form" action="include/updateUserInformation.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                            	<div class="col-sm-6">
                                <label for="widget-updateUser-form-firstname"  class="fr">Prénom</label>
                                <label for="widget-updateUser-form-firstname"  class="en">Firstname</label>
                                <label for="widget-updateUser-form-firstname"  class="nl">Voornaam</label>
                                <input type="text" id="widget-updateUser-form-firstname" name="widget-updateUser-form-firstname" class="form-control required">
                                </div>

								<div class="col-sm-6">
                                <label for="widget-updateUser-form-name"  class="fr">Nom</label>
                                <label for="widget-updateUser-form-name"  class="en">Name</label>
                                <label for="widget-updateUser-form-name"  class="nl">Achternaam</label>
                                <input type="text" id="widget-updateUser-form-name" name="widget-updateUser-form-name" class="form-control required">
                                </div>

								<div class="col-sm-6">
                                <label for="widget-updateUser-form-mail"  class="fr">E-mail</label>
                                <label for="widget-updateUser-form-mail"  class="en">E-mail</label>
                                <label for="widget-updateUser-form-mail"  class="nl">E-mail</label>
                                <input type="text" id="widget-updateUser-form-mail" name="widget-updateUser-form-mail" readonly="readonly" class="form-control">
                                <input type="text" id="widget-updateUser-form-requestor" name="widget-updateUser-form-requestor" class="form-control hidden" value="<?php echo $user; ?>">
                                </div>

                                <div class="col-sm-6">
                                <label for="widget-updateUser-form-status"  class="fr">Status</label>
                                <input type="text" id="widget-updateUser-form-status" name="widget-updateUser-form-status" readonly="readonly" class="form-control">
                                </div>

								<div class="col-md-4">
                                    <label for="fleetManager">Fleet manager</label>
                                    <input type="checkbox" name="fleetManager" class="form-control">
                                </div>

                            </div>
                            <div class="form-group col-sm-12" id="buildingUpdateUser"></div>

                            <div class="form-group col-sm-12" id="bikeUpdateUser"></div>
                            <div id="updateUserSendButton"></div>


						</form>
                        <div id="deleteUserButton"></div>
						<script type="text/javascript">
							jQuery("#widget-updateUser-form").validate({

								submitHandler: function(form) {

									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});

                                                get_users_listing();
                                                $('#updateUserInformation').modal('toggle');
                                                $('#usersListing').modal('toggle');

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

						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="BikesListing" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
            <div data-example-id="contextual-table" class="bs-example">
                        <span id="bikeDetails"></span>
            </div>

			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="BikesListingAdmin" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
            <div data-example-id="contextual-table" class="bs-example">
                        <span id="bikeDetailsAdmin"></span>
            </div>

			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="boxesListing" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
            <div data-example-id="contextual-table" class="bs-example">
                        <span id="boxesListingSpan"></span>
            </div>

			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="tasksListing" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>

            <div class="col-md-3">
                <label for="taskOwnerSelection">Filtrer sur Owner</label>
                <select class="taskOwnerSelection" name="taskOwnerSelection">
                </select>
            </div>
            <div class="col-md-3">
                <label for="tasksListing_number">Nombre de résultats</label>
                <select class="form-control required tasksListing_number" name="tasksListing_number">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>

            <div class="separator"></div>

            <div data-example-id="contextual-table" class="bs-example">
                        <span id="tasksListingSpan"></span>
            </div>

            <div class="separator"></div>
            <h4 class="text-green">Statistiques sur les tâches :</h4>
            <div class="col-md-3">
                <label for="taskOwnerSelection2">Filtrer sur Owner</label>
                <select class="taskOwnerSelection2" name="taskOwnerSelection2">
                </select>
            </div>

            <div class="col-md-3">
                <label for="numberOfDays">Nombre de jours</label>
                <input type="text" class="numberOfDays form-control required" name="numberOfDays" value="30">
            </div>

            <canvas id="myChart2" width="400" height="300"></canvas>

			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="offersListing" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>

            <!--<div class="col-md-3">
                <label for="companySelection">Filtrer sur la société</label>
                <select class="companySelection" name="companySelection">
                </select>
            </div>

            <div class="separator"></div>
            -->
            <div data-example-id="contextual-table" class="bs-example">
                        <span id="contractsListingSpan"></span>
            </div>

            <div class="separator"></div>

            <div data-example-id="contextual-table" class="bs-example">
                        <span id="offersListingSpan"></span>
            </div>

            <div class="separator"></div>

            <div data-example-id="contextual-table" class="bs-example">
                        <span id="costsListingSpan"></span>
            </div>

            <div class="separator"></div>
            <h4 class="text-green">Graphique :</h4>


            <canvas id="myChart" width="400" height="300"></canvas>


			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="feedbacksListing" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>

            <div data-example-id="contextual-table" class="bs-example">
                        <span id="feedbacksListingSpan"></span>
            </div>

            <div class="separator"></div>

			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="conditionListing" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>

            <div data-example-id="contextual-table" class="bs-example">
                        <span id="spanConditionListing"></span>
            </div>

			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="companyConditions" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
                        <h4 class="fr-inline text-green">Modifier les réglages des réservations :</h4>
                        <h4 class="en-inline text-green">Modify booking settings:</h4>
                        <h4 class="nl-inline text-green">Modify booking settings :</h4><br>
						<form id="widget-updateCompanyConditions-form" class="form-inline" action="include/updateCompanyConditions.php" role="form" method="post">



                            <span class="fr-inline"> <strong>Nom du groupe : </strong></span>
                            <span class="en-inline"> Group name: </span>
                            <span class="nl-inline"> Group name: </span>
                            <input type="test" name="name" class="form-control required" style="width: 20em;"><br/><br/>
                            <span class="fr-inline"> Durée maximale avant prochaine réservation (jours) : </span>
                            <span class="en-inline"> Maximum delay before next booking (days): </span>
                            <span class="nl-inline"> Maximum delay before next booking (days): </span>
                            <input type="number" class="form-control required" name="daysInAdvance" style="width: 7em;"><br/><br/>
                            <span class="fr-inline"> Durée maximale d'une réservation (heures) : </span>
                            <span class="en-inline"> Maximal duration of a booking (hours): </span>
                            <span class="nl-inline"> Maximal duration of a booking (hours): </span>
                            <input type="number" class="form-control required" name="bookingLength" style="width: 7em;"><br/><br/>
                            <span class="fr-inline"> Nombre maximum de réservations par an (9999 pour illimité): </span>
                            <span class="en-inline"> Maximal number of bookings per year: </span>
                            <span class="nl-inline"> Maximal number of bookings per year </span>
                            <input type="number" class="form-control required" name="bookingsPerYear" max="9999" style="width: 7em;"><br/><br/>
                            <span class="fr-inline"> Nombre maximum de réservations par mois (9999 pour illimité): </span>
                            <span class="en-inline"> Maximal number of bookings per month: </span>
                            <span class="nl-inline"> Maximal number of bookings per month </span>
                            <input type="number" class="form-control required" name="bookingsPerMonth" max="9999" style="width: 7em;"><br/><br/>

                            <div class="col-sm-6 jumbotron jumbotron-border">
                                <h4>Réglages de début de réservation</h4>
                                <div class="col-sm-12">
                                    <p><span class="fr-inline"> Première heure possible pour prendre un vélo : </span>
                                    <span class="en-inline"> First possible hour to take a bike: </span>
                                    <span class="nl-inline"> First possible hour to take a bike: </span>
                                    <input type="number" class="form-control required" name="startIntakeBooking" max="23" style="width: 7em;">
                                    <p>
                                </div>
                                <div class="col-sm-12">
                                    <p><span class="fr-inline"> Dernière heure possible afin de prendre un vélo : </span>
                                    <span class="en-inline"> Last possible hour to take a bike: </span>
                                    <span class="nl-inline"> Last possible hour to take a bike: </span>
                                    <input type="number" class="form-control required" name="endIntakeBooking" max="23" style="width: 7em;">
                                    </p>
                                </div>
                                <div class="col-sm-12">
                                    <h5>Début de réservation possible aux jours suivants:</h5>
                                    <span class="intakeBookingDays"></span>
                                </div>
                            </div>
                            <div class="col-sm-6 jumbotron jumbotron-border">
                                <h4>Réglages de fin de réservation</h4>
                                <div class="col-sm-12">
                                    <p><span class="fr-inline"> Première heure possible pour rendre un vélo : </span>
                                    <span class="en-inline"> First possible hour to deposit a bike: </span>
                                    <span class="nl-inline"> First possible hour to deposit a bike: </span>
                                    <input type="number" class="form-control required" name="startDepositBooking" max="23" style="width: 7em;">
                                    <p>
                                </div>
                                <div class="col-sm-12">
                                    <p><span class="fr-inline"> Dernière heure possible afin de rendre un vélo : </span>
                                    <span class="en-inline"> Last possible hour to deposit a bike: </span>
                                    <span class="nl-inline"> Last possible hour to deposit a bike: </span>
                                    <input type="number" class="form-control required" name="endDepositBooking" max="23" style="width: 7em;">
                                    </p>
                                </div>
                                <div class="col-sm-12">
                                    <h5>Fin de réservation possible aux jours suivants:</h5>
                                    <span class="depositBookingDays"></span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <h4>Accès des utilisateurs à ce groupe de conditions</h4>
                                <p class="text-red">Attention, attribuer un utilisateur à ce groupe supprimera automatiquement son appartenance à un autre groupe</p>
                                <span id="groupConditionUsers"></span>
                            </div>

							<button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Sauvegarder</button>
							<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Save</button>
							<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Besparen</button>
                            <input type="text" name="email" value="<?php echo $user; ?>" hidden>
                            <input type="text" name="id" value="" hidden>
                            <input type="text" name="action" hidden>
                        </form>
						<script type="text/javascript">
							jQuery("#widget-updateCompanyConditions-form").validate({
								submitHandler: function(form){
									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
												$('#companyConditions').modal('toggle');
                                                list_condition();

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

						</script>

                    </div>
                </div>
            </div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="companyListing" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>

            <div data-example-id="contextual-table" class="bs-example">
                        <span id="companyListingSpan"></span>
            </div>
            <div class="separator">            </div>

            <h4 class="text-green">Statistiques sur le nombre de clients : </h4>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="dtp_input3" class="control-label">Date de début</label>
                    <div class="input-group date form_date_start_client col-md-12" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                        <input class="form-control" size="16" type="text" value="" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input3" value="" /><br/>
                </div>
            </div>

			<div class="col-sm-3">
                <div class="form-group">
                    <label for="dtp_input4" class="control-label">Date de fin</label>
                    <div class="input-group date form_date_end_client col-md-12" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                        <input class="form-control" size="16" type="text" value="" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input4" value="" /><br/>
                </div>
            </div>



            <canvas id="myChart3" style="display: block; width: 800px; height: 400px;" width="800" height="400" class="chartjs-render-monitor"></canvas>

            <div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

    $('.form_date_start_client').datetimepicker({
    language:  'fr',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0
    });

    $('.form_date_end_client').datetimepicker({
    language:  'fr',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0
    });



    $('.form_date_start_client').change(function(){
        generateCompaniesGraphic($('.form_date_start_client').data("datetimepicker").getDate(), $('.form_date_end_client').data("datetimepicker").getDate());
    });
    $('.form_date_end_client').change(function(){
        generateCompaniesGraphic($('.form_date_start_client').data("datetimepicker").getDate(), $('.form_date_end_client').data("datetimepicker").getDate());
    });

</script>



<div class="modal fade" id="portfolioManager" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>

            <div data-example-id="contextual-table" class="bs-example">
                        <span id="portfolioBikesListing"></span>
            </div>

			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="billingListing" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
            <div class="dropdown companyBillSelection">
              <div class="col-md-3">
              	<ul class="nav">
                    <li class="dropdown" role="presentation">
                        <a aria-expanded="false" href="#" data-toggle="dropdown" class="dropdown-toggle"> <span class="billSelectionText">Choix de la société</span><span class="caret"></span> </a>
                        <ul role="menu" class="dropdown-menu billSelection">
                        </ul>
                    </li>
                 </ul>
               </div>
            </div>
            <div class="separator companyBillSelection"></div>

            <div data-example-id="contextual-table" class="bs-example">
                        <span id="billsListing"></span>
            </div>

			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>





<div class="modal fade" id="addBill" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr text-green">Ajouter une facture</h4>

						<form id="widget-addBill-form" action="include/add_bill.php" role="form" method="post">


                            <div class="form-group col-md-12">
                                <h4 class="fr text-green">Informations générales</h4>


                                <div class="col-md-12">
                                    <div class="col-md-4 IDAddBill hidden">
                                        <label for="ID"  class="fr">ID</label>
                                        <label for="ID"  class="en">ID</label>
                                        <label for="ID"  class="nl">ID</label>
                                        <input type="number" name="ID" class="form-control" readonly='readonly'>
                                    </div>
                                    <div class="col-md-4 IDAddBillOut hidden">
                                        <label for="ID_OUT"  class="fr">ID OUT</label>
                                        <label for="ID_OUT"  class="en">ID OUT</label>
                                        <label for="ID_OUT"  class="nl">ID OUT</label>
                                        <input type="number" name="ID_OUT" class="form-control" readonly='readonly'>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label for="widget-addBill-form-company"  class="fr">Originateur</label>
                                        <label for="widget-addBill-form-company"  class="en">Originateur</label>
                                        <label for="widget-addBill-form-company"  class="nl">Originateur</label>
                                        <span class="widget-addBill-form-company" name="widget-addBill-form-company"></span>
                                    </div>


                                    <div class="col-md-4">
                                        <label for="beneficiaryCompany"  class="fr">Beneficiaire</label>
                                        <label for="beneficiaryCompany"  class="en">Beneficiaire</label>
                                        <label for="beneficiaryCompany"  class="nl">Beneficiaire</label>
                                        <input type="text" name="beneficiaryCompany" class="form-control required" readonly='readonly' value="KAMEO">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="type">Type de facture</label>
                                        <select name="type">
                                            <option value="leasing">Leasing</option>
                                            <option value="achat">Achat</option>
                                            <option value="accessoire">Accessoire</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-4 widget-addBill-form-companyOther">
                                        <label for="widget-addBill-form-companyOther" class="widget-addBill-form-companyOther">Informations complémentaires (Origi.)</label>
                                        <input type="text" class="form-control widget-addBill-form-companyOther" name="widget-addBill-form-companyOther">
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4 typeOther">
                                        <label for="typeOther" class="hidden">Informations complémentaires (type)</label>
                                        <input type="text" class="form-control hidden" name="typeOther">
                                    </div>

                                </div>


                                <div class="col-md-12">

                                    <div class="col-md-4">
                                        <label for="communication"  class="fr">Communication</label>
                                        <label for="communication"  class="en">Communication </label>
                                        <label for="communication"  class="nl">Communication</label>
                                        <input type="text" name="communication" class="form-control" readonly='readonly'>
                                    </div>

                                </div>


                                <div class="separator"></div>
                                <h4 class="fr text-green">Informations sur les montants</h4>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label for="widget-addBill-form-amountHTVA"  class="fr">Montant (HTVA)</label>
                                        <label for="widget-addBill-form-amountHTVA"  class="en">Amount (VAT ex.)</label>
                                        <label for="widget-addBill-form-amountHTVA"  class="nl">Amount (VAT ex.)</label>
                                        <input type="text" class="widget-addBill-form-amountHTVA form-control required" name="widget-addBill-form-amountHTVA">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="widget-addBill-form-VAT" class="fr">TVA ? </label>
                                        <label for="widget-addBill-form-VAT" class="nl">TVA ?</label>
                                        <label for="widget-addBill-form-VAT" class="en">TVA ? </label>
                                        <input type="checkbox" class="widget-addBill-form-VAT form-control" name="widget-addBill-form-VAT" />
                                    </div>

                                    <div class="col-md-4">
                                        <label for="widget-addBill-form-amountTVAC"  class="fr">Montant (TVAC)</label>
                                        <label for="widget-addBill-form-amountTVAC"  class="en">Amount (VAT inc.)</label>
                                        <label for="widget-addBill-form-amountTVAC"  class="nl">Amount (VAT inc.)</label>
                                        <input type="text" class="widget-addBill-form-amountTVAC form-control required" name="widget-addBill-form-amountTVAC" readonly="readonly">
                                    </div>
                                </div>

								<div class="separator"></div>
                                <h4 class="fr text-green">Informations sur les dates</h4>
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <label for="widget-addBill-form-date"  class="fr">Date</label>
                                        <label for="widget-addBill-form-date"  class="en">Date</label>
                                        <label for="widget-addBill-form-date"  class="nl">Date</label>
                                        <input type="date" class="widget-addBill-form-date form-control required" name="widget-addBill-form-date">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="widget-addBill-form-datelimite"  class="fr">Date limite de paiement</label>
                                        <label for="widget-addBill-form-datelimite"  class="en">Date limite de paiement </label>
                                        <label for="widget-addBill-form-datelimite"  class="nl">Date limite de paiement</label>
                                        <input type="date" class="widget-addBill-form-datelimite form-control required" name="widget-addBill-form-datelimite">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <label for="widget-addBill-form-sent"  class="fr">Envoyée ?</label>
                                        <label for="widget-addBill-form-sent"  class="en">Sent ?</label>
                                        <label for="widget-addBill-form-sent"  class="nl">Sent ?</label>
                                        <input type="checkbox" name="widget-addBill-form-sent" >
                                    </div>

                                    <div class="col-md-6">
                                        <label for="widget-addBill-form-sendingDate"  class="fr">Date d'envoi</label>
                                        <label for="widget-addBill-form-sendingDate"  class="en">Sending date </label>
                                        <label for="widget-addBill-form-sendingDate"  class="nl">Sending date</label>
                                        <input type="date" class="widget-addBill-form-sendingDate form-control" name="widget-addBill-form-sendingDate">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <label for="widget-addBill-form-paid"  class="fr">Payée ?</label>
                                        <label for="widget-addBill-form-paid"  class="en">Paid ?</label>
                                        <label for="widget-addBill-form-paid"  class="nl">Paid ?</label>
                                        <input type="checkbox" name="widget-addBill-form-paid" >
                                    </div>

                                    <div class="col-md-6">
                                        <label for="widget-addBill-form-paymentDate"  class="fr">Date de paiement</label>
                                        <label for="widget-addBill-form-paymentDate"  class="en">Payment date </label>
                                        <label for="widget-addBill-form-paymentDate"  class="nl">Payment date</label>
                                        <input type="date" class="widget-addBill-form-paymentDate form-control " name="widget-addBill-form-paymentDate" >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group col-sm-6">
                                        <label for="widget-addBill-form-file"  class="fr">Facture</label>
                                        <label for="widget-addBill-form-file"  class="en">Bill</label>
                                        <label for="widget-addBill-form-file"  class="nl">Bill</label>
                                        <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                                        <input type=file size=40 id="widget-addBill-form-file" class="form-control required" name="widget-addBill-form-file">
                                    </div>
                                </div>
							</div>
                            <input type="text" name="communicationHidden" class="hidden">
                            <input type="text" class="widget-addBill-form-email" name="widget-addBill-form-email" value="<?php echo $user; ?>" hidden>
                            <div class="separator"></div>
                            <div class="col-md-12">
                                <button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-plus"></i>Ajouter</button>
                                <button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-plus"></i>Add</button>
                                <button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-plus"></i>Add</button>
                            </div>
						</form>
						<script type="text/javascript">
							jQuery("#widget-addBill-form").validate({
								submitHandler: function(form) {
									jQuery(form).ajaxSubmit({
										success: function(response) {

											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                get_bills_listing(document.getElementsByClassName('billSelectionText')[0].innerHTML, '*', '*', '*');
												$('#addBill').modal('toggle');
                                                document.getElementById('widget-addBill-form').reset();

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
                            $('.widget-addBill-form-company').change(function(){
                                var e = document.getElementsByClassName('widget-addBill-form-company2')[0];
                                var valueSelect = e.options[e.selectedIndex].value;
                                $('#widget-addBill-form input[name=communication]').attr('readonly', true);

                                if(valueSelect=='other'){
                                    $('.widget-addBill-form-companyOther').removeClass("hidden");
                                    $('#widget-addBill-form input[name=communication]').val($('#widget-addBill-form input[name=communicationHidden]').val());
                                    $('input[name=beneficiaryCompany]').attr('readonly', true);
                                    $('input[name=beneficiaryCompany]').val('KAMEO');


                                }else if(valueSelect=='KAMEO'){
                                    $('input[name=beneficiaryCompany]').attr('readonly', false);
                                    $('input[name=beneficiaryCompany]').val('');

                                    $('#widget-addBill-form input[name=communication]').attr('readonly', false);
                                    $('#widget-addBill-form input[name=communication]').val('');

                                }

                                else{
                                    $('.widget-addBill-form-companyOther').addClass("hidden");
                                    $('#widget-addBill-form input[name=communication]').val($('#widget-addBill-form input[name=communicationHidden]').val());
                                    $('input[name=beneficiaryCompany]').attr('readonly', true);
                                    $('input[name=beneficiaryCompany]').val('KAMEO');
                                    $('.IDAddBill').removeClass("hidden");
                                    $('.IDAddBillOut').removeClass("hidden");



                                }
                            });

                            $('#widget-addBill-form input[name=beneficiaryCompany]').change(function(){
                                if($('#widget-addBill-form input[name=beneficiaryCompany]').val()=='KAMEO'){
                                    $('.IDAddBill').removeClass("hidden");
                                    $('.IDAddBillOut').removeClass("hidden");
                                }else{
                                    $('.IDAddBill').addClass("hidden");
                                    $('.IDAddBillOut').addClass("hidden");
                                }
                            });

                            $('#widget-addBill-form select[name=type]').change(function(){
                                if($('#widget-addBill-form select[name=type]').val()=="autre"){
                                    $('#widget-addBill-form input[name=typeOther]').removeClass("hidden");
                                    $('#widget-addBill-form input[name=typeOther]').addClass("required");
                                    $('#widget-addBill-form label[for=typeOther]').removeClass("hidden");
                                }else{
                                    $('#widget-addBill-form input[name=typeOther]').addClass("hidden");
                                    $('#widget-addBill-form input[name=typeOther]').removeClass("required");
                                    $('#widget-addBill-form label[for=typeOther]').addClass("hidden");
                                }
                            })
                            $('input[name=widget-addBill-form-amountHTVA]').change(function(){
                                if($('input[name=widget-addBill-form-VAT]').is(':checked')){
                                    $('input[name=widget-addBill-form-amountTVAC]').val((1.21*$('input[name=widget-addBill-form-amountHTVA]').val()).toFixed(2));
                                }else{
                                    $('input[name=widget-addBill-form-amountTVAC]').val($('input[name=widget-addBill-form-amountHTVA]').val());
                                }
                              });
                            $('.widget-addBill-form-VAT').change(function(){
                                if($('input[name=widget-addBill-form-VAT]').is(':checked')){
                                    $('input[name=widget-addBill-form-amountTVAC]').val((1.21*$('input[name=widget-addBill-form-amountHTVA]').val()).toFixed(2));
                                }else{
                                    $('input[name=widget-addBill-form-amountTVAC]').val($('input[name=widget-addBill-form-amountHTVA]').val());
                                }
                              });
						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="addClient" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<h4 class="fr text-green">Ajouter un client</h4>

						<form id="widget-addClient-form" action="include/add_client.php" role="form" method="post">

                            <div class="form-group">

                                    <h4 class="fr text-green">Description</h4>

                            	<div class="col-md-4">
                                    <label for="internalReference"  class="fr">Référence interne</label>
                                    <label for="internalReference"  class="en">Référence interne</label>
                                    <label for="internalReference"  class="nl">Référence interne</label>
                                    <input type="text" class="form-control" name="internalReference">
                                </div>

                            	<div class="col-md-4">
                                    <label for="description"  class="fr">Nom de la société</label>
                                    <label for="description"  class="en">Company name</label>
                                    <label for="description"  class="nl">Company name</label>
                                    <input type="text" class="form-control" name="description" class="form-control required">
                                </div>
                            	<div class="col-md-4">
                                    <label for="VAT"  class="fr">Numéro de TVA</label>
                                    <label for="VAT"  class="en">VAT Number</label>
                                    <label for="VAT"  class="nl">VAT Number</label>
                                    <input type="text" class="form-control required" name="VAT" class="form-control required">
                                </div>
                            	<div class="col-md-4">
                                    <label for="type"  class="fr">Type</label>
                                    <label for="type"  class="en">Type</label>
                                    <label for="type"  class="nl">Type</label>
                                    <select title="Type" class="form-control selectpicker" name="type">
                                      <option value="CLIENT">Client</option>
                                      <option value="PROSPECT" selected>Prospect</option>
                                      <option value="ANCIEN PROSPECT" selected>Ancien prospect</option>
                                      <option value="ANCIEN CLIENT">Ancien client</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="picture"  class="fr">Logo de la société (.jpg)</label>
                                    <label for="picture"  class="en">Company image (jpg)</label>
                                    <label for="picture"  class="nl">Company image(jpg)</label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                                    <input type=file size=40 class="form-control" name="picture">
                                </div>


                                <div class="separator"></div>
                                    <h4 class="fr text-green">Adresse</h4>


								<div class="col-sm-4">
                                <label for="street"  class="fr">Rue:</label>
                                <label for="street"  class="en">Street: </label>
                                <label for="street"  class="nl">Street</label>
                                <input type="text" class="form-control" name="street" class="form-control required">
								</div>

								<div class="col-sm-4">
                                    <label for="zipCode"  class="fr">Code Postal</label>
                                    <label for="zipCode"  class="en">ZIP Code </label>
                                    <label for="zipCode"  class="nl">ZIP Code</label>
                                    <input type="text" class="form-control" name="zipCode" class="form-control required">
								</div>

								<div class="col-sm-4">
                                    <label for="city"  class="fr">Ville</label>
                                    <label for="city"  class="en">City</label>
                                    <label for="city"  class="nl">City</label>
                                    <input type="text" class="form-control" name="city" class="form-control required">
								</div>

                                <div class="separator"></div>
                                <h4 class="fr text-green">Personne de Contact</h4>

								<div class="col-md-3">
                                    <label for="contactMail"  class="fr">E-Mail</label>
                                    <label for="contactMail"  class="en">EMAIL</label>
                                    <label for="contactMail"  class="nl">EMAIL</label>
                                    <input type="text" class="form-control" name="contactMail" class="form-control required">
								</div>

								<div class="col-md-3">
                                    <label for="contactFirstMail"  class="fr">Prénom</label>
                                    <label for="contactFirstMail"  class="en">First Name</label>
                                    <label for="contactFirstMail"  class="nl">First Name</label>
                                    <input type="text" class="form-control" name="contactFirstName" class="form-control required">
								</div>
								<div class="col-md-3">
                                    <label for="contactLastName"  class="fr">Nom de Famille</label>
                                    <label for="contactLastName"  class="en">Last Name</label>
                                    <label for="contactLastName"  class="nl">Last Name</label>
                                    <input type="text" class="form-control" name="contactLastName" class="form-control required">
								</div>
                                <div class="col-md-3">
                                    <label for="phone"  class="fr">Téléphone</label>
                                    <label for="phone"  class="en">Phone</label>
                                    <label for="phone"  class="nl">Phone</label>
                                    <input type="text" class="form-control" name="phone" class="form-control">
								</div>
                                <h4 class="fr text-green addClientTechnicalUser hidden">Données techniques pour le premier utilisateur</h4>
                                <div class="separator"></div>
								<div class="col-md-3">
                                    <label for="$firstNameInitialisation"  class="hidden addClientTechnicalUser fr">Prénom</label>
                                    <label for="firstNameInitialisation"  class="en hidden addClientTechnicalUser">First name</label>
                                    <label for="firstNameInitialisation"  class="nl hidden addClientTechnicalUser">First name</label>
                                    <input type="text" class="form-control addClientTechnicalUser hidden" name="firstNameInitialisation" class="form-control required">
								</div>

								<div class="col-md-3">
                                    <label for="nameInitialisation"  class="fr addClientTechnicalUser hidden">Nom</label>
                                    <label for="nameInitialisation"  class="en addClientTechnicalUser hidden">Name</label>
                                    <label for="nameInitialisation"  class="nl addClientTechnicalUser hidden">Name</label>
                                    <input type="text" class="form-control  addClientTechnicalUser hidden" name="nameInitialisation" class="form-control required">
								</div>

								<div class="col-md-3">
                                    <label for="mailInitialisation"  class="fr addClientTechnicalUser hidden">Mail</label>
                                    <label for="mailInitialisation"  class="en addClientTechnicalUser hidden">Mail</label>
                                    <label for="mailInitialisation"  class="nl addClientTechnicalUser hidden">Mail</label>
                                    <input type="text" class="form-control addClientTechnicalUser hidden" name="mailInitialisation" class="form-control required">
								</div>

								<div class="col-md-3">
                                    <label for="passwordInitialisation"  class="fr addClientTechnicalUser hidden">Mot de passe</label>
                                    <label for="passwordInitialisation"  class="en addClientTechnicalUser hidden">Mot de passe</label>
                                    <label for="passwordInitialisation"  class="nl addClientTechnicalUser hidden">Mot de passe</label>
                                    <input type="password" autocomplete="off" class="form-control addClientTechnicalUser hidden" name="passwordInitialisation" class="form-control required">
								</div>

                                <input type="text" class="form-control hidden" name="email" class="form-control required" value="<?php echo $user; ?>" hidden>


                            </div>
                            <div class="separator"></div>
							<button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-plus"></i>Ajouter</button>
							<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-plus"></i>Add</button>
							<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-plus"></i>Add</button>
						</form>
						<script type="text/javascript">

                            $('#widget-addClient-form select[name=type]').change(function(){
                                if($('#widget-addClient-form select[name=type]').val()=="CLIENT"){
                                    $('.addClientTechnicalUser').removeClass("hidden");
                                }else{
                                    $('.addClientTechnicalUser').addClass("hidden");
                                }
                            });

							jQuery("#widget-addClient-form").validate({
								submitHandler: function(form) {
									jQuery(form).ajaxSubmit({
										success: function(response) {

											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                get_company_listing('*');
                                                document.getElementById('widget-addClient-form').reset();
												$('#addClient').modal('toggle');
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
						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="ReservationsListing" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>

            <h4 class="fr text-green">Vue sur les réservations</h4>

            <div class="dropdown">
              <div class="col-md-3">
              	<ul class="nav">
                    <li class="dropdown" role="presentation">
                        <a aria-expanded="false" href="#" data-toggle="dropdown" class="dropdown-toggle"> <span class="bikeSelectionText">Sélection de vélo</span><span class="caret"></span> </a>
                        <ul role="menu" class="dropdown-menu bikeSelection">
                        </ul>
                    </li>
                 </ul>
               </div>
            </div>
            <div class="separator"></div>

            <div class="col-md-5">
			<div class="form-group">
                <label for="dtp_input2" class="control-label">Date de début</label>
                <div class="input-group date form_date_start col-md-12" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
            </div>
            </div>

			<div class="col-md-5">
			<div class="form-group">
                <label for="dtp_input2" class="control-label">Date de fin</label>
                <div class="input-group date form_date_end col-md-12" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
            </div>
            </div>

            <script type="text/javascript">

            	$('.form_date_start').datetimepicker({
                language:  'fr',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
                });

            	$('.form_date_end').datetimepicker({
                language:  'fr',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
                });


                $('.form_date_start').change(function(){
                    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));
                });
                $('.form_date_end').change(function(){

                    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));
                });


            </script>


            <div data-example-id="contextual-table" class="bs-example">
                        <span id="ReservationsList"></span>
            </div>

			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="bikeDetailsFull" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr-inline text-green">Référence du vélo :</h4>
						<h4 class="en-inline text-green">Bike Reference:</h4>
						<h4 class="nl-inline text-green">Bike Reference :</h4>
                        <p span class="bikeReference"></p>
                    </div>

                    <div class="col-sm-5">
                        <h4><span class="fr"> Modèle : </span></h4>
                        <h4><span class="en"> Model: </span></h4>
                        <h4><span class="nl"> Model : </span></h4>
                        <p span class="bikeModel"></p>

                    </div>
                    <div class="col-sm-5">
                        <h4><span class="fr"> Référence du cadre : </span></h4>
                        <h4><span class="en"> Frame reference: </span></h4>
                        <h4><span class="nl"> Frame reference: </span></h4>
                        <p span class="frameReference"></p>

                    </div>

                    <div class="col-sm-10">
                    <h4 class="text-green">Informations relatives au contrat</h4>
                    </div>

                    <div class="col-sm-4">
                    <h4><span class="fr"> Type de contrat : </span></h4>
                    <h4><span class="en"> Contract type: </span></h4>
                    <h4><span class="nl"> Contract type : </span></h4>


                    <p><span class="contractType"></span></p>
                    </div>

                   <div class="col-sm-4">
                    <h4><span class="fr" >Date de début :</span></h4>
                    <h4><span class="en" >Start date:</span></h4>
                    <h4><span class="nl" >Start date :</span></h4>

                    <p><span class="startDateContract"></span></p>
                    </div>

                    <div class="col-sm-4">
                        <h4><span class="fr" >Date de fin :</span></h4>
                        <h4><span class="en" >End date:</span></h4>
                        <h4><span class="nl" >End date :</span></h4>
                    <p><span class="endDateContract"></span></p>
                    </div>

                   <div class="col-sm-10">
                    <h4>Votre vélo: </h4>
                        <div class="col-md-4">
                        <img src="" class="bikeImage" alt="image" />
                        </div>
                    </div>
                    <div class="separator"></div>
                    <h4 class="fr text-green">Historique du vélo</h4>
                    <span id="action_bike_log_user">
                    </span>

				</div>
            </div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>

		</div>
	</div>
</div>

<div class="modal fade" id="companyDetails" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="companyIdHidden" name="companyId" value="" />
        <div class="row">
          <form id="widget-companyDetails-form" action="include/update_client.php" role="form" method="post">
            <div class="col-sm-12 form-group">

              <div class="col-sm-12">
                <h4 class="text-green">Informations générales</h4>
                <a href="#" class="text-red updateClientInformationButton">Update</a>
                <a href="#" class="text-red cancelUpdateClientInformation hidden">Cancel update</a>
              </div>
              <div class="col-sm-12">
                <label class="fr">Nom de la société :</label>
                <label class="en">Company Name:</label>
                <label class="nl">Company Name :</label>
                <input type="text" id="companyName" class="form-control updateClientInformation" name="widget_companyDetails_companyName" value="" readonly="true"/>
              </div>

              <div class="col-sm-4">
                <label class="fr"> Rue : </label>
                <label class="en"> Street: </label>
                <label class="nl"> Street : </label>
                <input type="text" id="companyStreet" class="form-control updateClientInformation" name="widget_companyDetails_companyStreet" value="" readonly="true"/>
              </div>
              <div class="col-sm-4">
                <label class="fr"> Code postal : </label>
                <label class="en"> Zip Code: </label>
                <label class="nl"> Zip Code: </label>
                <input type="text" id="companyZIPCode" class="form-control updateClientInformation" name="widget_companyDetails_companyZIPCode" value="" readonly="true"/>
              </div>
              <div class="col-sm-4">
                <label class="fr"> Ville: </label>
                <label class="en"> Town: </label>
                <label class="nl"> Town : </label>
                <input type="text" id="companyTown" class="form-control updateClientInformation" name="widget_companyDetails_companyTown" value="" readonly="true"/>
              </div>
              <div class="col-sm-5">
                <label class="fr"> Numéro TVA : </label>
                <label class="en"> VAT Number: </label>
                <label class="nl"> VAT Number: </label>
                <input type="text" id="companyVAT" class="form-control updateClientInformation" name="widget_companyDetails_companyVAT" value="" readonly="true"/>

              </div>
              <div class="col-sm-5">
                <label class="fr"> Type : </label>
                <label class="en"> Type: </label>
                <label class="nl"> Type : </label>
                <select title="Type" class="form-control selectpicker updateClientInformationSelect" disabled name="type">
                  <option value="CLIENT">Client</option>
                  <option value="PROSPECT">Prospect</option>
                  <option value="ANCIEN PROSPECT">Ancien Prospect</option>
                  <option value="ANCIEN CLIENT">Ancien Client</option>
                </select>
              </div>
              <div class="separator"></div>

              <div class="col-sm-12">
                <h4 class="text-green">Informations relatives au contact</h4>
              </div>
              <div class="col-sm-12 contactAddButtons">
                <button class="addContact button small green button-3d rounded icon-right glyphicon glyphicon-plus" type="button"></button>
                <label for="addContact">Ajouter un contact</label>
              </div>
              <div class="contactAddIteration" style="display:none;">
                <div class="col-md-3 form-group">
                  <label for="email_billing" class="fr"> Email : </label>
                  <input disabled type="text" class="form-control emailContact required" name="email" placeholder="email" />
                </div>
                <div class="col-md-3 form-group">
                  <label class="fr" >Nom :</label>
                  <input disabled type="text" class="form-control lastNameContact required" name="lastName" placeholder="nom" />
                </div>
                <div class="col-md-3 form-group">
                  <label class="fr" >Prénom :</label>
                  <input disabled type="text" class="form-control firstNameContact required" name="firstName" placeholder="prenom" />
                </div>
                <div class="col-md-3 form-group">
                  <label class="fr" >Téléphone :</label>
                  <input disabled type="text" class="form-control phoneContact" name="phone" placeholder="téléphone" />
                </div>
                <div class="col-md-3 form-group">
                  <label class="fr" >Fonction :</label>
                  <input disabled type="text" name="function" class="form-control functionContact required" placeholder="Fonction" />
                </div>
                <div class="col-md-3 form-group">
                  <label class="fr" >Envoyer le rapport de statistiques ?</label>
                  <input disabled type="checkbox" name="bikesStats" class="form-control bikeStatsContact" value="true" />
                </div>
                <div class="col-sm-12 form-group" style="margin-top:20px;">
                  <button class="button small green button-3d rounded icon-right addCompanyContact">
                  <span class="fr-inline" style="display: inline;">
                  <i class="fa fa-plus"></i> Ajouter le contact</span></button>
                </div>
                <div class="separator separator-small"></div>
              </div>
              <div class="clientContactZone">
                  <!--<div class="separator separator-small"></div>-->
              </div>
              <div class="separator"></div>

              <div class="col-sm-12">
                <h4 class="text-green">Informations relatives à la facturation</h4>
              </div>

              <div class="col-md-3">
                <label for="email_billing" class="fr"> Email : </label>
                <label for="email_billing" class="en"> Email: </label>
                <label for="email_billing" class="nl"> Email : </label>
                <input type="text" class="form-control" name="email_billing" value="" readonly="true"/>
              </div>

              <div class="col-md-3">
                <label for="lastNameContactBilling" class="fr" >Nom :</label>
                <label for="lastNameContactBilling" class="en" >Last Name:</label>
                <label for="lastNameContactBilling" class="nl" >Last Name:</label>
                <input type="text" class="form-control" name="lastNameContactBilling" value="" readonly="true"/>
              </div>

              <div class="col-md-3">
                <label for="firstNameContactBilling" class="fr" >Prénom :</label>
                <label for="firstNameContactBilling" class="en" >First Name:</label>
                <label for="firstNameContactBilling" class="nl" >First Name :</label>
                <input type="text" class="form-control" name="firstNameContactBilling" value="" readonly="true"/>
              </div>

              <div class="col-md-3">
                <label for="phoneBilling" class="fr" >Téléphone :</label>
                <label for="phoneBilling" class="en" >Phone:</label>
                <label for="phoneBilling" class="nl" >Phone :</label>
                <input type="text" class="form-control" name="phoneBilling" value="" readonly="true"/>
              </div>
              <div class="col-sm-4">
                <label for="billing">Envoyer les factures automatiquement ?</label>
                <input type="checkbox" name="billing" class="form-control" readonly="true"/>
              </div>

              <div class="separator"></div>

              <div class="col-sm-12">
                <h4 class="text-green">Options</h4>
              </div>

              <div class="col-sm-4">
                <label for="assistance">Assistance</label>
                <input type="checkbox" name="assistance" class="form-control" readonly="true"/>
              </div>

              <div class="col-sm-4">
                <label for="locking">Locking</label>
                <input type="checkbox" name="locking" class="form-control" readonly="true"/>
              </div>


              <input type="text" id="widget_companyDetails_requestor" name="widget_companyDetails_requestor" class="form-control hidden" value="<?php echo $user; ?>">
              <input type="text" name="ID" class="form-control hidden">
              <input type="text" id="widget_companyDetails_internalReference" name="widget_companyDetails_internalReference" class="form-control hidden">

              <div class="col-sm-12">
                <button  class="button small green button-3d rounded icon-left hidden" id="sendButtonClientDetails" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
              </div>
            </div>


          </form>

          <script type="text/javascript">
          jQuery("#widget-companyDetails-form").validate({
            submitHandler: function(form){
              jQuery(form).ajaxSubmit({
                success: function(response) {
                  if (response.response == 'success') {
                    $.notify({
                      message: response.message
                    }, {
                      type: 'success'
                    });

                    document.getElementsByClassName("cancelUpdateClientInformation")[0].classList.add("hidden");
                    document.getElementsByClassName("updateClientInformationButton")[0].classList.remove("hidden");
                    document.getElementById("sendButtonClientDetails").classList.add("hidden");
                    document.getElementById("clientBikes").classList.remove("hidden");
                    document.getElementById("clientBuildings").classList.remove("hidden");
                    document.getElementById("clientContracts").classList.remove("hidden");

                    var classname = document.getElementsByClassName('updateClientInformation');
                    $('#widget-companyDetails-form input').attr("readonly", true);
                    $('#widget-companyDetails-form select').prop( "disabled", true );



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


          document.getElementsByClassName('updateClientInformationButton')[0].addEventListener('click', function(){
            document.getElementById("sendButtonClientDetails").classList.remove("hidden");
            document.getElementById("clientBikes").classList.add("hidden");
            document.getElementById("clientBuildings").classList.add("hidden");
            document.getElementById("clientContracts").classList.add("hidden");
            document.getElementsByClassName("cancelUpdateClientInformation")[0].classList.remove("hidden");
            document.getElementsByClassName("updateClientInformationButton")[0].classList.add("hidden");
            $('#widget-companyDetails-form input').attr("readonly", false);
            $('#widget-companyDetails-form select').removeAttr("disabled");


          });
          document.getElementsByClassName('cancelUpdateClientInformation')[0].addEventListener('click', function(){
            document.getElementsByClassName("cancelUpdateClientInformation")[0].classList.add("hidden");
            document.getElementsByClassName("updateClientInformationButton")[0].classList.remove("hidden");
            document.getElementById("sendButtonClientDetails").classList.add("hidden");
            document.getElementById("clientBikes").classList.remove("hidden");
            document.getElementById("clientContracts").classList.remove("hidden");
            document.getElementById("clientBuildings").classList.remove("hidden");
            $('#widget-companyDetails-form input').attr("readonly", true);
            $('#widget-companyDetails-form select').prop( "disabled", true );


          });

          </script>
          <script type="text/javascript" src="js/add_company_contact.js">

          </script>

          <div class="col-sm-12" id="clientBikes">
            <h4 class="text-green">Vélos :</h4>
            <p><span id="companyBikes"></span></p>
          </div>

          <div class="col-sm-12" id="clientBoxes">
            <h4 class="text-green">Bornes :</h4>
            <p><span id="companyBoxes"></span></p>
          </div>

          <div class="col-sm-12" id="clientContracts">
            <h4 class="text-green">Contrats et Offres :</h4>
            <p><span id="companyContracts"></span></p>
          </div>

          <div class="col-sm-12">
            <h4 class="text-green">Historique et actions :</h4>
            <span id="action_company_log"></span>

          </div>

          <div class="col-sm-12" id="clientBuildings">
            <h4 class="text-green">Bétiments:</h4>
            <p><span id="companyBuildings"></span></p>
          </div>

          <div class="col-sm-12" id="clientusers">
            <h4 class="text-green">Utilisateurs:</h4>
            <span id="companyUsers"></span>
          </div>

        </div>

      </div>
      <div class="fr" class="modal-footer">
        <button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
      </div>
      <div class="en" class="modal-footer">
        <button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
      </div>
      <div class="nl" class="modal-footer">
        <button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="addUser" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr text-green">Ajouter un utilisateur</h4>

						<form id="widget-addUser-form" action="include/add_user.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                            	<div class="col-md-4">
                                <label for="firstname"  class="fr">Prénom</label>
                                <label for="firstname"  class="en">Firstname</label>
                                <label for="firstname"  class="nl">Voornaam</label>
                                <input type="text" name="firstName" class="form-control required">
                                </div>

								<div class="col-md-4">
                                <label for="name"  class="fr">Nom</label>
                                <label for="name"  class="en">Name</label>
                                <label for="name"  class="nl">Achternaam</label>
                                <input type="text" name="name" class="form-control required">
								</div>

								<div class="col-md-4">
                                    <label for="mail"  class="fr">E-mail</label>
                                    <label for="mail"  class="en">E-mail</label>
                                    <label for="mail"  class="nl">E-mail</label>
                                    <input type="text" name="mail" class="form-control mail required">
                                </div>
								<div class="col-md-4">
                                    <label for="generatePassword"  class="fr">Genérer un password automatiquement</label>
                                    <input type="checkbox" name="generatePassword" class="form-control" checked>
                                </div>
                                <div class="col-md-8">
                                    <label for="password"  class="fr hidden">Password</label>
                                    <label for="password"  class="en hidden">Password</label>
                                    <label for="password"  class="nl hidden">Password</label>
                                    <input type="text" name="password" class="form-control required hidden">
                                </div>
								<div class="col-md-4">
                                    <label for="fleetManager">Fleet manager</label>
                                    <input type="checkbox" name="fleetManager" class="form-control">
                                </div>
                                <input type="text" name="requestor" class="form-control hidden" value="<?php echo $user; ?>">
                                <input type="text" name="company" class="form-control hidden">

                            </div>
                            <h4>Accès aux bâtiments</h4>
                            <div class="form-group col-sm-12" id="buildingCreateUser"></div>

                            <h4>Accès aux vélos</h4>
                            <div class="form-group col-sm-12" id="bikeCreateUser"></div>

                            <div id="confirmAddUser">

                            </div>

						</form>
						<script type="text/javascript">
							jQuery("#widget-addUser-form").validate({
								submitHandler: function(form) {

									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                get_users_listing();
												$('#addUser').modal('toggle');

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

						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    $('#widget-addUser-form input[name=generatePassword]').change(function(){
        if($('#widget-addUser-form input[name=generatePassword').is(':checked')){
            $('#widget-addUser-form label[for=password]').addClass("hidden");
            $('#widget-addUser-form input[name=password]').addClass("hidden");
        }else{
            $('#widget-addUser-form label[for=password]').removeClass("hidden");
            $('#widget-addUser-form input[name=password]').removeClass("hidden");
        }
    })
</script>

<div class="modal fade" id="taskManagement" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr text-green taskManagementTitle">Ajouter une action</h4>

						<form id="widget-taskManagement-form" action="include/action_company.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                                <div class="col-md-12">

                                    <div class="col-md-4">
                                        <label for="owner">Owner</label>
                                        <select title="owner" class="form-control required" name="owner">
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status">Statut :</label>
                                        <select title="Status" class="selectpicker form-control required" name="status">
                                          <option value="TO DO">To do</option>
                                          <option value="DONE">Done</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="company"  class="fr">Société</label>
                                        <label for="company"  class="en">Company</label>
                                        <label for="company"  class="nl">Company</label>
                                        <select title="company" class="selectpicker form-control required" name="company">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label for="type"  class="fr">Type</label>
                                        <label for="type"  class="en">Type</label>
                                        <label for="type"  class="nl">Type</label>
                                        <select title="type" class="selectpicker form-control required" name="type">
                                          <option value="contact">Prise de contact</option>
                                          <option value="rappel">Rappel</option>
                                          <option value="plan rdv">Planification de rendez-vous</option>
                                          <option value="rdv">Rendez-vous</option>
                                          <option value="offre">Formulation d'une offre</option>
                                          <option value="offreSigned">Offre signée</option>
                                          <option value="delivery">Livraison vélo</option>
                                          <option value="other">Autre</option>
                                        </select>

                                    </div>
                                    <div class="col-md-4">
                                        <label for="date"  class="fr">Date</label>
                                        <label for="date"  class="en">Date</label>
                                        <label for="date"  class="nl">Date</label>
                                        <input type="date" name="date" class="form-control required">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="reminder"  class="fr">Rappel ?</label>
                                        <label for="reminder"  class="en">Reminder ?</label>
                                        <label for="reminder"  class="nl">Reminder ?</label>
                                        <input type="date" name="date_reminder" class="form-control ">
                                    </div>
                                </div>


                                <div class="col-md-12">

                                    <div class="col-md-12">
                                        <label for="reminder"  class="fr">Titre</label>
                                        <label for="reminder"  class="en">Title</label>
                                        <label for="reminder"  class="nl">Title</label>
                                        <input type="text" name="title" class="form-control ">
                                    </div>

                                    <div class="col-md-12">
                                        <label for="reminder"  class="fr">Description</label>
                                        <label for="reminder"  class="en">Description</label>
                                        <label for="reminder"  class="nl">Description</label>
                                        <textarea class="form-control" rows="5" name="description"></textarea>
                                    </div>

                                </div>

                                <input type="text" name="requestor" class="form-control hidden" value="<?php echo $user; ?>">
                                <input type="text" name="action" class="form-control hidden" value="create">
	                            <div class="col-sm-12">
	                                <button  class="button small green button-3d rounded icon-left taskManagementSendButton" type="submit"><i class="fa fa-paper-plane"></i>Créer</button>
	                            </div>

                            </div>

						</form>
						<script type="text/javascript">
							jQuery("#widget-taskManagement-form").validate({
								submitHandler: function(form) {
									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                list_tasks('*', $('.taskOwnerSelection').val(), $('.tasksListing_number').val());
												$('#taskManagement').modal('toggle');
                                                document.getElementById('widget-taskManagement-form').reset();


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

						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="feedbackManagement" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr text-green feedbackManagementTitle">Ajouter un feedback</h4>

						<form id="widget-feedbackManagement-form" action="include/feedback_management.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                                <div class="col-md-12">

                                    <div class="col-md-4">
                                        <label for="utilisateur">ID</label>
                                        <input type='int' title="ID" class="form-control required" name="ID" readonly='readonly'>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="utilisateur">Utilisateur</label>
                                        <input type="text" title="utilisateur" class="form-control required" name="utilisateur" readonly='readonly'>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label for="bike"  class="fr">Vélo</label>
                                        <label for="bike"  class="en">Bike</label>
                                        <label for="bike"  class="nl">Bike</label>
                                        <input type="text" name="bike" class="form-control" readonly='readonly'>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="StartDate"  class="fr">Date de début</label>
                                        <label for="StartDate"  class="en">Start date</label>
                                        <label for="StartDate"  class="nl">Start date</label>
                                        <input type="text" name="startDate" class="form-control" readonly='readonly'>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="endDate"  class="fr">Date de Fin</label>
                                        <label for="endDate"  class="en">End date</label>
                                        <label for="endDate"  class="nl">End date</label>
                                        <input type="text" name="endDate" class="form-control" readonly='readonly'>
                                    </div>
                                </div>


                                <div class="col-md-12">

                                    <img class="feedbackBikeImage" alt="image vélo" />
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label for="note"  class="fr">Note</label>
                                        <label for="note"  class="en">Note</label>
                                        <label for="note"  class="nl">Note</label>
                                        <select class="form-control" name="note">
                                            <option value="5">5/5</option>
                                            <option value="4">4/5</option>
                                            <option value="3">3/5</option>
                                            <option value="2">2/5</option>
                                            <option value="1">1/5</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 feedbackEntretien hidden">
                                        <label for="entretien"  class="fr">Besoin d'entretien ?</label>
                                        <label for="entretien"  class="en">Need of maintenance ?</label>
                                        <label for="entretien"  class="nl">Need of maintenance ?</label>
                                        <label><input type="checkbox" name="entretien" class="form-control">Oui</label>
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <label for="comment"  class="fr">Commentaire</label>
                                        <label for="comment"  class="en">Comment</label>
                                        <label for="comment"  class="nl">Comment</label>
                                        <textarea class="form-control" rows="5" name="comment"></textarea>
                                    </div>
                                </div>

                                <input type="text" name="action" class="form-control hidden" value="add">
                                <input type="text" name="user" class="form-control hidden" value="<?php echo $user; ?>">
	                            <div class="col-sm-12">
	                                <button  class="button small green button-3d rounded icon-left feedbackManagementSendButton" type="submit"><i class="fa fa-paper-plane"></i>Créer</button>
	                            </div>

                            </div>

						</form>
						<script type="text/javascript">
							jQuery("#widget-feedbackManagement-form").validate({
								submitHandler: function(form) {
									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
												$('#feedbackManagement').modal('toggle');
                                                document.getElementById('widget-feedbackManagement-form').reset();


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

						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $('#widget-feedbackManagement-form select[name=note]').change(function() {
        if($('#widget-feedbackManagement-form select[name=note]').val()=="5"){
            $('#widget-feedbackManagement-form input[name=entretien]').prop("checked", false);
            $('.feedbackEntretien').addClass("hidden");
        }
        else{
            $('.feedbackEntretien').removeClass("hidden");
        }
    });

</script>

<div class="modal fade" id="bikeManagement" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">

						<form id="widget-bikeManagement-form" action="include/bike_management.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                                <h4 class="fr text-green bikeManagementTitle">Ajouter un vélo</h4>
                                <div class="col-sm-12">
                                    <h4 class="fr text-green">Caractéristiques du vélo</h4>
                                    <div class="col-sm-4">
                                        <label for="portfolioID"  class="fr">Marque - Modèle</label>
                                        <label for="portfolioID"  class="en">Marque - Modèle</label>
                                        <label for="portfolioID"  class="nl">Marque - Modèle</label>
                                        <select name="portfolioID" class="form-control required"></select>

                                    </div>

                                    <div class="col-sm-4">
                                        <label for="size"  class="fr">Taille</label>
                                        <label for="size"  class="en">Size</label>
                                        <label for="size"  class="nl">Size</label>
                                        <input type="text" name="size" class="form-control required">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="company"  class="fr">Société</label>
                                        <label for="company"  class="en">Company</label>
                                        <label for="company"  class="nl">Company</label>
                                        <select name="company" class="form-control required"></select>
                                    </div>

                                </div>
                                <div class="col-sm-12">
                                    <div class="col-sm-4">
                                        <label for="model"  class="fr">Nom pour client</label>
                                        <label for="model"  class="en">Bike name for client</label>
                                        <label for="model"  class="nl">Bike name for client</label>
                                        <input type="text" name="model" class="form-control required">

                                    </div>
                                    <div class="col-sm-4">
                                        <label for="frameNumber"  class="fr">Numéro d'identification</label>
                                        <label for="frameNumber"  class="en">Identification number</label>
                                        <label for="frameNumber"  class="nl">Identification number</label>
                                        <input type="text" name="frameNumberOriginel" class="form-control required hidden">
                                        <input type="text" name="frameNumber" class="form-control required">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="frameReference"  class="fr">Référence de cadre</label>
                                        <label for="frameReference"  class="en">Frame reference</label>
                                        <label for="frameReference"  class="nl">Frame reference</label>
                                        <input type="text" name="frameReference" class="form-control required">
                                    </div>

                                </div>
                                <div class="col-sm-12">

                                    <div class="col-sm-4 bikeManagementPicture">
                                        <label for="picture"  class="fr">Image actuelle</label>
                                        <label for="picture"  class="en">Current Image</label>
                                        <label for="picture"  class="nl">Current Image</label>
                                        <img id='bikeManagementPicture' alt="image">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="picture"  class="fr">Photo du vélo (.jpg)</label>
                                        <label for="picture"  class="en">Bike picture (jpg)</label>
                                        <label for="picture"  class="nl">Bike picture(jpg)</label>
                                        <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                                        <input type=file size=40 name="picture" class="form-control">
                                    </div>

                                </div>




                                <div class="separator"></div>

                                <div class="col-sm-12">
                                    <h4 class="fr text-green">Informations sur l'achat du vélo</h4>
                                    <div class="col-sm-5">
                                        <label for="price"  class="fr">Prix d'achat</label>
                                        <label for="price"  class="en">Buying price</label>
                                        <label for="price"  class="nl">Buying price</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">€</span>
                                            <input type="float" name="price" class="form-control required">
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <label for="buyingDate"  class="fr">Date d'achat</label>
                                        <label for="buyingDate"  class="en">Buying date</label>
                                        <label for="buyingDate"  class="nl">Buying date</label>
                                        <input type="date" name="buyingDate" class="form-control required">
                                    </div>
                                </div>

                                <div class="separator"></div>
                                <div class="col-sm-12">
                                    <h4 class="fr text-green">Informations relatives au contrat</h4>

                                    <div class="col-sm-4">
                                        <label for="contractType"  class="fr">Type de contrat</label>
                                        <label for="contractType"  class="en">Contract type</label>
                                        <label for="contractType"  class="nl">Contract type</label>
                                        <select name="contractType" class="form-control required">
                                            <option value="leasing">Leasing</option>
                                            <option value="renting">Location</option>
                                            <option value="test">Vélo de test</option>
                                            <option value="stock">Vélo de stock</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="contractStart"  class="fr">Début de contrat</label>
                                        <label for="contractStart"  class="en">Contract start</label>
                                        <label for="contractStart"  class="nl">Contract start</label>
                                        <input type="date" name="contractStart" class="form-control">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="contractEnd"  class="fr">Fin de contrat</label>
                                        <label for="contractEnd"  class="en">Contract End</label>
                                        <label for="contractEnd"  class="nl">Contract End</label>
                                        <input type="date" name="contractEnd" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="col-sm-4">
                                        <label for="insurance"  class="fr">Assurance ?</label>
                                        <label for="insurance"  class="en">Insurance ?</label>
                                        <label for="insurance"  class="nl">Insurance ?</label>
                                        <label><input type="checkbox"name="insurance" class="form-control">Oui</label>
                                    </div>

                                </div>
                                <div class="separator"></div>

                                <div class="col-sm-12">
                                    <h4 class="fr text-green">Informations relatives à la facturation</h4>

                                    <div class="col-sm-4">
                                        <label for="billingType"  class="fr">Type de facturation</label>
                                        <label for="billingType"  class="en">Billing type</label>
                                        <label for="billingType"  class="nl">Billing type</label>
                                        <select name="billingType" class="form-control">
                                            <option value="monthly">Mensuelle</option>
                                            <option value="annuelle">Annuelle </option>
                                            <option value="paid">Déjà payé</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="billingPrice"  class="fr">Montant de facturation</label>
                                        <label for="billingPrice"  class="en">Montant de facturation</label>
                                        <label for="billingPrice"  class="nl">Montant de facturation</label>

                                        <div class="input-group">
                                            <span class="input-group-addon">€/mois</span>
                                            <input type="float" name="billingPrice" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="billingGroup"  class="fr">Groupe de facturation</label>
                                        <label for="billingGroup"  class="en">Groupe de facturation</label>
                                        <label for="billingGroup"  class="nl">Groupe de facturation</label>
                                        <input type="text" name="billingGroup" class="form-control required">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="col-sm-4">
                                        <label for="billing"  class="fr">Facturation automatique ?</label>
                                        <label for="billing"  class="en">Automatic billing ?</label>
                                        <label for="billing"  class="nl">Automatic billing ?</label>
                                        <label><input type="checkbox"name="billing" class="form-control">Oui</label>
                                    </div>


                                </div>
                                <div class="form-group col-sm-4" id="addBike_firstBuilding"></div>
                                <div class="form-group col-sm-12" id="addBike_buildingListing"></div>


                                <input type="text" name="user" class="form-control hidden" value="<?php echo $user; ?>">
                                <input type="text" name="action" class="form-control hidden">

                                <div class="col-sm-12"><h4>Accès aux bâtiments de ce vélo</h4></div>
                                <div class="form-group col-sm-12" id="bikeBuildingAccessAdmin"></div>

                                <div class="col-sm-12"><h4>Accès des utilisateurs à ce vélo</h4></div>
                                <div class="form-group col-sm-12" id="bikeUserAccessAdmin"></div>

                            </div>
                            <div class="col-sm-12">
                                <button  class="fr button small green button-3d rounded icon-left bikeManagementSend" type="submit"><i class="fa fa-plus"></i>Ajouter</button>
                            </div>


						</form>



                        <div class="separator bikeActions"></div>

                        <div class="col-sm-12">

                            <h4 class="fr text-green">Actions prises sur le vélo</h4>


                            <form id="widget-addActionBike-form" action="include/action_bike_management.php" role="form" method="post">
                                <input type="text" name="bikeNumber" class="form-control required hidden">
                                <input type="text" name="widget-addActionBike-form-user" class="form-control required hidden" value="<?php echo $user; ?>">
                                <input type="text" name="widget-addActionBike-form-action" class="form-control required hidden" value="add">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <label for="widget-addActionBike-form-date" class="hidden">Date</label>
                                        <input type="date" name="widget-addActionBike-form-date" class="form-control required hidden">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="widget-addActionBike-form-description" class="hidden">Description</label>
                                        <input type="text" name="widget-addActionBike-form-description" class="form-control required hidden">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="widget-addActionBike-form-public" class="hidden">Public ?</label>
                                        <input type="checkbox" name="widget-addActionBike-form-public" class="form-control hidden">
                                    </div>
                                    <div class="col-sm-1">
                                        <button  class="fr button small green button-3d rounded icon-left hidden addActionConfirmButton" type="submit"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </form>

                            <span id="action_bike_log"></span>

                        </div>
                        <div class="right">
                            <form  id="widget-deleteBike-form" action="include/bike_management.php" role="form" method="post">
                                <input type="text" name="user" value="<?php echo $user; ?>" class="hidden">
                                <input type="text" name="action" value="delete" class="hidden">
                                <input type="text" class="hidden" readonly="readonly" name="frameNumber">
                                <button  class="fr button small red button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Supprimer le vélo</button>
                                <button  class="nl button small red button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Delete bike</button>
                                <button  class="en button small red button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Delete bike</button>
                            </form>
                        </div>


                        <div class="fr" class="modal-footer">
                            <button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
                        </div>
                        <div class="en" class="modal-footer">
                            <button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
                        </div>
                        <div class="nl" class="modal-footer">
                            <button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
                        </div>



						<script type="text/javascript">
							jQuery("#widget-bikeManagement-form").validate({
								submitHandler: function(form) {

									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                get_company_details($('#widget-companyDetails-form input[name=ID]').val());
                                                document.getElementById('widget-bikeManagement-form').reset();
												$('#bikeManagement').modal('toggle');
                                                list_bikes_admin();


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

                            jQuery("#widget-addActionBike-form").validate({
                                submitHandler: function(form) {

                                    jQuery(form).ajaxSubmit({
                                        success: function(response) {
                                            if (response.response == 'success') {
                                                $.notify({
                                                    message: response.message
                                                }, {
                                                    type: 'success'
                                                });
                                                $("label[for='widget-addActionBike-form-date']").addClass("hidden");
                                                $('input[name=widget-addActionBike-form-date]').addClass("hidden");
                                                $("label[for='widget-addActionBike-form-description']").addClass("hidden");
                                                $('input[name=widget-addActionBike-form-description]').addClass("hidden");
                                                $("label[for='widget-addActionBike-form-public']").addClass("hidden");
                                                $('input[name=widget-addActionBike-form-public]').addClass("hidden");
                                                $('.addActionConfirmButton').addClass("hidden");
                                                construct_form_for_bike_status_updateAdmin($('#widget-addActionBike-form input[name=bikeNumber]').val());

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

                            jQuery("#widget-deleteBike-form").validate({
                                submitHandler: function(form) {

                                    jQuery(form).ajaxSubmit({
                                        success: function(response) {
                                            if (response.response == 'success') {
                                                $.notify({
                                                    message: response.message
                                                }, {
                                                    type: 'success'
                                                });
                                                document.getElementById('widget-bikeManagement-form').reset();
                                                list_bikes_admin();
                                                $('#bikeManagement').modal('toggle');
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


						</script>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="boxManagement" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">

						<form id="widget-boxManagement-form" action="include/box_management.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                                <h4 class="fr text-green" id="widget-boxManagement-form-title">Ajouter une borne</h4>


								<div class="col-sm-4">
                                    <label for="reference"  class="fr">Référence</label>
                                    <label for="reference"  class="en">Reference</label>
                                    <label for="reference"  class="nl">Reference</label>
                                    <input type="text"  name="reference" class="form-control">
                                </div>


								<div class="col-sm-4">
                                    <label for="boxModel"  class="fr">Modèle</label>
                                    <label for="boxModel"  class="en">Model</label>
                                    <label for="boxModel"  class="nl">Model</label>
                                    <select name="boxModel" class="form-control required">
                                        <option value="5keys" />Box 5 clés<br/>
                                        <option value="10keys" />Box 10 clés<br/>
                                        <option value="20keys" />Box 20 clés<br/>
                                    </select>
								</div>


                                <div class="separator"></div>
                                <h4 class="fr text-green">Informations relatives au contrat</h4>

                                <div class="col-sm-4">
                                    <label for="company"  class="fr">Client actuel</label>
                                    <label for="company"  class="en">Current customer</label>
                                    <label for="company"  class="nl">Current customer</label>
                                    <select name="company" class="form-control required">
                                    </select>
                                </div>


								<div class="col-sm-4">
                                    <label for="contractStart"  class="fr">Début de contrat</label>
                                    <label for="contractStart"  class="en">Contract start</label>
                                    <label for="contractStart"  class="nl">Contract start</label>
                                    <input type="date"  name="contractStart" class="form-control">
                                </div>
								<div class="col-sm-4">
                                    <label for="contractEnd"  class="fr">Fin de contrat</label>
                                    <label for="contractEnd"  class="en">Contract End</label>
                                    <label for="contractEnd"  class="nl">Contract End</label>
                                    <input type="date" name="contractEnd" class="form-control">
                                </div>
                                <div class="separator"></div>

                                <h4 class="fr text-green">Informations relatives à la facturation</h4>

								<div class="col-sm-4">
                                    <label for="billing"  class="fr">Facturation automatique ?</label>
                                    <label for="billing"  class="en">Automatic billing ?</label>
                                    <label for="billing"  class="nl">Automatic billing ?</label>
                                    <label><input type="checkbox" name="billing" class="form-control">Oui</label>
                                </div>

								<div class="col-sm-4">
                                    <label for="amount"  class="fr">Montant (par mois)</label>
                                    <label for="amount"  class="en">Amount per month</label>
                                    <label for="amount"  class="nl">Amount per month</label>
                                    <input type="number" min='0' name="amount" class="form-control">
                                </div>

								<div class="col-sm-4">
                                    <label for="billingGroup"  class="fr">Groupe de facturation</label>
                                    <label for="billingGroup"  class="en">Groupe de facturation</label>
                                    <label for="billingGroup"  class="nl">Groupe de facturation</label>
                                    <input type="number" min="1" max="10" name="billingGroup" class="form-control required" value="1">
                                </div>


                                <input type="text" name="id" class="form-control hidden">
                                <input type="text" name="user" class="form-control hidden" value="<?php echo $user; ?>">
                                <input type="text" name="action" class="form-control hidden">

                            </div>


                            <button  id="widget-boxManagement-form-send" class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-plus"></i>Ajouter</button>

						</form>
						<script type="text/javascript">
							jQuery("#widget-boxManagement-form").validate({
								submitHandler: function(form) {

									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                get_company_details($('#widget-companyDetails-form input[name=ID]').val());
                                                document.getElementById('widget-boxManagement-form').reset();
												$('#boxManagement').modal('toggle');
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




						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="addBuilding" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">

						<form id="widget-addBuilding-form" action="include/add_building.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                                <h4 class="fr text-green">Ajouter un bâtiment</h4>

								<div class="col-sm-4">
                                    <label for="widget-addBuilding-form-model"  class="fr">Référence du bâtiment</label>
                                    <label for="widget-addBuilding-form-model"  class="en">Building reference</label>
                                    <label for="widget-addBuilding-form-model"  class="nl">Building reference</label>
                                    <input type="text" id="widget-addBuilding-form-reference" name="widget-addBuilding-form-reference" class="form-control required">
								</div>

                            	<div class="col-sm-12">
                                    <label for="widget-addBuilding-form-descriptionFr"  class="fr">Description en français</label>
                                    <label for="widget-addBuilding-form-descriptionFr"  class="en">French description</label>
                                    <label for="widget-addBuilding-form-descriptionFr"  class="nl">French description</label>
                                    <input type="text" id="widget-addBuilding-form-descriptionFr" name="widget-addBuilding-form-descriptionFr" class="form-control required">
                                </div>

								<div class="col-sm-12">
                                    <label for="widget-addBuilding-form-descriptionEn"  class="fr">Description en anglais</label>
                                    <label for="widget-addBuilding-form-descriptionEn"  class="en">English description</label>
                                    <label for="widget-addBuilding-form-descriptionEn"  class="nl">English description</label>
                                    <input type="text" id="widget-addBuilding-form-descriptionEn" name="widget-addBuilding-form-descriptionEn" class="form-control required">
                                </div>

								<div class="col-sm-12">
                                    <label for="widget-addBuilding-form-descriptionNl"  class="fr">Description en néerlandais</label>
                                    <label for="widget-addBuilding-form-descriptionNl"  class="en">Dutch description</label>
                                    <label for="widget-addBuilding-form-descriptionNl"  class="nl">Dutch description</label>
                                    <input type="text" id="widget-addBuilding-form-descriptionNl" name="widget-addBuilding-form-descriptionNl" class="form-control required">
                                </div>
								<div class="col-sm-12">
                                    <label for="widget-addBuilding-form-adress"  class="fr">Adresse</label>
                                    <label for="widget-addBuilding-form-adress"  class="en">Adress</label>
                                    <label for="widget-addBuilding-form-adress"  class="nl">Adresse</label>
                                    <input type="text" id="widget-addBuilding-form-adress" name="widget-addBuilding-form-adress" class="form-control required">
                                </div>

                                <input type="text" id="widget-addBuilding-form-requestor" name="widget-addBuilding-form-requestor" class="form-control required hidden" value="<?php echo $user; ?>">
                                <input type="text" id="widget-addBuilding-form-company" name="widget-addBuilding-form-company" class="form-control required hidden">

                                <div class="separator"></div>

                                <div class="col-sm-12"><h4>Accès des vélos à ce bâtiment</h4></div>
                                <span id="addBuilding_bikeListing"></span>

                                <div class="col-sm-12"><h4>Accès des utilisateurs à ce bâtiment</h4></div>
                                <span id="addBuilding_usersListing"></span>

                                <button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-plus"></i>Ajouter</button>
                                <button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-plus"></i>Add</button>
                                <button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-plus"></i>Add</button>
                            </div>

						</form>
						<script type="text/javascript">
							jQuery("#widget-addBuilding-form").validate({
								submitHandler: function(form) {
									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                get_company_details($('#widget-companyDetails-form input[name=ID]').val());
                                                document.getElementById('widget-addBuilding-form').reset();
												$('#addBuilding').modal('toggle');


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

						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="offerManagement" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">

						<form id="widget-offerManagement-form" action="include/offer_management.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                                <h4 class="fr text-green offerManagementTitle">Ajouter une offre</h4>
                                <div class="col-sm-12">

                                    <div class="col-sm-12">
                                        <label for="title"  class="fr">Titre</label>
                                        <label for="title"  class="en">Title</label>
                                        <label for="title"  class="nl">Title</label>
                                        <input type="text" name="title" class="form-control required">
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="description"  class="fr">Description</label>
                                        <label for="description"  class="en">Description</label>
                                        <label for="description"  class="nl">Description</label>
                                        <textarea class="form-control required" rows="5" name="description"></textarea>
                                    </div>

                                </div>

                                <div class="col-sm-12">
									<div class="col-sm-3">
	                                    <label for="type"  class="fr">Type</label>
	                                    <label for="type"  class="en">Type</label>
	                                    <label for="type"  class="nl">Type</label>
	                                    <select name="type" class="form-control required">
	                                        <option value="leasing">Leasing</option>
	                                        <option value="achat">achat</option>
	                                    </select>
	                                </div>
									<div class="col-sm-3">
	                                    <label for="status"  class="fr">Status</label>
	                                    <label for="status"  class="en">Status</label>
	                                    <label for="status"  class="nl">Status</label>
	                                    <select name="status" class="form-control required">
	                                        <option value="ongoing">En cours</option>
	                                        <option value="done">Signé</option>
	                                        <option value="lost">Perdu</option>
	                                    </select>
	                                </div>

	                            	<div class="col-sm-3">
	                                    <label for="probability"  class="fr">Chance de réussite</label>
	                                    <label for="probability"  class="en">Chance de réussite</label>
	                                    <label for="probability"  class="nl">chance de réussite</label>
	                                    <input type="number" min="0" max="100" name="probability" class="form-control required">
	                                </div>

									<div class="col-sm-3">
	                                    <label for="amount"  class="fr">Montant</label>
	                                    <label for="amount"  class="en">Montant</label>
	                                    <label for="amount"  class="nl">Montant</label>
	                                    <input type="number" min="0" name="amount" class="form-control required">
	                                </div>

									<div class="col-sm-3">
	                                    <label for="margin"  class="fr">Marge</label>
	                                    <label for="margin"  class="en">Marge</label>
	                                    <label for="margin"  class="nl">Marge</label>
	                                    <input type="number" min="0" name="margin" class="form-control">
	                                </div>
	                            </div>

								<div class="col-sm-12">
									<div class="col-sm-4">
	                                    <label for="date"  class="fr">Date de signature</label>
	                                    <label for="date"  class="en">Date de signature</label>
	                                    <label for="date"  class="nl">Date de signature</label>
	                                    <input type="date" name="date" class="form-control">
	                                </div>
									<div class="col-sm-4">
	                                    <label for="start"  class="fr">Date de début</label>
	                                    <label for="start"  class="en">Date de début</label>
	                                    <label for="start"  class="nl">Date de début</label>
	                                    <input type="date" name="start" class="form-control">
	                                </div>
									<div class="col-sm-4">
	                                    <label for="end"  class="fr">Date de fin</label>
	                                    <label for="end"  class="en">Date de fin</label>
	                                    <label for="end"  class="nl">Date de fin</label>
	                                    <input type="date" name="end" class="form-control">
	                                </div>
								</div>

                                <div class="col-sm-12"></div>
                                <br>

                                <input type="text" name="requestor" class="form-control required hidden" value="<?php echo $user; ?>">
                                <input type="text" name="action" class="form-control required hidden" value="add">
                                <input type="text" name="ID" class="hidden">
                                <input type="text" name="company" class="form-control required hidden">

                                <div class="separator"></div>
                                <button  class="fr button small green button-3d rounded icon-left offerManagementSendButton" type="submit"><i class="fa fa-plus"></i>Ajouter</button>
                            </div>

						</form>
						<script type="text/javascript">
							jQuery("#widget-offerManagement-form").validate({
								submitHandler: function(form) {
									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                list_contracts_offers('*');
                                                document.getElementById('widget-offerManagement-form').reset();
												$('#offerManagement').modal('toggle');


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

                            $("#widget-offerManagement-form select[name=type]").change(function() {
                                if($("#widget-offerManagement-form select[name=type]").val()=="achat"){
                                    $("#widget-offerManagement-form input[name=start]").val("");
                                    $("#widget-offerManagement-form input[name=end]").val("");
                                    $("#widget-offerManagement-form input[name=start]").attr("readonly", true);
                                    $("#widget-offerManagement-form input[name=end]").attr("readonly", true);

                                }
                                if($("#widget-offerManagement-form select[name=type]").val()=="leasing"){
                                    $("#widget-offerManagement-form input[name=start]").attr("readonly", false);
                                    $("#widget-offerManagement-form input[name=end]").attr("readonly", false);

                                }
                            });




						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="template" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
      </div>
      <div class="modal-body">
        <form class="isLeasing" id="templateForm" action="include/offer_template.php" method="post" role="form" novalidate="novalidate">
          <input type="hidden" name="companyIdTemplate" id ="companyIdTemplate" value="" aria-required="true"/>
          <div class="row buyOrLeasing">
            <div class="col-sm-4">
              <h4 class="fr text-green">Général: </h4>
              <h4 class="en text-green">General: </h4>
              <h4 class="nl text-green">General: </h4>
            </div>
            <div class="col-sm-12">
              <div class="col-sm-3 form-group">
                <label for="leasingCheck" class="fr">Leasing</label>
                <label for="leasingCheck" class="en">Leasing</label>
                <label for="leasingCheck" class="nl">Leasing</label>
                <select name="buyOrLeasing" id="buyOrLeasingSelect" class="form-control required" aria-required="true">
                  <option value="leasing" selected>Leasing</option>
                  <option value="buy">Achat</option>
                  <option value="both"> Achat et leasing</option>
                </select>
                <!--<input type="checkbox" class="leasingCheck form-control" name="isLeasing" value="leasing" checked />-->
              </div>
              <div class="col-sm-4 form-group leasingSpecific">
                <label for="leasingDuration" class="fr">Durée leasing (mois)</label>
                <label for="leasingDuration" class="en">Leasing duration (months)</label>
                <label for="leasingDuration" class="nl">Durée leasing (mois)</label>
                <input type="number" name="leasingDuration" class="leasingDuration form-control required" aria-required="true" value="36" min="1">
              </div>
              <div class="col-sm-3 form-group leasingSpecific">
                <label for="numberMaintenance" class="fr">Entretiens par an</label>
                <label for="numberMaintenance" class="en">Maintenance per year</label>
                <label for="numberMaintenance" class="nl">Entretiens par an</label>
                <input type="number" name="numberMaintenance" class="numberMaintenance form-control required" aria-required="true" value="1" min="0">
              </div>
              <div class="col-sm-2 form-group leasingSpecific">
                <label for="assuranceCheck" class="fr">Assurance</label>
                <label for="assuranceCheck" class="en">Assurance</label>
                <label for="assuranceCheck" class="nl">Assurance</label>
                <input type="checkbox" class="assuranceCheck form-control" name="assurance" value="true" checked />
              </div>
            </div>
          </div>
          <div class="separator"></div>
          <div class="row templateBike">
            <div class="col-sm-4">
              <h4 class="fr text-green">Nombre de vélos: </h4>
              <h4 class="en text-green">Bike number: </h4>
              <h4 class="nl text-green">Nombre de vélos: </h4>
            </div>
            <div class="col-sm-12">
              <i class="fa fa-bicycle"></i> <span class="bikesNumber">0</span><input type="hidden" id="bikesNumber" name="bikesNumber" value="0" />
              <button class="button small green button-3d rounded icon-right glyphicon glyphicon-plus" type="button"></button>
              <button class="button small red button-3d rounded icon-right glyphicon glyphicon-minus" type="button"></button>
            </div>
            <table class="table table-condensed tableFixed bikeNumberTable hideAt0">
              <thead>
                <tr>
                  <th class="bLabel"></th>
                  <th class="bikeBrandModel">
                    <label for="bikeBrandModel" class="fr">MARQUE - MODÈLE</label>
                    <label for="bikeBrandModel" class="en">BRAND - MODEL</label>
                    <label for="bikeBrandModel" class="nl">MARQUE - MODÈLE</label>
                  </th>
                  <th class="bikepAchat">
                    <label for="pAchat" class="fr">PRIX ACHAT</label>
                    <label for="pAchat" class="en">BUTING PRICE</label>
                    <label for="pAchat" class="nl">PRIX ACHAT</label>
                  </th>
                  <th class="bikepVenteHTVA" style="display:none">
                    <label for="pVenteHTVA" class="fr">PRIX VENTE HTVA</label>
                    <label for="pVenteHTVA" class="en">SELLING PRICE EXEPT VAT</label>
                    <label for="pVenteHTVA" class="nl">PRIX VENTE HTVA</label>
                  </th>
                  <th class="bikeLeasing">
                    <label for="leasing" class="fr">LEASING</label>
                    <label for="leasing" class="en">LEASING</label>
                    <label for="leasing" class="nl">LEASING</label>
                  </th>
                  <th class="bikeMarge">
                    <label for="marge" class="fr">MARGE</label>
                    <label for="marge" class="en">PROFIT</label>
                    <label for="marge" class="nl">MARGE</label>
                  </th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="separator"></div>
          <div class="row templateBoxes">
            <div class="col-sm-4">
              <h4 class="fr text-green">Nombre de boxes: </h4>
              <h4 class="en text-green">Boxes number: </h4>
              <h4 class="nl text-green">Nombre de boxes: </h4>
            </div>
            <div class="col-sm-12">
              <i class="fa fa-archive"></i> <span class="boxesNumber">0</span><input type="hidden" id="boxesNumber" name="boxesNumber" value="0" />
              <button class="button small green button-3d rounded icon-right glyphicon glyphicon-plus" type="button"></button>
              <button class="button small red button-3d rounded icon-right glyphicon glyphicon-minus" type="button"></button>
            </div>
            <table class="table table-condensed tableFixed  boxesNumberTable hideAt0">
              <thead>
                <tr>
                  <th class="boxLabel">
                  </th>
                  <th class="boxModel">
                    <label for="boxModel" class="fr">BOX</label>
                    <label for="boxModel" class="en">BOX</label>
                    <label for="boxModel" class="nl">BOX</label>
                  </th>
                  <th class="boxProdPrice">
                    <label for="boxProdPrice" class="fr">PRIX PRODUCTION</label>
                    <label for="boxProdPrice" class="en">MANUFACTURING PRICE</label>
                    <label for="boxProdPrice" class="nl">PRIX PRODUCTION</label>
                  </th>
                  <th class="boxInstallationPrice">
                    <label for="boxInstallationPrice" class="fr">PLACEMENT HTVA</label>
                    <label for="boxInstallationPrice" class="en">POSE EXCLUDING VAT</label>
                    <label for="boxInstallationPrice" class="nl">PLACEMENT HTVA</label>
                  </th>
                  <th class="boxLocationPrice">
                    <label for="boxLocationPrice" class="fr">LOCATION MENSUELLE</label>
                    <label for="boxLocationPrice" class="en">MONTHLY RENTING</label>
                    <label for="boxLocationPrice" class="nl">LOCATION MENSUELLE</label>
                  </th>
                  <th class="boxMarge">
                    <label for="boxMarge" class="fr">MARGE</label>
                    <label for="boxMarge" class="en">MARGE</label>
                    <label for="boxMarge" class="nl">MARGE</label>
                  </th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="separator"></div>
          <div class="row templateAccessories">
            <div class="col-sm-4">
              <h4 class="fr text-green">Accessoires: </h4>
              <h4 class="en text-green">Accessories: </h4>
              <h4 class="nl text-green">Accessoires: </h4>
            </div>
            <div class="col-sm-12 accessoriesButtons">
              <i class="fa fa-calculator"></i> <span class="accessoriesNumber">0</span><input type="hidden" id="accessoriesNumber" name="accessoriesNumber" value="0" />
              <button class="button small green button-3d rounded icon-right glyphicon glyphicon-plus" type="button"></button>
              <button class="button small red button-3d rounded icon-right glyphicon glyphicon-minus" type="button"></button>
            </div>
            <table class="table table-condensed tableFixed otherCostsAccesoiresTable hideAt0">
              <thead>
                <th class="accessoriesLabel"></th>
                <th class="accessoriesCategory">
                  <label for="aCategory" class="fr">Catégorie</label>
                </th>
                <th class="accessoriesAccessory">
                  <label for="aAccessory" class="fr">Accessoire</label>
                </th>
                <th class="accessoriesBuyingPrice">
                  <label for="aBuyingPrice" class="fr">Prix achat</label>
                </th>
                <th class="accessoriesPriceHTVA">
                  <label for="aPriceHTVA" class="fr">Prix Vente HTVA</label>
                </th>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
          <div class="separator"></div>
          <div class="row templateOthers">
            <div class="col-sm-4">
              <h4 class="fr text-green">Autres: </h4>
              <h4 class="en text-green">Others: </h4>
              <h4 class="nl text-green">Others: </h4>
            </div>
            <div class="col-sm-12 othersButtons">
              <i class="fa fa-eur"></i> <span class="othersNumber">0</span><input type="hidden" id="othersNumber" name="othersNumber" value="0" />
              <button class="button small green button-3d rounded icon-right glyphicon glyphicon-plus" type="button"></button>
              <button class="button small red button-3d rounded icon-right glyphicon glyphicon-minus" type="button"></button>
            </div>
            <table class="table table-condensed tableFixed otherTable hideAt0">
              <thead>
                <th class="othersLabel"></th>
                <th class="othersDescription">
                  <label for="oDescription" class="fr">Description</label>
                </th>
                <th class="othersCost">
                  <label for="oCost" class="fr">Cout</label>
                </th>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
          <div class="separator"></div><div class="separator"></div>
          <div class="row templateTableauRecap">
            <div class="col-sm-4">
              <h4 class="fr text-green">Tableau récapitulatif: </h4>
              <h4 class="en text-green">Summary table: </h4>
              <h4 class="nl text-green">Summary table: </h4>
            </div>
            <div class="col-sm-12">
              <button type="button" id="generateTableRecap" class="fr button small green button-3d rounded icon-left">Générer / Actualiser</button>
            </div>
            <table class="table table-condensed tableFixed summaryTable" style="display:none">
              <thead>
                <th><label for="recapLabel fr">Item</label></th>
                <th><label for="recapPrice fr">Prix de vente</label></th>
                <th><label for="recapLeasing fr">Leasing/location (au mois)</label></th>
              </thead>
              <tbody></tbody>
              <tfoot></tfoot>
            </table>
            <div class="separator"></div><div class="separator"></div>
          </div>
          <div class="row form-group" style="margin-bottom:20px;">
            <h4 class="text-green">Contact société</h4>
            <div class="col-sm-12">Champs temporaires, a remplacer par un select</div>
            <div class="col-sm-2"><label for="">Email</label><input type="text" class="form-control required" required name="contactEmail"></div>
            <div class="col-sm-2"><label for="">Nom</label><input type="text" class="form-control required" required name="contactLastName"></div>
            <div class="col-sm-2"><label for="">Prénom</label><input type="text" class="form-control required" required name="contactFirstName"></div>
            <div class="col-sm-2"><label for="">Téléphone</label><input type="text" class="form-control required" required name="contactPhone"></div>
          </div><br/>
          <button type="submit" class="fr button small green button-3d rounded icon-left">Générer PDF</button>
        </form>
      </div>
      <script src="js/template-offre.js"></script>

      <div class="modal-footer">
        <div class="pull-left">
          <button data-dismiss="modal" class="btn btn-b fr" type="button">Fermer</button>
          <button data-dismiss="modal" class="btn btn-b en" type="button">Close</button>
          <button data-dismiss="modal" class="btn btn-b nl" type="button">Sluiten</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="costsManagement" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">

						<form id="widget-costsManagement-form" action="include/costs_management.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                                <h4 class="fr text-green costManagementTitle">Ajouter un frais</h4>

								<div class="col-sm-12">
                                    <label for="title"  class="fr">Titre</label>
                                    <label for="title"  class="en">Title</label>
                                    <label for="title"  class="nl">Title</label>
                                    <input type="text" name="title" class="form-control required">
								</div>
								<div class="col-sm-12">
                                    <label for="description"  class="fr">Description</label>
                                    <label for="description"  class="en">Description</label>
                                    <label for="description"  class="nl">Description</label>
                                    <textarea class="form-control required" rows="5" name="description"></textarea>
								</div>

                                <div class="col-sm-12">
									<div class="col-sm-3">
	                                    <label for="type"  class="fr">Type</label>
	                                    <label for="type"  class="en">Type</label>
	                                    <label for="type"  class="nl">Type</label>
	                                    <select name="type" class="form-control required">
	                                        <option value="monthly">Coût mensuel</option>
	                                        <option value="one-shot">Coût ponctuel</option>
	                                    </select>
	                                </div>

									<div class="col-sm-3">
	                                    <label for="amount"  class="fr">Montant</label>
	                                    <label for="amount"  class="en">Montant</label>
	                                    <label for="amount"  class="nl">Montant</label>
	                                    <input type="number" min="0" name="amount" class="form-control required">
	                                </div>

	                            </div>

								<div class="col-sm-12">
									<div class="col-sm-4">
	                                    <label for="start"  class="fr">Date de début</label>
	                                    <label for="start"  class="en">Date de début</label>
	                                    <label for="start"  class="nl">Date de début</label>
	                                    <input type="date" name="start" class="form-control required">
	                                </div>
									<div class="col-sm-4">
	                                    <label for="end"  class="fr">Date de fin</label>
	                                    <label for="end"  class="en">Date de fin</label>
	                                    <label for="end"  class="nl">Date de fin</label>
	                                    <input type="date" name="end" class="form-control">
	                                </div>
								</div>

                                <div class="col-sm-12"></div>
                                <br>

                                <input type="text" name="requestor" class="form-control required hidden" value="<?php echo $user; ?>">
                                <input type="text" name="action" class="form-control required hidden" value="add">
                                <input type="text" name="ID" class="hidden">

                                <div class="separator"></div>
                                <button  class="fr button small green button-3d rounded icon-left costManagementSendButton" type="submit"><i class="fa fa-plus"></i>Ajouter</button>
                            </div>

						</form>
						<script type="text/javascript">
							jQuery("#widget-costsManagement-form").validate({
								submitHandler: function(form) {
									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                list_contracts_offers('*');
                                                document.getElementById('widget-costsManagement-form').reset();
												$('#costsManagement').modal('toggle');

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

                            $("#widget-costsManagement-form select[name=type]").change(function() {
                                if($("#widget-costsManagement-form select[name=type]").val()=="one-shot"){
                                    $("#widget-costsManagement-form input[name=end]").val("");
                                    $("#widget-costsManagement-form input[name=end]").attr("readonly", true);

                                }
                                if($("#widget-costsManagement-form select[name=type]").val()=="monthly"){
                                    $("#widget-costsManagement-form input[name=end]").attr("readonly", false);
                                }
                            });




						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="reservationDetails" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr-inline text-green">Référence de transaction :</h4>
						<h4 class="en-inline text-green">Booking reference:</h4>
						<h4 class="nl-inline text-green">Booking reference:</h4>
                        <h4 span class="reservationNumber fr-inline"></h4>
						<br><br>
						<div class="col-sm-6">
                            <h4><span class="fr"> Date de début: </span></h4>
                            <h4><span class="en"> Start date: </span></h4>
                            <h4><span class="nl"> Start date: </span></h4>
                            <p span class="reservationStartDate"></p>
                        </div>

						<div class="col-sm-6">
                            <h4><span class="fr"> Date de fin : </span></h4>
                            <h4><span class="en"> End date: </span></h4>
                            <h4><span class="nl"> End date: </span></h4>
                            <p span class="reservationEndDate"></p>
                        </div>

						<div class="col-sm-6">
                            <h4><span class="fr"> Bâtiment de départ: </span></h4>
                            <h4><span class="en"> Start building: </span></h4>
                            <h4><span class="nl"> Start building: </span></h4>
                            <p span class="reservationStartBuilding"></p>
                        </div>
						<div class="col-sm-6">
                            <h4><span class="fr"> Bâtiment d'arrivée: </span></h4>
                            <h4><span class="en"> End building: </span></h4>
                            <h4><span class="nl"> End building: </span></h4>
                            <p span class="reservationEndBuilding"></p>
                        </div>
                    </div>
                    <div class="col-sm-12">
						<div class="col-sm-6">
                            <h4><span class="fr"> Vélo: </span></h4>
                            <h4><span class="en"> Bike: </span></h4>
                            <h4><span class="nl"> Bike: </span></h4>
                            <p span class="reservationBikeNumber"></p>
                        </div>
						<div class="col-sm-6">
                            <h4><span class="fr"> Utilisateur: </span></h4>
                            <h4><span class="en"> User: </span></h4>
                            <h4><span class="nl"> User: </span></h4>
                            <p span class="reservationEmail"></p>
                        </div>

                        <div class="col-sm-4">
                            <img src="" class="reservationBikeImage" alt="image" />
                        </div>
					</div>
                    <div id="updateReservationdiv"></div>
                    <div id="deleteReservationdiv"></div>

				</div>
			</div>
            <div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>

		</div>
	</div>
</div>


<div class="modal fade" id="deleteReservation" tabindex="1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr text-green">Supprimer une réservation</h4>

						<form id="widget-deleteReservation-form" action="include/delete-reservation.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                            	<div class="col-sm-6">
                                <label for="widget-deleteReservation-form-start"  class="fr">Début :</label>
                                <label for="widget-deleteReservation-form-start"  class="en">Start: </label>
                                <label for="widget-deleteReservation-form-start"  class="nl">Start: </label>
                                <input type="text" id="widget-deleteReservation-form-start" readonly="readonly" name="widget-deleteReservation-form-start" class="form-control required">
                                </div>

								<div class="col-sm-6">
                                <label for="widget-deleteReservation-form-end"  class="fr">Fin :</label>
                                <label for="widget-deleteReservation-form-end"  class="en">End:</label>
                                <label for="widget-deleteReservation-form-end"  class="nl">End:</label>
                                <input type="text" id="widget-deleteReservation-form-end" readonly="readonly" name="widget-deleteReservation-form-end" class="form-control required">
                                </div>

								<div class="col-sm-6">
                                <label for="widget-deleteReservation-form-user"  class="fr">Utilisateur :</label>
                                <label for="widget-deleteReservation-form-user"  class="en">User:</label>
                                <label for="widget-deleteReservation-form-user"  class="nl">User:</label>
                                <input type="text" id="widget-deleteReservation-form-user" readonly="readonly" name="widget-deleteReservation-form-user" class="form-control">
                                <input type="text" id="widget-deleteReservation-form-requestor" name="widget-deleteReservation-form-requestor" class="form-control hidden" value="<?php echo $user; ?>">
                                <input type="text" id="widget-deleteReservation-form-ID" name="widget-deleteReservation-form-ID" class="form-control hidden">
                                </div>
                            </div>
                            <h4>Confirmation de suppression</h4>

                            <label for="widget-deleteReservation-form-confirmation" class="fr">Veuillez écrire "DELETE" afin de confirmer la suppression</label>
                            <input type="text" id="widget-deleteReservation-form-confirmation" name="widget-deleteReservation-form-confirmation" class="form-control">

							<button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
							<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
							<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>

						</form>
						<script type="text/javascript">
							jQuery("#widget-deleteReservation-form").validate({
								submitHandler: function(form) {

									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));
												$('#deleteReservation').modal('toggle');

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

						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="updateReservation" tabindex="1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr text-green">Supprimer une réservation</h4>

						<form id="widget-updateReservation-form" action="include/update-reservation.php" role="form" method="post">

                            <div class="form-group col-sm-12">
                            	<div class="col-sm-6">
                                <label for="widget-updateReservation-form-start"  class="fr">Début :</label>
                                <label for="widget-updateReservation-form-start"  class="en">Start: </label>
                                <label for="widget-updateReservation-form-start"  class="nl">Start: </label>
                                <input type="text" id="widget-updateReservation-form-start" name="widget-updateReservation-form-start" class="form-control required">
                                </div>

								<div class="col-sm-6">
                                <label for="widget-updateReservation-form-end"  class="fr">Fin :</label>
                                <label for="widget-updateReservation-form-end"  class="en">End:</label>
                                <label for="widget-updateReservation-form-end"  class="nl">End:</label>
                                <input type="text" id="widget-updateReservation-form-end" name="widget-updateReservation-form-end" class="form-control required">
                                </div>

								<div class="col-sm-6">
                                <label for="widget-updateReservation-form-user"  class="fr">Utilisateur :</label>
                                <label for="widget-updateReservation-form-user"  class="en">User:</label>
                                <label for="widget-updateReservation-form-user"  class="nl">User:</label>
                                <input type="text" id="widget-updateReservation-form-user" readonly="readonly" name="widget-updateReservation-form-user" class="form-control">
                                <input type="text" id="widget-updateReservation-form-requestor" name="widget-updateReservation-form-requestor" class="form-control hidden" value="<?php echo $user; ?>">
                                <input type="text" id="widget-updateReservation-form-ID" name="widget-updateReservation-form-ID" class="form-control hidden">
                                </div>
                            </div>
                            <h4>Confirmation de suppression</h4>

                            <label for="widget-updateReservation-form-confirmation" class="fr">Veuillez écrire "update" afin de confirmer la suppression</label>
                            <input type="text" id="widget-updateReservation-form-confirmation" name="widget-updateReservation-form-confirmation" class="form-control">

							<button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
							<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
							<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>

						</form>
						<script type="text/javascript">
							jQuery("#widget-updateReservation-form").validate({
								submitHandler: function(form) {

									jQuery(form).ajaxSubmit({
										success: function(response) {
											if (response.response == 'success') {
												$.notify({
													message: response.message
												}, {
													type: 'success'
												});
                                                get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));
												$('#updateReservation').modal('toggle');

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

						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="updateBikeStatus" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
                        <form id="widget-updateBikeStatus-form" action="include/updateBikeStatus.php" role="form" method="post">

                        <div class="col-sm-12">

                            <h4 class="fr-inline text-green">Référence du vélo :</h4>
                            <h4 class="en-inline text-green">Bike Reference:</h4>
                            <h4 class="nl-inline text-green">Bike Reference :</h4>
                            <span class="bikeReference"></span>

                            <div class="col-sm-12"></div>

                            <div class="col-sm-5">
                                <h4><span class="fr"> Modèle : </span></h4>
                                <h4><span class="en"> Model: </span></h4>
                                <h4><span class="nl"> Model : </span></h4>
                                <input type="text" class="bikeModel" name="model" />

                            </div>
                            <div class="col-sm-5">
                                <h4><span class="fr"> Référence du cadre : </span></h4>
                                <h4><span class="en"> Frame reference: </span></h4>
                                <h4><span class="nl"> Frame reference: </span></h4>
                                <p span class="frameReference"></p>

                            </div>

                            <div class="separator"></div>

                            <h4 class="text-green">Informations relatives au contrat</h4>

                            <div class="col-sm-4">
                                <h4><span class="fr"> Type de contrat : </span></h4>
                                <h4><span class="en"> Contract type: </span></h4>
                                <h4><span class="nl"> Contract type : </span></h4>
                                <p><span class="contractType"></span></p>
                            </div>

                           <div class="col-sm-4">
                                <h4><span class="fr" >Date de début :</span></h4>
                                <h4><span class="en" >Start date:</span></h4>
                                <h4><span class="nl" >Start date :</span></h4>
                                <p><span class="startDateContract"></span></p>
                            </div>

                            <div class="col-sm-4">
                                <h4><span class="fr" >Date de fin :</span></h4>
                                <h4><span class="en" >End date:</span></h4>
                                <h4><span class="nl" >End date :</span></h4>
                                <p><span class="endDateContract"></span></p>
                            </div>

                            <div class="separator"></div>

                            <h4 class="text-green">Informations relatives au vélo</h4>

                            <div class="col-md-12">
                                <img src="" class="bikeImage" alt="image" />
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <h4><span class="fr" >Status :</span></h4>
                                    <h4><span class="en" >Status:</span></h4>
                                    <h4><span class="nl" >Status :</span></h4>
                                    <select title="Bike Status" class="selectpicker" id="bikeStatus" name="bikeStatus">
                                      <option value="OK">En état d'utilisation</option>
                                      <option value="KO">Cassé</option>
                                      <option value="test">Vélo de test</option>
                                    </select>
                                </div>
                            </div>
                            <div class="separator"></div>
                            <div class="col-sm-12">
                                 <div class="col-md-6">
                                    <input type="text" class="hidden" id="widget-updateBikeStatus-form-frameNumber" name="widget-updateBikeStatus-form-frameNumber"/>
                                    <input type="text" class="hidden" name="user" value="<?php echo $user; ?>"/>
                                    <h4><span class="fr" >Accès aux bâtiments :</span></h4>
                                    <div id="bikeBuildingAccess"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                        <button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
                        <button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
                        <button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>
                        </div>
                    </form>
				</div>
			</div>


            <div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">

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

</script>


<div class="modal fade" id="updateBillingStatus" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
                    <form id="widget-updateBillingStatus-form" action="include/updateBillingStatus.php" role="form" method="post">

                        <div class="form-group col-md-12">

                            <h4 class="text-green">Informations sur la facture</h4>

                            <div class="col-md-3">
                                <label for="widget-updateBillingStatus-form-billingReference"  class="fr">ID</label>
                                <label for="widget-updateBillingStatus-form-billingReference"  class="en">ID</label>
                                <label for="widget-updateBillingStatus-form-billingReference"  class="nl">ID</label>
                                <input type="text" class="form-control required" readonly="readonly" name="widget-updateBillingStatus-form-billingReference">
                            </div>

                            <div class="col-md-3">
                                <label for="widget-updateBillingStatus-form-billingCompany"  class="fr">Originateur</label>
                                <label for="widget-updateBillingStatus-form-billingCompany"  class="en">Originateur</label>
                                <label for="widget-updateBillingStatus-form-billingCompany"  class="nl">Originateur</label>
                                <input type="text" class="form-control required" name="widget-updateBillingStatus-form-billingCompany">
                            </div>


                            <div class="col-md-3">
                                <label for="widget-updateBillingStatus-form-beneficiaryBillingCompany"  class="fr">Beneficiaire</label>
                                <label for="widget-updateBillingStatus-form-beneficiaryBillingCompany"  class="en">Beneficiaire</label>
                                <label for="widget-updateBillingStatus-form-beneficiaryBillingCompany"  class="nl">Beneficiaire</label>
                                <input type="text" name="widget-updateBillingStatus-form-beneficiaryBillingCompany" class="form-control required">
                            </div>

                            <div class="col-md-3">
                                <label for="widget-updateBillingStatus-form-type" class="widget-updateBillingStatus-form-type">Type de facture</label>
                                <input type="text" name="widget-updateBillingStatus-form-type" class="form-control required">
                            </div>

                            <div class="col-md-12"></div><!-- Pour mettre "COMMUNICATON" à la ligne -->
                            <div class="col-md-3">
                                <label for="widget-updateBillingStatus-form-communication"  class="fr">Communication</label>
                                <label for="widget-updateBillingStatus-form-communication"  class="en">Communication </label>
                                <label for="widget-updateBillingStatus-form-communication"  class="nl">Communication</label>
                                <input type="text" class="form-control required" name="widget-updateBillingStatus-form-communication">
                            </div>

                            <div class="separator"></div>

                            <h4 class="text-green">Informations sur les montants</h4>

                            <div class="col-md-3">
                                <label for="widget-updateBillingStatus-form-amountHTVA"  class="fr">Montant (HTVA)</label>
                                <label for="widget-updateBillingStatus-form-amountHTVA"  class="en">Amount (VAT ex.)</label>
                                <label for="widget-updateBillingStatus-form-amountHTVA"  class="nl">Amount (VAT ex.)</label>
                                <input type="text" class="form-control required" name="widget-updateBillingStatus-form-amountHTVA">
                            </div>

                            <div class="col-md-3">
                                <label for="widget-updateBillingStatus-form-VAT" class="fr">TVA ? </label>
                                <label for="widget-updateBillingStatus-form-VAT" class="nl">TVA ?</label>
                                <label for="widget-updateBillingStatus-form-VAT" class="en">TVA ? </label>
                                <input type="checkbox" class="form-control" name="widget-updateBillingStatus-form-VAT" />
                            </div>

                            <div class="col-md-3">
                                <label for="widget-updateBillingStatus-form-amountTVAC"  class="fr">Montant (TVAC)</label>
                                <label for="widget-updateBillingStatus-form-amountTVAC"  class="en">Amount (VAT inc.)</label>
                                <label for="widget-updateBillingStatus-form-amountTVAC"  class="nl">Amount (VAT inc.)</label>
                                <input type="text" class="form-control required" name="widget-updateBillingStatus-form-amountTVAC" readonly="readonly">
                            </div>

                            <div class="separator"></div>
                            <h4 class="text-green">Informations sur les dates</h4>
                            <div class="col-md-6">

                                <div class="col-md-6">
                                    <label for="widget-updateBillingStatus-form-date"  class="fr">Date</label>
                                    <label for="widget-updateBillingStatus-form-date"  class="en">Date</label>
                                    <label for="widget-updateBillingStatus-form-date"  class="nl">Date</label>
                                    <input type="date" class="widget-updateBillingStatus-form-date form-control required" name="widget-updateBillingStatus-form-date">
                                </div>
                                <div class="col-md-6">
                                    <label for="widget-updateBillingStatus-form-datelimite"  class="fr">Date limite de paiement</label>
                                    <label for="widget-updateBillingStatus-form-datelimite"  class="en">Date limite de paiement </label>
                                    <label for="widget-updateBillingStatus-form-datelimite"  class="nl">Date limite de paiement</label>
                                    <input type="date" class="form-control required" name="widget-updateBillingStatus-form-datelimite">
                                </div>
                                <div class="col-md-12"></div><!-- Pour mettre "Envoyée" à la ligne -->

                                <div class="col-md-6">
                                    <label for="widget-updateBillingStatus-form-sent"  class="fr">Envoyée ?</label>
                                    <label for="widget-updateBillingStatus-form-sent"  class="en">Sent ?</label>
                                    <label for="widget-updateBillingStatus-form-sent"  class="nl">Sent ?</label>
                                    <input type="checkbox" class='form-control' name="widget-updateBillingStatus-form-sent" >
                                </div>

                                <div class="col-md-6">
                                    <label for="widget-updateBillingStatus-form-sendingDate"  class="fr">Date d'envoi</label>
                                    <label for="widget-updateBillingStatus-form-sendingDate"  class="en">Sending date </label>
                                    <label for="widget-updateBillingStatus-form-sendingDate"  class="nl">Sending date</label>
                                    <input type="date" class='form-control'  name="widget-updateBillingStatus-form-sendingDate">
                                </div>
                                <div class="col-md-12"></div><!-- Pour mettre "Payée" à la ligne -->
                                <div class="col-md-6">
                                    <label for="widget-updateBillingStatus-form-paid"  class="fr">Payée ?</label>
                                    <label for="widget-updateBillingStatus-form-paid"  class="en">Paid ?</label>
                                    <label for="widget-updateBillingStatus-form-paid"  class="nl">Paid ?</label>
                                    <input type="checkbox" class='form-control'  name="widget-updateBillingStatus-form-paid" >
                                </div>

                                <div class="col-md-6">
                                    <label for="widget-updateBillingStatus-form-paymentDate"  class="fr">Date de paiement</label>
                                    <label for="widget-updateBillingStatus-form-paymentDate"  class="en">Payment date </label>
                                    <label for="widget-updateBillingStatus-form-paymentDate"  class="nl">Payment date</label>
                                    <input type="date" class='form-control'  name="widget-updateBillingStatus-form-paymentDate" >
                                </div>


                            </div>
                            <div class="col-md-6">
                                <h4 class="text-green">Informations sur le fichier</h4>

                                <div class="col-md-12">
                                    <label for="accounting"  class="fr">Envoyée au comptable ?</label>
                                    <label for="accounting"  class="en">Sent to accounting ?</label>
                                    <label for="accounting"  class="nl">Sent to accounting ?</label>
                                    <input type="checkbox" class='form-control'  name="accounting" >
                                </div>
                                <br><br>

                                <div class="col-md-12">
                                    <a href="#" class="widget-updateBillingStatus-form-currentFile" target="_blank"><img src="images/pdf.jpg" /></a>
                                    <input type="text" name="widget-updateBillingStatus-form-currentFile" class="hidden"/>
                                </div>

                                <div class="col-md-12">
                                    <label for="widget-updateBillingStatus-form-file"  class="fr">Modifier la facture (pdf):</label>
                                    <label for="widget-updateBillingStatus-form-file"  class="en">Modify the bill (pdf):</label>
                                    <label for="widget-updateBillingStatus-form-file"  class="nl">Modify the bill (pdf):</label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                                    <input type=file size=40 name="widget-updateBillingStatus-form-file">
                                </div>

                            </div>



				        </div>
                        <div class="separator"></div>
                        <h4 class="text-green">Détails de la facture</h4>
                        <div id="billingDetails" class="col-md-12">
                        </div>

                        <input type="text" name="widget-updateBillingStatus-form-user" value="<?php echo $user; ?>" class="hidden">

                        <div class="col-sm-12">
                            <button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
                            <button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
                            <button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>
                        </div>
                    </form>

                    <form id="widget-deleteBillingStatus-form" action="include/updateBillingStatus.php" role="form" method="post">
                        <div class="col-sm-12">
                            <input type="text" name="user" value="<?php echo $user; ?>" class="hidden">
                            <input type="text" name="action" value="delete" class="hidden">
                            <input type="text" class="hidden" readonly="readonly" name="reference">
                            <button  class="fr button small red button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Supprimer</button>
                            <button  class="nl button small red button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Delete</button>
                            <button  class="en button small red button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Delete</button>
                        </div>
                    </form>



				</div>
			</div>


            <div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">
    $('input[name=widget-updateBillingStatus-form-VAT]').change(function(){
        if($('input[name=widget-updateBillingStatus-form-VAT]').is(':checked')){
            $('input[name=widget-updateBillingStatus-form-amountTVAC]').val((1.21*$('input[name=widget-updateBillingStatus-form-amountHTVA]').val()).toFixed(2));
        }else{
            $('input[name=widget-updateBillingStatus-form-amountTVAC]').val($('input[name=widget-updateBillingStatus-form-amountHTVA]').val());
        }
      });
    $('input[name=widget-updateBillingStatus-form-amountHTVA]').change(function(){
        if($('input[name=widget-updateBillingStatus-form-VAT]').is(':checked')){
            $('input[name=widget-updateBillingStatus-form-amountTVAC]').val((1.21*$('input[name=widget-updateBillingStatus-form-amountHTVA]').val()).toFixed(2));
        }else{
            $('input[name=widget-updateBillingStatus-form-amountTVAC]').val($('input[name=widget-updateBillingStatus-form-amountHTVA]').val());
        }
      });
    jQuery("#widget-updateBillingStatus-form").validate({
        submitHandler: function(form) {
            jQuery(form).ajaxSubmit({
                success: function(response) {
                    if (response.response == 'success') {
                        document.getElementById('widget-updateBillingStatus-form').reset();
                        $.notify({
                            message: response.message
                        }, {
                            type: 'success'
                        });
                        $('#updateBillingStatus').modal('toggle');
                        get_bills_listing(document.getElementsByClassName('billSelectionText')[0].innerHTML, '*', '*', '*');
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

    jQuery("#widget-deleteBillingStatus-form").validate({
        submitHandler: function(form) {
            jQuery(form).ajaxSubmit({
                success: function(response) {
                    if (response.response == 'success') {
                        document.getElementById('widget-deleteBillingStatus-form').reset();
                        $.notify({
                            message: response.message
                        }, {
                            type: 'success'
                        });
                        $('#updateBillingStatus').modal('toggle');
                        get_bills_listing(document.getElementsByClassName('billSelectionText')[0].innerHTML, '*', '*', '*');
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


</script>

<div class="modal fade" id="updateAction" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">

						<form id="widget-updateAction-form" action="include/action_company.php" role="form" method="post">

                            <div class="form-group col-sm-12">

                                <h4 class="fr text-green">Modifier une action</h4>

                                <div class="col-sm-12">
                                    <div class="col-sm-4">
                                        <label for="id">ID :</label>
                                        <input type="text" readonly="readonly" class="form-control required" name="id" readonly="true"/>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="owner"> Owner : </label>
                                        <select title="Société" class="selectpicker" name="owner">
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="company" class="fr"> Société : </label>
                                        <label for="company" class="en"> Société : </label>
                                        <label for="company" class="nl"> Société : </label>
                                        <select title="Société" class="selectpicker" name="company">
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="col-sm-4">
                                        <label for="type" class="fr"> Type : </label>
                                        <label for="type" class="en"> Type : </label>
                                        <label for="type" class="nl"> Type : </label>
                                        <select title="type" class="selectpicker form-control required" name="type">
                                          <option value="contact">Prise de contact</option>
                                          <option value="rappel">Rappel</option>
                                          <option value="plan rdv">Planification de rendez-vous</option>
                                          <option value="rdv">Rendez-vous</option>
                                          <option value="offre">Formulation d'une offre</option>
                                          <option value="offreSigned">Offre signée</option>
                                          <option value="delivery">Livraison vélo</option>
                                          <option value="other">Autre</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="date">Date :</label>
                                        <input type="date" class="form-control required" name="date" />

                                    </div>
                                    <div class="col-sm-4">
                                        <label for="date_reminder">Rappel :</label>
                                        <input type="date" class="form-control" name="date_reminder" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="col-sm-12">
                                        <label for="title">Titre :</label>
                                        <input type="text" class="form-control required" name="title" />

                                    </div>
                                    <div class="col-sm-12">
                                        <label for="description">Description :</label>
                                        <textarea class="form-control" rows="5" name="description"></textarea>

                                    </div>
                                    <div class="col-sm-5">
                                        <label for="status">Statut :</label>
                                        <select title="Status" class="selectpicker" name="status">
                                          <option value="TO DO">To do</option>
                                          <option value="DONE">Done</option>
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <input type="text" name="requestor" value="<?php echo $user; ?>" class="hidden"/>
                            <input type="text" name="action" value="update" class="hidden"/>

                            <div class="col-sm-12">
                                <button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Sauvegarder</button>
                                <button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Save</button>
                                <button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Save</button>
                            </div>
                        </form>
                    </div>
				</div>
			</div>


            <div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">
    jQuery("#widget-updateAction-form").validate({
        submitHandler: function(form) {
            jQuery(form).ajaxSubmit({
                success: function(response) {
                    if (response.response == 'success') {
                        $.notify({
                            message: response.message
                        }, {
                            type: 'success'
                        });
                        list_tasks('*', $('.taskOwnerSelection').val(), $('.tasksListing_number').val());
                        document.getElementById('widget-updateAction-form').reset();
                        $('#updateAction').modal('toggle');


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

</script>


<div class="modal fade" id="updatePortfolioBike" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">

						<form id="widget-updateCatalog-form" action="include/update_catalog_bike.php" role="form" method="post">

                            <div class="form-group col-sm-12">

                                <h4 class="fr text-green">Modifier un vélo</h4>
                                <div class="col-sm-12">

                                    <div class="col-sm-4">
                                        <label for="ID">ID :</label>
                                        <input type="text" readonly="readonly" class="form-control" name="ID"/>
                                    </div>
                                    <div class="col-sm-12"></div>

                                    <div class="col-sm-4">
                                        <label for="brand" class="fr"> Marque : </label>
                                        <label for="brand" class="en"> Brand : </label>
                                        <label for="brand" class="nl"> Brand : </label>
                                        <select class="form-control required" name="brand">
                                                   <option value="Ahooga">Ahooga</option>
                                                   <option value="Bzen">Bzen</option>
                                                   <option value="Conway">Conway</option>
                                                   <option value="Douze Cycle">Douze Cycle</option>
                                                   <option value="HNF Nicolai">HNF Nicolai</option>
                                                   <option value="Orbea">Orbea</option>
                                                   <option value="Stevens">Stevens</option>
                                        </select>

                                    </div>
                                    <div class="col-sm-4">
                                        <label for="widget-updateCatalog-form-model" class="fr"> Modèle : </label>
                                        <label for="widget-updateCatalog-form-model" class="en"> Model : </label>
                                        <label for="widget-updateCatalog-form-model" class="nl"> Model : </label>
                                        <input type="text" class="form-control required" name="model" />
                                    </div>
                                    <div class="col-sm-4">
                                        <h4><span class="fr"> Type de cadre : </span></h4>
                                        <h4><span class="en"> Frame type: </span></h4>
                                        <h4><span class="nl"> Frame type: </span></h4>
                                        <select class="form-control  required" name="frame">
                                                   <option value="F">Femme</option>
                                                   <option value="H">Homme</option>
                                                   <option value="M">Mixte</option>
                                        </select>

                                    </div>

                                </div>
                                <div class="col-sm-12">

                                    <div class="col-sm-4">
                                        <h4><span class="fr"> Utilisation : </span></h4>
                                        <h4><span class="en"> Utilisation: </span></h4>
                                        <h4><span class="nl"> Utilisation: </span></h4>
                                        <select class="form-control" name="utilisation">
                                           <option value="Tout chemin">Tout chemin</option>
                                           <option value="Ville et chemin">Ville et chemin</option>
                                           <option value="Pliant">Pliant</option>
                                           <option value="Ville">Ville</option>
                                           <option value="Cargo">Cargo</option>
                                           <option value="Speedpedelec">Speedpedelec</option>
                                        </select>

                                    </div>
                                    <div class="col-sm-4">
                                        <h4><span class="fr"> Vélo électrique ? </span></h4>
                                        <h4><span class="en"> Electric bike? </span></h4>
                                        <h4><span class="nl"> Electric bike? </span></h4>
                                        <select class="form-control  required" name="electric">
                                                   <option value="Y">Y</option>
                                                   <option value="N">N</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-sm-12">

                                    <div class="col-sm-4">
                                        <label for="buyPrice" class="fr"> Prix  d'achat :</label>
                                        <label for="buyPrice" class="en"> Buy price :</label>
                                        <label for="buyPrice" class="nl"> Buy price :</label>
                                        <input type="text" class="form-control  required" name="buyPrice" />
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="price" class="fr"> Prix  de vente: </label>
                                        <label for="price" class="en"> Selling Price: </label>
                                        <label for="price" class="nl"> Selling Price: </label>
                                        <input type="text" class="form-control  required" name="price" />
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="stock" class="fr"> En stock ? </label>
                                        <label for="stock" class="en"> Sotck? </label>
                                        <label for="stock" class="nl"> Stock? </label>
                                        <input type="text" class="form-control required" name="stock" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="col-sm-4">
                                        <label for="display" class="fr">Afficher ? </label>
                                        <label for="display" class="en">Display ? </label>
                                        <label for="display" class="nl">Display ? </label>
                                        <input type="checkbox" name="display" class="form-control">
                                    </div>


                                    <div class="col-sm-8">
                                        <label for="link" class="fr"> Lien vers le site : </label>
                                        <label for="link" class="en"> Vendor link : </label>
                                        <label for="link" class="nl"> Vendor link</label>
                                        <input type="text" class="form-control  required" name="link" />
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-green">Image en taille normale</h4>

                            <div class="col-sm-12">


                                <img src="" class="bikeCatalogImage" alt="image" height="200" />

                                <div class="col-sm-6">
                                    <label for="file"  class="fr">Modifier la photo (ne rien uploader si ok)</label>
                                    <label for="file"  class="en">Modify the picture (don't do anything if already ok)</label>
                                    <label for="file"  class="nl">Modify the picture (don't do anything if already ok)</label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                                    <input type=file size=40 class="form-control" name="file">
                                </div>
                            </div>

                            <div class="col-sm-12">

                                <h4 class="text-green">Image en taille réduite</h4>
                                    <img src="" class="bikeCatalogImageMini" alt="image" height="200" />
                                <div class="col-sm-6">
                                    <label for="fileMini"  class="fr">Modifier la photo mini (ne rien uploader si ok)</label>
                                    <label for="fileMini"  class="en">Modify the mini picture (don't do anything if already ok)</label>
                                    <label for="fileMini"  class="nl">Modify the mini picture (don't do anything if already ok)</label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                                    <input type=file size=40 class="form-control" name="fileMini">
                                </div>
                            </div>


                            <input type="text" name="user" value="<?php echo $user; ?>" class="hidden"/>
                            <input type="text" name="action" value="update" class="hidden"/>

                            <div class="col-sm-12">
                                <button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Sauvegarder</button>
                                <button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Save</button>
                                <button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Save</button>
                            </div>
                        </form>

                        <form id="widget-deletePortfolioBike-form" action="include/update_catalog_bike.php" role="form" method="post">
                            <div class="col-sm-12">
                                <input type="text" name="user" value="<?php echo $user; ?>" class="hidden">
                                <input type="text" name="action" value="delete" class="hidden">
                                <input type="text" class="hidden" readonly="readonly" name="id">
                                <button  class="fr button small red button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Supprimer</button>
                                <button  class="nl button small red button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Delete</button>
                                <button  class="en button small red button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Delete</button>
                            </div>
                        </form>

                    </div>
				</div>
			</div>


            <div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">

    jQuery("#widget-updateCatalog-form").validate({
        submitHandler: function(form) {
            jQuery(form).ajaxSubmit({
                success: function(response) {

                    if (response.response == 'success') {
                        $.notify({
                            message: response.message
                        }, {
                            type: 'success'
                        });
                        listPortfolioBikes();
                        document.getElementById('widget-updateCatalog-form').reset();
                        $('#updatePortfolioBike').modal('toggle');

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

    jQuery("#widget-deletePortfolioBike-form").validate({
        submitHandler: function(form) {
            jQuery(form).ajaxSubmit({
                success: function(response) {

                    if (response.response == 'success') {
                        $.notify({
                            message: response.message
                        }, {
                            type: 'success'
                        });
                        listPortfolioBikes();
                        document.getElementById('widget-updateCatalog-form').reset();
                        $('#updatePortfolioBike').modal('toggle');

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

</script>


<div class="modal fade" id="addPortfolioBike" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
                    <h4 class="text-green">Ajouter un vélo au catalogue</h4>
                    <form id="widget-addCatalog-form" action="include/add_catalog_bike.php" role="form" method="post">
                        <div class="col-sm-12">
                            <h4 class="text-green">Informations sur le modèle</h4>

                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <label for="brand" class="fr"> Marque : </label>
                                    <label for="brand" class="en"> Brand: </label>
                                    <label for="brand" class="nl"> Brand : </label>
                                    <select class="form-control required" name="brand">
                                               <option value="Ahooga">Ahooga</option>
                                               <option value="Bzen">Bzen</option>
                                               <option value="Conway">Conway</option>
                                               <option value="Douze Cycle">Douze Cycle</option>
                                               <option value="HNF Nicolai">HNF Nicolai</option>
                                               <option value="Orbea">Orbea</option>
                                               <option value="Stevens">Stevens</option>
                                               <option value="other">Other</option>
                                    </select>

                                </div>
                                <div class="col-sm-4">
                                    <label for="model" class="fr"> Modèle : </label>
                                    <label for="model" class="en"> Model: </label>
                                    <label for="model" class="nl"> Model: </label>
                                    <input type="text" class="form-control required" name="model" />

                                </div>
                                <div class="col-sm-4">
                                    <label for="frame" class="fr"> Type de cadre : </label>
                                    <label for="frame" class="en"> Frame type: </label>
                                    <label for="frame" class="nl"> Frame type: </label>
                                    <select class="form-control required" name="frame">
                                               <option value="F">Femme</option>
                                               <option value="H">Homme</option>
                                               <option value="M">Mixte</option>
                                    </select>

                                </div>
                            </div>

                            <div class="col-sm-12">

                                <div class="col-sm-4">
                                    <label for="utilisation" class="fr"> Utilisation : </label>
                                    <label for="utilisation" class="en"> Utilisation: </label>
                                    <label for="utilisation" class="nl"> Utilisation: </label>
                                    <select class="form-control required" name="utilisation">
                                               <option value="Tout chemin">Tout chemin</option>
                                               <option value="Ville et chemin">Ville et chemin</option>
                                               <option value="Pliant">Pliant</option>
                                               <option value="Ville">Vile</option>
                                               <option value="Cargo">Cargo</option>
                                               <option value="Speedpedelec">Speedpedelec</option>
                                    </select>

                                </div>
                                <div class="col-sm-4">
                                    <label for="electric" class="fr"> Vélo électrique ? </label>
                                    <label for="electric" class="en"> Electric bike? </label>
                                    <label for="electric" class="nl"> Electric bike? </label>
                                    <select class="form-control required" name="electric">
                                               <option value="Y">Y</option>
                                               <option value="N">N</option>
                                    </select>

                                </div>
                                <div class="col-sm-4">
                                    <label for="link" class="fr"> Lien vers le site : </label>
                                    <label for="link" class="en"> Vendor link : </label>
                                    <label for="link" class="nl"> Vendor link</label>
                                    <input type="text" class="form-control required" name="link" />
                                </div>
                            </div>
                            <div class="separator"></div>
                            <h4 class="text-green">Information financières et stock</h4>

                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <label for="buyPrice" class="fr">Prix d'achat</label>
                                    <label for="buyPrice" class="nl">Buying price</label>
                                    <label for="buyPrice" class="en">Buying price</label>
                                    <input type="text" class="form-control required" name="buyPrice" />
                                </div>
                                <div class="col-sm-4">
                                    <label for="price" class="fr"> Prix : </label>
                                    <label for="price" class="en"> Price: </label>
                                    <label for="price" class="nl"> Price: </label>
                                    <input type="text" class="form-control required" name="price" />
                                </div>
                                <div class="col-sm-4">
                                    <label for="stock" class="fr"> En stock ? </label>
                                    <label for="stock" class="en"> Sotck? </label>
                                    <label for="stock" class="nl"> Stock? </label>
                                    <input type="text" class="bikeCatalogStock form-control required" name="stock" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                    <div class="col-sm-4">
                                        <label for="display" class="fr">Afficher ? </label>
                                        <label for="display" class="en">Display ? </label>
                                        <label for="display" class="nl">Display ? </label>
                                        <input type="checkbox" name="display" class="form-control">
                                    </div>



                            </div>
                            <div class="separator"></div>
                            <h4 class="text-green">Photos</h4>


                            <div class="form-group col-sm-6">
                                <label for="file"  class="fr">Photo</label>
                                <label for="file"  class="en">Picture</label>
                                <label for="file"  class="nl">Picture</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                                <input type=file size=40 class="form-control required" name="file">
                            </div>

                            <div class="col-sm-6">
                                <label for="fileMini"  class="fr">Photo mini</label>
                                <label for="fileMini"  class="en">Mini picture</label>
                                <label for="fileMini"  class="nl">Mini picture</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                                <input type=file size=40 class="form-control required" name="fileMini">
                            </div>
                        </div>

                        <input type="text" name="user" value="<?php echo $user; ?>" class="hidden" />

                        <div class="col-sm-12">
                            <button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
                            <button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
                            <button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>
                        </div>
                    </form>
				</div>
			</div>


            <div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">

    jQuery("#widget-addCatalog-form").validate({
        submitHandler: function(form) {
            jQuery(form).ajaxSubmit({
                success: function(response) {

                    if (response.response == 'success') {
                        $.notify({
                            message: response.message
                        }, {
                            type: 'success'
                        });
                        listPortfolioBikes();
                        document.getElementById('widget-addCatalog-form').reset();
                        $('#addPortfolioBike').modal('toggle');

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

</script>


<div class="modal fade" id="tellus" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<form id="widget-tellus-form" action="include/tellus-form.php" role="form" method="post">

                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="subject"  class="fr">Votre sujet</label>
										<label for="subject"  class="en">Subject</label>
										<label for="subject"  class="nl">Onderwerp</label>
                                        <input type="text" name="widget-tellus-form-subject" id="widget-tellus-form-subject" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message"  class="fr">Message</label>
									<label for="message"  class="en">Message</label>
									<label for="message"  class="nl">Bericht</label>
                                    <textarea type="text" name="widget-tellus-form-message" id="widget-tellus-form-message" rows="5" class="form-control required"></textarea>
                                </div>
                                <input type="text" class="hidden" id="widget-tellus-form-antispam" name="widget-tellus-form-antispam" value="" />
                                <button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
								<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
								<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>
                            </form>
							<script type="text/javascript">

                                function initializeTellUs() {
                                    document.getElementById('widget-tellus-form-subject').value="";
                                    document.getElementById('widget-tellus-form-message').value="";

                                }

                                jQuery("#widget-tellus-form").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });
													$('#tellus').modal('toggle');

                                                } else {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'danger'
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });

                            </script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="update" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<form id="widget-updateInfo" action="include/updateInfos.php" role="form" method="post">
                                <div class="row">
									<h4 class="col-md-3 fr">Informations générales</h4>
									<h4 class="col-md-3 en">General information</h4>
									<h4 class="col-md-3 nl">Algemene informatie</h4>
                                    <div class="form-group col-sm-12">
                                        <label for="firstname"  class="fr">Prénom</label>
                                        <label for="firstname"  class="en">Firstname</label>
                                        <label for="firstname"  class="nl">Voornaam</label>
                                        <input type="text" id="widget-update-form-firstname" name="widget-update-form-firstname" class="form-control required" value="<?php echo $row["PRENOM"] ?>">

                                        <label for="firstname"  class="fr">Nom</label>
                                        <label for="firstname"  class="en">Name</label>
                                        <label for="firstname"  class="nl">Achternaam</label>
                                        <input type="text" id="widget-update-form-name" name="widget-update-form-name" class="form-control required" value="<?php echo $row["NOM"] ?>">


                                        <label for="telephone"  class="fr">Numéro de téléphone</label>
										<label for="telephone"  class="en">Phone number</label>
										<label for="telephone"  class="nl">Telefoonnumber</label>
                                        <input type="text" id="widget-update-form-phone" name="widget-update-form-phone" class="form-control" value="<?php echo $row["PHONE"] ?>">
                                    </div>
                                    <h4 class="col-md-3 fr">Domicile</h4>
                                    <h4 class="col-md-3 en">Home</h4>
                                    <h4 class="col-md-3 nl">Thuis</h4>
                                    <div class="form-group col-sm-12">
                                        <label for="email"  class="fr">Adresse</label>
                                        <label for="email"  class="en">Adress</label>
                                        <label for="email"  class="nl">Adres</label>
                                        <input type="text" id="widget-update-form-adress" name="widget-update-form-adress" class="form-control" value="<?php echo $row['ADRESS'] ?>">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="widget-update-form-post-code"  class="fr">Code Postal</label>
                                        <label for="widget-update-form-post-code"  class="en">Postal Code</label>
                                        <label for="widget-update-form-post-code"  class="nl">Postcode</label>
                                        <input type="text" id="widget-update-form-post-code" name="widget-update-form-post-code" class="form-control" value="<?php echo $row['POSTAL_CODE'] ?>" autocomplete="postal-code">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="widget-update-form-city"  class="fr">Commune</label>
                                        <label for="widget-update-form-city"  class="en">City</label>
                                        <label for="widget-update-form-city"  class="nl">Gemeente</label>
                                        <input type="text" id="widget-update-form-city" name="widget-update-form-city" class="form-control" value="<?php echo $row['CITY'] ?>" autocomplete="address-level2">
                                    </div>
                                    <h4 class="col-md-3 fr">Lieu de travail</h4>
                                    <h4 class="col-md-3 nl">Werk</h4>
                                    <h4 class="col-md-3 en">Work place</h4>
                                    <div class="form-group col-sm-12">
                                        <label for="widget-update-form-work-adress"  class="fr">Adresse</label>
                                        <label for="widget-update-form-work-adress"  class="en">Adress</label>
                                        <label for="widget-update-form-work-adress"  class="nl">Adres</label>
                                        <input type="text" id="widget-update-form-work-adress" name="widget-update-form-work-adress" class="form-control" value="<?php echo $row['WORK_ADRESS'] ?>" autocomplete="off">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="widget-update-form-work-post-code"  class="fr">Code Postal</label>
                                        <label for="widget-update-form-work-post-code"  class="en">Postal Code</label>
                                        <label for="widget-update-form-work-post-code"  class="nl">Postcode</label>
                                        <input type="text" id="widget-update-form-work-post-code" name="widget-update-form-work-post-code" class="form-control" value="<?php echo $row['WORK_POSTAL_CODE'] ?>" autocomplete="off">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="widget-update-form-work-city"  class="fr">Commune</label>
                                        <label for="widget-update-form-work-city"  class="en">City</label>
                                        <label for="widget-update-form-work-city"  class="nl">Gemeente</label>
                                        <input type="text" id="widget-update-form-work-city" name="widget-update-form-work-city" class="form-control" value="<?php echo $row['WORK_CITY'] ?>" autocomplete="off">
                                    </div>
                                    <div class="col-sm-3">
                                    <label for="password"  class="fr">Mot de passe</label>
                                    <label for="password"  class="en">Password</label>
                                    <label for="password"  class="nl">Wachtwoord</label>
                                    </div>
                                    <div class="col-sm-9">
                                    <a class="text-green fr" onclick="updatePassword()">Actualiser</a>
                                    <a class="text-green en" onclick="updatePassword()">Update</a>
                                    <a class="text-green nl" onclick="updatePassword()">Update</a>
                                    </div>

                                     <div class="col-sm-12">
                                        <span id="widget-update-form-password-text"></span>
                                        <input type="password" id="widget-update-form-password" name="widget-update-form-password" class="form-control" value="********" autocomplete="off" readonly>
                                        <span id="widget-update-form-password-confirmation-text"></span>
                                        <input type="hidden" id="widget-update-form-password-confirmation"  name="widget-update-form-password-confirmation" class="form-control required" autocomplete="off">
                                        <input id="widget-update-form-password-switch" name="widget-update-form-password-switch" type="hidden" value="false">
                                    </div>

									<input type="text" class="hidden" id="widget-contact-form-antispam" name="widget-updateInfo-antispam" value="" />
								</div>
								<button  class="fr button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Envoyer</button>
								<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
								<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>

                            </form>
							<script type="text/javascript">

                                function initializeUpdate(){
                                    document.getElementById('widget-update-form-password-text').innerHTML="";
                                    document.getElementById('widget-update-form-password').readOnly = true;
                                    document.getElementById('widget-update-form-password').value="********";
                                    document.getElementById('widget-update-form-password-confirmation-text').innerHTML="";
                                    document.getElementById('widget-update-form-password-confirmation').type='hidden';
                                    document.getElementById('widget-update-form-password-switch').value="false";
                                }

                                function updatePassword(){

                                    document.getElementById('widget-update-form-password-text').innerHTML="<span class=\"fr\">Votre Nouveau mot de passe :</span><span class=\"nl\">Your new password :</span><span class=\"en\">Your new password:</span>";
                                    document.getElementById('widget-update-form-password').removeAttribute('readonly');
                                    document.getElementById('widget-update-form-password').value="";
                                    document.getElementById('widget-update-form-password-confirmation-text').innerHTML="<span class=\"fr\">Veuillez confirmer :</span><span class=\"nl\">Please confirm :</span><span class=\"en\">Please confirm:</span>";
                                    document.getElementById('widget-update-form-password-confirmation').type='password';
                                    document.getElementById('widget-update-form-password-switch').value="true";

                                    displayLanguage();
                                    var langue = getLanguage();
                                }
                                jQuery("#widget-updateInfo").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });
													$('#update').modal('toggle');
                                                    var timestamp=Date.now().toString();
                                                    addressDomicile="<?php
                                                    $address=$row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'];
                                                    echo $address;?>";
                                                    get_meteo(timestamp.substring(0,10), addressDomicile)
                                                } else {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'danger'
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });

                            </script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="assistance" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<div class=" jumbotron jumbotron-small jumbotron-border">
							<a data-target="#assistance2" data-toggle="modal" href="#" onclick="initializeAssistance2()">
								<img src="images/assistance.jpg" class="img-responsive img-rounded" alt="assistance">
								<h3 class="text-green fr">Assistance</h3>
								<h3 class="text-green en">Assistance</h3>
								<h3 class="text-green nl">Bijstand</h3>
								<p class="fr"><small>Vous avez besoin d'une intervention directement?</small></p>
								<p class="en"><small>Do you need an imediate intervention?</small></p>
								<p class="nl"><small>Heeft u een onmiddellijke interventie nodig?</small></p>
								<p></p>
								<p></p>
							</a>
						</div>

					</div>

					<div class="col-sm-6">
						<div class=" jumbotron jumbotron-small jumbotron-border">
							<a data-target="#entretien2" data-toggle="modal" href="#" onclick="initializeEntretien2()">
								<img src="images/entretien.jpg" class="img-responsive img-rounded" alt="entretien">
								<h3 class="text-green fr">Entretien</h3>
								<h3 class="text-green en">Maintenance</h3>
								<h3 class="text-green nl">Onderhoud</h3>
								<p class="fr"><small>Vous voulez continuer à rouler sans endommager le vélo?</small></p>
								<p class="en"><small>Ask for a maintenance</small></p>
								<p class="nl"><small>Vraag om onderhoud</small></p>
							</a>
						</div>

					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="assistance2" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="fr">Contacter l'assistance</h4>
						<h4 class="en">Contact assistance</h4>
						<h4 class="nl">Neem contact op met hulp</h4>
						<p class="fr">Appelez le numéro d'urgence de votre assurance P-Vélo <br> <em class="text-green">02 / 642 45 03</em></p>
						<p class="en">Call the P-Velo number <br> <em class="text-green">02 / 642 45 03</em></p>
						<p class="nl">Bel het P-Velo-nummer <br> <em class="text-green">02 / 642 45 03</em></p>
						<br>
						<p><span class="fr">Donnez votre numéro de contrat </span>
						<span class="en">Give your contract number </span>
						<span class="nl">Geef je contractnummer op </span>
						<em class="text-green" id="ContractReference"><?php

						if(isset($contractNumber) && $contractNumber!='0' && $contractNumber!='')
						{
							echo "<span style='display:block'>".$contractNumber."</span>";
						}
						else{
							echo "<span class=\"fr\"> Contactez-nous !</span><span class=\"en\">Please contact us</span><span class=\"nl\">Contacteer ons alsjeblieft</span>";
						}
						?></em></p>
						<br>
						<p class="fr">Pour nous aider à suivre votre dossier, veuillez remplir les informations ci-dessous.</p>
						<p class="en">To help to follow the ticket, please mention the following information.</p>
						<p class="nl">Volg de volgende informatie om het ticket te volgen.</p>

						<form id="widget-assistance-form" action="include/assistance-form.php" role="form" method="post">

							<div class="form-group">
								<label for="widget-assistance-form-message"  class="fr">Description du problème</label>
								<label for="widget-assistance-form-message"  class="en">Message</label>
								<label for="widget-assistance-form-message"  class="nl">Bericht</label>
								<textarea type="text" id="widget-assistance-form-message" name="widget-assistance-form-message" rows="5" class="form-control required"></textarea>
							</div>
							<div class="form-group">
							<p class="fr">Photo du problème</p>
							<p class="en">Picture of the issue</p>
							<p class="nl">Beeld van het probleem</p>
								<input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
								<input type=file size=40 id="widget-assistance-form-message-attachment" name="widget-assistance-form-message-attachment">
							</div>
                            <?php
                            if(isset($contractNumber) && $contractNumber!='0' && $contractNumber!='')
                            {
                                echo "<input type=\"text\" class=\"hidden\" name=\"widget-assistance-form-contract\" value=\"".$contractNumber."\" />";
                            }
                            else{
                                echo "<input type=\"text\" class=\"hidden\" name=\"widget-assistance-form-contract\"/>";
                            }
                            ?>

							<button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
							<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
							<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>
						</form>
						<script type="text/javascript">
							jQuery("#widget-assistance-form").validate({

								submitHandler: function(form) {

									jQuery(form).ajaxSubmit({
										success: function(text) {
											if (text.response == 'success') {
												$.notify({
													message: text.message
												}, {
													type: 'success'
												});
												$('#assistance2').modal('toggle');
												$('#assistance').modal('toggle');

											} else {
												$.notify({
													message: text.message
												}, {
													type: 'danger'
												});
											}
										}
									});
								}
							});

						</script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="entretien2" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
					<h4 class="fr">Demander un entretien</h4>
					<h4 class="en">Ask for an maintenance</h4>
					<h4 class="nl">Vraag om een onderhoud</h4>
						<form id="widget-entretien-form" action="include/entretien-form.php" role="form" method="post">

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="widget-entretien-form-message"  class="fr">Numéro de cadre</label>
                                        <label for="widget-entretien-form-message"  class="en">Frame Number</label>
                                        <label for="widget-entretien-form-message"  class="nl">Frame Numer</label>
                                        <input type="text" id="widget-entretien-form-frame-number" name="widget-entretien-form-frame-number" class="form-control required" />
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="widget-entretien-form-bikePart"  class="fr">Pièce présentant un problème</label>
										<label for="widget-entretien-form-bikePart"  class="en">Subject</label>
										<label for="widget-entretien-form-bikePart"  class="nl">Onderwerp</label>
                                        <select id="widget-entretien-form-bikePart" name="widget-entretien-form-bikePart">
                                           <option value="...">...</option>
								           <option value="Cadre" class="fr">Cadre</option>
								           <option value="Cadre" class="en">Frame</option>
								           <option value="Cadre" class="nl">Geraamte</option>
								           <option value="Guidon" class="fr">Guidon</option>
								           <option value="Guidon" class="en">Handle</option>
								           <option value="Guidon" class="nl">Handvat</option>
								           <option value="Selle" class="fr">Selle</option>
								           <option value="Selle" class="nl">Saddle</option>
								           <option value="Selle" class="nl">Zadel</option>
								           <option value="Roue" class="fr">Roue</option>
								           <option value="Roue" class="en">Wheel</option>
								           <option value="Roue" class="nl">Wiel</option>
								           <option value="Pédalier" class="fr">Pédalier</option>
								           <option value="Pédalier" class="en">Drive</option>
								           <option value="Pédalier" class="nl">Aandrijving</option>
								           <option value="Freins" class="fr">Freins</option>
								           <option value="Freins" class="en">Brake</option>
								           <option value="Freins" class="nl">Handrem</option>
								           <option value="Chaine" class="fr">Chaine</option>
								           <option value="Chaine" class="en">Chain</option>
								           <option value="Chaine" class="nl">Ketting</option>
								           <option value="Lampe" class="fr">Phare</option>
								           <option value="Lampe" class="en">Lights</option>
								           <option value="Lampe" class="nl">Lamp</option>
								           <option value="Autre" class="fr">Autre</option>
								           <option value="Autre" class="en">Other</option>
								           <option value="Autre" class="nl">Ander</option>
								       </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="widget-entretien-form-message"  class="fr">Décrivez le problème</label>
									<label for="widget-entretien-form-message"  class="en">Message</label>
									<label for="widget-entretien-form-message"  class="nl">Bericht</label>
                                    <textarea type="text" id="widget-entretien-form-message" name="widget-entretien-form-message" rows="5" class="form-control required"></textarea>
                                </div>
                                <div class="form-group">
									<label for="widget-entretien-form-message-attachment"  class="fr">Si possible, veuillez faire une photo de la pièce défectueuse</label>
									<label for="widget-entretien-form-message-attachment"  class="en">If possible, please provide a picture of the issue</label>
									<label for="widget-entretien-form-message-attachment"  class="nl">Geef indien mogelijk een beeld van het probleem</label>
									<input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                               		<input type=file size=40 id="widget-entretien-form-message-attachment" name="widget-entretien-form-message-attachment">
                                </div>

                                <input type="text" class="hidden" name="widget-entretien-form-antispam" value="" />
                                <button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
								<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
								<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>
                            </form>
                            <script type="text/javascript">
                                jQuery("#widget-entretien-form").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });
													$('#entretien2').modal('toggle');

                                                } else {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'danger'
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });

                            </script>
					</div>
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	function initializeAssistance2() {
		document.getElementById('widget-assistance-form-message').value="";
		document.getElementById('widget-assistance-form-message-attachment').value="";

	}
	function initializeEntretien2(frameNumber) {
        if(!(frameNumber)){
            frameNumber="";
        }else{
            $('#widget-entretien-form-frame-number').prop('readonly', true);
        }
		document.getElementById('widget-entretien-form-frame-number').value=frameNumber;
		document.getElementById('widget-entretien-form-message').value="";
		document.getElementById('widget-entretien-form-message-attachment').value="";

	}
</script>




<div class="loader"><!-- Place at bottom of page --></div>

		<!-- FOOTER -->
		<footer class="background-dark text-grey" id="footer">
    <div class="footer-content">
        <div class="container">
            <div class="row text-center">
                <div class="copyright-text text-center"> &copy; 2017 KAMEO Bikes
                </div>
                <div class="social-icons center">
							<ul>
								<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>

								<li class="social-instagram"><a href="https://www.instagram.com/kameobikes/" target="_blank"><i class="fa fa-instagram"></i></a></li>
							</ul>
						</div>
            </div>
        </div>
    </div>
</footer>
		<!-- END: FOOTER -->

	</div>
	<!-- END: WRAPPER -->
	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>
<script type="text/javascript">
	displayLanguage();
</script>

</body>
<?php
ob_end_flush();
?>

</html>
