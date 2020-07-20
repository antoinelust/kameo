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
  var email = user_data['EMAIL'];
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
	url: 'apis/Kameo/calendar.php',
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
function clickBikeDay(e){

    var email=user_data['EMAIL'];
    var timestampDay=e.id;

    if (e.classList.contains("green")){
      e.classList.remove("green");
      var lien = e.getElementsByTagName("I")[0];
      lien.parentNode.removeChild(lien);
      $.ajax({
        url: 'apis/Kameo/calendar.php',
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
        url: 'apis/Kameo/calendar.php',
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
construct_calendar_header(new Date().getMonth());