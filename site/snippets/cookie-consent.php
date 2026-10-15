<div id="cookie-banner" role="region" aria-label="Cookie consent" hidden>
  <p>
    We use Google Analytics to understand how visitors
    use this website. Analytics cookies are optional.
  </p>

  <button type="button" id="accept-analytics">
    Accept analytics
  </button>

  <button type="button" id="reject-analytics">
    Reject analytics
  </button>
</div>

<button type="button" id="cookie-settings">
  Cookie settings
</button>

<script>
(function () {
  const key = 'analytics-consent';
  const banner = document.getElementById('cookie-banner');
  const settings = document.getElementById('cookie-settings');

  function loadAnalytics() {
    if (window.analyticsLoaded) return;
    window.analyticsLoaded = true;

    const id = 'G-53G3EFBNQ8';

    const script = document.createElement('script');
    script.async = true;
    script.src =
      'https://www.googletagmanager.com/gtag/js?id=' + id;
    document.head.appendChild(script);

    window.dataLayer = window.dataLayer || [];
    window.gtag = function () {
      window.dataLayer.push(arguments);
    };

    window.gtag('js', new Date());
    window.gtag('config', id);
  }

  function applyConsent() {
    const choice = localStorage.getItem(key);

    banner.hidden = choice !== null;
    settings.hidden = false;

    if (choice === 'accepted') {
      loadAnalytics();
    }
  }

  document.getElementById('accept-analytics')
    .addEventListener('click', function () {
      localStorage.setItem(key, 'accepted');
      applyConsent();
    });

  document.getElementById('reject-analytics')
    .addEventListener('click', function () {
      localStorage.setItem(key, 'rejected');
      applyConsent();
    });

  settings.addEventListener('click', function () {
    banner.hidden = false;
  });

  applyConsent();
})();
</script>
