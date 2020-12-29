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
      console.log(response);
      if (response.response == "success" & response.notificationsNumber > 0) {
        var content = "";
        var size = response.notification.length;
        var count = 1;
        var countNew = 0;
        response.notification.forEach((notification) => {
          if(notification.TYPE=="feedback"){
            notification.TEXT=traduction.notifications_feedback_start+notification.TYPE_ITEM+traduction.notifications_feedback_middle+notification.TYPE_ITEM+','+notification.ID+traduction.notifications_feedback_end;
          }else if(notification.TYPE=="lateBooking"){
            if(typeof notification.nextBookingStart === "undefined"){
              notification.nextBookingStart = null;
            }
            notification.TEXT=traduction.notifications_lateBooking_1+notification.TYPE_ITEM+traduction.notifications_lateBooking_2+"<a data-toggle='modal' data-target='#lateBookingNotification' href='#' data-ID='"+notification.TYPE_ITEM+"' data-start='"+notification.start+"' data-end='"+notification.end+"' data-bike='"+notification.model+"' data-next-booking = '"+notification.nextBookingStart+"' class='lateBookingNotification text-green'> "+traduction.notifications_lateBooking_3+"</a>.";
          }
          read = "";
          borderBottom = "";
          markAsRead = "";
          if (notification.READ == 'N') {
            read = "notRead";
            markAsRead = '<span class="markAsRead text-green pointerClick" style="text-decoration:underline;">' + traduction.notif_mark_as_read + '</span>';
            countNew++;
          }
          if (count != size) {
            borderBottom = "notificationBorder";
          }
          content += `
          <div class="col-sm-12 notificationItem  `+borderBottom+` `+read+`">
          <span>`+notification.TEXT+`</span><br/><div style="text-align:right;">`+markAsRead+`</div><input type="hidden" class="notificationId" value="`+notification.ID+`" />
          </div>
          `;
          count++;

        });
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
          console.log($(this).data("next-booking"));
          if($(this).data("next-booking") == null){
            $('#widget_updateDepositHour_notification .nextBooking').addClass("hidden");
          }else{
            $('#widget_updateDepositHour_notification .nextBooking').removeClass("hidden");
            $('#widget_updateDepositHour_notification strong[name=nextBookingDate]').html($(this).data("next-booking").shortDate()+" à "+$(this).data("next-booking").shortHours());
          }
        })

      }
    }
  });
}
