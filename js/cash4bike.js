//recuperation des notifications
$('document').ready(function(){
    load_brands();
    
    $('#cash4bike-form select[name=brand]').change(function(){
        $('.bike_picture').addClass("hidden");          
        $('#cash4bike-form select[name=model]').val("selection");
           
        if($('#cash4bike-form select[name=brand]').val()=='selection'){
            $('.model').addClass("hidden");
        }else{
            load_models($('#cash4bike-form select[name=brand]').val());
            $('.model').removeClass("hidden");
        }
    });
    
    $('#cash4bike-form select[name=model]').change(function(){
      if($('#cash4bike-form select[name=model]').val()=='selection'){
        $('.bike_picture').addClass("hidden");
      }else{
          load_picture($('#cash4bike-form select[name=model]').val());
          $('.bike_picture').removeClass("hidden");
      }
    });
    
    $('#cash4bike-form select[name=transport]').change(function(){
        console.log($('#cash4bike-form select[name=transport]').val());
        if($('#cash4bike-form select[name=transport]').val()!='personnalCar' && $('#cash4bike-form select[name=transport]').val()!='companyCar'){
            $('.essence').addClass("hidden");
        }else{
            $('.essence').removeClass("hidden");
        }
    });
    
    $('#cash4bike-form input[name=domicile]').change(function(){
        $('#inputHomeAddress').removeClass('has-error');
        $('#inputHomeAddress').removeClass('has-success');
        $('#inputHomeAddress').addClass('has-warning');
        $('#inputHomeAddress2').removeClass('fa-check');
        $('#inputHomeAddress2').addClass('fa-info-circle');
        $('#inputHomeAddress2').removeClass('fa-close');
        
        
        var address=$('#cash4bike-form input[name=domicile]').val();
        $.ajax({
            url: 'include/API_google_maps/validate_address.php',
            method: 'get',
            data: {'address': address},
            success: function(response){
                if (response.response == "success") {
                    $('#inputHomeAddress').removeClass('has-error');
                    $('#inputHomeAddress').addClass('has-success');
                    $('#inputHomeAddress').removeClass('has-warning');
                    $('#inputHomeAddress2').addClass('fa-check');
                    $('#inputHomeAddress2').removeClass('fa-info-circle');
                    $('#inputHomeAddress2').removeClass('fa-close');
                                        
                }
                else{
                    $('#inputHomeAddress').addClass('has-error');
                    $('#inputHomeAddress').removeClass('has-success');
                    $('#inputHomeAddress').removeClass('has-warning');
                    $('#inputHomeAddress2').removeClass('fa-check');
                    $('#inputHomeAddress2').removeClass('fa-info-circle');
                    $('#inputHomeAddress2').addClass('fa-close');
                                        
                }
            }
        });   
    });
    $('#cash4bike-form input[name=travail]').change(function(){
        $('#inputWorkAddress').removeClass('has-error');
        $('#inputWorkAddress').removeClass('has-success');
        $('#inputWorkAddress').addClass('has-warning');
        $('#inputWorkAddress2').removeClass('fa-check');
        $('#inputWorkAddress2').addClass('fa-info-circle');
        $('#inputWorkAddress2').removeClass('fa-close');
        
        
        var address=$('#cash4bike-form input[name=travail]').val();
        $.ajax({
            url: 'include/API_google_maps/validate_address.php',
            method: 'get',
            data: {'address': address},
            success: function(response){
                if (response.response == "success") {
                    
                    $('#inputWorkAddress').removeClass('has-error');
                    $('#inputWorkAddress').addClass('has-success');
                    $('#inputWorkAddress').removeClass('has-warning');
                    $('#inputWorkAddress2').addClass('fa-check');
                    $('#inputWorkAddress2').removeClass('fa-info-circle');
                    $('#inputWorkAddress2').removeClass('fa-close');
                    
                }
                else{
                    
                    $('#inputWorkAddress').addClass('has-error');
                    $('#inputWorkAddress').removeClass('has-success');
                    $('#inputWorkAddress').removeClass('has-warning');
                    $('#inputWorkAddress2').removeClass('fa-check');
                    $('#inputWorkAddress2').removeClass('fa-info-circle');
                    $('#inputWorkAddress2').addClass('fa-close');
                    
                    
                }
            }
        });   
    });
    
    $('#cash4bike-form select[name=type]').change(function(){
        $('#cash4bike-form input[name=type]').val($('#cash4bike-form select[name=type]').val());
    });
    $('#cash4bike-form input[name=revenu]').change(function(){
        $('#cash4bike-form input[name=type]').val($('#cash4bike-form input[name=revenu]').val());
    });
    $('#cash4bike-form input[name=domicile]').change(function(){
        $('#cash4bike-form input[name=type]').val($('#cash4bike-form input[name=domicile]').val());
    });
    $('#cash4bike-form input[name=travail]').change(function(){
        $('#cash4bike-form input[name=type]').val($('#cash4bike-form input[name=travail]').val());
    });
    $('#cash4bike-form select[name=transport]').change(function(){
        $('#cash4bike-form input[name=type]').val($('#cash4bike-form select[name=transport]').val());
    });
    $('#cash4bike-form select[name=transportationEssence]').change(function(){
        $('#cash4bike-form input[name=type]').val($('#cash4bike-form select[name=transportationEssence]').val());
    });
    $('#cash4bike-form select[name=model]').change(function(){
        $('#cash4bike-form input[name=type]').val($('#cash4bike-form select[name=model]').val());
    });
    
    
});



