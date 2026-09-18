<?php
/*
  Builds a sticky in-page navigation from the heading blocks
  used in the page's layout field, so long pages (e.g. devising)
  get an always-visible menu of anchor links to jump between sections.
*/

use Kirby\Toolkit\Str;

if (!$page->show_page_nav()->toBool()) {
  return;
}

$anchors = [];

foreach ($page->layout()->toLayouts() as $layout) {
  foreach ($layout->columns() as $column) {
    foreach ($column->blocks() as $block) {
      if ($block->type() !== 'heading') {
        continue;
      }

      $text = strip_tags($block->text()->value());

      if ($text === '') {
        continue;
      }

      $anchors[] = [
        'level' => $block->level()->or('h2'),
        'text'  => $text,
        'slug'  => Str::slug($text),
      ];
    }
  }
}
?>

<?php if (count($anchors) > 1): ?>
<nav class="page-nav" aria-label="Page sections">
  <ul class="page-nav-list">
    <?php foreach ($anchors as $anchor): ?>
    <li class="page-nav-item page-nav-item--<?= esc($anchor['level'], 'attr') ?>">
      <a href="#<?= esc($anchor['slug'], 'attr') ?>" data-page-nav-link="<?= esc($anchor['slug'], 'attr') ?>"><?= esc($anchor['text']) ?></a>
    </li>
    <?php endforeach ?>
  </ul>
</nav>
<?php endif ?>
