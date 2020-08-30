$( ".fleetmanager" ).click(function() {
    $.ajax({
        url: 'apis/Kameo/initialize_counters.php',
        type: 'post',
        data: { "email": email, "type": "portfolioAccessories"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('counterAccessoriesPortfolio').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.accessoriesNumberPortfolio+"\" data-from=\"0\" data-seperator=\"true\">"+response.accessoriesNumberPortfolio+"</span>";
            }
        }
    })
})

$(".portfolioAccessoriesManagerClick").click(function() {
    listPortfolioAccessories();
    
})


//FleetManager: Gérer le catalogue | Displays the portfolio <table> by calling load_portfolio.php and creating it
function listPortfolioAccessories(){
  $.ajax({
    url: 'apis/Kameo/accessories/accessories.php',
    type: 'get',
    data: {"action": "listCatalog"},
    success: function(response){
      if (response.response == 'error') {
        console.log(response.message);
      } else{
            var dest="<table class=\"table table-condensed\" id=\"porfolioAccessoriesListing\"><h4 class=\"text-green\"><?=L::accessories_title_listing;?></h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#portfolioAccessoryManagement\" data-toggle=\"modal\" onclick=\"initializeCreatePortfolioAccessories()\" href=\"#\"><span><i class=\"fa fa-plus\"></i><?=L::accessories_add_accessory;?></span></a><thead><tr><th>ID</th><th><?=L::accessories_name;?></th><th><?=L::accessories_description;?></th><th><?=L::accessories_buying_price;?></th><th><?=L::accessories_selling_price;?></th><th><?=L::accessories_stock;?></th><th><?=L::accessories_display;?></th><th><?=L::accessories_type;?></th><th></th></tr></thead><tbody>";
          
          
            response.accessories.forEach(accessory => dest=dest.concat("<tr><td><a href=\"#\" class=\"text-green getPortfolioDetails\" data-target=\"#portfolioAccessoryManagement\" name=\""+accessory.ID+"\" data-toggle=\"modal\">"+accessory.ID+" </a></td><td>"+accessory.NAME+"</td><td>"+accessory.DESCRIPTION+"</td><td>"+accessory.BUYING_PRICE+" €</td><td>"+accessory.PRICE_HTVA+" €</td><td>"+accessory.STOCK+"</td><td>"+accessory.SHOW_ACCESSORIES+"</td><td>"+accessory.CATEGORY+"</td><td><a href=\"#\" class=\"text-green updatePortfolioClick\" data-target=\"#portfolioAccessoryManagement\" name=\""+accessory.ID+"\" data-toggle=\"modal\">Mettre à jour </a></td></tr>"));

            document.getElementById('portfolioAccessoriesListing').innerHTML=dest.concat("</tbody></table>");
            displayLanguage();
            $('#porfolioAccessoriesListing').DataTable({
            });      
          
              var d = new Date(); 

            $(".getPortfolioDetails").click(function() {
                $("#widget-addCatalogAccessory-form .ID").removeClass("hidden");
                $("#widget-addCatalogAccessory-form input[name=ID]").val(this.name);
                $("#widget-addCatalogAccessory-form button[type=submit]").addClass("hidden");
                $('#widget-addCatalogAccessory-form .accessoryCatalogImage').attr("src", "images_accessories/"+this.name+".jpg?"+d.getTime());                
                $('#widget-addCatalogAccessory-form input').attr('readonly', true);
                $('#widget-addCatalogAccessory-form select').attr('disabled', true);
                $('#widget-addCatalogAccessory-form input[name=display]').attr('disabled', true);
                getPortfolioDetails(this.name);
            })            
          
            $(".updatePortfolioClick").click(function() {
                $("#widget-addCatalogAccessory-form .ID").removeClass("hidden");
                $("#widget-addCatalogAccessory-form input[name=file]").removeClass("required");
                $("#widget-addCatalogAccessory-form input[name=ID]").val(this.name);
                $("#widget-addCatalogAccessory-form button[type=submit]").removeClass("hidden");
                $("#widget-addCatalogAccessory-form input[name=action]").val("update");
                $('#widget-addCatalogAccessory-form .accessoryCatalogImage').attr("src", "images_accessories/"+this.name+".jpg?"+d.getTime());
                $('#widget-addCatalogAccessory-form input').attr('readonly', false);    
                $('#widget-addCatalogAccessory-form select').attr('disabled', false);  
                $('#widget-addCatalogAccessory-form input[name=display]').attr('disabled', false);
                
                getPortfolioDetails(this.name);
            })
          
          
      }
    }
  });
}

