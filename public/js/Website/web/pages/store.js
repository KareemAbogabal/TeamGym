let search = document.querySelector(".inp-search");
let btnSearch = document.querySelector(".btn-search");
let btnSec = document.querySelectorAll(".btn-sec");
let section2 = document.querySelector(".section-2");
let section3 = document.querySelector(".section-3");
let texts = document.querySelector(".section-1 .content .texts");
let text = texts.querySelector("p");
let products = document.querySelectorAll(".section-2 .products .thumbs .thumb");
let minus = document.querySelector(".minus");
let quantity = document.querySelector(".quantity");
let plus = document.querySelector(".plus");
let productsPhoto = document.querySelector(".photo img");
let productsTitle = document.querySelector(".title");
let productsDescription = document.querySelector(".meta-text");
let productsPrice = document.querySelector(".price");
let productsDiscount = document.querySelector(".old-price");
let productsCode = 0;
let badge = document.querySelector(".badge");
let basket = document.querySelector(".basket");
let shoppingProducts = document.querySelector("article");
let storageKey = 'shopping_cart_items_v1';
let productChoose = document.querySelector('.product-choose');
let addButtons = document.querySelectorAll('.btn.add');
let removeAllProduct = document.querySelectorAll('.remove');
let priceTotalProduct = document.querySelectorAll('.price-total');
let formBuyProducts = document.querySelector('.main-card[data-state="show"] .form-buy');
let formBuy = document.querySelector('.main-card[data-state="show"] .form-buy .inputs');
let mainCardsShow = document.querySelector('.main-card[data-state="show"]');
let showCard = document.querySelectorAll(".show");
let closeCard = document.querySelector(".close-card");
let contentText = text.textContent;
let radius = 110;
let centerX = 0;
let centerY = radius;
let totalAngle = Math.PI;
let stepAngle = totalAngle / (contentText.length - 1);
let factor = 0.8;
let maxQuantity = 0;
let quantityProduct = 1;
window.maxQuantity = window.maxQuantity || 1;
text.innerHTML = "";

btnSearch.addEventListener("click", () => {
  products.forEach((product, index) => {
    const query = (search.value || '').trim().toLowerCase();
    const name = (product.getAttribute("data-title") || '').trim().toLowerCase();
    if (name === query || name.includes(query)) {
      window.scrollBy({
        top: 800,
        behavior: "smooth"
      });
      product.click();
    };
  });
});

basket.onclick = (e) => {
  e.stopPropagation();
  shoppingProducts.classList.toggle("show-shopping-carts");
  if (window.innerWidth <= 1115) {
    main.classList.remove("show-main");
    let menuInput = menu.querySelector("input");
    menuInput.checked = false;
  };
};


document.addEventListener("click", (e) => {
  if (shoppingProducts.classList.contains("show-shopping-carts")) {
    shoppingProducts.classList.remove("show-shopping-carts");
  };
});

shoppingProducts.addEventListener("click", (e) => {
  e.stopPropagation();
});

for (let i = 0; i < contentText.length; i++) {
  const ch = contentText[i];
  const angle = stepAngle * i * factor;
  const x = radius * Math.cos(angle - Math.PI / 2);
  const y = radius * Math.sin(angle - Math.PI / 2);
  let el = document.createElement("p");
  el.textContent = ch;
  el.style.position = "absolute";
  el.style.left = centerX + x + "px";
  el.style.top = centerY + y + "px";
  el.style.margin = "0";
  el.style.padding = "0";
  el.style.whiteSpace = "pre";
  el.style.transform = `rotate(${angle * 180 / Math.PI}deg)`;
  el.style.transformOrigin = "center bottom";
  el.style.display = "inline-block";
  texts.appendChild(el);
};

