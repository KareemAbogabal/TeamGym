let navMove = document.querySelector("nav");

document.addEventListener("DOMContentLoaded", function () {
  window.onscroll = () => {
    if (window.scrollY === 0) {
      navMove.classList.remove("blur-nav");
    } else {
      navMove.classList.add("blur-nav");
    };
  };
});
