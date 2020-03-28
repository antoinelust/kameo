//recuperation des notifications
$('document').ready(function(){
    load_brands();
    
    $('#cash4bike-form select[name=brand]').change(function(){
        $('.bike_picture').addClass("hidden");          
        
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



