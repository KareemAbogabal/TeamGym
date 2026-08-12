let html = document.querySelector("html");
let char1 = document.getElementById("chart-1");
let inputs = document.querySelectorAll('.search-input');
let mainTabelRowSearch = document.querySelectorAll('.main-tabel-row-search');

function makeChartCircle(element) {
  const isArabic = html.getAttribute("lang") === "ar";
  const ctx1Att = element.getAttribute("data-amount") ?? 0;
  const ctx2Att = element.getAttribute("data-paid") ?? 0;
  const doughnutConfig = {
    type: 'doughnut',
    data: {
      datasets: [{
        label: 'My First Dataset',
        data: [ctx2Att + ctx2Att, ctx1Att],
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

makeChartCircle(char1);


inputs.forEach((input, index) => {
  input.addEventListener('input', () => {
    const term = input.value.trim().toLowerCase();
    let mainTabelRowSearchChoose = mainTabelRowSearch[index].querySelectorAll(".row");
    mainTabelRowSearchChoose.forEach(row => {
      const text = Array.from(row.children).map(el => el.textContent.trim().toLowerCase()).join(' ');
      if (text.includes(term)) {
        row.style.display = 'flex';
      } else {
        row.style.display = 'none';
      };
    });
  });
});
