//toggle affichage notifs
$('body').on('click', '.notificationsClick', function(){
  toggle_notifications();
});

$('body').on('click', '.hideNotifications', function(){
  toggle_notifications();
});

$(document).on('click', function (e) {
    if ($(e.target).closest(".notificationHeading").length === 0) {
      if ($('.notificationsBlock').hasClass('isVisible')) {
        toggle_notifications();
      }
    }
});

//recuperation des notifications
$('document').ready(function(){
  load_notifications();
});

//marquer comme lu
$('body').on('click', '.markAsRead',function(){
  var that = $(this);
  $.ajax({
    url: 'apis/Kameo/set_notification_read.php',
    method: 'post',
    data: {
      'ID' : $(this).parents('.notificationItem').find('.notificationId').val()
    },
    success: function(response){
      if(response.response == 'success'){
        var countNotRead = $('.notificationsClick span').html();
        countNotRead -= 1;
        $(that).parents('.notificationItem').removeClass('notRead');
        $(that).parent().remove();
        $('.notificationsClick span').html(countNotRead);
        if (countNotRead == 0) {
          $('.notificationsClick i').addClass("fa-bell-o").addClass('text-green').removeClass("fa-bell").removeClass('text-red');
        }
      }else{
        console.log(response);
      }
    }

  });

});

function setNotificationAsRead(ID){
  $.ajax({
    url: 'apis/Kameo/set_notification_read.php',
    method: 'post',
    data: {
      'ID' : ID
    },
    success: function(response){
      if(response.response == 'success'){

      }else{
        console.log(response);
      }
    }
  });
}


function toggle_notifications(){
  $('.notificationsBlock').toggle();
  $('.hideNotifications').toggle();
  $('.notificationsBlock').toggleClass('isVisible');

}
function notification_set_as_read(ID){
  $.ajax({
      url: 'apis/Kameo/update_notification.php',
      method: 'post',
      data: {
        'action' : 'setAsRead',
        'ID' : ID
      },
      success: function(response){
        load_notifications();
      }
  });
}

