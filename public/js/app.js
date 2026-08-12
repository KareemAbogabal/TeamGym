let html = document.querySelector("html");
let btnSec = document.querySelectorAll(".btn-sec");
let section2 = document.querySelector(".section-2");
let section3 = document.querySelector(".section-3");
let section4 = document.querySelector(".section-4");
let section5 = document.querySelector(".section-5");
let products = document.querySelector('.products');
let product = Array.from(products.querySelectorAll('.product'));
let alignButtons = Array.from(document.querySelectorAll('.align-sec-3'));
let inputsSubscription =  document.querySelectorAll('.pearnt-input input');
let inputsSubscriptionP =  document.querySelectorAll('.pearnt-input main');
let addSystemLable =  document.querySelector('.add-system-lable');
let addSystem =  document.querySelector('.add-system');
let card =  document.querySelectorAll('.card-system');
let nameSystem =  document.querySelectorAll('.name-system');
let hiddenInputSystem = document.getElementById("system");
let btnsScrollProfileCard = document.querySelectorAll('.link-for-achievements a');
let btnsScrollProductCard = document.querySelectorAll('.aligns .btns a');
let linkForAchievements = document.querySelector('.link-for-achievements');
let productButton = document.querySelectorAll(".product-button");
let productsPhoto = document.querySelectorAll(".product .img-product");
let productsTitle = document.querySelectorAll(".product .title");
let productsDescription = document.querySelectorAll(".product .meta-text");
let productsPrice = document.querySelectorAll(".product .price");
let productsDiscount = document.querySelectorAll(".product .discount");
let productsCode = 0;
let storageKey = 'shopping_cart_items_v1';
let buttonProfile = null;
if (linkForAchievements) buttonProfile = linkForAchievements.querySelectorAll('a');
let checkAdd = true;

document.addEventListener("DOMContentLoaded", function () {
  window.onscroll = () => {
    if (window.scrollY === 0) {
      navmove.classList.remove("blur-nav");
      btnSec[0].classList.add("active");
      btnSec[1].classList.remove("active");
    } else {
      navmove.classList.add("blur-nav");
    };
    if (window.scrollY >= section2.offsetTop - 100) {
      btnSec[0].classList.remove("active");
      btnSec[1].classList.add("active");
      btnSec[2].classList.remove("active");
      btnSec[3].classList.remove("active");
    };
    if (window.scrollY >= section3.offsetTop - 100) {
      btnSec[0].classList.remove("active");
      btnSec[1].classList.remove("active");
      btnSec[2].classList.add("active");
      btnSec[3].classList.remove("active");
    };
    if (window.scrollY >= section4.offsetTop - 100) {
      btnSec[0].classList.remove("active");
      btnSec[1].classList.remove("active");
      btnSec[2].classList.remove("active");
      btnSec[3].classList.add("active");
      btnSec[4].classList.remove("active");
    };
    if (window.scrollY >= section5.offsetTop - 100) {
      btnSec[0].classList.remove("active");
      btnSec[1].classList.remove("active");
      btnSec[2].classList.remove("active");
      btnSec[3].classList.remove("active");
      btnSec[4].classList.add("active");
    };
  };
});

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

if (buttonProfile) {
  buttonProfile.forEach(button => {
    button.onclick = () => {
      buttonProfile.forEach(btn => btn.classList.remove("active-button-profile"));
      button.classList.add("active-button-profile");
    };
  });
};

inputsSubscription.forEach(input => {
  input.addEventListener('focus', () => {
    const label = input.previousElementSibling;
    if (label && label.tagName === 'LABEL') {
      label.classList.add("focus-label");
    };
  });
  input.addEventListener('blur', () => {
    const label = input.previousElementSibling;
    if (label && label.tagName === 'LABEL' && input.value == "") {
      label.classList.remove("focus-label");
    };
  });
});