Array.prototype.contains = function(v) {
  for (var i = 0; i < this.length; i++) {
    if (this[i] === v) return true;
  }
  return false;
};

Array.prototype.unique = function() {
  var arr = [];
  for (var i = 0; i < this.length; i++) {
    if (!arr.contains(this[i])) {
      arr.push(this[i]);
    }
  }
  return arr;
}



function load_brands(){
  $.ajax({
    url: 'include/get_bikes_catalog.php',
    method: 'get',
    success: function(response){
        if (response.response == "success") {
            var brand=[];
            response.bike.forEach((bike) => {
                brand.push(bike.brand);
            })
            brand=brand.unique().sort();
                        
            
            brand.forEach((brand) => {
                $('#cash4bike-form select[name=brand]').append("<option value=\""+brand+"\">"+brand+"<br>");
            })
        }
        else{
            console.log(response.message);
        }
    }
  });
}


function load_models(brand){
  $.ajax({
      url: 'include/get_bikes_catalog.php',
      data:{'brand': brand},
      method: 'get',
        success: function(response){
            if (response.response == "success") {
                var models=[];
                response.bike.forEach((bike) => {
                    models.push([bike.model, bike.priceHTVA, bike.id]);
                })
                models=models.unique().sort();
                
                $('#cash4bike-form select[name=model]')
                .find('option')
                .remove()
                .end()
                ;
                
                $('#cash4bike-form select[name=model]').append("<option value=\"selection\" selected>Veuillez selectionner<br>");
                models.forEach((model) => {
                    $('#cash4bike-form select[name=model]').append("<option value=\""+model[2]+"\">"+model[0]+ " - "+model[1]+" €<br>");
                })
            }
            else{
                console.log(response.message);
            }
        }
    });
}


function load_picture(id){
    $.ajax({
        url: 'include/get_bikes_catalog.php',
        method: 'get',
        data:{'id': id},

        success: function(response){
            if (response.response == "success") {
                
                var price=response.bike[0].priceHTVA;
                var brand=response.bike[0].brand;
                var model=response.bike[0].model;
                var frameType=response.bike[0].frameType;
                
                $.ajax({
                    url: 'include/get_prices.php',
                    method: 'post',
                    data:{'retailPrice': price},

                    success: function(response){
                        if (response.response == "success") {
                            $('#bike_price').html("<span class=\"text-green\">Prix à l'achat (HTVA): </span>"+price+" €");
                            $('#bike_leasing_price').html("<span class=\"text-green\">Prix en location tout inclus (HTVA): </span>"+response.HTVALeasingPrice+" €/mois");
                            document.getElementById("bike_picture").src="images_bikes/"+(brand+"_"+model+"_"+frameType).toLowerCase().replace(/ /g,'-')+"_mini.jpg";
                        }
                        else{
                            console.log(response);
                        }
                    }
                });                
            }
            else{
                console.log(response.message);
            }
        }
    });
}



