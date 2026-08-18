document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-follow]').forEach(target => {
    if (target.closest('.close-profile')) return;

    const value = target.getAttribute('data-follow');
    if (!value) return;

    const allCards = [...document.querySelectorAll(`.main-card[data-follow="${value}"]`)];
    const scope = target.closest('.card-employees, .row, .body-card, .card') || document.body;
    const linkedCard = scope.querySelector(`.main-card[data-follow="${value}"]`) || allCards[0] || null;

    if (allCards.length) {
      const toggleCard = () => {
        if (!linkedCard) return;
        const isVisible = linkedCard.classList.contains('show-main-card');
        allCards.forEach(card => card.classList.remove('show-main-card'));
        if (!isVisible) {
          linkedCard.classList.add('show-main-card');
        }
      };

      // Add listener directly to trigger button (not main-card, not close buttons)
      if (!target.classList.contains('main-card') && !target.closest('.close-profile')) {
        target.addEventListener('click', (event) => {
          event.stopPropagation();
          toggleCard();
        });
      }

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
