document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-follow]').forEach(target => {
    const value = target.getAttribute('data-follow');
    if (!value) return;

    const cardTargets = [...document.querySelectorAll(`.main-card[data-follow="${value}"]`)];
    if (cardTargets.length) {
      const toggleCard = () => {
        cardTargets.forEach(card => card.classList.toggle('show-main-card'));
      };

      document.querySelectorAll(`[data-follow="${value}"]`).forEach(el => {
        if (el === target || el.closest('.main-card')) return;
        el.addEventListener('click', (event) => {
          event.stopPropagation();
          toggleCard();
        });
      });
      return;
    }

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
