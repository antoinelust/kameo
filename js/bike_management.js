var mapInitialisation=false;


function updateDisplayBikeManagement(type){
    
    if(type=="selling"){
        $('#widget-bikeManagement-form input[name=bikeID]').attr('readonly', true);
        $('#widget-bikeManagement-form .contractEndBloc').fadeOut();
        $('#widget-bikeManagement-form label[for=contractStart]').html("Date de vente");
        $('#widget-bikeManagement-form .soldPrice').show();
        $('#widget-bikeManagement-form .soldPrice input').removeAttr("disabled");
        $('.contractInfos').fadeIn("slow");
        $('.billingInfos').fadeIn("slow");
        $('.buyingInfos').fadeIn("slow");
        $('.orderInfos').fadeOut("slow");


        $('.billingPriceDiv').fadeOut("slow");
        $('.billingGroupDiv').fadeOut("slow");
        $('.billingDiv').fadeOut("slow");
        $('#widget-bikeManagement-form select[name=billingType]').val("paid");
        $('#widget-bikeManagement-form select[name=billingType]').attr('readonly', true);

        $('#addBike_firstBuilding').fadeOut("slow");
        $('#addBike_buildingListing').fadeOut("slow");
        $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
        $('#bikeUserAccessAdminDiv').fadeOut("slow");
        $('#bikeBuildingAccessAdmin').fadeOut("slow");
        $('#bikeUserAccessAdmin').fadeOut("slow");
    }else if(type=="stock"){
        $('#widget-bikeManagement-form input[name=bikeID]').attr('readonly', true);        
        $('.contractInfos').fadeOut("slow");
        $('.billingInfos').fadeOut("slow");
        $('.buyingInfos').fadeIn("slow");
        $('.orderInfos').fadeOut("slow");


        $('.billingPriceDiv').fadeOut("slow");
        $('.billingGroupDiv').fadeOut("slow");
        $('.billingDiv').fadeOut("slow");
        $('#widget-bikeManagement-form select[name=billingType]').val("paid");
        $('#widget-bikeManagement-form select[name=billingType]').attr('readonly', true);

        $('#addBike_firstBuilding').fadeOut("slow");
        $('#addBike_buildingListing').fadeOut("slow");
        $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
        $('#bikeUserAccessAdminDiv').fadeOut("slow");
        $('#bikeBuildingAccessAdmin').fadeOut("slow");
        $('#bikeUserAccessAdmin').fadeOut("slow");          
        
    }else if(type=="order"){
        $('.contractInfos').fadeOut("slow");
        $('.billingInfos').fadeOut("slow");
        $('.buyingInfos').fadeIn("slow");
        $('.orderInfos').fadeIn("slow");

        $('.billingPriceDiv').fadeOut("slow");
        $('.billingGroupDiv').fadeOut("slow");
        $('.billingDiv').fadeOut("slow");            
        $('#addBike_firstBuilding').fadeOut("slow");
        $('#addBike_buildingListing').fadeOut("slow");
        $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
        $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
        $('#bikeBuildingAccessAdmin').fadeOut("slow");
        $('#bikeUserAccessAdmin').fadeOut("slow");
        $('addBike_firstBuilding').fadeOut("slow");
        $('#widget-bikeManagement-form input[name=bikeID]').attr('readonly', true);        

    }else{
        $('#widget-bikeManagement-form input[name=bikeID]').attr('readonly', true);        
        $('.buyingInfos').fadeIn("slow");
        $('.contractInfos').fadeIn("slow");
        $('.billingInfos').fadeIn("slow");
        $('.orderInfos').fadeOut("slow");


        if($('#widget-bikeManagement-form select[name=billingType]').val()!="paid"){
            $('.billingPriceDiv').fadeIn("slow");
            $('.billingGroupDiv').fadeIn("slow");
            $('.billingDiv').fadeIn("slow");            
        }

        $('#addBike_firstBuilding').removeClass("hidden");
        $('#addBike_buildingListing').removeClass("hidden");
        $('#bikeBuildingAccessAdminDiv').removeClass("hidden");
        $('#bikeBuildingAccessAdminDiv').removeClass("hidden");
        $('#bikeBuildingAccessAdmin').removeClass("hidden");
        $('#bikeUserAccessAdmin').removeClass("hidden");
        $('#widget-bikeManagement-form select[name=billingType]').attr('readonly', false);

    }    
    
    
    
    
    
}


