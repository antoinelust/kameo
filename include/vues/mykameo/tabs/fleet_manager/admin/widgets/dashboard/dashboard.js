$(".fleetmanager").click(function () {
  list_errors();
  initialize_task_owner_sales_selection();
});

var today = new Date();
var dashboard_tabs = [
  $("#dashboardBodyBills"),
  $("#dashboardBodyBikes"),
  $("#dashboardBodySells"),
  $("#dashboardBodyCompanies"),
];

$(".form_date_start_sell").datetimepicker({
  language: "fr",
  weekStart: 1,
  todayBtn: 1,
  autoclose: 1,
  todayHighlight: 1,
  startView: 2,
  minView: 2,
  forceParse: 0,
});

$(".form_date_end_sell").datetimepicker({
  language: "fr",
  weekStart: 1,
  todayBtn: 1,
  autoclose: 1,
  todayHighlight: 1,
  startView: 2,
  minView: 2,
  forceParse: 0,
});

$(".form_date_end_sell").data("datetimepicker").setDate(today);
today.setDate(today.getDate() - 7);
$(".form_date_start_sell").data("datetimepicker").setDate(today);

$(".form_date_start_sell").change(function () {
  list_sales(
    $(".taskOwnerSalesSelection").val(),
    $(".form_date_start_sell").data("datetimepicker").getDate(),
    $(".form_date_end_sell").data("datetimepicker").getDate()
  );
});
$(".form_date_end_sell").change(function () {
  list_sales(
    $(".taskOwnerSalesSelection").val(),
    $(".form_date_start_sell").data("datetimepicker").getDate(),
    $(".form_date_end_sell").data("datetimepicker").getDate()
  );
});
$(".taskOwnerSalesSelection").change(function () {
  list_sales(
    $(".taskOwnerSalesSelection").val(),
    $(".form_date_start_sell").data("datetimepicker").getDate(),
    $(".form_date_end_sell").data("datetimepicker").getDate()
  );
});
$(".dashboardBikes").click(function () {
  $(".dashboardTitle").html("Erreurs à corriger - Vélos");
  dashboard_tabs.forEach((tab) => tab.fadeOut());
  $("#dashboardBodyBikes").fadeIn();
});
$(".dashboardBills").click(function () {
  $(".dashboardTitle").html("Erreurs à corriger - Factures");
  dashboard_tabs.forEach((tab) => tab.fadeOut());
  $("#dashboardBodyBills").fadeIn();
});
$(".dashboardSells").click(function () {
  $(".dashboardTitle").html("Suivi prospection commerciale");
  dashboard_tabs.forEach((tab) => tab.fadeOut());
  $("#dashboardBodySells").fadeIn();
});
$(".dashboardCompanies").click(function () {
  $(".dashboardTitle").html("Erreurs à corriger - Sociétés");
  dashboard_tabs.forEach((tab) => tab.fadeOut());
  $("#dashboardBodyCompanies").fadeIn();
});
function list_errors() {
  $("#dashboardBodyBikes").html('');
  $("#dashboardBodyBills").html('');
  $.ajax({
    url: "apis/Kameo/error_management.php",
    method: "get",
    data: {
      action: "list",
      item: "bikesAndBoxes",
    },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        var i = 0;
        var j = 0;
        var dest ='<table class="table table-condensed"  data-order=\'[[ 0, "asc" ]]\'><thead><tr><th>ID</th><th scope="col">Référence</th><th>Description</th></thead><tbody>';


        response.bike.selling.forEach(function(bike){
          if (bike.frameNumber == null) {
            var bikeDescription = "N/A - " + bike.bikeID;
          } else {
            var bikeDescription = bike.bikeID + " - " + bike.frameNumber;
          }
          var temp =
            '<tr><td scope="row">' +
            (i + 1) +
            '</td><td><a class="updateBikeAdmin" data-target="#bikeManagement" name="' +
            bike.bikeID +
            '" data-toggle="modal" href="#">' +
            bikeDescription +
            "</a></td><td>Le vélo " +
            bikeDescription +
            " a été vendu mais la date de vente n'est pas mentionnée</td><td></tr>"; //onclick=\"set_required_image('false')\"
          dest = dest.concat(temp);
          i++;
        })
        if(response.contract.length > 0){
          response.contract.forEach(function(contract){
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              '</td><td><a class="updateBikeAdmin" data-target="#bikeManagement" name="' +
              contract.bikeID +
              '" data-toggle="modal" href="#">' +
              contract.frameNumber +
              "</a></td><td>Le vélo " +
              contract.frameNumber +
              " est en leasing sur une durée de "+contract.contractDuration+" mois. Les durées acceptées sont 24, 36 ou 48 mois. (valeur actuelles de contract : "+contract.contractStart+" au "+contract.contractEnd+")</td><td></tr>";
            dest = dest.concat(temp);
            i++;
          })
        }


        response.bike.order.forEach(function(bike){
          var temp =
            '<tr><td scope="row">' +
            (i + 1) +
            '</td><td><a class="updateBikeAdmin" data-target="#bikeManagement" name="' +
            bike.bikeID +
            '" data-toggle="modal" href="#">' +
            bike.bikeID +
            "</a></td><td>Le vélo est censer arriver chez nous le " +
            bike.supplierDeliveryDate +
            " et est mentionné en livraison chez le client le "+
            bike.clientDeliveryDate +
            "</td><td></tr>";
            i++;
            dest = dest.concat(temp);
        })

        if(typeof response.bike.sellingCompany != 'undefined'){
          response.bike.sellingCompany.forEach(function(bike){
            if (bike.frameNumber == null) {
              var bikeDescription = "N/A - " + bike.bikeID;
            } else {
              var bikeDescription = bike.bikeID + " - " + bike.frameNumber;
            }
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              '</td><td><a class="updateBikeAdmin" data-target="#bikeManagement" name="' +
              bike.bikeID +
              '" data-toggle="modal" href="#">' +
              bikeDescription +
              "</a></td><td>Le vélo " +
              bikeDescription +
              " a été vendu, il ne peut pas être assigné à Kameo</td><td></tr>"; //onclick=\"set_required_image('false')\"
            dest = dest.concat(temp);
            i++;
          })
        }
        if(typeof response.bike.stock != 'undefined'){
          response.bike.stock.forEach(function(bike){
            var bike = response.bike.stock[j];
            if (bike.frameNumber == null) {
              var bikeDescription = "N/A - " + bike.bikeID;
            }else{
              var bikeDescription = bike.bikeID + " - " + bike.frameNumber;
            }
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              '</td><td><a class="updateBikeAdmin" data-target="#bikeManagement" name="' +
              bike.bikeID +
              '" data-toggle="modal" href="#">' +
              bikeDescription +
              "</a></td><td>Le vélo " +
              bikeDescription +
              " ne peut pas être défini comme vélo de stock en dehors de la société Kameo</td><td></tr>"; //onclick=\"set_required_image('false')\"
            dest = dest.concat(temp);
          })
        }
        dest = dest.concat("</tbody></table>");
        $("#dashboardBodyBikes").html(dest);
        var i = 0;
        var dest = '<table class="table table-condensed"  data-order=\'[[ 0, "asc" ]]\'><thead><tr><th scope="col">ID</th><th scope="col">'+traduction.generic_reference+'</th><th scope="col">Description</th><th></th></thead><tbody>';
        if(typeof response.bike.bill != 'undefined'){
          response.bike.bill.forEach(function(bill){
            if (bill.bikeNumber == null) {
              var bikeDescription = bill.bikeID + " - N/A";
            } else {
              var bikeDescription = bill.bikeID + " - " + bill.bikeNumber;
            }
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              '</td><td><a class="updateBikeAdmin" data-target="#bikeManagement" name="' +
              bill.bikeID +
              '" data-toggle="modal" href="#">' +
              bikeDescription +
              "</a></td><td>" +
              bill.description +
              "</td><td>"+
              '<a class="button small green button-3d rounded icon-left generateInvoice" data-company="'+bill.company+'" data-date="'+bill.date+'"><i class="fa fa-paper-plane"></i>Générer </a>'+
              "</td></tr>";
            dest = dest.concat(temp);
            i++;
          })
        }
        if(typeof response.box.bill != 'undefined'){
          response.box.bill.forEach(function(bill){
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              '</td><td> Borne de chez ' +
              bill.company + ' - ID :' +
              bill.boxID +
              "</td><td>" +
              bill.description +
              "</td><td></td></tr>";
            //onclick=\"set_required_image('false')\"
            dest = dest.concat(temp);
            i++;
          })
        }
        dest = dest.concat("</tbody></table>");
        $("#dashboardBodyBills").html(dest);
        var i = 0;
        var dest =
          '<table class="table table-condensed"  data-order=\'[[ 0, "asc" ]]\'><thead><tr><th>ID</th><th scope="col"><span class="fr-inline">Référence</span><span class="en-inline">Bike Number</span><span class="nl-inline">Bike Number</span></th><th>Description</th></thead><tbody>';

        if(typeof response.company.img != 'undefined'){
          response.company.img.forEach(function(company){
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              '</td><td><a class="updateBikeAdmin" data-target="#bikeManagement" name="' +
              company.id +
              '" data-toggle="modal" href="#">' +
              company.name +
              "</a></td><td>Image manquante pour la société " +
              company.name +
              "</td></tr>";
            //onclick=\"set_required_image('false')\"
            dest = dest.concat(temp);
            i++;
          })
        }

        if(typeof response.company.action != 'undefined'){
          response.company.action.forEach(function(action){
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              '</td><td><a href="#" class="updateAction" data-target="#updateAction" data-toggle="modal" name="' +
              action.id +
              '">' +
              action.id +
              "</a></td><td>" +
              action.description +
              "</td></tr>";
            dest = dest.concat(temp);
            i++;
          })
        }
        dest = dest.concat("</tbody></table>");
        $("#dashboardBodyCompanies").html(dest);
        $(".updateAction").click(function () {
          construct_form_for_action_update(this.name);
        });
        $(".dashboardBikes").html(
          "Vélos (" +
            (response.bike.selling.length +
              response.bike.sellingCompany.length +
              response.bike.order.length) +
            ")"
        );
        $(".dashboardBills").html(
          "Factures (" + (((typeof response.bike.bill != 'undefined') ? parseInt(response.bike.bill.length) : 0)+parseInt(response.box.bill.length)) + ")"
        );
        $(".dashboardCompanies").html(
          "Sociétés (" +
            (((typeof response.company.img != 'undefined') ? response.company.img.length : 0) +
              ((typeof response.company.action != 'undefined') ? response.company.action.length : 0)) +
            ")"
        );
        if (
          parseInt((typeof response.bike.selling != 'undefined') ? response.bike.selling.length : 0) +
          parseInt((typeof response.bike.sellingCompany != 'undefined') ? response.bike.sellingCompany.length : 0) +
          parseInt(response.contract.length) +
          parseInt((typeof response.box.bill != 'undefined') ? response.box.bill.length : 0) +
          parseInt((typeof response.bike.order != 'undefined') ? response.bike.order.length : 0) +
          ((typeof response.bike.bill != 'undefined') ? parseInt(response.bike.bill.length) : 0)+
          parseInt((typeof response.company.img != 'undefined') ? response.company.img.length : 0) +
          parseInt((typeof response.company.action != 'undefined') ? response.company.action.length : 0)
           ==
          0
        ) {
          document.getElementById("errorCounter").innerHTML =
            '<span data-speed="1" data-refresh-interval="4" data-to="0" data-from="0" data-seperator="true">0</span>';
          $("#errorCounter").css("color", "#3cb395");
        } else {
          document.getElementById("errorCounter").innerHTML =
            '<span data-speed="1" data-refresh-interval="4" data-to="' +
              (parseInt((typeof response.bike.selling != 'undefined') ? response.bike.selling.length : 0) +
              parseInt((typeof response.bike.sellingCompany != 'undefined') ? response.bike.sellingCompany.length : 0) +
              parseInt(response.contract.length) +
              parseInt((typeof response.box.bill != 'undefined') ? response.box.bill.length : 0) +
              parseInt((typeof response.bike.order != 'undefined') ? response.bike.order.length : 0) +
              ((typeof response.bike.bill != 'undefined') ? parseInt(response.bike.bill.length) : 0)+
              parseInt((typeof response.company.img != 'undefined') ? response.company.img.length : 0) +
              parseInt((typeof response.company.action != 'undefined') ? response.company.action.length : 0)) +
            '" data-from="0" data-seperator="true">' +
              (parseInt((typeof response.bike.selling != 'undefined') ? response.bike.selling.length : 0) +
              parseInt((typeof response.bike.sellingCompany != 'undefined') ? response.bike.sellingCompany.length : 0) +
              parseInt(response.contract.length) +
              parseInt((typeof response.box.bill != 'undefined') ? response.box.bill.length : 0) +
              parseInt((typeof response.bike.order != 'undefined') ? response.bike.order.length : 0) +
              ((typeof response.bike.bill != 'undefined') ? parseInt(response.bike.bill.length) : 0)+
              parseInt((typeof response.company.img != 'undefined') ? response.company.img.length : 0) +
              parseInt((typeof response.company.action != 'undefined') ? response.company.action.length : 0)) +
            "</span>";
          $("#errorCounter").css("color", "#d80000");
        }
        $(".updateBikeAdmin").off();
        $(".updateBikeAdmin").click(function () {
          construct_form_for_bike_status_updateAdmin(this.name);
          $("#widget-bikeManagement-form input").attr("readonly", false);
          $("#widget-bikeManagement-form select").attr("readonly", false);
          $(".bikeManagementTitle").html("Modifier un vélo");
          $(".bikeManagementSend").removeClass("hidden");
          $(".bikeManagementSend").html('<i class="fa fa-plus"></i>Modifier');
        });

        $('.generateInvoice').off();
        $('.generateInvoice').click(function(){
          var DateParts = $(this).data('date').split("-");
          var dateTemp = new Date(DateParts[2], DateParts[1] - 1, DateParts[0]);
          var url = "generate_invoices.php?forced=Y&company="+$(this).data('company')+"&dateStart="+get_date_string(new Date(dateTemp.getFullYear(), dateTemp.getMonth(), 1))+"&dateEnd="+get_date_string(new Date(dateTemp.getFullYear(), dateTemp.getMonth()+1, 0));
          $.ajax({
            url: url,
            method: "get",
            success: function (response) {
              list_errors();
            }
          });
        })
      }
    },
  });
}


