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
        $('#bikeDetailsFull input[name=bikeID]').val(bikeID);
        $('#bikeDetailsFull input[name=bikeModel]').val(response.model);
        $('#bikeDetailsFull input[name=bikeNumber]').val(response.frameNumber);
        $('#bikeDetailsFull input[name=frameReference]').val(response.frameReference);
        $('#bikeDetailsFull input[name=contractType]').val(response.contractType);
        $('#bikeDetailsFull input[name=startDateContract]').val(response.contractStart);
        $('#bikeDetailsFull input[name=endDateContract]').val(response.contractEnd);
        $('#bikeDetailsFull input[name=bikeBrandCatalog]').val(response.brand);
        $('#bikeDetailsFull input[name=bikeModelCatalog]').val(response.modelCatalog);
        $('#bikeDetailsFull input[name=bikePrice]').val(Math.round(response.catalogPrice*1.21));
        $('#bikeDetailsFull select[name=bikeType]').val(response.biketype);

        if(response.contractType == "leasing"){
          $('#bikeDetailsFull .leasingAmount').removeClass("hidden");
          $('#bikeDetailsFull input[name=leasingAmount]').val(response.leasingPrice);
        }else $('#bikeDetailsFull .leasingAmount').addClass("hidden");

        if(response.biketype == "personnel"){
          $('#bikeDetailsFull .bikeOwner').removeClass("hidden");
          $('#bikeDetailsFull input[name=bikeOwner]').val(response.bikeOwner);
        }else{
          $('#bikeDetailsFull .bikeOwner').addClass("hidden");
        }

        document.getElementsByClassName("bikeImage")[0].src =
          "images_bikes/" + response.img + "_mini.jpg";
      }

      $('#valeurRachatTable').html("");
      for(i=12; i<37; i++){
        var parent = document.createElement("tr");
        var cell = document.createElement("td");
        if(i==12){
          cell.innerHTML="Mois [1-12]";
        }else{
          cell.innerHTML="Mois "+i;
        }
        parent.append(cell);
        var cell = document.createElement("td");
        if(i==12){
          cell.innerHTML=Math.round(response.catalogPrice*1.21)+" €";
        }else{
          cell.innerHTML=Math.round((0.6*(36-i)*response.leasingPrice*1.21 + 0.16*response.catalogPrice)*1.21)+" €";
        }
        parent.append(cell);
        $('#valeurRachatTable').append(parent);
      }

    },
  });

}
