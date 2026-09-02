let card = document.querySelector("main .card");
let switchBtn = document.querySelector("main .switch");
let signupForm = document.querySelector(".panel-signup");
let loginForm = document.querySelector(".panel-login");
let forget = document.querySelector(".forget");
let overlayForms = Array.from(document.querySelectorAll(".overlay-form"));

function resetOverlays() {
  overlayForms.forEach((f) => f.classList.add("hidden"));
}

function showActivePanel() {
  if (card.classList.contains("show-sigin-up")) {
    signupForm.classList.remove("hidden");
    loginForm.classList.add("hidden");
  } else {
    loginForm.classList.remove("hidden");
    signupForm.classList.add("hidden");
  }
}

function hidePanels() {
  signupForm.classList.add("hidden");
  loginForm.classList.add("hidden");
}

if (switchBtn) {
  switchBtn.addEventListener("click", () => {
    card.classList.toggle("show-sigin-up");
    resetOverlays();
    card.classList.remove("hidden");
    showActivePanel();
  });
}

if (forget) {
  forget.onclick = () => {
    hidePanels();
    card.classList.add("hidden");
    overlayForms.forEach((f, i) => {
      f.classList.toggle("hidden", i !== 0);
    });
  };
}

document.querySelectorAll(".overlay-form .form-back").forEach((btn) => {
  btn.onclick = () => {
    resetOverlays();
    card.classList.remove("hidden");
    showActivePanel();
  };
});

let inputs = document.querySelectorAll('.nebula-input .input');

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

let resetForm = overlayForms[2];
if (resetForm) {
  resetForm.addEventListener("submit", (e) => {
    let password = resetForm.querySelector('input[name="password"]');
    let confirm = resetForm.querySelector('input[name="password_confirmation"]');
    if (password && confirm && password.value !== confirm.value) {
      e.preventDefault();
      confirm.style.borderColor = "rgba(255, 80, 80, 0.6)";
      confirm.focus();
    } else if (confirm) {
      confirm.style.borderColor = "";
    }
  });
}
