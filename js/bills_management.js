function get_bills_listing(company, sent, paid, direction, email) {    
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
                var dest3="";
                
                
                if(response.update){

                    var temp="<table id=\"billsListingTable\" class=\"table table-condensed\" data-order='[[ 1, \"desc\" ]]' data-page-length='50'><h4 class=\"fr-inline text-green\">Vos Factures:</h4><h4 class=\"en-inline text-green\">Your Bills:</h4><h4 class=\"nl-inline text-green\">Your Bills:</h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addBill\" data-toggle=\"modal\" onclick=\"create_bill()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter une facture</span></a><thead><tr><th>Type</th><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Date d'initiation</span><span class=\"en-inline\">Generation Date</span><span class=\"nl-inline\">Generation Date</span></th><th><span class=\"fr-inline\">Montant (HTVA)</span><span class=\"en-inline\">Amount (VAT ex.)</span><span class=\"nl-inline\">Amount (VAT ex.)</span></th><th><span class=\"fr-inline\">Communication</span><span class=\"en-inline\">Communication</span><span class=\"nl-inline\">Communication</span></th><th><span class=\"fr-inline\">Envoi ?</span><span class=\"en-inline\">Sent</span><span class=\"nl-inline\">Sent</span></th><th><span class=\"fr-inline\">Payée ?</span><span class=\"en-inline\">Paid ?</span><span class=\"nl-inline\">Paid ?</span></th><th><span class=\"fr-inline\">Limite de paiement</span><span class=\"en-inline\">Limit payment date</span><span class=\"nl-inline\">Limit payment date</span></th><th>Comptable ?</th><th></th></tr></thead><tbody>";
                    var temp3="<table id=\"billsToSendListingTable\" class=\"table table-condensed\" data-order='[[ 1, \"desc\" ]]' data-page-length='50'><thead><tr><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount (VAT ex.)</span><span class=\"nl-inline\">Amount (VAT ex.)</span></th><th><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th>Email</th><th>Prénom</th><th>Nom</th><th></th></tr></thead><tbody>";
                }else{
                    var temp="<table id=\"billsListingTable\" class=\"table table-condensed\" data-order='[[ 1, \"desc\" ]]' data-page-length='50'><h4 class=\"fr-inline text-green\">Vos Factures:</h4><h4 class=\"en-inline text-green\">Your Bills:</h4><h4 class=\"nl-inline text-green\">Your Bills:</h4><br/><thead><tr><th>ID</th><th><span class=\"fr-inline\">Date d'initiation</span><span class=\"en-inline\">Generation Date</span><span class=\"nl-inline\">Generation Date</span></th><th><span class=\"fr-inline\">Montant (HTVA)</span><span class=\"en-inline\">Amount (VAT ex.)</span><span class=\"nl-inline\">Amount (VAT ex.)</span></th><th><span class=\"fr-inline\">Communication</span><span class=\"en-inline\">Communication</span><span class=\"nl-inline\">Communication</span></th><th><span class=\"fr-inline\">Envoyée ?</span><span class=\"en-inline\">Sent ?</span><span class=\"nl-inline\">Sent ?</span></th><th><span class=\"fr-inline\">Payée ?</span><span class=\"en-inline\">Paid ?</span><span class=\"nl-inline\">Paid ?</span></th><th><span class=\"fr-inline\">Limite de paiement</span><span class=\"en-inline\">Limit payment date</span><span class=\"nl-inline\">Limit payment date</span></th></tr></thead><tbody>";
                }
                dest=dest.concat(temp);
                dest3=dest3.concat(temp3);
                

                while (i < response.billNumber){
                    
                    
                    
                    
                    if(response.bill[i].sentDate==null){
                        var sendDate="N/A";
                    }else{
                        var sendDate=response.bill[i].sentDate.shortDate();
                    }
                    if(response.bill[i].paidDate==null){
                        var paidDate="N/A";
                    }else{
                        var paidDate=response.bill[i].paidDate.shortDate();
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
                        var temp="<tr><td class=\"text-green\">IN</td>";
                    }else if(response.update && response.bill[i].amountHTVA<0){
                        var temp="<tr><td class=\"text-red\">OUT</td>";
                    }else{
                        var temp="<tr>";
                    }
                    dest=dest.concat(temp);

                    if(response.bill[i].fileName){
                        var temp="<td><a href=\"factures/"+response.bill[i].fileName+"\" target=\"_blank\">"+response.bill[i].ID+"</a></td>";
                    }
                    else{
                        var temp="<td><a href=\"#\" class=\"text-red\">"+response.bill[i].ID+"</a></td>";
                    }
                    dest=dest.concat(temp);
                    if(response.update && response.bill[i].amountHTVA>0){
                        var temp="<td>"+response.bill[i].company+"</a></td>";
                        dest=dest.concat(temp);
                    }else if(response.update && response.bill[i].amountHTVA<0){
                        var temp="<td>"+response.bill[i].beneficiaryCompany+"</a></td>";
                        dest=dest.concat(temp);
                    }
                    var temp="<td data-sort=\""+(new Date(response.bill[i].date)).getTime()+"\">"+response.bill[i].date.shortDate()+"</td><td>"+Math.round(response.bill[i].amountHTVA)+" €</td><td>"+response.bill[i].communication+"</td>";
                    dest=dest.concat(temp);

                    if(sent=="Y"){
                        var temp="<td class=\"text-green\">"+sendDate+"</td>";
                    }else{
                        var temp="<td class=\"text-red\">"+sent+"</td>";
                    }
                    dest=dest.concat(temp);

                    if(paid=="Y"){
                        var temp="<td class=\"text-green\">"+paidDate+"</td>";
                    }else{
                        var temp="<td class=\"text-red\">"+paid+"</td>";
                    }
                    dest=dest.concat(temp);

                    dest=dest.concat("<td>"+paidLimit+"</td>");


                    if(response.update){
                        if(response.bill[i].communicationSentAccounting=="1"){
                            var temp="<td class=\"text-green\">OK</td>";
                        }else{
                            var temp="<td class=\"text-red\">KO</td>";
                        }
                        dest=dest.concat(temp);
                    }

                    if(response.update){
                        temp="<td><ins><a class=\"text-green updateBillingStatus\" data-target=\"#updateBillingStatus\" name=\""+response.bill[i].ID+"\" data-toggle=\"modal\" href=\"#\">Update</a></ins></td>";
                        dest=dest.concat(temp);
                    }

                    dest=dest.concat("</tr>");
                    
                    if(response.update){
                        if(response.bill[i].sent=='0'){
                            
                            
                            var request = new XMLHttpRequest();
                            
                            request.open('POST', './include/get_company_details', false);  // `false` makes the request synchronous
                            request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            
                            request.send("company="+response.bill[i].company);

                            if (request.status === 200) {
                                var json=request.responseText;
                                var response2 = JSON.parse(json);
                                                                
                                var temp3="<tr><td><a href=\"factures/"+response.bill[i].fileName+"\" target=\"_blank\"><i class=\"fa fa-file\"></i></a><input type=\"text\" class=\"form-control required hidden ID\" value=\""+response.bill[i].ID+"\" /></a></td>";
                                dest3=dest3.concat(temp3);
                                var temp3="<td>"+response.bill[i].company+"</a></td>";
                                dest3=dest3.concat(temp3);
                                var temp3="<td>"+Math.round(response.bill[i].amountHTVA)+" €</td>";
                                dest3=dest3.concat(temp3);
                                var temp3="<td data-sort=\""+(new Date(response.bill[i].date)).getTime()+"\">"+response.bill[i].date.shortDate()+"</td>";                 
                                dest3=dest3.concat(temp3);
                                var temp3="<td><input type=\"text\" class=\"form-control required email\" value=\""+response2.emailContactBilling+"\"/></td>";                 
                                dest3=dest3.concat(temp3);
                                var temp3="<td><input type=\"text\" class=\"form-control required firstName\" value=\""+response2.firstNameContactBilling+"\"/></td>";                 
                                dest3=dest3.concat(temp3);
                                var temp3="<td><input type=\"text\" class=\"form-control required lastName\" value=\""+response2.lastNameContactBilling+"\"/></td>";                 
                                dest3=dest3.concat(temp3);
                                var temp3="<td><input type=\"text\" class=\"form-control required hidden date\" value=\""+response.bill[i].date+"\"/></td>";     
                                dest3=dest3.concat(temp3);                                
                                var temp3="<td><input type=\"text\" class=\"form-control required hidden fileName\" value=\""+response.bill[i].fileName+"\"/></td>";
                                dest3=dest3.concat(temp3);
                                
                                dest3=dest3.concat("<td><button  class=\"sendBillButton button small green button-3d rounded icon-left\"><i class=\"fa fa-check\"></i>Envoyer</button></tr>");
                                
                            }
                        }
                    }                    
                    i++;
                }
                var temp="</tbody></table>";
                dest=dest.concat(temp);
                var temp3="</tbody></table>";
                dest3=dest3.concat(temp3);
                
                if(response.update){
                    $('.billsToSendSpan').removeClass("hidden");
                }else{
                    $('.billsToSendSpan').addClass("hidden");
                }
                
                document.getElementById('billsListing').innerHTML = dest;
                document.getElementById('billsToSendListing').innerHTML = dest3;
                
                
                $('.sendBillButton').click(function() {
                    var email_client=$(this).parents('tr').find('.email').val();
                    var id=$(this).parents('tr').find('.ID').val();
                    var lastName=$(this).parents('tr').find('.lastName').val();
                    var firstName=$(this).parents('tr').find('.firstName').val();
                    var date=$(this).parents('tr').find('.date').val();
                    var fileName=$(this).parents('tr').find('.fileName').val();
                    
                    $.ajax({
                        url: 'include/send_bill.php',
                        type: 'post',
                        data: { "id": id, "email": email_client, "firstName": firstName, "lastName": lastName, "date": date, "fileName": fileName},
                        success: function(response){
                            if(response.response == 'error') {
                              $.notify({
                                message: response.message
                              }, {
                                type: 'danger'
                              });
                            }
                            if(response.response == 'success'){
                              get_bills_listing('*', '*', '*', '*',email);
                              $.notify({
                                message: response.message
                              }, {
                                type: 'success'
                              });
                            }
                        }
                    })

                    
                });                
                
                
                var classname = document.getElementsByClassName('updateBillingStatus');
                for (var i = 0; i < classname.length; i++) {
                    classname[i].addEventListener('click', function() {construct_form_for_billing_status_update(this.name)}, false);
                }
                displayLanguage();
                
                $('#billsListingTable').DataTable();
                //$('#billsToSendListingTable').DataTable();
                

            }
        }
    })
}
