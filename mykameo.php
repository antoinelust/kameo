<?php
ob_start();
session_start();

$user=isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL;
$user_ID = isset($_SESSION['ID']) ? $_SESSION['ID'] : NULL;
$langue=isset($_SESSION['langue']) ? $_SESSION['langue'] : 'fr';

include 'include/header5.php';
include 'include/activitylog.php';
include 'include/connexion.php';

echo`
<style media="screen">
    .tableFixed {
      table-layout: fixed;
    }
    .separator-small{
      padding-top:20px;
      width:60%;
      opacity: 0.5;
    }
</style>`;

if($user==NULL){ //Not connected
  include 'include/vues/loginform.php'; //@TODO: Remove php from the view and convert to HTML
}else{ //Connected

}
?>

<script type="text/javascript" src="./js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript" src="./node_modules/chart.js/dist/Chart.js" charset="UTF-8"></script>
<script src="js/OpenLayers/OpenLayers.js"></script>
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
<script type="text/javascript" src="js/initialize_counters.js"></script>
<script type="text/javascript" src="js/cafetaria.js"></script>
<script type="text/javascript" src="js/orderManagement.js"></script>


<script type="text/javascript">

var email="<?php echo $user; ?>";
var langue= "<?php echo $_SESSION['langue']; ?>";
var user_ID = "<?php echo $user_ID; ?>";
var color=Chart.helpers.color;
var companyId;
//varibles des charts chartJS
var myChart;
var myChart2;
var myChart3;

var nbContacts;

window.addEventListener("DOMContentLoaded", function(event) {

	$( ".fleetmanager" ).click(function() {
    /** DASHBOARD **/
    list_errors();
    initialize_task_owner_sales_selection();

		initializeFields();
    
		hideResearch();
		get_company_conditions();
		list_condition();
		initialize_counters();
		var date=new Date();
		$(".form_date_end_client").data("datetimepicker").setDate(date);
		date.setMonth(date.getMonth()-6);
		$(".form_date_start_client").data("datetimepicker").setDate(date);
		list_maintenances();
	});

  $( ".dashboardManagementClick" ).click(function() {
  });

  $('.clientManagerClick').click(function(){
      get_company_listing('*');
      generateCompaniesGraphic($('.form_date_start_client').data("datetimepicker").getDate(), $('.form_date_end_client').data("datetimepicker").getDate());
  });

	$( ".reservations" ).click(function() {
		hideResearch();
		getHistoricBookings(email);
	});

    var date=new Date();
    $(".form_date_end").data("datetimepicker").setDate(date);
    date.setMonth(date.getMonth()-1);
    $(".form_date_start").data("datetimepicker").setDate(date);

});

function initializeFields(){

  $('#widget-bikeManagement-form select[name=company]').find('option').remove().end();
  $('#widget-updateAction-form select[name=company]').find('option').remove().end();
  $('#widget-taskManagement-form select[name=company]').find('option').remove().end();
  $('#widget-boxManagement-form select[name=company]').find('option').remove().end();

  $.ajax({
    url: 'include/get_companies_listing.php',
    type: 'post',
    data: {type: "*"},
    success: function(response){
      if(response.response == 'success'){
        for (var i = 0; i < response.companiesNumber; i++){
          var selected ="";
          if (response.company[i].internalReference == "KAMEO") {
            selected ="selected";
          }
          $('#widget-bikeManagement-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>");
          $('#widget-updateAction-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>");
          $('#widget-taskManagement-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\" "+selected+">"+response.company[i].companyName+"<br>");
          $('#widget-boxManagement-form select[name=company]').append("<option value=\""+response.company[i].internalReference+"\">"+response.company[i].companyName+"<br>");
        }
      }
	  else {
        console.log(response.response + ': ' + response.message);
      }
    }
  });

  $.ajax({
    url: 'include/initialize_fields.php',
    type: 'get',
    data: {type: "ownerField"},
    success: function(response){
        if(response.response == 'success'){

            $('#widget-taskManagement-form select[name=owner]').find('option').remove().end();
            $('.taskOwnerSelection').find('option').remove().end();
            $('.taskOwnerSelection2').find('option').remove().end();
            $('.taskOwnerSelection').append("<option value='*'>Tous<br>");
            $('.taskOwnerSelection2').append("<option value='*'>Tous<br>");
            $('#widget-taskManagement-form select[name=owner]').append("<option value='*'>Tous<br>");
            for (var i = 0; i < response.ownerNumber; i++){
                $('#widget-taskManagement-form select[name=owner]').append("<option value="+response.owner[i].email+">"+response.owner[i].firstName+" "+response.owner[i].name+"<br>");
                $('.taskOwnerSelection').append("<option value="+response.owner[i].email+">"+response.owner[i].firstName+" "+response.owner[i].name+"<br>");
                $('.taskOwnerSelection2').append("<option value="+response.owner[i].email+">"+response.owner[i].firstName+" "+response.owner[i].name+"<br>");
			      }
        }
    		else {
    			console.log(response.response + ': ' + response.message);
        }
    }
  });
}

//FleetManager: Gérer les Actions | List user task on <select> call
function taskFilter(e){
  list_tasks('*', $('.taskOwnerSelection').val(),'<?php echo $user ?>');
}

//FleetManager: Gérer les Actions | Displays the task graph by calling action_company.php and creating it
function generateTasksGraphic(company, owner, numberOfDays){
  $.ajax({
    url: 'include/action_company.php',
    type: 'get',
    data: { "action": "graphic", "company": company, "owner": owner, "numberOfDays": numberOfDays},
    success: function(response){
      if (response.response == 'error') {
		  console.log(response.message);
	  }
	  else {
        var ctx = document.getElementById('myChart2').getContext('2d');
        if (myChart2 != undefined)
          myChart2.destroy();

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
                ticks: { beginAtZero: true, stacked: true }
              }]
            },
            elements: {
              line: { tension: 0 }
            }
          }
        });
        if(response.presenceContacts=="1")
          myChart2.data.datasets[1].hidden=false;
        if(response.presenceReminder=="1")
          myChart2.data.datasets[2].hidden=false;
        if(response.presenceRDVPlan=="1")
          myChart2.data.datasets[3].hidden=false;
        if(response.presenceRDV=="1")
          myChart2.data.datasets[4].hidden=false;
        if(response.presenceOffers=="1")
          myChart2.data.datasets[5].hidden=false;
        if(response.presenceOffersSigned=="1")
          myChart2.data.datasets[6].hidden=false;
        if(response.presenceDelivery=="1")
          myChart2.data.datasets[7].hidden=false;
        if(response.presenceOther=="1")
          myChart2.data.datasets[8].hidden=false;

        myChart2.update();
      }
    }
  });
}

