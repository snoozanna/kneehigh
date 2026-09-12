<?php
snippet('header');

if (kirby()->request()->is('post') && kirby()->request()->get('contents_mode') !== null) {
  $postedMode = kirby()->request()->get('contents_mode');

  if (in_array($postedMode, ['guided', 'free'], true)) {
    kirby()->session()->set('contents_mode', $postedMode);
  }
}

$mode = kirby()->session()->get('contents_mode');
if (!in_array($mode, ['guided', 'free'], true)) {
  $mode = 'guided';
  kirby()->session()->set('contents_mode', $mode);
}

$contentsOrder = $page->contents_order()->toStructure();
$orderedPages = [];

foreach ($contentsOrder as $item) {
  $linkedPage = $item->page()->toPage();

  if ($linkedPage) {
    $orderedPages[] = $linkedPage;
  }
}

if ($mode === 'free' && !empty($orderedPages)) {
  shuffle($orderedPages);
}

$featuredPages = site()->index()->filterBy('is_featured_on_contents', true);
?>

<?php snippet('intro') ?>

<form method="post" class="contents-mode-switcher" id="contents-mode-form">
  <input type="hidden" name="contents_mode" id="contents-mode-input" value="<?= $mode ?>">

  <label class="contents-toggle" for="contents-toggle">
    <span class="contents-toggle-text">TELL ME WHERE TO GO</span>
    <input
      type="checkbox"
      id="contents-toggle"
      <?= $mode === 'guided' ? 'checked' : '' ?>
      aria-checked="<?= $mode === 'guided' ? 'true' : 'false' ?>"
    >
    <span class="contents-toggle-switch" aria-hidden="true"></span>
  </label>

  <noscript>
    <button type="submit">Save preference</button>
  </noscript>
</form>

<script>
  (function(){
    var toggle = document.getElementById('contents-toggle');
    var input = document.getElementById('contents-mode-input');
    var form = document.getElementById('contents-mode-form');

    if (!toggle || !input || !form) return;

    function postMode(mode){
      // Update hidden input for progressive enhancement
      input.value = mode;

      // Try a fetch POST so the change happens immediately and then reload
      try{
        var fd = new FormData();
        fd.append('contents_mode', mode);
        fetch(window.location.href, { method: 'POST', body: fd, credentials: 'same-origin' })
          .then(function(response){
            if (response && response.ok) {
              // reload so the new ordering is visible immediately
              window.location.reload();
            } else {
              form.submit();
            }
          }).catch(function(){
            form.submit();
          });
      } catch (e) {
        // fallback to traditional submit
        form.submit();
      }
    }

    toggle.addEventListener('change', function(){
      var mode = this.checked ? 'guided' : 'free';
      postMode(mode);
    });
  })();
</script>
<div class="contents-list">
<?php if (!empty($orderedPages)): ?>
  <ul class="contents-grid">
    <?php foreach ($orderedPages as $i => $item): $index = $i + 1; ?>
      <li class="contents-item">
        <a href="<?= $item->url() ?>">
          <span class="contents-content">
            <span class="contents-title"><?= $item->title()->esc() ?></span>
            <?php if ($mode === 'guided'): ?>
              <span class="contents-badge"><?= $index ?></span>
            <?php endif ?>
          </span>
        </a>
      </li>
    <?php endforeach ?>
  </ul>
<?php elseif ($featuredPages->isNotEmpty()): ?>
  <p>Featured pages are available, but no contents order has been set yet.</p>
  <ul class="contents-grid">
    <?php foreach ($featuredPages as $i => $item): $index = $i + 1; ?>
      <li class="contents-item">
        <a href="<?= $item->url() ?>">
          <span class="contents-content">
            <span class="contents-title"><?= $item->title()->esc() ?></span>
          </span>
        </a>
      </li>
    <?php endforeach ?>
  </ul>
<?php else: ?>
  <p>No pages are currently marked as featured on Contents.</p>
<?php endif ?>
</div>
<?php snippet('footer') ?>
