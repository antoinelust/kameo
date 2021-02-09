$( ".chat" ).click(function() {
  get_message_history();
});

$("#chat .mesgs .msg_send_btn").click(function() {
    var message=$("#chat .input_msg_write .write_msg").val();
    if(message != ""){
        write_message(message, email, email, "order");
        $("#chat .input_msg_write .write_msg").val("");
    }
});
$("#chat .input_msg_write .write_msg").keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        var message=$("#chat .input_msg_write .write_msg").val();
        if(message != ""){
            write_message(message, email, email, "order");
            $("#chat .input_msg_write .write_msg").val("");
        }
    }
});

function get_message_history(){
  console.log("test");
  $.ajax({
      url: 'api/chats',
      type: 'get',
      data: { "action": "retrieveMessages", "type": "order"},
      success: function(response){
          var dest="";
		$('#divChatCommand').empty();
		for (var i = 0; i<response.messagesNumber; i++){
		var kameoBikesRegex = new RegExp(/^(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")(@kameobikes.com){1}$/i);
              var isKameoBikes = kameoBikesRegex.test(response.messages[i].emailUser);

			if(isKameoBikes){
        var name_worker= response.messages[i].emailUser.split("@");
        name_worker = name_worker[0].toLocaleLowerCase();
        if(name_worker.includes(".")){
            name_worker = name_worker.replace(".", "_");
        }
        response.messages[i].img = "/images/" + name_worker +".jpg";

				$('#divChatCommand').append([
					$('<div/>',{ "class": "chat_message_container" }).append([
						$('<div/>',{ "class": "incoming_msg_img" }).prepend($('<img>',{src:response.messages[i].img, style:"border-radius: 50%"})),
						$('<div/>',{ "class": "received_msg" }).append([
							$('<div/>',{ "class": "received_withd_msg" }).append([
								$( '<p/>' ).text(response.messages[i].message),
								$('<span/>').addClass('time_date').html(response.messages[i].firstName+" "+response.messages[i].name+" | "+response.messages[i].messageHour+" | "+response.messages[i].messageDate)
							])
						])
					])
				]);
			}else{
				$('#divChatCommand').append([
					$('<div/>',{ "class": "chat_message_container" }).append([
						$('<div/>',{ "class": "outgoing_msg" }).append([
							$('<div/>',{ "class": "sent_msg" }).append([
								$( '<p/>' ).text(response.messages[i].message),
								$('<span/>').addClass('time_date').html(response.messages[i].firstName+" "+response.messages[i].name+" | "+response.messages[i].messageHour+" AM | "+response.messages[i].messageDate)
							])
						]),
						$('<div/>',{ "class": "sent_msg_img" }).prepend($('<img>',{src:'https://ptetutorials.com/images/user-profile.png'}))
					])
				]);
			}
		}
		if(response.messagesNumber==0){
			$('#divChatCommand').append([
					$('<div/>',{ "class": "chat_message_container" }).append([
						$('<div/>',{ "class": "incoming_msg_img" }).prepend($('<img>',{src:'https://ptetutorials.com/images/user-profile.png'})),
						$('<div/>',{ "class": "received_msg" }).append([
							$('<div/>',{ "class": "received_withd_msg" }).append([
								$( '<p/>' ).text(traduction.mk_ordertab_question),
								$('<span/>').addClass('time_date').html('Info Kameo Bikes')
							])
						])
					])
				]);
		}
		if(isKameoBikes == false){
			$('#divChatCommand').append([
					$('<div/>',{ "class": "chat_message_container" }).append([
						$('<div/>',{ "class": "incoming_msg_img" }).prepend($('<img>',{src:'https://ptetutorials.com/images/user-profile.png'})),
						$('<div/>',{ "class": "received_msg" }).append([
							$('<div/>',{ "class": "received_withd_msg" }).append([
								$( '<p/>' ).text(traduction.mk_ordertab_answer),
								$('<span/>').addClass('time_date').html('Info Kameo Bikes')
							])
						])
					])
				]);
		}
		$('.msg_history').scrollTop($('.msg_history').prop("scrollHeight"));
		$('#writeAdminMsg').val('');
      }
  })
}

function write_message(message, email, emailBeneficiary, type){
    if(message != ""){
        $.ajax({
        url: 'api/chats',
        type: 'post',
        data: { "action": "sendMessage", "message": message, "type": type},
        success: function(response){
          if (response.response == 'error') {
            console.log(response.message);
          } else{
              if(email==emailBeneficiary){
                  get_message_history();
              }
          }

        }
      })
    }
}
