<?php
$contentsPage = site()->page('contents');

if (!$contentsPage || !$page->is_featured_on_contents()->toBool()) {
  return;
}

$contentsMode = kirby()->session()->get('contents_mode');
if (!in_array($contentsMode, ['guided', 'free'], true)) {
  $contentsMode = 'guided';
}

$nextContentsPage = null;
$contentsOrder = $contentsPage->contents_order()->toStructure();
$orderedPages = [];

foreach ($contentsOrder as $item) {
  $linkedPage = $item->page()->toPage();

  if ($linkedPage) {
    $orderedPages[] = $linkedPage;
  }
}

$currentPosition = array_search($page->id(), array_map(fn ($item) => $item->id(), $orderedPages));

if ($currentPosition !== false && isset($orderedPages[$currentPosition + 1])) {
  $nextContentsPage = $orderedPages[$currentPosition + 1];
}

$prevContentsPage = null;
if ($currentPosition !== false && $currentPosition > 0 && isset($orderedPages[$currentPosition - 1])) {
  $prevContentsPage = $orderedPages[$currentPosition - 1];
}
?>

<?php if ($contentsMode === 'guided' && ($prevContentsPage || $nextContentsPage)): ?>
  <p class="contents-nav">
    <?php if ($prevContentsPage): ?>
      <a class="btn contents-btn contents-back" href="<?= $prevContentsPage->url() ?>">Back</a>
    <?php else: ?>
      <a class="btn contents-btn contents-back btn--secondary" href="<?= $contentsPage->url() ?>">Back to contents</a>
    <?php endif ?>

    <?php if ($nextContentsPage): ?>
      <a class="btn contents-btn contents-next" href="<?= $nextContentsPage->url() ?>">Next</a>
    <?php endif ?>
  </p>
<?php elseif ($contentsMode === 'free'): ?>
  <p class="contents-next">
    <a class="btn contents-btn" href="<?= $contentsPage->url() ?>">Back to contents page</a>
  </p>
<?php endif ?>
