// filters.js
document.querySelectorAll("[data-filter]").forEach((btn) => {
  btn.addEventListener("click", () => {
    const value = btn.dataset.filter;
    document.querySelectorAll(".card").forEach((card) => {
      card.style.display =
        value === "all" || card.dataset.format === value ? "" : "none";
    });
  });
});
