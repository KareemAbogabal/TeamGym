let html = document.querySelector("html");
let isArabic = html.getAttribute("lang") === "ar";
let recordForm = document.querySelector(".record-form");
let attachmentForm = document.querySelector(".attachment-form");
let exitForm = document.querySelector(".exit-form");
let nameInput = document.getElementById('fullname');
let attachmentName = document.querySelectorAll(".attachment-name");
let attachmentInput = document.querySelectorAll(".attachment-input");
let radioChoose = attachmentForm.querySelectorAll(".radio-choose");
let attachmentNameExit = document.querySelector(".attachment-name-exit");
let attachmentInputExit = document.querySelector(".attachment-input-exit");
let attachmentInputExitCode = document.querySelector(".supplement-input-exit-code");
let supplementInputExit = document.querySelector(".supplement-input-exit");
let systemInputExitCode = document.querySelector(".system-input-exit-code");
let systemInputExit = document.querySelector(".system-input-exit");
let radioChooseInstallment = document.querySelectorAll(".radio-choose-installment");
let installmentName = document.querySelector(".installment-name");
let supplementLableInstallment = document.querySelectorAll(".supplement-lable-installment");
let radioInputs = document.querySelectorAll(".radio-input");
let radioInputsSubscription = document.querySelectorAll(".main-input-subscription");
let supplementLable = attachmentForm.querySelectorAll(".supplement-lable");
let buttonChoose = exitForm.querySelectorAll(".button-choose");
let supplementLableExit = exitForm.querySelectorAll(".supplement-lable");
let radioInputSnacks = exitForm.querySelectorAll(".radio-input-snacks");
let radioSystemSnacks = document.querySelectorAll(".radio-system-snacks");
let stateCheckClient = document.querySelector(".state-check-client");
let radioSystemAttachment = document.querySelectorAll(".radio-system-attachment");
let radioSystemExit = exitForm.querySelectorAll(".radio-system-exit");
let fnameCardInput = Array.from(document.querySelectorAll('input[name="fname"]'));
let lnameCardInput = Array.from(document.querySelectorAll('input[name="lname"]'));
let nebulaInputs = document.querySelectorAll(".nebula-input input");
let nebulaLabel = document.querySelectorAll(".user-label");
let inputs = document.querySelectorAll('.search-input');
let rows = document.querySelectorAll(".row-record");
let btnDetails = document.querySelectorAll(".btn-details");
let mainCard = document.querySelector(`.main-card[data-state="entrances"]`);
let btnEntrance = document.querySelectorAll(`.btn-entrance`);
let btnDetailsCustomer = document.querySelectorAll(`.btn-details-customer`);
let mainCardsDetailsCustomer = document.querySelector('.main-card[data-state="details-customer"]');
let checkSendData = false;

inputs.forEach(input => {
  input.addEventListener('input', () => {
    const stateSearch = input.getAttribute("data-state-search");
    const today = new Date().toISOString().slice(0, 10);
    const term = input.value.trim().toLowerCase();
    if (stateSearch === "record") {
      const rowSearch = document.querySelectorAll(".row-record");
      const exitToday = Array.from(rowSearch).some(r => {
        const dateNode = r.querySelector("p:nth-child(6)");
        const rawDateText = dateNode ? dateNode.textContent.trim() : '';
        const rowDate = rawDateText.slice(0, 10);
        const rText = Array.from(r.children).map(el => el.textContent.trim().toLowerCase()).join(' ');
        return rowDate === today && rText.includes("exit");
      });
      rowSearch.forEach(row => {
        const dateNode = row.querySelector("p:nth-child(6)");
        const rawDateText = dateNode ? dateNode.textContent.trim() : '';
        const rowDate = rawDateText.slice(0, 10);
        const text = Array.from(row.children).map(el => el.textContent.trim().toLowerCase()).join(' ');
        if (!term) {
          row.style.display = 'flex';
        } else {
          if (exitToday) {
            row.style.display = text.includes(term) ? 'flex' : 'none';
          } else {
            row.style.display = (rowDate === today && text.includes(term)) ? 'flex' : 'none';
          };
        };
      });
    } else {
      const rowSearch = document.querySelectorAll(".row-customer");
      rowSearch.forEach(row => {
        const text = Array.from(row.children).map(el => el.textContent.trim().toLowerCase()).join(' ');
        row.style.display = text.includes(term) ? 'flex' : 'none';
      });
    };
  });
});

