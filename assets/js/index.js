// Lightbox
Array.from(document.querySelectorAll("[data-lightbox]")).forEach((element) => {
  element.onclick = (e) => {
    e.preventDefault();
    basicLightbox.create(`<img src="${element.href}">`).show();
  };
});

// Page nav: highlight the link for the section currently in view
document.addEventListener("DOMContentLoaded", () => {
  const links = Array.from(document.querySelectorAll("[data-page-nav-link]"));
  if (!links.length) return;

  const sections = links
    .map((link) =>
      document.getElementById(link.getAttribute("data-page-nav-link")),
    )
    .filter(Boolean);

  if (!sections.length) return;

  const setActive = (slug) => {
    links.forEach((link) => {
      link.classList.toggle(
        "is-active",
        link.getAttribute("data-page-nav-link") === slug,
      );
    });
  };

  const observer = new IntersectionObserver(
    (entries) => {
      const visible = entries.filter((entry) => entry.isIntersecting);
      if (visible.length) {
        setActive(visible[0].target.id);
      }
    },
    { rootMargin: "-45% 0px -50% 0px" },
  );

  sections.forEach((section) => observer.observe(section));
});

// Homepage hero randomize
document.addEventListener("DOMContentLoaded", () => {
  const data = window.HOMEPAGE_FEATURES || null;
  if (!data) return;

  const hero = document.getElementById("homepage-hero");
  const btn = document.getElementById("hero-randomize");
  const bgWrapper = hero && hero.querySelector(".hero-bg");
  const bgLayers = bgWrapper
    ? Array.from(bgWrapper.querySelectorAll(".hero-bg-layer"))
    : [];

  let currentSrc = bgLayers.length
    ? bgLayers.find(
        (l) =>
          parseFloat(l.style.opacity || window.getComputedStyle(l).opacity) >
          0.5,
      )?.style.backgroundImage
    : null;

  function pickNext(arr) {
    if (!arr.length) return null;
    if (arr.length === 1) return arr[0];
    let choice;
    do {
      choice = arr[Math.floor(Math.random() * arr.length)];
    } while (`url('${choice.src}')` === currentSrc);
    return choice;
  }

  function applyVariant(bg) {
    if (!hero || !bg) return;

    // crossfade background using the two layers
    if (bgLayers.length >= 2) {
      const [a, b] = bgLayers;
      const active =
        parseFloat(a.style.opacity || window.getComputedStyle(a).opacity) > 0.5
          ? a
          : b;
      const hidden = active === a ? b : a;
      hidden.style.backgroundImage = `url('${bg.src}')`;
      currentSrc = hidden.style.backgroundImage;
      // trigger crossfade
      hidden.style.opacity = 0;
      requestAnimationFrame(() => {
        hidden.style.opacity = 1;
        active.style.opacity = 0;
      });
    } else {
      hero.style.backgroundImage = `url('${bg.src}')`;
      currentSrc = hero.style.backgroundImage;
    }
  }

  // initial data already rendered server-side; clicking randomize picks new
  btn &&
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const bg = pickNext(data.collages);
      applyVariant(bg);
    });

  // clicking anywhere on the hero (except randomize) scrolls down to the text
  hero &&
    hero.addEventListener("click", (e) => {
      if (e.target && e.target.closest && e.target.closest("#hero-randomize"))
        return;
      e.preventDefault();
      const target = document.getElementById("homepage-text");
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
      } else {
        window.scrollTo({ top: window.innerHeight, behavior: "smooth" });
      }
    });
});
