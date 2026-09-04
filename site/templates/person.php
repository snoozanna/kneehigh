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

<article class="text">

 <header class="h1">
  <h1><?= $page->headline()->or($page->title())->esc() ?></h1>
        <?php if ($page->roles()->isNotEmpty()): ?>

      <?php
      $options = $page->blueprint()->field('roles')['options'];

      $roles = array_map(function ($value) use ($options) {
          return $options[$value] ?? $value;
      }, $page->roles()->split());
      ?>

      <p class="color-grey">
       <?= implode(', ', $roles) ?>
      </p>
<?php endif ?>
</header>
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
        
<br/>
        <?= $page->text()->toBlocks() ?>

      </div>

    </div>

    <!-- Right column -->
    <div class="column" style="--columns: 8">

<!-- Quotes  -->
 <?php if ($quotes->isNotEmpty()): ?>

  <section class="person-quotes">

    <h2><strong>Quotes</strong></h2>

    <?php foreach ($quotes as $quote): ?>
      
        <blockquote>
          "<?= $quote->text()->toBlocks() ?>"
        </blockquote>



<br/>
    <?php endforeach ?>

  </section>

<?php endif ?>
<br/>
<!-- Archive Objects -->
  <?php if ($archiveObjects->isNotEmpty()): ?>
<h2><strong>Archive Objects</strong></h2>
 

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

            

            <h2><strong><?= $object->description()->esc() ?></strong></h2>
        </a>
    </li>
<?php endif ?>

<?php endforeach ?>

        </ul>

        <?php else: ?>


<?php endif ?>


    </div>

  </div>

</article>

<?php snippet('footer') ?>
