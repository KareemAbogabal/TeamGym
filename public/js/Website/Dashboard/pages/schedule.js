let unactive = document.querySelectorAll(".unactive");
let exercises = document.querySelector(".exercises");
let nameExercises = document.querySelector(".name-exercises");
let description = document.querySelector(".description");
let btnGoExercise = document.querySelectorAll(".go-exercise");
let tableExercise = document.querySelector(".table");
let tableExerciseBody = document.querySelector(".table .body");
let showExerciseShape = document.querySelector(".show-exercises .body .shape");
let btnExercisesGo = document.querySelector(".btn-exercises-go");
let input = document.querySelector('.search-input');
let rows  = document.querySelectorAll('.table .row');
let isRunning = false;
let hasStarted = false;
let started = false;
let stopFn = null;
let dataExercise = [];
let code;

unactive.forEach(item => {
  item.onclick = () => {
    item.classList.add("reject");
    setTimeout(() => {
      item.classList.remove("reject");
    }, 710);
  };
})

function createExercises(name, numGroups, numSets, videoExercises) {
  let tableExerciseBodyChoose = document.querySelector(".table .body .choose");
  if (tableExerciseBodyChoose) {
    tableExerciseBodyChoose.remove();
  };
  const exercise = document.createElement('div');
  exercise.classList.add('row');
  const p1 = document.createElement('p');
  p1.textContent = name;
  p1.className = "search";
  exercise.appendChild(p1);
  const p2 = document.createElement('p');
  p2.textContent = numGroups;
  exercise.appendChild(p2);
  const p3 = document.createElement('p');
  p3.textContent = numSets;
  exercise.appendChild(p3);
  const button = document.createElement('button');
  button.className = 'add-video';
  button.textContent = 'Show Video';
  button.setAttribute("data-video", videoExercises);
  const arrowWrapper = document.createElement('div');
  arrowWrapper.classList.add('arrow-wrapper');
  const arrow = document.createElement('div');
  arrow.classList.add('arrow');
  arrowWrapper.appendChild(arrow);
  button.appendChild(arrowWrapper);
  exercise.appendChild(button);
  tableExerciseBody.appendChild(exercise);
};

function addVideo() {
  let addVideo = document.querySelectorAll(".add-video");
  addVideo.forEach((button) => {
    if (!button.classList.contains("event-bound")) {
      button.classList.add("event-bound");
      button.onclick = () => {
        let video = button.getAttribute("data-video");
        showExerciseShape.innerHTML = "";
        let createVideo = document.createElement("video");
        createVideo.src = video;
        createVideo.setAttribute("controls", "");
        createVideo.setAttribute("controlsList", "nodownload");
        showExerciseShape.appendChild(createVideo);
      };
    };
  });
};

function addImg(src) {
  showExerciseShape.innerHTML = "";
  let img = document.createElement("img");
  img.src = src;
  showExerciseShape.appendChild(img);
};

function startCount(count) {
  const unit = count > 1 ? 'seconds' : 'hours';
  const totalSeconds = unit === 'seconds' ? count : count * 3600;
  const progressBar = document.getElementById("timer");
  const timeLabel = document.getElementById("time-label");
  let timerId;
  const KEY_START = "timerStart";
  const KEY_ELAPSED = "timerElapsed";
  const KEY_ENDED = "timerEnded";
  const KEY_PAUSED = "timerPaused";
  function saveState(start, elapsed, ended, paused) {
    localStorage.setItem(KEY_START, start);
    localStorage.setItem(KEY_ELAPSED, elapsed);
    localStorage.setItem(KEY_ENDED, ended);
    localStorage.setItem(KEY_PAUSED, paused);
  };
  function loadState() {
    return {
      start: parseInt(localStorage.getItem(KEY_START), 10) || 0,
      elapsed: parseInt(localStorage.getItem(KEY_ELAPSED), 10) || 0,
      ended: localStorage.getItem(KEY_ENDED) === "true",
      paused: localStorage.getItem(KEY_PAUSED) === "true",
    };
  };
  function render(elapsed) {
    progressBar.max   = totalSeconds;
    progressBar.value = elapsed;
    if (elapsed === 0) {
      timeLabel.textContent = unit === 'seconds'
        ? count + 's'
        : count + 'h';
    } else {
      if (unit === 'seconds') {
        timeLabel.textContent = elapsed + 's';
      } else {
        const remaining = totalSeconds - elapsed;
        const mm = String(Math.floor(remaining / 60)).padStart(2, '0');
        const ss = String(remaining % 60).padStart(2, '0');
        timeLabel.textContent = mm + ':' + ss;
      };
    };
    const pct = (elapsed / totalSeconds) * 100;
    timeLabel.style.left = elapsed === 0
      ? '0%'
      : `calc(${pct}% - 15px)`;
  };
  function tick() {
    const state = loadState();
    const now   = Date.now();
    let elapsed = Math.floor((now - state.start) / 1000);
    if (elapsed >= totalSeconds) elapsed = totalSeconds;
    render(elapsed);
    saveState(state.start, elapsed, elapsed >= totalSeconds, false);
    if (elapsed >= totalSeconds) clearInterval(timerId);
  };
  function startTimer() {
    const state = loadState();
    let startTime;
    if (state.paused && state.elapsed < totalSeconds) {
      startTime = Date.now() - state.elapsed * 1000;
    } else {
      startTime = Date.now();
      state.elapsed = 0;
    };
    saveState(startTime, state.elapsed, false, false);
    render(state.elapsed);
    clearInterval(timerId);
    timerId = setInterval(tick, 1000);
  };
  function pauseTimer() {
    clearInterval(timerId);
    const state = loadState();
    saveState(state.start, state.elapsed, false, true);
  };
  window._startTimer = startTimer;
  window._pauseTimer = pauseTimer;
  (function init() {
    const state = loadState();
    if (state.start && !state.ended) {
      render(state.elapsed);
      if (!state.paused) timerId = setInterval(tick, 1000);
    } else if (state.ended) {
      render(state.elapsed);
    } else {
      render(0);
    }
  })();
  startTimer();
};

