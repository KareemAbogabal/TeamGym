let navMove = document.querySelector("nav");
let btnSec = document.querySelectorAll(".btn-sec");
let section2 = document.querySelector(".section-2");
let mainCardsShow = document.querySelector('.main-card[data-state="show"]');
let showCard = document.querySelectorAll(".show");

document.addEventListener("DOMContentLoaded", function () {
  window.onscroll = () => {
    if (window.scrollY === 0) {
      navMove.classList.remove("blur-nav");
      btnSec[0].classList.add("active");
      btnSec[1].classList.remove("active");
    } else {
      navMove.classList.add("blur-nav");
    };
    if (window.scrollY >= section2.offsetTop - 300) {
      btnSec[0].classList.remove("active");
      btnSec[1].classList.add("active");
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
