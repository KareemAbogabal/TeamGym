/* =====================================================================
   TeamGym — Design System Core JS
   Centralized behavior for main-card modals/overlays.

   Enhances (does not replace) the existing data-follow mechanism:
   - Tracks visibility of .main-card.show-main-card
   - Locks body scroll while any card is open (excludes toast overlay)
   - Closes the focused/active card on Escape
   - Provides accessible labeling defaults
   - Closes on backdrop click (optional, opt-in via data-backdrop-close)
   ===================================================================== */
(function () {
  "use strict";

  var body = document.body;
  var scrollLocked = false;
  var lastFocusedElement = null;

  function getOpenCards() {
    return Array.prototype.slice
      .call(document.querySelectorAll(".main-card.show-main-card"))
      .filter(function (c) {
        return !c.closest(".toasts-container");
      });
  }

  function lockScroll() {
    if (scrollLocked) return;
    var scrollY = window.pageYOffset || document.documentElement.scrollTop;
    body.style.top = "-" + scrollY + "px";
    body.classList.add("tg-scroll-locked");
    lastFocusedElement = document.activeElement;
    scrollLocked = true;
  }

  function unlockScroll() {
    if (!scrollLocked) return;
    var top = parseFloat(body.style.top || "0");
    body.classList.remove("tg-scroll-locked");
    body.style.top = "";
    window.scrollTo(0, Math.abs(top));
    scrollLocked = false;
    if (lastFocusedElement && typeof lastFocusedElement.focus === "function") {
      lastFocusedElement.focus();
    }
    lastFocusedElement = null;
  }

  function syncScrollLock() {
    if (getOpenCards().length > 0) {
      lockScroll();
    } else {
      unlockScroll();
    }
  }

  function closeCard(card) {
    card.classList.remove("show-main-card");
    syncScrollLock();
  }

  function onKeydown(e) {
    if (e.key !== "Escape") return;
    var cards = getOpenCards();
    if (!cards.length) return;
    e.preventDefault();
    // Close the top-most open card
    closeCard(cards[cards.length - 1]);
  }

  function onBackdropClick(e) {
    var card = e.target.closest(".main-card");
    if (!card) return;
    // Only when the click fell on the overlay itself (not inner content)
    if (e.target !== card) return;
    if (card.hasAttribute("data-backdrop-close")) {
      closeCard(card);
    }
  }

  function ensureAria(card) {
    if (!card.hasAttribute("role")) card.setAttribute("role", "dialog");
    if (!card.hasAttribute("aria-modal")) card.setAttribute("aria-modal", "true");
    if (!card.getAttribute("aria-label")) {
      var heading = card.querySelector("h1, h2, h3, [aria-label]");
      if (heading) {
        card.setAttribute("aria-label", heading.textContent.trim());
      }
    }
  }

  function observeCards() {
    var observer = null;
    if (typeof MutationObserver !== "undefined") {
      observer = new MutationObserver(function () {
        syncScrollLock();
        document.querySelectorAll(".main-card").forEach(ensureAria);
      });
      observer.observe(document.body, { attributes: true, subtree: true, attributeFilter: ["class"] });
    }
    return observer;
  }

  function init() {
    document.addEventListener("keydown", onKeydown, true);
    document.addEventListener("click", onBackdropClick, true);
    document.querySelectorAll(".main-card").forEach(ensureAria);
    syncScrollLock();
    observeCards();
  }

  window.TeamGymDesignSystem = {
    lockScroll: lockScroll,
    unlockScroll: unlockScroll,
    openCount: function () {
      return getOpenCards().length;
    },
    closeTop: function () {
      var cards = getOpenCards();
      if (cards.length) closeCard(cards[cards.length - 1]);
    },
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
