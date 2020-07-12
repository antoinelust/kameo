
window.addEventListener("DOMContentLoaded", function(event) {
    $( ".orderBike" ).click(function() {  
        get_command_user(email);
    });
    
    $("#orderBike .mesgs .msg_send_btn").click(function() {  
        var message=$("#orderBike .input_msg_write .write_msg").val();
        if(message != ""){
            write_message(message, email, email, "command");
            $("#orderBike .input_msg_write .write_msg").val("");
        }
    });
    
    $("#orderManager .mesgs .msg_send_btn").click(function() {  
        var message=$("#orderManager .input_msg_write .write_msg").val();
        var emailUser=$("#orderManager input[name=emailUser]").val();
        if(message != ""){
            write_message(message, email, emailUser, "command");
            $("#orderManager .input_msg_write .write_msg").val("");
        }
    });
    
    $("#orderBike .input_msg_write .write_msg").keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            var message=$("#orderBike .input_msg_write .write_msg").val();
            if(message != ""){
                write_message(message, email, email, "command");
                $("#orderBike .input_msg_write .write_msg").val("");
            }
        }
    });    
    $("#orderManager .input_msg_write .write_msg").keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            var message=$("#orderManager .input_msg_write .write_msg").val();
            var emailUser=$("#orderManager input[name=emailUser]").val();
            if(message != ""){
                write_message(message, email, emailUser, "command");
                $("#orderManager .input_msg_write .write_msg").val("");
            }
        }
    });    

    
});



function load_cafetaria(){
    $.ajax({
        url: 'include/load_portfolio.php',
        type: 'get',
        data: { "action": "list", "frameType": "*", "utilisation": "*", "price": "*", "brand": "*", "electric": "*"},
        success: function(response){
            if(response.response == 'error'){
              console.log(response.message);
            }
            if(response.response == 'success'){
                        if(response.response == 'success'){
                            var $grid = $('.grid').isotope();
                                                        
                            var i=0;
                            while (i<response.bikeNumber){
                                if(response.bike[i].display=='Y'){
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

                                    var temp="\
                                    <div class=\"grid-item\">\
                                        <div class=\"portfolio-image effect social-links\">\
                                            <img src=\"images_bikes/"+response.bike[i].brand.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].model.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].frameType.toLowerCase()+"_mini.jpg\" alt=\"image_"+response.bike[i].brand.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].model.toLowerCase().replace(/ /g, '-')+"_"+response.bike[i].frameType.toLowerCase()+"\" class=\"portfolio-img\">\
                                            <div class=\"image-box-content\">\
                                                <p>\
                                                    <a data-target=\"#bikePicture\" data-toggle=\"modal\" href=\"#\" onclick=\"updateBikePicture('"+response.bike[i].brand+"', '"+response.bike[i].model+"', '"+response.bike[i].frameType+"')\"><i class=\"fa fa-expand\"></i></a>\
                                                    <a data-target=\"#command\" class=\"orderBikeClick\" data-toggle=\"modal\" href=\"#\" name=\""+response.bike[i].ID+"\"><i class=\"fa fa-link\"></i></a>\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class=\"portfolio-description\">\
                                            <a href=\"offre.php?brand="+response.bike[i].brand.toLowerCase()+"&model="+response.bike[i].model.toLowerCase()+"&frameType="+response.bike[i].frameType.toLowerCase()+"\"><h4 class=\"title\">"+response.bike[i].brand+"</h4></a>\
                                            <p>"+response.bike[i].model+" "+frameType+"\
                                            <br>"+response.bike[i].utilisation+"\
                                            <br><a class=\"button small green button-3d rounded icon-left orderBikeClick\" data-target=\"#command\" data-toggle=\"modal\" href=\"#\" name=\""+response.bike[i].ID+"\">\
                                                <span class=\"fr\">Commander</span>\
                                                <span class=\"en\">Order</span>\
                                                <span class=\"nl\">Order</span>\
                                            </a>\
                                            </p>\
                                        </div>\
                                    </div>";

                                  var $item = $(temp);
                                  // add width and height class
                                  $item.addClass( 'grid-item--width3').addClass('grid-item--height3');
                                  $grid.append( $item )
                                    // add and lay out newly appended elements
                                    .isotope( 'appended', $item );
                                }
                                i++;
                            }

                            displayLanguage();

                            $( ".orderBikeClick" ).click(function() {  
                                fillCommandDetails(this.name);
                            });

                            $( "img.portfolio-img" ).load(function(){
                                $('.grid').isotope();
                            });
                            
                            
                    }                        
            }
        }
    
    })
    
    
}


