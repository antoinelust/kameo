<?php 
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

<script type="text/javascript">
    window.addEventListener("DOMContentLoaded", function(event) {
        
        var classname = document.getElementsByClassName('fleetmanager');
        for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener('click', hideResearch, false);
            classname[i].addEventListener('click', get_bikes_listing, false);            
        }
        
        var classname = document.getElementsByClassName('reservations');
        for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener('click', hideResearch, false);
        }              
    });
    
    
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
                        document.getElementsByClassName("assistanceReference")[0].innerHTML=response.contractReference;    
                        document.getElementsByClassName("bikeImage")[0].src="images_bikes/"+frameNumber+"_mini.jpg";

                    }              

                    }
                })
    }
</script>


<?php
if($connected){
    
    include 'include/connexion.php';	
    $sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY from customer_referential aa where aa.EMAIL='$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $userFrameNumber = $row['FRAME_NUMBER'];
    if (ctype_alpha(substr($userFrameNumber,0,3))){
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
            var user= "<?php echo $user; ?>";
            return $.ajax({
                url: 'include/load_client_conditions.php',
                type: 'post',
                data: { "userID": user},
                success: function(text){
                    if (text.response == 'error') {
                        console.log(text.message);
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
    function constructBuildingForm(daysToDisplay, administrator, assistance){

        
        if(assistance=="Y"){
            document.getElementById('assistanceSpan').innerHTML="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#assistance\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Assistance et Entretien</span><span class=\"en-inline\">Assistance and Maintenance</span><span class=\"nl-inline\">Hulp en Onderhoud</span></a>"
        }
        // 1st step: days and month fileds

        var daysFR=['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        var daysEN=['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var daysNL=['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'];


        var startDate = new Date();    
        var i=0;
        var j=0;
        var dest ="<select id=\"search-bikes-form-day\" name=\"search-bikes-form-day\"  class=\"form-control\">";

        var tempDate = new Date();
        var month = [];
        month.push(tempDate.getMonth()+1);

        while(i<=daysToDisplay){
            var dayFR = daysFR[tempDate.getDay()];
            var dayEN = daysEN[tempDate.getDay()];
            var dayNL = daysNL[tempDate.getDay()];
            if(tempDate.getDay()=="0" || tempDate.getDay()=="6"){
            } 
            else {

                var bookingDay="<option value=\""+tempDate.getDate()+"\" class=\"form-control fr\">"+dayFR+" "+tempDate.getDate()+"</option><option value=\""+tempDate.getDate()+"\" class=\"form-control en\">"+dayEN+" "+tempDate.getDate()+"</option><option value=\""+tempDate.getDate()+"\" class=\"form-control nl\">"+dayNL+" "+tempDate.getDate()+"</option>";
                i++;       
                dest = dest.concat(bookingDay);
            }
            if(tempDate.getMonth() != month[j]){
                j++;
                month.push(tempDate.getMonth());
            }

            tempDate.setDate(tempDate.getDate()+1);
        }
        var bookingDay="</select>";
        dest = dest.concat(bookingDay);
        document.getElementById('booking_day_form').innerHTML=dest;


        var monthFR=['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        var monthEN=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var monthNL=['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];

        var dest ="<select name=\"search-bikes-form-month\" id=\"search-bikes-form-month\" class=\"form-control\">";
        for(i=0;month[i];i++){
            var MonthBase1=month[i];
            var MonthBase0=month[i]-1;
            var bookingMonth="<option value=\""+MonthBase1+"\" class=\"form-control fr\">"+monthFR[MonthBase0]+"</option><option value=\""+MonthBase1+"\" class=\"form-control en\">"+monthEN[MonthBase0]+"</option><option value=\""+MonthBase1+"\" class=\"form-control nl\">"+monthNL[MonthBase0]+"</option>";
            dest = dest.concat(bookingMonth);

        }
        var bookingMonth="</select>";
        dest = dest.concat(bookingMonth);
        document.getElementById('booking_month_form').innerHTML=dest;


        // 2nd step: intake and deposit buildings
        var langue= "<?php echo $_SESSION['langue']; ?>";
        var userFrameNumber = "<?php echo $userFrameNumber; ?>";
        var i=0;

        $.ajax({
            url: 'include/booking_building_form.php',
            type: 'post',
            data: { "userFrameNumber": userFrameNumber},
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
                displayLanguage();
            }
        });
    }

    function showBooking(bookingID){
        var dest="";
        var langue= "<?php echo $_SESSION['langue']; ?>";

        $.ajax({
            url: 'include/get_future_booking.php',
            type: 'post',
            data: { "bookingID": bookingID},
            success: function(response){
                var name = response.clientBefore.name;
                var surname = response.clientBefore.surname;
                var phone = response.clientBefore.phone;
                var mail = response.clientBefore.mail;
                var depositDay = response.clientBefore.depositDay;
                var depositHour = response.clientBefore.depositHour;

                if(langue=="nl"){
                    var dest="<li class=\"nl\">Naam: "+name+" "+surname+"</li><li class=\"nl\">Telefoonnummer:"+phone+"</li><li class=\"nl\">Mail: "+mail+"</li><li class=\"nl\">Stort fiets op "+depositDay+" om "+depositHour+"</li>";
                } else if (langue == "en"){
                    var dest="<li class=\"en\">Name: "+name+" "+surname+"</li><li class=\"en\">Phone Number:"+phone+"</li><li class=\"en\">Mail: "+mail+"</li><li class=\"en\">Returns bike on" +depositDay+" at "+depositHour+"</li>";
                } else {
                    var dest="<li class=\"fr\">Nom et prénom: "+name+" "+surname+"</li><li class=\"fr\">Numéro de téléphone: "+phone+"</li><li class=\"fr\">Adresse mail: "+mail+"</li><li class=\"fr\">Dépose le vélo le "+depositDay+" à "+depositHour+"</li>";
                }

                document.getElementById('futureBookingBefore').innerHTML = dest;

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
                    var temp="<table class=\"table table-condensed\"><h4 class=\"fr-inline\">Vos vélos:</h4><h4 class=\"en-inline\">Your Bikes:</h4><h4 class=\"nl-inline\">Jouw fietsen:</h4><tbody><thead><tr><th><span class=\"fr-inline\">Référence</span><span class=\"en=inline\">Reference</span><span class=\"nl-inline\">Referentie</span></th><th><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th><span class=\"fr-inline\">Type de contrat</span><span class=\"en-inline\">Contract type</span><span class=\"nl-inline\">Contract type</span></th><th><span class=\"fr-inline\">Dates du contrat</span><span class=\"en-inline\">Contract dates</span><span class=\"nl-inline\">Contract data</span></th></tr></thead>";
                    dest=dest.concat(temp);
                    while (i < response.bikeNumber){
                        
                        var temp="<tr><th><a  data-target=\"#bikeDetailsFull\" name=\""+response.bike[i].frameNumber+"\" data-toggle=\"modal\" href=\"#\" onclick=\"fillBikeDetails(this.name)\">"+response.bike[i].frameNumber+"</a></th><th>"+response.bike[i].modelFR+"</th><th>"+response.bike[i].contractType+"</th><th>"+response.bike[i].contractDates+"</th></tr>";
                        dest=dest.concat(temp);
                        i++;
                        
                    }
                    var temp="</tobdy></table>";
                    dest=dest.concat(temp);
                    document.getElementById('bikeDetails').innerHTML = dest;
                    document.getElementById('BikesInCompany').innerHTML = response.bikeNumber;
                    document.getElementById('kmsCompany').innerHTML = response.kmsTotal;
                    displayLanguage();

                }
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
                var i=0;
                var dest="";

                var tempHistoricBookings="<table class=\"table table-condensed\"><h4 class=\"fr-inline\">Réservations précédentes:</h4><h4 class=\"en-inline\">Previous Bookings:</h4><h4 class=\"nl-inline\">Vorige reservaties:</h4><thead><tr><th>Date</th><th><span class=\"fr-inline\">Départ</span><span class=\"en\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Arrivée</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fitse</span></th></tr></thead><tbody>";


                dest = dest.concat(tempHistoricBookings);
                while (i < response.previous_bookings)
                {
                    var day=response.booking[i].day;            
                    var hour_start=response.booking[i].hour_start;
                    var hour_end=response.booking[i].hour_end;
                    var building_start_fr = response.booking[i].building_start_fr;
                    var building_start_en = response.booking[i].building_start_en;
                    var building_start_nl = response.booking[i].building_start_nl;
                    var building_end_fr = response.booking[i].building_end_fr;
                    var building_end_en = response.booking[i].building_end_en;
                    var building_end_nl = response.booking[i].building_end_nl;
                    var frame_number=response.booking[i].frameNumber;


                    var tempHistoricBookings ="<tr><td>"+day+"</td><td>"+building_start_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_start+"</td><td>"+building_end_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_end+"</td><td>"+frame_number+"</td></tr>";

                    dest = dest.concat(tempHistoricBookings);
                    i++;

                }
                var tempHistoricBookings="</tbody></table>";
                dest = dest.concat(tempHistoricBookings);

                //affichage du résultat de la recherche
                document.getElementById('historicBookings').innerHTML = dest;

                //Booking futurs

                var dest="";
                var tempFutureBookings="<table class=\"table table-condensed\"><h4 class=\"fr-inline\">Réservations futures:</h4><h4 class=\"en-inline\">Next bookings:</h4><h4 class=\"nl-inline\">Volgende boekingen:</h4><thead><tr><th>Date</th><th><span class=\"fr-inline\">Départ</span><span class=\"en\">Start</span><span class=\"nl-inline\">Start</span></th><th><span class=\"fr-inline\">Arrivée</span><span class=\"en-inline\">End</span><span class=\"nl-inline\">End</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Fitse</span></th></tr></thead><tbody>";
                dest = dest.concat(tempFutureBookings);
                var length = parseInt(response.future_bookings)+parseInt(response.previous_bookings);
                while (i < length)
                {
                    var day=response.booking[i].day;
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

                    var tempFutureBookings ="<tr><td>"+day+"</td><td>"+building_start_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_start+"</td><td>"+building_end_fr+" <span class=\"fr-inline\">à</span><span class=\"en-inline\">at</span><span class=\"nl-inline\">om</span> "+hour_end+"</td><td>"+frame_number+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a></td>";
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
            }
        });
    }

    function get_address_building(buildingCode){
        return $.ajax({
            url: 'include/get_address_building.php',
            type: 'post',
            data: { "buildingCode": buildingCode},
            success: function(text){
            }
            })
    }

    function get_address_domicile(){
        <?php include 'include/connexion.php';	
        $sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY from customer_referential aa where aa.EMAIL='$user'";
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
        $sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY from customer_referential aa where aa.EMAIL='$user'";
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
                                <li class="fr hidden fleetmanager"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-check-square-o"></i>Fleet manager</a> </li>
                                <li class="en hidden fleetmanager"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-check-square-o"></i>Fleet manager</a> </li>
                                <li class="nl hidden fleetmanager"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-check-square-o"></i>Fleet manager</a> </li>
                            </ul>

                            <div class="tabs-content">
                                <div class="tab-pane active" id="reserver">
                                    <form id="search-bikes-form" action="include/search-bikes.php" method="post">                    
                                        <div class="form-group">  
                                           <label for="booking_day_form" class="col-sm-12 fr">A quelle date voulez-vous prendre le vélo ?</label>
                                           <label for="booking_day_form" class="col-sm-12 en">When do you want to book a bike ?</label>
                                           <label for="booking_day_form" class="col-sm-12 nl">Wanneer wil je een fiets boeken?</label>                                      
                                            <div class="form-group col-sm-5" id="booking_day_form"></div>                                                                         

                                            <div class="form-group col-sm-5" id="booking_month_form"></div>                                                                         


                                            <div class="form-group col-sm-5" id="start_building_form"></div>                                                                         
                                            <div class="form-group col-sm-5" id="deposit_building_form"></div>                                                                         
                                            <div class="form-group col-sm-5">                                       
                                                 <label for="search-bikes-form-intake-hour" class="fr">À quelle heure voulez-vous prendre le vélo?</label>									     
                                                 <label for="search-bikes-form-intake-hour" class="en">When do you want to take the bike?</label>									     
                                                 <label for="search-bikes-form-intake-hour" class="nl">Wanneer wil je de fiets nemen?</label><span class="en"><br /></span>								     
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
                                            <div class="form-group col-sm-5">                                      
                                                 <label for="search-bikes-form-deposit-hour" class="fr">À quelle heure voulez-vous rendre le vélo?</label>									  
                                                 <label for="search-bikes-form-deposit-hour" class="en">When do you want to deposit the bike?</label>									  
                                                 <label for="search-bikes-form-deposit-hour" class="nl">Wanneer wil je de fiets storten?</label>									  

                                                 <select id="search-bikes-form-deposit-hour" name="search-bikes-form-deposit-hour" class="form-control">									           
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
                                            <input type="text" class="hidden" id="search-bikes-form-frame-number" name="search-bikes-form-frame-number" value="<?php echo $row['FRAME_NUMBER'] ?>" />                               
                                        </div> 

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
                                            constructBuildingForm(response.clientConditions.bookingDays, response.clientConditions.administrator, response.clientConditions.assistance);
                                            
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
                                                                    <p class=\"subtitle\">"+ bikeFrameNumber +"</p>\
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

                                                            document.getElementById('daySpan').innerHTML = document.getElementById("search-bikes-form-day").value;
                                                            document.getElementById('monthSpan').innerHTML = document.getElementById("search-bikes-form-month").value;
                                                            document.getElementById('yearSpan').innerHTML = "18";
                                                            document.getElementById('hourStartSpan').innerHTML = document.getElementById("search-bikes-form-intake-hour").value
                                                            document.getElementById('hourEndSpan').innerHTML = document.getElementById("search-bikes-form-deposit-hour").value
                                                            document.getElementById('startBuildingSpan').innerHTML = document.getElementById('search-bikes-form-intake-building').options[document.getElementById('search-bikes-form-intake-building').selectedIndex].text;

                                                            document.getElementById('endBuildingSpan').innerHTML = document.getElementById('search-bikes-form-deposit-building').options[document.getElementById('search-bikes-form-deposit-building').selectedIndex].text;

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
                                
                                
                                <div class="tab-pane" id="fleetmanager">

                                    <table class="table table-condensed"><h4 class="fr">Statistiques</h4>
                                        <tbody>
                                            Nombre de vélos en circulation:<a data-target="#BikesListing" data-toggle="modal" href="#"><span id="BikesInCompany"></span></a><br/>
                                            Nombre de réservations effectuées depuis le 1er Janvier par les employés: <span id="kmsCompany"></span>.
                                        </tbody>
                                    </table>
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
                        <br>
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
                                                    window.location.href = "mykameo.php";
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
                        <h4><span class="fr"> Jour : </span></h4>
                        <h4><span class="en"> Start : </span></h4>
                        <h4><span class="nl"> Start : </span></h4>

                        <p><span id="daySpan"></span>
                        /
                        <span id="monthSpan"></span> 
                        /
                        <span id="yearSpan"></span></p> 
                        </div>
                        
                        <div class="col-sm-10">
						<h4>Prise en charge du vélo</h4>
						</div>
                        
                        <div class="col-sm-5">
                        <h4><span class="fr"> Heure : </span></h4>
                        <h4><span class="en"> at : </span></h4>
                        <h4><span class="nl"> at : </span></h4>


                       	<p><span id="hourStartSpan"></span></p> 
                       	</div>

                       <div class="col-sm-5">
                        <h4><span class="fr" >Lieu :</span></h4>
                        <h4><span class="en" >from</span></h4>
                        <h4><span class="nl" >from</span></h4>

                        <p><span id="startBuildingSpan"></span></p>
                        </div>
                        
                        
						
						<div class="col-sm-10">
						<h4>Remise du vélo</h4>
						</div>
						
						<div class="col-sm-5">
                        <h4><span class="fr">Heure : </span></h4>
                        <h4><span class="en">Bike deposit : </span></h4>
                        <h4><span class="nl">Bike deposit : </span></h4>                            

                        <p><span id="hourEndSpan"></span></p>
                        </div> 

						<div class="col-sm-5">
                        <h4><span class="fr" >Lieu :</span></h4>
                        <h4><span class="en" >from</span></h4>
                        <h4><span class="nl" >from</span></h4>

                        <p><span id="endBuildingSpan"></span></p>
                        </div>

                       <div class="col-sm-10">
                        <h4>Votre vélo: </h4>
                            <div class="col-md-4">
                            <img src="" id="resumeBikeImage" alt="image" />
                            </div>  
                        </div>    
                        <form id="widget-new-booking" class="form-transparent-grey" action="include/new_booking.php" role="form" method="post">
                                                        
                            <input id="widget-new-booking-timestamp-start" name="widget-new-booking-timestamp-start" type="hidden">
                            <input id="widget-new-booking-timestamp-end" name="widget-new-booking-timestamp-end" type="hidden">                            
                            <input id="widget-new-booking-building-start" name="widget-new-booking-building-start" type="hidden">
                            <input id="widget-new-booking-building-end" name="widget-new-booking-building-end" type="hidden">
                            <input id="widget-new-booking-frame-number" name="widget-new-booking-frame-number" type="hidden">
                            <input id="widget-new-booking-mail-customer" name="widget-new-booking-mail-customer" type="hidden" value="<?php echo $user; ?>">

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


<div class="modal fade" id="BikesListing" tabindex="0" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
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

<div class="modal fade" id="bikeDetailsFull" tabindex="0" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3 class="fr-inline">Référence du vélo :</h3>
						<h3 class="en-inline">Bike Reference:</h3>
						<h3 class="nl-inline">Bike Reference :</h3>
                        <p span class="bikeReference">coucou</p>
						
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
						<h4>Informations relatives au contrat</h4>
						</div>
                        
                        <div class="col-sm-5">
                        <h4><span class="fr"> Type de contrat : </span></h4>
                        <h4><span class="en"> Contract type: </span></h4>
                        <h4><span class="nl"> Contract type : </span></h4>


                       	<p><span class="contractType"></span></p> 
                       	</div>

                       <div class="col-sm-5">
                        <h4><span class="fr" >Date de début :</span></h4>
                        <h4><span class="en" >Start date:</span></h4>
                        <h4><span class="nl" >Start date :</span></h4>

                        <p><span class="startDateContract"></span></p>
                        </div>

                        <div class="col-sm-5">
                            <h4><span class="fr" >Date de fin :</span></h4>
                            <h4><span class="en" >End date:</span></h4>
                            <h4><span class="nl" >End date :</span></h4>
                        <p><span class="endDateContract"></span></p>
                        </div>

                        <div class="col-sm-5">
                            <h4><span class="fr" >Référence pour assistance :</span></h4>
                            <h4><span class="en" >Assistance reference:</span></h4>
                            <h4><span class="nl" >Assistance reference :</span></h4>
                        <p><span class="assistanceReference"></span></p>
                        </div>

                       <div class="col-sm-10">
                        <h4>Votre vélo: </h4>
                            <div class="col-md-4">
                            <img src="" class="bikeImage" alt="image" />
                            </div>  
                        </div>    
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



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
                                        <div class="col-sm-3"</div>
                                        <label for="password"  class="fr">Mot de passe</label>
                                        <label for="password"  class="en">Password</label>
                                        <label for="password"  class="nl">Wachtwoord</label>
                                        </div>
                                        <div class="col-sm-9"</div>
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
						<p class="fr">Donnez votre numéro de contrat </span>
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


<script type="text/javascript">
	function initializeAssistance2() {
		document.getElementById('widget-assistance-form-message').value="";
		document.getElementById('widget-assistance-form-message-attachment').value="";
		
	}
	function initializeEntretien2() {
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

</html>
