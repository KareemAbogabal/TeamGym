import puppeteer from "puppeteer";
const EXE = "C:/Program Files/Google/Chrome/Application/chrome.exe";
const COOKIE = "0e210bb14b1b66f9592b27f66a615fb1040405fc|eyJpdiI6ImZMdzgrMzZZbVQ2czh0aFFLcWYvUGc9PSIsInZhbHVlIjoiYm1pVFFyRHdUY0FqYXVUMUhuSy9YZz09IiwibWFjIjoiNDZmNWRhZmUwZGE1NTNhMGRjNDFlYmE3MTg2Mzk5NjAwY2Y3YTMxMTkyMzRmM2YyYmU4ZmY1MDNkN2E4MDNjYiIsInRhZyI6IiJ9";

const browser = await puppeteer.launch({
  executablePath: EXE,
  headless: "new",
  args: ["--no-sandbox", "--disable-setuid-sandbox", "--disable-gpu", "--no-first-run", "--disable-extensions"],
});
const page = await browser.newPage();
await page.setCookie({
  name: "login_client",
  value: COOKIE,
  domain: "127.0.0.1",
  path: "/",
});
await page.goto("http://127.0.0.1:8000/health", { waitUntil: "domcontentloaded", timeout: 60000 });
await new Promise((r) => setTimeout(r, 4000));
const info = await page.evaluate(() => ({
  title: document.title,
  hasUpload: !!document.querySelector(".inbodyUpload"),
  hasTesseract: typeof window.Tesseract !== "undefined",
  tesseractVersion: (window.Tesseract && window.Tesseract.version) || null,
  bodyText: (document.body.innerText || "").slice(0, 200),
}));
console.log(JSON.stringify(info, null, 2));
await browser.close();
