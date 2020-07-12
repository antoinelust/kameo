function initialize_counters() {
    $.ajax({
        url: 'include/initialize_counters.php',
        type: 'post',
        data: { "email": email},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                
                document.getElementById('counterBike').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bikeNumberClient+"\" data-from=\"0\" data-seperator=\"true\">"+response.bikeNumberClient+"</span>";
                document.getElementById('counterOrders').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.ordersNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.ordersNumber+"</span>";
                document.getElementById('counterBookings').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bookingNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.bookingNumber+"</span>";
                document.getElementById('counterUsers').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.usersNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.usersNumber+"</span>";
                if(response.billsNumber==0){
                document.getElementById('counterBills').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.billsNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.billsNumber+"</span>";
                    $('#counterBills').css('color', '#3cb395');                
                }else{
                document.getElementById('counterBills').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.billsNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.billsNumber+"</span>";
                    $('#counterBills').css('color', '#d80000');
                }

                if(response.company=='KAMEO'){
                    document.getElementById('counterBikeAdmin').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bikeNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.bikeNumber+"</span>";          
                    document.getElementById('counterBikePortfolio').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bikeNumberPortfolio+"\" data-from=\"0\" data-seperator=\"true\">"+response.bikeNumberPortfolio+"</span>";
                    document.getElementById('counterClients').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.companiesNumberClientOrProspect+"\" data-from=\"0\" data-  seperator=\"true\">"+response.companiesNumberClientOrProspect+"</span>";

                    if(response.sumContractsCurrent>0){
                        document.getElementById('cashFlowSpan').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+Math.round(response.sumContractsCurrent)+"\" data-from=\"0\" data-    seperator=\"true\">"+Math.round(response.sumContractsCurrent)+"</span>";
                        $('#cashFlowSpan').css('color', '#3cb395');                
                    }else{
                        document.getElementById('cashFlowSpan').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+Math.round(response.sumContractsCurrent)+"\" data-from=\"0\" data-      seperator=\"true\">"+Math.round(response.sumContractsCurrent)+"</span>";
                        $('#cashFlowSpan').css('color', '#d80000');
                    }


                    document.getElementById('counterTasks').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.actionNumberNotDone+"\" data-from=\"0\" data-seperator=\"true\">"+response.actionNumberNotDone+"</span>";
                    document.getElementById('counterBoxes').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.boxesNumberTotal+"\" data-from=\"0\" data-seperator=\"true\">"+response.boxesNumberTotal+"</span>";
                    document.getElementById('counterFeedbacks').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.feedbacksNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.feedbacksNumber+"</span>";


                }
            }
        }
    })
}