function animationDot() {
  const section1 = document.querySelector('.section-1');
  const imgWrap = document.querySelector('.section-1 .img');
  const count = 250;            // عدد النقاط
  const dotSize = 6;            // قطر النقطة بالـpx
  const repelRadius = 140;      // نصف قطر التأثير حول الماوس (بالبكسل)
  const maxPush = 70;           // أقصى مقدار إزاحة عند أقصى قرب
  const minRadiusOffset = 200;  // مسافة داخل نصف قطر الـSVG لتبدأ النقاط
  const circle = 0.09;          // نسبة توسع الدائرة الخارجية
  const svg = imgWrap.querySelector('svg');
  const dotsContainer = document.createElement('div');
  dotsContainer.className = 'dots-container';
  imgWrap.appendChild(dotsContainer);
  function getRects() {
    const svgRect = svg.getBoundingClientRect();
    const imgRect = imgWrap.getBoundingClientRect();
    return { svgRect, imgRect };
  };
  function rand(min, max){ return min + Math.random() * (max - min); }
  function calcRadii(svgRect){
    const base = Math.min(svgRect.width, svgRect.height) / 2;
    const minR = Math.max(0, base - minRadiusOffset);
    const maxR = base + (base * circle);
    return { minR, maxR };
  };
  const dots = [];
  function createDots() {
    const { svgRect, imgRect } = getRects();
    const centerPageX = svgRect.left + svgRect.width / 2;
    const centerPageY = svgRect.top + svgRect.height / 2;
    const radii = calcRadii(svgRect);
    for (let i = 0; i < count; i++) {
      const angle = rand(0, Math.PI * 2);
      const r = Math.sqrt(Math.random()) * (radii.maxR - radii.minR) + radii.minR;
      const basePageX = centerPageX + Math.cos(angle) * r;
      const basePageY = centerPageY + Math.sin(angle) * r;
      const left = basePageX - imgRect.left - (dotSize / 2);
      const top  = basePageY - imgRect.top  - (dotSize / 2);
      const wrap = document.createElement('div');
      wrap.className = 'dot-wrap';
      wrap.style.left = left + 'px';
      wrap.style.top = top + 'px';
      wrap.style.width = dotSize + 'px';
      wrap.style.height = dotSize + 'px';
      const dot = document.createElement('div');
      dot.className = 'dot';
      dot.style.width = dotSize + 'px';
      dot.style.height = dotSize + 'px';
      const fx1 = rand(-8, 8).toFixed(2) + 'px';
      const fy1 = rand(-8, 8).toFixed(2) + 'px';
      const fx2 = rand(-14, 14).toFixed(2) + 'px';
      const fy2 = rand(-14, 14).toFixed(2) + 'px';
      const s1  = (rand(0.8, 1.1)).toFixed(2);
      const s2  = (rand(0.8, 1.3)).toFixed(2);
      const o1  = (rand(0.6, 1)).toFixed(2);
      const o2  = (rand(0.6, 1)).toFixed(2);
      const dur = rand(3.2, 8).toFixed(2) + 's';
      const delay = (rand(-4, 2)).toFixed(2) + 's';
      dot.style.setProperty('--fx1', fx1);
      dot.style.setProperty('--fy1', fy1);
      dot.style.setProperty('--fx2', fx2);
      dot.style.setProperty('--fy2', fy2);
      dot.style.setProperty('--s1', s1);
      dot.style.setProperty('--s2', s2);
      dot.style.setProperty('--o1', o1);
      dot.style.setProperty('--o2', o2);
      dot.style.setProperty('--dur', dur);
      dot.style.setProperty('--delay', delay);
      wrap.appendChild(dot);
      dotsContainer.appendChild(wrap);
      dots.push({
        wrap, dot,
        basePageX, basePageY,
        baseLeft: left, baseTop: top,
        pushed: false
      });
    };
  };
  function recalcPositions() {
    dotsContainer.innerHTML = '';
    dots.length = 0;
    createDots();
  };
  function onMouseMove(e) {
    const mouseX = e.pageX;
    const mouseY = e.pageY;
    for (let i = 0; i < dots.length; i++){
      const d = dots[i];
      let dx = d.basePageX - mouseX;
      let dy = d.basePageY - mouseY;
      let dist = Math.sqrt(dx * dx + dy * dy);
      if (dist < 1) dist = 1;
      if (dist < repelRadius) {
        const strength = (repelRadius - dist) / repelRadius;
        const push = strength * maxPush;
        const pushX = (dx / dist) * push;
        const pushY = (dy / dist) * push;
        d.wrap.style.transition = 'transform 160ms cubic-bezier(.22,.9,.3,1)';
        d.wrap.style.transform = `translate(${pushX.toFixed(2)}px, ${pushY.toFixed(2)}px)`;
        d.pushed = true;
      } else {
        if (d.pushed) {
          d.wrap.style.transition = 'transform 400ms cubic-bezier(.22,.9,.3,1)';
          d.wrap.style.transform = 'translate(0px, 0px)';
          d.pushed = false;
        };
      };
    };
  };
  function onMouseLeave() {
    for (let i = 0; i < dots.length; i++){
      const d = dots[i];
      d.wrap.style.transition = 'transform 450ms cubic-bezier(.22,.9,.3,1)';
      d.wrap.style.transform = 'translate(0px, 0px)';
      d.pushed = false;
    };
  };
  createDots();
  section1.addEventListener('mousemove', onMouseMove);
  section1.addEventListener('mouseleave', onMouseLeave);
  window.addEventListener('resize', () => {
    clearTimeout(window._dotsResizeTimer);
    window._dotsResizeTimer = setTimeout(recalcPositions, 220);
  });
};

