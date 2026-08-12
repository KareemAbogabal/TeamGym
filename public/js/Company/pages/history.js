let input = document.querySelector('.search-input');
let rows = document.querySelectorAll(".row");
let btnDetails = document.querySelectorAll(".btn-details");

input.addEventListener('input', () => {
  const term = input.value.trim().toLowerCase();
  rows.forEach(row => {
    const text = Array.from(row.children).map(el => el.textContent.trim().toLowerCase()).join(' ');
    if (text.includes(term)) {
      row.style.display = 'flex';
    } else {
      row.style.display = 'none';
    };
  });
});

btnDetails.forEach((button, index) => {
  button.onclick = () => {
    button.classList.toggle("show-btn-det");
    rows[index].classList.toggle("show-det-row");
  };
});

(function () {
  let data = JSON.parse(sessionStorage.getItem("rejectedPage")) || [];
  if (!data.includes("historys")) {
    data.push("historys");
    sessionStorage.setItem("rejectedPage", JSON.stringify(data));
  };
})();