//FleetManager: Gérer les clients | Displays the companies graph by calling get_companies_listing.php and creating it
function generateCompaniesGraphic(dateStart, dateEnd){

  var dateStartString=dateStart.getFullYear()+"-"+("0" + (dateStart.getMonth() + 1)).slice(-2)+"-"+("0" + dateStart.getDate()).slice(-2);
  var dateEndString=dateEnd.getFullYear()+"-"+("0" + (dateEnd.getMonth() + 1)).slice(-2)+"-"+("0" + dateEnd.getDate()).slice(-2);

  $.ajax({
    url: 'include/get_companies_listing.php',
    type: 'get',
    data: { "action": "graphic", "numberOfDays": "30", "dateStart": dateStartString, "dateEnd": dateEndString},
    success: function(response){
      if (response.response == 'error') {
		console.log(response.message);
	  }
	  else {
        var ctx = document.getElementById('myChart3').getContext('2d');
        if (myChart3 != undefined)
          myChart3.destroy();

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
              line: { tension: 0 }
            }
          }
        });
        myChart3.update();
      }
    }
  });
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
			$('input[name=widget-updateBillingStatus-form-VAT]').prop('checked', Boolean(response.bill.amountHTVA != response.bill.amountTVAC));
			$('input[name=widget-updateBillingStatus-form-sent]').prop( 'checked', Boolean(response.bill.sent=="1"));
			$('input[name=widget-updateBillingStatus-form-paid]').prop( 'checked', Boolean(response.bill.paid=="1"));
			$('#widget-updateBillingStatus-form input[name=accounting]').prop( 'checked', Boolean(response.bill.communicationSentAccounting=="1"));
			$('input[name=widget-updateBillingStatus-form-currentFile]').val(response.bill.file);
			$("#widget-deleteBillingStatus-form input[name=reference]").val(ID);
			if(response.bill.sentDate)
			  $('input[name=widget-updateBillingStatus-form-sendingDate]').val(response.bill.sentDate.substring(0,10));
			else
			  $('input[name=widget-updateBillingStatus-form-sendingDate]').val('');
			if(response.bill.paidDate)
			  $('input[name=widget-updateBillingStatus-form-paymentDate]').val(response.bill.paidDate.substring(0,10));
			else
			  $('input[name=widget-updateBillingStatus-form-paymentDate]').val('');
			if(response.bill.paidLimitDate)
			  $('input[name=widget-updateBillingStatus-form-datelimite]').val(response.bill.paidLimitDate.substring(0,10));
			else
			  $('input[name=widget-updateBillingStatus-form-datelimite]').val('');
			if(response.bill.file != ''){
			  $('.widget-updateBillingStatus-form-currentFile').attr("href", "factures/"+response.bill.file);
			  $('.widget-updateBillingStatus-form-currentFile').unbind('click');
			}else{
			  $('.widget-updateBillingStatus-form-currentFile').click(function(e) {
				e.preventDefault();
				$.notify({ message: "No file available for that bill" }, { type: 'danger' });
			  });
			}
			var dest='<table class=\"table table-condensed\"><thead><tr><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Bike</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Comentaire</span><span class=\"en-inline\">Comment</span><span class=\"nl-inline\">Comment</span></th></tr></thead><tbody>';
			for(var i = 0; i<response.billDetailsNumber; i++){
			  dest=dest.concat("<tr><th>"+response.bill.billDetails[i].bikeID + " - " + response.bill.billDetails[i].frameNumber+"</th><th>"+response.bill.billDetails[i].amountHTVA+"</th><th>"+response.bill.billDetails[i].comments+"</th></tr>");
			}
			document.getElementById('billingDetails').innerHTML=dest.concat("</tbody><table>");
			displayLanguage();
        }
    }
  });
}

