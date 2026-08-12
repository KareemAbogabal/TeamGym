let warning = document.querySelectorAll(".warning");

if (warning) {
  warning.forEach(item => {
    setTimeout(() => {
      item.classList.add("show");
    }, 1000);
    setTimeout(() => {
      item.classList.add("hidden");
      item.classList.remove("show");
    }, 5000);
  });
};
