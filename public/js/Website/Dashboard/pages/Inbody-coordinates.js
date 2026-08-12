const inbodyUpload = document.querySelector(".inbodyUpload");
const statusEl = document.getElementById("status");
const imgInBody = document.querySelector(".imgInBody");
const btnShowImgInBody = document.querySelector(".show-img-inBody");
const btnRemoveImgInBody = document.querySelector(".remove-img-inBody");
const imgUp = document.querySelector(".img-up");

const weight = document.querySelector(".weight");
const bmi = document.querySelector(".BMI");
const pbf = document.querySelector(".PBF");
const smm = document.querySelector(".SMM");
const kcal = document.querySelector(".KCAL");
const tbw = document.querySelector(".water");
const bodyFat = document.querySelector(".fat_mass");
const protein = document.querySelector(".protein");

// Segmental lean
const rightArmLean = document.querySelector(".right-arm-lean-kg");
const leftArmLean = document.querySelector(".left-arm-lean-kg");
const rightLegLean = document.querySelector(".right-leg-lean-kg");
const leftLegLean = document.querySelector(".left-leg-lean-kg");

// Segmental fat
const rightArmFat = document.querySelector(".right-arm-fat-kg");
const leftArmFat = document.querySelector(".left-arm-fat-kg");
const rightLegFat = document.querySelector(".right-leg-fat-kg");
const leftLegFat = document.querySelector(".left-leg-fat-kg");
let currentFile = null;

inbodyUpload.addEventListener("change", (e) => {
  const f = e.target.files[0];
  if (!f) {
    statusEl.textContent = "لم يتم رفع صورة.";
    return;
  };
  currentFile = f;
  statusEl.textContent = "صورة جاهزة OCR";
  run();
});

btnShowImgInBody.onclick = () => {
  imgUp.classList.add("show-img-up");
};

btnRemoveImgInBody.onclick = () => {
  imgUp.classList.remove("show-img-up");
};

function ensureImageLoaded(img) {
  return new Promise((resolve, reject) => {
    if (!img) return reject(new Error('No image element'));
    if (img.complete && img.naturalWidth) return resolve();
    img.onload = () => resolve();
    img.onerror = (e) => reject(e);
  });
};

async function annotateSourceAndShow(source) {
  if (!source) return;
  if (typeof btnShowImgInBody !== 'undefined' && btnShowImgInBody) {
    try {
      btnShowImgInBody.classList.add("show-button-img");
    } catch(e) {};
  };
  let rawCanvas;
  if (source instanceof File || source instanceof Blob) {
    const canvases = await preprocessToCanvas(source, 1, false);
    rawCanvas = canvases.rawCanvas;
  } else if (source instanceof HTMLImageElement) {
    await ensureImageLoaded(source);
    rawCanvas = document.createElement('canvas');
    rawCanvas.width = source.naturalWidth;
    rawCanvas.height = source.naturalHeight;
    rawCanvas.getContext('2d').drawImage(source, 0, 0);
  } else if (source instanceof HTMLCanvasElement) {
    rawCanvas = source;
  } else {
    throw new TypeError('Unsupported source type. Pass File, Blob, HTMLImageElement or HTMLCanvasElement.');
  };
  const naturalW = rawCanvas.width;
  const naturalH = rawCanvas.height;
  const imgUpEl = document.querySelector(".img-up .content .img");
  if (!imgUpEl) {
    console.warn('annotateSourceAndShow: target container (.img-up .content .img) not found');
    return { wrapper: null, img: null, overlay: null };
  };
  const maxWidth = 900;
  const containerW = imgUpEl.clientWidth || Math.min(naturalW, maxWidth);
  const displayW = Math.min(containerW, maxWidth);
  const displayH = Math.round((naturalH * displayW) / naturalW);
  const img = document.createElement('img');
  img.src = rawCanvas.toDataURL();
  img.style.display = 'block';
  img.style.objectFit = 'contain';
  img.style.userSelect = 'none';
  img.alt = 'inbody-image';
  await ensureImageLoaded(img);
  img.style.width = `${displayW}px`;
  img.style.height = `${displayH}px`;
  const overlay = document.createElement('canvas');
  overlay.style.width = `${displayW}px`;
  overlay.style.height = `${displayH}px`;
  overlay.style.position = 'absolute';
  overlay.style.left = '0';
  overlay.style.top = '0';
  overlay.style.pointerEvents = 'none';
  overlay.style.display = 'block';
  const dpr = window.devicePixelRatio || 1;
  overlay.width = Math.round(displayW * dpr);
  overlay.height = Math.round(displayH * dpr);
  const ctx = overlay.getContext('2d');
  ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  const wrapper = document.createElement('div');
  wrapper.className = "wrapper";
  wrapper.style.position = "relative";
  wrapper.style.width = `${displayW}px`;
  wrapper.style.height = `${displayH}px`;
  wrapper.appendChild(img);
  wrapper.appendChild(overlay);
  imgUpEl.innerHTML = "";
  imgUpEl.appendChild(wrapper);
  const colorMap = {
    WEIGHT_ORIG: "#ff3333", TBW_ORIG: "#ff9933", PROTEIN_ORIG: "#ffcc33",
    FAT_MASS_ORIG: "#33aaff", BMI_ORIG: "#9933ff", PBF_ORIG: "#33cc66",
    SMM_ORIG: "#cc33aa", KCAL_ORIG: "#33cccc", ORIG_LEAN: "#66ccff",
    ORIG_FAT: "#ff66cc", LEFT_ARM_FAT_ORIG: "#66ff66", RIGHT_ARM_FAT_ORIG: "#66ff66",
    LEFT_LEG_FAT_ORIG: "#66ff66", RIGHT_LEG_FAT_ORIG: "#66ff66",
  };
  const expectedKeys = [
    'WEIGHT_ORIG','TBW_ORIG','PROTEIN_ORIG','FAT_MASS_ORIG','BMI_ORIG','PBF_ORIG',
    'SMM_ORIG','KCAL_ORIG',
    'ORIG_LEAN','LEFT_ARM_LEAN_ORIG','RIGHT_ARM_LEAN_ORIG','LEFT_LEG_LEAN_ORIG','RIGHT_LEG_LEAN_ORIG',
    'ORIG_FAT','LEFT_ARM_FAT_ORIG','RIGHT_ARM_FAT_ORIG','LEFT_LEG_FAT_ORIG','RIGHT_LEG_FAT_ORIG'
  ];
  expectedKeys.forEach(key => {
    try {
      const box = window[key];
      if (!box) return;
      const scaled = scaleOrigBoxToCanvas(box, overlay);
      const sheetA = displaySheetAngle();
      drawBoxLabel(ctx, scaled, key, 1, colorMap[key] || "#ff3333", sheetA);
    } catch (err) {
      console.warn('annotate draw error for', key, err);
    }
  });
  return { wrapper, img, overlay };
};

if (imgInBody) {
  annotateSourceAndShow(imgInBody);
  statusEl.innerHTML = "تم التحقق بواسطة OCR";
};

const REF_W = 2100;
const REF_H = 2560;

const WEIGHT_ORIG = { x: 498, y: 630, w: 506, h: 55 };
const TBW_ORIG = { x: 512, y: 399, w: 489, h: 50 };
const PROTEIN_ORIG = { x: 506, y: 454, w: 494, h: 57 };
const FAT_MASS_ORIG = { x: 519, y: 567, w: 479, h: 59 };
const BMI_ORIG = { x: 363, y: 1281, w: 836, h: 63 };
const PBF_ORIG = { x: 359, y: 1342, w: 851, h: 50 };
const SMM_ORIG = { x: 359, y: 958, w: 840, h: 40 };
const KCAL_ORIG = { x: 1258, y: 811, w: 395, h: 52 };

const ORIG_LEAN = { x: 134, y: 1483, w: 531, h: 578 };
const LEFT_ARM_LEAN_ORIG = { x: 167, y: 1568, w: 155, h: 50 };
const RIGHT_ARM_LEAN_ORIG = { x: 487, y: 1567, w: 126, h: 50 };
const LEFT_LEG_LEAN_ORIG = { x: 178, y: 1846, w: 149, h: 61 };
const RIGHT_LEG_LEAN_ORIG = { x: 476, y: 1845, w: 145, h: 61 };

const ORIG_FAT = { x: 689, y: 1483, w: 531, h: 578 };
const LEFT_ARM_FAT_ORIG = { x: 750, y: 1569, w: 149, h: 50 };
const RIGHT_ARM_FAT_ORIG = { x: 1027, y: 1569, w: 149, h: 50 };
const LEFT_LEG_FAT_ORIG = { x: 762, y: 1840, w: 124, h: 44 };
const RIGHT_LEG_FAT_ORIG = { x: 1031, y: 1840, w: 124, h: 44 };

// fixCanvas() mutates these constants in place, so snapshot the pristine values
// once and restore them before every run to keep the offsets idempotent across
// multiple uploads in the same page session.
const __BOX_REFS = {
  WEIGHT_ORIG, TBW_ORIG, PROTEIN_ORIG, FAT_MASS_ORIG, BMI_ORIG, PBF_ORIG,
  SMM_ORIG, KCAL_ORIG, ORIG_LEAN, LEFT_ARM_LEAN_ORIG, RIGHT_ARM_LEAN_ORIG,
  LEFT_LEG_LEAN_ORIG, RIGHT_LEG_LEAN_ORIG, ORIG_FAT, LEFT_ARM_FAT_ORIG,
  RIGHT_ARM_FAT_ORIG, LEFT_LEG_FAT_ORIG, RIGHT_LEG_FAT_ORIG,
};
const __BOX_PRIMORDIAL = {};
for (const k of Object.keys(__BOX_REFS)) {
  __BOX_PRIMORDIAL[k] = { ...__BOX_REFS[k] };
}
function resetFixCanvasBoxes() {
  for (const k of Object.keys(__BOX_REFS)) {
    const b = __BOX_REFS[k];
    Object.assign(b, __BOX_PRIMORDIAL[k]);
  }
}

function fixCanvas(origBox, fields, percent = 1) {
  const origImgW = window.__orig_img_w || REF_W;
  const origImgH = window.__orig_img_h || REF_H;
  const baselineW = 3060;
  const baselineH = 4080;
  const strategy = 1.0;
  function mitigation() {
    if (fields.x !== undefined && fields.x !== null) {
      fields.x -= strategy;
    };

    if (fields.y !== undefined && fields.y !== null) {
      fields.y -= strategy;
    };

    if (fields.w !== undefined && fields.w !== null) {
      fields.w -= strategy;
    };

    if (fields.h !== undefined && fields.h !== null) {
      fields.h -= strategy;
    };
  };
  if (origImgW > baselineW && origImgH > baselineH) {
    mitigation();
  };
  if (window.__orig_img_w && window.__orig_img_h && (window.__orig_img_w > REF_W || window.__orig_img_h > REF_H)) {
    if (fields.x !== undefined && fields.x !== null) {
      const dx = (REF_W * fields.x) / 100;
      origBox.x += dx;
    };

    if (fields.y !== undefined && fields.y !== null) {
      const dy = (REF_H * fields.y) / 100;
      origBox.y += dy;
    };

    if (fields.w !== undefined && fields.w !== null) {
      const dw = (origBox.w * fields.w) / 100;
      origBox.w += dw;
    };

    if (fields.h !== undefined && fields.h !== null) {
      const dh = (origBox.h * fields.h) / 100;
      origBox.h += dh;
    };
    console.log("fixCanvas applied ->", origBox);
  };
  return origBox;
};

function relBoxToCanvas(relBox, rawCanvas) {
  const canvasW = rawCanvas.width;
  const canvasH = rawCanvas.height;

  // If we detected the physical sheet inside the photo, map the REF box layout
  // onto that sheet rectangle instead of the whole image. This corrects photos
  // where the report doesn't fill the frame (margins, tilted/zoomed shots).
  const sheet = window.__sheet_bounds;
  if (sheet && !sheet.fullFrame && sheet.w > 0 && sheet.h > 0) {
    const x = Math.round(sheet.x + relBox.x * sheet.w);
    const y = Math.round(sheet.y + relBox.y * sheet.h);
    const w = Math.round(relBox.w * sheet.w);
    const h = Math.round(relBox.h * sheet.h);

    const MIN_W = 100,
      MIN_H = 100;
    let finalX = x,
      finalY = y,
      finalW = w,
      finalH = h;
    if (finalW < MIN_W) {
      const diff = MIN_W - finalW;
      finalW = MIN_W;
      finalX = Math.max(0, finalX - Math.floor(diff / 2));
    }
    if (finalH < MIN_H) {
      const diff = MIN_H - finalH;
      finalH = MIN_H;
      finalY = Math.max(0, finalY - Math.floor(diff / 2));
    }
    if (finalX + finalW > canvasW) finalW = Math.max(1, canvasW - finalX);
    if (finalY + finalH > canvasH) finalH = Math.max(1, canvasH - finalY);
    return { x: finalX, y: finalY, w: finalW, h: finalH };
  }

  // استخدام الأبعاد الأصلية للصورة (إذا موجودة) وإلا استخدم REF
  const origImgW = window.__orig_img_w || REF_W;
  const origImgH = window.__orig_img_h || REF_H;

  // حساب عوامل التحجيم على كل محور
  const scaleX = canvasW / origImgW;
  const scaleY = canvasH / origImgH;

  // نعطي أولوية لملء العرض (كما طلبت)
  const scale = scaleX;
  const usedW = Math.round(origImgW * scale);
  const usedH = Math.round(origImgH * scale);

  // نحسب إزاحة أفقي (للاحتفاظ بمحاذاة وسط العرض) ونضبط الإزاحة العمودية لأعلى (0)
  const offsetX = Math.round((canvasW - usedW) / 2);
  const offsetY = 0;

  // تحويل الإحداثيات النسبية إلى إحداثيات raw-canvas
  const x = Math.round(relBox.x * usedW + offsetX);
  const y = Math.round(relBox.y * usedH + offsetY);
  const w = Math.round(relBox.w * usedW);
  const h = Math.round(relBox.h * usedH);

  // ضمان أبعاد دنيا وحماية من الخروج عن حدود الـcanvas
  const MIN_W = 100,
    MIN_H = 100;
  let finalX = x,
    finalY = y,
    finalW = w,
    finalH = h;

  if (finalW < MIN_W) {
    const diff = MIN_W - finalW;
    finalW = MIN_W;
    finalX = Math.max(0, finalX - Math.floor(diff / 2));
  };
  if (finalH < MIN_H) {
    const diff = MIN_H - finalH;
    finalH = MIN_H;
    finalY = Math.max(0, finalY - Math.floor(diff / 2));
  };

  if (finalX + finalW > canvasW) finalW = Math.max(1, canvasW - finalX);
  if (finalY + finalH > canvasH) finalH = Math.max(1, canvasH - finalY);

  // --- هنا نطبع الإحداثيات ---
  try {
    const safeScale = scale && scale !== 0 ? scale : 1;
    const origX = Math.round((finalX - offsetX) / safeScale);
    const origY = Math.round((finalY - offsetY) / safeScale);
    const origW = Math.round(finalW / safeScale);
    const origH = Math.round(finalH / safeScale);
    // console.log(relBox);

    // console.log("relBoxToCanvas => mapped to ORIGINAL image px:", {
    //   x: origX,
    //   y: origY,
    //   w: origW,
    //   h: origH,
    // });
  } catch (e) {
    console.warn("relBoxToCanvas: print failed", e);
  };
  return { x: finalX, y: finalY, w: finalW, h: finalH };
};

function normNum(s) {
  if (s == null) return null;
  s = String(s)
    .trim()
    .replace(/[，,]/g, ".")
    .replace(/[Oo]/g, "0")
    .replace(/[lI\|]/g, "1");
  const m = s.match(/-?[\d]{1,6}(?:[.,][\d]{1,3})?|-?[\d]{1,6}/);
  return m ? parseFloat(m[0]) : null;
};

/**
  * Try to find a numeric token near a word that matches labelRegex.
  * fullWords = fullRes.data.words (Tesseract layout words)
  * labelRegex = RegExp (fuzzy label, e.g. /BMI/i or /الوزن|وزن/i)
  * options: { preferPercent, maxLookRight, maxLookLeft, sameLineTolerance }
  */
function findValueNearLabel(fullWords, labelRegex, options = {}) {
  if (!Array.isArray(fullWords)) return null;
  const {
    preferPercent = false,
    maxLookRight = 6,
    maxLookLeft = 3,
    sameLineTolerance = 14,
    minV = -Infinity,
    maxV = Infinity,
  } = options;

  const numRe = /-?[\d]{1,3}(?:[.,][\d]{1,3})?/;

  for (let i = 0; i < fullWords.length; i++) {
    const w = fullWords[i];
    if (!w || !w.text) continue;
    const txt = (w.text || "").replace(/[,，]/g, ".").trim();
    if (!labelRegex.test(txt)) continue;

    // 1) check immediate neighbors to the right (common)
    for (let d = 1; d <= maxLookRight; d++) {
      const right = fullWords[i + d];
      if (!right) break;
      const s = (right.text || "").replace(/[,，]/g, ".").trim();
      // percent preferred
      if (preferPercent) {
        const pct = s.match(/(\d{1,3}[.,]?\d{0,3})\s*%/);
        if (pct) {
          const v = normNum(pct[1]);
          if (v !== null && v >= minV && v <= maxV) return v;
        };
      };
      const m = s.match(numRe);
      if (m) {
        const v = normNum(m[0]);
        if (v !== null && v >= minV && v <= maxV) return v;
      };
    };

    // 2) check left neighbors
    for (let d = 1; d <= maxLookLeft; d++) {
      const left = fullWords[i - d];
      if (!left) break;
      const s = (left.text || "").replace(/[,，]/g, ".").trim();
      if (preferPercent) {
        const pct = s.match(/(\d{1,3}[.,]?\d{0,3})\s*%/);
        if (pct) {
          const v = normNum(pct[1]);
          if (v !== null && v >= minV && v <= maxV) return v;
        };
      };
      const m = s.match(numRe);
      if (m) {
        const v = normNum(m[0]);
        if (v !== null && v >= minV && v <= maxV) return v;
      };
    };

    // 3) try same baseline (words with similar center-y)
    const midY = (w.bbox && (w.bbox.y0 + w.bbox.y1) / 2) || null;
    if (midY !== null) {
      const sameLine = fullWords.filter(
        (f) =>
          f &&
          f.bbox &&
          Math.abs((f.bbox.y0 + f.bbox.y1) / 2 - midY) < sameLineTolerance
      );
      for (const f of sameLine) {
        const s = (f.text || "").replace(/[,，]/g, ".").trim();
        if (preferPercent) {
          const pct = s.match(/(\d{1,3}[.,]?\d{0,3})\s*%/);
          if (pct) {
            const v = normNum(pct[1]);
            if (v !== null && v >= minV && v <= maxV) return v;
          };
        };
        const m = s.match(numRe);
        if (m) {
          const v = normNum(m[0]);
          if (v !== null && v >= minV && v <= maxV) return v;
        };
      };
    };
  };
  return null;
};

/** A convenience wrapper: try multiple label regexes in order (useful for Arabic+English) */
function findValueByLabels(fullWords, labelRegexes, options) {
  for (const rx of labelRegexes) {
    const v = findValueNearLabel(fullWords, rx, options);
    if (v !== null) return v;
  };
  return null;
};

function parseNumberInRange(t, minVal, maxVal) {
  if (!t) return null;
  let m = t.match(/\d{1,3}[.,]\d{1,3}/g);
  if (m) {
    for (const mm of m) {
      const v = normNum(mm);
      if (v !== null && v >= minVal && v <= maxVal) return v;
    };
  };
  m = t.match(/(\d{1,3}[.,]?\d{0,3})\s*%/);
  if (m) {
    const v = normNum(m[1]);
    if (v !== null && v >= minVal && v <= maxVal) return v;
  };
  // 4-6 digit integers (e.g. BMR "1710") must not be split into "171.0" by the
  // parts-join heuristic below. Take the first clean multi-digit token in range.
  m = t.match(/\d{4,6}(?:[.,]\d{1,3})?/);
  if (m) {
    const v = normNum(m[0]);
    if (v !== null && v >= minVal && v <= maxVal) return v;
  };
  const parts = t.match(/\d{1,3}/g);
  if (parts && parts.length >= 2) {
    for (let i = 0; i < parts.length - 1; i++) {
      const cand = parts[i] + "." + parts[i + 1];
      const vv = normNum(cand);
      if (vv !== null && vv >= minVal && vv <= maxVal) return vv;
    };
  };
  if (parts) {
    parts.sort((a, b) => b.length - a.length);
    for (const p of parts) {
      const vv = normNum(p);
      if (vv !== null && vv >= minVal && vv <= maxVal) return vv;
    };
  };
  return null;
};

function isReasonableCandidate(value, text, minVal, maxVal, field = "generic") {
  if (value === null || value === undefined || !Number.isFinite(value)) return false;
  if (value < minVal || value > maxVal) return false;

  const t = String(text || "").toLowerCase();
  const digits = (t.match(/\d/g) || []).length;
  if (digits > 12) return false;

  if (field === "bmi") {
    if (value < 8 || value > 60) return false;
    if (t.includes("kg") && !t.includes("/")) return false;
  }
  if (field === "pbf") {
    if (value > 100) return false;
    if (t.includes("kg") && !t.includes("%")) return false;
  }
  if (field === "kcal") {
    if (value > 10000) return false;
  }
  if (field === "weight" || field === "water" || field === "protein") {
    if (t.includes("%")) return false;
  }
  return true;
}

function extractNumberCandidates(text) {
  const arr = [];
  const re = /([0-9]{1,3}[.,][0-9]{1,3}|[0-9]{1,3})\s*(kg|cm|%|level)?/gi;
  let m;
  while ((m = re.exec(text)) !== null) {
    arr.push({
      num: normNum(m[1]),
      unit: m[2] || null,
      idx: m.index,
      ctx: text.slice(Math.max(0, m.index - 40), m.index + 40),
    });
  };
  return arr;
};

// خريطة اسمية للصناديق لطباعتها دون الحاجة لتعديل باقي الاستدعاءات

function scaleOrigBoxToCanvas(origBox, rawCanvas) {
  // تحويل الإحداثيات المطلقة إلى نسبية أولاً
  const relBox = {
    x: origBox.x / REF_W,
    y: origBox.y / REF_H,
    w: origBox.w / REF_W,
    h: origBox.h / REF_H,
  };

  // ثم استخدام الدالة المحسنة لتحويل الإحداثيات النسبية
  return relBoxToCanvas(relBox, rawCanvas);
};

function cropCanvas(baseCanvas, x, y, w, h) {
  const c = document.createElement("canvas");
  c.width = Math.round(w);
  c.height = Math.round(h);
  const ctx = c.getContext("2d", { willReadFrequently: true });
  ctx.drawImage(
    baseCanvas,
    Math.round(x),
    Math.round(y),
    Math.round(w),
    Math.round(h),
    0,
    0,
    Math.round(w),
    Math.round(h)
  );
  return c;
};

function scaleCanvasTo(src, factor) {
  const c = document.createElement("canvas");
  c.width = Math.max(1, Math.round(src.width * factor));
  c.height = Math.max(1, Math.round(src.height * factor));
  const ctx = c.getContext("2d", { willReadFrequently: true });
  ctx.imageSmoothingEnabled = false;
  ctx.drawImage(src, 0, 0, c.width, c.height);
  return c;
};

