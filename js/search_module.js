
function update_deposit_form(){
    var hour=document.getElementById('search-bikes-form-intake-hour').value;
    var date=document.getElementById('search-bikes-form-day').value;
    var Date1=date.split('-');
    var day=Date1[0];
    var month=(Date1[1]-1);
    var Hours=hour.split('h');

    var hour=Hours[0];
    var minute=Hours[1];

    var dateStart = new Date(new Date().getFullYear(), month, day, hour, minute);

    var dateTemp = new Date(new Date().getFullYear(), month, day, hour, minute);

    loadClientConditions()
    .done(function(response){
        bookingLength=parseInt(response.clientConditions.bookingLength);

        var hour=document.getElementById('search-bikes-form-intake-hour').value;
        var Hours1=hour.split('h');
        var hour=Hours1[0];
        var minute=Hours1[1];
        var date=document.getElementById('search-bikes-form-day').value;
        var Date1=date.split('-');
        var day=Date1[0];
        var month=(Date1[1]-1);


        var dateEnd = new Date(new Date().getFullYear(), month, day, hour, minute);
        dateEnd.setHours(dateEnd.getHours()+bookingLength);

        var currentDate = new Date(new Date().getFullYear(), month, day, hour, minute);



        var numberOfDays=Math.round((dateEnd-currentDate)/(1000*60*60*24));


        // 1st step: days and month fileds

        var hour=document.getElementById('search-bikes-form-intake-hour').value;
        var date1=document.getElementById('search-bikes-form-day').value;
        var Date1=date.split('-');
        var day=Date1[0];
        var month=(Date1[1]-1);
        var Hours1=hour.split('h');
        var hour=Hours1[0];
        var minute=Hours1[1];

        var tempDate = new Date(new Date().getFullYear(), month, day, hour, minute);
        var i=0;
        var j=0;
        var dest ="<select id=\"search-bikes-form-day-deposit\" name=\"search-bikes-form-day-deposit\"  class=\"form-control\">";


        while(i<=numberOfDays){
            if((tempDate.getDay()=="1" && parseInt(response.clientConditions.mondayDeposit)) || (tempDate.getDay()=="2" && parseInt(response.clientConditions.tuesdayDeposit)) || (tempDate.getDay()=="3" && parseInt(response.clientConditions.wednesdayDeposit)) || (tempDate.getDay()=="4" && parseInt(response.clientConditions.thursdayDeposit)) || (tempDate.getDay()=="5" && parseInt(response.clientConditions.fridayDeposit)) || (tempDate.getDay()=="6" && parseInt(response.clientConditions.saturdayDeposit)) || (tempDate.getDay()=="0" && parseInt(response.clientConditions.sundayDeposit))){
                var dayTrad = daysTrad[tempDate.getDay()];
                var bookingDay="<option value=\""+tempDate.getDate()+"-"+(tempDate.getMonth()+1)+"-"+tempDate.getFullYear()+"\" class=\"form-control\">"+dayTrad+" "+tempDate.getDate()+" "+monthTrad[tempDate.getMonth()]+"</option>";
                dest = dest.concat(bookingDay);
            }
            tempDate.setDate(tempDate.getDate()+1);
            i++;

        }
        var bookingDay="</select>";
        dest = dest.concat(bookingDay);
        document.getElementById('booking_day_form_deposit').innerHTML=dest;

        document.getElementById('search-bikes-form-day-deposit').addEventListener('change', function () { update_deposit_hour_form()}, false);

        update_deposit_hour_form();

    });



}

function update_intake_hour_form(){
    loadClientConditions()
    .done(function(response){
        var date1=document.getElementById('search-bikes-form-day').value;
        var Date1=date1.split('-');
        var day=Date1[0];
        var currentDate=new Date();

        if(currentDate.getDate()==day){
            var hours=currentDate.getHours();
            var minutes=currentDate.getMinutes();

            var m = (((minutes + 7.5)/15 | 0) * 15) % 60;
            var h = ((((minutes/105) + .5) | 0) + hours) % 24;

            var dateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), h, m);

        }
        else{
            var m = 0;
            var h = response.clientConditions.hourStartIntakeBooking;
            var dateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), h, m);
        }

        var dest="";

        while(dateTemp.getHours()<response.clientConditions.hourEndIntakeBooking){
            if(dateTemp.getMinutes()=="0"){
                var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
            }else{
                var hourString=dateTemp.getHours()+"h"+dateTemp.getMinutes();
            }

            dateTemp.setMinutes(dateTemp.getMinutes()+ parseInt(15));

            var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
            dest=dest.concat(tempString);
        }

        var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
        var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
        dest=dest.concat(tempString);


        document.getElementById('search-bikes-form-intake-hour').innerHTML=dest;

        update_deposit_form();
    });
}


