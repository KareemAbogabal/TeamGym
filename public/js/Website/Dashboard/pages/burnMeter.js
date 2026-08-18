let html = document.querySelector("html").getAttribute("lang");
let meter = document.querySelector(".meter");
let dot = document.querySelector(".meter .meter-speed span");
let meterIndex = document.querySelector(".meter .meter-speed .index");
let btnDetails = document.querySelectorAll(".btn-details");
let rows = document.querySelectorAll(".row");
let input = document.querySelector('.search-input');
let wither = document.querySelector('.wither');
let userLocation = wither.querySelector('.location');
let day = wither.querySelector('.day');
let date = wither.querySelector('.date');
let deg = wither.querySelector('.deg');
let icon = wither.querySelector('.icon');
let nameState = wither.querySelector('.name-state');
let speed = wither.querySelector('.speed');
let humidity = wither.querySelector('.humidity');
let programs = document.querySelectorAll('.program');
let btnStartProgram = document.querySelectorAll(".btn-start-program");
let programsP = document.querySelectorAll('.program i');
let programsH1 = document.querySelectorAll('.program h1');
let timeStopWatch = document.querySelector('.time');
let mainBtnStart = document.querySelector(".main-btn-start");
let arcFg = document.getElementById('arc-fg');
let timeText = document.getElementById('timeText');
let gaugeSvg = document.getElementById('gauge');
let step = document.querySelector('.step');
let kcal = document.querySelector('.kcal');
let fat = document.querySelector('.fat');
let durationMs = 60 * 1000;
let lastStart = null;
let elapsedSoFar = 0;
let runningTimer = false;
let rafTimer = null;
let nameCardio = 0;
let minutes = 0;
let distance = 0;
let start_latitude = 0;
let start_longitude = 0;
let end_latitude = 0;
let end_longitude = 0;
// start_latitude = "31.41637450452576";
// start_longitude = "31.7865226149387";
// end_latitude = "31.417000";
// end_longitude = "31.787000";

function meterSpeed() {
  let dot = document.querySelector(".meter .meter-speed span");
  let meterIndex = document.querySelector(".meter .meter-speed .index");
  dot.innerHTML = "";
  for (let i = 0; i < 90; i++) {
    let rotate = i * 9;
    let rotateNum = i * (360 / 90);
    if (i % 5 == 0) {
      dot.innerHTML += `
        <p style=" transform: rotate(${rotateNum}deg) translateX(-170px) rotate(${-rotateNum}deg);">${i}</p>
        <div class="dot" style="transform: rotate(${rotate}deg) translateX(-50px); width: 40px;"></div>
      `;
    } else {
      dot.innerHTML += `
        <div class="dot" style="transform: rotate(${rotate}deg) translateX(-50px); width: 25px;"></div>
      `;
    };
  };
  setTimeout(() => {
    meterIndex.style.transform = `rotate(90deg)`;
  }, 500);
  setTimeout(() => {
    meterIndex.style.transform = `rotate(-90deg)`;
  }, 1500);
};

function setActiveProgram(index) {
  programs.forEach((program, i) => {
    program.classList.remove('active');
    program.style.backgroundColor = '#3a3a3a2c';
    programsP[i].style.backgroundColor = '#3d3d3d';
    programsH1[i].style.color = 'var(--colorReverse)';
  });
  if (programs[index]) {
    programs[index].classList.add('active');
    programs[index].style.backgroundColor = 'var(--colorprogram)';
    programsP[index].style.backgroundColor = 'var(--bgprogram)';
    programsH1[index].style.color = 'var(--bgprogram)';
  };
};

document.addEventListener('DOMContentLoaded', () => {
  if (dot) {
    meterSpeed();
  };
  setActiveProgram(0);
});

let _stepListenerAttached = false;
let _stepListenerFn = null;
function detachStepListener(){
  if (_stepListenerAttached && _stepListenerFn){
    try { window.removeEventListener('devicemotion', _stepListenerFn); } catch(e){}
    _stepListenerAttached = false;
    _stepListenerFn = null;
    console.log('step listener detached');
  };
};

