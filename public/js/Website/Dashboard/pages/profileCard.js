let HTML = document.querySelector("html");
let btnProfile = document.querySelector(".btn-profile");
let menu = document.querySelector(".bar");
let navmove = document.querySelector("nav");
let main = navmove.querySelector("main");
let li = main.querySelectorAll("ul li");
let profile = document.querySelector(".profile");
let closeProfile = document.querySelector(".close-profile");
let charts = document.querySelectorAll(".chart");
let ctx1 = document.getElementById("myChart-1");
let ctx2 = document.getElementById("myChart-2");
let ctx3 = document.getElementById("myChart-3");
let ctx4 = document.getElementById("myChart-4");

function safeCreateChart(element, config) {
  try {
    new Chart(element, config);
  } catch (err) {
    console.error('Failed to create chart for element', element, err);
  };
};

function makeChartLine(element) {
  const isArabic = HTML.getAttribute("lang") === "ar";
  const ctx1Att = element.getAttribute("data-percentage");
  const baseConfig = {
    type: 'line',
    data: {
      labels: cardProfileMonths,
      datasets: [{
        label: lableCanava,
        data: JSON.parse(ctx1Att),
        borderColor: 'rgb(236, 234, 74)',
        backgroundColor: 'rgba(122, 87, 206, 0.2)',
        tension: 0.3,
      }]
    },
    options: {
      scales: {}
    }
  };
  if (isArabic) {
    baseConfig.options.plugins = {
      legend: {
        labels: {
          font: {
            family: 'Cairo',
            size: 14
          },
        },
      },
    };
    baseConfig.options.scales = {
      x: {
        ticks: {
          font: {
            family: 'Cairo',
            size: 12,
          },
        },
      },
      y: {
        ticks: {
          font: {
            family: 'Cairo',
            size: 12,
          },
        },
      },
    };
  };
  new Chart(element, baseConfig);
};

if (ctx1 && ctx2 && ctx3 && ctx4) {
  makeChartCircle(ctx1);
  makeChartLine(ctx2);
  makeChartLine(ctx3);
  makeChartLine(ctx4);
};

function makeChartCircle(element) {
  if (!element) return;
  const isArabic = HTML.getAttribute("lang") === "ar";
  const raw = element.getAttribute("data-percentage");
  const value = Number(raw);
  const doughnutConfig = {
    type: 'doughnut',
    data: {
      datasets: [{
        label: 'My First Dataset',
        data: [value, Math.max(0, 12 - value)],
        hoverOffset: 4,
        backgroundColor: [
          'rgb(236, 234, 74)',
          'rgba(51, 51, 51, 1)',
        ],
        borderWidth: 0,
        cutout: '95%',
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

charts.forEach(char => {
  makeChartCircle(char);
});

if (window.innerWidth <= 1115) {
  menu.onchange = () => {
    main.classList.toggle("show-main");
  };
  li.forEach(button => {
    button.onclick = () => {
      menu.click();
    };
  });
};

if (btnProfile) {
  btnProfile.onclick = () => {
    profile.classList.add("show-profile");
    // menu.click();
  };
  closeProfile.onclick = () => {
    profile.classList.remove("show-profile");
  };
};
