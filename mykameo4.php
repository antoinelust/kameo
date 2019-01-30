<?php 
session_start();
include 'include/header.php';
// checkAccess();
$user=$_SESSION['userID'];
$langue=$_SESSION['langue'];

include 'include/connexion.php';
$sql= "select national_registry_number, FRAME_NUMBER from customer_referential where EMAIL='$user'";
if ($conn->query($sql) === FALSE) {
    echo "erreur";
}
$result = mysqli_query($conn, $sql);        
$resultat = mysqli_fetch_assoc($result);
$userID = $resultat['national_registry_number'];
$userFrameNumber = $resultat['FRAME_NUMBER'];
$conn->close();
?>

<script type="text/javascript">
    
    
    
function loadClientConditions(){
        var userID= "<?php echo $userID; ?>";
        var langue= "<?php echo $langue; ?>";

        $.ajax({
            url: 'include/load_client_conditions.php',
            type: 'post',
            data: { "userID": userID},
            success: function(response) {
            }
        });
}
    
    
    
// Goal of this function is to delete the block with result of research
function hideResearch(){
    document.getElementById('velos').innerHTML = "";        
}
    // Goal of this function is to construct the reasearch fields 
function constructBuidlingForm() {
    
    // 1st step: days and month fileds
    
    var daysFR=['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
    var daysEN=['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var daysNL=['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'];
    
    
    var startDate = new Date();    
    var i=0;
    var j=0;
    var dest ="<select id=\"search-bikes-form-day\" name=\"search-bikes-form-day\"  class=\"form-control\">";
    
    
    var tempDate = new Date();
    var month = [tempDate.getMonth()];
    
    while(i<=3){
        var dayFR = daysFR[tempDate.getDay()];
        var dayEN = daysEN[tempDate.getDay()];
        var dayNL = daysNL[tempDate.getDay()];
        if(tempDate.getDay()=="0" || tempDate.getDay()=="6"){
            var bookingDay="<option value=\""+tempDate.getDate()+"\" class=\"fr\" disabled>"+dayFR+" "+tempDate.getDate()+"</option><option value=\""+tempDate.getDate()+"\" class=\"en\" disabled>"+dayEN+" "+tempDate.getDate()+"</option><option value=\""+tempDate.getDate()+"\" class=\"nl\" disabled>"+dayNL+" "+tempDate.getDate()+"</option>";            
        } 
        else {
            
            var bookingDay="<option value=\""+tempDate.getDate()+"\" class=\"form-control fr\">"+dayFR+" "+tempDate.getDate()+"</option><option value=\""+tempDate.getDate()+"\" class=\"form-control en\">"+dayEN+" "+tempDate.getDate()+"</option><option value=\""+tempDate.getDate()+"\" class=\"form-control nl\">"+dayNL+" "+tempDate.getDate()+"</option>";
            i++;            
        }
        
        if(tempDate.getMonth() != month[j]){
            j++;
            month[j]=tempDate.getMonth();
        }
        
        dest = dest.concat(bookingDay);
        tempDate.setDate(tempDate.getDate()+1);
    }
    var bookingDay="</select>";
    dest = dest.concat(bookingDay);
    document.getElementById('booking_day_form').innerHTML=dest;

    
    var monthFR=['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    var monthEN=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var monthNL=['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
    
    var i=0;
    var dest ="<select name=\"search-bikes-form-month\" id=\"search-bikes-form-month\" class=\"form-control\">";
    while(month[i]){
        var month = tempDate.getMonth()+1;
        var bookingMonth="<option value=\""+month+"\" class=\"form-control fr\">"+monthFR[tempDate.getMonth()]+"</option><option value=\""+month+"\" class=\"form-control en\">"+monthEN[tempDate.getMonth()]+"</option><option value=\""+month+"\" class=\"form-control nl\">"+monthNL[tempDate.getMonth()]+"</option>";
            i++;   
        dest = dest.concat(bookingMonth);

    }
    var bookingMonth="</select>";
    dest = dest.concat(bookingMonth);
    document.getElementById('booking_month_form').innerHTML=dest;

    
    // 2nd step: intake and deposit buildings
    var userID= "<?php echo $userID; ?>";
    var langue= "<?php echo $langue; ?>";
    var userFrameNumber = "<?php echo $userFrameNumber; ?>";
    var i=0;
    
    $.ajax({
        url: 'include/booking_building_form.php',
        type: 'post',
        data: { "userFrameNumber": userFrameNumber},
        success: function(response) {
            
            var dest="";
            var tempBuilding="<label for=\"search-bikes-form-intake-building\" class=\" fr\">Où voulez-vous prendre le vélo?</label><label for=\"search-bikes-form-intake-building\" class=\"en\">Where is your departure ?</label><label for=\"search-bikes-form-intake-building\" class=\"nl\">Where is your departure ?</label><br /><select id=\"search-bikes-form-intake-building\" name=\"search-bikes-form-intake-building\" class=\"form-control\">";        
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
            var tempBuilding="<label for=\"search-bikes-form-deposit-building\" class=\"fr\">Où voulez-vous rendre le vélo?</label><label for=\"search-bikes-form-deposit-building\" class=\"en\">Where is your departure ?</label><label for=\"search-bikes-form-deposit-building\" class=\"nl\">Where is your departure ?</label><br /><select id=\"search-bikes-form-deposit-building\" name=\"search-bikes-form-deposit-building\" class=\"form-control\">";        
            dest = dest.concat(tempBuilding);

            while (j < response.buildingNumber){
                j++;
                var building_code=response.building[j].building_code;
                var building_fr=response.building[j].fr;
                var building_en=response.building[j].en;
                var building_nl=response.building[j].nl;
                
                var tempBuilding="<option value=\""+building_code+"\" class=\"fr\">"+building_fr+"</option><option value=\""+building_code+"\" class=\"en\">"+building_en+"</option><option value=\""+building_code+"\" class=\"nl\">"+building_nl+"</option>";
                dest = dest.concat(tempBuilding);
            }
            var tempBuilding="</select>";
            dest = dest.concat(tempBuilding);
                document.getElementById('deposit_building_form').innerHTML=dest;            
        }
    });
}
    
function showBooking(bookingID){
    var dest="";
    var langue= "<?php echo $langue; ?>";

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
                    var dest="<li>Name: "+name+" "+surname+"</li><li>Phone Number:"+phone+"</li><li>Mail: "+mail+"</li><li>Deposit bike on"+depositDay+" at "+depositHour+"</li>";
            }
            else if (langue=="en"){
                    var dest="<li>Name: "+name+" "+surname+"</li><li>Phone Number:"+phone+"</li><li>Mail: "+mail+"</li><li>Deposit bike on"+depositDay+" at "+depositHour+"</li>";
            } else{
                    var dest="<li>Nom et prénom: "+name+" "+surname+"</li><li>Numéro de téléphone:"+phone+"</li><li>Adresse mail: "+mail+"</li><li>Dépose le vélo le "+depositDay+" à "+depositHour+"</li>";
            }
            document.getElementById('futureBookingBefore').innerHTML = dest;

            var name = response.clientAfter.name;
            var surname = response.clientAfter.surname;
            var phone = response.clientAfter.phone;
            var mail = response.clientAfter.mail;
            var intakeDay = response.clientAfter.intakeDay;
            var intakeHour = response.clientAfter.intakeHour;
            
            console.log("client apres : " +response.clientAfter.name);
            
            if(typeof response.clientAfter.name == 'undefined'){
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
                    var dest="<li>Name: "+name+" "+surname+"</li><li>Phone Number:"+phone+"</li><li>Mail: "+mail+"</li><li>Will take bike on"+intakeDay+" at "+intakeHour+"</li>";
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
    var langue= "<?php echo $langue; ?>";

    $.ajax({
        url: 'include/cancel_booking.php',
        type: 'post',
        data: { "bookingID": bookingID},
        success: function(text){
            $.notify({
                message: text.message
            }, {
                type: text.response
            });        
        }
    });
    
    getHistoricBookings();
}    
    
function getHistoricBookings() {
    var userID= "<?php echo $userID; ?>";
    var langue= "<?php echo $langue; ?>";
        $.ajax({
        url: 'include/get_historic_bookings.php',
        type: 'post',
        data: { "userID": userID},
        success: function(response) {
        var i=0;
        var dest="";
            
        if(langue=="nl"){
                    var tempHistoricBookings="<table class=\"table table-condensed\"><h4>Previous Bookings:</h4><thead><tr><th>Date</th><th>Start</th><th>End</th><th>Bike</th></tr></thead><tbody>";
        }
        else if (langue=="en"){
                    var tempHistoricBookings="<table class=\"table table-condensed\"><h4>Previous Bookings:</h4><thead><tr><th>Date</th><th>Start</th><th>End</th><th>Bike</th></tr></thead><tbody>";
        } else{
                    var tempHistoricBookings="<table class=\"table table-condensed\"><h4>Réservations précédentes:</h4><thead><tr><th>Date</th><th>Départ</th><th>Arrivée</th><th>Vélo</th></tr></thead><tbody>";
        }
        
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
            
            if(langue=="nl"){
                var tempHistoricBookings ="<tr class=\"fr\"><td>"+day+"</td><td>"+building_start_nl+" at "+hour_start+"</td><td>"+building_end_nl+" at "+hour_end+"</td><td>"+frame_number+"</td></tr>";
            }
            else if (langue=="en"){
                var tempHistoricBookings ="<tr><td>"+day+"</td><td>"+building_start_en+" at "+hour_start+"</td><td>"+building_end_en+" at "+hour_end+"</td><td>"+frame_number+"</td></tr>";
            } else{
                var tempHistoricBookings ="<tr><td>"+day+"</td><td>"+building_start_fr+" à "+hour_start+"</td><td>"+building_end_fr+" à "+hour_end+"</td><td>"+frame_number+"</td></tr>";
            }
            dest = dest.concat(tempHistoricBookings);
            i++;

        }
        var tempHistoricBookings="</tbody></table>";
        dest = dest.concat(tempHistoricBookings);

        //affichage du résultat de la recherche
        document.getElementById('historicBookings').innerHTML = dest;

        //Booking futurs
        
        var dest="";
            
        if(langue=="nl"){
                    var tempFutureBookings="<table class=\"table table-condensed\"><h4>Next Bookings:</h4><thead><tr><th>Date</th><th>Start</th><th>End</th><th>Bike</th></tr></thead><tbody>";
        }else if (langue=="en"){
                    var tempFutureBookings="<table class=\"table table-condensed\"><h4>Next Bookings:</h4><thead><tr><th>Date</th><th>Start</th><th>End</th><th>Bike</th></tr></thead><tbody>";
        } else{
                    var tempFutureBookings="<table class=\"table table-condensed\"><h4>Vos réservations futures:</h4><thead><tr><th>Date</th><th>Départ</th><th>Arrivée</th><th>Vélo</th></tr></thead><tbody>";
        }
        
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
            
            if(langue=="nl"){
                var tempFutureBookings ="<tr><td>"+day+"</td><td>"+building_start_nl+" at <strong>"+hour_start+"</strong></td><td>"+building_end_nl+" at <strong>"+hour_end+"</strong></td><td>"+frame_number+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a></td></td></tr>";
            } else if (langue=="en"){
                var tempFutureBookings ="<tr><td>"+day+"</td><td>"+building_start_en+" at <strong>"+hour_start+"</strong></td><td>"+building_end_en+" at <strong>"+hour_end+"</strong></td><td>"+frame_number+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a></td></td></tr>";
            } else{
                var tempFutureBookings ="<tr><td>"+day+"</td><td>"+building_start_fr+" à <strong>"+hour_start+"</strong></td><td>"+building_end_fr+" à <strong>"+hour_end+"</strong></td><td>"+frame_number+"</td><td><a class=\"button small green rounded effect\" onclick=\"showBooking("+booking_id+")\"><span>+</span></a>";
            }
            if(annulation){
                var tempAnnulation = "</td><td><a class=\"button small red rounded effect\" onclick=\"cancelBooking("+booking_id+")\"><i class=\"fa fa-times\"></i><span>annuler</span></a></td></td></tr>";
                tempFutureBookings = tempFutureBookings.concat(tempAnnulation);
            } else{
                var tempAnnulation = "</td></td></tr>";
                tempFutureBookings = tempFutureBookings.concat(tempAnnulation);

            }
            dest = dest.concat(tempFutureBookings);
            i++;

        }
        var tempFutureBookings="</tbody></table>";
        dest = dest.concat(tempFutureBookings);

        //affichage du résultat de la recherche
        document.getElementById('futureBookings').innerHTML = dest;

		}
    });
}
getHistoricBookings();

</script>
<?php

if($user==NULL){
    ?>
    <script>window.location.href = "http://www.kameobikes.com/index.php?deconnexion=true";</script>
    <?php
    //verifier pourquoi ne marche pas avec un ipad ! !
    //$user="pierre-yves.adant@kameobikes.com";
}

include 'include/connexion.php';
/* $sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY,
cc.MODEL_FR \"bike_Model_FR\", cc.MODEL_EN \"bike_Model_EN\", cc.MODEL_NL \"bike_Model_NL\", 
dd.MODEL_FR \"tires_Model_FR\", dd.MODEL_EN \"tires_Model_EN\", dd.MODEL_NL \"tires_Model_NL\",
ee.MODEL_FR \"saddle_Model_FR\", ee.MODEL_EN \"saddle_Model_EN\", ee.MODEL_NL \"saddle_Model_NL\",
ff.MODEL_FR \"handle_Model_FR\", ff.MODEL_EN \"handle_Model_EN\", ff.MODEL_NL \"handle_Model_NL\",
jj.TRANSMISSION_TYPE_FR \"transmission_type_FR\", jj.TRANSMISSION_TYPE_EN \"transmission_type_EN\", jj.TRANSMISSION_TYPE_NL \"transmission_type_NL\",
kk.ANTIVOL_FR \"antivol_FR\", kk.ANTIVOL_EN \"antivol_EN\", kk.ANTIVOL_NL \"antivol_NL\",
gg.COLOR_FR \"pedal_Color_FR\", gg.COLOR_EN \"pedal_Color_EN\", gg.COLOR_NL \"pedal_Color_NL\",
hh.COLOR_FR \"handle_Color_FR\", hh.COLOR_EN \"handle_Color_EN\", hh.COLOR_NL \"handle_Color_NL\",
ii.COLOR_FR \"wires_Color_FR\", ii.COLOR_EN \"wires_Color_EN\", ii.COLOR_NL \"wires_Color_NL\"
from customer_referential aa, customer_bikes bb, bike_models cc, tires_model dd, saddle_model ee, handle_model ff, transmission_type jj, antivol kk, color_proposed gg, color_proposed hh, color_proposed ii 
where aa.EMAIL='$user' and aa.FRAME_NUMBER=bb.FRAME_NUMBER and bb.TYPE=cc.ID and bb.ANTIVOL=kk.ID
and bb.tires_MODEL=dd.ID and bb.SADDLE_MODEL=ee.ID and bb.handle_MODEL=ff.ID and bb.transmission_type=jj.ID
and bb.PEDAL_COLOR=gg.COLOR_ID and bb.HANDLE_COLOR=hh.COLOR_ID and bb.WIRES_COLOR=ii.COLOR_ID"; */
	
$sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY from customer_referential aa where aa.EMAIL='$user'";
	
	
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	
	
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
                            <h2>MY KAMEO</h2>
                        </div>
						<?php
						//include 'include/meteo.php';
						?>
						<br />
                        <!--<img src="images/meteo_300px.jpg" class="img-responsive img-rounded" alt="meteo du jour">-->

						<div class="col-md-12"> 
                        <a class="button small red-dark button-3d rounded icon-right" data-target="#probleme" data-toggle="modal" href="#">
						<span class="fr">Signaler un problème</span>
						<span class="en">Report an issue</span>
						<span class="nl">Een probleem melden</span>
						</a>
						</div>	
						
                        
                        <br>
                        <?php 
						if (ctype_alpha(substr($row["FRAME_NUMBER"],0,3))){
                            
                            
?>
                          
                          
               <!--ce form ci permet de ne pas avoir un bug.-->
               <form action="#" method="post">
               </form>

                <div class="col-md-12">  		
                    <div id="tabs-05c" class="tabs color tabs radius">
                        <ul class="tabs-navigation">
                            <li class="active fr"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Réserver un vélo</a> </li>
                            <li class="active en"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Book a bike</a> </li>
                            <li class="active nl"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Book a bike</a> </li>
                            <li class="fr"><a href="#reservations" onclick="hideResearch()"><i class="fa fa-check-square-o"></i>Vos réservations</a> </li>
                            <li class="en"><a href="#reservations" onclick="hideResearch()"><i class="fa fa-check-square-o"></i>Your bookings</a> </li>
                            <li class="nl"><a href="#reservations" onclick="hideResearch()"><i class="fa fa-check-square-o"></i>Your bookings</a> </li>
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
                                         <label for="search-bikes-form-intake-hour">À quelle heure voulez-vous prendre le vélo?</label><br />									     
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
                                         <label for="search-bikes-form-deposit-hour">À quelle heure voulez-vous rendre le vélo?</label><br />									  
                                         
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
                                   constructBuidlingForm();
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


                                                    } else {
                                                        $.notify({
                                                            message: text.message
                                                        }, {
                                                            type: 'success'
                                                        });    
                                                        var i=1;
                                                        var dest = "";
                                                        while (i <= text.length)
                                                        {
                                                            timestampStart=text.timestampStartBooking;
                                                            buildingStart=text.buidlingStart;
                                                            timestampEnd=text.timestampEndBooking;
                                                            buildingEnd=text.buildingEnd;
                                                            
                                                            var bikeFrameNumber=text.bike[i].frameNumber;
                                                            var bikeType=text.bike[i].type;
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
                                                                <h4>Pameo</h4>\
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
                                                        //document.getElementById('startBuildingSpan').innerHTML = document.getElementById("search-bikes-form-intake-building").text;
                                                        document.getElementById('startBuildingSpan').innerHTML = document.getElementById('search-bikes-form-intake-building').options[document.getElementById('search-bikes-form-intake-building').selectedIndex].text;

                                                        //document.getElementById('endBuildingSpan').innerHTML = document.getElementById("search-bikes-form-deposit-building").text;      
                                                        document.getElementById('endBuildingSpan').innerHTML = document.getElementById('search-bikes-form-deposit-building').options[document.getElementById('search-bikes-form-deposit-building').selectedIndex].text;

                                                        document.getElementById('widget-new-booking-timestamp-start').value = text.timestampStartBooking;       
                                                        document.getElementById('widget-new-booking-timestamp-end').value = text.timestampEndBooking;
                                                        document.getElementById('widget-new-booking-building-start').value = document.getElementById("search-bikes-form-intake-building").value;;
                                                        document.getElementById('widget-new-booking-building-end').value = document.getElementById("search-bikes-form-deposit-building").value;

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

                            </div>


                            <div class="tab-pane" id="reservations">

                                <div data-example-id="contextual-table" class="bs-example">
<!--                                  <table class="table table-condensed">
                                  <h4>Vos anciennes réservations:</h4>
                                    <thead>
                                      <tr>
                                        <th>Date</th>
                                        <th>Départ</th>
                                        <th>Arrivée</th>
                                        <th>Vélo</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>2 avril 2018</td>
                                        <td>Rue Ste-Marie à 10h30</td>
                                        <td>Boulevard de la Sauvenière à 12h45</td>
                                        <td>Romeo ABC01</td>
                                      </tr>
                                      <tr>
                                        <td>3 avril 2018</td>
                                        <td>Boulevard de la sauvenière à 12h30</td>
                                        <td>Quai Marcellis à 16h15</td>
                                        <td>Romeo ABC01</td>
                                      </tr>
                                    </tbody>
                                  </table>-->
                                  <span id="historicBookings"></span>
                                </div>

                                <div class="seperator"></div>

                                <div data-example-id="contextual-table" class="bs-example">
<!--                                  <table class="table table-condensed">
                                  <h4>Vos prochaines réservations:</h4>
                                    <thead>
                                      <tr>
                                        <th>Date</th>
                                        <th>Départ</th>
                                        <th>Arrivée</th>
                                        <th>Vélo</th>
                                        <th></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>20 avril 2018</td>
                                        <td>Rue Ste-Marie à 10h30</td>
                                        <td>Quai Marcellis à 12h45</td>
                                        <td>Romeo ABC03</td>
                                        <td><a class="button small green rounded effect" data-target="#1" data-toggle="modal" href="#"><span>+</span></a></td></td>
                                      </tr>
                                      <tr>
                                        <td>27 avril 2018</td>
                                        <td>Quai Marcellis à 16h30</td>
                                        <td>Rue Ste-Marie à 17h15</td>
                                        <td>Romeo ABC03</td>
                                        <td><a class="button small green rounded effect" data-target="#2" data-toggle="modal" href="#"><span>+</span></a></td>
                                      </tr>
                                    </tbody>
                                  </table>-->                      
                                    <span id="futureBookings"></span>
                                </div>


                            </div>
                        </div>
                    </div>
                    
      <h3>Mes trajets "maison - boulot" à vélo</h3>
      <div class="visible-lg visible-md">               
      <div class="accordion toggle green">
			<div class="ac-item">
				<h5 class="ac-title">Semaine 31</h5>
				<div class="ac-content">
					<div data-example-id="contextual-table" class="bs-example">
				        <table class="table">
				          <tbody>
				            <tr class="succes">
				              <td>Lundi 30 <input type="checkbox" value=""></td>
				              <td>Mardi 31 <input type="checkbox" value=""></td>
				              <td>Mercredi 1 <input type="checkbox" value=""></td>
				              <td>Jeudi 2 <input type="checkbox" value=""></td>
				              <td>Vendredi 3 <input type="checkbox" value=""></td>
				              <td>Samedi 4 <input type="checkbox" value=""></td>
				              <td>Dimanche 5 <input type="checkbox" value=""></td>
				            </tr>
				            
				          </tbody>
				        </table>
				      </div>
				</div>
			</div>
			<div class="ac-item backgroundgreen">
				<h5 class="ac-title">Semaine 32</h5>
				<div class="ac-content">
					<div data-example-id="contextual-table" class="bs-example">
				        <table class="table">
				          <tbody>
				            <tr class="succes">
				              <td>Lundi 6 <input type="checkbox" value=""></td>
				              <td>Mardi 7 <input type="checkbox" value=""></td>
				              <td>Mercredi 8 <input type="checkbox" value=""></td>
				              <td>Jeudi 9 <input type="checkbox" value=""></td>
				              <td>Vendredi 10 <input type="checkbox" value=""></td>
				              <td>Samedi 11 <input type="checkbox" value=""></td>
				              <td>Dimanche 12 <input type="checkbox" value=""></td>
				            </tr>
				            
				          </tbody>
				        </table>
				      </div>
				</div>
			</div>
			<div class="ac-item">
				<h5 class="ac-title">Semaine 33</h5>
				<div class="ac-content">
					<div data-example-id="contextual-table" class="bs-example">
				        <table class="table">
				          <tbody>
				            <tr class="succes">
				              <td>Lundi 13 <input type="checkbox" value=""></td>
				              <td>Mardi 14 <input type="checkbox" value=""></td>
				              <td>Mercredi 15 <input type="checkbox" value=""></td>
				              <td>Jeudi 16 <input type="checkbox" value=""></td>
				              <td>Vendredi 17 <input type="checkbox" value=""></td>
				              <td>Samedi 18 <input type="checkbox" value=""></td>
				              <td>Dimanche 19 <input type="checkbox" value=""></td>
				            </tr>
				            
				          </tbody>
				        </table>
				      </div>
				</div>
			</div>
		</div>
		</div>
		
		<div class="visible-sm visible-xs">               
      <div class="accordion toggle green">
			<div class="ac-item">
				<h5 class="ac-title">Semaine 31</h5>
				<div class="ac-content">
					<div data-example-id="contextual-table" class="bs-example">
				        <table class="table">
				          <tbody>
				            <tr class="succes">
				              <td>Lun 30 <br><input type="checkbox" value=""></td>
				              <td>Mar 31 <br><input type="checkbox" value=""></td>
				              <td>Mer 1 <br><input type="checkbox" value=""></td>
				              <td>Jeu 2 <br><input type="checkbox" value=""></td>
				            </tr>
				            <tr class="succes">
				              <td>Ven 3 <br><input type="checkbox" value=""></td>
				              <td>Sam 4 <br><input type="checkbox" value=""></td>
				              <td>Dim 5 <br><input type="checkbox" value=""></td>
				            </tr>
				            
				          </tbody>
				        </table>
				      </div>
				</div>
			</div>
			<div class="ac-item backgroundgreen">
				<h5 class="ac-title">Semaine 32</h5>
				<div class="ac-content">
					<div data-example-id="contextual-table" class="bs-example">
				        <table class="table">
				          <tbody>
				            <tr class="succes">
				              <td>Lun 6 <br><input type="checkbox" value=""></td>
				              <td>Mar 7 <br><input type="checkbox" value=""></td>
				              <td>Mer 8 <br><input type="checkbox" value=""></td>
				              <td>Jeu 9 <br><input type="checkbox" value=""></td>
				            </tr>
				            <tr class="succes">
				              <td>Ven 10 <br><input type="checkbox" value=""></td>
				              <td>Sam 11 <br><input type="checkbox" value=""></td>
				              <td>Dim 12 <br><input type="checkbox" value=""></td>
				            </tr>
				            
				          </tbody>
				        </table>
				      </div>
				</div>
			</div>
			<div class="ac-item">
				<h5 class="ac-title">Semaine 33</h5>
				<div class="ac-content">
					<div data-example-id="contextual-table" class="bs-example">
				        <table class="table">
				          <tbody>
				            <tr class="succes">
				              <td>Lun 13 <br><input type="checkbox" value=""></td>
				              <td>Mar 14 <br><input type="checkbox" value=""></td>
				              <td>Mer 15 <br><input type="checkbox" value=""></td>
				              <td>Jeu 16 <br><input type="checkbox" value=""></td>
				            </tr>
				            <tr class="succes">
				              <td>Ven 17 <br><input type="checkbox" value=""></td>
				              <td>Sam 18 <br><input type="checkbox" value=""></td>
				              <td>Dim 19 <br><input type="checkbox" value=""></td>
				            </tr>
				            
				          </tbody>
				        </table>
				      </div>
				</div>
			</div>
		</div>
		</div>
        
        <div class="seperator"></div>
        
        <h3>Mes trajets "maison - boulot" à vélo 2</h3>
        	<div class="pager pager-modern text-center">
			    <a class="pager-prev" href="#"><span><i class="fa fa-chevron-left"></i>Juillet</span></a>
			    <a class="pager-all" href="#"><span class="text-green"S>Août</span></a>
			    <a class="pager-next" href="#"><span>Septembre<i class="fa fa-chevron-right"></i></span></a>
			</div>
			<br>
        <!--
        <div class="grid-system-demo jumbotron jumbotron-small jumbotron-border">
	      <div class="row seven-cols">
	        <div class="col-md-1 col-md-offset-4">Mer <b>1</b> <input type="checkbox" value=""></div>
	        <div class="col-md-1"><span>Jeu <b>2</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-1"><span>Ven <b>3</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-1"><span>Sam <b>4</b> <input type="checkbox" value=""></span> </div>
	      </div>
	      <div class="row">
	        <div class="col-md-1"><span class="grid-col-demo">Lun <b>6</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-1"><span class="grid-col-demo">Mar <b>7</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-1"><span class="grid-col-demo">Mer <b>8</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-1"><span class="grid-col-demo">Jeu <b>9</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-1"><span class="grid-col-demo">Ven <b>10</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-1"><span class="grid-col-demo text-green">Sam <b>11</b> <input type="checkbox" value=""></span> </div>
	      </div>
	      <div class="row">
	        <div class="col-md-2"><span class="grid-col-demo">Lun <b>13</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Mar <b>14</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Mer <b>15</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Jeu <b>16</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Ven <b>17</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Sam <b>18</b> <input type="checkbox" value=""></span> </div>
	      </div>
	      <div class="row">
	        <div class="col-md-2"><span class="grid-col-demo">Lun <b>20</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Mar <b>21</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Mer <b>22</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Jeu <b>23</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Ven <b>24</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Sam <b>25</b> <input type="checkbox" value=""></span> </div>
	      </div>
	      <div class="row">
	        <div class="col-md-2"><span class="grid-col-demo">Lun <b>27</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Mar <b>28</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Mer <b>29</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Jeu <b>30</b> <input type="checkbox" value=""></span> </div>
	        <div class="col-md-2"><span class="grid-col-demo">Ven <b>31</b> <input type="checkbox" value=""></span> </div>
	      </div>
	     </div>
	     -->
	     
	     <div class="container">
		  <div class="row seven-cols">
		    <div class="col-md-1 "></div>
		    <div class="col-md-1 "></div>
		    <div class="col-md-1 button small">Mer <b>1</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Jeu <b>2</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Ven <b>3</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Sam <b>4</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Dim <b>5</b> <input type="checkbox" value=""></div>
		  </div>
		  <div class="row seven-cols">
		    <div class="col-md-1 button small">Lun <b>6</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Mar <b>7</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Mer <b>8</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Jeu <b>9</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Ven <b>10</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small green">Sam <b>11</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Dim <b>12</b> <input type="checkbox" value=""></div>
		  </div>
		  <div class="row seven-cols">
		    <div class="col-md-1 button small">Lun <b>13</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Mar <b>14</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Mer <b>15</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Jeu <b>16</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Ven <b>17</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Sam <b>18</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Dim <b>19</b> <input type="checkbox" value=""></div>
		  </div>
		  <div class="row seven-cols">
		    <div class="col-md-1 button small">Lun <b>20</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Mar <b>21</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Mer <b>22</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Jeu <b>23</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Ven <b>24</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Sam <b>25</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Dim <b>26</b> <input type="checkbox" value=""></div>
		  </div>
		  <div class="row seven-cols">
		    <div class="col-md-1 button small">Lun <b>27</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Mar <b>28</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Mer <b>29</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Jeu <b>30</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1 button small">Ven <b>31</b> <input type="checkbox" value=""></div>
		    <div class="col-md-1"></div>
		    <div class="col-md-1"></div>
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
                                        <h4>Personne avant vous:</h4>
                                            <ul>
                                               <span id="futureBookingBefore"></span>
                                            </ul>
                                        <h4>Personne après vous:</h4>
                                               <span id="futureBookingAfter"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="pull-left">
                                        <button data-dismiss="modal" class="btn btn-b" type="button">Fermer</button>
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
                                        <button data-dismiss="modal" class="btn btn-b" type="button">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				
				    
				
                    <div id="velos"></div>
                </div>
<?php

						}
						else
						{
							$sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY,
							cc.MODEL_FR \"bike_Model_FR\", cc.MODEL_EN \"bike_Model_EN\", cc.MODEL_NL \"bike_Model_NL\", 
							dd.MODEL_FR \"tires_Model_FR\", dd.MODEL_EN \"tires_Model_EN\", dd.MODEL_NL \"tires_Model_NL\",
							ee.MODEL_FR \"saddle_Model_FR\", ee.MODEL_EN \"saddle_Model_EN\", ee.MODEL_NL \"saddle_Model_NL\",
							ff.MODEL_FR \"handle_Model_FR\", ff.MODEL_EN \"handle_Model_EN\", ff.MODEL_NL \"handle_Model_NL\",
							jj.TRANSMISSION_TYPE_FR \"transmission_type_FR\", jj.TRANSMISSION_TYPE_EN \"transmission_type_EN\", jj.TRANSMISSION_TYPE_NL \"transmission_type_NL\",
							kk.ANTIVOL_FR \"antivol_FR\", kk.ANTIVOL_EN \"antivol_EN\", kk.ANTIVOL_NL \"antivol_NL\",
							gg.COLOR_FR \"pedal_Color_FR\", gg.COLOR_EN \"pedal_Color_EN\", gg.COLOR_NL \"pedal_Color_NL\",
							hh.COLOR_FR \"handle_Color_FR\", hh.COLOR_EN \"handle_Color_EN\", hh.COLOR_NL \"handle_Color_NL\",
							ii.COLOR_FR \"wires_Color_FR\", ii.COLOR_EN \"wires_Color_EN\", ii.COLOR_NL \"wires_Color_NL\"
							from customer_referential aa, customer_bikes bb, bike_models cc, tires_model dd, saddle_model ee, handle_model ff, transmission_type jj, antivol kk, color_proposed gg, color_proposed hh, color_proposed ii 
							where aa.EMAIL='$user' and aa.FRAME_NUMBER=bb.FRAME_NUMBER and bb.TYPE=cc.ID and bb.ANTIVOL=kk.ID
							and bb.tires_MODEL=dd.ID and bb.SADDLE_MODEL=ee.ID and bb.handle_MODEL=ff.ID and bb.transmission_type=jj.ID
							and bb.PEDAL_COLOR=gg.COLOR_ID and bb.HANDLE_COLOR=hh.COLOR_ID and bb.WIRES_COLOR=ii.COLOR_ID";
							$result = mysqli_query($conn, $sql);
							$row = mysqli_fetch_assoc($result);
							?>
	                        <img src="images/romeo_big.jpg" class="img-responsive img-rounded" alt="Infographie">
						    <div class="table-responsive">
						      <table class="table table-striped">
						        <tbody>
								  <tr>
									<td class="fr">Type de cadre</td>
									<td class="en">Bike model</td>
									<td class="nl">Fietsmodel</td>
									<td class="fr"><?php echo $row["bike_Model_FR"] ?></td>
									<td class="en"><?php echo $row["bike_Model_EN"] ?></td>
									<td class="nl"><?php echo $row["bike_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Numéro de châssis</td>
									<td class="en">Frame number</td>
									<td class="nl">Fietsnummer</td>
										<td><?php echo $row["FRAME_NUMBER"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Type de selle</td>
									<td class="en">Saddle model</td>
									<td class="nl">Zadel model</td>
									<td class="fr"><?php echo $row["saddle_Model_FR"] ?></td>
									<td class="en"><?php echo $row["saddle_Model_EN"] ?></td>
									<td class="nl"><?php echo $row["saddle_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Type de poignées</td>
									<td class="en">Handle model</td>
									<td class="nl">Handvat model</td>
									<td class="fr"><?php echo $row["handle_Model_FR"] ?></td>
									<td class="en"><?php echo $row["handle_Model_EN"] ?></td>
									<td classd="nl"><?php echo $row["handle_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Type de pneu</td>
									<td class="en">Tires model</td>
									<td class="nl">Banden model</td>
									<td class="fr"><?php echo $row["tires_Model_FR"] ?></td>
									<td class="en"><?php echo $row["tires_Model_EN"] ?></td>
									<td class="nl"><?php echo $row["tires_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Type de transmission</td>
									<td class="en">Transmission type</td>
									<td class="nl">Transmissietype</td>
									<td class="fr"><?php echo $row["transmission_type_FR"] ?></td>
									<td class="en"><?php echo $row["transmission_type_EN"] ?></td>
									<td class="nl"><?php echo $row["transmission_type_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Couleur des pédales</td>
									<td class="en">Pedals color</td>
									<td class="nl">Pedalen kleur</td>
									<td class="fr"><?php echo $row["pedal_Color_FR"] ?></td>
									<td class="en"><?php echo $row["pedal_Color_EN"] ?></td>
									<td class="nl"><?php echo $row["pedal_Color_NL"] ?></td>
								  </tr>				
								  <tr>
									<td class="fr">Couleur des cables de frein</td>
									<td class="en">Wires color</td>
									<td class="nl">Remkabels kleur</td>
									<td class="fr"><?php echo $row["wires_Color_FR"] ?></td>
									<td class="en"><?php echo $row["wires_Color_EN"] ?></td>
									<td class="nl"><?php echo $row["wires_Color_NL"] ?></td>
								  </tr>			
								  <tr>
									<td class="fr">Couleur des poignées</td>
									<td class="en">Handle color</td>
									<td class="nl">Handvat kleur</td>
									<td class="fr"><?php echo $row["handle_Color_FR"] ?></td>
									<td class="en"><?php echo $row["handle_Color_EN"] ?></td>
									<td class="nl"><?php echo $row["handle_Color_NL"] ?></td>
								  </tr>										  
								  <tr>
									<td class="fr">Antivol</td>
									<td class="en">Locker</td>
									<td class="nl">Kastje</td>
									<td class="fr"><?php echo $row["antivol_FR"] ?></td>
									<td class="en"><?php echo $row["antivol_EN"] ?></td>
									<td class="nl"><?php echo $row["antivol_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Phares</td>
									<td class="en">Lights</td>
									<td class="nl">Licht</td>
									<td class="fr">Avant et arrière </td>
									<td class="en">Front and back lights </td>
									<td class="nl">Voor- en achterlicht </td>
								  </tr>

						        </tbody>
						      </table>
						    </div>
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
						
                        <li class="fr">Prénom
                            <small><?php echo $row["PRENOM"] ?></small>
                        </li>
						<li class="en">First Name
                            <small><?php echo $row["PRENOM"] ?></small>
                        </li>
						<li class="nl">Voornaam
                            <small><?php echo $row["PRENOM"] ?></small>
                        </li>
						
						
                        <li class="fr">Numéro de téléphone
                            <small class="phone"><?php echo $row["PHONE"] ?></small>
                        </li>
						<li class="en">Phone number
                            <small class="phone"><?php echo $row["PHONE"] ?></small>
                        </li>
						<li class="nl">Telefoonnummer
                            <small class="phone"><?php echo $row["PHONE"] ?></small>
                        </li>
						
                        <li class="fr">Adresse du domicile
                            <small><?php echo $row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'] ?></small>
                        </li>

						<li class="en">Home adress
                            <small><?php echo $row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'] ?></small>
                        </li>
						
						<li class="nl">Adress
                            <small><?php echo $row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'] ?></small>
                        </li>
						
                        <li class="fr">Lieu de travail
                            <small><?php echo $row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'] ?></small>
                        </li>

						<li class="en">Work place
                            <small><?php echo $row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'] ?></small>
                        </li>
						
						<li class="nl">Werk adress
                            <small><?php echo $row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'] ?></small>
                        </li>
											
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
                    <a class="button small green button-3d rounded icon-left" data-target="#update" data-toggle="modal" href="#">
						<span class="fr">ACTUALISER</span>
						<span class="en">UPDATE</span>
						<span class="nl">UPDATE</span>
					</a>
                    <br>
                    <br>
                    <a href="docs/test.pdf" target="_blank" title="Pdf" class="=fr">Conditions générales</a>
                    <a href="docs/test.pdf" target="_blank" title="Pdf" class="en">Terms and Conditions</a>
                    <a href="docs/test.pdf" target="_blank" title="Pdf" class="nl">Algemene voorwaarden</a>
                    <br>
                    <br>
                    <a href="docs/test.pdf" target="_blank" title="Pdf">Bike policy</a>
                    <br>
                    <br>
                    <a class="button small green button-3d rounded icon-left" data-target="#tellus" data-toggle="modal" href="#">
						<span class="fr">Partagez vos impressions</span>
						<span class="en">Tell us what you feel</span>
						<span class="nl">Vertel ons wat je voelt</span>
					</a>
					<br>
                </div>
                <!--end: widget blog articles-->
            </div>
            <!-- END: Sidebar-->
			
			
        </div>
    </div>
</section>
<!-- END: SECTION -->

<div class="modal fade" id="resume" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3 class="fr">Résumé de votre commande</h2>
						<h3 class="en">Resume</h2>
						<h3 class="nl">Geresumeerd</h2>
						
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
                                        <input type="text" name="widget-tellus-form-subject" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message"  class="fr">Message</label>
									<label for="message"  class="en">Message</label>
									<label for="message"  class="nl">Bericht</label>
                                    <textarea type="text" name="widget-tellus-form-message" rows="5" class="form-control required"></textarea>
                                </div>
                                <input type="text" class="hidden" id="widget-tellus-form-antispam" name="widget-tellus-form-antispam" value="" />
                                <button  class="fr button small green button-3d rounded icon-left" type="submit"><i class="fa fa-paper-plane"></i>Envoyer</button>
								<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
								<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>
                            </form>
							<script type="text/javascript">
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
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
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
									<h3 class="fr">Informations générales</h3>
									<h3 class="en">General information</h3>
									<h3 class="nl">Algemene informatie</h3>
                                    <div class="form-group col-sm-12">
                                        <label for="telephone"  class="fr">Numéro de téléphone</label>
										<label for="telephone"  class="en">Phone number</label>
										<label for="telephone"  class="nl">Telefoonnumber</label>
                                        <input type="text" name="widget-update-form-phone" class="form-control required" value="<?php echo $row["PHONE"] ?>" autocomplete="tel">
                                    </div>
									<h4 class="col-md-10 text-green fr">Domicile</h4>
									<h4 class="col-md-10 text-green en">Home</h4>
									<h4 class="col-md-10 text-green nl">Thuis</h4>
										 <div class="form-group col-sm-12">
											<label for="email"  class="fr">Adresse</label>
											<label for="email"  class="en">Adress</label>
											<label for="email"  class="nl">Adres</label>
											<input type="text" name="widget-update-form-adress" class="form-control required" value="<?php echo $row['ADRESS'] ?>">
										</div>
										<div class="form-group col-sm-12">
											<label for="velo"  class="fr">Code Postal</label>
											<label for="velo"  class="en">Postal Code</label>
											<label for="velo"  class="nl">Postcode</label>
											<input type="text" name="widget-update-form-post-code" class="form-control required" value="<?php echo $row['POSTAL_CODE'] ?>" autocomplete="postal-code">
										</div>
										<div class="form-group col-sm-12">
											<label for="chassis"  class="fr">Commune</label>
											<label for="chassis"  class="en">City</label>
											<label for="chassis"  class="nl">Gemeente</label>
											<input type="text" name="widget-update-form-city" class="form-control required" value="<?php echo $row['CITY'] ?>" autocomplete="address-level2">
										</div>
									<h4 class="col-md-10 text-green fr">Lieu de travail</h4>
									<h4 class="col-md-10 text-green nl">Werk</h4>
									<h4 class="col-md-10 text-green en">Work place</h4>
										<div class="form-group col-sm-12">
											<label for="email"  class="fr">Adresse</label>
											<label for="email"  class="en">Adress</label>
											<label for="email"  class="nl">Adres</label>
											<input type="text" name="widget-update-form-work-adress" class="form-control required" value="<?php echo $row['WORK_ADRESS'] ?>" autocomplete="off">
										</div>
										<div class="form-group col-sm-12">
											<label for="velo"  class="fr">Code Postal</label>
											<label for="velo"  class="en">Postal Code</label>
											<label for="velo"  class="nl">Postcode</label>
											<input type="text" name="widget-update-form-work-post-code" class="form-control required" value="<?php echo $row['WORK_POSTAL_CODE'] ?>" autocomplete="off">
										</div>
										<div class="form-group col-sm-12">
											<label for="chassis"  class="fr">Commune</label>
											<label for="chassis"  class="en">City</label>
											<label for="chassis"  class="nl">Gemeente</label>
											<input type="text" name="widget-update-form-work-city" class="form-control required" value="<?php echo $row['WORK_CITY'] ?>" autocomplete="off">
										</div>											
										
											<div class="col-sm-3"</div>
											<label for="chassis"  class="fr">Mot de passe</label>
											<label for="chassis"  class="en">Password</label>
											<label for="chassis"  class="nl">Wachtwoord</label>
											</div>
											<div class="col-sm-9"</div>
											<a class="text-green fr" onclick="updatePassword()">Actualiser</a>
											<a class="text-green en" onclick="updatePassword()">Update</a>
											<a class="text-green nl" onclick="updatePassword()">Update</a>
											</div>
                                            
                                            <div class="col-sm-12"</div>
                                            <span id="widget-update-form-password-text"></span>
											<input type="password" id="widget-update-form-password" name="widget-update-form-password" class="form-control required" value="********" autocomplete="off" readonly>
											</div>
										
										
									<input type="text" class="hidden" id="widget-contact-form-antispam" name="widget-updateInfo-antispam" value="" />
								</div>
								<button  class="fr button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Envoyer</button>
								<button  class="en button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Send</button>
								<button  class="nl button small green button-3d rounded icon-left" type="submit" ><i class="fa fa-paper-plane"></i>Verzenden</button>
                                
                            </form>
							<script type="text/javascript">
                                function updatePassword(){
                                    document.getElementById('widget-update-form-password-text').innerHTML="<span class=\"fr\">Votre Nouveau mot de passe :</span><span class=\"nl\">Your new password :</span><span class=\"en\">Your new password:</span>";
                                    document.getElementById('widget-update-form-password').removeAttribute('readonly');
                                    displayLanguage();
                                    
                                    var langue = getLanguage();
                                    console.log("Langue :"+langue);
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
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="probleme" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<form id="widget-issue-form" action="include/issue-form.php" role="form" method="post">
                                
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="subject"  class="fr">Pièce présentant un problème</label>
										<label for="subject"  class="en">Subject</label>
										<label for="subject"  class="nl">Onderwerp</label>
                                        <select name="widget-issue-form-bikePart">
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
                                    <label for="message"  class="fr">Décrivez le problème</label>
									<label for="message"  class="en">Message</label>
									<label for="message"  class="nl">Bericht</label>
                                    <textarea type="text" name="widget-issue-form-message" rows="5" class="form-control required"></textarea>
                                </div>
                                <input type="text" class="hidden" id="widget-issue-form-antispam" name="widget-issue-form-antispam" value="" />
                                <button  class="fr" class="button effect fill" type="submit" ><i class="fa fa-paper-plane"></i>&nbsp;Envoyer</button>
								<button  class="en" class="button effect fill" type="submit" ><i class="fa fa-paper-plane"></i>&nbsp;Send</button>
								<button  class="nl" class="button effect fill" type="submit" ><i class="fa fa-paper-plane"></i>&nbsp;Verzenden</button>
                            </form>
                            <script type="text/javascript">
                                jQuery("#widget-issue-form").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });
													$('#probleme').modal('toggle');

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
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
		


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

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>



</body>

</html>
