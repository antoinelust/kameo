//FONCTION QUI GERE LES PERMISSION (à refaire), SEPARER LA PARTIE QUI GERE LES CONDITIONS
$( ".fleetmanager" ).click(function() {
	temp_init();
});



function temp_init(){
    var emailArray;
    var email= "<?php echo $user_data['EMAIL'];?>";
    $.ajax({
      url: 'apis/Kameo/get_company_conditions.php',
      type: 'post',
      data: { "email": email, "id": ""},

      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          $('#widget-updateCompanyConditions-form input[name=action]').val("update");
          if(response.update){
            $('.tasksManagerClick').click(function(){
                list_tasks('*', $('.taskOwnerSelection').val(), "<?php echo $user_data['EMAIL'] ?>");
                generateTasksGraphic('*', $('.taskOwnerSelection2').val(), $('.numberOfDays').val());
            });
            $('#offerManagerClick').click(function(){
                list_contracts_offers('*');
                generateCashGraphic();
            });
            $('.ordersManagerClick').click(function(){get_orders_listing()});
            var classname = document.getElementsByClassName('administrationKameo');
            for (var i = 0; i < classname.length; i++) {
              classname[i].classList.remove("hidden");
            }
          }
        }
      }
    })
}
function generateCashGraphic()
  {
	  $.ajax({
        url: 'apis/Kameo/offer_management.php',
        type: 'get',
        data: { "graphics": "Y", action: "retrieve"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                var threeYearsFromNow = new Date();
                threeYearsFromNow.setFullYear(threeYearsFromNow.getFullYear() + 1);
                var maxXAxis=threeYearsFromNow.toISOString().split('T')[0];

                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        datasets: [{
                            label: 'Contrats signés',
                            borderColor: 'rgba(44, 132, 109, 0.5)',
                            backgroundColor:'rgba(44, 132, 109, 0)',
                            data: response.arrayContracts
                        },{
                            label: 'Offres',
                            borderColor: 'rgba(145, 145, 145, 0.5)',
                            backgroundColor:'rgba(145, 145, 145, 0)',
                            data: response.arrayOffers
                        },{
                            label: 'Chiffre d\'affaire',
                            borderColor: 'rgba(60, 179, 149, 0.5)',
                            backgroundColor:'rgba(60, 179, 149, 0)',
                            data: response.totalIN
                        },{
                            label: 'Frais',
                            borderColor: 'rgba(176, 0, 0, 0.5)',
                            backgroundColor:'rgba(176, 0, 0, 0)',
                            data: response.arrayCosts
                        },{
                            label: 'Cash flow',
                            borderColor: 'rgba(60, 179, 149, 0.5)',
                            backgroundColor:'rgba(60, 179, 149, 0.5)',
                            data: response.arrayFreeCashFlow
                        }],
                    labels: response.arrayDates

                    },

                    options: {
                        scales: {
                            xAxes:[{
                                ticks:{
                                    max: "2020-12-19"
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        },
                        elements: {
                            line: {
                                tension: 0
                            }
                        }

                    }
                });
				myChart.update();
            }
        }
    });
  }