// OCR a tight pixel bbox (small padding) at native + 1.5x scale, PSM 7, and pick
// the highest-confidence plausible parse.
async function tightCropRead(bbox, rawCanvas, minVal, maxVal, field) {
  const padX = Math.round((bbox.x1 - bbox.x0) * 0.12) + 8;
  const padY = Math.round((bbox.y1 - bbox.y0) * 0.15) + 6;
  const x = Math.max(0, bbox.x0 - padX);
  const y = Math.max(0, bbox.y0 - padY);
  const w = Math.min(rawCanvas.width - x, bbox.x1 - bbox.x0 + 2 * padX);
  const h = Math.min(rawCanvas.height - y, bbox.y1 - bbox.y0 + 2 * padY);
  if (w < 8 || h < 8) return null;
  const crop = cropCanvas(rawCanvas, x, y, w, h);
  const config = {
    logger: () => {},
    tessedit_char_whitelist: "0123456789.,%",
    tessedit_pageseg_mode: "7",
  };
  let best = null;
  const variants = [
    ["n", crop],
    ["x1.5", scaleCanvasTo(crop, 1.5)],
  ];
  for (const [label, cv] of variants) {
    try {
      const res = await Tesseract.recognize(cv, "eng", config);
      const text = (res.data.text || "").replace(/[_\r\n]/g, " ").trim();
      if (!text) continue;
      const parsed = parseNumberInRange(text, minVal, maxVal);
      const conf = Number(res.data.confidence || 0);
      if (parsed === null) continue;
      if (!isReasonableCandidate(parsed, text, minVal, maxVal, field)) continue;
      if (!best || conf > best.conf) best = { value: parsed, conf, variant: label };
    } catch (e) { /* try next variant */ }
  }
  return best ? best.value : null;
};

// Build "35" + "1" -> 35.1 from two clean integer OCR words (avoids the 3x
// resize artifact that garbles tight-crop OCR of small decimals).
function wordComboDecimal(cluster) {
  if (!Array.isArray(cluster) || cluster.length !== 2) return null;
  const a = (cluster[0].text || "").trim();
  const b = (cluster[1].text || "").trim();
  if (!/^\d{1,3}$/.test(a) || !/^\d{1}$/.test(b)) return null;
  const v = parseInt(a, 10) + parseInt(b, 10) / 10;
  if (!Number.isFinite(v)) return null;
  return v;
};

// Read the numeric value that sits on the same row as a text label, using the
// full-image OCR words to locate the exact value bbox, then tight-crop OCR it.
// This skips scale/bar rows (which pollute box-crop OCR) by only looking at the
// value words on the label's own line, inside the anchor region. Decimal reads
// are preferred (the report's real values have 1 decimal; scale markers are
// whole numbers). Returns { value, kind } or null.
async function readValueByLabelAnchor(fullWords, labelRegexes, anchorBox, rawCanvas, minVal, maxVal, field) {
  if (!Array.isArray(fullWords) || !fullWords.length) return null;
  const scaled = scaleOrigBoxToCanvas(anchorBox, rawCanvas);
  const padY = Math.round(scaled.h * 1.2) + 10;
  const padX = Math.round(scaled.w * 0.08) + 10;
  const sameLineTol = Math.max(14, Math.round(scaled.h * 0.5));

  const labels = fullWords.filter((w) => {
    if (!w || !w.bbox || !w.text) return false;
    if (w.confidence !== undefined && w.confidence < 40) return false;
    const midY = (w.bbox.y0 + w.bbox.y1) / 2;
    if (midY < scaled.y - padY || midY > scaled.y + scaled.h + padY) return false;
    return labelRegexes.some((rx) => rx.test((w.text || "").trim()));
  });

  let bestDecimal = null;
  let bestDecimalDist = Infinity;
  let bestInt = null;
  let bestIntDist = Infinity;

  for (const w of labels) {
    const midY = (w.bbox.y0 + w.bbox.y1) / 2;
    const numerics = fullWords.filter((f) => {
      if (!f || !f.bbox || !f.text || f === w) return false;
      if (f.confidence !== undefined && f.confidence < 40) return false;
      if (!/\d/.test(f.text)) return false;
      if (Math.abs((f.bbox.y0 + f.bbox.y1) / 2 - midY) > sameLineTol) return false;
      if (f.bbox.x0 < w.bbox.x1) return false;
      if (f.bbox.x1 > scaled.x + scaled.w + padX) return false;
      if (f.bbox.y0 < scaled.y - padY || f.bbox.y1 > scaled.y + scaled.h + padY) return false;
      return true;
    });
    if (!numerics.length) continue;

    const sorted = numerics.slice().sort((a, b) => a.bbox.x0 - b.bbox.x0);
    const clusters = [[sorted[0]]];
    for (let i = 1; i < sorted.length; i++) {
      const last = clusters[clusters.length - 1];
      const prev = last[last.length - 1];
      const gap = sorted[i].bbox.x0 - prev.bbox.x1;
      if (gap <= Math.max(60, Math.round((prev.bbox.x1 - prev.bbox.x0) * 0.5))) {
        last.push(sorted[i]);
      } else {
        clusters.push([sorted[i]]);
      }
    }

    for (const cluster of clusters) {
      const bbox = {
        x0: Math.min(...cluster.map((c) => c.bbox.x0)),
        y0: Math.min(...cluster.map((c) => c.bbox.y0)),
        x1: Math.max(...cluster.map((c) => c.bbox.x1)),
        y1: Math.max(...cluster.map((c) => c.bbox.y1)),
      };
      const dist = bbox.x0 - w.bbox.x1;
      const tightVal = await tightCropRead(bbox, rawCanvas, minVal, maxVal, field);
      const comboVal = wordComboDecimal(cluster);
      const candidates = [];
      if (tightVal !== null) candidates.push({ value: tightVal, kind: "tight" });
      if (comboVal !== null) candidates.push({ value: comboVal, kind: "combo" });
      for (const c of candidates) {
        if (c.value < minVal || c.value > maxVal) continue;
        const isDecimal = c.value !== Math.round(c.value);
        if (isDecimal) {
          if (dist < bestDecimalDist) {
            bestDecimal = c;
            bestDecimalDist = dist;
          }
        } else if (dist < bestIntDist) {
          bestInt = c;
          bestIntDist = dist;
        }
      }
    }
  }
  if (bestDecimal) return { value: bestDecimal.value, kind: bestDecimal.kind };
  if (bestInt) return { value: bestInt.value, kind: bestInt.kind };
  return null;
};

// Chart-row reader for the bar-graph metrics (BMI / PBF / SMM). Each graph has
// TWO label rows: the abbreviation ("BMI") sits on the scale-marker row, while
// the full-name row ("Body Mass Index") carries the real value. The full-name
// row must be used because OCR garbles scale marks into in-range decimals
// ("191" -> 19.1) that otherwise win the anchor read. Value digits on the
// full-name row are often split into letter-garbled words ("OS 5"), so every
// same-line token right of the labels is clustered and tight-crop re-OCR'd.
async function readChartRowValue(fullWords, labelRegexes, anchorBox, rawCanvas, minVal, maxVal, field) {
  if (!Array.isArray(fullWords) || !fullWords.length) return null;
  const scaled = scaleOrigBoxToCanvas(anchorBox, rawCanvas);
  const padY = Math.round(scaled.h * 1.2) + 10;
  const padX = Math.round(scaled.w * 0.08) + 10;
  const rowTol = Math.max(10, Math.round(scaled.h * 0.3));

  const labels = fullWords.filter((w) => {
    if (!w || !w.bbox || !w.text) return false;
    if (w.confidence !== undefined && w.confidence < 40) return false;
    const midY = (w.bbox.y0 + w.bbox.y1) / 2;
    if (midY < scaled.y - padY || midY > scaled.y + scaled.h + padY) return false;
    return labelRegexes.some((rx) => rx.test((w.text || "").trim()));
  });
  if (!labels.length) return null;

  // Group matching labels into rows; pick the row with the most label words
  // (the full-name row always beats the single abbreviation word), ties -> lowest.
  const rows = [];
  for (const l of labels) {
    const midY = (l.bbox.y0 + l.bbox.y1) / 2;
    let row = rows.find((r) => Math.abs(r.midY - midY) <= rowTol);
    if (!row) {
      row = { midY, y0: l.bbox.y0, words: [] };
      rows.push(row);
    }
    row.words.push(l);
    if (l.bbox.y0 < row.y0) row.y0 = l.bbox.y0;
  }
  rows.sort((a, b) => b.words.length - a.words.length || b.midY - a.midY);
  const row = rows[0];
  const rightmostX1 = Math.max(...row.words.map((l) => l.bbox.x1));

  const tokens = fullWords.filter((f) => {
    if (!f || !f.bbox || !f.text) return false;
    if (row.words.includes(f)) return false;
    const midY = (f.bbox.y0 + f.bbox.y1) / 2;
    if (Math.abs(midY - row.midY) > rowTol) return false;
    // Value glyphs sit on the same baseline as the full-name label; anything
    // clearly higher belongs to the scale-marker row above. All coords here are
    // raw-canvas pixels (words are produced from the upscaled canvas).
    if (f.bbox.y0 < row.y0 - 45) return false;
    if (f.bbox.x0 < rightmostX1) return false;
    if (f.bbox.x1 > scaled.x + scaled.w + padX) return false;
    const h = f.bbox.y1 - f.bbox.y0;
    if (h < 60 || h > 260) return false;
    if ((f.text || "").trim().length > 6) return false;
    return true;
  });
  if (!tokens.length) return null;

  // Digits of one value can be split by the gap-clustering below (scale-dependent),
  // so first try ONE crop over every digit-bearing token on the row. A decimal
  // read from this full span is the most reliable bar-tip value.
  const digitTokens = tokens.filter((t) => /\d/.test(t.text || ""));
  if (digitTokens.length > 1) {
    const allBox = {
      x0: Math.min(...digitTokens.map((c) => c.bbox.x0)),
      y0: Math.min(...digitTokens.map((c) => c.bbox.y0)),
      x1: Math.max(...digitTokens.map((c) => c.bbox.x1)),
      y1: Math.max(...digitTokens.map((c) => c.bbox.y1)),
    };
    const allVal = await tightCropRead(allBox, rawCanvas, minVal, maxVal, field);
    if (allVal !== null && allVal !== Math.round(allVal))
      return { value: allVal, kind: "tight-all" };
  }

  const sorted = tokens.slice().sort((a, b) => a.bbox.x0 - b.bbox.x0);
  const clusters = [[sorted[0]]];
  for (let i = 1; i < sorted.length; i++) {
    const last = clusters[clusters.length - 1];
    const prev = last[last.length - 1];
    const gap = sorted[i].bbox.x0 - prev.bbox.x1;
    if (gap <= Math.max(90, Math.round((prev.bbox.x1 - prev.bbox.x0) * 0.5))) {
      last.push(sorted[i]);
    } else {
      clusters.push([sorted[i]]);
    }
  }

  let bestDecimal = null;
  let bestDecimalDist = Infinity;
  let bestInt = null;
  let bestIntDist = Infinity;
  for (const cluster of clusters) {
    const bbox = {
      x0: Math.min(...cluster.map((c) => c.bbox.x0)),
      y0: Math.min(...cluster.map((c) => c.bbox.y0)),
      x1: Math.max(...cluster.map((c) => c.bbox.x1)),
      y1: Math.max(...cluster.map((c) => c.bbox.y1)),
    };
    const dist = bbox.x0 - rightmostX1;
    let tightVal = await tightCropRead(bbox, rawCanvas, minVal, maxVal, field);
    const comboVal = wordComboDecimal(cluster);
    // Bar-dash junk can sneak into a cluster's left edge (e.g. "F/M" before the
    // value digits). Retry on the digit-bearing sub-bbox only and prefer that
    // result when it succeeds - it crops exactly the real value glyphs. When the
    // value itself is letter-garbled ("OS" + "5") the sub-crop yields nothing and
    // the full-cluster crop is kept instead.
    const digitTok = cluster.filter((c) => /\d/.test(c.text || ""));
    if (digitTok.length && digitTok.length < cluster.length) {
      const subBbox = {
        x0: Math.min(...digitTok.map((c) => c.bbox.x0)),
        y0: Math.min(...digitTok.map((c) => c.bbox.y0)),
        x1: Math.max(...digitTok.map((c) => c.bbox.x1)),
        y1: Math.max(...digitTok.map((c) => c.bbox.y1)),
      };
      const subVal = await tightCropRead(subBbox, rawCanvas, minVal, maxVal, field);
      if (subVal !== null) tightVal = subVal;
    }
    const candidates = [];
    if (tightVal !== null) candidates.push({ value: tightVal, kind: "tight" });
    if (comboVal !== null) candidates.push({ value: comboVal, kind: "combo" });
    for (const c of candidates) {
      if (c.value < minVal || c.value > maxVal) continue;
      const isDecimal = c.value !== Math.round(c.value);
      if (isDecimal) {
        if (dist < bestDecimalDist) {
          bestDecimal = c;
          bestDecimalDist = dist;
        }
      } else if (dist < bestIntDist) {
        bestInt = c;
        bestIntDist = dist;
      }
    }
  }
  if (bestDecimal) return { value: bestDecimal.value, kind: bestDecimal.kind };
  if (bestInt) return { value: bestInt.value, kind: bestInt.kind };
  return null;
};

// Tolerant single-word label matcher (handles OCR noise like "Weiqht"). Only
// uses letters so stray punctuation does not break the comparison.
function fuzzyLabelMatch(word, label) {
  const w = String(word || "")
    .toLowerCase()
    .replace(/[^a-z]/g, "");
  const l = String(label || "")
    .toLowerCase()
    .replace(/[^a-z]/g, "");
  if (w === l) return true;
  const len = Math.max(w.length, l.length);
  if (Math.abs(w.length - l.length) > 1 || len < 3) return false;
  let diff = 0;
  const n = Math.min(w.length, l.length);
  for (let i = 0; i < n; i++) if (w[i] !== l[i]) diff++;
  diff += Math.abs(w.length - l.length);
  return diff <= 1;
}

// A word is treated as a numeric token when at least half of its characters are
// digits/separators and it contains a digit ("78.4kg" yes, "(L)" / "m2" no).
function isNumericTokenText(text) {
  const s = String(text || "").replace(/\s+/g, "");
  if (!/\d/.test(s)) return false;
  const numChars = (s.match(/[0-9.,%]/g) || []).length;
  return numChars >= s.length * 0.5;
}

// Realistic per-field value windows. A small-crop read that lands outside its
// window is treated as unreliable and replaced by the label-anchored read.
const FIELD_PLAUSIBLE = {
  weight: [20, 300],
  water: [8, 150],
  protein: [5, 80],
  fat: [2, 150],
  smm: [8, 90],
  kcal: [500, 6000],
  bmi: [10, 60],
  pbf: [3, 65],
};

// Layout-independent field locator. It scans the FULL-image OCR words for the
// field's text label (anywhere on the page), then finds the numeric token on
// the SAME row as that label (to the right of it). This does NOT depend on the
// REF template boxes, so it stays correct even when the photo is zoomed, cropped
// or tilted differently than the reference image. It reads the value from the
// exact token (tight crop + decimal heuristics) and returns the token's bbox so
// the rectangle can be centered precisely on the printed number.
// Returns { value, bbox, kind } | null. anchorBox is only a weak row prior.
async function locateFieldNumber(fullWords, labelRegexes, labelWords, rawCanvas, opts = {}) {
  const {
    minVal = -Infinity,
    maxVal = Infinity,
    field = "generic",
    anchorBox = null,
    decimals = 1,
  } = opts;
  if (!Array.isArray(fullWords) || !fullWords.length) return null;

  const H = rawCanvas.height;
  const words = fullWords.filter((w) => w && w.bbox && w.text);

  const labelMatched = (w) => {
    const t = (w.text || "").replace(/[,،]/g, ".").trim();
    if (labelRegexes && labelRegexes.length && labelRegexes.some((rx) => rx.test(t))) return true;
    if (labelWords && labelWords.length) {
      for (const lw of labelWords) {
        if (fuzzyLabelMatch(t, lw)) return true;
      }
    }
    return false;
  };

  const prior = anchorBox ? scaleOrigBoxToCanvas(anchorBox, rawCanvas) : null;
  const priorCy = prior ? prior.y + prior.h / 2 : H / 2;
  const bandH = prior ? Math.max(prior.h * 5, H * 0.08) : H * 0.5;

  const matches = [];
  for (const w of words) {
    if (w.confidence !== undefined && w.confidence < 40) continue;
    if (!labelMatched(w)) continue;
    const lcy = (w.bbox.y0 + w.bbox.y1) / 2;
    if (prior && Math.abs(lcy - priorCy) > bandH) continue;

    const rowH = Math.max(1, w.bbox.y1 - w.bbox.y0);
    const tolY = Math.max(30, rowH * 0.8);

    const numerics = words.filter((f) => {
      if (f === w) return false;
      if (!isNumericTokenText(f.text)) return false;
      if (f.confidence !== undefined && f.confidence < 40) return false;
      const fcy = (f.bbox.y0 + f.bbox.y1) / 2;
      if (Math.abs(fcy - lcy) > tolY) return false;
      if (f.bbox.x0 < w.bbox.x1 - rowH * 0.6) return false;
      return true;
    });
    if (!numerics.length) continue;

    const numericsSorted = numerics.slice().sort((a, b) => a.bbox.x0 - b.bbox.x0);
    const clusters = [[numericsSorted[0]]];
    for (let i = 1; i < numericsSorted.length; i++) {
      const last = clusters[clusters.length - 1];
      const prev = last[last.length - 1];
      const gap = numericsSorted[i].bbox.x0 - prev.bbox.x1;
      if (gap <= Math.max(22, (prev.bbox.x1 - prev.bbox.x0) * 0.25)) last.push(numericsSorted[i]);
      else clusters.push([numericsSorted[i]]);
    }

    for (const cluster of clusters) {
      const bbox = {
        x0: Math.min(...cluster.map((c) => c.bbox.x0)),
        y0: Math.min(...cluster.map((c) => c.bbox.y0)),
        x1: Math.max(...cluster.map((c) => c.bbox.x1)),
        y1: Math.max(...cluster.map((c) => c.bbox.y1)),
      };
      if (bbox.x1 - bbox.x0 < 12 || bbox.y1 - bbox.y0 < 12) continue;
      if (bbox.x1 - bbox.x0 > rawCanvas.width * 0.4) continue;
      matches.push({ label: w, cluster, bbox, lcy, ccy: (bbox.y0 + bbox.y1) / 2, dist: bbox.x0 - w.bbox.x1 });
    }
  }

  if (!matches.length) return null;

  matches.sort(
    (a, b) =>
      (prior ? Math.abs(a.lcy - priorCy) - Math.abs(b.lcy - priorCy) : 0) ||
      Math.abs(a.ccy - a.lcy) - Math.abs(b.ccy - b.lcy) ||
      a.dist - b.dist
  );

  // OCR sometimes merges the digits and drops the decimal point ("4506" -> 45.06,
  // "784" -> 78.4). Insert the decimal at the expected position when the result
  // lands inside the field's plausible window.
  const decimalFallback = (cluster) => {
    const digitsOnly = cluster.map((c) => c.text || "").join("").replace(/[^\d]/g, "");
    if (digitsOnly.length < 3 || digitsOnly.length > 6) return null;
    const orders = decimals === 0 ? [] : decimals === 2 ? [2, 1] : [1, 2];
    for (const n of orders) {
      if (digitsOnly.length <= n) continue;
      const cand = parseFloat(digitsOnly.slice(0, digitsOnly.length - n) + "." + digitsOnly.slice(digitsOnly.length - n));
      if (Number.isFinite(cand) && cand >= minVal && cand <= maxVal) return cand;
    }
    return null;
  };

  for (const m of matches) {
    const tightVal = await tightCropRead(m.bbox, rawCanvas, minVal, maxVal, field);
    const comboVal = wordComboDecimal(m.cluster);
    const fbVal = decimalFallback(m.cluster);
    let value = null;
    if (comboVal !== null && comboVal >= minVal && comboVal <= maxVal) value = comboVal;
    else if (tightVal !== null) value = tightVal;

    // Real values carry one decimal while scale markers are whole numbers, so
    // prefer a decimal reading when both are plausible ("163" -> 16.3).
    if (fbVal !== null && value === null) value = fbVal;
    else if (fbVal !== null && decimals > 0 && value !== null && value === Math.round(value) && fbVal !== Math.round(fbVal)) value = fbVal;

    if (value !== null) return { value, bbox: m.bbox, kind: "label-anchor" };
  }
  return null;
}

function createEnhancedCanvas(canvas) {
  const out = document.createElement("canvas");
  out.width = canvas.width;
  out.height = canvas.height;
  const ctx = out.getContext("2d", { willReadFrequently: true });
  ctx.drawImage(canvas, 0, 0);
  const imgd = ctx.getImageData(0, 0, canvas.width, canvas.height);
  const data = imgd.data;
  for (let i = 0; i < data.length; i += 4) {
    const r = data[i];
    const g = data[i + 1];
    const b = data[i + 2];
    const gray = Math.round(0.299 * r + 0.587 * g + 0.114 * b);
    const adjusted = Math.max(0, Math.min(255, (gray - 128) * 1.25 + 128 + 10));
    data[i] = data[i + 1] = data[i + 2] = adjusted;
  }
  ctx.putImageData(imgd, 0, 0);
  return out;
}

function createThresholdCanvas(canvas) {
  return createOtsuThresholdCanvas(canvas);
}

function createOtsuThresholdCanvas(canvas) {
  const out = document.createElement("canvas");
  out.width = canvas.width;
  out.height = canvas.height;
  const ctx = out.getContext("2d", { willReadFrequently: true });
  ctx.drawImage(canvas, 0, 0);
  const imgd = ctx.getImageData(0, 0, canvas.width, canvas.height);
  const data = imgd.data;

  const total = (data.length / 4) | 0;
  const hist = new Float64Array(256);
  for (let i = 0; i < data.length; i += 4) {
    const gray = Math.round(0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2]);
    hist[gray]++;
  }

  let sum = 0;
  for (let t = 0; t < 256; t++) sum += t * hist[t];
  let sumB = 0;
  let wB = 0;
  let maxVar = 0;
  let threshold = 127;
  for (let t = 0; t < 256; t++) {
    wB += hist[t];
    if (!wB) continue;
    const wF = total - wB;
    if (!wF) break;
    sumB += t * hist[t];
    const mB = sumB / wB;
    const mF = (sum - sumB) / wF;
    const between = wB * wF * (mB - mF) * (mB - mF);
    if (between > maxVar) {
      maxVar = between;
      threshold = t;
    }
  }

  for (let i = 0; i < data.length; i += 4) {
    const gray = Math.round(0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2]);
    const bw = gray > threshold ? 255 : 0;
    data[i] = data[i + 1] = data[i + 2] = bw;
  }
  ctx.putImageData(imgd, 0, 0);
  return out;
}

function createAdaptiveThresholdCanvas(canvas, blockSize = 41, C = 10) {
  const out = document.createElement("canvas");
  out.width = canvas.width;
  out.height = canvas.height;
  const ctx = out.getContext("2d", { willReadFrequently: true });
  ctx.drawImage(canvas, 0, 0);
  const imgd = ctx.getImageData(0, 0, canvas.width, canvas.height);
  const data = imgd.data;

  const w = canvas.width;
  const h = canvas.height;
  const total = w * h;
  const gray = new Float64Array(total);
  for (let i = 0, p = 0; i < total; i++, p += 4) {
    gray[i] = 0.299 * data[p] + 0.587 * data[p + 1] + 0.114 * data[p + 2];
  }

  // integral image for O(1) block means
  const ii = new Float64Array((w + 1) * (h + 1));
  for (let y = 0; y < h; y++) {
    for (let x = 0; x < w; x++) {
      ii[(y + 1) * (w + 1) + x + 1] =
        gray[y * w + x] +
        ii[y * (w + 1) + x + 1] +
        ii[(y + 1) * (w + 1) + x] -
        ii[y * (w + 1) + x];
    }
  }

  const half = Math.floor(blockSize / 2);
  const outData = new Uint8ClampedArray(total * 4);
  for (let y = 0; y < h; y++) {
    const y0 = Math.max(0, y - half);
    const y1 = Math.min(h - 1, y + half);
    for (let x = 0; x < w; x++) {
      const x0 = Math.max(0, x - half);
      const x1 = Math.min(w - 1, x + half);
      const sum =
        ii[(y1 + 1) * (w + 1) + x1 + 1] -
        ii[y0 * (w + 1) + x1 + 1] -
        ii[(y1 + 1) * (w + 1) + x0] +
        ii[y0 * (w + 1) + x0];
      const count = (y1 - y0 + 1) * (x1 - x0 + 1);
      const mean = sum / count;
      const bw = gray[y * w + x] > mean - C ? 255 : 0;
      const p = (y * w + x) * 4;
      outData[p] = outData[p + 1] = outData[p + 2] = bw;
      outData[p + 3] = 255;
    }
  }
  ctx.putImageData(new ImageData(outData, w, h), 0, 0);
  return out;
}

