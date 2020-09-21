
window.addEventListener("DOMContentLoaded", function(event) {
    $( ".orderBike" ).click(function() {
        get_command_user(email);
    });

    $("#orderBike .mesgs .msg_send_btn").click(function() {
        var message=$("#orderBike .input_msg_write .write_msg").val();
        if(message != ""){
            write_message(message, email, email, "order");
            $("#orderBike .input_msg_write .write_msg").val("");
        }
    });
    $("#orderBike .input_msg_write .write_msg").keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            var message=$("#orderBike .input_msg_write .write_msg").val();
            if(message != ""){
                write_message(message, email, email, "order");
                $("#orderBike .input_msg_write .write_msg").val("");
            }
        }
    });
});

function load_cafetaria(){
    $.ajax({
        url: 'apis/Kameo/orders/orders.php',
        type: 'get',
        data: { "action": "listOrderable"},
        success: function(response){
            if(response.response == 'error'){
              console.log(response.message);
            }
            if(response.response == 'success'){
				var $grid = $('.grid').isotope();
				if (($('.grid').isotope('getItemElements').length == 0))
				{
					for (var i=0; i<response.bikeNumber; i++){

							if(response.bike[i].frameType.toLowerCase()=="h"){
								var frameType = "Homme";
							} else if(response.bike[i].frameType.toLowerCase()=="m"){
								var frameType = "Mixte";
							} else if(response.bike[i].frameType.toLowerCase()=="f"){
								var frameType = "Femme";
							} else{
								var frameType = "undefined";
							}

							if(parseInt(response.bike[i].price)<="2000"){
								var price="2000";
							}else if(parseInt(response.bike[i].price)<="3000"){
								var price="between-2000-3000";
							}else if(parseInt(response.bike[i].price)<="4000"){
								var price="between-3000-4000";
							}else if(parseInt(response.bike[i].price)<="5000"){
								var price="between-4000-5000";
							}else{
								var price="5000";
							}

                            if((response.bike[i].stock)=="0"){
                                var stock="commande";
                            }else{
                                var stock="stock";
                            }

                            var priceByMonth = Math.round(response.bike[i].leasingPrice*(1-response.discount/100)) ;
                            if(response.bike[i].company == "KAMEO"){
                                priceByMonth = Math.round(priceByMonth * 1.21) + " €/mois TVAC";
                            }else{
                                priceByMonth = priceByMonth + " €/mois";
                            }
                            
							var temp="\
							<div class=\"grid-item\">\
								<div class=\"portfolio-image effect social-links\">\
									<img src=\"images_bikes/"+response.bike[i].brand.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].model.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].frameType.toLowerCase()+"_mini.jpg\" alt=\"image_"+response.bike[i].brand.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].model.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].frameType.toLowerCase()+"\" class=\"portfolio-img\">\
									<div class=\"image-box-content\">\
										<p>\
											<a data-target=\"#bikePicture\" data-toggle=\"modal\" href=\"#\" onclick=\"updateBikePicture('"+response.bike[i].brand+"', '"+response.bike[i].model+"', '"+response.bike[i].frameType+"')\"></a>\
											<a data-target=\"#command\" class=\"orderBikeClick\" data-toggle=\"modal\" href=\"#\" name=\""+response.bike[i].ID+"\"><i class=\"fa fa-link\"></i></a>\
										</p>\
									</div>\
								</div>\
								<div class=\"portfolio-description\">\
									<a href=\"offre.php?brand="+response.bike[i].brand.toLowerCase()+"&model="+response.bike[i].model.toLowerCase()+"&frameType="+response.bike[i].frameType.toLowerCase()+"\"><h4 class=\"title\">"+response.bike[i].brand+"</h4></a>\
									<p>"+response.bike[i].model+" "+frameType+"\
									<br>"+response.bike[i].utilisation+"\
									<br>Prix : "+priceByMonth;

                                    if(stock==="stock"){
                                        temp=temp+"<br><strong class=\"background-green text-dark center text-center text-small\">De stock</strong>";
                                    }else{
                                        temp=temp+"<br><strong class=\"text-green center text-center text-small\">Précommander</strong>";
                                    }

                                    temp=temp+"\
                                    <br><a class=\"button small green button-3d rounded icon-left orderBikeClick\" data-target=\"#command\" data-toggle=\"modal\"\
                                    href=\"#\" name=\""+response.bike[i].ID+"\">\
										<span>Commander</span>\
									</a>\
									</p>\
								</div>\
							</div>";

              var $item=$(temp);

						  // add width and height class
						  $item.addClass( 'grid-item--width3').addClass('grid-item--height3');
						  $grid.isotope( 'insert', $item );
					}

					//Fix Isotope not displayed after insert/append bug
					setTimeout(function(){
						$grid.isotope( 'reloadItems' ).isotope();
						$( ".orderBikeClick" ).click(function() {
							fillCommandDetails(this.name);
						});
						$( "img.portfolio-img" ).load(function(){
							$('.grid').isotope();
						});
						displayLanguage();
					}, 300);
				}
            }
        }
    })
}


