var mapInitialisation=false;


function bikeFilter(e){
    document.getElementsByClassName('bikeSelectionText')[0].innerHTML=e;
    get_reservations_listing(document.getElementsByClassName('bikeSelectionText')[0].innerHTML, new Date($(".form_date_start").data("datetimepicker").getDate()), new Date($(".form_date_end").data("datetimepicker").getDate()));

}


function update_offer_list(company){
        $.ajax({
          url: 'apis/Kameo/offer_management.php',
          method: 'get',
          data: {'company' : company, 'action': 'retrieve'},
          success: function(response){
            if (response.response == "error"){
              console.log(response.message);
            }else{
                $('#widget-bikeManagement-form select[name=offerReference]')
                    .find('option')
                    .remove()
                    .end()
                ;
                var i=0;
                while (i < response.offersNumber){
                    $('#widget-bikeManagement-form select[name=offerReference]').append("<option value="+response.offer[i].id+">"+response.offer[i].title+"<br>");
                    i++;
                }

                if(response.offersNumber == 0){
                    $('.offerReference').fadeOut();
                }else{
                    $('.offerReference').fadeIn();
                }

                $('#widget-bikeManagement-form select[name=offerReference').val("");

            }
          }
        });
}


function update_users_list(company, userEMAIL = null){
    $.ajax({
        url: 'apis/Kameo/get_users_listing.php',
        type: 'post',
        data: { "company": company},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){

                $('#widget-bikeManagement-form select[name=clientReference]')
                    .find('option')
                    .remove()
                    .end()
                ;
                var i=0;
                while (i < response.usersNumber){
                    $('#widget-bikeManagement-form select[name=clientReference]').append("<option value="+response.user[i].email+">"+response.user[i].firstName+" - "+response.user[i].name+"<br>");
                    i++;
                }

                if(response.usersNumber == 0){
                    $('.clientReference').fadeOut();
                }else{
                    $('.clientReference').fadeIn();
                }

                if(userEMAIL != null){
                    $('#widget-bikeManagement-form select[name=clientReference]').val(userEMAIL);
                }else{
                    $('#widget-bikeManagement-form select[name=clientReference]').val("");
                }


            }
        }
    });
}
