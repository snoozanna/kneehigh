<?php
/*
  Case Study template renders individual case study pages.

  They contain the markup together with some control structures
  like loops or if-statements. The `$page` variable always
  refers to the currently active page.

  To fetch the content from each field we call the field name as a
  method on the `$page` object, e.g. `$page->title()`.

  This template displays the case study content and links to related productions.

  Snippets like the header and footer contain markup used in
  multiple templates. They also help to keep templates clean.

  More about templates: https://getkirby.com/docs/guide/templates/basics
*/
?>
<?php snippet('header') ?>

<article class="note">
  <header class="note-header h1">
    <h1 class="note-title"><?= $page->title()->esc() ?></h1>
    <?php if ($page->subheadline()->isNotEmpty()): ?>
    <p class="note-subheading"><small><?= $page->subheadline()->esc() ?></small></p>
    <?php endif ?>
  </header>
  
  <div class="note text">
    <?= $page->text()->toBlocks() ?>
  </div>

  <!-- Related Productions -->
  <?php if ($relatedProductions->isNotEmpty()): ?>
  <div class="case-study-productions">
    <h2 class="h2"><strong>Related Productions</strong></h2>
    <ul class="album-gallery">
      <?php foreach ($relatedProductions as $production): ?>
        <li>
          <a href="<?= $production->url() ?>">
           
            <h3><?= $production->title()->esc() ?></h3>
          </a>
        </li>
      <?php endforeach ?>
    </ul>
  </div>
  <?php endif ?>

  <footer class="note-footer">
    <?php if ($page->tags()->isNotEmpty()): ?>
    <ul class="note-tags">
      <?php foreach ($page->tags()->split(',') as $tag): ?>
      <li>
        <a href="<?= $page->parent()->url(['params' => ['tag' => trim($tag)]]) ?>"><?= esc(trim($tag)) ?></a>
      </li>
      <?php endforeach ?>
    </ul>
    <?php endif ?>
  </footer>

</article>

<?php snippet('footer') ?>