document.addEventListener("DOMContentLoaded", function () {
  products[0].click();
  window.onscroll = () => {
    if (window.scrollY === 0) {
      navmove.classList.remove("blur-nav");
      btnSec[0].classList.add("active");
      btnSec[1].classList.remove("active");
    } else {
      navmove.classList.add("blur-nav");
    };
    if (window.scrollY >= section2.offsetTop - 300) {
      btnSec[0].classList.remove("active");
      btnSec[1].classList.add("active");
    };
  };
  animationDot();
});

products.forEach((product, index) => {
  let status = index + 1;
  badge.innerHTML = `1/${products.length}`;
  product.onclick = () => {
    products.forEach(product => {
      product.classList.remove("choose");
    });
    badge.innerHTML = `${status}/${products.length}`;
    product.classList.add("choose");
    let productImg = product.querySelector("img");
    let productCode = product.getAttribute("data-code");
    let productTitle = product.getAttribute("data-title");
    let productDescription = product.getAttribute("data-description");
    let productAmount = product.getAttribute("data-amount");
    let productDiscount = product.getAttribute("data-discount");
    let productQuantity = product.getAttribute("data-quantity");
    productsCode = productCode;
    productsPhoto.src = productImg.src;
    productsTitle.innerHTML = productTitle;
    productsDescription.innerHTML = productDescription;
    productsPrice.innerHTML = `${productAmount} EGP`;
    productsDiscount.innerHTML = `${productDiscount} EGP`;
    productsDiscount.innerHTML = `${productDiscount} EGP`;
    maxQuantity = productQuantity;
  };
});

function count(state) {
  if (state == "minus") {
    if (quantityProduct != 1) {
      --quantityProduct;
    };
  } else {
    if (quantityProduct != maxQuantity) {
      ++quantityProduct;
    };
  };
  console.log(quantityProduct);
  quantity.innerHTML = quantityProduct;
};

minus.onclick = () => {
  count("minus");
};

plus.onclick = () => {
  count("plus");
};

const getCart = () => {
  try {
    const raw = localStorage.getItem(storageKey);
    const products = raw ? JSON.parse(raw) : [];
    return Array.isArray(products) ? products : [];
  } catch (e) {
    return [];
  }
};

const setCart = (items) => {
  localStorage.setItem(storageKey, JSON.stringify(items));
};

const cleanNum = (s) => (s || s === 0) ? String(s).replace(/[^\d\.\-]/g, '').trim() : '';

const updateTotal = () => {
  const items = getCart();
  let total = 0;
  items.forEach(i => {
    const amt = parseFloat(cleanNum(i.amount || 0)) || 0;
    const qty = Number(i.quantity || 1) || 1;
    total += amt * qty;
  });
  localStorage.setItem('shopping_cart_total_v1', String(total));
  if (priceTotalProduct && priceTotalProduct.length) {
    const formatted = total ? `${Number(total).toLocaleString()} EGP` : '0 EGP';
    priceTotalProduct.forEach(el => { el.textContent = formatted; });
  };
  return total;
};

function removeInputsInFormBuy(code) {
  let inputs = document.querySelectorAll('.main-card[data-state="show"] .form-buy .inputs input');
  inputs.forEach(input => {
    let check = input.getAttribute("data-code-product");
    if (check == code) {
      input.remove();
    };
  });
};

function addInputsInFormBuy(item) {
  let typeCard = document.createElement("input");
  let codeOrderCard = document.createElement("input");
  let orderNameCard = document.createElement("input");
  let amountCard = document.createElement("input");
  let quantityCard = document.createElement("input");
  typeCard.setAttribute("data-code-product", item.code);
  codeOrderCard.setAttribute("data-code-product", item.code);
  orderNameCard.setAttribute("data-code-product", item.code);
  amountCard.setAttribute("data-code-product", item.code);
  quantityCard.setAttribute("data-code-product", item.code);
  typeCard.type = "hidden";
  codeOrderCard.type = "hidden";
  orderNameCard.type = "hidden";
  amountCard.type = "hidden";
  quantityCard.type = "hidden";
  typeCard.name = "type[]";
  codeOrderCard.name = "code[]";
  orderNameCard.name = "order_name[]";
  amountCard.name = "amount[]";
  quantityCard.name = "quantity[]";
  typeCard.value = "supplement";
  codeOrderCard.value = item.code;
  orderNameCard.value = item.title;
  amountCard.value = item.amount;
  quantityCard.value = item.quantity;
  formBuy.appendChild(typeCard);
  formBuy.appendChild(codeOrderCard);
  formBuy.appendChild(orderNameCard);
  formBuy.appendChild(amountCard);
  formBuy.appendChild(quantityCard);
};

