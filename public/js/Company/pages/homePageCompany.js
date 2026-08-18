let formSearch = document.querySelector(".form-search");
let searchInput = document.querySelector(".search");
let searchCard = document.querySelector(".search-card");
let rowSearched = document.querySelector(".searched");
let resultSearch = document.querySelector(".result-search");
let blurPage = document.querySelector(".blur-page");
let menuList = document.querySelector(".menu-list");
let accountMain = document.querySelector(".account-main");
let mainBlur = document.querySelector(".main-blur");
let menu = document.querySelector(".menu");
let ulItems = document.querySelectorAll("ul li a");
let textLink = document.querySelectorAll(".text-link");
let sideBar = document.querySelector(".side-bar");
let menuBar1 = document.querySelector(".bar-1");
let menuBar2 = document.querySelector(".bar-2");
let menuBar3 = document.querySelector(".bar-3");
let notificationBtn = document.querySelector(".notification-btn");
let notifications = document.querySelector(".notifications");
let optionBtn = document.querySelector(".option-btn");
let options = document.querySelector(".options");
let btnStateColorPage = document.querySelector(".state-color-page");
let btnAddEmployee = document.querySelector(".add-employee");
let mainCardAdd = document.querySelector(".main-card[data-state='add']");
let closeProfile = document.querySelectorAll(`.close-profile`);
let uploadImgEmployee = document.querySelector(`.upload-img-employee`);
let uploadImgFile = document.getElementById(`upload-img`);
let pRow = document.querySelectorAll('.table .body .row p');
let currentObjectUrl = null;
let typingTimer;
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userId = window.USER_ID;

