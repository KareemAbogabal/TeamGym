let html = document.querySelector("html");
let input = document.querySelector('.search-input');
let rows = document.querySelectorAll(".row:not([data-state-loop='not']");
let editProfile = document.querySelectorAll(".edit");
let showProfile = document.querySelectorAll(".show");
let mainCardsEdit = document.querySelector('.main-card[data-state="edit"]');
let mainCardsDocumentation = document.querySelectorAll('.card .body-card .img-card');
let mainCardsImgDocumentation = document.querySelectorAll('.card .body-card .img');
let documentationInput = document.querySelectorAll('.documentation-input');
let mainCardsShow = document.querySelector('.main-card[data-state="list"]');
let dataTableCardList = document.querySelector('.main-card[data-state="list"] .data-table');
let nameCategoryCardList = document.querySelector('.main-card[data-state="list"] .name-category');
let chartCardList = document.querySelector('.main-card[data-state="list"] #chart-1');
let amountCardList = document.querySelector('.main-card[data-state="list"] .amount');
let paidCardList = document.querySelector('.main-card[data-state="list"] .paid');
let residualCardList = document.querySelector('.main-card[data-state="list"] .residual');
let fnameCardInput = document.querySelectorAll('.fname-card');
let lnameCardInput = document.querySelectorAll('.lname-card');
let phoneCardInput = document.querySelectorAll('.phone-card');
let emailCardInput = document.querySelectorAll('.email-card');
let stateCardInput = document.querySelectorAll('.state-card');
let codeCardInput = document.querySelectorAll('.code');
let chartShow = document.querySelector('.chart-show');
let char1 = document.getElementById("chart-1");
let currentClientCode = null;

function getData(code) {
  let data = fetch('/customers/get-all-data-client', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': token || ''
    },
    body: JSON.stringify({code: code})
  }).then(response => response.json()).catch(error => {
    console.error('Error:', error);
  });
  return data;
};

function pad(n){ return n.toString().padStart(2, '0'); };

function clearClientQr() {
  var box = document.getElementById('clientQrBox');
  if (!box) return;
  box.innerHTML = '';
  var p = document.createElement('p');
  p.className = 'qr-placeholder';
  p.textContent = qrGeneratingLabel || 'Generating QR code…';
  box.appendChild(p);
}

function renderClientQr(code) {
  var box = document.getElementById('clientQrBox');
  if (!box) return;
  box.innerHTML = '';
  if (!code) { box.textContent = qrUnavailableLabel || 'QR code is unavailable.'; return; }
  function make(el) {
    if (window.QRCode) {
      new QRCode(el, { width: 130, height: 130 }).makeCode(String(code));
    } else {
      return false;
    }
    return true;
  }
  var p = document.createElement('p');
  p.textContent = String(code);
  box.appendChild(p);
  if (!make(box)) {
    var q = document.createElement('script');
    q.src = "https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js";
    q.onload = function () { make(box); };
    document.head.appendChild(q);
  }
}

function renderClientBarcode(barcode) {
  var box = document.getElementById('clientBarcodeBox');
  if (!box) return;
  box.innerHTML = '';
  if (!barcode) { box.textContent = qrUnavailableLabel || 'No barcode.'; return; }
  var svg = document.createElement('svg');
  svg.id = 'clientBarcodeSvg' + Date.now();
  box.appendChild(svg);
  function make(el) {
    if (window.JsBarcode) {
      JsBarcode(el, String(barcode), { format: 'ean13', width: 2, height: 80, displayValue: true, margin: 6, fontSize: 14 });
      return true;
    }
    return false;
  }
  if (!make(svg)) {
    var s = document.createElement('script');
    s.src = "https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js";
    s.onload = function () { make(svg); };
    document.head.appendChild(s);
  }
}

function formatIsoToLocalYMDHM(iso) {
  if (!iso) return '';
  const d = new Date(iso);
  const Y = d.getFullYear();
  const M = pad(d.getMonth() + 1);
  const D = pad(d.getDate());
  const h = pad(d.getHours());
  const m = pad(d.getMinutes());
  return `${Y}-${M}-${D} ${h}:${m}`;
};

