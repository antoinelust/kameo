function fillBikeDetails(element) {
  var bikeID = element;
  $.ajax({
    url: "apis/Kameo/get_bike_details.php",
    type: "post",
    data: { bikeID: bikeID },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        document.getElementsByClassName("bikeID")[0].innerHTML = bikeID;
        document.getElementsByClassName("bikeBrandCatalog")[0].innerHTML = response.brand;
        document.getElementsByClassName("bikeModelCatalog")[0].innerHTML = response.modelCatalog;
        document.getElementsByClassName("bikeNumber")[0].innerHTML = response.frameNumber;
        document.getElementsByClassName("bikeModel")[0].innerHTML =
          response.model;
        document.getElementsByClassName("bikePrice")[0].innerHTML = Math.round(response.catalogPrice*1.21) + " €";

        document.getElementsByClassName("frameReference")[0].innerHTML =
          response.frameReference;
        document.getElementsByClassName("contractType")[0].innerHTML =
          response.contractType;
        document.getElementsByClassName("startDateContract")[0].innerHTML =
          response.contractStart;
        document.getElementsByClassName("endDateContract")[0].innerHTML =
          response.contractEnd;
        document.getElementsByClassName("bikeImage")[0].src =
          "images_bikes/" + response.img + "_mini.jpg";
      }
    },
  });

  $.ajax({
    url: "apis/Kameo/action_bike_management.php",
    type: "post",
    data: {
      "readActionBike-action": "read",
      "readActionBike-bikeNumber": bikeID,
      "readActionBike-user": "<?php echo $user_data['EMAIL']; ?>",
    },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      } else {
        var i = 0;
        var dest =
          '<table class="table table-condensed"><tbody><thead><tr><th><span class="fr-inline">Date</span><span class="en-inline">Date</span><span class="nl-inline">Date</span></th><th><span class="fr-inline">Description</span><span class="en-inline">Description</span><span class="nl-inline">Description</span></th></tr></thead> ';
        while (i < response.actionNumber) {
          if (response.action[i].public == "1") {
            var temp =
              "<tr><td>" +
              response.action[i].date.substring(0, 10) +
              "</td><td>" +
              response.action[i].description +
              "</td></tr>";
            dest = dest.concat(temp);
          }
          i++;
        }
        dest = dest.concat("</tbody></table>");
        $("#action_bike_log_user").html(dest);
        displayLanguage();
      }
    },
  });
}