inputsSubscriptionP.forEach(input => {
  input.addEventListener('focus', () => {
    const label = input.previousElementSibling;
    if (label && label.tagName === 'LABEL') {
      label.classList.add("focus-label");
    };
  });
  input.addEventListener('blur', () => {
    const label = input.previousElementSibling;
    if (label && label.tagName === 'LABEL') {
      label.classList.remove("focus-label");
    };
  });
});


card.forEach((button, index) => {
  button.onclick = () => {
    if (checkAdd == true) {
      addSystem.setAttribute('contenteditable', 'false');
      addSystem.innerHTML = "";
      let system = document.createElement("div");
      let span = document.createElement("span");
      let inputCode = document.createElement("input");
      let inputName = document.createElement("input");
      let inputAmount = document.createElement("input");
      let buttonCloseSystem = document.createElement("button");
      if (button.classList.contains("pro")) {
        system.className = "system pro";
      } else {
        system.className = "system";
      };
      inputCode.type = "hidden";
      inputCode.name = "code";
      inputCode.value = nameSystem[index].getAttribute("data-code");
      inputName.type = "hidden";
      inputName.name = "order_name";
      inputName.value = nameSystem[index].getAttribute("data-name");
      inputAmount.type = "hidden";
      inputAmount.name = "amount";
      inputAmount.value = nameSystem[index].getAttribute("data-amount");
      span.innerHTML = nameSystem[index].innerHTML;
      hiddenInputSystem.value = nameSystem[index].innerHTML;
      buttonCloseSystem.className = `delete`;
      buttonCloseSystem.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-label="close"><path d="M18 6 6 18M6 6l12 12"/></svg>`;
      buttonCloseSystem.type = "button";
      system.appendChild(inputCode);
      system.appendChild(inputName);
      system.appendChild(inputAmount);
      system.appendChild(span);
      system.appendChild(buttonCloseSystem);
      addSystem.appendChild(system);
      addSystemLable.classList.add("focus-label");
    };
    let deleteBtn = document.querySelectorAll('.delete');
    let system = document.querySelectorAll('.system');
    deleteBtn.forEach((buttonDelete, indexDelete) => {
      buttonDelete.onclick = () => {
        system[indexDelete].remove();
        addSystemLable.classList.remove("focus-label");
        checkAdd = true;
        addSystem.setAttribute('contenteditable', 'true');
        addSystem.value = "";
      };
    });
    checkAdd = false;
  };
});

function scrollYCards(link) {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    let targetId = this.getAttribute('href');
    let targetElement = document.querySelector(targetId);
    if (targetElement) {
      targetElement.scrollIntoView({
        behavior: 'smooth',
        block: 'nearest',
        inline: 'start'
      });
    };
  });
};

btnsScrollProfileCard.forEach(link => {
  scrollYCards(link);
});

btnsScrollProductCard.forEach(link => {
  scrollYCards(link);
});

function getCart() {
  try {
    const raw = localStorage.getItem(storageKey);
    const products = raw ? JSON.parse(raw) : [];
    return Array.isArray(products) ? products : [];
  } catch (e) {
    return [];
  }
};

function setCart(items) {
  localStorage.setItem(storageKey, JSON.stringify(items));
};

function addCurrentProductToCart(index) {
  const img = (productsPhoto[index] && productsPhoto[index].src) ? productsPhoto[index].src : '';
  const title = (productsTitle[index] && productsTitle[index].textContent) ? productsTitle[index].textContent.trim() : '';
  const description = (productsDescription[index] && productsDescription[index].textContent) ? productsDescription[index].textContent.trim() : '';
  const amount = productsPrice[index] ? productsPrice[index].textContent : '';
  const discount = productsDiscount[index] ? productsDiscount[index].textContent : '';
  const code = product[index].getAttribute("data-code");
  const qty = 1;
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
};

productButton.forEach((button, index) => {
  button.onclick = () => {
    addCurrentProductToCart(index);
  };
});
