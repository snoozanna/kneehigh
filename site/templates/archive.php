<!-- templates/archive.php -->
<?php snippet('header') ?>
<?php snippet('filters', ['items' => $page->children()->listed()]) ?>

<div class="grid" id="archive-grid">
<?php foreach ($page->children()->listed() as $item): ?>
  <?php snippet('card-object', ['item' => $item]) ?>
<?php endforeach ?>
</div>

<?php snippet('footer') ?>