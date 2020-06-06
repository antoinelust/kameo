function list_errors(){
  $.ajax({
      url: 'include/error_management.php',
      method: 'get',
      data: {'action' : 'list', 'item': 'bikes'},
      success: function(response){
        if (response.response == "error") {
          console.log(response.message);
        }else{
            var i=0;
            var j=0;
            var dest="<table class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th>ID</th><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th>Description</th></thead><tbody>";
            while (i< response.bike.img.number){   
                var bike=response.bike.img[i];
                var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+bike.frameNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"set_required_image('false')\">"+bike.frameNumber+"</a></td><td>Image manquante sur le vélo "+bike.frameNumber+"</td><td></tr>";
                dest=dest.concat(temp);
                i++;
            }
            
            
            while (j< response.bike.stock.number){   
                var bike=response.bike.stock[i];
                
                var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+bike.frameNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"set_required_image('false')\">"+bike.frameNumber+"</a></td><td>Le vélo "+bike.frameNumber+" ne peut pas être défini comme vélo de stock en dehors de la société Kameo</td><td></tr>";
                dest=dest.concat(temp);
                i++;
                j++;
            }
                        
            dest=dest.concat("</tbody></table>");
            $('#dashboardBodyBikes').html(dest);
            
            
            var i=0;
            var dest="<table class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th>ID</th><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th>Description</th></thead><tbody>";
                                    
            while (i< response.bike.bill.number){   
                var bill=response.bike.bill[i];
                var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+bill.bikeNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"set_required_image('false')\">"+bill.bikeNumber+"</a></td><td>"+bill.description+"</td><td></tr>";
                dest=dest.concat(temp);
                i++;
            }
            
            
                        
            dest=dest.concat("</tbody></table>");
            $('#dashboardBodyBills').html(dest);
            
            var i=0;
            var dest="<table class=\"table table-condensed\"  data-order='[[ 0, \"asc\" ]]'><thead><tr><th>ID</th><th scope=\"col\"><span class=\"fr-inline\">Référence</span><span class=\"en-inline\">Bike Number</span><span class=\"nl-inline\">Bike Number</span></th><th>Description</th></thead><tbody>";
                                    
            while (i< response.company.img.number){   
                var company=response.company.img[i];
                var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td><a class=\"updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+company.id+"\" data-toggle=\"modal\" href=\"#\" onclick=\"set_required_image('false')\">"+company.name+"</a></td><td>Image manquante pour la société "+company.name+"</td></tr>";
                dest=dest.concat(temp);
                i++;
            }
            var j=0;
            while (j< response.company.action.number){   
                var action=response.company.action[j];
                var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td><a href=\"#\" class=\"updateAction\" data-target=\"#updateAction\" data-toggle=\"modal\" name=\""+action.id+"\">"+action.id+"</a></td><td>"+action.description+"</td></tr>";
                dest=dest.concat(temp);
                j++;
                i++;
            }
            
            dest=dest.concat("</tbody></table>");
            $('#dashboardBodyCompanies').html(dest);
            
            $(".updateAction").click(function() {
                construct_form_for_action_update(this.name);
            });
            
            
            $('.dashboardBikes').html("Vélos ("+response.bike.img.number+")");
            $('.dashboardBills').html("Factures ("+response.bike.bill.number+")");
            $('.dashboardCompanies').html("Sociétés ("+(response.company.img.number+response.company.action.number)+")");
            
            
            
            $(".updateBikeAdmin").click(function() {
                construct_form_for_bike_status_updateAdmin(this.name);
                $('#widget-bikeManagement-form input').attr('readonly', false);
                $('#widget-bikeManagement-form select').attr('readonly', false);
                $('.bikeManagementTitle').html('Modifier un vélo');
                $('.bikeManagementSend').removeClass('hidden');
                $('.bikeManagementSend').html('<i class="fa fa-plus"></i>Modifier');
            });
            
            

            if((response.bike.img.number+response.bike.bill.number+response.company.img.number+response.company.action.number)==0){
                document.getElementById('errorCounter').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\"0\" data-from=\"0\" data-seperator=\"true\">0</span>";
                $('#errorCounter').css('color', '#3cb395');                
            }else{
                document.getElementById('errorCounter').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+(response.bike.img.number+response.bike.bill.number+response.company.img.number+response.company.img.number)+"\" data-from=\"0\" data-seperator=\"true\">"+(response.bike.img.number+response.bike.bill.number+response.company.action.number)+"</span>";
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

function initialize_task_owner_sales_selection(){
    
  $.ajax({
      url: 'include/sales_management.php',
      method: 'get',
      data: {'action' : 'list', 'item': 'owners'},
      success: function(response){
        if (response.response == "error"){
          console.log(response.message);
        }else{
            $('.taskOwnerSalesSelection')
                .find('option')
                .remove()
                .end()
            ;
            $('.taskOwnerSalesSelection').append("<option value='*'>Tous<br>");

            var i=0;
            while (i < response.ownerNumber){
                $('.taskOwnerSalesSelection').append("<option value="+response.owner[i].email+">"+response.owner[i].firstName+" "+response.owner[i].name+"<br>");
                i++;
            }
            list_sales('*', $('.form_date_start_sell').data("datetimepicker").getDate(), $('.form_date_end_sell').data("datetimepicker").getDate())            
        }
      }
  });
    
    
}

function list_sales(owner, start, end){
    
    
    dateStartString=start.getFullYear()+"-"+("0" + (start.getMonth() + 1)).slice(-2)+"-"+("0" + start.getDate()).slice(-2);
    dateEndString=end.getFullYear()+"-"+("0" + (end.getMonth() + 1)).slice(-2)+"-"+("0" + end.getDate()).slice(-2);
    
    
    $.ajax({
        url: 'include/sales_management.php',
        method: 'get',
        data: {'action' : 'list', 'item': 'sales', 'owner': owner, 'start': dateStartString, 'end': dateEndString},
        success: function(response){
        if (response.response == "error"){
          console.log(response.message);
        }else{
            var i=0;
            var dest="<table class=\"table table-condensed\"><thead><tr><th>ID</th><th scope=\"col\"><span>Date</span></th><th>Owner</th><th>Description</th><th>Points</th></thead><tbody>";
            var totalPoints=0;
            while (i< response.sales.contact.number){
                var contact=response.sales.contact[i];                
                if(contact.type=="premier contact"){
                    var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td>"+contact.date.shortDate()+"</td><td>"+contact.owner+"</td><td><strong>Type:</strong> Prise de contact pour entreprise <a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+contact.companyID+"\">"+contact.company+"</a><br/><strong>Description :</strong> "+contact.description.replace(/(\r\n|\n|\r)/g,"<br />")+"</td><td>5</td></tr>";
                    totalPoints += 5;
                }else if(contact.type="rappel"){
                    var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td>"+contact.date.shortDate()+"</td><td>"+contact.owner+"</td><td><strong>Type:</strong> Relance pour entreprise <a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+contact.companyID+"\">"+contact.company+"</a><br/><strong>Description :</strong> "+contact.description.replace(/(\r\n|\n|\r)/g,"<br />")+"</td><td>1</td></tr>";
                    totalPoints += 1;

                }else if(contact.type="plan rdv"){
                    var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td>"+contact.date.shortDate()+"</td><td>"+contact.owner+"</td><td><strong>Type:</strong> Planficiation de rdv pour entreprise <a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+contact.companyID+"\">"+contact.company+"</a><br/><strong>Description :</strong> "+contact.description.replace(/(\r\n|\n|\r)/g,"<br />")+"</td><td>10</td></tr>";
                    totalPoints += 10;
                }else{
                    var temp="<tr><td scope=\"row\">"+(i+1)+"</td><td>"+contact.date.shortDate()+"</td><td>"+contact.owner+"</td><td><strong>Type:</strong> Type inconnu pour entreprise <a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+contact.companyID+"\">"+contact.company+"</a><br/><strong>Description :</strong> "+contact.description.replace(/(\r\n|\n|\r)/g,"<br />")+"</td><td>0</td></tr>";
                }
                dest=dest.concat(temp);


                i++;
            }

            dest=dest.concat("</tbody></table>");
            dest=dest.concat("<p>Nombre de points au total : <strong>"+totalPoints+"</strong></p>");
            $('#dashboardBodySellsTable').html(dest);
            
            $(".internalReferenceCompany").click(function() {
                get_company_details(this.name, email, true);
            });
            
        }
        }
    });
}