function switchMeter(name) {
  detachStepListener();
  meter.innerHTML = "";
  mainBtnStart.innerHTML = "";
  if (name == "Runing" || name == "Running the distance") {
    let speed = `
      <div class="meter-speed">
        <div class="index"></div>
        <span></span>
      </div>
    `;
    let steps = `
      <i class="fa-solid fa-shoe-prints"></i>
      <div class="content">
        <p>Steps</p>
        <p><span class="step">0</span> step</p>
      </div>
    `;
    meter.innerHTML = speed;
    mainBtnStart.innerHTML = steps;
    meterSpeed();
    timerMeterSpeed();
  } else if (name == "treadmill") {
    let time = `
      <div class="meter-time" role="main" aria-labelledby="title">
        <div class="gauge-wrap" aria-hidden="false">
          <svg id="gauge" width="100%" height="100%" viewBox="0 0 340 340">
            <defs>
              <linearGradient id="gGrad" x1="0" x2="1">
                <stop offset="0" stop-color="#6C63FF" stop-opacity="1" />
                <stop offset="1" stop-color="#00D4FF" stop-opacity="1" />
              </linearGradient>
              <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stdDeviation="6" result="b"/>
                <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
              </filter>
            </defs>
            <g transform="translate(170,170)">
              <circle r="140" fill="none" stroke="rgba(255,255,255,0.02)" stroke-width="12" />
              <circle id="arc-fg"
                r="140" cx="0" cy="0"
                fill="none"
                stroke="url(#gGrad)"
                stroke-width="18"
                stroke-linecap="round"
                style="filter:url(#glow); transform-origin: 0px 0px; transform: rotate(-90deg);"
                pathLength="100"
                stroke-dasharray="100 0"
                stroke-dashoffset="100"
              />
            </g>
          </svg>
          <div class="center-display" aria-live="polite">
            <div id="timeText" class="time" contenteditable="true">00:00.00</div>
          </div>
        </div>
      </div>
    `;
    let buttonStart = `
      <button id="startBtn">Start</button>
    `;
    meter.innerHTML = time;
    mainBtnStart.innerHTML = buttonStart;
    timeStopWatch = document.querySelector('.time');
    arcFg = document.getElementById('arc-fg');
    timer();
    timeStopWatch.addEventListener("input", () => {
      const buttonStart = document.getElementById('startBtn');
      buttonStart.onclick = () => {
        timeStopWatch.removeAttribute("contenteditable");
        if (!buttonStart.classList.contains("stop")) {
          if (typeof elapsedSoFar !== 'undefined' && elapsedSoFar > 0 && elapsedSoFar < durationMs) {
            resumeTimer();
          } else {
            startTimer(timeStopWatch.textContent);
          };
          buttonStart.textContent = "Stop";
          buttonStart.classList.add("stop");
        } else {
          pauseTimer();
          buttonStart.textContent = "Start";
          buttonStart.classList.remove("stop");
        };
      };
    });
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

btnDetails.forEach((button, index) => {
  button.onclick = () => {
    button.classList.toggle("show-btn-det");
    rows[index].classList.toggle("show-det-row");
  };
});

(function () {
  let latitude = [];
    let longitude = [];
    let APIkey = "427c81b378ae46fd50ce85cc78c9737c";
    navigator.geolocation.getCurrentPosition((position) => {
      latitude.push(position.coords.latitude);
      longitude.push(position.coords.longitude);
      fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${APIkey}`).then(response => response.json()).then(data => {
        let finalNameState = data.weather[0].description;
        let key = finalNameState.replace(/ /g, "_").toLowerCase();
        let translated = translations.weather[key] || finalNameState;
        let time = new Date();
        let day = time.getDate();
        let year = time.getFullYear();
        let monthsEn = ["jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec"];
        let monthsAr = ["يناير","فبراير","مارس","أبريل","مايو","يونيو","يوليو","أغسطس","سبتمبر","أكتوبر","نوفمبر","ديسمبر"];
        let monthName;
        let daysEn = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
        let daysAr = ["الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت"];
        let dayName;
        if (html == "en") {
          dayName = daysEn[time.getDay()];
          monthName = monthsEn[time.getDay()];
        } else {
          for (let i = 0; i <= daysEn.length - 1; i++) {
            if (daysEn[i] == time.getDay()) {
              dayName = daysAr[i];
              break;
            };
          };
          for (let i = 0; i <= monthsEn.length - 1; i++) {
            if (monthsEn[i] == time.getDay()) {
              monthName = monthsAr[i];
              break;
            };
          };
        };
        wither.classList.add(finalNameState.replace(" ", "-"));
        userLocation.innerHTML = data.name;
        icon.src = `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
        day.innerHTML = `${dayName}`;
        date.innerHTML = `${day} ${monthName}, ${year}`;
        nameState.innerHTML = translated;
        speed.innerHTML = parseInt(data.wind.speed);
        humidity.innerHTML = parseInt(data.main.humidity);
      }).catch(error => {
        console.error(error);
      });
    });
})();

