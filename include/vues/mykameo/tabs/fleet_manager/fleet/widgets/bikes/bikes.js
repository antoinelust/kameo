$( ".fleetmanager" ).click(function() {
    $.ajax({
        url: 'apis/Kameo/initialize_counters.php',
        type: 'post',
        data: { "email": email, "type": "bikes"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('counterBike').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bikeNumberClient+"\" data-from=\"0\" data-seperator=\"true\">"+response.bikeNumberClient+"</span>";
            }
        }
    })
})

$(".clientBikesManagerClick").click(function () {
 $("#bikeDetails").dataTable({
   destroy: true,
   ajax: {
     url: "api/bikes",
     contentType: "application/json",
     type: "GET",
     data: {
       action: "list",
     },
   },
   sAjaxDataProp: "bike",
   columns: [
     {
       title: "ID",
       data: "id",
       fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
         $(nTd).html("<a data-target=\"#bikeDetailsFull\" class=\"text-green retrieveBikeDetails\" name=\""+sData+"\" data-toggle=\"modal\" href=\"#\">"+sData+"</a>");
       }
     },
     { className: "hidden-xs", title: "Tech. ID", data: "frameNumber" },
     { title: "Funct. ID", data: "model" },
     {
       className: "hidden-xs",
       title: traduction.bike_description_contract_start,
       data: "contractStart",
       fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
         if (sData !== null) $(nTd).html(sData);
         else $(nTd).html("N/A");
       },
     },
     {
       className: "hidden-xs",
       title: traduction.bike_description_contract_end,
       data: "contractEnd",
       fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
         if (sData !== null) $(nTd).html(sData);
         else $(nTd).html("N/A");
       },
     },
     {
       className: "hidden-xs",
       title: traduction.leasingType_amountLeasing,
       data: "leasingPrice",
       fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
         if (sData !== null) $(nTd).html(sData + "€/"+traduction.generic_mois);
         else $(nTd).html("0 €/mois");
       },
     },
     {
       title: traduction.generic_status,
       data: "status",
       fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
         if (sData == null || sData == "KO") $(nTd).html("<i class=\"fa fa-close\" style=\"color:red\" aria-hidden=\"true\"></i>");
         else $(nTd).html("<i class=\"fa fa-check\" style=\"color:green\" aria-hidden=\"true\"></i>");
       },
     },
     {
       data: "id",
       fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
         $(nTd).html("<ins><a class=\"text-green updateBikeStatus\" data-target=\"#updateBikeStatus\" name=\""+sData+"\" data-toggle=\"modal\" href=\"#\">"+traduction.generic_update+"</a></ins>");
         },
      }
   ],
   order: [
     [0, "asc"]
   ],
   paging : false
 });
});

$("#bikeDetailsFull").on("show.bs.modal", function (event) {
 var bikeID = $(event.relatedTarget).attr("name");
 fillBikeDetails(bikeID);
 $.ajax({
   url: "apis/Kameo/action_bike_management.php",
   type: "post",
   data: {
     "readActionBike-action": "read",
     "readActionBike-bikeNumber": bikeID,
   },
   success : function(data) {
     $('#action_bike_log_user').dataTable( {
     destroy: true,
     bInfo : false,
     paging: false,
     searching: false,
     sAjaxDataProp: "",
     data : data.action,
     columns: [
     {
        title: traduction.generic_date,
        data: "date",
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
          $(nTd).html(sData.substring(0, 10));
        },
      },
      {
         title: traduction.generic_description,
         data: "title",
         fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
           if (sData == 'maintenance') $(nTd).html("Entretien fait");
           else if(sData == 'maintenance')$(nTd).html(description);
         },
       }
     ],
     order: [
       [0, "desc"]
     ]
    });
   }
 });
});

$("#updateBikeStatus").on("show.bs.modal", function (event) {
 var bikeID = $(event.relatedTarget).attr("name");
 construct_form_for_bike_status_update(bikeID);
})
