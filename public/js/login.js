let forms = document.querySelectorAll("form");
let switchCard = document.querySelector(".card");
let switchCardImg = switchCard.querySelector("img");
let switchCardh1 = switchCard.querySelector("h1");
let switchCardp = switchCard.querySelector("p");
let switchButton = document.querySelector(".switch");
let switchButtonP = switchButton.querySelector("p");
let forget = document.querySelector(".forget");

function getCookie(name) {
  let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? match[2] : null;
}

function showForm(index) {
  forms.forEach((f, i) => {
    if (i === index) {
      f.classList.remove("hidden");
    } else {
      f.classList.add("hidden");
    }
  });
  if (index !== 0 && index !== 2) {
    switchCard.classList.add("hidden");
  } else {
    switchCard.classList.remove("hidden");
  }
}

let temporary = getCookie("temporary");
let verified = getCookie("verified");

if (temporary) {
  showForm(3);
} else if (verified) {
  showForm(4);
}

forget.onclick = () => {
  showForm(1);
};

switchButton.addEventListener("click", () => {
  switchCard.classList.toggle("show-sigin-up");
  if (switchCard.classList.contains("show-sigin-up")) {
    switchCardImg.src = imgSiginUp;
    switchCardh1.innerHTML = h1SiginUp;
    switchCardp.innerHTML = pSiginUp;
    switchButtonP.innerHTML = btnLogin;
  } else {
    switchCardImg.src = imgLogin;
    switchCardh1.innerHTML = h1Login;
    switchCardp.innerHTML = pLogin;
    switchButtonP.innerHTML = btnSiginUp;
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

let resetForm = forms[4];
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