window._programStartLat = null;
window._programStartLng = null;

btnStartProgram.forEach((buttonProgram, index) => {
  buttonProgram.onclick = (e) => {
    nameCardio = programs[index].querySelector(".content h1");
    if (!buttonProgram.classList.contains("stop")) {
      buttonProgram.textContent = "Stop";
      buttonProgram.classList.add("stop");
      if (typeof window.startStopwatch === 'function') window.startStopwatch();
      if (typeof window.startIdleTimer === 'function') window.startIdleTimer();
      switchMeter(nameCardio.textContent);
      setActiveProgram(index);
      if (window._lastGPS && window._lastGPS.latitude !== null) {
        window._programStartLat = window._lastGPS.latitude;
        window._programStartLng = window._lastGPS.longitude;
        // start_latitude = window._programStartLat.toFixed(6);
        // start_longitude = window._programStartLng.toFixed(6);
        start_latitude = "31.41637450452576";
        start_longitude = "31.7865226149387";
        console.log("Start Location Saved:");
        console.log("Start Lat:", window._programStartLat);
        console.log("Start Lng:", window._programStartLng);
      } else {
        console.log("GPS not available yet to save start point");
      };
    } else {
      buttonProgram.textContent = "Start";
      buttonProgram.classList.remove("stop");
      if (typeof window.clearIdleTimer === 'function') window.clearIdleTimer();
      setActiveProgram(0);
      const gps = window._lastGPS || {};
      minutes = window.formatStopwatch();
      distance = Number(window._currentSpeed).toFixed(2);
      start_latitude = window._programStartLat.toFixed(6);
      start_longitude = window._programStartLng.toFixed(6);
      end_latitude = gps.latitude.toFixed(6);
      end_longitude = gps.longitude.toFixed(6);
      saveData(nameCardio, minutes, distance, start_latitude, start_longitude, end_latitude, end_longitude);
      console.log("Start Location:");
      console.log("Lat:", window._programStartLat !== null ? window._programStartLat.toFixed(6) : "n/a");
      console.log("Lng:", window._programStartLng !== null ? window._programStartLng.toFixed(6) : "n/a");
      console.log("Current Location:");
      console.log("Lat:", gps.latitude != null ? gps.latitude.toFixed(6) : "n/a");
      console.log("Lng:", gps.longitude != null ? gps.longitude.toFixed(6) : "n/a");
      console.log("Speed: " + Number(window._currentSpeed).toFixed(2) + " km/h");
      if (typeof window.formatStopwatch === "function") {
        console.log("Stopwatch: " + minutes);
      } else {
        console.log("Stopwatch: not available");
      };
      if (typeof window.resumeStopwatch === "function") {
        window.resumeStopwatch();
      };
    };
  };
});

