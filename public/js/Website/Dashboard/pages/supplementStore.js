let html = document.querySelector("html");
let products = document.querySelectorAll(".product");
let showDetailsProductBtn = document.querySelectorAll(".show-details-product");
let char = document.querySelectorAll(".chart");

showDetailsProductBtn.forEach((button, index) => {
  button.onclick = () => {
    const el = products[index];
    if (el.classList.contains("show-product")) {
      el.classList.remove("show-product");
      void el.offsetWidth;
      el.classList.add("hidden-product");
    } else {
      el.classList.remove("hidden-product");
      void el.offsetWidth;
      el.classList.add("show-product");
    };
  };
});

function makeChartCircle(element) {
  const isArabic = html.getAttribute("lang") === "ar";
  const ctx1Att = element.getAttribute("data-amount");
  const ctx2Att = element.getAttribute("data-paid");
  const doughnutConfig = {
    type: 'doughnut',
    data: {
      datasets: [{
        label: 'My First Dataset',
        data: [ctx1Att, ctx1Att - ctx2Att],
        hoverOffset: 4,
        backgroundColor: [
          'rgba(126, 255, 100, 1)',
          'rgba(255, 97, 97, 1)',
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

char.forEach(chart => {
  makeChartCircle(chart);
});