btnGoExercise.forEach((button) => {
  button.onclick = (e) => {
    let imgSrc = button.getAttribute("data-img");
    let dataCode = button.getAttribute("data-code");
    code = dataCode;
    const data = new FormData();
    data.append("code", code);
    fetch('/get-exercises', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: data
    }).then(response => response.json()).then(data => {
      dataExercise.push(data);
      let mainExercise = e.target.closest(`.exercise`);
      let h1Exercise = mainExercise.querySelector("h1");
      let pExercise = mainExercise.querySelector("p");
      nameExercises.innerHTML = h1Exercise.innerHTML;
      description.innerHTML = pExercise.getAttribute("data-description");
    }).catch(error => {
      console.error('Error:', error);
    });
    addImg(imgSrc);
    exercises.classList.add("hidden-exercises");
    tableExercise.classList.add("width-table");
    btnExercisesGo.innerHTML = "Start exercising";
    started = true;
  };
});

btnExercisesGo.onclick = () => {
  if (started) {
    if (!hasStarted) {
      dataExercise.forEach(item => {
        item.forEach(element => {
          let linkVideo = "";
          element.attachments.forEach(attachment => {
            linkVideo = attachment.video;
          });
          createExercises(element.name, element.ratio, element.sets, linkVideo);
        });
      });
      addVideo();
      // startCount(1);
      startCount(4500);
      isRunning = true;
      hasStarted = true;
      btnExercisesGo.innerHTML = "Pause";
      const data = new FormData();
      data.append("code", code);
      fetch('/insert-pay-day', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: data
      }).then(response => response.json()).then(data => {
        console.log(data);
      }).catch(error => {
        console.error('Error:', error);
      });
    } else {
      if (isRunning) {
        _pauseTimer();
        isRunning = false;
        btnExercisesGo.innerHTML = "Resume";
      } else {
        _startTimer();
        isRunning = true;
        btnExercisesGo.innerHTML = "Pause";
      };
    };
  } else if (btnExercisesGo.getAttribute("data-statement") == "true") {
    code = btnExercisesGo.getAttribute("data-code");
    let dataName = btnExercisesGo.getAttribute("data-name");
    let dataDescription = btnExercisesGo.getAttribute("data-description");
    const data = new FormData();
    data.append("code", code);
    fetch('/get-exercises', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: data
    }).then(response => response.json()).then(data => {
      exercises.classList.add("hidden-exercises");
      tableExercise.classList.add("width-table");
      btnExercisesGo.innerHTML = "Start exercising";
      dataExercise = data;
      dataExercise.forEach(element => {
        let linkVideo = "";
        element.attachments.forEach(attachment => {
          linkVideo = attachment.video;
        });
        createExercises(element.name, element.ratio, element.sets, linkVideo);
      });
    }).catch(error => {
      console.error('Error:', error);
    });
    addVideo();
    startCount(1);
    startCount(4500);
    isRunning = true;
    hasStarted = true;
    btnExercisesGo.innerHTML = "Pause";
    fetch('/insert-pay-day', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: data
    }).then(response => response.json()).then(data => {
      console.log(data);
    }).catch(error => {
      console.error('Error:', error);
    });
    if (isRunning) {
      _pauseTimer();
      isRunning = false;
      btnExercisesGo.innerHTML = "Resume";
    } else {
      _startTimer();
      isRunning = true;
      btnExercisesGo.innerHTML = "Pause";
    };
  };
};

input.addEventListener('input', () => {
  const term = input.value.trim().toLowerCase();
  rows.forEach(row => {
    const text = Array.from(row.children).map(el => el.textContent.trim().toLowerCase()).join(' ');
    if (text.includes(term)) {
      row.style.display = 'flex';
    } else {
      row.style.display = 'none';
    };
  });
});