window.addEventListener("DOMContentLoaded", function(event) {
    

    $(".bikeManagerClick").click(function() {
        list_bikes_admin();
    });
    

    $('#widget-bikeManagement-form select[name=billingType]').change(function(){
        console.log($('#widget-bikeManagement-form select[name=billingType]').val());
        if($('#widget-bikeManagement-form select[name=billingType]').val()=="paid"){
            $('.billingPriceDiv').fadeOut("slow");
            $('.billingGroupDiv').fadeOut("slow");
            $('.billingDiv').fadeOut("slow");
        }else{
            $('.billingPriceDiv').fadeIn("slow");
            $('.billingGroupDiv').fadeIn("slow");
            $('.billingDiv').fadeIn("slow");
        }
    });
    
    $('#widget-bikeManagement-form select[name=contractType]').change(function(){
        updateDisplayBikeManagement($('#widget-bikeManagement-form select[name=contractType]').val());
    });
    
    
    $('#insuranceBikeCheck').click(function(){
        console.log($('#insuranceBikeCheck').is(":checked"));
          if($('#insuranceBikeCheck').is(":checked")){
            $('#widget-bikeManagement-form label[for=contractEnd]').html("Date de fin d'assurance");
            $('#widget-bikeManagement-form .contractEndBloc').fadeIn();
          } else{
            $('#widget-bikeManagement-form .contractEndBloc').fadeOut();
          }
    });    
    

    $('#widget-bikeManagement-form select[name=company]').change(function(){
        console.log($('#widget-bikeManagement-form select[name=company]').val());
        if($('#widget-bikeManagement-form select[name=company]').val()=="KAMEO"){
            $('#widget-bikeManagement-form input[name=frameNumber]').removeClass("required");
        }else{
            $('#widget-bikeManagement-form input[name=frameNumber]').addClass("required");
        }
    });    
    
    
});

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
                var temp=`<h4 class="fr-inline text-green">Vélos: Leasing et autres</h4>
                            <h4 class="en-inline text-green">Bikes:</h4><h4 class="nl-inline text-green">Fietsen:</h4><br/>
                            <a class="button small green button-3d rounded icon-right addBikeAdmin" data-target="#bikeManagement" data-toggle="modal" href="#">
                              <span class="fr-inline"><i class="fa fa-plus"></i> Ajouter un vélo</span>
                            </a>
                            <span class="button small green button-3d rounded icon-right showSoldBikes">
                              <span class="fr-inline">Afficher les vélos vendus</span>
                              <span class="en-inline">Display sold bikes</span>
                            </span>
                            <span class="button small green button-3d rounded icon-right showOrders">
                              <span class="fr-inline">Afficher les commandes</span>
                              <span class="en-inline">Display ordered bikes</span>
                            </span>
                            <br/>
                            <table class="table table-condensed bikesListingTable" id=\"bookingAdminTable\" data-order='[[ 0, \"desc\" ]]' data-page-length='25'>
                              <thead>
                                <tr>
                                  <th>
                                    ID
                                  </th>
                                  <th>
                                    <span class="fr-inline">Société</span>
                                    <th><span class="fr-inline">Vélo</span>
                                  </th>
                                  <th>
                                    <span class="fr-inline">Marque - Modèle</span>
                                    <th><span class="fr-inline">Type de contrat</span>
                                  </th>
                                  <th>
                                    <span class="fr-inline">Début contrat</span>                                                                    
                                  </th>
                                  <th>
                                    <span class="fr-inline">Fin contrat</span>
                                    <th><span class="fr-inline">Montant</span>
                                  </th>
                                  <th>Facturation</th>
                                  <th>
                                    <span class="fr-inline">Etat du vélo</span>
                                  </th>
                                  <th>Assurance ?</th>
                                  <th>Mise à jour </th>
                                  <th>Rentabilité </th>
                                  <th>Date de commande </th>
                                  <th>Date de livraison </th>
                                  <th>Numéro commande fournisseur </th>
                                  <th>Taille </th>
                                  <th>Couleur </th>
                                </tr>
                              </thead><tbody>`;
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
                        start="<span class=\"text-green\">"+response.bike[i].contractStart.substr(8,2)+"/"+response.bike[i].contractStart.substr(5,2)+"/"+response.bike[i].contractStart.substr(2,2)+"</span>";
                    }else if(response.bike[i].contractStart==null && (response.bike[i].company=="KAMEO" || response.bike[i].company == 'KAMEO VELOS TEST')){
                        start="<span class=\"text-green\">N/A</span>";
                    }else if(response.bike[i].contractStart!=null && (response.bike[i].company=="KAMEO" || response.bike[i].company == 'KAMEO VELOS TEST')){
                        start="<span class=\"text-red\">"+response.bike[i].contractStart.substr(8,2)+"/"+response.bike[i].contractStart.substr(5,2)+"/"+response.bike[i].contractStart.substr(2,2)+"</span>";
                    }else if(response.bike[i].contractStart != null && response.bike[i].company != "KAMEO" && response.bike[i].contractType == "selling"){
                        start="<span class=\"text-green\">"+response.bike[i].contractStart.shortDate()+"</span>";
                    }else{
                        start="<span class=\"text-red\">ERROR</span>";
                    }



                    if(response.bike[i].contractEnd==null && (response.bike[i].company!="KAMEO" && response.bike[i].company != 'KAMEO VELOS TEST' && response.bike[i].contractType=='leasing')){
                        end="<span class=\"text-red\">N/A</span>";
                    }else if(response.bike[i].contractEnd!=null && (response.bike[i].company!="KAMEO" && response.bike[i].company != 'KAMEO VELOS TEST')){
                        end="<span class=\"text-green\">"+response.bike[i].contractEnd.substr(8,2)+"/"+response.bike[i].contractEnd.substr(5,2)+"/"+response.bike[i].contractEnd.substr(2,2)+"</span>";
                    }else if(response.bike[i].contractEnd==null && (response.bike[i].company=="KAMEO" || response.bike[i].company == 'KAMEO VELOS TEST' || response.bike[i].contractType=="renting" || response.bike[i].contractType=="test")){
                        end="<span class=\"text-green\">N/A</span>";
                    }else if(response.bike[i].contractEnd!=null && (response.bike[i].company=="KAMEO" || response.bike[i].company == 'KAMEO VELOS TEST')){
                        end="<span class=\"text-red\">"+response.bike[i].contractEnd.substr(8,2)+"/"+response.bike[i].contractEnd.substr(5,2)+"/"+response.bike[i].contractEnd.substr(2,2)+"</span>";
                    }else if(response.bike[i].contractEnd == null && response.bike[i].company != "KAMEO" && response.bike[i].contractType == "selling"){
                        end="<span class=\"text-green\">N/A</span>";
                    }else{
                        end="<span class=\"text-red\">ERROR</span>";
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

                    if(response.bike[i].contractType== 'selling'){
                      var leasingPrice="<span class=\"text-green\">"+response.bike[i].soldPrice+"</span>";
                    }
                    else if((response.bike[i].leasingPrice==null || response.bike[i].leasingPrice==0) && (response.bike[i].contractType== 'renting' || response.bike[i].contractType=='leasing') && response.bike[i].billingType != 'paid'){
                        var leasingPrice="<span class=\"text-red\">0</span>";
                    }else if((response.bike[i].leasingPrice!=null && response.bike[i].leasingPrice!=0) && (response.bike[i].contractType== 'renting' || response.bike[i].contractType=='leasing')){
                        var leasingPrice="<span class=\"text-green\">"+response.bike[i].leasingPrice+" €/mois</span>";
                    }else if((response.bike[i].leasingPrice!=null && response.bike[i].leasingPrice!=0) && (response.bike[i].contractType== 'stock' || response.bike[i].contractType=='test')){
                        var leasingPrice="<span class=\"text-red\">"+response.bike[i].leasingPrice+" €/mois</span>";
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
                    
                    if(response.bike[i].rentability!='N/A'){
                        var rentability="<td data-sort=\""+response.bike[i].rentability+"\">"+response.bike[i].rentability+" %</td>";
                    }else{
                        var rentability="<td data-sort=\"0\">"+response.bike[i].rentability+"</td>";
                    }
                                        
                    if(response.bike[i].GPS_ID != null){
                        temp=temp+"<a data-target=\"#bikePosition\" name=\""+response.bike[i].id+"\" data-toggle=\"modal\" class=\"clickBikePosition\" href=\"#\"><i class=\"fa fa-map-pin\" aria-hidden=\"true\"></i> </a>";
                    }
                    
                    if(response.bike[i].bikeBuyingDate==null){
                        var bikeBuyingDate="<span class=\"text-red\">N/A</span>";
                    }else{
                        var bikeBuyingDate="<span class=\"\">"+response.bike[i].bikeBuyingDate.shortDate()+"</span>";
                    }
                    if(response.bike[i].deliveryDate==null){
                        var deliveryDate="<span class=\"text-red\">N/A</span>";
                    }else{
                        var deliveryDate="<span class=\"\">"+response.bike[i].deliveryDate.shortDate()+"</span>";
                    }
                    
                    if(response.bike[i].color == "" || response.bike[i].color==null){
                        var color = "N/A";
                    }else{
                        var color = response.bike[i].color;
                    }
                    
                    temp = "<tr><td><a  data-target=\"#bikeManagement\" name=\"" + response.bike[i].id+"\" data-toggle=\"modal\" class=\"updateBikeAdmin\" href=\"#\">"+response.bike[i].id+"</a></td><td>"+response.bike[i].company+"</td><td>"+response.bike[i].frameNumber+"</td><td>"+brandAndModel+"</td><td>"+contractType+"</td><td>"+start+"</td><td>"+end+"</td><td>"+leasingPrice+"</td><td>"+automatic_billing+"</td><td>"+status+"</td><td>"+insurance+"</td><td data-sort=\""+(new Date(response.bike[i].HEU_MAJ)).getTime()+"\">"+response.bike[i].HEU_MAJ.shortDate()+"</td>"+rentability+"<td>"+bikeBuyingDate+"</td><td>"+deliveryDate+"</td><td>"+response.bike[i].orderNumber+"</td><td>"+response.bike[i].size+"</td><td>"+color+"</td></tr>";
                    dest=dest.concat(temp);
                  i++;
                }
                var temp="</tbody></table>";
                dest=dest.concat(temp);
                document.getElementById('bikeDetailsAdmin').innerHTML = dest;
                
                
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

                
                $('#bikePosition').on('shown.bs.modal', function () {
                    if(!mapInitialisation){
                        
                        
                    }
                })
                
                $('.clickBikePosition').on('click', function () {
                    $.ajax({
                            url: 'api/get_position.php',
                            type: 'get',
                            data: {"bikeId": this.name},
                            xhrFields: {
                                withCredentials: true
                            },
                            headers: {
                                'Authorization': 'Basic ' + btoa('antoine@kameobikes.com:test')
                            },

                            success: function(response){
                                if (response.response == 'error') {
                                    console.log(response.message);
                                } else{
                                    $('#demoMap').html("");
                                    var lon            = response.longitude;
                                    var lat            = response.latitude;
                                    var zoom           = 15;

                                    var position       = new OpenLayers.LonLat(lat, lon)
                                    var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
                                    var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
                                    var position       = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);

                                    map = new OpenLayers.Map("demoMap");
                                    var mapnik = new OpenLayers.Layer.OSM();
                                    map.addLayer(mapnik);
                                    var markers = new OpenLayers.Layer.Markers( "Markers" );
                                    map.addLayer(markers);
                                    markers.addMarker(new OpenLayers.Marker(position));

                                    map.setCenter(position, zoom);
                                }
                            }
                    })
                })
                    
                
                

                table = $('#bookingAdminTable').DataTable( {
                    paging: true,
                    searching: true,
                    "scrollX": true,                    
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
                      "columns": [
                        { "width": "50px" },
                        { "width": "50px" },
                        { "width": "100px" },
                        { "width": "180px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" },
                        { "width": "100px" }
                      ],
                        "columnDefs": [
                            {
                                "targets": [ 13 ],
                                "visible": false,
                                "searchable": false
                            },                            {
                                "targets": [ 14 ],
                                "visible": false,
                                "searchable": false
                            },
                            {
                                "targets": [ 15 ],
                                "visible": false
                            },
                            {
                                "targets": [ 16 ],
                                "visible": false
                            },
                            {
                                "targets": [ 17 ],
                                "visible": false
                            }
                        ]                    
                });
                
                
                table
                    .column(4)
                    .search( "test|stock|renting|leasing", true, false )
                    .draw();
                

            }
        }
    })
}

