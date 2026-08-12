let btnSec = document.querySelectorAll(".btn-sec");
let section2 = document.querySelector(".section-2");
let section3 = document.querySelector(".section-3");
let section4 = document.querySelector(".section-4");
let mainCardsShow = document.querySelector('.main-card[data-state="show"]');
let showCard = document.querySelectorAll(".show");
let closeCard = document.querySelector(".close-card");
let btnsScrollProfileCard = document.querySelectorAll('.link-for-achievements a');
let averages = document.querySelectorAll('.average');
let mainBlur = document.querySelector('.main-blur');
let mainCardVideo = document.querySelector('.main-card-video');
let cardVideo = document.querySelector('.card-video');
let video = document.querySelector('.card-video video');
let btnShowVideo = document.querySelector('.button-show-video');
let btnMinimize = document.querySelector('.minimize');
let btnMute = document.querySelector('.mute');
let mute = !!(video && video.muted);

if (window.innerWidth <= 1115) {
  menu.onchange = () => {
    main.classList.toggle("show-main");
  };
  li.forEach(button => {
    button.onclick = () => {
      menu.click();
    };
  });
};

btnShowVideo.onclick = () => {
  mainBlur.classList.add('show-main-blur');
  mainCardVideo.classList.add("position-main-card-video");
  cardVideo.classList.add("show-card-video");
  video.play();
};

btnMinimize.onclick = () => {
  mainBlur.classList.remove('show-main-blur');
  mainCardVideo.classList.remove("position-main-card-video");
  cardVideo.classList.remove("show-card-video");
  video.pause();
};

btnMute.onclick = () => {
  if (mute) {
    video.muted = false;
    btnMute.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" aria-hidden="true" role="img">
        <g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M11 5L6 9H3v6h3l5 4V5z" />
          <line x1="23" y1="9" x2="17" y2="15" />
          <line x1="17" y1="9" x2="23" y2="15" />
        </g>
      </svg>
    `;
    mute = false;
  } else {
    video.muted = true;
    btnMute.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" aria-hidden="true" role="img">
        <g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M11 5L6 9H3v6h3l5 4V5z" />
          <path d="M15.54 8.46a5 5 0 0 1 0 7.07" />
          <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
        </g>
      </svg>
    `;
    mute = true;
  };
};

averages.forEach(item => {
  let animating = false;
  item.onclick = () => {
    if (animating) return;
    if (!item.classList.contains('show-img')) {
      item.classList.remove('hidden-img');
      void item.offsetWidth;
      item.classList.add('show-img');
      animating = true;
      const onEnd = (ev) => {
        if (ev.animationName === 'showImg') {
          animating = false;
          item.removeEventListener('animationend', onEnd);
        };
      };
      setTimeout(() => {
        mainBlur.classList.add('show-main-blur');
      }, 5000);
      item.addEventListener('animationend', onEnd);
    } else {
      item.classList.remove('show-img');
      void item.offsetWidth;
      item.classList.add('hidden-img');
      animating = true;
      const onEnd2 = (ev) => {
        if (ev.animationName === 'showImg') {
          animating = false;
          item.removeEventListener('animationend', onEnd2);
        };
      };
      item.addEventListener('animationend', onEnd2);
      mainBlur.classList.remove('show-main-blur');
      setTimeout(() => {
        item.classList.remove('hidden-img');
      }, 6000);
    };
  };
});

function contentCard(e) {
  const row = e.target.closest(`.card`);
  if (!row) return;
  let codeOrder = row.getAttribute("data-code");
  let typeOrder = row.getAttribute("data-type");
  let orderName = row.getAttribute("data-name");
  let orderAmount = row.getAttribute("data-amount");
  if (row.getAttribute("data-quantity")) {
    quantityCard.value = 1;
  };

  let typeCard = mainCardsShow.querySelector(".type");
  let codeOrderCard = mainCardsShow.querySelector(".code_order");
  let orderNameCard = mainCardsShow.querySelector(".order_name");
  let amountCard = mainCardsShow.querySelector(".amount");
  let quantityCard = mainCardsShow.querySelector(".quantity");

  if (mainCardsShow) {
    typeCard.value = typeOrder;
    codeOrderCard.value = codeOrder;
    orderNameCard.value = orderName;
    amountCard.value = orderAmount;
  };
};

showCard.forEach((button, index) => {
  button.addEventListener("click", (e) => {
    contentCard(e);
  });
});

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
    };
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
