

function bikeFilter(e){
    document.getElementsByClassName('bikeSelectionText')[0].innerHTML=e;
    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));

}

function list_bikes_admin() {
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
                var temp=`<table class="table table-condensed bikesListingTable">
                            <h4 class="fr-inline text-green">Vélos: Leasing et autres</h4><br/>
                            <a class="button small green button-3d rounded icon-right addBikeAdmin" data-target="#bikeManagement" data-toggle="modal" href="#" onclick="set_required_image('true')">
                              <span class="fr-inline"><i class="fa fa-plus"></i> Ajouter un vélo</span>
                            </a>
                            <span class="button small green button-3d rounded icon-right showSoldBikes">
                              <span class="fr-inline">Afficher les vélos vendus</span>
                            </span>
                            <br/>
                            <h4 class="en-inline text-green">Bikes:</h4><h4 class="nl-inline text-green">Fietsen:</h4>
                            <tbody>
                              <thead>
                                <tr>
                                  <th>
                                    <span class="fr-inline">Société</span><span class="en-inline">Company</span>
                                    <span class="nl-inline">Company</span></th><th><span class="fr-inline">Vélo</span>
                                    <span class="en-inline">Bike</span><span class="nl-inline">Fiet</span>
                                  </th>
                                  <th>
                                    <span class="fr-inline">Marque - Modèle</span><span class="en-inline">Brand - Model</span>
                                    <span class="nl-inline">Brand - Model</span></th><th><span class="fr-inline">Type de contrat</span>
                                    <span class="en-inline">Contract type</span><span class="nl-inline">Contract type</span>
                                  </th>
                                  <th>
                                    <span class="fr-inline">Début contrat</span>
                                    <span class="en-inline">Contract Start</span>
                                    <span class="nl-inline">Contract Start</span>
                                  </th>
                                  <th>
                                    <span class="fr-inline">Fin contrat</span><span class="en-inline">Contract End</span>
                                    <span class="nl-inline">Contract End</span></th><th><span class="fr-inline">Montant</span>
                                    <span class="en-inline">Amount</span><span class="nl-inline">Amount</span>
                                  </th>
                                  <th>Facturation</th>
                                  <th>
                                    <span class="fr-inline">Etat du vélo</span>
                                    <span class="en-inline">Bike status</span>
                                    <span class="nl-inline">Bike status</span>
                                  </th>
                                  <th>Assurance ?</th>
                                  <th></th>
                                </tr>
                              </thead>`;
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
                    var row = '<tr class="showRow">';
                    if(response.bike[i].contractType == 'selling'){
                      row = '<tr style="display:none;" class="hideRow">';
                    }
                    var temp= row + "<td>"+response.bike[i].company+"</td><td><a  data-target=\"#bikeManagement\" name=\""+response.bike[i].frameNumber+"\" data-toggle=\"modal\" class=\"retrieveBikeAdmin\" href=\"#\">"+response.bike[i].frameNumber+"</a></td><td>"+brandAndModel+"</td><td>"+contractType+"</td><td>"+start+"</td><td>"+end+"</td><td>"+leasingPrice+"</td><td>"+automatic_billing+"</td><td>"+status+"</td><td>"+insurance+"</td><td><ins><a class=\"text-green updateBikeAdmin\" data-target=\"#bikeManagement\" name=\""+response.bike[i].frameNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"set_required_image('false')\">Mettre à jour</a></ins></td></tr>";
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

function get_bikes_listing() {

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

//Affichage des vélos vendus
$('body').on('click','.showSoldBikes', function(){
  var buttonContent = "Afficher les autres vélos";
  var titleContent = "Vélos: Vendus";
  switch_showed_bikes ('showSoldBikes', 'hideSoldBikes', buttonContent, titleContent);
});

//Affichage des autres vélos
$('body').on('click','.hideSoldBikes', function(){
  var buttonContent = "Afficher vélos vendus";
  var titleContent = "Vélos: Leasing et autres";
  switch_showed_bikes ('hideSoldBikes', 'showSoldBikes', buttonContent, titleContent);
});


function switch_showed_bikes(buttonRemove, buttonAdd, buttonContent, titleContent){
  //modification du bouton
  $('.'+buttonRemove).removeClass(buttonRemove).addClass(buttonAdd).find('.fr-inline').html(buttonContent);
  //modification du Titre
  $('#bikeDetailsAdmin').find('h4.fr-inline').html(titleContent);
  //gestion des classes show et hide
  $('.bikesListingTable').find('.showRow').removeClass('showRow').addClass('hideRowTemp');
  $('.bikesListingTable').find('.hideRow').removeClass('hideRow').addClass('showRow');
  $('.bikesListingTable').find('.hideRowTemp').removeClass('hideRowTemp').addClass('hideRow');
  //affichage
  $('.bikesListingTable').find('.hideRow').hide();
  $('.bikesListingTable').find('.showRow').fadeIn();

}


function set_required_image(foo){
  if (foo == 'true') {
    $('.bikeImageUpload').find('input').addClass('required');
  }else if(foo == 'false'){
    $('.bikeImageUpload').find('input').removeClass('required');
  }

}
