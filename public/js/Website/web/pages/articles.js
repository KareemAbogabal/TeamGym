let questions = document.querySelectorAll('.item');
let toggle = document.querySelectorAll('.toggle');
let navMove = document.querySelector("nav");
let btnSec = document.querySelectorAll(".btn-sec");
let section2 = document.querySelector(".section-2");

let reader = document.getElementById("article-reader");
let readerClose = document.getElementById("reader-close");
let readerArticles = document.querySelectorAll(".reader-article");
let articleCards = document.querySelectorAll(".article, .card[data-article]");
let articleLinks = document.querySelectorAll('a[href*="#article-"]:not([data-article])');

function openArticle(slug) {
  if (!slug) return;
  const target = document.getElementById("article-" + slug);
  if (!target) return;

  readerArticles.forEach((item) => {
    item.hidden = true;
  });
  target.hidden = false;
  reader.hidden = false;

  if (history.pushState) {
    history.pushState(null, "", "#article-" + slug);
  }

  if (articleSEO.items[slug]) {
    const seo = articleSEO.items[slug];
    document.title = seo.title;

    const metaDesc = document.querySelector('meta[name="description"]');
    if (metaDesc) metaDesc.setAttribute("content", seo.description);

    const canonical = document.querySelector('link[rel="canonical"]');
    if (canonical) canonical.setAttribute("href", seo.url);

    const ogTitle = document.querySelector('meta[property="og:title"]');
    if (ogTitle) ogTitle.setAttribute("content", seo.title);
    const ogDesc = document.querySelector('meta[property="og:description"]');
    if (ogDesc) ogDesc.setAttribute("content", seo.description);
    const ogImage = document.querySelector('meta[property="og:image"]');
    if (ogImage) ogImage.setAttribute("content", seo.image);
    const ogUrl = document.querySelector('meta[property="og:url"]');
    if (ogUrl) ogUrl.setAttribute("content", seo.url);
    const ogType = document.querySelector('meta[property="og:type"]');
    if (ogType) ogType.setAttribute("content", "article");

    const twTitle = document.querySelector('meta[name="twitter:title"]');
    if (twTitle) twTitle.setAttribute("content", seo.title);
    const twDesc = document.querySelector('meta[name="twitter:description"]');
    if (twDesc) twDesc.setAttribute("content", seo.description);
    const twImage = document.querySelector('meta[name="twitter:image"]');
    if (twImage) twImage.setAttribute("content", seo.image);

    const jsonLd = document.getElementById("article-jsonld");
    if (jsonLd) {
      jsonLd.textContent = JSON.stringify({
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": seo.title.replace(/^Team Gym \| /, ""),
        "description": seo.description,
        "image": seo.image,
        "url": seo.url,
        "datePublished": seo.datePublished,
        "inLanguage": seo.inLanguage,
        "author": { "@type": "Organization", "name": "Team Gym" },
        "publisher": {
          "@type": "Organization",
          "name": "Team Gym",
          "logo": { "@type": "ImageObject", "url": window.location.origin + "/images/header/Team-Gym.png" }
        }
      });
    }
  }

  reader.scrollIntoView({ behavior: "smooth", block: "start" });
}

function closeArticle() {
  reader.hidden = true;
  readerArticles.forEach((item) => {
    item.hidden = true;
  });

  if (history.replaceState) {
    history.replaceState(null, "", articleSEO.defaultUrl);
  }

  document.title = articleSEO.defaultTitle;
  const metaDesc = document.querySelector('meta[name="description"]');
  if (metaDesc) metaDesc.setAttribute("content", articleSEO.defaultDescription);
  const canonical = document.querySelector('link[rel="canonical"]');
  if (canonical) canonical.setAttribute("href", articleSEO.defaultUrl);
  const ogType = document.querySelector('meta[property="og:type"]');
  if (ogType) ogType.setAttribute("content", "website");
  const ogUrl = document.querySelector('meta[property="og:url"]');
  if (ogUrl) ogUrl.setAttribute("content", articleSEO.defaultUrl);

  const jsonLd = document.getElementById("article-jsonld");
  if (jsonLd) jsonLd.textContent = "";

  section2.scrollIntoView({ behavior: "smooth", block: "start" });
}

function slugFromHash() {
  const match = window.location.hash.match(/^#article-(.+)$/);
  return match ? match[1] : null;
}

document.addEventListener("DOMContentLoaded", function () {
  articleCards.forEach((card) => {
    card.addEventListener("click", (event) => {
      const slug = card.getAttribute("data-article");
      if (slug) {
        event.preventDefault();
        openArticle(slug);
      }
    });
  });

  articleLinks.forEach((link) => {
    link.addEventListener("click", (event) => {
      const slug = slugFromHashFor(link.getAttribute("href"));
      if (slug) {
        event.preventDefault();
        openArticle(slug);
      }
    });
  });

  if (readerClose) {
    readerClose.addEventListener("click", closeArticle);
  }

  window.addEventListener("hashchange", () => {
    const slug = slugFromHash();
    if (slug) {
      openArticle(slug);
    } else if (!window.location.hash) {
      closeArticle();
    }
  });

  const initial = slugFromHash();
  if (initial) {
    openArticle(initial);
  }

  toggle.forEach((item, index) => {
    item.addEventListener("click", () => {
      if (questions[index].classList.contains("show-item")) {
        questions[index].classList.remove("show-item");
        item.textContent = "+";
      } else {
        questions[index].classList.add("show-item");
        item.textContent = "−";
      }
    });
  });

  window.onscroll = () => {
    if (navMove && btnSec.length) {
      if (window.scrollY === 0) {
        navMove.classList.remove("blur-nav");
        btnSec[0].classList.add("active");
        btnSec[1].classList.remove("active");
      } else {
        navMove.classList.add("blur-nav");
      }
    }
    if (section2 && btnSec.length && window.scrollY >= section2.offsetTop - 300) {
      btnSec[0].classList.remove("active");
      btnSec[1].classList.add("active");
    }
  };
});

function slugFromHashFor(href) {
  const match = String(href).match(/#article-(.+)$/);
  return match ? match[1] : null;
}
