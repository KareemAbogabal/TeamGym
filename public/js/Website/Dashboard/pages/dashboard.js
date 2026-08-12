let html = document.querySelector("html");
let char1 = document.getElementById("chart-1");
let char2 = document.getElementById("chart-2");
let char3 = document.getElementById("chart-3");
let char4 = document.getElementById("chart-4");
let char5 = document.getElementById("chart-5");
let char6 = document.getElementById("chart-6");
let light = localStorage.getItem("state-mode-team-gym");

function normalizeNumberString(str) {
  if (str == null) return str;
  if (typeof str !== 'string') str = String(str);
  const arabicDigits = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
  for (let i = 0; i < 10; i++) {
    str = str.replace(new RegExp(arabicDigits[i], 'g'), String(i));
  };
  str = str.replace(/،/g, ',');
  return str.trim();
};

function safeParseJson(input) {
  if (input == null) return null;
  if (typeof input !== 'string') input = String(input);
  let s = normalizeNumberString(input);
  if (s.length > 1 && s[0] === "'" && s[s.length - 1] === "'") {
    s = '"' + s.slice(1, -1) + '"';
  };
  try {
    return JSON.parse(s);
  } catch (e) {
    const n = Number(s);
    if (!isNaN(n)) return n;
    if (s.indexOf(',') !== -1) {
      return s.split(',').map(part => {
        const nn = Number(normalizeNumberString(part).replace(/[^\d\.\-]/g, ''));
        return isNaN(nn) ? 0 : nn;
      });
    };
    return s;
  };
};

if (typeof months === 'undefined') {
  console.warn('charts: "months" variable is not defined — falling back to empty labels');
  months = [];
};

if (typeof Chart === 'undefined') {
  console.error('Chart.js is not loaded. Make sure chart.min.js is included before this script.');
} else {
  function safeCreateChart(element, config) {
    try {
      new Chart(element, config);
    } catch (err) {
      console.error('Failed to create chart for element', element, err);
    };
  };
  function makeChartCircle(element) {
    if (!element) return;
    const isArabic = html.getAttribute("lang") === "ar";
    const raw = element.getAttribute("data-percentage");
    const parsed = safeParseJson(raw);
    const value = Number(parsed) || 0;
    const doughnutConfig = {
      type: 'doughnut',
      data: {
        datasets: [{
          label: 'My First Dataset',
          data: [value, Math.max(0, 100 - value)],
          hoverOffset: 4,
          backgroundColor: [
            'rgb(236, 234, 74)',
            'rgba(37, 37, 37, 1)',
          ],
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
    safeCreateChart(element, doughnutConfig);
  };
  function makeChartPolarArea(element) {
    if (!element) return;
    const isArabic = html.getAttribute("lang") === "ar";
    const raw = element.getAttribute("data-percentage");
    const parsed = safeParseJson(raw);
    const dataArray = Array.isArray(parsed) ? parsed : [0,0,0,0,0];
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
          data: dataArray,
          backgroundColor: [
            'rgba(255, 120, 120, 1)',
            'rgba(164, 255, 111, 1)',
            'rgba(255, 253, 119, 1)',
            'rgba(99, 99, 99, 1)',
            'rgba(145, 86, 255, 1)',
          ],
          borderWidth: 0,
        }]
      },
      options: {
        responsive: true,
        scales: {
          r: {
            grid: {
              color: light == "light" ? "rgba(0, 0, 0, 1)"  : 'rgba(236, 236, 236, 1)',
            },
            angleLines: {
              color: light == "light" ? "rgba(0, 0, 0, 1)"  : 'rgba(236, 236, 236, 1)',
            },
            ticks: {
              color: light == "light" ? "rgba(0, 0, 0, 1)"  : 'rgba(236, 236, 236, 1)',
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
    safeCreateChart(element, doughnutConfig);
  };
  function makeChartBar(element) {
    if (!element) return;
    const isArabic = html.getAttribute("lang") === "ar";
    const raw1 = element.getAttribute("data-percentage-water");
    const raw2 = element.getAttribute("data-percentage-protein");
    const raw3 = element.getAttribute("data-percentage-fats");
    const dataWater = Array.isArray(safeParseJson(raw1)) ? safeParseJson(raw1) : [];
    const dataProtein = Array.isArray(safeParseJson(raw2)) ? safeParseJson(raw2) : [];
    const dataFats = Array.isArray(safeParseJson(raw3)) ? safeParseJson(raw3) : [];
    const stackedBarConfig = {
      type: 'bar',
      data: {
        labels: months,
        datasets: [
          {
            label: isArabic == "en" ? 'Water' : "المياه",
            data: dataWater,
            backgroundColor: 'rgba(66, 165, 245, 1)',
          },
          {
            label: isArabic == "en" ? 'Protein' : "البروتين",
            data: dataProtein,
            backgroundColor: 'rgba(102, 187, 106, 1)',
          },
          {
            label: isArabic == "en" ? 'Fats' : "الدهون",
            data: dataFats,
            backgroundColor: 'rgba(255, 167, 38, 1)',
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          x: {
            stacked: true,
            ticks: {
              font: {
                family: isArabic ? 'Cairo' : 'Arial',
                size: 12
              }
            }
          },
          y: {
            stacked: true,
            ticks: {
              font: {
                family: isArabic ? 'Cairo' : 'Arial',
                size: 12
              }
            }
          },
        },
      },
    };
    if (isArabic) {
      stackedBarConfig.options.plugins = {
        labels: {
          font: {
            family: 'Cairo',
            size: 14,
          },
        },
      };
    }
    safeCreateChart(element, stackedBarConfig);
  };

  function makeChartPoint(element) {
    if (!element) return;
    const isArabic = html.getAttribute("lang") === "ar";
    const raw = element.getAttribute("data-percentage");
    const parsed = safeParseJson(raw);
    const dataArray = Array.isArray(parsed) ? parsed : [];
    const lineChartConfig = {
      type: 'line',
      data: {
        labels: months,
        datasets: [{
          label: isArabic == "en" ? 'Body condition' : "حالة الجسم",
          data: dataArray,
          borderColor: 'rgba(255, 207, 76, 1)',
          pointBackgroundColor: '#fffc688a',
          tension: 0.4,
          pointBorderWidth: 1,
          pointRadius: 7,
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
      lineChartConfig.options.plugins = {
        labels: {
          font: {
            family: 'Cairo',
            size: 14,
          },
        },
      };
    }
    safeCreateChart(element, lineChartConfig);
  };
  if (char1) makeChartCircle(char1);
  if (char2) makeChartCircle(char2);
  if (char3) makeChartCircle(char3);
  if (char4) makeChartPolarArea(char4);
  if (char5) makeChartBar(char5);
  if (char6) makeChartPoint(char6);
};
