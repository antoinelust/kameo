<?php 
session_start();
include 'include/header.php';
// checkAccess();
$user=$_SESSION['userID'];
$langue=$_SESSION['langue'];
include 'include/activitylog.php';

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

<!-- Language management -->
<script type="text/javascript" src="js/language.js"></script>

<script type="text/javascript">
var langueJava = "<?php echo $_SESSION['langue']; ?>";

    
    
function loadClientConditions(){
        var userID= "<?php echo $userID; ?>";
		return $.ajax({
			url: 'include/load_client_conditions.php',
			type: 'post',
			data: { "userID": userID}
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
function constructBuildingForm(daysToDisplay, administrator){
    
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
	month.push(tempDate.getMonth());
	
	
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
    
    var i=0;
    var dest ="<select name=\"search-bikes-form-month\" id=\"search-bikes-form-month\" class=\"form-control\">";
	
    while(month[i]){
		var MonthBase0=month[i];
		var MonthBase1=month[i]+1;
        var bookingMonth="<option value=\""+MonthBase1+"\" class=\"form-control fr\">"+monthFR[MonthBase0]+"</option><option value=\""+MonthBase1+"\" class=\"form-control en\">"+monthEN[MonthBase0]+"</option><option value=\""+MonthBase1+"\" class=\"form-control nl\">"+monthNL[MonthBase0]+"</option>";
        i++;
        dest = dest.concat(bookingMonth);

    }
    var bookingMonth="</select>";
    dest = dest.concat(bookingMonth);
    document.getElementById('booking_month_form').innerHTML=dest;

    
    // 2nd step: intake and deposit buildings
    var userID= "<?php echo $userID; ?>";
    var langue= "<?php echo $_SESSION['langue']; ?>";
    var userFrameNumber = "<?php echo $userFrameNumber; ?>";
    var i=0;
    
    $.ajax({
        url: 'include/booking_building_form.php',
        type: 'post',
        data: { "userFrameNumber": userFrameNumber},
        success: function(response) {
            
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
            }
            var tempBuilding="</select>";
            dest = dest.concat(tempBuilding);
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
    
function getHistoricBookings() {
    var userID= "<?php echo $userID; ?>";
    var langue= "<?php echo $_SESSION['langue']; ?>";
        $.ajax({
        url: 'include/get_historic_bookings.php',
        type: 'post',
        data: { "userID": userID},
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
	console.log(travel_time_bike);
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
    date_end.setMonth(date_end.getMonth()+1);
    
    
    /* Initialisation part. We define the beginning of first line only. All the other lines will be defined in the body section here below */
    
    var date_temp = new Date(date_start);
    start_day=date_temp.getDay();
    var i=1;
    var string_calendar="<div class=\"row seven-cols\">";
    var temp="<div class=\"col-md-1\"></div>";
    
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
            var body_string="<div class=\"col-md-1 button small grey-dark text-white\">"+daysFR[date_temp.getDay()]+" <b>"+date_temp.getDate()+"</b></div>";
        }
        else if (month== new Date().getMonth() && date_temp.getDate() == new Date().getDate()){
            var body_string="<div class=\"col-md-1 button small green\">"+daysFR[date_temp.getDay()]+" <b>"+date_temp.getDate()+"</b><input type=\"checkbox\" value=\"\"></div>";
        }
        else{
            var body_string="<div class=\"col-md-1 button small\">"+daysFR[date_temp.getDay()]+" <b>"+date_temp.getDate()+"</b> <input type=\"checkbox\" value=\"\"></div>";            
        }
        string_calendar=string_calendar.concat(body_string);
        string_calendar=string_calendar.concat(end_string);
        /*<div class="row seven-cols">
            <div class="col-md-1 "></div>
            <div class="col-md-1 "></div>
            <div class="col-md-1 button small">Mer <b>1</b> <input type="checkbox" value=""></div>
            <div class="col-md-1 button small">Jeu <b>2</b> <input type="checkbox" value=""></div>
            <div class="col-md-1 button small">Ven <b>3</b> <input type="checkbox" value=""></div>
            <div class="col-md-1 button small">Sam <b>4</b> <input type="checkbox" value=""></div>
            <div class="col-md-1 button small">Dim <b>5</b> <input type="checkbox" value=""></div>
          </div>*/

        
        
        date_temp.setDate(date_temp.getDate()+1);
    }
    
    if(date_temp.getDay!=0){
        string_calendar=string_calendar.concat("</div>");
    }
    document.getElementById("my_calendar_body").innerHTML=string_calendar;    
    
}
    
function deconnexion(){
	<?php $_SESSION['login']="false"; ?>
	window.location.href = "http://www.kameobikes.com/index.php";
}
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
$sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY from customer_referential aa where aa.EMAIL='$user'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$conn->close();
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
						<br />
						<div class="col-md-12"> 
                        <a class="button small red-dark button-3d rounded icon-right" data-target="#assistance" data-toggle="modal" href="#">
						<span class="fr">Assistance et Entretien</span>
						<span class="en">Assistance and Maintenance</span>
						<span class="nl">Hulp en Onderhoud</span>
						</a>
						
						<a class="button small green button-3d rounded icon-right" data-target="#calendrier" data-toggle="modal" href="#">
						<span class="fr">Mon calendrier</span>
						<span class="en">My calendar</span>
						<span class="nl">Mijn kalender</span>
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
                        <ul id="mainTab" class="tabs-navigation">
                            <li class="reserver active fr"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Réserver un vélo</a> </li>
                            <li class="reserver active en"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Book a bike</a> </li>
                            <li class="reserver active nl"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Boek een fiets</a> </li>
                            <li class="reservations fr"><a href="#reservations" onclick="hideResearch()"><i class="fa fa-check-square-o"></i>Vos réservations</a> </li>
                            <li class="reservations en"><a href="#reservations" onclick="hideResearch()"><i class="fa fa-check-square-o"></i>Your bookings</a> </li>
                            <li class="reservations nl"><a href="#reservations" onclick="hideResearch()"><i class="fa fa-check-square-o"></i>Uw boekingen</a> </li>
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
									   constructBuildingForm(response.clientConditions.bookingDays, response.clientConditions.administrator);
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
																	
																	var hours= date.getHours();
																	var minutes= date.getMinutes();
																	minutes=minutes.toString();
																	
																	if (minutes.length ==1){
																		minutes="0"+minutes;
																	}

																	document.getElementById("meteoHour1").innerHTML=hours+"h"+minutes;
																	document.getElementById("meteoHour2").innerHTML=hours+"h"+minutes;
																	document.getElementById("meteoHour3").innerHTML=hours+"h"+minutes;
																	document.getElementById("meteoHour4").innerHTML=hours+"h"+minutes;

																	

																	get_meteo(text.timestampStartBooking, addressStart)
																	.done(function(response){
																			var find = '-';
																			var re = new RegExp(find, 'g');
																			
																			weather=response.icon.replace(re,"");
																			temperature=response.temperature;
																			precipitation=response.precipProbability;
																			windSpeed=response.windSpeed;
																			
																			document.getElementById("logo_meteo1").src="images/meteo/"+weather+".png";			
																			document.getElementById('temperature_widget1').innerHTML = Math.round(temperature)+" °C";
																			document.getElementById('precipitation_widget1').innerHTML = precipitation+" %";
																			document.getElementById('wind_widget1').innerHTML = windSpeed+" m/s";
																			document.getElementById("logo_meteo2").src="images/meteo/"+weather+".png";			
																			document.getElementById('temperature_widget2').innerHTML = Math.round(temperature)+" °C";
																			document.getElementById('precipitation_widget2').innerHTML = precipitation+" %";
																			document.getElementById('wind_widget2').innerHTML = windSpeed+" m/s";
																			document.getElementById("logo_meteo3").src="images/meteo/"+weather+".png";			
																			document.getElementById('temperature_widget3').innerHTML = Math.round(temperature)+" °C";
																			document.getElementById('precipitation_widget3').innerHTML = precipitation+" %";
																			document.getElementById('wind_widget3').innerHTML = windSpeed+" m/s";
																			document.getElementById("logo_meteo4").src="images/meteo/"+weather+".png";			
																			document.getElementById('temperature_widget4').innerHTML = Math.round(temperature)+" °C";
																			document.getElementById('precipitation_widget4').innerHTML = precipitation+" %";
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
												<span class="fr-inline text-white">à </span><span class="en-inline text-white">at </span><span class="nl-inline text-white">op </span>																				
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
													<img id="score_kameo1" alt="image" class="centerimg" data-toggle="tooltip" data-placement="top" title="Explication de l'indice MyKameo" />
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
												<span class="fr-inline text-white">à </span><span class="en-inline text-white">at </span><span class="nl-inline text-white">op </span>																				
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
													<img id="score_kameo2" alt="image" class="centerimg" data-toggle="tooltip" data-placement="top" title="Explication de l'indice MyKameo"/>
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
												<span class="fr-inline text-white">à </span><span class="en-inline text-white">at </span><span class="nl-inline text-white">op </span>																				
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
												<span class="fr-inline text-white">à </span><span class="en-inline text-white">at </span><span class="nl-inline text-white">op </span>																				
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
							bb.CONTRACT_REFERENCE, bb.CONTRACT_START, bb.CONTRACT_END, cc.MODEL_FR \"bike_Model_FR\", cc.MODEL_EN \"bike_Model_EN\", cc.MODEL_NL \"bike_Model_NL\", 
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
							$contractNumber=$row['CONTRACT_REFERENCE'];
							$contractStart=$row['CONTRACT_START'];
							$contractEnd=$row['CONTRACT_END'];
							
							?>
							
							<div id="travel_information_2" style="display: none;">
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
										<span class="fr-inline">Votre trajet domicile - travail à </span>
										<span class="en-inline">Your trip home - work at </span>
										<span class="nl-inline">Uw reis naar huis - werk bij </span>
										<span id="meteoHour2"></span>
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
													<img id="score_kameo2" alt="image" class="centerimg" data-toggle="tooltip" data-placement="top" title="Explication de l'indice MyKameo<br>coucou<br/>"/>
											</div>
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
										<span class="fr-inline text-white">Votre trajet domicile - travail à </span>
										<span class="en-inline text-white">Your trip home - work at </span>
										<span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
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
								<caption class="nl"> Beschrijving van uw fiets </caption>
						        <tbody>
								  <tr>
									<td class="fr">Modèle</td>
									<td class="en">Bike model</td>
									<td class="nl">Fietsmodel</td>
									<td class="fr-cell"><?php echo $row["bike_Model_FR"] ?></td>
									<td class="en-cell"><?php echo $row["bike_Model_EN"] ?></td>
									<td class="nl-cell"><?php echo $row["bike_Model_NL"] ?></td>
								  </tr>
 								  <!--<tr>
									<td class="fr">Numéro de châssis</td>
									<td class="en">Frame number</td>
									<td class="nl">Fietsnummer</td>
										<td><?php echo $row["FRAME_NUMBER"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Type de selle</td>
									<td class="en">Saddle model</td>
									<td class="nl">Zadel model</td>
									<td class="fr-cell"><?php echo $row["saddle_Model_FR"] ?></td>
									<td class="en-cell"><?php echo $row["saddle_Model_EN"] ?></td>
									<td class="nl-cell"><?php echo $row["saddle_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Type de poignées</td>
									<td class="en">Handle model</td>
									<td class="nl">Handvat model</td>
									<td class="fr-cell"><?php echo $row["handle_Model_FR"] ?></td>
									<td class="en-cell"><?php echo $row["handle_Model_EN"] ?></td>
									<td class="nl-cell"><?php echo $row["handle_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Type de pneu</td>
									<td class="en">Tires model</td>
									<td class="nl">Banden model</td>
									<td class="fr-cell"><?php echo $row["tires_Model_FR"] ?></td>
									<td class="en-cell"><?php echo $row["tires_Model_EN"] ?></td>
									<td class="nl-cell"><?php echo $row["tires_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Type de transmission</td>
									<td class="en">Transmission type</td>
									<td class="nl">Transmissietype</td>
									<td class="fr-cell"><?php echo $row["transmission_type_FR"] ?></td>
									<td class="en-cell"><?php echo $row["transmission_type_EN"] ?></td>
									<td class="nl-cell"><?php echo $row["transmission_type_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Couleur des pédales</td>
									<td class="en">Pedals color</td>
									<td class="nl">Pedalen kleur</td>
									<td class="fr-cell"><?php echo $row["pedal_Color_FR"] ?></td>
									<td class="en-cell"><?php echo $row["pedal_Color_EN"] ?></td>
									<td class="nl-cell"><?php echo $row["pedal_Color_NL"] ?></td>
								  </tr>				
								  <tr>
									<td class="fr">Couleur des cables de frein</td>
									<td class="en">Wires color</td>
									<td class="nl">Remkabels kleur</td>
									<td class="fr-cell"><?php echo $row["wires_Color_FR"] ?></td>
									<td class="en-cell"><?php echo $row["wires_Color_EN"] ?></td>
									<td class="nl-cell"><?php echo $row["wires_Color_NL"] ?></td>
								  </tr>			
								  <tr>
									<td class="fr">Couleur des poignées</td>
									<td class="en">Handle color</td>
									<td class="nl">Handvat kleur</td>
									<td class="fr-cell"><?php echo $row["handle_Color_FR"] ?></td>
									<td class="en-cell"><?php echo $row["handle_Color_EN"] ?></td>
									<td class="nl-cell"><?php echo $row["handle_Color_NL"] ?></td>
								  </tr>										  
								  <tr>
									<td class="fr">Antivol</td>
									<td class="en">Locker</td>
									<td class="nl">Kastje</td>
									<td class="fr-cell"><?php echo $row["antivol_FR"] ?></td>
									<td class="en-cell"><?php echo $row["antivol_EN"] ?></td>
									<td class="nl-cell"><?php echo $row["antivol_NL"] ?></td>
								  </tr>
								  <tr>
									<td class="fr">Phares</td>
									<td class="en">Lights</td>
									<td class="nl">Licht</td>
									<td class="fr-cell">Avant et arrière </td>
									<td class="en-cell">Front and back lights </td>
									<td class="nl-cell">Voor- en achterlicht </td>
								  </tr>
								  <tr>
									<td class="fr">Numéro de contract</td>
									<td class="en">Contract number</td>
									<td class="nl">Contract nummer</td>
									<td><?php echo $row["CONTRACT_REFERENCE"]; ?></td>
								  </tr>	-->								  
								  <tr>
									<td class="fr">Date de début de contract</td>
									<td class="en">Start date of contract</td>
									<td class="nl">Startdatum van het contract</td>
									<td><?php echo $row["CONTRACT_START"]; ?></td>
								  </tr>								  
								  <tr>
									<td class="fr">Date de fin de contract</td>
									<td class="en">End date of contract</td>
									<td class="nl">Einddatum van het contract</td>
									<td><?php echo $row["CONTRACT_END"]; ?></td>
								  </tr>			  
								  

						        </tbody>
						      </table>
							  
						    </div>
							<script type="text/javascript">

 								var hours= new Date().getHours();
								var minutes= new Date().getMinutes();
								minutes=minutes.toString();
								var kameo_score_loaded=false;
								
								if (minutes.length ==1){
									minutes="0"+minutes;
								}

								document.getElementById('meteoHour1').innerHTML = hours+"h"+ minutes;
								document.getElementById('meteoHour2').innerHTML = hours+"h"+ minutes;
								document.getElementById('meteoHour3').innerHTML = hours+"h"+ minutes;
								document.getElementById('meteoHour4').innerHTML = hours+"h"+ minutes;
								
								var addressDomicile=get_address_domicile();
								var addressTravail=get_address_travail();
								
								var timestamp=Date.now().toString();
								get_meteo(timestamp.substring(0,10), addressDomicile)
								.done(function(response){

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
										document.getElementById('walking_duration_widget2').innerHTML = response.duration_walking+" min";
										document.getElementById('bike_duration_widget2').innerHTML = response.duration_bike+" min";
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
                    <a class="button small green button-3d rounded icon-left" data-target="#update" data-toggle="modal" href="#" onclick="initializeUpdate()">
						<span class="fr">ACTUALISER</span>
						<span class="en">UPDATE</span>
						<span class="nl">UPDATE</span>
					</a>
                    <br>
                    <br>
                    <a href="docs/cgven.pdf" target="_blank" title="Pdf" class="fr">Conditions générales</a>
                    <a href="docs/cgven.pdf" target="_blank" title="Pdf" class="en">Terms and Conditions</a>
                    <a href="docs/cgven.pdf" target="_blank" title="Pdf" class="nl">Algemene voorwaarden</a>
                    <br>
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
						<span class="nl">Afmelden</span>
					</a>					
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
									<span class="col-md-3 fr">Informations générales</span>
									<span class="col-md-3 en">General information</span>
									<span class="col-md-3 nl">Algemene informatie</span>
                                    <div class="form-group col-sm-12">
                                        <label for="telephone"  class="fr">Numéro de téléphone</label>
										<label for="telephone"  class="en">Phone number</label>
										<label for="telephone"  class="nl">Telefoonnumber</label>
                                        <input type="text" name="widget-update-form-phone" class="form-control required" value="<?php echo $row["PHONE"] ?>" autocomplete="tel">
                                    </div>
									<span class="col-md-3 fr">Domicile</span>
									<span class="col-md-3 en">Home</span>
									<span class="col-md-3 nl">Thuis</span>
										 <div class="form-group col-sm-12">
											<label for="email"  class="fr">Adresse</label>
											<label for="email"  class="en">Adress</label>
											<label for="email"  class="nl">Adres</label>
											<input type="text" name="widget-update-form-adress" class="form-control required" value="<?php echo $row['ADRESS'] ?>">
										</div>
										<div class="form-group col-sm-12">
											<label for="widget-update-form-post-code"  class="fr">Code Postal</label>
											<label for="widget-update-form-post-code"  class="en">Postal Code</label>
											<label for="widget-update-form-post-code"  class="nl">Postcode</label>
											<input type="text" name="widget-update-form-post-code" class="form-control required" value="<?php echo $row['POSTAL_CODE'] ?>" autocomplete="postal-code">
										</div>
										<div class="form-group col-sm-12">
											<label for="widget-update-form-city"  class="fr">Commune</label>
											<label for="widget-update-form-city"  class="en">City</label>
											<label for="widget-update-form-city"  class="nl">Gemeente</label>
											<input type="text" name="widget-update-form-city" class="form-control required" value="<?php echo $row['CITY'] ?>" autocomplete="address-level2">
										</div>
									<span class="col-md-3 fr">Lieu de travail</span>
									<span class="col-md-3 nl">Werk</span>
									<span class="col-md-3 en">Work place</span>
										<div class="form-group col-sm-12">
											<label for="widget-update-form-work-adress"  class="fr">Adresse</label>
											<label for="widget-update-form-work-adress"  class="en">Adress</label>
											<label for="widget-update-form-work-adress"  class="nl">Adres</label>
											<input type="text" name="widget-update-form-work-adress" class="form-control required" value="<?php echo $row['WORK_ADRESS'] ?>" autocomplete="off">
										</div>
										<div class="form-group col-sm-12">
											<label for="widget-update-form-work-post-code"  class="fr">Code Postal</label>
											<label for="widget-update-form-work-post-code"  class="en">Postal Code</label>
											<label for="widget-update-form-work-post-code"  class="nl">Postcode</label>
											<input type="text" name="widget-update-form-work-post-code" class="form-control required" value="<?php echo $row['WORK_POSTAL_CODE'] ?>" autocomplete="off">
										</div>
										<div class="form-group col-sm-12">
											<label for="widget-update-form-work-city"  class="fr">Commune</label>
											<label for="widget-update-form-work-city"  class="en">City</label>
											<label for="widget-update-form-work-city"  class="nl">Gemeente</label>
											<input type="text" name="widget-update-form-work-city" class="form-control required" value="<?php echo $row['WORK_CITY'] ?>" autocomplete="off">
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
											<input type="password" id="widget-update-form-password" name="widget-update-form-password" class="form-control required" value="********" autocomplete="off" readonly>
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
							     <!--<div class="container">
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
								    <div class="col-md-1 button small">Sam <b>11</b> <input type="checkbox" value=""></div>
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
								    <div class="col-md-1 button small green">Mer <b>22</b> <input type="checkbox" value=""></div>
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
								</div>-->
                    </div>	
				</div>
			</div>
			<div class="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
				<button type="button" class="button green button-3d rounded icon-right">Sauvegarder</button>
			</div>
			<div class="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
				<button type="button" class="button green button-3d rounded icon-right">Save</button>
			</div>
			<div class="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Sluiten</button>
				<button type="button" class="button green button-3d rounded icon-right">Opslaan</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript"> construct_calendar_header(new Date().getMonth()); </script>

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

							<input type="text" class="hidden" name="widget-assistance-form-contract" value="<?php echo $contractNumber; ?>" />
							<input type="text" class="hidden" name="widget-assistance-form-antispam" value="" />
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
