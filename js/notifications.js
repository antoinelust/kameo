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
        console.log(response);
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
        var size = response.notification.length;
        var count = 1;
        var countNew = 0;
        response.notification.forEach((notification) => {
          console.log(notification);
          read = "";
          borderBottom = "";
          markAsRead = "";
          if (notification.READ == 'N') {
            read = "notRead";
            markAsRead = '<span class="markAsRead text-green pointerClick" style="text-decoration:underline;">' + notifTrads[0] + '</span>';
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
          content = '<div class="col-sm-12 notificationItem"><span>' + notifTrads[1] + '</span></div>'
          $('.notificationsBlock').html(content);
        }
      }
    }
  });
}