const makeThumb = (item) => {
  const wrap = document.createElement('div');
  wrap.className = 'thumb';
  wrap.dataset.title = item.title;
  wrap.dataset.description = item.description;
  wrap.dataset.amount = item.amount;
  wrap.dataset.discount = item.discount;
  wrap.dataset.quantity = item.quantity;

  const img = document.createElement('img');
  img.src = item.img;
  img.alt = item.title || 'thumb';
  wrap.appendChild(img);

  const btn = document.createElement('button');
  btn.type = 'button';
  btn.setAttribute('aria-label', 'remove');
  btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="30" height="30"><circle cx="32" cy="32" r="28" fill="none"/><line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/><line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/></svg>`;
  btn.addEventListener('click', (e) => {
    e.stopPropagation();
    removeFromCart(item.id);
    removeInputsInFormBuy(item.code);
  });
  wrap.appendChild(btn);

  wrap.addEventListener('click', () => {
    if (productsPhoto) productsPhoto.src = item.img;
    if (productsTitle) productsTitle.innerHTML = item.title;
    if (productsDescription) productsDescription.innerHTML = item.description;
    if (productsPrice) productsPrice.innerHTML = item.amount ? `${item.amount} EGP` : '';
    if (productsDiscount) productsDiscount.innerHTML = item.discount ? `${item.discount} EGP` : '';
    maxQuantity = item.quantity || 1;
    quantityProduct = 1;
    if (quantity) quantity.innerHTML = quantityProduct;
  });
  return wrap;
};

const renderCart = () => {
  if (!productChoose) return;
  const items = getCart();
  if (!items || items.length === 0) {
    productChoose.innerHTML = '<p>There is no product</p>';
  } else {
    productChoose.innerHTML = '';
    items.forEach(i => productChoose.appendChild(makeThumb(i)));
  };
  if (basket) {
    if (items && items.length) {
      basket.setAttribute('data-product', 'true');
      basket.classList.add('has-product');
    } else {
      basket.removeAttribute('data-product');
      basket.classList.remove('has-product');
    };
  };
  updateTotal();
};

const addCurrentProductToCart = () => {
  const img = (productsPhoto && productsPhoto.src) ? productsPhoto.src : '';
  const title = (productsTitle && productsTitle.textContent) ? productsTitle.textContent.trim() : '';
  const description = (productsDescription && productsDescription.textContent) ? productsDescription.textContent.trim() : '';
  const amount = cleanNum(productsPrice ? productsPrice.textContent : '');
  const discount = cleanNum(productsDiscount ? productsDiscount.textContent : '');
  const code = productsCode;
  const qty = quantityProduct || 1;

  if (!img || !title) return;

  const items = getCart();
  if (items.some(i => i.img === img && i.title === title)) {
    return;
  };

  const item = {
    id: Date.now().toString(36) + Math.random().toString(36).slice(2,6),
    img, title, description, amount, discount, quantity: qty, code
  };
  items.push(item);
  setCart(items);
  renderCart();
  addInputsInFormBuy(item);
};

const removeFromCart = (id) => {
  const items = getCart().filter(i => i.id !== id);
  setCart(items);
  renderCart();
};

if (removeAllProduct && removeAllProduct.length) {
  removeAllProduct.forEach(btn => btn.addEventListener('click', (e) => {
    e.preventDefault();
    setCart([]);
    localStorage.removeItem('shopping_cart_total_v1');
    renderCart();
    formBuy.innerHTML = "";
  }));
};

if (addButtons && addButtons.length) {
  addButtons.forEach(btn => btn.addEventListener('click', (e) => {
    e.preventDefault();
    addCurrentProductToCart();
  }));
};

document.addEventListener('DOMContentLoaded', renderCart);

function contentCard() {
  let data = "";
  // let codeOrder = row.getAttribute("data-code");
  // let typeOrder = row.getAttribute("data-type");
  // let orderName = row.getAttribute("data-name");
  // let orderAmount = row.getAttribute("data-amount");

  // let typeCard = mainCardsShow.querySelector(".type");
  // let codeOrderCard = mainCardsShow.querySelector(".code_order");
  // let orderNameCard = mainCardsShow.querySelector(".order_name");
  // let amountCard = mainCardsShow.querySelector(".amount");

};

formBuyProducts.addEventListener("submit", (e) => {
  e.preventDefault();
})
