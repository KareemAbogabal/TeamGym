const usersEditCard = document.querySelector('.main-card[data-state="edit"]');
const usersShowCard = document.querySelector('.main-card[data-state="list"]');

function closeAllCards() {
  [usersEditCard, usersShowCard].forEach(card => {
    if (card) card.classList.remove('show-main-card');
  });
}

function populateCard(type, e) {
  const card = type === 'edit' ? usersEditCard : usersShowCard;
  if (!card) return;

  const selector = type === 'edit' ? '.edit' : '.show';
  const btn = e.target.closest(selector);
  if (!btn) return;
  const row = btn.closest('.row');
  if (!row) return;

  let nameText = row.querySelector('.search')?.textContent?.trim() ?? '';
  const parts = nameText.split(/\s+/);
  const fname = parts.shift() || '';
  const lname = parts.join(' ') || '';
  const img = row.getAttribute('data-img');
  const code = row.getAttribute('data-code') ?? '';
  const role = row.getAttribute('data-role') ?? '';
  const email = row.getAttribute('data-communication') ?? '';
  const phone = row.querySelector('.phone')?.textContent?.trim() ?? '';
  const documentation = row.getAttribute('data-documentation');

  const nameImg = card.querySelector('.img img');
  if (nameImg) nameImg.src = img;

  card.querySelectorAll('.fname-card').forEach(i => i.value = fname);
  card.querySelectorAll('.lname-card').forEach(i => i.value = lname);
  card.querySelectorAll('.phone-card').forEach(i => i.value = phone);
  card.querySelectorAll('.email-card').forEach(i => i.value = email);
  card.querySelectorAll('.code').forEach(i => i.value = code ?? '');
  card.querySelectorAll('.full-name-card').forEach(i => i.textContent = `${fname} ${lname}`);

  if (type === 'edit') {
    const roleInput = card.querySelector('.role-card');
    if (roleInput) roleInput.value = role;
  } else {
    const roleText = card.querySelector('.role-card-text');
    if (roleText) roleText.textContent = role;
    const codeVal = card.querySelector('.code-value');
    if (codeVal) codeVal.textContent = code;
    const roleVal = card.querySelector('.role-value');
    if (roleVal) roleVal.textContent = role;
    const emailVal = card.querySelector('.email-value');
    if (emailVal) emailVal.textContent = email;
    const phoneVal = card.querySelector('.phone-value');
    if (phoneVal) phoneVal.textContent = phone;
  }

  const isVerified = documentation === 'true';
  const badge = card.querySelector('.verification-wrap');
  if (badge) badge.style.display = isVerified ? '' : 'none';
  const chk = card.querySelector('.documentation-input');
  if (chk) chk.checked = isVerified;

  closeAllCards();
  card.classList.add('show-main-card');
}

document.querySelectorAll('.edit').forEach(btn => {
  btn.addEventListener('click', (e) => populateCard('edit', e));
});

document.querySelectorAll('.show').forEach(btn => {
  btn.addEventListener('click', (e) => populateCard('show', e));
});

document.querySelectorAll('.main-card .close-profile').forEach(btn => {
  btn.addEventListener('click', () => closeAllCards());
});
