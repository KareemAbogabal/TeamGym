let html = document.querySelector("html");
let isArabic = html.getAttribute("lang") === "ar";
let systemsCard = document.querySelector(".systems-card");
let mainCard = document.querySelector(".main-card-systems");
let systemCard = document.querySelectorAll(".system");
let btnDefultSystem = document.querySelector(".defult-system");
let btnEditSystem = document.querySelectorAll(".btn-edit-system");
let mainCardsDefult = document.querySelector('.main-card[data-state="defult"]');
let mainCardsSystem = document.querySelector('.main-card[data-state="system"]');
let systemForm = document.querySelector('.system-form');
let mainCardsEdit = document.querySelector('.main-card[data-state="edit"]');
let mainCardsEditParagraph = document.querySelectorAll('.main-card[data-state="edit"] p');
let mainCardsEditH1 = document.querySelector('.main-card[data-state="edit"] h1');
let mainCardsShow = document.querySelector('.main-card[data-state="show"]');
let btnEditProduct = document.querySelectorAll('.edit-product');
let btnAddSystem = document.querySelector('.add-system');
let nameProduct = document.querySelectorAll('.name-product');
let nameProductInput = document.getElementById('name-product-update');
let priceProduct = document.querySelectorAll('.price-product');
let priceProductInput = document.getElementById('price-product-update');
let contentProduct = document.querySelectorAll('.content-product');
let contentProductInput = document.getElementById('content-product-update');
let discountProduct = document.querySelectorAll('.discount-product');
let discountProductInput = document.getElementById('discount-product-update');
let imgProduct = document.querySelectorAll('.img-product');
let imgProductInput = document.getElementById('img-product');
let input = document.getElementById('upload-product');
let products = document.querySelector('.products');
let product = Array.from(products.querySelectorAll('.product'));
let alignButtons = Array.from(document.querySelectorAll('.align-sec-3'));
let mainInputsSystem = document.querySelector(`.main-inputs-system`);
let systemFeature = document.querySelector(`.system-feature`);
let currentObjectUrls = null;

const SVG_NS = 'http://www.w3.org/2000/svg';
let _mainInputCounter = 0;

function createRememberCheckbox() {
  const id = 'remember';
  const wrapper = document.createElement('label');
  wrapper.className = 'container';
  const input = document.createElement('input');
  input.type = 'checkbox';
  input.name = "state_feature";
  input.id = id;
  const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
  svg.setAttribute('viewBox', '0 0 64 64');
  svg.setAttribute('height', '1em');
  svg.setAttribute('width', '1em');
  const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
  path.setAttribute('d', 'M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16');
  path.setAttribute('pathLength', '575.0541381835938');
  path.classList.add('path');
  svg.appendChild(path);
  wrapper.appendChild(input);
  wrapper.appendChild(svg);
  return wrapper;
};

