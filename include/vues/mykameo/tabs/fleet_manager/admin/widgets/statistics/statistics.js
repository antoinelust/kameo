$("#statisticsListing").on("show.bs.modal", function (event) {
  $.ajax({
    url: "api/statistics",
    type: "get",
    data: { action: "getStatistics" },
    success: function (response) {

      var totalLeasing = 0;
      var totalSelling = 0;
      var totalBoxes = 0;
      for (var i = 0; i < response.leasingOrders.length; i++) {
        if(new Date(response.commandsMonth[i])>=new Date('2021-01-01')){
          totalLeasing += response.leasingOrders[i] << 0;
          totalSelling += response.sellingOrders[i] << 0;
          totalBoxes += response.boxesOrders[i] << 0;
        }
      }
      $('#statisticsListing span[name=ordersStatisticsTotalLeasing]').html(totalLeasing);
      $('#statisticsListing span[name=ordersStatisticsTotalSelling]').html(totalSelling);
      $('#statisticsListing span[name=ordersStatisticsTotalBoxes]').html(totalBoxes);
      $('#statisticsListing span[name=ordersStatisticsTotal]').html(totalLeasing+totalSelling+totalBoxes);


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
              borderColor: "rgba(42, 157, 143, 1)",
              backgroundColor: "rgba(42, 157, 143, 0.5)",
              borderWidth: 2,
              stack: 'Stack 0',
              data: response.leasingOrders
            },
            {
              label: "Commandes - Ventes",
              borderColor: "rgba(231, 111, 81, 1)",
              backgroundColor: "rgba(231, 111, 81, 0.5)",
              borderWidth: 2,
              stack: 'Stack 0',
              data: response.sellingOrders
            },
            {
              label: "Commandes - Bornes",
              borderColor: "rgba(233, 196, 106, 1)",
              backgroundColor: "rgba(233, 196, 106, 0.5)",
              borderWidth: 2,
              stack: 'Stack 0',
              data: response.boxesOrders
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

      var totalBoxesMargin = 0;
      var totalLeasingMargin = 0;
      var totalSellingMargin = 0;
      var totalLeasingValue = 0;
      var totalSellingValue = 0;
      var totalBoxesValue = 0;
      var total=0;
      for (var i = 0; i < response.leasingMargin.length; i++) {
        if(new Date(response.commandsMonth[i])>=new Date('2021-01-01')){
          totalBoxesMargin += response.boxesMargin[i] << 0;
          totalLeasingMargin += response.leasingMargin[i] << 0;
          totalSellingMargin += response.sellingMargin[i] << 0;
          totalBoxesValue += (parseInt(response.boxesMargin[i])+parseInt(response.boxesCost[i])) <<0;
          totalLeasingValue += (response.leasingCost[i]+response.leasingMargin[i]) <<0;
          totalSellingValue += (response.sellingCost[i]+response.sellingMargin[i]) <<0;
        }
      }
      $('#statisticsListing span[name=ordersStatisticsTotalMargin]').html(Math.round(totalLeasingMargin+totalSellingMargin+totalBoxesValue).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');
      $('#statisticsListing span[name=ordersStatisticsTotalBoxesMargin]').html(Math.round(totalBoxesMargin).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € ('+Math.round(totalBoxesMargin/totalBoxes).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € par commande - '+(Math.round(totalBoxesMargin/(totalBoxesValue-totalBoxesMargin)*100))+'% ROI)');
      $('#statisticsListing span[name=ordersStatisticsTotalLeasingMargin]').html(Math.round(totalLeasingMargin).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € ('+Math.round(totalLeasingMargin/totalLeasing).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € par commande - '+(Math.round(totalLeasingMargin/(totalLeasingValue-totalLeasingMargin)*100))+'% ROI)');
      $('#statisticsListing span[name=ordersStatisticsTotalSellingMargin]').html(Math.round(totalSellingMargin).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € ('+Math.round(totalSellingMargin/totalSelling).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € par commande - '+(Math.round(totalSellingMargin/(totalSellingValue-totalSellingMargin)*100))+'% ROI)');
      $('#statisticsListing span[name=ordersStatisticsTotalBoxesValue]').html(Math.round(totalBoxesValue).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € ('+Math.round(totalBoxesValue/totalBoxes).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € par commande)');
      $('#statisticsListing span[name=ordersStatisticsTotalLeasingValue]').html(Math.round(totalLeasingValue).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € ('+Math.round(totalLeasingValue/totalLeasing).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € par commande)');
      $('#statisticsListing span[name=ordersStatisticsTotalSellingValue]').html(Math.round(totalSellingValue).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € ('+Math.round(totalSellingValue/totalSelling).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' € par commande)');
      $('#statisticsListing span[name=ordersStatisticsTotalContractValue]').html(Math.round(totalLeasingValue+totalSellingValue).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');


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
              borderColor: "rgba(231, 111, 81, 1)",
              backgroundColor: "rgba(231, 111, 81, 0.5)",
              borderWidth: 2,
              data: response.sellingCost
            },
            {
              label: "Ventes - Marge",
              stack: 'Stack 0',
              borderColor: "rgba(231, 111, 81, 1)",
              backgroundColor: "rgba(231, 111, 81, 0.1)",
              borderWidth: 2,
              data: response.sellingMargin
            },
            {
              label: "Leasing - Coûts",
              stack: 'Stack 1',
              borderColor: "rgba(42, 157, 143, 1)",
              backgroundColor: "rgba(42, 157, 143, 0.5)",
              borderWidth: 2,
              data: response.leasingCost
            },
            {
              label: "Leasing - Marge",
              stack: 'Stack 1',
              borderColor: "rgba(42, 157, 143, 1)",
              backgroundColor: "rgba(42, 157, 143, 0.1)",
              borderWidth: 2,
              data: response.leasingMargin
            },
            {
              label: "Bornes - Coûts",
              stack: 'Stack 2',
              borderColor: "rgba(233, 196, 106, 1)",
              backgroundColor: "rgba(233, 196, 106, 0.5)",
              borderWidth: 2,
              data: response.boxesCost
            },
            {
              label: "Bornes - Marge",
              stack: 'Stack 2',
              borderColor: "rgba(233, 196, 106, 1)",
              backgroundColor: "rgba(233, 196, 106, 0.1)",
              borderWidth: 2,
              data: response.boxesMargin
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
                    if(tooltipItem.dataset.label == 'Ventes - Marge' || tooltipItem.dataset.label == 'Leasing - Marge' || tooltipItem.dataset.label == 'Bornes - Marge'){
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
              borderColor: "rgba(42, 157, 143, 1)",
              backgroundColor: "rgba(42, 157, 143, 0.5)",
              data: response.contractStartSum,
              borderWidth: 2,
              stack: 'Stack 0'
            },
            {
              label: "Vélos vendus",
              borderColor: "rgba(231, 111, 81, 1)",
              backgroundColor: "rgba(231, 111, 81, 0.5)",
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
      for (var i = 0; i < response.soldBikesSum.length; i++) {
        if(new Date(response.contractStartMonth[i])>=new Date('2021-01-01')){
          total += response.soldBikesSum[i] << 0;
        }
      }
      for (var i = 0; i < response.contractStartSum.length; i++) {
        if(new Date(response.contractStartMonth[i])>=new Date('2021-01-01')){
          total += response.contractStartSum[i] << 0;
        }
      }
      $('#statisticsListing span[name=contractStartTotal]').html(total);


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
              borderColor: "rgba(42, 157, 143, 1)",
              backgroundColor: "rgba(42, 157, 143, 0.5)",
              borderWidth: 2,
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
            borderColor: "rgba(42, 157, 143, 1)",
            backgroundColor: "rgba(42, 157, 143, 0.5)",
            borderWidth: 2
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

      var total = 0;
      for (var i = 0; i < response.stockByBrandData.length; i++) {
        total += response.stockByBrandData[i] << 0;
      }
      $('#statisticsListing span[name=stockTotal]').html(total);



      $("canvas#currentStockTypes").remove();
      $("div.currentStockTypes").append('<canvas id="currentStockTypes" class="animated fadeIn" width="100%"></canvas>');


      var ctx = document.getElementById("currentStockTypes").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: 'bar',
        data:{
          labels: response.stockByUtilisationLabel,
          datasets: [{
            data: response.stockByUtilisationData,
            borderColor: "rgba(42, 157, 143, 1)",
            backgroundColor: "rgba(42, 157, 143, 0.5)",
            borderWidth: 2
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
              stack: 'Stack 0',
              borderColor: "rgba(42, 157, 143, 1)",
              backgroundColor: "rgba(42, 157, 143, 0.5)",
              borderWidth: 2,
              data: response.deliveryCost
            },
            {
              label: "Marge potentielle sur vente",
              borderColor: "rgba(42, 157, 143, 1)",
              backgroundColor: "rgba(42, 157, 143, 0.1)",
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

      var totalBikeLeasing=0;
      var totalBikeSelling=0;
      var totalBoxesLeasing=0;
      var totalAccessoryLeasing=0;
      var totalAccessorySelling=0;
      var totalMaintenance=0;
      var total=0;

      for (var i = 0; i < response.bikeLeasing.length; i++) {
        if(new Date(response.arrayDatesCA[i])>=new Date('2021-01-01')){
          totalBikeLeasing += response.bikeLeasing[i] << 0;
          totalBikeSelling += response.bikeSelling[i] << 0;
          totalBoxesLeasing += response.boxesLeasing[i] << 0;
          totalAccessoryLeasing += response.accessoryLeasing[i] << 0;
          totalAccessorySelling += response.accessorySelling[i] << 0;
          totalMaintenance += response.maintenance[i] << 0;
        }
      }
      total = totalBikeLeasing + totalBikeSelling + totalBoxesLeasing + totalAccessoryLeasing + totalAccessorySelling + totalMaintenance;
      $('#statisticsListing span[name=totalCALeasingBikes]').html(Math.round(totalBikeLeasing).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');
      $('#statisticsListing span[name=totalCASellingBikes]').html(Math.round(totalBikeSelling).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');
      $('#statisticsListing span[name=totalCALeasingBoxes]').html(Math.round(totalBoxesLeasing).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');
      $('#statisticsListing span[name=totalCALeasingAccessories]').html(Math.round(totalAccessoryLeasing).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');
      $('#statisticsListing span[name=totalCASellingAccessories]').html(Math.round(totalAccessorySelling).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');
      $('#statisticsListing span[name=totalCAMaintenance]').html(Math.round(totalMaintenance).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');
      $('#statisticsListing span[name=totalCA]').html(Math.round(total).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' €');

      var ctx = document.getElementById("myChartCA").getContext("2d");
      ctx.height = 500;
      var myChart = new Chart(ctx, {
        type: "bar",
        data: {
          datasets: [
            {
              label: "Leasing vélos",
              fill: true,
              backgroundColor: "rgba(42, 157, 143, 0.5)",
              stack: 'Stack 0',
              data: response.bikeLeasing
            },
            {
              label: "Vente vélos",
              fill: true,
              backgroundColor: "rgba(231, 111, 81, 0.5)",
              stack: 'Stack 0',
              data: response.bikeSelling
            },
            {
              label: "Leasing bornes",
              fill: true,
              backgroundColor: "rgba(233, 196, 106, 0.5)",
              stack: 'Stack 0',
              data: response.boxesLeasing
            },
            {
              label: "Leasing accessoires",
              fill: true,
              backgroundColor: "rgba(38, 70, 83, 0.5)",
              stack: 'Stack 0',
              data: response.accessoryLeasing
            },
            {
              label: "Ventes accessoires",
              fill: true,
              backgroundColor: "rgba(244, 162, 97, 0.5)",
              stack: 'Stack 0',
              data: response.accessorySelling
            },
            {
              label: "Main d'oeuvre",
              fill: true,
              backgroundColor: "rgba(38, 131, 255, 0.5)",
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
