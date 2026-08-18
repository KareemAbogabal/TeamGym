let exerciseCards = document.querySelectorAll('.exercise-card');
let detailPanel = document.getElementById('exerciseDetailPanel');
let detailName = detailPanel ? detailPanel.querySelector('.detail-name') : null;
let detailDescription = detailPanel ? detailPanel.querySelector('.detail-description') : null;
let shapesBody = detailPanel ? detailPanel.querySelector('.shapes-body') : null;
let detailMedia = detailPanel ? detailPanel.querySelector('.detail-media') : null;
let mediaContainer = detailPanel ? detailPanel.querySelector('.media-container') : null;
let detailImg = detailPanel ? detailPanel.querySelector('.detail-img') : null;
let btnExercisesGo = detailPanel ? detailPanel.querySelector('.btn-exercises-go') : null;
let input = document.querySelector('.search-input');
let rows = document.querySelectorAll('.table .row');
let isRunning = false;
let hasStarted = false;
let started = false;
let selectedCode = null;
let selectedElements = [];
let currentMediaBtn = null;

exerciseCards.forEach(card => {
  card.addEventListener('click', (e) => {
    if (e.target.closest('.shape-media-btn')) return;
    const isUnactive = card.classList.contains('unactive');
    if (isUnactive) {
      card.classList.add('reject');
      setTimeout(() => card.classList.remove('reject'), 710);
      return;
    }

    exerciseCards.forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');

    const name = card.getAttribute('data-name');
    const description = card.getAttribute('data-description');
    const code = card.getAttribute('data-code');
    const statement = card.getAttribute('data-statement');
    let elements = [];
    try {
      elements = JSON.parse(card.getAttribute('data-elements'));
    } catch (err) {}

    selectedCode = code;
    selectedElements = elements;

    if (detailName) detailName.textContent = name || '';
    if (detailDescription) detailDescription.textContent = description || '';

    if (detailMedia) detailMedia.classList.remove('visible');
    currentMediaBtn = null;

    if (shapesBody) shapesBody.innerHTML = '';
    elements.forEach((el, idx) => {
      const row = document.createElement('div');
      row.className = 'row';

      const pName = document.createElement('p');
      pName.textContent = el.name || '';
      pName.className = 'search';
      row.appendChild(pName);

      const pRatio = document.createElement('p');
      pRatio.textContent = el.ratio || '';
      row.appendChild(pRatio);

      const pSets = document.createElement('p');
      pSets.textContent = el.sets || '';
      row.appendChild(pSets);

      const btnsWrap = document.createElement('div');
      btnsWrap.className = 'shape-media-btns';

      const attachments = el.attachments || [];
      const hasImg = attachments.length > 0 && attachments[0].img && attachments[0].img !== '';
      const hasVideo = attachments.length > 0 && attachments[0].video && attachments[0].video !== '';

      const imgBtn = document.createElement('button');
      imgBtn.type = 'button';
      imgBtn.className = 'shape-media-btn';
      imgBtn.title = 'Show image';
      imgBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';
      if (!hasImg) {
        imgBtn.style.opacity = '0.3';
        imgBtn.style.pointerEvents = 'none';
      }
      imgBtn.addEventListener('click', (ev) => {
        ev.stopPropagation();
        showImage(attachments[0].img);
        btnsWrap.querySelectorAll('.shape-media-btn').forEach(b => b.classList.remove('active-btn'));
        imgBtn.classList.add('active-btn');
        shapesBody.querySelectorAll('.row').forEach(r => r.classList.remove('active-row'));
        row.classList.add('active-row');
        currentMediaBtn = imgBtn;
      });
      btnsWrap.appendChild(imgBtn);

      const videoBtn = document.createElement('button');
      videoBtn.type = 'button';
      videoBtn.className = 'shape-media-btn';
      videoBtn.title = 'Show video';
      videoBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>';
      if (!hasVideo) {
        videoBtn.style.opacity = '0.3';
        videoBtn.style.pointerEvents = 'none';
      }
      videoBtn.addEventListener('click', (ev) => {
        ev.stopPropagation();
        showVideo(attachments[0].video);
        btnsWrap.querySelectorAll('.shape-media-btn').forEach(b => b.classList.remove('active-btn'));
        videoBtn.classList.add('active-btn');
        shapesBody.querySelectorAll('.row').forEach(r => r.classList.remove('active-row'));
        row.classList.add('active-row');
        currentMediaBtn = videoBtn;
      });
      btnsWrap.appendChild(videoBtn);

      row.appendChild(btnsWrap);
      shapesBody.appendChild(row);
    });

    if (detailPanel) {
      detailPanel.classList.add('visible');
    }

    if (btnExercisesGo) {
      if (statement === 'true') {
        btnExercisesGo.textContent = tGo || 'Go';
        started = true;
      } else {
        started = false;
      }
    }
  });
});

