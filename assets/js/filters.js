// filters.js
document.querySelectorAll('[data-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.filter;
      document.querySelectorAll('.card').forEach(card => {
        card.style.display = (cat === 'all' || card.dataset.category === cat) ? '' : 'none';
      });
    });
  });