function update_deposit_hour_form(){
    var hour=document.getElementById('search-bikes-form-intake-hour').value;
    var date1=document.getElementById('search-bikes-form-day').value;
    var Date1=date1.split('-');
    var day=Date1[0];
    var month=Date1[1];


    var Hours1=hour.split('h');

    var hour=Hours1[0];
    var minute=Hours1[1];
    var dateStart = new Date(new Date().getFullYear(), month-1, day, hour, minute);

    loadClientConditions()
    .done(function(response){
        bookingLength=parseInt(response.clientConditions.bookingLength);
        var hour=document.getElementById('search-bikes-form-intake-hour').value;
        var date1=document.getElementById('search-bikes-form-day').value;
        var Date1=date1.split('-');
        var day=Date1[0];
        var month=Date1[1];
        var Hours1=hour.split('h');
        var hour=Hours1[0];
        var minute=Hours1[1];

        var dateEnd = new Date(new Date().getFullYear(), month-1, day, hour, minute);
        dateEnd.setHours(dateEnd.getHours()+bookingLength);

        var date1=document.getElementById('search-bikes-form-day-deposit').value;
        var Date1=date1.split('-');
        var day=Date1[0];
        var month=Date1[1];

        if( dateStart.getDate()!=day || (parseInt(hour) < parseInt(response.clientConditions.hourStartDepositBooking))){
            var currentDepositDate = new Date(new Date().getFullYear(), month-1, day, response.clientConditions.hourStartDepositBooking, '00');
            var dateTemp2 = new Date(new Date().getFullYear(), month-1, day, response.clientConditions.hourStartDepositBooking, '00');
        }else{
            var currentDepositDate = new Date(new Date().getFullYear(), month-1, day, hour, minute);
            var dateTemp2 = new Date(new Date().getFullYear(), month-1, day, hour, minute);
        }

        var Hours=[];
        while(dateTemp2<=dateEnd){
            hours=dateTemp2.getHours();
            if(dateTemp2.getMinutes()=="0"){
                minutes="00";
            }else{
                minutes=dateTemp2.getMinutes();
            }
            if(hours>=response.clientConditions.hourStartDepositBooking && hours<response.clientConditions.hourEndDepositBooking && dateTemp2.getDate()==currentDepositDate.getDate() && dateTemp2.getMonth()==currentDepositDate.getMonth()){
                var hourString=hours+'h'+minutes;
                Hours.push(hourString);
            }
            dateTemp2.setMinutes(dateTemp2.getMinutes()+15);
        }

        var hourBeforeLast=(response.clientConditions.hourEndDepositBooking-1)+'h45';
        var hourInArray=Hours[Hours.length-1];

        if(hourBeforeLast==hourInArray){
            Hours.push(response.clientConditions.hourEndDepositBooking+'h00');
        }


        var dest ="<select name=\"search-bikes-form-deposit-hour\" id=\"search-bikes-form-deposit-hour\" class=\"form-control\">";

        var i=0;
        while(i<Hours.length){
            var bookingHour="<option value=\""+Hours[i]+"\" class=\"form-control\">"+Hours[i]+"</option>";
            dest = dest.concat(bookingHour);
            i++;
        }
        var bookingHour="</select>";
        document.getElementById('booking_hour_form_deposit').innerHTML=dest;
    });
}

    // Goal of this function is to construct the reasearch fields
    function constructSearchForm(daysToDisplay, bookingLength, administrator, assistance, hourStartIntakeBooking, hourEndIntakeBooking, hourStartDepositBooking, hourEndDepositBooking, mondayIntake, tuesdayIntake, wednesdayIntake, thursdayIntake, fridayIntake, saturdayIntake, sundayIntake, mondayDeposit, tuesdayDeposit, wednesdayDeposit, thursdayDeposit, fridayDeposit, saturdayDeposit, sundayDeposit, maxBookingsPerYear, maxBookingsPerMonth, email){
        if(assistance=="Y"){
          if(user_data['COMPANY']=="Actiris" || user_data['COMPANY']==""){
            $('#entretienPopUP').addClass('hidden');
            document.getElementById('assistanceSpan').innerHTML="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#assistance\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Assistance</span><span class=\"en-inline\">Assistance</span><span class=\"nl-inline\">Hulp</span></a>"
          }else{
            $('#entretienPopUP').removeClass('hidden');
            document.getElementById('assistanceSpan').innerHTML="<a class=\"button small red-dark button-3d rounded icon-right\" data-target=\"#assistance\" data-toggle=\"modal\" href=\"#\"><span class=\"fr-inline\">Assistance et Entretien</span><span class=\"en-inline\">Assistance and Maintenance</span><span class=\"nl-inline\">Hulp en Onderhoud</span></a>"
          }
        }
        // 1st step: days and month fields
        if(daysToDisplay>0){

            var i=0;
            var j=0;
            var dest ="<select id=\"search-bikes-form-day\" name=\"search-bikes-form-day\"  class=\"form-control\">";
            var dest2 ="<select id=\"search-bikes-form-day-deposit\" name=\"search-bikes-form-day-deposit\"  class=\"form-control\">";

            var tempDate = new Date();

            var tempDate2=tempDate;
            bookingLength=parseInt(bookingLength);
            while(i<=daysToDisplay){
                var afterHour=false;
                if(tempDate.getHours()>=hourEndDepositBooking){
                    tempDate.setHours(hourStartIntakeBooking);
                    tempDate.setMinutes(0);
                    tempDate.setDate(tempDate.getDate()+1);
                    afterHour=true;
                }
                var dayTrad = daysTrad[tempDate.getDay()];
                if((tempDate.getDay()=="1" && parseInt(mondayIntake)) || (tempDate.getDay()=="2" && parseInt(tuesdayIntake)) || (tempDate.getDay()=="3" && parseInt(wednesdayIntake)) || (tempDate.getDay()=="4" && parseInt(thursdayIntake)) || (tempDate.getDay()=="5" && parseInt(fridayIntake)) || (tempDate.getDay()=="6" && parseInt(saturdayIntake)) || (tempDate.getDay()=="0" && parseInt(sundayIntake))){
                    var bookingDay="<option value=\""+tempDate.getDate()+"-"+(tempDate.getMonth()+1)+"-"+tempDate.getFullYear()+"\" class=\"form-control\">"+dayTrad+" "+tempDate.getDate()+" "+monthTrad[tempDate.getMonth()]+"</option>";

                    dest = dest.concat(bookingDay);
                }

                i++;
                if(afterHour==false){
                    tempDate.setDate(tempDate.getDate()+1);
                }
            }
            var bookingDay="</select>";
            dest = dest.concat(bookingDay);
            document.getElementById('booking_day_form').innerHTML=dest;

            document.getElementById('search-bikes-form-day').addEventListener('change', function () { update_intake_hour_form()}, false);


            var currentDate=new Date();

            var hours=currentDate.getHours();
            var minutes=currentDate.getMinutes();

            var m = (((minutes + 15)/15 | 0) * 15) % 60;
            var h = ((((minutes/105) + .5) | 0) + hours) % 24;
            var dateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), h, m);



            var dest="";

            var dateActuelle=dateTemp.getDate()+'-'+(dateTemp.getMonth()+1)+'-'+dateTemp.getFullYear();

            var dateSearch=$('#search-bikes-form-day').val().split('-');


            if(dateActuelle!=$('#search-bikes-form-day').val()){
                dateTemp.setDate(dateSearch[0]);
                dateTemp.setMonth(dateSearch[1] - 1);
                dateTemp.setFullYear(dateSearch[2]);
                dateTemp.setHours(hourStartIntakeBooking);
                dateTemp.setMinutes(0);
            }
            else if(dateTemp.getHours()>=hourEndDepositBooking){
                dateTemp.setHours(hourStartIntakeBooking);
                dateTemp.setMinutes(0);
                dateTemp.setDate(dateTemp.getDate()+1);
            }

            while(dateTemp.getHours()<hourEndIntakeBooking){
                if(dateTemp.getMinutes()=="0"){
                    var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
                }else{
                    var hourString=dateTemp.getHours()+"h"+dateTemp.getMinutes();
                }

                dateTemp.setMinutes(dateTemp.getMinutes()+ parseInt(15));


                var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
                dest=dest.concat(tempString);
            }

            var hourString=dateTemp.getHours()+"h0"+dateTemp.getMinutes();
            var tempString="<option value=\""+hourString+"\">"+hourString+"</option>";
            dest=dest.concat(tempString);


            document.getElementById('search-bikes-form-intake-hour').innerHTML=dest;



            // 2nd step: intake and deposit buildings
            var i=0;
            $.ajax({
                url: 'apis/Kameo/booking_building_form.php',
                type: 'post',
                data: { "email": email},
                success: function(response) {
                    if(response.response=="success"){
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
                        document.getElementById('search-bikes-form-maxBookingPerYear').value=maxBookingsPerYear;
                        document.getElementById('search-bikes-form-maxBookingPerMonth').value=maxBookingsPerMonth;
                        $('#search-bikes-form-email').val(email);
                        displayLanguage();

                    }else{
                        console.log(response.message);
                    }
                }
            });


            update_deposit_form();
        }
    }
