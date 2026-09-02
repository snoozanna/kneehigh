<?php
/*
  Templates render the content of your pages.

  They contain the markup together with some control structures
  like loops or if-statements. The `$page` variable always
  refers to the currently active page.

  To fetch the content from each field we call the field name as a
  method on the `$page` object, e.g. `$page->title()`.

  This template lists all all the subpages of the `productions`
  page with title and cover image.

  Snippets like the header and footer contain markup used in
  multiple templates. They also help to keep templates clean.

  More about templates: https://getkirby.com/docs/guide/templates/basics
*/
?>
<?php snippet('header') ?>
<?php snippet('intro') ?>

<ul class="text>
  <?php foreach ($productions as $production): ?>
    <li>
      <a href="<?= $production->url() ?>">
          <time datetime="<?= $production->date()->toDate('c') ?>">
        <?= $production->date()->toDate('Y') ?>
      </time>
        <?= $production->title()->esc() ?>
      </a>
    
    </li>
  <?php endforeach ?>
</ul>
<?php snippet('choice-navigation') ?>

<?php snippet('footer') ?>