function load_notifications(){
  $.ajax({
    url: 'apis/Kameo/notifications/notifications.php',
    method: 'get',
    data: {'action': "retrieveNotifications"},
    success: function(response){
      if (response.response == "success" & response.notificationsNumber > 0) {
        var content = "";
        var size = response.notifications.length;
        var count = 1;
        var countPagination = 1;
        var page = 1;
        var notificationPerPage = 5;
        var countNew = 0;
        response.notifications.forEach((notification) => {
          if(countPagination == notificationPerPage){
            countPagination = 0;
            page++;
          }
          if(notification.TYPE=="feedback"){
            notification.TEXT=traduction.notifications_feedback_start+notification.TYPE_ITEM+traduction.notifications_feedback_middle+notification.TYPE_ITEM+','+notification.ID+traduction.notifications_feedback_end;
          }else if(notification.TYPE=="lateBooking"){
            if(typeof notification.nextBookingStart === "undefined"){
              notification.nextBookingStart = null;
            }
            notification.TEXT=traduction.notifications_lateBooking_1+notification.TYPE_ITEM+traduction.notifications_lateBooking_2+"<a data-toggle='modal' data-target='#lateBookingNotification' href='#' data-ID='"+notification.TYPE_ITEM+"' data-start='"+notification.start+"' data-end='"+notification.end+"' data-bike='"+notification.model+"' data-next-booking = '"+notification.nextBookingStart+"' data-notificationid = '"+notification.notificationID+"' class='lateBookingNotification text-green'> "+traduction.notifications_lateBooking_3+"</a>.";
          }else if(notification.TYPE=="lateBookingNextUser"){
            notification.TEXT=traduction.notifications_lateBookingNextUser_1+notification.TYPE_ITEM+traduction.notifications_lateBookingNextUser_2+"<a data-toggle='modal' data-target='#newBookingLateBooking' href='#' data-ID='"+notification.TYPE_ITEM+"' data-start='"+notification.start+"' data-end='"+notification.end+"' data-bike='"+notification.model+"' data-bikeID = '"+notification.bikeID+"' data-notificationid = '"+notification.notificationID+"' class='lateBookingNewBooking text-green'> "+traduction.notifications_lateBookingNextUser_3+"</a>"+traduction.notifications_lateBookingNextUser_4;
          }else if(notification.TYPE=="lateBookingNextUserNewHour"){
            notification.TEXT=traduction.notifications_lateBookingNextUser_1+notification.TYPE_ITEM+traduction.notifications_lateBookingNextUser_2+"<a data-toggle='modal' data-target='#newBookingLateBookingNewHour' href='#' data-ID='"+notification.TYPE_ITEM+"' data-start='"+notification.start+"' data-end='"+notification.end+"' data-newEnd='"+notification.endPreviousBooking+"' data-bike='"+notification.model+"' data-bikeID = '"+notification.bikeID+"' data-notificationid = '"+notification.notificationID+"' class='lateBookingNewBookingnewHour text-green'> "+traduction.notifications_lateBookingNextUser_3+"</a>"+traduction.notifications_lateBookingNextUser_4;
          }
          read = "";
          borderBottom = "";
          markAsRead = "";
          if(notification.READ == 'N'){
            read = "notRead";
            markAsRead = '<span class="markAsRead text-green pointerClick" style="text-decoration:underline;">' + traduction.notif_mark_as_read + '</span>';
            countNew++;
          }
          if (count != size) {
            borderBottom = "notificationBorder";
          }
          content += `
          <div class="col-sm-12 page page`+page+` hidden notificationItem  `+borderBottom+` `+read+`">
          <span>`+notification.TEXT+`</span><br/><div style="text-align:right;">`+markAsRead+`</div><input type="hidden" class="notificationId" value="`+notification.ID+`" />
          </div>
          `;
          count++;
          countPagination++;

        });

        if(page>1){
          content += `<ul class="pagination">`;
          content += `<li class="page-item"><a class="page-link-notification active" href="#">1</a></li>`;
          for (let i = 2; i <= page; i++) {
            content += `<li class="page-item"><a class="page-link-notification" href="#">`+i+`</a></li>`;
          }
          content += `</ul>`;
        }



        if (countNew > 0) {
          $('.notificationsBlock').html(content);
          $('.notificationsClick i').removeClass("fa-bell-o").removeClass('text-green').addClass("fa-bell").addClass('text-red');
          $('.notificationsClick span').html(countNew);
        } else if(countNew == 0 && size > 0){
          $('.notificationsBlock').html(content);
          $('.notificationsClick i').addClass("fa-bell-o").addClass('text-green').removeClass("fa-bell").removeClass('text-red');
          $('.notificationsClick span').html(countNew);
        } else if (size > 0){
          $('.notificationsBlock').html(content);
        } else{
          content = '<div class="col-sm-12 notificationItem"><span>' + traduction.notif_no_notif + '</span></div>'
          $('.notificationsBlock').html(content);
        }

        $('.lateBookingNotification').click(function(){
          $('#widget_updateDepositHour_notification input[name=ID]').val($(this).data("id"));
          $('#widget_updateDepositHour_notification input[name=model]').val($(this).data("bike"));
          $('#widget_updateDepositHour_notification input[name=start]').val($(this).data("start").replace(' ', 'T'));
          $('#widget_updateDepositHour_notification input[name=end]').val($(this).data("end").replace(' ', 'T'));
          $('#widget_updateDepositHour_notification input[name=newEndDate]').val($(this).data("end").replace(' ', 'T'));
          if($(this).data("next-booking") == null){
            $('#widget_updateDepositHour_notification .nextBooking').addClass("hidden");
          }else{
            $('#widget_updateDepositHour_notification .nextBooking').removeClass("hidden");
            $('#widget_updateDepositHour_notification strong[name=nextBookingDate]').html($(this).data("next-booking").shortDate()+" à "+$(this).data("next-booking").shortHours());
          }
        })
        $('.lateBookingNewBooking').click(function(){
          notification_set_as_read($(this).data("notificationid"));

          $('#replaceBooking').html("");
          $('#newBookingLateBooking input[name=ID]').val($(this).data("id"));
          $('#newBookingLateBooking input[name=model]').val($(this).data("bike"));
          $('#newBookingLateBooking input[name=start]').val($(this).data("start").replace(' ', 'T'));
          $('#newBookingLateBooking input[name=end]').val($(this).data("end").replace(' ', 'T'));

          let start = new Date($(this).data("start").replace(' ', 'T'));
          let end = new Date($(this).data("end").replace(' ', 'T'));

          var data = new Array();
          data.push({ name: "action", value: "replaceBooking" });
          data.push({
            name: "intakeDay",
            value: start.getDate()+"-"+(start.getMonth() + 1)+"-"+start.getFullYear(),
          });
          data.push({
            name: "intakeHour",
            value: start.getHours()+"h"+start.getMinutes(),
          });
          data.push({
            name: "intakeBuilding",
            value: "ActirisSaintJosse",
          });
          data.push({
            name: "depositDay",
            value: end.getDate()+"-"+(end.getMonth() + 1)+"-"+end.getFullYear(),
          });
          data.push({
            name: "depositHour",
            value: end.getHours()+"h"+end.getMinutes(),
          });
          data.push({
            name: "depositBuilding",
            value: "ActirisSaintJosse",
          });

          $.ajax({
            type: "POST",
            url: "apis/Kameo/search-bikes.php",
            data: data,
            success: function (response){
              response.bike.forEach(function(bikeInformation, index){
                var newDiv = document.createElement("div");
                newDiv.className = "col-sm-4";
                var div_img = document.createElement('div');
                var img = document.createElement('img');
                img.src = "images_bikes/"+bikeInformation.type+"_mini.jpg";
                div_img.appendChild(img);
                newDiv.appendChild(div_img);
                var p=document.createElement("p");
                p.innerHTML = traduction.generic_bike + " : "+bikeInformation.typeDescription+"<br>"+traduction.generic_brand + " : "+bikeInformation.brand+"<br>"+ traduction.generic_model + " : "+bikeInformation.model+"<br> " + traduction.generic_size + " : "+bikeInformation.size;
                newDiv.appendChild(p);
                var button=document.createElement("button");
                button.className = "btn btn-small replaceBookingButton";
                button.innerHTML = traduction.generic_select;
                button.name=bikeInformation.bikeID;
                newDiv.appendChild(button);
                document.getElementById("replaceBooking").appendChild(newDiv);
              })

              $('.replaceBookingButton').click(function(){
                var data = new Array();
                data.push({ name: "action", value: "replaceBooking"});
                data.push({ name: "oldBookingID", value: $('#widget_newBooking_lateBooking input[name=ID]').val()});
                data.push({ name: "bikeID", value: this.name});
                data.push({ name: "widget-new-booking-date-start", value: $('#widget_newBooking_lateBooking input[name=start]').val()});
                data.push({ name: "widget-new-booking-date-end", value: $('#widget_newBooking_lateBooking input[name=end]').val()});
                data.push({ name: "widget-new-booking-building-start", value: "ActirisSaintJosse"});
                data.push({ name: "widget-new-booking-building-end", value: "ActirisSaintJosse"});
                $.ajax({
                  type: "POST",
                  url: "apis/Kameo/new_booking.php",
                  data: data,
                  success: function (response) {
                    if(response.response=="success"){
                      $('#newBookingLateBooking').modal('toggle');
                      $('#replaceBooking').html("");
                      $.notify({
                      message: "Réservation modifiée."
                      }, {
                      type: 'success'
                      });
                    }
                  }
                })
              })
            }
          })
        })

        $('.page-link-notification').click(function(){
          var page = $(this).html();
          $('.page').addClass("hidden");
          $('.page'+page).removeClass("hidden");
        })


        $('.lateBookingNewBookingnewHour').click(function(){
          notification_set_as_read($(this).data("notificationid"));
          $('#replaceBookingNewHour').html("");
          var newStartDate=new Date($(this).data("newend"))
          var currentStartDate=new Date($(this).data("start"));
          $('#newBookingLateBookingNewHour input[name=ID]').val($(this).data("id"));
          $('#newBookingLateBookingNewHour input[name=model]').val($(this).data("bike"));
          $('#newBookingLateBookingNewHour input[name=start]').val($(this).data("start").replace(' ', 'T'));
          $('#newBookingLateBookingNewHour input[name=end]').val($(this).data("end").replace(' ', 'T'));
          $('#newBookingLateBookingNewHour input[name=newStartDate]').val($(this).data("newend").replace(' ', 'T'));
          $('#newBookingLateBookingNewHour span[name=newStartDate]').html(get_date_string_european_with_hours(newStartDate));

          let start = new Date($(this).data("start").replace(' ', 'T'));
          let end = new Date($(this).data("end").replace(' ', 'T'));

          var data = new Array();
          data.push({ name: "action", value: "replaceBooking" });
          data.push({
            name: "intakeDay",
            value: start.getDate()+"-"+(start.getMonth() + 1)+"-"+start.getFullYear(),
          });
          data.push({
            name: "intakeHour",
            value: start.getHours()+"h"+start.getMinutes(),
          });
          data.push({
            name: "intakeBuilding",
            value: "ActirisSaintJosse",
          });
          data.push({
            name: "depositDay",
            value: end.getDate()+"-"+(end.getMonth() + 1)+"-"+end.getFullYear(),
          });
          data.push({
            name: "depositHour",
            value: end.getHours()+"h"+end.getMinutes(),
          });
          data.push({
            name: "depositBuilding",
            value: "ActirisSaintJosse",
          });

          $.ajax({
            type: "POST",
            url: "apis/Kameo/search-bikes.php",
            data: data,
            success: function (response){
              response.bike.forEach(function(bikeInformation, index){
                var newDiv = document.createElement("div");
                newDiv.className = "col-sm-4";
                var div_img = document.createElement('div');
                var img = document.createElement('img');
                img.src = "images_bikes/"+bikeInformation.type+"_mini.jpg";
                div_img.appendChild(img);
                newDiv.appendChild(div_img);
                var p=document.createElement("p");
                p.innerHTML = traduction.generic_brand + " : "+bikeInformation.brand+"<br>"+ traduction.generic_model + " : "+bikeInformation.model+"<br> " + traduction.generic_size + " : "+bikeInformation.size;
                newDiv.appendChild(p);
                var button=document.createElement("button");
                button.className = "btn btn-small replaceBookingNewHourButton";
                button.innerHTML = traduction.generic_select;
                button.name=bikeInformation.bikeID;
                newDiv.appendChild(button);
                document.getElementById("replaceBookingNewHour").appendChild(newDiv);
              })

              $('.replaceBookingNewHourButton').click(function(){
                var data = new Array();
                data.push({ name: "action", value: "replaceBookingNewHour"});
                data.push({ name: "oldBookingID", value: $('#widget_newBooking_lateBooking_new_hour input[name=ID]').val()});
                data.push({ name: "bikeID", value: this.name});
                data.push({ name: "widget-new-booking-date-start", value: $('#widget_newBooking_lateBooking_new_hour input[name=start]').val()});
                data.push({ name: "widget-new-booking-date-end", value: $('#widget_newBooking_lateBooking_new_hour input[name=end]').val()});
                data.push({ name: "widget-new-booking-building-start", value: "ActirisSaintJosse"});
                data.push({ name: "widget-new-booking-building-end", value: "ActirisSaintJosse"});
                $.ajax({
                  type: "POST",
                  url: "apis/Kameo/new_booking.php",
                  data: data,
                  success: function (response) {
                    if(response.response=="success"){
                      $('#newBookingLateBookingNewHour').modal('toggle');
                      $('#replaceBookingNewHour').html("");
                      $.notify({
                      message: "Réservation modifiée."
                      }, {
                      type: 'success'
                      });
                    }
                  }
                })
              })

              $( ".keepBookingNewHourButton" ).off();
              $('.keepBookingNewHourButton').click(function(){
                var data = new Array();
                data.push({ name: "action", value: "keepBookingNewHour"});
                data.push({ name: "bookingID", value: $('#widget_newBooking_lateBooking_new_hour input[name=ID]').val()});
                data.push({ name: "newDateStart", value: $('#widget_newBooking_lateBooking_new_hour input[name=newStartDate]').val()});
                $.ajax({
                  type: "POST",
                  url: "apis/Kameo/bookings/bookings.php",
                  data: data,
                  success: function (response) {
                    if(response.response=="success"){
                      load_notifications();
                      $('#newBookingLateBookingNewHour').modal('toggle');
                      $('#replaceBookingNewHour').html("");
                      $.notify({
                        message: response.response
                        }, {
                        type: 'success'
                        });
                    }
                  }
                })
              })
            }
          })
        })

        $('.page1').removeClass("hidden");



      }
    }
  });
}