function contentCard(type, e) {
  const btn = e.target.closest(`.${type}`);
  if (!btn) return;
  const row = btn.closest('.row');
  if (!row) return;
  let nameText = row.querySelector('.search')?.textContent?.trim() ?? '';
  let phone = row.querySelector('.phone')?.textContent?.trim() ?? '';
  let state = row.querySelector('.state')?.textContent?.trim() ?? '';
  let img = row.getAttribute("data-img");
  let documentationState = row.getAttribute("data-documentation");
  let emailState = row.getAttribute("data-communication");
  let code = row.getAttribute("data-code");
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
  let fname = "";
  let lname = "";
  const parts = nameText.split(/\s+/);
  fname = parts.shift() || "";
  lname = parts.join(" ") || "";
  codeCardInput.forEach(item => item.value = code ?? '');
  if (fnameCardInput) {
    fnameCardInput.forEach(item => item.value = fname);
    lnameCardInput.forEach(item => item.value = lname);
    phoneCardInput.forEach(item => item.value = phone);
    stateCardInput.forEach(item => item.value = state);
    emailCardInput.forEach(item => item.value = emailState ?? '');
  };
  if (documentationState == "true") {
    mainCardsDocumentation.forEach(item => { item.innerHTML += documentation; });
    mainCardsImgDocumentation.forEach(item => { item.innerHTML += documentation; });
    documentationInput.forEach(item => item.checked = true);
  };
  if (type == "edit") {
    const targetImgCard = mainCardsEdit.querySelector('.img img');
    targetImgCard.src = img;
    const targetFullNameCards = mainCardsEdit.querySelector('.full-name-card');
    targetFullNameCards.innerHTML = `${fname} ${lname}`;
  } else {
    const targetImgCard = mainCardsShow.querySelector('.img img');
    targetImgCard.src = img;
    const targetFullNameCards = mainCardsShow.querySelector('.full-name-card');
    targetFullNameCards.innerHTML = `${fname} ${lname}`;
    clearClientQr();
    currentClientCode = code;
    renderClientBarcode(null);
    renderClientQr(code);
    getData(code).then(data => {
      dataTableCardList.innerHTML = '';
      const headerDefs = {
        "Requests Payment": [
          { prop: 'order_name', label: orderName },
          { prop: 'amount',     label: amount },
          { prop: 'state',      label: state },
          { prop: 'payday',     label: payDay },
          { prop: 'created_at', label: date },
        ],
        "Payment": [
          { prop: 'order_name', label: orderName },
          { prop: 'amount',     label: amount },
          { prop: 'payday',     label: payDay },
          { prop: 'paid',       label: paid || 'paid' },
          { prop: 'created_at', label: date },
        ],
        "Payment Registry": [
          { prop: 'order_name', label: orderName },
          { prop: 'amount',     label: amount },
          { prop: 'paymonth',   label: month },
          { prop: 'created_at', label: date },
        ],
        "Record": [
          { prop: 'state',      label: state },
          { prop: 'amount',     label: amount },
          { prop: 'created_at', label: date },
        ],
        "Activity": [
          { prop: 'name',       label: name || 'name' },
          { prop: 'day',        label: day},
          { prop: 'month',      label: month || 'month' },
          { prop: 'created_at', label: date },
        ],
      };
      const el = (tag, cls) => {
        const e = document.createElement(tag);
        if (cls) e.className = cls;
        return e;
      };
      const tryFormat = (val) => {
        if (!val && val !== 0) return '';
        if (typeof val === 'string' && /\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/.test(val)) {
          return formatIsoToLocalYMDHM(val);
        }
        return val;
      };
      const tableTabBar = document.createElement('div');
      tableTabBar.className = 'table-tabs';
      const tableTabContent = document.createElement('div');
      tableTabContent.className = 'table-tabs-content';
      const tableTabs = [];
      Object.keys(data).forEach((main) => {
        if (main === "QR") return;
        const items = Array.isArray(data[main]) ? data[main] : [];
        let displayItems = items;
        if (main === "Payment Registry" && items.length) {
          displayItems = (typeof items.flatMap === 'function')
            ? items.flatMap(p => (p.registries || []).map(r => ({ ...r, order_name: p.order_name || '', payment_amount: p.amount || '' })))
            : items.reduce((acc, p) => {
                (p.registries || []).forEach(r => acc.push(Object.assign({}, r, { order_name: p.order_name || '', payment_amount: p.amount || '' })));
                return acc;
              }, []);
          const firstPayment = items[0];
          if (firstPayment) {
            chartCardList.setAttribute("data-amount", firstPayment.amount);
            chartCardList.setAttribute("data-paid", firstPayment.paid);
            amountCardList.innerHTML = firstPayment.amount;
            paidCardList.innerHTML = firstPayment.paid;
            residualCardList.innerHTML = (firstPayment.amount - firstPayment.paid);
            makeChartCircle(char1);
          };
        };
        if (main === "Payment") {
          Object.values(data).forEach((item) => {
            const match = items.find(p => p.type === "system");
            if (match) {
              nameCategoryCardList.setAttribute("data-category", match.order_name);
              nameCategoryCardList.innerHTML = match.order_name;
            };
          });
        };
        const mainTable = el('div', 'main-table');
        const h1 = el('h1'); h1.textContent = main;
        const table = el('div', 'table');
        const header = el('div', 'header');
        const body = el('div', 'body');
        let defs = headerDefs[main];
        if (!defs) {
          const first = items[0] || {};
          defs = Object.keys(first).map(k => ({ prop: k, label: k }));
        }
        defs.forEach(d => {
          const h4 = el('h4');
          h4.textContent = d.label ?? d.prop;
          header.appendChild(h4);
        });
        if (!displayItems || displayItems.length === 0) {
          const choose = el('div', 'choose');
          const p = el('p');
          p.textContent = 'Choose an exercise';
          choose.appendChild(p);
          body.appendChild(choose);
        } else {
          displayItems.forEach(item => {
            const row = el('div', 'row');
            defs.forEach(d => {
              const p = el('p');
              p.innerHTML = tryFormat(item ? item[d.prop] : '');
              row.appendChild(p);
            });
            body.appendChild(row);
          });
        };
        table.appendChild(header);
        table.appendChild(body);
        mainTable.appendChild(h1);
        mainTable.appendChild(table);
        const tabBtn = document.createElement('button');
        tabBtn.type = 'button';
        tabBtn.className = 'table-tab';
        tabBtn.textContent = main;
        mainTable.style.display = 'none';
        tableTabs.push({ btn: tabBtn, panel: mainTable });
        tableTabBar.appendChild(tabBtn);
        tableTabContent.appendChild(mainTable);
      });
      tableTabs.forEach((tab, i) => {
        tab.btn.addEventListener('click', () => {
          tableTabs.forEach(t => t.btn.classList.remove('active'));
          tab.btn.classList.add('active');
          tableTabs.forEach(t => t.panel.style.display = 'none');
          tab.panel.style.display = 'flex';
        });
        if (i === 0) {
          tab.btn.classList.add('active');
          tab.panel.style.display = 'flex';
        }
      });
      dataTableCardList.appendChild(tableTabBar);
      dataTableCardList.appendChild(tableTabContent);
      let pointsRowSMM = row.getAttribute("data-points-smm") || '[]';
      let pointsRowFat = row.getAttribute("data-points-fat") || '[]';
      const qrPanel = mainCardsShow.querySelector('.qr-status-panel');
      if (qrPanel) {
        const qr = data.QR || null;
        qrPanel.style.display = qr ? '' : 'none';
        if (qr) {
          const qrStatusMap = {
            active: qrActiveLabel || 'active',
            revoked: qrRevokedLabel || 'revoked',
            expired: qrExpiredLabel || 'expired',
          };
          qrPanel.querySelectorAll('.qr-status-row').forEach(r => {
            const key = r.getAttribute('data-for');
            const valueEl = r.querySelector('.qr-status-value');
            if (key === 'status') {
              valueEl.textContent = qrStatusMap[qr.status] || qr.status;
              valueEl.className = 'qr-status-value qr-status-badge qr-' + (qr.status || '');
            } else {
              let val = qr[key];
              val = (val !== null && val !== undefined) ? tryFormat(val) : '—';
              if (key === 'scan_count') val = qr.scan_count ?? 0;
              valueEl.textContent = val;
            }
          });
        }
      }
      const barcodePanel = mainCardsShow.querySelector('.barcode-status-panel');
      if (barcodePanel) {
        const bc = data.Barcode || null;
        barcodePanel.style.display = bc ? '' : 'none';
        renderClientBarcode(bc ? bc.barcode : null);
        if (bc) {
          const bcStatusMap = {
            active: qrActiveLabel || 'active',
            revoked: qrRevokedLabel || 'revoked',
            expired: qrExpiredLabel || 'expired',
          };
          barcodePanel.querySelectorAll('.barcode-status-row').forEach(r => {
            const key = r.getAttribute('data-for');
            const valueEl = r.querySelector('.barcode-status-value');
            if (key === 'status') {
              valueEl.textContent = bcStatusMap[bc.status] || bc.status;
              valueEl.className = 'barcode-status-value barcode-status-badge qr-' + (bc.status || '');
            } else {
              let val = bc[key];
              val = (val !== null && val !== undefined) ? tryFormat(val) : '—';
              if (key === 'scan_count') val = bc.scan_count ?? 0;
              valueEl.textContent = val;
            }
          });
        }
      }
      if (mainCardsShow) {
        function setSpan(selector, value) {
          const el = mainCardsShow.querySelector(selector);
          if (el) el.textContent = (value !== null && value !== undefined) ? value : 0;
        };
        setSpan('.weight', row.getAttribute('data-weight'));
        setSpan('.BMI', row.getAttribute('data-BMI'));
        setSpan('.PBF-percent', row.getAttribute('data-PBF'));
        setSpan('.SMM-kg', row.getAttribute('data-SMM'));
        setSpan('.kcal', row.getAttribute('data-kcal'));
        setSpan('.body-fat-mass', row.getAttribute('data-fat_mass'));
        setSpan('.protein-kg', row.getAttribute('data-protein'));
        setSpan('.water', row.getAttribute('data-water'));
        setSpan('.right-arm-lean-kg', row.getAttribute('data-right_arm_lean'));
        setSpan('.left-arm-lean-kg', row.getAttribute('data-left_arm_lean'));
        setSpan('.right-leg-lean-kg', row.getAttribute('data-right_leg_lean'));
        setSpan('.left-leg-lean-kg', row.getAttribute('data-left_leg_lean'));
        setSpan('.right-arm-fat-kg', row.getAttribute('data-right_arm_fat'));
        setSpan('.left-arm-fat-kg', row.getAttribute('data-left_arm_fat'));
        setSpan('.right-leg-fat-kg', row.getAttribute('data-right_leg_fat'));
        setSpan('.left-leg-fat-kg', row.getAttribute('data-left_leg_fat'));
      };
      try {
        makeChartBar(chartShow, pointsRowSMM, pointsRowFat);
      } catch (err) {
        console.error('makeChartBar error', err);
      };
    });
  };
};

