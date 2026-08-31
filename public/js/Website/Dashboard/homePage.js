let formSearch = document.querySelector(".form-search");
let searchInput = document.querySelector(".search");
let searchCard = document.querySelector(".search-card");
let rowSearched = document.querySelector(".searched");
let resultSearch = document.querySelector(".result-search");
let menuList = document.querySelector(".menu-list");
let accountMain = document.querySelector(".account-main");
let mainBlur = document.querySelector(".main-blur");
let menu = document.querySelector(".menu");
let ulItems = document.querySelectorAll("ul li a");
let textLink = document.querySelectorAll(".text-link");
let sideBar = document.querySelector(".side-bar");
let notificationBtn = document.querySelector(".notification-btn");
let notifications = document.querySelector(".notifications");
let optionBtn = document.querySelector(".option-btn");
let options = document.querySelector(".options");
let btnStateColorPage = document.querySelector(".state-color-page");
let menuBar1 = document.querySelector(".bar-1");
let menuBar2 = document.querySelector(".bar-2");
let menuBar3 = document.querySelector(".bar-3");
let pRow = document.querySelectorAll('.table .body .row p');
let typingTimer;

function msgNotFound() {
  let mainMsg = document.createElement("div");
  let msg = document.createElement("p");
  mainMsg.className = "msg"
  msg.innerHTML = "No search found";
  mainMsg.appendChild(msg);
  rowSearched.appendChild(mainMsg);
};

function saveToLocalSearched(text, itemObj = null) {
  let data = JSON.parse(localStorage.getItem("searchedItems")) || [];
  if (!data.includes(text)) {
    data.push(text);
    localStorage.setItem("searchedItems", JSON.stringify(data));
    saveToLocalResultSearched(itemObj);
  };
};

function saveToLocalResultSearched(itemObj) {
  let data = JSON.parse(localStorage.getItem("searchedResultItems")) || [];
  data.push(itemObj);
  localStorage.setItem("searchedResultItems", JSON.stringify(data));
};