function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  if (meta) return meta.getAttribute('content');
  const tokenInput = recordForm ? recordForm.querySelector('input[name="_token"]') : null;
  return tokenInput ? tokenInput.value : null;
};

async function postJson(url, data) {
  const headers = {
    'Accept': 'application/json',
    'X-CSRF-TOKEN': getCsrfToken()
  };
  let options;
  if (data instanceof FormData) {
    options = { method: 'POST', headers, body: data };
  } else {
    headers['Content-Type'] = 'application/json';
    options = { method: 'POST', headers, body: JSON.stringify(data) };
  };
  const resp = await fetch(url, options);
  if (!resp.ok) throw new Error('Network response was not ok: ' + resp.status);
  return resp.json();
};

function clearValueIfExists(el, value = '') {
  if (!el) return;
  try { el.value = value; } catch (e) { /* ignore */ }
};

function createRadioLabels(container, dataArray, className) {
  container.innerHTML = "";
  dataArray.forEach((item, idx) => {
    item.forEach((payment, index) => {
      const label = document.createElement("label");
      const input = document.createElement("input");
      const p = document.createElement("p");
      label.className = `label ${className}`;
      input.type = "radio";
      input.id = `value-${idx}`;
      input.name = "code_payment";
      input.value = payment.code;
      p.className = "text";
      p.innerHTML = payment.supplement.name ?? 'Unnamed';
      label.appendChild(input);
      label.appendChild(p);
      container.appendChild(label);
    })
  });
};

radioChoose.forEach((button) => {
  button.addEventListener('click', () => {
    const span = button.querySelector("span");
    const text = span.getAttribute("data-name");
    if (text == "supplement") {
      if (attachmentInput[0]) attachmentInput[0].value = text;
      if (radioInputs[0]) radioInputs[0].classList.add("show-radio-input");
      if (radioInputsSubscription[0]) radioInputsSubscription[0].classList.add("hidden-main-input-subscription");
    } else if (text == "system") {
      radioInputsSubscription[0].classList.remove("hidden-main-input-subscription");
      radioInputs[0].classList.remove("show-radio-input");
    } else {
      if (radioInputsSubscription[0]) radioInputsSubscription[0].classList.add("hidden-main-input-subscription");
      if (radioInputs[0]) radioInputs[0].classList.remove("show-radio-input");
    };
    if (attachmentName[0]) attachmentName[0].value = text;
  });
});

const lastClickMap = new WeakMap();

radioChooseInstallment.forEach((button, index) => {
  button.addEventListener('click', async (e) => {
    const now = Date.now();
    const last = lastClickMap.get(button) || 0;
    if (now - last < 300) {
      return;
    };
    lastClickMap.set(button, now);
    const span = button.querySelector("span");
    const text = span.getAttribute("data-name");
    installmentName.value = text;
    if (text == "supplement") {
      installmentName.value = "";
      if (radioInputsSubscription[1]) radioInputsSubscription[1].classList.add("hidden-main-input-subscription");
      if (radioInputs[1]) radioInputs[1].classList.add("show-radio-input");
      let fnameInstallment = document.querySelector(".fname-installment");
      let lnameInstallment = document.querySelector(".lname-installment");
      const formData = new FormData();
      formData.append('fname', fnameInstallment ? fnameInstallment.value : '');
      formData.append('lname', lnameInstallment ? lnameInstallment.value : '');
      try {
        const data = await postJson('/get-supplement-client', formData);
        if (data && data.check) {
          console.log('Server returned:', data.check);
          return;
        };
        if (!Array.isArray(data)) {
          console.warn('Expected an array, got:', data);
          return;
        };
        if (radioInputs[1]) {
          createRadioLabels(radioInputs[1], data, "supplement-lable-installment");
          supplementLableInstallment = document.querySelectorAll(".supplement-lable-installment");
          supplementLableInstallment.forEach((button) => {
            button.addEventListener('click', () => {
              const input = button.querySelector("input");
              if (input) installmentName.value = input.value;
            });
          });
        };
        console.log('Received data:', data);
      } catch (err) {
        console.error('Fetch error:', err);
      };
    } else {
      radioInputsSubscription[1].classList.remove("hidden-main-input-subscription");
      if (radioInputs[1]) radioInputs[1].classList.remove("show-radio-input");
    };
  });
});