editProfile.forEach((button) => {
  button.addEventListener("click", (e) => {
    contentCard("edit", e);
  });
});

showProfile.forEach((button) => {
  button.addEventListener("click", (e) => {
    contentCard("show", e);
  });
});

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

function makeChartBar(element, pointsSMM, pointsFat) {
  if (!element) return console.warn('Chart element not provided');
  const isArabic = document.documentElement.getAttribute("lang") === "ar";
  try {
    const existing = (typeof Chart.getChart === 'function') ? Chart.getChart(element) : element._chart;
    if (existing && typeof existing.destroy === 'function') {
      existing.destroy();
      element._chart = null;
    }
  } catch (e) {
    console.warn('Could not destroy existing chart (ignored)', e);
  }
  const stackedBarConfig = {
    type: 'bar',
    data: {
      labels: months,
      datasets: [
        {
          label: 'SMM',
          data: JSON.parse(pointsSMM),
          borderColor: Utils.CHART_BORDER_COLORS.red,
          backgroundColor: Utils.CHART_COLORS.red,
          borderWidth: 2,
          borderRadius: 10,
          borderSkipped: false,
        },
        {
          label: 'Fat',
          data: JSON.parse(pointsFat),
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
        legend: { position: 'top', labels: isArabic ? { font: { family: 'Cairo', size: 14 } } : undefined },
        title: { display: true }
      },
      scales: {
        x: { stacked: false, categoryPercentage: 0.8, barPercentage: 0.9, grid: { display: false, drawBorder: false } },
        y: { stacked: false, title: { display: true }, grid: { display: false, drawBorder: false } },
      },
    },
  };
  element._chart = new Chart(element, stackedBarConfig);
};

function makeChartCircle(element) {
  if (element._chartInstance) {
    element._chartInstance.destroy();
  };
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
  element._chartInstance = new Chart(element, doughnutConfig);
};

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

(function () {
  var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  function post(url, body, onOk, onErr) {
    return fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify(body),
    }).then(function (r) { return r.json(); }).then(function (data) {
      if (data.ok && onOk) onOk(data);
      else if (!data.ok && onErr) onErr(data);
      return data;
    });
  }
  function refreshBarcode() {
    if (!currentClientCode) return;
    getData(currentClientCode).then();
  }
  var printBtn = document.getElementById('btnBarcodePrint');
  if (printBtn) {
    printBtn.addEventListener('click', function () {
      if (!currentClientCode) return;
      window.open('/customers/print-barcode/' + encodeURIComponent(currentClientCode), '_blank');
    });
  }
  var regenBtn = document.getElementById('btnBarcodeRegenerate');
  if (regenBtn) {
    regenBtn.addEventListener('click', function () {
      if (!currentClientCode) return;
      post('/customers/regenerate-barcode', { code: currentClientCode }, function (data) {
        refreshBarcode();
      }, function (data) {
        alert((data && data.message) || 'Error');
      });
    });
  }
  var revokeBtn = document.getElementById('btnBarcodeRevoke');
  if (revokeBtn) {
    revokeBtn.addEventListener('click', function () {
      if (!currentClientCode) return;
      if (!confirm('Revoke this attendance barcode?')) return;
      post('/customers/revoke-barcode', { code: currentClientCode }, function (data) {
        refreshBarcode();
      }, function (data) {
        alert((data && data.message) || 'Error');
      });
    });
  }
})();
