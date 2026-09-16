<?php
/*
  Templates render the content of your pages.

  They contain the markup together with some control structures
  like loops or if-statements. The `$page` variable always
  refers to the currently active page.

  To fetch the content from each field we call the field name as a
  method on the `$page` object, e.g. `$page->title()`.

  This template lists all all the subpages of the `phototography`
  page with title and cover image.

  Snippets like the header and footer contain markup used in
  multiple templates. They also help to keep templates clean.

  More about templates: https://getkirby.com/docs/guide/templates/basics
*/
?>
<?php snippet('header') ?>
<?php snippet('intro') ?>

<div class="studio-list">

  <?php $textureCount = 7 ?>

  <?php foreach ($studios as $studio): ?>

    <?php $texture = 'texture_' . random_int(1, $textureCount) . '.png' ?>
    <?php $rotation = random_int(-30, 30) / 10 ?>

    <article class="note-excerpt studio" style="--studio-texture: url('<?= url('assets/img/textures/' . $texture) ?>'); transform: rotate(<?= $rotation ?>deg)">

      <header class="studio__header">

        <h2 class="note-excerpt-title h2">
          <a href="<?= $studio->url() ?>">
            <?= $studio->headline()->or($studio->title())->esc() ?>:   <?= $studio->description()->esc() ?>
          </a>
        </h2>
        <h2 class="note-excerpt-title h3">
        
          

        </h2>

      

      </header>

      <div class="note text">
        <?= $studio->text()->toBlocks() ?>
      </div>

    </article>
<br/>
  <?php endforeach ?>

</div>
<?php snippet('pagination', ['pagination' => $pagination]) ?>
<?php snippet('footer') ?>