supplementLableInstallment.forEach((button) => {
  button.addEventListener('click', () => {
    const input = button.querySelector("input");
    if (input) installmentName.value = input.value;
  });
});

supplementLableExit.forEach((button) => {
  button.addEventListener('click', () => {
    const input = button.querySelector("input");
    if (input) attachmentInputExit.value = input.value;
  });
});

buttonChoose.forEach((button) => {
  button.addEventListener('click', async () => {
    button.classList.add("choose-button");
    const text = button.getAttribute("data-name");
    if (attachmentNameExit) attachmentNameExit.value = text;
    if (text == "supplement") {
      // if (attachmentNameExit) attachmentNameExit.value = "";
      if (radioInputs[2]) radioInputs[2].classList.add("show-radio-input");
      const mainAmount = mainCard.querySelector(".main-amount");
      const fname = mainCard ? mainCard.querySelector(".fname") : null;
      const lname = mainCard ? mainCard.querySelector(".lname") : null;
      mainAmount.style.display = "flex";
      const formData = new FormData();
      formData.append('fname', fname ? fname.value.toLowerCase() : '');
      formData.append('lname', lname ? lname.value.toLowerCase() : '');
      try {
        const data = await postJson('/get-supplement-client', formData);
        if (data && data.check) {
          console.log('Server returned:', data.check);
          return;
        };
        if (!Array.isArray(data)) {
          console.warn('Expected an array, got:', data);
          return;
        };
        if (radioInputs[2]) {
          createRadioLabels(radioInputs[2], data, "supplement-lable");
          supplementLableExit = exitForm.querySelectorAll(".supplement-lable");
          supplementLableExit.forEach((button) => {
            button.addEventListener('click', () => {
              const input = button.querySelector("input");
              attachmentInputExitCode.value = input.value;
              if (input && attachmentInputExit) attachmentInputExit.value = input.value;
              supplementInputExit.value = "supplement";
            });
          });
        };
        console.log('Received data:', data);
      } catch (err) {
        console.error('Fetch error:', err);
      }
    } else if (text == "system") {
      systemInputExit.value = "system";
    } else if (text == "snacks") {
      radioInputSnacks[0].classList.add("show-radio-input");
      if (radioInputs[2]) radioInputs[2].classList.remove("show-radio-input");
    } else {
      if (radioInputs[2]) radioInputs[2].classList.remove("show-radio-input");
    };
  });
});

supplementLable.forEach((button) => {
  button.addEventListener('click', () => {
    const input = button.querySelector("input");
    if (input && input.value !== "supplement" && attachmentInput[0]) {
      attachmentInput[0].value = input.value;
    };
  });
});

radioSystemAttachment.forEach((button) => {
  button.addEventListener('click', () => {
    const input = button.querySelector("input");
    if (input && installmentName) {
      installmentName.value = input.value;
    }
  });
});

radioSystemSnacks.forEach((button) => {
  button.addEventListener('click', () => {
    const input = button.querySelector("input");
    if (input && installmentName) {
      attachmentInputExit.value = input.value;
      systemInputExitCode.value = input.value;
    };
  });
});

