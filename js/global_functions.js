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

// generic comparison function
cmp = function(x, y){
    return x > y ? 1 : x < y ? -1 : 0;
};


function initializeFields() {
  $("#widget-bikeManagement-form select[name=company]")
    .find("option")
    .remove()
    .end();
  $("#widget-updateAction-form select[name=company]")
    .find("option")
    .remove()
    .end();
  $("#widget-taskManagement-form select[name=company]")
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

  $("#widget-maintenanceManagement-form select[name=company]")
    .find("option")
    .remove()
    .end();

  $.ajax({
    url: "apis/Kameo/get_companies_listing.php",
    type: "post",
    data: { type: "*" },
    success: function (response) {
      if (response.response == "success") {
        for (var i = 0; i < response.companiesNumber; i++) {
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
          $("#widget-taskManagement-form select[name=company]").append(
            '<option value="' +
              response.company[i].internalReference +
              '" ' +
              selected +
              ">" +
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
          $("#widget-maintenanceManagement-form select[name=company]").append(
            '<option id= "'+ response.company[i].ID + '" value= "' +
             response.company[i].internalReference +
            '">' +
            response.company[i].companyName +  "<br>"
            );
          $("#widget-order-form select[name=company]").append(
            '<option value= "' +
            response.company[i].ID +
            '">' +
            response.company[i].companyName +  "<br>"
            );
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

initializeFields();

function list_kameobikes_member() {
  $('#widget-addActionCompany-form select[name=owner]')
    .find('option')
    .remove()
    .end();

  $.ajax({
    url: 'apis/Kameo/get_kameobikes_members.php',
    type: 'get',
    success: function(response) {
      if (response.response == 'error')
        console.log(response.message);
      else if (response.response == 'success') {
        for (var i = 0; i < response.membersNumber; i++)
          $('#widget-addActionCompany-form select[name=owner]').append("<option value=" + response.member[i].email + ">" + response.member[i].firstName + " " + response.member[i].name + "<br>");
      }
    }
  });
}
list_kameobikes_member();
