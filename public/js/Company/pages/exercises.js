let forms = document.querySelectorAll('form');
let html = document.querySelector("html");
let isArabic = html.getAttribute("lang") === "ar";
let foodeForm = document.querySelector('.foode-form');
let chartLine = document.querySelectorAll(".chart-line");
let fnameCardInput = document.querySelectorAll('.fname');
let lnameCardInput = document.querySelectorAll('.lname');
let phoneCardInput = document.querySelectorAll('.phone');
let stateCardInput = document.querySelectorAll('.state');
let editProfile = document.querySelectorAll(".edit");
let foodeProfile = document.querySelectorAll(".foode");
let listProfile = document.querySelectorAll(".list");
let deleteExercises = document.querySelectorAll(".delete");
let mainCardsEdit = document.querySelector('.main-card[data-state="edit"]');
let mainCardsShow = document.querySelector('.main-card[data-state="foode"]');
let mainCardsList = document.querySelector('.main-card[data-state="list"]');
let mainCardsDelete = document.querySelector('.main-card[data-state="delete"]');
let chartShow = document.querySelector('.chart-show');
let mainCardsEmployee = document.querySelectorAll('.main-card[data-state="employee"]');
let showDetailsEmployee = document.querySelectorAll(".show-details-employee");
let mainInputsExercises = document.querySelector(`.main-inputs-exercises`);
let btnAddExercises = document.querySelector(`.add-exercises`);
let btnAddShapeExercises = document.querySelector(`.add-shape-exercises`);
let mainInputsFoods = document.querySelector(`.main-inputs-foods`);
let btnAddShapeFoods = document.querySelector(`.add-shape-foods`);
let btnsRemove = document.querySelectorAll('.remove');
let btnUpdate = document.querySelectorAll('.update');
let documentationInput = document.querySelectorAll('.documentation-input');
let mainCardsDocumentation = document.querySelectorAll('.card .body-card .img');
let imgCard = document.querySelectorAll('.img-card');
let fullName = document.querySelectorAll('.full-name-card');
let btnShapes = document.querySelectorAll('.shape');
let checkShape = document.querySelectorAll('.check-shape');

