$( ".fleetmanager" ).click(function() {
	initializeFields();
    list_errors();
});





var today=new Date();
var dashboard_tabs = [$('#dashboardBodyBills'), $('#dashboardBodyBikes'), $('#dashboardBodySells'), $('#dashboardBodyCompanies')];

$('.form_date_start_sell').datetimepicker({language: 'fr', weekStart: 1, todayBtn:  1, autoclose: 1, todayHighlight: 1,
startView: 2, minView: 2, forceParse: 0
});

$('.form_date_end_sell').datetimepicker({language: 'fr', weekStart: 1, todayBtn:  1, autoclose: 1, todayHighlight: 1,
startView: 2, minView: 2, forceParse: 0
});

$(".form_date_end_sell").data("datetimepicker").setDate(today);
today.setDate(today.getDate()-7);
$(".form_date_start_sell").data("datetimepicker").setDate(today);

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
	$('.dashboardTitle').html("Erreurs à corriger - Vélos");
	dashboard_tabs.forEach(tab => tab.fadeOut());
	$('#dashboardBodyBikes').fadeIn();
});
$( ".dashboardBills" ).click(function() {
	$('.dashboardTitle').html("Erreurs à corriger - Factures");
	dashboard_tabs.forEach(tab => tab.fadeOut());
	$('#dashboardBodyBills').fadeIn();
});
$( ".dashboardSells" ).click(function() {
	$('.dashboardTitle').html("Suivi prospection commerciale");
	dashboard_tabs.forEach(tab => tab.fadeOut());
	$('#dashboardBodySells').fadeIn();
});
$( ".dashboardCompanies" ).click(function() {
	$('.dashboardTitle').html("Erreurs à corriger - Sociétés");
	dashboard_tabs.forEach(tab => tab.fadeOut());
	$('#dashboardBodyCompanies').fadeIn();
});
function list_errors() {
	$.ajax({
		url: 'apis/Kameo/error_management.php',
		method: 'get',
		data: {
			'action': 'list',
			'item': 'bikes'
		},
		success: function(response) {
			if (response.response == "error") {
				console.log(response.message);
			} else {
				var i = 0;
				var j = 0;
				var dest = "<table class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th>ID</th><th scope=\"col\">Référence</th><th>Description</th></thead><tbody>";

				while (j < response.bike.selling.number) {
					var bike = response.bike.selling[j];
					if (bike.frameNumber == null) {
						var bikeDescription = "N/A - " + bike.bikeID;
					} else {
						var bikeDescription = bike.bikeID + " - " + bike.frameNumber;
					}
					var temp = "<tr><td scope=\"row\">" + (i + 1) + "</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\"" + bike.bikeID + "\" data-toggle=\"modal\" href=\"#\">" + bikeDescription + "</a></td><td>Le vélo " + bikeDescription + " a été vendu mais la date de vente n'est pas mentionnée</td><td></tr>"; //onclick=\"set_required_image('false')\"
					dest = dest.concat(temp);
					i++;
					j++;
				}
				j=0;

				while (j < response.bike.sellingCompany.number) {
					var bike = response.bike.sellingCompany[j];
					if (bike.frameNumber == null) {
						var bikeDescription = "N/A - " + bike.bikeID;
					} else {
						var bikeDescription = bike.bikeID + " - " + bike.frameNumber;
					}
					var temp = "<tr><td scope=\"row\">" + (i + 1) + "</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\"" + bike.bikeID + "\" data-toggle=\"modal\" href=\"#\">" + bikeDescription + "</a></td><td>Le vélo " + bikeDescription + " a été vendu, il ne peut pas être assigné à Kameo</td><td></tr>"; //onclick=\"set_required_image('false')\"
					dest = dest.concat(temp);
					i++;
					j++;
				}
				j=0;

				while (j < response.bike.stock.number) {
					var bike = response.bike.stock[j];
					if (bike.frameNumber == null) {
						var bikeDescription = "N/A - " + bike.bikeID;
					} else {
						var bikeDescription = bike.bikeID + " - " + bike.frameNumber;
					}
					var temp = "<tr><td scope=\"row\">" + (i + 1) + "</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\"" + bike.bikeID + "\" data-toggle=\"modal\" href=\"#\">" + bikeDescription + "</a></td><td>Le vélo " + bikeDescription + " ne peut pas être défini comme vélo de stock en dehors de la société Kameo</td><td></tr>"; //onclick=\"set_required_image('false')\"
					dest = dest.concat(temp);
					i++;
					j++;
				}
				dest = dest.concat("</tbody></table>");
				$('#dashboardBodyBikes').html(dest);
				var i = 0;
				var dest = "<table class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th>ID</th><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th>Description</th></thead><tbody>";
				while (i < response.bike.bill.number) {
					var bill = response.bike.bill[i];
					if (bill.bikeNumber == null) {
						var bikeDescription = bill.bikeID + " - N/A";
					} else {
						var bikeDescription = bill.bikeID + " - " + bill.bikeNumber;
					}
					var temp = "<tr><td scope=\"row\">" + (i + 1) + "</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\"" + bill.bikeID + "\" data-toggle=\"modal\" href=\"#\">" + bikeDescription + "</a></td><td>" + bill.description + "</td><td></tr>";
					//onclick=\"set_required_image('false')\"
					dest = dest.concat(temp);
					i++;
				}
				dest = dest.concat("</tbody></table>");
				$('#dashboardBodyBills').html(dest);
				var i = 0;
				var dest = "<table class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th>ID</th><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th>Description</th></thead><tbody>";
				while (i < response.company.img.number) {
					var company = response.company.img[i];
					var temp = "<tr><td scope=\"row\">" + (i + 1) + "</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\"" + company.id + "\" data-toggle=\"modal\" href=\"#\">" + company.name + "</a></td><td>Image manquante pour la société " + company.name + "</td></tr>";
					//onclick=\"set_required_image('false')\"
					dest = dest.concat(temp);
					i++;
				}
				var j = 0;
				while (j < response.company.action.number) {
					var action = response.company.action[j];
					var temp = "<tr><td scope=\"row\">" + (i + 1) + "</td><td><a href=\"#\" class=\"updateAction\" data-target=\"#updateAction\" data-toggle=\"modal\" name=\"" + action.id + "\">" + action.id + "</a></td><td>" + action.description + "</td></tr>";
					dest = dest.concat(temp);
					j++;
					i++;
				}
				dest = dest.concat("</tbody></table>");
				$('#dashboardBodyCompanies').html(dest);
				$(".updateAction").click(function() {
					construct_form_for_action_update(this.name);
				});
				$('.dashboardBikes').html("Vélos ("+(response.bike.selling.number + response.bike.sellingCompany.number)+")");
				$('.dashboardBills').html("Factures (" + response.bike.bill.number + ")");
				$('.dashboardCompanies').html("Sociétés (" + (response.company.img.number + response.company.action.number) + ")");
				$(".updateBikeAdmin").click(function() {
					construct_form_for_bike_status_updateAdmin(this.name);
					$('#widget-bikeManagement-form input').attr('readonly', false);
					$('#widget-bikeManagement-form select').attr('readonly', false);
					$('.bikeManagementTitle').html('Modifier un vélo');
					$('.bikeManagementSend').removeClass('hidden');
					$('.bikeManagementSend').html('<i class="fa fa-plus"></i>Modifier');
				});
				if ((response.bike.selling.number + response.bike.bill.number + response.company.img.number + response.company.action.number) == 0) {
					document.getElementById('errorCounter').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\"0\" data-from=\"0\" data-seperator=\"true\">0</span>";
					$('#errorCounter').css('color', '#3cb395');
				} else {
					document.getElementById('errorCounter').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\"" + (response.bike.selling.number + response.bike.sellingCompany.number + response.bike.bill.number + response.company.img.number + response.company.action.number) + "\" data-from=\"0\" data-seperator=\"true\">" + (response.bike.selling.number + response.bike.sellingCompany.number + response.bike.bill.number + response.company.img.number + response.company.action.number) + "</span>";
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

function initialize_task_owner_sales_selection() {
	$.ajax({
		url: 'apis/Kameo/sales_management.php',
		method: 'get',
		data: {
			'action': 'list',
			'item': 'owners'
		},
		success: function(response) {
			if (response.response == "error") {
				console.log(response.message);
			} else {
				$('.taskOwnerSalesSelection').find('option').remove().end();
				$('.taskOwnerSalesSelection').append("<option value='*'>Tous<br>");
				for (i = 0; i < response.ownerNumber; i++) {
					$('.taskOwnerSalesSelection').append("<option value=" + response.owner[i].email + ">" + response.owner[i].firstName + " " + response.owner[i].name + "<br>");
					i++;
				}
				list_sales('*', $('.form_date_start_sell').data("datetimepicker").getDate(), $('.form_date_end_sell').data("datetimepicker").getDate());
			}
		}
	});
}

function list_sales(owner, start, end) {
	dateStartString = start.getFullYear() + "-" + ("0" + (start.getMonth() + 1)).slice(-2) + "-" + ("0" + start.getDate()).slice(-2);
	dateEndString = end.getFullYear() + "-" + ("0" + (end.getMonth() + 1)).slice(-2) + "-" + ("0" + end.getDate()).slice(-2);
	$.ajax({
		url: 'apis/Kameo/sales_management.php',
		method: 'get',
		data: {
			'action': 'list',
			'item': 'sales',
			'owner': owner,
			'start': dateStartString,
			'end': dateEndString
		},
		success: function(response) {
			if (response.response == "error") {
				console.log(response.message);
			} else {
				var i = 0;
				var dest = "<table class=\"table table-condensed\"><thead><tr><th>ID</th><th scope=\"col\"><span>Date</span></th><th>Owner</th><th>Description</th><th>Points</th></thead><tbody>";
				var totalPoints = 0;
				while (i < response.sales.contact.number) {
					var contact = response.sales.contact[i];
					if (contact.type == "premier contact") {
						var temp = "<tr><td scope=\"row\">" + (i + 1) + "</td><td>" + contact.date.shortDate() + "</td><td>" + contact.owner + "</td><td><strong>Type:</strong> Prise de contact pour entreprise <a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\"" + contact.companyID + "\">" + contact.company + "</a><br/><strong>Description :</strong> " + contact.description.replace(/(\r\n|\n|\r)/g, "<br />") + "</td><td>5</td></tr>";
						totalPoints += 5;
					} else if (contact.type = "rappel") {
						var temp = "<tr><td scope=\"row\">" + (i + 1) + "</td><td>" + contact.date.shortDate() + "</td><td>" + contact.owner + "</td><td><strong>Type:</strong> Relance pour entreprise <a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\"" + contact.companyID + "\">" + contact.company + "</a><br/><strong>Description :</strong> " + contact.description.replace(/(\r\n|\n|\r)/g, "<br />") + "</td><td>1</td></tr>";
						totalPoints += 1;
					} else if (contact.type = "plan rdv") {
						var temp = "<tr><td scope=\"row\">" + (i + 1) + "</td><td>" + contact.date.shortDate() + "</td><td>" + contact.owner + "</td><td><strong>Type:</strong> Planficiation de rdv pour entreprise <a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\"" + contact.companyID + "\">" + contact.company + "</a><br/><strong>Description :</strong> " + contact.description.replace(/(\r\n|\n|\r)/g, "<br />") + "</td><td>10</td></tr>";
						totalPoints += 10;
					} else {
						var temp = "<tr><td scope=\"row\">" + (i + 1) + "</td><td>" + contact.date.shortDate() + "</td><td>" + contact.owner + "</td><td><strong>Type:</strong> Type inconnu pour entreprise <a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\"" + contact.companyID + "\">" + contact.company + "</a><br/><strong>Description :</strong> " + contact.description.replace(/(\r\n|\n|\r)/g, "<br />") + "</td><td>0</td></tr>";
					}
					dest = dest.concat(temp);
					i++;
				}
				dest = dest.concat("</tbody></table>");
				dest = dest.concat("<p>Nombre de points au total : <strong>" + totalPoints + "</strong></p>");
				$('#dashboardBodySellsTable').html(dest);
			}
		}
	});
}
function initializeFields(){

  $('#widget-bikeManagement-form select[name=company]').find('option').remove().end();
  $('#widget-updateAction-form select[name=company]').find('option').remove().end();
  $('#widget-taskManagement-form select[name=company]').find('option').remove().end();
  $('#widget-boxManagement-form select[name=company]').find('option').remove().end();

  $.ajax({
    url: 'apis/Kameo/get_companies_listing.php',
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
    url: 'apis/Kameo/initialize_fields.php',
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
