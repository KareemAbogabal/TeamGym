let chartLine = document.querySelectorAll(".chart-line");
let html = document.querySelector("html");
let charCircle1 = document.querySelector(".chart-circle");
let chartBar = document.querySelector(".chart-bar");
let chartSankey = document.querySelector(".chart-sankey");

function makeCharLine(char) {
  const ctx = char.getContext("2d");
  const isArabic = document.documentElement.getAttribute("lang") === "ar";
  let points = JSON.parse(char.dataset.points || "[]");
  const grad = ctx.createLinearGradient(0, 0, ctx.canvas.width, 0);
  grad.addColorStop(0, '#5ec3ff');
  grad.addColorStop(0.6, '#2b9bff');
  grad.addColorStop(1, '#1e73d8');
  const fogGrad = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
  fogGrad.addColorStop(0, 'rgba(46,130,255,0.16)');
  fogGrad.addColorStop(0.45, 'rgba(46,130,255,0.08)');
  fogGrad.addColorStop(1, 'rgba(46,130,255,0)');
  const shadowPlugin = {
    id: 'lineShadow',
    beforeDatasetDraw(chart, args, options) {
      if (args.index !== options.mainDatasetIndex) return;
      const ctx = chart.ctx;
      ctx.save();
      ctx.shadowColor = options.color || 'rgba(30,144,255,0.25)';
      ctx.shadowBlur = options.blur || 14;
      ctx.lineJoin = 'round';
      ctx.lineCap = 'round';
    },
    afterDatasetDraw(chart, args, options) {
      if (args.index !== options.mainDatasetIndex) return;
      chart.ctx.restore();
    }
  };
  Chart.register(shadowPlugin);
  const data = {
    labels: points.map((_, i) => i + 1),
    datasets: [
      {
        data: points,
        tension: 0.4,
        pointRadius: 0,
        fill: true,
        backgroundColor: fogGrad,
      },
      {
        data: points,
        borderColor: grad,
        borderWidth: 3,
        tension: 0.4,
        pointRadius: 0,
        fill: false
      }
    ],
  };
  new Chart(ctx, {
    type: 'line',
    data,
    options: {
      responsive: false,
      plugins: {
        legend: { display: false },
        lineShadow: { color: 'rgba(46,130,255,0.20)', blur: 14, mainDatasetIndex: 1 }
      },
      scales: {
        x: {
          display: false,
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
          display: false,
          ticks: {
            color: 'rgba(236, 236, 236, 1)',
            backdropColor: 'transparent',
            backdropPadding: 0,
            font: {
              family: isArabic ? 'Cairo' : 'Arial',
              size: 12
            },
          },
        }
      },
      elements: {
        line: {
          borderCapStyle: 'round',
          borderJoinStyle: 'round'
        }
      },
      interaction: { intersect: false }
    }
  });
};

function makeChartCircle(element) {
  const isArabic = html.getAttribute("lang") === "ar";
  const ctx1Att = JSON.parse(element.getAttribute("data-revenues"));
  const ctx2Att = JSON.parse(element.getAttribute("data-expenses"));
  const doughnutConfig = {
    type: 'doughnut',
    data: {
      datasets: [{
        label: 'My First Dataset',
        data: [ctx1Att, 100],
        hoverOffset: 4,
        backgroundColor: [
          'rgba(255, 245, 100, 1)',
          'rgba(136, 136, 136, 1)',
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
  new Chart(element, doughnutConfig);
};

function makeChartBar(element) {
  const isArabic = html.getAttribute("lang") === "ar";
  const expenses = JSON.parse(element.getAttribute("data-expenses") || "[]");
  const revenues = JSON.parse(element.getAttribute("data-revenues") || "[]");
  const fontFamily = isArabic ? 'Cairo, Arial, sans-serif' : 'Arial, Helvetica, sans-serif';
  const config = {
    type: 'line',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Expenses',
          data: expenses,
          borderColor: 'rgba(255, 96, 96, 1)',
          backgroundColor: 'rgba(255, 96, 96, 0.2)',
          pointBackgroundColor: 'rgba(255, 96, 96, 1)',
        },
        {
          label: 'Revenues',
          data: revenues,
          borderColor: 'rgba(55, 51, 255, 1)',
          backgroundColor: 'rgba(55, 51, 255, 0.2)',
          pointBackgroundColor: 'rgba(55, 51, 255, 1)',
        },
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          labels: {
            font: {
              family: fontFamily,
              size: 14,
            },
          },
        },
      },
      scales: {
        x: {
          title: {
            display: true,
            font: { family: fontFamily, size: 12 }
          },
          ticks: {
            font: { family: fontFamily, size: 12 },
            color: 'rgba(236, 236, 236, 1)'
          }
        },
        y: {
          title: {
            display: true,
            font: { family: fontFamily, size: 12 }
          },
          ticks: {
            font: { family: fontFamily, size: 12 },
            color: 'rgba(236, 236, 236, 1)'
          }
        },
      },
    },
  };
  Chart.defaults.font.family = fontFamily;
  new Chart(element, config);
};


function makeSankey(element) {
  let raw = element.getAttribute('data-links');
  let links = [];
  try {
    links = JSON.parse(raw);
  } catch (e) {
    console.error('Invalid links JSON', e, raw);
  };
  const data = {
    datasets: [{
      label: 'Sankey demo',
      data: links.map(l => ({
        from: String(l.from),
        to: String(l.to),
        flow: Number(l.flow) || 0
      })),
      colorFrom: (c) => '#4caf50',
      colorTo: (c) => '#2196f3',
      colorMode: 'gradient',
    }]
  };
  new Chart(element, {
    type: 'sankey',
    data: data,
    options: {
      responsive: true,
      animation: {
        duration: 10000,
        easing: 'easeOutQuart',
      },
      transitions: {
        show: {
          animations: {
            x: { from: -50 },
            y: { from: 0 },
            alpha: { from: 0 }
          }
        },
        hide: {
          animations: {
            alpha: { to: 0 }
          }
        }
      }
    }
  });
};

chartLine.forEach((item) => {
  makeCharLine(item);
});

makeChartCircle(charCircle1);
makeChartBar(chartBar);
makeSankey(chartSankey);