btnsRemove.forEach((button, index) => {
  button.onclick = (e) => {
    const ele = e.target.closest(`.main-input`);
    ele.remove();
  };
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


function createRows(element) {
  let mainExercises = document.querySelector(".exercises .body");
  let mainFoods = document.querySelector(".foods .body");
  mainExercises.innerHTML = "";
  mainFoods.innerHTML = "";
  let rowsTabelsExercises = element.getAttribute("data-exercises");
  let rowsTabelsFoods = element.getAttribute("data-foods");
  const arrRowExercises = JSON.parse(rowsTabelsExercises.replace(/'/g, '"'));
  const arrRowFoods = JSON.parse(rowsTabelsFoods.replace(/'/g, '"'));
  arrRowExercises.forEach((row) => {
    let divRow = document.createElement("div");
    divRow.className = "row";
    row.forEach(item => {
      let paragarph = document.createElement("p");
      paragarph.innerHTML = item;
      divRow.appendChild(paragarph);
    });
    mainExercises.appendChild(divRow);
  })
  arrRowFoods.forEach((row) => {
    let divRow = document.createElement("div");
    divRow.className = "row";
    row.forEach(item => {
      let paragarph = document.createElement("p");
      paragarph.innerHTML = item;
      divRow.appendChild(paragarph);
    });
    mainFoods.appendChild(divRow);
  });
};

function contentCard(type, e) {
  const btn = e.target.closest(`.${type}`);
  if (!btn) {
    console.warn('لم أجد الزرّ (closest) — تأكد أنك ضغطت داخل عنصر الزرّ أو أن السيلكتور صحيح.');
    return;
  }
  const card = e.target.closest('.card-client');
  if (!card) {
    console.warn('لم أجد .card-client — تأكد من بنية الـ HTML.');
    return;
  };
  const row = card.querySelector('.main-content');
  if (!row) {
    console.warn('لم أجد .content داخل .card-client — تحقق من بنية الـ HTML.');
    return;
  };
  const nameEl = row.querySelector('.name-client') || card.querySelector('.name-client');
  if (!nameEl) {
    console.warn('لم أجد .name-client — تأكد أن العنصر موجود.');
    return;
  };
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
  let img = row.querySelector("img");
  let documentationState = row.getAttribute("data-documentation");
  imgCard.forEach(item => { if (img) item.src = img.src; });
  if (documentationState == "true") {
    mainCardsDocumentation.forEach(item => { item.innerHTML += documentation; });
    documentationInput.forEach(item => item.checked = true);
  };
  const nameText = nameEl.textContent.trim();
  const parts = nameText.split(/\s+/).filter(Boolean);
  const fname = parts.length ? parts.shift() : "";
  const lname = parts.length ? parts.join(" ") : "";
  const fullNameInCard = card.querySelectorAll('.full-name-card');
  if (fullNameInCard && fullNameInCard.length > 0) {
    fullNameInCard.forEach(el => {
      el.textContent = nameText;
    });
  } else {
    document.querySelectorAll('.full-name-card').forEach(el => {
      el.textContent = nameText;
    });
  };
  fnameCardInput.forEach(item => {
    item.value = fname;
  });
  lnameCardInput.forEach(item => {
    item.value = lname;
  });
  const code_client = card.querySelector('.code_client');
  let codClient = document.createElement("input");
  codClient.type = "hidden";
  codClient.value = code_client.value;
  codClient.name = "client_code";
  if (type === "edit") {
    forms[forms.length - 1].appendChild(codClient);
  } else if (type === "list") {
    let code = btn.getAttribute("data-code");
    function pad(n){ return n.toString().padStart(2, '0'); }
    function formatIsoToLocalYMDHM(iso) {
      if (!iso) return '';
      const d = new Date(iso);
      const Y = d.getFullYear();
      const M = pad(d.getMonth() + 1);
      const D = pad(d.getDate());
      const h = pad(d.getHours());
      const m = pad(d.getMinutes());
      return `${Y}-${M}-${D} ${h}:${m}`;
    }
    fetch('/get-activity-customer', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || ''
      },
      body: JSON.stringify({ code: code })
    }).then(async response => {
      if (!response.ok) {
        const text = await response.text();
        console.error('Non-OK response:', response.status, text);
        return Promise.reject(new Error('Server returned non-OK response'));
      }
      return response.json();
    }).then(data => {
      const mainTable = mainCardsList.querySelector('.main-t');
      if (!mainTable) {
        console.error('mainTable (.main-t) not found');
        return;
      }
      mainTable.innerHTML = '';
      data.forEach(activity => {
        const mainEl = document.createElement('main');
        const head = document.createElement('div');
        head.className = 'head';

        const h1 = document.createElement('h1');
        h1.textContent = activity.name || '';

        head.appendChild(h1);

        const formMain = document.createElement('form');
        formMain.method = 'post';
        formMain.action = destroy;

        const inputCsrf = document.createElement('input');
        inputCsrf.type = 'hidden';
        inputCsrf.name = '_token';
        inputCsrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '';

        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id';
        inputId.value = activity.id || '';

        const inputStateMain = document.createElement('input');
        inputStateMain.type = 'hidden';
        inputStateMain.name = 'state';
        inputStateMain.value = 'main';

        const btn = document.createElement('button');
        btn.type = 'submit';
        btn.innerHTML = `
          <svg width="30" height="30" viewBox="0 0 64 64" fill="none" aria-hidden="true">
            <g stroke="var(--colorSVG1)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none">
              <rect x="14" y="10" width="36" height="6" rx="2"/>
              <rect x="26" y="8" width="12" height="4" rx="1" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="1"/>
              <path d="M16 20 L48 20 L44 54 L20 54 Z" />
              <path d="M24 26 L26 48" />
              <path d="M32 26 L32 48" />
              <path d="M40 26 L38 48" />
              <path d="M20 54h24" stroke-width="3" stroke-linecap="round"/>
            </g>
          </svg>
        `;

        formMain.appendChild(inputCsrf);
        formMain.appendChild(inputId);
        formMain.appendChild(inputStateMain);
        formMain.appendChild(btn);

        head.appendChild(formMain);
        if (typeof createMediaAddButton === 'function') {
          try {
            createMediaAddButton(formMain);
          } catch (err) {
            console.error('createMediaAddButton error:', err);
          };
        };

        const tableWrap = document.createElement('div');
        tableWrap.className = 'table';

        const headerDiv = document.createElement('div');
        headerDiv.className = 'header';
        const headers = (activity.state === 'exercise')
          ? ['Shape','Groups','Repetitions','Images','Videos','delete']
          : ['Shape','Groups','Repetitions','delete'];

        headers.forEach(h => {
          const h4 = document.createElement('h4');
          h4.textContent = h;
          headerDiv.appendChild(h4);
        });
        tableWrap.appendChild(headerDiv);

        const bodyDiv = document.createElement('div');
        bodyDiv.className = 'body';

        if (Array.isArray(activity.elements)) {
          activity.elements.forEach(element => {
            const row = document.createElement('div');
            row.className = 'row';

            headers.forEach(col => {
              const p = document.createElement('p');

              switch (col.toLowerCase()) {
                case 'shape':
                  p.textContent = element.name || '';
                  break;

                case 'groups':
                  p.textContent = element.ratio || '';
                  break;

                case 'repetitions':
                  p.textContent = element.sets || '';
                  break;

                case 'images':
                  if (Array.isArray(element.attachments)) {
                    element.attachments.forEach(att => {
                      if (att.img && att.img !== '') {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'row-input';

                        const inputFile = document.createElement('input');
                        inputFile.type = 'file';
                        inputFile.id = `img-exercise-${att.id}`;
                        inputFile.name = 'file';
                        inputFile.setAttribute('data-code', att.id);

                        const hidId = document.createElement('input');
                        hidId.type = 'hidden';
                        hidId.name = 'id';
                        hidId.id = `code-${att.id}`;
                        hidId.value = att.id;

                        const hidState = document.createElement('input');
                        hidState.type = 'hidden';
                        hidState.name = 'state';
                        hidState.id = `state-${att.id}`;
                        hidState.value = 'img';

                        const label = document.createElement('label');
                        label.className = 'label-upload update';
                        label.setAttribute('for', `img-exercise-${att.id}`);
                        label.innerHTML = `<span>${tAddImg}</span>`;

                        wrapper.appendChild(inputFile);
                        wrapper.appendChild(hidId);
                        wrapper.appendChild(hidState);
                        wrapper.appendChild(label);

                        p.appendChild(wrapper);
                      };
                    });
                  };
                  break;

                case 'videos':
                  if (Array.isArray(element.attachments)) {
                    element.attachments.forEach(att => {
                      if (att.video && att.video !== '') {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'row-input';

                        const inputFile = document.createElement('input');
                        inputFile.type = 'file';
                        inputFile.id = `video-exercise-${att.id}`;
                        inputFile.name = 'file';
                        inputFile.setAttribute('data-code', att.id);

                        const hidId = document.createElement('input');
                        hidId.type = 'hidden';
                        hidId.name = 'id';
                        hidId.id = `code-${att.id}`;
                        hidId.value = att.id;

                        const hidState = document.createElement('input');
                        hidState.type = 'hidden';
                        hidState.name = 'state';
                        hidState.id = `state-${att.id}`;
                        hidState.value = 'video';

                        const label = document.createElement('label');
                        label.className = 'label-upload update';
                        label.setAttribute('for', `video-exercise-${att.id}`);
                        label.innerHTML = `<span>${tAddVideo}</span>`;

                        wrapper.appendChild(inputFile);
                        wrapper.appendChild(hidId);
                        wrapper.appendChild(hidState);
                        wrapper.appendChild(label);

                        p.appendChild(wrapper);
                      };
                    });
                  };
                  break;

                case 'delete':
                  const formDelete = document.createElement('form');
                  formDelete.method = 'post';
                  formDelete.action = destroy;

                  const cs = document.createElement('input');
                  cs.type = 'hidden';
                  cs.name = '_token';
                  cs.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '';

                  const idInput = document.createElement('input');
                  idInput.type = 'hidden';
                  idInput.name = 'id';
                  idInput.value = element.id || '';

                  const stateInput = document.createElement('input');
                  stateInput.type = 'hidden';
                  stateInput.name = 'state';
                  stateInput.value = 'coulmn';

                  const btnDelete = document.createElement('button');
                  btnDelete.type = 'submit';
                  btnDelete.innerHTML = `
                    <svg width="30" height="30" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                      <g stroke="var(--colorSVG1)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none">
                        <rect x="14" y="10" width="36" height="6" rx="2"/>
                        <rect x="26" y="8" width="12" height="4" rx="1" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="1"/>
                        <path d="M16 20 L48 20 L44 54 L20 54 Z" />
                        <path d="M24 26 L26 48" />
                        <path d="M32 26 L32 48" />
                        <path d="M40 26 L38 48" />
                        <path d="M20 54h24" stroke-width="3" stroke-linecap="round"/>
                      </g>
                    </svg>
                  `;
                  formDelete.appendChild(cs);
                  formDelete.appendChild(idInput);
                  formDelete.appendChild(stateInput);
                  formDelete.appendChild(btnDelete);
                  p.appendChild(formDelete);
                  break;
                default:
                  p.textContent = '';
              }; // end switch
              row.appendChild(p);
            }); // end headers.forEach
            bodyDiv.appendChild(row);
          }); // end elements.forEach
        }; // end if elements
        tableWrap.appendChild(bodyDiv);
        mainEl.appendChild(head);
        mainEl.appendChild(tableWrap);
        mainTable.appendChild(mainEl);
      }); // end activities forEach
    }).catch(error => {
      console.error('Error:', error);
    });
  } else if (type === "foode") {
    foodeForm.appendChild(codClient);
  };
};

(function () {
  if (typeof actionFormUpdate === 'undefined' || !actionFormUpdate) {
    console.error('actionFormUpdate غير معرف - عرفه مثلاً: const actionFormUpdate = "/update-media-endpoint";');
    return;
  };
  const saveLabel = window.tSaveLabel || 'Save';
  function getCsrfToken() {
    const m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.getAttribute('content') : '';
  };
  function shortName(name, max = 28) {
    if (!name) return '';
    return name.length > max ? name.slice(0, max - 3) + '...' : name;
  };
  async function onFileInputChange(ev) {
    const input = ev.target;
    if (!input || input.tagName !== 'INPUT' || input.type !== 'file') return;
    ev.preventDefault();
    const file = input.files && input.files[0];
    if (!file) return;
    const wrapper = input.closest('.row-input') || input.parentElement || document;
    let label = wrapper.querySelector(`label[for="${input.id}"]`) || wrapper.querySelector('label') || null;
    const displayName = shortName(file.name, 40);
    if (label) {
      const span = label.querySelector('span');
      if (span) span.textContent = displayName;
      else label.textContent = displayName;
    };
    try {
      const fd = new FormData();
      const fieldName = input.name || 'file';
      fd.append(fieldName, file, file.name);
      const idField = wrapper.querySelector('input[name="id"]') || wrapper.querySelector('input[id^="code-"]');
      const stateField = wrapper.querySelector('input[name="state"]');
      if (idField && idField.name) fd.append(idField.name, idField.value || '');
      if (stateField && stateField.name) fd.append(stateField.name, stateField.value || '');
      const parentForm = input.closest('form');
      if (parentForm) {
        Array.from(parentForm.elements).forEach(el => {
          if (!el.name) return;
          if (el.type === 'file') return;
          if (el.type === 'checkbox' || el.type === 'radio') {
            if (el.checked) fd.append(el.name, el.value);
          } else {
            fd.append(el.name, el.value || '');
          };
        });
      };
      // CSRF token
      const csrf = getCsrfToken();
      if (csrf) fd.append('_token', csrf);
      const origLabelText = label ? (label.innerText || label.textContent) : null;
      if (label) {
        label.style.opacity = '0.6';
      }
      const res = await fetch(actionFormUpdate, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: {
          'Accept': 'application/json'
        }
      });
      if (!res.ok) {
        const txt = await res.text().catch(() => res.statusText);
        console.error('Upload failed:', res.status, txt);
        if (label) {
          label.textContent = displayName + ' (failed)';
          setTimeout(() => {
            if (origLabelText) label.innerText = origLabelText;
            label.style.opacity = '1';
          }, 1500);
        };
        return;
      };
      const json = await res.json().catch(() => null);
      if (json && json.inserted_id) {
        if (idField && idField.name) {
          idField.value = json.inserted_id;
        };
      };
      if (label) {
        label.textContent = displayName;
        setTimeout(() => {
          try { label.textContent = saveLabel; } catch (e) { /* ignore */ }
          label.style.opacity = '1';
        }, 1200);
      };
    } catch (err) {
      console.error('Upload error:', err);
      const label2 = wrapper.querySelector(`label[for="${input.id}"]`) || wrapper.querySelector('label');
      if (label2) {
        label2.textContent = shortName(file.name) + ' (error)';
        setTimeout(() => { label2.style.opacity = '1'; }, 1500);
      };
    };
  }; // end onFileInputChange
  document.addEventListener('change', function (ev) {
    try {
      onFileInputChange(ev);
    } catch (e) {
      console.error('onFileInputChange threw:', e);
    };
  }, true);
  document.addEventListener('submit', function (ev) {
    const form = ev.target;
    if (!(form instanceof HTMLFormElement)) return;
    const anyFileInputs = form.querySelectorAll('input[type="file"]');
    if (anyFileInputs && anyFileInputs.length > 0) {
      ev.preventDefault();
    }
  }, true);
})(); // end IIFE


editProfile.forEach((button, index) => {
  button.addEventListener("click", (e) => {
    contentCard("edit", e);
  });
});

foodeProfile.forEach((button, index) => {
  button.addEventListener("click", (e) => {
    contentCard("foode", e);
  });
});

listProfile.forEach((button, index) => {
  button.addEventListener("click", (e) => {
    contentCard("list", e);
  });
});

deleteExercises.forEach((button, index) => {
  button.addEventListener("click", (e) => {
    contentCard("delete", e);
  });
});

const SVG_NS = 'http://www.w3.org/2000/svg';
let _mainInputCounter = 0;
let _exerciseCounter = 0;

function upload() {
  const fileInputs = document.querySelectorAll("input[type='file']");
  fileInputs.forEach((fileInput) => {
    fileInput.addEventListener("change", () => {
      const label = document.querySelector(`label[for="${fileInput.id}"]`);
      const span = label ? label.querySelector("span") : null;
      if (!span) return;
      if (fileInput.files.length > 0) {
        span.textContent = fileInput.files[0].name;
      } else {
        span.textContent = "لم يتم اختيار ملف";
      };
    });
  });
};

upload();

function update() {
  const fileInputs = document.querySelectorAll("input[type='file']");
  fileInputs.forEach((fileInput) => {
    fileInput.addEventListener("change", () => {
      let code = fileInput.getAttribute("data-code");
      const file = fileInput.files[0];
      const hiddenInputCode = document.querySelector(`input[id='code-${code}']`);
      const hiddenInputState = document.querySelector(`input[id='state-${code}']`);
      const formData = new FormData();
      formData.append('file', file);
      formData.append('id', hiddenInputCode.value);
      formData.append('state', hiddenInputState.value);
      fetch('/update-coulmn', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      }).then(response => response.text()).then(date => {
        console.log(date);
      }).catch(error => {
        console.error('Error:', error);
      });
    });
  });
};

btnUpdate.forEach((button) => {
  button.onclick = () => {
    update()
  };
});

function createLabelSVG() {
  const svg = document.createElementNS(SVG_NS, 'svg');
  svg.setAttribute('aria-hidden', 'true');
  svg.setAttribute('stroke', 'currentColor');
  svg.setAttribute('stroke-width', '2');
  svg.setAttribute('viewBox', '0 0 24 24');
  svg.setAttribute('fill', 'none');
  svg.setAttribute('xmlns', SVG_NS);

  const path1 = document.createElementNS(SVG_NS, 'path');
  path1.setAttribute('stroke-width', '2');
  path1.setAttribute('stroke', '#ffffff');
  path1.setAttribute('d', 'M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125');
  path1.setAttribute('stroke-linejoin', 'round');
  path1.setAttribute('stroke-linecap', 'round');

  const path2 = document.createElementNS(SVG_NS, 'path');
  path2.setAttribute('stroke-linejoin', 'round');
  path2.setAttribute('stroke-linecap', 'round');
  path2.setAttribute('stroke-width', '2');
  path2.setAttribute('stroke', '#ffffff');
  path2.setAttribute('d', 'M17 15V18M17 21V18M17 18H14M17 18H20');

  svg.appendChild(path1);
  svg.appendChild(path2);

  return svg;
}

function createRemoveSVG() {
  const svg = document.createElementNS(SVG_NS, 'svg');
  svg.setAttribute('xmlns', SVG_NS);
  svg.setAttribute('viewBox', '0 0 64 64');
  svg.setAttribute('width', '30');
  svg.setAttribute('height', '30');
  svg.setAttribute('aria-label', 'X in circle');

  const circle = document.createElementNS(SVG_NS, 'circle');
  circle.setAttribute('cx', '32');
  circle.setAttribute('cy', '32');
  circle.setAttribute('r', '28');
  circle.setAttribute('fill', 'none');

  const line1 = document.createElementNS(SVG_NS, 'line');
  line1.setAttribute('x1', '22');
  line1.setAttribute('y1', '22');
  line1.setAttribute('x2', '42');
  line1.setAttribute('y2', '42');
  line1.setAttribute('stroke', '#fff');
  line1.setAttribute('stroke-width', '5');
  line1.setAttribute('stroke-linecap', 'round');

  const line2 = document.createElementNS(SVG_NS, 'line');
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

  return svg;
}

function createMainInput(options = {}, state = null) {
  const mode = options.mode === 'show' ? 'show' : 'edit';
  _mainInputCounter += 1;
  const idx = _mainInputCounter;
  const mainInput = document.createElement('div');
  mainInput.className = 'main-input';

  const topLabel = document.createElement('label');
  topLabel.setAttribute('for', ' ');
  topLabel.textContent = mode === 'show' ?  isArabic ? "اسم الوجبة" : 'Name of the dish' : isArabic ? "اسم الشكل" : 'Shape name';
  mainInput.appendChild(topLabel);

  const row = document.createElement('div');
  const divInputs = document.createElement('div');
  const buttons = document.createElement('buttons');
  row.className = 'row-input';
  divInputs.className = 'inputs';
  buttons.className = 'buttons';

  if (mode == 'show') {
    const mealName = document.createElement('input');
    mealName.type = 'text';
    mealName.name = `meal[]`;
    mealName.placeholder = isArabic ? "اسم الوجبة" : "Meal name";
    row.appendChild(mealName);

    const howOften = document.createElement('input');
    howOften.type = 'text';
    howOften.name = `often[]`;
    howOften.placeholder = isArabic ? "عدد المرات" : "How often";
    row.appendChild(howOften);

    const quantity = document.createElement('input');
    quantity.type = 'text';
    quantity.name = `quantity[]`;
    quantity.placeholder = isArabic ? "الكميات " : "Quantity";
    row.appendChild(quantity);
  };

  if (mode === 'edit' && state == null) {
    const videoInput = document.createElement('input');
    videoInput.type = 'file';
    videoInput.id = `video-${idx}`;
    videoInput.name = `video[${_exerciseCounter}][]`;
    videoInput.setAttribute('accept', 'video/*');
    row.appendChild(videoInput);

    const shapeName = document.createElement('input');
    shapeName.type = 'text';
    shapeName.className = `shape`;
    shapeName.name = `shape[${_exerciseCounter}][]`;
    shapeName.placeholder = isArabic ? "اسم الشكل" : 'Shape name';
    divInputs.appendChild(shapeName);

    const groups = document.createElement('input');
    groups.type = 'text';
    groups.name = `groups[${_exerciseCounter}][]`;
    groups.placeholder = isArabic ? "عدد المجموعات" : "Number of groups";
    divInputs.appendChild(groups);

    const repetitions = document.createElement('input');
    repetitions.type = 'text';
    repetitions.name = `repetitions[${_exerciseCounter}][]`;
    repetitions.placeholder = isArabic ? "عدد التكرارات" : "Number of repetitions";
    divInputs.appendChild(repetitions);

    const videoLabel = document.createElement('label');
    videoLabel.setAttribute('for', videoInput.id);
    videoLabel.appendChild(createLabelSVG());
    const vspan = document.createElement('span');
    vspan.textContent = isArabic ? 'رفع فديو' : 'Add video';
    videoLabel.className = "label-upload";
    videoLabel.appendChild(vspan);
    buttons.appendChild(videoLabel);

    const imgInput = document.createElement('input');
    imgInput.type = 'file';
    imgInput.id = `img-${idx}`;
    imgInput.name = `img[${_exerciseCounter}][]`;
    imgInput.setAttribute('accept', 'image/*');
    buttons.appendChild(imgInput);

    const imgLabel = document.createElement('label');
    imgLabel.setAttribute('for', imgInput.id);
    imgLabel.appendChild(createLabelSVG());
    const ispan = document.createElement('span');
    ispan.textContent = isArabic ? 'رفع صورة' : 'Add img';
    imgLabel.className = "label-upload";
    imgLabel.appendChild(ispan);

    const divCheck = document.createElement('div');
    divCheck.className = "check-shape";
    buttons.appendChild(divCheck);

    buttons.appendChild(imgLabel);

    row.appendChild(buttons);
  }

  if (mode === 'edit' && state == "exercises") {
    _exerciseCounter++;
    const mainInputsExercises = document.querySelector('.main-inputs-exercises') || document.querySelector('.forms') || document.body;
    const mainInput = document.createElement('div');
    const labelMainInput = document.createElement('label');
    const rowInput = document.createElement('div');
    const inputExercise = document.createElement('input');
    const inputTimes = document.createElement('input');
    mainInput.className = 'main-input';
    labelMainInput.setAttribute('for', 'exercise-name');
    labelMainInput.innerHTML = isArabic ? "اسم الشكل" : "Exercise name";
    rowInput.className = 'row-input';
    inputExercise.type = 'text';
    inputExercise.id = 'exercise-name';
    inputExercise.className = '';
    inputExercise.name = `exercise_name[${_exerciseCounter}][]`;
    inputTimes.type = 'text';
    inputTimes.className = '';
    inputTimes.placeholder = 'times';
    inputTimes.name = `times[${_exerciseCounter}][]`;
    rowInput.appendChild(inputExercise);
    rowInput.appendChild(inputTimes);
    mainInput.appendChild(labelMainInput);
    mainInput.appendChild(rowInput);
    mainInputsExercises.appendChild(mainInput);

    const mainInputDescription = document.createElement('div');
    const labelMainInputDescription = document.createElement('label');
    const inputMainDescription = document.createElement('input');
    mainInputDescription.className = 'main-input';
    labelMainInputDescription.setAttribute("for", `description-${idx}`);
    labelMainInputDescription.innerHTML = isArabic ? "الوصف" : "Description";
    inputMainDescription.id = `description-${idx}`;
    inputMainDescription.name = `description[${_exerciseCounter}][]`;
    mainInputDescription.appendChild(labelMainInputDescription);
    mainInputDescription.appendChild(inputMainDescription);
    mainInputsExercises.appendChild(mainInputDescription);

    const videoInput = document.createElement('input');
    videoInput.type = 'file';
    videoInput.id = `video-${idx}`;
    videoInput.name = `video[${_exerciseCounter}][]`;
    videoInput.setAttribute('accept', 'video/*');
    row.appendChild(videoInput);

    const shapeName = document.createElement('input');
    shapeName.type = 'text';
    shapeName.className = `shape`;
    shapeName.name = `shape[${_exerciseCounter}][]`;
    shapeName.placeholder = isArabic ? "اسم الشكل" : 'Shape name';
    divInputs.appendChild(shapeName);

    const groups = document.createElement('input');
    groups.type = 'text';
    groups.name = `groups[${_exerciseCounter}][]`;
    groups.placeholder = isArabic ? "عدد المجموعات" : "Number of groups";
    divInputs.appendChild(groups);

    const repetitions = document.createElement('input');
    repetitions.type = 'text';
    repetitions.name = `repetitions[${_exerciseCounter}][]`;
    repetitions.placeholder = isArabic ? "عدد التكرارات" : "Number of repetitions";
    divInputs.appendChild(repetitions);

    const videoLabel = document.createElement('label');
    videoLabel.setAttribute('for', videoInput.id);
    videoLabel.appendChild(createLabelSVG());
    const vspan = document.createElement('span');
    vspan.textContent = isArabic ? 'رفع فديو' : 'Add video';
    videoLabel.className = "label-upload";
    videoLabel.appendChild(vspan);
    buttons.appendChild(videoLabel);

    const imgInput = document.createElement('input');
    imgInput.type = 'file';
    imgInput.id = `img-${idx}`;
    imgInput.name = `img[${_exerciseCounter}][]`;
    imgInput.setAttribute('accept', 'image/*');
    buttons.appendChild(imgInput);

    const imgLabel = document.createElement('label');
    imgLabel.setAttribute('for', imgInput.id);
    imgLabel.appendChild(createLabelSVG());
    const ispan = document.createElement('span');
    ispan.textContent = isArabic ? 'رفع صورة' : 'Add img';
    imgLabel.className = "label-upload";
    imgLabel.appendChild(ispan);
    buttons.appendChild(imgLabel);

  }

  const btn = document.createElement('button');
  btn.type = 'button';
  btn.className = 'remove';
  btn.appendChild(createRemoveSVG());
  btn.addEventListener('click', function () {
    const parent = btn.closest('.main-input');
    if (parent) parent.remove();
    _mainInputCounter = Math.max(0, _mainInputCounter - 1);
  });
  divInputs.appendChild(btn);

  row.appendChild(divInputs);

  if (mode !== "show") row.appendChild(buttons);

  mainInput.appendChild(row);
  return mainInput;
}

function createVerifiedCustomerElement(check, buttons) {
  const isArabicFlag = (typeof window !== 'undefined' && Object.prototype.hasOwnProperty.call(window, 'isArabic')) ? !!window.isArabic : false;
  let container = buttons;
  try {
    if (!container) {
      const active = document.activeElement;
      if (active && typeof active.closest === 'function') {
        const mainInput = active.closest('.main-input');
        if (mainInput) container = mainInput.querySelector('.buttons') || null;
      }
    }
  } catch (err) {
    container = null;
  }
  if (!container) {
    container = document.querySelector('.main-input .buttons') || document.querySelector('.verified-customer-container') || null;
  }
  if (!container) {
    console.warn('createVerifiedCustomerElement: no container found to append to.');
    return;
  }

  const existing = container.querySelector('.check-shape');
  if (existing) existing.remove();

  const wrapper = document.createElement('div');
  wrapper.className = 'check-shape';

  const svgHtml = `
    <svg viewBox="0 0 64 64" width="24" height="24" aria-label="${check === 'done' ? 'smaller curvy check in circle' : 'X in circle'}" role="img" xmlns="http://www.w3.org/2000/svg">
      ${check === 'done'
        ? `<circle cx="32" cy="32" r="28" fill="var(--colorCheck)"></circle>
          <path d="M20 34 Q26 40 30 42 Q36 34 44 24" fill="none" stroke="#fff" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"></path>`
        : `<circle cx="32" cy="32" r="28" fill="var(--colorError)"></circle>
          <line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"></line>
          <line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"></line>`
      }
    </svg>
  `;
  wrapper.innerHTML = svgHtml;
  const p = document.createElement('p');
  p.textContent = check === 'done' ? (isArabicFlag ? 'النموذج متاح' : 'The form is available') : (isArabicFlag ? 'النموذج غير متاح' : 'Form not available');
  wrapper.appendChild(p);
  container.appendChild(wrapper);
}

btnAddExercises.onclick = () => {
  mainInputsExercises.appendChild(createMainInput({mode: 'edit'}, 'exercises'));
  upload();
};

btnAddShapeExercises.onclick = () => {
  mainInputsExercises.appendChild(createMainInput({mode: 'edit'}));
  upload();
};

document.addEventListener('input', (e) => {
  const el = e.target;
  if (!el || !el.classList) return;
  if (!el.classList.contains('shape')) return;

  if (el._shapeDebounceTimer) clearTimeout(el._shapeDebounceTimer);
  el._shapeDebounceTimer = setTimeout(() => {
    (async () => {
      const name = (el.value || '').trim();
      const buttons = el.closest('.main-input') ? el.closest('.main-input').querySelector('.buttons') : null;
      if (buttons) {
        const old = buttons.querySelector('.check-shape');
        if (old) old.remove();
        const oldMsg = buttons.querySelector('.form-unavailable');
        if (oldMsg) oldMsg.remove();
      };
      if (!name) return;
      if (buttons) {
        if (!buttons.dataset.checkId) {
          buttons.dataset.checkId = 'chk-' + Math.random().toString(36).slice(2);
        }
        el.dataset.checkId = buttons.dataset.checkId;
      } else {
        el.dataset.checkId = '';
      };
      const expectedCheckId = el.dataset.checkId;

      // CSRF token من الميتا
      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
      // console.log('check-shape sending name:', name);
      fetch('/check-shape', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf || '',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ name })
      }).then(response => {
        if (!response.ok) {
          console.warn('check-shape response not ok', response.status);
        };
        return response.text();
      }).then(text => {
        const raw = (text || '').trim();
        // console.log('check-shape raw response:', raw);
        let isAvailable = false;
        if (raw) {
          try {
            const json = JSON.parse(raw);
            if (json && typeof json === 'object') {
              let val;
              if ('result' in json) val = json.result;
              else if ('available' in json) val = json.available;
              else if ('success' in json) val = json.success;
              else val = json;
              if (typeof val === 'boolean') isAvailable = val;
              else if (typeof val === 'number') isAvailable = val !== 0;
              else if (typeof val === 'string') isAvailable = ['yes', 'true', '1'].includes(val.trim().toLowerCase());
              else isAvailable = false;
            } else {
              const s = String(json).trim().toLowerCase();
              isAvailable = ['yes', 'true', '1'].includes(s);
            }
          } catch (err) {
            const s = raw.toLowerCase();
            isAvailable = ['yes', 'true', '1'].includes(s);
          };
        };
        // console.log('check-shape parsed result:', isAvailable);
        if (buttons && expectedCheckId && buttons.dataset.checkId === expectedCheckId) {
          if (typeof createVerifiedCustomerElement === 'function') {
            try {
              createVerifiedCustomerElement(isAvailable ? 'done' : 'error', buttons);
            } catch (err) {
              console.error('createVerifiedCustomerElement error', err);
            };
          };
        } else {
          console.warn('check-shape: response arrived but target container changed or missing — skipping append.');
        };
      }).catch(error => {
        console.error('check-shape fetch/read error:', error);
        if (buttons && expectedCheckId && buttons.dataset.checkId === expectedCheckId) {
          if (typeof createVerifiedCustomerElement === 'function') {
            try {
              createVerifiedCustomerElement('error', buttons);
            } catch (err) {
              console.error('createVerifiedCustomerElement error (after fetch failure):', err);
            };
          };
        } else {
          console.warn('check-shape: fetch failed and target container changed or missing — skipping append.');
        };
      });
    })();
  }, 350);
});

btnAddShapeFoods.onclick = () => {
  mainInputsFoods.appendChild(createMainInput({mode: 'show'}));
  upload();
};
