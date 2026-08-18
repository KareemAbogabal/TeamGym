(function () {
  "use strict";

  var DEFAULTS = {
    type: "info",
    message: "",
    title: null,
    duration: 5000,
  };

  var TITLES = {
    info: "Info",
    success: "Success",
    warning: "Warning",
    error: "Error",
  };

  var ICONS = {
    info: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M11 17h2v-6h-2v6zm1-15C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zM11 9h2V7h-2v2z"/></svg>',
    success: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>',
    warning: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>',
    error: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/></svg>',
  };

  var MESSAGES = {
    en: {
      "saved-successfully": "Saved successfully",
      "updated-successfully": "Updated successfully",
      "deleted-successfully": "Deleted successfully",
      "email-empty": "Email is required",
      "fname-empty": "First name is required",
      "lname-empty": "Last name is required",
      "phone-empty": "Phone is required",
      "password-empty": "Password is required",
      "email-check": "Please enter a valid email address",
      "failed-img": "Please choose an image first",
      "system-empty": "System is required",
      "employee-empty": "Employee is required",
      "lock-entrance": "Entrance is locked, you cannot enter again now",
    },
    ar: {
      "saved-successfully": "تم الحفظ بنجاح",
      "updated-successfully": "تم التحديث بنجاح",
      "deleted-successfully": "تم الحذف بنجاح",
      "email-empty": "البريد الإلكتروني مطلوب",
      "fname-empty": "الاسم الأول مطلوب",
      "lname-empty": "اسم العائلة مطلوب",
      "phone-empty": "رقم الهاتف مطلوب",
      "password-empty": "كلمة المرور مطلوبة",
      "email-check": "يرجى إدخال بريد إلكتروني صحيح",
      "failed-img": "يرجى اختيار صورة أولاً",
      "system-empty": "النظام مطلوب",
      "employee-empty": "الموظف مطلوب",
      "lock-entrance": "الدخول مقفول، لا يمكنك الدخول الآن",
    },
  };

  function translateMessage(key) {
    var lang = (document.documentElement.getAttribute("lang") || "en").slice(0, 2);
    var dict = MESSAGES[lang] || MESSAGES.en;
    return dict[key] || key;
  }

  function getContainer() {
    var container = document.querySelector(".toasts-container");
    if (!container) {
      container = document.createElement("div");
      container.className = "toasts-container";
      document.body.appendChild(container);
    }
    return container;
  }

  function buildToast(options) {
    var el = document.createElement("div");
    var icon = ICONS[options.type] || ICONS.info;
    el.className = "toast toast--" + options.type;
    el.setAttribute("role", "status");
    el.innerHTML =
      '<span class="toast__icon">' + icon + "</span>" +
      '<span class="toast__body"><span class="toast__title">' + escapeHtml(options.title) + "</span>" +
      '<span class="toast__message">' + escapeHtml(options.message) + "</span></span>" +
      '<button type="button" class="toast__close" aria-label="Close">' +
      '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>' +
      "</button>" +
      '<span class="toast__bar"></span>';
    return el;
  }

  function escapeHtml(str) {
    return String(str == null ? "" : str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function dismissToast(el) {
    if (el.dataset.dismissed) return;
    el.dataset.dismissed = "1";
    el.classList.remove("show");
    el.classList.add("hide");
    setTimeout(function () {
      el.remove();
    }, 400);
  }

  function mountToast(el, duration) {
    var bar = el.querySelector(".toast__bar");
    var closeBtn = el.querySelector(".toast__close");
    var elapsed = 0;
    var last = 0;
    var raf = null;
    var paused = false;
    var done = false;

    function loop(now) {
      if (done) return;
      if (paused) {
        last = now;
        return;
      }
      var dt = now - last;
      last = now;
      elapsed += dt;
      var progress = Math.min(1, elapsed / duration);
      if (bar) {
        bar.style.transform = "scaleX(" + (1 - progress).toFixed(4) + ")";
      }
      if (progress >= 1) {
        finish();
        return;
      }
      raf = requestAnimationFrame(loop);
    }

    function start() {
      last = performance.now();
      raf = requestAnimationFrame(loop);
    }

    function pause() {
      paused = true;
      if (raf) cancelAnimationFrame(raf);
    }

    function resume() {
      if (done) return;
      paused = false;
      last = performance.now();
      raf = requestAnimationFrame(loop);
    }

    function finish() {
      done = true;
      if (raf) cancelAnimationFrame(raf);
      dismissToast(el);
    }

    el.classList.add("show");
    start();

    if (closeBtn) {
      closeBtn.addEventListener("click", finish);
    }
    el.addEventListener("mouseenter", pause);
    el.addEventListener("mouseleave", resume);
  }

  function notify(options) {
    var opts = {};
    for (var key in DEFAULTS) {
      opts[key] = DEFAULTS[key];
    }
    if (options && typeof options === "object") {
      for (var k in options) {
        if (options[k] !== undefined && options[k] !== null) {
          opts[k] = options[k];
        }
      }
    }
    if (TITLES[opts.type] && !opts.title) {
      opts.title = TITLES[opts.type];
    }
    opts.message = translateMessage(opts.message);
    var el = buildToast(opts);
    getContainer().appendChild(el);
    mountToast(el, opts.duration);
    return el;
  }

  function init() {
    var toasts = document.querySelectorAll(".toast");
    toasts.forEach(function (el) {
      var duration = parseInt(el.getAttribute("data-duration"), 10) || DEFAULTS.duration;
      mountToast(el, duration);
    });
  }

  window.TeamGymNotify = notify;

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
