<?php
$choicePage = site()->page('choice');

if (!$choicePage || !$page->is_featured_on_choice()->toBool()) {
  return;
}

$choiceMode = kirby()->session()->get('choice_mode');
if (!in_array($choiceMode, ['guided', 'free'], true)) {
  $choiceMode = 'guided';
}

$nextChoicePage = null;
$choiceOrder = $choicePage->choice_order()->toStructure();
$orderedPages = [];

foreach ($choiceOrder as $item) {
  $linkedPage = $item->page()->toPage();

  if ($linkedPage) {
    $orderedPages[] = $linkedPage;
  }
}

$currentPosition = array_search($page->id(), array_map(fn ($item) => $item->id(), $orderedPages));

if ($currentPosition !== false && isset($orderedPages[$currentPosition + 1])) {
  $nextChoicePage = $orderedPages[$currentPosition + 1];
}
?>

<?php if ($choiceMode === 'guided' && $nextChoicePage): ?>
  <p class="choice-next">
    <a href="<?= $nextChoicePage->url() ?>">Next</a>
  </p>
<?php elseif ($choiceMode === 'free'): ?>
  <p class="choice-next">
    <a href="<?= $choicePage->url() ?>">Back to choice page</a>
  </p>
<?php endif ?>
