let chartLine = document.querySelectorAll(".chart-line");
let editProfile = document.querySelectorAll(".edit");
let btnRequests = document.querySelectorAll(".btn-requests");
let mainCardsEdit = document.querySelector('.main-card[data-state="edit"]');
let mainCardsSubscriptionRequests = document.querySelector('.main-card[data-state="subscription-requests"]');
let mainCardsDocumentation = document.querySelectorAll('.card .body-card .img-card');
let inputs = document.querySelectorAll('.search-input');
let mainTabelRowSearch = document.querySelectorAll('.main-tabel-row-search');
let html = document.querySelector("html");
let char1 = document.getElementById("chart-1");
let buttons = null;

function contentCard(type, e) {
  const btn = e.target.closest(`.${type}`);
  if (!btn) return;
  const row = btn.closest('.row');
  if (!row) return;

  let img = row.getAttribute("data-img");
  let fullName = row.getAttribute("data-name");
  let fname = row.getAttribute("data-fname");
  let lname = row.getAttribute("data-lname");
  let documentationState = row.getAttribute("data-documentation");
  let codeRequestPayment = row.getAttribute("data-code-request-payment");
  let idRequest = row.getAttribute("data-id-request");
  let codeOrder = row.getAttribute("data-code-order");
  let codeClient = row.getAttribute("data-code-client");
  let codeSupplements = row.getAttribute("data-code-supplements");
  let codeSystem = row.getAttribute("data-code-system");
  let orderName = row.getAttribute("data-order-name");
  let amount = row.getAttribute("data-amount");
  let payday = row.getAttribute("data-payday");
  let phone = row.getAttribute("data-phone");
  let paid = row.getAttribute("data-paid");
  let stateButtonsControl = row.getAttribute("data-state-buttons-control");

  let documentation = `
    <svg viewBox="0 0 256 256" width="20" height="20">
      <defs>
        <path id="tooth" d="M 0,-110 C 5,-106 10,-98 14,-84 L 6,-62 C 3,-56 0,-54 0,-54 C 0,-54 -3,-56 -6,-62 L -14,-84 C -10,-98 -5,-106 0,-110 Z" />
        <linearGradient id="yellow-white-yellow-45" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#f6d93b"/>
          <stop offset="50%" stop-color="#ffffff"/>
          <stop offset="100%" stop-color="#f6d93b"/>
        </linearGradient>
      </defs>
      <g transform="translate(128 128)">
        <g fill="url(#yellow-white-yellow-45)" stroke-linejoin="round" transform="scale(1.02)">
          <use href="#tooth" transform="rotate(0)"/>
          <use href="#tooth" transform="rotate(10)"/>
          <use href="#tooth" transform="rotate(20)"/>
          <use href="#tooth" transform="rotate(30)"/>
          <use href="#tooth" transform="rotate(40)"/>
          <use href="#tooth" transform="rotate(50)"/>
          <use href="#tooth" transform="rotate(60)"/>
          <use href="#tooth" transform="rotate(70)"/>
          <use href="#tooth" transform="rotate(80)"/>
          <use href="#tooth" transform="rotate(90)"/>
          <use href="#tooth" transform="rotate(100)"/>
          <use href="#tooth" transform="rotate(110)"/>
          <use href="#tooth" transform="rotate(120)"/>
          <use href="#tooth" transform="rotate(130)"/>
          <use href="#tooth" transform="rotate(140)"/>
          <use href="#tooth" transform="rotate(150)"/>
          <use href="#tooth" transform="rotate(160)"/>
          <use href="#tooth" transform="rotate(170)"/>
          <use href="#tooth" transform="rotate(180)"/>
          <use href="#tooth" transform="rotate(190)"/>
          <use href="#tooth" transform="rotate(200)"/>
          <use href="#tooth" transform="rotate(210)"/>
          <use href="#tooth" transform="rotate(220)"/>
          <use href="#tooth" transform="rotate(230)"/>
          <use href="#tooth" transform="rotate(240)"/>
          <use href="#tooth" transform="rotate(250)"/>
          <use href="#tooth" transform="rotate(260)"/>
          <use href="#tooth" transform="rotate(270)"/>
          <use href="#tooth" transform="rotate(280)"/>
          <use href="#tooth" transform="rotate(290)"/>
          <use href="#tooth" transform="rotate(300)"/>
          <use href="#tooth" transform="rotate(310)"/>
          <use href="#tooth" transform="rotate(320)"/>
          <use href="#tooth" transform="rotate(330)"/>
          <use href="#tooth" transform="rotate(340)"/>
          <use href="#tooth" transform="rotate(350)"/>
          <circle r="92" fill="url(#yellow-white-yellow-45)" stroke-width="1.4"/>
        </g>
        <path d="M -34 0 L -4 40 L 56 -20" fill="none" transform="translate(-15, -6)" stroke="#000" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
      </g>
    </svg>
  `;

  if (documentationState == "true") {
    mainCardsDocumentation.forEach(item => { item.innerHTML += documentation; });
  };

  if (type == "edit") {
    let imgCard = mainCardsEdit.querySelector(".img-card img");
    let fullNameCard = mainCardsEdit.querySelector(".full-name-card");
    let fnameCard = mainCardsEdit.querySelector(".fname");
    let lnameCard = mainCardsEdit.querySelector(".lname");
    let codeRequestPaymentCard = mainCardsEdit.querySelector(".code_request_payment");
    let codeClientCard = mainCardsEdit.querySelector(".code_client");
    let codeSupplementsCard = mainCardsEdit.querySelector(".code_supplements");
    let codeSystemCard = mainCardsEdit.querySelector(".code_systems");
    let orderNameCard = mainCardsEdit.querySelector(".order_name");
    let amountCard = mainCardsEdit.querySelector(".amount");
    let paydayCard = mainCardsEdit.querySelector(".payday");
    let buttonsCard = mainCardsEdit.querySelector(".buttons");
    buttons == null ? buttons = buttonsCard.innerHTML : null;
    buttonsCard.innerHTML = "";
    imgCard.src = img;
    fullNameCard.innerHTML = fullName;
    fnameCard.value = fname;
    lnameCard.value = lname;
    codeRequestPaymentCard.value = codeRequestPayment;
    codeClientCard.value = codeClient;
    codeSupplementsCard.value = codeSupplements;
    codeSystemCard.value = codeSystem;
    orderNameCard.value = orderName;
    amountCard.value = amount;
    paydayCard.value = payday;
    if (stateButtonsControl == "acceptance") {
      buttonsCard.innerHTML = buttons;
      let btnAcceptance = mainCardsEdit.querySelector(".acceptance");
      let btnReject = mainCardsEdit.querySelector(".reject");
      if (btnReject) btnReject.remove();
      if (btnAcceptance) btnAcceptance.remove();
      let closeProfile = document.querySelectorAll(`.close-profile`);
      closeProfile.forEach((button, index) => {
        button.addEventListener("click", (e) => {
          const mainCard = button.closest('.main-card');
          mainCard.classList.remove("show-main-card");
        });
      });
    };
  } else {
    let fnameCard = mainCardsSubscriptionRequests.querySelector(".fname");
    let lnameCard = mainCardsSubscriptionRequests.querySelector(".lname");
    let idRequestCard = mainCardsSubscriptionRequests.querySelector(".id_request");
    let codeOrderCard = mainCardsSubscriptionRequests.querySelector(".code_order");
    let phoneCard = mainCardsSubscriptionRequests.querySelector(".phone");
    let orderNameCard = mainCardsSubscriptionRequests.querySelector(".order_name");
    let buttonsCard = mainCardsSubscriptionRequests.querySelector(".buttons");
    let paidCard = mainCardsSubscriptionRequests.querySelector(".paid");
    let paidFind = mainCardsSubscriptionRequests.querySelector(".paid");
    let buttonRowCard = mainCardsSubscriptionRequests.querySelector('.button-row-card');
    let mainInputPaid = paidFind.closest(`.main-input`);
    let saveMainInputPaid = mainInputPaid;
    buttons == null ? buttons = buttonsCard.innerHTML : null;
    buttonsCard.innerHTML = "";
    fnameCard.value = fname;
    lnameCard.value = lname;
    orderNameCard.value = orderName;
    idRequestCard.value = idRequest;
    codeOrderCard.value = codeOrder;
    phoneCard.value = phone;
    if (paid !== null) {
      paidCard.value = paid;
    };
    buttonsCard.innerHTML = buttons;
    mainInputPaid.remove();
    if (stateButtonsControl == "acceptance") {
      let btnAcceptance = mainCardsSubscriptionRequests.querySelector(".acceptance");
      let btnReject = mainCardsSubscriptionRequests.querySelector(".reject");
      if (btnReject) btnReject.remove();
      if (btnAcceptance) btnAcceptance.remove();
      buttonRowCard.parentNode.insertBefore(saveMainInputPaid, buttonRowCard);
      let closeProfile = document.querySelectorAll(`.close-profile`);
      closeProfile.forEach((button, index) => {
        button.addEventListener("click", (e) => {
          const mainCard = button.closest('.main-card');
          mainCard.classList.remove("show-main-card");
        });
      });
    } else if (stateButtonsControl == "waiting") {
      let btnAcceptance = mainCardsSubscriptionRequests.querySelector(".acceptance");
      let btnReject = mainCardsSubscriptionRequests.querySelector(".reject");
      if (btnReject) btnReject.remove();
      btnAcceptance.innerHTML = btnAcceptance.dataset.label;
      btnAcceptance.value = "send";
      buttonRowCard.parentNode.insertBefore(saveMainInputPaid, buttonRowCard);
      let closeProfile = document.querySelectorAll(`.close-profile`);
      closeProfile.forEach((button, index) => {
        button.addEventListener("click", (e) => {
          const mainCard = button.closest('.main-card');
          mainCard.classList.remove("show-main-card");
        });
      });
    };
  };
};

editProfile.forEach((button, index) => {
  button.addEventListener("click", (e) => {
    contentCard("edit", e);
  });
});

btnRequests.forEach((button, index) => {
  button.addEventListener("click", (e) => {
    contentCard("btn-requests", e);
  });
});

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

(function () {
  let data = JSON.parse(sessionStorage.getItem("rejectedPage")) || [];
  if (!data.includes("requests")) {
    data.push("requests");
    sessionStorage.setItem("rejectedPage", JSON.stringify(data));
  };
})();