btnEditSystem.forEach((button, index) => {
  button.onclick = () => {
    systemsCard.innerHTML = "";
    const systemElement = button.closest('.system');
    const clone = systemElement.cloneNode(true);
    clone.querySelectorAll('input[id]').forEach((inp, i) => {
      const oldId = inp.id;
      const newId = `${oldId}-cl-${Date.now()}-${i}`; // id جديد وفريد
      inp.id = newId;
      clone.querySelectorAll(`label[for="${oldId}"]`).forEach(l => l.setAttribute('for', newId));
    });
    systemsCard.appendChild(clone);
    (function syncFeatureCheckboxesWithHidden(cloneNode){
      cloneNode.querySelectorAll('.add-features > p').forEach(p => {
        const checkbox = p.querySelector('input[type="checkbox"]');
        if (!checkbox) return;
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'feature[]';
        hidden.value = checkbox.checked ? 'true' : 'false';
        p.insertBefore(hidden, p.firstChild);
        checkbox.removeAttribute('name');
        checkbox.addEventListener('change', () => {
          hidden.value = checkbox.checked ? 'true' : 'false';
        });
        hidden.value = checkbox.checked ? 'true' : 'false';
      });
    })(clone);
    const ribbon = clone.querySelector('.ribbon');
    const titleElem = ribbon ? ribbon.nextElementSibling : clone.querySelector('h1');
    if (titleElem) {
      titleElem.setAttribute('contenteditable', 'true');
      titleElem.classList.add('editable-inline');
    };
    const amountH1 = clone.querySelector('.amount h1');
    const amountP = clone.querySelector('.amount p');
    if (amountH1) {
      amountH1.setAttribute('contenteditable', 'true');
      amountH1.classList.add('editable-inline');
    };
    if (amountP) {
      amountP.setAttribute('contenteditable', 'true');
      amountP.classList.add('editable-inline');
    };
    const form = systemsCard.closest('form');
    if (form) {
      ['system_title','system_price','system_period'].forEach(name => {
        const ex = form.querySelector(`input[name="${name}"]`);
        if (ex) ex.remove();
      });
      const hiddenTitle = document.createElement('input');
      hiddenTitle.type = 'hidden';
      hiddenTitle.name = 'system_title';
      hiddenTitle.value = titleElem ? titleElem.innerText.trim() : '';
      const hiddenPrice = document.createElement('input');
      hiddenPrice.type = 'hidden';
      hiddenPrice.name = 'system_price';
      hiddenPrice.value = amountH1 ? amountH1.innerText.trim() : '';
      const hiddenPeriod = document.createElement('input');
      hiddenPeriod.type = 'hidden';
      hiddenPeriod.name = 'system_duration';
      hiddenPeriod.value = amountP ? amountP.innerText.trim() : '';
      form.appendChild(hiddenTitle);
      form.appendChild(hiddenPrice);
      form.appendChild(hiddenPeriod);
      const syncHidden = () => {
        hiddenTitle.value  = titleElem ? titleElem.innerText.trim() : '';
        hiddenPrice.value  = amountH1 ? amountH1.innerText.trim() : '';
        hiddenPeriod.value = amountP ? amountP.innerText.trim() : '';
      };
      if (titleElem) titleElem.addEventListener('input', syncHidden);
      if (amountH1) amountH1.addEventListener('input', syncHidden);
      if (amountP) amountP.addEventListener('input', syncHidden);
      form.addEventListener('submit', syncHidden);
    };
    let features = document.querySelector(".systems-card .features");
    let addFeatures = document.querySelector(".systems-card .add-features");
    let buttonAdd = document.createElement("p");
    buttonAdd.innerHTML = '<i class="material-symbols-outlined">check_small</i> Add feature';
    buttonAdd.className = "add-feature";
    features.appendChild(buttonAdd);
    buttonAdd.onclick = () => {
      let feature = document.createElement("p");
      feature.appendChild(createRememberCheckbox());
      let featureInput = document.createElement("input");
      featureInput.type = 'text';
      featureInput.name = 'feature_new[]';
      feature.appendChild(featureInput);
      const removeBtn = document.createElement('button');
      removeBtn.type = 'button';
      removeBtn.className = 'remove';
      removeBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="18" height="18" aria-label="X in circle">
          <circle cx="32" cy="32" r="28" fill="none" />
          <line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
          <line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
        </svg>`;
      removeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        feature.remove();
      });
      feature.appendChild(removeBtn);
      addFeatures.appendChild(feature);
    };
  };
});


btnEditProduct.forEach((button, index) => {
  button.onclick = () => {
    let code = button.getAttribute("data-code");
    let inputCode = document.createElement("input");
    inputCode.type = "hidden";
    inputCode.name = "code_product";
    inputCode.value = code;
    let form = mainCardsEdit.querySelector("form");
    imgProductInput.src = imgProduct[index].src;
    nameProductInput.value = nameProduct[index].textContent;
    priceProductInput.value = priceProduct[index].textContent;
    contentProductInput.value = contentProduct[index].textContent;
    discountProductInput.value = discountProduct[index].textContent;
    form.appendChild(inputCode);
  };
});

function handleImageChange(imgElem, fileInput, opts = {}) {
  fileInput.addEventListener('change', (e) => {
    if (typeof opts.onStart === 'function') opts.onStart();
    const originalSrc = imgElem.src || '';
    const file = e.target.files && e.target.files[0];
    if (!file) {
      if (currentObjectUrls) {
        URL.revokeObjectURL(currentObjectUrls);
        currentObjectUrls = null;
      }
      imgElem.src = originalSrc;
      return;
    };
    if (!file.type.startsWith('image/')) {
      alert('الرجاء اختيار ملف صورة (jpg, png, ...).');
      (opts.resetInput || input).value = '';
      return;
    };
    if (currentObjectUrls) {
      URL.revokeObjectURL(currentObjectUrls);
      currentObjectUrls = null;
    };
    currentObjectUrls = URL.createObjectURL(file);
    imgElem.src = currentObjectUrls;
    imgElem.alt = file.name;
  });
};

handleImageChange(imgProductInput, input);

function updateActiveCard(elementPearntScroll, elementsScroll, nameClassElements = null, alignButtons = null, nameClassButtons = null) {
  const containerRect = elementPearntScroll.getBoundingClientRect();
  const containerCenter = containerRect.left + containerRect.width / 2;
  let closestCard = null;
  let closestIndex = 0;
  let minDistance = Infinity;
  elementsScroll.forEach((card, idx) => {
    const rect = card.getBoundingClientRect();
    const cardCenter = rect.left + rect.width / 2;
    const distance = Math.abs(containerCenter - cardCenter);
    if (distance < minDistance) {
      minDistance = distance;
      closestCard = card;
      closestIndex = idx;
    };
  });
  const firstRect = elementsScroll[0].getBoundingClientRect();
  if (firstRect.left >= containerRect.left && firstRect.left <= containerRect.left + 20) {
    closestCard = elementsScroll[0];
    closestIndex = 0;
  };
  const last = elementsScroll[elementsScroll.length - 1];
  const lastRect = last.getBoundingClientRect();
  if (lastRect.right <= containerRect.right && lastRect.right >= containerRect.right - 20) {
    closestCard = last;
    closestIndex = elementsScroll.length - 1;
  };
  if (nameClassElements !== null) {
    elementsScroll.forEach(card => card.classList.toggle(nameClassElements, card === closestCard));
  };
  if (nameClassButtons !== null && Array.isArray(alignButtons)) {
    alignButtons.forEach((btn, idx) => btn.classList.toggle(nameClassButtons, idx === closestIndex));
  };
};

let rafId = null;

products.addEventListener('scroll', () => {
  if (rafId !== null) {
    cancelAnimationFrame(rafId);
  };
  rafId = requestAnimationFrame(() => {
    updateActiveCard(products, product, 'active', alignButtons, 'show-align');
    rafId = null;
  });
});


btnAddSystem.onclick = () => {
};

btnDefultSystem.onclick = () => {
};

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
  topLabel.textContent = isArabic ? "مميزات النظام" : "system feature";
  mainInput.appendChild(topLabel);

  const row = document.createElement('div');
  const divInputs = document.createElement('div');
  const buttons = document.createElement('buttons');
  row.className = 'row-input';
  divInputs.className = 'inputs';
  buttons.className = 'buttons';

  if (mode == 'show') {
    console.log("yes");

    const featureName = document.createElement('input');
    featureName.type = 'text';
    featureName.name = `feature[]`;
    featureName.placeholder = isArabic ? "اسم الميزة" : "Feature name";
    row.appendChild(featureName);
  };

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

  mainInput.appendChild(row);
  return mainInput;
}

systemFeature.onclick = () => {
  mainInputsSystem.appendChild(createMainInput({mode: 'show'}));
};