//FleetManager: Gérer le catalogue | Displays the portfolio <table> by calling load_portfolio.php and creating it
function listPortfolioBikes(){
  $.ajax({
    url: 'include/load_portfolio.php',
    type: 'get',
    data: {"action": "list"},
    success: function(response){
      if (response.response == 'error') {
        console.log(response.message);
      } else{
            var dest="<table class=\"table table-condensed\" id=\"portfolioBikeListing\"><h4 class=\"fr-inline text-green\">Vélos du catalogue:</h4><h4 class=\"en-inline text-green\">Portfolio bikes:</h4><h4 class=\"nl-inline text-green\">Portfolio bikes:</h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addPortfolioBike\" data-toggle=\"modal\" onclick=\"initializeCreatePortfolioBike()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un vélo</span></a><thead><tr><th>ID</th><th><span class=\"fr-inline\">Marque</span><span class=\"en-inline\">Brand</span><span class=\"nl-inline\">Brand</span></th><th><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th><span class=\"fr-inline\">Utilisation</span><span class=\"en-inline\">Use</span><span class=\"nl-inline\">Use</span></th><th><span class=\"fr-inline\">Electrique ?</span><span class=\"en-inline\">Electric</span><span class=\"nl-inline\">Electric</span></th><th><span class=\"fr-inline\">Cadre</span><span class=\"en-inline\">Frame</span><span class=\"nl-inline\">Frame</span></th><th><span class=\"fr-inline\">Prix</span><span class=\"en-inline\">Price</span><span class=\"nl-inline\">Price</span></th><th>Afficher</th><th></th></tr></thead><tbody>";
            for(i = 0; i<response.bikeNumber; i++){
                dest=dest.concat("<tr><td>"+response.bike[i].ID+"</td><td>"+response.bike[i].brand+"</td><td>"+response.bike[i].model+"</td><td>"+response.bike[i].utilisation+"</td><td>"+response.bike[i].electric+"</td><td>"+response.bike[i].frameType+"</td><td>"+Math.round(response.bike[i].price)+" €</td><td>"+response.bike[i].display+"<td><a href=\"#\" class=\"text-green updatePortfolioClick\" onclick=\"initializeUpdatePortfolioBike('"+response.bike[i].ID+"')\" data-target=\"#updatePortfolioBike\" data-toggle=\"modal\">Mettre à jour </a></td></tr>");
            }
            document.getElementById('portfolioBikesListing').innerHTML=dest.concat("</tbody></table>");
            displayLanguage();
            $('#portfolioBikeListing').DataTable({
                "paging": false
            });
      }
    }
  });
}

//FleetManager: Gérer le catalogue | Displays the bike information when "Mettre à jour" is pressed
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
        $('#widget-updateCatalog-form input[name=display]').prop("checked", Boolean(response.display=='Y'));
      }
    }
  })
}

//FleetManager: Gérer le catalogue | Reset the form to add a bike to the catalogue
function initializeCreatePortfolioBike(){
  document.getElementById('x').reset();
}

</script>

<?php

//{DO EVERYTHING UNDER ONLY IF CONNECTED}
//Define $company as true if user uses personnal bikes, false if not
if($user!=NULL){
  $sql = "select * from customer_referential aa, customer_bike_access bb where aa.EMAIL='$user' and aa.EMAIL=bb.EMAIL and bb.TYPE='personnel' LIMIT 1";
  $result = mysqli_query($conn, $sql);
  $length = $result->num_rows;

  $row = mysqli_fetch_assoc($result);
  if ($length>0){
    $company=false;
  }
  else{
    $company=true;
  }
  ?>

  <script type="text/javascript">
  var connected="<?php echo $user!=NULL; ?>";

  var langueJava = "<?php echo $_SESSION['langue']; ?>";

  //Promise containing Client Conditions
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
    });
  }

  //Delete results of search
  function hideResearch(){
    document.getElementById('velos').innerHTML = "";
    document.getElementById("velos").style.display = "none";
    document.getElementById("travel_information").style.display = "none";
  }

  //FleetManager: Nombre d'utilisateurs | Displays the user list <table> by calling get_users_listing.php and creating it
  function get_users_listing(){
    var email= "<?php echo $user; ?>";
    $.ajax({
      url: 'include/get_users_listing.php',
      type: 'post',
      data: { "email": email},
      success: function(response){
        if(response.response == 'success'){
          var dest="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Utilisateurs :</h4><h4 class=\"en-inline\">Users:</h4><h4 class=\"nl-inline\">Gebruikers:</h4><br><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addUser\" data-toggle=\"modal\" onclick=\"create_user()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un utilisateur</span></a><tbody><thead><tr><th><span class=\"fr-inline\">Nom</span><span class=\"en-inline\">Name</span><span class=\"nl-inline\">Naam</span></th><th><span class=\"fr-inline\">Prénom</span><span class=\"en-inline\">Firstname</span><span class=\"nl-inline\">Voorname</span></th><th><span class=\"fr-inline\">e-mail</span><span class=\"en-inline\">mail</span><span class=\"nl-inline\">mail</span></th><th>Status</th><th></th></tr></thead>";
          for (var i = 0; i < response.usersNumber; i++){
            if(response.user[i].staann=='D'){
              var status="<span class=\"text-red\">Inactif</span>";
            }else{
              var status="Actif";
            }
            dest = dest.concat("<tr><td>"+response.user[i].name+"</td><td>"+response.user[i].firstName+"</td><td>"+response.user[i].email+"</td><td>"+status+"</td><td><a  data-target=\"#updateUserInformation\" name=\""+response.user[i].email+"\" data-toggle=\"modal\" class=\"text-green\" href=\"#\" onclick=\"update_user_information('"+response.user[i].email+"')\">Mettre à jour</a></td></tr>");
          }
          document.getElementById('usersList').innerHTML = dest;
          displayLanguage();
        }
        else {
          console.log(response.response + ': ' + response.message);
        }
      }
    })
  }

