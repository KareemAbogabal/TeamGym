let forms = document.querySelectorAll('form');
let inputs = document.querySelectorAll('.nebula-input .input');
let forget = document.querySelector('.forget');

inputs.forEach(input => {
  const wrapper = input.closest('.nebula-input');
  if (!wrapper) return;
  const label = wrapper.querySelector('.user-label');
  if (!label) return;
  const syncLabel = () => {
    if (input.value.trim() !== '') {
      label.classList.add('show-label');
    } else {
      label.classList.remove('show-label');
    };
  };
  syncLabel();
  input.addEventListener('focus', () => {
    label.classList.add('show-label');
  });
  input.addEventListener('input', syncLabel);
  input.addEventListener('blur', syncLabel);
  label.addEventListener('click', () => {
    input.focus();
  });
});

forget.onclick = () => {
  forms[0].classList.remove("hidden");
  forms[1].classList.add("hidden");
};
