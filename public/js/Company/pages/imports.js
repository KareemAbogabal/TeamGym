let html = document.querySelector("html");
let chartLine = document.querySelectorAll(".chart-line");
let input = document.querySelector('.search-input');
let rows = document.querySelectorAll(".row");
let nebulaInputs = document.querySelectorAll(".nebula-input input");
let nebulaLabel = document.querySelectorAll(".user-label");
let monthChar = document.querySelectorAll(".months");
let productAnalytics = document.querySelectorAll(".product-analytics");
let charCircle1 = document.querySelector(".chart-circle");
let char1 = document.getElementById("chart-1");
let radioChoose = document.querySelectorAll(".radio-choose");
let inpTypeProduct = document.querySelector(".type-product");
let inputImg = document.getElementById("img");
let labelUpload = document.querySelector(".label-upload");

inputImg.onchange = (e) => {
  const name = e.target.files[0].name;
  let span = labelUpload.querySelector("span");
  span.innerHTML = name;
};

radioChoose.forEach((button) => {
  button.addEventListener('click', () => {
    const span = button.querySelector("span");
    const name = span.getAttribute("data-name");
    inpTypeProduct.value = name;
  });
});

months.forEach((month, index) => {
  monthChar.forEach((item, index) => {
    item.innerHTML += `<p>${month}</p>`;
  });
});

productAnalytics.forEach(product => {
  product.addEventListener('click', () => {
    const isActive = product.classList.contains('show-det');
    productAnalytics.forEach(item => item.classList.remove('show-det'));
    if (!isActive) {
      requestAnimationFrame(() => {
        product.classList.add('show-det');
      });
    };
  });
});

function makeCharLine(char) {
  const ctx = char.getContext("2d");
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
    ]
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
        x: { display: false },
        y: { display: false }
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

chartLine.forEach((item) => {
  makeCharLine(item);
});


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

nebulaInputs.forEach((inputEl, index) => {
  inputEl.addEventListener('click', () => {
    if (nebulaLabel[index]) nebulaLabel[index].classList.add("show-input");
  });
  inputEl.addEventListener('mouseout', () => {
    if (inputEl.value == "" && nebulaLabel[index]) nebulaLabel[index].classList.remove("show-input");
  });
});

function makeChartCircle(element) {
  const isArabic = html.getAttribute("lang") === "ar";
  const ctx1Att = JSON.parse(element.getAttribute("data-revenues"));
  const ctx2Att = JSON.parse(element.getAttribute("data-input"));
  const doughnutConfig = {
    type: 'doughnut',
    data: {
      datasets: [{
        label: 'My First Dataset',
        data: [ctx1Att, ctx2Att],
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

const Utils = {
  CHART_BORDER_COLORS: {
    blue: 'rgba(54, 163, 235, 1)',
  },
  CHART_COLORS: {
    blue: 'rgba(54, 163, 235, 0.8)',
  }
};

function makeChartBar(element) {
  const isArabic = document.documentElement.getAttribute("lang") === "ar";
  const ctx1Att = element.getAttribute("data-revenues");
  const stackedBarConfig = {
    type: 'bar',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Revenues',
          data: JSON.parse(ctx1Att),
          borderColor: Utils.CHART_BORDER_COLORS.blue,
          backgroundColor: Utils.CHART_COLORS.blue,
          borderWidth: 2,
          borderRadius: 50,
          borderSkipped: false,
        }
      ]
    },
  };
  if (isArabic) {
    stackedBarConfig.options.plugins.legend.labels = {
      font: { family: 'Cairo', size: 20 }
    };
  };
  new Chart(element, stackedBarConfig);
};

makeChartCircle(charCircle1);
makeChartBar(char1);

(function () {
  let data = JSON.parse(sessionStorage.getItem("rejectedPage")) || [];
  if (!data.includes("imports")) {
    data.push("imports");
    sessionStorage.setItem("rejectedPage", JSON.stringify(data));
  };
})();
