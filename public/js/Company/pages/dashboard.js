let html = document.querySelector("html");
let char1 = document.getElementById("chart-1");
let char2 = document.getElementById("chart-2");
let input = document.querySelector('.search-payments');
let rows  = document.querySelectorAll('.history .table .row');

const Utils = {
  CHART_BORDER_COLORS: {
    red: 'rgba(255, 99, 133, 1)',
    blue: 'rgba(54, 163, 235, 1)',
  },
  CHART_COLORS: {
    red: 'rgba(255, 99, 133, 0.8)',
    blue: 'rgba(54, 163, 235, 0.8)',
  }
};

function makeChartBar(element) {
  if (!element || typeof Chart === 'undefined') {
    console.warn('Chart.js is not available; skipping chart render.');
    return;
  }
  const isArabic = document.documentElement.getAttribute("lang") === "ar";
  const ctx1Att = element.getAttribute("data-revenues");
  const ctx2Att = element.getAttribute("data-expenses");
  const stackedBarConfig = {
    type: 'bar',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Expenses',
          data: JSON.parse(ctx2Att),
          borderColor: Utils.CHART_BORDER_COLORS.red,
          backgroundColor: Utils.CHART_COLORS.red,
          borderWidth: 2,
          borderRadius: 10,
          borderSkipped: false,
        },
        {
          label: 'Revenues',
          data: JSON.parse(ctx1Att),
          borderColor: Utils.CHART_BORDER_COLORS.blue,
          backgroundColor: Utils.CHART_COLORS.blue,
          borderWidth: 2,
          borderRadius: 10,
          borderSkipped: false,
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
        }
      },
      scales: {
        x: {
          stacked: false,
          categoryPercentage: 0.8,
          barPercentage: 0.9,
          grid: {
            display: false,
            drawBorder: false,
          },
          ticks: {
            color: 'rgba(236, 236, 236, 1)',
            backdropColor: 'transparent',
            backdropPadding: 0,
            font: {
              family: isArabic ? 'Cairo' : 'Arial',
              size: 12
            },
          },
        },
        y: {
          stacked: false,
          title: {
            display: true,
          },
          grid: {
            display: false,
            drawBorder: false,
          },
          ticks: {
            color: 'rgba(236, 236, 236, 1)',
            backdropColor: 'transparent',
            backdropPadding: 0,
            font: {
              family: isArabic ? 'Cairo' : 'Arial',
              size: 12
            },
          },
        },
      },
    },
  };
  if (isArabic) {
    stackedBarConfig.options.plugins.legend.labels = {
      font: { family: 'Cairo', size: 14 }
    };
  };
  new Chart(element, stackedBarConfig);
};

function makeChartPolarArea(element) {
  if (!element || typeof Chart === 'undefined') {
    console.warn('Chart.js is not available; skipping polar-area chart.');
    return;
  }
  const isArabic = html.getAttribute("lang") === "ar";
  const ctx1Att = element.getAttribute("data-percentage");
  const doughnutConfig = {
    type: 'polarArea',
    labels: [
      'Red',
      'Green',
      'Yellow',
      'Grey',
      'Blue'
    ],
    data: {
      datasets: [{
        label: 'My First Dataset',
        data: JSON.parse(ctx1Att),
        backgroundColor: [
          'rgba(255, 120, 120, 1)',
          'rgba(224, 255, 111, 1)',
          'rgba(145, 86, 255, 1)',
          'rgba(99, 99, 99, 1)',
          'rgba(255, 253, 119, 1)',
        ],
        borderWidth: 0,
      }]
    },
    options: {
      responsive: true,
      scales: {
        r: {
          grid: {
            color: 'rgba(236, 236, 236, 1)',
          },
          angleLines: {
            color: 'rgba(236, 236, 236, 1)',
          },
          ticks: {
            color: 'rgba(236, 236, 236, 1)',
            backdropColor: 'transparent',
            backdropPadding: 0,
            font: {
              family: isArabic ? 'Cairo' : 'Arial',
              size: 12
            },
          },
        },
      },
    },
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

if (typeof Chart !== 'undefined') {
  makeChartBar(char1);
  makeChartPolarArea(char2);
}

if (input) {
  input.addEventListener('input', () => {
  const term = input.value.trim().toLowerCase();
    rows.forEach(row => {
      const text = Array.from(row.children).map(el => el.textContent.trim().toLowerCase()).join(' ');
      if (text.includes(term)) {
        row.style.display = 'flex';
      } else {
        row.style.display = 'none';
      };
    });
  });
}
