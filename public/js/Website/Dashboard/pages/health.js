let html = document.querySelector("html");
let light = "dark";
let char1 = document.getElementById("chart-1");
let char2 = document.getElementById("chart-2");
let char3 = document.getElementById("chart-3");
let char4 = document.getElementById("chart-4");
let char5 = document.getElementById("chart-5");

function makeChartCircle(element) {
  const isArabic = html.getAttribute("lang") === "ar";
  const ctx1Att = element.getAttribute("data-percentage");
  const doughnutConfig = {
    type: 'doughnut',
    data: {
      datasets: [{
        label: 'My First Dataset',
        data: [ctx1Att, 100 - ctx1Att],
        hoverOffset: 4,
        backgroundColor: [
          'rgba(126, 255, 100, 1)',
          'rgba(255, 97, 97, 1)',
        ],
        borderRadius: 6,
        borderWidth: 0,
        cutout: '80%',
      }]
    },
    options: {}
  };
  if (isArabic) {
    doughnutConfig.options.plugins = {
      legend: {
        labels: {
          font: {
            family: 'Cairo',
            size: 14
          },
        },
      },
    };
  };
  new Chart(element, doughnutConfig);
};

function makeChartPoint(element) {
  const isArabic = html.getAttribute("lang") === "ar";
  const ctx1Att = element.getAttribute("data-muscles");
  const lineChartConfig = {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'Body condition',
        fill: true,
        backgroundColor: 'rgba(255, 207, 76, 0.2)',
        data: JSON.parse(ctx1Att),
        borderColor: 'rgba(255, 207, 76, 1)',
        borderCapStyle: 'round',
        borderJoinStyle: 'round',
        pointBackgroundColor: '#fffc688a',
        tension: 0.4,
        pointBorderWidth: 1,
        pointRadius: 5,
        pointHoverRadius: 14,
        pointStyle: 'circle',
      }]
    },
    options: {
      scales: {
        x: {
          grid: {
            color: light == "light" ? "rgba(0, 0, 0, 1)" : 'rgba(236, 236, 236, 0.33)',
          },
          ticks: {
            font: {
              family: isArabic ? 'Cairo' : 'Arial',
              size: 12
            }
          }
        },
        y: {
          grid: {
            color: light == "light" ? "rgba(0, 0, 0, 1)" : 'rgba(236, 236, 236, 0.33)',
          },
          ticks: {
            font: {
              family: isArabic ? 'Cairo' : 'Arial',
              size: 12
            }
          }
        }
      }
    }
  };
  if (isArabic) {
    lineChartConfig.options.plugins.legend = {
      labels: {
        font: {
          family: 'Cairo',
          size: 14,
        },
      },
    };
  }
  new Chart(element, lineChartConfig);
};

function makeChartBar(element) {
  const isArabic = html.getAttribute("lang") === "ar";
  const ctx1Att = element.getAttribute("data-fat");
  const ctx2Att = element.getAttribute("data-water");
  const stackedBarConfig = {
    type: 'bar',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Water',
          data: JSON.parse(ctx1Att),
          backgroundColor: 'rgba(66, 165, 245, 1)',
          borderRadius: 6,
        },
        {
          label: 'Fats',
          data: JSON.parse(ctx2Att),
          backgroundColor: 'rgba(255, 167, 38, 1)',
          borderRadius: 6,
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        x: {
          stacked: true,
        },
        y: {
          stacked: true,
        },
      },
    },
  };
  if (isArabic) {
    stackedBarConfig.options.plugins.legend = {
      labels: {
        font: {
          family: 'Cairo',
          size: 14,
        },
      },
    };
  }
  new Chart(element, stackedBarConfig);
};


makeChartCircle(char1);
makeChartPoint(char2);
makeChartCircle(char3);
makeChartCircle(char4);
makeChartBar(char5);