function createShearedCanvas(canvas, shearDeg) {
  const radians = (shearDeg * Math.PI) / 180;
  const tan = Math.tan(radians);
  const newW = Math.round(canvas.width + Math.abs(tan) * canvas.height);
  const newH = canvas.height;
  const out = document.createElement("canvas");
  out.width = newW;
  out.height = newH;
  const ctx = out.getContext("2d", { willReadFrequently: true });
  ctx.translate(tan < 0 ? -tan * canvas.height : 0, 0);
  ctx.transform(1, 0, tan, 1, 0, 0);
  ctx.drawImage(canvas, 0, 0);
  return out;
}

function createRotatedCanvas(canvas, angleDeg) {
  const radians = (angleDeg * Math.PI) / 180;
  const cos = Math.abs(Math.cos(radians));
  const sin = Math.abs(Math.sin(radians));
  const newW = Math.round(canvas.width * cos + canvas.height * sin);
  const newH = Math.round(canvas.width * sin + canvas.height * cos);
  const out = document.createElement("canvas");
  out.width = newW;
  out.height = newH;
  const ctx = out.getContext("2d", { willReadFrequently: true });
  ctx.translate(newW / 2, newH / 2);
  ctx.rotate(radians);
  ctx.drawImage(canvas, -canvas.width / 2, -canvas.height / 2);
  return out;
}

async function preprocessToCanvas(file, scale = 3, applyThreshold = true) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.crossOrigin = "anonymous";
    const reader = new FileReader();
    reader.onload = (ev) => (img.src = ev.target.result);
    reader.onerror = reject;
    img.onload = () => {
      const w = Math.round(img.width * scale);
      const h = Math.round(img.height * scale);
      const raw = document.createElement("canvas");
      raw.width = w;
      raw.height = h;
      const rctx = raw.getContext("2d", { willReadFrequently: true });
      rctx.drawImage(img, 0, 0, w, h);
      const thresh = document.createElement("canvas");
      thresh.width = w;
      thresh.height = h;
      const tctx = thresh.getContext("2d", { willReadFrequently: true });
      tctx.drawImage(img, 0, 0, w, h);

      try {
        const imgd = tctx.getImageData(0, 0, w, h);
        const data = imgd.data;
        const contrast = 1.2;
        const brightness = 8;
        for (let i = 0; i < data.length; i += 4) {
          const r = data[i],
            g = data[i + 1],
            b = data[i + 2];
          let lum = 0.2126 * r + 0.7152 * g + 0.0722 * b;
          lum = (lum - 128) * contrast + 128 + brightness;
          lum = Math.max(0, Math.min(255, lum));
          data[i] = data[i + 1] = data[i + 2] = lum;
        };
        tctx.putImageData(imgd, 0, 0);
        if (applyThreshold) {
          const imgd2 = tctx.getImageData(0, 0, w, h);
          for (let i = 0; i < imgd2.data.length; i += 4) {
            const v = imgd2.data[i];
            const bw = v > 120 ? 255 : 0;
            imgd2.data[i] = imgd2.data[i + 1] = imgd2.data[i + 2] = bw;
          };
          tctx.putImageData(imgd2, 0, 0);
        };
      } catch (err) {
        console.warn("preprocess failed", err);
      };

      // store the real original image pixel dimensions for later scaling
      window.__orig_img_w = img.width;
      window.__orig_img_h = img.height;
      resolve({ rawCanvas: raw, threshCanvas: thresh });
      resetFixCanvasBoxes();
      fixCanvas(WEIGHT_ORIG, { w: 26 });
      fixCanvas(TBW_ORIG, { w: 26 });
      fixCanvas(PROTEIN_ORIG, { w: 26 });
      fixCanvas(FAT_MASS_ORIG, { w: 26 });
      fixCanvas(BMI_ORIG, { x: 0, y: 1 });
      fixCanvas(PBF_ORIG, { x: 0, y: 2 });
      fixCanvas(SMM_ORIG, { x: 0, y: 1.5 });
      fixCanvas(KCAL_ORIG, { x: 7, y: 1 });
      fixCanvas(ORIG_LEAN, { x: 0, y: 1.5, w: 13, h: 13 });
      fixCanvas(LEFT_ARM_LEAN_ORIG, { y: 2.5, w: 15 });
      fixCanvas(RIGHT_ARM_LEAN_ORIG, { y: 2.5, w: 20 });
      fixCanvas(LEFT_LEG_LEAN_ORIG, { y: 2.6, w: 10 });
      fixCanvas(RIGHT_LEG_LEAN_ORIG, { y: 2.6, w: 10 });
      fixCanvas(ORIG_FAT, { x: 2.2, y: 1.5, w: 13, h: 13 });
      fixCanvas(LEFT_ARM_FAT_ORIG, { x: 3, y: 2, w: 3 });
      fixCanvas(RIGHT_ARM_FAT_ORIG, { x: 3, y: 2, w: 3 });
      fixCanvas(LEFT_LEG_FAT_ORIG, { x: 2.5, y: 2.6, w: 20 });
      fixCanvas(RIGHT_LEG_FAT_ORIG, { x: 2.5, y: 2.6, w: 20 });
    };
    img.onerror = reject;
    reader.readAsDataURL(file);
  });
};

async function ocrNumbersFromOrigBoxWithCanvas(origBox, rawCanvas, minVal, maxVal, field = "generic", deep = false, alreadyScaled = false) {
  const scaled = alreadyScaled ? origBox : scaleOrigBoxToCanvas(origBox, rawCanvas);
  const baseCrop = cropCanvas(rawCanvas, scaled.x, scaled.y, scaled.w, scaled.h);
  const config = {
    logger: (m) => {},
    tessedit_char_whitelist: "0123456789.,%",
    tessedit_pageseg_mode: "7",
  };

  // When a sheet slope was measured, add deskewed crops (both sign directions)
  // so a slight tilt is corrected before OCR instead of relying on coarse ±1°.
  const sheet = window.__sheet_bounds;
  const slope =
    sheet && typeof sheet.angle === "number" && Math.abs(sheet.angle) >= 0.1
      ? sheet.angle
      : 0;

  const variants = [
    { label: "raw", canvas: baseCrop },
    { label: "enhanced", canvas: createEnhancedCanvas(baseCrop) },
    { label: "otsu", canvas: createOtsuThresholdCanvas(baseCrop) },
    { label: "adaptive", canvas: createAdaptiveThresholdCanvas(baseCrop) },
  ];
  if (slope) {
    variants.unshift(
      { label: "deskew+", canvas: createRotatedCanvas(baseCrop, -slope) },
      { label: "deskew-", canvas: createRotatedCanvas(baseCrop, slope) }
    );
  }

  const candidates = [];

  const runCandidate = async (sourceCanvas, variantLabel) => {
    try {
      const res = await Tesseract.recognize(sourceCanvas, "eng", config);
      const text = (res.data.text || "").replace(/[_\r\n]/g, " ").trim();
      if (!text) return;
      const parsed = parseNumberInRange(text, minVal, maxVal);
      const confidence = Number(res.data.confidence || 0);
      const valid = isReasonableCandidate(parsed, text, minVal, maxVal, field);
      const words = (res.data.words || [])
        .map((w) => {
          const wb = w && w.bbox;
          if (!wb) return null;
          const wt = (w.text || "").replace(/[,，]/g, ".").trim();
          if (!/\d/.test(wt)) return null;
          return {
            x0: wb.x0,
            y0: wb.y0,
            x1: wb.x1,
            y1: wb.y1,
            value: parseNumberInRange(wt, minVal, maxVal),
            txt: wt,
          };
        })
        .filter(Boolean);
      candidates.push({
        text,
        value: valid ? parsed : null,
        confidence,
        valid,
        variant: variantLabel,
        words,
      });
    } catch (e) {
      console.warn("ocrNumbersFromOrigBox error", e);
    }
  };

  // Phase 1 (always): all preprocessing variants at 0° rotation — cheap baseline
  for (const variant of variants) {
    await runCandidate(variant.canvas, variant.label);
  }

  // Phase 2 (deep only): retry with the measured slope plus mild rotations
  // ±1°, ±2° to fight small tilt
  if (deep && !candidates.some((c) => c.value !== null)) {
    const rotAngles = slope
      ? [slope, -slope, -1, 1, -2, 2]
      : [-1, 1, -2, 2];
    for (const variant of variants) {
      for (const angle of rotAngles) {
        await runCandidate(
          createRotatedCanvas(variant.canvas, angle),
          `${variant.label}:r${angle}`
        );
      }
    }
  }

  // Phase 3 (deep only): slight horizontal shear for perspective/skew distortion
  if (deep && !candidates.some((c) => c.value !== null)) {
    for (const variant of [variants[0], variants[2]]) {
      for (const shear of [-1.5, 1.5]) {
        await runCandidate(
          createShearedCanvas(variant.canvas, shear),
          `${variant.label}:s${shear}`
        );
      }
    }
  }

  if (!candidates.length) {
    return { value: null, raw_text: "", confidence: 0, variant: "none" };
  }

  const baseScore = (c) =>
    c.confidence / 100 +
    (c.valid ? 0.35 : 0) +
    (/[0-9]/.test(c.text) ? 0.05 : 0) +
    (c.valid && /^\d{1,6}(?:[.,]\d{1,3})?$/.test(c.text.trim()) ? 0.35 : 0);

  const scored = candidates.map((c) => ({ ...c, base: baseScore(c) }));

  // Single best read by base score (matches the original pipeline).
  const bestSingle = scored.reduce(
    (a, b) => (b.base > a.base ? b : a),
    scored[0]
  );

  let best = null;

  // Trust a confident single read. This preserves the correct behavior on
  // clean images where the plain crop reads correctly — wrong-but-agreed
  // preprocessing variants must not override a high-confidence read.
  if (bestSingle && bestSingle.value !== null && bestSingle.confidence >= 70) {
    best = bestSingle;
  } else {
    // Default to the single best read; a consensus only wins when the
    // agreeing variants are on average MORE confident than that single read.
    // This prevents e.g. two garbage reads of "740" (conf 54/40) from beating
    // a single correct "74" (conf 55).
    best = bestSingle && bestSingle.value !== null ? bestSingle : null;
    if (bestSingle && bestSingle.value !== null) {
      const groups = new Map();
      for (const c of scored) {
        if (c.value === null) continue;
        const key = c.value.toFixed(3);
        if (!groups.has(key)) groups.set(key, []);
        groups.get(key).push(c);
      }
      for (const arr of groups.values()) {
        const distinctVariants = new Set(
          arr.map((c) => c.variant.split(":")[0])
        ).size;
        if (distinctVariants < 2) continue;
        const avgConf = arr.reduce((s, c) => s + c.confidence, 0) / arr.length;
        if (avgConf < 45) continue;
        if (avgConf <= bestSingle.confidence) continue;
        const member = arr
          .slice()
          .sort((a, b) => b.confidence - a.confidence)[0];
        if (!best || member.confidence > best.confidence) best = member;
      }
    }
  }

  // Keep the original behavior: never leave `best` null when candidates exist,
  // even if none produced a valid value (callers treat null value as not-found).
  if (!best && bestSingle) best = bestSingle;

  // Recover the exact position of the parsed number: its OCR word bbox, mapped
  // back from crop-local coordinates into raw-canvas coordinates. Only valid
  // for non-rotated variants (rotate/shear/deskew change the geometry).
  let bbox = null;
  if (best && best.value != null && Array.isArray(best.words)) {
    const plain = /^(raw|enhanced|otsu|adaptive)$/.test(best.variant);
    if (plain) {
      const target = (+best.value).toFixed(3);
      const m = best.words.find(
        (w) => w.value != null && (+w.value).toFixed(3) === target
      );
      if (m) {
        bbox = {
          x0: scaled.x + m.x0,
          y0: scaled.y + m.y0,
          x1: scaled.x + m.x1,
          y1: scaled.y + m.y1,
        };
      }
    }
  }

  if (window.__debug_ocr) {
    const dbg = {
      field,
      deep,
      chosen: best
        ? {
            value: best.value,
            variant: best.variant,
            confidence: best.confidence,
            text: best.text,
          }
        : null,
      candidates: scored.map((c) => ({
        v: c.value,
        t: c.text,
        conf: Math.round(c.confidence),
        var: c.variant,
      })),
      ts: Date.now(),
    };
    console.log(
      `[OCR debug] ${field} deep=${deep} box=${Math.round(scaled.x)},${Math.round(scaled.y)} ${Math.round(scaled.w)}x${Math.round(scaled.h)} => chosen=${best ? best.value : "null"} (${best ? best.variant : "-"} conf=${best ? Math.round(best.confidence) : 0} text="${best ? best.text : ""}")`,
      scored.map((c) => ({
        v: c.value,
        t: c.text,
        conf: Math.round(c.confidence),
        var: c.variant,
      }))
    );
    try {
      const arr = JSON.parse(sessionStorage.getItem("__ocr_debug_log") || "[]");
      arr.push(dbg);
      sessionStorage.setItem("__ocr_debug_log", JSON.stringify(arr));
    } catch (e) {}
  }

  return {
    value: best.value,
    raw_text: best.text,
    confidence: best.confidence,
    variant: best.variant,
    bbox,
  };
};

// Default outward margin (raw-canvas px) of the dashed number-search area shown
// around each square. The search itself grows OUTWARD from the box until it
// finds the value - it never shrinks inward.
const SEARCH_OUT_MARGIN_PX = 3;

async function expandAndFindNumber(origBox, rawCanvas, minVal, maxVal, maxAttempts = 7, field = "generic") {
  const pads = [0, 8, 16, 32, 64, 120, 240];
  const directions = [
    (pad) => ({
      x: Math.max(0, origBox.x - pad),
      y: Math.max(0, origBox.y - pad),
      w: origBox.w + 2 * pad,
      h: origBox.h + 2 * pad,
    }), // both
    (pad) => ({
      x: origBox.x,
      y: Math.max(0, origBox.y - pad),
      w: origBox.w + pad,
      h: origBox.h + 2 * pad,
    }), // extend right
    (pad) => ({
      x: Math.max(0, origBox.x - pad),
      y: Math.max(0, origBox.y - pad),
      w: origBox.w + pad,
      h: origBox.h + 2 * pad,
    }), // extend left
  ];

  // Move OUTWARD step by step: try the box as-is first, then grow it outward in
  // all directions until the value is found. The pad that found it is returned
  // so the square can be animated growing by that exact amount.
  for (let i = 0; i < Math.min(maxAttempts, pads.length); i++) {
    const pad = pads[i];
    for (const dirFn of directions) {
      const expanded = dirFn(pad);
      // clamp to canvas
      expanded.w = Math.min(expanded.w, rawCanvas.width - expanded.x);
      expanded.h = Math.min(expanded.h, rawCanvas.height - expanded.y);
      const ocrResult = await ocrNumbersFromOrigBoxWithCanvas(
        expanded,
        rawCanvas,
        minVal,
        maxVal,
        field,
        pad === 0
      );
      const val = ocrResult.value;
      if (val !== null)
        return {
          value: val,
          raw_text: ocrResult.raw_text,
          padUsed: pad,
          dir:
            pad === 0
              ? "none"
              : dirFn === directions[0]
              ? "both"
              : dirFn === directions[1]
              ? "right"
              : "left",
          confidence: ocrResult.confidence,
          variant: ocrResult.variant,
          bbox: ocrResult.bbox || null,
          searchBox: scaleOrigBoxToCanvas(expanded, rawCanvas),
        };
      const ints = (ocrResult.raw_text.match(/\d{1,3}/g) || []).map((s) => normNum(s)).filter((v) => v !== null && v >= minVal && v <= maxVal);
      // Only trust an integer fallback when the crop is a single short number.
      // A long run like a BMI scale ("10.0 15.0 ... 50.0") yields many ints and
      // must NOT short-circuit the box expansion that reaches the real value.
      if (ints.length && ints.length <= 2) {
        return {
          value: ints[0],
          raw_text: ocrResult.raw_text,
          padUsed: pad,
          note: "picked-int",
          confidence: ocrResult.confidence,
          variant: ocrResult.variant,
          bbox: null,
          searchBox: scaleOrigBoxToCanvas(expanded, rawCanvas),
        };
      };
    };
  };
  return { value: null, raw_text: null, padUsed: null, bbox: null, searchBox: null };
};

function findNumericNearBox(fullWords, origBox, rawCanvas, minV = -Infinity, maxV = Infinity, maxDist = 800) {
  if (!Array.isArray(fullWords)) return null;
  const scaled = scaleOrigBoxToCanvas(origBox, rawCanvas);
  const centerX = scaled.x + scaled.w / 2;
  const centerY = scaled.y + scaled.h / 2;
  let best = null;
  let bestD = Infinity;
  for (const w of fullWords) {
    const raw = (w.text || "").replace(/[,，]/g, ".").trim();
    if (!/\d/.test(raw)) continue;
    if (raw.length > 12) continue;
    const m = raw.match(/-?[\d]{1,3}(?:[.,][\d]{1,3})?/);
    if (!m) continue;
    const v = normNum(m[0]);
    if (v === null || v < minV || v > maxV) continue;
    const cx = (w.bbox.x0 + w.bbox.x1) / 2;
    const cy = (w.bbox.y0 + w.bbox.y1) / 2;
    const d = Math.hypot(cx - centerX, cy - centerY);
    if (d < bestD && d <= maxDist) {
      bestD = d;
      best = { val: v, raw: raw, dist: d };
    };
  };
  return best ? best.val : null;
};

// Detect the physical InBody sheet (white paper) inside a larger/rotated photo.
// Returns a bbox in rawCanvas pixels, or null when no clear sheet is found.
// Dynamically measures the sheet's slope (tilt) so it can be drawn on the image
// and used to deskew crops, even when the paper fills the whole frame. Unlike a
// fixed small window, the search is adaptive: a coarse sweep covers the whole
// range (large tilts included), then a fine pass refines around the winner.
const SLOPE_SEARCH_RANGE = 20; // max tilt to track, in degrees (each direction)
function estimateSheetTilt(gray, sw, sh, otsuTh, rangeDeg = SLOPE_SEARCH_RANGE) {
  try {
    const th = Math.max(40, Math.min(otsuTh - 10, 170));
    // Restrict the projection to the paper region so background (a dark desk,
    // padding, etc.) cannot wash out the text-row signal. The paper mask is a
    // dilation of the bright (paper) pixels.
    const brightTh = Math.max(150, th);
    const inPaper = new Uint8Array(sw * sh);
    for (let y = 0; y < sh; y++) {
      const rowOff = y * sw;
      for (let x = 0; x < sw; x++) {
        if (gray[rowOff + x] < brightTh) continue;
        const x0 = Math.max(0, x - 3), x1 = Math.min(sw - 1, x + 3);
        const y0 = Math.max(0, y - 3), y1 = Math.min(sh - 1, y + 3);
        for (let ny = y0; ny <= y1; ny++) {
          const nRow = ny * sw;
          for (let nx = x0; nx <= x1; nx++) inPaper[nRow + nx] = 1;
        }
      }
    }

    const cx = sw / 2, cy = sh / 2;
    const rc = new Int32Array(sh);

    const score = (deg) => {
      const rad = (deg * Math.PI) / 180;
      const cc = Math.cos(rad), s = Math.sin(rad);
      rc.fill(0);
      for (let y = 0; y < sh; y++) {
        const dy = y - cy;
        const rowOff = y * sw;
        for (let x = 0; x < sw; x++) {
          const idx = rowOff + x;
          if (!inPaper[idx] || gray[idx] >= th) continue;
          const dx = x - cx;
          const ry = Math.round(dx * s + dy * cc + cy);
          if (ry < 0 || ry >= sh) continue;
          rc[ry]++;
        }
      }
      let e = 0;
      for (let r = 0; r < sh; r++) e += rc[r] * rc[r];
      return e;
    };

    // Coarse dynamic sweep across the whole range so large tilts are found.
    let best = 0, bestE = -1;
    const coarseStep = Math.max(0.5, rangeDeg / 30);
    for (let deg = -rangeDeg; deg <= rangeDeg + 1e-9; deg += coarseStep) {
      const e = score(deg);
      if (e > bestE) { bestE = e; best = deg; }
    }
    // Fine pass around the coarse winner for a precise angle.
    const fineStep = 0.1;
    for (let k = -10; k <= 10; k++) {
      const deg = best + k * fineStep;
      if (deg < -rangeDeg || deg > rangeDeg) continue;
      const e = score(deg);
      if (e > bestE) { bestE = e; best = deg; }
    }
    return Math.round(best * 100) / 100;
  } catch (e) {
    return 0;
  }
}

// Re-measures the slope on the already-downscaled grayscale and updates the
// active sheet bounds, so the overlay slope line/value tracks live.
function reestimateSheetAngle() {
  const m = window.__sheet_monitor;
  if (!m || !window.__sheet_bounds) return null;
  const angle = estimateSheetTilt(m.gray, m.sw, m.sh, m.th);
  window.__sheet_bounds.angle = angle;
  window.__sheet_bounds.slope = angle;
  return angle;
}

function displaySheetAngle() {
  // The estimator measures the projection rotation that concentrates the text
  // rows, which is the NEGATIVE of the paper's on-screen rotation (clockwise =
  // positive in the y-down canvas). Flip it here so the slope line, label, and
  // tilted boxes match the paper's visible tilt direction.
  const s = window.__sheet_bounds;
  const angle = s && typeof s.angle === "number" ? s.angle : 0;
  return -angle;
}

