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
        var address=$('#cash4bike-form input[name=domicile]').val();
        $.ajax({
            url: 'include/API_google_maps/validate_address.php',
            method: 'get',
            data: {'address': address},
            success: function(response){
                if (response.response == "success") {
                }
                else{
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
    console.log(id);
    $.ajax({
        url: 'include/get_bikes_catalog.php',
        method: 'get',
        data:{'id': id},

        success: function(response){
            if (response.response == "success") {
                $('#bike_price').html("<span class=\"text-green\">Prix : </span>"+response.bike[0].priceHTVA+" €");
                document.getElementById("bike_picture").src="images_bikes/"+(response.bike[0].brand+"_"+response.bike[0].model+"_"+response.bike[0].frameType).toLowerCase().replace(/ /g,'-')+"_mini.jpg";
            }
            else{
                console.log(response.message);
            }
        }
    });
}



