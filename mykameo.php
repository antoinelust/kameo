<?php
ob_start();
session_start();
$user=isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL;
$user_ID = isset($_SESSION['ID']) ? $_SESSION['ID'] : NULL;
include 'include/header5.php';
include 'include/environment.php';


if($user==NULL){
  $connected=false;
}else{
  $connected=true;
}

$langue=isset($_SESSION['langue']) ? $_SESSION['langue'] : 'fr';
include 'include/activitylog.php';
?>


<!-- Language management -->
<script type="text/javascript" src="js/language.js"></script>
<script type="text/javascript" src="./js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript" src="./node_modules/chart.js/dist/Chart.js" charset="UTF-8"></script>
<script type="text/javascript" src="js/feedback_management.js"></script>
<script type="text/javascript" src="js/bike_management.js"></script>
<script type="text/javascript" src="js/box_management.js"></script>
<script type="text/javascript" src="js/booking_management.js"></script>
<script type="text/javascript" src="js/tasks_management.js"></script>
<script type="text/javascript" src="js/contracts_management.js"></script>
<script type="text/javascript" src="js/search_module.js"></script>
<script type="text/javascript" src="js/bills_management.js"></script>
<script type="text/javascript" src="js/company_management.js"></script>
<script type="text/javascript" src="js/maintenance_management.js"></script>
<script type="text/javascript" src="js/notifications.js"></script>
<script type="text/javascript" src="js/addons/datatables.min.js"></script>
<script type="text/javascript" src="js/datatable_default.js"></script>
<script type="text/javascript" src="js/global_functions.js"></script>
<script type="text/javascript" src="js/dashboard_management.js"></script>
<script type="text/javascript" src="js/initialize_counters.js"></script>
<script src="js/OpenLayers/OpenLayers.js"></script>



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

const ENVIRONMENT = "<?php echo ENVIRONMENT; ?>";

var email="<?php echo $user; ?>";
var langue= "<?php echo $_SESSION['langue']; ?>";
var user_ID = "<?php echo $user_ID; ?>";

var color=Chart.helpers.color;

//id de la compagnie selectionnée si il y en a une sélectionnée
var companyId;

//varibles des charts chartJS
var myChart;
var myChart2;
var myChart3;

var nbContacts;

window.addEventListener("DOMContentLoaded", function(event) {
    
$( ".fleetmanager" ).click(function() {
      
    initializeFields();
    hideResearch();
    list_errors();
    initialize_task_owner_sales_selection();
    get_company_conditions();
    list_condition();
    initialize_counters();
    
    var tempDate=new Date();
    $(".form_date_end_client").data("datetimepicker").setDate(tempDate);
    tempDate.setMonth(tempDate.getMonth()-6);
    $(".form_date_start_client").data("datetimepicker").setDate(tempDate);
    list_maintenances();        
});

$( ".reservations" ).click(function() {
    hideResearch();      
    getHistoricBookings(email);
});




var tempDate=new Date();
$(".form_date_end").data("datetimepicker").setDate(tempDate);
tempDate.setMonth(tempDate.getMonth()-1);
$(".form_date_start").data("datetimepicker").setDate(tempDate);

});


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
          var selected ="";
          if (response.company[i].internalReference == "KAMEO") {
            selected ="selected";
          }
          $('#widget-bikeManagement-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>");
          $('#widget-updateAction-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>");
          $('#widget-taskManagement-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\" "+selected+">"+response.company[i].companyName+"<br>");
          $('#widget-boxManagement-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>");
          i++;
        }
        
      }
    }
  })
    
    
  $.ajax({
    url: 'include/initialize_fields.php',
    type: 'get',
    data: {type: "ownerField"},
    success: function(response){
        if(response.response == 'error') {
            console.log(response.message);
        }
        if(response.response == 'success'){
            var i=0;
            $('#widget-taskManagement-form select[name=owner]')
                .find('option')
                .remove()
                .end()
            ;
            $('.taskOwnerSelection')
                .find('option')
                .remove()
                .end()
            ;

            $('.taskOwnerSelection2')
                .find('option')
                .remove()
                .end()
            ;

            
            $('.taskOwnerSelection').append("<option value='*'>Tous<br>");
            $('.taskOwnerSelection2').append("<option value='*'>Tous<br>");
            
            
            $('#widget-taskManagement-form select[name=owner]').append("<option value='*'>Tous<br>");

            var i=0;
            while (i < response.ownerNumber){
                $('#widget-taskManagement-form select[name=owner]').append("<option value="+response.owner[i].email+">"+response.owner[i].firstName+" "+response.owner[i].name+"<br>");
                $('.taskOwnerSelection').append("<option value="+response.owner[i].email+">"+response.owner[i].firstName+" "+response.owner[i].name+"<br>");
                $('.taskOwnerSelection2').append("<option value="+response.owner[i].email+">"+response.owner[i].firstName+" "+response.owner[i].name+"<br>");
                i++;                
            }

            }
        }
    })    
}

function taskFilter(e){
  list_tasks('*', $('.taskOwnerSelection').val(),'<?php echo $user ?>');

}

