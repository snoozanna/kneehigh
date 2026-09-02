// Lightbox
Array.from(document.querySelectorAll("[data-lightbox]")).forEach((element) => {
  element.onclick = (e) => {
    e.preventDefault();
    basicLightbox.create(`<img src="${element.href}">`).show();
  };
});

// Homepage hero randomize
document.addEventListener("DOMContentLoaded", () => {
  const data = window.HOMEPAGE_FEATURES || null;
  if (!data) return;

  const hero = document.getElementById("homepage-hero");
  const fgContainer = hero && hero.querySelector(".hero-foreground");
  const btn = document.getElementById("hero-randomize");
  const bgWrapper = hero && hero.querySelector(".hero-bg");
  const bgLayers = bgWrapper
    ? Array.from(bgWrapper.querySelectorAll(".hero-bg-layer"))
    : [];

  // hide header initially while hero is visible
  document.body.classList.add("hero-open");

  function onScrollReveal() {
    if (window.scrollY > 20) {
      document.body.classList.remove("hero-open");
      window.removeEventListener("scroll", onScrollReveal);
    }
  }
  window.addEventListener("scroll", onScrollReveal, { passive: true });

  function pickRandom(arr, n = 1) {
    const copy = arr.slice();
    for (let i = copy.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [copy[i], copy[j]] = [copy[j], copy[i]];
    }
    return copy.slice(0, n);
  }

  function applyVariant(bg, fgs) {
    if (!hero) return;

    // crossfade background using the two layers
    if (bgLayers.length >= 2) {
      const [a, b] = bgLayers;
      const active =
        parseFloat(a.style.opacity || window.getComputedStyle(a).opacity) > 0.5
          ? a
          : b;
      const hidden = active === a ? b : a;
      hidden.style.backgroundImage = bg ? `url('${bg.src}')` : "";
      // trigger crossfade
      hidden.style.opacity = 0;
      requestAnimationFrame(() => {
        hidden.style.opacity = 1;
        active.style.opacity = 0;
      });
    } else {
      hero.style.backgroundImage = bg ? `url('${bg.src}')` : "";
    }

    // ensure 3 foreground anchors
    for (let i = 1; i <= 3; i++) {
      const cls = `fg-pos-${i}`;
      let anchor = fgContainer.querySelector(`.${cls}`);
      const fg = fgs[i - 1];

      if (!anchor) {
        anchor = document.createElement("a");
        anchor.className = `hero-foreground-item ${cls}`;
        const img = document.createElement("img");
        anchor.appendChild(img);
        fgContainer.appendChild(anchor);
      }

      const img = anchor.querySelector("img");
      if (fg) {
        anchor.href = fg.url || "#";
        img.src = fg.src;
        img.alt = fg.title || "";
        anchor.style.opacity = 0;
        setTimeout(() => {
          anchor.style.opacity = 1;
        }, 40);
      } else {
        anchor.style.opacity = 0;
      }
    }
  }

  // initial data already rendered server-side; clicking randomize picks new
  btn &&
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const bg = pickRandom(data.backgrounds, 1)[0] || null;
      const fgs = pickRandom(data.foregrounds, 3);
      applyVariant(bg, fgs);
    });

  // clicking anywhere on the hero (except randomize) scrolls down to the text
  hero &&
    hero.addEventListener("click", (e) => {
      if (e.target && e.target.closest && e.target.closest("#hero-randomize"))
        return;
      e.preventDefault();
      document.body.classList.remove("hero-open");
      const target = document.getElementById("homepage-text");
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
      } else {
        window.scrollTo({ top: window.innerHeight, behavior: "smooth" });
      }
    });
});
