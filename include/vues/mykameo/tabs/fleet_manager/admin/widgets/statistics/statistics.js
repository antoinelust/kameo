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

      var total = 0;
      for (var i = 0; i < response.leasingOrders.length; i++) {
        if(new Date(response.commandsMonth[i])>=new Date('2021-01-01')){
          total += response.leasingOrders[i] << 0;
        }
      }
      $('#statisticsListing span[name=ordersStatisticsTotalLeasing]').html(total);

      var total = 0;
      for (var i = 0; i < response.sellingOrders.length; i++) {
        if(new Date(response.commandsMonth[i])>=new Date('2021-01-01')){
          total += response.sellingOrders[i] << 0;
        }
      }
      $('#statisticsListing span[name=ordersStatisticsTotalSelling]').html(total);



      $("canvas#ordersStatisticsChart").remove();
      $("div.ordersStatisticsChart").append('<canvas id="ordersStatisticsChart" class="animated fadeIn" width="100%"></canvas>');

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
              stack: 'Stack 0',
              data: response.leasingOrders
            },
            {
              label: "Commandes - Ventes",
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(252, 39, 80, 0.5)",
              borderWidth: 2,
              stack: 'Stack 0',
              data: response.sellingOrders
            }
          ],
        },
        options: {
          scales: {
            xAxes: [{ stacked: true }],
            yAxes: [{ stacked: true }]
          },
          plugins: {
              legend: {
                  display: false,
              },
              title: {
                  display: true,
                  text: 'Nombre de commandes'
              },
              tooltip: {
                callbacks: {
                  footer: function(tooltipItems, data) {
                    let sum = 0;
                    tooltipItems.forEach(function(tooltipItem) {
                      sum += tooltipItem.parsed.y;
                    });
                    return 'Total : ' + sum;
                  }
                }
              }
          }
        }
      });
      myChart.update();

      var total = 0;
      for (var i = 0; i < response.leasingMargin.length; i++) {
        if(new Date(response.commandsMonth[i])>=new Date('2021-01-01')){
          total += response.leasingMargin[i] << 0;
        }
      }
      $('#statisticsListing span[name=ordersStatisticsTotalLeasingValue]').html(Math.round(total).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');


      var total = 0;
      for (var i = 0; i < response.sellingMargin.length; i++) {
        if(new Date(response.commandsMonth[i])>=new Date('2021-01-01')){
          total += response.sellingMargin[i] << 0;
        }
      }
      $('#statisticsListing span[name=ordersStatisticsTotalSellingValue]').html(Math.round(total).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');




      $("canvas#ordersMarginStatisticsChart").remove();
      $("div.ordersMarginStatisticsChart").append('<canvas id="ordersMarginStatisticsChart" class="animated fadeIn" width="100%"></canvas>');

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
          scales: {
            y: {
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " €";
                    }
                }
            }

          },
          plugins: {
            legend: {
                display: false,
            },
            title: {
                display: true,
                text: 'Valeur des commandes'
            },
            tooltip: {
              callbacks: {
                label: function(tooltipItems, data) {
                  return tooltipItems.dataset.label + ' : ' + tooltipItems.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' €';
                },
                footer: function(tooltipItems, data) {
                  let sum = 0;
                  let sumMargin = 0;
                  tooltipItems.forEach(function(tooltipItem) {
                    if(tooltipItem.dataset.label == 'Ventes - Marge' || tooltipItem.dataset.label == 'Leasing - Marge'){
                      sumMargin += tooltipItem.parsed.y;
                    }
                    sum += tooltipItem.parsed.y;
                  });
                  var mulstringText=['Marge totale des contrats : ' + sumMargin.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' €']
                  mulstringText.push('Valeur totale des contrats : ' + sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' €');
                  return mulstringText;
                }
              }
            },
          }
        }
      });
      myChart.update();

      $("canvas#contractStartStatisticsChart").remove();
      $("div.contractStartStatisticsChart").append('<canvas id="contractStartStatisticsChart" class="animated fadeIn" width="100%"></canvas>');

      var ctx = document.getElementById("contractStartStatisticsChart").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: response.contractStartMonth,
          datasets: [
            {
              label: "Vélos placés en leasing",
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              data: response.contractStartSum,
              borderWidth: 2,
              stack: 'Stack 0'
            },
            {
              label: "Vélos vendus",
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(252, 39, 80, 0.5)",
              borderWidth: 2,
              data: response.soldBikesSum,
              stack: 'Stack 0'
            }
          ],
        },
        options: {
          plugins: {
              legend: {
                  display: false,
              },
              title: {
                  display: true,
                  text: 'Sortie de stock'
              },
              tooltip: {
                callbacks: {
                  footer: function(tooltipItems, data) {
                    let sum = 0;
                    tooltipItems.forEach(function(tooltipItem) {
                      sum += tooltipItem.parsed.y;
                    });
                    return 'Total: ' + sum;

                  }
                }
              }
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


      var total = 0;
      for (var i = 0; i < response.deliveryNumberOfBike.length; i++) {
          total += response.deliveryNumberOfBike[i] << 0;
      }
      $('#statisticsListing span[name=deliveryChartTotal]').html(total);
      var total = 0;
      for (var i = 0; i < response.deliveryCost.length; i++) {
          total += response.deliveryCost[i] << 0;
      }

      $('#statisticsListing span[name=deliveryChartTotalValue]').html(Math.round(total).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');



      $("canvas#deliveryChartNumber").remove();
      $("div.deliveryChartNumber").append('<canvas id="deliveryChartNumber" class="animated fadeIn" width="100%"></canvas>');

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
          plugins: {
              legend: {
                  display: false,
              },
              title: {
                  display: true,
                  text: 'Nombre de vélos arrivant dans le stock'
              }
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

      $("canvas#currentStockBrands").remove();
      $("div.currentStockBrands").append('<canvas id="currentStockBrands" class="animated fadeIn" width="100%"></canvas>');


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
          plugins: {
              legend: {
                  display: false,
              },
              title: {
                  display: true,
                  text: 'Vélos dans le stock par marque'
              }
          }
        }
      });
      myChart.update();


      $("canvas#currentStockTypes").remove();
      $("div.currentStockTypes").append('<canvas id="currentStockTypes" class="animated fadeIn" width="100%"></canvas>');


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
          plugins: {
              legend: {
                  display: false,
              },
              title: {
                  display: true,
                  text: 'Vélos dans le stock par utilisation'
              }
          }
        }
      });
      myChart.update();


      $("canvas#deliveryChartValue").remove();
      $("div.deliveryChartValue").append('<canvas id="deliveryChartValue" class="animated fadeIn" width="100%"></canvas>');

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
              stack: 'Stack 0',
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              data: response.deliveryCost
            },
            {
              label: "Marge potentielle sur vente",
              borderColor: "rgba(44, 132, 109, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.1)",
              borderWidth: 2,
              stack: 'Stack 0',
              data: response.retailMargin
            }
          ],
        },
        options: {
          plugins: {
            legend: {
                display: false,
            },
            title: {
                display: true,
                text: 'Valeur des vélos arrivant dans le stock'
            },
            tooltip: {
              callbacks: {
                label: function(tooltipItems, data) {
                  return tooltipItems.dataset.label + ' : ' + tooltipItems.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' €';
                },
                footer: function(tooltipItems, data) {
                  let sum = 0;
                  tooltipItems.forEach(function(tooltipItem) {
                    sum += tooltipItem.parsed.y;
                  });
                  return 'Valeur marchande: ' + sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' €';
                }
              }
            }
          },
          scales: {
            y: {
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " €";
                    }
                }
            }

          },
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

      $("canvas#myChartCA").remove();
      $("div.myChartCA").append('<canvas id="myChartCA" class="animated fadeIn" width="100%"></canvas>');

      $('#statisticsListing span[name=totalCA]').html(Math.round(response.totalCA).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');
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
              stack: 'Stack 0',
              data: response.bikeLeasing
            },
            {
              label: "Vente vélos",
              fill: true,
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(252, 39, 80, 0.5)",
              stack: 'Stack 0',
              data: response.bikeSelling
            },
            {
              label: "Leasing bornes",
              fill: true,
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(176, 0, 0, 0.5)",
              stack: 'Stack 0',
              data: response.boxesLeasing
            },
            {
              label: "Leasing accessoires",
              fill: true,
              borderColor: "rgba(60, 179, 149, 0.5)",
              backgroundColor: "rgba(60, 179, 149, 0.5)",
              stack: 'Stack 0',
              data: response.accessoryLeasing
            },
            {
              label: "Ventes accessoires",
              fill: true,
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(176, 0, 0, 0.5)",
              stack: 'Stack 0',
              data: response.accessorySelling
            },
            {
              label: "Main d'oeuvre",
              fill: true,
              borderColor: "rgba(176, 0, 0, 0.5)",
              backgroundColor: "rgba(176, 0, 0, 0.5)",
              stack: 'Stack 0',
              data: response.maintenance
            }
          ],
          labels: response.arrayDatesCA,
        },
        options: {
          plugins: {
            tooltip: {
              callbacks: {
                label: function(tooltipItems, data) {
                  return tooltipItems.dataset.label + ' : ' + tooltipItems.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' €';
                },
                footer: function(tooltipItems, data) {
                  let sum = 0;
                  tooltipItems.forEach(function(tooltipItem) {
                    sum += tooltipItem.parsed.y;
                  });
                  return 'Total: ' + sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' €';
                }
              }
            }
          },
          responsive: true,
          scales: {
            y: {
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " €";
                    }
                }
            }

          },
        }
      });
      myChart.update();
    }
  })
});
