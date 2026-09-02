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

<form method="post" class="choice-mode-switcher">
  <label>
    <input type="radio" name="choice_mode" value="guided" <?= $mode === 'guided' ? 'checked' : '' ?>>
    TELL ME WHERE TO GO
  </label>

  <label>
    <input type="radio" name="choice_mode" value="free" <?= $mode === 'free' ? 'checked' : '' ?>>
    I WANT TO WORK IT OUT MYSELF
  </label>

  <button type="submit">Save preference</button>
</form>

<?php if (!empty($orderedPages)): ?>
  <ol>
    <?php foreach ($orderedPages as $item): ?>
      <li>
        <a href="<?= $item->url() ?>"><?= $item->title()->esc() ?></a>
      </li>
    <?php endforeach ?>
  </ol>
<?php elseif ($featuredPages->isNotEmpty()): ?>
  <p>Featured pages are available, but no choice order has been set yet.</p>
  <ul>
    <?php foreach ($featuredPages as $item): ?>
      <li><a href="<?= $item->url() ?>"><?= $item->title()->esc() ?></a></li>
    <?php endforeach ?>
  </ul>
<?php else: ?>
  <p>No pages are currently marked as featured on Choice.</p>
<?php endif ?>

<?php snippet('footer') ?>