function timer() {
  const realArcLength = arcFg.getTotalLength();
  const pathLenAttr = Number(arcFg.getAttribute('pathLength'));
  const arcLength = (isFinite(pathLenAttr) && pathLenAttr > 0) ? pathLenAttr : realArcLength;
  arcFg.style.strokeDasharray = String(arcLength);
  arcFg.style.strokeDashoffset = String(arcLength);
  function formatTimeMs(ms){
    ms = Math.max(0, Math.round(ms));
    const totalCentis = Math.floor(ms / 10);
    const centis = totalCentis % 100;
    const totalSeconds = Math.floor(ms / 1000);
    const seconds = totalSeconds % 60;
    const minutes = Math.floor(totalSeconds / 60);
    return `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}.${String(centis).padStart(2,'0')}`;
  };
  let kcalPerMinute = 10;
  let fatPerMinute = 1;
  const kcalPerSecond = kcalPerMinute / 60;
  const fatPerSecond = fatPerMinute / 60;
  let kcalAccum = 0;
  let fatAccum = 0;
  let lastWholeSecond = 0;
  function updateKcalFatUI(){
    if (kcal) kcal.textContent = kcalAccum.toFixed(2);
    if (fat) fat.textContent = fatAccum.toFixed(2);
  };
  updateKcalFatUI();
  function renderByPercentLinear(p){
    const clamped = Math.max(0, Math.min(1, p));
    arcFg.style.transition = 'stroke-dashoffset 90ms linear';
    arcFg.style.strokeDashoffset = String(arcLength * (1 - clamped));
    const msElapsed = Math.round(durationMs * clamped);
    let timeText = document.getElementById('timeText');
    timeText.textContent = formatTimeMs(msElapsed);
  };
  function stepTimer(){
    if (!runningTimer) return;
    const now = performance.now();
    const elapsed = elapsedSoFar + (lastStart ? (now - lastStart) : 0);
    const wholeSeconds = Math.floor(elapsed / 1000);
    if (wholeSeconds > lastWholeSecond) {
      const secondsToAdd = wholeSeconds - lastWholeSecond;
      kcalAccum += kcalPerSecond * secondsToAdd;
      fatAccum += fatPerSecond * secondsToAdd;
      lastWholeSecond = wholeSeconds;
      updateKcalFatUI();
    };
    const p = Math.min(1, elapsed / durationMs);
    renderByPercentLinear(p);
    if (p >= 1){
      arcFg.style.transition = 'stroke-dashoffset 220ms ease';
      arcFg.style.strokeDashoffset = String(0);
      runningTimer = false;
      rafTimer = null;
      lastStart = null;
      elapsedSoFar = durationMs;
      const buttonStart = document.getElementById("startBtn");
      buttonStart.textContent = "Start";
      buttonStart.classList.remove("stop");
      minutes = (durationMs / 1000) / 60;
      saveData(nameCardio, minutes, 0, start_latitude, start_longitude);
      return;
    };
    rafTimer = requestAnimationFrame(stepTimer);
  };
  function startTimer(minutes = 0, seconds = 0){
    let totalMs;
    if (typeof minutes === 'string' && (typeof seconds === 'undefined' || seconds === 0)){
      const s = minutes.trim();
      const m = s.match(/^(\d{1,2}):([0-5]\d)(?:\.(\d{1,2}))?$/);
      if (!m){
        console.warn('صيغة الوقت غير صحيحة — استخدم MM:SS أو MM:SS.cc (مثال: "01:30.00")');
        return;
      };
      const mm = Number(m[1]);
      const ss = Number(m[2]);
      const cs = m[3] ? Number(m[3].padEnd(2, '0')) : 0;
      totalMs = (mm * 60 + ss) * 1000 + cs * 10;
    } else {
      if (typeof seconds === 'undefined'){ seconds = minutes; minutes = 0; }
      totalMs = (Number(minutes) * 60 + Number(seconds)) * 1000;
    };
    if (!isFinite(totalMs) || totalMs <= 0){ console.warn('قيمة المدة غير صحيحة'); return; }
    if (rafTimer) cancelAnimationFrame(rafTimer);
    durationMs = totalMs;
    elapsedSoFar = 0;
    lastStart = performance.now();
    runningTimer = true;
    arcFg.style.transition = 'none';
    arcFg.style.strokeDashoffset = String(arcLength);
    let timeText = document.getElementById('timeText');
    timeText.textContent = formatTimeMs(0);
    kcalAccum = 0;
    fatAccum = 0;
    lastWholeSecond = 0;
    updateKcalFatUI();
    rafTimer = requestAnimationFrame(stepTimer);
  };
  function pauseTimer(){
    if (!runningTimer) return;
    const now = performance.now();
    if (lastStart) elapsedSoFar += (now - lastStart);
    lastStart = null;
    runningTimer = false;
    if (rafTimer) { cancelAnimationFrame(rafTimer); rafTimer = null; }
  };
  function resumeTimer(){
    if (runningTimer) return;
    if (elapsedSoFar >= durationMs) return;
    lastStart = performance.now();
    runningTimer = true;
    if (rafTimer) cancelAnimationFrame(rafTimer);
    rafTimer = requestAnimationFrame(stepTimer);
  };
  function resetTimer(){
    if (rafTimer) { cancelAnimationFrame(rafTimer); rafTimer = null; }
    runningTimer = false;
    lastStart = null;
    elapsedSoFar = 0;
    arcFg.style.transition = 'none';
    arcFg.style.strokeDashoffset = String(arcLength);
    let timeText = document.getElementById('timeText');
    timeText.textContent = formatTimeMs(0);
    kcalAccum = 0;
    fatAccum = 0;
    lastWholeSecond = 0;
    updateKcalFatUI();
  };
  function cancelTimerLoop(){
    if (rafTimer) { cancelAnimationFrame(rafTimer); rafTimer = null; }
    runningTimer = false;
    lastStart = null;
  };
  window.startTimer = startTimer;
  window.pauseTimer = pauseTimer;
  window.resumeTimer = resumeTimer;
  window.resetTimer = resetTimer;
  let rafPulse = null;
  pulseIndicator(2500);
  function pulseIndicator(totalMs = 1500, peakHoldMs = 100){
    if (rafPulse) return;
    const wasRunning = runningTimer;
    if (wasRunning) pauseTimer();
    const half = Math.max(30, Math.floor((totalMs - peakHoldMs) / 2));
    const fullDur = half + peakHoldMs + half;
    const start = performance.now();
    function easeInOutCubic(t){
      return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    };
    function stepPulse(){
      const now = performance.now();
      const progress = now - start;
      let p;
      if (progress <= half) p = progress / half;
      else if (progress <= half + peakHoldMs) p = 1;
      else if (progress <= half + peakHoldMs + half) p = 1 - ((progress - half - peakHoldMs) / half);
      else p = 0;
      const eased = easeInOutCubic(Math.max(0, Math.min(1, p)));
      arcFg.style.transition = 'none';
      arcFg.style.strokeDashoffset = String(arcLength * (1 - eased));
      if (progress < fullDur){
        rafPulse = requestAnimationFrame(stepPulse);
      } else {
        rafPulse = null;
        arcFg.style.transition = 'none';
        arcFg.style.strokeDashoffset = String(arcLength);
        if (wasRunning) resumeTimer();
      };
    };
    rafPulse = requestAnimationFrame(stepPulse);
  };
};