function detectSheetBounds(canvas) {
  try {
    const W = canvas.width;
    const H = canvas.height;
    if (!W || !H) return null;

    const sw = 160;
    const sh = Math.max(1, Math.round((H * sw) / W));
    const small = document.createElement("canvas");
    small.width = sw;
    small.height = sh;
    const sctx = small.getContext("2d", { willReadFrequently: true });
    sctx.drawImage(canvas, 0, 0, sw, sh);
    const img = sctx.getImageData(0, 0, sw, sh);
    const d = img.data;

    const gray = new Float32Array(sw * sh);
    for (let i = 0; i < sw * sh; i++) {
      gray[i] = 0.2126 * d[i * 4] + 0.7152 * d[i * 4 + 1] + 0.0722 * d[i * 4 + 2];
    }

    // Otsu threshold on the downscaled grayscale to separate paper from background
    const hist = new Float64Array(256);
    for (let i = 0; i < gray.length; i++) hist[Math.round(gray[i])]++;
    let sum = 0;
    for (let t = 0; t < 256; t++) sum += t * hist[t];
    let sumB = 0;
    let wB = 0;
    let maxVar = 0;
    let th = 127;
    for (let t = 0; t < 256; t++) {
      wB += hist[t];
      if (!wB) continue;
      const wF = gray.length - wB;
      if (!wF) break;
      sumB += t * hist[t];
      const mB = sumB / wB;
      const mF = (sum - sumB) / wF;
      const between = wB * wF * (mB - mF) * (mB - mF);
      if (between > maxVar) {
        maxVar = between;
        th = t;
      }
    }

    // Keep the downscaled grayscale + threshold so the slope can be re-measured
    // live during the animation overlay (dynamic monitoring of large tilts).
    window.__sheet_monitor = { gray, sw, sh, th };

    const slopeDeg = estimateSheetTilt(gray, sw, sh, th);

    // The sheet paper is the bright majority; keep a generous cutoff so text
    // darkened cells still count as "paper" after downsampling averages them.
    const brightTh = Math.max(150, th);
    let minX = sw, maxX = -1, minY = sh, maxY = -1, count = 0;
    const pts = [];
    for (let y = 0; y < sh; y++) {
      for (let x = 0; x < sw; x++) {
        if (gray[y * sw + x] >= brightTh) {
          if (x < minX) minX = x;
          if (x > maxX) maxX = x;
          if (y < minY) minY = y;
          if (y > maxY) maxY = y;
          count++;
          pts.push(x, y);
        }
      }
    }
    if (!count) return null;

    const cov = count / (sw * sh);
    const bw = maxX - minX + 1;
    const bh = maxY - minY + 1;

    // 1) A real (non-full-frame) sheet visible inside the photo.
    if (cov >= 0.25 && cov <= 0.97 && bw >= sw * 0.4 && bh >= sh * 0.4) {
      const aspect = bw / bh;
      if (aspect >= 0.5 && aspect <= 1.3) {
        const boxCov = (bw * bh) / (sw * sh);
        if (boxCov <= 0.98) {
          // Slope display: PCA over the bright (paper) pixels gives the sheet's
          // dominant orientation and its rotated bounding box.
          const n = pts.length / 2;
          let sx = 0, sy = 0;
          for (let i = 0; i < pts.length; i += 2) {
            sx += pts[i];
            sy += pts[i + 1];
          }
          const mx = sx / n, my = sy / n;
          let sxx = 0, sxy = 0, syy = 0;
          for (let i = 0; i < pts.length; i += 2) {
            const dx = pts[i] - mx, dy = pts[i + 1] - my;
            sxx += dx * dx;
            sxy += dx * dy;
            syy += dy * dy;
          }
          sxx /= n; sxy /= n; syy /= n;
          const theta = 0.5 * Math.atan2(2 * sxy, sxx - syy);
          const c = Math.cos(theta), s = Math.sin(theta);
          let uMin = Infinity, uMax = -Infinity, vMin = Infinity, vMax = -Infinity;
          for (let i = 0; i < pts.length; i += 2) {
            const dx = pts[i] - mx, dy = pts[i + 1] - my;
            const u = dx * c + dy * s;
            const v = -dx * s + dy * c;
            if (u < uMin) uMin = u;
            if (u > uMax) uMax = u;
            if (v < vMin) vMin = v;
            if (v > vMax) vMax = v;
          }
          const kx = W / sw, ky = H / sh;
          const corner = (u, v) => ({
            x: Math.round((mx + u * c - v * s) * kx),
            y: Math.round((my + u * s + v * c) * ky),
          });
          const corners = [
            corner(uMin, vMin),
            corner(uMax, vMin),
            corner(uMax, vMax),
            corner(uMin, vMax),
          ];
          return {
            x: Math.round((minX * W) / sw),
            y: Math.round((minY * H) / sh),
            w: Math.round((bw * W) / sw),
            h: Math.round((bh * H) / sh),
            angle: slopeDeg,
            slope: slopeDeg,
            corners,
            fullFrame: false,
          };
        }
      }
    }

    // 2) Full-frame sheet (paper fills the photo, e.g. a scan/close photo).
    // Keep the existing width-fill box mapping but still report the measured
    // slope so it is drawn on the image and used to deskew crops.
    return {
      x: 0,
      y: 0,
      w: W,
      h: H,
      angle: slopeDeg,
      slope: slopeDeg,
      corners: null,
      fullFrame: true,
    };
  } catch (e) {
    console.warn("detectSheetBounds error", e);
    return null;
  }
}

// Merge word arrays from two full-image OCR passes (bboxes share the same scale).
function mergeWordArrays(wa, wb) {
  const out = (wa || []).slice();
  for (const w of wb || []) {
    if (!w || !w.bbox) {
      out.push(w);
      continue;
    }
    const cx = (w.bbox.x0 + w.bbox.x1) / 2;
    const cy = (w.bbox.y0 + w.bbox.y1) / 2;
    const dup = out.some((o) => {
      if (!o || !o.bbox) return false;
      return (
        Math.abs((o.bbox.x0 + o.bbox.x1) / 2 - cx) < 4 &&
        Math.abs((o.bbox.y0 + o.bbox.y1) / 2 - cy) < 4 &&
        String(o.text || "") === String(w.text || "")
      );
    });
    if (!dup) out.push(w);
  }
  return out;
}

function mergeFullResults(a, b) {
  const text = [a && a.data && a.data.text, b && b.data && b.data.text]
    .filter(Boolean)
    .join("\n");
  const words = mergeWordArrays(
    (a && a.data && a.data.words) || [],
    (b && b.data && b.data.words) || []
  );
  const confidence = Math.max(
    (a && a.data && a.data.confidence) || 0,
    (b && b.data && b.data.confidence) || 0
  );
  return { data: { text, words, confidence } };
}

// parseFatWordsLayout unchanged
function parseFatWordsLayout(words) {
  const toks = (words || [])
    .map((w) => {
      const txt = (w.text || "").replace(/[,，]/g, ".").trim();
      const m = txt.match(/-?[\d]{1,3}(?:[.,][\d]{1,3})?/);
      const num = m ? normNum(m[0]) : null;
      const cx = (w.bbox.x0 + w.bbox.x1) / 2;
      const cy = (w.bbox.y0 + w.bbox.y1) / 2;
      return { txt, num, cx, cy, bbox: w.bbox };
    })
    .filter((t) => t.txt);
  if (!toks.length) return {};
  toks.sort((a, b) => a.cy - b.cy);
  const rows = [];
  for (const t of toks) {
    if (!rows.length || Math.abs(t.cy - rows[rows.length - 1].avgY) > 20)
      rows.push({ items: [t], avgY: t.cy });
    else {
      rows[rows.length - 1].items.push(t);
      rows[rows.length - 1].avgY =
        rows[rows.length - 1].items.reduce((s, o) => s + o.cy, 0) /
        rows[rows.length - 1].items.length;
    };
  };
  const out = {
    left_arm: null,
    right_arm: null,
    trunk: null,
    left_leg: null,
    right_leg: null,
  };
  if (rows.length >= 1) {
    const top = rows[0].items.sort((a, b) => a.cx - b.cx);
    if (top.length >= 2) {
      out.left_arm = top[0].num;
      out.right_arm = top[top.length - 1].num;
    } else if (top.length === 1) out.right_arm = top[0].num;
  }
  if (rows.length >= 2) {
    const mid = rows[Math.min(1, rows.length - 1)].items;
    if (mid.length) {
      const chosen = mid.reduce((a, b) => {
        const av = a && a.num != null ? a.num : 0;
        const bv = b && b.num != null ? b.num : 0;
        return av > bv ? a : b;
      });
      out.trunk = chosen && chosen.num != null ? chosen.num : null;
    };
  };
  if (rows.length >= 3) {
    const bot = rows[rows.length - 1].items.sort((a, b) => a.cx - b.cx);
    if (bot.length >= 2) {
      out.left_leg = bot[0].num;
      out.right_leg = bot[bot.length - 1].num;
    } else if (bot.length === 1) out.right_leg = bot[0].num;
  }
  return out;
};


function drawBoxLabel(ctx, box, label, displayScale, color = "red", angleDeg = 0) {
  const x = Math.round(box.x * displayScale);
  const y = Math.round(box.y * displayScale);
  const w = Math.round(box.w * displayScale);
  const h = Math.round(box.h * displayScale);

  // سماكة الخط تتناسب مع المقياس (لا تقل عن 1)
  ctx.lineWidth = Math.max(1, 2 * displayScale);
  ctx.strokeStyle = color;
  ctx.fillStyle = color;
  ctx.globalAlpha = 0.95;
  if (angleDeg) {
    // ارسم الصندوق بنفس انحدار الورقة المكتشف حتى يلتف حول النص المائل
    const cx = x + w / 2;
    const cy = y + h / 2;
    ctx.save();
    ctx.translate(cx, cy);
    ctx.rotate((angleDeg * Math.PI) / 180);
    ctx.strokeRect(-w / 2 + 0.5, -h / 2 + 0.5, w, h);
    ctx.restore();
  } else {
    ctx.strokeRect(x + 0.5, y + 0.5, w, h);
  }

  // خلفية التسميات ونص واضح (يبقى أفقيًا للقراءة)
  ctx.globalAlpha = 0.92;
  const fontSize = Math.max(10, Math.round(14 * displayScale)); // لا تجعل الخط أصغر من 10px
  ctx.font = `${fontSize}px sans-serif`;
  const text = String(label || "");
  const m = ctx.measureText(text);
  const pad = Math.max(4, Math.round(6 * displayScale));
  const lh = Math.round((fontSize + 4) * 1.0);
  // اجعل الخلفية داخل حدود الصورة (لا ترسم خارجها)
  const bx = x;
  const by = Math.max(0, y - lh);
  ctx.fillRect(bx, by, m.width + pad * 2, lh);

  ctx.fillStyle = "#fff";
  ctx.globalAlpha = 1;
  ctx.fillText(text, bx + pad, by + Math.round(lh * 0.72)); // ضبط عمودي للنص
};

// Draw the number-search area as dashed lines. The search grows OUTWARD from
// the box (never inward), so the dashed rectangle sits around the square,
// `outPx` (raw-canvas px) away from its edges. It follows the same sheet slope
// as the box so the dashed region sits exactly where the search happens.
function drawBoxSearchPerimeter(ctx, box, displayScale, color = "#ffffff", angleDeg = 0, outPx = SEARCH_OUT_MARGIN_PX) {
  const x = Math.round(box.x * displayScale);
  const y = Math.round(box.y * displayScale);
  const w = Math.round(box.w * displayScale);
  const h = Math.round(box.h * displayScale);
  const out = Math.max(1, Math.round(outPx * displayScale));
  const sx = x - out;
  const sy = y - out;
  const sw = w + 2 * out;
  const sh = h + 2 * out;
  ctx.save();
  ctx.lineWidth = Math.max(1, 1.5 * displayScale);
  ctx.strokeStyle = color;
  ctx.globalAlpha = 0.9;
  ctx.setLineDash([6, 4]);
  if (angleDeg) {
    const cx = sx + sw / 2;
    const cy = sy + sh / 2;
    ctx.translate(cx, cy);
    ctx.rotate((angleDeg * Math.PI) / 180);
    ctx.strokeRect(-sw / 2 + 0.5, -sh / 2 + 0.5, sw, sh);
  } else {
    ctx.strokeRect(sx + 0.5, sy + 0.5, sw, sh);
  }
  ctx.setLineDash([]);
  ctx.restore();
};

function showDebugOverlay(rawCanvas, boxesObj = {}, rawBoxTexts = {}, words = null, opts = {}) {
  const maxWidth = opts.maxWidth || 900;
  const showWords = !!opts.showWords;

  const img = document.createElement("img");
  img.src = rawCanvas.toDataURL();
  img.draggable = false;

  const overlay = document.createElement("canvas");
  // natural = دقة الـraw canvas (إحداثيات الأصل)
  const naturalW = rawCanvas.width;
  const naturalH = rawCanvas.height;

  img.onload = () => {
    // display size (كيف سيظهر للمستخدم)
    let imgUp = document.querySelector(".img-up .content .img");
    const DPR = window.devicePixelRatio || 1;

    // اختر displayW اعتمادًا على حجم الحاوية الفعلي (يفضل) أو fallback إلى maxWidth
    // هذا يضمن الرسم يتناسب مع القيود الحقيقية للـ DOM
    const containerW = imgUp.clientWidth || Math.min(naturalW, maxWidth);
    const displayW = Math.min(containerW, maxWidth);
    const displayH = Math.round((naturalH * displayW) / naturalW);
    overlay.width = Math.round(displayW * DPR);
    overlay.height = Math.round(displayH * DPR);
    overlay.style.width = `${displayW}px`;
    overlay.style.height = `${displayH}px`;
    overlay.style.position = "absolute";
    overlay.style.left = "0";
    overlay.style.top = "0";
    overlay.style.pointerEvents = "none";

    // wrapper يحتفظ بمقياس العرض/الارتفاع الفعلي
    const wrapper = document.createElement("div");
    wrapper.className = "wrapper";
    wrapper.style.position = "relative";
    wrapper.style.display = "inline-block";
    wrapper.style.width = `${displayW}px`;
    wrapper.style.height = `${displayH}px`;
    wrapper.style.boxSizing = "content-box";
    wrapper.appendChild(img);
    wrapper.appendChild(overlay);
    imgUp.appendChild(wrapper);

    const ctx = overlay.getContext("2d", { willReadFrequently: true });
    // نعيد مقياس الرسم لتعريف وحدة الرسم على أنها px CSS (نحول من البكسل الداخلي إلى CSS pixels)
    ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
    ctx.clearRect(0, 0, displayW, displayH);

    // displayScale: من إحداثيات rawCanvas (المستخدمة في باقي الدوال) إلى إحداثيات العرض
    const displayScale = displayW / naturalW;

    const colorMap = {
      WEIGHT_ORIG: "#ff3333",
      TBW_ORIG: "#ff9933",
      PROTEIN_ORIG: "#ffcc33",
      FAT_MASS_ORIG: "#33aaff",
      BMI_ORIG: "#9933ff",
      PBF_ORIG: "#33cc66",
      SMM_ORIG: "#cc33aa",
      KCAL_ORIG: "#33cccc",
      ORIG_LEAN: "#66ccff",
      ORIG_FAT: "#ff66cc",
      LEFT_ARM_FAT_ORIG: "#66ff66",
      RIGHT_ARM_FAT_ORIG: "#66ff66",
      LEFT_LEG_FAT_ORIG: "#66ff66",
      RIGHT_LEG_FAT_ORIG: "#66ff66",
    };

    // نرسم الصناديق: scaleOrigBoxToCanvas يرجع إحداثيات بالنسبة لـrawCanvas
    const sheetAngleNow = displaySheetAngle();
    for (const key of Object.keys(boxesObj)) {
      try {
        const origBox = boxesObj[key];
        const scaled = scaleOrigBoxToCanvas(origBox, rawCanvas); // x,y,w,h in raw pixels
        const label =
          key +
          (rawBoxTexts && rawBoxTexts[key]
            ? " ⇢ " +
              (rawBoxTexts[key].rawCanvas_text ||
                rawBoxTexts[key].threshCanvas_text ||
                "")
            : "");
        drawBoxLabel(
          ctx,
          scaled,
          label,
          displayScale,
          colorMap[key] || "#ff3333",
          sheetAngleNow
        );
        // dashed outline of the number-search area (grows OUTWARD from the box)
        drawBoxSearchPerimeter(
          ctx,
          scaled,
          displayScale,
          colorMap[key] || "#ff3333",
          sheetAngleNow
        );
      } catch (e) {
        console.warn("draw box error", key, e);
      };
    };

    // إذا طلبت رسم حدود كلمات OCR
    if (showWords && Array.isArray(words) && words.length) {
      ctx.globalAlpha = 0.45;
      ctx.lineWidth = Math.max(1, 1 * displayScale);
      ctx.strokeStyle = "rgba(0,0,0,0.4)";
      const limit = Math.min(words.length, 800);
      for (let i = 0; i < limit; i++) {
        const w = words[i];
        if (!w.bbox) continue;
        // تحوّل من إحداثيات OCR (التي تكون على نفس مقياس rawCanvas) إلى عرض
        const bx = Math.round(w.bbox.x0 * displayScale);
        const by = Math.round(w.bbox.y0 * displayScale);
        const bw = Math.round((w.bbox.x1 - w.bbox.x0) * displayScale);
        const bh = Math.round((w.bbox.y1 - w.bbox.y0) * displayScale);
        ctx.strokeRect(bx + 0.5, by + 0.5, bw, bh);
      };
      ctx.globalAlpha = 1;
    };
  };
};

function startBoxVisualAnimation(rawCanvas, boxesObj = {}, rawBoxTexts = {}, words = null, opts = {}) {
  const { maxWidth = 900 } = opts || {};
  let raf = null;
  let active = true;

  const controller = {
    stop: () => {
      return new Promise((resolve) => {
        active = false;
        if (raf) cancelAnimationFrame(raf);
        if (overlayCtx && boxStates) {
          const duration = 800; // المدة بالميلي ثانية (غيرها حسب رغبتك)
          const start = performance.now();
          function animateReturn(ts) {
            const progress = Math.min(1, (ts - start) / duration);
            for (const bs of boxStates) {
              // interpolate: move gradually towards orig
              bs.cur.x = bs.cur.x + (bs.orig.x - bs.cur.x) * progress;
              bs.cur.y = bs.cur.y + (bs.orig.y - bs.cur.y) * progress;
            };
            drawAll();
            if (progress < 1) {
              requestAnimationFrame(animateReturn);
            } else {
              for (const bs of boxStates) {
                bs.cur.x = bs.orig.x;
                bs.cur.y = bs.orig.y;
              };
              drawAll();
              resolve();
            };
          };
          requestAnimationFrame(animateReturn);
        } else {
          resolve();
        };
      });
    },
    // Pins each rectangle onto a raw-canvas box (e.g. the OCR value token) so
    // the number stays centered. Safe to call before boxStates are ready.
    pinAll: (map) => {
      window.__boxPinMap = map || null;
      if (Array.isArray(boxStates)) {
        for (const bs of boxStates) {
          const b = map && map[bs.key];
          if (b && b.w > 0 && b.h > 0) {
            bs.orig = { x: b.x, y: b.y, w: b.w, h: b.h };
            bs.cur = { x: b.x, y: b.y, w: b.w, h: b.h };
            // the search already ended, so the dashed area settles right around
            // the square again (small outward margin)
            bs.searchOut = SEARCH_OUT_MARGIN_PX;
          }
        }
      }
      if (overlayCtx) drawAll();
    },
    // Visible outward search: each square grows OUTWARD from its template box by
    // the pad that found its number (`pads[key]`), and the dashed search area
    // expands with it. Resolves when the sweep finishes so the caller can pin the
    // squares onto their numbers and reveal the OCR'd text afterwards.
    searchSweep: (pads, duration = 900) => {
      return new Promise((resolve) => {
        if (!overlayCtx || !boxStates.length) { resolve(); return; }
        const targets = {};
        for (const bs of boxStates) {
          const pad = pads && typeof pads[bs.key] === "number" ? pads[bs.key] : 0;
          const b = bs.base;
          const g = Math.max(0, pad);
          targets[bs.key] = {
            x: Math.max(0, b.x - g),
            y: Math.max(0, b.y - g),
            w: Math.max(1, b.w + 2 * g),
            h: Math.max(1, b.h + 2 * g),
          };
        }
        const start = performance.now();
        function frame(ts) {
          const p = Math.min(1, (ts - start) / duration);
          for (const bs of boxStates) {
            const t = targets[bs.key];
            bs.cur.x = bs.base.x + (t.x - bs.base.x) * p;
            bs.cur.y = bs.base.y + (t.y - bs.base.y) * p;
            bs.cur.w = bs.base.w + (t.w - bs.base.w) * p;
            bs.cur.h = bs.base.h + (t.h - bs.base.h) * p;
            bs.searchOut =
              SEARCH_OUT_MARGIN_PX + Math.max(0, t.w - bs.base.w) * 0.5 * p;
          }
          drawAll();
          if (p < 1) requestAnimationFrame(frame);
          else resolve();
        }
        requestAnimationFrame(frame);
      });
    },
  };

  let overlay = null;
  let overlayCtx = null;
  let boxStates = [];

  let cont = document.querySelector(".img-up");
  let imgUp = document.querySelector(".img-up .content .img");
  cont.classList.add("show-img-up");
  btnShowImgInBody.classList.add("show-button-img");

  const clearOldChildren = () => {
    Array.from(imgUp.childNodes).forEach((ch) => {
      if (ch.nodeType === 1 && ch.tagName.toLowerCase() !== "button")
        ch.remove();
    });
  };

  const img = document.createElement("img");
  img.src = rawCanvas.toDataURL();
  img.draggable = false;

  const naturalW = rawCanvas.width;
  const naturalH = rawCanvas.height;

  function drawAll() {
    if (!overlayCtx) return;
    const displayW = parseInt(overlay.style.width || overlay.width) || overlay.width;
    const displayScale = displayW / naturalW;
    // clear using logical pixels (divide by DPR because ctx is scaled)
    overlayCtx.clearRect(0, 0, overlay.width / (window.devicePixelRatio||1), overlay.height / (window.devicePixelRatio||1));

    // draw detected sheet bounds (green dashed) so slope/detection can be visually verified
    const sheet = window.__sheet_bounds;
    if (sheet && sheet.w > 0 && sheet.h > 0) {
      try {
        overlayCtx.strokeStyle = "#00ff44";
        overlayCtx.lineWidth = Math.max(1.5, 2 * displayScale);
        overlayCtx.setLineDash([8, 6]);
        if (sheet.fullFrame) {
          // Paper fills the whole photo: draw a slope indicator line across the
          // frame at the detected angle plus the angle label.
          const midY = (naturalH / 2) * displayScale;
          const x0 = 0, x1 = naturalW * displayScale;
          const displayAngle = displaySheetAngle();
          const tanA = Math.tan((displayAngle * Math.PI) / 180);
          overlayCtx.beginPath();
          overlayCtx.moveTo(x0 + 0.5, midY + 0.5);
          overlayCtx.lineTo(x1 + 0.5, midY + tanA * naturalW * displayScale + 0.5);
          overlayCtx.stroke();
          overlayCtx.setLineDash([]);
          overlayCtx.fillStyle = "#00ff44";
          overlayCtx.font = `${Math.max(11, Math.round(13 * displayScale))}px monospace`;
          overlayCtx.fillText(
            `slope: ${displayAngle.toFixed(2)}°`,
            12 * displayScale + 6,
            midY + (tanA * naturalW * displayScale) / 2 + 18
          );
        } else if (Array.isArray(sheet.corners) && sheet.corners.length === 4) {
          overlayCtx.beginPath();
          for (let i = 0; i < 4; i++) {
            const cx = sheet.corners[i].x * displayScale + 0.5;
            const cy = sheet.corners[i].y * displayScale + 0.5;
            if (i === 0) overlayCtx.moveTo(cx, cy);
            else overlayCtx.lineTo(cx, cy);
          }
          overlayCtx.closePath();
          overlayCtx.stroke();
          overlayCtx.setLineDash([]);
          overlayCtx.fillStyle = "#00ff44";
          overlayCtx.font = `${Math.max(11, Math.round(13 * displayScale))}px monospace`;
          overlayCtx.fillText(
            `sheet ${displaySheetAngle().toFixed(1)}°`,
            sheet.corners[0].x * displayScale + 8,
            sheet.corners[0].y * displayScale + 16
          );
        } else {
          overlayCtx.strokeRect(
            sheet.x * displayScale + 0.5,
            sheet.y * displayScale + 0.5,
            sheet.w * displayScale,
            sheet.h * displayScale
          );
          overlayCtx.setLineDash([]);
        }
      } catch (e) {}
    }

    for (const bs of boxStates) {
      try {
        const label =
          bs.key +
          (rawBoxTexts && rawBoxTexts[bs.key]
            ? " ⇢ " + (rawBoxTexts[bs.key].rawCanvas_text || rawBoxTexts[bs.key].threshCanvas_text || "")
            : "");
        drawBoxLabel(overlayCtx, { x: bs.cur.x, y: bs.cur.y, w: bs.cur.w, h: bs.cur.h }, label, displayScale, (bs.color || "#ff3333"), bs.angle);
        // dashed outline of the number-search area (grows OUTWARD from the box)
        drawBoxSearchPerimeter(
          overlayCtx,
          { x: bs.cur.x, y: bs.cur.y, w: bs.cur.w, h: bs.cur.h },
          displayScale,
          bs.color || "#ff3333",
          bs.angle,
          bs.searchOut
        );
      } catch (e) {
        console.warn("drawAll error", e);
      };
    };

    if (Array.isArray(words) && words.length) {
      overlayCtx.globalAlpha = 0.45;
      overlayCtx.lineWidth = Math.max(1, 1 * (overlay.width/(window.devicePixelRatio||1) / naturalW));
      overlayCtx.strokeStyle = "rgba(0,0,0,0.4)";
      const limit = Math.min(words.length, 800);
      const displayScaleWords = (overlay.width/(window.devicePixelRatio||1)) / naturalW;
      for (let i = 0; i < limit; i++) {
        const w = words[i];
        if (!w.bbox) continue;
        const bx = Math.round(w.bbox.x0 * displayScaleWords);
        const by = Math.round(w.bbox.y0 * displayScaleWords);
        const bw = Math.round((w.bbox.x1 - w.bbox.x0) * displayScaleWords);
        const bh = Math.round((w.bbox.y1 - w.bbox.y0) * displayScaleWords);
        overlayCtx.strokeRect(bx + 0.5, by + 0.5, bw, bh);
      };
      overlayCtx.globalAlpha = 1;
    };
  };

  img.onload = () => {
    clearOldChildren();
    let imgUp = document.querySelector(".img-up .content .img");
    const DPR = window.devicePixelRatio || 1;

    // اختر displayW اعتمادًا على حجم الحاوية الفعلي (يفضل) أو fallback إلى maxWidth
    // هذا يضمن الرسم يتناسب مع القيود الحقيقية للـ DOM
    const containerW = imgUp.clientWidth || Math.min(naturalW, maxWidth);
    const displayW = Math.min(containerW, maxWidth);
    const displayH = Math.round((naturalH * displayW) / naturalW);
    overlay = document.createElement("canvas");
    overlay.width = Math.round(displayW * DPR);
    overlay.height = Math.round(displayH * DPR);
    console.log(imgUp.clientWidth);
    console.log(imgUp.clientHeight);

    overlay.style.width = `${displayW}px`;
    overlay.style.height = `${displayH}px`;
    overlay.style.position = "absolute";
    overlay.style.left = "0";
    overlay.style.top = "0";
    overlay.style.pointerEvents = "none";

    const wrapper = document.createElement("div");
    wrapper.className = "wrapper";
    wrapper.style.position = "relative";
    wrapper.style.width = `${displayW}px`;
    wrapper.style.height = `${displayH}px`;
    wrapper.appendChild(img);
    wrapper.appendChild(overlay);
    imgUp.appendChild(wrapper);
    wrapperRef = wrapper;

    overlayCtx = overlay.getContext("2d", { willReadFrequently: true });
    overlayCtx.setTransform(DPR, 0, 0, DPR, 0, 0);
    overlayCtx.clearRect(0, 0, displayW, displayH);

    const colorMap = {
      WEIGHT_ORIG: "#ff3333", TBW_ORIG: "#ff9933", PROTEIN_ORIG: "#ffcc33",
      FAT_MASS_ORIG: "#33aaff", BMI_ORIG: "#9933ff", PBF_ORIG: "#33cc66",
      SMM_ORIG: "#cc33aa", KCAL_ORIG: "#33cccc", ORIG_LEAN: "#66ccff",
      ORIG_FAT: "#ff66cc", LEFT_ARM_FAT_ORIG: "#66ff66", RIGHT_ARM_FAT_ORIG: "#66ff66",
      LEFT_LEG_FAT_ORIG: "#66ff66", RIGHT_LEG_FAT_ORIG: "#66ff66"
    };
    boxStates = [];
    const sheetAngleNow = displaySheetAngle();
    for (const key of Object.keys(boxesObj)) {
      try {
        const scaled = scaleOrigBoxToCanvas(boxesObj[key], rawCanvas);
        boxStates.push({
          key,
          base: { x: scaled.x, y: scaled.y, w: scaled.w, h: scaled.h },
          orig: { x: scaled.x, y: scaled.y, w: scaled.w, h: scaled.h },
          cur: { x: scaled.x, y: scaled.y, w: scaled.w, h: scaled.h },
          angle: sheetAngleNow,
          color: colorMap[key] || "#ff3333",
          searchOut: SEARCH_OUT_MARGIN_PX
        });
        // if a pin was set before boxStates existed, apply it immediately
        const pb = window.__boxPinMap && window.__boxPinMap[key];
        if (pb && pb.w > 0 && pb.h > 0) {
          const last = boxStates[boxStates.length - 1];
          last.orig = { x: pb.x, y: pb.y, w: pb.w, h: pb.h };
          last.cur = { x: pb.x, y: pb.y, w: pb.w, h: pb.h };
        }
      } catch (e) { console.warn("prepare box animate error", key, e); };
    };

    let slopeFrame = 0;
    function animate(ts) {
      if (!active) return;

      // Live slope monitoring: re-measure periodically so the drawn slope line,
      // angle label, and the tilted squares track the detected tilt dynamically.
      if (++slopeFrame % 12 === 0) {
        reestimateSheetAngle();
        const liveAngle = displaySheetAngle();
        for (const bs of boxStates) bs.angle = liveAngle;
      }

      // Rectangles stay pinned on their detected numbers (no random drift).

      drawAll();
      raf = requestAnimationFrame(animate);
    };
    drawAll();
    raf = requestAnimationFrame(animate);
  };
  if (img.complete) img.onload();
  return controller;
};