function getPortfolioDetails(ID){
        
    if (!$("#widget-addCatalogAccessory-form [name=category]").find('option').length) {
      $.ajax({
        url: 'apis/Kameo/accessories/accessories.php',
        type: 'get',
        data: {"action": "listCategories"},
        success: function(response){
          if (response.response == 'error') {
            console.log(response.message);
          } else{
                response.categories.forEach(accessory => $("#widget-addCatalogAccessory-form [name=category]").append(new Option(accessory.CATEGORY, accessory.ID)));
                $("#widget-addCatalogAccessory-form [name=category]").val("");
          }
        }
      });   
    }
    $.ajax({
        url: 'apis/Kameo/accessories/accessories.php',
        type: 'get',
        data: {"action": "retrieve", "ID": ID},
        success: function(response){
            console.log(response);
          if (response.response == 'error') {
            console.log(response.message);
          } else{
                $("#widget-addCatalogAccessory-form [name=name]").val(response.accessory.NAME);
                $("#widget-addCatalogAccessory-form [name=description]").val(response.accessory.DESCRIPTION);
                $("#widget-addCatalogAccessory-form [name=category]").val(response.accessory.ACCESSORIES_CATEGORIES);
                $("#widget-addCatalogAccessory-form [name=buyingPrice]").val(response.accessory.BUYING_PRICE);
                $("#widget-addCatalogAccessory-form [name=sellingPrice]").val(response.accessory.PRICE_HTVA);
                $("#widget-addCatalogAccessory-form [name=stock]").val(response.accessory.STOCK);
                $("#widget-addCatalogAccessory-form [name=display]").val(response.accessory.SHOW_ACCESSORIES);
              
              if(response.accessory.SHOW_ACCESSORIES=="Y"){
                  $("#widget-addCatalogAccessory-form [name=display]").prop( "checked", true );
              }else{
                  $("#widget-addCatalogAccessory-form [name=display]").prop( "checked", false );
              }
              
          }
        }
    });   
}

//FleetManager: Gérer le catalogue | Reset the form to add a bike to the catalogue
function initializeCreatePortfolioAccessories(){
    document.getElementById("widget-addCatalogAccessory-form").reset();
    $("#widget-addCatalogAccessory-form .ID").addClass("hidden");
    $("#widget-addCatalogAccessory-form button[type=submit]").removeClass("hidden");
    $("#widget-addCatalogAccessory-form input[name=action]").val("add");
    $("#widget-addCatalogAccessory-form input[name=file]").addClass("required");
    $('#widget-addCatalogAccessory-form .accessoryCatalogImage').addClass("hidden");
    $('#widget-addCatalogAccessory-form input').attr('readonly', false);
    $('#widget-addCatalogAccessory-form select').attr('disabled', false);
    $('#widget-addCatalogAccessory-form input[name=display]').attr('disabled', false);
    
    
    if (!$("#widget-addCatalogAccessory-form [name=category]").find('option').length) {
      $.ajax({
        url: 'apis/Kameo/accessories/accessories.php',
        type: 'get',
        data: {"action": "listCategories"},
        success: function(response){
          if (response.response == 'error') {
            console.log(response.message);
          } else{
                response.categories.forEach(accessory => $("#widget-addCatalogAccessory-form [name=category]").append(new Option(accessory.CATEGORY, accessory.ID)));
                $("#widget-addCatalogAccessory-form [name=category]").val("");
          }
        }
      });   
    }
}