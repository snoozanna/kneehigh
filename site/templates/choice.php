<?php
snippet('header');

if (kirby()->request()->is('post') && kirby()->request()->get('choice_mode') !== null) {
  $postedMode = kirby()->request()->get('choice_mode');

  if (in_array($postedMode, ['guided', 'free'], true)) {
    kirby()->session()->set('choice_mode', $postedMode);
  }
}

$mode = kirby()->session()->get('choice_mode');
if (!in_array($mode, ['guided', 'free'], true)) {
  $mode = 'guided';
  kirby()->session()->set('choice_mode', $mode);
}

$choiceOrder = $page->choice_order()->toStructure();
$orderedPages = [];

foreach ($choiceOrder as $item) {
  $linkedPage = $item->page()->toPage();

  if ($linkedPage) {
    $orderedPages[] = $linkedPage;
  }
}

if ($mode === 'free' && !empty($orderedPages)) {
  shuffle($orderedPages);
}

$featuredPages = site()->index()->filterBy('is_featured_on_choice', true);
?>

<?php snippet('intro') ?>

<form method="post" class="choice-mode-switcher" id="choice-mode-form">
  <input type="hidden" name="choice_mode" id="choice-mode-input" value="<?= $mode ?>">

  <label class="choice-toggle" for="choice-toggle">
    <span class="choice-toggle-text">TELL ME WHERE TO GO</span>
    <input
      type="checkbox"
      id="choice-toggle"
      <?= $mode === 'guided' ? 'checked' : '' ?>
      aria-checked="<?= $mode === 'guided' ? 'true' : 'false' ?>"
    >
    <span class="choice-toggle-switch" aria-hidden="true"></span>
  </label>

  <noscript>
    <button type="submit">Save preference</button>
  </noscript>
</form>

<script>
  (function(){
    var toggle = document.getElementById('choice-toggle');
    var input = document.getElementById('choice-mode-input');
    var form = document.getElementById('choice-mode-form');

    if (!toggle || !input || !form) return;

    function postMode(mode){
      // Update hidden input for progressive enhancement
      input.value = mode;

      // Try a fetch POST so the change happens immediately and then reload
      try{
        var fd = new FormData();
        fd.append('choice_mode', mode);
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
<div class="choice-list">
<?php if (!empty($orderedPages)): ?>
  <ul class="choice-grid">
    <?php foreach ($orderedPages as $i => $item): $index = $i + 1; ?>
      <li class="choice-item">
        <a href="<?= $item->url() ?>">
          <span class="choice-content">
            <span class="choice-title"><?= $item->title()->esc() ?></span>
            <?php if ($mode === 'guided'): ?>
              <span class="choice-badge"><?= $index ?></span>
            <?php endif ?>
          </span>
        </a>
      </li>
    <?php endforeach ?>
  </ul>
<?php elseif ($featuredPages->isNotEmpty()): ?>
  <p>Featured pages are available, but no choice order has been set yet.</p>
  <ul class="choice-grid">
    <?php foreach ($featuredPages as $i => $item): $index = $i + 1; ?>
      <li class="choice-item">
        <a href="<?= $item->url() ?>">
          <span class="choice-content">
            <span class="choice-title"><?= $item->title()->esc() ?></span>
          </span>
        </a>
      </li>
    <?php endforeach ?>
  </ul>
<?php else: ?>
  <p>No pages are currently marked as featured on Choice.</p>
<?php endif ?>
</div>
<?php snippet('footer') ?>