function fillCommandDetails(ID){
    $.ajax({
    url: 'apis/Kameo/load_portfolio.php',
    type: 'get',
    data: { "action": "retrieve", "ID": ID},
    success: function(response){
      if (response.response == 'error') {
        console.log(response.message);
      } else{
        $('#widget-command-form input[name=ID]').val(response.ID);
        $('#widget-command-form select[name=brand]').val(response.brand);
        $('#widget-command-form input[name=model]').val(response.model);
        $('#widget-command-form select[name=frame]').val(response.frameType);
        $('#widget-command-form select[name=utilisation]').val(response.utilisation);
        $('#widget-command-form select[name=electric]').val(response.electric);
        $('#widget-command-form .link').attr("href", response.url);
        $('#widget-command-form .link').html(response.url);
        document.getElementsByClassName("commandImage")[0].src="images_bikes/"+response.brand.toLowerCase().replace(/ /g, '-')+"_"+response.model.toLowerCase().replace(/ /g, '-')+"_"+response.frameType.toLowerCase()+".jpg";
      }

    }
  })
}

function get_command_user(email){
    $.ajax({
    url: 'apis/Kameo/command.php',
    type: 'get',
    data: { "action": "list", "email": email},
    success: function(response){

      if (response.response == 'error') {
        console.log(response.message);
      } else{
          if(response.commandNumber>0){
              $('.gridForCatalog').addClass("hidden");
              $('.bikeOrdered').removeClass("hidden");

              var i = 0;
              while(i<response.commandNumber){
                  $('#orderBike .brand').html(response[i].brand);
                  $('#orderBike .model').html(response[i].model);
                  $('#orderBike .size').html(response[i].size);
                  $('#orderBike .color').html(response[i].color);
                  $('#orderBike .status').html(response[i].status);
                  if(response[i].remark==""){
                      $('#orderBike .remark').html("<span class=\"fr\">Pas de remarques spécifiques</span><span class=\"en\">No remark</span><span class=\"nl\">No remark</span>");
                  }else{
                      $('#orderBike .remark').html(response[i].remark);
                  }
                  if(response[i].testDate=="" || response[i].testDate == null){
                      $('#orderBike .fr .testDate').html("A confirmer");
                      $('#orderBike .nl .testDate').html("To be confirmed");
                      $('#orderBike .en .testDate').html("To be confirmed");
                  }else{
                      $('#orderBike .testDate').html(response[i].testDate);
                  }
                  if(response[i].testAddress=="" || response[i].testAddress == null){
                      $('#orderBike .fr .testPlace').html("A confirmer");
                      $('#orderBike .nl .testPlace').html("To be confirmed");
                      $('#orderBike .en .testPlace').html("To be confirmed");
                  }else{
                      $('#orderBike .testPlace').html(response[i].testAddress);
                  }
                  if(response[i].deliveryDate=="" || response[i].deliveryDate == null){
                      $('#orderBike .fr .deliveryDate').html("A confirmer");
                      $('#orderBike .nl .deliveryDate').html("To be confirmed");
                      $('#orderBike .en .deliveryDate').html("To be confirmed");
                  }else{
                      $('#orderBike .deliveryDate').html(response[i].deliveryDate);
                  }
                  if(response[i].deliveryAddress=="" || response[i].deliveryAddress == null){
                      $('#orderBike .fr .deliveryPlace').html("A confirmer");
                      $('#orderBike .nl .deliveryPlace').html("To be confirmed");
                      $('#orderBike .en .deliveryPlace').html("To be confirmed");
                  }else{
                      $('#orderBike .deliveryPlace').html(response[i].deliveryAddress);
                  }
                  $('#orderBike .image').attr('src', "images_bikes/"+response[i].brand.toLowerCase().replace(/ /g, '-')+"_"+response[i].model.toLowerCase().replace(/ /g, '-')+"_"+response[i].frameType.toLowerCase()+".jpg");
                  i++;
              }

              displayLanguage();

          }else{
              load_cafetaria();
          }
      }

    }
  });
    get_message_history();
}


function get_message_history(){
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
									$('<span/>').addClass('time_date').html(response.messages[i].firstName+" "+response.messages[i].name+" | "+response.messages[i].messageHour+" AM | "+response.messages[i].messageDate)
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
									$( '<p/>' ).text('Des questions sur un ou plusieurs de nos vélos ? Je suis à votre disposition pour vous aiguiller.'),
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
									$( '<p/>' ).text('Nous vous remercions pour votre message. Notre expert vous répondra aussi rapidement que possible.'),
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
