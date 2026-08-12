document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-follow]').forEach(target => {
    const value = target.getAttribute('data-follow');
    if (!value) return;
    const isMainCard = target.classList.contains('main-card');
    document.querySelectorAll(`[data-follow="${value}"]`).forEach(el => {
      if (el === target) return;
      el.addEventListener('click', () => {
        if (isMainCard) {
          target.classList.toggle('show-main-card');
        } else {
          target.classList.toggle('hidden');
        }
      });
    });
  });
});