function fillCommandDetails(ID){
    console.log(ID);
    $.ajax({
    url: 'include/load_portfolio.php',
    type: 'get',
    data: { "action": "retrieve", "ID": ID},
    success: function(response){
      if (response.response == 'error') {
        console.log(response.message);
      } else{
        $('#widget-command-form input[name=ID]').val(response.ID);

        $('#widget-command-form input[name=brand]').val(response.brand);
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
    url: 'include/command.php',
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
        url: 'include/chat_management.php',
        type: 'get',
        data: { "action": "list", "type": "command", "email": email},
        success: function(response){
            var i=0;
            var dest="";
            while(i<response.chatNumber){
                var isKameoBikes = response.chat[i].emailUser.slice(-14);
                if( isKameoBikes == "kameobikes.com"){
                    isKameoBikes = true;
                }else{
                    isKameoBikes = false;
                }
                
                if(response.chat[i].img=="none"){
                    var image = "https://ptetutorials.com/images/user-profile.png";
                }else{
                    var image = "./images/images_users/"+response.chat[i].img;
                }
                                
                if(isKameoBikes){
                    var temp="<div class=\"incoming_msg\">\
                    <div class=\"incoming_msg_img\">\
                    <img src=\""+image+"\" alt=\"sunil\"> </div>\
                      <div class=\"received_msg\">\
                        <div class=\"received_withd_msg\">";
                }else{
                    var temp="<div class=\"outgoing_msg\">\
                                  <div class=\"sent_msg\">";
                }
                dest=dest.concat(temp);
                var temp = "<p>"+response.chat[i].message+"</p>\
                      <span class=\"time_date\"> "+response.chat[i].firstName+" "+response.chat[i].name+" | "+response.chat[i].messageHour+" AM    |    "+response.chat[i].messageDate+"</span></div>\
                  </div>\
                </div>";
                dest=dest.concat(temp);
                i++;
            }
            
            if(response.chatNumber==0){
                var temp="<div class=\"incoming_msg\">\
                <div class=\"incoming_msg_img\">\
                <img src=\"https://ptetutorials.com/images/user-profile.png\" alt=\"sunil\"> </div>\
                  <div class=\"received_msg\">\
                    <div class=\"received_withd_msg\">\
                        <p>Des questions sur un ou plusieurs de nos vélos ? Je suis à votre disposition pour vous aiguiller.</p>\
                        <span class=\"time_date\"> Info Kameo Bikes </span></div>\
                  </div>\
                </div>";
                dest=dest.concat(temp);
            }
            
            if(isKameoBikes == false){
                var temp="<div class=\"incoming_msg\">\
                <div class=\"incoming_msg_img\">\
                <img src=\"https://ptetutorials.com/images/user-profile.png\" alt=\"sunil\"> </div>\
                  <div class=\"received_msg\">\
                    <div class=\"received_withd_msg\">\
                        <p>Nous vous remercions pour votre message. Notre expert vous répondra aussi rapidement que possible.</p>\
                        <span class=\"time_date\"> Info Kameo Bikes </span></div>\
                  </div>\
                </div>";
                dest=dest.concat(temp);
            }
            $('#divChatCommand').html(dest);
            $('.msg_history').scrollTop($('.msg_history').prop("scrollHeight"));
            
            
        }
    })
}

function get_message_history_admin(emailUser){
    $.ajax({
        url: 'include/chat_management.php',
        type: 'get',
        data: { "action": "list", "type": "command", "email": emailUser},
        success: function(response){
            var i=0;
            var dest="";
            while(i<response.chatNumber){
                var isKameoBikes = response.chat[i].emailUser.slice(-14);
                if( isKameoBikes == "kameobikes.com"){
                    isKameoBikes = true;
                }else{
                    isKameoBikes = false;
                }
                
                if(response.chat[i].img=="none"){
                    var image = "https://ptetutorials.com/images/user-profile.png";
                }else{
                    var image = "./images/images_users/"+response.chat[i].img;
                }
                                
                if(!isKameoBikes){
                    var temp="<div class=\"incoming_msg\">\
                    <div class=\"incoming_msg_img\">\
                    <img src=\""+image+"\" alt=\"sunil\"> </div>\
                      <div class=\"received_msg\">\
                        <div class=\"received_withd_msg\">";
                }else{
                    var temp="<div class=\"outgoing_msg\">\
                                  <div class=\"sent_msg\">";
                }
                dest=dest.concat(temp);
                var temp = "<p>"+response.chat[i].message+"</p>\
                      <span class=\"time_date\"> "+response.chat[i].firstName+" "+response.chat[i].name+" | "+response.chat[i].messageHour+" AM    |    "+response.chat[i].messageDate+"</span></div>\
                  </div>\
                </div>";
                dest=dest.concat(temp);
                i++;
            }
            $('#divChatCommandAdmin').html(dest);
            $('#orderManager .msg_history').scrollTop($('#orderManager .msg_history').prop("scrollHeight"));
        }
    })
}

function write_message(message, email, emailBeneficiary, type){
    if(message != ""){
        $.ajax({
        url: 'include/chat_management.php',
        type: 'post',
        data: { "action": "add", "message": message, "email": email, "emailBeneficiary": emailBeneficiary, "type": type},
        success: function(response){
          if (response.response == 'error') {
            console.log(response.message);
          } else{
              if(email==emailBeneficiary){
                  get_message_history();
              }else{
                  get_message_history_admin(emailBeneficiary);
              }
          }

        }
      })
    }
}