function registrationCustomer() {
  const registrationCustomerBtn = document.querySelector(".registration-customer");
  if (!registrationCustomerBtn) return;
  const forms = document.querySelectorAll(".forms form");
  registrationCustomerBtn.addEventListener('click', () => {
    forms[0].classList.add("hidden");
    forms[1].classList.remove("hidden");
    forms[2].classList.add("hidden");
    forms[3].classList.add("hidden");
  });
};

function registerRequest() {
  const registerRequestBtn = document.querySelector(".register-request");
  if (!registerRequestBtn) return;
  const forms = document.querySelectorAll(".forms form");
  registerRequestBtn.addEventListener('click', () => {
    forms[0].classList.add("hidden");
    forms[1].classList.add("hidden");
    forms[2].classList.remove("hidden");
    forms[3].classList.add("hidden");
  });
};

function installmentRegistration() {
  const installmentRegistrationBtn = document.querySelector(".installment-registration");
  if (!installmentRegistrationBtn) return;
  const forms = document.querySelectorAll(".forms form");
  installmentRegistrationBtn.addEventListener('click', () => {
    forms[0].classList.add("hidden");
    forms[1].classList.add("hidden");
    forms[2].classList.add("hidden");
    forms[3].classList.remove("hidden");
  });
};

nebulaInputs.forEach((inputEl, index) => {
  // inputEl.addEventListener('click', () => {
  //   if (nebulaLabel[index]) nebulaLabel[index].classList.add("show-input");
  // });
  inputEl.addEventListener('mouseenter', () => {
    if (inputEl.value == "" && nebulaLabel[index]) nebulaLabel[index].classList.add("show-input");
  });
  inputEl.addEventListener('mouseout', () => {
    if (inputEl.value == "" && nebulaLabel[index]) nebulaLabel[index].classList.remove("show-input");
  });
});

btnDetails.forEach((button, index) => {
  button.addEventListener('click', () => {
    button.classList.toggle("show-btn-det");
    if (rows[index]) rows[index].classList.toggle("show-det-row");
  });
});

