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
                document.getElementById('counterBikeAdmin').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bikeNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.bikeNumber+"</span>";          
                document.getElementById('counterBikePortfolio').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.bikeNumberPortfolio+"\" data-from=\"0\" data-seperator=\"true\">"+response.bikeNumberPortfolio+"</span>";
                document.getElementById('counterClients').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.companiesNumberClientOrProspect+"\" data-from=\"0\" data-  seperator=\"true\">"+response.companiesNumberClientOrProspect+"</span>";
                document.getElementById('cashFlowSpan').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+Math.round(response.sumContractsCurrent)+"\" data-from=\"0\" data-    seperator=\"true\">"+Math.round(response.sumContractsCurrent)+"</span>";
                document.getElementById('counterTasks').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.actionNumberNotDone+"\" data-from=\"0\" data-seperator=\"true\">"+response.actionNumberNotDone+"</span>";
                document.getElementById('counterBoxes').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.boxesNumberTotal+"\" data-from=\"0\" data-seperator=\"true\">"+response.boxesNumberTotal+"</span>";
                document.getElementById('counterBills').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.billsNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.billsNumber+"</span>";
                document.getElementById('counterFeedbacks').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.feedbacksNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.feedbacksNumber+"</span>";
                
            }
        }
    })
}