function initialize_task_owner_sales_selection() {
  $.ajax({
    url: "apis/Kameo/sales_management.php",
    method: "get",
    data: {
      action: "list",
      item: "owners",
    },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        $(".taskOwnerSalesSelection").find("option").remove().end();
        $(".taskOwnerSalesSelection").append("<option value='*'>Tous<br>");
        for (i = 0; i < response.ownerNumber; i++) {
          $(".taskOwnerSalesSelection").append(
            "<option value=" +
              response.owner[i].email +
              ">" +
              response.owner[i].firstName +
              " " +
              response.owner[i].name +
              "<br>"
          );
          i++;
        }
        list_sales(
          "*",
          $(".form_date_start_sell").data("datetimepicker").getDate(),
          $(".form_date_end_sell").data("datetimepicker").getDate()
        );
      }
    },
  });
}


function list_sales(owner, start, end) {
  dateStartString =
    start.getFullYear() +
    "-" +
    ("0" + (start.getMonth() + 1)).slice(-2) +
    "-" +
    ("0" + start.getDate()).slice(-2);
  dateEndString =
    end.getFullYear() +
    "-" +
    ("0" + (end.getMonth() + 1)).slice(-2) +
    "-" +
    ("0" + end.getDate()).slice(-2);
  $.ajax({
    url: "apis/Kameo/sales_management.php",
    method: "get",
    data: {
      action: "list",
      item: "sales",
      owner: owner,
      start: dateStartString,
      end: dateEndString,
    },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        var i = 0;
        var dest =
          '<table class="table table-condensed"><thead><tr><th>ID</th><th scope="col"><span>Date</span></th><th>Owner</th><th>Description</th><th>Points</th></thead><tbody>';
        var totalPoints = 0;
        while (i < response.sales.contact.number) {
          var contact = response.sales.contact[i];
          if (contact.type == "premier contact") {
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              "</td><td>" +
              contact.date.shortDate() +
              "</td><td>" +
              contact.owner +
              '</td><td><strong>Type:</strong> Prise de contact pour entreprise <a href="#" class="internalReferenceCompany" data-target="#companyDetails" data-toggle="modal" name="' +
              contact.companyID +
              '">' +
              contact.company +
              "</a><br/><strong>Description :</strong> " +
              contact.description.replace(/(\r\n|\n|\r)/g, "<br />") +
              "</td><td>5</td></tr>";
            totalPoints += 5;
          } else if ((contact.type = "rappel")) {
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              "</td><td>" +
              contact.date.shortDate() +
              "</td><td>" +
              contact.owner +
              '</td><td><strong>Type:</strong> Relance pour entreprise <a href="#" class="internalReferenceCompany" data-target="#companyDetails" data-toggle="modal" name="' +
              contact.companyID +
              '">' +
              contact.company +
              "</a><br/><strong>Description :</strong> " +
              contact.description.replace(/(\r\n|\n|\r)/g, "<br />") +
              "</td><td>1</td></tr>";
            totalPoints += 1;
          } else if ((contact.type = "plan rdv")) {
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              "</td><td>" +
              contact.date.shortDate() +
              "</td><td>" +
              contact.owner +
              '</td><td><strong>Type:</strong> Planficiation de rdv pour entreprise <a href="#" class="internalReferenceCompany" data-target="#companyDetails" data-toggle="modal" name="' +
              contact.companyID +
              '">' +
              contact.company +
              "</a><br/><strong>Description :</strong> " +
              contact.description.replace(/(\r\n|\n|\r)/g, "<br />") +
              "</td><td>10</td></tr>";
            totalPoints += 10;
          } else {
            var temp =
              '<tr><td scope="row">' +
              (i + 1) +
              "</td><td>" +
              contact.date.shortDate() +
              "</td><td>" +
              contact.owner +
              '</td><td><strong>Type:</strong> Type inconnu pour entreprise <a href="#" class="internalReferenceCompany" data-target="#companyDetails" data-toggle="modal" name="' +
              contact.companyID +
              '">' +
              contact.company +
              "</a><br/><strong>Description :</strong> " +
              contact.description.replace(/(\r\n|\n|\r)/g, "<br />") +
              "</td><td>0</td></tr>";
          }
          dest = dest.concat(temp);
          i++;
        }
        dest = dest.concat("</tbody></table>");
        dest = dest.concat(
          "<p>Nombre de points au total : <strong>" +
            totalPoints +
            "</strong></p>"
        );
        $("#dashboardBodySellsTable").html(dest);
        $(".internalReferenceCompany").click(function () {
          get_company_details(this.name, email, true);
        });
      }
    },
  });
}