let totalCount = {
  records: 0,
  historys: 0,
  requests: 0,
  imports: 0,
};

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
  fetch('/search-img', {
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
  const search = fetch('/search', {
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

closeProfile.forEach((button) => {
  button.addEventListener("click", (e) => {
    e.stopPropagation();
    const mainCard = button.closest('.main-card');
    if (!mainCard) return;
    mainCard.classList.remove("show-main-card");
  });
});

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

document.addEventListener('DOMContentLoaded', function () {
  const pageTitle = document.title;
  let result = "";
  const sep = pageTitle.indexOf("|");
  if (sep !== -1) {
    result = pageTitle.slice(sep + 1).trim().toLowerCase();
  } else {
    result = pageTitle.trim().toLowerCase();
  };
  const titleWords = result.split(/\s+/);
  let activeAdded = false;
  ulItems.forEach((link, i) => {
    if (activeAdded) return;
    const href = link.getAttribute('href') || '';
    if (href) {
      try {
        const target = new URL(href, location.origin);
        const current = new URL(location.href);
        if (target.pathname.replace(/\/+$/, '') === current.pathname.replace(/\/+$/, '')) {
          link.classList.add('active');
          activeAdded = true;
          return;
        };
      } catch (e) {
      };
    };
    const text = (typeof textLink !== 'undefined' && textLink[i])
      ? textLink[i].innerText.toLowerCase()
      : link.innerText.toLowerCase();
    const textWords = text.split(/\s+/).filter(w => w);
    const match = titleWords.some(word => {
      if (!word) return false;
      if (textWords.includes(word)) return true;
      return word.length >= 4 && text.includes(word);
    });
    if (match) {
      link.classList.add("active");
      activeAdded = true;
    };
  });
  if (window.innerWidth >= 1115) {
    const blurPage = document.querySelector('.blur-page');
    if (!blurPage) return;
    const SHOW_DELAY = 15000;
    const INACTIVITY_DEBOUNCE = 300; // وقت انتظار بسيط لاعتبار الماوس "توقف"
    const COMBO_COOLDOWN = 500;  // منع التكرار السريع عند الضغط
    let showTimer = null;
    let inactivityTimer = null; // debounce لمراقبة توقف الماوس
    let lastComboAt = 0;
    const pressed = new Set();
    function cancelShow() {
      if (showTimer) {
        clearTimeout(showTimer);
        showTimer = null;
      };
    };
    function scheduleShow() {
      // cancelShow(); // blur
      showTimer = setTimeout(() => {
        blurPage.classList.add('show-blur-page');
        showTimer = null;
      }, SHOW_DELAY);
    };
    function onMouseMove() {
      // cancelShow(); // blur
      if (inactivityTimer) clearTimeout(inactivityTimer);
      inactivityTimer = setTimeout(() => {
        // scheduleShow(); // blur
        inactivityTimer = null;
      }, INACTIVITY_DEBOUNCE);
    };
    window.addEventListener('mousemove', onMouseMove, { passive: true });
    window.addEventListener('touchstart', onMouseMove, { passive: true });
    window.addEventListener('touchmove', onMouseMove, { passive: true });
    document.addEventListener('keydown', function (e) {
      const key = (e.key || '').toLowerCase();
      if (!key) return;
      pressed.add(key);
      if (pressed.size >= 2) {
        const now = Date.now();
        if (now - lastComboAt < COMBO_COOLDOWN) return;
        lastComboAt = now;
        blurPage.classList.remove('show-blur-page');
        // cancelShow(); // blur
        // scheduleShow(); // blur
      };
    });
    document.addEventListener('keyup', function (e) {
      const key = (e.key || '').toLowerCase();
      if (!key) return;
      pressed.delete(key);
    });
    window.addEventListener('blur', () => pressed.clear());
    // scheduleShow(); // blur
  };
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


function handleImageChange(imgElem, fileInput, opts = {}) {
  fileInput.addEventListener('change', (e) => {
    if (typeof opts.onStart === 'function') opts.onStart();
    const originalSrc = imgElem.src || '';
    const file = e.target.files && e.target.files[0];
    if (!file) {
      if (currentObjectUrl) {
        URL.revokeObjectURL(currentObjectUrl);
        currentObjectUrl = null;
      }
      imgElem.src = originalSrc;
      return;
    };
    if (!file.type.startsWith('image/')) {
      alert('الرجاء اختيار ملف صورة (jpg, png, ...).');
      (opts.resetInput || input).value = '';
      return;
    };
    if (currentObjectUrl) {
      URL.revokeObjectURL(currentObjectUrl);
      currentObjectUrl = null;
    };
    currentObjectUrl = URL.createObjectURL(file);
    imgElem.src = currentObjectUrl;
    imgElem.alt = file.name;
  });
};

handleImageChange(uploadImgEmployee, uploadImgFile);

// Pusher.logToConsole = true;

if (typeof Echo !== 'undefined' && typeof KEY !== 'undefined') {
  var echo = new Echo({
    broadcaster: 'pusher',
    key: KEY,
    cluster: CLUSTER,
    forceTLS: true,
    auth: {
      headers: {
        'X-CSRF-TOKEN': token
      },
      withCredentials: true
    },
  });

  echo.private(`requests.${userId}`).listen('.NewRequestCreated', (e) => {
    let button;
    if (e.page === "records") {
      totalCount.records += e.count;
      button = document.querySelector('.records-link');
      button.setAttribute('data-badge', totalCount.records);
    } else if (e.page === "requests") {
      totalCount.requests += e.count;
      button = document.querySelector('.requests-link');
      button.setAttribute('data-badge', totalCount.requests);
    } else if (e.page === "imports") {
      totalCount.imports += e.count;
      button = document.querySelector('.imports-link');
      button.setAttribute('data-badge', totalCount.imports);
    } else if (e.page === "historys") {
      totalCount.historys += e.count;
      button = document.querySelector('.historys-link');
      button.setAttribute('data-badge', totalCount.historys);
    }
  });

  document.addEventListener('DOMContentLoaded', () => {
    let rejectPage = JSON.parse(sessionStorage.getItem("rejectedPage")) || [];
    fetch('/count-request', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token || ''
      },
      body: JSON.stringify({ page: rejectPage })
    }).then(response => response.json()).then(data => {
      console.log(data);
    }).catch(error => {
      console.error('Error:', error);
    });
  });
}
