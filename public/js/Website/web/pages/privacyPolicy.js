let navMove = document.querySelector("nav");
let btnSec = document.querySelectorAll(".btn-sec");

document.addEventListener("DOMContentLoaded", function () {
  window.onscroll = () => {
    if (window.scrollY === 0) {
      navMove.classList.remove("blur-nav");
      btnSec[0].classList.add("active");
      btnSec[1].classList.remove("active");
    } else {
      navMove.classList.add("blur-nav");
    };
    if (window.scrollY >= 750) {
      btnSec[0].classList.remove("active");
      btnSec[1].classList.add("active");
      btnSec[2].classList.remove("active");
      btnSec[3].classList.remove("active");
    };
  };
});
