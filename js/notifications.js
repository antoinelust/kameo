//toggle affichage notifs
$('body').on('click', '.notificationsClick', function(){
  toggle_notifications();
});

$('body').on('click', '.hideNotifications', function(){
  toggle_notifications();
});

//recuperation des notifications
$('document').ready(function(){
  $.ajax({
    url: 'include/get_notifications.php',
    method: 'post',
    data: {'ID': user_ID},
    success: function(response){
      if (response.response == "success") {
        var content = "";
        var size = response.notification.length;
        var count = 1;
        var countNew = 0;
        response.notification.forEach((notification) => {
          read = "";
          borderBottom = "";
          markAsRead = "";
          if (notification.READ == 'N') {
            read = "notRead";
            markAsRead = '<span class="markAsRead text-green pointerClick" style="text-decoration:undrerline;">Marquer comme lu</span>';
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
        } else if (size > 0){
          $('.notificationsBlock').html(content);
        } else{
          content = '<div class="col-sm-12 notificationItem"><span>Pas de notifications</span></div>'
          $('.notificationsBlock').html(content);
        }


      }else{
        console.log(response.message);
      }
    }
  });
});

//marquer comme lu
$('body').on('click', '.markAsRead',function(){
  var that = $(this);
  $.ajax({
    url: 'include/set_notification_read.php',
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
}