function searched(text, storage) {
  let rowSearch = document.createElement("div");
  let name = document.createElement("p");
  let button = document.createElement("button");
  rowSearch.className = "row-search";
  name.innerHTML = text;
  button.type = "button";
  button.className = "remove";
  button.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="30" height="30" aria-label="X in circle">
      <circle cx="32" cy="32" r="28" fill="none" />
      <line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
      <line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
    </svg>
  `;
  rowSearch.appendChild(name);
  rowSearch.appendChild(button);
  rowSearched.appendChild(rowSearch);
  if (storage) saveToLocalSearched(text, null);
  rowSearch.onclick = () => {
    searchInput.value = name.innerHTML;
    search(name.innerHTML, false, false);
  };
  button.onclick = (e) => {
    e.stopPropagation();
    let data = JSON.parse(localStorage.getItem("searchedItems")) || [];
    data = data.filter(item => item !== text);
    localStorage.setItem("searchedItems", JSON.stringify(data));
    rowSearch.remove();
  };
};

async function resolveImage(srcImg, img) {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fetch('/search_img', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': token || ''
    },
    body: JSON.stringify({ img: srcImg })
  }).then(response => response.json()).then(data => {
    if (data) {
      img.src = data.path;
    };
  }).catch(error => {
  });
};

function resultSearched(text, srcPage, srcImg, namePage, storage) {
  let a = document.createElement("a");
  let content = document.createElement("div");
  let img = document.createElement("img");
  let name = document.createElement("p");
  let p = document.createElement("p");
  a.href = srcPage;
  a.className = "row-search";
  content.className = "content";
  resolveImage(srcImg, img);
  name.innerHTML = text;
  p.innerHTML = `page: ${namePage}`;
  content.appendChild(img);
  content.appendChild(name);
  a.appendChild(content);
  a.appendChild(p);
  resultSearch.appendChild(a);
  if (storage) {
    saveToLocalSearched(text, {text, srcPage, srcImg, namePage});
  };
};

function search(text, stateSearch, resultSearch) {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const nameText = (text || '').trim();
  const parts = nameText.split(/\s+/).filter(Boolean);
  const fname = parts.length ? parts.shift() : "";
  const lname = parts.length ? parts.join(" ") : "";
  const search = fetch('/search_client', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': token || ''
    },
    body: JSON.stringify({ name: text, fname: fname, lname: lname })
  });
  search.then(response => response.json()).then(data => {
    data.forEach(item => {
      resultSearched(text, item.route, item.data.img, item.page, resultSearch);
    });
    if (stateSearch) searched(text, stateSearch);
  }).catch(error => {
  });
};

window.addEventListener("DOMContentLoaded", () => {
  let searchedTexts = JSON.parse(localStorage.getItem("searchedItems"));
  let searchedResultItems = JSON.parse(localStorage.getItem("searchedResultItems"));
  if (searchedTexts) {
    rowSearched.innerHTML = "";
    resultSearch.innerHTML = "";
    if (searchedTexts) {
      searchedTexts.forEach(item => {
        searched(item, false);
      });
    };
    if (searchedResultItems) {
      searchedResultItems.forEach(item => {
        if (!item) return;
        if (!item.text) return;
        resultSearched(item.text, item.srcPage, item.srcImg, item.namePage, false)
      });
    };
  };
});

searchInput.addEventListener("click", (e) => {
  searchCard.classList.add("show-search-card");
});

document.addEventListener("click", (e) => {
  if (!searchInput.contains(e.target) && !searchCard.contains(e.target)) {
    searchCard.classList.remove("show-search-card");
  };
});

searchInput.addEventListener("input", () => {
  formSearch.onsubmit = (e) => {
    e.preventDefault();
  };
  clearTimeout(typingTimer);
  typingTimer = setTimeout(() => {
    if (searchInput.value !== "") {
      search(searchInput.value, true, true);
    };
  }, 1000);
});

pRow.forEach(p => {
  p.addEventListener('click', function (e) {
    if (!p) return;
    pRow.forEach(function (el) {
      if (el !== p) el.classList.remove('full-text');
    });
    p.classList.toggle('full-text');
  });
});

if (window.innerWidth <= 1115) {
  menuList.onclick = () => {
    sideBar.classList.remove("hidden-side-bar");
    mainBlur.classList.remove("show-main-blur");
    menuBar1.classList.remove("add-bar-1");
    menuBar2.classList.remove("add-bar-2");
    menuBar3.classList.remove("add-bar-3");
    accountMain.classList.toggle("show-account-main");
  };
};

optionBtn.addEventListener("click", (e) => {
  e.stopPropagation();
  options.classList.toggle("show-options");
});

notificationBtn.addEventListener("click", (e) => {
  e.stopPropagation();
  if (notificationBtn.classList.contains("there-is")) {
    notifications.classList.toggle("show-notifications");
  };
});

document.body.addEventListener("click", () => {
  if (options.classList.contains("show-options")) {
    options.classList.remove("show-options");
  };
  if (notifications.classList.contains("show-notifications")) {
    notifications.classList.remove("show-notifications");
  };
});

document.addEventListener("DOMContentLoaded", function () {
  const pageTitle = document.title;
  let result = "";
  const sep = pageTitle.indexOf("|");
  if (sep !== -1) {
    result = pageTitle.slice(sep + 1).trim().toLowerCase();
  } else {
    result = pageTitle.trim().toLowerCase();
  };
  const titleWords = result.split(/\s+/);
  ulItems.forEach((link, i) => {
    const href = link.getAttribute('href') || '';
    if (href) {
      try {
        const target = new URL(href, location.origin);
        const current = new URL(location.href);
        if (target.pathname.replace(/\/+$/, '') === current.pathname.replace(/\/+$/, '')) {
          link.classList.add('active');
          return;
        };
      } catch (e) {
      };
    };
    const text = (typeof textLink !== 'undefined' && textLink[i])
      ? textLink[i].innerText.toLowerCase()
      : link.innerText.toLowerCase();
    const match = titleWords.some(word => text.includes(word));
    if (match) {
      link.classList.add("active");
    };
  });
});

menu.addEventListener("click", () => {
  sideBar.classList.toggle("hidden-side-bar");
  if (window.innerWidth <= 1115) {
    if (!sideBar.classList.contains("hidden-side-bar")) {
      mainBlur.classList.add("show-main-blur");
    } else {
      mainBlur.classList.remove("show-main-blur");
    };
    mainBlur.classList.toggle("show-main-blur");
    accountMain.classList.remove("show-account-main");
  };
  menuBar1.classList.toggle("add-bar-1");
  menuBar2.classList.toggle("add-bar-2");
  menuBar3.classList.toggle("add-bar-3");
  if (window.innerWidth >= 1115) {
    if (!sideBar.classList.contains("hidden-side-bar")) {
      localStorage.setItem("stat-side-bar", "hidden");
    } else {
      localStorage.setItem("stat-side-bar", "show");
    };
  };
});

document.addEventListener("DOMContentLoaded", () => {
  document.body.classList.remove("light");
  localStorage.setItem("state-mode-team-gym", "dark");
  if (localStorage.getItem("stat-side-bar") == "show") {
    sideBar.classList.add("hidden-side-bar");
    menuBar1.classList.add("add-bar-1");
    menuBar2.classList.add("add-bar-2");
    menuBar3.classList.add("add-bar-3");
  } else {
    sideBar.classList.remove("hidden-side-bar");
    menuBar1.classList.remove("add-bar-1");
    menuBar2.classList.remove("add-bar-2");
    menuBar3.classList.remove("add-bar-3");
  };
  if (window.innerWidth <= 1115) {
    localStorage.removeItem("stat-side-bar");
  };
});

(function () {
  const queue = [];
  let requestingPermission = false;
  function supportsNotifications() {
    return 'Notification' in window && (location.protocol === 'https:' || location.hostname === 'localhost');
  };
  async function ensurePermission() {
    if (!supportsNotifications()) return false;
    if (Notification.permission === 'granted') return true;
    if (Notification.permission === 'denied') return false;
    if (requestingPermission) {
      return new Promise(resolve => {
        const id = setInterval(() => {
          if (!requestingPermission) {
            clearInterval(id);
            resolve(Notification.permission === 'granted');
          }
        }, 100);
      });
    }
    requestingPermission = true;
    try {
      const perm = await Notification.requestPermission();
      requestingPermission = false;
      return perm === 'granted';
    } catch (e) {
      requestingPermission = false;
      return false;
    }
  }
  function buildBody(description, price, discount) {
    let body = description || '';
    if (price) body += (body ? '\n' : '') + `السعر: ${price}`;
    if (discount) body += ` — خصم: ${discount}`;
    return body;
  }
  function showNotificationNow(opts) {
    try {
      const title = opts.title || 'إشعار';
      const body = buildBody(opts.description, opts.price, opts.discount);
      const notificationOptions = {
        body,
        icon: opts.image || undefined,
        image: opts.image || undefined,
        badge: opts.badge || undefined,
        data: { url: opts.url || window.location.href },
        requireInteraction: !!opts.requireInteraction
      };
      const n = new Notification(title, notificationOptions);
      n.onclick = function (ev) {
        ev.preventDefault();
        if (notificationOptions.data && notificationOptions.data.url) {
          // حاول فتح الرابط في نفس التبويب أو نافذة جديدة
          try { window.focus(); } catch (e) {}
          window.location.href = notificationOptions.data.url;
        }
        n.close && n.close();
      };
      return n;
    } catch (err) {
      console.error('فشل عرض الإشعار:', err);
      return null;
    }
  }
  window.notifyUser = async function notifyUser(opts) {
    if (!supportsNotifications()) {
      console.warn('Notifications غير مدعومة أو الموقع ليس عبر HTTPS/localhost.');
      return false;
    }
    if (Notification.permission === 'granted') {
      showNotificationNow(opts);
      return true;
    }
    if (Notification.permission === 'denied') {
      console.warn('المستخدم رفض الإشعارات سابقاً.');
      return false;
    }
    queue.push(opts);
    const ok = await ensurePermission();
    if (!ok) {
      queue.length = 0;
      return false;
    }
    while (queue.length) {
      const item = queue.shift();
      showNotificationNow(item);
      await new Promise(r => setTimeout(r, 400));
    }
    return true;
  };
})();

(function() {
  function scrollToBottom(behavior = 'auto') {
    const container = document.querySelectorAll('.table .body');
    if (!container || container.length === 0) return;
    container.forEach(item => {
      item.scrollTo({ top: item.scrollHeight, behavior });
    });
  };
  document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelectorAll('.table .body');
    container.forEach(item => {
      requestAnimationFrame(() => scrollToBottom('smooth'));
    });
  });
  window.addEventListener('load', () => {
    scrollToBottom('smooth');
  });
})();

// Coach request main-card (opened from the options button)
(function () {
  const COACH_FOLLOW = 'coach-request';
  const coachTriggers = document.querySelectorAll(`[data-follow="${COACH_FOLLOW}"]:not(.main-card):not(.close-profile)`);
  const coachCards = document.querySelectorAll(`.main-card[data-follow="${COACH_FOLLOW}"]`);

  const closeCoachCards = () => {
    coachCards.forEach(card => card.classList.remove('show-main-card'));
  };

  coachTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      if (coachCards.length) {
        const wasVisible = coachCards[0].classList.contains('show-main-card');
        closeCoachCards();
        if (!wasVisible) coachCards[0].classList.add('show-main-card');
      }
    });
  });

  document.querySelectorAll(`.close-profile[data-follow="${COACH_FOLLOW}"]`).forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      closeCoachCards();
    });
  });
})();
