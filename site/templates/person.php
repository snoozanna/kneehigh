<?php
/*
  Templates render the content of your pages.

  They contain the markup together with some control structures
  like loops or if-statements. The `$page` variable always
  refers to the currently active page.

  To fetch the content from each field we call the field name as a
  method on the `$page` object, e.g. `$page->title()`.

  This note template renders a blog article. It uses the `$page->cover()`
  method from the `note.php` page model (/site/models/page.php)

  It also receives the `$tag` variable from its controller
  (/site/controllers/note.php) if a tag filter is activated.

  Snippets like the header and footer contain markup used in
  multiple templates. They also help to keep templates clean.

  More about templates: https://getkirby.com/docs/guide/templates/basics
*/
?>
<?php snippet('header') ?>

<article>

  <?php snippet('intro') ?>
  <div class="grid">

    <!-- Left column -->
    <div class="column" style="--columns: 4">
    <?php if ($cover = $page->cover()): ?>
<a href="<?= $cover->url() ?>" data-lightbox class="img" style="--w:2; --h:1">
  <img src="<?= $cover->crop(1200, 600)->url() ?>" alt="<?= $cover->alt()->esc() ?>">
</a>
<?php endif ?>
<br/>
      <div class="text">

        <?php if ($page->subheading()->isNotEmpty()): ?>
          <h2><?= $page->subheading()->esc() ?></h2>
        <?php endif ?>

        <div class="archive-meta">
        <?php if ($page->roles()->isNotEmpty()): ?>

      <?php
      $options = $page->blueprint()->field('roles')['options'];

      $roles = array_map(function ($value) use ($options) {
          return $options[$value] ?? $value;
      }, $page->roles()->split());
      ?>

      <p>
          <strong><?= implode(', ', $roles) ?></strong>
      </p>

<?php endif ?>
        </div>
        
<br/>
        <?= $page->text()->toBlocks() ?>

      </div>

    </div>

    <!-- Right column -->
    <div class="column" style="--columns: 8">
<h2><strong>Archive Objects</strong></h2>
  <?php if ($archiveObjects->isNotEmpty()): ?>

        <ul class="album-gallery">
        <?php foreach ($archiveObjects as $object): ?>

<?php if ($cover = $object->cover()): ?>
    <li>
        <a href="<?= $object->url() ?>">
            <figure
                class="img"
                style="--w:<?= $cover->width() ?>;--h:<?= $cover->height() ?>"
            >
                <img
                    src="<?= $cover->resize(1200)->url() ?>"
                    alt="<?= $cover->alt()->esc() ?>"
                >
            </figure>

            

            <h2><strong><?= $object->title()->esc() ?></strong></h2>
        </a>
    </li>
<?php endif ?>

<?php endforeach ?>

        </ul>

        <?php else: ?>

<p>No archive objects found.</p>

<?php endif ?>


    </div>

  </div>

</article>

<?php snippet('footer') ?>
