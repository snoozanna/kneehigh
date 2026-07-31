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

      <div class="text">

        <?php if ($page->subheading()->isNotEmpty()): ?>
          <h2><?= $page->subheading()->esc() ?></h2>
        <?php endif ?>

        <div class="archive-meta">

          <?php if ($page->date()->isNotEmpty()): ?>
            <p>
              <strong>Year:</strong>
              <?= $page->date()->toDate('Y') ?>
            </p>
          <?php endif ?>

          <?php if ($page->category()->isNotEmpty()): ?>
            <p>
              <strong>Category:</strong>
              <?= $page->category()->esc() ?>
            </p>
          <?php endif ?>

          <?php if ($production = $page->production()->toPage()): ?>
            <p>
              <strong>Production:</strong>
              <a href="<?= $production->url() ?>">
                <?= $production->title()->esc() ?>
              </a>
            </p>
          <?php endif ?>
          <?php if ($page->people()->isNotEmpty()): ?>
    <p class="image-people">
        <strong>People:</strong>

        <?php foreach ($page->people()->toPages() as $person): ?>
            <a href="<?= $person->url() ?>">
                <?= $person->title()->esc() ?>
            </a><?= !$person->isLast() ? ', ' : '' ?>
        <?php endforeach ?>

    </p>
<?php endif ?>
        </div>
            <br/>
        <?= $page->text()->toBlocks() ?>

      </div>

    </div>

    <!-- Right column -->
    <div class="column" style="--columns: 8">

      <?php if ($gallery->isNotEmpty()): ?>

        <ul class="album-gallery">
          <?php foreach ($gallery as $image): ?>
            <li>
              <a href="<?= $image->url() ?>" data-lightbox>
                <figure
                  class="img"
                  style="--w:<?= $image->width() ?>;--h:<?= $image->height() ?>"
                >
                  <img
                    src="<?= $image->resize(1200)->url() ?>"
                    alt="<?= $image->alt()->esc() ?>"
                  >
                </figure>
              </a>
            </li>
          
          <?php endforeach ?>
        </ul>

    

      <?php endif ?>

    </div>

  </div>

</article>

<?php snippet('footer') ?>
