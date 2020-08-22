$( ".fleetmanager" ).click(function() {
    $.ajax({
        url: 'apis/Kameo/initialize_counters.php',
        type: 'post',
        data: { "email": email, "type": "bikesAdmin"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('counterBikeAdmin').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bikeNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.bikeNumber+"</span>";
            }
        }
    })
})


function list_bikes_admin() {
    $.ajax({
        url: 'apis/Kameo/get_bikes_listing.php',
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
