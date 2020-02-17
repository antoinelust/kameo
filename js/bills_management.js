
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