// Rebase OCR word bboxes (crop-local) into raw-canvas coordinates.
function wordsToRaw(words, off) {
  const ox = off && off.x ? off.x : 0;
  const oy = off && off.y ? off.y : 0;
  const out = [];
  for (const w of words || []) {
    if (!w || !w.bbox) continue;
    out.push({
      text: w.text || "",
      bbox: {
        x0: w.bbox.x0 + ox,
        y0: w.bbox.y0 + oy,
        x1: w.bbox.x1 + ox,
        y1: w.bbox.y1 + oy,
      },
    });
  }
  return out;
}

// Find the OCR word (the value number) nearest to a box anchor so each rectangle
// can be re-centered onto the real printed number. Prefers digit tokens.
function findValueTokenBox(anchor, words, rawCanvas, marginX = 120, marginY = 80) {
  if (!anchor) return null;
  const scaled = scaleOrigBoxToCanvas(anchor, rawCanvas);
  const cx = scaled.x + scaled.w / 2;
  const cy = scaled.y + scaled.h / 2;
  const rx = scaled.w / 2 + Math.max(marginX, scaled.w * 0.25);
  const ry = scaled.h / 2 + Math.max(marginY, scaled.h * 0.5);
  let best = null, bestD = Infinity;
  let bestAny = null, bestAnyD = Infinity;
  for (const w of words || []) {
    if (!w || !w.bbox) continue;
    const wcx = (w.bbox.x0 + w.bbox.x1) / 2;
    const wcy = (w.bbox.y0 + w.bbox.y1) / 2;
    if (Math.abs(wcx - cx) > rx || Math.abs(wcy - cy) > ry) continue;
    const d = Math.hypot(wcx - cx, wcy - cy);
    if (/\d/.test(String(w.text || ""))) {
      if (d < bestD) { bestD = d; best = w; }
    } else if (d < bestAnyD) { bestAnyD = d; bestAny = w; }
  }
  const tok = best || bestAny;
  if (!tok) return null;
  return {
    x: tok.bbox.x0,
    y: tok.bbox.y0,
    w: Math.max(1, tok.bbox.x1 - tok.bbox.x0),
    h: Math.max(1, tok.bbox.y1 - tok.bbox.y0),
  };
}

// Build a box that tightly wraps a detected value token (bbox in raw-canvas
// coords) with comfortable padding, so the rectangle hugs the printed number
// instead of using the oversized template box.
function tokenToBox(tokenBBox) {
  if (!tokenBBox) return null;
  const tx = tokenBBox.x + tokenBBox.w / 2;
  const ty = tokenBBox.y + tokenBBox.h / 2;
  const tw = Math.max(1, tokenBBox.w);
  const th = Math.max(1, tokenBBox.h);
  const w = Math.max(tw + 70, tw * 2.0);
  const h = Math.max(th + 50, th * 1.8);
  return {
    x: Math.round(tx - w / 2),
    y: Math.round(ty - h / 2),
    w: Math.round(w),
    h: Math.round(h),
  };
}

// Recenter a box on a detected value token (kept for compatibility with the
// OCR-word fallback path): builds a tight box around the number.
function centerBoxOnToken(anchor, tokenBBox, rawCanvas) {
  if (!anchor || !tokenBBox) return null;
  return tokenToBox(tokenBBox);
}

// Chart rows (SMM / BMI / PBF) have no clean number token: the value shares its
// row with the bar-scale marks, so reading the row is unreliable. But the row
// label at the LEFT edge of the bar chart (e.g. "SMM", "BMI", "PBF") OCRs
// reliably, so we anchor the rectangle onto that row. This is fully layout-
// dynamic: it does not depend on the REF template rows, so it stays correct no
// matter how the photo is zoomed/cropped/tilted. Returns a raw-canvas box
// centered on the detected row (or null if no label is found).
function locateChartRowBBox(words, labelRx, anchorBox, rawCanvas) {
  if (!anchorBox) return null;
  const scaled = scaleOrigBoxToCanvas(anchorBox, rawCanvas);
  const cx = scaled.x + scaled.w / 2;
  const cy = scaled.y + scaled.h / 2;
  const bandH = Math.max(scaled.h * 4, rawCanvas.height * 0.06);

  // The real row label is the one inside the box's LEFT half (the "BMI is an
  // index..." explanation tokens sit far to the right and must be excluded).
  let best = null;
  let bestD = Infinity;
  for (const w of words || []) {
    if (!w || !w.bbox || !w.text) continue;
    if (w.confidence !== undefined && w.confidence < 40) continue;
    if (!labelRx.test(String(w.text).trim())) continue;
    if (w.bbox.x0 >= cx) continue;
    const wcy = (w.bbox.y0 + w.bbox.y1) / 2;
    if (Math.abs(wcy - cy) > bandH) continue;
    const d = Math.abs(wcy - cy);
    if (d < bestD) {
      bestD = d;
      best = w;
    }
  }
  if (!best) return null;
  const lcy = (best.bbox.y0 + best.bbox.y1) / 2;

  // If a DECIMAL token sits on the same row to the right of the label, that is
  // the printed value (scale marks are whole numbers) - center on it. Otherwise
  // keep the template-width box snapped onto the label row so it still covers
  // the number.
  const rowH = Math.max(1, best.bbox.y1 - best.bbox.y0);
  const tolY = Math.max(20, rowH * 0.9);
  let decBest = null;
  let decBestD = Infinity;
  for (const w of words || []) {
    if (!w || !w.bbox || !w.text || w === best) continue;
    if (!isNumericTokenText(w.text)) continue;
    if (w.confidence !== undefined && w.confidence < 40) continue;
    const wcy = (w.bbox.y0 + w.bbox.y1) / 2;
    if (Math.abs(wcy - lcy) > tolY) continue;
    if (w.bbox.x0 < best.bbox.x1) continue;
    const txt = String(w.text);
    if (!txt.includes(".") && !txt.includes(",")) continue;
    const wcx = (w.bbox.x0 + w.bbox.x1) / 2;
    const d = Math.abs(wcx - cx);
    if (d < decBestD) {
      decBestD = d;
      decBest = w;
    }
  }
  if (decBest) {
    const box = tokenToBox({
      x: decBest.bbox.x0,
      y: decBest.bbox.y0,
      w: decBest.bbox.x1 - decBest.bbox.x0,
      h: decBest.bbox.y1 - decBest.bbox.y0,
    });
    box.y = Math.round(lcy - box.h / 2);
    return box;
  }
  const h = Math.max(scaled.h, Math.round(rowH * 1.8));
  return {
    x: scaled.x,
    y: Math.round(lcy - h / 2),
    w: scaled.w,
    h: Math.round(h),
  };
}