function update_offer_list(company){    
        $.ajax({
          url: 'include/offer_management.php',
          method: 'get',
          data: {'company' : company, 'action': 'retrieve'},
          success: function(response){
            if (response.response == "error"){
              console.log(response.message);
            }else{
                $('#widget-bikeManagement-form select[name=offerReference]')
                    .find('option')
                    .remove()
                    .end()
                ;
                var i=0;
                while (i < response.offersNumber){
                    $('#widget-bikeManagement-form select[name=offerReference]').append("<option value="+response.offer[i].id+">"+response.offer[i].title+"<br>");
                    i++;
                }
                
                if(response.offersNumber == 0){
                    $('.offerReference').fadeOut();
                }else{
                    $('.offerReference').fadeIn();
                }
                
                $('#widget-bikeManagement-form select[name=offerReference').val("");
                
            }
          }
        });
}

$('#widget-bikeManagement-form input[name=company]').change(function(){
    update_offer_list($('#widget-bikeManagement-form select[name=company]').val());
});



function add_bike(ID){
    
    
    $('#widget-bikeManagement-form select[name=contractType')
        .find('option')
        .remove()
        .end()
    ;
    $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"order\">Commande</option>");
    
    
    $('.contractInfos').fadeOut("slow");
    $('.billingInfos').fadeOut("slow");
    $('.buyingInfos').fadeOut("slow");
    $('.orderInfos').fadeOut("slow");
    $('.billingPriceDiv').fadeOut("slow");
    $('.billingGroupDiv').fadeOut("slow");
    $('.billingDiv').fadeOut("slow");            
    $('#addBike_firstBuilding').fadeOut("slow");
    $('#addBike_buildingListing').fadeOut("slow");
    $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
    $('#bikeBuildingAccessAdminDiv').fadeOut("slow");
    $('#bikeBuildingAccessAdmin').fadeOut("slow");
    $('#bikeUserAccessAdmin').fadeOut("slow");
    $('addBike_firstBuilding').fadeOut("slow");
    
    
    
    
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
    });
    
    

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
                    $('#widget-bikeManagement-form input[name=model]').val(response.model);
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
                            message: "Si vous définissez un vélo en leasing, veuillez d'abord définir un bâtiment"
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
            update_offer_list(company);


        })
    }
    
    
    $('#widget-bikeManagement-form input[name=bikeID').val("");
    $('#widget-bikeManagement-form input[name=bikeID').fadeOut();
    $('#widget-bikeManagement-form label[for=bikeID').fadeOut();
    $('#widget-bikeManagement-form select[name=contractType').val("order");
    
    updateDisplayBikeManagement("order");
    
    
    $('#widget-bikeManagement-form select[name=company]').change(function(){
        updateAccessAdmin($('#widget-bikeManagement-form input[name=frameNumber]').val(), $('#widget-bikeManagement-form select[name=company]').val());
        update_offer_list($('#widget-bikeManagement-form select[name=company]').val());
        
    });


}