function billFilter(e){
  document.getElementsByClassName('billSelectionText')[0].innerHTML=e;
  get_bills_listing('*', '*', '*', '*', email);

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
              label: 'Entreprises pas intéressées',
              borderColor: "#99111C",
              backgroundColor: "#f6856f",
              data: response.companiesNotInterested
            },{
              label: 'Entreprises en contact',
              borderColor: "#333333",
              backgroundColor: "#fcdb76",
              data: response.companiesContact
            },{
              label: 'Entreprises sous offre',
              borderColor: "#333333",
              backgroundColor: "#b6db4d",
              data: response.companiesOffer
            },{
              label: 'Entreprises sous offre signée',
              borderColor: "#333333",
              backgroundColor: "#96c220",
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
            var temp="<table class=\"table table-condensed\" id=\"portfolioBikeListing\"><h4 class=\"fr-inline text-green\">Vélos du catalogue:</h4><h4 class=\"en-inline text-green\">Portfolio bikes:</h4><h4 class=\"nl-inline text-green\">Portfolio bikes:</h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addPortfolioBike\" data-toggle=\"modal\" onclick=\"initializeCreatePortfolioBike()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un vélo</span></a><thead><tr><th>ID</th><th><span class=\"fr-inline\">Marque</span><span class=\"en-inline\">Brand</span><span class=\"nl-inline\">Brand</span></th><th><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th><span class=\"fr-inline\">Utilisation</span><span class=\"en-inline\">Use</span><span class=\"nl-inline\">Use</span></th><th><span class=\"fr-inline\">Electrique ?</span><span class=\"en-inline\">Electric</span><span class=\"nl-inline\">Electric</span></th><th><span class=\"fr-inline\">Cadre</span><span class=\"en-inline\">Frame</span><span class=\"nl-inline\">Frame</span></th><th><span class=\"fr-inline\">Prix</span><span class=\"en-inline\">Price</span><span class=\"nl-inline\">Price</span></th><th>Afficher</th><th></th></tr></thead><tbody>";
            dest=dest.concat(temp);

            while(i<response.bikeNumber){
                var temp="<tr><td>"+response.bike[i].ID+"</td><td>"+response.bike[i].brand+"</td><td>"+response.bike[i].model+"</td><td>"+response.bike[i].utilisation+"</td><td>"+response.bike[i].electric+"</td><td>"+response.bike[i].frameType+"</td><td>"+Math.round(response.bike[i].price)+" €</td><td>"+response.bike[i].display+"<td><a href=\"#\" class=\"text-green updatePortfolioClick\" onclick=\"initializeUpdatePortfolioBike('"+response.bike[i].ID+"')\" data-target=\"#updatePortfolioBike\" data-toggle=\"modal\">Mettre à jour </a></td></tr>";
                dest=dest.concat(temp);
                i++;
            }
            document.getElementById('portfolioBikesListing').innerHTML=dest.concat("</tbody>");

            displayLanguage();
            $('#portfolioBikeListing').DataTable({
                "paging": false
            });
          
          
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

        document.getElementsByClassName("bikeCatalogImage")[0].src="images_bikes/"+response.brand.toLowerCase().replace(/ /g, '-')+"_"+response.model.toLowerCase().replace(/ /g, '-')+"_"+response.frameType.toLowerCase()+".jpg";
        document.getElementsByClassName("bikeCatalogImageMini")[0].src="images_bikes/"+response.brand.toLowerCase().replace(/ /g, '-')+"_"+response.model.toLowerCase().replace(/ /g, '-')+"_"+response.frameType.toLowerCase()+"_mini.jpg";
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


</script>
<?php

if($connected){

  include 'include/connexion.php';
  $sql = "select aa.EMAIL, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY, bb.TYPE from customer_referential aa, customer_bike_access bb where aa.EMAIL='$user' and aa.EMAIL=bb.EMAIL LIMIT 1";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);    
  if ($row['TYPE']=="partage"){
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
            var temp="<tr><td>"+response.user[i].name+"</td><td>"+response.user[i].firstName+"</td><td>"+response.user[i].email+"</td><td>"+status+"</td><td><a  data-target=\"#updateUserInformation\" name=\""+response.user[i].email+"\" data-toggle=\"modal\" class=\"text-green\" href=\"#\" onclick=\"update_user_information('"+response.user[i].email+"')\">Mettre à jour</a></td></tr>";
            dest=dest.concat(temp);

            i++;
          }
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
            document.getElementById('search-bikes-form-intake-hour').addEventListener('change', function () { update_deposit_form()}, false);
            document.getElementsByClassName('clientBikesManagerClick')[0].addEventListener('click', function() { get_bikes_listing()}, false);    
            document.getElementsByClassName('usersManagerClick')[0].addEventListener('click', function() { get_users_listing()}, false);    
            document.getElementsByClassName('reservationlisting')[0].addEventListener('click', function () { reservation_listing()}, false);
            document.getElementsByClassName('portfolioManagerClick')[0].addEventListener('click', function() { listPortfolioBikes()}, false);
              
            $('.clientManagerClick').click(function(){
                get_company_listing('*');
                generateCompaniesGraphic($('.form_date_start_client').data("datetimepicker").getDate(), $('.form_date_end_client').data("datetimepicker").getDate());
            });
              
              
              
              
            document.getElementsByClassName('boxManagerClick')[0].addEventListener('click', function() { list_boxes('*')}, false);
              
            $('.tasksManagerClick').click(function(){
                list_tasks('*', $('.taskOwnerSelection').val(), '<?php echo $user ?>');                
                generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val());
            });
            $('#offerManagerClick').click(function(){
                list_contracts_offers('*');                
            });
            
            document.getElementsByClassName('feedbackManagerClick')[0].addEventListener('click', function() {list_feedbacks()});
            document.getElementsByClassName('taskOwnerSelection')[0].addEventListener('change', function() { taskFilter()}, false);
            document.getElementsByClassName('taskOwnerSelection2')[0].addEventListener('change', function() { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);
            document.getElementsByClassName('numberOfDays')[0].addEventListener('change', function() { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);
            document.getElementsByClassName('maintenanceManagementClick')[0].addEventListener('click', function() { list_maintenances()}, false);
              
              
            if(email=='julien@kameobikes.com' || email=='antoine@kameobikes.com' || email=='thibaut@kameobikes.com' || email=='pierre-yves@kameobikes.com'){
                document.getElementsByClassName('billsManagerClick')[0].addEventListener('click', function() {get_bills_listing('*', '*', '*', '*', email)});
                document.getElementById('cashFlowManagement').classList.remove("hidden");
                document.getElementById('billsManagement').classList.remove("hidden");
                $('.billsTitle').removeClass("hidden");
            }
              
              

            var classname = document.getElementsByClassName('administrationKameo');
            for (var i = 0; i < classname.length; i++) {
              classname[i].classList.remove("hidden");
            }

            document.getElementById('clientManagement').classList.remove("hidden");
            document.getElementById('portfolioManagement').classList.remove("hidden");
            document.getElementById('bikesManagement').classList.remove("hidden");
            document.getElementById('boxesManagement').classList.remove("hidden");
            document.getElementById('tasksManagement').classList.remove("hidden");
            document.getElementById('feedbacksManagement').classList.remove("hidden");
            document.getElementById('maintenanceManagement').classList.remove("hidden");
            document.getElementById('dashBoardManagement').classList.remove("hidden");
          }else if(response.companyConditions.administrator=="Y"){
              document.getElementsByClassName('usersManagerClick')[0].addEventListener('click', function() { get_users_listing()}, false);    
              document.getElementsByClassName('clientBikesManagerClick')[0].addEventListener('click', function() { get_bikes_listing()}, false);    
              document.getElementsByClassName('reservationlisting')[0].addEventListener('click', function () { reservation_listing()}, false);
              
              $('.billsTitle').removeClass("hidden");
              document.getElementById('billsManagement').classList.remove("hidden");
              document.getElementsByClassName('billsManagerClick')[0].addEventListener('click', function() {get_bills_listing('*', '*', '*', '*', email)});
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

    var temp="<input type=\"checkbox\" name=\"intakeBookingMonday\" value=\"\">Lundi<br><input type=\"checkbox\" name=\"intakeBookingTuesday\" value=\"\">Mardi<br><input type=\"checkbox\" name=\"intakeBookingWednesday\" value=\"\">Mercredi<br><input type=\"checkbox\" name=\"intakeBookingThursday\" value=\"\">Jeudi<br><input type=\"checkbox\" name=\"intakeBookingFriday\" value=\"\">Vendredi<br><input type=\"checkbox\" name=\"intakeBookingSaturday\" value=\"\">Samedi<br><input type=\"checkbox\" name=\"intakeBookingSunday\" value=\"\">Dimanche<br>";
    var temp2="<input type=\"checkbox\" name=\"depositBookingMonday\" value=\"\">Lundi<br><input type=\"checkbox\" name=\"depositBookingTuesday\" value=\"\">Mardi<br><input type=\"checkbox\" name=\"depositBookingWednesday\" value=\"\">Mercredi<br><input type=\"checkbox\" name=\"depositBookingThursday\" value=\"\">Jeudi<br><input type=\"checkbox\" name=\"depositBookingFriday\" value=\"\">Vendredi<br><input type=\"checkbox\" name=\"depositBookingSaturday\" value=\"\">Samedi<br><input type=\"checkbox\" name=\"depositBookingSunday\" value=\"\">Dimanche<br>";
    document.getElementsByClassName('intakeBookingDays')[0].innerHTML = temp;
    document.getElementsByClassName('depositBookingDays')[0].innerHTML = temp2;
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
                temp="<input type=\"checkbox\" checked name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\"> "+response.building[i].descriptionFR+"<br>";

              }
              else if(response.building[i].access==false){
                temp="<input type=\"checkbox\" name=\"buildingAccess[]\" value=\""+response.building[i].buildingCode+"\"> "+response.building[i].descriptionFR+"<br>";

              }
              dest=dest.concat(temp);
              i++;
            }
            document.getElementById('buildingUpdateUser').innerHTML = dest;

            var i=0;
            var dest="<h4>Accès aux vélos</h4>";
            while(i<response.bikeNumber){
              if(response.bike[i].access==true){
                temp="<input type=\"checkbox\" checked name=\"bikeAccess[]\" value=\""+response.bike[i].bikeID+"\"> "+response.bike[i].bikeID+" - "+response.bike[i].model+"<br>";

              }
              else if(response.bike[i].access==false){
                temp="<input type=\"checkbox\" name=\"bikeAccess[]\" value=\""+response.bike[i].bikeID+"\"> "+response.bike[i].bikeID+" - "+response.bike[i].model+"<br>";

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

  function get_meteo(date, address){
    return $.ajax({
      url: 'include/meteo.php',
      type: 'post',
      data: { "date": date, "address": address}
    })
  }

  function get_travel_time(date, address_start, address_end){
      
    return $.ajax({
      url: 'include/get_directions.php',
      type: 'post',
      data: {"date": date, "address_start": address_start, "address_end": address_end},
      success: function(response){
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
    $('#companyHiddenOffer').val(company);

    $('#widget-offerManagement-form select[name=type]').val("leasing");
    $('#widget-offerManagement-form input[name=action]').val("add");
    $('#widget-offerManagement-form input').attr("readonly", false);
    $('#widget-offerManagement-form textarea').attr("readonly", false);
    $('#widget-offerManagement-form select').attr("readonly", false);
    document.getElementById('widget-offerManagement-form').reset();

  }
  function edit_contact(contact){
    return $.ajax({
      url: 'include/edit_company_contact.php',
      method: 'post',
      data: {
        'id': $(contact).find('.contactIdHidden').val(),
        'contactEmail':$(contact).find('.emailContact').val(),
        'firstName': $(contact).find('.firstName').val(),
        'lastName': $(contact).find('.lastName').val(),
        'phone': $(contact).find('.phone').val(),
        'function': $(contact).find('.fonction').val(),
        'bikesStats': $(contact).find('.bikesStats').prop('checked'),
        'companyId': $('#companyIdHidden').val(),
        'email': email

      },
      success: function(response){
      }
    });
  }

  function delete_contact(contact, id){
    return $.ajax({
      url: 'include/delete_company_contact.php',
      method: 'post',
      data: {
        'id' : id
      },
      success: function(response){
      }
    });
  }
  function get_company_contacts(ID){
    $.ajax({
      url: 'include/get_company_contact.php',
      method: 'post',
      data: { 'ID' : ID },
      success: function(response){
        initialize_company_contacts();
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
        nbContacts = response.length;
        for (var i = 0; i < response.length; i++) {
          var contactId = (response[i].contactId != undefined) ? response[i].contactId : '';
          var email = (response[i].emailContact != undefined) ? response[i].emailContact : '';
          var lastName = (response[i].lastNameContact != undefined) ? response[i].lastNameContact : '';
          var firstName = (response[i].firstNameContact != undefined) ? response[i].firstNameContact : '';
          var phone = (response[i].phone != undefined) ? response[i].phone : '';
          var fonction = (response[i].fonction != undefined) ? response[i].fonction : '';
          var bikesStatsChecked = "";
          if (response[i].bikesStats == "Y") {
            bikesStatsChecked = "checked";
          }
          contactContent += `
          <tr class="form-group">
          <td>
          <input type="text" class="form-control required emailContact" readonly="true"  name="contactEmail`+response[i].contactId+`" id="contactEmail`+response[i].contactId+`" value="`+email+`" required/>
          </td>
          <td>
          <input type="text" class="form-control required lastName" readonly="true"  name="contactNom`+response[i].contactId+`" id="contactNom`+response[i].contactId+`" value="`+lastName+`" required/>
          </td>
          <td>
          <input type="text" class="form-control required firstName" readonly="true" name="contactPrenom`+response[i].contactId+`" id="contactPrenom`+response[i].contactId+`" value="`+firstName+`" required/>
          </td>
          <td>
          <input type="tel" class="form-control phone" readonly="true"  name="contactPhone`+response[i].contactId+`" id="contactPhone`+response[i].contactId+`" value="`+phone+`"/>
          </td>
          <td>
          <input type="text" class="form-control fonction" readonly="true"  name="contactFunction`+response[i].contactId+`" id="contactFunction`+response[i].contactId+`" value="`+fonction+`"/>
          </td>
          <td>
          <input type="checkbox" class="form-control bikesStats" readonly="true"  name="contactBikesStats`+response[i].contactId+`" id="contactBikesStats`+response[i].contactId+`" value="bikesStats" `+bikesStatsChecked+`/>
          </td>
          <td>
          <button class="modify button small green button-3d rounded icon-right glyphicon glyphicon-pencil" type="button"></button>
          </td>
          <td>
          <button class="delete button small red button-3d rounded icon-right glyphicon glyphicon-remove" type="button"></button>
          </td>
          <input type="hidden" class="contactIdHidden" name="contactId`+response[i].contactId+`" id="contactId`+response[i].contactId+`" value="`+contactId+`" />
          </tr>`;
        }
        contactContent += "</tbody></table>";
        $('.clientContactZone').append(contactContent);
      }
    });

  }

  function initialize_company_contacts (){
    $('.clientContactZone').html('');
  }
  function remove_contact_form(removeContent = false){
    //retrait de l ajout
    $('.contactAddIteration').fadeOut();
    //ajout du statut disabled des input
    $('.contactAddIteration').find('input').each(function(){
      $(this).prop('disabled', true);
      if (removeContent) {
        $(this).val('');
      }
    });
    $('.removeContact').addClass('glyphicon-plus').addClass('green').addClass('addContact').removeClass('glyphicon-minus').removeClass('red').removeClass('removeContact');
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
          $('#widget-addActionCompany-form select[name=owner]').val('julien@kameobikes.com');

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
    $.ajax({
      url: 'include/logout.php',
      method: 'post',
      data: {},
      //si le tableau de session est vide, on est bien déconnecté
      success: function(response){
        if (response.length == 0) {
          window.location.reload(true);
        }
      }
    });

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
              <div class="row" style="position: relative;">
                <h2 class="col-sm-8">MY KAMEO</h2>
                <div class="notificationHeading">
                  <div class="col-sm-4" style="padding:0;">
                    <span class="pointerClick notificationsClick">
                      <i class="fa fa-2x fa-bell-o text-green" aria-hidden="true"></i>
                      <span style="font-size:20px;">0</span> &nbsp;
                    </span>
                     <a href="#" class="text-green hideNotifications" style="display:none">Masquer</a>
                  </div>
                  <div class="notificationsBlock row" style="display:none; overflow:hidden;">
                  </div>
                </div>
              </div>

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
                      var email="<?php echo $user; ?>";
                      loadClientConditions()
                      .done(function(response){
                        constructSearchForm(response.clientConditions.bookingDays, response.clientConditions.bookingLength, response.clientConditions.administrator, response.clientConditions.assistance, response.clientConditions.hourStartIntakeBooking, response.clientConditions.hourEndIntakeBooking, response.clientConditions.hourStartDepositBooking, response.clientConditions.hourEndDepositBooking, response.clientConditions.mondayIntake, response.clientConditions.tuesdayIntake, response.clientConditions.wednesdayIntake, response.clientConditions.thursdayIntake, response.clientConditions.fridayIntake, response.clientConditions.saturdayIntake, response.clientConditions.sundayIntake, response.clientConditions.mondayDeposit, response.clientConditions.tuesdayDeposit, response.clientConditions.wednesdayDeposit, response.clientConditions.thursdayDeposit, response.clientConditions.fridayDeposit, response.clientConditions.saturdayDeposit, response.clientConditions.sundayDeposit, response.clientConditions.maxBookingsPerYear, response.clientConditions.maxBookingsPerMonth, email);
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

                                //uniquement en dev
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

                                    date= new Date(text.dateStart);

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
                                    get_meteo(text.dateStart, addressStart)
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
                                          
                                          
                                        get_travel_time(text.dateStart, addressStart, addressEnd)
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

                                  var bikeID=text.bike[i].bikeID;
                                  var bikeFrameNumber=text.bike[i].frameNumber;
                                  var bikeType=text.bike[i].typeDescription;

                                  if(text.bike[i].brand && text.bike[i].model && text.bike[i].size){
                                    var title= "Marque : "+text.bike[i].brand+" <br/>Modèle : "+text.bike[i].model+" <br/>Taille : "+text.bike[i].size;
                                  }else{
                                    var title=bikeFrameNumber;
                                  }


                                  var codeVeloTemporaire ="<div class=\"col-md-4\">\
                                  <div class=\"featured-box\">\
                                  <div class=\"effect social-links\"> <img src=\"images_bikes/"+bikeID+".jpg\" alt=\"image\" />\
                                  <div class=\"image-box-content\">\
                                  <p> <a href=\"images_bikes/"+bikeID+".jpg\" data-lightbox-type=\"image\" title=\"\"><i class=\"fa fa-expand\"></i></a> </p>\
                                  </div>\
                                  </div>\
                                  </div>\
                                  </div>\
                                  <div class=\"col-md-4\">\
                                  <h4>"+ bikeType +"</h4>\
                                  <p class=\"subtitle\">"+ title +"</p>\
                                  </div>\
                                  <div class=\"col-md-2\">\
                                  <a class=\"button large green button-3d rounded icon-left\" name=\""+bikeID+"\" id=\"fr\" data-target=\"#resume\" data-toggle=\"modal\" href=\"#\" onclick=\"bookBike(this.name)\"><span>Réserver</span></a>\
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

                                document.getElementById('widget-new-booking-date-start').value = text.dateStart;
                                document.getElementById('widget-new-booking-date-end').value = text.dateEnd;
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
                      function bookBike(ID)
                      {
                        $('#widget-new-booking input[name=bikeID]').val(ID);
                        document.getElementById("resumeBikeImage").src="images_bikes/"+ID+".jpg";

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


                    <div class="tab-pane" id="fleetmanager">

                      <tbody>
                          
                          

                        <h4 class="fr">Votre flotte</h4><br/><br />

                        <div class="row">
                          <div class="col-md-4">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#BikesListing" class="clientBikesManagerClick" href="#" ><i class="fa fa-bicycle"></i></a> </div>
                              <div class="counter bold" id="counterBike" style="color:#3cb395"></div>
                              <p>Nombre de vélos</p>
                            </div>
                          </div>

                          <div class="seperator seperator-small visible-xs"><br/><br/></div>

                          <div class="col-md-4">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#usersListing" class="usersManagerClick" href="#" ><i class="fa fa-users"></i></a> </div>
                              <div class="counter bold" id="counterUsers" style="color:#3cb395"></div>
                              <p>Nombre d'utilisateurs</p>
                            </div>
                          </div>

                          <div class="seperator seperator-small visible-xs"><br/><br/></div>

                          <div class="col-md-4">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#ReservationsListing" href="#"><i class="fa fa-calendar-plus-o reservationlisting"></i></a></div>
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

                        <h4 class="fr hidden administrationKameo">Administration Kameo</h4>
                        <h4 class="en hidden administrationKameo">Kameo administration</h4>
                        <h4 class="en hidden administrationKameo">Kameo administration</h4><br/><br />
                        <div class="row">
                          <div class="col-md-4 hidden" id="clientManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#companyListing" href="#" class="clientManagerClick" ><i class="fa fa-users"></i></a> </div>
                              <div class="counter bold" id="counterClients" style="color:#3cb395"></div>
                              <p>Gérer les clients</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="portfolioManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#portfolioManager" href="#" class="portfolioManagerClick"><i class="fa fa-book"></i></a> </div>
                              <div class="counter bold" id='counterBikePortfolio' style="color:#3cb395"></div>
                              <p>Gérer le catalogue</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="bikesManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#BikesListingAdmin" href="#" class="bikeManagerClick"><i class="fa fa-bicycle"></i></a></div>
                              <div class="counter bold" id="counterBikeAdmin" style="color:#3cb395"></div>
                              <p>Gérer les vélos</p>
                            </div>
                          </div>
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
                                <a data-toggle="modal" data-target="#offersListing" href="#" id="offerManagerClick"><i class="fa fa-money"></i></a>
                              </div>
                              <div class="counter bold" id="cashFlowSpan" style="color:#3cb395"></div>
                              <p>Vue sur le cash-flow</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="feedbacksManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#feedbacksListing" href="#" class="feedbackManagerClick"><i class="fa fa-comments"></i></a>
                              </div>
                              <div class="counter bold" id="counterFeedbacks" style="color:#3cb395"></div>
                              <p>Vue sur les feedbacks</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="maintenanceManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#maintenanceListing" href="#" class="maintenanceManagementClick"><i class="fa fa-wrench"></i></a>
                              </div>
                              <div class="counter bold" id="counterMaintenance" style="color:#3cb395"></div>
                              <div class="counter bold" id="counterMaintenance2" style="color:#3cb395"></div>
                              <p>Vue sur les entretiens</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="dashBoardManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" class="dashboardManagementClick" data-target="#dashboard" href="#" ><i class="fa fa-dashboard"></i></a> </div>
                              <div class="counter bold" id='errorCounter' style="color:#3cb395"></div>
                              <p>Dashboard</p>
                            </div>
                          </div>

                        </div>


                        <div class="separator hidden kameo"></div>

                        <h4 class="fr billsTitle hidden">Factures</h4>
                        <h4 class="en billsTitle hidden">Billing</h4>
                        <h4 class="nl billsTitle hidden">Billing</h4><br/><br />

                        <div class="row">
                          <div class="col-md-4 hidden" id="billsManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite"><a data-toggle="modal" data-target="#billingListing" href="#" class="billsManagerClick"><i class="fa fa-folder-open-o"></i></a> </div>
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
                                <h4 class="text-green fr">Informations relatives à la réservation</h4>
                                <span id="bookingInformation"></span>
                                <br/><br/>
                            </div>
                            <div class="col-sm-12">
                                <h4 class="text-green fr">Vélo</h4>
                                <span id="bookingInformationBike"></span>
                                <img id='imageNextBooking' class="img-rounded img-responsive" alt="Responsive image">
                            </div>
                            
                            
                            <div class="separator"></div>
                            
                            <div class="col-sm-12">
                                <h4 class="fr text-green">Personne avant vous:</h4>
                                <h4 class="nl text-green">Persoon voor jou:</h4>
                                <h4 class="en text-green">Person before you:</h4>
                                <span id="futureBookingBefore"></span>
                            </div>
                            
                            <div class="separator"></div>                              
                            
                            <div class="col-sm-12">
                                <h4 class="fr text-green">Personne après vous:</h4>
                                <h4 class="nl text-green">Persoon na jou:</h4>
                                <h4 class="en text-green">Person after you:</h4>
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



                <div id="velos" style="display: none;"></div>
              </div>
              <?php

            }
            else
            {

                include 'include/connexion.php';
                $sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY, bb.CONTRACT_START, bb.CONTRACT_END, dd.BRAND, dd.MODEL, dd.FRAME_TYPE, cc.BIKE_NUMBER from customer_referential aa, customer_bikes bb, customer_bike_access cc, bike_catalog dd where aa.EMAIL='$user' and aa.EMAIL=cc.EMAIL and cc.BIKE_NUMBER=bb.FRAME_NUMBER and bb.TYPE=dd.ID";
                                
                
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                                
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $contractNumber='KAMEO BIKES';
                $contractStart=$row['CONTRACT_START'];
                $contractEnd=$row['CONTRACT_END'];

              ?>

              <div id="travel_information_2" class="hidden">
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

              <div id="travel_information_2_error" class="hidden">
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

              <div id="travel_information_2_loading">
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


              <img src="images_bikes/<?php echo $row['BIKE_NUMBER']; ?>.jpg" class="img-responsive img-rounded center" alt="Image of Bike">

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
                      <td class="fr-cell"><?php echo $row["BRAND"]." ".$row["MODEL"] ?></td>
                      <td class="en-cell"><?php echo $row["BRAND"]." ".$row["MODEL"] ?></td>
                      <td class="nl-cell"><?php echo $row["BRAND"]." ".$row["MODEL"] ?></td>
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
                  $('#travel_information_2_loading').removeClass("hidden");
                  $('#travel_information_2_error').addClass("hidden");
                  $('#travel_information_2').addClass("hidden");

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

                  var timestamp=new Date();
                  timestamp=(timestamp.toISOString().split('T')[0]+" "+timestamp.toISOString().split('T')[1]).split('.')[0];
                  get_meteo(timestamp, addressDomicile)
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

                      get_travel_time("now", addressDomicile, addressTravail)
                      .done(function(response){
                          if(response.duration_walking === undefined || response.duration_bike === undefined || response.duration_car === undefined){
                            $('#travel_information_2_loading').addClass("hidden")
                            $('#travel_information_2_error').removeClass("hidden")
                            $('#travel_information_2').addClass("hidden")                        
                          }else{
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
                                $('#travel_information_2_loading').addClass("hidden")
                                $('#travel_information_2_error').addClass("hidden")
                                $('#travel_information_2').removeClass("hidden")
                            };
                            img1.onerror = function() {
                                $('#travel_information_2_loading').addClass("hidden")
                                $('#travel_information_2_error').removeClass("hidden")
                                $('#travel_information_2').addClass("hidden")                        
                            };
                            img1.src=image;
                          }
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
                  success: function(response){
                    if (response.response == 'error') {
                      console.log(response.message);
                    }else{
                        var distance_bike= response.distance_bike;
                        var total_distance= (distance_bike * 2 * count)/1000;
                        document.getElementById('count_trips').innerHTML= count;
                        if(distance_bike !== undefined){
                            document.getElementById('total_trips').innerHTML= Math.round(total_distance)+" kms";
                        }else{
                            document.getElementById('total_trips').innerHTML=  "Veuillez renseigner votre adresse";
                        }
                    }
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
          <a href="docs/cgvfr.pdf" target="_blank" title="Pdf" class="en">Terms and Conditions</a>
          <a href="docs/cgvfr.pdf" target="_blank" title="Pdf" class="nl">Algemene voorwaarden</a>
          <br>
          <a href="docs/KAMEO-BikePolicy.pdf" target="_blank" title="Pdf">Bike policy</a>
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
                            window.location.href = "<?php echo "mykameo.php?feedback=".$_GET['feedback']; ?>";
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
            <h4 class=" text-green fr">Résumé de votre réservation</h3>
            <h3 class="text-green en">Resume</h3>
            <h3 class="text-green nl">Geresumeerd</h3>

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
                  <h4>Vélo: </h4>
                    <img id='resumeBikeImage' class="img-rounded img-responsive" alt="Responsive image">
                </div>
                <form id="widget-new-booking" class="form-transparent-grey" action="include/new_booking.php" role="form" method="post">
                    <input id="widget-new-booking-building-start" name="widget-new-booking-building-start" type="hidden">
                    <input id="widget-new-booking-building-end" name="widget-new-booking-building-end" type="hidden">
                    <input name="bikeID" type="hidden">
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
  <div class="modal-dialog modal-lg" style= "width: 1250px">
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

<div class="modal fade" id="bikePosition" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
  <div class="modal-dialog modal-lg" style= "width: 1250px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div data-example-id="contextual-table" class="bs-example">
          <h4 class="text-green">Position du vélo</h4>
          <div id="demoMap" style="height:750px"></div>          
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
        
        

  <div data-example-id="contextual-table" class="bs-example">
    <span id="contractsListingSpan"></span>
  </div>

        

  <div class="separator"></div>

  <div data-example-id="contextual-table" class="bs-example">
    <span id="offersListingSpan"></span>
  </div>

  <div class="separator"></div>
        
        
    <h4 class="text-green">Vélos Vendus :</h4>
    <p>
      <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        Afficher
      </button>
    </p>        

    <div class="collapse" id="collapseExample">
      <div class="card card-body">
        <span id="soldBikesListingSpan"></span>
      </div>
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

<div class="modal fade" id="maintenanceListing" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>

      <div data-example-id="contextual-table" class="bs-example">
        <span id="maintenanceListingSpan"></span>
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
              <div class="col-sm-12">
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
                <span id="companyListingFilter" class="hidden">HEU_MAJ</span>
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
                
              <div data-example-id="contextual-table" class="bs-example billsToSendSpan hidden">
                  <h4 class="text-green">Factures à envoyer</h4>
                  <span id="billsToSendListing"></span>
              </div>
              <div class="separator billsToSendSpan hidden"></div>
                
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


        <div class="modal fade" id="dashboard" tabindex="9" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div id="tabs-05c" class="tabs color tabs radius">
             		 <h3 class="text-green">Dashboard</h3>
             		 
             		 <ul class="tabs-navigation">
						<li class="active"><a href="#" class="dashboardBikes">Vélos</a> </li>
						<li><a href="#" class="dashboardBills">Factures</a> </li>
						<li><a href="#" class="dashboardCompanies">Sociétés</a> </li>                         
						<li><a href="#" class="dashboardSells">Prospection commerciale</a> </li>
					</ul>
					<div class="tabs-content">
						<div class="tab-pane active" id="">
							<h4 class="text-green dashboardTitle">Erreurs à corriger - Vélos</h4>
							<span id="dashboardBodyBikes"></span>
                            <span id="dashboardBodyBills" style="display: none;"></span>
                            <span id="dashboardBodyCompanies" style="display: none;"></span>
                            
                            
                            <span id="dashboardBodySells" style="display: none;">
                            
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="dtp_input2" class="control-label">Date de début</label>
                                  <div class="input-group date form_date_start_sell col-md-12" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" value="" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                  </div>
                                  <input type="hidden" id="dtp_input2" value="" /><br/>
                                </div>
                              </div>

                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="dtp_input2" class="control-label">Date de fin</label>
                                  <div class="input-group date form_date_end_sell col-md-12" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" value="" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                  </div>
                                  <input type="hidden" id="dtp_input2" value="" /><br/>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <label for="taskOwnerSalesSelection">Filtrer sur Owner</label>
                                <select class="taskOwnerSalesSelection" name="taskOwnerSalesSelection">
                                </select>
                              </div>
                                
                                <span id='dashboardBodySellsTable'></span>
                            
                            </span>
                        </div>
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

        <script type="text/javascript">
            

            $('.form_date_start_sell').datetimepicker({
              language:  'fr',
              weekStart: 1,
              todayBtn:  1,
              autoclose: 1,
              todayHighlight: 1,
              startView: 2,
              minView: 2,
              forceParse: 0
            });

            $('.form_date_end_sell').datetimepicker({
              language:  'fr',
              weekStart: 1,
              todayBtn:  1,
              autoclose: 1,
              todayHighlight: 1,
              startView: 2,
              minView: 2,
              forceParse: 0
            });



            var tempDate=new Date();
            $(".form_date_end_sell").data("datetimepicker").setDate(tempDate);
            tempDate.setDate(tempDate.getDate()-7);
            $(".form_date_start_sell").data("datetimepicker").setDate(tempDate);
            
            $('.form_date_start_sell').change(function(){
                list_sales($('.taskOwnerSalesSelection').val(), $('.form_date_start_sell').data("datetimepicker").getDate(), $('.form_date_end_sell').data("datetimepicker").getDate())
            });
            $('.form_date_end_sell').change(function(){
                list_sales($('.taskOwnerSalesSelection').val(), $('.form_date_start_sell').data("datetimepicker").getDate(), $('.form_date_end_sell').data("datetimepicker").getDate())
            });
            $('.taskOwnerSalesSelection').change(function(){
                list_sales($('.taskOwnerSalesSelection').val(), $('.form_date_start_sell').data("datetimepicker").getDate(), $('.form_date_end_sell').data("datetimepicker").getDate())
            });
            

            
            $( ".dashboardBikes" ).click(function() {
                    $('#dashboardBodyBills').fadeOut();                    
                    $('#dashboardBodyBikes').fadeIn();
                    $('#dashboardBodySells').fadeOut();
                    $('#dashboardBodyCompanies').fadeOut();
                    $('.dashboardTitle').html("Erreurs à corriger - Vélos");
                });            
                $( ".dashboardBills" ).click(function() {
                    $('#dashboardBodyBikes').fadeOut();
                    $('#dashboardBodyBills').fadeIn();
                    $('#dashboardBodyCompanies').fadeOut();                    
                    $('#dashboardBodySells').fadeOut();
                    
                    $('.dashboardTitle').html("Erreurs à corriger - Factures");
                });            
                $( ".dashboardSells" ).click(function() {
                    $('#dashboardBodyBikes').fadeOut();
                    $('#dashboardBodyBills').fadeOut();
                    $('#dashboardBodyCompanies').fadeOut();                    
                    $('#dashboardBodySells').fadeIn();
                    $('.dashboardTitle').html("Suivi prospection commerciale");
                });            
                $( ".dashboardCompanies" ).click(function() {
                    $('#dashboardBodyBikes').fadeOut();
                    $('#dashboardBodyBills').fadeOut();
                    $('#dashboardBodyCompanies').fadeIn();                    
                    $('#dashboardBodySells').fadeOut();
                    $('.dashboardTitle').html("Erreurs à corriger - Sociétés");
                });            

            
            
        </script>

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
                              <option value="leasing">Location</option>
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
                              get_bills_listing('*', '*', '*', '*',email);
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
                            <option value="ANCIEN PROSPECT">Ancien prospect</option>
                            <option value="ANCIEN CLIENT">Ancien client</option>
                            <option value="NOT INTERESTED">Pas intéressé</option>
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
                          <label for="contactEmail"  class="fr">E-Mail</label>
                          <label for="contactEmail"  class="en">EMAIL</label>
                          <label for="contactEmail"  class="nl">EMAIL</label>
                          <input type="text" name="contactEmail" class="form-control required">
                        </div>

                        <div class="col-md-3">
                          <label for="firstName"  class="fr">Prénom</label>
                          <label for="firstName"  class="en">First Name</label>
                          <label for="firstName"  class="nl">First Name</label>
                          <input type="text" name="firstName" class="form-control required">
                        </div>
                        <div class="col-md-3">
                          <label for="lastName"  class="fr">Nom de Famille</label>
                          <label for="lastName"  class="en">Last Name</label>
                          <label for="lastName"  class="nl">Last Name</label>
                          <input type="text" name="lastName" class="form-control required">
                        </div>
                        <div class="col-md-3">
                          <label for="phone"  class="fr">Téléphone</label>
                          <label for="phone"  class="en">Phone</label>
                          <label for="phone"  class="nl">Phone</label>
                          <input type="text" name="phone" class="form-control">
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
                              initializeFields();
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
                    <p span class="bikeID"></p>
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
                          <option value="NOT INTERESTED">Pas intéressé</option>
                        </select>
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
                  <div class="separator"></div>
                  <div class="col-sm-12">
                    <h4 class="text-green">Informations relatives au contact</h4>
                  </div>
                  <div class="col-sm-12 contactAddButtons">
                    <button class="addContact button small green button-3d rounded icon-right glyphicon glyphicon-plus" type="button"></button>
                    <label for="addContact">Ajouter un contact</label>
                  </div>
                  <form class="contactAddIteration" style="display:none;" action="#">
                    <div class="col-md-3 form-group">
                      <label for="email_billing" class="fr"> Email : </label>
                      <input disabled type="text" class="form-control emailContact required" name="emailContact" placeholder="email" />
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
                      <button class="button small green button-3d rounded icon-right addCompanyContact" type="button">
                        <span class="fr-inline" style="display: inline;">
                          <i class="fa fa-plus"></i> Ajouter le contact
                        </span>
                      </button>
                    </div>
                    <div class="separator separator-small"></div>
                  </form>
                  <form class="clientContactZone" action="#">
                    <!--<div class="separator separator-small"></div>-->
                  </form>
                  <div class="separator"></div>

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

                  <div class="col-sm-12" id="clientBikes">
                    <h4 class="text-green">Vélos :</h4>
                    <span id="companyBikes"></span>
                  </div>
                    
                    <div class="col-sm-12" id="clientOrderedBikes">
                        <div class="separator"></div>
                        <h4 class="text-green">Vélos en commande:</h4>
                        <span id="companyBikesOrder"></span>
                    </div>

                  <div class="col-sm-12" id="clientBoxes">
                    <div class="separator"></div>                      
                    <h4 class="text-green">Bornes :</h4>
                    <p><span id="companyBoxes"></span></p>
                  </div>

                  <div class="col-sm-12" id="clientContracts">
                    <div class="separator"></div>                      
                    <h4 class="text-green">Contrats et Offres :</h4>
                    <p><span id="companyContracts"></span></p>
                  </div>

                  <div class="col-sm-12">
                    <div class="separator"></div>                      
                    <h4 class="text-green">Historique et actions :</h4>
                    <span id="action_company_log"></span>

                  </div>

                  <div class="col-sm-12" id="clientBuildings">
                    <div class="separator"></div>                      
                    <h4 class="text-green">Bâtiments:</h4>
                    <p><span id="companyBuildings"></span></p>
                  </div>

                  <div class="col-sm-12" id="clientusers">
                    <div class="separator"></div>                      
                    <h4 class="text-green">Utilisateurs:</h4>
                    <span id="companyUsers"></span>
                  </div>

                  <div class="col-sm-12" id="clientBills">
                    <div class="separator"></div>  
                    <h4 class="text-green">Factures:</h4>
                    <span id="companyBills"></span>
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


        <script type="text/javascript" src="js/add_company_contact.js"></script>
        <script type="text/javascript">
        var contactInfo  = [];
        var contactKeys = [];

        $('.clientContactZone').on('click','.modify', function(){
          $(this).removeClass('modify').addClass('validate').removeClass('glyphicon-pencil').addClass('glyphicon-ok');
          $(this).parents('tr').find('.delete').removeClass('delete').removeClass('red').addClass('white').addClass('annuler').removeClass('glyphicon-remove').addClass('glyphicon-repeat');
          $(this).parents('tr').find('input').each(function(){
            contactInfo.push($(this).val());
            contactKeys.push($(this).attr('id'));
            $(this).prop('readonly', false);
          });
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
          var that = $(this);
          $(this).parents('tr').find('input').each(function(){
            //verification de la validité des champs
            if (!$(this).valid()) {
              valid = false;
            }
          });
          if (valid) {
            edit_contact($(this).parents('tr')).done(function(response){
              $(that).parents('tr').find('.validate').removeClass('validate').addClass('modify').addClass('glyphicon-pencil').removeClass('glyphicon-ok');
              $(that).parents('tr').find('.annuler').removeClass('annuler').removeClass('white').addClass('delete').addClass('red').addClass('glyphicon-remove').removeClass('glyphicon-repeat');
              $(that).parents('tr').find('input').each(function(){
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
            });
          }

        });


        $('.clientContactZone').on('click', '.delete', function(){
          if(confirm('Êtes-vous sur de vouloir supprimer ce contact ? Cette action est irréversible.')){
            that = $(this);
            if( nbContacts > 1) {
              delete_contact($(this).parents('tr'), $(this).parents('tr').find('.contactIdHidden').val()).done(function(response){
                $(that).parents('tr').fadeOut(function(){
                  $(that).parents('tr').remove();
                  nbContacts--;
                });
              });
            }
            else{
              $.notify({
                message: "Impossible d'effectuer la suppression, il faut au minimum une personne de contact"
              }, {
                type: 'danger'
              });
            }

          }
        });
        </script>



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
                          <input type="password" name="password" class="form-control required hidden">
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
                              document.getElementById('widget-addUser-form').reset();
                                

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
                            <label for="channel"  class="fr">Canal d'acquisition</label>
                            <label for="channel"  class="en">Channel</label>
                            <label for="channel"  class="nl">Channel</label>
                            <select title="channel" class="form-control required selectpicker" name="channel">
                              <option value="telephone" selected>Téléphone</option>
                              <option value="salon">Contact après visite sur salon</option>
                              <option value="site">Contact sur site internet</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-12">
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

                    var oldChannelValue = $('#widget-taskManagement-form select[name=channel]').val();

                    $('body').on('change', '#widget-taskManagement-form select[name=channel]', function(){
                      if ($('#widget-taskManagement-form select[name=channel]').val() != null) {
                        oldChannelValue = $('#widget-taskManagement-form select[name=channel]').val();
                      }
                    });

                    $('#widget-taskManagement-form select[name=type]').change(function(){
                      if($('#widget-taskManagement-form select[name=type]').val()=="contact"){
                        $('#widget-taskManagement-form select[name=channel]').val(oldChannelValue);

                        $('#widget-taskManagement-form label[for=channel]').addClass("required");
                        $('#widget-taskManagement-form label[for=channel]').removeClass("hidden");

                        $('#widget-taskManagement-form select[name=channel]').addClass("required");
                        $('#widget-taskManagement-form select[name=channel]').removeClass("hidden");

                      }else{
                        $('#widget-taskManagement-form label[for=channel]').removeClass("required");
                        $('#widget-taskManagement-form label[for=channel]').addClass("hidden");

                        $('#widget-taskManagement-form select[name=channel]').addClass("hidden");
                        $('#widget-taskManagement-form select[name=channel]').removeClass("required");

                        $('#widget-taskManagement-form select[name=channel]').val(null);
                      }
                    });



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
                              list_tasks('*', $('.taskOwnerSelection').val(),'<?php echo $user ?>');
                              $('#taskManagement').modal('toggle');
                                
                              get_company_details($('#widget-companyDetails-form input[name=ID]').val(), email);   
                                
                                
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
                          <div class="col-md-4 spanNote">
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
                        <div class="col-md-12 textAreaComment">
                          <div class="col-md-12">
                            <label for="comment"  class="fr">Commentaire</label>
                            <label for="comment"  class="en">Comment</label>
                            <label for="comment"  class="nl">Comment</label>
                            <textarea class="form-control" rows="5" name="comment"></textarea>
                          </div>
                        </div>
                          
                        <input type='int' class="form-control required hidden" name="feedbackID">
                        <input type="text" name="action" class="form-control hidden" value="add">
                        <input type="text" name="user" class="form-control hidden" value="<?php echo $user; ?>">
                        <input type="hidden" name="notificationID" />
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
                              if ($('#feedbackManagement input[name=notificationID]').val() != -1) {
                                notification_set_as_read($('#feedbackManagement input[name=notificationID]').val());
                              }
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


        <div class="modal fade" id="maintenanceManagementItem" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none; overflow-y: auto !important;">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-sm-12">
                    <h4 class="fr text-green maintenanceManagementTitle">Éditer un entretien</h4>

                    <form id="widget-maintenanceManagement-form" action="include/edit_maintenance.php" role="form" method="post">

                      <div class="form-group col-sm-12">
                        <div class="col-md-12">

                          <div class="col-md-4">
                            <label for="ID">ID</label>
                            <input type='int' title="ID" class="form-control required" name="ID" readonly='readonly'>
                          </div>
                          <div class="col-md-4">
                            <label for="utilisateur">Vélo</label>
                            <input type="text" title="velo" class="form-control required" name="velo" readonly='readonly'>
                          </div>
                          <div class="col-md-4">
                            <label for="utilisateur">Société</label>
                            <input type="text" title="company" class="form-control required" name="company" readonly='readonly'>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="col-md-4">
                            <label for="utilisateur">Status</label>
                            <select title="status" class="form-control required" name="status">
                              <option value="CONFIRMED">CONFIRMED</option>
                              <option value="AUTOMATICLY_PLANNED">AUTOMATICLY_PLANNED</option>
                              <option value="DONE">DONE</option>
                              <option value="CANCELLED">CANCELLED</option>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label for="dateMaintenance"  class="fr">Date d'entretien</label>
                            <input type="date" title="dateMaintenance" name="dateMaintenance" class="form-control">
                          </div>
                        </div>
                          <div class="col-md-12">
                            <label for="comment"  class="fr">Commentaire</label>
                            <label for="comment"  class="en">Comment</label>
                            <label for="comment"  class="nl">Comment</label>
                            <textarea class="form-control" rows="5" name="comment"></textarea>
                          </div>
                        </div>
                        <input type="text" name="action" class="form-control hidden" value="edit">
                        <input type="text" name="user" class="form-control hidden" value="<?php echo $user; ?>">
                        <div class="col-sm-12">
                          <button  class="button small green button-3d rounded icon-left maintenanceManagementSendButton" type="submit"><i class="fa fa-paper-plane"></i>Valider</button>
                        </div>

                      </div>

                    </form>
                    <script type="text/javascript">
                    jQuery("#widget-maintenanceManagement-form").validate({
                      submitHandler: function(form) {
                        jQuery(form).ajaxSubmit({
                          success: function(response) {
                            if (response.response == 'success') {
                              $.notify({
                                message: response.message
                              }, {
                                type: 'success'
                              });
                              list_maintenances();
                              $('#maintenanceManagementItem').modal('toggle');
                              document.getElementById('widget-maintenanceManagement-form').reset()
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
                      <h4 class="fr text-green bikeManagementTitle">Ajouter un vélo</h4>
                      <div class="form-group col-sm-12">
                        <h4 class="fr text-green">Caractéristiques du vélo</h4>
                          
                        <div class="col-sm-12">
                          <div class="col-sm-4">
                            <label for="bikeID">ID</label>
                            <input type="text" name="bikeID" class="form-control required" readonly>
                          </div>
                        </div>
                        <div class="col-sm-12">
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
                            <label for="color"  class="fr">Couleur</label>
                            <label for="color"  class="en">Color</label>
                            <label for="color"  class="nl">Color</label>
                            <input type="text" name="color" class="form-control">
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="col-sm-4">
                            <label for="company"  class="fr">Société</label>
                            <label for="company"  class="en">Company</label>
                            <label for="company"  class="nl">Company</label>
                            <select name="company" class="form-control required"></select>
                          </div>
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
                            <input type="text" name="frameNumberOriginel" class="form-control hidden">
                            <input type="text" name="frameNumber" class="form-control">
                          </div>
                          <div class="col-sm-4">
                            <label for="frameReference"  class="fr">Référence de cadre</label>
                            <label for="frameReference"  class="en">Frame reference</label>
                            <label for="frameReference"  class="nl">Frame reference</label>
                            <input type="text" name="frameReference" class="form-control required">
                          </div>
                          <div class="col-sm-4">
                            <label for="lockerReference"  class="fr">Clé du cadenas</label>
                            <label for="lockerReference"  class="en">Locker key number</label>
                            <label for="lockerReference"  class="nl">Locker key number</label>
                            <input type="text" name="lockerReference" class="form-control">
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="col-sm-4 bikeManagementPicture">
                            <label for="picture"  class="fr">Image actuelle</label>
                            <label for="picture"  class="en">Current Image</label>
                            <label for="picture"  class="nl">Current Image</label>
                            <img id='bikeManagementPicture' alt="image">
                          </div>
                          <div class="col-sm-4 bikeImageUpload">
                            <label for="picture"  class="fr">Photo du vélo (.jpg)</label>
                            <label for="picture"  class="en">Bike picture (jpg)</label>
                            <label for="picture"  class="nl">Bike picture(jpg)</label>
                            <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                            <input type=file size=40 name="picture" class="form-control">
                          </div>
                        </div>

                          <div class="separator"></div>
                          <h4 class="fr text-green">Type de contrat</h4>
                          <div class="col-sm-12">
                              <div class="col-sm-4">
                                <label for="contractType"  class="fr">Type de contrat</label>
                                <label for="contractType"  class="en">Contract type</label>
                                <label for="contractType"  class="nl">Contract type</label>
                                <select name="contractType" class="form-control required">
                                </select>
                              </div>
                          </div>

                        <div class="separator buyingInfos" style="display:none;"></div>
                        <div class="col-sm-12 buyingInfos" style="display:none;">
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
                        </div>

                        <div class="separator contractInfos" style="display:none;"></div>
                        <div class="col-sm-12 contractInfos" style="display:none;">
                          <h4 class="fr text-green">Informations relatives au contrat</h4>

                          <div class="col-sm-4 contractStartBloc">
                            <label for="contractStart"  class="fr">Début de contrat</label>
                            <label for="contractStart"  class="en">Contract start</label>
                            <label for="contractStart"  class="nl">Contract start</label>
                            <input type="date" name="contractStart" class="form-control">
                          </div>
                          <div class="col-sm-4 contractEndBloc">
                            <label for="contractEnd"  class="fr">Fin de contrat</label>
                            <label for="contractEnd"  class="en">Contract End</label>
                            <label for="contractEnd"  class="nl">Contract End</label>
                            <input type="date" name="contractEnd" class="form-control">
                          </div>
                          <div class="col-sm-12">
                            <div class="col-sm-4 insurance">
                              <label for="insurance"  class="fr">Assurance ?</label>
                              <label for="insurance"  class="en">Insurance ?</label>
                              <label for="insurance"  class="nl">Insurance ?</label>
                              <input type="checkbox"name="insurance" id="insuranceBikeCheck" class="form-control">Oui
                            </div>
                            <div class="col-sm-4 soldPrice" style="display:none;">
                              <label for="insurance"  class="fr">Prix de vente du vélo</label>
                              <label for="insurance"  class="en">Bike sold price</label>
                              <label for="insurance"  class="nl">Bike sold price</label>
                              <input type="number" min="0" value="0" name="bikeSoldPrice" id="bikeSoldPrice" class="form-control" disabled />
                            </div>
                          </div>
                        </div>
                        <div class="separator orderInfos" style="display:none;"></div>
                        <div class="col-sm-12 orderInfos" style="display:none;">
                          <h4 class="fr text-green">Informations relatives à la commande</h4>
                          <div class="col-sm-4">
                            <label for="orderingDate"  class="fr">Date de la commande</label>
                            <label for="orderingDate"  class="en">Ordering date</label>
                            <label for="orderingDate"  class="nl">Ordering date</label>
                            <input type="date" name="orderingDate" class="form-control">
                          </div>
                          <div class="col-sm-4">
                            <label for="deliveryDate"  class="fr">Date estimée d'arrivée</label>
                            <label for="deliveryDate"  class="en">Arrival estimated date</label>
                            <label for="deliveryDate"  class="nl">Arrival estimated date</label>
                            <input type="date" name="deliveryDate" class="form-control">
                          </div>
                          <div class="col-sm-4">
                            <label for="orderNumber"  class="fr">Numéro de commande</label>
                            <label for="orderNumber"  class="en">Numéro de commande</label>
                            <label for="orderNumber"  class="nl">Numéro de commande</label>
                            <input type="text" name="orderNumber" class="form-control">
                          </div>
                          <div class="col-sm-4 offer">
                            <label for="offerReference"  class="offerReference">Offre liée</label>
                            <select name="offerReference" class="form-control offerReference">
                            </select>
                          </div>
                        </div>
                          
                        <div class="separator billingInfos" style="display:none;"></div>
                        <div class="col-sm-12 billingInfos" style="display:none;">
                          <h4 class="fr text-green">Informations relatives à la facturation</h4>

                          <div class="col-sm-4">
                            <label for="billingType"  class="fr">Type de facturation</label>
                            <label for="billingType"  class="en">Billing type</label>
                            <label for="billingType"  class="nl">Billing type</label>
                            <select name="billingType" class="form-control">
                              <option value="monthly">Mensuelle</option>
                              <option value="paid">Déjà payé</option>
                            </select>
                          </div>

                          <div class="col-sm-4 billingPriceDiv">
                            <label for="billingPrice"  class="fr">Montant de facturation</label>
                            <label for="billingPrice"  class="en">Montant de facturation</label>
                            <label for="billingPrice"  class="nl">Montant de facturation</label>

                            <div class="input-group">
                              <span class="input-group-addon">€/mois</span>
                              <input type="float" name="billingPrice" class="form-control">
                            </div>
                          </div>

                          <div class="col-sm-4 billingGroupDiv">
                            <label for="billingGroup"  class="fr">Groupe de facturation</label>
                            <label for="billingGroup"  class="en">Groupe de facturation</label>
                            <label for="billingGroup"  class="nl">Groupe de facturation</label>
                            <input type="text" name="billingGroup" class="form-control required">
                          </div>
                            
                            <div class="col-sm-12 billingDiv">
                              <div class="col-sm-4">
                                <label for="billing"  class="fr">Facturation automatique ?</label>
                                <label for="billing"  class="en">Automatic billing ?</label>
                                <label for="billing"  class="nl">Automatic billing ?</label>
                                <label><input type="checkbox" name="billing" class="form-control">Oui</label>
                              </div>
                            </div>
                            
                        </div>
                          
                        <div class="form-group col-sm-4" style="display:none;" id="addBike_firstBuilding"></div>
                        <div class="form-group col-sm-12" style="display:none;" id="addBike_buildingListing"></div>


                        <input type="text" name="user" class="form-control hidden" value="<?php echo $user; ?>">
                        <input type="text" name="action" class="form-control hidden">

                        <div class="col-sm-12" id='bikeBuildingAccessAdminDiv'style="display:none;"><h4>Accès aux bâtiments de ce vélo</h4></div>
                        <div class="form-group col-sm-12" id="bikeBuildingAccessAdmin" style="display:none;"></div>

                        <div class="col-sm-12" id='bikeUserAccessAdminDiv' style="display:none;"><h4>Accès des utilisateurs à ce vélo</h4></div>
                        <div class="form-group col-sm-12" id="bikeUserAccessAdmin" style="display:none;"></div>

                      </div>
                      <div class="col-sm-12">
                        <button  class="fr button small green button-3d rounded icon-left bikeManagementSend" type="submit"><i class="fa fa-plus"></i>Ajouter</button>
                      </div>


                    </form>



                    <div class="separator bikeActions" class="hidden"></div>

                    <div class="col-sm-12 bikeActions" class="hidden">

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
                            <textarea type="text" name="widget-addActionBike-form-description" class="form-control required hidden"></textarea>
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

                    <div class="separator billsInfos" style="display:none;"></div>

                    <div class="col-sm-12 billsInfos" style="display:none;">

                      <h4 class="fr text-green">Factures du vélo</h4>

                      <span id="bills_bike"></span>

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
                              get_company_details($('#widget-companyDetails-form input[name=ID]').val(),email);
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
                    <script src="js/add_bike_sell.js"></script>
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
                          <input type="number" min="0" max="10" name="billingGroup" class="form-control required" value="1">
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
                              get_company_details($('#widget-companyDetails-form input[name=ID]').val(),email);
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
                              get_company_details($('#widget-companyDetails-form input[name=ID]').val(),email);
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
                              <option value="leasing">Location</option>
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

                        <div class="separator offerManagementDetails"></div>
                        <div class="col-sm-12 offerManagementDetails">
                            <h4 class="text-green">Détails de l'offre</h4>
                            <ul id='offerManagementDetails'>
                            </ul>
                        </div>
                        <div class="separator offerManagementPDF"></div>
                        <div class="col-sm-12 offerManagementPDF">
                            <h4 class="text-green">Offre PDF</h4>
                            
                            <object data="" id='offerManagementPDF' type="application/pdf" width="100%" height="800px"> 
                              <p>Apparemment vous n'avez pas un plug-in pour lire directement un fichier PDF. Vous pouvez <a href="resume.pdf">cliquer ici pour télécharger le fichier.</a></p>  
                            </object>                                                      
                        </div>
                        <br>
                        <input type="hidden" id="companyHiddenOffer" name="company" class="form-control required hidden" value="">
                        <input type="text" name="requestor" class="form-control required hidden" value="<?php echo $user; ?>">
                        <input type="text" name="action" class="form-control required hidden" value="add">
                        <input type="text" name="ID" class="hidden">


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
                              if($('#widget-companyDetails-form input[name=ID]').val()!=''){
                                  get_company_details($('#widget-companyDetails-form input[name=ID]').val(), email);   
                              }
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
                        <label for="leasingCheck" class="fr">Type</label>
                        <label for="leasingCheck" class="en">Type</label>
                        <label for="leasingCheck" class="nl">Type</label>
                        <select name="buyOrLeasing" id="buyOrLeasingSelect" class="form-control required" aria-required="true">
                          <option value="leasing" selected>Location</option>
                          <option value="buy">Achat</option>
                          <option value="both"> Achat et Location</option>
                        </select>
                        <!--<input type="checkbox" class="leasingCheck form-control" name="isLeasing" value="leasing" checked />-->
                      </div>
                      <div class="col-sm-4 form-group leasingSpecific">
                        <label for="leasingDuration" class="fr">Durée location (mois)</label>
                        <label for="leasingDuration" class="en">Location duration (months)</label>
                        <label for="leasingDuration" class="nl">Durée location (mois)</label>
                        <input type="number" name="leasingDuration" class="leasingDuration form-control required" aria-required="true" value="36" min="1">
                      </div>
                      <div class="col-sm-3 form-group leasingSpecific">
                        <label for="numberMaintenance" class="fr">Entretiens</label>
                        <label for="numberMaintenance" class="en">Maintenance</label>
                        <label for="numberMaintenance" class="nl">Maintenance</label>
                        <input type="number" name="numberMaintenance" class="numberMaintenance form-control required" aria-required="true" value="4" min="0">
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
                            <label for="bikeBrandModel" class="fr">Modèle</label>
                            <label for="bikeBrandModel" class="en">Model</label>
                            <label for="bikeBrandModel" class="nl">Model</label>
                          </th>
                          <th class="bikepAchat">
                            <label for="pAchat" class="fr">Prix d'achat</label>
                            <label for="pAchat" class="en">Buying price</label>
                            <label for="pAchat" class="nl">Buying price</label>
                          </th>
                          <th class="bikepCosts">
                            <label for="pAchat" class="fr">Coûts maintenance & assur.</label>
                            <label for="pAchat" class="en">Maintenance costs</label>
                            <label for="pAchat" class="nl">Maintenance costs</label>
                          </th>
                          <th class="bikepCatalog">
                            <label for="pCatalog" class="fr">Prix catalogue</label>
                            <label for="pCatalog" class="en">Catalog price</label>
                            <label for="pCatalog" class="nl">Catalog price</label>
                          </th>
                          <th class="bikepVenteHTVA" style="display:none">
                            <label for="pVenteHTVA" class="fr">Prix de vente</label>
                            <label for="pVenteHTVA" class="en">Selling price</label>
                            <label for="pVenteHTVA" class="nl">Selling price</label>
                          </th>
                          <th class="bikeLeasing">
                            <label for="leasing" class="fr">Location</label>
                            <label for="leasing" class="en">Renting</label>
                            <label for="leasing" class="nl">Renting</label>
                          </th>
                          <th class="contractLeasing">
                            <label for="contractLeasing" class="fr">Valeur totale</label>
                            <label for="contractLeasing" class="en">Total value</label>
                            <label for="contractLeasing" class="nl">Total value</label>
                          </th>
                          <th class="bikeMarge">
                            <label for="marge" class="fr">Marge</label>
                            <label for="marge" class="en">Margin</label>
                            <label for="marge" class="nl">Margin</label>
                          </th>
                          <th class="bikeFinalPrice hidden">
                            <label for="bikeFinalPrice" class="fr">bikeFinalPrice</label>
                            <label for="bikeFinalPrice" class="en">bikeFinalPrice</label>
                            <label for="bikeFinalPrice" class="nl">bikeFinalPrice</label>
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
                            <label for="boxModel" class="fr">Modèle</label>
                            <label for="boxModel" class="en">Model</label>
                            <label for="boxModel" class="nl">Model</label>
                          </th>
                          <th class="boxProdPrice">
                            <label for="boxProdPrice" class="fr">Production</label>
                            <label for="boxProdPrice" class="en">Production</label>
                            <label for="boxProdPrice" class="nl">Production</label>
                          </th>
                          <th class="boxMaintenance">
                            <label for="boxMaintenance" class="fr">Coûts maintenance</label>
                            <label for="boxMaintenance" class="en">Maintenance costs</label>
                            <label for="boxMaintenance" class="nl">Maintenance costs</label>
                          </th>
                          <th class="boxInstallationPrice">
                            <label for="boxInstallationPrice" class="fr">Installation</label>
                            <label for="boxInstallationPrice" class="en">Installation</label>
                            <label for="boxInstallationPrice" class="nl">Installation</label>
                          </th>
                          <th class="boxFinalInstallationPrice hidden">
                            <label for="boxFinalInstallationPrice" class="fr">boxFinalInstallationPrice</label>
                            <label for="boxFinalInstallationPrice" class="en">boxFinalInstallationPrice</label>
                            <label for="boxFinalInstallationPrice" class="nl">boxFinalInstallationPrice</label>
                          </th>
                          <th class="boxLocationPrice">
                            <label for="boxLocationPrice" class="fr">Location</label>
                            <label for="boxLocationPrice" class="en">Renting</label>
                            <label for="boxLocationPrice" class="nl">Renting</label>
                          </th>
                          <th class="boxFinalLocationPrice hidden">
                            <label for="boxFinalLocationPrice" class="fr">boxFinalLocationPrice</label>
                            <label for="boxFinalLocationPrice" class="en">boxFinalLocationPrice</label>
                            <label for="boxFinalLocationPrice" class="nl">boxFinalLocationPrice</label>
                          </th>
                            
                          <th class="boxContractPrice">
                            <label for="boxContractPrice" class="fr">Valeur totale</label>
                            <label for="boxContractPrice" class="en">Total value</label>
                            <label for="boxContractPrice" class="nl">Total value</label>
                          </th>
                          <th class="boxMarge">
                            <label for="boxMarge" class="fr">Marge</label>
                            <label for="boxMarge" class="en">Margin</label>
                            <label for="boxMarge" class="nl">Margin</label>
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
                        <th class="othersBuyingCost">
                          <label for="oBuyingCost" class="fr">Cout achat</label>
                        </th>
                        <th class="othersSellingCost">
                          <label for="oSellingCost" class="fr">Prix vente</label>
                        </th>
                        <th class="othersSellingCostFinal">
                          <label for="oSellingCost" class="fr">Prix vente final</label>
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
                        <th><label for="recapLeasing fr">Location (au mois)</label></th>
                      </thead>
                      <tbody></tbody>
                      <tfoot></tfoot>
                    </table>
                    <div class="separator"></div>
                  </div>
                  <div class="row form-group">
                    <h4 class="text-green">Délais vélos</h4>
                    <div class="col-sm-8">
                      <textarea name="delais" id="delais" class="form-control required" required></textarea>
                    </div>
                    <div class="separator"></div>
                    <h4 class="text-green">Validité de l'offre</h4>
                    <div class="col-sm-4">
                      <input type="date" name="offerValidity" id="offerValidity" class="form-control required" required>
                    </div>
                      <div class="separator"></div>
                  </div>
                  <div class="row form-group" style="margin-bottom:20px;">
                    <h4 class="text-green">Contact société</h4>
                    <div class="col-sm-4 companyContactDiv">
                    </div>
                    <div class="separator"></div>
                  </div>
                  <div class="row form-group" style="margin-bottom:20px;">
                    <h4 class="text-green">Signature de l'offre</h4>                      
                    <div class="col-sm-12">  
                        <div class="col-sm-12">  
                                <label for="probability"  class="fr">Chance de réussite</label>
                                <label for="probability"  class="en">Chance de réussite</label>
                                <label for="probability"  class="nl">chance de réussite</label>                        
                              <div class="col-sm-3 input-group">
                                <span class="input-group-addon">%</span>
                                <input type="number" min="0" max="100" name="probability" class="form-control required">
                              </div>
                          </div>
                      </div>
                        <div class="col-sm-12">
                          <div class="col-sm-4">
                            <label for="dateSignature"  class="fr">Date de signature</label>
                            <label for="dateSignature"  class="en">Date de signature</label>
                            <label for="dateSignature"  class="nl">Date de signature</label>
                            <input type="date" name="dateSignature" class="form-control">
                          </div>
                          <div class="col-sm-4">
                            <label for="dateStart"  class="fr">Date de début</label>
                            <label for="dateStart"  class="en">Date de début</label>
                            <label for="dateStart"  class="nl">Date de début</label>
                            <input type="date" name="dateStart" class="form-control">
                          </div>
                          <div class="col-sm-4">
                            <label for="dateEnd"  class="fr">Date de fin</label>
                            <label for="dateEnd"  class="en">Date de fin</label>
                            <label for="dateEnd"  class="nl">Date de fin</label>
                            <input type="date" name="dateEnd" class="form-control">
                          </div>
                        </div>
                    </div>
                    <input type="text" name="email" class="form-control required hidden" value="<?php echo $user; ?>">
                    <br/>
                  <button type="submit" class="fr button small green button-3d rounded icon-left generatePDF">Générer PDF</button>
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
                              },{
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
                      <h4 class="text-green">Caractéristiques du vélo</h4>                        
                        <div class="col-sm-4">
                            <label for="bikeID" class="fr-inline">Référence du vélo :</label>
                            <label for="bikeID" class="en-inline">Bike Reference :</label>
                            <label for="bikeID" class="nl-inline">Bike Reference :</label>
                            <input type="text" name="bikeID" readonly class="form-control" />
                        </div>
                        <div class="col-sm-12"></div>
                      <div class="col-sm-4">
                        <label for="bikeModel" class="fr">Modèle :</label>
                        <label for="bikeModel" class="en">Model:</label>
                        <label for="bikeModel" class="nl">Model:</label>
                        <input type="text" name="bikeModel" class="form-control" />
                      </div>
                      <div class="col-sm-4">
                        <label for="bikeNumber" class="fr">Numéro d'identification :</label>
                        <label for="bikeNumber" class="en">Identification number :</label>
                        <label for="bikeNumber" class="nl">Identification number :</label>
                        <input type="text" disabled name="bikeNumber" class="form-control">
                      </div>
                      <div class="col-sm-4">
                        <label for="frameReference" class="fr">Référence du cadre :</label>
                        <label for="frameReference" class="en">Frame reference :</label>
                        <label for="frameReference" class="nl">Frame reference :</label>
                        <input type="text" disabled name="frameReference" class="form-control">
                      </div>

                      <div class="separator"></div>

                      <h4 class="text-green">Informations relatives au contrat</h4>

                      <div class="col-sm-4">
                        <label for="contractType" class="fr">Type de contrat :</label>
                        <label for="contractType" class="en">Contract type :</label>
                        <label for="contractType" class="nl">Contract type :</label>
                        <input type="text" disabled name="contractType" class="form-control">
                          
                      </div>

                      <div class="col-sm-4">
                        <label for="startDateContract" class="fr">Date de début :</label>
                        <label for="startDateContract" class="en">Start date :</label>
                        <label for="startDateContract" class="nl">Start date :</label>
                        <input type="date" disabled name="startDateContract" class="form-control">
                      </div>

                      <div class="col-sm-4">
                        <label for="endDateContract" class="fr">Date de fin :</label>
                        <label for="endDateContract" class="en">End date :</label>
                        <label for="endDateContract" class="nl">End date :</label>
                        <input type="date" disabled name="endDateContract" class="form-control">
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
                          <input type="text" class="hidden" name="bikeID"/>
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
                  get_bills_listing('*', '*', '*', '*',email);
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
                  get_bills_listing('*', '*', '*', '*',email);
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
                  list_tasks('*', $('.taskOwnerSelection').val(),'<?php echo $user ?>');
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
                                <option value="Benno">Benno</option>
                                <option value="Bzen">Bzen</option>
                                <option value="Conway">Conway</option>
                                <option value="Douze Cycle">Douze Cycle</option>
                                <option value="HNF Nicolai">HNF Nicolai</option>
                                <option value="Kayza">Kayza</option>
                                <option value="Orbea">Orbea</option>
                                <option value="Victoria">Victoria</option>
                                <option value="Stevens">Stevens</option>
                                <option value="other">Other</option>
                                
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
                              <option value="Gravel">Gravel</option>
                              <option value="VTT">VTT</option>
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
                            <option value="Benno">Benno</option>                              
                            <option value="Bzen">Bzen</option>
                            <option value="Conway">Conway</option>
                            <option value="Douze Cycle">Douze Cycle</option>
                            <option value="HNF Nicolai">HNF Nicolai</option>
                            <option value="Kayza">Kayza</option>
                            <option value="Orbea">Orbea</option>
                            <option value="Victoria">Victoria</option>                              
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
                                <option value="Gravel">Gravel</option>
                                <option value="VTT">VTT</option>
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
                          <input type="text" class="bikeCatalogStock form-control" name="stock" />
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
        <?php if($connected){
          ?>
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


          <?php
        }
        ?>

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
                            <label for="bikeID"  class="fr">Numéro de cadre</label>
                            <label for="bikeID"  class="en">Frame Number</label>
                            <label for="bikeID"  class="nl">Frame Numer</label>
                            <input type="text" name="bikeID" class="form-control required" />
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

          </div>

          <script type="text/javascript">
          function initializeAssistance2() {
            document.getElementById('widget-assistance-form-message').value="";
            document.getElementById('widget-assistance-form-message-attachment').value="";

          }
          function initializeEntretien2(bikeID) {
            if(!(bikeID)){
                bikeID="";
                $('#widget-entretien-form input[name=bikeID]').attr('disabled', false);
                
            }else{
                $('#widget-entretien-form input[name=bikeID]').attr('disabled', true);
            }
            $('#widget-entretien-form input[name=bikeID]').val(bikeID);
            document.getElementById('widget-entretien-form-message').value="";
            document.getElementById('widget-entretien-form-message-attachment').value="";

          }
          </script>




          <div class="loader"><!-- Place at bottom of page --></div>

          <!-- FOOTER -->
		<footer class="background-dark text-grey" id="footer">
	    <div class="footer-content">
	        <div class="container">
	        
	        <br><br>
	        
	            <div class="row text-center">
	            
	                <div class="copyright-text text-center"><ins>Kameo Bikes SPRL</ins> 
						<br>BE 0681.879.712 
						<br>+32 498 72 75 46 </div>
						<br>
	                <div class="social-icons center">
								<ul>
									<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
									
									<li class="social-linkedin"><a href="https://www.linkedin.com/company/kameobikes/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
									
								</ul>
					</div>
					
					<div><a href="faq.php" class="text-green text-bold"><h3 class="text-green">FAQ</h3></a><!-- | <a href="bonsplans.php" class="text-green text-bold">Les bons plans</a>--></div>
					
					<br>
					<br>
					
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