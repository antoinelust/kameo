$(".fleetmanager").click(function () {
  $.ajax({
    url: "apis/Kameo/initialize_counters.php",
    type: "post",
    data: { email: email, type: "statistics" },
    success: function (response) {
      if (response.response == "error") {
        console.log(response.message);
      }
      document.getElementById("statisticsCounter").innerHTML ='<span style="color:#3cb395" data-speed="1" data-refresh-interval="4" data-to="' + Math.round(response.progressCA*100) + '" data-from="0" data-seperator="false">' + Math.round(response.progressCA*100) + "%</span>";

      if (response.progressCA > 0) {
        $("#statisticsCounter").css("color", "#3cb395");
      }else {
        $("#statisticsCounter").css("color", "#d80000");
      }
    },
  });
});




$("#statisticsListing").on("show.bs.modal", function (event) {
  $.ajax({
    url: "api/statistics",
    type: "get",
    data: { action: "getStatistics" },
    success: function (response) {
      var ctx = document.getElementById("ordersStatisticsChart").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: response.commandsMonth,
          datasets: [
            {
              label: "Commnandes - Leasing",
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              borderWidth: 2,
              data: response.leasingOrders
            },
            {
              label: "Commandes - Ventes",
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(252, 39, 80, 0.5)",
              borderWidth: 2,
              data: response.sellingOrders
            }
          ],
        },
        options: {
          scales: {
            xAxes: [{ stacked: true }],
            yAxes: [{ stacked: true }]
          },
          title: {
              display: true,
              text: 'Evolution du nombre de commandes'
          },
          legend:{
            display: false
          }
        }
      });
      myChart.update();
      var ctx = document.getElementById("ordersMarginStatisticsChart").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: response.commandsMonth,
          datasets: [
            {
              label: "Ventes - Coûts",
              stack: 'Stack 0',
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(252, 39, 80, 0.5)",
              borderWidth: 2,
              data: response.sellingCost
            },
            {
              label: "Ventes - Marge",
              stack: 'Stack 0',
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(252, 39, 80, 0.1)",
              borderWidth: 2,
              data: response.sellingMargin
            },
            {
              label: "Leasing - Coûts",
              stack: 'Stack 1',
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              borderWidth: 2,
              data: response.leasingCost
            },
            {
              label: "Leasing - Marge",
              stack: 'Stack 1',
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.1)",
              borderWidth: 2,
              data: response.leasingMargin
            }
          ],
        },
        options: {
          tooltips: {
              mode: 'label',
              callbacks: {
                  label: function(tooltipItem, data) {
                      var corporation = data.datasets[tooltipItem.datasetIndex].label;
                      var valor = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                      return corporation + " : " + valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') +' €';
                  }
              }
          },
          scales: {
            xAxes: [{ stacked: true }],
            yAxes: [{
              stacked: true,
              ticks: {
                beginAtZero: true,
                callback: function(value, index, values) {
                  if(parseInt(value) >= 1000){
                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " €";
                  } else {
                    return value + " €";
                  }
                }
              }
            }]
          },
          title: {
              display: true,
              text: 'Coûts et marges des contrats signés'
          },
          legend:{
            display: false
          }
        }
      });
      myChart.update();
      var ctx = document.getElementById("contractStartStatisticsChart").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: response.contractStartMonth,
          datasets: [
            {
              label: "",
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              data: response.contractStartSum
            }
          ],
        },
        options: {
          title: {
              display: true,
              text: 'Evolution du nombre de vélos placés en leasing'
          },
          legend:{
            display: false
          },
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true
                  }
              }]
          }
        }
      });
      myChart.update();


      var ctx = document.getElementById("soldBikeStatisticsChart").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: response.soldBikesMonth,
          datasets: [
            {
              label: "",
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              data: response.soldBikesSum
            }
          ],
        },
        options: {
          title: {
              display: true,
              text: 'Evolution du nombre de vélos vendus'
          },
          legend:{
            display: false
          },
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true
                  }
              }]
          }
        }
      });
      myChart.update();

      var ctx = document.getElementById("deliveryChartNumber").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: response.deliveryMonth,
          datasets: [
            {
              label: "Nombre",
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              data: response.deliveryNumberOfBike
            }
          ],
        },
        options: {
          title: {
              display: true,
              text: 'Nombre de vélos arrivant dans le stock'
          },
          legend:{
            display: false
          },
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true
                  }
              }]
          }
        }
      });
      myChart.update();

      var ctx = document.getElementById("currentStockBrands").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: 'bar',
        data:{
          labels: response.stockByBrandLabel,
          datasets: [{
            data: response.stockByBrandData,
            borderColor: "rgba(44, 132, 109, 0.5)",
            backgroundColor: "rgba(60, 179, 149, 0.5)",
            hoverOffset: 4
          }],
        },
        options: {
          title: {
              display: true,
              text: 'Vélos dans le stock par marque'
          },
          legend:{
            display: false
          }
        }
      });
      myChart.update();

      var ctx = document.getElementById("currentStockTypes").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: 'bar',
        data:{
          labels: response.stockByUtilisationLabel,
          datasets: [{
            label: 'My First Dataset',
            data: response.stockByUtilisationData,
            borderColor: "rgba(44, 132, 109, 0.5)",
            backgroundColor: "rgba(60, 179, 149, 0.5)",
            hoverOffset: 4
          }],
        },
        options: {
          title: {
              display: true,
              text: 'Vélos dans le stock par utilisation'
          },
          legend:{
            display: false
          }
        }
      });
      myChart.update();



      var ctx = document.getElementById("deliveryChartValue").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: response.deliveryMonth,
          datasets: [
            {
              label: "Coût d'achat",
              borderWidth: 2,
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              data: response.deliveryCost
            },
            {
              label: "Marge potentielle sur vente",
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.1)",
              borderWidth: 2,
              data: response.retailMargin
            }
          ],
        },
        options: {
          title: {
              display: true,
              text: 'Valeur des vélos arrivant dans le stock'
          },
          legend:{
            display: false
          },
          tooltips: {
              mode: 'label',
              callbacks: {
                  label: function(tooltipItem, data) {
                      var corporation = data.datasets[tooltipItem.datasetIndex].label;
                      var valor = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                      return corporation + " : " + valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') +' €';
                  }
              }
          },
          scales: {
            xAxes: [{ stacked: true }],
            yAxes: [{
              stacked: true,
              ticks: {
                beginAtZero: true,
                callback: function(value, index, values) {
                  if(parseInt(value) >= 1000){
                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " €";
                  } else {
                    return value + " €";
                  }
                }
              }
            }]
          }
        }
      });
      myChart.update();



    }
  });
  $.ajax({
    url: "api/cashFlow",
    type: "get",
    data: { action: "getGraphics" },
    success: function (response) {
      $('#statisticsListing span[name=totalCA]').html(Math.round(response.totalCA)+' €');
      var ctx = document.getElementById("myChartCA").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: "bar",
        data: {
          datasets: [
            {
              label: "Leasing vélos",
              fill: true,
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              data: response.bikeLeasing
            },
            {
              label: "Vente vélos",
              fill: true,
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(252, 39, 80, 0.5)",
              data: response.bikeSelling
            },
            {
              label: "Leasing bornes",
              fill: true,
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(176, 0, 0, 0.5)",
              data: response.boxesLeasing
            },
            {
              label: "Leasing accessoires",
              fill: true,
              borderColor: "rgba(60, 179, 149, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              data: response.accessoryLeasing
            },
            {
              label: "Ventes accessoires",
              fill: true,
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(176, 0, 0, 0.5)",
              data: response.accessorySelling
            },
            {
              label: "Main d'oeuvre",
              fill: true,
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(176, 0, 0, 0.5)",
              data: response.maintenance
            }
          ],
          labels: response.arrayDatesCA,
        },
        options: {
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        var corporation = data.datasets[tooltipItem.datasetIndex].label;
                        var valor = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        var total = 0;
                        for (var i = 0; i < data.datasets.length; i++)
                            total += data.datasets[i].data[tooltipItem.index];
                        if (tooltipItem.datasetIndex != data.datasets.length - 1) {
                            return corporation + " : " + valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') +' €';
                        } else {
                            return [corporation + " : " + valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'), "Total : $" + total + ' €'];
                        }
                    }
                }
            },
            responsive: true,
            scales: {
              xAxes: [{
                stacked: true,
              }],
              yAxes: [{
                stacked: true,
                ticks: {
                  beginAtZero: true,
                  callback: function(value, index, values) {
                    if(parseInt(value) >= 1000){
                      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " €";
                    } else {
                      return value + " €";
                    }
                  }
                }
              }]
            }
        }
      });
      myChart.update();
    }
  })
});