/************************************************************************************************************************************************/
/*** ENTERING WILD ZONE: WHERE NOTHING'S REFACTORED ***/
/************************************************************************************************************************************************/

  //FleetManager: Nombre d'utilisateurs | Displays the msg to confim user creation
  function confirm_add_user(){
    document.getElementById('confirmAddUser').innerHTML="<p><strong>Attention</strong>, la création d'un compte entraînera l'envoi d'un mail vers la personne en question.<br>Veuillez confirmer que les informations mentionées précédemment sont correctes.</p><button class=\"fr button small green button-3d rounded icon-left\" type=\"submit\"><i class=\"fa fa-paper-plane\"></i>Confirmer</button>";
  }

  //MARCHE QUAND TU CLIQUE SUR CLICK MANAGER MAIS DOIS ETRE DEPLACE SUR MODIFIER LES REGLAGES
  function list_condition(){
    var email= "<?php echo $user; ?>";
    $.ajax({
      url: 'include/get_conditions_listing.php',
      type: 'get',
      data: { "email": email},
      success: function(response){
        if(response.response == 'success'){
          var dest="<table class=\"table table-condensed\"><h4 class=\"fr-inline text-green\">Groupes de condition :</h4><h4 class=\"en-inline\">Condition groups:</h4><h4 class=\"nl-inline\">Condition groups:</h4><br><a class=\"button small green button-3d rounded icon-right\" data-target=\"#companyConditions\" data-toggle=\"modal\" onclick=\"create_condition()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un groupe de conditions</span></a><tbody><thead><tr><th><span class=\"fr-inline\">Nom</span><span class=\"en-inline\">Name</span><span class=\"nl-inline\">Naam</span></th><th><span class=\"fr-inline\">Nombre d'utilisateurs</span><span class=\"en-inline\">Groupe size</span><span class=\"nl-inline\">Group size</span></th><th></th></tr></thead>";

          for (var i = 0; i < response.conditionNumber; i++){
            if(response.condition[i].name=="generic"){
              var temp="<tr><th>Conditions génériques</th>";
            }
            else{
              var temp="<tr><th>"+response.condition[i].name+"</th>";
            }
            dest=dest.concat(temp);
            var temp="<th>"+response.condition[i].length+"</th><th><a  data-target=\"#companyConditions\" data-toggle=\"modal\" class=\"text-green\" href=\"#\" onclick=\"get_company_conditions('"+response.condition[i].id+"')\">Mettre à jour</a></th></tr>";
            dest=dest.concat(temp);
          }
          document.getElementById('spanConditionListing').innerHTML = dest;
          displayLanguage();
        }
        else {
          console.log(response.response + ': ' + response.message);
        }
      }
    });
  }

  //FONCTION QUI GERE LES PERMISSION (à refaire), SEPARER LA PARTIE QUI GERE LES CONDITIONS
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

            document.getElementsByClassName('boxManagerClick')[0].addEventListener('click', function() { list_boxes('*')}, false);
            $('.tasksManagerClick').click(function(){
                list_tasks('*', $('.taskOwnerSelection').val(), '<?php echo $user ?>');
                generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val());
            });
            $('#offerManagerClick').click(function(){
                list_contracts_offers('*');
            });
            $('.ordersManagerClick').click(function(){get_orders_listing()});
            document.getElementsByClassName('feedbackManagerClick')[0].addEventListener('click', function() {list_feedbacks()});
            document.getElementsByClassName('taskOwnerSelection')[0].addEventListener('change', function() { taskFilter()}, false);
            document.getElementsByClassName('taskOwnerSelection2')[0].addEventListener('change', function() { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);
            document.getElementsByClassName('numberOfDays')[0].addEventListener('change', function() { generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val())}, false);
            document.getElementsByClassName('maintenanceManagementClick')[0].addEventListener('click', function() { list_maintenances()}, false);
            if(email=='julien@kameobikes.com' || email=='antoine@kameobikes.com' || email=='thibaut@kameobikes.com' || email=='pierre-yves@kameobikes.com' || email=='test3@kameobikes.com'){
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
            document.getElementById('orderManagement').classList.remove("hidden");
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

  //APPELER QUAND TU CLIQUE SUR Ajouter un groupe de conditions
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

  //FleetManager: Nombre d'utilisateurs | List the building, bikes and display the create button
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
                  temp="<input type=\"checkbox\" name=\"bikeAccess[]\" checked value=\""+response.bike[i].id+"\">"+response.bike[i].frameNumber+" "+response.bike[i].model+"<br>";
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
          });
        }
      }
    });
  }

  //FleetManager: Nombre d'utilisateurs | Display user details when "Mettre à jour" button is pressed
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
    });
  }

  //FleetManager: Nombre d'utilisateurs | Display summary of user details when "Supprimer" button is pressed
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
    });
    $('#updateUserInformation').modal('toggle');
  }

  //FleetManager: Nombre d'utilisateurs | Display summary of user details when "Réactiver" button is pressed
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

  //Réserver un vélo, calcul distance entre 2 batiments
  function get_address_building(buildingReference){
    return $.ajax({
      url: 'include/get_address_building.php',
      type: 'post',
      data: { "buildingReference": buildingReference},
      success: function(text){
      }
    });
  }

  //Vélo perso, POUR L'INSTANT NE PAS TOUCHER  + calcul de km depuis le premier janvier
  function get_address_domicile(){
    <?php
    $sql = "select aa.EMAIL, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY from customer_referential aa where aa.EMAIL='$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    ?>

    addressDomicile="<?php
    $address=$row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'];
    echo $address;?>";
    return addressDomicile;
  }

  //Vélo perso, POUR L'INSTANT NE PAS TOUCHER + calcul de km depuis le premier janvier
  function get_address_travail(){
    <?php
    $sql = "select aa.EMAIL, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY from customer_referential aa where aa.EMAIL='$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    ?>

    addressTravail="<?php
    $address=$row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'];
    echo $address;?>";
    return addressTravail;
  }

  //Vélo perso + quand tu commande un vélo
  function get_meteo(date, address){
    return $.ajax({
      url: 'include/meteo.php',
      type: 'post',
      data: { "date": date, "address": address}
    })
  }

  //Vélo perso + quand tu commande un vélo
  function get_travel_time(date, address_start, address_end){

    return $.ajax({
      url: 'include/get_directions.php',
      type: 'post',
      data: {"date": date, "address_start": address_start, "address_end": address_end},
      success: function(response){
      }
    });
  }

  //Vélo perso + quand tu commande un vélo
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

  //Vélo perso + calendrier pour définir le nb de kilomètre, tu clique quand tu es allé au travail à vélo
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

  //Module CASHFLOW ==> Cout ==> retrieve cost
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
    });
  }

  //Module gérer les clients ==> id d'un client ==> ajouter une offre
  function add_offer(company){
    $('#companyHiddenOffer').val(company);
    $('#widget-offerManagement-form select[name=type]').val("leasing");
    $('#widget-offerManagement-form input[name=action]').val("add");
    $('#widget-offerManagement-form input').attr("readonly", false);
    $('#widget-offerManagement-form textarea').attr("readonly", false);
    $('#widget-offerManagement-form select').attr("readonly", false);
    document.getElementById('widget-offerManagement-form').reset();
  }

  //Module gérer les clients ==> un client ==> modifier un contact
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

  //Module gérer les clients ==> un client ==> supprimer un de la base de donnée, ne touche pas le front end contact
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

  //Module gérer les clients ==> un client ==> list les contacts
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

  //Module gérer les clients ==> un client ==> reset contact
  function initialize_company_contacts (){
    $('.clientContactZone').html('');
  }

  //Module gérer les clients ==> un client ==> Modifie le front end quand tu delete un contact
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

  //Quand tu clique sur l'ID d'une tache, est appelé dans /js/tasks_management.js & /js/company_management.js NE PAS TOUCHER
  function list_kameobikes_member(){
    $('#widget-addActionCompany-form select[name=owner]').find('option').remove().end();

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
    });
  }

  //Module gérer les clients ==> ajouter un batiment à un client
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

  //GENERAL
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
                <div class="notificationHeading"> <!-- NOTIFICATIONS -->
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
              <div class="col-md-12">
                <div id="tabs-05c" class="tabs color tabs radius">
                  <ul id="mainTab" class="tabs-navigation">
                    <li class="fr hidden orderBike"><a href="#orderBike" class="orderBike"><i class="fa fa-user"></i>Commander</a></li>
                    <li class="en hidden orderBike"><a href="#orderBike" class="orderBike"><i class="fa fa-user"></i>Order</a></li>
                    <li class="nl hidden orderBike"><a href="#orderBike" class="orderBike"><i class="fa fa-user"></i>Order</a></li>
                    <li class="reserver active fr"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Réserver un vélo</a> </li>
                    <li class="reserver active en"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Book a bike</a> </li>
                    <li class="reserver active nl"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Boek een fiets</a> </li>
                    <li class="fr"><a href="#reservations" class="reservations"><i class="fa fa-check-square-o"></i>Vos réservations</a> </li>
                    <li class="en"><a href="#reservations" class="reservations"><i class="fa fa-check-square-o"></i>Your bookings</a> </li>
                    <li class="nl"><a href="#reservations" class="reservations"><i class="fa fa-check-square-o"></i>Uw boekingen</a> </li>
                    <li class="fr hidden fleetmanager"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-user"></i>Fleet manager</a> </li>
                    <li class="en hidden fleetmanager"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-user"></i>Fleet manager</a> </li>
                    <li class="nl hidden fleetmanager"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-user"></i>Fleet manager</a> </li>
                    <!--<li class="fr"><a href="#routes" class="routes"><i class="fa fa-road"></i>Itinéraires</a> </li>
                    <li class="en"><a href="#routes" class="routes"><i class="fa fa-road"></i>Roads</a> </li>
                    <li class="nl"><a href="#routes" class="routes"><i class="fa fa-road"></i>Routes</a> </li>-->
                  </ul>
                  <div class="tabs-content">
                    <div class="tab-pane" id="orderBike"> <!-- TAB1: COMMANDER UN VELO -->
                      <div class="bikeOrdered hidden">
                            <h4 class="text-green fr">Votre commande - Vélo</h4>
                            <h4 class="text-green en">Your order - Bike</h4>
                            <h4 class="text-green nl">Your order - Bike</h4>
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <ul>
                                        <li class="fr"><strong>Marque :</strong> <span class="brand"></span></li>
                                        <li class="nl"><strong>Brand :</strong> <span class="brand"></span></li>
                                        <li class="en"><strong>Brand :</strong> <span class="brand"></span></li>
                                        <li class="fr"><strong>Modèle :</strong> <span class="model"></span></li>
                                        <li class="en"><strong>Model:</strong> <span class="model"></span></li>
                                        <li class="nl"><strong>Model:</strong> <span class="model"></span></li>
                                        <li class="fr"><strong>Taille :</strong> <span class="size"></span></li>
                                        <li class="nl"><strong>Size :</strong> <span class="size"></span></li>
                                        <li class="nl"><strong>Size :</strong> <span class="size"></span></li>
                                        <li class="fr"><strong>Couleur :</strong> <span class="color"></span></li>
                                        <li class="en"><strong>Couleur :</strong> <span class="color"></span></li>
                                        <li class="nl"><strong>Couleur :</strong> <span class="color"></span></li>
                                        <li class="fr"><strong>Statut :</strong> <span class="status"></span></li>
                                        <li class="nl"><strong>Status :</strong> <span class="status"></span></li>
                                        <li class="en"><strong>Status :</strong> <span class="status"></span></li>
                                    </ul>
                                    <strong>Remarque : </strong><br/>
                                    <p class="remark"></p>
                                </div>
                                <div class="col-sm-6">
                                    <img class="image" title="Image commande" alt="image commande" width='100%' style="border: 1px solid #555;" />
                                </div>
                            </div>
                            <div class="seperator"></div>
                            <h4 class="text-green fr">Votre commande - Test</h4>
                            <h4 class="text-green en">Your order - Test</h4>
                            <h4 class="text-green nl">Your order - Test</h4>
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <ul>
                                        <li class="fr"><strong>Date pour le test :</strong> <span class="testDate"></span></li>
                                        <li class="nl"><strong>Testing date :</strong> <span class="testDate"></span></li>
                                        <li class="en"><strong>Testing date :</strong> <span class="testDate"></span></li>
                                        <li class="fr"><strong>Lieu du test :</strong> <span class="testPlace"></span></li>
                                        <li class="nl"><strong>Lieu du test :</strong> <span class="testPlace"></span></li>
                                        <li class="en"><strong>Lieu du test :</strong> <span class="testPlace"></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="seperator"></div>
                            <h4 class="text-green fr">Votre commande - Livraison</h4>
                            <h4 class="text-green en">Your order - Livraison</h4>
                            <h4 class="text-green nl">Your order - Livraison</h4>
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <ul>
                                        <li class="fr"><strong>Date estimée de livraison :</strong> <span class="deliveryDate"></span></li>
                                        <li class="nl"><strong>Estimated delivery date :</strong> <span class="deliveryDate"></span></li>
                                        <li class="en"><strong>Estimated delivery date :</strong> <span class="deliveryDate"></span></li>
                                        <li class="fr"><strong>Lieu de livraison :</strong> <span class="deliveryPlace"></span></li>
                                        <li class="nl"><strong>Delivery address :</strong> <span class="deliveryPlace"></span></li>
                                        <li class="en"><strong>Delivery address :</strong> <span class="deliveryPlace"></span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="separator"></div>

                        <div id="gridForCatalog" class="gridForCatalog" data-example-id="contextual-table" class="bs-example">
                            <div class="grid">
                            </div>
                        </div>

                        <div class="separator gridForCatalog"></div>

                        <div class="mesgs col-sm-12">
                          <h4 class="text-green fr">Une question sur nos vélos ? Echangez avec notre expert</h4>
                          <h4 class="text-green nl">Any question about our bikes ? Chat with our expert !</h4>
                          <h4 class="text-green en">Any question about our bikes ? Chat with our expert !</h4>
                          <div class="msg_history">
                              <span id="divChatCommand"></span>
                          </div>
                          <div class="type_msg">
                            <div class="input_msg_write">
                              <input type="text" class="write_msg" placeholder="Type a message" />
                              <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="tab-pane active" id="reserver"> <!-- TAB2: RÉSERVER UN VELO -->
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
                        if (response.clientConditions.cafetaria == "Y"){
                            $(".orderBike").removeClass("hidden");
                        }
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
                                    <div class=\"effect social-links\"> <img src=\"images_bikes/"+text.bike[i].img+".jpg\" alt=\"image\" />\
                                    <div class=\"image-box-content\">\
                                    <p> <a href=\"images_bikes/"+text.bike[i].img+".jpg\" data-lightbox-type=\"image\" title=\"\"><i class=\"fa fa-expand\"></i></a> </p>\
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
                    <div class="tab-pane" id="reservations"> <!-- TAB3: VOS RÉSERVATIONS -->
                      <div data-example-id="contextual-table" class="bs-example">
                        <span id="historicBookings"></span>
                      </div>
                      <div class="seperator"></div>
                      <div data-example-id="contextual-table" class="bs-example">
                        <span id="futureBookings"></span>
                      </div>
                    </div>
                    <div class="tab-pane" id="fleetmanager"> <!-- TAB4: FLEET MANAGET / A CORRIGER -->
                        <h4 class="fr">Votre flotte</h4>
                        <br/><br/>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#BikesListing" class="clientBikesManagerClick" href="#" >
                                  <i class="fa fa-bicycle"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="counterBike" style="color:#3cb395"></div>
                              <p>Nombre de vélos</p>
                            </div>
                          </div>
                          <div class="seperator seperator-small visible-xs"><br/><br/></div>
                          <div class="col-md-4">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#usersListing" class="usersManagerClick" href="#" >
                                  <i class="fa fa-users"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="counterUsers" style="color:#3cb395"></div>
                              <p>Nombre d'utilisateurs</p>
                            </div>
                          </div>
                          <div class="seperator seperator-small visible-xs"><br/><br/></div>
                          <div class="col-md-4">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#ReservationsListing" href="#">
                                  <i class="fa fa-calendar-plus-o reservationlisting"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="counterBookings" style="color:#3cb395"></div>
                              <p>Nombre de réservations sur le mois passé</p>
                            </div>
                          </div>
                        </div>
                        <div class="separator"></div>
                        <h4 class="fr">Réglages</h4>
                        <h4 class="en">Settings</h4>
                        <h4 class="en">Settings</h4>
                        <br/><br/>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#conditionListing" href="#" >
                                  <i class="fa fa-cog"></i>
                                </a>
                              </div>
                              <div class="counter bold" style="color:#3cb395"></div>
                              <p>Modifier les réglages</p>
                            </div>
                          </div>
                        </div>
                        <div class="separator"></div>
                        <h4 class="fr hidden administrationKameo">Administration Kameo</h4>
                        <h4 class="en hidden administrationKameo">Kameo administration</h4>
                        <h4 class="en hidden administrationKameo">Kameo administration</h4>
                        <br/><br/>
                        <div class="row">
                          <div class="col-md-4 hidden" id="clientManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#companyListing" href="#" class="clientManagerClick" >
                                  <i class="fa fa-users"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="counterClients" style="color:#3cb395"></div>
                              <p>Gérer les clients</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="orderManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#ordersListing" href="#" class="ordersManagerClick" >
                                  <i class="fa fa-users"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="counterOrders" style="color:#3cb395"></div>
                              <p>Gérer les commandes</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="portfolioManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#portfolioManager" href="#" class="portfolioManagerClick">
                                  <i class="fa fa-book"></i>
                                </a>
                              </div>
                              <div class="counter bold" id='counterBikePortfolio' style="color:#3cb395"></div>
                              <p>Gérer le catalogue</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="bikesManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#BikesListingAdmin" href="#" class="bikeManagerClick">
                                  <i class="fa fa-bicycle"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="counterBikeAdmin" style="color:#3cb395"></div>
                              <p>Gérer les vélos</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="boxesManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#boxesListing" href="#" class="boxManagerClick">
                                  <i class="fa fa-cube"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="counterBoxes" style="color:#3cb395"></div>
                              <p>Gérer les Bornes</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="tasksManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#tasksListing" href="#" class="tasksManagerClick">
                                  <i class="fa fa-tasks"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="counterTasks" style="color:#3cb395"></div>
                              <p>Gérer les Actions</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="cashFlowManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#cashListing" href="#" id="offerManagerClick">
                                  <i class="fa fa-money"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="cashFlowSpan" style="color:#3cb395"></div>
                              <p>Vue sur le cash-flow</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="feedbacksManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#feedbacksListing" href="#" class="feedbackManagerClick">
                                  <i class="fa fa-comments"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="counterFeedbacks" style="color:#3cb395"></div>
                              <p>Vue sur les feedbacks</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="maintenanceManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#maintenanceListing" href="#" class="maintenanceManagementClick">
                                  <i class="fa fa-wrench"></i>
                                </a>
                              </div>
                              <div class="counter bold" id="counterMaintenance" style="color:#3cb395"></div>
                              <div class="counter bold" id="counterMaintenance2" style="color:#3cb395"></div>
                              <p>Vue sur les entretiens</p>
                            </div>
                          </div>
                          <div class="col-md-4 hidden" id="dashBoardManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" class="dashboardManagementClick" data-target="#dashboard" href="#" >
                                  <i class="fa fa-dashboard"></i>
                                </a>
                              </div>
                              <div class="counter bold" id='errorCounter' style="color:#3cb395"></div>
                              <p>Dashboard</p>
                            </div>
                          </div>
                        </div>
                        <div class="separator kameo"></div>
                        <h4 class="fr billsTitle hidden">Factures</h4>
                        <h4 class="en billsTitle hidden">Billing</h4>
                        <h4 class="nl billsTitle hidden">Billing</h4><br/><br />
                        <div class="row">
                          <div class="col-md-4 hidden" id="billsManagement">
                            <div class="icon-box medium fancy">
                              <div class="icon bold" data-animation="pulse infinite">
                                <a data-toggle="modal" data-target="#billingListing" href="#" class="billsManagerClick">
                                  <i class="fa fa-folder-open-o"></i>
                                </a>
                              </div>
                              <div class="counter bold" id='counterBills' style="color:#3cb395"></div>
                              <p>Aperçu des factures</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12" id="progress-bar-bookings"></div>
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

              <br/>

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

              <!-- METEO -->
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
                  get_meteo(timestamp, addressDomicile).done(function(response){
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

                      get_travel_time("now", addressDomicile, addressTravail).done(function(response){
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

            <?php }?>
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

<?php } ?>

<!-- TAB2: RESERVER UN VELO ==> RESUME DE RESERVATION -->
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

<!-- USERS WIDGET -->
<?php
  //php's $user var is needed here.
  include 'include/vues/tabs/fleet_manager/fleet/widgets/users/users.html';
  include 'include/vues/tabs/fleet_manager/fleet/widgets/users/add_user.html';
  include 'include/vues/tabs/fleet_manager/fleet/widgets/users/delete_user.html';
  include 'include/vues/tabs/fleet_manager/fleet/widgets/users/reactivate_user.html';
  include 'include/vues/tabs/fleet_manager/fleet/widgets/users/update_user.html';
?>
<!-- BIKES WIDGET -->
<?php
  include 'include/vues/tabs/fleet_manager/fleet/widgets/bikes/bikes.html';
  include 'include/vues/tabs/fleet_manager/fleet/widgets/bikes/bike_localization.html';
  include 'include/vues/tabs/fleet_manager/fleet/widgets/bikes/bike_details.html';
  include 'include/vues/tabs/fleet_manager/fleet/widgets/bikes/update_bike.html';
?>
<!-- MANAGE BIKES WIDGET -->
<?php
  include 'include/vues/tabs/fleet_manager/admin/widgets/bikes/bikes.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/bikes/manage_bike.html';
?>
<!-- BOXES WIDGET -->
<?php
  include 'include/vues/tabs/fleet_manager/admin/widgets/boxes/boxes.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/boxes/manage_box.html';
?>
<!-- TASKS WIDGET -->
<?php
  include 'include/vues/tabs/fleet_manager/admin/widgets/tasks/tasks.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/tasks/manage_task.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/tasks/update_task.html';
?>
<!-- CASHFLOW WIDGET -->
<?php include 'include/vues/tabs/fleet_manager/admin/widgets/cashflow/cashflow.html'; ?>
<!-- MANAGE FEEDBACKS WIDGET -->
<?php
  include 'include/vues/tabs/fleet_manager/admin/widgets/feedbacks/feedbacks.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/feedbacks/manage_feedback.html';
?>
<!-- MAINTENANCE WIDGET -->
<?php include 'include/vues/tabs/fleet_manager/admin/widgets/maintenance/maintenance.html'; ?>
<!-- CONDITIONS WIDGET -->
<?php
  include 'include/vues/tabs/fleet_manager/settings/widgets/conditions/conditions.html';
  include 'include/vues/tabs/fleet_manager/settings/widgets/conditions/update_condition.html';
?>
<!-- CUSTOMERS WIDGET -->
<?php
  include 'include/vues/tabs/fleet_manager/admin/widgets/customers/customers.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/customers/add_customer.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/customers/customer_details.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/customers/offers/add_offer.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/customers/offers/add_offer_template.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/customers/buildings/add_building.html';
?>
<!-- ORDERS WIDGET -->
<?php
  include 'include/vues/tabs/fleet_manager/admin/widgets/orders/orders.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/orders/manage_order.html';
?>
<!-- PORTFOLIO WIDGET -->
<?php
  /**
  **@TODO: Add a delete confirmation widget
  **/
  include 'include/vues/tabs/fleet_manager/admin/widgets/portfolio/portfolio.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/portfolio/update_portfolio.html';
  include 'include/vues/tabs/fleet_manager/admin/widgets/portfolio/add_portfolio.html';
?>
<!-- BILLS WIDGET -->
<?php
  include 'include/vues/tabs/fleet_manager/bills/widgets/bills/bills.html';
  include 'include/vues/tabs/fleet_manager/bills/widgets/bills/add_bill.html';
?>
<!-- DASHBOARD WIDGET-->
<?php include 'include/vues/tabs/fleet_manager/admin/widgets/dashboard/dashboard.html'; ?>
<script type="text/javascript" src="include/vues/tabs/fleet_manager/admin/widgets/dashboard/dashboard.js"></script>
<!-- RESERVATIONS WIDGET-->
<?php
  include 'include/vues/tabs/fleet_manager/fleet/widgets/reservations/reservations.html';
  include 'include/vues/tabs/fleet_manager/fleet/widgets/reservations/manage_reservation.html';
  include 'include/vues/tabs/fleet_manager/fleet/widgets/reservations/delete_reservation.html';
  /**
  ** @TODO: include 'include/vues/tabs/fleet_manager/fleet/widgets/reservations/update_reservation.html';
  **/
?>

<!-- DUNNO -->
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

<!-- ORDER: ORDER -->
<?php
  include 'include/vues/tabs/order/widgets/order.html';
?>

<!-- DUNNO -->
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

<!-- DUNNO -->
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

<?php
  include 'include/vues/widgets/feedback/feedback.html';
?>

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

<?php
  if($user!=NULL){
    //php's $row var and tons of stuff is needed here
    include 'include/vues/widgets/informations/update_informations.html';
  }
?>

<?php
  //php's $contractNumber var is needed here
  include 'include/vues/widgets/support/support.html';
  include 'include/vues/widgets/support/contact_support.html';
  include 'include/vues/widgets/support/contact_maintenance.html';
?>

<div class="loader"><!-- Place at bottom of page --></div>

<!-- FOOTER -->
<footer class="background-dark text-grey" id="footer">
  <div class="footer-content">
    <div class="container">
      <br><br>
        <div class="row text-center">
        <div class="copyright-text text-center">
          <ins>Kameo Bikes SPRL</ins>
					<br>BE 0681.879.712
					<br>+32 498 72 75 46
        </div>
				<br>
        <div class="social-icons center">
					<ul>
						<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
						<li class="social-linkedin"><a href="https://www.linkedin.com/company/kameobikes/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
					</ul>
			  </div>
				<div>
          <a href="faq.php" class="text-green text-bold"><h3 class="text-green">FAQ</h3></a>
          <!-- | <a href="bonsplans.php" class="text-green text-bold">Les bons plans</a>-->
        </div>
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

<script type="text/javascript" src="js/language.js">
  displayLanguage();
</script>


</body>
<?php
$conn->close();
ob_end_flush();
?>
</html>
