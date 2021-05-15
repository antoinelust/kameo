String.prototype.shortDate=function(){
    return this.substr(8,2)+"/"+this.substr(5,2)+"/"+this.substr(2,2);
};

String.prototype.shortDateHours=function(){
    return this.substr(8,2)+"/"+this.substr(5,2)+"/"+this.substr(2,2)+" "+this.substr(11,2)+":"+this.substr(14,2);
};
String.prototype.shortHours=function(){
    return this.substr(11,2)+":"+this.substr(14,2);
};


function get_date_string(date = Date.now()){
  var dateNow=new Date(date);
  var year=dateNow.getFullYear();
  var month=("0" + (dateNow.getMonth()+1)).slice(-2)
  var day=("0" + dateNow.getDate()).slice(-2)
  var dateNowString=year+"-"+month+"-"+day;
  return dateNowString;
}

function get_date_string_european(date = Date.now()){
  var dateNow=new Date(date);
  var year=dateNow.getFullYear();
  var month=("0" + (dateNow.getMonth()+1)).slice(-2)
  var day=("0" + dateNow.getDate()).slice(-2)
  var dateNowString=day+"/"+month+"/"+year;
  return dateNowString;
}

function get_date_string_european_with_hours(date = Date.now()){
  var dateNow=new Date(date);
  var year=dateNow.getFullYear();
  var month=("0" + (dateNow.getMonth()+1)).slice(-2)
  var day=("0" + dateNow.getDate()).slice(-2)
  var hours = ("0" + (dateNow.getHours())).slice(-2)
  var minutes = ("0" + (dateNow.getMinutes())).slice(-2)
  var dateNowString=day+"/"+month+"/"+year+" "+hours+":"+minutes;
  return dateNowString;
}

function get_date_string_european_with_hours2(date = Date.now()){
  var dateNow=new Date(date);
  var year=dateNow.getFullYear();
  var month=("0" + (dateNow.getMonth()+1)).slice(-2)
  var day=("0" + dateNow.getDate()).slice(-2)
  var hours = ("0" + (dateNow.getHours())).slice(-2)
  var minutes = ("0" + (dateNow.getMinutes())).slice(-2)
  var dateNowString=year+"-"+month+"-"+day+" "+hours+":"+minutes;
  return dateNowString;
}



// generic comparison function
cmp = function(x, y){
    return x > y ? 1 : x < y ? -1 : 0;
};


//récuperation du prix de leasing en fct du prix HTVA
function get_leasing_price(retailPrice, companyID=null){
  return  $.ajax({
    url: 'apis/Kameo/get_prices.php',
    method: 'post',
    data: {'retailPrice' : retailPrice, 'companyID': companyID},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
    }
  });
}

function initializeFields() {
  $("#widget-bikeManagement-form select[name=company]")
    .find("option")
    .remove()
    .end();
  $("#widget-updateAction-form select[name=company]")
    .find("option")
    .remove()
    .end();
  $("#widget-boxManagement-form select[name=company]")
    .find("option")
    .remove()
    .end();
    $("#widget-boxManagementAdmin-form select[name=company]")
      .find("option")
      .remove()
      .end();

  $("#widget-addBill-form select[name=company]")
    .find("option")
    .remove()
    .end();
     $("#widget-addDevis-form select[name=company]")
    .find("option")
    .remove()
    .end();

  $("#widget-manageStockAccessory-form select[name=company]")
    .find("option")
    .remove()
    .end();

  $("#widget-externalBikeManagement-form select[name=company]")
    .find("option")
    .remove()
    .end();

  $.ajax({
    url: "api/companies",
    type: "get",
    data: { type: "*", action: 'listMinimal' },
    success: function (response) {
      if (response.response == "success") {
        for (var i = 0; i < response.company.length; i++) {
          var selected = "";
          if (response.company[i].internalReference == "KAMEO") {
            selected = "selected";
          }
          $("#widget-bikeManagement-form select[name=company]").append(
            '<option value="' +
              response.company[i].internalReference +
              '">' +
              response.company[i].companyName +
              "<br>"
          );
          $("#widget-updateAction-form select[name=company]").append(
            '<option value="' +
              response.company[i].internalReference +
              '">' +
              response.company[i].companyName +
              "<br>"
          );
          $("#widget-boxManagement-form select[name=company]").append(
            '<option value="' +
              response.company[i].internalReference +
              '">' +
              response.company[i].companyName +
              "<br>"
          );
          $("#widget-boxManagementAdmin-form select[name=company]").append(
            '<option value="' +
              response.company[i].internalReference +
              '">' +
              response.company[i].companyName +
              "<br>"
          );
          $("#widget-order-form select[name=company]").append(
            '<option value= "' +
            response.company[i].ID +
            '">' +
            response.company[i].companyName +  "<br>"
          );
          $("#widget-addBill-form select[name=company]").append(
            '<option value= "' +
            response.company[i].internalReference +
            '">' +
            response.company[i].companyName +  "<br>"
          );
          $("#widget-addBill-form select[name=company]").val("");

           $("#widget-addDevis-form select[name=company]").append(
            '<option value= "' +
            response.company[i].internalReference +
            '">' +
            response.company[i].companyName +  "<br>"
          );
          $("#widget-addDevis-form select[name=company]").val("");

          $("#widget-manageStockAccessory-form select[name=company]").append(
            '<option value= "' +
            response.company[i].ID +
            '">' +
            response.company[i].companyName +  "<br>"
          );
          $("#widget-manageStockAccessory-form select[name=company]").val("");

          $("#widget-externalBikeManagement-form select[name=company]").append(
            '<option value= "' +
            response.company[i].ID +
            '">' +
            response.company[i].companyName +  "<br>"
          );
          $("#widget-externalBikeManagement-form select[name=company]").val("");


        }
      } else {
        console.log(response.response + ": " + response.message);
      }
    },
  });

  $.ajax({
    url: "apis/Kameo/initialize_fields.php",
    type: "get",
    data: { type: "ownerField" },
    success: function (response) {
      if (response.response == "success") {
        $("#widget-taskManagement-form select[name=owner]")
          .find("option")
          .remove()
          .end();
        $(".taskOwnerSelection").find("option").remove().end();
        $(".taskOwnerSelection2").find("option").remove().end();
        $(".taskOwnerSelection").append("<option value='*'>Tous<br>");
        $(".taskOwnerSelection2").append("<option value='*'>Tous<br>");
        $("#widget-taskManagement-form select[name=owner]").append(
          "<option value='*'>Tous<br>"
        );
        for (var i = 0; i < response.ownerNumber; i++) {
          $("#widget-taskManagement-form select[name=owner]").append(
            "<option value=" +
              response.owner[i].email +
              ">" +
              response.owner[i].firstName +
              " " +
              response.owner[i].name +
              "<br>"
          );
          $(".taskOwnerSelection").append(
            "<option value=" +
              response.owner[i].email +
              ">" +
              response.owner[i].firstName +
              " " +
              response.owner[i].name +
              "<br>"
          );
          $(".taskOwnerSelection2").append(
            "<option value=" +
              response.owner[i].email +
              ">" +
              response.owner[i].firstName +
              " " +
              response.owner[i].name +
              "<br>"
          );
        }
      } else {
        console.log(response.response + ": " + response.message);
      }
    },
  });
}


function getIndex(table, id) {
  for (var i = 0; i < table.length; i++) {
    if (table[i].id == id || table[i].ID == id) {
      return i;
    }
  }
}