btnEntrance.forEach((button) => {
  button.addEventListener("click", (e) => {
    if (!mainCard) return;

    const documentationSVG = `
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

    const fullName = button.getAttribute("data-full-name");
    const fname = button.getAttribute("data-fname");
    const lname = button.getAttribute("data-lname");
    const code = button.getAttribute("data-code");
    const documentation = button.getAttribute("data-documentation");
    const system = button.getAttribute("data-system");
    const systemDefult = button.getAttribute("data-system-defult");

    const fullNameCard = mainCard.querySelector(".full-name");
    const fnameCard = mainCard.querySelector(".fname");
    const lnameCard = mainCard.querySelector(".lname");
    const codeCard = mainCard.querySelector(".code");
    const imgCard = mainCard.querySelector(".img");
    const mainAmount = mainCard.querySelector(".main-amount");

    if (system == systemDefult) {
      mainAmount.style.display = "none";
    };
    if (fullNameCard) fullNameCard.innerHTML = fullName || '';
    if (fnameCard) fnameCard.value = fname || '';
    if (lnameCard) lnameCard.value = lname || '';
    if (codeCard) codeCard.value = code || '';

    if (documentation == "true" && imgCard) {
      imgCard.innerHTML += documentationSVG;
    };
  });
});

function createVerifiedCustomerElement(check) {
  const svgNS = "http://www.w3.org/2000/svg";
  const p = document.createElement('p');

  stateCheckClient.innerHTML = "";

  if (check === "done") {
    p.dataset.check = 'done';

    const svg = document.createElementNS(svgNS, 'svg');
    svg.setAttribute('viewBox', '0 0 64 64');
    svg.setAttribute('width', '64');
    svg.setAttribute('height', '64');
    svg.setAttribute('aria-label', 'smaller curvy check in circle');

    const circle = document.createElementNS(svgNS, 'circle');
    circle.setAttribute('cx', '32');
    circle.setAttribute('cy', '32');
    circle.setAttribute('r', '28');
    circle.setAttribute('fill', 'var(--colorCheck)');

    const path = document.createElementNS(svgNS, 'path');
    path.setAttribute('d', 'M20 34 Q26 40 30 42 Q36 34 44 24');
    path.setAttribute('fill', 'none');
    path.setAttribute('stroke', '#fff');
    path.setAttribute('stroke-width', '5');
    path.setAttribute('stroke-linecap', 'round');
    path.setAttribute('stroke-linejoin', 'round');

    svg.appendChild(circle);
    svg.appendChild(path);

    const spanText = document.createElement('span');
    spanText.textContent = isArabic ? 'تم التحقق من العميل' : 'Customer verified';

    const btnRegister = document.createElement('button');
    btnRegister.type = 'button';
    btnRegister.className = 'register-request';
    btnRegister.textContent = isArabic ? 'تسجيل طلب' : 'Register a request';

    const spanOr = document.createElement('span');
    spanOr.textContent =  isArabic ? 'او' : 'or';

    const btnInstallment = document.createElement('button');
    btnInstallment.type = 'button';
    btnInstallment.className = 'installment-registration';
    btnInstallment.textContent = isArabic ? 'تسجيل التقسيط' : 'Installment registration';

    p.appendChild(svg);
    p.appendChild(spanText);
    p.appendChild(btnRegister);
    p.appendChild(spanOr);
    p.appendChild(btnInstallment);
  } else if (check === "error") {
    p.dataset.check = 'error';

    const svg = document.createElementNS(svgNS, 'svg');
    svg.setAttribute('viewBox', '0 0 64 64');
    svg.setAttribute('width', '64');
    svg.setAttribute('height', '64');
    svg.setAttribute('aria-label', 'X in circle');

    const circle = document.createElementNS(svgNS, 'circle');
    circle.setAttribute('cx', '32');
    circle.setAttribute('cy', '32');
    circle.setAttribute('r', '28');
    circle.setAttribute('fill', 'var(--colorError)');

    const line1 = document.createElementNS(svgNS, 'line');
    line1.setAttribute('x1', '22');
    line1.setAttribute('y1', '22');
    line1.setAttribute('x2', '42');
    line1.setAttribute('y2', '42');
    line1.setAttribute('stroke', '#fff');
    line1.setAttribute('stroke-width', '5');
    line1.setAttribute('stroke-linecap', 'round');

    const line2 = document.createElementNS(svgNS, 'line');
    line2.setAttribute('x1', '42');
    line2.setAttribute('y1', '22');
    line2.setAttribute('x2', '22');
    line2.setAttribute('y2', '42');
    line2.setAttribute('stroke', '#fff');
    line2.setAttribute('stroke-width', '5');
    line2.setAttribute('stroke-linecap', 'round');

    svg.appendChild(circle);
    svg.appendChild(line1);
    svg.appendChild(line2);

    const spanText = document.createElement('span');
    spanText.textContent = isArabic ? 'العميل غير موجود' : 'Client not present';

    const btnRegisterCustomer = document.createElement('button');
    btnRegisterCustomer.type = 'button';
    btnRegisterCustomer.className = 'registration-customer';
    btnRegisterCustomer.textContent = isArabic ? 'تسجيل العميل' : 'Customer registration';

    p.appendChild(svg);
    p.appendChild(spanText);
    p.appendChild(btnRegisterCustomer);
  } else {
    return;
  };
  stateCheckClient.appendChild(p);
};

if (recordForm) {
  recordForm.addEventListener('submit', async (e) => {
    if (!checkSendData) e.preventDefault();
    const nameText = (nameInput.value || '').trim();
    const parts = nameText.split(/\s+/).filter(Boolean);
    const fname = parts.length ? parts.shift() : "";
    const lname = parts.length ? parts.join(" ") : "";
    fnameCardInput.forEach(item => { item.value = fname; });
    lnameCardInput.forEach(item => { item.value = lname; });
    const payload = { fname: fname, lname: lname };
    if (!checkSendData) {
      try {
        const data = await postJson('/search-client', payload);
        if (data && data.check !== "no") {
          createVerifiedCustomerElement("done");
          registerRequest();
          installmentRegistration();
          let inputCode = document.createElement("input");
          inputCode.type = "hidden";
          inputCode.value = data.code;
          inputCode.name = "code_client";
          recordForm.appendChild(inputCode);
          checkSendData = true;
        } else {
          createVerifiedCustomerElement("error");
          registrationCustomer();
        };
      } catch (err) {
        console.error('Error:', err);
      };
    };
  });
};


function contentCard(type, e, data = null) {
  const btn = e.target.closest(`.${type}`);
  if (!btn) return;
  const row = btn.closest('.row');
  if (!row) return;

  let img = row.getAttribute("data-img");
  let fullName = row.getAttribute("data-name");
  let documentationState = row.getAttribute("data-documentation");

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

  let mainCardsDetailsCustomerDocumentation = mainCardsDetailsCustomer.querySelector(".img");
  if (documentationState == "true") {
    mainCardsDetailsCustomerDocumentation.innerHTML += documentation;
  };

  function pad(n){ return n.toString().padStart(2, '0'); };

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

  if (type == "btn-details-customer") {
    let imgCard = mainCardsDetailsCustomer.querySelector(".img-card img");
    let fullNameCard = mainCardsDetailsCustomer.querySelector(".full-name-card");
    let bodySystem = mainCardsDetailsCustomer.querySelector(".system .table .body");
    let bodySupplement = mainCardsDetailsCustomer.querySelector(".supplement .table .body");
    let plan = mainCardsDetailsCustomer.querySelector(".plan");
    let paymentPaid = mainCardsDetailsCustomer.querySelector(".payment-paid");
    let namePaid = paymentPaid.getAttribute("data-lable");
    let paymentResidual = mainCardsDetailsCustomer.querySelector(".payment-residual");
    let nameResidual = paymentResidual.getAttribute("data-lable");
    imgCard.src = img;
    fullNameCard.innerHTML = fullName;
    if (data != null) {
      bodySystem.innerHTML = "";
      bodySupplement.innerHTML = "";
    };
    plan.innerHTML = data.category;
    data.payment.forEach((item, index) => {
      if (item.type == "system" && index == 0) {
        paymentPaid.innerHTML = `${namePaid}: ${item.paid}`;
        paymentResidual.innerHTML = `${nameResidual}: ${(item.amount - item.paid)}`;
      };
    });
    data.payment.forEach((item, index) => {
      item.registries.forEach(registrie => {
        let row = document.createElement("div");
        let content = document.createElement("div");
        let orderName = document.createElement("p");
        let type = document.createElement("p");
        let amount = document.createElement("p");
        let date = document.createElement("p");
        row.className = "row";
        content.className = "content";
        if (index == 0) {
          orderName.className = "search";
        };
        orderName.innerHTML = registrie.order_name;
        type.innerHTML = registrie.type;
        amount.innerHTML = registrie.amount;
        date.innerHTML = formatIsoToLocalYMDHM(registrie.created_at);
        content.appendChild(orderName);
        content.appendChild(type);
        content.appendChild(amount);
        content.appendChild(date);
        row.appendChild(content);
        if (registrie.type == "system") {
          bodySystem.appendChild(row);
        } else if (registrie.type == "supplement") {
          bodySupplement.appendChild(row);
        };
      });
    });
  };
};

btnDetailsCustomer.forEach(button => {
  button.onclick = (e) => {
    let code = button.getAttribute("data-code");
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/get-payment-customer', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token || ''
      },
      body: JSON.stringify({code: code})
    }).then(response => response.json()).then(data => {
      contentCard("btn-details-customer", e, data);
    }).catch(error => {
      console.error('Error:', error);
    });
  };
});

(function () {
  let data = JSON.parse(sessionStorage.getItem("rejectedPage")) || [];
  if (!data.includes("records")) {
    data.push("records");
    sessionStorage.setItem("rejectedPage", JSON.stringify(data));
  };
})();