async function run() {
  if (!currentFile) return;
  statusEl.textContent = "تحضير الصورة وOCR...";
  try {
    // preprocess (scale reduced to 3)
    const canvases = await preprocessToCanvas(currentFile, 3, true);
    const rawCanvas = canvases.rawCanvas;
    const threshCanvas = canvases.threshCanvas;

    // Detect the physical sheet inside the photo once; all box mapping uses it.
    window.__sheet_bounds = detectSheetBounds(rawCanvas);
    console.log("sheet bounds (canvas px):", window.__sheet_bounds);
    // Fresh label store for the overlay. The same object is passed into the
    // renderers by reference, so the OCR'd value text can be revealed on the
    // squares only AFTER the outward search finds the numbers.
    window.__rawBoxTextsForDebug = {};
    showDebugOverlay(
      rawCanvas,
      {
        WEIGHT_ORIG,
        TBW_ORIG,
        PROTEIN_ORIG,
        FAT_MASS_ORIG,
        BMI_ORIG,
        PBF_ORIG,
        SMM_ORIG,
        KCAL_ORIG,
        ORIG_LEAN,
        LEFT_ARM_LEAN_ORIG,
        RIGHT_ARM_LEAN_ORIG,
        LEFT_LEG_LEAN_ORIG,
        RIGHT_LEG_LEAN_ORIG,
        ORIG_FAT,
        LEFT_ARM_FAT_ORIG,
        RIGHT_ARM_FAT_ORIG,
        LEFT_LEG_FAT_ORIG,
        RIGHT_LEG_FAT_ORIG,
      },
      window.__rawBoxTextsForDebug || {},
      null,
      { maxWidth: 900, showWords: true }
    );
    const animController = startBoxVisualAnimation(rawCanvas, {
      WEIGHT_ORIG, TBW_ORIG, PROTEIN_ORIG, FAT_MASS_ORIG, BMI_ORIG, PBF_ORIG, SMM_ORIG, KCAL_ORIG,
      ORIG_LEAN, LEFT_ARM_LEAN_ORIG, RIGHT_ARM_LEAN_ORIG, LEFT_LEG_LEAN_ORIG, RIGHT_LEG_LEAN_ORIG,
      ORIG_FAT, LEFT_ARM_FAT_ORIG, RIGHT_ARM_FAT_ORIG, LEFT_LEG_FAT_ORIG, RIGHT_LEG_FAT_ORIG,
    }, window.__rawBoxTextsForDebug || {}, null, { maxWidth: 900 });

    // ثم بعد اكتمال full OCR (إذا أردت رسم حدود كلمات OCR أيضاً) استدعِ مرة أخرى:
    // بعد تعريف fullRes:
    // full OCR on thresholded canvas for layout/words
    statusEl.textContent = "OCR على الصورة الكاملة (layout)...";
    const fullResThresh = await Tesseract.recognize(threshCanvas, "eng", {
      logger: (m) => {
        setTimeout(() => {
          animController.stop();
        }, 15000);
        if (m && m.progress)
          statusEl.textContent = `OCR (full): ${
            m.status || ""
          } ${Math.round(m.progress * 100)}%`;
      },
    });
    // second full OCR pass on the raw (unthresholded) canvas so word-level
    // fallbacks still work when lighting makes the binary pass drop text.
    statusEl.textContent = "OCR على الصورة الكاملة (raw)...";
    const fullResRaw = await Tesseract.recognize(rawCanvas, "eng", {
      logger: (m) => {},
    });
    const fullRes = mergeFullResults(fullResThresh, fullResRaw);
    const fullText = fullRes.data.text || "";

    // Locate each headline field's value number from the FULL-image OCR words
    // (label-anchored, layout-independent). The located bbox is the exact printed
    // position of the number, so we can re-center the rectangle on it regardless
    // of how the photo was zoomed/cropped/tilted relative to the REF template.
    const located = {};
    const locateTasks = [
      ["weight", [/^Weight$/i], ["Weight"], WEIGHT_ORIG, { minVal: 10, maxVal: 350, field: "weight", decimals: 1 }],
      ["tbw", [/^Water$/i], ["Water"], TBW_ORIG, { minVal: 5, maxVal: 200, field: "water", decimals: 2 }],
      ["protein", [/^Protein$/i], ["Protein"], PROTEIN_ORIG, { minVal: 5, maxVal: 200, field: "protein", decimals: 1 }],
      ["fat", [/^Fat$/i], ["Fat"], FAT_MASS_ORIG, { minVal: 0.1, maxVal: 500, field: "fat", decimals: 1 }],
      ["kcal", [/^BMR$/i, /^Basal$/i, /^Metabolic$/i, /^Kcal$/i, /^Kcalories$/i], ["Basal", "Metabolic"], KCAL_ORIG, { minVal: 50, maxVal: 5000, field: "kcal", decimals: 0 }],
    ];
    for (const [key, rxs, lws, anchor, o] of locateTasks) {
      located[key] = await locateFieldNumber(fullRes.data.words || [], rxs, lws, rawCanvas, { anchorBox: anchor, ...o });
    }
    const locateBBoxes = {
      WEIGHT_ORIG: located.weight && located.weight.bbox,
      TBW_ORIG: located.tbw && located.tbw.bbox,
      PROTEIN_ORIG: located.protein && located.protein.bbox,
      FAT_MASS_ORIG: located.fat && located.fat.bbox,
      KCAL_ORIG: located.kcal && located.kcal.bbox,
    };
    window.__locatedForDebug = located;
    window.__locateBBoxesForDebug = locateBBoxes;
    if (window.__debug_ocr) {
      const arr = JSON.parse(sessionStorage.getItem("__ocr_debug_log") || "[]");
      arr.push({ located: Object.fromEntries(Object.entries(located).map(([k, v]) => [k, v ? { value: v.value, bbox: v.bbox, kind: v.kind } : null])), ts: Date.now() });
      sessionStorage.setItem("__ocr_debug_log", JSON.stringify(arr));
    }

    // LEAN crop OCR (thresholded) for lean numbers
    const leanScaled = scaleOrigBoxToCanvas(ORIG_LEAN, rawCanvas);
    const leanCrop = cropCanvas(
      threshCanvas,
      leanScaled.x,
      leanScaled.y,
      leanScaled.w,
      leanScaled.h
    );
    const leanRes = await Tesseract.recognize(leanCrop, "eng", {
      logger: (m) => {},
    });

    // FAT crop OCR (thresholded) for segmental fat layout
    const fatScaled = scaleOrigBoxToCanvas(ORIG_FAT, rawCanvas);
    const fatCrop = cropCanvas(
      threshCanvas,
      fatScaled.x,
      fatScaled.y,
      fatScaled.w,
      fatScaled.h
    );
    const fatRes = await Tesseract.recognize(fatCrop, "eng", {
      logger: (m) => {},
    });
    const fatTextRaw = (fatRes.data.text || "")
      .replace(/[_\n\r]/g, " ")
      .trim();

    // small numeric crops using expand fallback
    // small numeric crops using expand fallback (updated: use let where we may reassign)
    statusEl.textContent =
      "قراءة الأرقام من الـ crops الصغيرة (with expand fallback)...";
    const tbwRes = await expandAndFindNumber(
      TBW_ORIG,
      rawCanvas,
      5,
      200,
      6,
      "water"
    );
    const proteinRes = await expandAndFindNumber(
      PROTEIN_ORIG,
      rawCanvas,
      5,
      200,
      6,
      "protein"
    );
    let smmRes = await expandAndFindNumber(
      SMM_ORIG,
      rawCanvas,
      10,
      300,
      7,
      "smm"
    ); // <-- let (may be reassigned); min 10 rejects scale-row junk reads like "5"
    const weightRes = await expandAndFindNumber(
      WEIGHT_ORIG,
      rawCanvas,
      10,
      300,
      6,
      "weight"
    );
    let bmiRes = await expandAndFindNumber(
      BMI_ORIG,
      rawCanvas,
      2,
      100,
      7,
      "bmi"
    ); // <-- let (may be reassigned)
    let pbfRes = await expandAndFindNumber(PBF_ORIG, rawCanvas, 2, 60, 7, "pbf"); // <-- let (may be reassigned)
    let fatMassRes = await expandAndFindNumber(
      FAT_MASS_ORIG,
      rawCanvas,
      0.1,
      500,
      7,
      "fat"
    ); // <-- let
    let kcalRes = await expandAndFindNumber(
      KCAL_ORIG,
      rawCanvas,
      50,
      5000,
      7,
      "kcal"
    ); // <-- let

    // Capture the exact raw-canvas position of each value read from its small
    // crop (bbox comes back in raw coordinates). Used to re-center the visual
    // rectangles onto the real printed numbers even when the full-image OCR
    // words are missing. Capture before the wide fallbacks may reassign below.
    const valueBBoxes = {
      WEIGHT_ORIG: weightRes.bbox,
      TBW_ORIG: tbwRes.bbox,
      PROTEIN_ORIG: proteinRes.bbox,
      FAT_MASS_ORIG: fatMassRes.bbox,
      BMI_ORIG: bmiRes.bbox,
      PBF_ORIG: pbfRes.bbox,
      SMM_ORIG: smmRes.bbox,
      KCAL_ORIG: kcalRes.bbox,
    };

    // Outward search distance reached for each small-crop field (the pad that
    // found the value). The sweep animation grows each square by exactly this
    // much so the squares are seen moving OUTWARD until they find the number.
    const searchPads = {};
    for (const [k, r] of [
      ["WEIGHT_ORIG", weightRes], ["TBW_ORIG", tbwRes], ["PROTEIN_ORIG", proteinRes],
      ["FAT_MASS_ORIG", fatMassRes], ["BMI_ORIG", bmiRes], ["PBF_ORIG", pbfRes],
      ["SMM_ORIG", smmRes], ["KCAL_ORIG", kcalRes],
    ]) {
      if (r && typeof r.padUsed === "number") searchPads[k] = r.padUsed;
    }

    const fieldAwareResult = (res, field) => {
      if (!res || res.value === null || res.value === undefined) return null;
      if (!isReasonableCandidate(res.value, res.raw_text || "", field === "bmi" ? 2 : field === "pbf" ? 2 : field === "kcal" ? 50 : 0.1, field === "bmi" ? 60 : field === "pbf" ? 100 : field === "kcal" ? 5000 : 500, field)) {
        return null;
      }
      return res.value;
    };

    const tbwValue = fieldAwareResult(tbwRes, "water");
    const proteinValue = fieldAwareResult(proteinRes, "protein");
    const smmValue = fieldAwareResult(smmRes, "generic");
    const weightValue = fieldAwareResult(weightRes, "weight");
    const bmiValue = fieldAwareResult(bmiRes, "bmi");
    const pbfValue = fieldAwareResult(pbfRes, "pbf");
    const fatMassValue = fieldAwareResult(fatMassRes, "generic");
    const kcalValue = fieldAwareResult(kcalRes, "kcal");
    try {
      // إذا كانت النتيجة الحالية أقل من 1000 (يعني ممكن اقتطع رقم) وحقل raw_text موجود
      if (kcalRes && kcalRes.raw_text && (kcalRes.value == null || kcalRes.value < 1000)) {
        const txt = String(kcalRes.raw_text || '') + ' ' + (kcalRes.raw_text_from_expand || '') || '';
        // مطابقة 4-6 أرقام متتالية فقط (مثل 1710). لا نستخدم fallback 3-أرقام
        // لأنه يحوّل "74" صحيحة إلى "741"/"740" عند وجود أحرف مشوّهة مجاورة.
        let m = txt.match(/(\d{4,6})(?:[.,]\d{1,3})?/);
        if (m) {
          const candidate = parseFloat(m[1].replace(',', '.'));
          // نطاق منطقي للقيمة — عدل النطاق لو ترغب (مثلاً أقل من 10000)
          if (!isNaN(candidate) && candidate >= 50 && candidate <= 5000) {
            kcalRes = {
              value: candidate,
              raw_text: txt,
              padUsed: 'kcal-local-fix'
            };
            console.info('kcal local-fix applied ->', candidate);
          }
        }
      }
    } catch (e) {
      console.warn('kcal local-fix error', e);
    }

    // SMM post-check (can reassign smmRes). Scale markers are whole numbers and
    // the SMM value always has a decimal, so integer/low/null reads are suspect:
    // try the label-anchored value first (validated: "35"+"1" -> 35.1), then the
    // wide box, then nearby full-image words.
    const smmIntegerRead = smmRes.value !== null && smmRes.value === Math.round(smmRes.value);
    // SMM chart-row reader always attempts first (same rationale as BMI/PBF).
    // Values above 80 kg are impossible for a person and indicate scale-row
    // contamination (portrait reads e.g. 208.2), so they are overridden too.
    {
      const chart = await readChartRowValue(
        fullRes.data.words || [],
        [/^Skeletal$/, /^Muscle$/, /^Mass$/],
        SMM_ORIG,
        rawCanvas,
        10,
        300,
        "smm"
      );
      const cur = smmRes && smmRes.value;
      const needChart =
        cur === null ||
        cur === undefined ||
        cur > 80 ||
        (smmRes.confidence !== undefined && smmRes.confidence < 50) ||
        cur === Math.round(cur) ||
        (chart && chart.value !== null && Math.abs(chart.value - cur) > 0.3);
      if (chart && chart.value !== null && needChart)
        smmRes = {
          value: chart.value,
          raw_text: "from-chart-row",
          padUsed: "layout",
        };
    }
    if (smmRes.value === null || smmRes.value < 8 || smmRes.value > 80 || smmIntegerRead) {
      const anchored = await readValueByLabelAnchor(
        fullRes.data.words || [],
        [/^SMM(?!\w)/i, /^Skeletal$/, /^Muscle$/, /^Mass$/],
        SMM_ORIG,
        rawCanvas,
        10,
        300,
        "smm"
      );
      if (anchored && anchored.value !== null)
        smmRes = {
          value: anchored.value,
          raw_text: "from-label-anchor",
          padUsed: "layout",
        };
      else if (smmRes.value === null || smmRes.value < 8) {
        const origW = window.__orig_img_w || 1135;
        const wideBox = {
          x: Math.max(0, SMM_ORIG.x - 300),
          y: Math.max(0, SMM_ORIG.y - 80),
          w: Math.min(origW - SMM_ORIG.x + 300, SMM_ORIG.w + 600),
          h: SMM_ORIG.h + 160,
        };
        const wideRes = await expandAndFindNumber(
          wideBox,
          rawCanvas,
          8,
          300,
          6,
          "smm-wide"
        );
        if (wideRes.value !== null && wideRes.value >= 8) smmRes = wideRes;
        else {
          const wordFallback = findNumericNearBox(
            fullRes.data.words || [],
            SMM_ORIG,
            rawCanvas,
            8,
            300,
            1200
          );
          if (wordFallback)
            smmRes = {
              value: wordFallback,
              raw_text: "from-full-words-fallback",
              padUsed: "layout",
            };
        };
      };
    };

    // BMI: if missing, low-confidence (scale-row contamination), or read as a
    // whole number (BMI values always carry a decimal, so an integer crop read
    // is almost certainly a scale marker), re-read from the "BMI" label row and
    // fall back to nearby words.
    // BMI: chart-row reader always attempts first. The bar-tip value lives on the
    // "Body Mass Index" full-name row; scale-row garbage (e.g. "191" -> 19.1) must
    // never win, so a meaningfully different or missing read is overridden by the
    // full-name-row tight crop (validated: "08"+"5" cluster -> 28.5, true 28.45).
    {
      const chart = await readChartRowValue(
        fullRes.data.words || [],
        [/^Body$/, /^Mass$/, /^Index$/],
        BMI_ORIG,
        rawCanvas,
        8,
        60,
        "bmi"
      );
      const cur = bmiRes && bmiRes.value;
      const needChart =
        cur === null ||
        cur === undefined ||
        (bmiRes.confidence !== undefined && bmiRes.confidence < 50) ||
        cur === Math.round(cur) ||
        (chart && chart.value !== null && Math.abs(chart.value - cur) > 0.3);
      if (chart && chart.value !== null && needChart)
        bmiRes = {
          value: chart.value,
          raw_text: "from-chart-row",
          padUsed: "layout",
        };
    }
    if (
      bmiRes.value === null ||
      (bmiRes.confidence !== undefined && bmiRes.confidence < 50) ||
      (bmiRes.value !== null && bmiRes.value === Math.round(bmiRes.value))
    ) {
      const anchored = await readValueByLabelAnchor(
        fullRes.data.words || [],
        [/^BMI(?!\w)/i, /^Body$/, /^Mass$/, /^Index$/],
        BMI_ORIG,
        rawCanvas,
        8,
        60,
        "bmi"
      );
      if (anchored && anchored.value !== null)
        bmiRes = {
          value: anchored.value,
          raw_text: "from-label-anchor",
          padUsed: "layout",
        };
      else {
        const wordFallback = findNumericNearBox(
          fullRes.data.words || [],
          BMI_ORIG,
          rawCanvas,
          8,
          60,
          1200
        );
        if (wordFallback)
          bmiRes = {
            value: wordFallback,
            raw_text: "from-full-words-fallback",
            padUsed: "layout",
          };
      };
    };

    // PBF: if missing, low-confidence (scale-row contamination), or read as a
    // whole number (PBF values carry a decimal), re-read from the "PBF" label
    // row and fall back to nearby words.
    // PBF: chart-row reader always attempts first (same rationale as BMI). The
    // "Percent Body Fat" full-name row carries the true value; the abbreviation
    // row only holds scale markers that OCR garbles into wrong decimals.
    {
      const chart = await readChartRowValue(
        fullRes.data.words || [],
        [/^Percent$/, /^Body$/, /^Fat$/],
        PBF_ORIG,
        rawCanvas,
        2,
        60,
        "pbf"
      );
      const cur = pbfRes && pbfRes.value;
      const needChart =
        cur === null ||
        cur === undefined ||
        (pbfRes.confidence !== undefined && pbfRes.confidence < 50) ||
        cur === Math.round(cur) ||
        (chart && chart.value !== null && Math.abs(chart.value - cur) > 0.3);
      if (chart && chart.value !== null && needChart)
        pbfRes = {
          value: chart.value,
          raw_text: "from-chart-row",
          padUsed: "layout",
        };
    }
    if (
      pbfRes.value === null ||
      (pbfRes.confidence !== undefined && pbfRes.confidence < 50) ||
      (pbfRes.value !== null && pbfRes.value === Math.round(pbfRes.value))
    ) {
      const anchored = await readValueByLabelAnchor(
        fullRes.data.words || [],
        [/^PBF(?!\w)/i, /^Percent$/, /^Body$/, /^Fat$/],
        PBF_ORIG,
        rawCanvas,
        2,
        60,
        "pbf"
      );
      if (anchored && anchored.value !== null)
        pbfRes = {
          value: anchored.value,
          raw_text: "from-label-anchor",
          padUsed: "layout",
        };
      else {
        const wordFallback = findNumericNearBox(
          fullRes.data.words || [],
          PBF_ORIG,
          rawCanvas,
          2,
          100,
          1400
        );
        if (wordFallback)
          pbfRes = {
            value: wordFallback,
            raw_text: "from-full-words-fallback",
            padUsed: "layout",
          };
      };
    };

    // FAT MASS: if missing or unrealistic, fallback to words near FAT_MASS_ORIG
    if (fatMassRes.value === null || (fatMassRes.value !== null && fatMassRes.value > 300)) {
      const wordFallback = findNumericNearBox(
        fullRes.data.words || [],
        FAT_MASS_ORIG,
        rawCanvas,
        0.1,
        500,
        1400
      );
      if (wordFallback)
        fatMassRes = {
          value: wordFallback,
          raw_text: "from-full-words-fallback",
          padUsed: "layout",
        };
    };

    // kcal: post-check (kcal expected ~ >3). If <3 or null, try wide search + words.
    if (kcalRes.value === null || (kcalRes.value !== null && kcalRes.value < 3)) {
      const origW2 = window.__orig_img_w || 1135;
      const wideBox = {
        x: Math.max(0, KCAL_ORIG.x - 200),
        y: Math.max(0, KCAL_ORIG.y - 80),
        w: Math.min(origW2 - KCAL_ORIG.x + 200, KCAL_ORIG.w + 400),
        h: KCAL_ORIG.h + 160,
      };
      const wideRes2 = await expandAndFindNumber(
        wideBox,
        rawCanvas,
        50,
        5000,
        6,
        "kcal-wide"
      );
      if (wideRes2.value !== null && wideRes2.value >= 3)
        kcalRes = wideRes2;
      else {
        const wordFallback = findNumericNearBox(
          fullRes.data.words || [],
          KCAL_ORIG,
          rawCanvas,
          50,
          5000,
          1400
        );
        if (wordFallback)
          kcalRes = {
            value: wordFallback,
            raw_text: "from-full-words-fallback",
            padUsed: "layout",
          };
      };
    };

    // fallbacks by searching near box in fullRes words if still null
    const weightFallback =
      weightRes.value === null
        ? findNumericNearBox(
            fullRes.data.words || [],
            WEIGHT_ORIG,
            rawCanvas,
            10,
            300,
            900
          )
        : null;
    const proteinFallback =
      proteinRes.value === null
        ? findNumericNearBox(
            fullRes.data.words || [],
            PROTEIN_ORIG,
            rawCanvas,
            10,
            300,
            900
          )
        : null;
    const smmFallback =
      smmRes.value === null
        ? findNumericNearBox(
            fullRes.data.words || [],
            SMM_ORIG,
            rawCanvas,
            1,
            300,
            900
          )
        : null;
    const tbwFallback =
      tbwRes.value === null
        ? findNumericNearBox(
            fullRes.data.words || [],
            TBW_ORIG,
            rawCanvas,
            5,
            200,
            900
          ): null;

    const tbwParsed = tbwValue !== null ? tbwValue : tbwFallback;
    const proteinParsed = proteinValue !== null ? proteinValue : proteinRes.value;
    const smmParsed = smmValue !== null ? smmValue : smmFallback;
    const weightParsed =
      weightValue !== null ? weightValue : weightFallback;
    // ===== تحسين BMI و PBF باستخدام full OCR كـ fallback أقوى =====
    const combinedCandidates = extractNumberCandidates(fullText); // تأكد هذه المتغيرات متاحة في النطاق
    // ===== تحسين BMI و PBF باستخدام full OCR كـ fallback أقوى =====
    const bmiParsed = bmiValue !== null ? bmiValue : null;
    const pbfParsed = pbfValue !== null ? pbfValue : null;

    // ===== بديل أقوى لـ BMI و PBF (استبدل البلوك القديم بهذا) =====
    function normLabelRegex(label) {
      // نسمح بمسافات/حروف مشوّهة بين أحرف اللابل (للتعامل مع OCR الغلط)
      const chars = label
        .split("")
        .map((c) => c.replace(/[-/\\^$*+?.()|[\]{}]/g, "\\$&"))
        .join("\\s*");
      return new RegExp(chars, "i");
    };

    function findLabelNearby(fullWords, labelRegex, preferPct = true, minV = Number.NEGATIVE_INFINITY, maxV = Number.POSITIVE_INFINITY) {
      if (!Array.isArray(fullWords)) return null;
      const numRe = /-?[\d]{1,3}(?:[.,][\d]{1,3})?/;
      // scan words for a label-like token (supports fuzzy labelRegex)
      for (let i = 0; i < fullWords.length; i++) {
        const w = fullWords[i];
        const wtxt =
          w && w.text ? (w.text || "").replace(/[,，]/g, ".").trim() : "";
        if (!wtxt) continue;
        if (labelRegex.test(wtxt)) {
          // try immediate neighbors to the right (common)
          for (let d = 1; d <= 6; d++) {
            const right = fullWords[i + d];
            if (!right) continue;
            const s = (right.text || "").replace(/[,，]/g, ".").trim();
            // look for percent first if preferPct
            const pct = s.match(/(\d{1,3}[.,]?\d{0,3})\s*%/);
            if (preferPct && pct) {
              const v = normNum(pct[1]);
              if (v !== null && v >= minV && v <= maxV) return v;
            };
            const m = s.match(numRe);
            if (m) {
              const v = normNum(m[0]);
              if (v !== null && v >= minV && v <= maxV) return v;
            };
          };
          // try neighbors to the left
          for (let d = 1; d <= 3; d++) {
            const left = fullWords[i - d];
            if (!left) continue;
            const s = (left.text || "").replace(/[,，]/g, ".").trim();
            const pct = s.match(/(\d{1,3}[.,]?\d{0,3})\s*%/);
            if (preferPct && pct) {
              const v = normNum(pct[1]);
              if (v !== null && v >= minV && v <= maxV) return v;
            };
            const m = s.match(numRe);
            if (m) {
              const v = normNum(m[0]);
              if (v !== null && v >= minV && v <= maxV) return v;
            };
          };
          // try any number on same baseline (same row)
          const midY = (w.bbox && (w.bbox.y0 + w.bbox.y1) / 2) || null;
          if (midY !== null) {
            const sameLine = fullWords.filter((f) => {
              if (!f.bbox) return false;
              const fy = (f.bbox.y0 + f.bbox.y1) / 2;
              return Math.abs(fy - midY) < 14; // tolerance
            });
            for (const f of sameLine) {
              const s = (f.text || "").replace(/[,，]/g, ".").trim();
              const pct = s.match(/(\d{1,3}[.,]?\d{0,3})\s*%/);
              if (preferPct && pct) {
                const v = normNum(pct[1]);
                if (v !== null && v >= minV && v <= maxV) return v;
              }
              const m = s.match(numRe);
              if (m) {
                const v = normNum(m[0]);
                if (v !== null && v >= minV && v <= maxV) return v;
              };
            };
          };
        };
      };
      return null;
    };

    // fuzzy regexes (تسمح B M I أو BMI أو BMi الخ)
    const BMI_LABEL = normLabelRegex("BMI");
    const PBF_LABEL = normLabelRegex("PBF");

    // try layered fallback for BMI
    let bmiFinal = null;
    if (bmiRes && bmiRes.value !== null) {
      bmiFinal = bmiRes.value;
    } else {
      // 1) بحث مباشر في fullText بعد كلمة "BMI" (نبحث عن رقم عشري أولاً)
      let m = (fullText || "").match(
        /BMI[^\d]{0,30}([0-9]{1,2}[.,][0-9]{1,3})/i
      );
      if (m) bmiFinal = normNum(m[1]);

      // 2) إذا لم يوجد، بحث عن أنماط "kg/m2" أو "kg/m²" التي غالبًا تتبع رقم الـ BMI
      if (bmiFinal === null) {
        m = (fullText || "").match(
          /([2-9][0-9](?:[.,][0-9]{1,2})?)\s*(?:kg\/m2|kg\/m²|kg\/m\^2|kg m-2|kg m²|kg\/m\^2)/i
        );
        if (m) bmiFinal = normNum(m[1]);
      };

      // 3) ابحث في المرشّحين المجمّعين (combinedCandidates) قرب "BMI" في الـ ctx
      if (bmiFinal === null && Array.isArray(combinedCandidates)) {
        const cand = combinedCandidates.find(
          (c) =>
            /BMI/i.test(c.ctx) &&
            c.num !== null &&
            c.num >= 8 &&
            c.num <= 60
        );
        if (cand) bmiFinal = cand.num;
      };

      // 4) استخدام findLabelNearby (كلمات الـ OCR) — محاولة إيجاد رقم عند جملة "BMI"
      if (bmiFinal === null && typeof findLabelNearby === "function") {
        const near = findLabelNearby(
          fullRes.data.words || [],
          BMI_LABEL,
          false,
          8,
          60
        );
        if (near !== null) bmiFinal = near;
      };

      // 5) بحث مكثّف قريب من صندوق BMI باستخدام findNumericNearBox كـ fallback
      if (bmiFinal === null) {
        const near2 = findNumericNearBox(
          fullRes.data.words || [],
          BMI_ORIG,
          rawCanvas,
          8,
          60,
          3000
        );
        if (near2 !== null) bmiFinal = near2;
      };

      // 6) إذا لم نعثر على تسمية BMI لكن توجد أرقام عشرية مناسبة في النص، فضّل الأرقام ذات الفاصلة العشرية
      if (bmiFinal === null && Array.isArray(combinedCandidates)) {
        const dec = combinedCandidates.find(
          (c) => String(c.num).includes(".") && c.num >= 8 && c.num <= 60
        );
        if (dec) bmiFinal = dec.num;
      };

      // 7) أخيرًا: جرّب حساب BMI من الوزن والطول إن أمكن (إذا وُجد طول بالـ cm)
      if (bmiFinal === null && typeof findHeightCmInCombined === "function") {
        const h = findHeightCmInCombined();
        if (h !== null && general.weight_kg != null) {
          const bmiFromH = computeBMIFromWeightAndHeight(
            general.weight_kg,
            h
          );
          if (bmiFromH !== null) bmiFinal = bmiFromH;
        };
      };
    };

    if (bmiFinal !== null) bmiFinal = +bmiFinal.toFixed(1);

    // try layered fallback for PBF (% preferred)
    let pbfFinal = null;
    if (pbfRes && pbfRes.value !== null) {
      pbfFinal = pbfRes.value;
    } else {
      pbfFinal = findLabelNearby(fullRes.data.words || [], PBF_LABEL, true, 2, 100);
      if (pbfFinal === null) {
        const near = findNumericNearBox(fullRes.data.words || [], PBF_ORIG, rawCanvas, 2, 100, 1400);
        if (near !== null) pbfFinal = near;
      };
      if (pbfFinal === null) {
        // search for first percentage near "Body" or "Fat" in fullText
        const ft = fullText || "";
        const re =
          /(?:PBF|body fat|percent body fat)[^\d]{0,8}([0-9]{1,3}[.,]?[0-9]{0,3})\s*%/i;
        const m = ft.match(re);
        if (m) pbfFinal = normNum(m[1]);
      };
    };
    if (pbfFinal !== null) pbfFinal = +pbfFinal.toFixed(1);

    // final parsed values to use downstream
    const bmiParsedFinal =
      bmiFinal !== null
        ? bmiFinal
        : bmiParsed !== null
        ? +bmiParsed.toFixed(1)
        : null;
    const pbfParsedFinal =
      pbfFinal !== null
        ? pbfFinal
        : pbfRes && pbfRes.value !== null
        ? +pbfRes.value.toFixed(1)
        : null;

    // segmental fat: try small explicit crops then layout parsing from fatRes words
    async function trySmallSeg(orig) {
      const r = await expandAndFindNumber(orig, rawCanvas, 0.0, 200.0, 5, "seg");
      return r;
    };
    const leftArmSmall = await trySmallSeg(LEFT_ARM_FAT_ORIG);
    const rightArmSmall = await trySmallSeg(RIGHT_ARM_FAT_ORIG);
    const leftLegSmall = await trySmallSeg(LEFT_LEG_FAT_ORIG);
    const rightLegSmall = await trySmallSeg(RIGHT_LEG_FAT_ORIG);

    // console.log("FAT WORDS", fatRes.data.words.slice(0, 80));
    const fatLayout = parseFatWordsLayout(fatRes.data.words || []);
    const leanParsed = parseLean(
      leanRes.data.words || [],
      fullRes.data.words || [],
      rawCanvas
    );
    const fatParsedLikeLean = parseFat(
      fatRes.data.words || [],
      fullRes.data.words || [],
      rawCanvas
    );

    // Pin every value rectangle onto its real number. Preferred source: the
    // exact bbox recovered from the small crop that read each value (raw-canvas
    // coords). Fallback source: OCR word tokens (full image + lean/fat crops).
    const pinWords = []
      .concat(wordsToRaw(fullRes.data.words || [], null))
      .concat(wordsToRaw(leanRes.data.words || [], leanScaled))
      .concat(wordsToRaw(fatRes.data.words || [], fatScaled));
    const PIN_BOXES = {
      WEIGHT_ORIG, TBW_ORIG, PROTEIN_ORIG, FAT_MASS_ORIG,
      BMI_ORIG, PBF_ORIG, SMM_ORIG, KCAL_ORIG,
      LEFT_ARM_LEAN_ORIG, RIGHT_ARM_LEAN_ORIG,
      LEFT_LEG_LEAN_ORIG, RIGHT_LEG_LEAN_ORIG,
      LEFT_ARM_FAT_ORIG, RIGHT_ARM_FAT_ORIG,
      LEFT_LEG_FAT_ORIG, RIGHT_LEG_FAT_ORIG,
    };
    // Convert a {x0,y0,x1,y1} OCR-style bbox into a raw {x,y,w,h} box.
    const bboxToBox = (b) =>
      b && b.x1 != null && b.y1 != null
        ? tokenToBox({ x: b.x0, y: b.y0, w: b.x1 - b.x0, h: b.y1 - b.y0 })
        : null;
    const leanCoords = leanParsed.coords || {};
    const fatCoords = fatParsedLikeLean.coords || {};
    const directPins = {
      WEIGHT_ORIG: valueBBoxes.WEIGHT_ORIG,
      TBW_ORIG: valueBBoxes.TBW_ORIG,
      PROTEIN_ORIG: valueBBoxes.PROTEIN_ORIG,
      FAT_MASS_ORIG: valueBBoxes.FAT_MASS_ORIG,
      BMI_ORIG: valueBBoxes.BMI_ORIG,
      PBF_ORIG: valueBBoxes.PBF_ORIG,
      SMM_ORIG: valueBBoxes.SMM_ORIG,
      KCAL_ORIG: valueBBoxes.KCAL_ORIG,
      LEFT_ARM_LEAN_ORIG: leanCoords.left_arm && wordsToRaw([{ bbox: leanCoords.left_arm }], leanScaled)[0] &&
        (wordsToRaw([{ bbox: leanCoords.left_arm }], leanScaled)[0].bbox),
      RIGHT_ARM_LEAN_ORIG: leanCoords.right_arm && (wordsToRaw([{ bbox: leanCoords.right_arm }], leanScaled)[0].bbox),
      LEFT_LEG_LEAN_ORIG: leanCoords.left_leg && (wordsToRaw([{ bbox: leanCoords.left_leg }], leanScaled)[0].bbox),
      RIGHT_LEG_LEAN_ORIG: leanCoords.right_leg && (wordsToRaw([{ bbox: leanCoords.right_leg }], leanScaled)[0].bbox),
      LEFT_ARM_FAT_ORIG: (leftArmSmall && leftArmSmall.bbox) || (fatCoords.left_arm && (wordsToRaw([{ bbox: fatCoords.left_arm }], fatScaled)[0].bbox)),
      RIGHT_ARM_FAT_ORIG: (rightArmSmall && rightArmSmall.bbox) || (fatCoords.right_arm && (wordsToRaw([{ bbox: fatCoords.right_arm }], fatScaled)[0].bbox)),
      LEFT_LEG_FAT_ORIG: (leftLegSmall && leftLegSmall.bbox) || (fatCoords.left_leg && (wordsToRaw([{ bbox: fatCoords.left_leg }], fatScaled)[0].bbox)),
      RIGHT_LEG_FAT_ORIG: (rightLegSmall && rightLegSmall.bbox) || (fatCoords.right_leg && (wordsToRaw([{ bbox: fatCoords.right_leg }], fatScaled)[0].bbox)),
    };
    // Override the template-based boxes with the exact label-anchored number
    // positions found from the full-image OCR. This is what centers each
    // rectangle onto the real printed number regardless of layout drift.
    for (const [key, b] of Object.entries(locateBBoxes)) {
      if (b && b.x1 != null) directPins[key] = b;
    }
    const pinned = {};
    const usedSource = {};
    // Chart rows carry no clean value token (bar-scale noise), so anchor them on
    // their row label first - this is the dynamic, layout-independent path.
    const CHART_ROW_LABELS = {
      SMM_ORIG: /^SMM(?!\w)/i,
      BMI_ORIG: /^BMI(?!\w)/i,
      PBF_ORIG: /^PBF(?!\w)/i,
    };
    for (const key of Object.keys(PIN_BOXES)) {
      try {
        let box = null;
        if (CHART_ROW_LABELS[key]) {
          box = locateChartRowBBox(fullRes.data.words || [], CHART_ROW_LABELS[key], PIN_BOXES[key], rawCanvas);
          if (box) usedSource[key] = "chartlabel";
        }
        if (!box) {
          const direct = directPins[key];
          if (direct && direct.x1 != null) {
            box = bboxToBox(direct);
            if (box) usedSource[key] = "crop";
          }
        }
        if (!box) {
          const tok = findValueTokenBox(PIN_BOXES[key], pinWords, rawCanvas);
          if (tok) {
            box = centerBoxOnToken(PIN_BOXES[key], tok, rawCanvas);
            if (box) usedSource[key] = "words";
          }
        }
        if (box) pinned[key] = box;
      } catch (e) {
        console.warn("pin box error", key, e);
      }
    }
    // The squares search OUTWARD until they find their numbers: animate each
    // square growing outward by the pad that found its value, THEN snap it onto
    // the number, and only after that reveal the OCR'd value text.
    if (typeof animController.searchSweep === "function") {
      await animController.searchSweep(searchPads);
    }
    if (Object.keys(pinned).length) {
      window.__pinnedBoxesForDebug = pinned;
      window.__pinSources = usedSource;
      if (typeof animController.pinAll === "function") {
        animController.pinAll(pinned);
        console.log("pinned value rectangles onto OCR tokens:", pinned, "sources:", usedSource);
      }
    }
    // Reveal the value text on each square now that the search has found it.
    if (!window.__rawBoxTextsForDebug) window.__rawBoxTextsForDebug = {};
    const revealTexts = {
      WEIGHT_ORIG: weightParsed,
      TBW_ORIG: tbwParsed,
      PROTEIN_ORIG: proteinParsed,
      FAT_MASS_ORIG: fatMassValue,
      SMM_ORIG: smmRes.value,
      BMI_ORIG: bmiRes.value,
      PBF_ORIG: pbfRes.value,
      KCAL_ORIG: kcalRes.value,
    };
    for (const [k, v] of Object.entries(revealTexts)) {
      if (v !== null && v !== undefined && typeof v === "number" && Number.isFinite(v)) {
        window.__rawBoxTextsForDebug[k] = { rawCanvas_text: String(v) };
      }
    }

    // robust parseLean — replace any existing parseLean with this one
    // improved parseLean: يفضّل قيم "kg" داخل كل منطقة من LeanRes.data.words
    // استبدل دالتك الحالية بهذه الدالة
    // استبدل parseLean الحالية بهذه الدالة:
    function parseLean(leanWords, fullWords, rawCanvas) {
      // مساعدة لتحويل النص إلى رقم (أبقيت normNum كما في parseLean)
      function normNum(s) {
        if (s === null || s === undefined) return null;
        const cleaned = ("" + s)
          .replace(/[,，]/g, ".")
          .replace(/[^\d\.-]/g, "");
        if (!cleaned || cleaned === "." || cleaned === "-") return null;
        const v = parseFloat(cleaned);
        return Number.isFinite(v) ? v : null;
      };

      // helper لتنسيق الأرقام: truncate إلى 1 منزلة عشرية، وأرجع integer إذا كانت صحيحة
      function lmt1(v) {
        if (v === null || v === undefined) return null;
        // استخدم Math.trunc للحفاظ على إشارة العدد عند الأعداد السالبة أيضاً
        const truncated = Math.trunc(v * 10) / 10;
        // إذا أصبحت صحيحة بعد القص، أرجع integer
        if (Math.abs(truncated - Math.round(truncated)) < 1e-9) {
          return Math.round(truncated);
        };
        // خلاف ذلك أرجع بقيمة عشرية واحدة
        return Number(truncated.toFixed(1));
      };

      const toks = (leanWords || [])
        .map((w) => {
          const txt = (w.text || "").replace(/[,，]/g, ".").trim();
          const m = txt.match(/-?[\d]{1,3}(?:[.,][\d]{1,3})?/);
          const num = m ? normNum(m[0]) : null;
          return {
            txt,
            num,
            isPct: /%/.test(txt),
            bbox: w.bbox,
            cx: w.bbox ? (w.bbox.x0 + w.bbox.x1) / 2 : 0,
            cy: w.bbox ? (w.bbox.y0 + w.bbox.y1) / 2 : 0,
          };
        })
        .filter((t) => t.txt);

      // helper: find kg-looking token among leanWords items in a given vertical band
      function searchLeanTokensForRegion(regionFilter, preferSmall = true, minV = 0.0, maxV = 9999) {
        const items = toks.filter(regionFilter);
        if (!items.length) return null;
        // prefer explicit kg tokens
        const kgItems = items.filter(
          (i) =>
            /\bkg\b/i.test(i.txt) &&
            i.num !== null &&
            i.num >= minV &&
            i.num <= maxV
        );
        if (kgItems.length) {
          kgItems.sort((a, b) =>
            preferSmall ? a.num - b.num : b.num - a.num
          );
          return lmt1(kgItems[0].num);
        };
        // else pick non-% numeric tokens
        const nonPct = items.filter(
          (i) =>
            !i.isPct && i.num !== null && i.num >= minV && i.num <= maxV
        );
        if (nonPct.length) {
          nonPct.sort((a, b) =>
            preferSmall ? a.num - b.num : b.num - a.num
          );
          return lmt1(nonPct[0].num);
        };
        return null;
      };

      const out = {
        left_arm_lean_kg: null,
        right_arm_lean_kg: null,
        trunk_lean_kg: null,
        left_leg_lean_kg: null,
        right_leg_lean_kg: null,
        coords: {},
      };

      if (toks.length) {
        toks.sort((a, b) => a.cy - b.cy);
        const rows = [];
        for (const t of toks) {
          if (!rows.length || Math.abs(t.cy - rows[rows.length - 1].avgY) > 20)
            rows.push({ items: [t], avgY: t.cy });
          else {
            rows[rows.length - 1].items.push(t);
            rows[rows.length - 1].avgY =
              rows[rows.length - 1].items.reduce((s, o) => s + o.cy, 0) /
              rows[rows.length - 1].items.length;
          };
        };
        // top row -> arms
        if (rows.length >= 1) {
          const top = rows[0].items.sort((a, b) => a.cx - b.cx);
          const center = (top[0].cx + top[top.length - 1].cx) / 2;
          const leftSide = top.filter((t) => t.cx <= center);
          const rightSide = top.filter((t) => t.cx > center);
          out.left_arm_lean_kg = searchLeanTokensForRegion((it) => leftSide.includes(it), true, 0.05, 12);
          out.right_arm_lean_kg = searchLeanTokensForRegion((it) => rightSide.includes(it), true, 0.05, 12);
          if (leftSide[0] && leftSide[0].bbox)
            out.coords.left_arm = leftSide[0].bbox;
          if (rightSide[0] && rightSide[0].bbox)
            out.coords.right_arm = rightSide[0].bbox;
        };
        // mid row -> trunk
        if (rows.length >= 2) {
          const mid = rows[Math.min(1, rows.length - 1)].items;
          out.trunk_lean_kg = searchLeanTokensForRegion(
            (it) => mid.includes(it),
            false,
            0.5,
            9999
          );
          if (mid[0] && mid[0].bbox) out.coords.trunk = mid[0].bbox;
        };
        // bottom row -> legs
        if (rows.length >= 3) {
          const bot = rows[rows.length - 1].items.sort(
            (a, b) => a.cx - b.cx
          );
          const center = (bot[0].cx + bot[bot.length - 1].cx) / 2;
          const leftSide = bot.filter((t) => t.cx <= center);
          const rightSide = bot.filter((t) => t.cx > center);
          out.left_leg_lean_kg = searchLeanTokensForRegion((it) => leftSide.includes(it), true, 0.3, 200);
          out.right_leg_lean_kg = searchLeanTokensForRegion((it) => rightSide.includes(it), true, 0.3, 200);
          if (leftSide[0] && leftSide[0].bbox)
            out.coords.left_leg = leftSide[0].bbox;
          if (rightSide[0] && rightSide[0].bbox)
            out.coords.right_leg = rightSide[0].bbox;
        };
      };

      // fallback باستخدام fullWords و الثوابت (LEFT_ARM_LEAN_ORIG ... الخ)
      if ((out.left_arm_lean_kg === null || out.right_arm_lean_kg === null) && Array.isArray(fullWords)) {
        try {
          if (out.left_arm_lean_kg === null && typeof LEFT_ARM_LEAN_ORIG !== "undefined") {
            const v = findNumericNearBox(fullWords, LEFT_ARM_LEAN_ORIG, rawCanvas, 0.05, 12, 900);
            if (v !== null) out.left_arm_lean_kg = lmt1(v);
          };
          // القديم (يُوجد في بلوك fallback بالأسفل)
          if (out.right_arm_lean_kg === null && typeof RIGHT_ARM_LEAN_ORIG !== "undefined") {
            const v2 = findNumericNearBox(fullWords, RIGHT_ARM_LEAN_ORIG, rawCanvas, 0.05, 12, 900);
            if (v2 !== null) out.right_arm_lean_kg = lmt1(v2);
          };
          // fallback للـ right arm — نفس العمليات لكن من دون تقريب للقيمة
          if (out.right_arm_lean_kg === null && typeof RIGHT_ARM_LEAN_ORIG !== "undefined") {
            // محاولة 1: البحث الموسع باستخدام findNumericNearBox
            let v2 = findNumericNearBox(fullWords, RIGHT_ARM_LEAN_ORIG, rawCanvas, 0.05, 12, 1400);

            // محاولة 2: صندوق أوسع إذا الأولى لم تُرجع قيمة
            if (v2 === null) {
              const origW =
                window.__orig_img_w ||
                REF_W ||
                RIGHT_ARM_LEAN_ORIG.w ||
                0;
              const wideBox = {
                x: Math.max(0, RIGHT_ARM_LEAN_ORIG.x - 240),
                y: Math.max(0, RIGHT_ARM_LEAN_ORIG.y - 120),
                w: Math.min(
                  origW - Math.max(0, RIGHT_ARM_LEAN_ORIG.x - 240),
                  (RIGHT_ARM_LEAN_ORIG.w || 0) + 480
                ),
                h: (RIGHT_ARM_LEAN_ORIG.h || 0) + 240,
              };
              v2 = findNumericNearBox(fullWords, wideBox, rawCanvas, 0.05, 12, 2000);
            };

            // إذا وجدنا نتيجة مباشرة، ننظّفها ونحولها لعدد دون تقريب إضافي
            if (v2 !== null) {
              let s = String(v2)
                .replace(/kg/gi, "")
                .replace(/,/g, ".")
                .replace(/_/g, "")
                .replace(/\s+/g, "");
              s = s.replace(/[^0-9.]/g, "");
              if (s) {
                const dotCount = (s.match(/\./g) || []).length;
                if (dotCount > 1) {
                  const last = s.lastIndexOf(".");
                  s = s.slice(0, last).replace(/\./g, "") + s.slice(last);
                };
                if (s.indexOf(".") === -1 && (s.length === 3 || s.length === 4)) {
                  s =
                    s.slice(0, s.length - 2) +
                    "." +
                    s.slice(s.length - 2);
                };
                const num = parseFloat(s);
                if (!Number.isNaN(num)) {
                  // Important: لا نقّرب هنا — نخزن القيمة كما هي (مثال: 3.71)
                  out.right_arm_lean_kg = num;
                };
              };
            };

            // فحص احتياطي خاص للتوكنات ثلاثية/رباعية الأرقام مثل "_371kg"
            try {
              const scaledRA = scaleOrigBoxToCanvas(
                RIGHT_ARM_LEAN_ORIG,
                rawCanvas
              );
              const centerX = scaledRA.x + scaledRA.w / 2;
              const centerY = scaledRA.y + scaledRA.h / 2;

              let best = null;
              for (const w of fullWords || []) {
                if (!w || !w.text || !w.bbox) continue;
                const cx = (w.bbox.x0 + w.bbox.x1) / 2;
                const cy = (w.bbox.y0 + w.bbox.y1) / 2;
                const d = Math.hypot(cx - centerX, cy - centerY);
                if (d > 2200) continue;

                const txt = String(w.text);
                const m = txt.match(/(\d{3,4})/);
                if (m) {
                  const digits = m[1];
                  if (!best || d < best.d)
                    best = { digits, d, raw: w.text };
                };
              };

              if (best) {
                let s2 = best.digits;
                if (s2.length === 3 || s2.length === 4) {
                  s2 =
                    s2.slice(0, s2.length - 2) +
                    "." +
                    s2.slice(s2.length - 2);
                };
                const n2 = parseFloat(s2);
                if (!Number.isNaN(n2)) {
                  if (out.right_arm_lean_kg === null || Math.abs(out.right_arm_lean_kg - n2) > 0.0001) {
                    out.right_arm_lean_kg = n2;
                  };
                };
              };
            } catch (e) {
              // لا نريد أن يكسر ذلك التنفيذ
            };
          };

          const scaledRA = scaleOrigBoxToCanvas(
            RIGHT_ARM_LEAN_ORIG,
            rawCanvas
          );
          console.log(
            "WORDS near RIGHT_ARM (candidates):",
            (fullWords || [])
              .filter((w) => w && w.bbox)
              .map((w) => {
                const cx = (w.bbox.x0 + w.bbox.x1) / 2;
                const cy = (w.bbox.y0 + w.bbox.y1) / 2;
                return {
                  t: w.text,
                  cx,
                  cy,
                  d: Math.hypot(
                    cx - (scaledRA.x + scaledRA.w / 2),
                    cy - (scaledRA.y + scaledRA.h / 2)
                  ),
                };
              })
              .filter((o) => o.d < 1800)
              .slice(0, 40)
          );

          if (out.left_leg_lean_kg === null && typeof LEFT_LEG_LEAN_ORIG !== "undefined") {
            const v3 = findNumericNearBox(
              fullWords,
              LEFT_LEG_LEAN_ORIG,
              rawCanvas,
              0.3,
              200,
              900
            );
            if (v3 !== null) out.left_leg_lean_kg = lmt1(v3);
          };
          if (out.right_leg_lean_kg === null && typeof RIGHT_LEG_LEAN_ORIG !== "undefined") {
            const v4 = findNumericNearBox(
              fullWords,
              RIGHT_LEG_LEAN_ORIG,
              rawCanvas,
              0.3,
              200,
              900
            );
            if (v4 !== null) out.right_leg_lean_kg = lmt1(v4);
          };
          if (out.trunk_lean_kg === null && typeof ORIG_LEAN !== "undefined") {
            const v5 = findNumericNearBox(
              fullWords,
              ORIG_LEAN,
              rawCanvas,
              0.5,
              9999,
              1200
            );
            if (v5 !== null) out.trunk_lean_kg = lmt1(v5);
          };
        } catch (e) {
          console.warn("parseLean fallback search error", e);
        };
      };
      [
        "left_arm_lean_kg",
        "right_arm_lean_kg",
        "trunk_lean_kg",
        "left_leg_lean_kg",
        "right_leg_lean_kg",
      ].forEach((k) => {
        if (out[k] !== null && typeof out[k] === "number") {
          out[k] = out[k].toFixed(1); // الآن القيمة "4.0" كنص
        };
      });
      return out;
    };

    function parseFat(fatWords, fullWords, rawCanvas) {
      // محلي: تحويل أي نص OCR إلى عدد منطقي للحقل الصغير (بدون تقريب)
      function normalizeFatTokenForParsing(raw) {
        if (raw === null || raw === undefined) return null;
        let s = String(raw)
          .replace(/[,，]/g, ".")
          .replace(/_/g, "")
          .replace(/\s+/g, "");
        // أبقِ على الأرقام والنقطة فقط
        s = s.replace(/[^0-9.]/g, "");
        if (!s) return null;

        // إذا توجد أكثر من نقطة، اترك الأخيرة فقط
        const dots = (s.match(/\./g) || []).length;
        if (dots > 1) {
          const last = s.lastIndexOf(".");
          s = s.slice(0, last).replace(/\./g, "") + s.slice(last);
        };

        // إذا لا توجد نقطة، أدخل الفاصلة حسب طول السلسلة:
        // '8' -> '0.8', '22' -> '2.2', '371' -> '3.71', '1146' -> '11.46'
        if (s.indexOf(".") === -1) {
          if (s.length === 1) s = "0." + s;
          else if (s.length === 2)
            s = s.slice(0, s.length - 1) + "." + s.slice(s.length - 1);
          else if (s.length >= 3)
            s = s.slice(0, s.length - 2) + "." + s.slice(s.length - 2);
        };
        const n = parseFloat(s);
        return Number.isNaN(n) ? null : n;
      };

      // --- إخراج ابتدائي ---
      const toks = (fatWords || [])
        .map((w) => {
          const txt = (w.text || "").replace(/[,，]/g, ".").trim();
          // محاولة بسيطة لاستخراج رقم (قد لا يكون الكل محسوباً هنا، normalize لاحقًا)
          const m = txt.match(/-?[\d]{1,4}(?:[.,][\d]{1,4})?/);
          // لا نعتمد على normNum الخارجي: سنستخدم normalizeFatTokenForParsing لاحقًا
          const rawCandidate = m ? m[0] : null;
          const parsed = normalizeFatTokenForParsing(rawCandidate || txt);
          return {
            txt,
            rawCandidate,
            parsed, // قيمة رقمية أو null بعد التطبيع
            isPct: /%/.test(txt),
            bbox: w.bbox,
            cx: w.bbox ? (w.bbox.x0 + w.bbox.x1) / 2 : 0,
            cy: w.bbox ? (w.bbox.y0 + w.bbox.y1) / 2 : 0,
          };
        })
        .filter((t) => t.txt);

      // helper: find kg-looking token among fatWords items in a given vertical band
      function searchFatTokensForRegion(regionFilter, preferSmall = true, minV = 0.0, maxV = 9999) {
        const items = toks.filter(regionFilter);
        if (!items.length) return null;

        // نفضّل توكنات تحتوي "kg" أولاً
        const kgItems = items.filter(
          (i) =>
            /\bkg\b/i.test(i.txt) &&
            (i.parsed !== null
              ? i.parsed >= minV && i.parsed <= maxV
              : true)
        );

        if (kgItems.length) {
          // نرتب بحسب القيمة المنطقيّة إذا وُجدت؛ إن لم تتوفر parsed فنحاول تطبيع النص
          kgItems.forEach((it) => {
            if (it.parsed === null)
              it.parsed = normalizeFatTokenForParsing(it.txt);
          });
          const valid = kgItems.filter(
            (it) =>
              it.parsed !== null && it.parsed >= minV && it.parsed <= maxV
          );
          if (valid.length) {
            valid.sort((a, b) =>
              preferSmall ? a.parsed - b.parsed : b.parsed - a.parsed
            );
            return valid[0].parsed;
          };
          // لو لا توجد parsed صالحة، نأخذ أول kg token كقيمة عددية إن أمكن استخراجها مباشرة من النص الخام
          const fallback = kgItems[0];
          const nf = normalizeFatTokenForParsing(fallback.txt);
          return nf;
        };

        // خلاف ذلك، نبحث عن توكنات رقمية غير بالنسبة المئوية
        const nonPct = items.filter((i) => !i.isPct);
        if (nonPct.length) {
          nonPct.forEach((it) => {
            if (it.parsed === null)
              it.parsed = normalizeFatTokenForParsing(it.txt);
          });
          const valid = nonPct.filter(
            (it) =>
              it.parsed !== null && it.parsed >= minV && it.parsed <= maxV
          );
          if (valid.length) {
            valid.sort((a, b) =>
              preferSmall ? a.parsed - b.parsed : b.parsed - a.parsed
            );
            return valid[0].parsed;
          };
          // أخيراً، إن لم توجد parsed صالحة، حاول استخراج من أول nonPct نصي
          const fallback = nonPct[0];
          const nf = normalizeFatTokenForParsing(fallback.txt);
          return nf;
        };
        return null;
      };

      // الإخراج النهائي
      const out = {
        left_arm_fat_kg: null,
        right_arm_fat_kg: null,
        trunk_fat_kg: null,
        left_leg_fat_kg: null,
        right_leg_fat_kg: null,
        coords: {},
      };

      // top/mid/bottom rows داخل fatWords (فرضية أن fatWords تحوي قيم القطاعات غالبًا)
      if (toks.length) {
        toks.sort((a, b) => a.cy - b.cy);
        const rows = [];
        for (const t of toks) {
          if (
            !rows.length ||
            Math.abs(t.cy - rows[rows.length - 1].avgY) > 18
          )
            rows.push({ items: [t], avgY: t.cy });
          else {
            rows[rows.length - 1].items.push(t);
            rows[rows.length - 1].avgY =
              rows[rows.length - 1].items.reduce((s, o) => s + o.cy, 0) /
              rows[rows.length - 1].items.length;
          }
        }

        // top row -> arms
        if (rows.length >= 1) {
          const top = rows[0].items.sort((a, b) => a.cx - b.cx);
          const center = (top[0].cx + top[top.length - 1].cx) / 2;
          const leftSide = top.filter((t) => t.cx <= center);
          const rightSide = top.filter((t) => t.cx > center);
          out.left_arm_fat_kg = searchFatTokensForRegion(
            (it) => leftSide.includes(it),
            true,
            0.05,
            12
          );
          out.right_arm_fat_kg = searchFatTokensForRegion(
            (it) => rightSide.includes(it),
            true,
            0.05,
            12
          );
          if (leftSide[0] && leftSide[0].bbox)
            out.coords.left_arm = leftSide[0].bbox;
          if (rightSide[0] && rightSide[0].bbox)
            out.coords.right_arm = rightSide[0].bbox;
        };

        // mid row -> trunk
        if (rows.length >= 2) {
          const mid = rows[Math.min(1, rows.length - 1)].items;
          out.trunk_fat_kg = searchFatTokensForRegion(
            (it) => mid.includes(it),
            false,
            0.5,
            9999
          );
          if (mid[0] && mid[0].bbox) out.coords.trunk = mid[0].bbox;
        };

        // bottom row -> legs
        if (rows.length >= 3) {
          const bot = rows[rows.length - 1].items.sort(
            (a, b) => a.cx - b.cx
          );
          const center = (bot[0].cx + bot[bot.length - 1].cx) / 2;
          const leftSide = bot.filter((t) => t.cx <= center);
          const rightSide = bot.filter((t) => t.cx > center);
          out.left_leg_fat_kg = searchFatTokensForRegion(
            (it) => leftSide.includes(it),
            true,
            0.3,
            200
          );
          out.right_leg_fat_kg = searchFatTokensForRegion(
            (it) => rightSide.includes(it),
            true,
            0.3,
            200
          );
          if (leftSide[0] && leftSide[0].bbox)
            out.coords.left_leg = leftSide[0].bbox;
          if (rightSide[0] && rightSide[0].bbox)
            out.coords.right_leg = rightSide[0].bbox;
        };
      };

      // fallback باستخدام fullWords إن لم نجد ضمن fatWords
      if ((out.left_arm_fat_kg === null || out.right_arm_fat_kg === null || out.left_leg_fat_kg === null || out.right_leg_fat_kg === null || out.trunk_fat_kg === null) && Array.isArray(fullWords)) {
        try {
          // left arm
          if (out.left_arm_fat_kg === null && typeof LEFT_ARM_FAT_ORIG !== "undefined") {
            const v = findNumericNearBox(
              fullWords,
              LEFT_ARM_FAT_ORIG,
              rawCanvas,
              0.05,
              12,
              900
            );
            if (v !== null) {
              const nv = normalizeFatTokenForParsing(v);
              if (nv !== null) out.left_arm_fat_kg = nv;
            };
          };

          // right arm
          if (out.right_arm_fat_kg === null && typeof RIGHT_ARM_FAT_ORIG !== "undefined") {
            const v2 = findNumericNearBox(
              fullWords,
              RIGHT_ARM_FAT_ORIG,
              rawCanvas,
              0.05,
              12,
              900
            );
            if (v2 !== null) {
              const nv = normalizeFatTokenForParsing(v2);
              if (nv !== null) out.right_arm_fat_kg = nv;
            };
          };

          // left leg
          if (out.left_leg_fat_kg === null && typeof LEFT_LEG_FAT_ORIG !== "undefined") {
            const v3 = findNumericNearBox(
              fullWords,
              LEFT_LEG_FAT_ORIG,
              rawCanvas,
              0.3,
              200,
              900
            );
            if (v3 !== null) {
              const nv = normalizeFatTokenForParsing(v3);
              if (nv !== null) out.left_leg_fat_kg = nv;
            };
          };

          // right leg
          if (out.right_leg_fat_kg === null && typeof RIGHT_LEG_FAT_ORIG !== "undefined") {
            const v4 = findNumericNearBox(
              fullWords,
              RIGHT_LEG_FAT_ORIG,
              rawCanvas,
              0.3,
              200,
              900
            );
            if (v4 !== null) {
              const nv = normalizeFatTokenForParsing(v4);
              if (nv !== null) out.right_leg_fat_kg = nv;
            };
          };

          // trunk (أقوى فالباك بمربع أكبر)
          if (
            out.trunk_fat_kg === null &&
            typeof ORIG_FAT !== "undefined"
          ) {
            const v5 = findNumericNearBox(
              fullWords,
              ORIG_FAT,
              rawCanvas,
              0.5,
              9999,
              1200
            );
            if (v5 !== null) {
              const nv = normalizeFatTokenForParsing(v5);
              if (nv !== null) out.trunk_fat_kg = nv;
            };
          };
        } catch (e) {
          console.warn("parseFat fallback search error", e);
        };
      };

      // safety: إذا أي قيمة تبدو كنسبة مئوية كبيرة (>100) نلغيها
      [
        "left_arm_fat_kg",
        "right_arm_fat_kg",
        "trunk_fat_kg",
        "left_leg_fat_kg",
        "right_leg_fat_kg",
      ].forEach((k) => {
        if (out[k] !== null && typeof out[k] === "number" && out[k] > 100)
          out[k] = null;
      });
      return out;
    };

    function heuristicAdjust(label, value, raw_text) {
      if (value == null) return value;
      if (!isFinite(value)) return value;
      if (label === "protein_kg") {
        // إذا قرأنا قيمة كبيرة جدا (مثلاً >50kg) فهي غالباً خطأ فى الفاصلة
        if (value > 50) {
          const v10 = value / 10;
          if (v10 >= 1 && v10 <= 50) return +v10.toFixed(1);
          const v100 = value / 100;
          if (v100 >= 0.5 && v100 <= 50) return +v100.toFixed(1);
        };
      };

      // Generic fallback: لو القيمة ضخمة جداً حاول تقليلها بعوامل معقولة
      if (value > 500) {
        const v10 = value / 10;
        if (v10 > 0 && v10 < 500) return +v10.toFixed(1);
        const v100 = value / 100;
        if (v100 > 0 && v100 < 500) return +v100.toFixed(1);
      };
      return value;
    };

    function pickByCtx(labelRe, min, max) {
      if (Array.isArray(combinedCandidates)) {
        for (const c of combinedCandidates) {
          if (c && c.ctx && labelRe.test(c.ctx)) {
            const v = c.num;
            if (v !== null && v >= min && v <= max) return v;
          };
        };
      };
      if (fullText) {
        const src = labelRe.source.replace(/\\[a-z]/g, "");
        const re = new RegExp(
          "(" + src + ")[^\\d]{0,25}?([0-9]{1,3}[.,][0-9]{1,3}|[0-9]{1,3})",
          "i"
        );
        const m = fullText.match(re);
        if (m && m[2]) {
          const v = normNum(m[2]);
          if (v !== null && v >= min && v <= max) return v;
        };
      };
      return null;
    };

    const general = {
      weight_kg:
        weightParsed !== null && weightParsed !== undefined
          ? +weightParsed.toFixed(1)
          : pickByCtx(/weight/i, 10, 350) ?? null,
      BMI: bmiParsedFinal !== null ? bmiParsedFinal : null,
      PBF_percent: pbfParsedFinal !== null ? pbfParsedFinal : null,
      SMM_kg: smmParsed,
      water:
        tbwParsed !== null && tbwParsed !== undefined
          ? +tbwParsed.toFixed(1)
          : pickByCtx(/total body water/i, 5, 200) ?? null,
      fat_mass_kg:
        fatMassRes && fatMassRes.value !== null
          ? +fatMassRes.value.toFixed(1)
          : (function () {
              const v = pickByCtx(/body fat mass|fat mass/i, 0.1, 500);
              return v !== null ? +v.toFixed(1) : null;
            })(),
      protein_kg: (function () {
        const raw =
          proteinParsed !== null && proteinParsed !== undefined
            ? proteinParsed
            : pickByCtx(/protein/i, 0.1, 200) ?? null;
        const adj = heuristicAdjust(
          "protein_kg",
          raw,
          proteinRes && proteinRes.raw_text
        );
        return adj !== null && adj !== undefined ? +adj.toFixed(1) : null;
      })(),
    };
    function findHeightCmInCombined() {
      if (!Array.isArray(combinedCandidates)) return null;

      // مساعدة لتحويل أي نص إلى رقم صالح
      function toNum(x) {
        if (x === null || x === undefined) return null;
        const s = String(x).replace(/[^\d.\-]/g, "");
        const n = parseFloat(s);
        return Number.isFinite(n) ? n : null;
      };

      // BMI helper
      function bmiFor(heightCm, weight) {
        if (!heightCm || !weight) return null;
        const h = heightCm / 100;
        if (h <= 0) return null;
        return +(weight / (h * h));
      };

      // جمع مرشحين كما في النسخة السابقة (scoring)
      const labelRe = /\b(height|ht|الطول|الارتفاع)\b/i;
      const candidates = [];

      // 1) البحث في fullText
      try {
        const ft = String(fullText || "");
        const ftRe = /(\d{2,3})\s*[^\d\s]{0,2}\s*cm\b/i;
        const m = ft.match(ftRe);
        if (m && m[1]) {
          const v = parseInt(m[1], 10);
          if (v >= 90 && v <= 300) {
            candidates.push({
              num: v,
              raw: "found-in-fullText",
              unit: "cm",
              score: 40,
              source: "fullText",
            });
          };
        };
      } catch (e) {
        /* ignore */
      };

      // 2) مرشحو combinedCandidates مع تسجيل نقاط
      const weightVal =
        typeof general !== "undefined" &&
        general &&
        toNum(general.weight_kg)
          ? toNum(general.weight_kg)
          : typeof weightParsed !== "undefined"
          ? toNum(weightParsed)
          : null;

      for (let i = 0; i < combinedCandidates.length; i++) {
        const c = combinedCandidates[i];
        if (!c) continue;
        const rawCtx = String(c.ctx || c.raw || c.value || "");
        let num = null;
        const m1 = rawCtx.match(/(\d{2,3})\s*[^\d\s]{0,2}\s*cm\b/i);
        if (m1 && m1[1]) num = parseInt(m1[1], 10);
        if (num === null)
          num =
            toNum(c.num) ||
            toNum(c.value) ||
            (rawCtx.match(/\d{2,3}/)
              ? parseInt(rawCtx.match(/\d{2,3}/)[0], 10)
              : null);
        if (num === null) continue;
        if (num < 90 || num > 300) continue;

        let score = 0;
        if (c.unit && String(c.unit).toLowerCase() === "cm") score += 20;
        if (/\bcm\b/i.test(rawCtx)) score += 12;
        if (labelRe.test(rawCtx)) score += 18;
        if (num >= 140 && num <= 200) score += 8;

        if (weightVal) {
          const b = bmiFor(num, weightVal);
          if (b !== null && b >= 12 && b <= 50) {
            score += 6;
            if (b >= 16 && b <= 40) score += 4;
            if (b >= 18 && b <= 35) score += 4;
          } else {
            score -= 6;
          };
        };

        candidates.push({
          num: Math.round(num),
          raw: rawCtx,
          unit: c.unit || "",
          idx: i,
          score: score,
          source: "combinedCandidates",
        });
      };

      // 3) اختيار أعلى مرشح بعد فلترة معقولة
      let viable = candidates.filter((x) => x.num >= 120 && x.num <= 230);
      if (!viable.length)
        viable = candidates.filter((x) => x.num >= 100 && x.num <= 250);
      if (!viable.length) {
        try {
          if (typeof general !== "undefined") {
            general.debug = general.debug || {};
            general.debug._height_candidates = candidates;
          };
        } catch (e) {}
        return null;
      };
      viable.sort((a, b) => b.score - a.score || a.idx - b.idx);
      const best = viable[0];

      // حفظ debug
      try {
        if (typeof general !== "undefined") {
          general.debug = general.debug || {};
          general.debug._height_candidates = candidates.slice(0, 50);
          general.debug._height_chosen_by_function = best;
        }
      } catch (e) {};

      const chosen = Math.round(best.num);

      // ***** هنا: نعدل general مباشرة حتى الكود اللاحق يستخدم الطول و BMI المصححين *****
      try {
        if (typeof general !== "undefined") {
          general.height_cm = chosen; // ضبط الطول
          // إذا وُجد وزن نحسب BMI ونضعه
          const w = toNum(general.weight_kg) || toNum(weightParsed);
          if (w) {
            const bmiCalc = bmiFor(chosen, w);
            if (bmiCalc !== null) {
              const bmiRounded = +bmiCalc.toFixed(1);
              // نستبدل فقط إذا كانت القراءة الحالية غير موجودة/غير معقولة.
              // لا نكسر قراءة crop صحيحة (مثل 30.6) بقيمة محسوبة من طول مشكوك فيه.
              const curB = toNum(general.BMI);
              if (curB === null || curB < 10 || curB > 60) {
                general.BMI = bmiRounded;
                general.debug._height_bmi_applied = {
                  weight: w,
                  height: chosen,
                  bmiRounded,
                };
              } else {
                general.debug._height_bmi_kept = {
                  current: curB,
                  candidate: bmiRounded,
                };
              };
            };
          };

          // اختياري: إعادة حساب SMM من القطاعات إن وُجدت
          const seg =
            general.segmental && general.segmental.lean
              ? general.segmental.lean
              : typeof leanParsed === "object"
              ? leanParsed
              : null;
          if (seg) {
            const keys = [
              "left_arm_lean_kg",
              "right_arm_lean_kg",
              "trunk_lean_kg",
              "left_leg_lean_kg",
              "right_leg_lean_kg",
            ];
            const parts = keys.map((k) => {
              const v = toNum(seg[k]);
              return v === null ? 0 : v;
            });
            // detect obvious OCR trunk error (مثل 114.6) and try /10 if trunk >> plausible portion of weight
            if (w && parts[2] > w * 0.5) {
              let trunk = parts[2];
              while (trunk > w * 0.5 && trunk > 1) trunk = trunk / 10;
              parts[2] = +trunk.toFixed(1);
            };
            const recomputed = +parts
              .reduce((a, b) => a + b, 0)
              .toFixed(1);
            // اعتمد recomputed فقط إذا معقول ولدينا قراءة صندوق مفقودة (لا نكسر قراءة crop صحيحة)
            const curSMM = toNum(general.SMM_kg);
            if (
              recomputed >= 12 &&
              recomputed <= 60 &&
              (curSMM === null || curSMM === 0) &&
              Math.abs((toNum(general.SMM_kg) || 0) - recomputed) > 0.5
            ) {
              general.SMM_kg = recomputed;
              general.debug._height_smm_applied = { parts, recomputed };
            } else {
              general.debug._height_smm_note = { parts, recomputed };
            };
          };
        };
      } catch (e) {
        try {
          if (typeof general !== "undefined")
            general.debug = general.debug || {};
          general.debug._height_apply_error = String(e);
        } catch (er) {};
      };
      return chosen;
    };

    // حساب PBF من fat_mass_kg و weight_kg كحل احتياطي
    function computePBFFromMass(weightKg, fatMassKg) {
      if (weightKg == null || fatMassKg == null) return null;
      const p = (fatMassKg / weightKg) * 100.0;
      if (!isFinite(p)) return null;
      if (p < 2 || p > 60) return null; // sanity
      return +p.toFixed(1);
    };

    // حساب BMI من الوزن والطول (cm)
    function computeBMIFromWeightAndHeight(weightKg, heightCm) {
      if (weightKg == null || heightCm == null) return null;
      const h = heightCm / 100;
      if (h <= 0) return null;
      const bmi = weightKg / (h * h);
      if (!isFinite(bmi)) return null;
      if (bmi < 6 || bmi > 70) return null;
      return +bmi.toFixed(1);
    };

    // الآن نطبق قواعد fallback ذكية دون تغيير أي إحداثيات:
    try {
      // current extracted
      let currentBMI = general.BMI;
      let currentPBF = general.PBF_percent;

      // --- PBF: compute from fat mass when available and use robust heuristics ---
      const pbfFromMass = computePBFFromMass(
        general.weight_kg,
        general.fat_mass_kg
      );

      // إذا كانت القيمة الملتقطة مفقودة أو صغيرة جداً (<12) أو تختلف كثيراً (>8) عن المحسوبة -> اعتمد المحسوبة
      if (pbfFromMass !== null) {
        if (currentPBF === null || currentPBF < 12) {
          currentPBF = pbfFromMass;
          // console.log(
          //   "PBF replaced by computePBFFromMass =>",
          //   currentPBF
          // );
        } else if (Math.abs(currentPBF - pbfFromMass) > 8) {
          // اختلاف كبير بين OCR و الحساب -> نثق بالحساب
          currentPBF = pbfFromMass;
          // console.log(
          //   "PBF overridden by mass-based heuristic =>",
          //   currentPBF
          // );
        };
      };

      if (currentBMI === null || currentBMI < 10 || currentBMI > 60) {
        const heightCm = findHeightCmInCombined();
        if (heightCm !== null && general.weight_kg != null) {
          const bmiFromHeight = computeBMIFromWeightAndHeight(
            general.weight_kg,
            heightCm
          );
          if (bmiFromHeight !== null) {
            currentBMI = bmiFromHeight;
          };
        };
      };

      if ((currentBMI === null || currentBMI < 10 || currentBMI > 60) && Array.isArray(fullRes && fullRes.data && fullRes.data.words)) {
        const near = findNumericNearBox(
          fullRes.data.words || [],
          BMI_ORIG,
          rawCanvas,
          8,
          60,
          3000
        );
        if (near !== null) currentBMI = +(+near).toFixed(1);
      };

      if ((currentPBF === null || currentPBF < 2.5) && (fullText || "")) {
        // البحث عن أول نسبة % بعد عبارة body fat/PBF
        const re = /(?:PBF|body fat|percent body fat)[^\d%]{0,12}([0-9]{1,2}[.,]?[0-9]{0,2})\s*%/i;
        const m = (fullText || "").match(re);
        if (m) {
          const val = normNum(m[1]);
          if (val !== null && val >= 2 && val <= 60)
            currentPBF = +val.toFixed(1);
        };
        // أو محاولة إيجاد رقم قرب PACKAGED label باستخدام findLabelNearby (إذا كانت الدالة متاحة أعلاه)
        if ((currentPBF === null || currentPBF < 2.5) && Array.isArray(fullRes && fullRes.data && fullRes.data.words)) {
          const tryNear = findNumericNearBox(
            fullRes.data.words || [],
            PBF_ORIG,
            rawCanvas,
            2,
            100,
            3000
          );
          if (tryNear !== null) currentPBF = +(+tryNear).toFixed(1);
        };
      };

      // املأ القيم النهائية في general (نحو الحفاظ على نوع البيانات)
      general.BMI =
        currentBMI !== null && currentBMI !== undefined
          ? +currentBMI.toFixed(1)
          : null;
      general.PBF_percent =
        currentPBF !== null && currentPBF !== undefined
          ? +currentPBF.toFixed(1)
          : null;
    } catch (e) {
      console.warn("fallback BMI/PBF error", e);
    }
    findHeightCmInCombined();

    // Prefer the exact label-anchored value whenever the small-crop pipeline
    // produced a missing or implausible read (plausibility windows per field).
    const locatedValues = {
      weight: located.weight && located.weight.value != null ? +located.weight.value : null,
      tbw: located.tbw && located.tbw.value != null ? +located.tbw.value : null,
      protein: located.protein && located.protein.value != null ? +located.protein.value : null,
      fat: located.fat && located.fat.value != null ? +located.fat.value : null,
      smm: located.smm && located.smm.value != null ? +located.smm.value : null,
      kcal: located.kcal && located.kcal.value != null ? +located.kcal.value : null,
      bmi: located.bmi && located.bmi.value != null ? +located.bmi.value : null,
      pbf: located.pbf && located.pbf.value != null ? +located.pbf.value : null,
    };
    const betterLocated = (cur, loc, key) => {
      if (loc === null) return cur;
      if (cur === null || cur === undefined) return loc;
      const [lo, hi] = FIELD_PLAUSIBLE[key] || [-Infinity, Infinity];
      const curOk = cur >= lo && cur <= hi;
      const locOk = loc >= lo && loc <= hi;
      return !curOk && locOk ? loc : cur;
    };
    general.weight_kg = betterLocated(general.weight_kg, locatedValues.weight, "weight");
    general.water = betterLocated(general.water, locatedValues.tbw, "water");
    general.protein_kg = betterLocated(general.protein_kg, locatedValues.protein, "protein");
    general.fat_mass_kg = betterLocated(general.fat_mass_kg, locatedValues.fat, "fat");
    general.SMM_kg = betterLocated(general.SMM_kg, locatedValues.smm, "smm");
    general.BMI = betterLocated(general.BMI, locatedValues.bmi, "bmi");
    general.PBF_percent = betterLocated(general.PBF_percent, locatedValues.pbf, "pbf");
    if (locatedValues.kcal !== null) {
      const curK = kcalRes && kcalRes.value != null ? +kcalRes.value : null;
      kcalRes = { ...(kcalRes || {}), value: betterLocated(curK, locatedValues.kcal, "kcal") };
    }

    weight.innerHTML = general.weight_kg;
    bmi.innerHTML = general.BMI;
    pbf.innerHTML = general.PBF_percent;
    smm.innerHTML = general.SMM_kg;
    kcal.innerHTML = kcalRes.value;
    tbw.innerHTML = general.water;
    bodyFat.innerHTML = general.fat_mass_kg;
    protein.innerHTML = general.protein_kg;

    const max = 100;

    // Model 3D
    // const weight3D = document.querySelector(".weight-3d");
    // const rightArmLean3D = document.querySelector(".right-arm-lean-3d");
    // const leftArmLean3D = document.querySelector(".left-arm-lean-3d");
    // const rightLegLean3D = document.querySelector(".right-leg-lean-3d");
    // const leftLegLean3D = document.querySelector(".left-leg-lean-3d");

    // weight3D.innerHTML = ((general.weight_kg / max) * 100).toFixed(1) + "%";

    // leftArmLean3D.innerHTML = ((leanParsed.left_arm_lean_kg / max) * 100).toFixed(1) + "%";
    // rightArmLean3D.innerHTML = ((leanParsed.right_arm_lean_kg / max) * 100).toFixed(1) + "%";
    // rightLegLean3D.innerHTML = ((leanParsed.right_leg_lean_kg / max) * 100).toFixed(1) + "%";
    // leftLegLean3D.innerHTML = ((leanParsed.left_leg_lean_kg / max) * 100).toFixed(1) + "%";

    leftArmLean.innerHTML = leanParsed.left_arm_lean_kg;
    rightArmLean.innerHTML = leanParsed.right_arm_lean_kg;
    rightLegLean.innerHTML = leanParsed.right_leg_lean_kg;
    leftLegLean.innerHTML = leanParsed.left_leg_lean_kg;

    leftArmFat.innerHTML = fatParsedLikeLean.left_arm_fat_kg;
    rightArmFat.innerHTML = fatParsedLikeLean.right_arm_fat_kg;
    rightLegFat.innerHTML = fatParsedLikeLean.right_leg_fat_kg;
    leftLegFat.innerHTML = fatParsedLikeLean.left_leg_fat_kg;

    const lineages = {
      weight: general.weight_kg,
      BMI: general.BMI,
      PBF: general.PBF_percent,
      SMM: general.SMM_kg,
      kcal: kcalRes.value,
      water: general.water,
      fat_mass: general.fat_mass_kg,
      protein: general.protein_kg,

      left_arm_lean: leanParsed.left_arm_lean_kg,
      right_arm_lean: leanParsed.right_arm_lean_kg,
      right_leg_lean: leanParsed.right_leg_lean_kg,
      left_leg_lean: leanParsed.left_leg_lean_kg,

      left_arm_fat: fatParsedLikeLean.left_arm_fat_kg,
      right_arm_fat: fatParsedLikeLean.right_arm_fat_kg,
      right_leg_fat: fatParsedLikeLean.right_leg_fat_kg,
      left_leg_fat: fatParsedLikeLean.left_leg_fat_kg
    };

    console.log(fatParsedLikeLean.right_leg_fat_kg);

    async function sendAllLineages(name, lineage) {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      try {
        const res = await fetch('/lineage', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token || ''
          },
          body: JSON.stringify({name: name, lineage: lineage})
        });
        if (!res.ok) throw new Error(`Server responded ${res.status}`);
        const json = await res.json();
        console.log('Saved response:', json);
      } catch (err) {
        console.error('Send failed:', err);
      };
    };

    (async () => {
      for (const [key, value] of Object.entries(lineages)) {
        await sendAllLineages(key, value);
      }
    })();

    const final = {
      weight_kg: general.weight_kg,
      BMI: general.BMI,
      PBF_percent: general.PBF_percent,
      SMM_kg: general.SMM_kg,
      KCAL_ORIG: kcalRes.value,
      water: general.water,
      fat_mass_kg: general.fat_mass_kg,
      protein_kg: general.protein_kg,
      segmental: {
        lean: {
          left_arm_lean_kg: leanParsed.left_arm_lean_kg,
          right_arm_lean_kg: leanParsed.right_arm_lean_kg,
          trunk_lean_kg: leanParsed.trunk_lean_kg,
          left_leg_lean_kg: leanParsed.left_leg_lean_kg,
          right_leg_lean_kg: leanParsed.right_leg_lean_kg,
          coords: leanParsed.coords || {},
        },
        fat: {
          left_arm_fat_kg: fatParsedLikeLean.left_arm_fat_kg,
          right_arm_fat_kg: fatParsedLikeLean.right_arm_fat_kg,
          trunk_fat_kg: fatParsedLikeLean.trunk_fat_kg,
          left_leg_fat_kg: fatParsedLikeLean.left_leg_fat_kg,
          right_leg_fat_kg: fatParsedLikeLean.right_leg_fat_kg,
        },
      },
      debug: {
        tbw_raw_text_from_expand: tbwRes.raw_text,
        tbw_from_expand: tbwRes.value,
        tbw_fallback: tbwFallback,
        protein_raw_text_from_expand: proteinRes.raw_text,
        protein_from_expand: proteinRes.value,
        protein_fallback: proteinFallback,
        smm_raw_text_from_expand: smmRes.raw_text,
        smm_from_expand: smmRes.value,
        smm_fallback: smmFallback,
        weight_raw_text_from_expand: weightRes.raw_text,
        weight_from_expand: weightRes.value,
        weight_fallback: weightFallback,
        bmi_raw_text_from_expand: bmiRes.raw_text,
        bmi_from_expand: bmiRes.value,
        fat_crop_raw_text: fatTextRaw,
        fat_from_crop: parseNumberInRange(fatTextRaw, 0.1, 500) || null,
        fatmass_raw_text_from_expand: fatMassRes.raw_text,
        fatmass_from_expand: fatMassRes.value,
        kcal_raw_text_from_expand: kcalRes.raw_text,
        kcal_from_expand: kcalRes.value,
        small_segment_raws: {
          leftArm: leftArmSmall,
          rightArm: rightArmSmall,
          leftLeg: leftLegSmall,
          rightLeg: rightLegSmall,
        },
        fat_layout_parsed: fatLayout,
        parsed_full_excerpt: (fullText || "").slice(0, 2000),
        full_words_count: (fullRes.data.words || []).length,
        located: Object.fromEntries(
          Object.entries(located).map(([k, v]) => [
            k,
            v ? { value: v.value, bbox: v.bbox, kind: v.kind } : null,
          ])
        ),
        located_applied_values: locatedValues,
      },
    };
    statusEl.textContent = "انتهى — راجع JSON وحقول debug (F12 للكونسول)";
    window.__finalDebug = final;
    const img = new FormData();
    img.append('img', currentFile);
    fetch('/save-img', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: img
    }).then(response => {
      if (!response.ok) {
        console.error('Failed to update status');
      };
    }).catch(error => {
      console.error('Error:', error);
    });
    // keep the annotated paper (sheet slope + boxes) visible so the user can
    // verify the detection after OCR completes
    btnShowImgInBody.classList.add("show-button-img");
    statusEl.textContent = "انتهى — شاهد الصورة المعروضة (حدود الورقة والانحدار).";
  } catch (err) {
    console.error(err);
    statusEl.textContent = "خطأ: " + (err.message || err);
    alert("حدث خطأ: " + (err.message || err));
  }
}