function construct_form_for_bike_status_updateAdmin(bikeID){
    
  document.getElementById('widget-bikeManagement-form').reset();
    
    $('#widget-bikeManagement-form input[name=bikeID').fadeIn();
    $('#widget-bikeManagement-form label[for=bikeID').fadeIn();

    
    $('#widget-bikeManagement-form select[name=contractType')
        .find('option')
        .remove()
        .end()
    ;
    $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"leasing\">Location LT</option>");
    $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"renting\">Location CT</option>");
    $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"selling\">Vente</option>");
    $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"test\">Vélo de test</option>");
    $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"stock\">Vélo de stock</option>");
    $('#widget-bikeManagement-form select[name=contractType').append("<option value=\"order\">Commande</option>");
    
    
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
        var id;
        $.ajax({
                url: 'include/get_bike_details.php',
                type: 'post',
                data: { "bikeID": bikeID},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{
                        document.getElementById("bikeManagementPicture").src="images_bikes/"+bikeID+"_mini.jpg";
                        $('.bikeManagementPicture').removeClass('hidden');
                        id=response.id;
                        company=response.company;
                        
                        $('#widget-bikeManagement-form input[name=bikeID]').val(bikeID);
                        $('#widget-bikeManagement-form input[name=frameNumber]').val(response.frameNumber);
                        $('#widget-deleteBike-form input[name=frameNumber]').val(response.frameNumber);
                        $('#widget-bikeManagement-form input[name=frameNumberOriginel]').val(response.frameNumber);
                        $('#widget-bikeManagement-form input[name=model]').val(response.model);
                        $('#widget-bikeManagement-form input[name=size]').val(response.size);
                        $('#widget-bikeManagement-form input[name=color]').val(response.color);
                        $('#widget-bikeManagement-form input[name=frameReference]').val(response.frameReference);
                        $('#widget-bikeManagement-form input[name=lockerReference]').val(response.lockerReference);
                        $('#widget-bikeManagement-form input[name=price]').val(response.bikePrice);
                        $('#widget-bikeManagement-form input[name=buyingDate]').val(response.buyingDate);
                        $('#widget-bikeManagement-form select[name=billingType]').val(response.billingType);
                        $('#widget-bikeManagement-form select[name=contractType]').val(response.contractType);
                        $('#widget-bikeManagement-form input[name=bikeSoldPrice]').val(response.soldPrice);
                        $('#widget-bikeManagement-form input[name=orderNumber]').val(response.orderNumber);
                                                
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
                        if(response.deliveryDate == null){
                            $('#widget-bikeManagement-form input[name=deliveryDate]').val("");
                        }else{
                            $('#widget-bikeManagement-form input[name=deliveryDate]').val(response.deliveryDate.substr(0,10));
                        }
                        if(response.bikeBuyingDate == null){
                            $('#widget-bikeManagement-form input[name=orderingDate]').val("");
                        }else{
                            $('#widget-bikeManagement-form input[name=orderingDate]').val(response.bikeBuyingDate.substr(0,10));
                        }
                        update_offer_list(company);
                        if(response.offerID != null){
                            $('#widget-bikeManagement-form select[name=offerReference]').val(response.offerID);
                        }else{
                            $('#widget-bikeManagement-form select[name=offerReference]').val("");
                        }
                        
                        
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


                        document.getElementsByClassName("bikeManagementPicture")[0].src="images_bikes/"+bikeID+"_mini.jpg";

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
                        
                        
                        updateDisplayBikeManagement(response.contractType);
                    }

                }
        }).done(function(response){
            
            $.ajax({
                url: 'include/action_bike_management.php',
                type: 'post',
                data: { "readActionBike-action": "read", "bikeID": bikeID, "readActionBike-user": "<?php echo $user; ?>"},
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
                            $('textarea[name=widget-addActionBike-form-description]').removeClass("hidden");
                            $("label[for='widget-addActionBike-form-public']").removeClass("hidden");
                            $('input[name=widget-addActionBike-form-public]').removeClass("hidden");
                            $('.addActionConfirmButton').removeClass("hidden");
                        });

                    }

                }
            })
            
            $.ajax({
                url: 'include/get_bills_details_listing.php',
                type: 'post',
                data: { "bikeID": id},
                success: function(response){
                    if (response.response == 'error') {
                        console.log(response.message);
                    } else{

                        var i=0;
                        var dest="<table id=\"bills_details_listing\" class=\"table table-condensed\"  data-order='[[ 0, \"desc\" ]]'><thead><tr><th><span class=\"fr-inline\">ID</span></th><th><span class=\"fr-inline\">Date</span><span class=\"en-inline\">Date</span><span class=\"nl-inline\">Date</span></th><th><span class=\"fr-inline\">Montant</span><span class=\"en-inline\">Amount</span><span class=\"nl-inline\">Amount</span></th><th><span class=\"fr-inline\">Envoyée ?</span><span class=\"en-inline\">Sent ?</span><span class=\"nl-inline\">Sent ?</span></th><th><span class=\"fr-inline\">Payée ?</span><span class=\"en-inline\">Paid ?</span><span class=\"nl-inline\">Paid ?</span></th></tr></thead><tbody>";
                        while(i<response.billNumber){
                            if(response.bill[i].sent=="1"){
                                sent="<span class=\"text-green\">Y</span>"
                            }else{
                                sent="<span class=\"text-red\">N</span>"
                            }
                            if(response.bill[i].paid=="1"){
                                paid="<span class=\"text-green\">Y</span>"
                            }else{
                                paid="<span class=\"text-red\">N</span>"
                            }
                            
                            var temp="<tr><td><a href=\"factures/"+response.bill[i].fileName+"\" target=\"_blank\">"+response.bill[i].FACTURE_ID+"</a></td><td data-sort=\""+(new Date(response.bill[i].date)).getTime()+"\">"+response.bill[i].date.shortDate()+"</td><td>"+response.bill[i].amountHTVA+" €</td><td>"+sent+"</td><td>"+paid+"</td></tr>";
                            dest=dest.concat(temp);
                            i++;
                        }
                        dest=dest.concat("</tbody></table>");
                        $('#bills_bike').html(dest);
                        displayLanguage();
                        
                        $('#bills_details_listing').DataTable({
                            "searching": false,
                            "paging": false
                        }
                        );
                        
                        

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



function construct_form_for_bike_status_update(bikeID){

    $.ajax({
            url: 'include/get_bike_details.php',
            type: 'post',
            data: { "bikeID": bikeID},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{
                    $('#widget-updateBikeStatus-form input[name=bikeID]').val(bikeID);
                    $('#widget-updateBikeStatus-form input[name=bikeModel]').val(response.model);
                    $('#widget-updateBikeStatus-form input[name=bikeNumber]').val(response.frameNumber);
                    $('#widget-updateBikeStatus-form input[name=frameReference]').val(response.frameReference);
                    $('#widget-updateBikeStatus-form input[name=contractType]').val(response.contractType);
                    $('#widget-updateBikeStatus-form input[name=startDateContract]').val(response.contractStart);
                    $('#widget-updateBikeStatus-form input[name=endDateContract]').val(response.contractEnd);
                    document.getElementsByClassName("bikeImage")[1].src="images_bikes/"+bikeID+"_mini.jpg";

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

                    document.getElementById('bikeBuildingAccess').innerHTML = dest;

                }

            }
    })
}



function fillBikeDetails(element)
{
    var bikeID=element;
    $.ajax({
            url: 'include/get_bike_details.php',
            type: 'post',
            data: { "bikeID": bikeID},
            success: function(response){
                if (response.response == 'error') {
                    console.log(response.message);
                } else{
                    document.getElementsByClassName("bikeID")[0].innerHTML=bikeID;
                    document.getElementsByClassName("bikeModel")[0].innerHTML=response.model;
                    document.getElementsByClassName("frameReference")[0].innerHTML=response.frameReference;
                    document.getElementsByClassName("contractType")[0].innerHTML=response.contractType;
                    document.getElementsByClassName("startDateContract")[0].innerHTML=response.contractStart;
                    document.getElementsByClassName("endDateContract")[0].innerHTML=response.contractEnd;
                    document.getElementsByClassName("bikeImage")[0].src="images_bikes/"+bikeID+"_mini.jpg";

                }

                }
            })

    $.ajax({
            url: 'include/action_bike_management.php',
            type: 'post',
            data: { "readActionBike-action": "read", "readActionBike-bikeNumber": bikeID, "readActionBike-user": "<?php echo $user; ?>"},
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
                var temp="<h4 class=\"fr-inline text-green\">Vos vélos:</h4><h4 class=\"en-inline text-green\">Your Bikes:</h4><h4 class=\"nl-inline text-green\">Jouw fietsen:</h4><table class=\"table table-condensed\"><thead><tr><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fiet</span></th><th><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th><span class=\"fr-inline\">Type de contrat</span><span class=\"en-inline\">Contract type</span><span class=\"nl-inline\">Contract type</span></th><th><span class=\"fr-inline\">Début du contrat</span><span class=\"en-inline\">Contract start</span><span class=\"nl-inline\">Contract start</span></th><th><span class=\"fr-inline\">Fin du contrat</span><span class=\"en-inline\">Contract End</span><span class=\"nl-inline\">Contract End</span></th><th><span class=\"fr-inline\">Etat du vélo</span><span class=\"en-inline\">Bike status</span><span class=\"nl-inline\">Bike status</span></th><th></th></tr></thead><tbody>";
                dest=dest.concat(temp);



                while (i < response.bikeNumber){

                    if(response.bike[i].contractStart){
                        var contractStart="<td data-sort=\""+(new Date(response.bike[i].contractStart)).getTime()+"\">"+response.bike[i].contractStart.shortDate()+"</td>";
                    }else{
                        var contractStart="<td>N/A</td>";
                    }
                    if(response.bike[i].contractEnd){
                        var contractEnd="<td data-sort=\""+(new Date(response.bike[i].contractEnd)).getTime()+"\">"+response.bike[i].contractEnd.shortDate()+"</td>";
                    }else{
                        var contractEnd="<td>N/A</td>";
                    }



                    if(response.bike[i].status==null || response.bike[i].status=="KO"){
                        status="<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>";
                    }else{
                        status="<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>";
                    }

                    if(response.bike[i].frameNumber == null || response.bike[i].frameNumber == ''){
                        var frameNumber = "N/A - "+response.bike[i].id;
                    }else{
                        var frameNumber = response.bike[i].frameNumber;
                    }

                    var temp="<tr><td><a  data-target=\"#bikeDetailsFull\" name=\""+response.bike[i].id+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillBikeDetails(this.name)\">"+frameNumber+"</a></td><td>"+response.bike[i].model+"</td><td>"+response.bike[i].contractType+"</td>"+contractStart+contractEnd+"<td>"+status+"</td><td><ins><a class=\"text-green updateBikeStatus\" data-target=\"#updateBikeStatus\" name=\""+response.bike[i].id+"\" data-toggle=\"modal\" href=\"#\">Mettre à jour</a></ins></td></tr>";
                    dest=dest.concat(temp);
                    i++;

                }
                var temp="</tbody></table>";
                dest=dest.concat(temp);
                document.getElementById('bikeDetails').innerHTML = dest;

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
    var table = $('#bookingAdminTable').DataTable()
        .column(4)
        .search( "selling", true, false )
        .draw();
    
    table.column( 4 ).visible( false, true );  
    table.column( 5 ).visible( true, true );  
    table.column( 6 ).visible( true, true );  
    table.column( 7 ).visible( true, true );  
    table.column( 8 ).visible( false, true );  
    table.column( 9 ).visible( true, true );  
    table.column( 10 ).visible( true, true );  
    table.column( 11 ).visible( true, true );  
    table.column( 12 ).visible( true, true );  
    table.column( 13 ).visible( false, true );  
    table.column( 14 ).visible( false, true );  
    table.column( 15 ).visible( false, true );  
    table.column( 16 ).visible( false, true );  
    table.column( 17 ).visible( false, true );  

    $(table.column(5).header()).text('Date vente');
    $(table.column(6).header()).text('Fin assurance');
    
  switch_showed_bikes ('showSoldBikes', 'hideSoldBikes', buttonContent, titleContent);
});

$('body').on('click','.showOrders', function(){
    
  var titleContent = "Vélos: Commandes";
    var table = $('#bookingAdminTable').DataTable()
        .column(4)
        .search( "order", true, false )
        .draw();
    
    table.column( 4 ).visible( false, true );  
    table.column( 5 ).visible( false, true );  
    table.column( 6 ).visible( false, true );  
    table.column( 7 ).visible( false, true );  
    table.column( 8 ).visible( false, true );  
    table.column( 9 ).visible( false, true );  
    table.column( 10 ).visible( false, true );  
    table.column( 11 ).visible( false, true );  
    table.column( 12 ).visible( false, true );  
    table.column( 13 ).visible( true, true );  
    table.column( 14 ).visible( true, true );  
    table.column( 15 ).visible( true, true ); 
    table.column( 16 ).visible( true, true ); 
    table.column( 17 ).visible( true, true ); 
    $(table.column(5).header()).text('Date de commande');
    $(table.column(6).header()).text('Date d\'arrivée');
    table.draw();
    
});

//Affichage des autres vélos
$('body').on('click','.hideSoldBikes', function(){
  var buttonContent = "Afficher vélos vendus";
  var titleContent = "Vélos: Leasing et autres";
    
    table=$('#bookingAdminTable').DataTable()
        .column(4)
        .search( "test|stock|renting|leasing", true, false )
        .draw();
    
    table.column( 4 ).visible( true, true );  
    table.column( 5 ).visible( true, true );  
    table.column( 6 ).visible( true, true );  
    table.column( 7 ).visible( true, true );  
    table.column( 8 ).visible( true, true );  
    table.column( 9 ).visible( true, true );  
    table.column( 10 ).visible( true, true );  
    table.column( 11 ).visible( true, true );  
    table.column( 12 ).visible( true, true );  
    table.column( 13 ).visible( false, true );  
    table.column( 14 ).visible( false, true );  
    table.column( 15 ).visible( false, true );  
    table.column( 16 ).visible( false, true );  
    table.column( 17 ).visible( false, true );  
    
    
    $(table.column(5).header()).text('Début contrat');
    $(table.column(6).header()).text('Fin contrat');
    
    
  switch_showed_bikes ('hideSoldBikes', 'showSoldBikes', buttonContent, titleContent);
});


function switch_showed_bikes(buttonRemove, buttonAdd, buttonContent, titleContent){
  //modification du bouton
  $('.'+buttonRemove).removeClass(buttonRemove).addClass(buttonAdd).find('.fr-inline').html(buttonContent);
  //modification du Titre
  $('#bikeDetailsAdmin').find('h4.fr-inline').html(titleContent);
}