function showImage(src) {
  if (!detailMedia || !mediaContainer) return;
  const existingVideo = mediaContainer.querySelector('video');
  if (existingVideo) existingVideo.remove();
  detailImg.src = src || defaultExerciseImg || '';
  detailImg.style.display = 'block';
  detailMedia.classList.add('visible');
}

function showVideo(src) {
  if (!detailMedia || !mediaContainer) return;
  if (!src || src === '') return;
  detailImg.style.display = 'none';
  let video = mediaContainer.querySelector('video');
  if (!video) {
    video = document.createElement('video');
    video.setAttribute('controls', '');
    video.setAttribute('controlsList', 'nodownload');
    mediaContainer.appendChild(video);
  }
  video.src = src;
  video.style.display = 'block';
  detailMedia.classList.add('visible');
}

if (btnExercisesGo) {
  btnExercisesGo.addEventListener('click', () => {
    if (started && selectedCode) {
      if (!hasStarted) {
        startCount(4500);
        isRunning = true;
        hasStarted = true;
        btnExercisesGo.textContent = 'Pause';

        const data = new FormData();
        data.append('code', selectedCode);
        fetch('/insert-pay-day', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: data
        }).then(r => r.json()).then(d => console.log(d)).catch(err => console.error(err));
      } else {
        if (isRunning) {
          _pauseTimer();
          isRunning = false;
          btnExercisesGo.textContent = 'Resume';
        } else {
          _startTimer();
          isRunning = true;
          btnExercisesGo.textContent = 'Pause';
        }
      }
    }
  });
}

function startCount(count) {
  const unit = count > 1 ? 'seconds' : 'hours';
  const totalSeconds = unit === 'seconds' ? count : count * 3600;
  const progressBar = document.getElementById('timer');
  const timeLabel = document.getElementById('time-label');
  let timerId;
  const KEY_START = 'timerStart';
  const KEY_ELAPSED = 'timerElapsed';
  const KEY_ENDED = 'timerEnded';
  const KEY_PAUSED = 'timerPaused';

  function saveState(start, elapsed, ended, paused) {
    localStorage.setItem(KEY_START, start);
    localStorage.setItem(KEY_ELAPSED, elapsed);
    localStorage.setItem(KEY_ENDED, ended);
    localStorage.setItem(KEY_PAUSED, paused);
  }

  function loadState() {
    return {
      start: parseInt(localStorage.getItem(KEY_START), 10) || 0,
      elapsed: parseInt(localStorage.getItem(KEY_ELAPSED), 10) || 0,
      ended: localStorage.getItem(KEY_ENDED) === 'true',
      paused: localStorage.getItem(KEY_PAUSED) === 'true',
    };
  }

  function render(elapsed) {
    progressBar.max = totalSeconds;
    progressBar.value = elapsed;
    if (elapsed === 0) {
      timeLabel.textContent = unit === 'seconds' ? count + 's' : count + 'h';
    } else {
      if (unit === 'seconds') {
        timeLabel.textContent = elapsed + 's';
      } else {
        const remaining = totalSeconds - elapsed;
        const mm = String(Math.floor(remaining / 60)).padStart(2, '0');
        const ss = String(remaining % 60).padStart(2, '0');
        timeLabel.textContent = mm + ':' + ss;
      }
    }
    const pct = (elapsed / totalSeconds) * 100;
    timeLabel.style.left = elapsed === 0 ? '0%' : 'calc(' + pct + '% - 15px)';
  }

  function tick() {
    const state = loadState();
    const now = Date.now();
    let elapsed = Math.floor((now - state.start) / 1000);
    if (elapsed >= totalSeconds) elapsed = totalSeconds;
    render(elapsed);
    saveState(state.start, elapsed, elapsed >= totalSeconds, false);
    if (elapsed >= totalSeconds) clearInterval(timerId);
  }

  function startTimer() {
    const state = loadState();
    let startTime;
    if (state.paused && state.elapsed < totalSeconds) {
      startTime = Date.now() - state.elapsed * 1000;
    } else {
      startTime = Date.now();
      state.elapsed = 0;
    }
    saveState(startTime, state.elapsed, false, false);
    render(state.elapsed);
    clearInterval(timerId);
    timerId = setInterval(tick, 1000);
  }

  function pauseTimer() {
    clearInterval(timerId);
    const state = loadState();
    saveState(state.start, state.elapsed, false, true);
  }

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
}

if (input) {
  input.addEventListener('input', () => {
    const term = input.value.trim().toLowerCase();
    rows.forEach(row => {
      const text = Array.from(row.children).map(el => el.textContent.trim().toLowerCase()).join(' ');
      row.style.display = text.includes(term) ? 'flex' : 'none';
    });
  });
}
