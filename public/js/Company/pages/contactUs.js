let input = document.querySelector('.search-input');
let rows = document.querySelectorAll(".row");

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