window._stepListenerAttached = window._stepListenerAttached || false;
window._stepListenerFn = window._stepListenerFn || null;

function timerMeterSpeed(){
  if (typeof detachStepListener === 'function') detachStepListener();
  let stepsCount = 0;
  let lastStepTime = 0;
  const minStepInterval = 300;
  const stride = 0.78;
  const userWeightKg = 70;
  const alpha = 0.85;
  let g = { x:0, y:0, z:0 };
  let peakHistory = [];
  const peakHistoryLimit = 40;
  let baseThreshold = 1.2;
  let stopwatchInterval = null;
  let stopwatchStartTs = 0;
  let stopwatchElapsedMs = 0;
  let stopwatchRunning = false;
  function formatMs(ms){
    const totalSec = Math.floor(ms/1000);
    const h = Math.floor(totalSec / 3600);
    const m = Math.floor((totalSec % 3600) / 60);
    const s = totalSec % 60;
    const pad = n => n.toString().padStart(2,'0');
    if (h > 0) return `${pad(h)}:${pad(m)}:${pad(s)}`;
    return `${pad(m)}:${pad(s)}`;
  };
  function startStopwatch(){
    if (stopwatchRunning) return;
    stopwatchStartTs = performance.now() - stopwatchElapsedMs; // استئناف
    stopwatchInterval = setInterval(() => {
      stopwatchElapsedMs = performance.now() - stopwatchStartTs;
    }, 250);
    stopwatchRunning = true;
    console.log('stopwatch started/resumed');
  };
  function stopStopwatch(){
    if (!stopwatchRunning){
      return stopwatchElapsedMs;
    };
    if (stopwatchInterval !== null){
      clearInterval(stopwatchInterval);
      stopwatchInterval = null;
    };
    stopwatchElapsedMs = performance.now() - stopwatchStartTs;
    stopwatchRunning = false;
    console.log('stopwatch stopped');
    return stopwatchElapsedMs;
  };
  let idleTimer = null;
  const idleTimeoutMs = 1 * 60 * 1000;
  function clearIdleTimer(){
    if (idleTimer !== null){
      clearTimeout(idleTimer);
      idleTimer = null;
    };
  };
  function startIdleTimer(){
    clearIdleTimer();
    idleTimer = setTimeout(() => {
      const finalMs = stopStopwatch();
      console.log('لم تُسجَّل حركة لمدة ' + (idleTimeoutMs/60000) + ' دقائق — توقفت الساعة عند: ' + formatMs(finalMs));
      start_latitude = window._programStartLat.toFixed(6);
      start_longitude = window._programStartLng.toFixed(6);
      end_latitude = gps.latitude.toFixed(6);
      end_longitude = gps.longitude.toFixed(6);
      saveData(nameCardio, minutes, distance, start_latitude, start_longitude, end_latitude, end_longitude);
      window.dispatchEvent(new CustomEvent('noMotion7min', {
        detail: { message: 'No motion for idle period', timestamp: Date.now(), elapsedMs: finalMs }
      }));
      clearIdleTimer();
    }, idleTimeoutMs);
  };
  function onMotionDetected(){
    startIdleTimer();
  };
  window.getStopwatchInfo = function() {
    return { elapsedMs: stopwatchElapsedMs, running: stopwatchRunning };
  };
  window.resumeStopwatch = function() {
    startStopwatch();
  };
  window.formatStopwatch = function() {
    return formatMs(stopwatchElapsedMs);
  };
  window._lastGPS = window._lastGPS || { latitude: null, longitude: null, accuracy: null };
  function updateUI(){
    const stepEl = document.querySelector('.step');
    if (stepEl) stepEl.textContent = stepsCount;
    const distanceMeters = Math.round((stepsCount * stride) * 100) / 100;
    const distanceKm = distanceMeters / 1000;
    const calories = userWeightKg * distanceKm * 1.0;
    const fat_g = calories / 9.0;
    if (typeof kcal !== 'undefined' && kcal) kcal.textContent = calories.toFixed(1);
    if (typeof fat !== 'undefined' && fat) fat.textContent = fat_g.toFixed(1);
  };
  let smoothedSpeedKmh = 0;
  const speedSmoothing = 0.16;
  const maxSpeedKmh = 15;
  const magToKmhFactor = 5.0;
  let smoothedMag = 0;
  const magSmoothing = 0.2;
  const magDeadzone = 0.22;
  function handleMotion(e){
    const acc = e.acceleration && (e.acceleration.x !== null || e.acceleration.y !== null || e.acceleration.z !== null)
      ? e.acceleration
      : (e.accelerationIncludingGravity || null);
    if (!acc) return;
    const ax = acc.x || 0;
    const ay = acc.y || 0;
    const az = acc.z || 0;

    g.x = alpha * g.x + (1 - alpha) * ax;
    g.y = alpha * g.y + (1 - alpha) * ay;
    g.z = alpha * g.z + (1 - alpha) * az;

    const lx = ax - g.x;
    const ly = ay - g.y;
    const lz = az - g.z;
    let mag = Math.sqrt(lx*lx + ly*ly + lz*lz);

    smoothedMag = smoothedMag * (1 - magSmoothing) + mag * magSmoothing;
    mag = smoothedMag;

    // أي حركة تتجاوز الـ deadzone تُعتبر نشاطًا (تعيد تهيئة مؤقت الخمول)
    if (mag > magDeadzone) {
      onMotionDetected();
    };

    // حساب السرعة والمؤشر
    let instantKmh = 0;
    if (mag > magDeadzone) {
      instantKmh = Math.min(maxSpeedKmh, (mag - magDeadzone) * magToKmhFactor);
    } else {
      instantKmh = 0;
    };
    smoothedSpeedKmh = smoothedSpeedKmh * (1 - speedSmoothing) + instantKmh * speedSmoothing;
    window._currentSpeed = smoothedSpeedKmh;
    const norm = Math.max(0, Math.min(1, smoothedSpeedKmh / maxSpeedKmh));
    const angle = -90 + norm * 180;
    const meterIndexEl = document.querySelector(".meter .meter-speed .index");
    if (meterIndexEl) {
      meterIndexEl.style.transition = 'transform 220ms linear';
      meterIndexEl.style.transform = `rotate(${angle}deg)`;
    };

    // تتبع القمم للعتبة التكيفية
    if (mag > 0.6) {
      peakHistory.push(mag);
      if (peakHistory.length > peakHistoryLimit) peakHistory.shift();
    };
    let adaptiveThreshold = baseThreshold;
    if (peakHistory.length >= 6) {
      const sorted = peakHistory.slice().sort((a,b)=>a-b);
      const median = sorted[Math.floor(sorted.length/2)] || adaptiveThreshold;
      adaptiveThreshold = Math.max(baseThreshold * 0.7, median * 0.9);
    };

    // اكتشاف خطوة حقيقية
    const now = performance.now();
    if (mag > adaptiveThreshold && (now - lastStepTime) > minStepInterval) {
      const prevStepTime = lastStepTime || 0;
      const stepIntervalMs = prevStepTime ? (now - prevStepTime) : 0;
      stepsCount++;
      lastStepTime = now;
      updateUI();
      // خطوة تعتبر نشاط -> إعادة تهيئة الخمول
      startIdleTimer();
    };
  };

  // ===== attach / detach =====
  async function attach(){
    if (window._stepListenerAttached) return;
    if (typeof DeviceMotionEvent !== 'undefined' && typeof DeviceMotionEvent.requestPermission === 'function'){
      try {
        const perm = await DeviceMotionEvent.requestPermission();
        if (perm !== 'granted'){
          alert('يرجى السماح بالوصول إلى حسّاسات الحركة في إعدادات المتصفح أو من مربع الإذن.');
          return;
        };
      } catch(e){};
    };
    window._stepListenerFn = handleMotion;
    window.addEventListener('devicemotion', window._stepListenerFn);
    window._stepListenerAttached = true;
    console.log('step listener attached');

    // عند البدء: شغّل stopwatch وابدأ مراقبة الخمول
    window.startStopwatch = startStopwatch;
    window.stopStopwatch = stopStopwatch;
    window.resumeStopwatch = startStopwatch; // إن أردت اسم سابق
    window.startIdleTimer = startIdleTimer;
    window.clearIdleTimer = clearIdleTimer;
  };

  function detach(){
    if (window._stepListenerFn) {
      window.removeEventListener('devicemotion', window._stepListenerFn);
      window._stepListenerFn = null;
    };
    window._stepListenerAttached = false;
    clearIdleTimer();
    // إيقاف stopwatch وتسجيل الوقت
    if (stopwatchRunning || stopwatchElapsedMs > 0) {
      const finalMs = stopStopwatch();
      console.log('تم إيقاف stopwatch عند فك المستمع: ' + formatMs(finalMs));
    };
  };
  window.detachStepListener = detach;

  // فعل attach
  attach().then(()=> {
    stepsCount = 0;
    lastStepTime = 0;
    peakHistory = [];
    updateUI();
  }).catch(err => {
    console.warn('attach error', err);
  });
  let watchId = null;
  (function() {
    if (!navigator.geolocation){
      return;
    };
    try {
      watchId = navigator.geolocation.watchPosition(
        pos => {
          const c = pos.coords;
          // kept for debug only
          // console.log("Latitude: " + c.latitude.toFixed(6));
          // console.log("Longitude: " + c.longitude.toFixed(6));
          // console.log("Accuracy: " + c.accuracy + " m");
          window._lastGPS = {
            latitude: c.latitude,
            longitude: c.longitude,
            accuracy: c.accuracy
          };
        },
        err => {
          console.warn("تعذر الحصول على GPS: " + err.message);
        },
        {
          enableHighAccuracy: true,
          maximumAge: 0,
          timeout: 5000
        }
      );
    } catch(e){
      console.warn('geolocation watch failed', e);
    };
  })();
};

window._stepTools = {
  detachStepListener,
  timerMeterSpeed
};

function saveData(name, minutes, distance, start_latitude, start_longitude, end_latitude = null, end_longitude = null) {
  fetch('/save-data-cardio', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      code_client: code_client,
      name: name.textContent,
      minutes: minutes,
      distance: distance,
      start_latitude: start_latitude,
      start_longitude: start_longitude,
      end_latitude: end_latitude,
      end_longitude: end_longitude,
    })
  }).then(async response => {
    if (!response.ok) {
      const text = await response.text();
      console.error('Failed to update status', response.status, text);
      return;
    };
    const data = await response.json().catch(()=>null);
    console.log('Saved successfully', data);
    if (window.TeamGymNotify) {
      window.TeamGymNotify({ type: 'success', message: 'saved-successfully' });
    }
  })
  .catch(error => {
    console.error('Network/Error:', error);
  });
};
